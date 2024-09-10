<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategorie extends Model
{
    protected $fillable = [
        'name',
        'created_by',
    ];
    public static $catTypes = [
        'product' => 'Product - منتج',
        'income' => 'Income - دخل',
        'expense' => 'Expense - مصروف',
        'asset'=> 'Asset - أصل',
        'liability' => 'Liability - التزام',
        'equity' => 'Equity - حقوق الملكية',
        'costs of good sold' => 'Costs of Goods Sold - تكلفة البضاعة المباعة',
    ];

}
