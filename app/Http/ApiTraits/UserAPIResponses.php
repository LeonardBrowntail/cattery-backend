<?php

namespace App\Http\ApiTraits;

use Illuminate\Http\JsonResponse;

trait UserAPIResponses
{
    use ApiResponse;

    private function userIndexSuccessResponse(mixed $data): JsonResponse
    {
        return $this->generalResponse(true, 'fetched all users', 200, $data);
    }

    private function userShowSuccessResponse(mixed $data): JsonResponse
    {
        return $this->generalResponse(true, 'fetched user', 200, $data);
    }

    private function userUpdateSuccessResponse(mixed $data): JsonResponse
    {
        return $this->generalResponse(true, 'updated user', 200, $data);
    }

    private function userUpdateInvalidResponse(mixed $data): JsonResponse
    {
        return $this->generalResponse(false, 'invalid field(s)', 422, $data);
    }

    private function userCreateSuccessResponse(mixed $data): JsonResponse
    {
        return $this->generalResponse(true, 'created user', 201, $data);
    }

    private function userCreateInvalidResponse(mixed $data): JsonResponse
    {
        return $this->generalResponse(false, 'invalid field(s)', 422, $data);
    }

    private function userDestroySuccessResponse(): JsonResponse
    {
        return $this->generalResponse(true, 'deleted user', 204);
    }

    private function userNotFoundResponse(): JsonResponse
    {
        return $this->generalResponse(false, 'user not found', 404);
    }
}
