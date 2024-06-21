{{Form::model($location, array('route' => array('location.update', $location->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="col-12">
        <div class="form-group">
            {{ Form::label('name', __('Name'), ['class' => 'col-form-label']) }}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Product Category')))}}
            @error('country_name')
            <span class="invalid-country_name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    
</div>
<div class="row">
    
    <div class="col-md-6 col-12">
        <div class="form-group">
            {{Form::label('billing_country',__('Country'),array("class"=>"form-control-label")) }} <span style="color:red">*</span>
            {{--  {{Form::text('billing_country',old('billing_country'),array('class'=>'form-control','placeholder'=>__('Billing Country'),'required'=>'required'))}}  --}}
            <select name="billing_country" id="" class="form-control change_country" required>
                <option value="">{{ __('Select Country') }}</option>
                @foreach($countries as $key => $value)
                    <option value="{{ $key }}">{{ $key }}</option>
                @endforeach   
            </select>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="form-group">
            {{Form::label('billing_city',__('City'),array("class"=>"form-control-label")) }} <span style="color:red">*</span>
            {{--  {{Form::text('billing_city',old('billing_city'),array('class'=>'form-control','placeholder'=>__('Billing City'),'required'=>'required'))}}  --}}
            <select name="billing_city" id="city" class="form-control" required>  
                <option value="">{{ __('select city') }}</option>
            </select>  
        </div>
    </div>
</div>
<div class="form-group col-12 d-flex justify-content-end col-form-label">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary ms-2">
</div>
{{Form::close()}}
<script>
    $(document).on('change','.change_country',function(){
        var country = $('.change_country').val();
        $.ajax({
            url : '{{ route('user.city',[$store->slug,'_country']) }}'.replace('_country',country),
            method : 'POST',
            data : {
                "_token":"{{ csrf_token() }}",
            },
            context : this,
            dataType : 'json',
            success : function(data){
                $('#city').html('<option value="">Select city</option>'); 
                $.each(data.cities,function(key,value){
                    $("#city").append('<option value="'+value+'">'+value+'</option>');
                });
            }
        }); 
    });
</script>