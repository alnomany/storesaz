@extends('storefront.layout.theme10')
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
                    <h2> {{ __('Product') }}</h2>
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
                                            <div class="col-lg-3 col-sm-6 col-md-6 col-12 product-card">
                                                <div class="product-card-inner">
                                                    <div class="product-content-top d-flex align-items-center justify-content-between ">
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
                                                                    <a href="#" class="wish-btn add_to_wishlist wishlist_{{ $product->id }}" data-id="{{ $product->id }}"><i class="far fa-heart"></i></a>
                                                                @else
                                                                    <a href="#" class="wish-btn" data-id="{{ $product->id }}"><i class="fas fa-heart"></i></a>
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
                                                                <img src="{{$imgpath . $product->is_cover }}" alt="">
                                                            @else
                                                                <img src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}" alt="">
                                                            @endif
                                                        </a>
                                                    </div>
                                                    <div class="product-content">
                                                        <h6>
                                                            <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}">{{ $product->name }}</a>
                                                        </h6>
                                                        <p>{{ __('Category:') }} <b> {{ $product->getCategoryById($product->id) }}</b></p>
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
                                                            <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}" class="cart-btn btn">{{ __('ADD TO CART') }}</a>
                                                        @else
                                                            <a href="#" class="cart-btn btn add_to_cart" data-id="{{ $product->id }}">{{ __('ADD TO CART') }}</a>
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
            let selected = $(this);
            $(this).each(function(index, element) {

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
                        $('.variation_price').html(data.price);
                        $('#variant_id').val(data.variant_id);
                        $('#variant_qty').val(data.quantity);
                    }
                });
            }
        });
    </script>
@endpush
