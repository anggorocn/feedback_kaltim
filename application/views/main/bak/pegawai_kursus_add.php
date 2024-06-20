<?
include_once("functions/personal.func.php");
$this->load->model("base-validasi/Kursus");
$this->load->model("base-validasi/Instansi");

$this->load->model("base-validasi/HapusData");


$reqPegawaiId= $this->input->get('reqPegawaiId');
$reqRowId= $this->input->get('reqRowId');
$reqDetilId= $this->input->get('reqDetilId');
$reqMode= $this->input->get('reqMode');
$reqValidasiHapusId= $this->input->get('reqValidasiHapusId');
$adminuserid= $this->adminuserid;

$statement="";
$sOrder="";
$set= new Instansi();
$arrinstansi= [];
$set->selectByParams(array(), -1,-1,$statement,$sOrder);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	$arrdata["id"]= $set->getField("INSTANSI_ID");
	$arrdata["text"]= $set->getField("NAMA");
	array_push($arrinstansi, $arrdata);
}
unset($set);

$set = new Kursus();
if($reqMode == 'edit' || $reqMode == 'cancel' || $reqMode == 'view')
{
	if($reqDetilId == "")
	{
		$set->selectByParams(array('A.KURSUS_ID'=>$reqRowId));
	}
	else
	{
		$set->selectByParams(array('A.TEMP_VALIDASI_ID'=>$reqDetilId));
	}
	 // echo $set->query;exit;
	$set->firstRow();
	$reqRowId= $set->getField('KURSUS_ID');
	$reqPerubahanData= $set->getField('PERUBAHAN_DATA');
	$reqDataId=$set->getField('TEMP_VALIDASI_ID');
	$reqTipeKursus= $set->getField('TIPE');
	$arrdata = jenisbahasa($reqTipeKursus);
	$valTipeKursus= checkwarna($reqPerubahanData, 'TIPE',$arrdata, array("id","text"));
	$reqNamaKursus= $set->getField('NAMA');$valNamaKursus= checkwarna($reqPerubahanData, 'NAMA');
	$reqLamaKursus= $set->getField('LAMA');$valLamaKursus= checkwarna($reqPerubahanData, 'LAMA');
	$reqTahunKursus= $set->getField('TAHUN');$valTahunKursus= checkwarna($reqPerubahanData, 'TAHUN');
	$reqTanggalKursus= dateToPageCheck($set->getField('TANGGAL_MULAI'));$valTanggalKursus= checkwarna($reqPerubahanData, 'TANGGAL_MULAI',$arrdata, array("id","text"));
	$reqNoSertifikat= $set->getField('NO_PIAGAM');$valNoSertifikat= checkwarna($reqPerubahanData, 'NO_PIAGAM');
	$reqInstansiId= $set->getField('INSTANSI_ID');
	$reqInstansiNama= $set->getField('INSTANSI_NAMA');
	$reqInstitusi= $set->getField('PENYELENGGARA');$valInstitusi= checkwarna($reqPerubahanData, 'PENYELENGGARA');
	$valInstansi= checkwarna($reqPerubahanData, 'INSTANSI_ID',$arrinstansi, array("id","text"));
	$reqValidasi= $set->getField('VALIDASI');
	$reqLinkFile= $set->getField('LINK_FILE');
	$reqFileId= $set->getField('KURSUS_FILE_ID');
	$reqLinkFileServer= $set->getField('LINK_SERVER');

	
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
						<a class="text-muted">Kursus</a>
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
                    <h3 class="card-label">Kursus</h3>
                </div>
            </div>
            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
	        	<div class="card-body">
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tipe Kursus
	        			<?
	        			if(!empty($valTipeKursus['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTipeKursus['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-8 col-sm-12">
	        				<select class="form-control select2 <?=$valTipeKursus['warna']?>" id="kttipe" name="reqTipeKursus" style="width:auto;">
	        					<option value="1" <? if($reqTipeKursus == 1) echo 'selected'?>>DIKLAT TEKNIS</option>
	        					<option value="2" <? if($reqTipeKursus == 2) echo 'selected'?>>DIKLAT FUNGSIONAL</option>
	        					<option value="3" <? if($reqTipeKursus == 3) echo 'selected'?>>SEMINAR/WORKSHOP/MAGANG/SEJENISNYA</option>
	        				</select>
	        			</div>	        				
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Nama Kursus
	        			<?
	        			if(!empty($valNamaKursus['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNamaKursus['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-7 col-sm-12">
	        				<input type="text" class="form-control <?=$valNamaKursus['warna']?>" placeholder="Masukkan Nama Kursus"name="reqNamaKursus" id="reqNamaKursus" value="<?=$reqNamaKursus?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Lamanya Kursus
	        			<?
	        			if(!empty($valLamaKursus['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valLamaKursus['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
						
	        			<div class="col-lg-1 col-sm-12">
	        				<input type="text" class="form-control <?=$valLamaKursus['warna']?>" name="reqLamaKursus" id="reqLamaKursus" value="<?=$reqLamaKursus?>" />
	        			</div>
						<label class="col-form-label text-left">Jam</label>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tanggal Kursus
	        			<?
	        			if(!empty($valTanggalKursus['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTanggalKursus['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control <?=$valTanggalKursus['warna']?>"  id="kttglkursus" name="reqTanggalKursus" readonly="readonly" placeholder="Masukkan Tgl Kursus" value="<?=$reqTanggalKursus?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div> 
	        			<div class="col-lg-1 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control <?=$valTahunKursus['warna']?>" readonly style="background-color: #F3F6F9;" name="reqTahunKursus" id="reqTahunKursus" value="<?=$reqTahunKursus?>" />
		        			</div>
	        			</div>    		
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">No. Sertifikat
	        			<?
	        			if(!empty($valNoSertifikat['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNoSertifikat['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-5 col-sm-12">
	        				<input type="text" class="form-control <?=$valNoSertifikat['warna']?>" placeholder="Masukkan No Sertifikat"name="reqNoSertifikat" id="reqNoSertifikat" value="<?=$reqNoSertifikat?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Instansi
	        			<?
	        			if(!empty($valInstansi['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valInstansi['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-10 col-sm-12">
	        				<select class="form-control select2 <?=$valInstansi['warna']?>" id="ktinstansi" name="reqInstansiNama">
	        					<option value="<?=$reqInstansiId?>"><?=$reqInstansiNama?></option>
	        				</select>
	        				<span class="form-text text-muted"><label id="instansidetil"></label></span>
	        				<input type="hidden" name="reqInstansiId" id="reqInstansiId" value="<?=$reqInstansiId?>" >
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Institusi Penyelenggara
	        			<?
	        			if(!empty($valInstitusi['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valInstitusi['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-5 col-sm-12">
	        				<input type="text" class="form-control <?=$valInstitusi['warna']?>" placeholder="Masukkan Institusi"name="reqInstitusi" id="reqInstitusi" value="<?=$reqInstitusi?>" />
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

	        				<?if(empty($reqLinkFileServer))
	        				{
	        				?>
		        				<input type="button" id="upload" class="btn btn-success font-weight-bold mr-2" value="Upload" onclick="document.getElementById('reqLinkFile').click();" />
		        				<input type="file" style="display:none;" id="reqLinkFile" name="reqLinkFile"  accept=".pdf"/>
	        				<?
	        				}
	        				else
	        				{
	        				?>
	        					<a class="btn btn-success font-weight-bold mr-2" href="app/loadUrl/main/viewer?reqForm=kursus&reqFileId=<?=$reqFileId?>" target="_blank">Download</a>
	        					<a class="btn btn-danger font-weight-bold mr-2" onclick="btndeletefile(<?=$reqFileId?>,<?=$reqPegawaiId?>,<?=$reqDetilId?>,<?=$reqRowId?>)">Hapus File</a>
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
		window.location.href='app/index/pegawai_kursus'
	}

	function tampilInstansi(val) {
        if (val.loading) return val.text;
        var markup = "<div class='select2-result-repository clearfix'>" +
	            "<div class='select2-result-repository__meta'>" +
	            	"<div class='select2-result-repository__title'>" + val.description + "</div>" +
	            "</div>" +
            "</div>";
        return markup;
    }
    function pilihInstansi(val) {
    	$("#instansidetil").text(val.description);
    	$("#reqInstansiId").val(val.id);
    	$("#reqInstansinama").val(val.text);	
        // console.log(val);
        return val.text;
    }

	$('#kttipe').select2({
		placeholder: "Pilih tipe"
	});

    $("#ktinstansi").select2({
        placeholder: "Pilih instansi",
        allowClear: true,
        ajax: {
            url: "json-validasi/combo_json/autocompleteinstansi",
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
        templateResult: tampilInstansi, // omitted for brevity, see the source of this page
        templateSelection: pilihInstansi // omitted for brevity, see the source of this page
    });

    arrows= {leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'};
	$('#kttglkursus').datepicker({
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
    	$("#reqTahunKursus").val(val);
    }

	var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';
	jQuery(document).ready(function() {
		var form = KTUtil.getById('ktloginform');
		var formSubmitUrl = "json-validasi/personal_json/jsonpegawaikursusadd";
		var formSubmitButton = KTUtil.getById('ktloginformsubmitbutton');
		if (!form) {
			return;
		}
		FormValidation
		.formValidation(
			form,
			{
				fields: {
					reqInstansi: {
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
			        	Swal.fire({
			        		text: response.message,
			        		icon: "success",
			        		buttonsStyling: false,
			        		confirmButtonText: "Ok",
			        		customClass: {
			        			confirmButton: "btn font-weight-bold btn-light-primary"
			        		}
			        	}).then(function() {
			        		document.location.href = "app/index/pegawai_kursus";
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
            urlAjax= "json-validasi/personal_json/jsonpegawaikursusdeletefile?reqFileId="+fileid+"&reqPegawaiId="+reqPegawaiId+"&reqDetilId="+reqDetilId+"&reqRowId="+reqRowId;
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

