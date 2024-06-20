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

		$CI =& get_instance();
		$configdata= $CI->config;
        $configvlxsessfolder= $configdata->config["vlxsessfolder"];

        if($this->session->userdata("userpegawaiId".$configvlxsessfolder) == "")
		{
			redirect('login');
		}

		$this->pegawaiId= $this->session->userdata("userpegawaiId".$configvlxsessfolder);
		$this->userpegawaiNama= $this->session->userdata("userpegawaiNama".$configvlxsessfolder);
		$this->userstatuspegId= $this->session->userdata("userstatuspegId".$configvlxsessfolder);

		$this->adminuserid= $this->session->userdata("adminuserid".$configvlxsessfolder);
		$this->adminusernama= $this->session->userdata("adminusernama".$configvlxsessfolder);
		$this->adminuserloginnama= $this->session->userdata("adminuserloginnama".$configvlxsessfolder);
		$this->adminuseraksesappmenu= $this->session->userdata("adminuseraksesappmenu".$configvlxsessfolder);
		$this->userlevel= $this->session->userdata("userlevel".$configvlxsessfolder);
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

	function pangkatriwayatadd()
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

		$reqPegawaiId= $this->pegawaiId;
		$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
		// $sOrder = "";
		$set->selectbyparamsjabatan(array(), $displaylength, $displaystart, $statement);
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

	function jsonpendidikanriwayat()
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

		$reqPegawaiId= $this->pegawaiId;
		$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId;
		// $sOrder = "";
		$set->selectbyparamspendidikan(array(), $displaylength, $displaystart, $statement);
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
}
?>