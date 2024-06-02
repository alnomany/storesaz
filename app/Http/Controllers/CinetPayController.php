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

class CinetPayController extends Controller
{
    public function planPayWithCinetPay(Request $request)
    {
        $payment_setting = Utility::getAdminPaymentSetting();
        $cinetpay_api_key = !empty($payment_setting['cinetpay_api_key']) ? $payment_setting['cinetpay_api_key'] : '';
        $cinetpay_site_id = !empty($payment_setting['cinetpay_site_id']) ? $payment_setting['cinetpay_site_id'] : '';
        $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'XOF';
        // $currency='XOF';
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);

        $plan = Plan::find($planID);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $authuser = Auth::user();

        if ($plan) {
            /* Check for code usage */
            $get_amount = $plan->price;

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
                                    'payment_type' => __('CinetPay'),
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

                if (
                    $currency != 'XOF' &&
                    $currency != 'CDF' &&
                    $currency != 'USD' &&
                    $currency != 'KMF' &&
                    $currency != 'GNF'
                ) {
                    return redirect()->route('plans.index')->with('error', __('Availabe currencies: XOF, CDF, USD, KMF, GNF'));
                }
                $call_back = route('plan.cinetpay.return') . '?_token=' . csrf_token();
                $returnURL = route('plan.cinetpay.notify') . '?_token=' . csrf_token();
                $cinetpay_data =  [
                    "amount" => round($get_amount),
                    "currency" => $currency,
                    "apikey" => $cinetpay_api_key,
                    "site_id" => $cinetpay_site_id,
                    "transaction_id" => $orderID,
                    "description" => "Plan purchase",
                    "return_url" => $call_back,
                    "notify_url" => $returnURL,
                    "metadata" => "user001",
                    'customer_name' => isset($authuser->name) ? $authuser->name : 'Test',
                    'customer_surname' => isset($authuser->name) ? $authuser->name : 'Test',
                    'customer_email' => isset($authuser->email) ? $authuser->email : 'test@gmail.com',
                    'customer_phone_number' => isset($authuser->mobile_number) ? $authuser->mobile_number : '1234567890',
                    'customer_address' => isset($authuser->address) ? $authuser->address  : 'A-101, alok area, USA',
                    'customer_city' => 'texas',
                    'customer_country' => 'BF',
                    'customer_state' => 'USA',
                    'customer_zip_code' => isset($authuser->zipcode) ? $authuser->zipcode : '432876',
                ];

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api-checkout.cinetpay.com/v2/payment',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 45,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($cinetpay_data),
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_HTTPHEADER => array(
                        "content-type:application/json"
                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                //On recupère la réponse de CinetPay
                $response_body = json_decode($response, true);
                // dd($response_body);
                $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();

                if ($response_body['code'] == '201') {
                    $cinetpaySession = [
                        'order_id' => $orderID,
                        'amount' => $get_amount,
                        'plan_id' => $plan->id,
                        'coupon_id' => !empty($coupons->id) ? $coupons->id : '',
                        'coupon_code' => !empty($request->coupon) ? $request->coupon : '',
                    ];

                    $request->session()->put('cinetpaySession', $cinetpaySession);

                    $payment_link = $response_body["data"]["payment_url"]; // Retrieving the payment URL
                    return redirect($payment_link);
                } else {
                    return back()->with('error', $response_body["description"]);
                }
            } catch (\Exception $e) {
                \Log::debug($e->getMessage());
                return redirect()->route('plans.index')->with('error', $e->getMessage());
            }
            return view('plans.request', compact('stripe_session'));
        } else {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function planCinetPayReturn(Request $request)
    {
        $cinetpaySession = $request->session()->get('cinetpaySession');
        $request->session()->forget('cinetpaySession');

        if (isset($request->transaction_id) || isset($request->token)) {
            $payment_setting = Utility::getAdminPaymentSetting();

            $cinetpay_check = [
                "apikey" => $payment_setting['cinetpay_api_key'],
                "site_id" => $payment_setting['cinetpay_site_id'],
                "transaction_id" => $request->transaction_id
            ];

            $response = $this->getPayStatus($cinetpay_check);

            $response_body = json_decode($response, true);
            $authuser = Auth::user();
            $plan = Plan::find($cinetpaySession['plan_id']);
            $getAmount = $cinetpaySession['amount'];
            $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'XOF';
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

            if ($response_body['code'] == '00') {

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
                $order->payment_type = __('Cinetpay');
                $order->payment_status = 'success';
                $order->receipt = '';
                $order->user_id = $authuser->id;
                $order->save();

                $assignPlan = $authuser->assignPlan($plan->id);
                if ($request->coupon_code) {
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
                }

                Utility::referralTransaction($plan);
                if ($assignPlan['is_success']) {
                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
                } else {
                    return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                }
            } else {

                return redirect()->route('plans.index')->with('error', __('Your Payment has failed!'));
            }
        } else {
            return redirect()->route('plans.index')->with('error', __('Your Payment has failed!'));
        }
    }


    public function planCinetPayNotify(Request $request)
    {
        /* 1- Recovery of parameters posted on the URL by CinetPay
         * https://docs.cinetpay.com/api/1.0-fr/checkout/notification#les-etapes-pour-configurer-lurl-de-notification
         * */
        if (isset($request->transaction_id)) {
            // Using your transaction identifier, check that the order has not yet been processed
            $VerifyStatusCmd = "1"; // status value to retrieve from your database
            if ($VerifyStatusCmd == '00') {
                //The order has already been processed
                // Scarred you script
                die();
            }
            $payment_setting = Utility::getAdminPaymentSetting();

            /* 2- Otherwise, we check the status of the transaction in the event of a payment attempt on CinetPay
            * https://docs.cinetpay.com/api/1.0-fr/checkout/notification#2-verifier-letat-de-la-transaction */
            $cinetpay_check = [
                "apikey" => $payment_setting['cinetpay_api_key'],
                "site_id" => $payment_setting['cinetpay_site_id'],
                "transaction_id" => $request->transaction_id
            ];

            $response = $this->getPayStatus($cinetpay_check); // call query function to retrieve status

            //We get the response from CinetPay
            $response_body = json_decode($response, true);
            if ($response_body['code'] == '00') {
                /* correct, on délivre le service
                 * https://docs.cinetpay.com/api/1.0-fr/checkout/notification#3-delivrer-un-service*/
                echo 'Congratulations, your payment has been successfully completed';
            } else {
                // transaction a échoué
                echo 'Failure, code:' . $response_body['code'] . ' Description' . $response_body['description'] . ' Message: ' . $response_body['message'];
            }
            // Update the transaction in your database
            /*  $order->update(); */
        } else {
            print("cpm_trans_id non found");
        }
    }

    public function getPayStatus($data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-checkout.cinetpay.com/v2/payment/check',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 45,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => array(
                "content-type:application/json"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err){
            return redirect()->route('plans.index')->with('error', __('Something went wrong!'));
        }else{
            return ($response);
        }
    }
    public function orderPayWithcinetpay(Request $request, $slug)
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

