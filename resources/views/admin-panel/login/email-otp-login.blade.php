<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kode OTP Login Admin Panel Portal SDI</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <h2>Halo, {{ $nama }}</h2>
    <p>Berikut Kode OTP Anda:</p>

    <p>
        <span 
           style="display:inline-block;
                  padding:12px 20px;
                  background-color:#28a745;
                  color:#fff;
                  text-decoration:none;
                  border-radius:6px;">
           {{ $otp }}
</span>
    </p>

    <p>Jaga Kerahasiaan kode OTP anda, Terimakasih</p>
</body>
</html>
