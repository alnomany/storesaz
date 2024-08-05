<?php

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;
use App\Http\Controllers\StoreAnalytic;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\BillController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaytrController;
use App\Http\Controllers\SspayController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\themeController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\PaytabController;
Use App\Http\Controllers\UserController;
use App\Http\Controllers\VenderController;
use App\Http\Controllers\XenditController;
use App\Http\Controllers\FedapayController;
use App\Http\Controllers\IyziPayController;
use App\Http\Controllers\PayfastController;
use App\Http\Controllers\PayHereController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RattingController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AamarpayController;
use App\Http\Controllers\CashfreeController;
use App\Http\Controllers\CinetPayController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\YooKassaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ToyyibpayController;
use App\Http\Controllers\WebhookController;  
use App\Http\Controllers\AiTemplateController;
use App\Http\Controllers\PageOptionController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductTaxController;
use App\Http\Controllers\PaiementProController;
use App\Http\Controllers\PaymentWallController;
use App\Http\Controllers\PlanRequestController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\ProductCouponController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\BenefitPaymentController;
use App\Http\Controllers\ExpresscheckoutController;
use App\Http\Controllers\NepalstePaymnetController;
use App\Http\Controllers\ReferralProgramController;
use App\Http\Controllers\ProductCategorieController;
use App\Http\Controllers\CustomDomainRequestController;
use App\Http\Controllers\Customer\Auth\CustomerLoginController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware(['XSS']);

Route::get('login/{lang?}', function () {

    $url = \Request::url(); 
    $app_url=env('APP_URL');

    $urlExp=explode('/',$url);
    $finalUrl=$urlExp[2];
    $login=$urlExp[3];
    
    $app_urlExp=explode('/',$app_url);
    $finalappUrl=$app_urlExp[2];
    
    
    if(($finalUrl!=$finalappUrl) && $login=='login'){
        
            $local = parse_url(config('app.url'))['host'];

            $remote = request()->getHost();
        
            $remote = str_replace('www.', '', $remote);

            if ($local != $remote){
                $domain = \App\Models\CustomDomainRequest::where('status','1')->where('custom_domain',$remote)->first();
                // If the domain exists
                if(isset($domain) && !empty($domain)) {
                    $store = \App\Models\Store::find($domain->store_id);
                    if($store && $store->enable_domain == 'on' && $store['domain_switch'] == 'on') {
                        return redirect()->route('customer.loginform',$store->slug);
                    }
                } else {
                    $sub_store = \App\Models\Store::where('subdomain', '=', $remote)->where('enable_subdomain', 'on')->first();
                    if ($sub_store && $sub_store->enable_subdomain == 'on') {
                        return redirect()->route('customer.loginform',$sub_store->slug);
                    } else {
                        return abort('404', 'Not Found');
                    }
                }
            }

            // $domain_store = App\Models\Store::where('domains', '=', $remote)->where('enable_domain', 'on')->first();
            // $sub_store = App\Models\Store::where('subdomain', '=', $remote)->where('enable_subdomain', 'on')->first();
        
            // if(!empty($domain_store)){
            //     $slug=$domain_store->slug;
            //     return redirect()->route('customer.loginform',$slug);
            // }
            // if(!empty($sub_store)){
            //     $slug=$sub_store->slug;
            //     return redirect()->route('customer.loginform',$slug);
            // }
            
        return Redirect::to($app_url);
            
    }

    $lang='en';
    return view('auth.login', compact('lang'));


 })->name('login');
require __DIR__ . '/auth.php';


// benifits
Route::any('{slug}/payment/initiate', [BenefitPaymentController::class, 'storeInitiatePayment'])->name('store.benefit.initiate');
Route::any('/store/call_back', [BenefitPaymentController::class, 'storeCall_back'])->name('store.benefit.call_back');

