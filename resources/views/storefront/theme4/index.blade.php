@extends('storefront.layout.theme4')
@section('page-title')
    {{ __('Home') }}
@endsection
@push('css-page')
    <style>
        .p-tablist .nav-tabs .nav-item .nav-link.active {
            font-weight: bold;
        }

        .cat-box {
            max-height: 284px;
        }
    </style>
@endpush
@php
$imgpath=\App\Models\Utility::get_file('uploads/');
$productImg = \App\Models\Utility::get_file('uploads/is_cover_image/');
$catimg = \App\Models\Utility::get_file('uploads/product_image/');
$default =\App\Models\Utility::get_file('uploads/theme4/header/brand_logo.png');
$s_logo = \App\Models\Utility::get_file('uploads/store_logo/');

@endphp
@section('content')
<div class="wrapper">
    @foreach ($pixelScript as $script)
        <?= $script; ?>
    @endforeach
    @foreach ($getStoreThemeSetting as $ThemeSetting)
        @if (isset($ThemeSetting['section_name']) && $ThemeSetting['section_name'] == 'Home-Header' && $ThemeSetting['section_enable'] == 'on')
            @php
                $homepage_header_title_key = array_search('Title', array_column($ThemeSetting['inner-list'], 'field_name'));
                $homepage_header_title = $ThemeSetting['inner-list'][$homepage_header_title_key]['field_default_text'];

                $homepage_header_Sub_text_key = array_search('Sub text', array_column($ThemeSetting['inner-list'], 'field_name'));
                $homepage_header_Sub_text = $ThemeSetting['inner-list'][$homepage_header_Sub_text_key]['field_default_text'];

                $homepage_header_Button_key = array_search('Button', array_column($ThemeSetting['inner-list'], 'field_name'));
                $homepage_header_Button = $ThemeSetting['inner-list'][$homepage_header_Button_key]['field_default_text'];

                $homepage_header_bckground_Image_key = array_search('Background Image', array_column($ThemeSetting['inner-list'], 'field_name'));
                $homepage_header_bckground_Image = $ThemeSetting['inner-list'][$homepage_header_bckground_Image_key]['field_default_text'];

                if ($ThemeSetting['section_name'] == 'Home-Header' && $ThemeSetting['section_enable'] == 'on') {
                    $storethemesetting['enable_header_img'] = 'on';
                }

            @endphp
            <section class="home-banner-section" style="background-image: url({{ $imgpath . $homepage_header_bckground_Image }});">
                <div class="container">
                    <div class="banner-text">
                        <h2> {{ !empty($homepage_header_title) ? $homepage_header_title : 'Home Accessories' }}
                        </h2>
                        <p> {{ !empty($homepage_header_Sub_text) ? $homepage_header_Sub_text : 'There is only that moment and the incredible certainty that everything under the sun has been written by one hand only.' }}</p>
                        <a href="#" class="cart-btn" id="pro_scroll"> {{ !empty($homepage_header_Button) ? $homepage_header_Button : __('Start shopping') }}
                            <i class="fas fa-shopping-basket"></i>
                        </a>
                    </div>
                </div>
            </section>
        @endif
    @endforeach
    <section class="store-promotions">
        <div class="offset-container offset-left">
            <div class="store-promotions-inner">
                <div class="row align-items-center">
                    @foreach ($getStoreThemeSetting as $key => $storethemesetting)
                        @if (isset($storethemesetting['section_name']) && $storethemesetting['section_name'] == 'Home-Brand-Logo' && $storethemesetting['section_enable'] == 'on')
                            <div class="col-12">
                                <div class="client-logo-slider">
                                    @foreach ($storethemesetting['inner-list'] as $image)
                                        @if (!empty($image['image_path']))
                                            @foreach ($image['image_path'] as $img)
                                                    <div class="client-logo-itm">
                                                        <a href="#">
                                                            <img src="{{ $imgpath . (!empty($img) ? $img : 'storego-image.png') }}">
                                                        </a>
                                                    </div>
                                            @endforeach
                                        @else
                                            <div class="client-logo-itm">
                                                <a href="#">
                                                    <img src="{{ $default }}">
                                                </a>
                                            </div>
                                            <div class="client-logo-itm">
                                                <a href="#">
                                                    <img src="{{ $default }}">
                                                </a>
                                            </div>
                                            <div class="client-logo-itm">
                                                <a href="#">
                                                    <img src="{{ $default }}">
                                                </a>
                                            </div>
                                            <div class="client-logo-itm">
                                                <a href="#">
                                                    <img src="{{ $default }}">
                                                </a>
                                            </div>
                                            <div class="client-logo-itm">
                                                <a href="#">
                                                    <img src="{{ $default }}">
                                                </a>
                                            </div>
                                            <div class="client-logo-itm">
                                                <a href="#">
                                                    <img src="{{ $default }}">
                                                </a>
                                            </div>
                                            <div class="client-logo-itm">
                                                <a href="#">
                                                    <img src="{{ $default }}">
                                                </a>
                                            </div>
                                            @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                    <div class="col-md-4 col-12">
                        @foreach ($getStoreThemeSetting as $key => $storethemesetting)
                            @if ($storethemesetting['section_name'] == 'Home-Promotions' && $storethemesetting['section_enable'] == 'on')
                                @if (isset($storethemesetting['homepage-promotions-font-icon']) || isset($storethemesetting['homepage-promotions-title']) || isset($storethemesetting['homepage-promotions-description']))
                                    @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                        <div class="store-promotions-box">
                                            {!! $storethemesetting['homepage-promotions-font-icon'][$i] !!}
                                            <div class="store-text">
                                                <h4> {{ $storethemesetting['homepage-promotions-title'][$i] }}</h4>
                                                <p> {{ $storethemesetting['homepage-promotions-description'][$i] }}</p>
                                            </div>
                                        </div>
                                        @if ($i == 2)
                                            <a href="{{ route('store.categorie.product', [$store->slug, 'Start shopping']) }}" class="cart-btn">{{ __('Show more products') }}
                                                <i class="fas fa-shopping-basket"></i>
                                            </a>
                                        @endif
                                    @endfor
                                @else   
                                    @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                        <div class="store-promotions-box">
                                            {!! $storethemesetting['inner-list'][0]['field_default_text'] !!}
                                            <div class="store-text">
                                                <h4> {{ $storethemesetting['inner-list'][1]['field_default_text'] }}</h4>
                                                <p>  {{ $storethemesetting['inner-list'][2]['field_default_text'] }}</p>
                                            </div>
                                        </div>
                                        @if ($i == 2)
                                            <a href="{{ route('store.categorie.product', [$store->slug, 'Start shopping']) }}" class="cart-btn">{{ __('Show more products') }}
                                                <i class="fas fa-shopping-basket"></i>
                                            </a>
                                        @endif
                                    @endfor
                                @endif
                            @endif
                        @endforeach
                        
                    </div>
                    @foreach ($getStoreThemeSetting as $storethemesetting)
                        @if ($storethemesetting['section_name'] == 'Banner-Image')
                            @if ($storethemesetting['section_enable'] == 'on')
                                <div class="col-md-8 col-12">
                                    <div class="store-img">
                                        <img src="{{ $imgpath. (!empty($storethemesetting['inner-list'][0]['field_default_text']) ? $storethemesetting['inner-list'][0]['field_default_text'] : 'banner_image.png') }}">
                                    </div>
                                </div>
                            @else   
                                <div class="col-md-8 col-12">
                                    
                                </div>
                            @endif
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @if ($products['Start shopping']->count() > 0)
        <section class="bestseller-section tabs-wrapper padding-top padding-bottom" id="pro_items">
            <div class="container">
                <div class="bestseller-title">
                    <div class="section-title">
                        <h2>{{__('Products')}}</h2>
                        <div class="tab-bar">
                            <ul class="cat-tab tabs">
                                @foreach($categories as $key => $category)
                                    <li class="tab-link {{ $key == 0 ? 'active' : '' }}" data-tab="tab-{!! preg_replace('/[^A-Za-z0-9\-]/', '_', $category) !!}">
                                        <a> {{__($category)}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        </div>    
                        <a href="{{ route('store.categorie.product', [$store->slug, 'Start shopping']) }}" class="cart-btn">{{ __('Show more products') }}
                            <i class="fas fa-shopping-basket"></i>
                        </a>                
                </div>
                <div class="tabs-container">
                    @foreach ($products as $key => $items)
                        <div id="tab-{!! preg_replace('/[^A-Za-z0-9\-]/', '_', $key)!!}" class="tab-content {{($key=='Start shopping')?'active':''}}">
                            <div class="row product-row">
                                @if($items->count() > 0)
                                    @foreach($items as $product)
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                            <div class="product-card">
                                                <div class="card-img">
                                                    <a href="{{route('store.product.product_view',[$store->slug,$product->id])}}">
                                                        @if(!empty($product->is_cover) )
                                                            <img src="{{$productImg.$product->is_cover}}" alt="">
                                                        @else
                                                            <img src="{{asset(Storage::url('uploads/is_cover_image/default.jpg'))}}" alt="">
                                                        @endif
                                                    </a>
                                                    {{--  <div class="heart-icon">  --}}
                                                        @if (Auth::guard('customers')->check())
                                                            @if (!empty($wishlist) && isset($wishlist[$product->id]['product_id']))
                                                                @if ($wishlist[$product->id]['product_id'] != $product->id)
                                                                    <a
                                                                        class="heart-icon action-item wishlist-icon bg-light-gray add_to_wishlist wishlist_{{ $product->id }}"
                                                                        data-id="{{ $product->id }}">
                                                                        <i class="far fa-heart"></i>
                                                                    </a>
                                                                @else
                                                                    <a class="heart-icon action-item wishlist-icon bg-light-gray"
                                                                        data-id="{{ $product->id }}" disabled>
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
                                                    {{--  </div>  --}}
                                                </div>
                                                <div class="card-content">
                                                    <h6>
                                                        <a href="{{route('store.product.product_view',[$store->slug,$product->id])}}">{{$product->name}}</a>
                                                    </h6>
                                                <p>{{__('Category')}}: {{$product->product_category()}}
                                                    </p>
                                                    <div class="rating">
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
                                                    <div class="last-btn">
                                                        <div class="price">
                                                            <ins>
                                                                @if ($product->enable_product_variant == 'on')
                                                                    {{ __('In variant') }}
                                                                @else
                                                                    {{ \App\Models\Utility::priceFormat($product->price) }}
                                                                @endif
                                                            </ins>
                                                        </div>
                                                        @if ($product->enable_product_variant == 'on')
                                                            <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}" class="cart-btn"> <i class="fas fa-shopping-basket"></i>{{ __('Add To Cart') }}</a>
                                                        @else
                                                        <a data-id="{{ $product->id }}" class="cart-btn add_to_cart"> <i class="fas fa-shopping-basket"></i>{{ __('Add To Cart') }}</a>
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
        </section>
    @endif
    @foreach ($getStoreThemeSetting as $storethemesetting)
        @if (isset($storethemesetting['section_name']) && $storethemesetting['section_name'] == 'Home-Categories' && $storethemesetting['section_enable'] == 'on' && !empty($pro_categories))
            @php
                // dd($storethemesetting);
                $Titlekey = array_search('Title', array_column($storethemesetting['inner-list'], 'field_name'));
                $Title = $storethemesetting['inner-list'][$Titlekey]['field_default_text'];

                $Description_key = array_search('Description', array_column($storethemesetting['inner-list'], 'field_name'));
                $Description = $storethemesetting['inner-list'][$Description_key]['field_default_text'];
            @endphp
            <section class="category-section padding-bottom padding-top">
                <div class="container">
                    <div class="bestseller-title">
                        <div class="section-title">
                            <h2> {{ !empty($Title) ? $Title : 'Categories' }}</h2>
                            <p> {{ !empty($Description) ? $Description : 'There is only that moment and the incredible certainty <br> that everything under the sun has been written by one hand only.' }}</p>
                            </div>    
                            <a href="{{ route('store.categorie.product', [$store->slug, 'Start shopping']) }}" class="cart-btn">{{ __('Show more products') }}
                                <i class="fas fa-shopping-basket"></i>
                            </a>                
                    </div>
                    <div class="row product-row">
                        @foreach ($pro_categories as $key => $pro_categorie)
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="category-box">
                                    <div class="category-card-inner">
                                        <div class="category-content-top">
                                            <h3>
                                                <a>{{ $pro_categorie->name }}</a>
                                            </h3>
                                            <div class="category-card-img">
                                                @if (!empty($pro_categorie->categorie_img))
                                                    <a href="#">
                                                        <img src="{{ $catimg . (!empty($pro_categorie->categorie_img) ? $pro_categorie->categorie_img : 'default.jpg') }}" alt="">
                                                    </a>
                                                @else
                                                    <a href="#">
                                                        <img src="{{ asset(Storage::url('uploads/product_image/default.jpg')) }}" alt="">
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="category-content-bottom">
                                            <p>{{ __('Products') }}: {{ !empty($product_count[$key]) ? $product_count[$key] : '0' }}</p>
                                            <a href="{{ route('store.categorie.product', [$store->slug, $pro_categorie->name]) }}" class="cart-btn">{{ __('Show more products') }}
                                                <i class="fas fa-shopping-basket"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    @endforeach
    @if($getStoreThemeSetting[2]['section_enable'] == 'on')
        @foreach ($getStoreThemeSetting as $storethemesetting)
            @if (isset($storethemesetting['section_name']) && $storethemesetting['section_name'] == 'Home-Email-Subscriber' && $storethemesetting['section_enable'] == 'on')
                @php
                $emailsubs_img_key = array_search('Subscriber Background Image', array_column($storethemesetting['inner-list'], 'field_name'));
                $emailsubs_img = $storethemesetting['inner-list'][$emailsubs_img_key]['field_default_text'];

                $SubscriberTitle_key = array_search('Subscriber Title', array_column($storethemesetting['inner-list'], 'field_name'));
                $SubscriberTitle = $storethemesetting['inner-list'][$SubscriberTitle_key]['field_default_text'];

                $SubscriberDescription_key = array_search('Subscriber Description', array_column($storethemesetting['inner-list'], 'field_name'));
                $SubscriberDescription = $storethemesetting['inner-list'][$SubscriberDescription_key]['field_default_text'];

                $SubscribeButton_key = array_search('Subscribe Button Text', array_column($storethemesetting['inner-list'], 'field_name'));
                $SubscribeButton = $storethemesetting['inner-list'][$SubscribeButton_key]['field_default_text'];
                @endphp
                <section class="subcribe-section" style="background-image: url({{ $imgpath  . $emailsubs_img }});">
                    <div class="container">
                        <div class="subcribe-inner">
                            <h2>{{ !empty($SubscriberTitle) ? $SubscriberTitle : 'Always on time' }}</h2>
                            <p>{{ !empty($SubscriberDescription) ? $SubscriberDescription : 'Subscription here' }}</p>
                            {{ Form::open(['route' => ['subscriptions.store_email', $store->id], 'method' => 'POST']) }}
                            <div class="input-box">
                                {{ Form::email('email', null, ['placeholder' => __('TYPE YOUR EMAIL ADDRESS...')]) }}
                                <button type="submit"> <span class="btn-inner--text">{{ $SubscribeButton }}</span> <i class="fas fa-paper-plane"></i></button>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </section>
            @endif
        @endforeach
    @endif
    
    @if (count($topRatedProducts) > 0)
        <section class="total-rated-product padding-top padding-bottom">
            <div class="container">
                <div class="section-title d-flex align-items-center justify-content-between">
                    <h2 style=" margin-bottom: 10px; ">{{ __('Top rated products') }}</h2>
                    <a href="{{ route('store.categorie.product', $store->slug) }}" class="cart-btn">{{ __('Show more products') }}</a>
                </div>
                <div class="row  product-row">
                    @foreach ($topRatedProducts as $k => $topRatedProduct)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="product-card">
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
                                                    class="heart-icon action-item wishlist-icon bg-light-gray add_to_wishlist wishlist_{{ $topRatedProduct->product->id }}"
                                                    data-id="{{ $topRatedProduct->product->id }}">
                                                    <i class="far fa-heart"></i>
                                                </a>
                                            @else
                                                <a class="heart-icon action-item wishlist-icon bg-light-gray"
                                                    data-id="{{ $topRatedProduct->product->id }}" disabled>
                                                    <i class="fas fa-heart"></i>
                                                </a>
                                            @endif
                                        @else
                                            <a
                                                class="heart-icon action-item wishlist-icon bg-light-gray add_to_wishlist wishlist_{{ $topRatedProduct->product->id }}"
                                                data-id="{{ $topRatedProduct->product->id }}">
                                                <i class="far fa-heart"></i>
                                            </a>
                                        @endif
                                    @else
                                        <a
                                            class="heart-icon action-item wishlist-icon bg-light-gray add_to_wishlist wishlist_{{ $topRatedProduct->product->id }}"
                                            data-id="{{ $topRatedProduct->product->id }}">
                                            <i class="far fa-heart"></i>
                                        </a>
                                    @endif
                                </div>
                                <div class="card-content">
                                    <h6>
                                        <a href="{{ route('store.product.product_view', [$store->slug, $topRatedProduct->product_id]) }}">{{ $topRatedProduct->product->name }}</a>
                                    </h6>
                                <p>{{ __('Category') }}:  {{$topRatedProduct->product->product_category()}}
                                    </p>
                                    <div class="rating">
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
                                    <div class="last-btn">
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
                                            <a href="{{ route('store.product.product_view', [$store->slug, $topRatedProduct->product->id]) }}" class="cart-btn"><i class="fas fa-shopping-basket"></i></a>
                                        @else
                                            <a data-id="{{ $topRatedProduct->product->id }}" class="cart-btn add_to_cart">{{ __('Add to cart') }}  <i class="fas fa-shopping-basket"></i></a>
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
    @if($getStoreThemeSetting[5]['section_enable'] == 'on')
        <section class="testimonial-section padding-top padding-bottom">
            <div class="container">
                <div class="testimonial-title">
                    @foreach ($getStoreThemeSetting as $storethemesetting)
                        @if (isset($storethemesetting['section_name']) && $storethemesetting['section_name'] == 'Home-Testimonial' && $storethemesetting['array_type'] == 'inner-list' && $storethemesetting['section_enable'] == 'on')
                            @php
                                $Heading_key = array_search('Heading', array_column($storethemesetting['inner-list'], 'field_name'));
                                $Heading = $storethemesetting['inner-list'][$Heading_key]['field_default_text'];

                                $HeadingSubText_key = array_search('Heading Sub Text', array_column($storethemesetting['inner-list'], 'field_name'));
                                $HeadingSubText = $storethemesetting['inner-list'][$HeadingSubText_key]['field_default_text'];
                            @endphp
                            <h3 class="h1"> {{ !empty($storethemesetting['testimonial_main_heading']) ? $storethemesetting['testimonial_main_heading'] : 'Testimonials' }}</h3>
                            <p>{{ !empty($storethemesetting['testimonial_main_heading_title'])
                                ? $storethemesetting['testimonial_main_heading_title']
                                : 'There is only that moment and the incredible certainty that everything under the sun has been written by one hand only.' }}</p>
                        @endif
                    @endforeach
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
                                                    <img src="{{ $imgpath. (!empty($storethemesetting['homepage-testimonial-card-image'][$i]['field_prev_text']) ? $storethemesetting['homepage-testimonial-card-image'][$i]['field_prev_text'] : 'qo.png') }}">

                                                    <div class="review-inner">
                                                        <h5> {{ $storethemesetting['homepage-testimonial-card-title'][$i] }}</h5>
                                                        <h6>{{ $storethemesetting['homepage-testimonial-card-sub-text'][$i] }}</h6>
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
                                                {{ $storethemesetting['inner-list'][4]['field_default_text'] }}
                                            </p>
                                            <div class="review-box">
                                                <img src="{{ $imgpath. (!empty($storethemesetting['inner-list'][1]['field_default_text']) ? $storethemesetting['inner-list'][1]['field_default_text'] : '') }}">
                
                                                <div class="review-inner">
                                                    <h5> {{ $storethemesetting['inner-list'][2]['field_default_text'] }}</h5>
                                                    <h6>{{ $storethemesetting['inner-list'][3]['field_default_text'] }}</h6>
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
 </div>
