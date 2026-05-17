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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['bahan', 'obat', 'produk']);
            $table->string('unit_use');
            $table->string('unit_purchase')->nullable();
            $table->integer('conversion_factor')->default(1);
            $table->integer('min_stock_alert')->default(0);
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->decimal('selling_price', 15, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
