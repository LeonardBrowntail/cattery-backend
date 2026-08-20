<?php

namespace App\Http\Controllers;

use App\Http\ApiTraits\UserAPIResponses;
use App\Http\Requests\DestroyUserRequest;
use App\Http\Requests\IndexUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use AuthorizesRequests, UserAPIResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(IndexUserRequest $request)
    {
        $users = User::query()
            ->search($request->search())
            ->latest()
            ->paginate($request->perPage())
            ->withQueryString();

        return $this->userIndexSuccessResponse(UserResource::collection($users));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->UserNotFoundResponse();
        }
        return $this->userShowSuccessResponse(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->userNotFoundResponse();
        }

        DB::transaction(function() use ($user, $request) {
            $user->update($request->safe());
        });

        return $this->userUpdateSuccessResponse(new UserResource($user->fresh()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyUserRequest $request, int $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->userNotFoundResponse();
        }
        $user->delete();
        return $this->userDestroySuccessResponse();
    }
}
