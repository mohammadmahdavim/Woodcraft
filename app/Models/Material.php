<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function unit()
    {
        return $this->belongsTo(Unit::class)->withDefault();
    }

    public function type()
    {
        return $this->belongsTo(Type::class)->withDefault();
    }
}
