@extends('storefront.layout.theme7')
@section('page-title')
    {{ __('Cart') }}
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
    {{ __('Welcome') . ', ' . \Illuminate\Support\Facades\Auth::guard('customers')->user()->name }}
@endsection
@section('content')
<div class="wrapper">
    <section class="order-section padding-top padding-bottom">
        <div class="container">
            <div class="section-title d-flex justify-content-between">
                <h3 style=" padding-bottom: 10px; ">{{__('Products you purchased')}}</h3>
                <a href="{{ route('store.slug', $store->slug) }}"
                    class="btn btn-sm btn-secondary btn-icon shadow hover-shadow-lg hover-translate-y-n3"
                    id="pro_scroll">
                    <span class="btn-inner--text">{{ __('Back to home') }}</span>
                </a>
            </div>
            <div class="order-table">
                @if(!empty($orders) && count($orders) > 0)
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
                                        <td><a href="{{route('customer.order',[$store->slug,Crypt::encrypt($order->id)])}}">{{'#'.$order->order_id}}</a></td>
                                        <td>{{\App\Models\Utility::dateFormat($order->created_at)}}</td>
                                        <td>{{\App\Models\Utility::priceFormat($order->price)}}</td>
                                        <td>{{$order->payment_type}}</td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-end">
                                                @if($order->status != 'Cancel Order')
                                                    <button type="button" class="table-btn">
                                                        @if($order->status == 'pending')
                                                            <i class="fas fa-check soft-success"></i>
                                                        @else
                                                            <i class="fa fa-check-double soft-success"></i>
                                                        @endif
                                                        <span>
                                                            @if($order->status == 'pending')
                                                                {{__('Pending')}}: {{\App\Models\Utility::dateFormat($order->created_at)}}
                                                            @else
                                                                {{__('Delivered')}}: {{\App\Models\Utility::dateFormat($order->updated_at)}}
                                                            @endif
                                                        </span>
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
                                                    <a href="{{route('customer.order',[$store->slug,Crypt::encrypt($order->id)])}}" data-toggle="tooltip" data-title="{{__('Details')}}" class="view-btn"><i class="fas fa-eye"></i></a>
                                                </div>
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
        </div>
    </section>
</div>
@endsection
@push('script-page')
@endpush
