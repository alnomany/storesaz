@extends('layouts.admin')
@section('page-title')
    {{ __('Shipping') }}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block text-white font-weight-bold mb-2">{{ __('Shipping') }}</h5>
    </div>
@endsection
@push('css-page')
    <style>
        .btn-sm {
            padding: 0.5rem 0.5rem;
        }
    </style>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Shipping') }}</li>
@endsection
@section('action-btn')
   
    <a class="btn btn-sm btn-icon  bg-light-secondary me-2" href="{{ route('shipping.export') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Export') }}"> 
        <i  data-feather="download"></i>
    </a>
    @can('Create Shipping')
        <a href="#!" class="btn btn-sm btn-icon  bg-light-secondary me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Import') }}" data-ajax-popup="true" data-size="lg" data-title="{{ __('Import Shipping CSV File') }}" data-url="{{ route('shipping.file.import') }}">
            <i  data-feather="upload"></i>
        </a>
    @endcan
@endsection
@section('content')
    <!-- [ sample-page ] start -->
    <div class="col-sm-4 col-md-4 col-xxl-3">
        <div class="p-2 card mt-2">
            <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-user-tab-1" data-bs-toggle="pill"
                        data-bs-target="#pills-user-1" type="button"> <i
                            class="fas fa-location-arrow mx-2"></i>{{ __('Location') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-user-tab-2" data-bs-toggle="pill"
                        data-bs-target="#pills-user-2" type="button"> <i class="fas fa-shipping-fast mx-2"></i>
                        {{ __('Shipping') }}</button>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-sm-12 col-md-12 col-xxl-12">
        <div class="card">
            <div class="card-body">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-user-1" role="tabpanel"
                        aria-labelledby="pills-user-tab-1">
                        <div class="d-flex justify-content-between">
                            <h3 class="mb-0">{{ __('Location') }}</h3>
                            @can('Create Location')
                                <a class="btn btn-sm btn-icon  btn-primary me-2 text-white" data-url="{{ route('location.create') }}" data-title="{{ __('Create New Location') }}" data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create New Location') }}">
                                    <i  data-feather="plus"></i>
                                </a>
                            @endcan
                        </div>
                        <div class="row mt-3">
                                <div class="card-body table-border-style">
                                <div class="table-responsive">
                                    <table class="table mb-0 dataTable ">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Created At') }}</th>
                                                <th class="text-right">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($locations as $location)
                                                <tr data-name="{{ $location->name }}">
                                                    <td>{{ $location->name }}</td>
                                                    <td>{{ \App\Models\Utility::dateFormat($location->created_at) }}</td>
                                                    <td>
                                                        <div class="d-flex">
                                                            @can('Edit Location')
                                                                <a href="#!" class="btn btn-sm btn-icon  bg-light-secondary me-2" data-title="{{ __('Edit Location') }}" data-url="{{ route('location.edit', $location->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit') }}">
                                                                    <i  class="ti ti-edit f-20"></i>
                                                                </a>
                                                            @endcan
                                                            @can('Delete Location')
                                                                <a class="bs-pass-para btn btn-sm btn-icon bg-light-secondary" href="#"
                                                                    data-title="{{ __('Delete Lead') }}"
                                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                    data-confirm-yes="delete-form-{{ $location->id }}"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ __('Delete') }}">
                                                                    <i class="ti ti-trash f-20"></i>
                                                                </a>
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['location.destroy', $location->id], 'id' => 'delete-form-' . $location->id]) !!}
                                                                {!! Form::close() !!}
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
                    <div class="tab-pane fade" id="pills-user-2" role="tabpanel" aria-labelledby="pills-user-tab-2">
                        <div class="d-flex justify-content-between">
                            <h3 class="mb-0"> {{ __('Shipping') }}</h3>
                            @can('Create Shipping')
                                <a class="btn btn-sm btn-icon  btn-primary me-2 text-white" data-url="{{ route('shipping.create') }}" data-title="{{ __('Create New Shipping') }}" data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create New Shipping') }}">
                                    <i  data-feather="plus"></i>
                                </a>
                            @endcan
                        </div>

                        <div class="row mt-3">
                                <div class="card-body table-border-style">
                                <div class="table-responsive">
                                    <table class="table mb-0 dataTable1 ">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Price') }}</th>
                                                <th>{{ __('Location') }}</th>
                                                <th>{{ __('Created At') }}</th>
                                                <th class="text-right">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($shippings as $shipping)
                                                <tr data-name="{{ $shipping->name }}">
                                                    <td>{{ $shipping->name }}</td>
                                                    <td>{{ \App\Models\Utility::priceFormat($shipping->price) }}</td>
                                                    <td>{{ !empty($shipping->locationName()) ? $shipping->locationName() : '-' }}
                                                    </td>
                                                    <td>{{ \App\Models\Utility::dateFormat($shipping->created_at) }}</td>
                                                    <td class="Action">
                                                        <div class="d-flex">
                                                            @can('Edit Shipping')
                                                                <a href="#!" class="btn btn-sm btn-icon  bg-light-secondary me-2" data-title="{{ __('Edit Shipping') }}" data-url="{{ route('shipping.edit', $shipping->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit') }}">
                                                                    <i  class="ti ti-edit f-20"></i>
                                                                </a>
                                                            @endcan
                                                            @can('Delete Shipping')
                                                                <a class="bs-pass-para btn btn-sm btn-icon bg-light-secondary" href="#"
                                                                    data-title="{{ __('Delete Lead') }}"
                                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                    data-confirm-yes="delete-form-{{ $shipping->id }}"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="{{ __('Delete') }}">
                                                                    <i class="ti ti-trash f-20"></i>
                                                                </a>
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['shipping.destroy', $shipping->id], 'id' => 'delete-form-' . $shipping->id]) !!}
                                                                {!! Form::close() !!}
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
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection
<script>

</script>
