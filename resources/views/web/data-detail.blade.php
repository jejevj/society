@include('layouts.header-v2')
<div class="app-wrapper mt-detail" id="kt_app_wrapper">
    <div class="container-xxl">
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
            <div class="d-flex flex-column flex-column-fluid">
                <div id="kt_app_content" class="app-content">
                    <div>
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('beranda') }}">
                                            <i class="fa fa-home"></i> Beranda
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('list') }}">
                                            Data
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active">
                                        Detail
                                    </li>
                                </ol>
                            </nav>
                            <form action="{{ url(env('APP_ROUTE').'/list') }}" method="GET">
                                <div class="search-box d-flex align-items-stretch">
                                    <input type="text"
                                        name="search"
                                        class="form-control"
                                        placeholder="Cari data..."
                                        autocomplete="off">
                                    <button class="btn btn-marron ms-2">
                                        <i class="fa fa-search"></i>
                                    </button>

                                </div>
                            </form>

                        </div>                 
                        <div class="card card-flush mt-5 mb-4">
                            <div class="card-body mb-4">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-4 text-center">
                                            <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center">
                                                @if(!empty($dt->tmp_foto_organisasi))
                                                    <img 
                                                        src="{{ url('storage/' . $dt->tmp_foto_organisasi) }}" 
                                                        alt="Logo Organisasi"
                                                        class="img-fluid mb-3 logo-org mg-detail">
                                                @else
                                                    <div class="text-muted">
                                                        <i class="fa fa-image fa-2x mb-2"></i><br>
                                                        Logo tidak tersedia
                                                    </div>
                                                @endif
                                                <h6 class="fw-bold mt-2">
                                                    {{ $dt->nama_organisasi }}
                                                </h6>
                                                <small class="text-muted">
                                                    Satuan Kerja
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="mb-4">
                                            <div class="p-4">
                                                @include('web.data-detail-metadata')
                                            </div>
                                        </div>
                                    </div>
                                    @if($dt->sifat_master == 'TERBUKA')
                                    <div class="col-md-12">
                                        @include('web.data-detail-file')
                                    </div>
                                    @else
                                    <div class="col-md-12">
                                        @include('web.data-detail-terbatas')
                                    </div>
                                    @endif
                                </div>
                                <div class="row">
                                    @include('web.data-detail-terkait')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('web.data-detail-modal-preview')
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/papaparse.js') }}"></script>
<script>
document.getElementById('toggleDesc').addEventListener('click', function () {
    const content = document.getElementById('descContent');

    content.classList.toggle('collapsed');

    this.innerText = content.classList.contains('collapsed') 
        ? 'Lihat Selengkapnya' 
        : 'Sembunyikan';
});
document.getElementById('toggleMeta').addEventListener('click', function () {
    const content = document.getElementById('metaContent');

    content.classList.toggle('collapsed-meta');

    this.innerText = content.classList.contains('collapsed-meta') 
        ? 'Lihat Selengkapnya' 
        : 'Sembunyikan';
});

