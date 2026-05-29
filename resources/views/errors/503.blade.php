<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - Layanan Tidak Tersedia</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .error-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 60px 40px;
            text-align: center;
            max-width: 600px;
            width: 100%;
        }
        .error-code {
            font-size: 120px;
            font-weight: bold;
            color: #4facfe;
            line-height: 1;
            margin-bottom: 20px;
        }
        .error-title {
            font-size: 32px;
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .error-message {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .btn-home {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 172, 254, 0.4);
        }
        .illustration {
            margin-bottom: 30px;
            font-size: 80px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="illustration">🔧</div>
        <div class="error-code">503</div>
        <h1 class="error-title">Layanan Tidak Tersedia</h1>
        <p class="error-message">
            Maaf, layanan sedang dalam pemeliharaan atau mengalami gangguan sementara. 
            Kami sedang bekerja untuk mengembalikan layanan secepat mungkin. 
            Terima kasih atas kesabaran Anda.
        </p>
        <a href="{{ url(env('APP_ROUTE', '/')) }}" class="btn-home">Coba Lagi</a>
    </div>
</body>
</html>
