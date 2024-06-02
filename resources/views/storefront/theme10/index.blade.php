@extends('storefront.layout.theme10')

@section('page-title')
    {{ __('Home') }}
@endsection

@push('css-page')
@endpush
@php
    $imgpath = \App\Models\Utility::get_file('uploads/');
    $productImg = \App\Models\Utility::get_file('uploads/is_cover_image/');
    $catimg = \App\Models\Utility::get_file('uploads/product_image/');
    $default = \App\Models\Utility::get_file('uploads/theme10/header/brand_logo.jpg');
    $s_logo = \App\Models\Utility::get_file('uploads/store_logo/');

@endphp
@section('content')
    <div class="wrapper">
        @foreach ($pixelScript as $script)
            <?= $script; ?>
        @endforeach
        @foreach ($getStoreThemeSetting as $ThemeSetting)
            @if (isset($ThemeSetting['section_name']) &&
                    $ThemeSetting['section_name'] == 'Home-Header' &&
                    $ThemeSetting['section_enable'] == 'on')
                @php
                    $homepage_header_img_key = array_search('Background Image', array_column($ThemeSetting['inner-list'], 'field_name'));
                    $homepage_header_img = $ThemeSetting['inner-list'][$homepage_header_img_key]['field_default_text'];

                    $homepage_header_title_key = array_search('Title', array_column($ThemeSetting['inner-list'], 'field_name'));
                    $homepage_header_title = $ThemeSetting['inner-list'][$homepage_header_title_key]['field_default_text'];

                    $homepage_header_subtxt_key = array_search('Sub text', array_column($ThemeSetting['inner-list'], 'field_name'));
                    $homepage_header_subtxt = $ThemeSetting['inner-list'][$homepage_header_subtxt_key]['field_default_text'];

                    $homepage_header_btn_key = array_search('Button', array_column($ThemeSetting['inner-list'], 'field_name'));
                    $homepage_header_btn = $ThemeSetting['inner-list'][$homepage_header_btn_key]['field_default_text'];
                @endphp
                <section class="main-home-first-section padding-top">
                    <div class="offset-container offset-left">
                        <div class="row align-items-center">
                            <div class="col-md-6 col-12">
                                <div class="banner-content">
                                    <h1> {{ $homepage_header_title }}</h1>
                                    <p> {{ $homepage_header_subtxt }}</p>
                                    <a href="{{ route('store.categorie.product', [$store->slug, 'Start shopping']) }}"
                                        class="btn"> {{ $homepage_header_btn }}</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="banner-image">
                                    <img src="{{ $imgpath . $homepage_header_img }}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        @endforeach
        <section class="search-cat-section padding-top tabs-wrapper">
            <div class="container">
                <div class="category-search ">
                    <form action="{{ route('store.categorie.product', [$store->slug, 'Start shopping']) }}" method="get">
                        @csrf
                        <div class="input-wrapper">
                            <span class="in-icon">
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M0 8.25C0 12.8063 3.69365 16.5 8.25 16.5C10.1548 16.5 11.9088 15.8545 13.3052 14.7702C13.3578 14.8815 13.4302 14.9858 13.5222 15.0778L20.1222 21.6778C20.5518 22.1074 21.2482 22.1074 21.6778 21.6778C22.1074 21.2482 22.1074 20.5518 21.6778 20.1222L15.0778 13.5222C14.9858 13.4302 14.8815 13.3578 14.7702 13.3052C15.8545 11.9088 16.5 10.1548 16.5 8.25C16.5 3.69365 12.8063 0 8.25 0C3.69365 0 0 3.69365 0 8.25ZM2.2 8.25C2.2 4.90868 4.90868 2.2 8.25 2.2C11.5913 2.2 14.3 4.90868 14.3 8.25C14.3 11.5913 11.5913 14.3 8.25 14.3C4.90868 14.3 2.2 11.5913 2.2 8.25Z"
                                        fill="black"></path>
                                </svg>
                            </span>
                            <input type="text" placeholder="Type car part..." name="search_data">
                        </div>
                        <div class="nice-select tab-bar" tabindex="0"><span class="current">{{ __('Start shopping') }}</span>
                            <ul class="list cat-tab tabs">
                                @foreach ($categories as $key => $category)
                                <li class="option selected" data-tab="tab-{!! preg_replace('/[^A-Za-z0-9\-]/', '_', $category) !!}">{{ __($category) }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="btn-wrapper">
                            <button type="submit" class="btn-secondary">
                                <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_8_776)">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M0.5 12C0.5 11.4477 0.947715 11 1.5 11L21.0858 11L14.7932 4.7071C14.4026 4.31657 14.4027 3.6834 14.7932 3.29288C15.1837 2.90236 15.8169 2.90238 16.2074 3.29291L24.2071 11.2929C24.5976 11.6834 24.5976 12.3166 24.2071 12.7071L16.2074 20.7071C15.8169 21.0976 15.1837 21.0976 14.7932 20.7071C14.4027 20.3166 14.4026 19.6834 14.7932 19.2929L21.0858 13L1.5 13C0.947715 13 0.5 12.5523 0.5 12Z"
                                            fill="white"></path>
                                    </g>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </section>
        <section class="popular-itm-section padding-top padding-bottom tabs-wrapper">
            <div class="container">
                <div class="tabs-container">
                    <div class="section-title d-flex align-items-center justify-content-between">
                        <h2>{{ __('Popular Modals') }}</h2>
                        <a href="{{ route('store.categorie.product', [$store->slug, 'Start shopping']) }}"
                            class="btn">{{ __('GO TO SHOP') }}</a>
                    </div>

                    @foreach ($products as $key => $items)
                        <div class="tab-content   {{ $key == 'Start shopping' ? 'active' : '' }}"
                            id="tab-{!! preg_replace('/[^A-Za-z0-9\-]/', '_', $key) !!}">
                            <div class="row product-row">
                                @foreach ($items as $key => $product)
                                    @if ($key < 4)
                                        <div class="col-lg-3 col-sm-6 col-md-6 col-12 product-card">
                                            <div class="product-card-inner">
                                                <div
                                                    class="product-content-top d-flex align-items-center justify-content-between ">
                                                    <span class="p-lbl">
                                                        @if ($product->enable_product_variant == 'on')
                                                            {{ __('Variant') }}
                                                        @else
                                                            {{ \App\Models\Utility::priceFormat($product->price - $product->last_price) }}
                                                            {{ __('off') }}
                                                        @endif
                                                    </span>
                                                    @if (Auth::guard('customers')->check())
                                                        @if (!empty($wishlist) && isset($wishlist[$product->id]['product_id']))
                                                            @if ($wishlist[$product->id]['product_id'] != $product->id)
                                                                <a href="#"
                                                                    class="wish-btn add_to_wishlist wishlist_{{ $product->id }}"
                                                                    data-id="{{ $product->id }}"><i
                                                                        class="far fa-heart"></i></a>
                                                            @else
                                                                <a href="#" class="wish-btn"
                                                                    data-id="{{ $product->id }}"><i
                                                                        class="fas fa-heart"></i></a>
                                                            @endif
                                                        @else
                                                            <a href="#"
                                                                class="wish-btn add_to_wishlist wishlist_{{ $product->id }}"
                                                                data-id="{{ $product->id }}"><i
                                                                    class="far fa-heart"></i></a>
                                                        @endif
                                                    @else
                                                        <a href="#"
                                                            class="wish-btn  add_to_wishlist wishlist_{{ $product->id }}"
                                                            data-id="{{ $product->id }}"><i class="far fa-heart"></i></a>
                                                    @endif

                                                </div>
                                                <div class="product-img">
                                                    <a
                                                        href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}">
                                                        @if (!empty($product->is_cover))
                                                            <img src="{{ $productImg . $product->is_cover }}"
                                                                alt="">
                                                        @else
                                                            <img src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}"
                                                                alt="">
                                                        @endif
                                                    </a>
                                                </div>

                                                <div class="product-content">
                                                    <h6>
                                                        <a
                                                            href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}">{{ $product->name }}</a>
                                                    </h6>
                                                    <p>{{ __('Category:') }} <b> {{ $product->categories->name ?? '' }}</b></p>
                                                    <div class="price">
                                                        <ins>
                                                            @if ($product->enable_product_variant == 'on')
                                                                {{ __('In variant') }}
                                                            @else
                                                                {{ \App\Models\Utility::priceFormat($product->price) }}
                                                            @endif
                                                        </ins>
                                                        <del>
                                                            @if ($product->enable_product_variant == 'off')
                                                                {{ \App\Models\Utility::priceFormat($product->last_price) }}
                                                            @endif
                                                        </del>
                                                    </div>

                                                </div>
                                                <div class="product-bottom">
                                                    @if ($product->enable_product_variant == 'on')
                                                        <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}"
                                                            class="cart-btn btn"> {{ __('ADD TO CART') }}</a>
                                                    @else
                                                        <a href="#" class="cart-btn btn add_to_cart"
                                                            data-id="{{ $product->id }}"> {{ __('ADD TO CART') }}</a>
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
        </section>
        @foreach ($getStoreThemeSetting as $ThemeSetting)
            @if (isset($ThemeSetting['section_name']) &&
                    $ThemeSetting['section_name'] == 'Latest-Category' &&
                    $ThemeSetting['section_enable'] == 'on')
                @php
                    $homepage_header_title_key = array_search('Title', array_column($ThemeSetting['inner-list'], 'field_name'));
                    $homepage_header_title = $ThemeSetting['inner-list'][$homepage_header_title_key]['field_default_text'];

                    $homepage_header_subtxt_key = array_search('Sub text', array_column($ThemeSetting['inner-list'], 'field_name'));
                    $homepage_header_subtxt = $ThemeSetting['inner-list'][$homepage_header_subtxt_key]['field_default_text'];

                    $homepage_header_btn_key = array_search('Button', array_column($ThemeSetting['inner-list'], 'field_name'));
                    $homepage_header_btn = $ThemeSetting['inner-list'][$homepage_header_btn_key]['field_default_text'];
                @endphp
                <section class="latest-categories  padding-top padding-bottom">
                    <div class="container">
                        <div class="row row-gap">
                            <div class="col-lg-4 col-12">
                                <div class="section-desk">
                                    <div class="section-title">
                                        <h2> {{ $homepage_header_title }}</h2>
                                    </div>
                                    <p> {{ $homepage_header_subtxt }}</p>
                                    <a href="{{ route('store.categorie.product', [$store->slug, 'Start shopping']) }}"
                                        class="btn"> {{ $homepage_header_btn }}</a>
                                </div>
                            </div>
                            <div class="col-lg-8 col-12">
                                <div class="row row-gap">
                                    @foreach ($latest2category as $key_c => $category)
                                        @if ($key_c < 2)
                                            <div class="col-sm-6 col-12 category-card">
                                                <div class="category-card-inner">
                                                    <div class="category-img">
                                                        @if (!empty($category->categorie_img))
                                                            <img src="{{ $catimg . (!empty($category->categorie_img) ? $category->categorie_img : 'default.jpg') }}"
                                                                alt="">
                                                        @else
                                                            <img src="{{ asset(Storage::url('uploads/product_image/default.jpg')) }}"
                                                                alt="">
                                                        @endif
                                                    </div>
                                                    <div class="category-content">
                                                        <h4><a>{{ $category->name }}</a></h4>
                                                        <a href="{{ route('store.categorie.product', [$store->slug, $category->name]) }}"
                                                            class="btn"><svg width="25" height="24"
                                                                viewBox="0 0 25 24" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <g clip-path="url(#clip0_20_4009)">
                                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                                        d="M0.5 12C0.5 11.4477 0.947715 11 1.5 11L21.0858 11L14.7932 4.7071C14.4026 4.31657 14.4027 3.6834 14.7932 3.29288C15.1837 2.90236 15.8169 2.90238 16.2074 3.29291L24.2071 11.2929C24.5976 11.6834 24.5976 12.3166 24.2071 12.7071L16.2074 20.7071C15.8169 21.0976 15.1837 21.0976 14.7932 20.7071C14.4027 20.3166 14.4026 19.6834 14.7932 19.2929L21.0858 13L1.5 13C0.947715 13 0.5 12.5523 0.5 12Z"
                                                                        fill="#F7F6F1"></path>
                                                                </g>
                                                                <defs>
                                                                    <clipPath id="clip0_20_4009">
                                                                        <rect width="24" height="24"
                                                                            fill="white"
                                                                            transform="matrix(-1 0 0 1 24.5 0)"></rect>
                                                                    </clipPath>
                                                                </defs>
                                                            </svg></a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        @endforeach
        @foreach ($getStoreThemeSetting as $ThemeSetting)
            @if (isset($ThemeSetting['section_name']) &&
                    $ThemeSetting['section_name'] == 'Latest-Products' &&
                    $ThemeSetting['section_enable'] == 'on')
                @php
                    $latestCatTitle_key = array_search('Title', array_column($ThemeSetting['inner-list'], 'field_name'));
                    $latestCatTitle = $ThemeSetting['inner-list'][$latestCatTitle_key]['field_default_text'];

                    $latestCatSubText_key = array_search('Sub text', array_column($ThemeSetting['inner-list'], 'field_name'));
                    $latestCatSubText = $ThemeSetting['inner-list'][$latestCatSubText_key]['field_default_text'];

                    $latestCatButton_key = array_search('Button', array_column($ThemeSetting['inner-list'], 'field_name'));
                    $latestCatButton = $ThemeSetting['inner-list'][$latestCatButton_key]['field_default_text'];

                    $latestCatTagImg_key = array_search('Category Tag Image', array_column($ThemeSetting['inner-list'], 'field_name'));
                    $latestCatTagImg = $ThemeSetting['inner-list'][$latestCatTagImg_key]['field_default_text'];

                    $latestCatbackGround_key = array_search('Category Background Image', array_column($ThemeSetting['inner-list'], 'field_name'));
                    $latestCatbackGround = $ThemeSetting['inner-list'][$latestCatbackGround_key]['field_default_text'];
                    // dD($latestCatbackGround);
                @endphp
                <section class="latest-itm-section padding-top padding-bottom">
                    <div class="background-image">
                        <img src="{{ $imgpath . $latestCatbackGround }}" alt="">
                    </div>
                    <div class="container">
                        <div class="row row-gap align-items-center">
                            <div class="col-lg-6 col-12">
                                <div class="section-desk">
                                    <div class="store-logo">
                                        <img src="{{ $imgpath . $latestCatTagImg }}" alt="">
                                    </div>
                                    <div class="section-title">
                                        <h2>{{ $latestCatTitle }}</h2>
                                    </div>
                                    <p> {{ $latestCatSubText }}</p>
                                    <a href="{{ route('store.categorie.product', [$store->slug, 'Start shopping']) }}"
                                        class="btn btn-white">{{ $latestCatButton }}</a>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="row row-gap">
                                    @foreach ($latestProduct10 as $keys => $latestProduct)
                                        <div class="col-sm-6 col-md-6 col-12 product-card">
                                            <div class="product-card-inner">
                                                <div
                                                    class="product-content-top d-flex align-items-center justify-content-between ">
                                                    <span class="p-lbl">
                                                        @if ($latestProduct->enable_product_variant == 'on')
                                                            {{ __('Variant') }}
                                                        @else
                                                            {{ \App\Models\Utility::priceFormat($latestProduct->price - $latestProduct->last_price) }}
                                                            {{ __('off') }}
                                                        @endif
                                                    </span>
                                                    @if (Auth::guard('customers')->check())
                                                        @if (!empty($wishlist) && isset($wishlist[$latestProduct->id]['product_id']))
                                                            @if ($wishlist[$latestProduct->id]['product_id'] != $latestProduct->id)
                                                                <a href="#"
                                                                    class="wish-btn add_to_wishlist wishlist_{{ $latestProduct->id }}"
                                                                    data-id="{{ $latestProduct->id }}"><i
                                                                        class="far fa-heart"></i></a>
                                                            @else
                                                                <a href="#" class="wish-btn"
                                                                    data-id="{{ $latestProduct->id }}"><i
                                                                        class="fas fa-heart"></i></a>
                                                            @endif
                                                        @else
                                                            <a href="#"
                                                                class="wish-btn add_to_wishlist wishlist_{{ $latestProduct->id }}"
                                                                data-id="{{ $latestProduct->id }}"><i
                                                                    class="far fa-heart"></i></a>
                                                        @endif
                                                    @else
                                                        <a href="#"
                                                            class="wish-btn add_to_wishlist wishlist_{{ $latestProduct->id }}"
                                                            data-id="{{ $latestProduct->id }}"><i
                                                                class="far fa-heart"></i></a>
                                                    @endif

                                                </div>
                                                <div class="product-img">
                                                    <a
                                                        href="{{ route('store.product.product_view', [$store->slug, $latestProduct->id]) }}">
                                                        @if (!empty($latestProduct->is_cover))
                                                            <img src="{{ $productImg . $latestProduct->is_cover }}"
                                                                alt="">
                                                        @else
                                                            <img src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}"
                                                                alt="">
                                                        @endif
                                                    </a>
                                                </div>
                                                <div class="product-content">
                                                    <h6>
                                                        <a
                                                            href="{{ route('store.product.product_view', [$store->slug, $latestProduct->id]) }}">
                                                            {{ $latestProduct->name }}</a>
                                                    </h6>
                                                    <p> {{ __('Category:') }}
                                                        {{ isset($latestProduct->categories->name) ? $latestProduct->categories->name : '' }}</b></p>
                                                    <div class="price">
                                                        <ins>
                                                            @if ($latestProduct->enable_product_variant == 'on')
                                                                {{ __('In variant') }}
                                                            @else
                                                                {{ \App\Models\Utility::priceFormat($latestProduct->price) }}
                                                            @endif
                                                        </ins>
                                                        <del>
                                                            @if ($latestProduct->enable_product_variant == 'off')
                                                                {{ \App\Models\Utility::priceFormat($latestProduct->last_price) }}
                                                            @endif
                                                        </del>
                                                    </div>

                                                </div>
                                                <div class="product-bottom">
                                                    @if ($latestProduct->enable_product_variant == 'on')
                                                        <a href="{{ route('store.product.product_view', [$store->slug, $latestProduct->id]) }}"
                                                            class="cart-btn btn">{{ __('ADD TO CART') }}</a>
                                                    @else
                                                        <a href="#" class="cart-btn btn add_to_cart"
                                                            data-id="{{ $latestProduct->id }}">{{ __('ADD TO CART') }}</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        @endforeach
        @if ($getStoreThemeSetting[3]['section_enable'] == 'on')
            <section class="category-section padding-top padding-bottom">
                <div class="container">
                    @foreach ($getStoreThemeSetting as $storethemesetting)
                        @if (isset($storethemesetting['section_name']) &&
                                $storethemesetting['section_name'] == 'Home-Categories' &&
                                !empty($pro_categories))
                            @php
                                $Titlekey = array_search('Title', array_column($storethemesetting['inner-list'], 'field_name'));
                                $Title = $storethemesetting['inner-list'][$Titlekey]['field_default_text'];

                                $Description_key = array_search('Description', array_column($storethemesetting['inner-list'], 'field_name'));
                                $Description = $storethemesetting['inner-list'][$Description_key]['field_default_text'];
                            @endphp
                            <div class="section-title title-center text-center">
                                <h2> {{ $Title }}</h2>
                                <p>{{ $Description }}</p>
                            </div>
                        @endif
                    @endforeach
                    <div class="row row-gap">
                        @foreach ($pro_categories as $key => $pro_categorie)
                            <div class="col-lg-4 col-md-4 col-sm-6 col-12 category-card">
                                <div class="category-card-inner">
                                    <div class="category-img">
                                        @if (!empty($pro_categorie->categorie_img))
                                            <img src="{{ $catimg . (!empty($pro_categorie->categorie_img) ? $pro_categorie->categorie_img : 'default.jpg') }}"
                                                alt="">
                                        @else
                                            <img src="{{ asset(Storage::url('uploads/product_image/default.jpg')) }}"
                                                alt="">
                                        @endif
                                    </div>
                                    <div class="category-content">
                                        <h4><a
                                                href="{{ route('store.categorie.product', [$store->slug, $pro_categorie->name]) }}">{{ $pro_categorie->name }}</a>
                                        </h4>
                                        <a href="{{ route('store.categorie.product', [$store->slug, $pro_categorie->name]) }}"
                                            class="btn"><svg width="25" height="24" viewBox="0 0 25 24"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_20_4009)">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M0.5 12C0.5 11.4477 0.947715 11 1.5 11L21.0858 11L14.7932 4.7071C14.4026 4.31657 14.4027 3.6834 14.7932 3.29288C15.1837 2.90236 15.8169 2.90238 16.2074 3.29291L24.2071 11.2929C24.5976 11.6834 24.5976 12.3166 24.2071 12.7071L16.2074 20.7071C15.8169 21.0976 15.1837 21.0976 14.7932 20.7071C14.4027 20.3166 14.4026 19.6834 14.7932 19.2929L21.0858 13L1.5 13C0.947715 13 0.5 12.5523 0.5 12Z"
                                                        fill="#F7F6F1"></path>
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_20_4009">
                                                        <rect width="24" height="24" fill="white"
                                                            transform="matrix(-1 0 0 1 24.5 0)"></rect>
                                                    </clipPath>
                                                </defs>
                                            </svg></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
      
        @if (count($topRatedProducts) > 0)
            <section class="top-products-section padding-bottom">
                <div class="container">
                    <div class="section-title d-flex align-items-center justify-content-between">
                        <h2>{{ __('Top Rated Products') }}</h2>
                        <a href="{{ route('store.categorie.product', [$store->slug, 'Start shopping']) }}"
                            class="btn">{{ __('GO TO SHOP') }}</a>
                    </div>
                    <div class="row product-row">
                    
                            @foreach ($topRatedProducts as $k => $topRatedProduct)
                                <div class="col-lg-3 col-sm-6 col-md-6 col-12 product-card">
                                    <div class="product-card-inner">
                                        <div class="product-content-top d-flex align-items-center justify-content-between ">
                                            <span class="p-lbl">
                                                @if ($topRatedProduct->product->enable_product_variant == 'on')
                                                    {{ __('Variant') }}
                                                @else
                                                    {{ \App\Models\Utility::priceFormat($topRatedProduct->product->price - $topRatedProduct->product->last_price) }}
                                                    {{ __('off') }}
                                                @endif
                                            </span>
                                            @if (Auth::guard('customers')->check())
                                                @if (!empty($wishlist) && isset($wishlist[$topRatedProduct->product->id]['product_id']))
                                                    @if ($wishlist[$topRatedProduct->product->id]['product_id'] != $topRatedProduct->product->id)
                                                        <a href="#"
                                                            class="wish-btn add_to_wishlist wishlist_{{ $topRatedProduct->product->id }}"
                                                            data-id="{{ $topRatedProduct->product->id }}"><i
                                                                class="far fa-heart"></i></a>
                                                    @else
                                                        <a href="#" class="wish-btn"
                                                            data-id="{{ $topRatedProduct->product->id }}"><i
                                                                class="fas fa-heart"></i></a>
                                                    @endif
                                                @else
                                                    <a href="#"
                                                        class="wish-btn add_to_wishlist wishlist_{{ $topRatedProduct->product->id }}"
                                                        data-id="{{ $topRatedProduct->product->id }}"><i
                                                            class="far fa-heart"></i></a>
                                                @endif
                                            @else
                                                <a href="#"
                                                    class="wish-btn add_to_wishlist wishlist_{{ $topRatedProduct->product->id }}"
                                                    data-id="{{ $topRatedProduct->product->id }}"><i
                                                        class="far fa-heart"></i></a>
                                            @endif

                                        </div>
                                        <div class="product-img">
                                            <a
                                                href="{{ route('store.product.product_view', [$store->slug, $topRatedProduct->product->id]) }}">
                                                @if (!empty($topRatedProduct->product->is_cover))
                                                    <img src="{{ $productImg . $topRatedProduct->product->is_cover }}"
                                                        alt="">
                                                @else
                                                    <img src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}"
                                                        alt="">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="product-content">
                                            <h6>
                                                <a
                                                    href="{{ route('store.product.product_view', [$store->slug, $topRatedProduct->product->id]) }}">{{ $topRatedProduct->product->name }}</a>
                                            </h6>
                                            <p>{{ __('Category:') }}
                                                {{ $topRatedProduct->product->product_category() }}</b></p>
                                            <div class="price">
                                                <ins>
                                                    @if ($topRatedProduct->product->enable_product_variant == 'on')
                                                        {{ __('In variant') }}
                                                    @else
                                                        {{ \App\Models\Utility::priceFormat($topRatedProduct->product->price) }}
                                                    @endif
                                                </ins>
                                                <del>
                                                    @if ($topRatedProduct->product->enable_product_variant == 'off')
                                                        {{ \App\Models\Utility::priceFormat($topRatedProduct->product->last_price) }}
                                                    @endif
                                                </del>
                                            </div>

                                        </div>
                                        <div class="product-bottom">
                                            @if ($topRatedProduct->product->enable_product_variant == 'on')
                                                <a href="{{ route('store.product.product_view', [$store->slug, $topRatedProduct->product->id]) }}"
                                                    class="cart-btn btn">{{ __('ADD TO CART') }}</a>
                                            @else
                                                <a href="#" class="cart-btn btn add_to_cart"
                                                    data-id="{{ $topRatedProduct->product->id }}">{{ __('ADD TO CART') }}</a>
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
            @if (isset($ThemeSetting['section_name']) &&
                    $ThemeSetting['section_name'] == 'Top-Purchased' &&
                    $ThemeSetting['section_enable'] == 'on')
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
                <section class="most-purchased padding-bottom">
                    <div class="container">
                        <div class="row row-gap align-items-center">
                            <div class="col-md-6 col-12">
                                @if(!empty($mostPurchasedDetail))
                                    <div class="purchased-img">
                                        @if (!empty($mostPurchasedDetail->is_cover))
                                            <img src="{{ $productImg. $mostPurchasedDetail->is_cover }}" alt="">
                                        @else
                                            <img src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}" alt="">
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="section-desk">
                                    <div class="section-title">
                                        <h2>{{ $homepage_header_title }}</h2>
                                    </div>
                                    <p>{{ $homepage_header_subtext }}</p>
                                    <a href="{{ route('store.categorie.product', [$store->slug, 'Start shopping']) }}"
                                        class="btn"> {{ $homepage_header_btn }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        @endforeach
        @foreach ($getStoreThemeSetting as $storethemesetting)
            @if (isset($storethemesetting['section_name']) &&
                    $storethemesetting['section_name'] == 'Home-Testimonial' &&
                    $storethemesetting['array_type'] == 'inner-list' &&
                    $storethemesetting['section_enable'] == 'on')
                @php
                    $Heading_key = array_search('Heading', array_column($storethemesetting['inner-list'], 'field_name'));
                    $Heading = $storethemesetting['inner-list'][$Heading_key]['field_default_text'];

                @endphp
                <section class="testimonial-section padding-bottom">
                    <div class="container">
                        <div class="section-title">
                            <h2> {{ $Heading }}</h2>
                        </div>
                        <div class="testimonial-slider">
                            @foreach ($getStoreThemeSetting as $key => $storethemesetting)
                                @if (isset($storethemesetting['section_name']) &&
                                        $storethemesetting['section_name'] == 'Home-Testimonial' &&
                                        $storethemesetting['array_type'] == 'multi-inner-list')
                                    @if (isset($storethemesetting['homepage-testimonial-card-image']) ||
                                            isset($storethemesetting['homepage-testimonial-card-title']) ||
                                            isset($storethemesetting['homepage-testimonial-card-sub-text']) ||
                                            isset($storethemesetting['homepage-testimonial-card-description']) ||
                                            isset($storethemesetting['homepage-testimonial-card-enable']))
                                        @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                            @if ($storethemesetting['homepage-testimonial-card-enable'][$i] == 'on')
                                                <div class="testimonial-card">
                                                    <div class="testimonial-card-inner">
                                                        <p>
                                                            {{ $storethemesetting['homepage-testimonial-card-description'][$i] }}
                                                        </p>
                                                        <div class="abt-user">
                                                            <div class="user-img">
                                                                <img src="{{ $imgpath . $storethemesetting['homepage-testimonial-card-image'][$i]['field_prev_text'] }}"
                                                                    alt="">
                                                            </div>
                                                            <div class="user-dtl">
                                                                <b>{{ $storethemesetting['homepage-testimonial-card-title'][$i] }}</b>
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
                                                            <img src="{{ $imgpath . (!empty($storethemesetting['inner-list'][1]['field_default_text']) ? $storethemesetting['inner-list'][1]['field_default_text'] : 'avatar.png') }}"
                                                                alt="">
                                                        </div>
                                                        <div class="user-dtl">
                                                            <b>{{ $storethemesetting['inner-list'][2]['field_default_text'] }}</b>
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
        @if ($getStoreThemeSetting[7]['section_enable'] == 'on')
            <section class="social-media padding-bottom">
                <div class="container">
                    <div class="section-title title-center text-center">
                        <h2>{{ $getStoreThemeSetting[7]['inner-list'][1]['field_default_text'] }}</h2>
                        <p> {{ $getStoreThemeSetting[7]['inner-list'][2]['field_default_text'] }}</p>
                    </div>
                    <div class="social-media-slider">
                        @foreach ($getStoreThemeSetting as $key => $storethemesetting)
                            @if (isset($storethemesetting['section_name']) &&
                                    $storethemesetting['section_name'] == 'Home-Brand-Logo' &&
                                    $storethemesetting['section_enable'] == 'on')
                                @foreach ($storethemesetting['inner-list'] as $image)
                                    @if ($image['field_slug'] == 'homepage-brand-logo-input')
                                        @if (!empty($image['image_path']))
                                        @foreach ($image['image_path'] as $img)
                                            <div class="social-media-itm">
                                                    <div class="social-media-inner">
                                                        <a href="#">
                                                            <img src="{{ $imgpath . (!empty($img) ? $img : 'theme5/brand_logo/brand_logo.png') }}"
                                                                alt="">
                                                        </a>
                                                    </div>
                                                </div>
                                                @endforeach
                                        @else
                                            <div class="social-media-itm">
                                                <div class="social-media-inner">
                                                    <a href="#">
                                                        <img src="{{ $default }}" alt="">
                                                    </a>
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
        @foreach ($getStoreThemeSetting as $storethemesetting)
            @if (isset($storethemesetting['section_name']) &&
                    $storethemesetting['section_name'] == 'Home-Email-Subscriber' &&
                    $storethemesetting['section_enable'] == 'on')
                @php
                    $SubscriberTitle_key = array_search('Subscriber Title', array_column($storethemesetting['inner-list'], 'field_name'));
                    $SubscriberTitle = $storethemesetting['inner-list'][$SubscriberTitle_key]['field_default_text'];
                @endphp
                <section class="news-latter-section padding-bottom">
                    <div class="container">
                        <div class="newlatter-main">
                            <div class="row row-gap align-items-center">
                                <div class="col-lg-5 col-12">
                                    <div class="section-title">
                                        <h3> {{ $SubscriberTitle }}</h3>
                                    </div>
                                </div>
                                <div class="col-lg-7 col-12">
                                    <div class="newlatter-form">
                                        {{ Form::open(['route' => ['subscriptions.store_email', $store->id], 'method' => 'POST']) }}
                                        <div class="input-wrapper">
                                            {{ Form::email('email', null, ['placeholder' => __('Enter Your Email Address...')]) }}
                                            <button type="submit" class="btn-icon"><svg width="25" height="24"
                                                    viewBox="0 0 25 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_8_776)">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M0.5 12C0.5 11.4477 0.947715 11 1.5 11L21.0858 11L14.7932 4.7071C14.4026 4.31657 14.4027 3.6834 14.7932 3.29288C15.1837 2.90236 15.8169 2.90238 16.2074 3.29291L24.2071 11.2929C24.5976 11.6834 24.5976 12.3166 24.2071 12.7071L16.2074 20.7071C15.8169 21.0976 15.1837 21.0976 14.7932 20.7071C14.4027 20.3166 14.4026 19.6834 14.7932 19.2929L21.0858 13L1.5 13C0.947715 13 0.5 12.5523 0.5 12Z"
                                                            fill="white"></path>
                                                    </g>
                                                </svg></button>
                                        </div>
                                        <div class="form-text">
                                            {{ __('Enter your address and accept the activation link') }}
                                        </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        @endforeach
    </div>
@endsection

@push('script-page')
    <script>
        $("#start_shopping").click(function() {
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#shopping_section").offset().top
            }, 2000);
        });
    </script>

    <script>
        $(document).on('change', '.variant-selection', function() {

            var variants = [];

            $(this).each(function(index, element) {

                variants.push(element.value);
            });

            let product_id = $(this).closest(".card-body").find('.product_id').val();
            let variation_price = $(this).closest(".card-product").find('.variation_price');

            if (variants.length > 0) {

                $.ajax({
                    url: '{{ route('get.products.variant.quantity') }}',
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        variants: variants.join(' : '),
                        product_id: product_id
                    },
                    success: function(data) {
                        variation_price.html(data.price);
                        $('#variant_id').val(data.variant_id);
                        $('#variant_qty').val(data.quantity);
                    }
                });
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.category-options').eq(0).click();
        });

        $('.category-options').on('click', function() {

            var catNames = $(this).html();
            $('.category-dropdown').html(catNames);

            $(this).removeClass('active');
            $(this).addClass('active');

            var catNames_active = $(this).attr('data-active');
            $('.category-tab').removeClass('active');
            $('.category-tab[data-content="' + catNames_active + '"]').addClass('active');

        });
    </script>
@endpush
