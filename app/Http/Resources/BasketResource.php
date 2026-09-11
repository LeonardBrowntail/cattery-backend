<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class BasketResource extends JsonApiResource
{
    /**
     * @return array<string, mixed>
     */
    public function toAttributes(Request $request): array
    {
        $admin = $request->user()->isAdmin();

        return [
            'user' => $admin ? $this->resource->user_id : null,
            'cat' => new CatResource($this->whenLoaded('cat')),
            'created_at' => $admin ? $this->resource->created_at : null,
            'updated_at' => $admin ? $this->resource->updated_at : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toRelationships(Request $request): array
    {
        return [
            'owner' => UserResource::class,
            'cat' => CatResource::class,
        ];
    }
}
