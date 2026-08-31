<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class UserResource extends JsonApiResource
{
    public function toAttributes(Request $request): array
    {
        $isAdmin = $request->user()?->isAdmin();
        return [
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_admin' => $this->when($isAdmin, $this->is_admin),
            'created_at' => $this->when($isAdmin, $this->created_at),
            'updated_at' => $this->when($isAdmin, $this->updated_at),
        ];
    }

    public function toRelationships(Request $request) {
        return [
            'baskets',
            'orders'
        ];
    }

    public function toLinks(Request $request): array {
        return [
            'self' => route('users.show', $this->resource)
        ];
    }
}
