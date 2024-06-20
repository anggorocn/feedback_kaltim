<?
include_once("functions/personal.func.php");

$this->load->model("base-validasi/SuamiIstri");
$this->load->model("base-validasi/HapusData");
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

$set = new SuamiIstri();
if($reqMode == 'edit' || $reqMode == 'cancel' || $reqMode == 'view')
{
	if($reqDetilId == "")
	{
		$set->selectByParams(array('A.SUAMI_ISTRI_ID'=>$reqRowId));
	}
	else
	{
		$set->selectByParams(array('A.TEMP_VALIDASI_ID'=>$reqDetilId));
	}
	// echo $set->query;exit;
	$set->firstRow();
	$reqPerubahanData= $set->getField('PERUBAHAN_DATA');
	$reqRowId= $set->getField('SUAMI_ISTRI_ID');
	$reqDataId= $set->getField('TEMP_VALIDASI_ID');
	$reqIdSuamiIstri= $set->getField('SUAMI_ISTRI_ID');
	$reqNamaSuamiIstri= $set->getField('NAMA');$valNamaSuamiIstri= checkwarna($reqPerubahanData, 'NAMA');
	$reqTempatLahir= $set->getField('TEMPAT_LAHIR');$valTempatLahir= checkwarna($reqPerubahanData, 'TEMPAT_LAHIR');
	$reqTglLahir= dateToPageCheck($set->getField('TANGGAL_LAHIR'));$valTglLahir= checkwarna($reqPerubahanData, 'TANGGAL_LAHIR', "date");
	$reqTglKawin= dateToPageCheck($set->getField('TANGGAL_KAWIN'));$valTglKawin= checkwarna($reqPerubahanData, 'TANGGAL_KAWIN', "date");
	$reqPns= $set->getField('STATUS_PNS');$valPns= checkwarna($reqPerubahanData, 'STATUS_PNS');
	$reqNoAktaNikah= $set->getField('NO_AKTA_NIKAH');$valNoAktaNikah= checkwarna($reqPerubahanData, 'SK_CERAI');
	$reqKartu= $set->getField('KARTU');$valKartu= checkwarna($reqPerubahanData, 'KARTU');
	$reqStatus= $set->getField('STATUS');$valStatus= checkwarna($reqPerubahanData, 'STATUS',"combo");
	$reqNoHp= $set->getField('NO_HP');$valNoHp= checkwarna($reqPerubahanData, 'BUKU_NIKAH');
	$reqPekerjaan= $set->getField('PEKERJAAN');
	$reqPNS= $set->getField('STATUS_PNS');
	$reqNIP= $set->getField('NIP_PNS');$valNIP= checkwarna($reqPerubahanData, 'NIP_PNS');
	$reqPendidikan= $set->getField('PENDIDIKAN_ID');$valPendidikan= checkwarna($reqPerubahanData, 'PENDIDIKAN_ID', $arrpendidikan, array("id", "text"));
	$reqPekerjaan= $set->getField('PEKERJAAN');$valPekerjaan= checkwarna($reqPerubahanData, 'PEKERJAAN');
	$reqTunjangan= $set->getField('STATUS_TUNJANGAN');
	$reqSudahDibayar= $set->getField('STATUS_TUNJANGAN');
	$reqBulanDibayar= dateToPageCheck($set->getField('BULAN_BAYAR'));
	$reqFoto= $set->getField('FOTO');
	$reqFotoTmp= $set->getField('FOTO');
	$data = $set->getField('FOTO_BLOB');
	$data_karpeg= $set->getField('DOSIR_KARPEG');
	$reqValidasi= $set->getField('VALIDASI');
	$reqFileId= $set->getField('SUAMI_ISTRI_FILE_ID');
	$reqLinkFileKK= $set->getField('LINK_FILE_KK');
	$reqLinkFileAkta= $set->getField('LINK_FILE_AKTA');
	$reqLinkFileKtp= $set->getField('LINK_FILE_KTP');
	$checked="";
	if($reqPns == 1)
	{
		$checked="checked";
	}
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


// print_r($reqStatus);exit;

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
						<a class="text-muted">Suami Istri </a>
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
                    <h3 class="card-label">Suami Istri</h3>
                </div>
            </div>
            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
	        	<div class="card-body">
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Nama Suami/Istri
	        			<?
	        			if(!empty($valNamaSuamiIstri['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNamaSuamiIstri['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valNamaSuamiIstri['warna']?>" placeholder="Masukkan Suami/Istri" name="reqNamaSuamiIstri" id="reqNamaSuamiIstri" value="<?=$reqNamaSuamiIstri?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Karis/karsu
	        			<?
	        			if(!empty($valKartu['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valKartu['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valKartu['warna']?>" placeholder="Masukkan Karis/karsu" name="reqKartu" id="reqKartu" value="<?=$reqKartu?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tempat Lahir
	        			<?
	        			if(!empty($valTempatLahir['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTempatLahir['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valTempatLahir['warna']?>" placeholder="Masukkan Tempat Lahir" name="reqTempatLahir" id="reqTempatLahir" value="<?=$reqTempatLahir?>" />
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
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">No akta nikah
	        			<?
	        			if(!empty($valNoAktaNikah['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNoAktaNikah['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valNoAktaNikah['warna']?>" placeholder="Masukkan No akta nikah" name="reqNoAktaNikah" id="reqNoAktaNikah" value="<?=$reqNoAktaNikah?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tgl. nikah
	        			<?
	        			if(!empty($valTglKawin['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTglKawin['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control <?=$valTglKawin['warna']?>" id="kttglnikah" name="reqTglKawin" readonly="readonly" placeholder="Masukkan Tgl Kawin" value="<?=$reqTglKawin?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">No Hp
	        			<?
	        			if(!empty($valNoHp['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNoHp['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valNoHp['warna']?> " placeholder="Masukkan No Hp" name="reqNoHp" id="reqNoHp" value="<?=$reqNoHp?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Status
	        			<?
	        			if(!empty($valStatus['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valStatus['data']?>"></i>
	        			<?
	        			}
	        			?></label>
	        			<div class="col-lg-4 col-sm-12">
	        				<select class="form-control select2 <?=$valStatus['warna']?>" id="ktstatus" name="reqStatus" >
	        					<option value="1" <? if($reqStatus == 1) echo 'selected';?>>nikah</option>
	        					<option value="2" <? if($reqStatus == 2) echo 'selected';?>>cerai</option>
	        					<option value="3" <? if($reqStatus == 3) echo 'selected';?>>meninggal</option>
	        				</select>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">PNS
	        			<?
	        			if(!empty($valPns['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valPns['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<!-- <input type="hidden" id="reqPns" name="reqPns" value="<?=$reqPns?>"/> -->
	        				<input type="checkbox" id="reqPns" name="reqPns" <?=$checked?> value="1"  />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">NIP(PNS)
	        			<?
	        			if(!empty($valNIP['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNIP['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12" id="reqInfoNip">
	        				<input type="text" class="form-control <?=$valNIP['warna']?>" placeholder="Masukkan Nip" name="reqNIP" id="reqNIP" value="<?=$reqNIP?>" />
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
	        				<select class="form-control select2  <?=$valPendidikan['warna']?>" id="ktpendidikan" name="reqPendidikan" style="width:30%; ">
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
	        				<input type="text" class="form-control  <?=$valPekerjaan['warna']?>" placeholder="Masukkan Pekerjaan" name="reqPekerjaan" id="reqPekerjaan" value="<?=$reqPekerjaan?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Akta Nikah</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<?if(!file_exists($reqLinkFileAkta))
	        				{
	        					?>
	        					<input type="button" id="uploadakta" class="btn btn-success font-weight-bold mr-2" value="Upload" onclick="document.getElementById('reqLinkFileAkta').click();" />
	        					<input type="file" style="display:none;" id="reqLinkFileAkta" name="reqLinkFileAkta"  accept=".pdf"/>
	        					<? 
	        					$file_name_akta = "../".$folder."/uploads/suami_istri/FOTO_BLOB-".$reqRowId.".pdf";
	        					if (file_exists($file_name_akta)) 
	        					{
	        						?>
	        						<a class="btn btn-primary font-weight-bold mr-2" href="<?=$file_name_akta?>" target="_blank">Download</a>
	        						<? 
	        					}
	        					?>
	        					<?
	        				}
	        				else
	        				{
	        					?>
	        					<a class="btn btn-primary font-weight-bold mr-2" href="app/loadUrl/main/viewer?reqForm=akta&reqFileId=<?=$reqFileId?>" target="_blank">Download</a>
	        					<a class="btn btn-danger font-weight-bold mr-2" onclick="btndeletefile('<?=$reqFileId?>','<?=$reqPegawaiId?>','<?=$reqDetilId?>','akta')">Hapus File</a>
	        					<?
	        				}
	        				?>
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Kartu Keluarga</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<?if(!file_exists($reqLinkFileKK))
	        				{
	        					?>
	        					<input type="button" id="uploadkk" class="btn btn-success font-weight-bold mr-2" value="Upload" onclick="document.getElementById('reqLinkFileKK').click();" />
	        					<input type="file" style="display:none;" id="reqLinkFileKK" name="reqLinkFileKK"  accept=".pdf"/>
	        					<? 
	        					$file_name_kk = "../".$folder."/uploads/suami_istri/FOTO_BLOB-".$reqRowId.".pdf";
	        					$file_name_kk = "../".$folder."/uploads/suami_istri/KK-".$reqRowId.".pdf";
	        					if (file_exists($file_name_ktp)) 
	        					{
	        						?>
	        						<a class="btn btn-primary font-weight-bold mr-2" href="<?=$file_name_ktp?>" target="_blank">Download</a>
	        						<? 
	        					}
	        					?>
	        					<?
	        				}
	        				else
	        				{
	        					?>
	        					<a class="btn btn-primary font-weight-bold mr-2" href="app/loadUrl/main/viewer?reqForm=kk&reqFileId=<?=$reqFileId?>" target="_blank">Download</a>
	        					<a class="btn btn-danger font-weight-bold mr-2" onclick="btndeletefile('<?=$reqFileId?>','<?=$reqPegawaiId?>','<?=$reqDetilId?>','kk')">Hapus File</a>
	        					<?
	        				}
	        				?>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        		<label class="col-form-label text-right col-lg-2 col-sm-12">Ktp</label>
	        		<div class="col-lg-4 col-sm-12">
	        			<?if(!file_exists($reqLinkFileKtp))
	        			{
	        				?>
	        				<input type="button" id="uploadktp" class="btn btn-success font-weight-bold mr-2" value="Upload" onclick="document.getElementById('reqLinkFileKtp').click();" />
	        				<input type="file" style="display:none;" id="reqLinkFileKtp" name="reqLinkFileKtp"  accept=".pdf"/>
	        				<? 
	        				$file_name_ktp = "../".$folder."/uploads/suami_istri/FOTO_BLOB-".$reqRowId.".pdf";
	        				if (file_exists($file_name_ktp)) 
	        				{
	        					?>
	        					<a class="btn btn-primary font-weight-bold mr-2" href="<?=$file_name_akta?>" target="_blank">Download</a>
	        					<? 
	        				}
	        				?>
	        				<?
	        			}
	        			else
	        			{
	        				?>
	        				<a class="btn btn-primary font-weight-bold mr-2" href="app/loadUrl/main/viewer?reqForm=ktp&reqFileId=<?=$reqFileId?>" target="_blank">Download</a>
	        				<a class="btn btn-danger font-weight-bold mr-2" onclick="btndeletefile('<?=$reqFileId?>','<?=$reqPegawaiId?>','<?=$reqDetilId?>','ktp')">Hapus File</a>

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

	$(document).ready(function() {
		$('#reqLinkFileKK').change(function() {
			var reqLinkFileKK = $('#reqLinkFileKK').val().split('\\').pop();
			var lastIndex = reqLinkFileKK.lastIndexOf("\\");   
			$('#uploadkk').val(reqLinkFileKK);
		});
		$('#reqLinkFileAkta').change(function() {
			var reqLinkFileAkta = $('#reqLinkFileAkta').val().split('\\').pop();
			var lastIndex = reqLinkFileAkta.lastIndexOf("\\");   
			$('#uploadakta').val(reqLinkFileAkta);
		});
		$('#reqLinkFileKtp').change(function() {
			var reqLinkFileKtp = $('#reqLinkFileKtp').val().split('\\').pop();
			var lastIndex = reqLinkFileKtp.lastIndexOf("\\");   
			$('#uploadktp').val(reqLinkFileKtp);
		});
		
	});
	$(document).on("input", "#reqNoHp", function() {
		this.value = this.value.replace(/\D/g,'');
	});
	function kembali() {
		window.location.href='app/index/pegawai_suami_istri'
	}
	$('#ktstatus').select2({
		placeholder: "Pilih Status"
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
		var formSubmitUrl = "json-validasi/personal_json/jsonpegawaisuamiistriadd";
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
			        		document.location.href = "app/index/pegawai_suami_istri";
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

	function btndeletefile(fileid,reqPegawaiId,reqDetilId,reqMode) {
		if(fileid !== "")
        {
            urlAjax= "json-validasi/personal_json/jsonpegawaisuamiistrideletefile?reqFileId="+fileid+"&reqPegawaiId="+reqPegawaiId+"&reqDetilId="+reqDetilId+"&reqMode="+reqMode;
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

