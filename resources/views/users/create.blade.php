<form method="post" action="{{ route('users.store') }}">
    @csrf
    <div class="row">
        <div class="form-group col-md-12">
            {{Form::label('name',__('Name'),array('class'=>'form-label'))}}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Name'),'required'=>'required'))}}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('Email',__('Email'),array('class'=>'form-label')) }}
            {{ Form::email('email',null,array('class'=>'form-control','placeholder'=>__('Enter Email'),'required'=>'required')) }}
        </div>
        {{-- <div class="form-group col-md-12">
            {{ Form::label('Password',__('Password'),array('class'=>'form-label')) }}
            {{ Form::password('password',array('class'=>'form-control','placeholder'=>__('Enter Password'),'required'=>'required')) }}
        </div> --}}
        <div class="form-group col-md-12">
            {{ Form::label('User Role',__('User Role'),array('class'=>'form-label')) }}
            {{ Form::select('role',$roles,null,array('class'=>'form-control','placeholder'=>__('Select Role'),'required'=>'required')) }}
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
                {{ Form::label('password', __('Password'), ['class' => 'col-form-label']) }}
                {{ Form::password('password', ['class' => 'form-control', 'placeholder' => __('Enter Password')]) }}
                @error('password')
                    <small class="invalid-password" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </small>
                @enderror
            </div>
        </div>
        <div class="form-group col-12 d-flex justify-content-end col-form-label">
            <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
            <input type="submit" value="{{__('Save')}}" class="btn btn-primary ms-2">
        </div>
    </div>
</form>
