<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class UserResource extends JsonApiResource
{
    /**
     * @return array<string, mixed>
     */
    public function toAttributes(Request $request): array
    {
        return [
            'username' => $this->resource->username,
            'email' => $this->resource->email,
            'phone' => $this->resource->phone,
            'is_admin' => (bool) $this->resource->is_admin,
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
            'baskets',
            'orders',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function toLinks(Request $request): array
    {
        return [
            'self' => route('users.show', $this->resource),
        ];
    }
}
