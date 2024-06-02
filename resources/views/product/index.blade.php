@extends('layouts.admin')
@section('page-title')
    {{ __('Products') }}
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ __('Products') }}</li>
@endsection
@section('action-btn')
<div class="pr-2">
    <a class="btn btn-sm btn-icon  bg-light-secondary me-2" href="{{ route('product.export') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Export') }}"> 
        <i  data-feather="download"></i>
    </a>
    @can('Create Products')
        <a href="#!" class="btn btn-sm btn-icon  bg-light-secondary me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Import') }}" data-ajax-popup="true" data-size="lg" data-title="{{ __('Import Product CSV File') }}" data-url="{{ route('product.file.import') }}">
            <i  data-feather="upload"></i>
        </a>
    @endcan
    @can('Create Products')
        <a class="btn btn-sm btn-icon  btn-primary me-2" href="{{ route('product.create') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create') }}">
            <i  data-feather="plus"></i>
        </a>
    @endcan
    <a class="btn btn-sm btn-icon  bg-light-secondary" href="{{ route('product.grid') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Grid View') }}">
        <i  class="ti ti-grid-dots f-30"></i>
    </a>
</div>
@endsection
@php
    $logo=\App\Models\Utility::get_file('uploads/is_cover_image/');
@endphp
@section('filter')
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{ asset('custom/libs/summernote/summernote-bs4.css') }}">
@endpush
@push('script-page')
    <script src="{{ asset('custom/libs/summernote/summernote-bs4.js') }}"></script>
@endpush
@section('content')
<div class="row">
    <!-- [ sample-page ] start -->
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table dataTable" id="pc-dt-satetime-sorting">
                        <thead>
                            <tr>
                                <th>{{ __('Products') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('Price') }}</th>
                                <th>{{ __('Quantity') }}</th>
                                <th>{{ __('Stock') }}</th>
                                <th>{{ __('Created at') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if (!empty($product->is_cover))
                                                <img src="{{$logo.(isset($product->is_cover) && !empty($product->is_cover)?$product->is_cover:'default.jpg')}}" alt="" class="theme-avtar">
                                            @else
                                                <img src="{{$logo.(isset($product->is_cover) && !empty($product->is_cover)?$product->is_cover:'default.jpg')}}" alt="" class="theme-avtar">
                                            @endif
                                            <div class="ms-3">
                                                <a href="{{ route('product.show', $product->id) }}" class="text-dark f-w-700">{{ $product->name }}</a>
                                                <div class="mt-2 d-flex align-items-center">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @php
                                                            $icon = 'fa-star';
                                                            $color = '';
                                                            $newVal1 = $i - 0.5;
                                                            if ($product->product_rating() < $i && $product->product_rating() >= $newVal1) {
                                                                $icon = 'fa-star-half-alt';
                                                            }
                                                            if ($product->product_rating() >= $newVal1) {
                                                                $color = 'text-success';
                                                            }
                                                        @endphp
                                                        <i class="fa fa-solid  {{ $icon . ' ' . (!empty($color) ? $color : 'text-secondary') }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ !empty($product->product_category()) ? $product->product_category() : '-' }}</td>
                                    <td>
                                        @if ($product->enable_product_variant == 'on')
                                            {{ __('In Variant') }}
                                        @else
                                            {{ \App\Models\Utility::priceFormat($product->price) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($product->enable_product_variant == 'on')
                                            {{ __('In Variant') }}
                                        @else
                                            {{ $product->quantity }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($product->enable_product_variant == 'on')
                                        <span class="badge rounded p-2 f-w-600  bg-light-primary">{{ __('In Variant') }}</span>
                                        @else
                                            @if ($product->quantity == 0)
                                                <span class="badge rounded p-2 f-w-600  bg-light-danger">  {{ __('Out of stock') }}</span>
                                            @else
                                                <span class="badge rounded p-2 f-w-600  bg-light-primary"> {{ __('In stock') }}</span>
                                            @endif
                                        @endif
                                        
                                    </td>
                                    <td>
                                        {{ \App\Models\Utility::dateFormat($product->created_at) }}
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            @can('Show Products')
                                                <a href="{{ route('product.show', $product->id) }}" class="btn btn-sm btn-icon  bg-light-secondary me-2" data-toggle="tooltip" data-original-title="{{ __('View') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('View') }}" data-tooltip="View">
                                                    <i  class="ti ti-eye f-20"></i>
                                                </a>
                                            @endcan
                                            @can('Edit Products')
                                                <a class="btn btn-sm btn-icon  bg-light-secondary me-2" href="{{ route('product.edit', $product->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit') }}">
                                                    <i  class="ti ti-edit f-20"></i>
                                                </a>
                                            @endcan
                                            @can('Delete Products')
                                                <a class="bs-pass-para btn btn-sm btn-icon bg-light-secondary" href="#"
                                                    data-title="{{ __('Delete Lead') }}"
                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                    data-confirm-yes="delete-form-{{ $product->id }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Delete') }}">
                                                    <i class="ti ti-trash f-20"></i>
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['product.destroy', $product->id], 'id' => 'delete-form-' . $product->id]) !!}
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
        $(document).on('click', '#billing_data', function() {
            $("[name='shipping_address']").val($("[name='billing_address']").val());
            $("[name='shipping_city']").val($("[name='billing_city']").val());
            $("[name='shipping_state']").val($("[name='billing_state']").val());
            $("[name='shipping_country']").val($("[name='billing_country']").val());
            $("[name='shipping_postalcode']").val($("[name='billing_postalcode']").val());
        })
    </script>
@endpush