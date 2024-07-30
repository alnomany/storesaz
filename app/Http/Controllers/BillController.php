<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BillController extends Controller
{
    //
    public function create($vendorId)
    {

        /*
            $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'bill')->get();
            $category     = ProductServiceCategory::where('created_by', Auth::user()->creatorId())
                ->whereNotIn('type', ['product & service', 'income',])
                ->get()->pluck('name', 'id');
            $category->prepend('Select Category', '');
            */
            $user = Auth::user();

            $store = Store::where('id', $user->current_store)->first();
            $bill_number = \Auth::user()->billNumberFormat($this->billNumber());
           
                $venders=Supplier::where('store_id', $store->id)->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                $product_services = Product::where('store_id', $store->id)->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $venders->prepend('Select Vender', '');

            $product_services->prepend('Select Item', '');

            $chartAccounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                ->where('created_by', \Auth::user()->creatorId())->get()
                ->pluck('code_name', 'id');
            $chartAccounts->prepend('Select Account', '');

            $subAccounts = ChartOfAccount::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name' , 'chart_of_account_parents.account');
            $subAccounts->leftjoin('chart_of_account_parents', 'chart_of_accounts.parent', 'chart_of_account_parents.id');
            $subAccounts->where('chart_of_accounts.parent', '!=', 0);
            $subAccounts->where('chart_of_accounts.created_by', \Auth::user()->creatorId());
            $subAccounts = $subAccounts->get()->toArray();

            return view('bill.create', compact('venders', 'bill_number', 'product_services',  'vendorId','chartAccounts' , 'subAccounts'));
  
    }
    public function vender(Request $request)
    {
        $vender = Supplier::where('id', '=', $request->id)->first();

        return view('bill.vender_detail', compact('vender'));
    }
}
