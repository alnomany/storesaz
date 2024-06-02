@extends('storefront.layout.theme7')
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
            <div class="section-title-bg section-title d-flex align-items-center justify-content-between">
                <h2>{{__('Products')}}</h2>
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
                        <div id="tab-{!! preg_replace('/[^A-Za-z0-9\-]/', '_', $key)!!}" class="tab-content {{($key==$categorie_name)?'active':''}}">
                            <div class="row product-row">
                                @foreach ($items as $key => $product)
                                    {{--  @if ($key < 4)  --}}
                                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                            <div class="product-card">
                                                <div class="product-card-inner">
                                                    <div class="bestseller-tag">
                                                        <p>{{ __('Bestseller') }}</p>
                                                    </div>
                                                    <div class="product-card-body">
                                                        <div class="card-img">
                                                            <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}">
                                                                @if (!empty($product->is_cover) )
                                                                    <img src="{{ $imgpath . $product->is_cover }}">
                                                                @else
                                                                    <img src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}">
                                                                @endif
                                                            </a>
                                                            @if (Auth::guard('customers')->check())
                                                                @if (!empty($wishlist) && isset($wishlist[$product->id]['product_id']))
                                                                    @if ($wishlist[$product->id]['product_id'] != $product->id)
                                                                        <a
                                                                            class="heart-icon action-item wishlist-icon bg-light-gray add_to_wishlist wishlist_{{ $product->id }}"
                                                                            data-id="{{ $product->id }}">
                                                                            <i class="far fa-heart"></i>
                                                                        </a>
                                                                    @else
                                                                        <a class="heart-icon action-item wishlist-icon bg-light-gray add_to_wishlist wishlist_{{ $product->id }}"
                                                                            disabled>
                                                                            <i class="fas fa-heart"></i>
                                                                        </a>
                                                                    @endif
                                                                @else
                                                                    <a
                                                                        class="heart-icon action-item wishlist-icon bg-light-gray add_to_wishlist wishlist_{{ $product->id }}"
                                                                        data-id="{{ $product->id }}">
                                                                        <i class="far fa-heart"></i>
                                                                    </a>
                                                                @endif
                                                            @else
                                                                <a
                                                                    class="heart-icon action-item wishlist-icon bg-light-gray add_to_wishlist wishlist_{{ $product->id }}"
                                                                    data-id="{{ $product->id }}">
                                                                    <i class="far fa-heart"></i>
                                                                </a>
                                                            @endif
                                                        </div>
                                                        <div class="card-content">
                                                            <h6>
                                                                <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}"> {{ $product->name }}</a>
                                                            </h6>
                                                        <p><span>{{ __('Category') }}:</span>  {{ $product->product_category() }}</p>
                                                        <div class="price">
                                                            @if ($product->enable_product_variant == 'on')
                                                                {{ __('In variant') }}
                                                            @else
                                                                {{ \App\Models\Utility::priceFormat($product->price) }}
                                                            @endif
                                                        </div>
                                                        @if ($product->enable_product_variant == 'on')
                                                            <div class="last-btn">
                                                                <a href="{{ route('store.product.product_view', [$store->slug, $product->id]) }}" class="btn" tabindex="0">{{ __('ADD TO CART') }}</a>
                                                            </div>
                                                        @else
                                                            <div class="last-btn">
                                                                <a href="javascript:void(0)" class="btn add_to_cart" tabindex="0"  data-id="{{ $product->id }}"> {{ __('ADD TO CART') }}</a>
                                                            </div>
                                                        @endif
                                                        </div>
                                                    </div>
                                                
                                                </div>
                                            </div>
                                        </div>
                                    {{--  @endif  --}}
                                @endforeach
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
