<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/class-list-util.php");
include_once("functions/class-list-util-serverside.php");

class user_group_json extends CI_Controller {

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
		$this->load->model("base-app/UserGroup");

		$set= new UserGroup();

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
		// $sOrder = " ORDER BY A.TANGGAL ASC";
		$sOrder = "";
		$set->selectByParamsMonitoring(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else if ($valkey == "TANGGAL")
				{
					$row[$valkey]= dateToPageCheck($set->getField($valkey));
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

	function add()
	{
		$this->load->model('base-app/UserGroup');
		
		$set= new UserGroup();
		
		$reqMode= $this->input->post("reqMode");
		$reqId= $this->input->post("reqId");
		$reqNama= $this->input->post("reqNama");
		$reqAksesAppSimpegId= $this->input->post("reqAksesAppSimpegId");
		
		$set->setField("NAMA", $reqNama);
		$set->setField("AKSES_APP_SIMPEG_ID", ValToNullDB($reqAksesAppSimpegId));
		$set->setField("LAST_USER", $this->LOGIN_USER);
		$set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
		$set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
	    $set->setField("LAST_DATE", "NOW()");
		$set->setField("USER_GROUP_ID", $reqId);
		
		$reqSimpan= "";
		if($reqMode == "insert")
		{
			if($set->insert())
			{
				$reqId= $set->id;
				$reqSimpan= 1;
			}
		}
		else
		{
			if($set->update())
			{
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

	function add_menu()
	{
		$this->load->model('base-app/UserGroup');
		
		$reqMode= $this->input->post("reqMode");
		$reqMenuId= $this->input->post("reqMenuId");
		$reqCheck= $this->input->post("reqCheck");
		$reqNama= $this->input->post("reqNama");
		$reqRowId= $this->input->post("reqRowId");
		$reqTable= $this->input->post("reqTable");
		
		//$set = new AksesAdmIntranet();
		//$set_menu = new AksesAdmIntranetMenu();
		$set= new UserGroup();
		$set_menu= new UserGroup();

		$reqSimpan= "";
		$setquery="";
		if($reqMode == "insert")
		{	
			  $set->setField("NAMA", $reqNama);
			  $set->setField("TABLE", $reqTable);
			  $set->setField("LAST_USER", $this->LOGIN_USER);
			  $set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			  $set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			  $set->setField("LAST_DATE", "NOW()");
			  $set->insertMenu();
			  $reqRowId= $set->id;
			  for($i=0;$i<count($reqMenuId);$i++)
			  {
				  $set_menu = new UserGroup();
				  $set_menu->setField("AKSES_ADM_INTRANET_ID", $reqRowId);
				  $set_menu->setField("MENU_ID", $reqMenuId[$i]);
				  $set_menu->setField("AKSES", $reqCheck[$i]);
				  $set_menu->setField("TABLE", $reqTable);
				  $set_menu->setField("LAST_USER", $this->LOGIN_USER);
				  $set_menu->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				  $set_menu->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			  	  $set_menu->setField("LAST_DATE", "NOW()");
				  $set_menu->insertMenuDetil();
				  unset($set_menu);	  
			  }
			  $reqSimpan= 1;
		}
		else
		{
			  $set->setField("NAMA", $reqNama);
			  $set->setField("AKSES_ADM_INTRANET_ID", $reqRowId);	  
			  $set->setField("TABLE", $reqTable);
			  $set->setField("LAST_USER", $this->LOGIN_USER);
			  $set->setField("USER_LOGIN_ID", $this->LOGIN_ID);
			  $set->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
			  $set->setField("LAST_DATE", "NOW()");
			  $set->updateMenu();
			  $setquery=$set->query;

			  $set_menu->setField("AKSES_ADM_INTRANET_ID", $reqRowId);
			  $set_menu->setField("TABLE", $reqTable);
			  $set_menu->deleteMenuDetil();
			  $setquery.=";".$set_menu->query;
			  
			  for($i=0;$i<count($reqMenuId);$i++)
			  {
				  $set_menu = new UserGroup();
				  $set_menu->setField("AKSES_ADM_INTRANET_ID", $reqRowId);
				  $set_menu->setField("MENU_ID", $reqMenuId[$i]);
				  $set_menu->setField("AKSES", $reqCheck[$i]);
				  $set_menu->setField("TABLE", $reqTable);
				  $set_menu->setField("LAST_USER", $this->LOGIN_USER);
				  $set_menu->setField("USER_LOGIN_ID", $this->LOGIN_ID);
				  $set_menu->setField("USER_LOGIN_PEGAWAI_ID", ValToNullDB($this->LOGIN_PEGAWAI_ID));
				  $set_menu->setField("LAST_DATE", "NOW()");
				  $set_menu->insertMenuDetil();
				  $setquery.=";".$set_menu->query;
				  //echo $set_menu->query;exit;
				  //unset($set_menu);	  
			  }
			  // echo $setquery;exit();
			  $reqSimpan= 1;
		}

		if($reqSimpan == 1)
		{
			echo json_response(200, $reqRowId."-Data berhasil disimpan.");
		}
		else
		{
			echo json_response(400, "Data gagal disimpan");
		}
	}

}
?>