Route::group(['middleware' => ['verified']], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['XSS']);

    //Vender or supplier 
    Route::get('purchase/items', [PurchaseController::class, 'items'])->name('purchase.items');
    Route::resource('purchase', PurchaseController::class)->middleware(['auth', 'XSS']);
    Route::get('purchase/pdf/{id}', [PurchaseController::class, 'purchase'])->name('purchase.pdf')->middleware(['auth', 'XSS']);

    Route::get('/vendor/purchase/{id}/', [PurchaseController::class, 'purchaseLink'])->name('purchase.link.copy');
    //    Route::get('/bill/{id}/', 'PurchaseController@purchaseLink')->name('purchase.link.copy');
    Route::get('purchase/{id}/payment', [PurchaseController::class, 'payment'])->name('purchase.payment');
    Route::post('purchase/{id}/payment', [PurchaseController::class, 'createPayment'])->name('purchase.payment');
    Route::post('purchase/{id}/payment/{pid}/destroy', [PurchaseController::class, 'paymentDestroy'])->name('purchase.payment.destroy');
    Route::post('purchase/product/destroy', [PurchaseController::class, 'productDestroy'])->name('purchase.product.destroy');
    Route::post('purchase/vender', [PurchaseController::class, 'vender'])->name('purchase.vender');
    Route::post('purchase/product', [PurchaseController::class, 'product'])->name('purchase.product');
    Route::get('purchase/create/{cid}', [PurchaseController::class, 'create'])->name('purchase.create');
    Route::get('purchase/{id}/sent', [PurchaseController::class, 'sent'])->name('purchase.sent');
    Route::get('purchase/{id}/resent', [PurchaseController::class, 'resent'])->name('purchase.resent'); 
   // 
   Route::post('bill/vender', [BillController::class, 'vender'])->name('bill.vender');
   Route::resource('vender', VenderController::class)->middleware(['auth', 'XSS']);


    // product category
    Route::resource('product_categorie', ProductCategorieController::class)->middleware(['auth', 'XSS']);

    // product
    Route::resource('product', ProductController::class)->middleware(['auth', 'XSS']);
    Route::post('product/store', [ProductController::class, 'store'])->name('product.store')->middleware(['auth']);
    Route::post('product/{id}/update', [ProductController::class,'productUpdate'])->name('products.update')->middleware('auth');
    // product tax
    Route::resource('product_tax', ProductTaxController::class)->middleware(['auth', 'XSS']);

    // product-coupon
    Route::resource('product-coupon', ProductCouponController::class)->middleware(['auth', 'XSS']);

    // orders
    Route::resource('orders', OrderController::class)->middleware(['auth', 'XSS']);
    Route::delete('order/product/{id}/{variant_id?}/{order_id}/{key}', [OrderController::class, 'delete_order_item'])->name('delete.order_item');
    Route::get('bank_transfer_order_show/{id}', [OrderController::class, 'bank_transfer_order_show'])->name('bank_transfer.order.show');
    Route::post('order_status_edit/{id}', [OrderController::class, 'StatusEdit'])->name('order.status.edit');

    // storefront
    Route::get('storeanalytic', [StoreAnalytic::class, 'index'])->name('storeanalytic')->middleware(['XSS', 'auth']);

    Route::resource('subscriptions', SubscriptionController::class)->middleware(['auth', 'XSS']);
    // Route::resource('custom-page', PageOptionController::class);

    // Route::resource('blog', BlogController::class)->middleware(['auth']);

    Route::get('/customer', [StoreController::class, 'customerindex'])->name('customer.index')->middleware(['auth', 'XSS']);

    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index')->middleware(['auth', 'XSS']);
    Route::get('settings', [SettingController::class, 'index'])->name('settings');
    Route::get('themes',[themeController::class,'index'])->name('themes.theme')->middleware(['auth','XSS']);
    Route::post('pwa-settings/{id}',[StoreController::class,'pwasetting'])->name('setting.pwa')->middleware(['auth','XSS']);
    // Route::resource('store-resource', StoreController::class)->middleware(['auth', 'XSS']);

    Route::get('profile', [DashboardController::class, 'profile'])->name('profile')->middleware(['auth', 'XSS']);

    Route::get('change-language/{lang}', [LanguageController::class, 'changeLanquage'])->name('change.language')->middleware(['auth', 'XSS']);

    Route::middleware(['auth'])->group(function () {
        Route::resource('stores', StoreController::class);
        Route::post('store-setting/{id}', [StoreController::class, 'savestoresetting'])->name('settings.store');
    });

    Route::middleware(['auth', 'XSS'])->group(function () {
        Route::get('change-language/{lang}', [LanguageController::class, 'changeLanquage'])->name('change.language');
        Route::get('manage-language/{lang}', [LanguageController::class, 'manageLanguage'])->name('manage.language');
        Route::post('store-language-data/{lang}', [LanguageController::class, 'storeLanguageData'])->name('store.language.data');
        Route::get('create-language', [LanguageController::class, 'createLanguage'])->name('create.language');
        Route::post('store-language', [LanguageController::class, 'storeLanguage'])->name('store.language');
        Route::delete('/lang/{lang}', [LanguageController::class, 'destroyLang'])->name('lang.destroy');
    });

    Route::middleware(['auth', 'XSS'])->group(function () {
        Route::get('store-grid/grid', [StoreController::class, 'grid'])->name('store.grid');
        Route::get('store-customDomain/customDomain', [StoreController::class, 'customDomain'])->name('store.customDomain');
        Route::get('store-subDomain/subDomain', [StoreController::class, 'subDomain'])->name('store.subDomain');
        Route::get('store-plan/{id}/plan', [StoreController::class, 'upgradePlan'])->name('plan.upgrade');
        Route::get('store-plan-active/{id}/plan/{pid}', [StoreController::class, 'activePlan'])->name('plan.active');
        Route::DELETE('store-delete/{id}', [StoreController::class, 'storedestroy'])->name('user.destroy');
        Route::DELETE('ownerstore-delete/{id}', [StoreController::class, 'ownerstoredestroy'])->name('ownerstore.destroy');
        Route::get('store-edit/{id}', [StoreController::class, 'storedit'])->name('user.edit');
        Route::Put('store-update/{id}', [StoreController::class, 'storeupdate'])->name('user.update');
    });

    Route::get('/store-change/{id}', [StoreController::class, 'changeCurrantStore'])->name('change_store')->middleware(['auth', 'XSS']);

    Route::middleware(['auth', 'XSS'])->group(function () {
        Route::get('change-language/{lang}', [LanguageController::class, 'changeLanquage'])->name('change.language');
        Route::get('manage-language/{lang}', [LanguageController::class, 'manageLanguage'])->name('manage.language');
        Route::post('store-language-data/{lang}', [LanguageController::class, 'storeLanguageData'])->name('store.language.data');
        Route::get('create-language', [LanguageController::class, 'createLanguage'])->name('create.language');
        Route::post('store-language', [LanguageController::class, 'storeLanguage'])->name('store.language');
        Route::delete('/lang/{lang}', [LanguageController::class, 'destroyLang'])->name('lang.destroy');
    });

    Route::get('/change/mode', [DashboardController::class, 'changeMode'])->name('change.mode');
    Route::get('profile', [DashboardController::class, 'profile'])->name('profile')->middleware(['auth', 'XSS']);
    Route::put('change-password', [DashboardController::class, 'updatePassword'])->name('update.password');
    Route::put('edit-profile', [DashboardController::class, 'editprofile'])->name('update.account')->middleware(['auth', 'XSS']);
    Route::get('storeanalytic', [StoreAnalytic::class, 'index'])->middleware('auth')->name('storeanalytic')->middleware(['XSS']);

    Route::middleware(['auth', 'XSS'])->group(function () {
        Route::post('business-setting', [SettingController::class, 'saveBusinessSettings'])->name('business.setting');
        Route::post('company-setting', [SettingController::class, 'saveCompanySettings'])->name('company.setting');
        Route::post('email-setting', [SettingController::class, 'saveEmailSettings'])->name('email.setting');
        Route::post('system-setting', [SettingController::class, 'saveSystemSettings'])->name('system.setting');
        Route::post('pusher-setting', [SettingController::class, 'savePusherSettings'])->name('pusher.setting');
        Route::post('test-mail', [SettingController::class, 'testMail'])->name('test.mail');
        Route::post('send-mail', [SettingController::class, 'testSendMail'])->name('test.send.mail');
        Route::get('settings', [SettingController::class, 'index'])->name('settings');
        Route::post('payment-setting', [SettingController::class, 'savePaymentSettings'])->name('payment.setting');
        Route::post('owner-payment-setting/{slug?}', [SettingController::class, 'saveOwnerPaymentSettings'])->name('owner.payment.setting');
        Route::post('owner-email-setting/{slug?}', [SettingController::class, 'saveOwneremailSettings'])->name('owner.email.setting');
        Route::post('owner-twilio-setting/{slug?}', [SettingController::class, 'saveOwnertwilioSettings'])->name('owner.twilio.setting');
        Route::get('pixel-setting/create',[SettingController::class,'CreatePixel'])->name('owner.pixel.create');
        Route::post('pixel-setting/{slug?}',[SettingController::class,'savePixelSettings'])->name('owner.pixel.setting');
        Route::delete('pixel-delete/{id}',[SettingController::class,'pixelDelete'])->name('pixel.delete');
        Route::any('/cookie-consent', [SettingController::class,'CookieConsent'])->name('cookie-consent'); 
        Route::post('cookie-setting', [SettingController::class, 'saveCookieSettings'])->name('cookie.setting');
    });
    //==================================== webhook setting ====================================//
    Route::resource('webhook', WebhookController::class)->middleware(['auth', 'XSS',]);

    Route::resource('product_categorie', ProductCategorieController::class)->middleware(['auth', 'XSS']);
    Route::resource('product_tax', ProductTaxController::class)->middleware(['auth', 'XSS']);

    //=================================product import/export=============================
    Route::get('shippings/export', [ShippingController::class, 'fileExport'])->name('shipping.export');
    Route::get('shipping/import/export', [ShippingController::class, 'fileImportExport'])->name('shipping.file.import');
    Route::post('shipping/import', [ShippingController::class, 'fileImport'])->name('shipping.import');

    Route::resource('shipping', ShippingController::class)->middleware(['auth', 'XSS']);

    Route::resource('location', LocationController::class)->middleware(['auth', 'XSS']);
    Route::resource('custom-page', PageOptionController::class)->middleware(['auth','Lang']);
    Route::resource('blog', BlogController::class)->middleware(['auth','Lang']);
    Route::get('blog-social', [BlogController::class, 'socialBlog'])->name('blog.social')->middleware(['auth', 'XSS']);
    Route::post('store-social-blog', [BlogController::class, 'storeSocialblog'])->name('store.socialblog')->middleware(['auth', 'XSS']);

    Route::get('/plan/error/{flag}', ['as' => 'error.plan.show', 'uses' => 'PaymentWallController@planerror']);
    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index')->middleware(['auth', 'XSS']);
    Route::get('/plans/create', [PlanController::class, 'create'])->name('plans.create')->middleware(['auth', 'XSS']);
    Route::post('/plans', [PlanController::class, 'store'])->name('plans.store')->middleware(['auth', 'XSS']);
    Route::get('/plans/edit/{id}', [PlanController::class, 'edit'])->name('plans.edit')->middleware(['auth', 'XSS']);
    Route::put('/plans/{id}', [PlanController::class, 'update'])->name('plans.update')->middleware(['auth', 'XSS']);
    Route::post('/user-plans/', [PlanController::class, 'userPlan'])->name('update.user.plan')->middleware(['auth', 'XSS']);
    Route::resource('orders', OrderController::class)->middleware(['auth', 'XSS']);

    Route::get('order/export', [OrderController::class, 'fileExport'])->name('order.export');
    Route::get('order-receipt/{id}', [OrderController::class, 'receipt'])->name('order.receipt')->middleware('auth');
    Route::middleware(['XSS'])->group(function () {
        Route::resource('rating', RattingController::class);
        Route::post('rating_view', [RattingController::class, 'rating_view'])->name('rating.rating_view');
        Route::get('rating/{slug?}/product/{id}', [RattingController::class, 'rating'])->name('rating');
        Route::post('stor_rating/{slug?}/product/{id}', [RattingController::class, 'stor_rating'])->name('stor_rating');
        Route::post('edit_rating/product/{id}', [RattingController::class, 'edit_rating'])->name('edit_rating');
        // Route::resource('subscriptions', SubscriptionController::class);
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/product-variants/create', [ProductController::class, 'productVariantsCreate'])->name('product.variants.create');
        Route::get('/product-variants/edit/{product_id}', [ProductController::class, 'productVariantsEdit'])->name('product.variants.edit');
        Route::post('/product-variants-possibilities/{product_id}', [ProductController::class, 'getProductVariantsPossibilities'])->name('product.variants.possibilities');
        Route::get('/get-product-variants-possibilities', [ProductController::class, 'getProductVariantsPossibilities'])->name('get.product.variants.possibilities');
        Route::get('products/grid', [ProductController::class, 'grid'])->name('product.grid');
        Route::delete('product/{id}/delete', [ProductController::class, 'fileDelete'])->name('products.file.delete');
        Route::delete('product/variant/{id}/{product_id}', [ProductController::class, 'VariantDelete'])->name('products.variant.delete');
    });

    //=================================product import/export=============================
    Route::get('products/export', [ProductController::class, 'fileExport'])->name('product.export');
    Route::get('product/import/export', [ProductController::class, 'fileImportExport'])->name('product.file.import');
    Route::post('product/import', [ProductController::class, 'fileImport'])->name('product.import');

    Route::get('/store-resource/edit/display/{id}', [StoreController::class, 'storeenable'])->name('store-resource.edit.display')->middleware(['auth', 'XSS']);
    Route::Put('/store-resource/display/{id}', [StoreController::class, 'storeenableupdate'])->name('store-resource.display')->middleware(['auth', 'XSS']);
    Route::resource('store-resource', StoreController::class)->middleware(['auth', 'XSS']);

    Route::get('productcoupon/import/export', [ProductCouponController::class, 'fileImportExport'])->name('productcoupon.file.import');
    Route::post('productcoupon/import', [ProductCouponController::class, 'fileImport'])->name('productcoupon.import');
    Route::get('productcoupon/export', [ProductCouponController::class, 'fileExport'])->name('productcoupon.export');

    Route::resource('coupons', CouponController::class)->middleware(['auth', 'XSS']);

    Route::post('prepare-payment', [PlanController::class, 'preparePayment'])->name('prepare.payment')->middleware(['auth', 'XSS']);

    Route::get('/payment/{code}', [PlanController::class, 'payment'])->name('payment')->middleware(['auth', 'XSS']);

    Route::get('{id}/{amount}/get-payment-status{slug?}', [PaypalController::class, 'planGetPaymentStatus'])->name('plan.get.payment.status')->middleware(['XSS']);
    Route::post('plan-pay-with-paypal', [PaypalController::class, 'planPayWithPaypal'])->name('plan.pay.with.paypal')->middleware(['auth', 'XSS']);

    Route::get('{id}/get-store-payment-status', [PaypalController::class, 'storeGetPaymentStatus'])->name('get.store.payment.status')->middleware(['auth', 'XSS']);
    Route::post('toyyibpay-prepare-plan', [ToyyibpayController::class, 'toyyibpayPaymentPrepare'])->middleware(['auth'])->name('toyyibpay.prepare.plan');
    Route::get('toyyibpay-payment-plan/{plan_id}/{amount}/{couponCode}', [ToyyibpayController::class, 'toyyibpayPlanGetPayment'])->middleware(['auth'])->name('plan.toyyibpay.callback');

    //sspay route
    Route::post('sspay-prepare-plan', [SspayController::class, 'SspayPaymentPrepare'])->middleware(['auth'])->name('sspay.prepare.plan');
    Route::get('sspay-payment-plan/{plan_id}/{amount}/{couponCode}', [SspayController::class, 'SspayPlanGetPayment'])->middleware(['auth'])->name('plan.sspay.callback');

    //paytab 
    Route::post('plan-pay-with-paytab', [PaytabController::class, 'planPayWithpaytab'])->middleware(['auth'])->name('plan.pay.with.paytab');
    Route::any('paytab-success/plan', [PaytabController::class, 'PaytabGetPayment'])->middleware(['auth'])->name('plan.paytab.success');

    // cashfree
    Route::post('cashfree/payments/store', [CashfreeController::class, 'cashfreePaymentStore'])->name('cashfree.payment');
    Route::any('cashfree/payments/success', [CashfreeController::class, 'cashfreePaymentSuccess'])->name('cashfreePayment.success');

    //aamarpay
    Route::post('/aamarpay/payment', [AamarpayController::class, 'pay'])->name('pay.aamarpay.payment');
    Route::any('/aamarpay/success/{data}', [AamarpayController::class, 'aamarpaysuccess'])->name('pay.aamarpay.success');

    // paytr
    Route::post('/paytr/payment', [PaytrController::class, 'PlanpayWithPaytr'])->name('plan.pay.with.paytr');
    Route::get('/paytr/sussess/', [PaytrController::class, 'paytrsuccess'])->name('pay.paytr.success');

    // benifits
    Route::any('/payment/initiate', [BenefitPaymentController::class, 'initiatePayment'])->name('benefit.initiate');
    Route::any('call_back', [BenefitPaymentController::class, 'call_back'])->name('benefit.call_back');

     //Iyzipay Route
    Route::post('iyzipay/prepare', [IyziPayController::class, 'initiatePayment'])->name('iyzipay.payment.init');
    Route::post('iyzipay/callback/plan/{id}/{amount}/{coupan_code?}', [IyziPayController::class, 'iyzipayCallback'])->name('iyzipay.payment.callback');

    //plan with payfast payment
    Route::post('payfast-plan', [PayfastController::class, 'index'])->name('payfast.payment')->middleware(['auth']);
    Route::get('payfast-plan/{success}', [PayfastController::class, 'success'])->name('payfast.payment.success')->middleware(['auth']);

    // Plan with yookassa payment
    Route::get('/plan/yookassa/payment', [YooKassaController::class,'planPayWithYooKassa'])->name('plan.pay.with.yookassa');
    Route::get('/plan/yookassa/{plan}', [YooKassaController::class,'planGetYooKassaStatus'])->name('plan.get.yookassa.status');

    // plan with midtrans payment
    Route::any('/midtrans', [MidtransController::class, 'planPayWithMidtrans'])->name('plan.get.midtrans');
    Route::any('/midtrans/callback', [MidtransController::class, 'planGetMidtransStatus'])->name('plan.get.midtrans.status');

    // plan pay with xendit payment
    Route::any('/xendit/payment', [XenditController::class, 'planPayWithXendit'])->name('plan.xendit.payment');
    Route::any('/xendit/payment/status', [XenditController::class, 'planGetXenditStatus'])->name('plan.xendit.status');

    // nepalste paymnet
    Route::post('/nepalste/payment', [NepalstePaymnetController::class, 'planPayWithnepalste'])->name('plan.pay.with.nepalste');
    Route::get('nepalste/status/', [NepalstePaymnetController::class, 'planGetNepalsteStatus'])->name('nepalste.status');
    Route::get('nepalste/cancel/', [NepalstePaymnetController::class, 'planGetNepalsteCancel'])->name('nepalste.cancel');

    //paiment pro
    Route::post('/paiementpro/payment', [PaiementProController::class, 'planPayWithpaiementpro'])->name('plan.pay.with.paiementpro');
    Route::get('paiementpro/status', [PaiementProController::class, 'planGetpaiementproStatus'])->name('paiementpro.status');

    //fedapay
    Route::post('/fedapay/payment', [FedapayController::class, 'planPayWithFedapay'])->name('plan.pay.with.fedapay');
    Route::get('fedapay/status', [FedapayController::class, 'planGetFedapayStatus'])->name('fedapay.status');

    //payhere
    Route::post('/payhere/payment', [PayHereController::class, 'planPayWithPayHere'])->name('plan.pay.with.payhere');
    Route::any('payhere/status', [PayHereController::class, 'planGetPayHereStatus'])->name('payhere.status');

    //cinetpay
    Route::post('/plan/cinetpay/payment', [CinetPayController::class,'planPayWithCinetPay'])->name('plan.pay.with.cinetpay');
    Route::post('/plan/cinetpay/payment/return', [CinetPayController::class,'planCinetPayReturn'])->name('plan.cinetpay.return');
    Route::post('/plan/cinetpay/payment/notify/', [CinetPayController::class,'planCinetPayNotify'])->name('plan.cinetpay.notify');

    Route::get(
        'qr-code',
        function () {
            return QrCode::generate();
        }
    );

    Route::resource('product-coupon', ProductCouponController::class)->middleware(['auth', 'XSS']);

    // Plan Purchase Payments methods

    Route::get('plan/prepare-amount', [PlanController::class, 'planPrepareAmount'])->name('plan.prepare.amount');
    Route::get('paystack-plan/{code}/{plan_id}', [PaymentController::class, 'paystackPlanGetPayment'])->name('paystack.plan.callback')->middleware(['auth']);
    Route::get('flutterwave-plan/{code}/{plan_id}', [PaymentController::class, 'flutterwavePlanGetPayment'])->name('flutterwave.plan.callback')->middleware(['auth']);
    Route::get('razorpay-plan/{code}/{plan_id}', [PaymentController::class, 'razorpayPlanGetPayment'])->name('razorpay.plan.callback')->middleware(['auth']);
    Route::post('mercadopago-prepare-plan', [PaymentController::class, 'mercadopagoPaymentPrepare'])->name('mercadopago.prepare.plan')->middleware(['auth']);
    Route::any('plan-mercado-callback/{plan_id}', [PaymentController::class, 'mercadopagoPaymentCallback'])->name('plan.mercado.callback')->middleware(['auth']);

    Route::post('paytm-prepare-plan', [PaymentController::class, 'paytmPaymentPrepare'])->name('paytm.prepare.plan')->middleware(['auth']);
    Route::post('paytm-payment-plan', [PaymentController::class, 'paytmPlanGetPayment'])->name('plan.paytm.callback')->middleware(['auth']);

    Route::post('mollie-prepare-plan', [PaymentController::class, 'molliePaymentPrepare'])->name('mollie.prepare.plan')->middleware(['auth']);
    Route::get('mollie-payment-plan/{slug}/{plan_id}', [PaymentController::class, 'molliePlanGetPayment'])->name('plan.mollie.callback')->middleware(['auth']);

    Route::post('coingate-prepare-plan', [PaymentController::class, 'coingatePaymentPrepare'])->name('coingate.prepare.plan')->middleware(['auth']);
    Route::get('coingate-payment-plan', [PaymentController::class, 'coingatePlanGetPayment'])->name('coingate.mollie.callback')->middleware(['auth']);

    Route::post('skrill-prepare-plan', [PaymentController::class, 'skrillPaymentPrepare'])->name('skrill.prepare.plan')->middleware(['auth']);
    Route::get('skrill-payment-plan', [PaymentController::class, 'skrillPlanGetPayment'])->name('plan.skrill.callback')->middleware(['auth']);
    Route::post('store/{slug?}', [StoreController::class, 'changeTheme'])->name('store.changetheme');
    Route::get('store/{slug?}/login', [CustomerLoginController::class, 'showLoginForm'])->name('customer.loginform');
    Route::get('{slug?}/edit-products/{theme?}', [StoreController::class, 'Editproducts'])->name('store.editproducts')->middleware(['auth', 'XSS']);
    //PLAN PAYMENTWALL
    Route::post('/planpayment', [PaymentWallController::class, 'planpay'])->name('paymentwall')->middleware(['auth', 'XSS']);
    Route::post('/paymentwall-payment/{plan}', [PaymentWallController::class, 'planPayWithPaymentWall'])->name('paymentwall.payment')->middleware(['auth', 'XSS']);

    //  Bank Transfer
    Route::post('/bank_transfer', [PaymentController::class, 'bank_transfer'])->name('plan.bank_transfer');
    Route::get('bank_transfer_show/{id}', [PaymentController::class, 'bank_transfer_show'])->name('bank_transfer.show');
    Route::post('status_edit/{id}', [PaymentController::class, 'StatusEdit'])->name('status.edit');
    Route::delete('/planorder_delete/{id}', [PlanController::class, 'planorderdestroy'])->name('planorder.destroy');

    Route::post('{slug?}/store-edit-products/{theme?}', [StoreController::class, 'StoreEditProduct'])->name('store.storeeditproducts')->middleware(['auth']);
    // Route::delete('{slug?}/{theme}/brand/{id}/delete/', [StoreController::class ,'brandfileDelete'])->name('brand.file.delete')->middleware(['auth']);
    Route::post('product-image-delete', [StoreController::class, 'image_delete'])->name('product.image.delete')->middleware(['auth', 'XSS']);

    // Email Templates
    Route::get('email_template_lang/{lang?}', [EmailTemplateController::class, 'emailTemplate'])->name('email_template')->middleware(['auth', 'XSS']);
    Route::get('email_template_lang/{id}/{lang?}', [EmailTemplateController::class, 'manageEmailLang'])->name('manage.email.language')->middleware(['auth', 'XSS']);
    Route::put('email_template_lang/{id}/', [EmailTemplateController::class, 'updateEmailSettings'])->name('updateEmail.settings')->middleware(['auth', 'XSS']);
    Route::put('email_template_store/{pid}', [EmailTemplateController::class, 'storeEmailLang'])->name('store.email.language')->middleware(['auth', 'XSS']);
    Route::put('email_template_status/{id}', [EmailTemplateController::class, 'updateStatus'])->name('status.email.language')->middleware(['auth', 'XSS']);
    Route::put('email_template_status/{id}', [EmailTemplateController::class, 'updateStatus'])->name('email_template.update')->middleware(['auth', 'XSS']);

    //=================================Plan Request Module ====================================//
    Route::get('plan_request', [PlanRequestController::class, 'index'])->name('plan_request.index')->middleware(['auth', 'XSS']);
    Route::get('request_frequency/{id}', [PlanRequestController::class, 'requestView'])->name('request.view')->middleware(['auth', 'XSS']);
    Route::get('request_send/{id}', [PlanRequestController::class, 'userRequest'])->name('send.request')->middleware(['auth', 'XSS']);
    Route::get('request_response/{id}/{response}', [PlanRequestController::class, 'acceptRequest'])->name('response.request')->middleware(['auth', 'XSS']);
    Route::get('request_cancel/{id}', [PlanRequestController::class, 'cancelRequest'])->name('request.cancel')->middleware(['auth', 'XSS']);

    /*==================================Recaptcha====================================================*/
    Route::post('/recaptcha-settings', [SettingController::class, 'recaptchaSettingStore'])->name('recaptcha.settings.store')->middleware(['auth', 'XSS']);

    Route::get('/remove-coupn', [StoreController::class, 'remcoup'])->name('apply.removecoupn');

    Route::any('user-reset-password/{id}', [StoreController::class, 'employeePassword'])->name('user.reset');
    Route::post('user-reset-password/{id}', [StoreController::class, 'employeePasswordReset'])->name('user.password.update');

    // ===================================customer view==========================================

    Route::get('/customer', [StoreController::class, 'customerindex'])->name('customer.index')->middleware(['auth', 'XSS']);
    Route::get('/customer/view/{id}', [StoreController::class, 'customershow'])->name('customer.show')->middleware(['auth', 'XSS']);
    Route::get('customer/export', [StoreController::class, 'fileExport'])->name('customer.export');


    //=========================================storage setting ==========================================
    Route::post('storage-settings', [SettingController::class, 'storageSettingStore'])->name('storage.setting.store')->middleware(['auth', 'XSS']);
});
Route::post('subscriptions/{id}', [SubscriptionController::class, 'store_email'])->name('subscriptions.store_email');
Route::get('get-products-variant-quantity', [ProductController::class, 'getProductsVariantQuantity'])->name('get.products.variant.quantity');
// customer side
Route::get('page/{slug?}', [StoreController::class, 'pageOptionSlug'])->name('pageoption.slug')->middleware('DomainCheck');
Route::get('store-blog/{slug?}', [StoreController::class, 'StoreBlog'])->name('store.blog');
Route::get('store-blog-view/{slug?}/blog/{id}', [StoreController::class, 'StoreBlogView'])->name('store.store_blog_view');

