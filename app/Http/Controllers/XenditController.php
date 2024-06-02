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
use Xendit\Xendit;
use Illuminate\Support\Facades\Session;


class XenditController extends Controller
{
    public function planPayWithXendit(Request $request){
        $payment_setting = Utility::getAdminPaymentSetting();
        $xendit_api = $payment_setting['xendit_api'];
        $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'USD';

        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan = Plan::find($planID);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $user = Auth::user();
        if ($plan) {
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
            $response = ['orderId' => $orderID, 'user' => $user, 'get_amount' => $get_amount, 'plan' => $plan, 'currency' => $currency,'coupon'=>$request->coupon];
            Xendit::setApiKey($xendit_api);
            $params = [
                'external_id' => $orderID,
                'payer_email' => Auth::user()->email,
                'description' => 'Payment for order ' . $orderID,
                'amount' => $get_amount,
                'callback_url' =>  route('plan.xendit.status'),
                'success_redirect_url' => route('plan.xendit.status', $response),
                'failure_redirect_url' => route('plans.index'),
            ];

            $invoice = \Xendit\Invoice::create($params);
            Session::put('invoice',$invoice);

            return redirect($invoice['invoice_url']);
        }
    }
    public function planGetXenditStatus(Request $request){
        $payment_setting = Utility::getAdminPaymentSetting();
        $xendit_api = $payment_setting['xendit_api'];
        Xendit::setApiKey($xendit_api);

        $session = Session::get('invoice');
        $getInvoice = \Xendit\Invoice::retrieve($session['id']);

        $authuser = User::find($request->user);
        $plan = Plan::find($request->plan);

        if($getInvoice['status'] == 'PAID'){

            PlanOrder::create(
                [
                    'order_id' => $request->orderId,
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'card_number' => null,
                    'card_exp_month' => null,
                    'card_exp_year' => null,
                    'plan_name' => $plan->name,
                    'plan_id' => $plan->id,
                    'price' => $request->get_amount == null ? 0 : $request->get_amount,
                    'price_currency' => $request->currency,
                    'txn_id' => '',
                    'payment_type' => __('Xendit'),
                    'payment_status' => 'success',
                    'receipt' => null,
                    'user_id' => $request->user,
                ]
            );

            $assignPlan = $authuser->assignPlan($plan->id);
            $user = $request->user;
            if (!empty($request->coupon)) {
                $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $userCoupon = new UserCoupon();
                    $userCoupon->user = $user->id;
                    $userCoupon->coupon = $coupons->id;
                    $userCoupon->order = $request->orderId;
                    $userCoupon->save();
                    $usedCoupun = $coupons->used_coupon();
                    if ($coupons->limit <= $usedCoupun) {
                        $coupons->is_active = 0;
                        $coupons->save();
                    }
                }
            }

            Utility::referralTransaction($plan);
            if($assignPlan['is_success'])
            {
                return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
            }
            else
            {
                return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
            }
        }
    }
    public function orderPayWithXendit(Request $request , $slug){
        $cart     = session()->get($slug);
        if(!empty($cart))
        {
            $products = $cart['products'];
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
        $xendit_api = $store_payment_setting['xendit_api'];
        $orderId = time();
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
                $response = ['orderId' => $orderId, 'get_amount' => $price, 'slug' => $slug];
                Xendit::setApiKey($xendit_api);
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                $params = [
                    'external_id' => $orderID,
                    'payer_email' => isset($cart['customer']['email']) ? $cart['customer']['email'] : '',
                    'description' => 'Payment for order ' . $orderId,
                    'amount' => $price,
                    'callback_url' =>  route('order.xendit.status'),
                    'success_redirect_url' => route('order.xendit.status', $response)
                ];
                $invoice = \Xendit\Invoice::create($params);
                Session::put('invoice',$invoice);
    
                return redirect($invoice['invoice_url']);

            }
            catch (\Throwable $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        else{
            return redirect()->back()->with('error', __('Product is deleted.'));
        }
    }
    public function getOrderPaymentStatus(Request $request){
        $get_amount = $request->amount;
        $store        = Store::where('slug', $request->slug)->first();
        $cart     = session()->get($request->slug);
        $cust_details = $cart['customer'];

        if(\Auth::check())
        {
            $store_payment_setting = Utility::getPaymentSetting();
        }
        else
        {
            $store_payment_setting = Utility::getPaymentSetting($store->id);
        }
        $xendit_api = $store_payment_setting['xendit_api'];
        Xendit::setApiKey($xendit_api);

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

        if($products){
            try{
                $session = Session::get('invoice');
                $getInvoice = \Xendit\Invoice::retrieve($session['id']);
                if($getInvoice['status'] == 'PAID'){
                    if (Utility::CustomerAuthCheck($store->slug)) {
                        $customer = Auth::guard('customers')->user()->id;
                    }else{
                        $customer = 0;
                    }
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
                    $order->price           = $price;
                    $order->coupon          = isset($cart['coupon']['data_id']) ? $cart['coupon']['data_id'] : '';
                    $order->coupon_json     = json_encode($coupon);
                    $order->discount_price  = isset($cart['coupon']['discount_price']) ? $cart['coupon']['discount_price'] : '';
                    $order->product         = json_encode($products);
                    $order->price_currency  = $store->currency_code;
                    $order->txn_id          = time();
                    $order->payment_type    = 'Xendit';
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

            }catch(\Exception $e){
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        else{
            return redirect()->back()->with('error', __('Transaction Unsuccesfull'));
        }
    }
}
