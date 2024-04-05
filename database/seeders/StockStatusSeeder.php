<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockStatus;

class StockStatusSeeder extends Seeder
{
    public function run()
    {

        StockStatus::create([
            'description' => 'Entrada',
            'in_out' => true,
        ]);

        StockStatus::create([
            'description' => 'Salida',
            'in_out' => false,
        ]);

    }
}
