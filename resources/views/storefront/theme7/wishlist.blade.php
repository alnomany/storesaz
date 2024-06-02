@extends('storefront.layout.theme7')
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
    <section class="wishlist-section padding-top padding-bottom">
        <div class="container">
            <div class="row product-row">
                @if (!empty($products) || ($products = []))
                @foreach ($products as $k => $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="product-card">
                            <div class="product-card-inner">
                                <div class="bestseller-tag">
                                    <p>{{ __('BESTSELLER') }}</p>
                                </div>
                                <div class="product-card-body">
                                    <div class="card-img">
                                        <a href="{{ route('store.product.product_view', [$store->slug, $product['product_id']]) }}">
                                            @if (!empty($product['image']))
                                                <img src="{{  $imgpath.$product['image'] }}">
                                            @else
                                                <img src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}">
                                            @endif
                                        </a>
                                    
                                        <a class="heart-icon action-item wishlist-icon bg-light-gray delete_wishlist_item" id="delete_wishlist_item1" data-id="{{ $product['product_id'] }}">
                                            <i class="fas fa-heart"></i>
                                        </a>
                                    </div>
                                    <div class="card-content">
                                        <h6>
                                            <a href="{{ route('store.product.product_view', [$store->slug, $product['product_id']]) }}">{{ $product['product_name'] }}</a>
                                        </h6>
                                    <p><span>{{ __('Category') }}:</span>{{ \App\Models\Product::getCategoryById($product['product_id']) }}</p>
                                    <div class="price">
                                        @if ($product['enable_product_variant'] == 'on')
                                            <ins>{{ __('In variant') }}</ins>
                                        @else  
                                            <ins>{{ \App\Models\Utility::priceFormat($product['price']) }}</ins>
                                        @endif
                                    </div>
                                    @if ($product['enable_product_variant'] == 'on')
                                        <div class="last-btn">
                                            <a href="{{ route('store.product.product_view', [$store->slug, $product['product_id']]) }}" class="btn" tabindex="0">{{ __('Add to cart') }}</a>
                                        </div>
                                    @else   
                                        <div class="last-btn">
                                            <a href="javascript:void(0)" class="btn add_to_cart" tabindex="0" data-id="{{ $product['product_id'] }}">{{ __('Add to cart') }}</a>
                                        </div>
                                    @endif
                                    </div>
                                </div>    
                            </div>
                            
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
</script>
@endpush
