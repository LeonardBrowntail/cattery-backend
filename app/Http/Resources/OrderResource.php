<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class OrderResource extends JsonApiResource
{
    /**
     * The resource's attributes.
     */
    public $attributes = [
        'province',
        'regency',
        'district',
        'village',
        'address',
        'status'
    ];

    /**
     * The resource's relationships.
     */
    public $relationships = [
        'order_details'
    ];
}
