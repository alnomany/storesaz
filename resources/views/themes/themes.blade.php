@extends('layouts.admin')
@section('page-title')
        {{ __('Manage Themes') }}
@endsection


@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-bold mb-0 text-white">{{ __('Manage Themes') }}</h5>
    </div>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Home') }}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ __('Themes') }}</li>
@endsection

@section('action-btn')
@endsection

@section('filter')
@endsection

@section('content')
<style>
    :root {
        --theme-color: red !important;
        
    }
</style>
    <div class="tab-pane" id="pills-theme_setting" role="tabpanel" aria-labelledby="pills-theme_setting">
        {{ Form::open(['route' => ['store.changetheme', $store_settings->id], 'method' => 'POST']) }}
        <div class="d-flex mb-3 align-items-center justify-content-between">
            <h3>{{ __('Themes') }}</h3>
            {{ Form::hidden('themefile', null, ['id' => 'themefile']) }}
            <button type="submit" class="btn  btn-primary"> <i data-feather="check-circle"
                    class="me-2"></i>{{ __('Save Changes') }}</button>

        </div>
        <!-- i added -->
    
        @php
            $themeImg = \App\Models\Utility::get_file('uploads/store_theme/');
        @endphp
        <div class="border border-primary rounded p-3">
            <div class="row gy-4 ">
                @foreach (\App\Models\Utility::themeOne() as $key => $v)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="theme-card border-primary {{ $key }} {{ $store_settings['theme_dir'] == $key ? 'selected' : ''  }}">
                            <div class="theme-card-inner">
                                <div class="theme-image border  rounded">
                                    <img src="{{ asset(Storage::url('uploads/store_theme/' . $key . '/Home0.png')) }}"
                                        class="color1 img-center pro_max_width pro_max_height {{ $key }}_img"
                                        data-id="{{ $key }}">
                                </div>
                                <div class="theme-content mt-3">
                                    <p class="mb-0">{{ __('Select Sub-Color') }}</p>
                                    <div class="d-flex mt-2 justify-content-between align-items-center {{ $key == 'theme10' ? 'theme10box' : '' }}"
                                        id="{{ $key }}">
                                        <div class="color-inputs">
                                            @foreach ($v as $css => $val)
                                                <label class="colorinput">
                                                    <input name="theme_color" id="color1-theme4" type="radio"
                                                        value="{{ $css }}" data-theme="{{ $key }}"
                                                        data-imgpath="{{ $val['img_path'] }}"
                                                        class="colorinput-input color-{{ $loop->index++ }}"
                                                        {{ isset($store_settings['store_theme']) && $store_settings['store_theme'] == $css && $store_settings['theme_dir'] == $key ? 'checked' : '' }}>
                                                    <span class="border-box">
                                                        <span class="colorinput-color"
                                                            style="background: #{{ $val['color'] }}"></span>
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <!--i added -->
                                        <div class="color-inputs">
                                                <label class="colorinput">
                                                    <input type ="color" class="colorinput-input color-1" name="theme_color1" id="color-picker-{{$key}}" >
                                                        <input type="hidden" name="theme_color_new_{{ $key }}" id="theme_color-{{$key}}" value="{{ isset($store_settings['color_' . $key]) ? $store_settings['color_' . $key] : '' }}">
                                                    <span class="border-box" id="value2">
                                                        <span class="colorinput-color" id="value-{{$key}}"
                                                            
                                                            style="color:black; background: {{ isset($store_settings['color_' . $key]) ? $store_settings['color_' . $key] : 'white' }};"></span>
                                                    </span>
                                                </label>
                                        </div>
                                        @can('Edit Themes')
                                            @if (isset($store_settings['theme_dir']) && $store_settings['theme_dir'] == $key)
                                                <a href="{{ route('store.editproducts', [$store_settings->slug, $key]) }}"
                                                    class="btn btn-primary" id="button-addon2"> <i data-feather="edit"></i>
                                                    {{ __('Edit') }}</a>
                                            @endif
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('script-page')
    <script>
        var colorPicker = document.getElementById("color-picker-theme2");
        var colorPicker1 = document.getElementById("color-picker-theme1");

        var theme_color1 = document.getElementById("theme_color-theme1");
        var theme_color2 = document.getElementById("theme_color-theme2");
        //i stoped here
       // var colorPicker1 = document.getElementById("color-picker-theme1");


        
        var colorValue1 = document.getElementById("value-theme1");
        var colorValue2 = document.getElementById("value-theme2");

        colorPicker.onchange = function() {
            theme_color2.value = colorPicker.value;
            colorValue2.style.backgroundColor = colorPicker.value;
        }
        colorPicker1.onchange = function() {
            theme_color1.value = colorPicker1.value;
            colorValue1.style.backgroundColor = colorPicker1.value;
        }
        $(document).on('click', 'input[name="theme_color"]', function() {
            var eleParent = $(this).attr('data-theme');
            $('#themefile').val(eleParent);
            var imgpath = $(this).attr('data-imgpath');
            $('.' + eleParent + '_img').attr('src', imgpath);
        });
        $(document).ready(function() {
            setTimeout(function(e) {
                var checked = $("input[type=radio][name='theme_color']:checked");
                $('#themefile').val(checked.attr('data-theme'));
                $('.' + checked.attr('data-theme') + '_img').attr('src', checked.attr('data-imgpath'));
            }, 300);
        });
        $(".color1").click(function() {
            var dataId = $(this).attr("data-id");
            $('#' + dataId).trigger('click');
            var first_check = $('#' + dataId).find('.color-0').trigger("click");
            $( ".theme-card" ).each(function() {
                $(".theme-card").removeClass('selected');     
            });
           $('.' +dataId).addClass('selected');
           
        });
    </script>
@endpush