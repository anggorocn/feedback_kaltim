<?
include_once("functions/personal.func.php");
include_once("functions/string.func.php");

$this->load->model("base-data/InfoData");
$this->load->model("base-data/FormulaPenilaian");
$this->load->model("base-data/Feedback");
$this->load->library('globalmenu');

$userpegawaimode= $this->userpegawaimode;
$adminuserid= $this->adminuserid;
$reqJadwalTesId= $this->input->get("jadwaltesid");

if(!empty($userpegawaimode) && !empty($adminuserid))
    $reqPegawaiId= $userpegawaimode;
else
    $reqPegawaiId= $this->pegawaiId;

$set= new InfoData();
$set->selectbyparamspegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1);
// echo $set->query;exit;
$set->firstRow();
$reqNipBaru= $set->getField('NIP_BARU');
$reqNama= $set->getField('NAMA');
$reqEmail= $set->getField('EMAIL');
$reqAlamat= $set->getField('ALAMAT');
$reqPangkatTerkahir= $set->getField('PANGKAT_KODE')." (".$set->getField('PANGKAT_NAMA').")";
$reqTmtPangkat= getFormattedDateTime($set->getField('LAST_TMT_PANGKAT'), false);
$reqJabatanTerkahir= $set->getField('LAST_JABATAN');
$reqJabatanEselonId= $set->getField('LAST_ESELON_ID');
$reqJabatanEselon= $set->getField('ESELON_NAMA');
$reqJabatanTipePegawai= $set->getField('TIPE_PEGAWAI_NAMA');
$reqTmtJabatan= getFormattedDateTime($set->getField('LAST_TMT_JABATAN'), false);
$reqJenjangNama= $set->getField('JENJANG_NAMA');

$reqMode="update";
// $reqMode="insert";
$readonly = "readonly";

// ------------------
$reqId= $this->input->get("formulaid");

$set= new FormulaPenilaian();
$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
$statementdetil= " AND COALESCE(B.JUMLAH_PEGAWAI,0) = 1";
$sOrder = " ORDER BY ORDER_KUADRAN";
$set->selectpotensikompetensitable(array(), -1, -1, $statement, $statementdetil, $sOrder);
// echo $set->query;exit;
$set->firstRow();
$kodekuadran= $set->getField("KODE_KUADRAN");
// echo $kodekuadran;exit;

$set= new FormulaPenilaian();
$set->selectbyparamsformulapenilaiannineboxstandart();
$set->firstRow();
// echo $set->query;exit;
$reqSkpX0= $set->getField("SKP_X0");
$reqSkpY0= $set->getField("SKP_Y0");
$reqGmX0= $set->getField("GM_X0");
$reqGmY0= $set->getField("GM_Y0");
$reqSkpX1= $set->getField("SKP_X1");
$reqSkpY1= $reqSkpX0+1;
$reqGmX1= $set->getField("GM_X1");
$reqGmY1= $set->getField("GM_Y1");
$reqSkpX2= $set->getField("SKP_X2");
$reqSkpY2= $reqSkpX1+1;
$reqGmX2= $set->getField("GM_X2");
$reqGmY2= $set->getField("GM_Y2");

if($reqSkpY0 == "") $reqSkpY0= 0;
if($reqGmX0 == "") $reqGmX0= 0;
if($reqSkpY1 == "") $reqSkpY1= 0;
if($reqGmX1 == "") $reqGmX1= 0;
if($reqSkpY2 == "") $reqSkpY2= 0;
if($reqGmX2 == "") $reqGmX2= 0;

$set= new FormulaPenilaian();
$getformulapenilaianpendidikan= $set->getformulapenilaianpendidikan($reqId, $reqPegawaiId);

$arrpendidikannilai= [];
$set= new FormulaPenilaian();
$set->selectstr($getformulapenilaianpendidikan);
while($set->nextRow())
{
	$vindikatorpenilaianid= $set->getField("INDIKATOR_PENILAIAN_ID");
	$vsubindikatorid= $set->getField("PENDIDIKAN_ID");

	$arrdata= [];
	$arrdata["key"]= $vindikatorpenilaianid."-".$vsubindikatorid;
	$arrdata["id"]= $vindikatorpenilaianid;
	$arrdata["subindikatorid"]= $vsubindikatorid;
	$arrdata["nilai"]= $set->getField("NILAI");
	array_push($arrpendidikannilai, $arrdata);
}
// print_r($arrpendidikannilai);exit;

$set= new FormulaPenilaian();
$getformulapenilaianjabatan= $set->getformulapenilaianjabatan($reqId, $reqPegawaiId);

$arrjabatanilai= [];
$set= new FormulaPenilaian();
$set->selectstr($getformulapenilaianjabatan);
while($set->nextRow())
{
	$vindikatorpenilaianid= $set->getField("INDIKATOR_PENILAIAN_ID");
	$vsubindikatorid= $set->getField("SUB_INDIKATOR_ID");

	$arrdata= [];
	$arrdata["key"]= $vindikatorpenilaianid."-".$vsubindikatorid;
	$arrdata["id"]= $vindikatorpenilaianid;
	$arrdata["subindikatorid"]= $vsubindikatorid;
	$arrdata["nilai"]= $set->getField("NILAI");
	array_push($arrjabatanilai, $arrdata);
}
// print_r($arrjabatanilai);exit;

