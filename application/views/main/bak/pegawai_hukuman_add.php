<?

include_once("functions/personal.func.php");

$this->load->model("base-validasi/Hukuman");
$this->load->model("base-personal/TingkatHukuman");
$this->load->model("base-personal/JenisHukuman");
$this->load->model("base-validasi/HapusData");
$this->load->model("base-validasi/PejabatPenetap");


$reqPegawaiId= $this->input->get('reqPegawaiId');
$reqRowId= $this->input->get('reqRowId');
$reqDetilId= $this->input->get('reqDetilId');
$reqMode= $this->input->get('reqMode');
$reqValidasiHapusId= $this->input->get('reqValidasiHapusId');
$folder = $this->config->item('simpeg');


$adminuserid= $this->adminuserid;

$statement="";
$sOrder="";
$set= new PejabatPenetap();
$arrpejabat= [];
$set->selectByParams(array(), -1,-1,$statement,$sOrder);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	$arrdata["id"]= $set->getField("PEJABAT_PENETAP_ID");
	$arrdata["text"]= $set->getField("JABATAN");
	array_push($arrpejabat, $arrdata);
}
unset($set);

$statement="";
$sOrder="";
$set= new TingkatHukuman();
$arrtipe= [];
$set->selectByParams(array(), -1,-1,$statement,$sOrder);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	$arrdata["id"]= $set->getField("TINGKAT_HUKUMAN_ID");
	$arrdata["text"]= $set->getField("TINGKAT_HUKUMAN");
	array_push($arrtipe, $arrdata);
}
unset($set);

$statement="";
$sOrder="";
$set= new JenisHukuman();
$arrjenis= [];
$set->selectByParams(array(), -1,-1,$statement,$sOrder);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	$arrdata["id"]= $set->getField("JENIS_HUKUMAN_ID");
	$arrdata["text"]= $set->getField("NAMA");
	array_push($arrjenis, $arrdata);
}
unset($set);



