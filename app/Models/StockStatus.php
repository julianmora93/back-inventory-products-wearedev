<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Movement;
use Illuminate\Database\Eloquent\Model;

class StockStatus extends Model
{
    protected $table = 'stock_status';

    protected $fillable = [
        'description',
        'in_out',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    // Relación con Product
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_status_stock', 'status_stock_id', 'product_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    // Relación con Movement
    public function movements()
    {
        return $this->hasMany(Movement::class, 'movement_status_id');
    }
}