$set= new FormulaPenilaian();
$getformulapendidikanpelatihan= $set->getformulapendidikanpelatihan($reqId, $reqPegawaiId);

$arrpendidikanpelatihan= [];
$set= new FormulaPenilaian();
$set->selectstr($getformulapendidikanpelatihan);
while($set->nextRow())
{
	$vindikatorpenilaianid= $set->getField("INDIKATOR_PENILAIAN_ID");
	$vsubindikatorid= $set->getField("SUB_INDIKATOR_ID");

	$arrdata= [];
	$arrdata["key"]= $vindikatorpenilaianid."-".$vsubindikatorid;
	$arrdata["id"]= $vindikatorpenilaianid;
	$arrdata["subindikatorid"]= $vsubindikatorid;
	$arrdata["nilai"]= $set->getField("NILAI");
	array_push($arrpendidikanpelatihan, $arrdata);
}
// print_r($arrpendidikanpelatihan);exit;

$set= new FormulaPenilaian();
$getformulaketerlibatantim= $set->getformulaketerlibatantim($reqId, $reqPegawaiId);

$arrdalamtim= [];
$set= new FormulaPenilaian();
$set->selectstr($getformulaketerlibatantim);
while($set->nextRow())
{
	$vindikatorpenilaianid= $set->getField("INDIKATOR_PENILAIAN_ID");
	$vsubindikatorid= $set->getField("SUB_INDIKATOR_ID");

	$arrdata= [];
	$arrdata["key"]= $vindikatorpenilaianid."-".$vsubindikatorid;
	$arrdata["id"]= $vindikatorpenilaianid;
	$arrdata["subindikatorid"]= $vsubindikatorid;
	$arrdata["nilai"]= $set->getField("NILAI");
	array_push($arrdalamtim, $arrdata);
}
// print_r($arrdalamtim);exit;


$statement= " and a.pegawai_id = ".$reqPegawaiId." and a.formula_penilaian_id = ".$reqId;
$set= new FormulaPenilaian();
$set->selectnilairangkuman(array(), -1,-1, $statement);
$set->firstRow();
// echo $set->query;exit;
$VPOTENSI= $set->getField("VPOTENSI");
$VKOMPETENSI= $set->getField("VKOMPETENSI");
$VPENDIDIKAN_FORMAL= $set->getField("VPENDIDIKAN_FORMAL");
$VPELATIHAN= $set->getField("VPELATIHAN");
$VJABATAN= $set->getField("VJABATAN");
$VKOMITMENORGANISASI= $set->getField("VKOMITMENORGANISASI");
$VPANGKAT= $set->getField("VPANGKAT");
$VSKP= $set->getField("VSKP");

$set= new FormulaPenilaian();
$arrindikatorpenilaian= [];
$set->selectbyindikatorpenilaian();
// echo $set->query;exit;
$headberbeda='';
$headTotal=0;
$jenisheadSebelum='';
while($set->nextRow())
{
	$vjenis= $set->getField("JENIS_SUBINDIKATOR");

	$vjenisnilai= 0;
	if($vjenis == "potensi")
		$vjenisnilai= $VPOTENSI;
	else if($vjenis == "kompetensi")
		$vjenisnilai= $VKOMPETENSI;
	else if($vjenis == "pendidikan_formal")
		$vjenisnilai= $VPENDIDIKAN_FORMAL;
	else if($vjenis == "kinerja")
		$vjenisnilai= $VPELATIHAN;
	else if($vjenis == "penghargaan")
		$vjenisnilai= $VJABATAN;
	else if($vjenis == "riwayat_hukdis")
		$vjenisnilai= $VKOMITMENORGANISASI;

	if($headberbeda==''){
		$headberbeda=$set->getField("jenis");
	}
	else if($headberbeda!=$set->getField("jenis")){
		$headberbeda=$set->getField("jenis");
		$arrdata["id"]= 'xxxx';
		$arrdata["nama"]= 'total';
		$arrdata["jenis"]= 'total';
		$arrdata["jenisnilai"]= $headTotal;
		$arrdata["jenishead"]= $jenisheadSebelum;
		$headTotal=0;
		array_push($arrindikatorpenilaian, $arrdata);
	}

	$arrdata= [];
	$arrdata["id"]= $set->getField("INDIKATOR_PENILAIAN_ID");
	$arrdata["nama"]= $set->getField("NAMA");
	$arrdata["jenis"]= $vjenis;
	$arrdata["jenisnilai"]= $vjenisnilai;
	$arrdata["jenishead"]= $set->getField("jenis");
	$jenisheadSebelum=$set->getField("jenis");
	$headTotal=$headTotal+$vjenisnilai;
	array_push($arrindikatorpenilaian, $arrdata);
}

$headberbeda=$set->getField("jenis");
$arrdata["id"]= 'xxxx';
$arrdata["nama"]= 'total';
$arrdata["jenis"]= 'total';
$arrdata["jenisnilai"]= $headTotal;
$arrdata["jenishead"]= $set->getField("jenis");
array_push($arrindikatorpenilaian, $arrdata);
unset($set);
// print_r($arrindikatorpenilaian);exit;

