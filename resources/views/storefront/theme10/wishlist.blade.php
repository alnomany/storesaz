@extends('storefront.layout.theme10')
@section('page-title')
    {{ __('Wish list') }}
@endsection
@push('css-page')
@endpush
@php
    $imgpath=\App\Models\Utility::get_file('uploads/is_cover_image/');

    @endphp
@section('content')
<div class="wrapper">
    <section class="wishlist-page padding-bottom padding-top ">
        <div class="container">
            <div class="section-title">
                <h2>{{ __('Wishlist') }}</h2>
            </div>
            <div class="row product-row">
                @foreach ($products as $k => $product)
                    <div class="col-lg-3 col-sm-6 col-md-6 col-12 product-card">
                        <div class="product-card-inner">
                            <div class="product-content-top d-flex align-items-center justify-content-between ">
                                <span>
                                
                                </span>
                                <a href="#" class="wish-btn delete_wishlist_item" id="delete_wishlist_item1" data-id="{{ $product['product_id'] }}"><i class="fas fa-heart"></i></a>
                            </div>
                            <div class="product-img">
                                <a href="{{ route('store.product.product_view', [$store->slug,$product['product_id']]) }}">
                                    @if (!empty($product['image']))
                                        <img src="{{ $imgpath.$product['image'] }}" alt="">
                                    @else
                                        <img src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}" alt="">
                                    @endif
                                </a>
                            </div>
                            <div class="product-content">
                                <h6>
                                    <a href="{{ route('store.product.product_view', [$store->slug, $product['product_id']]) }}">{{ $product['product_name'] }}</a>
                                </h6>
                                <p>{{ __('Category:') }}: <b> {{ \App\Models\Product::getCategoryById($product['product_id']) }}</b></p>
                                <div class="price">
                                    <ins>
                                        @if ($product['enable_product_variant'] == 'on')
                                            {{ __('In variant') }}
                                        @else
                                            {{ \App\Models\Utility::priceFormat($product['price']) }}
                                        @endif
                                    </ins>
                                    <del>
                                        @if ($product['enable_product_variant'] == 'off')
                                            {{ \App\Models\Utility::priceFormat(\App\Models\Product::getProductById($product['product_id'])->last_price) }}
                                        @endif
                                    </del>
                                </div>
                            </div>
                            <div class="product-bottom">
                                @if ($product['enable_product_variant'] == 'on')
                                    <a href="{{ route('store.product.product_view', [$store->slug, $product['product_id']]) }}" class="cart-btn btn"> {{ __('ADD TO CART') }}</a>
                                @else
                                    <a href="#" class="cart-btn btn add_to_cart" data-id="{{ $product['product_id'] }}"> {{ __('ADD TO CART') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
@push('script-page')
    <script>
        $(document).on('click', '.delete_wishlist_item', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');

            $.ajax({
                type: "DELETE",
                url: '{{ route('delete.wishlist_item', [$store->slug, '__product_id']) }}'.replace(
                    '__product_id', id),
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    if (response.status == "success") {
                        show_toastr('Success', response.message, 'success');
                        $('.wishlist_' + response.id).remove();
                        $('.wishlist_count').html(response.count);
                        location.reload();
                    } else {
                        show_toastr('Error', response.message, 'error');
                    }
                },
                error: function(result) {}
            });
        });


    </script>
@endpush
