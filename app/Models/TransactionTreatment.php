<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionTreatment extends Model
{
    protected $fillable = ['transaction_id', 'treatment_id', 'price'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }
}
