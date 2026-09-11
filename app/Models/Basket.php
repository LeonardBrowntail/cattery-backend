<?php

namespace App\Models;

use App\Policies\BasketPolicy;
use Carbon\CarbonImmutable;
use Database\Factories\BasketFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $cat_id
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Cat $cat
 * @property-read User $owner
 *
 * @method static \Database\Factories\BasketFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Basket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Basket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Basket query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Basket whereCatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Basket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Basket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Basket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Basket whereUserId($value)
 *
 * @mixin \Eloquent
 */
#[UseFactory(BasketFactory::class)]
#[UsePolicy(BasketPolicy::class)]
class Basket extends Model
{
    /** @use HasFactory<BasketFactory> */
    use HasFactory;

    protected $table = 'baskets';

    protected $fillable = [
        'user_id',
        'cat_id',
    ];

    /** @return BelongsTo<User, $this> */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** @return BelongsTo<Cat, $this> */
    public function cat(): BelongsTo
    {
        return $this->belongsTo(Cat::class, 'cat_id');
    }
}
