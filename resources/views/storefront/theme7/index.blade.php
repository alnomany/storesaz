@extends('storefront.layout.theme7')

@section('page-title')
    {{ __('Home') }}
@endsection

@push('css-page')
<style>
    [dir="rtl"] .footer-link-2 {
        text-align: right;
    }
</style>
@endpush
@php
$imgpath=\App\Models\Utility::get_file('uploads/');
$productImg = \App\Models\Utility::get_file('uploads/is_cover_image/');
$catimg = \App\Models\Utility::get_file('uploads/product_image/');
$default =\App\Models\Utility::get_file('uploads/theme7/header/img01.jpg');
$s_logo = \App\Models\Utility::get_file('uploads/store_logo/');

@endphp
@section('content')
<div class="wrapper">
    @foreach ($pixelScript as $script)
        <?= $script; ?>
    @endforeach
    @php
        $homepage_header_text = $homepage_header_btn = $homepage_header_bg_img = '';
        $homepage_header_2_key = array_search('Home-Header', array_column($getStoreThemeSetting, 'section_name'));

        // if ( is_int($homepage_header_2_key)) {
        if (!empty($getStoreThemeSetting[$homepage_header_2_key])) {
            $homepage_header_2 = $getStoreThemeSetting[$homepage_header_2_key];
        }
    @endphp
    @if ($getStoreThemeSetting[0]['section_enable'] == 'on')
    <section class="banner-section padding-top padding-bottom">
            @if ($getStoreThemeSetting[0]['section_enable'] == 'on')
                <div class="banner-left-side">
                    <img src="{{ $imgpath. (!empty($getStoreThemeSetting[1]['homepage-header-bg-image'][0]['image']) ? $getStoreThemeSetting[1]['homepage-header-bg-image'][0]['image'] : $getStoreThemeSetting[1]['inner-list'][0]['field_default_text']) }}">
                    <ul>
                        @if (isset($getStoreThemeSetting[2]['homepage-sidebar-social-icon']) || isset($getStoreThemeSetting[2]['homepage-sidebar-social-link']))
                            @if (isset($getStoreThemeSetting[2]['inner-list'][1]['field_default_text']) && isset($getStoreThemeSetting[2]['inner-list'][0]['field_default_text']))
                                @foreach ($getStoreThemeSetting[2]['homepage-sidebar-social-icon'] as $icon_key => $storethemesettingicon)
                                    @foreach ($getStoreThemeSetting[2]['homepage-sidebar-social-link'] as $link_key => $storethemesettinglink)
                                        @if ($icon_key == $link_key)
                                            <li>
                                                <a href="{{ $storethemesettinglink }}">
                                                    {!! $storethemesettingicon !!}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endif
                        @else
                            @for ($i = 0; $i < $getStoreThemeSetting[2]['loop_number']; $i++)
                                @if (isset($getStoreThemeSetting[2]['inner-list'][1]['field_default_text']) && isset($getStoreThemeSetting[2]['inner-list'][0]['field_default_text']))
                                    <li>
                                        <a href="{{ $getStoreThemeSetting[2]['inner-list'][1]['field_default_text'] }}" target="_blank">
                                            {!! $getStoreThemeSetting[2]['inner-list'][0]['field_default_text'] !!}
                                        </a>
                                    </li>
                                @endif
                            @endfor
                        @endif
                    </ul>
                </div>
            @endif
            {{--  @if ($getStoreThemeSetting[0]['section_enable'] == 'on')  --}}
                <div class="container">
                    <div class="banner-slider">
                        @for ($i = 0; $i < $homepage_header_2['loop_number']; $i++)
                        @php
                            foreach ($homepage_header_2['inner-list'] as $homepage_header_2_value) {
                                if ($homepage_header_2_value['field_slug'] == 'homepage-header-title') {
                                    $homepage_header_text = $homepage_header_2_value['field_default_text'];
                                }
                                if ($homepage_header_2_value['field_slug'] == 'homepage-sub-text') {
                                    $homepage_header_sub_text = $homepage_header_2_value['field_default_text'];
                                }
                                if ($homepage_header_2_value['field_slug'] == 'homepage-header-button') {
                                    $homepage_header_btn = $homepage_header_2_value['field_default_text'];
                                }
                                if ($homepage_header_2_value['field_slug'] == 'header-tag') {
                                    $homepage_header_tag = $homepage_header_2_value['field_default_text'];
                                }
                    
                                if (!empty($homepage_header_2[$homepage_header_2_value['field_slug']])) {
                                    if ($homepage_header_2_value['field_slug'] == 'homepage-header-title') {
                                        $homepage_header_text = $homepage_header_2[$homepage_header_2_value['field_slug']][$i];
                                    }
                                    if ($homepage_header_2_value['field_slug'] == 'homepage-sub-text') {
                                        $homepage_header_sub_text = $homepage_header_2[$homepage_header_2_value['field_slug']][$i];
                                    }
                                    if ($homepage_header_2_value['field_slug'] == 'header-tag') {
                                        $homepage_header_tag = $homepage_header_2[$homepage_header_2_value['field_slug']][$i];
                                    }
                                    if ($homepage_header_2_value['field_slug'] == 'homepage-header-button') {
                                        $homepage_header_btn = $homepage_header_2[$homepage_header_2_value['field_slug']][$i];
                                    }
                                }
                            }
                        @endphp
                            <div class="banner-main-itm">
                                <div class="banner-inner">
                                    <div class="banner-content">
                                        <img src="{{ $imgpath. (!empty($homepage_header_tag['field_prev_text']) ? $homepage_header_tag['field_prev_text'] : $homepage_header_tag) }}">
                                        <h2 class="h1"> {{ $homepage_header_text }} </h2>
                                        <p>{{ $homepage_header_sub_text }}
                                        </p>
                                        <a href="#" class="btn start_shopping" id="start_shopping">{{ $homepage_header_btn }}</a>
                                    </div>
                                    @if (sizeof($theme7_product))
                                        <div class="banner-right">
                                            @if ($theme7_product != null)
                                                <div class="banner-img">
                                                    <div class="banner-img-inner">
                                                        @if ($i == 0)
                                                            @if (!empty($theme7_product_image[1]))
                                                                @if ($theme7_product_image->count() > 0 )
                                                                    <img src="{{ $catimg . $theme7_product_image[1]['product_images'] }}"> 
                                                                @else
                                                                    <img src="{{ asset(Storage::url('uploads/product_image/default.jpg')) }}"> 
                                                                @endif
                                                                <div class="banner-img-text">
                                                                    @if ($theme7_product[0]['enable_product_variant'] == 'on')
                                                                        <a href="{{ route('store.product.product_view', [$store->slug, $theme7_product[0]['id']]) }}" class="btn">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="512px" id="Layer_1"  version="1.1" viewBox="0 0 512 512" width="512px" xml:space="preserve"><polygon points="448,224 288,224 288,64 224,64 224,224 64,224 64,288 224,288 224,448 288,448 288,288 448,288 "/></svg>
                                                                        </a>
                                                                    @else
                                                                        <a class="btn add_to_cart"  data-id="{{ $theme7_product[0]['id'] }}">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="512px" id="Layer_1"  version="1.1" viewBox="0 0 512 512" width="512px" xml:space="preserve"><polygon points="448,224 288,224 288,64 224,64 224,224 64,224 64,288 224,288 224,448 288,448 288,288 448,288 "/></svg>
                                                                        </a>
                                                                    @endif
                                                                    <p>{{ $theme7_product[0]['name'] }}</p>
                                                                    <div class="price">
                                                                        @if ($theme7_product[0]['enable_product_variant'] == 'on')
                                                                            {{ __('In variant') }}
                                                                        @else
                                                                            {{ \App\Models\Utility::priceFormat($theme7_product[0]['price']) }}
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @else
                                                            @if (!empty($theme7_product_image[0]))
                                                                @if ($theme7_product_image->count() > 0 )
                                                                    <img src="{{ $catimg . $theme7_product_image[0]['product_images'] }}"> 
                                                                @else
                                                                    <img src="{{ asset(Storage::url('uploads/product_image/default.jpg')) }}"> 
                                                                @endif
                                                                <div class="banner-img-text">
                                                                    @if (!empty($theme7_product[1]) && $theme7_product[1]['enable_product_variant'] == 'on')
                                                                        <a href="{{ route('store.product.product_view', [$store->slug, $theme7_product[1]['id']]) }}" class="btn">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="512px" id="Layer_1"  version="1.1" viewBox="0 0 512 512" width="512px" xml:space="preserve"><polygon points="448,224 288,224 288,64 224,64 224,224 64,224 64,288 224,288 224,448 288,448 288,288 448,288 "/></svg>
                                                                        </a>
                                                                    @else 
                                                                        <a class="btn add_to_cart"  data-id="{{ $theme7_product[1]['id'] }}">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="512px" id="Layer_1"  version="1.1" viewBox="0 0 512 512" width="512px" xml:space="preserve"><polygon points="448,224 288,224 288,64 224,64 224,224 64,224 64,288 224,288 224,448 288,448 288,288 448,288 "/></svg>
                                                                        </a>
                                                                    @endif
                                                
                                                                    <p>{{ $theme7_product[1]['name'] }}</p>
                                                                    <div class="price">
                                                                        @if (!empty($theme7_product[1]) && $theme7_product[1]['enable_product_variant'] == 'on')
                                                                            {{ __('In variant') }}
                                                                        @else
                                                                         {{ \App\Models\Utility::priceFormat($theme7_product[1]['price']) }}
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
    </section>
