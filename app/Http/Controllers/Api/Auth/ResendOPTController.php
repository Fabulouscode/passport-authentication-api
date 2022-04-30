<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResendOTPRequest;
use App\Models\User;
use App\Notifications\ResendOTP;
use App\Services\OTPService;
use Illuminate\Http\Request;

class ResendOPTController extends Controller
{
    private $otpService;

    public function __construct(OTPService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function resendOTP(ResendOTPRequest $request)
    {
        $user = User::firstWhere('email', $request->email);

        if(!$user) {
            return response([
                'status' => 'fail',
                'code' => 403,
                'message' => 'Account not found on this platform. Proceed to sign up!',
            ])->setStatusCode(403);
        }

        $otp = $this->otpService->resendOTP($request->otpRequestId);

         // Resend OTP via email
         $user->notify(new ResendOTP($user, $otp));

        return response([
            'status' => 'success',
            'code' => 200,
            'message' => 'OTP resent successfully. Check your email!'
        ])->setStatusCode(200);
    }
}
