<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/class-list-util.php");
include_once("functions/class-list-util-serverside.php");
include_once("functions/excel_reader2.php");


class upload_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		//kauth
		if($this->session->userdata("adminuserid") == "")
		{
			redirect('adminlogin');
		}
		
		$this->adminuserid= $this->session->userdata("adminuserid");
		$this->adminusernama= $this->session->userdata("adminusernama");
		$this->adminuseraksesappmenu= $this->session->userdata("adminuseraksesappmenu");
	}

	function uploadmulti()
	{
		// ini_set('display_errors', 1);
		$this->load->model("base-app/UploadFile");
		$this->load->model("base-data/SuamiIstri");
		$this->load->model("base-data/SkCpns");
		$this->load->model("base-data/SkPns");
		$this->load->model("base-data/PegawaiJabatan");
		$this->load->model("base-data/Anak");
		$this->load->model("base-data/PangkatRiwayat");
		$this->load->model("base-data/TambahanMasaKerja");
		$this->load->model("base-data/PegawaiPendidikanRiwayat");
		$this->load->model("base-data/PegawaiDiklat");
		$this->load->model("base-data/PenilaianKerjaPegawai");

		$server = $this->config->item('server_upload');
		$set = new UploadFile();
		$check_peg = new UploadFile();

		$errors     = array();
		$maxsize    = 2097152;
		$acceptable = array(
			'application/pdf'
		);

		$fileName = $_FILES['file']['name'];
		$fileSize = $_FILES['file']['size'];
		$tmpName = $_FILES['file']['tmp_name'];

		$extcheck = str_replace(".pdf", "", $fileName);
		$checknip = explode('_', $extcheck );
		$nipfile=$checknip[1];
		$nipfilecheck=$checknip[2];

		$statement=" AND A.NIP_BARU='".$nipfile."' OR A.NIP_BARU='".$nipfilecheck."' ";
		$check_peg->selectByParamsCheckPegawai(array(),-1,-1,$statement);
		$check_peg->firstRow();
		$nip=$check_peg->getField("NIP_BARU");
		$pegawaiId=$check_peg->getField("PEGAWAI_ID");

		if(!empty($nip))
		{
			$acceptname = array(
				'SK_CPNS_'.$nip.'.pdf',
				'SK_PNS_'.$nip.'.pdf',
				'KK_'.$nip.'.pdf',
				'AKTA_NIKAH_'.$nip.'.pdf',
				'AKTA_ANAK_'.$nip.'.pdf',
				'SK_JABATAN_'.$nip.'.pdf',
				'SK_KP_'.$nip.'.pdf',
				'PMK_'.$nip.'.pdf',
				'IJAZAH_'.$nip.'.pdf',
				'SK_PIM_'.$nip.'.pdf',
				'SKP_'.$nip.'.pdf'
			);

			if((!in_array($fileName, $acceptname))) {
				header("HTTP/1.0 400 Bad Request");
				$errors = 'Pastikan nama file '.$fileName.' sesuai dengan format yang ada';
				echo $errors;exit;
			}
			else
			{
				$check_peg = new UploadFile();
				$check_folder = new UploadFile();
				$check = new UploadFile();
				$target_file= "";
				$ext = "pdf";
				if($fileName == $acceptname[0])
				{
					$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
					$check_peg->selectByParamsCheckCpnsFile(array(),-1,-1,$statement);
					$check_peg->firstRow();
					$reqFileId=$check_peg->getField("SK_CPNS_FILE_ID");
					$reqId=$check_peg->getField("SK_CPNS_ID");
					$reqLinkFile=$check_peg->getField("LINK_FILE");
					$statement=" AND A.LINK_SERVER_ID= 8 ";
					$check_folder->selectByParamsCheckFolder(array(),-1,-1,$statement);
					$check_folder->firstRow();
					$reqFolder=$check_folder->getField("FOLDER");

					if(!empty($reqFileId))
					{
						if(!empty($reqLinkFile))
						{
							$target_file = $reqLinkFile;
						}
						else
						{
							$urlupload= $server.$nip.$reqFolder.$reqId."\\";
							$target_file = $urlupload.$fileName ;
						}
						$set_upload= new SkCpns();
						$set_upload->setField("LAST_UPDATE_USER", $this->adminusernama);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", "");
						$set_upload->setField('SK_CPNS_FILE_ID', $reqFileId);
						$set_upload->setField("LINK_FILE", $target_file);
						$set_upload->setField("LINK_SERVER", $target_file);
						$set_upload->setField("NAMA_FILE", $fileName);
						$set_upload->setField('SK_CPNS_ID', $reqId);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $pegawaiId);
						$reqMode="update";
					}
					else
					{
						$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
						$check->selectByParamsCheckCpns(array(),-1,-1,$statement);
						$check->firstRow();
						$reqId=$check->getField("SK_CPNS_ID");

						$urlupload= $server.$nip.$reqFolder.$reqId."\\";
						$target_file = $urlupload.$fileName ;

						if (!is_dir($urlupload)) {
							makedirs($urlupload);
						}

						if(!empty($reqId))
						{
							$set_upload= new SkCpns();
							$set_upload->setField("LAST_CREATE_USER", $this->adminusernama);
							$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
							$set_upload->setField("LAST_CREATE_SATKER", "");
							$set_upload->setField("LINK_FILE", $target_file);
							$set_upload->setField("LINK_SERVER", $target_file);
							$set_upload->setField("NAMA_FILE", $fileName);
							$set_upload->setField('SK_CPNS_ID', $reqId);
							$set_upload->setField("TIPE_FILE", $ext);
							$set_upload->setField('PEGAWAI_ID', $pegawaiId);
							$reqMode="insert";
						}
						else 
						{
							header("HTTP/1.0 400 Bad Request");
							echo "Data Sk Cpns tidak ditemukan";exit;
						}
					}

					if (move_uploaded_file($tmpName, $target_file)) 
					{
						if($reqMode=="insert")
						{
							if($set_upload->insertupload())
							{
								echo "File ".$fileName." berhasil di unggah";
							}
						}
						else
						{
							if($set_upload->updateupload())
							{
								echo "File ".$fileName." berhasil di unggah";
							}
						}
					} 
					else 
					{
						header("HTTP/1.0 400 Bad Request");
						echo "Cek kembali file anda";
					}
				}
				elseif ($fileName == $acceptname[1]) {
					$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
					$check_peg->selectByParamsCheckPnsFile(array(),-1,-1,$statement);
					$check_peg->firstRow();
					$reqFileId=$check_peg->getField("SK_PNS_FILE_ID");
					$reqId=$check_peg->getField("SK_PNS_ID");
					$reqLinkFile=$check_peg->getField("LINK_FILE");
					$statement=" AND A.LINK_SERVER_ID= 9 ";
					$check_folder->selectByParamsCheckFolder(array(),-1,-1,$statement);
					$check_folder->firstRow();
					$reqFolder=$check_folder->getField("FOLDER");

					if(!empty($reqFileId))
					{
						if(!empty($reqLinkFile))
						{
							$target_file = $reqLinkFile;
						}
						else
						{
							$urlupload= $server.$nip.$reqFolder.$reqId."\\";
							$target_file = $urlupload.$fileName ;
						}
						$set_upload= new SkPns();
						$set_upload->setField("LAST_UPDATE_USER", $this->adminusernama);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", "");
						$set_upload->setField('SK_PNS_FILE_ID', $reqFileId);
						$set_upload->setField("LINK_FILE", $target_file);
						$set_upload->setField("LINK_SERVER", $target_file);
						$set_upload->setField("NAMA_FILE", $fileName);
						$set_upload->setField('SK_PNS_ID', $reqId);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $pegawaiId);
						$reqMode="update";
					}
					else
					{
						$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
						$check->selectByParamsCheckPns(array(),-1,-1,$statement);
						$check->firstRow();
						$reqId=$check->getField("SK_PNS_ID");

						$urlupload= $server.$nip.$reqFolder.$reqId."\\";
						$target_file = $urlupload.$fileName ;

						if (!is_dir($urlupload)) {
							makedirs($urlupload);
						}

						if(!empty($reqId))
						{
							$set_upload= new SkPns();
							$set_upload->setField("LAST_CREATE_USER", $this->adminusernama);
							$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
							$set_upload->setField("LAST_CREATE_SATKER", "");
							$set_upload->setField("LINK_FILE", $target_file);
							$set_upload->setField("LINK_SERVER", $target_file);
							$set_upload->setField("NAMA_FILE", $fileName);
							$set_upload->setField('SK_PNS_ID', $reqId);
							$set_upload->setField("TIPE_FILE", $ext);
							$set_upload->setField('PEGAWAI_ID', $pegawaiId);
							$reqMode="insert";
						}
						else 
						{
							header("HTTP/1.0 400 Bad Request");
							echo "Data Sk Pns tidak ditemukan";exit;
						}
					}

					if (move_uploaded_file($tmpName, $target_file)) 
					{
						if($reqMode=="insert")
						{
							if($set_upload->insertupload())
							{
								echo "File ".$fileName." berhasil di unggah";
							}
						}
						else
						{
							if($set_upload->updateupload())
							{
								echo "File ".$fileName." berhasil di unggah";
							}
						}
					} 
					else 
					{
						header("HTTP/1.0 400 Bad Request");
						echo "Cek kembali file anda";
					}
				}
				elseif ($fileName == $acceptname[2]) {
					$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
					$check_peg->selectByParamsCheckSuamiIstriFile(array(),-1,-1,$statement);
					$check_peg->firstRow();
					$reqFileId=$check_peg->getField("SUAMI_ISTRI_FILE_ID");
					$reqId=$check_peg->getField("SUAMI_ISTRI_ID");
					$reqLinkFile=$check_peg->getField("LINK_FILE_KK");
					$statement=" AND A.LINK_SERVER_ID=15 ";
					$check_folder->selectByParamsCheckFolder(array(),-1,-1,$statement);
					$check_folder->firstRow();
					$reqFolder=$check_folder->getField("FOLDER");
					if(!empty($reqFileId))
					{
						if(!empty($reqLinkFile))
						{
							$target_file = $reqLinkFile;
						}
						else
						{
							$urlupload= $server.$nip.$reqFolder.$reqId."\\";
							$target_file = $urlupload.$fileName ;
						}
						$set_upload= new SuamiIstri();
						$set_upload->setField("LAST_UPDATE_USER", $this->adminusernama);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", "");
						$set_upload->setField('SUAMI_ISTRI_FILE_ID', $reqFileId);
						$set_upload->setField("LINK_FILE_KK", $target_file);
						$set_upload->setField("NAMA_FILE_KK", $fileName);
						$set_upload->setField('SUAMI_ISTRI_ID', $reqId);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $pegawaiId);
						$reqMode="update";
					}
					else
					{
						$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
						$check->selectByParamsCheckSuamiIstri(array(),-1,-1,$statement);
						$check->firstRow();
						$reqId=$check->getField("SUAMI_ISTRI_ID");

						$urlupload= $server.$nip.$reqFolder.$reqId."\\";
						$target_file = $urlupload.$fileName ;

						if (!is_dir($urlupload)) {
							makedirs($urlupload);
						}

						if(!empty($reqId))
						{
							$set_upload= new SuamiIstri();
							$set_upload->setField("LAST_CREATE_USER", $this->adminusernama);
							$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
							$set_upload->setField("LAST_CREATE_SATKER", "");
							$set_upload->setField("LINK_FILE_KK", $target_file);
							$set_upload->setField("NAMA_FILE_KK", $fileName);
							$set_upload->setField('SUAMI_ISTRI_ID', $reqId);
							$set_upload->setField("TIPE_FILE", $ext);
							$set_upload->setField('PEGAWAI_ID', $pegawaiId);
							$reqMode="insert";

						}
						else 
						{
							header("HTTP/1.0 400 Bad Request");
							echo "Data Suami/Istri tidak ditemukan";exit;
						}
					}

					if (move_uploaded_file($tmpName, $target_file)) 
					{
						if($reqMode=="insert")
						{
							if($set_upload->insertupload())
							{
								echo "File ".$fileName." berhasil di unggah";
							}
						}
						else
						{
							$nama="NAMA_FILE_KK";
							$link="LINK_FILE_KK";
							if($set_upload->updateupload($nama,$link))
							{
								echo "File ".$fileName." berhasil di unggah";
							}
						}
					} 
					else 
					{
						header("HTTP/1.0 400 Bad Request");
						echo "Cek kembali file anda";
					}
				}
				elseif ($fileName == $acceptname[3]) {
					$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
					$check_peg->selectByParamsCheckSuamiIstriFile(array(),-1,-1,$statement);
					// echo $check_peg->query;exit;
					$check_peg->firstRow();
					$reqFileId=$check_peg->getField("SUAMI_ISTRI_FILE_ID");
					$reqId=$check_peg->getField("SUAMI_ISTRI_ID");
					$reqLinkFile=$check_peg->getField("LINK_FILE_AKTA");
					$reqNamaFile=$check_peg->getField("NAMA_FILE_AKTA");
					$statement=" AND A.LINK_SERVER_ID=15 ";
					$check_folder->selectByParamsCheckFolder(array(),-1,-1,$statement);
					$check_folder->firstRow();
					$reqFolder=$check_folder->getField("FOLDER");
					if(!empty($reqFileId))
					{
						if(!empty($reqLinkFile))
						{
							$target_file = $reqLinkFile;
						}
						else
						{
							$urlupload= $server.$nip.$reqFolder.$reqId."\\";
							$renamefileAkta = "AKTA_".$nip.".".$ext;
							$target_file = $urlupload.$renamefileAkta ;
						}
						$set_upload= new SuamiIstri();
						$set_upload->setField("LAST_UPDATE_USER", $this->adminusernama);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", "");
						$set_upload->setField('SUAMI_ISTRI_FILE_ID', $reqFileId);
						$set_upload->setField("LINK_FILE_AKTA", $target_file);
						$set_upload->setField("NAMA_FILE_AKTA", $renamefileAkta);
						$set_upload->setField('SUAMI_ISTRI_ID', $reqId);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $pegawaiId);
						$reqMode="update";
					}
					else
					{
						$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
						$check->selectByParamsCheckSuamiIstri(array(),-1,-1,$statement);
						$check->firstRow();
						$reqId=$check->getField("SUAMI_ISTRI_ID");

						$urlupload= $server.$nip.$reqFolder.$reqId."\\";
						$renamefileAkta = "AKTA_".$nip.".".$ext;

						$target_file = $urlupload.$renamefileAkta ;


						if (!is_dir($urlupload)) {
							makedirs($urlupload);
						}

						if(!empty($reqId))
						{
							$set_upload= new SuamiIstri();
							$set_upload->setField("LAST_CREATE_USER", $this->adminusernama);
							$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
							$set_upload->setField("LAST_CREATE_SATKER", "");
							$set_upload->setField("LINK_FILE_AKTA", $target_file);
							$set_upload->setField("NAMA_FILE_AKTA", $renamefileAkta);
							$set_upload->setField('SUAMI_ISTRI_ID', $reqId);
							$set_upload->setField("TIPE_FILE", $ext);
							$set_upload->setField('PEGAWAI_ID', $pegawaiId);
							$reqMode="insert";

						}
						else 
						{
							header("HTTP/1.0 400 Bad Request");
							echo "Data Suami/Istri tidak ditemukan";exit;
						}
					}


					if (move_uploaded_file($tmpName, $target_file)) 
					{
						if($reqMode=="insert")
						{
							if($set_upload->insertupload())
							{
								echo "File ".$fileName." berhasil di unggah";
							}
						}
						else
						{
							$nama="NAMA_FILE_AKTA";
							$link="LINK_FILE_AKTA";
							if($set_upload->updateupload($nama,$link))
							{
								echo "File ".$fileName." berhasil di unggah";
							}
						}
					} 
					else 
					{
						header("HTTP/1.0 400 Bad Request");
						echo "Cek kembali file anda";
					}
				}
				elseif ($fileName == $acceptname[4]) {
					$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
					$check_peg->selectByParamsCheckAnakFile(array(),-1,-1,$statement);
					// echo $check_peg->query;exit;
					$check_peg->firstRow();
					$reqFileId=$check_peg->getField("ANAK_FILE_ID");
					$reqId=$check_peg->getField("ANAK_ID");
					$reqLinkFile=$check_peg->getField("LINK_FILE");
					$reqNamaFile=$check_peg->getField("NAMA_FILE");
					$statement=" AND A.LINK_SERVER_ID=7";

					$check_folder->selectByParamsCheckFolder(array(),-1,-1,$statement);
					$check_folder->firstRow();
					$reqFolder=$check_folder->getField("FOLDER");
					if(!empty($reqFileId))
					{
						if(!empty($reqLinkFile))
						{
							$target_file = $reqLinkFile;
						}
						else
						{
							$urlupload= $server.$nip.$reqFolder.$reqId."\\";
							$renamefile = "AKTA_".$nip.".".$ext;
							$target_file = $urlupload.$renamefile ;
						}
						$set_upload= new Anak();
						$set_upload->setField("LAST_UPDATE_USER", $this->adminusernama);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", "");
						$set_upload->setField('ANAK_FILE_ID', $reqFileId);
						$set_upload->setField("LINK_FILE", $target_file);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField('ANAK_ID', $reqId);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $pegawaiId);
						$reqMode="update";
					}
					else
					{
						$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
						$check->selectByParamsCheckAnak(array(),-1,-1,$statement);
						$check->firstRow();
						$reqId=$check->getField("ANAK_ID");

						$urlupload= $server.$nip.$reqFolder.$reqId."\\";
						$renamefile = "AKTA_".$nip.".".$ext;

						$target_file = $urlupload.$renamefile ;


						if (!is_dir($urlupload)) {
							makedirs($urlupload);
						}

						if(!empty($reqId))
						{
							$set_upload= new Anak();
							$set_upload->setField("LAST_CREATE_USER", $this->adminusernama);
							$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
							$set_upload->setField("LAST_CREATE_SATKER", "");
							$set_upload->setField("LINK_FILE", $target_file);
							$set_upload->setField("NAMA_FILE", $renamefile);
							$set_upload->setField('ANAK_ID', $reqId);
							$set_upload->setField("TIPE_FILE", $ext);
							$set_upload->setField('PEGAWAI_ID', $pegawaiId);
							$reqMode="insert";

						}
						else 
						{
							header("HTTP/1.0 400 Bad Request");
							echo "Data Anak tidak ditemukan";exit;
						}
					}


					if (move_uploaded_file($tmpName, $target_file)) 
					{
						if($reqMode=="insert")
						{
							if($set_upload->insertupload())
							{
								echo "File ".$fileName." berhasil di unggah";
							}
						}
						else
						{
							if($set_upload->updateupload())
							{
								echo "File ".$fileName." berhasil di unggah";
							}
						}
					} 
					else 
					{
						header("HTTP/1.0 400 Bad Request");
						echo "Cek kembali file anda";
					}
				}
				elseif ($fileName == $acceptname[5]) {
					$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
					$check_peg->selectByParamsCheckJabatanFile(array(),-1,-1,$statement);
					$check_peg->firstRow();
					$reqFileId=$check_peg->getField("PEGAWAI_JABATAN_FILE_ID");
					$reqId=$check_peg->getField("PEGAWAI_JABATAN_ID");
					$reqLinkFile=$check_peg->getField("LINK_FILE");
					$statement=" AND A.LINK_SERVER_ID=1 ";
					$check_folder->selectByParamsCheckFolder(array(),-1,-1,$statement);
					$check_folder->firstRow();
					$reqFolder=$check_folder->getField("FOLDER");
					if(!empty($reqFileId))
					{
						if(!empty($reqLinkFile))
						{
							$target_file = $reqLinkFile;
						}
						else
						{
							$urlupload= $server.$nip.$reqFolder.$reqId."\\";
							$target_file = $urlupload.$fileName ;
						}
						$set_upload= new PegawaiJabatan();
						$set_upload->setField("LAST_UPDATE_USER", $this->adminusernama);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", "");
						$set_upload->setField('PEGAWAI_JABATAN_FILE_ID', $reqFileId);
						$set_upload->setField("LINK_FILE", $target_file);
						$set_upload->setField("LINK_SERVER", $target_file);
						$set_upload->setField("NAMA_FILE", $fileName);
						$set_upload->setField('PEGAWAI_JABATAN_ID', $reqId);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $pegawaiId);
						$reqMode="update";
					}
					else
					{
						$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
						$check->selectByParamsCheckJabatan(array(),-1,-1,$statement);
						$check->firstRow();
						$reqId=$check->getField("PEGAWAI_JABATAN_ID");

						$urlupload= $server.$nip.$reqFolder.$reqId."\\";
						$target_file = $urlupload.$fileName ;

						if (!is_dir($urlupload)) {
							makedirs($urlupload);
						}

						if(!empty($reqId))
						{
							$set_upload= new PegawaiJabatan();
							$set_upload->setField("LAST_CREATE_USER", $this->adminusernama);
							$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
							$set_upload->setField("LAST_CREATE_SATKER", "");
							$set_upload->setField("LINK_FILE", $target_file);
							$set_upload->setField("LINK_SERVER", $target_file);
							$set_upload->setField("NAMA_FILE", $fileName);
							$set_upload->setField('PEGAWAI_JABATAN_ID', $reqId);
							$set_upload->setField("TIPE_FILE", $ext);
							$set_upload->setField("JENIS_JABATAN_ID", valToNullDB(""));
							$set_upload->setField('PEGAWAI_ID', $pegawaiId);
							$reqMode="insert";
						}
						else 
						{
							header("HTTP/1.0 400 Bad Request");
							echo "Data Jabatan tidak ditemukan";exit;
						}
					}
					if (move_uploaded_file($tmpName, $target_file)) 
					{
						if($reqMode=="insert")
						{
							if($set_upload->insertupload())
							{
								echo "File ".$fileName." berhasil di unggah";
							}
						}
						else
						{
							if($set_upload->updateupload())
							{
								echo "File ".$fileName." berhasil di unggah";
							}
						}
					} 
					else 
					{
						header("HTTP/1.0 400 Bad Request");
						echo "Cek kembali file anda";
					}
				}
				elseif ($fileName == $acceptname[6]) {

					$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
					$check_peg->selectByParamsCheckKpFile(array(),-1,-1,$statement);
					$check_peg->firstRow();
					$reqFileId=$check_peg->getField("PANGKAT_RIWAYAT_FILE_ID");
					$reqId=$check_peg->getField("PANGKAT_RIWAYAT_ID");
					$reqLinkFile=$check_peg->getField("LINK_FILE");
					$statement=" AND A.LINK_SERVER_ID=16 ";
					$check_folder->selectByParamsCheckFolder(array(),-1,-1,$statement);
					$check_folder->firstRow();
					$reqFolder=$check_folder->getField("FOLDER");

					if(!empty($reqFileId))
					{
						if(!empty($reqLinkFile))
						{
							$target_file = $reqLinkFile;
						}
						else
						{
							$urlupload= $server.$nip.$reqFolder.$reqId."\\";
							$renamefile = "SK_".$nip.".".$ext;
							$target_file = $urlupload.$renamefile ;
						}
						$set_upload= new PangkatRiwayat();
						$set_upload->setField("LAST_UPDATE_USER", $this->adminusernama);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", "");
						$set_upload->setField('PANGKAT_RIWAYAT_FILE_ID', $reqFileId);
						$set_upload->setField("LINK_FILE_SK", $target_file);
						$set_upload->setField("LINK_SERVER", $target_file);
						$set_upload->setField("NAMA_FILE_SK", $renamefile);
						$set_upload->setField('PANGKAT_RIWAYAT_ID', $reqId);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $pegawaiId);
						$reqMode="update";
					}
					else
					{
						$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
						$check->selectByParamsCheckKp(array(),-1,-1,$statement);
						$check->firstRow();
						$reqId=$check->getField("PANGKAT_RIWAYAT_ID");

						$urlupload= $server.$nip.$reqFolder.$reqId."\\";
						$renamefile = "SK_".$nip.".".$ext;
						$target_file = $urlupload.$renamefile ;

						if (!is_dir($urlupload)) {
							makedirs($urlupload);
						}

						if(!empty($reqId))
						{
							$set_upload= new PangkatRiwayat();
							$set_upload->setField("LAST_CREATE_USER", $this->adminusernama);
							$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
							$set_upload->setField("LAST_CREATE_SATKER", "");
							$set_upload->setField("LINK_FILE_SK", $target_file);
							$set_upload->setField("LINK_SERVER", $target_file);
							$set_upload->setField("NAMA_FILE_SK", $renamefile);
							$set_upload->setField('PANGKAT_RIWAYAT_ID', $reqId);
							$set_upload->setField("TIPE_FILE", $ext);
							$set_upload->setField('PEGAWAI_ID', $pegawaiId);
							$reqMode="insert";
						}
						else 
						{
							header("HTTP/1.0 400 Bad Request");
							echo "Data Riwayat Pangkat tidak ditemukan";exit;
						}
					}
					if (move_uploaded_file($tmpName, $target_file)) 
					{
						if($reqMode=="insert")
						{
							if($set_upload->insertupload())
							{
								echo "File ".$fileName." berhasil di unggah";
							}
						}
						else
						{
							$nama="NAMA_FILE_SK";
							$link="LINK_FILE_SK";
							if($set_upload->updateupload($nama,$link))
							{
								echo "File ".$fileName." berhasil di unggah";
							}
						}
					} 
					else 
					{
						header("HTTP/1.0 400 Bad Request");
						echo "Cek kembali file anda";
					}
				}
				elseif ($fileName == $acceptname[7]) {
					$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
					$check_peg->selectByParamsCheckPmkFile(array(),-1,-1,$statement);
					$check_peg->firstRow();
					$reqFileId=$check_peg->getField("TAMBAHAN_MASA_KERJA_FILE_ID");
					$reqId=$check_peg->getField("TAMBAHAN_MASA_KERJA_ID");
					$reqLinkFile=$check_peg->getField("LINK_FILE");
					$reqTMTSK=$check_peg->getField("TMT_SK");
					$tahun=date("Y");
					if(!empty($reqTMTSK))
					{
						$tahun =getYear($reqTMTSK);
					}
					$statement=" AND A.LINK_SERVER_ID=3 ";
					$check_folder->selectByParamsCheckFolder(array(),-1,-1,$statement);
					$check_folder->firstRow();
					$reqFolder=$check_folder->getField("FOLDER");

					if(!empty($reqFileId))
					{
						if(!empty($reqLinkFile))
						{
							$target_file = $reqLinkFile;
						}
						else
						{
							$urlupload= $server.$nip.$reqFolder.$reqId."\\";
							$target_file = $urlupload.$reqLinkFile ;
						}
						$set_upload= new TambahanMasaKerja();
						$set_upload->setField("LAST_UPDATE_USER", $this->adminusernama);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", "");
						$set_upload->setField('TAMBAHAN_MASA_KERJA_FILE_ID', $reqFileId);
						$set_upload->setField("LINK_FILE", $reqLinkFile);
						$set_upload->setField("LINK_SERVER", $reqLinkFile);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField('TAMBAHAN_MASA_KERJA_ID', $reqId);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $pegawaiId);
						$reqMode="update";
					}
					else
					{
						$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
						$check->selectByParamsCheckPmk(array(),-1,-1,$statement);
						$check->firstRow();
						$reqId=$check->getField("TAMBAHAN_MASA_KERJA_ID");

						$urlupload= $server.$nip.$reqFolder.$reqId."\\";
						$renamefile = "PMK_".$tahun."_".$nip.".".$ext;
						$target_file = $urlupload.$renamefile ;

						if (!is_dir($urlupload)) {
							makedirs($urlupload);
						}

						if(!empty($reqId))
						{
							$set_upload= new TambahanMasaKerja();
							$set_upload->setField("LAST_CREATE_USER", $this->adminusernama);
							$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
							$set_upload->setField("LAST_CREATE_SATKER", "");
							$set_upload->setField("LINK_FILE", $target_file);
							$set_upload->setField("LINK_SERVER", $target_file);
							$set_upload->setField("NAMA_FILE", $renamefile);
							$set_upload->setField('TAMBAHAN_MASA_KERJA_ID', $reqId);
							$set_upload->setField("TIPE_FILE", $ext);
							$set_upload->setField('PEGAWAI_ID', $pegawaiId);
							$reqMode="insert";
						}
						else 
						{
							header("HTTP/1.0 400 Bad Request");
							echo "Data Tambahan Masa Kerja tidak ditemukan";exit;
						}
					}
					if (move_uploaded_file($tmpName, $target_file)) 
					{
						if($reqMode=="insert")
						{
							if($set_upload->insertupload())
							{
								echo "File ".$fileName." berhasil di unggah";
							}
						}
						else
						{
							if($set_upload->updateupload())
							{
								echo "File ".$fileName." berhasil di unggah";
							}
						}
					} 
					else 
					{
						header("HTTP/1.0 400 Bad Request");
						echo "Cek kembali file anda";
					}
				}
				elseif ($fileName == $acceptname[8]) {
					$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
					$check_peg->selectByParamsCheckPendidikanFile(array(),-1,-1,$statement);
					$check_peg->firstRow();
					$reqFileId=$check_peg->getField("PEGAWAI_PENDIDIKAN_RIWAYAT_FILE_ID");
					$reqId=$check_peg->getField("PEGAWAI_PENDIDIKAN_RIWAYAT_ID");
					$reqLinkFile=$check_peg->getField("LINK_FILE");
			
					$statement=" AND A.LINK_SERVER_ID=11 ";
					$check_folder->selectByParamsCheckFolder(array(),-1,-1,$statement);
					$check_folder->firstRow();
					$reqFolder=$check_folder->getField("FOLDER");

					if(!empty($reqFileId))
					{
						if(!empty($reqLinkFile))
						{
							$target_file = $reqLinkFile;
						}
						else
						{
							$urlupload= $server.$nip.$reqFolder.$reqId."\\";
							$target_file = $urlupload.$reqLinkFile ;
						}
						$set_upload= new PegawaiPendidikanRiwayat();
						$set_upload->setField("LAST_UPDATE_USER", $this->adminusernama);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", "");
						$set_upload->setField('PEGAWAI_PENDIDIKAN_RIWAYAT_FILE_ID', $reqFileId);
						$set_upload->setField("LINK_FILE", $reqLinkFile);
						$set_upload->setField("LINK_SERVER", $reqLinkFile);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField('PEGAWAI_PENDIDIKAN_RIWAYAT_ID', $reqId);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $pegawaiId);
						$reqMode="update";
					}
					else
					{
						$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
						$check->selectByParamsCheckPendidikan(array(),-1,-1,$statement);
						$check->firstRow();
						$reqId=$check->getField("PEGAWAI_PENDIDIKAN_RIWAYAT_ID");

						$urlupload= $server.$nip.$reqFolder.$reqId."\\";
						$renamefile = "PENDIDIKAN_RIWAYAT_".$nip.".".$ext;
						$target_file = $urlupload.$renamefile ;

						if (!is_dir($urlupload)) {
							makedirs($urlupload);
						}

						if(!empty($reqId))
						{
							$set_upload= new PegawaiPendidikanRiwayat();
							$set_upload->setField("LAST_CREATE_USER", $this->adminusernama);
							$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
							$set_upload->setField("LAST_CREATE_SATKER", "");
							$set_upload->setField("LINK_FILE", $target_file);
							$set_upload->setField("LINK_SERVER", $target_file);
							$set_upload->setField("NAMA_FILE", $renamefile);
							$set_upload->setField('PEGAWAI_PENDIDIKAN_RIWAYAT_ID', $reqId);
							$set_upload->setField("TIPE_FILE", $ext);
							$set_upload->setField('PEGAWAI_ID', $pegawaiId);
							$reqMode="insert";
						}
						else 
						{
							header("HTTP/1.0 400 Bad Request");
							echo "Data Pendidikan Riwayat tidak ditemukan";exit;
						}
					}
					if (move_uploaded_file($tmpName, $target_file)) 
					{
						if($reqMode=="insert")
						{
							if($set_upload->insertupload())
							{
								echo "File ".$fileName." berhasil di unggah";
							}
						}
						else
						{
							if($set_upload->updateupload())
							{
								echo "File ".$fileName." berhasil di unggah";
							}
						}
					} 
					else 
					{
						header("HTTP/1.0 400 Bad Request");
						echo "Cek kembali file anda";
					}
				}
				elseif ($fileName == $acceptname[9]) {
					$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
					$check_peg->selectByParamsCheckDiklatFile(array(),-1,-1,$statement);
					$check_peg->firstRow();
					$reqFileId=$check_peg->getField("PEGAWAI_DIKLAT_FILE_ID");
					$reqId=$check_peg->getField("PEGAWAI_DIKLAT_ID");
					$reqLinkFile=$check_peg->getField("LINK_FILE");
			
					$statement=" AND A.LINK_SERVER_ID=14 ";
					$check_folder->selectByParamsCheckFolder(array(),-1,-1,$statement);
					$check_folder->firstRow();
					$reqFolder=$check_folder->getField("FOLDER");

					$set_upload= new PegawaiDiklat();
					if(!empty($reqFileId))
					{
						if(!empty($reqLinkFile))
						{
							$target_file = $reqLinkFile;
						}
						else
						{
							$urlupload= $server.$nip.$reqFolder.$reqId."\\";
							$target_file = $urlupload.$reqLinkFile ;
						}
						$set_upload= new PegawaiDiklat();
						$set_upload->setField("LAST_UPDATE_USER", $this->adminusernama);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", "");
						$set_upload->setField('PEGAWAI_DIKLAT_FILE_ID', $reqFileId);
						$set_upload->setField("LINK_FILE", $reqLinkFile);
						$set_upload->setField("LINK_SERVER", $reqLinkFile);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField('PEGAWAI_DIKLAT_ID', $reqId);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $pegawaiId);
						$reqMode="update";
					}
					else
					{
						$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
						$check->selectByParamsCheckDiklat(array(),-1,-1,$statement);
						$check->firstRow();
						$reqId=$check->getField("PEGAWAI_DIKLAT_ID");
						$reqDiklatNama=$check->getField("DIKLAT_KET");
						$reqDiklatNama= str_replace(" ", "_", $reqDiklatNama);

						$urlupload= $server.$nip.$reqFolder.$reqId."\\";
						$renamefile = "SK_".$reqDiklatNama."_".$nip.".".$ext;
						$target_file = $urlupload.$renamefile ;

						if (!is_dir($urlupload)) {
							makedirs($urlupload);
						}

						if(!empty($reqId))
						{
							$set_upload= new PegawaiDiklat();
							$set_upload->setField("LAST_CREATE_USER", $this->adminusernama);
							$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
							$set_upload->setField("LAST_CREATE_SATKER", "");
							$set_upload->setField("LINK_FILE", $target_file);
							$set_upload->setField("LINK_SERVER", $target_file);
							$set_upload->setField("NAMA_FILE", $renamefile);
							$set_upload->setField('PEGAWAI_DIKLAT_ID', $reqId);
							$set_upload->setField("TIPE_FILE", $ext);
							$set_upload->setField('PEGAWAI_ID', $pegawaiId);
							$reqMode="insert";
						}
						else 
						{
							header("HTTP/1.0 400 Bad Request");
							echo "Data Pegawat Diklat tidak ditemukan";exit;
						}
					}
					if (move_uploaded_file($tmpName, $target_file)) 
					{
						if($reqMode=="insert")
						{
							if($set_upload->insertupload())
							{
								echo "File ".$fileName." berhasil di unggah";
							}
						}
						else
						{
							if($set_upload->updateupload())
							{
								echo "File ".$fileName." berhasil di unggah";
							}
						}
					} 
					else 
					{
						header("HTTP/1.0 400 Bad Request");
						echo "Cek kembali file anda";
					}
				}
				elseif ($fileName == $acceptname[10]) {
					$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
					$check_peg->selectByParamsCheckSkpFile(array(),-1,-1,$statement);
					$check_peg->firstRow();
					$reqFileId=$check_peg->getField("PENILAIAN_KERJA_PEGAWAI_FILE_ID");
					$reqId=$check_peg->getField("PENILAIAN_KERJA_PEGAWAI_ID");
					$reqLinkFile=$check_peg->getField("LINK_FILE");
			
					$statement=" AND A.LINK_SERVER_ID=13 ";
					$check_folder->selectByParamsCheckFolder(array(),-1,-1,$statement);
					$check_folder->firstRow();
					$reqFolder=$check_folder->getField("FOLDER");


					if(!empty($reqFileId))
					{
						if(!empty($reqLinkFile))
						{
							$target_file = $reqLinkFile;
						}
						else
						{
							$urlupload= $server.$nip.$reqFolder.$reqId."\\";
							$target_file = $urlupload.$reqLinkFile ;
						}
						$set_upload= new PenilaianKerjaPegawai();
						$set_upload->setField("LAST_UPDATE_USER", $this->adminusernama);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", "");
						$set_upload->setField('PENILAIAN_KERJA_PEGAWAI_FILE_ID', $reqFileId);
						$set_upload->setField("LINK_FILE", $reqLinkFile);
						$set_upload->setField("LINK_SERVER", $reqLinkFile);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField('PENILAIAN_KERJA_PEGAWAI_ID', $reqId);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $pegawaiId);
						$reqMode="update";
					}
					else
					{
						$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
						$check->selectByParamsCheckSkp(array(),-1,-1,$statement);
						$check->firstRow();
						$reqId=$check->getField("PENILAIAN_KERJA_PEGAWAI_ID");
						$reqTahun=$check->getField("TAHUN");

						$urlupload= $server.$nip.$reqFolder.$reqId."\\";
						if(!empty($reqTahun))
						{
							$renamefile = "SKP_".$reqTahun."_".$nip.".".$ext;
						}
						else
						{
							$renamefile = "SKP_".$nip.".".$ext;
						}
						$target_file = $urlupload.$renamefile ;

						if (!is_dir($urlupload)) {
							makedirs($urlupload);
						}

						if(!empty($reqId))
						{
							$set_upload= new PenilaianKerjaPegawai();
							$set_upload->setField("LAST_CREATE_USER", $this->adminusernama);
							$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
							$set_upload->setField("LAST_CREATE_SATKER", "");
							$set_upload->setField("LINK_FILE", $target_file);
							$set_upload->setField("LINK_SERVER", $target_file);
							$set_upload->setField("NAMA_FILE", $renamefile);
							$set_upload->setField('PENILAIAN_KERJA_PEGAWAI_ID', $reqId);
							$set_upload->setField("TIPE_FILE", $ext);
							$set_upload->setField('PEGAWAI_ID', $pegawaiId);
							$reqMode="insert";
						}
						else 
						{
							header("HTTP/1.0 400 Bad Request");
							echo "Data Skp tidak ditemukan";exit;
						}
					}
					if (move_uploaded_file($tmpName, $target_file)) 
					{
						if($reqMode=="insert")
						{
							if($set_upload->insertupload())
							{
								echo "File ".$fileName." berhasil di unggah";
							}
						}
						else
						{
							if($set_upload->updateupload())
							{
								echo "File ".$fileName." berhasil di unggah";
							}
						}
					} 
					else 
					{
						header("HTTP/1.0 400 Bad Request");
						echo "Cek kembali file anda";
					}
				}
			}
			
		}
		else
		{
			header("HTTP/1.0 400 Bad Request");
			echo " Cek kembali Nip pada nama file/ Nip Pegawai tidak ditemukan ";
		}

	}

	function jsonupload()
	{
		$this->load->model("base-app/UploadFile");

		$adminuserid= $this->adminuserid;


		$set= new UploadFile();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= "";
		$sOrder = "";
		$set->selectByParams(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		$no=1;
		while ($set->nextRow()) 
		{
			$reqLinkFile = $set->getField("LINK_FILE");
			$reqPegawaiId = $this->pegawaiId;
			$reqRowId = $set->getField("RIWAYAT_KONTRAK_ID");
			$reqFileId = $set->getField("UPLOAD_FILE_ID");
			$reqLinkFileServer = $set->getField("LINK_FILE");

			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "NO")
				{
					$row[$valkey]= $no;
				}
				else if ($valkey == "KETERANGAN")
				{ 
					$row[$valkey]= '';

					if(!empty($reqLinkFileServer))
					{
						$row[$valkey].= '
						<a class="navi-link" href="admin/loadUrl/admin/viewer?reqForm=upload_multi&reqFileId='.$reqFileId.'" target="_blank">
						<img src="images/download.png" title="download" style="width:20px;height:20px">
						</a>
						&nbsp;
						';

						$row[$valkey].= '
						<a class="navi-link" onclick=\'btnDeleteFile("'.$reqFileId.'","'.$reqPegawaiId.'","'.$reqRowId.'");\'>
						<img src="images/delete-icon.png" title="delete" style="width:20px;height:20px">
						</a>
						';
					}
					
				}
				else
				{
					$row[$valkey]= $set->getField($valkey);
				}
			}
			$no++;
			array_push($arrinfodata, $row);
		}

		// get all raw data
		$alldata = $arrinfodata;
		// print_r($alldata);exit;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			// $data[] = filterArray( $d, $columnsDefault );
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {
			$data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					$data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			if(count($columnsDefault) - 2 == $column){}
			else
			{
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			}
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function uploaddeletefile()
	{
		$this->load->model("base-app/UploadFile");
		$reqFileId= $this->input->get("reqFileId");
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqRowId= $this->input->get("reqRowId");
		$set= new UploadFile();
		$set->setField('UPLOAD_FILE_ID', $reqFileId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);

		$set_upload = new UploadFile();
		
		$set_upload->selectByParams(array('A.UPLOAD_FILE_ID'=>$reqFileId));
		// echo $set_upload->query;exit;
		
		$set_upload->firstRow();
		$reqLinkFile= $set_upload->getField('LINK_FILE');
		// echo $reqRowId;exit;		
		$reqSimpan="";

		if(file_exists($reqLinkFile))
		{
			unlink($reqLinkFile);
		}

		if($set->delete())	
		{			
			$reqSimpan=1;
		}

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil dihapus');
		}
		else
		{
			echo json_response(400, 'Data gagal dihapus');
		}
				
	}
	
	
}
?>