<? 
include_once(APPPATH.'/models/Entity.php');

class RiwayatPangkat extends Entity{ 

	var $query;

	function RiwayatPangkat()
	{
		$this->Entity(); 
	}

		function insert()
	{
		$this->setField("RIWAYAT_PANGKAT_ID", $this->getNextId("RIWAYAT_PANGKAT_ID","simpeg.RIWAYAT_PANGKAT")); 

		$str = "
		INSERT INTO simpeg.RIWAYAT_PANGKAT
		(
			RIWAYAT_PANGKAT_ID, PEGAWAI_ID, PANGKAT_ID, TMT_PANGKAT, MK_TAHUN, MK_BULAN
		) 
		VALUES 
		(
			".$this->getField("RIWAYAT_PANGKAT_ID")."
			,'".$this->getField("PEGAWAI_ID")."'
			, '".$this->getField("PANGKAT_ID")."'
			, ".$this->getField("TMT_PANGKAT")."
			, ".$this->getField("MK_TAHUN")."
			, ".$this->getField("MK_BULAN")."
		)
		";

		$this->id = $this->getField("riwayat_ess_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "		
		UPDATE simpeg.RIWAYAT_PANGKAT
		SET    
		 	PANGKAT_ID= '".$this->getField("PANGKAT_ID")."',
		 	TMT_PANGKAT= ".$this->getField("TMT_PANGKAT").",
		 	MK_TAHUN= ".$this->getField("MK_TAHUN").",
		 	MK_BULAN= ".$this->getField("MK_BULAN")."
		WHERE RIWAYAT_PANGKAT_ID = ".$this->getField("RIWAYAT_PANGKAT_ID")."
		"; 
		$this->query = $str;
		// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function delete()
	{
		$str = "		
		DELETE FROM simpeg.RIWAYAT_PANGKAT
		WHERE RIWAYAT_PANGKAT_ID = ".$this->getField("RIWAYAT_PANGKAT_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

	
} 
?>