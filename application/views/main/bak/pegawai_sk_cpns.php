<?
$this->load->model("base-validasi/SkCpns");
$this->load->model("base-validasi/Satker");
$this->load->model("base-validasi/Pangkat");

$reqPegawaiId= $this->pegawaiId;
$reqSatkerId= $this->input->get('reqIdOrganisasi');
$adminuserid= $this->adminuserid;
$folder = $this->config->item('simpeg');


$set   = new SkCpns();
$set->selectByParamsAdmin(array("A.PEGAWAI_ID" => $reqPegawaiId));
$set->firstRow();
// echo $set->query;exit;
$reqRowId= $set->getField('SK_CPNS_ID');
$reqTempValidasiId= $set->getField('TEMP_VALIDASI_ID');
$reqNoNotaBAKN= $set->getField('NO_NOTA');
$reqTanggalNotaBAKN= dateToPageCheck($set->getField('TANGGAL_NOTA'));
$reqPejabatPenetapan= $set->getField('PEJABAT_PENETAP_ID');
$reqNamaPejabatPenetap= $set->getField('NAMA_PENETAP');
$reqNIPPejabatPenetap= $set->getField('NIP_PENETAP');
$reqNoSuratKeputusan= $set->getField('NO_SK');
$reqTanggalSuratKeputusan= dateToPageCheck($set->getField('TANGGAL_SK'));
$reqTerhitungMulaiTanggal= dateToPageCheck($set->getField('TMT_CPNS'));
$reqGolRuang= $set->getField('PANGKAT_ID');
$reqTanggalTugas= dateToPageCheck($set->getField('TANGGAL_TUGAS'));
$reqSkcpnsId= $set->getField('SK_CPNS_ID');
$reqTh= $set->getField('MASA_KERJA_TAHUN');
$reqBl= $set->getField('MASA_KERJA_BULAN');
$reqGajiPokok= $set->getField('GAJI_POKOK');
$reqPejabatPenetap= $set->getField("PEJABAT_PENETAP");
// echo $reqPejabatPenetap;exit;
$reqPejabatPenetapId= $set->getField("PEJABAT_PENETAP_ID");

$reqLinkFile= $set->getField('LINK_FILE');
$reqFileId= $set->getField('SK_CPNS_FILE_ID');
$reqLinkFileServer= $set->getField('LINK_SERVER');


unset($set);

if($reqRowId == "")
	$reqMode= "insert";
else
	$reqMode= "update";

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

// print_r($arragama);exit;
$disabled = "";
if (empty($adminuserid))
{
	$disabled = "disabled";	
}

?>
<script type="text/javascript" src="functions/globalfunction.js"></script>
<style type="text/css">
	   select[readonly].select2-hidden-accessible + .select2-container {
        pointer-events: none;
        touch-action: none;
    }

    select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
        background: #F3F6F9;
        box-shadow: none;
    }

    select[readonly].select2-hidden-accessible + .select2-container .select2-selection__arrow, select[readonly].select2-hidden-accessible + .select2-container .select2-selection__clear {
        display: none;
    }
