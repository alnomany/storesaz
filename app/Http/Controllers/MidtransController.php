<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\PlanOrder;
use App\Models\Product;
use App\Models\ProductCoupon;
use App\Models\ProductVariantOption;
use App\Models\PurchasedProducts;
use App\Models\Shipping;
use App\Models\Store;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class MidtransController extends Controller
{
    public function planPayWithMidtrans(Request $request){
        $payment_setting = Utility::getAdminPaymentSetting();
        $midtrans_secret = $payment_setting['midtrans_secret'];
        $midtrans_mode = $payment_setting['midtrans_mode'];
        $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'USD';

        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan = Plan::find($planID);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        if ($plan) {
            $get_amount = round($plan->price);

            if (!empty($request->coupon)) {
                $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if(!empty($coupons))
                {
                    $usedCoupun     = $coupons->used_coupon();
                    $discount_value = ($plan->price / 100) * $coupons->discount;
                    $get_amount          = $plan->price - $discount_value;

                    if($coupons->limit == $usedCoupun)
                    {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                }
                else
                {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }
            try {
                 // Set your Merchant Server Key
                \Midtrans\Config::$serverKey = $midtrans_secret;
                // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
                // \Midtrans\Config::$isProduction = false;
                if ($midtrans_mode == 'sandbox') {
                    \Midtrans\Config::$isProduction = false;
                } else {
                    \Midtrans\Config::$isProduction = true;
                }
                // Set sanitization on (default)
                \Midtrans\Config::$isSanitized = true;
                // Set 3DS transaction for credit card to true
                \Midtrans\Config::$is3ds = true;

                $params = array(
                    'transaction_details' => array(
                        'order_id' => $orderID,
                        'gross_amount' => round($get_amount),
                    ),
                    'customer_details' => array(
                        'first_name' => Auth::user()->name,
                        'last_name' => '',
                        'email' => Auth::user()->email,
                        'phone' => '8787878787',
                    ),
                );
                $snapToken = \Midtrans\Snap::getSnapToken($params);

                $authuser = Auth::user();
                $authuser->plan = $plan->id;
                $authuser->save();

                PlanOrder::create(
                    [
                        'order_id' => $orderID,
                        'name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                        'card_number' => null,
                        'card_exp_month' => null,
                        'card_exp_year' => null,
                        'plan_name' => $plan->name,
                        'plan_id' => $plan->id,
                        'price' => round($get_amount) == null ? 0 : round($get_amount),
                        'price_currency' => $currency,
                        'txn_id' => '',
                        'payment_type' => __('Midtrans'),
                        'payment_status' => 'pending',
                        'receipt' => null,
                        'user_id' => $authuser->id,
                    ]
                );
                $data = [
                    'snap_token' => $snapToken,
                    'midtrans_secret' => $midtrans_secret,
                    'order_id' => $orderID,
                    'plan_id' => $plan->id,
                    'amount' => $get_amount,
                    'coupon'=>$request->coupon,
                    'fallback_url' => 'plan.get.midtrans.status'
                ];

                return view('midtras.payment', compact('data'));
            } catch (\Exception $e) {
                return redirect()->back()->with('error',$e->getMessage());
            }
           
        }
    }

    public function planGetMidtransStatus(Request $request){
        $response = json_decode($request->json, true);
        if (isset($response['status_code']) && $response['status_code'] == 200) {
            $plan = Plan::find($request['plan_id']);
            $user = auth()->user();
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            try {
                $Order                 = PlanOrder::where('order_id', $request['order_id'])->first();
                $Order->payment_status = 'success';
                $Order->save();

                $assignPlan = $user->assignPlan($plan->id);

                if (!empty($request->coupon)) {
                    $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    if (!empty($coupons)) {
                        $userCoupon = new UserCoupon();
                        $userCoupon->user = $user->id;
                        $userCoupon->coupon = $coupons->id;
                        $userCoupon->order = $orderID;
                        $userCoupon->save();
                        $usedCoupun = $coupons->used_coupon();
                        if ($coupons->limit <= $usedCoupun) {
                            $coupons->is_active = 0;
                            $coupons->save();
                        }
                    }
                }

                Utility::referralTransaction($plan);
                if ($assignPlan['is_success']) {
                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
                } else {
                    return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                }
            } catch (\Exception $e) {
                return redirect()->route('plans.index')->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->back()->with('error', $response['status_message']);
        }
    }
    public function orderPayWithMidtrans(Request $request , $slug){
        $cart     = session()->get($slug);

        if(!empty($cart))
        {
            $products = $cart['products'];
            $cust_details = $cart['customer'];
        }
        else
        {
            return redirect()->back()->with('error', __('Please add to product into cart'));
        }

        $store = Store::where('slug', $slug)->first();
        if(\Auth::check())
        {
            $store_payment_setting = Utility::getPaymentSetting();
        }
        else
        {
            $store_payment_setting = Utility::getPaymentSetting($store->id);
        }

        $midtrans_secret = $store_payment_setting['midtrans_secret'];
        $midtrans_mode = $store_payment_setting['midtrans_mode'];
        $currency = isset($store->currency_code) ? $store->currency_code : 'IDR';
        $total        = 0;
        $sub_tax      = 0;
        $total_tax    = 0;
        $product_name = [];
        $product_id   = [];
        foreach($products as $key => $product)
        {
            if($product['variant_id'] != 0)
            {

                $product_name[] = $product['product_name'];
                $product_id[]   = $product['id'];

                foreach($product['tax'] as $tax)
                {
                    $sub_tax   = ($product['variant_price'] * $product['quantity'] * $tax['tax']) / 100;
                    $total_tax += $sub_tax;
                }
                $totalprice = $product['variant_price'] * $product['quantity'];
                $total      += $totalprice;
            }
            else
            {
                $product_name[] = $product['product_name'];
                $product_id[]   = $product['id'];

                foreach($product['tax'] as $tax)
                {
                    $sub_tax   = ($product['price'] * $product['quantity'] * $tax['tax']) / 100;
                    $total_tax += $sub_tax;
                }
                $totalprice = $product['price'] * $product['quantity'];
                $total      += $totalprice;
            }
        }
        if($products)
        {
            $price     = $total + $total_tax;
            if(isset($cart['coupon']))
            {
                if($cart['coupon']['coupon']['enable_flat'] == 'off')
                {
                    $discount_value = ($price / 100) * $cart['coupon']['coupon']['discount'];
                    $price          = $price - $discount_value;
                }
                else
                {
                    $discount_value = $cart['coupon']['coupon']['flat_discount'];
                    $price          = $price - $discount_value;
                }
            }
            
            if(isset($cart['shipping']) && isset($cart['shipping']['shipping_id']) && !empty($cart['shipping']))
            {
                $shipping = Shipping::find($cart['shipping']['shipping_id']);
                if(!empty($shipping))
                {
                    $price = $price + $shipping->price;
                }
            }
            if(isset($cart['customer']) && !empty($cart['customer']))
            {

                $pdata['phone']   = isset($cart['customer']['phone']) ? $cart['customer']['phone'] : '';
                $pdata['email']   = isset($cart['customer']['email']) ? $cart['customer']['email'] : '';
                $pdata['user_id'] = isset($cart['customer']['id']) ? $cart['customer']['id'] : '';
            }
            else
            {
                $pdata['phone']   = '';
                $pdata['email']   = '';
                $pdata['user_id'] = '';
            }
            try {
                // Set your Merchant Server Key
                \Midtrans\Config::$serverKey = $midtrans_secret;
                // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
                // \Midtrans\Config::$isProduction = false;
                if ($midtrans_mode == 'sandbox') {
                    \Midtrans\Config::$isProduction = false;
                } else {
                    \Midtrans\Config::$isProduction = true;
                }
                // Set sanitization on (default)
                \Midtrans\Config::$isSanitized = true;
                // Set 3DS transaction for credit card to true
                \Midtrans\Config::$is3ds = true;
                $order_id = time();
                $params = array(
                    'transaction_details' => array(
                        'order_id' => $order_id,
                        'gross_amount' => round($price),
                    ),
                    'customer_details' => array(
                        'first_name' =>isset($cust_details['name']) ? $cust_details['name'] : '',
                        'last_name' => '',
                        'email' => isset($cust_details['email']) ? $cust_details['email'] : '',
                        'phone' => '8787878787',
                    ),
                );
                $snapToken = \Midtrans\Snap::getSnapToken($params);
                $data = [
                    'snap_token' => $snapToken,
                    'midtrans_secret' => $midtrans_secret,
                    'order_id' => $order_id,
                    'slug'=>$slug,
                    'amount'=>$price,
                    'fallback_url' => 'order.midtrans.status'
                ];
                return view('midtras.payment', compact('data'));

            }
            catch (\Throwable $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Product is deleted.'));
        }

    }
    public function getOrderPaymentStatus(Request $request){
        $response = json_decode($request->json, true);
        if (isset($response['status_code']) && $response['status_code'] == 200) {
            $store        = Store::where('slug', $request->slug)->first();
            $cart     = session()->get($request->slug);
            $cust_details = $cart['customer'];
            if(!empty($cart))
            {
                $products = $cart['products'];
            }
            else
            {
                return redirect()->back()->with('error', __('Please add to product into cart'));
            }
            if(isset($cart['coupon']['data_id']))
            {
                $coupon = ProductCoupon::where('id', $cart['coupon']['data_id'])->first();
            }
            else
            {
                $coupon = '';
            }
            $product_name = [];
            $product_id   = [];
            $tax_name     = [];
            $totalprice   = 0;
            
            foreach($products as $key => $product)
            {
                if($product['variant_id'] == 0)
                {
                    $new_qty                = $product['originalquantity'] - $product['quantity'];
                    $product_edit           = Product::find($product['product_id']);
                    $product_edit->quantity = $new_qty;
                    $product_edit->save();
    
                    $tax_price = 0;
                    if(!empty($product['tax']))
                    {
                        foreach($product['tax'] as $key => $taxs)
                        {
                            $tax_price += $product['price'] * $product['quantity'] * $taxs['tax'] / 100;
    
                        }
                    }
                    $totalprice     += $product['price'] * $product['quantity'];
                    $product_name[] = $product['product_name'];
                    $product_id[]   = $product['id'];
                }
                elseif($product['variant_id'] != 0)
                {
                    $new_qty                   = $product['originalvariantquantity'] - $product['quantity'];
                    $product_variant           = ProductVariantOption::find($product['variant_id']);
                    $product_variant->quantity = $new_qty;
                    $product_variant->save();
    
                    $tax_price = 0;
                    if(!empty($product['tax']))
                    {
                        foreach($product['tax'] as $key => $taxs)
                        {
                            $tax_price += $product['variant_price'] * $product['quantity'] * $taxs['tax'] / 100;
                        }
                    }
                    $totalprice     += $product['variant_price'] * $product['quantity'];
                    $product_name[] = $product['product_name'];
                    $product_id[]   = $product['id'];
                }
            }
            $price=$totalprice+$tax_price;
            if(isset($cart['coupon']))
            {
                if($cart['coupon']['coupon']['enable_flat'] == 'off')
                {
                    $discount_value = ($price / 100) * $cart['coupon']['coupon']['discount'];
                    $price          = $price - $discount_value;
                }
                else
                {
                    $discount_value = $cart['coupon']['coupon']['flat_discount'];
                    $price          = $price - $discount_value;
                }
            }
    
            if(isset($cart['shipping']) && isset($cart['shipping']['shipping_id']) && !empty($cart['shipping']))
            {
                $shipping       = Shipping::find($cart['shipping']['shipping_id']);
                $shipping_name  = $shipping->name;
                $shipping_price = $shipping->price;
                $shipping_data  = json_encode(
                    [
                        'shipping_name' => $shipping_name,
                        'shipping_price' => $shipping_price,
                        'location_id' => $cart['shipping']['location_id'],
                    ]
                );
            }
            else
            {
                $shipping_data = '';
            }
          
            if (Utility::CustomerAuthCheck($store->slug)) {
                $customer = Auth::guard('customers')->user()->id;
            }else{
                $customer = 0;
            }
            $customer               = Auth::guard('customers')->user();
            $order                  = new Order();
            $order->order_id        = $request->order_id;
            $order->name            = isset($cust_details['name']) ? $cust_details['name'] : '' ;
            $order->email           = isset($cust_details['email']) ? $cust_details['email'] : '';
            $order->card_number     = '';
            $order->card_exp_month  = '';
            $order->card_exp_year   = '';
            $order->status          = 'pending';
            $order->user_address_id = isset($cust_details['id']) ? $cust_details['id'] : '';
            $order->shipping_data   = $shipping_data;
            $order->product_id      = implode(',', $product_id);
            $order->price           = $price;
            $order->coupon          = isset($cart['coupon']['data_id']) ? $cart['coupon']['data_id'] : '';
            $order->coupon_json     = json_encode($coupon);
            $order->discount_price  = isset($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
            $order->product         = json_encode($products);
            $order->price_currency  = $store->currency_code;
            $order->txn_id          = time();
            $order->payment_type    = 'Midtrans';
            $order->payment_status  = 'approved';
            $order->receipt         = '';
            $order->user_id         = $store['id'];
            $order->customer_id     = isset($customer->id) ? $customer->id : '';
            $order->save();

            //webhook
            $module = 'New Order';
            $webhook =  Utility::webhook($module, $store->id);
            if ($webhook) {
                $parameter = json_encode($order);
                //
                // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
                if ($status != true) {
                    $msg  = 'Webhook call failed.';
                }
            }

            if ((!empty(Auth::guard('customers')->user()) && $store->is_checkout_login_required == 'on') ){

                foreach($products as $product_id)
                {
                    $purchased_products = new PurchasedProducts();
                    $purchased_products->product_id  = $product_id['product_id'];
                    $purchased_products->customer_id = $customer->id;
                    $purchased_products->order_id   = $order->id;
                    $purchased_products->save();
                }
            }
            $order_email = $order->email;
            $owner = User::find($store->created_by);
            $owner_email=$owner->email;
            $order_id = Crypt::encrypt($order->id);
            // if(isset($store->mail_driver) && !empty($store->mail_driver))
            // {
                $dArr = [
                    'order_name' => $order->name,
                ];
                $resp = Utility::sendEmailTemplate('Order Created', $order_email, $dArr, $store, $order_id);
                $resp1=Utility::sendEmailTemplate('Order Created For Owner', $owner_email, $dArr, $store, $order_id);
            // }
            if(isset($store->is_twilio_enabled) && $store->is_twilio_enabled=="on")
            {
                    Utility::order_create_owner($order,$owner,$store);
                    Utility::order_create_customer($order,$customer,$store);
            }
            $msg = redirect()->route(
                'store-complete.complete', [
                                                $store->slug,
                                                Crypt::encrypt($order->id),
                                            ]
            )->with('success', __('Transaction has been success'));

            session()->forget($store->slug);

            return $msg;
        }
        else{
            return redirect()->back()->with('error', $response['status_message']);
        }

    }
}
