<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VenderController extends Controller
{
    //
    
    public function index()
    {
      /*  if(\Auth::user()->can('manage vender'))
        {
            $venders = Vender::where('created_by', \Auth::user()->creatorId())->get();

            return view('vender.index', compact('venders'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
            */
            $user = Auth::user();

            $store_id = Store::where('id', $user->current_store)->first();
    
            $suppliers = Supplier::where('store_id', $store_id->id)->get();
            return view('supplier.index', compact('suppliers'));
    }


    public function create()
    {
        $user = Auth::user();

        $store_id = Store::where('id', $user->current_store)->first();

        $suppliers = Supplier::where('store_id', $store_id->id)->get();

        return view('supplier.create',compact('suppliers'));

       /* if(\Auth::user()->can('create vender'))
        {

            return view('vender.create', compact('customFields'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
            */
    }


    public function store(Request $request)
    {
            // Retrieve all input data
    $data = $request->all();
    $supplier = Supplier::create($data);

// Create a new supplier and store it in the database
return redirect()->back();

    
    }

}
