<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class CatResource extends JsonApiResource
{
    /**
     * @return array<string, mixed>
     */
    public function toAttributes(Request $request): array
    {
        return [
            'father' => new CatResource($this->whenLoaded('father')),
            'mother' => new CatResource($this->whenLoaded('mother')),
            'name' => $this->resource->name,
            'sex' => $this->resource->sex,
            'color' => $this->resource->color,
            'breed' => $this->resource->breed,
            'birthdate' => $this->resource->birthdate,
            'price' => (float) $this->resource->price,
            'description' => $this->resource->description,
            'status' => $this->resource->status,
            'images' => CatImageResource::collection($this->whenLoaded('images')),
            'primary_image' => new CatImageResource($this->whenLoaded('primaryImage')),
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
            'father',
            'mother',
            'childrenAsFather',
            'childrenAsMother',
            'primaryImage',
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
            'self' => route('cats.show', $this->resource),
        ];
    }
}
