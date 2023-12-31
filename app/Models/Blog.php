<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded=[];
    public function user()
    {
        $this->belongsTo(User::class)->withDefault();
    }

    /**
     * Get all of the post's comments.
     */
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