Route::get('store/{slug?}', [StoreController::class, 'storeSlug'])->name('store.slug')->middleware(['XSS','DomainCheck']);
Route::get('store/{slug?}/categorie/{name?}', [StoreController::class, 'product'])->name('store.categorie.product')->middleware('XSS');
Route::get('user-cart-item/{slug?}/cart/{product_id?}/{quantity?}/{variant_id?}', [StoreController::class, 'StoreCart'])->name('store.cart');
Route::get('checkoutPermission/{store?}', [StoreController::class, 'CheckoutPermit'])->name('checkout.permission');
Route::get('user-address/{slug?}/useraddress', [StoreController::class, 'userAddress'])->name('user-address.useraddress');
Route::get('store-payment/{slug?}/userpayment', [StoreController::class, 'userPayment'])->name('store-payment.payment');
Route::get('store/{slug?}/product/{id}', [StoreController::class, 'productView'])->name('store.product.product_view');
Route::post('user-product_qty/{slug?}/product/{id}/{variant_name?}', [StoreController::class, 'productqty'])->name('user-product_qty.product_qty');
Route::post('customer/{slug}', [StoreController::class, 'customer'])->name('store.customer');
Route::post('user-location/{slug}/location/{id}', [StoreController::class, 'UserLocation'])->name('user.location');
Route::post('user-shipping/{slug}/shipping/{id}', [StoreController::class, 'UserShipping'])->name('user.shipping');
Route::post('{slug}/user-city/{city}',[storecontroller::class,'userCity'])->name('user.city');
Route::post('save-rating/{slug?}', [StoreController::class, 'saverating'])->name('store.saverating');
Route::delete('delete_cart_item/{slug?}/product/{id}/{variant_id?}', [StoreController::class, 'delete_cart_item'])->name('delete.cart_item');
Route::delete('delete_wishlist_item/{slug?}/product/{id}/', [StoreController::class, 'delete_wishlist_item'])->name('delete.wishlist_item');

