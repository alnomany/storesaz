{{Form::open(array('url'=>'product_categorie','method'=>'post','enctype'=>'multipart/form-data'))}}
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
    <div class="col-12">
        <div class="form-group">
            {{Form::label('name',__('Name'),array('class'=>'col-form-label'))}}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Product Category'),'required'=>'required'))}}
        </div>

        <div class="form-group">
            <label for="categorie_img" class="col-form-label">{{ __('Upload Category Image') }}</label>
            {{-- <input type="file" name="categorie_img" id="categorie_img"  class="form-control"> --}}
            <input type="file" name="categorie_img" id="categorie_img" class="form-control" onchange="document.getElementById('catImg').src = window.URL.createObjectURL(this.files[0])" multiple>
            <img id="catImg" src="" width="20%" class="mt-2"/>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group col-md-12 d-block">
            {{ Form::label('type', __('Category Type'),['class'=>'form-label']) }}
            {{ Form::select('type',$types,null, array('class' => 'form-control select cattype ','required'=>'required')) }}
        </div>
        <div class="form-group col-md-12 account d-none">
            {{Form::label('chart_account_id',__('Account'),['class'=>'form-label'])}}
            <select class="form-control select" name="chart_account" id="chart_account">
            </select>
        </div>
    </div>
    <div class="form-group col-12 d-flex justify-content-end col-form-label">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Save')}}" class="btn btn-primary ms-2">
    </div>
</div>
{{Form::close()}}
<script>
        //hide & show chartofaccount

        $(document).on('click', '.cattype', function ()
    {
        var type = $(this).val();
        if (type != 'product & service') {
            $('.account').removeClass('d-none')
            $('.account').addClass('d-block');
        } else {
            $('.account').addClass('d-none')
            $('.account').removeClass('d-block');
        }
    });
    
    $(document).on('change', '#type', function () {
        var type = $(this).val();

        $.ajax({
            url: '{{route('productServiceCategory.getaccount')}}',
            type: 'POST',
            data: {
                "type": type,
                "_token": "{{ csrf_token() }}",
            },

            success: function (data) {
                $('#chart_account').empty();
                $.each(data.chart_accounts, function (key, value) {
                    $('#chart_account').append('<option value="' + key + '" class="subAccount">' + value + '</option>');
                    $.each(data.sub_accounts, function (subkey, subvalue) {
                        if(key == subvalue.account)
                        {
                            $('#chart_account').append('<option value="' + subvalue.id + '">' + '&nbsp; &nbsp;&nbsp;' + subvalue.name + '</option>');
                        }
                });
                });
            }

        });
    });
</script>