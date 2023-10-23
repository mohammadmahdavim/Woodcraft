<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubInvoice extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded=[];

    public function invoice()
    {
        $this->belongsTo(Invoice::class)->withDefault();
    }

    public function product()
    {
        $this->belongsTo(Product::class)->withDefault();
    }
}
