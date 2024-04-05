<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTables extends Migration
{
    public function up()
    {
        Schema::table('products_movements', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('movement_id')->references('id')->on('movements')->onDelete('cascade');
        });

        Schema::table('movements', function (Blueprint $table) {
            $table->foreign('movement_status_id')->references('id')->on('stock_status')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('products_movements', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['movement_id']);
        });

        Schema::table('movements', function (Blueprint $table) {
            $table->dropForeign(['movement_status_id']);
        });
    }
}
