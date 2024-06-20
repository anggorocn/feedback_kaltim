<?
include_once("functions/personal.func.php");
$this->load->model("base-validasi/Cuti");
$this->load->model("base-validasi/HapusData");


$reqPegawaiId= $this->input->get('reqPegawaiId');
$reqRowId= $this->input->get('reqRowId');
$reqDetilId= $this->input->get('reqDetilId');
$reqMode= $this->input->get('reqMode');
$reqValidasiHapusId= $this->input->get('reqValidasiHapusId');

$adminuserid= $this->adminuserid;

$set = new Cuti();
if($reqMode == 'edit' || $reqMode == 'cancel' || $reqMode == 'view')
{
	if($reqDetilId == "")
	{
		$set->selectByParams(array('CUTI_ID'=>$reqRowId));
	}
	else
	{
		$set->selectByParams(array('TEMP_VALIDASI_ID'=>$reqDetilId));
	}
	// echo $set->query;exit;
	$set->firstRow();
	$reqPerubahanData= $set->getField('PERUBAHAN_DATA');
	// print_r($reqPerubahanData);
	$reqRowId= $set->getField('CUTI_ID');
	$reqDataId= $set->getField('TEMP_VALIDASI_ID');
	$reqJenisCuti= $set->getField('JENIS_CUTI');
	$arrdata = cutitahunan($reqJenisCuti);
	$valJenisCuti= checkwarna($reqPerubahanData, 'JENIS_CUTI', $arrdata, array("id","text"));
	$reqNoSurat= $set->getField('NO_SURAT');$valNoSurat= checkwarna($reqPerubahanData, 'NO_SURAT');
	$reqTanggalPermohonan = dateToPageCheck($set->getField('TANGGAL_PERMOHONAN'));$valTanggalPermohonan= checkwarna($reqPerubahanData, 'TANGGAL_PERMOHONAN', "date");
	$reqTanggalSurat= dateToPageCheck($set->getField('TANGGAL_SURAT'));$valTanggalSurat= checkwarna($reqPerubahanData, 'TANGGAL_SURAT', "date");
	$reqTanggalMulai= dateToPageCheck($set->getField('TANGGAL_MULAI'));$valTanggalMulai= checkwarna($reqPerubahanData, 'TANGGAL_MULAI', "date");
	$reqTanggalSelesai= dateToPageCheck($set->getField('TANGGAL_SELESAI'));$valTanggalSelesai= checkwarna($reqPerubahanData, 'TANGGAL_SELESAI', "date");
	$reqLama= $set->getField('LAMA');$valLama= checkwarna($reqPerubahanData, 'LAMA');
	$reqCutiKeterangan= $set->getField("CUTI_KETERANGAN");$valCutiKeterangan= checkwarna($reqPerubahanData, 'CUTI_KETERANGAN');
	$reqKeterangan= $set->getField('KETERANGAN');$valKeterangan= checkwarna($reqPerubahanData, 'KETERANGAN');
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
						<a class="text-muted">Cuti</a>
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
                    <h3 class="card-label">Cuti</h3>
                </div>
            </div>
            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
	        	<div class="card-body">
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Jenis Cuti
	        			<?
	        			if(!empty($valJenisCuti['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valJenisCuti['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-8 col-sm-12">
	        				<select class="form-control select2 <?=$valJenisCuti['warna']?> " id="ktjenis" name="reqJenisCuti" style="width:auto;">
	        					<option value="1" <? if($reqJenisCuti == 1) echo 'selected';?>>Cuti Tahunan</option>				
	        					<option value="2" <? if($reqJenisCuti == 2) echo 'selected';?>>Cuti Besar</option>				
	        					<option value="3" <? if($reqJenisCuti == 3) echo 'selected';?>>Cuti Sakit</option>				
	        					<option value="4" <? if($reqJenisCuti == 4) echo 'selected';?>>Cuti Bersalin</option>				
	        					<option value="5" <? if($reqJenisCuti == 5) echo 'selected';?>>CLTN</option>				
	        					<option value="6" <? if($reqJenisCuti == 6) echo 'selected';?>>Perpanjangan CLTN</option>
	        					<option value="7" <? if($reqJenisCuti == 7) echo 'selected';?>>Cuti Menikah</option>
	        					<option value="8" <? if($reqJenisCuti == 8) echo 'selected';?>>Cuti karena alasan penting</option>
	        				</select>
	        			</div>	        				
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">No Surat
	        			<?
	        			if(!empty($valNoSurat['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNoSurat['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valNoSurat['warna']?>" placeholder="Masukkan No. Surat"name="reqNoSurat" id="reqNoSurat" value="<?=$reqNoSurat?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tanggal Surat
	        			<?
	        			if(!empty($valTanggalSurat['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTanggalSurat['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control <?=$valTanggalSurat['warna']?>" id="kttanggalkeputusan" name="reqTanggalSurat" readonly="readonly" placeholder="Masukkan Tanggal Surat" value="<?=$reqTanggalSurat?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tanggal Mulai
	        			<?
	        			if(!empty($valTanggalMulai['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTanggalSurat['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control <?=$valTanggalSurat['warna']?>" id="kttanggalmulai" name="reqTanggalMulai" readonly="readonly" placeholder="Masukkan Tanggal Mulai" value="<?=$reqTanggalMulai?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tanggal Selesai
	        			<?
	        			if(!empty($valTanggalSelesai['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTanggalSelesai['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control <?=$valTanggalSelesai['warna']?>" id="kttanggalselesai" name="reqTanggalSelesai" readonly="readonly" placeholder="Masukkan Tanggal Selesai" value="<?=$reqTanggalSelesai?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Lama
	        			<?
	        			if(!empty($valCutiKeterangan['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valCutiKeterangan['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valCutiKeterangan['warna']?>" placeholder="Masukkan Lama"name="reqCutiKeterangan" id="reqCutiKeterangan" value="<?=$reqCutiKeterangan?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Keterangan
	        			<?
	        			if(!empty($valKeterangan['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valKeterangan['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valKeterangan['warna']?>" placeholder="Masukkan Keterangan"  name="reqKeterangan"  value="<?=$reqKeterangan?>" />
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
		window.location.href='app/index/pegawai_cuti'
	}

	$('#ktjenis').select2({
		placeholder: "Pilih jenis"
	});
	
    arrows= {leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'};
	$('#kttanggalkeputusan,#kttanggalmulai,#kttanggalakhir,#kttanggalselesai,#kttmtsk').datepicker({
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
		var formSubmitUrl = "json-validasi/personal_json/jsonpegawaicutiadd";
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
			        		document.location.href = "app/index/pegawai_cuti";
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

