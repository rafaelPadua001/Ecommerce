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
        Schema::create('cart_items', function (Blueprint $table) {
           
                $table->id();
                $table->unsignedBigInteger('cart_id');
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('user_id');
                $table->integer('quantity');
                $table->json('color')->nullable();
                $table->text('size')->nullable();
                $table->decimal('price', 8, 2);
                $table->decimal('delivery_price');
                $table->decimal('total_price');
                $table->boolean('is_active')->default(true);
                $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
