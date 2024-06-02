@extends('storefront.layout.theme6')
@section('page-title')
    {{ __('Home') }}
@endsection

@php
$imgpath=\App\Models\Utility::get_file('uploads/');
$productImg = \App\Models\Utility::get_file('uploads/is_cover_image/');
$catimg = \App\Models\Utility::get_file('uploads/product_image/');
$default =\App\Models\Utility::get_file('uploads/theme4/header/brand_logo.png');
$s_logo = \App\Models\Utility::get_file('uploads/store_logo/');

@endphp

@section('content')
    <div class="home-wrapper">
        @foreach ($pixelScript as $script)
            <?= $script; ?>
        @endforeach
        <section class="main-home-first-section">
            <div class="banner-slider">
                @php
                    $homepage_header_text = $homepage_header_btn = $homepage_header_bg_img = '';

                    $homepage_header_2_key = array_search('Home-Header', array_column($getStoreThemeSetting, 'section_name'));
                    if ($homepage_header_2_key != '') {
                        $homepage_header_2 = $getStoreThemeSetting[$homepage_header_2_key];
                    }
                @endphp
                @for ($i = 0; $i < $homepage_header_2['loop_number']; $i++)
                    @php
                        foreach ($homepage_header_2['inner-list'] as $homepage_header_2_value) {
                            if ($homepage_header_2_value['field_slug'] == 'homepage-header-sub-title') {
                                $homepage_header_sub_title = $homepage_header_2_value['field_default_text'];
                            }
                            if ($homepage_header_2_value['field_slug'] == 'homepage-header-title') {
                                $homepage_header_text = $homepage_header_2_value['field_default_text'];
                            }
                            if ($homepage_header_2_value['field_slug'] == 'homepage-sub-text') {
                                $homepage_header_sub_text = $homepage_header_2_value['field_default_text'];
                            }
                            if ($homepage_header_2_value['field_slug'] == 'homepage-header-button') {
                                $homepage_header_btn = $homepage_header_2_value['field_default_text'];
                            }
                            if ($homepage_header_2_value['field_slug'] == 'homepage-header-bg-image') {
                                $homepage_header_bg_img = $homepage_header_2_value['field_default_text'];
                            }

                            if (!empty($homepage_header_2[$homepage_header_2_value['field_slug']])) {
                                if ($homepage_header_2_value['field_slug'] == 'homepage-header-sub-title') {
                                    $homepage_header_sub_title = $homepage_header_2[$homepage_header_2_value['field_slug']][$i];
                                }
                                if ($homepage_header_2_value['field_slug'] == 'homepage-header-title') {
                                    $homepage_header_text = $homepage_header_2[$homepage_header_2_value['field_slug']][$i];
                                }
                                if ($homepage_header_2_value['field_slug'] == 'homepage-sub-text') {
                                    $homepage_header_sub_text = $homepage_header_2[$homepage_header_2_value['field_slug']][$i];
                                }
                                if ($homepage_header_2_value['field_slug'] == 'homepage-header-button') {
                                    $homepage_header_btn = $homepage_header_2[$homepage_header_2_value['field_slug']][$i];
                                }
                                if ($homepage_header_2_value['field_slug'] == 'homepage-header-bg-image') {
                                    $homepage_header_bg_img = $homepage_header_2[$homepage_header_2_value['field_slug']][$i]['field_prev_text'];
                                }
                            }
                        }
                    @endphp
                    @if ($getStoreThemeSetting[1]['section_enable'] == 'on')
                        <div class="banner-slide">
                            <div class="banner-content-main">
                                <div class="banner-image">
                                    <img src="{{ $imgpath . $homepage_header_bg_img }}" alt="">
                                </div>
                                <div class="container">
                                    <div class="banner-content-inner">
                                        <div class="lbl">{{ __($homepage_header_sub_title) }}</div>
                                        <h1> {{ __($homepage_header_text) }}</h1>
                                        <p> {{ __($homepage_header_sub_text) }}</p>
                                        <a href="#" class="btn-secondary start_shopping" id="start_shopping"> {{ __($homepage_header_btn) }} </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endfor
            </div>
            <div class="decorative-left">
                <svg width="113" height="49" viewBox="0 0 113 49" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M110.525 22.2417C109.535 21.3226 86.0332 0 56.487 0C26.9408 0 3.46533 21.3226 2.47524 22.2417L0 24.5L2.47524 26.7583C3.46533 27.6774 26.9408 49 56.487 49C86.0332 49 109.535 27.6774 110.525 26.7583L113 24.5L110.525 22.2417ZM100.963 26.5482C91.2633 33.7762 80.1827 38.8996 68.4201 41.5948L83.3496 26.5482H100.963ZM83.9488 22.4517L69.2018 7.58892C80.6645 10.3639 91.4623 15.4169 100.963 22.4517H83.9488ZM62.3493 6.48607L78.1907 22.4517H57.3468L42.8342 7.82532C47.3154 6.74189 51.9048 6.178 56.513 6.14468C58.5974 6.14468 60.5254 6.30225 62.4535 6.48607H62.3493ZM51.5626 26.5482L38.2746 39.9405C28.8756 36.9165 20.0178 32.3952 12.0374 26.5482H51.5626ZM12.0113 22.4517C19.9935 16.5856 28.8616 12.0547 38.2746 9.03323L51.5626 22.4517H12.0113ZM42.8082 41.1747L57.3207 26.5482H77.5132L61.5937 42.5927C59.9262 42.724 58.2327 42.8553 56.3828 42.8553C51.8443 42.8061 47.3257 42.2423 42.9124 41.1747H42.8082Z"
                        fill="#94CE79"></path>
                </svg>
            </div>
            <div class="decorative-img">
                <img src=" {{ asset('assets/theme6/img/decorative.png') }}" alt="">
            </div>
        </section>
        <section class="bestseller-section padding-bottom padding-top">
            @foreach ($getStoreThemeSetting as $storesetting)
                @if ($storesetting['section_name'] == 'Quote' && $storesetting['section_enable'] == 'on')
                    @php
                        foreach ($storesetting['inner-list'] as $value) {
                            $quote = $value['field_default_text'];
                        }
                    @endphp
                    <div class="container">
                        <div class="section-title">
                            <p>{{ $quote }}</p>
                        </div>
                    </div>
                @endif
            @endforeach

            @if (count($topRatedProducts) > 0)
                <div class="product-slider bestseller-slider flex-slider">
                    @foreach ($topRatedProducts as $k => $topRatedProduct)
                        <div class="product-card">
                            <div class="product-card-inner">
                                <div class="product-content-top d-flex  justify-content-between ">
                                    <span class="p-lbl">{{ __('Bestseller') }}</span>

                                    @if (Auth::guard('customers')->check())
                                        @if (!empty($wishlist) && isset($wishlist[$topRatedProduct->product->id]['product_id']))
                                            @if ($wishlist[$topRatedProduct->product->id]['product_id'] != $topRatedProduct->product->id)
                                                <a data-toggle="tooltip" data-original-title="Wishlist"
                                                    type="button"
                                                    class="wish-btn  add_to_wishlist wishlist_{{ $topRatedProduct->product->id }}"
                                                    data-id="{{ $topRatedProduct->product->id }}">
                                                    <i class="far fa-heart"></i>
                                                </a>
                                            @else
                                                <a data-toggle="tooltip" data-original-title="Wishlist"
                                                    type="button" class="wish-btn "
                                                    data-id="{{ $topRatedProduct->product->id }}" disabled>
                                                    <i class="fas fa-heart"></i>
                                                </a>
                                            @endif
                                        @else
                                            <a data-toggle="tooltip" data-original-title="Wishlist"
                                                type="button"
                                                class="wish-btn  add_to_wishlist wishlist_{{ $topRatedProduct->product->id }}"
                                                data-id="{{ $topRatedProduct->product->id }}">
                                                <i class="far fa-heart"></i>
                                            </a>
                                        @endif
                                    @else
                                        <a data-toggle="tooltip" data-original-title="Wishlist" type="button"
                                            class="wish-btn  add_to_wishlist wishlist_{{ $topRatedProduct->product->id }}"
                                            data-id="{{ $topRatedProduct->product->id }}">
                                            <i class="far fa-heart"></i>
                                        </a>
                                    @endif
                                </div>
                                <div class="product-img">
                                    <a href="{{ route('store.product.product_view', [$store->slug, $topRatedProduct->product->id]) }}">
                                        @if (!empty($topRatedProduct->product->is_cover))
                                            <img alt="Image placeholder"
                                                src="{{ $productImg. $topRatedProduct->product->is_cover }}"
                                                class="img-center img-fluid">
                                        @else
                                            <img alt="Image placeholder"
                                                src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}"
                                                class="img-center img-fluid">
                                        @endif
                                    </a>
                                </div>
                                <div class="product-content">
                                    <h6>
                                        {{ $topRatedProduct->product->name }}
                                    </h6>
                                    <div class="price">
                                        <ins>
                                           @if ($topRatedProduct->product->enable_product_variant == 'on')
                                                {{ __('In variant') }}
                                            @else
                                                {{ \App\Models\Utility::priceFormat($topRatedProduct->product->price) }}
                                            @endif
                                        </ins>
                                    </div>
                                    <p>Category: <b> {{ $topRatedProduct->product->product_category() }}</b></p>
                                </div>
                                <div class="product-bottom">
                                    {{-- <a href="#" class="cart-btn btn">ADD TO CART</a> --}}
                                    @if ($topRatedProduct->product->enable_product_variant == 'on')
                                        <a href="{{ route('store.product.product_view', [$store->slug, $topRatedProduct->product->id]) }}"
                                            class="cart-btn btn">
                                            {{ __('ADD TO CART') }}
                                        </a>
                                    @else
                                        <a href="javascript:void(0)"
                                            class="cart-btn btn add_to_cart"
                                            data-id="{{ $topRatedProduct->product->id }}">
                                            {{ __('ADD TO CART') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
        @foreach ($getStoreThemeSetting as $storethemesetting)
            @if (isset($storethemesetting['section_name']) &&
                $storethemesetting['section_name'] == 'Home-Email-Subscriber' &&
                $storethemesetting['section_enable'] == 'on')
                @php
                    $SubscriberTitle_key = array_search('Subscriber Title', array_column($storethemesetting['inner-list'], 'field_name'));
                    $SubscriberTitle = $storethemesetting['inner-list'][$SubscriberTitle_key]['field_default_text'];

                    $SubscriberBG_key = array_search('Subscriber Background Image', array_column($storethemesetting['inner-list'], 'field_name'));
                    $SubscriberBG = $storethemesetting['inner-list'][$SubscriberBG_key]['field_default_text'];

                @endphp
                    <sectoion class="newsletter-section padding-top padding-bottom">
                        <div class="section-bg">
                            <img src="{{$imgpath . $SubscriberBG}}" alt="">
                        </div>
                        <div class="container">
                            <div class="newsletter-content">
                                <div class="section-title">
                                    <h2>{{ !empty($SubscriberTitle) ? $SubscriberTitle : 'Subscribe to us and stay up to date with the information' }}</h2>
                                </div>
                                <div class="newsletter-form">
                                    {{ Form::open(['route' => ['subscriptions.store_email', $store->id], 'method' => 'POST']) }}
                                        <div class="input-wrapper">
                                            {{ Form::email('email', null, ['aria-label' => 'Enter your email address', 'placeholder' => __('Enter Your Email Address')]) }}

                                            <button type="submit" class="btn">Subscribe</button>
                                        </div>
                                   {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </sectoion>
            @endif
        @endforeach

        @foreach ($getStoreThemeSetting as $key => $storethemesetting)
            @if ($storethemesetting['section_name'] == 'Home-Promotions' && $storethemesetting['array_type'] == 'inner-list')
                @php
                    $section_enable = !empty($storethemesetting['section_enable']) ? $storethemesetting['section_enable'] : '';
                @endphp
            @endif
        @endforeach
        @if ($section_enable == 'on')
            <section class="store-promotions-section">
                <div class="offset-container offset-left">
                    <div class="row align-items-center justify-content-end ">
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="promotions-left padding-bottom padding-top">
                                @foreach ($getStoreThemeSetting as $key => $storethemesetting)
                                    @if ($storethemesetting['section_name'] == 'Home-Promotions' &&
                                        $storethemesetting['array_type'] == 'multi-inner-list')
                                        @if (isset($storethemesetting['homepage-promotions-font-icon']) ||
                                                isset($storethemesetting['homepage-promotions-title']) ||
                                                isset($storethemesetting['homepage-promotions-description']))
                                            @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)

                                                <div class="about-promotions">
                                                    <div class="promotions-icon">
                                                        {!! $storethemesetting['homepage-promotions-font-icon'][$i] !!}
                                                    </div>
                                                    <div class="promotions-content">
                                                        <h3> {{ $storethemesetting['homepage-promotions-title'][$i] }}</h3>
                                                        <p>   {{ $storethemesetting['homepage-promotions-description'][$i] }}</p>
                                                    </div>
                                                </div>
                                            @endfor
                                        @else
                                            @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                                <div class="about-promotions">
                                                    <div class="promotions-icon">
                                                        {!! $storethemesetting['inner-list'][0]['field_default_text'] !!}
                                                    </div>
                                                    <div class="promotions-content">
                                                        <h3> {{ $storethemesetting['inner-list'][1]['field_default_text'] }}</h3>
                                                        <p>{{ $storethemesetting['inner-list'][2]['field_default_text'] }}</p>
                                                    </div>
                                                </div>
                                            @endfor
                                        @endif
                                    @endif
                                @endforeach

                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="promotion-img">
                                {{-- <img src="assets/images/promotion_img.jpg" alt=""> --}}
                                @foreach ($getStoreThemeSetting as $storethemesetting)
                                    @php
                                        $promotion_img = '';
                                        if ($storethemesetting['section_name'] == 'Home-Promotions' && $storethemesetting['array_type'] == 'inner-list') {
                                            $promotion_img = $storethemesetting['inner-list'][0]['field_default_text'];

                                            echo '<img src="' . $imgpath . $promotion_img . '" class="img-fluid">';
                                        }
                                        // <img src="{{ asset(Storage::url('uploads/' . $promotion_img)) }}" class="img-fluid">
                                    @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @foreach ($getStoreThemeSetting as $storethemesetting)
            @if ($storethemesetting['section_name'] == 'Banner-Image')
                @php
                    $test = $getStoreThemeSetting[0]['section_enable'] == 'on' ? $storethemesetting['inner-list'][0]['field_default_text'] : '';
                @endphp
                <section class="product-section padding-top padding-bottom">
                    @if ($getStoreThemeSetting[0]['section_enable'] == 'on')
                        <div class="section-bg">
                            <img src="{{ $imgpath  . $test }}" id="shopping_section">
                        </div>
                    @endif
                    <div class="container">
                        @if (count($theme6_product_random) > 0)
                            <div class="tabs-wrapper">
                                <div class="section-title">
                                    <div class="row row-gap align-items-center">
                                        <div class="col-lg-8 col-xl-9 col-12">
                                            <ul class="d-flex tabs" id="myTab">
                                                @php
                                                    $counter= 0;
                                                @endphp
                                                @foreach ($categories as $key => $category)

                                                    <li class="tab-link" data-tab="{{$counter}}">
                                                        <a href="#{!! preg_replace('/[^A-Za-z0-9\-]/', '_', $category) !!} " class="productTab" >
                                                             {{ __($category) }}
                                                        </a>
                                                    </li>
                                                    @php
                                                        $counter++;
                                                    @endphp
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="col-lg-4  col-xl-3 col-12 d-flex justify-content-end ">
                                            <a href="{{ route('store.categorie.product', [$store->slug, 'Start shopping']) }}" class="btn-secondary">{{ __('Show More') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="tabs-container">
                                    @php
                                        $counter1= 0;
                                    @endphp
                                    @foreach ($products as $key => $items)

                                        <div class="tab-content {{ $key == 'Start shopping' ? 'active' : '' }}" id="{{$counter1}}" role="tabpanel"    aria-labelledby="shopping-tab">
                                            <div class="row product-row">
                                                @foreach ($items as $key => $product)
                                                    @if ($key < 4)
                                                        <div class="col-lg-3 col-sm-6 col-md-6 col-12 product-card">
                                                            <div class="product-card-inner">
                                                                <div class="product-content-top d-flex  justify-content-between ">
                                                                    <span class="p-lbl">{{ __('Bestseller') }}</span>
                                                                    @if (Auth::guard('customers')->check())
                                                                        @if (!empty($wishlist) && isset($wishlist[$product->id]['product_id']))
                                                                            @if ($wishlist[$product->id]['product_id'] != $product->id)
                                                                                <a data-toggle="tooltip"
                                                                                    data-original-title="Wishlist" type="button"
                                                                                    class="wish-btn  add_to_wishlist wishlist_{{ $product->id }}"
                                                                                    data-id="{{ $product->id }}">
                                                                                    <i class="far fa-heart"></i>
                                                                                </a>
                                                                            @else
                                                                                <a data-toggle="tooltip"
                                                                                    data-original-title="Wishlist" type="button"
                                                                                    class="wish-btn "
                                                                                    data-id="{{ $product->id }}" disabled>
                                                                                    <i class="fas fa-heart"></i>
                                                                                </a>
                                                                            @endif
                                                                        @else
                                                                            <a data-toggle="tooltip"
                                                                                data-original-title="Wishlist" type="button"
                                                                                class="wish-btn  add_to_wishlist wishlist_{{ $product->id }}"
                                                                                data-id="{{ $product->id }}">
                                                                                <i class="far fa-heart"></i>
                                                                            </a>
                                                                        @endif
                                                                    @else
                                                                        <a data-toggle="tooltip"
                                                                            data-original-title="Wishlist" type="button"
                                                                            class="wish-btn  add_to_wishlist wishlist_{{ $product->id }}"
                                                                            data-id="{{ $product->id }}">
                                                                            <i class="far fa-heart"></i>
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                                <div class="product-img">

                                                                    <a
                                                                        href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}">
                                                                        @if (!empty($product->is_cover) )
                                                                            <img alt="Image placeholder"
                                                                                src="{{ $productImg . $product->is_cover }}">
                                                                        @else
                                                                            <img alt="Image placeholder"
                                                                                src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}">
                                                                        @endif
                                                                    </a>
                                                                </div>
                                                                <div class="product-content">
                                                                    <h6>
                                                                        <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}">{{ $product->name }}</a>
                                                                    </h6>
                                                                    <div class="price">
                                                                        <ins><span class="currency-type"></span>
                                                                            @if ($product->enable_product_variant == 'on')
                                                                                {{ __('In variant') }}
                                                                            @else
                                                                                {{ \App\Models\Utility::priceFormat($product->price) }}
                                                                            @endif

                                                                        </ins>
                                                                    </div>
                                                                    <p>Category: <b> {{ $product->product_category() }}</b></p>
                                                                </div>
                                                                <div class="product-bottom">

                                                                    @if ($product->enable_product_variant == 'on')
                                                                        <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}"
                                                                            class="cart-btn btn">
                                                                            {{ __('ADD TO CART') }}
                                                                        </a>
                                                                    @else
                                                                        <a href="javascript:void(0)"
                                                                            class="cart-btn btn add_to_cart"
                                                                            data-id="{{ $product->id }}">
                                                                            {{ __('ADD TO CART') }}
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        @php
                                            $counter1++;
                                        @endphp
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </section>
            @endif
        @endforeach

        @foreach ($getStoreThemeSetting as $storethemesetting)
            @if (isset($storethemesetting['section_name']) &&
                $storethemesetting['section_name'] == 'Home-Categories' &&
                $storethemesetting['section_enable'] == 'on' &&
                !empty($pro_categories))
                @php
                    // dd($storethemesetting);
                    $Titlekey = array_search('Title', array_column($storethemesetting['inner-list'], 'field_name'));
                    $Title = $storethemesetting['inner-list'][$Titlekey]['field_default_text'];

                    $Description_key = array_search('Description', array_column($storethemesetting['inner-list'], 'field_name'));
                    $Description = $storethemesetting['inner-list'][$Description_key]['field_default_text'];
                @endphp
                    <section class="category-section padding-bottom padding-top">
                        <div class="container">
                            <div class="section-title title-center">
                                <h2>{{ !empty($Title) ? $Title : 'Categories' }}</h2>
                                <p>{{ !empty($Description)
                                        ? $Description
                                        : 'There is only that moment and the incredible certainty <br> that everything under the sun has been written by one hand only.' }}</p>
                            </div>
                            <div class="row row-gap">
                                @foreach ($pro_categories as $key => $pro_categorie)
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-12 category-card">
                                        <div class="category-card-inner">
                                            <div class="category-img">
                                                {{-- <img src="assets/images/cate1.png" alt=""> --}}
                                                @if (!empty($pro_categorie->categorie_img) )
                                                    <img alt="Image placeholder"
                                                        src="{{ $catimg. (!empty($pro_categorie->categorie_img) ? $pro_categorie->categorie_img : 'default.jpg') }}">
                                                @else
                                                    <img alt="Image placeholder"
                                                        src="{{ asset(Storage::url('uploads/product_image/default.jpg')) }}">
                                                @endif
                                            </div>
                                            <div class="category-content">
                                                <h3>{{ $pro_categorie->name }}</h3>
                                                <a href="{{ route('store.categorie.product', [$store->slug, $pro_categorie->name]) }}" class="btn btn-bordered">VIEW MORE</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
            @endif
        @endforeach

    @if($getStoreThemeSetting[6]['section_enable'] == 'on')
        <section class="testimonial-section padding-bottom">
            <div class="container">
                @foreach ($getStoreThemeSetting as $storethemesetting)
                    @if (isset($storethemesetting['section_name']) &&
                        $storethemesetting['section_name'] == 'Home-Testimonial' &&
                        $storethemesetting['array_type'] == 'inner-list' &&
                        $storethemesetting['section_enable'] == 'on')
                        @php
                            $Heading_key = array_search('Heading', array_column($storethemesetting['inner-list'], 'field_name'));
                            $Heading = $storethemesetting['inner-list'][$Heading_key]['field_default_text'];

                            $HeadingSubText_key = array_search('Heading Sub Text', array_column($storethemesetting['inner-list'], 'field_name'));
                            $HeadingSubText = $storethemesetting['inner-list'][$HeadingSubText_key]['field_default_text'];
                        @endphp
                            <div class="section-title title-center">
                                <h2>{{ !empty($Heading) ? $Heading : '' }}</h2>
                                <p> {{ !empty($HeadingSubText) ? $HeadingSubText : '' }}</p>
                            </div>
                    @endif
                @endforeach
                <div class="testimonial-slider">
                    @foreach ($getStoreThemeSetting as $key => $storethemesetting)
                        @if (isset($storethemesetting['section_name']) && $storethemesetting['section_name'] == 'Home-Testimonial' && $storethemesetting['array_type'] == 'multi-inner-list')
                            @if (isset($storethemesetting['homepage-testimonial-card-image']) || isset($storethemesetting['homepage-testimonial-card-title']) || isset($storethemesetting['homepage-testimonial-card-sub-text']) || isset($storethemesetting['homepage-testimonial-card-description']) || isset($storethemesetting['homepage-testimonial-card-enable']))
                                @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                    @if($storethemesetting['homepage-testimonial-card-enable'][$i] == 'on')
                                        <div class="testimonial-card">
                                            <div class="testimonial-inner">
                                                <h5> {{ $storethemesetting['homepage-testimonial-card-title'][$i] }}</h5>
                                                <div class="user-words">
                                                    <p><span class="starting">
                                                            <svg width="14" height="10" viewBox="0 0 14 10" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M0 7.61497C0 8.77005 0.855615 9.62567 2.05348 9.62567C3.5508 9.62567 4.96257 8.25668 4.96257 6.75936C4.96257 5.7754 4.32086 5.04813 3.59358 4.83423L6.20321 0H3.93583L0.385027 6.28877C0.171123 6.6738 0 7.14438 0 7.61497ZM7.27273 7.61497C7.27273 8.77005 8.12834 9.62567 9.3262 9.62567C10.8235 9.62567 12.2353 8.25668 12.2353 6.75936C12.2353 5.7754 11.5936 5.04813 10.8663 4.83423L13.4759 0H11.2086L7.65775 6.28877C7.44385 6.6738 7.27273 7.14438 7.27273 7.61497Z"
                                                                    fill="#615144"></path>
                                                            </svg>
                                                        </span> {{ $storethemesetting['homepage-testimonial-card-description'][$i] }}
                                                        <span class="closing">
                                                            <svg width="14" height="10" viewBox="0 0 14 10" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M0.507812 7.73216C0.507812 8.88724 1.36343 9.74286 2.56129 9.74286C4.05861 9.74286 5.47038 8.37387 5.47038 6.87654C5.47038 5.89259 4.82867 5.16532 4.1014 4.95141L6.71102 0.117188H4.44364L0.892839 6.40596C0.678936 6.79098 0.507812 7.26157 0.507812 7.73216ZM7.78054 7.73216C7.78054 8.88724 8.63616 9.74286 9.83402 9.74286C11.3313 9.74286 12.7431 8.37387 12.7431 6.87654C12.7431 5.89259 12.1014 5.16532 11.3741 4.95141L13.9837 0.117188H11.7164L8.16557 6.40596C7.95166 6.79098 7.78054 7.26157 7.78054 7.73216Z"
                                                                    fill="#615144"></path>
                                                            </svg>
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="storelogo">
                                                    <img src="{{ $imgpath. $storethemesetting['homepage-testimonial-card-image'][$i]['field_prev_text'] }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endfor
                            @else
                                @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                    <div class="testimonial-card">
                                        <div class="testimonial-inner">
                                            <h5>{{ $storethemesetting['inner-list'][2]['field_default_text'] }}</h5>
                                            <div class="user-words">
                                                <p><span class="starting">
                                                        <svg width="14" height="10" viewBox="0 0 14 10" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M0 7.61497C0 8.77005 0.855615 9.62567 2.05348 9.62567C3.5508 9.62567 4.96257 8.25668 4.96257 6.75936C4.96257 5.7754 4.32086 5.04813 3.59358 4.83423L6.20321 0H3.93583L0.385027 6.28877C0.171123 6.6738 0 7.14438 0 7.61497ZM7.27273 7.61497C7.27273 8.77005 8.12834 9.62567 9.3262 9.62567C10.8235 9.62567 12.2353 8.25668 12.2353 6.75936C12.2353 5.7754 11.5936 5.04813 10.8663 4.83423L13.4759 0H11.2086L7.65775 6.28877C7.44385 6.6738 7.27273 7.14438 7.27273 7.61497Z"
                                                                fill="#615144"></path>
                                                        </svg>
                                                    </span> {{ $storethemesetting['inner-list'][3]['field_default_text'] }}
                                                    <span class="closing">
                                                        <svg width="14" height="10" viewBox="0 0 14 10" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M0.507812 7.73216C0.507812 8.88724 1.36343 9.74286 2.56129 9.74286C4.05861 9.74286 5.47038 8.37387 5.47038 6.87654C5.47038 5.89259 4.82867 5.16532 4.1014 4.95141L6.71102 0.117188H4.44364L0.892839 6.40596C0.678936 6.79098 0.507812 7.26157 0.507812 7.73216ZM7.78054 7.73216C7.78054 8.88724 8.63616 9.74286 9.83402 9.74286C11.3313 9.74286 12.7431 8.37387 12.7431 6.87654C12.7431 5.89259 12.1014 5.16532 11.3741 4.95141L13.9837 0.117188H11.7164L8.16557 6.40596C7.95166 6.79098 7.78054 7.26157 7.78054 7.73216Z"
                                                                fill="#615144"></path>
                                                        </svg>
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="storelogo">
                                                <img src="{{ $imgpath. (!empty($storethemesetting['inner-list'][1]['field_default_text']) ? $storethemesetting['inner-list'][1]['field_default_text'] : 'avatar.png') }}" alt="">
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
        <section class="storepost-section padding-bottom padding-top">
            <div class="container">
                <div class="row row-gap">
                    @foreach ($getStoreThemeSetting as $key => $storethemesetting)
                        @if (isset($storethemesetting['section_name']) &&
                            $storethemesetting['section_name'] == 'Home-Brand-Logo' &&
                            $storethemesetting['section_enable'] == 'on')
                                @foreach ($storethemesetting['inner-list'] as $image)
                                    @if (!empty($image['image_path']))
                                        @foreach ($image['image_path'] as $img)
                                            <div class="col-lg-2 col-sm-4 col-12">
                                                <div class="store-post-img">
                                                    <img src="{{ $imgpath . (!empty($img) ? $img : 'theme5/brand_logo/brand_logo.png') }}" alt="">
                                                </div>
                                            </div>
                                        @endforeach
                                    @else

                                        <div class="col-lg-2 col-sm-4 col-12">
                                            <div class="store-post-img">
                                                <img src="{{ $default}}" alt="">
                                            </div>
                                        </div>

                                        <div class="col-lg-2 col-sm-4 col-12">
                                            <div class="store-post-img">
                                                <img src="{{ $default}}" alt="">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-sm-4 col-12">
                                            <div class="store-post-img">
                                                <img src="{{ $default}}" alt="">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-sm-4 col-12">
                                            <div class="store-post-img">
                                                <img src="{{ $default}}" alt="">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-sm-4 col-12">
                                            <div class="store-post-img">
                                                <img src="{{ $default}}" alt="">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-sm-4 col-12">
                                            <div class="store-post-img">
                                                <img src="{{ $default}}" alt="">
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                        @endif
                    @endforeach

                </div>
            </div>
        </section>
    </div>

@endsection

@push('script-page')
    <script>


         $(document).ready(function() {

           $("#electronic-tab").trigger('click');
            // $('#Furniture').addClass('active');
            $("#myTab li:eq(0)").addClass('active');
            $("#myTab li a:eq(0)").trigger('click');
            $("#myTab li a:eq(0)").addClass('active');
        });
        $(".start_shopping").click(function() {
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#shopping_section").offset().top
            });
        });
    </script>
@endpush
