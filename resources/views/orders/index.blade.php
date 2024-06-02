    @extends('layouts.admin')
    @section('page-title')
        {{ __('Orders') }}
    @endsection
    @section('title')
        <div class="d-inline-block">
            <h5 class="h4 d-inline-block text-white font-weight-bold mb-0">{{ __('Orders') }}</h5>
        </div>
    @endsection
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ __('Orders') }}</li>
    @endsection
    @section('action-btn')
        <a class="btn btn-sm btn-icon  bg-light-secondary me-2" href="{{ route('order.export') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Export') }}"> 
            <i  data-feather="download"></i>
        </a>
    @endsection
    @php
        $user   = \Auth::user()->currentuser();
        $plan   = \App\Models\Plan::find($user->plan);
    @endphp
    @section('filter')
    @endsection
    @section('content')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    @if ($plan->storage_limit <= $user->storage_limit && $plan->storage_limit != -1)
                        <small class="text-danger mt-3 ms-3">{{ __('Your plan storage limit is over , so you can not see customer uploaded payment receipt.') }}</small>
                    @endif
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table mb-0 dataTable">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('Orders') }}</th>
                                        <th scope="col" class="sort">{{ __('Date') }}</th>
                                        <th scope="col" class="sort">{{ __('Name') }}</th>
                                        <th scope="col" class="sort">{{ __('Value') }}</th>
                                        <th scope="col" class="sort text-end">{{ __('Payment Type') }}</th>
                                        <th scope="col" class="sort text-end">{{ __('Reciept') }}</th>
                                        <th scope="col" class="sort text-center">{{ __('Status') }}</th>
                                        <th scope="col" class="sort text-center">{{ __('Payment Status') }}</th>
                                        <th scope="col" class="text-center">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <th scope="row">
                                                <a href="{{ route('orders.show', \Illuminate\Support\Facades\Crypt::encrypt($order->id)) }}"
                                                class="btn btn-sm btn-white btn-icon btn-outline-primary order2_badge" data-bs-toggle="tooltip"
                                                title="{{__('Details')}}"
                                                data-toggle="tooltip">
                                                    <span class="btn-inner--text">{{$order->order_id[0] == '#' ?  $order->order_id : '#' .$order->order_id }}</span>
                                                </a>
                                            </th>
                                            <td class="order">
                                                <span
                                                    class="h6 text-sm font-weight-bold mb-0">{{ \App\Models\Utility::dateFormat($order->created_at) }}</span>
                                            </td>
                                            <td>
                                                <span class="client">{{ $order->name }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="value text-sm mb-0">{{ \App\Models\Utility::priceFormat($order->price) }}</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="taxes text-sm mb-0">{{ $order->payment_type }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if ($plan->storage_limit <= $user->storage_limit && $plan->storage_limit != -1)
                                                    -
                                                @else
                                                    @if ($order->payment_type == 'Bank Transfer')
                                                        <a href="{{ asset(Storage::url($order->receipt)) }}" title="Invoice"
                                                            download>
                                                            <i class="fas fa-file-invoice"></i>
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex row justify-content-center">
                                                    <div class="col-auto">
                                                        @if ($order->status != 'Cancel Order')
                                                        <button type="button"
                                                            class="btn btn-sm {{ $order->status == 'pending' ? 'btn-soft-info' : 'btn-soft-success' }} btn-icon rounded-pill">
                                                            <span class="btn-inner--icon">
                                                                @if ($order->status == 'pending')
                                                                    <i class="fas fa-check soft-success"></i>
                                                                @else
                                                                    <i class="fa fa-check-double soft-success"></i>
                                                                @endif
                                                            </span>
                                                            @if ($order->status == 'pending')
                                                                <span class="btn-inner--text">
                                                                    {{ __('Pending') }}:
                                                                    {{ \App\Models\Utility::dateFormat($order->created_at) }}
                                                                </span>
                                                            @else
                                                                <span class="btn-inner--text">
                                                                    {{ __('Delivered') }}:
                                                                    {{ \App\Models\Utility::dateFormat($order->updated_at) }}
                                                                </span>
                                                            @endif
                                                        </button>
                                                        @else
                                                            <button type="button"
                                                                class="btn btn-sm btn-soft-danger btn-icon rounded-pill">
                                                                <span class="btn-inner--icon">
                                                                    @if ($order->status == 'pending')
                                                                        <i class="fas fa-check soft-success"></i>
                                                                    @else
                                                                        <i class="fa fa-check-double soft-success"></i>
                                                                    @endif
                                                                </span>
                                                                <span class="btn-inner--text">
                                                                    {{ __('Cancel Order') }}:
                                                                    {{ \App\Models\Utility::dateFormat($order->created_at) }}
                                                                </span>
                                                            </button>
                                                        @endif
                                                    </div>


                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="text-sm mb-0">{{ $order->payment_status }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex">
                                                    @can('Show Orders')
                                                        <a href="{{ route('orders.show', \Illuminate\Support\Facades\Crypt::encrypt($order->id)) }}" class="btn btn-sm btn-icon  bg-light-secondary me-2" data-toggle="tooltip" data-original-title="{{ __('View') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('View') }}" data-tooltip="View">
                                                            <i  class="ti ti-eye f-20"></i>
                                                        </a>
                                                    @endcan
                                                    @can('Delete Orders')
                                                        <a class="bs-pass-para btn btn-sm btn-icon bg-light-secondary" href="#"
                                                            data-title="{{ __('Delete Lead') }}"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $order->id }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Delete') }}">
                                                            <i class="ti ti-trash f-20"></i>
                                                        </a>
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['orders.destroy', $order->id], 'id' => 'delete-form-' . $order->id]) !!}
                                                        {!! Form::close() !!}
                                                    @endcan
                                                    @if($order->payment_status == 'pending' && $order->payment_type == 'Bank Transfer')
                                                       
                                                        <a href="#"  class="btn btn-sm btn-icon bg-light-secondary ms-2"
                                                            data-url="{{ route('bank_transfer.order.show',$order->id) }}"
                                                            data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" title=""
                                                            data-title="{{ __('Payment Status') }}"
                                                            data-bs-original-title="{{ __('Payment Status') }}">
                                                            <i class="ti ti-caret-right f-20"></i>
                                                        </a>
                                                        
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
