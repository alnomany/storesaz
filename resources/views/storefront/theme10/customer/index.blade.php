@extends('storefront.layout.theme10')
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
    <section class="customer-home padding-top padding-bottom">
        <div class="container">
            @if ($storethemesetting['enable_header_img'] == 'on')
                <div class="section-title d-flex align-items-center justify-content-between">
                    <h2><b>{{ __('Products you purchased') }}</b></h2>
                    <a href="{{ route('store.slug', $store->slug) }}" class="btn">{{ __('Back to home') }}  <i class="fas fa-home"></i></a>
                </div>
            @endif
            <div class="purchased-list">
                @if (!empty($orders) && count($orders) > 0)
                    <table class="purchased-tabel">
                        <thead>
                            <tr>
                                <th>{{ __('Order') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Value') }}</th>
                                <th>{{ __('Payment Type') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td><a href="{{ route('customer.order', [$store->slug, Crypt::encrypt($order->id)]) }}">{{$order->order_id[0] == '#' ?  $order->order_id : '#' .$order->order_id }}</a></td>
                                    <td>{{ \App\Models\Utility::dateFormat($order->created_at) }}</td>
                                    <td>{{ \App\Models\Utility::priceFormat($order->price) }}</td>
                                    <td>{{ $order->payment_type }}</td>
                                    <td>
                                        <div class="actions-wrapper">
                                            @if ($order->status != 'Cancel Order')
                                                <span class="badge rounded-pill">
                                                    @if ($order->status == 'pending')
                                                        <i class="fas fa-check soft-success"></i>
                                                    @else
                                                        <i class="fa fa-check-double soft-success"></i>
                                                    @endif
                                                    @if ($order->status == 'pending')
                                                        {{ __('Pending') }}:
                                                        {{ \App\Models\Utility::dateFormat($order->created_at) }}
                                                    @else
                                                        {{ __('Delivered') }}:
                                                        {{ \App\Models\Utility::dateFormat($order->updated_at) }}
                                                    @endif
                                                </span>
                                            @else
                                                <span class="badge rounded-pill">
                                                    @if ($order->status == 'pending')
                                                        <i class="fas fa-check soft-success"></i>
                                                    @else
                                                        <i class="fa fa-check-double soft-success"></i>
                                                    @endif
                                                    {{ __('Cancel Order') }}:
                                                    {{ \App\Models\Utility::dateFormat($order->created_at) }}
                                                </span>
                                            @endif
                                            <a href="{{ route('customer.order', [$store->slug, Crypt::encrypt($order->id)]) }}" class="view-btn"><i class="fas fa-eye"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
