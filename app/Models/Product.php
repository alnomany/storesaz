<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    private static $ratting = null;
    private static $user_count = null;
    protected $fillable = [
        'name',
        'product_categorie',
        'price',
        'quantity',
        'SKU',
        'product_tax',
        'product_display',
        'rating_display',
        'is_cover',
        'description',
        'detail',
        'specification',
        'created_by',
    ];

    public function categories()
    {
        return $this->hasOne('App\Models\ProductCategorie', 'id', 'product_categorie');
    }

    public function product_taxs()
    {
        return $this->hasOne('App\Models\ProductTax', 'id', 'product_tax');
    }

    private static $productCategory = [];
    public function product_category()
    {
        if(!array_key_exists($this->product_categorie,self::$productCategory)){
            $result = ProductCategorie::whereIn('id', explode(',', $this->product_categorie))->get()->pluck('name')->toArray();
            self::$productCategory[$this->product_categorie] =  implode(', ', $result);
            $data = implode(', ', $result);
        }
        else{
            $data =   self::$productCategory[$this->product_categorie];
        }
        return $data;
    }

    public function product_rating()
    {
        // if(is_null(self::$ratting)){
            $ratting    = Ratting::where('product_id', $this->id)->where('rating_view', 'on')->sum('ratting');
        //     self::$ratting = $ratting;
        // }
        // $ratting = self::$user_count;
        // if(is_null(self::$ratting)){
            $user_count = Ratting::where('product_id', $this->id)->where('rating_view', 'on')->count();
        //     self::$user_count = $user_count;
        // }
        // $user_count = self::$user_count;
        if($user_count > 0)
        {
            $avg_rating = number_format($ratting / $user_count, 1);
        }
        else
        {
            $avg_rating = number_format($ratting / 1, 1);

        }

        return $avg_rating;
    }

    public static function getCategoryById($productId)
    {
        $product = Product::find($productId);

        $result = ProductCategorie::whereIn('id', explode(',', $product->product_categorie))->get()->pluck('name')->toArray();

        return implode(', ', $result);
    }

    public static function getRatingById($productId)
    {
        $ratting    = Ratting::where('product_id', $productId)->where('rating_view', 'on')->sum('ratting');
        $user_count = Ratting::where('product_id', $productId)->where('rating_view', 'on')->count();
        if($user_count > 0)
        {
            $avg_rating = number_format($ratting / $user_count, 1);
        }
        else
        {
            $avg_rating = number_format($ratting / 1, 1);

        }

        return $avg_rating;
    }

    public static function possibleVariants($groups, $prefix = '')
    {
        $result = [];
        $group  = array_shift($groups);
        foreach($group as $selected)
        {
            if($groups)
            {
                $result = array_merge($result, self::possibleVariants($groups, $prefix . $selected . ' : '));
            }
            else
            {
                $result[] = $prefix . $selected;
            }
        }
       
        return $result;
    }

    public function product_img()
    {

        return $this->hasOne('App\Models\product_images', 'product_id', 'id');
    }

    public static function getProductById($id)
    {
        return Product::find($id);
    }

    public function getVarByProductId($id){
        $dsji = ProductVariantOption::where('product_id',$id)->get();
            return $dsji;
    }
}
