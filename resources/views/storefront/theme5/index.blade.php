@extends('storefront.layout.theme5')

@section('page-title')
    {{ __('Home') }}
@endsection

@push('css-page')
    <style>
        .product-box .product-price {
            justify-content: unset;
        }
    </style>
@endpush
@php
    $imgpath = \App\Models\Utility::get_file('uploads/');
    $productImg = \App\Models\Utility::get_file('uploads/is_cover_image/');
    $catimg = \App\Models\Utility::get_file('uploads/product_image/');
    $default = \App\Models\Utility::get_file('uploads/theme4/header/brand_logo.png');
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
                    $homepage_header_title_key = array_search('Title', array_column($ThemeSetting['inner-list'], 'field_name'));
                    $homepage_header_title = $ThemeSetting['inner-list'][$homepage_header_title_key]['field_default_text'];
                    
                    $homepage_header_Sub_text_key = array_search('Sub text', array_column($ThemeSetting['inner-list'], 'field_name'));
                    $homepage_header_Sub_text = $ThemeSetting['inner-list'][$homepage_header_Sub_text_key]['field_default_text'];
                    
                    $homepage_header_Button_key = array_search('Button', array_column($ThemeSetting['inner-list'], 'field_name'));
                    $homepage_header_Button = $ThemeSetting['inner-list'][$homepage_header_Button_key]['field_default_text'];
                    
                    $homepage_header_bckground_Image_key = array_search('Background Image', array_column($ThemeSetting['inner-list'], 'field_name'));
                    $homepage_header_bckground_Image = $ThemeSetting['inner-list'][$homepage_header_bckground_Image_key]['field_default_text'];
                @endphp
                <section class="main-home-first-section padding-top padding-bottom ">
                    <div class="container">
                        <div class="row row-gap align-items-center">
                            <div class="col-lg-9 col-12">
                                <div class="row row-gap align-items-center">
                                    <div class="col-md-6 col-12">
                                        <div class="banner-content">
                                            <div class="banner-title">
                                                <h1>{{ !empty($homepage_header_title) ? $homepage_header_title : 'Home Accessories' }}
                                                </h1>
                                            </div>
                                            <p> {{ !empty($homepage_header_Sub_text) ? $homepage_header_Sub_text : 'There is only that moment and the incredible certainty that everything under the sun has been written by one hand only.' }}
                                            </p>
                                            <a href="#" class="btn" id="pro_scroll">
                                                {{ !empty($homepage_header_Button) ? $homepage_header_Button : __('Start shopping') }}
                                                <i class="fas fa-shopping-basket"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="banner-img">
                                            <img src="{{ $imgpath . $homepage_header_bckground_Image }}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($theme3_product != null)
                                <div class="col-lg-3 col-12">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-4 col-12 product-card">
                                            <div class="product-card-inner">
                                                <div class="product-content-top">
                                                    <div class="product-rating">
                                                        @if ($store->enable_rating == 'on')
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @php
                                                                    $icon = 'fa-star';
                                                                    $color = '';
                                                                    $newVal1 = $i - 0.5;
                                                                    if ($theme3_product->product_rating() < $i && $theme3_product->product_rating() >= $newVal1) {
                                                                        $icon = 'fa-star-half-alt';
                                                                    }
                                                                    if ($theme3_product->product_rating() >= $newVal1) {
                                                                        $color = 'text-primary';
                                                                    }
                                                                @endphp
                                                                <i class="star fas {{ $icon . ' ' . $color }}"></i>
                                                            @endfor
                                                        @endif
                                                    </div>
                                                    @if (Auth::guard('customers')->check())
                                                        {{--  @if ($theme3_product['enable_product_variant'] != 'on')  --}}
                                                        @if (!empty($wishlist) && isset($wishlist[$theme3_product->id]['product_id']))
                                                            @if ($wishlist[$theme3_product->id]['product_id'] != $theme3_product->id)
                                                                <a href="#"
                                                                    class="btn wishlist-btn add_to_wishlist wishlist_{{ $theme3_product->id }}"
                                                                    data-id="{{ $theme3_product->id }}"><i
                                                                        class="far fa-heart"></i></a>
                                                            @else
                                                                <a href="#" class="btn wishlist-btn"
                                                                    data-id="{{ $theme3_product->id }}" disabled><i
                                                                        class="fas fa-heart"></i></a>
                                                            @endif
                                                        @else
                                                            <a href="#"
                                                                class="btn wishlist-btn add_to_wishlist wishlist_{{ $theme3_product->id }}"
                                                                data-id="{{ $theme3_product->id }}"><i
                                                                    class="far fa-heart"></i></a>
                                                        @endif
                                                        {{--  @endif  --}}
                                                    @else
                                                        <a href="#"
                                                            class="btn wishlist-btn add_to_wishlist wishlist_{{ $theme3_product->id }}"
                                                            data-id="{{ $theme3_product->id }}"><i
                                                                class="far fa-heart"></i></a>
                                                    @endif
                                                </div>
                                                <div class="product-img">
                                                    <a
                                                        href="{{ route('store.product.product_view', [$store->slug, $theme3_product->id]) }}">
                                                        @if ($theme3_product_image->count() > 0)
                                                            <img src="{{ $catimg . $theme3_product_image[0]['product_images'] }}"
                                                                alt="">
                                                        @else
                                                            <img src="{{ asset(Storage::url('uploads/product_image/default.jpg')) }}"
                                                                alt="">
                                                        @endif
                                                    </a>
                                                </div>
                                                <div class="product-content">
                                                    <h5>
                                                        <a
                                                            href="{{ route('store.product.product_view', [$store->slug, $theme3_product->id]) }}">{{ $theme3_product->name }}</a>
                                                    </h5>
                                                    @if ($theme3_product['enable_product_variant'] != 'on')
                                                        <div class="price">
                                                            <ins><span
                                                                    class="currency-type"></span>{{ \App\Models\Utility::priceFormat($theme3_product->price) }}
                                                            </ins>
                                                        </div>
                                                        <div class="product-content-bottom">
                                                            <a href="#" class="btn cart-btn add_to_cart"
                                                                data-id="{{ $theme3_product['id'] }}"><i
                                                                    class="fas fa-shopping-basket"></i></a>
                                                        </div>
                                                    @else
                                                        <div class="price">
                                                            <ins><span
                                                                    class="currency-type"></span>{{ __('In Variant') }}</ins>
                                                        </div>
                                                        <div class="product-content-bottom">
                                                            <a href="{{ route('store.product.product_view', [$store->slug, $theme3_product['id']]) }}"
                                                                class="btn cart-btn"><i class="fas fa-shopping-basket"></i>
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </section>
            @endif
        @endforeach
        <section class="our-client-section padding-bottom">
            <div class="container">
                <div class="client-logo-slider">
                    @foreach ($getStoreThemeSetting as $key => $storethemesetting)
                        @if (isset($storethemesetting['section_name']) &&
                                $storethemesetting['section_name'] == 'Home-Brand-Logo' &&
                                $storethemesetting['section_enable'] == 'on')
                            @foreach ($storethemesetting['inner-list'] as $image)
                                @if (!empty($image['image_path']))
                                    @foreach ($image['image_path'] as $img)
                                        <div class="client-logo-item">
                                            <a href="#">
                                                <img src="{{ $imgpath . (!empty($img) ? $img : 'theme5/brand_logo/brand_logo.png') }}"
                                                    alt="">
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="client-logo-item">
                                        <a href="#">
                                            <img src="{{ $default }}" alt="">
                                        </a>
                                    </div>
                                    <div class="client-logo-item">
                                        <a href="#">
                                            <img src="{{ $default }}" alt="">
                                        </a>
                                    </div>
                                    <div class="client-logo-item">
                                        <a href="#">
                                            <img src="{{ $default }}" alt="">
                                        </a>
                                    </div>
                                    <div class="client-logo-item">
                                        <a href="#">
                                            <img src="{{ $default }}" alt="">
                                        </a>
                                    </div>
                                    <div class="client-logo-item">
                                        <a href="#">
                                            <img src="{{ $default }}" alt="">
                                        </a>
                                    </div>
                                    <div class="client-logo-item">
                                        <a href="#">
                                            <img src="{{ $default }}" alt="">
                                        </a>
                                    </div>
                                    <div class="client-logo-item">
                                        <a href="#">
                                            <img src="{{ $default }}" alt="">
                                        </a>
                                    </div>
                                    <div class="client-logo-item">
                                        <a href="#">
                                            <img src="{{ $default }}" alt="">
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
        @if ($products['Start shopping']->count() > 0)
            <section class="product-section padding-bottom" id="pro_items">
                <div class="container">
                    <div class="section-title d-flex align-items-center justify-content-between">
                        <h2>{{ __('Products') }}</h2>
                        <a href="{{ route('store.categorie.product', [$store->slug, 'Start shopping']) }}"
                            class="btn">{{ __('Show more products') }} <i class="fas fa-shopping-basket"></i></a>
                    </div>
                    <div class="tabs-wrapper">
                        <ul class="d-flex tabs">
                            @foreach ($categories as $key => $category)
                                <li class="tab-link {{ $key == 0 ? 'active' : '' }}"
                                    data-tab="tab-{!! preg_replace('/[^A-Za-z0-9\-]/', '_', $category) !!}">
                                    <a> {{ __($category) }}</a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tabs-container">
                            @foreach ($products as $key => $items)
                                <div class="tab-content {{ $key == 'Start shopping' ? 'active' : '' }}"
                                    id="tab-{!! preg_replace('/[^A-Za-z0-9\-]/', '_', $key) !!}">
                                    <div class="row product-row">
                                        @if ($items->count() > 0)
                                            @foreach ($items as $product)
                                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 product-card">
                                                    <div class="product-card-inner">
                                                        <div class="product-content-top">
                                                            <div class="product-rating">
                                                                @if ($store->enable_rating == 'on')
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        @php
                                                                            $icon = 'fa-star';
                                                                            $color = '';
                                                                            $newVal1 = $i - 0.5;
                                                                            if ($product->product_rating() < $i && $product->product_rating() >= $newVal1) {
                                                                                $icon = 'fa-star-half-alt';
                                                                            }
                                                                            if ($product->product_rating() >= $newVal1) {
                                                                                $color = 'text-primary';
                                                                            }
                                                                        @endphp
                                                                        <i class="star fas {{ $icon . ' ' . $color }}"></i>
                                                                    @endfor
                                                                @endif
                                                            </div>
                                                            @if (Auth::guard('customers')->check())
                                                                @if (!empty($wishlist) && isset($wishlist[$product->id]['product_id']))
                                                                    @if ($wishlist[$product->id]['product_id'] != $product->id)
                                                                        <a class="btn wishlist-btn add_to_wishlist wishlist_{{ $product->id }}"
                                                                            data-id="{{ $product->id }}"><i
                                                                                class="far fa-heart"></i></a>
                                                                    @else
                                                                        <a class="btn wishlist-btn"
                                                                            data-id="{{ $product->id }}"><i
                                                                                class="fas fa-heart"></i></a>
                                                                    @endif
                                                                @else
                                                                    <a class="btn wishlist-btn add_to_wishlist wishlist_{{ $product->id }}"
                                                                        data-id="{{ $product->id }}"><i
                                                                            class="far fa-heart"></i></a>
                                                                @endif
                                                            @else
                                                                <a class="btn wishlist-btn add_to_wishlist wishlist_{{ $product->id }}"
                                                                    data-id="{{ $product->id }}"><i
                                                                        class="far fa-heart"></i></a>
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
                                                            <h5>
                                                                <a
                                                                    href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}">{{ $product->name }}</a>
                                                            </h5>
                                                            @if ($product['enable_product_variant'] == 'on')
                                                                <div class="price">
                                                                    <ins>{{ __('In variant') }}</ins>
                                                                </div>
                                                                <div class="product-content-bottom">
                                                                    <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}"
                                                                        class="btn cart-btn"><i
                                                                            class="fas fa-shopping-basket"></i></a>
                                                                </div>
                                                            @else
                                                                <div class="price">
                                                                    <ins>{{ \App\Models\Utility::priceFormat($product->price) }}
                                                                    </ins>
                                                                </div>
                                                                <div class="product-content-bottom">
                                                                    <a class="btn cart-btn add_to_cart"
                                                                        data-id="{{ $product->id }}"><i
                                                                            class="fas fa-shopping-basket"></i></a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 product-card">
                                                <h6 class="no_record"><i class="fas fa-ban"></i>
                                                    {{ __('No Record Found') }}</h6>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @else
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 product-card">
                <h6 class="no_record"><i class="fas fa-ban"></i> {{ __('No Record Found') }}</h6>
            </div>
        @endif

        @foreach ($getStoreThemeSetting as $storethemesetting)
            @if (isset($storethemesetting['section_name']) &&
                    $storethemesetting['section_name'] == 'Home-Email-Subscriber' &&
                    $storethemesetting['section_enable'] == 'on')
                @php
                    // dd($storethemesetting);
                    $emailsubs_img_key = array_search('Subscriber Background Image', array_column($storethemesetting['inner-list'], 'field_name'));
                    $emailsubs_img = $storethemesetting['inner-list'][$emailsubs_img_key]['field_default_text'];
                    
                    $SubscriberTitle_key = array_search('Subscriber Title', array_column($storethemesetting['inner-list'], 'field_name'));
                    $SubscriberTitle = $storethemesetting['inner-list'][$SubscriberTitle_key]['field_default_text'];
                    
                    $SubscriberDescription_key = array_search('Subscriber Description', array_column($storethemesetting['inner-list'], 'field_name'));
                    $SubscriberDescription = $storethemesetting['inner-list'][$SubscriberDescription_key]['field_default_text'];
                    
                    $SubscribeButton_key = array_search('Subscribe Button Text', array_column($storethemesetting['inner-list'], 'field_name'));
                    $SubscribeButton = $storethemesetting['inner-list'][$SubscribeButton_key]['field_default_text'];
                @endphp

                <section class="newslatter-section padding-bottom">
                    <div class="container">
                        <div class="section-title title-center text-center">
                            <h2> {{ !empty($SubscriberTitle) ? $SubscriberTitle : 'Always on time' }}</h2>
                            <p> {{ !empty($SubscriberDescription) ? $SubscriberDescription : 'Subscription here' }}.</p>
                        </div>
                        {{ Form::open(['route' => ['subscriptions.store_email', $store->id], 'method' => 'POST']) }}
                        <div class="newslatter-form">
                            <div class="input-wrapper">
                                {{ Form::email('email', null, ['class' => 'form-control form-control-flush', 'aria-label' => 'Enter your email address', 'placeholder' => __('Enter Your Email Address')]) }}
                                <button type="submit" class="btn">{{ $SubscribeButton }} <i
                                        class="far fa-paper-plane"></i></button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </section>
            @endif
        @endforeach

        @if (count($topRatedProducts) > 0)
            <section class="total-rated-product padding-bottom">
                <div class="container">
                    <div class="section-title d-flex align-items-center justify-content-between">
                        <h2 class="h1">{{ __('Top rated products') }}</h2>
                        <a href="{{ route('store.categorie.product', $store->slug) }}" class="btn">{{ __('Show more products') }}</a>
                    </div>
                    <div class="row product-row">
                        @foreach ($topRatedProducts as $k => $topRatedProduct)
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 product-card">
                                <div class="product-card-inner">
                                    <div class="product-content-top">
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
                                        <h5>
                                            <a href="{{ route('store.product.product_view', [$store->slug, $topRatedProduct->product_id]) }}">{{ $topRatedProduct->product->name }}</a>
                                        </h5>
                                        <div class="price">
                                            <ins>
                                                @if ($topRatedProduct->product->enable_product_variant == 'on')
                                                    {{ __('In variant') }}
                                                @else
                                                    {{ \App\Models\Utility::priceFormat($topRatedProduct->product->price) }}
                                                @endif
                                            </ins>
                                        </div>
                                        <div class="product-content-bottom">
                                            @if ($topRatedProduct->product->enable_product_variant == 'on')
                                                <a href="{{ route('store.product.product_view', [$store->slug, $topRatedProduct->product->id]) }}" class="btn cart-btn"><i class="fas fa-shopping-basket"></i></a>
                                            @else
                                                <a data-id="{{ $topRatedProduct->product->id }}" class="btn cart-btn add_to_cart"><i class="fas fa-shopping-basket"></i></a>
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


        @foreach ($getStoreThemeSetting as $storethemesetting)
            @if (isset($storethemesetting['section_name']) &&
                    $storethemesetting['section_name'] == 'Home-Testimonial' &&
                    $storethemesetting['array_type'] == 'inner-list' &&
                    $storethemesetting['section_enable'] == 'on')
                <section class="testimonial-section padding-bottom">
                    <div class="container">
                        <div class="row row-gap align-items-center">
                            <div class="col-lg-4 col-12 testimonial-left">
                                <div class="section-title d-flex align-items-center justify-content-between">
                                    @php
                                        $Heading_key = array_search('Heading', array_column($storethemesetting['inner-list'], 'field_name'));
                                        $Heading = $storethemesetting['inner-list'][$Heading_key]['field_default_text'];
                                        
                                        $HeadingSubText_key = array_search('Heading Sub Text', array_column($storethemesetting['inner-list'], 'field_name'));
                                        $HeadingSubText = $storethemesetting['inner-list'][$HeadingSubText_key]['field_default_text'];
                                    @endphp
                                    <h2> {{ !empty($Heading) ? $Heading : 'Testimonials' }}</h2>
                                    <p> {{ !empty($HeadingSubText) ? $HeadingSubText : 'There is only that moment and the incredible certainty that <br> everything  under the sun has been written by one hand only.' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-8 col-12">
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
                                                                        <b>
                                                                            {{ $storethemesetting['homepage-testimonial-card-title'][$i] }}</b>
                                                                        <small>{{ $storethemesetting['homepage-testimonial-card-sub-text'][$i] }}</small>
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
                                                                {{ $storethemesetting['inner-list'][4]['field_default_text'] }}
                                                            </p>
                                                            <div class="abt-user">
                                                                <div class="user-img">
                                                                    <img src="{{ $imgpath . (!empty($storethemesetting['inner-list'][1]['field_default_text']) ? $storethemesetting['inner-list'][1]['field_default_text'] : 'avatar.png') }}"
                                                                        alt="">
                                                                </div>
                                                                <div class="user-dtl">
                                                                    <b>
                                                                        {{ $storethemesetting['inner-list'][2]['field_default_text'] }}</b>
                                                                    <small>{{ $storethemesetting['inner-list'][3]['field_default_text'] }}</small>
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
                        </div>
                    </div>
                </section>
            @endif
        @endforeach
        <section class="store-promotions-section padding-bottom">
            <div class="container">
                <div class="row row-gap justify-content-center">
                    @foreach ($getStoreThemeSetting as $key => $storethemesetting)
                        @if ($storethemesetting['section_name'] == 'Home-Promotions' && $storethemesetting['section_enable'] == 'on')
                            @if (isset($storethemesetting['homepage-promotions-font-icon']) ||
                                    isset($storethemesetting['homepage-promotions-title']) ||
                                    isset($storethemesetting['homepage-promotions-description']))
                                @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                    <div class="col-lg-4 col-md-6 col-12">
                                        <div class="about-promotions">
                                            <h3>{!! $storethemesetting['homepage-promotions-font-icon'][$i] !!}
                                                {{ $storethemesetting['homepage-promotions-title'][$i] }}</h3>
                                            <p>{{ $storethemesetting['homepage-promotions-description'][$i] }}</p>
                                        </div>
                                    </div>
                                @endfor
                            @else
                                @for ($i = 0; $i < $storethemesetting['loop_number']; $i++)
                                    <div class="col-lg-4 col-md-6 col-12">
                                        <div class="about-promotions">
                                            <h3>{!! $storethemesetting['inner-list'][0]['field_default_text'] !!}
                                                {{ $storethemesetting['inner-list'][1]['field_default_text'] }}</h3>
                                            <p> {{ $storethemesetting['inner-list'][2]['field_default_text'] }}</p>
                                        </div>
                                    </div>
                                @endfor
                            @endif
                        @endif
                    @endforeach
                </div>
            </div>
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
                        <div class="bestseller-title">
                            <div class="section-title">
                                <h2> {{ !empty($Title) ? $Title : 'Categories' }}</h2>
                                <p>{{ !empty($Description) ? $Description : 'There is only that moment and the incredible certainty <br> that everything under the sun has been written by one hand only.' }}</p>
                                </div>    
                                <a href="{{ route('store.categorie.product', [$store->slug, 'Start shopping']) }}" class="btn cart-btn">{{ __('Show more products') }}
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
                                                <p>{{ __('Products') }}:  {{ !empty($product_count[$key]) ? $product_count[$key] : '0' }}</p>
                                                <a href="{{ route('store.categorie.product', [$store->slug, $pro_categorie->name]) }}" class="btn cart-btn">{{ __('Show more products') }}
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
                url: '{{ route('user.addToCart', ['__product_id', $store->slug, 'variation_id']) }}'
                    .replace(
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
