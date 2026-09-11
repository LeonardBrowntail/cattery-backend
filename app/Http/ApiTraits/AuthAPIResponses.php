<?php

namespace App\Http\ApiTraits;

use Illuminate\Http\JsonResponse;

trait AuthAPIResponses
{
    use ApiResponse;

    private function registerSuccessResponse(): JsonResponse
    {
        return $this->generalResponse(true, 'registered', 201);
    }

    private function loginSuccessResponse(string $token, mixed $user): JsonResponse
    {
        return $this->generalResponse(true, 'logged in', 200, ['token' => $token, 'user' => $user]);
    }

    private function logoutSuccessResponse(): JsonResponse
    {
        return $this->generalResponse(true, 'logged out', 200);
    }

    private function registerInvalidResponse(mixed $errors): JsonResponse
    {
        return $this->generalResponse(false, 'invalid registration form', 422, $errors);
    }

    private function loginInvalidResponse(mixed $errors): JsonResponse
    {
        return $this->generalResponse(false, 'invalid login form', 422, $errors);
    }

    private function loginFailedResponse(): JsonResponse
    {
        return $this->generalResponse(false, 'login failed, email or password invalid', 401);
    }

    private function logoutFailedResponse(string $errors): JsonResponse
    {
        return $this->generalResponse(false, 'unknown error occured during logout', 400, $errors);
    }
}
