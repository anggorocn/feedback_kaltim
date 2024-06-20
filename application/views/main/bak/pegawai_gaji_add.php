<?
$this->load->model("base-validasi/GajiRiwayat");
$this->load->model("base-validasi/Pangkat");
$this->load->model("base-validasi/HapusData");
$this->load->model("base-validasi/Pegawai");
$this->load->model("base-validasi/Eselon");

$reqPegawaiId= $this->input->get('reqPegawaiId');
$reqRowId= $this->input->get('reqRowId');
$reqDetilId= $this->input->get('reqDetilId');
$reqMode= $this->input->get('reqMode');
$reqValidasiHapusId= $this->input->get('reqValidasiHapusId');
$adminuserid= $this->adminuserid;
$folder = $this->config->item('simpeg');


$set = new GajiRiwayat();
if($reqMode == 'edit' || $reqMode == 'cancel' || $reqMode == 'view')
{
	// if($reqDetilId == "")
	// {
	// 	$set->selectByParams(array('A.GAJI_RIWAYAT_ID'=>$reqRowId));
	// }
	// else
	// {
	// 	$set->selectByParams(array('TEMP_VALIDASI_ID'=>$reqDetilId));
	// }
	$set->selectByParamsAdmin(array('A.GAJI_RIWAYAT_ID'=>$reqRowId));
	// echo $set->query;exit;
	$set->firstRow();
	$reqRowId= $set->getField('GAJI_RIWAYAT_ID');
	$reqDataId= $set->getField('TEMP_VALIDASI_ID');
	$reqPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
	$reqNoSK= $set->getField('NO_SK');
	$reqGolRuang= $set->getField('PANGKAT_ID');
	$reqTglSK= dateToPageCheck($set->getField('TANGGAL_SK'));
	$reqGajiPokok= $set->getField('GAJI_POKOK');
	$reqTh= $set->getField('MASA_KERJA_TAHUN');
	$reqBl= $set->getField('MASA_KERJA_BULAN');
	$reqJenis= $set->getField('JENIS_KENAIKAN');
	$reqPejabatPenetap= $set->getField('PEJABAT_PENETAP');
	$reqPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
	$reqTMTSK= dateToPageCheck($set->getField('TMT_SK'));
	$reqPerubahanData= $set->getField('PERUBAHAN_DATA');
	$reqLinkFile= $set->getField('LINK_FILE');
	$reqFileId= $set->getField('GAJI_RIWAYAT_FILE_ID');
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

$statement="";
$sOrder="";
$set= new Pangkat();
$arrpangkat= [];
$set->selectByParams(array(), -1,-1,$statement,$sOrder);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	$arrdata["id"]= $set->getField("PANGKAT_ID");
	$arrdata["text"]= $set->getField("KODE");
	array_push($arrpangkat, $arrdata);
}
unset($set);
// print_r($arragama);exit;
$disabled = "";
if (empty($adminuserid))
{
	$disabled = "disabled";	
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
						<a class="text-muted">Riwayat Gaji</a>
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
                    <h3 class="card-label">Riwayat Gaji</h3>
                </div>
            </div>
            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
	        	<div class="card-body">
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">No. SK</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" <?=$disabled?> placeholder="Masukkan No. Surat Keputusan"name="reqNoSK" id="reqNoSK" value="<?=$reqNoSK?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tanggal SK</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control" <?=$disabled?> id="kttanggalkeputusan" name="reqTglSK" readonly="readonly" placeholder="Masukkan Tanggal Surat Keputusan" value="<?=$reqTglSK?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Gol/Ruang</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<select class="form-control select2" <?=$disabled?> id="ktgol" name="reqGolRuang" style="width:30%; ">
	        					<option value=""></option>
	        					<?
	        					foreach($arrpangkat as $item) 
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
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">TMT SK:</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control" <?=$disabled?> id="kttmtsk" name="reqTMTSK" readonly="readonly" placeholder="Masukkan TMT SK" value="<?=$reqTMTSK?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        		</div>
	        		
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Pejabat Penetap</label>
	        			<div class="col-lg-10 col-sm-12">
	        				<select class="form-control select2" <?=$disabled?> id="ktpejabat" name="reqPejabatPenetap">
	        					<option value="<?=$reqPejabatPenetapId?>"><?=$reqPejabatPenetap?></option>
	        				</select>
	        				<span class="form-text text-muted"><label id="pejabatdetil"></label></span>
	        				<input type="hidden" name="reqPejabatPenetapId" id="reqPejabatPenetapId" value="<?=$reqPejabatPenetapId?>" >
	        				<input type="hidden" name="reqPejabatPenetap" id="reqPejabatPenetap" value="<?=$reqPejabatPenetap?>" >
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Masa Kerja</label>
	        			<div class="form-group col-xs-3 col-xs-offset-1">
	        				<input type="text" style="width:100px;margin-left: 11px" <?=$disabled?> class="form-control" placeholder="Tahun" maxlength="4" name="reqTh" id="reqTh" value="<?=$reqTh?>" />
	        			</div>
	        			<div class="form-group col-xs-3">
	        				Th
	        			</div>
	        			<div class="form-group col-xs-3">
	        				<input type="text" style="width:100px;" class="form-control" <?=$disabled?> placeholder="Bulan" maxlength="2" name="reqBl" id="reqBl" value="<?=$reqBl?>" />
	        			</div>
	        			<div class="form-group col-xs-3">
	        				Bl
	        			</div>
	        			<label class="col-form-label text-right col-lg-3 col-sm-12">Gaji Pokok</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" style="margin-left: 13px" <?=$disabled?>   OnFocus="FormatAngka('reqGajiPokok')" OnKeyUp="FormatUang('reqGajiPokok')" OnBlur="FormatUang('reqGajiPokok')" placeholder="Masukkan Gaji Pokok" name="reqGajiPokok" id="reqGajiPokok" value="<?=numberToIna($reqGajiPokok)?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Jenis</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<select class="form-control select2" <?=$disabled?> id="ktjenis" name="reqJenis" style="width:auto;">
	        					<option value="1" <? if($reqJenis == 1) echo "selected";?>>Kenaikan Pangkat</option>
	        					<option value="2" <? if($reqJenis == 2) echo "selected";?>>Gaji Berkala</option>
	        					<option value="3" <? if($reqJenis == 3) echo "selected";?>>CPNS</option>
	        					<option value="4" <? if($reqJenis == 4) echo "selected";?>>PNS</option>
	        					<option value="5" <? if($reqJenis == 5) echo "selected";?>>PMK</option>
	        				</select>
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
	        				<?
	        				if(!empty($adminuserid) && empty($reqValidasi))
	        				{
	        				?>
		        				<button type="submit" id="ktloginformsubmitbutton"  class="btn btn-primary font-weight-bold mr-2">Simpan</button>
		        				<button onclick="kembali()" type="button" class="btn btn-warning font-weight-bold mr-2">Kembali</button>
	        				<?
	        				}
	        				else
	        				{
	        				?>
		        				<button onclick="kembali()" type="button" class="btn btn-warning font-weight-bold mr-2">Kembali</button>
	        				<?
	        				}
	        				?>

	        				<?if(!file_exists($reqLinkFile))
	        				{
	        				?>  <? 
	        					$file_name_direktori = "../".$folder."/uploads/GAJI_RIWAYAT/FOTO_BLOB-".$reqPegawaiId.".pdf";
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
	        					<a class="btn btn-success font-weight-bold mr-2" href="app/loadUrl/main/viewer?reqForm=gaji&reqFileId=<?=$reqFileId?>" target="_blank">Download</a>
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
		window.location.href='app/index/pegawai_gaji'
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
	$('#ktgol').select2({
		placeholder: "Pilih gol"
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
	$('#kttanggalkeputusan,#kttmtgol,#kttanggalnota,#kttmtjabatan,#kttmtsk').datepicker({
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
		var formSubmitUrl = "json-validasi/personal_json/jsonriwayatgajiadd";
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
			        		document.location.href = "app/index/pegawai_gaji";
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
            urlAjax= "json-validasi/personal_json/jsonriwayatgajideletefile?reqFileId="+fileid+"&reqPegawaiId="+reqPegawaiId+"&reqDetilId="+reqDetilId+"&reqRowId="+reqRowId;
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

