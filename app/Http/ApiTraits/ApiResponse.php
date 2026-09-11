<?php

namespace App\Http\ApiTraits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    /**
     * Create a general response format.
     *
     * The payload key name will automatically be set to `data` or `errors` depending on the status provided.
     * When the payload is a paginated resource collection, `meta` and `links` are also emitted so clients
     * can render page counts and "has more" controls.
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
            if ($payload instanceof ResourceCollection && $payload->resource instanceof LengthAwarePaginator) {
                $json['data'] = $payload;
                $json['meta'] = $this->paginationMeta($payload->resource);
                $json['links'] = $this->paginationLinks($payload->resource);
            } elseif ($status) {
                $json['data'] = $payload;
            } else {
                $json['errors'] = $payload;
            }
        }

        return response()->json($json, $code);
    }

    /**
     * @template TKey of array-key
     * @template TValue
     *
     * @param  LengthAwarePaginator<TKey, TValue>  $paginator
     * @return array{current_page: int, last_page: int, per_page: int, total: int}
     */
    private function paginationMeta(LengthAwarePaginator $paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
        ];
    }

    /**
     * @template TKey of array-key
     * @template TValue
     *
     * @param  LengthAwarePaginator<TKey, TValue>  $paginator
     * @return array{first: string, last: string, prev: string|null, next: string|null}
     */
    private function paginationLinks(LengthAwarePaginator $paginator): array
    {
        return [
            'first' => $paginator->url(1),
            'last' => $paginator->url($paginator->lastPage()),
            'prev' => $paginator->previousPageUrl(),
            'next' => $paginator->nextPageUrl(),
        ];
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
