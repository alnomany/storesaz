@extends('layouts.admin')
@php
    $storagesetting = App\Models\Utility::StorageSettings();
    if($storagesetting['storage_setting'] == 'wasabi' || $storagesetting['storage_setting'] == 's3'){
        $logo = \App\Models\Utility::get_file('uploads/logo');
    }else{
        $logo = \App\Models\Utility::get_file('uploads/logo/');
    }
    
    $logo_img = \App\Models\Utility::getValByName('company_logo');
    $logo_light = \App\Models\Utility::getValByName('company_logo_light');
    $s_logo = \App\Models\Utility::get_file('uploads/store_logo/');
    $company_favicon = \App\Models\Utility::getValByName('company_favicon');
    $lang = \App\Models\Utility::getValByName('default_language');
    $company_logo = \App\Models\Utility::GetLogo();
    $metaimage = Utility::get_file('uploads/metaImage/');
    if (Auth::user()->type !== 'super admin') {
        $store_lang = $store_settings->lang;
    }
    
    // storage setting
    $file_type = config('files_types');
    $setting = App\Models\Utility::settings();
    
    $local_storage_validation = $setting['local_storage_validation'];
    $local_storage_validations = explode(',', $local_storage_validation);
    
    $s3_storage_validation = $setting['s3_storage_validation'];
    $s3_storage_validations = explode(',', $s3_storage_validation);
    
    $wasabi_storage_validation = $setting['wasabi_storage_validation'];
    $wasabi_storage_validations = explode(',', $wasabi_storage_validation);
    
    $setting_color = App\Models\Utility::colorset();
    
    $color = 'theme-3';
    if (!empty($setting_color['color'])) {
        $color = $setting_color['color'];
    }
    $flag = (!empty($setting['color_flag'])) ? $setting['color_flag'] : 'false';
    $plan = \App\Models\Plan::find(\Auth::user()->plan);
    $chatgpt = \App\Models\Utility::settings();

    $languages = \App\Models\Utility::languages();
@endphp
@section('page-title')
    @if (Auth::user()->type == 'super admin')
        {{ __('Settings') }}
    @else
        {{ __('Store Settings') }}
    @endif
