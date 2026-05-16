<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'purchase_price',
        'cash_margin_percentage',
        'cash_sale_price',
        'emi_margin_percentage',
        'emi_sale_price',
        'stock',
        'discount_percentage'
    ];

    public function getDiscountedPriceAttribute()
    {
        if ($this->discount_percentage > 0) {
            $discountAmount = ($this->cash_sale_price * $this->discount_percentage) / 100;
            return $this->cash_sale_price - $discountAmount;
        }
        return $this->cash_sale_price;
    }
}
