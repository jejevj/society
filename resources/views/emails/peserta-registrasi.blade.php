<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .header { background: #ff7a00; padding: 32px 40px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 24px; }
        .header p { color: #ffe0c2; margin: 6px 0 0; font-size: 14px; }
        .body { padding: 36px 40px; color: #333; }
        .body h2 { font-size: 20px; margin-top: 0; color: #ff7a00; }
        .event-box { background: #fff8f2; border-left: 4px solid #ff7a00; padding: 16px 20px; border-radius: 6px; margin: 20px 0; }
        .event-box p { margin: 4px 0; font-size: 14px; color: #555; }
        .event-box strong { color: #333; }
        .btn-wrap { text-align: center; margin: 28px 0; }
        .btn { display: inline-block; background: #ff7a00; color: #fff !important; text-decoration: none; padding: 14px 36px; border-radius: 8px; font-size: 16px; font-weight: bold; }
        .info-box { background: #f0f9ff; border: 1px solid #bae0fd; border-radius: 6px; padding: 14px 20px; font-size: 13px; color: #0369a1; margin-top: 20px; }
        .footer { background: #f4f6f8; text-align: center; padding: 20px; font-size: 12px; color: #aaa; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>&#127881; Pendaftaran Event Berhasil!</h1>
        <p>Anda telah terdaftar sebagai peserta</p>
    </div>
    <div class="body">
        <h2>Halo, {{ $namaPeserta }}!</h2>
        <p>Selamat! Pembayaran Anda telah dikonfirmasi dan Anda resmi terdaftar sebagai peserta pada event berikut:</p>

        <div class="event-box">
            <p><strong>&#127937; Event:</strong> {{ $namaEvent }}</p>
            <p><strong>&#128231; Email Peserta:</strong> {{ $emailPeserta }}</p>
        </div>

        <p>Karena email ini belum memiliki akun di platform kami, silakan <strong>lengkapi data dan buat password</strong> untuk mengakses portal peserta:</p>

        <div class="btn-wrap">
            <a href="{{ $registerUrl }}" class="btn">Lengkapi Data &amp; Buat Akun</a>
        </div>

        <div class="info-box">
            &#9432; Link ini hanya berlaku <strong>48 jam</strong>. Jika sudah kadaluarsa, hubungi panitia event.
        </div>

        <p style="margin-top:24px; font-size:13px; color:#888;">Jika Anda tidak merasa mendaftar event ini, abaikan email ini.</p>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} Society Event Platform. All rights reserved.
    </div>
</div>
</body>
</html>
