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
		ini_set('display_errors', 1);
		$this->load->model("base-app/UploadFile");
		$this->load->model("base-data/SuamiIstri");
		$this->load->model("base-data/SkCpns");
		$this->load->model("base-data/SkPns");
		$this->load->model("base-data/PegawaiJabatan");
		$server = $this->config->item('server_upload');
		$set = new UploadFile();
		$check_peg = new UploadFile();

		$errors     = array();
		$maxsize    = 2097152;
		$acceptable = array(
			'application/pdf'
		);

		// foreach($_FILES['file']['name'] as $keys => $values)
		// {
			// $fileName = $_FILES['file']['name'][$keys];
			// $tmpName = $_FILES['file']['tmp_name'][$keys];
			$fileName = $_FILES['file']['name'];
			$tmpName = $_FILES['file']['tmp_name'];

			$extcheck = str_replace(".pdf", "", $fileName);
			$checknip = explode('_', $extcheck );
			$nipfile=$checknip[1];
			$nipfilecheck=$checknip[2];

			$statement=" AND A.NIP_BARU='".$nipfile."' OR A.NIP_BARU='".$nipfilecheck."' ";
			$check_peg->selectByParamsCheckPegawai(array(),-1,-1,$statement);
				// echo $check_peg->query;exit;
			$check_peg->firstRow();
			$nip=$check_peg->getField("NIP_BARU");
			$pegawaiId=$check_peg->getField("PEGAWAI_ID");
			// unset($check_peg);
			// print_r($fileName);exit();

			if(!empty($nip))
			{
				$acceptname = array(
					'SK_CPNS_'.$nip.'.pdf',
					'SK_PNS_'.$nip.'.pdf',
					'KK_'.$nip.'.pdf',
					'AKTA_NIKAH_'.$nip.'.pdf',
					'SK_JABATAN_'.$nip.'.pdf'
				);


				if((!in_array($fileName, $acceptname))) {
					// print_r($fileName);
					header("HTTP/1.0 400 Bad Request");
					$errors = 'Pastikan nama file sesuai dengan format yang ada';
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
						// echo $check_peg->query;exit;
						$check_peg->firstRow();
						$reqFileId=$check_peg->getField("SK_CPNS_FILE_ID");
						$reqId=$check_peg->getField("SK_CPNS_ID");
						$reqLinkFile=$check_peg->getField("LINK_FILE");
						$statement=" AND A.LINK_SERVER_ID= 8 ";
						$check_folder->selectByParamsCheckFolder(array(),-1,-1,$statement);
							// echo $check_peg->query;exit;
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
							// echo $check->query;exit;
							$check->firstRow();
							$reqId=$check->getField("SK_CPNS_ID");
							// unset($check);

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
								echo "Data Sk Cpns tidak ditemukan";
							}
						}

						if (move_uploaded_file($tmpName, $target_file)) 
						{
							if($reqMode=="insert")
							{
								if($set_upload->insertupload())
								{
									echo "File berhasil di upload";
								}
							}
							else
							{
								if($set_upload->updateupload())
								{
									echo "File berhasil di upload";
								}
							}
						} 
						else 
						{
							echo "Cek kembali file anda";
						}
					}
					elseif ($fileName == $acceptname[1]) {
						$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
						$check_peg->selectByParamsCheckPnsFile(array(),-1,-1,$statement);
						// echo $check_peg->query;exit;
						$check_peg->firstRow();
						$reqFileId=$check_peg->getField("SK_PNS_FILE_ID");
						$reqId=$check_peg->getField("SK_PNS_ID");
						$reqLinkFile=$check_peg->getField("LINK_FILE");
						// unset($check_peg);
						$statement=" AND A.LINK_SERVER_ID= 9 ";
						$check_folder->selectByParamsCheckFolder(array(),-1,-1,$statement);
							// echo $check_peg->query;exit;
						$check_folder->firstRow();
						$reqFolder=$check_folder->getField("FOLDER");
						// unset($check_folder);

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
							// echo $check->query;exit;
							$check->firstRow();
							$reqId=$check->getField("SK_PNS_ID");
							// unset($check);

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
								echo "Data Sk Pns tidak ditemukan";
							}
						}

						if (move_uploaded_file($tmpName, $target_file)) 
						{
							if($reqMode=="insert")
							{
								if($set_upload->insertupload())
								{
									echo "File berhasil di upload";
								}
							}
							else
							{
								if($set_upload->updateupload())
								{
									echo "File berhasil di upload";
								}
							}
						} 
						else 
						{
							echo "Cek kembali file anda";
						}
					}
					elseif ($fileName == $acceptname[2]) {
						$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
						$check_peg->selectByParamsCheckSuamiIstriFile(array(),-1,-1,$statement);
						// echo $check_peg->query;exit;
						$check_peg->firstRow();
						$reqFileId=$check_peg->getField("SUAMI_ISTRI_FILE_ID");
						$reqId=$check_peg->getField("SUAMI_ISTRI_ID");
						$reqLinkFile=$check_peg->getField("LINK_FILE_KK");
						// unset($check_peg);
						$statement=" AND A.LINK_SERVER_ID=15 ";
						$check_folder->selectByParamsCheckFolder(array(),-1,-1,$statement);
							// echo $check_peg->query;exit;
						$check_folder->firstRow();
						$reqFolder=$check_folder->getField("FOLDER");
						// unset($check_folder);
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
							// echo $check->query;exit;
							$check->firstRow();
							$reqId=$check->getField("SUAMI_ISTRI_ID");
							// unset($check);

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
								echo "Data Suami/Istri tidak ditemukan";
							}
						}

						if (move_uploaded_file($tmpName, $target_file)) 
						{
							if($reqMode=="insert")
							{
								if($set_upload->insertupload())
								{
									echo "File berhasil di upload";
								}
							}
							else
							{
								$nama="NAMA_FILE_KK";
								$link="LINK_FILE_KK";
								if($set_upload->updateupload($nama,$link))
								{
									echo "File berhasil di upload";
								}
							}
						} 
						else 
						{
							echo "Cek kembali file anda";
						}
						// print_r($target_file);exit;
					}
					elseif ($fileName == $acceptname[3]) {
						// print_r("3");
					}
					elseif ($fileName == $acceptname[4]) {
						$statement=" AND A.PEGAWAI_ID='".$pegawaiId."' ";
						$check_peg->selectByParamsCheckJabatanFile(array(),-1,-1,$statement);
						// echo $check_peg->query;exit;
						$check_peg->firstRow();
						$reqFileId=$check_peg->getField("PEGAWAI_JABATAN_FILE_ID");
						$reqId=$check_peg->getField("PEGAWAI_JABATAN_ID");
						$reqLinkFile=$check_peg->getField("LINK_FILE");
						// unset($check_peg);
						$statement=" AND A.LINK_SERVER_ID=1 ";
						$check_folder->selectByParamsCheckFolder(array(),-1,-1,$statement);
							// echo $check_peg->query;exit;
						$check_folder->firstRow();
						$reqFolder=$check_folder->getField("FOLDER");
						// unset($check_folder);
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
							// echo $check->query;exit;
							$check->firstRow();
							$reqId=$check->getField("PEGAWAI_JABATAN_ID");
							// unset($check);

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
								echo "Data Jabatan tidak ditemukan";
							}
						}
						// print_r($reqMode);exit;
						if (move_uploaded_file($tmpName, $target_file)) 
						{
							if($reqMode=="insert")
							{
								if($set_upload->insertupload())
								{
									echo "File berhasil di upload";
								}
							}
							else
							{
								$nama="NAMA_FILE_KK";
								$link="LINK_FILE_KK";
								if($set_upload->updateupload($nama,$link))
								{
									echo "File berhasil di upload";
								}
							}
						} 
						else 
						{
							echo "Cek kembali file anda";
						}
					}
					
				}

			}
			else
			{
				header("HTTP/1.0 400 Bad Request");
				echo "Cek kembali file anda ";
			}
			
		// }

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