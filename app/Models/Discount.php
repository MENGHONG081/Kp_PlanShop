<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'product_id',
        'discount_percent',
        'price_after_discount',
        'description',
        'discount_date',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'discount_date' => 'date',
        'discount_percent' => 'decimal:2',
        'price_after_discount' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
