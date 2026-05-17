<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_number',
        'patient_id',
        'date',
        'subtotal',
        'total_amount',
        'status',
        'payment_method',
        'amount_paid',
        'change_amount'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function treatments()
    {
        return $this->hasMany(TransactionTreatment::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
