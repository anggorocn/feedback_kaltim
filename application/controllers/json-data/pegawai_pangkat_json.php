<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/class-list-util.php");
include_once("functions/class-list-util-serverside.php");

class pegawai_pangkat_json extends CI_Controller {

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


	function riwayatpangkatadd()
	{
		$this->load->model("base-data/RiwayatPangkat");

		$reqMode= $this->input->post("reqMode");
		$reqGolRuang= $this->input->post("reqGolRuang");
		$reqTMTGol= $this->input->post("reqTMTGol");
		$reqTh= $this->input->post("reqTh");
		$reqBl= $this->input->post("reqBl");
		$reqRowId= $this->input->post("reqRowId");
		
		$userpegawaimode= $this->userpegawaimode;
		$adminuserid= $this->adminuserid;

		if(!empty($userpegawaimode) && !empty($adminuserid))
		    $reqPegawaiId= $userpegawaimode;
		else
		    $reqPegawaiId= $this->pegawaiId;


		$set= new RiwayatPangkat();
		$set->setField('RIWAYAT_PANGKAT_ID', $reqRowId);
		$set->setField('PEGAWAI_ID', $reqPegawaiId);
		$set->setField('PANGKAT_ID', $reqGolRuang);
		$set->setField('TMT_PANGKAT', dateToDBCheck($reqTMTGol));
		$set->setField('MK_TAHUN', ValToNullDB($reqTh));
		$set->setField('MK_BULAN', ValToNullDB($reqBl));

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
		}

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

	function delete()
	{
		$this->load->model("base-data/RiwayatPangkat");

		$reqId= $this->input->get("reqId");

		$set= new RiwayatPangkat();
		$set->setField("RIWAYAT_PANGKAT_ID", $reqId);
		$set->delete();

		echo json_response(200, $reqId.'-Data berhasil disimpan');
	}
}
?>