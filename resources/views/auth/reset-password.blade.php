@extends('layouts.auth')
@section('page-title')
    {{ __('Reset Password') }}
@endsection
@section('content')
<div class="card-body">
    <div>
        <h2 class="mb-3 f-w-600">{{ __('Reset Password') }}</h2>
    </div>
    <div class="custom-login-form">
        <form method="POST" action="{{ route('password.update') }}" class="needs-validation" novalidate="">
        @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <div class="form-group">
                {{Form::label('E-Mail Address',__('E-Mail Address'),array('class' => 'form-label'))}}
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('Enter Email') }}">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                {{Form::label('Password',__('Password'),array('class' => 'form-label'))}}
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="{{ __('Enter Password') }}">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                {{Form::label('password-confirm',__('Confirm Password'),array('class' => 'form-label'))}}
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Enter Confirm Password') }}">
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-block mt-2">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>
    </div>
  </div>
@endsection
