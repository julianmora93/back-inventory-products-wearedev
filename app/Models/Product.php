<?php

namespace App\Models;

use App\Models\StockStatus;
use App\Models\ProductMovement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'description',
        'quantity',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function movements()
    {
        return $this->hasMany(ProductMovement::class);
    }

    public function status()
    {
        return $this->belongsToMany(StockStatus::class, 'product_status_stock', 'product_id', 'status_stock_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