Route::get('store-complete/{slug?}/{id}', [StoreController::class, 'complete'])->name('store-complete.complete');

Route::any('add-to-cart/{slug?}/{id}/{variant_id?}', [StoreController::class, 'addToCart'])->name('user.addToCart');

Route::group(
    ['middleware' => ['XSS']],
    function () {
        Route::get('order', [StripePaymentController::class, 'index'])->name('order.index');
        Route::get('/stripe/{code}', [StripePaymentController::class, 'stripe'])->name('stripe')->middleware('auth');
        Route::post('/stripe/{slug?}', [StripePaymentController::class, 'stripePost'])->name('stripe.post');
        Route::post('stripe-payment', [StripePaymentController::class, 'addpayment'])->name('stripe.payment');
    }
);

// product paypal payments
Route::post('pay-with-paypal/{slug?}', [PaypalController::class, 'PayWithPaypal'])->name('pay.with.paypal')->middleware(['XSS']);
Route::get('{id}/get-payment-status{slug?}', [PaypalController::class, 'GetPaymentStatus'])->name('get.payment.status')->middleware(['XSS']);

Route::get('{slug?}/customerorder/{id}', [StoreController::class, 'customerorder'])->name('customer.order')->middleware('customerauth');
Route::get('{slug?}/order/{id}', [StoreController::class, 'userorder'])->name('user.order');

