<html lang="en">
<head>
	<base href="<?=base_url()?>">
	<meta charset="utf-8" />
	<title>SiMEGILAN | Admin</title>
	<meta name="description" content="User profile block example" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	
</head>
</html>
<?
//Load file content
$this->load->model("base-validasi/PegawaiJabatan");
$this->load->model("base-validasi/Kursus");
$this->load->model("base-validasi/TambahanMasaKerja");
$this->load->model("base-validasi/Kontrak");
$this->load->model("base-validasi/Penghargaan");
$this->load->model("base-validasi/Hukuman");
$this->load->model("base-validasi/Anak");
$this->load->model("base-validasi/SkCpns");
$this->load->model("base-data/SkPns");
$this->load->model("base-data/Pegawai");
$this->load->model("base-validasi/PegawaiPendidikanRiwayat");
$this->load->model("base-validasi/GajiRiwayat");
$this->load->model("base-validasi/PenilaianKerjaPegawai");
$this->load->model("base-validasi/PegawaiDiklat");
$this->load->model("base-validasi/SuamiIstri");
$this->load->model("base-data/PangkatRiwayat");




$reqPegawaiJabatanFileId= $this->input->get("reqPegawaiJabatanFileId");
$reqForm= $this->input->get("reqForm");
$reqFileId= $this->input->get("reqFileId");

$link="LINK_SERVER";
$namafile="NAMA_FILE";
$image="";

