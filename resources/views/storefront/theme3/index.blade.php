@extends('storefront.layout.theme3')
@section('page-title')
    {{__('Home')}}
@endsection
@push('css-page')
    <style>
        .product-box .product-price {
            justify-content: unset;
        }

        .p-tablist .nav-tabs .nav-item .nav-link.active {
            background-color: #fff !important;
            border-radius: 25px;
            padding: 10px;
        }

        .p-tablist .nav-tabs .nav-item .nav-link {
            border-radius: 25px;
            padding: 10px;
        }
        .nav-tabs {
            border-bottom: none;
        }
    </style>
@endpush
@php
$imgpath=\App\Models\Utility::get_file('uploads/');
$productImg = \App\Models\Utility::get_file('uploads/is_cover_image/');
$catimg = \App\Models\Utility::get_file('uploads/product_image/');
$default =\App\Models\Utility::get_file('uploads/theme2/header/storego-image.png');
$s_logo = \App\Models\Utility::get_file('uploads/blog_cover_image/');

@endphp
@section('content') 
<div class="wrapper">
    @foreach ($pixelScript as $script)
        <?= $script; ?>
    @endforeach
    <section class="main-home-first-section">
        <div class="offset-container offset-left">
            <div class="row justify-content-end">
                @if($theme3_product != null)
                    <div class="col-lg-6 col-12">
                        <div class="home-banner-product padding-top">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12">
                                    <div class="banner-title">
                                        <h1>{{$theme3_product->name}}</h1>
                                    </div>
                                    <div class="banner-links">
                                        <ul class="banner-list">
                                            <li><a href="{{route('store.product.product_view',[$store->slug,$theme3_product->id])}}" class="text-dark">{{__('DESCRIPTION')}}</a></li>
                                            <li><a href="{{route('store.product.product_view',[$store->slug,$theme3_product->id])}}" class="text-dark">{{__('SPECIFICATION')}}</a></li>
                                            <li><a href="{{route('store.product.product_view',[$store->slug,$theme3_product->id])}}" class="text-dark">{{__('DETAILS')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="product-img">
                                        {{--  @if($theme3_product_image != null && $theme3_product_image->count()>0)
                                            <img src="{{$catimg .$theme3_product_image[0]['product_images']}}" alt="">
                                        @endif  --}}
                                        @if($theme3_product_image != null &&  $theme3_product_image->count()>0)
                                            <img src="{{$catimg.$theme3_product_image[0]['product_images']}}" alt="">
                                        @endif
                                    </div>
                                    @if($theme3_product_image != null )
                                        @if($theme3_product['enable_product_variant'] == 'on')
                                            <div class="price justify-content-center">
                                                <ins><span class="currency-type"></span> {{__('In variant')}}</ins>
                                            </div>
                                            <div class="cart-btns">
                                                <a href="{{route('store.product.product_view',[$store->slug,$theme3_product->id])}}" class="btn">{{__('ADD TO CART')}}<i class="fas fa-shopping-basket"></i></a>
                                                @if(!empty($wishlist) && isset($wishlist[$theme3_product->id]['product_id']))
                                                    @if($wishlist[$theme3_product->id]['product_id'] != $theme3_product->id)
                                                        <a class="btn-secondary wish-btn add_to_wishlist wishlist_{{$theme3_product->id}}" data-id="{{$theme3_product->id}}"><i class="far fa-heart"></i></a>
                                                    @else
                                                        <a class="btn-secondary wish-btn"><i class="fas fa-heart" data-id="{{$theme3_product->id}}"></i></a>
                                                    @endif
                                                @else
                                                    <a class="btn-secondary wish-btn add_to_wishlist wishlist_{{$theme3_product->id}}" data-id="{{$theme3_product->id}}"><i class="far fa-heart"></i></a>
                                                @endif
                                            </div>
                                        @else
                                            <div class="price justify-content-center">
                                                <ins>{{\App\Models\Utility::priceFormat($theme3_product->price)}}</ins>
                                            </div>
                                            <div class="cart-btns">
                                                <a class="btn add_to_cart" data-id="{{$theme3_product->id}}">{{__('ADD TO CART')}}<i class="fas fa-shopping-basket"></i></a>
                                                @if(!empty($wishlist) && isset($wishlist[$theme3_product->id]['product_id']))
                                                    @if($wishlist[$theme3_product->id]['product_id'] != $theme3_product->id)
                                                        <a class="btn-secondary wish-btn add_to_wishlist wishlist_{{$theme3_product->id}}" data-id="{{$theme3_product->id}}"><i class="far fa-heart"></i></a>
                                                    @else
                                                        <a class="btn-secondary wish-btn"><i class="fas fa-heart" data-id="{{$theme3_product->id}}"></i></a>
                                                    @endif
                                                @else
                                                    <a class="btn-secondary wish-btn add_to_wishlist wishlist_{{$theme3_product->id}}" data-id="{{$theme3_product->id}}"><i class="far fa-heart"></i></a>
                                                @endif
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-lg-6 col-12">
                    @if($getStoreThemeSetting[0]['section_enable'] == 'on')
                        @foreach($getStoreThemeSetting as $storethemesetting)
                            @if($storethemesetting['section_name'] == 'Banner-Image')
                                <div class="main-banner-img">
                                    <img src="{{$imgpath.(!empty($storethemesetting['inner-list'][0]['field_default_text'])?$storethemesetting['inner-list'][0]['field_default_text']:'theme3/header/header_img_3.png')}}" alt="">
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </section>
    <section class="store-promotions-section">
        @if($getStoreThemeSetting[1]['section_enable'] == 'on')
            <div class="container">
                <div class="row justify-content-center">
                    @foreach ($getStoreThemeSetting as $key => $storethemesetting)
                        @if ($storethemesetting['section_name'] == 'Home-Promotions')
                            @if (isset($storethemesetting['homepage-promotions-font-icon']) || isset($storethemesetting['homepage-promotions-title']) || isset($storethemesetting['homepage-promotions-description']))
                                @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                    <div class="col-lg-4 col-md-6 col-12">
                                        <div class="about-promotions">
                                            <h3>{!! $storethemesetting['homepage-promotions-font-icon'][$i] !!} {{ $storethemesetting['homepage-promotions-title'][$i] }}</h3>
                                            <p> {{ $storethemesetting['homepage-promotions-description'][$i] }}</p>
                                        </div>
                                    </div>
                                @endfor
                            @else
                                @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                    <div class="col-lg-4 col-md-6 col-12">
                                        <div class="about-promotions">
                                            <h3>{!! $storethemesetting['inner-list'][0]['field_default_text'] !!} {{ $storethemesetting['inner-list'][1]['field_default_text'] }}</h3>
                                            <p> {{ $storethemesetting['inner-list'][2]['field_default_text'] }}</p>
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
    @if($store->blog_enable == 'on')
        <section class="new-collection-section padding-bottom">
            <div class="container">
                <div class="row collection-row">
                    <div class="col-lg-8 col-12">
                        <div class="new-collection-item">
                            <div class="new-collection-inner">
                                @if($blogs->count()>0)

                                    <div class="collection-img">
                                            <img src="{{$s_logo.($blogs[0]['blog_cover_image'])}}" alt="">
                                    </div>
                                    <div class="collection-desk">
                                        <h2>{{$blogs[0]['title']}}</h2>
                                        <a href="{{route('store.store_blog_view',[$store->slug,$blogs[0]['id']])}}" class="btn btn-white">{{__('Show More')}}</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-12 right-side">
                        <div class="row">
                            <div class="col-lg-12 col-md-6 col-sm-6 col-12">
                                <div class="new-collection-item style-two">
                                    <div class="new-collection-inner">
                                        @if($blogs->count()>1)
                                            <div class="collection-img">
                                                <img src="{{$s_logo.($blogs[1]['blog_cover_image'])}}" alt="">
                                            </div>
                                            <div class="collection-desk">
                                                <h3>{{$blogs[1]['title']}}</h3>
                                                <a href="{{route('store.store_blog_view',[$store->slug,$blogs[1]['id']])}}" class="btn-link">{{__('SHOW MORE')}}</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-6 col-sm-6 col-12">
                                <div class="new-collection-item style-two">
                                    <div class="new-collection-inner">
                                        @if($blogs->count()>2)
                                            <div class="collection-img">
                                                <img src="{{$s_logo.($blogs[2]['blog_cover_image'])}}" alt="">
                                            </div>
                                            <div class="collection-desk">
                                                <h3>{{$blogs[2]['title']}}</h3>
                                                <a href="{{route('store.store_blog_view',[$store->slug,$blogs[2]['id']])}}" class="btn-link">{{__('SHOW MORE')}}</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    @if($theme3_product_random != null && $theme3_product_random->count()>0)
        <section class="your-time-section padding-bottom ">
            <div class="container">
                <div class="row  align-items-center ">
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="rightside-img">
                            @if(!empty($theme3_product_random->is_cover))
                                <img src="{{$productImg.(!empty($theme3_product_random->is_cover)?$theme3_product_random->is_cover:'')}}" title="{{$theme3_product_random->name}}" alt="">
                            @else
                                <img src="{{asset(Storage::url('uploads/is_cover_image/default.jpg'))}}" alt="" title="{{$theme3_product_random->name}}">
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="product-desk">
                            <div class="title-inner">
                                <h2><a href="{{route('store.product.product_view',[$store->slug,$theme3_product_random->id])}}">{{$theme3_product_random->name}}</a></h2>
                            </div>
                            <p>{!! $theme3_product_random->detail !!}</p>
                            @if($theme3_product_random['enable_product_variant'] == 'on')
                                <div class="cart-btn align-items-center">
                                    <a href="{{route('store.product.product_view',[$store->slug,$theme3_product_random->id])}}" class="btn"><i class="fas fa-shopping-basket"></i></a>
                                    @if(Auth::guard('customers')->check())
                                        @if(!empty($wishlist) && isset($wishlist[$theme3_product_random->id]['product_id']))
                                            @if($wishlist[$theme3_product_random->id]['product_id'] != $theme3_product_random->id)
                                                <a class="btn-secondary wish-btn add_to_wishlist wishlist_{{$theme3_product_random->id}}" data-id="{{$theme3_product_random->id}}"><i class="far fa-heart"></i></a>
                                            @else
                                                <a class="btn-secondary wish-btn"><i class="fas fa-heart" data-id="{{$theme3_product_random->id}}"></i></a>
                                            @endif
                                        @else
                                            <a class="btn-secondary wish-btn add_to_wishlist wishlist_{{$theme3_product_random->id}}" data-id="{{$theme3_product_random->id}}"><i class="far fa-heart"></i></a>
                                        @endif
                                    @endif
                                    <div class="price">
                                        <h6>{{__('In variant')}}</h6>
                                    </div>
                                </div>
                            @else
                                <div class="cart-btn align-items-center">
                                    <a class="btn add_to_cart" data-id="{{$theme3_product_random->id}}">{{__('Add to cart')}}<i class="fas fa-shopping-basket"></i></a>
                                    @if(Auth::guard('customers')->check())
                                        @if(!empty($wishlist) && isset($wishlist[$theme3_product_random->id]['product_id']))
                                            @if($wishlist[$theme3_product_random->id]['product_id'] != $theme3_product_random->id)
                                                <a class="btn-secondary wish-btn add_to_wishlist wishlist_{{$theme3_product_random->id}}" data-id="{{$theme3_product_random->id}}"><i class="far fa-heart"></i></a>
                                            @else
                                                <a class="btn-secondary wish-btn"><i class="fas fa-heart" data-id="{{$theme3_product_random->id}}"></i></a>
                                            @endif
                                        @else
                                            <a class="btn-secondary wish-btn add_to_wishlist wishlist_{{$theme3_product_random->id}}" data-id="{{$theme3_product_random->id}}"><i class="far fa-heart"></i></a>
                                        @endif
                                    @endif
                                    <div class="price">
                                        <ins>{{\App\Models\Utility::priceFormat($theme3_product_random->price)}}</ins>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <section class="bestsellers-section  padding-bottom ">
        @if($products['Start shopping']->count() > 0)
            <div class="container">
                <div class="tabs-wrapper">
                    <div class="section-title-bg section-title d-flex align-items-center justify-content-between">
                        <h2>{{__('Products')}}</h2>
                        <ul class="d-flex tabs">
                            @foreach($categories as $key => $category)
                                <li class="tab-link {{ $key == 0 ? 'active' : '' }}" data-tab="tab-{!! preg_replace('/[^A-Za-z0-9\-]/', '_', $category) !!}">
                                    <a> {{__($category)}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="tabs-container">
                        @foreach($products as $key => $items)
                            <div class="tab-content {{($key=='Start shopping')?'active show':''}}" id="tab-{!! preg_replace('/[^A-Za-z0-9\-]/', '_', $key)!!}">
                                <div class="row product-row ">
                                    @if($items->count() > 0)
                                        @foreach($items as $product)
                                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 product-card">
                                                <div class="product-card-inner">
                                                    <div class="product-img">
                                                        <a href="{{route('store.product.product_view',[$store->slug,$product->id])}}">
                                                            @if(!empty($product->is_cover) )
                                                                <img src="{{$productImg.$product->is_cover}}" alt="">
                                                            @else
                                                                <img src="{{asset(Storage::url('uploads/is_cover_image/default.jpg'))}}" alt="">
                                                            @endif
                                                        </a>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="product-rating">
                                                            @if($store->enable_rating == 'on')
                                                                @for($i =1;$i<=5;$i++)
                                                                    @php
                                                                        $icon = 'fa-star';
                                                                        $color = '';
                                                                        $newVal1 = ($i-0.5);
                                                                        if($product->product_rating() < $i && $product->product_rating() >= $newVal1)
                                                                        {
                                                                            $icon = 'fa-star-half-alt';
                                                                        }
                                                                        if($product->product_rating() >= $newVal1)
                                                                        {
                                                                            $color = 'text-warning';
                                                                        }
                                                                    @endphp
                                                                    <i class="star fas {{$icon .' '. $color}}"></i>
                                                                @endfor
                                                            @endif
                                                        </div>
                                                        <h5>
                                                            <a href="{{route('store.product.product_view',[$store->slug,$product->id])}}">{{$product->name}}</a>
                                                        </h5>
                                                        <p>{{__('Category')}}: {{$product->product_category()}}</p>
                                                        <div class="product-content-bottom">
                                                            @if($product['enable_product_variant'] == 'on')
                                                                <div class="price">
                                                                    <ins>{{__('In variant')}}</ins>
                                                                </div>
                                                                <a href="{{route('store.product.product_view',[$store->slug,$product->id])}}" class="btn cart-btn"><i class="fas fa-shopping-basket"></i></a>
                                                                @if(Auth::guard('customers')->check())
                                                                    @if(!empty($wishlist) && isset($wishlist[$product->id]['product_id']))
                                                                        @if($wishlist[$product->id]['product_id'] != $product->id)
                                                                            <a class="btn-secondary wish-btn add_to_wishlist wishlist_{{$product->id}}" data-id="{{$product->id}}"><i class="far fa-heart"></i></a>
                                                                        @else
                                                                            <a class="btn-secondary wish-btn " data-id="{{$product->id}}"><i class="fas fa-heart"></i></a>
                                                                        @endif
                                                                    @else
                                                                        <a class="btn-secondary wish-btn add_to_wishlist wishlist_{{$product->id}}" data-id="{{$product->id}}"><i class="far fa-heart"></i></a>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                <div class="price">
                                                                    <ins>{{\App\Models\Utility::priceFormat($product->price)}}</ins>
                                                                </div>
                                                                <a class="btn cart-btn add_to_cart" data-id="{{$product->id}}">{{__('Add to cart')}}<i class="fas fa-shopping-basket"></i></a>
                                                                @if(Auth::guard('customers')->check())
                                                                    @if(!empty($wishlist) && isset($wishlist[$product->id]['product_id']))
                                                                        @if($wishlist[$product->id]['product_id'] != $product->id)
                                                                            <a class="btn-secondary wish-btn add_to_wishlist wishlist_{{$product->id}}" data-id="{{$product->id}}"><i class="far fa-heart"></i></a>
                                                                        @else
                                                                            <a class="btn-secondary wish-btn"><i class="fas fa-heart" data-id="{{$product->id}}"></i></a>
                                                                        @endif
                                                                    @else
                                                                        <a class="btn-secondary wish-btn add_to_wishlist wishlist_{{$product->id}}" data-id="{{$product->id}}"><i class="far fa-heart"></i></a>
                                                                    @endif
                                                                @endif 
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 product-card">
                                            <h6 class="no_record"><i class="fas fa-ban"></i> {{__('No Record Found')}}</h6>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach            
                    </div>
                </div>
            </div>
        @endif
    </section>
    @foreach ($getStoreThemeSetting as $storethemesetting)
        @if (isset($storethemesetting['section_name']) && $storethemesetting['section_name'] == 'Home-Categories' && $storethemesetting['section_enable'] == 'on' && !empty($pro_categories))
            @php
                // dd($storethemesetting);
                $Titlekey = array_search('Title', array_column($storethemesetting['inner-list'], 'field_name'));
                $Title = $storethemesetting['inner-list'][$Titlekey]['field_default_text'];

                $Description_key = array_search('Description', array_column($storethemesetting['inner-list'], 'field_name'));
                $Description = $storethemesetting['inner-list'][$Description_key]['field_default_text'];
            @endphp
            <section class="category-section padding-bottom">
                <div class="container">
                    <div class="section-title text-center">
                        <h2>{{$Title}}</h2>
                        <p>{{$Description}}</p>
                    </div>
                    <div class="row category-row">
                        @foreach($pro_categories as $key=>$pro_categorie)
                            @if($product_count[$key] > 0)
                                <div class="col-md-6 col-lg-4 col-12 category-card">
                                    <div class="category-card-inner">
                                        <div class="category-card-img">
                                            <a href="{{route('store.categorie.product',[$store->slug,$pro_categorie->name])}}">
                                                @if(!empty($pro_categorie->categorie_img))
                                                    <img src="{{$catimg .(!empty($pro_categorie->categorie_img)?$pro_categorie->categorie_img:'default.jpg')}}" alt="">
                                                @else
                                                    <img src="{{asset(Storage::url('uploads/product_image/default.jpg'))}}" alt="">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="category-content">
                                            <h4>
                                                <a href="#">{{$pro_categorie->name}}</a>
                                            </h4>
                                            <a href="{{route('store.categorie.product',[$store->slug,$pro_categorie->name])}}" class="btn-link">{{__('SHOW MORE')}}</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    @endforeach

    @if (count($topRatedProducts) > 0)
        <section class="total-rated-product padding-bottom">
            <div class="container">
                <div class="section-title d-flex align-items-center justify-content-between">
                    <h2 class="h1">{{ __('Top rated products') }}</h2>
                    <a href="{{ route('store.categorie.product', $store->slug) }}" class="btn cart-btn">{{ __('Show more products') }}</a>
                </div>
                <div class="row">
                    @foreach ($topRatedProducts as $k => $topRatedProduct)
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 product-card">
                            <div class="product-card-inner">
                                <div class="product-img">
                                    <a href="{{ route('store.product.product_view', [$store->slug, $topRatedProduct->product_id]) }}">
                                        @if (!empty($topRatedProduct->product->is_cover))
                                            <img src="{{$productImg . $topRatedProduct->product->is_cover }}">
                                        @else
                                            <img src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}">
                                        @endif
                                    </a>
                                </div>
                                <div class="product-content">
                                    <div class="product-rating">
                                        @if ($store->enable_rating == 'on')
                                            @for ($i = 1; $i <= 5; $i++)
                                                @php
                                                    $icon = 'fa-star';
                                                    $color = '';
                                                    $newVal1 = $i - 0.5;
                                                    if ($topRatedProduct->product->product_rating() < $i && $topRatedProduct->product->product_rating() >= $newVal1) {
                                                        $icon = 'fa-star-half-alt';
                                                    }
                                                    if ($topRatedProduct->product->product_rating() >= $newVal1) {
    
                                                        $color = 'text-warning';
    
                                                    }
                                                @endphp
    
                                            <i class="star fas {{ $icon . ' ' . $color }}"></i>
                                            @endfor
                                        @endif
                                    </div>
                                    <h5>
                                        <a href="{{ route('store.product.product_view', [$store->slug, $topRatedProduct->product_id]) }}">{{ $topRatedProduct->product->name }}</a>
                                    </h5>
                                    <p>{{ __('Category') }}:  {{$topRatedProduct->product->product_category()}}</p>
                                    <div class="product-content-bottom">
                                        <div class="price">
                                            <ins>
                                               
                                                @if ($topRatedProduct->product->enable_product_variant == 'on')
                                                    {{ __('In variant') }}
                                                @else
                                                    {{ \App\Models\Utility::priceFormat($topRatedProduct->product->price) }}
                                                @endif
                                            </ins>
                                        </div>
                                        @if ($topRatedProduct->product->enable_product_variant == 'on')
                                            <a href="{{ route('store.product.product_view', [$store->slug, $topRatedProduct->product->id]) }}" class="btn cart-btn"><i class="fas fa-shopping-basket"></i></a>
                                        @else
                                            <a href="#" class="btn cart-btn add_to_cart" data-id="{{ $topRatedProduct->product->id }}">{{ __('Add to cart') }} <i class="fas fa-shopping-basket"></i></a>
                                        @endif
                                        @if (Auth::guard('customers')->check())
                                            @if (!empty($wishlist) && isset($wishlist[$topRatedProduct->product->id]['product_id']))
                                                @if ($wishlist[$topRatedProduct->product->id]['product_id'] != $topRatedProduct->product->id)
                                                    <a href="#" class="btn wishlist-btn add_to_wishlist wishlist_{{ $topRatedProduct->product->id }}" data-id="{{ $topRatedProduct->product->id }}"><i class="far fa-heart"></i></a>
                                                @else
                                                    <a href="#" class="btn wishlist-btn" data-id="{{ $topRatedProduct->product->id }}" disabled><i class="far fa-heart"></i></a>
                                                @endif
                                            @else
                                                <a href="#" class="btn wishlist-btn add_to_wishlist wishlist_{{ $topRatedProduct->product->id }}" data-id="{{ $topRatedProduct->product->id }}"><i class="far fa-heart"></i></a>
                                            @endif
                                        @else
                                            <a href="#" class="btn wishlist-btn add_to_wishlist wishlist_{{ $topRatedProduct->product->id }}" data-id="{{ $topRatedProduct->product->id }}"><i class="far fa-heart"></i></a>
                                        @endif
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($getStoreThemeSetting[3]['section_enable'] == 'on')
        <section class="testimonial-section padding-bottom">
            <div class="container">
                <div class="testimonial-slider">
                    @foreach ($getStoreThemeSetting as $key => $storethemesetting)
                        @if (isset($storethemesetting['section_name']) && $storethemesetting['section_name'] == 'Home-Testimonial' && $storethemesetting['array_type'] == 'multi-inner-list') 
                            @if (isset($storethemesetting['homepage-testimonial-card-image']) || isset($storethemesetting['homepage-testimonial-card-title']) || isset($storethemesetting['homepage-testimonial-card-sub-text']) || isset($storethemesetting['homepage-testimonial-card-description']) || isset($storethemesetting['homepage-testimonial-card-enable']))
                                @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                    @if($storethemesetting['homepage-testimonial-card-enable'][$i] == 'on')
                                        {{--  <div class="testimonial-slider">  --}}
                                            <div class="testimonial-card">
                                                <div class="testimonial-content">
                                                    <div class="title-inner">
                                                        <div class="subtitle">{{$getStoreThemeSetting[3]['inner-list'][1]['field_default_text']}}</div>
                                                        <h2>{{$getStoreThemeSetting[3]['inner-list'][0]['field_default_text']}}</h2>
                                                    </div>
                                                    <p>{{ $storethemesetting['homepage-testimonial-card-description'][$i] }}</p>
                                                    <div class="abt-user">
                                                        <p>{{ $storethemesetting['homepage-testimonial-card-title'][$i] }}</p>
                                                        <small> {{ $storethemesetting['homepage-testimonial-card-sub-text'][$i] }}</small>
                                                    </div>
                                                </div>
                                                <div class="testimonial-img">
                                                    <img src="{{ $imgpath. (!empty($storethemesetting['homepage-testimonial-card-image'][$i]['field_prev_text']) ? $storethemesetting['homepage-testimonial-card-image'][$i]['field_prev_text'] : 'theme3/header/header_img_3.png') }}" alt="">
                                                </div>
                                            </div>
                                        {{--  </div>  --}}
                                    @endif
                                @endfor
                            @else
                                @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                
                                        <div class="testimonial-card">
                                            <div class="testimonial-content">
                                                <div class="title-inner">
                                                    @for($k=0;$k<=count($getStoreThemeSetting);$k++)
                                                        @if (isset($getStoreThemeSetting[$k]['section_name']) && $getStoreThemeSetting[$k]['section_name'] == 'Home-Testimonial' && $getStoreThemeSetting[$k]['array_type'] == 'inner-list' && $getStoreThemeSetting[$k]['section_enable'] == 'on')
                                                            @php
                                                                $Heading_key = array_search('Main Heading', array_column($getStoreThemeSetting[$k]['inner-list'], 'field_name'));
                                                                $Heading = $getStoreThemeSetting[$k]['inner-list'][$Heading_key]['field_default_text'];

                                                                $HeadingSubText_key = array_search('Main Heading Title', array_column($getStoreThemeSetting[$k]['inner-list'], 'field_name'));
                                                                $HeadingSubText = $getStoreThemeSetting[$k]['inner-list'][$HeadingSubText_key]['field_default_text'];
                                                            @endphp
                                                            <div class="subtitle">{{$HeadingSubText}}</div>
                                                            <h2>{{$Heading}}</h2>
                                                        @endif
                                                    @endfor
                                                </div>
                                                @for($a=0;$a<=count($getStoreThemeSetting);$a++)
                                                    @if (isset($getStoreThemeSetting[$a]['section_name']) && $getStoreThemeSetting[$a]['section_name'] == 'Home-Testimonial' && $getStoreThemeSetting[$a]['array_type'] == 'multi-inner-list')
                                                        <p>{{ $getStoreThemeSetting[$a]['inner-list'][4]['field_default_text'] }}</p>
                                                        <div class="abt-user">
                                                            <p>{{ $getStoreThemeSetting[$a]['inner-list'][2]['field_default_text'] }}</p>
                                                            <small> {{ $getStoreThemeSetting[$a]['inner-list'][3]['field_default_text'] }}</small>
                                                        </div>
                                                    @endif
                                                @endfor
                                            </div>
                                            <div class="testimonial-img">
                                                <img src="{{$imgpath.(!empty($storethemesetting['inner-list'][1]['field_default_text']) ? $storethemesetting['inner-list'][1]['field_default_text'] : 'theme3/header/header_img_3.png')}}" alt="">
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
</div>
@endsection
