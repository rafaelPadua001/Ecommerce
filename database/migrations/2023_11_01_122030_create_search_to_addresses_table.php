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
        Schema::create('search_to_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('postal_code');
            $table->string('type_postal_code');
            $table->string('uf');
            $table->string('cidade');
            $table->string('bairro');
            $table->string('endereco');
            $table->string('complemento');
            $table->string('codigoIBGE');
            $table->foreignId('user_id')->constrained('users')
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
        Schema::dropIfExists('search_to_addresses');
    }
};
