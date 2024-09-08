<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Product;
use App\Models\Utility;
use Illuminate\Http\Request;
use App\Models\ChartOfAccount;
use App\Models\ProductCategorie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductCategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    
        $chart_accounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
        ->where('created_by', \Auth::user()->creatorId())->get()
        ->pluck('code_name', 'id');
        if(\Auth::user()->can('Manage Product category')){
            $user = \Auth::user()->current_store;

            $product_categorys = ProductCategorie::where('store_id', $user)->where('created_by', \Auth::user()->creatorId())->get();
    
            return view('product_category.index', compact('product_categorys'));
        }
        else
        {
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
  

        if(\Auth::user()->can('Create Product category')){
            $types = ProductCategorie::$catTypes;

            $type = ['' => 'Select Category Type'];
            $types = array_merge($type, $types);

             $chart_accounts = ChartOfAccount::select(DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                ->where('created_by', \Auth::user()->creatorId())->get()
                ->pluck('code_name', 'id');
            $chart_accounts->prepend('Select Account', '');
            return view('product_category.create', compact('types', 'chart_accounts'));
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
    
    public function getAccount(Request $request)
    {
        $chart_accounts = [];
        if ($request->type == 'income') {
            $chart_accounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name, chart_of_accounts.id as id'))
            ->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type')
            ->where('chart_of_account_types.name' ,'Income')
            ->where('parent', '=', 0)
            ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())->get()
            ->pluck('code_name', 'id');
        } elseif ($request->type == 'expense') {
            $chart_accounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name, chart_of_accounts.id as id'))
            ->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type')
            ->where('chart_of_account_types.name' ,'Expenses')
            ->where('parent', '=', 0)
            ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())->get()
            ->pluck('code_name', 'id');
        } elseif ($request->type == 'asset') {
            $chart_accounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name, chart_of_accounts.id as id'))
            ->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type')
            ->where('chart_of_account_types.name' ,'Assets')
            ->where('parent', '=', 0)
            ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())->get()
            ->pluck('code_name', 'id');
        } elseif ($request->type == 'liability') {
            $chart_accounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name, chart_of_accounts.id as id'))
            ->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type')
            ->where('chart_of_account_types.name' ,'Liabilities')
            ->where('parent', '=', 0)
            ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())->get()
            ->pluck('code_name', 'id');
        } elseif ($request->type == 'equity') {
            $chart_accounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name, chart_of_accounts.id as id'))
            ->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type')
            ->where('chart_of_account_types.name' ,'Equity')
            ->where('parent', '=', 0)
            ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())->get()
            ->pluck('code_name', 'id');
        } elseif ($request->type == 'costs of good sold') {
            $chart_accounts = ChartOfAccount::select(\DB::raw('CONCAT(chart_of_accounts.code, " - ", chart_of_accounts.name) AS code_name, chart_of_accounts.id as id'))
            ->leftjoin('chart_of_account_types', 'chart_of_account_types.id','chart_of_accounts.type')
            ->where('chart_of_account_types.name' ,'Costs of Goods Sold')
            ->where('parent', '=', 0)
            ->where('chart_of_accounts.created_by', \Auth::user()->creatorId())->get()
            ->pluck('code_name', 'id');
        } else {
            $chart_accounts = 0;
        }

        $subAccounts = ChartOfAccount::select('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.name' , 'chart_of_account_parents.account');
        $subAccounts->leftjoin('chart_of_account_parents', 'chart_of_accounts.parent', 'chart_of_account_parents.id');
        $subAccounts->where('chart_of_accounts.parent', '!=', 0);
        $subAccounts->where('chart_of_accounts.created_by', \Auth::user()->creatorId());
        $subAccounts = $subAccounts->get()->toArray();

    $response = [
        'chart_accounts' => $chart_accounts,
        'sub_accounts' => $subAccounts,
    ];

        return response()->json($response);

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
        if(\Auth::user()->can('Create Product category')){
            $pro_cat = ProductCategorie::where('name', $request->name)->where('store_id',Auth::user()->current_store)->first();


            if(!empty($pro_cat))
            {
                return redirect()->back()->with('error', __('Product Category Already Exist!'));
            }

            if(!empty($request->categorie_img))
            {
                $image_size = $request->file('categorie_img')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);
               
                if($result==1)
                {
                    $filenameWithExt  = $request->file('categorie_img')->getClientOriginalName();
                    $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension        = $request->file('categorie_img')->getClientOriginalExtension();
                    $fileNameToStores = $filename . '_' . time() . '.' . $extension;
                    $settings = Utility::getStorageSetting();
                    if($settings['storage_setting']=='local'){
                        $dir        = 'uploads/product_image/';
                    }
                    else{
                            $dir        = 'uploads/product_image/';
                    }
                    $path = Utility::upload_file($request,'categorie_img',$fileNameToStores,$dir,[]);

                    if($path['flag'] == 1){
                        $url = $path['url'];
                    }else{
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }
                // $dir              = storage_path('uploads/product_image/');
                // if(!file_exists($dir))
                // {
                //     mkdir($dir, 0777, true);
                // }
                // $path = $request->file('categorie_img')->storeAs('uploads/product_image/', $fileNameToStores);
            }

            $productCategorie             = new ProductCategorie();
            $productCategorie['store_id'] = \Auth::user()->current_store;
            $productCategorie['type'] = $request->type;
            $productCategorie['chart_account_id'] = !empty($request->chart_account) ? $request->chart_account : 0;
            $productCategorie['name']     = $request->name;
            if(!empty($fileNameToStores))
            {
                $productCategorie['categorie_img'] = $fileNameToStores;
            }
            $productCategorie['created_by'] = \Auth::user()->creatorId();
            $productCategorie->save();

            return redirect()->back()->with('success', __('Product Category added!'). ((isset($result) && $result!=1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\ProductCategorie $productCategorie
     *
     * @return \Illuminate\Http\Response
     */

    public function show(ProductCategorie $productCategorie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\ProductCategorie $productCategorie
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductCategorie $productCategorie)
    {
        if(\Auth::user()->can('Edit Product category')){
            return view('product_category.edit', compact('productCategorie'));
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\ProductCategorie $productCategorie
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductCategorie $productCategorie)
    {
        if(\Auth::user()->can('Edit Product category')){
            $pro_cat = ProductCategorie::where('name', $request->name)->where('store_id', Auth::user()->current_store)->first();

            if(!empty($request->categorie_img))
            {

                $fileName = $pro_cat->categorie_img !== 'default.jpg' ? $pro_cat->categorie_img : '' ;
                $filePath ='uploads/product_image/'. $fileName;

                $image_size = $request->file('categorie_img')->getSize();
                $result = Utility::updateStorageLimit(\Auth::user()->creatorId(), $image_size);

                if($result == 1){
                    Utility::changeStorageLimit(\Auth::user()->creatorId(),$filePath);
                    $filenameWithExt  = $request->file('categorie_img')->getClientOriginalName();
                    $filename         = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension        = $request->file('categorie_img')->getClientOriginalExtension();
                    $fileNameToStores = $filename . '_' . time() . '.' . $extension;
                    $settings = Utility::getStorageSetting();
                    if($settings['storage_setting']=='local'){
                        $dir        = 'uploads/product_image/';
                    }
                    else{
                            $dir        = 'uploads/product_image/';
                    }
                    $path = Utility::upload_file($request,'categorie_img',$fileNameToStores,$dir,[]);
    
                    if($path['flag'] == 1){
                        $url = $path['url'];
                    }else{
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }
                // $dir              = storage_path('uploads/product_image/');
                // if(asset(Storage::exists('uploads/product_image/' . ($productCategorie['categorie_img']))))
                // {
                //     asset(Storage::delete('uploads/product_image/' . $productCategorie['categorie_img']));
                // }

                // if(!file_exists($dir))
                // {
                //     mkdir($dir, 0777, true);
                // }

                // $path = $request->file('categorie_img')->storeAs('uploads/product_image/', $fileNameToStores);
            }


            $productCategorie['name'] = $request->name;
            if(!empty($fileNameToStores))
            {
                $productCategorie['categorie_img'] = $fileNameToStores;
            }
            $productCategorie['created_by'] = \Auth::user()->creatorId();
            $productCategorie->update();

            return redirect()->back()->with('success', __('Product Category updated!'). ((isset($result) && $result!=1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\ProductCategorie $productCategorie
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCategorie $productCategorie)
    {
        if(\Auth::user()->can('Delete Product category')){
            $product = Product::where('product_categorie', $productCategorie->id)->get();

            if($product->count() != 0)
            {
                return redirect()->back()->with(
                    'error', __('Category is used in products!')
                );
            }
            else
            {
                $fileName = $productCategorie->categorie_img !== 'default.jpg' ? $productCategorie->categorie_img : '' ;
                $filePath ='uploads/product_image/'. $fileName;
                
                Utility::changeStorageLimit(\Auth::user()->creatorId(),$filePath);
                $productCategorie->delete();

                return redirect()->back()->with(
                    'success', __('Product Category Deleted!')
                );
            }
        }
        else{
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
    public function getProductCategories(){
        $user = \Auth::user()->current_store;
        $productCategory = ProductCategorie::where('store_id',$user)->where('type',"")->get();    
        $html = '<div class="mb-3 mr-2 mx-2 zoom-in ">
                    <div class="card rounded-10 card-stats mb-0 cat-active overflow-hidden" data-id="0">
                    <div class="category-select" data-cat-id="0">
                        <button type="button" class="btn tab-btns btn-primary">'.__("All Categories").'</button>
                    </div>
                    </div>
                </div>';
        foreach($productCategory as $key => $cat){
            $dcls = 'category-select';
            $html .= ' <div class="mb-3 mr-2 mx-2 zoom-in cat-list-btn">
            <div class="card rounded-10 card-stats mb-0 overflow-hidden " data-id="'.$cat->id.'">
               <div class="'.$dcls.'" data-cat-id="'.$cat->id.'">
                  <button type="button" class="btn tab-btns ">'.$cat->name.'</button>
               </div>
            </div>
         </div>';
         
        }
        return Response($html);
    }
}