@endsection
@section('title')
    <div class="d-inline-block">
        @if (Auth::user()->type == 'super admin')
            <h5 class="h4 d-inline-block font-weight-bold mb-0 text-white">{{ __('Settings') }}</h5>
        @else
            <h5 class="h4 d-inline-block font-weight-bold mb-0 text-white">{{ __('Store Setting') }}</h5>
        @endif
    </div>
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ __('Settings') }}</li>
@endsection
@section('action-btn')
    <ul class="nav nav-pills cust-nav   rounded  mb-3" id="pills-tab" role="tablist">
        @if (Auth::user()->type == 'super admin')
            <li class="nav-item">
                <a class="nav-link active" id="site_setting_tab" data-bs-toggle="pill" href="#pills-brand-setting"
                    role="tab" aria-controls="pills-brand-setting" aria-selected="true">{{ __('Brand Settings') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-payment-setting_tab" data-bs-toggle="pill" href="#pills-payment-setting"
                    role="tab" aria-controls="pills-payment-setting"
                    aria-selected="false">{{ __('Payment Settings') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-email-settings_tab" data-bs-toggle="pill" href="#pills-email-settings"
                    role="tab" aria-controls="pills-email-settings"
                    aria-selected="false">{{ __('Email Settings') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="recaptcha-settings_tab" data-bs-toggle="pill" href="#pills-recaptcha-settings"
                    role="tab" aria-controls="pills-recaptcha-settings-tab"
                    aria-selected="false">{{ __('ReCaptcha Settings') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="storage_settings_tab" data-bs-toggle="pill" href="#storage_settings"
                    role="tab" aria-controls="pills-storage_settings-tab"
                    aria-selected="false">{{ __('Storage Settings') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-cache_settings-tab" data-bs-toggle="pill" href="#pills-cache-settings"
                    role="tab" aria-controls="pills-cache_settings-tab"
                    aria-selected="false">{{ __('Cache Settings') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-cookie_settings-tab" data-bs-toggle="pill" href="#pills-cookie-settings"
                    role="tab" aria-controls="pills-cookie_settings-tab"
                    aria-selected="false">{{ __('Cookie Settings') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-chatgpt-tab" data-bs-toggle="pill" href="#pills-chatgpt-settings"
                    role="tab" aria-controls="pills-chatgpt-tab"
                    aria-selected="false">{{ __('Chat GPT Settings') }}</a>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link active" id="pills-brand_setting-tab" data-bs-toggle="pill" href="#pills-brand-setting"
                    role="tab" aria-controls="pills-brandsetting" aria-selected="false">{{ __('Brand Settings') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-store_setting-tab" data-bs-toggle="pill" href="#pills-store_setting"
                    role="tab" aria-controls="pills-store_setting" aria-selected="false">{{ __('Store Settings') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-store_payment-setting-tab" data-bs-toggle="pill"
                    href="#pills-store_payment-setting" role="tab" aria-controls="pills-store_payment-setting"
                    aria-selected="false">{{ __('Payment Settings') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-store_email_setting-tab" data-bs-toggle="pill"
                    href="#pills-store_email_setting" role="tab" aria-controls="pills-store_email_setting"
                    aria-selected="false">{{ __('Email Settings') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-whatsapp_custom_massage-tab" data-bs-toggle="pill"
                    href="#pills-whatsapp_custom_massage" role="tab" aria-controls="pills-whatsapp_custom_massage"
                    aria-selected="false">{{ __('Whatsapp Message Settings') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-twilio_setting-tab" data-bs-toggle="pill" href="#pills-twilio_setting"
                    role="tab" aria-controls="pills-twilio_setting"
                    aria-selected="false">{{ __('Twilio Settings') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-pixel_setting-tab" data-bs-toggle="pill" href="#pixel_settings"
                    role="tab" aria-controls="pixel_settings"
                    aria-selected="false">{{ __('Pixel Settings') }}</a>
            </li>
            @if ($plan->pwa_store == 'on')
                <li class="nav-item">
                    <a class="nav-link" id="pills-pwa_setting-tab" data-bs-toggle="pill" href="#pwa_settings"
                        role="tab" aria-controls="pwa_settings"
                        aria-selected="false">{{ __('PWA Settings') }}</a>
                </li>
            @endif
            <li class="nav-item">
                <a class="nav-link" id="pills-webhook_setting-tab" data-bs-toggle="pill" href="#webhook_settings"
                    role="tab" aria-controls="webhook_settings"
                    aria-selected="false">{{ __('Webhook Settings') }}</a>
            </li>
        @endif
    </ul>
@endsection
@section('filter')
@endsection
@push('script-page')
    <script src="{{ asset('custom/libs/summernote/summernote-bs4.js') }}"></script>
    
    <script>
        function check_theme(color_val) {
            $('.theme-color').prop('checked', false);
            $('input[value="' + color_val + '"]').prop('checked', true);
        }
    </script>
    <script>
        $(document).on('change', '[name=storage_setting]', function() {
            if ($(this).val() == 's3') {
                $('.s3-setting').removeClass('d-none');
                $('.wasabi-setting').addClass('d-none');
                $('.local-setting').addClass('d-none');
            } else if ($(this).val() == 'wasabi') {
                $('.s3-setting').addClass('d-none');
                $('.wasabi-setting').removeClass('d-none');
                $('.local-setting').addClass('d-none');
            } else {
                $('.s3-setting').addClass('d-none');
                $('.wasabi-setting').addClass('d-none');
                $('.local-setting').removeClass('d-none');
            }
        });
    </script>
    <script>
        var multipleCancelButton = new Choices(
            '#choices-multiple-remove-button', {
                removeItemButton: true,
            }
        );
        var multipleCancelButton = new Choices(
            '#choices-multiple-remove-button1', {
                removeItemButton: true,
            }
        );
        var multipleCancelButton = new Choices(
            '#choices-multiple-remove-button2', {
                removeItemButton: true,
            }
        );
    </script>
    <script>
        $(document).on('change', '#domain_switch', function() {
            if ($(this).is(':checked')) {
                $('.domain_text').show();
            } else {
                $('.domain_text').hide();
                $('.request_msg').hide();
            }
        });
    </script>
@endpush
@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            @if (Auth::user()->type == 'super admin')
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade active show" id="pills-brand-setting" role="tabpanel" aria-labelledby="pills-brand_setting-tab">
                        {{ Form::model($settings, ['route' => 'business.setting', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Brand Settings') }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-4 col-sm-6 col-md-6">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h5>{{ __('Logo dark') }}</h5>
                                                        </div>
                                                        <div class="card-body pt-0">
                                                            <div class=" setting-card">
                                                                <div class="mt-4">  {{-- logo-content --}}
                                                                    {{-- <a href="{{ asset(Storage::url('uploads/logo/logo-dark.png')) }}"
                                                                        target="_blank">
                                                                        <img src="{{ asset(Storage::url('uploads/logo/logo-dark.png')) }}"
                                                                            width="170px" class="img_setting"
                                                                            id="logoDark">
                                                                    </a> --}}
                                                                    <a href="{{$logo. '/' . (isset($logo_img) && !empty($logo_img)? $logo_img:'logo-dark.png')}}" target="_blank">
                                                                        <img id="logoDark" alt="your image" src="{{$logo. '/' . (isset($logo_img) && !empty($logo_img)? $logo_img:'logo-dark.png') . '?timestamp='. time()}}  " width="150px" class="img_setting fix-logo">
                                                                    </a>
                                                                </div>
                                                                <div class="choose-files mt-5">
                                                                    <label for="logo_dark">
                                                                        <div class=" bg-primary full_logo"> <i
                                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                        </div>
                                                                        <input type="file" name="logo_dark"
                                                                            id="logo_dark" class="form-control file"
                                                                            data-filename="logo_dark"
                                                                            onchange="document.getElementById('logoDark').src = window.URL.createObjectURL(this.files[0])">
                                                                    </label>
                                                                </div>
                                                                @error('logo_dark')
                                                                    <div class="row">
                                                                        <span class="invalid-logo" role="alert">
                                                                            <strong
                                                                                class="text-danger">{{ $message }}</strong>
                                                                        </span>
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6 col-md-6">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h5>{{ __('Logo Light') }}</h5>
                                                        </div>
                                                        <div class="card-body pt-0">
                                                            <div class=" setting-card">
                                                                <div class="mt-4">  {{-- logo-content --}}
                                                                    {{-- <a href="{{ asset(Storage::url('uploads/logo/logo-light.png')) }}"
                                                                        target="_blank">
                                                                        <img src="{{ asset(Storage::url('uploads/logo/logo-light.png')) }}"
                                                                            width="170px" class=" img_setting"
                                                                            id="logoLight">
                                                                    </a> --}}

                                                                    <a href="{{$logo. '/' . 'logo-light.png'}}" target="_blank">
                                                                        <img id="adminLogoLight" alt="your image" src="{{$logo. '/' . 'logo-light.png' . '?timestamp='. time()}}" width="170px" class="img_setting fix-logo">
                                                                    </a>
                                                                </div>
                                                                <div class="choose-files mt-5">
                                                                    <label for="logo_light">
                                                                        <div class=" bg-primary"> <i
                                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                        </div>
                                                                        <input type="file" class="form-control file"
                                                                            name="logo_light" id="logo_light"
                                                                            data-filename="logo_light"
                                                                            onchange="document.getElementById('adminLogoLight').src = window.URL.createObjectURL(this.files[0])">
                                                                    </label>
                                                                </div>
                                                                @error('logo_light')
                                                                    <div class="row">
                                                                        <span class="invalid-logo" role="alert">
                                                                            <strong
                                                                                class="text-danger">{{ $message }}</strong>
                                                                        </span>
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6 col-md-6">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h5>{{ __('Favicon') }}</h5>
                                                        </div>
                                                        <div class="card-body pt-0">
                                                            <div class=" setting-card">
                                                                <div class="logo-content mt-3">
                                                                    <a href="{{ $logo . '/' . 'favicon.png' }}"
                                                                        target="_blank">
                                                                        <img src="{{ $logo . '/' . 'favicon.png' . '?timestamp='. time() }}"
                                                                        {{-- <img src="{{ $logo . 'favicon.png' . '?timestamp='. time() }}" --}}
                                                                            width="50px" height="50px"
                                                                            class="img_setting favicon" id="adminfavicon">
                                                                    </a>
                                                                </div>
                                                                {{-- <div class="logo-content logo-set-bg  text-center py-2">
                                                                    <img src="{{ $logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}"  width="50px" class="img_setting">
                                                                </div> --}}
                                                                <div class="choose-files mt-5">
                                                                    <label for="favicon">
                                                                        <div class=" bg-primary favicon_update"> <i
                                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                        </div>
                                                                        <input type="file" class="form-control file"
                                                                            id="favicon" name="favicon"
                                                                            data-filename="favicon_update"
                                                                            onchange="document.getElementById('adminfavicon').src = window.URL.createObjectURL(this.files[0])">
                                                                    </label>
                                                                </div>
                                                                @error('favicon')
                                                                    <div class="row">
                                                                        <span class="invalid-logo" role="alert">
                                                                            <strong
                                                                                class="text-danger">{{ $message }}</strong>
                                                                        </span>
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    {{ Form::label('title_text', __('Title Text'), ['class' => 'form-label']) }}
                                                    {{ Form::text('title_text', null, ['class' => 'form-control', 'placeholder' => __('Title Text')]) }}
                                                    @error('title_text')
                                                        <span class="invalid-title_text" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('footer_text', __('Footer Text'), ['class' => 'form-label']) }}
                                                    {{ Form::text('footer_text', null, ['class' => 'form-control', 'placeholder' => __('Footer Text')]) }}
                                                    @error('footer_text')
                                                        <span class="invalid-footer_text" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-4">
                                                    {{ Form::label('default_language', __('Default Language'), ['class' => 'form-label']) }}
                                                    <div class="changeLanguage">
                                                        <select name="default_language" id="default_language"
                                                            class="form-control" data-toggle="select">
                                                            @foreach ($languages as $code => $language)
                                                                <option @if ($lang == $code) selected @endif
                                                                    value="{{ $code }}">
                                                                    {{ ucFirst($language) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <div class="form-group">
                                                        {{ Form::label('currency_symbol', __('Currency Symbol*'), ['class' => 'form-label']) }}
                                                        {{ Form::text('currency_symbol', $settings['currency_symbol'], ['class' => 'form-control']) }}
                                                        <small>{{ __('Note: This value will be automatically assigned whenever a new store is created.') }}</small>
                                                        @error('currency_symbol')
                                                            <span class="invalid-currency_symbol" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4 mb-0">
                                                    <div class="form-group">
                                                        {{ Form::label('currency', __('Currency *'), ['class' => 'form-label']) }}
                                                        {{ Form::text('currency', $settings['currency'], ['class' => 'form-control font-style']) }}
                                                        <small>{{ __('Note: This value will be automatically assigned whenever a new store is created.') }}</small>
                                                        <small>
                                                            <a href="https://stripe.com/docs/currencies"
                                                                target="_blank">{{ __('you can find out how to do that here..') }}</a>
                                                        </small>
                                                        <br>


                                                        @error('currency')
                                                            <span class="invalid-currency" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                                        @enderror

                                                    </div>
                                                </div>

                                                <div class="form-group col-6 col-md-3">
                                                    <div class="custom-control form-switch p-0">
                                                        <label class="form-check-label"
                                                            for="display_landing_page">{{ __('Enable Landing Page') }}</label><br>
                                                        <input type="checkbox" name="display_landing_page"
                                                            class="form-check-input" id="display_landing_page"
                                                            data-toggle="switchbutton"
                                                            {{ $settings['display_landing_page'] == 'on' ? 'checked="checked"' : '' }}
                                                            data-onstyle="primary">
                                                    </div>
                                                </div>

                                                <div class="form-group col-6 col-md-3">
                                                    <div class="custom-control form-switch p-0">
                                                        <label class="form-check-label"
                                                            for="SITE_RTL">{{ __('Enable RTL') }}</label><br>
                                                        <input type="checkbox" class="form-check-input"
                                                            data-toggle="switchbutton" data-onstyle="primary"
                                                            name="SITE_RTL" id="SITE_RTL"
                                                            {{ $settings['SITE_RTL'] == 'on' ? 'checked="checked"' : '' }}>
                                                    </div>
                                                </div>
                                                <div class="form-group col-6 col-md-3">
                                                    <div class="custom-control form-switch p-0">
                                                        <label class="form-check-label"
                                                            for="signup_button">{{ __('Enable Sign-Up Page') }}</label><br>
                                                        <input type="checkbox" name="signup_button"
                                                            class="form-check-input" id="signup_button"
                                                            data-toggle="switchbutton"
                                                            {{ Utility::getValByName('signup_button') == 'on' ? 'checked="checked"' : '' }}
                                                            data-onstyle="primary">
                                                    </div>
                                                </div>
                                                <div class="form-group col-6 col-md-3">
                                                    <div class="custom-control form-switch p-0">
                                                        <label class="form-check-label"
                                                            for="email_verification">{{ __('Enable Email Verification') }}</label><br>
                                                        <input type="checkbox" name="email_verification"
                                                            class="form-check-input" id="email_verification"
                                                            data-toggle="switchbutton"
                                                            {{ Utility::getValByName('email_verification') == 'on' ? 'checked="checked"' : '' }}
                                                            data-onstyle="primary">
                                                    </div>
                                                </div>
                                                <div class="setting-card setting-logo-box p-3">
                                                    <div class="row">
                                                        <h5>{{ __('Theme Customizer') }}</h5>
                                                        <div class="col-md-4 my-auto">
                                                            <h6 class="mt-2">
                                                                <i data-feather="credit-card"
                                                                    class="me-2"></i>{{ __('Primary Color Settings') }}
                                                            </h6>
                                                            <hr class="my-2" />
                                                            <div class="color-wrp">
                                                                <div class="theme-color themes-color">
                                                                    <a href="#!" class="themes-color-change {{ $color == 'theme-1' ? 'active_color' : '' }}" data-value="theme-1"></a>
                                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-1"{{ $color == 'theme-1' ? 'checked' : '' }}>
                                                                    <a href="#!" class="themes-color-change {{ $color == 'theme-2' ? 'active_color' : '' }}" data-value="theme-2"></a>
                                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-2"{{ $color == 'theme-2' ? 'checked' : '' }}>
                                                                    <a href="#!" class="themes-color-change {{ $color == 'theme-3' ? 'active_color' : '' }}" data-value="theme-3"></a>
                                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-3"{{ $color == 'theme-3' ? 'checked' : '' }}>
                                                                    <a href="#!" class="themes-color-change {{ $color == 'theme-4' ? 'active_color' : '' }}" data-value="theme-4"></a>
                                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-4"{{ $color == 'theme-4' ? 'checked' : '' }}>
                                                                    <a href="#!" class="themes-color-change {{ $color == 'theme-5' ? 'active_color' : '' }}" data-value="theme-5"></a>
                                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-5"{{ $color == 'theme-5' ? 'checked' : '' }}>
                                                                    <br>
                                                                    <a href="#!" class="themes-color-change {{ $color == 'theme-6' ? 'active_color' : '' }}" data-value="theme-6"></a>
                                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-6"{{ $color == 'theme-6' ? 'checked' : '' }}>
                                                                    <a href="#!" class="themes-color-change {{ $color == 'theme-7' ? 'active_color' : '' }}" data-value="theme-7"></a>
                                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-7"{{ $color == 'theme-7' ? 'checked' : '' }}>
                                                                    <a href="#!" class="themes-color-change {{ $color == 'theme-8' ? 'active_color' : '' }}" data-value="theme-8"></a>
                                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-8"{{ $color == 'theme-8' ? 'checked' : '' }}>
                                                                    <a href="#!" class="themes-color-change {{ $color == 'theme-9' ? 'active_color' : '' }}" data-value="theme-9"></a>
                                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-9"{{ $color == 'theme-9' ? 'checked' : '' }}>
                                                                    <a href="#!" class="themes-color-change {{ $color == 'theme-10' ? 'active_color' : '' }}" data-value="theme-10"></a>
                                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-10"{{ $color == 'theme-10' ? 'checked' : '' }}>
                                                                </div>
                                                                <div class="color-picker-wrp ">
                                                                        <input type="color" value="{{ $color ? $color : '' }}" class="colorPicker {{ isset($flag) && $flag == 'true' ? 'active_color' : '' }}" name="custom_color" id="color-picker">                                             
                                                                        <input type='hidden' name="color_flag" value = {{  isset($flag) && $flag == 'true' ? 'true' : 'false' }}>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 my-auto mt-2">
                                                            <h6 class="">
                                                                <i data-feather="layout"
                                                                    class="me-2"></i>{{ __('Sidebar Settings') }}
                                                            </h6>
                                                            <hr class="my-2" />
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="cust-theme-bg" name="cust_theme_bg"
                                                                    {{ Utility::getValByName('cust_theme_bg') == 'on' ? 'checked' : '' }} />
                                                                <label class="form-check-label f-w-600 pl-1"
                                                                    for="cust-theme-bg">{{ __('Transparent layout') }}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 my-auto mt-2">
                                                            <h6 class="">
                                                                <i data-feather="sun"
                                                                    class="me-2"></i>{{ __('Layout Settings') }}
                                                            </h6>
                                                            <hr class="my-2" />
                                                            <div class="form-check form-switch mt-2">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="cust-darklayout" name="cust_darklayout"
                                                                    {{ Utility::getValByName('cust_darklayout') == 'on' ? 'checked' : '' }} />
                                                                <label class="form-check-label f-w-600 pl-1"
                                                                    for="cust-darklayout">{{ __('Dark Layout') }}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card-footer p-0">
                                                    <div class="col-sm-12 mt-3 px-2">
                                                        <div class="text-end">
                                                            {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                    </div>
                    <div class="tab-pane fade" id="pills-payment-setting" role="tabpanel" aria-labelledby="pills-brand_setting-tab">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ 'Payment Setting' }}</h5>
                                <small>{{__('These details will be used to collect subscription plan payments. Each subscription plan will have a payment button based on the below configuration.')}}</small>
                            </div>
                            <div class="card-body">
                                <form id="setting-form" method="post" action="{{ route('payment.setting') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12">
                                            {{-- <div class="card"> --}}
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                                        <label class="col-form-label">{{ __('Currency') }}</label>
                                                        <input type="text" name="currency" class="form-control"
                                                            id="currency" value="{{ isset($admin_payment_setting['currency']) ? $admin_payment_setting['currency'] : '' }}" required>
                                                        <small class="text-xs">
                                                            {{ __('Note: Add currency code as per three-letter ISO code') }}.
                                                            <a href="https://stripe.com/docs/currencies"
                                                                target="_blank">{{ __('you can find out how to do that here..') }}</a>
                                                                {{__('and This value will be automatically assigned whenever a new store is created.')}}
                                                        </small>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                                        <label for="currency_symbol"
                                                            class="col-form-label">{{ __('Currency Symbol') }}</label>
                                                        <input type="text" name="currency_symbol"
                                                            class="form-control" id="currency_symbol"
                                                            value="{{ isset($admin_payment_setting['currency_symbol']) ? $admin_payment_setting['currency_symbol'] : '' }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- </div> --}}
                                        </div>
                                    </div>
                                   
                                    <div class="row">
                                        <div class="faq col-12">
                                            <div class="accordion accordion-flush setting-accordion"
                                                id="accordionExample">
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingOne">
                                                        <button class="accordion-button collapsed"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapseOne"
                                                            aria-expanded="false"
                                                            aria-controls="collapseOne">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Stripe') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('On/Off') }}:</span>
                                                                <div
                                                                    class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_stripe_enabled" value="off">
                                                                    <input type="checkbox" class="form-check-input input-primary" name="is_stripe_enabled" id="is_stripe_enabled" {{ isset($admin_payment_setting['is_stripe_enabled']) && $admin_payment_setting['is_stripe_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="is_stripe_enabled"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseOne"
                                                        class="accordion-collapse collapse"
                                                        aria-labelledby="headingOne"
                                                        data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">
                                                                
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        {{ Form::label('stripe_key', __('Stripe Key'), ['class' => 'col-form-label']) }}
                                                                        {{ Form::text('stripe_key', isset($admin_payment_setting['stripe_key']) ? $admin_payment_setting['stripe_key'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Stripe Key')]) }}
                                                                        @error('stripe_key')
                                                                            <span class="invalid-stripe_key" role="alert">
                                                                                <strong
                                                                                    class="text-danger">{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        {{ Form::label('stripe_secret', __('Stripe Secret'), ['class' => 'col-form-label']) }}
                                                                        {{ Form::text('stripe_secret', isset($admin_payment_setting['stripe_secret']) ? $admin_payment_setting['stripe_secret'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Stripe Secret')]) }}
                                                                        @error('stripe_secret')
                                                                            <span class="invalid-stripe_secret"
                                                                                role="alert">
                                                                                <strong
                                                                                    class="text-danger">{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Manually -->
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="heading-2-15">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapse15"
                                                                aria-expanded="true" aria-controls="collapse2">
                                                                <span class="d-flex align-items-center">
                                                                    {{ __('Manually') }}
                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2">{{__('On/Off :')}}</span>
                                                                    <div class="form-check form-switch d-inline-block custom-switch-v1">
                                                                        <input type="hidden" name="manually_enabled"
                                                                            value="off">
                                                                        <input type="checkbox" class="form-check-input"
                                                                            name="manually_enabled" id="manually_enabled"
                                                                            {{ isset($admin_payment_setting['manually_enabled']) && $admin_payment_setting['manually_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                        <label class="custom-control-label form-label"
                                                                            for="manually_enabled"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse15"
                                                            class="accordion-collapse collapse"aria-labelledby="heading-2-15"data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <small class="m-0">{{ __('Requesting manual payment for the planned amount for the subscriptions plan.') }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <!-- Bank transfer -->
                                                <div class="accordion-item">
                                                    <h2 class=" accordion-header" id="heading-2-16">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapse16"
                                                            aria-expanded="false" aria-controls="collapse16">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Bank Transfer') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('On/Off :')}}</span>
                                                                <div class="form-check form-switch custom-switch-v1 d-inline-block">
                                                                    <input type="hidden" name="enable_bank"
                                                                        value="off">
                                                                    <input type="checkbox" class="form-check-input input-primary"
                                                                        name="enable_bank" id="enable_bank" {{ isset($admin_payment_setting['enable_bank']) && $admin_payment_setting['enable_bank'] == 'on' ? 'checked="checked"' : '' }}>
                                                                        <label class="form-check-label"
                                                                        for="enable_bank"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse16" class="accordion-collapse collapse"
                                                        aria-labelledby="heading-2-16" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label">{{ __('Bank Details') }}</label>

                                                                        <textarea type="text" name="bank_number" id="bank_number" class="form-control" rows="6" placeholder="{{ __('Bank Transfer Number') }}">{{ isset($admin_payment_setting['bank_number']) ? $admin_payment_setting['bank_number'] : '' }}</textarea>
                                                                        <small>{{ __('Example : Bank : bank name </br> Account Number : 0000 0000 </br>') }}</small>
                                                                        @if ($errors->has('bank_number'))
                                                                            <span class="invalid-feedback d-block">
                                                                                {{ $errors->first('bank_number') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingTwo">
                                                        <button class="accordion-button collapsed"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapseTwo"
                                                            aria-expanded="false"
                                                            aria-controls="collapseTwo">
                                                            <span class="d-flex align-items-center"> {{ __('Paypal') }}</span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('On/Off') }}:</span>
                                                                <div
                                                                    class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_paypal_enabled" value="off">
                                                                    <input type="checkbox"  name="is_paypal_enabled" id="is_paypal_enabled" class="form-check-input input-primary" {{ isset($admin_payment_setting['is_paypal_enabled']) && $admin_payment_setting['is_paypal_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="is_paypal_enabled"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseTwo"
                                                        class="accordion-collapse collapse"
                                                        aria-labelledby="headingTwo"
                                                        data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="bussiness-hours">
                                                                <div class="row align-items-center gy-4">
                                                                    <div class="col-lg-12">
                                                                        <label class="paypal-label col-form-label" for="paypal_mode">{{ __('Paypal Mode') }}</label>
                                                                        <br>
                                                                        <div class="d-flex flex-wrap">
                                                                            <div class="mr-2" style="margin-right: 15px;">
                                                                                <div class="border card p-3">
                                                                                    <div class="form-check">
                                                                                        <label
                                                                                            class="form-check-labe text-dark">
                                                                                            <input type="radio"
                                                                                                name="paypal_mode"
                                                                                                value="sandbox"
                                                                                                class="form-check-input"
                                                                                                {{ !isset($admin_payment_setting['paypal_mode']) || $admin_payment_setting['paypal_mode'] == '' || $admin_payment_setting['paypal_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                            {{ __('Sandbox') }}
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="mr-2 me-2">
                                                                                <div class="border card p-3">
                                                                                    <div class="form-check">
                                                                                        <label
                                                                                            class="form-check-labe text-dark">
                                                                                            <input type="radio"
                                                                                                name="paypal_mode"
                                                                                                value="live"
                                                                                                class="form-check-input"
                                                                                                {{ isset($admin_payment_setting['paypal_mode']) && $admin_payment_setting['paypal_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                            {{ __('Live') }}
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label for="paypal_client_id" class="col-form-label">{{ __('Client ID') }}</label>
                                                                            <input type="text" name="paypal_client_id"
                                                                                id="paypal_client_id" class="form-control"
                                                                                value="{{ !isset($admin_payment_setting['paypal_client_id']) || is_null($admin_payment_setting['paypal_client_id']) ? '' : $admin_payment_setting['paypal_client_id'] }}"
                                                                                placeholder="{{ __('Client ID') }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label for="paypal_secret_key"
                                                                                class="col-form-label">{{ __('Secret Key') }}</label>
                                                                            <input type="text" name="paypal_secret_key"
                                                                                id="paypal_secret_key" class="form-control"
                                                                                value="{{ !isset($admin_payment_setting['paypal_secret_key']) || is_null($admin_payment_setting['paypal_secret_key']) ? '' : $admin_payment_setting['paypal_secret_key'] }}"
                                                                                placeholder="{{ __('Secret Key') }}">
                                                                        </div>  
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingThree">
                                                        <button class="accordion-button collapsed"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapseThree"
                                                            aria-expanded="false"
                                                            aria-controls="collapseTwo">
                                                            <span
                                                                class="d-flex align-items-center">{{ __('Paystack') }}</span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('On/Off') }}:</span>
                                                                <div
                                                                    class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_paystack_enabled" value="off">
                                                                    <input type="checkbox" name="is_paystack_enabled" class="form-check-input input-primary" id="is_paystack_enabled" {{ isset($admin_payment_setting['is_paystack_enabled']) && $admin_payment_setting['is_paystack_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label" for="is_paystack_enabled"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseThree"
                                                        class="accordion-collapse collapse"
                                                        aria-labelledby="headingTwo"
                                                        data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="bussiness-hours">
                                                                <div class="row align-items-center gy-4">
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label for="paypal_client_id" class="col-form-label">{{ __('Public Key') }}</label>
                                                                            <input type="text" name="paystack_public_key" id="paystack_public_key" class="form-control"
                                                                                value="{{ !isset($admin_payment_setting['paystack_public_key']) || is_null($admin_payment_setting['paystack_public_key']) ? '' : $admin_payment_setting['paystack_public_key'] }}"
                                                                                placeholder="{{ __('Public Key') }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group">
                                                                            <label for="paystack_secret_key" class="col-form-label">{{ __('Secret Key') }}</label>
                                                                            <input type="text" name="paystack_secret_key"
                                                                                id="paystack_secret_key"
                                                                                class="form-control"
                                                                                value="{{ !isset($admin_payment_setting['paystack_secret_key']) || is_null($admin_payment_setting['paystack_secret_key']) ? '' : $admin_payment_setting['paystack_secret_key'] }}"
                                                                                placeholder="{{ __('Secret Key') }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingFour">
                                                        <button class="accordion-button collapsed"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapseFour"
                                                            aria-expanded="false"
                                                            aria-controls="collapseTwo">
                                                            <span
                                                                class="d-flex align-items-center">{{ __('Flutterwave') }}</span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('On/Off') }}:</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_flutterwave_enabled" value="off">
                                                                    <input type="checkbox" name="is_flutterwave_enabled"
                                                                        class="form-check-input input-primary"
                                                                        id="is_flutterwave_enabled"  {{ isset($admin_payment_setting['is_flutterwave_enabled']) && $admin_payment_setting['is_flutterwave_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="is_flutterwave_enabled"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseFour"
                                                        class="accordion-collapse collapse"
                                                        aria-labelledby="headingFour"
                                                        data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">
                                                                <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="paypal_client_id" class="col-form-label">{{ __('Public Key') }}</label>
                                                                            <input type="text" name="flutterwave_public_key" id="flutterwave_public_key"
                                                                                class="form-control"
                                                                                value="{{ !isset($admin_payment_setting['flutterwave_public_key']) || is_null($admin_payment_setting['flutterwave_public_key']) ? '' : $admin_payment_setting['flutterwave_public_key'] }}"
                                                                                placeholder="Public Key">
                                                                        </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="paystack_secret_key" class="col-form-label">{{ __('Secret Key') }}</label>
                                                                        <input type="text" name="flutterwave_secret_key"
                                                                            id="flutterwave_secret_key" class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['flutterwave_secret_key']) || is_null($admin_payment_setting['flutterwave_secret_key']) ? '' : $admin_payment_setting['flutterwave_secret_key'] }}"
                                                                            placeholder="Secret Key">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingFive">
                                                        <button class="accordion-button collapsed"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapseFive"
                                                            aria-expanded="false"
                                                            aria-controls="collapseFive">
                                                            <span
                                                                class="d-flex align-items-center">  {{ __('Razorpay') }}</span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('On/Off') }}:</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_razorpay_enabled" value="off"> 
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary" 
                                                                        name="is_razorpay_enabled"
                                                                        id="is_razorpay_enabled"  {{ isset($admin_payment_setting['is_razorpay_enabled']) && $admin_payment_setting['is_razorpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="is_razorpay_enabled"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseFive"
                                                        class="accordion-collapse collapse"
                                                        aria-labelledby="headingFive"
                                                        data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_client_id" class="col-form-label">{{ __('Public Key') }}</label>
                                                                        <input type="text" name="razorpay_public_key" id="razorpay_public_key" class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['razorpay_public_key']) || is_null($admin_payment_setting['razorpay_public_key']) ? '' : $admin_payment_setting['razorpay_public_key'] }}"
                                                                            placeholder="Public Key">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="paystack_secret_key" class="col-form-label"> {{ __('Secret Key') }}</label>
                                                                        <input type="text" name="razorpay_secret_key" id="razorpay_secret_key" class="form-control" value="{{ !isset($admin_payment_setting['razorpay_secret_key']) || is_null($admin_payment_setting['razorpay_secret_key']) ? '' : $admin_payment_setting['razorpay_secret_key'] }}" placeholder="Secret Key">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingSix">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                                            <span
                                                                class="d-flex align-items-center"> {{ __('Paytm') }}</span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('On/Off') }}:</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_paytm_enabled" value="off">
                                                                    <input type="checkbox" class="form-check-input input-primary"  name="is_paytm_enabled" id="is_paytm_enabled" {{ isset($admin_payment_setting['is_paytm_enabled']) && $admin_payment_setting['is_paytm_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label" for="is_paytm_enabled"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">
                                                                <div class="col-lg-12 pb-4">
                                                                    <label class="paypal-label col-form-label" for="paypal_mode">{{ __('Paytm Environment') }}</label>
                                                                    <br>
                                                                    <div class="d-flex flex-wrap">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="paytm_mode"
                                                                                            value="local"
                                                                                            class="form-check-input"
                                                                                            {{ !isset($admin_payment_setting['paytm_mode']) || $admin_payment_setting['paytm_mode'] == '' || $admin_payment_setting['paytm_mode'] == 'local' ? 'checked="checked"' : '' }}>
                                                                                        {{ __('Local') }}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="paytm_mode"
                                                                                            value="production"
                                                                                            class="form-check-input"
                                                                                            {{ isset($admin_payment_setting['paytm_mode']) && $admin_payment_setting['paytm_mode'] == 'production' ? 'checked="checked"' : '' }}>
                                                                                        {{ __('Production') }}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <div class="form-group">
                                                                        <label for="paytm_public_key" class="col-form-label">{{ __('Merchant ID') }}</label>
                                                                        <input type="text" name="paytm_merchant_id"
                                                                            id="paytm_merchant_id" class="form-control"
                                                                            value="{{ isset($admin_payment_setting['paytm_merchant_id']) ? $admin_payment_setting['paytm_merchant_id'] : '' }}"
                                                                            placeholder="{{ __('Merchant ID') }}" />
                                                                        @if ($errors->has('paytm_merchant_id'))
                                                                            <span class="invalid-feedback d-block">
                                                                                {{ $errors->first('paytm_merchant_id') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <div class="form-group">
                                                                        <label for="paytm_secret_key" class="col-form-label">{{ __('Merchant Key') }}</label>
                                                                        <input type="text" name="paytm_merchant_key"
                                                                            id="paytm_merchant_key" class="form-control"
                                                                            value="{{ isset($admin_payment_setting['paytm_merchant_key']) ? $admin_payment_setting['paytm_merchant_key'] : '' }}"
                                                                            placeholder="{{ __('Merchant Key') }}" />
                                                                        @if ($errors->has('paytm_merchant_key'))
                                                                            <span class="invalid-feedback d-block">
                                                                                {{ $errors->first('paytm_merchant_key') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <div class="form-group">
                                                                        <label for="paytm_industry_type" class="col-form-label">{{ __('Industry Type') }}</label>
                                                                        <input type="text" name="paytm_industry_type"
                                                                            id="paytm_industry_type"
                                                                            class="form-control"
                                                                            value="{{ isset($admin_payment_setting['paytm_industry_type']) ? $admin_payment_setting['paytm_industry_type'] : '' }}"
                                                                            placeholder="{{ __('Industry Type') }}" />
                                                                        @if ($errors->has('paytm_industry_type'))
                                                                            <span class="invalid-feedback d-block">
                                                                                {{ $errors->first('paytm_industry_type') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingseven">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseseven" aria-expanded="false" aria-controls="collapseseven">
                                                            <span
                                                                class="d-flex align-items-center">{{ __('Mercado Pago') }}</span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">On/Off:</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_mercado_enabled" value="off">
                                                                    <input type="checkbox"  name="is_mercado_enabled"
                                                                        class="form-check-input input-primary"
                                                                        id="is_mercado_enabled" {{ isset($admin_payment_setting['is_mercado_enabled']) && $admin_payment_setting['is_mercado_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="is_mercado_enabled"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseseven" class="accordion-collapse collapse" aria-labelledby="headingseven" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-lg-12 pb-4">
                                                                    <label class="col-form-label"
                                                                        for="mercado_mode">{{ __('Mercado Mode') }}</label>
                                                                    <br>
                                                                    <div class="d-flex flex-wrap">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="mercado_mode"
                                                                                            value="sandbox"
                                                                                            class="form-check-input"
                                                                                            {{ (isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == '') || (isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                                        {{ __('Sandbox') }}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2 me-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="mercado_mode"
                                                                                            value="live"
                                                                                            class="form-check-input"
                                                                                            {{ isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                        {{ __('Live') }}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="mercado_access_token"
                                                                            class="col-form-label">{{ __('Access Token') }}</label>
                                                                        <input type="text"
                                                                            name="mercado_access_token"
                                                                            id="mercado_access_token"
                                                                            class="form-control"
                                                                            value="{{ isset($admin_payment_setting['mercado_access_token']) ? $admin_payment_setting['mercado_access_token'] : '' }}"
                                                                            placeholder="{{ __('Access Token') }}" />
                                                                        @if ($errors->has('mercado_secret_key'))
                                                                            <span class="invalid-feedback d-block">
                                                                                {{ $errors->first('mercado_access_token') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingeight">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseeight" aria-expanded="false" aria-controls="collapseeight">
                                                            <span class="d-flex align-items-center">{{ __('Mollie') }}</span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('On/Off') }}:</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_mollie_enabled" value="off">
                                                                    <input type="checkbox" name="is_mollie_enabled" 
                                                                        class="form-check-input input-primary"
                                                                        id="is_mollie_enabled"  {{ isset($admin_payment_setting['is_mollie_enabled']) && $admin_payment_setting['is_mollie_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="is_mollie_enabled"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseeight" class="accordion-collapse collapse" aria-labelledby="headingeight" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="mollie_api_key"
                                                                            class="col-form-label">{{ __('Mollie Api Key') }}</label>
                                                                        <input type="text" name="mollie_api_key"
                                                                            id="mollie_api_key" class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['mollie_api_key']) || is_null($admin_payment_setting['mollie_api_key']) ? '' : $admin_payment_setting['mollie_api_key'] }}"
                                                                            placeholder="Mollie Api Key">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="mollie_profile_id"
                                                                            class="col-form-label">{{ __('Mollie Profile Id') }}</label>
                                                                        <input type="text" name="mollie_profile_id"
                                                                            id="mollie_profile_id" class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['mollie_profile_id']) || is_null($admin_payment_setting['mollie_profile_id']) ? '' : $admin_payment_setting['mollie_profile_id'] }}"
                                                                            placeholder="Mollie Profile Id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="mollie_partner_id"
                                                                            class="col-form-label">{{ __('Mollie Partner Id') }}</label>
                                                                        <input type="text" name="mollie_partner_id"
                                                                            id="mollie_partner_id" class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['mollie_partner_id']) || is_null($admin_payment_setting['mollie_partner_id']) ? '' : $admin_payment_setting['mollie_partner_id'] }}"
                                                                            placeholder="Mollie Partner Id">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingnine">
                                                        <button class="accordion-button collapsed"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapseNine"
                                                            aria-expanded="false"
                                                            aria-controls="collapseNine">
                                                            <span
                                                                class="d-flex align-items-center">{{ __('Skrill') }}</span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('On/Off') }}:</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_skrill_enabled" value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_skrill_enabled"
                                                                        id="is_skrill_enabled" {{ isset($admin_payment_setting['is_skrill_enabled']) && $admin_payment_setting['is_skrill_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="is_skrill_enabled"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseNine"
                                                        class="accordion-collapse collapse"
                                                        aria-labelledby="headingnine"
                                                        data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="mollie_api_key"
                                                                            class="col-form-label">{{ __('Skrill Email') }}</label>
                                                                        <input type="email" name="skrill_email"
                                                                            id="skrill_email" class="form-control"
                                                                            value="{{ isset($admin_payment_setting['skrill_email']) ? $admin_payment_setting['skrill_email'] : '' }}"
                                                                            placeholder="{{ __('Skrill Email') }}" />
                                                                        @if ($errors->has('skrill_email'))
                                                                            <span class="invalid-feedback d-block">
                                                                                {{ $errors->first('skrill_email') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingTen">
                                                        <button class="accordion-button collapsed"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapseTen"
                                                            aria-expanded="false"
                                                            aria-controls="collapseTen">
                                                            <span
                                                                class="d-flex align-items-center">{{ __('CoinGate') }}</span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('On/Off') }}:</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_coingate_enabled" value="off">
                                                                    <input type="checkbox" name="is_coingate_enabled"
                                                                        class="form-check-input input-primary"
                                                                        id="is_coingate_enabled"  {{ isset($admin_payment_setting['is_coingate_enabled']) && $admin_payment_setting['is_coingate_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="is_coingate_enabled"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseTen"
                                                        class="accordion-collapse collapse"
                                                        aria-labelledby="headingTen"
                                                        data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">
                                                                <div class="col-lg-12 pb-4">
                                                                    <label class="col-form-label" for="coingate_mode">{{ __('CoinGate Mode') }}</label>
                                                                    <br>
                                                                    <div class="d-flex flex-wrap">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="coingate_mode"
                                                                                            value="sandbox"
                                                                                            class="form-check-input"
                                                                                            {{ !isset($admin_payment_setting['coingate_mode']) || $admin_payment_setting['coingate_mode'] == '' || $admin_payment_setting['coingate_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                        {{ __('Sandbox') }}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2 me-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="coingate_mode"
                                                                                            value="live"
                                                                                            class="form-check-input"
                                                                                            {{ isset($admin_payment_setting['coingate_mode']) && $admin_payment_setting['coingate_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                        {{ __('Live') }}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="coingate_auth_token"
                                                                            class="col-form-label">{{ __('CoinGate Auth Token') }}</label>
                                                                        <input type="text" name="coingate_auth_token"
                                                                            id="coingate_auth_token"
                                                                            class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['coingate_auth_token']) || is_null($admin_payment_setting['coingate_auth_token']) ? '' : $admin_payment_setting['coingate_auth_token'] }}"
                                                                            placeholder="CoinGate Auth Token">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingEleven">
                                                        <button class="accordion-button collapsed"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapseEleven"
                                                            aria-expanded="false"
                                                            aria-controls="collapseEleven">
                                                            <span
                                                                class="d-flex align-items-center">{{ __('PaymentWall') }}</span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('On/Off') }}:</span>
                                                                <div
                                                                    class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_paymentwall_enabled" value="off">
                                                                    <input type="checkbox"  name="is_paymentwall_enabled"
                                                                        class="form-check-input input-primary"
                                                                        id="is_paymentwall_enabled" {{ isset($admin_payment_setting['is_paymentwall_enabled']) && $admin_payment_setting['is_paymentwall_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="is_paymentwall_enabled"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseEleven"
                                                        class="accordion-collapse collapse"
                                                        aria-labelledby="headingEleven"
                                                        data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="paymentwall_public_key"
                                                                            class="col-form-label">{{ __('Public Key') }}</label>
                                                                        <input type="text"
                                                                            name="paymentwall_public_key"
                                                                            id="paymentwall_public_key"
                                                                            class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['paymentwall_public_key']) || is_null($admin_payment_setting['paymentwall_public_key']) ? '' : $admin_payment_setting['paymentwall_public_key'] }}"
                                                                            placeholder="{{ __('Public Key') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="paymentwall_private_key"
                                                                            class="col-form-label">{{ __('Private Key') }}</label>
                                                                        <input type="text"
                                                                            name="paymentwall_private_key"
                                                                            id="paymentwall_private_key"
                                                                            class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['paymentwall_private_key']) || is_null($admin_payment_setting['paymentwall_private_key']) ? '' : $admin_payment_setting['paymentwall_private_key'] }}"
                                                                            placeholder="{{ __('Private Key') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingTwelve">
                                                        <button class="accordion-button collapsed"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapseTwelve"
                                                            aria-expanded="false"
                                                            aria-controls="collapseTwelve">
                                                            <span class="d-flex align-items-center">{{ __('Toyyibpay') }}</span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('On/Off') }}:</span>
                                                                <div
                                                                    class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_toyyibpay_enabled" value="off">
                                                                    <input type="checkbox"  name="is_toyyibpay_enabled"
                                                                        class="form-check-input input-primary"
                                                                        id="is_toyyibpay_enabled" {{ isset($admin_payment_setting['is_toyyibpay_enabled']) && $admin_payment_setting['is_toyyibpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="is_toyyibpay_enabled"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseTwelve"
                                                        class="accordion-collapse collapse"
                                                        aria-labelledby="headingTwelve"
                                                        data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="toyyibpay_category_code"
                                                                            class="col-form-label">{{ __('Category Code') }}</label>
                                                                        <input type="text"
                                                                            name="toyyibpay_category_code"
                                                                            id="toyyibpay_category_code"
                                                                            class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['toyyibpay_category_code']) || is_null($admin_payment_setting['toyyibpay_category_code']) ? '' : $admin_payment_setting['toyyibpay_category_code'] }}"
                                                                            placeholder="{{ __('category code') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="toyyibpay_secret_key"
                                                                            class="col-form-label">{{ __('Secret Key') }}</label>
                                                                        <input type="text"
                                                                            name="toyyibpay_secret_key"
                                                                            id="toyyibpay_secret_key"
                                                                            class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['toyyibpay_secret_key']) || is_null($admin_payment_setting['toyyibpay_secret_key']) ? '' : $admin_payment_setting['toyyibpay_secret_key'] }}"
                                                                            placeholder="{{ __('toyyibpay secret key') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingThirteen">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseThirteen"
                                                            aria-expanded="true" aria-controls="collapseThirteen">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Payfast') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('On/Off') }}:</span>
                                                                <div
                                                                    class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_payfast_enabled" value="off">
                                                                    <input type="checkbox"  name="is_payfast_enabled"
                                                                        class="form-check-input input-primary"
                                                                        id="is_payfast_enabled" {{ isset($admin_payment_setting['is_payfast_enabled']) && $admin_payment_setting['is_payfast_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="is_payfast_enabled"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseThirteen" class="accordion-collapse collapse"aria-labelledby="headingThirteen"data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <label class="payfast-label col-form-label"
                                                                        for="payfast_mode">{{ __('Payfast Mode') }}</label>
                                                                    <br>
                                                                    <div class="d-flex">
                                                                        <div class="me-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark {{ isset($admin_payment_setting['payfast_mode']) && $admin_payment_setting['payfast_mode'] == 'sandbox' ? 'active' : '' }}">
                                                                                        <input type="radio"
                                                                                            name="payfast_mode" value="sandbox"
                                                                                            class="form-check-input"
                                                                                            {{ (isset($admin_payment_setting['payfast_mode']) && $admin_payment_setting['payfast_mode'] == '') || (isset($admin_payment_setting['payfast_mode']) &&
                                                                                            $admin_payment_setting['payfast_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>{{ __('Sandbox') }}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="me-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark {{ isset($admin_payment_setting['payfast_mode']) && $admin_payment_setting['payfast_mode'] == 'live' ? 'active' : '' }}">
                                                                                        <input type="radio"
                                                                                            name="payfast_mode" value="live"
                                                                                            class="form-check-input"
                                                                                            {{ isset($admin_payment_setting['payfast_mode']) && $admin_payment_setting['payfast_mode'] == 'live' ? 'checked="checked"' : '' }}>{{ __('Live') }}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="payfast_merchant_id"
                                                                            class="col-form-label">{{ __('Merchant ID') }}</label>
                                                                        <input type="text" name="payfast_merchant_id"
                                                                            id="payfast_merchant_id" class="form-control"
                                                                            value="{{ isset($admin_payment_setting['payfast_merchant_id']) ? $admin_payment_setting['payfast_merchant_id'] : '' }}"
                                                                            placeholder="{{ __('Merchant ID') }}">
                                                                    </div>
                                                                    @if ($errors->has('payfast_merchant_id'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('payfast_merchant_id') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="payfast_merchant_key"
                                                                            class="col-form-label">{{ __('Merchant Key') }}</label>
                                                                        <input type="text" name="payfast_merchant_key"
                                                                            id="payfast_merchant_key" class="form-control"
                                                                            value="{{ isset($admin_payment_setting['payfast_merchant_key']) ? $admin_payment_setting['payfast_merchant_key'] : '' }}"
                                                                            placeholder="{{ __('Merchant Key') }}">
                                                                    </div>
                                                                    @if ($errors->has('payfast_merchant_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('payfast_merchant_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="payfast_signature"
                                                                            class="col-form-label">{{ __('Salt Passphrase') }}</label>
                                                                        <input type="text" name="payfast_signature"
                                                                            id="payfast_signature" class="form-control"
                                                                            value="{{ isset($admin_payment_setting['payfast_signature']) ? $admin_payment_setting['payfast_signature'] : '' }}"
                                                                            placeholder="{{ __('Salt Passphrase') }}">
                                                                    </div>
                                                                    @if ($errors->has('payfast_signature'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('payfast_signature') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingEighteen">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseEighteen"
                                                            aria-expanded="true" aria-controls="collapseEighteen">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Iyzipay') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('On/Off :')}}</span>
                                                                <div class="form-check form-switch d-inline-block custom-switch-v1">
                                                                    <input type="hidden" name="is_iyzipay_enabled"
                                                                        value="off">
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="is_iyzipay_enabled"
                                                                        id="is_iyzipay_enabled"
                                                                        {{ isset($admin_payment_setting['is_iyzipay_enabled']) && $admin_payment_setting['is_iyzipay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="custom-control-label form-label"
                                                                        for="is_iyzipay_enabled"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseEighteen" class="accordion-collapse collapse"aria-labelledby="headingEighteen"data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <label class="payfast-label col-form-label"
                                                                        for="iyzipay_mode">{{ __('Iyzipay Mode') }}</label>
                                                                    <br>
                                                                    <div class="d-flex">
                                                                        <div class="me-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark {{ isset($admin_payment_setting['iyzipay_mode']) && $admin_payment_setting['iyzipay_mode'] == 'sandbox' ? 'active' : '' }}">
                                                                                        <input type="radio"
                                                                                            name="iyzipay_mode" value="sandbox"
                                                                                            class="form-check-input"
                                                                                            {{ (isset($admin_payment_setting['iyzipay_mode']) && $admin_payment_setting['iyzipay_mode'] == '') || (isset($admin_payment_setting['iyzipay_mode']) &&
                                                                                            $admin_payment_setting['iyzipay_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>{{ __('Sandbox') }}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="me-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark {{ isset($admin_payment_setting['iyzipay_mode']) && $admin_payment_setting['iyzipay_mode'] == 'live' ? 'active' : '' }}">
                                                                                        <input type="radio"
                                                                                            name="iyzipay_mode" value="live"
                                                                                            class="form-check-input"
                                                                                            {{ isset($admin_payment_setting['iyzipay_mode']) && $admin_payment_setting['iyzipay_mode'] == 'live' ? 'checked="checked"' : '' }}>{{ __('Live') }}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="iyzipay_key"
                                                                            class="col-form-label">{{ __('Iyzipay Key') }}</label>
                                                                        <input type="text" name="iyzipay_key"
                                                                            id="iyzipay_key" class="form-control"
                                                                            value="{{ isset($admin_payment_setting['iyzipay_key']) ? $admin_payment_setting['iyzipay_key'] : '' }}"
                                                                            placeholder="{{ __('Iyzipay Key') }}">
                                                                    </div>
                                                                    @if ($errors->has('iyzipay_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('iyzipay_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="iyzipay_secret"
                                                                            class="col-form-label">{{ __('Iyzipay Secret') }}</label>
                                                                        <input type="text" name="iyzipay_secret"
                                                                            id="iyzipay_secret" class="form-control"
                                                                            value="{{ isset($admin_payment_setting['iyzipay_secret']) ? $admin_payment_setting['iyzipay_secret'] : '' }}"
                                                                            placeholder="{{ __('Merchant Key') }}">
                                                                    </div>
                                                                    @if ($errors->has('iyzipay_secret'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('iyzipay_secret') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingNineteen">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseNineteen"
                                                            aria-expanded="true" aria-controls="collapseNineteen">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Sspay') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('On/Off :')}}</span>
                                                                <div class="form-check form-switch d-inline-block custom-switch-v1">
                                                                    <input type="hidden" name="is_sspay_enabled"
                                                                        value="off">
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="is_sspay_enabled"
                                                                        id="is_sspay_enabled"
                                                                        {{ isset($admin_payment_setting['is_sspay_enabled']) && $admin_payment_setting['is_sspay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="custom-control-label form-label"
                                                                        for="is_sspay_enabled"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseNineteen" class="accordion-collapse collapse"aria-labelledby="headingNineteen"data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="sspay_category_code"
                                                                            class="col-form-label">{{ __('Sspay Category Code') }}</label>
                                                                        <input type="text" name="sspay_category_code"
                                                                            id="sspay_category_code" class="form-control"
                                                                            value="{{ isset($admin_payment_setting['sspay_category_code']) ? $admin_payment_setting['sspay_category_code'] : '' }}"
                                                                            placeholder="{{ __('Sspay Category Code') }}">
                                                                    </div>
                                                                    @if ($errors->has('sspay_category_code'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('sspay_category_code') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="sspay_secret_key"
                                                                            class="col-form-label">{{ __('Sspay Secret') }}</label>
                                                                        <input type="text" name="sspay_secret_key"
                                                                            id="sspay_secret_key" class="form-control"
                                                                            value="{{ isset($admin_payment_setting['sspay_secret_key']) ? $admin_payment_setting['sspay_secret_key'] : '' }}"
                                                                            placeholder="{{ __('Sspay Secret') }}">
                                                                    </div>
                                                                    @if ($errors->has('sspay_secret_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('sspay_secret_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingTwenty">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseTwenty"
                                                            aria-expanded="true" aria-controls="collapseTwenty">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Paytab') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('On/Off :')}}</span>
                                                                <div class="form-check form-switch d-inline-block custom-switch-v1">
                                                                    <input type="hidden" name="is_paytab_enabled"
                                                                        value="off">
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="is_paytab_enabled"
                                                                        id="is_paytab_enabled"
                                                                        {{ isset($admin_payment_setting['is_paytab_enabled']) && $admin_payment_setting['is_paytab_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="custom-control-label form-label"
                                                                        for="is_paytab_enabled"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseTwenty" class="accordion-collapse collapse"aria-labelledby="headingTwenty"data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paytab_profile_id"
                                                                            class="col-form-label">{{ __('Profile Id') }}</label>
                                                                        <input type="text" name="paytab_profile_id"
                                                                            id="paytab_profile_id" class="form-control"
                                                                            value="{{ isset($admin_payment_setting['paytab_profile_id']) ? $admin_payment_setting['paytab_profile_id'] : '' }}"
                                                                            placeholder="{{ __('Profile Id') }}">
                                                                    </div>
                                                                    @if ($errors->has('paytab_profile_id'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('paytab_profile_id') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paytab_server_key"
                                                                            class="col-form-label">{{ __('Server Key') }}</label>
                                                                        <input type="text" name="paytab_server_key"
                                                                            id="paytab_server_key" class="form-control"
                                                                            value="{{ isset($admin_payment_setting['paytab_server_key']) ? $admin_payment_setting['paytab_server_key'] : '' }}"
                                                                            placeholder="{{ __('Paytab Secret') }}">
                                                                    </div>
                                                                    @if ($errors->has('paytab_server_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('paytab_server_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paytab_region"
                                                                            class="form-label">{{ __('Region') }}</label>
                                                                        <input type="text" name="paytab_region"
                                                                            id="paytab_region"
                                                                            class="form-control form-control-label"
                                                                            value="{{ isset($admin_payment_setting['paytab_region']) ? $admin_payment_setting['paytab_region'] : '' }}"
                                                                            placeholder="{{ __('Region') }}" /><br>
                                                                        @if ($errors->has('paytab_region'))
                                                                            <span class="invalid-feedback d-block">
                                                                                {{ $errors->first('paytab_region') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingTwentyOne">
                                                        <button class="accordion-button collapsed"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapseTwentyOne"
                                                            aria-expanded="false"
                                                            aria-controls="collapseTwentyOne">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Benefit') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('On/Off') }}:</span>
                                                                <div
                                                                    class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_benefit_enabled" value="off">
                                                                    <input type="checkbox" class="form-check-input input-primary" name="is_benefit_enabled" id="is_benefit_enabled" {{ isset($admin_payment_setting['is_benefit_enabled']) && $admin_payment_setting['is_benefit_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="is_benefit_enabled"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseTwentyOne"
                                                        class="accordion-collapse collapse"
                                                        aria-labelledby="headingTwentyOne"
                                                        data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">
                                                                
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        {{ Form::label('benefit_api_key', __('Benefit Key'), ['class' => 'col-form-label']) }}
                                                                        {{ Form::text('benefit_api_key', isset($admin_payment_setting['benefit_api_key']) ? $admin_payment_setting['benefit_api_key'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Benefit Key')]) }}
                                                                        @error('benefit_api_key')
                                                                            <span class="invalid-benefit_api_key" role="alert">
                                                                                <strong
                                                                                    class="text-danger">{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        {{ Form::label('benefit_secret_key', __('Benefit Secret Key'), ['class' => 'col-form-label']) }}
                                                                        {{ Form::text('benefit_secret_key', isset($admin_payment_setting['benefit_secret_key']) ? $admin_payment_setting['benefit_secret_key'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Benefit Secret key')]) }}
                                                                        @error('benefit_secret_key')
                                                                            <span class="invalid-benefit_secret_key"
                                                                                role="alert">
                                                                                <strong
                                                                                    class="text-danger">{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingTwentyTwo">
                                                        <button class="accordion-button collapsed"
                                                            type="button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapseTwentyTwo"
                                                            aria-expanded="false"
                                                            aria-controls="collapseTwentyTwo">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Cashfree') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('On/Off') }}:</span>
                                                                <div
                                                                    class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_cashfree_enabled" value="off">
                                                                    <input type="checkbox" class="form-check-input input-primary" name="is_cashfree_enabled" id="is_cashfree_enabled" {{ isset($admin_payment_setting['is_cashfree_enabled']) && $admin_payment_setting['is_cashfree_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="is_cashfree_enabled"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseTwentyTwo"
                                                        class="accordion-collapse collapse"
                                                        aria-labelledby="headingTwentyTwo"
                                                        data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        {{ Form::label('cashfree_api_key', __('Cashfree Key'), ['class' => 'col-form-label']) }}
                                                                        {{ Form::text('cashfree_api_key', isset($admin_payment_setting['cashfree_api_key']) ? $admin_payment_setting['cashfree_api_key'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Cashfree Key')]) }}
                                                                        @error('cashfree_api_key')
                                                                            <span class="invalid-cashfree_api_key" role="alert">
                                                                                <strong
                                                                                    class="text-danger">{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        {{ Form::label('cashfree_secret_key', __('Cashfree Secret Key'), ['class' => 'col-form-label']) }}
                                                                        {{ Form::text('cashfree_secret_key', isset($admin_payment_setting['cashfree_secret_key']) ? $admin_payment_setting['cashfree_secret_key'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Cashfree Secret key')]) }}
                                                                        @error('cashfree_secret_key')
                                                                            <span class="invalid-cashfree_secret_key"
                                                                                role="alert">
                                                                                <strong
                                                                                    class="text-danger">{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingTwentyThree">
                                                        <button class="accordion-button" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseTwentyThree"
                                                            aria-expanded="true" aria-controls="collapseTwentyThree">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Aamarpay') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <label class="form-check-label m-1"
                                                                    for="is_aamarpay_enabled">{{ __('On/Off :') }}</label>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_aamarpay_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_aamarpay_enabled" id="is_aamarpay_enabled"
                                                                        {{ isset($admin_payment_setting['is_aamarpay_enabled']) && $admin_payment_setting['is_aamarpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseTwentyThree" class="accordion-collapse collapse"
                                                        aria-labelledby="headingTwentyThree" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row pt-2">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        {{ Form::label('aamarpay_store_id', __('Store Id'), ['class' => 'form-label']) }}
                                                                        {{ Form::text('aamarpay_store_id', isset($admin_payment_setting['aamarpay_store_id']) ? $admin_payment_setting['aamarpay_store_id'] : '', ['class' => 'form-control', 'placeholder' => __('Store Id')]) }}<br>
                                                                        @if ($errors->has('aamarpay_store_id'))
                                                                            <span class="invalid-feedback d-block">
                                                                                {{ $errors->first('aamarpay_store_id') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        {{ Form::label('aamarpay_signature_key', __('Signature Key'), ['class' => 'form-label']) }}
                                                                        {{ Form::text('aamarpay_signature_key', isset($admin_payment_setting['aamarpay_signature_key']) ? $admin_payment_setting['aamarpay_signature_key'] : '', ['class' => 'form-control', 'placeholder' => __('Signature Key')]) }}<br>
                                                                        @if ($errors->has('aamarpay_signature_key'))
                                                                            <span class="invalid-feedback d-block">
                                                                                {{ $errors->first('aamarpay_signature_key') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        {{ Form::label('aamarpay_description', __('Description'), ['class' => 'form-label']) }}
                                                                        {{ Form::text('aamarpay_description', isset($admin_payment_setting['aamarpay_description']) ? $admin_payment_setting['aamarpay_description'] : '', ['class' => 'form-control', 'placeholder' => __('Description')]) }}<br>
                                                                        @if ($errors->has('aamarpay_description'))
                                                                            <span class="invalid-feedback d-block">
                                                                                {{ $errors->first('aamarpay_description') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingTwentyFour">
                                                        <button class="accordion-button" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseTwentyFour"
                                                            aria-expanded="true" aria-controls="collapseTwentyFour">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Paytr') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <label class="form-check-label m-1"
                                                                    for="is_paytr_enabled">{{ __('On/Off :') }}</label>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_paytr_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_paytr_enabled" id="is_paytr_enabled"
                                                                        {{ isset($admin_payment_setting['is_paytr_enabled']) && $admin_payment_setting['is_paytr_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseTwentyFour" class="accordion-collapse collapse"
                                                        aria-labelledby="headingTwentyFour" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row pt-2">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        {{ Form::label('paytr_merchant_id', __('Merchant Id'), ['class' => 'form-label']) }}
                                                                        {{ Form::text('paytr_merchant_id', isset($admin_payment_setting['paytr_merchant_id']) ? $admin_payment_setting['paytr_merchant_id'] : '', ['class' => 'form-control', 'placeholder' => __('Merchant Id')]) }}<br>
                                                                        @if ($errors->has('paytr_merchant_id'))
                                                                            <span class="invalid-feedback d-block">
                                                                                {{ $errors->first('paytr_merchant_id') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        {{ Form::label('paytr_merchant_key', __('Merchant Key'), ['class' => 'form-label']) }}
                                                                        {{ Form::text('paytr_merchant_key', isset($admin_payment_setting['paytr_merchant_key']) ? $admin_payment_setting['paytr_merchant_key'] : '', ['class' => 'form-control', 'placeholder' => __('Merchant Key')]) }}<br>
                                                                        @if ($errors->has('paytr_merchant_key'))
                                                                            <span class="invalid-feedback d-block">
                                                                                {{ $errors->first('paytr_merchant_key') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        {{ Form::label('paytr_merchant_salt', __('Merchant Salt'), ['class' => 'form-label']) }}
                                                                        {{ Form::text('paytr_merchant_salt', isset($admin_payment_setting['paytr_merchant_salt']) ? $admin_payment_setting['paytr_merchant_salt'] : '', ['class' => 'form-control', 'placeholder' => __('Merchant Salt')]) }}<br>
                                                                        @if ($errors->has('paytr_merchant_salt'))
                                                                            <span class="invalid-feedback d-block">
                                                                                {{ $errors->first('paytr_merchant_salt') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingTwentyFive">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseTwentyFive"
                                                            aria-expanded="true" aria-controls="collapseTwentyFive">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Yookassa') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('On/Off :')}}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_yookassa_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_yookassa_enabled" id="is_yookassa_enabled"
                                                                        {{ isset($admin_payment_setting['is_yookassa_enabled']) && $admin_payment_setting['is_yookassa_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>
        
                                                        </button>
                                                    </h2>
                                                    <div id="collapseTwentyFive" class="accordion-collapse collapse" aria-labelledby="headingTwentyFive" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="yookassa_shop_id"
                                                                            class="form-label">{{ __('Shop ID Key') }}</label>
                                                                        <input type="text" name="yookassa_shop_id"
                                                                            id="yookassa_shop_id" class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['yookassa_shop_id']) || is_null($admin_payment_setting['yookassa_shop_id']) ? '' : $admin_payment_setting['yookassa_shop_id'] }}"
                                                                            placeholder="{{ __('Shop ID Key') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="yookassa_secret"
                                                                            class="form-label">{{ __('Secret Key') }}</label>
                                                                        <input type="text" name="yookassa_secret"
                                                                            id="yookassa_secret" class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['yookassa_secret']) || is_null($admin_payment_setting['yookassa_secret']) ? '' : $admin_payment_setting['yookassa_secret'] }}"
                                                                            placeholder="{{ __('Secret Key') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingTwentySix">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseTwentySix"
                                                            aria-expanded="true" aria-controls="collapseTwentySix">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Midtrans') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('On/Off :')}}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_midtrans_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_midtrans_enabled" id="is_midtrans_enabled"
                                                                        {{ isset($admin_payment_setting['is_midtrans_enabled']) && $admin_payment_setting['is_midtrans_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>
        
                                                        </button>
                                                    </h2>
                                                    <div id="collapseTwentySix" class="accordion-collapse collapse" aria-labelledby="headingTwentySix" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-lg-12 pb-4">
                                                                    <label class="midtrans-label col-form-label" for="midtrans_mode">{{ __('Midtrans Mode') }}</label>
                                                                    <br>
                                                                    <div class="d-flex flex-wrap">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="midtrans_mode"
                                                                                            value="sandbox"
                                                                                            class="form-check-input"
                                                                                            {{ !isset($store_payment_setting['midtrans_mode']) || $store_payment_setting['midtrans_mode'] == '' || $store_payment_setting['midtrans_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                        {{ __('Sandbox') }}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2 me-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="midtrans_mode"
                                                                                            value="live"
                                                                                            class="form-check-input"
                                                                                            {{ isset($store_payment_setting['midtrans_mode']) && $store_payment_setting['midtrans_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                        {{ __('Live') }}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="midtrans_secret"
                                                                            class="form-label">{{ __('Secret Key') }}</label>
                                                                        <input type="text" name="midtrans_secret"
                                                                            id="midtrans_secret" class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['midtrans_secret']) || is_null($admin_payment_setting['midtrans_secret']) ? '' : $admin_payment_setting['midtrans_secret'] }}"
                                                                            placeholder="{{ __('Secret Key') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingTwentySeven">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseTwentySeven"
                                                            aria-expanded="true" aria-controls="collapseTwentySeven">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Xendit') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('On/Off :')}}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_xendit_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_xendit_enabled" id="is_xendit_enabled"
                                                                        {{ isset($admin_payment_setting['is_xendit_enabled']) && $admin_payment_setting['is_xendit_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>
        
                                                        </button>
                                                    </h2>
                                                    <div id="collapseTwentySeven" class="accordion-collapse collapse" aria-labelledby="headingTwentySeven" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="xendit_api"
                                                                            class="form-label">{{ __('API Key') }}</label>
                                                                        <input type="text" name="xendit_api"
                                                                            id="xendit_api" class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['xendit_api']) || is_null($admin_payment_setting['xendit_api']) ? '' : $admin_payment_setting['xendit_api'] }}"
                                                                            placeholder="{{ __('API Key') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="xendit_token"
                                                                            class="form-label">{{ __('Token') }}</label>
                                                                        <input type="text" name="xendit_token"
                                                                            id="xendit_token" class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['xendit_token']) || is_null($admin_payment_setting['xendit_token']) ? '' : $admin_payment_setting['xendit_token'] }}"
                                                                            placeholder="{{ __('Token') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingTwentyEight">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseTwentyEight"
                                                            aria-expanded="true" aria-controls="collapseTwentyEight">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Nepalste') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('On/Off :')}}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_nepalste_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_nepalste_enabled" id="is_nepalste_enabled"
                                                                        {{ isset($admin_payment_setting['is_nepalste_enabled']) && $admin_payment_setting['is_nepalste_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>
        
                                                        </button>
                                                    </h2>
                                                    <div id="collapseTwentyEight" class="accordion-collapse collapse" aria-labelledby="headingTwentyEight" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="nepalste_public_key"
                                                                            class="form-label">{{ __('Public Key') }}</label>
                                                                        <input type="text" name="nepalste_public_key"
                                                                            id="nepalste_public_key" class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['nepalste_public_key']) || is_null($admin_payment_setting['nepalste_public_key']) ? '' : $admin_payment_setting['nepalste_public_key'] }}"
                                                                            placeholder="{{ __('Public Key') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="nepalste_secret_key"
                                                                            class="form-label">{{ __('Secret Key') }}</label>
                                                                        <input type="text" name="nepalste_secret_key"
                                                                            id="nepalste_secret_key" class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['nepalste_secret_key']) || is_null($admin_payment_setting['nepalste_secret_key']) ? '' : $admin_payment_setting['nepalste_secret_key'] }}"
                                                                            placeholder="{{ __('Secret Key') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingTwentyNine">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseTwentyNine"
                                                            aria-expanded="true" aria-controls="collapseTwentyNine">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Paiement Pro') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('On/Off :')}}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_paiementpro_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_paiementpro_enabled" id="is_paiementpro_enabled"
                                                                        {{ isset($admin_payment_setting['is_paiementpro_enabled']) && $admin_payment_setting['is_paiementpro_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>
        
                                                        </button>
                                                    </h2>
                                                    <div id="collapseTwentyNine" class="accordion-collapse collapse" aria-labelledby="headingTwentyNine" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paiementpro_merchant_id"
                                                                            class="form-label">{{ __('Merchant Id') }}</label>
                                                                        <input type="text" name="paiementpro_merchant_id"
                                                                            id="paiementpro_merchant_id" class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['paiementpro_merchant_id']) || is_null($admin_payment_setting['paiementpro_merchant_id']) ? '' : $admin_payment_setting['paiementpro_merchant_id'] }}"
                                                                            placeholder="{{ __('Merchant Id') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingThirty">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseThirty"
                                                            aria-expanded="true" aria-controls="collapseThirty">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Fedapay') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('On/Off :')}}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_fedapay_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_fedapay_enabled" id="is_fedapay_enabled"
                                                                        {{ isset($admin_payment_setting['is_fedapay_enabled']) && $admin_payment_setting['is_fedapay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>
        
                                                        </button>
                                                    </h2>
                                                    <div id="collapseThirty" class="accordion-collapse collapse" aria-labelledby="headingThirty" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-lg-12 pb-4">
                                                                    <label class="fedapay-label col-form-label" for="fedapay_mode">{{ __('Fedapay Mode') }}</label>
                                                                    <br>
                                                                    <div class="d-flex flex-wrap">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="fedapay_mode"
                                                                                            value="sandbox"
                                                                                            class="form-check-input"
                                                                                            {{ !isset($admin_payment_setting['fedapay_mode']) || $admin_payment_setting['fedapay_mode'] == '' || $admin_payment_setting['fedapay_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                        {{ __('Sandbox') }}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2 me-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="fedapay_mode"
                                                                                            value="live"
                                                                                            class="form-check-input"
                                                                                            {{ isset($admin_payment_setting['fedapay_mode']) && $admin_payment_setting['fedapay_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                        {{ __('Live') }}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="fedapay_public_key"
                                                                            class="form-label">{{ __('Public Key') }}</label>
                                                                        <input type="text" name="fedapay_public_key"
                                                                            id="fedapay_public_key" class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['fedapay_public_key']) || is_null($admin_payment_setting['fedapay_public_key']) ? '' : $admin_payment_setting['fedapay_public_key'] }}"
                                                                            placeholder="{{ __('Public Key') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="fedapay_secret_key"
                                                                            class="form-label">{{ __('Secret Key') }}</label>
                                                                        <input type="text" name="fedapay_secret_key"
                                                                            id="fedapay_secret_key" class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['fedapay_secret_key']) || is_null($admin_payment_setting['fedapay_secret_key']) ? '' : $admin_payment_setting['fedapay_secret_key'] }}"
                                                                            placeholder="{{ __('Secret Key') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingThirtyOne">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseThirtyOne"
                                                            aria-expanded="true" aria-controls="collapseThirtyOne">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('PayHere') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('On/Off :')}}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_payhere_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_payhere_enabled" id="is_payhere_enabled"
                                                                        {{ isset($admin_payment_setting['is_payhere_enabled']) && $admin_payment_setting['is_payhere_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>
        
                                                        </button>
                                                    </h2>
                                                    <div id="collapseThirtyOne" class="accordion-collapse collapse" aria-labelledby="headingThirtyOne" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-lg-12 pb-4">
                                                                    <label class="payhere-label col-form-label" for="payhere_mode">{{ __('PayHere Mode') }}</label>
                                                                    <br>
                                                                    <div class="d-flex flex-wrap">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="payhere_mode"
                                                                                            value="sandbox"
                                                                                            class="form-check-input"
                                                                                            {{ !isset($admin_payment_setting['payhere_mode']) || $admin_payment_setting['payhere_mode'] == '' || $admin_payment_setting['payhere_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                        {{ __('Sandbox') }}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2 me-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="payhere_mode"
                                                                                            value="live"
                                                                                            class="form-check-input"
                                                                                            {{ isset($admin_payment_setting['payhere_mode']) && $admin_payment_setting['payhere_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                        {{ __('Live') }}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="payhere_merchant_id"
                                                                            class="form-label">{{ __('Merchant ID') }}</label>
                                                                        <input type="text" name="payhere_merchant_id"
                                                                            id="payhere_merchant_id" class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['payhere_merchant_id']) || is_null($admin_payment_setting['payhere_merchant_id']) ? '' : $admin_payment_setting['payhere_merchant_id'] }}"
                                                                            placeholder="{{ __('Merchant ID') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="payhere_merchant_secret"
                                                                            class="form-label">{{ __('Merchant Secret') }}</label>
                                                                        <input type="text" name="payhere_merchant_secret"
                                                                            id="payhere_merchant_secret" class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['payhere_merchant_secret']) || is_null($admin_payment_setting['payhere_merchant_secret']) ? '' : $admin_payment_setting['payhere_merchant_secret'] }}"
                                                                            placeholder="{{ __('Merchant Secret') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="payhere_app_id"
                                                                            class="form-label">{{ __('App ID') }}</label>
                                                                        <input type="text" name="payhere_app_id"
                                                                            id="payhere_app_id" class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['payhere_app_id']) || is_null($admin_payment_setting['payhere_app_id']) ? '' : $admin_payment_setting['payhere_app_id'] }}"
                                                                            placeholder="{{ __('App ID') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="payhere_app_secret"
                                                                            class="form-label">{{ __('App Secret') }}</label>
                                                                        <input type="text" name="payhere_app_secret"
                                                                            id="payhere_app_secret" class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['payhere_app_secret']) || is_null($admin_payment_setting['payhere_app_secret']) ? '' : $admin_payment_setting['payhere_app_secret'] }}"
                                                                            placeholder="{{ __('App Secret') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingThirtyTwo">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseThirtyTwo"
                                                            aria-expanded="true" aria-controls="collapseThirtyTwo">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('CinetPay') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('On/Off :')}}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_cinetpay_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_cinetpay_enabled" id="is_cinetpay_enabled"
                                                                        {{ isset($admin_payment_setting['is_cinetpay_enabled']) && $admin_payment_setting['is_cinetpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>
        
                                                        </button>
                                                    </h2>
                                                    <div id="collapseThirtyTwo" class="accordion-collapse collapse" aria-labelledby="headingThirtyTwo" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="cinetpay_site_id"
                                                                            class="form-label">{{ __('CinetPay Site ID') }}</label>
                                                                        <input type="text" name="cinetpay_site_id"
                                                                            id="cinetpay_site_id" class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['cinetpay_site_id']) || is_null($admin_payment_setting['cinetpay_site_id']) ? '' : $admin_payment_setting['cinetpay_site_id'] }}"
                                                                            placeholder="{{ __('CinetPay Site ID') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="cinetpay_api_key"
                                                                            class="form-label">{{ __('CinetPay API Key') }}</label>
                                                                        <input type="text" name="cinetpay_api_key"
                                                                            id="cinetpay_api_key" class="form-control"
                                                                            value="{{ !isset($admin_payment_setting['cinetpay_api_key']) || is_null($admin_payment_setting['cinetpay_api_key']) ? '' : $admin_payment_setting['cinetpay_api_key'] }}"
                                                                            placeholder="{{ __('CinetPay API Key') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer p-0">
                                        <div class="col-sm-12 mt-3 px-2">
                                            <div class="text-end">
                                                {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-email-settings" role="tabpanel" aria-labelledby="pills-brand_setting-tab">
                        <div class="col-md-12">

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="">
                                        {{ __('Email Settings') }}
                                    </h5>
                                    <small>{{ __('(This SMTP will be used for system-level email sending. Additionally, if a company user does not set their SMTP, then this SMTP will be used for sending emails.)') }}</small>
                                </div>
                                <div class="card-body p-4">
                                    {{ Form::open(['route' => 'email.setting', 'method' => 'post']) }}
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                            {{ Form::label('mail_driver', __('Mail Driver'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_driver', isset($settings['mail_driver']) ? $settings['mail_driver'] : '', ['class' => 'form-control', 'id' => 'mail_driver', 'placeholder' => __('Enter Mail Driver')]) }}
                                            @error('mail_driver')
                                                <span class="invalid-mail_driver" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                            {{ Form::label('mail_host', __('Mail Host'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_host', isset($settings['mail_host']) ? $settings['mail_host'] : '', ['class' => 'form-control ', 'id' => 'mail_host', 'placeholder' => __('Enter Mail Driver')]) }}
                                            @error('mail_host')
                                                <span class="invalid-mail_driver" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                            {{ Form::label('mail_port', __('Mail Port'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_port', isset($settings['mail_port']) ? $settings['mail_port'] : '', ['class' => 'form-control', 'id' => 'mail_port', 'placeholder' => __('Enter Mail Port')]) }}
                                            @error('mail_port')
                                                <span class="invalid-mail_port" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                            {{ Form::label('mail_username', __('Mail Username'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_username', isset($settings['mail_username']) ? $settings['mail_username'] : '', ['class' => 'form-control', 'id' => 'mail_username', 'placeholder' => __('Enter Mail Username')]) }}
                                            @error('mail_username')
                                                <span class="invalid-mail_username" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                            {{ Form::label('mail_password', __('Mail Password'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_password', isset($settings['mail_password']) ? $settings['mail_password'] : '', ['class' => 'form-control', 'id' => 'mail_password', 'placeholder' => __('Enter Mail Password')]) }}
                                            @error('mail_password')
                                                <span class="invalid-mail_password" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                            {{ Form::label('mail_encryption', __('Mail Encryption'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_encryption', isset($settings['mail_encryption']) ? $settings['mail_encryption'] : '', ['class' => 'form-control', 'id' => 'mail_encryption', 'placeholder' => __('Enter Mail Encryption')]) }}
                                            @error('mail_encryption')
                                                <span class="invalid-mail_encryption" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                            {{ Form::label('mail_from_address', __('Mail From Address'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_from_address', isset($settings['mail_from_address']) ? $settings['mail_from_address'] : '', ['class' => 'form-control', 'id' => 'mail_from_address', 'placeholder' => __('Enter Mail From Address')]) }}
                                            @error('mail_from_address')
                                                <span class="invalid-mail_from_address" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6 form-group">
                                            {{ Form::label('mail_from_name', __('Mail From Name'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_from_name', isset($settings['mail_from_name']) ? $settings['mail_from_name'] : '', ['class' => 'form-control', 'id' => 'mail_from_name', 'placeholder' => __('Enter Mail Encryption')]) }}
                                            @error('mail_from_name')
                                                <span class="invalid-mail_from_name" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="col-lg-12 ">
                                        <div class="row">
                                            <div class=" text-end">
                                                <div class="card-footer p-0">
                                                    <div class="col-sm-12 mt-3 px-2">
                                                        <div class="d-flex justify-content-between gap-2 flex-column flex-sm-row">
                                                            <a href="#" 
                                                                data-size="md" data-url="{{ route('test.mail') }}"
                                                                data-title="{{ __('Send Test Mail') }}"
                                                                class="btn btn-xs  btn-primary send_email">
                                                                {{ __('Send Test Mail') }}
                                                            </a>
                                                            {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-recaptcha-settings" role="tabpanel" aria-labelledby="pills-brand_setting-tab">
                        <form method="POST" action="{{ route('recaptcha.settings.store') }}" accept-charset="UTF-8">
                            @csrf
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row gy-2">
                                            <div class="col-lg-8 col-md-8 col-sm-8">
                                                <h5 class="">{{ __('ReCaptcha Settings') }}</h5><small
                                                    class="text-secondary font-weight-bold"><a
                                                        href="https://phppot.com/php/how-to-get-google-recaptcha-site-and-secret-key/"
                                                        target="_blank" class="text-blue">
                                                        <small>({{ __('How to Get Google reCaptcha Site and Secret key') }})</small>
                                                    </a></small>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 text-sm-end">
                                                <div class="col switch-width">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" data-toggle="switchbutton"
                                                            data-onstyle="primary" class="" value="yes"
                                                            name="recaptcha_module" id="recaptcha_module"
                                                            {{ !empty($settings['RECAPTCHA_MODULE']) && $settings['RECAPTCHA_MODULE'] == 'yes' ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label form-control-label px-2"
                                                            for="recaptcha_module "></label><br>
                                                        <a href="https://phppot.com/php/how-to-get-google-recaptcha-site-and-secret-key/"
                                                            target="_blank" class="text-blue">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        @csrf
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                                <label for="google_recaptcha_key"
                                                    class="form-label">{{ __('Google Recaptcha Key') }}</label>
                                                <input class="form-control"
                                                    placeholder="{{ __('Enter Google Recaptcha Key') }}"
                                                    name="google_recaptcha_key" type="text"
                                                    value="{{ isset($settings['NOCAPTCHA_SITEKEY']) ? $settings['NOCAPTCHA_SITEKEY'] : '' }}" id="google_recaptcha_key">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                                <label for="google_recaptcha_secret"
                                                    class="form-label">{{ __('Google Recaptcha Secret') }}</label>
                                                <input class="form-control "
                                                    placeholder="{{ __('Enter Google Recaptcha Secret') }}"
                                                    name="google_recaptcha_secret" type="text"
                                                    value="{{ isset($settings['NOCAPTCHA_SECRET']) ? $settings['NOCAPTCHA_SECRET'] : '' }}"
                                                    id="google_recaptcha_secret">
                                            </div>
                                        </div>
                                        <div class="card-footer p-0">
                                            <div class="col-sm-12 mt-3 px-2">
                                                <div class="text-end">
                                                    {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="storage_settings" role="tabpanel" aria-labelledby="pills-brand_setting-tab">
                        {{ Form::open(array('route' => 'storage.setting.store', 'enctype' => "multipart/form-data")) }}
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-10 col-md-10 col-sm-10">
                                            <h5 class="">{{ __('Storage Settings') }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="pe-2">
                                            <input type="radio" class="btn-check" name="storage_setting" id="local-outlined" autocomplete="off" {{  $settings['storage_setting'] == 'local'?'checked':'' }} value="local" checked>
                                            <label class="btn btn-outline-primary" for="local-outlined">{{ __('Local') }}</label>
                                        </div>
                                        <div  class="pe-2">
                                            <input type="radio" class="btn-check" name="storage_setting" id="s3-outlined" autocomplete="off" {{  $settings['storage_setting']=='s3'?'checked':'' }}  value="s3">
                                            <label class="btn btn-outline-primary" for="s3-outlined"> {{ __('AWS S3') }}</label>
                                        </div>

                                        <div  class="pe-2">
                                            <input type="radio" class="btn-check" name="storage_setting" id="wasabi-outlined" autocomplete="off" {{  $settings['storage_setting']=='wasabi'?'checked':'' }} value="wasabi">
                                            <label class="btn btn-outline-primary" for="wasabi-outlined">{{ __('Wasabi') }}</label>
                                        </div>
                                    </div>
                                    <div  class="mt-2">
                                    <div class="local-setting row {{  $settings['storage_setting']=='local'?' ':'d-none' }}">
                                        {{-- <h4 class="small-title">{{ __('Local Settings') }}</h4> --}}
                                        <div class="col-lg-6 col-md-11 col-sm-12">
                                            {{Form::label('local_storage_validation',__('Only Upload Files'),array('class'=>' form-label')) }}
                                            <select name="local_storage_validation[]" class="form-control" name="choices-multiple-remove-button" id="choices-multiple-remove-button" placeholder="This is a placeholder" multiple>
                                                @foreach($file_type as $f)
                                                <option @if (in_array($f, $local_storage_validations)) selected @endif>{{$f}}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label" for="local_storage_max_upload_size">{{ __('Max upload size ( In KB)')}}</label>
                                                <input type="number" name="local_storage_max_upload_size" class="form-control" value="{{(!isset($settings['local_storage_max_upload_size']) || is_null($settings['local_storage_max_upload_size'])) ? '' : $settings['local_storage_max_upload_size']}}" placeholder="{{ __('Max upload size') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="s3-setting row {{  $settings['storage_setting']=='s3'?' ':'d-none' }}">

                                        <div class=" row ">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="s3_key">{{ __('S3 Key') }}</label>
                                                    <input type="text" name="s3_key" class="form-control" value="{{(!isset($settings['s3_key']) || is_null($settings['s3_key'])) ? '' : $settings['s3_key']}}" placeholder="{{ __('S3 Key') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="s3_secret">{{ __('S3 Secret') }}</label>
                                                    <input type="text" name="s3_secret" class="form-control" value="{{(!isset($settings['s3_secret']) || is_null($settings['s3_secret'])) ? '' : $settings['s3_secret']}}" placeholder="{{ __('S3 Secret') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="s3_region">{{ __('S3 Region') }}</label>
                                                    <input type="text" name="s3_region" class="form-control" value="{{(!isset($settings['s3_region']) || is_null($settings['s3_region'])) ? '' : $settings['s3_region']}}" placeholder="{{ __('S3 Region') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="s3_bucket">{{ __('S3 Bucket') }}</label>
                                                    <input type="text" name="s3_bucket" class="form-control" value="{{(!isset($settings['s3_bucket']) || is_null($settings['s3_bucket'])) ? '' : $settings['s3_bucket']}}" placeholder="{{ __('S3 Bucket') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="s3_url">{{ __('S3 URL')}}</label>
                                                    <input type="text" name="s3_url" class="form-control" value="{{(!isset($settings['s3_url']) || is_null($settings['s3_url'])) ? '' : $settings['s3_url']}}" placeholder="{{ __('S3 URL')}}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="s3_endpoint">{{ __('S3 Endpoint')}}</label>
                                                    <input type="text" name="s3_endpoint" class="form-control" value="{{(!isset($settings['s3_endpoint']) || is_null($settings['s3_endpoint'])) ? '' : $settings['s3_endpoint']}}" placeholder="{{ __('S3 Endpoint') }}">
                                                </div>
                                            </div>
                                            <div class="form-group col-8 switch-width">
                                                {{Form::label('s3_storage_validation',__('Only Upload Files'),array('class'=>' form-label')) }}
                                                    <select name="s3_storage_validation[]"  class="form-control" name="choices-multiple-remove-button" id="choices-multiple-remove-button1" placeholder="This is a placeholder" multiple>
                                                        @foreach($file_type as $f)
                                                            <option @if (in_array($f, $s3_storage_validations)) selected @endif>{{$f}}</option>
                                                        @endforeach
                                                    </select>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="s3_max_upload_size">{{__('Max upload size (In KB)')}}</label>
                                                    <input type="number" name="s3_max_upload_size" class="form-control" value="{{(!isset($settings['s3_max_upload_size']) || is_null($settings['s3_max_upload_size'])) ? '' : $settings['s3_max_upload_size']}}" placeholder="{{ __('Max upload size') }}">
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="wasabi-setting row {{  $settings['storage_setting']=='wasabi'?' ':'d-none' }}">
                                        <div class=" row ">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="s3_key">{{ __('Wasabi Key') }}</label>
                                                    <input type="text" name="wasabi_key" class="form-control" value="{{(!isset($settings['wasabi_key']) || is_null($settings['wasabi_key'])) ? '' : $settings['wasabi_key']}}" placeholder="{{ __('Wasabi Key') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="s3_secret">{{ __('Wasabi Secret') }}</label>
                                                    <input type="text" name="wasabi_secret" class="form-control" value="{{(!isset($settings['wasabi_secret']) || is_null($settings['wasabi_secret'])) ? '' : $settings['wasabi_secret']}}" placeholder="{{ __('Wasabi Secret') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="s3_region">{{ __('Wasabi Region') }}</label>
                                                    <input type="text" name="wasabi_region" class="form-control" value="{{(!isset($settings['wasabi_region']) || is_null($settings['wasabi_region'])) ? '' : $settings['wasabi_region']}}" placeholder="{{ __('Wasabi Region') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="wasabi_bucket">{{ __('Wasabi Bucket') }}</label>
                                                    <input type="text" name="wasabi_bucket" class="form-control" value="{{(!isset($settings['wasabi_bucket']) || is_null($settings['wasabi_bucket'])) ? '' : $settings['wasabi_bucket']}}" placeholder="{{ __('Wasabi Bucket') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="wasabi_url">{{ __('Wasabi URL')}}</label>
                                                    <input type="text" name="wasabi_url" class="form-control" value="{{(!isset($settings['wasabi_url']) || is_null($settings['wasabi_url'])) ? '' : $settings['wasabi_url']}}" placeholder="{{ __('Wasabi URL')}}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="wasabi_root">{{ __('Wasabi Root')}}</label>
                                                    <input type="text" name="wasabi_root" class="form-control" value="{{(!isset($settings['wasabi_root']) || is_null($settings['wasabi_root'])) ? '' : $settings['wasabi_root']}}" placeholder="{{ __('Wasabi Root') }}">
                                                </div>
                                            </div>
                                            <div class="form-group col-8 switch-width">
                                                {{Form::label('wasabi_storage_validation',__('Only Upload Files'),array('class'=>'form-label')) }}

                                                <select name="wasabi_storage_validation[]" class="form-control" name="choices-multiple-remove-button" id="choices-multiple-remove-button2" placeholder="This is a placeholder" multiple>
                                                    @foreach($file_type as $f)
                                                        <option @if (in_array($f, $wasabi_storage_validations)) selected @endif>{{$f}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="wasabi_root">{{ __('Max upload size ( In KB)')}}</label>
                                                    <input type="number" name="wasabi_max_upload_size" class="form-control" value="{{(!isset($settings['wasabi_max_upload_size']) || is_null($settings['wasabi_max_upload_size'])) ? '' : $settings['wasabi_max_upload_size']}}" placeholder="{{ __('Max upload size') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
                                </div>
                            </div>
                        {{Form::close()}}
                        </div>
                    </div> 
                    <div class="tab-pane fade" id="pills-cache-settings" role="tabpanel" aria-labelledby="pills-cache_settings-tab">
                        <div class="card mb-3">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6">
                                        <h5 class="h6 md-0">{{ __('Cache Settings') }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <p>{{ __('This is a page meant for more advanced users, simply ignore it if you do not
                                                understand what cache is.') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="input-group search-form">
                                            <input type="text" value="{{ Utility::GetCacheSize() }}" class="form-control" disabled>
                                            <span class="input-group-text bg-transparent">{{__('MB')}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <a href = "{{ url('config-cache') }}" class="btn btn-m btn-primary m-r-10 ">{{ __('Clear Cache') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-cookie-settings" role="tabpanel" aria-labelledby="pills-cookie_settings-tab">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <div class="card">
                
                                {{Form::model($settings,array('route'=>'cookie.setting','method'=>'post'))}}
                                    <div class="card-header flex-column flex-lg-row  d-flex align-items-lg-center gap-2 justify-content-between">
                                        <h5>{{ __('Cookie Settings') }}</h5>
                                        <div class="d-flex align-items-center">
                                            {{ Form::label('enable_cookie', __('Enable cookie'), ['class' => 'col-form-label p-0 fw-bold me-3']) }}
                                            <div class="custom-control custom-switch"  onclick="enablecookie()">
                                                <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary" name="enable_cookie" class="form-check-input input-primary "
                                                    id="enable_cookie" {{ $settings['enable_cookie'] == 'on' ? ' checked ' : '' }} >
                                                <label class="custom-control-label mb-1" for="enable_cookie"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body cookieDiv {{ $settings['enable_cookie'] == 'off' ? 'disabledCookie ' : '' }}">
                                        @if(!empty($chatgpt['chatgpt_key']) && $settings['enable_cookie'] == 'on')
                                            <div class="d-flex justify-content-end">
                                                <a href="#" class="btn btn-primary btn-sm" data-size="xl" data-ajax-popup-over="true" data-url="{{ route('generate',['cookie']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
                                                    <i class="fas fa-robot"></i> {{ __('Generate with AI') }}
                                                </a>
                                            </div>
                                        @endif
                                        <div class="row ">
                                            <div class="col-md-6">
                                                <div class="form-check form-switch custom-switch-v1" id="cookie_log">
                                                    <input type="checkbox" name="cookie_logging" class="form-check-input input-primary cookie_setting"
                                                        id="cookie_logging"{{ $settings['cookie_logging'] == 'on' ? ' checked ' : '' }}>
                                                    <label class="form-check-label" for="cookie_logging">{{__('Enable logging')}}</label>
                                                </div>
                                                <div class="form-group" >
                                                    {{ Form::label('cookie_title', __('Cookie Title'), ['class' => 'col-form-label' ]) }}
                                                    {{ Form::text('cookie_title', null, ['class' => 'form-control cookie_setting'] ) }}
                                                </div>
                                                <div class="form-group ">
                                                    {{ Form::label('cookie_description', __('Cookie Description'), ['class' => ' form-label']) }}
                                                    {!! Form::textarea('cookie_description', null, ['class' => 'form-control cookie_setting', 'rows' => '3']) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check form-switch custom-switch-v1 ">
                                                    <input type="checkbox" name="necessary_cookies" class="form-check-input input-primary"
                                                        id="necessary_cookies" checked onclick="return false">
                                                    <label class="form-check-label" for="necessary_cookies">{{__('Strictly necessary cookies')}}</label>
                                                </div>
                                                <div class="form-group ">
                                                    {{ Form::label('strictly_cookie_title', __(' Strictly Cookie Title'), ['class' => 'col-form-label']) }}
                                                    {{ Form::text('strictly_cookie_title', null, ['class' => 'form-control cookie_setting']) }}
                                                </div>
                                                <div class="form-group ">
                                                    {{ Form::label('strictly_cookie_description', __('Strictly Cookie Description'), ['class' => ' form-label']) }}
                                                    {!! Form::textarea('strictly_cookie_description', null, ['class' => 'form-control cookie_setting ', 'rows' => '3']) !!}
                                                </div>
                                            </div>
                                            
                                            <div class="col-12">
                                                <h5>{{__('More Information')}}</h5>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group ">
                                                    {{ Form::label('more_information_description', __('Contact Us Description'), ['class' => 'col-form-label']) }}
                                                    {{ Form::text('more_information_description', null, ['class' => 'form-control cookie_setting']) }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group ">
                                                    {{ Form::label('contactus_url', __('Contact Us URL'), ['class' => 'col-form-label']) }}
                                                    {{ Form::text('contactus_url', null, ['class' => 'form-control cookie_setting']) }}
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center gap-2 flex-sm-column flex-lg-row justify-content-between" >
                                        <div>
                                            @if(isset($settings['cookie_logging']) && $settings['cookie_logging'] == 'on')
                                            <label for="file" class="form-label">{{__('Download cookie accepted data')}}</label>
                                                <a href="{{ asset(Storage::url('uploads/sample')) . '/data.csv' }}" class="btn btn-primary mr-2 ">
                                                    <i class="ti ti-download"></i>
                                                </a>
                                                @endif
                                        </div>
                                        <input type="submit" value="{{ __('Save') }}" class="btn btn-primary">
                                    </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-chatgpt-settings" role="tabpanel" aria-labelledby="pills-chatgpt-tab">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                            <div class="card">
                                {{Form::model($settings,array('route'=>'settings.chatgptkey','method'=>'post'))}}
                                    <div class="card-header">
                                        <h5>{{ __('Chat GPT Settings') }}</h5>
                                        <small>{{ __('Edit your key details') }}</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                {{ Form::label('chatgpt_key', __('Chat GPT Key'), ['class' => 'col-form-label']) }}
                                                {{ Form::text('chatgpt_key',isset($settings['chatgpt_key']) ? $settings['chatgpt_key'] : '',['class'=>'form-control','placeholder'=>__('Enter Chatgpt Key Here')]) }}
                                            </div>
                                            <div class="col-md-6 form-group">
                                                {{ Form::label('chatgpt_model_name', __('Chat GPT Model Name'), ['class' => 'col-form-label']) }}
                                                {{ Form::text('chatgpt_model_name', isset($settings['chatgpt_model_name']) ? $settings['chatgpt_model_name'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Chat GPT Model Name ')]) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-end">
                                        <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                                    </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade active show" id="pills-brand-setting" role="tabpanel"
                        aria-labelledby="pills-brand_setting-tab">
                        {{ Form::model($settings, ['route' => 'business.setting', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Brand Settings') }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-4 col-sm-6 col-md-6">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h5>{{ __('Logo dark') }}</h5>
                                                        </div>

                                                        <div class="card-body pt-0">
                                                            <div class="setting-card">
                                                                <div class="mt-4">  {{-- logo-content --}}
                                                                    {{-- <img src="{{ $logo . '/' . (isset($logo_dark) && !empty($logo_dark) ? $logo_dark : ' logo-dark.png') }}"
                                                                        class="img-setting" width="170px"> --}}

                                                                    <a href="{{ route('dashboard') }}" class="b-brand">
                                                                        <img src="{{ $logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') . '?timestamp='. time() }}"
                                                                            alt="{{ config('app.name', 'Storego') }}"
                                                                            id="adminlogoDark"
                                                                            class="logo logo-lg nav-sidebar-logo fix-logo">
                                                                    </a>
                                                                </div>
                                                                <div class="choose-files mt-5">
                                                                    <label for="company_logo">
                                                                        <div class=" bg-primary company_logo_update">
                                                                            <i
                                                                                class="ti ti-upload "></i>{{ __('Choose file here') }}
                                                                            <input type="file" id="company_logo"
                                                                                data-filename="company_logo_update"
                                                                                name="logo_dark" class="form-control file"
                                                                                onchange=" document.getElementById('adminlogoDark').src = window.URL.createObjectURL(this.files[0])">
                                                                        </div>
                                                                        {{-- <input type="file" name="logo_dark"
                                                                        id="company_logo" class="form-control file "
                                                                        data-filename="company_logo_update"> --}}
                                                                    </label>

                                                                </div>
                                                                @error('company_logo')
                                                                    <div class="row">
                                                                        <span class="invalid-logo" role="alert">
                                                                            <strong
                                                                                class="text-danger">{{ $message }}</strong>
                                                                        </span>
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6 col-md-6">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h5>{{ __('Logo Light') }}</h5>
                                                        </div>
                                                        <div class="card-body pt-0">
                                                            <div class=" setting-card">
                                                                <div class="mt-4">  {{-- logo-content --}}
                                                                    <a href="{{ $logo . '/' . (isset($logo_light) && !empty($logo_light) ? $logo_light : 'logo-light.png') }}"
                                                                        target="_blank">
                                                                        <img src="{{ $logo . '/' . (isset($logo_light) && !empty($logo_light) ? $logo_light : 'logo-light.png') . '?timestamp='. time() }}"
                                                                            class=" img_setting fix-logo" width="170px"
                                                                            id="logo-light">
                                                                    </a>
                                                                    
                                                                    {{--  <a href="{{ $logo . 'logo-light.png' }}" target="_blank">
                                                                        <img id="logo-light" alt="your image"
                                                                            src="{{ $logo . 'logo-light.png' }}" width="170px"
                                                                            class="img_setting">
                                                                    </a>  --}}
                                                                </div>
                                                                <div class="choose-files mt-5">
                                                                    <label for="company_logo_light">
                                                                        <div class=" bg-primary dark_logo_update"> <i
                                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                        </div>
                                                                        <input type="file" class="form-control file"
                                                                            name="logo_light" id="company_logo_light"
                                                                            data-filename="dark_logo_update"
                                                                            onchange=" document.getElementById('logo-light').src = window.URL.createObjectURL(this.files[0])">
                                                                    </label>
                                                                </div>
                                                                @error('company_logo_light')
                                                                    <div class="row">
                                                                        <span class="invalid-logo" role="alert">
                                                                            <strong
                                                                                class="text-danger">{{ $message }}</strong>
                                                                        </span>
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6 col-md-6">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h5>{{ __('Favicon') }}</h5>
                                                        </div>
                                                        <div class="card-body pt-0">
                                                            <div class=" setting-card">
                                                                <div class="logo-content mt-3">
                                                                    <a href="{{ $logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}"
                                                                        target="_blank">
                                                                        <img src="{{ $logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') . '?timestamp='. time() }}"
                                                                            width="50px" height="50px"
                                                                            class=" img_setting favicon" id="faviCon">
                                                                    </a>
                                                                    {{--  <a href="{{$logo.(isset($logo) && !empty($logo)? $logo :'favicon.png')}}" target="_blank">
                                                                        <img alt="your image" src="{{$logo.'favicon.png'}}"   width="50px" height="50px" class=" img_setting favicon" id="faviCon">
                                                                    </a>  --}}
                                                                </div>
                                                                <div class="choose-files mt-5">
                                                                    <label for="company_favicon">
                                                                        <div class=" bg-primary company_favicon_update"> <i
                                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                        </div>
                                                                        <input type="file" class="form-control file"
                                                                            id="company_favicon" name="favicon"
                                                                            data-filename="company_favicon_update"
                                                                            onchange=" document.getElementById('faviCon').src = window.URL.createObjectURL(this.files[0])">
                                                                    </label>
                                                                </div>
                                                                @error('logo')
                                                                    <div class="row">
                                                                        <span class="invalid-logo" role="alert">
                                                                            <strong
                                                                                class="text-danger">{{ $message }}</strong>
                                                                        </span>
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    {{ Form::label('title_text', __('Title Text'), ['class' => 'form-label']) }}
                                                    {{ Form::text('title_text', null, ['class' => 'form-control', 'placeholder' => __('Title Text')]) }}
                                                    @error('title_text')
                                                        <span class="invalid-title_text" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('footer_text', __('Footer Text'), ['class' => 'form-label']) }}
                                                    {{ Form::text('footer_text', null, ['class' => 'form-control', 'placeholder' => __('Footer Text')]) }}
                                                    @error('footer_text')
                                                        <span class="invalid-footer_text" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="site_date_format"
                                                        class="form-label">{{ __('Date Format') }}</label>
                                                    <select type="text" name="site_date_format" class="form-control"
                                                        data-toggle="select" id="site_date_format">
                                                        <option value="M j, Y"
                                                            @if (@$settings['site_date_format'] == 'M j, Y') selected="selected" @endif>
                                                            Jan 1,2015</option>
                                                        <option value="d-m-Y"
                                                            @if (@$settings['site_date_format'] == 'd-m-Y') selected="selected" @endif>
                                                            d-m-y</option>
                                                        <option value="m-d-Y"
                                                            @if (@$settings['site_date_format'] == 'm-d-Y') selected="selected" @endif>
                                                            m-d-y</option>
                                                        <option value="Y-m-d"
                                                            @if (@$settings['site_date_format'] == 'Y-m-d') selected="selected" @endif>
                                                            y-m-d</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="site_time_format"
                                                        class="form-label">{{ __('Time Format') }}</label>
                                                    <select type="text" name="site_time_format" class="form-control"
                                                        data-toggle="select" id="site_time_format">
                                                        <option value="g:i A"
                                                            @if (@$settings['site_time_format'] == 'g:i A') selected="selected" @endif>
                                                            10:30 PM</option>
                                                        <option value="g:i a"
                                                            @if (@$settings['site_time_format'] == 'g:i a') selected="selected" @endif>
                                                            10:30 pm</option>
                                                        <option value="H:i"
                                                            @if (@$settings['site_time_format'] == 'H:i') selected="selected" @endif>
                                                            22:30</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('timezone',__('Timezone'),array('class' => 'form-control-label'))}}
                                                    <select type="text" name="timezone" class="form-control custom-select" id="timezone">
                                                        <option value="">{{__('Select Timezone')}}</option>
                                                        @foreach($timezones as $k=>$timezone)
                                                            <option value="{{$k}}" {{($settings['timezone']==$k)?'selected':''}}>{{$timezone}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group col-6 col-md-3">
                                                    <div class="custom-control form-switch p-0">
                                                        <label class="form-check-label"
                                                            for="SITE_RTL">{{ __('Enable RTL') }}</label><br>
                                                        <input type="checkbox" class="form-check-input"
                                                            data-toggle="switchbutton" data-onstyle="primary" name="SITE_RTL"
                                                            id="SITE_RTL"
                                                            {{ $settings['SITE_RTL'] == 'on' ? 'checked="checked"' : '' }}>
                                                    </div>
                                                </div>
                                                <div class="setting-card setting-logo-box p-3">
                                                    <div class="row">
                                                        <h5>{{ __('Theme Customizer') }}</h5>
                                                        <div class="col-md-4 my-auto">
                                                            <h6 class="mt-2">
                                                                <i data-feather="credit-card"
                                                                    class="me-2"></i>{{ __('Primary Color Settings') }}
                                                            </h6>
                                                            <hr class="my-2" />
                                                            
                                                            <div class="color-wrp">
                                                                <div class="theme-color themes-color">
                                                                    <a href="#!" class="themes-color-change {{ $color == 'theme-1' ? 'active_color' : '' }}" data-value="theme-1"></a>
                                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-1"{{ $color == 'theme-1' ? 'checked' : '' }}>
                                                                    <a href="#!" class="themes-color-change {{ $color == 'theme-2' ? 'active_color' : '' }}" data-value="theme-2"></a>
                                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-2"{{ $color == 'theme-2' ? 'checked' : '' }}>
                                                                    <a href="#!" class="themes-color-change {{ $color == 'theme-3' ? 'active_color' : '' }}" data-value="theme-3"></a>
                                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-3"{{ $color == 'theme-3' ? 'checked' : '' }}>
                                                                    <a href="#!" class="themes-color-change {{ $color == 'theme-4' ? 'active_color' : '' }}" data-value="theme-4"></a>
                                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-4"{{ $color == 'theme-4' ? 'checked' : '' }}>
                                                                    <a href="#!" class="themes-color-change {{ $color == 'theme-5' ? 'active_color' : '' }}" data-value="theme-5"></a>
                                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-5"{{ $color == 'theme-5' ? 'checked' : '' }}>
                                                                    <br>
                                                                    <a href="#!" class="themes-color-change {{ $color == 'theme-6' ? 'active_color' : '' }}" data-value="theme-6"></a>
                                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-6"{{ $color == 'theme-6' ? 'checked' : '' }}>
                                                                    <a href="#!" class="themes-color-change {{ $color == 'theme-7' ? 'active_color' : '' }}" data-value="theme-7"></a>
                                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-7"{{ $color == 'theme-7' ? 'checked' : '' }}>
                                                                    <a href="#!" class="themes-color-change {{ $color == 'theme-8' ? 'active_color' : '' }}" data-value="theme-8"></a>
                                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-8"{{ $color == 'theme-8' ? 'checked' : '' }}>
                                                                    <a href="#!" class="themes-color-change {{ $color == 'theme-9' ? 'active_color' : '' }}" data-value="theme-9"></a>
                                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-9"{{ $color == 'theme-9' ? 'checked' : '' }}>
                                                                    <a href="#!" class="themes-color-change {{ $color == 'theme-10' ? 'active_color' : '' }}" data-value="theme-10"></a>
                                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-10"{{ $color == 'theme-10' ? 'checked' : '' }}>
                                                                </div>
                                                                <div class="color-picker-wrp ">
                                                                        <input type="color" value="{{ $color ? $color : '' }}" class="colorPicker {{ isset($flag) && $flag == 'true' ? 'active_color' : '' }}" name="custom_color" id="color-picker">                                             
                                                                        <input type='hidden' name="color_flag" value = {{  isset($flag) && $flag == 'true' ? 'true' : 'false' }}>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 my-auto mt-2">
                                                            <h6 class="">
                                                                <i data-feather="layout"
                                                                    class="me-2"></i>{{ __('Sidebar Settings') }}
                                                            </h6>
                                                            <hr class="my-2" />
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="cust-theme-bg" name="cust_theme_bg" 
                                                                    {{ Utility::getValByName('cust_theme_bg') == 'on' ? 'checked' : '' }} />
                                                                <label class="form-check-label f-w-600 pl-1" 
                                                                    for="cust-theme-bg">{{ __('Transparent layout') }}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 my-auto mt-2">
                                                            <h6 class="">
                                                                <i data-feather="sun"
                                                                    class="me-2"></i>{{ __('Layout Settings') }}
                                                            </h6>
                                                            <hr class="my-2" />
                                                            <div class="form-check form-switch mt-2">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="cust-darklayout" name="cust_darklayout"
                                                                    {{ $settings['cust_darklayout'] == 'on' ? 'checked' : '' }} />
                                                                <label class="form-check-label f-w-600 pl-1"
                                                                    for="cust-darklayout">{{ __('Dark Layout') }}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer ">
                                            <div class="col-sm-12 px-2">
                                                <div class="text-end">
                                                    {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                    <!--       here -->
                    <div class="tab-pane fade" id="pills-store_setting" role="tabpanel" aria-labelledby="pills-brand_setting-tab">
                        {{ Form::model($store_settings, ['route' => ['settings.store', $store_settings['id']], 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <div class="col-lg-12 col-sm-12 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex flex-wrap justify-content-between">
                                            <h5>{{ __('Store Settings') }}</h5>
                                            @if($plan->enable_chatgpt == 'on')
                                                <a href="#" class="btn btn-primary btn-sm float-end" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['meta']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
                                                    <i class="fas fa-robot"></i> {{ __('Generate with AI') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class=" setting-card">
                                            <div class="row mt-2">
                                                <div class="col-lg-4 col-sm-6 col-md-6">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h5>{{ __('Store Logo') }}</h5>
                                                        </div>
                                                        <div class="card-body pt-0">
                                                            <div class=" setting-card">
                                                                <div class="logo-content mt-3">
                                                                    {{-- <a href="{{ $store_logo . '/' . (isset($store_settings['logo']) && !empty($store_settings['logo']) ? $store_settings['logo'] : 'logo.png') }}"
                                                                    target="_blank">
                                                                    <img src="{{ $store_logo . '/' . (isset($store_settings['logo']) && !empty($store_settings['logo']) ? $store_settings['logo'] : 'logo.png') }}"
                                                                        class="big-logo invoice_logo img_setting"
                                                                        id="storeLogo">
                                                                </a> --}}
                                                                    <a href="{{ $s_logo . (isset($store_settings['logo']) && !empty($store_settings['logo']) ? $store_settings['logo'] : 'logo.png') }}"
                                                                        target="_blank">
                                                                        <img id="StorelogoOwner" alt="your image"
                                                                            src="{{ $s_logo . (isset($store_settings['logo']) && !empty($store_settings['logo']) ? $store_settings['logo'] : 'logo.png') . '?timestamp='. time() }}"
                                                                            class="big-logo invoice_logo img_setting"
                                                                            id="storeLogo">
                                                                    </a>
                                                                </div>
                                                                <div class="choose-files mt-4">
                                                                    <label for="logo">
                                                                        <div class=" bg-primary logo_update"> <i
                                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                        </div>
                                                                        <input type="file" class="form-control file"
                                                                            name="logo" id="logo"
                                                                            data-filename="logo_update"
                                                                            onchange="document.getElementById('storeLogo').src = window.URL.createObjectURL(this.files[0])">
                                                                    </label>
                                                                </div>
                                                                @error('logo')
                                                                    <div class="row">
                                                                        <span class="invalid-logo" role="alert">
                                                                            <strong
                                                                                class="text-danger">{{ $message }}</strong>
                                                                        </span>
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6 col-md-6">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h5>{{ __('Invoice Logo') }}</h5>
                                                        </div>
                                                        <div class="card-body pt-0">
                                                            <div class=" setting-card">
                                                                <div class="logo-content mt-3">
                                                                    {{-- <a href="{{ $store_logo . '/' . (isset($store_settings['invoice_logo']) && !empty($store_settings['invoice_logo']) ? $store_settings['invoice_logo'] : 'invoice_logo.png') }}"
                                                                    target="_blank">
                                                                    <img src="{{ $store_logo . '/' . (isset($store_settings['invoice_logo']) && !empty($store_settings['invoice_logo']) ? $store_settings['invoice_logo'] : 'invoice_logo.png') }}"
                                                                        class="big-logo invoice_logo img_setting"
                                                                        id="invoiceLogo">
                                                                </a> --}}
                                                                    <a href="{{ $s_logo . (isset($store_settings['invoice_logo']) && !empty($store_settings['invoice_logo']) ? $store_settings['invoice_logo'] : 'invoice_logo.png') }}"
                                                                        target="_blank">
                                                                        <img id="invoiceOwner" alt="your image"
                                                                            src="{{ $s_logo . (isset($store_settings['invoice_logo']) && !empty($store_settings['invoice_logo']) ? $store_settings['invoice_logo'] : 'invoice_logo.png') . '?timestamp='. time() }}"
                                                                            width="150px"
                                                                            class="big-logo invoice_logo img_setting"
                                                                            id="invoiceLogo">
                                                                    </a>
                                                                </div>
                                                                <div class="choose-files mt-4">
                                                                    <label for="invoice_logo">
                                                                        <div class=" bg-primary logo_update"> <i
                                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                        </div>
                                                                        <input type="file" name="invoice_logo"
                                                                            id="invoice_logo" class="form-control file"
                                                                            data-filename="invoice_logo_update"
                                                                            onchange="document.getElementById('invoiceLogo').src = window.URL.createObjectURL(this.files[0])">
                                                                    </label>
                                                                </div>
                                                                @error('invoice_logo')
                                                                    <div class="row">
                                                                        <span class="invalid-invoice_logo" role="alert">
                                                                            <strong
                                                                                class="text-danger">{{ $message }}</strong>
                                                                        </span>
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    {{ Form::label('store_name', __('Store Name'), ['class' => 'form-label']) }}
                                                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Store Name')]) !!}
                                                    @error('store_name')
                                                        <span class="invalid-store_name" role="alert">
                                                            <strong class="text-danger">
                                                                {{ $message }}
                                                            </strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
                                                    {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => __('Email')]) }}
                                                    @error('email')
                                                        <span class="invalid-email" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('commercial_register', __('commercial_register'), ['class' => 'form-label']) }}
                                                    {{ Form::text('commercial_register', null, ['class' => 'form-control', 'placeholder' => __('commercial_register')]) }}
                                                    @error('commercial_register')
                                                        <span class="invalid-email" role="alert">
                                                            <strong class="text-danger">{{ $message }}</stronAg>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('limit_inventory', __('limit_inventory'), ['class' => 'form-label']) }}
                                                    {{ Form::number('limit_inventory', isset($store_settings['limit_inventory']) ? $store_settings['limit_inventory'] : 2, ['class' => 'form-control', 'placeholder' => __('limit_inventory')]) }}
                                                    @error('limit_inventory')
                                                        <span class="invalid-email" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{ Form::label('tax_number', __('tax_number'), ['class' => 'form-label']) }}
                                                    {{ Form::text('tax_number', null, ['class' => 'form-control', 'placeholder' => __('tax_number')]) }}
                                                    @error('tax_number')
                                                        <span class="invalid-email" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                @if ($plan->enable_custdomain == 'on' || $plan->enable_custsubdomain == 'on')
                                                    <div class="col-md-6 py-4">
                                                        <div class="radio-button-group row gy-2 mts">
                                                            <div class="col-sm-4">
                                                                <label
                                                                    class="btn btn-outline-primary w-100 {{ $store_settings['enable_storelink'] == 'on' ? 'active' : '' }}">
                                                                    <input type="radio"
                                                                        class="domain_click  radio-button"
                                                                        name="enable_domain" value="enable_storelink"
                                                                        id="enable_storelink"
                                                                        {{ $store_settings['enable_storelink'] == 'on' ? 'checked' : '' }}>
                                                                    {{ __('Store Link') }}
                                                                </label>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                @if ($plan->enable_custdomain == 'on')
                                                                    <label
                                                                        class="btn btn-outline-primary w-100 {{ $store_settings['enable_domain'] == 'on' ? 'active' : '' }}">
                                                                        <input type="radio"
                                                                            class="domain_click radio-button"
                                                                            name="enable_domain" value="enable_domain"
                                                                            id="enable_domain"
                                                                            {{ $store_settings['enable_domain'] == 'on' ? 'checked' : '' }}>
                                                                        {{ __('Domain') }}
                                                                    </label>
                                                                @endif
                                                            </div>
                                                            <div class="col-sm-4">
                                                                @if ($plan->enable_custsubdomain == 'on')
                                                                    <label
                                                                        class="btn btn-outline-primary w-100 {{ $store_settings['enable_subdomain'] == 'on' ? 'active' : '' }}">
                                                                        <input type="radio"
                                                                            class="domain_click radio-button"
                                                                            name="enable_domain" value="enable_subdomain"
                                                                            id="enable_subdomain"
                                                                            {{ $store_settings['enable_subdomain'] == 'on' ? 'checked' : '' }}>
                                                                        {{ __('Sub Domain') }}
                                                                    </label>
                                                            @endif
                                                            </div>
                                                            {{-- </div> --}}
                                                        </div>
                                                        {{--  new added  --}}
                                                        @if ($domainPointing == 1)
                                                            <div class="text-sm mt-2" id="domainnote"
                                                                style="{{ $store_settings['enable_domain'] == 'on' ? 'display: block' : 'display: none' }}">
                                                                <span><b class="text-success">{{ __('Note : Before add Custom Domain, your domain A record is pointing to our server IP :') }}{{ $serverIp }}|
                                                                        {{ __('Your Custom Domain IP Is This: ') }}{{ $domainip }}</b></span>
                                                            </div>
                                                        @else
                                                            <div class="text-sm mt-2" id="domainnote"
                                                                style="{{ $store_settings['enable_domain'] == 'on' ? 'display: block' : 'display: none' }}">
                                                                <span><b>{{ __('Note : Before add Custom Domain, your domain A record is pointing to our server IP :') }}{{ $serverIp }}|</b>
                                                                    <b
                                                                        class="text-danger">{{ __('Your Custom Domain IP Is This: ') }}{{ $domainip }}</b></span>
                                                            </div>
                                                        @endif
                                                        @if ($subdomainPointing == 1)
                                                            <div class="text-sm mt-2" id="subdomainnote"
                                                                style="{{ $store_settings['enable_subdomain'] == 'on' ? 'display: block' : 'display: none' }}">
                                                                <span><b class="text-success">{{ __('Note : Before add Sub Domain, your domain A record is pointing to our server IP :') }}{{ $serverIp }}|
                                                                    {{ __('Your Sub Domain IP Is This: ') }}{{ $domainip }}</b></span>
                                                            </div>
                                                        @else
                                                            <div class="text-sm mt-2" id="subdomainnote"
                                                                style="{{ $store_settings['enable_subdomain'] == 'on' ? 'display: block' : 'display: none' }}">
                                                                <span><b>{{ __('Note : Before add Sub Domain, your domain A record is pointing to our server IP :') }}{{ $serverIp }}|</b>
                                                                    <b
                                                                        class="text-danger">{{ __('Your Sub Domain IP Is This: ') }}{{ $domainip }}</b></span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="form-group col-md-6" id="StoreLink"
                                                        style="{{ $store_settings['enable_storelink'] == 'on' ? 'display: block' : 'display: none' }}">
                                                        {{ Form::label('store_link', __('Store Link'), ['class' => 'form-label']) }}
                                                        <div class="input-group">
                                                            <input type="text"
                                                                value="{{ $store_settings['store_url'] }}"
                                                                id="myInput" class="form-control d-inline-block"
                                                                aria-label="Recipient's username"
                                                                aria-describedby="button-addon2" readonly>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-outline-primary" type="button"
                                                                    onclick="myFunction()" id="button-addon2"><i
                                                                        class="far fa-copy"></i>
                                                                    {{ __('Copy Link') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 domain"
                                                        style="{{ $store_settings['enable_domain'] == 'on' ? 'display:block' : 'display:none' }}">
                                                        <div class="row">
                                                            <div class="form-group col-md-3  mb-0">
                                                                {{ Form::label('domain_switch', __('Custom Domain'), ['class' => 'form-label']) }}
                                                                <div class="form-check form-switch custom-switch-v1 mt-1">
                                                                    <input type="hidden" name="domain_switch"
                                                                        value="off">
                                                                    <input type="checkbox" class="form-check-input"
                                                                        name="domain_switch" id="domain_switch"
                                                                        {{ isset($store_settings['domain_switch']) && $store_settings['domain_switch'] == 'on' ? 'checked="checked"' : '' }}>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-9 domain_text mb-0" style="{{ $store_settings['domain_switch'] == 'on' ? '' : 'display:none' }}">
                                                                {{ Form::label('store_domain', __('Custom Domain'), ['class' => 'form-label']) }}
                                                                {{ Form::text('domains', $store_settings['domains'], ['class' => 'form-control', 'placeholder' => __('xyz.com')]) }}
                                                                <span class="text-muted text-end request_msg">{{ $request_msg }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if ($plan->enable_custsubdomain == 'on')
                                                        <div class="form-group col-md-6 sundomain"
                                                            style="{{ $store_settings['enable_subdomain'] == 'on' ? 'display:block' : 'display:none' }}">
                                                            {{ Form::label('store_subdomain', __('Sub Domain'), ['class' => 'form-label']) }}
                                                            <div class="input-group">
                                                                {{ Form::text('subdomain', $store_settings['slug'], ['class' => 'form-control', 'placeholder' => __('Enter Domain'), 'readonly']) }}
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text"
                                                                        id="basic-addon2">.{{ $subdomain_name }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="form-group col-md-6" id="StoreLink">
                                                        {{ Form::label('store_link', __('Store Link'), ['class' => 'form-label']) }}
                                                        <div class="input-group">
                                                            <input type="text"
                                                                value="{{ $store_settings['store_url'] }}"
                                                                id="myInput" class="form-control d-inline-block"
                                                                aria-label="Recipient's username"
                                                                aria-describedby="button-addon2" readonly>
                                                            <div class="input-group-append">
                                                                <button class="btn btn-outline-primary" type="button"
                                                                    onclick="myFunction()" id="button-addon2"><i
                                                                        class="far fa-copy"></i>
                                                                    {{ __('Copy Link') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="form-group col-md-4">
                                                    {{ Form::label('tagline', __('Tagline'), ['class' => 'form-label']) }}
                                                    {{ Form::text('tagline', null, ['class' => 'form-control', 'placeholder' => __('Tagline')]) }}
                                                    @error('tagline')
                                                        <span class="invalid-tagline" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-4">
                                                    {{ Form::label('address', __('Address'), ['class' => 'form-label']) }}
                                                    {{ Form::text('address', null, ['class' => 'form-control', 'placeholder' => __('Address')]) }}
                                                    @error('address')
                                                        <span class="invalid-address" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-4">
                                                    {{ Form::label('city', __('City'), ['class' => 'form-label']) }}
                                                    {{ Form::text('city', null, ['class' => 'form-control', 'placeholder' => __('City')]) }}
                                                    @error('city')
                                                        <span class="invalid-city" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-4">
                                                    {{ Form::label('state', __('State'), ['class' => 'form-label']) }}
                                                    {{ Form::text('state', null, ['class' => 'form-control', 'placeholder' => __('State')]) }}
                                                    @error('state')
                                                        <span class="invalid-state" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-4">
                                                    {{ Form::label('zipcode', __('Zipcode'), ['class' => 'form-label']) }}
                                                    {{ Form::text('zipcode', null, ['class' => 'form-control', 'placeholder' => __('Zipcode')]) }}
                                                    @error('zipcode')
                                                        <span class="invalid-zipcode" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-4">
                                                    {{ Form::label('country', __('Country'), ['class' => 'form-label']) }}
                                                    {{ Form::text('country', null, ['class' => 'form-control', 'placeholder' => __('Country')]) }}
                                                    @error('country')
                                                        <span class="invalid-country" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-4">
                                                    {{ Form::label('store_default_language', __('Store Default Language'), ['class' => 'form-label']) }}
                                                    <div class="changeLanguage">
                                                        <select name="store_default_language" id="store_default_language"
                                                            class="form-control" data-toggle="select">
                                                            @foreach ($languages as $code => $language)
                                                                <option @if ($store_lang == $code) selected @endif
                                                                    value="{{ $code }}">
                                                                    {{ ucFirst($language) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    {{ Form::label('decimal_number_format', __('Decimal Number Format'), ['class' => 'form-label']) }}
                                                    {{ Form::number('decimal_number', isset($store_settings['decimal_number']) ? $store_settings['decimal_number'] : 2, ['class' => 'form-control', 'placeholder' => __('decimal_number')]) }}
                                                    @error('decimal_number')
                                                        <span class="invalid-decimal_number" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-4 mt-3">
                                                    <label class="form-check-label"
                                                        for="is_checkout_login_required"></label>
                                                    <div class="custom-control form-switch">
                                                        <input type="checkbox" class="form-check-input"
                                                            name="is_checkout_login_required"
                                                            id="is_checkout_login_required"
                                                            @if ($store_settings['is_checkout_login_required'] == null) @if ($settings['is_checkout_login_required'] == 'on')
                                                                {{ 'checked=checked' }} @endif
                                                        @elseif($store_settings['is_checkout_login_required'] == 'on') {{ 'checked=checked' }}
                                                        @else {{ '' }} @endif
                                                        {{-- {{ $store_settings['is_checkout_login_required'] == 'on' ? 'checked=checked' : '' }} --}}
                                                        >
                                                        {{ Form::label('is_checkout_login_required', __('Is Checkout Login Required'), ['class' => 'form-check-label mb-3']) }}
                                                    </div>
                                                </div>
                                                @if ($plan->blog == 'on')
                                                    <div class="form-group col-md-4">
                                                        <label class="form-check-label" for="blog_enable"></label>
                                                        <div class="custom-control form-switch">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="blog_enable" id="blog_enable"
                                                                {{ $store_settings['blog_enable'] == 'on' ? 'checked=checked' : '' }}>
                                                            {{ Form::label('blog_enable', __('Blog Menu Dispay'), ['class' => 'form-check-label mb-3']) }}
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($plan->shipping_method == 'on')
                                                    <div class="form-group col-md-4">
                                                        <label class="form-check-label" for="enable_shipping"></label>
                                                        <div class="custom-control form-switch">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="enable_shipping" id="enable_shipping"
                                                                {{ $store_settings['enable_shipping'] == 'on' ? 'checked=checked' : '' }}>
                                                            {{ Form::label('enable_shipping', __('Shipping Method Enable'), ['class' => 'form-check-label mb-3']) }}
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="form-group col-md-4 ">
                                                    <label class="form-check-label" for="enable_rating"></label>
                                                    <div class="custom-control form-switch">
                                                        <input type="checkbox" class="form-check-input"
                                                            name="enable_rating" id="enable_rating"
                                                            {{ $store_settings['enable_rating'] == 'on' ? 'checked=checked' : '' }}>
                                                        {{ Form::label('enable_rating', __('Product Rating Display'), ['class' => 'form-check-label mb-3']) }}
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <i class="fab fa-google" aria-hidden="true"></i>
                                                        {{ Form::label('google_analytic', __('Google Analytic'), ['class' => 'form-label']) }}
                                                        {{ Form::text('google_analytic', null, ['class' => 'form-control', 'placeholder' => 'UA-XXXXXXXXX-X']) }}
                                                        @error('google_analytic')
                                                            <span class="invalid-google_analytic" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                                        {{ Form::label('facebook_pixel_code', __('Facebook Pixel'), ['class' => 'form-label']) }}
                                                        {{ Form::text('fbpixel_code', null, ['class' => 'form-control', 'placeholder' => 'UA-0000000-0']) }}
                                                        @error('facebook_pixel_code')
                                                            <span class="invalid-google_analytic" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    {{ Form::label('storejs', __('Store Custom JS'), ['class' => 'form-label']) }}
                                                    {{ Form::textarea('storejs', null, ['class' => 'form-control', 'rows' => 3, 'placehold   er' => __('About')]) }}
                                                    @error('storejs')
                                                        <span class="invalid-about" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{ Form::label('metakeyword', __('Meta Keywords'), ['class' => 'form-label']) }}
                                                        {!! Form::text('metakeyword', null, [
                                                            'class' => 'form-control',
                                                            'rows' => 3,
                                                            'placeholder' => __('Meta Keyword'),
                                                        ]) !!}
                                                        @error('meta_keywords')
                                                            <span class="invalid-about" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        {{ Form::label('metadesc', __('Meta Description'), ['class' => 'form-label']) }}
                                                        {!! Form::textarea('metadesc', null, [
                                                            'class' => 'form-control',
                                                            'rows' => 3,
                                                            'placeholder' => __('Meta Description'),
                                                        ]) !!}
    
                                                        @error('meta_description')
                                                            <span class="invalid-about" role="alert">
                                                                <strong class="text-danger">{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    
                                                    <div class="form-group pt-0">
                                                        <div class=" setting-card">
                                                            <label for="" class="form-label">{{ __('Meta Image') }}</label>
                                                            <div class="logo-content mt-4">
                                                                
                                                                <a href="{{$metaimage.(isset($store_settings->metaimage) && !empty($store_settings->metaimage)? $store_settings->metaimage:'default.png')}}" target="_blank">
                                                                    <img id="meta_image" alt="your image" src="{{$metaimage.(isset($store_settings->metaimage) && !empty($store_settings->metaimage)? $store_settings->metaimage:'default.png')}}" width="150px" class="img_setting">
                                                                </a>
                                                            </div>
                                                            <div class="choose-files mt-5">
                                                                <label for="metaimage">
                                                                    <div class=" bg-primary full_logo"> <i
                                                                            class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                    </div>
                                                                    <input type="file" name="metaimage"
                                                                        id="metaimage" class="form-control file"
                                                                        data-filename="metaimage"
                                                                        onchange="document.getElementById('meta_image').src = window.URL.createObjectURL(this.files[0])">
                                                                </label>
                                                            </div>
                                                            @error('metaimage')
                                                                <div class="row">
                                                                    <span class="invalid-logo" role="alert">
                                                                        <strong
                                                                            class="text-danger">{{ $message }}</strong>
                                                                    </span>
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="col-sm-12 px-2">
                                            <div class="text-end">
                                                <button type="button" class="btn bs-pass-para btn-secondary btn-light"
                                                    data-title="{{ __('Delete') }}"
                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                    data-confirm-yes="delete-form-{{ $store_settings->id }}">
                                                    <span class="text-black">{{ __('Delete Store') }}</span>
                                                </button>
                                                {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                        {!! Form::open([
                            'method' => 'DELETE',
                            'route' => ['ownerstore.destroy', $store_settings->id],
                            'id' => 'delete-form-' . $store_settings->id,
                        ]) !!}
                        {!! Form::close() !!}
                    </div>
                    <div class="tab-pane fade" id="pills-store_payment-setting" role="tabpanel" aria-labelledby="pills-brand_setting-tab">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ 'Payment Settings' }}</h5>
                                <small
                                class="text-dark font-weight-bold">{{ __('These details will be used to collect invoice payments. Each invoice will have a payment button based on the below configuration.') }}</small>
                            </div>
                            <div class="card-body">

                                {{ Form::open(['route' => ['owner.payment.setting', $store_settings->slug], 'method' => 'post', 'novalidate']) }}
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                                <label class="col-form-label">{{ __('Currency') }}</label>
                                                <input type="text" name="currency" class="form-control"
                                                    id="currency" value="{{ $store_settings['currency_code'] }}"
                                                    required>
                                                <small class="text-xs">
                                                    {{ __('Note: Add currency code as per three-letter ISO code') }}.
                                                    <a href="https://stripe.com/docs/currencies"
                                                        target="_blank">{{ __('You can find out how to do that here..') }}</a>
                                                        {{__(' and this value will be automatically assigned whenever a new store is created.')}}
                                                </small>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                                <label for="currency_symbol"
                                                    class="col-form-label">{{ __('Currency Symbol') }}</label>
                                                <input type="text" name="currency_symbol" class="form-control"
                                                    id="currency_symbol" value="{{ $store_settings['currency'] }}"
                                                    required>
                                            </div>

                                        </div>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-control-label mb-3"
                                                                for="example3cols3Input">{{ __('Currency Symbol Position') }}</label>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-check form-check-inline mb-3">
                                                                        <input type="radio" id="customRadio5"
                                                                            name="currency_symbol_position" value="pre"
                                                                            class="form-check-input"
                                                                            @if ($store_settings['currency_symbol_position'] == 'pre' || $store_settings['currency_symbol_position'] == null) checked @endif>
                                                                        <label class="form-check-label"
                                                                            for="customRadio5">{{ __('Pre') }}</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-check form-check-inline mb-3">
                                                                        <input type="radio" id="customRadio6"
                                                                            name="currency_symbol_position" value="post"
                                                                            class="form-check-input"
                                                                            @if ($store_settings['currency_symbol_position'] == 'post') checked @endif>
                                                                        <label class="form-check-label"
                                                                            for="customRadio6">{{ __('Post') }}</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-control-label mb-3"
                                                                for="example3cols3Input">{{ __('Currency Symbol Space') }}</label>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-check form-check-inline mb-3">
                                                                        <input type="radio" id="customRadio7"
                                                                            name="currency_symbol_space" value="with"
                                                                            class="form-check-input"
                                                                            @if ($store_settings['currency_symbol_space'] == 'with') checked @endif>
                                                                        <label class="form-check-label"
                                                                            for="customRadio7">{{ __('With Space') }}</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-check form-check-inline mb-3">
                                                                        <input type="radio" id="customRadio8"
                                                                            name="currency_symbol_space" value="without"
                                                                            class="form-check-input"
                                                                            @if ($store_settings['currency_symbol_space'] == 'without' || $store_settings['currency_symbol_space'] == null) checked @endif>
                                                                        <label class="form-check-label"
                                                                            for="customRadio8">{{ __('Without Space') }}</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h6>{{ __('Custom Field For Checkout') }}</h6>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('custom_field_title_1', __('Custom Field Title'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('custom_field_title_1', !empty($store_payment_setting['custom_field_title_1']) ? $store_payment_setting['custom_field_title_1'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Custom Field Title')]) }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('custom_field_title_2', __('Custom Field Title'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('custom_field_title_2', !empty($store_payment_setting['custom_field_title_2']) ? $store_payment_setting['custom_field_title_2'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Custom Field Title')]) }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('custom_field_title_3', __('Custom Field Title'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('custom_field_title_3', !empty($store_payment_setting['custom_field_title_3']) ? $store_payment_setting['custom_field_title_3'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Custom Field Title')]) }}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('custom_field_title_4', __('Custom Field Title'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('custom_field_title_4', !empty($store_payment_setting['custom_field_title_4']) ? $store_payment_setting['custom_field_title_4'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Custom Field Title')]) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              
                                <div class="row">
                                    <div class="faq col-12">
                                        <div class="accordion accordion-flush setting-accordion"
                                            id="accordionExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingFourteen">
                                                    <button class="accordion-button collapsed"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseFourteen"
                                                        aria-expanded="false"
                                                        aria-controls="collapseFourteen">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('COD') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('On/Off') }}:</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="enable_cod" value="off">
                                                                <input type="checkbox" class="form-check-input input-primary" name="enable_cod" id="enable_cod" {{ $store_settings['enable_cod'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="enable_cod"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseFourteen"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="headingFourteen"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row gy-4">
                                                            
                                                            <div class="col-6 py-2">
                                                                <small>
                                                                    {{ __('Note : Enable or disable cash on delivery.') }}</small><br>
                                                                <small>
                                                                    {{ __('This detail will use for make checkout of shopping cart.') }}</small>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingFifteen">
                                                    <button class="accordion-button collapsed"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseFifteen"
                                                        aria-expanded="false"
                                                        aria-controls="collapseFifteen">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Telegram') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('On/Off') }}:</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="enable_telegram" value="off">
                                                                <input type="checkbox" class="form-check-input input-primary" name="enable_telegram" id="enable_telegram" {{ $store_settings['enable_telegram'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="enable_telegram"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseFifteen"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="headingFifteen"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row gy-4">
                                                            <div class="col-12 py-2">
                                                                <small>
                                                                    {{ __('Note: This detail will use for make checkout of shopping cart.') }}</small>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('telegrambot', __('Telegram Access Token'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('telegrambot', $store_settings['telegrambot'], ['class' => 'form-control active telegrambot', 'placeholder' => '1234567890:AAbbbbccccddddxvGENZCi8Hd4B15M8xHV0']) }}
                                                                    <p>{{ __('Get Chat ID') }} :
                                                                        https://api.telegram.org/bot-TOKEN-/getUpdates</p>

                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('telegramchatid', __('Telegram Chat Id'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('telegramchatid', $store_settings['telegramchatid'], ['class' => 'form-control active telegramchatid', 'placeholder' => '123456789']) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingSixteen">
                                                    <button class="accordion-button collapsed"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseSixteen"
                                                        aria-expanded="false"
                                                        aria-controls="collapseSixteen">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Whatsapp') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('On/Off') }}:</span>
                                                            
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="enable_whatsapp" value="off">
                                                                <input type="checkbox" class="form-check-input input-primary" name="enable_whatsapp" id="enable_whatsapp" {{ $store_settings['enable_whatsapp'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="enable_whatsapp"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseSixteen"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="headingSixteen"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row gy-4">
                                                            <div class="col-12 py-2">
                                                                <small>
                                                                    {{ __('Note: This detail will use for make checkout of shopping cart.') }}</small>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <input type="text" name="whatsapp_number"
                                                                        id="whatsapp_number"
                                                                        class="form-control input-mask"
                                                                        data-mask="+00 00000000000"
                                                                        value="{{ $store_settings['whatsapp_number'] }}"
                                                                        placeholder="+00 00000000000" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingSeventeen">
                                                    <button class="accordion-button collapsed"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseSeventeen"
                                                        aria-expanded="false"
                                                        aria-controls="collapseSeventeen">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Bank Transfer') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('On/Off') }}:</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="enable_bank" value="off">
                                                                <input type="checkbox" class="form-check-input input-primary" name="enable_bank" id="enable_bank" {{ $store_settings['enable_bank'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="enable_bank"></label>
                                                            </div>
                                                        </div>
                                                       
                                                    </button>
                                                </h2>
                                                <div id="collapseSeventeen"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="headingSeventeen"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row gy-4">
                                                            <div class="col-6 py-2">
                                                                <small>
                                                                    {{ __('Note: Input your bank details including bank name.') }}</small>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <textarea type="text" name="bank_number" id="bank_number" class="form-control" placeholder="{{ __('Bank Transfer Number') }}">{{ $store_settings['bank_number'] }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button collapsed"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseOne"
                                                        aria-expanded="false"
                                                        aria-controls="collapseOne">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Stripe') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('On/Off') }}:</span>
                                                            <div
                                                                class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_stripe_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input input-primary" name="is_stripe_enabled" id="is_stripe_enabled" {{ isset($store_payment_setting['is_stripe_enabled']) && $store_payment_setting['is_stripe_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="is_stripe_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseOne"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="headingOne"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row gy-4">
                                                            
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('stripe_key', __('Stripe Key'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('stripe_key', isset($store_payment_setting['stripe_key']) ? $store_payment_setting['stripe_key'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Stripe Key')]) }}
                                                                    @error('stripe_key')
                                                                        <span class="invalid-stripe_key" role="alert">
                                                                            <strong
                                                                                class="text-danger">{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('stripe_secret', __('Stripe Secret'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('stripe_secret', isset($store_payment_setting['stripe_secret']) ? $store_payment_setting['stripe_secret'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Stripe Secret')]) }}
                                                                    @error('stripe_secret')
                                                                        <span class="invalid-stripe_secret"
                                                                            role="alert">
                                                                            <strong
                                                                                class="text-danger">{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwo">
                                                    <button class="accordion-button collapsed"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseTwo"
                                                        aria-expanded="false"
                                                        aria-controls="collapseTwo">
                                                        <span class="d-flex align-items-center"> {{ __('Paypal') }}</span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('On/Off') }}:</span>
                                                            <div
                                                                class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_paypal_enabled" value="off">
                                                                <input type="checkbox"  name="is_paypal_enabled" id="is_paypal_enabled" class="form-check-input input-primary" {{ isset($store_payment_setting['is_paypal_enabled']) && $store_payment_setting['is_paypal_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="is_paypal_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseTwo"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="headingTwo"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="bussiness-hours">
                                                            <div class="row align-items-center gy-4">
                                                                <div class="col-lg-12">
                                                                    <label class="paypal-label col-form-label" for="paypal_mode">{{ __('Paypal Mode') }}</label>
                                                                    <br>
                                                                    <div class="d-flex flex-wrap">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="paypal_mode"
                                                                                            value="sandbox"
                                                                                            class="form-check-input"
                                                                                            {{ !isset($store_payment_setting['paypal_mode']) || $store_payment_setting['paypal_mode'] == '' || $store_payment_setting['paypal_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                        {{ __('Sandbox') }}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2 me-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="paypal_mode"
                                                                                            value="live"
                                                                                            class="form-check-input"
                                                                                            {{ isset($store_payment_setting['paypal_mode']) && $store_payment_setting['paypal_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                        {{ __('Live') }}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_client_id" class="col-form-label">{{ __('Client ID') }}</label>
                                                                        <input type="text" name="paypal_client_id"
                                                                            id="paypal_client_id" class="form-control"
                                                                            value="{{ !isset($store_payment_setting['paypal_client_id']) || is_null($store_payment_setting['paypal_client_id']) ? '' : $store_payment_setting['paypal_client_id'] }}"
                                                                            placeholder="{{ __('Client ID') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_secret_key"
                                                                            class="col-form-label">{{ __('Secret Key') }}</label>
                                                                        <input type="text" name="paypal_secret_key"
                                                                            id="paypal_secret_key" class="form-control"
                                                                            value="{{ !isset($store_payment_setting['paypal_secret_key']) || is_null($store_payment_setting['paypal_secret_key']) ? '' : $store_payment_setting['paypal_secret_key'] }}"
                                                                            placeholder="{{ __('Secret Key') }}">
                                                                    </div>  
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingThree">
                                                    <button class="accordion-button collapsed"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseThree"
                                                        aria-expanded="false"
                                                        aria-controls="collapseTwo">
                                                        <span
                                                            class="d-flex align-items-center">{{ __('Paystack') }}</span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('On/Off') }}:</span>
                                                            <div
                                                                class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_paystack_enabled" value="off">
                                                                <input type="checkbox" name="is_paystack_enabled" class="form-check-input input-primary" id="is_paystack_enabled" {{ isset($store_payment_setting['is_paystack_enabled']) && $store_payment_setting['is_paystack_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label" for="is_paystack_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseThree"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="headingTwo"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="bussiness-hours">
                                                            <div class="row align-items-center gy-4">
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_client_id" class="col-form-label">{{ __('Public Key') }}</label>
                                                                        <input type="text" name="paystack_public_key" id="paystack_public_key" class="form-control"
                                                                            value="{{ !isset($store_payment_setting['paystack_public_key']) || is_null($store_payment_setting['paystack_public_key']) ? '' : $store_payment_setting['paystack_public_key'] }}"
                                                                            placeholder="{{ __('Public Key') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="form-group">
                                                                        <label for="paystack_secret_key" class="col-form-label">{{ __('Secret Key') }}</label>
                                                                        <input type="text" name="paystack_secret_key"
                                                                            id="paystack_secret_key"
                                                                            class="form-control"
                                                                            value="{{ !isset($store_payment_setting['paystack_secret_key']) || is_null($store_payment_setting['paystack_secret_key']) ? '' : $store_payment_setting['paystack_secret_key'] }}"
                                                                            placeholder="{{ __('Secret Key') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingFour">
                                                    <button class="accordion-button collapsed"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseFour"
                                                        aria-expanded="false"
                                                        aria-controls="collapseTwo">
                                                        <span
                                                            class="d-flex align-items-center">{{ __('Flutterwave') }}</span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('On/Off') }}:</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_flutterwave_enabled" value="off">
                                                                <input type="checkbox" name="is_flutterwave_enabled"
                                                                    class="form-check-input input-primary"
                                                                    id="is_flutterwave_enabled"  {{ isset($store_payment_setting['is_flutterwave_enabled']) && $store_payment_setting['is_flutterwave_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="is_flutterwave_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseFour"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="headingFour"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row gy-4">
                                                            <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_client_id" class="col-form-label">{{ __('Public Key') }}</label>
                                                                        <input type="text" name="flutterwave_public_key" id="flutterwave_public_key"
                                                                            class="form-control"
                                                                            value="{{ !isset($store_payment_setting['flutterwave_public_key']) || is_null($store_payment_setting['flutterwave_public_key']) ? '' : $store_payment_setting['flutterwave_public_key'] }}"
                                                                            placeholder="Public Key">
                                                                    </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="paystack_secret_key" class="col-form-label">{{ __('Secret Key') }}</label>
                                                                    <input type="text" name="flutterwave_secret_key"
                                                                        id="flutterwave_secret_key" class="form-control"
                                                                        value="{{ !isset($store_payment_setting['flutterwave_secret_key']) || is_null($store_payment_setting['flutterwave_secret_key']) ? '' : $store_payment_setting['flutterwave_secret_key'] }}"
                                                                        placeholder="Secret Key">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingFive">
                                                    <button class="accordion-button collapsed"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseFive"
                                                        aria-expanded="false"
                                                        aria-controls="collapseFive">
                                                        <span
                                                            class="d-flex align-items-center">  {{ __('Razorpay') }}</span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('On/Off') }}:</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_razorpay_enabled" value="off"> 
                                                                <input type="checkbox"
                                                                    class="form-check-input input-primary" 
                                                                    name="is_razorpay_enabled"
                                                                    id="is_razorpay_enabled"  {{ isset($store_payment_setting['is_razorpay_enabled']) && $store_payment_setting['is_razorpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="is_razorpay_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseFive"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="headingFive"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row gy-4">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="paypal_client_id" class="col-form-label">{{ __('Public Key') }}</label>
                                                                    <input type="text" name="razorpay_public_key" id="razorpay_public_key" class="form-control"
                                                                        value="{{ !isset($store_payment_setting['razorpay_public_key']) || is_null($store_payment_setting['razorpay_public_key']) ? '' : $store_payment_setting['razorpay_public_key'] }}"
                                                                        placeholder="Public Key">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="paystack_secret_key" class="col-form-label"> {{ __('Secret Key') }}</label>
                                                                    <input type="text" name="razorpay_secret_key" id="razorpay_secret_key" class="form-control" value="{{ !isset($store_payment_setting['razorpay_secret_key']) || is_null($store_payment_setting['razorpay_secret_key']) ? '' : $store_payment_setting['razorpay_secret_key'] }}" placeholder="Secret Key">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingSix">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                                        <span
                                                            class="d-flex align-items-center"> {{ __('Paytm') }}</span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('On/Off') }}:</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_paytm_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input input-primary"  name="is_paytm_enabled" id="is_paytm_enabled" {{ isset($store_payment_setting['is_paytm_enabled']) && $store_payment_setting['is_paytm_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label" for="is_paytm_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row gy-4">
                                                            <div class="col-lg-12 pb-4">
                                                                <label class="paypal-label col-form-label" for="paypal_mode">{{ __('Paytm Environment') }}</label>
                                                                <br>
                                                                <div class="d-flex flex-wrap">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label
                                                                                    class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="paytm_mode"
                                                                                        value="local"
                                                                                        class="form-check-input"
                                                                                        {{ !isset($store_payment_setting['paytm_mode']) || $store_payment_setting['paytm_mode'] == '' || $store_payment_setting['paytm_mode'] == 'local' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Local') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label
                                                                                    class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="paytm_mode"
                                                                                        value="production"
                                                                                        class="form-check-input"
                                                                                        {{ isset($store_payment_setting['paytm_mode']) && $store_payment_setting['paytm_mode'] == 'production' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Production') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="paytm_public_key" class="col-form-label">{{ __('Merchant ID') }}</label>
                                                                    <input type="text" name="paytm_merchant_id"
                                                                        id="paytm_merchant_id" class="form-control"
                                                                        value="{{ isset($store_payment_setting['paytm_merchant_id']) ? $store_payment_setting['paytm_merchant_id'] : '' }}"
                                                                        placeholder="{{ __('Merchant ID') }}" />
                                                                    @if ($errors->has('paytm_merchant_id'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('paytm_merchant_id') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="paytm_secret_key" class="col-form-label">{{ __('Merchant Key') }}</label>
                                                                    <input type="text" name="paytm_merchant_key"
                                                                        id="paytm_merchant_key" class="form-control"
                                                                        value="{{ isset($store_payment_setting['paytm_merchant_key']) ? $store_payment_setting['paytm_merchant_key'] : '' }}"
                                                                        placeholder="{{ __('Merchant Key') }}" />
                                                                    @if ($errors->has('paytm_merchant_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('paytm_merchant_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="paytm_industry_type" class="col-form-label">{{ __('Industry Type') }}</label>
                                                                    <input type="text" name="paytm_industry_type"
                                                                        id="paytm_industry_type"
                                                                        class="form-control"
                                                                        value="{{ isset($store_payment_setting['paytm_industry_type']) ? $store_payment_setting['paytm_industry_type'] : '' }}"
                                                                        placeholder="{{ __('Industry Type') }}" />
                                                                    @if ($errors->has('paytm_industry_type'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('paytm_industry_type') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingseven">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseseven" aria-expanded="false" aria-controls="collapseseven">
                                                        <span
                                                            class="d-flex align-items-center">{{ __('Mercado Pago') }}</span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('On/Off:') }}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_mercado_enabled" value="off">
                                                                <input type="checkbox"  name="is_mercado_enabled"
                                                                    class="form-check-input input-primary"
                                                                    id="is_mercado_enabled" {{ isset($store_payment_setting['is_mercado_enabled']) && $store_payment_setting['is_mercado_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="is_mercado_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseseven" class="accordion-collapse collapse" aria-labelledby="headingseven" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-lg-12 pb-4">
                                                                <label class="col-form-label"
                                                                    for="mercado_mode">{{ __('Mercado Mode') }}</label>
                                                                <br>
                                                                <div class="d-flex flex-wrap">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label
                                                                                    class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="mercado_mode"
                                                                                        value="sandbox"
                                                                                        class="form-check-input"
                                                                                        {{ (isset($store_payment_setting['mercado_mode']) && $store_payment_setting['mercado_mode'] == '') || (isset($store_payment_setting['mercado_mode']) && $store_payment_setting['mercado_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2 me-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label
                                                                                    class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="mercado_mode"
                                                                                        value="live"
                                                                                        class="form-check-input"
                                                                                        {{ isset($store_payment_setting['mercado_mode']) && $store_payment_setting['mercado_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="mercado_access_token"
                                                                        class="col-form-label">{{ __('Access Token') }}</label>
                                                                    <input type="text"
                                                                        name="mercado_access_token"
                                                                        id="mercado_access_token"
                                                                        class="form-control"
                                                                        value="{{ isset($store_payment_setting['mercado_access_token']) ? $store_payment_setting['mercado_access_token'] : '' }}"
                                                                        placeholder="{{ __('Access Token') }}" />
                                                                    @if ($errors->has('mercado_secret_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('mercado_access_token') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingeight">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseeight" aria-expanded="false" aria-controls="collapseeight">
                                                        <span class="d-flex align-items-center">{{ __('Mollie') }}</span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('On/Off') }}:</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_mollie_enabled" value="off">
                                                                <input type="checkbox" name="is_mollie_enabled" 
                                                                    class="form-check-input input-primary"
                                                                    id="is_mollie_enabled"  {{ isset($store_payment_setting['is_mollie_enabled']) && $store_payment_setting['is_mollie_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="is_mollie_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseeight" class="accordion-collapse collapse" aria-labelledby="headingeight" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="mollie_api_key"
                                                                        class="col-form-label">{{ __('Mollie Api Key') }}</label>
                                                                    <input type="text" name="mollie_api_key"
                                                                        id="mollie_api_key" class="form-control"
                                                                        value="{{ !isset($store_payment_setting['mollie_api_key']) || is_null($store_payment_setting['mollie_api_key']) ? '' : $store_payment_setting['mollie_api_key'] }}"
                                                                        placeholder="Mollie Api Key">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="mollie_profile_id"
                                                                        class="col-form-label">{{ __('Mollie Profile Id') }}</label>
                                                                    <input type="text" name="mollie_profile_id"
                                                                        id="mollie_profile_id" class="form-control"
                                                                        value="{{ !isset($store_payment_setting['mollie_profile_id']) || is_null($store_payment_setting['mollie_profile_id']) ? '' : $store_payment_setting['mollie_profile_id'] }}"
                                                                        placeholder="Mollie Profile Id">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="mollie_partner_id"
                                                                        class="col-form-label">{{ __('Mollie Partner Id') }}</label>
                                                                    <input type="text" name="mollie_partner_id"
                                                                        id="mollie_partner_id" class="form-control"
                                                                        value="{{ !isset($store_payment_setting['mollie_partner_id']) || is_null($store_payment_setting['mollie_partner_id']) ? '' : $store_payment_setting['mollie_partner_id'] }}"
                                                                        placeholder="Mollie Partner Id">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingnine">
                                                    <button class="accordion-button collapsed"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseNine"
                                                        aria-expanded="false"
                                                        aria-controls="collapseNine">
                                                        <span
                                                            class="d-flex align-items-center">{{ __('Skrill') }}</span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('On/Off') }}:</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_skrill_enabled" value="off">
                                                                <input type="checkbox"
                                                                    class="form-check-input input-primary"
                                                                    name="is_skrill_enabled"
                                                                    id="is_skrill_enabled" {{ isset($store_payment_setting['is_skrill_enabled']) && $store_payment_setting['is_skrill_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="is_skrill_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseNine"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="headingnine"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row gy-4">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="mollie_api_key"
                                                                        class="col-form-label">{{ __('Skrill Email') }}</label>
                                                                    <input type="email" name="skrill_email"
                                                                        id="skrill_email" class="form-control"
                                                                        value="{{ isset($store_payment_setting['skrill_email']) ? $store_payment_setting['skrill_email'] : '' }}"
                                                                        placeholder="{{ __('Skrill Email') }}" />
                                                                    @if ($errors->has('skrill_email'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('skrill_email') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTen">
                                                    <button class="accordion-button collapsed"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseTen"
                                                        aria-expanded="false"
                                                        aria-controls="collapseTen">
                                                        <span
                                                            class="d-flex align-items-center">{{ __('CoinGate') }}</span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('On/Off') }}:</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_coingate_enabled" value="off">
                                                                <input type="checkbox" name="is_coingate_enabled"
                                                                    class="form-check-input input-primary"
                                                                    id="is_coingate_enabled"  {{ isset($store_payment_setting['is_coingate_enabled']) && $store_payment_setting['is_coingate_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="is_coingate_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseTen"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="headingTen"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row gy-4">
                                                            <div class="col-lg-12 pb-4">
                                                                <label class="col-form-label" for="coingate_mode">{{ __('CoinGate Mode') }}</label>
                                                                <br>
                                                                <div class="d-flex flex-wrap">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="coingate_mode"
                                                                                        value="sandbox"
                                                                                        class="form-check-input"
                                                                                        {{ !isset($store_payment_setting['coingate_mode']) || $store_payment_setting['coingate_mode'] == '' || $store_payment_setting['coingate_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2 me-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label
                                                                                    class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="coingate_mode"
                                                                                        value="live"
                                                                                        class="form-check-input"
                                                                                        {{ isset($store_payment_setting['coingate_mode']) && $store_payment_setting['coingate_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="coingate_auth_token"
                                                                        class="col-form-label">{{ __('CoinGate Auth Token') }}</label>
                                                                    <input type="text" name="coingate_auth_token"
                                                                        id="coingate_auth_token"
                                                                        class="form-control"
                                                                        value="{{ !isset($store_payment_setting['coingate_auth_token']) || is_null($store_payment_setting['coingate_auth_token']) ? '' : $store_payment_setting['coingate_auth_token'] }}"
                                                                        placeholder="CoinGate Auth Token">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingEleven">
                                                    <button class="accordion-button collapsed"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseEleven"
                                                        aria-expanded="false"
                                                        aria-controls="collapseEleven">
                                                        <span
                                                            class="d-flex align-items-center">{{ __('PaymentWall') }}</span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('On/Off') }}:</span>
                                                            <div
                                                                class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_paymentwall_enabled" value="off">
                                                                <input type="checkbox"  name="is_paymentwall_enabled"
                                                                    class="form-check-input input-primary"
                                                                    id="is_paymentwall_enabled" {{ isset($store_payment_setting['is_paymentwall_enabled']) && $store_payment_setting['is_paymentwall_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="is_paymentwall_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseEleven"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="headingEleven"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row gy-4">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="paymentwall_public_key"
                                                                        class="col-form-label">{{ __('Public Key') }}</label>
                                                                    <input type="text"
                                                                        name="paymentwall_public_key"
                                                                        id="paymentwall_public_key"
                                                                        class="form-control"
                                                                        value="{{ !isset($store_payment_setting['paymentwall_public_key']) || is_null($store_payment_setting['paymentwall_public_key']) ? '' : $store_payment_setting['paymentwall_public_key'] }}"
                                                                        placeholder="{{ __('Public Key') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="paymentwall_private_key"
                                                                        class="col-form-label">{{ __('Private Key') }}</label>
                                                                    <input type="text"
                                                                        name="paymentwall_private_key"
                                                                        id="paymentwall_private_key"
                                                                        class="form-control"
                                                                        value="{{ !isset($store_payment_setting['paymentwall_private_key']) || is_null($store_payment_setting['paymentwall_private_key']) ? '' : $store_payment_setting['paymentwall_private_key'] }}"
                                                                        placeholder="{{ __('Private Key') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwelve">
                                                    <button class="accordion-button collapsed"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseTwelve"
                                                        aria-expanded="false"
                                                        aria-controls="collapseTwelve">
                                                        <span class="d-flex align-items-center">{{ __('Toyyibpay') }}</span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('On/Off') }}:</span>
                                                            <div
                                                                class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_toyyibpay_enabled" value="off">
                                                                <input type="checkbox"  name="is_toyyibpay_enabled"
                                                                    class="form-check-input input-primary"
                                                                    id="is_toyyibpay_enabled" {{ isset($store_payment_setting['is_toyyibpay_enabled']) && $store_payment_setting['is_toyyibpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="is_toyyibpay_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseTwelve"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="headingTwelve"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row gy-4">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="toyyibpay_category_code"
                                                                        class="col-form-label">{{ __('Category Code') }}</label>
                                                                    <input type="text"
                                                                        name="toyyibpay_category_code"
                                                                        id="toyyibpay_category_code"
                                                                        class="form-control"
                                                                        value="{{ !isset($store_payment_setting['toyyibpay_category_code']) || is_null($store_payment_setting['toyyibpay_category_code']) ? '' : $store_payment_setting['toyyibpay_category_code'] }}"
                                                                        placeholder="{{ __('category code') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label for="toyyibpay_secret_key"
                                                                        class="col-form-label">{{ __('Secret Key') }}</label>
                                                                    <input type="text"
                                                                        name="toyyibpay_secret_key"
                                                                        id="toyyibpay_secret_key"
                                                                        class="form-control"
                                                                        value="{{ !isset($store_payment_setting['toyyibpay_secret_key']) || is_null($store_payment_setting['toyyibpay_secret_key']) ? '' : $store_payment_setting['toyyibpay_secret_key'] }}"
                                                                        placeholder="{{ __('toyyibpay secret key') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingThirteen">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseThirteen"
                                                        aria-expanded="true" aria-controls="collapseThirteen">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Payfast') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('On/Off :')}}</span>
                                                            <div class="form-check form-switch d-inline-block custom-switch-v1">
                                                                <input type="hidden" name="is_payfast_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_payfast_enabled"
                                                                    id="is_payfast_enabled"
                                                                    {{ isset($store_payment_setting['is_payfast_enabled']) && $store_payment_setting['is_payfast_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_payfast_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseThirteen" class="accordion-collapse collapse"aria-labelledby="headingThirteen"data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label class="payfast-label col-form-label"
                                                                    for="payfast_mode">{{ __('Payfast Mode') }}</label>
                                                                <br>
                                                                <div class="d-flex">
                                                                    <div class="me-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label
                                                                                    class="form-check-labe text-dark {{ isset($store_payment_setting['payfast_mode']) && $store_payment_setting['payfast_mode'] == 'sandbox' ? 'active' : '' }}">
                                                                                    <input type="radio"
                                                                                        name="payfast_mode" value="sandbox"
                                                                                        class="form-check-input"
                                                                                        {{ (isset($store_payment_setting['payfast_mode']) && $store_payment_setting['payfast_mode'] == '') || (isset($store_payment_setting['payfast_mode']) &&
                                                                                        $store_payment_setting['payfast_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>{{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="me-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label
                                                                                    class="form-check-labe text-dark {{ isset($store_payment_setting['payfast_mode']) && $store_payment_setting['payfast_mode'] == 'live' ? 'active' : '' }}">
                                                                                    <input type="radio"
                                                                                        name="payfast_mode" value="live"
                                                                                        class="form-check-input"
                                                                                        {{ isset($store_payment_setting['payfast_mode']) && $store_payment_setting['payfast_mode'] == 'live' ? 'checked="checked"' : '' }}>{{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="payfast_merchant_id"
                                                                        class="col-form-label">{{ __('Merchant ID') }}</label>
                                                                    <input type="text" name="payfast_merchant_id"
                                                                        id="payfast_merchant_id" class="form-control"
                                                                        value="{{ isset($store_payment_setting['payfast_merchant_id']) ? $store_payment_setting['payfast_merchant_id'] : '' }}"
                                                                        placeholder="{{ __('Merchant ID') }}">
                                                                </div>
                                                                @if ($errors->has('payfast_merchant_id'))
                                                                    <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('payfast_merchant_id') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="payfast_merchant_key"
                                                                        class="col-form-label">{{ __('Merchant Key') }}</label>
                                                                    <input type="text" name="payfast_merchant_key"
                                                                        id="payfast_merchant_key" class="form-control"
                                                                        value="{{ isset($store_payment_setting['payfast_merchant_key']) ? $store_payment_setting['payfast_merchant_key'] : '' }}"
                                                                        placeholder="{{ __('Merchant Key') }}">
                                                                </div>
                                                                @if ($errors->has('payfast_merchant_key'))
                                                                    <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('payfast_merchant_key') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="payfast_signature"
                                                                        class="col-form-label">{{ __('Salt Passphrase') }}</label>
                                                                    <input type="text" name="payfast_signature"
                                                                        id="payfast_signature" class="form-control"
                                                                        value="{{ isset($store_payment_setting['payfast_signature']) ? $store_payment_setting['payfast_signature'] : '' }}"
                                                                        placeholder="{{ __('Salt Passphrase') }}">
                                                                </div>
                                                                @if ($errors->has('payfast_signature'))
                                                                    <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('payfast_signature') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingEighteen">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseEighteen"
                                                        aria-expanded="true" aria-controls="collapseEighteen">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Iyzipay') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('On/Off :')}}</span>
                                                            <div class="form-check form-switch d-inline-block custom-switch-v1">
                                                                <input type="hidden" name="is_iyzipay_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_iyzipay_enabled"
                                                                    id="is_iyzipay_enabled"
                                                                    {{ isset($store_payment_setting['is_iyzipay_enabled']) && $store_payment_setting['is_iyzipay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_iyzipay_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseEighteen" class="accordion-collapse collapse"aria-labelledby="headingEighteen"data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label class="payfast-label col-form-label"
                                                                    for="iyzipay_mode">{{ __('Iyzipay Mode') }}</label>
                                                                <br>
                                                                <div class="d-flex">
                                                                    <div class="me-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label
                                                                                    class="form-check-labe text-dark {{ isset($store_payment_setting['iyzipay_mode']) && $store_payment_setting['iyzipay_mode'] == 'sandbox' ? 'active' : '' }}">
                                                                                    <input type="radio"
                                                                                        name="iyzipay_mode" value="sandbox"
                                                                                        class="form-check-input"
                                                                                        {{ (isset($store_payment_setting['iyzipay_mode']) && $store_payment_setting['iyzipay_mode'] == '') || (isset($store_payment_setting['iyzipay_mode']) &&
                                                                                        $store_payment_setting['iyzipay_mode'] == 'sandbox') ? 'checked="checked"' : '' }}>{{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="me-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label
                                                                                    class="form-check-labe text-dark {{ isset($store_payment_setting['iyzipay_mode']) && $store_payment_setting['iyzipay_mode'] == 'live' ? 'active' : '' }}">
                                                                                    <input type="radio"
                                                                                        name="iyzipay_mode" value="live"
                                                                                        class="form-check-input"
                                                                                        {{ isset($store_payment_setting['iyzipay_mode']) && $store_payment_setting['iyzipay_mode'] == 'live' ? 'checked="checked"' : '' }}>{{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="iyzipay_key"
                                                                        class="col-form-label">{{ __('Iyzipay Key') }}</label>
                                                                    <input type="text" name="iyzipay_key"
                                                                        id="iyzipay_key" class="form-control"
                                                                        value="{{ isset($store_payment_setting['iyzipay_key']) ? $store_payment_setting['iyzipay_key'] : '' }}"
                                                                        placeholder="{{ __('Iyzipay Key') }}">
                                                                </div>
                                                                @if ($errors->has('iyzipay_key'))
                                                                    <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('iyzipay_key') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="iyzipay_secret"
                                                                        class="col-form-label">{{ __('Iyzipay Secret') }}</label>
                                                                    <input type="text" name="iyzipay_secret"
                                                                        id="iyzipay_secret" class="form-control"
                                                                        value="{{ isset($store_payment_setting['iyzipay_secret']) ? $store_payment_setting['iyzipay_secret'] : '' }}"
                                                                        placeholder="{{ __('Merchant Key') }}">
                                                                </div>
                                                                @if ($errors->has('iyzipay_secret'))
                                                                    <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('iyzipay_secret') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingNineteen">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseNineteen"
                                                        aria-expanded="true" aria-controls="collapseNineteen">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Sspay') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('On/Off :')}}</span>
                                                            <div class="form-check form-switch d-inline-block custom-switch-v1">
                                                                <input type="hidden" name="is_sspay_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_sspay_enabled"
                                                                    id="is_sspay_enabled"
                                                                    {{ isset($store_payment_setting['is_sspay_enabled']) && $store_payment_setting['is_sspay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_sspay_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseNineteen" class="accordion-collapse collapse"aria-labelledby="headingNineteen"data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="sspay_category_code"
                                                                        class="col-form-label">{{ __('Sspay Category Code') }}</label>
                                                                    <input type="text" name="sspay_category_code"
                                                                        id="sspay_category_code" class="form-control"
                                                                        value="{{ isset($store_payment_setting['sspay_category_code']) ? $store_payment_setting['sspay_category_code'] : '' }}"
                                                                        placeholder="{{ __('Sspay Category Code') }}">
                                                                </div>
                                                                @if ($errors->has('sspay_category_code'))
                                                                    <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('sspay_category_code') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="sspay_secret_key"
                                                                        class="col-form-label">{{ __('Sspay Secret') }}</label>
                                                                    <input type="text" name="sspay_secret_key"
                                                                        id="sspay_secret_key" class="form-control"
                                                                        value="{{ isset($store_payment_setting['sspay_secret_key']) ? $store_payment_setting['sspay_secret_key'] : '' }}"
                                                                        placeholder="{{ __('Sspay Secret') }}">
                                                                </div>
                                                                @if ($errors->has('sspay_secret_key'))
                                                                    <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('sspay_secret_key') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwenty">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwenty"
                                                        aria-expanded="true" aria-controls="collapseTwenty">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Paytab') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('On/Off :')}}</span>
                                                            <div class="form-check form-switch d-inline-block custom-switch-v1">
                                                                <input type="hidden" name="is_paytab_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="is_paytab_enabled"
                                                                    id="is_paytab_enabled"
                                                                    {{ isset($store_payment_setting['is_paytab_enabled']) && $store_payment_setting['is_paytab_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-label"
                                                                    for="is_paytab_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseTwenty" class="accordion-collapse collapse" aria-labelledby="headingTwenty" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="paytab_profile_id"
                                                                        class="col-form-label">{{ __('Profile Id') }}</label>
                                                                    <input type="text" name="paytab_profile_id"
                                                                        id="paytab_profile_id" class="form-control"
                                                                        value="{{ isset($store_payment_setting['paytab_profile_id']) ? $store_payment_setting['paytab_profile_id'] : '' }}"
                                                                        placeholder="{{ __('Profile Id') }}">
                                                                </div>
                                                                @if ($errors->has('paytab_profile_id'))
                                                                    <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('paytab_profile_id') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="paytab_server_key"
                                                                        class="col-form-label">{{ __('Server Key') }}</label>
                                                                    <input type="text" name="paytab_server_key"
                                                                        id="paytab_server_key" class="form-control"
                                                                        value="{{ isset($store_payment_setting['paytab_server_key']) ? $store_payment_setting['paytab_server_key'] : '' }}"
                                                                        placeholder="{{ __('Paytab Secret') }}">
                                                                </div>
                                                                @if ($errors->has('paytab_server_key'))
                                                                    <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('paytab_server_key') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="paytab_region"
                                                                        class="form-label">{{ __('Region') }}</label>
                                                                    <input type="text" name="paytab_region"
                                                                        id="paytab_region"
                                                                        class="form-control form-control-label"
                                                                        value="{{ isset($store_payment_setting['paytab_region']) ? $store_payment_setting['paytab_region'] : '' }}"
                                                                        placeholder="{{ __('Region') }}" /><br>
                                                                    @if ($errors->has('paytab_region'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('paytab_region') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwentyOne">
                                                    <button class="accordion-button collapsed"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseTwentyOne"
                                                        aria-expanded="false"
                                                        aria-controls="collapseTwentyOne">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Benefit') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('On/Off') }}:</span>
                                                            <div
                                                                class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_benefit_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input input-primary" name="is_benefit_enabled" id="is_benefit_enabled" {{ isset($store_payment_setting['is_benefit_enabled']) && $store_payment_setting['is_benefit_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="is_benefit_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseTwentyOne"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="headingTwentyOne"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row gy-4">
                                                            
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('benefit_api_key', __('Benefit Key'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('benefit_api_key', isset($store_payment_setting['benefit_api_key']) ? $store_payment_setting['benefit_api_key'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Benefit Key')]) }}
                                                                    @error('benefit_api_key')
                                                                        <span class="invalid-benefit_api_key" role="alert">
                                                                            <strong
                                                                                class="text-danger">{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('benefit_secret_key', __('Benefit Secret Key'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('benefit_secret_key', isset($store_payment_setting['benefit_secret_key']) ? $store_payment_setting['benefit_secret_key'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Benefit Secret key')]) }}
                                                                    @error('benefit_secret_key')
                                                                        <span class="invalid-benefit_secret_key"
                                                                            role="alert">
                                                                            <strong
                                                                                class="text-danger">{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwentyTwo">
                                                    <button class="accordion-button collapsed"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseTwentyTwo"
                                                        aria-expanded="false"
                                                        aria-controls="collapseTwentyTwo">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Cashfree') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{ __('On/Off') }}:</span>
                                                            <div
                                                                class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_cashfree_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input input-primary" name="is_cashfree_enabled" id="is_cashfree_enabled" {{ isset($store_payment_setting['is_cashfree_enabled']) && $store_payment_setting['is_cashfree_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="is_cashfree_enabled"></label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseTwentyTwo"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="headingTwentyTwo"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row gy-4">
                                                            
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('cashfree_api_key', __('Cashfree Key'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('cashfree_api_key', isset($store_payment_setting['cashfree_api_key']) ? $store_payment_setting['cashfree_api_key'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Cashfree Key')]) }}
                                                                    @error('cashfree_api_key')
                                                                        <span class="invalid-cashfree_api_key" role="alert">
                                                                            <strong
                                                                                class="text-danger">{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('cashfree_secret_key', __('Cashfree Secret Key'), ['class' => 'col-form-label']) }}
                                                                    {{ Form::text('cashfree_secret_key', isset($store_payment_setting['cashfree_secret_key']) ? $store_payment_setting['cashfree_secret_key'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Cashfree Secret key')]) }}
                                                                    @error('cashfree_secret_key')
                                                                        <span class="invalid-cashfree_secret_key"
                                                                            role="alert">
                                                                            <strong
                                                                                class="text-danger">{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwentyThree">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwentyThree"
                                                        aria-expanded="true" aria-controls="collapseTwentyThree">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Aamarpay') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <label class="form-check-label m-1"
                                                                for="is_aamarpay_enabled">{{ __('On/Off :') }}</label>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_aamarpay_enabled"
                                                                    value="off">
                                                                <input type="checkbox"
                                                                    class="form-check-input input-primary"
                                                                    name="is_aamarpay_enabled" id="is_aamarpay_enabled"
                                                                    {{ isset($store_payment_setting['is_aamarpay_enabled']) && $store_payment_setting['is_aamarpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseTwentyThree" class="accordion-collapse collapse"
                                                    aria-labelledby="headingTwentyThree" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row pt-2">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('aamarpay_store_id', __('Store Id'), ['class' => 'form-label']) }}
                                                                    {{ Form::text('aamarpay_store_id', isset($store_payment_setting['aamarpay_store_id']) ? $store_payment_setting['aamarpay_store_id'] : '', ['class' => 'form-control', 'placeholder' => __('Store Id')]) }}<br>
                                                                    @if ($errors->has('aamarpay_store_id'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('aamarpay_store_id') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('aamarpay_signature_key', __('Signature Key'), ['class' => 'form-label']) }}
                                                                    {{ Form::text('aamarpay_signature_key', isset($store_payment_setting['aamarpay_signature_key']) ? $store_payment_setting['aamarpay_signature_key'] : '', ['class' => 'form-control', 'placeholder' => __('Signature Key')]) }}<br>
                                                                    @if ($errors->has('aamarpay_signature_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('aamarpay_signature_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('aamarpay_description', __('Description'), ['class' => 'form-label']) }}
                                                                    {{ Form::text('aamarpay_description', isset($store_payment_setting['aamarpay_description']) ? $store_payment_setting['aamarpay_description'] : '', ['class' => 'form-control', 'placeholder' => __('Description')]) }}<br>
                                                                    @if ($errors->has('aamarpay_description'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('aamarpay_description') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwentyFour">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwentyFour"
                                                        aria-expanded="true" aria-controls="collapseTwentyFour">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Paytr') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <label class="form-check-label m-1"
                                                                for="is_paytr_enabled">{{ __('On/Off :') }}</label>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_paytr_enabled"
                                                                    value="off">
                                                                <input type="checkbox"
                                                                    class="form-check-input input-primary"
                                                                    name="is_paytr_enabled" id="is_paytr_enabled"
                                                                    {{ isset($store_payment_setting['is_paytr_enabled']) && $store_payment_setting['is_paytr_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapseTwentyFour" class="accordion-collapse collapse"
                                                    aria-labelledby="headingTwentyFour" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row pt-2">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('paytr_merchant_id', __('Merchant Id'), ['class' => 'form-label']) }}
                                                                    {{ Form::text('paytr_merchant_id', isset($store_payment_setting['paytr_merchant_id']) ? $store_payment_setting['paytr_merchant_id'] : '', ['class' => 'form-control', 'placeholder' => __('Merchant Id')]) }}<br>
                                                                    @if ($errors->has('paytr_merchant_id'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('paytr_merchant_id') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('paytr_merchant_key', __('Merchant Key'), ['class' => 'form-label']) }}
                                                                    {{ Form::text('paytr_merchant_key', isset($store_payment_setting['paytr_merchant_key']) ? $store_payment_setting['paytr_merchant_key'] : '', ['class' => 'form-control', 'placeholder' => __('Merchant Key')]) }}<br>
                                                                    @if ($errors->has('paytr_merchant_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('paytr_merchant_key') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    {{ Form::label('paytr_merchant_salt', __('Merchant Salt'), ['class' => 'form-label']) }}
                                                                    {{ Form::text('paytr_merchant_salt', isset($store_payment_setting['paytr_merchant_salt']) ? $store_payment_setting['paytr_merchant_salt'] : '', ['class' => 'form-control', 'placeholder' => __('Merchant Salt')]) }}<br>
                                                                    @if ($errors->has('paytr_merchant_salt'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('paytr_merchant_salt') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwentyFive">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwentyFive"
                                                        aria-expanded="true" aria-controls="collapseTwentyFive">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Yookassa') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('On/Off :')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_yookassa_enabled"
                                                                    value="off">
                                                                <input type="checkbox"
                                                                    class="form-check-input input-primary"
                                                                    name="is_yookassa_enabled" id="is_yookassa_enabled"
                                                                    {{ isset($store_payment_setting['is_yookassa_enabled']) && $store_payment_setting['is_yookassa_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="customswitchv1-2"></label>
                                                            </div>
                                                        </div>
    
                                                    </button>
                                                </h2>
                                                <div id="collapseTwentyFive" class="accordion-collapse collapse" aria-labelledby="headingTwentyFive" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="yookassa_shop_id"
                                                                        class="form-label">{{ __('Shop ID Key') }}</label>
                                                                    <input type="text" name="yookassa_shop_id"
                                                                        id="yookassa_shop_id" class="form-control"
                                                                        value="{{ !isset($store_payment_setting['yookassa_shop_id']) || is_null($store_payment_setting['yookassa_shop_id']) ? '' : $store_payment_setting['yookassa_shop_id'] }}"
                                                                        placeholder="{{ __('Shop ID Key') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="yookassa_secret"
                                                                        class="form-label">{{ __('Secret Key') }}</label>
                                                                    <input type="text" name="yookassa_secret"
                                                                        id="yookassa_secret" class="form-control"
                                                                        value="{{ !isset($store_payment_setting['yookassa_secret']) || is_null($store_payment_setting['yookassa_secret']) ? '' : $store_payment_setting['yookassa_secret'] }}"
                                                                        placeholder="{{ __('Secret Key') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwentySix">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwentySix"
                                                        aria-expanded="true" aria-controls="collapseTwentySix">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Midtrans') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('On/Off :')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_midtrans_enabled"
                                                                    value="off">
                                                                <input type="checkbox"
                                                                    class="form-check-input input-primary"
                                                                    name="is_midtrans_enabled" id="is_midtrans_enabled"
                                                                    {{ isset($store_payment_setting['is_midtrans_enabled']) && $store_payment_setting['is_midtrans_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="customswitchv1-2"></label>
                                                            </div>
                                                        </div>
    
                                                    </button>
                                                </h2>
                                                <div id="collapseTwentySix" class="accordion-collapse collapse" aria-labelledby="headingTwentySix" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-lg-12 pb-4">
                                                                <label class="midtrans-label col-form-label" for="midtrans_mode">{{ __('Midtrans Mode') }}</label>
                                                                <br>
                                                                <div class="d-flex flex-wrap">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label
                                                                                    class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="midtrans_mode"
                                                                                        value="sandbox"
                                                                                        class="form-check-input"
                                                                                        {{ !isset($store_payment_setting['midtrans_mode']) || $store_payment_setting['midtrans_mode'] == '' || $store_payment_setting['midtrans_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2 me-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label
                                                                                    class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="midtrans_mode"
                                                                                        value="live"
                                                                                        class="form-check-input"
                                                                                        {{ isset($store_payment_setting['midtrans_mode']) && $store_payment_setting['midtrans_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="midtrans_secret"
                                                                        class="form-label">{{ __('Secret Key') }}</label>
                                                                    <input type="text" name="midtrans_secret"
                                                                        id="midtrans_secret" class="form-control"
                                                                        value="{{ !isset($store_payment_setting['midtrans_secret']) || is_null($store_payment_setting['midtrans_secret']) ? '' : $store_payment_setting['midtrans_secret'] }}"
                                                                        placeholder="{{ __('Secret Key') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwentySeven">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwentySeven"
                                                        aria-expanded="true" aria-controls="collapseTwentySeven">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Xendit') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('On/Off :')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_xendit_enabled"
                                                                    value="off">
                                                                <input type="checkbox"
                                                                    class="form-check-input input-primary"
                                                                    name="is_xendit_enabled" id="is_xendit_enabled"
                                                                    {{ isset($store_payment_setting['is_xendit_enabled']) && $store_payment_setting['is_xendit_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="customswitchv1-2"></label>
                                                            </div>
                                                        </div>
    
                                                    </button>
                                                </h2>
                                                <div id="collapseTwentySeven" class="accordion-collapse collapse" aria-labelledby="headingTwentySeven" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="xendit_api"
                                                                        class="form-label">{{ __('API Key') }}</label>
                                                                    <input type="text" name="xendit_api"
                                                                        id="xendit_api" class="form-control"
                                                                        value="{{ !isset($store_payment_setting['xendit_api']) || is_null($store_payment_setting['xendit_api']) ? '' : $store_payment_setting['xendit_api'] }}"
                                                                        placeholder="{{ __('API Key') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="xendit_token"
                                                                        class="form-label">{{ __('Token') }}</label>
                                                                    <input type="text" name="xendit_token"
                                                                        id="xendit_token" class="form-control"
                                                                        value="{{ !isset($store_payment_setting['xendit_token']) || is_null($store_payment_setting['xendit_token']) ? '' : $store_payment_setting['xendit_token'] }}"
                                                                        placeholder="{{ __('Token') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwentyEight">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwentyEight"
                                                        aria-expanded="true" aria-controls="collapseTwentyEight">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Nepalste') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('On/Off :')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_nepalste_enabled"
                                                                    value="off">
                                                                <input type="checkbox"
                                                                    class="form-check-input input-primary"
                                                                    name="is_nepalste_enabled" id="is_nepalste_enabled"
                                                                    {{ isset($store_payment_setting['is_nepalste_enabled']) && $store_payment_setting['is_nepalste_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="customswitchv1-2"></label>
                                                            </div>
                                                        </div>
    
                                                    </button>
                                                </h2>
                                                <div id="collapseTwentyEight" class="accordion-collapse collapse" aria-labelledby="headingTwentyEight" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="nepalste_public_key"
                                                                        class="form-label">{{ __('Public Key') }}</label>
                                                                    <input type="text" name="nepalste_public_key"
                                                                        id="nepalste_public_key" class="form-control"
                                                                        value="{{ !isset($store_payment_setting['nepalste_public_key']) || is_null($store_payment_setting['nepalste_public_key']) ? '' : $store_payment_setting['nepalste_public_key'] }}"
                                                                        placeholder="{{ __('Public Key') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="nepalste_secret_key"
                                                                        class="form-label">{{ __('Secret Key') }}</label>
                                                                    <input type="text" name="nepalste_secret_key"
                                                                        id="nepalste_secret_key" class="form-control"
                                                                        value="{{ !isset($store_payment_setting['nepalste_secret_key']) || is_null($store_payment_setting['nepalste_secret_key']) ? '' : $store_payment_setting['nepalste_secret_key'] }}"
                                                                        placeholder="{{ __('Secret Key') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwentyNine">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwentyNine"
                                                        aria-expanded="true" aria-controls="collapseTwentyNine">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Paiement Pro') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('On/Off :')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_paiementpro_enabled"
                                                                    value="off">
                                                                <input type="checkbox"
                                                                    class="form-check-input input-primary"
                                                                    name="is_paiementpro_enabled" id="is_paiementpro_enabled"
                                                                    {{ isset($store_payment_setting['is_paiementpro_enabled']) && $store_payment_setting['is_paiementpro_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="customswitchv1-2"></label>
                                                            </div>
                                                        </div>
    
                                                    </button>
                                                </h2>
                                                <div id="collapseTwentyNine" class="accordion-collapse collapse" aria-labelledby="headingTwentyNine" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="paiementpro_merchant_id"
                                                                        class="form-label">{{ __('Merchant Id') }}</label>
                                                                    <input type="text" name="paiementpro_merchant_id"
                                                                        id="paiementpro_merchant_id" class="form-control"
                                                                        value="{{ !isset($store_payment_setting['paiementpro_merchant_id']) || is_null($store_payment_setting['paiementpro_merchant_id']) ? '' : $store_payment_setting['paiementpro_merchant_id'] }}"
                                                                        placeholder="{{ __('Merchant Id') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingThirty">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseThirty"
                                                        aria-expanded="true" aria-controls="collapseThirty">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('Fedapay') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('On/Off :')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_fedapay_enabled"
                                                                    value="off">
                                                                <input type="checkbox"
                                                                    class="form-check-input input-primary"
                                                                    name="is_fedapay_enabled" id="is_fedapay_enabled"
                                                                    {{ isset($store_payment_setting['is_fedapay_enabled']) && $store_payment_setting['is_fedapay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="customswitchv1-2"></label>
                                                            </div>
                                                        </div>
    
                                                    </button>
                                                </h2>
                                                <div id="collapseThirty" class="accordion-collapse collapse" aria-labelledby="headingThirty" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-lg-12 pb-4">
                                                                <label class="fedapay-label col-form-label" for="fedapay_mode">{{ __('Fedapay Mode') }}</label>
                                                                <br>
                                                                <div class="d-flex flex-wrap">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label
                                                                                    class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="fedapay_mode"
                                                                                        value="sandbox"
                                                                                        class="form-check-input"
                                                                                        {{ !isset($store_payment_setting['fedapay_mode']) || $store_payment_setting['fedapay_mode'] == '' || $store_payment_setting['fedapay_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2 me-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label
                                                                                    class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="fedapay_mode"
                                                                                        value="live"
                                                                                        class="form-check-input"
                                                                                        {{ isset($store_payment_setting['fedapay_mode']) && $store_payment_setting['fedapay_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="fedapay_public_key"
                                                                        class="form-label">{{ __('Public Key') }}</label>
                                                                    <input type="text" name="fedapay_public_key"
                                                                        id="fedapay_public_key" class="form-control"
                                                                        value="{{ !isset($store_payment_setting['fedapay_public_key']) || is_null($store_payment_setting['fedapay_public_key']) ? '' : $store_payment_setting['fedapay_public_key'] }}"
                                                                        placeholder="{{ __('Public Key') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="fedapay_secret_key"
                                                                        class="form-label">{{ __('Secret Key') }}</label>
                                                                    <input type="text" name="fedapay_secret_key"
                                                                        id="fedapay_secret_key" class="form-control"
                                                                        value="{{ !isset($store_payment_setting['fedapay_secret_key']) || is_null($store_payment_setting['fedapay_secret_key']) ? '' : $store_payment_setting['fedapay_secret_key'] }}"
                                                                        placeholder="{{ __('Secret Key') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingThirtyOne">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseThirtyOne"
                                                        aria-expanded="true" aria-controls="collapseThirtyOne">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('PayHere') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('On/Off :')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_payhere_enabled"
                                                                    value="off">
                                                                <input type="checkbox"
                                                                    class="form-check-input input-primary"
                                                                    name="is_payhere_enabled" id="is_payhere_enabled"
                                                                    {{ isset($store_payment_setting['is_payhere_enabled']) && $store_payment_setting['is_payhere_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="customswitchv1-2"></label>
                                                            </div>
                                                        </div>
    
                                                    </button>
                                                </h2>
                                                <div id="collapseThirtyOne" class="accordion-collapse collapse" aria-labelledby="headingThirtyOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-lg-12 pb-4">
                                                                <label class="payhere-label col-form-label" for="payhere_mode">{{ __('PayHere Mode') }}</label>
                                                                <br>
                                                                <div class="d-flex flex-wrap">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label
                                                                                    class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="payhere_mode"
                                                                                        value="sandbox"
                                                                                        class="form-check-input"
                                                                                        {{ !isset($store_payment_setting['payhere_mode']) || $store_payment_setting['payhere_mode'] == '' || $store_payment_setting['payhere_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Sandbox') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2 me-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label
                                                                                    class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="payhere_mode"
                                                                                        value="live"
                                                                                        class="form-check-input"
                                                                                        {{ isset($store_payment_setting['payhere_mode']) && $store_payment_setting['payhere_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                                                                    {{ __('Live') }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="payhere_merchant_id"
                                                                        class="form-label">{{ __('Merchant ID') }}</label>
                                                                    <input type="text" name="payhere_merchant_id"
                                                                        id="payhere_merchant_id" class="form-control"
                                                                        value="{{ !isset($store_payment_setting['payhere_merchant_id']) || is_null($store_payment_setting['payhere_merchant_id']) ? '' : $store_payment_setting['payhere_merchant_id'] }}"
                                                                        placeholder="{{ __('Merchant ID') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="payhere_merchant_secret"
                                                                        class="form-label">{{ __('Merchant Secret') }}</label>
                                                                    <input type="text" name="payhere_merchant_secret"
                                                                        id="payhere_merchant_secret" class="form-control"
                                                                        value="{{ !isset($store_payment_setting['payhere_merchant_secret']) || is_null($store_payment_setting['payhere_merchant_secret']) ? '' : $store_payment_setting['payhere_merchant_secret'] }}"
                                                                        placeholder="{{ __('Merchant Secret') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="payhere_app_id"
                                                                        class="form-label">{{ __('App ID') }}</label>
                                                                    <input type="text" name="payhere_app_id"
                                                                        id="payhere_app_id" class="form-control"
                                                                        value="{{ !isset($store_payment_setting['payhere_app_id']) || is_null($store_payment_setting['payhere_app_id']) ? '' : $store_payment_setting['payhere_app_id'] }}"
                                                                        placeholder="{{ __('App ID') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="payhere_app_secret"
                                                                        class="form-label">{{ __('App Secret') }}</label>
                                                                    <input type="text" name="payhere_app_secret"
                                                                        id="payhere_app_secret" class="form-control"
                                                                        value="{{ !isset($store_payment_setting['payhere_app_secret']) || is_null($store_payment_setting['payhere_app_secret']) ? '' : $store_payment_setting['payhere_app_secret'] }}"
                                                                        placeholder="{{ __('App Secret') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingThirtyTwo">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseThirtyTwo"
                                                        aria-expanded="true" aria-controls="collapseThirtyTwo">
                                                        <span class="d-flex align-items-center">
                                                            {{ __('CinetPay') }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="me-2">{{__('On/Off :')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_cinetpay_enabled"
                                                                    value="off">
                                                                <input type="checkbox"
                                                                    class="form-check-input input-primary"
                                                                    name="is_cinetpay_enabled" id="is_cinetpay_enabled"
                                                                    {{ isset($store_payment_setting['is_cinetpay_enabled']) && $store_payment_setting['is_cinetpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="customswitchv1-2"></label>
                                                            </div>
                                                        </div>
    
                                                    </button>
                                                </h2>
                                                <div id="collapseThirtyTwo" class="accordion-collapse collapse" aria-labelledby="headingThirtyTwo" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="cinetpay_site_id"
                                                                        class="form-label">{{ __('CinetPay Site ID') }}</label>
                                                                    <input type="text" name="cinetpay_site_id"
                                                                        id="cinetpay_site_id" class="form-control"
                                                                        value="{{ !isset($store_payment_setting['cinetpay_site_id']) || is_null($store_payment_setting['cinetpay_site_id']) ? '' : $store_payment_setting['cinetpay_site_id'] }}"
                                                                        placeholder="{{ __('CinetPay Site ID') }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="cinetpay_api_key"
                                                                        class="form-label">{{ __('CinetPay API Key') }}</label>
                                                                    <input type="text" name="cinetpay_api_key"
                                                                        id="cinetpay_api_key" class="form-control"
                                                                        value="{{ !isset($store_payment_setting['cinetpay_api_key']) || is_null($store_payment_setting['cinetpay_api_key']) ? '' : $store_payment_setting['cinetpay_api_key'] }}"
                                                                        placeholder="{{ __('CinetPay API Key') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="col-sm-12 px-2">
                                    <div class="text-end">
                                        {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-store_email_setting" role="tabpanel" aria-labelledby="pills-brand_setting-tab">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="">
                                        {{ __('Email Settings') }}
                                    </h5>
                                    <small>{{ __('( This SMTP will be used for sending your company-level email. If this field is empty, then SuperAdmin SMTP will be used for sending emails.)') }}</small>
                                </div>
                                <div class="card-body p-4">
                                    {{ Form::open(['route' => ['owner.email.setting', $store_settings->slug], 'method' => 'post']) }}
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            {{ Form::label('mail_driver', __('Mail Driver'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_driver', $store_settings->mail_driver, ['class' => 'form-control', 'id' => 'mail_driver', 'placeholder' => __('Enter Mail Driver')]) }}
                                            @error('mail_driver')
                                                <span class="invalid-mail_driver" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('mail_host', __('Mail Host'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_host', $store_settings->mail_host, ['class' => 'form-control ', 'id' => 'mail_host', 'placeholder' => __('Enter Mail Host')]) }}
                                            @error('mail_host')
                                                <span class="invalid-mail_driver" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('mail_port', __('Mail Port'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_port', $store_settings->mail_port, ['class' => 'form-control', 'id' => 'mail_port', 'placeholder' => __('Enter Mail Port')]) }}
                                            @error('mail_port')
                                                <span class="invalid-mail_port" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('mail_username', __('Mail Username'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_username', $store_settings->mail_username, ['class' => 'form-control', 'id' => 'mail_username', 'placeholder' => __('Enter Mail Username')]) }}
                                            @error('mail_username')
                                                <span class="invalid-mail_username" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('mail_password', __('Mail Password'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_password', $store_settings->mail_password, ['class' => 'form-control', 'id' => 'mail_password', 'placeholder' => __('Enter Mail Password')]) }}
                                            @error('mail_password')
                                                <span class="invalid-mail_password" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('mail_encryption', __('Mail Encryption'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_encryption', $store_settings->mail_encryption, ['class' => 'form-control', 'id' => 'mail_encryption', 'placeholder' => __('Enter Mail Encryption')]) }}
                                            @error('mail_encryption')
                                                <span class="invalid-mail_encryption" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('mail_from_address', __('Mail From Address'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_from_address', $store_settings->mail_from_address, ['class' => 'form-control', 'id' => 'mail_from_address', 'placeholder' => __('Enter Mail From Address')]) }}
                                            @error('mail_from_address')
                                                <span class="invalid-mail_from_address" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('mail_from_name', __('Mail From Name'), ['class' => 'form-label']) }}
                                            {{ Form::text('mail_from_name', $store_settings->mail_from_name, ['class' => 'form-control', 'id' => 'mail_from_name', 'placeholder' => __('Enter Mail From Name')]) }}
                                            @error('mail_from_name')
                                                <span class="invalid-mail_from_name" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="text-end">
                                        <div class="card-footer">
                                            <div class="col-sm-12 px-2">
                                                <div class="d-flex justify-content-between gap-2 flex-column flex-sm-row">
                                                    <a href="#" data-url="{{ route('test.mail') }}"
                                                        data-title="{{ __('Send Test Mail') }}"
                                                        class="btn btn-xs btn-primary send_email">
                                                        {{ __('Send Test Mail') }}
                                                    </a>

                                                    {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-whatsapp_custom_massage" role="tabpanel" aria-labelledby="pills-brand_setting-tab">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="">
                                        {{ __('Whatsapp Message Settings') }}
                                    </h5>
                                </div>
                                <div class="card-body p-4">
                                    {{ Form::model($store_settings, ['route' => ['customMassage', $store_settings->slug], 'method' => 'POST']) }}
                                    <div class="row">
                                        <h6 class="font-weight-bold">{{ __('Order Variable') }}</h6>
                                        <div class="form-group col-md-6">
                                            <p class="mb-1">{{ __('Store Name') }} : <span
                                                    class="pull-right text-primary">{store_name}</span></p>
                                            <p class="mb-1">{{ __('Order No') }} : <span
                                                    class="pull-right text-primary">{order_no}</span></p>
                                            <p class="mb-1">{{ __('Customer Name') }} : <span
                                                    class="pull-right text-primary">{customer_name}</span></p>
                                            <p class="mb-1">{{ __('Billing Address') }} : <span
                                                    class="pull-right text-primary">{billing_address}</span></p>
                                            <p class="mb-1">{{ __('Billing Country') }} : <span
                                                    class="pull-right text-primary">{billing_country}</span></p>
                                            <p class="mb-1">{{ __('Billing City') }} : <span
                                                    class="pull-right text-primary">{billing_city}</span></p>
                                            <p class="mb-1">{{ __('Billing Postalcode') }} : <span
                                                    class="pull-right text-primary">{billing_postalcode}</span></p>
                                            <p class="mb-1">{{ __('Shipping Address') }} : <span
                                                    class="pull-right text-primary">{shipping_address}</span></p>
                                            <p class="mb-1">{{ __('Shipping Country') }} : <span
                                                    class="pull-right text-primary">{shipping_country}</span></p>

                                            <p class="mb-1">{{ __('Shipping City') }} : <span
                                                    class="pull-right text-primary">{shipping_city}</span></p>
                                            <p class="mb-1">{{ __('Shipping Postalcode') }} : <span
                                                    class="pull-right text-primary">{shipping_postalcode}</span></p>
                                            <p class="mb-1">{{ __('Item Variable') }} : <span
                                                    class="pull-right text-primary">{item_variable}</span></p>
                                            <p class="mb-1">{{ __('Qty Total') }} : <span
                                                    class="pull-right text-primary">{qty_total}</span></p>
                                            <p class="mb-1">{{ __('Sub Total') }} : <span
                                                    class="pull-right text-primary">{sub_total}</span></p>
                                            <p class="mb-1">{{ __('Discount Amount') }} : <span
                                                    class="pull-right text-primary">{discount_amount}</span></p>
                                            <p class="mb-1">{{ __('Shipping Amount') }} : <span
                                                    class="pull-right text-primary">{shipping_amount}</span></p>
                                            <p class="mb-1">{{ __('Total Tax') }} : <span
                                                    class="pull-right text-primary">{total_tax}</span></p>
                                            <p class="mb-1">{{ __('Final Total') }} : <span
                                                    class="pull-right text-primary">{final_total}</span></p>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <h6 class="font-weight-bold">{{ __('Item Variable') }}</h6>
                                            <p class="mb-1">{{ __('Sku') }} : <span
                                                    class="pull-right text-primary">{sku}</span></p>
                                            <p class="mb-1">{{ __('Quantity') }} : <span
                                                    class="pull-right text-primary">{quantity}</span></p>
                                            <p class="mb-1">{{ __('Product Name') }} : <span
                                                    class="pull-right text-primary">{product_name}</span></p>
                                            <p class="mb-1">{{ __('Variant Name') }} : <span
                                                    class="pull-right text-primary">{variant_name}</span></p>
                                            <p class="mb-1">{{ __('Item Tax') }} : <span
                                                    class="pull-right text-primary">{item_tax}</span></p>
                                            <p class="mb-1">{{ __('Item total') }} : <span
                                                    class="pull-right text-primary">{item_total}</span></p>
                                            <div class="form-group">
                                                <label for="storejs" class="col-form-label">{item_variable}</label>
                                                {{ Form::text('item_variable', null, ['class' => 'form-control', 'placeholder' => '{quantity} x {product_name} - {variant_name} + {item_tax} = {item_total}']) }}
                                            </div>
                                            <div class="form-group">
                                                {{ Form::label('content', __('Whatsapp Message'), ['class' => 'col-form-label']) }}
                                                {{ Form::textarea('content', null, ['class' => 'form-control', 'required' => 'required']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="text-end">
                                        <div class="card-footer">
                                            <div class="col-sm-12 px-2">
                                                <div class="d-flex justify-content-end">
                                                    {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-twilio_setting" role="tabpanel" aria-labelledby="pills-brand_setting-tab">
                        <div class="col-md-12">
                            <div class="card">
                                <form method="POST" action="{{ route('owner.twilio.setting', $store_settings->slug) }}" accept-charset="UTF-8">
                                @csrf
                                    <div class="card-header">
                                        <div class="row ">
                                            <div class="col-6">
                                                <h5>{{ __('Twilio Settings') }}</h5>
                                                <small>{{__('Edit Twilio Settings')}}</small>
                                            </div>
                                            <div class="col-6 d-flex justify-content-end">
                                                <div class="form-check form-switch ">
                                                    <input type="checkbox" class="form-check-input off switch"
                                                        data-toggle="switchbutton" name="is_twilio_enabled"
                                                        id="twilio_module"
                                                        {{ $store_settings['is_twilio_enabled'] == 'on' ? 'checked=checked' : '' }}>
                                                    {{-- <label class="form-check-label" for="twilio_module">
                                                        {{ __('Twilio') }}
                                                    </label> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        
                                    <div class="card-body p-4">
                                        <div class="row">

                                            <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                                <label for="twilio_token"
                                                    class="form-label">{{ __('Twilio SID') }}</label>
                                                <input class="form-control" name="twilio_sid" type="text"
                                                    value="{{ $store_settings->twilio_sid }}" id="twilio_sid">
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                                <label for="twilio_token"
                                                    class="form-label">{{ __('Twilio Token') }}</label>
                                                <input class="form-control " name="twilio_token" type="text"
                                                    value="{{ $store_settings->twilio_token }}" id="twilio_token">
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                                <label for="twilio_from"
                                                    class="form-label">{{ __('Twilio From') }}</label>
                                                <input class="form-control " name="twilio_from" type="text"
                                                    value="{{ $store_settings->twilio_from }}" id="twilio_from">
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                                <label for="notification_number"
                                                    class="form-label">{{ __('Notification Number') }}</label>
                                                <input class="form-control " name="notification_number"
                                                    type="text"
                                                    value="{{ $store_settings->notification_number }}"
                                                    id="notification_number">
                                                <small>* {{ __('Use country code with your number') }} *</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <div class="card-footer">
                                                <div class="col-sm-12 px-2">
                                                    <div class="d-flex justify-content-end">
                                                        {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pixel_settings" role="tabpanel" aria-labelledby="pills-pixel_setting-tab">
                        <div class="card">
                            <div class="custom-fields">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <div class="">
                                        <h5 class="">{{ __('Pixel Fields Settings') }}</h5>
                                        <small>{{ __('Enter Your Pixel Fields Settings') }}</small>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-icon  btn-primary me-2" data-ajax-popup="true" data-url="{{ route('owner.pixel.create') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create') }}" data-title="{{ __('Create New Pixel') }}">
                                        <i  data-feather="plus"></i>
                                    </a>
                                </div>
                                <div class="card-body table-border-style">
                                    <div class="datatable-container">
                                    
                                        <div class="table-responsive custom-field-table">
                                            
                                            <table class="table dataTable-table" id="pc-dt-simple" data-repeater-list="fields">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>{{ __('Platform') }}</th>
                                                        <th>{{ __('Pixel Id') }}</th>
                    
                                                        <th class="text-right">{{ __('Action') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($PixelFields as  $PixelField)
                                                        <tr>
                                                            <td class="text-capitalize"> 
                                                                {{ $PixelField->platform }}
                                                            </td>
                                                            <td>
                                                                {{ $PixelField->pixel_id }}
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="d-flex">
                                                                    <a class="bs-pass-para btn btn-sm btn-icon bg-light-secondary" href="#" data-title="{{ __('Delete pixel') }}" data-confirm="{{ __('Are You Sure?') }}" data-text="{{ __('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="pixel-delete-form-{{ $PixelField->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Delete') }}">
                                                                        <i class="ti ti-trash f-20"></i>
                                                                    </a>
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['pixel.delete', $PixelField->id], 'id' => 'pixel-delete-form-' . $PixelField->id]) !!}
                                                                    {!! Form::close() !!}
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
                    </div>
                    <div class="tab-pane fade" id="pwa_settings" role="tabpanel" aria-labelledby="pills-pwa_setting-tab">
                        <div class="card">
                            <div class="card-header">
                                <div class="row ">
                                    <div class="col-6">
                                        <h5>{{ __('PWA Settings') }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                @if ($plan->pwa_store == 'on')
                                    {{ Form::model($store_settings, ['route' => ['setting.pwa', $store_settings['id']], 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                                        <div class="form-group col-md-4 ">
                                            <label class="form-check-label"
                                                for="is_checkout_login_required"></label>
                                            <div class="custom-control form-switch">
                                                <input type="checkbox"
                                                    class="form-check-input enable_pwa_store" name="pwa_store"
                                                    id="pwa_store"
                                                    {{ $store_settings['enable_pwa_store'] == 'on' ? 'checked=checked' : '' }}>
                                                {{ Form::label('pwa_store', __('Progressive Web App (PWA)'), ['class' => 'form-check-label mb-3']) }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6 pwa_is_enable">
                                                {{ Form::label('pwa_app_title', __('App Title'), ['class' => 'form-label']) }}
                                                {{ Form::text('pwa_app_title', !empty($pwa_data->name) ? $pwa_data->name : '', ['class' => 'form-control', 'placeholder' => __('App Title')]) }}
                                            </div>

                                            <div class="form-group col-md-6 pwa_is_enable">
                                                {{ Form::label('pwa_app_name', __('App Name'), ['class' => 'form-label']) }}
                                                {{ Form::text('pwa_app_name', !empty($pwa_data->short_name) ? $pwa_data->short_name : '', ['class' => 'form-control', 'placeholder' => __('App Name')]) }}
                                            </div>

                                            <div class="form-group col-md-6 pwa_is_enable">
                                                {{ Form::label('pwa_app_background_color', __('App Background Color'), ['class' => 'form-label']) }}
                                                {{-- {{ Form::text('pwa_app_background_color', , ['class' => 'form-control', 'placeholder' => __('App Background Color')]) }} --}}
                                                {{ Form::color('pwa_app_background_color', !empty($pwa_data->background_color) ? $pwa_data->background_color : '', ['class' => 'form-control color-picker', 'placeholder' => __('18761234567')]) }}
                                            </div>

                                            <div class="form-group col-md-6 pwa_is_enable">
                                                {{ Form::label('pwa_app_theme_color', __('App Theme Color'), ['class' => 'form-label']) }}
                                                {{-- {{ Form::text('pwa_app_theme_color', !empty($pwa_data->theme_color) ? $pwa_data->theme_color : '', ['class' => 'form-control', 'placeholder' => __('App Theme Color')]) }} --}}
                                                {{ Form::color('pwa_app_theme_color', !empty($pwa_data->theme_color) ? $pwa_data->theme_color : '', ['class' => 'form-control color-picker', 'placeholder' => __('18761234567')]) }}
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                                        </div>
                                    {{ Form::close() }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="webhook_settings" role="tabpanel" aria-labelledby="webhook_settings-tab">
                        <div id="webhook_settings" class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6">
                                        <h5>{{ __('Webhook Settings') }}</h5>
                                        <small>{{ __('Edit your Webhook Settings') }}</small>
                                    </div>
                                    <div class="col-6 text-end">
                                        <a href="#" class="btn btn-sm btn-icon  btn-primary me-2" data-size="md" data-ajax-popup="true" data-url="{{ route('webhook.create') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create') }}" data-title="{{ __('Create New Webhook') }}">
                                            <i  data-feather="plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table mb-0 pc-dt-simple ">
                                        <thead>
                                            <tr>
                                            <tr>
                                                <th>{{ __('Module') }}</th>
                                                <th>{{ __('Method') }}</th>
                                                <th>{{ __('Url') }}</th>
                                                <th width="200px"> {{ __('Action') }}</th>
                                            </tr>
                                            </tr>
                                        </thead>
                                        @php
                                            $store = \Auth::user()->current_store;
                                            $webhooks = App\Models\Webhook::where('store_id', $store)->get();
                                        @endphp
                                        @foreach ($webhooks as $webhook)
                                            <tbody>
                                                <td>{{ $webhook->module }}</td>
                                                <td>{{ $webhook->method }}</td>
                                                <td>{{ $webhook->url }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        <a href="#"
                                                            class="btn btn-sm btn-icon bg-light-secondary me-2"
                                                            data-url="{{ route('webhook.edit', $webhook) }}"
                                                            data-ajax-popup="true" data-size="md"
                                                            data-title="{{ __('Edit') }}" data-toggle="tooltip"
                                                            data-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-edit f-20"></i>
                                                        </a>
                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['webhook.destroy', $webhook->id],
                                                            'id' => 'delete-form-' . $webhook->id,
                                                        ]) !!}
                                                        <a class=" show_confirm btn btn-sm btn-icon bg-light-secondary me-2"
                                                            href="#" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="{{ __('Delete') }}">
                                                            <i class="ti ti-trash f-20"></i>
                                                        </a>
                                                        {!! Form::close() !!}
                                                </td>
                                            </tbody>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            @endif
        </div>
        <!-- [ sample-page ] end -->
    </div>
@endsection
@push('script-page')
    <script src="{{ asset('custom/libs/jquery-mask-plugin/dist/jquery.mask.min.js') }}"></script>
    <script>
        function myFunction() {
            var copyText = document.getElementById("myInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            show_toastr('Success', "{{ __('Link copied') }}", 'success');
        }

        $(document).ready(function() {
            setTimeout(function(e) {
                var checked = $("input[type=radio][name='theme_color']:checked");
                $('#themefile').val(checked.attr('data-theme'));
                $('.' + checked.attr('data-theme') + '_img').attr('src', checked.attr('data-imgpath'));
            }, 300);

            if ($('.enable_pwa_store').is(':checked')) {

                $('.pwa_is_enable').removeClass('disabledPWA');
            } else {

                $('.pwa_is_enable').addClass('disabledPWA');
            }

            $('#pwa_store').on('change', function() {
                if ($('.enable_pwa_store').is(':checked')) {

                    $('.pwa_is_enable').removeClass('disabledPWA');
                } else {

                    $('.pwa_is_enable').addClass('disabledPWA');
                }
            });
        });

        $(".color1").click(function() {
            var dataId = $(this).attr("data-id");
            $('#' + dataId).trigger('click');
            var first_check = $('#' + dataId).find('.color-0').trigger("click");
        });
    </script>

    <script type="text/javascript">
        $(document).on("click", '.send_email', function(e) {
            e.preventDefault();
            var title = $(this).attr('data-title');
            var size = 'md';
            var url = $(this).attr('data-url');

            if (typeof url != 'undefined') {
                $("#commonModal .modal-title").html(title);
                $("#commonModal .modal-dialog").addClass('modal-' + size);
                $("#commonModal").modal('show');

                $.post(url, {

                    _token: '{{ csrf_token() }}',
                    mail_driver: $("#mail_driver").val(),
                    mail_host: $("#mail_host").val(),
                    mail_port: $("#mail_port").val(),
                    mail_username: $("#mail_username").val(),
                    mail_password: $("#mail_password").val(),
                    mail_encryption: $("#mail_encryption").val(),
                    mail_from_address: $("#mail_from_address").val(),
                    mail_from_name: $("#mail_from_name").val(),
                }, function(data) {
                    $('#commonModal .modal-body').html(data);
                });
            }
        });

        $(document).on('submit', '#test_email', function(e) {
            e.preventDefault();
            $("#email_sending").show();
            var post = $(this).serialize();
            var url = $(this).attr('action');
            $.ajax({
                type: "post",
                url: url,
                data: post,
                cache: false,
                beforeSend: function() {
                    $('#test_email .btn-create').attr('disabled', 'disabled');
                },
                success: function(data) {
                    if (data.is_success) {
                        show_toastr('Success', data.message, 'success');
                    } else {
                        show_toastr('Error', data.message, 'error');
                    }
                    $("#email_sending").hide();
                    $('#commonModal').modal('hide');
                },
                complete: function() {
                    $('#test_email .btn-create').removeAttr('disabled');
                },
            });
        });
    </script>
    <script type="text/javascript">
        function enablecookie() {
            const element = $('#enable_cookie').is(':checked');
            $('.cookieDiv').addClass('disabledCookie');
            if (element==true) {
                $('.cookieDiv').removeClass('disabledCookie');
                $("#cookie_logging").attr('checked', true);
            } else {
                $('.cookieDiv').addClass('disabledCookie');
                $("#cookie_logging").attr('checked', false);
            }
        }
    </script>
    <script>
        var themescolors = document.querySelectorAll(".themes-color > a");
        for (var h = 0; h < themescolors.length; h++) {
            var c = themescolors[h];

            c.addEventListener("click", function(event) {
                var targetElement = event.target;
                if (targetElement.tagName == "SPAN") {
                    targetElement = targetElement.parentNode;
                }
                var temp = targetElement.getAttribute("data-value");
                removeClassByPrefix(document.querySelector("body"), "theme-");
                document.querySelector("body").classList.add(temp);
            });
        }
        var custthemebg = document.querySelector("#cust-theme-bg");
        custthemebg.addEventListener("click", function() {
            if (custthemebg.checked) {
                document.querySelector(".dash-sidebar").classList.add("transprent-bg");
                document
                    .querySelector(".dash-header:not(.dash-mob-header)")
                    .classList.add("transprent-bg");
            } else {
                document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
                document
                    .querySelector(".dash-header:not(.dash-mob-header)")
                    .classList.remove("transprent-bg");
            }
        });

        var custdarklayout = document.querySelector("#cust-darklayout");
        custdarklayout.addEventListener("click", function() {
            if (custdarklayout.checked) {
                document
                    .querySelector(".m-header > .b-brand > .logo-lg")
                    .setAttribute("src","{{ asset('/storage/uploads/logo/logo-light.png') }}");
                document
                    .querySelector("#main-style-link")
                    .setAttribute("href","{{ asset('assets/css/style-dark.css') }}");
            } else {
                document
                    .querySelector(".m-header > .b-brand > .logo-lg")
                    .setAttribute("src", "{{ asset('/storage/uploads/logo/logo-dark.png') }}");
                document
                    .querySelector("#main-style-link")
                    .setAttribute("href", "{{ asset('assets/css/style.css') }}");
            }
        });

        function removeClassByPrefix(node, prefix) {
            for (let i = 0; i < node.classList.length; i++) {
                let value = node.classList[i];
                if (value.startsWith(prefix)) {
                    node.classList.remove(value);
                }
            }
        }
    </script>

<script>       
    $('.colorPicker').on('click', function(e) {
               $('body').removeClass('custom-color');
               if (/^theme-\d+$/) {
                   $('body').removeClassRegex(/^theme-\d+$/);
               }
               $('body').addClass('custom-color');
               $('.themes-color-change').removeClass('active_color');
               $(this).addClass('active_color');
               const input = document.getElementById("color-picker");
               setColor();
               input.addEventListener("input", setColor);
               function setColor() {
                document.documentElement.style.setProperty('--color-customColor', input.value);
                }
               $(`input[name='color_flag`).val('true');
           });
   
           $('.themes-color-change').on('click', function() {
   
           $(`input[name='color_flag`).val('false');
   
               var color_val = $(this).data('value');
               $('body').removeClass('custom-color');
               if(/^theme-\d+$/)
               {
                   $('body').removeClassRegex(/^theme-\d+$/);                
               }
               $('body').addClass(color_val);
               $('.theme-color').prop('checked', false);
               $('.themes-color-change').removeClass('active_color');
               $('.colorPicker').removeClass('active_color');
               $(this).addClass('active_color');
               $(`input[value=${color_val}]`).prop('checked', true);
           });
           
           $.fn.removeClassRegex = function(regex) {
       return $(this).removeClass(function(index, classes) {
           return classes.split(/\s+/).filter(function(c) {
               return regex.test(c);
           }).join(' ');
       });
   };
   </script>
@endpush
