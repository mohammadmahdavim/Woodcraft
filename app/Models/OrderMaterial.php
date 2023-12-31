<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderMaterial extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function material()
    {
        return $this->belongsTo(Material::class)->withDefault();
    }
}
