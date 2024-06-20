<?
$this->load->model("base-validasi/SkPns");
$this->load->model("base-validasi/Satker");
$this->load->model("base-validasi/Pangkat");

$reqPegawaiId= $this->pegawaiId;
$reqSatkerId= $this->input->get('reqIdOrganisasi');
$adminuserid= $this->adminuserid;
$folder = $this->config->item('simpeg');


$set   = new SkPns();
$set->selectByParamsAdmin(array("A.PEGAWAI_ID" => $reqPegawaiId));
$set->firstRow();
// echo $set->query;exit;
$reqRowId= $set->getField('SK_PNS_ID');
$reqTempValidasiId= $set->getField('TEMP_VALIDASI_ID');
$reqPejabatPenetapan= $set->getField('PEJABAT_PENETAP_ID');
$reqNamaPejabatPenetap= $set->getField('NAMA_PENETAP');
$reqNIPPejabatPenetap= $set->getField('NIP_PENETAP');
$reqNoSuratKeputusan= $set->getField('NO_SK');
$reqTanggalSuratKeputusan= dateToPageCheck($set->getField('TANGGAL_SK'));
$reqTerhitungMulaiTanggal= dateToPageCheck($set->getField('TMT_PNS'));
$reqNoDiklatPrajabatan= $set->getField('NO_PRAJAB');
$reqTanggalDiklatPrajabatan= dateToPageCheck($set->getField('TANGGAL_PRAJAB'));
$reqNoSuratUjiKesehatan= $set->getField('NO_UJI_KESEHATAN');
$reqTanggalSuratUjiKesehatan= dateToPageCheck($set->getField('NO_UJI_KESEHATAN'));
$reqGolRuang= $set->getField('PANGKAT_ID');
$reqPengambilanSumpah= $set->getField('SUMPAH');
$reqSkPnsId= $set->getField('SK_PNS_ID');
$reqTanggalSumpah= dateToPageCheck($set->getField('TANGGAL_SUMPAH'));
$reqNoSKSumpah= $set->getField('NO_SK_TANGGAL');
$reqPejabatSumpahId= $set->getField('PEJABAT_PENETAP_SUMPAH_ID');
$reqNamaPejabatPenetapSumpah= $set->getField('PEJABAT_PENETAP_SUMPAH');
$reqNoDiklatPrajabatan= $set->getField('NO_PRAJAB');
$reqTh= $set->getField('MASA_KERJA_TAHUN');
$reqBl= $set->getField('MASA_KERJA_BULAN');
$reqNoBeritaAcara= $set->getField('NOMOR_BERITA_ACARA');
$reqTanggalBeritaAcara= dateToPageCheck($set->getField('TANGGAL_BERITA_ACARA'));
$reqKeteranganLPJ= $set->getField('KETERANGAN_LPJ');
$reqGajiPokok= $set->getField('GAJI_POKOK');
$data= $set->getField('FOTO_BLOB');
$reqPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
$reqPejabatPenetap= $set->getField('PEJABAT_PENETAP');

$reqFileId= $set->getField('SK_PNS_FILE_ID');
$reqLinkFile= $set->getField('LINK_FILE');
$reqLinkFileBerita= $set->getField('LINK_FILE_BERITA');
$reqLinkFileSurat= $set->getField('LINK_FILE_SURAT');
$reqLinkFileSpmt= $set->getField('LINK_FILE_SPMT');

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

$disabled = "";
// $disabled = "";
if (empty($adminuserid))
{
	// $disabled = "readonly";
	$disabled = "disabled";	
}

