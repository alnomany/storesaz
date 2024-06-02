@php
    $logo = \App\Models\Utility::get_file('uploads/is_cover_image/');
@endphp
{{-- <div class="modal-body p-0"> --}}
    <input type="hidden" id="product_id" value="{{ $products->id }}">
    <input type="hidden" id="variant_id" value="">
    <input type="hidden" id="variant_qty" value="">
    <div class="cart-variant-body">
        <div class="row">
            <div class="col-lg-4 col-md-5 col-12">
                <div class="cart-variant-img">
                    <div class="variant-main-media">
                        <img src="{{ $logo . (isset($products->is_cover) && !empty($products->is_cover) ? $products->is_cover : 'default_img.png') }}"
                            class="default-img" target="_blank" alt="logitech Keys">{{-- style=" height: 6rem; width: 100%;"--}}
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-7 col-12">
                <div class="cart-variant-detail">
                    <span
                        class="ctg-badge">{{ isset($products->categories) && !empty($products->categories) ? $products->categories->name : '' }}</span>
                    <h3>{{ $products->name }}</h3>
                    <p class="pt-2">{{__('VARIATION:')}}</p>
                    <div class="pv-selection">
                        @foreach ($product_variant_names as $key => $variant)
                            <label for="" class="pt-2">{{ ucfirst($variant->variant_name) }}</label>
                            <select name="product[{{ $key }}]" id="pro_variants_name"
                                class="form-control custom-select variant-selection pro_variants_name{{ $key }}">
                                <option value="0">{{ __('Select Option') }}</option>
                                @foreach ($variant->variant_options as $key => $values)
                                    <option value="{{ $values }}">
                                        {{ $values }}
                                    </option>
                                @endforeach
                            </select>
                        @endforeach
                    </div>
                    <div class="cart-variable row pt-3">
                        <div class="col-md-6">
                            <div class="variant_qty" style=" font-size: large; ">
                                {{__('QTY')}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="variation_price1 text-end" style=" font-size: large; ">
                                @if ($products->enable_product_variant == 'on')
                                    <p style=" font-size: large; ">{{__('Please Select Variants')}}</p>
                                @else
                                    <p>{{ \App\Models\Utility::priceFormat($products->price) }}</p>

                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- </div> --}}
<div class="col-12 d-flex justify-content-end col-form-label">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <a href="#!" type="submit" class="btn btn-primary add_to_cart_variant toacartvariant ms-2" data-toggle="tooltip" data-id="{{ $products->id }}" >{{--data-url="{{ url('addToCartVariant/' . $products->id . '/' . $session_key) }}"--}}
        {{ __('Add To Cart') }}
        <i class="fas fa-shopping-basket ms-1" style="font-size: initial;"></i>
    </a>
</div>
{{-- <script>
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
           var session_key = "{{ $session_key }}";
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
</script> --}}