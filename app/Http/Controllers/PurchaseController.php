<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Product;
use App\Models\Utility;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\PurchasePayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\PurchasedProductsVendor;
use Illuminate\Support\Facades\Storage;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $vender = Supplier::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $vender->prepend('Select Vendor', '');
        $status = Purchase::$statues;
        $purchases = Purchase::where('created_by', '=', \Auth::user()->creatorId())->with(['supplier'])->get();


        return view('purchases.index', compact('purchases', 'status','vender'));
    }
    public function product(Request $request)
    {
     // return  $product = Product::find($request->product_id)->product_tax;
        $data['product']     = $product = Product::find($request->product_id);
        $data['unit']        = !empty($product->unit) ? $product->unit->name : '';
        //$data['product_taxes']       = 15;
        $data['taxRate']     = $taxRate = !empty($product->product_tax) ? $product->taxRate($product->product_tax) : 0;
        $data['product_taxes']       = !empty($product->product_tax) ? $product->tax($product->product_tax) : 0;
        $salePrice           = $product->purchase_price;
        $quantity            = 1;
        $taxPrice            = ($taxRate / 100) * ($salePrice * $quantity);
        $data['totalAmount'] = ($salePrice * $quantity);

        return json_encode($data);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
      
        
                $user = Auth::user();

                $store = Store::where('id', $user->current_store)->first();
                $bill_number = "";
               
                $venders=Supplier::where('store_id', $store->id)->get()->pluck('name', 'id');
                $product_services = Product::where('store_id', $store->id)->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                $venders->prepend('Select Vender', '');
                $product_services->prepend('Select Item', '');
                $purchase_number = "pur1000";
                $vendorId = 0;
             
    
    
               
    
                return view('purchases.createnew', compact('venders', 'purchase_number', 'product_services','vendorId'));
            }
     
        
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

      
            $validator = \Validator::make(
                $request->all(), [
                    'vender_id' => 'required',
                    'purchase_date' => 'required',
               
                    'items' => 'required',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $purchase                 = new Purchase();
            $purchase->purchase_id    = $this->purchaseNumber();
            $purchase->supplier_id      = $request->vender_id;
           // $purchase->warehouse_id      = $request->warehouse_id;
            $purchase->date  = $request->purchase_date;
            $purchase->purchase_number   = !empty($request->purchase_number) ? $request->purchase_number : 0;
            $purchase->status         =  0;
            //            $purchase->discount_apply = isset($request->discount_apply) ? 1 : 0;
           // $purchase->category_id    = $request->category_id;
            $purchase->created_by     = \Auth::user()->creatorId();
            $purchase->save();

            $products = $request->items;

            for($i = 0; $i < count($products); $i++)
            {
                // PurchasedProductsVendor same old file PurchasedProducts
                $purchaseProduct              = new PurchasedProductsVendor();
                $purchaseProduct->purchase_id     = $purchase->id;
                $purchaseProduct->product_id  = $products[$i]['item'];
                $purchaseProduct->quantity    = $products[$i]['quantity'];
                $purchaseProduct->tax         = $products[$i]['product_tax'];
            //                $purchaseProduct->discount    = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
                $purchaseProduct->discount    = $products[$i]['discount'];
                $purchaseProduct->price       = $products[$i]['price'];
                $purchaseProduct->description = $products[$i]['description'];
                $purchaseProduct->save();
                /*

                //inventory management (Quantity)
                Utility::total_quantity('plus',$purchaseProduct->quantity,$purchaseProduct->product_id);

                //Product Stock Report
                $type='purchase';
                $type_id = $purchase->id;
                $description=$products[$i]['quantity'].'  '.__(' quantity add in purchase').' '. \Auth::user()->purchaseNumberFormat($purchase->purchase_id);
                Utility::addProductStock( $products[$i]['item'],$products[$i]['quantity'],$type,$description,$type_id);

                //Warehouse Stock Report
                if(isset($products[$i]['item']))
                {
                    Utility::addWarehouseStock( $products[$i]['item'],$products[$i]['quantity'],$request->warehouse_id);
                }
                    */

            }

            return redirect()->route('purchase.index', $purchase->id)->with('success', __('Purchase successfully created.'));
    
    }
    public function destroy(Purchase $purchase)
    {
            if($purchase->created_by == \Auth::user()->creatorId())
            {
                $purchase_products = PurchaseProduct::where('purchase_id',$purchase->id)->get();

                $purchasepayments = $purchase->payments;
                foreach($purchasepayments as $key => $value)
                {
                    $purchasepayment = PurchasePayment::find($value->id)->first();
                    $purchasepayment->delete();
                }

                foreach($purchase_products as $purchase_product)
                {
                    /*
                    $warehouse_qty = WarehouseProduct::where('warehouse_id',$purchase->warehouse_id)->where('product_id',$purchase_product->product_id)->first();

                    $warehouse_transfers = WarehouseTransfer::where('product_id',$purchase_product->product_id)->where('from_warehouse',$purchase->warehouse_id)->get();
                    foreach ($warehouse_transfers as $warehouse_transfer)
                    {
                        $temp = WarehouseProduct::where('warehouse_id',$warehouse_transfer->to_warehouse)->first();
                        if($temp)
                        {
                            $temp->quantity = $temp->quantity - $warehouse_transfer->quantity;
                            if($temp->quantity > 0)
                            {
                                $temp->save();
                            }
                            else
                            {
                                $temp->delete();
                            }

                        }
                    }
                    if(!empty($warehouse_qty))
                    {
                        $warehouse_qty->quantity = $warehouse_qty->quantity - $purchase_product->quantity;
                        if( $warehouse_qty->quantity > 0)
                        {
                            $warehouse_qty->save();
                        }
                        else
                        {
                            $warehouse_qty->delete();
                        }
                    }*/
                    $product_qty = Product::where('id',$purchase_product->product_id)->first();
                    if(!empty($product_qty))
                    {
                        $product_qty->quantity = $product_qty->quantity - $purchase_product->quantity;
                        $product_qty->save();
                    }
                    $purchase_product->delete();

                }

                $purchase->delete();
                PurchasedProductsVendor::where('purchase_id', '=', $purchase->id)->delete();


                return redirect()->route('purchase.index')->with('success', __('Purchase successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
      

    }
    public function createPayment(Request $request, $purchase_id)
    {
        /*
        if(\Auth::user()->can('create payment purchase'))
        {
        */
            $validator = \Validator::make(
                $request->all(), [
                    'date' => 'required',
                    'amount' => 'required',
                    'account_id' => 'required',

                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $purchasePayment                 = new PurchasePayment();
            $purchasePayment->purchase_id        = $purchase_id;
            $purchasePayment->date           = $request->date;
            $purchasePayment->amount         = $request->amount;
            $purchasePayment->account_id     = $request->account_id;
            $purchasePayment->payment_method = 0;
            $purchasePayment->reference      = $request->reference;
            $purchasePayment->description    = $request->description;
            if(!empty($request->add_receipt))
            {
                $fileName = time() . "_" . $request->add_receipt->getClientOriginalName();
                $request->add_receipt->storeAs('uploads/payment', $fileName);
                $purchasePayment->add_receipt = $fileName;
            }
            $purchasePayment->save();

            $purchase  = Purchase::where('id', $purchase_id)->first();
            $due   = $purchase->getDue();
            $total = $purchase->getTotal();

            if($purchase->status == 0)
            {
                $purchase->send_date = date('Y-m-d');
                $purchase->save();
            }

            if($due <= 0)
            {
                $purchase->status = 4;
                $purchase->save();
            }
            else
            {
                $purchase->status = 3;
                $purchase->save();
            }
            $purchasePayment->user_id    = $purchase->vender_id;
            $purchasePayment->user_type  = 'Vender';
            $purchasePayment->type       = 'Partial';
            $purchasePayment->created_by = \Auth::user()->id;
            $purchasePayment->payment_id = $purchasePayment->id;
            $purchasePayment->category   = 'Bill';
            $purchasePayment->account    = $request->account_id;
           // Transaction::addTransaction($purchasePayment);

            $vender = Supplier::where('id', $purchase->supplier_id)->first();

            $payment         = new PurchasePayment();
            $payment->name   = $vender['name'];
            $payment->method = '-';
            $payment->date   = \Auth::user()->dateFormat($request->date);
            $payment->amount = \Auth::user()->priceFormat($request->amount);
            $payment->bill   = 'bill ' . \Auth::user()->purchaseNumberFormat($purchasePayment->purchase_id);

            Utility::userBalance('vendor', $purchase->vender_id, $request->amount, 'debit');

            Utility::bankAccountBalance($request->account_id, $request->amount, 'debit');

            // Send Email
            $setings = Utility::settings();
            if($setings['new_bill_payment'] == 1)
            {

                $vender = Supplier::where('id', $purchase->supplier_id)->first();
                $billPaymentArr = [
                    'vender_name'   => $vender->name,
                    'vender_email'  => $vender->email,
                    'payment_name'  =>$payment->name,
                    'payment_amount'=>$payment->amount,
                    'payment_bill'  =>$payment->bill,
                    'payment_date'  =>$payment->date,
                    'payment_method'=>$payment->method,
                    'company_name'=>$payment->method,

                ];


                $resp = Utility::sendEmailTemplate1('new_bill_payment', [$vender->id => $vender->email], $billPaymentArr);

                return redirect()->back()->with('success', __('Payment successfully added.') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));

            }

    }

    public function sent($id)
    {
       /*
        if(\Auth::user()->can('send purchase'))
        {
        */
        
             $purchase            = Purchase::where('id', $id)->first();
            $purchase->send_date = date('Y-m-d');
            $purchase->status    = 1;
            $purchase->save();

             $vender = Supplier::where('id', $purchase->supplier_id)->first();

            $purchase->name = !empty($vender) ? $vender->name : '';
            $purchase->purchase = \Auth::user()->purchaseNumberFormat($purchase->purchase_id);

            $purchaseId    = Crypt::encrypt($purchase->id);
            $purchase->url = route('purchase.pdf', $purchaseId);

            Utility::userBalance('vendor', $vender->id, $purchase->getTotal(), 'credit');

            $vendorArr = [
                'vender_bill_name' => $purchase->name,
                'vender_bill_number' =>$purchase->purchase,
                'vender_bill_url' => $purchase->url,

            ];
            $resp = \App\Models\Utility::sendEmailTemplate1('vender_bill_sent', [$vender->id => $vender->email], $vendorArr);

            return redirect()->back()->with('success', __('Purchase successfully sent.') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
        }
        /*
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        } }
            */

    public function resent($id)
    {

     
            $purchase = Purchase::where('id', $id)->first();

            $vender = Supplier::where('id', $purchase->vender_id)->first();

            $purchase->name = !empty($vender) ? $vender->name : '';
            $purchase->purchase = \Auth::user()->purchaseNumberFormat($purchase->purchase_id);

            $purchaseId    = Crypt::encrypt($purchase->id);
            $purchase->url = route('purchase.pdf', $purchaseId);

        return redirect()->back()->with('success', __('Bill successfully sent.'));
        
 

    }
    
    public function purchase($purchase_id)
    {

        $settings = Utility::settings();
        $purchaseId   = Crypt::decrypt($purchase_id);

        $purchase  = Purchase::where('id', $purchaseId)->first();
        $data  = DB::table('settings');
        $data  = $data->where('created_by', '=', $purchase->created_by);
        $data1 = $data->get();

        foreach($data1 as $row)
        {
            $settings[$row->name] = $row->value;
        }

        $vendor = $purchase->supplier;

        $totalTaxPrice = 0;
        $totalQuantity = 0;
        $totalRate     = 0;
        $totalDiscount = 0;
        $taxesData     = [];
        $items         = [];

        foreach($purchase->items as $product)
        {
            $item              = new \stdClass();
            $item->name        = !empty($product->product) ? $product->product->name : '';
            $item->quantity    = $product->quantity;
            $item->tax         = $product->tax;
            $item->discount    = $product->discount;
            $item->price       = $product->price;
            $item->description = $product->description;

            $totalQuantity += $item->quantity;
            $totalRate     += $item->price;
            $totalDiscount += $item->discount;

            $taxes     = Utility::tax($product->tax);
            $itemTaxes = [];
            if(!empty($item->tax))
            {
                foreach($taxes as $tax)
                {
                    $taxPrice      = Utility::taxRate($tax->rate, $item->price, $item->quantity,$item->discount);
                    $totalTaxPrice += $taxPrice;

                    $itemTax['name']  = $tax->name;
                    $itemTax['rate']  = $tax->rate . '%';
                    //$itemTax['price'] = Utility::priceFormat($settings, $taxPrice);
                    $itemTax['price'] =  $taxPrice;

                    $itemTax['tax_price'] =$taxPrice;
                    $itemTaxes[]      = $itemTax;


                    if(array_key_exists($tax->name, $taxesData))
                    {
                        $taxesData[$tax->name] = $taxesData[$tax->name] + $taxPrice;
                    }
                    else
                    {
                        $taxesData[$tax->name] = $taxPrice;
                    }

                }

                $item->itemTax = $itemTaxes;
            }
            else
            {
                $item->itemTax = [];
            }
            $items[] = $item;
        }

        $purchase->itemData      = $items;
        $purchase->totalTaxPrice = $totalTaxPrice;
        $purchase->totalQuantity = $totalQuantity;
        $purchase->totalRate     = $totalRate;
        $purchase->totalDiscount = $totalDiscount;
        $purchase->taxesData     = $taxesData;


            //        $logo         = asset(Storage::url('uploads/logo/'));
            //        $company_logo = Utility::getValByName('company_logo_dark');
            //        $img          = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png'));

        $logo         = asset(Storage::url('uploads/logo/'));
        $company_logo = Utility::getValByName('company_logo_dark');
        $purchase_logo = Utility::getValByName('purchase_logo');
        if(isset($purchase_logo) && !empty($purchase_logo))
        {
            $img = Utility::get_file('purchase_logo/') . $purchase_logo;
        }
        else{
            $img          = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png'));
        }

        if($purchase)
        {
            $color      = '#' . $settings['purchase_color'];
            $font_color = Utility::getFontColor($color);

            return view('purchases.templates.' . $settings['purchase_template'], compact('purchase', 'color', 'settings', 'vendor', 'img', 'font_color'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }
    public function payment($purchase_id)
    {
       
            $purchase    = Purchase::where('id', $purchase_id)->first();
            $venders = Supplier::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            //$categories = ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
           // $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $accounts   = ['bank','cash'];

            return view('purchases.payment', compact('venders', 'accounts', 'purchase'));
        
      
    }

    function purchaseNumber()
    {
        $latest = Purchase::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->purchase_id + 1;
    }

    /**
     * Display the specified resource.
     */
    public function show( $ids)
    {
        //
        try {
            $id       = Crypt::decrypt($ids);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Purchase Not Found.'));
        }


        $id   = Crypt::decrypt($ids);
        $purchase = Purchase::find($id);

        if($purchase->created_by == \Auth::user()->creatorId())
        {

            $purchasePayment = PurchasePayment::where('purchase_id', $purchase->id)->first();
            $vendor      = $purchase->supplier;
            $iteams      = $purchase->items;

            return view('purchases.view', compact('purchase', 'vendor', 'iteams', 'purchasePayment'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    
    public function purchaseLink($purchaseId)
    {
        try {
            $id       = Crypt::decrypt($purchaseId);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Purchase Not Found.'));
        }

        $id             = Crypt::decrypt($purchaseId);
        $purchase       = Purchase::find($id);

        if(!empty($purchase))
        {
            $user_id        = $purchase->created_by;
            $user           = User::find($user_id);
            $purchasePayment = PurchasePayment::where('purchase_id', $purchase->id)->first();
            $vendor = $purchase->vender;
            $iteams   = $purchase->items;

            return view('purchase.customer_bill', compact('purchase', 'vendor', 'iteams','purchasePayment','user'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */

}
