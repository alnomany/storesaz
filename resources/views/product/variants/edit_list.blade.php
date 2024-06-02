
<div class="table-responsive">
    <table class="table">
        <thead>
        <tr class="text-center">

            @foreach($variantArray as $variant)
                <th><span>{{ ucwords($variant) }}</span></th>
            @endforeach
            <th><span>{{ __('Price') }}</span></th>
            <th><span>{{ __('Quantity') }}</span></th>

        </tr>
        </thead>
        <tbody>
            @php
                $io=0;
            @endphp
           
            @foreach($possibilities as $counter => $possibility)
            @php
                $name = App\Models\ProductVariantOption::variant_name($possibility, $io, $product_id);
                if ($name['has_variant'] == 0) {
                    $io++;
                }
                @endphp
                {{-- @DD($possibilities,$variantArray) --}}
            <tr>
                @foreach(explode(' : ', $possibility) as $key => $values)
                    <td>
                        <input type="text" autocomplete="off" spellcheck="false" class="form-control wid-100" value="{{ $values }}" name="{{ !empty($name['has_name'][$key]) ? $name['has_name'][$key] : $name['has_name'][0] }}" readonly>
                    </td>
                @endforeach

                {!! Form::hidden($name['has_name'][0], $possibility) !!}

                <td>
                    <input type="number" id="vprice_{{ $counter }}" autocomplete="off" spellcheck="false" placeholder="{{ __('Enter Price') }}" class="form-control wid-100"
                    name="{{ $name['price'] }}" value="{{ $name['price_val'] }}">
                </td>
                <td>
                    <input type="number" id="vquantity_{{ $counter }}" autocomplete="off" spellcheck="false" placeholder="{{ __('Enter Quantity') }}" class="form-control wid-100"
                    name="{{ $name['qty'] }}" value="{{ $name['qty_val'] }}">
                </td>
                {{--  <td class="d-flex align-items-center mt-3 border-0 ">
                    <div class="action-btn bg-danger ms-2 delet-posibility">
                        <a class="text-white align-items-center btn btn-sm d-inline-flex " href="javascript:;">
                            <i class="ti ti-trash"></i>
                        </a>
                    </div>
                </td>  --}}
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
