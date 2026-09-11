<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $order_id
 * @property string $transaction_code
 * @property string $transaction_id
 * @property CarbonImmutable|null $transaction_time
 * @property numeric $amount
 * @property int|null $recorded_by
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Order $order
 * @property-read User|null $recorder
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction query()
 *
 * @mixin \Eloquent
 */
class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'order_id',
        'transaction_code',
        'transaction_id',
        'transaction_time',
        'amount',
        'recorded_by',
    ];

    protected $casts = [
        'transaction_time' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /** @return BelongsTo<Order, $this> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** @return BelongsTo<User, $this> */
    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
