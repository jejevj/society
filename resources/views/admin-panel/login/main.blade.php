
@include('admin-panel.layouts.header-login')
				<div class="app-wrapper flex-column flex-row-fluid mt-100" id="kt_app_wrapper">
					<div class="container-xxl">
						<div class="d-flex flex-column flex-column-fluid align-items-center min-vh-100">
							<div class="mw-480">
								<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
									<div class="card-body p-8 bg-orange">
										<div class="text-center mb-6">
											<img src="{{ asset('images/logo-name.png') }}"
												alt="Logo"
												style="height:60px;" class="mb-3">

											<h2 class="fw-bold text-white mb-1">
												Application Login
											</h2>

											<div class="text-white fs-6">
												Admin Panel
											</div>
										</div>
										<hr>
										<form id="actForm">
											@csrf
											<div class="mb-5">
												<label class="form-label fw-semibold text-white">Username / Email</label>
												<input type="text"
													name="username"
													class="form-control form-control-lg form-control-solid"
													placeholder="Username / Email">
											</div>
											<div class="mb-6">
												<label class="form-label fw-semibold text-white">Password</label>
												<input type="password"
													name="password"
													class="form-control form-control-lg form-control-solid"
													placeholder="Password">
											</div>
											<div class="d-grid mb-4">
												<button class="btn btn-white-submit text-white btn-lg rounded-3 shadow-sm"
														type="submit"
														id="btn-save">
													<i class="fa fa-sign-in-alt me-2 text-white"></i>
													Login
												</button>
											</div>
											<div class="text-center text-white fs-7">
												© 2026 Society Event - Science Bank
											</div>

										</form>

									</div>
								</div>

							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			$(document).ready(function () {
				$("#actForm").on("submit", function (e) {
					e.preventDefault();

					$.ajax({
						url: "{{ route('login-backend-action') }}",
						type: "POST",
						data: $(this).serialize(),
						beforeSend: function () {
							Swal.fire({
								title: "Being processed...",
								text: "Please wait a moment",
								allowOutsideClick: false,
								didOpen: () => {
									Swal.showLoading();
								}
							});
						},
						success: function (res) {
							Swal.close();

							if (res.status) {
								Swal.fire({
									icon: "success",
									title: "Success",
									text: res.message,
									timer: 1500,
									showConfirmButton: false
								}).then(() => {
									if (res.redirect) {
										window.location.href = res.redirect;
									}
								});
							} else {
								Swal.fire({
									icon: "error",
									title: "Login Failed",
									text: res.message
								});
							}
						},
						error: function () {
							Swal.close(); 
							Swal.fire({
								icon: "error",
								title: "Oops...",
								text: "An error occurred, please try again.."
							});
						}
					});
				});
			});


		</script>

@include('admin-panel.layouts.footer')