<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

class TransactionResource extends JsonApiResource
{
    /**
     * @return array<string, mixed>
     */
    public function toAttributes(Request $request): array
    {
        return [
            'transaction_code' => $this->resource->transaction_code,
            'transaction_id' => $this->resource->transaction_id,
            'transaction_time' => $this->resource->transaction_time,
            'amount' => (float) $this->resource->amount,
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
            'order',
            'recorder',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function toLinks(Request $request): array
    {
        return [
            'self' => route('transactions.show', $this->resource),
        ];
    }
}