@endif
    <section class="store-promotions">
        @if ($getStoreThemeSetting[3]['section_enable'] == 'on')
            <div class="container">
                <div class="row">
                    @foreach ($getStoreThemeSetting as $key => $storethemesetting)
                        @if ($storethemesetting['section_name'] == 'Home-Promotions' && $storethemesetting['array_type'] == 'multi-inner-list')
                            @if (isset($storethemesetting['homepage-promotions-font-icon']) || isset($storethemesetting['homepage-promotions-title']) || isset($storethemesetting['homepage-promotions-description']))
                                @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                    <div class="col-lg-4 col-md-4 col-12 store-promotions">
                                        <div class="store-promotions-box">
                                            {!! $storethemesetting['homepage-promotions-font-icon'][$i] !!}
                                            <h4> {{ $storethemesetting['homepage-promotions-title'][$i] }}</h4>
                                            <p>{{ $storethemesetting['homepage-promotions-description'][$i] }}</p>
                                        </div>
                                    </div>
                                @endfor
                            @else
                                @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                    <div class="col-lg-4 col-md-4 col-12 store-promotions">
                                        <div class="store-promotions-box">
                                            {!! $storethemesetting['inner-list'][0]['field_default_text'] !!}
                                            <h4> {{ $storethemesetting['inner-list'][1]['field_default_text'] }}</h4>
                                            <p>{{ $storethemesetting['inner-list'][2]['field_default_text'] }}</p>
                                        </div>
                                    </div>
                                @endfor
                            @endif
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </section>
    <section class="bestseller-section tabs-wrapper padding-top padding-bottom" id="shopping_section">
        <div class="container">
            <div class="section-title">
                <h2>{{ __('Bestsellers') }}</h2>
            </div>
            <div class="tab-bar">
                <ul class="cat-tab tabs">
                    @foreach ($categories as $key => $category)
                        <li class="tab-link {{ $key == 0 ? 'active' : '' }}" data-tab="tab-{!! preg_replace('/[^A-Za-z0-9\-]/', '_', $category) !!}">
                            <a> {{__($category)}}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="tabs-container">
                @foreach($products as $key => $items)
                    <div id="tab-{!! preg_replace('/[^A-Za-z0-9\-]/', '_', $key)!!}" class="tab-content {{($key=='Start shopping')?'active':''}}">
                        <div class="row product-row">
                            @foreach ($items as $key => $product)
                                @if ($key < 4)
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                        <div class="product-card">
                                            <div class="product-card-inner">
                                                <div class="bestseller-tag">
                                                    <p>{{ __('Bestseller') }}</p>
                                                </div>
                                                <div class="product-card-body">
                                                    <div class="card-img">
                                                        <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}">
                                                            @if (!empty($product->is_cover) )
                                                                <img src="{{ $productImg . $product->is_cover }}">
                                                            @else
                                                                <img src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}">
                                                            @endif
                                                        </a>
                                                        @if (Auth::guard('customers')->check())
                                                            @if (!empty($wishlist) && isset($wishlist[$product->id]['product_id']))
                                                                @if ($wishlist[$product->id]['product_id'] != $product->id)
                                                                    <a
                                                                        class="heart-icon action-item wishlist-icon bg-light-gray add_to_wishlist wishlist_{{ $product->id }}"
                                                                        data-id="{{ $product->id }}">
                                                                        <i class="far fa-heart"></i>
                                                                    </a>
                                                                @else
                                                                    <a class="heart-icon action-item wishlist-icon bg-light-gray add_to_wishlist wishlist_{{ $product->id }}" disabled>
                                                                        <i class="fas fa-heart"></i>
                                                                    </a>
                                                                @endif
                                                            @else
                                                                <a
                                                                    class="heart-icon action-item wishlist-icon bg-light-gray add_to_wishlist wishlist_{{ $product->id }}"
                                                                    data-id="{{ $product->id }}">
                                                                    <i class="far fa-heart"></i>
                                                                </a>
                                                            @endif
                                                        @else
                                                            <a
                                                                class="heart-icon action-item wishlist-icon bg-light-gray add_to_wishlist wishlist_{{ $product->id }}"
                                                                data-id="{{ $product->id }}">
                                                                <i class="far fa-heart"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                    <div class="card-content">
                                                        <h6>
                                                            <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}"> {{ $product->name }}</a>
                                                        </h6>
                                                    <p><span>{{ __('Category') }}:</span>  {{ $product->product_category() }}</p>
                                                    <div class="price">
                                                        @if ($product->enable_product_variant == 'on')
                                                            {{ __('In variant') }}
                                                        @else
                                                            {{ \App\Models\Utility::priceFormat($product->price) }}
                                                        @endif
                                                    </div>
                                                    @if ($product->enable_product_variant == 'on')
                                                        <div class="last-btn">
                                                            <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}" class="btn" tabindex="0">{{ __('ADD TO CART') }}</a>
                                                        </div>
                                                    @else
                                                        <div class="last-btn">
                                                            <a href="javascript:void(0)" class="btn add_to_cart" tabindex="0"  data-id="{{ $product->id }}"> {{ __('ADD TO CART') }}</a>
                                                        </div>
                                                    @endif
                                                    </div>
                                                </div>
                                            
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="showmore-btn text-center">
                <a href="{{ route('store.categorie.product', [$store->slug, 'Start shopping']) }}" class="btn" tabindex="0">{{ __('Show More') }}</a>
            </div>
        </div>
    </section>
    @if ($getStoreThemeSetting[4]['section_enable'] == 'on')
        <section class="featured-product padding-bottom padding-top">
            <div class="offset-container offset-left">
                <div class="row no-gutters align-items-center">
                    <div class="col-md-5 col-12">
                        <div class="featured-product-detail">
                            <h3> {{ $getStoreThemeSetting[4]['inner-list'][0]['field_default_text'] }}</h3> 
                            <p> {{ $getStoreThemeSetting[4]['inner-list'][1]['field_default_text'] }}
                            </p>
                            <a href="{{ route('store.categorie.product', [$store->slug, 'Start shopping']) }}" class="btn" tabindex="0"> {{ $getStoreThemeSetting[4]['inner-list'][3]['field_default_text'] }}</a> 
                            @if(!empty($mostPurchasedDetail))
                                <div class="featured-card">
                                    <div class="featured-card-inner">
                                        <div class="bestseller-tag">
                                            <p> {{ __('Bestseller') }}</p>
                                        </div>
                                        <div class="featured-card-img">
                                            <a href="{{ route('store.product.product_view', [$store->slug, $mostPurchasedDetail->id]) }}">
                                                @if (!empty($mostPurchasedDetail->is_cover) )
                                                    <img src="{{ $productImg. $mostPurchasedDetail->is_cover }}">
                                                @else
                                                    <img src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="featured-content">
                                            <h6> {{ $mostPurchasedDetail->name }}</h6>
                                            
                                            {{--  <p>{{ __('Category') }}: {{ $product->product_category() }}</p>  --}}
                                            <div class="price-btn">
                                                <div class="price">
                                                    @if ($mostPurchasedDetail->enable_product_variant == 'on')
                                                        {{ __('In variant') }}
                                                    @else
                                                        {{ \App\Models\Utility::priceFormat($mostPurchasedDetail->price) }}
                                                    @endif
                                                </div>
                                            @if ($mostPurchasedDetail->enable_product_variant == 'on')
                                                <a href="{{ route('store.product.product_view', [$store->slug, $mostPurchasedDetail->id]) }}" class="btn" tabindex="0"> {{ __('ADD TO CART') }}</a>
                                            @else
                                                <a href="javascript:void(0)" class="btn add_to_cart" tabindex="0" data-id="{{ $mostPurchasedDetail->id }}"> {{ __('ADD TO CART') }}</a>
                                            @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-7 col-12">
                        <div class="featured-main-img">
                            <img src="{{ $imgpath. $getStoreThemeSetting[4]['inner-list'][2]['field_default_text'] }}">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    @if ($getStoreThemeSetting[6]['section_enable'] == 'on')
        <section class="category-section padding-top padding-bottom">
            <div class="container">
                <div class="section-title text-center">
                    @foreach ($getStoreThemeSetting as $storethemesetting)
                        @if (isset($storethemesetting['section_name']) && $storethemesetting['section_name'] == 'Home-Categories' && !empty($pro_categories))
                            @php
                                $Titlekey = array_search('Title', array_column($storethemesetting['inner-list'], 'field_name'));
                                $Title = $storethemesetting['inner-list'][$Titlekey]['field_default_text'];

                                $Description_key = array_search('Description', array_column($storethemesetting['inner-list'], 'field_name'));
                                $Description = $storethemesetting['inner-list'][$Description_key]['field_default_text'];

                                $catImg_key = array_search('Image', array_column($storethemesetting['inner-list'], 'field_name'));
                                $catImg = $storethemesetting['inner-list'][$catImg_key]['field_default_text'];
                            @endphp
                            <img src="{{ $imgpath. $catImg }}">
                            <h2>{{ $Title }}</h2>
                            <p> {{ $Description }}</p>
                        @endif
                    @endforeach
                </div>
                <div class="row product-row">
                    @foreach ($pro_categories as $key => $pro_categorie)
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="category-card">
                                <div class="category-card-inner">
                                    <div class="product-card-body">
                                        <div class="card-img">
                                            @if (!empty($pro_categorie->categorie_img))
                                                <a>
                                                    <img src="{{ $catimg . (!empty($pro_categorie->categorie_img) ? $pro_categorie->categorie_img : 'default.jpg') }}">
                                                </a>
                                            @else
                                                <a>
                                                    <img src="{{ asset(Storage::url('uploads/product_image/default.jpg')) }}">
                                                </a>
                                            @endif
                                        </div>
                                        <div class="card-content">
                                            <h6>
                                                <a>{{ $pro_categorie->name }}</a>
                                            </h6>
                                            <a href="{{ route('store.categorie.product', [$store->slug, $pro_categorie->name]) }}" class="btn">{{ __('VIEW MORE') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if (count($topRatedProducts) > 0)
        <section class="total-rated-product padding-top padding-bottom">
            <div class="container">
                <div class="section-title d-flex align-items-center justify-content-between">
                    <h2 >{{ __('Top rated products') }}</h2>
                    <a href="{{ route('store.categorie.product', $store->slug) }}" class="btn">{{ __('Show more products') }}</a>
                </div>
                <div class="row product-row">
                    @foreach ($topRatedProducts as $k => $topRatedProduct)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="product-card">
                                <div class="product-card-inner">
                                    <div class="bestseller-tag">
                                        <p>{{ __('BESTSELLER') }}</p>
                                    </div>
                                    <div class="product-card-body">
                                        <div class="card-img">
                                            <a href="{{ route('store.product.product_view', [$store->slug, $topRatedProduct->product_id]) }}">
                                                @if (!empty($topRatedProduct->product->is_cover))
                                                    <img src="{{$productImg . $topRatedProduct->product->is_cover }}">
                                                @else
                                                    <img src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}">
                                                @endif
                                            </a>
                                            @if (Auth::guard('customers')->check())
                                                @if (!empty($wishlist) && isset($wishlist[$topRatedProduct->product->id]['product_id']))
                                                    @if ($wishlist[$topRatedProduct->product->id]['product_id'] != $topRatedProduct->product->id)
                                                        <a
                                                            class="heart-icon action-item wishlist-icon bg-light-gray add_to_wishlist wishlist_{{ $topRatedProduct->product->id }}" data-id="{{ $topRatedProduct->product->id }}">
                                                            <i class="far fa-heart"></i>
                                                        </a>
                                                    @else
                                                        <a class="heart-icon action-item wishlist-icon bg-light-gray" data-id="{{ $topRatedProduct->product->id }}" disabled>
                                                            <i class="fas fa-heart"></i>
                                                        </a>
                                                    @endif
                                                @else
                                                    <a
                                                        class="heart-icon action-item wishlist-icon bg-light-gray add_to_wishlist wishlist_{{ $topRatedProduct->product->id }}" data-id="{{ $topRatedProduct->product->id }}">
                                                        <i class="far fa-heart"></i>
                                                    </a>
                                                @endif
                                            @else
                                                <a
                                                    class="heart-icon action-item wishlist-icon bg-light-gray add_to_wishlist wishlist_{{ $topRatedProduct->product->id }}" data-id="{{ $topRatedProduct->product->id }}">
                                                    <i class="far fa-heart"></i>
                                                </a>
                                            @endif
                                        </div>
                                        <div class="card-content">
                                            <h6>
                                                <a href="{{ route('store.product.product_view', [$store->slug, $topRatedProduct->product_id]) }}">{{ $topRatedProduct->product->name }}</a>
                                            </h6>
                                            <p>{{ __('Category') }}:  {{$topRatedProduct->product->product_category()}}</p>
                                        <div class="price">
                                            <ins>
                                                @if ($topRatedProduct->product->enable_product_variant == 'on')
                                                    {{ __('In variant') }}
                                                @else
                                                    {{ \App\Models\Utility::priceFormat($topRatedProduct->product->price) }}
                                                @endif
                                            </ins>
                                        </div>
                                            <div class="last-btn">
                                                @if ($topRatedProduct->product->enable_product_variant == 'on')
                                                    <a href="{{ route('store.product.product_view', [$store->slug, $topRatedProduct->product->id]) }}" class="btn" tabindex="0"></a>
                                                @else
                                                    <a href="#" class="btn add_to_cart" tabindex="0" data-id="{{ $topRatedProduct->product->id }}">{{ __('Add to cart') }} </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($getStoreThemeSetting[7]['section_enable'] == 'on')
        <section class="testimonial-section padding-top padding-bottom">
            <div class="container">
                <div class="section-title text-center">
                    <h3>{{ $getStoreThemeSetting[7]['inner-list'][0]['field_default_text'] }}</h3>
                </div>
                <div class="testimonial-slider">
                    @foreach ($getStoreThemeSetting as $key => $storethemesetting)
                        @if (isset($storethemesetting['section_name']) && $storethemesetting['section_name'] == 'Home-Testimonial' && $storethemesetting['array_type'] == 'multi-inner-list')
                            @if (isset($storethemesetting['homepage-testimonial-card-image']) || isset($storethemesetting['homepage-testimonial-card-title']) || isset($storethemesetting['homepage-testimonial-card-sub-text']) || isset($storethemesetting['homepage-testimonial-card-description']) || isset($storethemesetting['homepage-testimonial-card-enable']))
                                @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                    @if($storethemesetting['homepage-testimonial-card-enable'][$i] == 'on')
                                        <div class="testimonial-card">
                                            <div class="testimonial-inner">
                                                <p>
                                                    {{ $storethemesetting['homepage-testimonial-card-description'][$i] }}
                                                </p>
                                                <div class="review-box">
                                                    <img src="{{ $imgpath. $storethemesetting['homepage-testimonial-card-image'][$i]['field_prev_text'] }}">
                    
                                                    <div class="review-inner">
                                                        <h5>{{ $storethemesetting['homepage-testimonial-card-title'][$i] }}</h5>
                                                    </div>
                                                
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endfor
                            @else
                                @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                    <div class="testimonial-card">
                                        <div class="testimonial-inner">
                                            <p>
                                                {{ $storethemesetting['inner-list'][3]['field_default_text'] }}
                                            </p>
                                            <div class="review-box">
                                                <img src="{{ $imgpath . (!empty($storethemesetting['inner-list'][1]['field_default_text']) ? $storethemesetting['inner-list'][1]['field_default_text'] : 'avatar.png') }}">
                
                                                <div class="review-inner">
                                                    <h5>{{ $storethemesetting['inner-list'][2]['field_default_text'] }}</h5>
                                                </div>
                                            
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            @endif
                        @endif
                    @endforeach
                    
                </div>
            </div>
        </section>
    @endif
    @if ($getStoreThemeSetting[9]['section_enable'] == 'on')
        <section class="blog-section padding-top padding-bottom">
            <div class="container">
                <div class="section-title text-center">
                    <h3>{{ $getStoreThemeSetting[9]['inner-list'][1]['field_default_text'] }}</h3>
                    <p>
                        {{ $getStoreThemeSetting[9]['inner-list'][2]['field_default_text'] }}
                    </p>
                </div>
                <div class="row blog-row">
                    @foreach ($getStoreThemeSetting as $key => $storethemesetting)
                        @if (isset($storethemesetting['section_name']) && $storethemesetting['section_name'] == 'Home-Brand-Logo' && $storethemesetting['section_enable'] == 'on')
                            @foreach ($storethemesetting['inner-list'] as $image)
                                @if ($image['field_slug'] == 'homepage-brand-logo-input')
                                    @if (!empty($image['image_path']))
                                        @foreach ($image['image_path'] as $img)
                                            <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                                <div class="blog-img">
                                                    <img src="{{ $imgpath . (!empty($img) ? $img : 'theme5/brand_logo/brand_logo.png') }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                            <div class="blog-img">
                                                <img src="{{ $default}}">
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    @foreach ($getStoreThemeSetting as $storesetting)
        @if ($storesetting['section_name'] == 'Quote' && $storesetting['section_enable'] == 'on')
            @php
                foreach ($storesetting['inner-list'] as $value) {
                    $quote = $value['field_default_text'];
                }
            @endphp
            <div class="container">
                <div class="section-title text-center">
                    <p>{{ $quote }}</p>
                </div>
            </div>
        @endif
    @endforeach
</div> 

@endsection

@push('script-page')
    <script>
        $(document).ready(function() {


            // setTimeout(() => {
            //     // $('#Furniture').addClass('active');
            //     // $('#all-products-tab').trigger('click');
            //     // $("#myTab li:eq(0)").addClass('active');
            //     // $("#myTab li a:eq(0)").addClass('active');
            // }, 500);
        });
        // Tab js
        $('ul.tabs li').click(function() {
            // alert('hello')
            var $this = $(this);
            var $theTab = $(this).attr('data-tab');
            if ($this.hasClass('active')) {} else {
                $this.closest('.tabs-wrapper').find('ul.tabs li, .tabs-container .tab-content').removeClass(
                    'active');
                $('.tabs-container .tab-content[id="' + $theTab + '"], ul.tabs li[data-tab="' + $theTab + ']')
                    .addClass('active');
            }
            $('ul.tabs li').removeClass('active');
            $(this).addClass('active');
            // $('.product-tab-slider').slick('refresh');
        });

        $(".productTab").click(function(e) {
            e.preventDefault();
            $('.productTab').removeClass('active')

        });


        $(".start_shopping").click(function() {
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#shopping_section").offset().top
            },100);
        });
    </script>
@endpush
