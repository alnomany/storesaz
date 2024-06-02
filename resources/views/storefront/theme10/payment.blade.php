@extends('storefront.layout.theme10')
@section('page-title')
    {{ __('Product Details') }}
@endsection
@section('content')
    <div class="wrapper">
        @php
        if (!empty(session()->get('lang'))) {
            $currantLang = session()->get('lang');
        } else {
            $currantLang = $store->lang;
        }
        $languages = \App\Models\Utility::languages();
        $storethemesetting = \App\Models\Utility::demoStoreThemeSetting($store->id, $store->theme_dir);
    @endphp
    @php
        $coupon_price = !empty($coupon_price) ? $coupon_price : 0;
        $shipping_price = !empty($shipping_price) ? $shipping_price : 0;
        $productImg = \App\Models\Utility::get_file('uploads/is_cover_image/');
    @endphp
    <input type="hidden" id="return_url">
    <input type="hidden" id="return_order_id">
        <section class="padding-top padding-bottom checkout-section">
            <div class="container">
                <div class="row align-items-center cart-head">
                    <div class="col-lg-3 col-md-12 col-12">
                        <div class="cart-title">
                            <h2>{{ __('Payment') }}</h2>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-12 col-12 justify-content-end">
                        <div class="cart-header-btn">
                            <a href="{{ route('store.cart', $store->slug) }}">1 - {{ __('My Cart') }}</a>
                            <a href="{{ route('user-address.useraddress', $store->slug) }}">2 -{{ __('Customer') }}</a>
                            <a href="{{ route('store-payment.payment', $store->slug) }}" class="active-btn">3 - {{ __('Payment') }}</a>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <input type="hidden" class="form-control hidden_coupon" data_id="{{ $coupon_id }}">
                    <div class="col-lg-8 col-12">
                        @if($store['enable_cod'] == 'on')
                        <div class="payment-method">
                            <div class="payment-title d-flex align-items-center justify-content-between">
                                <h4>{{__('COD')}}</h4>
                                <div class="payment-image d-flex align-items-center">
                                    <i class="fas fa-truck text-primary fa-3x"></i>
                                </div>
                            </div>
                            <p>{{__('Cash on delivery is a type of transaction in which payment for a good is made at the time of delivery.')}}</p>
                            <form class="w3-container w3-display-middle w3-card-4" method="POST" action="{{ route('user.cod',$store->slug) }}">
                                @csrf
                                <input type="hidden" name="product_id">
                                <div class="form-group text-right">
                                    <button type="button" class="btn btn-primary btn-sm " id="cash_on_delivery">{{__('Pay Now')}}</button>
                                </div>
                            </form>
                        </div>
                        @endif
                        @if(isset($store_payments['is_stripe_enabled']) && $store_payments['is_stripe_enabled'] == 'on')
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{ __('Stripe') }}</h4>
                                    <div class="payment-image d-flex align-items-center">
                                        <img src="{{ asset('assets/theme1/images/visa.png') }}" alt="">
                                        <img src="{{ asset('assets/theme1/images/mastercard.png') }}" alt="">
                                        <img src="{{ asset('assets/theme1/images/skrill.png') }}" alt="">
                                    </div>
                                </div>
                                <p>{{ __('Safe money transfer using your bank account. We support Mastercard, Visa and
                                    Skrill.') }}</p>
                                <form action="{{ route('stripe.post',$store->slug) }}" method="post" class="payment-method-form" id="payment-form">
                                    @csrf
                                    <input type="hidden" name="product_id">
                                    <div class="form-group">
                                        <label for="">{{__('Name on card')}}</label>
                                        <input type="text" name="name" placeholder="Enter Your Name">
                                    </div>
                                    <div class="form-group">
                                        <div id="card-element"></div>
                                        <div id="card-errors" role="alert"></div>
                                    </div>
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn">{{__('Pay Now')}}</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        @if($store['enable_telegram'] == 'on')
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{__('Telegram')}}</h4>
                                    <div class="payment-image d-flex align-items-center">
                                        <img src="{{asset('assets/img/telegram.svg')}}" alt="">
                                    </div>
                                </div>
                                <p>{{ __('Click to chat. The click to chat feature lets customers click an URL in order to directly start a chat with another person or business via Telegram. ... QR code. As you know, having to add a phone number to
                                    your contacts in order to start up a Telegram message can take a little while')}}.....</p>
                                <form action="{{ route('user.telegram',$store->slug) }}" method="post" class="payment-method-form" id="payment-form">
                                    @csrf
                                    <input type="hidden" name="product_id">
                                    <div class="form-group text-right">
                                        <button type="button" class="btn" id="owner-telegram">{{__('Pay Now')}}</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        <!-- Add money using Bank Transfer -->
                        @if($store['enable_bank'] == 'on')
                        <div class="payment-method">
                            <div class="payment-title d-flex align-items-center justify-content-between">
                                <h4>{{ 'Bank Transfer' }}</h4>
                                <div class="payment-image d-flex align-items-center">
                                    <img src="{{ asset('assets/img/bank.png') }}" alt="">
                                </div>
                            </div>
                            <ul class="form-group">
                
                                <li> {!! $store->bank_number !!}</li>
                                
                            </ul>
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <form style="margin-top: 0" action="{{ route('user.bank_transfer', $store->slug) }}" method="POST" id="bank_transfer_form" class="payment-method-form" enctype="multipart/form-data">
                                        @csrf
                                        <div class="upload-btn-wrapper">
                                            <label for="bank_transfer_invoice" class="file-upload btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.67952 7.2448C6.69833 7.59772 6.42748 7.89908 6.07456 7.91789C5.59289 7.94357 5.21139 7.97498 4.91327 8.00642C4.51291 8.04864 4.26965 8.29456 4.22921 8.64831C4.17115 9.15619 4.12069 9.92477 4.12069 11.0589C4.12069 12.193 4.17115 12.9616 4.22921 13.4695C4.26972 13.8238 4.51237 14.0691 4.91213 14.1112C5.61223 14.1851 6.76953 14.2586 8.60022 14.2586C10.4309 14.2586 11.5882 14.1851 12.2883 14.1112C12.6881 14.0691 12.9307 13.8238 12.9712 13.4695C13.0293 12.9616 13.0798 12.193 13.0798 11.0589C13.0798 9.92477 13.0293 9.15619 12.9712 8.64831C12.9308 8.29456 12.6875 8.04864 12.2872 8.00642C11.9891 7.97498 11.6076 7.94357 11.1259 7.91789C10.773 7.89908 10.5021 7.59772 10.5209 7.2448C10.5397 6.89187 10.8411 6.62103 11.194 6.63984C11.695 6.66655 12.0987 6.69958 12.4214 6.73361C13.3713 6.8338 14.1291 7.50771 14.2428 8.50295C14.3077 9.07016 14.3596 9.88879 14.3596 11.0589C14.3596 12.229 14.3077 13.0476 14.2428 13.6148C14.1291 14.6095 13.3732 15.2837 12.4227 15.384C11.6667 15.4638 10.4629 15.5384 8.60022 15.5384C6.73752 15.5384 5.5337 15.4638 4.77779 15.384C3.82728 15.2837 3.07133 14.6095 2.95763 13.6148C2.89279 13.0476 2.84082 12.229 2.84082 11.0589C2.84082 9.88879 2.89279 9.07016 2.95763 8.50295C3.0714 7.50771 3.82911 6.8338 4.77903 6.73361C5.10175 6.69958 5.50546 6.66655 6.00642 6.63984C6.35935 6.62103 6.6607 6.89187 6.67952 7.2448Z" fill="white"></path>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.81509 4.79241C6.56518 5.04232 6.16 5.04232 5.91009 4.79241C5.66018 4.5425 5.66018 4.13732 5.91009 3.88741L8.14986 1.64764C8.39977 1.39773 8.80495 1.39773 9.05486 1.64764L11.2946 3.88741C11.5445 4.13732 11.5445 4.5425 11.2946 4.79241C11.0447 5.04232 10.6395 5.04232 10.3896 4.79241L9.24229 3.64508V9.77934C9.24229 10.1328 8.95578 10.4193 8.60236 10.4193C8.24893 10.4193 7.96242 10.1328 7.96242 9.77934L7.96242 3.64508L6.81509 4.79241Z" fill="white"></path>
                                                </svg>
                                                {{ __('Upload invoice reciept') }}
                                            </label>
                                            <input type="file" name="bank_transfer_invoice" id="bank_transfer_invoice" class="file-input" >
                                            <input type="hidden" name="product_id">
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn" id="bank_transfer">{{ __('Pay Now') }}</button>
                                    </div>
                                </div>
                            </div>
    
                            {{--  <div class="col-md-6">  --}}
                                
                            {{--  </div>  --}}
                        </div>
                        @endif
                        <!-- Add money using whatsapp -->
                        @if($store['enable_whatsapp'] == 'on')
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{__('WhatsApp')}}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{asset('assets/img/whatsapp.png')}}" alt="">
                                    </div>
                                </div>
                                <p>{{ __('Click to chat. The click to chat feature lets customers click an URL in order to directly start a chat with another person or business via WhatsApp. ... QR code. As you know, having to add a phone number to
                                    your contacts in order to start up a WhatsApp message can take a little while')}}.....</p>
                                <div class="form-group">
                                    <form method="POST" action="{{ route('user.whatsapp',$store->slug) }}" class="payment-method-form">
                                        @csrf
                                        <div class="form-group">
                                            <label>{{__('Phone Number')}}</label>
                                            <input name="wts_number" id="wts_number" type="text" placeholder="Enter Your Phone Number">
                                        </div>
                                       
                                    </form>
                                </div>
                                <div class="form-group text-right">
                                    <button type="submit" class="btn" id="owner-whatsapp">{{__('Pay Now')}}</button>
                                </div>
                            </div>
                        @endif
    
                        @if(isset($store_payments['is_paystack_enabled']) && $store_payments['is_paystack_enabled']=='on')
                            <script src="https://js.paystack.co/v1/inline.js"></script>
                            <script src="https://checkout.paystack.com/service-worker.js"></script>
                            {{-- PAYSTACK JAVASCRIPT FUNCTION --}}
                            <script>
                                function payWithPaystack() {
                                    var paystack_callback = "{{ url('/paystack') }}";
                                    var order_id = '{{$order_id = time()}}';
                                    var slug = '{{$store->slug}}';
                                    var handler = PaystackPop.setup({
                                        key: '{{ $store_payments['paystack_public_key']  }}',
                                        email: '{{$cust_details['email']}}',
                                        amount: $('.pro_total_price').data('value') * 100,
                                        currency: '{{$store['currency_code']}}',
                                        ref: 'pay_ref_id' + Math.floor((Math.random() * 1000000000) +
                                            1
                                        ), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                                        metadata: {
                                            custom_fields: [{
                                                display_name: "Mobile Number",
                                                variable_name: "mobile_number",
                                                value: "{{$cust_details['phone']}}"
                                            }]
                                        },
    
                                        callback: function (response) {
                                            console.log(response.reference, order_id);
                                            window.location.href = paystack_callback + '/' + slug + '/' + response.reference + '/' + {{$order_id}};
                                        },
                                        onClose: function () {
                                            alert('window closed');
                                        }
                                    });
                                    handler.openIframe();
                                }
    
                            </script>
                        {{-- /PAYSTACK JAVASCRIPT FUNCTION --}}
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{__('Paystack')}}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{asset('assets/img/paystack-logo.jpg')}}" alt="">
                                    </div>
                                </div>
                                <p>
                                {{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Paystack to finish complete your purchase')}}.
                                </p>
                                {{--  <form class="payment-method-form">  --}}
                                    <input type="hidden" name="product_id">
                                    <div class="form-group text-right">
                                        <button type="submit" onclick="payWithPaystack()" class="btn">{{__('Pay Now')}}</button>
                                    </div>
                                {{--  </form>  --}}
                            </div>
                        @endif
    
                        <!-- Add money using PayPal -->
                        @if(isset($store_payments['is_paypal_enabled']) && $store_payments['is_paypal_enabled'] == 'on')
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{('Paypal')}}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{asset('assets/img/paypal.png')}}" alt="">
                                    </div>
                                </div>
                                <p>{{ __('Pay your order using the most known and secure platform for online money transfers. You
                                    will be redirected to PayPal to finish complete your purchase.') }}</p>
                                <form method="POST" action="{{ route('pay.with.paypal',$store->slug) }}" class="payment-method-form">
                                    @csrf
                                    <input type="hidden" name="product_id">
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn">{{__('Pay Now')}}</button>
                                    </div>
                                </form>
                            </div>
                        @endif
    
                        @if(isset($store_payments['is_flutterwave_enabled']) && $store_payments['is_flutterwave_enabled']=='on')
                            <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
                                    {{-- Flutterwave JAVASCRIPT FUNCTION --}}
                                    <script>
    
                                        function payWithRave() {
                                            var API_publicKey = '{{ $store_payments['flutterwave_public_key']  }}';
                                            var nowTim = "{{ date('d-m-Y-h-i-a') }}";
                                            var order_id = '{{$order_id = time()}}';
                                            var flutter_callback = "{{ url('/flutterwave') }}";
                                            var x = getpaidSetup({
                                                PBFPubKey: API_publicKey,
                                                customer_email: '{{$cust_details['email']}}',
                                                amount: $('.product_total').val(),
                                                customer_phone: '{{$cust_details['phone']}}',
                                                currency: '{{$store['currency_code']}}',
                                                txref: nowTim + '__' + Math.floor((Math.random() * 1000000000)) + 'fluttpay_online-' +
                                                {{ date('Y-m-d') }},
                                                meta: [{
                                                    metaname: "payment_id",
                                                    metavalue: "id"
                                                }],
                                                onclose: function () {
                                                },
                                                callback: function (response) {
    
                                                    var txref = response.tx.txRef;
    
                                                    if (
                                                        response.tx.chargeResponseCode == "00" ||
                                                        response.tx.chargeResponseCode == "0"
                                                    ) {
                                                        window.location.href = flutter_callback + '/{{$store->slug}}/' + txref + '/' + {{$order_id}};
                                                    } else {
                                                        // redirect to a failure page.
                                                    }
                                                    x.close(); // use this to close the modal immediately after payment.
                                                }
                                            });
                                        }
                                    </script>
                                    {{-- /Flutterwave JAVASCRIPT FUNCTION --}}
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{__('Flutterwave')}}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{asset('assets/img/flutterwave_logo.png')}}" alt="">
                                    </div>
                                </div>
                                <p>{{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Flutterwave to finish complete your purchase')}}.</p>
                                
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn" onclick="payWithRave()">{{__('Pay Now')}}</button>
                                    </div>
                                
                            </div>
                        @endif
    
                        @if(isset($store_payments['is_razorpay_enabled']) && $store_payments['is_razorpay_enabled'] == 'on')
                            @php
                            $logo         =asset(Storage::url('uploads/logo/'));
                            $company_logo =\App\Models\Utility::getValByName('company_logo');
                            @endphp
    
                            <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                            {{-- Flutterwave JAVASCRIPT FUNCTION --}}
                            <script>
    
                                function payRazorPay() {
                                    var getAmount = $('.product_total').val();
                                    var order_id = '{{$order_id = time()}}';
                                    var product_id = '{{$order_id}}';
                                    var useremail = '{{$cust_details['email']}}';
                                    var razorPay_callback = '{{url('razorpay')}}';
                                    var totalAmount = getAmount * 100;
                                    var product_array = '{{$encode_product}}';
                                    var product = JSON.parse(product_array.replace(/&quot;/g, '"'));
    
    
                                    var coupon_id = $('.hidden_coupon').attr('data_id');
                                    var dicount_price = $('.dicount_price').html();
    
                                    var options = {
                                        "key": "{{ $store_payments['razorpay_public_key']  }}", // your Razorpay Key Id
                                        "amount": totalAmount,
                                        "name": product,
                                        "currency": '{{$store['currency_code']}}',
                                        "description": "Order Id : " + order_id,
                                        "image": "{{$logo.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo-dark.png')}}",
                                        "handler": function (response) {
                                            window.location.href = razorPay_callback + '/{{$store->slug}}/' + response.razorpay_payment_id + '/' + order_id;
                                        },
                                        "theme": {
                                            "color": "#528FF0"
                                        }
                                    };
    
                                    var rzp1 = new Razorpay(options);
                                    rzp1.open();
                                }
                            </script>
                            {{-- /Razerpay JAVASCRIPT FUNCTION --}}
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{__('Razorpay')}}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{asset('assets/img/razorpay.png')}}" alt="">
                                    </div>
                                </div>
                                <p> {{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Razorpay to finish complete your purchase')}}.</p>
                                    <div class="form-group text-right">
                                        <input type="hidden" name="product_id">
                                        <button type="submit" onclick="payRazorPay()" class="btn">{{__('Pay Now')}}</button>
                                    </div>
                            </div>
                        @endif
    
                        @if(isset($store_payments['is_paytm_enabled']) && $store_payments['is_paytm_enabled'] == 'on')
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{__('Paytm')}}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{asset('assets/img/Paytm.png')}}" alt="">
                                    </div>
                                </div>
                                <p>{{ __('Pay your order using the most known and secure platform for online money transfers. You
                                    will be redirected to Paytm to finish complete your purchase.') }}</p>
                                <form method="POST" action="{{ route('paytm.prepare.payments',$store->slug) }}" class="payment-method-form">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                    <input type="hidden" name="order_id" value="{{str_pad(!empty($order->id) ? $order->id + 1 : 0 + 1, 4, "100", STR_PAD_LEFT)}}">
                                    @php
                                        $skrill_data = [
                                            'transaction_id' => md5(date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id'),
                                            'user_id' => 'user_id',
                                            'amount' => 'amount',
                                            'currency' => 'currency',
                                        ];
                                        session()->put('skrill_data', $skrill_data);
                                    @endphp
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn">{{__('Pay Now')}}</button>
                                    </div>
                                </form>
                            </div>
                        @endif
    
                        @if(isset($store_payments['is_mercado_enabled']) && $store_payments['is_mercado_enabled'] == 'on')
                            <script>
                                function payMercado() {
    
                                    var product_array = '{{$encode_product}}';
                                    var product = JSON.parse(product_array.replace(/&quot;/g, '"'));
                                    var order_id = '{{$order_id = time()}}';
    
                                    var total_price = $('#Subtotal .pro_total_price').attr('data-value');
                                    var coupon_id = $('.hidden_coupon').attr('data_id');
                                    var dicount_price = $('.dicount_price').html();
    
                                    var data = {
                                        coupon_id: coupon_id,
                                        dicount_price: dicount_price,
                                        total_price: total_price,
                                        product: product,
                                        order_id: order_id,
                                    }
                                    $.ajax({
                                        url: '{{ route('mercadopago.prepare',$store->slug) }}',
                                        method: 'POST',
                                        data: data,
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        success: function (data) {
                                            if (data.status == 'success') {
                                                window.location.href = data.url;
                                            } else {
                                                show_toastr("Error", data.success, data["status"]);
                                            }
                                        }
                                    });
                                }
                            </script>
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{__('Mercado Pago')}}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{asset('assets/img/mercadopago.png')}}" alt="">
                                    </div>
                                </div>
                                <p>{{ __('Pay your order using the most known and secure platform for online money transfers. You
                                    will be redirected to Mercado Pago to finish complete your purchase.') }}</p>
                                <div class="form-group text-right">
                                    <button type="submit" onclick="payMercado()" class="btn">{{__('Pay Now')}}</button>
                                </div>
                            </div>
                        @endif
                        @if(isset($store_payments['is_mollie_enabled']) && $store_payments['is_mollie_enabled'] == 'on')
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{ __('Mollie') }}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{asset('assets/img/mollie.png')}}" alt="">
                                    </div>
                                </div>
                                <p>{{ __('Pay your order using the most known and secure platform for online money transfers. You
                                    will be redirected to Mollie to finish complete your purchase.') }}</p>
                                <form action="{{ route('mollie.prepare.payments',$store->slug) }}" method="POST" class="payment-method-form">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                    <input type="hidden" name="desc" value="{{time()}}">
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn" >{{__('Pay Now')}}</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        @if(isset($store_payments['is_skrill_enabled']) && $store_payments['is_skrill_enabled'] == 'on')
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{__('Skrill')}}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{asset('assets/img/skrill.png')}}" alt="">
                                    </div>
                                </div>
                                <p> {{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Skrill to finish complete your purchase')}}.</p>
                                <form method="POST" action="{{ route('skrill.prepare.payments',$store->slug) }}" class="payment-method-form">
                                    @csrf
                                    <input type="hidden" name="transaction_id" value="{{ date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id' }}">
                                    <input type="hidden" name="desc" value="{{time()}}">
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn">{{__('Pay Now')}}</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        @if(isset($store_payments['is_coingate_enabled']) && $store_payments['is_coingate_enabled'] == 'on')
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{__('CoinGate')}}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{ asset('assets/theme1/images/coingate.png') }}" alt="">
                                    </div>
                                </div>
                                <p>{{ __('Pay your order using the most known and secure platform for online money transfers. You
                                    will be redirected to CoinGate to finish complete your purchase.') }}</p>
                                <form method="POST" action="{{ route('coingate.prepare',$store->slug) }}" class="payment-method-form">
                                    @csrf
                                    <input type="hidden" name="transaction_id" value="{{ date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id' }}">
                                    <input type="hidden" name="desc" value="{{time()}}">
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn">{{__('Pay Now')}}</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        @if(isset($store_payments['is_paymentwall_enabled']) && $store_payments['is_paymentwall_enabled'] == 'on')
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{__('PaymentWall')}}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{asset('assets/img/Paymentwall.png')}}" alt="">
                                    </div>
                                </div>
                                <p>{{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to PaymentWall to finish complete your purchase')}}.</p>
                                <form method="POST" action="{{ route('paymentwall.session.store',$store->slug) }}" class="payment-method-form">
                                    @csrf
                                    <input type="hidden" name="transaction_id" value="{{ date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id' }}">
                                    <input type="hidden" name="desc" value="{{time()}}">
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn">{{__('Pay Now')}}</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        @if(isset($store_payments['is_toyyibpay_enabled']) && $store_payments['is_toyyibpay_enabled'] == 'on')
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{__('Toyyibpay')}}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{asset('assets/img/toyyibpay.png')}}" alt="">
                                    </div>
                                </div>
                                <p>{{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to toyyibpay to finish complete your purchase')}}.</p>
                                <form method="POST" action="{{ route('toyyibpay.prepare.payments',$store->slug) }}" class="payment-method-form">
                                    @csrf
                                    <input type="hidden" name="transaction_id" value="{{ date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id' }}">
                                    <input type="hidden" name="desc" value="{{time()}}">
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn">{{__('Pay Now')}}</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        @if(isset($store_payments['is_payfast_enabled']) && $store_payments['is_payfast_enabled'] == 'on')
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{__('Payfast')}}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{asset('assets/img/payfast.png')}}" alt="">
                                    </div>
                                </div>
                                <p>{{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to payfast to finish complete your purchase')}}.</p>
                                @php
                                    $pfHost = $store_payments['payfast_mode'] == 'sandbox' ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
                                @endphp
                                <form method="POST" action='{{"https://" . $pfHost . "/eng/process"}}' class="payment-method-form" id="payfast-form">
                                    @csrf
                                    <div id="get-payfast-inputs"></div>
                                    <div class="form-group text-right">
                                        <input type="hidden" name="product_id" id="product_id">
                                        <button type="submit" class="btn" id="pay_with_payfast">{{__('Pay Now')}}</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        @if(isset($store_payments['is_iyzipay_enabled']) && $store_payments['is_iyzipay_enabled'] == 'on')
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{__('Iyzipay')}}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{asset('assets/img/iyzipay.svg')}}" alt="">
                                    </div>
                                </div>
                                <p>{{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to iyzipay to finish complete your purchase')}}.</p>
                                <form method="POST" action="{{ route('iyzipay.prepare.payment',$store->slug) }}" class="payment-method-form" id="iyzipay-form">
                                    @csrf
                                    <div class="form-group text-right">
                                        <input type="hidden" name="product_id" id="product_id">
                                        <button type="submit" class="btn" id="pay_with_iyzipay">{{__('Pay Now')}}</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        @if(isset($store_payments['is_sspay_enabled']) && $store_payments['is_sspay_enabled'] == 'on')
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{__('Sspay')}}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{asset('assets/img/sspay.png')}}" alt="">
                                    </div>
                                </div>
                                <p>{{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Sspay to finish complete your purchase')}}.</p>
                                <form method="POST" action="{{ route('sspay.prepare.payments',$store->slug) }}" class="payment-method-form">
                                    @csrf
                                    <input type="hidden" name="transaction_id" value="{{ date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id' }}">
                                    <input type="hidden" name="desc" value="{{time()}}">
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn">{{__('Pay Now')}}</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        @if(isset($store_payments['is_paytab_enabled']) && $store_payments['is_paytab_enabled'] == 'on')
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{__('Paytab')}}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{asset('assets/img/paytab.png')}}" alt="">
                                    </div>
                                </div>
                                <p>{{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Paytab to finish complete your purchase')}}.</p>
                                <form method="POST" action="{{ route('pay.with.paytab',$store->slug) }}" class="payment-method-form">
                                    @csrf
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn">{{__('Pay Now')}}</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        @if(isset($store_payments['is_benefit_enabled']) && $store_payments['is_benefit_enabled'] == 'on')
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{__('Benefit')}}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{asset('assets/img/benefit.png')}}" alt="">
                                    </div>
                                </div>
                                <p>{{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Benefit to finish complete your purchase')}}.</p>
                                <form method="POST" action="{{ route('store.benefit.initiate',$store->slug) }}" class="payment-method-form">
                                    @csrf
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn">{{__('Pay Now')}}</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        @if(isset($store_payments['is_cashfree_enabled']) && $store_payments['is_cashfree_enabled'] == 'on')
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{__('Cashfree')}}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{asset('assets/img/cashfree.png')}}" alt="">
                                    </div>
                                </div>
                                <p>{{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Cashfree to finish complete your purchase')}}.</p>
                                <form method="POST" action="{{ route('store.cashfree.initiate',$store->slug) }}" class="payment-method-form">
                                    @csrf
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn">{{__('Pay Now')}}</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        @if(isset($store_payments['is_aamarpay_enabled']) && $store_payments['is_aamarpay_enabled'] == 'on')
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{__('Aamarpay')}}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{asset('assets/img/aamarpay.png')}}" alt="">
                                    </div>
                                </div>
                                <p>{{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Aamarpay to finish complete your purchase')}}.</p>
                                <form method="POST" action="{{ route('store.pay.aamarpay.payment',$store->slug) }}" class="payment-method-form">
                                    @csrf
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn">{{__('Pay Now')}}</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        @if(isset($store_payments['is_paytr_enabled']) && $store_payments['is_paytr_enabled'] == 'on')
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{__('Paytr')}}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{asset('assets/img/paytr.png')}}" alt="">
                                    </div>
                                </div>
                                <p>{{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Paytr to finish complete your purchase')}}.</p>
                                <form method="POST" action="{{ route('order.pay.with.paytr',$store->slug) }}" class="payment-method-form">
                                    @csrf
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn">{{__('Pay Now')}}</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        @if(isset($store_payments['is_yookassa_enabled']) && $store_payments['is_yookassa_enabled'] == 'on')
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{__('Yookassa')}}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{asset('assets/img/yookassa.png')}}" alt="">
                                    </div>
                                </div>
                                <p>{{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Yookassa to finish complete your purchase')}}.</p>
                                <form method="get" action="{{ route('store.with.yookassa',$store->slug) }}" class="payment-method-form">
                                    @csrf
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn">{{__('Pay Now')}}</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        @if(isset($store_payments['is_midtrans_enabled']) && $store_payments['is_midtrans_enabled'] == 'on')
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{__('Midtrans')}}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{asset('assets/img/midtrans.png')}}" alt="">
                                    </div>
                                </div>
                                <p>{{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Midtrans to finish complete your purchase')}}.</p>
                                <form method="get" action="{{ route('order.pay.with.midtrans',$store->slug) }}" class="payment-method-form">
                                    @csrf
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn">{{__('Pay Now')}}</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        @if(isset($store_payments['is_xendit_enabled']) && $store_payments['is_xendit_enabled'] == 'on')
                            <div class="payment-method">
                                <div class="payment-title d-flex align-items-center justify-content-between">
                                    <h4>{{__('Xendit')}}</h4>
                                    <div class="payment-image extra-size d-flex align-items-center">
                                        <img src="{{asset('assets/img/xendit.png')}}" alt="">
                                    </div>
                                </div>
                                <p>{{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Xendit to finish complete your purchase')}}.</p>
                                <form method="get" action="{{ route('order.pay.with.xendit',$store->slug) }}" class="payment-method-form">
                                    @csrf
                                    <div class="form-group text-right">
                                        <button type="submit" class="btn">{{__('Pay Now')}}</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        @if(isset($store_payments['is_nepalste_enabled']) && $store_payments['is_nepalste_enabled'] == 'on')
                        <div class="payment-method">
                            <div class="payment-title d-flex align-items-center justify-content-between">
                                <h4>{{__('Nepalste')}}</h4>
                                <div class="payment-image extra-size d-flex align-items-center">
                                    <img src="{{asset('assets/img/nepalste.png')}}" alt="">
                                </div>
                            </div>
                            <p>{{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Nepalste to finish complete your purchase')}}.</p>
                            <form method="get" action="{{ route('order.with.nepalste',$store->slug) }}" class="payment-method-form">
                                @csrf
                                <div class="form-group text-right">
                                    <button type="submit" class="btn">{{__('Pay Now')}}</button>
                                </div>
                            </form>
                        </div>
                    @endif
                    @if(isset($store_payments['is_paiementpro_enabled']) && $store_payments['is_paiementpro_enabled'] == 'on')
                        <div class="payment-method">
                            <div class="payment-title d-flex align-items-center justify-content-between">
                                <h4>{{__('Paiement Pro')}}</h4>
                                <div class="payment-image extra-size d-flex align-items-center">
                                    <img src="{{asset('assets/img/paiementpro.png')}}" alt="">
                                </div>
                            </div>
                            <p>{{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Paiement Pro to finish complete your purchase')}}.</p>
                            <form method="get" action="{{ route('order.with.paiementpro',$store->slug) }}" class="payment-method-form">
                                @csrf
                                <div class="form-group">
                                    <label>{{__('Mobile Number')}}</label>
                                    <input name="mobile_number" id="mobile_number" type="number" placeholder="Enter Your Mobile Number">
                                </div>
                                <div class="form-group">
                                    <label>{{__('Channel')}}</label>
                                    <input name="channel" id="channel" type="text" placeholder="Enter Your Channel Code" required>
                                    <small style="color: red">{{ __('Example : OMCIV2,MOMO,CARD,FLOOZ ,PAYPAL') }}</small>
                                </div>
                                <div class="form-group text-right">
                                    <button type="submit" class="btn">{{__('Pay Now')}}</button>
                                </div>
                            </form>
                        </div>
                    @endif
                    @if(isset($store_payments['is_fedapay_enabled']) && $store_payments['is_fedapay_enabled'] == 'on')
                        <div class="payment-method">
                            <div class="payment-title d-flex align-items-center justify-content-between">
                                <h4>{{__('Fedapay')}}</h4>
                                <div class="payment-image extra-size d-flex align-items-center">
                                    <img src="{{asset('assets/img/fedapay.svg')}}" alt="">
                                </div>
                            </div>
                            <p>{{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to Fedapay to finish complete your purchase')}}.</p>
                            <form method="get" action="{{ route('order.with.fedapay',$store->slug) }}" class="payment-method-form">
                                @csrf
                                <div class="form-group text-right">
                                    <button type="submit" class="btn">{{__('Pay Now')}}</button>
                                </div>
                            </form>
                        </div>
                    @endif
                    @if(isset($store_payments['is_payhere_enabled']) && $store_payments['is_payhere_enabled'] == 'on')
                        <div class="payment-method">
                            <div class="payment-title d-flex align-items-center justify-content-between">
                                <h4>{{__('PayHere')}}</h4>
                                <div class="payment-image extra-size d-flex align-items-center">
                                    <img src="{{asset('assets/img/payhere.png')}}" alt="">
                                </div>
                            </div>
                            <p>{{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to PayHere to finish complete your purchase')}}.</p>
                            <form method="post" action="{{ route('order.with.payhere',$store->slug) }}" class="payment-method-form">
                                @csrf
                                <div class="form-group text-right">
                                    <button type="submit" class="btn">{{__('Pay Now')}}</button>
                                </div>
                            </form>
                        </div>
                    @endif
                    @if(isset($store_payments['is_cinetpay_enabled']) && $store_payments['is_cinetpay_enabled'] == 'on')
                        <div class="payment-method">
                            <div class="payment-title d-flex align-items-center justify-content-between">
                                <h4>{{__('CinetPay')}}</h4>
                                <div class="payment-image extra-size d-flex align-items-center">
                                    <img src="{{asset('assets/img/cinetpay.png')}}" alt="">
                                </div>
                            </div>
                            <p>{{__('Pay your order using the most known and secure platform for online money transfers. You will be redirected to CinetPay to finish complete your purchase')}}.</p>
                            <form method="post" action="{{ route('order.with.cinetpay',$store->slug) }}" class="payment-method-form">
                                @csrf
                                <div class="form-group text-right">
                                    <button type="submit" class="btn">{{__('Pay Now')}}</button>
                                </div>
                            </form>
                        </div>
                    @endif
                        <div class="pagination-btn d-flex align-items-center justify-content-center ">
                            <a href="{{route('store.slug',$store->slug)}}" class="btn back-btn">{{__('Return to shop')}}</a>
                        </div>
                    </div>
    
                    <div class="col-lg-4 col-12 right-side">
                        <div class="mini-cart internal-box">
                            <div class="internal-header">
                                <h4>{{__('Summary')}}</h4>
                            </div>
                            <div id="cart-body" class="mini-cart-has-item">
                                @if(!empty($products))
                                    @php
                                        $total = 0;
                                        $sub_tax = 0;
                                        $sub_total= 0;
                                    @endphp
                                    @foreach($products as $product)
                                        @if(isset($product['variant_id']) && !empty($product['variant_id']))
                                            <div class="mini-cart-body">
                                                <div class="mini-cart-item">
                                                    <div class="mini-cart-image">
                                                        <a href="">
                                                            <img src="{{$productImg .$product['image']}}" alt="img">
                                                        </a>
                                                    </div>
                                                    <div class="mini-cart-details">
                                                        <p class="mini-cart-title">
                                                            <a href="">{{$product['product_name'].' - ( ' . $product['variant_name'] .' ) '}}</a>
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
                                            </div>
                                            @php
                                                $total += $totalprice;
                                            @endphp
                                        @else
                                            <div class="mini-cart-body">
                                                <div class="mini-cart-item">
                                                    <div class="mini-cart-image">
                                                        <a href="">
                                                            <img src="{{$productImg .$product['image']}}" alt="img">
                                                        </a>
                                                    </div>
                                                    <div class="mini-cart-details">
                                                        <p class="mini-cart-title">
                                                            <a href="">{{$product['product_name']}}</a>
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
                                                            <a class="remove_item">
                                                                {{\App\Models\Utility::priceFormat($totalprice)}}
                                                            </a>
                                                            @php
                                                                $total += $totalprice;
                                                            @endphp
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                        @endif
                                    @endforeach
                                    <div class="mini-cart-footer">
                                        <input type="hidden" name="coupon" class="hidd_val" data_id="{{$coupon_id}}" value="{{$coupon_price}}"></div>
                                        <ul class="cart-summery">
                                            
                                            <li>
                                                <span class="cart-sum-left"> {{ __('item') }}</span>
                                                <span class="cart-sum-right">{{\App\Models\Utility::priceFormat( !empty($sub_total)?$sub_total:'0')}}</span>
                                            </li> 
                                            @if($store->enable_shipping == "on")
                                            <li class="shipping_price_add">
                                                    <span class="cart-sum-left">{{__('Shipping Price')}} </span>
                                                    <span class="cart-sum-right shipping_price" data-value="{{$shipping_price}}">{{\App\Models\Utility::priceFormat(!empty($shipping_price)?$shipping_price:0)}}</span>
                                            </li>
                                            @endif
                                            <li>
                                                <span class="cart-sum-left">{{__('Coupon')}} </span>
                                                <span class="cart-sum-right dicount_price">{{!empty($discount_price)?$discount_price:'0.00'}}</span>
                                            </li>
                                            
                                            @foreach($taxArr['tax'] as $k=>$tax)
                                            <li>
                                                @php
                                                    $rate = $taxArr['rate'][$k];
                                                @endphp
                                                <span class="cart-sum-left">{{$tax}}</span>
                                                <span class="cart-sum-right"> {{\App\Models\Utility::priceFormat($rate)}} </span>
                                            </li>
                                            @endforeach 
                                        </ul>
                                        <div class="mini-cart-footer-total-row d-flex align-items-center justify-content-between">
                                            <div class="mini-total-lbl">
                                                {{__('Total')}}
                                            </div>
                                            <div class="mini-total-price final_total_price" id="total_value">
                                                <input type="hidden" class="product_total" value="{{$total+$shipping_price-$coupon_price}}">
                                                <input type="hidden" class="total_pay_price" value="{{App\Models\Utility::priceFormat($total)}}">
                                                <span class="pro_total_price" data-value="{{$total+$shipping_price-$coupon_price}}">{{\App\Models\Utility::priceFormat(!empty($total)?$total+$shipping_price-$coupon_price:0)}}</span> 
                                            </div>
                                        </div>
                                    </div>   
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


@endsection
@push('script-page')
    <script src="{{ asset('custom/libs/jquery-mask-plugin/dist/jquery.mask.min.js') }}"></script>


    @if (isset($store_payments['is_stripe_enabled']) && $store_payments['is_stripe_enabled'] == 'on')
        <script src="https://js.stripe.com/v3/"></script>
        <script type="text/javascript">
            var stripe = Stripe('{{ isset($store_payments['stripe_key']) ? $store_payments['stripe_key'] : '' }}');
            var elements = stripe.elements();

            // Custom styling can be passed to options when creating an Element.
            var style = {
                base: {
                    // Add your base input styles here. For example:
                    fontSize: '14px',
                    color: '#32325d',
                },
            };

            // Create an instance of the card Element.
            var card = elements.create('card', {
                style: style
            });

            // Add an instance of the card Element into the `card-element` <div>.
            card.mount('#card-element');

            // Create a token or display an error when the form is submitted.
            var form = document.getElementById('payment-form');
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                stripe.createToken(card).then(function(result) {
                    if (result.error) {
                        $("#card-errors").html(result.error.message);
                        show_toastr('Error', result.error.message, 'error');
                    } else {
                        // Send the token to your server.
                        stripeTokenHandler(result.token);
                    }
                });
            });

            function stripeTokenHandler(token) {
                // Insert the token ID into the form so it gets submitted to the server
                var form = document.getElementById('payment-form');
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);
                form.appendChild(hiddenInput);

                form.submit();
            }
        </script>
    @endif
    <script>
        $(document).on('click', '#owner-whatsapp', function() {
            $('#owner-whatsapp').prop('disabled',true);
            var product_array = '{{ $encode_product }}';
            var product = JSON.parse(product_array.replace(/&quot;/g, '"'));
            var order_id = '{{ $order_id = time() }}';
            var total_price = $('.pro_total_price').attr('data-value');
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();

            var data = {
                type: 'whatsapp',
                coupon_id: coupon_id,
                dicount_price: dicount_price,
                total_price: total_price,
                product: product,
                order_id: order_id,
                wts_number: $('#wts_number').val()
            }
            getWhatsappUrl(dicount_price, total_price, coupon_id, data);

            $.ajax({
                url: '{{ route('user.whatsapp', $store->slug) }}',
                method: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.status == 'success') {
                        $('#owner-whatsapp').prop('disabled',true);
                        show_toastr(data["status"], data["success"], data["status"]);

                        setTimeout(function() {
                            var get_url_msg_url = $('#return_url').val();
                            var append_href = get_url_msg_url +
                                '{{ route('user.order', [$store->slug, Crypt::encrypt(!empty($order->id) ? $order->id + 1 : 0 + 1)]) }}';
                            window.open(append_href, '_blank');
                        }, 1000);

                        setTimeout(function() {
                            var url =
                                '{{ route('store-complete.complete', [$store->slug, ':id']) }}';
                            url = url.replace(':id', data.order_id);

                            window.location.href = url;
                        }, 1000);

                    } else {
                        $('#owner-whatsapp').prop('disabled',false);
                        show_toastr("Error", data.success, data["status"]);
                    }
                }
            });

        });

        $(document).on('click', '#owner-telegram', function() {
            $('#owner-telegram').prop('disabled',true);
            var product_array = '{{ $encode_product }}';
            var product = JSON.parse(product_array.replace(/&quot;/g, '"'));
            var order_id = '{{ $order_id = time() }}';
            var total_price = $('.pro_total_price').attr('data-value');
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();


            var data = {
                type: 'telegram',
                coupon_id: coupon_id,
                dicount_price: dicount_price,
                total_price: total_price,
                product: product,
                order_id: order_id,
            }

            getWhatsappUrl(dicount_price, total_price, coupon_id, data);

            $.ajax({
                url: '{{ route('user.telegram', $store->slug) }}',
                method: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.status == 'success') {
                        $('#owner-telegram').prop('disabled',true);
                        show_toastr(data["status"], data["success"], data["status"]);

                        setTimeout(function() {


                            var url =
                                '{{ route('store-complete.complete', [$store->slug, ':id']) }}';
                            url = url.replace(':id', data.order_id);

                            window.location.href = url;
                        }, 1000);

                    } else {
                        $('#owner-telegram').prop('disabled',false);
                        show_toastr("Error", data.success, data["status"]);
                    }
                }
            });
        });
        $(document).on('click', '#cash_on_delivery', function() {
            $('#cash_on_delivery').prop('disabled',true);
            var product_array = '{{ $encode_product }}';
            var product = JSON.parse(product_array.replace(/&quot;/g, '"'));
            var order_id = '{{ $order_id = time() }}';

            var total_price = $('.pro_total_price').attr('data-value');
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();

            var data = {
                coupon_id: coupon_id,
                dicount_price: dicount_price,
                total_price: total_price,
                product: product,
                order_id: order_id,
            }

            $.ajax({
                url: '{{ route('user.cod', $store->slug) }}',
                method: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.status == 'success') {
                        $('#cash_on_delivery').prop('disabled',true);
                        show_toastr(data["status"], data["success"], data["status"]);

                        setTimeout(function() {
                            var url =
                                '{{ route('store-complete.complete', [$store->slug, ':id']) }}';
                            url = url.replace(':id', data.order_id);
                            window.location.href = url;
                        }, 1000);
                    } else {
                        $('#cash_on_delivery').prop('disabled',false);
                        show_toastr("Error", data.success, data["status"]);
                    }
                }
            });
        });
        $(document).on('click', '#bank_transfer', function() {
            $('#bank_transfer').prop('disabled',true);
            var product_array = '{!! $encode_product !!}';
            var product = JSON.parse(product_array.replace(/&quot;/g, '"'));
            var order_id = '{{ $order_id = time() }}';
            var total_price = $('.pro_total_price').attr('data-value');
            var coupon_id = $('.hidden_coupon').attr('data_id');
            var dicount_price = $('.dicount_price').html();
            var files = $('#bank_transfer_invoice')[0].files;

            var formData = new FormData($("#bank_transfer_form")[0]);
            formData.append('product', product_array);
            formData.append('order_id', order_id);
            formData.append('total_price', total_price);
            formData.append('coupon_id', coupon_id);
            formData.append('dicount_price', dicount_price);
            formData.append('files', files);

            $.ajax({
                url: '{{ route('user.bank_transfer', $store->slug) }}',
                method: 'POST',
                // data: data,
                data: formData,
                contentType: false,
                // cache: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.status == 'success') {
                        $('#bank_transfer').prop('disabled',true);
                        show_toastr(data["status"], data["success"], data["status"]);
                        setTimeout(function() {
                            var url =
                                '{{ route('store-complete.complete', [$store->slug, ':id']) }}';
                            url = url.replace(':id', data.order_id);
                            window.location.href = url;
                        }, 1000);
                    } else {
                        $('#bank_transfer').prop('disabled',false);
                        show_toastr("Error", data.success, data["status"]);
                    }
                }
            });
        });
    </script>
    <script>
        // Apply Coupon
        $(document).on('click', '.apply-coupon', function(e) {
            e.preventDefault();

            var ele = $(this);
            var coupon = ele.closest('.row').find('.coupon').val();
            var hidden_field = $('.hidden_coupon').val();
            var price = $('#card-summary .product_total').val();
            var shipping_price = $('#card-summary .shipping_price').attr('data-value');

            if (coupon == hidden_field) {
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
                                    '<span class="text-sm font-weight-bold total_price" data-value="' +
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
                    show_toastr('Error', '{{ __('Invalid Coupon Code.') }}', 'error');
                }
            }

        });

        //for create/get Whatsapp Url
        function getWhatsappUrl(coupon = '', finalprice = '', coupon_id = '', data = '') {
            $.ajax({
                url: '{{ route('get.whatsappurl', $store->slug) }}',
                method: 'post',
                data: {
                    dicount_price: coupon,
                    finalprice: finalprice,
                    coupon_id: coupon_id,
                    data: data
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.status == 'success') {
                        $('#return_url').val(data.url);
                        $('#return_order_id').val(data.order_id);

                    } else {
                        $('#return_url').val('')
                        show_toastr("Error", data.success, data["status"]);
                    }
                }
            });
        }

        //for create/get Telegram Url
        function getTelegramUrl(coupon = '', finalprice = '', coupon_id = '', data = '') {
            $.ajax({
                url: '{{ route('get.whatsappurl', $store->slug) }}',
                method: 'post',
                data: {
                    dicount_price: coupon,
                    finalprice: finalprice,
                    coupon_id: coupon_id,
                    data: data
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.status == 'success') {
                        $('#return_url').val(data.url);
                        $('#return_order_id').val(data.order_id);

                    } else {
                        $('#return_url').val('')
                        show_toastr("Error", data.success, data["status"]);
                    }
                }
            });
        }
        // function removecartsession(slug) {
        //     $.ajax({
        //         url: '{{ route('remove.session', $store->slug) }}?cart=yes',
        //         method: 'get',
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         success: function(data) {

        //         }
        //     });
        // }


        function removesession(slug) {
            $.ajax({
                url: '{{ route('remove.session', $store->slug) }}',
                method: 'get',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {

                }
            });
        }
    </script>
    <script>
        $(document).ready(function(){
            get_payfast_status(amount = 0,coupon = null);
        })
        function get_payfast_status(amount,coupon){
            var product_id = $('#product_id').val();
            var coupon_id = $('.hidden_coupon').attr('data_id');
            $.ajax({
                url: '{{ route('payfast.prepare.payment', $store->slug) }}',
                method: 'POST',
                data : {
                    'product_id' : product_id,
                    'coupon_amount' : amount,
                    'coupon_id' : coupon_id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.success == true) {
                        $('#get-payfast-inputs').append(data.inputs);

                    }else{
                        show_toastr('Error', data.inputs, 'error')
                    }
                }
            });
        }
    </script>
@endpush
