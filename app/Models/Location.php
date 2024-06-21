<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'name',
        'store_id',
        'country',
        'city',
        'created_by',
    ];
}
