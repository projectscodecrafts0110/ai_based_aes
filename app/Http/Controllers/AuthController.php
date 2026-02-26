<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Confirm password
        if ($validated['password'] !== $request->input('password_confirmation')) {
            return back()->withErrors(['password_confirmation' => 'Passwords do not match.'])->withInput();
        };

        // Create user
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        $otp = rand(100000, 999999);
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5),
        ]);

        if ($user) {
            $otpHtml = "
<div style='margin:0;padding:0;background-color:#f4f6f9;font-family:Segoe UI,Arial,sans-serif;'>

    <table width='100%' cellpadding='0' cellspacing='0' style='padding:40px 0;background-color:#f4f6f9;'>
        <tr>
            <td align='center'>

                <!-- Main Container -->
                <table width='600' cellpadding='0' cellspacing='0' style='background-color:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,0.05);'>
                    
                    <!-- Header -->
                    <tr>
                        <td style='background:linear-gradient(135deg,#1e3c72,#2a5298);padding:30px;text-align:center;color:#ffffff;'>
                            <h1 style='margin:0;font-size:26px;letter-spacing:1px;'>SmartHire</h1>
                            <p style='margin:8px 0 0;font-size:14px;opacity:0.9;'>
                                AI-Based Applicant Evaluation System
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style='padding:40px 35px;text-align:center;'>

                            <h2 style='margin-top:0;color:#333;font-size:22px;'>
                                Verify Your Identity
                            </h2>

                            <p style='color:#555;font-size:15px;line-height:1.6;margin-bottom:30px;'>
                                Hi <strong>{$user->name}</strong>,<br><br>
                                To continue your application process in <strong>SmartHire</strong>, 
                                please confirm your identity using the secure One-Time Password (OTP) below.
                                <br><br>
                                This code will expire in <strong>5 minutes</strong>.
                            </p>

                            <!-- OTP Box -->
                            <div style='margin:30px 0;'>
                                <div style='display:inline-block;padding:18px 40px;
                                            font-size:30px;
                                            letter-spacing:8px;
                                            font-weight:bold;
                                            color:#1e3c72;
                                            background-color:#eef3ff;
                                            border-radius:10px;
                                            border:2px dashed #2a5298;'>
                                    {$otp}
                                </div>
                            </div>

                            <p style='color:#777;font-size:13px;line-height:1.5;margin-top:25px;'>
                                For your security, never share this code with anyone.
                                <br>
                                If you did not request this verification, please ignore this email.
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style='background-color:#f8f9fc;padding:20px;text-align:center;font-size:12px;color:#888;'>
                            This is an automated message from SmartHire.<br>
                            &copy; " . date('Y') . " SmartHire. All rights reserved.
                        </td>
                    </tr>

                </table>
                <!-- End Container -->

            </td>
        </tr>
    </table>

</div>
";

            Mail::html($otpHtml, function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('SmartHire Registration OTP');
            });


            // Log the user in
            auth()->login($user);

            // Redirect after success
            return redirect()->route('login')
                ->with('success', 'Account created successfully! Please log in.');
        }
    }

    public function login(Request $request)
    {
        //  Validate input
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Attempt login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect based on role
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Logged in successfully as Admin!');
            }

            if (!Auth::user()->otp_verified) {
                return redirect()->route('otp.verify')
                    ->with('info', 'Please verify your OTP to continue.');
            }
        }

        // Failed login
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    public function resendOtp(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Please log in to resend OTP.']);
        }

        $otp = rand(100000, 999999);
        $otpExpiresAt = now()->addMinutes(5);

        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => $otpExpiresAt,
            'otp_verified' => false,
        ]);

        // Send OTP email (same HTML as before)
        $otpHtml = "
<div style='margin:0;padding:0;background-color:#f4f6f9;font-family:Segoe UI,Arial,sans-serif;'>
    <table width='100%' cellpadding='0' cellspacing='0' style='padding:40px 0;background-color:#f4f6f9;'>
        <tr>
            <td align='center'>

                <!-- Main Container -->
                <table width='600' cellpadding='0' cellspacing='0' style='background-color:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,0.05);'>
                    
                    <!-- Header -->
                    <tr>
                        <td style='background:linear-gradient(135deg,#1e3c72,#2a5298);padding:30px;text-align:center;color:#ffffff;'>
                            <h1 style='margin:0;font-size:26px;letter-spacing:1px;'>SmartHire</h1>
                            <p style='margin:8px 0 0;font-size:14px;opacity:0.9;'>
                                AI-Based Applicant Evaluation System
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style='padding:40px 35px;text-align:center;'>

                            <h2 style='margin-top:0;color:#333;font-size:22px;'>
                                Verify Your Identity
                            </h2>

                            <p style='color:#555;font-size:15px;line-height:1.6;margin-bottom:30px;'>
                                Hi <strong>{$user->name}</strong>,<br><br>
                                To continue your application process in <strong>SmartHire</strong>, 
                                please confirm your identity using the secure One-Time Password (OTP) below.
                                <br><br>
                                This code will expire in <strong>5 minutes</strong>.
                            </p>

                            <!-- OTP Box -->
                            <div style='margin:30px 0;'>
                                <div style='display:inline-block;padding:18px 40px;
                                            font-size:30px;
                                            letter-spacing:8px;
                                            font-weight:bold;
                                            color:#1e3c72;
                                            background-color:#eef3ff;
                                            border-radius:10px;
                                            border:2px dashed #2a5298;'>
                                    {$otp}
                                </div>
                            </div>

                            <p style='color:#777;font-size:13px;line-height:1.5;margin-top:25px;'>
                                For your security, never share this code with anyone.
                                <br>
                                If you did not request this verification, please ignore this email.
                            </p>
                            </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style='background-color:#f8f9fc;padding:20px;text-align:center;font-size:12px;color:#888;'>
                            This is an automated message from SmartHire.<br>
                            &copy; " . date('Y') . " SmartHire. All rights reserved.
                        </td>
                    </tr>
                </table>
                <!-- End Container -->
            </td>
        </tr>
    </table>
</div>
";

        Mail::html($otpHtml, function ($message) use ($user) {
            $message->to($user->email)
                ->subject('SmartHire Registration OTP');
        });

        return back()->with('success', 'A new OTP has been sent to your email.');
    }

    public function verifyOtp(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Please log in to verify OTP.']);
        }

        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        if ($user->otp_code === $request->input('otp') && now()->lessThanOrEqualTo($user->otp_expires_at)) {
            $user->update([
                'otp_verified' => true,
                'otp_code' => null,
                'otp_expires_at' => null,
            ]);

            return redirect()->route('dashboard')->with('success', 'OTP verified successfully! Welcome to SmartHire.');
        }

        return back()->withErrors(['otp_code' => 'Invalid or expired OTP. Please try again.']);
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
