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

								<a href="{{ route('ganti-password-user') }}"
								class="btn btn-light-warning w-100">

									<i class="fa-solid fa-key me-2"></i>
									Change Password

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
                                    My Profile
                                </h2>

                                <div class="text-muted">
                                    Manage your personal information
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">
                                        Full Name
                                    </label>
                                    <input type="text" name="nama" class="form-control form-control-lg" value="{{ $detail->nama_user }}">
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">
                                        Email Address
                                    </label>
                                    <input type="email" name="username" class="form-control form-control-lg" value="{{ $detail->username_user }}">
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">
                                        ID Number (National ID / Student ID / Employee ID) *
                                    </label>
                                    <input type="text" name="identitas" class="form-control form-control-lg" value="{{ $detail->identitas_user }}">
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">
                                        Phone Number
                                    </label>
                                    <input type="text" name="telepon" class="form-control form-control-lg" value="{{ $detail->telepon_user }}" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">
                                        Occupation
                                    </label>
                                    <input type="text" name="pekerjaan" class="form-control form-control-lg" value="{{ $detail->pekerjaan_user }}">
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">
                                        Address
                                    </label>
                                    <input type="text" name="alamat" class="form-control form-control-lg" value="{{ $detail->alamat_user }}">
                                </div>


								<div class="col-md-6 mb-4">
                                    <label class="form-label">Organization Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $detail->organisasi_user }}" name="organisasi" placeholder="University, company, institution, etc.">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Organization Type <span class="text-danger">*</span></label>
                                    <select name="tipe_organisasi" class="form-select">
                                        <option value="" disabled selected>-- Select type --</option>
                                        <option <?php if($detail->tipe_organisasi_user == 'university'){ echo 'selected'; }?> value="university">University / Academic Institution</option>
                                        <option <?php if($detail->tipe_organisasi_user == 'research_institute'){ echo 'selected'; }?> value="research_institute">Research Institute</option>
                                        <option <?php if($detail->tipe_organisasi_user == 'company'){ echo 'selected'; }?> value="company">Private Company</option>
                                        <option <?php if($detail->tipe_organisasi_user == 'government'){ echo 'selected'; }?> value="government">Government Agency</option>
                                        <option <?php if($detail->tipe_organisasi_user == 'hospital'){ echo 'selected'; }?> value="hospital">Hospital / Medical Institution</option>
                                        <option <?php if($detail->tipe_organisasi_user == 'ngo'){ echo 'selected'; }?> value="ngo">NGO / Non-Profit</option>
                                        <option <?php if($detail->tipe_organisasi_user == 'other'){ echo 'selected'; }?> value="other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Job Title / Profession <span class="text-danger">*</span></label>
                                    <input type="text" name="job_title" class="form-control" value="{{ $detail->job_title_user }}"  placeholder="e.g. Researcher, Lecturer, Director">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        ID Document Scan (JPG/PNG, max 2MB) *
                                    </label>

                                    <div class="border rounded p-4 bg-light">
                                        @if(!empty($detail->file_identitas_user))
                                            <img src="{{ url('storage/'.$detail->file_identitas_user) }}" class="img-fluid rounded mb-3" style="max-height:180px">
                                        @else
                                            <div class="text-muted mb-3">
                                                No identity document uploaded
                                            </div>
                                        @endif
                                        <input type="file" name="foto" accept="image/*" class="form-control">
                                        <small class="text-muted">
                                            JPG, JPEG, PNG (Max 5 MB)
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <button type="submit"
                                    id="btn-save"
                                    class="btn bg-detail text-white px-8">

                                <i class="fa-solid fa-floppy-disk me-2 text-white"></i>
                                Save Changes
                            </button>
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
					url: "{{ route('updateProfilUserAction') }}",
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
