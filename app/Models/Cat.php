<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
    protected $fillable = [
        'breed_id',
        'cert_id',
        'father_id',
        'mother_id',
        'price',
        'name',
        'age',
        'color',
        'desc',
        'weight',
        'for_sale',
    ];
}