<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BomTemplateItem extends Model
{
    protected $fillable = ['bom_template_id', 'item_id', 'quantity', 'unit'];

    public function bomTemplate()
    {
        return $this->belongsTo(BomTemplate::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
