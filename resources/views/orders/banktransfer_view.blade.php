<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
        <table class="table">
            <tr>
                <td class="h6">{{__('Order Id')}}</td>
                <td>{{ $order->order_id }}</td>
            </tr>
            <tr>
                <td class="h6">{{__('Name')}}</td>
                <td>{{ $order->name }}</td>
            </tr>
            <tr>
                <td class="h6">{{__('Price')}}</td>
                <td>{{ \App\Models\Utility::priceFormat($order->price) }}</td>
            </tr>
            <tr>
                <td class="h6">{{__('Payment Type')}}</td>
                <td>{{ $order->payment_type }}</td>
            </tr>
            <tr>
                <td class="h6">{{__('Payment Status')}}</td>
                <td>{{ $order->payment_status }}</td>
            </tr>
            <tr>
                <td class="h6">{{__('Bank Details')}}</td>
                <td>{!! !empty($store->bank_number) ? $store->bank_number : '' !!}</td>
            </tr>
            <tr>
                <td class="h6">{{__('Payment Status')}}</td>
                <td><a href="{{ \App\Models\Utility::get_file($order->receipt) }}"  title="Invoice" class="btn btn-primary btn-sm" download="">
                    <i class="ti ti-download"></i>
                </a></td>
            </tr>

        </table>
        </div>
    </div>
</div>
@if (\Auth::user()->type !== 'super admin')
    {{ Form::model($order, ['route' => ['order.status.edit', $order->id], 'method' => 'POST']) }}
        <div class="text-end">
            <input type="submit" value="{{ __('approved') }}" class="btn btn-success rounded" name="status">
            <input type="submit" value="{{ __('Reject') }}" class="btn btn-danger rounded" name="status">
        </div>
    {{ Form::close() }}
@endif
