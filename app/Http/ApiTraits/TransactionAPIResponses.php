<?php

namespace App\Http\ApiTraits;

use Illuminate\Http\JsonResponse;

trait TransactionAPIResponses
{
    use ApiResponse;

    private function transactionIndexSuccessResponse(mixed $data): JsonResponse
    {
        return $this->generalResponse(true, 'fetched transactions', 200, $data);
    }

    private function transactionShowSuccessResponse(mixed $data): JsonResponse
    {
        return $this->generalResponse(true, 'fetched transaction', 200, $data);
    }

    private function transactionCreateSuccessResponse(mixed $data): JsonResponse
    {
        return $this->generalResponse(true, 'recorded transaction', 201, $data);
    }

    private function transactionDestroySuccessResponse(): JsonResponse
    {
        return $this->generalResponse(true, 'deleted transaction entry', 204);
    }

    private function transactionNotFoundResponse(): JsonResponse
    {
        return $this->generalResponse(false, 'transaction not found', 404);
    }

    private function transactionCreateFailedResponse(mixed $errors): JsonResponse
    {
        return $this->generalResponse(false, 'unable to record transaction', 422, $errors);
    }
}
