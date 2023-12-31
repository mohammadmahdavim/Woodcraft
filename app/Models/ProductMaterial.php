<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductMaterial extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function material()
    {
        return $this->belongsTo(Material::class)->withDefault();;
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault();;
    }
}
