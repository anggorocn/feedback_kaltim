<?
include_once("functions/personal.func.php");

$this->load->model("base-data/Anak");
$this->load->model("base-data/HapusData");
$this->load->model("base-personal/Pendidikan");

$reqPegawaiId= $this->input->get('reqPegawaiId');
$reqRowId= $this->input->get('reqRowId');
$reqDetilId= $this->input->get('reqDetilId');
$reqMode= $this->input->get('reqMode');
$reqValidasiHapusId= $this->input->get('reqValidasiHapusId');
$adminuserid= $this->adminuserid;
$folder = $this->config->item('simpeg');


$statement="";
$sOrder="";
$set= new Pendidikan();
$arrpendidikan= [];
$set->selectByParams(array(), -1,-1,$statement,$sOrder);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	$arrdata["id"]= $set->getField("PENDIDIKAN_ID");
	$arrdata["text"]= $set->getField("NAMA");
	array_push($arrpendidikan, $arrdata);
}
unset($set);

$set = new Anak();
if($reqMode == 'edit' || $reqMode == 'cancel' || $reqMode == 'view')
{
	$set->selectByParams(array('A.ANAK_ID'=>$reqRowId));
	// echo $set->query;exit;
	$set->firstRow();
	$reqPerubahanData= $set->getField('PERUBAHAN_DATA');
	$reqRowId= $set->getField('ANAK_ID');
	$reqDataId= $set->getField('TEMP_VALIDASI_ID');
	$reqNama= $set->getField('NAMA');$valNama= checkwarna($reqPerubahanData, 'NAMA');
	$reqTmpLahir= $set->getField('TEMPAT_LAHIR');$valTmpLahir= checkwarna($reqPerubahanData, 'TEMPAT_LAHIR');
	$reqTglLahir= dateToPageCheck($set->getField('TANGGAL_LAHIR'));$valTglLahir= checkwarna($reqPerubahanData, 'TANGGAL_LAHIR', "date");
	$reqLP= $set->getField('JENIS_KELAMIN');
	$arrdata = jeniskelamin($reqLP);
	$valLP= checkwarna($reqPerubahanData, 'JENIS_KELAMIN',$arrdata, array("id","text"));
	$reqStatus= $set->getField('STATUS_KELUARGA');
	$arrdata = statusanak($reqStatus);
	// print_r($reqPerubahanData);
	$valStatus= checkwarna($reqPerubahanData, 'STATUS_KELUARGA',$arrdata, array("id","text"));
	$reqDapatTunjangan= $set->getField('STATUS_TUNJANGAN');$valDapatTunjangan= checkwarna($reqPerubahanData, 'STATUS_TUNJANGAN');
	$reqPendidikan= $set->getField('PENDIDIKAN_ID');$valPendidikan= checkwarna($reqPerubahanData, 'PENDIDIKAN_ID', $arrpendidikan, array("id", "text"));
	$reqPekerjaan= $set->getField('PEKERJAAN');$valPekerjaan= checkwarna($reqPerubahanData, 'PEKERJAAN');
	$reqMulaiDibayar= dateToPageCheck($set->getField('AWAL_BAYAR'));$valMulaiDibayar= checkwarna($reqPerubahanData, 'AWAL_BAYAR');
	$reqAkhirDibayar= dateToPageCheck($set->getField('AKHIR_BAYAR'));$valAkhirDibayar= checkwarna($reqPerubahanData, 'AKHIR_BAYAR');
	$reqStatusNikah= $set->getField('STATUS_NIKAH');
	$reqValidasi= $set->getField('VALIDASI');

	$reqLinkFile= $set->getField('LINK_FILE');
	$reqFileId= $set->getField('ANAK_FILE_ID');
	$reqLinkFileServer= $set->getField('LINK_SERVER');
}
unset($set);
if($reqRowId == "")
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

