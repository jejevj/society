<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>OTP Code</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <h2>Hi, {{ $nama }}</h2>
    <p>Here is your OTP Code:</p>

    <p>
        <span 
           style="display:inline-block;
                  padding:12px 20px;
                  background-color:#cc0a24;
                  color:#fff;
                  text-decoration:none;
                  border-radius:6px;">
           {{ $otp }}
</span>
    </p>

    <p>Keep your OTP code confidential, Thank you</p>
</body>
</html>
