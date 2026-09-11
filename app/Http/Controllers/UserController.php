<?php

namespace App\Http\Controllers;

use App\Http\ApiTraits\UserAPIResponses;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use UserAPIResponses;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        if (! $request->user()->isAdmin()) {
            return $this->forbiddenResponse();
        }

        $users = User::query()
            ->search($request->query('search'))
            ->latest()
            ->paginate($request->query('perPage', 10))
            ->withQueryString();

        return $this->userIndexSuccessResponse($users->toResourceCollection());
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return $this->userNotFoundResponse();
        }

        if (! $request->user()->isAdmin() && $request->user()->id !== $user->id) {
            return $this->forbiddenResponse();
        }

        return $this->userShowSuccessResponse($user->toResource());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return $this->userUpdateInvalidResponse($request->validator->errors());
        }

        $user = User::find($id);

        if (! $user) {
            return $this->userNotFoundResponse();
        }

        if (! $request->user()->isAdmin() && $request->user()->id !== $user->id) {
            return $this->forbiddenResponse();
        }

        DB::transaction(function () use ($user, $request) {
            $user->update($request->safe()->toArray());
        });

        return $this->userUpdateSuccessResponse($user->toResource());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        if (! $request->user()->isAdmin() && $request->user()->id !== $id) {
            return $this->forbiddenResponse();
        }

        $user = User::find($id);

        if (! $user) {
            return $this->userNotFoundResponse();
        }

        if ($user->isAdmin() && User::where('is_admin', true)->count() <= 1) {
            return $this->forbiddenResponse('Cannot delete the only admin user.');
        }

        $user->delete();

        return $this->userDestroySuccessResponse();
    }
}
