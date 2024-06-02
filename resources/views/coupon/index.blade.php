@extends('layouts.admin')
@section('page-title')
    {{__('Coupons')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block text-white font-weight-bold mb-0 ">{{__('Coupons')}}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Coupons') }}</li>
@endsection

@section('action-btn')
@can('Create Coupans')
    <a class="btn btn-sm btn-icon  btn-primary me-2 text-white" data-url="{{ route('coupons.create') }}" data-title="{{ __('Add Coupon') }}" data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create') }}">
        <i  data-feather="plus"></i>
    </a>
@endcan
@endsection

@push('script-page')
    <script>
        $(document).on('click', '#code-generate', function () {
            var length = 10;
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            $('#auto-code').val(result);
        });
    </script>
@endpush
@section('content')
 <!-- [ Main Content ] start -->
 <div class="row">
    <!-- [ basic-table ] start -->
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table dataTable">
                        <thead>
                            <tr>
                                <th> {{__('Name')}}</th>
                                <th> {{__('Code')}}</th>
                                <th> {{__('Discount (%)')}}</th>
                                <th> {{__('Limit')}}</th>
                                <th> {{__('Used')}}</th>
                                <th> {{__('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($coupons as $coupon)
                            <tr>
                                <td>{{ $coupon->name }}</td>
                                <td>{{ $coupon->code }}</td>
                                <td>{{ $coupon->discount }}</td>
                                <td>{{ $coupon->limit }}</td>
                                <td>{{ $coupon->used_coupon() }}</td>
                                <td class="Action">
                                    <div class="d-flex">
                                        @can('Show Coupans')
                                            <a href="{{ route('coupons.show', $coupon->id) }}" class="btn btn-sm btn-icon  bg-light-secondary me-2" data-tooltip="Edit" data-original-title="{{ __('View') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('View Coupon') }}" data-tooltip="View">
                                                <i  class="ti ti-eye f-20"></i>
                                            </a>
                                        @endcan
                                        @can('Edit Coupans')
                                            <a href="#!" class="btn btn-sm btn-icon  bg-light-secondary me-2" data-url="{{ route('coupons.edit', $coupon->id) }}"  data-title="{{ __('Edit Coupon') }}" data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit') }}">
                                                <i  class="ti ti-edit f-20"></i>
                                            </a>
                                        @endcan
                                        @can('Delete Coupans')
                                            <a class="bs-pass-para btn btn-sm btn-icon bg-light-secondary" href="#"
                                                data-title="{{ __('Delete Lead') }}"
                                                data-confirm="{{ __('Are You Sure?') }}"
                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                data-confirm-yes="delete-form-{{ $coupon->id }}"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __('Delete') }}">
                                                <i class="ti ti-trash f-20"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['coupons.destroy', $coupon->id], 'id' => 'delete-form-' . $coupon->id]) !!}
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
    <!-- [ basic-table ] end -->
</div>
<!-- [ Main Content ] end -->
@endsection
