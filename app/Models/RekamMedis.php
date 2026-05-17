<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekamMedis extends Model
{
    use HasFactory;

    protected $table = 'rekam_medis';

    protected $fillable = [
        'kunjungan_id',
        'patient_id',
        'diagnosis',
        'tindakan',
        'resep',
        'catatan',
        'odontogram_data',
        'foto',
    ];

    protected $casts = [
        'odontogram_data' => 'array',
    ];

    public function kunjungan(): BelongsTo
    {
        return $this->belongsTo(Kunjungan::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
