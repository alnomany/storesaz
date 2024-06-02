@extends('storefront.layout.theme1')
@section('page-title')
    {{__('Customer Home')}}
@endsection

@php
    if (!empty(session()->get('lang'))) {
        $currantLang = session()->get('lang');
    } else {
        $currantLang = $store->lang;
    }
    \App::setLocale($currantLang);
@endphp
@section('content')
@section('head-title')
    {{__('Welcome').', '.\Illuminate\Support\Facades\Auth::guard('customers')->user()->name}}
@endsection
@section('content')
<div class="wrapper">
    <section class="main-home-first-section" style="background-image:url({{asset(Storage::url('uploads/store_logo/'.(!empty($storethemesetting['header_img'])?$storethemesetting['header_img']:'home-banner.png')))}}); background-position: center center;">
        <div class="container">
            <div class="banner-content">
                <h1>{{__('Products you purchased')}}</h1>
               
                <a href="{{route('store.slug',$store->slug)}}" class="btn">{{__('Back to home')}}
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492.004 492.004"  xml:space="preserve">
                        <g>
                            <g>
                                <path d="M382.678,226.804L163.73,7.86C158.666,2.792,151.906,0,144.698,0s-13.968,2.792-19.032,7.86l-16.124,16.12    c-10.492,10.504-10.492,27.576,0,38.064L293.398,245.9l-184.06,184.06c-5.064,5.068-7.86,11.824-7.86,19.028    c0,7.212,2.796,13.968,7.86,19.04l16.124,16.116c5.068,5.068,11.824,7.86,19.032,7.86s13.968-2.792,19.032-7.86L382.678,265    c5.076-5.084,7.864-11.872,7.848-19.088C390.542,238.668,387.754,231.884,382.678,226.804z"/>
                            </g>
                        </g>
                     </svg>
                </a>
            </div>
        </div>
    </section>
    <section class="order-section padding-top">
        <div class="container">
            @if(!empty($orders) && count($orders) > 0)
                <div class="order-table">
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead>
                                <tr>
                                    <th>{{__('Order')}}</th>
                                    <th>{{__('Date')}}</th>
                                    <th>{{__('Value')}}</th>
                                    <th>{{__('Payment Type')}}</th>
                                    <th class="text-right">{{__("Action")}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)                   
                                    <tr>
                                        <td>
                                            <a href="{{route('customer.order',[$store->slug,Crypt::encrypt($order->id)])}}" class="">
                                                <span>{{'#'.$order->order_id}}</span>
                                            </a>
                                        </td>
                                        <td>
                                            <span>{{\App\Models\Utility::dateFormat($order->created_at)}}</span>
                                        </td>
                                        <td>
                                            <span>{{\App\Models\Utility::priceFormat($order->price)}}</span>
                                        </td>
                                        <td>
                                            <span>{{$order->payment_type}}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-end">
                                                @if($order->status != 'Cancel Order')
                                                    <button type="button" class="table-btn">
                                                        @if($order->status == 'pending')
                                                            <i class="fas fa-check soft-success"></i>
                                                        @else
                                                            <i class="fa fa-check-double soft-success"></i>
                                                        @endif
                                                        @if($order->status == 'pending')
                                                            <span>
                                                                {{__('Pending')}}: {{\App\Models\Utility::dateFormat($order->created_at)}}
                                                            </span>
                                                        @else
                                                            <span>
                                                                {{__('Delivered')}}: {{\App\Models\Utility::dateFormat($order->updated_at)}}
                                                            </span>
                                                        @endif
                                                    </button>
                                                @else
                                                    <button type="button" class="table-btn">
                                                        @if($order->status == 'pending')
                                                            <i class="fas fa-check soft-success"></i>
                                                        @else
                                                            <i class="fa fa-check-double soft-success"></i>
                                                        @endif
                                                        <span>
                                                            {{__('Cancel Order')}}: {{\App\Models\Utility::dateFormat($order->created_at)}}
                                                        </span>
                                                    </button>
                                                @endif
                                                <!-- Actions -->
                                                <div class="eye-btn">
                                                    <a href="{{route('customer.order',[$store->slug,Crypt::encrypt($order->id)])}}" class="" data-toggle="tooltip" data-title="{{__('Details')}}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
            <tr>
                <td colspan="7">
                    <div class="text-center">
                        <i class="fas fa-folder-open text-gray" style="font-size: 48px;"></i>
                        <h2>{{ __('Opps...') }}</h2>
                        <h6> {!! __('No data Found.') !!} </h6>
                    </div>
                </td>
            </tr>
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
                        console.log(response.status);
                        show_toastr('Error', response.error, 'error');
                    }
                },
                error: function (result) {
                }
            });
        });

        $(".productTab").click(function (e) {
            e.preventDefault();
            $('.productTab').removeClass('active')

        });

        $("#pro_scroll").click(function () {
            $('html, body').animate({
                scrollTop: $("#pro_items").offset().top
            }, 1000);
        });
    </script>
@endpush
