@extends('storefront.layout.theme5')
@section('page-title')
    {{__('Product Details')}}
@endsection
@php
$imgpath=\App\Models\Utility::get_file('uploads/product_image/');
$proimg=\App\Models\Utility::get_file('uploads/is_cover_image/');

@endphp
@push('css-page')
    <style>
        [dir="rtl"] .slick-slide {
            float: right;
        }
    </style>
@endpush
@section('content')
<div class="wrapper">
    <section class="product-main-section padding-bottom padding-top">
        <div class="container">
            <div class="row row-gap">
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="pdp-summery">
                        <div class="section-title">
                            <h2>{{ $products->name }}</h2>
                        </div>
                        <p>{!! $products->description !!}
                        </p>
                        @if ($products->enable_product_variant == 'on')
                            <input type="hidden" id="product_id" value="{{ $products->id }}">
                            <input type="hidden" id="variant_id" value="">
                            <input type="hidden" id="variant_qty" value="">
                            <div class="p-color mt-3">
                                <p class="mb-0">{{ __('VARIATION:') }}</p>

                                @foreach ($product_variant_names as $key => $variant)
                                    <div class="col-sm-6 mb-4 mb-sm-0">
                                        <p class="d-block h6 mb-0">
                                        <p class="mb-0 variant_name">
                                            {{ empty($variant->variant_name) ? $variant['variant_name'] : $variant->variant_name }}
                                        </p>

                                        <select name="product[{{ $key }}]" id="pro_variants_name"
                                            class="form-control variant-selection  pro_variants_name{{ $key }} pro_variants_name variant_loop variant_val">
                                            {{-- <option value="">{{ __('Select') }}</option> --}}
                                            @foreach ($variant->variant_options ?? $variant['variant_options'] as $key => $values)
                                                <option value="{{ $values }}"
                                                    id="{{ $values }}_varient_option">{{ $values }}
                                                </option>
                                            @endforeach
                                        </select>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <div class="cart-price d-flex align-items-center product-price">
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
                            <a href="#" class="btn add_to_cart" data-id="{{ $products->id }}">{{ __('Add to cart') }} <i class="fas fa-shopping-basket"></i></a>
                        </div>
                        <span class=" mb-0 text-danger product-price-error"></span>
                        <ul class="product-variables">
                            <li>
                                <span class="var-left"><b>{{ __('Category') }}:</b></span>
                                <span class="var-right">{{ $products->product_category() }}</span>
                            </li>
                            <li>
                                <span class="var-left"><b>{{ __('SKU') }}:</b> </span>
                                <span class="var-right">{{ $products->SKU }}</span>
                            </li>
                            @if (!empty($products->custom_field_1) && !empty($products->custom_value_1))
                                <li>
                                    <span class="var-left"><b>{{ $products->custom_field_1 }} :</b> </span>
                                    <span class="var-right">{{ $products->custom_value_1 }}</span>
                                </li>
                            @endif
                            @if (!empty($products->custom_field_2) && !empty($products->custom_value_2))
                                <li>
                                    <span class="var-left"><b>{{ $products->custom_field_2 }} :</b> </span>
                                    <span class="var-right">{{ $products->custom_value_2 }}</span>
                                </li>
                            @endif
                            @if (!empty($products->custom_field_3) && !empty($products->custom_value_3))
                                <li>
                                    <span class="var-left"><b>{{ $products->custom_field_3 }} :</b> </span>
                                    <span class="var-right">{{ $products->custom_value_3 }}</span>
                                </li>
                            @endif
                            @if (!empty($products->custom_field_4) && !empty($products->custom_value_4))
                                <li>
                                    <span class="var-left"><b>{{ $products->custom_field_4 }} :</b> </span>
                                    <span class="var-right">{{ $products->custom_value_4 }}</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="customer-product-review">
                        <div class="review-title">{{__('Reviews')}}: <span class="total-rates">{{$avg_rating}}/5</span><span
                                class="t-gray"> ({{__('reviews')}})</span></div>
                        <div class="review-btn-star d-flex align-items-center">
                            <div class="product-rating">
                                @if($store_setting->enable_rating == 'on')
                                    @for($i =1;$i<=5;$i++)
                                        @php
                                            $icon = 'fa-star';
                                            $color = '';
                                            $newVal1 = ($i-0.5);
                                            if($avg_rating < $i && $avg_rating >= $newVal1)
                                            {
                                                $icon = 'fa-star-half-alt';
                                            }
                                            if($avg_rating >= $newVal1)
                                            {
                                                $color = 'text-primary';
                                            }
                                        @endphp
                                        <i class="star fas {{$icon .' '. $color}}"></i>
                                    @endfor
                                @endif
                            </div>
                            @if(Auth::guard('customers')->check())
                            <a href="#" class="btn btn-primary rounded-pill btn-icon shadow hover-shadow-lg hover-translate-y-n3" data-size="lg" data-toggle="modal" data-url="{{route('rating',[$store->slug,$products->id])}}" data-ajax-popup="true" data-title="{{__('Create New Rating')}}">
                                <i class="fas fa-plus"></i>
                            </a>
                            @endif
                        </div>
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
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="pdp-sliders-wrapper">
                        <div class="pdp-main-slider">
                            @foreach($products_image as $key => $productss)
                                <div class="pdp-main-itm">
                                    <div class="pdp-main-media">
                                        @if(!empty($products_image[$key]->product_images))
                                            <img src="{{$imgpath.$products_image[$key]->product_images}}" alt="">
                                        @else
                                            <img src="{{asset(Storage::url('uploads/product_image/default.jpg'))}}" alt="">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="pdp-thumb-slider">
                            @foreach($products_image as $key => $productss)
                                <div class="pdp-thumb-itm">
                                    <div class="pdp-thumb-media">
                                        @if(!empty($products_image[$key]->product_images) )
                                            <img src="{{$imgpath.$products_image[$key]->product_images}}" alt="">
                                        @else
                                            <img src="{{asset(Storage::url('uploads/product_image/default.jpg'))}}" alt="">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="description-accordion">
                        <div class="set has-children">
                            <a href="javascript:;" class="acnav-label">
                                <span>{{__('DESCRIPTION')}}</span>
                            </a>
                            <div class="acnav-list" style=" overflow-y: scroll; max-height: 290px;">
                                <p>  {!! $products->description !!}</p>
                            </div>
                        </div>
                        <div class="set has-children">
                            <a href="javascript:;" class="acnav-label">
                                <span> {{__('SPECIFICATION')}}</span>
                            </a>
                            <div class="acnav-list" style=" overflow-y: scroll; max-height: 290px;">
                                <p> {!! $products->specification !!}</p>
                            </div>
                        </div>
                        <div class="set has-children">
                            <a href="javascript:;" class="acnav-label">
                                <span> {{__('DETAILS')}}</span>
                            </a>
                            <div class="acnav-list" style=" overflow-y: scroll; max-height: 290px;">
                                <p>{!! $products->detail !!}</p>
                            </div>
                        </div>
                        @if(!empty($products->attachment))
                            <div class="set has-children">
                                <a href="javascript:;" class="acnav-label">
                                    <span> {{__('Download instruction .pdf')}}</span>
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
    <section class="related-product-section padding-bottom">
        <div class="container">
            <div class="section-title">
                <h2>{{__('Related products')}}</h2>
            </div>
            <div class="related-product-slider product-slider">
                @foreach($all_products as $key => $product)
                    @if($product->id != $products->id)
                        <div class="product-card">
                            <div class="product-card-inner">
                                <div class="product-content-top">
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
                                                        $color = 'text-primary';
                                                    }
                                                @endphp
                                                <i class="star fas {{$icon .' '. $color}}"></i>
                                            @endfor
                                        @endif
                                    </div>
                                    @if(Auth::guard('customers')->check())
                                        @if(!empty($wishlist) && isset($wishlist[$product->id]['product_id']))
                                            @if($wishlist[$product->id]['product_id'] != $product->id)
                                                <a href="#" class="btn wishlist-btn add_to_wishlist wishlist_{{$product->id}}" data-id="{{$product->id}}"><i class="far fa-heart"></i></a>   
                                            @else
                                                <a href="#" class="btn wishlist-btn" data-id="{{$product->id}}"><i class="fas fa-heart"></i></a>   
                                            @endif
                                        @else
                                            <a href="#" class="btn wishlist-btn add_to_wishlist wishlist_{{$product->id}}" data-id="{{$product->id}}"><i class="far fa-heart"></i></a>   
                                        @endif
                                    @else
                                        <a href="#" class="btn wishlist-btn add_to_wishlist wishlist_{{$product->id}}" data-id="{{$product->id}}"><i class="far fa-heart"></i></a>   
                                    @endif
                                </div>
                                <div class="product-img">
                                    <a href="{{route('store.product.product_view',[$store->slug,$product->id])}}">
                                        @if(!empty($product->is_cover) )
                                            <img src="{{$proimg.$product->is_cover}}" alt="">
                                        @else
                                            <img src="{{asset(Storage::url('uploads/is_cover_image/default.jpg'))}}" alt="">
                                        @endif
                                    </a>
                                </div>
                                <div class="product-content">
                                    <h5>
                                        <a href="{{route('store.product.product_view',[$store->slug,$product->id])}}">{{$product['name']}}</a>
                                    </h5>
                                    @if($product['enable_product_variant'] != 'on')
                                        <div class="price">
                                            <ins>{{\App\Models\Utility::priceFormat($product['price'])}} </ins>
                                        </div>
                                        <div class="product-content-bottom">
                                            <a class="btn cart-btn add_to_cart" data-id="{{$product->id}}"><i class="fas fa-shopping-basket"></i></a>
                                        </div>
                                    @else
                                        <div class="price">
                                            <ins>{{__('In Variant')}}</ins>
                                        </div>
                                        <div class="product-content-bottom">
                                            <a href="{{route('store.product.product_view',[$store->slug,$product->id])}}" class="btn cart-btn"><i class="fas fa-shopping-basket"></i></a>
                                        </div>
                                    @endif
                                    
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
                        $( ".variant_loop" ).each(function( index ) {
                                var variant_name = $(this).prev().text();
                                var variant_val = $(this).val();
                                variant_message_array.push(variant_val+" "+variant_name);
                        });
                        var variant_message = variant_message_array.join(" and ");

                        if(data.variant_id == 0) {
                            $('.add_to_cart').hide();

                            $('.product-price').hide();
                            $('.product-price-error').show();
                            var message =  '<span class=" mb-0 text-danger">This product is not available with '+variant_message+'.</span>';
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