$statement= " AND A.FORMULA_PENILAIAN_ID = ".$reqId;
$set= new FormulaPenilaian();
$arrnilai= [];
$set->selectbyparamsformulapenilaianbobot(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$vindikatorpenilaianid= $set->getField("INDIKATOR_PENILAIAN_ID");
	$vsubindikatorid= $set->getField("SUB_INDIKATOR_ID");

	$arrdata= [];
	$arrdata["key"]= $vindikatorpenilaianid."-".$vsubindikatorid;
	$arrdata["id"]= $vindikatorpenilaianid;
	$arrdata["subindikatorid"]= $vsubindikatorid;
	$arrdata["nilai"]= $set->getField("NILAI");
	array_push($arrnilai, $arrdata);
}
unset($set);
// print_r($arrnilai);exit;

$arrDataPotensi= [];
$arrDataKompetensi= [];
$statement=" AND B.PEGAWAI_ID = ".$reqPegawaiId;
$arrDataPotensi= array();
$index= 0;
$set= new FormulaPenilaian();
$set->selectspiderpotensikompetensi(array(), -1, -1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$aspekid= $set->getField("ASPEK_ID");

	$arrdata= [];
	$arrdata["NAMA"]= $set->getField("NAMA");
	$arrdata["NILAI"]= $set->getField("NILAI");
	$arrdata["NILAI_STANDAR"]= $set->getField("NILAI_STANDAR");

	if($aspekid == "1")
	{
		$jumlahDataPotensi++;
		array_push($arrDataPotensi, $arrdata);
	}
	else if($aspekid == "2")
	{
		$jumlahDataKompetensi++;
		array_push($arrDataKompetensi, $arrdata);
	}
}
// $jumlahDataPotensi= $index;
// print_r($arrDataPotensi);exit;
// print_r($arrDataKompetensi);exit;

$vfpeg= new globalmenu();
$arrparam= [];
$indikatorpenilaiansub= $vfpeg->indikatorpenilaiansub($arrparam);
// print_r($indikatorpenilaiansub);exit;


// onecheck: awal tambahan rekomendasi
$index_catatan= 0;
$arrPotensiStrength=array();
$set_catatan= new Feedback();
$statement_catatan= " AND A.TIPE = 'profil_kekuatan' AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParamsCatatan(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrPotensiStrength[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $index_catatan++;
}
$jumlahPotensiStrength= $index_catatan;

$index_catatan= 0;
$arrPenilaianPotensiWeaknes=array();
$set_catatan= new Feedback();
$statement_catatan= " AND A.TIPE = 'profil_kelemahan' AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParamsCatatan(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrPenilaianPotensiWeaknes[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $index_catatan++;
}
$jumlahPenilaianPotensiWeaknes= $index_catatan;

$index_catatan= 0;
$arrPenilaianPotensiKesimpulan=array();
$set_catatan= new Feedback();
$statement_catatan= " AND A.TIPE = 'profil_rekomendasi' AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParamsCatatan(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrPenilaianPotensiKesimpulan[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $index_catatan++;
}
$jumlahPenilaianPotensiKesimpulan= $index_catatan;

$index_catatan= 0;
$arrPenilaianPotensiSaranPengembangan=array();
$set_catatan= new Feedback();
$statement_catatan= " AND A.TIPE = 'profil_saran_pengembangan' AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParamsCatatan(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrPenilaianPotensiSaranPengembangan[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $index_catatan++;
}
$jumlahPenilaianPotensiSaranPengembangan= $index_catatan;

$index_catatan= 0;
$arrPenilaianPotensiSaranPenempatan=array();
$set_catatan= new Feedback();
$statement_catatan= " AND A.TIPE = 'profil_saran_penempatan' AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParamsCatatan(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrPenilaianPotensiSaranPenempatan[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $index_catatan++;
}
$jumlahPenilaianPotensiSaranPenempatan= $index_catatan;

$index_catatan= 0;
$arrPenilaianPotensiProfilKompetensi=array();
$set_catatan= new Feedback();
$statement_catatan= " AND A.TIPE = 'profil_kompetensi' AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParamsCatatan(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrPenilaianPotensiProfilKompetensi[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $index_catatan++;
}
$jumlahPenilaianPotensiProfilKompetensi= $index_catatan;


$set= new Feedback();
$set->selectByParamsFeedback(array("A.PEGAWAI_ID"=>$reqPegawaiId,"A.JADWAL_TES_ID"=>$reqJadwalTesId,"A.asesor_id"=>$this->adminuserid), -1,-1);
// echo $set->query;exit;
$set->firstRow();
$reqKeterangan= $set->getField('KETERANGAN');
$reqHarapan= $set->getField('HARAPAN');
$reqHarapanInstansi= $set->getField('HARAPAN_INSTANSI');
$reqSaranPengembangan= $set->getField('SARAN_PENGEMBANGAN');
$reqQuotes= $set->getField('QUOTES');
$reqFeedbackId= $set->getField('FEEDBACK_ID');


