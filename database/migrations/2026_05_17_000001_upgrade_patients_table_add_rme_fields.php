<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('no_rm', 20)->unique()->nullable()->after('id');
            $table->string('nama_lengkap', 100)->nullable()->after('no_rm');
            $table->string('nik', 16)->nullable()->after('nama_lengkap');
            $table->date('tanggal_lahir')->nullable()->after('nik');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('tanggal_lahir');
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O', '-'])->default('-')->after('jenis_kelamin');
            $table->text('alamat')->nullable()->after('golongan_darah');
            $table->string('email', 100)->nullable()->after('phone');
            $table->string('pekerjaan', 50)->nullable()->after('email');
            $table->text('alergi')->nullable()->after('pekerjaan');
            $table->json('fotos')->nullable()->after('alergi');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'no_rm', 'nama_lengkap', 'nik', 'tanggal_lahir',
                'jenis_kelamin', 'golongan_darah', 'alamat',
                'email', 'pekerjaan', 'alergi', 'fotos',
            ]);
        });
    }
};
