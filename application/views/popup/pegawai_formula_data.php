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

$index_loop= 0;
$arrPenilaian="";
$set= new Feedback();
$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set->selectByParamsPenilaianAsesor($statement);
// echo $set->query;exit;
while($set->nextRow())
{
  // ambil data lain dari aspek 1
  if($set->getField("ASPEK_ID") == "1")
  {
    $reqLainPenilaianPotensiId= $set->getField("PENILAIAN_ID");
    $reqPenilaianPotensiStrength= $set->getField("CATATAN_STRENGTH");
    $reqPenilaianPotensiWeaknes= $set->getField("CATATAN_WEAKNES");
    $reqPenilaianPotensiKesimpulan= $set->getField("KESIMPULAN");
    $reqPenilaianPotensiSaranPengembangan= $set->getField("SARAN_PENGEMBANGAN");
    $reqPenilaianPotensiSaranPenempatan= $set->getField("SARAN_PENEMPATAN");
    $reqPenilaianPotensiProfilKepribadian= $set->getField("PROFIL_KEPRIBADIAN");
    $reqPenilaianPotensiKesesuaianRumpun= $set->getField("KESESUAIAN_RUMPUN");
    $reqPenilaianPotensiProfilKompetensi= $set->getField("RINGKASAN_PROFIL_KOMPETENSI");
  }

  $arrPenilaian[$index_loop]["PENILAIAN_DETIL_ID"]= $set->getField("PENILAIAN_DETIL_ID");
  $arrPenilaian[$index_loop]["CATATAN_STRENGTH"]= $set->getField("CATATAN_STRENGTH");
  $arrPenilaian[$index_loop]["PROFIL_KEPRIBADIAN"]= $set->getField("PROFIL_KEPRIBADIAN");

  $arrPenilaian[$index_loop]["ATRIBUT_GROUP"]= $set->getField("ATRIBUT_GROUP");
  $arrPenilaian[$index_loop]["ASPEK_ID"]= $set->getField("ASPEK_ID");
  $arrPenilaian[$index_loop]["NAMA"]= $set->getField("NAMA");
  $arrPenilaian[$index_loop]["ATRIBUT_ID"]= $set->getField("ATRIBUT_ID");
  $arrPenilaian[$index_loop]["ATRIBUT_ID_PARENT"]= $set->getField("ATRIBUT_ID_PARENT");
  $arrPenilaian[$index_loop]["NILAI_STANDAR"]= $set->getField("NILAI_STANDAR");
  $arrPenilaian[$index_loop]["NILAI"]= $set->getField("NILAI");
  $arrPenilaian[$index_loop]["GAP"]= $set->getField("GAP");
  $arrPenilaian[$index_loop]["ASESOR_POTENSI_ID"]= $reqAsesorPotensiPegawaiId;

  $arrPenilaian[$index_loop]["CATATAN"]= $set->getField("CATATAN");
  $arrPenilaian[$index_loop]["BUKTI"]= $set->getField("BUKTI");

  $index_loop++;
}
$jumlah_penilaian= $index_loop;

$index_catatan= 0;
$arrNilaiAkhirSaranPengembangan=array();
$set_catatan= new Feedback();
$statement_catatan= " AND A.TIPE = 'area_pengembangan' AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParamsCatatan(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrNilaiAkhirSaranPengembangan[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $index_catatan++;
}
$jumlahNilaiAkhirSaranPengembangan= $index_catatan;

