<?
include_once("functions/personal.func.php");

$this->load->model("base-validasi/PenilaianKerja");
$this->load->model("base-validasi/HapusData");
$this->load->model("base-validasi/PejabatPenetap");


$reqPegawaiId= $this->input->get('reqPegawaiId');
$reqRowId= $this->input->get('reqRowId');
$reqDetilId= $this->input->get('reqDetilId');
$reqMode= $this->input->get('reqMode');
$reqValidasiHapusId= $this->input->get('reqValidasiHapusId');

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

$set = new PenilaianKerja();
if($reqMode == 'edit' || $reqMode == 'cancel' || $reqMode == 'view')
{
	if($reqDetilId == "")
	{
		$set->selectByParams(array('PENILAIAN_KERJA_ID'=>$reqRowId));
	}
	else
	{
		$set->selectByParams(array('TEMP_VALIDASI_ID'=>$reqDetilId));
	}
	// echo $set->query;exit;
	$set->firstRow();
	$reqRowId 			= $set->getField('PENILAIAN_KERJA_ID');
	$reqPerubahanData= $set->getField('PERUBAHAN_DATA');
	$reqDataId= $set->getField('TEMP_VALIDASI_ID');
	$reqTahun= $set->getField('TAHUN');$valTahun= checkwarna($reqPerubahanData, 'TAHUN');
	$reqNilai1= $set->getField("NILAI1");$valNilai1= checkwarna($reqPerubahanData, 'NILAI1');
	$reqNilai2= $set->getField("NILAI2");$valNilai2= checkwarna($reqPerubahanData, 'NILAI2');
	$reqNilai3= $set->getField("NILAI3");$valNilai3= checkwarna($reqPerubahanData, 'NILAI3');
	$reqNilai4= $set->getField("NILAI4");$valNilai4= checkwarna($reqPerubahanData, 'NILAI4');
	$reqNilai5= $set->getField("NILAI5");$valNilai5= checkwarna($reqPerubahanData, 'NILAI5');
	$reqNilai6= $set->getField("NILAI6");$valNilai6= checkwarna($reqPerubahanData, 'NILAI6');
	$reqJumlah= $set->getField("JUMLAH");$valJumlah= checkwarna($reqPerubahanData, 'JUMLAH');
	$reqRataRata= $set->getField("RATA_RATA");$valRataRata= checkwarna($reqPerubahanData, 'RATA_RATA');
	$reqTanggalAwal= dateToPageCheck($set->getField("TANGGAL_AWAL"));$valTanggalAwal= checkwarna($reqPerubahanData, 'TANGGAL_AWAL',"date");
	$reqTanggalAkhir= dateToPageCheck($set->getField("TANGGAL_AKHIR"));$valTanggalAkhir= checkwarna($reqPerubahanData, 'TANGGAL_AKHIR',"date");
	$reqSasaranKerja= $set->getField("SASARAN_KERJA");$valSasaranKerja= checkwarna($reqPerubahanData, 'SASARAN_KERJA');
	$reqSasaranKerjaHasil= $set->getField("SASARAN_KERJA_HASIL");$valSasaranKerjaHasil= checkwarna($reqPerubahanData, 'SASARAN_KERJA_HASIL');
	$reqPerilakuHasil= $set->getField("PERILAKU_HASIL");$valPerilakuHasil= checkwarna($reqPerubahanData, 'PERILAKU_HASIL');
	$reqNilaiHasil= $set->getField("NILAI_HASIL");$valNilaiHasil= checkwarna($reqPerubahanData, 'NILAI_HASIL');
	$reqPejabatPenetap= $set->getField("PEJABAT_PENETAP");
	$reqPejabatPenetapId= $set->getField("PEJABAT_PENETAP_ID");$valPejabatPenetap= checkwarna($reqPerubahanData, 'PEJABAT_PENETAP_ID', $arrpejabat, array("id","text"));
	$reqRekomendasi= $set->getField("REKOMENDASI");$valRekomendasi= checkwarna($reqPerubahanData, 'REKOMENDASI');

	$reqValidasi= $set->getField('VALIDASI');
}
unset($set);
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
						<a class="text-muted">Lain Lain</a>
					</li>
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">Penilaian Prestasi</a>
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
                    <h3 class="card-label">Penilaian Prestasi</h3>
                </div>
            </div>
            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
	        	<div class="card-body">
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tahun
	        			<?
	        			if(!empty($valTahun['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTahun['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valTahun['warna']?>" placeholder="Masukkan Tahun"name="reqTahun" id="reqTahun" value="<?=$reqTahun?>" />
	        			</div>

	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">JANGKA WAKTU PENILAIAN
	        			<?
	        			if(!empty($valTanggalAwal['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTanggalAwal['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control <?=$valTanggalAwal['warna']?>" id="kttglawal" name="reqTanggalAwal" readonly="readonly" placeholder="Masukkan Tgl Awal" value="<?=$reqTanggalAwal?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">s/d
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
		        				<input type="text" class="form-control <?=$valTanggalAkhir['warna']?>" id="kttglakhir" name="reqTanggalAkhir" readonly="readonly" placeholder="Masukkan Tgl Akhir" value="<?=$reqTanggalAkhir?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">PEJABAT PENILAI
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
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">UNSUR YANG DINILAI
	        			<?
	        			if(!empty($valSasaranKerja['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valSasaranKerja['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">a. Sasaran Kerja Pegawai (SKP)</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valSasaranKerja['warna']?>" placeholder="Masukkan Sasaran"name="reqSasaranKerja" id="reqSasaranKerja" value="<?=$reqSasaranKerja?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">x &nbsp; 60%
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
        				<input type="text"  disabled class="form-control" name="reqSasaranKerjaHasil" id="reqSasaranKerjaHasil" value="<?=$reqSasaranKerjaHasil?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">b.Perilaku Kerja</label>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">1. Orientasi Pelayanan
	        			<?
	        			if(!empty($valNilai1['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNilai1['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valNilai1['warna']?>" name="reqNilai1" id="reqNilai1" value="<?=$reqNilai1?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">2. Integritas
	        			<?
	        			if(!empty($valNilai2['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNilai2['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valNilai2['warna']?>" name="reqNilai2" id="reqNilai2" value="<?=$reqNilai2?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">3. Komitmen
	        			<?
	        			if(!empty($valNilai3['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNilai3['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valNilai3['warna']?>" name="reqNilai3" id="reqNilai3" value="<?=$reqNilai3?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">4. Disiplin
	        			<?
	        			if(!empty($valNilai4['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNilai4['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valNilai4['warna']?>" name="reqNilai4" id="reqNilai4" value="<?=$reqNilai4?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">5. Kerjasama
	        			<?
	        			if(!empty($valNilai5['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNilai5['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valNilai5['warna']?>" name="reqNilai5" id="reqNilai5" value="<?=$reqNilai5?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">6. Kepemimpinan
	        			<?
	        			if(!empty($valNilai6['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNilai6['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valNilai6['warna']?>" name="reqNilai6" id="reqNilai6" value="<?=$reqNilai6?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">7. Jumlah
	        			</label>
	        			<label id="reqJumlahNama"><?=$tempJumlah?></label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="hidden" name="reqJumlah" id="reqJumlah" value="<?=$reqJumlah?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">8. Nilai rata - rata</label>
	        			<label id="reqRataRataNama"><?=$reqRataRataNama?></label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="hidden" name="reqRataRata" id="reqRataRata" value="<?=$reqRataRata?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12 ">9. Nilai Perilaku Kerja</label>
	        			<label id="reqRataRataNama"><?=$reqRataRataNama?></label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="hidden" name="reqRataRata" id="reqRataRata" value="<?=$reqRataRata?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">x &nbsp; 40%</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<label id="reqPerilakuHasilNama"><?=$reqPerilakuHasil?></label>
        				<input type="hidden"  disabled class="form-control" name="reqPerilakuHasil" id="reqPerilakuHasil" value="<?=$reqPerilakuHasil?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">NILAI PRESTASI KERJA</label>
	        			<div class="col-lg-4 col-sm-12">
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12"></label>
	        			<div class="col-lg-4 col-sm-12">
	        				<label id="reqHasilNilaiInfo"><?=$reqNilaiHasil?></label>
	        				<input type="hidden" name="reqNilaiHasil" id="reqHasilNilai" style="width:34px;" value="<?=$reqNilaiHasil?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">REKOMENDASI
	        			<?
	        			if(!empty($valRekomendasi['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valRekomendasi['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<textarea class="form-control  <?=$valRekomendasi['warna']?>"  name="reqRekomendasi" ><?=$reqRekomendasi?></textarea>
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
	$(document).on("input", "#reqTahun", function() {
		this.value = this.value.replace(/\D/g,'');
	});
	function kembali() {
		window.location.href='app/index/pegawai_penilaian_prestasi'
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
	$('#kttglawal,#kttmtgol,#kttglakhir').datepicker({
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
		var formSubmitUrl = "json-validasi/personal_json/jsonpegawaipenilaianprestasiadd";
		var formSubmitButton = KTUtil.getById('ktloginformsubmitbutton');
		if (!form) {
			return;
		}
		FormValidation
		.formValidation(
			form,
			{
				fields: {
					reqTahun: {
						validators: {
							notEmpty: {
								message: 'Tahun harus diisi'
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
			        		document.location.href = "app/index/pegawai_penilaian_prestasi";
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

	function setHitung()
	{
		var tempJumlah= tempRataRata= 0;
		var tempPembagi= 6;
		$('input[id^="reqNilai"]').each(function(i){
			var id= $(this).attr('id');
			id= id.replace("reqNilai", "")

			var reqNilai= tempNilai= "";
			tempNilai= reqNilai= $(this).val();
			reqNilai= reqNilai.replace(",", ".");
			reqNilai= checkNan(reqNilai);
			tempJumlah= parseFloat(tempJumlah) + parseFloat(reqNilai);
				//alert(tempJumlah);
				
				if(id == 6 && tempNilai == "")
				{
					tempPembagi= 5;
				}
				
			});
			//alert(tempJumlah);
			tempRataRata= parseFloat(tempJumlah) / tempPembagi;
			tempJumlah= tempJumlah.toFixed(2);
			tempRataRata= tempRataRata.toFixed(2);
			
			$("#reqRataRataInfo").text(setNilaiInfo(tempRataRata));
			
			tempJumlah= String(tempJumlah);
			tempJumlah= tempJumlah.replace(".", ",");
			tempRataRata= String(tempRataRata);
			tempRataRata= tempRataRata.replace(".", ",");
			$("#reqJumlahNama").text(tempJumlah);
			$("#reqJumlah").val(tempJumlah);
			$("#reqPerilakuKinerja, #reqRataRataNama").text(tempRataRata);
			$("#reqRataRata").val(tempRataRata);
			
			setNilaiHasil();
		}
		function setNilaiInfo(value)
		{
			reqNilai= checkNan(value);

			// console.log(reqNilai);
			
			if(parseFloat(reqNilai) <= 50)
				return "(Buruk)";
			else if(parseFloat(reqNilai) <= 60)
				return "(Sedang)";
			else if(parseFloat(reqNilai) <= 75)
				return "(Cukup)";
			else if(parseFloat(reqNilai) <= 90.99)
				return "(Baik)";
			else
				return "(Sangat Baik)";
		}
		
		function setNilai(id)
	   	{
			var reqNilai= "";
			reqNilai= $("#reqNilai"+id).val();
			reqNilai= reqNilai.replace(",", ".");
			reqNilai= checkNan(reqNilai);
			
			if(reqNilai == ""){}
			else
			{
				$("#reqNilaiNama"+id).text(setNilaiInfo(reqNilai));
			}
			setHitung();
	  	}

	  	function setNilaiHasil()
		{
			var reqSasaranKerja= reqSasaranKerjaHasil= tempSasaranKerjaHasil= reqRataRata= reqPerilakuHasil= tempPerilakuHasil= reqHasilNilai= "";
			
			reqSasaranKerja= $("#reqSasaranKerja").val();
			reqSasaranKerja= decodeFloat(reqSasaranKerja);
			reqSasaranKerja= checkNan(reqSasaranKerja);
			
			reqSasaranKerjaHasil= (reqSasaranKerja * 60) / 100;
			tempSasaranKerjaHasil= reqSasaranKerjaHasil= reqSasaranKerjaHasil.toFixed(2);
			reqSasaranKerjaHasilNama= encodeFloat(reqSasaranKerjaHasil);
			
			$("#reqSasaranKerjaHasilNama").text(reqSasaranKerjaHasilNama);
			$("#reqSasaranKerjaHasil").val(reqSasaranKerjaHasilNama);
			
			//------------
			
			reqRataRata= $("#reqRataRata").val();
			reqRataRata= decodeFloat(reqRataRata);
			reqRataRata= checkNan(reqRataRata);
			
			reqPerilakuHasil= (reqRataRata * 40) / 100;
			tempPerilakuHasil= reqPerilakuHasil= reqPerilakuHasil.toFixed(2);
			reqPerilakuHasilNama= encodeFloat(reqPerilakuHasil);
			
			$("#reqPerilakuHasilNama").text(reqPerilakuHasilNama);
			$("#reqPerilakuHasil").val(reqPerilakuHasilNama);
			
			//------------
			reqHasilNilai= parseFloat(tempSasaranKerjaHasil) + parseFloat(tempPerilakuHasil);
			reqHasilNilai= reqHasilNilai.toFixed(2);
			
			$("#reqHasilNilaiNama").text(setNilaiInfo(reqHasilNilai));
			reqHasilNilai= encodeFloat(reqHasilNilai);
			$("#reqHasilNilaiInfo").text(reqHasilNilai);
			$("#reqHasilNilai").val(reqHasilNilai);
			
		}

		function decodeFloat(value)
		{
			value= value.replace(",", ".");
			return value;
		}
		
		function encodeFloat(value)
		{
			value= String(value);
			value= value.replace(".", ",");
			return value;
		}
		// $('input[id^="reqNilai"]').each(function(i){
		// 	var id= $(this).attr('id');
		// 	id= id.replace("reqNilai", "")
		// 		//alert('--'+id); alert($(this).attr('id'));alert($(this).attr('id')+"--"+$(this).val());
		// 		setNilai(id);
		// 	});

		// $('input[id^="reqNilai"]').keyup(function() {
		// 		var id= $(this).attr('id');
		// 		id= id.replace("reqNilai", "")
		// 		//alert('--'+id);
		// 		setNilai(id);
		// 	});

		function checkNan(value)
		{
			if(typeof value == "undefined" || isNaN(value) || value == "")
			return 0;
			else
			return value;
		}
</script>

