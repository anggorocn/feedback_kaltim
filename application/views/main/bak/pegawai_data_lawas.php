
<div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
	<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<div class="d-flex align-items-center flex-wrap mr-1">
			<div class="d-flex align-items-baseline flex-wrap mr-5">
				<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">Data Pegawai</a>
					</li>
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">Identitas Pegawai</a>
					</li>
				</ul>
			</div>
		</div>

	</div>
</div>	<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
	<!--begin::Container-->
	<div class="container">
		<!--begin::Notice-->	
		<div class="row">
			<div class="col-lg-6">
				<!--begin::Card-->
				<div class="card card-custom example example-compact">
					<div class="card-header">
						<h3 class="card-title">Identitas Pegawai</h3>
						<div class="card-toolbar">
							<div class="example-tools justify-content-center">
								<span class="example-toggle" data-toggle="tooltip" title="View code"></span>
							</div>
						</div>
					</div>
					<!--begin::Form-->
					<form class="form" id="kt_form_1" method="POST" enctype="multipart/form-data">
						<div class="card-body">
							<div class="form-group row">
								<label class="col-form-label text-right col-lg-3 col-sm-12">NIP </label>
								<div class="form-group col-xs-3 col-xs-offset-1">
									<input type="text" style="width:170px;" class="form-control" placeholder="Masukkan NIP lama"name="reqNIP1" id="reqNIP1" />
								</div>

								<div class="form-group col-xs-3">
									<input type="text" style="width:170px;" class="form-control" placeholder="Masukkan NIP baru"name="reqNIP1" id="reqNIP1" />
								</div>
							</div>
							<div class="form-group row">
								<label class="col-form-label text-right col-lg-3 col-sm-12">Nama *</label>
								<div class="col-lg-4 col-sm-12">
									<input type="text" class="form-control" name="email" placeholder="Masukkan nama anda" />
								</div>
							</div>
							<div class="form-group row">
								<label class="col-form-label text-right col-lg-3 col-sm-12">Gelar Depan </label>
								<div class="form-group col-xs-1 col-xs-offset-1">
									<input type="text" style="width:110px;" class="form-control" name="reqNIP1" id="reqNIP1" />
								</div>
								<label class="col-form-label text-right col-lg-3 col-sm-12">Gelar Belakang</label>

								<div class="form-group col-xs-3">
									<input type="text" style="width:110px;" class="form-control"name="reqNIP1" id="reqNIP1" />
								</div>
							</div>
							<div class="form-group row">
								<label class="col-form-label text-right col-lg-3 col-sm-12">Tempat Lahir </label>
								<div class="form-group col-xs-1 col-xs-offset-1">
									<input type="text" style="width:110px;" class="form-control" name="reqTempatLahir" id="reqTempatLahir" />
								</div>
								<label class="col-form-label text-right col-lg-3 col-sm-12">Tanggal Lahir</label>
								<div class="form-group col-xs-1 col-xs-offset-1">
									<div class="input-group date">
										<input type="text" class="form-control" id="kttanggallahir" name="reqTanggalLahir" readonly="readonly" placeholder="Select date" value="02-05-2021" />
										<div class="input-group-append">
											<span class="input-group-text">
												<i class="la la-calendar"></i>
											</span>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-form-label text-right col-lg-3 col-sm-12">US Phone</label>
								<div class="col-lg-9 col-md-9 col-sm-12">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">
												<i class="flaticon2-phone"></i>
											</span>
										</div>
										<input type="text" class="form-control" name="phone" placeholder="Enter phone" />
									</div>
									<span class="form-text text-muted">Please enter your US phone number</span>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-form-label text-right col-lg-3 col-sm-12">Option *</label>
								<div class="col-lg-9 col-md-9 col-sm-12">
									<select class="form-control" name="option">
										<option value="">Select</option>
										<option>1</option>
										<option>2</option>
										<option>3</option>
										<option>4</option>
										<option>5</option>
									</select>
									<span class="form-text text-muted">Please select an option.</span>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-form-label text-right col-lg-3 col-sm-12">Options *</label>
								<div class="col-lg-9 col-md-9 col-sm-12">
									<select class="form-control" name="options" multiple="multiple" size="7">
										<option>Option 1</option>
										<option>Option 2</option>
										<option>Option 3</option>
										<option>Option 4</option>
										<option>Option 5</option>
										<option>Option 6</option>
										<option>Option 7</option>
									</select>
									<span class="form-text text-muted">Please select at least 2 or maximum 5 options</span>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-form-label text-right col-lg-3 col-sm-12">Memo *</label>
								<div class="col-lg-9 col-md-9 col-sm-12">
									<textarea class="form-control" name="memo" placeholder="Enter a menu" rows="3"></textarea>
									<span class="form-text text-muted">Please enter a menu within text length range 50 and 100.</span>
								</div>
							</div>
							<div class="card-footer">
								<div class="row">
									<div class="col-lg-9 ml-lg-auto">
										<button type="submit" class="btn btn-primary font-weight-bold mr-2" name="submitButton">Validate</button>
										<button type="reset" class="btn btn-light-primary font-weight-bold">Cancel</button>
									</div>
								</div>
							</div>
						</div>
						<!-- -- end kiri -->
					</form>
					<!--end::Form-->
				</div>
				<!--end::Card-->
			</div>
			<!--end::Container-->
			<div class="col-lg-6">
				<div class="card card-custom example example-compact">
					<div class="card-header">
						<h3 class="card-title"></h3>
						<div class="card-toolbar">
							<div class="example-tools justify-content-center">
								<span class="example-toggle" data-toggle="tooltip" title="View code"></span>
							</div>
						</div>
					</div>
					<!--begin::Form-->
					<form class="form" id="kt_form_2">
						<div class="card-body">
							<div class="form-group row">
								<label class="col-form-label text-right col-lg-3 col-sm-12">Email *</label>
								<div class="col-lg-9 col-md-9 col-sm-12">
									<input type="text" class="form-control" name="email" placeholder="Enter your email" />
									<span class="form-text text-muted">We'll never share your email with anyone else.</span>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!--end::Entry-->
	</div>
</div>
<!--end::Content-->

				

		
<!--begin::Global Theme Bundle(used by all pages)-->
<script src="assets/plugins/global/plugins.bundle.js"></script>
<script src="assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
<script src="assets/js/scripts.bundle.js"></script>
<!--end::Global Theme Bundle-->
<!--begin::Page Scripts(used by this page)-->
<script src="assets/js/pages/crud/forms/validation/form-controls.js"></script>
