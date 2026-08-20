<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CatImage extends Model
{
    protected $fillable = [
        'cat_id',
        'path',
        'is_primary'
    ];

    protected $appends = ['url'];

    public function cat() {
        return $this->belongsTo(Cat::class);
    }

    public function getUrlAttribute() {
        return Storage::disk('public')->path($this->path);
    }
}
