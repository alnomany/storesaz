<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanOrder extends Model
{
    protected $fillable = [
        'order_id',
        'name',
        'card_number',
        'card_exp_month',
        'card_exp_year',
        'plan_name',
        'plan_id',
        'coupon',
        'coupon_json',
        'discount_price',
        'price',
        'price_currency',
        'txn_id',
        'payment_type',
        'payment_status',
        'receipt',
        'user_id',
    ];
    public static function total_plan_price()
    {
        return PlanOrder::sum('price');
    }
    public static function total_orders()
    {
        return PlanOrder::count();
    }
    public function total_coupon_used()
    {
	
        return $this->hasOne('App\Models\UserCoupon', 'order', 'order_id');
    }
}
