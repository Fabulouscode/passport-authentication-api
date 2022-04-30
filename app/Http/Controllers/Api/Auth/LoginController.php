<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\SignupResource;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        if(!Auth::attempt($request->only('email', 'password'))) {
            
            return response([
                'status' => 'fail',
                'code' => 401,
                'message' => 'Invalid Login credentials.'
            ])->setStatusCode(401);
        }

        $user = Auth::user();

        if ($user->status == 0) {
            return response([
                'status' => 'fail',
                'code' => 401,
                'message' => 'Hello, there! activate your account first.'
            ])->setStatusCode(401);
        }

        $accessToken = $user->createToken('authToken')->accessToken;

        return response([
            'status' => 'success',
            'code' => 200,
            'message' => 'Logged in successfully.',
            'token' => $accessToken,
            'data' => new SignupResource($user),
        ])->setStatusCode(200);
    }

}
