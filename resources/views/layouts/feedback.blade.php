
@php
	use Illuminate\Support\Facades\DB;
	$cek_chatbot = DB::table('app_setting')->select('url_chatbot')->first();
@endphp
@if(!empty($cek_chatbot->url_chatbot))

<div id="chatFloating">
    <div id="chatBotToggle">
        <button id="closeChatBtn" type="button">&times;</button>
        <a 
        href="{{ $cek_chatbot->url_chatbot }}"
        target="_blank"
        class="btn btn-gold-chat">
            <i class="fa fa-headset text-white me-1"></i>
            Pusat Informasi
        </a>
    </div>
    
    
</div>
@endif

<button id="surveyToggle" class="btn btn-marron">
    <i class="fa fa-envelope text-white"></i>Feedback
</button>

<div class="modal fade" id="surveyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header flex-column align-items-center text-center position-relative">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                <img alt="Logo" src="{{ asset('logo/logo-kemhan.png') }}" class="h-80px mb-3" />
                <p class="fs-6 mb-0">Bantu kami meningkatkan layanan dengan feedback Anda.</p>
            </div>
            <div class="modal-body">
                <form id="surveyForm">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">Apakah tujuan utama Anda mengunjungi laman Satu Data Pertahanan hari ini? <span class="text-danger">*</span></label>
                        <select class="form-control" name="tujuan" required>
                            <option value="">-- Pilih --</option>
                            <option value="Mencari data terbuka Kementrian Pertahanan untuk kepentingan bisnis, perumusan kebijakan, atau referensi pribadi lainnya.">Mencari data terbuka Kementrian Pertahanan untuk kepentingan bisnis, perumusan kebijakan, atau referensi pribadi lainnya.</option>
                            <option value="Mencari data terbuka Kementrian Pertahanan untuk kepentingan bahan ajar/kurikum/tugas belajar.">Mencari data terbuka Kementrian Pertahanan untuk kepentingan bahan ajar/kurikum/tugas belajar.</option>
                            <option value="Mencari data untuk membuktikan kebenaran atas sebuah isu tertentu.">Mencari data untuk membuktikan kebenaran atas sebuah isu tertentu.</option>
                            <option value="Mempelajari lebih lanjut terkait transparansi data dan informasi yang dimiliki oleh Kementrian Pertahanan.">Mempelajari lebih lanjut terkait transparansi data dan informasi yang dimiliki oleh Kementrian Pertahanan.</option>
                            <option value="Lainnya">Lainnya (tulis secara spesifik)</option>
                        </select>
                    </div>
                    <div class="mb-4 d-none" id="tujuanLainnyaWrapper">
                        <label class="form-label">Tuliskan tujuan Anda <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="tujuan_lainnya" id="tujuanLainnya">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Apakah Anda berhasil menemukan data atau informasi yang Anda cari? <span class="text-danger">*</span></label>
                        <select class="form-control" name="keberhasilan" required>
                            <option value="">-- Pilih --</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Berikan saran dan masukan Anda untuk Satu Data Pertahanan agar kami dapat memberikan layanan lebih baik lagi</label>
                        <textarea class="form-control" name="saran" rows="5" placeholder="Tuliskan saran atau kendala disini"></textarea>
                    </div>

                    <button type="submit" class="btn btn-marron-monitoring w-100 text-white">
                        Kirim
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('closeChatBtn').addEventListener('click', function () {
        const el = document.getElementById('chatFloating');
        el.style.transition = 'opacity 0.3s ease';
        el.style.opacity = '0';

        setTimeout(() => {
            el.style.display = 'none';
        }, 300);
    });

    $(document).ready(function(){

    $("#surveyToggle").click(function(){
        $("#surveyModal").modal("show");
    });

    $('select[name="tujuan"]').on('change', function(){
        if ($(this).val() === 'Lainnya') {
            $('#tujuanLainnyaWrapper').removeClass('d-none');
            $('#tujuanLainnya').prop('required', true);
        } else {
            $('#tujuanLainnyaWrapper').addClass('d-none');
            $('#tujuanLainnya').prop('required', false);
            $('#tujuanLainnya').val('');
        }

    })

    $("#surveyForm").submit(function(e){
        e.preventDefault();

        $.ajax({
            url: "{{ route('survey-submit') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(res){
                if(res.status){
                    Swal.fire("Terima Kasih!", res.message, "success");
                    $("#surveyModal").modal("hide");
                    $("#surveyForm")[0].reset();
                } else {
                    Swal.fire("Gagal!", res.message, "error");
                }
            },
            error: function(){
                Swal.fire("Error!", "Terjadi kesalahan.", "error");
            }
        });
    });

});
</script>