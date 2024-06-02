@extends('storefront.layout.theme3')
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
    @if($storethemesetting['enable_header_img'] == 'on')  
        <section class="inner-page-banner">
            <div class="offset-container offset-left">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="banner-content padding-top">
                            <div class="banner-title">
                                <h1>{{__('Products you purchased')}}</h1>
                            </div>
                            <ul class="banner-list">
                                <li>
                                    <a href="{{route('store.slug',$store->slug)}}" class="btn">{{__('Back to home')}} <i class="fa fa-angle-right"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="page-banner-img">
                            @if(isset($storethemesetting['enable_banner_img']) && $storethemesetting['enable_banner_img'] == 'on')
                                <img src="{{asset(Storage::url('uploads/store_logo/'.(!empty($storethemesetting['banner_img'])?$storethemesetting['banner_img']:'header_img_3.png')))}}" alt="">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <section class="customer-home padding-top padding-bottom">
        <div class="container">
            @if(!empty($orders) && count($orders) > 0)
                <div class="purchased-list">
                    <table class="purchased-tabel">
                        <thead>
                            <tr>
                                <th>{{__('Order')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Value')}}</th>
                                <th>{{__('Payment Type')}}</th>
                                <th>{{__("Action")}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td><a href="{{route('customer.order',[$store->slug,Crypt::encrypt($order->id)])}}">{{'#'.$order->order_id}}</a></td>
                                    <td>{{\App\Models\Utility::dateFormat($order->created_at)}}</td>
                                    <td>{{\App\Models\Utility::priceFormat($order->price)}}</td>
                                    <td>{{$order->payment_type}}</td>
                                    <td>
                                        <div class="actions-wrapper">
                                            @if($order->status != 'Cancel Order')
                                                <span class="badge rounded-pill">
                                                    @if($order->status == 'pending')
                                                        <i class="fas fa-check soft-success"></i>
                                                    @else
                                                        <i class="fa fa-check-double soft-success"></i>
                                                    @endif
                                                </span>
                                                @if($order->status == 'pending')
                                                    {{__('Pending')}}: {{\App\Models\Utility::dateFormat($order->created_at)}}
                                                @else
                                                    {{__('Delivered')}}: {{\App\Models\Utility::dateFormat($order->updated_at)}}
                                                @endif
                                            @else
                                                <span class="badge rounded-pill">
                                                    @if($order->status == 'pending')
                                                        <i class="fas fa-check soft-success"></i>
                                                    @else
                                                        <i class="fa fa-check-double soft-success"></i>
                                                    @endif
                                                </span>
                                               
                                                {{__('Cancel Order')}}: {{\App\Models\Utility::dateFormat($order->created_at)}}
                                            @endif
                                            <a href="{{route('customer.order',[$store->slug,Crypt::encrypt($order->id)])}}" data-toggle="tooltip" data-title="{{__('Details')}}" class="view-btn"><i class="fas fa-eye"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
@endpush
