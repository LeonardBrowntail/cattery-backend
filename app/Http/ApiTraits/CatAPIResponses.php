<?php

namespace App\Http\ApiTraits;

use App\Http\ApiTraits\ApiResponse;

trait CatAPIResponses
{
    use ApiResponse;

    private function catIndexSuccessResponse(mixed $data) {
        return $this->generalResponse(true, "fetched cats", 200, $data);
    }

    private function catShowSuccessResponse(mixed $data) {
        return $this->generalResponse(true, "fetched cat", 200, $data);
    }

    private function catCreateSuccessResponse(mixed $data) {
        return $this->generalResponse(true, "created new cat entry", 201, $data);
    }

    private function catUpdateSuccessResponse(mixed $data) {
        return $this->generalResponse(true, "updated cat entry", 200, $data);
    }

    private function catDestroySuccessResponse() {
        return $this->generalResponse(true, "deleted cat entry", 204);
    }

    private function catNotFoundResponse() {
        return $this->generalResponse(false, "cat not found", 404);
    }

    private function catCreateFailedValidationResponse(mixed $errors) {
        return $this->generalResponse(false, "invalid field(s)", 400, $errors);
    }
}
