<div style="font-family: Arial, Helvetica, sans-serif; background:#f5f7fa; padding:30px;">
    
    <div style="max-width:600px; margin:auto; background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 5px 20px rgba(0,0,0,0.05);">
        
        <!-- HEADER -->
        <div style="background:linear-gradient(90deg,#ffffff,#f9f9f9); padding:20px; border-bottom:1px solid #eee;">
            <h2 style="margin:0; color:#333;">Layanan Data Terbuka</h2>
            <small style="color:#999;">Kementerian Pertahanan RI</small>
        </div>
        <div style="padding:30px;">
            <p style="margin-bottom:15px;">Halo <strong>{{ $nama }}</strong>,</p>

            <p style="margin-bottom:20px; color:#555; line-height:1.6;">
                Permohonan data SDI Anda dengan judul:
                <strong>"{{ $fileName }}"</strong>
                saat ini sedang <strong>dalam proses</strong>.
            </p>
            <div style="background:#f8f9fc; border:1px dashed #d4af37; border-radius:8px; padding:15px; text-align:center; margin-bottom:20px;">
                <div style="font-size:13px; color:#888; margin-bottom:5px;">
                    Kode Permohonan
                </div>

                <div id="kodeText"
                    style="font-size:22px; font-weight:bold; letter-spacing:2px; color:#333;">
                    {{ $kode }}
                </div>

                <button onclick="copyKode()"
                    style="margin-top:10px; background:#d4af37; color:white; border:none; padding:8px 16px; border-radius:6px; cursor:pointer;">
                    Copy Kode Permohonan
                </button>
            </div>

            <p style="color:#555; line-height:1.6;">
                Silakan cek status permohonan Anda secara berkala melalui menu 
                <strong>Monitoring Permohonan</strong>.
            </p>

            <p style="margin-top:25px;">Terima kasih.</p>
        </div>
        <div style="background:#fafafa; padding:15px; text-align:center; font-size:12px; color:#999;">
            © 2026 Kementerian Pertahanan Republik Indonesia
        </div>

    </div>
</div>

<script>
function copyKode() {
    var text = document.getElementById("kodeText").innerText;
    navigator.clipboard.writeText(text);
    alert("Kode berhasil disalin!");
}
</script>