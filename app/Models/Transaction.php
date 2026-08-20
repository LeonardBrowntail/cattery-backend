<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'order_id',
        'method',
        'code',
        'date',
        'total',
    ];

    public function order() {
        $this->hasOne(Order::class);
    }

    public function scopeMethod(Builder $query, ?string $method) {
        return $method ? $query->where('method', $method) : $query;
    }

    public function scope
}
