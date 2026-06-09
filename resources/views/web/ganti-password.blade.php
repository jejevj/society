@include('layouts.header-v2')

<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
    <div class="container py-10">
        <form id="actForm" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-4 mb-5">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center p-8">
                            <div class="symbol symbol-100px mx-auto mb-5">
                                @if(!empty($detail->file_identitas_user))
                                    <img src="{{ url('storage/'.$detail->file_identitas_user) }}"
                                         class="rounded-circle object-fit-cover"
                                         style="width:100px;height:100px;">
                                @else
                                    <div class="symbol-label bg-light-primary">
                                        <i class="fa-solid fa-user fs-1 text-detail"></i>
                                    </div>
                                @endif
                            </div>
                            <h3 class="fw-bold mb-1">
                                {{ $detail->nama_user }}
                            </h3>
                            <div class="text-muted mb-4">
                                {{ $detail->username_user }}
                            </div>
                            <div class="badge badge-light-success">
                                Active Member
                            </div>
                            <hr>
                            <div class="text-start">
                                <div class="mb-3">
                                    <small class="text-muted d-block">
                                        Identity Number
                                    </small>
                                    <strong>
                                        {{ $detail->identitas_user ?: '-' }}
                                    </strong>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted d-block">
                                        Phone Number
                                    </small>

                                    <strong>
                                        {{ $detail->telepon_user ?: '-' }}
                                    </strong>
                                </div>
								<hr>

								<a href="{{ route('profile-user') }}" class="btn btn-light-warning w-100">
									<i class="fa-solid fa-key me-2"></i>
									Change Profile
								</a>
                            </div>
                        </div>
                    </div>
					
                </div>

                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
						<div class="card-header border-0 pt-6">
							<div>
								<h2 class="fw-bold mb-1">
									Change Password
								</h2>
								<div class="text-muted">
									Update your account password
								</div>
							</div>
						</div>

						<div class="card-body">
							<form id="actForm">
								@csrf
								<div class="row">
									<div class="col-md-12 mb-4">
										<label class="form-label fw-semibold">
											Current Password
										</label>
										<input type="password"
											name="old_password"
											class="form-control form-control-lg">
									</div>
									<div class="col-md-12 mb-4">
										<label class="form-label fw-semibold">
											New Password
										</label>
										<input type="password"
											name="new_password"
											class="form-control form-control-lg">
									</div>
									<div class="col-md-12 mb-4">
										<label class="form-label fw-semibold">
											Confirm New Password
										</label>
										<input type="password"
											name="confirm_password"
											class="form-control form-control-lg">
									</div>
								</div>
								<button type="button"
										id="btn-save"
										class="btn btn-warning">
									<i class="fa-solid fa-key me-2"></i>
									Update Password
								</button>
							</form>
						</div>
					</div>
                </div>

            </div>

        </form>

    </div>
</div>

		<script>
			$.ajaxSetup({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
			});

			$('#btn-save').on('click', function (e) {
				e.preventDefault();
				let formData = new FormData($('#actForm')[0]);
				formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

				$.ajax({
					url: "{{ route('updatePasswordUserAction') }}",
					type: 'POST',
					data: formData,
					contentType: false,
					processData: false,
					success: function (response) {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: response.message
						}).then(() => {
							location.reload();
						});
					},
					error: function (xhr) {
						let message = 'Terjadi kesalahan.';
						if (xhr.responseJSON && xhr.responseJSON.message) {
							message = xhr.responseJSON.message;
						}

						Swal.fire({
							icon: 'error',
							title: 'Gagal',
							text: message
						});
					}
				});
			});

			
			
		</script>
		

@include('layouts.footer-v2')
