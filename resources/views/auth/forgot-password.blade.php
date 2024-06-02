@extends('layouts.auth')
@section('page-title')
    {{__('Reset Password')}}
@endsection
@section('language-bar')
    @php
        $languages = App\Models\Utility::languages();
    @endphp
    <div class="lang-dropdown-only-desk">
        <li class="dropdown dash-h-item drp-language">
            <a class="dash-head-link dropdown-toggle btn" href="#"
                data-bs-toggle="dropdown" aria-expanded="false">
                <span class="drp-text"> {{ ucFirst($languages[$lang]) }}
                </span>
            </a>
            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                @foreach($languages as $code => $language)
                    <a href="{{ route('password.request', $code) }}" tabindex="0" class="dropdown-item {{ $code == $lang ? 'active':'' }}">
                        <span>{{ ucFirst($language)}}</span>
                    </a>
                @endforeach
            </div>
        </li>
    </div>
@endsection
@section('content')
<div class="card-body">
    <div>
        <h2 class="mb-3 f-w-600">{{ __('Forgot Password') }}</h2>
    </div>
    @if(session('status'))
        <div class="alert alert-danger">
            {{ session('status') }}
        </div>
    @endif
    <div class="custom-login-form">
        <form method="POST" action="{{ route('password.email') }}" class="needs-validation" novalidate="">
        @csrf
        <div class="">
            <div class="form-group mb-3">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('Enter Email') }}">
                @error('email')
                <span class="error invalid-email text-danger" role="alert">
                    <small>{{ $message }}</small>
                </span>
                @enderror
            </div>
            @if(env('RECAPTCHA_MODULE') == 'yes')
                <div class="form-group col-lg-12 col-md-12 mt-3">
                    {!! NoCaptcha::display() !!}
                    @error('g-recaptcha-response')
                    <span class="error small text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            @endif

            <div class="d-grid">
                <button class="btn btn-primary btn-submit btn-block mt-2">{{ __('Send Password Reset Link') }}  </button>
            </div>
            <p class="my-4 text-center">{{__('Back to')}}
                <a href="{{route('login',$lang)}}" class="my-4 text-primary">{{ __('Login') }}</a>
            </p>
        </div>
        </form>
    </div>
</div>
@endsection
@push('custom-scripts')
@if(env('RECAPTCHA_MODULE') == 'yes')
        {!! NoCaptcha::renderJs() !!}
@endif
@endpush
