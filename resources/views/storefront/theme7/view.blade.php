@extends('storefront.layout.theme7')

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
                    <div class="product-slider">
                        <div class="pdp-det-slider">
                            @foreach ($products_image as $key => $productss)
                                <div class="pdp-main-itm">
                                    <div class="pdp-itm-inner">
                                        @if (!empty($products_image[$key]->product_images))
                                            <img src="{{ $imgpath . $products_image[$key]->product_images }}">
                                        @else
                                            <img src="{{ asset(Storage::url('uploads/product_image/default.jpg')) }}">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
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
                                        <a class="add_to_wishlist wishlist_{{ $products->id }}" data-id="{{ $products->id }}">
                                            <i class="far fa-heart"></i>
                                        </a>
                                    @else
                                        <a class="add_to_wishlist wishlist_{{ $products->id }}" disabled>
                                            <i class="fas fa-heart"></i>
                                        </a>
                                    @endif
                                @else
                                    <a class="add_to_wishlist wishlist_{{ $products->id }}" data-id="{{ $products->id }}">
                                        <i class="far fa-heart"></i>
                                    </a>
                                @endif
                            @else
                                <a class="add_to_wishlist wishlist_{{ $products->id }}" data-id="{{ $products->id }}">
                                    <i class="far fa-heart"></i>
                                </a>
                            @endif
                        </div>
                        <h2>{{ $products->name }}</h2>
                        <p>{!! $products->detail !!}
                        </p>
                        @if ($products->enable_product_variant == 'on')
                            <input type="hidden" id="product_id" value="{{ $products->id }}">
                            <input type="hidden" id="variant_id" value="">
                            <input type="hidden" id="variant_qty" value="">
                            <div class="p-color mt-3">
                                <p class="mb-0">{{__('VARIATION:')}}</p>

                                @foreach ($product_variant_names as $key => $variant)
                                    <div class="col-sm-6 mb-4 mb-sm-0">
                                        <p class="d-block h6 mb-0">
                                        <p class="mb-0 variant_name">{{ empty($variant->variant_name) ? $variant['variant_name'] :  $variant->variant_name}}</p>

                                        <select name="product[{{ $key }}]"  id="pro_variants_name"
                                            class="form-control variant-selection  pro_variants_name{{ $key }} pro_variants_name variant_loop variant_val">
                                            {{-- <option value="">{{ __('Select') }}</option> --}}
                                            @foreach ($variant->variant_options ?? $variant['variant_options']  as $key => $values)
                                                <option value="{{ $values }}" id="{{ $values }}_varient_option">{{ $values }}</option>
                                            @endforeach
                                        </select>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <div class="product-price">
                            <div class="price variation_price">
                                @if ($products->enable_product_variant == 'on')
                                    {{ \App\Models\Utility::priceFormat(0) }} 
                                @else
                                  {{ \App\Models\Utility::priceFormat($products->price) }}
                                @endif
                                <del>{{ \App\Models\Utility::priceFormat($products->last_price) }}</del>
                            </div>
                            <a href="#" class="btn add_to_cart" data-id="{{ $products->id }}">{{ __('Add to cart') }}</a>
                        </div>
                        <span class=" mb-0 text-danger product-price-error"></span>
                        <ul>
                            <li><span>{{ __('Category') }}:</span> {{ $products->product_category() }} <span>{{ __('ID') }}:</span> {{ $products->SKU }}</li>
                            @if (!empty($products->custom_field_1) && !empty($products->custom_value_1))
                                <li><span>{{ $products->custom_field_1 }} : </span> {{ $products->custom_value_1 }}</li>
                            @endif
                            @if (!empty($products->custom_field_2) && !empty($products->custom_value_2))
                                <li><span>{{ $products->custom_field_2 }} :</span> {{ $products->custom_value_2 }}</li>
                            @endif
                            @if (!empty($products->custom_field_3) && !empty($products->custom_value_3))
                                <li><span>{{ $products->custom_field_3 }} :</span> {{ $products->custom_value_3 }}</li>
                            @endif
                            @if (!empty($products->custom_field_4) && !empty($products->custom_value_4))
                                <li><span>{{ $products->custom_field_4 }} :</span> {{ $products->custom_value_4 }}</li>
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
                            {{--  <p>{{ $avg_rating }}/5 ({{ $user_count }} {{ __('reviews') }})</p>  --}}
                            
                        </div>
                        {{--  @if (!empty($product_rating))
                            <p>
                                {{ $product_rating->description }}
                            </p>
                        @endif  --}}
                        <div class="review-box-2">
                            <h5>{{ __('reviews') }}:
                                <b>{{ $avg_rating }}/5</b>
                                <span> {{ __('reviews') }} </span>
                            </h5>
                            @if (Auth::guard('customers')->check())
                                <a href="#" class="btn btn-sm btn-primary btn-icon-only rounded-circle float-right text-white" data-size="lg" data-toggle="modal" data-url="{{route('rating',[$store->slug,$products->id])}}" data-ajax-popup="true" data-title="{{__('Create New Rating')}}">
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
                                    <span>{{ __('DETAILS') }}</span> 
                                </a>
                                <div class="acnav-list" style=" overflow-y: scroll; max-height: 290px;">
                                    <p> {!! $products->detail !!}</p>
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
                <h2>{{ __('Related Product') }}</h2>
            </div>
            <div class="row product-row">
                @foreach ($all_products as $key => $product)
                    @if ($product->id != $products->id)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="product-card">
                                <div class="product-card-inner">
                                    <div class="bestseller-tag">
                                        <p>{{ __('BESTSELLER') }}</p>
                                    </div>
                                    <div class="product-card-body">
                                        <div class="card-img">
                                            <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}">
                                                @if (!empty($product->is_cover))
                                                    <img src="{{ $proimg . $product->is_cover }}">
                                                @else
                                                    <img src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}">
                                                @endif
                                            </a>
                                            @if(!empty($wishlist) && isset($wishlist[$product->id]['product_id']))
                                                @if($wishlist[$product->id]['product_id'] != $product->id)
                                                    <a  class="heart-icon action-item wishlist-icon bg-light-gray add_to_wishlist wishlist_{{$product->id}}" data-id="{{$product->id}}">
                                                        <i class="far fa-heart"></i>
                                                    </a>
                                                @else
                                                    <a class="heart-icon action-item wishlist-icon bg-light-gray" data-id="{{$product->id}}" disabled>
                                                        <i class="fas fa-heart"></i>
                                                    </a>
                                                @endif
                                            @else
                                                <a class="heart-icon action-item wishlist-icon bg-light-gray add_to_wishlist wishlist_{{$product->id}}" data-id="{{$product->id}}">
                                                    <i class="far fa-heart"></i>
                                                </a>
                                            @endif
                                        </div>
                                        <div class="card-content">
                                            <h6>
                                                <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}">  {{ $product->name }}</a>
                                            </h6>
                                        <p><span>Category:</span> Table Lamps</p>
                                        <div class="price">
                                            @if ($product->enable_product_variant == 'on')
                                                {{ __('In variant') }}
                                            @else
                                                {{ \App\Models\Utility::priceFormat($product->price) }}
                                            @endif
                                        </div>
                                            <div class="last-btn">
                                                @if ($product->enable_product_variant == 'on')
                                                    <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}" class="btn" tabindex="0">{{ __('Add To Cart') }}</a>
                                                @else
                                                    <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}" class="btn" tabindex="0">{{ __('Add To Cart') }}</a>
                                                @endif
                                            </div>
                                        </div>
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
