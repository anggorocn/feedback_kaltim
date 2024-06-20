<? 
include_once(APPPATH.'/models/Entity.php');

class PegawaiSkp extends Entity{ 

	var $query;

	function PegawaiSkp()
	{
		$this->Entity(); 
	}

	function selectbyPegawaiSkp($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		A.*
		FROM data.riwayat_skp A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
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