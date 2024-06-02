@extends('storefront.layout.theme6')
@section('page-title')
    {{ __('Home') }}
@endsection
@php
    $imgpath = \App\Models\Utility::get_file('uploads/is_cover_image/');

@endphp
@section('content')
   <div class="wrapper">
       <section class="porduct-listing-page padding-top padding-bottom">
        <div class="container">
            <div class="tabs-wrapper">
                <div class="section-title">
                    <div class="row row-gap align-items-center">
                        <div class="col-lg-4  col-xl-6 col-12">
                            <h2>{{ __('Product') }}</h2>
                        </div>
                        <div class="col-lg-8 col-xl-6 col-12">
                            <ul class="d-flex tabs" role="tablist" id="myTab">
                                @foreach ($categories as $key => $category)
                                    <li class="tab-link {{($category == $categorie_name)?'active':''}}  " data-tab="tab-{!! preg_replace('/[^A-Za-z0-9\-]/', '_', $category)!!}">
                                        <a href="#{!! preg_replace('/[^A-Za-z0-9\-]/', '_', $category) !!}" data-id="{{ $key }}"
                                            class="  tab-a border-0  btn-block text-secondary m-0 rounded-0 productTab"
                                            id="electronic-tab" data-toggle="tab" role="tab"
                                            aria-controls="home" aria-selected="false">
                                            {{ __($category) }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="tabs-container">
                    @php
                        $counter = 0;
                    @endphp
                    @if ($products['Start shopping']->count() > 0)
                        @foreach ($products as $key => $items)
                            <div class="tab-content {{ $key == $categorie_name ? 'active' : '' }}" id="tab-{!! preg_replace('/[^A-Za-z0-9\-]/', '_', $key)!!}">
                                <div class="row product-row">
                                    @foreach ($items as $key => $product)
                                        <div class="col-lg-3 col-sm-6 col-md-6 col-12 product-card">
                                            <div class="product-card-inner">
                                                <div class="product-content-top d-flex  justify-content-between ">
                                                    <span class="p-lbl">Bestseller</span>

                                                        @if (Auth::guard('customers')->check())
                                                            @if (!empty($wishlist) && isset($wishlist[$product->id]['product_id']))
                                                                @if ($wishlist[$product->id]['product_id'] != $product->id)
                                                                    <a data-toggle="tooltip"
                                                                        data-original-title="Wishlist" type="button"
                                                                        class="wish-btn  add_to_wishlist wishlist_{{ $product->id }}"
                                                                        data-id="{{ $product->id }}">
                                                                        <i class="far fa-heart"></i>
                                                                    </a>
                                                                @else
                                                                    <a data-toggle="tooltip"
                                                                        data-original-title="Wishlist" type="button"
                                                                        class="wish-btn "
                                                                        data-id="{{ $product->id }}" disabled>
                                                                        <i class="fas fa-heart"></i>
                                                                    </a>
                                                                @endif
                                                            @else
                                                                <a data-toggle="tooltip" data-original-title="Wishlist"
                                                                    type="button"
                                                                    class="wish-btn  add_to_wishlist wishlist_{{ $product->id }}"
                                                                    data-id="{{ $product->id }}">
                                                                    <i class="far fa-heart"></i>
                                                                </a>
                                                            @endif
                                                        @else
                                                            <a data-toggle="tooltip" data-original-title="Wishlist"
                                                                type="button"
                                                                class="wish-btn  add_to_wishlist wishlist_{{ $product->id }}"
                                                                data-id="{{ $product->id }}">
                                                                <i class="far fa-heart"></i>
                                                            </a>
                                                        @endif
                                                </div>
                                                <div class="product-img">

                                                    <a href="{{route('store.product.product_view',[$store->slug,$product->id])}}">
                                                        @if (!empty($product->is_cover) )
                                                            <img alt="Image placeholder"
                                                                src="{{ $imgpath. $product->is_cover }}">
                                                        @else
                                                            <img alt="Image placeholder"
                                                                src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}">
                                                        @endif
                                                    </a>
                                                </div>
                                                <div class="product-content">
                                                    <h6>
                                                        <a href="{{route('store.product.product_view',[$store->slug,$product->id])}}">{{ $product->name }}</a>
                                                    </h6>
                                                    <div class="price">
                                                        <ins><span class="currency-type"></span>
                                                            @if ($product->enable_product_variant == 'on')
                                                                {{ __('In variant') }}
                                                            @else
                                                                {{ \App\Models\Utility::priceFormat($product->price) }}
                                                            @endif
                                                        </ins>
                                                    </div>
                                                    <p>{{__('Category')}}: <b>{{ $product->product_category() }}</b></p>
                                                </div>
                                                <div class="product-bottom">

                                                    @if ($product->enable_product_variant == 'on')
                                                        <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}"
                                                            class="cart-btn btn">
                                                            {{ __('ADD TO CART') }}
                                                        </a>
                                                    @else
                                                        <a href="javascript:void(0)"
                                                            class="cart-btn btn add_to_cart"
                                                            data-id="{{ $product->id }}">
                                                            {{ __('ADD TO CART') }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @php
                                $counter++;
                            @endphp
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

        $(document).on('click', '.qty-plus', function() {
            $(this).prev().val(+$(this).prev().val() + 1);
        });
        $(document).on('click', '.qty-minus', function() {
            if ($(this).next().val() > 0) $(this).next().val(+$(this).next().val() - 1);
        });

    </script>
@endpush
