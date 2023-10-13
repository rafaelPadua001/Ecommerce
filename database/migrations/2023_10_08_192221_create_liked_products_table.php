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
                table: 'products', indexName: 'likes_products_id'
            );
            $table->foreignId('user_id')->constrained(
                table: 'customers', indexName: 'likes_customers_id'
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
