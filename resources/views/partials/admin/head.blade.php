@php
    $logo = asset(Storage::url('uploads/logo/'));
    $favicon = \App\Models\Utility::getValByName('company_favicon');
    $settings = Utility::settings();
    $title_text = \App\Models\Utility::getValByName('title_text');
@endphp
<head>
    <title> 
        {{ $title_text ? $title_text : env('APP_NAME', 'StoreGo SaaS') }} - @yield('page-title')
    </title>
    
    <meta charset="utf-8" dir="{{ $settings['SITE_RTL'] == 'on' ? 'rtl' : '' }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="{{ env('APP_NAME') }} - Online Store Builder" />
    <meta name="keywords" content="{{ env('APP_NAME') }}" />
    <meta name="author" content="Rajodiya Infotech" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon icon -->
    @if (\Auth::user()->type == 'super admin')
        <link rel="icon" href="{{ $logo . '/favicon.png' . '?timestamp='. time() }}" type="image/x-icon" />
    @else
        <link rel="icon" href="{{ $logo . '/' . (isset($favicon) && !empty($favicon) ? $favicon : 'favicon.png') . '?timestamp='. time() }}" type="image/x-icon" />
    @endif
    

    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/libs/animate.css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/libs/select2/dist/css/select2.min.css') }}">
     <link rel="stylesheet" href="{{ asset('custom/libs/@fortawesome/fontawesome-free/css/all.min.css') }}">
     <link rel="stylesheet" href="{{ asset('custom/libs/@fancyapps/fancybox/dist/jquery.fancybox.min.css') }}">
     <link rel="stylesheet" href="{{ asset('custom/libs/summernote/summernote-bs4.css') }}">
     <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-switch-button.min.css') }}">
    <!-- vendor css -->
    {{--  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">  --}}
    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    <link rel="stylesheet" href="{{ asset('custom/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}">
    @if (isset($settings['SITE_RTL']) && $settings['SITE_RTL'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
    @endif
    @if ($settings['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}" id="main-style-link">
        <link rel="stylesheet" href="{{ asset('custom/css/custom-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    @endif

    <style>
        :root {
            --color-customColor: <?= $color ?>;    
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/custom-color.css') }}">
    
    @stack('css')
</head>