// print_r($arragama);exit;

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
						<a class="text-muted">SK Pns</a>
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
                    <h3 class="card-label">SK Pns</h3>
                </div>
            </div>
            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
	        	<div class="card-body">
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
	        				<input type="text" class="form-control" <?=$disabled?> style=";" placeholder="Masukkan No. Surat Keputusan"name="reqNoSuratKeputusan" id="reqNoSuratKeputusan" value="<?=$reqNoSuratKeputusan?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tanggal Surat Keputusan</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control" <?=$disabled?> id="kttanggalkeputusan" name="reqTanggalSuratKeputusan" placeholder="Masukkan Tanggal Surat Keputusan" value="<?=$reqTanggalSuratKeputusan?>" />
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
		        				<input type="text" class="form-control" <?=$disabled?> id="kttanggalmulai"  name="reqTerhitungMulaiTanggal" placeholder="Masukkan Tanggal Mulai" value="<?=$reqTerhitungMulaiTanggal?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">No. Diklat Prajabatan</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" <?=$disabled?> style=";" placeholder="Masukkan No. Diklat Prajabatan"name="reqNoDiklatPrajabatan" id="reqNoDiklatPrajabatan" value="<?=$reqNoDiklatPrajabatan?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tanggal Diklat Prajabatan</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control" id="kttanggaldiklat" name="reqTanggalDiklatPrajabatan" <?=$disabled?> placeholder="Masukkan Tanggal Diklat Prajabatan" value="<?=$reqTanggalDiklatPrajabatan?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">No. Surat Uji Kesehatan</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" <?=$disabled?> style=";" placeholder="Masukkan No. Surat Uji Kesehatan"name="reqNoSuratUjiKesehatan" id="reqNoSuratUjiKesehatan" value="<?=$reqNoSuratUjiKesehatan?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tanggal Surat Uji Kesehatan</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control" <?=$disabled?> id="kttanggalsurat" name="reqTanggalSuratUjiKesehatan" placeholder="Masukkan Tanggal Surat Uji" value="<?=$reqTanggalSuratUjiKesehatan?>" />
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
	        				<select class="form-control select2" <?=$disabled?> style=";" id="ktgolid" name="reqGolRuang">
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
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Pengambilan Sumpah</label>
	        			<div class="form-group col-xs-3 col-xs-offset-1">
	        				<input type="checkbox" name="reqPengambilanSumpah"  <?=$disabled?> value="1" <? if($reqPengambilanSumpah == 1) echo 'checked'?> />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">No. Berita Acara</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" <?=$disabled?> style=";" placeholder="Masukkan No. Berita Acara"name="reqNoBeritaAcara" id="reqNoBeritaAcara" value="<?=$reqNoBeritaAcara?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tanggal Berita Acara</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control" id="kttanggalberita" <?=$disabled?> name="reqTanggalBeritaAcara"  placeholder="Masukkan Tanggal Berita Acara" value="<?=$reqTanggalBeritaAcara?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Masa Kerja</label>
	        			<div class="form-group col-xs-3 col-xs-offset-1">
	        				<input type="text" style="width:100px;margin-left: 11px;;" <?=$disabled?>  class="form-control" placeholder="Tahun" maxlength="4" name="reqTh" id="reqTh" value="<?=$reqTh?>" />
	        			</div>
	        			<div class="form-group col-xs-3">
	        				Th
	        			</div>
	        			<div class="form-group col-xs-3">
	        				<input type="text" style="width:100px;" <?=$disabled?> class="form-control" placeholder="Bulan" maxlength="2" name="reqBl" id="reqBl" value="<?=$reqBl?>" />
	        			</div>
	        			<div class="form-group col-xs-3">
	        				Bl
	        			</div>
	        			<label class="col-form-label text-right col-lg-3 col-sm-12">Gaji Pokok</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" <?=$disabled?> style="margin-left: 13px;"   OnFocus="FormatAngka('reqGajiPokok')" OnKeyUp="FormatUang('reqGajiPokok')" OnBlur="FormatUang('reqGajiPokok')" placeholder="Masukkan Gaji Pokok" name="reqGajiPokok" id="reqGajiPokok" value="<?=numberToIna($reqGajiPokok)?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Lampiran SK PNS</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<?if(empty($reqLinkFile))
	        				{
	        					?>
	        					<input type="button" id="upload" class="btn btn-success font-weight-bold mr-2" value="Upload" onclick="document.getElementById('reqLinkFile').click();" />
	        					<input type="file" style="display:none;" id="reqLinkFile" name="reqLinkFile"  accept=".pdf"/>
	        					<? 
	        					$file_name_direktori = "../".$folder."/uploads/sk_pns/FOTO_BLOB-".$reqPegawaiId.".pdf";
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
	        					<a class="btn btn-success font-weight-bold mr-2" href="app/loadUrl/admin/viewer?reqForm=skpns&reqFileId=<?=$reqFileId?>" target="_blank">Download</a>
	        					<a class="btn btn-danger font-weight-bold mr-2" onclick="btndeletefile('<?=$reqFileId?>','<?=$reqPegawaiId?>','<?=$reqRowId?>','')">Hapus File</a>
	        					<?
	        				}
	        				?>
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Lampiran berita acara</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<?if(empty($reqLinkFileBerita))
	        				{
	        					?>
	        					<input type="button" id="uploadberita" class="btn btn-success font-weight-bold mr-2" value="Upload" onclick="document.getElementById('reqLinkFileBerita').click();" />
	        					<input type="file" style="display:none;" id="reqLinkFileBerita" name="reqLinkFileBerita"  accept=".pdf"/>
	        					<? 
	        					$file_name_direktori = "../".$folder."/uploads/sk_pns/FOTO_BLOB_BERITA-".$reqPegawaiId.".pdf";
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
	        					<a class="btn btn-success font-weight-bold mr-2" href="app/loadUrl/admin/viewer?reqForm=sk_pns_berita&reqFileId=<?=$reqFileId?>" target="_blank">Download</a>
	        					<a class="btn btn-danger font-weight-bold mr-2" onclick="btndeletefile('<?=$reqFileId?>','<?=$reqPegawaiId?>','<?=$reqRowId?>','berita')">Hapus File</a>
	        					<?
	        				}
	        				?>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Lampiran surat uji kesehatan</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<?if(empty($reqLinkFileSurat))
	        				{
	        					?>
	        					<input type="button" id="uploadsurat" class="btn btn-success font-weight-bold mr-2" value="Upload" onclick="document.getElementById('reqLinkFileSurat').click();" />
	        					<input type="file" style="display:none;" id="reqLinkFileSurat" name="reqLinkFileSurat"  accept=".pdf"/>
	        					<? 
	        					$file_name_direktori = "../".$folder."/uploads/sk_pns/FOTO_BLOB_KES-".$reqPegawaiId.".pdf";
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
	        					<a class="btn btn-success font-weight-bold mr-2" href="app/loadUrl/admin/viewer?reqForm=sk_pns_surat&reqFileId=<?=$reqFileId?>" target="_blank">Download</a>
	        					<a class="btn btn-danger font-weight-bold mr-2" onclick="btndeletefile('<?=$reqFileId?>','<?=$reqPegawaiId?>','<?=$reqRowId?>','surat')">Hapus File</a>
	        					<?
	        				}
	        				?>
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Lampiran SPMT</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<?if(empty($reqLinkFileSpmt))
	        				{
	        					?>
	        					<input type="button" id="uploadspmt" class="btn btn-success font-weight-bold mr-2" value="Upload" onclick="document.getElementById('reqLinkFileSpmt').click();" />
	        					<input type="file" style="display:none;" id="reqLinkFileSpmt" name="reqLinkFileSpmt"  accept=".pdf"/>
	        					<?
	        				}
	        				else
	        				{
	        					?>
	        					<a class="btn btn-success font-weight-bold mr-2" href="app/loadUrl/admin/viewer?reqForm=sk_pns_spmt&reqFileId=<?=$reqFileId?>" target="_blank">Download</a>
	        					<a class="btn btn-danger font-weight-bold mr-2" onclick="btndeletefile('<?=$reqFileId?>','<?=$reqPegawaiId?>','<?=$reqRowId?>','spmt')">Hapus File</a>
	        					<?
	        				}
	        				?>
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

	$(document).ready(function() {
		$('#reqLinkFile').change(function() {
			var reqLinkFile = $('#reqLinkFile').val().split('\\').pop();
			var lastIndex = reqLinkFile.lastIndexOf("\\");   
			$('#upload').val(reqLinkFile);
		});
		$('#reqLinkFileBerita').change(function() {
			var reqLinkFileBerita = $('#reqLinkFileBerita').val().split('\\').pop();
			var lastIndex = reqLinkFileBerita.lastIndexOf("\\");   
			$('#uploadberita').val(reqLinkFileBerita);
		});
		$('#reqLinkFileSurat').change(function() {
			var reqLinkFileSurat = $('#reqLinkFileSurat').val().split('\\').pop();
			var lastIndex = reqLinkFileSurat.lastIndexOf("\\");   
			$('#uploadsurat').val(reqLinkFileSurat);
		});
		$('#reqLinkFileSpmt').change(function() {
			var reqLinkFileSpmt = $('#reqLinkFileSpmt').val().split('\\').pop();
			var lastIndex = reqLinkFileSpmt.lastIndexOf("\\");   
			$('#uploadspmt').val(reqLinkFileSpmt);
		});
	});

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
	$('#kttanggalkeputusan,#kttanggalmulai,#kttanggaldiklat,#kttanggalsurat,#kttanggalberita').datepicker({
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
		var formSubmitUrl = "json-validasi/personal_json/jsonpegawaiskpnsadd";
		var formSubmitButton = KTUtil.getById('ktloginformsubmitbutton');
		if (!form) {
			return;
		}
		FormValidation
		.formValidation(
			form,
			{
				fields: {
					reqGolRuang: {
						validators: {
							notEmpty: {
								message: 'Please select an option'
							}
						}
					},
					reqTanggalLahir: {
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
			        		document.location.href = "app/index/pegawai_sk_pns";
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

	function btndeletefile(fileid,reqPegawaiId,reqRowId,reqMode) {
		if(fileid !== "")
        {
            urlAjax= "json-data/personal_json/jsonpegawaipnsdeletefile?reqFileId="+fileid+"&reqPegawaiId="+reqPegawaiId+"&reqRowId="+reqRowId+"&reqMode="+reqMode;
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

