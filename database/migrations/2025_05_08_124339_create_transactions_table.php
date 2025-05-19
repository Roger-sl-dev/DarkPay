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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_id')->unique(); // ID da transação no Stripe
            $table->decimal('amount', 10, 2);       // Valor da transação
            $table->string('currency', 3);          // Ex: 'brl'
            $table->string('status');               // Ex: 'requires_payment_method', 'succeeded'
            $table->string('payment_method');       // Ex: 'pix', 'card'
            $table->string('description')->nullable(); // Descrição opcional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
