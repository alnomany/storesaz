{{Form::open(array('url'=>'supplierstore','method'=>'post','enctype'=>'multipart/form-data'))}}
<div class="d-flex justify-content-end">
    @php
        $plan = \App\Models\Plan::find(\Auth::user()->plan);
    @endphp
    @if($plan->enable_chatgpt == 'on')
        <a href="#" class="btn btn-primary btn-sm" data-size="lg" data-ajax-popup-over="true" data-url="{{ route('generate',['category']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}" data-title="{{ __('Generate Content With AI') }}">
            <i class="fas fa-robot"></i> {{ __('Generate with AI') }}
        </a>
    @endif
</div>
<div class="row">
    <div class="col-6">
        <div class="form-group">
            {{Form::label('name',__('Name'),array('class'=>'col-form-label'))}}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Name'),'required'=>'required'))}}
        </div>
        <div class="form-group">
            {{Form::label('email',__('Email'),array('class'=>'col-form-label'))}}
            {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Email'),'required'=>'required'))}}
        </div>
        <div class="form-group">
            {{Form::label('phone_number',__('Number'),array('class'=>'col-form-label'))}}
            {{Form::text('phone_number',null,array('class'=>'form-control','placeholder'=>__('Number'),'required'=>'required'))}}
        </div>
        <div class="form-group">
            {{ Form::hidden('store_id', Auth::user()->current_store, ['class' => 'form-control', 'placeholder' => __('Store ID')]) }}
        </div>
    </div>
    <div class="col-6">
      
        <div class="form-group">
            {{Form::label('city',__('City'),array('class'=>'col-form-label'))}}
            {{Form::text('city',null,array('class'=>'form-control','placeholder'=>__('select city'),'required'=>'required'))}}
        </div>
        <div class="form-group">
            {{Form::label('country',__('Country'),array('class'=>'col-form-label'))}}
            {{Form::text('country',null,array('class'=>'form-control','placeholder'=>__('Select Country'),'required'=>'required'))}}
        </div>
        <div class="form-group">
            {{Form::label('tax_number',__('Tax'),array('class'=>'col-form-label'))}}
            {{Form::text('tax_number',null,array('class'=>'form-control','placeholder'=>__('Tax')))}}
        </div>
  
    </div>
   
    <div class="form-group col-12 d-flex justify-content-end col-form-label">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Save')}}" class="btn btn-primary ms-2">
    </div>
</div>
{{Form::close()}}
