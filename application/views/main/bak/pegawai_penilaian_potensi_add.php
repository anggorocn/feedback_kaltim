<?
include_once("functions/personal.func.php");
$this->load->model("base-validasi/PotensiDiri");
$this->load->model("base-validasi/HapusData");


$reqPegawaiId= $this->input->get('reqPegawaiId');
$reqRowId= $this->input->get('reqRowId');
$reqDetilId= $this->input->get('reqDetilId');
$reqMode= $this->input->get('reqMode');
$reqValidasiHapusId= $this->input->get('reqValidasiHapusId');

$adminuserid= $this->adminuserid;

$set = new PotensiDiri();
if($reqMode == 'edit' || $reqMode == 'cancel' || $reqMode == 'view')
{
	if($reqDetilId == "")
	{
		$set->selectByParams(array('POTENSI_DIRI_ID'=>$reqRowId));
	}
	else
	{
		$set->selectByParams(array('TEMP_VALIDASI_ID'=>$reqDetilId));
	}
	// echo $set->query;exit;
	$set->firstRow();
	$reqPerubahanData= $set->getField('PERUBAHAN_DATA');
	$reqRowId= $set->getField('POTENSI_DIRI_ID');
	$reqDataId= $set->getField('TEMP_VALIDASI_ID');
	$reqTahun= $set->getField('TAHUN');$valTahun= checkwarna($reqPerubahanData, 'TAHUN');
	$reqTanggungJawab= $set->getField('TANGGUNG_JAWAB');$valTanggungJawab= checkwarna($reqPerubahanData, 'TANGGUNG_JAWAB');
	$reqMotivasi= $set->getField('MOTIVASI');$valMotivasi= checkwarna($reqPerubahanData, 'MOTIVASI');
	$reqMinat= $set->getField('MINAT');$valMinat= checkwarna($reqPerubahanData, 'MINAT');
	$reqValidasi= $set->getField('VALIDASI');
	
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

?>
<script type="text/javascript" src="functions/globalfunction.js"></script>

<div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
	<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<div class="d-flex align-items-center flex-wrap mr-1">
			<div class="d-flex align-items-baseline flex-wrap mr-5">
				<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">Lain Lain</a>
					</li>
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">Penilaian Potensi Diri</a>
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
                    <h3 class="card-label">Penilaian Potensi Diri</h3>
                </div>
            </div>
            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
	        	<div class="card-body">
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tahun
	        			<?
	        			if(!empty($valTahun['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTahun['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valTahun['warna']?>" placeholder="Masukkan Tahun"name="reqTahun" id="reqTahun" value="<?=$reqTahun?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tanggung Jawab
	        			<?
	        			if(!empty($valTanggungJawab['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTanggungJawab['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<textarea  class="form-control <?=$valTanggungJawab['warna']?>" name="reqTanggungJawab"><?=$reqTanggungJawab?></textarea>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Motivasi
	        			<?
	        			if(!empty($valMotivasi['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valMotivasi['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<textarea  class="form-control <?=$valMotivasi['warna']?>" name="reqMotivasi"><?=$reqMotivasi?></textarea>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Minat Bidang Tugas
	        			<?
	        			if(!empty($valMinat['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valMinat['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<textarea  class="form-control <?=$valMinat['warna']?>" name="reqMinat"><?=$reqMinat?></textarea>
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
	        				<?
	        				if(!empty($adminuserid) && empty($reqValidasi))
	        				{
	        				?>
	        					<button type="submit" id="ktloginformsubmitbutton"  name="reqStatusValidasi"  class="btn btn-primary font-weight-bold mr-2" value="1">Validasi</button>
	        					<button type="submit" id="ktloginformsubmitbutton" name="reqStatusValidasi"  class="btn btn-danger font-weight-bold mr-2" value="0">Tolak</button>
	        					<button onclick="kembali()" type="button" class="btn btn-warning font-weight-bold mr-2">Kembali</button>
	        				<?
	        				}
	        				else if(!empty($adminuserid) && !empty($reqValidasi))
	        				{
	        				?>
	        					<button onclick="kembali()" type="button" class="btn btn-warning font-weight-bold mr-2">Kembali</button>
		        				
	        				<?
	        				}
	        				else
	        				{
	        				?>
		        				<button type="submit" id="ktloginformsubmitbutton"  class="btn btn-primary font-weight-bold mr-2">Simpan</button>
		        				<button onclick="kembali()" type="button" class="btn btn-warning font-weight-bold mr-2">Kembali</button>
	        				<?
	        				}
	        				?>
	        			</div>
	        		</div>
	        	</div>
	        </form>
        </div>
    </div>
</div>

<script type="text/javascript">
	$(document).on("input", "#reqTahun", function() {
		this.value = this.value.replace(/\D/g,'');
	});
	function kembali() {
		window.location.href='app/index/pegawai_penilaian_potensi'
	}
	
	var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';
	jQuery(document).ready(function() {
		var form = KTUtil.getById('ktloginform');
		var formSubmitUrl = "json-validasi/personal_json/jsonpegawaipenilaianpotensiadd";
		var formSubmitButton = KTUtil.getById('ktloginformsubmitbutton');
		if (!form) {
			return;
		}
		FormValidation
		.formValidation(
			form,
			{
				fields: {
					reqTahun: {
						validators: {
							notEmpty: {
								message: 'Tahun harus diisi'
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
			        		document.location.href = "app/index/pegawai_penilaian_potensi";
			        		// window.location.reload();
			        	});
			        },
			        error: function(xhr, status, error) {
			        	var err = JSON.parse(xhr.responseText);
			        	Swal.fire("Error", err.message, "error");
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

