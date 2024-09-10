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
        'product' => 'product',
        'income' => 'Income',
        'expense' => 'Expense',
        'asset'=> 'Asset',
        'liability' => 'Liability',
        'equity' => 'Equity',
        'costs of good sold' => 'Costs of Goods Sold',
    ];

}