Route::post('{slug?}/whatsapp', [StoreController::class, 'whatsapp'])->name('user.whatsapp');
Route::post('{slug?}/telegram', [StoreController::class, 'telegram'])->name('user.telegram');

Route::post('{slug?}/cod', [StoreController::class, 'cod'])->name('user.cod');
Route::post('{slug?}/bank_transfer', [StoreController::class, 'bank_transfer'])->name('user.bank_transfer');

Route::get('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('apply.coupon')->middleware(['auth', 'XSS']);

Route::get('/apply-productcoupon', [ProductCouponController::class, 'applyProductCoupon'])->name('apply.productcoupon');

Route::get('change-language-store/{slug?}/{lang}', [LanguageController::class, 'changeLanquageStore'])->name('change.languagestore')->middleware(['XSS']);
//    Payments Callbacks

Route::get('paystack/{slug}/{code}/{order_id}', [PaymentController::class, 'paystackPayment'])->name('paystack');
Route::get('flutterwave/{slug}/{tran_id}/{order_id}', [PaymentController::class, 'flutterwavePayment'])->name('flutterwave');
Route::get('razorpay/{slug}/{pay_id}/{order_id}', [PaymentController::class, 'razerpayPayment'])->name('razorpay');
Route::post('{slug}/paytm/prepare-payments/', [PaymentController::class, 'paytmOrder'])->name('paytm.prepare.payments'); //product
Route::post('paytm/callback/', [PaymentController::class, 'paytmCallback'])->name('paytm.callback'); //product
Route::post('{slug}/mollie/prepare-payments/', [PaymentController::class, 'mollieOrder'])->name('mollie.prepare.payments');
Route::get('{slug}/{order_id}/mollie/callback/', [PaymentController::class, 'mollieCallback'])->name('mollie.callback');
Route::post('{slug}/mercadopago/prepare-payments/', [PaymentController::class, 'mercadopagoPayment'])->name('mercadopago.prepare');
Route::any('{slug}/mercadopago/callback/', [PaymentController::class, 'mercadopagoCallback'])->name('mercado.callback');

Route::post('{slug}/coingate/prepare-payments/', [PaymentController::class, 'coingatePayment'])->name('coingate.prepare');
Route::get('coingate/callback', [PaymentController::class, 'coingateCallback'])->name('coingate.callback');

Route::post('{slug}/skrill/prepare-payments/', [PaymentController::class, 'skrillPayment'])->name('skrill.prepare.payments');
Route::get('skrill/callback', [PaymentController::class, 'skrillCallback'])->name('skrill.callback');


//ORDER PAYMENTWALL
Route::post('{slug}/paymentwall/store-slug/', [StoreController::class, 'paymentwallstoresession'])->name('paymentwall.session.store');
Route::any('{slug}/order/error/{flag}', [PaymentWallController::class, 'orderpaymenterror'])->name('order.callback.error');
Route::any('/{slug}/paymentwall/order', [PaymentWallController::class, 'orderindex'])->name('paymentwall.index');
Route::post('/{slug}/order-pay-with-paymentwall/', [PaymentWallController::class, 'orderPayWithPaymentwall'])->name('order.pay.with.paymentwall');

//payment toyyibpay

Route::post('{slug}/toyyibpay/prepare-payments/', [ToyyibpayController::class, 'toyyibpaypayment'])->name('toyyibpay.prepare.payments');
Route::get('toyyibpay/callback/{get_amount}/{slug?}', [ToyyibpayController::class, 'toyyibpaycallpack'])->name('toyyibpay.callback');

// sspay
Route::post('{slug}/sspay/prepare-payments/', [SspayController::class, 'Sspaypayment'])->name('sspay.prepare.payments');
Route::get('sspay/callback/{get_amount}/{slug?}', [SspayController::class, 'Sspaycallpack'])->name('sspay.callback');

//paytab 
Route::post('pay-with-paytab/{slug}', [PaytabController::class, 'PayWithpaytab'])->name('pay.with.paytab');
Route::any('paytab-success/store', [PaytabController::class, 'PaytabGetPaymentCallback'])->name('paytab.success');

// cashfree
Route::post('{slug}/cashfree/payments/store', [CashfreeController::class, 'payWithCashfree'])->name('store.cashfree.initiate');
Route::any('store/cashfree/payments/success', [CashfreeController::class, 'storeCashfreePaymentSuccess'])->name('store.cashfreePayment.success');

// aamarpay
Route::post('{slug}/aamarpay/payment', [AamarpayController::class, 'payWithAamarpay'])->name('store.pay.aamarpay.payment');
Route::any('aamarpay/success/store/{data}', [AamarpayController::class, 'storeAamarpaysuccess'])->name('store.pay.aamarpay.success');

// paytr
Route::post('store/paytr/payment/{slug}', [PaytrController::class, 'OrderpayWithPaytr'])->name('order.pay.with.paytr');
Route::get('store/paytr/sussess/', [PaytrController::class, 'OrderPaytrsuccess'])->name('order.pay.success');

// payfast payment
Route::post('{slug}/payfast/prepare-payments/', [PayfastController::class, 'payfastpayment'])->name('payfast.prepare.payment');
Route::get('{slug}/payfast/{success}', [PayfastController::class, 'payfastcallback'])->name('payfast.callback');

Route::post('iyzipay/prepare-payments/{slug}', [IyziPayController::class, 'iyzipaypayment'])->name('iyzipay.prepare.payment');
Route::post('iyzipay/callback/{slug}/{amount}', [IyziPayController::class, 'iyzipaypaymentCallback'])->name('iyzipay.callback');

Route::get('payment-with-yookassa/{slug}', [YooKassaController::class, 'storePayWithYookassa'])->name('store.with.yookassa');
Route::get('payment-yookassa-status', [YooKassaController::class, 'getStorePaymentStatus'])->name('store.yookassa.status');

Route::any('payment-with-midtrans/{slug}', [MidtransController::class, 'orderPayWithMidtrans'])->name('order.pay.with.midtrans');
Route::any('payment-midtrans-status/', [MidtransController::class, 'getOrderPaymentStatus'])->name('order.midtrans.status');

Route::any('/order-pay-with-xendit/{slug}', [XenditController::class, 'orderPayWithXendit'])->name('order.pay.with.xendit');
Route::any('/order-xendit-status', [XenditController::class, 'getOrderPaymentStatus'])->name('order.xendit.status');

//nepalste
Route::any('/order-with-nepalste/{slug}', [NepalstePaymnetController::class, 'orderPayWithnepalste'])->name('order.with.nepalste');
Route::any('/order-nepalste-status', [NepalstePaymnetController::class, 'orderGetNepalsteStatus'])->name('order.nepalste.status');
Route::get('/order-nepalste/cancel', [NepalstePaymnetController::class, 'orderGetNepalsteCancel'])->name('order.nepalste.cancel');

//paiement pro
Route::any('/order-with-paiementpro/{slug}', [PaiementProController::class, 'orderPayWithpaiementpro'])->name('order.with.paiementpro');
Route::any('/order-paiementpro-status', [PaiementProController::class, 'orderGetpaiementproStatus'])->name('order.paiementpro.status');

//fedapay
Route::any('/order-with-fedapay/{slug}', [FedapayController::class, 'orderPayWithfedapay'])->name('order.with.fedapay');
Route::any('/order-fedapay-status', [FedapayController::class, 'orderGetfedapayStatus'])->name('order.fedapay.status');

//payhere
Route::post('/order-payhere-payment/{slug}', [PayHereController::class, 'orderPayWithPayHere'])->name('order.with.payhere');
Route::get('/order-payhere-status', [PayHereController::class, 'orderGetPayHereStatus'])->name('order.payhere.status');

//cinet pay
Route::post('/order-cinetpay-payment/{slug}', [CinetPayController::class, 'orderPayWithcinetpay'])->name('order.with.cinetpay');
Route::get('/order-cinetpay-status', [CinetPayController::class, 'orderGetcinetpayStatus'])->name('order.cinetpay.status');
Route::post('/order/cinetpay/payment/return', [CinetPayController::class,'orderCinetPayReturn'])->name('order.cinetpay.return');
Route::post('/order/cinetpay/payment/notify/', [CinetPayController::class,'orderCinetPayNotify'])->name('order.cinetpay.notify');

//================================= Custom Massage Page ====================================//
Route::post('/store/custom-msg/{slug}', [StoreController::class, 'customMassage'])->name('customMassage');
Route::post('store/get-massage/{slug}', [StoreController::class, 'getWhatsappUrl'])->name('get.whatsappurl');
Route::get('store/remove-session/{slug}', [StoreController::class, 'removeSession'])->name('remove.session');

//WISH LIST
Route::get('store/{slug}/wishlist', [StoreController::class, 'Wishlist'])->name('store.wishlist');
Route::post('store/{slug}/addtowishlist/{id}', [StoreController::class, 'AddToWishlist'])->name('store.addtowishlist');

Route::post('store/{slug}/downloadable_prodcut', [StoreController::class, 'downloadable_prodcut'])->name('user.downloadable_prodcut');
/*=================================Customer Login==========================================*/

Route::get('{slug}/user-create', [StoreController::class, 'userCreate'])->name('store.usercreate')->middleware('DomainCheck');
Route::post('{slug}/user-create', [StoreController::class, 'userStore'])->name('store.userstore');

Route::get('{slug}/customer-login', [CustomerLoginController::class, 'showLoginForm'])->name('customer.loginform')->middleware('DomainCheck');
Route::post('{slug}/customer-login/{cart?}', [CustomerLoginController::class, 'login'])->name('customer.login');

Route::get('{slug}/customer-home', [StoreController::class, 'customerHome'])->name('customer.home')->middleware('customerauth');

Route::get('{slug}/customer-profile/{id}', [CustomerLoginController::class, 'profile'])->name('customer.profile')->middleware('customerauth');
Route::put('{slug}/customer-profile/{id}', [CustomerLoginController::class, 'profileUpdate'])->name('customer.profile.update')->middleware('customerauth');
Route::put('{slug}/customer-profile-password/{id}', [CustomerLoginController::class, 'updatePassword'])->name('customer.profile.password')->middleware('customerauth');
Route::post('{slug}/customer-logout', [CustomerLoginController::class, 'logout'])->name('customer.logout');

Route::resource('roles', RoleController::class)->middleware(['auth','XSS']);
Route::resource('users',UserController::class)->middleware(['auth','XSS']);
Route::get('users/reset/{id}',[UserController::class,'reset'])->name('users.reset')->middleware(['auth','XSS']);
Route::post('users/reset/{id}',[UserController::class,'updatePassword'])->name('users.resetpassword')->middleware(['auth','XSS']);
Route::resource('permissions', PermissionController::class)->middleware(['auth','XSS',]);

//==================================== cache setting ====================================//
Route::get('/config-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    return redirect()->back()->with('success', 'Clear Cache successfully.');
});



