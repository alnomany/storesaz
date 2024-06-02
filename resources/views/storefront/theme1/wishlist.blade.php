@extends('storefront.layout.theme1')
@section('page-title')
    {{ __('Wishlist') }}
@endsection
@push('css-page')
@endpush
@php
    $imgpath = \App\Models\Utility::get_file('uploads/is_cover_image/');

@endphp
@section('content')
    <div class="wrapper">
        <section class="wishlist-section padding-top">
            <div class="container">
                @if(count($products) > 0)
                    <div class="row product-row">
                        @foreach ($products as $k => $product)
                            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                <div class="product-card">
                                    <div class="card-img">
                                        <a
                                            href="{{ route('store.product.product_view', [$store->slug, $product['product_id']]) }}">
                                            @if (!empty($product['image']))
                                                <img src="{{ $imgpath . $product['image'] }}" alt="Image placeholder">
                                            @else
                                                <img alt="Image placeholder"
                                                    src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}">
                                            @endif
                                        </a>
                                        {{--  <div class="heart-icon" data-id="{{ $product['product_id'] }}" id="delete_wishlist_item1">  --}}
                                        <a class="heart-icon action-item wishlist-icon bg-light-gray delete_wishlist_item"
                                            id="delete_wishlist_item1" data-id="{{ $product['product_id'] }}">
                                            <i class="fas fa-heart"></i>
                                        </a>
                                        {{--  </div>  --}}
                                    </div>  
                                    <div class="card-content">
                                        <div class="rating">
                                            @if ($store['enable_rating'] == 'on')
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @php
                                                        $icon = 'fa-star';
                                                        $color = '';
                                                        $newVal1 = $i - 0.5;
                                                        if (\App\Models\Product::getRatingById($product['product_id']) < $i && \App\Models\Product::getRatingById($product['product_id']) >= $newVal1) {
                                                            $icon = 'fa-star-half-alt';
                                                        }
                                                        if (\App\Models\Product::getRatingById($product['product_id']) >= $newVal1) {
                                                            $color = 'text-warning';
                                                        }
                                                    @endphp
                                                    <i class="star fas {{ $icon . ' ' . $color }}"></i>
                                                @endfor
                                            @endif
                                        </div>
                                        <h6>
                                            <a
                                                href="{{ route('store.product.product_view', [$store->slug, $product['product_id']]) }}">{{ $product['product_name'] }}</a>
                                        </h6>
                                        <p>{{ __('Category') }}:
                                            {{ \App\Models\Product::getCategoryById($product['product_id']) }}</p>

                                        <div class="last-btn">
                                            <div class="price">
                                                @if ($product['enable_product_variant'] == 'on')
                                                    <ins>{{ __('In variant') }}</ins>
                                                @else
                                                    <ins>{{ \App\Models\Utility::priceFormat($product['price']) }}</ins>
                                                @endif
                                            </div>
                                            @if ($product['enable_product_variant'] == 'on')
                                                <a href="{{ route('store.product.product_view', [$store->slug, $product['product_id']]) }}"
                                                    class="cart-btn">
                                                    <i class="fas fa-shopping-basket"></i>
                                                </a>
                                            @else
                                                <a href="javascript:void(0)" class="cart-btn add_to_cart"
                                                    data-id="{{ $product['product_id'] }}">
                                                    <i class="fas fa-shopping-basket"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>  
                @else
                    <div class="row product-row justify-content-center">
                        <div class="text-center">
                            <i class="fas fa-folder-open text-gray" style="font-size: 48px;"></i>
                            <h2>{{ __('Opps...') }}</h2>
                            <h6> {!! __('No data Found.') !!} </h6>
                        </div>
                    </div>
                @endif
                
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
                url: '{{ route('user.addToCart', ['__product_id', $store->slug, 'variation_id']) }}'
                    .replace(
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
