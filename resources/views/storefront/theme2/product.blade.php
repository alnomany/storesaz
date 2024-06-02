@extends('storefront.layout.theme2')
@section('page-title')
    {{__('Home')}}
@endsection
@push('css-page')

@endpush
@php
    $imgpath=\App\Models\Utility::get_file('uploads/is_cover_image/');

@endphp
@section('content')
    <div class="wrapper">
        <section class="bestseller-section tabs-wrapper padding-bottom padding-top">
            <div class="container">
                <div class="section-title-bg section-title d-flex align-items-center justify-content-between">
                    <h2>{{__('Products')}}</h2>
                    <ul class="d-flex tabs">
                        @foreach($categories as $key=>$category)
                            <li class="tab-link {{($category==$categorie_name)?'active':''}}" data-tab="tab-{!! preg_replace('/[^A-Za-z0-9\-]/', '_', $category)!!}">
                                <a href="#tab-{!!preg_replace('/[^A-Za-z0-9\-]/','_',$category)!!}" id="electronic-tab" data-id="{{$key}}">{{$category}}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @if($products['Start shopping']->count() > 0)
                    @foreach ($products as $key => $items)
                        <div class="tab-content {{ $key == $categorie_name ? 'active' : '' }}" id="tab-{!! preg_replace('/[^A-Za-z0-9\-]/', '_', $key) !!}">
                            @if ($items->count() > 0)
                                <div class="row">
                                    @foreach ($items as $product)
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                            <div class="product-card">
                                                <div class="card-img">
                                                    <a href="{{route('store.product.product_view',[$store->slug,$product->id])}}">
                                                        @if(!empty($product->is_cover) )
                                                            <img src="{{$imgpath.$product->is_cover}}">
                                                        @else
                                                            <img src="{{asset(Storage::url('uploads/is_cover_image/default.jpg'))}}">
                                                        @endif
                                                    </a>
                                                </div>  
                                                <div class="card-content">
                                                    <h6>
                                                        <a href="{{route('store.product.product_view',[$store->slug,$product->id])}}">{{$product->name}}</a>
                                                    </h6>
                                                    <div class="rating">
                                                        @if($store->enable_rating == 'on')
                                                            @for($i =1;$i<=5;$i++)
                                                                @php
                                                                    $icon = 'fa-star';
                                                                    $color = '';
                                                                    $newVal1 = ($i-0.5);
                                                                    if($product->product_rating() < $i && $product->product_rating() >= $newVal1)
                                                                    {
                                                                        $icon = 'fa-star-half-alt';
                                                                    }
                                                                    if($product->product_rating() >= $newVal1)
                                                                    {
                                                                        $color = 'text-warning';
                                                                    }
                                                                @endphp
                                                                <i class="star fas {{$icon .' '. $color}}"></i>
                                                            @endfor
                                                        @endif
                                                    </div>
                                                    <div class="price">
                                                        @if ($product->enable_product_variant == 'on')
                                                            <ins>{{ __('In variant') }}</ins>
                                                        @else
                                                            <ins>{{ \App\Models\Utility::priceFormat($product->price) }}</ins>
                                                        @endif
                                                    </div>
                                                    <p>{{__('Category')}}: {{$product->product_category()}}</p>
                                                    <div class="last-btn">
                                                        @if ($product->enable_product_variant == 'on')
                                                            <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}" class="cart-btn">{{ __('Add to cart') }} <i class="fas fa-shopping-basket"></i></a>
                                                        @else
                                                            <a href="#" class="cart-btn add_to_cart" data-id="{{ $product->id }}">{{ __('Add to cart') }} <i class="fas fa-shopping-basket"></i></a>
                                                        @endif
                                                        @if (Auth::guard('customers')->check())
                                                            @if (!empty($wishlist) && isset($wishlist[$product->id]['product_id']))
                                                                @if ($wishlist[$product->id]['product_id'] != $product->id)
                                                                    <a href="#" class="heart-btn add_to_wishlist wishlist_{{ $product->id }}" data-id="{{ $product->id }}"><i class="far fa-heart"></i></a>
                                                                @else
                                                                    <a href="#" class="heart-btn wishlist_{{ $product->id }}" data-id="{{ $product->id }}" disabled><i class="fas fa-heart"></i></a> 
                                                                @endif
                                                            @else
                                                                <a href="#" class="heart-btn add_to_wishlist wishlist_{{ $product->id }}" data-id="{{ $product->id }}"><i class="far fa-heart"></i></a>
                                                            @endif
                                                        @else
                                                            <a href="#" class="heart-btn add_to_wishlist wishlist_{{ $product->id }}" data-id="{{ $product->id }}"><i class="far fa-heart"></i></a>
                                                        @endif
                                                    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                    <div class="product-card">
                                        <h6 class="no_record"><i class="fas fa-ban"></i> {{__('No Record Found')}}</h6>
                                    </div>
                                </div>
                            @endif
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
        </section>    
    </div>    
@endsection
@push('script-page')
    <script>
        $(".add_to_cart").click(function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var variants = [];
            $(".variant-selection").each(function (index, element) {
                variants.push(element.value);
            });

            if (jQuery.inArray('', variants) != -1) {
                show_toastr('Error', "{{ __('Please select all option.') }}", 'error');
                return false;
            }
            var variation_ids = $('#variant_id').val();

            $.ajax({
                url: '{{route('user.addToCart', ['__product_id',$store->slug,'variation_id'])}}'.replace('__product_id', id).replace('variation_id', variation_ids ?? 0),
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    variants: variants.join(' : '),
                },
                success: function (response) {
                    if (response.status == "Success") {
                        show_toastr('Success', response.success, 'success');
                        $("#shoping_counts").html(response.item_count);
                    } else {
                        show_toastr('Error', response.error, 'error');
                    }
                },
                error: function (result) {
                    console.log('error');
                }
            });
        });

        $(document).on('click', '.add_to_wishlist', function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            $.ajax({
                type: "POST",
                url: '{{route('store.addtowishlist', [$store->slug,'__product_id'])}}'.replace('__product_id', id),
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function (response) {
                    if (response.status == "Success") {
                        show_toastr('Success', response.message, 'success');
                        $('.wishlist_' + response.id).removeClass('add_to_wishlist');
                        $('.wishlist_' + response.id).html('<i class="fas fa-heart"></i>');
                        $('.wishlist_count').html(response.count);
                    } else {
                        show_toastr('Error', response.message, 'error');
                    }
                },
                error: function (result) {
                }
            });
        });
    </script>
@endpush
