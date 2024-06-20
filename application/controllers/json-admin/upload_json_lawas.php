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
		$folder = $this->config->item('server_upload');
		$this->load->model("base-app/UploadFile");
		$reqSimpan = '';
		if(isset($_FILES['file']['name'][0]))
		{

			$linkupload= $folder.'upload_file\\';

			if (!is_dir($linkupload)) {
				makedirs($linkupload);
			}

			$errors     = array();
			$maxsize    = 2097152;
			$acceptable = array(
				'application/pdf'
			);
			foreach($_FILES['file']['name'] as $keys => $values)
			{
				$fileName = $_FILES['file']['name'][$keys];
				$fileSize = $_FILES['file']['size'][$keys];
				$fileTipe = $_FILES['file']['type'][$keys];

				if((!in_array($fileTipe, $acceptable)) && (!empty($fileTipe))) {
					$errors[] = 'File gagal diupload, Pastikan File berformat PDF';
				}
				else
				{
					if($fileSize >= $maxsize) {
						$errors[] = 'File '.$fileName.' terlalu besar. <br> Pastikan ukuran file tidak lebih dari 2 MB';
					}
					
				}

				if(count($errors) > 0) 
				{
					foreach($errors as $error) {
						echo json_response(400, $error);
					}
					exit;
				}
				if(count($errors) === 0) 
				{
					$linkfile=$linkupload.$values;
					if(move_uploaded_file($_FILES['file']['tmp_name'][$keys], $linkfile))
					{
						$statement=" AND A.NAMA_FILE='".$fileName."'";
						$set_upload= new UploadFile();
						$set_upload->selectByParams(array(),-1,-1,$statement);
						// echo $set_upload->query;exit;
						$set_upload->firstRow();
						$tempLinkFile=$set_upload->getField("LINK_FILE");
						$fileid=$set_upload->getField("UPLOAD_FILE_ID");
						unset($set_upload);

						if($tempLinkFile != $linkfile)
						{
							$set_upload= new UploadFile();
							$set_upload->setField("LAST_CREATE_USER", $userLogin->idUser);
							$set_upload->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
							$set_upload->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
							$set_upload->setField("LINK_FILE", $linkfile);
							$set_upload->setField("NAMA_FILE", $fileName);
							$set_upload->setField("TIPE_FILE", $fileTipe);
							$set_upload->setField('PEGAWAI_ID', ValToNullDB($reqPegawaiId));
							if($set_upload->insert())
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
		}
		
		if($reqSimpan == 1)
		{
			echo json_response(200, 'File berhasil diupload');
		}
		else
		{
			echo json_response(400, 'File gagal diupload, Cek kembali file anda atau hubungi admin');	
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