document.addEventListener("DOMContentLoaded", function () {
    const previewCsvBtns = document.querySelectorAll(".preview-csv-btn");
    const csvModalEl = document.getElementById('csvPreviewModal');
    const csvModal = new bootstrap.Modal(csvModalEl);
    const csvContainer = document.getElementById("csvPreviewContainer");

    let fullData = [];
    let currentPage = 1;
    let perPage = 10;

    let searchKeyword = "";

    function renderTable() {
        if (!fullDataBackup.length) {
            csvContainer.innerHTML = "<p class='p-3'>Tidak ada data</p>";
            return;
        }
        let filtered = fullDataBackup.filter(row =>
            Object.values(row).some(val =>
                String(val).toLowerCase().includes(searchKeyword)
            )
        );

        let totalPage = Math.ceil(filtered.length / perPage);
        let start = (currentPage - 1) * perPage;
        let end = start + perPage;
        let pageData = filtered.slice(start, end);

        let html = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <input type="text" id="searchCsv" class="form-control w-50" 
                placeholder="🔍 Cari data..." value="${searchKeyword}">
            <div class="small text-muted">
                Total: ${filtered.length} data
            </div>
        </div>

        <div class="table-responsive" style="max-height:400px;">
            <table class="table table-striped table-hover table-bordered align-middle">
                <thead class="table-dark" style="position: sticky; top:0;">
                    <tr>
                        ${Object.keys(fullDataBackup[0]).map(k => `<th>${k}</th>`).join("")}
                    </tr>
                </thead>
                <tbody>
                    ${pageData.map(row => `
                        <tr>
                            ${Object.values(row).map(v => `<td>${v ?? '-'}</td>`).join("")}
                        </tr>
                    `).join("")}
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="small text-muted">
                Halaman ${currentPage} dari ${totalPage || 1}
            </div>
            <div>
                <button class="btn btn-sm btn-light me-1" id="prevPage">←</button>
                <button class="btn btn-sm btn-light" id="nextPage">→</button>
            </div>
        </div>
        `;

        csvContainer.innerHTML = html;
        document.getElementById("prevPage").onclick = () => {
            if (currentPage > 1) {
                currentPage--;
                renderTable();
            }
        };

        document.getElementById("nextPage").onclick = () => {
            if (currentPage < totalPage) {
                currentPage++;
                renderTable();
            }
        };
        document.getElementById("searchCsv").addEventListener("input", function () {
            searchKeyword = this.value.toLowerCase();
            currentPage = 1;
            renderTable();
        });
    }

    let fullDataBackup = [];
    previewCsvBtns.forEach(btn => {
        btn.addEventListener("click", function () {

            let id    = this.dataset.id;
            let kode  = this.dataset.kode;
            let url = `{{ env('APP_ROUTE') }}/preview-csv/${kode}/${id}`;

            fetch("{{ route('log.preview') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ id: id, kode: kode })
            });

            csvContainer.innerHTML = "<p class='p-3'>Loading CSV...</p>";

            fetch(url)
                .then(res => {
                    if (!res.ok) throw new Error("Gagal ambil CSV");
                    return res.json();
                })
                .then(data => {
                    fullData = data;
                    fullDataBackup = data;
                    currentPage = 1;
                    renderTable();
                })
                .catch(err => {
                    csvContainer.innerHTML = "<p class='text-danger p-3'>Gagal load CSV</p>";
                    console.error(err);
                });

            csvModal.show();
        });
    });

    csvModalEl.addEventListener('hidden.bs.modal', function () {
        csvContainer.innerHTML = "";
        fullData = [];
        fullDataBackup = [];
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );

    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

});

document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll(".btn-diagram");
    const modalEl = document.getElementById('diagramModal');
    const modal = new bootstrap.Modal(modalEl);
    let chartInstance = null;
    let currentJson = [];
    const xSelect = document.getElementById("xAxis");
    const ySelect = document.getElementById("yAxis");
    const renderBtn = document.getElementById("renderChart");
    const chartAlert = document.getElementById("chartAlert");
    function showAlert(message){

        chartAlert.classList.remove('d-none');
        chartAlert.innerHTML = `
            <i class="fa fa-circle-exclamation me-2"></i>
            ${message}
        `;
        setTimeout(() => {
            chartAlert.classList.add('d-none');
        }, 3000);
    }

    function initAxisOptions(data) {
        if (!data || !data.length) return;
        const keys = Object.keys(data[0]);
        xSelect.innerHTML = '<option value="">Pilih Sumbu X</option>';
        ySelect.innerHTML = '<option value="">Pilih Sumbu Y</option>';
        keys.forEach(k => {
            xSelect.innerHTML += `<option value="${k}">${k}</option>`;
            ySelect.innerHTML += `<option value="${k}">${k}</option>`;
        });
    }

    buttons.forEach(btn => {
        btn.addEventListener("click", async function () {
            let url = this.dataset.url;
            try {
                const res = await fetch(url);
                const data = await res.json();
                if (!Array.isArray(data) || !data.length) {
                    showAlert("Data diagram kosong");
                    return;
                }
                currentJson = data;
                initAxisOptions(data);
                xSelect.value = "";
                ySelect.value = "";
                if (chartInstance) {
                    chartInstance.destroy();
                    chartInstance = null;
                }
                modal.show();
            } catch (err) {
                console.error(err);
                showAlert("Gagal memuat diagram");
            }
        });

    });

    renderBtn.addEventListener("click", function () {
        let xKey = xSelect.value;
        let yKey = ySelect.value;
        if (!xKey || !yKey) {
            showAlert("Silakan pilih sumbu X dan Y terlebih dahulu");
            return;
        }
        const labels = currentJson.map(row => row[xKey] ?? '-');
        const values = currentJson.map(row => parseFloat(row[yKey]) || 0);
        const ctx = document.getElementById('chartCanvas').getContext('2d');
        if (chartInstance) {
            chartInstance.destroy();
        }
        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: yKey,
                    data: values,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: true
                    }
                },

                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

    });
    
    const downloadBtn = document.getElementById("downloadChart");
    downloadBtn.addEventListener("click", function () {
        if (!chartInstance) {
            showAlert("Diagram belum dibuat");
            return;
        }
        const canvas = document.getElementById("chartCanvas");
        const image = canvas.toDataURL("image/png");
        const link = document.createElement("a");
        link.href = image;
        link.download = "diagram-data.png";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

    });

});


</script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const previewBtns = document.querySelectorAll(".preview-btn");
    const modalEl = document.getElementById('filePreviewModal');
    const modal = new bootstrap.Modal(modalEl);

    const frame = document.getElementById("filePreviewFrame");
    const container = document.getElementById("fileHtmlContainer");
    const loader = document.getElementById("excelLoader");

    previewBtns.forEach(btn => {

        btn.addEventListener("click", async function () {

            let url  = this.dataset.url;
            let type = this.dataset.type || '';

            frame.style.display = "none";
            container.style.display = "none";
            loader.style.display = "none";

            frame.src = "";
            container.innerHTML = "";

            // IMAGE
            if (type === 'image') {

                container.style.display = "block";

                container.innerHTML = `
                    <div class="text-center">
                        <img src="${url}" 
                             class="img-fluid rounded shadow-sm" 
                             style="max-height:500px;">
                    </div>
                `;

                modal.show();
                return;
            }

            // PDF
            if (type === 'pdf') {

                frame.style.display = "block";

                frame.src = url;

                modal.show();
                return;
            }

            // EXCEL / CSV
            loader.style.display = "block";

            try {

                const res = await fetch(url);
                const data = await res.arrayBuffer();

                const wb = XLSX.read(data, { type: "array" });

                const sheet = wb.SheetNames[0];

                const json = XLSX.utils.sheet_to_json(
                    wb.Sheets[sheet],
                    { header: 1 }
                );

                loader.style.display = "none";
                container.style.display = "block";

                renderExcelTable(json);

            } catch (e) {

                loader.style.display = "none";

                container.style.display = "block";

                container.innerHTML = `
                    <div class="alert alert-danger">
                        Gagal memuat file
                    </div>
                `;
            }

            modal.show();

        });

    });

});
document.addEventListener("DOMContentLoaded", function () {

    const embedBtns = document.querySelectorAll(".preview-embed-btn");

    const modalEl = document.getElementById('embedModal');
    const modal = new bootstrap.Modal(modalEl);

    const frame = document.getElementById("embedFrame");

    embedBtns.forEach(btn => {
        btn.addEventListener("click", function () {

            let embed = this.dataset.embed;

            frame.src = "";
            frame.srcdoc = "";
            if (!embed.includes("<")) {

                if (embed.includes("youtube.com/watch?v=")) {
                    embed = embed.replace("watch?v=", "embed/");
                }

                frame.src = embed;
            }
            else {
                frame.srcdoc = embed;
            }

            modal.show();
        });
    });

});
</script>

@include('layouts.footer-detail')
