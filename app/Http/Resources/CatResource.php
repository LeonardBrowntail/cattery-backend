<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class CatResource extends JsonApiResource
{
    public function toAttributes(Request $request): array
    {
        return [
            'father' => new CatResource($this->whenLoaded('father')),
            'mother' => new CatResource($this->whenLoaded('mother')),
            'name' => $this->name,
            'sex' => $this->sex,
            'color' => $this->color,
            'breed' => $this->breed,
            'birthdate' => $this->birthdate,
            'price' => (float) $this->price,
            'description' => $this->description,
            'status' => $this->status,
            'images' => CatImageResource::collection($this->whenLoaded('images')),
            'primary_image' => new CatImageResource($this->whenLoaded('primaryImage')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    public function toRelationships(Request $request) {
        return [
            'father',
            'mother',
            'childrenAsFather',
            'childrenAsMother',
            'primaryImage',
            'baskets',
            'orders'
        ];
    }

    public function toLinks(Request $request) {
        return [
            'self' => route('cats.show', $this->resource)
        ];
    }
}