//==================================== Pos Routes ====================================//
Route::middleware(['auth', 'XSS'])->group(function () {
    Route::resource('pos', PosController::class);
    Route::post('/cartdiscount', [PosController::class, 'cartdiscount'])->name('cartdiscount');
    Route::get('product-categories', [ProductCategorieController::class, 'getProductCategories'])->name('product.categories');
    Route::get('search-products', [ProductController::class, 'searchProducts'])->name('search.products');
    Route::get('addToCart/{id}/{session}', [ProductController::class, 'addToCart']);
    Route::patch('update-cart', [ProductController::class, 'updateCart']);
    Route::delete('remove-from-cart', [ProductController::class, 'removeFromCart']);
    Route::post('empty-cart', [ProductController::class, 'emptyCart']);
    Route::get('printview/pos', [PosController::class, 'printView'])->name('pos.printview');
    Route::get('pos/data/store', [PosController::class, 'store'])->name('pos.data.store');
    //variant
    Route::get('pos-productVariant/{id}/{session}', [ProductController::class, 'productVariant']);
    Route::get('addToCartVariant/{id}/{session}/{variation_id?}', [ProductController::class, 'addToCartVariant'])->name('addToCartVariant');
});

Route::get('express-checkout/create/{id}/',[ExpresscheckoutController::class,'create'])->name('expresscheckout.create')->middleware(['auth','XSS']);
Route::post('express-checkout/store',[ExpresscheckoutController::class,'store'])->name('expresscheckout.store')->middleware(['auth','XSS']);
Route::get('express-checkout/edit/{id}',[ExpresscheckoutController::class,'edit'])->name('expresscheckout.edit')->middleware(['auth','XSS']);
Route::post('express-checkout/update/{id}',[ExpresscheckoutController::class,'update'])->name('expresscheckout.update')->middleware(['auth','XSS']);
Route::delete('express-checkout/delete/{id}',[ExpresscheckoutController::class,'destroy'])->name('expresscheckout.destroy');

