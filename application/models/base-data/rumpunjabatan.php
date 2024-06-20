<? 
include_once(APPPATH.'/models/Entity.php');

class RumpunJabatan extends Entity{ 

	var $query;

	function RumpunJabatan()
	{
		$this->Entity(); 
	}

	function insert()
	{
		$this->setField("PERUSAHAAN_PERATURAN_ID", $this->getNextId("PERUSAHAAN_PERATURAN_ID","pds_kpi.perusahaan_peraturan")); 

		$str = "
		INSERT INTO pds_kpi.perusahaan_peraturan
		(
			PERUSAHAAN_PERATURAN_ID, PERUSAHAAN_ID, KODE, NAMA
			, LAST_CREATE_USER, LAST_CREATE_DATE
		) 
		VALUES 
		(
			".$this->getField("PERUSAHAAN_PERATURAN_ID")."
			, ".$this->getField("PERUSAHAAN_ID")."
			, '".$this->getField("KODE")."'
			, '".$this->getField("NAMA")."'
			, '".$this->getField("LAST_USER")."'
			, NOW()
		)
		";

		$this->id = $this->getField("PERUSAHAAN_PERATURAN_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "		
		UPDATE pds_kpi.perusahaan_peraturan
		SET    
		 	NAMA= '".$this->getField("NAMA")."'
		 	, KODE= '".$this->getField("KODE")."'
		 	, PERUSAHAAN_ID= ".$this->getField("PERUSAHAAN_ID")."
		 	, LAST_UPDATE_USER= '".$this->getField("LAST_USER")."'
			, LAST_UPDATE_DATE= NOW()
		WHERE PERUSAHAAN_PERATURAN_ID = ".$this->getField("PERUSAHAAN_PERATURAN_ID")."
		";
		$this->query = $str;
		// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function delete()
	{
		$str = "		
		DELETE FROM pds_kpi.perusahaan_peraturan
		WHERE PERUSAHAAN_PERATURAN_ID = ".$this->getField("PERUSAHAAN_PERATURAN_ID")."
		";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function selectparam($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.NAMA")
	{
		$str = "
		SELECT
		* from simpeg.rumpun_jabatan
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

	function selectparamstree($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.rumpun_ID ASC ')
	{
		$str = "
		SELECT 
				
			A.*,
				CASE
				WHEN A.RUMPUN_ID_PARENT = '0' THEN CONCAT('<a onClick=\"adddata(''',A.RUMPUN_ID,''',''insert'')\" style=\"cursor:pointer\" title=\"Tambah\"><i class=\"la la-plus-circle\"></i></a> - ')
				ELSE ''
				END ||
				CONCAT('<a onClick=\"adddata(''',A.RUMPUN_ID,''',''update'')\", ''Aplikasi Data'', ''500'', ''200'')\" style=\"cursor:pointer\" title=\"Ubah\"><i class=\"la la-edit\"></i></a> - <a onClick=\"hapusdata(''',A.RUMPUN_ID,''','''')\" style=\"cursor:pointer\" title=\"Hapus\"><i class=\"la la-trash-o\"></i></a>')
			LINK_URL_INFO
		FROM simpeg.rumpun_jabatan A
		WHERE 1 = 1
		";
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
		
    }
} 
?>