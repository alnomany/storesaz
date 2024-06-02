@php
// ($store_logo = asset(Storage::url('uploads/product_image/')))
$store_logo=\App\Models\Utility::get_file('uploads/product_image/');
@endphp
@extends('layouts.admin')
@section('page-title')
    {{ __('Product Category') }}
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{__('Home')}}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{__('Product Category')}}</li>
@endsection
@section('action-btn')
<div class="pr-2">
    @can('Create Product category')
        <a href="#" class="btn btn-sm btn-icon  btn-primary me-2" data-ajax-popup="true" data-url="{{ route('product_categorie.create') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create') }}" data-title="{{ __('Create New Product Category') }}">
            <i  data-feather="plus"></i>
        </a>
    @endcan
</div>
@endsection

@section('content')
<div class="row">
    <!-- [ sample-page ] start -->
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body pb-0 table-border-style">
                <div class="table-responsive">
                    <table class="table dataTable">
                        <thead>
                            <tr>
                                <th>{{ __('Product Image') }}</th>
                                <th>{{ __('Category Name') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product_categorys as $product_category)
                                <tr data-name="{{ $product_category->name }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($product_category->categorie_img)
                                                <img src="{{ $store_logo }}/{{ $product_category->categorie_img }}" alt="" class="theme-avtar">
                                            @else
                                                <img src="{{ $store_logo }}/default.jpg" alt="" class="theme-avtar">
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $product_category->name }}</td>
                                    <td>
                                        <div class="d-flex">
                                            @can('Edit Product category')
                                                <a href="#!" class="btn btn-sm btn-icon  bg-light-secondary me-2" data-url="{{ route('product_categorie.edit', $product_category->id) }}"  data-ajax-popup="true" data-title="{{ __('Edit Category') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit') }}" data-tooltip="Edit">
                                                    <i class="ti ti-edit f-20"></i>
                                                </a>
                                            @endcan
                                            @can('Delete Product category')
                                                <a href="#!" class="bs-pass-para btn btn-sm btn-icon bg-light-secondary" data-title="{{ __('Delete Lead') }}" data-confirm="{{ __('Are You Sure?') }}"  data-text="{{ __('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="delete-form-{{ $product_category->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Delete') }}">
                                                    <i class="ti ti-trash f-20"></i>
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['product_categorie.destroy', $product_category->id], 'id' => 'delete-form-' . $product_category->id]) !!}
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
    <!-- [ sample-page ] end -->
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