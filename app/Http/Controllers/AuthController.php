<?php

namespace App\Http\Controllers;

use App\Http\ApiTraits\AuthAPIResponses;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;

class AuthController extends Controller
{
    use AuthAPIResponses;

    public function register(RegisterRequest $request) {
        if (isset($request->validator) && $request->validator->fails()) {
            return $this->registerInvalidResponse($request->validator->errors());
        }
        $valid = $request->validated();
        $valid['password'] = Hash::make($request->validated('password'));
        User::create($valid);
        return $this->registerSuccessResponse();
    }

    public function login(LoginRequest $request) {
        if (isset($request->validator) && $request->validator->fails()){
            return $this->loginInvalidResponse($request->validator->errors());
        }

        // check username
        $user = User::where('email', $request->validated('email'))->first();

        $validPass = null;
        if ($user) {
            // check password
            $validPass = Hash::check($request->validated('password'), $user->password);
        }
        
        if (!$user || !$validPass) {
            return $this->loginFailedResponse();
        }

        if ($user->isAdmin()) {
            $token = $user->createToken('auth-token')->plainTextToken;
        } else {
            $token = $user->createToken('auth-token', ['user.view', 'user.update', 'basket.create', 'basket.update', 'basket.delete', 'order.create'])->plainTextToken;
        }
        return $this->loginSuccessResponse($token, $user->toResource());
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return $this->logoutSuccessResponse();
    }
}
