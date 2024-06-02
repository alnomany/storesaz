<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Utility;
use Illuminate\Support\Facades\Crypt;
use App\Models\Plan;
use App\Models\UserCoupon;
use App\Models\Coupon;
use App\Models\PlanOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariantOption;
use App\Models\PurchasedProducts;
use App\Models\ProductCoupon;
use App\Models\Store;
use App\Models\Shipping;
use App\Models\User;
use Exception; 

class PayfastController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            $payment_setting = Utility::getAdminPaymentSetting();

            $planID = Crypt::decrypt($request->plan_id);
            $plan = Plan::find($planID);
            if ($plan) {
                $plan_amount = $plan->price;
                $order_id = strtoupper(str_replace('.', '', uniqid('', true)));
                $user = Auth::user();
                if ($request->coupon_amount > 0 && $request->coupon_code != null) {
                    $coupons = Coupon::where('code', $request->coupon_code)->first();
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
                    $coupon_amount = str_replace(",","",$request->coupon_amount);
                        $plan_amount = $plan_amount - $coupon_amount;
                    }
                }
                $success = Crypt::encrypt([
                    'plan' => $plan->toArray(),
                    'order_id' => $order_id,
                    'plan_amount' => $plan_amount
                ]);
                $data = array(
                    // Merchant details
                    'merchant_id' => !empty($payment_setting['payfast_merchant_id']) ? $payment_setting['payfast_merchant_id'] : '',
                    'merchant_key' => !empty($payment_setting['payfast_merchant_key']) ? $payment_setting['payfast_merchant_key'] : '',
                    'return_url' => route('payfast.payment.success',$success),
                    'cancel_url' => route('plans.index'),
                    'notify_url' => route('plans.index'),
                    // Buyer details
                    'name_first' => $user->name,
                    'name_last' => '',
                    'email_address' => $user->email,
                    // Transaction details
                    'm_payment_id' => $order_id, //Unique payment ID to pass through to notify_url
                    'amount' => number_format(sprintf('%.2f', $plan_amount), 2, '.', ''),
                    'item_name' => $plan->name,
                );

                $passphrase = !empty($payment_setting['payfast_signature']) ? $payment_setting['payfast_signature'] : '';
                $signature = $this->generateSignature($data, $passphrase);
                $data['signature'] = $signature;

                $htmlForm = '';

                foreach ($data as $name => $value) {
                    $htmlForm .= '<input name="' . $name . '" type="hidden" value=\'' . $value . '\' />';
                }
                return response()->json([
                    'success' => true,
                    'inputs' => $htmlForm,
                ]);

            }
        }

    }
    public function generateSignature($data, $passPhrase = null)
    {
        $pfOutput = '';
        foreach ($data as $key => $val) {
            if ($val !== '') {
                $pfOutput .= $key . '=' . urlencode(trim($val)) . '&';
            }
        }

        $getString = substr($pfOutput, 0, -1);
        if ($passPhrase !== null) {
            $getString .= '&passphrase=' . urlencode(trim($passPhrase));
        }
        return md5($getString);
    }

    public function success($success){

        try{
            $payment_setting = Utility::getAdminPaymentSetting();
            $user = Auth::user();
            $data = Crypt::decrypt($success);
            $order = new PlanOrder();
            $order->order_id = $data['order_id'];
            $order->name = $user->name;
            $order->card_number = '';
            $order->card_exp_month = '';
            $order->card_exp_year = '';
            $order->plan_name = $data['plan']['name'];
            $order->plan_id = $data['plan']['id'];
            $order->price = $data['plan_amount'];
            $order->price_currency = isset($payment_setting['currency']) && !empty($payment_setting['currency']) ? $payment_setting['currency'] : 'USD';
            $order->txn_id = $data['order_id'];
            $order->payment_type = __('PayFast');
            $order->payment_status = 'success';
            $order->txn_id = '';
            $order->receipt = '';
            $order->user_id = $user->id;
            $order->save();
            $assignPlan = $user->assignPlan($data['plan']['id']);

            $plan = Plan::find($data['plan']['id']);
            Utility::referralTransaction($plan);
            if ($assignPlan['is_success']) {
                return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
            } else {
                return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
            }
        }catch(Exception $e){
            return redirect()->route('plans.index')->with('error', __($e));
        }
    }
    public function payfastpayment(Request $request, $slug){
      
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
        $companyPaymentSetting = Utility::getPaymentSetting($store->id);
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
            $order_id = time();
            $success = Crypt::encrypt([
                'product' => $product['product_id'],
                'order_id' => $order_id,
                'product_amount' => $get_amount
            ]);
            $data = array(
                // Merchant details
                'merchant_id' => !empty($companyPaymentSetting['payfast_merchant_id']) ? $companyPaymentSetting['payfast_merchant_id'] : '',
                'merchant_key' => !empty($companyPaymentSetting['payfast_merchant_key']) ? $companyPaymentSetting['payfast_merchant_key'] : '',
                'return_url' => route('payfast.callback',[$slug,$success]),
                'cancel_url' => route('store-payment.payment'),
                'notify_url' => route('store-payment.payment'),
                // Buyer details
                'name_first' => isset($customers['name']) ? $customers['name'] : '',
                'name_last' => '',
                'email_address' => isset($customers['email']) ? $customers['email'] : '',
                // Transaction details
                'm_payment_id' => $order_id, //Unique payment ID to pass through to notify_url
                'amount' => number_format(sprintf('%.2f', $get_amount), 2, '.', ''),
                'item_name' => $product['product_name'],
            );

            $passphrase = !empty($companyPaymentSetting['payfast_signature']) ? $companyPaymentSetting['payfast_signature'] : '';
            $signature = $this->generateSignature($data, $passphrase);
            $data['signature'] = $signature;

            $htmlForm = '';

            foreach ($data as $name => $value) {
                $htmlForm .= '<input name="' . $name . '" type="hidden" value=\'' . $value . '\' />';
            }
            return response()->json([
                'success' => true,
                'inputs' => $htmlForm,
            ]);

        }
    }
    public function payfastcallback($slug,$success){
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
                // $totalprice     = $price + $shipping->price;
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
            $data = Crypt::decrypt($success);
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
            $order->product_id      = $data['product'];
            $order->price           = $data['product_amount'];
            $order->coupon          = isset($cart['coupon']['data_id']) ? $cart['coupon']['data_id'] : '';
            $order->coupon_json     = json_encode($coupon);
            $order->discount_price  = isset($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
            $order->product         = json_encode($products);
            $order->price_currency  = $store->currency_code;
            $order->txn_id          = isset($pay_id) ? $pay_id : '';
            $order->payment_type    = 'Payfast';
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
        }catch(Exception $e){
            return redirect()->back()->with('error', __($e));
        }
    }
}
