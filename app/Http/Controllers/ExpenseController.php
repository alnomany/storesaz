<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\User;
use App\Models\Store;

use App\Models\Product;
use App\Models\Utility;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Supplier;
use App\Models\BankAccount;
use App\Models\BillAccount;
use Illuminate\Support\Facades\Crypt;

use App\Models\BillPayment;
use App\Models\BillProduct;
use App\Models\ProductCategorie;


use Illuminate\Http\Request;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    //
    
    public function index(Request $request)
    {


            $vender = Supplier::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $vender->prepend('Select Vendor', '');

           $category =['','مصروفات','بنزين'];
           // $category->prepend('Select Category', '');

            $status = Bill::$statues;

            $query = Bill::where('type', '=', 'Expense')
                ->where('created_by', '=', \Auth::user()->creatorId());
            if (!empty($request->vender)) {
                $query->where('vender_id', '=', $request->vender);
            }
            if (count(explode('to', $request->bill_date)) > 1) {
                $date_range = explode(' to ', $request->bill_date);
                $query->whereBetween('bill_date', $date_range);
            } elseif (!empty($request->bill_date)) {
                $date_range = [$request->date, $request->bill_date];
                $query->whereBetween('bill_date', $date_range);
            }

            if (!empty($request->category)) {
                $query->where('category_id', '=', $request->category);
            }

            $expenses = $query->with(['category'])->get();

            return view('expense.index', compact('expenses', 'vender', 'status', 'category'));
      
    }
    public function expenseNumber()
    {
        $latest = Bill::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 'Expense')->latest()->first();
        if (!$latest) {
            return 1;
        }

        return $latest->bill_id + 1;
    }
    public function employee(Request $request)
    {
        $employee = User::where('id', '=', $request->id)->first();

        return view('expense.employee_detail', compact('employee'));
    }

    public function vender(Request $request)
    {
        $vender = Supplier::where('id', '=', $request->id)->first();

        return view('expense.vender_detail', compact('vender'));
    }
    public function customer(Request $request)
    {
        $customer = Customer::where('id', '=', $request->id)->first();
        return view('expense.customer_detail', compact('customer'));
    }
    
    public function product(Request $request)
    {
        $data['product'] = $product = Product::find($request->product_id);
        $data['unit'] = !empty($product->unit) ? $product->unit->name : '';
        $data['taxRate'] = $taxRate = !empty($product->tax_id) ? $product->taxRate($product->tax_id) : 0;
        $data['taxes'] = !empty($product->tax_id) ? $product->tax($product->tax_id) : 0;
        $salePrice = $product->purchase_price;
        $quantity = 1;
        $taxPrice = ($taxRate / 100) * ($salePrice * $quantity);
        $data['totalAmount'] = ($salePrice * $quantity);

        return json_encode($data);
    }

    public function create1($Id)
    {

       $category =['','مصروفات','بنزين'];

       $category = ProductCategorie::where('created_by', '=', \Auth::user()->creatorId())->get()
       ->whereNotIn('type', [' ', 'income'])
       ->pluck('name', 'id');

            $expense_number = \Auth::user()->expenseNumberFormat($this->expenseNumber());

          
            $user = \Auth::user();
            $store_id = Store::where('id', $user->current_store)->first();
             $employees = User::where('created_by','=',\Auth::user()->creatorId())->where('current_store',\Auth::user()->current_store)->get()->pluck('name', 'id');

            $employees->prepend('Select Employee', '');

            $customers = Customer::where('store_id', $store_id->id)->get()->pluck('name', 'id');
            $customers->prepend('Select Customer', '');

            $venders = Supplier::where('store_id', $store_id->id)->get()->pluck('name', 'id');
            $venders->prepend('Select Vender', '');

            $product_services = Product::where('store_id', $store_id->id)->get()->pluck('name', 'id');
            $product_services->prepend('Select Item', '');

            $chartAccounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                ->where('created_by', \Auth::user()->creatorId())->get()
                ->pluck('code_name', 'id');
            $chartAccounts->prepend('Select Account', '');

            $subAccounts = ChartOfAccount::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name', 'chart_of_account_parents.account');
            $subAccounts->leftjoin('chart_of_account_parents', 'chart_of_accounts.parent', 'chart_of_account_parents.id');
            $subAccounts->where('chart_of_accounts.parent', '!=', 0);
            $subAccounts->where('chart_of_accounts.created_by', \Auth::user()->creatorId());
            $subAccounts = $subAccounts->get()->toArray();

           $chartAccounts =[0=>"الاصول",1=>"المرتبات"];
            $accounts = BankAccount::select('*', DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))
                ->where('created_by', \Auth::user()->creatorId())
                ->get()->pluck('name', 'id');
                

            return view('expense.create', compact('employees', 'customers', 'venders', 'expense_number', 'product_services', 'category', 'Id', 'accounts','chartAccounts'));
       
    }
    public function store(Request $request)
    {
        //validate
            $validator = \Validator::make(
                $request->all(), [
                    'payment_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages3 = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages3->first());
            }
            if (!empty($request->items) && empty($request->items[0]['item']) && empty($request->items[0]['chart_account_id']) && empty($request->items[0]['amount'])) {
                $itemValidator = \Validator::make(
                    $request->all(), [
                        'item' => 'required',
                    ]
                );
                if ($itemValidator->fails()) {
                    $messages1 = $itemValidator->getMessageBag();
                    return redirect()->back()->with('error', $messages1->first());
                }
            }
            if (!empty($request->items) && empty($request->items[0]['chart_account_id']) && !empty($request->items[0]['amount'])) {
                $accountValidator = \Validator::make(
                    $request->all(), [
                        'chart_account_id' => 'required',
                    ]
                );
                if ($accountValidator->fails()) {
                    $messages2 = $accountValidator->getMessageBag();
                    return redirect()->back()->with('error', $messages2->first());
                }

            }
            //#second save to bill
            $expense = new Bill();
            $expense->bill_id = $this->expenseNumber();
            if ($request->type == 'employee') {
                $expense->vender_id = $request->employee_id;
            } elseif ($request->type == 'customer') {
                $expense->vender_id = $request->customer_id;
            } else {
                $expense->vender_id = $request->vender_id;
            }
            $expense->bill_date = $request->payment_date;
            $expense->status = 4;
            $expense->type = 'Expense';
            $expense->user_type = $request->type;
            $expense->due_date = $request->payment_date;
            $expense->category_id = !empty($request->category_id) ? $request->category_id : 0;
            $expense->order_number = 0;
            $expense->created_by = \Auth::user()->creatorId();
            $expense->save();
//#3 save billProduct 
            $products = $request->items;

            $total_amount = 0;

            for ($i = 0; $i < count($products); $i++) {
                if (!empty($products[$i]['item'])) {
                    $expenseProduct = new BillProduct();
                    $expenseProduct->bill_id = $expense->id;
                    $expenseProduct->product_id = $products[$i]['item'];
                    $expenseProduct->quantity = $products[$i]['quantity'];
                    $expenseProduct->tax = $products[$i]['tax'];
                    $expenseProduct->discount = $products[$i]['discount'];
                    $expenseProduct->price = $products[$i]['price'];
                    $expenseProduct->description = $products[$i]['description'];
                    $expenseProduct->save();
                }
//#4 save billAccount
                $expenseTotal = 0;
                if (!empty($products[$i]['chart_account_id'])) {
                    $expenseAccount = new BillAccount();
                    $expenseAccount->chart_account_id = $products[$i]['chart_account_id'];
                    $expenseAccount->price = $products[$i]['amount'] ? $products[$i]['amount'] : 0;
                    $expenseAccount->description = $products[$i]['description'];
                    $expenseAccount->type = 'Bill';
                    $expenseAccount->ref_id = $expense->id;
                    $expenseAccount->save();
                    $expenseTotal = $expenseAccount->price;
                }

                //inventory management (Quantity)
                if (!empty($expenseProduct)) {
                    Utility::total_quantity('plus', $expenseProduct->quantity, $expenseProduct->product_id);
                }

                //Product Stock Redashboardrt
                if (!empty($products[$i]['item'])) {
                    $type = 'bill';
                    $type_id = $expense->id;
                    $description = $products[$i]['quantity'] . '  ' . __('quantity purchase in bill') . ' ' . \Auth::user()->expenseNumberFormat($expense->bill_id);
                    Utility::addProductStock($products[$i]['item'], $products[$i]['quantity'], $type, $description, $type_id);
                    $total_amount += ($expenseProduct->quantity * $expenseProduct->price) + $expenseTotal;

                }

            }

            $expensePayment = new BillPayment();
            $expensePayment->bill_id = $expense->id;
                        $expensePayment->date = $request->payment_date;
            $expensePayment->amount = $request->totalAmount;
            $expensePayment->account_id = $request->account_id;
            $expensePayment->payment_method = 0;
            $expensePayment->reference = 'NULL';
            $expensePayment->description = 'NULL';
            $expensePayment->add_receipt = 'NULL';
            $expensePayment->save();

            if (!empty($request->chart_account_id)) {

                $expenseaccount = Product::find($request->category_id);
                $chart_account = ChartOfAccount::find($expenseaccount->chart_account_id);
                $expenseAccount = new BillAccount();
                $expenseAccount->chart_account_id = $chart_account['id'];
                $expenseAccount->price = $total_amount;
                $expenseAccount->description = $request->description;
                $expenseAccount->type = 'Bill Category';
                $expenseAccount->ref_id = $expense->id;
                $expenseAccount->save();
            }

            Utility::bankAccountBalance($request->account_id, $request->totalAmount, 'debit');

            Utility::updateUserBalance('vendor', $expense->vender_id, $request->totalAmount, 'credit');

            //For Notification
            $setting = Utility::settings(\Auth::user()->creatorId());

            if ($request->type == 'employee') {
                $user = User::find($request->employee_id);
                //


                //
                $contact = $user->phone;
            } else if ($request->type == 'customer') {
                $user = Customer::find($request->customer_id);
                $contact = $user->contact;

            } else {
                $user = Supplier::find($request->vender_id);
                $contact = $user->contact;
            }

            $bill_products = BillProduct::where('bill_id', $expense->id)->get();
            foreach ($bill_products as $bill_product) {
                $product = Product::find($bill_product->product_id);
                $totalTaxPrice = 0;
                if($bill_product->tax != null)
                {
                    $taxes = \App\Models\Utility::tax($bill_product->tax);
                    foreach ($taxes as $tax) {
                        $taxPrice = \App\Models\Utility::taxRate($tax->rate, $bill_product->price, $bill_product->quantity, $bill_product->discount);
                        $totalTaxPrice += $taxPrice;
                    }
                }

                $itemAmount = ($bill_product->price * $bill_product->quantity) - ($bill_product->discount) + $totalTaxPrice;

                $data = [
                    'account_id' => $product->expense_chartaccount_id,
                    'transaction_type' => 'Debit',
                    'transaction_amount' => $itemAmount,
                    'reference' => 'Expense',
                    'reference_id' => $expense->id,
                    'reference_sub_id' => $product->id,
                    'date' => $expense->bill_date,
                ];
                Utility::addTransactionLines($data);
            }

            $bill_accounts = BillAccount::where('ref_id', $expense->id)->get();
            foreach ($bill_accounts as $bill_product) {
                $data = [
                    'account_id' => $bill_product->chart_account_id,
                    'transaction_type' => 'Debit',
                    'transaction_amount' => $bill_product->price,
                    'reference' => 'Expense Account',
                    'reference_id' => $bill_product->ref_id,
                    'reference_sub_id' => $bill_product->id,
                    'date' => $expense->bill_date,
                ];
                Utility::addTransactionLines($data);
            }

            $billPayments = BillPayment::where('bill_id', $expense->id)->get();
            foreach ($billPayments as $billPayment) {
                $accountId = BankAccount::find($billPayment->account_id);

                $data = [
                    'account_id' => $accountId->account_id,
                    'transaction_type' => 'Debit',
                    'transaction_amount' => $billPayment->amount,
                    'reference' => 'Expense Payment',
                    'reference_id' => $expense->id,
                    'reference_sub_id' => $billPayment->id,
                    'date' => $billPayment->date,
                ];
                Utility::addTransactionLines($data);
            }

            $expenseNotificationArr = [
                'expense_number' => \Auth::user()->expenseNumberFormat($expense->bill_id),
                'user_name' => \Auth::user()->name,
                'bill_date' => $expense->bill_date,
                'bill_due_date' => $expense->due_date,
                'vendor_name' => $user->name,
            ];
/*
            //Slack Notification
            if (isset($setting['bill_notification']) && $setting['bill_notification'] == 1) {
                Utility::send_slack_msg('new_bill', $expenseNotificationArr);
            }
            //Telegram Notification
            if (isset($setting['telegram_bill_notification']) && $setting['telegram_bill_notification'] == 1) {
                Utility::send_telegram_msg('new_bill', $expenseNotificationArr);
            }
            //Twilio Notification
            if (isset($setting['twilio_bill_notification']) && $setting['twilio_bill_notification'] == 1) {
                Utility::send_twilio_msg($contact, 'new_bill', $expenseNotificationArr);
            }

            //webhook
            $module = 'New Bill';
            $webhook = Utility::webhookSetting($module);
            if ($webhook) {
                $parameter = json_encode($expense);
                $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);

                if ($status == true) {
                    return redirect()->route('expense.index', $expense->id)->with('success', __('Expense successfully created.'));
                } else {
                    return redirect()->back()->with('error', __('Webhook call failed.'));
                }
            }
                */

            return redirect()->route('expense.index', $expense->id)->with('success', __('Expense successfully created.'));
        } 
        
    public function show($ids)
    {

      

            $id = Crypt::decrypt($ids);

           // $expense = Bill::with('debitNote', 'payments.bankAccount', 'items.product.unit')->find($id);
            $expense = Bill::with('debitNote', 'payments.bankAccount')->find($id);


            if (!empty($expense) && $expense->created_by == Auth::user()->id) {
                $expensePayment = BillPayment::where('bill_id', $expense->id)->first();

                if ($expense->user_type == 'employee') {
                    $user = $expense->employee;
                } elseif ($expense->user_type == 'customer') {
                    $user = $expense->customer;
                } else {
                    $user = $expense->vender;
                }

                $item = $expense->items;
                $accounts = $expense->accounts;
                $items = [];
                if (!empty($item) && count($item) > 0) {
                    foreach ($item as $k => $val) {
                        if (!empty($accounts[$k])) {
                            $val['chart_account_id'] = $accounts[$k]['chart_account_id'];
                            $val['account_id'] = $accounts[$k]['id'];
                            $val['amount'] = $accounts[$k]['price'];
                        }
                        $items[] = $val;
                    }
                } else {

                    foreach ($accounts as $k => $val) {
                        $val1['chart_account_id'] = $accounts[$k]['chart_account_id'];
                        $val1['account_id'] = $accounts[$k]['id'];
                        $val1['amount'] = $accounts[$k]['price'];
                        $items[] = $val1;

                    }
                }

                return view('expense.view', compact('expense', 'user', 'items', 'expensePayment'));
            } 
        }
    }
    



