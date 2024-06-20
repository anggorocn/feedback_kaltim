<?
include_once("functions/personal.func.php");

$this->load->model("base-validasi/TambahanMasaKerja");
$this->load->model("base-validasi/Pangkat");
$this->load->model("base-validasi/PejabatPenetap");
$this->load->model("base-validasi/Pangkat");

$reqPegawaiId= $this->pegawaiId;
$reqSatkerId= $this->input->get('reqIdOrganisasi');
$adminuserid= $this->adminuserid;
$folder = $this->config->item('simpeg');



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

$set= new Pangkat();
$arrgol= [];
$set->selectByParams();
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	$arrdata["id"]= $set->getField("PANGKAT_ID");
	$arrdata["text"]= $set->getField("KODE");
	array_push($arrgol, $arrdata);
}
unset($set);


$set   = new TambahanMasaKerja();
if (!empty($adminuserid))
{
	$set->selectByParams(array("A.PEGAWAI_ID" => $reqPegawaiId), -1, -1, " AND A.TEMP_VALIDASI_ID IS NOT NULL ");
}
else
{
	$set->selectByParams(array("A.PEGAWAI_ID" => $reqPegawaiId), -1, -1, " AND VALIDASI IS NULL ");

}
$set->firstRow();
// echo $set->query;exit;
$reqRowId= $set->getField('TAMBAHAN_MASA_KERJA_ID');
$reqDataId= $set->getField('TEMP_VALIDASI_ID');
$reqPerubahanData= $set->getField('PERUBAHAN_DATA');
// echo $reqPerubahanData;exit;
$reqTambMKId= $set->getField('TAMBAHAN_MASA_KERJA_ID');
$reqNoSK= $set->getField('NO_SK');$valNoSK= checkwarna($reqPerubahanData, 'NO_SK');
$reqTglSK= dateToPageCheck($set->getField('TANGGAL_SK'));$valTglSK= checkwarna($reqPerubahanData, 'TANGGAL_SK', "date");
$reqTMTSK= dateToPageCheck($set->getField('TMT_SK'));$valTMTSK= checkwarna($reqPerubahanData, 'TMT_SK', "date");
$reqTambahanMasaKerja= $set->getField('TAMBAHAN_MASA_KERJA_ID');
$reqMasaKerja= $set->getField('TAMBAHAN_MASA_KERJA_ID');
$reqThTMK= $set->getField('TAHUN_TAMBAHAN');$valThTMK= checkwarna($reqPerubahanData, 'TAHUN_TAMBAHAN');
$reqThMK= $set->getField('TAHUN_BARU');$valThMK= checkwarna($reqPerubahanData, 'TAHUN_BARU');
$reqBlTMK= $set->getField('BULAN_TAMBAHAN');$valBlTMK= checkwarna($reqPerubahanData, 'BULAN_TAMBAHAN');
$reqBlMK= $set->getField('BULAN_BARU');$valBlMK= checkwarna($reqPerubahanData, 'BULAN_BARU');

$reqPejabatPenetap= $set->getField("PEJABAT_PENETAP");$valPejabatPenetap= checkwarna($reqPerubahanData, 'PEJABAT_PENETAP');
$reqPejabatPenetapId= $set->getField("PEJABAT_PENETAP_ID");$valPejabatPenetapId= checkwarna($reqPerubahanData, 'PEJABAT_PENETAP_ID', $arrpejabat, array("id","text"));
$reqGajiPokok= $set->getField('GAJI_POKOK');$valGajiPokok= checkwarna($reqPerubahanData, 'GAJI_POKOK');
$reqGolRuang= $set->getField('PANGKAT_ID');$valGolRuang= checkwarna($reqPerubahanData, 'PANGKAT_ID', $arrgol, array("id","text"));
$data = $set->getField('FOTO_BLOB');
$reqValidasi= $set->getField('VALIDASI');
$reqLinkFile= $set->getField('LINK_FILE');
$reqFileId= $set->getField('TAMBAHAN_MASA_KERJA_FILE_ID');
$reqLinkFile= $set->getField('LINK_SERVER');	

