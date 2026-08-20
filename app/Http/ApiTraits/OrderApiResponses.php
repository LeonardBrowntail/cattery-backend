<?php

namespace App\Http\ApiTraits;

use App\Http\ApiTraits\ApiResponse;

trait OrderAPIResponses
{
    use ApiResponse;

    private function orderIndexSuccessResponse(mixed $data) {
        return $this->generalResponse(true, "fetched orders", 200, $data);
    }

    private function orderShowSuccessResponse(mixed $data) {
        return $this->generalResponse(true, "fetched order", 200, $data);
    }

    private function orderCreateSuccessResponse(mixed $data) {
        return $this->generalResponse(true, "created new order entry", 201, $data);
    }

    private function orderUpdateSuccessResponse(mixed $data) {
        return $this->generalResponse(true, "updated order entry", 200, $data);
    }

    private function orderDestroySuccessResponse() {
        return $this->generalResponse(true, "deleted order entry", 204);
    }

    private function orderNotFoundResponse() {
        return $this->generalResponse(false, "cat order found", 404);
    }

    private function orderCreateFailedValidationResponse(mixed $errors) {
        return $this->generalResponse(false, "invalid field(s)", 400, $errors);
    }
}
