<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Product;
use App\Models\ProductVariantOption;
use App\Models\Shipping;
use App\Models\Store;
use App\Models\User;
use App\Models\PlanOrder;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\PurchasedProducts;
class PaypalController extends Controller
{
    private $_api_context;


    public function customerPayWithPaypal(Request $request, $invoice_id)
    {
        $invoice = Invoice::find($invoice_id);
        if (Auth::check()) {
            $settings = DB::table('settings')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('value', 'name');
            $user = \Auth::user();
        } else {
            $user = User::where('id', $invoice->created_by)->first();
            $settings = Utility::settingById($invoice->created_by);
        }

        $get_amount = $request->amount;

        $request->validate(['amount' => 'required|numeric|min:0']);

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));

        if ($invoice) {
            if ($get_amount > $invoice->getDue()) {
                return redirect()->back()->with('error', __('Invalid amount.'));
            } else {

                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                $name = Utility::invoiceNumberFormat($settings, $invoice->invoice_id);

                $paypalToken = $provider->getAccessToken();
                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('customer.get.payment.status', [$invoice->id, $get_amount]),
                        "cancel_url" => route('customer.get.payment.status', [$invoice->id, $get_amount]),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => Utility::getValByName('site_currency'),
                                "value" => $get_amount,
                            ],
                        ],
                    ],
                ]);

                if (isset($response['id']) && $response['id'] != null) {
                    // redirect to approve href
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }
                    return redirect()
                        ->route('invoice.show', \Crypt::encrypt($invoice->id))
                        ->with('error', 'Something went wrong.');
                } else {
                    return redirect()
                        ->route('invoice.show', \Crypt::encrypt($invoice->id))
                        ->with('error', $response['message'] ?? 'Something went wrong.');
                }


                return redirect()->route('customer.invoice.show', \Crypt::encrypt($invoice_id))->back()->with('error', __('Unknown error occurred'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }



    public function paymentConfig()
    {
 
        $payment_setting = Utility::getAdminPaymentSetting();
        if($payment_setting['paypal_mode'] == 'live'){
              config([
                        'paypal.live.client_id' => isset($payment_setting['paypal_client_id']) ? $payment_setting['paypal_client_id'] : '',
                        'paypal.live.client_secret' => isset($payment_setting['paypal_secret_key']) ? $payment_setting['paypal_secret_key'] : '',
                        'paypal.mode' => isset($payment_setting['paypal_mode']) ? $payment_setting['paypal_mode'] : '',
                    ]);
        }else{
             config([
                        'paypal.sandbox.client_id' => isset($payment_setting['paypal_client_id']) ? $payment_setting['paypal_client_id'] : '',
                        'paypal.sandbox.client_secret' => isset($payment_setting['paypal_secret_key']) ? $payment_setting['paypal_secret_key'] : '',
                        'paypal.mode' => isset($payment_setting['paypal_mode']) ? $payment_setting['paypal_mode'] : '',
                    ]);
        }
    
    }
    public function planPayWithPaypal(Request $request)
    {

        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);

        $plan   = Plan::find($planID);
        $user = Auth::user();
        $this->paymentconfig($user);
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $get_amount = $plan->price;
        $coupon_id = '';
        $admin_payment_setting = Utility::getAdminPaymentSetting();
        if($plan){
            try
            {
                $coupon_id = 0;
                $price     = $plan->price;
                if(!empty($request->coupon))
                {
                    $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    if(!empty($coupons))
                    {
                        $usedCoupun     = $coupons->used_coupon();
                        $discount_value = ($plan->price / 100) * $coupons->discount;
                        $price          = $plan->price - $discount_value;
                        if($coupons->limit == $usedCoupun)
                        {
                            return redirect()->back()->with('error', __('This coupon code has expired.'));
                        }
                        	$coupon_id = $coupons->id;
                       
                       
		               if($price<1)
		               {
                        $order_id = strtoupper(str_replace('.', '', uniqid('', true)));
                            $statuses = 'success';
                        if ($coupon_id != '') {
                        
                        
                            $userCoupon         = new UserCoupon();
                            $userCoupon->user   = $user->id;
                            $userCoupon->coupon = $coupons->id;
                            $userCoupon->order  = $order_id;
                            $userCoupon->save();
                            $usedCoupun = $coupons->used_coupon();
                            if ($coupons->limit <= $usedCoupun) {
                                $coupons->is_active = 0;
                                $coupons->save();
                            }
					   
					}
                
		                  
                    $order                 = new PlanOrder();
				    $order->order_id       = $order_id;
				    $order->name           = $user->name;
				    $order->card_number    = '';
				    $order->card_exp_month = '';
				    $order->card_exp_year  = '';
				    $order->plan_name      = $plan->name;
				    $order->plan_id        = $plan->id;
				    $order->price          = $price;
				    $order->price_currency = !empty($admin_payment_setting['currency'])?$admin_payment_setting['currency']:'USD';
				    $order->txn_id         = '';
				    $order->payment_type   = 'PAYPAL';
				    $order->payment_status = $statuses;
				    $order->receipt        = '';
				    $order->user_id        = $user->id;
				    $order->save();
                    $assignPlan = $user->assignPlan($plan->id);

                    // Utility::referralTransaction($plan);
                    if($assignPlan['is_success'])
                    {
                        return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
                    }
                    else
                    {
                        return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                    }

		                }

                    }
                    else
                    {
                        return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }
                $this->paymentConfig($user);
                $paypalToken = $provider->getAccessToken();
                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('plan.get.payment.status',[$plan->id,$price,$coupon_id]),
                        "cancel_url" =>  route('plan.get.payment.status',[$plan->id,$price,$coupon_id]),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => !empty($admin_payment_setting['currency'])?$admin_payment_setting['currency']:'USD',
                                "value" => $price
                            ]
                        ]
                    ]
                ]);
                   if (isset($response['id']) && $response['id'] != null) {
                    // redirect to approve href
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }
                    return redirect()
                        ->route('plans.index')
                        ->with('error', 'Something went wrong.');
                } else {
                    return redirect()
                        ->route('plans.index')
                        ->with('error', $response['message'] ?? 'Something went wrong.');
                }
            }
            catch(\Exception $e)
            {
                
                return redirect()->route('plans.index')->with('error', __($e->getMessage()));
            }
        }else{
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }


    public function planGetPaymentStatus(Request $request, $plan_id, $amount)
    {
        $this->paymentconfig();
        $user = Auth::user();
        $plan = Plan::find($plan_id);

        if ($plan) {
            // $this->paymentConfig();
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);
            // dd($response);
            $payment_id = Session::get('paypal_payment_id');
            $order_id = strtoupper(str_replace('.', '', uniqid('', true)));

            // $status  = ucwords(str_replace('_', ' ', $result['state']));
            if ($request->has('coupon_id') && $request->coupon_id != '') {
                $coupons = Coupon::find($request->coupon_id);
                if (!empty($coupons)) {
                    $userCoupon = new UserCoupon();
                    $userCoupon->user = $user->id;
                    $userCoupon->coupon = $coupons->id;
                    $userCoupon->order = $order_id;
                    $userCoupon->save();
                    $usedCoupun = $coupons->used_coupon();
                    if ($coupons->limit <= $usedCoupun) {
                        $coupons->is_active = 0;
                        $coupons->save();
                    }
                }
            }
            if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                if ($response['status'] == 'COMPLETED') {
                    $statuses = 'success';
                }
                $payment_setting = Utility::getAdminPaymentSetting();
                // dd($response);
                $order = new PlanOrder();
                $order->order_id = $order_id;
                $order->name = $user->name;
                $order->card_number = '';
                $order->card_exp_month = '';
                $order->card_exp_year = '';
                $order->plan_name = $plan->name;
                $order->plan_id = $plan->id;
                $order->price = $amount;
                $order->price_currency = isset($payment_setting['currency']) && !empty($payment_setting['currency']) ? $payment_setting['currency'] : 'USD';
                $order->txn_id = $payment_id;
                $order->payment_type = __('PAYPAL');
                $order->payment_status = $statuses;
                $order->txn_id = '';
                $order->receipt = '';
                $order->user_id = $user->id;
                $order->save();
                $assignPlan = $user->assignPlan($plan->id);
                
                Utility::referralTransaction($plan);
                if ($assignPlan['is_success']) {
                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
                } else {
                    return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                }

                return redirect()
                    ->route('plans.index')
                    ->with('success', 'Transaction complete.');
            } else {
                return redirect()
                    ->route('plans.index')
                    ->with('error', $response['message'] ?? 'Something went wrong.');
            }
        } else {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }

