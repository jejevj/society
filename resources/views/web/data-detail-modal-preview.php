            <div class="modal fade" id="csvPreviewModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Preview</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            <div id="csvPreviewContainer" style="max-height:600px; overflow:auto; padding:20px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="filePreviewModal" tabindex="-1">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Preview</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-0">
                            <iframe id="filePreviewFrame" style="width:100%;height:500px;"></iframe>
                            <div id="fileHtmlContainer" style="display:none; padding:10px;"></div>
                            <div id="excelLoader" style="display:none; text-align:center; padding:40px;">
                                <div class="spinner-border text-primary" role="status"></div>
                                <div class="mt-2">Memuat file...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="diagramModal" tabindex="-1">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg overflow-hidden p-4">
                        <div class="modal-header border-0 py-4 px-6">
                            <div>
                                <h4 class="modal-title fw-bold mb-1">
                                    Diagram Data
                                </h4>
                                <div class="small">
                                    Visualisasi data interaktif
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="bg-light border-bottom px-4 py-3">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-muted">
                                        Sumbu X
                                    </label>
                                    <select id="xAxis" class="form-select form-select-lg">
                                        <option value="">Pilih Sumbu X</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-muted">
                                        Sumbu Y
                                    </label>
                                    <select id="yAxis" class="form-select form-select-lg">
                                        <option value="">Pilih Sumbu Y</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-marron w-100 btn-lg" id="renderChart"> <i class="fa fa-chart-column me-2 text-white"></i> Tampilkan </button>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-secondary border w-100 btn-lg" id="downloadChart">
                                        <i class="fa fa-download me-2"></i>
                                        Unduh
                                    </button>
                                </div>
                            </div>
                            <div id="chartAlert"
                                class="alert alert-warning mt-3 d-none mb-0">
                            </div>
                        </div>
                        <div class="modal-body p-4 bg-white">
                            <div class="chart-wrapper">
                                <canvas id="chartCanvas"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="embedModal" tabindex="-1">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Preview</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body p-0">
                            <iframe id="embedFrame"
                                style="width:100%;height:600px;border:0;">
                            </iframe>
                        </div>

                    </div>
                </div>
            </div>