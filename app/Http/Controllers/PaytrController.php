<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utility;
use App\Models\Plan;
use App\Models\PlanOrder;
use App\Models\UserCoupon;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariantOption;
use App\Models\PurchasedProducts;
use App\Models\ProductCoupon;
use App\Models\Store;
use App\Models\Shipping;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Exception;

class PaytrController extends Controller
{
    public function PlanpayWithPaytr(Request $request)
    {
        $payment_setting = Utility::getAdminPaymentSetting();
        $paytr_merchant_id = $payment_setting['paytr_merchant_id'];
        $paytr_merchant_key = $payment_setting['paytr_merchant_key'];
        $paytr_merchant_salt = $payment_setting['paytr_merchant_salt'];
        $currency =  isset($payment_setting['currency']) && !empty($payment_setting['currency']) ? $payment_setting['currency'] : 'TL';
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $authuser = \Auth::user();
        $plan = Plan::find($planID);
        if ($plan)
        {
            $get_amount = $plan->price;
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
                $coupon = (empty($request->coupon)) ? "0" : $request->coupon;

                $merchant_id    = $paytr_merchant_id;
                $merchant_key   = $paytr_merchant_key;
                $merchant_salt  = $paytr_merchant_salt;

                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                $email = $authuser->email;
                $payment_amount = $plan->price;
                $merchant_oid = $orderID;
                $user_name = $authuser->name;
                $user_address =  'no address';
                $user_phone =  '0000000000';
                $user_basket = base64_encode(json_encode(array(
                    array("Plan", $payment_amount, 1),
                )));

                if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                    $ip = $_SERVER["HTTP_CLIENT_IP"];
                } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                } else {
                    $ip = $_SERVER["REMOTE_ADDR"];
                }

                $user_ip = $ip;
                $timeout_limit = "30";
                $debug_on = 1;
                $test_mode = 0;
                $no_installment = 0;
                $max_installment = 0;
                $currency = isset($payment_setting['currency']) && !empty($payment_setting['currency']) ? $payment_setting['currency'] : 'TL';
                $lang = ($authuser->lang == 'tr') ? 'tr' : 'en';
                $hash_str = $merchant_id . $user_ip . $merchant_oid . $email . $payment_amount . $user_basket . $no_installment . $max_installment . $currency . $test_mode;
                $paytr_token = base64_encode(hash_hmac('sha256', $hash_str . $merchant_salt, $merchant_key, true));

                $request['orderID'] = $orderID;
                $request['plan_id'] = $plan->id;
                $request['price'] = $get_amount;
                $request['coupon'] = $request->coupon;
                $request['payment_status'] = 'failed';
                $payment_failed = $request->all();
                $request['payment_status'] = 'success';
                $payment_success = $request->all();

