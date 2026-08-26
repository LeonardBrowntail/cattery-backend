<?php

namespace App\Http\Controllers;

use App\Http\ApiTraits\UserAPIResponses;
use App\Http\Requests\DestroyUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use UserAPIResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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
    public function show(Request $request, int $id)
    {
        $user = User::find($id);
        if (!$user || $user->isAdmin() && !$request->user()->isAdmin()) {
            return $this->UserNotFoundResponse();
        } else {
            return $this->userShowSuccessResponse($user->toResource());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return $this->userUpdateInvalidResponse($request->validator->errors());
        }
        
        $user = User::find($id);
        if (!$user) {
            return $this->userNotFoundResponse();
        }

        DB::transaction(function() use ($user, $request) {
            $user->update($request->safe()->toArray());
        });

        return $this->userUpdateSuccessResponse($user->toResource());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->userNotFoundResponse();
        }
        $user->delete();
        return $this->userDestroySuccessResponse();
    }
}
