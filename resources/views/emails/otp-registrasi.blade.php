<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Registrasi</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f4f8; margin: 0; padding: 0; }
        .wrapper { max-width: 520px; margin: 40px auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.10); }
        .header { background: linear-gradient(135deg, #1a1a2e 0%, #E62020 100%); padding: 36px 32px 28px; text-align: center; }
        .header h1 { color: #fff; font-size: 1.5rem; margin: 0 0 4px; font-weight: 800; }
        .header p  { color: rgba(255,255,255,0.75); font-size: 0.9rem; margin: 0; }
        .body  { padding: 36px 32px; }
        .body p { color: #444; font-size: 0.95rem; line-height: 1.6; margin: 0 0 16px; }
        .otp-box { background: #f8f8fb; border: 2px dashed #E62020; border-radius: 12px; text-align: center; padding: 24px 16px; margin: 24px 0; }
        .otp-box .label { font-size: 0.75rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: #999; margin-bottom: 8px; }
        .otp-box .code  { font-size: 2.8rem; font-weight: 900; letter-spacing: 10px; color: #E62020; font-family: monospace; }
        .note { font-size: 0.82rem; color: #aaa; text-align: center; margin-top: 16px; }
        .footer { background: #f4f4f8; text-align: center; padding: 20px 32px; font-size: 0.78rem; color: #bbb; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>Verifikasi Registrasi</h1>
        <p>Kode OTP untuk melanjutkan pendaftaran Anda</p>
    </div>
    <div class="body">
        <p>Halo <strong>{{ $nama }}</strong>,</p>
        <p>Terima kasih telah mendaftar. Gunakan kode OTP di bawah ini untuk melanjutkan proses registrasi Anda. Kode ini berlaku selama <strong>10 menit</strong>.</p>
        <div class="otp-box">
            <div class="label">Kode OTP Anda</div>
            <div class="code">{{ $otp }}</div>
        </div>
        <p>Jika Anda tidak merasa mendaftar, abaikan email ini.</p>
        <p class="note">Jangan bagikan kode ini kepada siapapun.</p>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} Society Event &mdash; Email ini dikirim otomatis, jangan balas.
    </div>
</div>
</body>
</html>
