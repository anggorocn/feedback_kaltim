<? 
include_once(APPPATH.'/models/Entity.php');

class PegawaiEss extends Entity{ 

	var $query;

	function PegawaiEss()
	{
		$this->Entity(); 
	}

	function selectbyPegawaiEss($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		A.*
		FROM data.riwayat_ess A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		// echo $str; exit;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
	}

	function add()
	{
		$this->setField("riwayat_ess_ID", $this->getNextId("riwayat_ess_ID","data.riwayat_ess")); 

		$str = "
		INSERT INTO data.riwayat_ess
		(
			riwayat_ess_ID, NILAI, PEGAWAI_ID,TAHUN
		) 
		VALUES 
		(
			".$this->getField("riwayat_ess_ID")."
			,'".$this->getField("NILAI")."'
			, '".$this->getField("PEGAWAI_ID")."'
			, '".$this->getField("TAHUN")."'
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
		UPDATE data.riwayat_ess
		SET    
		 	NILAI= '".$this->getField("NILAI")."',
		 	PEGAWAI_ID= '".$this->getField("PEGAWAI_ID")."',
		 	TAHUN= '".$this->getField("TAHUN")."'
		WHERE riwayat_ess_ID = ".$this->getField("riwayat_ess_ID")."
		"; 
		$this->query = $str;
		// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function delete()
	{
		$str = "		
		DELETE FROM data.riwayat_ess
		WHERE riwayat_ess_id = ".$this->getField("riwayat_ess_id")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
} 
?>