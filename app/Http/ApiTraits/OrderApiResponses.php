<?php

namespace App\Http\ApiTraits;

use Illuminate\Http\JsonResponse;

trait OrderAPIResponses
{
    use ApiResponse;

    private function orderIndexSuccessResponse(mixed $data): JsonResponse
    {
        return $this->generalResponse(true, 'fetched orders', 200, $data);
    }

    private function orderShowSuccessResponse(mixed $data): JsonResponse
    {
        return $this->generalResponse(true, 'fetched order', 200, $data);
    }

    private function orderCreateSuccessResponse(mixed $data): JsonResponse
    {
        return $this->generalResponse(true, 'created new order entry', 201, $data);
    }

    private function orderUpdateSuccessResponse(mixed $data): JsonResponse
    {
        return $this->generalResponse(true, 'updated order entry', 200, $data);
    }

    private function orderDestroySuccessResponse(): JsonResponse
    {
        return $this->generalResponse(true, 'deleted order entry', 200);
    }

    private function orderNotFoundResponse(): JsonResponse
    {
        return $this->generalResponse(false, 'order not found', 404);
    }

    private function orderCreateFailedResponse(mixed $errors): JsonResponse
    {
        return $this->generalResponse(false, 'unable to create order', 422, $errors);
    }

    private function orderUpdateInvalidResponse(mixed $errors): JsonResponse
    {
        return $this->generalResponse(false, 'invalid field(s)', 400, $errors);
    }
}
