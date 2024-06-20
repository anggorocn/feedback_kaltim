<?
$this->load->model("base-validasi/PendidikanRiwayat");
$this->load->model("base-validasi/HapusData");
$this->load->model("base-personal/Pendidikan");
// $this->load->model("base-validasi/PendidikanRiwayat");


$reqPegawaiId= $this->input->get('reqPegawaiId');
$reqRowId= $this->input->get('reqRowId');
$reqDetilId= $this->input->get('reqDetilId');
$reqMode= $this->input->get('reqMode');
$reqValidasiHapusId= $this->input->get('reqValidasiHapusId');
$adminuserid= $this->adminuserid;

$set = new PendidikanRiwayat();
if($reqMode == 'edit' || $reqMode == 'cancel' || $reqMode == 'view')
{
	$set->selectByParamsAdmin(array('A.PENDIDIKAN_RIWAYAT_ID'=>$reqRowId));
	
	// echo $set->query;exit;
	$set->firstRow();
	$reqRowId= $set->getField('PENDIDIKAN_RIWAYAT_ID');
	$reqDataId= $set->getField('TEMP_VALIDASI_ID');
	$reqNamaSekolah= $set->getField('NAMA');
	$reqPendidikan= $set->getField('PENDIDIKAN_ID');
	$reqTglSTTB= dateToPageCheck($set->getField('TANGGAL_STTB'));
	$reqJurusan= $set->getField('JURUSAN');
	$reqJurusanId= $set->getField('JURUSAN_PENDIDIKAN_ID');
	// echo $reqJurusan;exit;
	$reqAlamatSekolah= $set->getField('TEMPAT');
	$reqKepalaSekolah = $set->getField('KEPALA');
	$reqNoSTTB= $set->getField('NO_STTB');
	$reqPerubahanData= $set->getField('PERUBAHAN_DATA');
	$reqValidasi= $set->getField('VALIDASI');
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
$sOrder=" ORDER BY COALESCE(PANGKAT_MINIMAL,0)";
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

$disabled = "";
if (empty($adminuserid))
{
	$disabled = "disabled";	
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
						<a class="text-muted">Pendidikan Umum</a>
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
                    <h3 class="card-label">Pendidikan Umum</h3>
                </div>
            </div>
            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
	        	<div class="card-body">
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Pendidikan</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<select class="form-control select2" id="ktpendidikan" <?=$disabled?> name="reqPendidikan" style="width:30%; ">
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
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">No. STTB</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" <?=$disabled?> placeholder="Masukkan No. STTB"name="reqNoSTTB" id="reqNoSTTB" value="<?=$reqNoSTTB?>" />
	        			</div>
	        		</div>
	        		
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Jurusan</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<select class="form-control select2" <?=$disabled?> id="ktjurusan" name="reqJurusan">
	        					<option value="<?=$reqJurusanId?>"><?=$reqJurusan?></option>
	        				</select>
	        				<span class="form-text text-muted"><label id="jurusandetil"></label></span>
	        				<input type="hidden" name="reqJurusanId" id="reqJurusanId" value="<?=$reqJurusanId?>" >
	        				<input type="hidden" name="reqJurusan" id="reqJurusan" value="<?=$reqJurusan?>" >
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tgl. STTB</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control" <?=$disabled?> id="kttanggalsttb" name="reqTglSTTB" readonly="readonly" placeholder="Masukkan Tanggal STTB" value="<?=$reqTglSTTB?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Nama Sekolah</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" <?=$disabled?> placeholder="Masukkan Nama Sekolah" name="reqNamaSekolah" id="reqNamaSekolah" value="<?=$reqNamaSekolah?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Alamat</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" <?=$disabled?> placeholder="Masukkan Alamat" name="reqAlamatSekolah" id="reqAlamatSekolah" value="<?=$reqAlamatSekolah?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Kepala Sekolah</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" <?=$disabled?> placeholder="Masukkan nama Kepala Sekolah" name="reqKepalaSekolah" id="reqKepalaSekolah" value="<?=$reqKepalaSekolah?>" />
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
		window.location.href='app/index/pendidikan_umum'
	}

	function tampilJurusan(val) {
        if (val.loading) return val.text;
        var markup = "<div class='select2-result-repository clearfix'>" +
	            "<div class='select2-result-repository__meta'>" +
	            	"<div class='select2-result-repository__title'>" + val.description + "</div>" +
	            "</div>" +
            "</div>";
        return markup;
    }
    function pilihJurusan(val) {
    	$("#jurusandetil").text(val.description);
    	$("#reqJurusanId").val(val.id);
    	$("#reqJurusan").val(val.text);	
        // console.log(val);
        return val.text;
    }
	$('#ktgol').select2({
		placeholder: "Pilih gol"
	});
	$('#ktpendidikan').select2({
		placeholder: "Pilih Pendidikan"
	});
	$("#ktjurusan").select2({
        placeholder: "Pilih jurusan",
        allowClear: true,
        ajax: {
            url: "json-validasi/combo_json/autocompletejurusan",
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
        templateResult: tampilJurusan, // omitted for brevity, see the source of this page
        templateSelection: pilihJurusan // omitted for brevity, see the source of this page
    });
    arrows= {leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'};
	$('#kttanggalkeputusan,#kttmtgol,#kttanggalnota,#kttanggalsttb').datepicker({
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
		var formSubmitUrl = "json-validasi/personal_json/jsonpendidikanumumadd";
		var formSubmitButton = KTUtil.getById('ktloginformsubmitbutton');
		if (!form) {
			return;
		}
		FormValidation
		.formValidation(
			form,
			{
				fields: {
					reqNamaSekolah: {
						validators: {
							notEmpty: {
								message: 'Nama sekolah harus diisi'
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
			        		document.location.href = "app/index/pendidikan_umum";
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
</script>