// product payments
function PayWithPaypal(Request $request, $slug)
{
    $cart = session()->get($slug);
    if(!empty($cart))
    {
        $products = $cart['products'];
    }
    else
    {
        return redirect()->back()->with('error', __('Please add to product into cart'));
    }

    $store = Store::where('slug', $slug)->first();

    $admin_payments_details = Utility::getPaymentSetting($store->id);

    if($admin_payments_details['paypal_mode'] == 'live'){
        config([
            'paypal.live.client_id' => isset($admin_payments_details['paypal_client_id']) ? $admin_payments_details['paypal_client_id'] : '',
            'paypal.live.client_secret' => isset($admin_payments_details['paypal_secret_key']) ? $admin_payments_details['paypal_secret_key'] : '',
            'paypal.mode' => isset($admin_payments_details['paypal_mode']) ? $admin_payments_details['paypal_mode'] : '',
        ]);
    }
    else{
        config(
            [
                'paypal.sandbox.client_id' => isset($admin_payments_details['paypal_client_id']) ? $admin_payments_details['paypal_client_id'] : '',
                'paypal.sandbox.client_secret' => isset($admin_payments_details['paypal_secret_key']) ? $admin_payments_details['paypal_secret_key'] : '',
                'paypal.mode' => isset($admin_payments_details['paypal_mode']) ? $admin_payments_details['paypal_mode'] : '',
            ]
        );
    }    

    $provider = new PayPalClient;

    $provider->setApiCredentials(config('paypal'));
    $paypalToken = $provider->getAccessToken();

    Session::put('paypal_payment_id', $paypalToken['access_token']);
    $objUser = \Auth::user();

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

    // $this->paymentconfig();
    if ($products) {
        try
        {
            $coupon_id = null;
            $price = $total + $total_tax;
            if (isset($cart['coupon'])) {
                if ($cart['coupon']['coupon']['enable_flat'] == 'off') {
                    $discount_value = ($price / 100) * $cart['coupon']['coupon']['discount'];
                    $price = $price - $discount_value;
                } else {
                    $discount_value = $cart['coupon']['coupon']['flat_discount'];
                    $price = $price - $discount_value;
                }
            }

            if (isset($cart['shipping']) && isset($cart['shipping']['shipping_id']) && !empty($cart['shipping'])) {
                $shipping = Shipping::find($cart['shipping']['shipping_id']);
                if (!empty($shipping)) {
                    $price = $price + $shipping->price;
                }
            }

            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('get.payment.status', $store->slug),
                    "cancel_url" => route('get.payment.status', $store->slug),
                ],
                "purchase_units" => [
                    0 => [
                        "amount" => [
                            "currency_code" => !empty($store->currency_code) ? $store->currency_code : 'USD',//Utility::getValByName('site_currency'),
                            "value" => str_replace(',','',number_format($price,2)),
                        ],
                    ],
                ],
            ]);
            if (isset($response['id']) && $response['id'] != null) {
                // redirect to approve href
                foreach ($response['links'] as $links) {
                    if ($links['rel'] == 'approve') {
                        return redirect()->away($links['href']);
                    }
                }
                return redirect()->back()->with('error', 'Something went wrong.');
            } else {
                return redirect()->back()->with('error', $response['message'] ?? 'Something went wrong.');
            }

        } catch (\Exception$e) {
            return redirect()->back()->with('error', __('Unknown error occurred'));
        }
    } else {
        return redirect()->back()->with('error', __('is deleted.'));
    }
}