if($reqForm== "jabatan")
{
	$set_upload= new PegawaiJabatan();
	$set_upload->selectByParamsUpload(array("A.PEGAWAI_JABATAN_FILE_ID"=>$reqPegawaiJabatanFileId),-1,-1,$statement);
}
elseif($reqForm== "masa_kerja")
{
	$set_upload= new TambahanMasaKerja();
	$set_upload->selectByParamsUpload(array("A.TAMBAHAN_MASA_KERJA_FILE_ID"=>$reqFileId),-1,-1,$statement);
}
elseif($reqForm== "riwayat_kontrak")
{
	$set_upload= new Kontrak();
	$set_upload->selectByParamsUpload(array("A.RIWAYAT_KONTRAK_FILE_ID"=>$reqFileId),-1,-1,$statement);
}
elseif($reqForm== "penghargaan")
{
	$set_upload= new Penghargaan();
	$set_upload->selectByParamsUpload(array("A.PENGHARGAAN_FILE_ID"=>$reqFileId),-1,-1,$statement);
}
elseif($reqForm== "hukuman")
{
	$set_upload= new Hukuman();
	$set_upload->selectByParamsUpload(array("A.HUKUMAN_FILE_ID"=>$reqFileId),-1,-1,$statement);
}
elseif($reqForm== "anak")
{
	$set_upload= new Anak();
	$set_upload->selectByParamsUpload(array("A.ANAK_FILE_ID"=>$reqFileId),-1,-1,$statement);
}
elseif($reqForm== "skcpns")
{
	$set_upload= new SkCpns();
	$set_upload->selectByParamsAdmin(array("K.SK_CPNS_FILE_ID"=>$reqFileId),-1,-1,$statement);
}
elseif($reqForm== "skpns")
{
	$set_upload= new SkPns();
	$set_upload->selectByParamsUpload(array("A.SK_PNS_FILE_ID"=>$reqFileId),-1,-1,$statement);
	$link="LINK_FILE";
}
elseif($reqForm== "sk_pns_berita")
{
	$set_upload= new SkPns();
	$set_upload->selectByParamsUpload(array("A.SK_PNS_FILE_ID"=>$reqFileId),-1,-1,$statement);
	$link="LINK_FILE_BERITA";
}
elseif($reqForm== "sk_pns_surat")
{
	$set_upload= new SkPns();
	$set_upload->selectByParamsUpload(array("A.SK_PNS_FILE_ID"=>$reqFileId),-1,-1,$statement);
	$link="LINK_FILE_SURAT";
}
elseif($reqForm== "sk_pns_spmt")
{
	$set_upload= new SkPns();
	$set_upload->selectByParamsUpload(array("A.SK_PNS_FILE_ID"=>$reqFileId),-1,-1,$statement);
	$link="LINK_FILE_SPMT";
}
elseif($reqForm== "pegawai_foto")
{
	$set_upload= new Pegawai();
	$set_upload->selectByParamsUpload(array("A.PEGAWAI_FILE_ID"=>$reqFileId),-1,-1,$statement);
	$link="LINK_FILE_FOTO";
	$namafile="NAMA_FILE_FOTO";

	$image=1;
}
elseif($reqForm== "pegawai_karpeg")
{
	$set_upload= new Pegawai();
	$set_upload->selectByParamsUpload(array("A.PEGAWAI_FILE_ID"=>$reqFileId),-1,-1,$statement);
	$link="LINK_FILE_KARPEG";
	$namafile="NAMA_FILE_KARPEG";
}
elseif($reqForm== "pegawai_askes")
{
	$set_upload= new Pegawai();
	$set_upload->selectByParamsUpload(array("A.PEGAWAI_FILE_ID"=>$reqFileId),-1,-1,$statement);
	$link="LINK_FILE_ASKES";
	$namafile="NAMA_FILE_ASKES";
}
elseif($reqForm== "pegawai_taspen")
{
	$set_upload= new Pegawai();
	$set_upload->selectByParamsUpload(array("A.PEGAWAI_FILE_ID"=>$reqFileId),-1,-1,$statement);
	$link="LINK_FILE_TASPEN";
	$namafile="NAMA_FILE_TASPEN";
}
elseif($reqForm== "pegawai_npwp")
{
	$set_upload= new Pegawai();
	$set_upload->selectByParamsUpload(array("A.PEGAWAI_FILE_ID"=>$reqFileId),-1,-1,$statement);
	$link="LINK_FILE_NPWP";
	$namafile="NAMA_FILE_NPWP";
}
elseif($reqForm== "pegawai_nik")
{
	$set_upload= new Pegawai();
	$set_upload->selectByParamsUpload(array("A.PEGAWAI_FILE_ID"=>$reqFileId),-1,-1,$statement);
	$link="LINK_FILE_NIK";
	$namafile="NAMA_FILE_NPWP";
}
elseif($reqForm== "pegawai_sk")
{
	$set_upload= new Pegawai();
	$set_upload->selectByParamsUpload(array("A.PEGAWAI_FILE_ID"=>$reqFileId),-1,-1,$statement);
	$link="LINK_FILE_SK";
	$namafile="NAMA_FILE_SK";
}
elseif($reqForm== "pendidikan")
{
	$set_upload= new PegawaiPendidikanRiwayat();
	$set_upload->selectByParamsUpload(array("A.PEGAWAI_PENDIDIKAN_RIWAYAT_FILE_ID"=>$reqFileId),-1,-1,$statement);
	$link="LINK_FILE";
}
elseif($reqForm== "gaji")
{
	$set_upload= new GajiRiwayat();
	$set_upload->selectByParamsUpload(array("A.GAJI_RIWAYAT_FILE_ID"=>$reqFileId),-1,-1,$statement);
	$link="LINK_FILE";
}
elseif($reqForm== "skp")
{
	$set_upload= new PenilaianKerjaPegawai();
	$set_upload->selectByParamsUpload(array("A.PENILAIAN_KERJA_PEGAWAI_FILE_ID"=>$reqFileId),-1,-1,$statement);
	$link="LINK_FILE";
}
elseif($reqForm== "diklat")
{
	$set_upload= new PegawaiDiklat();
	$set_upload->selectByParamsUpload(array("A.PEGAWAI_DIKLAT_FILE_ID"=>$reqFileId),-1,-1,$statement);
	$link="LINK_FILE";
}
elseif($reqForm== "kk")
{
	$set_upload= new SuamiIstri();
	$set_upload->selectByParamsUpload(array("A.SUAMI_ISTRI_FILE_ID"=>$reqFileId),-1,-1,$statement);
	$link="LINK_FILE_KK";
	$namafile="NAMA_FILE_KK";
}
elseif($reqForm== "akta")
{
	$set_upload= new SuamiIstri();
	$set_upload->selectByParamsUpload(array("A.SUAMI_ISTRI_FILE_ID"=>$reqFileId),-1,-1,$statement);
	$link="LINK_FILE_AKTA";
	$namafile="NAMA_FILE_AKTA";
}
elseif($reqForm== "ktp")
{
	$set_upload= new SuamiIstri();
	$set_upload->selectByParamsUpload(array("A.SUAMI_ISTRI_FILE_ID"=>$reqFileId),-1,-1,$statement);
	$link="LINK_FILE_KTP";
	$namafile="NAMA_FILE_KTP";
}
elseif($reqForm== "pangkat_sk")
{
	$set_upload= new PangkatRiwayat();
	$set_upload->selectByParamsUpload(array("A.PANGKAT_RIWAYAT_FILE_ID"=>$reqFileId),-1,-1,$statement);
	$link="LINK_FILE_SK";
	$namafile="NAMA_FILE_SK";
}
elseif($reqForm== "pangkat_stlud")
{
	$set_upload= new PangkatRiwayat();
	$set_upload->selectByParamsUpload(array("A.PANGKAT_RIWAYAT_FILE_ID"=>$reqFileId),-1,-1,$statement);
	$link="LINK_FILE_STLUD";
	$namafile="NAMA_FILE_STLUD";
}
else
{
	$set_upload= new Kursus();
	$set_upload->selectByParamsUpload(array("A.KURSUS_FILE_ID"=>$reqFileId),-1,-1,$statement);

}
// echo $set_upload->query;exit;
$set_upload->firstRow();
$tempLinkFile=$set_upload->getField($link);
$namafile=$set_upload->getField($namafile);
unset($set_upload);

//print_r($tempLinkFile);exit;

if(!empty($tempLinkFile))
{
	$pdf_content = file_get_contents($tempLinkFile );
	if(empty($namafile))
	{
		$namafile = 'document.pdf';
	}
	//Specify that the content has PDF Mime Type
	header("Content-Type: application/pdf");
	header('Content-Disposition: inline; filename="' . $namafile . '"');
	header('Content-Transfer-Encoding: binary');
 	header('Accept-Ranges: bytes'); 
	//Display it
	echo $pdf_content;
}
else
{
	echo "File Tidak Ditemukan";
}


?>

