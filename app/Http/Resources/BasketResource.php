<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BasketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($request->user()->isAdmin()) {
            return [
                'user' => new UserResource($this->whenLoaded('owner')),
                'cat' => new CatResource($this->whenLoaded('cat')),
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ];
        }
        return [
            'cat' => new CatResource($this->whenLoaded('cat'))
        ];
    }
}
