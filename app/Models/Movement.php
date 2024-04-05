<?php

namespace App\Models;

use App\Models\StockStatus;
use App\Models\ProductMovement;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $fillable = [
        'movement_status_id',
        'date_movement'
    ];

    protected $dates = [
        'date_movement',
        'created_at',
        'updated_at',
    ];

    // Relación con ProductMovement
    public function productMovements()
    {
        return $this->hasMany(ProductMovement::class);
    }

    public function statusStock()
    {
        return $this->belongsTo(StockStatus::class, 'movement_status_id');
    }
}
