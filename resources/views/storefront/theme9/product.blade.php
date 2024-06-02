@extends('storefront.layout.theme9')
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
    <section class="bestseller-section tabs-wrapper padding-top padding-bottom">
        <div class="container">
            <div class="tab-nav">
                <h2>{{ __('Products') }}</h2>
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
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                            <div class="product-card">
                                                <div class="product-card-inner">
                                                    <div class="product-content-top d-flex  justify-content-between ">
                                                        @if (Auth::guard('customers')->check())
                                                            @if (!empty($wishlist) && isset($wishlist[$product->id]['product_id']))
                                                                @if ($wishlist[$product->id]['product_id'] != $product->id)
                                                                    <a href="#" class="wish-btn add_to_wishlist wishlist_{{ $product->id }}" data-id="{{ $product->id }}"><i class="far fa-heart"></i></a>
                                                                @else
                                                                    <a href="#" class="wish-btn"><i class="fas fa-heart wishlist_{{ $product->id }}"></i></a>
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
                                                                <img src="{{ $imgpath . $product->is_cover }}" alt="">
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
                                                            <span> {{ $product->product_rating() }} / {{ __('5.0') }}</span> 
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
