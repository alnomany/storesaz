<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\PlanOrder;
use App\Models\ProductCoupon;
use App\Models\PurchasedProducts;
use App\Models\Shipping;
use App\Models\Store;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;

class FedapayController extends Controller
{
    public function planPayWithFedapay(Request $request)
    {
        $payment_setting = Utility::getAdminPaymentSetting();
        $fedapay = !empty($payment_setting['fedapay_secret_key']) ? $payment_setting['fedapay_secret_key'] : '';
        $fedapay_mode = !empty($payment_setting['fedapay_mode']) ? $payment_setting['fedapay_mode'] : 'sandbox';
        $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'XOF';
        // $currency='XOF';
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan = Plan::find($planID);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $authuser = Auth::user();

        if ($plan) {
            /* Check for code usage */
            $integerValue = $plan->price;

            $get_amount = intval($integerValue);
            if (!empty($request->coupon)) {
                $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun = $coupons->used_coupon();
                    $discount_value = ($plan->price / 100) * $coupons->discount;

                    $get_amount = $plan->price - $discount_value;

                    if ($coupons->limit == $usedCoupun) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                    if ($get_amount <= 0) {
                        $authuser = Auth::user();
                        $authuser->plan = $plan->id;
                        $authuser->save();
                        $assignPlan = $authuser->assignPlan($plan->id);
                        if ($assignPlan['is_success'] == true && !empty($plan)) {

                            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                            $userCoupon = new UserCoupon();

                            $userCoupon->user = $authuser->id;
                            $userCoupon->coupon = $coupons->id;
                            $userCoupon->order = $orderID;
                            $userCoupon->save();
                            PlanOrder::create(
                                [
                                    'order_id' => $orderID,
                                    'name' => null,
                                    'email' => null,
                                    'card_number' => null,
                                    'card_exp_month' => null,
                                    'card_exp_year' => null,
                                    'plan_name' => $plan->name,
                                    'plan_id' => $plan->id,
                                    'price' => $get_amount == null ? 0 : $get_amount,
                                    'price_currency' => $currency,
                                    'txn_id' => '',
                                    'payment_type' => __('Fedapay'),
                                    'payment_status' => 'success',
                                    'receipt' => null,
                                    'user_id' => $authuser->id,
                                ]
                            );
                            return redirect()->route('plans.index')->with('success', __('Plan Successfully Activated'));
                        }
                    }
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }

            try {
                \FedaPay\FedaPay::setApiKey($fedapay);

                \FedaPay\FedaPay::setEnvironment($fedapay_mode);
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                $transaction = \FedaPay\Transaction::create([
                    "description" => "Fedapay Payment",
                    "amount" => round($get_amount),
                    "currency" => ["iso" => $currency],

                    "callback_url" => route('fedapay.status', [
                        'order_id' => $orderID,
                        'plan_id' => $plan->id,
                        "amount" => $get_amount,
                        "coupon_id" => !empty($coupons->id) ? $coupons->id : '',
                        'coupon_code' => !empty($request->coupon) ? $request->coupon : '',
                    ]),
                    "cancel_url" => route('fedapay.status', [
                        'order_id' => $orderID,
                        'plan_id' => $plan->id,
                        "amount" => $get_amount,
                        "coupon_id" => !empty($coupons->id) ? $coupons->id : '',
                        'coupon_code' => !empty($request->coupon) ? $request->coupon : '',
                    ]),

                ]);

                $token = $transaction->generateToken();

                return redirect($token->url);
            } catch (\Exception $e) {
                return redirect()->route('plans.index')->with('error', $e->getMessage());
            }
        } else {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function planGetFedapayStatus(Request $request)
    {
        $payment_setting = Utility::getAdminPaymentSetting();
        $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'XOF';

        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

        $getAmount = $request->amount;
        $authuser = Auth::user();
        $plan = Plan::find($request->plan_id);
        // Utility::transaction($plan);

        try {

            if ($request->status == 'approved') {

                $order = new PlanOrder();
                $order->order_id = $orderID;
                $order->name = $authuser->name;
                $order->card_number = '';
                $order->card_exp_month = '';
                $order->card_exp_year = '';
                $order->plan_name = $plan->name;
                $order->plan_id = $plan->id;
                $order->price = $getAmount;
                $order->price_currency = $currency;
                $order->txn_id = $orderID;
                $order->payment_type = __('Fedapay');
                $order->payment_status = 'success';
                $order->receipt = '';
                $order->user_id = $authuser->id;
                $order->save();
                $assignPlan = $authuser->assignPlan($plan->id);
            } else {
                return redirect()->back()->with('error', __('Transaction Unsuccesfull'));
            }
            // if(!empty($authuser->referral_user)){
            //     Utility::transaction($order);
            // }
            $coupons = Coupon::find($request->coupon_id);

            if (!empty($request->coupon_id)) {
                if (!empty($coupons)) {
                    $userCoupon = new UserCoupon();
                    $userCoupon->user = $authuser->id;
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
                return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
            } else {
                return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
            }
        } catch (\Exception $e) {
            return redirect()->route('plans.index')->with('error', $e->getMessage());
        }
    }


    public function orderPayWithfedapay(Request $request, $slug) 
    {
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

        $fedapay_secret = $store_payment_setting['fedapay_secret_key'];
        $fedapay_mode = $store_payment_setting['fedapay_mode'];
        $currency = isset($store->currency_code) ? $store->currency_code : 'XOF';
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

                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                \FedaPay\FedaPay::setApiKey($fedapay_secret);
                \FedaPay\FedaPay::setEnvironment($fedapay_mode);

                $transaction = \FedaPay\Transaction::create([
                    "description" => "Fedapay Payment",
                    "amount" => round($price),
                    "currency" => ["iso" => $currency],
                    "callback_url" => route('order.fedapay.status', ['slug' => $slug, 'amount' => $price, 'orderId' => $orderID, 'product_id' => $product_id]),
                    "cancel_url" => route('order.fedapay.status', ['slug' => $slug, 'amount' => $price, 'orderId' => $orderID, 'product_id' => $product_id]),
                ]);

                $token = $transaction->generateToken();
                return redirect($token->url);

            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage() ?? 'Something went wrong.');
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Product is deleted.'));
        }

    }

    public function orderGetfedapayStatus(Request $request)
    {
        $getAmount = $request->amount;
        $product_id = $request->product_id;
        $slug = $request->slug;
        $store = Store::where('slug', $slug)->first();
        $storepaymentSetting = Utility::getPaymentSetting($store->id);
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

            if ($request->status == 'approved') {
                $customer               = Auth::guard('customers')->user();
                $order                  = new Order();
                $order->order_id        = $request->orderId;
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
                $order->payment_type    = 'Fedapay';
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
            } else {
                return redirect()->route('store.slug',$slug)->with('error', __('Transaction Unsuccesfull'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

}
