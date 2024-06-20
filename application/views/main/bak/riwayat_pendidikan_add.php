<?
include_once("functions/personal.func.php");

$this->load->model("base-validasi/PegawaiPendidikanRiwayat");
$this->load->model("base-validasi/TingkatPendidikan");
$this->load->model("base-validasi/PegawaiPendidikan");

$reqPegawaiId= $this->input->get('reqPegawaiId');
$reqSatkerId= $this->input->get('reqIdOrganisasi');
$adminuserid= $this->adminuserid;
$reqRowId= $this->input->get('reqRowId');
$reqDetilId= $this->input->get('reqDetilId');
$reqMode= $this->input->get('reqMode');
$reqValidasiHapusId= $this->input->get('reqValidasiHapusId');


$statement="";
$sOrder="";
$set= new PegawaiPendidikan();
$arrpejabat= array();
$set->selectByParams(array(), -1,-1,$statement,$sOrder);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	$arrdata["id"]= $set->getField("PEGAWAI_PENDIDIKAN_ID");
	$arrdata["text"]= $set->getField("NAMA");
	$arrdata["TINGKAT_PENDIDIKAN_ID"]= $set->getField("TINGKAT_PENDIDIKAN_ID");
	$arrdata["TINGKAT_PENDIDIKAN"]= $set->getField("TINGKAT_PENDIDIKAN");
	array_push($arrpejabat, $arrdata);
}

// print_r($arrpejabat);exit;
unset($set);

$set= new TingkatPendidikan();
$arrgol= array();
$set->selectByParams();
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	$arrdata["id"]= $set->getField("TINGKAT_PENDIDIKAN_ID");
	$arrdata["text"]= $set->getField("NAMA");
	array_push($arrgol, $arrdata);
}
unset($set);


$set   = new PegawaiPendidikanRiwayat();

