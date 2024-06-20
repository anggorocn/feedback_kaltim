<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/class-list-util.php");
include_once("functions/class-list-util-serverside.php");

class user_json extends CI_Controller {

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

	function json()
	{
		$this->load->model("base-app/Users");
		$reqStatus= $this->input->get("reqStatus");

		$set= new Users();

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

		$statement= "";
		if($reqStatus == ""){}
			else
				$statement.= " AND JML.JUMLAH_USER_LOGIN > 1";
		// $sOrder = " ORDER BY A.TANGGAL ASC";
		$sOrder = "";
		$set->selectByParamsMonitoring(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		$no=1;
		while ($set->nextRow()) 
		{
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
				else if ($valkey == "TANGGAL")
				{
					$row[$valkey]= dateToPageCheck($set->getField($valkey));
				}
				else
					$row[$valkey]= $set->getField($valkey);
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

	function add()
	{
		// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
		$this->load->model('base-app/Users');
		$this->load->model('base-app/UsersLog');
		
		$set= new Users();
		
		$reqMode= $this->input->post("reqMode");
		$reqId= $this->input->post("reqId");

		$reqNama= $this->input->post("reqNama");
		$reqNamaLoginTemp= $this->input->post("reqNamaLoginTemp");
		$reqNamaLogin	= $this->input->post("reqNamaLogin");
		$reqPassword 	= $this->input->post("reqPassword");
		$reqUserGroup 	= $this->input->post("reqUserGroup");
		$reqSatkerId	= $this->input->post("reqSatkerId");
		$reqAlamat		= $this->input->post("reqAlamat"); 
		$reqEmail		= $this->input->post("reqEmail"); 
		$reqTelepon		= $this->input->post("reqTelepon"); 
		$reqPegawaiId	= $this->input->post("reqPegawaiId"); 
		
		
		$set->setField('USER_GROUP_ID', $reqUserGroup);
		$set->setField('USER_LOGIN', $reqNamaLogin);
		$set->setField('USER_PASS', $reqPassword);
		$set->setField('NAMA', $reqNama);
		$set->setField('ALAMAT', $reqAlamat);
		$set->setField('EMAIL', $reqEmail);
		$set->setField('TELEPON', $reqTelepon);
		if($reqUserGroup==1)
		{
			$set->setField('PEGAWAI_ID', ValToNullDB($reqPegawaiId));	
		}
		else
		{
			$set->setField('PEGAWAI_ID',0);
		}
		$set->setField('SATKER_ID', $reqSatkerId);
		$set->setField('USER_APP_ID', $reqId);		

		
		$reqSimpan= "";
		if($reqMode == "insert")
		{
			$set->setField("LAST_CREATE_USER", $this->adminusernama);
			$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
			$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);

			$check= new Users();
			$check->selectByParamsMonitoring(array("A.USER_LOGIN"=>$reqNamaLogin));
			$check->firstRow();
			$reqNamaLogin  = $check->getField('USER_LOGIN');
			if($reqNamaLogin == ""){}
			else
			{
					echo json_response(400, "Data tidak bisa simpan karena ada user login yang sama");
					exit;
			}

			if($set->insert())
			{
				$reqId= $set->id;
				$tempKeteranganLog= setLogInfo($reqMode, $this->adminusernama, "USER_APP");
				$set_log= new UsersLog();
				$set_log->setField("USER_APP_ID", $this->adminuserid);
				$set_log->setField("KETERANGAN", $tempKeteranganLog);
				$set_log->insert();
				unset($set_log);
				$reqSimpan= 1;
			}
		}
		else
		{
			if($set->update())
			{
				$tempKeteranganLog= setLogInfo($reqMode, $this->adminusernama, "USER_APP");
				$set_log= new UsersLog();
				$set_log->setField("USER_APP_ID", $this->adminuserid);
				$set_log->setField("KETERANGAN", $tempKeteranganLog);
				$set_log->insert();
				unset($set_log);
				$reqSimpan= 1;
			}
		}
		
		if($reqSimpan == 1)
		{
			echo json_response(200, $reqId."-Data berhasil disimpan.");
		}
		else
		{
			echo json_response(400, "Data gagal disimpan");
		}
	}

	function delete()
	{
		$this->load->model("base-app/Users");
		$reqId= $this->input->get("reqId");
		$set= new Users();
		$set->setField('USER_APP_ID', $reqId);
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

	function reset()
	{
		// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
		$this->load->model('base-app/Users');
		$this->load->model('base-app/UsersLog');
		
		$set= new Users();
		
		$reqMode= $this->input->post("reqMode");
		$reqId= $this->input->post("reqId");


		$reqPassword 	= $this->input->post("reqPassword");

		$set->setField('USER_PASS', $reqPassword);
		$set->setField('USER_APP_ID',$reqId);

		
		$reqSimpan= "";

		if($set->updatePassword())
		{
			$reqId= $set->id;
			$tempKeteranganLog= setLogInfo($reqMode, $this->adminusernama, "USER_APP");
			$set_log= new UsersLog();
			$set_log->setField("USER_APP_ID", $this->adminuserid);
			$set_log->setField("KETERANGAN", $tempKeteranganLog);
			$set_log->insert();
			unset($set_log);
			$reqSimpan= 1;
		}
		
		
		if($reqSimpan == 1)
		{
			echo json_response(200, $reqId."-Data berhasil disimpan.");
		}
		else
		{
			echo json_response(400, "Data gagal disimpan");
		}
	}



}
?>