
						</div>
                        
                        <div class="card card-flush about-sdp mt-auto">
                            <div class="container py-5 px-10">
                                <div class="row align-items-center">
                                    <div class="col-lg-7 mb-4 mb-lg-0">
                                        <h2 class="about-title">
                                            Satu Data Pertahanan
                                        </h2>
                                        <p class="about-subtitle">
                                            Portal Data Terpadu Kementerian Pertahanan Republik Indonesia 
                                            yang menyajikan data dari seluruh satuan dan unit kerja secara 
                                            terintegrasi, akurat, dan berkelanjutan.
                                        </p>

                                        <a href="{{ route('tentang-kami') }}" class="btn btn-gold-foot mt-3">
                                            Selengkapnya <i class="fa-solid fa-arrow-right ms-1 text-white"></i>
                                        </a>
                                    </div>
                                    <div class="col-lg-5">
                                        <div class="about-box d-flex align-items-center">

                                            <div class="me-4 text-center">
                                                <img src="{{ env('ASSET_URL') }}logo/logo-kemhan-jpg.jpg"
                                                    alt="Logo Kemhan"
                                                    class="about-logo">
                                            </div>

                                            <div>
                                                <h5 class="fw-bold text-white mb-2">
                                                    Kementerian Pertahanan RI
                                                </h5>

                                                <p class="text-white-50 small mb-2">
                                                    Telp: (021) 81234567890
                                                </p>

                                                <div class="d-flex gap-3 about-social">
                                                    @if(!empty($set->url_facebook))
                                                        <a href="{{ $set->url_facebook }}"
                                                        target="_blank"
                                                        aria-label="Facebook Kementerian Pertahanan RI">
                                                            <i class="fab fa-facebook" aria-hidden="true"></i>
                                                        </a>
                                                    @endif
                                                    @if(!empty($set->url_instagram))
                                                        <a href="{{ $set->url_instagram }}"
                                                        target="_blank"
                                                        aria-label="Instagram Kementerian Pertahanan RI">
                                                            <i class="fab fa-instagram" aria-hidden="true"></i>
                                                        </a>
                                                    @endif
                                                    @if(!empty($set->url_youtube))
                                                        <a href="{{ $set->url_youtube }}"
                                                        target="_blank"
                                                        aria-label="YouTube Kementerian Pertahanan RI">
                                                            <i class="fab fa-youtube" aria-hidden="true"></i>
                                                        </a>
                                                    @endif
                                                    @if(!empty($set->url_linkedin))
                                                        <a href="{{ $set->url_linkedin }}"
                                                        target="_blank"
                                                        aria-label="LinkedIn Kementerian Pertahanan RI">
                                                            <i class="fab fa-linkedin" aria-hidden="true"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
					</div>
                    
				</div>
			</div>
		</div>
@include('layouts.feedback')




</script>
		<script>var hostUrl = "assets/";</script>
        <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
        <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
        <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
        <script src="{{ asset('assets/js/custom/apps/ecommerce/sales/listing.js') }}"></script>
        <script src="{{ asset('assets/js/widgets.bundle.js') }}"></script>
        <script src="{{ asset('assets/js/custom/widgets.js') }}"></script>
        <!-- <script src="{{ asset('assets/js/custom/apps/chat/chat.js') }}"></script> -->

        <script src="{{ asset('assets/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
        <script src="{{ asset('assets/js/custom/utilities/modals/users-search.js') }}"></script>

	</body>
</html>