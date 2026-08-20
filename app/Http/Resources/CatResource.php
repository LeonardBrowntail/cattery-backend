<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $list = [
            'id' => $this->id,
            'father' => new CatResource($this->whenLoaded('father')),
            'mother' => new CatResource($this->whenLoaded('mother')),
            'birthdate' => $this->birthdate,
            'breed' => $this->breed,
            'sex' => $this->sex,
            'price' => (float) $this->price,
            'name' => $this->name,
            'color' => $this->color,
            'description' => $this->description,
            'status' => $this->status,
            'images' => CatImageResource::collection($this->whenLoaded('images')),
            'primary_image' => new CatImageResource($this->whenLoaded('primaryImage')),
        ];

        if ($request->user()->isAdmin()) {
            $list['created_at'] = $this->created_at;
            $list['updated_at'] = $this->updated_at;
        }

        return $list;
    }
}