Route::post('chatgptkey',[SettingController::class,'chatgptkey'])->name('settings.chatgptkey');

Route::get('generate/{template_name}',[AiTemplateController::class,'create'])->name('generate')->middleware(['auth','XSS']);
Route::post('generate/keywords/{id}',[AiTemplateController::class,'getKeywords'])->name('generate.keywords');
Route::post('generate/response',[AiTemplateController::class,'aiGenerate'])->name('generate.response');

Route::post('disable-language',[LanguageController::class,'disableLang'])->name('disablelanguage')->middleware(['auth','XSS']);


// store links (Admin Side)
Route::get('/store-links/{id}', [StoreController::class, 'storelinks'])->middleware('XSS', 'auth')->name('store.links');

// Company Login (Admin Side)
Route::get('users/{id}/login-with-owner', [UserController::class, 'LoginWithOwner'])->middleware('XSS', 'auth')->name('login.with.owner');
Route::get('login-with-owner/exit', [UserController::class, 'ExitOwner'])->middleware('XSS', 'auth')->name('exit.owner');

// Admin Hub
Route::get('owner-info/{id}', [UserController::class, 'OwnerInfo'])->name('owner.info');
Route::post('user-unable', [UserController::class, 'UserUnable'])->name('user.unable');

Route::get('plan-trial/{id}', [PlanController::class,'planTrial'])->name('plan.trial')->middleware(['auth', 'XSS']);
Route::post('plans/plan-active', [PlanController::class, 'planActive'])->name('plan.enable')->middleware(['auth', 'XSS']);
Route::delete('plans/delete/{id}',[PlanController::class,'destroy'])->name('plans.destroy')->middleware(['auth', 'XSS']);

