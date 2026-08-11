<?php

namespace App\Http\Controllers;

use App\Http\ApiTraits\UserAPIResponses;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use UserAPIResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        return $this->userIndexSuccessResponse(UserResource::collection($user));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->UserNotFoundResponse();
        }
        return $this->userShowSuccessResponse($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
