<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class OrderResource extends JsonApiResource
{
    /**
     * @return array<string, mixed>
     */
    public function toAttributes(Request $request): array
    {
        return [
            'province' => $this->resource->province,
            'regency' => $this->resource->regency,
            'district' => $this->resource->district,
            'village' => $this->resource->village,
            'address' => $this->resource->address,
            'status' => $this->resource->status,
            'total' => (float) $this->resource->total,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }

    /**
     * @return array<int, string>
     */
    public function toRelationships(Request $request): array
    {
        return [
            'user',
            'orderDetails',
            'transaction',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function toLinks(Request $request): array
    {
        return [
            'self' => route('orders.show', $this->resource),
        ];
    }
}