// print_r($reqPerubahanData);exit;

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
						<a class="text-muted">Anak </a>
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
                    <h3 class="card-label">Anak</h3>
                </div>
            </div>
            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
	        	<div class="card-body">
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Nama
	        			<?
	        			if(!empty($valNama['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNama['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valNama['warna']?>" placeholder="Masukkan Anak" name="reqNama" id="reqNama" value="<?=$reqNama?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tempat Lahir
	        			<?
	        			if(!empty($valTmpLahir['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTmpLahir['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valTmpLahir['warna']?>" placeholder="Masukkan Tempat Lahir" name="reqTmpLahir" id="reqTmpLahir" value="<?=$reqTmpLahir?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tgl Lahir
	        			<?
	        			if(!empty($valTglLahir['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTglLahir['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control <?=$valTglLahir['warna']?>" id="kttgllahir" name="reqTglLahir" readonly="readonly" placeholder="Masukkan Tgl Mulai" value="<?=$reqTglLahir?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">L/P
	        			<?
	        			if(!empty($valLP['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valLP['data']?>"></i>
	        			<?
	        			}
	        			?></label>
	        			<div class="col-lg-4 col-sm-12">
	        				<select class="form-control select2 <?=$valLP['warna']?>" id="ktlp" name="reqLP" >
	        					<option value="L" <? if($reqLP == 'L') echo 'selected';?>>L</option>
	        					<option value="P" <? if($reqLP == 'P') echo 'selected';?>>P</option>
	        				</select>
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Status
	        			<?
	        			if(!empty($valStatus['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valStatus['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<select class="form-control select2 <?=$valStatus['warna']?>" id="ktstatus" name="reqStatus" >
	        					<option value="1" <? if($reqStatus == 1) echo 'selected';?>>Kandung</option>
	        					<option value="2" <? if($reqStatus == 2) echo 'selected';?>>Tiri</option>
	        					<option value="3" <? if($reqStatus == 3) echo 'selected';?>>Angkat</option>
	        				</select>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tunjangan</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="radio" <?=$disabled?> <? if($reqDapatTunjangan == '1') echo 'checked';?>  name="reqDapatTunjangan" value="1" /><label style="width:auto;<?=$tmp1?>" > Dapat</label> &nbsp;&nbsp;&nbsp;
	        				<input type="radio" <?=$disabled?> <? if($reqDapatTunjangan == '2') echo 'checked';?> name="reqDapatTunjangan" value="2" /> <label style="width:auto;<?=$tmp2?>" >Tidak</label>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Pendidikan
	        			<?
	        			if(!empty($valPendidikan['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valPendidikan['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<select class="form-control select2 <?=$valPendidikan['warna']?>" id="ktpendidikan" name="reqPendidikan" style="width:50%; ">
	        					<option value=""></option>
	        					<?
	        					foreach($arrpendidikan as $item) 
	        					{
	        						$selectvalid= $item["id"];
	        						$selectvaltext= $item["text"];
	        					?>
	        					<option value="<?=$selectvalid?>" <? if($reqPendidikan == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
	        					<?
	        					}
	        					?>
	        				</select>
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Pekerjaan
	        			<?
	        			if(!empty($valPekerjaan['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valPekerjaan['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12" id="reqInfoNip">
	        				<input type="text" class="form-control <?=$valPekerjaan['warna']?>" placeholder="Masukkan Pekerjaan" name="reqPekerjaan" id="reqPekerjaan" value="<?=$reqPekerjaan?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Akta lahir</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<?if(empty($reqLinkFileServer))
	        				{
	        					?>
	        					<input type="button" id="upload" class="btn btn-success font-weight-bold mr-2" value="Upload" onclick="document.getElementById('reqLinkFile').click();" />
	        					<input type="file" style="display:none;" id="reqLinkFile" name="reqLinkFile" accept=".pdf"/>
	        					<? 
	        					$file_name_direktori = "../".$folder."/uploads/anak/FOTO_BLOB-".$reqRowId.".pdf";

	        					if (file_exists($file_name_direktori)) 
	        					{
	        						?>
	        						<a class="btn btn-success font-weight-bold mr-2" href="<?=$file_name_direktori?>" target="_blank">Download</a>
	        						<? 
	        					}
	        					?>
	        					<?
	        				}
	        				else
	        				{
	        					?>
	        					<a class="btn btn-success font-weight-bold mr-2" href="app/loadUrl/main/viewer?reqForm=anak&reqFileId=<?=$reqFileId?>" target="_blank">Download</a>
	        					<a class="btn btn-danger font-weight-bold mr-2" onclick="btndeletefile('<?=$reqFileId?>','<?=$reqPegawaiId?>','<?=$reqRowId?>')">Hapus File</a>
	        					<?
	        				}
	        				?>
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
	$(document).on("input", "#reqNoHp", function() {
		this.value = this.value.replace(/\D/g,'');
	});
	function kembali() {
		window.location.href='app/index/pegawai_anak'
	}
	$('#ktstatus').select2({
		placeholder: "Pilih Status"
	});
	$('#ktlp').select2({
		placeholder: "Pilih Jenis Kelamin"
	});
	$('#ktstatusnikah').select2({
		placeholder: "Pilih Status Nikah"
	});
	$('#ktpendidikan').select2({
		placeholder: "Pilih Pendidikan"
	});
	
    arrows= {leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'};
	$('#kttgllahir,#kttglnikah').datepicker({
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
		var formSubmitUrl = "json-validasi/personal_json/jsonpegawaianakadd";
		var formSubmitButton = KTUtil.getById('ktloginformsubmitbutton');
		if (!form) {
			return;
		}
		FormValidation
		.formValidation(
			form,
			{
				fields: {
					reqNamaSuamiIstri: {
						validators: {
							notEmpty: {
								message: 'Nama Suami/Istri harus diisi'
							}
						}
					},
					reqTempatLahir: {
						validators: {
							notEmpty: {
								message: 'Tempat Lahir harus diisi'
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
			        		document.location.href = "app/index/pegawai_anak";
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

	function btndeletefile(fileid,reqPegawaiId,reqRowId) {
		if(fileid !== "")
        {
            urlAjax= "json-validasi/personal_json/jsonpegawaianakdeletefile?reqFileId="+fileid+"&reqPegawaiId="+reqPegawaiId+"&reqRowId="+reqRowId;
            swal.fire({
                title: 'Apakah anda yakin untuk hapus file?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes'
            }).then(function(result) { 
                if (result.value) {
                    $.ajax({
                        url : urlAjax,
                        type : 'DELETE',
                        dataType:'json',
                        beforeSend: function() {
                            swal.fire({
                                title: 'Please Wait..!',
                                text: 'Is working..',
                                onOpen: function() {
                                    swal.showLoading()
                                }
                            })
                        },
                        success : function(data) { 
                            swal.fire({
                                position: 'center',
                                icon: "success",
                                type: 'success',
                                title: data.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(function() {
                                location.reload();
                            });
                        },
                        complete: function() {
                            swal.hideLoading();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            swal.hideLoading();
                            var err = JSON.parse(jqXHR.responseText);
                            Swal.fire("Error", err.message, "error");
                        }
                    });
                }
            });
        }
	}
</script>

