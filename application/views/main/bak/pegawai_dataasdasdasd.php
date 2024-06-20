<?
include_once("functions/personal.func.php");

$this->load->model("base-validasi/Pegawai");
$this->load->model("base-validasi/Satker");
$this->load->model("base-validasi/Agama");
$this->load->model("base-validasi/TipePegawai");
$this->load->model("base-validasi/Kedudukan");
$this->load->model("base-validasi/JenisPegawai");
$this->load->model("base-validasi/StatusPegawai");

$reqPegawaiId= $this->pegawaiId;
$reqSatkerId= $this->input->get('reqSatkerId');

$set= new Agama();
$arragama= [];
$set->selectByParams();
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	$arrdata["id"]= $set->getField("AGAMA_ID");
	$arrdata["text"]= $set->getField("NAMA");
	array_push($arragama, $arrdata);
}
unset($set);

$set   = new Pegawai();
$record= $set->getCountByParams(array("PEGAWAI_ID"=>$reqPegawaiId));
$reqMode="";
if($record > 0)
{
	$reqMode="update";
	$set->selectByParams(array("P.PEGAWAI_ID"=>$reqPegawaiId),-1,-1);	
}
else
{
	$reqMode="insert";
}
$set->firstRow();
// echo $set->query;exit;
$reqTempValidasiId= $set->getField('TEMP_VALIDASI_ID');
$reqPerubahanData= $set->getField('PERUBAHAN_DATA');

$reqNipLama= $set->getField('NIP_LAMA');
$reqNipBaru= $set->getField('NIP_BARU');
$reqNama= $set->getField('NAMA');
$reqTipePegawai= $set->getField('TIPE_PEGAWAI_ID');
$reqGelarDepan= $set->getField('GELAR_DEPAN');
$reqGelarBelakang= $set->getField('GELAR_BELAKANG');
$reqStatusPegawai= $set->getField('STATUS_PEGAWAI');
$reqTempatLahir= $set->getField('TEMPAT_LAHIR');$valTempatLahir= checkwarna($reqPerubahanData, 'TEMPAT_LAHIR');
$reqTanggalLahir= dateToPageCheck($set->getField('TANGGAL_LAHIR'));$valTanggalLahir= checkwarna($reqPerubahanData, 'TANGGAL_LAHIR', "date");
$reqTglPensiun= dateToPageCheck($set->getField('TANGGAL_PENSIUN'));
$reqJenisKelamin= $set->getField('JENIS_KELAMIN');
$reqJenisPegawai= $set->getField('JENIS_PEGAWAI_ID');
$reqStatusPernikahan= $set->getField('STATUS_KAWIN');
$reqKartuPegawai= $set->getField('KARTU_PEGAWAI');
$reqSukuBangsa= $set->getField('SUKU_BANGSA');
$reqGolDarah= $set->getField('GOLONGAN_DARAH');
$reqAkses= $set->getField('ASKES');
$reqTaspen= $set->getField('TASPEN');
$reqAlamat= $set->getField('ALAMAT');$valAlamat= checkwarna($reqPerubahanData, 'ALAMAT');
$reqNPWP= $set->getField('NPWP');
$reqNIK= $set->getField('NIK');
$reqRT= $set->getField('RT');
$reqRW= $set->getField('RW');
$reqEmail= $set->getField('EMAIL');
$reqPropinsiId= $set->getField('PROPINSI_ID');
$reqPropinsi= $set->getField('PROPINSI_NAMA');
$reqKabupatenId= $set->getField('KABUPATEN_ID');
$reqKabupaten= $set->getField('KABUPATEN_NAMA');
$reqKecamatanId= $set->getField('KECAMATAN_ID');
$reqKecamatan= $set->getField('KECAMATAN_NAMA');
$reqDesaId= $set->getField('KELURAHAN_ID');
$reqDesa= $set->getField('KELURAHAN_NAMA');
$reqBank= $set->getField('BANK_ID');
$reqNoRekening= $set->getField('NO_REKENING');
$reqPangkatTerkahir= $set->getField('GOL_RUANG');
$reqTMTPangkat= $set->getField('TMT_PANGKAT');
$reqJabatanTerkahir= $set->getField('JABATAN');
$reqTMTJabatan= $set->getField('TMT_JABATAN');
$reqPendidikanTerkahir= $set->getField('PENDIDIKAN');
$reqJurusanTerkahir= $set->getField('JURUSAN');
$reqTahunLulus= $set->getField('TAHUN');
$reqGambar= $set->getField('FOTO_BLOB');
$reqAgamaId= $set->getField('AGAMA_ID');$valAgamaId= checkwarna($reqPerubahanData, 'AGAMA_ID', $arragama, array("id", "text"));
$reqTelepon= $set->getField('TELEPON');
$reqKodePos= $set->getField('KODEPOS');
$reqKedudukanId= $set->getField('KEDUDUKAN_ID');
$reqKonversiNIP= $set->getField('SK_KONVERSI_NIP');
$reqSatuanKerjaNama= $set->getField('NMSATKER');
$reqSatuanKerja= $set->getField('SATKER_ID');
$reqFotoBlob= $set->getField('FOTO_BLOB');
$reqFotoBlobOther=$set->getField('foto_blob_other');
unset($set);
// print_r($reqPerubahanData);exit;

