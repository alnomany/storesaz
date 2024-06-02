@extends('storefront.layout.theme6')
@section('page-title')
    {{__('Register')}}
@endsection
@php
if (!empty(session()->get('lang'))) {
    $currantLang = session()->get('lang');
} else {
    $currantLang = $store->lang;
}
\App::setLocale($currantLang);
@endphp
@push('css-page')

@endpush
@section('content')
  <div class="wrapper">
        <section class="login-section padding-top padding-bottom ">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-7 col-md-12 col-12">
                        <div class="login-form">
                            <div class="section-title">
                                <h2>Customer Register</h2>
                            </div>
                           {!! Form::open(['route' => ['store.userstore', $slug]], ['method' => 'post']) !!}
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Full Name</label>
                                    <input name="name" class="form-control" type="text" placeholder="Full Name *" required="required">
                                </div>
                                @error('name')
                                    <span class="error invalid-email text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email</label>
                                    <input name="email" class="form-control" type="email" placeholder="Email *" required="required">
                                </div>
                                @error('email')
                                    <span class="error invalid-email text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Number</label>
                                    <input name="phone_number" class="form-control" type="text" placeholder="Number *" required="required">
                                </div>
                                @error('number')
                                    <span class="error invalid-email text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Password</label>
                                    <input name="password" class="form-control" type="password" placeholder="Password *" required="required">
                                </div>
                                @error('password')
                                    <span class="error invalid-email text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Confirm Password</label>
                                    <input name="password_confirmation" class="form-control" type="password" placeholder="Confirm Password *" required="required">
                                </div>
                                @error('password_confirmation')
                                    <span class="error invalid-email text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="last-btns">
                                    <button class="login-btn btn" type="submit">Register</button>
                                    <p>By using the system, you accept the
                                        <a href="terms.html"> Privacy Policy </a> and <a href="about-us.html"> System Regulations. </a>
                                    </p>
                                </div>
                                <p class="register-btn">Already registered ? <a href="{{ route('customer.loginform', $slug) }}">Log in</a></p>
                             {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('script-page')
@endpush
