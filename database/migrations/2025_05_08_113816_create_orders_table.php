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
            $table->string('cliente_nome');
            $table->string('status')->default('pendente');
        
            // Endereço
            $table->string('endereco_rua');
            $table->string('endereco_numero');
            $table->string('endereco_bairro')->nullable();
            $table->string('endereco_cidade');
            $table->string('endereco_estado');
            $table->string('endereco_cep');
        
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