if($reqMode == 'edit' || $reqMode == 'cancel' || $reqMode == 'view')
{
	if($reqDetilId == "")
	{
		$set->selectByParams(array('A.PEGAWAI_PENDIDIKAN_RIWAYAT_ID'=>$reqRowId));
	}
	else
	{
		$set->selectByParams(array('A.TEMP_VALIDASI_ID'=>$reqDetilId));
	}
	// echo $set->query;exit;
	$set->firstRow();
	// echo $set->query;exit;
	$reqRowId= $set->getField('PEGAWAI_PENDIDIKAN_RIWAYAT_ID');
	$reqDataId= $set->getField('TEMP_VALIDASI_ID');
	$reqPerubahanData= $set->getField('PERUBAHAN_DATA');

	$reqPendidikanId= $set->getField('PEGAWAI_PENDIDIKAN_ID');$valPendidikanId= checkwarna($reqPerubahanData, 'PEGAWAI_PENDIDIKAN_ID', $arrpejabat, array("id","text"));
	$reqPendidikan= $set->getField('PEGAWAI_PENDIDIKAN');$valPendidikan= checkwarna($reqPerubahanData, 'PEGAWAI_PENDIDIKAN');
	$reqJurusanId= $set->getField('TINGKAT_PENDIDIKAN_ID');$valJurusanId= checkwarna($reqPerubahanData, 'TINGKAT_PENDIDIKAN_ID', $arrpejabat, array("id","text"));
	$reqJurusan= $set->getField('TINGKAT_PENDIDIKAN');$valJurusan= checkwarna($reqPerubahanData, 'TINGKAT_PENDIDIKAN');
	// print_r($reqJurusan);exit;
	$reqTglSTTB= dateToPageCheck($set->getField('TANGGAL_LULUS'));$valTglSTTB= checkwarna($reqPerubahanData, 'TANGGAL_LULUS', "date");
	$reqTahun= $set->getField('TAHUN_LULUS');$valTahun= checkwarna($reqPerubahanData, 'TAHUN_LULUS');
	$reqNomorIjazah= $set->getField('NOMOR_IJAZAH');$valNomorIjazah= checkwarna($reqPerubahanData, 'NOMOR_IJAZAH');
	$reqNamaSekolah= $set->getField('NAMA_SEKOLAH');$valNamaSekolah= checkwarna($reqPerubahanData, 'NAMA_SEKOLAH');
	$reqGelarDepan= $set->getField('GELAR_DEPAN');$valGelarDepan= checkwarna($reqPerubahanData, 'GELAR_DEPAN');

	$reqGelarBelakang= $set->getField('GELAR_BELAKANG');$valGelarBelakang= checkwarna($reqPerubahanData, 'GELAR_BELAKANG');

	$reqPendidikanCpns= $set->getField('PENDIDIKAN_CPNS');

	$reqValidasi= $set->getField('VALIDASI');
	$reqLinkFile= $set->getField('LINK_FILE');
	$reqFileId= $set->getField('PEGAWAI_PENDIDIKAN_RIWAYAT_FILE_ID');
	
}
	// print_r($reqPerubahanData);exit;

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
						<a class="text-muted">Riwayat Pendidikan</a>
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
                    <h3 class="card-label">Riwayat Pendidikan</h3>
                </div>
            </div>
            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
	        	<div class="card-body">
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Pendidikan
	        			<?
	        			if(!empty($valPendidikanId['data']))
	        			{
	        			?>
	        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valPendidikanId['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-10 col-sm-12">
	        				<select class="form-control select2 <?=$valPendidikanId['warna']?>" id="ktpendidikan" name="reqPejabatPenetap">
	        					<option value="<?=$reqPendidikanId?>"><?=$reqPendidikan?></option>
	        				</select>
	        				<span class="form-text text-muted"><label id="pendidikandetil"></label></span>
	        				<input type="hidden" name="reqPendidikanId" id="reqPendidikanId" value="<?=$reqPendidikanId?>" >
	        				<input type="hidden" name="reqPendidikan" id="reqPendidikan" value="<?=$reqPendidikan?>" >
	        			</div>
	        		</div>
	        		
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Jenjang Pendidikan
	        			<?
	        			if(!empty($valJurusan['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNamaKursus['data']?>"></i>
	        			<?
	        			}
	        			?>
	        		    </label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valJurusan['warna']?>"  readonly id="reqJurusan" name="reqJurusan" value="<?=$reqJurusan?>" />
	        				<input type="hidden" name="reqJurusanId" id="reqJurusanId" value="<?=$reqJurusanId?>" >
	        			</div>
	        			
	        		</div>

	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tanggal Lulus
	        			<?
	        			if(!empty($valTglSTTB['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTahun['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control <?=$valTglSTTB['warna']?>" <?=$disabled?> id="kttanggalsttb" name="reqTglSTTB" readonly="readonly" placeholder="Masukkan Tanggal Lulus" value="<?=$reqTglSTTB?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tahun Lulus
	        			<?
	        			if(!empty($valTahun['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTahun['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-1 col-sm-12">
	        				<input type="text" class="form-control <?=$valTahun['warna']?>" <?=$disabled?> id="reqTahun" name="reqTahun" readonly="readonly" value="<?=$reqTahun?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Nomor Ijazah
	        			<?
	        			if(!empty($valNomorIjazah['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNomorIjazah['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-10 col-sm-12">
	        				<input type="text" class="form-control  <?=$valNomorIjazah['warna']?>" <?=$disabled?> placeholder="Masukkan Nomor Ijazah" name="reqNomorIjazah" id="reqNomorIjazah" value="<?=$reqNomorIjazah?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Nama Sekolah
	        			<?
	        			if(!empty($valNamaSekolah['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNamaSekolah['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-10 col-sm-12">
	        				<input type="text" class="form-control <?=$valNamaSekolah['warna']?>" <?=$disabled?> placeholder="Masukkan Nama Sekolah" name="reqNamaSekolah" id="reqNamaSekolah" value="<?=$reqNamaSekolah?>" />
	        			</div>
	        		</div>
	        		
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Gelar Depan
	        			<?
	        			if(!empty($valGelarDepan['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valGelarDepan['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valGelarDepan['warna']?>" <?=$disabled?> placeholder="Masukkan Gelar Depan" name="reqGelarDepan" id="reqGelarDepan" value="<?=$reqGelarDepan?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Gelar Belakang
	        			<?
	        			if(!empty($valGelarBelakang['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valGelarBelakang['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valGelarBelakang['warna']?>" <?=$disabled?> placeholder="Masukkan Gelar Belakang" name="reqGelarBelakang" id="reqGelarBelakang" value="<?=$reqGelarBelakang?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12"></label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="checkbox" <?=$disabled?> <? if($reqPendidikanCpns == '1') echo 'checked';?>  name="reqPendidikanCpns" value="1" /><label style="width:auto;" > &nbsp;&nbsp;&nbsp;Pendidikan Pengangkatan CPNS</label> 
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

	        				<?if(empty($reqLinkFile))
	        				{
	        				?>
		        				<input type="button" id="upload" class="btn btn-success font-weight-bold mr-2" value="Upload" onclick="document.getElementById('reqLinkFile').click();" />
		        				<input type="file" style="display:none;" id="reqLinkFile" name="reqLinkFile"  accept=".pdf"/>
	        				<?
	        				}
	        				else
	        				{
	        				?>
	        					<a class="btn btn-success font-weight-bold mr-2" href="app/loadUrl/main/viewer?reqForm=pendidikan&reqFileId=<?=$reqFileId?>" target="_blank">Download</a>
	        					<a class="btn btn-danger font-weight-bold mr-2" onclick="btndeletefile('<?=$reqFileId?>','<?=$reqPegawaiId?>','<?=$reqDetilId?>','<?=$reqRowId?>')">Hapus File</a>
	        				<?
	        				}
	        				?>
	        				
	        			</div>
	        		</div>
	        	</div>
	        </form>
        </div>
    </div>
</div>>

<script type="text/javascript">
	$(document).on("input", "#reqThTMK,#reqMasaKerjaTahun,#reqMasaKerjaBulan,#reqMasaBerlaku,#reqGajiPokok", function() {
		this.value = this.value.replace(/\D/g,'');
	});
	function kembali() {
		window.location.href='app/index/riwayat_pendidikan'
	}

	function tampilPendidikan(val) {
        if (val.loading) return val.text;
        var markup = "<div class='select2-result-repository clearfix'>" +
	            "<div class='select2-result-repository__meta'>" +
	            	"<div class='select2-result-repository__title'>" + val.description + "</div>" +
	            "</div>" +
            "</div>";
        return markup;
    }
    function pilihPendidikan(val) {
    	$("#pendidikandetil").text(val.description);
    	$("#reqPendidikanId").val(val.id);
    	$("#reqPendidikan").val(val.text);
    	if (val.TINGKAT_PENDIDIKAN_ID) 
		{
			$("#reqJurusanId").val(val.TINGKAT_PENDIDIKAN_ID);	
		}
		if (val.TINGKAT_PENDIDIKAN) 
		{
			$("#reqJurusan").val(val.TINGKAT_PENDIDIKAN);	
		}	
        // console.log(val);
        return val.text;
    }

	$("#ktpendidikan").select2({
        placeholder: "Pilih Pendidikan",
        allowClear: true,
        ajax: {
            url: "json-validasi/combo_json/autocompleteriwayatpendidikan",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term
                    , page: params.page
                };
            },
            processResults: function(data, params) {
                params.page = params.page || 1;
                return {
                    results: data.items
                    , pagination: {
                        more: (params.page * 30) < data.total_count && data.items != ""
                    }
                };
            },
            cache: true
        },
        escapeMarkup: function(markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 1,
        templateResult: tampilPendidikan, // omitted for brevity, see the source of this page
        templateSelection: pilihPendidikan // omitted for brevity, see the source of this page
    });

	arrows= {leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'};
	$('#kttanggalsttb').datepicker({
		todayHighlight: true
		, autoclose: true
		, orientation: "bottom left"
		, clearBtn: true
		, format: 'dd-mm-yyyy'
		, templates: arrows
	}).on("changeDate", function (e) {
		var date = $(this).datepicker('getDate'),
		day  = date.getDate(),  
		month = date.getMonth() + 1,              
		year =  date.getFullYear();
		setTahun(year);
	});


	function setTahun(val) {
    	$("#reqTahun").val(val);
    }

	var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';
	jQuery(document).ready(function() {
		var form = KTUtil.getById('ktloginform');
		var formSubmitUrl = "json-validasi/personal_json/jsonriwayatpendidikanadd";
		var formSubmitButton = KTUtil.getById('ktloginformsubmitbutton');
		if (!form) {
			return;
		}
		FormValidation
		.formValidation(
			form,
			{
				fields: {
					reqNoSK: {
						validators: {
							notEmpty: {
								message: 'No Sk harus diisi'
							}
						}
					},
					reqThTMK: {
						validators: {
							notEmpty: {
								message: 'Tambahan tahun harus diisi'
							}
						}
					},
					reqBlTMK: {
						validators: {
							notEmpty: {
								message: 'Tambahan bulan harus diisi'
							}
						}
					},
					reqThMK: {
						validators: {
							notEmpty: {
								message: 'Tambahan tahun harus diisi'
							}
						}
					},
					reqBlMK: {
						validators: {
							notEmpty: {
								message: 'Tambahan bulan harus diisi'
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
			        		document.location.href = "app/index/riwayat_pendidikan";
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

	function btndeletefile(fileid,reqPegawaiId,reqDetilId,reqRowId) {
		if(fileid !== "")
        {
            urlAjax= "json-validasi/personal_json/jsonriwayatpendidikandeletefile?reqFileId="+fileid+"&reqPegawaiId="+reqPegawaiId+"&reqDetilId="+reqDetilId+"&reqRowId="+reqRowId;
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

