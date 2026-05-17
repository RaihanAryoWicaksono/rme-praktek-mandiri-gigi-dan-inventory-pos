<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    protected $fillable = ['name', 'category', 'base_price', 'is_active'];

    public function bomTemplates()
    {
        return $this->hasMany(BomTemplate::class);
    }
}