$set = new Hukuman();
if($reqMode == 'edit' || $reqMode == 'cancel' || $reqMode == 'view')
{
	if($reqDetilId == "")
	{
		$set->selectByParams(array('A.HUKUMAN_ID'=>$reqRowId));
	}
	else
	{
		$set->selectByParams(array('A.TEMP_VALIDASI_ID'=>$reqDetilId));
	}
	// echo $set->query;exit;
	$set->firstRow();
	$reqPerubahanData= $set->getField('PERUBAHAN_DATA');
	$reqRowId= $set->getField('HUKUMAN_ID');
	$reqDataId= $set->getField('TEMP_VALIDASI_ID');
	if( $set->getField('PEJABAT_PENETAP_ID')==''){
		$reqStatus='baru';
		$reqDisplayBaru='';
		$reqDisplay='none';
	}else{
		$reqDisplayBaru='none';
		$reqDisplay='';
	}
	$reqPjPenetapNama= $set->getField('PEJABAT_PENETAP');
	$reqPjPenetapId	= $set->getField('PEJABAT_PENETAP_ID');
	$reqNoSK= $set->getField('NO_SK');$valNoSK= checkwarna($reqPerubahanData, 'NO_SK');
	$reqPejabatPenetap= $set->getField('PEJABAT_PENETAP_ID');
	$reqTanggalSK= dateToPageCheck($set->getField('TANGGAL_SK'));$valTanggalSK= checkwarna($reqPerubahanData, 'TANGGAL_SK', "date");
	$reqTMTSK= dateToPageCheck($set->getField('TMT_SK'));$valTMTSK= checkwarna($reqPerubahanData, 'TMT_SK', "date");
	$reqJenisHukuman= $set->getField('JENIS_HUKUMAN_ID');$valJenisHukuman= checkwarna($reqPerubahanData, 'JENIS_HUKUMAN_ID', $arrjenis, array("id","text"));
	$reqTingkatHukuman= $set->getField('TINGKAT_HUKUMAN_ID');$valTingkatHukuman= checkwarna($reqPerubahanData, 'TINGKAT_HUKUMAN_ID', $arrtipe, array("id","text"));
	$reqPeraturan= $set->getField('PERATURAN_ID');
	$reqPermasalahan= $set->getField('KETERANGAN');$valPermasalahan= checkwarna($reqPerubahanData, 'KETERANGAN');
	$reqMasihBerlaku= $set->getField('STATUS_BERLAKU');$valMasihBerlaku= checkwarna($reqPerubahanData, 'STATUS_BERLAKU');
	if($reqMasihBerlaku == 'Ya')
	{
		$reqMasihBerlaku=1;
	}
	else
	{
		$reqMasihBerlaku=0;
	}
	$reqTanggalMulai= dateToPageCheck($set->getField('TANGGAL_MULAI'));$valTanggalMulai= checkwarna($reqPerubahanData, 'TANGGAL_MULAI', "date");
	$reqTanggalAkhir= dateToPageCheck($set->getField('TANGGAL_AKHIR'));$valTanggalAkhir= checkwarna($reqPerubahanData, 'TANGGAL_AKHIR', "date");
	
	$reqPejabatPenetap= $set->getField('PEJABAT_PENETAP');
	$reqPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');$valPejabatPenetap= checkwarna($reqPerubahanData, 'PEJABAT_PENETAP_ID', $arrpejabat, array("id","text"));

	$reqValidasi= $set->getField('VALIDASI');
	$reqLinkFile= $set->getField('LINK_FILE');
	$reqFileId= $set->getField('HUKUMAN_FILE_ID');
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
						<a class="text-muted">Hukuman</a>
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
                    <h3 class="card-label">Hukuman</h3>
                </div>
            </div>
            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
	        	<div class="card-body">
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tingkat Hukuman
	        			<?
	        			if(!empty($valTingkatHukuman['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTingkatHukuman['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-8 col-sm-12">
	        				<select class="form-control select2 <?=$valTingkatHukuman['warna']?>" id="kttingkat" name="reqTingkatHukuman" style="width:40%; ">
	        					<option value=""></option>
	        					<?
	        					foreach($arrtipe as $item) 
	        					{
	        						$selectvalid= $item["id"];
	        						$selectvaltext= $item["text"];
	        					?>
	        					<option value="<?=$selectvalid?>" <? if($reqTingkatHukuman == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
	        					<?
	        					}
	        					?>
	        				</select>
	        				<input type="checkbox" name="reqMasihBerlaku" disabled="disabled" value="1" <? if($reqMasihBerlaku == 1) echo 'checked'?>/> Masih Berlaku<br />
	        			</div>	        				
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Jenis Hukuman
	        			<?
	        			if(!empty($valJenisHukuman['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valJenisHukuman['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-10 col-sm-12">
	        				<select class="form-control select2 <?=$valJenisHukuman['warna']?>" id="ktjenis" name="reqJenisHukuman" style="width:70%; ">
	        					<option value=""></option>
	        					<?
	        					foreach($arrjenis as $item) 
	        					{
	        						$selectvalid= $item["id"];
	        						$selectvaltext= $item["text"];
	        					?>
	        					<option value="<?=$selectvalid?>" <? if($reqJenisHukuman == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
	        					<?
	        					}
	        					?>
	        				</select>
	        				
	        			</div>	        				
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">No. SK
	        			<?
	        			if(!empty($valNoSK['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNoSK['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valNoSK['warna']?>" placeholder="Masukkan No. Surat Keputusan"name="reqNoSK" id="reqNoSK" value="<?=$reqNoSK?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tanggal SK
	        			<?
	        			if(!empty($valTanggalSK['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTanggalSK['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control <?=$valTanggalSK['warna']?>" id="kttanggalkeputusan" name="reqTanggalSK" readonly="readonly" placeholder="Masukkan Tanggal Surat Keputusan" value="<?=$reqTanggalSK?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">TMT SK:
	        			<?
	        			if(!empty($valTMTSK['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTMTSK['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control <?=$valTMTSK['warna']?>" id="kttmtsk" name="reqTMTSK" readonly="readonly" placeholder="Masukkan TMT SK" value="<?=$reqTMTSK?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tanggal Awal
	        			<?
	        			if(!empty($valTanggalMulai['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTanggalMulai['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control <?=$valTanggalMulai['warna']?>" id="kttanggalmulai" name="reqTanggalMulai" readonly="readonly" placeholder="Masukkan Tanggal Mulai" value="<?=$reqTanggalMulai?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tanggal Akhir
	        			<?
	        			if(!empty($valTanggalAkhir['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTanggalAkhir['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control <?=$valTanggalAkhir['warna']?>" id="kttanggalakhir" name="reqTanggalAkhir" readonly="readonly" placeholder="Masukkan Tanggal Akhir" value="<?=$reqTanggalAkhir?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Permasalahan
	        			<?
	        			if(!empty($valPermasalahan['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valPermasalahan['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control select2 <?=$valPermasalahan['warna']?>"  name="reqPermasalahan"  value="<?=$reqPermasalahan?>" />
	        			</div>
	        			
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Pejabat Penetap
	        			<?
	        			if(!empty($valPejabatPenetap['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valPejabatPenetap['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-10 col-sm-12">
	        				<select class="form-control select2 <?=$valPejabatPenetap['warna']?>" id="ktpejabat" name="reqPejabatPenetap">
	        					<option value="<?=$reqPejabatPenetapId?>"><?=$reqPejabatPenetap?></option>
	        				</select>
	        				<span class="form-text text-muted"><label id="pejabatdetil"></label></span>
	        				<input type="hidden" name="reqPejabatPenetapId" id="reqPejabatPenetapId" value="<?=$reqPejabatPenetapId?>" >
	        				<input type="hidden" name="reqPejabatPenetap" id="reqPejabatPenetap" value="<?=$reqPejabatPenetap?>" >
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

	        				<?if(!file_exists($reqLinkFileServer))
	        				{
	        				?>
		        				<input type="button" id="upload" class="btn btn-success font-weight-bold mr-2" value="Upload" onclick="document.getElementById('reqLinkFile').click();" />
		        				<input type="file" style="display:none;" id="reqLinkFile" name="reqLinkFile"  accept=".pdf"/>
		        				<? 
	        					$file_name_direktori = "../".$folder."/uploads/hukuman/FOTO_BLOB-".$reqRowId.".pdf";
	        					if (file_exists($file_name_direktori)) 
	        					{
	        						?>
	        						<a class="btn btn-primary font-weight-bold mr-2" href="<?=$file_name_direktori?>" target="_blank">Download</a>
	        						<? 
	        					}
	        					?>
	        				<?
	        				}
	        				else
	        				{
	        				?>
	        					<a class="btn btn-success font-weight-bold mr-2" href="app/loadUrl/main/viewer?reqForm=hukuman&reqFileId=<?=$reqFileId?>" target="_blank">Download</a>
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
		window.location.href='app/index/pegawai_hukuman'
	}

	function tampilPejabatPenetap(val) {
        if (val.loading) return val.text;
        var markup = "<div class='select2-result-repository clearfix'>" +
	            "<div class='select2-result-repository__meta'>" +
	            	"<div class='select2-result-repository__title'>" + val.description + "</div>" +
	            "</div>" +
            "</div>";
        return markup;
    }
    function pilihPejabatPenetap(val) {
    	$("#pejabatdetil").text(val.description);
    	$("#reqPejabatPenetapId").val(val.id);
    	$("#reqPejabatPenetap").val(val.text);	
        // console.log(val);
        return val.text;
    }
	$('#kttingkat').select2({
		placeholder: "Pilih tingkat"
	});
	$('#ktjenis').select2({
		placeholder: "Pilih jenis"
	});
	$("#ktpejabat").select2({
        placeholder: "Pilih pejabat penetap",
        allowClear: true,
        ajax: {
            url: "json-validasi/combo_json/autocompletepejabatpenetap",
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
        templateResult: tampilPejabatPenetap, // omitted for brevity, see the source of this page
        templateSelection: pilihPejabatPenetap // omitted for brevity, see the source of this page
    });
    arrows= {leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'};
	$('#kttanggalkeputusan,#kttanggalmulai,#kttanggalakhir,#kttmtjabatan,#kttmtsk').datepicker({
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
		var formSubmitUrl = "json-validasi/personal_json/jsonpegawaihukumanadd";
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
			        		document.location.href = "app/index/pegawai_hukuman";
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

	function btndeletefile(fileid,reqPegawaiId,reqDetilId,reqRowId) {
		if(fileid !== "")
        {
            urlAjax= "json-validasi/personal_json/jsonpegawaihukumandeletefile?reqFileId="+fileid+"&reqPegawaiId="+reqPegawaiId+"&reqDetilId="+reqDetilId+"&reqRowId="+reqRowId;
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