$index_catatan= 0;
$arrUraianPotensi=array();
$set_catatan= new Feedback();
$statement_catatan= " AND A.TIPE = 'uraian_potensi' AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.JADWAL_TES_ID = ".$reqJadwalTesId;
$set_catatan->selectByParamsCatatan(array(), -1,-1, $statement_catatan);
// echo $set_catatan->query;exit;
while($set_catatan->nextRow())
{
  $arrUraianPotensi[$index_catatan]["KETERANGAN"]= $set_catatan->getField("KETERANGAN");
  $index_catatan++;
}
$jumlahUraianPotensi= $index_catatan;

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
                    <h3 class="card-label">Informasi Pegawai</h3>
                </div>
            </div>

            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
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
			        			
			        			<label class="col-form-label text-right col-lg-4 col-sm-12">Pdf Laporan Tertulis </label>
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
	        </form>
        </div>
		<br>
        <div class="card card-custom">
        	<div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="flaticon2-notepad text-primary"></i>
                    </span>
                    <h3 class="card-label">Nilai Akhir</h3>
                </div>
            </div>

            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
	        	<div class="card-body">
	        		<table class="customers">
		        		<tbody>
		        			<tr class="<?=$css?>">
	                            <th colspan="6"><b>Penilaian Kompetensi</b></th>
	                        </tr>
		                    <?
		                    $arrayKey= in_array_column("2", "ASPEK_ID", $arrPenilaian);
		                    
		                    if($arrayKey == ''){}
		                    else
		                    {
		                      	$reqPenilaianKompetensiGroup= "";
		                      	$index_atribut_parent= 0;
		                      	for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
		                      	{
		                        	$index_row= $arrayKey[$index_detil];

			                        $reqPenilaianKompetensiAspekId= $arrPenilaian[$index_row]["ASPEK_ID"];
			                        $reqPenilaianKompetensiAtributNama= $arrPenilaian[$index_row]["NAMA"];
			                        $reqPenilaianKompetensiNilaiStandar= $arrPenilaian[$index_row]["NILAI_STANDAR"];
			                        $reqPenilaianKompetensiAtributId= $arrPenilaian[$index_row]["ATRIBUT_ID"];
			                        $reqPenilaianKompetensiAtributIdParent= $arrPenilaian[$index_row]["ATRIBUT_ID_PARENT"];
			                        $reqPenilaianKompetensiAtributGroup= $arrPenilaian[$index_row]["ATRIBUT_GROUP"];
			                        $reqPenilaianKompetensiDetilId= $arrPenilaian[$index_row]["PENILAIAN_DETIL_ID"];
			                        $reqPenilaianKompetensiNilaiAsli= $arrPenilaian[$index_row]["NILAI"];
			                        $pecahdecimal= explode(".",$reqPenilaianKompetensiNilaiAsli);
			                        $decimalValueNilaiAkhir= $pecahdecimal[1];
			                        $reqPenilaianKompetensiNilai= $pecahdecimal[0];
			                        $reqPenilaianKompetensiGap= $arrPenilaian[$index_row]["GAP"];
			                        if($reqPenilaianKompetensiGap == "" || $reqPenilaianKompetensiGap == "0"){
			                          $reqPenilaianKompetensiGap= 0;
			                        }
			                        else{
			                          $reqPenilaianKompetensiGap= $reqPenilaianKompetensiNilaiAsli-$reqPenilaianKompetensiNilaiStandar;
			                        }

			                        $reqPenilaianKompetensiCatatan= $arrPenilaian[$index_row]["BUKTI"];
			                        $reqPenilaianKompetensiBukti= $arrPenilaian[$index_row]["CATATAN"];

		                        	// kondisi khusus karena salah data
		                        	if($reqPenilaianKompetensiAtributId == "02")
		                          	continue;

			                        if($reqPenilaianKompetensiAtributIdParent == "0")
			                        {
			                            $index_atribut_parent++;
			                            $index_atribut=0;
			                        	?>
			                          	<tr class="<?=$css?>">
			                            	<th colspan="6"><b><?=romanic_number($index_atribut_parent)?>. <?=$reqPenilaianKompetensiAtributNama?></b></th>
			                          	</tr>
			                          	<tr>
				                            <td style="text-align:center; width: 1%">No</td>
				                            <td style="text-align:center;">ATRIBUT & INDIKATOR</td>
				                            <td style="text-align:center; width: 5%">Standar Rating</td>
				                            <td style="text-align:center">Nilai</td>
				                            <td style="text-align:center; width: 5%">Gap</td>
				                            <td style="text-align:center;">Deskripsi</td>
				                         </tr>
			                        	<?
			                        }
			                        else
			                        {
			                          	$arrChecked= "";
			                            $arrChecked= radioPenilaian($reqPenilaianKompetensiNilai);
			                            $index_atribut++;

			                          	if($reqPenilaianKompetensiNilai == "" ){}
			                          	else
			                        	?>
			                          
			                          	<tr>
			                            	<td style="vertical-align: top; text-align:center"><?=$index_atribut?></td>
				                            <td><?=$reqPenilaianKompetensiAtributNama?>.</td>
				                            <td align="center"><?=NolToNone($reqPenilaianKompetensiNilaiStandar)?>&nbsp;</td>
				                            <td align="center"><?=$reqPenilaianKompetensiNilaiAsli?></td>
				                            <td align="center"><?=$reqPenilaianKompetensiGap?></td>
				                            <td><?=$reqPenilaianKompetensiBukti?></td>
				                          </tr>
				                        <?
			                        }
			                    }
		                    }
		                    ?>
		                    <tr>
		                      <th colspan="6">
		                        Area Pengembangan
		                      </th>
		                    </tr>
	                        <?
	                        for($index_catatan=0; $index_catatan<$jumlahNilaiAkhirSaranPengembangan; $index_catatan++)
	                        {
	                          	$reqinfocatatan= $arrNilaiAkhirSaranPengembangan[$index_catatan]["KETERANGAN"];
	                        	?>
		                        <tr>
			                      <td ></td>
			                      <td colspan="5"><?=$reqinfocatatan?></td>
			                  	</tr>
			                  	<?
			                }?>
		                  	<tr>
		                      <th colspan="6">
	  	                        Uraian Kompetesi
		                      </th>
		                    </tr>
	                        <?
	                        for($index_catatan=0; $index_catatan<$jumlahUraianPotensi; $index_catatan++)
	                        {
	                        	$reqinfocatatan= $arrUraianPotensi[$index_catatan]["KETERANGAN"];
		                        ?>
		                        <tr>
			                      <td ></td>
			                      <td colspan="5"><?=$reqinfocatatan?></td>
			                  	</tr>
			                  	<?
			                }
			                ?>
	                  	</tbody>
	              	</table>
	        	</div>
	        </form>
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

            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
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
	        </form>
        </div>
    </div>
</div>

<script type="text/javascript">
	function cetak(argument) {
		pageUrl= "app/loadUrl/main/cetakan_feedback_tertulis?formulaid=1&reqPegawaiHard=<?=$reqPegawaiId?>&jadwaltesid=<?=$reqJadwalTesId?>";
        window.open(pageUrl, '_blank').focus();
	}
</script>