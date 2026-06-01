@include('layouts.header-v2')

<style>
.success-fullscreen { position:relative; min-height:100vh; }
.success-fullscreen::before { content:''; position:fixed; inset:0; z-index:-2; background:#0a0a1e; }
.success-fullscreen::after  { content:''; position:fixed; inset:0; z-index:-1; background:radial-gradient(ellipse at center, rgba(230,32,32,0.12) 0%, transparent 70%); }
.success-content { position:relative; z-index:1; display:flex; align-items:center; justify-content:center; min-height:100vh; padding:40px 16px; }

.success-card {
    background:#fff; border-radius:24px; box-shadow:0 24px 80px rgba(0,0,0,0.35);
    padding:48px 40px; max-width:480px; width:100%; text-align:center;
}
.check-anim {
    width:80px; height:80px; border-radius:50%; background:linear-gradient(135deg,#22c55e,#16a34a);
    display:flex; align-items:center; justify-content:center; margin:0 auto 24px;
    box-shadow:0 8px 32px rgba(34,197,94,0.35);
    animation: popIn 0.5s cubic-bezier(0.34,1.56,0.64,1) both;
}
@keyframes popIn { from { transform:scale(0); opacity:0; } to { transform:scale(1); opacity:1; } }
.check-anim i { color:#fff; font-size:2.2rem; }

.success-card h2 { font-size:1.5rem; font-weight:800; color:#1a1a1a; margin-bottom:8px; }
.success-card p  { color:#777; font-size:0.92rem; line-height:1.6; margin-bottom:0; }

.divider { border:none; border-top:1px solid #f0f0f0; margin:24px 0; }

.info-row { display:flex; align-items:center; gap:10px; font-size:0.88rem; color:#555; margin-bottom:10px; }
.info-row i { color:#E62020; width:18px; text-align:center; flex-shrink:0; }

.btn-login {
    display:inline-flex; align-items:center; justify-content:center; gap:8px;
    background:#E62020; color:#fff; border:none; border-radius:10px;
    padding:13px 32px; font-weight:700; font-size:0.95rem; text-decoration:none;
    transition:background 0.2s; width:100%; margin-top:8px;
}
.btn-login:hover { background:#c41a1a; color:#fff; }

.confetti-wrap { position:fixed; inset:0; pointer-events:none; z-index:9999; overflow:hidden; }
.confetti { position:absolute; top:-20px; width:10px; height:10px; opacity:0; animation:fall linear forwards; }
@keyframes fall {
    0%   { opacity:1; transform:translateY(0) rotate(0deg); }
    100% { opacity:0; transform:translateY(110vh) rotate(720deg); }
}

@media (max-width:480px) { .success-card { padding:32px 20px; } }
</style>

{{-- Confetti --}}
<div class="confetti-wrap" id="confettiWrap"></div>

<div class="success-fullscreen">
<div class="success-content">
    <div class="success-card">
        <div class="check-anim">
            <i class="fa-solid fa-check"></i>
        </div>

        <h2>Pendaftaran Berhasil!</h2>
        <p>Selamat! Akun Anda telah dibuat dan Anda berhasil terdaftar pada event ini. Silakan masuk untuk melanjutkan.</p>

        <hr class="divider">

        <div class="info-row">
            <i class="fa-solid fa-envelope"></i>
            <span>Bukti pendaftaran telah dikirim ke email Anda</span>
        </div>
        <div class="info-row">
            <i class="fa-solid fa-calendar-check"></i>
            <span>Akses event aktif setelah pembayaran dikonfirmasi</span>
        </div>

        <a href="{{ route('login') }}" class="btn-login">
            <i class="fa-solid fa-right-to-bracket"></i> Masuk ke Akun Saya
        </a>
    </div>
</div>
</div>

<script>
(function () {
    const colors = ['#E62020','#f8ee93','#22c55e','#3b82f6','#a855f7','#f97316'];
    const wrap   = document.getElementById('confettiWrap');
    for (let i = 0; i < 80; i++) {
        const el  = document.createElement('div');
        el.className = 'confetti';
        const size  = Math.random() * 8 + 6;
        const left  = Math.random() * 100;
        const delay = Math.random() * 1.5;
        const dur   = Math.random() * 2 + 2;
        const color = colors[Math.floor(Math.random() * colors.length)];
        const shapes = ['0%','50%','0% 50%'];
        el.style.cssText = `left:${left}%;width:${size}px;height:${size}px;background:${color};border-radius:${shapes[Math.floor(Math.random()*shapes.length)]};animation-duration:${dur}s;animation-delay:${delay}s;`;
        wrap.appendChild(el);
    }
})();
</script>

@include('layouts.footer-v2')
