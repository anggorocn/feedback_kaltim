<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/class-list-util.php");
include_once("functions/class-list-util-serverside.php");

class info_admin_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		//kauth

		$CI =& get_instance();
		$configdata= $CI->config;
        $configvlxsessfolder= $configdata->config["vlxsessfolder"];

  		$redirectlogin= "1";
        if(!empty($_SESSION["vuserpegawaimode".$configvlxsessfolder]) && !empty($this->session->userdata("adminuserid".$configvlxsessfolder)))
        {
        	$this->session->set_userdata("userpegawaimode".$configvlxsessfolder, $_SESSION["vuserpegawaimode".$configvlxsessfolder]);
        	$redirectlogin= "";
        }

		if(!empty($this->session->userdata("userpegawaiId".$configvlxsessfolder)) && !empty($redirectlogin))
		{
        	$redirectlogin= "";
        }
        
        // khusus admin
        if(!empty($this->session->userdata("adminuserid".$configvlxsessfolder)) && !empty($redirectlogin))
		{
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
		$this->userstatuspegId= $this->session->userdata("userstatuspegId".$configvlxsessfolder);
		$this->userpegawaimode= $this->session->userdata("userpegawaimode".$configvlxsessfolder);

		$this->adminuserid= $this->session->userdata("adminuserid".$configvlxsessfolder);
		$this->adminusernama= $this->session->userdata("adminusernama".$configvlxsessfolder);
		$this->adminuserloginnama= $this->session->userdata("adminuserloginnama".$configvlxsessfolder);
		$this->adminuseraksesappmenu= $this->session->userdata("adminuseraksesappmenu".$configvlxsessfolder);

		$this->userlevel= $this->session->userdata("userlevel".$configvlxsessfolder);
	}

	function jsonpegawai()
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
		if($reqSatuanKerja!=''){
			$statement.= " AND A.SATKER_ID = '".$reqSatuanKerja."'";
		}
		$statement.= " AND A.STATUS_JENIS = 1";

		// $sOrder = "";
		$set->selectbyparamspegawai(array(), $displaylength, $displaystart, $statement);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$reqRowId= $set->getField("FORMULA_PENILAIAN_ID");
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

	function jsonformulapenilaian()
	{
		ini_set('memory_limit', -1);
		ini_set('max_execution_time', -1);

		$this->load->model("base-data/FormulaPenilaian");

		$set= new FormulaPenilaian();

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

		// $sOrder = "";
		$set->selectbyparamsformulapenilaian(array(), $displaylength, $displaystart, $statement);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$reqRowId= $set->getField("FORMULA_PENILAIAN_ID");
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

	function formulapenilaiandata()
	{
		$this->load->model("base-data/FormulaPenilaian");

		$reqId= $this->input->post("reqId");
		$reqMode= $this->input->post("reqMode");
		$reqNama= $this->input->post("reqNama");
		
		$set= new FormulaPenilaian();
		$set->setField("NAMA", setQuote($reqNama));
		$set->setField("FORMULA_PENILAIAN_ID", $reqId);

		$reqSimpan="";
		if(empty($reqId))
		{
			if($set->insertformulapenilaian())
			{
				$reqId= $set->id;
				$reqSimpan = 1;
			}
		}
		else
		{
			if($set->updateformulapenilaian())
			{
				$reqSimpan = 1;
			}
		}

		if($reqSimpan == 1)
		{
			echo json_response(200, $reqId.'-Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
	}

	function formulapenilaianbobot()
	{
		$this->load->model("base-data/FormulaPenilaian");

		$reqId= $this->input->post("reqId");
		$reqIndikatorPenilaianId= $this->input->post("reqIndikatorPenilaianId");
		$reqSubIndikatorId= $this->input->post("reqSubIndikatorId");
		$reqNilai= $this->input->post("reqNilai");
		$reqdetilJenis= $this->input->post("infodetilJenis");

		$set= new FormulaPenilaian();
		$set->setField("FORMULA_PENILAIAN_ID", $reqId);
		$set->deleteformulapenilaianbobot();

		foreach($reqIndikatorPenilaianId as $key => $item)
		{
			$set= new FormulaPenilaian();
			$set->setField("FORMULA_PENILAIAN_ID", $reqId);
			$set->setField("INDIKATOR_PENILAIAN_ID", $reqIndikatorPenilaianId[$key]);
			$set->setField("SUB_INDIKATOR_ID", $reqSubIndikatorId[$key]);
			$set->setField("JENIS_SUBINDIKATOR", $reqdetilJenis[$key]);
			$set->setField("NILAI", ValToNullDB($reqNilai[$key]));
			$set->insertformulapenilaianbobot();
		}

		echo json_response(200, $reqId.'-Data berhasil disimpan');
	}

	function formulapenilaiannilai()
	{
		$this->load->model("base-data/FormulaPenilaian");

		$reqId= $this->input->post("reqId");
		$reqIndikatorPenilaianId= $this->input->post("reqIndikatorPenilaianId");
		$reqSubIndikatorId= $this->input->post("reqSubIndikatorId");
		$reqSubIndikatorDetilId= $this->input->post("reqSubIndikatorDetilId");
		$reqdetilJenis= $this->input->post("infodetilJenis");
		$reqNilai= $this->input->post("reqNilai");

		$set= new FormulaPenilaian();
		$set->setField("FORMULA_PENILAIAN_ID", $reqId);
		$set->deleteformulapenilaianvalue();

		foreach($reqIndikatorPenilaianId as $key => $item)
		{
			$set= new FormulaPenilaian();
			$set->setField("FORMULA_PENILAIAN_ID", $reqId);
			$set->setField("INDIKATOR_PENILAIAN_ID", $reqIndikatorPenilaianId[$key]);
			$set->setField("SUB_INDIKATOR_ID", $reqSubIndikatorId[$key]);
			$set->setField("SUB_INDIKATOR_DETIL_ID", $reqSubIndikatorDetilId[$key]);
			$set->setField("JENIS_SUBINDIKATOR", $reqdetilJenis[$key]);
			$set->setField("NILAI", ValToNullDB($reqNilai[$key]));
			$set->insertformulapenilaianvalue();
		}

		echo json_response(200, $reqId.'-Data berhasil disimpan');
	}

	function jsonformulapenilaianpegawai()
	{

		ini_set('memory_limit', -1);
		ini_set('max_execution_time', -1);
		
		$this->load->model("base-data/FormulaPenilaian");

		$reqId= $this->input->get("reqId");

		$set= new FormulaPenilaian();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault);exit;

		$displaystart= -1;
		$displaylength=-1;

		$arrinfodata= [];

		$statement.= " AND FP.FORMULA_PENILAIAN_ID = ".$reqId;

		// $sOrder = "";
		// echo "sasas";exit;
		$set->selectbyparamspegawai(array(), $displaylength, $displaystart, $statement);
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

	function formulapenilaianhitungulang()
	{
		$this->load->model("base-data/FormulaPenilaian");

		$reqId= $this->input->get("reqId");

		$set= new FormulaPenilaian();
		$set->setField("ID", $reqId);
		$set->ppetapegawai();
		echo "1";
	}

	function formulapenilaianhitungulangCAT()
	{
		$this->load->model("base-data/FormulaPenilaian");

		$reqId= $this->input->get("reqId");

		$set= new FormulaPenilaian();
		$set->setField("ID", $reqId);
		$set->ppetapegawaiCat();
		echo "1";
	}

	function formulapenilaianstandarninebox()
	{
		$this->load->model("base-data/FormulaPenilaian");

		$reqId= $this->input->post("reqId");
		$reqToleransiY= $this->input->post("reqToleransiY");
		$reqToleransiX= $this->input->post("reqToleransiX");
		$reqSkpX0= $this->input->post("reqSkpX0");
		$reqGmY0= $this->input->post("reqGmY0");
		$reqSkpX1= $this->input->post("reqSkpX1");
		$reqGmY1= $this->input->post("reqGmY1");
		$reqSkpX2= $this->input->post("reqSkpX2");
		$reqGmY2= $this->input->post("reqGmY2");

		$statement= " AND A.FORMULA_PENILAIAN_ID = ".$reqId;
		$set= new FormulaPenilaian();
		$set->selectbyparamsformulapenilaiannineboxstandart(array(), -1,-1, $statement);
		// echo $set->query;exit;
		$set->firstRow();
		$checkid= $set->getField("FORMULA_PENILAIAN_ID");

		$set= new FormulaPenilaian();
		$set->setField("FORMULA_PENILAIAN_ID", $reqId);
		$set->setField("TOLERANSI_Y", $reqToleransiY);
		$set->setField("TOLERANSI_X", $reqToleransiX);

		$set->setField("SKP_X0", ValToNullDB($reqSkpX0));
		$set->setField("GM_Y0", ValToNullDB($reqGmY0));
		$set->setField("SKP_X1", ValToNullDB($reqSkpX1));
		$set->setField("GM_Y1", ValToNullDB($reqGmY1));
		$set->setField("SKP_X2", ValToNullDB($reqSkpX2));
		$set->setField("GM_Y2", ValToNullDB($reqGmY2));

		$reqSimpan="";
		if(empty($checkid))
		{
			if($set->insertnineboxstandart())
			{
				$reqId= $set->id;
				$reqSimpan = 1;
			}
		}
		else
		{
			if($set->updatenineboxstandart())
			{
				$reqSimpan = 1;
			}
		}

		if($reqSimpan == 1)
		{
			echo json_response(200, $reqId.'-Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
	}

	function formulapenilaiangrafik()
	{
		$this->load->model("base-data/FormulaPenilaian");
		$this->load->model("base-data/InfoData");

		$m= $this->input->get("m");
		$pegawaiid= $this->input->get("pegawaiid");
		$reqId= $this->input->get("reqId");
		$reqFormulaPenilaianId= $this->input->get("reqFormulaPenilaianId");
		$reqTipePegawaiId= $this->input->get("reqTipePegawaiId");
		$reqPangkatId= $this->input->get("reqPangkatId");
		$reqEselonId= $this->input->get("reqEselonId");
		$reqRumpunId= $this->input->get("reqRumpunId");
		$reqSatuanKerja= $this->input->get("reqSatuanKerja");
		$reqSatuanKerjaEs2= $this->input->get("reqSatuanKerjaEs2");

		if($m == "potensikompetensi")
		{
			$set= new FormulaPenilaian();
			$statement = "";
			$statement.= " AND A.STATUS_JENIS = 1";


			/*if (!empty($reqId))
				$statement .= " AND A.SATKER_ID LIKE '".$reqId."%'";
			 
			if($reqPencarian == ""){}
			else
			{
				$statement.= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($reqPencarian)."%') ";
			}

			if($reqFormulaId == "")
				$statement.=" AND 1=2";
			else
				$statement .= " AND X.FORMULA_ID = '".$reqFormulaId."' ";*/

			// $statement .= " AND COALESCE(X.NILAI,0) > 0 AND COALESCE(Y.NILAI,0) > 0";
				
			if(!empty($pegawaiid))
				$statement.= " AND A.PEGAWAI_ID = ".$pegawaiid;

			if(!empty($reqTipePegawaiId) || !empty($reqEselonId))
			{
				if(!empty($reqTipePegawaiId) && !empty($reqEselonId))
				{
					$arrTipePegawaiId= explode(",", $reqTipePegawaiId);
					$arrEselonId= explode(",", $reqEselonId);
				    // print_r($arrTipePegawaiId);
				    // print_r($arrEselonId);
				    // exit();

				    $statementkhusus= "";
				    $statementdetil.= "
				    AND
				    (
				    ";
				    foreach($arrTipePegawaiId as $vtipepegawai)
				    {
				    	$infokondisitipe= "";
						foreach($arrEselonId as $veselonid)
						{
					        $veselonid= explode("-", $veselonid);
					        // print_r($veselonid);exit;
					        $infoeselonid= $veselonid[0];
					        $infoeselongroup= $veselonid[1];

					        if($infoeselongroup == "eselon")
					        {
					        	if($vtipepegawai == "1")
					        	{
									$statementeselon= "
									(
									A.TIPE_PEGAWAI_ID in(".$vtipepegawai.")
									";

									$statementeselon.= "AND A.LAST_ESELON_ID in(".$infoeselonid.")";

									$statementeselon.= "
									)
									";
						          	$statementkhusus= getconcatseparator($statementkhusus, $statementeselon, "OR");
								}
					        }
					        else
					        {
								if($vtipepegawai !== "1" && $vtipepegawai !== "2" )
								{
									if($infoeselongroup !== "eselon")
									{
										$infokondisitipe= "1";
										$statementeselon= "
										(
										A.TIPE_PEGAWAI_ID in(".$vtipepegawai.")
										";

										$statementeselon.= "AND A.LAST_JENJANG_ID in(".$infoeselonid.")";

										$statementeselon.= "
										)
										";

										$statementkhusus= getconcatseparator($statementkhusus, $statementeselon, "OR"); 
									}
								}
								elseif($vtipepegawai == "2" )
								{
									if($infoeselongroup !== "eselon")
									{
										$infokondisitipe= "1";
										$statementeselon= "
										(
										A.TIPE_PEGAWAI_ID in(".$vtipepegawai.")
										";

										// $statementeselon.= "AND A.LAST_JENJANG_ID in(".$infoeselonid.")";

										$statementeselon.= "
										)
										";

										$statementkhusus= getconcatseparator($statementkhusus, $statementeselon, "OR"); 
									}
								}
					        }
				      	}

				      	if(empty($infokondisitipe) && $vtipepegawai !== "1")
				      	{
				      		$statementeselon= "
				      		(
				      		A.TIPE_PEGAWAI_ID in(".$vtipepegawai.")
				      		";

				      		$statementeselon.= "
				      		)
				      		";
				      		$statementkhusus= getconcatseparator($statementkhusus, $statementeselon, "OR");
				      	}
				    }
				    // echo $statementkhusus;exit;
				    $statementdetil.= $statementkhusus.")";
				    // echo $statementdetil;exit;
				}
				else if(!empty($reqTipePegawaiId))
				{
					$statementdetil.= " AND A.TIPE_PEGAWAI_ID in(".$reqTipePegawaiId.")";
				}
				else if(!empty($reqEselonId))
				{
					$infoeselondataid= "";
					$arrEselonId= explode(",", $reqEselonId);
					// print_r($arrEselonId);exit;
					foreach($arrEselonId as $veselonid)
					{
				        $veselonid= explode("-", $veselonid);
				        // print_r($veselonid);exit;
				        $infoeselonid= $veselonid[0];
				        $infoeselongroup= $veselonid[1];

				        $infoeselondataid= getconcatseparator($infoeselondataid, $infoeselonid);
				    }
					$statementdetil.= " AND A.LAST_ESELON_ID in( ".$infoeselondataid.")";
				}
			}

			if(!empty($reqPangkatId))
			{
				$statement.= " AND A.LAST_PANGKAT_ID IN (".$reqPangkatId.")";
			}

			if(!empty($reqSatuanKerjaEs2))
			{
				$statement.= " AND A.SATKER_ID = '".$reqSatuanKerjaEs2."'";
			}

			if(!empty($reqSatuanKerja) && empty($reqSatuanKerjaEs2))
			{
				$statement.= " AND A.SATKER_ID LIKE '".$reqSatuanKerja."%'";
			}

			if(!empty($reqRumpunId))
			{
				$reqRumpunId= "'".str_replace(",", "','", $reqRumpunId)."'";
				// echo $reqRumpunId;exit;
				// $statement.= " AND A.LAST_JABATAN_ID IN (SELECT JABATAN_ID FROM simpeg.jabatan WHERE RUMPUN_ID IN (".$reqRumpunId."))";
				$statement.= " AND A.LAST_JABATAN_ID IN (SELECT JABATAN_ID FROM simpeg.jabatan WHERE RUMPUN_ID_PARENT IN (".$reqRumpunId."))";
			}

			$sOrder = " ORDER BY COALESCE(X.NILAI,0) DESC, COALESCE(Y.NILAI,0) DESC ";
			// $set->selectpotensikompetensigrafik(array(), -1, -1, $statement, $sOrder);
			// $set->selectpotensikompetensigrafik(array(), 1000, 0, $statement.$statementdetil, $sOrder, $reqFormulaPenilaianId);
			$set->selectpotensikompetensigrafik(array(), -1, -1, $statement.$statementdetil, $sOrder, $reqFormulaPenilaianId);
			// echo $set->query;exit;
			$i=0;
			while($set->nextRow())
			{
				$arrData[$i]= array("x" => (float)$set->getField("NILAI_X"), "y" => (float)$set->getField("NILAI_Y"), "myData" => $set->getField("NAMA")."<br/>Kinerja (".round($set->getField("NILAI_Y"),2).")"."<br/>Potensi (".round($set->getField("NILAI_X"),2).")");
				$i++;
			}

			$arr_json= $arrData;
		}
		else if($m == "potensikompetensisuksesi")
		{
			$reqFormulaPenilaianId= $reqJabatanId= $reqSatuanKerja= $reqSatuanKerjaEs2= $reqPangkatId= $reqTipePegawaiId= $reqEselonId= $reqKuadranId= $reqRumpunId= "";
			$statement= " AND 1=2";
			if(!empty($reqId))
			    $statement= " AND A.RENCANA_SUKSESI_ID = ".$reqId;
			$set= new InfoData();
			$set->selectbyparamsrencanasuksesidetil(array(), -1,-1, $statement);
			while($set->nextRow())
			{
			    $infokey= $set->getField("INFOKEY");
			    $infoval= $set->getField("INFOVAL");

			    if($infokey == "formulaid")
			    {
			        $reqFormulaPenilaianId= getconcatseparator($reqFormulaPenilaianId, $infoval);
			    }
			    else if($infokey == "jabatan")
			    {
			        $reqJabatanId= getconcatseparator($reqJabatanId, $infoval);
			    }
			    else if($infokey == "satuankerjaeselon1")
			    {
			        $reqSatuanKerja= getconcatseparator($reqSatuanKerja, $infoval);
			    }
			    else if($infokey == "satuankerjaeselon2")
			    {
			        $reqSatuanKerjaEs2= getconcatseparator($reqSatuanKerjaEs2, $infoval);
			    }
			    else if($infokey == "pangkat")
			    {
			        $reqPangkatId= getconcatseparator($reqPangkatId, $infoval);
			    }
			    else if($infokey == "tipepegawai")
			    {
			        $reqTipePegawaiId= getconcatseparator($reqTipePegawaiId, $infoval);
			    }
			    else if($infokey == "eselonid")
			    {
			        $reqEselonId= getconcatseparator($reqEselonId, $infoval);
			    }
			    else if($infokey == "kuadran")
			    {
			    	$reqKuadranId= getconcatseparator($reqKuadranId, $infoval);
			    }
			    else if($infokey == "rumpunid")
			    {
			        $reqRumpunId= getconcatseparator($reqRumpunId, $infoval);
			    }
			}

			if(empty($reqFormulaPenilaianId))
				$reqFormulaPenilaianId= -1;

			$statement= "";
			$statementdetil= "";
			$statement.= " AND A.STATUS_JENIS = 1";

			if(!empty($reqTipePegawaiId) || !empty($reqEselonId))
			{
				if(!empty($reqTipePegawaiId) && !empty($reqEselonId))
				{
					$arrTipePegawaiId= explode(",", $reqTipePegawaiId);
					$arrEselonId= explode(",", $reqEselonId);
				    // print_r($arrTipePegawaiId);
				    // print_r($arrEselonId);
				    // exit();

				    $statementkhusus= "";
				    $statementdetil.= "
				    AND
				    (
				    ";
				    foreach($arrTipePegawaiId as $vtipepegawai)
				    {
				    	$infokondisitipe= "";
						foreach($arrEselonId as $veselonid)
						{
					        $veselonid= explode("-", $veselonid);
					        // print_r($veselonid);exit;
					        $infoeselonid= $veselonid[0];
					        $infoeselongroup= $veselonid[1];

					        if($infoeselongroup == "eselon")
					        {
					        	if($vtipepegawai == "1")
					        	{
									$statementeselon= "
									(
									A.TIPE_PEGAWAI_ID in(".$vtipepegawai.")
									";

									$statementeselon.= "AND A.LAST_ESELON_ID in(".$infoeselonid.")";

									$statementeselon.= "
									)
									";
						          	$statementkhusus= getconcatseparator($statementkhusus, $statementeselon, "OR");
								}
					        }
					        else
					        {
								if($vtipepegawai !== "1" && $vtipepegawai !== "2")
								{
									if($infoeselongroup !== "eselon")
									{
										$infokondisitipe= "1";
										$statementeselon= "
										(
										A.TIPE_PEGAWAI_ID in(".$vtipepegawai.")
										";

										$statementeselon.= "AND A.LAST_JENJANG_ID in(".$infoeselonid.")";

										$statementeselon.= "
										)
										";

										$statementkhusus= getconcatseparator($statementkhusus, $statementeselon, "OR"); 
									}
								}
								elseif($vtipepegawai == "2" )
									{
										if($infoeselongroup !== "eselon")
										{
											$infokondisitipe= "1";
											$statementeselon= "
											(
											A.TIPE_PEGAWAI_ID in(".$vtipepegawai.")
											";

											// $statementeselon.= "AND A.LAST_JENJANG_ID in(".$infoeselonid.")";

											$statementeselon.= "
											)
											";

											$statementkhusus= getconcatseparator($statementkhusus, $statementeselon, "OR"); 
										}
									}
					        }
				      	}

				      	if(empty($infokondisitipe) && $vtipepegawai !== "1")
				      	{
				      		$statementeselon= "
				      		(
				      		A.TIPE_PEGAWAI_ID in(".$vtipepegawai.")
				      		";

				      		$statementeselon.= "
				      		)
				      		";
				      		$statementkhusus= getconcatseparator($statementkhusus, $statementeselon, "OR");
				      	}
				    }
				    // echo $statementkhusus;exit;
				    $statementdetil.= $statementkhusus.")";
				    // echo $statementdetil;exit;
				}
				else if(!empty($reqTipePegawaiId))
				{
					$statementdetil.= " AND A.TIPE_PEGAWAI_ID in(".$reqTipePegawaiId.")";
				}
				else if(!empty($reqEselonId))
				{
					$infoeselondataid= "";
					$arrEselonId= explode(",", $reqEselonId);
					// print_r($arrEselonId);exit;
					foreach($arrEselonId as $veselonid)
					{
				        $veselonid= explode("-", $veselonid);
				        // print_r($veselonid);exit;
				        $infoeselonid= $veselonid[0];
				        $infoeselongroup= $veselonid[1];

				        $infoeselondataid= getconcatseparator($infoeselondataid, $infoeselonid);
				    }
					$statementdetil.= " AND A.LAST_ESELON_ID in( ".$infoeselondataid.")";
				}
			}

			if(!empty($reqPangkatId))
			{
				$statementdetil.= " AND A.LAST_PANGKAT_ID IN (".$reqPangkatId.")";
			}
			
			if(!empty($reqSatuanKerjaEs2))
			{
				$statementdetil.= " AND A.SATKER_ID = '".$reqSatuanKerjaEs2."'";
			}

			if(!empty($reqSatuanKerja) && empty($reqSatuanKerjaEs2))
			{
				$statementdetil.= " AND A.SATKER_ID LIKE '".$reqSatuanKerja."%'";
			}

			if(!empty($reqRumpunId))
			{
				$reqRumpunId= "'".str_replace(",", "','", $reqRumpunId)."'";
				// echo $reqRumpunId;exit;
				// $statementdetil.= " AND A.LAST_JABATAN_ID IN (SELECT JABATAN_ID FROM simpeg.jabatan WHERE RUMPUN_ID IN (".$reqRumpunId."))";
				$statementdetil.= " AND A.LAST_JABATAN_ID IN (SELECT JABATAN_ID FROM simpeg.jabatan WHERE RUMPUN_ID_PARENT IN (".$reqRumpunId."))";
			}

			/*if(empty($reqKuadranId))
			{
				$statement.= " AND B.ORDER_KUADRAN IN (7,8,9)";
			}
			else
			{
				$statement.= " AND B.ORDER_KUADRAN IN (".$reqKuadranId.")";
			}*/

			if(!empty($pegawaiid))
				$statement.= " AND A.PEGAWAI_ID = ".$pegawaiid;

			$set= new FormulaPenilaian();
			$sOrder = " ORDER BY COALESCE(X.NILAI,0) DESC, COALESCE(Y.NILAI,0) DESC ";
			$set->selectpotensikompetensigrafik(array(), -1, -1, $statement.$statementdetil, $sOrder, $reqFormulaPenilaianId);
			// echo $set->query;exit;
			$i=0;
			while($set->nextRow())
			{
				$arrData[$i]= array("x" => (float)$set->getField("NILAI_X"), "y" => (float)$set->getField("NILAI_Y"), "myData" => $set->getField("NAMA")."<br/>Kinerja (".round($set->getField("NILAI_Y"),2).")"."<br/>Potensi (".round($set->getField("NILAI_X"),2).")");
				$i++;
			}

			$arr_json= $arrData;
		}

		echo json_encode($arr_json);
	}

	function formulapenilaiantable()
	{
		$this->load->model("base-data/FormulaPenilaian");
		$m= $this->input->get("m");
		$reqFormulaPenilaianId= $this->input->get("reqFormulaPenilaianId");
		$reqTipePegawaiId= $this->input->get("reqTipePegawaiId");
		$reqPangkatId= $this->input->get("reqPangkatId");
		$reqEselonId= $this->input->get("reqEselonId");
		$reqRumpunId= $this->input->get("reqRumpunId");
		$reqSatuanKerja= $this->input->get("reqSatuanKerja");
		$reqSatuanKerjaEs2= $this->input->get("reqSatuanKerjaEs2");

		if($m == "potensikompetensi")
		{
			$set= new FormulaPenilaian();
			$statement = "";
			$statementdetil = "";
			$statement.= " AND A.STATUS_JENIS = 1";

			if(!empty($reqTipePegawaiId) || !empty($reqEselonId))
			{
				if(!empty($reqTipePegawaiId) && !empty($reqEselonId))
				{
					$arrTipePegawaiId= explode(",", $reqTipePegawaiId);
					$arrEselonId= explode(",", $reqEselonId);
				    // print_r($arrTipePegawaiId);
				    // print_r($arrEselonId);
				    // exit();

				    $statementkhusus= "";
				    $statement.= "
				    AND
				    (
				    ";
				    foreach($arrTipePegawaiId as $vtipepegawai)
				    {
				    	$infokondisitipe= "";
						foreach($arrEselonId as $veselonid)
						{
					        $veselonid= explode("-", $veselonid);
					        // print_r($veselonid);exit;
					        $infoeselonid= $veselonid[0];
					        $infoeselongroup= $veselonid[1];

					        if($infoeselongroup == "eselon")
					        {
					        	if($vtipepegawai == "1")
					        	{
									$statementeselon= "
									(
									A.TIPE_PEGAWAI_ID in(".$vtipepegawai.")
									";

									$statementeselon.= "AND A.LAST_ESELON_ID in(".$infoeselonid.")";

									$statementeselon.= "
									)
									";
						          	$statementkhusus= getconcatseparator($statementkhusus, $statementeselon, "OR");
								}
					        }
					        else
					        {
								if($vtipepegawai !== "1" && $vtipepegawai !== "2" )
								{
									if($infoeselongroup !== "eselon")
									{
										$infokondisitipe= "1";
										$statementeselon= "
										(
										A.TIPE_PEGAWAI_ID in(".$vtipepegawai.")
										";

										$statementeselon.= "AND A.LAST_JENJANG_ID in(".$infoeselonid.")";

										$statementeselon.= "
										)
										";

										$statementkhusus= getconcatseparator($statementkhusus, $statementeselon, "OR"); 
									}
								}
								elseif($vtipepegawai == "2" )
								{
									if($infoeselongroup !== "eselon")
									{
										$infokondisitipe= "1";
										$statementeselon= "
										(
										A.TIPE_PEGAWAI_ID in(".$vtipepegawai.")
										";

										// $statementeselon.= "AND A.LAST_JENJANG_ID in(".$infoeselonid.")";

										$statementeselon.= "
										)
										";

										$statementkhusus= getconcatseparator($statementkhusus, $statementeselon, "OR"); 
									}
								}
					        }
				      	}

				      	if(empty($infokondisitipe) && $vtipepegawai !== "1")
				      	{
				      		$statementeselon= "
				      		(
				      		A.TIPE_PEGAWAI_ID in(".$vtipepegawai.")
				      		";

				      		$statementeselon.= "
				      		)
				      		";
				      		$statementkhusus= getconcatseparator($statementkhusus, $statementeselon, "OR");
				      	}
				    }
				    // echo $statementkhusus;exit;
				    $statement.= $statementkhusus.")";
				    // echo $statement;exit;
				}
				else if(!empty($reqTipePegawaiId))
				{
					$statement.= " AND A.TIPE_PEGAWAI_ID in(".$reqTipePegawaiId.")";
				}
				else if(!empty($reqEselonId))
				{
					$infoeselondataid= "";
					$arrEselonId= explode(",", $reqEselonId);
					// print_r($arrEselonId);exit;
					foreach($arrEselonId as $veselonid)
					{
				        $veselonid= explode("-", $veselonid);
				        // print_r($veselonid);exit;
				        $infoeselonid= $veselonid[0];
				        $infoeselongroup= $veselonid[1];

				        $infoeselondataid= getconcatseparator($infoeselondataid, $infoeselonid);
				    }
					$statement.= " AND A.LAST_ESELON_ID in( ".$infoeselondataid.")";
				}
			}

			if(!empty($reqPangkatId))
			{
				$statement.= " AND A.LAST_PANGKAT_ID IN (".$reqPangkatId.")";
			}

			if(!empty($reqSatuanKerjaEs2))
			{
				$statement.= " AND A.SATKER_ID = '".$reqSatuanKerjaEs2."'";
			}

			if(!empty($reqSatuanKerja) && empty($reqSatuanKerjaEs2))
			{
				$statement.= " AND A.SATKER_ID LIKE '".$reqSatuanKerja."%'";
			}

			if(!empty($reqRumpunId))
			{
				$reqRumpunId= "'".str_replace(",", "','", $reqRumpunId)."'";
				// echo $reqRumpunId;exit;
				// $statement.= " AND A.LAST_JABATAN_ID IN (SELECT JABATAN_ID FROM simpeg.jabatan WHERE RUMPUN_ID IN (".$reqRumpunId."))";
				$statement.= " AND A.LAST_JABATAN_ID IN (SELECT JABATAN_ID FROM simpeg.jabatan WHERE RUMPUN_ID_PARENT IN (".$reqRumpunId."))";
			}

			$sOrder = " ORDER BY ORDER_KUADRAN";
			$set->selectpotensikompetensitable(array(), -1, -1, $statement, $statementdetil, $sOrder, $reqFormulaPenilaianId);
				// echo $set->query;exit;
		}

		echo'
		<table class="table table-bordered table-hover table-checkable dataTable no-footer dtr-inline">
	        <thead>
	            <tr>
	                <th style="font-size: 10px;">KUADRAN</th>
	                <th style="font-size: 10px;">JUMLAH</th>
	                <th style="font-size: 10px;">KETERANGAN</th>
	            </tr>
	        </thead>
	        <tbody>
	    ';

	    while($set->nextRow())
	    {
	    echo'
		        <tr style="cursor:pointer" onclick="setkuadran('.$set->getField("ID_KUADRAN").')">
			        <td style="font-size: 12px;">'.$set->getField("KODE_KUADRAN").'</td>
			        <td style="font-size: 12px;">'.$set->getField("JUMLAH").' orang</td>
			        <td style="font-size: 12px;">'.$set->getField("NAMA_KUADRAN").'</td>
		        </tr>
		';
		}

		echo'
		    </tbody>
	    </table>
        ';

	}

	function jsonformulapenilaiankuadranpegawai()
	{

		ini_set('memory_limit', -1);
		ini_set('max_execution_time', -1);
		
		$this->load->model("base-data/FormulaPenilaian");

		$reqFormulaPenilaianId= $this->input->get("reqFormulaPenilaianId");
		$reqTipePegawaiId= $this->input->get("reqTipePegawaiId");
		$reqPangkatId= $this->input->get("reqPangkatId");
		$reqEselonId= $this->input->get("reqEselonId");
		$reqRumpunId= $this->input->get("reqRumpunId");
		$reqSatuanKerja= $this->input->get("reqSatuanKerja");
		$reqSatuanKerjaEs2= $this->input->get("reqSatuanKerjaEs2");
		$reqKuadranId= $this->input->get("reqKuadranId");

		$set= new FormulaPenilaian();

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

		// $statement.= " AND FP.FORMULA_PENILAIAN_ID = ".$reqId;

		$statement= "";
		$statementdetil= "";
		$statement.= " AND A.STATUS_JENIS = 1";

		if(!empty($reqTipePegawaiId) || !empty($reqEselonId))
		{
			if(!empty($reqTipePegawaiId) && !empty($reqEselonId))
			{
				$arrTipePegawaiId= explode(",", $reqTipePegawaiId);
				$arrEselonId= explode(",", $reqEselonId);
			    // print_r($arrTipePegawaiId);
			    // print_r($arrEselonId);
			    // exit();

			    $statementkhusus= "";
			    $statementdetil.= "
			    AND
			    (
			    ";
			    foreach($arrTipePegawaiId as $vtipepegawai)
			    {
			    	$infokondisitipe= "";
					foreach($arrEselonId as $veselonid)
					{
				        $veselonid= explode("-", $veselonid);
				        // print_r($veselonid);exit;
				        $infoeselonid= $veselonid[0];
				        $infoeselongroup= $veselonid[1];

				        if($infoeselongroup == "eselon")
				        {
				        	if($vtipepegawai == "1")
				        	{
								$statementeselon= "
								(
								A.TIPE_PEGAWAI_ID in(".$vtipepegawai.")
								";

								$statementeselon.= "AND A.LAST_ESELON_ID in(".$infoeselonid.")";

								$statementeselon.= "
								)
								";
					          	$statementkhusus= getconcatseparator($statementkhusus, $statementeselon, "OR");
							}
				        }
				        else
				        {
							if($vtipepegawai !== "1" && $vtipepegawai !== "2")
							{
								if($infoeselongroup !== "eselon")
								{
									$infokondisitipe= "1";
									$statementeselon= "
									(
									A.TIPE_PEGAWAI_ID in(".$vtipepegawai.")
									";

									$statementeselon.= "AND A.LAST_JENJANG_ID in(".$infoeselonid.")";

									$statementeselon.= "
									)
									";

									$statementkhusus= getconcatseparator($statementkhusus, $statementeselon, "OR"); 
								}
							}
							elseif($vtipepegawai == "2" )
								{
									if($infoeselongroup !== "eselon")
									{
										$infokondisitipe= "1";
										$statementeselon= "
										(
										A.TIPE_PEGAWAI_ID in(".$vtipepegawai.")
										";

										// $statementeselon.= "AND A.LAST_JENJANG_ID in(".$infoeselonid.")";

										$statementeselon.= "
										)
										";

										$statementkhusus= getconcatseparator($statementkhusus, $statementeselon, "OR"); 
									}
								}
				        }
			      	}

			      	if(empty($infokondisitipe) && $vtipepegawai !== "1")
			      	{
			      		$statementeselon= "
			      		(
			      		A.TIPE_PEGAWAI_ID in(".$vtipepegawai.")
			      		";

			      		$statementeselon.= "
			      		)
			      		";
			      		$statementkhusus= getconcatseparator($statementkhusus, $statementeselon, "OR");
			      	}
			    }
			    // echo $statementkhusus;exit;
			    $statementdetil.= $statementkhusus.")";
			    // echo $statementdetil;exit;
			}
			else if(!empty($reqTipePegawaiId))
			{
				$statementdetil.= " AND A.TIPE_PEGAWAI_ID in(".$reqTipePegawaiId.")";
			}
			else if(!empty($reqEselonId))
			{
				$infoeselondataid= "";
				$arrEselonId= explode(",", $reqEselonId);
				// print_r($arrEselonId);exit;
				foreach($arrEselonId as $veselonid)
				{
			        $veselonid= explode("-", $veselonid);
			        // print_r($veselonid);exit;
			        $infoeselonid= $veselonid[0];
			        $infoeselongroup= $veselonid[1];

			        $infoeselondataid= getconcatseparator($infoeselondataid, $infoeselonid);
			    }
				$statementdetil.= " AND A.LAST_ESELON_ID in( ".$infoeselondataid.")";
			}
		}

		if(!empty($reqPangkatId))
		{
			$statementdetil.= " AND A.LAST_PANGKAT_ID IN (".$reqPangkatId.")";
		}
		
		if(!empty($reqSatuanKerjaEs2))
		{
			$statementdetil.= " AND A.SATKER_ID = '".$reqSatuanKerjaEs2."'";
		}

		if(!empty($reqSatuanKerja) && empty($reqSatuanKerjaEs2))
		{
			$statementdetil.= " AND A.SATKER_ID LIKE '".$reqSatuanKerja."%'";
		}

		if(!empty($reqRumpunId))
		{
			$reqRumpunId= "'".str_replace(",", "','", $reqRumpunId)."'";
			// echo $reqRumpunId;exit;
			// $statementdetil.= " AND A.LAST_JABATAN_ID IN (SELECT JABATAN_ID FROM simpeg.jabatan WHERE RUMPUN_ID IN (".$reqRumpunId."))";
			$statementdetil.= " AND A.LAST_JABATAN_ID IN (SELECT JABATAN_ID FROM simpeg.jabatan WHERE RUMPUN_ID_PARENT IN (".$reqRumpunId."))";
		}

		if(!empty($reqKuadranId))
		{
			$statement.= " AND A.KUADRAN_PEGAWAI_ID = '".$reqKuadranId."'";
		}

		// $sOrder = " ORDER BY A.NILAI_X DESC, A.NILAI_Y DESC, B.ORDER_KUADRAN DESC";
		$sOrder = " ORDER BY ORDER_KUADRAN DESC, A.NILAI_X DESC, A.NILAI_Y DESC, B.ORDER_KUADRAN DESC";
		$set->selectbyparamskuadranpegawai(array(), $displaylength, $displaystart, $statement, $statementdetil, $sOrder, $reqFormulaPenilaianId);
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

				else if ($valkey == "ORDER_KUADRAN")
				{
					$row[$valkey]= "<span style='display:none'>".$set->getField('ORDER_KUADRAN')."-</span>".romanic_number($set->getField('ORDER_KUADRAN'));
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

	function AddMasterKampus()
	{
		$this->load->model("base-data/MasterKampus");

		$reqId= $this->input->post("reqId");
		$reqTingkatPendidikanS1= $this->input->post("reqTingkatPendidikanS1");
		$reqTingkatPendidikanS2= $this->input->post("reqTingkatPendidikanS2");
		$reqTingkatPendidikanS3= $this->input->post("reqTingkatPendidikanS3");
		// echo $reqTingkatPendidikanS3; exit; 
		$reqNama= $this->input->post("reqNama");
		
		$set= new MasterKampus();
		$set->setField("NAMA", setQuote($reqNama));
		$set->setField("S1", ValToNullDB($reqTingkatPendidikanS1));
		$set->setField("S2", ValToNullDB($reqTingkatPendidikanS2));
		$set->setField("S3", ValToNullDB($reqTingkatPendidikanS3));
		$set->setField("Kampus_id", $reqId);

		$reqSimpan="";
		if(empty($reqId))
		{
			if($set->insert())
			{
				$reqId= $set->id;
				$reqSimpan = 1;
			}
		}
		else
		{
			if($set->update())
			{
				$reqSimpan = 1;
			}
		}

		if($reqSimpan == 1)
		{
			echo json_response(200, $reqId.'-Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
	}

	function rencanasuksesidelete()
	{
		$this->load->model("base-data/InfoData");
		$reqId= $this->input->get("reqId");
		$set= new InfoData();
		$set->setField('RENCANA_SUKSESI_ID', $reqId);
		$reqSimpan="";
		if($set->rencanasuksesidelete())
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

	function rencanasuksesiadd()
	{
		$this->load->model("base-data/InfoData");

		// $vpost= $this->input->post();
		// print_r($vpost);exit;
		$reqId= $this->input->post("reqId");
		$reqNama= $this->input->post("reqNama");

		$set= new InfoData();
		$set->setField("NAMA", setQuote($reqNama));
		$set->setField("RENCANA_SUKSESI_ID", $reqId);

		$reqSimpan="";
		if(empty($reqId))
		{
			if($set->rencanasuksesiinsert())
			{
				$reqId= $set->id;
				$reqSimpan = 1;
			}
		}
		else
		{
			if($set->rencanasuksesiupdate())
			{
				$reqSimpan = 1;
			}
		}

		if($reqSimpan == 1)
		{
			$setdetil= new InfoData();
			$setdetil->setField("RENCANA_SUKSESI_ID", $reqId);
			$setdetil->rencanasuksesidetildelete();

			$reqData= $this->input->post("reqData");
			// print_r($reqData);exit;
			foreach ($reqData as $key => $value) {
				if(!empty($value))
				{
					// echo $key."\n";
					$infoarr= explode(",", $value);
					foreach ($infoarr as $keydetil => $valuedetil) {
						// echo $keydetil.";".$valuedetil."\n";
						$setdetil= new InfoData();
						$setdetil->setField("RENCANA_SUKSESI_ID", $reqId);
						$setdetil->setField("INFOKEY", $key);
						$setdetil->setField("INFOVAL", $valuedetil);
						$setdetil->rencanasuksesidetilinsert();
					}
				}
			}

			/*$reqJabatanId= $this->input->post("reqJabatanId");
			$reqSatuanKerja= $this->input->post("reqSatuanKerja");
			$reqSatuanKerjaEs2= $this->input->post("reqSatuanKerjaEs2");
			$reqPangkatId= $this->input->post("reqPangkatId");
			$reqTipePegawaiId= $this->input->post("reqTipePegawaiId");
			$reqEselonId= $this->input->post("reqEselonId");
			$reqRumpunId= $this->input->post("reqRumpunId");*/
			echo json_response(200, $reqId.'-Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
	}

	function jsonrencanasuksesidata()
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

		// $sOrder = "";
		$set->selectbyparamsrencanasuksesimonitoring(array(), $displaylength, $displaystart, $statement);
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

	function jsonrencanasuksesi()
	{
		$this->load->model("base-data/InfoData");

		$reqId= $this->input->get("reqId");

		$reqFormulaPenilaianId= $reqJabatanId= $reqSatuanKerja= $reqSatuanKerjaEs2= $reqPangkatId= $reqTipePegawaiId= $reqEselonId= $reqKuadranId= $reqRumpunId= "";
		$statement= " AND 1=2";
		if(!empty($reqId))
		    $statement= " AND A.RENCANA_SUKSESI_ID = ".$reqId;
		$set= new InfoData();
		$set->selectbyparamsrencanasuksesidetil(array(), -1,-1, $statement);
		while($set->nextRow())
		{
		    $infokey= $set->getField("INFOKEY");
		    $infoval= $set->getField("INFOVAL");

		    if($infokey == "formulaid")
		    {
		        $reqFormulaPenilaianId= getconcatseparator($reqFormulaPenilaianId, $infoval);
		    }
		    else if($infokey == "jabatan")
		    {
		        $reqJabatanId= getconcatseparator($reqJabatanId, $infoval);
		    }
		    else if($infokey == "satuankerjaeselon1")
		    {
		        $reqSatuanKerja= getconcatseparator($reqSatuanKerja, $infoval);
		    }
		    else if($infokey == "satuankerjaeselon2")
		    {
		        $reqSatuanKerjaEs2= getconcatseparator($reqSatuanKerjaEs2, $infoval);
		    }
		    else if($infokey == "pangkat")
		    {
		        $reqPangkatId= getconcatseparator($reqPangkatId, $infoval);
		    }
		    else if($infokey == "tipepegawai")
		    {
		        $reqTipePegawaiId= getconcatseparator($reqTipePegawaiId, $infoval);
		    }
		    else if($infokey == "eselonid")
		    {
		        $reqEselonId= getconcatseparator($reqEselonId, $infoval);
		    }
		    else if($infokey == "kuadran")
		    {
		    	$reqKuadranId= getconcatseparator($reqKuadranId, $infoval);
		    }
		    else if($infokey == "rumpunid")
		    {
		        $reqRumpunId= getconcatseparator($reqRumpunId, $infoval);
		    }
		}

		if(empty($reqFormulaPenilaianId))
			$reqFormulaPenilaianId= -1;

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
		$statementdetil= "";
		$statement.= " AND A.STATUS_JENIS = 1";

		if(!empty($reqTipePegawaiId) || !empty($reqEselonId))
		{
			if(!empty($reqTipePegawaiId) && !empty($reqEselonId))
			{
				$arrTipePegawaiId= explode(",", $reqTipePegawaiId);
				$arrEselonId= explode(",", $reqEselonId);
			    // print_r($arrTipePegawaiId);
			    // print_r($arrEselonId);
			    // exit();

			    $statementkhusus= "";
			    $statementdetil.= "
			    AND
			    (
			    ";
			    foreach($arrTipePegawaiId as $vtipepegawai)
			    {
			    	$infokondisitipe= "";
					foreach($arrEselonId as $veselonid)
					{
				        $veselonid= explode("-", $veselonid);
				        // print_r($veselonid);exit;
				        $infoeselonid= $veselonid[0];
				        $infoeselongroup= $veselonid[1];

				        if($infoeselongroup == "eselon")
				        {
				        	if($vtipepegawai == "1")
				        	{
								$statementeselon= "
								(
								A.TIPE_PEGAWAI_ID in(".$vtipepegawai.")
								";

								$statementeselon.= "AND A.LAST_ESELON_ID in(".$infoeselonid.")";

								$statementeselon.= "
								)
								";
					          	$statementkhusus= getconcatseparator($statementkhusus, $statementeselon, "OR");
							}
				        }
				        else
				        {
							if($vtipepegawai !== "1" && $vtipepegawai !== "2")
							{
								if($infoeselongroup !== "eselon")
								{
									$infokondisitipe= "1";
									$statementeselon= "
									(
									A.TIPE_PEGAWAI_ID in(".$vtipepegawai.")
									";

									$statementeselon.= "AND A.LAST_JENJANG_ID in(".$infoeselonid.")";

									$statementeselon.= "
									)
									";

									$statementkhusus= getconcatseparator($statementkhusus, $statementeselon, "OR"); 
								}
							}
							elseif($vtipepegawai == "2" )
								{
									if($infoeselongroup !== "eselon")
									{
										$infokondisitipe= "1";
										$statementeselon= "
										(
										A.TIPE_PEGAWAI_ID in(".$vtipepegawai.")
										";

										// $statementeselon.= "AND A.LAST_JENJANG_ID in(".$infoeselonid.")";

										$statementeselon.= "
										)
										";

										$statementkhusus= getconcatseparator($statementkhusus, $statementeselon, "OR"); 
									}
								}
				        }
			      	}

			      	if(empty($infokondisitipe) && $vtipepegawai !== "1")
			      	{
			      		$statementeselon= "
			      		(
			      		A.TIPE_PEGAWAI_ID in(".$vtipepegawai.")
			      		";

			      		$statementeselon.= "
			      		)
			      		";
			      		$statementkhusus= getconcatseparator($statementkhusus, $statementeselon, "OR");
			      	}
			    }
			    // echo $statementkhusus;exit;
			    $statementdetil.= $statementkhusus.")";
			    // echo $statementdetil;exit;
			}
			else if(!empty($reqTipePegawaiId))
			{
				$statementdetil.= " AND A.TIPE_PEGAWAI_ID in(".$reqTipePegawaiId.")";
			}
			else if(!empty($reqEselonId))
			{
				$infoeselondataid= "";
				$arrEselonId= explode(",", $reqEselonId);
				// print_r($arrEselonId);exit;
				foreach($arrEselonId as $veselonid)
				{
			        $veselonid= explode("-", $veselonid);
			        // print_r($veselonid);exit;
			        $infoeselonid= $veselonid[0];
			        $infoeselongroup= $veselonid[1];

			        $infoeselondataid= getconcatseparator($infoeselondataid, $infoeselonid);
			    }
				$statementdetil.= " AND A.LAST_ESELON_ID in( ".$infoeselondataid.")";
			}
		}

		if(!empty($reqPangkatId))
		{
			$statementdetil.= " AND A.LAST_PANGKAT_ID IN (".$reqPangkatId.")";
		}
		
		if(!empty($reqSatuanKerjaEs2))
		{
			$statementdetil.= " AND A.SATKER_ID = '".$reqSatuanKerjaEs2."'";
		}

		if(!empty($reqSatuanKerja) && empty($reqSatuanKerjaEs2))
		{
			$statementdetil.= " AND A.SATKER_ID LIKE '".$reqSatuanKerja."%'";
		}

		if(!empty($reqRumpunId))
		{
			$reqRumpunId= "'".str_replace(",", "','", $reqRumpunId)."'";
			// echo $reqRumpunId;exit;
			// $statementdetil.= " AND A.LAST_JABATAN_ID IN (SELECT JABATAN_ID FROM simpeg.jabatan WHERE RUMPUN_ID IN (".$reqRumpunId."))";
			$statementdetil.= " AND A.LAST_JABATAN_ID IN (SELECT JABATAN_ID FROM simpeg.jabatan WHERE RUMPUN_ID_PARENT IN (".$reqRumpunId."))";
		}

		if(empty($reqKuadranId))
		{
			$statement.= " AND B.ORDER_KUADRAN IN (7,8,9)";
		}
		else
		{
			$statement.= " AND B.ORDER_KUADRAN IN (".$reqKuadranId.")";
		}
		// echo $statement;exit;
		// echo $statementdetil;exit;

		// simpan data pegawai
		$set= new InfoData();
		$set->setField("RENCANA_SUKSESI_ID", $reqId);
		$set->rencanasuksesidetilpegawaiinsert($statement, $statementdetil, $reqFormulaPenilaianId);
		
		$statement= " AND RSP.RENCANA_SUKSESI_ID = ".$reqId;
		$set= new InfoData();
		$sOrder = " ORDER BY ORDER_KUADRAN DESC, RSP.NILAI_X DESC, RSP.NILAI_Y DESC, BK.ORDER_KUADRAN DESC";
		$set->selectbyparamsrencanasuksesipegawai(array(), $displaylength, $displaystart, $statement, $sOrder);
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

				else if ($valkey == "ORDER_KUADRAN")
				{
					$row[$valkey]= "<span style='display:none'>".$set->getField('ORDER_KUADRAN')."-</span>".romanic_number($set->getField('ORDER_KUADRAN'));
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

}
?>