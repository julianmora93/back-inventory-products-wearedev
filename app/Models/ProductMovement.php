<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Movement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductMovement extends Model
{
    protected $table = 'products_movements';

    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'movement_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function movement()
    {
        return $this->belongsTo(Movement::class);
    }
}
