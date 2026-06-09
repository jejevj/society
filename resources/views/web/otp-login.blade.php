@include('layouts.header-v2')
				<div class="app-wrapper flex-column flex-row-fluid mt-150" id="kt_app_wrapper">
					<div class="container-xxl">
						<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
							<div class="d-flex flex-column flex-column-fluid mb-140">
								<div id="kt_app_content" class="app-content">
									<div class="card card-flush mb-10">
                                        <div class="card border-0">
											<div class="card-body d-block text-start">
												<div class="row">
													<div class="col-12">
														<h2 class="list-title mb-2">
															OTP Verification
														</h2>
													</div>
												</div>
											</div>
										</div>
                                        <style>
                                            .otp-input{
                                                width:55px;
                                                height:55px;
                                                text-align:center;
                                                font-size:24px;
                                                font-weight:600;
                                            }
                                        </style>
										<div class="h-xl-100 mt-10 mb-4">
											<div class="mb-4">
												<div class="row justify-content-center">
                                                    <div class="col-lg-5 col-md-7 col-sm-10">
                                                        <div class="card card-flush shadow-sm border-0">
                                                            <div class="card-body py-15 px-10">
                                                                <form method="POST" id="actForm">
                                                                    @csrf
                                                                    
                                                                    <input type="hidden" name="key" value="{{ $key }}">
                                                                    <input type="hidden" name="otp" id="otp">
                                                                    <div class="text-center mb-8">
                                                                        <div class="symbol symbol-80px mx-auto mb-5">
                                                                            <div class="symbol-label bg-light-primary">
                                                                                <i class="fa fa-shield-halved fs-1 text-primary"></i>
                                                                            </div>
                                                                        </div>
                                                                        <h2 class="fw-bold mb-3">
                                                                            OTP Verification
                                                                        </h2>
                                                                        <div class="text-muted fs-6">
                                                                            Enter the 6-digit verification code sent to your email
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex justify-content-center gap-3 mb-8">
                                                                        <input type="text" maxlength="1" class="otp-input form-control">
                                                                        <input type="text" maxlength="1" class="otp-input form-control">
                                                                        <input type="text" maxlength="1" class="otp-input form-control">
                                                                        <input type="text" maxlength="1" class="otp-input form-control">
                                                                        <input type="text" maxlength="1" class="otp-input form-control">
                                                                        <input type="text" maxlength="1" class="otp-input form-control">
                                                                    </div>
                                                                    <button type="submit"
                                                                        class="btn btn-marron-monitoring text-white w-100 h-50px">
                                                                        Verify OTP
                                                                    </button>

                                                                </form>

                                                            </div>
                                                        </div>
													</div>
                                                </div>
											</div>
										</div>											
									</div>
								</div>
							</div>
                            <script>
                                $.ajaxSetup({
                                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                                });

                                $('#actForm').on('submit', function (e) {
                                    e.preventDefault();
                                     if ($('#otp').val().length !== 6) {
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'OTP Incomplete',
                                            text: 'Please enter all 6 OTP digits'
                                        });
                                        return;
                                    }

                                    let formData = new FormData(this);

                                    $.ajax({
                                        url: "{{ route('verifyOtpAction') }}",
                                        type: 'POST',
                                        data: formData,
                                        contentType: false,
                                        processData: false,
                                        success: function (response) {
                                            if (response.status) {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Success',
                                                    text: response.message
                                                }).then(() => {
                                                    window.location.href = "{{ env('APP_ROUTE') }}";
                                                });
                                            } else {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Failed',
                                                    text: response.message
                                                });
                                            }
                                        },
                                        error: function (xhr) {
                                            let message = 'Errors.';
                                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                                message = xhr.responseJSON.message;
                                            }

                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Failed',
                                                text: message
                                            });
                                        }
                                    });
                                });

                                $('.otp-input:first').focus();

                                function updateOtpValue() {
                                    let otp = '';

                                    $('.otp-input').each(function () {
                                        otp += $(this).val();
                                    });

                                    $('#otp').val(otp);
                                }

                                $('.otp-input').on('input', function () {

                                    // hanya angka
                                    this.value = this.value.replace(/[^0-9]/g, '');

                                    updateOtpValue();

                                    // pindah ke input berikutnya
                                    if ($(this).val().length === 1) {
                                        $(this).next('.otp-input').focus();
                                    }

                                    
                                });

                                $('.otp-input').on('keydown', function (e) {

                                    if (e.key === 'Backspace') {

                                        if ($(this).val() === '') {
                                            $(this).prev('.otp-input').focus();
                                        }

                                    }
                                });

                                $('.otp-input').on('paste', function (e) {

                                    let pastedData = (e.originalEvent || e)
                                        .clipboardData
                                        .getData('text')
                                        .replace(/\D/g, '');

                                    if (pastedData.length > 0) {

                                        $('.otp-input').each(function (index) {
                                            $(this).val(pastedData[index] || '');
                                        });

                                        updateOtpValue();

                                        let lastFilled = $('.otp-input').filter(function () {
                                            return $(this).val() !== '';
                                        }).last();

                                        if (lastFilled.length) {
                                            lastFilled.focus();
                                        }

                                        e.preventDefault();

                                    }
                                });
                            </script>
							

@include('layouts.footer-v2')
