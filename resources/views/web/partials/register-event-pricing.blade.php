{{-- Pricing panel: include inside register.blade.php when $event is present --}}
@if($event)
<div class="mb-6" id="eventPricingPanel">

    {{-- Base event price --}}
    <div class="d-flex align-items-center justify-content-between p-4 rounded-2 bg-light-primary border border-primary border-dashed mb-4">
        <div>
            <span class="fw-bold text-primary fs-5">{{ $event->judul_event }}</span>
            <div class="text-muted fs-7 mt-1">Biaya Pendaftaran</div>
        </div>
        <div class="text-end">
            @if(($event->harga_event ?? 0) > 0)
                <span class="fw-bolder fs-4 text-primary">Rp&nbsp;{{ number_format($event->harga_event, 0, ',', '.') }}</span>
            @else
                <span class="badge badge-light-success fw-bold fs-7 px-3 py-2">GRATIS</span>
            @endif
        </div>
    </div>

    @if(isset($event->paket) && $event->paket->count() > 0)
    <div class="mb-3">
        <div class="fw-semibold text-gray-700 fs-6 mb-3">
            <i class="ki-outline ki-gift fs-5 me-1 text-primary"></i> Paket &amp; Add-On
        </div>

        {{-- Included packages (is_addon = 0) --}}
        @php $included = $event->paket->where('is_addon', 0); @endphp
        @if($included->count() > 0)
        <div class="mb-3">
            <div class="text-muted fs-7 fw-semibold text-uppercase letter-spacing-wide mb-2">Sudah Termasuk</div>
            @foreach($included->sortBy('urutan_paket') as $paket)
            <div class="d-flex align-items-center justify-content-between py-2 px-3 rounded-1 bg-light mb-1">
                <div class="d-flex align-items-center gap-2">
                    @if($paket->icon_paket)
                        <i class="{{ $paket->icon_paket }} text-success fs-5"></i>
                    @else
                        <i class="ki-outline ki-check-circle text-success fs-5"></i>
                    @endif
                    <div>
                        <div class="fs-7 fw-semibold">{{ $paket->judul_paket }}</div>
                        @if($paket->sub_judul_paket)
                            <div class="text-muted fs-8">{{ $paket->sub_judul_paket }}</div>
                        @endif
                    </div>
                </div>
                <span class="badge badge-light-success fs-8">Termasuk</span>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Optional addons (is_addon = 1) --}}
        @php $addons = $event->paket->where('is_addon', 1); @endphp
        @if($addons->count() > 0)
        <div>
            <div class="text-muted fs-7 fw-semibold text-uppercase letter-spacing-wide mb-2">Add-On Opsional</div>
            @foreach($addons->sortBy('urutan_paket') as $paket)
            <label class="d-flex align-items-center justify-content-between py-2 px-3 rounded-1 border border-dashed border-gray-300 mb-2 w-100" style="cursor:pointer">
                <div class="d-flex align-items-center gap-3">
                    <input type="checkbox"
                        class="form-check-input addon-paket-checkbox"
                        name="selected_paket[]"
                        value="{{ $paket->kode_paket }}"
                        data-harga="{{ (int)($paket->harga_paket ?? 0) }}"
                        data-judul="{{ $paket->judul_paket }}">
                    <div>
                        <div class="fs-7 fw-semibold">{{ $paket->judul_paket }}</div>
                        @if($paket->sub_judul_paket)
                            <div class="text-muted fs-8">{{ $paket->sub_judul_paket }}</div>
                        @endif
                    </div>
                </div>
                <div class="text-end ms-3 flex-shrink-0">
                    @if(($paket->harga_paket ?? 0) > 0)
                        <span class="fw-bold text-primary fs-7">Rp&nbsp;{{ number_format($paket->harga_paket, 0, ',', '.') }}</span>
                    @else
                        <span class="badge badge-light-success fs-8">Gratis</span>
                    @endif
                </div>
            </label>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Total summary --}}
    <div class="separator separator-dashed my-3"></div>
    <div class="d-flex justify-content-between align-items-center px-1 mb-1">
        <span class="fw-semibold text-gray-600 fs-7">Biaya Pendaftaran</span>
        <span class="fw-semibold text-gray-700 fs-7">Rp&nbsp;{{ number_format($event->harga_event ?? 0, 0, ',', '.') }}</span>
    </div>
    <div class="d-flex justify-content-between align-items-center px-1 mb-3" id="rowAddonSummary" style="display:none!important">
        <span class="text-muted fs-7">Add-On dipilih</span>
        <span class="text-muted fs-7" id="addonSummaryText">Rp 0</span>
    </div>
    <div class="d-flex justify-content-between align-items-center px-1 py-2 rounded-1 bg-light-primary">
        <span class="fw-bolder text-primary fs-6">Total Estimasi</span>
        <span class="fw-bolder fs-5 text-primary" id="totalHargaEstimasi">
            Rp&nbsp;{{ number_format($event->harga_event ?? 0, 0, ',', '.') }}
        </span>
    </div>
    @endif

</div>

<script>
(function () {
    var basePrice  = {{ (int)($event->harga_event ?? 0) }};
    var totalEl    = document.getElementById('totalHargaEstimasi');
    var addonRow   = document.getElementById('rowAddonSummary');
    var addonText  = document.getElementById('addonSummaryText');

    function fmt(n) {
        return 'Rp\u00a0' + n.toLocaleString('id-ID');
    }

    function updateTotal() {
        var checkboxes = document.querySelectorAll('.addon-paket-checkbox');
        var extra = 0;
        checkboxes.forEach(function (cb) {
            if (cb.checked) extra += parseInt(cb.dataset.harga || 0, 10);
        });
        if (totalEl)  totalEl.textContent  = fmt(basePrice + extra);
        if (addonRow) addonRow.style.display = extra > 0 ? '' : 'none';
        if (addonText) addonText.textContent = fmt(extra);
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.addon-paket-checkbox').forEach(function (cb) {
            cb.addEventListener('change', updateTotal);
        });
        updateTotal();
    });
}());
</script>
@endif
