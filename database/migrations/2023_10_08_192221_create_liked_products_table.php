<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('liked_products', function (Blueprint $table) {
            $table->id();
            $table->integer('likes');
            $table->foreignId('product_id')->constrained(
                table: 'products', indexName: 'id'
            );
            $table->foreignId('user_id')->costrined(
                table: 'customers', indexName: 'id'
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liked_products');
    }
};