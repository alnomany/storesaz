@php
$data = DB::table('settings');

$data = $data
    ->where('created_by', '>', 1)
    ->where('store_id', $store->id)
    ->where('name', 'SITE_RTL')
    ->first();
    $clang = session()->get('lang');
    if($clang == 'ar' || $clang == 'he'){
        $data->value = 'on';
    }
@endphp
<!DOCTYPE html>
<html lang="en" dir="{{ empty($data) ? '' : ($data->value == 'on' ? 'rtl' : '' )}}">
@php
    $setting = DB::table('settings')
    ->where('name', 'company_favicon')
    ->where('store_id', $store->id)
    ->first();
    $settings = Utility::settings();
    $getStoreThemeSetting = Utility::getStoreThemeSetting($store->id, $store->theme_dir);
    $getStoreThemeSetting1 = [];

    if (!empty($getStoreThemeSetting['dashboard'])) {
        $getStoreThemeSetting = json_decode($getStoreThemeSetting['dashboard'], true);
        $getStoreThemeSetting1 = Utility::getStoreThemeSetting($store->id, $store->theme_dir);
    }

    if (empty($getStoreThemeSetting)) {
        $path = storage_path() . '/uploads/' . $store->theme_dir . '/' . $store->theme_dir . '.json';
        $getStoreThemeSetting = json_decode(file_get_contents($path), true);
    }
    $themeClass = $store->store_theme;

    $imgpath=\App\Models\Utility::get_file('uploads/');
    $s_logo = \App\Models\Utility::get_file('uploads/store_logo/');
    $metaImage = \App\Models\Utility::get_file('uploads/metaImage');
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   
    <title>@yield('page-title') - {{ $store->tagline ? $store->tagline : config('APP_NAME', ucfirst($store->name)) }}
    </title>

    <!-- Primary Meta Tags -->
    <meta name="title" content="{{ $store->metakeyword }}">
    <meta name="description" content="{{ ucfirst($store->metadesc) }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ env('APP_URL') . '/store/' . $store->slug }}">
    <meta property="og:title" content="{{ $store->metakeyword }}">
    <meta property="og:description" content="{{ ucfirst($store->metadesc) }}">
    <meta property="og:image" content="{{ $metaImage .'/'. $store->metaimage }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ env('APP_URL') . '/store/' . $store->slug }}">
    <meta property="twitter:title" content="{{ $store->metakeyword }}">
    <meta property="twitter:description" content="{{ ucfirst($store->metadesc) }}">
    <meta property="twitter:image" content="{{ $metaImage .'/'. $store->metaimage }}">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset(Storage::url('uploads/logo/') . (!empty($setting->value) ? $setting->value : 'favicon.png' . '?timestamp='. time())) }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/theme9/fonts/fontawesome-free/css/all.min.css') }}">
    @if (isset($data->value) && $data->value == 'on')
        <link rel="stylesheet" href="{{ asset('assets/theme9/css/rtl-main-style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/theme9/css/rtl-responsive.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/theme9/css/main-style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/theme9/css/responsive.css') }}">
    @endif
                {{-- pwa customer app --}}
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="mobile-wep-app-capable" content="yes">
    <meta name="apple-mobile-wep-app-capable" content="yes">
    <meta name="msapplication-starturl" content="/">
    <link rel="apple-touch-icon" href="{{ asset(Storage::url('uploads/logo/') . (!empty($setting->value) ? $setting->value : 'favicon.png' . '?timestamp='. time())) }}" />

    @if ($store->enable_pwa_store == 'on')
    <link rel="manifest" href="{{asset('storage/uploads/customer_app/store_'.$store->id.'/manifest.json')}}" />
    @endif
    @php
        $pwa = $store->pwa_store($store);
    @endphp
    @if (!empty($pwa->theme_color))
        <meta name="theme-color" content="{{ $pwa->theme_color }}" />
    @endif
    @if (!empty($pwa->background_color))
        <meta name="apple-mobile-web-app-status-bar"
            content="{{ $pwa->background_color }}" />
    @endif
    <style>
        .site-header .menu-dropdown {
            min-width: 162px !important;
        }
        .product-bottom {
            padding: 15px;
        }
        .product-slider .product-card {
            padding: 0 15px;
        }

        @media screen and (max-width: 767px){
            [dir=""] .mobile-menu-bottom .language-header-2 .acnav-list {
                right: 0px;
            }
        }
        @media screen and (max-width: 767px){
            [dir="rtl"] .mobile-menu-bottom .language-header-2 .acnav-list {
                left: 0px;
            }
        }
    </style>

    @stack('css-page')
</head>

