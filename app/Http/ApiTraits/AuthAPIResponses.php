<?php

namespace App\Http\ApiTraits;

trait AuthAPIResponses
{
    use ApiResponse;

    private function registerSuccessResponse() {
        return $this->generalResponse(true, "Registered", 201);
    }

    private function loginSuccessResponse(string $token, mixed $user) {
        return $this->generalResponse(true, "Logged in", 200, ["token" => $token, "user" => $user]);
    }

    private function logoutSuccessResponse() {
        return $this->generalResponse(true, "Logged out", 200);
    }

    private function registerFailedValidationResponse(mixed $errors) {
        return $this->generalResponse(false, "Invalid registration form", 400, $errors);
    }

    private function loginFailedValidationResponse(mixed $errors) {
        return $this->generalResponse(false, "Invalid login form", 400, $errors);
    }

    private function loginFailedAuthenticationResponse() {
        return $this->generalResponse(false, "Login failed, email or password invalid", 400);
    }

    private function logoutFailedResponse(string $errors) {
        return $this->generalResponse(false, "Unknown error occured during logout", 400, $errors);
    }
}
