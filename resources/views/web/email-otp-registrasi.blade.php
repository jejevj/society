<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <style>
        body { margin: 0; padding: 0; background: #f4f4f4; font-family: 'Segoe UI', Arial, sans-serif; }
        .wrapper { max-width: 520px; margin: 30px auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #1a1a2e 0%, #E62020 100%); padding: 32px 36px; text-align: center; }
        .header h1 { color: #fff; font-size: 1.4rem; font-weight: 800; margin: 0; letter-spacing: 0.5px; }
        .header p { color: rgba(255,255,255,0.75); font-size: 0.85rem; margin: 6px 0 0; }
        .body { padding: 32px 36px; }
        .greeting { font-size: 1rem; color: #333; margin-bottom: 12px; }
        .otp-box { text-align: center; margin: 24px 0; }
        .otp-code { display: inline-block; background: #f8f8f8; border: 2px dashed #E62020; border-radius: 14px; padding: 16px 36px; font-size: 2.4rem; font-weight: 900; letter-spacing: 10px; color: #E62020; font-family: 'Courier New', monospace; }
        .note { font-size: 0.83rem; color: #888; text-align: center; margin-top: 8px; }
        .divider { border: none; border-top: 1px solid #f0f0f0; margin: 24px 0; }
        .footer { padding: 16px 36px 24px; text-align: center; color: #bbb; font-size: 0.77rem; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Email Verification</h1>
            <p>Society Event Registration</p>
        </div>
        <div class="body">
            <p class="greeting">Hi <strong>{{ $nama }}</strong>,</p>
            <p style="color:#555;font-size:0.9rem;">Thank you for registering. Use the OTP code below to verify your email address and continue with your registration.</p>
            <div class="otp-box">
                <div class="otp-code">{{ $otp }}</div>
            </div>
            <p class="note">This code is valid for <strong>10 minutes</strong>. Do not share it with anyone.</p>
            <hr class="divider">
            <p style="font-size:0.82rem;color:#999;text-align:center;">If you did not register, please ignore this email.</p>
        </div>
        <div class="footer">&copy; {{ date('Y') }} Society Event &mdash; All rights reserved.</div>
    </div>
</body>
</html>
