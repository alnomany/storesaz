<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Ratting;
use App\Models\Utility;
use App\Models\ProductTax;
use Illuminate\Http\Request;
use App\Exports\ProductExport;
use App\Imports\ProductImport;
use App\Models\Product_images;
use App\Models\Expresscheckout;
use Illuminate\Validation\Rule;
use App\Models\ProductCategorie;
use App\Models\ProductVariantOption;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        if (Auth::check()) {
            $userlang = \Auth::user()->lang;
            \App::setLocale($userlang);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('Manage Products')){
            $user = \Auth::user();
            $store_id = Store::where('id', $user->current_store)->first();
            $products = Product::where('store_id', $store_id->id)->orderBy('id', 'DESC')->get();
            $productcategorie = ProductCategorie::where('store_id', $store_id->id)->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            return view('product.index', compact('products', 'productcategorie'));
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
        if(\Auth::user()->can('Create Products')){
            $user = \Auth::user();
            $store_id = Store::where('id', $user->current_store)->first();
            $product_categorie = ProductCategorie::where('store_id', $store_id->id)->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $product_tax = ProductTax::where('store_id', $store_id->id)->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('product.create', compact('product_categorie', 'product_tax'));
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
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
        
    
        if(\Auth::user()->can('Create Products')){
             $user = \Auth::user();
            $store_id = Store::where('id', $user->current_store)->first();

 
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'SKU' => ['required',Rule::unique('products')->where(function ($query) use ($store_id) {
                     return $query->where('store_id', $store_id->id);
                     }),
                     ],
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                $msg['flag'] = 'error';
                $msg['msg'] = $messages->first();

                return $msg;
            }
            if ($request->enable_product_variant == '') {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'price' => 'required',
                        'quantity' => 'required',
                        'name' => 'required',
                        'SKU' => 'required',
                        'last_price' => 'required',
                    ]
                );
            }
            if ($request->enable_product_variant == 'on') {
                if (!empty($request->verians)) {
                    foreach ($request->verians as $k => $items) {
                        foreach ($items as $item_k => $item) {
                            if (empty($item) && $item < 0) {
                                $msg['flag'] = 'error';
                                $msg['msg'] = __('Please Fill The Form');

                                return $msg;
                            }
                        }
                    }
                } else {
                    $msg['flag'] = 'error';
                    $msg['msg'] = __('Please Add Variants');

                    return $msg;
                }
            }


            $file_name = [];
            if (!empty($request->multiple_files) && count($request->multiple_files) > 0) {
                foreach ($request->multiple_files as $key => $file) {
                    $image_size = $file->getSize();
                    $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                    if($result==1){
                        $filenameWithExt = $file->getClientOriginalName();
                        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();
                        $fileNameToStore = $filename . '_' . time() . rand(1,100) .'.' . $extension;
                        $settings = Utility::getStorageSetting();

                        $dir = 'uploads/product_image/';
                        $path = Utility::keyWiseUpload_file($request, 'multiple_files', $fileNameToStore, $dir, $key, []);
                        if ($path['flag'] == 1) {
                            $url = $path['url'];
                            $file_name[] = $fileNameToStore;
                        }
                    }
                }
            }
            if (!empty($request->is_cover_image)) {
                $image_size = $request->file('is_cover_image')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                if($result==1){
                    $filenameWithExt = $request->file('is_cover_image')->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $request->file('is_cover_image')->getClientOriginalExtension();
                    $fileNameToStores = $filename . '_' . time() . '.' . $extension;
                    $settings = Utility::getStorageSetting();
                    if ($settings['storage_setting'] == 'local') {
                        $dir = 'uploads/is_cover_image/';
                    } else {
                        $dir = 'uploads/is_cover_image/';
                    }
                    $path = Utility::upload_file($request, 'is_cover_image', $fileNameToStores, $dir, []);
                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }
            }

            if (!empty($request->attachment)) {
                $image_size = $request->file('attachment')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                if($result==1){
                    $filenameWithExt = $request->file('attachment')->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $request->file('attachment')->getClientOriginalExtension();
                    $fileAttachment = time() . '_' .$filename .  '.' . $extension;
                    $settings = Utility::getStorageSetting();
                    if ($settings['storage_setting'] == 'local') {
                        $dir = 'uploads/is_cover_image/';
                    } else {
                        $dir = 'uploads/is_cover_image/';
                    }
                    $path = Utility::upload_file($request, 'attachment', $fileAttachment, $dir, []);
                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                    // $dir = storage_path('uploads/is_cover_image/');
                    // if (!file_exists($dir)) {
                    //     mkdir($dir, 0777, true);
                    // }
                    // $path = $request->file('attachment')->storeAs('uploads/is_cover_image/', $fileAttachment);
                }
            }

            if (!empty($request->downloadable_prodcut)) {
                $image_size = $request->file('downloadable_prodcut')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
                if($result==1){
                    $filenameWithExt = $request->file('downloadable_prodcut')->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $request->file('downloadable_prodcut')->getClientOriginalExtension();
                    $filedownloadable1 = $filename . '_' . time() . '.' . $extension;
                    $settings = Utility::getStorageSetting();

                    if ($settings['storage_setting'] == 'local') {
                        $dir = 'uploads/downloadable_prodcut/';
                    } else {
                        $dir = 'uploads/downloadable_prodcut/';
                    }

                    $filedownloadable = str_replace(' ', '_', $filedownloadable1);
                    $path = Utility::upload_file($request, 'downloadable_prodcut', $filedownloadable, $dir, []);

                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }
            }

            if (!empty($request->product_tax)) {
                if (count($request->product_tax) > 1 && in_array(0, $request->product_tax)) {
                    $msg['flag'] = 'error';
                    $msg['msg'] = __('Please select valid tax');

                    return $msg;
                }
            }
            if (!empty($request->product_categorie)) {
                if (count($request->product_categorie) > 1 && in_array(0, $request->product_categorie)) {
                    $msg['flag'] = 'error';
                    $msg['msg'] = __('Please select valid Categorie');

                    return $msg;
                }
            }

            $user = \Auth::user();
            $creator = User::find($user->creatorId());
            // $total_product = $user->countProducts();
            $total_product = $user->countStoreProducts($user->current_store);
            $plan = Plan::find($creator->plan);

            if ($total_product < $plan->max_products || $plan->max_products == -1) {
                $product = new Product();
                $product['store_id'] = $store_id->id;
                $product['name'] = $request->name;
                if (!empty($request->product_categorie)) {
                    $product['product_categorie'] = implode(',', $request->product_categorie);
                } else {
                    $product['product_categorie'] = $request->product_categorie;
                }
                if (!empty($request->price)) {
                    $product['price'] = !empty($request->price) ? $request->price : '0';
                    $product['last_price'] = !empty($request->last_price) ? $request->last_price : '0';
                    $product['purchase_price'] = !empty($request->purchase_price) ? $request->purchase_price : '0';

                    
                }
                if (!empty($request->quantity)) {
                    $product['quantity'] = !empty($request->quantity) ? $request->quantity : '0';
                }
                $product['SKU'] = $request->SKU;
                if (!empty($request->product_tax)) {
                    $product['product_tax'] = implode(',', $request->product_tax);
                } else {
                    $product['product_tax'] = $request->product_tax;
                }

                $product['custom_field_1'] = $request->custom_field_1;
                $product['custom_value_1'] = $request->custom_value_1;
                $product['custom_field_2'] = $request->custom_field_2;
                $product['custom_value_2'] = $request->custom_value_2;
                $product['custom_field_3'] = $request->custom_field_3;
                $product['custom_value_3'] = $request->custom_value_3;
                $product['custom_field_4'] = $request->custom_field_4;
                $product['custom_value_4'] = $request->custom_value_4;
                $product['product_display'] = isset($request->product_display) ? 'on' : 'off';
                $product['enable_product_variant'] = isset($request->enable_product_variant) ? 'on' : 'off';
                $product['variants_json'] = $request->hiddenVariantOptions;
                $product['is_cover'] = !empty($fileNameToStores) ? $fileNameToStores : '';
                $product['attachment'] = !empty($fileAttachment) ? $fileAttachment : '';
                $product['downloadable_prodcut'] = !empty($filedownloadable) ? $filedownloadable : '';
                $product['description'] = $request->description;
                $product['specification'] = $request->specification;
                $product['detail'] = $request->detail;
                $product['created_by'] = \Auth::user()->creatorId();
                $product->save();

                if (!empty($file_name)) {
                    foreach ($file_name as $file) {
                        $objStore = Product_images::create(
                            [
                                'product_id' => $product->id,
                                'product_images' => $file,
                            ]
                        );
                    }
                }

                if ($request->enable_product_variant == 'on') {
                    $product->variants_json = json_decode($product->variants_json, true);

                    $variant_options = array_column($product->variants_json, 'variant_options');

                    $possibilities = Product::possibleVariants($variant_options);

//added
                    foreach ($possibilities as $key => $possibility) {
                        $VariantOption = new ProductVariantOption();
                        $VariantOption->name = $possibility;
                        $VariantOption->product_id = $product->id;
                        $VariantOption->price = $request->verians[$key]['price'];
                        $VariantOption->purchase_price = $request->verians[$key]['purchase_price'];

                        $VariantOption->quantity = !empty($request->verians[$key]['qty']) ? $request->verians[$key]['qty'] : 0;
                        $VariantOption->created_by = \Auth::user()->creatorId();
                        $VariantOption->save();
                    }
                }
                if (!empty($product)) {

                    //webhook
                    $module = 'New Product';
                    $webhook =  Utility::webhook($module, $store_id->id);
                    if ($webhook) {
                        $parameter = json_encode($product);
                        // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
                        $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
                        if ($status != true) {
                            $msgs  = 'Webhook call failed.';
                        }
                    }
                    $msg['flag'] = 'success';
                    $msg['msg']  = __('Product Successfully Created') . ((isset($msgs)) ? '<br> <span class="text-danger">' . $msgs . '</span>' : '') .  ((isset($result) && $result!=1) ? '<br> <span class="text-danger">' . $result . '</span>' : '');
                } else {
                    $msg['flag'] = 'error';
                    $msg['msg']  = __('Product Created Failed');
                }

                return $msg;
            } else {
                $msg['flag'] = 'error';
                $msg['msg'] = __('Your product limit is over Please upgrade plan');

                return $msg;
            }
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Product $product
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        if(\Auth::user()->can('Show Products')){
            $user = \Auth::user();
            $store = Store::where('id', $user->current_store)->first();
            
            if($user->current_store == $product->store_id){
                $product_image = Product_images::where('product_id', $product->id)->get();
        
                $product_tax = ProductTax::where('store_id', $store->id)->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                $product_ratings = Ratting::where('product_id', $product->id)->get();
        
                $ratting = Ratting::where('product_id', $product->id)->where('rating_view', 'on')->sum('ratting');
                $user_count = Ratting::where('product_id', $product->id)->where('rating_view', 'on')->count();
                if ($user_count > 0) {
                    $avg_rating = number_format($ratting / $user_count, 1);
                } else {
                    $avg_rating = number_format($ratting / 1, 1);
                }
        
                $variant_name = json_decode($product->variants_json);
                $product_variant_names = $variant_name;

                $expresscheckout = Expresscheckout::where('store_id',$store->id)->where('product_id',$product->id)->with('product')->get();
        
                return view('product.view', compact('product', 'product_image', 'product_tax', 'product_ratings', 'store', 'avg_rating', 'user_count', 'product_variant_names','expresscheckout'));
            }else{
                return redirect()->back()->with('error', 'Permission denied.');
            }
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Product $product
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        if(\Auth::user()->can('Edit Products')){
            $user = \Auth::user();
            $store_id = Store::where('id', $user->current_store)->first();

            if($user->current_store == $product->store_id){
                $product_categorie = ProductCategorie::where('store_id', $store_id->id)->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                $product_image = Product_images::where('product_id', $product->id)->get();
                $product_tax = ProductTax::where('store_id', $store_id->id)->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

                $productVariantArrays = [];
                $product_variant_names = [];
                $variant_options = [];
                // if ($product->enable_product_variant == 'on') {
                    $productVariants = ProductVariantOption::where('product_id', $product->id)->get();

                    if (!empty(json_decode($product->variants_json, true))) {

                        $variant_options = array_column(json_decode($product->variants_json), 'variant_name');
                        $product_variant_names = $variant_options;
                    }

                    foreach ($productVariants as $key => $productVariant) {
                        $productVariantArrays[$key]['product_variants'] = $productVariant->toArray();
                    }
                // }
                return view('product.edit', compact('product', 'product_categorie', 'product_image', 'product_tax', 'productVariantArrays', 'product_variant_names', 'variant_options'));
            }else{
                return redirect()->back()->with('error', 'Permission denied.');
            }
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Product $product
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $product)
    {
        //
    }

    public function productUpdate(Request $request, $product_id)
    {
        if(\Auth::user()->can('Edit Products')){
            $product = Product::find($product_id);

            $user = \Auth::user();
            $store_id = Store::where('id', $user->current_store)->first();

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                ]
            );
            if ($request->enable_product_variant == '') {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'price' => 'required',
                        'quantity' => 'required',
                        'is_cover_image' => 'mimes:jpeg,png,jpg,gif,svg,pdf,doc|max:20480',
                        'downloadable_prodcut' => 'mimes:jpeg,png,jpg,gif,svg,pdf,doc|max:20480',
                    ]
                );
            }
            if ($request->enable_product_variant == 'on') {
                if (!empty($request->verians || $request->variants)) {
                    if (!empty($request->verians)) {
                        foreach ($request->verians as $k => $items) {
                            foreach ($items as $item_k => $item) {
                                if (!isset($item)) {
                                    $msg['flag'] = 'error';
                                    $msg['msg'] = __('Please Fill The Form');

                                    return $msg;
                                }
                            }
                        }
                    } else {
                        foreach ($request->variants as $k => $items) {
                            foreach ($items as $item_k => $item) {
                                if (!isset($item)) {
                                    $msg['flag'] = 'error';
                                    $msg['msg'] = __('Please Fill The Form');

                                    return $msg;
                                }
                            }
                        }
                    }
                } else {
                    $msg['flag'] = 'error';
                    $msg['msg'] = __('Please Add Variants');
                    return $msg;
                }
            }
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                $msg['flag'] = 'error';
                $msg['msg'] = $messages->first();

                return $msg;
            }

            $file_name = [];

            if (!empty($request->multiple_files) && count($request->multiple_files) > 0) {

                foreach ($request->multiple_files as $key => $file) {

                    $image_size = $file->getSize();
                    $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);

                    if($result==1){
                        $filenameWithExt = $file->getClientOriginalName();
                        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();
                        $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                        // $file_name[] = $fileNameToStore;
                        $settings = Utility::getStorageSetting();

                        $dir = 'uploads/product_image/';
                        $path = Utility::keyWiseUpload_file($request, 'multiple_files', $fileNameToStore, $dir, $key, []);

                        if ($path['flag'] == 1) {
                            $url = $path['url'];
                            $file_name[] = $fileNameToStore;
                        }
                    }
                    // $dir             = storage_path('uploads/product_image/');
                    // if(!file_exists($dir))
                    // {
                    //     mkdir($dir, 0777, true);
                    // }
                    // $path = $file->storeAs('uploads/product_image/', $fileNameToStore);
                }
            }

            if (!empty($request->attachment)) {
                // if (asset(Storage::exists('uploads/is_cover_image/' . $product->attachment))) {
                //     asset(Storage::delete('uploads/is_cover_image/' . $product->attachment));
                // }
                $fileName = $product->attachment !== 'default.jpg' ? $product->attachment : '' ;
                $filePath ='uploads/is_cover_image/'. $fileName;

                $image_size = $request->file('attachment')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);

                if($result==1){
                    Utility::changeStorageLimit(\Auth::user()->creatorId(),$filePath);
                    $filenameWithExt = $request->file('attachment')->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $request->file('attachment')->getClientOriginalExtension();
                    $fileAttachment = $filename . '_' . time() . '.' . $extension;
                    $settings = Utility::getStorageSetting();

                    if ($settings['storage_setting'] == 'local') {
                        $dir = 'uploads/is_cover_image/';
                    } else {
                        $dir = 'uploads/is_cover_image/';
                    }
                    $path = Utility::upload_file($request, 'attachment', $fileAttachment, $dir, []);

                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }

                // $dir             = storage_path('uploads/is_cover_image/');
                // if(!file_exists($dir))
                // {
                //     mkdir($dir, 0777, true);
                // }
                // $path = $request->file('attachment')->storeAs('uploads/is_cover_image/', $fileAttachment);
            }

            if (!empty($request->downloadable_prodcut)) {

                // if (asset(Storage::exists('uploads/is_cover_image/' . $product->downloadable_prodcut))) {
                //     asset(Storage::delete('uploads/is_cover_image/' . $product->downloadable_prodcut));
                // }

                $fileName = $product->downloadable_prodcut !== 'default.jpg' ? $product->downloadable_prodcut : '' ;
                $filePath ='uploads/downloadable_prodcut/'. $fileName;

                $image_size = $request->file('downloadable_prodcut')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);

                if($result==1){
                    Utility::changeStorageLimit(\Auth::user()->creatorId(),$filePath);
                    $filenameWithExt = $request->file('downloadable_prodcut')->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $request->file('downloadable_prodcut')->getClientOriginalExtension();
                    $filedownloadable1 = $filename . '_' . time() . '.' . $extension;
                    $settings = Utility::getStorageSetting();

                    if ($settings['storage_setting'] == 'local') {
                        $dir = 'uploads/downloadable_prodcut/';
                    } else {
                        $dir = 'uploads/downloadable_prodcut/';
                    }

                    $filedownloadable = str_replace(' ', '_', $filedownloadable1);
                    $path = Utility::upload_file($request, 'downloadable_prodcut', $filedownloadable, $dir, []);

                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }
                // $dir               = storage_path('uploads/downloadable_prodcut/');
                // if(!file_exists($dir))
                // {
                //     mkdir($dir, 0777, true);
                // }
                // $filedownloadable = str_replace(' ', '_', $filedownloadable1);

                // $path = $request->file('downloadable_prodcut')->storeAs('uploads/downloadable_prodcut/', $filedownloadable);
            }

            if (!empty($request->is_cover_image)) {
                // if (asset(Storage::exists('uploads/is_cover_image/' . $product->is_cover))) {
                //     asset(Storage::delete('uploads/is_cover_image/' . $product->is_cover));
                // }
                $fileName = $product->is_cover !== 'default.jpg' ? $product->is_cover : '' ;
                $filePath ='uploads/is_cover_image/'. $fileName;

                $image_size = $request->file('is_cover_image')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);

                if($result==1){
                    Utility::changeStorageLimit(\Auth::user()->creatorId(),$filePath);
                    $filenameWithExt = $request->file('is_cover_image')->getClientOriginalName();
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension = $request->file('is_cover_image')->getClientOriginalExtension();
                    $fileNameToStores = $filename . '_' . time() . '.' . $extension;
                    $settings = Utility::getStorageSetting();

                    if ($settings['storage_setting'] == 'local') {
                        $dir = 'uploads/is_cover_image/';
                    } else {
                        $dir = 'uploads/is_cover_image/';
                    }
                    $path = Utility::upload_file($request, 'is_cover_image', $fileNameToStores, $dir, []);

                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }

                // $dir              = storage_path('uploads/is_cover_image/');
                // if(!file_exists($dir))
                // {
                //     mkdir($dir, 0777, true);
                // }
                // $path = $request->file('is_cover_image')->storeAs('uploads/is_cover_image/', $fileNameToStores);
            }

            if (!empty($request->product_tax)) {
                if (count($request->product_tax) > 1 && in_array(0, $request->product_tax)) {
                    return redirect()->back()->with('error', __('Please select valid tax'));
                }
            }

            if (!empty($request->product_categorie)) {
                if (count($request->product_categorie) > 1 && in_array(0, $request->product_categorie)) {
                    return redirect()->back()->with('error', __('Please select valid Categorie'));
                }
            }

            $product['store_id'] = $store_id->id;
            $product['name'] = $request->name;
            if (!empty($request->product_categorie)) {
                $product['product_categorie'] = implode(',', $request->product_categorie);
            } else {
                $product['product_categorie'] = $request->product_categorie;
            }
            if (!empty($request->price)) {
                $product['price'] = !empty($request->price) ? $request->price : '0';
                $product['last_price'] = !empty($request->last_price) ? $request->last_price : '0';
                $product['purchase_price'] = !empty($request->purchase_price) ? $request->purchase_price : '0';
                
            }
            if (!empty($request->quantity)) {
                $product['quantity'] = !empty($request->quantity) ? $request->quantity : '0';
            }
            $product['SKU'] = $request->SKU;
            if (!empty($request->product_tax)) {
                $product['product_tax'] = implode(',', $request->product_tax);
            } else {
                $product['product_tax'] = $request->product_tax;
            }

            $product['custom_field_1'] = $request->custom_field_1;
            $product['custom_value_1'] = $request->custom_value_1;
            $product['custom_field_2'] = $request->custom_field_2;
            $product['custom_value_2'] = $request->custom_value_2;
            $product['custom_field_3'] = $request->custom_field_3;
            $product['custom_value_3'] = $request->custom_value_3;
            $product['custom_field_4'] = $request->custom_field_4;
            $product['custom_value_4'] = $request->custom_value_4;
            if(!empty($fileAttachment) ){

                $product['attachment'] =  $fileAttachment;
            }
            if(!empty($filedownloadable)){
                
                $product['downloadable_prodcut'] =  $filedownloadable;
            }
            $product['product_display'] = isset($request->product_display) ? 'on' : 'off';

            $product['variants_json'] = !empty($request->hiddenVariantOptions) ? $request->hiddenVariantOptions : $product->variants_json;

            if ($request->enable_product_variant == 'on') {
                $product['enable_product_variant'] = 'on';
            } else {
                $product['enable_product_variant'] = 'off';
            }

            if ($request->enable_product_variant == 'on' && $request->hiddenhidden == 'now_in_var') {
                $hiddnopt = !empty($request->hiddenVariantOptions) ? $request->hiddenVariantOptions : $product->variants_json;
                $variant_options = array_column(json_decode($hiddnopt, true), 'variant_options');

                $possibilities_possibilities = Product::possibleVariants($variant_options);

                foreach ($possibilities_possibilities as $key => $possibility) {
                    $VariantOption = new ProductVariantOption();
                    $VariantOption->name = $possibility;
                    $VariantOption->product_id = $product->id;
                    $VariantOption->price = $request->verians[$key]['price'];
                    $VariantOption->purchase_price = $request->verians[$key]['purchase_price'];

                    $VariantOption->quantity = !empty($request->verians[$key]['qty']) ? $request->verians[$key]['qty'] : 0;
                    $VariantOption->created_by = Auth::user()->creatorId();
                    $VariantOption->save();
                }
            } else {
                if ($request->enable_product_variant == 'on') {

                    // $product['enable_product_variant'] = 'on';
                    // $product['variants_json'] = !empty($request->hiddenVariantOptions) ? $request->hiddenVariantOptions : $product->variants_json;

                    if (!empty($request->verians) && count($request->verians) > 0) {
                        foreach ($request->verians as $key => $possibility) {

                            $possibilities = ProductVariantOption::find($key);

                            if (!empty($possibilities) && isset($possibility['variants'])) {
                                $possibilities->price = $possibility['price'];
                                //added
                                $possibilities->purchase_price = $possibility['purchase_price'];

                                $possibilities->quantity = $possibility['quantity'] ?? $possibility['qty'];

                                $possibilities->save();
                            } else {

                                $VariantOptionNew = new ProductVariantOption();
                                $VariantOptionNew->name = $possibility['name'];
                                $VariantOptionNew->product_id = $product->id;
                                $VariantOptionNew->price = $possibility['price'];
                                $VariantOptionNew->purchase_price = $possibility['purchase_price'];

                                $VariantOptionNew->quantity = $possibility['quantity'] ?? $possibility['qty'];
                                $VariantOptionNew->created_by = Auth::user()->creatorId();
                                $VariantOptionNew->save();
                            }
                        }
                    } else if (!empty($request->variants) && count($request->variants) > 0) {


                        foreach ($request->variants as $key => $possibility) {

                            $possibilities = Product::possibleVariants($possibility['variants']);
                            $possibilities = ProductVariantOption::find($key);
                            $possibilities->price = $possibility['price'];
                            $possibilities->purchase_price = $possibility['purchase_price'];

                            $possibilities->quantity = $possibility['quantity'] ?? $possibility['qty'];

                            $possibilities->save();
                        }
                    }
                } else {
                    $product['enable_product_variant'] = 'off';
                }
            }

            if (!empty($request->is_cover_image)) {
                $product['is_cover'] = $fileNameToStores;
            }

            $product['description'] = $request->description;
            $product['specification'] = $request->specification;
            $product['detail'] = $request->detail;
            $product['created_by'] = \Auth::user()->creatorId();
            foreach ($file_name as $file) {
                $objStore = Product_images::create(
                    [
                        'product_id' => $product->id,
                        'product_images' => $file,
                    ]
                );
            }

            $product->save();

            if ($product->enable_product_variant == 'on') {

                if (!empty($request->variants)) {
                    foreach ($request->variants as $key => $variant) {
                        $newVal = '';

                        foreach (array_values($variant['variants']) as $k => $v) {
                            if (!empty($newVal)) {
                                $newVal .= ' : ' . $v[0];
                            } else {
                                $newVal .= $v[0];
                            }
                        }
                        $VariantOption = ProductVariantOption::find($key);
                        $VariantOption->name = $newVal;
                        $VariantOption->price = $variant['price'];
                        $VariantOption->purchase_price = $variant['purchase_price'];

                        $VariantOption->quantity = $variant['quantity'] ?? $variant['qty'];
                        $VariantOption->save();
                    }
                }
            }

            if (!empty($product)) {
                $msg['flag'] = 'success';
                $msg['msg'] = __('Product Successfully Updated') . ((isset($result) && $result!=1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''); 
            } else {
                $msg['flag'] = 'error';
                $msg['msg'] = __('Product Created Failed');
            }

            return $msg;
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Product $product
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if(\Auth::user()->can('Delete Products')){
            Ratting::where('product_id', $product->id)->delete();
            $Product_images = Product_images::where('product_id', $product->id)->get();
            $pro_img = new ProductController();
           
            // $dir = storage_path('uploads/is_cover_image/');
            // if (!empty($product->is_cover)) {
            //     unlink($dir . $product->is_cover);
            // }
            if(isset($product->is_cover)){
                $fileName = $product->is_cover !== 'default.jpg' ? $product->is_cover : '' ;
                $filePath ='uploads/is_cover_image/'. $fileName;
                
                Utility::changeStorageLimit(\Auth::user()->creatorId(),$filePath);
            }
            if(isset($product->attachment)){
                $fileName = $product->attachment !== 'default.jpg' ? $product->attachment : '' ;
                $filePath ='uploads/is_cover_image/'. $fileName;
                
                Utility::changeStorageLimit(\Auth::user()->creatorId(),$filePath);
            }
            if(isset($product->downloadable_prodcut)){
                $fileName = $product->downloadable_prodcut !== 'default.jpg' ? $product->downloadable_prodcut : '' ;
                $filePath ='uploads/downloadable_prodcut/'. $fileName;   
                Utility::changeStorageLimit(\Auth::user()->creatorId(),$filePath);
            }
            foreach ($Product_images as $pro) {
                if(isset($pro->product_images)){
                    $fileName = $pro->product_images !== 'default.jpg' ? $pro->product_images : '' ;
                    $filePath ='uploads/product_image/'. $fileName;
                    
                    Utility::changeStorageLimit(\Auth::user()->creatorId(),$filePath);
                }
            }

            foreach ($Product_images as $pro) {
                $pro_img->fileDelete($pro->id);
            }
            ProductVariantOption::where('product_id', $product->id)->forceDelete();
            $product->delete();
    
            return redirect()->back()->with('success', __('Product successfully deleted.'));
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function grid()
    {
        $user = \Auth::user();
        $store_id = Store::where('id', $user->current_store)->first();
        $products = Product::where('store_id', $store_id->id)->orderBy('id', 'DESC')->get();

        return view('product.grid', compact('products'));
    }

    public function fileDelete($id)
    {
        $product_img_id = Product_images::find($id);

        $dir = storage_path('uploads/product_image/');
        if (!empty($product_img_id->product_images)) {
            if (!file_exists($dir . $product_img_id->product_images)) {
                Product_images::where('id', $id)->delete();

                return response()->json(
                    [
                        'error' => __('File not exists in folder!'),
                        'id' => $id,
                    ]
                );
            } else {
                $fileName = $product_img_id->product_images !== 'default.jpg' ? $product_img_id->product_images : '' ;
                $filePath ='uploads/product_image/'. $fileName;
                
                Utility::changeStorageLimit(\Auth::user()->creatorId(),$filePath);
                Product_images::where('id', '=', $id)->delete();
                // unlink($dir . $product_img_id->product_images);

                return response()->json(
                    [
                        'success' => __('Record deleted successfully!'),
                        'id' => $id,
                    ]
                );
            }
        }

        return response()->json(
            [
                'success' => __('Record deleted successfully!'),
                'id' => $id,
            ]
        );
    }

    public function productVariantsCreate(Request $request)
    {
        if(\Auth::user()->can('Create Variants')){
            return view('product.variants.create')->render();
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function productVariantsEdit(Request $request, $product_id)
    {
        if(\Auth::user()->can('Edit Variants')){
            $product = Product::getProductById($product_id);
            $productVariantOption = json_decode($product->variants_json, true);
            if (empty($productVariantOption)) {
                return view('product.variants.create')->render();
            } else {
                return view('product.variants.edit', compact('product', 'productVariantOption', 'product_id'))->render();
            }
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function getProductVariantsPossibilities(Request $request, $product_id = 0)
    {
        $variant_edit = $request->variant_edit;
        if (!empty($variant_edit) && $variant_edit == 'edit') {
            $variant_option123 = json_decode($request->hiddenVariantOptions, true);
            foreach ($variant_option123 as $key => $value) {
                $new_key = array_search($value['variant_name'], array_column($request->variant_edt, 'variant_name'));
                if (!empty($request->variant_edt[$new_key]['variant_options'])) {
                    $new_val = explode('|', $request->variant_edt[$new_key]['variant_options']);
                    $variant_option123[$key]['variant_options'] = array_merge($variant_option123[$key]['variant_options'], $new_val);
                }
            }
            $request['hiddenVariantOptions'] = json_encode($variant_option123);
        }
        $variant_name = $request->variant_name;
        $variant_options = $request->variant_options;
        $hiddenVariantOptions = $request->hiddenVariantOptions;
        $hiddenVariantOptions = json_decode($hiddenVariantOptions, true);
        $variants = [
            [
                'variant_name' => $variant_name,
                'variant_options' => explode('|', $variant_options),
            ],
        ];
        if (empty($variant_edit) && $variant_edit != 'edit') {
            $hiddenVariantOptions = array_merge($hiddenVariantOptions, $variants);
        }
        $hiddenVariantOptions = array_map("unserialize", array_unique(array_map("serialize", $hiddenVariantOptions)));
        $optionArray = $variantArray = [];

        foreach ($hiddenVariantOptions as $variant) {
            $variantArray[] = $variant['variant_name'];
            $optionArray[] = $variant['variant_options'];
        }
        $deleted_variants = ProductVariantOption::onlyTrashed()->where('product_id', $product_id)->get();
        $possibilities = Product::possibleVariants($optionArray);
        foreach ($deleted_variants as $key => $dv) {
            $deleted_variant = $dv->name;
            if (in_array($deleted_variant, $possibilities)) {
                $indexKay = array_search($deleted_variant, $possibilities, true);
                unset($possibilities[$indexKay]);
            }
        }

        $variantArray = array_unique($variantArray);
        if (!empty($variant_edit) && $variant_edit == 'edit') {
            $varitantHTML = view('product.variants.edit_list', compact('possibilities', 'variantArray', 'product_id'))->render();
        } else {
            $varitantHTML = view('product.variants.list', compact('possibilities', 'variantArray'))->render();
        }

        $result = [
            'status' => false,
            'hiddenVariantOptions' => json_encode($hiddenVariantOptions),
            'varitantHTML' => $varitantHTML,
        ];

        return response()->json($result);
    }

    public function getProductsVariantQuantity(Request $request)
    {
        
        $status = false;
        $quantity = $variant_id = 0;
        $quantityHTML = '<strong>' . __('Please select variants to get available quantity.') . '</strong>';
        $priceHTML = '';
        $product = Product::find($request->product_id);
        $price = \App\Models\Utility::priceFormat(0);
        //dd($request->variants);
        $status = false;

        if ($product && $request->variants != '') {
            $variant = ProductVariantOption::where('product_id', $product['id'])->where('name', $request->variants)->first();

            if ($variant) {
                $status = true;
                $quantity = $variant->quantity - (isset($cart[$variant->id]['quantity']) ? $cart[$variant->id]['quantity'] : 0);
                $price = \App\Models\Utility::priceFormat($variant->price);
                $variant_id = $variant->id;
            }
        }

        return response()->json(
            [
                'status' => $status,
                'price' => $price,
                'quantity' => $quantity,
                'variant_id' => $variant_id
            ]
        );
       
    }

    public function VariantDelete(Request $request, $id, $product_id)
    {
        if(\Auth::user()->can('Delete Variants')){
            $product = Product::find($product_id);
            if (!empty($product->variants_json) && ProductVariantOption::find($id)->exists()) {
                $var_json = json_decode($product->variants_json, true);
            
                $i = 0;
                foreach ($var_json[0] as $key => $value) {
                    $var_ops = explode(' : ', ProductVariantOption::find($id)->name);
                    $count = ProductVariantOption::where('product_id', $product->id)->where('name', 'LIKE', '%' . $var_ops[0] . '%')->count();
                    if ($count == 1 && $i == 0) {
                        $unsetIndex = array_search($var_ops[0], $var_json[0]['variant_options'], true);
                        unset($var_json[0]['variant_options'][$unsetIndex]);
                    }
                    $i++;
                }
                $variants = ProductVariantOption::where('product_id',$product->id)->count();
                if($variants == 1){
                    $product->variants_json = '{}';
                    $product->update();
                }else{
                    $product->variants_json = json_encode($var_json);
                    $product->update();
                }
                
            }
            ProductVariantOption::find($id)->delete();
            return redirect()->back()->with('success', __('Variant successfully deleted.'));
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function fileExport()
    {
        $name = 'product_' . date('Y-m-d i:h:s');
        $data = Excel::download(new ProductExport(), $name . '.xlsx');

        return $data;
    }

    public function fileImportExport()
    {
        if(\Auth::user()->can('Create Products')){
            return view('product.import');
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }

    }

    public function fileImport(Request $request)
    {
        if(\Auth::user()->can('Create Products')){
            $rules = [
                'file' => 'required|mimes:csv,txt,xlsx',
            ];
            $user = \Auth::user();
            $store_id = Store::where('id', $user->current_store)->first();

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $products = (new ProductImport())->toArray(request()->file('file'))[0];

            $totalproduct = count($products) - 1;

            $errorArray = [];
            for ($i = 1; $i <= count($products) - 1; $i++) {
                $product = $products[$i];
                $productBySku = Product::where('SKU', $product[2])->first();

                if (!empty($productByname)) {
                    $productData = $productBySku;
                } else {
                    $productData = new Product();
                }

                $productData->name = $product[0];
                $productData->description = $product[1];
                $productData->product_categorie = $product[2];
                $productData->product_tax = $product[3];

                $productData->price = $product[4];
                $productData->last_price = $product[5];
                $productData->purchase_price = $product[6];
                //$productData->last_price = $product[5];


                $productData->quantity = $product[7];
                $productData->SKU = $product[8];
                $productData->product_display = $product[9];


                $productData->store_id = $store_id->id;
                $productData->created_by = \Auth::user()->creatorId();

                if (empty($productData)) {
                    $errorArray[] = $productData;
                } else {
                    $productData->save();
                }
            }

            $errorRecord = [];
            if (empty($errorArray)) {
                $data['status'] = 'success';
                $data['msg'] = __('Record successfully imported');
            } else {
                $data['status'] = 'error';
                $data['msg'] = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalproduct . ' ' . 'record');

                foreach ($errorArray as $errorData) {

                    $errorRecord[] = implode(',', $errorData);
                }

                \Session::put('errorArray', $errorRecord);
            }
            return redirect()->back()->with($data['status'], $data['msg']); 
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
    public function searchProducts(Request $request)
    {
        $lastsegment = $request->session_key;
        if ($request->ajax() && isset($lastsegment) && !empty($lastsegment)) {
            $output = "";
            if ($request->cat_id !== '' && $request->search == '') {
                if($request->cat_id == '0'){//->where('enable_product_variant','off')
                    $products = Product::where('store_id',$request->store_id)->get();
                   
                }else{
                    
                    $products = Product::where('product_categorie', $request->cat_id)->where('store_id',$request->store_id)->get();
                }

            } else {
                if($request->cat_id == '0'){
                    $products = Product::where('name', 'LIKE', "%{$request->search}%")->orWhere('sku', '=', $request->search)->where('store_id',$request->store_id)->get();
                   
                }else{
                    $products = Product::where('name', 'LIKE', "%{$request->search}%")->orWhere('sku', '=', $request->search)->where('store_id',$request->store_id)->Where('product_categorie', $request->cat_id)->get();
                   
                }
            }
            if (count($products)>0)
            {
                foreach ($products as $key => $product)
                {
                    if($product->enable_product_variant == 'off'){
                        $quantity = $product->quantity;
                        if(!empty($product->is_cover)){
                            // $image_url =('uploads/is_cover_image').'/'.$product->is_cover;
                            $image_url =\App\Models\Utility::get_file('uploads/is_cover_image').'/'.$product->is_cover;
                        }else{
                            // $image_url =('uploads/is_cover_image').'/default.jpg';
                            $image_url =\App\Models\Utility::get_file('uploads/is_cover_image').'/default.jpg';
                        }
                        if ($request->session_key == 'purchases')
                        {
                            $productprice = $product->price != 0 ? $product->price : 0;
                        }
                        else if ($request->session_key == 'pos')
                        {
                            $productprice = $product->price != 0 ? $product->price : 0;
                        }
                        else
                        {
                            $productprice = $product->price != 0 ? $product->price : $product->price;
                        }


                        $output .= '

                                <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 col-sm-6 col-xs-6 col-12">
                                    <div class="tab-pane fade show active toacart w-100" data-url="' . url('addToCart/' . $product->id . '/' . $lastsegment) .'">
                                        <div class="position-relative card">
                                            <img alt="Image placeholder" src="' . $image_url/*asset(Storage::url($image_url))*/ . '" class="card-image avatar hover-shadow-lg" style=" height: 6rem; width: 100%;">
                                            <div class="p-0 custom-card-body card-body d-flex ">
                                                <div class="card-body my-2 p-2 text-left card-bottom-content">
                                                    <h6 class="mb-2 text-dark product-title-name">' . $product->name . '</h6>
                                                    <small class="badge badge-primary mb-0">' . Utility::priceFormat($productprice) . '</small>

                                                    <small class="top-badge badge badge-danger mb-0">'. $quantity. ' QTY'.'</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        ';
                    }else{
                        if(!empty($product->is_cover)){
                            $image_url =\App\Models\Utility::get_file('uploads/is_cover_image').'/'.$product->is_cover;
                        }else{
                            $image_url =\App\Models\Utility::get_file('uploads/is_cover_image').'/default.jpg';
                        }

                        $output .= '

                                <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 col-sm-6 col-xs-6 col-12">
                                    <div class="tab-pane fade show active w-100" data-url="' . url('pos-productVariant/' . $product->id . '/' . $lastsegment) .'" data-ajax-popup="true" data-size="lg" data-align="centered" data-title="Product Variant" >
                                        <div class="position-relative card">
                                            <img alt="Image placeholder" src="' . $image_url/*asset(Storage::url($image_url))*/ . '" class="card-image avatar hover-shadow-lg" style=" height: 6rem; width: 100%;">
                                            <div class="p-0 custom-card-body card-body d-flex ">
                                                <div class="card-body my-2 p-2 text-left card-bottom-content">
                                                    <h6 class="mb-2 text-dark product-title-name">' . $product->name . '</h6>
                                                    <small class="badge badge-primary mb-0">In Variant</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        ';
                    }

                }

                return Response($output);
            } else {
                $output='<div class="card card-body col-12 text-center">
                    <h5>'.__("No Product Available").'</h5>
                    </div>';
                return Response($output);
            }
        }
    }

    public function productVariant(Request $request, $id, $session_key)
    {
        $products = Product::where('id', $id)->first();
        $variant_name = json_decode($products->variants_json);
        $product_variant_names = $variant_name;

        return view('pos.product_variant', compact('products', 'product_variant_names', 'session_key'));
    }
    public function addToCartVariant(Request $request, $id,$session_key,$variant_id = 0)
    {
// dd($request->all(), $id,$session_key,$variant_id);
        $variant = ProductVariantOption::find($variant_id);
        $product = Product::find($id);
        if ($product) {
            $productquantity = $product->quantity;
        }
        if ($variant_id > 0) {
            $quantity = $variant->quantity;
        }else{
            $quantity = 0;
        }
        if ($session_key == 'pos' && $quantity <= 0) {
            return response()->json(
                [
                    'code' => 404,
                    'status' => 'Error',
                    'error' => __('This product is out of stock!'),
                ],
                404
            );
        }

        $productname = $product->name;

        if  ($session_key == 'pos') {

            $productprice = $product->price != 0 ? $product->price : 0;
        }

        $originalquantity = (int)$productquantity;
        
        $taxes=Utility::tax($product->product_tax);
        $totalTaxRate=Utility::totalTaxRate($product->product_tax);
        $product_tax='';
        $producttax = [];
        $itemTaxes = [];
        $totalTax = 0;
        $product_tax_id=[];
        if (!empty($taxes)) {
            foreach ($taxes as $tax) {
               
                if (!empty($tax)) {
                    $producttax = Utility::taxRate($tax->rate, $product->price, 1);
                    $product_tax.= !empty($tax)?"<span class='badge bg-primary'>". $tax->name.' ('.$tax->rate.'%)'."</span><br>":'';
                    $totalTax += $producttax;
                    $itemTax['tax_name'] = $tax->name;
                    $itemTax['tax'] = $tax->rate;
                    $itemTaxes[] = $itemTax;
                    $subtotal = $productprice + $totalTax;
                }
                else{
                    $subtotal = $productprice;
                    $product_tax = '-';
                }
                
            }           
        }
      
        $cart            = session()->get($session_key);
        if(!empty($product->is_cover)){
            // $image_url =('uploads/is_cover_image').'/'.$product->is_cover;
            $image_url =\App\Models\Utility::get_file('uploads/is_cover_image').'/'.$product->is_cover;
        }else{
            // $image_url =('uploads/is_cover_image').'/default.jpg';
            $image_url =\App\Models\Utility::get_file('uploads/is_cover_image').'/default.jpg';
        }

        if ($variant_id > 0) {
            $variant_itemTaxes = [];
            $variant_name = $variant->name;
            $variant_price = $variant->price;
            $originalvariantquantity = (int) $variant->quantity;
            //variant count tax
            $variant_taxes = Utility::tax($product->product_tax);
            $variant_producttax = 0;

            if (!empty($variant_taxes)) {
                foreach ($variant_taxes as $variant_tax) {
                    if (!empty($variant_tax)) {
                        $variant_producttax = Utility::taxRate($variant_tax->rate, $variant_price, 1);
                        $itemTax['tax_name'] = $variant_tax->name;
                        $itemTax['tax'] = $variant_tax->rate;
                        $variant_itemTaxes[] = $itemTax;
                    }
                }
            }
            // $variant_subtotal = Utility::priceFormat($variant_price * $variant->quantity);
            $variant_subtotal = $variant_price + $variant_producttax;
        }

        $time = time();

        $model_delete_id = 'delete-form-' . $time;

        $carthtml = '';

        $carthtml .= '<tr data-product-id="' . $time . '" id="product-variant-id-' . $variant_id . '">
                        <td class="cart-images">
                            <img alt="Image placeholder" src="' . $image_url/*asset(Storage::url($image_url))*/ . '" class="card-image avatar shadow hover-shadow-lg">
                        </td>

                        <td class="name">' . $productname . '-' . $variant_name . '</td>

                        <td class="">
                                <span class="quantity buttons_added">
                                        <input type="button" value="-" class="minus">
                                        <input type="number" step="1" min="1" max="" name="quantity" title="' . __('Quantity') . '" class="input-number" size="4" data-url="' . url('update-cart/') . '" data-id="' . $time . '">
                                        <input type="button" value="+" class="plus">
                                </span>
                        </td>


                        <td class="tax">' . $product_tax . '</td>

                        <td class="price">' .  Utility::priceFormat($variant_price) . '</td>

                        <td class="subtotal">' . Utility::priceFormat($variant_subtotal) . '</td>

                        <td class="">
                                <a href="#" class="bs-pass-para btn btn-sm btn-icon bg-light-secondary" data-confirm="' . __("Are You Sure?") . '" data-text="' . __("This action can not be undone. Do you want to continue?") . '" data-confirm-yes=' . $model_delete_id . ' title="' . __('Delete') . '}" data-id="' . $time . '" title="' . __('Delete') . '"   >
                                <span class=""><i class="ti ti-trash"></i></span>
                                </a>
                                <form method="post" action="' . url('remove-from-cart') . '"  accept-charset="UTF-8" id="' . $model_delete_id . '">
                                    <input name="_method" type="hidden" value="DELETE">
                                    <input name="_token" type="hidden" value="' . csrf_token() . '">
                                    <input type="hidden" name="session_key" value="' . $session_key . '">
                                    <input type="hidden" name="id" value="' . $time . '">
                                </form>

                        </td>
                    </td>';

        // if cart is empty then this the first product
        if (!$cart) {
            $cart = [
                $time => [
                    "product_id" => $product->id,
                    "product_name" => $productname,
                    "image" => $product->is_cover,
                    "quantity" => 1,
                    "price" => $productprice,
                    "id" => $id,
                    "downloadable_prodcut" => $product->downloadable_prodcut,
                    "tax" => $variant_itemTaxes,
                    "subtotal" => $subtotal,
                    "originalquantity" => $originalquantity,
                    "variant_name" => $variant_name,
                    "variant_price" => $variant_price,
                    "variant_qty" => $variant->quantity,
                    "variant_subtotal" => $variant_subtotal,
                    "originalvariantquantity" => $originalvariantquantity,
                    'variant_id' => $variant_id,
                ],
            ];

            if ($originalvariantquantity < $cart[$time]['quantity'] && $session_key == 'pos') {
                return response()->json(
                    [
                        'code' => 404,
                        'status' => 'Error',
                        'error' => __('This product is out of stock!'),
                    ],
                    404
                );
            }

            session()->put($session_key, $cart);
          
            return response()->json(
                [
                    'code' => 200,
                    'status' => 'Success',
                    'success' => $productname . __(' added to cart successfully!'),
                    'product' => $cart[$time],
                    'carthtml' => $carthtml,
                ]
            );
        }
        
        // if cart not empty then check if this product exist then increment quantity
        if ($variant_id > 0) {
        $key = false;
        foreach ($cart as $k => $value) {
            if ($variant_id == $value['variant_id']) {
                $key = $k;
            }
        }

        if ($key !== false && isset($cart[$key]['variant_id']) && $cart[$key]['variant_id'] != 0) {
            if (isset($cart[$key])) {
                $cart[$key]['quantity'] = $cart[$key]['quantity'] + 1;
                $cart[$key]['variant_subtotal'] = $cart[$key]['variant_price'] * $cart[$key]['quantity'];

                $variant_producttax1 = 0;

                if (!empty($variant_taxes)) {
                    foreach ($variant_taxes as $variant_tax1) {
                        if (!empty($variant_tax1)) {
                            $variant_producttax1 = Utility::taxRate($variant_tax1->rate, $variant_price, $cart[$key]['quantity']);
                        }
                    }
                }
                $cart[$key]['variant_subtotal'] = $cart[$key]['variant_subtotal'] + $variant_producttax1;

                if ($originalvariantquantity < $cart[$key]['quantity']) {
                    return response()->json(
                        [
                            'code' => 404,
                            'status' => 'Error',
                            'error' => __('This product is out of stock!'),
                        ]
                    );
                }

                session()->put($session_key, $cart);

                return response()->json(
                    [
                        'code' => 200,
                        'status' => 'Success',
                        'success' => $productname . __('   added to cart successfully!'),
                        'product' => $cart[$key],
                        'carttotal' => $cart,
                    ]
                );
            }
        }
    }

        // if item not exist in cart then add to cart with quantity = 1
        $cart[$time] = [
            "product_id" => $product->id,
            "product_name" => $productname,
            "image" => $product->is_cover,
            "quantity" => 1,
            "price" => $productprice,
            "id" => $id,
            "downloadable_prodcut" => $product->downloadable_prodcut,
            "tax" => $variant_itemTaxes,
            "subtotal" => $subtotal,
            "originalquantity" => $originalquantity,
            "variant_name" => $variant->name,
            "variant_price" => $variant->price,
            "variant_qty" => $variant->quantity,
            "variant_subtotal" => $variant_subtotal,
            "originalvariantquantity" => $originalvariantquantity,
            'variant_id' => $variant_id,
        ];

        if ($originalvariantquantity < $cart[$time]['quantity'] && $session_key == 'pos') {
            return response()->json(
                [
                    'code' => 404,
                    'status' => 'Error',
                    'error' => __('This product is out of stock!'),
                ],
                404
            );
        }

        session()->put($session_key, $cart);
        return response()->json(
            [
                'code' => 200,
                'status' => 'Success',
                'success' => $productname . __(' added to cart successfully!'),
                'product' => $cart[$time],
                'carthtml' => $carthtml,
                'carttotal' => $cart,
            ]
        );
    }

    public function addToCart(Request $request, $id,$session_key)
    {

    
        $product = Product::find($id);
        if ($product) {
            $productquantity = $product->quantity;
        }
        if (!$product || ($session_key == 'pos' && $productquantity == 0)) {
            return response()->json(
                [
                    'code' => 404,
                    'status' => 'Error',
                    'error' => __('This product is out of stock!'),
                ],
                404
            );
        }

        $productname = $product->name;

        if  ($session_key == 'pos') {

            $productprice = $product->price != 0 ? $product->price : 0;
        }

        $originalquantity = (int)$productquantity;
        
        $taxes=Utility::tax($product->product_tax);
        $totalTaxRate=Utility::totalTaxRate($product->product_tax);
        $product_tax='';
        $producttax = [];
        $itemTaxes = [];
        $totalTax = 0;
        $product_tax_id=[];
        if (!empty($taxes)) {
            foreach ($taxes as $tax) {
               
                if (!empty($tax)) {
                    $producttax = Utility::taxRate($tax->rate, $product->price, 1);
                    $product_tax.= !empty($tax)?"<span class='badge bg-primary'>". $tax->name.' ('.$tax->rate.'%)'."</span><br>":'';
                    $totalTax += $producttax;
                    $itemTax['tax_name'] = $tax->name;
                    $itemTax['tax'] = $tax->rate;
                    $itemTaxes[] = $itemTax;
                    $subtotal = $productprice + $totalTax;
                }
                else{
                    $subtotal = $productprice;
                    $product_tax = '-';
                }
                
            }           
        }
      
        $cart            = session()->get($session_key);
        if(!empty($product->is_cover)){
            // $image_url =('uploads/is_cover_image').'/'.$product->is_cover;
            $image_url =\App\Models\Utility::get_file('uploads/is_cover_image').'/'.$product->is_cover;
        }else{
            // $image_url =('uploads/is_cover_image').'/default.jpg';
            $image_url =\App\Models\Utility::get_file('uploads/is_cover_image').'/default.jpg';
        }

        $time = time();

        $model_delete_id = 'delete-form-' . $time;

        $carthtml = '';

        $carthtml .= '<tr data-product-id="' . $time . '" id="product-id-' . $id . '">
                        <td class="cart-images">
                            <img alt="Image placeholder" src="' . $image_url/*asset(Storage::url($image_url))*/ . '" class="card-image avatar shadow hover-shadow-lg">
                        </td>

                        <td class="name">' . $productname . '</td>

                        <td class="">
                                <span class="quantity buttons_added">
                                        <input type="button" value="-" class="minus">
                                        <input type="number" step="1" min="1" max="" name="quantity" title="' . __('Quantity') . '" class="input-number" size="4" data-url="' . url('update-cart/') . '" data-id="' . $time . '">
                                        <input type="button" value="+" class="plus">
                                </span>
                        </td>


                        <td class="tax">' . $product_tax . '</td>

                        <td class="price">' .  Utility::priceFormat($productprice) . '</td>

                        <td class="subtotal">' . Utility::priceFormat($subtotal) . '</td>

                        <td class="">
                                <a href="#" class="bs-pass-para btn btn-sm btn-icon bg-light-secondary" data-confirm="' . __("Are You Sure?") . '" data-text="' . __("This action can not be undone. Do you want to continue?") . '" data-confirm-yes=' . $model_delete_id . ' title="' . __('Delete') . '}" data-id="' . $time . '" title="' . __('Delete') . '"   >
                                <span class=""><i class="ti ti-trash"></i></span>
                                </a>
                                <form method="post" action="' . url('remove-from-cart') . '"  accept-charset="UTF-8" id="' . $model_delete_id . '">
                                    <input name="_method" type="hidden" value="DELETE">
                                    <input name="_token" type="hidden" value="' . csrf_token() . '">
                                    <input type="hidden" name="session_key" value="' . $session_key . '">
                                    <input type="hidden" name="id" value="' . $time . '">
                                </form>

                        </td>
                    </td>';
        
        // if cart is empty then this the first product
        if (!$cart) {
            $cart = [
                $time => [
                    "product_id" => $product->id,
                    "product_name" => $productname,
                    "image" => $product->is_cover,
                    "quantity" => 1,
                    "price" => $productprice,
                    "id" => $id,
                    "downloadable_prodcut" => $product->downloadable_prodcut,
                    "tax" => $itemTaxes,
                    "subtotal" => $subtotal,
                    "originalquantity" => $originalquantity,
                    'variant_id' => 0,
                ],
            ];


            if ($originalquantity < $cart[$time]['quantity'] && $session_key == 'pos') {
                return response()->json(
                    [
                        'code' => 404,
                        'status' => 'Error',
                        'error' => __('This product is out of stock!'),
                    ],
                    404
                );
            }

            session()->put($session_key, $cart);
          
            return response()->json(
                [
                    'code' => 200,
                    'status' => 'Success',
                    'success' => $productname . __(' added to cart successfully!'),
                    'product' => $cart[$time],
                    'carthtml' => $carthtml,
                ]
            );
        }

        // if cart not empty then check if this product exist then increment quantity
        $key = false;
        foreach ($cart as $k => $value) {
            if ($product->id == $value['product_id']) {
                $key = $k;
            }
        }

        if ($key !== false) {
            if (isset($cart[$key])) {
                $cart[$key]['quantity'] = $cart[$key]['quantity'] + 1;
                $cart[$key]['subtotal'] = $cart[$key]['price'] * $cart[$key]['quantity'];
                $tax = 0;
                if(!empty($cart[$key]["tax"])){
                    foreach($cart[$key]["tax"] as $t){
                        $beforeTax = Utility::taxRate($t['tax'], $cart[$key]['price'], $cart[$key]["quantity"]); 
                        $tax += $beforeTax;
                    }
                }
                else{
                    $tax      = 0;
                }
                $cart[$key]["subtotal"]         = $cart[$key]['subtotal'] + $tax;

                if ($originalquantity < $cart[$key]['quantity'] && $session_key == 'pos') {
                    return response()->json(
                        [
                            'code' => 404,
                            'status' => 'Error',
                            'error' => __('This product is out of stock!'),
                        ]
                    );
                }

                session()->put($session_key, $cart);
                return response()->json(
                    [
                        'code' => 200,
                        'status' => 'Success',
                        'success' => $productname . __('   added to cart successfully!'),
                        'product' => $cart[$key],
                        'carttotal' => $cart,
                    ]
                );
            }
        }

        // if item not exist in cart then add to cart with quantity = 1
        $cart[$time] = [
            "product_id" => $product->id,
            "product_name" => $productname,
            "image" => $product->is_cover,
            "quantity" => 1,
            "price" => $productprice,
            "id" => $id,
            "downloadable_prodcut" => $product->downloadable_prodcut,
            "tax" => $itemTaxes,
            "subtotal" => $subtotal,
            "originalquantity" => $originalquantity,
            'variant_id' => 0,
        ];

        if ($originalquantity < $cart[$time]['quantity'] && $session_key == 'pos') {
            return response()->json(
                [
                    'code' => 404,
                    'status' => 'Error',
                    'error' => __('This product is out of stock!'),
                ],
                404
            );
        }

        session()->put($session_key, $cart);
        return response()->json(
            [
                'code' => 200,
                'status' => 'Success',
                'success' => $productname . __(' added to cart successfully!'),
                'product' => $cart[$time],
                'carthtml' => $carthtml,
                'carttotal' => $cart,
            ]
        );
    }
    public function updateCart(Request $request)
    {
        $id          = $request->id;
        $quantity    = $request->quantity;
        $discount    = $request->discount;
        $session_key = $request->session_key;

        if ($request->ajax() && isset($id) && !empty($id) && isset($session_key) && !empty($session_key)) {
            $cart = session()->get($session_key);   

            if (isset($cart[$id]) && $quantity == 0) {
                unset($cart[$id]);
            }
            
            if($cart[$id]['variant_id'] == 0){
                if ($quantity) {

                    $cart[$id]["quantity"] = $quantity;
                    $taxes            = !empty($cart[$id]["tax"]) ? $cart[$id]["tax"]:'';
                    $producttax = 0;
                    if (!empty($taxes)) {
                        foreach ($taxes as $tax) {
                            if (!empty($tax)) {
                                $totalTax = Utility::taxRate($tax['tax'], $cart[$id]['price'], $quantity);
                                $producttax += $totalTax;
                                $itemTax['tax_name'] = $tax['tax_name'];
                                $itemTax['tax'] = $tax['tax'];
                                $itemTaxes[] = $itemTax;
                            }
                        }
                        $productprice          = $cart[$id]["price"] *  (float)$quantity;
                        $subtotal = $productprice +  $producttax;
                    }
                    else{
                    
                        $productprice          = $cart[$id]["price"];
                        $subtotal = $productprice  *  (float)$quantity ;
                    }
                

                    $cart[$id]["subtotal"] = $subtotal ;
                }

                if ( isset($cart[$id]) && isset($cart[$id]["originalquantity"]) < $cart[$id]['quantity'] && $session_key == 'pos') {
                    return response()->json(
                        [
                            'code' => 404,
                            'status' => 'Error',
                            'error' => __('This product is out of stock!'),
                        ],
                        404
                    );
                }
            }else{
                if ($quantity) {

                    $cart[$id]["quantity"] = $quantity;
                    $taxes            = !empty($cart[$id]["tax"]) ? $cart[$id]["tax"]:'';
                    $producttax = 0;
                    if (!empty($taxes)) {
                        foreach ($taxes as $tax) {
                            if (!empty($tax)) {
                                $totalTax = Utility::taxRate($tax['tax'], $cart[$id]['variant_price'], $quantity);
                                $producttax += $totalTax;
                                $itemTax['tax_name'] = $tax['tax_name'];
                                $itemTax['tax'] = $tax['tax'];
                                $itemTaxes[] = $itemTax;
                            }
                        }
                        $productprice          = $cart[$id]["variant_price"] *  (float)$quantity;
                        $subtotal = $productprice +  $producttax;
                    }
                    else{
                    
                        $productprice          = $cart[$id]["variant_price"];
                        $subtotal = $productprice  *  (float)$quantity ;
                    }
                

                    $cart[$id]["variant_subtotal"] = $subtotal ;
                }

                if ( isset($cart[$id]) && isset($cart[$id]["originalvariantquantity"]) < $cart[$id]['quantity'] && $session_key == 'pos') {
                    return response()->json(
                        [
                            'code' => 404,
                            'status' => 'Error',
                            'error' => __('This product is out of stock!'),
                        ],
                        404
                    );
                }
            }

            $subtotal1 = 0;
            foreach($cart as $id => $value){
                if($value['variant_id'] == 0){
                    $subtotal1 += $value['subtotal'];
                }else{
                    $subtotal1 += $value['variant_subtotal'];
                }
            }
            $subtotal = $subtotal1;
            $discount = $request->discount;
            $total = $subtotal - (float)$discount;
            $totalDiscount = Utility::priceFormat($total);
            $discount = $totalDiscount;

            session()->put($session_key, $cart);
            return response()->json(
                [
                    'code' => 200,
                    'success' => __('Cart updated successfully!'),
                    'product' => $cart,
                    'discount' => $discount,
                ]
            );
        } else {
            return response()->json(
                [
                    'code' => 404,
                    'status' => 'Error',
                    'error' => __('This Product is not found!'),
                ],
                404
            );
        }
    }
    public function removeFromCart(Request $request)
    {
        $id          = $request->id;
        $session_key = $request->session_key;
        if (isset($id) && !empty($id) && isset($session_key) && !empty($session_key)) {
            $cart = session()->get($session_key);
            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put($session_key, $cart);
            }

            return redirect()->back()->with('error', __('Product removed from cart!'));
        } else {
            return redirect()->back()->with('error', __('This Product is not found!'));
        }
    }
    public function emptyCart(Request $request)
    {
        $session_key = $request->session_key;

        if (isset($session_key) && !empty($session_key))
        {
            $cart = session()->get($session_key);
            if (isset($cart) && count($cart) > 0)
            {
                session()->forget($session_key);
            }

            return redirect()->back()->with('error', __('Cart is empty!'));
        }
        else
        {
            return redirect()->back()->with('error', __('Cart cannot be empty!.'));

        }
    }
}
