<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/class-list-util.php");
include_once("functions/class-list-util-serverside.php");

class personal_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		//kauth
		if($this->session->userdata("userpegawaiId") == "")
		{
			redirect('login');
		}
		
		$this->pegawaiId= $this->session->userdata("userpegawaiId");
		$this->userpegawaiNama= $this->session->userdata("userpegawaiNama");
		$this->adminuserid= $this->session->userdata("adminuserid");
	}

	function jsonpegawaidataadd()
	{
		$this->load->model("base-validasi/Pegawai");

		$reqMode= $this->input->post("reqMode");
		$reqTempValidasiId= $this->input->post("reqTempValidasiId");
		$reqPegawaiId= $this->pegawaiId;
		$reqNipLama= $this->input->post("reqNipLama");
		$reqNipBaru= $this->input->post("reqNipBaru");
		$reqSatuanKerja= $this->input->post("reqSatuanKerja");
		$reqNama= $this->input->post("reqNama");
		$reqTipePegawai= $this->input->post("reqTipePegawai");
		$reqGelarDepan= $this->input->post("reqGelarDepan");
		$reqGelarBelakang= $this->input->post("reqGelarBelakang");
		$reqStatusPegawai= $this->input->post("reqStatusPegawai");
		$reqTempatLahir= $this->input->post("reqTempatLahir");
		$reqTanggalLahir= $this->input->post("reqTanggalLahir");
		$reqJenisKelamin= $this->input->post("reqJenisKelamin");
		$reqJenisPegawai= $this->input->post("reqJenisPegawai");
		$reqStatusPernikahan= $this->input->post("reqStatusPernikahan");
		$reqKartuPegawai= $this->input->post("reqKartuPegawai");
		$reqSukuBangsa= $this->input->post("reqSukuBangsa");
		$reqGolDarah= $this->input->post("reqGolDarah");
		$reqAkses= $this->input->post("reqAkses");
		$reqTaspen= $this->input->post("reqTaspen");
		$reqAlamat= $this->input->post("reqAlamat");
		$reqNPWP= $this->input->post("reqNPWP");
		$reqNIK= $this->input->post("reqNIK");
		$reqRT= $this->input->post("reqRT");
		$reqRW= $this->input->post("reqRW");
		$reqEmail= $this->input->post("reqEmail");
		$reqPropinsiId= $this->input->post("reqPropinsiId");
		$reqKabupatenId= $this->input->post("reqKabupatenId");
		$reqKecamatanId= $this->input->post("reqKecamatanId");
		$reqDesaId= $this->input->post("reqDesaId");
		$reqBank= $this->input->post("reqBank");
		$reqNoRekening= $this->input->post("reqNoRekening");
		$reqTanggalPensiun= $this->input->post("reqTglPensiun");
		$reqTanggalPindah= $this->input->post("reqTanggalPindah");
		$reqAgamaId= $this->input->post("reqAgamaId");
		$reqTelepon= $this->input->post("reqTelepon");
		$reqKodePos= $this->input->post("reqKodePos");
		$reqKedudukanId= $this->input->post("reqKedudukanId");
		$reqKonversiNIP= $this->input->post("reqKonversiNIP");
		$reqGambar= $_FILES["reqGambar"];
		$reqGambarSetengah= $_FILES["reqGambarSetengah"];
		$reqStatusValidasi= $this->input->post("reqStatusValidasi");

		$reqLinkFile=$_FILES['reqLinkFileFoto']['tmp_name'];
		$fileNamefoto = $_FILES['reqLinkFileFoto']['name'];

		$reqLinkFileKarpeg=$_FILES['reqLinkFileKarpeg']['tmp_name'];
		$fileNameKarpeg = $_FILES['reqLinkFileKarpeg']['name'];

		$reqLinkFileAskes=$_FILES['reqLinkFileAskes']['tmp_name'];
		$fileNameAskes = $_FILES['reqLinkFileAskes']['name'];

		$reqLinkFileTaspen=$_FILES['reqLinkFileTaspen']['tmp_name'];
		$fileNameTaspen = $_FILES['reqLinkFileTaspen']['name'];

		$reqLinkFileNpwp=$_FILES['reqLinkFileNpwp']['tmp_name'];
		$fileNameNpwp = $_FILES['reqLinkFileNpwp']['name'];

		$reqLinkFileNik=$_FILES['reqLinkFileNik']['tmp_name'];
		$fileNameNik = $_FILES['reqLinkFileNik']['name'];

		$reqLinkFileSk=$_FILES['reqLinkFileSk']['tmp_name'];
		$fileNameSk = $_FILES['reqLinkFileSk']['name'];


		if(!empty($fileNamefoto))
		{
			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'image/jpeg',
				'image/gif',
				'image/png'
			);

			if(($_FILES['reqLinkFileFoto']['size'] >= $maxsize)) {
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if((!in_array($_FILES['reqLinkFileFoto']['type'], $acceptable)) && (!empty($_FILES["reqLinkFileFoto"]["type"]))) {
				$errors[] = 'File gagal diupload, Pastikan File berformat JPEG,JPG,GIF,PNG';
			}

			if(count($errors) > 0) 
			{
				foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
			}
			
		}

		if(!empty($fileNameKarpeg) || !empty($fileNameAskes) || !empty($fileNameTaspen) || !empty($fileNameNpwp) || !empty($fileNameNik) || !empty($fileNameSk))
		{
			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFileKarpeg']['size'] >= $maxsize) || ($_FILES['reqLinkFileAskes']['size'] >= $maxsize) || ($_FILES['reqLinkFileTaspen']['size'] >= $maxsize) || ($_FILES['reqLinkFileNpwp']['size'] >= $maxsize) || ($_FILES['reqLinkFileNik']['size'] >= $maxsize) || ($_FILES['reqLinkFileSk']['size'] >= $maxsize) ) 
			{
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if(count($errors) > 0) 
			{
				foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
			}
			
		}
		// echo $reqTempValidasiId."--";exit;

		$set= new Pegawai();
		$set->setField("LAST_CREATE_USER", "");
		$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
		$set->setField("LAST_CREATE_SATKER", "");
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('NIP_LAMA', $reqNipLama);
		$set->setField('NIP_BARU', $reqNipBaru);
		$set->setField('SATKER_ID', $reqSatuanKerja);
		$reqNama= str_replace("\'", "''", $reqNama);	
		$set->setField('NAMA', setQuote($reqNama));
		$set->setField('TIPE_PEGAWAI_ID', $reqTipePegawai);
		$set->setField('GELAR_DEPAN', $reqGelarDepan);
		$set->setField('GELAR_BELAKANG', $reqGelarBelakang);
		$set->setField('STATUS_PEGAWAI', ValToNullDB($reqStatusPegawai));
		$set->setField('TEMPAT_LAHIR', $reqTempatLahir);
		$set->setField('TANGGAL_LAHIR', dateToDBCheck($reqTanggalLahir));
		$set->setField('TANGGAL_PENSIUN', dateToDBCheck($reqTanggalPensiun));
		$set->setField('TANGGAL_PINDAH', dateToDBCheck($reqTanggalPindah));
		$set->setField('JENIS_KELAMIN', $reqJenisKelamin);
		$set->setField('JENIS_PEGAWAI_ID', ValToNullDB($reqJenisPegawai));
		$set->setField('STATUS_KAWIN', ValToNullDB($reqStatusPernikahan));
		$set->setField('KARTU_PEGAWAI', $reqKartuPegawai);
		$set->setField('SUKU_BANGSA', $reqSukuBangsa);
		$reqGolDarah = str_replace(" ", "", $reqGolDarah);	
		$set->setField('GOLONGAN_DARAH', $reqGolDarah);
		$set->setField('ASKES', $reqAkses);
		$set->setField('TASPEN', $reqTaspen);
		$set->setField('ALAMAT', setQuote($reqAlamat,1));
		$set->setField('NPWP', $reqNPWP);
		$set->setField('NIK', $reqNIK);
		$set->setField('RT', $reqRT);
		$set->setField('RW', $reqRW);
		$set->setField('EMAIL', $reqEmail);
		$set->setField('SK_KONVERSI_NIP', $reqKonversiNIP);
		$set->setField('TANGGAL_MATI', dateToDBCheck(''));
		$set->setField('TANGGAL_TERUSAN', dateToDBCheck(''));
		$set->setField('TANGGAL_UPDATE',"CURRENT_DATE");
		$set->setField('PROPINSI_ID', ValToNullDB($reqPropinsiId));
		$set->setField('KABUPATEN_ID', ValToNullDB($reqKabupatenId));
		$set->setField('KECAMATAN_ID', ValToNullDB($reqKecamatanId));
		$set->setField('KELURAHAN_ID', ValToNullDB($reqDesaId));
		$set->setField('BANK_ID', ValToNullDB($reqBank));
		$set->setField('NO_REKENING', $reqNoRekening);
		$set->setField('AGAMA_ID', ValToNullDB($reqAgamaId));
		$set->setField('TELEPON', $reqTelepon);
		$set->setField('KODEPOS', $reqKodePos);
		$set->setField('KEDUDUKAN_ID', ValToNullDB($reqKedudukanId));

		$reqSimpan="";
		if(is_numeric($reqStatusValidasi))
		{
			$set->setField('VALIDASI', $reqStatusValidasi);
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
			$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

			if($set->updatevalidasi())
			{
				$reqSimpan= "1";
			}
		}
		else
		{
			if($reqMode == "insert")
			{
				if($set->insert())
				{
					$reqPegawaiId= $set->pegawai_id;
					$reqSimpan = 1;
				}
			}
			elseif($reqMode == "update")
			{
				$set->setField("LAST_UPDATE_USER", "");
				$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_UPDATE_SATKER", "");	
				$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);
				if($set->update())
				{
					$reqSimpan = 1;
				}
			}
			
		}

		// echo $set->query;exit;

		if(!empty($fileNamefoto))
		{
			if(count($errors) === 0) 
			{
				$statement="";
				$set_upload= new Pegawai();
				$set_upload->selectByParams(array("P.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 10";
				$set_upload= new Pegawai();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqPegawaiId."\\";

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$path_parts = pathinfo($_FILES["reqLinkFileFoto"]["name"]);
				$ext = strtolower($path_parts['extension']);

				$renamefile = "FOTO_".$tempNipBaru.".".$ext;

				$dest_path = $urlupload . $renamefile;
				// print_r($dest_path);exit;
				if(file_exists($dest_path))
				{
					unlink($dest_path);
				}

				$statement="";
				$nama="NAMA_FILE_FOTO";
				$link="LINK_FILE_FOTO";
				$tipe="TIPE_FILE_FOTO";

				if(move_uploaded_file($reqLinkFile, $dest_path))
				{
					$statement="";
					$set_upload= new Pegawai();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE_FOTO");
					$fileid=$set_upload->getField("PEGAWAI_FILE_ID");
			
					if(empty($fileid) && empty($tempLinkFile) )
					{
						$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField("LINK_FILE_FOTO", $dest_path);
						$set_upload->setField("NAMA_FILE_FOTO", $renamefile);
						$set_upload->setField("TIPE_FILE_FOTO", $ext);
						$set_upload->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);
						$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$set_upload->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField("LINK_FILE_FOTO", $dest_path);
						$set_upload->setField("NAMA_FILE_FOTO", $renamefile);
						$set_upload->setField("TIPE_FILE_FOTO", $ext);
						$set_upload->setField('PEGAWAI_FILE_ID', $fileid);
						$set_upload->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);
						if($set_upload->updateupload($nama,$link,$tipe))
						{
							$reqSimpan= 1;
						}
					}
				}
			}
			else 
		    {
		    	foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
		    }
		}


		if(!empty($fileNameKarpeg) || !empty($fileNameAskes) || !empty($fileNameTaspen) || !empty($fileNameNpwp) || !empty($fileNameNik) || !empty($fileNameSk))
		{
			if(count($errors) === 0) 
			{
				$statement="";
				$set_upload= new Pegawai();
				$set_upload->selectByParamsData(array("P.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 10";
				$set_upload= new Pegawai();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqPegawaiId."\\";

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$ext = "pdf";

				$renamefileKarpeg = "KARPEG_".$tempNipBaru.".".$ext;
				$renamefileAskes = "ASKES_".$tempNipBaru.".".$ext;
				$renamefileTaspen = "TASPEN_".$tempNipBaru.".".$ext;
				$renamefileNpwp = "NPWP_".$tempNipBaru.".".$ext;
				$renamefileNik = "NIK_".$tempNipBaru.".".$ext;
				$renamefileSk = "SK_".$tempNipBaru.".".$ext;

				$dest_path_karpeg = $urlupload . $renamefileKarpeg;
				$dest_path_askes = $urlupload . $renamefileAskes;
				$dest_path_taspen = $urlupload . $renamefileTaspen;
				$dest_path_npwp = $urlupload . $renamefileNpwp;
				$dest_path_nik = $urlupload . $renamefileNik;
				$dest_path_sk = $urlupload . $renamefileSk;

				if (!empty($fileNameKarpeg))
				{
					if(file_exists($dest_path_karpeg))
					{
						unlink($dest_path_karpeg);
					}

				}
				else if (!empty($fileNameAskes))
				{
					if(file_exists($dest_path_askes))
					{
						unlink($dest_path_askes);
					}
				}
				else if (!empty($fileNameTaspen))
				{
					if(file_exists($dest_path_taspen))
					{
						unlink($dest_path_taspen);
					}
				}
				else if (!empty($fileNameNpwp))
				{
					if(file_exists($dest_path_npwp))
					{
						unlink($dest_path_npwp);
					}
				}
				else if (!empty($fileNameNik))
				{
					if(file_exists($dest_path_nik))
					{
						unlink($dest_path_nik);
					}

				}
				else if (!empty($fileNameSk))
				{
					if(file_exists($dest_path_sk))
					{
						unlink($dest_path_sk);
					}

				}

				$statement="";


				if(move_uploaded_file($reqLinkFileKarpeg, $dest_path_karpeg))
				{
					$statement="";
					$set_upload= new Pegawai();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE_KARPEG");
					$fileid=$set_upload->getField("PEGAWAI_FILE_ID");
					
					$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_upload->setField("LINK_FILE_KARPEG", $dest_path_karpeg);
					$set_upload->setField("NAMA_FILE_KARPEG", $renamefileKarpeg);
					$set_upload->setField("TIPE_FILE", $ext);
					$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);

					$nama="NAMA_FILE_KARPEG";
					$link="LINK_FILE_KARPEG";
					$tipe="TIPE_FILE";
					if(empty($fileid) && empty($tempLinkFile))
					{
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$set_upload->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('PEGAWAI_FILE_ID', $fileid);
						$set_upload->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);
						if($set_upload->updateupload($nama,$link,$tipe))
						{
							$reqSimpan= 1;
						}
					}
				}

				if(move_uploaded_file($reqLinkFileAskes, $dest_path_askes))
				{
					$statement="";
					$set_upload= new Pegawai();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE_ASKES");
					$fileid=$set_upload->getField("PEGAWAI_FILE_ID");
					
					$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_upload->setField("LINK_FILE_ASKES", $dest_path_askes);
					$set_upload->setField("NAMA_FILE_ASKES", $renamefileAskes);
					$set_upload->setField("TIPE_FILE", $ext);
					$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);

					$nama="NAMA_FILE_ASKES";
					$link="LINK_FILE_ASKES";
					$tipe="TIPE_FILE";
					if(empty($fileid) && empty($tempLinkFile))
					{
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$set_upload->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('PEGAWAI_FILE_ID', $fileid);
						$set_upload->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);
						if($set_upload->updateupload($nama,$link,$tipe))
						{
							$reqSimpan= 1;
						}
					}
				}

				if(move_uploaded_file($reqLinkFileTaspen, $dest_path_taspen) )
				{
					$statement="";
					$set_upload= new Pegawai();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE_TASPEN");
					$fileid=$set_upload->getField("PEGAWAI_FILE_ID");
					
					$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_upload->setField("LINK_FILE_TASPEN", $dest_path_taspen);
					$set_upload->setField("NAMA_FILE_TASPEN", $renamefileTaspen);
					$set_upload->setField("TIPE_FILE", $ext);
					$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);

					$nama="NAMA_FILE_TASPEN";
					$link="LINK_FILE_TASPEN";
					$tipe="TIPE_FILE";
					if(empty($fileid) && empty($tempLinkFile))
					{
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$set_upload->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('PEGAWAI_FILE_ID', $fileid);
						$set_upload->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);
						if($set_upload->updateupload($nama,$link,$tipe))
						{
							$reqSimpan= 1;
						}
					}
				}

				if(move_uploaded_file($reqLinkFileNpwp, $dest_path_npwp))
				{
					$statement="";
					$set_upload= new Pegawai();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE_NPWP");
					$fileid=$set_upload->getField("PEGAWAI_FILE_ID");
					
					$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_upload->setField("LINK_FILE_NPWP", $dest_path_npwp);
					$set_upload->setField("NAMA_FILE_NPWP", $renamefileNpwp);
					$set_upload->setField("TIPE_FILE", $ext);
					$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);

					$nama="NAMA_FILE_NPWP";
					$link="LINK_FILE_NPWP";
					$tipe="TIPE_FILE";
					if(empty($fileid) && empty($tempLinkFile))
					{
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$set_upload->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('PEGAWAI_FILE_ID', $fileid);
						$set_upload->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);
						if($set_upload->updateupload($nama,$link,$tipe))
						{
							$reqSimpan= 1;
						}
					}
					// echo $set_upload->query;exit;

				}

				if(move_uploaded_file($reqLinkFileNik, $dest_path_nik))
				{
					$statement="";
					$set_upload= new Pegawai();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE_NIK");
					$fileid=$set_upload->getField("PEGAWAI_FILE_ID");
					
					$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_upload->setField("LINK_FILE_NIK", $dest_path_nik);
					$set_upload->setField("NAMA_FILE_NIK", $renamefileNik);
					$set_upload->setField("TIPE_FILE", $ext);
					$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);

					$nama="NAMA_FILE_NIK";
					$link="LINK_FILE_NIK";
					$tipe="TIPE_FILE";
					if(empty($fileid) && empty($tempLinkFile))
					{
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$set_upload->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('PEGAWAI_FILE_ID', $fileid);
						$set_upload->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);
						if($set_upload->updateupload($nama,$link,$tipe))
						{
							$reqSimpan= 1;
						}
					}
				}

				if(move_uploaded_file($reqLinkFileSk, $dest_path_sk))
				{
					$statement="";
					$set_upload= new Pegawai();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE_SK");
					$fileid=$set_upload->getField("PEGAWAI_FILE_ID");
					
					$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_upload->setField("LINK_FILE_SK", $dest_path_sk);
					$set_upload->setField("NAMA_FILE_SK", $renamefileSk);
					$set_upload->setField("TIPE_FILE", $ext);
					$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);

					$nama="NAMA_FILE_SK";
					$link="LINK_FILE_SK";
					$tipe="TIPE_FILE";
					if(empty($fileid) && empty($tempLinkFile))
					{
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$set_upload->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('PEGAWAI_FILE_ID', $fileid);
						$set_upload->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);
						if($set_upload->updateupload($nama,$link,$tipe))
						{
							$reqSimpan= 1;
						}
					}
				}
				
			}
			else 
		    {
		    	foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
		    }
		}

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsonpegawaideletefile()
	{
		$this->load->model("base-validasi/Pegawai");
		$reqFileId= $this->input->get("reqFileId");
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqMode= $this->input->get("reqMode");
		$reqTempValidasiId= $this->input->get("reqTempValidasiId");
		$set= new Pegawai();
		$set->setField('PEGAWAI_FILE_ID', $reqFileId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

		$set_upload = new Pegawai();
		
		if($reqTempValidasiId == "")
		{
			$set_upload->selectByParams(array('P.PEGAWAI_ID'=>$reqPegawaiId));
		}
		else
		{
			$set_upload->selectByParams(array('A.TEMP_VALIDASI_ID'=>$reqTempValidasiId));
		}

		$set_upload->firstRow();

		$reqSimpan="";
		if($reqMode=="pegawai_foto")
		{
			$reqLinkFile= $set_upload->getField('LINK_FILE_FOTO');
			$reqNama="NAMA_FILE_FOTO";
			$reqLink="LINK_FILE_FOTO";
			$reqTipe="TIPE_FILE_FOTO";
			if($set->deletefile($reqNama,$reqLink,$reqTipe))	
			{			
				$reqSimpan=1;
			}
		}
		else if($reqMode=="pegawai_karpeg")
		{
			$reqLinkFile= $set_upload->getField('LINK_FILE_KARPEG');
			$reqNama="NAMA_FILE_KARPEG";
			$reqLink="LINK_FILE_KARPEG";
			$reqTipe="TIPE_FILE";
			if($set->deletefile($reqNama,$reqLink,$reqTipe))	
			{			
				$reqSimpan=1;
			}
		}
		else if($reqMode=="pegawai_askes")
		{
			$reqLinkFile= $set_upload->getField('LINK_FILE_ASKES');
			$reqNama="NAMA_FILE_ASKES";
			$reqLink="LINK_FILE_ASKES";
			$reqTipe="TIPE_FILE";
			if($set->deletefile($reqNama,$reqLink,$reqTipe))	
			{			
				$reqSimpan=1;
			}
		}
		else if($reqMode=="pegawai_taspen")
		{
			$reqLinkFile= $set_upload->getField('LINK_FILE_TASPEN');

			$reqNama="NAMA_FILE_TASPEN";
			$reqLink="LINK_FILE_TASPEN";
			$reqTipe="TIPE_FILE";
			if($set->deletefile($reqNama,$reqLink,$reqTipe))	
			{			
				$reqSimpan=1;
			}
		}
		else if($reqMode=="pegawai_npwp")
		{
			$reqLinkFile= $set_upload->getField('LINK_FILE_NPWP');
			$reqNama="NAMA_FILE_NPWP";
			$reqLink="LINK_FILE_NPWP";
			$reqTipe="TIPE_FILE";
			if($set->deletefile($reqNama,$reqLink,$reqTipe))	
			{			
				$reqSimpan=1;
			}
		}
		else if($reqMode=="pegawai_nik")
		{
			$reqLinkFile= $set_upload->getField('LINK_FILE_NIK');
			$reqNama="NAMA_FILE_NIK";
			$reqLink="LINK_FILE_NIK";
			$reqTipe="TIPE_FILE";
			if($set->deletefile($reqNama,$reqLink,$reqTipe))	
			{			
				$reqSimpan=1;
			}
		}
		else if($reqMode=="pegawai_sk")
		{
			$reqLinkFile= $set_upload->getField('LINK_FILE_SK');
			$reqNama="NAMA_FILE_SK";
			$reqLink="LINK_FILE_SK";
			$reqTipe="TIPE_FILE";
			if($set->deletefile($reqNama,$reqLink,$reqTipe))	
			{			
				$reqSimpan=1;
			}
		}
		 // print_r($reqLinkFile);exit;

		if(file_exists($reqLinkFile))
		{
			unlink($reqLinkFile);
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

	function jsonpegawaiskcpnsadd()
	{
		$this->load->model("base-validasi/SkCpns");
		$this->load->model("base-validasi/PejabatPenetap");
		$this->load->model("base-validasi/TrigerCpnsPnsPangkatGajiTambahan");

		$reqMode= $this->input->post("reqMode");
		$reqPegawaiId= $this->pegawaiId;
		$reqRowId= $this->input->post("reqRowId");
		$reqTempValidasiId= $this->input->post("reqTempValidasiId");
		$reqNoNotaBAKN= $this->input->post("reqNoNotaBAKN");
		$reqTanggalNotaBAKN= $this->input->post("reqTanggalNotaBAKN");
		$reqPejabatPenetapan= $this->input->post("reqPejabatPenetapan");
		$reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
		$reqNamaPejabatPenetap= $this->input->post("reqNamaPejabatPenetap");
		$reqNIPPejabatPenetap= $this->input->post("reqNIPPejabatPenetap");
		$reqNoSuratKeputusan= $this->input->post("reqNoSuratKeputusan");
		$reqTanggalSuratKeputusan= $this->input->post("reqTanggalSuratKeputusan");
		$reqTerhitungMulaiTanggal= $this->input->post("reqTerhitungMulaiTanggal");
		$reqGolRuang= $this->input->post("reqGolRuang");
		$reqTanggalTugas= $this->input->post("reqTanggalTugas");
		$reqSkcpnsId= $this->input->post("reqSkcpnsId");
		$reqTh= $this->input->post("reqTh");
		$reqBl= $this->input->post("reqBl");
		$reqGajiPokok= $this->input->post("reqGajiPokok");
		$reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");
		$reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
		// var_dump ($reqPejabatPenetap);exit;
		$reqStatusPejabat= $this->input->post("reqStatusPejabat");
		$reqUpload= $this->input->post("reqUpload");
		$reqLinkFile= $_FILES["reqLinkFile"];

		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];

		if(!empty($fileName))
		{
			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFile']['size'] >= $maxsize) || ($_FILES["reqLinkFile"]["size"] == 0)) {
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if((!in_array($_FILES['reqLinkFile']['type'], $acceptable)) && (!empty($_FILES["reqLinkFile"]["type"]))) {
				$errors[] = 'File gagal diupload, Pastikan File berformat PDF';
			}

			if(count($errors) > 0) 
			{
				foreach($errors as $error) {
					echo json_response(400, $error);
				}
				exit;
			}
			
		}

		$set= new SkCpns();
		$set->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId));	
		$set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('PANGKAT_ID', ValToNullDB($reqGolRuang));
		$set->setField('TMT_CPNS', dateToDBCheck($reqTerhitungMulaiTanggal));
		$set->setField('TANGGAL_TUGAS', dateToDBCheck($reqTanggalTugas)); 
		$set->setField('NO_STTPP', '');
		$set->setField('NO_NOTA', $reqNoNotaBAKN);
		$set->setField('TANGGAL_NOTA', dateToDBCheck($reqTanggalNotaBAKN));
		$set->setField('NO_SK', $reqNoSuratKeputusan);
		$set->setField('TANGGAL_STTPP', dateToDBCheck($reqTanggalTugas));
		$set->setField('NAMA_PENETAP', $reqNamaPejabatPenetap);
		$set->setField('TANGGAL_SK', dateToDBCheck($reqTanggalSuratKeputusan));
		$set->setField('NIP_PENETAP', $reqNIPPejabatPenetap);
		$set->setField('TANGGAL_UPDATE',$reqTan);
		$set->setField('MASA_KERJA_TAHUN', ValToNullDB($reqTh));
		$set->setField('MASA_KERJA_BULAN', ValToNullDB($reqBl));
		$set->setField('USER_APP_ID', $userLogin->UID);
		$set->setField('GAJI_POKOK', ValToNullDB(dotToNo($reqGajiPokok)));
		$reqSimpan="";
		if($reqUpload==1)
		{
			$reqSimpan= 1;
		}
		else
		{
			if($reqMode == "insert")
			{
				$set->setField("LAST_CREATE_USER", "");
				$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_CREATE_SATKER", "");
				if($reqPejabatPenetapId == "")
				{
					$set_pejabat=new PejabatPenetap();
					$set_pejabat->setField('JABATAN', strtoupper($reqPejabatPenetap));
					$set_pejabat->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_pejabat->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_pejabat->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_pejabat->insert();
					$reqPejabatPenetapId=$set_pejabat->id;
					unset($set_pejabat);
				}
				if($set->insertadmin())
				{
					$triger= new TrigerCpnsPnsPangkatGajiTambahan();
					$triger->setField("PEGAWAI_ID", $reqPegawaiId);
					$triger->setField("MODE", "sk_cpns");
					$triger->setTriger();
					unset($triger);
					$reqSimpan = 1;
				}
			}
			elseif($reqMode == "update")
			{
				$set->setField("LAST_UPDATE_USER", "");
				$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_UPDATE_SATKER", "");	
				// $set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);
				$set->setField('SK_CPNS_ID',ValToNullDB($reqRowId));
				//kalau pejabat tidak ada
				if($reqPejabatPenetapId == "")
				{
					$set_pejabat=new PejabatPenetap();
					$set_pejabat->setField('JABATAN', strtoupper($reqPejabatPenetap));
					$set_pejabat->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_pejabat->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_pejabat->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_pejabat->insert();
					$reqPejabatPenetapId=$set_pejabat->id;
					unset($set_pejabat);
				}
				if($set->updateadmin())
				{
					$triger= new TrigerCpnsPnsPangkatGajiTambahan();
					$triger->setField("PEGAWAI_ID", $reqPegawaiId);
					$triger->setField("MODE", "sk_cpns");
					$triger->setTriger();
					unset($triger);
					$reqSimpan = 1 ;
				}
			}
		}

		if(!empty($fileName))
		{
			if(count($errors) === 0) 
			{
				$statement="";
				$set_upload= new SkCpns();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 8";
				$set_upload= new SkCpns();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
				$ext = strtolower($path_parts['extension']);

				$renamefile = "SK_CPNS_".$tempNipBaru.".".$ext;

				$dest_path = $urlupload . $renamefile;
				// print_r($dest_path);exit;
				if(file_exists($dest_path))
				{
					unlink($dest_path);
				}

				$statement="";

				if(move_uploaded_file($reqLinkFile, $dest_path))
				{
					$statement=" AND A.SK_CPNS_ID=".$reqRowId;
					$set_upload= new SkCpns();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE");
			
					$link_server=$dest_path;

					if(empty($tempLinkFile))
					{
						$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('SK_CPNS_ID', ValToNullDB($reqRowId));
						$set_upload->setField("LINK_FILE", $dest_path);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField("LINK_SERVER", $link_server);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$reqSimpan= 1;
					}
				}
			}
			else 
		    {
		    	foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
		    }
		}


		// echo $set->query;exit;

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsonpegawaiskpnsadd()
	{
		$this->load->model("base-validasi/SkPns");
		$this->load->model("base-validasi/PejabatPenetap");
		$this->load->model("base-validasi/TrigerCpnsPnsPangkatGajiTambahan");

		$reqMode= $this->input->post("reqMode");
		$reqPegawaiId= $this->pegawaiId;
		$reqRowId= $this->input->post("reqRowId");
		$reqTempValidasiId= $this->input->post("reqTempValidasiId");
		$reqPejabatPenetapan= $this->input->post("reqPejabatPenetapan");
		$reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
		$reqNamaPejabatPenetap= $this->input->post("reqNamaPejabatPenetap");
		$reqNIPPejabatPenetap= $this->input->post("reqNIPPejabatPenetap");
		$reqNoSuratKeputusan= $this->input->post("reqNoSuratKeputusan");
		$reqTanggalSuratKeputusan= $this->input->post("reqTanggalSuratKeputusan");
		$reqTerhitungMulaiTanggal= $this->input->post("reqTerhitungMulaiTanggal");
		$reqGolRuang= $this->input->post("reqGolRuang");
		$reqTanggalTugas= $this->input->post("reqTanggalTugas");
		$reqNoDiklatPrajabatan= $this->input->post("reqNoDiklatPrajabatan");
		$reqTanggalDiklatPrajabatan= $this->input->post("reqTanggalDiklatPrajabatan");
		$reqNoSuratUjiKesehatan= $this->input->post("reqNoSuratUjiKesehatan");
		$reqTanggalSuratUjiKesehatan= $this->input->post("reqTanggalSuratUjiKesehatan");
		$reqPengambilanSumpah= $this->input->post("reqPengambilanSumpah");
		$reqSKPNSId= $this->input->post("reqSKPNSId");
		$reqTanggalSumpah= $this->input->post("reqTanggalSumpah");
		$reqNoSKSumpah= $this->input->post("reqNoSKSumpah");
		$reqPejabatSumpah= $this->input->post("reqPejabatSumpah");
		$reqNamaPejabatPenetapSumpah= $this->input->post("reqNamaPejabatPenetapSumpah");
		$reqTh= $this->input->post("reqTh");
		$reqBl= $this->input->post("reqBl");
		$reqNoBeritaAcara= $this->input->post("reqNoBeritaAcara");
		$reqTanggalBeritaAcara= $this->input->post("reqTanggalBeritaAcara");
		// echo $reqTanggalBeritaAcara;exit;
		$reqKeteranganLPJ= $this->input->post("reqKeteranganLPJ");
		$reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");
		$reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
		$reqStatusPejabat= $this->input->post("reqStatusPejabat");
		$reqPejabatSumpahId= $this->input->post("reqPejabatSumpahId");
		$reqStatusPejabatSumpah= $this->input->post("reqStatusPejabatSumpah");
		$reqLinkFile= $_FILES["reqLinkFile"];
		$reqLinkFileBeritaAcara= $_FILES["reqLinkFileBeritaAcara"];
		$reqLinkFileSuratUjiKesehatan= $_FILES["reqLinkFileSuratUjiKesehatan"];
		$reqGajiPokok= $this->input->post("reqGajiPokok");
		$reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");
		$reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
		$reqUpload= $this->input->post("reqUpload");


		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];

		$reqLinkFileBerita=$_FILES['reqLinkFileBerita']['tmp_name'];
		$fileNameBerita = $_FILES['reqLinkFileBerita']['name'];

		$reqLinkFileSurat=$_FILES['reqLinkFileSurat']['tmp_name'];
		$fileNameSurat = $_FILES['reqLinkFileSurat']['name'];

		$reqLinkFileSpmt=$_FILES['reqLinkFileSpmt']['tmp_name'];
		$fileNameSpmt = $_FILES['reqLinkFileSpmt']['name'];

		// print_r($reqLinkFileSurat);exit;

		$set= new SkPns();
		$set->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId));	
		$set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
		$set->setField('PEJABAT_PENETAP_SUMPAH_ID', ValToNullDB($reqPejabatSumpahId));	
		$set->setField('PEJABAT_PENETAP_SUMPAH', strtoupper($reqPejabatSumpah));
		$set->setField('PANGKAT_ID', ValToNullDB($reqGolRuang));
		$set->setField('TMT_PNS', dateToDBCheck($reqTerhitungMulaiTanggal));
		$set->setField('TANGGAL_TUGAS', dateToDBCheck($reqTanggalTugas)); 
		$set->setField('NO_STTPP', '');
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('NO_NOTA', $reqNoNotaBAKN);
		$set->setField('TANGGAL_NOTA', dateToDBCheck($reqTanggalNotaBAKN));
		$set->setField('NO_SK', $reqNoSuratKeputusan);
		$set->setField('TANGGAL_STTPP', dateToDBCheck($reqTanggalTugas));
		$set->setField('NAMA_PENETAP', $reqNamaPejabatPenetap);		
		$set->setField('TANGGAL_SK', dateToDBCheck($reqTanggalSuratKeputusan));
		$set->setField('NIP_PENETAP', $reqNIPPejabatPenetap);
		$set->setField('NO_PRAJAB',$reqNoDiklatPrajabatan);
		$set->setField('NO_UJI_KESEHATAN',$reqNoSuratUjiKesehatan);
		$set->setField('TANGGAL_UJI_KESEHATAN', dateToDBCheck($reqTanggalSuratUjiKesehatan));
		$set->setField('TANGGAL_PRAJAB', dateToDBCheck($reqTanggalDiklatPrajabatan));
		$set->setField('TANGGAL_SUMPAH', dateToDBCheck($reqTanggalSumpah));
		$set->setField('NO_SK_SUMPAH', $reqNoSKSumpah);		
		$set->setField('SUMPAH', ValToNullDB((int)$reqPengambilanSumpah));
		$set->setField('MASA_KERJA_TAHUN', ValToNullDB($reqTh));
		$set->setField('MASA_KERJA_BULAN', ValToNullDB($reqBl));
		$set->setField('USER_APP_ID', $userLogin->UID);
		$set->setField('NOMOR_BERITA_ACARA',$reqNoBeritaAcara);
		$set->setField('TANGGAL_BERITA_ACARA', dateToDBCheck($reqTanggalBeritaAcara));
		$set->setField('KETERANGAN_LPJ',$reqKeteranganLPJ);
		$set->setField('GAJI_POKOK', ValToNullDB(dotToNo($reqGajiPokok)));
		$reqSimpan="";
		if($reqUpload==1)
		{
			$reqSimpan= 1;
		}
		else
		{
			if($reqMode == "insert")
			{
				$set->setField("LAST_CREATE_USER", "");
				$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_CREATE_SATKER", "");
				$set->setField('SK_PNS_ID',ValToNullDB($reqRowId));
				if($reqPejabatPenetapId == "")
				{
					$set_pejabat=new PejabatPenetap();
					$set_pejabat->setField('JABATAN', strtoupper($reqPejabatPenetap));
					$set_pejabat->setField("LAST_CREATE_USER", "");
					$set_pejabat->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_pejabat->setField("LAST_CREATE_SATKER", "");
					$set_pejabat->insert();
					$reqPejabatPenetapId=$set_pejabat->id;
					unset($set_pejabat);
				}
				if($set->insertadmin())
				{
					$triger= new TrigerCpnsPnsPangkatGajiTambahan();
					$triger->setField("PEGAWAI_ID", $reqPegawaiId);
					$triger->setField("MODE", "sk_pns");
					$triger->setTriger();
					unset($triger);
					$reqSimpan = 1;
				}
			}
			elseif($reqMode == "update")
			{
				$set->setField("LAST_UPDATE_USER", "");
				$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_UPDATE_SATKER", "");	
				// $set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);
				$set->setField('SK_PNS_ID',ValToNullDB($reqRowId));
				//kalau pejabat tidak ada
				if($reqPejabatPenetapId == "")
				{
					$set_pejabat=new PejabatPenetap();
					$set_pejabat->setField('JABATAN', strtoupper($reqPejabatPenetap));
					$set_pejabat->setField("LAST_CREATE_USER", "");
					$set_pejabat->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_pejabat->setField("LAST_CREATE_SATKER", "");
					$set_pejabat->insert();
					$reqPejabatPenetapId=$set_pejabat->id;
					unset($set_pejabat);
				}
				if($set->updateadmin())
				{
					$triger= new TrigerCpnsPnsPangkatGajiTambahan();
					$triger->setField("PEGAWAI_ID", $reqPegawaiId);
					$triger->setField("MODE", "sk_pns");
					$triger->setTriger();
					unset($triger);
					$reqSimpan = 1 ;
				}
			}
		}

				if(!empty($fileName) || !empty($fileNameBerita) || !empty($fileNameSurat) || !empty($fileNameSpmt))
		{
			if(count($errors) === 0) 
			{
				$statement="";
				$set_upload= new SkPns();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 9";
				$set_upload= new SkPns();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
				$ext = strtolower($path_parts['extension']);

				$path_parts = pathinfo($_FILES["reqLinkFileBerita"]["name"]);
				$extberita = strtolower($path_parts['extension']);

				$path_parts = pathinfo($_FILES["reqLinkFileSurat"]["name"]);
				$extsurat = strtolower($path_parts['extension']);

				$path_parts = pathinfo($_FILES["reqLinkFileSpmt"]["name"]);
				$extspmt = strtolower($path_parts['extension']);

				$renamefile = "SK_PNS_".$tempNipBaru.".".$ext;
				$renamefileBerita = "SK_PNS_BERITA_".$tempNipBaru.".".$extberita;
				$renamefileSurat = "SK_PNS_SURAT_".$tempNipBaru.".".$extsurat;
				$renamefileSpmt = "SK_PNS_SPMT_".$tempNipBaru.".".$extspmt;

				$dest_path = $urlupload . $renamefile;
				$dest_path_berita = $urlupload . $renamefileBerita;
				$dest_path_surat = $urlupload . $renamefileSurat;
				$dest_path_spmt = $urlupload . $renamefileSpmt;
				// print_r($reqLinkFile);exit;
				if(file_exists($dest_path))
				{
					unlink($dest_path);
				}
				elseif(file_exists($dest_path_berita))
				{
					unlink($dest_path_berita);
				}
				elseif(file_exists($dest_path_surat))
				{
					unlink($dest_path_surat);
				}
				elseif(file_exists($dest_path_spmt))
				{
					unlink($dest_path_spmt);
				}


				$statement="";


				if(move_uploaded_file($reqLinkFile, $dest_path))
				{
					$statement=" AND A.SK_PNS_ID=".$reqRowId;
					$set_upload= new SkPns();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE");
					$skpnsfileid=$set_upload->getField("SK_PNS_FILE_ID");
					
					$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_upload->setField('SK_PNS_ID', ValToNullDB($reqRowId));
					$set_upload->setField("LINK_FILE", $dest_path);
					$set_upload->setField("NAMA_FILE", $renamefile);
					$set_upload->setField("TIPE_FILE", $ext);
					$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
					if(empty($skpnsfileid))
					{
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$set_upload->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('SK_PNS_FILE_ID', $skpnsfileid);
						if($set_upload->updateupload())
						{
							$reqSimpan= 1;
						}
					}
				}

				if(move_uploaded_file($reqLinkFileBerita, $dest_path_berita))
				{
					$statement=" AND A.SK_PNS_ID=".$reqRowId;
					$set_upload= new SkPns();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE_BERITA");
					$skpnsfileid=$set_upload->getField("SK_PNS_FILE_ID");
					
					$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_upload->setField('SK_PNS_ID', ValToNullDB($reqRowId));
					$set_upload->setField("LINK_FILE_BERITA", $dest_path_berita);
					$set_upload->setField("NAMA_FILE_BERITA", $renamefileBerita);
					$set_upload->setField("TIPE_FILE", $ext);
					$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
					if(empty($skpnsfileid) && empty($tempLinkFile) )
					{
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$set_upload->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('SK_PNS_FILE_ID', $skpnsfileid);
						if($set_upload->updateuploadberita())
						{
							$reqSimpan= 1;
						}
					}
				}

				if(move_uploaded_file($reqLinkFileSurat, $dest_path_surat) )
				{
					$statement=" AND A.SK_PNS_ID=".$reqRowId;
					$set_upload= new SkPns();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE");
					$skpnsfileid=$set_upload->getField("SK_PNS_FILE_ID");
					
					$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_upload->setField('SK_PNS_ID', ValToNullDB($reqRowId));
					$set_upload->setField("LINK_FILE_SURAT", $dest_path_surat);
					$set_upload->setField("NAMA_FILE_SURAT", $renamefileSurat);
					$set_upload->setField("TIPE_FILE", $ext);
					$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
					if(empty($skpnsfileid))
					{
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$set_upload->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('SK_PNS_FILE_ID', $skpnsfileid);
						if($set_upload->updateuploadsurat())
						{
							$reqSimpan= 1;
						}
					}
				}

				if(move_uploaded_file($reqLinkFileSpmt, $dest_path_spmt))
				{
					$statement=" AND A.SK_PNS_ID=".$reqRowId;
					$set_upload= new SkPns();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE");
					$skpnsfileid=$set_upload->getField("SK_PNS_FILE_ID");
					
					$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_upload->setField('SK_PNS_ID', ValToNullDB($reqRowId));
					$set_upload->setField("LINK_FILE_SPMT", $dest_path_spmt);
					$set_upload->setField("NAMA_FILE_SPMT", $renamefileSpmt);
					$set_upload->setField("TIPE_FILE", $ext);
					$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
					if(empty($skpnsfileid))
					{
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$set_upload->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('SK_PNS_FILE_ID', $skpnsfileid);
						if($set_upload->updateuploadsmpt())
						{
							$reqSimpan= 1;
						}
					}
				}
				
			}
			else 
		    {
		    	foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
		    }
		}
		// echo $set->query;exit;;

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsonpegawaipangkatriwayatadd()
	{
		$this->load->model("base-validasi/PangkatRiwayat");
		$this->load->model("base-validasi/TrigerCpnsPnsPangkatGajiTambahan");

		$reqMode= $this->input->post("reqMode");
		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqRowId= $this->input->post("reqRowId");
		$reqDataId= $this->input->post("reqDataId");
		$reqSTLUD= $this->input->post("reqSTLUD");
		$reqNoSTLUD= $this->input->post("reqNoSTLUD");
		$reqTglSTLUD= $this->input->post("reqTglSTLUD");
		$reqTMTGol= $this->input->post("reqTMTGol");
		$reqNoNota= $this->input->post("reqNoNota");
		$reqTglNota= $this->input->post("reqTglNota");
		$reqJenisKP= $this->input->post("reqJenisKP");
		$reqKredit= $this->input->post("reqKredit");
		$reqKeterangan= $this->input->post("reqKeterangan");
		$reqNoSK= $this->input->post("reqNoSK");
		$reqTglSK= $this->input->post("reqTglSK");
		$reqGolRuang= $this->input->post("reqGolRuang");
		$reqTMTSK= $this->input->post("reqTMTSK");
		$reqPjPenetap= $this->input->post("reqPjPenetap");
		$reqStatusPejabatPenetap= $this->input->post("reqStatusPejabatPenetap");
		$reqPjPenetap_Baru= $this->input->post("reqPjPenetap_Baru");
		$reqTh= $this->input->post("reqTh");
		$reqBl= $this->input->post("reqBl");
		$reqGajiPokok= $this->input->post("reqGajiPokok");
		$reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");
		$reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
		$reqStatusPejabat= $this->input->post("reqStatusPejabat");
		$reqTMTGolLama= $this->input->post("reqTMTGolLama");
		$reqTglSKLama= $this->input->post("reqTglSKLama");
		$reqGolRuangTemp= $this->input->post("reqGolRuangTemp");
		$reqUpload= $this->input->post("reqUpload");

		$reqLinkFileSk=$_FILES['reqLinkFileSk']['tmp_name'];
		$fileNameSk = $_FILES['reqLinkFileSk']['name'];

		$reqLinkFileStlud=$_FILES['reqLinkFileStlud']['tmp_name'];
		$fileNameSTLUD = $_FILES['reqLinkFileStlud']['name'];

		if(!empty($fileNameSk) || !empty($fileNameSTLUD) )
		{
			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFileSk']['size'] >= $maxsize) || ($_FILES['reqLinkFileStlud']['size'] >= $maxsize) ) 
			{
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if(count($errors) > 0) 
			{
				foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
			}
			
		}

		$set = new PangkatRiwayat();
		$set->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId));	
		$set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
		$set->setField('PANGKAT_RIWAYAT_ID', ValToNullDB($reqRowId));
		$set->setField('PANGKAT_ID', ValToNullDB($reqGolRuang));
		$set->setField('STLUD', $reqSTLUD);
		$set->setField('NO_STLUD', $reqNoSTLUD);
		$set->setField('NO_NOTA', $reqNoNota);
		$set->setField('NO_SK', $reqNoSK);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('MASA_KERJA_TAHUN', ValToNullDB($reqTh));
		$set->setField('MASA_KERJA_BULAN', ValToNullDB($reqBl));
		$set->setField('GAJI_POKOK', ValToNullDB(dotToNo($reqGajiPokok)));
		$set->setField('KREDIT', ValToNullDB(commaToDot($reqKredit)));
		$set->setField('KETERANGAN', $reqKeterangan);
		$set->setField('JENIS_KP', ValToNullDB($reqJenisKP));
		$set->setField('KETERANGAN', $reqKeterangan);	
		$set->setField('TANGGAL_STLUD', dateToDBCheck($reqTglSTLUD));
		$set->setField('TANGGAL_NOTA', dateToDBCheck($reqTglNota));
		$set->setField('TANGGAL_SK', dateToDBCheck($reqTglSK));
		$set->setField('TMT_PANGKAT', dateToDBCheck($reqTMTGol));
		$set->setField('USER_APP_ID', "");
		$set->setField('TEMP_VALIDASI_ID', $reqDataId);



		$reqSimpan= "";
		if($reqUpload==1)
		{
			$reqSimpan= 1;
		}
		else
		{
			if($reqMode == "insert")
			{
				$set->setField("LAST_CREATE_USER", "");
				$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_CREATE_SATKER", "");	

				if($set->insertadmin())
				{
					$triger= new TrigerCpnsPnsPangkatGajiTambahan();
					$triger->setField("PEGAWAI_ID", $reqPegawaiId);
					if($reqJenisKP == "9")
						$triger->setField("MODE", "pangkat_riwayat_sk_cpns");
					else
						$triger->setField("MODE", "pangkat_riwayat_sk_pns");
					$triger->setTriger();
				//echo $triger->query;exit;
					unset($triger);
					$reqRowId = $set->id;
					$reqSimpan= 1;
				}
			}
			elseif($reqMode == "update")
			{	
				$set->setField("LAST_UPDATE_USER", "");
				$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_UPDATE_SATKER", "");
				$set->setField('PANGKAT_ID_LAMA', $reqGolRuangTemp);
				$set->setField('TANGGAL_SK_LAMA', dateToDBCheck($reqTglSKLama));
				$set->setField('TMT_PANGKAT_LAMA', dateToDBCheck($reqTMTGolLama));
				if($set->updateadmin())
				{
					$triger= new TrigerCpnsPnsPangkatGajiTambahan();
					$triger->setField("PEGAWAI_ID", $reqPegawaiId);
					if($reqJenisKP == "9")
						$triger->setField("MODE", "pangkat_riwayat_sk_cpns");
					else
						$triger->setField("MODE", "pangkat_riwayat_sk_pns");
					$triger->setTriger();
				//echo $triger->query;exit;
					unset($triger);
					$reqSimpan= 1;
				}
			}

		}

		if(!empty($fileNameSk) || !empty($fileNameSTLUD) )
			{
				if(count($errors) === 0) 
				{
					$statement="";
					$set_upload= new PangkatRiwayat();
					$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					$set_upload->firstRow();
					$tempNipBaru=$set_upload->getField("NIP_BARU");
					unset($set_upload);

					$statement=" AND LINK_SERVER_ID = 16";
					$set_upload= new PangkatRiwayat();
					$set_upload->selectByParamsServer(array(),-1,-1,$statement);
						// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkServer=$set_upload->getField("LINK_SERVER");
					$tempLinkFolder=$set_upload->getField("FOLDER");

					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";

					if (!is_dir($urlupload)) {
						makedirs($urlupload);
					}
					
					$ext = "pdf";

					$renamefileSk = "SK_".$tempNipBaru.".".$ext;
					$renamefileStlud = "STLUD_".$tempNipBaru.".".$ext;
					

					$dest_path_sk = $urlupload . $renamefileSk;
					$dest_path_stlud = $urlupload . $renamefileStlud;
					
					if (!empty($fileNameSk))
					{
						if(file_exists($dest_path_sk))
						{
							unlink($dest_path_sk);
						}

					}
					else if (!empty($fileNameSTLUD))
					{
						if(file_exists($dest_path_stlud))
						{
							unlink($dest_path_stlud);
						}
					}

					$statement="";


					if(move_uploaded_file($reqLinkFileSk,$dest_path_sk))
					{
						$statement=" AND A.PANGKAT_RIWAYAT_ID=".$reqRowId;
						$set_upload= new PangkatRiwayat();
						$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
						// echo $set_upload->query;exit;
						$set_upload->firstRow();
						$tempLinkFile=$set_upload->getField("LINK_FILE_SK");
						$fileid=$set_upload->getField("PANGKAT_RIWAYAT_FILE_ID");
						
						$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField("LINK_FILE_SK", $dest_path_sk);
						$set_upload->setField("NAMA_FILE_SK", $renamefileSk);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
						$set_upload->setField('PANGKAT_RIWAYAT_ID', $reqRowId);


						$nama="NAMA_FILE_SK";
						$link="LINK_FILE_SK";
						if(empty($fileid) && empty($tempLinkFile))
						{
							if($set_upload->insertupload())
							{
								$reqSimpan= 1;
							}
						}
						else
						{
							$set_upload->setField("LAST_UPDATE_USER", $userLogin->idUser);
							$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
							$set_upload->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
							$set_upload->setField('PANGKAT_RIWAYAT_FILE_ID', $fileid);
							if($set_upload->updateupload($nama,$link))
							{
								$reqSimpan= 1;
							}
						}
					}

					if(move_uploaded_file($reqLinkFileStlud, $dest_path_stlud))
					{
						$statement=" AND A.PANGKAT_RIWAYAT_ID=".$reqRowId;
						$set_upload= new PangkatRiwayat();
						$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
						// echo $set_upload->query;exit;
						$set_upload->firstRow();
						$tempLinkFile=$set_upload->getField("LINK_FILE_STLUD");
						$fileid=$set_upload->getField("PANGKAT_RIWAYAT_FILE_ID");
						
						$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField("LINK_FILE_STLUD", $dest_path_stlud);
						$set_upload->setField("NAMA_FILE_STLUD", $renamefileStlud);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PANGKAT_RIWAYAT_ID', $reqRowId);
						$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);

						$nama="NAMA_FILE_STLUD";
						$link="LINK_FILE_STLUD";
						if(empty($fileid) && empty($tempLinkFile))
						{
							if($set_upload->insertupload())
							{
								$reqSimpan= 1;
							}
						}
						else
						{
							$set_upload->setField("LAST_UPDATE_USER", $userLogin->idUser);
							$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
							$set_upload->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
							$set_upload->setField('PANGKAT_RIWAYAT_FILE_ID', $fileid);
							if($set_upload->updateupload($nama,$link))
							{
								$reqSimpan= 1;
							}
						}
					}

				
				}
				else 
			    {
			    	foreach($errors as $error) {
			    		echo json_response(400, $error);
			    	}
			    	exit;
			    }
			}
		

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsonpegawaijabatanriwayatadd()
	{
		$this->load->model("base-validasi/JabatanRiwayat");

		$set = new JabatanRiwayat();

		$reqMode= $this->input->post("reqMode");
		// echo $reqMode;exit;
		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqRowId= $this->input->post("reqRowId");
		$reqDataId= $this->input->post("reqDataId");
		$reqNoSK= $this->input->post("reqNoSK");
		$reqTglSK= $this->input->post("reqTglSK");
		$reqNamaJabatan= $this->input->post("reqNamaJabatan");
		// echo $reqNamaJabatan;;exit;
		$reqTMTJabatan= $this->input->post("reqTMTJabatan");
		$reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
		$reqStatusPejabatPenetap= $this->input->post("reqStatusPejabatPenetap");
		$reqPejabatPenetap_Baru= $this->input->post("reqPejabatPenetap_Baru");
		$reqEselon= $this->input->post("reqEselon");
		$reqTMTEselon= $this->input->post("reqTMTEselon");
		$reqTMTJabatanFungsional= $this->input->post("reqTMTJabatanFungsional");
		$reqTMTTugasTambahan= $this->input->post("reqTMTTugasTambahan");
		$reqTglSKBUP= $this->input->post("reqTglSKBUP");
		$reqTMTBatasUsiaPensiun= $this->input->post("reqTMTBatasUsiaPensiun");
		$reqKeteranganBUP= $this->input->post("reqKeteranganBUP");
		$reqNoPelantikan= $this->input->post("reqNoPelantikan");    
		$reqTglPelantikan= $this->input->post("reqTglPelantikan");
		$reqTunjangan= $this->input->post("reqTunjangan");
		$reqBlnDibayar= $this->input->post("reqBlnDibayar");
		$reqSatkerId= $this->input->post("reqSatkerId");
		$reqTipePegawaiId= $this->input->post("reqTipePegawaiId");
		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");
		$reqStatusPejabat= $this->input->post("reqStatusPejabat");
		$reqLinkFile= $_FILES["reqLinkFile"];
		$reqLinkFilePelantikan= $_FILES["reqLinkFilePelantikan"];
		$reqLinkFileMenduduki= $_FILES["reqLinkFileMenduduki"];
		$reqLinkFileTugas= $_FILES["reqLinkFileTugas"];
		$reqLinkFileTugasTambahan= $_FILES["reqLinkFileTugasTambahan"];
		$reqRowTipePegawaiId= $this->input->post("reqRowTipePegawaiId");

		if($reqRowTipePegawaiId == "11")
		{
			$reqTMTEselon= $reqTMTJabatan;
		}
		else
		{
			$reqTMTEselon= $reqEselon= "";
		}
		$checking_pegawai="";
		if($reqTipePegawaiId == 1)
		{
			$jabatan_cek = new JabatanRiwayat();
			$checking_pegawai = $jabatan_cek->getCountByParamsJabatanTerakhir(array("SATKER_ID" => $reqSatkerId, 'NOT PEGAWAI_ID'=>$reqPegawaiId));
		}
		$reqSimpan =""; 
		if($checking_pegawai == ""){
			$set->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId));	
			$set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
			if($reqTipePegawaiId == 1)
			{
				$set->setField('SATKER_ID', $reqSatkerId);	
				$set->setField('JABATAN_FUNGSIONAL_ID', ValToNullDB($req));	
			}
			else
			{
				$set->setField('SATKER_ID', '');	
				$set->setField('JABATAN_FUNGSIONAL_ID', ValToNullDB($reqSatkerId));
			}

			$set->setField('NO_SK', $reqNoSK);
			$set->setField('ESELON_ID', ValToNullDB($reqEselon));
			$set->setField('NAMA', $reqNamaJabatan);
			$set->setField('NO_PELANTIKAN', $reqNoPelantikan);
			$set->setField('TUNJANGAN', ValToNullDB($reqTunjangan));
			$set->setField('KREDIT', ValToNullDB($req));
			$set->setField('SUDAH_DIBAYAR', ValToNullDB($req));
			$set->setField('TANGGAL_SK', dateToDBCheck($reqTglSK));
			$set->setField('TMT_JABATAN', dateToDBCheck($reqTMTJabatan));
			$set->setField('TMT_ESELON', dateToDBCheck($reqTMTEselon));
			$set->setField('TANGGAL_PELANTIKAN', dateToDBCheck($reqTglPelantikan));
			$set->setField('BULAN_DIBAYAR', dateToDBCheck($reqBlnDibayar));
			$set->setField('TMT_JABATAN_FUNGSIONAL', dateToDBCheck($reqTMTJabatanFungsional));
			$set->setField('TMT_TUGAS_TAMBAHAN', dateToDBCheck($reqTMTTugasTambahan));
			$set->setField('TGL_SK_PERPANJANGAN_BUP', dateToDBCheck($reqTglSKBUP));
			$set->setField('TMT_BATAS_USIA_PENSIUN', dateToDBCheck($reqTMTBatasUsiaPensiun));
			$set->setField('PEGAWAI_ID', $reqPegawaiId);
			$set->setField('USER_APP_ID', $userLogin->UID);
			$set->setField('KETERANGAN_BUP', $reqKeteranganBUP);

			if($reqMode == "insert")
			{
				$set->setField("LAST_CREATE_USER", $userLogin->idUser);
				$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
				$set->setField('JABATAN_RIWAYAT_ID', ValToNullDB($reqRowId));

				if($set->insertadmin())
				{
					$reqSimpan = 1; 
				}
			}
			elseif ($reqMode == "update") 
			{
				$set->setField('JABATAN_RIWAYAT_ID', $reqRowId);
				$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
				$set->setField('TEMP_VALIDASI_ID', $reqDataId);
				if($set->updateadmin())
				{
					$reqSimpan = 1 ;
				}
			}

		}
		else
		{
			echo json_response(400, "'Jabatan telah diduduki : ".$checking_pegawai.".'");
		}

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}
	function jsonriwayatgajiadd()
	{
		$this->load->model("base-validasi/GajiRiwayat");
		$this->load->model("base-validasi/TrigerCpnsPnsPangkatGajiTambahan");

		$set = new GajiRiwayat();

		$reqMode= $this->input->post("reqMode");
		$reqMode= $this->input->post("reqMode");
		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqRowId= $this->input->post("reqRowId");
		$reqDataId= $this->input->post("reqDataId");
		$reqNoSK= $this->input->post("reqNoSK");
		$reqTglSK= $this->input->post("reqTglSK");
		$reqGolRuang= $this->input->post("reqGolRuang");
		$reqTMTSK= $this->input->post("reqTMTSK");
		$reqPjPenetap= $this->input->post("reqPjPenetap");
		$reqStatusPejabatPenetap= $this->input->post("reqStatusPejabatPenetap");
		$reqPjPenetap_Baru= $this->input->post("reqPjPenetap_Baru");
		$reqTh= $this->input->post("reqTh");
		$reqBl= $this->input->post("reqBl");
		$reqGajiPokok= $this->input->post("reqGajiPokok");
		$reqJenis= $this->input->post("reqJenis");
		$reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");
		$reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
		$reqStatusPejabat= $this->input->post("reqStatusPejabat");
		$reqLinkFile = $_FILES["reqLinkFile"];
		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];

		if(!empty($fileName))
		{
			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFile']['size'] >= $maxsize) || ($_FILES["reqLinkFile"]["size"] == 0)) {
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if((!in_array($_FILES['reqLinkFile']['type'], $acceptable)) && (!empty($_FILES["reqLinkFile"]["type"]))) {
				$errors[] = 'File gagal diupload, Pastikan File berformat PDF';
			}

			if(count($errors) > 0) 
			{
				foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
			}
			
		}
		$reqSimpan =""; 
		
		$set->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId));	
		$set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
		$set->setField('JENIS_KENAIKAN', ValToNullDB($reqJenis));
		$set->setField('SUDAH_DIBAYAR', ValToNullDB($req));
		$set->setField('POTONGAN_PANGKAT', ValToNullDB($req));
		$set->setField('NO_SK', $reqNoSK);	
		$set->setField('PANGKAT_ID', ValToNullDB($reqGolRuang));
		$set->setField('TANGGAL_SK', dateToDBCheck($reqTglSK));
		$set->setField('GAJI_POKOK', ValToNullDB(dotToNo($reqGajiPokok)));
		$set->setField('MASA_KERJA_TAHUN', ValToNullDB($reqTh));
		$set->setField('MASA_KERJA_BULAN', ValToNullDB($reqBl));
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('TMT_SK', dateToDBCheck($reqTMTSK));
		$set->setField('BULAN_DIBAYAR', dateToDBCheck($reqTglSK));
		/*$set->setField('PEKERJAAN', $reqPekerjaan);	
		$set->setField('AKHIR_BAYAR', dateToDBCheck($reqAkhirDibayar));*/
		$set->setField('USER_APP_ID', $userLogin->UID);
		
		if($reqMode == "insert")
		{
			$set->setField("LAST_CREATE_USER", $userLogin->idUser);
			$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
			$set->setField('GAJI_RIWAYAT_ID', ValToNullDB($reqRowId));

			if($set->insertadmin())
			{
				if($reqJenis == "3" || $reqJenis == "4" || $reqJenis == "5")
				{
					$triger= new TrigerCpnsPnsPangkatGajiTambahan();
					$triger->setField("PEGAWAI_ID", $reqPegawaiId);
					if($reqJenis == "3")
						$triger->setField("MODE", "gaji_riwayat_sk_cpns");
					elseif($reqJenis == "4")
						$triger->setField("MODE", "gaji_riwayat_sk_pns");
					else
						$triger->setField("MODE", "gaji_riwayat_tambahan");
					$triger->setTriger();
					//echo $triger->query;exit;
					unset($triger);
				}
				$reqDataId = $set->id;
				$reqSimpan = 1; 
			}
		}
		elseif ($reqMode == "update") 
		{
			$set->setField('GAJI_RIWAYAT_ID', $reqRowId);
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
			$set->setField('TEMP_VALIDASI_ID', $reqDataId);
			if($set->updateadmin())
			{
				if($reqJenis == "3" || $reqJenis == "4" || $reqJenis == "5")
				{
					$triger= new TrigerCpnsPnsPangkatGajiTambahan();
					$triger->setField("PEGAWAI_ID", $reqPegawaiId);
					if($reqJenis == "3")
						$triger->setField("MODE", "gaji_riwayat_sk_cpns");
					elseif($reqJenis == "4")
						$triger->setField("MODE", "gaji_riwayat_sk_pns");
					else
						$triger->setField("MODE", "gaji_riwayat_tambahan");
					$triger->setTriger();
					//echo $triger->query;exit;
					unset($triger);
				}
				$reqSimpan = 1 ;
			}
		}

		if(!empty($fileName))
		{
			if(count($errors) === 0) 
			{

				$statement="";
				$set_upload= new GajiRiwayat();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 12";
				$set_upload= new GajiRiwayat();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				if($reqDataId == "")
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";
				}
				else
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqDataId."\\";
				}
			

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
				$ext = strtolower($path_parts['extension']);

				$renamefile = "GAJI_RIWAYAT_".$tempNipBaru.".".$ext;

				$dest_path = $urlupload . $renamefile;
				// print_r($dest_path);exit;
				if(file_exists($dest_path))
				{
					unlink($dest_path);
				}

				$reqSimpan="";
				$statement="";

				if(move_uploaded_file($reqLinkFile, $dest_path))
				{
					if($reqDataId == "")
					{
						$statement=" AND A.GAJI_RIWAYAT_ID=".$reqRowId;
					}
					else
					{
						$statement=" AND A.TEMP_VALIDASI_ID=".$reqDataId;
					}

					$set_upload= new GajiRiwayat();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE");
			
					$link_server=$dest_path;

					if(empty($tempLinkFile))
					{
						$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDataId));
						$set_upload->setField('GAJI_RIWAYAT_ID', ValToNullDB($reqRowId));
						$set_upload->setField("LINK_FILE", $dest_path);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$reqSimpan= 1;
					}
				}
			}
		}

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsonpendidikanumumadd()
	{
		$this->load->model("base-validasi/PendidikanRiwayat");
		$this->load->model("base-validasi/JurusanPendidikan");

		$reqMode= $this->input->post("reqMode");
		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqRowId= $this->input->post("reqRowId");
		$reqDataId= $this->input->post("reqDataId");
		$reqPendidikan= $this->input->post("reqPendidikan");
		$reqJurusan= $this->input->post("reqJurusan");
		$reqJurusanId= $this->input->post("reqJurusanId");
		$reqNamaSekolah= $this->input->post("reqNamaSekolah");
		$reqAlamatSekolah= $this->input->post("reqAlamatSekolah");
		$reqKepalaSekolah= $this->input->post("reqKepalaSekolah");
		$reqNoSTTB= $this->input->post("reqNoSTTB");
		$reqTglSTTB= $this->input->post("reqTglSTTB");

		$set = new PendidikanRiwayat();
		$set->setField('KEPALA', setQuote($reqKepalaSekolah,1));
		$set->setField('NAMA', setQuote($reqNamaSekolah,1));
		$set->setField('PENDIDIKAN_ID', $reqPendidikan);
		$set->setField('TANGGAL_STTB', dateToDBCheck($reqTglSTTB));
		$set->setField('JURUSAN', $reqJurusan);
		$set->setField('JURUSAN_PENDIDIKAN_ID', ValToNullDB($reqJurusanId));
		$set->setField('TEMPAT', $reqAlamatSekolah);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('NO_STTB', $reqNoSTTB);
		$set->setField('USER_APP_ID', $userLogin->UID);
		
		$reqSimpan= "";
		if($reqMode == "insert")
		{
			$set->setField("LAST_CREATE_USER", $userLogin->idUser);
			$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
			$set->setField('PENDIDIKAN_RIWAYAT_ID', ValToNullDB($reqRowId));	

			if($set->insertadmin())
			{
				$reqSimpan= 1;
			}
		}
		elseif($reqMode == "update")
		{	
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);	
			$set->setField('TEMP_VALIDASI_ID', $reqDataId);
			$set->setField('PENDIDIKAN_RIWAYAT_ID', $reqRowId);
			if($set->updateadmin())
			{
				$reqSimpan= 1;
			}
		}

		// echo $set->query;exit;

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsondiklatstrukturaladd()
	{
		$this->load->model("base-validasi/DiklatStruktural");

		$reqMode= $this->input->post("reqMode");
		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqRowId= $this->input->post("reqRowId");
		$reqDataId= $this->input->post("reqDataId");
		$reqDiklat= $this->input->post("reqDiklat");
		$reqTempat= $this->input->post("reqTempat");
		$reqPenyelenggara= $this->input->post("reqPenyelenggara");
		$reqAngkatan= $this->input->post("reqAngkatan");
		$reqTahun= $this->input->post("reqTahun");
		$reqNoSTTPP= $this->input->post("reqNoSTTPP");
		$reqTglMulai= $this->input->post("reqTglMulai");
		$reqTglSTTPP= $this->input->post("reqTglSTTPP");
		$reqTglSelesai= $this->input->post("reqTglSelesai");
		$reqJumlahJam= $this->input->post("reqJumlahJam"); 

		$set = new DiklatStruktural();
		$set->setField('TAHUN', ValToNullDB($reqTahun));
		$set->setField('DIKLAT_ID', $reqDiklat);
		$set->setField('NO_STTPP', $reqNoSTTPP);
		$set->setField('TANGGAL_MULAI', dateToDBCheck($reqTglMulai));
		$set->setField('TANGGAL_SELESAI', dateToDBCheck($reqTglSelesai));
		$set->setField('TANGGAL_STTPP', dateToDBCheck($reqTglSTTPP));
		$set->setField('PENYELENGGARA', $reqPenyelenggara);
		$set->setField('ANGKATAN', $reqAngkatan);
		$set->setField('TEMPAT', $reqTempat);
		$set->setField('JUMLAH_JAM', ValToNullDB($reqJumlahJam));
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('USER_APP_ID', $userLogin->UID);
		
		$reqSimpan= "";
		if($reqMode == "insert")
		{
			$set->setField("LAST_CREATE_USER", $userLogin->idUser);
			$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
			$set->setField('DIKLAT_STRUKTURAL_ID', ValToNullDB($reqRowId));

			if($set->insert())
			{
				$reqSimpan= 1;
			}
		}
		elseif($reqMode == "update")
		{	
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
			$set->setField('TEMP_VALIDASI_ID', $reqDataId);
			$set->setField('DIKLAT_STRUKTURAL_ID', $reqRowId);
			if($set->update())
			{
				$reqSimpan= 1;
			}
		}

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsondiklatfungsionaladd()
	{
		$this->load->model("base-validasi/DiklatFungsional");

		$reqMode= $this->input->post("reqMode");
		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqRowId= $this->input->post("reqRowId");
		$reqDataId= $this->input->post("reqDataId");
		$reqNamaDiklat= $this->input->post("reqNamaDiklat");
		$reqTempat= $this->input->post("reqTempat");
		$reqPenyelenggara= $this->input->post("reqPenyelenggara");
		$reqAngkatan= $this->input->post("reqAngkatan");
		$reqTahun= $this->input->post("reqTahun");
		$reqNoSTTPP= $this->input->post("reqNoSTTPP");
		$reqTglMulai= $this->input->post("reqTglMulai");
		$reqTglSTTPP= $this->input->post("reqTglSTTPP");
		$reqTglSelesai= $this->input->post("reqTglSelesai");
		$reqJumlahJam= $this->input->post("reqJumlahJam"); 

		$set = new DiklatFungsional();
		$set->setField('TAHUN', ValToNullDB($reqTahun));
		$set->setField('NAMA', $reqNamaDiklat);
		$set->setField('NO_STTPP', $reqNoSTTPP);
		$set->setField('TANGGAL_MULAI', dateToDBCheck($reqTglMulai));
		$set->setField('TANGGAL_SELESAI', dateToDBCheck($reqTglSelesai));
		$set->setField('TANGGAL_STTPP', dateToDBCheck($reqTglSTTPP));
		$set->setField('PENYELENGGARA', $reqPenyelenggara);
		$set->setField('ANGKATAN', $reqAngkatan);
		$set->setField('TEMPAT', $reqTempat);
		$set->setField('JUMLAH_JAM', ValToNullDB($reqJumlahJam));
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('USER_APP_ID', $userLogin->UID);
		
		$reqSimpan= "";
		if($reqMode == "insert")
		{
			$set->setField("LAST_CREATE_USER", $userLogin->idUser);
			$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
			$set->setField('DIKLAT_FUNGSIONAL_ID', ValToNullDB($reqRowId));

			if($set->insert())
			{
				$reqSimpan= 1;
			}
		}
		elseif($reqMode == "update")
		{	
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
			$set->setField('TEMP_VALIDASI_ID', $reqDataId);
			$set->setField('DIKLAT_FUNGSIONAL_ID', $reqRowId);
			if($set->update())
			{
				$reqSimpan= 1;
			}
		}

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsondiklatteknisadd()
	{
		$this->load->model("base-validasi/DiklatTeknis");

		$reqMode= $this->input->post("reqMode");
		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqRowId= $this->input->post("reqRowId");
		$reqDataId= $this->input->post("reqDataId");
		$reqNamaDiklat= $this->input->post("reqNamaDiklat");
		$reqTempat= $this->input->post("reqTempat");
		$reqPenyelenggara= $this->input->post("reqPenyelenggara");
		$reqAngkatan= $this->input->post("reqAngkatan");
		$reqTahun= $this->input->post("reqTahun");
		$reqNoSTTPP= $this->input->post("reqNoSTTPP");
		$reqTglMulai= $this->input->post("reqTglMulai");
		$reqTglSTTPP= $this->input->post("reqTglSTTPP");
		$reqTglSelesai= $this->input->post("reqTglSelesai");
		$reqJumlahJam= $this->input->post("reqJumlahJam"); 

		$set = new DiklatTeknis();
		$set->setField('TAHUN', ValToNullDB($reqTahun));
		$set->setField('NAMA', $reqNamaDiklat);
		$set->setField('NO_STTPP', $reqNoSTTPP);
		$set->setField('TANGGAL_MULAI', dateToDBCheck($reqTglMulai));
		$set->setField('TANGGAL_SELESAI', dateToDBCheck($reqTglSelesai));
		$set->setField('TANGGAL_STTPP', dateToDBCheck($reqTglSTTPP));
		$set->setField('PENYELENGGARA', $reqPenyelenggara);
		$set->setField('ANGKATAN', $reqAngkatan);
		$set->setField('TEMPAT', $reqTempat);
		$set->setField('JUMLAH_JAM', ValToNullDB($reqJumlahJam));
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('USER_APP_ID', $userLogin->UID);
		
		$reqSimpan= "";
		if($reqMode == "insert")
		{
			$set->setField("LAST_CREATE_USER", $userLogin->idUser);
			$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
			$set->setField('DIKLAT_TEKNIS_ID', ValToNullDB($reqRowId));

			if($set->insert())
			{
				$reqSimpan= 1;
			}
		}
		elseif($reqMode == "update")
		{	
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
			$set->setField('TEMP_VALIDASI_ID', $reqDataId);
			$set->setField('DIKLAT_TEKNIS_ID', $reqRowId);
			if($set->update())
			{
				$reqSimpan= 1;
			}
		}

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsonpegawaiseminaradd()
	{
		$this->load->model("base-validasi/Seminar");

		$reqMode= $this->input->post("reqMode");
		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqRowId= $this->input->post("reqRowId");
		$reqDataId= $this->input->post("reqDataId");
		$reqNamaSeminar= $this->input->post("reqNamaSeminar");
		$reqTempat= $this->input->post("reqTempat");
		$reqPenyelenggara= $this->input->post("reqPenyelenggara");
		$reqTglMulai= $this->input->post("reqTglMulai");
		$reqNoPiagam= $this->input->post("reqNoPiagam");
		$reqTglSelesai= $this->input->post("reqTglSelesai"); 

		$set = new Seminar();
		$set->setField('NAMA', $reqNamaSeminar);
		$set->setField('TEMPAT', $reqTempat);
		$set->setField('TANGGAL_PIAGAM', dateToDBCheck($reqTglPiagam));
		$set->setField('PENYELENGGARA', $reqPenyelenggara);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('NO_PIAGAM', $reqNoPiagam);
		$set->setField('TANGGAL_MULAI', dateToDBCheck($reqTglMulai));
		$set->setField('TANGGAL_SELESAI', dateToDBCheck($reqTglSelesai));
		
		$reqSimpan= "";
		if($reqMode == "insert")
		{
			$set->setField("LAST_CREATE_USER", $userLogin->idUser);
			$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
			$set->setField('SEMINAR_ID', ValToNullDB($reqRowId));

			if($set->insert())
			{
				$reqSimpan= 1;
			}
		}
		elseif($reqMode == "update")
		{	
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
			$set->setField('TEMP_VALIDASI_ID', $reqDataId);
			if($set->update())
			{
				$reqSimpan= 1;
			}
		}

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}


	function jsontambahanmasakerjaadd()
	{
		$this->load->model("base-validasi/TambahanMasaKerja");
		$this->load->model("base-validasi/TrigerCpnsPnsPangkatGajiTambahan");
		$this->load->model("base-validasi/PejabatPenetap");

		$reqMode= $this->input->post("reqMode");
		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqRowId= $this->input->post("reqRowId");
		$reqDataId= $this->input->post("reqDataId");
		$reqTambMKId= $this->input->post("reqTambMKId");
		$reqNoSK= $this->input->post("reqNoSK");
		$reqTglSK= $this->input->post("reqTglSK");
		$reqTMTSK= $this->input->post("reqTMTSK");
		$reqTambahanMasaKerja= $this->input->post("reqTambahanMasaKerja");
		$reqMasaKerja= $this->input->post("reqMasaKerja");
		$reqThTMK= $this->input->post("reqThTMK");
		$reqThMK= $this->input->post("reqThMK");
		$reqBlTMK= $this->input->post("reqBlTMK");
		$reqBlMK= $this->input->post("reqBlMK");
		$reqLinkFile= $_FILES["reqLinkFile"];
		$reqGajiPokok= $this->input->post("reqGajiPokok");
		$reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");
		$reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
		$reqGolRuang= $this->input->post("reqGolRuang"); 
		$reqStatusValidasi= $this->input->post("reqStatusValidasi");

		$tahun=date("Y");
		if(!empty($reqTMTSK))
		{
			$tahun =getYear($reqTMTSK);
		}
		

		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];

		if(!empty($fileName))
		{
			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFile']['size'] >= $maxsize) || ($_FILES["reqLinkFile"]["size"] == 0)) {
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if((!in_array($_FILES['reqLinkFile']['type'], $acceptable)) && (!empty($_FILES["reqLinkFile"]["type"]))) {
				$errors[] = 'File gagal diupload, Pastikan File berformat PDF';
			}

			if(count($errors) > 0) 
			{
				foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
			}
			
		}

		$set = new TambahanMasaKerja();
		$set->setField('BULAN_BARU', $reqBlMK);
		$set->setField('BULAN_TAMBAHAN', $reqBlTMK);
		$set->setField('NO_SK', $reqNoSK);
		$set->setField('TAHUN_BARU', $reqThMK);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('TAHUN_TAMBAHAN', $reqThTMK);
		$set->setField('TANGGAL_SK', dateToDBCheck($reqTglSK));
		$set->setField('TMT_SK', dateToDBCheck($reqTMTSK));
		$set->setField('USER_APP_ID', $userLogin->UID);
		$set->setField('PANGKAT_ID', ValToNullDB($reqGolRuang));
		$set->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId));	
		$set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
		$set->setField('GAJI_POKOK', ValToNullDB(dotToNo($reqGajiPokok)));

		if($reqPejabatPenetapId == "")
		{
			$set_pejabat=new PejabatPenetap();
			$set_pejabat->setField('JABATAN', strtoupper($reqPejabatPenetap));
			$set_pejabat->setField("LAST_CREATE_USER", $userLogin->idUser);
			$set_pejabat->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
			$set_pejabat->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
			$set_pejabat->insert();
			$reqPejabatPenetapId=$set_pejabat->id;
			unset($set_pejabat);
		}

		
		$reqSimpan= "";
		if(is_numeric($reqStatusValidasi))
		{
			$set->setField('VALIDASI', $reqStatusValidasi);
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
			$set->setField('TEMP_VALIDASI_ID', $reqDataId);

			if($set->updatevalidasi())
			{
				$reqSimpan= "1";
			}
		}
		else
		{
			if($reqMode == "insert")
			{
				$set->setField("LAST_CREATE_USER", $userLogin->idUser);
				$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
				$set->setField('TAMBAHAN_MASA_KERJA_ID',ValToNullDB($reqRowId));

				if($set->insert())
				{
					$triger= new TrigerCpnsPnsPangkatGajiTambahan();
					$triger->setField("PEGAWAI_ID", $reqPegawaiId);
					$triger->setField("MODE", "tambahan_gaji");
					$triger->setTriger();
					//echo $triger->query;exit;
					unset($triger);
					$reqSimpan= 1;
				}
			}
			elseif($reqMode == "update")
			{	
				$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
				$set->setField('TEMP_VALIDASI_ID', $reqDataId);
				$set->setField("TAMBAHAN_MASA_KERJA_ID", $reqId);
				if($set->update())
				{

					$triger= new TrigerCpnsPnsPangkatGajiTambahan();
					$triger->setField("PEGAWAI_ID", $reqPegawaiId);
					$triger->setField("MODE", "tambahan_gaji");
					$triger->setTriger();
					//echo $triger->query;exit;
					unset($triger);
					$reqSimpan= 1;
				}
			}
		}

		if(!empty($fileName))
		{
			if(count($errors) === 0) 
			{

				$statement="";
				$set_upload= new TambahanMasaKerja();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 3";
				$set_upload= new TambahanMasaKerja();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				if($reqDataId == "")
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";
				}
				else
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqDataId."\\";
				}

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
				$ext = strtolower($path_parts['extension']);


				$renamefile = "PMK_".$tahun."_".$tempNipBaru.".".$ext;
	
				$dest_path = $urlupload . $renamefile;
				// print_r($dest_path);exit;
				if(file_exists($dest_path))
				{
					unlink($dest_path);
				}

				$statement="";

				if(move_uploaded_file($reqLinkFile, $dest_path))
				{
					if($reqDataId == "")
					{
						$statement=" AND A.TAMBAHAN_MASA_KERJA_ID=".$reqRowId;
					}
					else
					{
						$statement=" AND A.TEMP_VALIDASI_ID=".$reqDataId;
					}
					$set_upload= new TambahanMasaKerja();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE");
			
					$link_server=$dest_path;

					if(empty($tempLinkFile))
					{
						$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDataId));
						$set_upload->setField('TAMBAHAN_MASA_KERJA_ID', ValToNullDB($reqRowId));
						$set_upload->setField("LINK_FILE", $dest_path);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField("LINK_SERVER", $link_server);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$reqSimpan= 1;
					}
				}
			}
			else 
		    {
		    	foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
		    }
		}
		// echo $set->query;exit;

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsonmasakerjadeletefile()
	{
		$this->load->model("base-validasi/TambahanMasaKerja");
		$reqFileId= $this->input->get("reqFileId");
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqDetilId= $this->input->get("reqDetilId");
		$reqRowId= $this->input->get("reqRowId");
		$set= new TambahanMasaKerja();
		$set->setField('TAMBAHAN_MASA_KERJA_FILE_ID', $reqFileId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);

		$set_upload = new TambahanMasaKerja();
		
		if($reqDetilId == "")
		{
			$set_upload->selectByParams(array('A.TAMBAHAN_MASA_KERJA_ID'=>$reqRowId));
		}
		else
		{
			$set_upload->selectByParams(array('A.TEMP_VALIDASI_ID'=>$reqDetilId));
		}
		
		$set_upload->firstRow();
		$reqLinkFile= $set_upload->getField('LINK_FILE');
		// echo $reqRowId;exit;		
		$reqSimpan="";

		if(file_exists($reqLinkFile))
		{
			unlink($reqLinkFile);
		}

		if($set->deletefile())	
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


	function jsonorangtuaadd()
	{
		$this->load->model("base-data/OrangTua");

		$reqMode= $this->input->post("reqMode");
		$reqModeAyah= $this->input->post("reqModeAyah");
		$reqModeIbu= $this->input->post("reqModeIbu");
		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqRowIdAyah= $this->input->post("reqRowIdAyah");
		$reqDataIdAyah= $this->input->post("reqDataIdAyah");
		$reqRowIdIbu= $this->input->post("reqRowIdIbu");
		$reqDataIdIbu= $this->input->post("reqDataIdIbu");
		$reqAyahId= $this->input->post("reqIdAyah");
		$reqIbuId= $this->input->post("reqIdIbu");
		$reqNamaAyah= $this->input->post("reqNamaAyah");
		$reqNamaIbu= $this->input->post("reqNamaIbu");
		$reqTempatLahirAyah= $this->input->post("reqTempatLahirAyah");
		$reqTempatLahirIbu= $this->input->post("reqTempatLahirIbu");
		$reqTglLahirAyah= $this->input->post("reqTglLahirAyah");
		$reqTglLahirIbu= $this->input->post("reqTglLahirIbu");
		$reqUsiaAyah= $this->input->post("reqUsiaAyah");
		$reqUsiaIbu= $this->input->post("reqUsiaIbu");
		$reqPekerjaanAyah= $this->input->post("reqPekerjaanAyah");
		$reqPekerjaanIbu= $this->input->post("reqPekerjaanIbu");
		$reqAlamatAyah= $this->input->post("reqAlamatAyah");
		$reqAlamatIbu= $this->input->post("reqAlamatIbu");
		$reqPropinsiAyahId= $this->input->post("reqPropinsiAyahId");
		$reqPropinsiIbuId= $this->input->post("reqPropinsiIbuId");
		$reqKabupatenAyahId= $this->input->post("reqKabupatenAyahId");
		$reqKabupatenIbuId= $this->input->post("reqKabupatenIbuId");
		$reqKecamatanAyahId= $this->input->post("reqKecamatanAyahId");
		$reqKecamatanIbuId= $this->input->post("reqKecamatanIbuId");
		$reqDesaAyahId= $this->input->post("reqDesaAyahId");
		$reqDesaIbuId= $this->input->post("reqDesaIbuId");
		$reqKodePosAyah= $this->input->post("reqKodePosAyah");
		$reqKodePosIbu= $this->input->post("reqKodePosIbu");
		$reqTeleponAyah= $this->input->post("reqTeleponAyah");
		$reqTeleponIbu= $this->input->post("reqTeleponIbu");
		$reqStatusValidasi= $this->input->post("reqStatusValidasi");
		// echo  $reqStatusValidasi;exit;

		$set = new OrangTua();
		$set->setField("PEGAWAI_ID", $reqPegawaiId);


		$reqSimpan= "";
	
		if($reqModeAyah == "insert" || $reqModeIbu == "insert")
		{
			if($reqModeAyah == "insert")
			{
				$set->setField("JENIS_KELAMIN", "L");
				$set->setField("NAMA", $reqNamaAyah);
				$set->setField("TEMPAT_LAHIR", $reqTempatLahirAyah);
				$set->setField("TANGGAL_LAHIR", dateToDBCheck($reqTglLahirAyah));
				$set->setField("PEKERJAAN", $reqPekerjaanAyah);
				$set->setField("ALAMAT", $reqAlamatAyah);
				$set->setField("KODEPOS", $reqKodePosAyah);
				$set->setField("TELEPON", $reqTeleponAyah);
				$set->setField("PROPINSI_ID", ValToNullDB($reqPropinsiAyahId));
				$set->setField("KABUPATEN_ID", ValToNullDB($reqKabupatenAyahId));
				$set->setField("KECAMATAN_ID", ValToNullDB($reqKecamatanAyahId));
				$set->setField("KELURAHAN_ID", ValToNullDB($reqDesaAyahId));
				$set->setField("LAST_CREATE_USER", $userLogin->idUser);
				$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
				$set->setField('ORANG_TUA_ID',ValToNullDB($reqRowIdAyah));
				if($set->insert())
				{
					$reqSimpan = 1;
				}
			}
			if($reqModeIbu == "insert")
			{
				$set->setField("JENIS_KELAMIN", "P");
				$set->setField("NAMA", $reqNamaIbu);
				$set->setField("TEMPAT_LAHIR", $reqTempatLahirIbu);
				$set->setField("TANGGAL_LAHIR", dateToDBCheck($reqTglLahirIbu));
				$set->setField("PEKERJAAN", $reqPekerjaanIbu);
				$set->setField("ALAMAT", $reqAlamatIbu);
				$set->setField("KODEPOS", $reqKodePosIbu);
				$set->setField("TELEPON", $reqTeleponIbu);
				$set->setField("PROPINSI_ID", ValToNullDB($reqPropinsiIbuId));
				$set->setField("KABUPATEN_ID", ValToNullDB($reqKabupatenIbuId));
				$set->setField("KECAMATAN_ID", ValToNullDB($reqKecamatanIbuId));
				$set->setField("KELURAHAN_ID", ValToNullDB($reqDesaIbuId));
				$set->setField("LAST_CREATE_USER", $userLogin->idUser);
				$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
				$set->setField('ORANG_TUA_ID',ValToNullDB($reqRowIdIbu));
				if($set->insert())
				{
					$reqSimpan = 1;
				}
			}
		}
		elseif($reqModeAyah == "update" || $reqModeIbu == "update")
		{
			if($reqModeAyah == "update")
			{
				$set->setField("JENIS_KELAMIN", "L");
				$set->setField("NAMA", $reqNamaAyah);
				$set->setField("TEMPAT_LAHIR", $reqTempatLahirAyah);
				$set->setField("TANGGAL_LAHIR", dateToDBCheck($reqTglLahirAyah));
				$set->setField("PEKERJAAN", $reqPekerjaanAyah);
				$set->setField("ALAMAT", $reqAlamatAyah);
				$set->setField("KODEPOS", $reqKodePosAyah);
				$set->setField("TELEPON", $reqTeleponAyah);
				$set->setField("ORANG_TUA_ID", $reqAyahId);
				$set->setField("PROPINSI_ID", ValToNullDB($reqPropinsiAyahId));
				$set->setField("KABUPATEN_ID", ValToNullDB($reqKabupatenAyahId));
				$set->setField("KECAMATAN_ID", ValToNullDB($reqKecamatanAyahId));
				$set->setField("KELURAHAN_ID", ValToNullDB($reqDesaAyahId));
				$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
				$set->setField('ORANG_TUA_ID', $reqRowIdAyah);

				if($set->update())
				{
					$reqSimpan = 1;
				}
			}
			if($reqModeIbu == "update")
			{
				$set->setField("JENIS_KELAMIN", "P");
				$set->setField("NAMA", $reqNamaIbu);
				$set->setField("TEMPAT_LAHIR", $reqTempatLahirIbu);
				$set->setField("TANGGAL_LAHIR", dateToDBCheck($reqTglLahirIbu));
				$set->setField("PEKERJAAN", $reqPekerjaanIbu);
				$set->setField("ALAMAT", $reqAlamatIbu);
				$set->setField("KODEPOS", $reqKodePosIbu);
				$set->setField("TELEPON", $reqTeleponIbu);
				$set->setField("ORANG_TUA_ID", $reqIbuId);
				$set->setField("PROPINSI_ID", ValToNullDB($reqPropinsiIbuId));
				$set->setField("KABUPATEN_ID", ValToNullDB($reqKabupatenIbuId));
				$set->setField("KECAMATAN_ID", ValToNullDB($reqKecamatanIbuId));
				$set->setField("KELURAHAN_ID", ValToNullDB($reqDesaIbuId));
				$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
				$set->setField('ORANG_TUA_ID', $reqRowIdIbu);

				if($set->update())
				{
					$reqSimpan = 1;
				}
			}
		}

		

		// echo $set->query;exit;

		

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsonpegawaisuamiistriadd()
	{
		$this->load->model("base-validasi/SuamiIstri");

		$reqMode = $this->input->post("reqMode");
		$reqPegawaiId = $this->input->post('reqPegawaiId');
		$reqRowId= $this->input->post("reqRowId");
		$reqDataId= $this->input->post("reqDataId");
		$reqIdSuamiIstri= $this->input->post("reqIdSuamiIstri");
		$reqNamaSuamiIstri= $this->input->post("reqNamaSuamiIstri");
		$reqTempatLahir= $this->input->post("reqTempatLahir");
		$reqTglLahir= $this->input->post("reqTglLahir");
		$reqNoAktaNikah= $this->input->post("reqNoAktaNikah");
		$reqStatus= $this->input->post("reqStatus");
		$reqNoHp= $this->input->post("reqNoHp");
		$reqTglNikah= $this->input->post("reqTglNikah");
		$reqTglKawin= $this->input->post("reqTglKawin");
		$reqPns= $this->input->post("reqPns");
		$reqNIP= $this->input->post("reqNIP");
		$reqPendidikan= $this->input->post("reqPendidikan");
		$reqPekerjaan= $this->input->post("reqPekerjaan");
		$reqTunjangan= $this->input->post("reqTunjangan");
		$reqSudahDibayar= $this->input->post("reqSudahDibayar");
		$reqBulanDibayar= $this->input->post("reqBulanDibayar");
		$reqKartu= $this->input->post("reqKartu");
		$reqFoto = $_FILES["reqFoto"];
		$reqFotoTmp= $this->input->post("reqFotoTmp");
		$reqDosirKarpeg= $_FILES["reqDosirKarpeg"];
		$reqStatusValidasi= $this->input->post("reqStatusValidasi");

		$reqLinkFileKK=$_FILES['reqLinkFileKK']['tmp_name'];
		$fileNameKK = $_FILES['reqLinkFileKK']['name'];
		$reqLinkFileAkta=$_FILES['reqLinkFileAkta']['tmp_name'];
		$fileNameAkta = $_FILES['reqLinkFileAkta']['name'];
		$reqLinkFileKtp=$_FILES['reqLinkFileKtp']['tmp_name'];
		$fileNameKtp = $_FILES['reqLinkFileKtp']['name'];

		$set = new SuamiIstri();
		$set->setField("PEGAWAI_ID", $reqPegawaiId);
		$set->setField("PENDIDIKAN_ID", ValToNullDB($reqPendidikan));
		$set->setField("NAMA", $reqNamaSuamiIstri);
		$set->setField("TEMPAT_LAHIR", $reqTempatLahir);
		$set->setField("TANGGAL_LAHIR", dateToDBCheck($reqTglLahir));
		$set->setField("TANGGAL_KAWIN", dateToDBCheck($reqTglKawin));
		$set->setField('SK_CERAI', $reqNoAktaNikah);
		$set->setField('STATUS', ValToNullDB($reqStatus));
		$set->setField('BUKU_NIKAH', $reqNoHp);
		$set->setField("KARTU", $reqKartu);
		$set->setField("STATUS_PNS", ValToNullDB($reqPns));
		$set->setField("NIP_PNS", $reqNIP);
		$set->setField("PEKERJAAN", $reqPekerjaan);
		$set->setField("STATUS_TUNJANGAN", ValToNullDB($reqTunjangan));
		$set->setField("STATUS_BAYAR", ValToNullDB($reqSudahDibayar));
		$set->setField("BULAN_BAYAR", dateToDBCheck($reqBulanDibayar));
		
		$reqSimpan= "";
		if(is_numeric($reqStatusValidasi))
		{
			$set->setField('VALIDASI', $reqStatusValidasi);
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
			$set->setField('TEMP_VALIDASI_ID', $reqDataId);

			if($set->updatevalidasi())
			{
				$reqSimpan= "1";
			}
		}
		else
		{
			if($reqMode == "insert")
			{
				$set->setField("LAST_CREATE_USER", $userLogin->idUser);
				$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
				$set->setField('SUAMI_ISTRI_ID',ValToNullDB($reqRowId));

				if($set->insert())
				{
					$reqDataId= $set->id;
					$reqSimpan= 1;
				}
			}
			elseif($reqMode == "update")
			{	
				$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
				$set->setField('TEMP_VALIDASI_ID', $reqDataId);
				$set->setField("SUAMI_ISTRI_ID", $reqRowId);
				if($set->update())
				{
					$reqSimpan= 1;
				}
			}
		}

		if(!empty($fileNameKK) || !empty($fileNameAkta) || !empty($fileNameKtp))
		{

			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFileKK']['size'] >= $maxsize) || ($_FILES['reqLinkFileAkta']['size'] >= $maxsize) || ($_FILES['reqLinkFileKtp']['size'] >= $maxsize)) 
			{
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if(count($errors) === 0) 
			{

				$statement="";
				$set_upload= new SuamiIstri();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 15";
				$set_upload= new SuamiIstri();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				if($reqDataId == "")
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";
				}
				else
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqDataId."\\";
				}

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$ext = "pdf";


				$renamefileKK= "KK_".$tempNipBaru.".".$ext;
				$renamefileAkta = "AKTA_".$tempNipBaru.".".$ext;
				$renamefileKtp = "KTP_".$tempNipBaru.".".$ext;

				$dest_path_kk = $urlupload . $renamefileKK;
				$dest_path_akta = $urlupload . $renamefileAkta;
				$dest_path_ktp = $urlupload . $renamefileKtp;
	
				// var_dump($reqDataId);exit;
				if (!empty($fileNameKK))
				{
					if(file_exists($dest_path_kk))
					{
						unlink($dest_path_kk);
					}

				}
				else if (!empty($fileNameAkta))
				{
					if(file_exists($dest_path_akta))
					{
						unlink($dest_path_akta);
					}
				}
				else if (!empty($fileNameKtp))
				{
					if(file_exists($dest_path_ktp))
					{
						unlink($dest_path_ktp);
					}
				}

				$statement="";

				if(move_uploaded_file($reqLinkFileKK, $dest_path_kk))
				{
					if($reqDataId == "")
					{
						$statement=" AND A.SUAMI_ISTRI_ID=".$reqRowId;
					}
					else
					{
						$statement=" AND A.TEMP_VALIDASI_ID=".$reqDataId;
					}
					$set_upload= new SuamiIstri();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE_KK");
					$fileid=$set_upload->getField("SUAMI_ISTRI_FILE_ID");
					
					$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDataId));
					$set_upload->setField("LINK_FILE_KK", $dest_path_kk);
					$set_upload->setField("NAMA_FILE_KK", $renamefileKK);
					$set_upload->setField('SUAMI_ISTRI_ID', $reqRowId);
					$set_upload->setField("TIPE_FILE", $ext);
					$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);

					$nama="NAMA_FILE_KK";
					$link="LINK_FILE_KK";
					if(empty($fileid) && empty($tempLinkFile))
					{
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$set_upload->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('SUAMI_ISTRI_FILE_ID', $fileid);
						if($set_upload->updateupload($nama,$link))
						{
							$reqSimpan= 1;
						}
					}
				}

				if(move_uploaded_file($reqLinkFileAkta, $dest_path_akta))
				{
					if($reqDataId == "")
					{
						$statement=" AND A.SUAMI_ISTRI_ID=".$reqRowId;
					}
					else
					{
						$statement=" AND A.TEMP_VALIDASI_ID=".$reqDataId;
					}
					$set_upload= new SuamiIstri();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE_AKTA");
					$fileid=$set_upload->getField("SUAMI_ISTRI_FILE_ID");
					
					$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDataId));
					$set_upload->setField("LINK_FILE_AKTA", $dest_path_akta);
					$set_upload->setField("NAMA_FILE_AKTA", $renamefileAkta);
					$set_upload->setField('SUAMI_ISTRI_ID', $reqRowId);
					$set_upload->setField("TIPE_FILE", $ext);
					$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);

					$nama="NAMA_FILE_AKTA";
					$link="LINK_FILE_AKTA";
					if(empty($fileid) && empty($tempLinkFile))
					{
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$set_upload->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('SUAMI_ISTRI_FILE_ID', $fileid);
						if($set_upload->updateupload($nama,$link))
						{
							$reqSimpan= 1;
						}
					}
					// echo $set_upload->query;exit;
				}

				if(move_uploaded_file($reqLinkFileKtp, $dest_path_ktp) )
				{
					if($reqDataId == "")
					{
						$statement=" AND A.SUAMI_ISTRI_ID=".$reqRowId;
					}
					else
					{
						$statement=" AND A.TEMP_VALIDASI_ID=".$reqDataId;
					}
					$set_upload= new SuamiIstri();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE_KK");
					$fileid=$set_upload->getField("SUAMI_ISTRI_FILE_ID");
					
					$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDataId));
					$set_upload->setField("LINK_FILE_KTP", $dest_path_ktp);
					$set_upload->setField("NAMA_FILE_KTP", $renamefileKtp);
					$set_upload->setField('SUAMI_ISTRI_ID', $reqRowId);
					$set_upload->setField("TIPE_FILE", $ext);
					$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);

					$nama="NAMA_FILE_KTP";
					$link="LINK_FILE_KTP";
					if(empty($fileid) && empty($tempLinkFile))
					{
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$set_upload->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('SUAMI_ISTRI_FILE_ID', $fileid);
						if($set_upload->updateupload($nama,$link))
						{
							$reqSimpan= 1;
						}
					}
				}
			}
			else 
		    {
		    	foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
		    }
		}

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsonpegawaianakadd()
	{
		$this->load->model("base-data/Anak");

		$reqMode = $this->input->post("reqMode");
		$reqPegawaiId = $this->input->post('reqPegawaiId');
		$reqRowId= $this->input->post("reqRowId");
		$reqDataId= $this->input->post("reqDataId");
		$reqNama = $this->input->post("reqNama");
		$reqTmpLahir = $this->input->post("reqTmpLahir");
		$reqTglLahir = $this->input->post("reqTglLahir");
		$reqLP= $this->input->post("reqLP");
		$reqStatus= $this->input->post("reqStatus");
		$reqDapatTunjangan= $this->input->post("reqDapatTunjangan");
		$reqPendidikan= $this->input->post("reqPendidikan");
		$reqPekerjaan= $this->input->post("reqPekerjaan");
		$reqMulaiDibayar= $this->input->post("reqMulaiDibayar");
		$reqAkhirDibayar= $this->input->post("reqAkhirDibayar");
		$reqStatusValidasi= $this->input->post("reqStatusValidasi");

		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];

		if(!empty($fileName))
		{
			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFile']['size'] >= $maxsize) || ($_FILES["reqLinkFile"]["size"] == 0)) {
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if((!in_array($_FILES['reqLinkFile']['type'], $acceptable)) && (!empty($_FILES["reqLinkFile"]["type"]))) {
				$errors[] = 'File gagal diupload, Pastikan File berformat PDF';
			}

			if(count($errors) > 0) 
			{
				foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
			}
			
		}

		$set = new Anak();
		$set->setField('NAMA', setQuote($reqNama,1));
		$set->setField('TEMPAT_LAHIR', $reqTmpLahir);
		$set->setField('TANGGAL_LAHIR', dateToDBCheck($reqTglLahir));
		$set->setField('JENIS_KELAMIN', $reqLP);
		$set->setField('STATUS_KELUARGA', ValToNullDB($reqStatus));
		$set->setField('STATUS_TUNJANGAN', ValToNullDB($reqDapatTunjangan));
		$set->setField('PENDIDIKAN_ID', ValToNullDB($reqPendidikan));
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('PEKERJAAN', $reqPekerjaan);
		$set->setField('AWAL_BAYAR', dateToDBCheck($reqMulaiDibayar));
		$set->setField('AKHIR_BAYAR', dateToDBCheck($reqAkhirDibayar));
		$set->setField('USER_APP_ID', ValToNullDB($userLogin->UID));
		
		$reqSimpan= "";

		if($reqMode == "insert")
		{
			$set->setField("LAST_CREATE_USER", $userLogin->idUser);
			$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);	
			$set->setField('ANAK_ID', ValToNullDB($reqRowId));

			if($set->insert())
			{
				$reqRowId=$set->id;
				$reqSimpan= 1;
			}
		}
		elseif($reqMode == "update")
		{	
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);	
			$set->setField('TEMP_VALIDASI_ID', $reqDataId);
			$set->setField("ANAK_ID", $reqRowId);
			if($set->update())
			{
				$reqSimpan= 1;
			}
		}

		if(!empty($fileName))
		{
			if(count($errors) === 0) 
			{
				$statement="";
				$set_upload= new Anak();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 7";
				$set_upload= new Anak();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
				$ext = strtolower($path_parts['extension']);

				$renamefile = "ANAK_".$tempNipBaru.".".$ext;

				$dest_path = $urlupload . $renamefile;
				// print_r($dest_path);exit;
				if(file_exists($dest_path))
				{
					unlink($dest_path);
				}

				$statement="";

				if(move_uploaded_file($reqLinkFile, $dest_path))
				{
					$statement=" AND A.ANAK_ID=".$reqRowId;
					$set_upload= new Anak();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE");
			
					$link_server=$dest_path;

					if(empty($tempLinkFile))
					{
						$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('ANAK_ID', ValToNullDB($reqRowId));
						$set_upload->setField("LINK_FILE", $dest_path);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField("LINK_SERVER", $link_server);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$reqSimpan= 1;
					}
				}
			}
			else 
		    {
		    	foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
		    }
		}
		

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsonpegawaiorganisasiadd()
	{
		$this->load->model("base-validasi/OrganisasiRiwayat");

		$reqMode =  $this->input->post("reqMode");
		$reqPegawaiId =  $this->input->post('reqPegawaiId');
		$reqRowId= $this->input->post("reqRowId");
		$reqDataId= $this->input->post("reqDataId");
		$reqNamaOrganisasi= $this->input->post("reqNamaOrganisasi");
		$reqJabatan= $this->input->post("reqJabatan");
		$reqAwal= $this->input->post("reqAwal");
		$reqAkhir= $this->input->post("reqAkhir");
		$reqPimpinan= $this->input->post("reqPimpinan");
		$reqTempat= $this->input->post("reqTempat");
		$reqStatusValidasi= $this->input->post("reqStatusValidasi");

		$set = new OrganisasiRiwayat();
		$set->setField('JABATAN', $reqJabatan);
		$set->setField('NAMA', $reqNamaOrganisasi);
		$set->setField('TANGGAL_AWAL', dateToDBCheck($reqAwal));
		$set->setField('TANGGAL_AKHIR', dateToDBCheck($reqAkhir));
		$set->setField('PIMPINAN', $reqPimpinan);
		$set->setField('TEMPAT', $reqTempat);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		
		$reqSimpan= "";

		if(is_numeric($reqStatusValidasi))
		{
			$set->setField('VALIDASI', $reqStatusValidasi);
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
			$set->setField('TEMP_VALIDASI_ID', $reqDataId);

			if($set->updatevalidasi())
			{
				$reqSimpan= "1";
			}
		}
		else
		{
			if($reqMode == "insert")
			{
				$set->setField("LAST_CREATE_USER", $userLogin->idUser);
				$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
				$set->setField('ORGANISASI_RIWAYAT_ID', ValToNullDB($reqRowId));

				if($set->insert())
				{
					$reqSimpan= 1;
				}
			}
			elseif($reqMode == "update")
			{	
				$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);	
				$set->setField('TEMP_VALIDASI_ID', $reqDataId);
				$set->setField('ORGANISASI_RIWAYAT_ID', $reqRowId);
				if($set->update())
				{
					$reqSimpan= 1;
				}
			}
		}

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsonpegawaipenghargaanadd()
	{
		$this->load->model("base-validasi/Penghargaan");

		$reqMode=  $this->input->post("reqMode");
		$reqPegawaiId=  $this->input->post('reqPegawaiId');
		$reqRowId= $this->input->post("reqRowId");
		$reqDataId= $this->input->post("reqDataId");
		$reqTahun= $this->input->post("reqTahun");
		$reqNamaPenghargaan= $this->input->post("reqNamaPenghargaan");
		$reqNoSK= $this->input->post("reqNoSK");
		$reqTglSK= $this->input->post("reqTglSK");
		$reqPjPenetap= $this->input->post("reqPjPenetap");
		$reqStatusPejabatPenetap= $this->input->post("reqStatusPejabatPenetap");
		$reqPjPenetap_Baru= $this->input->post("reqPjPenetap_Baru");
		$reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");
		$reqStatusPejabat= $this->input->post("reqStatusPejabat");
		$reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
		$reqStatusValidasi= $this->input->post("reqStatusValidasi");

		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];

		if(!empty($fileName))
		{
			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFile']['size'] >= $maxsize) || ($_FILES["reqLinkFile"]["size"] == 0)) {
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if((!in_array($_FILES['reqLinkFile']['type'], $acceptable)) && (!empty($_FILES["reqLinkFile"]["type"]))) {
				$errors[] = 'File gagal diupload, Pastikan File berformat PDF';
			}

			if(count($errors) > 0) 
			{
				foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
			}
			
		}

		$set = new Penghargaan();
		$set->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId));	
		$set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
		$set->setField('NAMA', $reqNamaPenghargaan);
		$set->setField('TAHUN', ValToNullDB($reqTahun));
		$set->setField('TANGGAL_SK', dateToDBCheck($reqTglSK));
		$set->setField('NO_SK', $reqNoSK);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);

		$reqSimpan= "";
		if(is_numeric($reqStatusValidasi))
		{
			$set->setField('VALIDASI', $reqStatusValidasi);
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
			$set->setField('TEMP_VALIDASI_ID', $reqDataId);

			if($set->updatevalidasi())
			{
				$reqSimpan= "1";
			}
		}
		else
		{
			if($reqMode == "insert")
			{
				$set->setField("LAST_CREATE_USER", $userLogin->idUser);
				$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
				$set->setField('PENGHARGAAN_ID', ValToNullDB($reqRowId));

				if($set->insert())
				{
					$reqDataId=$set->id;
					$reqSimpan= 1;
				}
			}
			elseif($reqMode == "update")
			{	
				$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);	
				$set->setField('TEMP_VALIDASI_ID', $reqDataId);
				$set->setField('PENGHARGAAN_ID', $reqRowId);
				if($set->update())
				{
					$reqSimpan= 1;
				}
			}
		}

		if(!empty($fileName))
		{
			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFile']['size'] >= $maxsize) || ($_FILES["reqLinkFile"]["size"] == 0)) {
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if((!in_array($_FILES['reqLinkFile']['type'], $acceptable)) && (!empty($_FILES["reqLinkFile"]["type"]))) {
				$errors[] = 'File gagal diupload, Pastikan File berformat PDF';
			}

			if(count($errors) === 0) 
			{

				$statement="";
				$set_upload= new Penghargaan();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 5";
				$set_upload= new Penghargaan();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				if($reqDataId == "")
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";
				}
				else
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqDataId."\\";
				}

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
				$ext = strtolower($path_parts['extension']);


				if(!empty($reqTahun))
				{
					$renamefile = "PENGHARGAAN_".$reqTahun."_".$tempNipBaru.".".$ext;
				}
				else
				{
					$renamefile = "PENGHARGAAN_".$tempNipBaru.".".$ext;
				}

	
				$dest_path = $urlupload . $renamefile;
				// print_r($dest_path);exit;
				if(file_exists($dest_path))
				{
					unlink($dest_path);
				}

				$statement="";

				if(move_uploaded_file($reqLinkFile, $dest_path))
				{
					if($reqDataId == "")
					{
						$statement=" AND A.PENGHARGAAN_ID=".$reqRowId;
					}
					else
					{
						$statement=" AND A.TEMP_VALIDASI_ID=".$reqDataId;
					}
					$set_upload= new Penghargaan();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE");
			
					$link_server=$dest_path;

					if(empty($tempLinkFile))
					{
						$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDataId));
						$set_upload->setField('PENGHARGAAN_ID', ValToNullDB($reqRowId));
						$set_upload->setField("LINK_FILE", $dest_path);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField("LINK_SERVER", $link_server);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$reqSimpan= 1;
					}
				}
			}
			else 
		    {
		    	foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
		    }
		}

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsonpegawaipenilaianpotensiadd()
	{
		$this->load->model("base-validasi/PotensiDiri");

		$reqMode=  $this->input->post("reqMode");
		$reqPegawaiId=  $this->input->post('reqPegawaiId');
		$reqDataId= $this->input->post("reqDataId");
		$reqTahun= $this->input->post("reqTahun");
		$reqTanggungJawab= $this->input->post("reqTanggungJawab");
		$reqMotivasi= $this->input->post("reqMotivasi");
		$reqMinat= $this->input->post("reqMinat");
		$reqRowId= $this->input->post("reqRowId");
		$reqStatusValidasi= $this->input->post("reqStatusValidasi");

		$set = new PotensiDiri();
		$set->setField('TAHUN', $reqTahun);
		$set->setField('TANGGUNG_JAWAB', $reqTanggungJawab);
		$set->setField('MOTIVASI', $reqMotivasi);
		$set->setField('MINAT', $reqMinat);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		
		$reqSimpan= "";
		if(is_numeric($reqStatusValidasi))
		{
			$set->setField('VALIDASI', $reqStatusValidasi);
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
			$set->setField('TEMP_VALIDASI_ID', $reqDataId);

			if($set->updatevalidasi())
			{
				$reqSimpan= "1";
			}
		}
		else
		{
			if($reqMode == "insert")
			{
				$set->setField("LAST_CREATE_USER", $userLogin->idUser);
				$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
				$set->setField('POTENSI_DIRI_ID', ValToNullDB($reqRowId));
				if($set->insert())
				{
					$reqSimpan= 1;
				}
			}
			elseif($reqMode == "update")
			{	
				$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
				$set->setField('TEMP_VALIDASI_ID', $reqDataId);
				if($set->update())
				{
					$reqSimpan= 1;
				}
			}
		}

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsonpegawaipenilaianprestasiadd()
	{
		$this->load->model("base-validasi/PenilaianKerja");

		$reqMode=  $this->input->post("reqMode");
		$reqPegawaiId=  $this->input->post('reqPegawaiId');
		$reqDataId= $this->input->post("reqDataId");
		$reqRowId=  $this->input->post('reqRowId');
		$reqTahun= $this->input->post("reqTahun");
		$reqNilai1= $this->input->post("reqNilai1");
		$reqNilai2= $this->input->post("reqNilai2");
		$reqNilai3= $this->input->post("reqNilai3");
		$reqNilai4= $this->input->post("reqNilai4");
		$reqNilai5= $this->input->post("reqNilai5");
		$reqNilai6= $this->input->post("reqNilai6");
		$reqJumlah= $this->input->post("reqJumlah");
		$reqRataRata= $this->input->post("reqRataRata");
		$reqSasaranKerja= $this->input->post("reqSasaranKerja");
		$reqSasaranKerjaHasil= $this->input->post("reqSasaranKerjaHasil");
		$reqPerilakuHasil= $this->input->post("reqPerilakuHasil");
		$reqNilaiHasil= $this->input->post("reqNilaiHasil");
		$reqTanggalAwal= $this->input->post("reqTanggalAwal");
		$reqTanggalAkhir= $this->input->post("reqTanggalAkhir");
		$reqRekomendasi= $this->input->post("reqRekomendasi");
		$reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");
		$reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
		$reqStatusValidasi= $this->input->post("reqStatusValidasi");


		$set = new PenilaianKerja();
		$set->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId));	
		$set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));

		$set->setField('TAHUN', $reqTahun);
		$set->setField('NILAI1', ValToNullDB(commaToDot($reqNilai1)));
		$set->setField('NILAI2', ValToNullDB(commaToDot($reqNilai2)));
		$set->setField('NILAI3', ValToNullDB(commaToDot($reqNilai3)));
		$set->setField('NILAI4', ValToNullDB(commaToDot($reqNilai4)));
		$set->setField('NILAI5', ValToNullDB(commaToDot($reqNilai5)));
		$set->setField('NILAI6', ValToNullDB(commaToDot($reqNilai6)));
		$set->setField('JUMLAH', ValToNullDB(commaToDot($reqJumlah)));
		$set->setField('RATA_RATA', ValToNullDB(commaToDot($reqRataRata)));

		$set->setField('TANGGAL_AWAL', dateToDBCheck($reqTanggalAwal));
		$set->setField('TANGGAL_AKHIR', dateToDBCheck($reqTanggalAkhir));

		$set->setField('SASARAN_KERJA', ValToNullDB(commaToDot($reqSasaranKerja)));
		$set->setField('SASARAN_KERJA_HASIL', ValToNullDB(commaToDot($reqSasaranKerjaHasil)));
		$set->setField('PERILAKU_HASIL', ValToNullDB(commaToDot($reqPerilakuHasil)));
		$set->setField('NILAI_HASIL', ValToNullDB(commaToDot($reqNilaiHasil)));
		$set->setField('REKOMENDASI', $reqRekomendasi);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('USER_APP_ID', $userLogin->UID);
		
		$reqSimpan= "";
		if(is_numeric($reqStatusValidasi))
		{
			$set->setField('VALIDASI', $reqStatusValidasi);
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
			$set->setField('TEMP_VALIDASI_ID', $reqDataId);

			if($set->updatevalidasi())
			{
				$reqSimpan= "1";
			}
		}
		else
		{
			if($reqMode == "insert")
			{
				$set->setField("LAST_CREATE_USER", $userLogin->idUser);
				$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
				$set->setField('PENILAIAN_KERJA_ID', ValToNullDB($reqRowId));
				if($set->insert())
				{
					$reqSimpan= 1;
				}
			}
			elseif($reqMode == "update")
			{	
				$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
				$set->setField('TEMP_VALIDASI_ID', $reqDataId);
				if($set->update())
				{
					$reqSimpan= 1;
				}
			}
		}

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsonpegawaihukumanadd()
	{
		$this->load->model("base-validasi/Hukuman");
		$this->load->model("base-validasi/PejabatPenetap");

		$reqMode = $this->input->post("reqMode");
		$reqPegawaiId = $this->input->post('reqPegawaiId');
		$reqRowId= $this->input->post("reqRowId");
		$reqDataId= $this->input->post("reqDataId");
		$reqTingkatHukuman = $this->input->post("reqTingkatHukuman");
		$reqPeraturan = $this->input->post("reqPeraturan");
		$reqMasihBerlaku = $this->input->post("reqMasihBerlaku");
		$reqJenisHukuman= $this->input->post("reqJenisHukuman");
		$reqNoSK= $this->input->post("reqNoSK");
		$reqTanggalSK= $this->input->post("reqTanggalSK");
		$reqTMTSK= $this->input->post("reqTMTSK");
		$reqPermasalahan= $this->input->post("reqPermasalahan");
		$reqPjPenetap= $this->input->post("reqPjPenetap");
		$reqStatusPejabatPenetap= $this->input->post("reqStatusPejabatPenetap");
		$reqPjPenetap_Baru= $this->input->post("reqPjPenetap_Baru");
		$reqTanggalMulai= $this->input->post("reqTanggalMulai");
		$reqTanggalAkhir= $this->input->post("reqTanggalAkhir");
		$reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");
		$reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
		$reqStatusValidasi= $this->input->post("reqStatusValidasi");

		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];

		if(!empty($fileName))
		{
			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFile']['size'] >= $maxsize) || ($_FILES["reqLinkFile"]["size"] == 0)) {
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if((!in_array($_FILES['reqLinkFile']['type'], $acceptable)) && (!empty($_FILES["reqLinkFile"]["type"]))) {
				$errors[] = 'File gagal diupload, Pastikan File berformat PDF';
			}

			if(count($errors) > 0) 
			{
				foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
			}
			
		}

		$set = new Hukuman();
		$set->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId));	
		$set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
		$set->setField('TANGGAL_MULAI', dateToDBCheck($reqTanggalMulai));
		$set->setField('TANGGAL_AKHIR', dateToDBCheck($reqTanggalAkhir));
		$set->setField('NO_SK', $reqNoSK);
		$set->setField('TANGGAL_SK', dateToDBCheck($reqTanggalSK));
		$set->setField('TMT_SK', dateToDBCheck($reqTMTSK));
		$set->setField('JENIS_HUKUMAN_ID', ValToNullDB($reqJenisHukuman));
		$set->setField('TINGKAT_HUKUMAN_ID', ValToNullDB($reqTingkatHukuman));
		$set->setField('PERATURAN_ID', ValToNullDB($reqPeraturan));
		$set->setField('KETERANGAN', $reqPermasalahan);
		$set->setField('PEGAWAI_ID',$reqPegawaiId);
		$set->setField('BERLAKU',ValToNullDB((int)$reqMasihBerlaku));

		$statementPejabat = " AND JABATAN = '".strtoupper($reqPejabatPenetap)."'";
		$pejabat_cek =new PejabatPenetap();
		$pejabat_cek->selectByParams(array(), -1, -1, $statementPejabat);
		$pejabat_cek->firstRow();
		$reqPejabatPenetapId = $pejabat_cek->getField("PEJABAT_PENETAP_ID");

		//kalau pejabat tidak ada
		if($reqPejabatPenetapId == "")
		{
			$set_pejabat=new PejabatPenetap();
			$set_pejabat->setField('JABATAN', strtoupper($reqPejabatPenetap));
			$set_pejabat->setField("LAST_CREATE_USER", $userLogin->idUser);
			$set_pejabat->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
			$set_pejabat->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
			$set_pejabat->insert();
			$reqPejabatPenetapId=$set_pejabat->id;
			unset($set_pejabat);
		}

		if(is_numeric($reqStatusValidasi))
		{
			$set->setField('VALIDASI', $reqStatusValidasi);
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
			$set->setField('TEMP_VALIDASI_ID', $reqDataId);

			if($set->updatevalidasi())
			{
				$reqSimpan= "1";
			}
		}
		else
		{
		
			$reqSimpan= "";
			if($reqMode == "insert")
			{
				$set->setField("LAST_CREATE_USER", $userLogin->idUser);
				$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
				$set->setField('HUKUMAN_ID', ValToNullDB($reqRowId));
				if($set->insert())
				{
					$reqDataId=$set->id;
					$reqSimpan= 1;
				}
			}
			elseif($reqMode == "update")
			{	
				$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
				$set->setField('TEMP_VALIDASI_ID', $reqDataId);
				if($set->update())
				{
					$reqSimpan= 1;
				}
			}
		}

		if(!empty($fileName))
		{
			if(count($errors) === 0) 
			{

				$statement="";
				$set_upload= new Hukuman();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 6";
				$set_upload= new Hukuman();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				if($reqDataId == "")
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";
				}
				else
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqDataId."\\";
				}

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
				$ext = strtolower($path_parts['extension']);


				$renamefile = "HUKUMAN_".$tempNipBaru.".".$ext;
	
				$dest_path = $urlupload . $renamefile;
				// print_r($dest_path);exit;
				if(file_exists($dest_path))
				{
					unlink($dest_path);
				}

				$statement="";

				if(move_uploaded_file($reqLinkFile, $dest_path))
				{
					if($reqDataId == "")
					{
						$statement=" AND A.HUKUMAN_ID=".$reqRowId;
					}
					else
					{
						$statement=" AND A.TEMP_VALIDASI_ID=".$reqDataId;
					}
					$set_upload= new Hukuman();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE");
			
					$link_server=$dest_path;

					if(empty($tempLinkFile))
					{
						$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDataId));
						$set_upload->setField('HUKUMAN_ID', ValToNullDB($reqRowId));
						$set_upload->setField("LINK_FILE", $dest_path);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField("LINK_SERVER", $link_server);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$reqSimpan= 1;
					}
				}
			}
			else 
		    {
		    	foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
		    }
		}

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsonpegawaicutiadd()
	{
		$this->load->model("base-validasi/Cuti");

		$reqMode = $this->input->post("reqMode");
		$reqPegawaiId = $this->input->post('reqPegawaiId');
		$reqRowId= $this->input->post("reqRowId");
		$reqDataId= $this->input->post("reqDataId");
		$reqTahun= $this->input->post("reqTahun");
		$reqJenisCuti= $this->input->post("reqJenisCuti");
		$reqNoSurat= $this->input->post("reqNoSurat");
		$reqTanggalSurat= $this->input->post("reqTanggalSurat");
		$reqTanggalMulai= $this->input->post("reqTanggalMulai");
		$reqTanggalSelesai= $this->input->post("reqTanggalSelesai");
		$reqTanggalPermohonan= $this->input->post("reqTanggalPermohonan");
		$reqLama= $this->input->post("reqLama");
		$reqCutiKeterangan= $this->input->post("reqCutiKeterangan");
		$reqKeterangan= $this->input->post("reqKeterangan");
		$reqStatusValidasi= $this->input->post("reqStatusValidasi");

		$set = new Cuti();
		$set->setField('NO_SURAT', $reqNoSurat);
		$set->setField('JENIS_CUTI', ValToNullDB($reqJenisCuti));
		$set->setField('TANGGAL_PERMOHONAN', dateToDBCheck($reqTanggalPermohonan));
		$set->setField('TANGGAL_SURAT', dateToDBCheck($reqTanggalSurat));
		$set->setField('TANGGAL_MULAI', dateToDBCheck($reqTanggalMulai));
		$set->setField('TANGGAL_SELESAI', dateToDBCheck($reqTanggalSelesai));	
		$set->setField('LAMA', ValToNullDB($reqLama));
		$set->setField("CUTI_KETERANGAN", $reqCutiKeterangan);
		$set->setField('KETERANGAN', $reqKeterangan);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		
		$reqSimpan= "";
		if(is_numeric($reqStatusValidasi))
		{
			$set->setField('VALIDASI', $reqStatusValidasi);
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
			$set->setField('TEMP_VALIDASI_ID', $reqDataId);

			if($set->updatevalidasi())
			{
				$reqSimpan= "1";
			}
		}
		else
		{
			if($reqMode == "insert")
			{
				$set->setField("LAST_CREATE_USER", $userLogin->idUser);
				$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);	
				$set->setField('CUTI_ID', ValToNullDB($reqRowId));
				if($set->insert())
				{
					$reqSimpan= 1;
				}
			}
			elseif($reqMode == "update")
			{	
				$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
				$set->setField('TEMP_VALIDASI_ID', $reqDataId);
				if($set->update())
				{
					$reqSimpan= 1;
				}
			}
		}

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsonpegawaibahasaadd()
	{
		$this->load->model("base-validasi/Bahasa");

		$reqMode = $this->input->post("reqMode");
		$reqPegawaiId = $this->input->post('reqPegawaiId');
		$reqRowId= $this->input->post("reqRowId");
		$reqDataId= $this->input->post("reqDataId");
		$reqNamaBahasa= $this->input->post("reqNamaBahasa");
		$reqJenisBahasa= $this->input->post("reqJenisBahasa");
		$reqKemampuanBicara= $this->input->post("reqKemampuanBicara");
		$reqStatusValidasi= $this->input->post("reqStatusValidasi");
		// echo  $reqStatusValidasi;exit;

		$set = new Bahasa();
		$set->setField('NAMA', $reqNamaBahasa);
		$set->setField('JENIS', $reqJenisBahasa);
		$set->setField('KEMAMPUAN', $reqKemampuanBicara);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);

		$reqSimpan= "";
		if(is_numeric($reqStatusValidasi))
		{
			$set->setField('VALIDASI', $reqStatusValidasi);
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
			$set->setField('TEMP_VALIDASI_ID', $reqDataId);

			if($set->updatevalidasi())
			{
				$reqSimpan= "1";
			}
		}
		else
		{
			if($reqMode == "insert")
			{
				$set->setField("LAST_CREATE_USER", $userLogin->idUser);
				$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
				$set->setField('BAHASA_ID', ValToNullDB($reqRowId));
				if($set->insert())
				{
					$reqSimpan= 1;
				}
			}
			elseif($reqMode == "update")
			{	
				$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
				$set->setField('TEMP_VALIDASI_ID', $reqDataId);
				if($set->update())
				{
					$reqSimpan= 1;
				}
			}

		}
		// echo $set->query;exit;
		
		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsonpegawaibahasavalidasi()
	{
		$this->load->model("base-validasi/Bahasa");

		$reqMode = $this->input->post("reqMode");
		$reqPegawaiId = $this->input->post('reqPegawaiId');
		$reqRowId= $this->input->post("reqRowId");
		$reqTempValidasiId= $this->input->post("reqTempValidasiId");
		$reqValidasi= $this->input->post("reqValidasi");
		$reqStatusValidasi= $this->input->post("reqStatusValidasi");
		$reqTempValidasiHapusId= $this->input->post("reqTempValidasiHapusId");


		if(empty($reqStatusValidasi))
        {
            echo json_response(400, 'Isikan terlebih dahulu Status Klarifikasi.');
            exit;
        }
        elseif($reqStatusValidasi == "2")
        {
            if(empty($reqTempValidasiId) && !empty($reqTempValidasiHapusId))
            {
                $set->setField('TEMP_VALIDASI_ID', $reqTempValidasiHapusId);
                $reqSimpan= "";
                if($set->deletehapusdata())
                {
                    $reqSimpan= "1";
                }

            }
            else
            {
                $set->setField('VALIDASI', $reqStatusValidasi);
                $set->setField("LAST_LEVEL", $this->LOGIN_LEVEL);
                $set->setField("LAST_USER", $this->LOGIN_USER);
                $set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
                $set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
                $set->setField("LAST_DATE", "NOW()");
                $set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

                $reqSimpan= "";
                if($set->updatevalidasi())
                {
                    $reqSimpan= "1";
                }

            }

        }
        else
        {
        	$reqNamaBahasa= $this->input->post("reqNamaBahasa");
        	$reqJenisBahasa= $this->input->post("reqJenisBahasa");
        	$reqKemampuanBicara= $this->input->post("reqKemampuanBicara");

			$set = new Bahasa();
			$set->setField('NAMA', $reqNamaBahasa);
			$set->setField('JENIS', $reqJenisBahasa);
			$set->setField('KEMAMPUAN', $reqKemampuanBicara);
			$set->setField('PEGAWAI_ID', $reqPegawaiId);

			$set->setField('BAHASA_ID', $reqRowId);
            $set->setField('VALIDASI', ValToNullDB($reqStatusValidasi));
            $set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);
			
			$reqSimpan= "";

			if ($reqValidasi ==  1)
			{
				if(!empty($reqTempValidasiId))
				{
					$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
					$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
					$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
					if($set->update())
					{
						$set->updatetanggalvalidasi();
						$reqSimpan= "1";
					}
				}
				elseif(empty($reqTempValidasiId) && !empty($reqTempValidasiHapusId))
				{
					$set->setField('VALIDASI', $reqStatusValidasi);
					$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
					$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
					$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
					$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiHapusId);

					$reqSimpan= "";
					if($set->updatevalidasihapusdata())
					{
						$reqSimpan= "1";
					}
				}

			}
			else
			{
				if($reqMode == "insert")
				{
					$set->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set->setField('BAHASA_ID', ValToNullDB($reqRowId));
					if($set->insert())
					{
						$reqSimpan= 1;
					}
				}
				elseif($reqMode == "update")
				{	
					$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
					$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
					$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
					$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);
					if($set->update())
					{
						$reqSimpan= 1;
					}
				}
			}
			
		}


		// echo $set->query;exit;

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsonpegawaikursusadd()
	{
		$this->load->model("base-validasi/Kursus");

		$reqMode = $this->input->post("reqMode");
		$reqPegawaiId = $this->input->post('reqPegawaiId');
		$reqRowId= $this->input->post("reqRowId");
		$reqDataId= $this->input->post("reqDataId");

		$reqTipeKursus= $this->input->post("reqTipeKursus");
		$reqNamaKursus= $this->input->post("reqNamaKursus");
		$reqLamaKursus= $this->input->post("reqLamaKursus");
		$reqJenisBahasa= $this->input->post("reqJenisBahasa");
		$reqKemampuanBicara= $this->input->post("reqKemampuanBicara");
		$reqTanggalKursus= $this->input->post("reqTanggalKursus");
		$reqTahunKursus= $this->input->post("reqTahunKursus");
		$reqNoSertifikat= $this->input->post("reqNoSertifikat");
		$reqInstansiId= $this->input->post("reqInstansiId");
		$reqInstitusi= $this->input->post("reqInstitusi");
		$reqStatusValidasi= $this->input->post("reqStatusValidasi");

		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];

		if(!empty($fileName))
		{
			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFile']['size'] >= $maxsize) || ($_FILES["reqLinkFile"]["size"] == 0)) {
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if((!in_array($_FILES['reqLinkFile']['type'], $acceptable)) && (!empty($_FILES["reqLinkFile"]["type"]))) {
				$errors[] = 'File gagal diupload, Pastikan File berformat PDF';
			}

			if(count($errors) > 0) 
			{
				foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
			}
			
		}

		$set = new Kursus();
		$set->setField('NAMA', $reqNamaKursus);
		$set->setField('LAMA', ValToNullDB($reqLamaKursus));
		$set->setField('TANGGAL_MULAI', dateToDBCheck($reqTanggalKursus));
		$set->setField('INSTANSI_ID', ValToNullDB($reqInstansiId));
		$set->setField('TIPE',ValToNullDB($reqTipeKursus));
		$set->setField('NO_PIAGAM', $reqNoSertifikat);
		$set->setField('PENYELENGGARA', $reqInstitusi);
		$set->setField('TAHUN', ValToNullDB($reqTahunKursus));
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		
		$reqSimpan= "";
		if(is_numeric($reqStatusValidasi))
		{
			$set->setField('VALIDASI', $reqStatusValidasi);
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
			$set->setField('TEMP_VALIDASI_ID', $reqDataId);

			if($set->updatevalidasi())
			{
				$reqSimpan= "1";
			}
		}
		else
		{
			if($reqMode == "insert")
			{
				$set->setField("LAST_CREATE_USER", $userLogin->idUser);
				$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
				$set->setField('KURSUS_ID', ValToNullDB($reqRowId));
				if($set->insert())
				{
					$reqDataId = $set->id;
					$reqSimpan= 1;
				}
			}
			elseif($reqMode == "update")
			{	
				$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
				$set->setField('TEMP_VALIDASI_ID', $reqDataId);
				if($set->update())
				{
					$reqSimpan= 1;
				}
			}
		}

		if(!empty($fileName))
		{
			if(count($errors) === 0) 
			{

				$statement="";
				$set_upload= new Kursus();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 2";
				$set_upload= new Kursus();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				if($reqDataId == "")
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";
				}
				else
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqDataId."\\";
				}

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
				$ext = strtolower($path_parts['extension']);


				$renamefile = "BIMTEK_".$tempNipBaru.".".$ext;
	
				$dest_path = $urlupload . $renamefile;
				// print_r($dest_path);exit;
				if(file_exists($dest_path))
				{
					unlink($dest_path);
				}

				$statement="";

				if(move_uploaded_file($reqLinkFile, $dest_path))
				{
					if($reqDataId == "")
					{
						$statement=" AND A.KURSUS_ID=".$reqRowId;
					}
					else
					{
						$statement=" AND A.TEMP_VALIDASI_ID=".$reqDataId;
					}
					$set_upload= new Kursus();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE");
			
					$link_server=$dest_path;

					if(empty($tempLinkFile))
					{
						$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDataId));
						$set_upload->setField('KURSUS_ID', ValToNullDB($reqRowId));
						$set_upload->setField("LINK_FILE", $dest_path);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField("LINK_SERVER", $link_server);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$reqSimpan= 1;
					}
				}
			}
			else 
		    {
		    	foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
		    }
		}

		// echo $set->query;exit;

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsonpegawaidiklatadd()
	{
		$this->load->model("base-validasi/PegawaiDiklat");

		$reqMode = $this->input->post("reqMode");
		$reqPegawaiId = $this->input->post('reqPegawaiId');
		$reqRowId= $this->input->post("reqRowId");
		$reqDataId= $this->input->post("reqDataId");
		$reqNomor= $this->input->post("reqNomor");
		$reqTanggal= $this->input->post("reqTanggal");
		$reqTahun= $this->input->post("reqTahun");
		$reqDiklat= $this->input->post("reqDiklat");
		$reqStatusValidasi= $this->input->post("reqStatusValidasi");

		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];

		if(!empty($fileName))
		{
			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFile']['size'] >= $maxsize) || ($_FILES["reqLinkFile"]["size"] == 0)) {
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if((!in_array($_FILES['reqLinkFile']['type'], $acceptable)) && (!empty($_FILES["reqLinkFile"]["type"]))) {
				$errors[] = 'File gagal diupload, Pastikan File berformat PDF';
			}

			if(count($errors) > 0) 
			{
				foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
			}
			
		}

		$set = new PegawaiDiklat();
		$set->setField('NOMOR', $reqNomor);
		$set->setField('TANGGAL', dateToDBCheck($reqTanggal));
		$set->setField('TAHUN', ValToNullDB($reqTahun));
		$set->setField("DIKLAT_ID", $reqDiklat);
		$set->setField("PEGAWAI_ID", $reqPegawaiId);
		
		$reqSimpan= "";
		if(is_numeric($reqStatusValidasi))
		{
			$set->setField('VALIDASI', $reqStatusValidasi);
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
			$set->setField('TEMP_VALIDASI_ID', $reqDataId);

			if($set->updatevalidasi())
			{
				$reqSimpan= "1";
			}
		}
		else
		{
			if($reqMode == "insert")
			{
				$set->setField("LAST_CREATE_USER", $userLogin->idUser);
				$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);	
				$set->setField('PEGAWAI_DIKLAT_ID', ValToNullDB($reqRowId));
				if($set->insert())
				{
					$reqDataId = $set->id;
					$reqSimpan= 1;
				}
			}
			elseif($reqMode == "update")
			{	
				$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
				$set->setField('TEMP_VALIDASI_ID', $reqDataId);
				if($set->update())
				{
					$reqSimpan= 1;
				}
			}
		}

		if(!empty($fileName))
		{
			if(count($errors) === 0) 
			{

				$statement="";
				$set_upload= new PegawaiDiklat();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 14";
				$set_upload= new PegawaiDiklat();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				$statement=" AND B.DIKLAT_ID = ".$reqDiklat;
				$check= new PegawaiDiklat();
				$check->selectByParams(array(),-1,-1,$statement);
					// echo $check->query;exit;
				$check->firstRow();
				$reqDiklatNama=$check->getField("DIKLAT_KET");
				$reqDiklatNama= str_replace(" ", "_", $reqDiklatNama);

				if($reqDataId == "")
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";
				}
				else
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqDataId."\\";
				}

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
				$ext = strtolower($path_parts['extension']);


				$renamefile = "SK_".$reqDiklatNama."_".$tempNipBaru.".".$ext;
	
				$dest_path = $urlupload . $renamefile;
				// print_r($dest_path);exit;
				if(file_exists($dest_path))
				{
					unlink($dest_path);
				}

				$statement="";

				if(move_uploaded_file($reqLinkFile, $dest_path))
				{
					if($reqDataId == "")
					{
						$statement=" AND A.PEGAWAI_DIKLAT_ID=".$reqRowId;
					}
					else
					{
						$statement=" AND A.TEMP_VALIDASI_ID=".$reqDataId;
					}
					$set_upload= new PegawaiDiklat();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE");
			
					$link_server=$dest_path;

					if(empty($tempLinkFile))
					{
						$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDataId));
						$set_upload->setField('PEGAWAI_DIKLAT_ID', ValToNullDB($reqRowId));
						$set_upload->setField("LINK_FILE", $dest_path);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField("LINK_SERVER", $link_server);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$reqSimpan= 1;
					}
				}
			}
			else 
		    {
		    	foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
		    }
		}

		// echo $set->query;exit;

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}																												
	
	function jsonpangkatriwayat()
	{
		$this->load->model("base-validasi/PangkatRiwayat");

		$set= new PangkatRiwayat();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault);exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " AND A.PEGAWAI_ID = ".$this->pegawaiId;
		$sOrder = " ORDER BY A.TMT_PANGKAT ASC";
		$set->selectByParamsAdmin(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$folder = $this->config->item('simpeg');
			$reqLinkFileSk = $set->getField("LINK_FILE_SK");
			$reqLinkFileStlud = $set->getField("LINK_FILE_STLUD");
			$reqPegawaiId = $this->pegawaiId;
			$reqRowId = $set->getField("PANGKAT_RIWAYAT_ID");
			$reqFileId = $set->getField("PANGKAT_RIWAYAT_FILE_ID");
			$file_name_sk = "../".$folder."/uploads/PANGKAT_RIWAYAT/FOTO_BLOB-".$reqRowId.".pdf";
			$file_name_stlud = "../".$folder."/uploads/PANGKAT_RIWAYAT/FOTO_BLOB_STLUD-".$reqRowId.".pdf";
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "SK")
				{ 
					$row[$valkey]= '
					<a class="navi-link" onclick=\'btnUploadSk("'.$reqPegawaiId.'","'.$reqRowId.'");\'>
					<img src="images/upload.png" title="upload" style="width:20px;height:20px">
					</a>
					&nbsp;
					';

					if(!empty($reqLinkFileSk))
					{
						$reqMode="pangkat_sk";
						$row[$valkey].= '
						<a class="navi-link" href="app/loadUrl/main/viewer?reqForm=pangkat_sk&reqFileId='.$reqFileId.'" target="_blank">
						<img src="images/download.png" title="download" style="width:20px;height:20px">
						</a>
						&nbsp;
						';

						$row[$valkey].= '
						<a class="navi-link" onclick=\'btnDeleteFile("'.$reqFileId.'","'.$reqPegawaiId.'","'.$reqRowId.'","'.$reqMode.'");\'>
						<img src="images/delete-icon.png" title="delete" style="width:20px;height:20px">
						</a>
						';
					}
					else
					{
						if (file_exists($file_name_sk)) 
						{
							$row[$valkey]= '<a class="btn btn-primary font-weight-bold mr-2" href='.$file_name_sk.' target="_blank">Download</a>';
						}
					}
					
				}
				else if ($valkey == "STLUD")
				{ 
					$reqMode="pangkat_stlud";
					$row[$valkey]= '
					<a class="navi-link" onclick=\'btnUploadStlud("'.$reqPegawaiId.'","'.$reqRowId.'");\'>
					<img src="images/upload.png" title="upload" style="width:20px;height:20px">
					</a>
					&nbsp;
					';

					if(!empty($reqLinkFileStlud))
					{
						$row[$valkey].= '
						<a class="navi-link" href="app/loadUrl/main/viewer?reqForm=pangkat_stlud&reqFileId='.$reqFileId.'" target="_blank">
						<img src="images/download.png" title="download" style="width:20px;height:20px">
						</a>
						&nbsp;
						';

						$row[$valkey].= '
						<a class="navi-link" onclick=\'btnDeleteFile("'.$reqFileId.'","'.$reqPegawaiId.'","'.$reqRowId.'","'.$reqMode.'");\'>
						<img src="images/delete-icon.png" title="delete" style="width:20px;height:20px">
						</a>
						';
					}
					else
					{
						if (file_exists($file_name_stlud)) 
						{
							$row[$valkey]= '<a class="btn btn-primary font-weight-bold mr-2" href='.$file_name_stlud.' target="_blank">Download</a>';
						}
					}
					
				}
				else
				{
					$row[$valkey]= $set->getField($valkey);
				}
			}
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

	function jsonpangkatriwayatupload()
	{
		$this->load->model("base-validasi/PangkatRiwayat");
		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqRowId= $this->input->post("reqRowId");
		$reqLinkFileSk=$_FILES['reqLinkFileSk']['tmp_name'];
		$fileNameSk = $_FILES['reqLinkFileSk']['name'];
		$reqLinkFileStlud=$_FILES['reqLinkFileStlud']['tmp_name'];
		$fileNameSTLUD = $_FILES['reqLinkFileStlud']['name'];
	
	
		// print_r($reqLinkFileSk);exit();
		$reqSimpan="";
		if(!empty($fileNameSk) || !empty($fileNameSTLUD) )
		{

			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFileSk']['size'] >= $maxsize) || ($_FILES['reqLinkFileStlud']['size'] >= $maxsize) ) 
			{
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if(count($errors) === 0) 
			{
				$statement="";
				$set_upload= new PangkatRiwayat();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 16";
				$set_upload= new PangkatRiwayat();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$ext = "pdf";

				$renamefileSk = "SK_".$tempNipBaru.".".$ext;
				$renamefileStlud = "STLUD_".$tempNipBaru.".".$ext;
				

				$dest_path_sk = $urlupload . $renamefileSk;
				$dest_path_stlud = $urlupload . $renamefileStlud;
				
				if (!empty($fileNameSk))
				{
					if(file_exists($dest_path_sk))
					{
						unlink($dest_path_sk);
					}

				}
				else if (!empty($fileNameSTLUD))
				{
					if(file_exists($dest_path_stlud))
					{
						unlink($dest_path_stlud);
					}
				}

				$statement="";
				// print_r($dest_path_sk);exit;

				if(move_uploaded_file($reqLinkFileSk,$dest_path_sk))
				{
					$statement=" AND A.PANGKAT_RIWAYAT_ID=".$reqRowId;
					$set_upload= new PangkatRiwayat();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE_SK");
					$fileid=$set_upload->getField("PANGKAT_RIWAYAT_FILE_ID");
					
					$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_upload->setField("LINK_FILE_SK", $dest_path_sk);
					$set_upload->setField("NAMA_FILE_SK", $renamefileSk);
					$set_upload->setField("TIPE_FILE", $ext);
					$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
					$set_upload->setField('PANGKAT_RIWAYAT_ID', $reqRowId);


					$nama="NAMA_FILE_SK";
					$link="LINK_FILE_SK";
					if(empty($fileid) && empty($tempLinkFile))
					{
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$set_upload->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('PANGKAT_RIWAYAT_FILE_ID', $fileid);
						if($set_upload->updateupload($nama,$link))
						{
							$reqSimpan= 1;
						}
					}
				}

				if(move_uploaded_file($reqLinkFileStlud, $dest_path_stlud))
				{
					$statement=" AND A.PANGKAT_RIWAYAT_ID=".$reqRowId;
					$set_upload= new PangkatRiwayat();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE_SK");
					$fileid=$set_upload->getField("PANGKAT_RIWAYAT_FILE_ID");
					
					$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_upload->setField("LINK_FILE_STLUD", $dest_path_stlud);
					$set_upload->setField("NAMA_FILE_STLUD", $renamefileStlud);
					$set_upload->setField("TIPE_FILE", $ext);
					$set_upload->setField('PANGKAT_RIWAYAT_ID', $reqRowId);
					$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);

					$nama="NAMA_FILE_STLUD";
					$link="LINK_FILE_STLUD";
					if(empty($fileid) && empty($tempLinkFile))
					{
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$set_upload->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('PANGKAT_RIWAYAT_FILE_ID', $fileid);
						if($set_upload->updateupload($nama,$link))
						{
							$reqSimpan= 1;
						}
					}
				}
			}
			else 
		    {
		    	foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
		    }
		}
		

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'File berhasil diupload');
		}
		else
		{
			echo json_response(400, 'File gagal diupload, Cek kembali file anda atau hubungi admin');
		}
		
		
		// print_r($reqDetilId);exit;		
	}

	function jsonpangkatriwayatdelete()
	{
		$this->load->model("base-validasi/PangkatRiwayat");
		$reqDetilId= $this->input->get("reqDetilId");
		$set= new PangkatRiwayat();
		$set->setField('PANGKAT_RIWAYAT_ID', $reqDetilId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
		if($set->deleteadmin())	
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

	function jsonpangkatriwayatdeletefile()
	{
		$this->load->model("base-validasi/PangkatRiwayat");
		$reqFileId= $this->input->get("reqFileId");
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqMode= $this->input->get("reqMode");
		$set= new PangkatRiwayat();
		$set->setField('PANGKAT_RIWAYAT_FILE_ID', $reqFileId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);

		$set_upload = new PangkatRiwayat();
		
		$set_upload->selectByParams(array('A.PEGAWAI_ID'=>$reqPegawaiId));

		$set_upload->firstRow();

		$reqSimpan="";
		if($reqMode=="pangkat_sk")
		{
			$reqLinkFile= $set_upload->getField('LINK_FILE_SK');
			$reqNama="NAMA_FILE_SK";
			$reqLink="LINK_FILE_SK";
			$reqTipe="TIPE_FILE";
			if($set->deletefile($reqNama,$reqLink,$reqTipe))	
			{			
				$reqSimpan=1;
			}
		}
		else if($reqMode=="pangkat_stlud")
		{
			$reqLinkFile= $set_upload->getField('LINK_FILE_STLUD');
			$reqNama="NAMA_FILE_STLUD";
			$reqLink="LINK_FILE_STLUD";
			$reqTipe="TIPE_FILE";
			if($set->deletefile($reqNama,$reqLink,$reqTipe))	
			{			
				$reqSimpan=1;
			}
		}

		 // print_r($reqLinkFile);exit;

		if(file_exists($reqLinkFile))
		{
			unlink($reqLinkFile);
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
	function jsonpegawaijabatan()
	{
		$this->load->model("base-validasi/JabatanRiwayat");

		$set= new JabatanRiwayat();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " AND A.PEGAWAI_ID = ".$this->pegawaiId;
		$sOrder = "";
		$set->selectByParamsAdmin(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else
					$row[$valkey]= $set->getField($valkey);
			}
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
	function jsonjabatanriwayatdelete()
	{
		$this->load->model("base-validasi/JabatanRiwayat");
		$reqDetilId= $this->input->get("reqDetilId");
		$set= new JabatanRiwayat();
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
		if($set->deleteMaster())	
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
	function jsonpegawairiwayatgaji()
	{
		$this->load->model("base-validasi/GajiRiwayat");

		$set= new GajiRiwayat();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " AND A.PEGAWAI_ID = ".$this->pegawaiId;
		$sOrder = " ORDER BY A.TMT_SK ASC";
		$set->selectByParamsAdmin(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$reqLinkFile = $set->getField("LINK_FILE");
			$reqFileId = $set->getField("GAJI_RIWAYAT_FILE_ID");
			$reqPegawaiId = $this->pegawaiId;
			$reqDetilId = $set->getField("TEMP_VALIDASI_ID");
			$reqRowId = $set->getField("GAJI_RIWAYAT_ID");
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "KETERANGAN")
				{ 
					$row[$valkey]= '
					<a class="navi-link" onclick=\'btnUpload("'.$reqDetilId.'","'.$reqPegawaiId.'","'.$reqRowId.'");\'>
					<img src="images/upload.png" title="upload" style="width:20px;height:20px">
					</a>
					&nbsp;
					';

					if(!empty($reqLinkFile))
					{
						$row[$valkey].= '
						<a class="navi-link" href="app/loadUrl/main/viewer?reqForm=gaji&reqFileId='.$reqFileId.'" target="_blank">
						<img src="images/download.png" title="download" style="width:20px;height:20px">
						</a>
						&nbsp;
						';

						$row[$valkey].= '
						<a class="navi-link" onclick=\'btnDeleteFile("'.$reqFileId.'","'.$reqPegawaiId.'","'.$reqDetilId.'","'.$reqRowId.'");\'>
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

	function jsonriwayatgajiupload()
	{
		$this->load->model("base-validasi/GajiRiwayat");
		$reqDetilId= $this->input->post("reqDetilId");
		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqRowId= $this->input->post("reqRowId");
		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];

		$statement="";
		$set_upload= new GajiRiwayat();
		$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
		$set_upload->firstRow();
		$tempNipBaru=$set_upload->getField("NIP_BARU");
		unset($set_upload);

		if(!empty($fileName))
		{

			$statement="";
			$set_upload= new GajiRiwayat();
			$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
			$set_upload->firstRow();
			$tempNipBaru=$set_upload->getField("NIP_BARU");
			unset($set_upload);

			$statement=" AND LINK_SERVER_ID = 12";
			$set_upload= new GajiRiwayat();
			$set_upload->selectByParamsServer(array(),-1,-1,$statement);
				// echo $set_upload->query;exit;
			$set_upload->firstRow();
			$tempLinkServer=$set_upload->getField("LINK_SERVER");
			$tempLinkFolder=$set_upload->getField("FOLDER");

			if($reqDetilId == "")
			{
				$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";
			}
			else
			{
				$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqDetilId."\\";
			}


			if (!is_dir($urlupload)) {
				makedirs($urlupload);
			}
			
			$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
			$ext = strtolower($path_parts['extension']);

			$renamefile = "GAJI_RIWAYAT_".$tempNipBaru.".".$ext;

			$dest_path = $urlupload . $renamefile;
			//print_r($reqLinkFile);exit;
			if(file_exists($dest_path))
			{
				unlink($dest_path);
			}

			$reqSimpan="";
			$statement="";

			if(move_uploaded_file($reqLinkFile, $dest_path))
			{
				if($reqDetilId == "")
				{
					$statement=" AND A.GAJI_RIWAYAT_ID=".$reqRowId;
				}
				else
				{
					$statement=" AND A.TEMP_VALIDASI_ID=".$reqDetilId;
				}
				$set_upload= new GajiRiwayat();
				$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkFile=$set_upload->getField("LINK_FILE");
				
				// print_r($tempLinkFile);exit;
		
				$link_server=$dest_path;

				if(empty($tempLinkFile))
				{
					$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDetilId));
					$set_upload->setField('GAJI_RIWAYAT_ID', ValToNullDB($reqRowId));
					$set_upload->setField("LINK_FILE", $dest_path);
					$set_upload->setField("NAMA_FILE", $renamefile);
					$set_upload->setField("TIPE_FILE", $ext);
					$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
					if($set_upload->insertupload())
					{
						$reqSimpan= 1;
					}
					// echo $set_upload->query;exit;
				}
				else
				{
					$reqSimpan= 1;
				}
			}
		}

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'File berhasil diupload');
		}
		else
		{
			echo json_response(400, 'File gagal diupload, Cek kembali file anda atau hubungi admin');
		}
		
		
		// print_r($reqDetilId);exit;		
	}
	function jsonriwayatgajidelete()
	{
		$this->load->model("base-validasi/GajiRiwayat");
		$reqDetilId= $this->input->get("reqDetilId");
		$set= new GajiRiwayat();
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
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
	function jsonriwayatgajideletefile()
	{
		$this->load->model("base-validasi/GajiRiwayat");
		$reqFileId= $this->input->get("reqFileId");
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqDetilId= $this->input->get("reqDetilId");
		$reqRowId= $this->input->get("reqRowId");
		$set= new GajiRiwayat();
		$set->setField('GAJI_RIWAYAT_FILE_ID', $reqFileId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);

		$set_upload = new GajiRiwayat();
		if($reqDetilId == "")
		{
			$set_upload->selectByParamsAdmin(array('A.GAJI_RIWAYAT_FILE_ID'=>$reqRowId));
		}
		else
		{
			$set_upload->selectByParamsAdmin(array('A.TEMP_VALIDASI_ID'=>$reqDetilId));
		}
		$set_upload->firstRow();
		$reqLinkFile= $set_upload->getField('LINK_FILE');


		$reqSimpan="";

		if(file_exists($reqLinkFile))
		{
			unlink($reqLinkFile);
		}

		if($set->deletefile())	
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
	function jsonpendidikanumum()
	{
		$this->load->model("base-validasi/PendidikanRiwayat");

		$adminuserid= $this->adminuserid;


		$set= new PendidikanRiwayat();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " AND A.PEGAWAI_ID = ".$this->pegawaiId;
		// if (!empty($adminuserid))
		// {
		// 	$statement.= " AND TEMP_VALIDASI_ID IS NOT NULL";
		// }
		$sOrder = "";
		$set->selectByParamsAdmin(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else
					$row[$valkey]= $set->getField($valkey);
			}
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
	function jsonpendidikanumumdelete()
	{
		$this->load->model("base-validasi/PendidikanRiwayat");
		$reqDetilId= $this->input->get("reqDetilId");
		$set= new PendidikanRiwayat();
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
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
					
	function jsondiklatstruktural()
	{
		$this->load->model("base-validasi/DiklatStruktural");

		$set= new DiklatStruktural();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " AND PEGAWAI_ID = ".$this->pegawaiId;
		$sOrder = "";
		$set->selectByParams(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else
					$row[$valkey]= $set->getField($valkey);
			}
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
	function jsondiklatstrukturaldelete()
	{
		$this->load->model("base-validasi/DiklatStruktural");
		$reqDetilId= $this->input->get("reqDetilId");
		$set= new DiklatStruktural();
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
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
	function jsondiklatfungsional()
	{
		$this->load->model("base-validasi/DiklatFungsional");

		$set= new DiklatFungsional();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " AND PEGAWAI_ID = ".$this->pegawaiId;
		$sOrder = "";
		$set->selectByParams(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else
					$row[$valkey]= $set->getField($valkey);
			}
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
	function jsondiklatfungsionaldelete()
	{
		$this->load->model("base-validasi/DiklatFungsional");
		$reqDetilId= $this->input->get("reqDetilId");
		$set= new DiklatFungsional();
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
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
	function jsondiklatteknis()
	{
		$this->load->model("base-validasi/DiklatTeknis");

		$set= new DiklatTeknis();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " AND PEGAWAI_ID = ".$this->pegawaiId;
		$sOrder = "";
		$set->selectByParams(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else
					$row[$valkey]= $set->getField($valkey);
			}
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

	function jsondiklatteknisdelete()
	{
		$this->load->model("base-validasi/DiklatTeknis");
		$reqDetilId= $this->input->get("reqDetilId");
		$set= new DiklatTeknis();
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
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
	function jsonpegawaiseminar()
	{
		$this->load->model("base-validasi/Seminar");

		$set= new Seminar();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " AND PEGAWAI_ID = ".$this->pegawaiId;
		$sOrder = "";
		$set->selectByParams(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else
					$row[$valkey]= $set->getField($valkey);
			}
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
	function jsonpegawaiseminardelete()
	{
		$this->load->model("base-validasi/Seminar");
		$reqDetilId= $this->input->get("reqDetilId");
		$set= new Seminar();
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
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
	function jsonpegawaisuamiistri()
	{
		$this->load->model("base-validasi/SuamiIstri");

		$adminuserid= $this->adminuserid;

		$set= new SuamiIstri();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " AND A.PEGAWAI_ID = ".$this->pegawaiId;
		if (!empty($adminuserid))
		{
			$statement.= " AND A.TEMP_VALIDASI_ID IS NOT NULL";
		}
		$sOrder = "";
		$set->selectByParams(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$folder = $this->config->item('simpeg');
			$reqLinkFileKK = $set->getField("LINK_FILE_KK");
			$reqLinkFileAkta = $set->getField("LINK_FILE_AKTA");
			$reqLinkFileKtp = $set->getField("LINK_FILE_KTP");
			$reqPegawaiId = $this->pegawaiId;
			$reqRowId = $set->getField("SUAMI_ISTRI_ID");
			$reqFileId = $set->getField("SUAMI_ISTRI_FILE_ID");
			$reqDetilId = $set->getField("TEMP_VALIDASI_ID");
			$file_name_kk = "../".$folder."/uploads/suami_istri/KK-".$reqRowId.".pdf";
			$file_name_ktp = "../".$folder."/uploads/suami_istri/KTP-".$reqRowId.".pdf";
			$file_name_akta = "../".$folder."/uploads/suami_istri/FOTO_BLOB-".$reqRowId.".pdf";
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "AKTA_NIKAH")
				{ 
					$row[$valkey]= '
					<a class="navi-link" onclick=\'btnUploadAkta("'.$reqDetilId.'","'.$reqPegawaiId.'","'.$reqRowId.'");\'>
					<img src="images/upload.png" title="upload" style="width:20px;height:20px">
					</a>
					&nbsp;
					';

					if(!empty($reqLinkFileAkta))
					{
						$reqMode="akta";
						$row[$valkey].= '
						<a class="navi-link" href="app/loadUrl/main/viewer?reqForm=akta&reqFileId='.$reqFileId.'" target="_blank">
						<img src="images/download.png" title="download" style="width:20px;height:20px">
						</a>
						&nbsp;
						';

						$row[$valkey].= '
						<a class="navi-link" onclick=\'btnDeleteFile("'.$reqFileId.'","'.$reqPegawaiId.'","'.$reqDetilId.'","'.$reqRowId.'","'.$reqMode.'");\'>
						<img src="images/delete-icon.png" title="delete" style="width:20px;height:20px">
						</a>
						';
					}
					else
					{
						if (file_exists($file_name_akta)) 
						{
							$row[$valkey]= '<a class="btn btn-primary font-weight-bold mr-2" href='.$file_name_akta.' target="_blank">Download</a>';
						}
					}
					
				}
				else if ($valkey == "KARTU_KELUARGA")
				{ 
					$reqMode="kk";
					$row[$valkey]= '
					<a class="navi-link" onclick=\'btnUploadKK("'.$reqDetilId.'","'.$reqPegawaiId.'","'.$reqRowId.'");\'>
					<img src="images/upload.png" title="upload" style="width:20px;height:20px">
					</a>
					&nbsp;
					';

					if(!empty($reqLinkFileKK))
					{
						$row[$valkey].= '
						<a class="navi-link" href="app/loadUrl/main/viewer?reqForm=kk&reqFileId='.$reqFileId.'" target="_blank">
						<img src="images/download.png" title="download" style="width:20px;height:20px">
						</a>
						&nbsp;
						';

						$row[$valkey].= '
						<a class="navi-link" onclick=\'btnDeleteFile("'.$reqFileId.'","'.$reqPegawaiId.'","'.$reqDetilId.'","'.$reqRowId.'","'.$reqMode.'");\'>
						<img src="images/delete-icon.png" title="delete" style="width:20px;height:20px">
						</a>
						';
					}
					else
					{
						if (file_exists($file_name_kk)) 
						{
							$row[$valkey]= '<a class="btn btn-primary font-weight-bold mr-2" href='.$file_name_kk.' target="_blank">Download</a>';
						}
					}
					
				}
				else if ($valkey == "KTP")
				{ 
					$reqMode="ktp";
					$row[$valkey]= '
					<a class="navi-link" onclick=\'btnUploadKtp("'.$reqDetilId.'","'.$reqPegawaiId.'","'.$reqRowId.'");\'>
					<img src="images/upload.png" title="upload" style="width:20px;height:20px">
					</a>
					&nbsp;
					';

					if(!empty($reqLinkFileKtp))
					{
						$row[$valkey].= '
						<a class="navi-link" href="app/loadUrl/main/viewer?reqForm=ktp&reqFileId='.$reqFileId.'" target="_blank">
						<img src="images/download.png" title="download" style="width:20px;height:20px">
						</a>
						&nbsp;
						';

						$row[$valkey].= '
						<a class="navi-link" onclick=\'btnDeleteFile("'.$reqFileId.'","'.$reqPegawaiId.'","'.$reqDetilId.'","'.$reqRowId.'","'.$reqMode.'");\'>
						<img src="images/delete-icon.png" title="delete" style="width:20px;height:20px">
						</a>
						';
					}
					else
					{
						if (file_exists($file_name_ktp)) 
						{
							$row[$valkey]= '<a class="btn btn-primary font-weight-bold mr-2" href='.$file_name_ktp.' target="_blank">Download</a>';
						}
					}

					
				}
				else
				{
					$row[$valkey]= $set->getField($valkey);
				}
			}
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

	function jsonpegawaisuamiistriupload()
	{
		$this->load->model("base-validasi/SuamiIstri");
		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqRowId= $this->input->post("reqRowId");
		$reqLinkFileKK=$_FILES['reqLinkFileKK']['tmp_name'];
		$fileNameKK = $_FILES['reqLinkFileKK']['name'];
		$reqLinkFileAkta=$_FILES['reqLinkFileAkta']['tmp_name'];
		$fileNameAkta = $_FILES['reqLinkFileAkta']['name'];
		$reqLinkFileKtp=$_FILES['reqLinkFileKtp']['tmp_name'];
		$fileNameKtp = $_FILES['reqLinkFileKtp']['name'];
	
		// print_r($reqRowId);exit();
		$reqSimpan="";
		if(!empty($fileNameKK) || !empty($fileNameAkta) || !empty($fileNameKtp))
		{

			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFileKK']['size'] >= $maxsize) || ($_FILES['reqLinkFileAkta']['size'] >= $maxsize) || ($_FILES['reqLinkFileKtp']['size'] >= $maxsize)) 
			{
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if(count($errors) === 0) 
			{

				$statement="";
				$set_upload= new SuamiIstri();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 15";
				$set_upload= new SuamiIstri();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				if($reqDetilId == "")
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";
				}
				else
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqDetilId."\\";
				}


				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$ext = "pdf";


				$renamefileKK= "KK_".$tempNipBaru.".".$ext;
				$renamefileAkta = "AKTA_".$tempNipBaru.".".$ext;
				$renamefileKtp = "KTP_".$tempNipBaru.".".$ext;

				$dest_path_kk = $urlupload . $renamefileKK;
				$dest_path_akta = $urlupload . $renamefileAkta;
				$dest_path_ktp = $urlupload . $renamefileKtp;
	
				// print_r($dest_path);exit;
				if (!empty($fileNameKK))
				{
					if(file_exists($dest_path_kk))
					{
						unlink($dest_path_kk);
					}

				}
				else if (!empty($fileNameAkta))
				{
					if(file_exists($dest_path_akta))
					{
						unlink($dest_path_akta);
					}
				}
				else if (!empty($fileNameKtp))
				{
					if(file_exists($dest_path_ktp))
					{
						unlink($dest_path_ktp);
					}
				}

				$statement="";

				if(move_uploaded_file($reqLinkFileKK, $dest_path_kk))
				{
					if($reqDetilId == "")
					{
						$statement=" AND A.SUAMI_ISTRI_ID=".$reqRowId;
					}
					else
					{
						$statement=" AND A.TEMP_VALIDASI_ID=".$reqDetilId;
					}
					$set_upload= new SuamiIstri();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE_KK");
					$fileid=$set_upload->getField("SUAMI_ISTRI_FILE_ID");
					
					$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_upload->setField("LINK_FILE_KK", $dest_path_kk);
					$set_upload->setField("NAMA_FILE_KK", $renamefileKK);
					$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDetilId));
					$set_upload->setField('SUAMI_ISTRI_ID', $reqRowId);
					$set_upload->setField("TIPE_FILE", $ext);
					$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);

					$nama="NAMA_FILE_KK";
					$link="LINK_FILE_KK";
					if(empty($fileid) && empty($tempLinkFile))
					{
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$set_upload->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('SUAMI_ISTRI_FILE_ID', $fileid);
						if($set_upload->updateupload($nama,$link))
						{
							$reqSimpan= 1;
						}
					}
				}

				if(move_uploaded_file($reqLinkFileAkta, $dest_path_akta))
				{
					if($reqDetilId == "")
					{
						$statement=" AND A.SUAMI_ISTRI_ID=".$reqRowId;
					}
					else
					{
						$statement=" AND A.TEMP_VALIDASI_ID=".$reqDetilId;
					}
					$set_upload= new SuamiIstri();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE_AKTA");
					$fileid=$set_upload->getField("SUAMI_ISTRI_FILE_ID");
					
					$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_upload->setField("LINK_FILE_AKTA", $dest_path_akta);
					$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDetilId));
					$set_upload->setField("NAMA_FILE_AKTA", $renamefileAkta);
					$set_upload->setField('SUAMI_ISTRI_ID', $reqRowId);
					$set_upload->setField("TIPE_FILE", $ext);
					$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);

					$nama="NAMA_FILE_AKTA";
					$link="LINK_FILE_AKTA";
					if(empty($fileid) && empty($tempLinkFile))
					{
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$set_upload->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('SUAMI_ISTRI_FILE_ID', $fileid);
						if($set_upload->updateupload($nama,$link))
						{
							$reqSimpan= 1;
						}
					}
				}

				if(move_uploaded_file($reqLinkFileKtp, $dest_path_ktp) )
				{
					if($reqDetilId == "")
					{
						$statement=" AND A.SUAMI_ISTRI_ID=".$reqRowId;
					}
					else
					{
						$statement=" AND A.TEMP_VALIDASI_ID=".$reqDetilId;
					}
					$set_upload= new SuamiIstri();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE_KK");
					$fileid=$set_upload->getField("SUAMI_ISTRI_FILE_ID");
					
					$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_upload->setField("LINK_FILE_KTP", $dest_path_ktp);
					$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDetilId));
					$set_upload->setField("NAMA_FILE_KTP", $renamefileKtp);
					$set_upload->setField('SUAMI_ISTRI_ID', $reqRowId);
					$set_upload->setField("TIPE_FILE", $ext);
					$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);

					$nama="NAMA_FILE_KTP";
					$link="LINK_FILE_KTP";
					if(empty($fileid) && empty($tempLinkFile))
					{
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$set_upload->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('SUAMI_ISTRI_FILE_ID', $fileid);
						if($set_upload->updateupload($nama,$link))
						{
							$reqSimpan= 1;
						}
					}
				}
			}
			else 
		    {
		    	foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
		    }
		}
		

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'File berhasil diupload');
		}
		else
		{
			echo json_response(400, 'File gagal diupload, Cek kembali file anda atau hubungi admin');
		}
		
		
		// print_r($reqDetilId);exit;		
	}

	function jsonpegawaisuamiistridelete()
	{
		$this->load->model("base-validasi/SuamiIstri");
		$reqDetilId= $this->input->get("reqDetilId");
		$set= new SuamiIstri();
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
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

	function jsonpegawaisuamiistrideletefile()
	{
		$this->load->model("base-validasi/SuamiIstri");
		$reqFileId= $this->input->get("reqFileId");
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqDetilId= $this->input->get("reqDetilId");
		$reqMode= $this->input->get("reqMode");
		$set= new SuamiIstri();
		$set->setField('SUAMI_ISTRI_FILE_ID', $reqFileId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);

		$set_upload = new SuamiIstri();
		
		if($reqDetilId == "")
		{
			$set_upload->selectByParams(array('A.SUAMI_ISTRI_ID'=>$reqRowId));
		}
		else
		{
			$set_upload->selectByParams(array('A.TEMP_VALIDASI_ID'=>$reqDetilId));
		}

		$set_upload->firstRow();

		$reqSimpan="";
		if($reqMode=="kk")
		{
			$reqLinkFile= $set_upload->getField('LINK_FILE_KK');
			$reqNama="NAMA_FILE_KK";
			$reqLink="LINK_FILE_KK";
			$reqTipe="TIPE_FILE";
			if($set->deletefile($reqNama,$reqLink,$reqTipe))	
			{			
				$reqSimpan=1;
			}
		}
		else if($reqMode=="akta")
		{
			$reqLinkFile= $set_upload->getField('LINK_FILE_AKTA');
			$reqNama="NAMA_FILE_AKTA";
			$reqLink="LINK_FILE_AKTA";
			$reqTipe="TIPE_FILE";
			if($set->deletefile($reqNama,$reqLink,$reqTipe))	
			{			
				$reqSimpan=1;
			}
		}
		else if($reqMode=="ktp")
		{
			$reqLinkFile= $set_upload->getField('LINK_FILE_KTP');
			$reqNama="NAMA_FILE_KTP";
			$reqLink="LINK_FILE_KTP";
			$reqTipe="TIPE_FILE";
			if($set->deletefile($reqNama,$reqLink,$reqTipe))	
			{			
				$reqSimpan=1;
			}
		}

		 // print_r($reqLinkFile);exit;

		if(file_exists($reqLinkFile))
		{
			unlink($reqLinkFile);
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
	function jsonpegawaianak()
	{
		$this->load->model("base-data/Anak");

		$adminuserid= $this->adminuserid;

		$set= new Anak();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " AND A.PEGAWAI_ID = ".$this->pegawaiId;

		$sOrder = "";
		$set->selectByParams(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$reqLinkFile = $set->getField("LINK_FILE");
			$reqPegawaiId = $this->pegawaiId;
			$reqRowId = $set->getField("ANAK_ID");
			$reqFileId = $set->getField("ANAK_FILE_ID");
			$reqLinkFileServer = $set->getField("LINK_SERVER");

			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "KETERANGAN")
				{ 
					$row[$valkey]= '
					<a class="navi-link" onclick=\'btnUpload("'.$reqPegawaiId.'","'.$reqRowId.'");\'>
					<img src="images/upload.png" title="upload" style="width:20px;height:20px">
					</a>
					&nbsp;
					';

					if(!empty($reqLinkFileServer))
					{
						$row[$valkey].= '
						<a class="navi-link" href="app/loadUrl/main/viewer?reqForm=anak&reqFileId='.$reqFileId.'" target="_blank">
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

	function jsonpegawaianakupload()
	{
		$this->load->model("base-data/Anak");
		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqRowId= $this->input->post("reqRowId");
		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];
	
		// print_r($reqRowId);exit();
		$reqSimpan="";
		if(!empty($fileName))
		{
			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFile']['size'] >= $maxsize) || ($_FILES["reqLinkFile"]["size"] == 0)) {
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if((!in_array($_FILES['reqLinkFile']['type'], $acceptable)) && (!empty($_FILES["reqLinkFile"]["type"]))) {
				$errors[] = 'File gagal diupload, Pastikan File berformat PDF';
			}

			if(count($errors) === 0) 
			{

				$statement="";
				$set_upload= new Anak();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 7";
				$set_upload= new Anak();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
				$ext = strtolower($path_parts['extension']);


				$renamefile = "ANAK_".$tempNipBaru.".".$ext;
	
				$dest_path = $urlupload . $renamefile;
				// print_r($dest_path);exit;
				if(file_exists($dest_path))
				{
					unlink($dest_path);
				}

				$statement="";

				if(move_uploaded_file($reqLinkFile, $dest_path))
				{
					$statement=" AND A.ANAK_ID=".$reqRowId;
					$set_upload= new Anak();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE");
			
					$link_server=$dest_path;

					if(empty($tempLinkFile))
					{
						$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('ANAK_ID', ValToNullDB($reqRowId));
						$set_upload->setField("LINK_FILE", $dest_path);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField("LINK_SERVER", $link_server);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$reqSimpan= 1;
					}
				}
			}
			else 
		    {
		    	foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
		    }
		}
		

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'File berhasil diupload');
		}
		else
		{
			echo json_response(400, 'File gagal diupload, Cek kembali file anda atau hubungi admin');
		}
		
		
		// print_r($reqDetilId);exit;		
	}
	function jsonpegawaianakdelete()
	{
		$this->load->model("base-data/Anak");
		$reqDetilId= $this->input->get("reqDetilId");
		$set= new Anak();
		$set->setField('ANAK_ID', $reqDetilId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
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

	function jsonpegawaianakdeletefile()
	{
		$this->load->model("base-data/Anak");
		$reqFileId= $this->input->get("reqFileId");
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqRowId= $this->input->get("reqRowId");
		$set= new Anak();
		$set->setField('ANAK_FILE_ID', $reqFileId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);

		$set_upload = new Anak();
		
		$set_upload->selectByParams(array('A.ANAK_ID'=>$reqRowId));
		
		$set_upload->firstRow();
		$reqLinkFile= $set_upload->getField('LINK_SERVER');
		// echo $reqRowId;exit;		
		$reqSimpan="";

		if(file_exists($reqLinkFile))
		{
			unlink($reqLinkFile);
		}

		if($set->deletefile())	
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
	function jsonpegawaiorganisasi()
	{
		$this->load->model("base-validasi/OrganisasiRiwayat");

		$adminuserid= $this->adminuserid;

		$set= new OrganisasiRiwayat();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " AND PEGAWAI_ID = ".$this->pegawaiId;
		if (!empty($adminuserid))
		{
			$statement.= " AND TEMP_VALIDASI_ID IS NOT NULL";
		}
		$sOrder = "";
		$set->selectByParams(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else
					$row[$valkey]= $set->getField($valkey);
			}
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

	function jsonpegawaiorganisasidelete()
	{
		$this->load->model("base-validasi/OrganisasiRiwayat");
		$reqDetilId= $this->input->get("reqDetilId");
		$set= new OrganisasiRiwayat();
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
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
	function jsonpegawaipenghargaandelete()
	{
		$this->load->model("base-validasi/Penghargaan");
		$reqDetilId= $this->input->get("reqDetilId");
		$set= new Penghargaan();
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
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

	function jsonpegawaipenilaiandelete()
	{
		$this->load->model("base-validasi/PotensiDiri");
		$reqDetilId= $this->input->get("reqDetilId");
		$set= new PotensiDiri();
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
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
	function jsonpegawaipenghargaan()
	{
		$this->load->model("base-validasi/Penghargaan");

		$adminuserid= $this->adminuserid;

		$set= new Penghargaan();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " AND A.PEGAWAI_ID = ".$this->pegawaiId;
		if (!empty($adminuserid))
		{
			$statement.= " AND A.TEMP_VALIDASI_ID IS NOT NULL";
		}
		$sOrder = "";
		$set->selectByParams(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$reqLinkFile = $set->getField("LINK_FILE");
			$reqPegawaiId = $this->pegawaiId;
			$reqRowId = $set->getField("PENGHARGAAN_ID");
			$reqFileId = $set->getField("PENGHARGAAN_FILE_ID");
			$reqLinkFileServer = $set->getField("LINK_SERVER");
			$reqDetilId = $set->getField("TEMP_VALIDASI_ID");
			$reqTahun = $set->getField("TAHUN");
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "KETERANGAN")
				{ 
					$row[$valkey]= '
					<a class="navi-link" onclick=\'btnUpload("'.$reqDetilId.'","'.$reqPegawaiId.'","'.$reqTahun.'","'.$reqRowId.'");\'>
					<img src="images/upload.png" title="upload" style="width:20px;height:20px">
					</a>
					&nbsp;
					';

					if(!empty($reqLinkFileServer))
					{
						$row[$valkey].= '
						<a class="navi-link" href="app/loadUrl/main/viewer?reqForm=penghargaan&reqFileId='.$reqFileId.'" target="_blank">
						<img src="images/download.png" title="download" style="width:20px;height:20px">
						</a>
						&nbsp;
						';

						$row[$valkey].= '
						<a class="navi-link" onclick=\'btnDeleteFile("'.$reqFileId.'","'.$reqPegawaiId.'","'.$reqDetilId.'","'.$reqRowId.'");\'>
						<img src="images/delete-icon.png" title="delete" style="width:20px;height:20px">
						</a>
						';
					}
				}
				else
					$row[$valkey]= $set->getField($valkey);
			}
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

	function jsonpegawaipenghargaanupload()
	{
		$this->load->model("base-validasi/Penghargaan");
		$reqDetilId= $this->input->post("reqDetilId");
		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqRowId= $this->input->post("reqRowId");
		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];
		$reqTahun= $this->input->post("reqTahun");
	
		// print_r($reqRowId);exit();
		$reqSimpan="";
		if(!empty($fileName))
		{
			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFile']['size'] >= $maxsize) || ($_FILES["reqLinkFile"]["size"] == 0)) {
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if((!in_array($_FILES['reqLinkFile']['type'], $acceptable)) && (!empty($_FILES["reqLinkFile"]["type"]))) {
				$errors[] = 'File gagal diupload, Pastikan File berformat PDF';
			}

			if(count($errors) === 0) 
			{

				$statement="";
				$set_upload= new Penghargaan();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 5";
				$set_upload= new Penghargaan();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				if($reqDetilId == "")
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";
				}
				else
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqDetilId."\\";
				}

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
				$ext = strtolower($path_parts['extension']);


				if(!empty($reqTahun))
				{
					$renamefile = "PENGHARGAAN_".$reqTahun."_".$tempNipBaru.".".$ext;
				}
				else
				{
					$renamefile = "PENGHARGAAN_".$tempNipBaru.".".$ext;
				}

	
				$dest_path = $urlupload . $renamefile;
				// print_r($dest_path);exit;
				if(file_exists($dest_path))
				{
					unlink($dest_path);
				}

				$statement="";

				if(move_uploaded_file($reqLinkFile, $dest_path))
				{
					if($reqDetilId == "")
					{
						$statement=" AND A.PENGHARGAAN_ID=".$reqRowId;
					}
					else
					{
						$statement=" AND A.TEMP_VALIDASI_ID=".$reqDetilId;
					}
					$set_upload= new Penghargaan();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE");
			
					$link_server=$dest_path;

					if(empty($tempLinkFile))
					{
						$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDetilId));
						$set_upload->setField('PENGHARGAAN_ID', ValToNullDB($reqRowId));
						$set_upload->setField("LINK_FILE", $dest_path);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField("LINK_SERVER", $link_server);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$reqSimpan= 1;
					}
				}
			}
			else 
		    {
		    	foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
		    }
		}
		

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'File berhasil diupload');
		}
		else
		{
			echo json_response(400, 'File gagal diupload, Cek kembali file anda atau hubungi admin');
		}
		
		
		// print_r($reqDetilId);exit;		
	}

	function jsonpegawaipenghargaandeletefile()
	{
		$this->load->model("base-validasi/Penghargaan");
		$reqFileId= $this->input->get("reqFileId");
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqDetilId= $this->input->get("reqDetilId");
		$reqRowId= $this->input->get("reqRowId");
		$set= new Penghargaan();
		$set->setField('PENGHARGAAN_FILE_ID', $reqFileId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);

		$set_upload = new Penghargaan();
		
		if($reqDetilId == "")
		{
			$set_upload->selectByParams(array('A.PENGHARGAAN_ID'=>$reqRowId));
		}
		else
		{
			$set_upload->selectByParams(array('A.TEMP_VALIDASI_ID'=>$reqDetilId));
		}
		
		$set_upload->firstRow();
		$reqLinkFile= $set_upload->getField('LINK_FILE');
		// echo $reqRowId;exit;		
		$reqSimpan="";

		if(file_exists($reqLinkFile))
		{
			unlink($reqLinkFile);
		}

		if($set->deletefile())	
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
	
	function jsonpegawaipotensidiri()
	{
		$this->load->model("base-validasi/PotensiDiri");

		$adminuserid= $this->adminuserid;

		$set= new PotensiDiri();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " AND PEGAWAI_ID = ".$this->pegawaiId;
		if (!empty($adminuserid))
		{
			$statement.= " AND TEMP_VALIDASI_ID IS NOT NULL";
		}
		$sOrder = "";
		$set->selectByParams(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else
					$row[$valkey]= $set->getField($valkey);
			}
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
	function jsonpegawaiprestasi()
	{
		$this->load->model("base-validasi/PenilaianKerja");

		$adminuserid= $this->adminuserid;


		$set= new PenilaianKerja();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " AND PEGAWAI_ID = ".$this->pegawaiId;
		if (!empty($adminuserid))
		{
			$statement.= " AND TEMP_VALIDASI_ID IS NOT NULL";
		}
		$sOrder = "";
		$set->selectByParams(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else
					$row[$valkey]= $set->getField($valkey);
			}
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
	function jsonpegawaihukuman()
	{
		$this->load->model("base-validasi/Hukuman");
		$adminuserid= $this->adminuserid;

		$set= new Hukuman();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " AND A.PEGAWAI_ID = ".$this->pegawaiId;
		if (!empty($adminuserid))
		{
			$statement.= " AND A.TEMP_VALIDASI_ID IS NOT NULL";
		}

		$sOrder = "";
		$set->selectByParams(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$reqLinkFile = $set->getField("LINK_FILE");
			$reqPegawaiId = $this->pegawaiId;
			$reqRowId = $set->getField("HUKUMAN_ID");
			$reqFileId = $set->getField("HUKUMAN_FILE_ID");
			$reqLinkFileServer = $set->getField("LINK_SERVER");
			$reqDetilId = $set->getField("TEMP_VALIDASI_ID");
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "UPLOAD")
				{ 
					$row[$valkey]= '
					<a class="navi-link" onclick=\'btnUpload("'.$reqDetilId.'","'.$reqPegawaiId.'","'.$reqRowId.'");\'>
					<img src="images/upload.png" title="upload" style="width:20px;height:20px">
					</a>
					&nbsp;
					';

					if(!empty($reqLinkFileServer))
					{
						$row[$valkey].= '
						<a class="navi-link" href="app/loadUrl/main/viewer?reqForm=hukuman&reqFileId='.$reqFileId.'" target="_blank">
						<img src="images/download.png" title="download" style="width:20px;height:20px">
						</a>
						&nbsp;
						';

						$row[$valkey].= '
						<a class="navi-link" onclick=\'btnDeleteFile("'.$reqFileId.'","'.$reqPegawaiId.'","'.$reqDetilId.'","'.$reqRowId.'");\'>
						<img src="images/delete-icon.png" title="delete" style="width:20px;height:20px">
						</a>
						';
					}
				}
				else
					$row[$valkey]= $set->getField($valkey);
			}
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

	function jsonpegawaihukumanupload()
	{
		$this->load->model("base-validasi/Hukuman");
		$reqDetilId= $this->input->post("reqDetilId");
		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqRowId= $this->input->post("reqRowId");
		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];
	
		// print_r($reqRowId);exit();
		$reqSimpan="";
		if(!empty($fileName))
		{

			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFile']['size'] >= $maxsize) || ($_FILES["reqLinkFile"]["size"] == 0)) {
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if((!in_array($_FILES['reqLinkFile']['type'], $acceptable)) && (!empty($_FILES["reqLinkFile"]["type"]))) {
				$errors[] = 'File gagal diupload, Pastikan File berformat PDF';
			}

			if(count($errors) === 0) 
			{

				$statement="";
				$set_upload= new Hukuman();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 6";
				$set_upload= new Hukuman();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				if($reqDetilId == "")
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";
				}
				else
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqDetilId."\\";
				}

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
				$ext = strtolower($path_parts['extension']);


				$renamefile = "HUKUMAN_".$tempNipBaru.".".$ext;
	
				$dest_path = $urlupload . $renamefile;
				// print_r($dest_path);exit;
				if(file_exists($dest_path))
				{
					unlink($dest_path);
				}

				$statement="";

				if(move_uploaded_file($reqLinkFile, $dest_path))
				{
					if($reqDetilId == "")
					{
						$statement=" AND A.HUKUMAN_ID=".$reqRowId;
					}
					else
					{
						$statement=" AND A.TEMP_VALIDASI_ID=".$reqDetilId;
					}
					$set_upload= new Hukuman();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE");
			
					$link_server=$dest_path;

					if(empty($tempLinkFile))
					{
						$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDetilId));
						$set_upload->setField('HUKUMAN_ID', ValToNullDB($reqRowId));
						$set_upload->setField("LINK_FILE", $dest_path);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField("LINK_SERVER", $link_server);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$reqSimpan= 1;
					}
				}
			}
			else 
		    {
		    	foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
		    }
		}
		

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'File berhasil diupload');
		}
		else
		{
			echo json_response(400, 'File gagal diupload, Cek kembali file anda atau hubungi admin');
		}
		
		
		// print_r($reqDetilId);exit;		
	}
	function jsonriwayathukumandelete()
	{
		$this->load->model("base-validasi/Hukuman");
		$reqDetilId= $this->input->get("reqDetilId");
		$set= new Hukuman();
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
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

	function jsonpegawaihukumandeletefile()
	{
		$this->load->model("base-validasi/Hukuman");
		$reqFileId= $this->input->get("reqFileId");
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqDetilId= $this->input->get("reqDetilId");
		$reqRowId= $this->input->get("reqRowId");
		$set= new Hukuman();
		$set->setField('HUKUMAN_FILE_ID', $reqFileId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);

		$set_upload = new Hukuman();
		
		if($reqDetilId == "")
		{
			$set_upload->selectByParams(array('A.HUKUMAN_ID'=>$reqRowId));
		}
		else
		{
			$set_upload->selectByParams(array('A.TEMP_VALIDASI_ID'=>$reqDetilId));
		}
		
		$set_upload->firstRow();
		$reqLinkFile= $set_upload->getField('LINK_FILE');
		// echo $reqRowId;exit;		
		$reqSimpan="";

		if(file_exists($reqLinkFile))
		{
			unlink($reqLinkFile);
		}

		if($set->deletefile())	
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

	function jsonpegawaicuti()
	{
		$this->load->model("base-validasi/Cuti");
		$adminuserid= $this->adminuserid;

		$set= new Cuti();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " AND PEGAWAI_ID = ".$this->pegawaiId;
		if (!empty($adminuserid))
		{
			$statement.= " AND TEMP_VALIDASI_ID IS NOT NULL";
		}
		$sOrder = "";
		$set->selectByParams(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else
					$row[$valkey]= $set->getField($valkey);
			}
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

	function jsonpegawaicutidelete()
	{
		$this->load->model("base-validasi/Cuti");
		$reqDetilId= $this->input->get("reqDetilId");
		$set= new Cuti();
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
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
	function jsonpegawaibahasa()
	{
		$this->load->model("base-validasi/Bahasa");

		$set= new Bahasa();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$adminuserid= $this->adminuserid;


		$statement= " AND PEGAWAI_ID = ".$this->pegawaiId;
		if (!empty($adminuserid))
		{
			$statement.= " AND TEMP_VALIDASI_ID IS NOT NULL";
		}
		$sOrder = "";
		$set->selectByParams(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else
					$row[$valkey]= $set->getField($valkey);
			}
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

	function jsonpegawaibahasadelete()
	{
		$this->load->model("base-validasi/Bahasa");
		$reqDetilId= $this->input->get("reqDetilId");
		$set= new Bahasa();
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
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

	function jsonpegawaikursus()
	{
		$this->load->model("base-validasi/Kursus");

		$adminuserid= $this->adminuserid;


		$set= new Kursus();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " AND A.PEGAWAI_ID = ".$this->pegawaiId;
		if (!empty($adminuserid))
		{
			$statement.= " AND A.TEMP_VALIDASI_ID IS NOT NULL";
		}
		$sOrder = "";
		$set->selectByParams(array(), $displaylength, $displaystart, $statement, $sOrder);
		 // echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$reqLinkFile = $set->getField("LINK_FILE");
			$reqPegawaiId = $this->pegawaiId;
			$reqRowId = $set->getField("KURSUS_ID");
			$reqFileId = $set->getField("KURSUS_FILE_ID");
			$reqJenisJabatanId = $set->getField("JENIS_JABATAN_ID");
			$reqLinkFileServer = $set->getField("LINK_SERVER");
			$reqDetilId = $set->getField("TEMP_VALIDASI_ID");
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "KETERANGAN")
				{ 
					$row[$valkey]= '
					<a class="navi-link" onclick=\'btnUpload("'.$reqDetilId.'","'.$reqPegawaiId.'","'.$reqJenisJabatanId.'","'.$reqRowId.'");\'>
					<img src="images/upload.png" title="upload" style="width:20px;height:20px">
					</a>
					&nbsp;
					';

					if(!empty($reqLinkFileServer))
					{
						$row[$valkey].= '
						<a class="navi-link" href="app/loadUrl/main/viewer?reqForm=kursus&reqFileId='.$reqFileId.'" target="_blank">
						<img src="images/download.png" title="download" style="width:20px;height:20px">
						</a>
						&nbsp;
						';

						$row[$valkey].= '
						<a class="navi-link" onclick=\'btnDeleteFile("'.$reqFileId.'","'.$reqPegawaiId.'","'.$reqDetilId.'","'.$reqRowId.'");\'>
						<img src="images/delete-icon.png" title="delete" style="width:20px;height:20px">
						</a>
						';
					}
					
				}
				else
					$row[$valkey]= $set->getField($valkey);
			}
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

	function jsonpegawaikursusupload()
	{
		$this->load->model("base-validasi/Kursus");
		$reqDetilId= $this->input->post("reqDetilId");
		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqJenisJabatanId= $this->input->post("reqJenisJabatanId");
		$reqRowId= $this->input->post("reqRowId");
		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];
	
		// print_r($reqRowId);exit();
		$reqSimpan="";
		if(!empty($fileName))
		{

			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFile']['size'] >= $maxsize) || ($_FILES["reqLinkFile"]["size"] == 0)) {
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if((!in_array($_FILES['reqLinkFile']['type'], $acceptable)) && (!empty($_FILES["reqLinkFile"]["type"]))) {
				$errors[] = 'File gagal diupload, Pastikan File berformat PDF';
			}

			if(count($errors) === 0) 
			{

				$statement="";
				$set_upload= new Kursus();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 2";
				$set_upload= new Kursus();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				if($reqDetilId == "")
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";
				}
				else
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqDetilId."\\";
				}

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
				$ext = strtolower($path_parts['extension']);


				$renamefile = "BIMTEK_".$tempNipBaru.".".$ext;
	
				$dest_path = $urlupload . $renamefile;
				// print_r($dest_path);exit;
				if(file_exists($dest_path))
				{
					unlink($dest_path);
				}

				$statement="";

				if(move_uploaded_file($reqLinkFile, $dest_path))
				{
					if($reqDetilId == "")
					{
						$statement=" AND A.KURSUS_ID=".$reqRowId;
					}
					else
					{
						$statement=" AND A.TEMP_VALIDASI_ID=".$reqDetilId;
					}
					$set_upload= new Kursus();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE");
			
					$link_server=$dest_path;

					if(empty($tempLinkFile))
					{
						$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDetilId));
						$set_upload->setField('KURSUS_ID', ValToNullDB($reqRowId));
						$set_upload->setField("LINK_FILE", $dest_path);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField("LINK_SERVER", $link_server);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$reqSimpan= 1;
					}
				}
			}
			else 
		    {
		    	foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
		    }
		}
		

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'File berhasil diupload');
		}
		else
		{
			echo json_response(400, 'File gagal diupload, Cek kembali file anda atau hubungi admin');
		}
		
		
		// print_r($reqDetilId);exit;		
	}

	function jsonpegawaikursusdelete()
	{
		$this->load->model("base-validasi/Kursus");
		$reqDetilId= $this->input->get("reqDetilId");
		$set= new Kursus();
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
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

	function jsonpegawaikursusdeletefile()
	{
		$this->load->model("base-validasi/Kursus");
		$reqFileId= $this->input->get("reqFileId");
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqDetilId= $this->input->get("reqDetilId");
		$reqRowId= $this->input->get("reqRowId");
		$set= new Kursus();
		$set->setField('KURSUS_FILE_ID', $reqFileId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);

		$set_upload = new Kursus();
		
		if($reqDetilId == "")
		{
			$set_upload->selectByParams(array('A.KURSUS_ID'=>$reqRowId));
		}
		else
		{
			$set_upload->selectByParams(array('A.TEMP_VALIDASI_ID'=>$reqDetilId));
		}
		
		$set_upload->firstRow();
		$reqLinkFile= $set_upload->getField('LINK_FILE');
		// echo $reqRowId;exit;		
		$reqSimpan="";

		if(file_exists($reqLinkFile))
		{
			unlink($reqLinkFile);
		}

		if($set->deletefile())	
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

	function jsonpegawaidiklat()
	{
		$this->load->model("base-validasi/PegawaiDiklat");

		$set= new PegawaiDiklat();

		$adminuserid= $this->adminuserid;

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault);exit;

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " AND A.PEGAWAI_ID = ".$this->pegawaiId;
		if (!empty($adminuserid))
		{
			$statement.= " AND A.TEMP_VALIDASI_ID IS NOT NULL";
		}
		$sOrder = " ORDER BY A.TANGGAL ASC";
		$set->selectByParams(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$reqLinkFile = $set->getField("LINK_FILE");
			$reqPegawaiId = $this->pegawaiId;
			$reqRowId = $set->getField("PEGAWAI_DIKLAT_ID");
			$reqFileId = $set->getField("PEGAWAI_DIKLAT_FILE_ID");
			$reqDiklatId = $set->getField("DIKLAT_ID");
			$reqLinkFileServer = $set->getField("LINK_SERVER");
			$reqDetilId = $set->getField("TEMP_VALIDASI_ID");
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "TANGGAL")
				{
					$row[$valkey]= dateToPageCheck($set->getField($valkey));
				}
				else if ($valkey == "KETERANGAN")
				{ 
					$row[$valkey]= '
					<a class="navi-link" onclick=\'btnUpload("'.$reqDetilId.'","'.$reqPegawaiId.'","'.$reqRowId.'","'.$reqDiklatId.'");\'>
					<img src="images/upload.png" title="upload" style="width:20px;height:20px">
					</a>
					&nbsp;
					';

					if(!empty($reqLinkFile))
					{
						$row[$valkey].= '
						<a class="navi-link" href="app/loadUrl/main/viewer?reqForm=diklat&reqFileId='.$reqFileId.'" target="_blank">
						<img src="images/download.png" title="download" style="width:20px;height:20px">
						</a>
						&nbsp;
						';

						$row[$valkey].= '
						<a class="navi-link" onclick=\'btnDeleteFile("'.$reqFileId.'","'.$reqPegawaiId.'","'.$reqDetilId.'","'.$reqRowId.'");\'>
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

	function jsonpegawaidiklatupload()
	{
		$this->load->model("base-validasi/PegawaiDiklat");
		$reqDetilId= $this->input->post("reqDetilId");
		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqDiklatId= $this->input->post("reqDiklatId");
		$reqRowId= $this->input->post("reqRowId");
		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];
	
		// print_r($reqRowId);exit();
		$reqSimpan="";
		if(!empty($fileName))
		{

			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFile']['size'] >= $maxsize) || ($_FILES["reqLinkFile"]["size"] == 0)) {
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if((!in_array($_FILES['reqLinkFile']['type'], $acceptable)) && (!empty($_FILES["reqLinkFile"]["type"]))) {
				$errors[] = 'File gagal diupload, Pastikan File berformat PDF';
			}

			if(count($errors) === 0) 
			{

				$statement="";
				$set_upload= new PegawaiDiklat();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 14";
				$set_upload= new PegawaiDiklat();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				$statement=" AND B.DIKLAT_ID = ".$reqDiklatId;
				$check= new PegawaiDiklat();
				$check->selectByParams(array(),-1,-1,$statement);
					// echo $check->query;exit;
				$check->firstRow();
				$reqDiklatNama=$check->getField("DIKLAT_KET");
				$reqDiklatNama= str_replace(" ", "_", $reqDiklatNama);

				if($reqDetilId == "")
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";
				}
				else
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqDetilId."\\";
				}

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
				$ext = strtolower($path_parts['extension']);


				$renamefile = "SK_".$reqDiklatNama."_".$tempNipBaru.".".$ext;
	
				$dest_path = $urlupload . $renamefile;
				// print_r($dest_path);exit;
				if(file_exists($dest_path))
				{
					unlink($dest_path);
				}

				$statement="";

				if(move_uploaded_file($reqLinkFile, $dest_path))
				{
					if($reqDetilId == "")
					{
						$statement=" AND A.PEGAWAI_DIKLAT_ID=".$reqRowId;
					}
					else
					{
						$statement=" AND A.TEMP_VALIDASI_ID=".$reqDetilId;
					}
					$set_upload= new PegawaiDiklat();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE");
			
					$link_server=$dest_path;

					if(empty($tempLinkFile))
					{
						$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDetilId));
						$set_upload->setField('PEGAWAI_DIKLAT_ID', ValToNullDB($reqRowId));
						$set_upload->setField("LINK_FILE", $dest_path);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$reqSimpan= 1;
					}
				}
			}
			else 
		    {
		    	foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
		    }
		}
		

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'File berhasil diupload');
		}
		else
		{
			echo json_response(400, 'File gagal diupload, Cek kembali file anda atau hubungi admin');
		}
		
		
		// print_r($reqDetilId);exit;		
	}

	function jsonpegawaidiklatdelete()
	{
		$this->load->model("base-validasi/PegawaiDiklat");
		$reqDetilId= $this->input->get("reqDetilId");
		$set= new PegawaiDiklat();
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
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

	function jsonpegawaidiklatdeletefile()
	{
		$this->load->model("base-validasi/PegawaiDiklat");
		$reqFileId= $this->input->get("reqFileId");
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqDetilId= $this->input->get("reqDetilId");
		$reqRowId= $this->input->get("reqRowId");
		$set= new PegawaiDiklat();
		$set->setField('PEGAWAI_DIKLAT_FILE_ID', $reqFileId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);

		$set_upload = new PegawaiDiklat();
		
		if($reqDetilId == "")
		{
			$set_upload->selectByParams(array('A.PEGAWAI_DIKLAT_ID'=>$reqRowId));
		}
		else
		{
			$set_upload->selectByParams(array('A.TEMP_VALIDASI_ID'=>$reqDetilId));
		}
		
		$set_upload->firstRow();
		$reqLinkFile= $set_upload->getField('LINK_FILE');
		// echo $reqRowId;exit;		
		$reqSimpan="";

		if(file_exists($reqLinkFile))
		{
			unlink($reqLinkFile);
		}

		if($set->deletefile())	
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

	function jsonpegawaijabatantipe()
	{
		$this->load->model("base-validasi/PegawaiJabatan");

		$set= new PegawaiJabatan();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " AND A.PEGAWAI_ID = ".$this->pegawaiId;
		$sOrder = "";
		$set->selectByParamsMonitoring(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$jenisjabatan = $set->getField("JENIS_JABATAN_ID");
			$reqLinkFile = $set->getField("LINK_FILE");
			$reqPegawaiJabatanFileId = $set->getField("PEGAWAI_JABATAN_FILE_ID");
			$reqPegawaiId = $this->pegawaiId;
			$reqDetilId = $set->getField("TEMP_VALIDASI_ID");
			$reqJenisJabatanId = $set->getField("JENIS_JABATAN_ID");
			$reqRowId = $set->getField("PEGAWAI_JABATAN_ID");
			$reqLinkServer = $set->getField("LINK_SERVER");
			// print_r($reqLinkFile);exit;
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{

				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else if ($valkey == "NAMA_FUNGSIONAL")
				{ 
					if ($jenisjabatan == 1)
					{
						$row[$valkey]= $set->getField("NAMA_STRUKTURAL");
					}
					else if ($jenisjabatan == 2 || $jenisjabatan == 4)
					{
						$row[$valkey]= $set->getField("NAMA_FUNGSIONAL");
					}
					else if  ($jenisjabatan == 3)
					{
						$row[$valkey]= $set->getField("NAMA_PELAKSANA");
					}
					
				}
				else if ($valkey == "KETERANGAN")
				{ 
					$row[$valkey]= '
					<a class="navi-link" onclick=\'btnUpload("'.$reqDetilId.'","'.$reqPegawaiId.'","'.$reqJenisJabatanId.'","'.$reqRowId.'");\'>
					<img src="images/upload.png" title="upload" style="width:20px;height:20px">
					</a>
					&nbsp;
					';

					if(!empty($reqLinkServer))
					{
						$row[$valkey].= '
						<a class="navi-link" href="app/loadUrl/main/viewer?reqForm=jabatan&reqPegawaiJabatanFileId='.$reqPegawaiJabatanFileId.'" target="_blank">
						<img src="images/download.png" title="download" style="width:20px;height:20px">
						</a>
						&nbsp;
						';

						$row[$valkey].= '
						<a class="navi-link" onclick=\'btnDeleteFile("'.$reqPegawaiJabatanFileId.'","'.$reqPegawaiId.'","'.$reqDetilId.'","'.$reqRowId.'");\'>
						<img src="images/delete-icon.png" title="delete" style="width:20px;height:20px">
						</a>
						';
					}
					
				}
				else
					$row[$valkey]= $set->getField($valkey);
			}
			array_push($arrinfodata, $row);
		}
		// print_r($arrinfodata);exit;

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

	function jsonpegawaijabatanadd()
	{
		$this->load->model("base-validasi/PegawaiJabatan");

		$reqMode= $this->input->post("reqMode");
		$reqRowId= $this->input->post("reqRowId");
		$reqTempValidasiId= $this->input->post("reqTempValidasiId");
		$reqPegawaiId= $this->pegawaiId;
		$reqJenisJabatan= $this->input->post("reqJenisJabatan");
		$reqKategoriJabatan= $this->input->post("reqKategoriJabatan");
		$reqKategoriJabatanId= $this->input->post("reqKategoriJabatanId");
		$reqJabatanId= $this->input->post("reqJabatanId");
		$reqBup= $this->input->post("reqBup");
		$reqKelJab= $this->input->post("reqKelJab");
		$reqTmtJabatan= $this->input->post("reqTmtJabatan");
		// var_dump ($reqJabatanId);exit;
		$reqTanggalSuratKeputusan= $this->input->post("reqTanggalSuratKeputusan");
		$reqNoSk= $this->input->post("reqNoSk");
		$reqTugasTambahan= $this->input->post("reqTugasTambahan");
		$reqTugasTambahanId= $this->input->post("reqTugasTambahanId");
		$reqEselonId= $this->input->post("reqEselonId");
		$reqStatusValidasi= $this->input->post("reqStatusValidasi");
		$reqUnorId= $this->input->post("reqUnorId");
		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];

		if(!empty($fileName))
		{
			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFile']['size'] >= $maxsize) || ($_FILES["reqLinkFile"]["size"] == 0)) {
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if((!in_array($_FILES['reqLinkFile']['type'], $acceptable)) && (!empty($_FILES["reqLinkFile"]["type"]))) {
				$errors[] = 'File gagal diupload, Pastikan File berformat PDF';
			}

			if(count($errors) > 0) 
			{
				foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
			}
			
		}

	
		$set= new PegawaiJabatan();
		$set->setField("LAST_CREATE_USER", "");
		$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
		$set->setField("LAST_CREATE_SATKER", "");
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('JENIS_JABATAN_ID', ValToNullDB($reqJenisJabatan));
		$a="";
		if ($reqJenisJabatan == 1) {
			$set->setField('JABATAN_STRUKTURAL_NEW_ID', $reqJabatanId);
		}
		elseif($reqJenisJabatan == 2 || $reqJenisJabatan == 4)
		{
			$set->setField('JABATAN_FUNGSIONAL_NEW_ID', $reqJabatanId);
			
		}
		elseif ($reqJenisJabatan == 3) {
			$set->setField('JABATAN_PELAKSANA_NEW_ID', $reqJabatanId);
		}

		if($reqJenisJabatan == 4)
		{
			$set->setField('TUGAS_TAMBAHAN_ID', $reqTugasTambahanId);
			$set->setField('TUGAS_TAMBAHAN_NAMA', $reqTugasTambahan);
		}

		$set->setField('ESELON_ID', ValToNullDB($reqEselonId));
		$set->setField('TIPE_PEGAWAI_NEW_ID', $reqKategoriJabatan);
		$set->setField('BUP', ValToNullDB($reqBup));
		$set->setField('KELAS_JABATAN', ValToNullDB($reqKelJab));
		$set->setField('TMT_JABATAN', dateToDBCheck($reqTmtJabatan));
		$set->setField('TANGGAL_SK', dateToDBCheck($reqTanggalSuratKeputusan));
		$set->setField('NO_SK', $reqNoSk);
		$set->setField('USER_APP_ID', ValToNullDB($userLogin->UID));
		$set->setField('UNOR_ID', ValToNullDB($reqUnorId));
		
		$reqSimpan= "";
		$reqId= $reqTempValidasiId;
		if(is_numeric($reqStatusValidasi))
		{
			$set->setField('VALIDASI', $reqStatusValidasi);
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
			$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

			if($set->updatevalidasi())
			{
				$reqSimpan= "1";
			}
		}
		else
		{
			if($reqMode == "insert")
			{
				$set->setField("LAST_CREATE_USER", $userLogin->idUser);
				$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
				$set->setField('PEGAWAI_JABATAN_ID', ValToNullDB($reqRowId));
				if($set->insert())
				{
					$reqId = $set->id;
					$reqSimpan= 1;
				}
			}
			elseif($reqMode == "update")
			{	
				$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
				$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);
				if($set->update())
				{
					$reqSimpan= 1;
				}
			}
		}

		if(!empty($fileName))
		{
			if(count($errors) === 0) 
			{

				$statement="";
				$set_upload= new PegawaiJabatan();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 1";
				$set_upload= new PegawaiJabatan();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				if($reqId == "")
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";
				}
				else
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqId."\\";
				}
			

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
				$ext = strtolower($path_parts['extension']);

				$renamefile = "SK_JABATAN_".$tempNipBaru.".".$ext;

				$dest_path = $urlupload . $renamefile;
				// print_r($dest_path);exit;
				if(file_exists($dest_path))
				{
					unlink($dest_path);
				}

				$reqSimpan="";
				$statement="";

				if(move_uploaded_file($reqLinkFile, $dest_path))
				{
					if($reqId == "")
					{
						$statement=" AND A.PEGAWAI_JABATAN_ID=".$reqRowId;
					}
					else
					{
						$statement=" AND A.TEMP_VALIDASI_ID=".$reqId;
					}

					$set_upload= new PegawaiJabatan();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE");
			
					$link_server=$dest_path;

					if(empty($tempLinkFile))
					{
						$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqId));
						$set_upload->setField('JENIS_JABATAN_ID', ValToNullDB($reqJenisJabatan));
						$set_upload->setField('PEGAWAI_JABATAN_ID', ValToNullDB($reqRowId));
						$set_upload->setField("LINK_FILE", $dest_path);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField("LINK_SERVER", $link_server);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$reqSimpan= 1;
					}
				}
			}
		}


		// echo $set->query;exit;
		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsonpegawaijabatantipedelete()
	{
		$this->load->model("base-validasi/PegawaiJabatan");
		$reqDetilId= $this->input->get("reqDetilId");
		$set= new PegawaiJabatan();
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
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

	function jsonpegawaijabatanupload()
	{
		$this->load->model("base-validasi/PegawaiJabatan");
		$reqDetilId= $this->input->post("reqDetilId");
		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqJenisJabatanId= $this->input->post("reqJenisJabatanId");
		$reqRowId= $this->input->post("reqRowId");
		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];

		$statement="";
		$set_upload= new PegawaiJabatan();
		$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
		$set_upload->firstRow();
		$tempNipBaru=$set_upload->getField("NIP_BARU");
		unset($set_upload);

		if(!empty($fileName))
		{

			$statement="";
			$set_upload= new PegawaiJabatan();
			$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
			$set_upload->firstRow();
			$tempNipBaru=$set_upload->getField("NIP_BARU");
			unset($set_upload);

			$statement=" AND LINK_SERVER_ID = 1";
			$set_upload= new PegawaiJabatan();
			$set_upload->selectByParamsServer(array(),-1,-1,$statement);
				// echo $set_upload->query;exit;
			$set_upload->firstRow();
			$tempLinkServer=$set_upload->getField("LINK_SERVER");
			$tempLinkFolder=$set_upload->getField("FOLDER");

			if($reqDetilId == "")
			{
				$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";
			}
			else
			{
				$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqDetilId."\\";
			}



			if (!is_dir($urlupload)) {
				makedirs($urlupload);
			}
			
			$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
			$ext = strtolower($path_parts['extension']);

			$renamefile = "SK_JABATAN_".$tempNipBaru.".".$ext;

			$dest_path = $urlupload . $renamefile;
			//print_r($reqLinkFile);exit;
			if(file_exists($dest_path))
			{
				unlink($dest_path);
			}

			$reqSimpan="";
			$statement="";

			if(move_uploaded_file($reqLinkFile, $dest_path))
			{
				if($reqDetilId == "")
				{
					$statement=" AND A.PEGAWAI_JABATAN_ID=".$reqRowId;
				}
				else
				{
					$statement=" AND A.TEMP_VALIDASI_ID=".$reqDetilId;
				}
				$set_upload= new PegawaiJabatan();
				$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkFile=$set_upload->getField("LINK_FILE");
				
				//print_r($tempLinkFile);exit;
		
				$link_server=$dest_path;

				if(empty($tempLinkFile))
				{
					$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDetilId));
					$set_upload->setField('PEGAWAI_JABATAN_ID', ValToNullDB($reqRowId));
					$set_upload->setField("LINK_FILE", $dest_path);
					$set_upload->setField("NAMA_FILE", $renamefile);
					$set_upload->setField("JENIS_JABATAN_ID", $reqJenisJabatanId);
					$set_upload->setField("LINK_SERVER", $link_server);
					$set_upload->setField("TIPE_FILE", $ext);
					$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
					if($set_upload->insertupload())
					{
						$reqSimpan= 1;
					}
					//echo $set_upload->query;exit;
				}
				else
				{
					$reqSimpan= 1;
				}
			}
		}




		if($reqSimpan == 1 )
		{
			echo json_response(200, 'File berhasil diupload');
		}
		else
		{
			echo json_response(400, 'File gagal diupload, Cek kembali file anda atau hubungi admin');
		}
		
		
		// print_r($reqDetilId);exit;		
	}

	function jsonpegawaijabatantipedeletefile()
	{
		$this->load->model("base-validasi/PegawaiJabatan");
		$reqFileId= $this->input->get("reqFileId");
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqDetilId= $this->input->get("reqDetilId");
		$reqRowId= $this->input->get("reqRowId");
		$set= new PegawaiJabatan();
		$set->setField('PEGAWAI_JABATAN_FILE_ID', $reqFileId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);

		$set_upload = new PegawaiJabatan();
		if($reqDetilId == "")
		{
			$set_upload->selectByParams(array('A.PEGAWAI_JABATAN_ID'=>$reqRowId));
		}
		else
		{
			$set_upload->selectByParams(array('A.TEMP_VALIDASI_ID'=>$reqDetilId));
		}
		$set_upload->firstRow();
		$reqLinkFile= $set_upload->getField('LINK_FILE');


		$reqSimpan="";

		if(file_exists($reqLinkFile))
		{
			unlink($reqLinkFile);
		}

		if($set->deletefile())	
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

	function jsonpegawaijabatantambahan()
	{
		$this->load->model("base-validasi/PegawaiJabatan");

		$set= new PegawaiJabatan();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement.= " AND TIPE_PEGAWAI_NEW_ID = '16' ";
		$sOrder = " ORDER BY JABATAN_STRUKTURAL_NEW_ID";
		$set->selectByParamsJabatanTambahan(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$jenisjabatan = $set->getField("JENIS_JABATAN_ID");
			// print_r($jenisjabatan);exit;
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{

				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else
					$row[$valkey]= $set->getField($valkey);
			}
			array_push($arrinfodata, $row);
		}
		// print_r($arrinfodata);exit;

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


	function monitoringpegawai()
	{
		$this->load->model("base-validasi/MonitoringPegawai");

		$set= new MonitoringPegawai();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement.= " AND (STATUS_PEGAWAI = 1 OR STATUS_PEGAWAI = 2 OR STATUS_PEGAWAI = 9) ";
		$sOrder = " ORDER BY CASE WHEN COALESCE(C.ESELON_ID,99) = 99 THEN 99 WHEN C.ESELON_ID = 0 THEN 99 ELSE C.ESELON_ID END ASC, B.PANGKAT_ID DESC, B.TMT_PANGKAT ASC";
		$set->selectByParamsMonitoringPegawai(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$jenisjabatan = $set->getField("JENIS_JABATAN_ID");
			// print_r($jenisjabatan);exit;
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{

				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else
					$row[$valkey]= $set->getField($valkey);
			}
			array_push($arrinfodata, $row);
		}
		// print_r($arrinfodata);exit;

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

	function pegawaiverifikator()
	{
		$this->load->model("base-validasi/Pegawaiverifikator");

		$set= new Pegawaiverifikator();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement.= " AND (STATUS_PEGAWAI = 1 OR STATUS_PEGAWAI = 2 OR STATUS_PEGAWAI = 9) ";
		$sOrder = " ORDER BY CASE WHEN COALESCE(C.ESELON_ID,99) = 99 THEN 99 WHEN C.ESELON_ID = 0 THEN 99 ELSE C.ESELON_ID END ASC, B.PANGKAT_ID DESC, B.TMT_PANGKAT ASC";
		$set->selectByParamsPegawaiVerifikator(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$jenisjabatan = $set->getField("JENIS_JABATAN_ID");
			// print_r($jenisjabatan);exit;
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{

				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else
					$row[$valkey]= $set->getField($valkey);
			}
			array_push($arrinfodata, $row);
		}
		// print_r($arrinfodata);exit;

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
	
	function jsonpegawaikontrak()
	{
		$this->load->model("base-validasi/Kontrak");

		$adminuserid= $this->adminuserid;


		$set= new Kontrak();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " AND A.PEGAWAI_ID = ".$this->pegawaiId;
		if (!empty($adminuserid))
		{
			$statement.= " AND A.TEMP_VALIDASI_ID IS NOT NULL";
		}
		$sOrder = "";
		$set->selectByParams(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$reqLinkFile = $set->getField("LINK_FILE");
			$reqPegawaiId = $this->pegawaiId;
			$reqRowId = $set->getField("RIWAYAT_KONTRAK_ID");
			$reqFileId = $set->getField("RIWAYAT_KONTRAK_FILE_ID");
			$reqLinkFileServer = $set->getField("LINK_SERVER");
			$reqDetilId = $set->getField("TEMP_VALIDASI_ID");
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "TANGGAL_SK" || $valkey == "TMT_SK" || $valkey == "SELESAI_KONTRAK")
				{
					$row[$valkey]= dateToPageCheck($set->getField($valkey));
				}
				else if ($valkey == "KETERANGAN")
				{ 
					$row[$valkey]= '
					<a class="navi-link" onclick=\'btnUpload("'.$reqDetilId.'","'.$reqPegawaiId.'","'.$reqRowId.'");\'>
					<img src="images/upload.png" title="upload" style="width:20px;height:20px">
					</a>
					&nbsp;
					';

					if(!empty($reqLinkFileServer))
					{
						$row[$valkey].= '
						<a class="navi-link" href="app/loadUrl/main/viewer?reqForm=riwayat_kontrak&reqFileId='.$reqFileId.'" target="_blank">
						<img src="images/download.png" title="download" style="width:20px;height:20px">
						</a>
						&nbsp;
						';

						$row[$valkey].= '
						<a class="navi-link" onclick=\'btnDeleteFile("'.$reqFileId.'","'.$reqPegawaiId.'","'.$reqDetilId.'","'.$reqRowId.'");\'>
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

	function jsonpegawaikontrakupload()
	{
		$this->load->model("base-validasi/Kontrak");
		$reqDetilId= $this->input->post("reqDetilId");
		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqRowId= $this->input->post("reqRowId");
		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];
	
		// print_r($reqRowId);exit();
		$reqSimpan="";
		if(!empty($fileName))
		{

			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFile']['size'] >= $maxsize) || ($_FILES["reqLinkFile"]["size"] == 0)) {
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if((!in_array($_FILES['reqLinkFile']['type'], $acceptable)) && (!empty($_FILES["reqLinkFile"]["type"]))) {
				$errors[] = 'File gagal diupload, Pastikan File berformat PDF';
			}

			if(count($errors) === 0) 
			{

				$statement="";
				$set_upload= new Kontrak();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 4";
				$set_upload= new Kontrak();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				if($reqDetilId == "")
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";
				}
				else
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqDetilId."\\";
				}

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
				$ext = strtolower($path_parts['extension']);


				$renamefile = "KONTRAK_".$tempNipBaru.".".$ext;
	
				$dest_path = $urlupload . $renamefile;
				// print_r($dest_path);exit;
				if(file_exists($dest_path))
				{
					unlink($dest_path);
				}

				$statement="";

				if(move_uploaded_file($reqLinkFile, $dest_path))
				{
					if($reqDetilId == "")
					{
						$statement=" AND A.RIWAYAT_KONTRAK_ID=".$reqRowId;
					}
					else
					{
						$statement=" AND A.TEMP_VALIDASI_ID=".$reqDetilId;
					}
					$set_upload= new Kontrak();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE");
			
					$link_server=$dest_path;

					if(empty($tempLinkFile))
					{
						$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDetilId));
						$set_upload->setField('RIWAYAT_KONTRAK_ID', ValToNullDB($reqRowId));
						$set_upload->setField("LINK_FILE", $dest_path);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField("LINK_SERVER", $link_server);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$reqSimpan= 1;
					}
				}
			}
			else 
		    {
		    	foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
		    }
		}
		

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'File berhasil diupload');
		}
		else
		{
			echo json_response(400, 'File gagal diupload, Cek kembali file anda atau hubungi admin');
		}
		
		
		// print_r($reqDetilId);exit;		
	}

	function jsonpegawaikontrakdelete()
	{
		$this->load->model("base-validasi/Kontrak");
		$reqDetilId= $this->input->get("reqDetilId");
		$set= new Kontrak();
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
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

	function jsonpegawaikontrakdeletefile()
	{
		$this->load->model("base-validasi/Kontrak");
		$reqFileId= $this->input->get("reqFileId");
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqDetilId= $this->input->get("reqDetilId");
		$reqRowId= $this->input->get("reqRowId");
		$set= new Kontrak();
		$set->setField('RIWAYAT_KONTRAK_FILE_ID', $reqFileId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);

		$set_upload = new Kontrak();
		
		if($reqDetilId == "")
		{
			$set_upload->selectByParams(array('A.RIWAYAT_KONTRAK_ID'=>$reqRowId));
		}
		else
		{
			$set_upload->selectByParams(array('A.TEMP_VALIDASI_ID'=>$reqDetilId));
		}
		
		$set_upload->firstRow();
		$reqLinkFile= $set_upload->getField('LINK_FILE');
		// echo $reqRowId;exit;		
		$reqSimpan="";

		if(file_exists($reqLinkFile))
		{
			unlink($reqLinkFile);
		}

		if($set->deletefile())	
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

	function jsonkontrakadd()
	{
		$this->load->model("base-validasi/Kontrak");
		$this->load->model("base-validasi/PejabatPenetap");

		$reqMode= $this->input->post("reqMode");
		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqRowId= $this->input->post("reqRowId");
		$reqDataId= $this->input->post("reqDataId");
		$reqTambMKId= $this->input->post("reqTambMKId");
		$reqNoSK= $this->input->post("reqNoSK");
		$reqTglSK= $this->input->post("reqTglSK");
		$reqTMTSK= $this->input->post("reqTMTSK");
		$reqSelesai= $this->input->post("reqSelesai");
		$reqMasaBerlaku= $this->input->post("reqMasaBerlaku");
		$reqMasaKerjaTahun= $this->input->post("reqMasaKerjaTahun");
		$reqMasaKerjaBulan= $this->input->post("reqMasaKerjaBulan");
		$reqGolonganPppkId= $this->input->post("reqGolonganPppkId");
		$reqGajiPokok= $this->input->post("reqGajiPokok");
		$reqPejabatPenetapId= $this->input->post("reqPejabatPenetapId");
		$reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
		$reqStatusValidasi= $this->input->post("reqStatusValidasi");

		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];

		if(!empty($fileName))
		{
			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFile']['size'] >= $maxsize) || ($_FILES["reqLinkFile"]["size"] == 0)) {
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if((!in_array($_FILES['reqLinkFile']['type'], $acceptable)) && (!empty($_FILES["reqLinkFile"]["type"]))) {
				$errors[] = 'File gagal diupload, Pastikan File berformat PDF';
			}

			if(count($errors) > 0) 
			{
				foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
			}
			
		}

		$set = new Kontrak();
		$set->setField('MASA_KERJA_BULAN', $reqMasaKerjaBulan);
		$set->setField('MASA_KERJA_TAHUN', $reqMasaKerjaTahun);
		$set->setField('NO_SK', $reqNoSK);
		$set->setField('MASA_BERLAKU', ValToNullDB($reqMasaBerlaku));
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('TANGGAL_SK', dateToDBCheck($reqTglSK));
		$set->setField('SELESAI_KONTRAK', dateToDBCheck($reqSelesai));
		$set->setField('TMT_SK', dateToDBCheck($reqTMTSK));
		$set->setField('USER_APP_ID', $userLogin->UID);
		$set->setField('GOLONGAN_PPPK_ID', ValToNullDB($reqGolonganPppkId));
		$set->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId));	
		$set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
		$set->setField('GAJI_POKOK', ValToNullDB(dotToNo($reqGajiPokok)));

		if($reqPejabatPenetapId == "")
		{
			$set_pejabat=new PejabatPenetap();
			$set_pejabat->setField('JABATAN', strtoupper($reqPejabatPenetap));
			$set_pejabat->setField("LAST_CREATE_USER", $userLogin->idUser);
			$set_pejabat->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
			$set_pejabat->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
			$set_pejabat->insert();
			$reqPejabatPenetapId=$set_pejabat->id;
			unset($set_pejabat);
		}

		
		$reqSimpan= "";
		if(is_numeric($reqStatusValidasi))
		{
			$set->setField('VALIDASI', $reqStatusValidasi);
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
			$set->setField('TEMP_VALIDASI_ID', $reqDataId);

			if($set->updatevalidasi())
			{
				$reqSimpan= "1";
			}
		}
		else
		{
			if($reqMode == "insert")
			{
				$set->setField("LAST_CREATE_USER", $userLogin->idUser);
				$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
				$set->setField('RIWAYAT_KONTRAK_ID',ValToNullDB($reqRowId));

				if($set->insert())
				{
					$reqDataId=$set->id;
					$reqSimpan= 1;
				}
			}
			elseif($reqMode == "update")
			{	
				$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
				$set->setField('TEMP_VALIDASI_ID', $reqDataId);
				$set->setField("RIWAYAT_KONTRAK_ID", $reqId);
				if($set->update())
				{

					$reqSimpan= 1;
				}
			}
		}

		// echo $set->query;exit;


		if(!empty($fileName))
		{
			if(count($errors) === 0) 
			{

				$statement="";
				$set_upload= new Kontrak();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 4";
				$set_upload= new Kontrak();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				if($reqDataId == "")
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";
				}
				else
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqDataId."\\";
				}

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
				$ext = strtolower($path_parts['extension']);


				$renamefile = "KONTRAK_".$tempNipBaru.".".$ext;
	
				$dest_path = $urlupload . $renamefile;
				// print_r($dest_path);exit;
				if(file_exists($dest_path))
				{
					unlink($dest_path);
				}

				$statement="";

				if(move_uploaded_file($reqLinkFile, $dest_path))
				{
					if($reqDataId == "")
					{
						$statement=" AND A.RIWAYAT_KONTRAK_ID=".$reqRowId;
					}
					else
					{
						$statement=" AND A.TEMP_VALIDASI_ID=".$reqDataId;
					}
					$set_upload= new Kontrak();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE");
			
					$link_server=$dest_path;

					if(empty($tempLinkFile))
					{
						$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDataId));
						$set_upload->setField('RIWAYAT_KONTRAK_ID', ValToNullDB($reqRowId));
						$set_upload->setField("LINK_FILE", $dest_path);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField("LINK_SERVER", $link_server);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$reqSimpan= 1;
					}
				}
			}
			else 
		    {
		    	foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
		    }
		}
		// echo $set->query;exit;

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsonskpadd()
	{
		$this->load->model("base-validasi/PenilaianKerjaPegawai");
		$this->load->model("base-validasi/PejabatPenilai");

		$reqMode 			= $this->input->post("reqMode");
		$reqPegawaiId 		= $this->input->post('reqPegawaiId');
		$reqRowId			= $this->input->post("reqRowId");
		$reqDataId			= $this->input->post("reqDataId");

		$reqJenisJabatanId= $this->input->post("reqJenisJabatanId");
		$reqTahun= $this->input->post("reqTahun");
		$reqNilai1= $this->input->post("reqNilai1");
		$reqNilai2= $this->input->post("reqNilai2");
		$reqNilai3= $this->input->post("reqNilai3");
		$reqNilai4= $this->input->post("reqNilai4");
		$reqNilai5= $this->input->post("reqNilai5");
		$reqNilai6= $this->input->post("reqNilai6");
		$reqJumlah= $this->input->post("reqJumlah");
		$reqRataRata= $this->input->post("reqRataRata");
		$reqSasaranKerja= $this->input->post("reqSasaranKerja");
		$reqSasaranKerjaHasil= $this->input->post("reqSasaranKerjaHasil");
		$reqPerilakuHasil= $this->input->post("reqPerilakuHasil");
		$reqNilaiHasil= $this->input->post("reqNilaiHasil");
		$reqPerilakuKinerja= $this->input->post("reqPerilakuKinerja");
		$reqTanggalAwal= $this->input->post("reqTanggalAwal");
		$reqTanggalAkhir= $this->input->post("reqTanggalAkhir");
		$reqRekomendasi= $this->input->post("reqRekomendasi");

		$reqPejabatNipId= $this->input->post("reqPejabatNipId");
		$reqAtasanNipId= $this->input->post("reqAtasanNipId");
		$reqNamaPejabat= $this->input->post("reqNamaPejabat");
		$reqPejabatJabatan= $this->input->post("reqPejabatJabatan");
		$reqPejabatUnor= $this->input->post("reqPejabatUnor");
		$reqPejabatGolongan= $this->input->post("reqPejabatGolongan");
		$reqPejabatTmtGolongan= $this->input->post("reqPejabatTmtGolongan");

		$reqAtasanNipId= $this->input->post("reqAtasanNipId");
		$reqNamaAtasan= $this->input->post("reqNamaAtasan");
		$reqAtasanJabatan= $this->input->post("reqAtasanJabatan");
		$reqAtasanUnor= $this->input->post("reqAtasanUnor");
		$reqAtasanGolongan= $this->input->post("reqAtasanGolongan");
		$reqAtasanTmtGolongan= $this->input->post("reqAtasanTmtGolongan");

		$reqStatusValidasi= $this->input->post("reqStatusValidasi");

		$reqPejabatStatus= $this->input->post("reqPejabatStatus");
		$reqAtasanStatus= $this->input->post("reqAtasanStatus");

		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];

		if(!empty($fileName))
		{
			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFile']['size'] >= $maxsize) || ($_FILES["reqLinkFile"]["size"] == 0)) {
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if((!in_array($_FILES['reqLinkFile']['type'], $acceptable)) && (!empty($_FILES["reqLinkFile"]["type"]))) {
				$errors[] = 'File gagal diupload, Pastikan File berformat PDF';
			}

			if(count($errors) > 0) 
			{
				foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
			}
			
		}

		$penilaian_kerja = new PenilaianKerjaPegawai();
		$penilaian_kerja->setField('PEJABAT_PENILAI_ID', ValToNullDB($reqPejabatNipId));
		$penilaian_kerja->setField('ATASAN_PEJABAT_PENILAI_ID', ValToNullDB($reqAtasanNipId));
		$penilaian_kerja->setField('JENIS_JABATAN_ID',  ValToNullDB($reqJenisJabatanId));	

		$penilaian_kerja->setField('TAHUN',  ValToNullDB($reqTahun));
		$penilaian_kerja->setField('NILAI1', ValToNullDB(commaToDot($reqNilai1)));
		$penilaian_kerja->setField('NILAI2', ValToNullDB(commaToDot($reqNilai2)));
		$penilaian_kerja->setField('NILAI3', ValToNullDB(commaToDot($reqNilai3)));
		$penilaian_kerja->setField('NILAI4', ValToNullDB(commaToDot($reqNilai4)));
		$penilaian_kerja->setField('NILAI5', ValToNullDB(commaToDot($reqNilai5)));
		$penilaian_kerja->setField('NILAI6', ValToNullDB(commaToDot($reqNilai6)));
		$penilaian_kerja->setField('JUMLAH', ValToNullDB(commaToDot($reqJumlah)));
		// $penilaian_kerja->setField('RATA_RATA', ValToNullDB(commaToDot($reqRataRata)));
		$penilaian_kerja->setField('JENIS_JABATAN_ID',  ValToNullDB($reqJenisJabatanId));
		$penilaian_kerja->setField('STATUS',  ValToNullDB($reqPejabatStatus));
		$penilaian_kerja->setField('STATUS_ATASAN',  ValToNullDB($reqAtasanStatus));

		$penilaian_kerja->setField('SASARAN_KERJA', ValToNullDB(commaToDot($reqSasaranKerja)));
		$penilaian_kerja->setField('SASARAN_KERJA_HASIL', ValToNullDB(commaToDot($reqSasaranKerjaHasil)));
		$penilaian_kerja->setField('PERILAKU_HASIL', ValToNullDB(commaToDot($reqPerilakuHasil)));
		$penilaian_kerja->setField('NILAI_PERILAKU', ValToNullDB(commaToDot($reqPerilakuKinerja)));
		$penilaian_kerja->setField('NILAI_HASIL', ValToNullDB(commaToDot($reqNilaiHasil)));
		$penilaian_kerja->setField('TMT_GOLONGAN', dateToDBCheck($reqPejabatTmtGolongan));

		$penilaian_kerja->setField('NAMA_ATASAN_PENILAI', strtoupper($reqNamaAtasan));
		$penilaian_kerja->setField('JABATAN_ATASAN_PENILAI', strtoupper($reqAtasanJabatan));
		$penilaian_kerja->setField('UNOR_ATASAN_PENILAI', strtoupper($reqAtasanUnor));
		$penilaian_kerja->setField('GOLONGAN_ATASAN_PENILAI', strtoupper($reqAtasanGolongan));
		$penilaian_kerja->setField('TMT_GOLONGAN_ATASAN', dateToDBCheck($reqAtasanTmtGolongan));

		$penilaian_kerja->setField('REKOMENDASI', $reqRekomendasi);
		$penilaian_kerja->setField('PEGAWAI_ID', $reqPegawaiId);
		$penilaian_kerja->setField('USER_APP_ID', $userLogin->UID);


		$reqSimpan= "";
		if(is_numeric($reqStatusValidasi))
		{
			$penilaian_kerja->setField('VALIDASI', $reqStatusValidasi);
			$penilaian_kerja->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$penilaian_kerja->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$penilaian_kerja->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
			$penilaian_kerja->setField('TEMP_VALIDASI_ID', $reqDataId);

			if($penilaian_kerja->updatevalidasi())
			{
				$reqSimpan= "1";
			}
		}
		else
		{
			if($reqMode == "insert")
			{
				$penilaian_kerja->setField("LAST_CREATE_USER", $userLogin->idUser);
				$penilaian_kerja->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
				$penilaian_kerja->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
				$penilaian_kerja->setField('PENILAIAN_KERJA_PEGAWAI_ID',ValToNullDB($reqRowId));

				if($penilaian_kerja->insert())
				{
					$reqSimpan= 1;
					$reqDataId=$penilaian_kerja->id;
				}
			}
			elseif($reqMode == "update")
			{	
				$penilaian_kerja->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$penilaian_kerja->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$penilaian_kerja->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
				$penilaian_kerja->setField('TEMP_VALIDASI_ID', $reqDataId);
				$penilaian_kerja->setField("PENILAIAN_KERJA_PEGAWAI_ID", $reqId);
				if($penilaian_kerja->update())
				{

					$reqSimpan= 1;
				}
			}
		}

		if(!empty($fileName))
		{
			if(count($errors) === 0) 
			{

				$statement="";
				$set_upload= new PenilaianKerjaPegawai();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 13";
				$set_upload= new PenilaianKerjaPegawai();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				if($reqDataId == "")
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";
				}
				else
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqDataId."\\";
				}

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
				$ext = strtolower($path_parts['extension']);


				if(!empty($reqTahun))
				{
					$renamefile = "SKP_".$reqTahun."_".$tempNipBaru.".".$ext;
				}
				else
				{
					$renamefile = "SKP_".$tempNipBaru.".".$ext;
				}
				
	
				$dest_path = $urlupload . $renamefile;
				// print_r($dest_path);exit;
				if(file_exists($dest_path))
				{
					unlink($dest_path);
				}

				$statement="";

				if(move_uploaded_file($reqLinkFile, $dest_path))
				{
					if($reqDataId == "")
					{
						$statement=" AND A.PENILAIAN_KERJA_PEGAWAI_ID=".$reqRowId;
					}
					else
					{
						$statement=" AND A.TEMP_VALIDASI_ID=".$reqDataId;
					}
					$set_upload= new PenilaianKerjaPegawai();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE");
			
					$link_server=$dest_path;

					if(empty($tempLinkFile))
					{
						$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDataId));
						$set_upload->setField('PENILAIAN_KERJA_PEGAWAI_ID', ValToNullDB($reqRowId));
						$set_upload->setField("LINK_FILE", $dest_path);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$reqSimpan= 1;
					}
				}
			}
			else 
		    {
		    	foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
		    }
		}

		

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}
	
	function jsonriwayatpendidikan()
	{
		$this->load->model("base-validasi/PegawaiPendidikanRiwayat");

		$adminuserid= $this->adminuserid;


		$set= new PegawaiPendidikanRiwayat();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " AND A.PEGAWAI_ID = ".$this->pegawaiId;
		if (!empty($adminuserid))
		{
			$statement.= " AND A.TEMP_VALIDASI_ID IS NOT NULL";
		}
		$sOrder = "";
		$set->selectByParams(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$reqLinkFile = $set->getField("LINK_FILE");
			$reqFileId = $set->getField("PEGAWAI_PENDIDIKAN_RIWAYAT_FILE_ID");
			$reqPegawaiId = $this->pegawaiId;
			$reqDetilId = $set->getField("TEMP_VALIDASI_ID");
			$reqRowId = $set->getField("PEGAWAI_PENDIDIKAN_RIWAYAT_ID");
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "TANGGAL_LULUS" || $valkey == "TMT_SK" || $valkey == "SELESAI_KONTRAK")
				{
					$row[$valkey]= dateToPageCheck($set->getField($valkey));
				}
				else if ($valkey == "KETERANGAN")
				{ 
					$row[$valkey]= '
					<a class="navi-link" onclick=\'btnUpload("'.$reqDetilId.'","'.$reqPegawaiId.'","'.$reqRowId.'");\'>
					<img src="images/upload.png" title="upload" style="width:20px;height:20px">
					</a>
					&nbsp;
					';

					if(!empty($reqLinkFile))
					{
						$row[$valkey].= '
						<a class="navi-link" href="app/loadUrl/main/viewer?reqForm=pendidikan&reqFileId='.$reqFileId.'" target="_blank">
						<img src="images/download.png" title="download" style="width:20px;height:20px">
						</a>
						&nbsp;
						';

						$row[$valkey].= '
						<a class="navi-link" onclick=\'btnDeleteFile("'.$reqFileId.'","'.$reqPegawaiId.'","'.$reqDetilId.'","'.$reqRowId.'");\'>
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

	function jsonriwayatpendidikanupload()
	{
		$this->load->model("base-validasi/PegawaiPendidikanRiwayat");
		$reqDetilId= $this->input->post("reqDetilId");
		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqRowId= $this->input->post("reqRowId");
		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];

		$statement="";
		$set_upload= new PegawaiPendidikanRiwayat();
		$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
		$set_upload->firstRow();
		$tempNipBaru=$set_upload->getField("NIP_BARU");
		unset($set_upload);

		if(!empty($fileName))
		{

			$statement="";
			$set_upload= new PegawaiPendidikanRiwayat();
			$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
			$set_upload->firstRow();
			$tempNipBaru=$set_upload->getField("NIP_BARU");
			unset($set_upload);

			$statement=" AND LINK_SERVER_ID = 11";
			$set_upload= new PegawaiPendidikanRiwayat();
			$set_upload->selectByParamsServer(array(),-1,-1,$statement);
				// echo $set_upload->query;exit;
			$set_upload->firstRow();
			$tempLinkServer=$set_upload->getField("LINK_SERVER");
			$tempLinkFolder=$set_upload->getField("FOLDER");

			if($reqDetilId == "")
			{
				$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";
			}
			else
			{
				$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqDetilId."\\";
			}


			if (!is_dir($urlupload)) {
				makedirs($urlupload);
			}
			
			$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
			$ext = strtolower($path_parts['extension']);

			$renamefile = "PENDIDIKAN_RIWAYAT_".$tempNipBaru.".".$ext;

			$dest_path = $urlupload . $renamefile;
			//print_r($reqLinkFile);exit;
			if(file_exists($dest_path))
			{
				unlink($dest_path);
			}

			$reqSimpan="";
			$statement="";

			if(move_uploaded_file($reqLinkFile, $dest_path))
			{
				if($reqDetilId == "")
				{
					$statement=" AND A.PEGAWAI_PENDIDIKAN_RIWAYAT_ID=".$reqRowId;
				}
				else
				{
					$statement=" AND A.TEMP_VALIDASI_ID=".$reqDetilId;
				}
				$set_upload= new PegawaiPendidikanRiwayat();
				$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkFile=$set_upload->getField("LINK_FILE");
				
				//print_r($tempLinkFile);exit;
		
				$link_server=$dest_path;

				if(empty($tempLinkFile))
				{
					$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDetilId));
					$set_upload->setField('PEGAWAI_PENDIDIKAN_RIWAYAT_ID', ValToNullDB($reqRowId));
					$set_upload->setField("LINK_FILE", $dest_path);
					$set_upload->setField("NAMA_FILE", $renamefile);
					$set_upload->setField("TIPE_FILE", $ext);
					$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
					if($set_upload->insertupload())
					{
						$reqSimpan= 1;
					}
					// echo $set_upload->query;exit;
				}
				else
				{
					$reqSimpan= 1;
				}
			}
		}

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'File berhasil diupload');
		}
		else
		{
			echo json_response(400, 'File gagal diupload, Cek kembali file anda atau hubungi admin');
		}
		
		
		// print_r($reqDetilId);exit;		
	}


	function jsonriwayatpendidikandelete()
	{
		$this->load->model("base-validasi/PegawaiPendidikanRiwayat");
		$reqDetilId= $this->input->get("reqDetilId");
		$set= new PegawaiPendidikanRiwayat();
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
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

	function jsonriwayatpendidikandeletefile()
	{
		$this->load->model("base-validasi/PegawaiPendidikanRiwayat");
		$reqFileId= $this->input->get("reqFileId");
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqDetilId= $this->input->get("reqDetilId");
		$reqRowId= $this->input->get("reqRowId");
		$set= new PegawaiPendidikanRiwayat();
		$set->setField('PEGAWAI_PENDIDIKAN_RIWAYAT_FILE_ID', $reqFileId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);

		$set_upload = new PegawaiPendidikanRiwayat();
		if($reqDetilId == "")
		{
			$set_upload->selectByParams(array('A.PEGAWAI_PENDIDIKAN_RIWAYAT_ID'=>$reqRowId));
		}
		else
		{
			$set_upload->selectByParams(array('A.TEMP_VALIDASI_ID'=>$reqDetilId));
		}
		$set_upload->firstRow();
		$reqLinkFile= $set_upload->getField('LINK_FILE');


		$reqSimpan="";

		if(file_exists($reqLinkFile))
		{
			unlink($reqLinkFile);
		}

		if($set->deletefile())	
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

	function jsonriwayatpendidikanadd()
	{
		$this->load->model("base-validasi/PegawaiPendidikanRiwayat");
		$this->load->model("base-validasi/PejabatPenetap");

		$reqMode= $this->input->post("reqMode");
		$reqPegawaiId= $this->input->post('reqPegawaiId');
		$reqRowId= $this->input->post("reqRowId");
		$reqDataId= $this->input->post("reqDataId");

		$reqPendidikanId= $this->input->post("reqPendidikanId");
		$reqPendidikan= $this->input->post("reqPendidikan");
		$reqJurusanId= $this->input->post("reqJurusanId");
		$reqJurusan= $this->input->post("reqJurusan");
		$reqTglSTTB= $this->input->post("reqTglSTTB");
		$reqTahun= $this->input->post("reqTahun");
		$reqNomorIjazah= $this->input->post("reqNomorIjazah");
		$reqNamaSekolah= $this->input->post("reqNamaSekolah");
		$reqGelarDepan= $this->input->post("reqGelarDepan");
		$reqGelarBelakang= $this->input->post("reqGelarBelakang");
		$reqPendidikanCpns= $this->input->post("reqPendidikanCpns");
		$reqStatusValidasi= $this->input->post("reqStatusValidasi");

		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];

		if(!empty($fileName))
		{
			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFile']['size'] >= $maxsize) || ($_FILES["reqLinkFile"]["size"] == 0)) {
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if((!in_array($_FILES['reqLinkFile']['type'], $acceptable)) && (!empty($_FILES["reqLinkFile"]["type"]))) {
				$errors[] = 'File gagal diupload, Pastikan File berformat PDF';
			}

			if(count($errors) > 0) 
			{
				foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
			}
			
		}

		$set = new PegawaiPendidikanRiwayat();
		$set->setField('PEGAWAI_PENDIDIKAN_ID', ValToNullDB($reqPendidikanId));
		$set->setField('TINGKAT_PENDIDIKAN_ID', ValToNullDB($reqJurusanId));
		$set->setField('TANGGAL_LULUS', dateToDBCheck($reqTglSTTB));
		$set->setField('TAHUN_LULUS', $reqTahun);
		$set->setField('NOMOR_IJAZAH', $reqNomorIjazah);
		$set->setField('NAMA_SEKOLAH', $reqNamaSekolah);
		$set->setField('GELAR_DEPAN', $reqGelarDepan);
		$set->setField('GELAR_BELAKANG', $reqGelarBelakang);
		$set->setField('PENDIDIKAN_CPNS', ValToNullDB($reqPendidikanCpns));
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('USER_APP_ID', $userLogin->UID);


		$reqSimpan= "";
		if(is_numeric($reqStatusValidasi))
		{
			$set->setField('VALIDASI', $reqStatusValidasi);
			$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
			$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
			$set->setField('TEMP_VALIDASI_ID', $reqDataId);

			if($set->updatevalidasi())
			{
				$reqSimpan= "1";
			}
		}
		else
		{
			if($reqMode == "insert")
			{
				$set->setField("LAST_CREATE_USER", $userLogin->idUser);
				$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
				$set->setField('PEGAWAI_PENDIDIKAN_RIWAYAT_ID',ValToNullDB($reqRowId));

				if($set->insert())
				{
					$reqDataId = $set->id;
					$reqSimpan= 1;
				}
			}
			elseif($reqMode == "update")
			{	
				$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
				$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
				$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
				$set->setField('TEMP_VALIDASI_ID', $reqDataId);
				$set->setField('PEGAWAI_PENDIDIKAN_RIWAYAT_ID',ValToNullDB($reqRowId));
				if($set->update())
				{

					$reqSimpan= 1;
				}
			}
		}

		if(!empty($fileName))
		{
			if(count($errors) === 0) 
			{

				$statement="";
				$set_upload= new PegawaiPendidikanRiwayat();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 11";
				$set_upload= new PegawaiPendidikanRiwayat();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				if($reqDataId == "")
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";
				}
				else
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqDataId."\\";
				}
			

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
				$ext = strtolower($path_parts['extension']);

				$renamefile = "PENDIDIKAN_RIWAYAT_".$tempNipBaru.".".$ext;

				$dest_path = $urlupload . $renamefile;
				// print_r($dest_path);exit;
				if(file_exists($dest_path))
				{
					unlink($dest_path);
				}

				$reqSimpan="";
				$statement="";

				if(move_uploaded_file($reqLinkFile, $dest_path))
				{
					if($reqDataId == "")
					{
						$statement=" AND A.PEGAWAI_PENDIDIKAN_RIWAYAT_ID=".$reqRowId;
					}
					else
					{
						$statement=" AND A.TEMP_VALIDASI_ID=".$reqDataId;
					}

					$set_upload= new PegawaiPendidikanRiwayat();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE");
			
					$link_server=$dest_path;

					if(empty($tempLinkFile))
					{
						$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDataId));
						$set_upload->setField('PEGAWAI_PENDIDIKAN_RIWAYAT_ID', ValToNullDB($reqRowId));
						$set_upload->setField("LINK_FILE", $dest_path);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$reqSimpan= 1;
					}
				}
			}
		}
		// echo $set->query;exit;

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}
	function jsonpegawaiskp()
	{
		$this->load->model("base-validasi/PenilaianKerjaPegawai");

		$adminuserid= $this->adminuserid;


		$set= new PenilaianKerjaPegawai();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " AND A.PEGAWAI_ID = ".$this->pegawaiId;

		if (!empty($adminuserid))
		{
			$statement.= " AND A.TEMP_VALIDASI_ID IS NOT NULL";
		}
		$sOrder = "";
		$set->selectByParams(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$reqLinkFile = $set->getField("LINK_FILE");
			$reqPegawaiId = $this->pegawaiId;
			$reqRowId = $set->getField("PENILAIAN_KERJA_PEGAWAI_ID");
			$reqFileId = $set->getField("PENILAIAN_KERJA_PEGAWAI_FILE_ID");
			$reqDetilId = $set->getField("TEMP_VALIDASI_ID");
			$reqTahun = $set->getField("TAHUN");
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "KETERANGAN")
				{ 
					$row[$valkey]= '
					<a class="navi-link" onclick=\'btnUpload("'.$reqDetilId.'","'.$reqPegawaiId.'","'.$reqRowId.'","'.$reqTahun.'");\'>
					<img src="images/upload.png" title="upload" style="width:20px;height:20px">
					</a>
					&nbsp;
					';

					if(!empty($reqLinkFile))
					{
						$row[$valkey].= '
						<a class="navi-link" href="app/loadUrl/main/viewer?reqForm=skp&reqFileId='.$reqFileId.'" target="_blank">
						<img src="images/download.png" title="download" style="width:20px;height:20px">
						</a>
						&nbsp;
						';

						$row[$valkey].= '
						<a class="navi-link" onclick=\'btnDeleteFile("'.$reqFileId.'","'.$reqPegawaiId.'","'.$reqDetilId.'","'.$reqRowId.'");\'>
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

	function jsonpegawaiskpupload()
	{
		$this->load->model("base-validasi/PenilaianKerjaPegawai");
		$reqDetilId= $this->input->post("reqDetilId");
		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqJenisJabatanId= $this->input->post("reqJenisJabatanId");
		$reqTahun= $this->input->post("reqTahun");
		$reqRowId= $this->input->post("reqRowId");
		$reqLinkFile=$_FILES['reqLinkFile']['tmp_name'];
		$fileName = $_FILES['reqLinkFile']['name'];
	
		// print_r($reqRowId);exit();
		$reqSimpan="";
		if(!empty($fileName))
		{

			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);

			if(($_FILES['reqLinkFile']['size'] >= $maxsize) || ($_FILES["reqLinkFile"]["size"] == 0)) {
				$errors[] = 'File terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
			}

			if((!in_array($_FILES['reqLinkFile']['type'], $acceptable)) && (!empty($_FILES["reqLinkFile"]["type"]))) {
				$errors[] = 'File gagal diupload, Pastikan File berformat PDF';
			}

			if(count($errors) === 0) 
			{

				$statement="";
				$set_upload= new PenilaianKerjaPegawai();
				$set_upload->selectByParamsCheckPegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
				$set_upload->firstRow();
				$tempNipBaru=$set_upload->getField("NIP_BARU");
				unset($set_upload);

				$statement=" AND LINK_SERVER_ID = 13";
				$set_upload= new PenilaianKerjaPegawai();
				$set_upload->selectByParamsServer(array(),-1,-1,$statement);
					// echo $set_upload->query;exit;
				$set_upload->firstRow();
				$tempLinkServer=$set_upload->getField("LINK_SERVER");
				$tempLinkFolder=$set_upload->getField("FOLDER");

				if($reqDetilId == "")
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqRowId."\\";
				}
				else
				{
					$urlupload= $tempLinkServer.$tempNipBaru.$tempLinkFolder.$reqDetilId."\\";
				}

				if (!is_dir($urlupload)) {
					makedirs($urlupload);
				}
				
				$path_parts = pathinfo($_FILES["reqLinkFile"]["name"]);
				$ext = strtolower($path_parts['extension']);

				if(!empty($reqTahun))
				{
					$renamefile = "SKP_".$reqTahun."_".$tempNipBaru.".".$ext;
				}
				else
				{
					$renamefile = "SKP_".$tempNipBaru.".".$ext;
				}
				
	
				$dest_path = $urlupload . $renamefile;
				// print_r($dest_path);exit;
				if(file_exists($dest_path))
				{
					unlink($dest_path);
				}

				$statement="";

				if(move_uploaded_file($reqLinkFile, $dest_path))
				{
					if($reqDetilId == "")
					{
						$statement=" AND A.PENILAIAN_KERJA_PEGAWAI_ID=".$reqRowId;
					}
					else
					{
						$statement=" AND A.TEMP_VALIDASI_ID=".$reqDetilId;
					}
					$set_upload= new PenilaianKerjaPegawai();
					$set_upload->selectByParamsUpload(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1,$statement);
					// echo $set_upload->query;exit;
					$set_upload->firstRow();
					$tempLinkFile=$set_upload->getField("LINK_FILE");
			
					$link_server=$dest_path;

					if(empty($tempLinkFile))
					{
						$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set_upload->setField('TEMP_VALIDASI_ID', ValToNullDB($reqDetilId));
						$set_upload->setField('PENILAIAN_KERJA_PEGAWAI_ID', ValToNullDB($reqRowId));
						$set_upload->setField("LINK_FILE", $dest_path);
						$set_upload->setField("NAMA_FILE", $renamefile);
						$set_upload->setField("TIPE_FILE", $ext);
						$set_upload->setField('PEGAWAI_ID', $reqPegawaiId);
						if($set_upload->insertupload())
						{
							$reqSimpan= 1;
						}
					}
					else
					{
						$reqSimpan= 1;
					}
					// echo $set_upload->query;exit;
				}
			}
			else 
		    {
		    	foreach($errors as $error) {
		    		echo json_response(400, $error);
		    	}
		    	exit;
		    }
		}
		

		if($reqSimpan == 1 )
		{
			echo json_response(200, 'File berhasil diupload');
		}
		else
		{
			echo json_response(400, 'File gagal diupload, Cek kembali file anda atau hubungi admin');
		}
		
		
		// print_r($reqDetilId);exit;		
	}

	function jsonpegawaiskpdelete()
	{
		$this->load->model("base-validasi/PenilaianKerjaPegawai");
		$reqDetilId= $this->input->get("reqDetilId");
		$set= new PenilaianKerjaPegawai();
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
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

	function jsonpegawaiskpdeletefile()
	{
		$this->load->model("base-validasi/PenilaianKerjaPegawai");
		$reqFileId= $this->input->get("reqFileId");
		$reqPegawaiId= $this->input->get("reqPegawaiId");
		$reqDetilId= $this->input->get("reqDetilId");
		$reqRowId= $this->input->get("reqRowId");
		$set= new PenilaianKerjaPegawai();
		$set->setField('PENILAIAN_KERJA_PEGAWAI_FILE_ID', $reqFileId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('TEMP_VALIDASI_ID', $reqDetilId);

		$set_upload = new PenilaianKerjaPegawai();
		
		if($reqDetilId == "")
		{
			$set_upload->selectByParams(array('A.PENILAIAN_KERJA_PEGAWAI_ID'=>$reqRowId));
		}
		else
		{
			$set_upload->selectByParams(array('A.TEMP_VALIDASI_ID'=>$reqDetilId));
		}
		
		$set_upload->firstRow();
		$reqLinkFile= $set_upload->getField('LINK_FILE');
		// echo $reqRowId;exit;		
		$reqSimpan="";

		if(file_exists($reqLinkFile))
		{
			unlink($reqLinkFile);
		}

		if($set->deletefile())	
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