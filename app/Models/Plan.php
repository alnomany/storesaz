<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    private static $freeplan = null;
    private static $mostPurchasePlan = null;
    protected $fillable = [
        'name',
        'price',
        'duration',
        'max_stores',
        'max_products',
        'max_users',
        'storage_limit',
        'enable_custdomain',
        'enable_custsubdomain',
        'additional_page',
        'blog',
        'shipping_method',
        'trial',
        'trial_days',
        'is_active',
        'enable_chatgpt',
        'image',
        'description',
        'pwa_store',
    ];

    public static $arrDuration = [
        'Lifetime' => 'Lifetime',
        'Month' => 'Per Month',
        'Year' => 'Per Year',
    ];

    public function status()
    {
        return [
            __('Lifetime'),
            __('Per Month'),
            __('Per Year'),
        ];
    }

    public static function total_plan()
    {
        return Plan::count();
    }

    public static function most_purchese_plan()
    {
        if(is_null(self::$freeplan)){
            self::$freeplan = Plan::where('price', '<=', 0)->first()->id;
        }
        $freeplan = self::$freeplan;
        if(is_null(self::$mostPurchasePlan)){

            self::$mostPurchasePlan =  User:: select('plans.name', 'plans.id', \DB::raw('count(*) as total'))->join('plans', 'plans.id', '=', 'users.plan')->where('type', '=', 'owner')->where('plan', '!=', $freeplan)->orderBy('total', 'Desc')->groupBy('plans.name', 'plans.id')->first();
        }
        return self::$mostPurchasePlan;
    }

    public function transkeyword()
    {
        $arr = [
            __('Per Month'),
            __('Per Year'),
            __('Year'),
        ];
    }
}