        $cinetpay_api_key = $store_payment_setting['cinetpay_api_key'];
        $cinetpay_site_id = $store_payment_setting['cinetpay_site_id'];
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

            // $currency = 'NPR';

            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

            try {

                if (
                    $currency != 'XOF' &&
                    $currency != 'CDF' &&
                    $currency != 'USD' &&
                    $currency != 'KMF' &&
                    $currency != 'GNF'
                ) {
                    return redirect()->back()->with('error', __('Availabe currencies: XOF, CDF, USD, KMF, GNF'));
                }
                $call_back = route('order.cinetpay.return') . '?_token=' . csrf_token();
                $returnURL = route('order.cinetpay.notify') . '?_token=' . csrf_token();
                $cinetpay_data = [
                    "amount" => round($price),
                    "currency" => $currency,
                    "apikey" => $cinetpay_api_key,
                    "site_id" => $cinetpay_site_id,
                    "transaction_id" => $orderID,
                    "description" => "Cinetpay Invoice Payment",
                    "return_url" => $call_back,
                    "notify_url" => $returnURL,
                    "metadata" => "user001",
                    'customer_name' => isset($cust_details['name']) ? $cust_details['name'] : 'Test',
                    'customer_surname' => isset($cust_details['last_name']) ? $cust_details['last_name'] : 'Test',
                    'customer_email' => isset($cust_details['email']) ? $cust_details['email'] : 'test@gmail.com',
                    'customer_phone_number' => isset($cust_details['phone']) ? $cust_details['phone'] : '1234567890',
                    'customer_address' => isset($cust_details['billing_address']) ? $cust_details['billing_address'] : 'A-101, alok area, USA',
                    'customer_city' => 'texas',
                    'customer_country' => 'BF',
                    'customer_state' => 'USA',
                    'customer_zip_code' => isset($cust_details['billing_postalcode']) ? $cust_details['billing_postalcode'] : '432876',
                ];
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api-checkout.cinetpay.com/v2/payment',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 45,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($cinetpay_data),
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_HTTPHEADER => array(
                        "content-type:application/json",
                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                //On recupère la réponse de CinetPay
                $response_body = json_decode($response, true);

                if ($response_body['code'] == '201') {
                    $cinetpaySession = [
                        'product_id' => $product_id, 
                        'amount' => $price, 
                        'orderId' => $orderID, 
                        'slug' => $slug,
                        'currency' => $currency,
                    ];

                    $request->session()->put('cinetpaySession', $cinetpaySession);

                    $payment_link = $response_body["data"]["payment_url"]; // Retrieving the payment URL
                    return redirect($payment_link);
                } else {
                    // dd('d');
                    return back()->with('error','Something went wrong!');
                }

            } catch (\Exception $e) {
                \Log::debug($e->getMessage());
                return redirect()->back()->with('error', 'Something went wrong!');
                // return redirect()->back()->with('error', $e->getMessage());
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Product is deleted.'));
        }

    }


