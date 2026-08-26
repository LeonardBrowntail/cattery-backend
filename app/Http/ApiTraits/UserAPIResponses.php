<?php

namespace App\Http\ApiTraits;

trait UserAPIResponses
{
    use ApiResponse;

    private function userIndexSuccessResponse(mixed $data) {
        return $this->generalResponse(true, "fethced all users", 200, $data);
    }

    private function userShowSuccessResponse(mixed $data) {
        return $this->generalResponse(true, "fetched user", 200, $data);
    }

    private function userUpdateSuccessResponse(mixed $data) {
        return $this->generalResponse(true, "updated user", 200, $data);
    }

    private function userUpdateInvalidResponse(mixed $data) {
        return $this->generalResponse(false, "invalid field(s)", 400, $data);
    }

    private function userDestroySuccessResponse() {
        return $this->generalResponse(true, "deleted user", 200);
    }

    private function userNotFoundResponse() {
        return $this->generalResponse(false, "user not found", 404);
    }
}