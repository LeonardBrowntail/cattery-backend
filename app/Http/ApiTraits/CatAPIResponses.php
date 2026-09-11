<?php

namespace App\Http\ApiTraits;

use Illuminate\Http\JsonResponse;

trait CatAPIResponses
{
    use ApiResponse;

    private function catIndexSuccessResponse(mixed $data): JsonResponse
    {
        return $this->generalResponse(true, 'fetched cats', 200, $data);
    }

    private function catShowSuccessResponse(mixed $data): JsonResponse
    {
        return $this->generalResponse(true, 'fetched cat', 200, $data);
    }

    private function catCreateSuccessResponse(mixed $data): JsonResponse
    {
        return $this->generalResponse(true, 'created new cat entry', 201, $data);
    }

    private function catUpdateSuccessResponse(mixed $data): JsonResponse
    {
        return $this->generalResponse(true, 'updated cat entry', 200, $data);
    }

    private function catDestroySuccessResponse(): JsonResponse
    {
        return $this->generalResponse(true, 'deleted cat', 200);
    }

    private function catNotFoundResponse(): JsonResponse
    {
        return $this->generalResponse(false, 'cat not found', 404);
    }

    private function catInvalidResponse(mixed $errors): JsonResponse
    {
        return $this->generalResponse(false, 'invalid field(s)', 400, $errors);
    }
}
