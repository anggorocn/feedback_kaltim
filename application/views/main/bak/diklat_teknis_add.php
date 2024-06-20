<?
$this->load->model("base-validasi/DiklatTeknis");
$this->load->model("base-validasi/HapusData");
$this->load->model("base-personal/Diklat");
// $this->load->model("base-validasi/PendidikanRiwayat");


$reqPegawaiId= $this->input->get('reqPegawaiId');
$reqRowId= $this->input->get('reqRowId');
$reqDetilId= $this->input->get('reqDetilId');
$reqMode= $this->input->get('reqMode');
$reqValidasiHapusId= $this->input->get('reqValidasiHapusId');

$set = new DiklatTeknis();
if($reqMode == 'edit' || $reqMode == 'cancel' || $reqMode == 'view')
{
	if($reqDetilId == "")
	{
		$set->selectByParams(array('DIKLAT_TEKNIS_ID'=>$reqRowId));
	}
	else
	{
		$set->selectByParams(array('TEMP_VALIDASI_ID'=>$reqDetilId));
	}
	// echo $set->query;exit;
	$set->firstRow();
	$reqRowId= $set->getField('DIKLAT_TEKNIS_ID');
	$reqDataId= $set->getField('TEMP_VALIDASI_ID');
	$reqDiklat= $set->getField('DIKLAT_ID');
	$reqNamaDiklat= $set->getField('NAMA');
	$reqTahun= $set->getField('TAHUN');
	$reqNoSTTPP= $set->getField('NO_STTPP');
	$reqPenyelenggara = $set->getField('PENYELENGGARA');
	$reqAngkatan= $set->getField('ANGKATAN');
	$reqTglMulai= dateToPageCheck($set->getField('TANGGAL_MULAI'));
	$reqTglSelesai = dateToPageCheck($set->getField('TANGGAL_SELESAI'));
	$reqTglSTTPP= dateToPageCheck($set->getField('TANGGAL_STTPP'));
	$reqTempat= $set->getField('TEMPAT');
	$reqJumlahJam= $set->getField('JUMLAH_JAM');
	$reqPerubahanData= $set->getField('PERUBAHAN_DATA');
}
unset($set);
if($reqDetilId == "")
{
	$reqMode = 'insert';
}
else
{
	$reqMode = 'update';
}

// print_r($arragama);exit;

?>
<script type="text/javascript" src="functions/globalfunction.js"></script>

<div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
	<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<div class="d-flex align-items-center flex-wrap mr-1">
			<div class="d-flex align-items-baseline flex-wrap mr-5">
				<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">Data Riwayat</a>
					</li>
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">Diklat Teknis </a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
        	<div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="flaticon2-notepad text-primary"></i>
                    </span>
                    <h3 class="card-label">Diklat Teknis</h3>
                </div>
            </div>
            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
	        	<div class="card-body">
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Nama Diklat</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" placeholder="Masukkan Nama Diklat" name="reqNamaDiklat" id="reqNamaDiklat" value="<?=$reqNamaDiklat?>" />
	        			</div>
	        		</div>
	        		
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tempat</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" placeholder="Masukkan Nama Tempat" name="reqTempat" id="reqTempat" value="<?=$reqTempat?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Penyelenggara</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" placeholder="Masukkan Nama Penyelenggara" name="reqPenyelenggara" id="reqPenyelenggara" value="<?=$reqPenyelenggara?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Angkatan</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" placeholder="Masukkan Angkatan" name="reqAngkatan" id="reqAngkatan" value="<?=$reqAngkatan?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tahun</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" maxlength="4" placeholder="Masukkan Tahun" name="reqTahun" id="reqTahun" value="<?=$reqTahun?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">No. STTPP</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control"placeholder="Masukkan No. STTPP" name="reqNoSTTPP" id="reqNoSTTPP" value="<?=$reqNoSTTPP?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tgl Mulai</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control" id="kttglmulai" name="reqTglMulai" readonly="readonly" placeholder="Masukkan Tgl Mulai" value="<?=$reqTglMulai?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tgl. STTPP</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control" id="kttglsttp" name="reqTglSTTPP" readonly="readonly" placeholder="Masukkan Tgl. STTPP" value="<?=$reqTglSTTPP?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tgl Selesai</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control" id="kttglselesai" name="reqTglSelesai" readonly="readonly" placeholder="Masukkan Tgl Selesai" value="<?=$reqTglSelesai?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Jumlah Jam</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control"placeholder="Masukkan Jumlah Jam" name="reqJumlahJam" id="reqJumlahJam" value="<?=$reqJumlahJam?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">File Arsip</label>
	        			<div class="col-lg-4 col-sm-12">
	        			</div>
	        		</div>
	        	</div>
	        	<div class="card-footer">
	        		<div class="row">
	        			<div class="col-lg-9">
	        				<input type="hidden" name="reqMode" value="<?=$reqMode?>">
	        				<input type="hidden" name="reqRowId" value="<?=$reqRowId?>">
	        				<input type="hidden" name="reqDataId" value="<?=$reqDataId?>">
	        				<input type="hidden" name="reqPegawaiId" value="<?=$reqPegawaiId?>">
	        				<input type="hidden" name="reqTipePegawaiId" value="<?=$reqTipePegawaiParentId?>">
	        				<input type="hidden" name="reqRowTipePegawaiId" value="<?=$reqTipePegawaiId?>">
	        				<button type="submit" id="ktloginformsubmitbutton"  class="btn btn-primary font-weight-bold mr-2">Simpan</button>
	        				<button onclick="kembali()" type="button" class="btn btn-warning font-weight-bold mr-2">Kembali</button>
	        			</div>
	        		</div>
	        	</div>
	        </form>
        </div>
    </div>
