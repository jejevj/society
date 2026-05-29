<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <h2>Halo, {{ $nama }}</h2>
    <p>Terima kasih sudah melakukan proses lupa password. Silakan klik tombol di bawah ini untuk proses selanjutnya:</p>

    <p>
        <a href="{{ $verificationUrl }}" 
           style="display:inline-block;
                  padding:12px 20px;
                  background-color:#28a745;
                  color:#fff;
                  text-decoration:none;
                  border-radius:6px;">
           Lupa Password
        </a>
    </p>

    <p>Jika tombol tidak bisa diklik, salin dan buka link berikut di browser Anda:</p>
    <p>{{ $verificationUrl }}</p>
</body>
</html>
