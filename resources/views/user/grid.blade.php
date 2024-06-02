@extends('layouts.admin')
@section('page-title')
    {{__('Store')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0 text-white">{{__('Store')}}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Store') }}</li>
@endsection
@section('action-btn')
<div class="pr-2">
    <a href="{{ route('store.subDomain') }}" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="tooltip" data-bs-placement="top"
        title="{{ __('Sub Domain') }}" >{{__('Sub Domain')}}</a>

    <a href="{{ route('store.customDomain') }}" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="tooltip" data-bs-placement="top"
        title="{{ __('Custom Domain') }}" >{{__('Custom Domain')}}</a>

    <a href="{{ route('store-resource.index') }}" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="tooltip"
        data-bs-placement="top" title="{{ __('List View') }}"><i class="fas fa-list"></i></a>
    @can('Create Store')
        <a href="#"  data-size="md" data-url="{{ route('store-resource.create') }}" data-ajax-popup="true" data-title="{{__('Create New Store')}}"  class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="tooltip"
            data-bs-placement="top" title="{{ __('Create New Store') }}"><i class="ti ti-plus"></i></a>
    @endcan
</div>
@endsection

@section('filter')
@endsection
@section('content')
    @if(\Auth::user()->type = 'super admin')
        <div class="row">
            @foreach($users as $user)
            <div class="col-md-4 col-xxl-3">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" style="">
                                    @can('Edit Store')
                                        <a href="#" data-size="md" data-url="{{ route('store-resource.edit',$user->id) }}" data-ajax-popup="true"  data-title="{{ __('Edit Store') }}" class="dropdown-item"><i
                                                class="ti ti-edit"></i>
                                            <span>{{ __('Edit') }}</span>
                                        </a>
                                    @endcan
                                    @can('Upgrade Plans')
                                        <a href="#" data-size="md" data-url="{{ route('plan.upgrade',$user->id) }}" data-ajax-popup="true" data-title="{{ __('Upgrade Plan') }}" class="dropdown-item"><i class="ti ti-trophy"></i>
                                            <span>{{ __('Upgrade Plan') }}</span>
                                        </a>
                                    @endcan
                                    @can('Reset Password')
                                        <a href="#" data-size="md"
                                        data-url="{{ route('user.reset', \Crypt::encrypt($user->id)) }}"
                                        data-ajax-popup="true" data-title="{{ __('Reset Password') }}"
                                        class="dropdown-item"
                                        title="{{ __('Reset Password') }}">
                                            <i class="ti ti-key "></i>
                                            <span >{{ __('Reset Password') }} </span>
                                        </a>
                                    @endcan
                                    @if(Auth::user()->type == "super admin")
                                        <a class="dropdown-item"
                                            href="{{ route('login.with.owner', $user->id) }}"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="{{ __('Login As Owner') }}">
                                            <i class="ti ti-replace"></i>
                                            <span >{{ __('Login As Owner') }} </span>
                                        </a>

                                        <a href="#" data-size="lg"
                                            data-url="{{ route('store.links', $user->id) }}"
                                            data-ajax-popup="true" data-title="{{ __('Store Links') }}"
                                            class="dropdown-item"
                                            title="{{ __('Store Links') }}">
                                            <i class="ti ti-adjustments "></i>
                                            <span >{{ __('Store Links') }} </span>
                                        </a>
                                    @endif
                                    @if($user->id != 2)
                                        @can('Delete Store')
                                            <a class="bs-pass-para dropdown-item trigger--fire-modal-1" href="#"
                                                data-title="{{ __('Delete') }}" data-confirm="{{ __('Are You Sure?') }}"
                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                data-confirm-yes="delete-form-{{ $user->id }}">
                                                <i class="ti ti-trash"></i><span class="ms-1">{{ __('Delete') }} </span>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['store-resource.destroy', $user->id], 'id' => 'delete-form-' . $user->id]) !!}
                                            {!! Form::close() !!}
                                        @endcan
                                    @endif
                                    @if ($user->is_enable_login == 1)
                                        <a href="{{ route('users.login', \Crypt::encrypt($user->id)) }}"
                                            class="dropdown-item">
                                            <i class="ti ti-road-sign"></i>
                                            <span class="text-danger"> {{ __('Login Disable') }}</span>
                                        </a>
                                    @elseif ($user->is_enable_login == 0 && $user->password == null)
                                        <a href="#" data-url="{{ route('user.reset', \Crypt::encrypt($user->id)) }}"
                                            data-ajax-popup="true" data-size="md" class="dropdown-item login_enable"
                                            data-title="{{ __('New Password') }}" class="dropdown-item">
                                            <i class="ti ti-road-sign"></i>
                                            <span class="text-success"> {{ __('Login Enable') }}</span>
                                        </a>
                                    @else
                                        <a href="{{ route('users.login', \Crypt::encrypt($user->id)) }}"
                                            class="dropdown-item">
                                            <i class="ti ti-road-sign"></i>
                                            <span class="text-success"> {{ __('Login Enable') }}</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <div class="avatar-parent-child">
                            <img alt="" src="{{ asset(Storage::url("uploads/profile/")).'/'}}{{ !empty($user->avatar)?$user->avatar:'avatar.png' }}" class="img-fluid rounded-circle card-avatar">
                        </div>

                        <h5 class="h6 mt-4 mb-0"> {{$user->name}}</h5>
                        <a href="#" class="d-block text-sm text-muted my-4"> {{$user->email}}</a>
                        <div class="card mb-0 mt-3">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-6">
                                        <h6 class="mb-0">{{$user->countProducts($user->id)}}</h6>
                                        <p class="text-muted text-sm mb-0">{{ __('Products')}}</p>
                                    </div>
                                    <div class="col-6">{{--text-end--}}
                                        <h6 class="mb-0">{{$user->countStores($user->id)}}</h6>
                                        <p class="text-muted text-sm mb-0">{{ __('Stores')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-6 pe-0">
                                <div class="actions d-flex justify-content-between">
                                    <span class="d-block text-sm text-muted"> {{__('Plan') }} : {{ !empty($user->currentPlan->name ) ? $user->currentPlan->name : ""}}</span>
                                </div>
                                <div class="actions d-flex justify-content-between mt-1">
                                    <span class="d-block text-sm text-muted">{{__('Plan Expired') }} : {{!empty($user->plan_expire_date) ? \Auth::user()->dateFormat($user->plan_expire_date):'Unlimited'}}</span>
                                </div>
                            </div>
                            <div class="col-6 text-center Id">
                                <a href="#" data-url="{{route('owner.info', $user->id)}}" data-size="lg" data-ajax-popup="true" class="btn btn-outline-primary" data-title="{{__('Owner Info')}}">{{__('Admin Hub')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="col-md-3">
                @can('Create Store')
                    <a data-url="{{ route('store-resource.create') }}" data-size="md" class="btn-addnew-project" data-ajax-popup="true" data-title="{{__('Create New Store')}}"  ><i class="ti ti-plus text-white"></i>
                        <div class="bg-primary proj-add-icon">
                            <i class="ti ti-plus"></i>
                        </div>
                        <h6 class="mt-4 mb-2">{{ __('New Store') }}</h6>
                        <p class="text-muted text-center">{{ __('Click here to add New Store') }}</p>
                    </a>
                @endcan
            </div>
        </div>
    @endif
@endsection

@push('script-page')
{{-- Password  --}}
<script>
    $(document).on('change', '#password_switch', function() {
        if ($(this).is(':checked')) {
            $('.ps_div').removeClass('d-none');
            $('#password').attr("required", true);

        } else {
            $('.ps_div').addClass('d-none');
            $('#password').val(null);
            $('#password').removeAttr("required");
        }
    });
    $(document).on('click', '.login_enable', function() {
        setTimeout(function() {
            $('.login_field').append($('<input>', {
                type: 'hidden',
                val: 'true',
                name: 'login_enable'
            }));
        }, 2000);
    });
</script>
@endpush