</style>
<div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
	<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<div class="d-flex align-items-center flex-wrap mr-1">
			<div class="d-flex align-items-baseline flex-wrap mr-5">
				<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">Data Utama</a>
					</li>
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">SK Cpns</a>
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
                    <h3 class="card-label">SK Cpns</h3>
                </div>
            </div>
            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
	        	<div class="card-body">
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">No. Nota BKN</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" <?=$disabled?> style="" placeholder="Nota BAKN harus diisi"name="reqNoNotaBAKN" id="reqNoNotaBAKN" value="<?=$reqNoNotaBAKN?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tanggal Nota BKN</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control" id="kttanggalnota" <?=$disabled?> placeholder="Masukkan Tanggal Nota" value="<?=$reqTanggalNotaBAKN?>" />
		        				<input type="hidden" name="reqTanggalSuratKeputusan" value="<?=$reqTanggalSuratKeputusan?>" >
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
	        				<select class="form-control select2" <?=$disabled?> id="ktsatuankerja" name="reqPejabatPenetap">
	        					<option value="<?=$reqPejabatPenetapId?>"><?=$reqPejabatPenetap?></option>
	        				</select>
	        				<span class="form-text text-muted"><label id="pejabatdetil"></label></span>
	        				<input type="hidden" name="reqPejabatPenetapId" id="reqPejabatPenetapId" value="<?=$reqPejabatPenetapId?>" >
	        				<input type="hidden" name="reqPejabatPenetap" id="reqPejabatPenetap" value="<?=$reqPejabatPenetap?>" >
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">No. Surat Keputusan</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" <?=$disabled?> style="" placeholder="Masukkan No. Surat Keputusan"name="reqNoSuratKeputusan" id="reqNoSuratKeputusan" value="<?=$reqNoSuratKeputusan?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tanggal Surat Keputusan</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control" <?=$disabled?> id="kttanggalkeputusan" placeholder="Masukkan Tanggal Surat Keputusan" value="<?=$reqTanggalSuratKeputusan?>" />
		        				<input type="hidden" name="reqTanggalSuratKeputusan" value="<?=$reqTanggalSuratKeputusan?>" >
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Terhitung Mulai Tanggal</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control" <?=$disabled?> id="kttanggalmulai" placeholder="Masukkan Tanggal Mulai" value="<?=$reqTerhitungMulaiTanggal?>" />
		        				<input type="hidden" name="reqTerhitungMulaiTanggal" value="<?=$reqTerhitungMulaiTanggal?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Gol/Ruang</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<select class="form-control select2" <?=$disabled?> id="ktgolid" name="reqGolRuang">
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
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tanggal Tugas</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control" <?=$disabled?> id="kttanggaltugas" name="reqTanggalTugas"  placeholder="Masukkan Tanggal Tugas" value="<?=$reqTanggalTugas?>" />
		        				<input type="hidden" name="reqTanggalTugas" value="<?=$reqTanggalTugas?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Masa Kerja</label>
	        			<div class="form-group col-xs-3 col-xs-offset-1">
	        				<input type="text" style="width:100px;margin-left: 11px;" <?=$disabled?>  class="form-control" placeholder="Tahun" maxlength="4" name="reqTh" id="reqTh" value="<?=$reqTh?>" />
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
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Gaji Pokok</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" <?=$disabled?> style=""  OnFocus="FormatAngka('reqGajiPokok')" OnKeyUp="FormatUang('reqGajiPokok')" OnBlur="FormatUang('reqGajiPokok')" placeholder="Masukkan Gaji Pokok" name="reqGajiPokok" id="reqGajiPokok" value="<?=numberToIna($reqGajiPokok)?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Lampiran SK CPNS</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<?if(empty($reqLinkFileServer))
	        				{
	        					?>
	        					<input type="button" id="upload" class="btn btn-success font-weight-bold mr-2" value="Upload" onclick="document.getElementById('reqLinkFile').click();" />
	        					<input type="file" style="display:none;" id="reqLinkFile" name="reqLinkFile"  accept=".pdf"/>
	        					<? 
	        					$file_name_direktori = "../".$folder."/uploads/sk_cpns/FOTO_BLOB-".$reqPegawaiId.".pdf";
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
	        					<a class="btn btn-success font-weight-bold mr-2" href="app/loadUrl/admin/viewer?reqForm=skcpns&reqFileId=<?=$reqFileId?>" target="_blank">Download</a>
	        					<a class="btn btn-danger font-weight-bold mr-2" onclick="btndeletefile(<?=$reqFileId?>,<?=$reqPegawaiId?>,<?=$reqRowId?>)">Hapus File</a>
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
	        				<input type="hidden" name="reqTempValidasiId" value="<?=$reqTempValidasiId?>">
	        				<?
	        				if(!empty($adminuserid) && empty($reqValidasi))
	        				{
	        				?>
	        					<input type="hidden" name="reqUpload" value="2">
		        				<button type="submit" id="ktloginformsubmitbutton"  class="btn btn-primary font-weight-bold mr-2">Simpan</button>
	        				<?
	        				}
	        				else
	        				{
	        				?>
		        				<input type="hidden" name="reqUpload" value="1">
		        				<button type="submit" id="ktloginformsubmitbutton"  class="btn btn-primary font-weight-bold mr-2">Proses Upload</button>
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
        placeholder: "Pilih satuan kerja",
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
    $('#kttanggalnota,#kttanggalkeputusan,#kttanggalmulai,#kttanggaltugas').datepicker({
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
		var formSubmitUrl = "json-validasi/personal_json/jsonpegawaiskcpnsadd";
		var formSubmitButton = KTUtil.getById('ktloginformsubmitbutton');
		if (!form) {
			return;
		}
		FormValidation
		.formValidation(
			form,
			{
				fields: {
					reqGolRuaang: {
						validators: {
							notEmpty: {
								message: 'Please select an option'
							}
						}
					},
					reqTangagalLahir: {
						validators: {
							notEmpty: {
								message: 'Please select an option'
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
			        		confirmButtonText: "Ok, got it!",
			        		customClass: {
			        			confirmButton: "btn font-weight-bold btn-light-primary"
			        		}
			        	}).then(function() {
			        		document.location.href = "app/index/pegawai_sk_cpns";
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
	function btndeletefile(fileid,reqPegawaiId,reqRowId) {
		if(fileid !== "")
        {
            urlAjax= "json-data/personal_json/jsonpegawaicpnsdeletefile?reqFileId="+fileid+"&reqPegawaiId="+reqPegawaiId+"&reqRowId="+reqRowId;
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

