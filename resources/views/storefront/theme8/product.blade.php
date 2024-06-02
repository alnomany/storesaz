@extends('storefront.layout.theme8')
@section('page-title')
    {{ __('Home') }}
@endsection
@push('css-page')
@endpush
@php
    $imgpath=\App\Models\Utility::get_file('uploads/is_cover_image/');

@endphp
@section('content')
<div class="wrapper">
    <section class="porduct-listing-page padding-top padding-bottom">
        <div class="container">
            <div class="tabs-wrapper">
                <div class="tab-nav">
                    <h2>{{ __('Product') }}</h2>
                    <ul class="d-flex tabs">
                        @foreach($categories as $key=>$category)
                            <li class="tab-link {{($category==$categorie_name)?'active':''}}" data-tab="tab-{!! preg_replace('/[^A-Za-z0-9\-]/', '_', $category)!!}">
                                <a href="#{!!preg_replace('/[^A-Za-z0-9\-]/','_',$category)!!}" id="electronic-tab" data-id="{{$key}}">{{$category}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="tabs-container">
                    @if ($products['Start shopping']->count() > 0)
                        @foreach($products as $key => $items)
                            <div class="tab-content {{ $key == $categorie_name ? 'active' : '' }}" id="tab-{!! preg_replace('/[^A-Za-z0-9\-]/', '_', $key)!!}">
                                <div class="row product-row">
                                    @if($items->count() > 0)
                                        @foreach($items as $product)
                                            <div class="col-lg-3 col-md-4 col-12 col-sm-6 product-card">
                                                <div class="product-card-inner">
                                                    <div class="product-content-top">
                                                        <h6>
                                                            <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}"> {{ $product->name }}</a>
                                                        </h6>
                                                        <p>{{ $product->product_category() }}</p>
                                                    </div>
                                                    <div class="product-img">
                                                        <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}">
                                                            @if (!empty($product->is_cover) )
                                                                <img src="{{ $imgpath. $product->is_cover }}" alt="">
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
                                                                    <span class="d-block font-size-12 mb-1 variant_name">
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
                                                            <div class="price {{ $product->enable_product_variant == 'on' ? 'variation_price' . $product->id : '' }}">
                                                                <ins>
                                                                    @if ($product->enable_product_variant == 'on')
                                                                        {{ __('In variant') }}
                                                                    @else
                                                                        {{ \App\Models\Utility::priceFormat($product->price) }}
                                                                    @endif
                                                                </ins>
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
                                                            <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}" class="cart-btn btn add_to_cart" data-id="{{ $product->id }}">  {{ __('ADD TO CART') }}</a>
                                                        @else
                                                            <a href="#" class="cart-btn btn add_to_cart" data-id="{{ $product->id }}">  {{ __('ADD TO CART') }}</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif 
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="d-flex justify-content-center">
                            <div>
                                <i class="fas fa-folder-open text-gray" style="font-size: 48px;"></i>
                                <h2>{{ __('Opps...') }}</h2>
                                <h6> {!! __('No data Found.') !!} </h6>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@push('script-page')
    <script>
        // $(document).ready(function() {
        //     @if ($categorie_name == 'Start shopping')

        //         $('#Furniture').addClass('active');
        //         $("#myTab li:eq(0)").addClass('active');
        //     @endif
        //     // $("#myTab li a:eq(0)").addClass('active');
        // });
        // Tab js
        $('#myTab li').click(function() {
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
        $(document).on('click', '.qty-plus', function() {
            $(this).prev().val(+$(this).prev().val() + 1);
        });
        $(document).on('click', '.qty-minus', function() {
            if ($(this).next().val() > 0) $(this).next().val(+$(this).next().val() - 1);
        });
        $(document).ready(function() {
            $('.tab-a').click(function() {
                $(".tab-pane").removeClass('tab-active');
                $(".tab-pane[data-id='" + $(this).attr('data-id') + "']").addClass("tab-active");
                $(".tab-a").removeClass('active-a');
                $(this).parent().find(".tab-a").addClass('active-a');
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
