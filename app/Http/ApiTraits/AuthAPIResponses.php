<?php

namespace App\Http\ApiTraits;

trait AuthAPIResponses
{
    use ApiResponse;

    private function registerSuccessResponse() {
        return $this->generalResponse(true, "registered", 201);
    }

    private function loginSuccessResponse(string $token, mixed $user) {
        return $this->generalResponse(true, "logged in", 200, ["token" => $token, "user" => $user]);
    }

    private function logoutSuccessResponse() {
        return $this->generalResponse(true, "logged out", 200);
    }

    private function registerInvalidResponse(mixed $errors) {
        return $this->generalResponse(false, "invalid registration form", 400, $errors);
    }

    private function loginInvalidResponse(mixed $errors) {
        return $this->generalResponse(false, "invalid login form", 400, $errors);
    }

    private function loginFailedResponse() {
        return $this->generalResponse(false, "login failed, email or password invalid", 400);
    }

    private function logoutFailedResponse(string $errors) {
        return $this->generalResponse(false, "unknown error occured during logout", 400, $errors);
    }
}
