<?php

namespace App\Models;

use Database\Factories\CatImageFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $cat_id
 * @property string $path
 * @property int $is_primary
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Cat $cat
 * @property-read mixed $url
 * @method static \Database\Factories\CatImageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatImage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatImage whereCatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatImage whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatImage wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */

#[UseFactory(CatImageFactory::class)]
class CatImage extends Model
{
    use HasFactory;

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
