@extends('storefront.layout.theme8')

@section('page-title')
    {{ __('Home') }}
@endsection

@push('css-page')
@endpush
@php
    $imgpath = \App\Models\Utility::get_file('uploads/');
    $productImg = \App\Models\Utility::get_file('uploads/is_cover_image/');
    $catimg = \App\Models\Utility::get_file('uploads/product_image/');
    $default = \App\Models\Utility::get_file('uploads/theme7/header/img01.jpg');
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
                $homepage_header_img_key = array_search('Header Image', array_column($ThemeSetting['inner-list'], 'field_name'));
                $homepage_header_img = $ThemeSetting['inner-list'][$homepage_header_img_key]['field_default_text'];

                $homepage_header_Sub_img_key = array_search('Header Tag', array_column($ThemeSetting['inner-list'], 'field_name'));
                $homepage_header_Sub_img = $ThemeSetting['inner-list'][$homepage_header_Sub_img_key]['field_default_text'];

                $homepage_header_title_key = array_search('Title', array_column($ThemeSetting['inner-list'], 'field_name'));
                $homepage_header_title = $ThemeSetting['inner-list'][$homepage_header_title_key]['field_default_text'];

                $homepage_header_subtxt_key = array_search('Sub text', array_column($ThemeSetting['inner-list'], 'field_name'));
                $homepage_header_subtxt = $ThemeSetting['inner-list'][$homepage_header_subtxt_key]['field_default_text'];

                $homepage_header_btn_key = array_search('Button', array_column($ThemeSetting['inner-list'], 'field_name'));
                $homepage_header_btn = $ThemeSetting['inner-list'][$homepage_header_btn_key]['field_default_text'];
            @endphp
            <section class="main-home-first-section padding-top">
                <div class="home-banner-img">
                    <img src="{{ $imgpath . $homepage_header_img }}" alt="">
                </div>
                <div class="container">
                    <div class="banner-content">
                        <div class="banner-logo">
                            <img src="{{ $imgpath . $homepage_header_Sub_img }}" alt="">
                        </div>
                        <h1>  {{ $homepage_header_title }}</h1>
                        <p>{{ $homepage_header_subtxt }}</p>
                        <a href="{{ route('store.categorie.product', [$store->slug, 'Start shopping']) }}" class="btn-secondary"> {{ $homepage_header_btn }}</a>
                    </div>
                </div>
            </section>
        @endif
    @endforeach
    @if ($getStoreThemeSetting[1]['section_enable'] == 'on')
        <section class="store-promotions-section">
            <div class="container">
                <div class="promotions-card">
                    <div class="row row-gap ">
                        @foreach ($getStoreThemeSetting as $key => $storethemesetting)
                            @if ($storethemesetting['section_name'] == 'Home-Promotions' && $storethemesetting['array_type'] == 'multi-inner-list')
                                @if (isset($storethemesetting['homepage-promotions-font-icon']) || isset($storethemesetting['homepage-promotions-title']) || isset($storethemesetting['homepage-promotions-description']))
                                    @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                        <div class="col-md-4 col-12">
                                            <div class="about-promotions">
                                                <div class="promotions-icon">
                                                    {!! $storethemesetting['homepage-promotions-font-icon'][$i] !!}
                                                </div>
                                                <div class="promotions-content">
                                                    <h3> {{ $storethemesetting['homepage-promotions-title'][$i] }}</h3>
                                                    <p> {{ $storethemesetting['homepage-promotions-description'][$i] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                @else
                                    @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                        <div class="col-md-4 col-12">
                                            <div class="about-promotions">
                                                <div class="promotions-icon">
                                                    {!! $storethemesetting['inner-list'][0]['field_default_text'] !!}
                                                </div>
                                                <div class="promotions-content">
                                                    <h3> {{ $storethemesetting['inner-list'][1]['field_default_text'] }}</h3>
                                                    <p>{{ $storethemesetting['inner-list'][2]['field_default_text'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
    @if (count($topRatedProducts) > 0)
        <section class="top-products-section padding-bottom padding-top">
            <div class="container">
                <div class="section-title d-flex align-items-center justify-content-between">
                    <h2>{{ __('TOP PRODUCTS') }}</h2>
                    <a href="{{ route('store.categorie.product', [$store->slug, 'Start shopping']) }}" class="btn">{{ __('GO TO SHOP') }}</a>
                </div>
                <div class="row product-row">
                    @foreach ($topRatedProducts as $k => $topRatedProduct)
                        <div class="col-lg-3 col-sm-6 col-md-4 col-12 product-card">
                            <div class="product-card-inner">
                                <div class="product-content-top">
                                    <h6>
                                        <a href="{{ route('store.product.product_view', [$store->slug, $topRatedProduct->product->id]) }}">{{ $topRatedProduct->product->name }}</a>
                                    </h6>
                                    <p> {{ $topRatedProduct->product->product_category() }}</p>
                                </div>
                                <div class="product-img">
                                    <a href="{{ route('store.product.product_view', [$store->slug, $topRatedProduct->product->id]) }}">
                                        @if (!empty($topRatedProduct->product->is_cover))
                                            <img src="{{ $productImg . $topRatedProduct->product->is_cover }}" alt="">
                                        @else
                                            <img src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}" alt="">
                                        @endif
                                    </a>
                                </div>
                                <div class="card-body">
                                    @if ($topRatedProduct->product->enable_product_variant == 'on')
                                        <input type="hidden" id="product_id" class="product_id"
                                            value="{{ $topRatedProduct->product->id }}">
                                        <input type="hidden" id="variant_id" value="">
                                        <input type="hidden" id="variant_qty" value="">


                                        @php $json_variant = json_decode($topRatedProduct->product->variants_json); @endphp

                                        @foreach ($json_variant as $key => $json)

                                            @php $variant_name =  $json->variant_name; @endphp

                                        @endforeach


                                        @foreach ($json_variant as $key => $variant)
                                        {{-- @DD() --}}

                                            <div class="dropdown w-100 ">
                                                <span class="d-none">{{ $variant->variant_name}}:</span>
                                                <select name="product[{{ $key }}]"
                                                    id="pro_variants_name{{ $key }}"
                                                    class="btn btn-outline-primary d-flex font-size-12 font-weight-400 justify-content-between px-3 rounded-pill w-100 variant-selection  pro_variants_name{{ $key }} pro_variants_name variant_loop variant_val">
                                                    {{-- <option value="select">{{ __('Select') }}</option> --}}
                                                    @foreach ($variant->variant_options as $key => $values)
                                                        <option value="{{ $values }}"
                                                            id="{{ $values }}_varient_option">
                                                            {{ $values }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="product-content">
                                    <div class="wish-price">
                                        <div class="price {{ $topRatedProduct->product->enable_product_variant == 'on' ? 'variation_price' .$topRatedProduct->product->id : '' }}">
                                            <ins>
                                                @if ($topRatedProduct->product->enable_product_variant == 'on')
                                                    {{ __('In variant') }}
                                                @else
                                                    {{ \App\Models\Utility::priceFormat($topRatedProduct->product->price) }}
                                                @endif
                                            </ins>
                                        </div>
                                        @if (Auth::guard('customers')->check())
                                            @if (!empty($wishlist) && isset($wishlist[$topRatedProduct->product->id]['product_id']))
                                                @if ($wishlist[$topRatedProduct->product->id]['product_id'] != $topRatedProduct->product->id)
                                                    <a href="#" class="wishlist-btn add_to_wishlist wishlist_{{ $topRatedProduct->product->id }}" data-id="{{ $topRatedProduct->product->id }}"><i class="far fa-heart"></i></a>
                                                @else
                                                    <a href="#" class="wishlist-btn" data-id="{{ $topRatedProduct->product->id }}"><i class="fas fa-heart"></i></a>
                                                @endif
                                            @else
                                                <a href="#" class="wishlist-btn add_to_wishlist wishlist_{{ $topRatedProduct->product->id }}" data-id="{{ $topRatedProduct->product->id }}"><i class="far fa-heart"></i></a>
                                            @endif
                                        @else
                                            <a href="#" class="wishlist-btn add_to_wishlist wishlist_{{ $topRatedProduct->product->id }}" data-id="{{ $topRatedProduct->product->id }}"><i class="far fa-heart"></i></a>
                                        @endif

                                    </div>
                                    @if ($topRatedProduct->product->enable_product_variant == 'on')
                                        <a href="{{ route('store.product.product_view', [$store->slug, $topRatedProduct->product->id]) }}" class="cart-btn btn add_to_cart" data-id="{{ $topRatedProduct->product->id }}"> {{ __('ADD TO CART') }}</a>
                                    @else
                                        <a href="#" class="cart-btn btn add_to_cart" data-id="{{ $topRatedProduct->product->id }}"> {{ __('ADD TO CART') }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    @foreach ($getStoreThemeSetting as $ThemeSetting)
        @if (isset($ThemeSetting['section_name']) && $ThemeSetting['section_name'] == 'Top-Purchased' && $ThemeSetting['section_enable'] == 'on')
            @php
                $homepage_header_img_key = array_search('Background Image', array_column($ThemeSetting['inner-list'], 'field_name'));
                $homepage_header_img = $ThemeSetting['inner-list'][$homepage_header_img_key]['field_default_text'];

                $homepage_header_title_key = array_search('Title', array_column($ThemeSetting['inner-list'], 'field_name'));
                $homepage_header_title = $ThemeSetting['inner-list'][$homepage_header_title_key]['field_default_text'];

                $homepage_header_subtext_key = array_search('Sub text', array_column($ThemeSetting['inner-list'], 'field_name'));
                $homepage_header_subtext = $ThemeSetting['inner-list'][$homepage_header_subtext_key]['field_default_text'];

                $homepage_header_btn_key = array_search('Button Text', array_column($ThemeSetting['inner-list'], 'field_name'));
                $homepage_header_btn = $ThemeSetting['inner-list'][$homepage_header_btn_key]['field_default_text'];
            @endphp
            <section class="most-purchased padding-bottom padding-top">
                <div class="container">
                    <div class="row row-gap align-items-center">
                        <div class="col-md-6 col-12">
                            <div class="section-desk">
                                <div class="store-logo">
                                    <img src="{{ $imgpath . $homepage_header_img }}" alt="">
                                </div>
                                <div class="section-title">
                                    <h2>{{ $homepage_header_title }}</h2>
                                </div>
                                <p>{{ $homepage_header_subtext }}</p>
                                <a href="{{ route('store.categorie.product', [$store->slug, 'Start shopping']) }}" class="btn"> {{ $homepage_header_btn }}</a>
                            </div>
                        </div>
                        @if(!empty($mostPurchasedDetail))
                        <div class="col-md-6 col-12" data-id="{{ $mostPurchasedDetail->id }}">
                            <div class="product-image">
                                @if (!empty($mostPurchasedDetail->is_cover) )
                                    <img src="{{ $productImg . $mostPurchasedDetail->is_cover }}" alt="">
                                @else
                                    <img src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}" alt="">
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif
    @endforeach
    <section class="exclusive-section padding-top padding-bottom">
        <div class="container">
            @foreach ($getStoreThemeSetting as $ThemeSetting)
                @if (isset($ThemeSetting['section_name']) && $ThemeSetting['section_name'] == 'Product-Section-Header' && $ThemeSetting['section_enable'] == 'on')
                    @php
                        $homepage_header_img_key = array_search('Product Header Image', array_column($ThemeSetting['inner-list'], 'field_name'));
                        $homepage_header_img = $ThemeSetting['inner-list'][$homepage_header_img_key]['field_default_text'];

                        $homepage_header_tagImg_key = array_search('Product Header Tag', array_column($ThemeSetting['inner-list'], 'field_name'));
                        $homepage_header_tagImg = $ThemeSetting['inner-list'][$homepage_header_tagImg_key]['field_default_text'];

                        $homepage_header_title_key = array_search('Title', array_column($ThemeSetting['inner-list'], 'field_name'));
                        $homepage_header_title = $ThemeSetting['inner-list'][$homepage_header_title_key]['field_default_text'];

                        $homepage_header_subTxt_key = array_search('Sub text', array_column($ThemeSetting['inner-list'], 'field_name'));
                        $homepage_header_subTxt = $ThemeSetting['inner-list'][$homepage_header_subTxt_key]['field_default_text'];

                        $homepage_header_btn_key = array_search('Button', array_column($ThemeSetting['inner-list'], 'field_name'));
                        $homepage_header_btn = $ThemeSetting['inner-list'][$homepage_header_btn_key]['field_default_text'];
                    @endphp
                    <div class="row row-gap align-items-center">
                        <div class="col-md-6 col-12">
                            <div class="section-desk">
                                <div class="store-logo">
                                    <img src="{{ $imgpath . $homepage_header_tagImg }}" alt="">
                                </div>
                                <div class="section-title">
                                    <h2> {{ $homepage_header_title }}</h2>
                                </div>
                                <p>{{ $homepage_header_subTxt }}</p>
                                <a href="{{ route('store.categorie.product', [$store->slug, 'Start shopping']) }}" class="btn btn-white"> {{ $homepage_header_btn }}</a>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="product-image">
                                <img src="{{ $imgpath . $homepage_header_img }}" alt="">
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
            <div class="tabs-wrapper padding-top">
                <div class="tab-nav">
                    <ul class="d-flex tabs">
                        @foreach ($categories as $key => $category)
                            <li class="tab-link {{ $key == 0 ? 'active' : '' }}" data-tab="tab-{!! preg_replace('/[^A-Za-z0-9\-]/', '_', $category) !!}">
                                <a href="javascript:;">{{__($category)}}</a>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('store.categorie.product', [$store->slug, 'Start shopping']) }}" class="btn btn-white"> {{ __('SHOW MORE') }}</a>
                </div>
                <div class="tabs-container">
                    @foreach($products as $key => $items)
                        <div class="tab-content {{($key=='Start shopping')?'active':''}}" id="tab-{!! preg_replace('/[^A-Za-z0-9\-]/', '_', $key)!!}">
                            <div class="row product-row">
                                @foreach ($items as $key => $product)
                                    @if ($key < 4)
                                        <div class="col-lg-3 col-md-4 col-12 col-sm-6 product-card">
                                            <div class="product-card-inner">
                                                <div class="product-content-top">
                                                    <h6>
                                                        <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}">{{ $product->name }}</a>
                                                    </h6>
                                                    <p>{{ $product->product_category() }}</p>
                                                </div>
                                                <div class="product-img">
                                                    <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}">
                                                        @if (!empty($product->is_cover) )
                                                            <img src="{{ $productImg . $product->is_cover }}" alt="">
                                                        @else
                                                            <img src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}" alt="">
                                                        @endif
                                                    </a>
                                                </div>
                                                <div class="product-content">
                                                    <div class="card-body">
                                                        @if ($product->enable_product_variant == 'on')
                                                            <input type="hidden" id="product_id" value="{{ $product->id }}" class="product_id">
                                                            <input type="hidden" id="variant_id" value="">
                                                            <input type="hidden" id="variant_qty" value="">


                                                            @php $json_variant = json_decode($product->variants_json); @endphp
                                                            @foreach ($json_variant as $key => $json)
                                                                @php $variant_name = $json->variant_name; @endphp
                                                            @endforeach

                                                            @foreach ($json_variant as $key => $variant)
                                                                <span class="d-block font-size-12 variant_name">
                                                                    {{ $variant->variant_name }} :
                                                                </span>
                                                                <div class="dropdown w-100 ">
                                                                    <select name="product[{{ $key }}]"
                                                                        id="pro_variants_name{{ $key }}"
                                                                        class="btn btn-outline-white d-flex font-size-12 font-weight-400 justify-content-between px-3 rounded-pill w-100 variant-selection  pro_variants_name{{ $key }} pro_variants_name variant_loop variant_val">
                                                                        @foreach ($variant->variant_options as $key => $values)
                                                                            <option value="{{ $values }}"
                                                                                id="{{ $values }}_varient_option">
                                                                                {{ $values }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                                                
                                                    <div class="wish-price">
                                                        <div class="price card-price {{ $product->enable_product_variant == 'on' ? 'variation_price' . $product->id : '' }}">

                                                                @if ($product->enable_product_variant == 'on')
                                                                    {{ __('In variant') }}
                                                                @else
                                                                    {{ \App\Models\Utility::priceFormat($product->price) }}
                                                                @endif

                                                        </div>
                                                        @if (Auth::guard('customers')->check())
                                                            @if (!empty($wishlist) && isset($wishlist[$product->id]['product_id']))
                                                                @if ($wishlist[$product->id]['product_id'] != $product->id)
                                                                    <a href="#" class="wishlist-btn add_to_wishlist wishlist_{{ $product->id }}" data-id="{{ $product->id }}"><i class="far fa-heart"></i></a>
                                                                @else
                                                                    <a href="#" class="wishlist-btn" data-id="{{ $product->id }}"><i class="fas fa-heart"></i></a>
                                                                @endif
                                                            @else
                                                                <a href="#" class="wishlist-btn add_to_wishlist wishlist_{{ $product->id }}" data-id="{{ $product->id }}"><i class="far fa-heart"></i></a>
                                                            @endif
                                                        @else
                                                            <a href="#" class="wishlist-btn add_to_wishlist wishlist_{{ $product->id }}" data-id="{{ $product->id }}"><i class="far fa-heart"></i></a>
                                                        @endif

                                                    </div>
                                                    <span class=" mb-0 text-danger product-price-error"></span>
                                                    @if ($product->enable_product_variant == 'on')
                                                        <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}" class="cart-btn btn add_to_cart" data-id="{{ $product->id }}"> {{ __('ADD TO CART') }}</a>
                                                    @else
                                                        <a href="#" class="cart-btn btn add_to_cart" data-id="{{ $product->id }}"> {{ __('ADD TO CART') }}</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @foreach ($getStoreThemeSetting as $ThemeSetting)
        @if (isset($ThemeSetting['section_name']) && $ThemeSetting['section_name'] == 'Latest Product' && $ThemeSetting['section_enable'] == 'on')
            @php
                $homepage_header_tagImg_key = array_search('Tag Image', array_column($ThemeSetting['inner-list'], 'field_name'));
                $homepage_header_tagImg = $ThemeSetting['inner-list'][$homepage_header_tagImg_key]['field_default_text'];

                $homepage_header_title_key = array_search('Title', array_column($ThemeSetting['inner-list'], 'field_name'));
                $homepage_header_title = $ThemeSetting['inner-list'][$homepage_header_title_key]['field_default_text'];

                $homepage_header_subTxt_key = array_search('Sub text', array_column($ThemeSetting['inner-list'], 'field_name'));
                $homepage_header_subTxt = $ThemeSetting['inner-list'][$homepage_header_subTxt_key]['field_default_text'];
            @endphp
            <section class="latest-product-section padding-top">
                <div class="container">
                    <div class="row justify-content-center ">
                        <div class="col-lg-12 col-xl-10 col-12 ">
                            <div class="lt-product">
                                <div class="lt-product-inner">
                                    <div class="lt-product-content">
                                        <div class="section-desk">
                                            <div class="store-logo">
                                                <img src="{{ $imgpath . $homepage_header_tagImg }}" alt="">
                                            </div>
                                            <div class="section-title">
                                                <h2>{{ $homepage_header_title }}</h2>
                                            </div>
                                            <div class="row row-gap ">
                                                <div class="col-lg-6 col-12">
                                                    <p>{{ $homepage_header_subTxt }}</p>
                                                </div>
                                                @if (!empty($latestProduct))
                                                    <div class="col-lg-6 col-12">
                                                        <h2><a> {{ $latestProduct->name }}</a></h2>
                                                        <div class="price">
                                                            <ins>  {{ \App\Models\Utility::priceFormat($latestProduct->price) }}</ins>
                                                        </div>
                                                        <a href="#" class="cart-btn btn add_to_cart"  data-id="{{ $latestProduct->id }}">{{ __('ADD TO CART') }}</a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @if (!empty($latestProduct))
                                        <div class="lt-product-img">
                                            <a href="{{ route('store.product.product_view', [$store->slug, $latestProduct->id]) }}">
                                                @if (!empty($latestProduct->is_cover) )
                                                    <img src="{{ $productImg . $latestProduct->is_cover }}" alt="">
                                                @else
                                                    <img src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}" alt="">
                                                @endif
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endforeach
    @foreach ($getStoreThemeSetting as $storethemesetting)
        @if (isset($storethemesetting['section_name']) && $storethemesetting['section_name'] == 'Home-Testimonial' && $storethemesetting['array_type'] == 'inner-list' && $storethemesetting['section_enable'] == 'on')
            <section class="testimonial-section padding-top padding-bottom">
                <div class="container">
                    @php
                        $Heading_key = array_search('Heading', array_column($storethemesetting['inner-list'], 'field_name'));
                        $Heading = $storethemesetting['inner-list'][$Heading_key]['field_default_text'];

                        $HeadingSubText_key = array_search('Heading Sub Text', array_column($storethemesetting['inner-list'], 'field_name'));
                        $HeadingSubText = $storethemesetting['inner-list'][$HeadingSubText_key]['field_default_text'];
                    @endphp
                    <div class="section-title title-center text-center">
                        <h2>{{ !empty($Heading) ? $Heading : 'Testimonials' }}</h2>
                        <p>{{ !empty($HeadingSubText)
                            ? $HeadingSubText
                            : 'There is only that moment and the incredible certainty that <br> everything under the sun has been written by one hand only.' }}</p>
                    </div>
                    <div class="testimonial-slider">
                        @foreach ($getStoreThemeSetting as $key => $storethemesetting)
                            @if (isset($storethemesetting['section_name']) && $storethemesetting['section_name'] == 'Home-Testimonial' && $storethemesetting['array_type'] == 'multi-inner-list')
                                @if (isset($storethemesetting['homepage-testimonial-card-image']) || isset($storethemesetting['homepage-testimonial-card-title']) || isset($storethemesetting['homepage-testimonial-card-sub-text']) || isset($storethemesetting['homepage-testimonial-card-description']) || isset($storethemesetting['homepage-testimonial-card-enable']))
                                    @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                        @if ($storethemesetting['homepage-testimonial-card-enable'][$i] == 'on')
                                            <div class="testimonial-card">
                                                <div class="testimonial-card-inner">
                                                    <p>
                                                        {{ $storethemesetting['homepage-testimonial-card-description'][$i] }}
                                                    </p>
                                                    <div class="abt-user">
                                                        <div class="user-img">
                                                            <img src="{{ $imgpath . $storethemesetting['homepage-testimonial-card-image'][$i]['field_prev_text'] }}" alt="">
                                                        </div>
                                                        <div class="user-dtl">
                                                            <b>{{ $storethemesetting['homepage-testimonial-card-title'][$i] }}</b>
                                                            <small> {{ $storethemesetting['homepage-testimonial-card-sub-text'][$i] }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endfor
                                @else
                                    @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                        <div class="testimonial-card">
                                            <div class="testimonial-card-inner">
                                                <p>
                                                    {{ $storethemesetting['inner-list'][3]['field_default_text'] }}
                                                </p>
                                                <div class="abt-user">
                                                    <div class="user-img">
                                                        <img src="{{ $imgpath . $storethemesetting['inner-list'][1]['field_default_text'] }}" alt="">
                                                    </div>
                                                    <div class="user-dtl">
                                                        <b> {{ $storethemesetting['inner-list'][2]['field_default_text'] }}!</b>
                                                        <small>{{ $storethemesetting['inner-list'][4]['field_default_text'] }}</small>
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
    @endforeach
    @foreach ($getStoreThemeSetting as $storethemesetting)
        @if (isset($storethemesetting['section_name']) && $storethemesetting['section_name'] == 'Home-Email-Subscriber' && $storethemesetting['section_enable'] == 'on')
            @php
                $SubscriberTitle_key = array_search('Subscriber Title', array_column($storethemesetting['inner-list'], 'field_name'));
                $SubscriberTitle = $storethemesetting['inner-list'][$SubscriberTitle_key]['field_default_text'];

                $SubscriberTitle_btn_key = array_search('Button', array_column($storethemesetting['inner-list'], 'field_name'));
                $SubscriberTitle_btn = $storethemesetting['inner-list'][$SubscriberTitle_btn_key]['field_default_text'];
            @endphp
            <section class="newsletter-section padding-top">
                <div class="container">
                    <div class="row row-gap align-items-center">
                        <div class="col-md-6 col-12">
                            <h2>{{ $SubscriberTitle }}</h2>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="newsletter-form">
                                {{ Form::open(['route' => ['subscriptions.store_email', $store->id], 'method' => 'POST']) }}
                                    <div class="input-wrapper">
                                        {{ Form::email('email', null, ['placeholder' => __('Enter Your Email Address')]) }}
                                        <button type="submit" class="btn-secondary">{{ $SubscriberTitle_btn }}</button>
                                    </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endforeach
    @if ($getStoreThemeSetting[6]['section_enable'] == 'on')
        <section class="category-section padding-bottom padding-top">
            <div class="container">
                @foreach ($getStoreThemeSetting as $storethemesetting)
                    @if (isset($storethemesetting['section_name']) && $storethemesetting['section_name'] == 'Home-Categories' && !empty($pro_categories))
                        @php
                            $Titlekey = array_search('Title', array_column($storethemesetting['inner-list'], 'field_name'));
                            $Title = $storethemesetting['inner-list'][$Titlekey]['field_default_text'];

                            $Description_key = array_search('Description', array_column($storethemesetting['inner-list'], 'field_name'));
                            $Description = $storethemesetting['inner-list'][$Description_key]['field_default_text'];
                        @endphp
                        <div class="section-title title-center text-center">
                            <h2>{{ $Title }}</h2>
                            <p>{{ $Description }}</p>
                        </div>
                    @endif
                @endforeach
                <div class="row row-gap ">
                    @foreach ($pro_categories as $key => $pro_categorie)
                        <div class="col-lg-4 col-md-6 col-sm-6  col-12">
                            <div class="category-card">
                                <div class="category-img">
                                    @if (!empty($pro_categorie->categorie_img) )
                                        <a><img src="{{ $catimg . (!empty($pro_categorie->categorie_img) ? $pro_categorie->categorie_img : 'default.jpg') }}" alt=""></a>
                                    @else
                                        <a><img src="{{ asset(Storage::url('uploads/product_image/default.jpg')) }}" alt=""></a>
                                    @endif
                                </div>
                                <div class="category-content">
                                    <h4><a>{{ $pro_categorie->name }}</a></h4>
                                    <a href="{{ route('store.categorie.product', [$store->slug, $pro_categorie->name]) }}" class="btn-secondary">{{ __('SHOW MORE') }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
@endsection

@push('script-page')
    <script>
       
        $(document).ready(function() {
            $("#myTab li:eq(0)").addClass('active');

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

        });

        $(".productTab").click(function(e) {
            e.preventDefault();
            $('.productTab').removeClass('active')

        });


        $("#start_shopping").click(function() {
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#shopping_section").offset().top
            }, 2000);
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.variant-selection').each(function(index) {
               $(this).trigger('change');
            });
        });

        $(document).on('change', '.variant-selection', function() {
            var variants = [];
            let selected1 = $(this).parent().parent().find('.variant-selection');
            // let test = $(this).closest(".card-body").find('.variant-selection').val();

            $(selected1).each(function(index, element) {

                variants.push(element.value);
            });

            let product_id = $(this).closest(".card-body").find('.product_id').val();
            let variation_price = $(this).closest(".product-content").find('.variation_price');
            let product_price_error = $(this).closest(".product-content").find('.product-price-error');
            let product_price = $(this).closest(".product-content").find('.product-price');
            let add_to_cart = $(this).closest(".product-content").find('.add_to_cart');
            if (variants.length > 0) {

                $.ajax({
                    url: '{{ route('get.products.variant.quantity') }}',
                    context: this,
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        variants: variants.join(' : '),
                        product_id: product_id
                    },
                    success: function(data) {
                        product_price_error.hide();
                        product_price.show();

                        $('.variation_price' + product_id).html(data.price);
                        $('#variant_id').val(data.variant_id);
                        $('#variant_qty').val(data.quantity);

                        var variant_message_array = [];
                        $(this).parents('.card-body').find('.variant_loop').each(function(index) {
                            var variant_name = $(this).prev().text();
                            var variant_val = $(this).val();
                            // console.log(variant_val + " ," + variant_name);
                            variant_message_array.push(variant_val + " " + variant_name);
                        });
                        var variant_message = variant_message_array.join(" and ");

                        if (data.variant_id == 0) {
                            add_to_cart.hide();
                            variation_price.html('');
                            product_price.hide();
                            product_price_error.show();
                            var message =
                                '<span class=" mb-0 text-danger">This product is not available with ' +
                                variant_message + '.</span>';
                            product_price_error.html(message);
                        }else{
                            add_to_cart.show()
                        }
                    }
                });
            }
        });
    </script>
@endpush
