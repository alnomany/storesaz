@extends('storefront.layout.theme9')
@section('page-title')
    {{__('Cart')}}
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
    <section class="order-detail-section padding-top padding-bottom">
        <div class="container">
            @if($storethemesetting['enable_header_img'] == 'on')
                <div class="section-title order-detail-title">
                    <h3>{{__('Order detail')}}</h3>
                    <a href="{{route('customer.home',$store->slug)}}" class="btn">{{__('Back to order')}}</a>
                </div>
            @endif
            <div class="pending-btn text-right">
                @if($order->status == 'pending')
                    <a>{{__('Pending')}}</a>
                @elseif($order->status == 'Cancel Order')
                    <a>{{__('Order Canceled')}}</a>
                @else
                    <a>{{__('Delivered')}}</a>
                @endif
            </div>
            <div class="row row-gap">
                <div class="col-lg-8 col-12">
                    <div class="order-detail-card">
                        <div class="detail-header">
                            <h6>{{__('Items from Order')}} {{$order->order_id}}</h6>
                        </div>
                        <div class="order-detail-table order-table">
                            <div class="table-responsive">
                                <table class="table align-items-center">
                                    <thead>
                                        <tr>
                                            <th>{{__('Item')}}</th>
                                            <th>{{__('Quantity')}}</th>
                                            <th>{{__('Price')}}</th>
                                            <th>{{__('Total')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $sub_tax = 0;
                                            $total = 0;
                                        @endphp
                                        @foreach($order_products as $key=>$product)
                                            @if($product->variant_id != 0)
                                                <tr>
                                                    <td>
                                                        <span>{{$product->product_name .' - ( '.$product->variant_name.' )'}}</span>
                                                        @if(!empty($product->tax))
                                                            @php
                                                                $total_tax=0;
                                                            @endphp
                                                            @foreach($product->tax as $tax)
                                                                @php
                                                                    $sub_tax = ($product->variant_price* $product->quantity * $tax->tax) / 100;
                                                                    $total_tax += $sub_tax;
                                                                @endphp
                                                                {{$tax->tax_name.' '.$tax->tax.'%'.' ('.$sub_tax.')'}}
                                                            @endforeach
                                                        @else
                                                            @php
                                                                $total_tax = 0
                                                            @endphp
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span > {{$product->quantity}}</span>
                                                    </td>
                                                    <td>
                                                        <span>{{App\Models\Utility::priceFormat($product->variant_price)}}</span>
                                                    </td>
                                                    <td>
                                                        <span> {{App\Models\Utility::priceFormat($product->variant_price*$product->quantity+$total_tax)}}</span>
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td>
                                                        <span> {{$product->product_name}}</span>
                                                        @if(!empty($product->tax))
                                                            @php
                                                                $total_tax=0;
                                                            @endphp
                                                            @foreach($product->tax as $tax)
                                                                @php
                                                                    $sub_tax = ($product->price* $product->quantity * $tax->tax) / 100;
                                                                    $total_tax += $sub_tax;
                                                                @endphp
                                                                {{$tax->tax_name.' '.$tax->tax.'%'.' ('.$sub_tax.')'}}
                                                            @endforeach
                                                        @else
                                                            @php
                                                                $total_tax = 0
                                                            @endphp
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span > {{$product->quantity}}</span>
                                                    </td>
                                                    <td>
                                                        <span> {{App\Models\Utility::priceFormat($product->price)}}</span>
                                                    </td>
                                                    <td>
                                                        <span> {{App\Models\Utility::priceFormat($product->price*$product->quantity+$total_tax)}}</span>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if($order->status == 'delivered' && !empty($product->downloadable_prodcut))
                                                <tr>
                                                    <td>
                                                        <a data-id="{{$order->id}}" data-value="{{asset(Storage::url('uploads/downloadable_prodcut'.'/'.$product->downloadable_prodcut))}}" class="btn downloadable_prodcut">{{__('Download')}}
                                                        
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <h6>{{__('Get your product from here')}}</h6>
                                                    </td>   
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="order-detail-card">
                        <div class="detail-header">
                            <h6>{{__('Items from Order '). $order->order_id}}</h6>
                        </div>
                        <div class="detail-card-body">
                            <ul class="order-summery">
                                <li>
                                    <span class="sum-left">{{__('Sub Total')}} :</span>
                                    <span class="sum-right">{{App\Models\Utility::priceFormat($sub_total)}}</span>
                                </li>
                                <li>
                                    <span class="sum-left">{{__('Estimated Tax')}} :</span>
                                    <span class="sum-right">{{App\Models\Utility::priceFormat($final_taxs)}}</span>
                                </li>
                                @if(!empty($discount_price))
                                    <li>
                                        <span class="sum-left">{{ __('Apply Coupon') }} :</span>
                                        <span class="sum-right">{{$discount_price}}</span>
                                    </li>
                                @endif
                                @if(!empty($shipping_data))
                                    @if(!empty($discount_value))
                                        <li>
                                            <span class="sum-left">{{__('Shipping Price')}} :</span>
                                            <span class="sum-right">{{App\Models\Utility::priceFormat($shipping_data->shipping_price)}}</span>
                                        </li>
                                        <li>
                                            <span class="sum-left">{{__('Grand Total')}} :</span>
                                            <span class="sum-right">{{\App\Models\Utility::priceFormat($order->price + $shipping_data->shipping_price) }}</span>
                                        </li>
                                    @else
                                        <li>
                                            <span class="sum-left">{{__('Shipping Price')}} :</span>
                                            <span class="sum-right">{{App\Models\Utility::priceFormat($shipping_data->shipping_price)}}</span>
                                        </li>
                                        <li>
                                            <span class="sum-left">{{__('Grand Total')}} :</span>
                                            <span class="sum-right">{{ App\Models\Utility::priceFormat($sub_total + $shipping_data->shipping_price + $final_taxs) }}</span>
                                        </li>
                                    @endif
                                @elseif(!empty($discount_value))
                                    <li>
                                        <span class="sum-left">{{__('Grand Total')}} :</span>
                                        <span class="sum-right">{{ App\Models\Utility::priceFormat($grand_total-$discount_value) }}</span>
                                    </li>
                                @else
                                    <li>
                                        <span class="sum-left">{{__('Grand Total')}} :</span>
                                        <span class="sum-right">{{ App\Models\Utility::priceFormat($grand_total) }}</span>
                                    </li>
                                @endif
                                <li>
                                    <span class="sum-left">{{__('Payment Type')}} :</span>
                                    <span class="sum-right">{{ $order['payment_type'] }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="order-detail-card">
                        <div class="detail-header">
                            <h6>{{__('Shipping Information')}}</h6>
                        </div>
                        <div class="detail-card-body">
                            <ul class="address-info">
                                <li>
                                    <span class="address-left">{{__('Company')}}</span>
                                    <span class="address-right">{{$user_details->shipping_address}}</span>
                                </li>
                                <li>
                                    <span class="address-left">{{__('City')}}</span>
                                    <span class="address-right">{{$user_details->shipping_city}}</span>
                                </li>
                                <li>
                                    <span class="address-left">{{__('Country')}}</span>
                                    <span class="address-right">{{$user_details->shipping_country}}</span>
                                </li>
                                <li>
                                    <span class="address-left">{{__('Postal Code')}}</span>
                                    <span class="address-right">{{$user_details->shipping_postalcode}}</span>
                                </li>
                                <li>
                                    <span class="address-left">{{__('Phone')}}</span>
                                    <span class="address-right">{{$user_details->phone}}</span>
                                </li>
                                @if(!empty($location_data && $shipping_data))
                                    <li>
                                        <span class="address-left">{{__('Location')}}</span>
                                        <span class="address-right">
                                            {{$location_data->name}}</span>
                                    </li>
                                    <li>
                                        <span class="address-left">{{__('Shipping Method')}}</span>
                                        <span class="address-right">{{$shipping_data->shipping_name}}</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="order-detail-card">
                        <div class="detail-header">
                            <h6>{{__('Billing Information')}}</h6>
                        </div>
                        <div class="detail-card-body">
                            <ul class="address-info">
                                <li>
                                    <span class="address-left">{{__('Company')}}</span>
                                    <span class="address-right">{{$user_details->billing_address}}</span>
                                </li>
                                <li>
                                    <span class="address-left">{{__('City')}}</span>
                                    <span class="address-right">{{$user_details->billing_city}}</span>
                                </li>
                                <li>
                                    <span class="address-left">{{__('Country')}}</span>
                                    <span class="address-right">{{$user_details->billing_country}}</span>
                                </li>
                                <li>
                                    <span class="address-left">{{__('Postal Code')}}</span>
                                    <span class="address-right">{{$user_details->billing_postalcode}}</span>
                                </li>
                                <li>
                                    <span class="address-left">{{__('Phone')}}</span>
                                    <span class="address-right">{{$user_details->phone}}</span>
                                </li>
                                @if(!empty($location_data && $shipping_data))
                                    <li>
                                        <span class="address-left">{{__('Location')}}</span>
                                        <span class="address-right">
                                            {{$location_data->name}}</span>
                                    </li>
                                    <li>
                                        <span class="address-left">{{__('Shipping Method')}}</span>
                                        <span class="address-right">{{$shipping_data->shipping_name}}</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="order-detail-card">
                        <div class="detail-header">
                            <h6>{{__('Extra Information')}}</h6>
                        </div>
                        <div class="detail-card-body">
                            <ul class="address-info">
                                <li>
                                    <span class="address-left">{{isset($store_payment_setting['custom_field_title_1']) ? $store_payment_setting['custom_field_title_1'] : ''}}</span>
                                    <span class="address-right">{{$user_details->custom_field_title_1}}</span>
                                </li>
                                <li>
                                    <span class="address-left">{{isset($store_payment_setting['custom_field_title_2']) ? $store_payment_setting['custom_field_title_2'] : ''}}</span>
                                    <span class="address-right">{{$user_details->custom_field_title_2}}</span>
                                </li>
                                <li>
                                    <span class="address-left">{{isset($store_payment_setting['custom_field_title_3']) ? $store_payment_setting['custom_field_title_3'] : ''}}</span>
                                    <span class="address-right">{{$user_details->custom_field_title_3}}</span>
                                </li>
                                <li>
                                    <span class="address-left">{{isset($store_payment_setting['custom_field_title_4']) ? $store_payment_setting['custom_field_title_4'] : ''}}</span>
                                    <span class="address-right">{{$user_details->custom_field_title_4}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>    
</div>
@endsection
@push('script-page')
<script type="text/javascript">
     $(document).on('click', '.downloadable_prodcut', function () {

        var download_product = $(this).attr('data-value');
        var order_id = $(this).attr('data-id');

        var data = {
            download_product: download_product,
            order_id: order_id,
        }

        $.ajax({
            url: '{{ route('user.downloadable_prodcut',$store->slug) }}',
            method: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data.status == 'success') {
                    show_toastr("success", data.message+'<br> <b>'+data.msg+'<b>', data["status"]);
                    $('.downloadab_msg').html('<span class="text-success">' + data.msg + '</sapn>');
                } else {
                    show_toastr("Error", data.message+'<br> <b>'+data.msg+'<b>', data["status"]);
                }
            }
        });
    });
</script>
@endpush
