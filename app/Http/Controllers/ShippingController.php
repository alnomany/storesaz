<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Shipping;
use App\Models\Store;
use App\Models\Plan;
use App\Imports\ShippingImport;
use App\Exports\ShippingExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $plan = Plan::find(\Auth::user()->plan);
        if(\Auth::user()->can('Manage Shipping') && $plan->shipping_method == 'on'){
            $user = \Auth::user()->current_store;
            $store = Store::where('id',$user)->where('is_store_enabled', '1')->first();


            $shippings = Shipping::where('store_id', $user)->where('created_by', \Auth::user()->creatorId())->get();
            $locations = Location::where('store_id', $user)->where('created_by', \Auth::user()->creatorId())->get();

    
            return view('shipping.index', compact('shippings', 'locations','store'));
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(\Auth::user()->can('Create Shipping')){
            $user      = \Auth::user()->current_store;
            $locations = Location::where('store_id', $user)->get()->pluck('name', 'id');

            return view('shipping.create', compact('locations'));
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(\Auth::user()->can('Create Shipping')){
            $this->validate(
                $request, [
                            'name' => 'required|max:40',
                            'price' => 'required|numeric',
                        ]
            );

            $shipping              = new Shipping();
            $shipping->name        = $request->name;
            $shipping->price       = $request->price;
            $shipping->location_id = implode(',', $request->location);
            $shipping->store_id    = \Auth::user()->current_store;
            $shipping->created_by  = \Auth::user()->creatorId();
            $shipping->save();

            return redirect()->back()->with('success', __('Shipping added!'));
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Shipping $shipping
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Shipping $shipping)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Shipping $shipping
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Shipping $shipping)
    {
        if(\Auth::user()->can('Edit Shipping')){
            $store_id = Auth::user()->current_store;

            $locations = Location::where('store_id', $store_id)->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            
            return view('shipping.edit', compact('shipping', 'locations'));
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Shipping $shipping
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shipping $shipping)
    {
        if(\Auth::user()->can('Edit Shipping')){
            $this->validate(
                $request, [
                            'name' => 'required|max:40',
                            'price' => 'required|numeric',
                        ]
            );

            $shipping->name        = $request->name;
            $shipping->price       = $request->price;
            $shipping->location_id = implode(',', $request->location);
            $shipping->created_by  = \Auth::user()->creatorId();
            $shipping->save();

            return redirect()->back()->with('success', __('Shipping Updated!'));
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Shipping $shipping
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shipping $shipping)
    {
        if(\Auth::user()->can('Delete Shipping')){
            $shipping->delete();

            return redirect()->back()->with('success', __('Shipping Deleted!'));
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

     public function fileExport()
    {

        $name = 'Shipping_' . date('Y-m-d i:h:s');
        $data = Excel::download(new ShippingExport(), $name . '.xlsx');


        return $data;
    }

     public function fileImportExport()
    {
        if(\Auth::user()->can('Create Shipping')){
            return view('shipping.import');
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

     public function fileImport(Request $request)
    {
        if(\Auth::user()->can('Create Shipping')){
            $rules = [
                'file' => 'required|mimes:csv,txt,xlsx',
            ];
            $user     = \Auth::user();
            $store_id = Store::where('id', $user->current_store)->first();


            $validator = \Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $shippings = (new ShippingImport())->toArray(request()->file('file'))[0];

            $totalshipping = count($shippings) - 1;

            $locationArray    = [];
            $shippingArray    = [];
            for($i = 1; $i <= count($shippings) - 1; $i++)
            {
                $shipping = $shippings[$i];
                $locationbyname = Location::where('name', $shipping[0])->first();
                $shippingbyname=Shipping::where('name',$shipping[1])->first();

                if(!empty($shippingbyname)&&!empty($locationbyname))
                {
                    $locationData = $locationbyname;
                    $shippingData = $shippingbyname;
                }
                else
                {
                    $locationData = new Location();
                    $shippingData = new Shipping();

                }

                $locationData->name         = $shipping[0];
                $locationData->store_id     = $store_id->id;
                $locationData->created_by    = \Auth::user()->creatorId();

                $shippingData->name         = $shipping[1];
                $shippingData->price          = $shipping[2];
                $shippingData->store_id = $store_id->id;
                $shippingData->created_by        = \Auth::user()->creatorId();



                if(empty($locationData)&& empty($shippingData))
                {
                    $locationArray[] = $locationData;
                    $shippingArray[] = $shippingData;
                }
                else
                {
                    $locationData->save();
                    $shippingData->save();
                }
            }

            $locationRecord = [];
            $shippingRecord = [];
            if(empty($locationArray)&& empty($shippingArray))
            {
                $data['status'] = 'success';
                $data['msg']    = __('Record successfully imported');
            }
            else
            {
                $data['status'] = 'error';
                $data['msg']    = count($locationArray) . ' ' . __('Record imported fail out of' . ' ' . $totalshipping . ' ' . 'record').' and '.count($shippingArray) . ' ' . __('Record imported fail out of' . ' ' . $totalshipping . ' ' . 'record');


                foreach($locationArray as $locationData)
                {

                    $locationRecord[] = implode(',', $locationData);

                }

                foreach($shippingArray as $shippingData)
                {

                    $shippingRecord[] = implode(',', $shippingData);

                }

                \Session::put('locationArray', $locationRecord);
                \Session::put('shippingArray', $shippingRecord);
            }

            return redirect()->back()->with($data['status'], $data['msg']);
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
