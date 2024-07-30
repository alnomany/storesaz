<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = [
        'country',
        'city',
        'supplier_id',
        'billing_no',
        'notes',
        'product_id',
        'discount',
        'the_amount_paid',
        'payment_method',
        'the_amount_owed_supplier',
        'due_date_payment',
        'purchase_id',
        'created_by',
        'warehouse_id',
        'purchase_number',
        'category_id',
        'status',
        'send_date',

        'date',

    ];

    public static $statues = [
        'Draft',
        'Sent',
        'Unpaid',
        'Partialy Paid',
        'Paid',
    ];
    public function getSubTotal()
    {

        $subTotal = 0;
        foreach($this->items as $product)
        {

            $subTotal += ($product->price * $product->quantity);
        }

        return $subTotal;
    }
    public function getTotal()
    {

        return ($this->getSubTotal() -$this->getTotalDiscount()) + $this->getTotalTax();
    }
    
    public function getTotalTax()
    {
        $taxData = Utility::getTaxData();
        $totalTax = 0;
        foreach($this->items as $product)
        {
            // $taxes = Utility::totalTaxRate($product->tax);

            $taxArr = explode(',', $product->tax);
            $taxes = 0;
            foreach ($taxArr as $tax) {
                // $tax = TaxRate::find($tax);
                $taxes += !empty($taxData[$tax]['rate']) ? $taxData[$tax]['rate'] : 0;
            }

            $totalTax += ($taxes / 100) * ($product->price * $product->quantity);
        }

        return $totalTax;
    }

    
    public function getTotalDiscount()
    {
        $totalDiscount = 0;
        foreach($this->items as $product)
        {
            $totalDiscount += $product->discount;
        }

        return $totalDiscount;
    }
    public function getDue()
    {
        $due = 0;
        foreach($this->payments as $payment)
        {
            $due += $payment->amount;
        }

        return ($this->getTotal() - $due);
    }
    public function lastPayments()
    {
        return $this->hasOne('App\Models\PurchasePayment', 'id', 'purchase_id');
    }
    public function payments()
    {
        return $this->hasMany('App\Models\PurchasePayment', 'purchase_id', 'id');
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function items()
    {
        return $this->hasMany('App\Models\PurchasedProductsVendor', 'purchase_id', 'id');
    }
}
