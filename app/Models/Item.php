<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name', 'type', 'unit_use', 'unit_purchase', 
        'conversion_factor', 'min_stock_alert', 'purchase_price', 'selling_price', 'is_active'
    ];

    public function batches()
    {
        return $this->hasMany(ItemBatch::class);
    }

    public function mutations()
    {
        return $this->hasMany(StockMutation::class);
    }

    public function currentStock()
    {
        return $this->batches()->sum('stock');
    }
}
