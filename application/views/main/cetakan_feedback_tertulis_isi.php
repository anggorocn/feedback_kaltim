<?
$this->load->model("base-data/Feedback");
$this->load->model("base-data/InfoData");

$reqPegawaiId= $this->input->get("reqPegawaiHard");
$reqJadwalTesId= $this->input->get("jadwaltesid");
$adminuserid= $this->input->get("adminuserid");

$baseUrl="http://192.168.88.100/feedback_kaltim/";

$set= new InfoData();
$set->selectbyparamspegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1);
// echo $set->query;exit;
$set->firstRow();
$reqNipBaru= $set->getField('NIP_BARU');
$reqNama= $set->getField('NAMA');
$reqJabatanTerkahir= $set->getField('LAST_JABATAN');
$reqJabatanEselon= $set->getField('ESELON_NAMA');

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

$set= new Feedback();
$set->selectByParamsFeedback(array("A.PEGAWAI_ID"=>$reqPegawaiId,"A.JADWAL_TES_ID"=>$reqJadwalTesId,"A.asesor_id"=>$adminuserid	), -1,-1);
// echo $set->query;exit;
$set->firstRow();
$reqKeterangan= $set->getField('KETERANGAN');
$reqHarapan= $set->getField('HARAPAN');
$reqHarapanInstansi= $set->getField('HARAPAN_INSTANSI');
$reqSaranPengembangan= $set->getField('SARAN_PENGEMBANGAN');
$reqQuotes= $set->getField('QUOTES');
$reqFeedbackId= $set->getField('FEEDBACK_ID');

