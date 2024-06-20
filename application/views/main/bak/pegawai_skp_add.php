<?
include_once("functions/personal.func.php");
$this->load->model("base-data/Kontrak");
$this->load->model("base-data/PejabatPenetap");
$this->load->model("base-validasi/PenilaianKerjaPegawai");


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

$statement=" AND A.PEGAWAI_ID=".$reqPegawaiId;
$set= new PenilaianKerjaPegawai();
$arrgol= array();
$set->selectByParamsJenisJabatan(array(), -1,-1,$statement,$sOrder);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	$arrdata["id"]= $set->getField("JENIS_JABATAN_ID");
	$arrdata["text"]= $set->getField("JENIS_JABATAN");
	array_push($arrgol, $arrdata);
}
unset($set);

$set = new PenilaianKerjaPegawai();
if($reqMode == 'edit' || $reqMode == 'cancel' || $reqMode == 'view')
{
	
	if($reqDetilId == "")
	{
		$set->selectByParams(array('A.PENILAIAN_KERJA_PEGAWAI_ID'=>$reqRowId));
	}
	else
	{
		$set->selectByParams(array('A.TEMP_VALIDASI_ID'=>$reqDetilId));
	}
	// echo $set->query;exit;
	$set->firstRow();
	$reqRowId= $set->getField('PENILAIAN_KERJA_PEGAWAI_ID');
	$reqDataId= $set->getField('TEMP_VALIDASI_ID');
	$reqPerubahanData= $set->getField('PERUBAHAN_DATA');
	
	// $reqTMTSK= dateToPageCheck($set->getField('TMT_SK'));$valTMTSK= checkwarna($reqPerubahanData, 'TMT_SK', "date");
	// $reqSelesai= dateToPageCheck($set->getField('SELESAI_KONTRAK'));$valSelesai= checkwarna($reqPerubahanData, 'SELESAI_KONTRAK', "date");

	$reqTahun= $set->getField('TAHUN');$valTahun= checkwarna($reqPerubahanData, 'TAHUN');
	$reqNilai1= $set->getField("NILAI1");$valNilai1= checkwarna($reqPerubahanData, 'NILAI1');
	$reqNilai2= $set->getField("NILAI2");$valNilai2= checkwarna($reqPerubahanData, 'NILAI2');
	$reqNilai3= $set->getField("NILAI3");$valNilai3= checkwarna($reqPerubahanData, 'NILAI3');
	$reqNilai4= $set->getField("NILAI4");$valNilai4= checkwarna($reqPerubahanData, 'NILAI4');
	$reqNilai5= $set->getField("NILAI5");$valNilai5= checkwarna($reqPerubahanData, 'NILAI5');
	$reqNilai6= $set->getField("NILAI6");$valNilai6= checkwarna($reqPerubahanData, 'NILAI6');
	$reqJumlah= $set->getField("JUMLAH");$valJumlah= checkwarna($reqPerubahanData, 'JUMLAH');

	$reqSasaranKerja= $set->getField("SASARAN_KERJA");$valSasaranKerja= checkwarna($reqPerubahanData, 'NILAI6');
	$reqSasaranKerjaHasil= $set->getField("SASARAN_KERJA_HASIL");$valSasaranKerjaHasil= checkwarna($reqPerubahanData, 'SASARAN_KERJA_HASIL');
	$reqPerilakuHasil= $set->getField("PERILAKU_HASIL");$valPerilakuHasil= checkwarna($reqPerubahanData, 'PERILAKU_HASIL');
	$reqNilaiHasil= $set->getField("NILAI_HASIL");$valNilaiHasil= checkwarna($reqPerubahanData, 'NILAI_HASIL');

	$reqPejabatNipId= $set->getField("PEJABAT_PENILAI_ID");
	$reqPejabatNipNama= $set->getField("PEGAWAI_INFO");

	$reqNamaPejabat= $set->getField("NAMA_PENILAI");
	$reqPejabatJabatan= $set->getField("JABATAN_PENILAI");$valPejabatJabatan= checkwarna($reqPerubahanData, 'JABATAN_PENILAI');
	$reqPejabatUnor= $set->getField("UNOR_PENILAI");$valPejabatUnor= checkwarna($reqPerubahanData, 'UNOR_PENILAI');
	$reqPejabatGolongan= $set->getField("GOLONGAN_PENILAI");$valPejabatGolongan= checkwarna($reqPerubahanData, 'GOLONGAN_PENILAI');
	$reqPejabatTmtGolongan= dateToPageCheck($set->getField("TMT_GOLONGAN"));$valPejabatTmtGolongan= checkwarna($reqPerubahanData, 'TMT_GOLONGAN', "date");

	$reqPejabatStatus= $set->getField("STATUS");$valPejabatStatus= checkwarna($reqPerubahanData, 'STATUS');
	$reqAtasanNipId= $set->getField("ATASAN_PEJABAT_PENILAI_ID");
	$reqAtasanNipNama= $set->getField("PEGAWAI_ATASAN_INFO");
		// print_r($reqAtasanNipNama);exit;
	$reqAtasanStatus= $set->getField("STATUS_ATASAN");$valAtasanStatus= checkwarna($reqPerubahanData, 'STATUS_ATASAN');

	$reqNamaAtasan= $set->getField("ATASAN_PENILAI");$valNamaAtasan= checkwarna($reqPerubahanData, 'ATASAN_PENILAI');

	$reqAtasanJabatan= $set->getField("JABATAN_ATASAN_PENILAI");$valAtasanJabatan= checkwarna($reqPerubahanData, 'JABATAN_ATASAN_PENILAI');
	$reqAtasanUnor= $set->getField("UNOR_ATASAN_PENILAI");$valAtasanUnor= checkwarna($reqPerubahanData, 'UNOR_ATASAN_PENILAI');
	$reqAtasanGolongan= $set->getField("GOLONGAN_ATASAN_PENILAI");$valAtasanGolongan= checkwarna($reqPerubahanData, 'GOLONGAN_ATASAN_PENILAI');
	$reqAtasanTmtGolongan= dateToPageCheck($set->getField("TMT_GOLONGAN_ATASAN"));$valAtasanTmtGolongan= checkwarna($reqPerubahanData, 'TMT_GOLONGAN_ATASAN', "date");

	$reqGolRuang= $set->getField('PANGKAT_ID');$valGolRuang= checkwarna($reqPerubahanData, 'PANGKAT_ID', $arrgol, array("id","text"));
	$reqJenisJabatanId= $set->getField("JENIS_JABATAN_ID"); 
	$valGolonganPppkId= checkwarna($reqPerubahanData, 'JENIS_JABATAN_ID', $arrpejabat, array("id","text"));
	$reqValidasi= $set->getField('VALIDASI');
	$reqLinkFile= $set->getField('LINK_FILE');
	$reqFileId= $set->getField('PENILAIAN_KERJA_PEGAWAI_FILE_ID');
	
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

<meta http-equiv="Cache-Control" content="no-store" />

<script type="text/javascript" src="functions/globalfunction.js"></script>

<div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
	<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<div class="d-flex align-items-center flex-wrap mr-1">
			<div class="d-flex align-items-baseline flex-wrap mr-5">
				<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">Data Lain-Lain</a>
					</li>
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">SKP</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
        	<!-- <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="flaticon2-notepad text-primary"></i>
                    </span>
                    <h3 class="card-label">Riwayat SKP</h3>
                </div>
            </div> -->
            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
            	<ul class="nav nav-tabs">
            		<li> <a class="nav-link active" id="home-tab" data-toggle="tab" href="#riwayatskp"> &nbsp;Riwayat SKP</a></li>
            		<li><a class="nav-link" data-toggle="tab" href="#pejabatpenilai">&nbsp;Pejabat Penilai</a></li>
            		<li><a class="nav-link" data-toggle="tab" href="#atasanpejabatpenilai">&nbsp;Atasan Pejabat Penilai</a></li>
            	</ul>
	        	<div class="card-body">
	        		<div class="tab-content">
	        			<div class="tab-pane fade show active" id="riwayatskp" role="tabpanel" aria-labelledby="riwayatskp-tab">
			        		<div class="form-group row">
			        			<label class="col-form-label text-right col-lg-2 col-sm-12">Jenis Jabatan
			        			<?
			        			if(!empty($valGolonganPppkId['data']))
			        			{
			        			?>
			        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valGolonganPppkId['data']?>"></i>
			        			<?
			        			}
			        			?>
			        			</label>
			        			<div class="col-lg-4 col-sm-12">
			        				<select class="form-control select2 <?=$valGolonganPppkId['warna']?>" id="ktgolid" name="reqJenisJabatanId">
			        					<option value=""></option>
			        					<?
			        					foreach($arrgol as $item) 
			        					{
			        						$selectvalid= $item["id"];
			        						$selectvaltext= $item["text"];
			        					?>
			        					<option value="<?=$selectvalid?>" <? if($reqJenisJabatanId == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
			        					<?
			        					}
			        					?>
			        				</select>
			        			</div>
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
			        			<div class="col-lg-1 col-sm-12">
			        				<input type="text" class="form-control <?=$valTahun['warna']?>" name="reqTahun" id="reqTahun" value="<?=$reqTahun?>"  maxlength="4"/>
			        			</div>
			        			
			        		</div>
			        		<div class="form-group row">
			        			<label class="col-form-label text-right col-lg-2 col-sm-12"><b>Sasaran Kinerja Pegawai</b>
			        			</label>
			        		</div>
			        		<div class="form-group row">
			        			<label class="col-form-label text-right col-lg-2 col-sm-12">Nilai SKP
			        			<?
			        			if(!empty($valSasaranKerja['data']))
			        			{
			        			?>
			        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valSasaranKerja['data']?>"></i>
			        			<?
			        			}
			        			?>
			        			</label>
			        			<div class="form-group col-xs-3 col-xs-offset-1">
			        				<input type="text" style="width:100px;margin-left: 11px" class="form-control <?=$valSasaranKerja['warna']?>"  name="reqSasaranKerja" id="reqSasaranKerja" value="<?=$reqSasaranKerja?>" />
			        			</div>
			        			
			        			<div class="form-group col-xs-3 col-xs-offset-1">
			        				<input type="text" disabled style="width:65px;margin-left: 11px" class="form-control " value="60%" />
			        			</div>
			        			<div class="form-group col-xs-3 col-xs-offset-1">
			        				<input type="text" readonly class="form-control " style="width:65px;margin-left: 11px;background-color: #F3F6F9;"  name="reqSasaranKerjaHasilNama" id="reqSasaranKerjaHasilNama" value="<?=$reqSasaranKerjaHasilNama?>" />
			        				<input type="hidden" name="reqSasaranKerjaHasil" id="reqSasaranKerjaHasil" style="width:150px;" value="<?=$reqSasaranKerjaHasil?>" />
			        			</div>
			        		</div>
			        		<div class="form-group row">
			        			<label class="col-form-label text-right col-lg-2 col-sm-12"><b>Perilaku Kerja</b>
			        			</label>
			        		</div>
			        		<div class="form-group row">
			        			<label class="col-form-label text-right col-lg-2 col-sm-12">Orientasi Pelayanan
			        			<?
			        			if(!empty($valNilai1['data']))
			        			{
			        			?>
			        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNilai1['data']?>"></i>
			        			<?
			        			}
			        			?>
			        			</label>
			        			<div class="form-group col-xs-3 col-xs-offset-1">
			        				<input type="text" style="width:100px;margin-left: 11px" class="form-control <?=$valNilai1['warna']?>" name="reqNilai1" id="reqNilai1" value="<?=$reqNilai1?>" />
			        			</div>
			        			<div class="form-group col-xs-3 col-xs-offset-1">
			        				<input type="text" disabled style="width:120px;margin-left: 11px" id="reqNilaiNama1" class="form-control " value=""/>
			        			</div>
			        			<label class="col-form-label text-right col-lg-2 col-sm-12">Integritas
			        			<?
			        			if(!empty($valNilai2['data']))
			        			{
			        			?>
			        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNilai2['data']?>"></i>
			        			<?
			        			}
			        			?>
			        			</label>
			        			<div class="form-group col-xs-3 col-xs-offset-1">
			        				<input type="text" style="width:100px;margin-left: 11px" class="form-control <?=$valNilai2['warna']?>" name="reqNilai2" id="reqNilai2" value="<?=$reqNilai2?>" />
			        			</div>
			        			<div class="form-group col-xs-3 col-xs-offset-1">
			        				<input type="text" disabled style="width:120px;margin-left: 11px" id="reqNilaiNama2" class="form-control " value="" />
			        			</div>
			        		</div>
			        		<div class="form-group row">
			        			<label class="col-form-label text-right col-lg-2 col-sm-12">Komitmen
			        			<?
			        			if(!empty($valNilai3['data']))
			        			{
			        			?>
			        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valMasaBerlaku['data']?>"></i>
			        			<?
			        			}
			        			?>
			        			</label>
			        			<div class="form-group col-xs-3 col-xs-offset-1">
			        				<input type="text" style="width:100px;margin-left: 11px" class="form-control <?=$valNilai3['warna']?>" name="reqNilai3" id="reqNilai3" value="<?=$reqNilai3?>" />
			        			</div>
			        			<div class="form-group col-xs-3 col-xs-offset-1">
			        				<input type="text" disabled style="width:120px;margin-left: 11px" id="reqNilaiNama3" class="form-control " value="" />
			        			</div>
			        			<label class="col-form-label text-right col-lg-2 col-sm-12">Disiplin
			        			<?
			        			if(!empty($valNilai4['data']))
			        			{
			        			?>
			        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNilai4['data']?>"></i>
			        			<?
			        			}
			        			?>
			        			</label>
			        			<div class="form-group col-xs-3 col-xs-offset-1">
			        				<input type="text" style="width:100px;margin-left: 11px" class="form-control <?=$valNilai4['warna']?>" name="reqNilai4" id="reqNilai4" value="<?=$reqNilai4?>" />
			        			</div>
			        			<div class="form-group col-xs-3 col-xs-offset-1">
			        				<input type="text" disabled style="width:120px;margin-left: 11px" id="reqNilaiNama4" class="form-control " value="<?=$reqNilaiNama4?>" />
			        			</div>
			        		</div>
			        		<div class="form-group row">
			        			<label class="col-form-label text-right col-lg-2 col-sm-12">Kerja Sama
			        				<?
			        				if(!empty($valNilai5['data']))
			        				{
			        					?>
			        					<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNilai5['data']?>"></i>
			        					<?
			        				}
			        				?>
			        			</label>
			        			<input type="text" style="width:100px;margin-left: 11px" class="form-control <?=$valNilai5['warna']?>" name="reqNilai5" id="reqNilai5" value="<?=$reqNilai5?>" />
			        			
			        			<input type="text" disabled style="width:120px;margin-left: 11px" id="reqNilaiNama5" class="form-control " value="<?=$reqNilaiNama5?>" />
			        			
			        			<label id="labelnilai6" class="col-form-label text-right col-lg-2 col-sm-12">Kepemimpinan
			        				<?
			        				if(!empty($valNilai6['data']))
			        				{
			        					?>
			        					<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNilai6['data']?>"></i>
			        					<?
			        				}
			        				?>
			        			</label>
			        			<input type="text" style="width:100px;margin-left: 11px" class="form-control <?=$valNilai6['warna']?>" name="reqNilai6" id="reqNilai6" value="<?=$reqNilai6?>" />
			        		</div>
			        		<div class="form-group row">
			        			<input type="hidden" name="reqRataRata" id="reqRataRata" style="width:34px;<?=$reqRataRataTable?>" value="<?=$reqRataRata?>" /><?=$reqRataRataBalon?>
			        		</div>
			        		<div class="form-group row">
			        			<label class="col-form-label text-right col-lg-2 col-sm-12">Nilai Perilaku Kerja
			        			<?
			        			if(!empty($valPerilakuKinerja['data']))
			        			{
			        			?>
			        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valPerilakuKinerja['data']?>"></i>
			        			<?
			        			}
			        			?>
			        			</label>
			        			<div class="form-group col-xs-3 col-xs-offset-1">
			        				<input type="text" readonly style="width:100px;margin-left: 11px;background-color: #F3F6F9;" class="form-control <?=$valPerilakuKinerja['warna']?>"  name="reqPerilakuKinerja" id="reqPerilakuKinerja" value="<?=$reqPerilakuKinerja?>" />
			        			</div>
			        			
			        			<div class="form-group col-xs-3 col-xs-offset-1">
			        				<input type="text" disabled style="width:65px;margin-left: 11px" class="form-control " value="40%" />
			        			</div>
			        			<div class="form-group col-xs-3 col-xs-offset-1">
			        				<input type="text" readonly class="form-control " style="width:65px;margin-left: 11px;background-color: #F3F6F9;"  name="reqPerilakuHasilNama" id="reqPerilakuHasilNama" value="<?=$reqPerilakuHasilNama?>" />
			        				<input type="hidden" name="reqPerilakuHasil" id="reqPerilakuHasil" style="width:34px;" value="<?=$reqPerilakuHasil?>" /><
			        			</div>
			        		</div>
			        		<div class="form-group row">
			        			<label class="col-form-label text-right col-lg-2 col-sm-12">Nilai Prestasi Kerja
			        			</label>
			        			<div class="form-group col-xs-3 col-xs-offset-1">
			        				<input type="text" readonly style="width:100px;margin-left: 11px;background-color: #F3F6F9;" class="form-control <?=$valMasaBerlaku['warna']?>"  name="reqHasilNilaiInfo" id="reqHasilNilaiInfo" value="<?=$reqHasilNilaiInfo?>" />
			        			</div>
			        			<div class="form-group col-xs-3 col-xs-offset-1">
			        				<input type="text" disabled class="form-control " style="width:120px;margin-left: 11px;color: #787878;"  name="reqHasilNilaiNama" id="reqHasilNilaiNama" value="<?=$reqHasilNilaiNama?>" />
			        			</div>
			        			<input type="hidden" name="reqNilaiHasil" id="reqHasilNilai" style="width:34px;" value="<?=$reqNilaiHasil?>" />
			        		</div>
			        	</div>
			        	<div class="tab-pane fade" id="pejabatpenilai" role="tabpanel" aria-labelledby="profile-tab">
			        		<div class="form-group row">
			        			<label class="col-form-label text-right col-lg-2 col-sm-12"> Status Pejabat Penilai
			        			</label>
			        			<div class="col-lg-4 col-sm-12" style="margin-top: 8.5px">
			        				<input type="radio" <? if ($reqPejabatStatus== 1) echo "checked" ?> name="reqPejabatStatus" id="reqPejabatStatus" value="1" />
			        				<label style="width:auto;" > &nbsp;&nbsp;&nbsp;Pns</label> 
			        				&nbsp;
			        				<input type="radio" <? if ($reqPejabatStatus== 2) echo "checked" ?> name="reqPejabatStatus" id="reqPejabatStatus" value="2" />
			        				<label style="width:auto;" > &nbsp;&nbsp;&nbsp;Bukan Pns Lamongan</label> 
			        			</div>
			        		</div>
			        		<div class="form-group row">
			        			<label class="col-form-label text-right col-lg-2 col-sm-12">NIP</label>
			        			<div class="col-lg-10 col-sm-12">
			        				<select style="width: 50%" class="form-control select2" id="ktpejabatnip" <?=$disabled?> name="reqPejabatNip">
			        					<option value="<?=$reqPejabatNipId?>"><?=$reqPejabatNipNama?></option>
			        				</select> <img src="images/delete-icon.png" id="clear_data">
			        				<span class="form-text text-muted"><label id="nipdetil"></label></span>
			        				<input type="hidden" name="reqPejabatNipId" id="reqPejabatNipId" value="<?=$reqPejabatNipId?>" >
			        			</div>
			        		</div>
			        		<div class="form-group row">
			        			<label class="col-form-label text-right col-lg-2 col-sm-12">Nama</label>
			        			<div class="col-lg-10 col-sm-12">
			        				<input type="text" class="form-control" <?=$disabled?> name="reqNamaPejabat" id="reqNamaPejabat" value="<?=$reqNamaPejabat?>" />
			        			</div>
			        		</div>
			        		<div class="form-group row">
			        			<label class="col-form-label text-right col-lg-2 col-sm-12">Jabatan</label>
			        			<?
			        			if(!empty($valPejabatJabatan['data']))
			        			{
			        			?>
			        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valPejabatJabatan['data']?>"></i>
			        			<?
			        			}
			        			?>
			        			<div class="col-lg-10 col-sm-12">
			        				<input type="text" class="form-control" <?=$disabled?> name="reqPejabatJabatan" id="reqPejabatJabatan" value="<?=$reqPejabatJabatan?>" />
			        			</div>
			        		</div>
			        		<div class="form-group row">
			        			<label class="col-form-label text-right col-lg-2 col-sm-12">Unor</label>
			        			<?
			        			if(!empty($valPejabatUnor['data']))
			        			{
			        			?>
			        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valPejabatUnor['data']?>"></i>
			        			<?
			        			}
			        			?>
			        			<div class="col-lg-10 col-sm-12">
			        				<input type="text" class="form-control" <?=$disabled?> name="reqPejabatUnor" id="reqPejabatUnor" value="<?=$reqPejabatUnor?>" />
			        			</div>
			        		</div>
			        		<div class="form-group row">
			        			<label class="col-form-label text-right col-lg-2 col-sm-12">Golongan</label>
			        			<?
			        			if(!empty($valPejabatGolongan['data']))
			        			{
			        			?>
			        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valPejabatGolongan['data']?>"></i>
			        			<?
			        			}
			        			?>
			        			<div class="col-lg-4 col-sm-12">
			        				<input type="text" class="form-control" <?=$disabled?> name="reqPejabatGolongan" id="reqPejabatGolongan" value="<?=$reqPejabatGolongan?>" />
			        			</div>
			        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tmt Golongan</label>
			        			<div class="col-lg-2 col-sm-12">
			        				<div class="input-group date">
			        					<input type="text" class="form-control <?=$valPejabatGolongan['warna']?>" id="kttglgolongan" name="reqPejabatTmtGolongan" readonly="readonly" placeholder="" value="<?=$reqPejabatTmtGolongan?>" />
			        					<div class="input-group-append">
			        						<span class="input-group-text">
			        							<i class="la la-calendar"></i>
			        						</span>
			        					</div>
			        				</div>
			        			</div>
			        		</div>
			        	</div>
			        	<div class="tab-pane fade" id="atasanpejabatpenilai" role="tabpanel" aria-labelledby="profile-tab">
			        		<div class="form-group row">
			        			<label class="col-form-label text-right col-lg-2 col-sm-12"> Status Atasan Penilai
			        			</label>
			        			<div class="col-lg-4 col-sm-12" style="margin-top: 8.5px">
			        				<input type="radio" <? if ($reqAtasanStatus== 1) echo "checked" ?> name="reqAtasanStatus" id="reqAtasanStatus" value="1" />
			        				<label style="width:auto;" > &nbsp;&nbsp;&nbsp;Pns</label> 
			        				&nbsp;
			        				<input type="radio"  <? if ($reqAtasanStatus== 2) echo "checked" ?> name="reqAtasanStatus" id="reqAtasanStatus" value="2" />
			        				<label style="width:auto;" > &nbsp;&nbsp;&nbsp;Bukan Pns Lamongan</label> 
			        			</div>
			        		</div>
			        		<div class="form-group row">
			        			<label class="col-form-label text-right col-lg-2 col-sm-12">NIP</label>
			        			<?
			        			if(!empty($valPejabatGolongan['data']))
			        			{
			        			?>
			        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valPejabatGolongan['data']?>"></i>
			        			<?
			        			}
			        			?>
			        			<div class="col-lg-10 col-sm-12">
			        				<select style="width: 50%" class="form-control select2" id="ktatasannip" <?=$disabled?> name="reqAtasanNip">
			        					<option value="<?=$reqAtasanNipId?>"><?=$reqAtasanNipNama?></option>
			        				</select> <img src="images/delete-icon.png" id="clear_data_atasan">
			        				<span class="form-text text-muted"><label id="nipatasandetil"></label></span>
			        				<input type="hidden" name="reqAtasanNipId" id="reqAtasanNipId" value="<?=$reqAtasanNipId?>" >
			        			</div>
			        		</div>
			        		<div class="form-group row">
			        			<label class="col-form-label text-right col-lg-2 col-sm-12">Nama</label>
			        			<?
			        			if(!empty($valNamaAtasan['data']))
			        			{
			        			?>
			        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNamaAtasan['data']?>"></i>
			        			<?
			        			}
			        			?>
			        			<div class="col-lg-8 col-sm-12">
			        				<input type="text" class="form-control" <?=$disabled?> name="reqNamaAtasan" id="reqNamaAtasan" value="<?=$reqNamaAtasan?>" />
			        			</div>
			        		</div>
			        		<div class="form-group row">
			        			<label class="col-form-label text-right col-lg-2 col-sm-12">Jabatan</label>
			        			<?
			        			if(!empty($valAtasanJabatan['data']))
			        			{
			        			?>
			        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valAtasanJabatan['data']?>"></i>
			        			<?
			        			}
			        			?>
			        			<div class="col-lg-8 col-sm-12">
			        				<input type="text" class="form-control" <?=$disabled?> name="reqAtasanJabatan" id="reqAtasanJabatan" value="<?=$reqAtasanJabatan?>" />
			        			</div>
			        		</div>
			        		<div class="form-group row">
			        			<label class="col-form-label text-right col-lg-2 col-sm-12">Unor</label>
			        			<?
			        			if(!empty($valAtasanUnor['data']))
			        			{
			        			?>
			        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valAtasanUnor['data']?>"></i>
			        			<?
			        			}
			        			?>
			        			<div class="col-lg-8 col-sm-12">
			        				<input type="text" class="form-control" <?=$disabled?> name="reqAtasanUnor" id="reqAtasanUnor" value="<?=$reqAtasanUnor?>" />
			        			</div>
			        		</div>
			        		<div class="form-group row">
			        			<label class="col-form-label text-right col-lg-2 col-sm-12">Golongan</label>
			        			<?
			        			if(!empty($valAtasanGolongan['data']))
			        			{
			        			?>
			        				<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valAtasanGolongan['data']?>"></i>
			        			<?
			        			}
			        			?>
			        			<div class="col-lg-4 col-sm-12">
			        				<input type="text" class="form-control" <?=$disabled?> name="reqAtasanGolongan" id="reqAtasanGolongan" value="<?=$reqAtasanGolongan?>" />
			        			</div>

			        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tmt Golongan</label>
			        			<div class="col-lg-2 col-sm-12">
			        				<div class="input-group date">
			        					<input type="text" class="form-control <?=$valTanggal['warna']?>" id="kttglatasangolongan" name="reqAtasanTmtGolongan" readonly="readonly" placeholder="" value="<?=$reqAtasanTmtGolongan?>" />
			        					<div class="input-group-append">
			        						<span class="input-group-text">
			        							<i class="la la-calendar"></i>
			        						</span>
			        					</div>
			        				</div>
			        			</div>
			        		</div>
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

	        				<?if(empty($reqLinkFile))
	        				{
	        				?>
		        				<input type="button" id="upload" class="btn btn-success font-weight-bold mr-2" value="Upload" onclick="document.getElementById('reqLinkFile').click();" />
		        				<input type="file" style="display:none;" id="reqLinkFile" name="reqLinkFile"  accept=".pdf"/>
	        				<?
	        				}
	        				else
	        				{
	        				?>
	        					<a class="btn btn-success font-weight-bold mr-2" href="app/loadUrl/main/viewer?reqForm=skp&reqFileId=<?=$reqFileId?>" target="_blank">Download</a>
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
	$(document).on("input", "#reqTahun,#reqSasaranKerja", function() {
		this.value = this.value.replace(/\D/g,'');
	});

	function kembali() {
		window.location.href='app/index/pegawai_skp'
	}

	var reqJenisJabatanId = '<?=$reqJenisJabatanId?>';
	// console.log(reqJenisJabatanId);
	if (reqJenisJabatanId == 1)
	{
		// valNilai6
		$("#reqNilai6").show();
		$("#labelnilai6").show();
	}
	else
	{
		$("#reqNilai6").hide();
		$("#labelnilai6").hide();
	}
	// console.log(reqJenisJabatanId);

	$('reqJenisJabatanId').on('change', function() {
	var reqJenisJabatanId =	$("#reqJenisJabatanId").val();
	if (reqJenisJabatanId == 1)
	{
		// valNilai6
		$("#reqNilai6").show();
		$("#labelnilai6").show();
	}
	else
	{
		$("#reqNilai6").hide();
		$("#labelnilai6").hide();
	}
		
	});


	$("#radioID") 
    .change(function(){ 
        if( $(this).is(":checked") ){ // check if the radio is checked
            var val = $(this).val(); // retrieve the value
        }
    });
	function tampilNip(val) {
        if (val.loading) return val.text;
        var markup = "<div class='select2-result-repository clearfix'>" +
	            "<div class='select2-result-repository__meta'>" +
	            	"<div class='select2-result-repository__title'>" + val.description + "</div>" +
	            "</div>" +
            "</div>";
        return markup;
    }
    function pilihNip(val) {
    	// console.log(val);
    	if(val.id !="")
    	{
    		
    		if(val.nip !="")
    		{
    			$("#nipdetil").text(val.nip);
    		}
    		if(val.nama != undefined)
    		{
    			$("#reqNamaPejabat").val(val.nama);
    		}
    		if(val.jabatan !=undefined)
    		{
    			$("#reqPejabatJabatan").val(val.jabatan);
    		}
    		if(val.golongan !=undefined)
    		{
    			$("#reqPejabatGolongan").val(val.golongan);	
    		}
    		if(val.unor !=undefined)
    		{
    			$("#reqPejabatUnor").val(val.unor);
    		}
    		if(val.tmt_gol !=undefined)
    		{
    			$("#kttglgolongan").val(val.tmt_gol);
    		}
    		
    		$("#reqPejabatNipId").val(val.id);
    		$("#reqPejabatNip").val(val.text);

    		$("#reqNamaPejabat").attr("disabled", true);
    		$("#reqPejabatJabatan").attr("disabled", true); 
    		$("#reqPejabatGolongan").attr("disabled", true);
    		$("#reqPejabatUnor").attr("disabled", true);
    		$("#kttglgolongan").attr("readonly", true);   
    	}	
        // console.log(val);
        return val.text;
    }
    function pilihAtasanNip(val) {
    	if(val.id !="")
    	{
    		if(val.nip !="")
    		{
    			 $("#nipatasandetil").text(val.nip);
    		}
    		if(val.nama != undefined)
    		{
    			 $("#reqNamaAtasan").val(val.nama);
    		}
    		if(val.jabatan !=undefined)
    		{
    			 $("#reqAtasanJabatan").val(val.jabatan);
    		}
    		if(val.golongan !=undefined)
    		{
    			$("#reqAtasanGolongan").val(val.golongan);
    		}
    		if(val.unor !=undefined)
    		{
    			$("#reqAtasanUnor").val(val.unor);
    		}
    		if(val.tmt_gol !=undefined)
    		{
    			$("#kttglatasangolongan").val(val.tmt_gol);
    		}

    		$("#reqAtasanNipId").val(val.id);
    		$("#reqAtasanNip").val(val.text); 
    		$("#reqNamaAtasan").attr("disabled", true);
    		$("#reqAtasanJabatan").attr("disabled", true); 
    		$("#reqAtasanGolongan").attr("disabled", true);
    		$("#reqAtasanUnor").attr("disabled", true);
    		$("#kttglatasangolongan").attr("readonly", true);  
    	}	
        // console.log(val);
        return val.text;
    }
	$('#ktgolid').select2({
		placeholder: "Pilih jenis jabatan"
	});
	$('input[name="reqPejabatStatus"]') 
    .change(function(){ 
        if( $(this).is(":checked") ){ 
            var val = $(this).val(); 
            if (val == 2)
            {
            	$('#ktpejabatnip').prop('disabled', true);
            	$("#reqNamaPejabat").attr("disabled", false);
            	$("#reqPejabatJabatan").attr("disabled", false); 
            	$("#reqPejabatGolongan").attr("disabled", false);
            	$("#reqPejabatUnor").attr("disabled", false);
            	$("#kttglgolongan").attr("readonly", false);
            	$("#ktpejabatnip"). empty();
            	$("#nipdetil").text('');
            	$("#reqPejabatNipId").val('');
            	$("#reqPejabatNip").val('');
            	$("#reqNamaPejabat").val('');
            	$("#reqPejabatJabatan").val('');
            	$("#reqPejabatGolongan").val('');
            	$("#reqPejabatUnor").val('');
            	$("#kttglgolongan").val('');  
            }
            else
            {
            	$('#ktpejabatnip').prop('disabled', false);
            	$("#reqNamaPejabat").attr("disabled", true);
            	$("#reqPejabatJabatan").attr("disabled", true); 
            	$("#reqPejabatGolongan").attr("disabled", true);
            	$("#reqPejabatUnor").attr("disabled", true);
            	$("#kttglgolongan").attr("readonly", true);
            }
            // console.log(val)
        }
    });
    $('input[name="reqAtasanStatus"]') 
    .change(function(){ 
        if( $(this).is(":checked") ){ 
            var val = $(this).val(); 
            if (val == 2)
            {
            	$('#ktatasannip').prop('disabled', true);
            	$("#reqNamaAtasan").attr("disabled", false);
            	$("#reqAtasanJabatan").attr("disabled", false); 
            	$("#reqAtasanGolongan").attr("disabled", false);
            	$("#reqAtasanUnor").attr("disabled", false);
            	$("#kttglatasangolongan").attr("disabled", false);   

            	$("#ktatasannip"). empty();
            	$("#nipatasandetil").text('');
            	$("#reqAtasanNipId").val('');
            	$("#reqAtasanNip").val('');
            	$("#reqNamaAtasan").val('');
            	$("#reqAtasanJabatan").val('');
            	$("#reqAtasanGolongan").val('');
            	$("#reqAtasanUnor").val('');
            	$("#kttglatasangolongan").val('');  
            }
            else
            {
            	$('#ktatasannip').prop('disabled', false);
            	$("#reqNamaAtasan").attr("disabled", true);
            	$("#reqAtasanJabatan").attr("disabled", true); 
            	$("#reqAtasanGolongan").attr("disabled", true);
            	$("#reqAtasanUnor").attr("disabled", true);
            	$("#kttglatasangolongan").attr("readonly", true);   
            }
            // console.log(val)
        }
    });
	$("#clear_data").on("click", function(){
       $("#reqNamaPejabat").attr("disabled", false);
       $("#reqPejabatJabatan").attr("disabled", false); 
       $("#reqPejabatGolongan").attr("disabled", false);
       $("#reqPejabatUnor").attr("disabled", false);
       $("#kttglgolongan").attr("readonly", false);

       $("#ktpejabatnip"). empty();

       $("#nipdetil").text('');
       $("#reqPejabatNipId").val('');
       $("#reqPejabatNip").val('');

       $("#reqNamaPejabat").val('');
       $("#reqPejabatJabatan").val('');
       $("#reqPejabatGolongan").val('');
       $("#reqPejabatUnor").val('');
       $("#kttglgolongan").val('');  
   });
	$("#clear_data_atasan").on("click", function(){
		$("#reqNamaAtasan").attr("disabled", false);
		$("#reqAtasanJabatan").attr("disabled", false); 
		$("#reqAtasanGolongan").attr("disabled", false);
		$("#reqAtasanUnor").attr("disabled", false);
		$("#kttglatasangolongan").attr("disabled", false);

		$("#ktatasannip"). empty();

		$("#nipatasandetil").text('');
		$("#reqAtasanNipId").val('');
		$("#reqAtasanPejabatNip").val('');

		$("#reqNamaAtasan").val('');
		$("#reqAtasanJabatan").val('');
		$("#reqAtasanGolongan").val('');
		$("#reqAtasanUnor").val('');
		$("#kttglatasangolongan").val('');  
	});
	$("#ktpejabatnip").select2({
        placeholder: "Pilih nip",
        allowClear: true,
        ajax: {
            url: "json-data/combo_json/autocompletedatapegawai",
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
        templateResult: tampilNip, // omitted for brevity, see the source of this page
        templateSelection: pilihNip // omitted for brevity, see the source of this page
    });
    $("#ktatasannip").select2({
        placeholder: "Pilih nip",
        allowClear: true,
        ajax: {
            url: "json-data/combo_json/autocompletedatapegawai",
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
        templateResult: tampilNip, // omitted for brevity, see the source of this page
        templateSelection: pilihAtasanNip // omitted for brevity, see the source of this page
    });
    arrows= {leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'};
	$('#kttglgolongan,#kttglatasangolongan').datepicker({
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
		var formSubmitUrl = "json-validasi/personal_json/jsonskpadd";
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
			        		document.location.href = "app/index/pegawai_skp";
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
	$(function(){
		setHitung();
		$('input[id^="reqNilai"]').each(function(i){
			var id= $(this).attr('id');
			id= id.replace("reqNilai", "");
			$(document).on("input", "#reqNilai"+id, function() {
				this.value = this.value.replace(/\D/g,'');
			});
			setNilai(id);
		});

		$("#reqSasaranKerja").keyup(function() {
			setNilaiHasil();
		});

		$('input[id^="reqNilai"]').keyup(function() {
			var id= $(this).attr('id');
			id= id.replace("reqNilai", "");
			// console.log(id);

			setNilai(id);
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
						// console.log(tempJumlah);

			tempJumlah= tempJumlah.toFixed(2);
			tempRataRata= tempRataRata.toFixed(2);

			$("#reqRataRataInfo").text(setNilaiInfo(tempRataRata));
			
			tempJumlah= String(tempJumlah);
			tempJumlah= tempJumlah.replace(".", ",");
			tempRataRata= String(tempRataRata);
			tempRataRata= tempRataRata.replace(".", ",");
			$("#reqJumlahNama").val(tempJumlah);
			$("#reqJumlah").val(tempJumlah);
			$("#reqPerilakuKinerja, #reqRataRataNama").val(tempRataRata);
			$("#reqRataRata").val(tempRataRata);
			
			setNilaiHasil();
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

		function setNilaiHasil()
		{

			var reqSasaranKerja= reqSasaranKerjaHasil= tempSasaranKerjaHasil= reqRataRata= reqPerilakuHasil= tempPerilakuHasil= reqHasilNilai= "";
			
			reqSasaranKerja= $("#reqSasaranKerja").val();
			reqSasaranKerja= decodeFloat(reqSasaranKerja);
			// console.log(reqSasaranKerja);

			reqSasaranKerja= checkNan(reqSasaranKerja);
			
			reqSasaranKerjaHasil= (reqSasaranKerja * 60) / 100;
			tempSasaranKerjaHasil= reqSasaranKerjaHasil= reqSasaranKerjaHasil.toFixed(2);
			reqSasaranKerjaHasilNama= encodeFloat(reqSasaranKerjaHasil);
			// console.log(reqSasaranKerjaHasilNama);

			$("#reqSasaranKerjaHasilNama").val(reqSasaranKerjaHasilNama);
			$("#reqSasaranKerjaHasil").val(reqSasaranKerjaHasilNama);
			
			//------------

			reqRataRata= $("#reqRataRata").val();
			reqRataRata= decodeFloat(reqRataRata);
			reqRataRata= checkNan(reqRataRata);
			
			reqPerilakuHasil= (reqRataRata * 40) / 100;
			tempPerilakuHasil= reqPerilakuHasil= reqPerilakuHasil.toFixed(2);
			reqPerilakuHasilNama= encodeFloat(reqPerilakuHasil);
			// console.log(reqRataRata);
			
			$("#reqPerilakuHasilNama").val(reqPerilakuHasilNama);
			$("#reqPerilakuHasil").val(reqPerilakuHasilNama);
			
			//------------
			reqHasilNilai= parseFloat(tempSasaranKerjaHasil) + parseFloat(tempPerilakuHasil);
			reqHasilNilai= reqHasilNilai.toFixed(2);
			
			$("#reqHasilNilaiNama").val(setNilaiInfo(reqHasilNilai));
			reqHasilNilai= encodeFloat(reqHasilNilai);
			$("#reqHasilNilaiInfo").val(reqHasilNilai);
			$("#reqHasilNilai").val(reqHasilNilai);
			
		}

		function checkNan(value)
		{
			if(typeof value == "undefined" || isNaN(value) || value == "")
				return 0;
			else
				return value;
		}
		
		function setNilaiInfo(value)
		{
			reqNilai= checkNan(value);
			
			if(parseFloat(reqNilai) <= 50)
				return "Kurang";
			else if(parseFloat(reqNilai) <= 60)
				return "Sedang";
			else if(parseFloat(reqNilai) <= 75)
				return "Cukup";
			else if(parseFloat(reqNilai) <= 90.99)
				return "Baik";
			else
				return "Sangat Baik";
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
				$("#reqNilaiNama"+id).val(setNilaiInfo(reqNilai));
			}
			setHitung();
	  	}

	function btndeletefile(fileid,reqPegawaiId,reqDetilId,reqRowId) {
		if(fileid !== "")
        {
            urlAjax= "json-validasi/personal_json/jsonpegawaiskpdeletefile?reqFileId="+fileid+"&reqPegawaiId="+reqPegawaiId+"&reqDetilId="+reqDetilId+"&reqRowId="+reqRowId;
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

