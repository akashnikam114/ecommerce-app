<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'product_id', 'qty', 'price_at_time'];
    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'price_at_time' => 'decimal:2',
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