    public function orderCinetPayReturn(Request $request)
    {
        $cinetpaySession = $request->session()->get('cinetpaySession');
        $request->session()->forget('cinetpaySession');

        $getAmount = $cinetpaySession['amount'];
        $product_id = $cinetpaySession['product_id'];
        $slug = $cinetpaySession['slug'];
        $store = Store::where('slug', $slug)->first();
        $storepaymentSetting = Utility::getPaymentSetting($store->id);
        $cinetpay_check = [
            "apikey" => $storepaymentSetting['cinetpay_api_key'],
            "site_id" => $storepaymentSetting['cinetpay_site_id'],
            "transaction_id" => $request->transaction_id,
        ];

        $response = $this->orderGetcinetpayStatus($cinetpay_check);

        $response_body = json_decode($response, true);
        
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

            if (isset($response_body['code']) && $response_body['code'] == '00') {
                $customer               = Auth::guard('customers')->user();
                $order                  = new Order();
                $order->order_id        = $cinetpaySession['orderId'];
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
                $order->payment_type    = 'CinetPay';
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
            }else {
                return redirect()->route('store.slug',$slug)->with('error', $response_body['message']);
            }
        }catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    public function orderCinetPayNotify(Request $request)
    {
        if (isset($request->transaction_id)) {
            // Using your transaction identifier, check that the order has not yet been processed
            $VerifyStatusCmd = "1"; // status value to retrieve from your database
            if ($VerifyStatusCmd == '00') {
                //The order has already been processed
                // Scarred you script
                die();
            }
            $cinetpaySession = $request->session()->get('cinetpaySession');
            $request->session()->forget('cinetpaySession');
            $slug = $cinetpaySession['slug'];
            $store = Store::where('slug', $slug)->first();
            if(\Auth::check())
            {
                $payment_setting = Utility::getPaymentSetting();
            }
            else
            {
                $payment_setting = Utility::getPaymentSetting($store->id);
            }

            /* 2- Otherwise, we check the status of the transaction in the event of a payment attempt on CinetPay
             * https://docs.cinetpay.com/api/1.0-fr/checkout/notification#2-verifier-letat-de-la-transaction */
            $cinetpay_check = [
                "apikey" => $payment_setting['cinetpay_api_key'],
                "site_id" => $payment_setting['cinetpay_site_id'],
                "transaction_id" => $request->transaction_id,
            ];

            $response = $this->invoiceGetcinetpayStatus($cinetpay_check); // call query function to retrieve status

            //We get the response from CinetPay
            $response_body = json_decode($response, true);
            if ($response_body['code'] == '00') {
                /* correct, on délivre le service
                 * https://docs.cinetpay.com/api/1.0-fr/checkout/notification#3-delivrer-un-service*/
                echo 'Congratulations, your payment has been successfully completed';
            } else {
                // transaction a échoué
                echo 'Failure, code:' . $response_body['code'] . ' Description' . $response_body['description'] . ' Message: ' . $response_body['message'];
            }
            // Update the transaction in your database
            /*  $order->update(); */
        } else {
            print("cpm_trans_id non found");
        }
    }

    public function orderGetcinetpayStatus($data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-checkout.cinetpay.com/v2/payment/check',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 45,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => array(
                "content-type:application/json",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return redirect()->back()->with('error', __('Something went wrong!'));
        } else {
            return ($response);
        }

    }
}
