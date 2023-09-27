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
        Schema::create('product_videos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('link');
            $table->string('plataform');
            $table->foreignId('user_id')->constrained(
                table: 'users', indexName: 'product_videos_user_id'
            )
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('product_id')->constrained(
                table: 'products', indexName: 'product_videos_product_id'
            )
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
        Schema::dropIfExists('product_videos');
    }
};