<body class="{{ !empty($themeClass)? $themeClass : 'theme9-v1' }}">
    @php
        if (!empty(session()->get('lang'))) {
            $currantLang = session()->get('lang');
        } else {
            $currantLang = $store->lang;
        }
        $languages = \App\Models\Utility::languages();
        $langName = \App\Models\Languages::where('code',$currantLang)->first();
        $storethemesetting = \App\Models\Utility::demoStoreThemeSetting($store->id, $store->theme_dir);
    @endphp
    <header class="site-header header-home">
        @if ($storethemesetting['enable_top_bar'] == 'on')
            <div class="notification-bar">
                <div class="container">
                    <p>{{ !empty($storethemesetting['top_bar_title']) ? $storethemesetting['top_bar_title'] : '' }}</p>
                </div>
            </div>
        @endif
        <div class="main-navigationbar">
            <div class="container">
                <div class="navigationbar-row">
                    <div class="main-menu-col">
                        <ul>
                            <li class="menu-link">
                                <a href="{{ route('store.slug', $store->slug) }}">{{ ucfirst($store->name) }}</a>
                            </li>
                            @if (!empty($page_slug_urls))
                                @foreach ($page_slug_urls as $k => $page_slug_url)
                                    @if ($page_slug_url->enable_page_header == 'on')
                                        <li class="menu-link">
                                            <a href="{{ env('APP_URL') . '/page/' . $page_slug_url->slug }}">{{ ucfirst($page_slug_url->name) }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                            @if ($store['blog_enable'] == 'on' && !empty($blog))
                                <li class="menu-link">
                                    <a href="{{ route('store.blog', $store->slug) }}">{{ __('Blog') }}</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="logo-col">
                        <a href="{{ route('store.slug', $store->slug) }}">
                            <img src="{{ $s_logo . (!empty($store->logo) ? $store->logo : 'logo.png') . '?timestamp='. time() }}" alt="">
                        </a>
                    </div>
                    <div class="menu-right">

                        <div class="menu-col-right">
                            <ul>
                                <li>
                                    <a href="#" class="search-header"><i class="fas fa-search"></i></a>
                                </li>
                                <li>
                                    @if (Utility::CustomerAuthCheck($store->slug) == true)
                                        <a href="{{ route('store.wishlist', $store->slug) }}">
                                            <svg width="14" height="11" viewBox="0 0 14 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.28104 10.816L7.00066 9.70588L7.73831 10.8059C7.29288 11.061 6.73058 11.0649 6.28104 10.816ZM5.94502 1.94599C5.40383 1.60628 4.69678 1.29412 3.92584 1.29412C2.15782 1.29412 1.12007 2.4958 1.46598 4.14522C2.05823 6.9692 7.00066 9.70588 7.00066 9.70588C6.28104 10.816 6.28131 10.8162 6.28104 10.816L6.27824 10.8145L6.27363 10.8119L6.25911 10.8038C6.2471 10.797 6.23049 10.7876 6.20954 10.7757C6.16767 10.7518 6.10844 10.7176 6.03417 10.6739C5.88575 10.5864 5.67656 10.46 5.42518 10.3001C4.92434 9.98146 4.24646 9.52329 3.54432 8.96724C2.84615 8.41431 2.09728 7.74366 1.47274 6.99682C0.856963 6.26045 0.297325 5.37361 0.091352 4.39147C-0.143665 3.27085 0.0676085 2.1527 0.80853 1.29418C1.55422 0.430121 2.68948 0 3.92584 0C5.2026 0 6.26391 0.555807 6.92232 0.999458C6.94884 1.01733 6.97495 1.03518 7.00066 1.053C7.02636 1.03518 7.05248 1.01733 7.079 0.999458C7.73741 0.555807 8.79871 0 10.0755 0C12.8375 0 14.4309 2.36483 13.8978 4.44365C13.6534 5.39682 13.0871 6.26486 12.4702 6.99358C11.8447 7.73242 11.1053 8.39982 10.4174 8.95274C9.72601 9.50845 9.06246 9.96769 8.57284 10.2877C8.32717 10.4482 8.12312 10.5751 7.97846 10.6631C7.90609 10.7071 7.84843 10.7414 7.8077 10.7654C7.79739 10.7715 7.78816 10.7769 7.78004 10.7816C7.77213 10.7863 7.76528 10.7903 7.75953 10.7936L7.74545 10.8018L7.741 10.8043L7.73831 10.8059C7.73804 10.806 7.73831 10.8059 7.00066 9.70588C7.00066 9.70588 11.8169 6.94702 12.5353 4.14522C12.8812 2.79622 11.8435 1.29412 10.0755 1.29412C9.30453 1.29412 8.59749 1.60628 8.0563 1.94599C7.41033 2.35148 7.00066 2.79622 7.00066 2.79622C7.00066 2.79622 6.59098 2.35148 5.94502 1.94599Z" fill="white"></path>
                                            </svg>
                                            <span class="count wishlist_count">
                                                @if (!empty($wishlist))
                                                    {{ count($wishlist) }}
                                                @elseif (!empty($cart['wishlist']))
                                                    {{ count($cart['wishlist']) }}
                                                @else
                                                    0
                                                @endif
                                            </span>
                                        </a>
                                    @endif
                                </li>
                                @if (Utility::CustomerAuthCheck($store->slug) == true)
                                    <li class="profile-header has-item">
                                        <a href="javascript:void(0)">
                                            <span class="login-text" style="display: block;">{{ ucFirst(Auth::guard('customers')->user()->name) }}</span>
                                            {{--  <span class="login-text" style="display: none;">Sign In</span>  --}}
                                        </a>
                                        <div class="menu-dropdown">
                                            <ul>
                                                <li>
                                                    <a href="{{ route('store.slug', $store->slug) }}">{{ __('My Dashboard') }}</a>
                                                </li>
                                                <li>
                                                    <a href="#" data-url="{{ route('customer.profile', [$store->slug, \Illuminate\Support\Facades\Crypt::encrypt(Auth::guard('customers')->user()->id)]) }}" data-ajax-popup="true" data-title="{{ __('Edit Profile') }}" data-toggle="modal">
                                                        {{ __('My Profile') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('customer.home', $store->slug) }}"> {{ __('My Orders') }}</a>
                                                </li>
                                                <li>
                                                    @if (Utility::CustomerAuthCheck($store->slug) == false)
                                                        <a href="{{ route('customer.login', $store->slug) }}">{{ __('Sign in') }}</a>
                                                    @else
                                                        <a href="#" onclick="event.preventDefault(); document.getElementById('customer-frm-logout').submit();">{{ __('Logout') }}</a>
                                                        <form id="customer-frm-logout" action="{{ route('customer.logout', $store->slug) }}" method="POST" class="d-none">
                                                        {{ csrf_field() }}
                                                    </form>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @else
                                    <li class="profile-header has-item">
                                        <a href="{{ route('customer.login', $store->slug) }}">
                                            <span class="login-text " style="display: block;">{{ __('Log in') }}</span>
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a href="{{ route('store.cart', $store->slug) }}">
                                        <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.2919 8.13529H5.50403C4.5856 8.13555 3.79748 7.48102 3.6291 6.57815L2.74566 1.79231C2.68975 1.48654 2.42089 1.26607 2.11009 1.27114H0.63557C0.284554 1.27114 0 0.986585 0 0.63557C0 0.284554 0.284554 7.71206e-08 0.63557 7.71206e-08H2.1228C3.04124 -0.000259399 3.82935 0.654274 3.99773 1.55715L4.88118 6.34299C4.93709 6.64876 5.20595 6.86922 5.51675 6.86415H12.2983C12.6091 6.86922 12.8779 6.64876 12.9338 6.34299L13.7347 2.02111C13.769 1.83381 13.7175 1.64099 13.5944 1.49571C13.4713 1.35044 13.2895 1.26802 13.0991 1.27114H5.72013C5.36911 1.27114 5.08456 0.986585 5.08456 0.63557C5.08456 0.284554 5.36911 7.71206e-08 5.72013 7.71206e-08H13.0927C13.6597 -0.000160116 14.1974 0.252022 14.5597 0.688097C14.9221 1.12417 15.0716 1.6989 14.9677 2.25627L14.1669 6.57815C13.9985 7.48102 13.2104 8.13555 12.2919 8.13529ZM8.26264 10.6782C8.26264 9.62515 7.40897 8.77148 6.35593 8.77148C6.00491 8.77148 5.72036 9.05604 5.72036 9.40705C5.72036 9.75807 6.00491 10.0426 6.35593 10.0426C6.70694 10.0426 6.9915 10.3272 6.9915 10.6782C6.9915 11.0292 6.70694 11.3138 6.35593 11.3138C6.00491 11.3138 5.72036 11.0292 5.72036 10.6782C5.72036 10.3272 5.4358 10.0426 5.08479 10.0426C4.73377 10.0426 4.44922 10.3272 4.44922 10.6782C4.44922 11.7312 5.30288 12.5849 6.35593 12.5849C7.40897 12.5849 8.26264 11.7312 8.26264 10.6782ZM12.076 11.9493C12.076 11.5983 11.7914 11.3138 11.4404 11.3138C11.0894 11.3138 10.8048 11.0292 10.8048 10.6782C10.8048 10.3272 11.0894 10.0426 11.4404 10.0426C11.7914 10.0426 12.076 10.3272 12.076 10.6782C12.076 11.0292 12.3605 11.3138 12.7115 11.3138C13.0626 11.3138 13.3471 11.0292 13.3471 10.6782C13.3471 9.62515 12.4934 8.77148 11.4404 8.77148C10.3874 8.77148 9.53369 9.62515 9.53369 10.6782C9.53369 11.7312 10.3874 12.5849 11.4404 12.5849C11.7914 12.5849 12.076 12.3003 12.076 11.9493Z" fill="white"></path>
                                        </svg>
                                        <span class="count" id="shoping_counts"> {{ !empty($total_item) ? $total_item : '0' }}</span>
                                    </a>
                                </li>
                                <li class="language-header has-item">
                                    <a href="#">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" xml:space="preserve">
                                            <path d="M251.823 446.874h15.852v-.39c99.829-6.058 179.202-89.161 179.202-190.481 0-101.327-79.373-184.43-179.202-190.481-2.478-.151-4.97-.233-7.473-.283-1.396-.038-2.793-.113-4.202-.113s-2.806.075-4.202.113c-2.503.05-4.995.132-7.473.283-99.829 6.051-179.202 89.154-179.202 190.481 0 101.32 79.373 184.423 179.202 190.481v.39h7.498zm38.649-26.936c27.93-27.389 48.229-55.966 60.741-85.55h52.775c-22.972 43.204-64.3 75.222-113.516 85.55zm133.056-163.935c0 19.281-3.314 37.787-9.336 55.042h-54.777c4.719-16.776 7.084-33.83 6.945-51.16-.164-20.859-3.85-40.592-9.713-58.929h57.545a166.774 166.774 0 0 1 9.336 55.047zm-19.539-78.391h-56.273c-16.406-37-40.436-66.691-58.715-85.852 49.87 10.001 91.788 42.215 114.988 85.852zm-136.314-74.077c15.626 15.89 37.78 41.737 54.022 74.076h-54.022v-74.076zm0 97.421h64.138c6.719 18.361 11.047 38.265 11.197 59.249.125 17.186-2.598 34.139-8.027 50.84h-67.308V200.956zm0 133.432h57.847c-12.568 25.778-31.842 50.877-57.847 75.127v-75.127zm-23.35-23.343h-67.308c-5.429-16.701-8.152-33.654-8.027-50.84.151-20.984 4.479-40.888 11.197-59.249h64.137v110.089zm0-133.433h-54.022c16.242-32.339 38.396-58.193 54.022-74.083v74.083zM223.001 91.76c-18.28 19.161-42.31 48.852-58.715 85.852h-56.274c23.198-43.637 65.118-75.851 114.989-85.852zM88.473 256.003c0-19.28 3.315-37.787 9.335-55.047h57.545c-5.862 18.336-9.542 38.069-9.712 58.929-.138 17.33 2.227 34.384 6.945 51.16H97.808a166.739 166.739 0 0 1-9.335-55.042zm19.538 78.385h52.777c12.518 29.584 32.811 58.161 60.747 85.55-49.223-10.328-90.551-42.346-113.524-85.55zm78.467 0h57.847v75.127c-26.004-24.25-45.278-49.348-57.847-75.127z" fill="#ffffff" class="fill-000000"></path>
                                        </svg>
                                        <span class="lbl-text"> {{ ucFirst($langName->fullName) }}</span>
                                    </a>
                                    <div class="menu-dropdown">
                                        <ul>
                                            @foreach ($languages as $code => $language)
                                                <li><a href="{{ route('change.languagestore', [$store->slug, $code]) }}" class="@if ($language == $currantLang) active-language text-primary @endif">{{  ucFirst($language) }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="mobile-menu mobile-only">
                        <button class="mobile-menu-button" id="menu">
                            <div class="one"></div>
                            <div class="two"></div>
                            <div class="three"></div>
                        </button>
                    </div>
                    <div class="mobile-menu-wrapper">
                        <div class="menu-close-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="18" viewBox="0 0 20 18">
                                <path fill="#24272a"
                                    d="M19.95 16.75l-.05-.4-1.2-1-5.2-4.2c-.1-.05-.3-.2-.6-.5l-.7-.55c-.15-.1-.5-.45-1-1.1l-.1-.1c.2-.15.4-.35.6-.55l1.95-1.85 1.1-1c1-1 1.7-1.65 2.1-1.9l.5-.35c.4-.25.65-.45.75-.45.2-.15.45-.35.65-.6s.3-.5.3-.7l-.3-.65c-.55.2-1.2.65-2.05 1.35-.85.75-1.65 1.55-2.5 2.5-.8.9-1.6 1.65-2.4 2.3-.8.65-1.4.95-1.9 1-.15 0-1.5-1.05-4.1-3.2C3.1 2.6 1.45 1.2.7.55L.45.1c-.1.05-.2.15-.3.3C.05.55 0 .7 0 .85l.05.35.05.4 1.2 1 5.2 4.15c.1.05.3.2.6.5l.7.6c.15.1.5.45 1 1.1l.1.1c-.2.15-.4.35-.6.55l-1.95 1.85-1.1 1c-1 1-1.7 1.65-2.1 1.9l-.5.35c-.4.25-.65.45-.75.45-.25.15-.45.35-.65.6-.15.3-.25.55-.25.75l.3.65c.55-.2 1.2-.65 2.05-1.35.85-.75 1.65-1.55 2.5-2.5.8-.9 1.6-1.65 2.4-2.3.8-.65 1.4-.95 1.9-1 .15 0 1.5 1.05 4.1 3.2 2.6 2.15 4.3 3.55 5.05 4.2l.2.45c.1-.05.2-.15.3-.3.1-.15.15-.3.15-.45z">
                                </path>
                            </svg>
                        </div>
                        <div class="mobile-menu-bar">
                            <ul>
                                <li class="menu-lnk">
                                    <a href="{{ route('store.slug', $store->slug) }}">{{ ucfirst($store->name) }}</a>
                                </li>

                                @if (!empty($page_slug_urls))
                                     @foreach ($page_slug_urls as $k => $page_slug_url)
                                         @if ($page_slug_url->enable_page_header == 'on')
                                             <li class="menu-lnk">
                                                 <a href="{{ env('APP_URL') . '/page/' . $page_slug_url->slug }}">{{ ucfirst($page_slug_url->name) }}</a>
                                             </li>
                                         @endif
                                     @endforeach
                                @endif
                                @if ($store['blog_enable'] == 'on' && !empty($blog))
                                 <li class="menu-lnk">
                                     <a href="{{ route('store.blog', $store->slug) }}">{{ __('Blog') }}</a>
                                 </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @if (Utility::CustomerAuthCheck($store->slug) == true)
                <div class="mobile-menu-bottom">
                    <ul>
                        <li class="set has-children">
                            <a href="javascript:;" class="acnav-label">
                                <span>{{ ucFirst(Auth::guard('customers')->user()->name) }}</span>
                            </a>
                            <div class="acnav-list">
                                <ul>
                                    <li>
                                        <a href="{{ route('store.slug', $store->slug) }}">{{ __('My Dashboard') }}</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" data-url="{{ route('customer.profile', [$store->slug, \Illuminate\Support\Facades\Crypt::encrypt(Auth::guard('customers')->user()->id)]) }}" data-ajax-popup="true" data-title="{{ __('Edit Profile') }}" data-toggle="modal">
                                            {{ __('My Profile') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('customer.home', $store->slug) }}"> {{ __('My Orders') }}</a>
                                    </li>
                                    <li>
                                        @if (Utility::CustomerAuthCheck($store->slug) == false)
                                            <a href="{{ route('customer.login', $store->slug) }}">{{ __('Sign in') }}</a>
                                        @else
                                            <a href="#" onclick="event.preventDefault(); document.getElementById('customer-frm-logout').submit();">{{ __('Logout') }}</a>
                                            <form id="customer-frm-logout" action="{{ route('customer.logout', $store->slug) }}" method="POST" class="d-none">
                                            {{ csrf_field() }}
                                        </form>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="set has-children language-header-2">
                            <a href="javascript:;" class="acnav-label">
                                <span> {{ ucFirst($langName->fullName) }}</span>
                            </a>
                            <div class="acnav-list">
                                <ul>
                                    @foreach ($languages as $code => $language)
                                        <li><a href="{{ route('change.languagestore', [$store->slug, $code]) }}" class="@if ($language == $currantLang) active-language text-primary @endif">{{  ucFirst($language) }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            @else
                <div class="mobile-menu-bottom">
                    <ul>
                        <li class="set has-children">
                            <a href="{{ route('customer.login', $store->slug) }}" class="acnav-label">
                                <span>{{ __('Log in') }}</span>
                            </a>
                        </li>
                        <li class="set has-children language-header-2">
                            <a href="javascript:;" class="acnav-label">
                                <span> {{ ucFirst($langName->fullName) }}</span>
                            </a>
                            <div class="acnav-list">
                                <ul>
                                    @foreach ($languages as $code => $language)
                                        <li><a href="{{ route('change.languagestore', [$store->slug, $code]) }}" class="@if ($language == $currantLang) active-language text-primary @endif">{{  ucFirst($language) }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
    </header>
    @yield('content')
    <footer class="footer">
        <div class="container">
            <div class="row footer-top">
                <div class="col-lg-5 col-12 footer-link-1">
                    <div class="footer-col footer-subscribe-col">
                        <div class="footer-widget">
                            @foreach ($getStoreThemeSetting as $storethemesetting)
                                @if (isset($storethemesetting['section_name']) && $storethemesetting['section_name'] == 'Home-Email-Subscriber' && $storethemesetting['section_enable'] == 'on')
                                    @php
                                        $SubscriberTitle_key = array_search('Subscriber Title', array_column($storethemesetting['inner-list'], 'field_name'));
                                        $SubscriberTitle = $storethemesetting['inner-list'][$SubscriberTitle_key]['field_default_text'];
                                    @endphp
                                    <div class="footer-subscribe-form">
                                        <h4> {{ $SubscriberTitle }}
                                        </h4>
                                        {{ Form::open(['route' => ['subscriptions.store_email', $store->id], 'method' => 'POST']) }}
                                            <div class="input-wrapper">
                                                {{ Form::email('email', null, ['placeholder' => __('Enter Your Email Address')]) }}
                                                <button type="submit" class="btn"><svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_8_776)">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.5 12C0.5 11.4477 0.947715 11 1.5 11L21.0858 11L14.7932 4.7071C14.4026 4.31657 14.4027 3.6834 14.7932 3.29288C15.1837 2.90236 15.8169 2.90238 16.2074 3.29291L24.2071 11.2929C24.5976 11.6834 24.5976 12.3166 24.2071 12.7071L16.2074 20.7071C15.8169 21.0976 15.1837 21.0976 14.7932 20.7071C14.4027 20.3166 14.4026 19.6834 14.7932 19.2929L21.0858 13L1.5 13C0.947715 13 0.5 12.5523 0.5 12Z" fill="white"></path>
                                                    </g>
                                                </svg></button>
                                            </div>
                                            <p>{{ __('Enter your address and accept the activation link') }}</p>
                                        {{ Form::close() }}
                                    </div>
                                @endif
                            @endforeach

                        </div>
                    </div>
                </div>
                @if ($getStoreThemeSetting[9]['section_enable'] == 'on')
                    @foreach ($getStoreThemeSetting as $key => $storethemesetting)
                        @foreach ($storethemesetting['inner-list'] as $keyy => $theme)
                            @if ($theme['field_name'] == 'Enable Quick Link 1')
                                @if ($getStoreThemeSetting[9]['inner-list'][0]['field_default_text'] == 'on')
                                    @if (!empty($getStoreThemeSetting[9]))
                                        @if ((isset($getStoreThemeSetting[9]['section_enable']) && $getStoreThemeSetting[9]['section_enable'] == 'on') || $getStoreThemeSetting[9]['inner-list'][1]['field_default_text'])
                                            <div class="col-lg-2 col-12 footer-link-2">
                                                <h6> {{ __($getStoreThemeSetting[9]['inner-list'][1]['field_default_text']) }}
                                                </h6>
                                                <ul>
                                                    @if (isset($getStoreThemeSetting[10]['homepage-header-quick-link-name-1'], $getStoreThemeSetting[10]['homepage-header-quick-link-1']))
                                                        @foreach ($getStoreThemeSetting[10]['homepage-header-quick-link-name-1'] as $name_key => $storethemesettingname)
                                                            @foreach ($getStoreThemeSetting[10]['homepage-header-quick-link-1'] as $link_key => $storethemesettinglink)
                                                                @if ($name_key == $link_key)
                                                                    <li>
                                                                        <a href="{{ $storethemesettinglink }}">{{ $storethemesettingname }}</a>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                    @else
                                                        @for ($i = 0; $i < $getStoreThemeSetting[10]['loop_number']; $i++)
                                                            <li>
                                                                <a href="{{ $getStoreThemeSetting[10]['inner-list'][1]['field_default_text'] }}">{{ $getStoreThemeSetting[10]['inner-list'][0]['field_default_text'] }}</a>
                                                            </li>
                                                        @endfor
                                                    @endif
                                                </ul>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                                @if ($getStoreThemeSetting[11]['inner-list'][0]['field_default_text'] == 'on')
                                    @if (!empty($getStoreThemeSetting[11]))
                                        @if ((isset($getStoreThemeSetting[11]['section_enable']) && $getStoreThemeSetting[11]['section_enable'] == 'on') || $getStoreThemeSetting[11]['inner-list'][1]['field_default_text'])
                                            <div class="col-lg-2 col-12 footer-link-2">
                                                <h6> {{ __($getStoreThemeSetting[11]['inner-list'][1]['field_default_text']) }}
                                                </h6>
                                                <ul>
                                                    @if (isset($getStoreThemeSetting[12]['homepage-header-quick-link-name-2'], $getStoreThemeSetting[12]['homepage-header-quick-link-2']))
                                                        @foreach ($getStoreThemeSetting[12]['homepage-header-quick-link-name-2'] as $name_key => $storethemesettingname)
                                                            @foreach ($getStoreThemeSetting[12]['homepage-header-quick-link-2'] as $link_key => $storethemesettinglink)
                                                                @if ($name_key == $link_key)
                                                                    <li>
                                                                        <a href="{{ $storethemesettinglink }}">{{ $storethemesettingname }}</a>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                    @else
                                                    @for ($i = 0; $i < $getStoreThemeSetting[12]['loop_number']; $i++)
                                                        <li>
                                                            <a href="{{ $getStoreThemeSetting[12]['inner-list'][1]['field_default_text'] }}">{{ $getStoreThemeSetting[12]['inner-list'][0]['field_default_text'] }} </a>
                                                        </li>
                                                    @endfor
                                                    @endif
                                                </ul>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                                @if ($getStoreThemeSetting[13]['inner-list'][1]['field_default_text'] == 'on')
                                    @if (!empty($getStoreThemeSetting[13]))
                                        @if ((isset($getStoreThemeSetting[13]['section_enable']) && $getStoreThemeSetting[13]['section_enable'] == 'on') || $getStoreThemeSetting[13]['inner-list'][1]['field_default_text'])
                                        <div class="col-lg-2 col-12 footer-link-2">
                                            <h6> {{ __($getStoreThemeSetting[13]['inner-list'][0]['field_default_text']) }}
                                            </h6>
                                            <ul>
                                                @if (isset($getStoreThemeSetting[14]['homepage-header-quick-link-name-3'], $getStoreThemeSetting[14]['homepage-header-quick-link-3']))
                                                    @foreach ($getStoreThemeSetting[14]['homepage-header-quick-link-name-3'] as $name_key => $storethemesettingname)
                                                        @foreach ($getStoreThemeSetting[14]['homepage-header-quick-link-3'] as $link_key => $storethemesettinglink)
                                                            @if ($name_key == $link_key)
                                                                <li>
                                                                    <a href="{{ $storethemesettinglink }}">{{ $storethemesettingname }}</a>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                @else
                                                    @for ($i = 0; $i < $getStoreThemeSetting[14]['loop_number']; $i++)
                                                        <li>
                                                            <a href="{{ $getStoreThemeSetting[14]['inner-list'][1]['field_default_text'] }}">{{ $getStoreThemeSetting[14]['inner-list'][0]['field_default_text'] }}</a>
                                                        </li>
                                                    @endfor
                                                @endif
                                             </ul>
                                        </div>
                                        @endif
                                    @endif
                                @endif
                            @endif
                        @endforeach
                    @endforeach
                @endif
            </div>
            <div class="row footer-bottom">
                <div class="col-md-6 col-12">
                    @if ($getStoreThemeSetting[15]['section_enable'] == 'on')
                        <p>{{ $getStoreThemeSetting[15]['inner-list'][0]['field_default_text'] }}</p>
                    @endif
                </div>
                <div class="col-md-6 col-12">
                    @if ($getStoreThemeSetting[15]['section_enable'] == 'on')
                        <ul class="social-link">
                            @if (isset($getStoreThemeSetting[16]['homepage-footer-2-social-icon']) || isset($getStoreThemeSetting[16]['homepage-footer-2-social-link']))
                                @if (isset($getStoreThemeSetting[16]['inner-list'][1]['field_default_text']) && isset($getStoreThemeSetting[16]['inner-list'][0]['field_default_text']))
                                    @foreach ($getStoreThemeSetting[16]['homepage-footer-2-social-icon'] as $icon_key => $storethemesettingicon)
                                        @foreach ($getStoreThemeSetting[16]['homepage-footer-2-social-link'] as $link_key => $storethemesettinglink)
                                            @if ($icon_key == $link_key)
                                                <li>
                                                    <a href="{{ $storethemesettinglink }}">
                                                        {!! $storethemesettingicon !!}
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endif
                            @else
                                @for ($i = 0; $i < $getStoreThemeSetting[16]['loop_number']; $i++)
                                    @if (isset($getStoreThemeSetting[16]['inner-list'][1]['field_default_text']) && isset($getStoreThemeSetting[16]['inner-list'][0]['field_default_text']))
                                    <li>
                                        <a href="{{ $getStoreThemeSetting[16]['inner-list'][1]['field_default_text'] }}" target="_blank">
                                            {!! $getStoreThemeSetting[16]['inner-list'][0]['field_default_text'] !!}
                                        </a>
                                    </li>
                                    @endif
                                @endfor
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </footer>
    @if ($getStoreThemeSetting[15]['section_enable'] == 'on')
        <script>
            {!! $getStoreThemeSetting[17]['inner-list'][0]['field_default_text'] !!}
        </script>
    @endif
    <div class="modal fade modal-popup" id="commonModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-inner lg-dialog" role="document">
            <div class="modal-content">
                
                <div class="modal-header  popup-header align-items-center">
                    <div class="modal-title">
                        <h6 class="mb-0" id="modelCommanModelLabel"></h6>
                    </div>
                    <button type="button" class="close close-button" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
    <div id="omnisearch" class="omnisearch">
        <div class="container">
            <!-- Search form -->
            <form class="omnisearch-form" action="{{ route('store.categorie.product', [$store->slug, 'Start shopping']) }}" method="get">
                @csrf
                <input type="hidden" name="_token" value="">
                  <div class="form-group">
                    <div class="input-group">
                       <button type="submit"><i class="fas fa-search"></i></button>
                        <input type="text" name="search_data" class="form-control form-control-flush" placeholder="Type your product...">
                        <div class="close-btn" style=" padding-right: 15px;padding-left: 15px;  ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="18" viewBox="0 0 20 18">
                                <path fill="#24272a" d="M19.95 16.75l-.05-.4-1.2-1-5.2-4.2c-.1-.05-.3-.2-.6-.5l-.7-.55c-.15-.1-.5-.45-1-1.1l-.1-.1c.2-.15.4-.35.6-.55l1.95-1.85 1.1-1c1-1 1.7-1.65 2.1-1.9l.5-.35c.4-.25.65-.45.75-.45.2-.15.45-.35.65-.6s.3-.5.3-.7l-.3-.65c-.55.2-1.2.65-2.05 1.35-.85.75-1.65 1.55-2.5 2.5-.8.9-1.6 1.65-2.4 2.3-.8.65-1.4.95-1.9 1-.15 0-1.5-1.05-4.1-3.2C3.1 2.6 1.45 1.2.7.55L.45.1c-.1.05-.2.15-.3.3C.05.55 0 .7 0 .85l.05.35.05.4 1.2 1 5.2 4.15c.1.05.3.2.6.5l.7.6c.15.1.5.45 1 1.1l.1.1c-.2.15-.4.35-.6.55l-1.95 1.85-1.1 1c-1 1-1.7 1.65-2.1 1.9l-.5.35c-.4.25-.65.45-.75.45-.25.15-.45.35-.65.6-.15.3-.25.55-.25.75l.3.65c.55-.2 1.2-.65 2.05-1.35.85-.75 1.65-1.55 2.5-2.5.8-.9 1.6-1.65 2.4-2.3.8-.65 1.4-.95 1.9-1 .15 0 1.5 1.05 4.1 3.2 2.6 2.15 4.3 3.55 5.05 4.2l.2.45c.1-.05.2-.15.3-.3.1-.15.15-.3.15-.45z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- checkout modal --}}
    <div class="modal-popup top-center fade" id="Checkout">
        <div class="modal-dialog-inner modal-md">
            <div class="popup-content">
                <div class="popup-header">
                    <h4>{{ __('Checkout As Guest Or Login') }}</h4>
                    <div class="close-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" fill="none">
                            <path d="M27.7618 25.0008L49.4275 3.33503C50.1903 2.57224 50.1903 1.33552 49.4275 0.572826C48.6647 -0.189868 47.428 -0.189965 46.6653 0.572826L24.9995 22.2386L3.33381 0.572826C2.57102 -0.189965 1.3343 -0.189965 0.571605 0.572826C-0.191089 1.33562 -0.191186 2.57233 0.571605 3.33503L22.2373 25.0007L0.571605 46.6665C-0.191186 47.4293 -0.191186 48.666 0.571605 49.4287C0.952952 49.81 1.45285 50.0007 1.95275 50.0007C2.45266 50.0007 2.95246 49.81 3.3339 49.4287L24.9995 27.763L46.6652 49.4287C47.0465 49.81 47.5464 50.0007 48.0463 50.0007C48.5462 50.0007 49.046 49.81 49.4275 49.4287C50.1903 48.6659 50.1903 47.4292 49.4275 46.6665L27.7618 25.0008Z" fill="white"></path>
                        </svg>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="btn-group">
                        <a href="{{ route('customer.login', $store->slug) }}" class="btn">{{ __('Countinue to sign in') }}</a>
                        <a href="{{ route('user-address.useraddress', $store->slug) }}" class="btn">{{ __('Countinue as guest') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($settings['enable_cookie'] == 'on')
        @include('layouts.cookie_consent')
    @endif
    <script src="{{asset('custom/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/bootstrap.min.js')}}"></script>
    <script src="{{ asset('custom/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/theme9/js/slick.min.js') }}" defer="defer"></script>
    <script src="{{ asset('custom/libs/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
                {{-- pwa customer app --}}
    @if ($store->enable_pwa_store == 'on')
        <script type="text/javascript">

        const container = document.querySelector("body")

        const coffees = [];

        if ("serviceWorker" in navigator) {

            window.addEventListener("load", function() {
                navigator.serviceWorker
                    .register( "{{asset("serviceWorker.js")}}" )
                    .then(res => console.log(""))
                    .catch(err => console.log("service worker not registered", err))

            })
        }

        </script>
    @endif
    @if (isset($data->value) && $data->value == 'on')
        <script src="{{ asset('assets/theme9/js/rtl-custom.js') }}" defer="defer"></script>
    @else
        <script src="{{ asset('assets/theme9/js/custom.js') }}" defer="defer"></script>
    @endif

    <script>
        var dataTabelLang = {
            paginate: {
                previous: "{{ 'Previous' }}",
                next: "{{ 'Next' }}"
            },
            lengthMenu: "{{ 'Show' }} MENU {{ 'entries' }}",
            zeroRecords: "{{ 'No data available in table' }}",
            info: "{{ 'Showing' }} START {{ 'to' }} END {{ 'of' }} TOTAL {{ 'entries' }}",
            infoEmpty: " ",
            search: "{{ 'Search:' }}"
        }
    </script>

    <script src="{{ asset('custom/js/custom.js') }}"></script>


    @if (App\Models\Utility::getValByName('gdpr_cookie') == 'on')
        <script type="text/javascript">
            var defaults = {
                'messageLocales': {
                    /*'en': 'We use cookies to make sure you can have the best experience on our website. If you continue to use this site we assume that you will be happy with it.'*/
                    'en': "{{ App\Models\Utility::getValByName('cookie_text') }}"
                },
                'buttonLocales': {
                    'en': 'Ok'
                },
                'cookieNoticePosition': 'bottom',
                'learnMoreLinkEnabled': false,
                'learnMoreLinkHref': '/cookie-banner-information.html',
                'learnMoreLinkText': {
                    'it': 'Saperne di più',
                    'en': 'Learn more',
                    'de': 'Mehr erfahren',
                    'fr': 'En savoir plus'
                },
                'buttonLocales': {
                    'en': 'Ok'
                },
                'expiresIn': 30,
                'buttonBgColor': '#d35400',
                'buttonTextColor': '#fff',
                'noticeBgColor': '#000',
                'noticeTextColor': '#fff',
                'linkColor': '#009fdd'
            };
        </script>
        <script src="{{ asset('custom/js/cookie.notice.js') }}"></script>
    @endif

    @stack('script-page')

    @if (Session::has('success'))
        <script>
            show_toastr('{{ __('Success') }}', '{!! session('success') !!}', 'success');
        </script>
        {{ Session::forget('success') }}
    @endif
    @if (Session::has('error'))
        <script>
            show_toastr('{{ __('Error') }}', '{!! session('error') !!}', 'error');
        </script>
        {{ Session::forget('error') }}
    @endif

    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $store->google_analytic }}"></script>

    {!! $store->storejs !!}
    <script>
        $(".add_to_cart").click(function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var variants = [];
            $(".variant-selection").each(function(index, element) {
                variants.push(element.value);
            });

            if (jQuery.inArray('', variants) != -1) {
                show_toastr('Error', "{{ __('Please select all option.') }}", 'error');
                return false;
            }
            var variation_ids = $('#variant_id').val();
            $.ajax({
                url: '{{ route('user.addToCart', ['__product_id', $store->slug, 'variation_id']) }}'
                    .replace(
                        '__product_id', id).replace('variation_id', variation_ids ?? 0),
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    variants: variants.join(' : '),
                },
                success: function(response) {
                    if (response.status == "Success") {
                        show_toastr('Success', response.success, 'success');
                        $("#shoping_counts").html(response.item_count);
                    } else {
                        show_toastr('Error', response.error, 'error');
                    }
                },
                error: function(result) {
                    console.log('error');
                }
            });
        });

        $(document).on('click', '.add_to_wishlist', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            $.ajax({
                type: "POST",
                url: '{{ route('store.addtowishlist', [$store->slug, '__product_id']) }}'.replace(
                    '__product_id', id),
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    console.log(response)
                    if (response.status == "Success") {
                        show_toastr('Success', response.message, 'success');
                        $('.wishlist_' + response.id).removeClass('add_to_wishlist');
                        $('.wishlist_' + response.id).html('<i class="fas fa-heart"></i>');
                        $('.wishlist_count').html(response.count);
                    } else {
                        show_toastr('Error', response.error, 'error');
                    }
                },
                error: function(result) {}
            });
        });

        $(document).on('change', '#pro_variants_name', function() {
            var variants = [];
            $(".variant-selection").each(function(index, element) {
                variants.push(element.value);
            });
            if (variants.length > 0) {
                $.ajax({
                    url: '{{ route('get.products.variant.quantity') }}',
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        variants: variants.join(' : '),
                        product_id: $('#product_id').val()
                    },

                    success: function(data) {
                        $('.variation_price').html(data.price);
                        $('#variant_id').val(data.variant_id);
                        $('#variant_qty').val(data.quantity);
                    }
                });
            }
        });
    </script>
    <script>
        $(".productTab").click(function(e) {
            e.preventDefault();
            $('.productTab').removeClass('active')

        });

        $("#pro_scroll").click(function() {
            $('html, body').animate({
                scrollTop: $("#pro_items").offset().top
            }, 1000);
        });
    </script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', '{{ $store->google_analytic }}');
    </script>

    <!-- Facebook Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ $store->fbpixel_code }}');
        fbq('track', 'PageView');
    </script>


    <script type="text/javascript">
        // $(function() {
        //     $(".drop-down__button ").on("click", function(e) {
        //         $(".drop-down").addClass("drop-down--active");
        //         e.stopPropagation()
        //     });
        //     $(document).on("click", function(e) {
        //         if ($(e.target).is(".drop-down") === false) {
        //             $(".drop-down").removeClass("drop-down--active");
        //         }
        //     });
        // });

        // drop-down__button
        $("#dropDown").click(function() {
            $(".drop-down__menu-box").slideToggle();
        });
    </script>
    <script>
        $('.close-btn').click(function() {
            $('#navbar-top-main').hide();
        });
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=0000&ev=PageView&noscript={{ $store->fbpixel_code }}" /></noscript>
</body>
</html>
