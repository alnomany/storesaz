@extends('storefront.layout.theme2')
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
    @if($storethemesetting['enable_header_img'] == 'on')
        <section class="home-banner-section" style="background-image: url({{asset(Storage::url('uploads/store_logo/'.(!empty($storethemesetting['header_img'])?$storethemesetting['header_img']:'home-banner1.png')))}});">
            <div class="banner-text">
                <h2>{{__('Products you purchased')}}
                </h2>
                <a href="{{route('store.slug',$store->slug)}}" class="cart-btn">{{__('Back to home')}}
                    <i class="fas fa-shopping-basket"></i>
                </a>
            </div>
        </section>
    @endif
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
                                            <span >{{\App\Models\Utility::dateFormat($order->created_at)}}</span>
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
                        show_toastr('Error', response.message, 'error');
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
