<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/class-list-util.php");
include_once("functions/class-list-util-serverside.php");

class home_json extends CI_Controller {

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

	function grafik_gol_pns()
	{
		$this->load->model("base-data/Pegawai");
		$reqStatusPegawai= $this->input->post('reqStatusPegawai');
		$statement= " AND C.GOL_RUANG IS NOT NULL AND  STATUS_PEGAWAI =".$reqStatusPegawai;
		$set= new Pegawai();
		$arrgrafikgol= array();
		$set->selectByParamsGrafikGolPns(array(),-1,-1,$statement);
		// echo $set->query;exit;
		while($set->nextRow())
		{
			$arrdata= [];
			$arrdata["PANGKAT_ID"]= $set->getField("PANGKAT_ID");
			$arrdata["y"]= $set->getField("JUMLAH");
			$arrdata["name"]= $set->getField("GOL_RUANG");
			array_push($arrgrafikgol, $arrdata);
		}

		echo json_encode($arrgrafikgol, JSON_NUMERIC_CHECK);
	}

	function grafik_gol_pppk()
	{
		$this->load->model("base-data/Pegawai");
		$reqStatusPegawai= $this->input->post('reqStatusPegawai');
		$statement="";
		$set= new Pegawai();
		$arrgrafikgol= array();
		$set->selectByParamsGrafikGolPPPK(array(),-1,-1,$statement);
		// echo $set->query;exit;
		while($set->nextRow())
		{
			$arrdata= [];
			$arrdata["GOLONGAN_PPPK_ID"]= $set->getField("GOLONGAN_PPPK_ID");
			$arrdata["y"]= $set->getField("JUMLAH");
			$arrdata["name"]= $set->getField("GOLONGAN_PPPK");
			array_push($arrgrafikgol, $arrdata);
		}

		echo json_encode($arrgrafikgol, JSON_NUMERIC_CHECK);
	}

	function grafik_jenis_kelamin_pns()
	{
		$this->load->model("base-data/Pegawai");
		$reqStatusPegawai= $this->input->post('reqStatusPegawai');
		$statement="";
		$set= new Pegawai();
		$arrgrafikgol= array();
		$set->selectByParamsJenisKelaminPns(array(),-1,-1,$statement);
		// echo $set->query;exit;


		while($set->nextRow())
		{
			$arrdata= [];
			$arrdata["data"]= $set->getField("JUMLAH");
			array_push($arrgrafikgol, $arrdata);
		}
		unset($set);

		$set= new Pegawai();
		$arrgrafikpppk= array();
		$set->selectByParamsJenisKelaminPPPK(array(),-1,-1,$statement);
		// echo $set->query;exit;
		while($set->nextRow())
		{
			$arrdata= [];
			$arrdata["data"]= $set->getField("JUMLAH");
			array_push($arrgrafikpppk, $arrdata);
		}

		$arrData['pns'] = [];
		foreach ($arrgrafikgol as $key => $value){
				array_push($arrData['pns'], $value['data']);
		}

		$arrData['pppk'] = [];
		foreach ($arrgrafikpppk as $key => $value){
				array_push($arrData['pppk'], $value['data']);
		}


		echo json_encode($arrData, JSON_NUMERIC_CHECK);
	}

	function grafik_umur_pegawai()
	{
		$this->load->model("base-data/Pegawai");
		$reqStatusPegawai= $this->input->post('reqStatusPegawai');
		$statement="";
		$set= new Pegawai();
		$arrgrafikgol= array();
		$set->selectByParamsUmur(array(),-1,-1,$statement);
		// echo $set->query;exit;
		while($set->nextRow())
		{
			$arrdata= [];
			$arrdata["GOLONGAN_PPPK_ID"]= $set->getField("GOLONGAN_PPPK_ID");
			$arrdata["y"]= $set->getField("JUMLAH");
			// var_dump($set->getField("UMUR"));
			if ($set->getField("UMUR")== "55")
			{
				$arrdata["name"]= "> 55";
			}
			else
			{
				$arrdata["name"]= $set->getField("UMUR");
			}
			
			
			array_push($arrgrafikgol, $arrdata);
		}

		echo json_encode($arrgrafikgol, JSON_NUMERIC_CHECK);
	}

	function grafik_pendidikan()
	{
		$this->load->model("base-data/Pegawai");
		$reqStatusPegawai= $this->input->post('reqStatusPegawai');
		$statement="";
		$set= new Pegawai();
		$arrgrafikgol= array();
		$set->selectByParamsPendidikan(array(),-1,-1,$statement);
		// echo $set->query;exit;
		while($set->nextRow())
		{
			$arrdata= [];
			$arrdata["GOLONGAN_PPPK_ID"]= $set->getField("GOLONGAN_PPPK_ID");
			$arrdata["y"]= $set->getField("JUMLAH");		
			$arrdata["name"]= $set->getField("NAMA");
			array_push($arrgrafikgol, $arrdata);
		}

		echo json_encode($arrgrafikgol, JSON_NUMERIC_CHECK);
	}

	function grafik_tipe()
	{
		$this->load->model("base-data/Pegawai");
		$reqStatusPegawai= $this->input->post('reqStatusPegawai');
		$statement="";
		$set= new Pegawai();
		$arrgrafikgol= array();
		$set->selectByTipePegawai(array(),-1,-1,$statement);
		// echo $set->query;exit;
		while($set->nextRow())
		{
			$arrdata= [];
			$arrdata["y"]= $set->getField("JUMLAH");		
			$arrdata["name"]= $set->getField("NAMA");
			array_push($arrgrafikgol, $arrdata);
		}

		echo json_encode($arrgrafikgol, JSON_NUMERIC_CHECK);
	}

	function grafik_struktural()
	{
		$this->load->model("base-data/Pegawai");
		$reqStatusPegawai= $this->input->post('reqStatusPegawai');
		$statement="";
		$set= new Pegawai();
		$arrgrafikgol= array();
		$set->selectByParamsStatistikStruktural(array(),-1,-1,$statement);
		// echo $set->query;exit;
		while($set->nextRow())
		{
			$arrdata= [];
			$arrdata["y"]= $set->getField("JUMLAH");		
			$arrdata["name"]= $set->getField("NAMA");
			array_push($arrgrafikgol, $arrdata);
		}

		echo json_encode($arrgrafikgol, JSON_NUMERIC_CHECK);
	}


}
?>