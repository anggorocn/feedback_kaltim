<? 
include_once(APPPATH.'/models/Entity.php');

class MasterKampus extends Entity{ 

	var $query;

	function MasterKampus()
	{
		$this->Entity(); 
	}

	function insert()
	{
		$this->setField("KAMPUS_ID", $this->getNextId("KAMPUS_ID","simpeg.kampus")); 

		$str = "
		INSERT INTO simpeg.kampus
		(
			KAMPUS_ID, S1,S2,S3, NAMA
		) 
		VALUES 
		(
			".$this->getField("KAMPUS_ID")."
			,".$this->getField("S1").",
			,".$this->getField("S2").",
			,".$this->getField("S3").",
			, '".$this->getField("NAMA")."'
		)
		";

		$this->id = $this->getField("KAMPUS_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "		
		UPDATE simpeg.kampus
		SET    
		 	NAMA= '".$this->getField("NAMA")."',
		 	S1= ".$this->getField("S1").",
		 	S2= ".$this->getField("S2").",
		 	S3= ".$this->getField("S3")."
		WHERE KAMPUS_ID = ".$this->getField("KAMPUS_ID")."
		"; 
		$this->query = $str;
		// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }
} 
?>