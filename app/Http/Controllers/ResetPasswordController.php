<?php

namespace App\Http\Controllers;

use App\Models\ResetPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{

    public function sendOTP(Request $request) {

    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors(),
        ], 400);
    }

    $otp_code = rand(1000, 9999);

    $existingOtp = DB::table('password_reset_otps')->where('email', $request->email)->first();
    
    if ($existingOtp && Carbon::parse($existingOtp->expires_at)->isFuture()) {
        return response()->json([
            'success' => false,
            'message' => 'OTP is still valid. Please try again later.',
        ], 400);
    }

    DB::table('password_reset_otps')->updateOrInsert(
        ['email' => $request->email],
        [
            'otp' => $otp_code,
            'is_used' => false,
            'expires_at' => Carbon::now()->addMinutes(2),
            'updated_at' => now(),
        ]
    );

    try {
        Mail::raw("Your password reset OTP is: $otp_code", function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Password Reset OTP');
        });
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to send OTP. Please try again later.',
        ], 500);
    }

    return response()->json([
        'success' => true,
        'message' => 'OTP sent successfully',
        'data' => $otp_code
    ], 200);
}


    public function verifyOtp(Request $request){

        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:password_reset_otps,email',
            'otp' => 'required|digits:4'
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ],400);
        }

        $otpRecord = DB::table('password_reset_otps')
        ->where('email',$request->email)
        ->where('otp',$request->otp)
        ->where('is_used',false)
        ->where('expires_at','>',now())
        ->first();

        if(!$otpRecord){
            return response()->json(['message' => 'Invalid or expired OTP.'], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Correct and valid OTP code',
            'data' => $otpRecord
        ],200);
    }

    public function resetPassword(Request $request){

        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:password_reset_otps,email',
            'password' => [
                'required',
                'confirmed',
                'min:6',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/',]
            ]);

            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors(),
                ],400);
            }

            $user = User::where('email',$request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            DB::table('password_reset_otps')
            ->where('email',$user->email)
            ->update(['is_used' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Password Reset Success',
                'data'=>$request->password
            ],200);
        }

    }

