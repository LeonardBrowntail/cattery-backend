<?php

namespace App\Http\ApiTraits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Create a general response format.
     *
     * The payload key name will automatically be set to `data` or `errors` depending on the status provided.
     *
     * @param  bool  $status  Should be `true` if the response is a success, `false` otherwise. Affects the key name of the response payload.
     * @param  string  $message  A message to be sent with the response.
     * @param  int  $code  The HTTP status code to be sent with the response.
     * @param  mixed  $payload  The data or errors to be sent with the response, if any.
     */
    private function generalResponse(bool $status, string $message, int $code, mixed $payload = null): JsonResponse
    {
        $json = [
            'status' => $status,
            'message' => $message,
        ];
        if ($payload !== null) {
            if ($status) {
                $json['data'] = $payload;
            } else {
                $json['errors'] = $payload;
            }
        }

        return response()->json($json, $code);
    }

    /**
     * Generic forbidden (403) response.
     */
    private function forbiddenResponse(string $message = 'Unauthorized action'): JsonResponse
    {
        return $this->generalResponse(false, $message, 403);
    }

    /**
     * Generic not found (404) response.
     */
    private function notFoundResponse(string $resource = 'Resource'): JsonResponse
    {
        return $this->generalResponse(false, "$resource not found", 404);
    }
}
