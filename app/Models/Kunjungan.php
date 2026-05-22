<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Kunjungan extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'tanggal_kunjungan',
        'keluhan_utama',
        'anamnesis',
        'tekanan_darah',
        'nadi',
        'status',
    ];

    protected $appends = ['label_status', 'badge_status'];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function rekamMedis(): HasOne
    {
        return $this->hasOne(RekamMedis::class);
    }

    public function getLabelStatusAttribute(): string
    {
        return match ($this->status) {
            'antrian'          => 'Antrian',
            'sedang_diperiksa' => 'Sedang Diperiksa',
            'selesai'          => 'Selesai',
            default            => $this->status,
        };
    }

    public function getBadgeStatusAttribute(): string
    {
        return match ($this->status) {
            'antrian'          => 'bg-yellow-100 text-yellow-800',
            'sedang_diperiksa' => 'bg-blue-100 text-blue-800',
            'selesai'          => 'bg-green-100 text-green-800',
            default            => 'bg-gray-100 text-gray-800',
        };
    }
}
