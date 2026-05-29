                                                        <div class="col-lg-12 my-2">
                                                            <div class="my-3">
                                                                <div class="card shadow-sm">
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            
                                                                            <div class="mb-7">
                                                                                <h4 class="fw-bold mb-2">Jenis</h4>
                                                                                <div class="filter-scroll">
                                                                                    <div class="form-check mb-2">
                                                                                        <input class="form-check-input" type="checkbox" value="semua" id="filterTipeSemua" {{ empty($f_tipe) ? 'checked' : '' }}>
                                                                                        <label class="form-check-label">Semua</label>
                                                                                    </div>

                                                                                    @foreach($list_tipe as $l)
                                                                                    <div class="form-check mb-2">
                                                                                        <input class="form-check-input" type="checkbox"
                                                                                            value="{{ $l->kode_status }}"
                                                                                            id="filterTipe{{ $l->kode_status }}"
                                                                                            {{ in_array($l->kode_status, (array) $f_tipe) ? 'checked' : '' }}>
                                                                                        <label class="form-check-label">{{ $l->keterangan_status }}</label>
                                                                                    </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                            <div class="mb-5">
                                                                                <h4 class="fw-bold mb-2">Status</h4>
                                                                                <div class="form-check mb-2">
                                                                                    <input class="form-check-input" type="checkbox" value="semua" id="filterSifatSemua" {{ empty($f_status) ? 'checked' : '' }}>
                                                                                    <label class="form-check-label">Semua</label>
                                                                                </div>

                                                                                <div class="form-check mb-2">
                                                                                    <input class="form-check-input" type="checkbox" value="TERBUKA" id="filterSifatTerbuka"
                                                                                        {{ in_array('TERBUKA', (array) $f_status) ? 'checked' : '' }}>
                                                                                    <label class="form-check-label">Terbuka</label>
                                                                                </div>

                                                                                <div class="form-check mb-2">
                                                                                    <input class="form-check-input" type="checkbox" value="TERBATAS" id="filterSifatTerbatas"
                                                                                        {{ in_array('TERBATAS', (array) $f_status) ? 'checked' : '' }}>
                                                                                    <label class="form-check-label">Terbatas</label>
                                                                                </div>

                                                                            </div>
                                                                            <div class="mb-5">
                                                                                <h4 class="fw-bold mb-2">Organisasi</h4>
                                                                                <div class="filter-scroll">
                                                                                    <div class="form-check mb-2">
                                                                                        <input class="form-check-input" type="checkbox" value="semua" id="filterOrgSemua" {{ empty($f_organisasi) ? 'checked' : '' }}>
                                                                                        <label class="form-check-label">Semua</label>
                                                                                    </div>

                                                                                    @foreach($list_organisasi as $l)
                                                                                    <div class="form-check mb-2">
                                                                                        <input class="form-check-input" type="checkbox"
                                                                                            value="{{ $l->id_organisasi }}"
                                                                                            id="filterOrg{{ $l->id_organisasi }}"
                                                                                            {{ in_array($l->id_organisasi, (array) $f_organisasi) ? 'checked' : '' }}>
                                                                                        <label class="form-check-label">{{ $l->nama_organisasi }}</label>
                                                                                    </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>

                                                                            <div class="mb-5">
                                                                                <h4 class="fw-bold mb-2">Topik</h4>
                                                                                <div class="filter-scroll">
                                                                                    <div class="form-check mb-2">
                                                                                        <input class="form-check-input" type="checkbox" value="semua" id="filterTopikSemua" {{ empty($f_topik) ? 'checked' : '' }}>
                                                                                        <label class="form-check-label">Semua</label>
                                                                                    </div>

                                                                                    @foreach($list_topik as $l)
                                                                                    <div class="form-check mb-2">
                                                                                        <input class="form-check-input" type="checkbox"
                                                                                            value="{{ $l->id_topik }}"
                                                                                            id="filterTopik{{ $l->id_topik }}"
                                                                                            {{ in_array($l->id_topik, (array) $f_topik) ? 'checked' : '' }}>
                                                                                        <label class="form-check-label">{{ $l->nama_topik }}</label>
                                                                                    </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>

                                                                            

                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <script>
                                                        document.addEventListener("DOMContentLoaded", function() {
                                                            document.getElementById("btnSearch").addEventListener("click", function(e) {
                                                                e.preventDefault();
                                                                updateUrlFilter();
                                                            });
                                                            document.querySelectorAll(".form-check-input").forEach(function(checkbox) {
                                                                checkbox.addEventListener("change", function() {
                                                                    handleCheckboxChange(this);
                                                                });
                                                            });

                                                            function handleCheckboxChange(checkbox) {
                                                                const id = checkbox.id;

                                                                if (id.includes("Semua")) {
                                                                    let group = id.replace("Semua", "");
                                                                    document.querySelectorAll("input[id^=" + group + "]").forEach(cb => {
                                                                        if (cb.id !== id) cb.checked = false;
                                                                    });
                                                                } else {
                                                                    let group = id.replace(/[^A-Za-z]/g, ""); 
                                                                    let semua = document.getElementById("filter" + group + "Semua");
                                                                    if (semua) semua.checked = false;
                                                                }

                                                                updateUrlFilter();
                                                            }

                                                            function updateUrlFilter() {
                                                                let params = new URLSearchParams();

                                                                let searchVal = document.getElementById("searchInput").value.trim();
                                                                if (searchVal) {
                                                                    params.set("search", searchVal);
                                                                }

                                                                document.querySelectorAll("input[id^=filterTipe]:checked").forEach(cb => {
                                                                    if (cb.value !== "semua") params.append("tipe[]", cb.value);
                                                                });

                                                                document.querySelectorAll("input[id^=filterOrg]:checked").forEach(cb => {
                                                                    if (cb.value !== "semua") params.append("org[]", cb.value);
                                                                });

                                                                document.querySelectorAll("input[id^=filterTopik]:checked").forEach(cb => {
                                                                    if (cb.value !== "semua") params.append("topik[]", cb.value);
                                                                });

                                                                document.querySelectorAll("input[id^=filterSifat]:checked").forEach(cb => {
                                                                    if (cb.value !== "semua") params.append("status[]", cb.value);
                                                                });

                                                                window.location.search = params.toString();
                                                            }
                                                        });
                                                        </script>