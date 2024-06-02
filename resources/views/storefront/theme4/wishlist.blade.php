@extends('storefront.layout.theme4')
@section('page-title')
    {{__('Wish list')}}
@endsection
@push('css-page')
<style>
    @media screen and (max-width: 1199px){
        .last-btn {
            flex-direction: inherit;
            align-items: start;
        }
    }
</style>
@endpush
@php
    $imgpath=\App\Models\Utility::get_file('uploads/is_cover_image/');

    @endphp
@section('content')
<div class="wrapper">
    <section class="wishlist-section padding-top padding-bottom">
        <div class="container">
            <div class="row product-row">
                @foreach($products as $k => $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12 wishlist_{{$product['product_id']}}">
                        <div class="product-card">
                            <div class="card-img">
                                <a href="{{route('store.product.product_view',[$store->slug,$product['product_id']])}}">
                                    @if(!empty($product['image']))
                                        <img src="{{$imgpath.$product['image'] }}">
                                    @else
                                    <img src="{{asset(Storage::url('uploads/is_cover_image/default.jpg'))}}">
                                    @endif
                                </a>
                            </div>
                            <div class="card-content">
                                <h6>
                                    <a href="#">{{$product['product_name']}}</a>
                                </h6>
                                <div class="rating">
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
                                <div class="price">
                                    @if($product['enable_product_variant'] == 'on')
                                        <ins>{{__('In variant')}}</ins>
                                    @else
                                        <ins>{{\App\Models\Utility::priceFormat($product['price'])}}</ins>
                                    @endif
                                </div>
                                <p>{{__('Category')}}: {{\App\Models\Product::getCategoryById($product['product_id'])}}</p>
                                <div class="last-btn">
                                    @if($product['enable_product_variant'] == 'on')
                                        <a href="{{route('store.product.product_view',[$store->slug,$product['product_id']])}}" class="cart-btn add_to_cart" data-id="{{$product['product_id']}}">{{__('In variant')}}<i class="fas fa-shopping-basket"></i></a>
                                    @else
                                        <a href="#" class="cart-btn add_to_cart" data-id="{{$product['product_id']}}">{{__('Add to cart')}} <i class="fas fa-shopping-basket"></i></a>
                                    @endif
                                    <a href="#" class="heart-btn delete_wishlist_item" id="delete_wishlist_item1" data-id="{{$product['product_id']}}"><i class="fas fa-heart"></i></a>
                                </div>
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
