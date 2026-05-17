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
            $table->string('transaction_number')->unique();
            $table->foreignId('patient_id')->constrained()->restrictOnDelete();
            $table->date('date');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('payment_method')->default('cash');
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('change_amount', 15, 2)->default(0);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
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
