<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .success-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.08);
            padding: 50px 40px;
            max-width: 520px;
            width: 100%;
            text-align: center;
        }
        .success-icon {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .success-icon i { font-size: 40px; color: #fff; }
        @keyframes popIn {
            0%   { transform: scale(0); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        .detail-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
            text-align: left;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-row .label { color: #64748b; }
        .detail-row .value { font-weight: 600; color: #1e293b; }
        .badge-sandbox {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
</head>
<body>
<div class="success-card">

    {{-- Icon Sukses --}}
    <div class="success-icon">
        <i class="fas fa-check"></i>
    </div>

    <h2 class="fw-bold text-success mb-1">Pembayaran Berhasil!</h2>
    <p class="text-muted mb-0">Registrasi event kamu telah dikonfirmasi.</p>

    {{-- Sandbox badge --}}
    @if(isset($isSandbox) && $isSandbox)
        <div class="mt-2">
            <span class="badge-sandbox"><i class="fas fa-flask me-1"></i> Sandbox / Testing Mode</span>
        </div>
    @endif

    {{-- Detail transaksi --}}
    @if(isset($reg) && $reg)
    <div class="detail-box">
        <div class="detail-row">
            <span class="label">Order ID</span>
            <span class="value">{{ $reg->midtrans_order_id ?? '-' }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Nama</span>
            <span class="value">{{ $reg->nama_peserta ?? '-' }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Email</span>
            <span class="value">{{ $reg->email_peserta ?? '-' }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Event</span>
            <span class="value">{{ $reg->kode_event ?? '-' }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Total Bayar</span>
            <span class="value text-success">Rp {{ number_format($reg->total_bayar ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Status</span>
            <span class="value"><span class="badge bg-success">PAID</span></span>
        </div>
    </div>
    @endif

    <p class="text-muted small mb-4">
        Kamu akan menerima konfirmasi ke email yang terdaftar.<br>
        Silakan login untuk melihat detail registrasi.
    </p>

    <div class="d-grid gap-2">
        <a href="{{ route('login') }}" class="btn btn-success btn-lg rounded-pill">
            <i class="fas fa-sign-in-alt me-2"></i> Login ke Akun Saya
        </a>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="fas fa-home me-2"></i> Kembali ke Beranda
        </a>
    </div>

</div>
</body>
</html>
