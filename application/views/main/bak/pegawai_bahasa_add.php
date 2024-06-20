<?
include_once("functions/personal.func.php");
$this->load->model("base-validasi/Bahasa");
$this->load->model("base-validasi/HapusData");


$reqPegawaiId= $this->input->get('reqPegawaiId');
$reqRowId= $this->input->get('reqRowId');
$reqDetilId= $this->input->get('reqDetilId');
$reqMode= $this->input->get('reqMode');
$reqValidasiHapusId= $this->input->get('reqValidasiHapusId');

$adminuserid= $this->adminuserid;

// echo $adminuserid;exit;

$set = new Bahasa();
if($reqMode == 'edit' || $reqMode == 'cancel' || $reqMode == 'view')
{
	if($reqDetilId == "")
	{
		$set->selectByParams(array('BAHASA_ID'=>$reqRowId));
	}
	else
	{
		$set->selectByParams(array('TEMP_VALIDASI_ID'=>$reqDetilId));
	}
	// echo $set->query;exit;
	$set->firstRow();
	$reqRowId= $set->getField('BAHASA_ID');
	$reqPerubahanData= $set->getField('PERUBAHAN_DATA');
	$reqDataId=$set->getField('TEMP_VALIDASI_ID');
	$reqNamaBahasa= $set->getField('NAMA');$valNamaBahasa= checkwarna($reqPerubahanData, 'NAMA');
	$reqJenisBahasa= $set->getField('JENIS');
	$arrdata = jenisbahasa($reqJenisBahasa);
	$valJenisBahasa= checkwarna($reqPerubahanData, 'JENIS',$arrdata, array("id","text"));
	$reqKemampuanBicara= $set->getField('KEMAMPUAN');
	$arrdata = kemampuanbicara($reqKemampuanBicara);
	$valKemampuanBicara= checkwarna($reqPerubahanData, 'KEMAMPUAN',$arrdata, array("id","text"));
	$reqValidasi= $set->getField('VALIDASI');
	
	unset($set);
}
if($reqDetilId == "")
{
	$reqMode = 'insert';
}
else
{
	$reqMode = 'update';
}

if($reqJenis == "")
{
	$reqJenis = 1;
}

$arrstatusvalidasi= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"1", "text"=>"Valid")
  , array("id"=>"2", "text"=>"Ditolak")
);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrstatusvalidasi, $arrdata);
}



?>
<script type="text/javascript" src="functions/globalfunction.js"></script>

<div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
	<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<div class="d-flex align-items-center flex-wrap mr-1">
			<div class="d-flex align-items-baseline flex-wrap mr-5">
				<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">Data Lain Lain</a>
					</li>
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">Bahasa</a>
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
                    <h3 class="card-label">Bahasa</h3>
                </div>
            </div>
            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
	        	<div class="card-body">
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Jenis Bahasa
	        			<?
	        			if(!empty($valJenisBahasa['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valJenisBahasa['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-8 col-sm-12">
	        				<select class="form-control select2 <?=$valJenisBahasa['warna']?>" id="ktjenis" name="reqJenisBahasa" style="width:auto;">
	        					<option value="1" <? if($reqJenisBahasa == 1) echo 'selected'?>>Asing</option>
	        					<option value="2" <? if($reqJenisBahasa == 2) echo 'selected'?>>Daerah</option>
	        				</select>
	        			</div>	        				
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Nama Bahasa
	        			<?
	        			if(!empty($valNamaBahasa['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNamaBahasa['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valNamaBahasa['warna']?>" placeholder="Masukkan Nama Bahasa"name="reqNamaBahasa" id="reqNamaBahasa" value="<?=$reqNamaBahasa?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Kemampuan Bicara
	        			<?
	        			if(!empty($valKemampuanBicara['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valKemampuanBicara['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-8 col-sm-12">
	        				<select class="form-control select2 <?=$valKemampuanBicara['warna']?>" id="ktkemampuan" name="reqKemampuanBicara" style="width:auto;">
	        					<option value="1" <? if($reqKemampuanBicara == 1) echo 'selected'?>>Aktif</option>
	        					<option value="2" <? if($reqKemampuanBicara == 2) echo 'selected'?>>Pasif</option>
	        				</select>
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
	$(document).on("input", "#reqTh,#reqBl,#reqGajiPokok", function() {
		this.value = this.value.replace(/\D/g,'');
	});
	function kembali() {
		window.location.href='app/index/pegawai_bahasa'
	}

	$('#ktjenis').select2({
		placeholder: "Pilih jenis"
	});
	$('#ktkemampuan').select2({
		placeholder: "Pilih kemampuan"
	});

	$('#ktStatusValidasi').select2({
		placeholder: "Status Validasi"
	});

	var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';
	jQuery(document).ready(function() {
		var form = KTUtil.getById('ktloginform');
		var formSubmitUrl = "json-validasi/personal_json/jsonpegawaibahasaadd";
		var formSubmitButton = KTUtil.getById('ktloginformsubmitbutton');
		if (!form) {
			return;
		}
		FormValidation
		.formValidation(
			form,
			{
				fields: {
					reqPejabatPenetap: {
						validators: {
							notEmpty: {
								message: 'Pejabat Penetap harus diisi'
							}
						}
					},
					reqNoSK: {
						validators: {
							notEmpty: {
								message: 'No SK harus diisi'
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
			        		document.location.href = "app/index/pegawai_bahasa";
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

