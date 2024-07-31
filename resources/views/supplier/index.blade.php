@php($customer_avatar = \App\Models\Utility::get_file('uploads/customerprofile/'))
@extends('layouts.admin')
@section('page-title')
    {{ __('Suppliers') }}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h5 d-inline-block text-white font-weight-bold mb-0 ">{{ __('Suppliers') }}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Suppliers') }}</li>
@endsection
@section('action-btn')
<div class="pr-2">
    @can('Create Product category')
        <a href="#" class="btn btn-sm btn-icon  btn-primary me-2" data-ajax-popup="true" data-url="{{ route('suppliercreate') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create') }}" data-title="{{ __('Suppliers') }}">
            <i  data-feather="plus"></i>
        </a>
    @endcan
</div>
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
                                    <th> {{__('Name')}}</th>
                                    <th> {{__('Email')}}</th>
                                    <th> {{__('Phone No')}}</th>
                                    <th> {{__('Country')}}</th>

                                    <th> {{__('City')}}</th>

                                    <th class="text-right"> {{__('Action')}}</th>
                                </tr>
                            </thead>
                            @foreach ($suppliers as $supplier)
                                    <tr class="font-style">
                                  
                                        <td>{{ $supplier->name }}</td>
                                        <td>{{ $supplier->email}}</td>
                                        <td>{{ $supplier->phone_number}}</td>
                                        <td>{{ $supplier->country}}</td>
                                        <td>{{ $supplier->city}}</td>

                                        <td class="Action">
                                            <div class="d-flex">
                                                @can('Show Customers')
                                                 <!--   <a href="{{ route('customer.show', $supplier->id) }}" class="btn btn-sm btn-icon  bg-light-secondary me-2" data-tooltip="View" data-original-title="{{ __('View') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('View') }}" data-tooltip="View">
                                                        <i  class="ti ti-eye f-20"></i>
                                                    </a>-->
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                         
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
