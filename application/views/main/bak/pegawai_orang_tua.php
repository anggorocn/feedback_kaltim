<?
include_once("functions/personal.func.php");

$this->load->model("base-data/OrangTua");

$reqPegawaiId= $this->pegawaiId;
$reqMode = $this->input->get("reqMode");
$reqSatkerId = $this->input->get("reqIdOrganisasi");
$adminuserid= $this->adminuserid;


$statement="";

$set= new OrangTua();
$set->selectByParamsAyah(array("PEGAWAI_ID" => $reqPegawaiId,"JENIS_KELAMIN" => "L"),-1,-1, $statement);
// echo $set->query;exit;
$set->firstRow();
$reqPerubahanDataAyah= $set->getField('PERUBAHAN_DATA');

$reqRowIdAyah= $set->getField('ORANG_TUA_ID');
$reqDataIdAyah= $set->getField('TEMP_VALIDASI_ID');
$reqNamaAyah= $set->getField('NAMA');$valNamaAyah= checkwarna($reqPerubahanDataAyah, 'NAMA');
$reqIdAyah= $set->getField("ORANG_TUA_ID");
$reqTempatLahirAyah= $set->getField('TEMPAT_LAHIR');$valTempatLahirAyah= checkwarna($reqPerubahanDataAyah, 'TEMPAT_LAHIR');
$reqTglLahirAyah= dateToPageCheck($set->getField('TANGGAL_LAHIR'));$valTglLahirAyah= checkwarna($reqPerubahanDataAyah, 'TANGGAL_LAHIR', "date");
$reqUsiaAyah= $set->getField('USIA');
$reqPekerjaanAyah = $set->getField('PEKERJAAN');$valPekerjaanAyah= checkwarna($reqPerubahanDataAyah, 'PEKERJAAN');
$reqAlamatAyah= $set->getField('ALAMAT'); $valAlamatAyah= checkwarna($reqPerubahanDataAyah, 'ALAMAT');
$reqPropinsiAyahId= $set->getField('PROPINSI_ID');
$reqKabupatenAyahId= $set->getField('KABUPATEN_ID');
$reqKecamatanAyahId= $set->getField('KECAMATAN_ID');
$reqDesaAyahId= $set->getField('KELURAHAN_ID');
$reqPropinsiAyah= $set->getField('PROPINSI_NAMA');
$reqKabupatenAyah= $set->getField('KABUPATEN_NAMA');
$reqKecamatanAyah= $set->getField('KECAMATAN_NAMA');
$reqKodePosAyah= $set->getField('KODEPOS');
$reqDesaAyah= $set->getField('KELURAHAN_NAMA');
$reqTeleponAyah= $set->getField('TELEPON');$valTeleponAyah= checkwarna($reqPerubahanDataAyah, 'TELEPON');
$reqValidasiAyah= $set->getField('VALIDASI');	


if($reqRowIdAyah == "")
	$reqModeAyah= "insert";
else
	$reqModeAyah= "update";

unset($set);
	
$set= new OrangTua();
$set->selectByParamsIbu(array("PEGAWAI_ID" => $reqPegawaiId,"JENIS_KELAMIN" => "P"),-1,-1, $statement);
$set->firstRow();
$reqRowIdIbu= $set->getField('ORANG_TUA_ID');
$reqDataIdIbu= $set->getField('TEMP_VALIDASI_ID');
if($reqRowIdIbu == "")
	$reqModeIbu= "insert";
else
	$reqModeIbu= "update";

$reqPerubahanDataIbu= $set->getField('PERUBAHAN_DATA');	
$reqIdIbu= $set->getField("ORANG_TUA_ID");
$reqNamaIbu= $set->getField('NAMA');$valNamaIbu= checkwarna($reqPerubahanDataIbu, 'NAMA');
$reqTempatLahirIbu= $set->getField('TEMPAT_LAHIR');$valTempatLahirIbu= checkwarna($reqPerubahanDataIbu, 'TEMPAT_LAHIR');
$reqTglLahirIbu= dateToPageCheck($set->getField('TANGGAL_LAHIR'));$valTglLahirIbu= checkwarna($reqPerubahanDataIbu, 'TANGGAL_LAHIR', "date");
$reqUsiaIbu= $set->getField('USIA');
$reqPekerjaanIbu= $set->getField('PEKERJAAN');$valPekerjaanIbu= checkwarna($reqPerubahanDataIbu, 'PEKERJAAN');
$reqAlamatIbu= $set->getField('ALAMAT');$valAlamatIbu= checkwarna($reqPerubahanDataIbu, 'PEKERJAAN');
$reqPropinsiIbuId= $set->getField('PROPINSI_ID');
$reqKabupatenIbuId= $set->getField('KABUPATEN_ID');
$reqKecamatanIbuId= $set->getField('KECAMATAN_ID');
$reqDesaIbuId= $set->getField('KELURAHAN_ID');
$reqPropinsiIbu= $set->getField('PROPINSI_NAMA');
$reqKabupatenIbu= $set->getField('KABUPATEN_NAMA');
$reqKecamatanIbu= $set->getField('KECAMATAN_NAMA');
$reqDesaIbu= $set->getField('KELURAHAN_NAMA');
$reqKodePosIbu= $set->getField('KODEPOS');
$reqTeleponIbu= $set->getField('TELEPON');$valTeleponIbu= checkwarna($reqPerubahanDataIbu, 'TELEPON');
$reqValidasiIbu= $set->getField('VALIDASI');	


