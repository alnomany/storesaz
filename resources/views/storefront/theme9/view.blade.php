@extends('storefront.layout.theme9')

@section('page-title')
    {{ __('Home') }}
@endsection

@push('css-page')
    <style>
        [dir="rtl"] .slick-slide {
            float: right;
        }
    </style>
@endpush
@php
$imgpath=\App\Models\Utility::get_file('uploads/product_image/');
$proimg=\App\Models\Utility::get_file('uploads/is_cover_image/');

@endphp
@section('content')
<div class="wrapper">
    <section class="product-detail-section padding-top">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="product-slider-det">
                        <div class="pdp-det-slider">
                            {{--  <div class="pdp-main-itm">  --}}
                                @foreach ($products_image as $key => $productss)
                                    <div class="pdp-main-itm">
                                        <div class="pdp-main-media">
                                            @if (!empty($products_image[$key]->product_images))
                                                <img src="{{ $imgpath . $products_image[$key]->product_images }}">
                                            @else
                                                <img src="{{ asset(Storage::url('uploads/product_image/default.jpg')) }}">
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            {{--  </div>  --}}
                        </div>
                    </div>
                    <div class="product-thumb-slider">
                        @foreach ($products_image as $key => $productss)
                            <div class="product-thumb-itm">
                                <div class="pdp-thumb-inner">
                                    @if (!empty($products_image[$key]->product_images))
                                        <img src="{{ $imgpath. $products_image[$key]->product_images }}" alt="product">
                                    @else
                                        <img src="{{ asset(Storage::url('uploads/product_image/default.jpg')) }}" alt="product">
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="product-detail">
                        <div class="product-review">
                            <span class="static-rating static-rating-sm d-block">
                                @if ($store_setting->enable_rating == 'on')
                                    @for ($i = 1; $i <= 5; $i++)
                                        @php
                                            $icon = 'fa-star';
                                            $color = '';
                                            $newVal1 = $i - 0.5;
                                            if ($avg_rating < $i && $avg_rating >= $newVal1) {
                                                $icon = 'fa-star-half-alt';
                                            }
                                            if ($avg_rating >= $newVal1) {
                                                $color = 'text-primary';
                                            }
                                        @endphp
                                        <i class="star fas {{ $icon . ' ' . $color }}"></i>
                                    @endfor
                                @endif
                            </span>
                            <p>{{ $avg_rating }}/5 ({{ $user_count }} {{ __('reviews') }})</p>
                            @if (Auth::guard('customers')->check())
                                @if (!empty($wishlist) && isset($wishlist[$products->id]['product_id']))
                                    @if ($wishlist[$products->id]['product_id'] != $products->id)
                                        <a class=" wishlist-btn add_to_wishlist wishlist_{{ $products->id }}" data-id="{{ $products->id }}"><i class="far fa-heart"></i></a>
                                    @else
                                        <a class=" wishlist-btn wishlist_{{ $products->id }}"><i class="fas fa-heart"></i></a>
                                    @endif
                                @else
                                    <a class=" wishlist-btn add_to_wishlist wishlist_{{ $products->id }}" data-id="{{ $products->id }}"><i class="far fa-heart"></i></a>
                                @endif
                            @else
                                <a href="#" class=" wishlist-btn add_to_wishlist wishlist_{{ $products->id }}" data-id="{{ $products->id }}"><i class="far fa-heart"></i></a>
                            @endif
                        </div>
                        <p>{{ __('Category') }}: {{ $product_categorie }}</p>
                        <h2>{{ $products->name }}</h2>
                        <p> {!! $products->detail !!}
                        </p>
                        @if ($products->enable_product_variant == 'on')
                            <input type="hidden" id="product_id" value="{{ $products->id }}">
                            <input type="hidden" id="variant_id" value="">
                            <input type="hidden" id="variant_qty" value="">
                            <div class="col-md-5">
                               
                                @foreach ($product_variant_names as $key => $variant)
                                <div class="dropdown w-100">
                                    <p class="mb-0 variant_name">
                                        {{ empty($variant->variant_name) ? $variant['variant_name'] : $variant->variant_name }}
                                    </p>
                                    <select name="product[{{ $key }}]" id="pro_variants_name"
                                        class="form-control d-flex font-size-12 font-weight-400 justify-content-between px-3 rounded-0 w-100 variant-selection  pro_variants_name{{ $key }} pro_variants_name variant_loop variant_val">

                                        @foreach ($variant->variant_options ?? $variant['variant_options'] as $key => $values)
                                            <option value="{{ $values }}"
                                                id="{{ $values }}_varient_option">{{ $values }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            @endforeach
                            </div>
                        @endif
                        <div class="product-price">
                            <div class="price variation_price">
                                <ins>
                                    @if ($products->enable_product_variant == 'on')
                                        {{ \App\Models\Utility::priceFormat(0) }}
                                    @else
                                        {{ \App\Models\Utility::priceFormat($products->price) }}
                                    @endif
                                </ins>
                                <del>{{ \App\Models\Utility::priceFormat($products->last_price) }}</del>
                            </div>
                            <a href="#" class="btn add_to_cart" data-id="{{ $products->id }}">{{ __('Add to cart') }}</a>
                        </div>
                        <span class=" mb-0 text-danger product-price-error"></span>
                        <ul>
                            <li><span>{{ __('Category') }}:</span> {{ $product_categorie }}<span>{{ __('SKU') }}:</span> {{ $products->SKU }}</li>
                            @if (!empty($products->custom_field_1) && !empty($products->custom_value_1))
                                <li><span>{{ $products->custom_field_1 }} : </span>  {{ $products->custom_value_1 }}</li>
                            @endif
                            @if (!empty($products->custom_field_2) && !empty($products->custom_value_2))
                                <li><span>{{ $products->custom_field_2 }} : </span>  {{ $products->custom_value_2 }}</li>
                            @endif
                            @if (!empty($products->custom_field_3) && !empty($products->custom_value_3))
                                <li><span>{{ $products->custom_field_3 }} : </span>  {{ $products->custom_value_3 }}</li>
                            @endif
                            @if (!empty($products->custom_field_4) && !empty($products->custom_value_4))
                                <li><span>{{ $products->custom_field_4 }} : </span>  {{ $products->custom_value_4 }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>    
    <section class="product-detail padding-top padding-bottom">
        <div class="container">
            <div class="row product-detail-row">
                <div class="col-md-6 col-12">
                    <div class="pdp-review">
                        <div class="product-review">
                            <span class="static-rating static-rating-sm d-block">
                                @if ($store_setting->enable_rating == 'on')
                                    @for ($i = 1; $i <= 5; $i++)
                                        @php
                                            $icon = 'fa-star';
                                            $color = '';
                                            $newVal1 = $i - 0.5;
                                            if ($avg_rating < $i && $avg_rating >= $newVal1) {
                                                $icon = 'fa-star-half-alt';
                                            }
                                            if ($avg_rating >= $newVal1) {
                                                $color = 'text-primary';
                                            }
                                        @endphp
                                        <i class="star fas {{ $icon . ' ' . $color }}"></i>
                                    @endfor
                                @endif
                            </span>
                            <p>{{ $avg_rating }}/5 ({{ $user_count }} {{ __('reviews') }})</p>
                            
                        </div>
                        <div class="review-box-2">
                            <h5>{{ __('Reviews') }}:
                                 <b> {{ $avg_rating }}/5</b>
                                <span> ({{ $user_count }} {{ __('reviews') }})</span>
                            </h5>
                            @if (Auth::guard('customers')->check())
                                <a href="#" class=" btn-sm btn-primary btn-icon-only rounded-circle float-right text-white" data-size="lg" data-toggle="modal" data-url="{{route('rating',[$store->slug,$products->id])}}" data-ajax-popup="true" data-title="{{__('Create New Rating')}}">
                                    <i class="fas fa-plus"></i>
                                </a>
                            @endif
                        </div>
                        <div class="review-box-bottom">
                            @foreach ($product_ratings as $product_key => $product_rating)
                                @if ($product_rating->rating_view == 'on')
                                    <div class="review-top d-flex">
                                        <p>{{ $product_rating->name }} :</p>
                                        <span>{{ $product_rating->title }}</span>
                                    </div>
                                    <div class="rating-pdp">
                                        <span>
                                            @for ($i = 0; $i < 5; $i++)
                                                <i class="star fas fa-star {{ $product_rating->ratting > $i ? 'text-primary' : '' }}"></i>
                                            @endfor
                                        </span>
                                        <p>{{ $avg_rating }}/5 ({{ $user_count }} {{ __('reviews') }})</p>
                                    </div>
                                    <p>{{ $product_rating->description }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="descripction">
                        @if (!empty($products->description))
                            <div class="set has-children is-open">
                                <a href="javascript:;" class="acnav-label">
                                    <span>{{ __('DESCRIPTION') }}</span> 
                                </a>
                                <div class="acnav-list" style="display: block; overflow-y: scroll; max-height: 290px;">
                                    <p>{!! $products->description !!}</p>
                                </div>
                            </div>
                        @endif
                        @if (!empty($products->specification))
                            <div class="set has-children">
                                <a href="javascript:;" class="acnav-label">
                                    <span> {{ __('SPECIFICATION') }}</span> 
                                </a>
                                <div class="acnav-list" style=" overflow-y: scroll; max-height: 290px;">
                                    <p>{!! $products->specification !!}</p>
                                </div>
                            </div>
                        @endif
                        @if (!empty($products->detail))
                            <div class="set has-children">
                                <a href="javascript:;" class="acnav-label">
                                    <span> {{ __('DETAILS') }}</span> 
                                </a>
                                <div class="acnav-list" style=" overflow-y: scroll; max-height: 290px;">
                                    <p>{!! $products->detail !!}</p>
                                </div>
                            </div>
                        @endif
                        @if(!empty($products->attachment))
                            <div class="set has-children">
                                <a href="javascript:;" class="acnav-label">
                                    <span> {{__('Download instruction ')}}</span>
                                </a>
                                <div class="acnav-list">
                                    <div class="btn">
                                        <a href="{{asset(Storage::url('uploads/is_cover_image/'.$products->attachment))}}" class="btn-instruction" download="{{$products->attachment}}">
                                            <span class="btn-inner--icon">
                                                <i class="fas fa-shopping-basket"></i>
                                            </span>
                                            {{__('Download instruction .pdf')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="related-product padding-bottom">
        <div class="container">
            <div class="section-title">
                <h2>{{ __('Related Products') }}</h2>
            </div>
            <div class="row product-row">
                @foreach ($all_products as $key => $product)
                    @if ($product->id != $products->id)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="product-card">
                                <div class="product-card-inner">
                                    <div class="product-content-top d-flex  justify-content-between ">
                                        @if (Auth::guard('customers')->check())
                                            @if (!empty($wishlist) && isset($wishlist[$product->id]['product_id']))
                                                @if ($wishlist[$product->id]['product_id'] != $product->id)
                                                    <a href="#" class="wish-btn add_to_wishlist wishlist_{{ $product->id }}" data-id="{{ $product->id }}"><i class="far fa-heart"></i></a>
                                                @else
                                                    <a href="#" class="wish-btn"><i class="fas fa-heart" data-id="{{ $product->id }}"></i></a>
                                                @endif
                                            @else
                                                <a href="#" class="wish-btn add_to_wishlist wishlist_{{ $product->id }}" data-id="{{ $product->id }}"><i class="far fa-heart"></i></a>
                                            @endif
                                        @else
                                            <a href="#" class="wish-btn add_to_wishlist wishlist_{{ $product->id }}" data-id="{{ $product->id }}"><i class="far fa-heart"></i></a>
                                        @endif
                                    </div>
                                    <div class="product-img">
                                        <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}">
                                            @if (!empty($product->is_cover) )
                                                <img src="{{ $proimg. $product->is_cover }}" alt="">
                                            @else
                                                <img src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}" alt="">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="product-content">
                                        <h6>
                                            <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}">
                                                {{ $product->name }}
                                            </a>
                                        </h6>
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
                                        <div class="review-card">
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-1">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.6846 1.54449C6.14218 0.144023 8.10781 0.144027 8.5654 1.54449L9.38429 4.05075C9.41301 4.13865 9.4936 4.19876 9.5854 4.20075L12.1696 4.25677C13.5864 4.28749 14.191 6.08576 13.0846 6.97816L10.9486 8.70091C10.8804 8.75594 10.8516 8.84689 10.8757 8.93155L11.6348 11.6008C12.0332 13.0019 10.4448 14.1165 9.27925 13.2537L7.25325 11.7538C7.17694 11.6973 7.07306 11.6973 6.99675 11.7538L4.97074 13.2537C3.80522 14.1165 2.21676 13.0019 2.61521 11.6008L3.37428 8.93155C3.39835 8.84689 3.36961 8.75594 3.30138 8.70091L1.16544 6.97816C0.0590049 6.08576 0.663576 4.28749 2.08036 4.25677L4.6646 4.20075C4.7564 4.19876 4.83699 4.13865 4.86571 4.05075L5.6846 1.54449ZM7.33077 1.95425C7.2654 1.75419 6.9846 1.75419 6.91923 1.95425L6.10034 4.46051C5.89929 5.07584 5.33518 5.49658 4.69255 5.51051L2.10831 5.56653C1.90592 5.57092 1.81955 5.82782 1.97761 5.9553L4.11356 7.67806C4.59113 8.06325 4.79234 8.69988 4.62381 9.2925L3.86474 11.9617C3.80782 12.1619 4.03475 12.3211 4.20125 12.1978L6.22726 10.698C6.76144 10.3025 7.48855 10.3025 8.02274 10.698L10.0487 12.1978C10.2153 12.3211 10.4422 12.1619 10.3853 11.9617L9.62618 9.2925C9.45765 8.69988 9.65887 8.06324 10.1364 7.67806L12.2724 5.9553C12.4305 5.82782 12.3441 5.57092 12.1417 5.56653L9.55745 5.51051C8.91482 5.49658 8.35071 5.07584 8.14966 4.46051L7.33077 1.95425Z" fill="#8A8A8A"></path>
                                            </svg>
                                            <span>{{ $avg_rating }}/{{ __('5.0') }}</span> 
                                        </div>
                                    </div>
                                    <div class="product-bottom">
                                        @if ($product->enable_product_variant == 'on')
                                            <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}" class="cart-btn btn"> {{ __('ADD TO CART') }}</a>
                                        @else
                                            <a href="#" class="cart-btn btn add_to_cart" data-id="{{ $product->id }}"> {{ __('ADD TO CART') }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
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
        set_variant_price();
    });
    $(document).on('change', '#pro_variants_name', function() {
        set_variant_price();
    });

    function set_variant_price() {
        var variants = [];
        $(".variant-selection").each(function(index, element) {
            variants.push(element.value);
        });

        if (variants.length > 0) {
            $.ajax({
                url: '{{ route('get.products.variant.quantity') }}',
                data: {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    variants: variants.join(' : '),
                    product_id: $('#product_id').val()
                },

                success: function(data) {
                    $('.product-price-error').hide();
                    $('.product-price').show();

                    $('.variation_price').html(data.price);
                    $('#variant_id').val(data.variant_id);
                    $('#variant_qty').val(data.quantity);


                    var variant_message_array = [];
                    $(".variant_loop").each(function(index) {
                        var variant_name = $(this).prev().text();
                        var variant_val = $(this).val();
                        variant_message_array.push(variant_val + " " + variant_name);
                    });
                    var variant_message = variant_message_array.join(" and ");

                    if (data.variant_id == 0) {
                        $('.add_to_cart').hide();

                        $('.product-price').hide();
                        $('.product-price-error').show();
                        var message =
                            '<span class=" mb-0 text-danger">This product is not available with ' +
                            variant_message + '.</span>';
                        $('.product-price-error').html(message);
                    }else{
                        $('.add_to_cart').show();

                    }
                }
            });
        }
    }
</script>
@endpush