</div>

<script type="text/javascript">
	$(document).on("input", "#reqJumlahJam,#reqTahun", function() {
		this.value = this.value.replace(/\D/g,'');
	});
	function kembali() {
		window.location.href='app/index/diklat_teknis'
	}
	$('#ktdiklat').select2({
		placeholder: "Pilih Diklat"
	});
	
    arrows= {leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'};
	$('#kttglmulai,#kttglselesai,#kttglsttp').datepicker({
		todayHighlight: true
		, autoclose: true
		, orientation: "bottom left"
		, clearBtn: true
		, format: 'dd-mm-yyyy'
		, templates: arrows
	});
	var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';
	jQuery(document).ready(function() {
		var form = KTUtil.getById('ktloginform');
		var formSubmitUrl = "json-validasi/personal_json/jsondiklatteknisadd";
		var formSubmitButton = KTUtil.getById('ktloginformsubmitbutton');
		if (!form) {
			return;
		}
		FormValidation
		.formValidation(
			form,
			{
				fields: {
					reqNamaDiklat: {
						validators: {
							notEmpty: {
								message: 'Nama Diklat harus diisi'
							}
						}
					},
					reqPenyelenggara: {
						validators: {
							notEmpty: {
								message: 'Nama Penyelenggara harus diisi'
							}
						}
					},
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					submitButton: new FormValidation.plugins.SubmitButton(),
					bootstrap: new FormValidation.plugins.Bootstrap()
				}
			}
			)
		.on('core.form.valid', function() {
				// Show loading state on button
				KTUtil.btnWait(formSubmitButton, _buttonSpinnerClasses, "Please wait");
				var formData = new FormData(document.querySelector('form'));
				$.ajax({
					url: formSubmitUrl,
					data: formData,
					processData: false,
					contentType: false,
					type: 'POST',
					dataType: 'json',
					success: function (response) {
			        	// console.log(response); return false;
			        	// Swal.fire("Good job!", "You clicked the button!", "success");
			        	Swal.fire({
			        		text: response.message,
			        		icon: "success",
			        		buttonsStyling: false,
			        		confirmButtonText: "Ok",
			        		customClass: {
			        			confirmButton: "btn font-weight-bold btn-light-primary"
			        		}
			        	}).then(function() {
			        		document.location.href = "app/index/diklat_teknis";
			        		// window.location.reload();
			        	});
			        },
			        error: function(xhr, status, error) {
			        	// var err = JSON.parse(xhr.responseText);
			        	// Swal.fire("Error", err.message, "error");
			        },
			        complete: function () {
			        	KTUtil.btnRelease(formSubmitButton);
			        }
			    });
			})
		.on('core.form.invalid', function() {
			Swal.fire({
				text: "Sorry, looks like there are some errors detected, please try again.",
				icon: "error",
				buttonsStyling: false,
				confirmButtonText: "Ok",
				customClass: {
					confirmButton: "btn font-weight-bold btn-light-primary"
				}
			}).then(function() {
				KTUtil.scrollTop();
			});
		});
	});
</script>

