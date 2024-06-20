<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/class-list-util.php");
include_once("functions/class-list-util-serverside.php");

class kenaikan_gaji_berkala_pegawai_json extends CI_Controller {

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
		$this->userlevel= $this->session->userdata("userlevel");
		$this->userSatkerId= $this->session->userdata("userSatkerId");
	}

	function json_pegawai()
	{
		$this->load->model("base-app/KenaikanGajiBerkala");

		$set= new KenaikanGajiBerkala();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}


		$setInfoTampil=$this->input->get("setInfoTampil");
		$reqKeterangan = $this->input->get("reqKeterangan");
		$reqId = $this->input->get("reqId");
		$reqBulan = $this->input->get("reqBulan");
		$reqTahun = $this->input->get("reqTahun");
		$reqStatusAdministrasi= $this->input->get("reqStatusAdministrasi");
		$reqRowId= $this->input->get("reqRowId");
		$reqSearch = $this->input->get("reqSearch");
		$reqType = $this->input->get("reqType");
		$reqMode= $this->input->get("reqMode");
		$reqKgb= $this->input->get("reqKgb");

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];
		$filter=array();

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
				$statement.= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_REQUEST['search']['value'])."%')";
			}
			
		}

		if($reqBulan == "")
		{
			if($reqId == "")
				$filter= array("SUBSTR(PERIODE,3,4)" => $reqTahun);
			else
				$filter= array("A.SATKER_ID" => $reqId, "SUBSTR(PERIODE,3,4)" => $reqTahun);
		}
		else
		{
			if($reqId == "")
				$filter= array("PERIODE" => $reqBulan.$reqTahun);
			else
				$filter= array("A.SATKER_ID" => $reqId, "PERIODE" => $reqBulan.$reqTahun);
		}


		if($reqStatusAdministrasi == ""){}
		elseif($reqStatusAdministrasi == "xx")
			$statement.= " AND A.STATUS_KGB IS NULL";
		else
			$statement.= " AND A.STATUS_KGB = '".$reqStatusAdministrasi."'";

		if($reqKgb=="admin"){}
		else if($reqKgb=="guru")
		{
			$statement.= " AND TIPE_PEGAWAI_ID = '2102'";
		}
		else
		{
			$statement.= " AND TIPE_PEGAWAI_ID <> '2102'";
		}
		// if($this->userlevel == "1"){}
		// elseif($this->userlevel == "5")
		// 	$statement.= " AND TIPE_PEGAWAI_ID = '2102'";
		// else
		// 	$statement.= " AND TIPE_PEGAWAI_ID <> '2102'";

		if($this->userSatkerId == "" && $reqId == "")
		{
			if($setInfoTampil == "1"){}
			else
			{
				$statement= " AND 1 = 2 ";
			}
		}

		$sOrder = " ";
		// print_r($this->userlevel);exit;
		$set->selectByParamsBaru($filter, $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$infocheckid= $set->getField("PEGAWAI_ID");
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
				{
					$row[$valkey]= "1";
				}
				else if ($valkey == "CHECK")
				{
					if($set->getField("STATUS_KGB") < 1 || empty($set->getField("STATUS_KGB")))
					{
						$row[$valkey] = $set->getField($aColumns[$i]);	
					}
					else
					{
						// $row[$valkey] = "<input type='checkbox' onclick='setKlikCheck()'  class='editor-active'  id='reqPilihCheck".$no_urut."' value='".$set->getField("PEGAWAI_ID")."-".$set->getField("STATUS_KGB")."-".$set->getField("SATKER_ID")."'>";
						
						$row[$valkey]= "<input type='checkbox' $checked onclick='setKlikCheck()' class='editor-active' id='reqPilihCheck".$infocheckid."' value='".$infocheckid."'>";
						
					}
				}
				else if ($valkey == "GAJI_POKOK_BARU" || $valkey == "GAJI_LAMA")
				{
					$row[$valkey]= currencyToPage($set->getField($valkey));
				}
				else if ($valkey == "ID_ROW")
				{
					$row[$valkey]= $set->getField("PEGAWAI_ID").'-'.$reqBulan.'-'.$reqTahun.'-'.$set->getField("SATKER_ID");
				}
				else if ($valkey == "TANGGAL_SK" || $valkey == "TMT_SK_BARU" || $valkey == "TMT_SK_LAMA")
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
		if ( isset($_REQUEST['search'])) {
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

	function json_detail()
	{
		$this->load->model("base-app/GajiRiwayat");

		$set= new GajiRiwayat();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}

		$reqPegawaiId = $this->input->get("reqPegawaiId");


		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];
		$filter=array();

		$statement= " AND A.PEGAWAI_ID=".$reqPegawaiId;


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


		$sOrder = " ";
		// print_r($filter);exit;
		$set->selectByParams($filter, $displaylength, $displaystart, $statement, $sOrder);
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
				else if ($valkey == "GAJI_POKOK" )
				{
					$row[$valkey]= currencyToPage($set->getField($valkey));
				}
				else if ($valkey == "MASA_KERJA")
				{
					$row[$valkey]= $set->getField("MASA_KERJA_TAHUN").'-'.$set->getField("MASA_KERJA_BULAN");
				}
				else if ($valkey == "TANGGAL_SK" || $valkey == "TMT_SK")
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
		if ( isset($_REQUEST['search'])) {
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

	function jsonkgbadd()
	{
		$this->load->model("base-app/GajiRiwayat");
		$this->load->model("base-data/PejabatPenetap");
		$this->load->model("base-app/Pegawai");
		$this->load->model("base-app/KenaikanGajiBerkala");

		$reqMode 			= $this->input->post("reqMode");
		$reqPegawaiId 		= $this->input->post('reqPegawaiId');
		$reqRowId			= $this->input->post("reqRowId");
		$reqDataId			= $this->input->post("reqDataId");

		$reqGAJI_RIWAYAT_ID = $this->input->post("reqGAJI_RIWAYAT_ID");
		$reqMode = $this->input->post("reqMode"); 
		$reqPegawaiId = $this->input->post("reqPegawaiId"); 
		$reqMode	= $this->input->post("reqMode");

		$reqBulan= $this->input->post("reqBulan");
		$reqTahun= $this->input->post("reqTahun");
		$reqSatkerId= $this->input->post("reqSatkerId");

		$reqNoSK 		= $this->input->post("reqNoSK");
		$reqTglSK 		= $this->input->post("reqTglSK");
		$reqGolRuang 	= $this->input->post("reqGolRuang");
		$reqTMTSK		= $this->input->post("reqTMTSK");
		$reqPejabatPenetapId	= $this->input->post("reqPejabatPenetapId");
		$reqPejabatPenetap= $this->input->post("reqPejabatPenetap");
		$reqTh			= $this->input->post("reqTh");
		$reqBl			= $this->input->post("reqBl");
		$reqGajiPokok	= $this->input->post("reqGajiPokok");
		$reqJenis		= $this->input->post("reqJenis");
		$reqPegawaiId	= $this->input->post("reqPegawaiId");

		$reqPeriode= "01".$reqBulan."".$reqTahun;
		// print_r($reqPeriode);exit;
		
		$gaji = new GajiRiwayat();
		$gaji->setField('GAJI_RIWAYAT_ID', $reqGAJI_RIWAYAT_ID);
		$gaji->setField('NO_SK', $reqNoSK);
		$gaji->setField('PANGKAT_ID', $reqGolRuang);
		$gaji->setField('TANGGAL_SK', dateToDBCheck($reqTglSK));
		$gaji->setField('GAJI_POKOK', ValToNullDB(dotToNo($reqGajiPokok)));
		$gaji->setField('MASA_KERJA_TAHUN', $reqTh);
		$gaji->setField('MASA_KERJA_BULAN', $reqBl);


		$reqSimpan= "";
	
		if($reqPjPenetapNama == "")
		{
			$statement= " AND UPPER(JABATAN) IS NULL";
		}
		else
		{
			$statement= " AND UPPER(JABATAN)='".strtoupper($reqPjPenetapNama)."'";
		}

		$set=new PejabatPenetap();
		$set->selectByParams(array(),-1,-1,$statement);
		$set->firstRow();
		// echo $set->query;exit;
		$tempPejabatPenetapId= $set->getField("PEJABAT_PENETAP_ID");
		$tempPejabatPenetapNama= $set->getField("JABATAN");

		if($reqPejabatPenetapId == "")
		{
			$set_pejabat=new PejabatPenetap();
			$set_pejabat->setField('JABATAN', strtoupper($reqPejabatPenetap));
			$set_pejabat->setField("LAST_CREATE_USER", $userLogin->idUser);
			$set_pejabat->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
			$set_pejabat->setField("LAST_CREATE_SATKER", $this->userSatkerId);
			$set_pejabat->insert();
			$reqPejabatPenetapId=$set_pejabat->id;
			unset($set_pejabat);
		}
		$gaji->setField('JENIS_KENAIKAN', $reqJenis);
		$gaji->setField('PEJABAT_PENETAP_ID', $reqPejabatPenetapId);
		$gaji->setField('PEJABAT_PENETAP', $reqPejabatPenetap);
		$gaji->setField('PEGAWAI_ID', $reqPegawaiId);
		$gaji->setField('TMT_SK', dateToDBCheck($reqTMTSK));
		$gaji->setField("LAST_UPDATE_USER", $userLogin->idUser);
		$gaji->setField("LAST_UPDATE_DATE", OCI_SYSDATE);	
		$gaji->setField("LAST_UPDATE_SATKER", $this->userSatkerId);
		$gaji->setField('BULAN_DIBAYAR', dateToDBCheck($reqTglSK));
		$gaji->setField('SUDAH_DIBAYAR', ValToNullDB($req));
		$gaji->setField('POTONGAN_PANGKAT', ValToNullDB($req));

		if($gaji->update())
		{
			$reqSimpan =1;
		}

		$statement_gaji= " AND PEGAWAI_ID = ".$reqPegawaiId." AND TO_CHAR(TMT_SK, 'DDMMYYYY') = '".$reqPeriode."'";
		$set_gaji_riwayat= new GajiRiwayat();
		$set_gaji_riwayat->selectByParams(array(), -1,-1, $statement_gaji);
		// echo $set_gaji_riwayat->query;exit;
		$set_gaji_riwayat->firstRow();
		$tempGajiRiwayatId= $set_gaji_riwayat->getField("GAJI_RIWAYAT_ID");
		unset($set_gaji_riwayat);

		
		if($reqSimpan == 1 )
		{
			echo json_response(200, 'Data berhasil disimpan');
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
				
	}

	function delete()
	{
		$this->load->model("base-app/GajiRiwayat");
		$this->load->model("base-app/KenaikanGajiBerkala");
		$reqGAJI_RIWAYAT_ID= $this->input->get("reqGAJI_RIWAYAT_ID");
		$gaji= new GajiRiwayat();
		$gaji->setField('GAJI_RIWAYAT_ID', $reqGAJI_RIWAYAT_ID);
		$reqSimpan="";
		if($gaji->delete())
		{
			$tempPeriode= $reqBulan."".$reqTahun;
			$set_generate= new KenaikanGajiBerkala();
			$set_generate->setField("PEGAWAI_ID", $reqPegawaiId);
			$set_generate->setField("PERIODE", $tempPeriode);
			$set_generate->updateNomorGenerateNull();
			unset($set_generate);
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

	function proses_kgb()
	{
		$this->load->model("base-app/GajiRiwayat");
		$this->load->model("base-data/PejabatPenetap");
		$this->load->model("base-app/KenaikanGajiBerkala");
		$this->load->model("base-app/Pegawai");

		$setInfoTampil=$this->input->post("setInfoTampil");
		$reqKeterangan = $this->input->post("reqKeterangan");
		$reqId = $this->input->post("reqId");
		$reqBulan =$this->input->post("reqBulan");
		$reqTahun =$this->input->post("reqTahun");
		$reqStatusAdministrasi=$this->input->post("reqStatusAdministrasi");
		$reqRowId=$this->input->post("reqRowId");
		$reqSearch =$this->input->post("reqSearch");
		$reqType =$this->input->post("reqType");
		$reqMode=$this->input->post("reqMode");

		$kgb = new KenaikanGajiBerkala();


		$reqPeriode= "01".$reqBulan."".$reqTahun;

		if($this->userSatkerId == ""){}
		else // kondisi login sebagai SKPD
		{
			if($reqId == "")
				$reqId = $this->userSatkerId;
		}

		
		if($reqMode == "proses")
		{
			if($reqBulan == "")
			{
				$arrBulan= setBulanLoop();
				for($i=0; $i < count($arrBulan); $i++)
				{
					$kgb->setField("PERIODE", $reqBulan.$reqTahun);	
					$kgb->setField("SATKERID", $reqId);
					
					$tempStatusProses= "";
					if($this->userSatkerId == "" && $reqId == "")
					{
						if($setInfoTampil == "1")
						{
							$tempStatusProses= "1";
						}
					}
					else
					{
						$tempStatusProses= "1";
					}
					
					if($tempStatusProses == "1")
					{
						if($this->userlevel == "5")
							$kgb->callKGBPendidikan();
						else
							$kgb->callKGB();
					}
				}
			}
			else
			{
				$kgb->setField("PERIODE", $reqBulan.$reqTahun);	
				$kgb->setField("SATKERID", $reqId);
				
				$tempStatusProses= "";
				if($this->userSatkerId == "" && $reqId == "")
				{
					if($setInfoTampil == "1")
					{
						$tempStatusProses= "1";
					}
				}
				else
				{
					$tempStatusProses= "1";
				}
				
				if($tempStatusProses == "1")
				{
					if($this->userlevel == "5")
						$kgb->callKGBPendidikan();
					else
						$kgb->callKGB();
				}
			}
			
		}
		elseif($reqMode == "usulan")
		{
			$statementProses= " AND Y.PERIODE ='".$reqBulan.$reqTahun."' AND Y.STATUS_KGB IS NULL AND Y.SATKER_ID LIKE '".$reqId."%' ";
			
			if($reqRowId == ""){}
			else
				$statementProses.= " AND Y.PEGAWAI_ID = ".$reqRowId;
			
			$kgb->setField("STATUS_KGB", "1");
			$kgb->updateStatusKgb($statementProses);
			//echo $kgb->query;exit;
		}
		elseif($reqMode == "proses_usulan")
		{

			$statementProses= " AND Y.PERIODE ='".$reqBulan.$reqTahun."' AND ( Y.STATUS_KGB = '1' OR Y.STATUS_KGB IS NULL) AND Y.SATKER_ID LIKE '".$reqId."%' ";
			
			if($reqRowId == ""){}
			else
				$statementProses.= " AND Y.PEGAWAI_ID = ".$reqRowId;
			
			// set tanggal proses	
			$kgb->updateTanggalSkProsesKgb($statementProses);

			$kgb->setField("STATUS_KGB", "2");
			$kgb->updateStatusKgb($statementProses);
		}
		elseif($reqMode == "proses_tidak")
		{
			$statementProses= " AND Y.PERIODE ='".$reqBulan.$reqTahun."' AND Y.SATKER_ID LIKE '".$reqId."%' ";
			
			if($reqRowId == ""){}
			else
				$statementProses.= " AND Y.PEGAWAI_ID = ".$reqRowId;
				
			$kgb->setField("STATUS_KGB", "4");
			$kgb->updateStatusKgb($statementProses);
		}

		elseif($reqMode == "proses_selesai")
		{
			$statementProses= " AND Y.PERIODE ='".$reqBulan.$reqTahun."' AND Y.STATUS_KGB = '2' AND Y.SATKER_ID LIKE '".$reqId."%' ";
			
			if($reqRowId == ""){}
			else
			{
				$statement_gaji= " AND PEGAWAI_ID = ".$reqRowId." AND TO_CHAR(TMT_SK, 'DDMMYYYY') = '01".$reqBulan.$reqTahun."'";
				$set_gaji_riwayat= new GajiRiwayat();
				$set_gaji_riwayat->selectByParams(array(), -1,-1, $statement_gaji);
				//echo $set_gaji_riwayat->query;exit;
				$set_gaji_riwayat->firstRow();
				$tempGajiRiwayatId= $set_gaji_riwayat->getField("GAJI_RIWAYAT_ID");
				unset($set_gaji_riwayat);
			
				$set_kgb_simpan= new KenaikanGajiBerkala();
		        $set_kgb_simpan->selectByParams(array(),-1,-1," AND A.PEGAWAI_ID = ".$reqRowId." AND PERIODE = '".$reqBulan.$reqTahun."' ".$statement);
		        $set_kgb_simpan->firstRow();
				//echo $set_kgb_simpan->query;exit;
		        $tempStatusKgb= $set_kgb_simpan->getField("STATUS_KGB");
		        $tempNoSK= $set_kgb_simpan->getField('NO_SK');
		        $tempNomorGenerate= $set_kgb_simpan->getField("NOMOR_GENERATE");

		        // tambahan tgl sk
		        $tempTanggalSkProses= dateToPageCheck($set_kgb_simpan->getField("TANGGAL_SK_PROSES"));
		    	
				if($tempGajiRiwayatId == "")
				{
					$set_pimpinan= new Pegawai();
					$statement_pimpinan=" AND SUBSTR(A.SATKER_ID, 1, 2) = '24'";
					$set_pimpinan->selectByParamsMonitoring2(array(),-1,-1,$statement_pimpinan, " ORDER BY C.ESELON_ID ASC, B.PANGKAT_ID DESC,  B.TMT_PANGKAT ASC ");
					$set_pimpinan->firstRow();
					$tempKepala= $set_pimpinan->getField("JABATAN");
					unset($set_pimpinan);
					
					$tempPjPenetapNama= $tempKepala;
					$tempGolRuang= $set_kgb_simpan->getField('PANGKAT_ID');
					$tempTglSK = dateToPageCheck($set_kgb_simpan->getField('TANGGAL_SK'));
					$tempGajiPokok= $set_kgb_simpan->getField('GAJI_BARU');
					$arrMasaKerja= explode(' - ',$set_kgb_simpan->getField('MASA_KERJA'));
					$tempTh= $arrMasaKerja[0];
					$tempBl= $arrMasaKerja[1];
					$tempTMTSK= dateToPageCheck($set_kgb_simpan->getField('TMT_BARU'));
					$tempJenis= 2;
					$tempPegawaiId= $set_kgb_simpan->getField('PEGAWAI_ID');
					

					if($tempTanggalSkProses == "")
						$tempTglSK= date("d-m-Y");
					else
						$tempTglSK= $tempTanggalSkProses;

					$gaji= new GajiRiwayat();
					$gaji->setField('NO_SK', $tempNoSK);
					$gaji->setField('PANGKAT_ID', $tempGolRuang);
					$gaji->setField('TANGGAL_SK', dateToDBCheck($tempTglSK));
					$gaji->setField('GAJI_POKOK', $tempGajiPokok);
					$gaji->setField('MASA_KERJA_TAHUN', $tempTh);
					$gaji->setField('MASA_KERJA_BULAN', $tempBl);
					
					if($tempKepala == "")
						$statement= " AND UPPER(JABATAN) IS NULL";
					else
						$statement= " AND UPPER(JABATAN)='".strtoupper($tempKepala)."'";
					
					$statement= " AND UPPER(JABATAN)='BUPATI LAMONGAN'";
					$set=new PejabatPenetap();
					$set->selectByParams(array(),-1,-1,$statement);
					$set->firstRow();
					//echo $set->query;
					$tempPejabatPenetapId= $set->getField("PEJABAT_PENETAP_ID");
					$tempPejabatPenetapNama= $set->getField("JABATAN");
					
					if($tempPejabatPenetapId == "")
					{
						$set=new PejabatPenetap();
						$set->setField('JABATAN', strtoupper($reqPjPenetapNama));	
						$set->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set->setField("LAST_CREATE_SATKER", $this->userSatkerId);
						$set->insert();
						$tempPejabatPenetapNama=$reqPjPenetapNama;
						$tempPejabatPenetapId=$set->id;
						unset($set);
					}
					
					$gaji->setField('JENIS_KENAIKAN', $tempJenis);
					$gaji->setField('PEJABAT_PENETAP_ID', $tempPejabatPenetapId);
					$gaji->setField('PEJABAT_PENETAP', $tempPejabatPenetapNama);
					$gaji->setField('PEGAWAI_ID', $reqRowId);
					$gaji->setField('TMT_SK', dateToDBCheck($tempTMTSK));
					$gaji->setField("LAST_CREATE_USER", $userLogin->idUser);
					$gaji->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$gaji->setField("LAST_CREATE_SATKER", $this->userSatkerId);
					
					$gaji->setField('SUDAH_DIBAYAR', ValToNullDB($req));
					$gaji->setField('POTONGAN_PANGKAT', ValToNullDB($req));
					$gaji->setField('BULAN_DIBAYAR', dateToDBCheck($tempTglSK));
					
					if($gaji->insert())	
					{
					}
					//echo $gaji->query;exit;
				}
				
				$statementProses.= " AND Y.PEGAWAI_ID = ".$reqRowId;
			}
				
			$kgb->setField("STATUS_KGB", "3");
			$kgb->updateStatusKgb($statementProses);
		}
		elseif($reqMode == "proses_batal")
		{
			$tempPeriode= $reqBulan."".$reqTahun;
			$statement_gaji= " AND PEGAWAI_ID = ".$reqRowId." AND TO_CHAR(TMT_SK, 'DDMMYYYY') = '01".$tempPeriode."'";
			$set_gaji_riwayat= new GajiRiwayat();
			$set_gaji_riwayat->selectByParams(array(), -1,-1, $statement_gaji);
			//echo $set_gaji_riwayat->query;exit;
			$set_gaji_riwayat->firstRow();
			$tempGajiRiwayatId= $set_gaji_riwayat->getField("GAJI_RIWAYAT_ID");
			
			
			$set_generate= new KenaikanGajiBerkala();
			$set_generate->setField("PEGAWAI_ID", $reqRowId);
			$set_generate->setField("PERIODE", $tempPeriode);
			if($set_generate->deletePegawaiPeriode())
			{
				$kgb->setField("PERIODE", $tempPeriode);
				$kgb->setField("SATKERID", $reqId);
			
				if($this->userlevel == "5")
					$kgb->callKGBPendidikan();
				else
					$kgb->callKGB();
					
				$set_gaji_riwayat->setField('GAJI_RIWAYAT_ID', $tempGajiRiwayatId);
				if($set_gaji_riwayat->delete())
				{
					
				}
				unset($set_gaji_riwayat);
			}
			unset($set_generate);
		}
				
	}


}
?>