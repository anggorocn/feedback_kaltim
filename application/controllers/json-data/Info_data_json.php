<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/class-list-util.php");
include_once("functions/class-list-util-serverside.php");

class info_data_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		//kauth

		session_start();
		
		$CI =& get_instance();
		$configdata= $CI->config;
        $configvlxsessfolder= $configdata->config["vlxsessfolder"];
		$reqPegawaiHard=$this->input->get('reqPegawaiHard');

        $redirectlogin= "";
        if(!empty($_SESSION["vuserpegawaimode".$configvlxsessfolder]) && !empty($this->session->userdata("adminuserid".$configvlxsessfolder)))
        {
        	$this->session->set_userdata("userpegawaimode".$configvlxsessfolder, $_SESSION["vuserpegawaimode".$configvlxsessfolder]);
        	$redirectlogin= "";
        }

		if(!empty($this->session->userdata("userpegawaiId".$configvlxsessfolder)) && !empty($redirectlogin))
		{
        	$redirectlogin= "";
        }

        if(!empty($reqPegawaiHard)){
        	$redirectlogin= "";
        }
        // echo $redirectlogin."xx".$this->session->userdata("userpegawaimode".$configvlxsessfolder)."xx".$this->session->userdata("adminuserid".$configvlxsessfolder)."xx".$_SESSION["vuserpegawaimode".$configvlxsessfolder];exit;
        // echo $redirectlogin."xx".$this->session->userdata("userpegawaiId".$configvlxsessfolder);exit;

        if(!empty($redirectlogin))
		{
			redirect('login');
		}

		$this->pegawaiId= $this->session->userdata("userpegawaiId".$configvlxsessfolder);
		$this->userpegawaiNama= $this->session->userdata("userpegawaiNama".$configvlxsessfolder);
		// echo $this->userpegawaiNama; exit;
		$this->userstatuspegId= $this->session->userdata("userstatuspegId".$configvlxsessfolder);
		$this->userpegawaimode= $this->session->userdata("userpegawaimode".$configvlxsessfolder);

		$this->adminuserid= $this->session->userdata("adminuserid".$configvlxsessfolder);
		$this->adminusernama= $this->session->userdata("adminusernama".$configvlxsessfolder);
		$this->adminuserloginnama= $this->session->userdata("adminuserloginnama".$configvlxsessfolder);
		$this->adminuseraksesappmenu= $this->session->userdata("adminuseraksesappmenu".$configvlxsessfolder);

		$this->userlevel= $this->session->userdata("userlevel".$configvlxsessfolder);


        if(!empty($reqPegawaiHard)){
        	$this->userpegawaimode=$reqPegawaiHard;
        }
	}

	function indentitaspegawai()
	{
		// $this->load->model("base-data/Pegawai");

		/*$reqMode= $this->input->post("reqMode");
		
		$set= new Pegawai();
		$set->setField("LAST_CREATE_USER", "");
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqNama= str_replace("\'", "''", $reqNama);	
		$set->setField('NAMA', setQuote($reqNama));
		$set->setField('STATUS_PEGAWAI', ValToNullDB($reqStatusPegawai));
		$set->setField('TANGGAL_LAHIR', dateToDBCheck($reqTanggalLahir));

		$reqSimpan="";
	
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
			if($set->update())
			{
				$reqSimpan = 1;
			}
		}*/

		$reqSimpan="1";
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
		$this->load->model("base-data/InfoData");

		$set= new InfoData();

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

		$userpegawaimode= $this->userpegawaimode;
		$adminuserid= $this->adminuserid;

		if(!empty($userpegawaimode) && !empty($adminuserid))
		    $reqPegawaiId= $userpegawaimode;
		else
		    $reqPegawaiId= $this->pegawaiId;

		$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
		// $sOrder = "";
		$set->selectbyparamspangkat(array(), $displaylength, $displaystart, $statement);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$reqRowId= $set->getField("RIWAYAT_PANGKAT_ID");
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "PANGKAT_INFO")
				{
					$row[$valkey]= $set->getField('PANGKAT_KODE')." (".$set->getField('PANGKAT_NAMA').")";
				}
				else if ($valkey == "TMT_PANGKAT")
				{
					$row[$valkey]= getFormattedDateTime($set->getField($valkey), false);
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

	function riwayatpangkatadd()
	{
		$this->load->model("base-data/RiwayatPangkat");

		$reqMode= $this->input->post("reqMode");
		$reqGolRuang= $this->input->post("reqGolRuang");
		$reqTMTGol= $this->input->post("reqTMTGol");
		$reqTh= $this->input->post("reqTh");
		$reqBl= $this->input->post("reqBl");
		$reqRowId= $this->input->post("reqRowId");
		
		// ambil pegawai id
		$reqPegawaiId= $this->getpegawaiid();

		$set= new RiwayatPangkat();
		$set->setField('RIWAYAT_PANGKAT_ID', $reqRowId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('PANGKAT_ID', $reqGolRuang);
		$set->setField('TMT_PANGKAT', dateToDBCheck($reqTMTGol));
		$set->setField('MK_TAHUN', ValToNullDB($reqTh));
		$set->setField('MK_BULAN', ValToNullDB($reqBl));

		$reqSimpan="";
	
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
			if($set->update())
			{
				$reqSimpan = 1;
			}
		}

		$reqSimpan="1";
		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function jsonpegawaijabatan()
	{
		$this->load->model("base-data/InfoData");

		$set= new InfoData();

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

		$userpegawaimode= $this->userpegawaimode;
		$adminuserid= $this->adminuserid;

		if(!empty($userpegawaimode) && !empty($adminuserid))
		    $reqPegawaiId= $userpegawaimode;
		else
		    $reqPegawaiId= $this->pegawaiId;

		$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
		// $sOrder = "";
		$set->selectbyparamsjabatandata(array(), $displaylength, $displaystart, $statement);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$reqRowId= $set->getField("RIWAYAT_JABATAN_ID");
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "TMT_JABATAN")
				{
					$row[$valkey]= getFormattedDateTime($set->getField($valkey), false);
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

	function jabatanriwayatadd()
	{
		$this->load->model("base-data/InfoData");

		$reqMode= $this->input->post("reqMode");
		$reqRowId= $this->input->post("reqRowId");
		$reqNamaJabatan= $this->input->post("reqNamaJabatan");
		$reqEselon= $this->input->post("reqEselon");
		$reqTh= $this->input->post("reqTh");
		$reqBl= $this->input->post("reqBl");
		$reqTMTJabatan= $this->input->post("reqTMTJabatan");
		
		// ambil pegawai id
		$reqPegawaiId= $this->getpegawaiid();

		$set= new InfoData();
		$set->setField('RIWAYAT_JABATAN_ID', $reqRowId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('ESELON_ID', $reqEselon);	
		$set->setField('JABATAN', setQuote($reqNamaJabatan));
		$set->setField('MASA_JAB_TAHUN', ValToNullDB($reqTh));
		$set->setField('MASA_JAB_BULAN', ValToNullDB($reqBl));
		$set->setField('TMT_JABATAN', dateToDBCheck($reqTMTJabatan));

		$reqSimpan="";
	
		if($reqRowId == "")
		{
			if($set->jabatanriwayatinsert())
			{
				$reqPegawaiId= $set->pegawai_id;
				$reqSimpan = 1;
			}
		}
		else
		{
			if($set->jabatanriwayatupdate())
			{
				$reqSimpan = 1;
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

	function jsonpendidikanriwayat()
	{

		ini_set('memory_limit', -1);
		ini_set('max_execution_time', -1);
		
		$this->load->model("base-data/InfoData");
		$set= new InfoData();

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

		$userpegawaimode= $this->userpegawaimode;
		$adminuserid= $this->adminuserid;

		if(!empty($userpegawaimode) && !empty($adminuserid))
		    $reqPegawaiId= $userpegawaimode;
		else
		    $reqPegawaiId= $this->pegawaiId;
		
		$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
		// $sOrder = "";
		$set->selectbyparamspendidikandata(array(), $displaylength, $displaystart, $statement);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$reqRowId= $set->getField("RIWAYAT_PENDIDIKAN_ID");
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
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

	function pendidikanriwayatadd()
	{
		$this->load->model("base-data/InfoData");

		$reqMode= $this->input->post("reqMode");
		$reqRowId= $this->input->post("reqRowId");
		$reqPendidikanId= $this->input->post("reqPendidikanId");
		$reqKampusId= $this->input->post("reqKampusId");
		$reqJurusan= $this->input->post("reqJurusan");
		$reqJurusanNama= $this->input->post("reqJurusanNama");
		$reqTanggal= $this->input->post("reqTanggal");
		
		// ambil pegawai id
		$reqPegawaiId= $this->getpegawaiid();

		$set= new InfoData();
		$set->setField('RIWAYAT_PENDIDIKAN_ID', $reqRowId);
		$set->setField('PENDIDIKAN_ID', ValToNullDB($reqPendidikanId));
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		// $set->setField('KODE_JURUSAN', ValToNullDB($reqJurusan));
		$set->setField('JURUSAN', $reqJurusanNama);
		$set->setField('KAMPUS_ID', ValToNullDB($reqKampusId));
		$set->setField('TANGGAL', dateToDBCheck($reqTanggal));

		$reqSimpan="";
	
		if($reqRowId == "")
		{
			if($set->pendidikanriwayatinsert())
			{
				$reqPegawaiId= $set->pegawai_id;
				$reqSimpan = 1;
			}
		}
		else
		{
			if($set->pendidikanriwayatupdate())
			{
				$reqSimpan = 1;
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

	function jsondiklatstruktural()
	{
		$this->load->model("base-data/InfoData");

		$set= new InfoData();

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

		$userpegawaimode= $this->userpegawaimode;
		$adminuserid= $this->adminuserid;

		if(!empty($userpegawaimode) && !empty($adminuserid))
		    $reqPegawaiId= $userpegawaimode;
		else
		    $reqPegawaiId= $this->pegawaiId;
		
		$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
		// $sOrder = "";
		$set->selectbyparamsdiklatstrukturaldata(array(), $displaylength, $displaystart, $statement);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$reqRowId= $set->getField("RIWAYAT_PENDIDIKAN_ID");
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "TANGGAL_INFO")
				{
					$row[$valkey]= dateToPageCheck($set->getField("TANGGAL_MULAI"))." s/d ".dateToPageCheck($set->getField("TANGGAL_AKHIR"));
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

	function diklatstrukturaladd()
	{
		$this->load->model("base-data/InfoData");

		$reqMode= $this->input->post("reqMode");
		$reqRowId= $this->input->post("reqRowId");
		$reqNama= $this->input->post("reqNama");
		$reqTanggalMulai= $this->input->post("reqTanggalMulai");
		$reqTanggalAkhir= $this->input->post("reqTanggalAkhir");
		$reqPenyelenggara= $this->input->post("reqPenyelenggara");
		
		// ambil pegawai id
		$reqPegawaiId= $this->getpegawaiid();

		$set= new InfoData();
		$set->setField('RIWAYAT_DIKLAT_STRUKTURAL_ID', $reqRowId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('NAMA', $reqNama);
		$set->setField('PENYELENGGARA', $reqPenyelenggara);
		$set->setField('TANGGAL_MULAI', dateToDBCheck($reqTanggalMulai));
		$set->setField('TANGGAL_AKHIR', dateToDBCheck($reqTanggalAkhir));

		$reqSimpan="";
	
		if($reqRowId == "")
		{
			if($set->diklatstrukturalriwayatinsert())
			{
				$reqPegawaiId= $set->pegawai_id;
				$reqSimpan = 1;
			}
		}
		else
		{
			if($set->diklatstrukturalriwayatupdate())
			{
				$reqSimpan = 1;
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

	function jsondiklatfungsional()
	{
		$this->load->model("base-data/InfoData");

		$set= new InfoData();

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

		$userpegawaimode= $this->userpegawaimode;
		$adminuserid= $this->adminuserid;

		if(!empty($userpegawaimode) && !empty($adminuserid))
		    $reqPegawaiId= $userpegawaimode;
		else
		    $reqPegawaiId= $this->pegawaiId;
		
		$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
		// $sOrder = "";
		$set->selectbyparamsdiklatfungsionaldata(array(), $displaylength, $displaystart, $statement);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$reqRowId= $set->getField("RIWAYAT_PENDIDIKAN_ID");
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "TANGGAL_INFO")
				{
					$row[$valkey]= dateToPageCheck($set->getField("TANGGAL_MULAI"))." s/d ".dateToPageCheck($set->getField("TANGGAL_AKHIR"));
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

	function diklatfungsionaladd()
	{
		$this->load->model("base-data/InfoData");

		$reqMode= $this->input->post("reqMode");
		$reqRowId= $this->input->post("reqRowId");
		$reqNama= $this->input->post("reqNama");
		$reqTanggalMulai= $this->input->post("reqTanggalMulai");
		$reqTanggalAkhir= $this->input->post("reqTanggalAkhir");
		$reqPenyelenggara= $this->input->post("reqPenyelenggara");
		
		// ambil pegawai id
		$reqPegawaiId= $this->getpegawaiid();

		$set= new InfoData();
		$set->setField('RIWAYAT_DIKLAT_FUNGSIONAL_ID', $reqRowId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('NAMA', $reqNama);
		$set->setField('PENYELENGGARA', $reqPenyelenggara);
		$set->setField('TANGGAL_MULAI', dateToDBCheck($reqTanggalMulai));
		$set->setField('TANGGAL_AKHIR', dateToDBCheck($reqTanggalAkhir));

		$reqSimpan="";
	
		if($reqRowId == "")
		{
			if($set->diklatfungsionalriwayatinsert())
			{
				$reqPegawaiId= $set->pegawai_id;
				$reqSimpan = 1;
			}
		}
		else
		{
			if($set->diklatfungsionalriwayatupdate())
			{
				$reqSimpan = 1;
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

	function jsondiklatteknis()
	{
		$this->load->model("base-data/InfoData");

		$set= new InfoData();

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

		$userpegawaimode= $this->userpegawaimode;
		$adminuserid= $this->adminuserid;

		if(!empty($userpegawaimode) && !empty($adminuserid))
		    $reqPegawaiId= $userpegawaimode;
		else
		    $reqPegawaiId= $this->pegawaiId;
		
		$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
		// $sOrder = "";
		$set->selectbyparamsdiklatteknisdata(array(), $displaylength, $displaystart, $statement);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$reqRowId= $set->getField("RIWAYAT_PENDIDIKAN_ID");
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "TANGGAL_INFO")
				{
					$row[$valkey]= dateToPageCheck($set->getField("TANGGAL_MULAI"))." s/d ".dateToPageCheck($set->getField("TANGGAL_AKHIR"));
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

	function diklatteknisadd()
	{
		$this->load->model("base-data/InfoData");

		$reqMode= $this->input->post("reqMode");
		$reqRowId= $this->input->post("reqRowId");
		$reqNama= $this->input->post("reqNama");
		$reqTanggalMulai= $this->input->post("reqTanggalMulai");
		$reqTanggalAkhir= $this->input->post("reqTanggalAkhir");
		$reqPenyelenggara= $this->input->post("reqPenyelenggara");
		
		// ambil pegawai id
		$reqPegawaiId= $this->getpegawaiid();

		$set= new InfoData();
		$set->setField('RIWAYAT_DIKLAT_TEKNIS_ID', $reqRowId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('NAMA', $reqNama);
		$set->setField('PENYELENGGARA', $reqPenyelenggara);
		$set->setField('TANGGAL_MULAI', dateToDBCheck($reqTanggalMulai));
		$set->setField('TANGGAL_AKHIR', dateToDBCheck($reqTanggalAkhir));

		$reqSimpan="";
	
		if($reqRowId == "")
		{
			if($set->diklatteknisriwayatinsert())
			{
				$reqPegawaiId= $set->pegawai_id;
				$reqSimpan = 1;
			}
		}
		else
		{
			if($set->diklatteknisriwayatupdate())
			{
				$reqSimpan = 1;
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

	function totaldiklatteknisadd()
	{
		$this->load->model("base-data/InfoData");
		$reqMode= $this->input->post("reqMode");
		$reqRowId= $this->input->post("reqRowId");
		$reqTotal= $this->input->post("reqTotal");

		// ambil pegawai id
		$reqPegawaiId= $this->getpegawaiid();
		
		$set= new InfoData();
		$set->setField('RIWAYAT_DIKLAT_TEKNIS_ID', $reqRowId);
		$set->setField('JUMLAH', ValToNullDB($reqTotal));
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
	
		if($reqRowId == "")
		{
			if($set->totaldiklatteknisriwayatinsert())
			{
				$reqPegawaiId= $set->pegawai_id;
				$reqSimpan = 1;
			}
		}
		else
		{
			if($set->totaldiklatteknisriwayatupdate())
			{
				$reqSimpan = 1;
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

	function totaldiklatstrukturaladd()
	{
		$this->load->model("base-data/InfoData");
		$reqMode= $this->input->post("reqMode");
		$reqRowId= $this->input->post("reqRowId");
		$reqPim1= $this->input->post("reqPim1");
		$reqPim2= $this->input->post("reqPim2");
		$reqPim3= $this->input->post("reqPim3");
		$reqPim4= $this->input->post("reqPim4");

		// ambil pegawai id
		$reqPegawaiId= $this->getpegawaiid();
		
		$set= new InfoData();
		$set->setField('RIWAYAT_DIKLAT_STRUKTURAL_ID', $reqRowId);
		$set->setField('PIM1', ValToNullDB($reqPim1));
		$set->setField('PIM2', ValToNullDB($reqPim2));
		$set->setField('PIM3', ValToNullDB($reqPim3));
		$set->setField('PIM4', ValToNullDB($reqPim4));
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
	
		if($reqRowId == "")
		{
			if($set->totaldiklatstrukturalriwayatinsert())
			{
				$reqPegawaiId= $set->pegawai_id;
				$reqSimpan = 1;
			}
		}
		else
		{
			if($set->totaldiklatstrukturalriwayatupdate())
			{
				$reqSimpan = 1;
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

	function totaldiklatfungsionaladd()
	{
		$this->load->model("base-data/InfoData");
		$reqMode= $this->input->post("reqMode");
		$reqRowId= $this->input->post("reqRowId");
		$reqKeterampilan= $this->input->post("reqKeterampilan");
		$reqAhliPertama= $this->input->post("reqAhliPertama");
		$reqAhliMuda= $this->input->post("reqAhliMuda");
		$reqAhliMadya= $this->input->post("reqAhliMadya");
		$reqAhliUtama= $this->input->post("reqAhliUtama");
		$reqJenjangId= $this->input->post("reqJenjangId");
		
		if($reqJenjangId == "0")
			$reqJenjangId= "0";
		else if($reqJenjangId == "xxx")
			$reqJenjangId= "";
		// echo $reqJenjangId;exit;

		// ambil pegawai id
		$reqPegawaiId= $this->getpegawaiid();
		
		$set= new InfoData();
		$set->setField('RIWAYAT_DIKLAT_FUNGSIONAL_ID', $reqRowId);
		$set->setField('JENJANG_KETERAMPILAN', ValToNullDB($reqKeterampilan));
		$set->setField('JENJANG_AHLIPERTAMA', ValToNullDB($reqAhliPertama));
		$set->setField('JENJANG_AHLIMUDA', ValToNullDB($reqAhliMuda));
		$set->setField('JENJANG_AHLIMADYA', ValToNullDB($reqAhliMadya));
		$set->setField('JENJANG_AHLIUTAMA', ValToNullDB($reqAhliUtama));
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
	
		if($reqRowId == "")
		{
			if($set->totaldiklatfungsionalriwayatinsert())
			{
				$reqPegawaiId= $set->pegawai_id;
				$reqSimpan = 1;
			}
		}
		else
		{
			if($set->totaldiklatfungsionalriwayatupdate())
			{
				$reqSimpan = 1;
			}
		}

		if($reqSimpan == 1 )
		{
			$set= new InfoData();
			$set->setField('PEGAWAI_ID', $reqPegawaiId);
			$set->setField('LAST_JENJANG_ID', ValToNullDB($reqJenjangId));
			$set->totaldiklatfungsionalriwayatlast();

			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}					
	}

	function jsonjabatanriwayatdelete()
	{
		$this->load->model("base-data/infodata");

		$reqId= $this->input->get("reqDetilId");

		$set= new infodata();
		$set->setField("RIWAYAT_JABATAN_ID", $reqId);
		$set->jabatanriwayatdelete();

		echo json_response(200, $reqId.'-Data berhasil disimpan');
	}

	function jsonpendidikanriwayatdelete()
	{
		$this->load->model("base-data/infodata");

		$reqId= $this->input->get("reqDetilId");

		$set= new infodata();
		$set->setField("RIWAYAT_PENDIDIKAN_ID", $reqId);
		$set->pendidikanriwayatdelete();

		echo json_response(200, $reqId.'-Data berhasil disimpan');
	}

	function jsondiklatstrukturaldelete()
	{
		$this->load->model("base-data/infodata");

		$reqId= $this->input->get("reqDetilId");

		$set= new infodata();
		$set->setField("RIWAYAT_DIKLAT_STRUKTURAL_ID", $reqId);
		$set->strukturalriwayatdelete();

		echo json_response(200, $reqId.'-Data berhasil disimpan');
	}

	function jsondiklatfungsionaldelete()
	{
		$this->load->model("base-data/infodata");

		$reqId= $this->input->get("reqDetilId");

		$set= new infodata();
		$set->setField("RIWAYAT_DIKLAT_FUNGSIONAL_ID", $reqId);
		$set->fungsionalriwayatdelete();

		echo json_response(200, $reqId.'-Data berhasil disimpan');
	}

	function jsondiklatteknisdelete()
	{
		$this->load->model("base-data/infodata");

		$reqId= $this->input->get("reqDetilId");

		$set= new infodata();
		$set->setField("RIWAYAT_DIKLAT_TEKNIS_ID", $reqId);
		$set->teknisriwayatdelete();

		echo json_response(200, $reqId.'-Data berhasil disimpan');
	}

	function totalpltplhadd()
	{
		$this->load->model("base-data/InfoData");
		$reqTipe= $this->input->post("reqTipe");
		$reqJumlah= $this->input->post("reqJumlah");
		$reqId= $this->input->post("reqId");

		// ambil pegawai id
		$reqPegawaiId= $this->getpegawaiid();

		for ($i=0;$i<count($reqId);$i++){

			$set= new InfoData();
			$set->setField('TOTAL_PLT_PLH_ID', $reqId[$i]);
			$set->setField('TIPE', ValToNullDB($reqTipe[$i]));
			$set->setField('JUMLAH', ValToNullDB($reqJumlah[$i]));
			$set->setField('PEGAWAI_ID', $reqPegawaiId);

			if($reqId[$i]==''){
				$set->totalpltplhinsert();
			}
			else{
				$set->totalpltplhupdate();
			}
			
		}

		// echo json_response(400, 'Data gagal disimpan');
		echo json_response(200, 'Data berhasil disimpan');

	
	}

	function totalpltplhdel()
	{
		$this->load->model("base-data/infodata");

		$reqId= $this->input->get("reqId");

		$set= new infodata();
		$set->setField("TOTAL_PLT_PLH_ID", $reqId);
		$set->pltplhdelete();

		echo json_response(200, $reqId.'-Data berhasil disimpan');
	}

	function totalkedudukantimadd()
	{
		$this->load->model("base-data/InfoData");
		$reqMode= $this->input->post("reqMode");
		$reqRowId= $this->input->post("reqRowId");
		$reqKetua= $this->input->post("reqKetua");
		$reqAnggota= $this->input->post("reqAnggota");

		// ambil pegawai id
		$reqPegawaiId= $this->getpegawaiid();
		
		$set= new InfoData();
		$set->setField('TOTAL_KEDUDUKAN_TIM_ID', $reqRowId);
		$set->setField('KETUA', ValToNullDB($reqKetua));
		$set->setField('ANGGOTA', ValToNullDB($reqAnggota));
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$reqSimpan="";
	
		if($reqRowId == "")
		{
			if($set->totalkedudukantiminsert())
			{
				$reqPegawaiId= $set->pegawai_id;
				$reqSimpan = 1;
			}
		}
		else
		{
			if($set->totalkedudukantimupdate())
			{
				$reqSimpan = 1;
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

	function getpegawaiid()
	{
		$userpegawaimode= $this->userpegawaimode;
		$adminuserid= $this->adminuserid;
		if(!empty($userpegawaimode) && !empty($adminuserid))
		    $reqPegawaiId= $userpegawaimode;
		else
		    $reqPegawaiId= $this->pegawaiId;

		return $reqPegawaiId;
	}
}
?>