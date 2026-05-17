<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemBatch extends Model
{
    protected $fillable = ['item_id', 'batch_number', 'stock', 'expiry_date'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function mutations()
    {
        return $this->hasMany(StockMutation::class);
    }
}