$url = 'https://api-simpeg.kaltimbkd.info/pns/semua-data-utama/'.$reqNipBaru.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$dataApi = json_decode(file_get_contents($url), true);
// print_r($dataApi); exit;
?>

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
    .customers {
	  font-family: Arial, Helvetica, sans-serif;
	  border-collapse: collapse;
	  width: 100%;
	}

	.customers td, .customers th {
	  border: 1px solid #ddd;
	  padding: 8px;
	}

	.customers tr:nth-child(even){background-color: #f2f2f2;}

	.customers tr:hover {background-color: #ddd;}

	.customers th {
	  padding-top: 12px;
	  padding-bottom: 12px;
	  text-align: left;
	  background-color: #04AA6D;
	  color: white;
	}

</style>

<div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
	<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<div class="d-flex align-items-center flex-wrap mr-1">
			<div class="d-flex align-items-baseline flex-wrap mr-5">
				<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">Data Pegawai</a>
					</li>
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">Informasi Pegawai</a>
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
                    <h3 class="card-label">Rencana Pengembangan Diri</h3>
                </div>
            </div>
        	<div class="card-body">
        		<div class="row">
        			<div class="col-md-8">
        				<div class="form-group row">
		        			<label class="col-form-label text-right col-lg-4 col-sm-12">NIP</label>
		        			<label class="col-form-label col-lg-8 col-sm-12"><?=$reqNipBaru?></label>
		        			<label class="col-form-label text-right col-lg-4 col-sm-12">Nama</label>
		        			<label class="col-form-label col-lg-8 col-sm-12"><?=$reqNama?></label>
		        			<label class="col-form-label text-right col-lg-4 col-sm-12">Pangkat/Gol</label>
		        			<label class="col-form-label col-lg-8 col-sm-12"><?=$reqPangkatTerkahir?></label>
		        			<label class="col-form-label text-right col-lg-4 col-sm-12">Jabatan</label>
		        			<label class="col-form-label col-lg-8 col-sm-12"><?=$reqJabatanTerkahir?></label>
						<?
						if($reqJabatanEselonId !== "99")
						{
						?>
		        			<label class="col-form-label text-right col-lg-4 col-sm-12">Eselon</label>
		        			<label class="col-form-label col-lg-8 col-sm-12"><?=$reqJabatanEselon?></label>
		        		<?
		        		}
		        		?>
		        			<label class="col-form-label text-right col-lg-4 col-sm-12">Jenis Jabatan</label>
		        			<label class="col-form-label col-lg-8 col-sm-12"><?=$reqJabatanTipePegawai?></label>
		        		<?
		        		if(!empty($reqJenjangNama))
		        		{
		        		?>
		        			<label class="col-form-label text-right col-lg-4 col-sm-12">Jenjang Jabatan</label>
		        			<label class="col-form-label col-lg-8 col-sm-12"><?=$reqJenjangNama?></label>
		        		<?
		        		}
		        		?>
		        			<label class="col-form-label text-right col-lg-4 col-sm-12">Kuadran</label>
		        			<label class="col-form-label col-lg-8 col-sm-12"><?=$kodekuadran?></label>

		        			<label class="col-form-label text-right col-lg-4 col-sm-12">Pdf Laporan Lisan </label>
		        			<label class="col-form-label col-lg-8 col-sm-12">
                        		<button onclick="cetak()" type="button" class="btn btn-warning font-weight-bold mr-2">Cetak Pdf</button>
		        			</label>
		        		</div>
        			</div>
        			<div class="col-md-4">
       					<img id="reqImagePeserta" src="<?=$dataApi['foto_original']?>" style="width: 75%">
        			</div>
        		</div>
        	</div>
        </div>
        <br>

        <div class="card card-custom">
        	<div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="flaticon2-notepad text-primary"></i>
                    </span>
                    <h3 class="card-label">Grafik Kompetensi</h3>
                </div>
            </div>
        	<div class="card-body">
        		<div class="container" style="width: 80%; padding: 0 0;">
					<table style="font-size: 10pt; width: 100%;">
						<tr>
							<!-- <td style="width: 50%; text-align: center;"> -->
							<td style="width: 100%; text-align: center;">
								GRAFIK GAMBARAN KOMPETENSI SAAT INI
								<div id="containerpotensi"></div>
							</td>
						</tr>
					</table>
				</div>
        	</div>
        </div>
        <br>

        <div class="card card-custom">
        	<div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="flaticon2-notepad text-primary"></i>
                    </span>
                    <h3 class="card-label">Grafik Potensi</h3>
                </div>
            </div>
        	<div class="card-body">
        		<br>
        		<div class="container" style="width: 80%; padding: 0 0;">
					<table style="font-size: 10pt; width: 100%;">
						<tr>
							<!-- <td style="width: 50%; text-align: center;"> -->
							<td style="width: 100%; text-align: center;">
								GRAFIK GAMBARAN POTENSI SAAT INI
								<div id="containerkompetensi"></div>
							</td>
						</tr>
					</table>
				</div>
        	</div>
        </div>
        <br>
        <div class="card card-custom">
        	<div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="flaticon2-notepad text-primary">Kesimpulan</i>
                    </span>
                    <h3 class="card-label"></h3>
                </div>
            </div>
        	<div class="card-body">
        		<div class="row">
        			 <table class="customers">
                        <tbody id="tbDataLoop">
                          	<tr>
		                      <th colspan="2">
		                        Kekuatan
		                      </th>
		                    </tr>
		                    <?
                            for($index_catatan=0; $index_catatan<$jumlahPotensiStrength; $index_catatan++)
                            {
                              $reqinfocatatan= $arrPotensiStrength[$index_catatan]["KETERANGAN"];
                            	?>
                              	<tr>
			                      <td><?=$index_catatan+1?></td>
			                      <td><?=$reqinfocatatan?></td>
			                    </tr>
                            	<?
                            }
                            ?>
		                    <tr>
		                      <th colspan="2">
		                        Kelemahan 
		                      </th>
		                    </tr>
		                    <?
                            for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiWeaknes; $index_catatan++)
                          	{
                            	$reqinfocatatan= $arrPenilaianPotensiWeaknes[$index_catatan]["KETERANGAN"];
                            	?>
                              	<tr>
			                      <td><?=$index_catatan+1?></td>
			                      <td><?=$reqinfocatatan?></td>
			                    </tr>
                            	<?
                            }
                            ?>
		                    <tr>
		                      <th colspan="2">
		                        Rekomendasi 
		                      </th>
		                    </tr>
		                    <?
                            for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiKesimpulan; $index_catatan++)
                            {
                              $reqinfocatatan= $arrPenilaianPotensiKesimpulan[$index_catatan]["KETERANGAN"];
                            	?>
                              	<tr>
			                      <td><?=$index_catatan+1?></td>
			                      <td><?=$reqinfocatatan?></td>
			                    </tr>
                            	<?
                            }
                            ?>
		                    <tr>
		                      <th colspan="2">
		                        Saran Pengembangan
		                      </th>
		                    </tr>
		                    <?
                            for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiSaranPengembangan; $index_catatan++)
                            {
                              $reqinfocatatan= $arrPenilaianPotensiSaranPengembangan[$index_catatan]["KETERANGAN"];
                            	?>
                              	<tr>
			                      <td><?=$index_catatan+1?></td>
			                      <td><?=$reqinfocatatan?></td>
			                    </tr>
                            	<?
                            }
                            ?>
		                    <tr>
		                      <th colspan="2">
		                        Saran Penempatan
		                      </th>
		                    </tr>
		                    <?
                            for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiSaranPenempatan; $index_catatan++)
                            {
                              $reqinfocatatan= $arrPenilaianPotensiSaranPenempatan[$index_catatan]["KETERANGAN"];
                            	?>
                              	<tr>
			                      <td><?=$index_catatan+1?></td>
			                      <td><?=$reqinfocatatan?></td>
			                    </tr>
                            	<?
                            }
                            ?>
		                    <tr>
		                      <th colspan="2">
		                        Ringkasan Profil Kompetensi
		                      </th>
		                    </tr>
		                    <?
                            for($index_catatan=0; $index_catatan<$jumlahPenilaianPotensiProfilKompetensi; $index_catatan++)
                            {
                              $reqinfocatatan= $arrPenilaianPotensiProfilKompetensi[$index_catatan]["KETERANGAN"];
                            	?>
                              	<tr>
			                      <td><?=$index_catatan+1?></td>
			                      <td><?=$reqinfocatatan?></td>
			                    </tr>
                            	<?
                            }
                            ?>
                        </tbody>
                    </table>
        		</div>
        	</div>
        </div>
        <br>
        <div class="card card-custom">
        	<div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="flaticon2-notepad text-primary"></i>
                    </span>
                    <h3 class="card-label">Feedback</h3>
                </div>
            </div>

            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
	        	<div class="card-body">
	        		<div class="row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Kesimpulan Feedback </label>
	        			<label class="col-form-label col-lg-10 col-sm-12">
	        				<textarea style="width:100%"  class="form-control" name="reqKeterangan"><?=$reqKeterangan?></textarea>
	        			</label>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Rencana Pengembangan diri </label>
	        			<label class="col-form-label col-lg-10 col-sm-12">
	        				<a onclick="create_tr()" class="btn btn-warning font-weight-bold mr-2">Tambah</a>
	        				<table class="customers">
		                        <thead>
		                          	<tr>
				                      <th style="width:100px">
				                        Urut
				                      </th>
				                      <th>
				                        Nama
				                      </th>
				                      <th style="width:50px">
				                        
				                      </th>
				                    </tr>
				                </thead>
				                <tbody id="PengembanganDiri">
				                	<?
				                	$set->selectByParamsDetilFeedback(array("FEEDBACK_ID"=>$reqFeedbackId), -1,-1);
									// echo $set->query;exit;
									while($set->nextRow())
									{									  
				                	?>
				                	<tr>
								    	<td style="width:100px"  disbled>
								    		<input type="text" class="form-control" name="reqPengembanganDiriUrut[]" value='<?=$set->getField('URUT')?>' oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
								    	</td>
								    	<td>
								    		<textarea class="form-control" name="reqPengembanganDiri[]"><?=$set->getField('Keterangan')?></textarea> 
								    	</td>
								    	<td style="width:5px">
								            <button onclick="remove_tr(this)" type="button" class="btn btn-danger font-weight-bold mr-2"><i class="fa fa-trash"></i></button>
								    	</td>
								    </tr>	
				                	<?
				                	}
				                	?>
				                </tbody>
				            </table>
	        			</label>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Harapan</label>
	        			<label class="col-form-label col-lg-10 col-sm-12">
	        				<textarea style="width:100%"  class="form-control" name="reqHarapan"><?=$reqHarapan?></textarea>
	        			</label>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Harapan terhadap Instansi</label>
	        			<label class="col-form-label col-lg-10 col-sm-12">
	        				<textarea style="width:100%"  class="form-control" name="reqHarapanInstansi"><?=$reqHarapanInstansi?></textarea>
	        			</label>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Saran Pengembangan untuk Organisasi </label>
	        			<label class="col-form-label col-lg-10 col-sm-12">
	        				<textarea style="width:100%"  class="form-control" name="reqSaranPengembangan"><?=$reqSaranPengembangan?></textarea>
	        			</label>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Quotes</label>
	        			<label class="col-form-label col-lg-10 col-sm-12">
	        				<textarea style="width:100%"  class="form-control"  name="reqQuotes"><?=$reqQuotes?></textarea>
	        			</label>
	        		</div>
	        		<div style="width:100%;text-align: center">
	        			<input type="hidden" name='reqPegawaiId' value='<?=$reqPegawaiId?>'>
	        			<input type="hidden" name='reqJadwalTesId' value='<?=$reqJadwalTesId?>'>
	        			<input type="hidden" name='reqFeedbackId' value='<?=$reqFeedbackId?>'>
	        			<input type="hidden" name='reqAsesorId' value='<?=$this->adminuserid?>'>
	        			<button type="submit" id="ktloginformsubmitbutton"  class="btn btn-primary font-weight-bold mr-2">Simpan</button>
		        	</div>
	        	</div>
	        </form>
        </div>
        <br>
    </div>
