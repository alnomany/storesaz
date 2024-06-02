@extends('storefront.layout.theme6')
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
                        <div class="col-lg-3 col-sm-6 col-md-6 col-12 product-card  wishlist_{{ $product['product_id'] }}">
                            <div class="product-card-inner">
                                <div class="product-content-top d-flex  justify-content-between ">
                                    <span class="p-lbl">{{ __('Bestseller') }}</span>
                                    <a href="#" class="wish-btn delete_wishlist_item" id="delete_wishlist_item1" data-id="{{ $product['product_id'] }}"><i class="fas fa-heart"></i></a>
                                </div>
                                <div class="product-img">
                                    @if (!empty($product['image']))
                                        <img alt="Image placeholder" src="{{ $imgpath.$product['image'] }}"
                                            class="img-center img-fluid">
                                    @else
                                        <img alt="Image placeholder"
                                            src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}"
                                            class="img-center img-fluid">
                                    @endif
                                </div>
                                <div class="product-content">
                                    <h6>
                                        <a href="{{ route('store.product.product_view', [$store->slug, $product['product_id']]) }}">{{ $product['product_name'] }}</a>
                                    </h6>
                                    <div class="price">
                                        <ins><span class="currency-type"></span>
                                            @if ($product['enable_product_variant'] == 'on')
                                                {{ __('In variant') }}
                                            @else
                                                {{ \App\Models\Utility::priceFormat($product['price']) }}
                                            @endif
                                        </ins>
                                    </div>
                                    <p>{{__('Category')}}: <b>{{ \App\Models\Product::getCategoryById($product['product_id']) }}</b></p>
                                </div>
                                <div class="product-bottom">
                                    {{-- <a href="#" class="cart-btn btn">ADD TO CART</a> --}}
                                    @if ($product['enable_product_variant'] == 'on')
                                        <a href="{{ route('store.product.product_view', [$store->slug, $product['product_id']]) }}"
                                            class="cart-btn btn">
                                            {{ __('ADD TO CART') }}
                                        </a>
                                    @else
                                        <a href="javascript:void(0)"
                                            class="cart-btn btn add_to_cart"
                                            data-id="{{ $product['product_id'] }}">
                                            {{ __('ADD TO CART') }}
                                        </a>
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
