<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable('order_id', 'cat_id', 'price')]
class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'cat_id',
        'price'
    ];

    public function order() {
        $this->belongsTo(Order::class);
    }
}
