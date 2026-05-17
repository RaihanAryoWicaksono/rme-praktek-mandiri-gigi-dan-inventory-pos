<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BomTemplate extends Model
{
    protected $fillable = ['treatment_id', 'name'];

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    public function items()
    {
        return $this->hasMany(BomTemplateItem::class);
    }
}