</div>

<script type="text/javascript">

var vgrafik= "potensikompetensi";
setGrafik("json-data/info_admin_json/formulapenilaiangrafik?pegawaiid=<?=$reqPegawaiId?>&m="+vgrafik);

chartkompetensi = new Highcharts.chart({
	chart: {
		renderTo: 'containerkompetensi',
		polar: true,
		type: 'line'
	},
	
	title: {
		text: '',
		x: -80
	},

	pane: {
		size: '80%'
	},

	xAxis: {
		labels: {
			rotation: 1,
			step: 1
		},
		categories: [
		<?
		for($index_data=0; $index_data < $jumlahDataKompetensi; $index_data++)
		{
			if($index_data > 0)
				echo ",";
		?>
			'<?=$arrDataKompetensi[$index_data]["NAMA"]?>'
		<?
		}
		?>
		],
		tickmarkPlacement: 'on',
		lineWidth: 0
	},

	credits: {
      enabled: false
    },

	yAxis: {
		gridLineInterpolation: 'polygon',
		lineWidth: 0,
		min: 0,
		labels: {
            enabled: false
        }
	},

	tooltip: {
		shared: true,
		pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
	},

	// legend: {
	// 	align: 'right',
	// 	verticalAlign: 'middle',
	// 	layout: 'vertical'
	// },

	series: [
	{
		name: 'Capaian',
		data: [
		<?
		for($index_data=0; $index_data < $jumlahDataKompetensi; $index_data++)
		{
			if($index_data > 0)
				echo ",";
		?>
			<?=$arrDataKompetensi[$index_data]["NILAI"]?>
		<?
		}
		?>
		],
		pointPlacement: 'on',
		color: "#0F0",
		dataLabels: {
			// inside: true,
			enabled: true
			// , style: {
			// 	color: 'white'
			// }
		}
	}
	, 
	{
		name: 'Nilai Standar',
		data: [
		<?
		for($index_data=0; $index_data < $jumlahDataKompetensi; $index_data++)
		{
			if($index_data > 0)
				echo ",";
		?>
			<?=$arrDataKompetensi[$index_data]["NILAI_STANDAR"]?>
		<?
		}
		?>
		],
		pointPlacement: 'on',
		color: "#FF0000",
		dataLabels: {
			// inside: true,
			enabled: true
			// , style: {
			// 	color: 'white'
			// }
		}
	}
	],

	responsive: {
		rules: [{
			condition: {
				maxWidth: 500
			},
			chartOptions: {
				legend: {
					align: 'center',
					verticalAlign: 'bottom',
					layout: 'horizontal'
				},
				pane: {
					size: '70%'
				}
			}
		}]
	}

});