unset($set);
// print_r($arragama);exit;

?>
<script type="text/javascript" src="functions/globalfunction.js"></script>

<div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
	<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<div class="d-flex align-items-center flex-wrap mr-1">
			<div class="d-flex align-items-baseline flex-wrap mr-5">
				<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">Data Keluarga</a>
					</li>
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">Orang Tua</a>
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
                    <h3 class="card-label">Ayah</h3>
                </div>
                <div class="card-title" style="margin-right: 400px">
                    <span class="card-icon">
                        <i class="flaticon2-notepad text-primary"></i>
                    </span>
                    <h3 class="card-label">Ibu</h3>
                </div>
            </div>
            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
	        	<div class="card-body">
	        		
	        		<div class="form-group row">
	        			
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Nama Ayah
	        			<?
	        			if(!empty($valNamaAyah['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNamaAyah['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valNamaAyah['warna']?>" placeholder="Masukkan Nama Ayah"name="reqNamaAyah" id="reqNamaAyah" value="<?=$reqNamaAyah?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Nama Ibu
	        			<?
	        			if(!empty($valNamaIbu['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNamaIbu['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valNamaIbu['warna']?>" placeholder="Masukkan Nama Ibu"name="reqNamaIbu" id="reqNamaIbu" value="<?=$reqNamaIbu?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tempat Lahir
	        			<?
	        			if(!empty($valTempatLahirAyah['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTempatLahirAyah['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valTempatLahirAyah['warna']?> " placeholder="Masukkan Tempat Lahir Ayah"name="reqTempatLahirAyah" id="reqTempatLahirAyah" value="<?=$reqTempatLahirAyah?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tempat Lahir
	        			<?
	        			if(!empty($valTempatLahirIbu['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTempatLahirIbu['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valTempatLahirIbu['warna']?>" placeholder="Masukkan Tempat Lahir Ibu"name="reqTempatLahirIbu" id="reqTempatLahirIbu" value="<?=$reqTempatLahirIbu?>" />
	        			</div>
	        		</div>
	        		
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tanggal Lahir
	        			<?
	        			if(!empty($valTglLahirAyah['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTglLahirAyah['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control <?=$valTglLahirAyah['warna']?>" id="kttglayah" name="reqTglLahirAyah" readonly="readonly" placeholder="Tanggal Lahir Ayah" value="<?=$reqTglLahirAyah?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tanggal Lahir
	        			<?
	        			if(!empty($valTglLahirIbu['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTglLahirIbu['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control <?=$valTglLahirIbu['warna']?>" id="kttglibu" name="reqTglLahirIbu" readonly="readonly" placeholder="Tanggal Lahir Ibu" value="<?=$reqTglLahirIbu?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Pekerjaan
	        			<?
		        		if(!empty($valPekerjaanAyah['data']))
		        		{
		        		?>
		        		<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valPekerjaanAyah['data']?>"></i>
		        		<?
		        		}
		        		?>
		        		</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valPekerjaanAyah['warna']?>" placeholder="Masukkan Pekerjaan Ayah"name="reqPekerjaanAyah" id="reqPekerjaanAyah" value="<?=$reqPekerjaanAyah?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Pekerjaan
	        				<?
		        			if(!empty($valPekerjaanIbu['data']))
		        			{
		        			?>
		        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valPekerjaanIbu['data']?>"></i>
		        			<?
		        			}
		        			?>
		        		</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valPekerjaanIbu['warna']?>" placeholder="Masukkan Pekerjaan Ibu"name="reqPekerjaanIbu" id="reqPekerjaanIbu" value="<?=$reqPekerjaanIbu?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Alamat
	        				<?
		        			if(!empty($valAlamatAyah['data']))
		        			{
		        			?>
		        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valAlamatAyah['data']?>"></i>
		        			<?
		        			}
		        			?>
		        		</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<textarea rows="2" class="form-control <?=$valAlamatAyah['warna']?> "  placeholder="Masukkan Alamat Ayah" cols="50" name="reqAlamatAyah" value="<?=$reqAlamatAyah?>"><?=$reqAlamatAyah?></textarea>
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Alamat
	        				<?
		        			if(!empty($valAlamatIbu['data']))
		        			{
		        			?>
		        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valAlamatIbu['data']?>"></i>
		        			<?
		        			}
		        			?>
		        		</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<textarea rows="2" class="form-control <?=$valAlamatIbu['warna']?>" placeholder="Masukkan Alamat Ibu" cols="50" name="reqAlamatIbu" value="<?=$reqAlamatIbu?>"><?=$reqAlamatIbu?></textarea>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Pekerjaan
	        				<?
		        			if(!empty($valPekerjaanAyah['data']))
		        			{
		        			?>
		        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valPekerjaanAyah['data']?>"></i>
		        			<?
		        			}
		        			?>
		        		</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valAlamatIbu['warna']?>" placeholder="Masukkan Pekerjaan Ayah"name="reqPekerjaanAyah" id="reqPekerjaanAyah" value="<?=$reqPekerjaanAyah?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Pekerjaan
	        				<?
		        			if(!empty($valPekerjaanIbu['data']))
		        			{
		        			?>
		        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valPekerjaanIbu['data']?>"></i>
		        			<?
		        			}
		        			?>
		        		</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control  <?=$valAlamatIbu['warna']?>" placeholder="Masukkan Pekerjaan Ibu"name="reqPekerjaanIbu" id="reqPekerjaanIbu" value="<?=$reqPekerjaanIbu?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Telepon
	        				<?
		        			if(!empty($valTeleponAyah['data']))
		        			{
		        			?>
		        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTeleponAyah['data']?>"></i>
		        			<?
		        			}
		        			?>
		        		</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valTeleponAyah['warna']?>" placeholder="Masukkan Telepon Ayah"name="reqTeleponAyah" id="reqTeleponAyah" value="<?=$reqTeleponAyah?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Telepon
	        				<?
		        			if(!empty($valTeleponIbu['data']))
		        			{
		        			?>
		        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTeleponIbu['data']?>"></i>
		        			<?
		        			}
		        			?>
		        		</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valTeleponIbu['warna']?>" placeholder="Masukkan Telepon Ibu"name="reqTeleponIbu" id="reqTeleponIbu" value="<?=$reqTeleponIbu?>" />
	        			</div>
	        		</div>

	        	</div>
	        	<div class="card-footer">
	        		<div class="row">
	        			<div class="col-lg-9">
	        				<input type="hidden" name="reqPegawaiId" value="<?=$reqPegawaiId?>">
	        				<input type="hidden" name="reqMode" value="<?=$reqMode?>">
	        				<input type="hidden" name="reqRowIdAyah" value="<?=$reqRowIdAyah?>">
	        				<input type="hidden" name="reqDataIdAyah" value="<?=$reqDataIdAyah?>">
	        				<input type="hidden" name="reqRowIdIbu" value="<?=$reqRowIdIbu?>">
	        				<input type="hidden" name="reqDataIdIbu" value="<?=$reqDataIdIbu?>">
	        				<input type="hidden" name="reqModeAyah" value="<?=$reqModeAyah?>">
	        				<input type="hidden" name="reqModeIbu" value="<?=$reqModeIbu?>">
	        				<button type="submit" id="ktloginformsubmitbutton"  class="btn btn-primary font-weight-bold mr-2">Simpan</button>
	        			</div>
	        		</div>
	        	</div>
	        </form>
        </div>
    </div>
</div>

<script type="text/javascript">
	$(document).on("input", "#reqTeleponAyah,#reqTeleponIbu", function() {
		this.value = this.value.replace(/\D/g,'');
	});

    arrows= {leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'};
	$('#kttglayah,#kttglibu').datepicker({
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
		var formSubmitUrl = "json-validasi/personal_json/jsonorangtuaadd";
		var formSubmitButton = KTUtil.getById('ktloginformsubmitbutton');
		if (!form) {
			return;
		}
		FormValidation
		.formValidation(
			form,
			{
				fields: {
					reqNamaAyah: {
						validators: {
							notEmpty: {
								message: 'Nama ayah harus diisi'
							}
						}
					},
					reqNamaIbu: {
						validators: {
							notEmpty: {
								message: 'Nama ibu harus diisi'
							}
						}
					},
					reqTglLahirAyah: {
						validators: {
							notEmpty: {
								message: 'Tanggal lahir ayah harus diisi'
							}
						}
					},
					reqTglLahirIbu: {
						validators: {
							notEmpty: {
								message: 'Tanggal lahir ibu harus diisi'
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
			        		document.location.href = "app/index/pegawai_orang_tua";
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
				confirmButtonText: "Ok, got it!",
				customClass: {
					confirmButton: "btn font-weight-bold btn-light-primary"
				}
			}).then(function() {
				KTUtil.scrollTop();
			});
		});
	});
</script>

