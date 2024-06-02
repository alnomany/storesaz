@extends('storefront.layout.theme6')
@section('page-title')
    {{ __('Home') }}
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

    @if ($storethemesetting['enable_header_img'] == 'on')
        <section class="contain-product container">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="banner-contain">
                        <h1>{{ __('Products you purchased') }}</h1>
                        <p>
                        </p>
                        <a href="{{ route('store.slug', $store->slug) }}"
                            class="btn btn-sm btn-secondary btn-icon shadow hover-shadow-lg hover-translate-y-n3"
                            id="pro_scroll">
                            <span class="btn-inner--text">{{ __('Back to home') }}</span>
                            <span class="btn-inner--icon">
                                <i class="fas fa-shopping-basket"></i>
                            </span>
                        </a>
                    </div>
                </div>

            </div>
        </section>
    @endif
    <div class="wrapper">
        @if (!empty($orders) && count($orders) > 0)
            <section class="customer-home padding-top padding-bottom">
                <div class="container">
                    <div class="section-title d-flex align-items-center justify-content-between">
                        <h2><b>{{ __('Products you purchased') }}</b></h2>
                        <a href="{{ route('store.slug', $store->slug) }}" class="btn">{{ __('Back to home') }} <i class="fas fa-home"></i></a>
                    </div>
                    <div class="purchased-list">
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
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('customer.order', [$store->slug, Crypt::encrypt($order->id)]) }}">
                                                {{ $order->order_id[0] == '#' ? $order->order_id : '#' . $order->order_id }}
                                            </a>
                                        </td>
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
                                                    </span>
                                                    @if ($order->status == 'pending')
                                                        <span class="badge rounded-pill">
                                                            {{ __('Pending') }}:
                                                            {{ \App\Models\Utility::dateFormat($order->created_at) }}
                                                        </span>
                                                    @else
                                                        <span class="badge rounded-pill">
                                                            {{ __('Delivered') }}:
                                                            {{ \App\Models\Utility::dateFormat($order->updated_at) }}
                                                        </span>
                                                    @endif

                                                @else

                                                    <span class="badge rounded-pill">
                                                        @if ($order->status == 'pending')
                                                            <i class="fas fa-check soft-success"></i>
                                                        @else
                                                            <i class="fa fa-check-double soft-success"></i>
                                                        @endif
                                                    </span>
                                                    <span class="badge rounded-pill">
                                                        {{ __('Cancel Order') }}:
                                                        {{ \App\Models\Utility::dateFormat($order->created_at) }}
                                                    </span>

                                                @endif
                                                <a href="{{ route('customer.order', [$store->slug, Crypt::encrypt($order->id)]) }}"
                                                    class="view-btn" data-toggle="tooltip" data-title="Details"
                                                    data-original-title="" title="">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
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
@endsection
@push('script-page')
@endpush