chartpotensi = new Highcharts.chart({
	chart: {
		renderTo: 'containerpotensi',
		polar: true,
		type: 'line'
	},

	title: {
		text: '',
		x: -80
	},

	pane: {
		size: '80%'
	},

	xAxis: {
		labels: {
			rotation: 1,
			step: 1
		},
		categories: [
		<?
		for($index_data=0; $index_data < $jumlahDataPotensi; $index_data++)
		{
			if($index_data > 0)
				echo ",";
		?>
			'<?=$arrDataPotensi[$index_data]["NAMA"]?>'
		<?
		}
		?>
		],
		tickmarkPlacement: 'on',
		lineWidth: 0
	},

	credits: {
      enabled: false
    },

	yAxis: {
		gridLineInterpolation: 'polygon',
		lineWidth: 0,
		min: 0,
		labels: {
            enabled: false
        }
	},

	tooltip: {
		shared: true,
		pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
	},

	// legend: {
	// 	align: 'right',
	// 	verticalAlign: 'middle',
	// 	layout: 'vertical'
	// },

	series: [
	{
		name: 'Capaian',
		data: [
		<?
		for($index_data=0; $index_data < $jumlahDataPotensi; $index_data++)
		{
			if($index_data > 0)
				echo ",";
		?>
			<?=$arrDataPotensi[$index_data]["NILAI"]?>
		<?
		}
		?>
		],
		pointPlacement: 'on',
		color: "#0F0",
		dataLabels: {
			// inside: true,
			enabled: true
			// , style: {
			// 	color: 'white'
			// }
		}
	}
	, 
	{
		name: 'Nilai Standar',
		data: [
		<?
		for($index_data=0; $index_data < $jumlahDataPotensi; $index_data++)
		{
			if($index_data > 0)
				echo ",";
		?>
			<?=$arrDataPotensi[$index_data]["NILAI_STANDAR"]?>
		<?
		}
		?>
		],
		pointPlacement: 'on',
		color: "#FF0000",
		dataLabels: {
			// inside: true,
			enabled: true
			// , style: {
			// 	color: 'white'
			// }
		}
	}
	],

	responsive: {
		rules: [{
			condition: {
				maxWidth: 500
			},
			chartOptions: {
				legend: {
					align: 'center',
					verticalAlign: 'bottom',
					layout: 'horizontal'
				},
				pane: {
					size: '70%'
				}
			}
		}]
	}

});

