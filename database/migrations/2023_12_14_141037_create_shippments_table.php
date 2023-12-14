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
        Schema::create('shippments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company');
            $table->double('price', 10, 2);
            $table->integer('deadline');
            $table->string('product_id');
            $table->integer('quantity');
            $table->foreignId('user_id')->constrained('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('cart_id')->constrained('carts')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('cart_item_id')->constrained('cart_items')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('delivery_id')->constrained('deliveries')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippments');
    }
};
