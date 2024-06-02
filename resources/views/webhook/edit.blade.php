@php
    $modules = \App\Models\Webhook::modules();
    $methods = \App\Models\Webhook::methods();
@endphp

{{ Form::open(['route' => ['webhook.update' , $webhook[0]['id']], 'method' => 'PUT']) }}


    <div class="row">
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('Module', __('Module'), ['class' => 'col-form-label']) }}
                <select name="module" class="form-control select2" id="module">
                    @foreach ($modules as $key => $value)
                    <option value = "{{ $key }}" {{ $key == $webhook[0]['module'] ? 'selected' : '' }}>{{__($value)}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('Method', __('Method'), ['class' => 'col-form-label']) }}
                <select name="method" class="form-control select2" id="method">
                    @foreach ($methods as $key => $value)
                    <option value = "{{ $key }}" {{ $key == $webhook[0]['method'] ? 'selected' : '' }}>{{__($value)}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('Url', __('Url'), ['class' => 'col-form-label']) }}
                {{ Form::text('webbbook_url', $webhook[0]['url'], ['class' => 'form-control ', 'placeholder' => 'WebBook Url']) }}
            </div>
        </div>
    </div>
    <div class="form-group col-12 d-flex justify-content-end col-form-label">
        <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Create') }}" class="btn btn-primary ms-2">
    </div>

{{ Form::close() }}