$set= new Feedback();
$set->selectByParamsJadwal(array("A.JADWAL_TES_ID"=>$reqJadwalTesId), -1,-1);
// echo $set->query;exit;
$set->firstRow();
$tempAcara= $set->getField('ACARA');
$tempNipPimpinan= $set->getField('NIP_PIMPINAN');
$tempTanggalTes= explode(" ",$set->getField('TANGGAL_TES'));
$tempTanggalTes= explode('-', $tempTanggalTes[0]);
$tempTanggalTes= $tempTanggalTes[2]." ".getNameMonth($tempTanggalTes[1])." ".$tempTanggalTes[0];

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="../WEB/css/cetaknew.css" type="text/css">
</head>
<body>
	<div class="container">

		<table>
			<tr>
				<td style="width:13%"></td>
				<td>
					<img src="<?=$baseUrl?>images/logo_kaltim.png" style="width: 70px;">				
				</td>
				<td style="text-align:center; font-size: 20px;">
					UPTD PENILAIAN KOMPETENSI  PEGAWAI <br> BADAN KEPEGAWAIAN DAERAH<br> KALIMANTAN TIMUR
				</td>
				<td style="width:13%">
					<table style="width:100%;border: 2x solid red;margin-top: 50px;">
						<tr>
							<td style="text-align: center;color: red;">RAHASIA</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<div style="text-align: center; width: 100%">
			<p style="padding: 1px;border-bottom: 2px solid;border-top: 1px solid"></p>
			<h2 ><strong>LAPORAN UMPAN BALIK</strong></h2>
		</div>
		<br>
		<table style="background-color: white; width: 100%;">
			<tr>
				<td style="width:20%">Nomor</td>
				<td style="width:20px">:</td>
				<!-- <td><?=$tempPesertaNomorUrut.'/UPTD/'.$tempBulanRomawi.'/'.$tempTahun?></td> -->
			</tr>
			<tr>
				<td>Nama Assessee</td>
				<td style="width:20px">:</td>
				<td><?=$reqNama?></td>
			</tr>
			<tr>
				<td>NIP</td>
				<td style="width:20px">:</td>
				<td><?=$reqNipBaru?></td>
			</tr>
			<tr>
				<td>Jabatan</td>
				<td style="width:20px">:</td>
				<td><?=$reqJabatanTerkahir?></td>
			</tr> 
			<tr>
				<td>Instansi</td>
				<td style="width:20px">:</td>
				<td><?=$reqJabatanEselon?></td>
			</tr>
			<tr>
				<td>Kegiatan</td>
				<td style="width:20px">:</td>
				<td>Assessment <?=$tempAcara?></td>
			</tr>
			
		</table>
		<br>
		
		<table style="width: 100%;  border-collapse: collapse;">
          	<tr>
              <th style="width:50%;background-color:lightgray ; border:0.5px solid black ;" colspan="2"> KEKUATAN</th>
              <th style="width:50%;background-color:lightgray ; border:0.5px solid black;" colspan="2"> KELEMAHAN</th>
            </tr>
            <?
            $totalArr=$jumlahPotensiStrength;
            if($jumlahPotensiStrength<$jumlahPenilaianPotensiWeaknes){
            	$totalArr=$jumlahPenilaianPotensiWeaknes;
            }
            for($index_catatan=0; $index_catatan<$totalArr; $index_catatan++)
            {
              $reqinfocatatan1= $arrPotensiStrength[$index_catatan]["KETERANGAN"];
              $reqinfocatatan2= $arrPenilaianPotensiWeaknes[$index_catatan]["KETERANGAN"];
            	?>
              	<tr>
                  <td style="width:5%;text-align:Center;vertical-align: top; border:0.5px solid black;"><?=$index_catatan+1?></td>
                  <td style="width:45%;text-align:justify;vertical-align: top; border:0.5px solid black;padding-left: 5px;"><?=$reqinfocatatan1?></td>
                  <td style="width:5%;text-align:Center;vertical-align: top; border:0.5px solid black;"><?=$index_catatan+1?></td>
                  <td style="width:45%;text-align:justify;vertical-align: top; border:0.5px solid black;padding-left: 5px;"><?=$reqinfocatatan2?></td>
                </tr>
            	<?
            }
            ?>
            <tr>
              <th colspan="4" style="background-color:lightgray ; border:0.5px solid black;"> SARAN PENGEMBANGAN UNTUK ASSESSEE</th>
            </tr>
            <tr>
              <td colspan="4" style="text-align:justify;vertical-align: top; border:0.5px solid black;padding-left: 5px;"><?=$reqHarapan?></th>
            </tr>

            <tr>
              <th colspan="4" style="background-color:lightgray ; border:0.5px solid black;">SARAN PENGEMBANGAN UNTUK ORGANISASI</th>
            </tr>
            <tr>
              <td colspan="4" style="text-align:justify;vertical-align: top; border:0.5px solid black;padding-left: 5px;"><?=$reqHarapanInstansi?></th>
            </tr>
            
            <tr>
              <th colspan="4" style="background-color:lightgray ; border:0.5px solid black;">RENCANA PENGEMBANGAN DIRI</th>
            </tr>
            <?
			$set->selectByParamsDetilFeedback(array("FEEDBACK_ID"=>$reqFeedbackId), -1,-1);
			$i=1;
			while($set->nextRow()){
            ?>
	            <tr>
	              <td style="text-align:center;vertical-align: top; border:0.5px solid black;"><?=$i?></th>
	              <td colspan="4" style="text-align:justify;vertical-align: top; border:0.5px solid black;padding-left: 5px;"><?=$set->getField('Keterangan')?></th>
	            </tr>
	        <?
	        $i++;}?>
        </table>
        <br>
        <table style="width:100%">
        	<tr>
        		<td style="width:50%">Samarinda, <?=$tempTanggalTes?> </td>
        	</tr>
        	<tr>
        		<td style="width:50%">Pemberi Umpan Balik</td>
        	</tr>
        	<tr>
        		<td style="width:50%"><br></td>
        	</tr>
        	<tr>
        		<td style="width:50%"><br></td>
        	</tr>
        	<tr>
        		<td style="width:50%">ADMINISTRATOR KEGIATAN</td>
        	</tr>
        	<tr>
        		<td style="width:50%">NIP. <?=$tempNipPimpinan?></td>
        	</tr>
        </table>
	</div>
</body>
</html>