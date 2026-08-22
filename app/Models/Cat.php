<?php

namespace App\Models;

use Database\Factories\CatFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $father_id
 * @property int|null $mother_id
 * @property \Carbon\CarbonImmutable $birthdate
 * @property string $breed
 * @property string $sex
 * @property numeric $price
 * @property string $color
 * @property string $name
 * @property string $description
 * @property numeric $weight
 * @property string $status
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Basket> $baskets
 * @property-read int|null $baskets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Cat> $childrenAsFather
 * @property-read int|null $children_as_father_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Cat> $childrenAsMother
 * @property-read int|null $children_as_mother_count
 * @property-read Cat|null $father
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CatImage> $images
 * @property-read int|null $images_count
 * @property-read Cat|null $mother
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderDetail> $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\CatImage|null $primaryImage
 * @method static Builder<static>|Cat breed(?string $breed)
 * @method static \Database\Factories\CatFactory factory($count = null, $state = [])
 * @method static Builder<static>|Cat newModelQuery()
 * @method static Builder<static>|Cat newQuery()
 * @method static Builder<static>|Cat priceBetween(?float $minPrice, ?float $maxPrice)
 * @method static Builder<static>|Cat query()
 * @method static Builder<static>|Cat search(?string $keyword)
 * @method static Builder<static>|Cat sex(?string $sex)
 * @method static Builder<static>|Cat status(?string $status)
 * @method static Builder<static>|Cat whereBirthdate($value)
 * @method static Builder<static>|Cat whereBreed($value)
 * @method static Builder<static>|Cat whereColor($value)
 * @method static Builder<static>|Cat whereCreatedAt($value)
 * @method static Builder<static>|Cat whereDescription($value)
 * @method static Builder<static>|Cat whereFatherId($value)
 * @method static Builder<static>|Cat whereId($value)
 * @method static Builder<static>|Cat whereMotherId($value)
 * @method static Builder<static>|Cat whereName($value)
 * @method static Builder<static>|Cat wherePrice($value)
 * @method static Builder<static>|Cat whereSex($value)
 * @method static Builder<static>|Cat whereStatus($value)
 * @method static Builder<static>|Cat whereUpdatedAt($value)
 * @method static Builder<static>|Cat whereWeight($value)
 * @mixin \Eloquent
 */
#[UseFactory(CatFactory::class)]
#[UsePolicy(CatPolicy::class)]
class Cat extends Model
{
    use HasFactory;

    protected $table = 'cats';

    protected $fillable = [
        'father_id',
        'mother_id',
        'birthdate',
        'name',
        'breed',
        'sex',
        'color',
        'price',
        'description',
        'status',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'price' => 'decimal:2',
    ];

    public function baskets()
    {
        return $this->hasMany(Basket::class);
    }

    public function orders()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function father()
    {
        return $this->belongsTo(Cat::class);
    }

    public function mother()
    {
        return $this->belongsTo(Cat::class);
    }

    public function childrenAsFather()
    {
        return $this->hasMany(Cat::class, 'father_id');
    }

    public function childrenAsMother()
    {
        return $this->hasMany(Cat::class, 'mother_id');
    }

    public function images()
    {
        return $this->hasMany(CatImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(CatImage::class)->where('is_primary', true);
    }

    // Query scopes

    public function scopeBreed(Builder $query, ?string $breed)
    {
        return $breed ? $query->where('breed', $breed) : $query;
    }

    public function scopeSex(Builder $query, ?string $sex)
    {
        return $sex ? $query->where('sex', $sex) : $query;
    }

    public function scopeStatus(Builder $query, ?string $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function scopePriceBetween(Builder $query, ?float $minPrice, ?float $maxPrice)
    {
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }

        return $query;
    }

    public function scopeSearch(Builder $query, ?string $keyword)
    {
        if (! $keyword) {
            return $query;
        }

        $like = '%'.str_replace(['%', '_'], ['\%', '\_'], $keyword).'%';

        return $query->where(function (Builder $query) use ($like) {
            $query->where('name', 'like', $like)
                ->orWhere('breed', 'like', $like)
                ->orWhere('color', 'like', $like)
                ->orWhere('description', 'like', $like);
        });
    }
}