function setGrafik(link_url)
{
    var s_url= link_url;

    //alert(s_url);return false;
    var request = $.get(s_url);
    request.done(function(dataJson)
    {
        if(dataJson == ''){}
        else
        {
            dataValue= JSON.parse(dataJson);
            // console.log(dataValue);

            if(Array.isArray(dataValue) && dataValue.length)
            {
                nilaix= dataValue[0].x.toFixed(2);
                nilaix= nilaix.replace(".00", "");
                nilaiy= parseFloat(dataValue[0].y);
                // nilaiy= parseFloat(nilaiy) - 7;
                nilaiy= nilaiy.toFixed(2);
                nilaiy= nilaiy.replace(".00", "");
            }

            if(dataValue == null){}
            else
            {
                // console.log("xxx");
                // nilaix= dataValue[0].x.toFixed(2);
                // nilaix= nilaix.replace(".00", "");
                // nilaiy= parseFloat(dataValue[0].y);
                // // nilaiy= parseFloat(nilaiy) - 7;
                // nilaiy= nilaiy.toFixed(2);
                // nilaiy= nilaiy.replace(".00", "");
            }

            var reqSkpY0= reqSkpX0= reqGmY0= reqGmX0=
            reqSkpY1= reqSkpX1= reqGmY1= reqGmX1=
            reqSkpY2= reqSkpX2= reqGmY2= reqGmX2= 0;

            reqSkpY0= parseFloat($("#reqInfoSkpY0").val());
            reqSkpX0= parseFloat($("#reqInfoSkpX0").val());
            reqGmY0= parseFloat($("#reqInfoGmY0").val());
            reqGmX0= parseFloat($("#reqInfoGmX0").val());
            reqSkpY1= parseFloat($("#reqInfoSkpY1").val());
            reqSkpX1= parseFloat($("#reqInfoSkpX1").val());
            reqGmY1= parseFloat($("#reqInfoGmY1").val());
            reqGmX1= parseFloat($("#reqInfoGmX1").val());
            reqSkpY2= parseFloat($("#reqInfoSkpY2").val());
            reqSkpX2= parseFloat($("#reqInfoSkpX2").val());
            reqGmY2= parseFloat($("#reqInfoGmY2").val());
            reqGmX2= parseFloat($("#reqInfoGmX2").val());

            chartkuadran = new Highcharts.Chart({
            chart: {
                    renderTo: 'kontenidgrafik',
                },
                exporting: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
                legend:{
                    enabled:false
                },
                xAxis: {
                    title:{
                         text:'Potensi'
                         , style: {
                            // color: 'white',
                            // fontSize: '15px'
                         }
                    },
                    min: 0,
                    max: reqSkpX2,
                    tickLength:0,
                    minorTickLength:0,
                    gridLineWidth:0,
                    showLastLabel:true,
                    showFirstLabel:false,
                    // lineColor:'#ccc',
                    // lineWidth:1,
                    lineColor:'white',
                    lineWidth:0,
                    bgColor: "#ff0",
                    labels: {
                        style: {
                            // color: 'white',
                            // fontSize: '15px'
                        }
                    },
                },
                yAxis: {
                    title:{
                        text:'Kinerja'
                        , rotation:270
                        , style: {
                            // color: 'white',
                            // fontSize: '15px'
                        }
                    },
                    min: 0,
                    max: reqGmY2,
                    tickLength:3,
                    minorTickLength:0,
                    gridLineWidth:0,
                    // lineColor:'#ccc',
                    // lineWidth:1
                    lineColor:'white',
                    lineWidth:0,
                    labels: {
                        style: {
                            // color: 'white',
                            // fontSize: '15px'
                        }
                    },
                },
                tooltip: {
                    formatter: function() {
                        var s = this.point.myData;
                        return s;
                    }
                },
                title: {
                    text:''
                },
                series: [
                {
                    type: 'line',
                    name: 'SKP Kurang',
                    lineWidth: 0,
                    // borderWidth: 0,
                    data: [[reqSkpX0, reqSkpY0], [reqSkpX0, reqSkpX2]],
                    marker: {
                        enabled: false
                    },
                    states: {
                        hover: {
                            lineWidth: 0
                        }
                    },
                    enableMouseTracking: false
                },
                {
                    type: 'line',
                    name: 'GM Kurang',
                    lineWidth: 0,
                    // borderWidth: 0,
                    data: [[reqGmX0, reqGmY0], [reqGmY2, reqGmY0]],
                    marker: {
                        enabled: false
                    },
                    states: {
                        hover: {
                            lineWidth: 0
                        }
                    },
                    enableMouseTracking: false
                },
                {
                    type: 'line',
                    name: 'SKP Sedang',
                    lineWidth: 0,
                    // borderWidth: 0,
                    data: [[reqSkpX1, reqSkpY1], [reqSkpX1, reqSkpX2]],
                    marker: {
                        enabled: false
                    },
                    states: {
                        hover: {
                            lineWidth: 0
                        }
                    },
                    enableMouseTracking: false
                },
                {
                    type: 'line',
                    name: 'GM Sedang',
                    lineWidth: 0,
                    // borderWidth: 0,
                    data: [[reqGmX1, reqGmY1], [reqGmY2, reqGmY1]],
                    marker: {
                        enabled: false
                    },
                    states: {
                        hover: {
                            lineWidth: 0
                        }
                    },
                    enableMouseTracking: false
                },
                {
                    type: 'line',
                    name: 'SKP Baik',
                    lineWidth: 0,
                    // borderWidth: 0,
                    data: [[reqSkpX2, reqSkpY2], [reqSkpX2, reqSkpX2]],
                    marker: {
                        enabled: false
                    },
                    states: {
                        hover: {
                            lineWidth: 0
                        }
                    },
                    enableMouseTracking: false
                },
                {
                    type: 'line',
                    name: 'GM Baik',
                    lineWidth: 0,
                    // borderWidth: 0,
                    data: [[reqGmX2, reqGmY2], [reqGmY2, reqGmY2]],
                    marker: {
                        enabled: false
                    },
                    states: {
                        hover: {
                            lineWidth: 0
                        }
                    },
                    enableMouseTracking: false
                },
                {
                    type: 'scatter',
                    name: 'Observations',
                    color: 'blue',
                    //data: [[80,80], [40.5,40.5], [60.8,60.8], [53.5,53.5], [63.9,63.9], [90.2,90.2], [95,95]],
                    data: dataValue,
                    marker: {
                        radius: 8
                    }
                }
                ]

                }
            );

        }

    });
}

