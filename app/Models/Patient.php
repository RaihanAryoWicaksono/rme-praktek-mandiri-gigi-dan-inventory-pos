<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_rm',
        'name',
        'nama_lengkap',
        'nik',
        'tanggal_lahir',
        'jenis_kelamin',
        'golongan_darah',
        'alamat',
        'phone',
        'email',
        'pekerjaan',
        'alergi',
        'fotos',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'fotos'         => 'array',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function kunjungans(): HasMany
    {
        return $this->hasMany(Kunjungan::class);
    }

    public function rekamMedis(): HasMany
    {
        return $this->hasMany(RekamMedis::class);
    }

    public function getUmurAttribute(): ?int
    {
        return $this->tanggal_lahir?->age;
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->nama_lengkap ?? $this->name;
    }

    public static function generateNoRM(): string
    {
        $prefix = 'RM-' . date('Ymd') . '-';
        $last = static::where('no_rm', 'like', $prefix . '%')
            ->orderByDesc('no_rm')
            ->value('no_rm');

        $seq = $last ? ((int) substr($last, -3)) + 1 : 1;

        return $prefix . str_pad($seq, 3, '0', STR_PAD_LEFT);
    }
}
