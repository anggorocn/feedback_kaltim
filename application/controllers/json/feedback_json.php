<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/class-list-util.php");
include_once("functions/class-list-util-serverside.php");

class feedback_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		//kauth
		$loginadminid = $this->input->post("loginadminid");
		$loginadminid = $this->input->get("reqAdminId");
		// $loginadminid=1;
		if($loginadminid!=''){
			redirect('adminlogin/actionLoginAdmin?loginadminid='.$loginadminid);
		}

		session_start();

		$CI =& get_instance();
		$configdata= $CI->config;
        $configvlxsessfolder= $configdata->config["vlxsessfolder"];
		
		if(empty($this->session->userdata("adminuserid".$configvlxsessfolder)))
		{
			redirect('https://simace.kaltimbkd.info/assesment/main/login.php');
			// redirect('adminlogin');
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

	function add()
	{
		// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
		$this->load->model('base-data/Feedback');
		
		$set= new Feedback();
		
		$reqAsesorId= $this->adminuserid;
		$reqPegawaiId= $this->input->post("reqPegawaiId");
		$reqJadwalTesId= $this->input->post("reqJadwalTesId");
		$reqFeedbackId= $this->input->post("reqFeedbackId");

		$reqKeterangan= $this->input->post("reqKeterangan");
		$reqHarapan= $this->input->post("reqHarapan");
		$reqHarapanInstansi	= $this->input->post("reqHarapanInstansi");
		$reqSaranPengembangan 	= $this->input->post("reqSaranPengembangan");
		$reqQuotes 	= $this->input->post("reqQuotes");

		$reqPengembanganDiriUrut 	= $this->input->post("reqPengembanganDiriUrut");
		$reqPengembanganDiri 	= $this->input->post("reqPengembanganDiri");
		
		$set->setField('feedback_id', $reqFeedbackId);
		$set->setField('asesor_id', $reqAsesorId);
		$set->setField('pegawai_id', $reqPegawaiId);
		$set->setField('jadwal_tes_id', $reqJadwalTesId);
		$set->setField('keterangan', $reqKeterangan);
		$set->setField('harapan', $reqHarapan);
		$set->setField('saran_pengembangan', $reqSaranPengembangan);
		$set->setField('quotes', $reqQuotes);
		$set->setField('harapan_instansi', $reqHarapanInstansi);

		$reqSimpan= "";
		if($reqFeedbackId == "")
		{
			if($set->insert())
			{
				$reqFeedbackId= $set->id;
				$reqSimpan=1;
			}
		}
		else
		{
			if($set->update())
			{
				$reqSimpan= 1;
			}
		}

		if($reqSimpan==1){
			$set->delete();

			for($i=0;$i<count($reqPengembanganDiri);$i++){
				$set->setField('keterangan', $reqPengembanganDiri[$i]);
				$set->setField('URUT', $reqPengembanganDiriUrut[$i]);
				$set->setField('feedback_id', $reqFeedbackId);
				$set->insertDetil();
			}
			
			$reqSimpan=2;

		}
		
		if($reqSimpan == 2)
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