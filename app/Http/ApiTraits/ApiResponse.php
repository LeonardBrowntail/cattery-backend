<?php

namespace App\Http\ApiTraits;

trait ApiResponse
{
    private function generalResponse(bool $status, string $message, int $code, mixed $data = null) {
        $json = [
            'status' => $status,
            'message' => $message
        ];
        if ($data) {
            if ($status) {
                $json['data'] = $data;
            } else {
                $json['errors'] = $data;
            }
        }
        return response()->json($json, $code);
    }
}
