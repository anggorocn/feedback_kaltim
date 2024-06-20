<? 
include_once(APPPATH.'/models/Entity.php');

class PegawaiKinerja extends Entity{ 

	var $query;

	function PegawaiKinerja()
	{
		$this->Entity(); 
	}

	function selectbyPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT c.acara, c.tanggal_tes tanggal, simpeg.pembulatan(p1.jpm*100) jpm1, simpeg.pembulatan(p2.jpm*100) jpm2 FROM jadwal_pegawai a
		left join jadwal_asesor b on a. jadwal_asesor_id = b.jadwal_asesor_id
		left join jadwal_tes c on b. jadwal_tes_id = c.jadwal_tes_id
		left join penilaian p1 on b. jadwal_tes_id = p1.jadwal_tes_id and p1.aspek_id=1 and a.pegawai_id=p1.pegawai_id
		left join penilaian p2 on b. jadwal_tes_id = p2.jadwal_tes_id and p2.aspek_id=2 and a.pegawai_id=p2.pegawai_id
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement."group by c.acara, p1.jpm, p2.jpm, c.tanggal_tes".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
	}

		function insert()
	{
		$this->setField("riwayat_skp_ID", $this->getNextId("riwayat_skp_ID","data.riwayat_skp")); 

		$str = "
		INSERT INTO data.riwayat_skp
		(
			riwayat_skp_ID, NILAI, PEGAWAI_ID,TAHUN
		) 
		VALUES 
		(
			".$this->getField("riwayat_skp_ID")."
			,'".$this->getField("NILAI")."'
			, '".$this->getField("PEGAWAI_ID")."'
			, '".$this->getField("TAHUN")."'
		)
		";

		$this->id = $this->getField("riwayat_skp_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "		
		UPDATE data.riwayat_skp
		SET    
		 	NILAI= '".$this->getField("NILAI")."',
		 	PEGAWAI_ID= '".$this->getField("PEGAWAI_ID")."',
		 	TAHUN= '".$this->getField("TAHUN")."'
		WHERE riwayat_skp_ID = ".$this->getField("riwayat_skp_ID")."
		"; 
		$this->query = $str;
		// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function delete()
	{
		$str = "		
		DELETE FROM data.riwayat_skp
		WHERE riwayat_skp_ID = ".$this->getField("riwayat_skp_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

	
} 
?>