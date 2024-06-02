@extends('layouts.admin')
@section('page-title')
    {{ __('Product Tax') }}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block text-white font-weight-bold mb-0 ">{{ __('Product Tax') }}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Product Tax') }}</li>
@endsection
@section('action-btn')
    @can('Create Product Tax')
        <a class="btn btn-sm btn-icon  btn-primary me-2 text-white" data-url="{{ route('product_tax.create') }}" data-title="{{ __('Create New Product Tax') }}" data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create') }}">
            <i  data-feather="plus"></i>
        </a>
    @endcan
@endsection
@section('filter')
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body table-border-style">
                <h5></h5>
                <div class="table-responsive">
                    <table class="table mb-0 dataTable ">
                        <thead>
                            <tr>
                                <th scope="col" class="sort" data-sort="name">{{ __('Tax Name') }}</th>
                                <th scope="col" class="sort" data-sort="name">{{ __('Rate %') }}</th>
                                <th class="text-right">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product_taxs as $product_tax)
                                <tr data-name="{{ $product_tax->name }}">
                                    <td >{{ $product_tax->name }}</td>
                                    <td >{{ $product_tax->rate }}</td>
                                    <td class="Action">                                          
                                        <div class="d-flex">
                                            @can('Edit Product Tax')
                                                <a href="#!" class="btn btn-sm btn-icon  bg-light-secondary me-2" data-url="{{ route('product_tax.edit', $product_tax->id) }}" data-tooltip="Edit" data-ajax-popup="true" data-title="{{ __('Edit Tax') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit') }}">
                                                    <i  class="ti ti-edit f-20"></i>
                                                </a>
                                            @endcan
                                            @can('Delete Product Tax')
                                                <a class="bs-pass-para btn btn-sm btn-icon bg-light-secondary" href="#"
                                                    data-title="{{ __('Delete Tax') }}"
                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                    data-confirm-yes="delete-form-{{ $product_tax->id }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete') }}">
                                                    <i class="ti ti-trash f-20"></i>
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['product_tax.destroy', $product_tax->id], 'id' => 'delete-form-' . $product_tax->id]) !!}
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
@endsection
@push('script-page')
    <script>
        $(document).ready(function() {
            $(document).on('keyup', '.search-user', function() {
                var value = $(this).val();
                $('.employee_tableese tbody>tr').each(function(index) {
                    var name = $(this).attr('data-name').toLowerCase();
                    if (name.includes(value.toLowerCase())) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
@endpush
