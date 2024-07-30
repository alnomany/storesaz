@if(!empty($vender))
    <div class="row">
        <div class="col-md-5">
            <div class="bill-to">
                @if(!empty($vender['name']))
                <small>
                    <span>{{$vender['name']}}</span><br>
                    <span>{{$vender['phone_number']}}</span><br>
                    <span>{{$vender['country'] . ' , '.$vender['city'].' , '.$vender['address'].'.'}}</span>
                    <br><br><br>
                </small>
                @else
                    <br> -
                @endif
            </div>
        </div>
     
        <div class="col-md-2">
            <a href="#" id="remove" class="text-sm">{{__(' Remove')}}</a>
        </div>
    </div>
@endif
