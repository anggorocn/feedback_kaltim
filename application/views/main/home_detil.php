<!DOCTYPE html>
<html>
<head>
<style>
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}

.button {
  position: relative;
  background-color: #04AA6D;
  border: none;
  font-size: 15px;
  color: #FFFFFF;
  padding: 5px;
  text-align: center;
  transition-duration: 0.4s;
  text-decoration: none;
  overflow: hidden;
  cursor: pointer;
}

.button:after {
  content: "";
  background: #f1f1f1;
  display: block;
  position: absolute;
  padding-top: 300%;
  padding-left: 350%;
  margin-left: -20px !important;
  margin-top: -120%;
  opacity: 0;
  transition: all 0.8s
}

.button:active:after {
  padding: 0;
  margin: 0;
  opacity: 1;
  transition: 0s
}
</style>
<?

$this->load->model("base-data/Feedback");

$tempAsesorId= $this->adminuserid;
$reqTanggalTes= $this->input->get("reqTanggalTes");
$reqTanggalTespecah=explode('-', $reqTanggalTes);
// echo $tempAsesorId;exit;

$set= new Feedback();
$set->selectByParamsIdentitasAsesor(array("USER_APP_ID"=>$this->adminuserid),-1,-1);
$set->firstRow();
$reqPegawaiAdminId= $set->getField('PEGAWAI_ID');

if(strlen($reqTanggalTespecah[0])!=2){
  $reqTanggalTespecah[0]='0'.$reqTanggalTespecah[0];
}

if(strlen($reqTanggalTespecah[1])!=2){
  $reqTanggalTespecah[1]='0'.$reqTanggalTespecah[1];
}
// print_r($reqTanggalTespecah);

$reqTanggalTes= $reqTanggalTespecah[0].'-'.$reqTanggalTespecah[1].'-'.$reqTanggalTespecah[2];

$set= new Feedback();
$set->selectByParams(array(), -1,-1, " AND A.ASESOR_ID = ".$reqPegawaiAdminId);
$set->firstRow();
$tempAsesorNama= $set->getField("NAMA");
unset($set);

$arrAsesor="";

$statement= " AND TO_CHAR(TANGGAL_TES, 'DD-MM-YYYY') = '".$reqTanggalTes."'";
$setJadwal= new Feedback();
$setJadwal->selectByParamsJadwalTes(array(), -1,-1, $statement);
 // echo $setJadwal->query;exit;
$index_loop= 0;
while($setJadwal->nextRow())
{
  $reqJadwalTesId= $setJadwal->getField("JADWAL_TES_ID");
  
  $statement= " AND JA.JADWAL_TES_ID = ".$reqJadwalTesId." ";
  $set= new Feedback();
  
  $set->selectByParamsDataAsesorPegawaiSuper($statement, $reqPegawaiAdminId);
 // echo $set->query;exit;
  
  while($set->nextRow())
  {

    $arrAsesor[$index_loop]["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
    $arrAsesor[$index_loop]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
    $arrAsesor[$index_loop]["ACARA"]= $setJadwal->getField("ACARA");
    $arrAsesor[$index_loop]["NAMA_PEGAWAI"]= $set->getField("NAMA_PEGAWAI");
    $arrAsesor[$index_loop]["NIP_BARU"]= $set->getField("NIP_BARU");
    $arrAsesor[$index_loop]["NOMOR_URUT_GENERATE"]= $set->getField("NOMOR_URUT_GENERATE");
    $arrAsesor[$index_loop]["ESELON"]= $set->getField("last_eselon_id");
    $arrAsesor[$index_loop]["ASESOR_ID"]= $set->getField("asesor_id");

    
    $index_loop++;
    $jumlah_asesor= $index_loop;
  }
  
}

// print_r($arrAsesor);exit;

$reqArrJadwalTesId= "";
$statement= " AND TO_CHAR(TANGGAL_TES, 'DD-MM-YYYY') = '".$reqTanggalTes."'";
$set= new Feedback();
$set->selectByParamsJadwalTes(array(),-1,-1, $statement);
while($set->nextRow())
{
  $separator= "";
  if($reqArrJadwalTesId == ""){}
  else
  $separator= ",";

  $reqArrJadwalTesId.= $separator.$set->getField("JADWAL_TES_ID");
}

?>
</head>
<body>

<h1>Peserta Ujian Tanggal <?=$reqTanggalTes?></h1>

<table id="customers">
  <tr>
    <th>No </th>
    <th>Acara </th>
    <th>No Urut</th>
    <th>Peserta</th>
    <th>Aksi</th>
  </tr>
    <?
  $cekUJian='';
  $cekNIP='';
  $no=1;
  for($checkbox_index=0;$checkbox_index < $jumlah_asesor;$checkbox_index++)
  {
    $reqJadwalTesId= $arrAsesor[$checkbox_index]["JADWAL_TES_ID"];
    $reqPegawaiId= $arrAsesor[$checkbox_index]["PEGAWAI_ID"];
    $reqAsesorId= $arrAsesor[$checkbox_index]["ASESOR_ID"];
    $muncul='';
    
    if($reqMode==''){
      if(($cekUJian!=$reqJadwalTesId||$cekNIP!=$reqPegawaiId) && $reqAsesorId==$reqPegawaiAdminId){
        $muncul=1;
      }
    }
    else{
      if(($cekUJian!=$reqJadwalTesId||$cekNIP!=$reqPegawaiId)){
        $muncul=1;
      }
    }
    if($muncul==1){
      ?>
        <tr>
          <!-- <?=$tempAsesorId?>-<?=$reqAsesorId?> -->
          <td><?=$no?></td>
          <td><?=$arrAsesor[$checkbox_index]["ACARA"]?></td>
          <td><?=$arrAsesor[$checkbox_index]["NOMOR_URUT_GENERATE"]?></td>
          <td><?=$arrAsesor[$checkbox_index]["NAMA_PEGAWAI"]?><br>(<?=$arrAsesor[$checkbox_index]["NIP_BARU"]?>)</td>
          <td><button class="button" onclick="openpopup(<?=$reqPegawaiId?>,<?=$reqJadwalTesId?>)">Lihat Detil</button></td>
      <?
      $no++;;
      $cekUJian=$reqJadwalTesId;
      $cekNIP=$reqPegawaiId;
    }
  }
  ?>
</table>

</body>
</html>

