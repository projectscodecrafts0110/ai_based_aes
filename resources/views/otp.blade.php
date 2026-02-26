<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
</head>
<style>
    body {
        background: linear-gradient(135deg, #22c55e, #800000, #eab308);
    }
</style>

<body>
    <div style="min-height:100vh; display:flex; align-items:center; justify-content:center;">

        <div
            style="width:100%; max-width:420px; background:#ffffff; padding:40px; 
                border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,0.05);">

            <!-- Logo / Title -->
            <div style="text-align:center; margin-bottom:25px;">
                <h2 style="margin:0; font-weight:700; color:#1e3c72;">SmartHire</h2>
                <p style="margin:5px 0 0; font-size:14px; color:#777;">
                    AI-Based Applicant Evaluation System
                </p>
            </div>

            <!-- Heading -->
            <h3 style="text-align:center; margin-bottom:10px; color:#333;">
                OTP Verification
            </h3>

            <p style="text-align:center; font-size:14px; color:#666; margin-bottom:30px;">
                Enter the 6-digit code sent to your email.<br>
                This code will expire in <strong>5 minutes</strong>.
            </p>

            <!-- OTP Form -->
            <form method="POST" action="{{ route('otp.verify.post') }}">
                @csrf

                <div style="margin-bottom:20px;">
                    <input type="text" name="otp" maxlength="6" required placeholder="Enter OTP"
                        style="width:100%; padding:14px; 
                              font-size:20px; 
                              text-align:center; 
                              letter-spacing:6px; 
                              border:2px solid #e5e7eb; 
                              border-radius:10px; 
                              outline:none;"
                        onfocus="this.style.borderColor='#1e3c72'" onblur="this.style.borderColor='#e5e7eb'">
                </div>

                <!-- Error -->
                @if (session('error'))
                    <div style="color:#dc3545; font-size:13px; margin-bottom:15px; text-align:center;">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Submit Button -->
                <button type="submit"
                    style="width:100%; padding:14px; 
                           background:#1e3c72; 
                           color:#ffffff; 
                           border:none; 
                           border-radius:10px; 
                           font-weight:600; 
                           font-size:15px; 
                           cursor:pointer;">
                    Verify OTP
                </button>
            </form>

            <!-- Resend -->
            <div style="text-align:center; margin-top:20px; font-size:13px;">
                <span style="color:#777;">Didn't receive the code?</span>
                <form method="POST" action="{{ route('otp.resend') }}" style="display:inline;">
                    @csrf
                    <button type="submit"
                        style="background:none; border:none; color:#1e3c72; font-weight:600; cursor:pointer;">
                        Resend OTP
                    </button>
                </form>
            </div>

        </div>

    </div>
</body>

</html>
