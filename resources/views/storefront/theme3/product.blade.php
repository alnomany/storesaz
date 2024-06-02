@extends('storefront.layout.theme3')
@section('page-title')
    {{__('Home')}}
@endsection
@php
    $imgpath=\App\Models\Utility::get_file('uploads/is_cover_image/');

@endphp
@section('content')
     <div class="wrapper">
        <section class="product-list-page padding-top padding-bottom ">
            <div class="container">
                <div class="tabs-wrapper">
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
                        @if($products['Start shopping']->count() > 0)
                            @foreach($products as $key => $items)
                                <div class="tab-content {{ $key == $categorie_name ? 'active' : '' }}" id="tab-{!! preg_replace('/[^A-Za-z0-9\-]/', '_', $key)!!}">
                                    <div class="row product-row ">
                                        @if($items->count() > 0)
                                            @foreach($items as $product)
                                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 product-card">
                                                    <div class="product-card-inner">
                                                        <div class="product-img">
                                                            <a href="{{route('store.product.product_view',[$store->slug,$product->id])}}">
                                                                @if(!empty($product->is_cover) )
                                                                    <img src="{{$imgpath.$product->is_cover}}" alt="">
                                                                @else
                                                                    <img src="{{asset(Storage::url('uploads/is_cover_image/default.jpg'))}}" alt="">
                                                                @endif
                                                            </a>
                                                        </div>
                                                        <div class="product-content">
                                                            <div class="product-rating">
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
                                                            <h5>
                                                                <a href="{{route('store.product.product_view',[$store->slug,$product->id])}}">{{$product->name}}</a>
                                                            </h5>
                                                            <p>{{__('Category')}}: {{$product->product_category()}}</p>
                                                            <div class="product-content-bottom">
                                                                @if($product['enable_product_variant'] == 'on')
                                                                    <div class="price">
                                                                        <ins>{{__('In variant')}}</ins>
                                                                    </div>
                                                                    <a href="{{route('store.product.product_view',[$store->slug,$product->id])}}" class="btn cart-btn"><i class="fas fa-shopping-basket"></i></a>
                                                                    @if(Auth::guard('customers')->check())
                                                                        @if(!empty($wishlist) && isset($wishlist[$product->id]['product_id']))
                                                                            @if($wishlist[$product->id]['product_id'] != $product->id)
                                                                                <a class="btn-secondary wish-btn add_to_wishlist wishlist_{{$product->id}}" data-id="{{$product->id}}"><i class="far fa-heart"></i></a>
                                                                            @else
                                                                                <a class="btn-secondary wish-btn"><i class="fas fa-heart" data-id="{{$product->id}}"></i></a>
                                                                            @endif
                                                                        @else
                                                                            <a class="btn-secondary wish-btn add_to_wishlist wishlist_{{$product->id}}" data-id="{{$product->id}}"><i class="far fa-heart"></i></a>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    <div class="price">
                                                                        <ins>{{\App\Models\Utility::priceFormat($product->price)}}</ins>
                                                                    </div>
                                                                    <a class="btn cart-btn add_to_cart" data-id="{{$product->id}}">{{__('Add to cart')}}<i class="fas fa-shopping-basket"></i></a>
                                                                    @if(Auth::guard('customers')->check())
                                                                        @if(!empty($wishlist) && isset($wishlist[$product->id]['product_id']))
                                                                            @if($wishlist[$product->id]['product_id'] != $product->id)
                                                                                <a class="btn-secondary wish-btn add_to_wishlist wishlist_{{$product->id}}" data-id="{{$product->id}}"><i class="far fa-heart"></i></a>
                                                                            @else
                                                                                <a class="btn-secondary wish-btn"><i class="fas fa-heart" data-id="{{$product->id}}"></i></a>
                                                                            @endif
                                                                        @else
                                                                            <a class="btn-secondary wish-btn add_to_wishlist wishlist_{{$product->id}}" data-id="{{$product->id}}"><i class="far fa-heart"></i></a>
                                                                        @endif
                                                                    @endif 
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="col-12 d-flex justify-content-center product-card">
                                                <h6 class="no_record"><i class="fas fa-ban"></i> {{__('No Record Found')}}</h6>
                                            </div>
                                        @endif
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
            </div>
        </section>
    </div>
@endsection
