<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasedProductsVendor extends Model
{
    use HasFactory;
    protected $table ='purchased_products_vendor';

    protected $fillable = [
        'product_id',
        'purchase_id',
        'quantity',
        'tax',
        'discount',
        'total',
    ];

    public function product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

}