Route::get('/refund/{id}/{user_id}', [PlanController::class, 'refund'])->name('order.refund');

// password
Route::get('user-login/{id}', [StoreController::class, 'LoginManage'])->name('users.login');
Route::get('owner-user-login/{id}', [UserController::class, 'UserLoginManage'])->name('owner.users.login');


//=================================Customdomain Request Module ====================================//

Route::get('custom_domain_request', [CustomDomainRequestController::class, 'index'])->name('custom_domain_request.index')->middleware(['auth', 'XSS']);
Route::delete('custom_domain_request/{id}/destroy', [CustomDomainRequestController::class, 'destroy'])->name('custom_domain_request.destroy')->middleware(['XSS']);
Route::get('custom_domain_request/{id}/{response}', [CustomDomainRequestController::class, 'updateRequestStatus'])->name('custom_domain_request.request')->middleware(['auth', 'XSS']);


// Reffral Code
Route::get('referral-program/company', [ReferralProgramController::class, 'companyIndex'])->name('referral-program.company')->middleware(['auth', 'XSS']);
Route::resource('referral-program', ReferralProgramController::class)->middleware(['auth', 'XSS']);
Route::get('request-amount-sent/{id}', [ReferralProgramController::class, 'requestedAmountSent'])->name('request.amount.sent')->middleware(['auth', 'XSS']);
Route::get('request-amount-cancel/{id}', [ReferralProgramController::class, 'requestCancel'])->name('request.amount.cancel')->middleware(['auth', 'XSS']);
Route::post('request-amount-store/{id}', [ReferralProgramController::class, 'requestedAmountStore'])->name('request.amount.store')->middleware(['auth', 'XSS']);
Route::get('request-amount/{id}/{status}', [ReferralProgramController::class, 'requestedAmount'])->name('amount.request')->middleware(['auth', 'XSS']);