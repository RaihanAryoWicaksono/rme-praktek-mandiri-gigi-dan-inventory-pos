<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kunjungans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->date('tanggal_kunjungan');
            $table->text('keluhan_utama');
            $table->text('anamnesis')->nullable();
            $table->string('tekanan_darah', 10)->nullable();
            $table->string('nadi', 10)->nullable();
            $table->enum('status', ['antrian', 'sedang_diperiksa', 'selesai'])->default('antrian');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungans');
    }
};
