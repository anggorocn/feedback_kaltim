<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once("functions/image.func.php");
include_once("functions/string.func.php");
include_once("functions/date.func.php");

class Adminlogin extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		//kauth
		// $this->load->library('session');

		$CI =& get_instance();
		$configdata= $CI->config;
        $configvlxsessfolder= $configdata->config["vlxsessfolder"];

		$this->sessadmininfopesan= $this->session->userdata("sessadmininfopesan".$configvlxsessfolder);
		$this->adminuserid= $this->session->userdata("adminuserid".$configvlxsessfolder);
	}

	public function index()
	{
		$CI =& get_instance();
		$configdata= $CI->config;
        $configvlxsessfolder= $configdata->config["vlxsessfolder"];

		if(!empty($this->adminuserid))
		{
			redirect('admin');
		}

		$this->session->set_userdata('sessadmininfopesan'.$configvlxsessfolder, "");
		$data['pesan']="";
		$this->load->view('admin/login', $data);
	}

	function action()
	{
		$this->load->library("crfs_protect"); $csrf = new crfs_protect('_crfs_login');
		if (!$csrf->isTokenValid($_POST['_crfs_login']))
		{
		?>
			<script language="javascript">
				alert('<?=$respon?>');
				document.location.href = 'logout';
			</script>
		<?
			exit();
		}

		$CI =& get_instance();
		$configdata= $CI->config;
        $configvlxsessfolder= $configdata->config["vlxsessfolder"];
		
		$reqUser= $this->input->post("reqUser");
		$reqPasswd= $this->input->post("reqPasswd");
		$reqCaptcha= $this->input->post("reqCaptcha");
		
		if(!empty($reqUser) AND !empty($reqPasswd))
		{
			$respon = $this->kauth->cekuseradminlogin($reqUser,$reqPasswd);
			//echo $respon;exit;
			if($respon == "1")
			{
				redirect('admin');
			}
			else
			{
				$this->session->set_userdata('sessadmininfopesan'.$configvlxsessfolder, $respon);
				$this->logout();
			}
		}
		else
		{
			$this->session->set_userdata('sessadmininfopesan'.$configvlxsessfolder, "Masukkan username dan password.");
			$this->logout();
		}
	}

	public function logout()
	{
		$this->kauth->unsetcekuseradminlogin();
		// $this->session->sess_destroy();
		redirect ('adminlogin');
	}

	public function actionLoginAdmin()
	{
		$loginadminid = $this->input->get("loginadminid");
		// echo $loginadminid;exit;
		$CI =& get_instance();
		$configdata= $CI->config;
        $configvlxsessfolder= $configdata->config["vlxsessfolder"];
		$respon = $this->kauth->cekuserloginpersonal($loginadminid);
		// print_r($respon);exit;
		if($respon == "0")
		{
			$this->session->set_userdata('sessinfopesan'.$configvlxsessfolder, "Username dan password tidak sesuai.");
			redirect ('login');
		}
		else if($respon == "1")
		{
			redirect('app');
		}
		else
		{
			$this->session->set_userdata('sessinfopesan'.$configvlxsessfolder, $respon);
			redirect ('login');
		}
	}
}