<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OtpVerificationController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);
        
        $user = $request->user();
        
        if ($user->email_verification_otp == $request->otp && $user->otp_expires_at > now()) {
            $user->markEmailAsVerified();
            $user->email_verification_otp = null;
            $user->save();
            return redirect()->intended('/dashboard')->with('success', 'Email verified successfully!');
        }
        
        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }

    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    }
}
