<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\OrderDetailFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $order_id
 * @property int $cat_id
 * @property numeric $price
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 *
 * @method static \Database\Factories\OrderDetailFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereCatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class OrderDetail extends Model
{
    /** @use HasFactory<OrderDetailFactory> */
    use HasFactory;

    protected $fillable = [
        'order_id',
        'cat_id',
        'price',
    ];

    /** @return BelongsTo<Order, $this> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** @return BelongsTo<Cat, $this> */
    public function cat(): BelongsTo
    {
        return $this->belongsTo(Cat::class);
    }
}
