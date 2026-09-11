<?php

namespace App\Models;

use App\Policies\CatPolicy;
use Carbon\CarbonImmutable;
use Database\Factories\CatFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int|null $father_id
 * @property int|null $mother_id
 * @property CarbonImmutable $birthdate
 * @property string $breed
 * @property string $sex
 * @property numeric $price
 * @property string $color
 * @property string $name
 * @property string $description
 * @property numeric $weight
 * @property string $status
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Collection<int, Basket> $baskets
 * @property-read int|null $baskets_count
 * @property-read Collection<int, Cat> $childrenAsFather
 * @property-read int|null $children_as_father_count
 * @property-read Collection<int, Cat> $childrenAsMother
 * @property-read int|null $children_as_mother_count
 * @property-read Cat|null $father
 * @property-read Collection<int, CatImage> $images
 * @property-read int|null $images_count
 * @property-read Cat|null $mother
 * @property-read Collection<int, OrderDetail> $orders
 * @property-read int|null $orders_count
 * @property-read CatImage|null $primaryImage
 *
 * @method static \Database\Factories\CatFactory factory($count = null, $state = [])
 * @method static Builder<static>|Cat newModelQuery()
 * @method static Builder<static>|Cat newQuery()
 * @method static Builder<static>|Cat priceBetween(?float $minPrice, ?float $maxPrice)
 * @method static Builder<static>|Cat query()
 * @method static Builder<static>|Cat search(?string $keyword)
 * @method static Builder<static>|Cat sex(?string $sex)
 * @method static Builder<static>|Cat status(array<int, string>|string|null $status)
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
 *
 * @mixin \Eloquent
 */
#[UseFactory(CatFactory::class)]
#[UsePolicy(CatPolicy::class)]
class Cat extends Model
{
    /** @use HasFactory<CatFactory> */
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

    /** @return HasMany<Basket, $this> */
    public function baskets(): HasMany
    {
        return $this->hasMany(Basket::class);
    }

    /** @return HasMany<OrderDetail, $this> */
    public function orders(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    /** @return BelongsTo<Cat, $this> */
    public function father(): BelongsTo
    {
        return $this->belongsTo(Cat::class);
    }

    /** @return BelongsTo<Cat, $this> */
    public function mother(): BelongsTo
    {
        return $this->belongsTo(Cat::class);
    }

    /** @return HasMany<Cat, $this> */
    public function childrenAsFather(): HasMany
    {
        return $this->hasMany(Cat::class, 'father_id');
    }

    /** @return HasMany<Cat, $this> */
    public function childrenAsMother(): HasMany
    {
        return $this->hasMany(Cat::class, 'mother_id');
    }

    /** @return HasMany<CatImage, $this> */
    public function images(): HasMany
    {
        return $this->hasMany(CatImage::class);
    }

    /** @return HasOne<CatImage, $this> */
    public function primaryImage(): HasOne
    {
        return $this->hasOne(CatImage::class)->where('is_primary', true);
    }

    // Query scopes

    /**
     * @param  Builder<Cat>  $query
     * @return Builder<Cat>
     */
    public function scopeSex(Builder $query, ?string $sex): Builder
    {
        return $sex ? $query->where('sex', $sex) : $query;
    }

    /**
     * @param  Builder<Cat>  $query
     * @param  array<int, string>|string|null  $status
     * @return Builder<Cat>
     */
    public function scopeStatus(Builder $query, array|string|null $status): Builder
    {
        if ($status === null) {
            return $query;
        }

        return $query->whereIn('status', (array) $status);
    }

    /**
     * @param  Builder<Cat>  $query
     * @return Builder<Cat>
     */
    public function scopePriceBetween(Builder $query, ?float $minPrice, ?float $maxPrice): Builder
    {
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }

        return $query;
    }

    /**
     * @param  Builder<Cat>  $query
     * @return Builder<Cat>
     */
    public function scopeSearch(Builder $query, ?string $keyword): Builder
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
