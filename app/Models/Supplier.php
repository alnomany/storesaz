<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'accont_number',
        'country',
        'city',
        'address',
        'tax_number',
        'password',
        'store_id',
        'created_by',
    ];
}