function GetPaymentStatus(Request $request, $slug)
{
    $store = Store::where('slug', $slug)->first();
    $admin_payments_details = Utility::getPaymentSetting($store->id);

    if($admin_payments_details['paypal_mode'] == 'live'){
        config([
            'paypal.live.client_id' => isset($admin_payments_details['paypal_client_id']) ? $admin_payments_details['paypal_client_id'] : '',
            'paypal.live.client_secret' => isset($admin_payments_details['paypal_secret_key']) ? $admin_payments_details['paypal_secret_key'] : '',
            'paypal.mode' => isset($admin_payments_details['paypal_mode']) ? $admin_payments_details['paypal_mode'] : '',
        ]);
    }
    else{
        config(
            [
                'paypal.sandbox.client_id' => isset($admin_payments_details['paypal_client_id']) ? $admin_payments_details['paypal_client_id'] : '',
                'paypal.sandbox.client_secret' => isset($admin_payments_details['paypal_secret_key']) ? $admin_payments_details['paypal_secret_key'] : '',
                'paypal.mode' => isset($admin_payments_details['paypal_mode']) ? $admin_payments_details['paypal_mode'] : '',
            ]
        );
    }    
    $cart = session()->get($slug);
    if (isset($cart['coupon'])) {
        $coupon = $cart['coupon']['coupon'];
    }
    if (isset($cart) && !empty($cart['products'])) {
        $products = $cart['products'];
    } else {
        return redirect()->back()->with('error', __('Please add to product into cart'));
    }
    $user_details = $cart['customer'];

    $total = 0;
    $new_qty = 0;
    $sub_total = 0;
    $total_tax = 0;
    $product_name = [];
    $product_id = [];
    $quantity = [];
    $pro_tax = [];
    $sub_tax = 0;
    foreach ($products as $key => $product) {
        if ($product['variant_id'] != 0) {
            $new_qty = $product['originalvariantquantity'] - $product['quantity'];
            $product_edit = ProductVariantOption::find($product['variant_id']);
            $product_edit->quantity = $new_qty;
            $product_edit->save();

            $product_name[] = $product['product_name'];
            $product_id[] = $product['id'];
            $quantity[] = $product['quantity'];

            foreach ($product['tax'] as $tax) {
                $sub_tax = ($product['variant_price'] * $product['quantity'] * $tax['tax']) / 100;
                $total_tax += $sub_tax;
                $pro_tax[] = $sub_tax;
            }
            $totalprice = $product['variant_price'] * $product['quantity'];
            $subtotal = $product['variant_price'] * $product['quantity'];
            $sub_total += $subtotal;
            $total += $totalprice;
        } else {
            $new_qty = $product['originalquantity'] - $product['quantity'];
            $product_edit = Product::find($product['product_id']);
            $product_edit->quantity = $new_qty;
            $product_edit->save();

            $product_name[] = $product['product_name'];
            $product_id[] = $product['id'];
            $quantity[] = $product['quantity'];

            foreach ($product['tax'] as $tax) {
                $sub_tax = ($product['price'] * $product['quantity'] * $tax['tax']) / 100;
                $total_tax += $sub_tax;
                $pro_tax[] = $sub_tax;
            }
            $totalprice = $product['price'] * $product['quantity'];
            $subtotal = $product['price'] * $product['quantity'];
            $sub_total += $subtotal;
            $total += $totalprice;
        }
    }
    $price = $totalprice + $sub_tax;
    if (isset($cart['coupon'])) {
        if ($cart['coupon']['coupon']['enable_flat'] == 'off') {
            $discount_value = ($price / 100) * $cart['coupon']['coupon']['discount'];
            $price = $price - $discount_value;
        } else {
            $discount_value = $cart['coupon']['coupon']['flat_discount'];
            $price = $price - $discount_value;
        }
    }
    if (isset($cart['shipping']) && isset($cart['shipping']['shipping_id']) && !empty($cart['shipping'])) {
        $shipping = Shipping::find($cart['shipping']['shipping_id']);
        if (!empty($shipping)) {
            $shipping_name = $shipping->name;
            $shipping_price = $shipping->price;

            $shipping_data = json_encode(
                [
                    'shipping_name' => $shipping_name,
                    'shipping_price' => $shipping_price,
                    'location_id' => $cart['shipping']['location_id'],
                ]
            );
        } else {
            $shipping_data = '';
        }
    }
    $user = Auth::user();

    if ($product) {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

        $payment_id = Session::get('paypal_payment_id');

        $order_id = strtoupper(str_replace('.', '', uniqid('', true)));

        // $this->setApiContext($slug);
        // $payment_id = Session::get('paypal_payment_id');
        // Session::forget('paypal_payment_id');
        // if(empty($request->PayerID || empty($request->token)))
        // {
        //     return redirect()->route('store-payment.payment', $slug)->with('error', __('Payment failed'));
        // }
        // $payment   = Payment::get($payment_id, $this->_api_context);
        // $execution = new PaymentExecution();
        // $execution->setPayerId($request->PayerID);
        try
        {
            $order = new Order();
            $order->user_id = Auth()->id();
            $latestOrder = Order::orderBy('created_at', 'DESC')->first();
            if (!empty($latestOrder)) {
                $order->order_nr =  str_pad($latestOrder->id + 1, 4, "100", STR_PAD_LEFT);
            } else {
                $order->order_nr =  str_pad(1, 4, "100", STR_PAD_LEFT);

            }
            $orderID = $order->order_nr;
            $statuses ='';
            if (isset($response['status']) && $response['status'] == 'COMPLETED') {

                if ($response['status'] == 'COMPLETED') {

                    $statuses = 'success';
                }

                // dd($response,$provider, $total);
                // $status = ucwords(str_replace('_', ' ', $result['state']));

                    if (Utility::CustomerAuthCheck($store->slug)) {
                        $customer = Auth::guard('customers')->user()->id;
                    } else {
                        $customer = 0;
                    }

                    $customer = Auth::guard('customers')->user();
                    $order = new Order();
                    $order->order_id = $orderID;
                    $order->name = $user_details['name'];
                    $order->email = $user_details['email'];
                    $order->card_number = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year = '';
                    $order->status = 'pending';
                    $order->user_address_id = $user_details['id'];
                    $order->product_id = implode(',', $product_id);
                    $order->shipping_data = !empty($shipping_data) ? $shipping_data : '';
                    $order->coupon = !empty($cart['coupon']['coupon']['id']) ? $cart['coupon']['coupon']['id'] : '';
                    $order->coupon_json = json_encode(!empty($coupon) ? $coupon : '');
                    $order->discount_price = !empty($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
                    $order->price = $price;
                    $order->product = json_encode($products);
                    $order->price_currency = $store->currency_code;
                    $order->txn_id = $payment_id;
                    $order->payment_type = __('PAYPAL');
                    $order->payment_status = $statuses;
                    $order->receipt = '';
                    $order->user_id = $store['id'];
                    $order->customer_id = isset($customer->id) ? $customer->id : '';


                    $order->save();


                    if ((!empty(Auth::guard('customers')->user()) && $store->is_checkout_login_required == 'on')) {
                        foreach ($products as $product_id) {
                            $purchased_products = new PurchasedProducts();
                            $purchased_products->product_id = $product_id['product_id'];
                            $purchased_products->customer_id = $customer->id;
                            $purchased_products->order_id = $order->id;
                            $purchased_products->save();
                        }
                    }
                    session()->forget($slug);

                    $order_email = $order->email;

                    $owner = User::find($store->created_by);

                    $owner_email = $owner->email;

                    $order_id = Crypt::encrypt($order->id);
                    // if (isset($store->mail_driver) && !empty($store->mail_driver)) {
                        $dArr = [
                            'order_name' => $order->name,
                        ];
                        $resp = Utility::sendEmailTemplate('Order Created', $order_email, $dArr, $store, $order_id);

                        $resp1 = Utility::sendEmailTemplate('Order Created For Owner', $owner_email, $dArr, $store, $order_id);

                    // }
                    if (isset($store->is_twilio_enabled) && $store->is_twilio_enabled == "on") {
                        Utility::order_create_owner($order, $owner, $store);
                        Utility::order_create_customer($order, $customer, $store);
                    }

                    return redirect()->route(
                        'store-complete.complete', [
                            $store->slug,
                            Crypt::encrypt($order->id),
                        ]
                    )->with('success', __('Transaction has been') . $statuses);

            }else {
                return redirect()->back()->with('error', __('Transaction has been') . $statuses);
            }
        } catch (\Exception $e) {
            
            return redirect()->back()->with('error', __('Transaction has been failed.'));
        }
    } else {
        return redirect()->back()->with('error', __(' is deleted.'));
    }
}

}
