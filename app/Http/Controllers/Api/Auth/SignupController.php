<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use App\Http\Resources\SignupResource;
use App\Models\User;
use App\Services\OTPService;
use Illuminate\Support\Facades\Hash;

class SignupController extends Controller
{
    public function __construct(OTPService $otpService)
    {
        $this->otpService = $otpService;
    }
    public function signup(SignupRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
        ]);

        if(!$user) {
            return response([
                'status' => 'fail',
                'code' => 400,
                'message' => 'Unable to create account.Try again!'
            ])->setStatusCode(400);
        }

         // Create an OTP
         $otp = $this->otpService->requestOTP($user->email);


        return response([
            'status' => 'success',
            'code' => 201,
            'message' => 'Account created Successfully. Check email to activate your account.',
            'data' => new SignupResource($user),
        ])->setStatusCode(201);
    }
}
