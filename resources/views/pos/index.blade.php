@php
    $logo=\App\Models\Utility::get_file('uploads/logo');
    $product_item=\App\Models\Utility::get_file('uploads/is_cover_image/');
    $company_favicon=Utility::getValByName('company_favicon');
    $SITE_RTL = Utility::getValByName('SITE_RTL');
    $setting = \App\Models\Utility::colorset();
    // $color = 'theme-3';
    // if (!empty($setting['color'])) {
    //     $color = $setting['color'];
    // }
    $color = !empty($setting['color']) ? $setting['color'] : 'theme-3';

    if(isset($setting['color_flag']) && $setting['color_flag'] == 'true')
    {
        $themeColor = 'custom-color';
    }
    else {
        $themeColor = $color;
    }
    $storesetting = Utility::StorageSettings();
@endphp
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        {{ !empty($companySettings['header_text']) ? $companySettings['header_text']->value : config('app.name', 'Storego Saas') }}
        - {{ __('POS') }}</title>

    <link rel="icon"
          href="{{ asset(Storage::url('uploads/logo/')) . '/' . (isset($companySettings['company_favicon']) && !empty($companySettings['company_favicon']) ? $companySettings['company_favicon']->value : 'favicon.png') }}"
          type="image" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">

    <!--bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-switch-button.min.css') }}">

    <!-- vendor css -->
    @if ($SITE_RTL == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
    @endif
    @if ($setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    @endif
    <link rel="stylesheet" href="{{ asset('custom/css/custom.css') }}" id="main-style-link">

    <style>

        .bg-color{
            @if($color=='theme-1')
                background :linear-gradient(141.55deg, rgba(81, 69, 157, 0) 3.46%, rgba(255, 58, 110, 0.6) 99.86%), #51459d;
            @elseif($color=='theme-2')
                background : linear-gradient(141.55deg, rgba(81, 69, 157, 0) 3.46%, #4ebbd3 99.86%), #1f3996;
            @elseif($color=='theme-3')
                background :  linear-gradient(141.55deg, #6fd943 3.46%, #6fd943 99.86%), #6fd943;
            @elseif($color=='theme-4')
                background :  linear-gradient(141.55deg, rgba(104, 94, 229, 0) 3.46%, #685ee5 99.86%), #584ed2;
            @endif
        }
        :root {
            --color-customColor: <?= $color ?>;    
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/custom-color.css') }}">

    @stack('css-page')
</head>

<body class="{{ $themeColor }}">
<div class="container-fluid px-2">
    <?php $lastsegment = request()->segment(count(request()->segments())) ?>
        <div class="row">
            <div class="col-12">
                <div class="mt-2 pos-top-bar bg-primary d-flex justify-content-between">
                    <span class="text-white">{{__('POS')}}</span>
                    <a  href="{{ route('dashboard') }}" class="text-white"><i class="ti ti-home" style="font-size: 20px;"></i> </a>
                </div>
            </div>
        </div>
        <div class="mt-2 row">
            <div class="col-lg-7">
                <div class="sop-card card">
                    <div class="card-header p-2">
                        <div class="search-bar-left">
                            <form>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ti ti-search"></i></span>
                                    </div>
                                    <input id="searchproduct" type="text" data-url="{{ route('search.products') }}" placeholder="{{ __('Search Product') }}" class="form-control pr-4 rounded-right">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-2">
                        <div class="right-content">
                            <div class="button-list b-bottom catgory-pad mb-4">
                                <div class="form-row m-0" id="categories-listing">
                                </div>
                            </div>
                            <div class="product-body-nop">
                                <div class="form-row row" id="product-listing">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div class="col-lg-5 ps-lg-0">
                    <div class="card m-0">
                        <div class="card-header p-2">
                            <div class="row">
                                <div class="col-md-6">
                                    {{ Form::select('customer_id',$customers,'', array('class' => 'form-control select customer_select','id'=>'customer','required'=>'required')) }}
                                    {{ Form::hidden('vc_name_hidden', '',['id' => 'vc_name_hidden']) }}
                                    <input type="hidden" id="store_id" value="{{ \Auth::user()->current_store }}">
                                </div>
                            </div>
                        </div>
                        <div class="card-body carttable cart-product-list carttable-scroll" id="carthtml">
                            @php $total = 0 @endphp
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th class="text-left">{{__('Name')}}</th>
                                        <th class="text-center">{{__('QTY')}}</th>
                                        <th>{{__('Tax')}}</th>
                                        <th class="text-center">{{__('Price')}}</th>
                                        <th class="text-center">{{__('Sub Total')}}</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbody">
                                    @if(session($lastsegment) && !empty(session($lastsegment)) && count(session($lastsegment)) > 0)
                                        @foreach(session($lastsegment) as $id => $details)
                                            @php
                                          
                                                $product = \App\Models\Product::find($details['id']);
                                                $image_url = !empty($product->is_cover) ? $product->is_cover : 'default.jpg';
                                                if($details['variant_id'] <= 0){
                                                    $total = $total + (float) $details['subtotal'];
                                                }else{
                                                    $total = $total + (float) $details['variant_subtotal'];
                                                }
                                            @endphp
                                            @if(\Auth::user()->current_store == $product->store_id)
                                                @if($details['variant_id'] <= 0)
                                                    <tr data-product-id="{{$id}}" id="product-id-{{$details['id']}}">
                                                @else
                                                    <tr data-product-id="{{$id}}" id="product-variant-id-{{$details['variant_id']}}">
                                                @endif
                                                    <td class="cart-images">
                                                        {{-- <img alt="Image placeholder" src="{{ asset(Storage::url('uploads/is_cover_image/'.$image_url)) }}" class="card-image avatar rounded-circle-sale shadow hover-shadow-lg"> --}}
                                                        <img alt="Image placeholder" src="{{ $product_item.$image_url }}" class="card-image avatar rounded-circle-sale shadow hover-shadow-lg">
                                                    </td>
                                                    @if($details['variant_id'] <= 0)
                                                        <td class="name">{{ $details['product_name'] }}</td>
                                                        <td>
                                                            <span class="quantity buttons_added">
                                                                <input type="button" value="-" class="minus">
                                                                <input type="number" step="1" min="1" max="" name="quantity"
                                                                    title="{{ __('Quantity') }}" class="input-number"
                                                                    data-url="{{ url('update-cart/') }}" data-id="{{ $id }}"
                                                                    size="4" value="{{ $details['quantity'] }}">
                                                                <input type="button" value="+" class="plus">
                                                            </span>
                                                        </td>
                                                    @else
                                                        <td class="name">{{ $details['product_name'] . '-' . $details['variant_name'] }}</td>
                                                        <td>
                                                            <span class="quantity buttons_added">
                                                                <input type="button" value="-" class="minus">
                                                                <input type="number" step="1" min="1" max="" name="quantity"
                                                                    title="{{ __('Quantity') }}" class="input-number"
                                                                    data-url="{{ url('update-cart/') }}" data-id="{{ $id }}"
                                                                    size="4" value="{{ $details['quantity'] }}">
                                                                <input type="button" value="+" class="plus">
                                                            </span>
                                                        </td>
                                                    @endif
                                                
                                                    <td>
                                                        @if(!empty($product->product_tax))
                                                            @php
                                                                $taxes=\Utility::tax($product->product_tax);
                                                            @endphp
                                                            @foreach($taxes as $tax)
                                                                <span class="badge bg-primary">{{$tax->name .' ('.$tax->rate .'%)'}}</span> <br>
                                                            @endforeach
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                
                                                    @if($details['variant_id'] <= 0)
                                                        {{--  <td class="price text-right">{{  \App\Models\Utility::priceFormat($details['price']) }}</td>  --}}
                                                        <td class="price text-right">{{ \App\Models\Utility::priceFormat($details['price']) }}</td>
                                                        
                                                        <td class="col-sm-3 mt-2">
                                                            {{--  <span class="subtotal">{{  \App\Models\Utility::priceFormat($details['subtotal']) }}</span>  --}}
                                                            <span class="subtotal">{{ \App\Models\Utility::priceFormat($details['subtotal']) }}</span>
                                                        </td>
                                                    @else
                                                        <td class="price text-right">{{ \App\Models\Utility::priceFormat($details['variant_price']) }}</td>
                                                        <td class="col-sm-3 mt-2">
                                                            <span class="subtotal">{{ \App\Models\Utility::priceFormat($details['variant_subtotal']) }}</span>
                                                        </td>
                                                    @endif
                                                    <td class="col-sm-2 mt-2">
                                                        <a href="#" class="bs-pass-para btn btn-sm btn-icon bg-light-secondary" data-confirm="{{ __('Are You Sure?') }}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"
                                                        data-confirm-yes="delete-form-{{ $id }}" title="{{ __('Delete') }}" data-id="{{ $id }}">
                                                            <i class="ti ti-trash" title="{{ __('Delete') }}"></i>
                                                        </a>
                                                        {!! Form::open(['method' => 'delete', 'url' => ['remove-from-cart'],'id' => 'delete-form-'.$id]) !!}
                                                        <input type="hidden" name="session_key" value="{{ $lastsegment }}">
                                                        <input type="hidden" name="id" value="{{ $id }}">
                                                        {!! Form::close() !!}
                                                    </td>
                                                </tr>
                                            @endif
                                            
                                        @endforeach
                                    @else
                                        <tr class="text-center no-found">
                                            <td colspan="7">{{__('No Data Found.!')}}</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="total-section mt-3">
                                <div class="row align-items-center">
                                    <div class="col-md-6 col-12">
                                        <div class="left-inner ">
                                        <div class="d-flex text-end justify-content-end align-items-center">
                                            <span class="input-group-text bg-transparent">{{$store->currency}}</span>
                                            {{ Form::number('discount',null, array('class' => ' form-control discount','required'=>'required','placeholder'=>__('Discount'))) }}
                                            {{ Form::hidden('discount_hidden', '',['id' => 'discount_hidden']) }}
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="right-inner mt-3">
                                            <div class="d-flex text-end justify-content-md-end  justify-content-flex-start">
                                                <h6 class="mb-0 text-dark" style=" color: black !important; ">{{__('Sub Total')}} :</h6>
                                                <h6 class="mb-0 text-dark subtotal_price" id="displaytotal" style=" color: black !important; ">{{  \App\Models\Utility::priceFormat($total) }}</h6>
                                            </div>
                                            
                                        <div class="d-flex align-items-center justify-content-md-end  justify-content-flex-start">
                                            <h6 class="" style=" color: black !important; ">{{__('Total')}} :</h6>
                                            <h6 class="totalamount" style=" color: black !important; ">{{ \App\Models\Utility::priceFormat($total) }}</h6>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between pt-3" id="btn-pur">
                                    @can('Create Pos')
                                        <button type="button" class="btn btn-primary rounded"  data-ajax-popup="true" data-size="xl"
                                                data-align="centered" data-url="{{route('pos.create')}}" data-title="{{__('POS Invoice')}}"
                                                @if(session($lastsegment) && !empty(session($lastsegment)) && count(session($lastsegment)) > 0) @else disabled="disabled" @endif>
                                            {{ __('PAY') }}
                                        </button>
                                    @endcan
                                    <div class="tab-content btn-empty text-end">
                                        <a href="#" class="btn btn-danger bs-pass-para rounded m-0"  data-toggle="tooltip" data-original-title="{{ __('Empty Cart') }}"
                                           data-confirm="{{ __('Are You Sure?') }}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"
                                           data-confirm-yes="delete-form-emptycart">{{ __('Empty Cart') }}
                                        </a>
                                        {!! Form::open(['method' => 'post', 'url' => ['empty-cart'],'id' => 'delete-form-emptycart']) !!}
                                        <input type="hidden" name="session_key" value="{{ $lastsegment }}" id="empty_cart">
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>


<div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
<div class="position-fixed top-0 end-0 p-3" style="z-index: 99999">
    <div id="liveToast" class="toast text-white  fade" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body"> </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Required Js -->
<script src="{{ asset('custom/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/dash.js') }}"></script>
<script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>

<script src="{{ asset('custom/libs/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
<script src="{{ asset('custom/libs/select2/dist/js/select2.min.js')}}"></script>
<script src="{{ asset('custom/libs/moment/min/moment.min.js')}}"></script>
<script src="{{ asset('custom/libs/@fancyapps/fancybox/dist/jquery.fancybox.min.js')}}"></script>
<script src="{{ asset('custom/libs/fullcalendar/dist/fullcalendar.min.js')}}"></script>
<script src="{{ asset('custom/libs/flatpickr/dist/flatpickr.min.js')}}"></script>
<script src="{{ asset('custom/libs/quill/dist/quill.min.js')}}"></script>
<script src="{{ asset('custom/libs/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
<script src="{{ asset('custom/libs/autosize/dist/autosize.min.js')}}"></script>
<script src="{{ asset('custom/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('custom/js/repeater.js') }}"></script>
<script src="{{ asset('custom/js/letter.avatar.js')}}"></script>
<script src="{{asset('assets/js/plugins/choices.min.js')}}"></script>
<script src="{{ asset('assets/js/plugins/simple-datatables.js')}}"></script>
<script src="{{asset('assets/js/plugins/bootstrap-switch-button.min.js')}}"></script>
<script src="{{ asset('custom/libs/dropzone/dist/min/dropzone.min.js')}}"></script>
<script src="{{ asset('custom/libs/progressbar.js/dist/progressbar.min.js')}}"></script>
<script src="{{ asset('custom/libs/summernote/summernote-bs4.js') }}"></script>
<script type="text/javascript" src="{{ asset('custom/js/custom.js') }}"></script>
<!-- Apex Chart -->
<script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>

@if($message = Session::get('success'))
    <script>
        show_toastr('success', '{!! $message !!}');
    </script>
@endif
@if($message = Session::get('error'))
    <script>
        show_toastr('error', '{!! $message !!}');
    </script>
@endif
@stack('script-page')

<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $( document ).ready(function() {

        $( "#vc_name_hidden" ).val($('.customer_select').val());
        $( "#discount_hidden").val($('.discount').val());

        $(function () {
            getProductCategories();

        });
    
        if ($('#searchproduct').length > 0) {
            var url = $('#searchproduct').data('url');
            var store_id = $( "#store_id" ).val();
            searchProducts(url,'','0',store_id);
        }
        $( '.customer_select' ).change(function() {
            $( "#vc_name_hidden" ).val($(this).val());
        });
    
        $(document).on('click', '#clearinput', function (e) {
            var IDs = [];
            $(this).closest('div').find("input").each(function () {
                IDs.push('#' + this.id);
            });
            $(IDs.toString()).val('');
        });


        $(document).on('keyup', 'input#searchproduct', function () {
            var url = $(this).data('url');
            var value = this.value;
            var cat = $('.cat-active').children().data('cat-id');
            var store_id = $( "#store_id" ).val();
            searchProducts(url, value,cat,store_id);
        });


        function searchProducts(url, value,cat_id,store_id = '0') {
            var session_key = $('#empty_cart').val();
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    'search': value,
                    'cat_id': cat_id,
                    'store_id' : store_id,
                    'session_key': session_key
                },
                success: function (data) {
                    $('#product-listing').html(data);
                }
            });
        }

        function getProductCategories() {
            $.ajax({
                type: 'GET',
                url: '{{ route('product.categories') }}',
                success: function (data) {
                  
                    $('#categories-listing').html(data);
                }
            });
        }

        $(document).on('click', '.toacart', function () {
           
            var sum = 0
            $.ajax({
                url: $(this).data('url'),

                success: function (data) {
                    
                    if (data.code == '200') {

                        $('#displaytotal').text(addCommas(data.product.subtotal));
                        $('.totalamount').text(addCommas(data.product.subtotal));

                        if ('carttotal' in data) {
                            $.each(data.carttotal, function (key, value) {
                                if(value.variant_id == 0){
                                    $('#product-id-' + value.id + ' .subtotal').text(addCommas(value.subtotal));
                                    sum += value.subtotal;
                                }else{
                                    $('#product-variant-id-' + value.variant_id + ' .subtotal').text(addCommas(value.variant_subtotal));
                                    sum += value.variant_subtotal;
                                }
                            });
                            $('#displaytotal').text(addCommas(sum));

                            $('.totalamount').text(addCommas(sum));

                       $('.discount').val('');
                        }
                        
                        $('#tbody').append(data.carthtml);
                        $('.no-found').addClass('d-none');
                        $('.carttable #product-id-' + data.product.id + ' input[name="quantity"]').val(data.product.quantity);
                        $('#btn-pur button').removeAttr('disabled');
                        $('.btn-empty button').addClass('btn-clear-cart');
                       
                        }
                },
                error: function (data) {
                    data = data.responseJSON;
                    show_toastr('{{ __("Error") }}', data.error, 'error');
                }
            });
        });

        $(document).on('change keyup', '#carthtml input[name="quantity"]', function (e) {
            e.preventDefault();
            var ele = $(this);
            var sum = 0;
            var quantity = ele.closest('span').find('input[name="quantity"]').val();
            var discount = $('.discount').val();
            var session_key = $('#empty_cart').val();

            if(quantity != null && quantity != 0){
                $.ajax({
                    url: ele.data('url'),
                    method: "patch",
                    data: {
                        id: ele.attr("data-id"),
                        quantity: quantity,
                        discount:discount,
                        session_key: session_key
                    },
                    success: function (data) {
                        if (data.code == '200') {

                            if (quantity == 0) {
                                ele.closest(".row").hide(250, function () {
                                    ele.closest(".row").remove();
                                });
                                if (ele.closest(".row").is(":last-child")) {
                                    $('#btn-pur button').attr('disabled', 'disabled');
                                    $('.btn-empty button').removeClass('btn-clear-cart');
                                }
                            }

                            $.each(data.product, function (key, value) {
                                // sum += value.subtotal;
                                // $('#product-id-' + value.id + ' .subtotal').text(addCommas(value.subtotal));
                                if(value.variant_id == 0){
                                    $('#product-id-' + value.id + ' .subtotal').text(addCommas(value.subtotal));
                                    sum += value.subtotal;
                                }else{
                                    $('#product-variant-id-' + value.variant_id + ' .subtotal').text(addCommas(value.variant_subtotal));
                                    sum += value.variant_subtotal;
                                }
                            });

                            $('#displaytotal').text(addCommas(sum));
                            if(discount <= sum){
                                $('.totalamount').text(data.discount);
                            }
                            else{
                                $('.totalamount').text(addCommas(0));
                            }
                        }
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        show_toastr('{{ __("Error") }}', data.error, 'error');
                    }
                });
            }
        });

        $(document).on('click', '.remove-from-cart', function (e) {
            e.preventDefault();

            var ele = $(this);
            var sum = 0;

            if (confirm('{{ __("Are you sure?") }}')) {
                ele.closest(".row").hide(250, function () {
                    ele.closest(".row").parent().parent().remove();
                });
                if (ele.closest(".row").is(":last-child")) {
                    $('#btn-pur button').attr('disabled', 'disabled');
                    $('.btn-empty button').removeClass('btn-clear-cart');
                }
                $.ajax({
                    url: ele.data('url'),
                    method: "DELETE",
                    data: {
                        id: ele.attr("data-id"),
                        
                    },
                    success: function (data) {
                        if (data.code == '200') {

                            $.each(data.product, function (key, value) {
                                sum += value.subtotal;
                                $('#product-id-' + value.id + ' .subtotal').text(addCommas(value.subtotal));
                            });

                            $('#displaytotal').text(addCommas(sum));

                            show_toastr('success', data.success, 'success')
                        }
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        show_toastr('{{ __("Error") }}', data.error, 'error');
                    }
                });
            }
        });

        $(document).on('click', '.btn-clear-cart', function (e) {
            e.preventDefault();

            if (confirm('{{ __("Remove all items from cart?") }}')) {

                $.ajax({
                    url: $(this).data('url'),
                    data: {
                        session_key: session_key
                    },
                    success: function (data) {
                        location.reload();
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        show_toastr('{{ __("Error") }}', data.error, 'error');
                    }
                });
            }
        });

        $(document).on('click', '.btn-done-payment', function (e) {
            e.preventDefault();
            var ele = $(this);

            $.ajax({
                url: ele.data('url'),

                method: 'GET',
                data: {
                    vc_name: $('#vc_name_hidden').val(),
                    warehouse_name: $('#warehouse_name_hidden').val(),
                    discount : $('#discount_hidden').val(),
                },
                beforeSend: function () {
                    ele.remove();
                },
                success: function (data) {
                   
                    if (data.code == 200) {
                        show_toastr('success', data.success, 'success')
                    }
                   
                },
                error: function (data) {
                    data = data.responseJSON;
                    show_toastr('{{ __("Error") }}', data.error, 'error');
                }

            });

        });

        $(document).on('click', '.category-select', function (e) {
            var cat = $(this).data('cat-id');
            var white = 'text-white';
            var dark = 'text-dark';
            $('.category-select').find('.tab-btns').removeClass('btn-primary')
            $(this).find('.tab-btns').addClass('btn-primary')
            $('.category-select').parent().removeClass('cat-active');
            $('.category-select').find('.card-title').removeClass('text-white').addClass('text-dark');
            $('.category-select').find('.card-title').parent().removeClass('text-white').addClass('text-dark');
            $(this).find('.card-title').removeClass('text-dark').addClass('text-white');
            $(this).find('.card-title').parent().removeClass('text-dark').addClass('text-white');
            $(this).parent().addClass('cat-active');
            var url = '{{ route('search.products') }}'
            var store_id=$('#store_id').val();
            searchProducts(url,'',cat,store_id);
        });

        $(document).on('change keyup', '.discount', function () {

            var discount = $('.discount').val();
            var total = $('#displaytotal').text();
            var maintotal = parseFloat(total.replace("{{isset($storesetting['currency_symbol'])?$storesetting['currency_symbol']:'$'}}","").replace(",",""))
            if(discount <= maintotal){
                $( "#discount_hidden" ).val(discount);
            }else{
                $( "#discount_hidden" ).val(maintotal);
            }
            $.ajax({
                url: "{{route('cartdiscount')}}",
                method: 'POST',
                data: {discount: discount,},
                success: function (data)
                {
                    if(discount <= maintotal){
                        $('.totalamount').text(data.total);
                    }else{
                        $('.totalamount').text(addCommas(0));
                    }
                },
                error: function (data) {
                    data = data.responseJSON;
                    show_toastr('{{ __("Error") }}', data.error, 'error');
                }
            });

            var price = {{$total}}
            var total_amount = price-discount;
            $('.totalamount').text(total_amount);
        });

    });


// Product Variant script

    $(document).on('change', '.variant-selection', function() {
            var variants = [];
            $(".variant-selection").each(function(index, element) {
                if (element.value != '' && element.value != undefined) {
                    var el_val = element.value;
                    variants.push(el_val);
                }
            });
            if (variants.length > 0) {

                $.ajax({
                    url: '{{ route('get.products.variant.quantity') }}',
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        variants: variants.join(' : '),
                        product_id: $('#product_id').val()
                    },

                    success: function(data) {
                        if (data.variant_id == 0) {
                            $('.variant_stock1').addClass('d-none');
                            $('.variation_price1').html('Please Select Variants');
                            // $('#variant_qty').val('0');
                        } else {
                            var qty = 'Price : '  + data.price;
                            var amount = 'QTY : ' + data.quantity;
                            $('.variation_price1').html(qty);
                            $('#variant_id').val(data.variant_id);
                            // $('#variant_qty').val(data.quantity);
                            $('.variant_qty').html(amount);
                            $('.variant_stock1').removeClass('d-none');
                            if (data.quantity != 0) {
                                $('.variant_stock1').html('In Stock');
                                $(".variant_stock1").css({
                                    "backgroundColor": "#C2FFA5",
                                    "color": "#58A336"
                                });
                            } else {
                                $(".variant_qty").css({
                                    // "backgroundColor": "#FFA5A5",
                                    "color": "rgb(253 58 110)"
                                });
                                $('.variant_qty').html('Out Of Stock');
                            }
                        }
                    }
                });
            }
        });


        $(document).on('click', '.toacartvariant', function () {
           
           var sum = 0;
           var id = $(this).attr('data-id');
           var session_key = "{{ $lastsegment }}";
           var variants = [];
            $(".variant-selection").each(function(index, element) {
                variants.push(element.value);
            });

            if (jQuery.inArray('0', variants) != -1) {
                show_toastr('Error', "{{ __('Please select all option.') }}", 'error');
                return false;
            }

            var variation_ids = $('#variant_id').val();

           $.ajax({
                url: '{{ route('addToCartVariant', ['__product_id', 'session_key', 'variation_id']) }}'
                    .replace('__product_id', id).replace('session_key', session_key).replace('variation_id', variation_ids ?? 0),//$(this).data('url'),
                data: {
                    "_token": "{{ csrf_token() }}",
                    variants: variants.join(' : '),
                },
               success: function (data) {
                   if (data.code == '200') {

                       $('#displaytotal').text(addCommas(data.product.variant_subtotal));
                       $('.totalamount').text(addCommas(data.product.variant_subtotal));

                       if ('carttotal' in data) {
                           $.each(data.carttotal, function (key, value) {
                                if(value.variant_id == 0){
                                    $('#product-id-' + value.id + ' .subtotal').text(addCommas(value.subtotal));
                                    sum += value.subtotal;
                                }else{
                                    $('#product-variant-id-' + value.variant_id + ' .subtotal').text(addCommas(value.variant_subtotal));
                                    sum += value.variant_subtotal;
                                }
                           });
                           $('#displaytotal').text(addCommas(sum));

                           $('.totalamount').text(addCommas(sum));

                      $('.discount').val('');
                       }
                       
                       $('#tbody').append(data.carthtml);
                       $('.no-found').addClass('d-none');
                       $('.carttable #product-variant-id-' + data.product.variant_id + ' input[name="quantity"]').val(data.product.quantity);
                       $('#btn-pur button').removeAttr('disabled');
                       $('.btn-empty button').addClass('btn-clear-cart');
                      
                       }
               },
               error: function (data) {
                   data = data.responseJSON;
                   show_toastr('{{ __("Error") }}', data.error, 'error');
               }
           });
       });

        $(document).on('click', '.add_to_cart_variant', function () {
            $('#commonModal').modal('hide');
        });
</script>
<script>
    var site_currency_symbol_position = '{{ \App\Models\Utility::getValByName('currency_symbol_position') }}';
    var site_currency_symbol = '{{ \App\Models\Store::where('id',\Auth::user()->current_store)->first()->currency }}';
</script>

</body>
</html>
