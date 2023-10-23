<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function user()
    {
        $this->belongsTo(User::class, 'author')->withDefault();
    }

    public function materials()
    {
        return $this->hasMany(ProductMaterial::class);
    }
}
