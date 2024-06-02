@php($customer_avatar = \App\Models\Utility::get_file('uploads/customerprofile/'))
@extends('layouts.admin')
@section('page-title')
    {{ __('Store Customers') }}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h5 d-inline-block text-white font-weight-bold mb-0 ">{{ __('Store Customers') }}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Store Customers') }}</li>
@endsection
@section('action-btn')
<a class="btn btn-sm btn-icon  bg-light-secondary me-2" href="{{ route('customer.export') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Export') }}"> 
    <i  data-feather="download"></i>
</a>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table mb-0 dataTable">
                            <thead>
                                <tr>
                                    <th> {{__('Customer Avatar')}}</th>
                                    <th> {{__('Name')}}</th>
                                    <th> {{__('Email')}}</th>
                                    <th> {{__('Phone No')}}</th>
                                    <th class="text-right"> {{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer)
                                    <tr class="font-style">
                                        <td>
                                            <div class="media align-items-center">
                                                <div>
                                                    <a href="{{$customer_avatar}}/{{$customer->avatar}}" target="_blank">
                                                    <img alt="Image placeholder" src="{{$customer_avatar}}/{{$customer->avatar}}" class="rounded-circle">
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->email}}</td>
                                        <td>{{ $customer->phone_number}}</td>
                                        <td class="Action">
                                            <div class="d-flex">
                                                @can('Show Customers')
                                                    <a href="{{ route('customer.show', $customer->id) }}" class="btn btn-sm btn-icon  bg-light-secondary me-2" data-tooltip="View" data-original-title="{{ __('View') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('View') }}" data-tooltip="View">
                                                        <i  class="ti ti-eye f-20"></i>
                                                    </a>
                                                @endcan
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
