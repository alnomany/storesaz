@extends('layouts.admin')
@php
    $logo = asset(Storage::url('uploads/logo/'));
    $company_logo = \App\Models\Utility::getValByName('company_logo');
    $company_favicon = \App\Models\Utility::getValByName('company_favicon');
    $store_logo = asset(Storage::url('uploads/store_logo/'));
    $lang = \App\Models\Utility::getValByName('default_language');
   // if (Auth::user()->type == 'Owner') {
        $store_lang = $store->lang;
   // }
    
@endphp
@section('page-title')
    {{ __('Store Theme Setting') }}
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('settings') }}">{{ __(' Store Settings') }}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ __('Store Theme Setting') }}</li>
@endsection
@section('action-btn')
    <ul class="nav nav-pills cust-nav rounded  mb-3" id="pills-tab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="header" data-bs-toggle="pill" href="#pills-header" role="tab"
                aria-controls="pills-header" aria-selected="true">{{ __('Header') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="home" data-bs-toggle="pill" href="#pills-home" role="tab"
                aria-controls="pills-home" aria-selected="false">{{ __('Home') }}</a>
        </li>
        @if($theme !== 'theme3' && $theme !== 'theme8' && $theme !== 'theme9')
        <li class="nav-item">
            <a class="nav-link" id="brand" data-bs-toggle="pill" href="#pills-brand" role="tab"
                aria-controls="pills-brand" aria-selected="false">{{ __('Brand') }}</a>
        </li>
        @endif
        @if($theme == 'theme8' || $theme == 'theme7' || $theme == 'theme9' ||$theme == 'theme10')
            <li class="nav-item">
                <a class="nav-link" id="product" data-bs-toggle="pill" href="#pills-product" role="tab"
                    aria-controls="pills-product" aria-selected="false">{{ __('Product') }}</a>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link" id="footer" data-bs-toggle="pill" href="#pills-footer" role="tab"
                aria-controls="pills-footer" aria-selected="false">{{ __('Footer') }}</a>
        </li>
    </ul>
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{ asset('custom/libs/summernote/summernote-bs4.css') }}">
    <style>
        hr {
            margin: 8px;
        }
    </style>
@endpush
@push('css')
<style>
    [dir="rtl"] .custom-switch-v1.form-switch {
        padding-left: 0 !important;
    }
</style>
@endpush
@section('content')
    <div class="row">
        @if (Auth::user()->type !== 'super admin')
        <!-- [ sample-page ] start -->
        {{ Form::open(['route' => ['store.storeeditproducts', [$store->slug, $theme]], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
            <div class="col-sm-12">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade active show" id="pills-header" role="tabpanel" aria-labelledby="pills-header">
                       
                            <div class="row">
                                <div class="col-12 text-lg-end">
                                    <button type="submit" class="btn mb-3  btn-primary submit_all"> <i data-feather="check-circle"
                                            class="me-2"></i>{{ __('Save Changes') }}</button>
                                </div>
                                @if ($theme == 'theme1' || $theme == 'theme6' || $theme == 'theme7' || $theme == 'theme8' || $theme == 'theme9' || $theme == 'theme10')
                                    @php
                                        $storethemesetting = \App\Models\Utility::demoStoreThemeSetting($store->id, $store->theme_dir);
                                    @endphp
                                    <div class="col-lg-6">
                                        <div class="d-flex mb-3 align-items-center justify-content-between">
                                            <h4 class="mb-0">{{ __('Top Bar Setting') }}</h4>
                                            <div class="form-check form-switch custom-switch-v1">
                                                @if (!empty($storethemesetting['enable_top_bar']))
                                                    <input type="checkbox" class="form-check-input input-primary"
                                                        name="enable_top_bar" id="enable_top_bar"
                                                        {{ $storethemesetting['enable_top_bar'] == 'on' ? 'checked="checked"' : '' }}>
                                                @else
                                                    <input type="checkbox" class="form-check-input input-primary"
                                                        name="enable_top_bar" id="enable_top_bar">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="card border border-primary shadow-none">
                                            <div class="card-body p-3">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            {{ Form::label('top_bar_title', __('Top Bar Title'), ['class' => 'col-form-label']) }}
                                                            {{ Form::text('top_bar_title', !empty($storethemesetting['top_bar_title']) ? $storethemesetting['top_bar_title'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Top Bar Title')]) }}
                                                            @error('top_bar_title')
                                                                <span class="invalid-top_bar_title" role="alert">
                                                                    <strong class="text-danger">{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    @if ($theme == 'theme1')
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                {{ Form::label('top_bar_number', __('Top Bar Number'), ['class' => 'col-form-label']) }}
                                                                {{ Form::text('top_bar_number', !empty($storethemesetting['top_bar_number']) ? $storethemesetting['top_bar_number'] : '', ['class' => 'form-control', 'placeholder' => __('Top Bar Number')]) }}
                                                                @error('top_bar_number')
                                                                    <span class="invalid-top_bar_number" role="alert">
                                                                        <strong class="text-danger">{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                {{ Form::label('top_bar_whatsapp', __('Whatsapp'), ['class' => 'col-form-label']) }}
                                                                {{ Form::text('top_bar_whatsapp', !empty($storethemesetting['top_bar_whatsapp']) ? $storethemesetting['top_bar_whatsapp'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Whatsapp')]) }}
                                                                @error('top_bar_whatsapp')
                                                                    <span class="invalid-top_bar_whatsapp" role="alert">
                                                                        <strong class="text-danger">{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                {{ Form::label('top_bar_instagram', __('Instagram'), ['class' => 'col-form-label']) }}
                                                                {{ Form::text('top_bar_instagram', !empty($storethemesetting['top_bar_instagram']) ? $storethemesetting['top_bar_instagram'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Instagram')]) }}
                                                                @error('top_bar_instagram')
                                                                    <span class="invalid-top_bar_instagram" role="alert">
                                                                        <strong class="text-danger">{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                {{ Form::label('top_bar_twitter', __('Twitter'), ['class' => 'col-form-label']) }}
                                                                {{ Form::text('top_bar_twitter', !empty($storethemesetting['top_bar_twitter']) ? $storethemesetting['top_bar_twitter'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Twitter')]) }}
                                                                @error('top_bar_twitter')
                                                                    <span class="invalid-top_bar_twitter" role="alert">
                                                                        <strong class="text-danger">{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                {{ Form::label('top_bar_messenger', __('Messenger'), ['class' => 'col-form-label']) }}
                                                                {{ Form::text('top_bar_messenger', !empty($storethemesetting['top_bar_messenger']) ? $storethemesetting['top_bar_messenger'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Messenger')]) }}
                                                                @error('top_bar_messenger')
                                                                    <span class="invalid-top_bar_messenger" role="alert">
                                                                        <strong class="text-danger">{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @foreach ($getStoreThemeSetting as $json_key => $section)
                                    @php
                                        $id = '';

                                        if ($section['section_name'] == 'Home-Brand-Logo') {
                                            $id = 'Brand_Logo';
                                        }
                                        if ($section['section_name'] == 'Home-Header') {
                                            $id = 'Header_Setting';
                                            $class = 'card';
                                        }
                                        if ($section['section_name'] == 'Home-Promotions') {
                                            $id = 'Features_Setting';
                                        }
                                        if ($section['section_name'] == 'Home-Email-Subscriber') {
                                            $id = 'Email_Subscriber_Setting';
                                        }
                                        if ($section['section_name'] == 'Home-Categories') {
                                            $id = 'Categories';
                                        }
                                        if ($section['section_name'] == 'Home-Testimonial') {
                                            $id = 'Testimonials';
                                        }
                                        if ($section['section_name'] == 'Home-Footer-1') {
                                            $id = 'Footer_1';
                                        }
                                        if ($section['section_name'] == 'Home-Footer-2') {
                                            $id = 'Footer_2';
                                        }
                                        if ($section['section_name'] == 'Banner-Image') {
                                            $id = 'Banner_Img_Setting';
                                        }
                                        if ($section['section_name'] == 'Quote') {
                                            $id = 'Quote';
                                        }
                                        if ($section['section_name'] == 'Top-Purchased') {
                                            $id = 'top_purchased';
                                        }
                                        if ($section['section_name'] == 'Product-Section-Header') {
                                            $id = 'product_header';
                                        }
                                        if ($section['section_name'] == 'Latest Product') {
                                            $id = 'latest_product';
                                        }
                                        if ($section['section_name'] == 'Central-Banner') {
                                            $id = 'Banner_Setting';
                                        }
                                        if ($section['section_name'] == 'Latest-Category') {
                                            $id = 'latest_categories';
                                        }
                                        if ($section['section_name'] == 'Latest-Products') {
                                            $id = 'latest_Products';
                                        }

                                    @endphp
                                   
                                    @if ($section['section_name'] == 'Banner-Image')
                                        <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                        value="{{ $section['section_name'] }}">
                                        <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                            value="{{ $section['section_slug'] }}">
                                        <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                            value="{{ $section['array_type'] }}">
                                        <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                            value="{{ $section['loop_number'] }}">
                                        @php
                                            $loop = 1;
                                            $section = (array) $section;
                                        @endphp
                                        <div class="col-lg-6">
                                            @if ($json_key == 0 || ($json_key - 1 > -1 && $getStoreThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                                <div class="d-flex mb-3 align-items-center justify-content-between">
                                                    <h4 class="mb-0">{{ $section['section_name'] }} </h4>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden"
                                                            name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                            value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                            id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                            {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="card border border-primary shadow-none">
                                                <div class="card-body p-3" >
                                                    @php $loop1 = 1; @endphp
                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                        @php
                                                            $loop1 = (int) $section['loop_number'];
                                                        @endphp
                                                    @endif
                                                    @for ($i = 0; $i < $loop1; $i++)
                                                        <div class="row">
                                                            @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                                <?php $field = (array) $field; ?>
                                                                <input type="hidden"
                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                    value="{{ $field['field_name'] }}">
                                                                <input type="hidden"
                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                    value="{{ $field['field_slug'] }}">
                                                                <input type="hidden"
                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                    value="{{ $field['field_help_text'] }}">
                                                                <input type="hidden"
                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                    value="{{ $field['field_default_text'] }}">
                                                                <input type="hidden"
                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                    value="{{ $field['field_type'] }}">
                                                                @if ($field['field_type'] == 'text')
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            @php
                                                                                $checked1 = $field['field_default_text'];
                                                                                if (!empty($section[$field['field_slug']][$i])) {
                                                                                    $checked1 = $section[$field['field_slug']][$i];
                                                                                }
                                                                            @endphp
                                                                            @if ($section['array_type'] == 'multi-inner-list')
                                                                                <input type="text"
                                                                                    name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                    class="form-control"
                                                                                    value="{{ $checked1 }}"
                                                                                    placeholder="{{ $field['field_help_text'] }}">
                                                                            @else
                                                                                <input type="text" class="form-control"
                                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                    value="{{ $field['field_default_text'] }}"
                                                                                    placeholder="{{ $field['field_help_text'] }}">
                                                                            @endif

                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if ($field['field_type'] == 'text area')
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                                @php
                                                                                    $checked1 = $field['field_default_text'];
                                                                                    
                                                                                    if (!empty($section[$field['field_slug']][$i])) {
                                                                                        $checked1 = $section[$field['field_slug']][$i];
                                                                                    }
                                                                                
                                                                                @endphp
                                                                            @if ($section['array_type'] == 'multi-inner-list')
                                                                                <textarea name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" id=""
                                                                                    class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                            @else
                                                                                <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" id=""
                                                                                    class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if ($field['field_type'] == 'photo upload')
                                                                    <div class="col-md-6">
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            @php
                                                                                $checked2 = $field['field_default_text'];
                                                                                
                                                                                if (!empty($section[$field['field_slug']])) {
                                                                                    $checked2 = $section[$field['field_slug']][$i];
                                                                                
                                                                                    if (is_array($checked2)) {
                                                                                        $checked2 = $checked2['field_prev_text'];
                                                                                    }
                                                                                }
                                                                                $imgdisplay = \App\Models\Utility::get_file('uploads/');
                                                                            @endphp
                                                                            <div class="form-group">
                                                                                <label
                                                                                    class="form-label">{{ $field['field_name'] }}</label>
                                                                                <input type="hidden"
                                                                                    name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                    value="{{ $checked2 }}">
                                                                                <input type="file"
                                                                                    name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                    class="form-control"
                                                                                    placeholder="{{ $field['field_help_text'] }}">
                                                                            </div>
                                                                            @if (isset($checked2) && !is_array($checked2))
                                                                                <img src="{{ asset(Storage::url('uploads/' . $checked2)) }}"
                                                                                    style="width: auto; max-height: 80px;">
                                                                            @else
                                                                                <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                    style="width: auto; max-height: 80px;">
                                                                            @endif
                                                                        @else
                                                                            @php
                                                                                $imgdisplay = \App\Models\Utility::get_file('uploads/');
                                                                                
                                                                            @endphp
                                                                            <div class="form-group">
                                                                                <label class="form-label">{{ $field['field_name'] }}</label>
                                                                                <input type="hidden" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]" value="{{ $field['field_default_text'] }}">
                                                                                <input type="file"
                                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                    class="form-control"
                                                                                    placeholder="{{ $field['field_help_text'] }}">
                                                                            </div>
                                                                            <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }} "
                                                                                id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                                class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                                @if (!empty($getStoreThemeSetting['dashboard'])) style=""
                                                                        @else
                                                                        style="width: auto; height: 50px;" @endif
                                                                                @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                        @else
                                                                        style="width: 200px; height: 200px;" @endif>
                                                                        @endif

                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                @if($theme !== 'theme3')
                                    <div class="col-lg-6">
                                        @foreach ($getStoreThemeSetting as $json_key => $section)
                                            @php
                                                $id = '';

                                                if ($section['section_name'] == 'Home-Brand-Logo') {
                                                    $id = 'Brand_Logo';
                                                }
                                                if ($section['section_name'] == 'Home-Header') {
                                                    $id = 'Header_Setting';
                                                    $class = 'card';
                                                }
                                                if ($section['section_name'] == 'Home-Promotions') {
                                                    $id = 'Features_Setting';
                                                }
                                                if ($section['section_name'] == 'Home-Email-Subscriber') {
                                                    $id = 'Email_Subscriber_Setting';
                                                }
                                                if ($section['section_name'] == 'Home-Categories') {
                                                    $id = 'Categories';
                                                }
                                                if ($section['section_name'] == 'Home-Testimonial') {
                                                    $id = 'Testimonials';
                                                }
                                                if ($section['section_name'] == 'Home-Footer-1') {
                                                    $id = 'Footer_1';
                                                }
                                                if ($section['section_name'] == 'Home-Footer-2') {
                                                    $id = 'Footer_2';
                                                }
                                                if ($section['section_name'] == 'Banner-Image') {
                                                    $id = 'Banner_Img_Setting';
                                                }
                                                if ($section['section_name'] == 'Quote') {
                                                    $id = 'Quote';
                                                }
                                                if ($section['section_name'] == 'Top-Purchased') {
                                                    $id = 'top_purchased';
                                                }
                                                if ($section['section_name'] == 'Product-Section-Header') {
                                                    $id = 'product_header';
                                                }
                                                if ($section['section_name'] == 'Latest Product') {
                                                    $id = 'latest_product';
                                                }
                                                if ($section['section_name'] == 'Central-Banner') {
                                                    $id = 'Banner_Setting';
                                                }
                                                if ($section['section_name'] == 'Latest-Category') {
                                                    $id = 'latest_categories';
                                                }
                                                if ($section['section_name'] == 'Latest-Products') {
                                                    $id = 'latest_Products';
                                                }

                                            @endphp
                                            @if ($section['section_name'] == 'Home-Header')
                                                @if ($json_key == 0 || ($json_key - 1 > -1 && $getStoreThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                                    <div class="d-flex mb-3 align-items-center justify-content-between">
                                                        <h4 class="mb-0">{{ $section['section_name'] }} </h4>
                                                        <div class="form-check form-switch custom-switch-v1">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                                value="off">
                                                            <input type="checkbox" class="form-check-input input-primary"
                                                                name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                                id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                                {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                        <div class="card border border-primary shadow-none">
                                            @foreach ($getStoreThemeSetting as $json_key => $section)
                                            
                                                @if ($section['section_name'] == 'Home-Header')
                                                    <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                                    value="{{ $section['section_name'] }}">
                                                    <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                                        value="{{ $section['section_slug'] }}">
                                                    <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                                        value="{{ $section['array_type'] }}">
                                                    <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                                        value="{{ $section['loop_number'] }}">
                                                    @php
                                                        $loop = 1;
                                                        $section = (array) $section;
                                                    @endphp
                                                    <div class="card-body p-3">
                                                        @php $loop1 = 1; @endphp
                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                            @php
                                                                $loop1 = (int) $section['loop_number'];
                                                            @endphp
                                                        @endif
                                                        @for ($i = 0; $i < $loop1; $i++)
                                                            <div class="row">
                                                                @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                                    <?php $field = (array) $field; ?>
                                                                    <input type="hidden"
                                                                        name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                        value="{{ $field['field_name'] }}">
                                                                    <input type="hidden"
                                                                        name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                        value="{{ $field['field_slug'] }}">
                                                                    <input type="hidden"
                                                                        name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                        value="{{ $field['field_help_text'] }}">
                                                                    <input type="hidden"
                                                                        name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                        value="{{ $field['field_default_text'] }}">
                                                                    <input type="hidden"
                                                                        name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                        value="{{ $field['field_type'] }}">
                                                                    @if ($field['field_type'] == 'text')
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label
                                                                                    class="form-label">{{ $field['field_name'] }}</label>
                                                                                @php
                                                                                    $checked1 = $field['field_default_text'];
                                                                                    if (!empty($section[$field['field_slug']][$i])) {
                                                                                        $checked1 = $section[$field['field_slug']][$i];
                                                                                    }
                                                                                @endphp
                                                                                @if ($section['array_type'] == 'multi-inner-list')
                                                                                    <input type="text"
                                                                                        name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                        class="form-control"
                                                                                        value="{{ $checked1 }}"
                                                                                        placeholder="{{ $field['field_help_text'] }}">
                                                                                @else
                                                                                    <input type="text" class="form-control"
                                                                                        name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                        value="{{ $field['field_default_text'] }}"
                                                                                        placeholder="{{ $field['field_help_text'] }}">
                                                                                @endif

                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    @if ($field['field_type'] == 'text area')
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label
                                                                                    class="form-label">{{ $field['field_name'] }}</label>
                                                                                    @php
                                                                                        $checked1 = $field['field_default_text'];
                                                                                        
                                                                                        if (!empty($section[$field['field_slug']][$i])) {
                                                                                            $checked1 = $section[$field['field_slug']][$i];
                                                                                        }
                                                                                    
                                                                                    @endphp
                                                                                @if ($section['array_type'] == 'multi-inner-list')
                                                                                    <textarea name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" id=""
                                                                                        class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                                @else
                                                                                    <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" id=""
                                                                                        class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    @if ($field['field_type'] == 'photo upload')
                                                                        <div class="col-md-6">
                                                                            @if ($section['array_type'] == 'multi-inner-list')
                                                                                @php
                                                                                    $checked2 = $field['field_default_text'];
                                                                                    
                                                                                    if (!empty($section[$field['field_slug']])) {
                                                                                        $checked2 = $section[$field['field_slug']][$i];
                                                                                    
                                                                                        if (is_array($checked2)) {
                                                                                            $checked2 = $checked2['field_prev_text'];
                                                                                        }
                                                                                    }
                                                                                    $imgdisplay = \App\Models\Utility::get_file('uploads/');
                                                                                @endphp
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        class="form-label">{{ $field['field_name'] }}</label>
                                                                                    <input type="hidden"
                                                                                        name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                        value="{{ $checked2 }}">
                                                                                    <input type="file"
                                                                                        name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                        class="form-control"
                                                                                        placeholder="{{ $field['field_help_text'] }}">
                                                                                </div>
                                                                                @if (isset($checked2) && !is_array($checked2))
                                                                                    <img src="{{ asset(Storage::url('uploads/' . $checked2)) }}"
                                                                                        style="width: auto; max-height: 80px;">
                                                                                @else
                                                                                    <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                        style="width: auto; max-height: 80px;">
                                                                                @endif
                                                                            @else
                                                                                @php
                                                                                    $imgdisplay = \App\Models\Utility::get_file('uploads/');
                                                                                    
                                                                                @endphp
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        class="form-label">{{ $field['field_name'] }}</label>
                                                                                        <input type="hidden" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]" value="{{ $field['field_default_text'] }}">
                                                                                    <input type="file" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" class="form-control" placeholder="{{ $field['field_help_text'] }}">
                                                                                </div>
                                                                                <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }} "
                                                                                    id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                                    class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                                    @if (!empty($getStoreThemeSetting['dashboard'])) style=""
                                                                                    @else
                                                                                    style="width: auto; height: 50px;" @endif
                                                                                    @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                                    @else
                                                                                    style="width: 200px; height: 200px;" @endif>
                                                                            @endif

                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        @endfor
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                        
                            </div>
                       
                    </div>
                    <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home">
                        <div class="row">
                            <div class="col-12 text-lg-end">
                                <button type="submit" class="btn mb-3  btn-primary submit_all"> <i data-feather="check-circle"
                                        class="me-2"></i>{{ __('Save Changes') }}</button>
                            </div>
                            @foreach ($getStoreThemeSetting as $json_key => $section)
                                @php
                                    $id = '';

                                    if ($section['section_name'] == 'Home-Brand-Logo') {
                                        $id = 'Brand_Logo';
                                    }
                                    if ($section['section_name'] == 'Home-Header') {
                                        $id = 'Header_Setting';
                                        $class = 'card';
                                    }
                                    if ($section['section_name'] == 'Home-Promotions') {
                                        $id = 'Features_Setting';
                                    }
                                    if ($section['section_name'] == 'Home-Email-Subscriber') {
                                        $id = 'Email_Subscriber_Setting';
                                    }
                                    if ($section['section_name'] == 'Home-Categories') {
                                        $id = 'Categories';
                                    }
                                    if ($section['section_name'] == 'Home-Testimonial') {
                                        $id = 'Testimonials';
                                    }
                                    if ($section['section_name'] == 'Home-Footer-1') {
                                        $id = 'Footer_1';
                                    }
                                    if ($section['section_name'] == 'Home-Footer-2') {
                                        $id = 'Footer_2';
                                    }
                                    if ($section['section_name'] == 'Banner-Image') {
                                        $id = 'Banner_Img_Setting';
                                    }
                                    if ($section['section_name'] == 'Quote') {
                                        $id = 'Quote';
                                    }
                                    if ($section['section_name'] == 'Top-Purchased') {
                                        $id = 'top_purchased';
                                    }
                                    if ($section['section_name'] == 'Product-Section-Header') {
                                        $id = 'product_header';
                                    }
                                    if ($section['section_name'] == 'Latest Product') {
                                        $id = 'latest_product';
                                    }
                                    if ($section['section_name'] == 'Central-Banner') {
                                        $id = 'Banner_Setting';
                                    }
                                    if ($section['section_name'] == 'Latest-Category') {
                                        $id = 'latest_categories';
                                    }
                                    if ($section['section_name'] == 'Latest-Products') {
                                        $id = 'latest_Products';
                                    }

                                @endphp
                                
                                @if ($section['section_name'] == 'Home-Email-Subscriber')
                                    <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                    value="{{ $section['section_name'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                        value="{{ $section['section_slug'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                        value="{{ $section['array_type'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                        value="{{ $section['loop_number'] }}">
                                    @php
                                        $loop = 1;
                                        $section = (array) $section;
                                    @endphp
                                    <div class="col-lg-6">
                                        @if ($json_key == 0 || ($json_key - 1 > -1 && $getStoreThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                            <div class="d-flex mb-3 align-items-center justify-content-between">
                                                <h4 class="mb-0">{{ $section['section_name'] }} </h4>
                                                <div class="form-check form-switch custom-switch-v1">
                                                    <input type="hidden"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        value="off">
                                                    <input type="checkbox" class="form-check-input input-primary"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                        {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="card border border-primary shadow-none">
                                            <div class="card-body p-3">
                                                @php $loop1 = 1; @endphp
                                                @if ($section['array_type'] == 'multi-inner-list')
                                                    @php
                                                        $loop1 = (int) $section['loop_number'];
                                                    @endphp
                                                @endif
                                                @for ($i = 0; $i < $loop1; $i++)
                                                    <div class="row">
                                                        @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                            <?php $field = (array) $field; ?>

                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                value="{{ $field['field_name'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                value="{{ $field['field_slug'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                value="{{ $field['field_help_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                value="{{ $field['field_default_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                value="{{ $field['field_type'] }}">
                                                            @if ($field['field_type'] == 'text')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <input type="text"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                class="form-control"
                                                                                value="{{ $checked1 }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @else
                                                                            <input type="text" class="form-control"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                value="{{ $field['field_default_text'] }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'text area')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                            @php
                                                                                $checked1 = $field['field_default_text'];
                                                                                
                                                                                if (!empty($section[$field['field_slug']][$i])) {
                                                                                    $checked1 = $section[$field['field_slug']][$i];
                                                                                }
                                                                               
                                                                            @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <textarea name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                        @else
                                                                            <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'photo upload')
                                                                <div class="col-md-6">
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        @php
                                                                            $checked2 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']])) {
                                                                                $checked2 = $section[$field['field_slug']][$i];
                                                                            
                                                                                if (is_array($checked2)) {
                                                                                    $checked2 = $checked2['field_prev_text'];
                                                                                }
                                                                            }
                                                                            $imgdisplay = \App\Models\Utility::get_file('uploads/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                value="{{ $checked2 }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if (isset($checked2) && !is_array($checked2))
                                                                            <img src="{{ asset(Storage::url('uploads/' . $checked2)) }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @endif
                                                                    @else
                                                                        @php
                                                                            $imgdisplay = \App\Models\Utility::get_file('uploads/');
                                                                            
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]" value="{{ $field['field_default_text'] }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }} "
                                                                            id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                            class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                            @if (!empty($getStoreThemeSetting['dashboard'])) style=""
                                                                    @else
                                                                    style="width: auto; height: 50px;" @endif
                                                                            @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                    @else
                                                                    style="width: 200px; height: 200px;" @endif>
                                                                    @endif

                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            @foreach ($getStoreThemeSetting as $json_key => $section)
                                @php
                                    $id = '';

                                    if ($section['section_name'] == 'Home-Brand-Logo') {
                                        $id = 'Brand_Logo';
                                    }
                                    if ($section['section_name'] == 'Home-Header') {
                                        $id = 'Header_Setting';
                                        $class = 'card';
                                    }
                                    if ($section['section_name'] == 'Home-Promotions') {
                                        $id = 'Features_Setting';
                                    }
                                    if ($section['section_name'] == 'Home-Email-Subscriber') {
                                        $id = 'Email_Subscriber_Setting';
                                    }
                                    if ($section['section_name'] == 'Home-Categories') {
                                        $id = 'Categories';
                                    }
                                    if ($section['section_name'] == 'Home-Testimonial') {
                                        $id = 'Testimonials';
                                    }
                                    if ($section['section_name'] == 'Home-Footer-1') {
                                        $id = 'Footer_1';
                                    }
                                    if ($section['section_name'] == 'Home-Footer-2') {
                                        $id = 'Footer_2';
                                    }
                                    if ($section['section_name'] == 'Banner-Image') {
                                        $id = 'Banner_Img_Setting';
                                    }
                                    if ($section['section_name'] == 'Quote') {
                                        $id = 'Quote';
                                    }
                                    if ($section['section_name'] == 'Top-Purchased') {
                                        $id = 'top_purchased';
                                    }
                                    if ($section['section_name'] == 'Product-Section-Header') {
                                        $id = 'product_header';
                                    }
                                    if ($section['section_name'] == 'Latest Product') {
                                        $id = 'latest_product';
                                    }
                                    if ($section['section_name'] == 'Central-Banner') {
                                        $id = 'Banner_Setting';
                                    }
                                    if ($section['section_name'] == 'Latest-Category') {
                                        $id = 'latest_categories';
                                    }
                                    if ($section['section_name'] == 'Latest-Products') {
                                        $id = 'latest_Products';
                                    }

                                @endphp
                                @if ($section['section_name'] == 'Home-Categories')
                                    <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                    value="{{ $section['section_name'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                        value="{{ $section['section_slug'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                        value="{{ $section['array_type'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                        value="{{ $section['loop_number'] }}">
                                    @php
                                        $loop = 1;
                                        $section = (array) $section;
                                    @endphp
                                    <div class="col-lg-6">
                                        @if ($json_key == 0 || ($json_key - 1 > -1 && $getStoreThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                            <div class="d-flex mb-3 align-items-center justify-content-between">
                                                <h4 class="mb-0">{{ $section['section_name'] }} </h4>
                                                <div class="form-check form-switch custom-switch-v1">
                                                    <input type="hidden"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        value="off">
                                                    <input type="checkbox" class="form-check-input input-primary"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                        {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="card border border-primary shadow-none">
                                            <div class="card-body p-3">
                                                @php $loop1 = 1; @endphp
                                                @if ($section['array_type'] == 'multi-inner-list')
                                                    @php
                                                        $loop1 = (int) $section['loop_number'];
                                                    @endphp
                                                @endif
                                                @for ($i = 0; $i < $loop1; $i++)
                                                    <div class="row">
                                                        @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                            <?php $field = (array) $field; ?>

                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                value="{{ $field['field_name'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                value="{{ $field['field_slug'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                value="{{ $field['field_help_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                value="{{ $field['field_default_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                value="{{ $field['field_type'] }}">
                                                            @if ($field['field_type'] == 'text')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <input type="text"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                class="form-control"
                                                                                value="{{ $checked1 }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @else
                                                                            <input type="text" class="form-control"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                value="{{ $field['field_default_text'] }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'text area')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                            @php
                                                                                $checked1 = $field['field_default_text'];
                                                                                
                                                                                if (!empty($section[$field['field_slug']][$i])) {
                                                                                    $checked1 = $section[$field['field_slug']][$i];
                                                                                }
                                                                               
                                                                            @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <textarea name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                        @else
                                                                            <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'photo upload')
                                                                <div class="col-md-6">
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        @php
                                                                            $checked2 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']])) {
                                                                                $checked2 = $section[$field['field_slug']][$i];
                                                                            
                                                                                if (is_array($checked2)) {
                                                                                    $checked2 = $checked2['field_prev_text'];
                                                                                }
                                                                            }
                                                                            $imgdisplay = \App\Models\Utility::get_file('uploads/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                value="{{ $checked2 }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if (isset($checked2) && !is_array($checked2))
                                                                            <img src="{{ asset(Storage::url('uploads/' . $checked2)) }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @endif
                                                                    @else
                                                                        @php
                                                                            $imgdisplay = \App\Models\Utility::get_file('uploads/');
                                                                            
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]" value="{{ $field['field_default_text'] }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }} "
                                                                            id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                            class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                            @if (!empty($getStoreThemeSetting['dashboard'])) style=""
                                                                    @else
                                                                    style="width: auto; height: 50px;" @endif
                                                                            @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                    @else
                                                                    style="width: 200px; height: 200px;" @endif>
                                                                    @endif

                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            @if($theme !== 'theme10')
                                <div class="col-lg-6">
                                    @foreach ($getStoreThemeSetting as $json_key => $section)
                                        @php
                                            $id = '';

                                            if ($section['section_name'] == 'Home-Brand-Logo') {
                                                $id = 'Brand_Logo';
                                            }
                                            if ($section['section_name'] == 'Home-Header') {
                                                $id = 'Header_Setting';
                                                $class = 'card';
                                            }
                                            if ($section['section_name'] == 'Home-Promotions') {
                                                $id = 'Features_Setting';
                                            }
                                            if ($section['section_name'] == 'Home-Email-Subscriber') {
                                                $id = 'Email_Subscriber_Setting';
                                            }
                                            if ($section['section_name'] == 'Home-Categories') {
                                                $id = 'Categories';
                                            }
                                            if ($section['section_name'] == 'Home-Testimonial') {
                                                $id = 'Testimonials';
                                            }
                                            if ($section['section_name'] == 'Home-Footer-1') {
                                                $id = 'Footer_1';
                                            }
                                            if ($section['section_name'] == 'Home-Footer-2') {
                                                $id = 'Footer_2';
                                            }
                                            if ($section['section_name'] == 'Banner-Image') {
                                                $id = 'Banner_Img_Setting';
                                            }
                                            if ($section['section_name'] == 'Quote') {
                                                $id = 'Quote';
                                            }
                                            if ($section['section_name'] == 'Top-Purchased') {
                                                $id = 'top_purchased';
                                            }
                                            if ($section['section_name'] == 'Product-Section-Header') {
                                                $id = 'product_header';
                                            }
                                            if ($section['section_name'] == 'Latest Product') {
                                                $id = 'latest_product';
                                            }
                                            if ($section['section_name'] == 'Central-Banner') {
                                                $id = 'Banner_Setting';
                                            }
                                            if ($section['section_name'] == 'Latest-Category') {
                                                $id = 'latest_categories';
                                            }
                                            if ($section['section_name'] == 'Latest-Products') {
                                                $id = 'latest_Products';
                                            }

                                        @endphp
                                        @if ($section['section_name'] == 'Home-Promotions')
                                            @if ($json_key == 0 || ($json_key - 1 > -1 && $getStoreThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                                <div class="d-flex mb-3 align-items-center justify-content-between">
                                                    <h4 class="mb-0">{{ $section['section_name'] }} </h4>
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="hidden"
                                                            name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                            value="off">
                                                        <input type="checkbox" class="form-check-input input-primary"
                                                            name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                            id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                            {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                    <div class="card border border-primary shadow-none">
                                        @foreach ($getStoreThemeSetting as $json_key => $section)
                                            
                                            @if ($section['section_name'] == 'Home-Promotions')
                                                <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                                value="{{ $section['section_name'] }}">
                                                <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                                    value="{{ $section['section_slug'] }}">
                                                <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                                    value="{{ $section['array_type'] }}">
                                                <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                                    value="{{ $section['loop_number'] }}">
                                                @php
                                                    $loop = 1;
                                                    $section = (array) $section;
                                                @endphp
                                                <div class="card-body p-3">
                                                    @php $loop1 = 1; @endphp
                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                        @php
                                                            $loop1 = (int) $section['loop_number'];
                                                        @endphp
                                                    @endif
                                                    @for ($i = 0; $i < $loop1; $i++)
                                                        <div class="row">
                                                            @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                                <?php $field = (array) $field; ?>

                                                                <input type="hidden"
                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                    value="{{ $field['field_name'] }}">
                                                                <input type="hidden"
                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                    value="{{ $field['field_slug'] }}">
                                                                <input type="hidden"
                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                    value="{{ $field['field_help_text'] }}">
                                                                <input type="hidden"
                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                    value="{{ $field['field_default_text'] }}">
                                                                <input type="hidden"
                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                    value="{{ $field['field_type'] }}">
                                                                @if ($field['field_type'] == 'text')
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            @php
                                                                                $checked1 = $field['field_default_text'];
                                                                                if (!empty($section[$field['field_slug']][$i])) {
                                                                                    $checked1 = $section[$field['field_slug']][$i];
                                                                                }
                                                                            @endphp
                                                                            @if ($section['array_type'] == 'multi-inner-list')
                                                                                <input type="text"
                                                                                    name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                    class="form-control"
                                                                                    value="{{ $checked1 }}"
                                                                                    placeholder="{{ $field['field_help_text'] }}">
                                                                            @else
                                                                                <input type="text" class="form-control"
                                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                    value="{{ $field['field_default_text'] }}"
                                                                                    placeholder="{{ $field['field_help_text'] }}">
                                                                            @endif

                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if ($field['field_type'] == 'text area')
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            @php
                                                                                $checked1 = $field['field_default_text'];
                                                                                
                                                                                if (!empty($section[$field['field_slug']][$i])) {
                                                                                    $checked1 = $section[$field['field_slug']][$i];
                                                                                }
                                                                               
                                                                            @endphp
                                                                            @if ($section['array_type'] == 'multi-inner-list')
                                                                                <textarea class="form-control" name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                            @else
                                                                                <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" id=""
                                                                                    class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if ($field['field_type'] == 'photo upload')
                                                                    <div class="col-md-6">
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            @php
                                                                                $checked2 = $field['field_default_text'];
                                                                                
                                                                                if (!empty($section[$field['field_slug']])) {
                                                                                    $checked2 = $section[$field['field_slug']][$i];
                                                                                
                                                                                    if (is_array($checked2)) {
                                                                                        $checked2 = $checked2['field_prev_text'];
                                                                                    }
                                                                                }
                                                                                $imgdisplay = \App\Models\Utility::get_file('uploads/');
                                                                            @endphp
                                                                            <div class="form-group">
                                                                                <label
                                                                                    class="form-label">{{ $field['field_name'] }}</label>
                                                                                <input type="hidden"
                                                                                    name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                    value="{{ $checked2 }}">
                                                                                <input type="file"
                                                                                    name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                    class="form-control"
                                                                                    placeholder="{{ $field['field_help_text'] }}">
                                                                            </div>
                                                                            @if (isset($checked2) && !is_array($checked2))
                                                                                <img src="{{ asset(Storage::url('uploads/' . $checked2)) }}"
                                                                                    style="width: auto; max-height: 80px;">
                                                                            @else
                                                                                <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                    style="width: auto; max-height: 80px;">
                                                                            @endif
                                                                        @else
                                                                            @php
                                                                                $imgdisplay = \App\Models\Utility::get_file('uploads/');
                                                                                
                                                                            @endphp
                                                                            <div class="form-group">
                                                                                <label
                                                                                    class="form-label">{{ $field['field_name'] }}</label>
                                                                                <input type="hidden" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]" value="{{ $field['field_default_text'] }}">
                                                                                <input type="file"
                                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                    class="form-control"
                                                                                    placeholder="{{ $field['field_help_text'] }}">
                                                                            </div>
                                                                            <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }} "
                                                                                id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                                class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                                @if (!empty($getStoreThemeSetting['dashboard'])) style=""
                                                                                @else
                                                                                style="width: auto; height: 50px;" @endif
                                                                                @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                                @else
                                                                                style="width: 200px; height: 200px;" @endif>
                                                                        @endif

                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endfor
                                                </div>
                                                <hr>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <div class="col-lg-6">
                                @foreach ($getStoreThemeSetting as $json_key => $section)
                                    @php
                                        $id = '';

                                        if ($section['section_name'] == 'Home-Brand-Logo') {
                                            $id = 'Brand_Logo';
                                        }
                                        if ($section['section_name'] == 'Home-Header') {
                                            $id = 'Header_Setting';
                                            $class = 'card';
                                        }
                                        if ($section['section_name'] == 'Home-Promotions') {
                                            $id = 'Features_Setting';
                                        }
                                        if ($section['section_name'] == 'Home-Email-Subscriber') {
                                            $id = 'Email_Subscriber_Setting';
                                        }
                                        if ($section['section_name'] == 'Home-Categories') {
                                            $id = 'Categories';
                                        }
                                        if ($section['section_name'] == 'Home-Testimonial') {
                                            $id = 'Testimonials';
                                        }
                                        if ($section['section_name'] == 'Home-Footer-1') {
                                            $id = 'Footer_1';
                                        }
                                        if ($section['section_name'] == 'Home-Footer-2') {
                                            $id = 'Footer_2';
                                        }
                                        if ($section['section_name'] == 'Banner-Image') {
                                            $id = 'Banner_Img_Setting';
                                        }
                                        if ($section['section_name'] == 'Quote') {
                                            $id = 'Quote';
                                        }
                                        if ($section['section_name'] == 'Top-Purchased') {
                                            $id = 'top_purchased';
                                        }
                                        if ($section['section_name'] == 'Product-Section-Header') {
                                            $id = 'product_header';
                                        }
                                        if ($section['section_name'] == 'Latest Product') {
                                            $id = 'latest_product';
                                        }
                                        if ($section['section_name'] == 'Central-Banner') {
                                            $id = 'Banner_Setting';
                                        }
                                        if ($section['section_name'] == 'Latest-Category') {
                                            $id = 'latest_categories';
                                        }
                                        if ($section['section_name'] == 'Latest-Products') {
                                            $id = 'latest_Products';
                                        }

                                    @endphp
                                    @if ($section['section_name'] == 'Home-Testimonial')
                                        @if ($json_key == 0 || ($json_key - 1 > -1 && $getStoreThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                            <div class="d-flex mb-3 align-items-center justify-content-between">
                                                <h4 class="mb-0">{{ $section['section_name'] }} </h4>
                                                <div class="form-check form-switch custom-switch-v1">
                                                    <input type="hidden"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        value="off">
                                                    <input type="checkbox" class="form-check-input input-primary"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                        {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                                <div class="card border border-primary shadow-none">
                                    @foreach ($getStoreThemeSetting as $json_key => $section)
                                        @if ($section['section_name'] == 'Home-Testimonial')
                                            <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                            value="{{ $section['section_name'] }}">
                                            <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                                value="{{ $section['section_slug'] }}">
                                            <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                                value="{{ $section['array_type'] }}">
                                            <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                                value="{{ $section['loop_number'] }}">
                                            @php
                                                $loop = 1;
                                                $section = (array) $section;
                                            @endphp
                                            <div class="card-body p-3">
                                                @php $loop1 = 1; @endphp
                                                @if ($section['array_type'] == 'multi-inner-list')
                                                    @php
                                                        $loop1 = (int) $section['loop_number'];
                                                    @endphp
                                                @endif
                                                @for ($i = 0; $i < $loop1; $i++)
                                                    <div class="row">
                                                        @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                            <?php $field = (array) $field; ?>

                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                value="{{ $field['field_name'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                value="{{ $field['field_slug'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                value="{{ $field['field_help_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                value="{{ $field['field_default_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                value="{{ $field['field_type'] }}">
                                                            @if ($field['field_type'] == 'text')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <input type="text"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                class="form-control"
                                                                                value="{{ $checked1 }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @else
                                                                            <input type="text" class="form-control"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                value="{{ $field['field_default_text'] }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'text area')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                            @php
                                                                                $checked1 = $field['field_default_text'];
                                                                                
                                                                                if (!empty($section[$field['field_slug']][$i])) {
                                                                                    $checked1 = $section[$field['field_slug']][$i];
                                                                                }
                                                                               
                                                                            @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <textarea name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                        @else
                                                                            <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'photo upload')
                                                                <div class="col-md-6">
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        @php
                                                                            $checked2 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']])) {
                                                                                $checked2 = $section[$field['field_slug']][$i];
                                                                            
                                                                                if (is_array($checked2)) {
                                                                                    $checked2 = $checked2['field_prev_text'];
                                                                                }
                                                                            }
                                                                            $imgdisplay = \App\Models\Utility::get_file('uploads/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                value="{{ $checked2 }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if (isset($checked2) && !is_array($checked2))
                                                                            <img src="{{ asset(Storage::url('uploads/' . $checked2)) }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @endif
                                                                    @else
                                                                        @php
                                                                            $imgdisplay = \App\Models\Utility::get_file('uploads/');
                                                                            
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]" value="{{ $field['field_default_text'] }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }} "
                                                                            id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                            class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                            @if (!empty($getStoreThemeSetting['dashboard'])) style=""
                                                                    @else
                                                                    style="width: auto; height: 50px;" @endif
                                                                            @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                    @else
                                                                    style="width: 200px; height: 200px;" @endif>
                                                                    @endif

                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'checkbox')
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <div class="form-check form-switch custom-switch-v1">
                                                                            <label class="form-check-label"
                                                                                for="array[ {{ $section['section_slug'] }}][{{ $field['field_slug'] }}]">{{ $field['field_name'] }}</label>
                                                                            @if ($section['array_type'] == 'multi-inner-list')
                                                                                @php
                                                                                    $checked1 = '';
                                                                                    
                                                                                    if (!empty($section[$field['field_slug']][$i]) && $section[$field['field_slug']][$i] == 'on') {
                                                                                        $checked1 = 'checked';
                                                                                    } else {
                                                                                        if (!empty($section['section_enable']) && $section['section_enable'] == 'on') {
                                                                                            $checked1 = 'checked';
                                                                                        }
                                                                                    }
                                                                                    
                                                                                @endphp
                                                                                <input type="hidden"
                                                                                    name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                    value="off">
                                                                                <input type="checkbox"
                                                                                    class="form-check-input input-primary"
                                                                                    name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                    id="array[{{ $section['section_slug'] }}][{{ $field['field_slug'] }}]"
                                                                                    {{ $checked1 }}>
                                                                            @else
                                                                                @php
                                                                                    $checked = '';
                                                                                    if (!empty($field['field_default_text']) && $field['field_default_text'] == 'on') {
                                                                                        $checked = 'checked';
                                                                                    }
                                                                                @endphp
                                                                                <input type="hidden"
                                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                    value="off">
                                                                                <input type="checkbox"
                                                                                    class="form-check-input input-primary"
                                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                    id="array[{{ $section['section_slug'] }}][{{ $field['field_slug'] }}]"
                                                                                    {{ $checked }}>
                                                                            @endif

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    <hr>
                                                @endfor
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-brand" role="tabpanel" aria-labelledby="pills-brand">
                        <div class="row">
                            <div class="col-12 text-lg-end">
                                <button type="submit" class="btn mb-3  btn-primary submit_all"> <i data-feather="check-circle"
                                        class="me-2"></i> {{ __('Save Changes') }}</button>
                            </div>
                            @foreach ($getStoreThemeSetting as $json_key => $section)
                                @php
                                    $id = '';

                                    if ($section['section_name'] == 'Home-Brand-Logo') {
                                        $id = 'Brand_Logo';
                                    }
                                    if ($section['section_name'] == 'Home-Header') {
                                        $id = 'Header_Setting';
                                        $class = 'card';
                                    }
                                    if ($section['section_name'] == 'Home-Promotions') {
                                        $id = 'Features_Setting';
                                    }
                                    if ($section['section_name'] == 'Home-Email-Subscriber') {
                                        $id = 'Email_Subscriber_Setting';
                                    }
                                    if ($section['section_name'] == 'Home-Categories') {
                                        $id = 'Categories';
                                    }
                                    if ($section['section_name'] == 'Home-Testimonial') {
                                        $id = 'Testimonials';
                                    }
                                    if ($section['section_name'] == 'Home-Footer-1') {
                                        $id = 'Footer_1';
                                    }
                                    if ($section['section_name'] == 'Home-Footer-2') {
                                        $id = 'Footer_2';
                                    }
                                    if ($section['section_name'] == 'Banner-Image') {
                                        $id = 'Banner_Img_Setting';
                                    }
                                    if ($section['section_name'] == 'Quote') {
                                        $id = 'Quote';
                                    }
                                    if ($section['section_name'] == 'Top-Purchased') {
                                        $id = 'top_purchased';
                                    }
                                    if ($section['section_name'] == 'Product-Section-Header') {
                                        $id = 'product_header';
                                    }
                                    if ($section['section_name'] == 'Latest Product') {
                                        $id = 'latest_product';
                                    }
                                    if ($section['section_name'] == 'Central-Banner') {
                                        $id = 'Banner_Setting';
                                    }
                                    if ($section['section_name'] == 'Latest-Category') {
                                        $id = 'latest_categories';
                                    }
                                    if ($section['section_name'] == 'Latest-Products') {
                                        $id = 'latest_Products';
                                    }

                                @endphp
                                @if ($section['section_name'] == 'Home-Brand-Logo' || $section['section_name'] == 'Quote')
                                    <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                    value="{{ $section['section_name'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                        value="{{ $section['section_slug'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                        value="{{ $section['array_type'] }}">
                                    <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                        value="{{ $section['loop_number'] }}">
                                    @php
                                        $loop = 1;
                                        $section = (array) $section;
                                    @endphp
                                    <div class="col-lg-6">
                                        @if ($json_key == 0 || ($json_key - 1 > -1 && $getStoreThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                            <div class="d-flex mb-3 align-items-center justify-content-between">
                                                <h4 class="mb-0">{{ $section['section_name'] }} </h4>
                                                <div class="form-check form-switch custom-switch-v1">
                                                    <input type="hidden"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        value="off">
                                                    <input type="checkbox" class="form-check-input input-primary"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                        {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="card border border-primary shadow-none">
                                            <div class="card-body p-3">
                                                @php $loop1 = 1; @endphp
                                                @if ($section['array_type'] == 'multi-inner-list')
                                                    @php
                                                        $loop1 = (int) $section['loop_number'];
                                                    @endphp
                                                @endif
                                                @for ($i = 0; $i < $loop1; $i++)
                                                    <div class="row">
                                                        @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                            <?php $field = (array) $field; ?>

                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                value="{{ $field['field_name'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                value="{{ $field['field_slug'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                value="{{ $field['field_help_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                value="{{ $field['field_default_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                value="{{ $field['field_type'] }}">
                                                            @if ($field['field_type'] == 'text')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <input type="text"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                class="form-control"
                                                                                value="{{ $checked1 }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @else
                                                                            <input type="text" class="form-control"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                value="{{ $field['field_default_text'] }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'text area')
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                            @php
                                                                                $checked1 = $field['field_default_text'];
                                                                                
                                                                                if (!empty($section[$field['field_slug']][$i])) {
                                                                                    $checked1 = $section[$field['field_slug']][$i];
                                                                                }
                                                                               
                                                                            @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <textarea name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                        @else
                                                                            <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'photo upload')
                                                                <div class="col-md-6">
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        @php
                                                                            $checked2 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']])) {
                                                                                $checked2 = $section[$field['field_slug']][$i];
                                                                            
                                                                                if (is_array($checked2)) {
                                                                                    $checked2 = $checked2['field_prev_text'];
                                                                                }
                                                                            }
                                                                            $imgdisplay = \App\Models\Utility::get_file('uploads/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                value="{{ $checked2 }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if (isset($checked2) && !is_array($checked2))
                                                                            <img src="{{ asset(Storage::url('uploads/' . $checked2)) }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @endif
                                                                    @else
                                                                        @php
                                                                            $imgdisplay = \App\Models\Utility::get_file('uploads/');
                                                                            
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]" value="{{ $field['field_default_text'] }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }} "
                                                                            id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                            class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                            @if (!empty($getStoreThemeSetting['dashboard'])) style=""
                                                                @else
                                                                style="width: auto; height: 50px;" @endif
                                                                            @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                @else
                                                                style="width: 200px; height: 200px;" @endif>
                                                                    @endif

                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'multi file upload')
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        <input type="file"
                                                                            name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][multi_image][]"
                                                                            class="form-control custom-input-file" multiple>
                                                                    </div>
                                                                </div>
                                                                <div id="img-count" class="badge badge-primary rounded-pill">
                                                                </div>
                                                                @if (!empty($field['image_path']))
                                                                    @foreach ($field['image_path'] as $key => $file_pathh)
                                                                        <div class="card mb-3 border shadow-none product_Image"
                                                                            data-value="{{ $file_pathh }}">
                                                                            <div class="px-3 py-3">
                                                                                <div class="row align-items-center">
                                                                                    <div class="col ml-n2">
                                                                                        <p class="card-text small text-muted">
                                                                                            <input type="hidden"
                                                                                                name='array[{{ $json_key }}][prev_image][]'
                                                                                                value="{{ $file_pathh }}">
                                                                                            <img class="rounded"
                                                                                                src="{{ asset(Storage::url('uploads/' . $file_pathh)) }}"
                                                                                                width="70px"
                                                                                                alt="Image placeholder"
                                                                                                data-dz-thumbnail>

                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="col-auto actions">
                                                                                        <a class="action-item btn btn-sm btn-icon btn-light-secondary"
                                                                                            href=" {{ asset(Storage::url('uploads/' . $file_pathh)) }}"
                                                                                            download=""
                                                                                            data-toggle="tooltip"
                                                                                            data-original-title="{{ __('Download') }}">
                                                                                            <i data-feather="download"></i>
                                                                                        </a>
                                                                                    </div>
                                                                                    <div class="col-auto actions">
                                                                                        <a name="deleteRecord"
                                                                                            class="action-item deleteRecord btn btn-sm bg-icon btn-light-secondary me-2"
                                                                                            data-name="{{ 'uploads/' . $file_pathh }}">
                                                                                            <i data-feather="trash-2"></i>
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-product" role="tabpanel" aria-labelledby="pills-product">
                        <div class="row">
                            <div class="col-12 text-lg-end">
                                <button type="submit" class="btn mb-3  btn-primary submit_all"> <i data-feather="check-circle"
                                        class="me-2"></i> {{ __('Save Changes') }}</button>
                            </div>
                            @foreach ($getStoreThemeSetting as $json_key => $section)
                                @php
                                    $id = '';

                                    if ($section['section_name'] == 'Home-Brand-Logo') {
                                        $id = 'Brand_Logo';
                                    }
                                    if ($section['section_name'] == 'Home-Header') {
                                        $id = 'Header_Setting';
                                        $class = 'card';
                                    }
                                    if ($section['section_name'] == 'Home-Promotions') {
                                        $id = 'Features_Setting';
                                    }
                                    if ($section['section_name'] == 'Home-Email-Subscriber') {
                                        $id = 'Email_Subscriber_Setting';
                                    }
                                    if ($section['section_name'] == 'Home-Categories') {
                                        $id = 'Categories';
                                    }
                                    if ($section['section_name'] == 'Home-Testimonial') {
                                        $id = 'Testimonials';
                                    }
                                    if ($section['section_name'] == 'Home-Footer-1') {
                                        $id = 'Footer_1';
                                    }
                                    if ($section['section_name'] == 'Home-Footer-2') {
                                        $id = 'Footer_2';
                                    }
                                    if ($section['section_name'] == 'Banner-Image') {
                                        $id = 'Banner_Img_Setting';
                                    }
                                    if ($section['section_name'] == 'Quote') {
                                        $id = 'Quote';
                                    }
                                    if ($section['section_name'] == 'Top-Purchased') {
                                        $id = 'top_purchased';
                                    }
                                    if ($section['section_name'] == 'Product-Section-Header') {
                                        $id = 'product_header';
                                    }
                                    if ($section['section_name'] == 'Latest Product') {
                                        $id = 'latest_product';
                                    }
                                    if ($section['section_name'] == 'Central-Banner') {
                                        $id = 'Banner_Setting';
                                    }
                                    if ($section['section_name'] == 'Latest-Category') {
                                        $id = 'latest_categories';
                                    }
                                    if ($section['section_name'] == 'Latest-Products') {
                                        $id = 'latest_Products';
                                    }

                                @endphp
                               
                                @if (
                                    $section['section_name'] == 'Top-Purchased' ||
                                        $section['section_name'] == 'Product-Section-Header' ||
                                        $section['section_name'] == 'Latest Product' ||
                                        $section['section_name'] == 'Central-Banner' ||
                                        $section['section_name'] == 'Latest-Category' ||
                                        $section['section_name'] == 'Latest-Products')
                                        <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                        value="{{ $section['section_name'] }}">
                                        <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                            value="{{ $section['section_slug'] }}">
                                        <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                            value="{{ $section['array_type'] }}">
                                        <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                            value="{{ $section['loop_number'] }}">
                                        @php
                                            $loop = 1;
                                            $section = (array) $section;
                                        @endphp
                                    <div class="col-lg-6">
                                        @if ($json_key == 0 || ($json_key - 1 > -1 && $getStoreThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                            <div class="d-flex mb-3 align-items-center justify-content-between">
                                                <h4 class="mb-0">{{ $section['section_name'] }} </h4>
                                                <div class="form-check form-switch custom-switch-v1">
                                                    <input type="hidden"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        value="off">
                                                    <input type="checkbox" class="form-check-input input-primary"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                        {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="card border border-primary shadow-none">
                                            <div class="card-body p-3">
                                                @php $loop1 = 1; @endphp
                                                @if ($section['array_type'] == 'multi-inner-list')
                                                    @php
                                                        $loop1 = (int) $section['loop_number'];
                                                    @endphp
                                                @endif
                                                @for ($i = 0; $i < $loop1; $i++)
                                                    <div class="row">
                                                        @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                            <?php $field = (array) $field; ?>

                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                value="{{ $field['field_name'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                value="{{ $field['field_slug'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                value="{{ $field['field_help_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                value="{{ $field['field_default_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                value="{{ $field['field_type'] }}">
                                                            @if ($field['field_type'] == 'text')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <input type="text"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                class="form-control"
                                                                                value="{{ $checked1 }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @else
                                                                            <input type="text" class="form-control"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                value="{{ $field['field_default_text'] }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'text area')
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                            @php
                                                                                $checked1 = $field['field_default_text'];
                                                                                
                                                                                if (!empty($section[$field['field_slug']][$i])) {
                                                                                    $checked1 = $section[$field['field_slug']][$i];
                                                                                }
                                                                               
                                                                            @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <textarea name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                        @else
                                                                            <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'photo upload')
                                                                <div class="col-md-6">
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        @php
                                                                            $checked2 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']])) {
                                                                                $checked2 = $section[$field['field_slug']][$i];
                                                                            
                                                                                if (is_array($checked2)) {
                                                                                    $checked2 = $checked2['field_prev_text'];
                                                                                }
                                                                            }
                                                                            $imgdisplay = \App\Models\Utility::get_file('uploads/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                value="{{ $checked2 }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if (isset($checked2) && !is_array($checked2))
                                                                            <img src="{{ asset(Storage::url('uploads/' . $checked2)) }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @endif
                                                                    @else
                                                                        @php
                                                                            $imgdisplay = \App\Models\Utility::get_file('uploads/');
                                                                            
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]" value="{{ $field['field_default_text'] }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }} "
                                                                            id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                            class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                            @if (!empty($getStoreThemeSetting['dashboard'])) style=""
                                                                @else
                                                                style="width: auto; height: 50px;" @endif
                                                                            @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                @else
                                                                style="width: 200px; height: 200px;" @endif>
                                                                    @endif

                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'multi file upload')
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        <input type="file"
                                                                            name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][multi_image][]"
                                                                            class="form-control custom-input-file" multiple>
                                                                    </div>
                                                                </div>
                                                                <div id="img-count" class="badge badge-primary rounded-pill">
                                                                </div>
                                                                @if (!empty($field['image_path']))
                                                                    @foreach ($field['image_path'] as $key => $file_pathh)
                                                                        <div class="card mb-3 border shadow-none product_Image"
                                                                            data-value="{{ $file_pathh }}">
                                                                            <div class="px-3 py-3">
                                                                                <div class="row align-items-center">
                                                                                    <div class="col ml-n2">
                                                                                        <p class="card-text small text-muted">
                                                                                            <input type="hidden"
                                                                                                name='array[{{ $json_key }}][prev_image][]'
                                                                                                value="{{ $file_pathh }}">
                                                                                            <img class="rounded"
                                                                                                src="{{ asset(Storage::url('uploads/' . $file_pathh)) }}"
                                                                                                width="70px"
                                                                                                alt="Image placeholder"
                                                                                                data-dz-thumbnail>

                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="col-auto actions">
                                                                                        <a class="action-item btn btn-sm btn-icon btn-light-secondary"
                                                                                            href=" {{ asset(Storage::url('uploads/' . $file_pathh)) }}"
                                                                                            download=""
                                                                                            data-toggle="tooltip"
                                                                                            data-original-title="{{ __('Download') }}">
                                                                                            <i data-feather="download"></i>
                                                                                        </a>
                                                                                    </div>
                                                                                    <div class="col-auto actions">
                                                                                        <a name="deleteRecord"
                                                                                            class="action-item deleteRecord btn btn-sm btn-icon bg-light-secondary me-2"
                                                                                            data-name="{{ 'uploads/' . $file_pathh }}">
                                                                                            <i data-feather="trash-2"></i>
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-footer" role="tabpanel" aria-labelledby="pills-footer">
                        <div class="row">
                            <div class="col-12 text-lg-end">
                                <button type="submit" class="btn mb-3  btn-primary submit_all"> <i data-feather="check-circle"
                                        class="me-2"></i>{{ __('Save Changes') }}</button>
                            </div>
                            <div class="col-lg-6">
                                @foreach ($getStoreThemeSetting as $json_key => $section)
                                    @php
                                        $id = '';

                                        if ($section['section_name'] == 'Home-Brand-Logo') {
                                            $id = 'Brand_Logo';
                                        }
                                        if ($section['section_name'] == 'Home-Header') {
                                            $id = 'Header_Setting';
                                            $class = 'card';
                                        }
                                        if ($section['section_name'] == 'Home-Promotions') {
                                            $id = 'Features_Setting';
                                        }
                                        if ($section['section_name'] == 'Home-Email-Subscriber') {
                                            $id = 'Email_Subscriber_Setting';
                                        }
                                        if ($section['section_name'] == 'Home-Categories') {
                                            $id = 'Categories';
                                        }
                                        if ($section['section_name'] == 'Home-Testimonial') {
                                            $id = 'Testimonials';
                                        }
                                        if ($section['section_name'] == 'Home-Footer-1') {
                                            $id = 'Footer_1';
                                        }
                                        if ($section['section_name'] == 'Home-Footer-2') {
                                            $id = 'Footer_2';
                                        }
                                        if ($section['section_name'] == 'Banner-Image') {
                                            $id = 'Banner_Img_Setting';
                                        }
                                        if ($section['section_name'] == 'Quote') {
                                            $id = 'Quote';
                                        }
                                        if ($section['section_name'] == 'Top-Purchased') {
                                            $id = 'top_purchased';
                                        }
                                        if ($section['section_name'] == 'Product-Section-Header') {
                                            $id = 'product_header';
                                        }
                                        if ($section['section_name'] == 'Latest Product') {
                                            $id = 'latest_product';
                                        }
                                        if ($section['section_name'] == 'Central-Banner') {
                                            $id = 'Banner_Setting';
                                        }
                                        if ($section['section_name'] == 'Latest-Category') {
                                            $id = 'latest_categories';
                                        }
                                        if ($section['section_name'] == 'Latest-Products') {
                                            $id = 'latest_Products';
                                        }

                                    @endphp
                                    @if ($section['section_name'] == 'Home-Footer-1')
                                        @if ($json_key == 0 || ($json_key - 1 > -1 && $getStoreThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                            <div class="d-flex mb-3 align-items-center justify-content-between">
                                                <h4 class="mb-0">{{ $section['section_name'] }} </h4>
                                                <div class="form-check form-switch custom-switch-v1">
                                                    <input type="hidden"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        value="off">
                                                    <input type="checkbox" class="form-check-input input-primary"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                        {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        @endif
                                        @endif
                                    @endforeach
                                    <div class="card border border-primary shadow-none">
                                    @foreach ($getStoreThemeSetting as $json_key => $section)
                                   
                                        
                                        @if ($section['section_name'] == 'Home-Footer-1')
                                            <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                            value="{{ $section['section_name'] }}">
                                            <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                                value="{{ $section['section_slug'] }}">
                                            <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                                value="{{ $section['array_type'] }}">
                                            <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                                value="{{ $section['loop_number'] }}">
                                            @php
                                                $loop = 1;
                                                $section = (array) $section;
                                            @endphp
                                            <div class="card-body p-3">
                                                @php $loop1 = 1; @endphp
                                                @if ($section['array_type'] == 'multi-inner-list')
                                                    @php
                                                        $loop1 = (int) $section['loop_number'];
                                                    @endphp
                                                @endif
                                                @for ($i = 0; $i < $loop1; $i++)
                                                    <div class="row">
                                                        @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                            <?php $field = (array) $field; ?>
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                value="{{ $field['field_name'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                value="{{ $field['field_slug'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                value="{{ $field['field_help_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                value="{{ $field['field_default_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                value="{{ $field['field_type'] }}">
                                                            @if ($field['field_type'] == 'text')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <input type="text"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                class="form-control"
                                                                                value="{{ $checked1 }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @else
                                                                            <input type="text" class="form-control"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                value="{{ $field['field_default_text'] }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'text area')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                            @php
                                                                                $checked1 = $field['field_default_text'];
                                                                                
                                                                                if (!empty($section[$field['field_slug']][$i])) {
                                                                                    $checked1 = $section[$field['field_slug']][$i];
                                                                                }
                                                                               
                                                                            @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <textarea name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                        @else
                                                                            <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'checkbox')
                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <div class="form-check form-switch custom-switch-v1">
                                                                            <label class="form-check-label"
                                                                                for="array[ {{ $section['section_slug'] }}][{{ $field['field_slug'] }}]">{{ $field['field_name'] }}</label>
                                                                            @if ($section['array_type'] == 'multi-inner-list')
                                                                                @php
                                                                                    $checked1 = '';
                                                                                    
                                                                                    if (!empty($section[$field['field_slug']][$i]) && $section[$field['field_slug']][$i] == 'on') {
                                                                                        $checked1 = 'checked';
                                                                                    } else {
                                                                                        if (!empty($section['section_enable']) && $section['section_enable'] == 'on') {
                                                                                            $checked1 = 'checked';
                                                                                        }
                                                                                    }
                                                                                    
                                                                                @endphp
                                                                                <input type="hidden"
                                                                                    name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                    value="off">
                                                                                <input type="checkbox"
                                                                                    class="form-check-input input-primary"
                                                                                    name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                    id="array[{{ $section['section_slug'] }}][{{ $field['field_slug'] }}]"
                                                                                    {{ $checked1 }}>
                                                                            @else
                                                                                @php
                                                                                    $checked = '';
                                                                                    if (!empty($field['field_default_text']) && $field['field_default_text'] == 'on') {
                                                                                        $checked = 'checked';
                                                                                    }
                                                                                @endphp
                                                                                <input type="hidden"
                                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                    value="off">
                                                                                <input type="checkbox"
                                                                                    class="form-check-input input-primary"
                                                                                    name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                    id="array[{{ $section['section_slug'] }}][{{ $field['field_slug'] }}]"
                                                                                    {{ $checked }}>
                                                                            @endif

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'photo upload')
                                                                <div class="col-md-6">
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        @php
                                                                            $checked2 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']])) {
                                                                                $checked2 = $section[$field['field_slug']][$i];
                                                                            
                                                                                if (is_array($checked2)) {
                                                                                    $checked2 = $checked2['field_prev_text'];
                                                                                }
                                                                            }
                                                                            $imgdisplay = \App\Models\Utility::get_file('uploads/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                value="{{ $checked2 }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if (isset($checked2) && !is_array($checked2))
                                                                            <img src="{{ asset(Storage::url('uploads/' . $checked2)) }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @endif
                                                                    @else
                                                                        @php
                                                                            $imgdisplay = \App\Models\Utility::get_file('uploads/');
                                                                            
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                                <input type="hidden" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]" value="{{ $field['field_default_text'] }}">
                                                                            <input type="file" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" class="form-control" placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }} "
                                                                            id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                            class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                            @if (!empty($getStoreThemeSetting['dashboard'])) style=""
                                                                            @else
                                                                            style="width: auto; height: 50px;" @endif
                                                                            @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                            @else
                                                                            style="width: 200px; height: 200px;" @endif>
                                                                    @endif

                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endfor
                                            </div>  
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-lg-6">
                                @foreach ($getStoreThemeSetting as $json_key => $section)
                                    @php
                                        $id = '';

                                        if ($section['section_name'] == 'Home-Brand-Logo') {
                                            $id = 'Brand_Logo';
                                        }
                                        if ($section['section_name'] == 'Home-Header') {
                                            $id = 'Header_Setting';
                                            $class = 'card';
                                        }
                                        if ($section['section_name'] == 'Home-Promotions') {
                                            $id = 'Features_Setting';
                                        }
                                        if ($section['section_name'] == 'Home-Email-Subscriber') {
                                            $id = 'Email_Subscriber_Setting';
                                        }
                                        if ($section['section_name'] == 'Home-Categories') {
                                            $id = 'Categories';
                                        }
                                        if ($section['section_name'] == 'Home-Testimonial') {
                                            $id = 'Testimonials';
                                        }
                                        if ($section['section_name'] == 'Home-Footer-1') {
                                            $id = 'Footer_1';
                                        }
                                        if ($section['section_name'] == 'Home-Footer-2') {
                                            $id = 'Footer_2';
                                        }
                                        if ($section['section_name'] == 'Banner-Image') {
                                            $id = 'Banner_Img_Setting';
                                        }
                                        if ($section['section_name'] == 'Quote') {
                                            $id = 'Quote';
                                        }
                                        if ($section['section_name'] == 'Top-Purchased') {
                                            $id = 'top_purchased';
                                        }
                                        if ($section['section_name'] == 'Product-Section-Header') {
                                            $id = 'product_header';
                                        }
                                        if ($section['section_name'] == 'Latest Product') {
                                            $id = 'latest_product';
                                        }
                                        if ($section['section_name'] == 'Central-Banner') {
                                            $id = 'Banner_Setting';
                                        }
                                        if ($section['section_name'] == 'Latest-Category') {
                                            $id = 'latest_categories';
                                        }
                                        if ($section['section_name'] == 'Latest-Products') {
                                            $id = 'latest_Products';
                                        }

                                    @endphp
                                    @if ($section['section_name'] == 'Home-Footer-2')
                                        @if ($json_key == 0 || ($json_key - 1 > -1 && $getStoreThemeSetting[$json_key - 1]['section_slug'] != $section['section_slug']))
                                            <div class="d-flex mb-3 align-items-center justify-content-between">
                                                <h4 class="mb-0">{{ $section['section_name'] }} </h4>
                                                <div class="form-check form-switch custom-switch-v1">
                                                    <input type="hidden"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        value="off">
                                                    <input type="checkbox" class="form-check-input input-primary"
                                                        name="array[{{ $json_key }}][section_enable]{{ $section['section_enable'] }}"
                                                        id="array[{{ $json_key }}]{{ $section['section_slug'] }}"
                                                        {{ $section['section_enable'] == 'on' ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                                <div class="card border border-primary shadow-none">
                                    @foreach ($getStoreThemeSetting as $json_key => $section)
                                        @if ($section['section_name'] == 'Home-Footer-2')
                                            <input type="hidden" name="array[{{ $json_key }}][section_name]"
                                            value="{{ $section['section_name'] }}">
                                            <input type="hidden" name="array[{{ $json_key }}][section_slug]"
                                                value="{{ $section['section_slug'] }}">
                                            <input type="hidden" name="array[{{ $json_key }}][array_type]"
                                                value="{{ $section['array_type'] }}">
                                            <input type="hidden" name="array[{{ $json_key }}][loop_number]"
                                                value="{{ $section['loop_number'] }}">
                                            @php
                                                $loop = 1;
                                                $section = (array) $section;
                                            @endphp
                                            <div class="card-body p-3">
                                                @php $loop1 = 1; @endphp
                                                @if ($section['array_type'] == 'multi-inner-list')
                                                    @php
                                                        $loop1 = (int) $section['loop_number'];
                                                    @endphp
                                                @endif
                                                @for ($i = 0; $i < $loop1; $i++)
                                                    <div class="row">
                                                        @foreach ($section['inner-list'] as $inner_list_key => $field)
                                                            <?php $field = (array) $field; ?>
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_name]"
                                                                value="{{ $field['field_name'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_slug]"
                                                                value="{{ $field['field_slug'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_help_text]"
                                                                value="{{ $field['field_help_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                value="{{ $field['field_default_text'] }}">
                                                            <input type="hidden"
                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_type]"
                                                                value="{{ $field['field_type'] }}">
                                                            @if ($field['field_type'] == 'text')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                        @php
                                                                            $checked1 = $field['field_default_text'];
                                                                            if (!empty($section[$field['field_slug']][$i])) {
                                                                                $checked1 = $section[$field['field_slug']][$i];
                                                                            }
                                                                        @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <input type="text"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]"
                                                                                class="form-control"
                                                                                value="{{ $checked1 }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @else
                                                                            <input type="text" class="form-control"
                                                                                name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]"
                                                                                value="{{ $field['field_default_text'] }}"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'text area')
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label
                                                                            class="form-label">{{ $field['field_name'] }}</label>
                                                                            @php
                                                                                $checked1 = $field['field_default_text'];
                                                                                
                                                                                if (!empty($section[$field['field_slug']][$i])) {
                                                                                    $checked1 = $section[$field['field_slug']][$i];
                                                                                }
                                                                               
                                                                            @endphp
                                                                        @if ($section['array_type'] == 'multi-inner-list')
                                                                            <textarea name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $checked1 }}</textarea>
                                                                        @else
                                                                            <textarea name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" id=""
                                                                                class="form-control" rows="3" placeholder="{{ $field['field_help_text'] }}">{{ $field['field_default_text'] }}</textarea>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @if ($field['field_type'] == 'photo upload')
                                                                <div class="col-md-6">
                                                                    @if ($section['array_type'] == 'multi-inner-list')
                                                                        @php
                                                                            $checked2 = $field['field_default_text'];
                                                                            
                                                                            if (!empty($section[$field['field_slug']])) {
                                                                                $checked2 = $section[$field['field_slug']][$i];
                                                                            
                                                                                if (is_array($checked2)) {
                                                                                    $checked2 = $checked2['field_prev_text'];
                                                                                }
                                                                            }
                                                                            $imgdisplay = \App\Models\Utility::get_file('uploads/');
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                            <input type="hidden"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][field_prev_text]"
                                                                                value="{{ $checked2 }}">
                                                                            <input type="file"
                                                                                name="array[{{ $json_key }}][{{ $field['field_slug'] }}][{{ $i }}][image]"
                                                                                class="form-control"
                                                                                placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        @if (isset($checked2) && !is_array($checked2))
                                                                            <img src="{{ asset(Storage::url('uploads/' . $checked2)) }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @else
                                                                            <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }}"
                                                                                style="width: auto; max-height: 80px;">
                                                                        @endif
                                                                    @else
                                                                        @php
                                                                            $imgdisplay = \App\Models\Utility::get_file('uploads/');
                                                                            
                                                                        @endphp
                                                                        <div class="form-group">
                                                                            <label
                                                                                class="form-label">{{ $field['field_name'] }}</label>
                                                                                <input type="hidden" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_prev_text]" value="{{ $field['field_default_text'] }}">
                                                                            <input type="file" name="array[{{ $json_key }}][inner-list][{{ $inner_list_key }}][field_default_text]" class="form-control" placeholder="{{ $field['field_help_text'] }}">
                                                                        </div>
                                                                        <img src="{{ $imgdisplay }}{{ $field['field_default_text'] }} "
                                                                            id="{{ $field['field_slug'] == 'header-tag' || $field['field_slug'] == 'product-header-tag' || $field['field_slug'] == 'tag-image' || $field['field_slug'] == 'homepage-footer-logo8' || $field['field_slug'] == 'homepage-category-tag-image' ? 'shadow-img' : '' }}"
                                                                            class="{{ $field['field_slug'] == 'homepage-category-tag-image' ? 'homepage-category-tag-image' : '' }}"
                                                                            @if (!empty($getStoreThemeSetting['dashboard'])) style=""
                                                                            @else
                                                                            style="width: auto; height: 50px;" @endif
                                                                            @if ($field['field_slug'] == 'homepage-footer-logo') style="width: auto; height: 80px;"
                                                                            @else
                                                                            style="width: 200px; height: 200px;" @endif>
                                                                    @endif

                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endfor
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
        @endif
        <!-- [ sample-page ] end -->
    </div>
@endsection
@push('script-page')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.js"></script>
    <script>
        function check_theme(color_val) {
            $('.theme-color').prop('checked', false);
            $('input[value="' + color_val + '"]').prop('checked', true);
        }
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })
    </script>
    <script>
        $(document).ready(function() {
            $('.repeater').repeater({
                initEmpty: false,
                show: function() {
                    $(this).slideDown();
                },
                hide: function(deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(deleteElement);
                    }
                },
                isFirstItemUndeletable: true
            })
        });



        $(".deleteRecord").click(function(e) {
            e.preventDefault();
            var id = $(this).data("name");
            var data = {
                'image': id,
                '_token': $("meta[name='csrf-token']").attr("content")
            };

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{ route('product.image.delete') }}",
                data: data,
                context: this,
                success: function(data) {
                    $(this).closest('.product_Image').remove();
                    $('.submit_all').click();
                }
            });
        });
    </script>
    <script src="{{ asset('custom/libs/summernote/summernote-bs4.js') }}"></script>
    <script>
        var Dropzones = function() {
            var e = $('[data-toggle="dropzone1"]'),
                t = $(".dz-preview");
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            e.length && (Dropzone.autoDiscover = !1, e.each(function() {
                var e, a, n, o, i;
                e = $(this), a = void 0 !== e.data("dropzone-multiple"), n = e.find(t), o = void 0, i = {
                    url: "{{ route('store.storeeditproducts', [$store->slug, $theme]) }}",
                    headers: {
                        'x-csrf-token': CSRF_TOKEN,
                    },
                    thumbnailWidth: null,
                    thumbnailHeight: null,
                    previewsContainer: n.get(0),
                    previewTemplate: n.html(),
                    maxFiles: 10,
                    parallelUploads: 10,
                    autoProcessQueue: true,
                    uploadMultiple: true,
                    acceptedFiles: a ? null : "image/*",
                    success: function(file, response) {
                        if (response.status == "success") {
                            show_toastr('success', response.success, 'success');
                            {{-- // window.location.href = "{{route('product.index')}}"; --}}
                        } else {
                            show_toastr('Error', response.msg, 'error');
                        }
                    },
                    error: function(file, response) {
                        // Dropzones.removeFile(file);
                        if (response.error) {
                            show_toastr('Error', response.error, 'error');
                        } else {
                            show_toastr('Error', response, 'error');
                        }
                    },
                    init: function() {
                        var myDropzone = this;
                    }

                }, n.html(""), e.dropzone(i)
            }))
        }()

        $("#eventBtn").click(function() {
            $("#BigButton").clone(true).appendTo("#fileUploadsContainer").find("input").val("").end();
        });
        $("#testimonial_eventBtn").click(function() {
            $("#BigButton2").clone(true).appendTo("#fileUploadsContainer2").find("input").val("").end();
        });

        $(document).on('click', '#remove', function() {
            var qq = $('.BigButton').length;

            if (qq > 1) {
                var dd = $(this).attr('data-id');

                $(this).parents('#BigButton').remove();
            }
        });
        $("input[type='file']").on("change", function() {
            var numFiles = $(this).get(0).files.length
            $('#img-count').html(numFiles + ' Images selected');
        })
    </script>
@endpush
