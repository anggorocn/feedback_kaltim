<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/class-list-util.php");
include_once("functions/class-list-util-serverside.php");
include_once("functions/excel_reader2.php");


class import_json extends CI_Controller {

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

	function jsonpegawaijabatantipe()
	{
		$this->load->model("base-app/Import");

		$set= new Import();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		// $statement= " AND A.PEGAWAI_ID = ".$this->pegawaiId;
		$statement= "";

		if (empty($_REQUEST['search']['value'])){}
		else
		{
			if (is_numeric($_REQUEST['search']['value']))
			{
				$statement.= " AND (UPPER(A.NIP_BARU) LIKE '%".strtoupper($_REQUEST['search']['value'])."%')";
			}
			else
			{
				$statement.= " AND (UPPER(A.NAMA_PEGAWAI) LIKE '%".strtoupper($_REQUEST['search']['value'])."%')";
			}
			
		}

		$sOrder = " ORDER BY A.GROUP_NAMA,A.TMT_JABATAN ASC ";
		$set->selectByParamsRiwayatJabatan(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$jenisjabatan = $set->getField("JENIS_JABATAN_ID");
			// print_r($jenisjabatan);exit;
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				// print_r($valkey.'<br>');

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
				else if ($valkey == "TMT_JABATAN")
				{ 
					$row[$valkey]= dateToPageCheck($set->getField($valkey));
					
				}
				else
				{
					$row[$valkey]= $set->getField($valkey);
				}
			}
			array_push($arrinfodata, $row);
		}
		// exit;

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
			// $data         = filterKeyword( $data, $_REQUEST['search'] );
			// $totalDisplay = count( $data );
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
	
