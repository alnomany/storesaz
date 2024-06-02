@extends('storefront.layout.theme9')
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
            @if ($storethemesetting['enable_header_img'] == 'on')
                <div class="section-title order-detail-title">
                    <h3>{{ __('Products you purchased') }}</h3>
                    <a href="{{ route('store.slug', $store->slug) }}" class="btn">{{ __('Back to home') }}</a>
                </div>
            @endif
            <div class="order-table">
                @if (!empty($orders) && count($orders) > 0)
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('Order') }}</th>
                                    <th scope="col" class="sort">{{ __('Date') }}</th>
                                    <th scope="col" class="sort">{{ __('Value') }}</th>
                                    <th scope="col" class="sort">{{ __('Payment Type') }}</th>
                                    <th scope="col" class="text-right">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('customer.order', [$store->slug, Crypt::encrypt($order->id)]) }}" class="">
                                                <span>{{$order->order_id[0] == '#' ?  $order->order_id : '#' .$order->order_id }}</span>
                                            </a>
                                        </td>
                                        <td>
                                            <span >{{ \App\Models\Utility::dateFormat($order->created_at) }}</span>
                                        </td>
                                        <td>
                                            <span>{{ \App\Models\Utility::priceFormat($order->price) }}</span>
                                        </td>
                                        <td>
                                            <span>{{ $order->payment_type }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-end">
                                                @if ($order->status != 'Cancel Order')
                                                    <button type="button" class="table-btn">
                                                        @if ($order->status == 'pending')
                                                            <i class="fas fa-check soft-success"></i>
                                                        @else
                                                            <i class="fa fa-check-double soft-success"></i>
                                                        @endif
                                                        @if ($order->status == 'pending')
                                                            <span>
                                                                {{ __('Pending') }}:
                                                                {{ \App\Models\Utility::dateFormat($order->created_at) }}
                                                            </span>
                                                        @else
                                                            <span>
                                                                {{ __('Delivered') }}:
                                                                {{ \App\Models\Utility::dateFormat($order->updated_at) }}
                                                            </span>
                                                        @endif
                                                    </button>
                                                @else
                                                    <button type="button" class="table-btn">
                                                        @if ($order->status == 'pending')
                                                            <i class="fas fa-check soft-success"></i>
                                                        @else
                                                            <i class="fa fa-check-double soft-success"></i>
                                                        @endif
                                                        <span>
                                                            {{ __('Cancel Order') }}:
                                                            {{ \App\Models\Utility::dateFormat($order->created_at) }}
                                                        </span>
                                                    </button>
                                                @endif
                                                    <!-- Actions -->
                                                    <div class="eye-btn">
                                                        <a href="{{ route('customer.order', [$store->slug, Crypt::encrypt($order->id)]) }}" class="action-item mr-2" data-toggle="tooltip" data-title="Details" data-original-title="" title="">
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
