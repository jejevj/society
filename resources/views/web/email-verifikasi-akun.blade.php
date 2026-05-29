<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Akun</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <h2>Halo, {{ $nama }}</h2>
    <p>Terima kasih sudah melakukan registrasi. Silakan klik tombol di bawah ini untuk verifikasi akun Anda:</p>

    <p>
        <a href="{{ $verificationUrl }}" 
           style="display:inline-block;
                  padding:12px 20px;
                  background-color:#28a745;
                  color:#fff;
                  text-decoration:none;
                  border-radius:6px;">
           Verifikasi Akun
        </a>
    </p>

    <p>Jika tombol tidak bisa diklik, salin dan buka link berikut di browser Anda:</p>
    <p>{{ $verificationUrl }}</p>
</body>
</html>