                $post_vals = array(
                    'merchant_id' => $merchant_id,
                    'user_ip' => $user_ip,
                    'merchant_oid' => $merchant_oid,
                    'email' => $email,
                    'payment_amount' => $payment_amount,
                    'paytr_token' => $paytr_token,
                    'user_basket' => $user_basket,
                    'debug_on' => $debug_on,
                    'lang' => $lang,
                    'no_installment' => $no_installment,
                    'max_installment' => $max_installment,
                    'user_name' => $user_name,
                    'user_address' => $user_address,
                    'user_phone' => $user_phone,
                    'merchant_ok_url' => route('pay.paytr.success', $payment_success),
                    'merchant_fail_url' => route('pay.paytr.success', $payment_failed),
                    'timeout_limit' => $timeout_limit,
                    'currency' => $currency,
                    'test_mode' => $test_mode
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/get-token");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 20);


                $result = @curl_exec($ch);

                if (curl_errno($ch)) {
                    die("PAYTR IFRAME connection error. err:" . curl_error($ch));
                }

                curl_close($ch);

                $result = json_decode($result, 1);
                if ($result['status'] == 'success') {
                    $token = $result['token'];
                } else {
                    return redirect()->back()->with('error', 'Currency Not Supported.Contact To Your Site Admin');
                }

                return view('plans.paytr_payment', compact('token'));
            } catch (\Throwable $th) {

                return redirect()->route('plans.index')->with('error', $th->getMessage());
            }
        }
    }

    public function paytrsuccess(Request $request)
    {
        if ($request->payment_status == "success")
        {
            try {
                $user = \Auth::user();
                $planID = $request->plan_id;
                $plan = Plan::find($planID);
                $couponCode = $request->coupon;
                $getAmount = $request->price;

                if ($couponCode != 0) {
                    $coupons = Coupon::where('code', strtoupper($couponCode))->where('is_active', '1')->first();
                    $request['coupon_id'] = $coupons->id;
                } else {
                    $coupons = null;
                }
                $payment_setting = Utility::getAdminPaymentSetting();

                $order = new Order();
                $order->order_id = $request->orderID;
                $order->name = $user->name;
                $order->card_number = '';
                $order->card_exp_month = '';
                $order->card_exp_year = '';
                $order->plan_name = $plan->name;
                $order->plan_id = $plan->id;
                $order->price = $getAmount;
                $order->price_currency = isset($payment_setting['currency']) && !empty($payment_setting['currency']) ? $payment_setting['currency'] : 'TL';
                $order->txn_id = $request->orderID;
                $order->payment_type = __('PayTR');
                $order->payment_status = 'success';
                $order->txn_id = '';
                $order->receipt = '';
                $order->user_id = $user->id;
                $order->save();
                $assignPlan = $user->assignPlan($plan->id);

                $coupons = Coupon::find($request->coupon_id);
                if (!empty($request->coupon)) {
                    $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    if (!empty($coupons)) {
                        $userCoupon = new UserCoupon();
                        $userCoupon->user = $user->id;
                        $userCoupon->coupon = $coupons->id;
                        $userCoupon->order = $request->orderID;
                        $userCoupon->save();
                        $usedCoupun = $coupons->used_coupon();
                        if ($coupons->limit <= $usedCoupun) {
                            $coupons->is_active = 0;
                            $coupons->save();
                        }
                    }
                }

                Utility::referralTransaction($plan);
                if ($assignPlan['is_success'])
                {
                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
                } else
                {
                    return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                }
            } catch (Exception $e)
            {
                return redirect()->route('plans.index')->with('error', __($e));
            }
        }
        else
        {
            return redirect()->route('plans.index')->with('success', __('Your Transaction is fail please try again.'));
        }
    }
    public function OrderpayWithPaytr(Request $request,$slug){
        try {
            $cart = session()->get($slug);

            if(!empty($cart) && isset($cart['products']))
            {
                $products = $cart['products'];
            }
            else
            {
                return redirect()->back()->with('error', __('Please add to product into cart'));
            }
            if(!empty($cart['customer'])){
                $customers = $cart['customer'];
            }
            else{
                $customers = [];
            }
            $store = Store::where('slug', $slug)->first();
            $storepaymentSetting = Utility::getPaymentSetting($store->id);
            $total_tax = $sub_total = $total = $sub_tax = 0;
            $product_name = [];
            $product_id = [];
            foreach ($products as $key => $product) {
                if ($product['variant_id'] != 0) {

                    $product_name[] = $product['product_name'];
                    $product_id[] = $product['id'];

                    foreach ($product['tax'] as $tax) {
                        $sub_tax = ($product['variant_price'] * $product['quantity'] * $tax['tax']) / 100;
                        $total_tax += $sub_tax;
                    }
                    $totalprice = $product['variant_price'] * $product['quantity'];
                    $total += $totalprice;
                } else {
                    $product_name[] = $product['product_name'];
                    $product_id[] = $product['id'];

                    foreach ($product['tax'] as $tax) {
                        $sub_tax = ($product['price'] * $product['quantity'] * $tax['tax']) / 100;
                        $total_tax += $sub_tax;
                    }
                    $totalprice = $product['price'] * $product['quantity'];
                    $total += $totalprice;
                }
            }
            if ($products) {
                $get_amount = $total + $total_tax;
                if (isset($cart['coupon'])) {
                    if ($cart['coupon']['coupon']['enable_flat'] == 'off') {
                    
                        $discount_value = ($get_amount / 100) * $cart['coupon']['coupon']['discount'];
                        $get_amount = $get_amount - $discount_value;
                    } else {
                    
                        $discount_value = $cart['coupon']['coupon']['flat_discount'];
                        $get_amount = $get_amount - $discount_value;
                    }
                }
                if (isset($cart['shipping']) && isset($cart['shipping']['shipping_id']) && !empty($cart['shipping'])) {
                    $shipping = Shipping::find($cart['shipping']['shipping_id']);
                    if (!empty($shipping)) {
                        $get_amount = $get_amount + $shipping->price;
                    }
                }
                $merchant_id    = $storepaymentSetting['paytr_merchant_id'];
                $merchant_key   = $storepaymentSetting['paytr_merchant_key'];
                $merchant_salt  = $storepaymentSetting['paytr_merchant_salt'];

                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                $email = $customers['email'];
                $payment_amount = intval(round($get_amount));
                $merchant_oid = $orderID;
                $user_name = $customers['name'];
                $user_address =  'no address';
                $user_phone =  $customers['phone'];
                $user_basket = base64_encode(json_encode(array(
                    array("Plan", $payment_amount, 1),
                )));

                if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                    $ip = $_SERVER["HTTP_CLIENT_IP"];
                } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                } else {
                    $ip = $_SERVER["REMOTE_ADDR"];
                }

                $user_ip = $ip;
                $timeout_limit = "30";
                $debug_on = 1;
                $test_mode = 0;
                $no_installment = 0;
                $max_installment = 0;
                $currency = !empty($store->currency_code) ? $store->currency_code : 'TL';
                $lang = ($store->lang == 'tr') ? 'tr' : 'en';
                $hash_str = $merchant_id . $user_ip . $merchant_oid . $email . $payment_amount . $user_basket . $no_installment . $max_installment . $currency . $test_mode;
                $paytr_token = base64_encode(hash_hmac('sha256', $hash_str . $merchant_salt, $merchant_key, true));

                $request['orderID'] = $orderID;
                $request['slug'] = $slug;
                $request['price'] = $get_amount;
                $request['product_id'] = $product_id;
                $request['payment_status'] = 'failed';
                $payment_failed = $request->all();
                $request['payment_status'] = 'success';
                $payment_success = $request->all();

                $post_vals = array(
                    'merchant_id' => $merchant_id,
                    'user_ip' => $user_ip,
                    'merchant_oid' => $merchant_oid,
                    'email' => $email,
                    'payment_amount' => $payment_amount,
                    'paytr_token' => $paytr_token,
                    'user_basket' => $user_basket,
                    'debug_on' => $debug_on,
                    'lang' => $lang,
                    'no_installment' => $no_installment,
                    'max_installment' => $max_installment,
                    'user_name' => $user_name,
                    'user_address' => $user_address,
                    'user_phone' => $user_phone,
                    'merchant_ok_url' => route('order.pay.success', $payment_success),
                    'merchant_fail_url' => route('order.pay.success', $payment_failed),
                    'timeout_limit' => $timeout_limit,
                    'currency' => $currency,
                    'test_mode' => $test_mode
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/get-token");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 20);


                $result = @curl_exec($ch);

                if (curl_errno($ch)) {
                    die("PAYTR IFRAME connection error. err:" . curl_error($ch));
                }

                curl_close($ch);

                $result = json_decode($result, 1);
                if ($result['status'] == 'success') {
                    $token = $result['token'];
                } else {
                    return redirect()->back()->with('error', 'Currency Not Supported.Contact To Your Site Admin');
                }

                return view('storefront.paytr_payment', compact('token'));
            
            }
        }catch(Exception $e){
            return redirect()->back()->with('error', __($e));
        }
    }
    public function OrderPaytrsuccess(Request $request){
        if ($request->payment_status == "success")
        {
            $slug = $request->slug;
            $getAmount=$request->price;
            $product_id = $request->product_id;
            try{
                $store = Store::where('slug', $slug)->first();
                $cart = session()->get($slug);
                $products       = $cart['products'];
                $cust_details = $cart['customer'];
                if(isset($cart['coupon']['data_id']))
                {
                    $coupon = ProductCoupon::where('id', $cart['coupon']['data_id'])->first();
                }
                else
                {
                    $coupon = '';
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
               
                $customer               = Auth::guard('customers')->user();
                $order                  = new Order();
                $order->order_id        = time();
                $order->name            = isset($cust_details['name']) ? $cust_details['name'] : '' ;
                $order->email           = isset($cust_details['email']) ? $cust_details['email'] : '';
                $order->card_number     = '';
                $order->card_exp_month  = '';
                $order->card_exp_year   = '';
                $order->status          = 'pending';
                $order->user_address_id = isset($cust_details['id']) ? $cust_details['id'] : '';
                $order->shipping_data   = $shipping_data;
                $order->product_id      = implode(',', $product_id);
                $order->price           = $getAmount;
                $order->coupon          = isset($cart['coupon']['data_id']) ? $cart['coupon']['data_id'] : '';
                $order->coupon_json     = json_encode($coupon);
                $order->discount_price  = isset($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
                $order->product         = json_encode($products);
                $order->price_currency  = $store->currency_code;
                $order->txn_id          = isset($pay_id) ? $pay_id : '';
                $order->payment_type    = 'Paytab';
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
                $owner=User::find($store->created_by);
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

                session()->forget($slug);

                return $msg;
                
            }catch(Exception $e){
                return redirect()->back()->with('error', __($e));
            }
        }
        else
        {
            return redirect()->back()->with('success', __('Your Transaction is fail please try again.'));
        }
    }
}
