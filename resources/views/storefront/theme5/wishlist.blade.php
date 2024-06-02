@extends('storefront.layout.theme5')
@section('page-title')
    {{__('Wish list')}}
@endsection
@push('css-page')
@endpush
@php
    $imgpath=\App\Models\Utility::get_file('uploads/is_cover_image/');

    @endphp
@section('content')
<div class="wrapper">
    <section class="wishlist-page padding-top padding-bottom">
        <div class="container">
            <div class="section-title d-flex align-items-center justify-content-between">
                <h2>{{ __('Wishlist') }}</h2>
            </div>
            <div class="row product-row">
                @foreach($products as $k => $product)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 product-card wishlist_{{$product['product_id']}}">
                        <div class="product-card-inner">
                            <div class="product-content-top">
                                <div class="product-rating">
                                    @if($store['enable_rating'] == 'on')
                                        @for($i =1;$i<=5;$i++)
                                            @php
                                                $icon = 'fa-star';
                                                $color = '';
                                                $newVal1 = ($i-0.5);
                                                if(\App\Models\Product::getRatingById($product['product_id']) < $i && \App\Models\Product::getRatingById($product['product_id']) >= $newVal1)
                                                {
                                                    $icon = 'fa-star-half-alt';
                                                }
                                                if(\App\Models\Product::getRatingById($product['product_id']) >= $newVal1)
                                                {
                                                    $color = 'text-warning';
                                                }
                                            @endphp
                                            <i class="star fas {{$icon .' '. $color}}"></i>
                                        @endfor
                                    @endif
                                </div>
                                <a href="#" class="btn wishlist-btn delete_wishlist_item" id="delete_wishlist_item1" data-id="{{$product['product_id']}}"><i class="fas fa-heart"></i></a>
                            </div>
                            <div class="product-img">
                                <a href="{{route('store.product.product_view',[$store->slug,$product['product_id']])}}">
                                    @if(!empty($product['image']))
                                        <img src="{{$imgpath.$product['image']}}" alt="">
                                    @else
                                        <img src="{{asset(Storage::url('uploads/is_cover_image/default.jpg'))}}" alt="">
                                    @endif
                                </a>
                            </div>
                            <div class="product-content">
                                <h5>
                                    <a href="{{route('store.product.product_view',[$store->slug,$product['product_id']])}}">{{$product['product_name']}}</a>
                                </h5>
                                @if($product['enable_product_variant'] == 'on')
                                    <div class="price">
                                        <ins>{{__('In variant')}}</ins>
                                    </div>
                                    <div class="product-content-bottom">
                                        <a href="{{route('store.product.product_view',[$store->slug,$product['product_id']])}}" class="btn cart-btn add_to_cart"><i class="fas fa-shopping-basket"></i></a>
                                    </div>
                                @else
                                    <div class="price">
                                        <ins>{{\App\Models\Utility::priceFormat($product['price'])}} </ins>
                                    </div>
                                    <div class="product-content-bottom">
                                        <a class="btn cart-btn add_to_cart" data-id="{{$product['product_id']}}"><i class="fas fa-shopping-basket"></i></a>
                                    </div>
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
        $(document).on('click', '.delete_wishlist_item', function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');

            $.ajax({
                type: "DELETE",
                url: '{{route('delete.wishlist_item', [$store->slug,'__product_id'])}}'.replace('__product_id', id),
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function (response) {
                    if (response.status == "success") {
                        show_toastr('Success', response.message, 'success');
                        $('.wishlist_' + response.id).remove();
                        $('.wishlist_count').html(response.count);

                    } else {
                        show_toastr('Error', response.message, 'error');
                    }
                },
                error: function (result) {
                }
            });
        });
        $(document).on('click', '.add_to_cart', function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            $.ajax({
                type: "POST",
                url: '{{route('user.addToCart', ['__product_id',$store->slug])}}'.replace('__product_id', id),
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function (response) {
                    if (response.status == "Success") {
                        show_toastr('Success', response.success, 'success');
                        $(".shoping_counts").attr("value", response.item_count);
                        $(".shoping_counts").html(response.item_count);
                    } else {
                        show_toastr('Error', response.error, 'error');
                    }
                },
                error: function (result) {
                    console.log('error');
                }
            });
        });
    </script>
@endpush
