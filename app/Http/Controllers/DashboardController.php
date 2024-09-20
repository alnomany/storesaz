<?php

namespace App\Http\Controllers;

use File;
use Stripe;
use App\Models\Bill;
use App\Models\Plan;
use App\Models\User;
use App\Models\Order;
use App\Models\Store;
use App\Models\Product;
use App\Models\Utility;
use App\Models\PlanOrder;
use App\Models\UserStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CustomDomainRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Constraint\Count;

class DashboardController extends Controller
{
    use \RachidLaasri\LaravelInstaller\Helpers\MigrationsHelper;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['XSS']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       
        if (!file_exists(storage_path() . "/installed")) {
            header('location:install');
            die;
        }
        $uri = url()->full();
        $segments = explode('/', str_replace(''.url('').'', '', $uri));
        $segments = $segments[1] ?? null;
        
        if($segments == null) {
            $local = parse_url(config('app.url'))['host'];
            // Get the request host
            $remote = request()->getHost();
            // Get the remote domain
            
            // remove WWW
            $remote = str_replace('www.', '', $remote);
            
            if ($local != $remote){
                $domain = CustomDomainRequest::where('status','1')->where('custom_domain',$remote)->first();
                // If the domain exists
                if(isset($domain) && !empty($domain)) {
                    $store = Store::find($domain->store_id);
                    if($store && $store->enable_domain == 'on' && $store['domain_switch'] == 'on') {
                        return app('App\Http\Controllers\StoreController')->storeSlug($store->slug);
                    }
                } else {
                    $sub_store = Store::where('subdomain', '=', $remote)->where('enable_subdomain', 'on')->first();
                    if ($sub_store && $sub_store->enable_subdomain == 'on') {
                        return app('App\Http\Controllers\StoreController')->storeSlug($sub_store->slug);
                    } else {
                        return abort('404', 'Not Found');
                    }
                }
            }

            // $store = Store::where('domains', '=', $remote)->where('enable_domain', 'on')->first();
            // // If the domain exists
            // // if ($store && $store->enable_domain == 'on') {
            // //     return app('App\Http\Controllers\StoreController')->storeSlug($store->slug);
            // // }
            // // $sub_store = Store::where('subdomain', '=', $remote)->where('enable_subdomain', 'on')->first();
            // // if ($sub_store && $sub_store->enable_subdomain == 'on') {
            // //     return app('App\Http\Controllers\StoreController')->storeSlug($sub_store->slug);
            // // }

            // if($store && $store->enable_domain == 'on') {
            //     return app('App\Http\Controllers\StoreController')->storeSlug($store->slug);
            // }
            
            // $sub_store = Store::where('subdomain', '=', $remote)->where('enable_subdomain', 'on')->first();
            // if ($sub_store && $sub_store->enable_subdomain == 'on') {
            
            //     return app('App\Http\Controllers\StoreController')->storeSlug($sub_store->slug);
            // }

        }
        if (\Auth::check()) {
            if(\Auth::user()->can('Manage Dashboard')){
                if (\Auth::user()->type == 'super admin') {
                    
                    $user = \Auth::user();
                    $user['total_user'] = $user->countCompany();
                    $user['total_paid_user'] = $user->countPaidCompany();
                    $user['total_orders'] = PlanOrder::total_orders();
                    $user['total_orders_price'] = Order::total_orders_price();
                    $user['total_plan_price'] = PlanOrder::total_plan_price();
                    $user['total_plan'] = Plan::total_plan();
                    $user['most_purchese_plan'] = (!empty(Plan::most_purchese_plan()) ? Plan::most_purchese_plan()->name : '-');
                    $chartData = $this->getOrderChart(['duration' => 'week']);
                    return view('home', compact('user', 'chartData'));
                } else {
                    $store = Auth::user();
                    $userstore = UserStore::where('store_id', $store->current_store)->first();
                   // $currentstore = Store::where('id', $store->current_store)->first();

                    $newproduct = Product::where('store_id', $store->current_store)->count();
                    $products = Product::where('store_id', $store->current_store)->limit(5)->get();
                    $lowStockThreshold =0;
              
                    ////////////////////////////////////////
/*
                    $topProducts = Product::select('products.*', DB::raw('SUM(orders.quantity) as total_sales'))
                    ->join('orders', 'products.id', '=', 'orders.product_id') // Assuming `product_id` is the foreign key in orders
                    ->where('products.store_id', $store->current_store)
                    ->groupBy('products.id')
                    ->orderBy('total_sales', 'desc')
                    ->limit(5)
                    ->get(); */
                    //////////////////////////
                    $new_orders = Order::where('user_id', $store->current_store)->limit(8)->orderBy('id', 'DESC')->get();
                    $chartData = $this->getOrderChart(['duration' => 'week'],$userstore);
                    $saleData = $this->getSaleChart(['duration' => 'week'],$userstore);
                    $store_id = Store::where('id', $store->current_store)->first();
                    if ($store_id) {
                        // $app_url = trim(env('APP_URL'), '/');
                        // $store_id['store_url'] = $app_url . '/store/' . $store_id['slug'];
                        if ($store_id['enable_storelink'] == 'on') {
                            $app_url = trim(env('APP_URL'), '/');
                            $store_id['store_url'] = $app_url . '/store/' . $store_id['slug'];
                        } else if ($store_id['enable_domain'] == 'on') {
                            $store_id['store_url'] = 'https://' . $store_id['domains'] . '/';
                        } else {
                            $store_id['store_url'] = 'https://' . $store_id['subdomain'] . '/';
                        }
                    }
                    $lowStockThreshold =   $store_id->limit_inventory;
                    

                     $lowStockProducts = Product::where('store_id', $store->current_store)
                    ->where('quantity', '<', $lowStockThreshold)
                    ->limit(50)
                    ->get();
                    
                    $totle_sale = 0;
                    $totle_order = 0;
                    $total_purchase_price = 0;
                     $orders = Order::where('user_id', $store->current_store)->get();
                    if (!empty($orders)) {
                        $pro_qty = 0;
                        $item_id = [];
                        $totle_qty = [];
                        foreach ($orders as $order) {
                            $order_array = json_decode($order->product);
                            $pro_id = [];
                            
                            foreach ($order_array as $key => $item) {
                                $product = Product::find($item->id);

                                if ($product) {
                                    // Calculate total purchase price based on quantity
                                    $total_purchase_price += $product->purchase_price * $item->quantity;
                                }
                                if (!in_array($item->id, $item_id)) {
                                    $item_id[] = $item->id;
                                    $totle_qty[] = $item->quantity;
                                } else {

                                    $totle_qty[array_search($item->id, $item_id)] += $item->quantity;
                                }
                            }
                            $totle_sale += $order['price'];
                            $totle_order++;
                        }
                    }
                    // Retrieve all expenses
                   // $expenses = Bill::with('debitNote', 'payments.bankAccount')->get();
                   $user1 =Auth::user();
                   $store_id1 = Store::where('id', $user1->current_store)->first();
                   $store_id1= $store_id1->id;

                   $expenses = Bill::whereHas('billProducts.product.store', function ($query) use ($store_id1) {
                    $query->where('store_id', $store_id1);
                })
                ->with([
                    'debitNote',
                    'payments.bankAccount',
                    'billProducts.product.store' // Eager load store relation through billProducts and product
                ])
                ->get();
                    
                    $totalExpenses = 0;

                    // Iterate through each expense
                    foreach ($expenses as $expense) {
                        // Calculate total for each expense using your existing logic
                        $totalExpense = $expense->getTotal(); // Assuming you have implemented getTotal() method in Bill model

                        // Accumulate total expenses
                        $totalExpenses += $totalExpense;
                    }
                     $totalExpenses;

                    $users = \Auth::user()->currentuser();
                    $plan = Plan::find($users->plan);
                    if($plan->storage_limit > 0)
                    {
                        $storage_limit = ($users->storage_limit / $plan->storage_limit) * 100;
                    }   
                    else{
                        $storage_limit = 0;

                    }
                    return view('home', compact('products','lowStockProducts','saleData', 'store_id', 'totle_sale','totalExpenses', 'total_purchase_price','store', 'orders', 'totle_order', 'newproduct', 'item_id', 'totle_qty', 'chartData', 'new_orders','storage_limit','plan','users'));
                }
            }else{
                return redirect()->back()->with('error', 'Permission denied.');
            }
        } else {
            if (!file_exists(storage_path() . "/installed")) {
                header('location:install');
                die;
            } else {
                $settings = Utility::settings();
                if ($settings['display_landing_page'] == 'on' && \Schema::hasTable('landing_page_settings')) {
                    return view('landingpage::layouts.landingpage');  
                } else {
                    return redirect('login');
                }
            }
        }
       
    }

    public function getOrderChart($arrParam,$userstore = null)
    {
        $user = Auth::user();
        $arrDuration = [];
        if ($arrParam['duration']) {
            if ($arrParam['duration'] == 'week') {
                $previous_week = strtotime("-2 week +1 day");
                for ($i = 0; $i < 14; $i++) {
                    $arrDuration[date('Y-m-d', $previous_week)] = date('d-M', $previous_week);
                    $previous_week = strtotime(date('Y-m-d', $previous_week) . " +1 day");
                }
            }
        }
        $arrTask = [];
        $arrTask['label'] = [];
        $arrTask['data'] = [];
        foreach ($arrDuration as $date => $label) {
        
            if (Auth::user()->type == 'Owner') {
                $data = Order::select(\DB::raw('count(*) as total'))->where('user_id', $userstore->store_id)->whereDate('created_at', '=', $date)->first();
            } else {
                $data = PlanOrder::select(\DB::raw('count(*) as total'))->whereDate('created_at', '=', $date)->first();
            }

            $arrTask['label'][] = $label;
            $arrTask['data'][] = $data->total;
        }
        return $arrTask;
    }
    public function getSaleChart($arrParam,$userstore)
    {
        $user = Auth::user();
        $arrDuration = [];
        if ($arrParam['duration']) {
            if ($arrParam['duration'] == 'week') {
                $previous_week = strtotime("-1 week +1 day");
                for ($i = 0; $i < 7; $i++) {
                    $arrDuration[date('Y-m-d', $previous_week)] = date('d-M', $previous_week);
                    $previous_week = strtotime(date('Y-m-d', $previous_week) . " +1 day");
                }
            }
        }
        $arrTask = [];
        $arrTask['label'] = [];
        $arrTask['data'] = [];
        foreach ($arrDuration as $date => $label) {
            if (Auth::user()->type == 'Owner') {
                $data = Order::select(\DB::raw('sum(price) as total',))->where('user_id', $userstore->store_id)->whereDate('created_at', '=', $date)->first();

            } else {
                $data = Order::select(\DB::raw('sum(price) as total'))->whereDate('created_at', '=', $date)->first();
            }
            $arrTask['label'][] = $label;
            $arrTask['data'][] = $data->total;
        }
        return $arrTask;
    }

    public function stripe(Request $request)
    {
        $price = 100;
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        if ($price > 0.0) {
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $data = Stripe\Charge::create(
                [
                    "amount" => 100 * $price,
                    "currency" => "usd",
                    "source" => $request->stripeToken,
                    "description" => " Test Plan ",
                    "metadata" => ["order_id" => $orderID],
                ]
            );
        }
    }
    public function check()
    {

    }
    public function profile()
    {
        $userDetail = \Auth::user();

        return view('profile', compact('userDetail'));
    }

    public function editprofile(Request $request)
    {
        $userDetail = \Auth::user();

        $user = User::findOrFail($userDetail['id']);
        $this->validate(
            $request, [
                'name' => 'required|max:120',
                'email' => 'required|email|unique:users,email,' . $userDetail['id'],
                'profile' => 'mimes:jpeg,png,jpg,gif,svg,pdf,doc|max:20480',
            ]
        );

        if($request->hasFile('profile'))
        {
            $filenameWithExt = $request->file('profile')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('profile')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $settings = Utility::getStorageSetting();
            $dir        = 'uploads/profile';

            $image_path = $dir . $userDetail['avatar'];
            if(File::exists($image_path))
            {
                File::delete($image_path);
            }

            $path = Utility::upload_file($request,'profile',$fileNameToStore,$dir,[]);

            if($path['flag'] == 1){
                $url = $path['url'];
            }else{
                return redirect()->back()->with('error', __($path['msg']));
            }


        }

        if (!empty($request->profile)) {
            $user['avatar'] = $fileNameToStore;
        }

        $user['name'] = $request['name'];
        $user['email'] = $request['email'];
        $user->save();

        return redirect()->back()->with('success', __('Profile successfully updated.'));
    }

    public function updatePassword(Request $request)
    {
        if (\Auth::Check()) {
            $request->validate(
                [
                    'current_password' => 'required',
                    'new_password' => 'required|min:6',
                    'confirm_password' => 'required|same:new_password',
                ]
            );
            $objUser = \Auth::user();
            $request_data = $request->All();
            $current_password = $objUser->password;
            if (Hash::check($request_data['current_password'], $current_password)) {
                $user_id = \Auth::User()->id;
                $obj_user = User::find($user_id);
                $obj_user->password = Hash::make($request_data['new_password']);
                $obj_user->save();

                return redirect()->route('profile', $objUser->id)->with('success', __('Password successfully updated.'));
            } else {
                return redirect()->route('profile', $objUser->id)->with('error', __('Please enter correct current password.'));
            }
        } else {
            return redirect()->route('profile', \Auth::user()->id)->with('error', __('Something is wrong.'));
        }
    }

    public function changeMode()
    {
        $usr = Auth::user();
        if ($usr->mode == 'light') {
            $usr->mode = 'dark';
        } else {
            $usr->mode = 'light';
        }
        $usr->save();

        return redirect()->back();
    }
   
      
   
}
