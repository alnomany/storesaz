@extends('layouts.admin')
@section('page-title')
    {{ __('Product') }}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block text-white font-weight-bold mb-0 ">{{ __('Product') }}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Product') }}</li>
@endsection
@section('action-btn')
    <a class="btn btn-sm btn-icon  bg-light-secondary me-2" href="{{ route('product.export') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Export') }}"> 
        <i  data-feather="download"></i>
    </a>
    @can('Create Products')
        <a href="#!" class="btn btn-sm btn-icon  bg-light-secondary me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Import') }}" data-ajax-popup="true" data-size="lg" data-title="{{ __('Import Product CSV File') }}" data-url="{{ route('product.file.import') }}">
            <i  data-feather="upload"></i>
        </a>
    @endcan
    <a href="{{ route('product.index') }}" class="btn btn-sm btn-icon  bg-light-secondary me-2" data-bs-toggle="tooltip"
            data-bs-placement="top" title="{{ __('List View') }}"><i class="fas fa-list"></i></a>
    @can('Create Products')
        <a class="btn btn-sm btn-icon  btn-primary me-2" href="{{ route('product.create') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create') }}">
            <i  data-feather="plus"></i>
        </a>
    @endcan
@endsection
@section('filter')
@endsection
@section('content')
    <div class="row">
        @foreach ($products as $product)
            <div class="col-lg-3 col-sm-6 col-md-6">
                <div class="card text-white text-center">
                    <div class="card-header border-0 pb-0">
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" style="">
                                    @can('Show Products')
                                        <a href="{{ route('product.show', $product->id) }}" class="dropdown-item"><i
                                                class="fas fa-eye"></i>
                                            <span>{{ __('View') }}</span></a>
                                    @endcan
                                    @can('Edit Products')
                                        <a href="{{ route('product.edit', $product->id) }}" class="dropdown-item"><i
                                                class="ti ti-edit"></i>
                                            <span>{{ __('Edit') }}</span></a>
                                    @endcan
                                    @can('Delete Products')
                                        <a class="bs-pass-para dropdown-item trigger--fire-modal-1" href="#"
                                            data-title="{{ __('Delete Lead') }}" data-confirm="{{ __('Are You Sure?') }}"
                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                            data-confirm-yes="delete-form-{{ $product->id }}">
                                            <i class="ti ti-trash"></i><span>{{ __('Delete') }} </span>

                                        </a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['product.destroy', $product->id], 'id' => 'delete-form-' . $product->id]) !!}
                                        {!! Form::close() !!}
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body product_card">
                        @if (!empty($product->is_cover))
                        <a href="{{ asset(Storage::url('uploads/is_cover_image/' . $product->is_cover)) }}" target="_blank">
                            <img alt="Image placeholder"
                                src="{{ asset(Storage::url('uploads/is_cover_image/' . $product->is_cover)) }}"
                                class="img-fluid rounded-circle card-avatar" alt="images">
                        </a>
                        @else
                        <a href="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}" target="_blank">
                            <img alt="Image placeholder"
                                src="{{ asset(Storage::url('uploads/is_cover_image/default.jpg')) }}"
                                class="img-fluid rounded-circle card-avatar" alt="images">
                        </a>
                        @endif
                        <h4 class="text-primary mt-2"> <a
                                href="{{ route('product.show', $product->id) }}">{{ $product->name }}</a></h4>
                        <h4 class="text-muted">
                            <small>{{ \App\Models\Utility::priceFormat($product->price) }}</small>
                        </h4>
                        @if ($product->enable_product_variant != 'on')
                            @if ($product->quantity == 0)
                                <span class="badge bg-danger p-2 px-3 rounded">
                                    {{ __('Out of stock') }}
                                </span>
                            @else
                                <span class="badge bg-primary p-2 px-3 rounded">
                                    {{ __('In stock') }}
                                </span>
                            @endif
                        @endif
                        <div class="row mt-1">
                            <div class="col-12 col-sm-12">
                                <div>
                                    @for ($i = 1; $i <= 5; $i++)
                                        @php
                                            $icon = 'fa-star';
                                            $color = '';
                                            $newVal1 = $i - 0.5;
                                            if ($product->product_rating() < $i && $product->product_rating() >= $newVal1) {
                                                $icon = 'fa-star-half-alt';
                                            }
                                            if ($product->product_rating() >= $newVal1) {
                                                $color = 'text-warning';
                                            } else {
                                                $color = 'text-black';
                                            }
                                        @endphp
                                        <i class="fas {{ $icon . ' ' . $color }}"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        @can('Create Products')
            <div class="col-md-3">
                <a href="{{ route('product.create') }}" class="btn-addnew-project" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create Product')}}">
                    <div class="bg-primary proj-add-icon">
                        <i class="ti ti-plus"></i>
                    </div>
                    <h6 class="mt-4 mb-2">{{ __('New Product') }}</h6>
                    <p class="text-muted text-center">{{ __('Click here to add New Product') }}</p>
                </a>
            </div>
        @endcan
    </div>

@endsection
