@extends('storefront.layout.theme3')
@section('page-title')
    {{__('Product Details')}}
@endsection
@php
     $productImg = \App\Models\Utility::get_file('uploads/is_cover_image/');
@endphp
@section('content')
@php
    if(!empty(session()->get('lang')))
    {
        $currantLang = session()->get('lang');
    }else{
        $currantLang = $store->lang;
    }
    $languages=\App\Models\Utility::languages();
    $storethemesetting=\App\Models\Utility::demoStoreThemeSetting($store->id,$store->theme_dir);
@endphp
<div class="wrapper">
    <section class="cart-section padding-bottom padding-top">
        <div class="container">
            <div class="row align-items-center cart-head">
                <div class="col-lg-3 col-md-12 col-12">
                    <div class="cart-title">
                        <h2>{{ __('Customer') }}</h2>
                    </div>
                </div>
                <div class="col-lg-9 col-md-12 col-12 justify-content-end">
                    <div class="cart-header-btn">
                        <a href="{{ route('store.cart', $store->slug) }}">1 - {{ __('My Cart') }}</a>
                        <a href="{{ route('user-address.useraddress', $store->slug) }}" class="active-btn">2 -{{ __('Customer') }}</a>
                        <a href="{{ route('store-payment.payment', $store->slug) }}">3 - {{ __('Payment') }}</a>
                    </div>
                </div>

            </div>
            {{ Form::model($cust_details, ['route' => ['store.customer', $store->slug], 'method' => 'POST']) }}
                <div class="row">
                    <div class="col-lg-8 col-12">
                        <div class="customer-info">
                            <h5>{{ __('Billing information') }}</h5>
                            <p> {{ __('Fill the form below so we can send you the orders invoice.') }}</p>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {{Form::label('name',__('First Name'),array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                    {{Form::text('name',old('name'),array('class'=>'form-control','placeholder'=>__('Enter Your First Name'),'required'=>'required'))}}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {{Form::label('last_name',__('Last Name'),array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                    {{Form::text('last_name',old('last_name'),array('class'=>'form-control','placeholder'=>__('Enter Your Last Name'),'required'=>'required'))}}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {{Form::label('phone',__('Phone'),array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                    {{Form::text('phone',old('phone'),array('class'=>'form-control','placeholder'=>'(99) 12345 67890','required'=>'required'))}}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {{Form::label('email',__('Email'),array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                    {{Form::email('email',(Utility::CustomerAuthCheck($store->slug) ? Auth::guard('customers')->user()->email : ''),array('class'=>'form-control','placeholder'=>__('Enter Your Email Address'),'required'=>'required'))}}
                                </div>
                            </div>
                            @if(!empty($store_payment_setting['custom_field_title_1']))
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {{Form::label('custom_field_title_1',$store_payment_setting['custom_field_title_1'],array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                    {{Form::text('custom_field_title_1',old('custom_field_title_1'),array('class'=>'form-control','placeholder'=>'Enter '.$store_payment_setting['custom_field_title_1'],'required'=>'required'))}}
                                </div>
                            </div>
                            @endif
                            @if(!empty($store_payment_setting['custom_field_title_2']))
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        {{Form::label('custom_field_title_2',$store_payment_setting['custom_field_title_2'],array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                        {{Form::text('custom_field_title_2',old('custom_field_title_2'),array('class'=>'form-control','placeholder'=>'Enter '.$store_payment_setting['custom_field_title_1'],'required'=>'required'))}}
                                    </div>
                                </div>
                            @endif
                            @if(!empty($store_payment_setting['custom_field_title_3']))
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            {{Form::label('custom_field_title_3',$store_payment_setting['custom_field_title_3'],array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                            {{Form::text('custom_field_title_3',old('custom_field_title_3'),array('class'=>'form-control','placeholder'=>'Enter '.$store_payment_setting['custom_field_title_1'],'required'=>'required'))}}
                                        </div>
                                    </div>
                            @endif
                            @if(!empty($store_payment_setting['custom_field_title_4']))
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        {{Form::label('custom_field_title_4',$store_payment_setting['custom_field_title_4'],array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                        {{Form::text('custom_field_title_4',old('custom_field_title_4'),array('class'=>'form-control','placeholder'=>'Enter '.$store_payment_setting['custom_field_title_1'],'required'=>'required'))}}
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    {{Form::label('billingaddress',__('Address'),array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                    {{Form::text('billing_address',old('billing_address'),array('class'=>'form-control','placeholder'=>__('Billing Address'),'required'=>'required'))}}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group focused">
                                    {{Form::label('billing_country',__('Country'),array("class"=>"form-control-label")) }} <span style="color:red">*</span>
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
                                    <select name="billing_city" id="city" class="form-control" required>  
                                        <option value="">{{ __('select city') }}</option>
                                    </select> 
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {{Form::label('billing_postalcode',__('Postal Code'),array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                    {{Form::text('billing_postalcode',old('billing_postalcode'),array('class'=>'form-control','placeholder'=>__('Billing Postal Code'),'required'=>'required'))}}
                                </div>
                            </div>
                            @if($store->enable_shipping == "on" && $shippings->count() > 0)
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        {{Form::label('location_id',__('Location'),array("class"=>"form-control-label")) }} <span style="color:red">*</span>
                                        {{ Form::select('location_id', $locations, null,array('class' => 'form-control change_location','required'=>'required')) }}
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-12 col-12">
                                <div class="row align-items-center">
                                    <div class="col-md-6 col-12">
                                        <div class="customer-info">
                                            <h5>{{__('Shipping informations')}}</h5>
                                            <p>{{__('Fill the form below so we can send you the orders invoice.')}}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="addres-btn d-flex justify-content-end">
                                            <a class="btn" onclick="billing_data()" id="billing_data" data-toggle="tooltip" data-placement="top" title="Same As Billing Address">
                                                {{__('Copy Address')}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    {{Form::label('shipping_address',__('Address'),array("class"=>"form-control-label")) }}
                                    {{Form::text('shipping_address',old('shipping_address'),array('class'=>'form-control','placeholder'=>__('Shipping Address')))}}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {{Form::label('shipping_country',__('Country'),array("class"=>"form-control-label")) }}
                                    {{Form::text('shipping_country',old('shipping_country'),array('class'=>'form-control','placeholder'=>__('Shipping Country')))}}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {{Form::label('shipping_city',__('City'),array("class"=>"form-control-label")) }}
                                    {{Form::text('shipping_city',old('shipping_city'),array('class'=>'form-control','placeholder'=>__('Shipping City')))}}
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    {{Form::label('shipping_postalcode',__('Postal Code'),array("class"=>"form-control-label")) }}
                                    {{Form::text('shipping_postalcode',old('shipping_postalcode'),array('class'=>'form-control','placeholder'=>__('Shipping Postal Code')))}}
                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <div class="pagination-btn d-flex align-items-center justify-content-center ">
                                    <a href="{{route('store.slug',$store->slug)}}" class="btn back-btn">{{__('Return to shop')}}</a>
                                    <button type="submit" class="next-btn btn">{{__('Next step')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-12 right-side ">
                        <div class="shiping-type internal-box">
                            <div class="internal-header">
                                <h5>{{__('Select Shipping')}}</h5>
                                <div class="radio-group" id="shipping_location_content">
                                </div>
                            </div>
                        </div>
                        <div class="coupon-form internal-box">
                            <div class="internal-header">
                                <h4>{{__('Coupon')}}</h4>
                            </div>
                            <div class="internal-box-body">
                                <form action="">
                                    <div class="input-wrapper">
                                        <input type="text" id="stripe_coupon" name="coupon" class="coupon hidd_val" placeholder="{{ __('Enter Coupon Code') }}">
                                        <input type="hidden" name="coupon" class="hidden_coupon" value="">
                                    </div>
                                    <div class="btn-wrapper">
                                        <button type="submit" class="btn apply-coupon">{{ __('Apply') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="mini-cart internal-box" id="card-summary">
                            <div class="internal-header">
                                <h4>{{ __('Summary') }}</h4>
                            </div>
                            <div id="cart-body" class="mini-cart-has-item">
                                <div class="mini-cart-body">
                                    @if (!empty($products))
                                        @php
                                            $total = 0;
                                            $sub_tax = 0;
                                            $sub_total = 0;
                                        @endphp
                                        @foreach ($products as $product)
                                            @if (isset($product['variant_id']) && !empty($product['variant_id']))
                                                <div class="mini-cart-item">
                                                    <div class="mini-cart-image">
                                                        <a href="#">
                                                            <img src="{{$productImg .$product['image']}}" alt="img">
                                                        </a>
                                                    </div>
                                                    <div class="mini-cart-details">
                                                        <p class="mini-cart-title">
                                                            <a href="#">{{$product['product_name'].' - ( ' . $product['variant_name'] .' ) '}}</a>
                                                        </p>
                                                        @php
                                                            $total_tax=0;
                                                        @endphp
                                                        <div class="pvarprice d-flex align-items-center justify-content-between">
                                                            <div class="price">
                                                                <small>
                                                                    {{$product['quantity']}} x {{\App\Models\Utility::priceFormat($product['variant_price'])}}
                                                                    @if(!empty($product['tax']))
                                                                        +
                                                                        @foreach($product['tax'] as $tax)
                                                                            @php
                                                                                $sub_tax = ($product['variant_price'] * $product['quantity'] * $tax['tax']) / 100;
                                                                                $total_tax += $sub_tax;
                                                                            @endphp
    
                                                                            {{\App\Models\Utility::priceFormat($sub_tax).' ('.$tax['tax_name'].' '.($tax['tax']).'%)'}}
                                                                        @endforeach
                                                                    @endif
                                                                </small>
                                                                @php
                                                                    $totalprice = $product['variant_price'] * $product['quantity'] + $total_tax;
                                                                    $subtotal = $product['variant_price'] * $product['quantity'];
                                                                    $sub_total += $subtotal;
                                                                @endphp
                                                            </div>
                                                            <a class="remove_item">
                                                                {{\App\Models\Utility::priceFormat($totalprice)}}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                @php
                                                    $total += $totalprice;
                                                @endphp
                                            @else
                                                <div class="mini-cart-item">
                                                    <div class="mini-cart-image">
                                                        <a href="#">
                                                            <img src="{{$productImg .$product['image']}}" alt="img">
                                                        </a>
                                                    </div>
                                                    <div class="mini-cart-details">
                                                        <p class="mini-cart-title">
                                                            <a href="#">{{$product['product_name']}}</a>
                                                        </p>
                                                        @php
                                                            $total_tax=0;
                                                        @endphp
                                                        <div class="pvarprice d-flex align-items-center justify-content-between">
                                                            <div class="price">
                                                                <small>
                                                                    {{$product['quantity']}} x {{\App\Models\Utility::priceFormat($product['price'])}}
                                                                    @if(!empty($product['tax']))
                                                                        +
                                                                        @foreach($product['tax'] as $tax)
                                                                            @php
                                                                                $sub_tax = ($product['price'] * $product['quantity'] * $tax['tax']) / 100;
                                                                                $total_tax += $sub_tax;
                                                                            @endphp
        
                                                                            {{\App\Models\Utility::priceFormat($sub_tax).' ('.$tax['tax_name'].' '.($tax['tax']).'%)'}}
                                                                        @endforeach
                                                                    @endif
                                                                </small>
                                                                @php
                                                                    $totalprice = $product['price'] * $product['quantity'] + $total_tax;
                                                                    $subtotal = $product['price'] * $product['quantity'];
                                                                    $sub_total += $subtotal;
                                                                @endphp
                                                            </div>
                                                            <a class="remove_item" href="#">
                                                                {{\App\Models\Utility::priceFormat($totalprice)}}
                                                            </a>
                                                            @php
                                                            $total += $totalprice;
                                                            @endphp
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                <div class="mini-cart-footer">
                                    <ul class="cart-summery">
                                        <li>
                                            <span class="cart-sum-left">{{ __('item') }}</span>
                                            <span class="cart-sum-right">{{\App\Models\Utility::priceFormat( !empty($sub_total)?$sub_total:'0')}}</span>
                                        </li> 
                                        @if($store->enable_shipping == "on")
                                            <li>
                                                <span class="cart-sum-left">{{__('Shipping Price')}} </span>
                                                <span class="cart-sum-right shipping_price" data-value=""></span>
                                            </li>
                                        @endif
                                            <li>
                                                <span class="cart-sum-left">{{__('Coupon')}} </span>
                                                <span class="cart-sum-right dicount_price">{{\App\Models\Utility::priceFormat(0)}}</span>
                                            </li>
                                        @foreach($taxArr['tax'] as $k=>$tax)
                                            <li>
                                                @php
                                                    $rate = $taxArr['rate'][$k];
                                                @endphp
                                                <span class="cart-sum-left">{{$tax}}</span>
                                                <span class="cart-sum-right"> {{\App\Models\Utility::priceFormat($rate)}}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div
                                        class="mini-cart-footer-total-row d-flex align-items-center justify-content-between">
                                        <div class="mini-total-lbl">
                                            {{__('Total')}}
                                        </div>
                                        <div class="mini-total-price final_total_price" id="total_value" data-value="666">
                                            <input type="hidden" class="product_total" value="{{$total}}">
                                            <input type="hidden" class="total_pay_price" value="{{App\Models\Utility::priceFormat($total)}}">
                                            <span class="pro_total_price" data-value="{{\App\Models\Utility::priceFormat(!empty($total)?$total:0)}}">  {{\App\Models\Utility::priceFormat(!empty($total)?$total:'0')}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {{ Form::close() }} 
        </div>
    </section>
</div>
@endsection
@push('script-page')
{{--<script src="{{asset('assets/theme3/js/all.min.js')}}"></script>--}}
{{-- <script src="{{asset('assets/theme3/js/purpose.core.js')}}"></script> --}}
<script src="{{asset('assets/theme3/js/swiper.min.js')}}"></script>
<script src="{{asset('assets/theme3/js/purpose.js')}}"></script>
<script src="{{asset('custom/js/custom.js')}}"></script>
<script src="{{ asset('custom/libs/bootstrap-notify/bootstrap-notify.min.js')}}"></script>


@stack('script-page')
@if(Session::has('success'))
    <script>
        show_toastr('{{__('Success')}}', '{!! session('success') !!}', 'success');
    </script>
    {{ Session::forget('success') }}
@endif
@if(Session::has('error'))
    <script>
        show_toastr('{{__('Error')}}', '{!! session('error') !!}', 'error');
    </script>
    {{ Session::forget('error') }}
@endif
@php
    $store_settings = \App\Models\Store::where('slug',$store->slug)->first();
@endphp
<script async src="https://www.googletagmanager.com/gtag/js?id={{$store_settings->google_analytic}}"></script>
{!! $store_settings->storejs !!}
<script>
    function billing_data() {
        $("[name='shipping_address']").val($("[name='billing_address']").val());
        $("[name='shipping_city']").val($("[name='billing_city']").val());
        $("[name='shipping_state']").val($("[name='billing_state']").val());
        $("[name='shipping_country']").val($("[name='billing_country']").val());
        $("[name='shipping_postalcode']").val($("[name='billing_postalcode']").val());
    }

    $(document).ready(function() {
        $('.change_location').trigger('change');

        setTimeout(function() {
            var shipping_id = $("input[name='shipping_id']:checked").val();
            getTotal(shipping_id);
        }, 200);
    });

    $(document).on('change', '.shipping_mode', function() {
        var shipping_id = this.value;
        getTotal(shipping_id);
    });

    function getTotal(shipping_id) {
        var pro_total_price = $('.pro_total_price').attr('data-value');
        if (shipping_id == undefined) {
            $('.shipping_price_add').hide();
            return false
        } else {
            $('.shipping_price_add').show();
        }

        $.ajax({
            url: '{{ route('user.shipping', [$store->slug, '_shipping']) }}'.replace('_shipping', shipping_id),
            data: {
                "pro_total_price": pro_total_price,
                "_token": "{{ csrf_token() }}",
            },
            method: 'POST',
            context: this,
            dataType: 'json',

            success: function(data) {
                var price = data.price + pro_total_price;
                $('.shipping_price').html(data.price);
                $('.shipping_price').attr('data-value', data.price);
                $('.pro_total_price').html(data.total_price);
            }
        });
    }

    $(document).on('change', '.change_location', function() {
        var location_id = $('.change_location').val();

        if (location_id == 0) {
            $('#location_hide').hide();

        } else {
            $('#location_hide').show();

        }

        $.ajax({
            url: '{{ route('user.location', [$store->slug, '_location_id']) }}'.replace('_location_id',
                location_id),
            data: {
                "_token": "{{ csrf_token() }}",
            },
            method: 'POST',
            context: this,
            dataType: 'json',

            success: function(data) {
                var html = '';
                var shipping_id =
                    '{{ isset($cust_details['shipping_id']) ? $cust_details['shipping_id'] : '' }}';
                $.each(data.shipping, function(key, value) {
                    var checked = '';
                    if (shipping_id != '' && shipping_id == value.id) {
                        checked = 'checked';
                    }

                    html +=
                        '<div class="shipping_location"><input type="radio" name="shipping_id" data-id="' +
                        value.price + '" value="' + value.id + '" id="shipping_price' +
                        key + '" class="shipping_mode" ' + checked + '>' +
                        ' <label name="shipping_label" for="shipping_price' + key +
                        '" class="shipping_label"> ' + value.name + '</label></div>';

                });
                $('#shipping_location_content').html(html);
            }
        });
    });

    $(document).on('click', '.apply-coupon', function(e) {
        e.preventDefault();

        var ele = $(this);
        var coupon = ele.closest('.row').find('.coupon').val();
        var hidden_field = $('.hidden_coupon').val();
        var price = $('#card-summary .product_total').val();
        var shipping_price = $('#card-summary .shipping_price').attr('data-value');
        if (coupon == hidden_field && coupon != "") {
            show_toastr('Error', 'Coupon Already Used', 'error');
        } else {
            if (coupon != '') {
                $.ajax({
                    url: '{{ route('apply.productcoupon') }}',
                    datType: 'json',
                    data: {
                        price: price,
                        shipping_price: shipping_price,
                        store_id: {{ $store->id }},
                        coupon: coupon
                    },
                    success: function(data) {
                        $('#stripe_coupon, #paypal_coupon').val(coupon);
                        if (data.is_success) {
                            $('.hidden_coupon').val(coupon);
                            $('.hidden_coupon').attr(data);

                            $('.dicount_price').html(data.discount_price);

                            var html = '';
                            html +=
                                '<span class="text-sm font-weight-bold s-p-total pro_total_price" data-original="' +
                                data.final_price_data_value + '">' + data.final_price + '</span>'
                            $('.final_total_price').html(html);


                            // $('.coupon-tr').show().find('.coupon-price').text(data.discount_price);
                            // $('.final-price').text(data.final_price);
                            show_toastr('Success', data.message, 'success');
                        } else {
                            
                            // $('.coupon-tr').hide().find('.coupon-price').text('');
                            // $('.final-price').text(data.final_price);
                            show_toastr('Error', data.message, 'error');
                        }
                    }
                })
            } else {

                $.ajax({
                    url: '{{ route('apply.removecoupn') }}',
                    datType: 'json',
                    data: {
                        price: "price",
                        shipping_price: "shipping_price",
                        slug: {{ $store->id }},
                        coupon: "coupon"
                    },
                    success: function(data) {}
                });
                var hidd_cou = $('.hidd_val').val();

                if (hidd_cou == "") {
                    var total_pa_val = $(".total_pay_price").val();
                    $(".final_total_price").html(total_pa_val);
                    $(".dicount_price").html(0.00);

                }
                show_toastr('Error', '{{ __('Invalid Coupon Code.') }}', 'error');
            }
        }

    });
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
@endpush
