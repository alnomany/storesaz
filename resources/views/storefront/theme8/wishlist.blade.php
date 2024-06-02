@extends('storefront.layout.theme8')
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
                    <div class="col-lg-3 col-sm-6 col-md-4 col-12 product-card">
                        <div class="product-card-inner">
                            <div class="product-content-top">
                                <h6>
                                    <a href="{{ route('store.product.product_view', [$store->slug, $product['product_id']]) }}">{{ $product['product_name'] }}</a>
                                </h6>
                                <p> {{ __('Category') }}: {{ \App\Models\Product::getCategoryById($product['product_id']) }}</p>
                            </div>
                            <div class="product-img">
                                <a href="{{ route('store.product.product_view', [$store->slug, $product['product_id']]) }}">
                                    @if (!empty($product['image']))
                                        <img src="{{$imgpath.$product['image'] }}" alt="">
                                    @else
                                        <img src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}" alt="">
                                    @endif
                                </a>
                            </div>
                            <div class="product-content">
                                <div class="wish-price">
                                    <div class="price">
                                        <ins> 
                                            @if ($product['enable_product_variant'] == 'on')
                                                {{ __('In variant') }}
                                            @else
                                                {{ \App\Models\Utility::priceFormat($product['price']) }}
                                            @endif
                                        </ins>
                                    </div>
                                    <a href="#" class="wishlist-btn delete_wishlist_item" data-id="{{ $product['product_id'] }}" id="delete_wishlist_item1"><i class="fas fa-heart"></i></a>
                                </div>
                                @if ($product['enable_product_variant'] == 'on')
                                    <a href="{{ route('store.product.product_view', [$store->slug, $product['product_id']]) }}" class="cart-btn btn">{{ __('ADD TO CART') }}</a>
                                @else
                                    <a href="#" class="cart-btn btn add_to_cart"  data-id="{{ $product['product_id'] }}"> {{ __('ADD TO CART') }}</a>
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


        $(document).on('change', '.variant-selection', function() {

            var variants = [];

            let selected1 = $(this).parent().parent().find('.variant-selection');
            $(selected1).each(function(index, element) {
                variants.push(element.value);

            });
            let product_id = $(this).closest(".card-product").find('#product_id').val();
            let variation_price = $(this).closest(".card-product").find('.variation_price');

            if (variants.length > 0) {

                $.ajax({
                    url: '{{ route('get.products.variant.quantity') }}',
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        variants: variants.join(' : '),
                        product_id:product_id

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
@endpush
