<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $fillable = [
        'name',
        'price',
        'location_id',
        'store_id',
        'created_by',
    ];
    private static $location = null;
    public function locationName()
    {
        if(is_null(self::$location)){
            $result =  Location::whereIn('id',explode(',',$this->location_id))->get()->pluck('name')->toArray();
            self::$location = implode(', ',$result);

        }
        return self::$location;
    }
}
