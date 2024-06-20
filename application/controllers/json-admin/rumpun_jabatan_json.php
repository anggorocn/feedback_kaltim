<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/class-list-util.php");
include_once("functions/class-list-util-serverside.php");

class rumpun_jabatan_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		//kauth

		$CI =& get_instance();
		$configdata= $CI->config;
        $configvlxsessfolder= $configdata->config["vlxsessfolder"];

		$this->nama= $this->session->userdata("nama".$configvlxsessfolder);
		$this->level= $this->session->userdata("level".$configvlxsessfolder);
		$this->userNRP= $this->session->userdata("userNRP".$configvlxsessfolder);
		$this->userAksesKpi= $this->session->userdata("userAksesKpi".$configvlxsessfolder);
	}

	function tree() 
	{
		$this->load->model("base-data/RumpunJabatan");
		$set= new RumpunJabatan();
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;//
		$id = isset($_POST['id']) ? $_POST['id'] : 0;
		$result = array();

		$reqPerusahaanId= $this->input->get("reqPerusahaanId");
		$reqPerusahaanPeraturanId= $this->input->get("reqPerusahaanPeraturanId");

		if ($id == 0)
		{
			$result["total"] = 0;
			$set->selectparamstree(array("A.rumpun_ID_parent" => 0), -1, -1, $statement);
			$i=0;

			while($set->nextRow())
			{
				$items[$i]['ID']= $set->getField("RUMPUN_ID");
				$items[$i]['NAMA']= $set->getField("NAMA");
				$items[$i]['DEFINISI']= $set->getField("DEFINISI");
				$items[$i]['AKSI']= $set->getField("LINK_URL_INFO");
				$items[$i]['state']= $this->haschild($set->getField("RUMPUN_ID"), $statement) ? 'closed' : 'open';
				$i++;
			}
			$result["rows"] = $items;
		} 
		else 
		{
			$set->selectparamstree(array("A.rumpun_ID_parent" => $id), -1, -1, $statement);
			// echo $set->query;exit;
			$i=0;
			while($set->nextRow())
			{
				$items[$i]['ID']= $set->getField("RUMPUN_ID");
				$items[$i]['NAMA']= $set->getField("NAMA");
				$items[$i]['DEFINISI']= $set->getField("DEFINISI");
				$items[$i]['AKSI']= $set->getField("LINK_URL_INFO");
				$items[$i]['state']= $this->haschild($set->getField("RUMPUN_ID"), $statement) ? 'closed' : 'open';
				$i++;
			}
		}
		echo json_encode($result);
	}	
	
	function haschild($id, $stat)
	{
		$child = new RumpunJabatan();
		$child->selectparamstree(array("A.RUMPUN_ID_PARENT" => $id), -1,-1, $stat);
		// echo $child->query;exit;
		$child->firstRow();
		$tempId= $child->getField("RUMPUN_ID");
		// echo $child->errorMsg;exit;
		// echo $tempId;exit;
		if($tempId == "") return false;
		else return true;
		unset($child);
	}

	function add()
	{
		$this->load->model("base/Indikator");

		$reqId= $this->input->post("reqId");
		$reqMode= $this->input->post("reqMode");
		$reqPerusahaanPeraturanId= $this->input->post("reqPerusahaanPeraturanId");
		$reqPerusahaanId= $this->input->post("reqPerusahaanId");
		$reqKode= $this->input->post("reqKode");
		$reqNama= $this->input->post("reqNama");
		$reqSatuanId= $this->input->post("reqSatuanId");
		$reqPolaritasId= $this->input->post("reqPolaritasId");
		$reqFrekuensiId= $this->input->post("reqFrekuensiId");
		$reqKeterangan= $this->input->post("reqKeterangan");
		
		$set= new Indikator();
		$set->setField("INDIKATOR_ID", $reqId);
		$set->setField("INDIKATOR_PARENT_ID", $reqId);
		$set->setField("PERUSAHAAN_PERATURAN_ID", ValToNullDB($reqPerusahaanPeraturanId));
		$set->setField("PERUSAHAAN_ID", ValToNullDB($reqPerusahaanId));
		$set->setField("KODE", setQuote($reqKode));
		$set->setField("NAMA", setQuote($reqNama));
		$set->setField("SATUAN_ID", ValToNullDB($reqSatuanId));
		$set->setField("POLARITAS_ID", ValToNullDB($reqPolaritasId));
		$set->setField("FREKUENSI_ID", ValToNullDB($reqFrekuensiId));
		$set->setField("KETERANGAN", nl2br(stripcslashes(setQuote($reqKeterangan))));
		$set->setField("LAST_USER", $this->nama);

		$reqSimpan="";
		if($reqMode == "insert")
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
			$reqMode= "update";
			echo json_response(200, $reqId.'-Data berhasil disimpan-'.$reqMode);
		}
		else
		{
			echo json_response(400, 'Data gagal disimpan');
		}
	}

	function delete()
	{
		$this->load->model("base/Indikator");

		$reqId= $this->input->get("reqId");
		$reqPerusahaanPeraturanId= $this->input->get("reqPerusahaanPeraturanId");
		$reqPerusahaanId= $this->input->get("reqPerusahaanId");

		$set= new Indikator();
		$set->setField("INDIKATOR_ID", $reqId);
		$set->setField("PERUSAHAAN_PERATURAN_ID", $reqPerusahaanPeraturanId);
		$set->setField("PERUSAHAAN_ID", $reqPerusahaanId);
		$set->delete();
		$vreturn["PESAN"] = "Data berhasil dihapus.";	
		echo json_encode($vreturn);
	}

	function addbobot()
	{
		// reqValId
		// print_r($this->input->post());exit;
		$this->load->model("base/IndikatorBobot");

		$reqPerusahaanPeraturanId= $this->input->post("reqPerusahaanPeraturanId");
		$reqPerusahaanId= $this->input->post("reqPerusahaanId");
		$reqValId= $this->input->post("reqValId");
		$reqValParentId= $this->input->post("reqValParentId");
		$reqValIndikatorId= $this->input->post("reqValIndikatorId");
		$reqValIndikatorParentId= $this->input->post("reqValIndikatorParentId");
		$reqBobot= $this->input->post("reqBobot");

		$set= new IndikatorBobot();
		$set->setField("PERUSAHAAN_PERATURAN_ID", $reqPerusahaanPeraturanId);
		$set->setField("PERUSAHAAN_ID", $reqPerusahaanId);
		$set->delete();

		foreach ($reqValId as $key => $value) {
			$set= new IndikatorBobot();
			$set->setField("PERUSAHAAN_PERATURAN_ID", $reqPerusahaanPeraturanId);
			$set->setField("PERUSAHAAN_ID", $reqPerusahaanId);
			$set->setField("INDIKATOR_ID", $reqValIndikatorId[$key]);
			$set->setField("INDIKATOR_PARENT_ID", $reqValIndikatorParentId[$key]);
			$set->setField("ID", $reqValId[$key]);
			$set->setField("PARENT_ID", $reqValParentId[$key]);
			$set->setField("BOBOT", ValToNull($reqBobot[$key]));
			$set->insert();
		}

		$reqMode= "update";
		echo json_response(200, '-Data berhasil disimpan');
	}

}
?>