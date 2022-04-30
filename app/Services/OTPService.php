<?php 

namespace App\Services;


use App\Models\Otp;
use Carbon\Carbon;

class OTPService
{

    public function generateOTP(int $defaultDigit = 4)
    {
        $digit = config('otp.digit') ?? $defaultDigit;
        return rand(pow(10, $digit - 1), pow(10, $digit) - 1);
    }

    public function expireOldOTPRequests($email)
    {
        return Otp::where('email', $email)
            ->where('status', 'NEW')
            ->update(['status' => 'EXPIRED']);
    }

    public function createOTPRecord($email, $token, $requestId)
    {
        return Otp::create([
            'request_id' => $requestId,
            'email' => $email,
            'token' => $token,
            'expires_on' => Carbon::now()->addMinutes(15)
        ]);
    }

    public static function updateRetry($otp) {
        return Otp::where('email', $otp->email)
            ->where('status', 'NEW')
            ->increment('retry');
    }

    public static function countNumberOfResendOTP($otp){
        return Otp::where('request_id', $otp->request_id)->count();
    }

    public function updateStatusTo($token, $status)
    {
        return Otp::where('token', $token)->update(['status' => $status]);
    }

    public function findOTPByTokenAndEmailAndStatus($token, $email, $status = 'NEW')
    {
        return Otp::where([
            'email' => $email,
            'token' => $token,
            'status' => $status
        ])->first();
    }

    public function findOTPByTokenAndEmail($token, $email)
    {
        return Otp::where([
            'email' => $email,
            'token' => $token,
        ])->first();
    }

    public function requestOTP($email)
    {
        $this->expireOldOTPRequests($email);
        $token = $this->generateOTP();
        $requestId = md5(time());
        return $this->createOTPRecord($email, $token, $requestId);
    }

    public function isValidOTP($token, $email)
    {
        $otp = $this->findOTPByTokenAndEmail($token, $email);
        if (!$otp) {
            return null;
        }

        if ($otp->status != Otp::NEW){
            return null;
        }

        $this->updateStatusTo($token,'USED');

        return $otp;

    }

    public function resendOTP($requestId)
    {
        $otp = $this->findOTPByRequestId($requestId);
        $this->expireOldOTPRequests($otp->email);
        $token = $this->generateOTP();
        return $this->createOTPRecord($otp->email, $token, $otp->request_id);
    }

    public function findOTPByRequestId($requestId)
    {
        return Otp::where('request_id', $requestId)->first();
    }
}
