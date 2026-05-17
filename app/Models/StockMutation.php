<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMutation extends Model
{
    protected $fillable = [
        'item_id', 'item_batch_id', 'type', 'quantity', 
        'reason', 'reference_transaction_id'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function batch()
    {
        return $this->belongsTo(ItemBatch::class, 'item_batch_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'reference_transaction_id');
    }
}
