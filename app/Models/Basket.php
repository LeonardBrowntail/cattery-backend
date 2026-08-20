<?php

namespace App\Models;

use App\Policies\BasketPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;

#[UsePolicy(BasketPolicy::class)]
class Basket extends Model
{
    protected $table = 'baskets';
    
    protected $fillable = [
        'user_id',
        'cat_id'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cat() {
        return $this->belongsTo(Cat::class, 'cat_id');
    }
}
