<?php

namespace App\Http\ApiTraits;

trait UserAPIResponses
{
    use ApiResponse;

    private function userIndexSuccessResponse(mixed $data) {
        return $this->generalResponse(true, "Berhasil mendapatkan list 'user'", 200, $data);
    }

    private function userShowSuccessResponse(mixed $data) {
        return $this->generalResponse(true, "Berhasil mendapatkan data 'user'", 200, $data);
    }

    private function userNotFoundResponse() {
        return $this->generalResponse(false, "User tidak ditemukan", 404);
    }
}