	function jsonpegawaipendidikan()
	{
		$this->load->model("base-app/Import");

		$set= new Import();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		// $statement= " AND A.PEGAWAI_ID = ".$this->pegawaiId;
		$statement= "";

		if (empty($_REQUEST['search']['value'])){}
		else
		{
			if (is_numeric($_REQUEST['search']['value']))
			{
				$statement.= " AND (UPPER(P.NIP_BARU) LIKE '%".strtoupper($_REQUEST['search']['value'])."%')";
			}
			else
			{
				$statement.= " AND (UPPER(P.NAMA) LIKE '%".strtoupper($_REQUEST['search']['value'])."%')";
			}
			
		}

		$sOrder = " ORDER BY P.NAMA, A.PEGAWAI_ID ";
		$set->selectByParamsRiwayatPendidikan(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$jenisjabatan = $set->getField("JENIS_JABATAN_ID");
			// print_r($jenisjabatan);exit;
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				// print_r($valkey.'<br>');

				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "TANGGAL_LULUS") 
				{
					$row[$valkey]= dateToPageCheck($set->getField($valkey));
				}
				else
				{
					$row[$valkey]= $set->getField($valkey);
				}
			}
			array_push($arrinfodata, $row);
		}
		// exit;

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
			// $data         = filterKeyword( $data, $_REQUEST['search'] );
			// $totalDisplay = count( $data );
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

	function import_jabatan() 
	{
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);

		$data = new Spreadsheet_Excel_Reader($_FILES['reqLinkFile']['tmp_name']);
		$reqJenisJabatan= $this->input->post("reqJenisJabatan");
		// print_r($reqJenisJabatan);exit;

		$baris = $data->rowcount($sheet_index=0);
		 // var_dump($baris);exit;

		$arrField= array("NIP_FIELD","ID_JABATAN","NO_SK","TMT_JABATAN","TANGGAL_SK","ID_STRUKTURAL");

		$this->load->model("base-app/Import");

		$set = new Import();
		
		$checksimpan=1;
		$reqSimpan="";
		$index=2;
		for ($i=2; $i<=$baris; $i++){
			$colIndex=1;
			$arrData="";

			for($row=0; $row < count($arrField); $row++){

				$tempValue= $data->val($i,$colIndex);
				$arrData[$arrField[$row]]['VALUE']= $data->val($i,$colIndex);

				if($arrField[$row]=="NIP_FIELD")
				{
					if (!empty($tempValue))
					{
						$statement =" AND A.NIP_BARU ='".$tempValue."'";
						$nip = new Import();
						$nip->selectByParamsCheckPegawai(array(), -1, -1, $statement, $sOrder);
						// echo $nip->query;exit;
						$nip->firstRow();
						$reqPegawaiId=$nip->getField("PEGAWAI_ID");
						$reqUnorId=$nip->getField("SATKER_ID");

						$set->setField("PEGAWAI_ID",$reqPegawaiId);
						$set->setField("UNOR_ID",ValToNullDB($reqUnorId));
					}
					else
					{
						echo json_response(400, 'NIP Belum Diisi');
						exit();
					}
				}
				else if($arrField[$row]=="ID_JABATAN")
				{
					if (!empty($tempValue))
					{
						$nip = new Import();
						if ($reqJenisJabatan == 1)
						{
							$statement =" AND A.ID_JABATAN ='".$tempValue."'";
							$nip->selectByParamsCheckStruktural(array(), -1, -1, $statement, $sOrder);
							$nip->firstRow();
							$reqJabatanStrukturalId=$nip->getField("JABATAN_STRUKTURAL_NEW_ID");
							if (empty($reqJabatanStrukturalId))
							{
								$checksimpan=0;
								echo json_response(400, 'ID Jabatan '.$tempValue.' Tidak Ada');
								exit();
								
							}
							// echo $nip->query;exit;
						}
						else if ($reqJenisJabatan == 2)
						{
							$statement =" AND A.ID_JABATAN ='".$tempValue."'";
							$nip->selectByParamsCheckFungsional(array(), -1, -1, $statement, $sOrder);
							$nip->firstRow();
							$reqJabatanFungsionalId=$nip->getField("JABATAN_FUNGSIONAL_NEW_ID");
							if (empty($reqJabatanFungsionalId))
							{
								$checksimpan=0;
								echo json_response(400, 'ID Jabatan '.$tempValue.' Tidak Ada');
								exit();
								
							}
							// echo $nip->query;exit;
						}
						else if($reqJenisJabatan == 3)
						{
							$statement =" AND A.ID_JABATAN ='".$tempValue."'";
							$nip->selectByParamsCheckPelaksana(array(), -1, -1, $statement, $sOrder);
							$nip->firstRow();
							$reqJabatanPelaksanaId=$nip->getField("JABATAN_PELAKSANA_NEW_ID");
							if (empty($reqJabatanPelaksanaId))
							{
								$checksimpan=0;
								echo json_response(400, 'ID Jabatan '.$tempValue.' Tidak Ada');
								exit();
								
							}
							// var_dump ($reqJabatanPelaksanaId);exit;
						}
						else if ($reqJenisJabatan == 4)
						{
							$statement =" AND A.ID_JABATAN ='".$tempValue."'";
							$nip->selectByParamsCheckFungsional(array(), -1, -1, $statement, $sOrder);
							$nip->firstRow();
							$reqJabatanFungsionalId=$nip->getField("JABATAN_FUNGSIONAL_NEW_ID");
							if (empty($reqJabatanFungsionalId))
							{
								$checksimpan=0;
								echo json_response(400, 'ID Jabatan '.$tempValue.' Tidak Ada');
								exit();
								
							}
							// echo $nip->query;exit;
						}
						$reqEselonId=$nip->getField("ESELON_ID");
						$reqTipePegawaiId=$nip->getField("TIPE_PEGAWAI_NEW_ID");
						$reqJabatanFungsionalNama=$nip->getField("FUNGSIONAL_NAMA");
						$reqBup=$nip->getField("BUP");
						$reqKelasJabatan=$nip->getField("KELAS_JABATAN");

						$set->setField("ESELON_ID",ValToNullDB($reqEselonId));
						$set->setField("JENIS_JABATAN_ID",$reqJenisJabatan);
						$set->setField("TIPE_PEGAWAI_NEW_ID",$reqTipePegawaiId);
						$set->setField("JABATAN_FUNGSIONAL_NEW_ID",$reqJabatanFungsionalId);
						$set->setField("JABATAN_PELAKSANA_NEW_ID",$reqJabatanPelaksanaId);
						$set->setField("JABATAN_STRUKTURAL_NEW_ID",$reqJabatanStrukturalId);
						$set->setField("BUP",ValToNullDB($reqBup));
						$set->setField("KELAS_JABATAN",ValToNullDB($reqKelasJabatan));
						$set->setField("FUNGSIONAL_NAMA",$reqJabatanFungsionalNama);

					}
					else
					{
						echo json_response(400, 'ID Jabatan Belum Diisi');
						exit();
					}
					
				}
				else if($arrField[$row]=="TMT_JABATAN")
				{
					$checktanggal = isDateCheckVal($tempValue);
					if ($checktanggal == 1)
					{
						$tanggal  = date("d-m-Y", strtotime($tempValue));
						$checktanggaltmt = $tanggal;
						// print_r($tanggal);exit;
						$set->setField($arrField[$row],dateToDBCheck($tanggal));
					}
					else
					{
						$checksimpan=0;
						echo json_response(400, 'Format tanggal tidak sesuai');
						exit();
					}
					
				}
				else if($arrField[$row]=="TANGGAL_SK" )
				{
					$checktanggal = isDateCheckVal($tempValue);
					if ($checktanggal == 1)
					{
						$tanggal  = date("d-m-Y", strtotime($tempValue));
						// print_r($tanggal);exit;
						$set->setField($arrField[$row],dateToDBCheck($tanggal));
					}
					else
					{
						$checksimpan=0;
						echo json_response(400, 'Format tanggal tidak sesuai');
						exit();
					}
					
				}
				else if($arrField[$row]=="ID_STRUKTURAL")
				{
					if (!empty($tempValue))
					{
						if($reqJenisJabatan == 4)
						{
							$nip = new Import();
							$statement =" AND A.ID_JABATAN ='".$tempValue."'";
							$nip->selectByParamsCheckStruktural(array(), -1, -1, $statement, $sOrder);
							$nip->firstRow();
							$reqJabatanStrukturalId=$nip->getField("JABATAN_STRUKTURAL_NEW_ID");
							$reqJabatanStrukturalNama=$nip->getField("NAMA");

							if (empty($reqJabatanStrukturalId))
							{
								$checksimpan=0;
								echo json_response(400, 'ID Jabatan Struktural '.$tempValue.' Tidak Ada');
								exit();
							}
							$set->setField("TUGAS_TAMBAHAN_ID",$reqJabatanStrukturalId);
							$set->setField("TUGAS_TAMBAHAN_NAMA",$reqJabatanStrukturalNama);
						}
					}
				}
				else
				{
					
					$set->setField($arrField[$row],$tempValue);
				}
				$colIndex++;
			}

			$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");

			if (!empty($reqPegawaiId) && !empty($checktanggaltmt))
			{
				$checkjabatan = new Import();
				$cektanggaljabatan = date("Y-m-d", strtotime($checktanggaltmt));
				$statement =" AND A.PEGAWAI_ID ='".$reqPegawaiId."' AND A.TMT_JABATAN = '".$cektanggaljabatan."'";
				$checkjabatan->selectByParamsCheckJabatan(array(), -1, -1, $statement, $sOrder);
				// echo $checkjabatan->query;exit;
				$checkjabatan->firstRow();
				$reqCheckPegawaiId=$checkjabatan->getField("PEGAWAI_ID");
				$reqPegawaiJabatanId=$checkjabatan->getField("PEGAWAI_JABATAN_ID");
				$reqTanggalCheck=dateToPageCheck($checkjabatan->getField("TMT_JABATAN"));
				// print_r($reqPegawaiJabatanId);

				// print_r($reqTanggalCheck);
				if($reqTanggalCheck != $checktanggaltmt)
				{
					if($set->insertjabatan())
					{
						$reqSimpan = 1;
					}

				}
				else
				{
					$set->setField("PEGAWAI_JABATAN_ID",$reqPegawaiJabatanId);
					$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
					if($set->updateJabatan())
					{
						$reqSimpan = 1;
					}

				}

			}

		}
		// exit;

		if($reqSimpan == 1 && $checksimpan==1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');	
		}
	}
	
	
	function import_pendidikan() 
	{
		ini_set("memory_limit","500M");
		ini_set('max_execution_time', 520);

		$data = new Spreadsheet_Excel_Reader($_FILES['reqLinkFile']['tmp_name']);

		$baris = $data->rowcount($sheet_index=0);

		$arrField= array("NIP_BARU","ID_PEGAWAI_PENDIDIKAN","TANGGAL_LULUS","TAHUN_LULUS","NOMOR_IJAZAH","NAMA_SEKOLAH","GELAR_DEPAN","GELAR_BELAKANG","PENDIDIKAN_CPNS");

		$this->load->model("base-app/Import");


		$set = new Import();
		
		$checksimpan=1;
		$reqSimpan="";
		$checkijazah="";
		$index=2;
		for ($i=2; $i<=$baris; $i++){
			$colIndex=1;
			$arrData="";

			for($row=0; $row < count($arrField); $row++){

				$tempValue= $data->val($i,$colIndex);
				$arrData[$arrField[$row]]['VALUE']= $data->val($i,$colIndex);

				if($arrField[$row]=="NIP_BARU")
				{
					if (!empty($tempValue))
					{
						$statement =" AND A.NIP_BARU ='".$tempValue."'";
						$nip = new Import();
						$nip->selectByParamsCheckPegawai(array(), -1, -1, $statement, $sOrder);
						// echo $nip->query;exit;
						$nip->firstRow();
						$reqPegawaiId=$nip->getField("PEGAWAI_ID");
						if(empty($reqPegawaiId))
						{
							echo json_response(400, 'Nip Baru Pegawai tidak ditemukan');
							exit();
						}
						else
						{
							$set->setField("PEGAWAI_ID",$reqPegawaiId);
						}

					}
					else
					{
						echo json_response(400, 'NIP Belum Diisi');
						exit();
					}
				}
				else if($arrField[$row]=="ID_PEGAWAI_PENDIDIKAN")
				{
					if (!empty($tempValue))
					{
						$statement =" AND ID_PEGAWAI_PENDIDIKAN ='".$tempValue."'";
						$pendidikan = new Import();
						$pendidikan->selectByParamsCheckPendidikan(array(), -1, -1, $statement, $sOrder);
						// echo $pendidikan->query;exit;
						$pendidikan->firstRow();
						$reqPegawaiPendidikanId=$pendidikan->getField("PEGAWAI_PENDIDIKAN_ID");
						$reqIdPegawaiPendidikan=$pendidikan->getField("ID_PEGAWAI_PENDIDIKAN");
						$reqTingkatPendidikanId=$pendidikan->getField("TINGKAT_PENDIDIKAN_ID");

						if(empty($reqPegawaiPendidikanId))
						{
							echo json_response(400, 'Id Pegawai Pendidikan tidak ditemukan');
							exit();
						}
						else
						{
							$set->setField("PEGAWAI_PENDIDIKAN_ID",$reqPegawaiPendidikanId);
							$set->setField("ID",$reqIdPegawaiPendidikan);
							$set->setField("TINGKAT_PENDIDIKAN_ID",$reqTingkatPendidikanId);
						}

					}
					else
					{
						echo json_response(400, 'Id Pegawai Pendidikan belum diisi');
						exit();
					}
				}
				else if($arrField[$row]=="TANGGAL_LULUS")
				{

					$checktanggal = isDateCheckValNew($tempValue);
					if ($checktanggal == 1)
					{
						$tanggal  = date("d-m-Y", strtotime($tempValue));
						$checktanggaltmt = $tanggal;
						// print_r($checktanggaltmt);exit;
						$set->setField($arrField[$row],dateToDBCheck($tanggal));
					}
					else
					{
						$checksimpan=0;
						echo json_response(400, 'Format tanggal tidak sesuai');
						exit();
					}
					
				}
				else if($arrField[$row]=="NOMOR_IJAZAH")
				{
					$checkijazah = $tempValue;
					$set->setField($arrField[$row],$tempValue);
				}
				else
				{
					
					$set->setField($arrField[$row],$tempValue);
				}
				$colIndex++;
			}

			$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");

			if (!empty($reqPegawaiId) && !empty($checktanggaltmt))
			{
				$checkpendidikan = new Import();
				$cektanggaljabatan = date("Y-m-d", strtotime($checktanggaltmt));
				$statement =" AND PEGAWAI_ID ='".$reqPegawaiId."' AND TANGGAL_LULUS = '".$cektanggaljabatan."'";
				$checkpendidikan->selectByParamsCheckRiwayatPendidikan(array(), -1, -1, $statement, $sOrder);
				// echo $checkpendidikan->query;exit;
				$checkpendidikan->firstRow();
				$reqTanggalCheck=dateToPageCheck($checkpendidikan->getField("TANGGAL_LULUS"));
				$reqPegawaiPendidikanRiwayatId=$checkpendidikan->getField("PEGAWAI_PENDIDIKAN_RIWAYAT_ID");
				$reqPegawaiIjazah=$checkpendidikan->getField("NOMOR_IJAZAH");
				//var_dump($checkijazah);exit;

				if($reqPegawaiIjazah != $checkijazah)
				{
					if($set->insertpendidikan())
					{
						$reqSimpan = 1;
					}

				}
				else
				{
					$set->setField("PEGAWAI_PENDIDIKAN_RIWAYAT_ID",$reqPegawaiPendidikanRiwayatId);
					$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");
					if($set->updatependidikan())
					{
						$reqSimpan = 1;
					}
				}
			}
		}
		// exit;

		if($reqSimpan == 1 && $checksimpan==1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');	
		}
	}
	

}
?>