unset($set);

if($reqDataId == "")
	$reqMode= "insert";
else
	$reqMode= "update";



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
						<a class="text-muted">Tambahan Masa Kerja</a>
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
                    <h3 class="card-label">Tambahan Masa Kerja</h3>
                </div>
            </div>
            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
	        	<div class="card-body">
	        		
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
	        				<input type="text" class="form-control <?=$valNoSK['warna']?>" placeholder="Masukkan No. SK"name="reqNoSK" id="reqNoSK" value="<?=$reqNoSK?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Gol/Ruang
	        			<?
	        			if(!empty($valGolRuang['data']))
	        			{
	        			?>
	        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valGolRuang['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<select class="form-control select2 <?=$valGolRuang['warna']?>" id="ktgolid" name="reqGolRuang">
	        					<option value=""></option>
	        					<?
	        					foreach($arrgol as $item) 
	        					{
	        						$selectvalid= $item["id"];
	        						$selectvaltext= $item["text"];
	        					?>
	        					<option value="<?=$selectvalid?>" <? if($reqGolRuang == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
	        					<?
	        					}
	        					?>
	        				</select>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Pejabat Penetap
	        			<?
	        			if(!empty($valPejabatPenetapId['data']))
	        			{
	        			?>
	        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valPejabatPenetapId['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-10 col-sm-12">
	        				<select class="form-control select2 <?=$valPejabatPenetapId['warna']?>" id="ktsatuankerja" name="reqPejabatPenetap">
	        					<option value="<?=$reqPejabatPenetapId?>"><?=$reqPejabatPenetap?></option>
	        				</select>
	        				<span class="form-text text-muted"><label id="pejabatdetil"></label></span>
	        				<input type="hidden" name="reqPejabatPenetapId" id="reqPejabatPenetapId" value="<?=$reqPejabatPenetapId?>" >
	        				<input type="hidden" name="reqPejabatPenetap" id="reqPejabatPenetap" value="<?=$reqPejabatPenetap?>" >
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Gaji Pokok
	        			<?
	        			if(!empty($valGajiPokok['data']))
	        			{
	        			?>
	        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valGajiPokok['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valGajiPokok['warna']?>"  OnFocus="FormatAngka('reqGajiPokok')" OnKeyUp="FormatUang('reqGajiPokok')" OnBlur="FormatUang('reqGajiPokok')" placeholder="Masukkan Gaji Pokok" name="reqGajiPokok" id="reqGajiPokok" value="<?=numberToIna($reqGajiPokok)?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tanggal SK
	        			<?
	        			if(!empty($valTglSK['data']))
	        			{
	        			?>
	        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTglSK['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
	        					<input type="text" class="form-control <?=$valTglSK['warna']?>" id="kttanggalkeputusan" name="reqTglSK" readonly="readonly" placeholder="Masukkan Tanggal SK" value="<?=$reqTglSK?>" />
	        					<div class="input-group-append">
	        						<span class="input-group-text">
	        							<i class="la la-calendar"></i>
	        						</span>
	        					</div>
	        				</div>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">TMT SK
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
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tambahan Masa Kerja
	        			<?
	        			if(!empty($valThTMK['data']))
	        			{
	        			?>
	        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valThTMK['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="form-group col-xs-3 col-xs-offset-1">
	        				<input type="text" style="width:100px;margin-left: 11px" class="form-control <?=$valThTMK['warna']?>" placeholder="Tahun" maxlength="4" name="reqThTMK" id="reqThTMK" value="<?=$reqThTMK?>" />
	        			</div>
	        			<div class="form-group col-xs-3 ">
	        				Th
	        			</div>
	        			<div class="form-group col-xs-3">
	        				<input type="text" style="width:100px;" class="form-control <?=$valBlTMK['warna']?>" placeholder="Bulan" maxlength="2" name="reqBlTMK" id="reqBlTMK" value="<?=$reqBlTMK?>" />
	        			</div>
	        			<div class="form-group col-xs-3">
	        				Bl
	        			</div>
	        			<label class="col-form-label text-right col-lg-3 col-sm-12" style="margin-left: 11px">Masa Kerja
	        			<?
	        			if(!empty($valThMK['data']))
	        			{
	        			?>
	        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valThMK['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="form-group col-xs-3 col-xs-offset-1">
	        				<input type="text" style="width:100px;margin-left: 11px" class="form-control <?=$valThMK['warna']?>" placeholder="Tahun" maxlength="4" name="reqThMK" id="reqThMK" value="<?=$reqThMK?>" />
	        			</div>
	        			<div class="form-group col-xs-3">
	        				Th
	        			</div>
	        			<div class="form-group col-xs-3">
	        				<input type="text" style="width:100px;" class="form-control <?=$valBlMK['warna']?>" placeholder="Bulan" maxlength="2" name="reqBlMK" id="reqBlMK" value="<?=$reqBlMK?>" />
	        			</div>
	        			<div class="form-group col-xs-3">
	        				Bl
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Lampiran</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<?if(!file_exists($reqLinkFile))
	        				{

	        				?>
		        				<input type="button" id="upload" class="btn btn-success font-weight-bold mr-2" value="Upload" onclick="document.getElementById('reqLinkFile').click();" />
		        				<input type="file" style="display:none;" id="reqLinkFile" name="reqLinkFile"  accept=".pdf"/>
		        				<? 
	        					$file_name_direktori = "../".$folder."/uploads/tambahan_masa_kerja/FOTO_BLOB-".$reqPegawaiId.".pdf";
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
	        					<a class="btn btn-success font-weight-bold mr-2" href="app/loadUrl/main/viewer?reqForm=kursus&reqFileId=<?=$reqFileId?>" target="_blank">Download</a>
	        					<a class="btn btn-danger font-weight-bold mr-2" onclick="btndeletefile(<?=$reqFileId?>,<?=$reqPegawaiId?>,'',<?=$reqRowId?>)">Hapus File</a>
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
	        				<?
	        				if(!empty($adminuserid) && empty($reqValidasi))
	        				{
	        				?>
	        					<button type="submit" id="ktloginformsubmitbutton"  name="reqStatusValidasi"  class="btn btn-primary font-weight-bold mr-2" value="1">Validasi</button>
	        					<button type="submit" id="ktloginformsubmitbutton" name="reqStatusValidasi"  class="btn btn-danger font-weight-bold mr-2" value="0">Tolak</button>
	        				<?
	        				}
	        				else
	        				{
	        				?>
		        				<button type="submit" id="ktloginformsubmitbutton"  class="btn btn-primary font-weight-bold mr-2">Simpan</button>
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
	$(document).on("input", "#reqThTMK,#reqBlTMK,#reqThMK,#reqBlMK,#reqGajiPokok", function() {
		this.value = this.value.replace(/\D/g,'');
	});
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
	$('#ktgolid').select2({
		placeholder: "Pilih gol/ruang"
	});
	$("#ktsatuankerja").select2({
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
	$('#kttmtsk,#kttanggalkeputusan').datepicker({
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
		var formSubmitUrl = "json-validasi/personal_json/jsontambahanmasakerjaadd";
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
					// reqPejabatPenetap: {
					// 	validators: {
					// 		notEmpty: {
					// 			message: 'Pejabat Penetap harus diisi'
					// 		}
					// 	}
					// },
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
			        		document.location.href = "app/index/tambahan_masa_kerja";
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
            urlAjax= "json-validasi/personal_json/jsonmasakerjadeletefile?reqFileId="+fileid+"&reqPegawaiId="+reqPegawaiId+"&reqDetilId="+reqDetilId+"&reqRowId="+reqRowId;
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

