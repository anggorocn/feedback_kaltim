<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/class-list-util.php");
include_once("functions/class-list-util-serverside.php");

class export_json extends CI_Controller {

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

	function jsonpegawaikursus()
	{
		$this->load->model("base-app/Export");

		$adminuserid= $this->adminuserid;
		$reqBulan= $this->input->get('reqBulan');
		$reqTahun= $this->input->get('reqTahun');


		$set= new Export();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " ";
		if(!empty($reqBulan))
		{
			$statement .= " AND TO_CHAR(A.LAST_CREATE_DATE, 'MM') = '".$reqBulan."'";	
		}

		if(!empty($reqTahun))
		{
			$statement .= " AND TO_CHAR(A.LAST_CREATE_DATE, 'YYYY') = '".$reqTahun."'";	
		}
		// if (!empty($adminuserid))
		// {
		// 	$statement.= " AND TEMP_VALIDASI_ID IS NOT NULL";
		// }
		$sOrder = "";
		$set->selectByParams(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "TANGGAL_MULAI")
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

	function jsonpegawaidiklat()
	{
		$this->load->model("base-app/Export");

		$adminuserid= $this->adminuserid;
		$reqBulan= $this->input->get('reqBulan');
		$reqTahun= $this->input->get('reqTahun');


		$set= new Export();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= " ";
		if(!empty($reqBulan))
		{
			$statement .= " AND TO_CHAR(A.LAST_CREATE_DATE, 'MM') = '".$reqBulan."'";	
		}

		if(!empty($reqTahun))
		{
			$statement .= " AND TO_CHAR(A.LAST_CREATE_DATE, 'YYYY') = '".$reqTahun."'";	
		}
		// if (!empty($adminuserid))
		// {
		// 	$statement.= " AND TEMP_VALIDASI_ID IS NOT NULL";
		// }
		$sOrder = "";
		$set->selectByParamsDiklat(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
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

	function json_pegawai()
	{
		$this->load->model("base-app/Export");

		$set= new Export();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault);exit;
		$reqId= $this->input->get('reqId');
		$reqValidasi= $this->input->get('reqValidasi');
		$reqSatuanKerja= $this->input->get('reqSatuanKerja');
		$reqTipePegawai= $this->input->get('reqTipePegawai');
		$reqStatusPegawai= $this->input->get('reqStatusPegawai');

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= "";



		if(!empty($reqSatuanKerja))
		{
			$statement.= " AND A.SATKER_ID LIKE '".$reqSatuanKerja."%' ";
		}

		if(!empty($reqTipePegawai))
		{
			$statement.= " AND C.TIPE_PEGAWAI_NEW_ID LIKE '".$reqTipePegawai."%'";
		}

		if ($reqStatusPegawai == 11) {
			$statement.= " AND (STATUS_PEGAWAI = 1 OR STATUS_PEGAWAI = 2 OR STATUS_PEGAWAI = 4 OR STATUS_PEGAWAI = 5)";
		}
		else if ($reqStatusPegawai == 12) {
			$statement.= " AND (STATUS_PEGAWAI = 3 OR STATUS_PEGAWAI = 6)";
		}
		else if ($reqStatusPegawai == 13) {
			$statement.= " AND (STATUS_PEGAWAI = 7)";
		}
		else
		{
			$statement.= "AND (STATUS_PEGAWAI = 1 OR STATUS_PEGAWAI = 2 OR STATUS_PEGAWAI = 4 OR STATUS_PEGAWAI = 5)";
		}


		if (empty($_REQUEST['search']['value'])){}
		else
		{
			if (is_numeric($_REQUEST['search']['value']))
			{
				$statement.= " AND (UPPER(A.NIP_BARU) LIKE '%".strtoupper($_REQUEST['search']['value'])."%')";
			}
			else
			{
				$statement.= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_REQUEST['search']['value'])."%')";
			}
			
		}

		// var_dump($_REQUEST['search']['value']);exit;
		// print_r($reqTipePegawai);exit;
		

		$sOrder = " ";
		// $sOrder = "";
		$set->selectByParamsPegawai(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$reqcheckid = $set->getField("STATUS_PEGAWAI");
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else if ($valkey == "TANGGAL")
				{
					$row[$valkey]= dateToPageCheck($set->getField($valkey));
				}
				else if ($valkey == "GOL_RUANG")
				{
					if ($reqcheckid == 4 || $reqcheckid == 5)
					{
						$row[$valkey]= $set->getField("GOLONGAN_PPPK");
					}
					else
					{
						$row[$valkey]= $set->getField($valkey);
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

			// var_dump($_REQUEST['search']);exit;
			// $keys=array();
			// if ($_REQUEST['search']['value'] != 0)
			// {

			// 	if (is_numeric($_REQUEST['search']['value']))
			// 	{
			// 		// var_dump($_REQUEST['search']['value']);exit;
			// 		$keys = array_keys(array_column($data, 'NIP_BARU'), $_REQUEST['search']['value']);
			// 	}
			// 	else
			// 	{
			// 		// var_dump($_REQUEST['search']['value']);exit;
			// 		$keys = array_keys(array_column($data, 'NAMA'), $_REQUEST['search']['value']);
			// 	}

			// 	$data = array_map(function($k) use ($data){return $data[$k];}, $keys);
			// }
			
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
			// print_r($_REQUEST['length']);exit;
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

}
?>