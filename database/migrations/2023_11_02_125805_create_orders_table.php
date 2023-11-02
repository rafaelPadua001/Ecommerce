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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('client');
            $table->string('description');
            $table->date('exec_date')->default('2023-11-02');
            $table->decimal('value', 10, 2);
            $table->enum('status', ['open', 'close']);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cart_item_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('cart_item_id')->references('id')->on('cart_items');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