@endsection
@push('script-page')
    <script>
        $(".add_to_cart").click(function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var variants = [];
            $(".variant-selection").each(function(index, element) {
                variants.push(element.value);
            });

            if (jQuery.inArray('', variants) != -1) {
                show_toastr('Error', "{{ __('Please select all option.') }}", 'error');
                return false;
            }
            var variation_ids = $('#variant_id').val();

            $.ajax({
                url: '{{ route('user.addToCart', ['__product_id', $store->slug, 'variation_id']) }}'.replace(
                    '__product_id', id).replace('variation_id', variation_ids ?? 0),
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    variants: variants.join(' : '),
                },
                success: function(response) {
                    if (response.status == "Success") {
                        show_toastr('Success', response.success, 'success');
                        $("#shoping_counts").html(response.item_count);
                    } else {
                        show_toastr('Error', response.error, 'error');
                    }
                },
                error: function(result) {
                    console.log('error');
                }
            });
        });

        $(document).on('click', '.add_to_wishlist', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            $.ajax({
                type: "POST",
                url: '{{ route('store.addtowishlist', [$store->slug, '__product_id']) }}'.replace(
                    '__product_id', id),
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    if (response.status == "Success") {
                        show_toastr('Success', response.message, 'success');
                        $('.wishlist_' + response.id).removeClass('add_to_wishlist');
                        $('.wishlist_' + response.id).html('<i class="fas fa-heart"></i>');
                        $('.wishlist_count').html(response.count);
                    } else {
                        show_toastr('Error', response.error, 'error');
                    }
                },
                error: function(result) {}
            });
        });

        $(".productTab").click(function(e) {
            e.preventDefault();
            $('.productTab').removeClass('active')

        });

        $("#pro_scroll").click(function() {
            $('html, body').animate({
                scrollTop: $("#pro_items").offset().top
            }, 100);
        });
    </script>
@endpush
