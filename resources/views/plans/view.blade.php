<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
        <table class="table">
            <tr>
                <td class="h6">{{__('Order Id')}}</td>
                <td>{{ $order->order_id }}</td>
            </tr>
            <tr>
                <td class="h6">{{__('Plan Name')}}</td>
                <td>{{ $order->plan_name }}</td>
            </tr>
            <tr>
                <td class="h6">{{__('Plan Price')}}</td>
                <td>{{ $order->price }}</td>
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
                <td>{!! !empty($admin_payments_setting['bank_number'])?$admin_payments_setting['bank_number']:'' !!}</td>
            </tr>
            <tr>
                <td class="h6">{{__('Payment Status')}}</td>
                <td><a href="{{ \App\Models\Utility::get_file($order->receipt) }}"  title="Invoice" down
                    class="btn btn-primary btn-sm" download="">
                    <i class="ti ti-download"></i>
                </a></td>
            </tr>

        </table>
        </div>
    </div>
</div>
@if (\Auth::user()->type == 'super admin')
    {{ Form::model($order, ['route' => ['status.edit', $order->id], 'method' => 'POST']) }}
        <div class="text-end">
            <input type="submit" value="{{ __('Approved') }}" class="btn btn-success rounded" name="status">
            <input type="submit" value="{{ __('Reject') }}" class="btn btn-danger rounded" name="status">
        </div>
    {{ Form::close() }}
@endif
