<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once("functions/string.func.php");

class globalmenu
{
	function caripg($pg)
	{
		$pg= str_replace("_data", "", $pg);
		$pg= str_replace("_standar_nine_box", "", $pg);
		$pg= str_replace("_bobot", "", $pg);
		$pg= str_replace("_nilai", "", $pg);
		$pg= str_replace("_pegawai", "", $pg);

		return $pg;
	}

	function cariparentmenu($arrparam)
	{
		$pg= $arrparam["pg"];

		$pg= $this->caripg($pg);
		// echo $pg;exit;
		$arrMenu= $arrparam["arrMenu"];
		// print_r($arrMenu);exit;

		$vreturn= "";
		$arrayKey= [];
		$arrayKey= in_array_column($pg, "LINK_FILE", $arrMenu);
		// print_r($arrayKey);exit;
		if(!empty($arrayKey))
			$vreturn= $arrMenu[$arrayKey[0]]["MENU_PARENT_ID"];

		return $vreturn;
	}

	function harcodemenu($arrparam)
	{
		// print_r($arrparam);exit;
		$mode= $arrparam["mode"];
		$formulaid= $arrparam["formulaid"];
		$rencanasuksesiid= $arrparam["rencanasuksesiid"];
		$vreturn= [];

		if($mode == "personal")
		{
			$link= "pegawai_data";
			$linkinfo= "Identitas Pegawai";
			if(!empty($formulaid))
			{
				$linkinfo= "Informasi Pegawai";
				$link= "pegawai_formula_data";
			}
			else if(!empty($rencanasuksesiid))
			{
				$linkinfo= "Informasi Pegawai";
				$link= "pegawai_suksesi_data";
			}

			// $vreturn= array(
			// 	array("MENU_ID"=>"01", "MENU_PARENT_ID"=>"0", "NAMA"=> "Data Utama", "LINK_FILE"=>"", "AKSES"=>"", "JUMLAH_CHILD"=>1)
			// 	, array("MENU_ID"=>"0101", "MENU_PARENT_ID"=>"01", "NAMA"=> $linkinfo, "LINK_FILE"=>$link, "AKSES"=>"", "JUMLAH_CHILD"=>0)
			// 	, array("MENU_ID"=>"02", "MENU_PARENT_ID"=>"0", "NAMA"=> "Data Riwayat", "LINK_FILE"=>"", "AKSES"=>"", "JUMLAH_CHILD"=>1)
			// 	, array("MENU_ID"=>"0201", "MENU_PARENT_ID"=>"02", "NAMA"=> "Riwayat Pangkat/Golongan", "LINK_FILE"=>"pegawai_pangkat", "AKSES"=>"", "JUMLAH_CHILD"=>0)
			// 	, array("MENU_ID"=>"0202", "MENU_PARENT_ID"=>"02", "NAMA"=> "Riwayat Jabatan", "LINK_FILE"=>"pegawai_jabatan", "AKSES"=>"", "JUMLAH_CHILD"=>0)
			// 	, array("MENU_ID"=>"0203", "MENU_PARENT_ID"=>"02", "NAMA"=> "Pendidikan Umum", "LINK_FILE"=>"pegawai_pendidikan_umum", "AKSES"=>"", "JUMLAH_CHILD"=>0)
			// 	, array("MENU_ID"=>"0204", "MENU_PARENT_ID"=>"02", "NAMA"=> "Diklat Struktural", "LINK_FILE"=>"pegawai_diklat_struktural", "AKSES"=>"", "JUMLAH_CHILD"=>0)
			// 	, array("MENU_ID"=>"0205", "MENU_PARENT_ID"=>"02", "NAMA"=> "Diklat Fungsional", "LINK_FILE"=>"pegawai_diklat_fungsional", "AKSES"=>"", "JUMLAH_CHILD"=>0)
			// 	, array("MENU_ID"=>"0206", "MENU_PARENT_ID"=>"02", "NAMA"=> "Diklat Teknis", "LINK_FILE"=>"pegawai_diklat_teknis", "AKSES"=>"", "JUMLAH_CHILD"=>0)
			// 	, array("MENU_ID"=>"0207", "MENU_PARENT_ID"=>"02", "NAMA"=> "SKP", "LINK_FILE"=>"pegawai_skp", "AKSES"=>"", "JUMLAH_CHILD"=>0)
			// 	, array("MENU_ID"=>"0208", "MENU_PARENT_ID"=>"02", "NAMA"=> "ESS", "LINK_FILE"=>"pegawai_ess", "AKSES"=>"", "JUMLAH_CHILD"=>0)
			// 	, array("MENU_ID"=>"0209", "MENU_PARENT_ID"=>"02", "NAMA"=> "Total Diklat Teknis", "LINK_FILE"=>"pegawai_total_diklat_teknis", "AKSES"=>"", "JUMLAH_CHILD"=>0)
			// 	, array("MENU_ID"=>"0210", "MENU_PARENT_ID"=>"02", "NAMA"=> "Total Diklat Struktural", "LINK_FILE"=>"pegawai_total_diklat_struktural", "AKSES"=>"", "JUMLAH_CHILD"=>0)
			// 	, array("MENU_ID"=>"0211", "MENU_PARENT_ID"=>"02", "NAMA"=> "Total Diklat Fungsional", "LINK_FILE"=>"pegawai_total_diklat_fungsional", "AKSES"=>"", "JUMLAH_CHILD"=>0)
			// 	, array("MENU_ID"=>"0212", "MENU_PARENT_ID"=>"02", "NAMA"=> "Total PLT PLH", "LINK_FILE"=>"pegawai_total_plt_plh", "AKSES"=>"", "JUMLAH_CHILD"=>0)
			// 	// , array("MENU_ID"=>"0213", "MENU_PARENT_ID"=>"02", "NAMA"=> "Rumpun Jabatan", "LINK_FILE"=>"rumpun_jabatan", "AKSES"=>"", "JUMLAH_CHILD"=>0)
			// 	, array("MENU_ID"=>"0214", "MENU_PARENT_ID"=>"02", "NAMA"=> "Kedudukan Tim", "LINK_FILE"=>"pegawai_kedudukan_tim", "AKSES"=>"", "JUMLAH_CHILD"=>0)
			// );

			$vreturn= array(
				array("MENU_ID"=>"01", "MENU_PARENT_ID"=>"0", "NAMA"=> "Data Utama", "LINK_FILE"=>"", "AKSES"=>"", "JUMLAH_CHILD"=>1)
				, array("MENU_ID"=>"0101", "MENU_PARENT_ID"=>"01", "NAMA"=> $linkinfo, "LINK_FILE"=>$link, "AKSES"=>"", "JUMLAH_CHILD"=>0)
				, array("MENU_ID"=>"02", "MENU_PARENT_ID"=>"0", "NAMA"=> "Data Riwayat", "LINK_FILE"=>"", "AKSES"=>"", "JUMLAH_CHILD"=>1)
				// , array("MENU_ID"=>"0201", "MENU_PARENT_ID"=>"02", "NAMA"=> "Riwayat Pangkat/Golongan", "LINK_FILE"=>"pegawai_pangkat", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				// , array("MENU_ID"=>"0202", "MENU_PARENT_ID"=>"02", "NAMA"=> "Riwayat Jabatan", "LINK_FILE"=>"pegawai_jabatan", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				, array("MENU_ID"=>"0203", "MENU_PARENT_ID"=>"02", "NAMA"=> "Pendidikan Umum", "LINK_FILE"=>"pegawai_pendidikan_umum", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				// , array("MENU_ID"=>"0204", "MENU_PARENT_ID"=>"02", "NAMA"=> "Diklat Struktural", "LINK_FILE"=>"pegawai_diklat_struktural", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				// , array("MENU_ID"=>"0205", "MENU_PARENT_ID"=>"02", "NAMA"=> "Diklat Fungsional", "LINK_FILE"=>"pegawai_diklat_fungsional", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				// , array("MENU_ID"=>"0206", "MENU_PARENT_ID"=>"02", "NAMA"=> "Diklat Teknis", "LINK_FILE"=>"pegawai_diklat_teknis", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				, array("MENU_ID"=>"0205", "MENU_PARENT_ID"=>"02", "NAMA"=> "SKP", "LINK_FILE"=>"pegawai_skp", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				, array("MENU_ID"=>"0206", "MENU_PARENT_ID"=>"02", "NAMA"=> "Riwayat Huknis", "LINK_FILE"=>"pegawai_huknis", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				, array("MENU_ID"=>"0207", "MENU_PARENT_ID"=>"02", "NAMA"=> "Riwayat Assesment", "LINK_FILE"=>"pegawai_assesment", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				// , array("MENU_ID"=>"0207", "MENU_PARENT_ID"=>"02", "NAMA"=> "Kinerja", "LINK_FILE"=>"pegawai_kinerja", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				, array("MENU_ID"=>"0208", "MENU_PARENT_ID"=>"02", "NAMA"=> "Riwayat Penghargaan", "LINK_FILE"=>"pegawai_penghargaan", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				// , array("MENU_ID"=>"0208", "MENU_PARENT_ID"=>"02", "NAMA"=> "ESS", "LINK_FILE"=>"pegawai_ess", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				// , array("MENU_ID"=>"0209", "MENU_PARENT_ID"=>"02", "NAMA"=> "Total Diklat Teknis", "LINK_FILE"=>"pegawai_total_diklat_teknis", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				// , array("MENU_ID"=>"0210", "MENU_PARENT_ID"=>"02", "NAMA"=> "Total Diklat Struktural", "LINK_FILE"=>"pegawai_total_diklat_struktural", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				// , array("MENU_ID"=>"0211", "MENU_PARENT_ID"=>"02", "NAMA"=> "Total Diklat Fungsional", "LINK_FILE"=>"pegawai_total_diklat_fungsional", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				// , array("MENU_ID"=>"0212", "MENU_PARENT_ID"=>"02", "NAMA"=> "Total PLT PLH", "LINK_FILE"=>"pegawai_total_plt_plh", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				// , array("MENU_ID"=>"0213", "MENU_PARENT_ID"=>"02", "NAMA"=> "Rumpun Jabatan", "LINK_FILE"=>"rumpun_jabatan", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				// , array("MENU_ID"=>"0214", "MENU_PARENT_ID"=>"02", "NAMA"=> "Kedudukan Tim", "LINK_FILE"=>"pegawai_kedudukan_tim", "AKSES"=>"", "JUMLAH_CHILD"=>0)
			);
		}
		else if($mode == "admin")
		{
			$vreturn= array(
				array("MENU_ID"=>"01", "MENU_PARENT_ID"=>"0", "NAMA"=> "Data", "LINK_FILE"=>"", "AKSES"=>"", "JUMLAH_CHILD"=>3)
				, array("MENU_ID"=>"0101", "MENU_PARENT_ID"=>"01", "NAMA"=> "Pegawai", "LINK_FILE"=>"pegawai", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				, array("MENU_ID"=>"0101", "MENU_PARENT_ID"=>"01", "NAMA"=> "Formula talenta", "LINK_FILE"=>"formula_penilaian", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				, array("MENU_ID"=>"0101", "MENU_PARENT_ID"=>"01", "NAMA"=> "Talent pool", "LINK_FILE"=>"home", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				// , array("MENU_ID"=>"02", "MENU_PARENT_ID"=>"0", "NAMA"=> "Master", "LINK_FILE"=>"", "AKSES"=>"", "JUMLAH_CHILD"=>2)
				, array("MENU_ID"=>"0102", "MENU_PARENT_ID"=>"02", "NAMA"=> "Master Kampus", "LINK_FILE"=>"master_kampus", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				, array("MENU_ID"=>"0102", "MENU_PARENT_ID"=>"02", "NAMA"=> "Rumpun Jabatan", "LINK_FILE"=>"rumpun_jabatan", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				// , array("MENU_ID"=>"03", "MENU_PARENT_ID"=>"0", "NAMA"=> "Talenta", "LINK_FILE"=>"", "AKSES"=>"", "JUMLAH_CHILD"=>2)
				, array("MENU_ID"=>"0301", "MENU_PARENT_ID"=>"03", "NAMA"=> "Rencana Suksesi", "LINK_FILE"=>"rencana_suksesi", "AKSES"=>"", "JUMLAH_CHILD"=>0)
				, array("MENU_ID"=>"0302", "MENU_PARENT_ID"=>"03", "NAMA"=> "Pengembangan Talent", "LINK_FILE"=>"pengembangan_talent", "AKSES"=>"", "JUMLAH_CHILD"=>0)
			);
		}

		return $vreturn;
	}

	function menuheader($arrparam)
	{
		// print_r($arrparam);exit;
		$pg= $arrparam["pg"];
		$id= $arrparam["id"];

		$arrmenulinkformula= array("formula_penilaian_data", "formula_penilaian_standar_nine_box", "formula_penilaian_bobot", "formula_penilaian_nilai", "formula_penilaian_pegawai");

		$vreturn= [];
		if(in_array($pg, $arrmenulinkformula))
		{
			$vreturn= array(
				array("id"=>"01", "idparent"=>"0", "nama"=>"Formula Talenta", "linkfile"=>"formula_penilaian", "jumlah_anak"=>0)
				, array("id"=>"02", "idparent"=>"0", "nama"=>"Data", "linkfile"=>"formula_penilaian_data", "jumlah_anak"=>0)
			);

			if(!empty($id))
			{
				array_push($vreturn
					, array("id"=>"03", "idparent"=>"0", "nama"=>"Indikator Penilaian", "linkfile"=>"", "jumlah_anak"=>4)
					, array("id"=>"0301", "idparent"=>"03", "nama"=>"Standar Nine Box", "linkfile"=>"formula_penilaian_standar_nine_box", "jumlah_anak"=>0)
					, array("id"=>"0302", "idparent"=>"03", "nama"=>"Bobot", "linkfile"=>"formula_penilaian_bobot", "jumlah_anak"=>0)
					, array("id"=>"0303", "idparent"=>"03", "nama"=>"Penilaian", "linkfile"=>"formula_penilaian_nilai", "jumlah_anak"=>0)
					, array("id"=>"0304", "idparent"=>"03", "nama"=>"Proses Pegawai", "linkfile"=>"formula_penilaian_pegawai", "jumlah_anak"=>0)
				);
			}
		}
		// print_r($vreturn);exit;

		return $vreturn;
	}

	function indikatorpenilaiansub($arrparam)
	{
		$CI = &get_instance();
		$CI->load->model("base-data/FormulaPenilaian");

		$statement= "";
		// $statement= " AND A.SUB_INDIKATOR_ID = 8";
		// $statement= " AND A.SUB_INDIKATOR_ID IN (8,9)";
		// $statement= " AND A.INDIKATOR_PENILAIAN_ID = 5";
		// $statement= " AND A.INDIKATOR_PENILAIAN_ID IN (5,6)";
		$vreturn= [];
		$set= new FormulaPenilaian();
		$set->selectbyindikatorpenilaiansub(array(), -1, -1, $statement);
		// echo $set->query;exit;
		while($set->nextRow())
		{
			$arrdata= [];
			$arrdata["PERMEN_INDIKATOR_ID"]= $set->getField("PERMEN_INDIKATOR_ID");
			$arrdata["INDIKATOR_PENILAIAN_ID"]= $set->getField("INDIKATOR_PENILAIAN_ID");
			$arrdata["SUB_INDIKATOR_ID"]= $set->getField("SUB_INDIKATOR_ID");
			$arrdata["NAMA"]= $set->getField("NAMA");

			array_push($vreturn, $arrdata);
		}
		return $vreturn;
	}

	function indikatorpenilaianvalue($arrparam)
	{
		$CI = &get_instance();
		$CI->load->model("base-data/FormulaPenilaian");

		$statement= "";
		// $statement= " AND A.SUB_INDIKATOR_ID = 8";
		// $statement= " AND A.SUB_INDIKATOR_ID IN (8,9)";
		// $statement= " AND A.INDIKATOR_PENILAIAN_ID = 5";
		// $statement= " AND A.INDIKATOR_PENILAIAN_ID IN (5,6)";
		$vreturn= [];
		$set= new FormulaPenilaian();
		$set->selectbyindikatorpenilaianvalue(array(), -1, -1, $statement);
		// echo $set->query;exit;
		while($set->nextRow())
		{
			$indikatorpenilaianid= $set->getField("INDIKATOR_PENILAIAN_ID");
			$subindikatorid= $set->getField("SUB_INDIKATOR_ID");

			$arrdata= [];
			$arrdata["key"]= $indikatorpenilaianid."-".$subindikatorid;
			$arrdata["PERMEN_INDIKATOR_ID"]= $set->getField("PERMEN_INDIKATOR_ID");
			$arrdata["INDIKATOR_PENILAIAN_ID"]= $indikatorpenilaianid;
			$arrdata["SUB_INDIKATOR_ID"]= $subindikatorid;
			$arrdata["SUB_INDIKATOR_DETIL_ID"]= $set->getField("SUB_INDIKATOR_DETIL_ID");
			$arrdata["NAMA"]= $set->getField("VLABEL");

			array_push($vreturn, $arrdata);
		}
		// print_r($vreturn);exit;
		return $vreturn;
	}

	function getjabatantree($arrparam)
	{
		$CI = &get_instance();
		$CI->load->model("base-data/InfoData");

		$statement="";
		$set= new InfoData();
		$arrsimpegjabatan= [];
		$set->selectbyparamsjabatansimpegdata(array(), -1,-1,$statement);
		// echo $set->query;exit;
		while($set->nextRow())
		{
			$arrdata= [];
			$arrdata["id"]= $set->getField("JABATAN_ID");
			$arrdata["parentid"]= $set->getField("JABATAN_ID_PARENT");
			$arrdata["text"]= $set->getField("NAMA_JABATAN");
			array_push($arrsimpegjabatan, $arrdata);
		}
		unset($set);
		// print_r($arrsimpegjabatan);exit;

		$arrdatajabatan= [];
		$infocarikey= "0";
		// echo $infocarikey;exit;
		$arrcheck= in_array_column($infocarikey, "parentid", $arrsimpegjabatan);
		// print_r($arrcheck);exit;
		foreach ($arrcheck as $vindex)
		{
			$vid= $arrsimpegjabatan[$vindex]["id"];
			$vtext= $arrsimpegjabatan[$vindex]["text"];

			$arrdata= [];
			$arrdata["id"]= $vid;
			$arrdata["text"]= $vtext;

			$infocarichildkey= $vid;
			$arrchildcheck= in_array_column($infocarichildkey, "parentid", $arrsimpegjabatan);
			if(!empty($arrchildcheck))
			{
				$arrdata["inc"]= $this->getchild($vid, $arrsimpegjabatan);
			}
			array_push($arrdatajabatan, $arrdata);
		}
		return $arrdatajabatan;
	}

	function getchild($vid, $arrsimpegjabatan)
	{
		$arrdatachildjabatan= [];
		$infocarikey= $vid;
		$arrcheck= in_array_column($infocarikey, "parentid", $arrsimpegjabatan);
		// print_r($arrcheck);exit;
		foreach ($arrcheck as $vindex)
		{
			$vid= $arrsimpegjabatan[$vindex]["id"];
			$vtext= $arrsimpegjabatan[$vindex]["text"];

			$arrdata= [];
			$arrdata["id"]= $vid;
			$arrdata["text"]= $vtext;

			$infocarichildkey= $vid;
			$arrchildcheck= in_array_column($infocarichildkey, "parentid", $arrsimpegjabatan);
			if(!empty($arrchildcheck))
			{
				$arrdata["inc"]= $this->getchild($vid, $arrsimpegjabatan);
			}
			array_push($arrdatachildjabatan, $arrdata);
		}
		return $arrdatachildjabatan;
	}

}