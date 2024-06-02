<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Mail\OrderDeliveredMail;
use App\Mail\OrderMail;
use App\Models\Order;
use App\Exports\OrderExport;
use App\Models\Store;
use App\Models\UserDetail;
use App\Models\Utility;
use App\Models\Customer;
use App\Models\Product;
use App\Models\PurchasedProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('Manage Orders')){
            if (Auth::user()->type == 'super admin') {
                $user  = \Auth::user();
                $store = Store::where('id', $user->current_store)->first();
    
                $orders = Order::orderBy('id', 'DESC')->get();
            } else {
                $user  = \Auth::user();
                $store = Store::where('id', $user->current_store)->first();
    
                $orders = Order::orderBy('id', 'DESC')->where('user_id', $store->id)->get();
            }
            return view('orders.index', compact('orders'));
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
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Order $order
     *
     * @return \Illuminate\Http\Response
     */
    public function show($order)
    {
        if(\Auth::user()->can('Show Orders')){
            try{
                $id    = Crypt::decrypt($order);
                $order = Order::find($id);
                $store = Store::where('id', $order->user_id)->first();
                $user_details = UserDetail::where('id', $order->user_address_id)->first();
                if (!empty($order->shipping_data)) {
                    $shipping_data = json_decode($order->shipping_data);
                    $location_data = Location::where('id', $shipping_data->location_id)->first();
                } else {
                    $shipping_data = '';
                    $location_data = '';
                }
                $order_products = json_decode($order->product);
                $sub_total      = 0;
                if (!empty($order_products)) {
                    $grand_total = 0;
                    $total_taxs  = 0;
                    foreach ($order_products as $product) {
                        if (isset($product->variant_id) && $product->variant_id != 0) {
                            if (!empty($product->tax)) {
                                foreach ($product->tax as $tax) {
                                    $sub_tax    = ($product->variant_price * $product->quantity * $tax->tax) / 100;
                                    $total_taxs += $sub_tax;
                                }
                            }
        
                            $totalprice  = $product->variant_price * $product->quantity + $total_taxs;
                            $subtotal    = $product->variant_price * $product->quantity;
                            $sub_total   += $subtotal;
                            $grand_total += $totalprice;
                        } else {
                            if (!empty($product->tax)) {
                                foreach ($product->tax as $tax) {
                                    $sub_tax    = ($product->price * $product->quantity * $tax->tax) / 100;
                                    $total_taxs += $sub_tax;
                                }
                                
                            }
                            // else{
                            //     $total_taxs = 0;
                            // }
                            $totalprice  = $product->price * $product->quantity + $total_taxs;
                            $subtotal    = $product->price * $product->quantity;
                            $sub_total   += $subtotal;
                            // $grand_total += $totalprice;
                            $grand_total = $sub_total + $total_taxs;
                        }
                    
                    }
                    if (!empty($order->coupon_json)) {
                        $coupon = json_decode($order->coupon_json);
                    }
                    if (!empty($order->discount_price)) {
                        $discount_price = $order->discount_price;
                    } else {
                        $discount_price = '';
                    }
                    $discount_value = 0;
                    if (!empty($coupon)) {
                        if ($coupon->enable_flat == 'on') {
                            $discount_value = $coupon->flat_discount;
                        } else {
                            $discount_value = ($grand_total / 100) * $coupon->discount;
                        }
                    }
                }
                $store_payment_setting = \Utility::getPaymentSetting($store->id);
                $order_id              = Crypt::encrypt($order->id);
        
        
                return view('orders.view', compact('store_payment_setting', 'discount_price', 'order', 'store', 'grand_total', 'order_products', 'sub_total', 'total_taxs', 'user_details', 'order_id', 'shipping_data', 'location_data', 'discount_value'));
            }
            catch(\Exception $e){
                return redirect()->back()->with('error','Invalid Url');
            }
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Order $order
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Order $order
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $store = Store::where('id', $order->user_id)->first();
        $order_email = $order->email;

        //  order is Cancel
        if($request->delivered == 'Cancel Order' && $order['status'] != "Cancel Order")
        {
            $Products_order = json_decode($order->product);

            foreach($Products_order as $PurchasedProduct)
            {
                $product = Product::where('id',$PurchasedProduct->product_id)->first();
                $product->quantity = $product->quantity + $PurchasedProduct->quantity;
                $product->save();
            }
        }

        // order is delivered
        if($request->delivered == 'delivered' && $order['status'] == "Cancel Order" )
        {
            $Products_order = json_decode($order->product);

            foreach($Products_order as $PurchasedProduct)
            {
                $product = Product::where('id',$PurchasedProduct->product_id)->first();
                $product->quantity = $product->quantity - $PurchasedProduct->quantity;
                $product->save();
            }
        }
        $order['status'] = $request->delivered;
        $order->update();
        //webhook
        $module = 'Status Change';
        $store_id = \Auth::user()->current_store;
        $webhook =  Utility::webhook($module, $store_id);
            if ($webhook) {
                $parameter = json_encode($order->product);
                // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
                if ($status != true) {
                    $msgs  = 'Webhook call failed.';
                }
            }

        // if (isset($store->mail_driver) && !empty($store->mail_driver))
        // {
            $dArr  = [
                'order_name' => $order['name'],
                'order_status' => $order['status']
            ];

            try
            {
                $order_id = Crypt::encrypt($order->id);
                $resp  = Utility::sendEmailTemplate('Status Change', $order_email, $dArr, $store, $order_id);
               
            }
            catch(\Exception $e)
            {
                // dd($e);
            }
        // }
        $order = Crypt::encrypt($order->id);
        if (isset($store->is_twilio_enabled) && $store->is_twilio_enabled == "on")
        {
            $order = Order::find(Crypt::decrypt($order));
            $customer = Customer::where('id', $order->customer_id)->first();
            Utility::status_change_customer($order, $customer, $store);
        }

        $return =  response()->json(
            [
                'success' => __('Successfully Updated.') . ((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''),
            ]
        );
        return $return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Order $order
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        if(\Auth::user()->can('Delete Orders')){
            //  order is Cancel
            if($order->status != 'Cancel Order')
            {
                $Products_order = json_decode($order->product);
                foreach($Products_order as $PurchasedProduct)
                {
                    
                    $product = Product::where('id',$PurchasedProduct->product_id)->first();
                    $product->quantity = $product->quantity + $PurchasedProduct->quantity;
                    $product->save();
                }

            }
            $order->delete();

            return redirect()->back()->with(
                'success',
                __('Order Deleted!')
            );
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function receipt($id)
    {
        $order = Order::find($id);
        $store = Store::where('id', $order->user_id)->first();

        if (!empty($order->shipping_data)) {
            $shipping_data = json_decode($order->shipping_data);
            $location_data = Location::where('id', $shipping_data->location_id)->first();
        } else {
            $shipping_data = '';
            $location_data = '';
        }

        $user_details = UserDetail::where('id', $order->user_address_id)->first();

        $order_products = json_decode($order->product);
        $sub_total      = 0;
        if (!empty($order_products)) {
            $grand_total = 0;
            $total_taxs  = 0;
            foreach ($order_products as $k => $product) {
                if (isset($product->variant_id) && $product->variant_id != 0) {
                    if (!empty($product->tax)) {
                        foreach ($product->tax as $tax) {
                            $sub_tax    = ($product->variant_price * $product->quantity * $tax->tax) / 100;
                            $total_taxs += $sub_tax;
                        }
                    }
                    $totalprice  = $product->variant_price * $product->quantity + $total_taxs;
                    $subtotal    = $product->variant_price * $product->quantity;
                    $sub_total   += $subtotal;
                    $grand_total += $totalprice;
                } else {
                    if (!empty($product->tax)) {
                        foreach ($product->tax as $tax) {
                            $sub_tax    = ($product->price * $product->quantity * $tax->tax) / 100;
                            $total_taxs += $sub_tax;
                        }
                    }

                    $totalprice  = $product->price * $product->quantity + $total_taxs;
                    $subtotal    = $product->price * $product->quantity;
                    $sub_total   += $subtotal;
                    $grand_total += $totalprice;
                }
            }
        }
        $discount_value = 0;
        $plan_price     = 0;
        if (!empty($order->coupon_json)) {
            $coupons = json_decode($order->coupon_json);
            if (!empty($coupons)) {
                if ($coupons->enable_flat == 'on') {
                    $discount_value = $coupons->flat_discount;
                } else {
                    $discount_value = ($grand_total / 100) * $coupons->discount;
                }
            }

            $plan_price = $grand_total - $discount_value;
        }

        $order_id = Crypt::encrypt($order->id);

        return view('orders.receipt', compact('order', 'store', 'grand_total', 'order_products', 'sub_total', 'total_taxs', 'user_details', 'order_id', 'shipping_data', 'location_data', 'discount_value', 'plan_price'));
    }

    public function fileExport()
    {

        $name = 'Order_' . date('Y-m-d i:h:s');
        $data = Excel::download(new OrderExport(), $name . '.xlsx');
        return $data;
    }
    public function delete_order_item($id, $variant_id = 0, $order_id, $key)
    {
        if(\Auth::user()->can('Delete Orders')){
            $order_item = Order::where('order_id', $order_id)->orWhere('order_id','#'.$order_id)->first();
            $order_json =  json_decode($order_item->product, true);
            foreach ($order_json as $orderkey => $json) {

                if ($key == $orderkey) {
                    unset($order_json[$orderkey]);
                }
            }
            $order_item->product = json_encode($order_json);
            $order_item->update();
            if($order_item->product == '[]'){
                $order_item->delete();
                return redirect()->route('orders.index')->with('success', __('Order Item Deleted!'));
            }
            return redirect()->back()->with('success', __('Order Item Deleted!'));
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
    public function bank_transfer_order_show($order_id){
        $store = Store::where('id',\Auth::user()->current_store)->first();
        $order = Order::find($order_id);
        return view('orders.banktransfer_view', compact('order','store'));
    }
    public function StatusEdit(Request $request, $order_id)
    {
        $order = Order::find($order_id);
        $order->payment_status = $request->status;
        $order->update();

        return redirect()->back()->with('success', __('Order payment successfully updated.'));

    }
}
