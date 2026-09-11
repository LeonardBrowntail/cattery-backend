<?php

namespace App\Http\ApiTraits;

use Illuminate\Http\JsonResponse;

trait BasketAPIResponses
{
    use ApiResponse;

    protected function basketAdminIndexSuccessResponse(mixed $data): JsonResponse
    {
        return $this->generalResponse(true, 'Successfully fetched all baskets', 200, $data);
    }

    protected function basketUserIndexSuccessResponse(mixed $data): JsonResponse
    {
        return $this->generalResponse(true, 'Successfully fetched basket', 200, $data);
    }

    protected function basketCreateSuccessResponse(int $catId): JsonResponse
    {
        return $this->generalResponse(true, "Successfully added cat (id:{$catId}) into basket.", 201);
    }

    protected function basketUpdateSuccessResponse(int $catId): JsonResponse
    {
        return $this->generalResponse(true, "Successfully updated basket with cat (id:{$catId}).", 200);
    }

    protected function basketCreateFailedResponse(int $catId, string $reason = 'already in basket'): JsonResponse
    {
        return $this->generalResponse(false, "Unable to add cat (id:{$catId}) into basket.", 422, ['cat_id' => ["Cat (id:{$catId}) is {$reason}."]]);
    }

    protected function basketInvalidResponse(mixed $error): JsonResponse
    {
        return $this->generalResponse(false, 'Invalid field(s).', 422, $error);
    }

    protected function basketNotFoundResponse(): JsonResponse
    {
        return $this->generalResponse(false, 'basket not found', 404);
    }

    protected function basketDestroySuccessResponse(): JsonResponse
    {
        return $this->generalResponse(true, 'deleted basket entry', 204);
    }
}
