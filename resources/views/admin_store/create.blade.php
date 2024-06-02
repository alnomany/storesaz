{{Form::open(array('url'=>'store-resource','method'=>'post'))}}
<div class="d-flex justify-content-end">
    @php
        $plan = \App\Models\Plan::find(\Auth::user()->plan);
        $settings = \App\Models\Utility::settings();
    @endphp
    @if(\Auth::user()->type == 'super admin')
        @if(!empty($settings['chatgpt_key']))
            <a href="#" class="btn btn-primary btn-sm" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['store']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
                <i class="fas fa-robot"></i> {{ __('Generate with AI') }}
            </a>
        @endif
    @else
        @if($plan->enable_chatgpt == 'on')
            <a href="#" class="btn btn-primary btn-sm" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['store']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
                <i class="fas fa-robot"></i> {{ __('Generate with AI') }}
            </a>
        @endif
    @endif
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group">
            {{Form::label('store_name',__('Store Name'),array('class'=>'form-label'))}}
            {{Form::text('store_name',null,array('class'=>'form-control','placeholder'=>__('Enter Store Name'),'required'=>'required'))}}
        </div>

        @php
        $themeImg = \App\Models\Utility::get_file('uploads/store_theme/');
        @endphp
        @if(\Auth::user()->type !== 'super admin')
            <div class="form-group">
                {{Form::label('store_name',__('Store Theme'),array('class'=>'form-label'))}}
            </div>
            <div class="border border-primary rounded p-3">
                <div class="row gy-4 ">
                    {{ Form::hidden('themefile', null, ['id' => 'themefile1']) }}
                    @foreach (\App\Models\Utility::themeOne() as $key => $v)
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="theme-card border-primary s_{{ $key }}  {{ $store_settings['theme_dir'] == $key ? 'selected' : ''  }}">
                                <div class="theme-card-inner">
                                    <div class="theme-image border  rounded">
                                        <img src="{{ asset(Storage::url('uploads/store_theme/' . $key . '/Home.png')) }}"
                                            class="color img-center pro_max_width pro_max_height {{ $key }}_img"
                                            data-id="{{ $key }}">
                                    </div>
                                    <div class="theme-content mt-3">
                                        <p class="mb-0">{{ __('Select Sub-Color') }}</p>
                                        <div class="d-flex mt-2 justify-content-between align-items-center {{ $key == 'theme10' ? 'theme10box' : '' }}"
                                            id="radio_{{ $key }}">
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    @if(\Auth::user()->type == 'super admin')
        <div class="col-12">
            <div class="form-group">
                {{Form::label('name',__('Name'),array('class'=>'form-label'))}}
                {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Name'),'required'=>'required'))}}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{Form::label('email',__('Email'),array('class'=>'form-label'))}}
                {{Form::email('email',null,array('class'=>'form-control','placeholder'=>__('Enter Email'),'required'=>'required'))}}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="password_switch">{{ __('Login is enable') }}</label>
                <div class="form-check form-switch custom-switch-v1 float-end">
                    <input type="checkbox" name="password_switch" class="form-check-input input-primary pointer" value="on" id="password_switch">
                    <label class="form-check-label" for="password_switch"></label>
                </div>
            </div>
        </div>
        <div class="col-12 ps_div d-none">
            <div class="form-group">
                {{Form::label('password',__('Password'),array('class'=>'form-label'))}}
                {{Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter Password')))}}
                @error('password')
                    <small class="invalid-password" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </small>
                @enderror
            </div>
        </div>
    @endif
    <div class="form-group col-12 d-flex justify-content-end col-form-label">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Save')}}" class="btn btn-primary ms-2">
    </div>
    <script>
        $('body').on('click', 'input[name="theme_color"]', function() {
            var eleParent = $(this).attr('data-theme');
            $('#themefile1').val(eleParent);
            var imgpath = $(this).attr('data-imgpath');
            $('.' + eleParent + '_img').attr('src', imgpath);
        });
        $('body').ready(function() {
            setTimeout(function(e) {
                var checked = $("input[type=radio][name='theme_color']:checked");
                $('#themefile1').val(checked.attr('data-theme'));
                $('.' + checked.attr('data-theme') + '_img').attr('src', checked.attr('data-imgpath'));
            }, 300);
        });
        $(".color").click(function() {
            var dataId = $(this).attr("data-id");
            $('#radio_' + dataId).trigger('click');
            var first_check = $('#radio_' + dataId).find('.color-0').trigger("click");
            $( ".theme-card" ).each(function() {
                $(".theme-card").removeClass('selected');     
            });
            $('.s_' +dataId ).addClass('selected');
        });
    </script>
</div>

{{Form::close()}}