if($reqStatusPegawai == '6' || $reqStatusPegawai == '5')
	$reqStatusLabelTanggal= 'Wafat';
else
	$reqStatusLabelTanggal= 'Pensiun';
	
if($reqSatkerId == ""){}
else
{
	$satker = new Satker();
	$satker->selectByParams(array("A.SATKER_ID"=> $reqSatkerId),-1,-1,'');
	$satker->firstRow();
	$reqSatuanKerjaNama = $satker->getField('SATKER_FULL');
	$reqSatuanKerja	 = $reqSatkerId;
}

$tipe_pegawai= new TipePegawai();
$tipe_pegawai->selectByParams();

$set= new StatusPegawai();
$arrstatuspegawai= [];
$set->selectByParams();
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	$arrdata["id"]= $set->getField("STATUS_PEGAWAI_ID");
	$arrdata["text"]= $set->getField("NAMA");
	array_push($arrstatuspegawai, $arrdata);
}
unset($set);

$disabled = "disabled";

// print_r($arragama);exit;
// http://192.168.88.100/lamongan/simpeg/main/pegawai_edit.php?reqId=7484
// http://192.168.88.100/lamongan/simpeg/main/validasi_pegawai_add.php?reqId=7484
?>
<div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
	<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<div class="d-flex align-items-center flex-wrap mr-1">
			<div class="d-flex align-items-baseline flex-wrap mr-5">
				<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">Data Pegawai</a>
					</li>
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">Identitas Pegawai</a>
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
                    <h3 class="card-label">Identitas Pegawai</h3>
                </div>
            </div>
            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
	        	<div class="card-body">
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">
	        				NIP Lama
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" <?=$disabled?> placeholder="Masukkan NIP lama"name="reqNipLama" id="reqNipLama" value="<?=$reqNipLama?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">NIP Baru</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" <?=$disabled?> placeholder="Masukkan NIP baru" name="reqNipBaru" id="reqNipBaru" value="<?=$reqNipBaru?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Nama</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" <?=$disabled?> name="reqNama" id="reqNama" placeholder="Masukkan nama anda" value="<?=$reqNama?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">
		        			Agama
		        			<?
	        				if(!empty($valAgamaId['data']))
	        				{
	        				?>
	        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valAgamaId['data']?>"></i>
	        				<?
	        				}
	        				?>
		        		</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<select class="form-control select2 <?=$valAgamaId['warna']?>"  <?=$disabled?> id="ktagamaid" name="reqAgamaId">
	        					<option value=""></option>
	        					<?
	        					// $reqAgamaId= "1";
	        					foreach($arragama as $item) 
	        					{
	        						$selectvalid= $item["id"];
	        						$selectvaltext= $item["text"];
	        					?>
	        					<option value="<?=$selectvalid?>" <? if($reqAgamaId == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
	        					<?
	        					}
	        					?>
	        				</select>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Gelar Depan</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" <?=$disabled?> placeholder="Masukkan gelar depan" name="reqGelarDepan"  id="reqGelarDepan" value="<?=$reqGelarDepan?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Gelar Belakang</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" <?=$disabled?> placeholder="Masukkan gelar belakang" name="reqGelarBelakang" id="reqGelarBelakang" value="<?=$reqGelarBelakang?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">
	        				Tempat Lahir
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
	        				<input type="text" <?=$disabled?> class="form-control <?=$valTempatLahir['warna']?>" placeholder="Masukkan tempat lahir" name="reqTempatLahir"  id="reqTempatLahir" value="<?=$reqTempatLahir?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">
		        			Tanggal Lahir
		        			<?
	        				if(!empty($valTanggalLahir['data']))
	        				{
	        				?>
	        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTanggalLahir['data']?>"></i>
	        				<?
	        				}
	        				?>
		        		</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" <?=$disabled?> class="form-control <?=$valTanggalLahir['warna']?>" id="kttanggallahir" name="reqTanggalLahir" readonly="readonly" placeholder="Select date" value="<?=$reqTanggalLahir?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Jenis Kelamin</label>
	        			<div class="col-lg-2 col-sm-12">
	        				<select class="form-control select2" id="ktjeniskelamin"  name="reqJenisKelamin" >
	        					<option value="L" <? if($reqJenisKelamin == 'L') echo 'selected';?>>L</option>
	        					<option value="P" <? if($reqJenisKelamin == 'P') echo 'selected';?>>P</option>
	        				</select>
	        			</div>
	        			<label class="col-form-label text-right col-lg-4 col-sm-12">Status Pernikahan</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<select class="form-control select2" id="ktstatuspernikahan"  name="reqStatusPernikahan" >
	        					<option value="1" <? if($reqStatusPernikahan == "1") echo 'selected'?>>Belum Kawin</option>
	        					<option value="2" <? if($reqStatusPernikahan == "2") echo 'selected'?>>Kawin</option>
	        					<option value="3" <? if($reqStatusPernikahan == "3") echo 'selected'?>>Janda</option>
	        					<option value="4" <? if($reqStatusPernikahan == "4") echo 'selected'?>>Duda</option>
	        				</select>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Suku Bangsa</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" placeholder="Masukkan suku bangsa" name="reqSukuBangsa"  id="reqSukuBangsa" value="<?=$reqSukuBangsa?>"/>
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Gol Darah</label>
	        			<div class="col-lg-2 col-sm-12">
	        				<select class="form-control select2" id="ktgoldarah" name="reqGolDarah" >
	        					<option></option>
	        					<option value="A" <? if(trim($reqGolDarah) == "A") echo 'selected'?>>A</option>
	        					<option value="B" <? if(trim($reqGolDarah) == "B") echo 'selected'?>>B</option>
	        					<option value="AB" <? if(trim($reqGolDarah) == "AB") echo 'selected'?>>AB</option>
	        					<option value="O" <? if(trim($reqGolDarah) == "O") echo 'selected'?>>O</option>
	        				</select>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">
		        			Alamat
		        			<?
	        				if(!empty($valAlamat['data']))
	        				{
	        				?>
	        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valAlamat['data']?>"></i>
	        				<?
	        				}
	        				?>
		        		</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<textarea class="form-control <?=$valAlamat['warna']?>" name="reqAlamat" cols="46" placeholder="Masukkan suku alamat" > <?=$reqAlamat?></textarea>
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Telepon/HP</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control" placeholder="Masukkan kontak"  name="reqTelepon" id="reqTelepon" value="<?=$reqTelepon?>" />
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Email *</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control"placeholder=" Masukkan email" name="reqEmail" id="reqEmail" value="<?=$reqEmail?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Foto</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<!-- <? 
	        				$file_name_direktori = "../uploads/pegawai/FOTO_BLOB-".$reqPegawaiId.".jpg";
	        				if (file_exists($file_name_direktori)) 
	        				{
	        					?>
	        					<img src="<?=$file_name_direktori?>" width=120 height=150 style="border:solid 1px;">
	        					<a href="javascript:confirmAction('?reqPegawaiId=<?=$reqPegawaiId?>&reqMode=hapus_pegawai_dosir&reqRowMode=hapus_foto', '1')"><img src="images/button_cancel.png" width="15" height="15"/></a>
	        					<? 
	        				}
	        				?>
	        				<br/>
	        				<input type="file" style="font-size:10px" name="reqGambar" id="reqGambar" 
	        				class="form-control" data-options="validType:['fileTypeSatuFile[\'jpg\']']" /><br/>
	        				<span style="color:#C00; font-size:9px">max upload 2 mb format jpg</span> -->
	        			</div>
	        		</div>
	        		<? 
	        		// if($reqPegawaiId == ""){}
	        		// else
	        		// {
	        		?>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Pangkat Terakhir</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<?=$reqPangkatTerkahir?>
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">TMT Pangkat</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<?=$reqTMTPangkat?>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Jabatan Terakhir</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<?=$reqJabatanTerkahir?>
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">TMT Jabatan</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<?=$reqTMTJabatan?>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Pendidikan Terakhir</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<?=$reqPendidikanTerkahir.' '.$reqJurusanTerkahir?>
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tahun Lulus</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<?=$reqTahunLulus?>
	        			</div>
	        		</div>
	        		<? 
	        		// }
	        		?>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Satuan Kerja</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<select class="form-control select2" <?=$disabled?> id="ktsatuankerja" name="reqSatuanKerjaNama">
	        					<option value="<?=$reqSatuanKerja?>"><?=$reqSatuanKerjaNama?></option>
	        				</select>
	        				<span class="form-text text-muted"><label id="satkerdetil"></label></span>
	        				<input type="hidden" name="reqSatuanKerja" id="reqSatuanKerja" value="<?=$reqSatuanKerja?>" >
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tipe Pegawai</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<select class="form-control select2" id="kttipepegawai" name="reqTipePegawai">
	        					<? 
	        					while ($tipe_pegawai->nextRow())
	        					{
	        						?>
	        						<option value="<?=$tipe_pegawai->getField('TIPE_PEGAWAI_ID')?>"
	        							<? 
	        							if ($tipe_pegawai->getField('TIPE_PEGAWAI_ID') == $reqTipePegawai) echo 'selected'?>>
	        							<?
	        							if($tipe_pegawai->getField('TIPE_PEGAWAI_ID_parent') == 0)  
	        								echo $tipe_pegawai->getField('TIPE_PEGAWAI_ID').'.'.$tipe_pegawai->getField('NAMA');
	        							elseif(strlen($tipe_pegawai->getField("TIPE_PEGAWAI_ID")) == 4)
	        								echo substr($tipe_pegawai->getField('TIPE_PEGAWAI_ID'),0,1).'.'.substr($tipe_pegawai->getField('TIPE_PEGAWAI_ID'),1,1).".".substr($tipe_pegawai->getField('TIPE_PEGAWAI_ID'),2).'.'.$tipe_pegawai->getField('NAMA');
	        							else														
	        								echo substr($tipe_pegawai->getField('TIPE_PEGAWAI_ID'),0,1).'.'.substr($tipe_pegawai->getField('TIPE_PEGAWAI_ID'),1).'.'.$tipe_pegawai->getField('NAMA');
	        							?>
	        						</option>
	        						<? 
	        					} 
	        					?>
	        				</select>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tanggal Pensiun</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control" <?=$disabled?> id="kttanggalpensiun" name="reqTglPensiun" readonly="readonly" placeholder="Select date" value="<?=$reqTglPensiun?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Status Pegawai</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<select class="form-control select2" id="ktstatuspegawai" name="reqStatusPegawai">
	        					<?
	        					foreach($arrstatuspegawai as $item) 
	        					{
	        						$selectvalid= $item["id"];
	        						$selectvaltext= $item["text"];
	        					?>
	        					<option value="<?=$selectvalid?>" <? if($reqStatusPegawai == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
	        					<?
	        					}
	        					?>
	        				</select>
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Kartu Pegawai</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<!-- <input type="text" class="form-control" name="reqKartuPegawai" id="reqKartuPegawai" style="width:150px;" value="<?=$reqKartuPegawai?>" />
	        				<? 
	        				$file_name_direktori = "../uploads/pegawai/DOSIR_KARPEG-".$reqPegawaiId.".pdf";
	        				if (file_exists($file_name_direktori)) 
	        				{
	        					?>
	        					<a href="#" onclick="setCetakGeneral('download_preview.php?reqIsi=<?=$file_name_direktori?>');" ><img src="images/down.png" title="download" /></a>
	        					<a href="javascript:confirmAction('?reqPegawaiId=<?=$reqPegawaiId?>&reqMode=hapus_pegawai_dosir&reqRowMode=karpeg', '1')"><img src="images/button_cancel.png" width="15" height="15"/></a>
	        					<? 
	        				}
	        				?>
	        				<br/>
	        				<input type="file" style="font-size:10px" name="reqDosirKarpeg" id="reqDosirKarpeg" 
	        				class="form-control" data-options="validType:['fileTypeSatuFile[\'pdf\']']" /> -->
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Askes</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<!-- <input type="text" class="form-control" name="reqAkses" id="reqAkses" style="width:150px;" value="<?=$reqAkses?>" />
	        				<? 
	        				$file_name_direktori = "../uploads/pegawai/DOSIR_ASKES-".$reqPegawaiId.".pdf";
	        				if (file_exists($file_name_direktori)) 
	        				{
	        					?>
	        					<a href="#" onclick="setCetakGeneral('download_preview.php?reqIsi=<?=$file_name_direktori?>');" ><img src="images/down.png" title="download" /></a>
	        					<a href="javascript:confirmAction('?reqPegawaiId=<?=$reqPegawaiId?>&reqMode=hapus_pegawai_dosir&reqRowMode=askes', '1')"><img src="images/button_cancel.png" width="15" height="15"/></a>
	        					<? 
	        				}
	        				?>
	        				<br/>
	        				<input type="file" style="font-size:10px" name="reqDosirAskes" id="reqDosirAskes" 
	        				class="form-control" data-options="validType:['fileTypeSatuFile[\'pdf\']']" /> -->
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Taspen</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<!-- <input type="text" class="form-control" name="reqTaspen" id="reqTaspen" style="width:150px;" value="<?=$reqTaspen?>" />
	        				<? 
	        				$file_name_direktori = "../uploads/pegawai/DOSIR_TASPEN-".$reqPegawaiId.".pdf";
	        				if (file_exists($file_name_direktori)) 
	        				{
	        					?>
	        					<a href="#" onclick="setCetakGeneral('download_preview.php?reqIsi=<?=$file_name_direktori?>');" ><img src="images/down.png" title="download" /></a>
	        					<a href="javascript:confirmAction('?reqPegawaiId=<?=$reqPegawaiId?>&reqMode=hapus_pegawai_dosir&reqRowMode=taspen', '1')"><img src="images/button_cancel.png" width="15" height="15"/></a>
	        					<? 
	        				}
	        				?>
	        				<br/>
	        				<input type="file" style="font-size:10px" name="reqDosirTaspen" id="reqDosirTaspen" 
	        				class="form-control" data-options="validType:['fileTypeSatuFile[\'pdf\']']" /> -->
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">NPWP</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<!-- <input type="text" class="form-control" name="reqNPWP" id="reqNPWP" maxlength="15" style="width:150px;" value="<?=$reqNPWP?>" />
	        				<? 
	        				$file_name_direktori = "../uploads/pegawai/DOSIR_NPWP-".$reqPegawaiId.".pdf";
	        				if (file_exists($file_name_direktori)) 
	        				{
	        					?>
	        					<a href="#" onclick="setCetakGeneral('download_preview.php?reqIsi=<?=$file_name_direktori?>');" ><img src="images/down.png" title="download" /></a>
	        					<a href="javascript:confirmAction('?reqPegawaiId=<?=$reqPegawaiId?>&reqMode=hapus_pegawai_dosir&reqRowMode=npwp', '1')"><img src="images/button_cancel.png" width="15" height="15"/></a>
	        					<? 
	        				}
	        				?>
	        				<br/>
	        				<input type="file" style="font-size:10px" name="reqDosirNpwp" id="reqDosirNpwp" 
	        				class="form-control" data-options="validType:['fileTypeSatuFile[\'pdf\']']" /> -->
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">NIK</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<!-- <input type="text" class="form-control" name="reqNIK" id="reqNIK" style="width:150px;" value="<?=$reqNIK?>" />
	        				<? 
	        				$file_name_direktori = "../uploads/pegawai/DOSIR_NIK-".$reqPegawaiId.".pdf";
	        				if (file_exists($file_name_direktori)) 
	        				{
	        					?>
	        					<a href="#" onclick="setCetakGeneral('download_preview.php?reqIsi=<?=$file_name_direktori?>');" ><img src="images/down.png" title="download" /></a>
	        					<a href="javascript:confirmAction('?reqPegawaiId=<?=$reqPegawaiId?>&reqMode=hapus_pegawai_dosir&reqRowMode=nik', '1')"><img src="images/button_cancel.png" width="15" height="15"/></a>
	        					<? 
	        				}
	        				?>
	        				<br/>
	        				<input type="file" style="font-size:10px" name="reqDosirNIK" id="reqDosirNIK" 
	        				class="form-control" data-options="validType:['fileTypeSatuFile[\'pdf\']']" /> -->
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">SK Konversi NIP</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<!-- <?=$disabled?> <input type="text" class="form-control" name="reqKonversiNIP" id="reqKonversiNIP" style="width:150px;" value="<?=$reqKonversiNIP?>" />
	        				<? 
	        				$file_name_direktori = "../uploads/pegawai/DOSIR_SK_KONVERSI-".$reqPegawaiId.".pdf";
	        				if (file_exists($file_name_direktori)) 
	        				{
	        					?>
	        					<a href="#" onclick="setCetakGeneral('download_preview.php?reqIsi=<?=$file_name_direktori?>');" ><img src="images/down.png" title="download" /></a>
	        					<a href="javascript:confirmAction('?reqPegawaiId=<?=$reqPegawaiId?>&reqMode=hapus_pegawai_dosir&reqRowMode=nik', '1')"><img src="images/button_cancel.png" width="15" height="15"/></a>
	        					<? 
	        				}
	        				?>
	        				<br/>
	        				<input type="file" style="font-size:10px" name="reqDosirKonversiNIP" id="reqDosirKonversiNIP" 
	        				class="form-control" data-options="validType:['fileTypeSatuFile[\'pdf\']']" /> -->
	        			</div>
	        		</div>
	        		
	        	</div>
	        	<div class="card-footer">
	        		<div class="row">
	        			<div class="col-lg-9">
	        				<input type="hidden" name="reqMode" value="<?=$reqMode?>">
	        				<input type="hidden" name="reqTempValidasiId" value="<?=$reqTempValidasiId?>">
	        				<button type="submit" id="ktloginformsubmitbutton"  class="btn btn-primary font-weight-bold mr-2">Simpan</button>
	        			</div>
	        		</div>
	        	</div>
	        </form>
        </div>
    </div>
</div>

<script type="text/javascript">
	$(function () {
		$("[rel=tooltip]").tooltip({ placement: 'right'});
		// $('[data-toggle="tooltip"]').tooltip()
	})

	function tampilSatuanKerja(satker) {
        if (satker.loading) return satker.text;
        var markup = "<div class='select2-result-repository clearfix'>" +
	            "<div class='select2-result-repository__meta'>" +
	            	"<div class='select2-result-repository__title'>" + satker.description + "</div>" +
	            "</div>" +
            "</div>";
        return markup;
    }
    function pilihSatuanKerja(satker) {
    	$("#satkerdetil").text(satker.description);
    	$("#reqSatuanKerja").val(satker.id);
        // console.log(satker);
        return satker.text;
    }
	$('#ktagamaid').select2({
		placeholder: "Pilih agama"
	});
	$('#ktjeniskelamin').select2({
		placeholder: "Pilih jenis kelamin"
	});
	$('#ktstatuspernikahan').select2({
		placeholder: "Pilih Status"
	});
	$('#ktgoldarah').select2({
		placeholder: "Piih gol darah"
	});
	$('#kttipepegawai').select2({
		placeholder: "Piih tipe pegawai"
	});
	$('#ktstatuspegawai').select2({
		placeholder: "Piih status pegawai"
	});
	$("#ktsatuankerja").select2({
        placeholder: "Pilih satuan kerja",
        allowClear: true,
        ajax: {
            url: "json-validasi/combo_json/autocompletesatuankerja",
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
        templateResult: tampilSatuanKerja, // omitted for brevity, see the source of this page
        templateSelection: pilihSatuanKerja // omitted for brevity, see the source of this page
    });
    arrows= {leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'};
	$('#kttanggallahir').datepicker({
		todayHighlight: true
		, autoclose: true
		, orientation: "bottom left"
		, clearBtn: true
		, format: 'dd-mm-yyyy'
		, templates: arrows
	});
	$('#kttanggalpensiun').datepicker({
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
		var formSubmitUrl = "json-validasi/personal_json/jsonpegawaidataadd";
		var formSubmitButton = KTUtil.getById('ktloginformsubmitbutton');
		if (!form) {
			return;
		}
		FormValidation
		.formValidation(
			form,
			{
				fields: {
					reqEmail: {
						validators: {
							notEmpty: {
								message: 'Email is required'
							},
							emailAddress: {
								message: 'The value is not a valid email address'
							}
						}
					},
					reqAgamaId: {
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
					reqSatuanKerjaNama: {
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
			        		document.location.href = "app/index/pegawai_data";
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
</script>

