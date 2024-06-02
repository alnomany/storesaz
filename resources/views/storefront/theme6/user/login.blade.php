@extends('storefront.layout.theme6')
@section('page-title')
    {{__('Login')}}
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
                                <h2>Customer login </h2>
                            </div>
                            {!! Form::open(array('route' => array('customer.login', $slug,(!empty($is_cart) && $is_cart==true)?$is_cart:false)),['method'=>'POST']) !!}
                                <div class="form-group">
                                    <label for="exampleInputEmail1">username</label>
                                   {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter Your Email')))}}
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Password</label>
                                   {{Form::password('password',array('class'=>'form-control','id'=>'exampleInputPassword1','placeholder'=>__('Enter Your Password')))}}
                                </div>
                                <div class="last-btns">
                                    <button class="login-btn btn" type="submit">Sign in</button>
                                    <p>By using the system, you accept the
                                        <a href="terms.html"> Privacy Policy </a> and <a href="about-us.html"> System
                                            Regulations. </a>
                                    </p>
                                </div>
                                <p class="register-btn">Don't have account ? <a href="{{route('store.usercreate',$slug)}}">Register</a></p>
                           {{Form::close()}}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
@push('script-page')
    <script>
        if ('{!! !empty($is_cart) && $is_cart==true !!}') {
            show_toastr('Error', 'You need to login!', 'error');
        }
    </script>
@endpush