var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';
jQuery(document).ready(function() {
    var form = KTUtil.getById('ktloginform');
    var formSubmitUrl = "json/feedback_json/add";
    var formSubmitButton = KTUtil.getById('ktloginformsubmitbutton');
    if (!form) {
        return;
    }
    FormValidation
    .formValidation(
        form,
        {
            fields: {
                reqPassword: {
                    validators: {
                        notEmpty: {
                            message: 'Password harus diisi'
                        }
                    }
                },
                reqNamaLogin: {
                    validators: {
                        notEmpty: {
                            message: 'Nama Login harus diisi'
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

                    data= response.message;
                    data= data.split("-");
                    rowid= data[0];
                    infodata= data[1];

                    if(rowid == "xxx")
                    {
                        Swal.fire("Error", infodata, "error");
                    }
                    else
                    {
                        Swal.fire({
                            text: infodata,
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn font-weight-bold btn-light-primary"
                            }
                        }).then(function() {
                            document.location.href = "popup/index/rencana_pengembangan_diri?reqPegawaiHard=<?=$reqPegawaiId?>&jadwaltesid=<?=$reqJadwalTesId?>";
                            // window.location.reload();
                        });
                    }
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
            text: "Check kembali isian pada form",
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

function create_tr(table_id) {
    // let table_body = document.getElementById('PengembanganDiri')

    // tr_clone=;

    // table_body.append(`<tr> <td>xxxxxxxxxxxxx </td></tr>`);

    var scntDiv = document.getElementById('PengembanganDiri')
    infodata= `
    <tr>
    	<td style="width:100px"  disbled>
    		<input type="text" class="form-control" name="reqPengembanganDiriUrut[]" values='' oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
    	</td>
    	<td>
    		<textarea class="form-control" name="reqPengembanganDiri[]"></textarea> 
    	</td>
    	<td style="width:5px">
            <button onclick="remove_tr(this)" type="button" class="btn btn-danger font-weight-bold mr-2"><i class="fa fa-trash"></i></button>
    	</td>
    </tr>	
    `;
    var elm = $(infodata).appendTo(scntDiv); 

}


function remove_tr(This) {
    This.closest('tr').remove();
}

function cetak(argument) {
	pageUrl= "app/loadUrl/main/cetakan_feedback_lisan?formulaid=1&reqPegawaiHard=<?=$reqPegawaiId?>&jadwaltesid=<?=$reqJadwalTesId?>";
    window.open(pageUrl, '_blank').focus();
}
</script>
