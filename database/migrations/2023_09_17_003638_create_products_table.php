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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->json('images')->nullable();
            $table->string('platform');
            $table->json('video_links')->nullable();
            $table->json('colors')->nullable();
            $table->json('size')->nullable();
            $table->string('unity');
            $table->decimal('price', 10, 2);
            $table->integer('stock_quantity');
            $table->string('sku')->unique()->nullable();
            $table->string('manufacturer')->nulllable();
            $table->decimal('weight', 8, 2);
            $table->decimal('height', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('length', 8, 2)->nullable();
            $table->boolean('availability')->default(true);
            $table->string('slug')->unique()->nullable(); 
            $table->string('status')->default('active');
            $table->boolean('highlight')->default(false); // Define o valor padrão conforme necessário
            $table->foreignId('user_id')->constrained(
                table: 'users', indexName: 'product_user_id'
            )
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('category_id')->constrained(
                table: 'categories', indexName: 'product_category_id'
            )
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('subcategory_id')->constrained(
                table: 'subcategories', indexName:'product_subcategories_id'
            )
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->foreign('discount_id')->references('id')->on('coupons');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
