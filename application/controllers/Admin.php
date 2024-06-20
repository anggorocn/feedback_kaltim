<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once("functions/image.func.php");
include_once("functions/string.func.php");

class Admin extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		//kauth
		$loginadminid = $this->input->post("loginadminid");
		$loginadminid = $this->input->get("reqAdminId");
		$loginadminid=1;
		// echo "xxx".$loginadminid;exit;
		if($loginadminid!=''){
			redirect('adminlogin/actionLoginAdmin?loginadminid='.$loginadminid);
		}

		session_start();

		$CI =& get_instance();
		$configdata= $CI->config;
        $configvlxsessfolder= $configdata->config["vlxsessfolder"];
		
		if(empty($this->session->userdata("adminuserid".$configvlxsessfolder)))
		{
			redirect('adminlogin');
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
	
	public function index()
	{

		// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
		$CI =& get_instance();
		$configdata= $CI->config;
        $configvlxsessfolder= $configdata->config["vlxsessfolder"];

		$pg = $this->uri->segment(3, "home");
		$reqId = $this->input->get("reqId");
				
		$view = array(
			'pg' => $pg,
			'linkBack' => $file."_detil"
		);	
		// print_r($pg);exit;
		$data = array(
			'content' => $this->load->view("admin/".$pg,$view,TRUE),
			'pg' => $pg
		);
		
		$this->load->view('admin/index', $data);
	}

	public function index_popup()
	{
		$CI =& get_instance();
		$configdata= $CI->config;
        $configvlxsessfolder= $configdata->config["vlxsessfolder"];

		$pg = $this->uri->segment(3, "home");
		$reqId = $this->input->get("reqId");
				
		$view = array(
			'pg' => $pg,
			'linkBack' => $file."_detil"
		);	
		// print_r($view);exit;
		$data = array(
			'breadcrumb' => $breadcrumb,
			'content' => $this->load->view("admin/".$pg,$view,TRUE),
			'pg' => $pg
		);
		// print_r($data);exit;
		
		$this->load->view('admin/index_popup', $data);
	}

	public function loadUrl()
	{		
		$reqFolder = $this->uri->segment(3, "");
		$reqFilename = $this->uri->segment(4, "");
		$reqParse1 = $this->uri->segment(5, "");
		$reqParse2 = $this->uri->segment(6, "");
		$reqParse3 = $this->uri->segment(7, "");
		$reqParse4 = $this->uri->segment(8, "");
		$reqParse5 = $this->uri->segment(9, "");
		$data = array(
			'reqParse1' => urldecode($reqParse1),
			'reqParse2' => urldecode($reqParse2),
			'reqParse3' => urldecode($reqParse3),
			'reqParse4' => urldecode($reqParse4),
			'reqParse5' => urldecode($reqParse5)
		);

		if($reqFolder == "admin")
			$this->session->set_userdata('currentUrl', $reqFilename);
		
		$this->load->view($reqFolder.'/'.$reqFilename, $data);
	}	

	public function setpegawai()
	{
		$reqId= $this->input->get("reqId");

		$CI =& get_instance();
		$configdata= $CI->config;
        $configvlxsessfolder= $configdata->config["vlxsessfolder"];

        $_SESSION["vuserpegawaimode".$configvlxsessfolder]= $reqId;
		
		$this->session->set_userdata("userpegawaimode".$configvlxsessfolder, $reqId);

		// echo $this->session->userdata("userpegawaimode".$configvlxsessfolder);exit;

		// $this->kauth->setadminpegawai($reqId);
		echo json_response(200, $reqId."-Data berhasil disimpan.");
	}

	public function unsetpegawai()
	{
		$this->kauth->unsetadminpegawai();
		echo json_response(200, "Data berhasil disimpan.");
	}
	
	public function logout()
	{
		$this->kauth->unsetcekuseradminlogin();
		// $this->session->sess_destroy();
		redirect ('adminlogin');
	}

	
}