<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivateAccountRequest;
use App\Http\Resources\SignupResource;
use App\Models\User;
use App\Notifications\WelcomeMsg;
use App\Services\OTPService;
use Illuminate\Http\Request;

class ActivateAccountController extends Controller
{

    /**
     * @var OTPService
     */
    private $otpService;

    public function __construct(OTPService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function accountActivation(ActivateAccountRequest $request)
    {
        $user = User::firstWhere('email', $request->email);
    
        // Check if it's a user
        if(!$user) {
            return response([
                'status' => 'fail',
                'code' => 403,
                'message' => 'You do not have an account on this platform. Proceed to sign up!',
            ])->setStatusCode(403);
        }

         //check if otp is valid
         $otp = $this->otpService->isValidOTP($request->otp, $user->email);

         if(!$otp) {
             return response([
                 'status' => 'fail',
                 'code' => 400,
                 'message' => 'Invalid OTP. Please, request for a new OTP.',
             ])->setStatusCode(400);
         }

         //Update the user details

         $status = $user->update(['status' => '1']);

        // Create authentication token - passport
        $accessToken = $user->createToken('authToken')->accessToken;

        if(!$status || !$accessToken) {
            return response([
                'status' => 'fail',
                'code' => 500,
                'message' => 'Unable to activate account. Try again!',
            ])->setStatusCode(500);
        }

         // send notification to user's email
         $user->notify(new WelcomeMsg($user, $otp));

        // success message response
        return response([
            'status' => 'success',
            'code' => 202,
            'message' => 'Account activated successfully. ',
            'token' => $accessToken,
            'data' => new SignupResource($user),
        ])->setStatusCode(202);

    }
     
}
