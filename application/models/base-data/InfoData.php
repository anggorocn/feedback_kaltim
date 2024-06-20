<? 
include_once(APPPATH.'/models/Entity.php');

class InfoData extends Entity{ 

	var $query;

	function InfoData()
	{
		$this->Entity(); 
	}

	function selectbyformulapenilaian($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		A.*
		FROM simpeg.formula_penilaian A
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

	function selectbyagama($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		A.*
		FROM simpeg.agama A
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

	function selectbysatuankerja($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		A.*
		FROM simpeg.satker A
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

	function selectbypangkat($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		A.*
		FROM simpeg.pangkat A
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

	function selectbyeselon($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.ESELON_ID")
	{
		$str = "
		SELECT
		A.*
		FROM simpeg.eselon A
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

	function selectbytipepegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.TIPE_PEGAWAI_ID")
	{
		$str = "
		SELECT
		A.*
		FROM simpeg.tipe_pegawai A
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

	function selectbypendidikan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.PENDIDIKAN_ID")
	{
		$str = "
		SELECT
		A.*
		FROM simpeg.pendidikan A
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

	function selectbyjenjang($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.JENJANG_ID")
	{
		$str = "
		SELECT
		A.*
		FROM simpeg.jenjang A
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

	function selectbykampus($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.NAMA")
	{
		$str = "
		SELECT
		B.NAMA TINGKAT_PENDIDIKAN_NAMA, C.NAMA TINGKAT_PENDIDIKAN_NAMAS1, D.NAMA TINGKAT_PENDIDIKAN_NAMAS2
		, E.NAMA TINGKAT_PENDIDIKAN_NAMAS3
		, A.*
		FROM simpeg.kampus A
		left join simpeg.tingkat_pendidikan b on a.TINGKAT_PENDIDIKAN_ID = b. TINGKAT_PENDIDIKAN_ID 
		left join simpeg.tingkat_pendidikan c on a.s1 = c. TINGKAT_PENDIDIKAN_ID 
		left join simpeg.tingkat_pendidikan d on a.s2 = d. TINGKAT_PENDIDIKAN_ID 
		left join simpeg.tingkat_pendidikan e on a.s3 = e. TINGKAT_PENDIDIKAN_ID 
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

	function selectbyparamspegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		A1.KODE PANGKAT_KODE, A1.NAMA PANGKAT_NAMA, A2.NAMA SATKER_NAMA
		, A3.NAMA ESELON_NAMA, A4.NAMA TIPE_PEGAWAI_NAMA,  A6.NAMA PENDIDIKAN_NAMA
		, A.*
		FROM simpeg.pegawai A
		LEFT JOIN simpeg.pangkat A1 ON A1.PANGKAT_ID = A.LAST_PANGKAT_ID
		LEFT JOIN simpeg.satker A2 ON A.SATKER_ID = A2.SATKER_ID
		LEFT JOIN simpeg.eselon A3 on A.LAST_ESELON_ID = A3.ESELON_ID
		LEFT JOIN simpeg.tipe_pegawai A4 on A.TIPE_PEGAWAI_ID = A4.TIPE_PEGAWAI_ID
		LEFT JOIN simpeg.pendidikan A6 ON A.LAST_DIK_JENJANG = A6.PENDIDIKAN_ID
		WHERE 1=1
		"; 

		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectbyparamspangkat($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY COALESCE(A.TMT_PANGKAT, TO_DATE('1900-01-01', 'YYYY-MM-DD')) DESC")
	{
		$str = "
		SELECT
		A1.KODE PANGKAT_KODE, A1.NAMA PANGKAT_NAMA
		, A.*
		FROM simpeg.riwayat_pangkat A
		LEFT JOIN simpeg.pangkat A1 ON A1.PANGKAT_ID = A.PANGKAT_ID
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

	function selectbyparamsjabatan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.TMT_JABATAN DESC")
	{
		$str = "
		SELECT
		A1.NAMA ESELON_NAMA
		, A.*
		FROM simpeg.riwayat_jabatan A
		LEFT JOIN simpeg.eselon A1 ON A1.ESELON_ID = A.ESELON_ID
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

	function selectbyparamsjabatandata($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY COALESCE(A.TMT_JABATAN, TO_DATE('1900-01-01', 'YYYY-MM-DD')) DESC")
	{
		$str = "
		SELECT
		A1.NAMA ESELON_NAMA
		, A.*
		FROM data.riwayat_jabatan A
		LEFT JOIN simpeg.eselon A1 ON A1.ESELON_ID = A.ESELON_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectbyparamspendidikan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.TAHUN::NUMERIC DESC")
	{
		$str = "
		SELECT
		A1.NAMA PENDIDIKAN_NAMA, A2.NAMA KAMPUS_NAMA
		, A.*
		FROM simpeg.riwayat_pendidikan A
		LEFT JOIN simpeg.pendidikan A1 ON A1.PENDIDIKAN_ID = A.PENDIDIKAN_ID
		LEFT JOIN simpeg.kampus A2 ON A2.KAMPUS_ID = A.KAMPUS_ID
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

	function selectbyparamspendidikandata($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.PENDIDIKAN_ID DESC")
	{
		$str = "
		SELECT a.*, b.nama PENDIDIKAN_NAMA
		from data.riwayat_pendidikan a
		left join simpeg.pendidikan b on b.PENDIDIKAN_ID =a.PENDIDIKAN_ID
		where 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectbyparamsdiklatstrukturaldata($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY COALESCE(A.TANGGAL_MULAI, TO_DATE('1900-01-01', 'YYYY-MM-DD')) DESC")
	{
		$str = "
		SELECT
		A.*
		FROM data.riwayat_diklat_struktural A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;

		return $this->selectLimit($str,$limit,$from); 
	}

	function selectbyparamsdiklatfungsionaldata($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY COALESCE(A.TANGGAL_MULAI, TO_DATE('1900-01-01', 'YYYY-MM-DD')) DESC")
	{
		$str = "
		SELECT
		A.*
		FROM data.riwayat_diklat_fungsional A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;

		return $this->selectLimit($str,$limit,$from); 
	}

	function selectbyparamsdiklatteknisdata($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY COALESCE(A.TANGGAL_MULAI, TO_DATE('1900-01-01', 'YYYY-MM-DD')) DESC")
	{
		$str = "
		SELECT
		A.*
		FROM data.riwayat_diklat_teknis A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;

		return $this->selectLimit($str,$limit,$from); 
	}

	function selectbyparamstingkatpendidikan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.TINGKAT_PENDIDIKAN_ID ASC")
	{
		$str = "
		SELECT
		A.*
		FROM simpeg.tingkat_pendidikan A
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

	function jabatanriwayatinsert()
	{
		$this->setField("RIWAYAT_JABATAN_ID", $this->getNextId("RIWAYAT_JABATAN_ID", "data.riwayat_jabatan")); 

		$str = "
		INSERT INTO data.riwayat_jabatan
		(
			RIWAYAT_JABATAN_ID, PEGAWAI_ID, ESELON_ID, JABATAN, MASA_JAB_TAHUN, MASA_JAB_BULAN, TMT_JABATAN
		) 
		VALUES 
		(
			".$this->getField("RIWAYAT_JABATAN_ID")."
			, '".$this->getField("PEGAWAI_ID")."'
			, '".$this->getField("ESELON_ID")."'
			, '".$this->getField("JABATAN")."'
			, ".$this->getField("MASA_JAB_TAHUN")."
			, ".$this->getField("MASA_JAB_BULAN")."
			, ".$this->getField("TMT_JABATAN")."
		)
		";

		$this->id = $this->getField("RIWAYAT_JABATAN_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function jabatanriwayatupdate()
	{
		$str = "		
		UPDATE data.riwayat_jabatan
		SET    
		 	ESELON_ID= ".$this->getField("ESELON_ID")."
		 	, JABATAN= '".$this->getField("JABATAN")."'
		 	, MASA_JAB_TAHUN= ".$this->getField("MASA_JAB_TAHUN")."
		 	, MASA_JAB_BULAN= ".$this->getField("MASA_JAB_BULAN")."
		 	, TMT_JABATAN= ".$this->getField("TMT_JABATAN")."
		WHERE RIWAYAT_JABATAN_ID = ".$this->getField("RIWAYAT_JABATAN_ID")."
		"; 
		$this->query = $str;
		// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function pendidikanriwayatinsert()
	{
		$this->setField("RIWAYAT_PENDIDIKAN_ID", $this->getNextId("RIWAYAT_PENDIDIKAN_ID", "data.riwayat_pendidikan")); 

		// , KODE_JURUSAN
		// , ".$this->getField("KODE_JURUSAN")."
		$str = "
		INSERT INTO data.riwayat_pendidikan
		(
			RIWAYAT_PENDIDIKAN_ID, PENDIDIKAN_ID, PEGAWAI_ID, KAMPUS_ID
			, JURUSAN, TANGGAL
		) 
		VALUES 
		(
			".$this->getField("RIWAYAT_PENDIDIKAN_ID")."
			, ".$this->getField("PENDIDIKAN_ID")."
			, ".$this->getField("PEGAWAI_ID")."
			, ".$this->getField("KAMPUS_ID")."
			, '".$this->getField("JURUSAN")."'
			, ".$this->getField("TANGGAL")."
		)
		";

		$this->id = $this->getField("RIWAYAT_PENDIDIKAN_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function pendidikanriwayatupdate()
	{
		$str = "		
		UPDATE data.riwayat_pendidikan
		SET    
		 	PENDIDIKAN_ID= ".$this->getField("PENDIDIKAN_ID")."
		 	, KAMPUS_ID= ".$this->getField("KAMPUS_ID")."
		 	, JURUSAN= '".$this->getField("JURUSAN")."'
		 	, TANGGAL= ".$this->getField("TANGGAL")."
		WHERE RIWAYAT_PENDIDIKAN_ID = ".$this->getField("RIWAYAT_PENDIDIKAN_ID")."
		"; 
		$this->query = $str;
		// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function diklatstrukturalriwayatinsert()
	{
		$this->setField("RIWAYAT_DIKLAT_STRUKTURAL_ID", $this->getNextId("RIWAYAT_DIKLAT_STRUKTURAL_ID", "data.riwayat_diklat_struktural")); 

		$str = "
		INSERT INTO data.riwayat_diklat_struktural
		(
			RIWAYAT_DIKLAT_STRUKTURAL_ID, PEGAWAI_ID, NAMA, PENYELENGGARA
			, TANGGAL_MULAI, TANGGAL_AKHIR
		) 
		VALUES 
		(
			".$this->getField("RIWAYAT_DIKLAT_STRUKTURAL_ID")."
			, ".$this->getField("PEGAWAI_ID")."
			, '".$this->getField("NAMA")."'
			, '".$this->getField("PENYELENGGARA")."'
			, ".$this->getField("TANGGAL_MULAI")."
			, ".$this->getField("TANGGAL_AKHIR")."
		)
		";

		$this->id = $this->getField("RIWAYAT_DIKLAT_STRUKTURAL_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function diklatstrukturalriwayatupdate()
	{
		$str = "		
		UPDATE data.riwayat_diklat_struktural
		SET    
		 	RIWAYAT_DIKLAT_STRUKTURAL_ID= ".$this->getField("RIWAYAT_DIKLAT_STRUKTURAL_ID")."
		 	, NAMA= '".$this->getField("NAMA")."'
		 	, PENYELENGGARA= '".$this->getField("PENYELENGGARA")."'
		 	, TANGGAL_MULAI= ".$this->getField("TANGGAL_MULAI")."
		 	, TANGGAL_AKHIR= ".$this->getField("TANGGAL_AKHIR")."
		WHERE RIWAYAT_DIKLAT_STRUKTURAL_ID = ".$this->getField("RIWAYAT_DIKLAT_STRUKTURAL_ID")."
		"; 
		$this->query = $str;
		// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function diklatfungsionalriwayatinsert()
	{
		$this->setField("RIWAYAT_DIKLAT_FUNGSIONAL_ID", $this->getNextId("RIWAYAT_DIKLAT_FUNGSIONAL_ID", "data.riwayat_diklat_fungsional")); 

		$str = "
		INSERT INTO data.riwayat_diklat_fungsional
		(
			RIWAYAT_DIKLAT_FUNGSIONAL_ID, PEGAWAI_ID, NAMA, PENYELENGGARA, TANGGAL_MULAI, TANGGAL_AKHIR
		) 
		VALUES 
		(
			".$this->getField("RIWAYAT_DIKLAT_FUNGSIONAL_ID")."
			, ".$this->getField("PEGAWAI_ID")."
			, '".$this->getField("NAMA")."'
			, '".$this->getField("PENYELENGGARA")."'
			, ".$this->getField("TANGGAL_MULAI")."
			, ".$this->getField("TANGGAL_AKHIR")."
		)
		";

		$this->id = $this->getField("RIWAYAT_DIKLAT_FUNGSIONAL_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function diklatfungsionalriwayatupdate()
	{
		$str = "		
		UPDATE data.riwayat_diklat_fungsional
		SET    
		 	RIWAYAT_DIKLAT_FUNGSIONAL_ID= ".$this->getField("RIWAYAT_DIKLAT_FUNGSIONAL_ID")."
		 	, NAMA= '".$this->getField("NAMA")."'
		 	, PENYELENGGARA= '".$this->getField("PENYELENGGARA")."'
		 	, TANGGAL_MULAI= ".$this->getField("TANGGAL_MULAI")."
		 	, TANGGAL_AKHIR= ".$this->getField("TANGGAL_AKHIR")."
		WHERE RIWAYAT_DIKLAT_FUNGSIONAL_ID = ".$this->getField("RIWAYAT_DIKLAT_FUNGSIONAL_ID")."
		"; 
		$this->query = $str;
		// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function diklatteknisriwayatinsert()
	{
		$this->setField("RIWAYAT_DIKLAT_TEKNIS_ID", $this->getNextId("RIWAYAT_DIKLAT_TEKNIS_ID", "data.riwayat_diklat_teknis")); 

		$str = "
		INSERT INTO data.riwayat_diklat_teknis
		(
			RIWAYAT_DIKLAT_TEKNIS_ID, PEGAWAI_ID, NAMA, PENYELENGGARA, TANGGAL_MULAI, TANGGAL_AKHIR
		) 
		VALUES 
		(
			".$this->getField("RIWAYAT_DIKLAT_TEKNIS_ID")."
			, ".$this->getField("PEGAWAI_ID")."
			, '".$this->getField("NAMA")."'
			, '".$this->getField("PENYELENGGARA")."'
			, ".$this->getField("TANGGAL_MULAI")."
			, ".$this->getField("TANGGAL_AKHIR")."
		)
		";

		$this->id = $this->getField("RIWAYAT_DIKLAT_TEKNIS_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function diklatteknisriwayatupdate()
	{
		$str = "		
		UPDATE data.riwayat_diklat_teknis
		SET    
		 	RIWAYAT_DIKLAT_TEKNIS_ID= ".$this->getField("RIWAYAT_DIKLAT_TEKNIS_ID")."
		 	, NAMA= '".$this->getField("NAMA")."'
		 	, PENYELENGGARA= '".$this->getField("PENYELENGGARA")."'
		 	, TANGGAL_MULAI= ".$this->getField("TANGGAL_MULAI")."
		 	, TANGGAL_AKHIR= ".$this->getField("TANGGAL_AKHIR")."
		WHERE RIWAYAT_DIKLAT_TEKNIS_ID = ".$this->getField("RIWAYAT_DIKLAT_TEKNIS_ID")."
		"; 
		$this->query = $str;
		// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function jabatanriwayatdelete()
	{
		$str = "		
		DELETE FROM data.riwayat_jabatan
		WHERE RIWAYAT_JABATAN_ID = ".$this->getField("RIWAYAT_JABATAN_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function pendidikanriwayatdelete()
	{
		$str = "		
		DELETE FROM data.riwayat_pendidikan
		WHERE RIWAYAT_PENDIDIKAN_ID = ".$this->getField("RIWAYAT_PENDIDIKAN_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
    
    function strukturalriwayatdelete()
	{
		$str = "		
		DELETE FROM data.riwayat_diklat_struktural
		WHERE RIWAYAT_DIKLAT_STRUKTURAL_ID = ".$this->getField("RIWAYAT_DIKLAT_STRUKTURAL_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
    
    function fungsionalriwayatdelete()
	{
		$str = "		
		DELETE FROM data.riwayat_diklat_fungsional
		WHERE RIWAYAT_DIKLAT_FUNGSIONAL_ID = ".$this->getField("RIWAYAT_DIKLAT_FUNGSIONAL_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
    
    function teknisriwayatdelete()
	{
		$str = "		
		DELETE FROM data.riwayat_diklat_teknis
		WHERE RIWAYAT_DIKLAT_TEKNIS_ID = ".$this->getField("RIWAYAT_DIKLAT_TEKNIS_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

   	function selectbyJurusan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		A.*
		FROM simpeg.jurusan A
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

	function totaldiklatteknisriwayatinsert()
	{
		$this->setField("TOTAL_DIKLAT_TEKNIS_ID", $this->getNextId("TOTAL_DIKLAT_TEKNIS_ID", "data.TOTAL_DIKLAT_TEKNIS")); 

		$str = "
		INSERT INTO data.TOTAL_DIKLAT_TEKNIS
		(
			TOTAL_DIKLAT_TEKNIS_ID, PEGAWAI_ID, JUMLAH
		) 
		VALUES 
		(
			".$this->getField("TOTAL_DIKLAT_TEKNIS_ID")."
			, ".$this->getField("PEGAWAI_ID")."
			, ".$this->getField("JUMLAH")."
		)
		";

		$this->id = $this->getField("TOTAL_DIKLAT_TEKNIS_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function totaldiklatteknisriwayatupdate()
	{
		$str = "		
		UPDATE data.TOTAL_DIKLAT_TEKNIS
		SET    
		 	JUMLAH= ".$this->getField("JUMLAH")."
		WHERE PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
		"; 
		$this->query = $str;
		// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function selectbytotaldiklatteknis($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		A.*
		FROM data.TOTAL_DIKLAT_TEKNIS A
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

	function totaldiklatstrukturalriwayatinsert()
	{
		$this->setField("TOTAL_DIKLAT_struktural_ID", $this->getNextId("TOTAL_DIKLAT_struktural_ID", "data.total_diklat_struktural")); 

		$str = "
		INSERT INTO data.total_diklat_struktural
		(
			TOTAL_DIKLAT_STRUKTURAL_ID, PEGAWAI_ID, PIM1, PIM2, PIM3, PIM4
		) 
		VALUES 
		(
			".$this->getField("TOTAL_DIKLAT_struktural_ID")."
			, ".$this->getField("PEGAWAI_ID")."
			, ".$this->getField("PIM1")."
			, ".$this->getField("PIM2")."
			, ".$this->getField("PIM3")."
			, ".$this->getField("PIM4")."
		)
		";

		$this->id = $this->getField("TOTAL_DIKLAT_struktural_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function totaldiklatstrukturalriwayatupdate()
	{
		$str = "		
		UPDATE data.total_diklat_struktural
		SET    
		 	PIM1= ".$this->getField("PIM1")."
		 	, PIM2= ".$this->getField("PIM2")."
		 	, PIM3= ".$this->getField("PIM3")."
		 	, PIM4= ".$this->getField("PIM4")."
		WHERE PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
		"; 
		$this->query = $str;
		// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function selectbytotaldiklatstruktural($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		A.*
		FROM data.total_diklat_struktural A
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

	function totaldiklatfungsionalriwayatinsert()
	{
		$this->setField("TOTAL_DIKLAT_FUNGSIONAL_ID", $this->getNextId("TOTAL_DIKLAT_FUNGSIONAL_ID", "data.total_diklat_fungsional"));

		$str = "
		INSERT INTO data.total_diklat_fungsional
		(
			TOTAL_DIKLAT_FUNGSIONAL_ID, PEGAWAI_ID, JENJANG_KETERAMPILAN, JENJANG_AHLIPERTAMA
			, JENJANG_AHLIMUDA, JENJANG_AHLIMADYA, JENJANG_AHLIUTAMA
		) 
		VALUES 
		(
			".$this->getField("TOTAL_DIKLAT_FUNGSIONAL_ID")."
			, ".$this->getField("PEGAWAI_ID")."
			, ".$this->getField("JENJANG_KETERAMPILAN")."
			, ".$this->getField("JENJANG_AHLIPERTAMA")."
			, ".$this->getField("JENJANG_AHLIMUDA")."
			, ".$this->getField("JENJANG_AHLIMADYA")."
			, ".$this->getField("JENJANG_AHLIUTAMA")."
		)
		";

		$this->id = $this->getField("TOTAL_DIKLAT_FUNGSIONAL_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function totaldiklatfungsionalriwayatupdate()
	{
		$str = "		
		UPDATE data.total_diklat_fungsional
		SET    
		 	JENJANG_KETERAMPILAN= ".$this->getField("JENJANG_KETERAMPILAN")."
		 	, JENJANG_AHLIPERTAMA= ".$this->getField("JENJANG_AHLIPERTAMA")."
		 	, JENJANG_AHLIMUDA= ".$this->getField("JENJANG_AHLIMUDA")."
		 	, JENJANG_AHLIMADYA= ".$this->getField("JENJANG_AHLIMADYA")."
		 	, JENJANG_AHLIUTAMA= ".$this->getField("JENJANG_AHLIUTAMA")."
		WHERE PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
		"; 
		$this->query = $str;
		// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function totaldiklatfungsionalriwayatlast()
	{
		$str = "		
		UPDATE simpeg.pegawai
		SET    
		 	LAST_JENJANG_ID= ".$this->getField("LAST_JENJANG_ID")."
		WHERE PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
		"; 
		$this->query = $str;
		// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function selectbytotalfungsionalstruktural($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		A.*
		FROM data.total_diklat_fungsional A
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

	function totalpltplhinsert()
	{
		$this->setField("TOTAL_PLT_PLH_ID", $this->getNextId("TOTAL_PLT_PLH_ID", "data.TOTAL_PLT_PLH"));

		$str = "
		INSERT INTO data.TOTAL_PLT_PLH
		(
			TOTAL_PLT_PLH_ID, PEGAWAI_ID, TIPE, JUMLAH
		) 
		VALUES 
		(
			".$this->getField("TOTAL_PLT_PLH_ID")."
			, ".$this->getField("PEGAWAI_ID")."
			, ".$this->getField("TIPE")."
			, ".$this->getField("JUMLAH")."
		)
		";

		$this->id = $this->getField("TOTAL_PLT_PLH_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function totalpltplhupdate()
	{
		$str = "		
		UPDATE data.TOTAL_PLT_PLH
		SET    
		 	TIPE= ".$this->getField("TIPE")."
		 	, JUMLAH= ".$this->getField("JUMLAH")."
		WHERE TOTAL_PLT_PLH_ID = ".$this->getField("TOTAL_PLT_PLH_ID")."
		"; 
		$this->query = $str;
		// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function selectbytotalpltplh($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		A.*
		FROM data.TOTAL_PLT_PLH A
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
    
    function pltplhdelete()
	{
		$str = "		
		DELETE FROM data.TOTAL_PLT_PLH
		WHERE TOTAL_PLT_PLH_ID = ".$this->getField("TOTAL_PLT_PLH_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function totalkedudukantiminsert()
	{
		$this->setField("TOTAL_KEDUDUKAN_TIM_ID", $this->getNextId("TOTAL_KEDUDUKAN_TIM_ID", "data.total_kedudukan_tim")); 

		$str = "
		INSERT INTO data.total_kedudukan_tim
		(
			TOTAL_KEDUDUKAN_TIM_ID, PEGAWAI_ID, KETUA, ANGGOTA
		) 
		VALUES 
		(
			".$this->getField("TOTAL_KEDUDUKAN_TIM_ID")."
			, ".$this->getField("PEGAWAI_ID")."
			, ".$this->getField("KETUA")."
			, ".$this->getField("ANGGOTA")."
		)
		";

		$this->id = $this->getField("TOTAL_KEDUDUKAN_TIM_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function totalkedudukantimupdate()
	{
		$str = "		
		UPDATE data.total_kedudukan_tim
		SET
			KETUA= ".$this->getField("KETUA")."
			, ANGGOTA= ".$this->getField("ANGGOTA")."
		WHERE PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
		"; 
		$this->query = $str;
		// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function selectbytotalkedudukantim($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		A.*
		FROM data.total_kedudukan_tim A
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

	function selectbyparamsjabatansimpegdata($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="order by a.kode_jabatan")
	{
		$str = "
		SELECT
		A.*
		FROM simpeg.jabatan A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectbyparamsrumpunsimpegdata($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="order by a.rumpun_id")
	{
		$str = "
		SELECT
		A.*
		FROM simpeg.rumpun_jabatan A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
	}

	function rencanasuksesiinsert()
	{
		$this->setField("RENCANA_SUKSESI_ID", $this->getNextId("RENCANA_SUKSESI_ID", "data.rencana_suksesi"));

		$str = "
		INSERT INTO data.rencana_suksesi
		(
			RENCANA_SUKSESI_ID, NAMA
		) 
		VALUES 
		(
			".$this->getField("RENCANA_SUKSESI_ID")."
			, '".$this->getField("NAMA")."'
		)
		";

		$this->id = $this->getField("RENCANA_SUKSESI_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function rencanasuksesiupdate()
	{
		$str = "		
		UPDATE data.rencana_suksesi
		SET
		 	NAMA= '".$this->getField("NAMA")."'
		WHERE RENCANA_SUKSESI_ID = ".$this->getField("RENCANA_SUKSESI_ID")."
		"; 
		$this->query = $str;
		// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function rencanasuksesidelete()
	{
		$str = "		
		DELETE FROM data.rencana_suksesi
		WHERE RENCANA_SUKSESI_ID = ".$this->getField("RENCANA_SUKSESI_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function rencanasuksesidetildelete()
	{
		$str = "		
		DELETE FROM data.rencana_suksesi_detil
		WHERE RENCANA_SUKSESI_ID = ".$this->getField("RENCANA_SUKSESI_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function rencanasuksesidetilinsert()
	{
		$str = "
		INSERT INTO data.rencana_suksesi_detil
		(
			RENCANA_SUKSESI_ID, INFOKEY, INFOVAL
		) 
		VALUES 
		(
			".$this->getField("RENCANA_SUKSESI_ID")."
			, '".$this->getField("INFOKEY")."'
			, '".$this->getField("INFOVAL")."'
		)
		";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function selectbyparamsrencanasuksesi($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="order by a.rencana_suksesi_id")
	{
		$str = "
		SELECT
		A.*
		FROM data.rencana_suksesi A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectbyparamsrencanasuksesidetil($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="order by a.rencana_suksesi_id")
	{
		$str = "
		SELECT
		A.*
		FROM data.rencana_suksesi_detil A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectbyparamsrencanasuksesimonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="order by a.rencana_suksesi_id")
	{
		$str = "
		SELECT
			COALESCE(B1.JUMLAH,0) JUMLAH, B2.NAMA_JABATAN
			, A.*
		FROM data.rencana_suksesi A
		LEFT JOIN
		(
			SELECT RENCANA_SUKSESI_ID, COUNT(1) JUMLAH FROM data.rencana_suksesi_pegawai GROUP BY RENCANA_SUKSESI_ID
		) B1 ON A.RENCANA_SUKSESI_ID = B1.RENCANA_SUKSESI_ID
		LEFT JOIN
		(
			SELECT A.RENCANA_SUKSESI_ID, A.INFOVAL, B.NAMA_JABATAN
			FROM data.rencana_suksesi_detil A
			INNER JOIN simpeg.jabatan B ON B.JABATAN_ID = A.INFOVAL
			WHERE A.INFOKEY = 'jabatan'
		) B2 ON A.RENCANA_SUKSESI_ID = B2.RENCANA_SUKSESI_ID
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
	}

	function rencanasuksesidetilpegawaiinsert($statement='', $statementdetil='', $formulaid="")
	{
		$strd= "		
		DELETE FROM data.rencana_suksesi_pegawai
		WHERE RENCANA_SUKSESI_ID = ".$this->getField("RENCANA_SUKSESI_ID")."
		"; 
		$this->query = $strd;
		// echo $strd;exit;
		$this->execQuery($strd);

		if(empty($formulaid))
			$formulaid= "1";

		$str= "
		INSERT INTO data.rencana_suksesi_pegawai
		(
			RENCANA_SUKSESI_ID, PEGAWAI_ID, NILAI_X, NILAI_Y, ORDER_KUADRAN, PENILAIAN_ID
		)
		SELECT
			".$this->getField("RENCANA_SUKSESI_ID")." RENCANA_SUKSESI_ID
			, A.PEGAWAI_ID, A.NILAI_X, A.NILAI_Y, B.ORDER_KUADRAN, A.PENILAIAN_ID
		FROM
		(
			SELECT
			CAST
			(
				CASE WHEN
				COALESCE(X.NILAI,0) <= FTP.KUADRAN_X1 
				THEN '1'
				WHEN 
				COALESCE(X.NILAI,0) > FTP.KUADRAN_X1 AND COALESCE(X.NILAI,0) <= FTP.KUADRAN_X2
				THEN '2'
				ELSE '3' END
				||
				CASE 
				WHEN (COALESCE(Y.NILAI,0) >= 0) AND COALESCE(Y.NILAI,0) <= FTP.KUADRAN_Y1 THEN '1'
				WHEN (COALESCE(Y.NILAI,0) > FTP.KUADRAN_Y1) AND COALESCE(Y.NILAI,0) <= FTP.KUADRAN_Y2 THEN '2'
				ELSE '3' END
			AS INTEGER
			) KUADRAN_PEGAWAI_ID, PN.PENILAIAN_ID
			, CASE WHEN COALESCE(X.NILAI,0) > MAX_DATA_X THEN MAX_DATA_Y ELSE COALESCE(X.NILAI,0) END NILAI_X
			, CASE WHEN COALESCE(Y.NILAI,0) > MAX_DATA_Y THEN MAX_DATA_Y ELSE COALESCE(Y.NILAI,0) END NILAI_Y
			, A1.KODE PANGKAT_KODE, A1.NAMA PANGKAT_NAMA, A2.NAMA SATKER_NAMA
			, CASE WHEN A.LAST_ESELON_ID = 99 THEN A4.NAMA ELSE A3.NAMA END ESELON_NAMA
			, A.*
			FROM simpeg.pegawai A
			LEFT JOIN simpeg.pangkat A1 ON A1.PANGKAT_ID = A.LAST_PANGKAT_ID
			LEFT JOIN simpeg.satker A2 ON A.SATKER_ID = A2.SATKER_ID
			LEFT JOIN simpeg.eselon A3 ON A3.ESELON_ID = A.LAST_ESELON_ID
			LEFT JOIN simpeg.tipe_pegawai A4 ON A4.TIPE_PEGAWAI_ID = A.TIPE_PEGAWAI_ID
			INNER JOIN
			(
				select *
				from simpeg.pegawai_formula_penilaian a
				where 1=1 and formula_penilaian_id = ".$formulaid." and jenis_subindikator = 'jumlah_potensi'
			) X ON A.PEGAWAI_ID = X.PEGAWAI_ID
			LEFT JOIN
			(
				select *
				from simpeg.pegawai_formula_penilaian a
				where 1=1 and formula_penilaian_id = ".$formulaid." and jenis_subindikator = 'jumlah_kompetensi'
			) Y ON A.PEGAWAI_ID = Y.PEGAWAI_ID
			LEFT JOIN
			(
				SELECT FORMULA_PENILAIAN_ID
				, COALESCE(GM_X0,0) KUADRAN_Y0, COALESCE(GM_Y0,0) KUADRAN_Y1, COALESCE(GM_Y1,0) KUADRAN_Y2
				, COALESCE(SKP_Y0,0) KUADRAN_X0, COALESCE(SKP_X0,0) KUADRAN_X1, COALESCE(SKP_X1,0) KUADRAN_X2
				, COALESCE(GM_Y2,0) MAX_DATA_Y, COALESCE(SKP_X2,0) MAX_DATA_X
				FROM simpeg.formula_tp
			) FTP ON FTP.FORMULA_PENILAIAN_ID = X.FORMULA_PENILAIAN_ID
			LEFT JOIN
			(
				select replace(replace(array_agg(penilaian_id::text)::text, '{', ''), '}', '') penilaian_id, pegawai_id
				from penilaian a
				where 1=1
				and exists
				(
					select 1
					from
					(
					select pegawai_id, aspek_id, max(tanggal_tes) tanggal_tes from penilaian where aspek_id = '1' group by pegawai_id, aspek_id
					union
					select pegawai_id, aspek_id, max(tanggal_tes) tanggal_tes from penilaian where aspek_id = '2' group by pegawai_id, aspek_id
					) x
					where a.pegawai_id = x.pegawai_id and a.tanggal_tes = x.tanggal_tes and a.aspek_id = x.aspek_id
				)
				group by pegawai_id
			) PN ON A.PEGAWAI_ID = PN.PEGAWAI_ID
			WHERE 1=1 ".$statementdetil."
		) A
		LEFT JOIN
		(
			SELECT * FROM
			(
				SELECT 11 ID_KUADRAN, 'Tingkatkan Kompetensi' NAMA_KUADRAN, 'I' KODE_KUADRAN
				, 1 ORDER_KUADRAN
				UNION ALL
				SELECT 12 ID_KUADRAN, 'Tingkatkan Peran Saat Ini' NAMA_KUADRAN, 'II' KODE_KUADRAN
				, 2 ORDER_KUADRAN
				UNION ALL
				SELECT 21 ID_KUADRAN, 'Tingkatkan Peran Saat Ini' NAMA_KUADRAN, 'III' KODE_KUADRAN 
				, 3 ORDER_KUADRAN
				UNION ALL
				SELECT 13 ID_KUADRAN, 'Tingkatkan Peran Saat Ini' NAMA_KUADRAN, 'IV' KODE_KUADRAN 
				, 4 ORDER_KUADRAN
				UNION ALL
				SELECT 22 ID_KUADRAN, 'Siap Untuk Peran Masa Depan Dengan Pengembangan' NAMA_KUADRAN, 'V' KODE_KUADRAN 
				, 5 ORDER_KUADRAN
				UNION ALL
				SELECT 31 ID_KUADRAN, 'Pertimbangkan (Mutasi)' NAMA_KUADRAN, 'VI' KODE_KUADRAN 
				, 6 ORDER_KUADRAN
				UNION ALL
				SELECT 23 ID_KUADRAN, 'Siap Untuk Peran Masa Depan Dengan Pengembangan' NAMA_KUADRAN, 'VII' KODE_KUADRAN 
				, 7 ORDER_KUADRAN
				UNION ALL
				SELECT 32 ID_KUADRAN, 'Siap Untuk Peran Masa Depan Dengan Pengembangan' NAMA_KUADRAN, 'VIII' KODE_KUADRAN 
				, 8 ORDER_KUADRAN
				UNION ALL
				SELECT 33 ID_KUADRAN, 'Siap Untuk Peran Di Masa Depan' NAMA_KUADRAN, 'IX' KODE_KUADRAN 
				, 9 ORDER_KUADRAN
			) A
		) B ON A.KUADRAN_PEGAWAI_ID = B.ID_KUADRAN
		WHERE 1=1 ";

		$str .= $statement;
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}

	function selectbyparamsrencanasuksesipegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="order by RSP.RENCANA_SUKSESI_ID")
	{
		$str = "
		SELECT
			A1.KODE PANGKAT_KODE, A1.NAMA PANGKAT_NAMA, A2.NAMA SATKER_NAMA
			, CASE WHEN A.LAST_ESELON_ID = 99 THEN A4.NAMA ELSE A3.NAMA END ESELON_NAMA
			, RJ.RUMPUN_NAMA, RJ.RUMPUN_SUB_NAMA, BK.KODE_KUADRAN
			, RSP.*
			, A.*
		FROM simpeg.pegawai A
		LEFT JOIN simpeg.pangkat A1 ON A1.PANGKAT_ID = A.LAST_PANGKAT_ID
		LEFT JOIN simpeg.satker A2 ON A.SATKER_ID = A2.SATKER_ID
		LEFT JOIN simpeg.eselon A3 ON A3.ESELON_ID = A.LAST_ESELON_ID
		LEFT JOIN simpeg.tipe_pegawai A4 ON A4.TIPE_PEGAWAI_ID = A.TIPE_PEGAWAI_ID
		INNER JOIN data.rencana_suksesi_pegawai RSP ON A.PEGAWAI_ID = RSP.PEGAWAI_ID
		LEFT JOIN
		(
			SELECT A.JABATAN_ID, A.RUMPUN_ID, B1.NAMA RUMPUN_SUB_NAMA, A.RUMPUN_ID_PARENT, B2.NAMA RUMPUN_NAMA
			FROM simpeg.jabatan A
			INNER JOIN simpeg.rumpun_jabatan B1 ON A.RUMPUN_ID = B1.RUMPUN_ID
			INNER JOIN simpeg.rumpun_jabatan B2 ON A.RUMPUN_ID_PARENT = B2.RUMPUN_ID
		) RJ ON A.LAST_JABATAN_ID = RJ.JABATAN_ID
		LEFT JOIN
		(
			SELECT * FROM
			(
				SELECT 11 ID_KUADRAN, 'Tingkatkan Kompetensi' NAMA_KUADRAN, 'I' KODE_KUADRAN
				, 1 ORDER_KUADRAN
				UNION ALL
				SELECT 12 ID_KUADRAN, 'Tingkatkan Peran Saat Ini' NAMA_KUADRAN, 'II' KODE_KUADRAN
				, 2 ORDER_KUADRAN
				UNION ALL
				SELECT 21 ID_KUADRAN, 'Tingkatkan Peran Saat Ini' NAMA_KUADRAN, 'III' KODE_KUADRAN 
				, 3 ORDER_KUADRAN
				UNION ALL
				SELECT 13 ID_KUADRAN, 'Tingkatkan Peran Saat Ini' NAMA_KUADRAN, 'IV' KODE_KUADRAN 
				, 4 ORDER_KUADRAN
				UNION ALL
				SELECT 22 ID_KUADRAN, 'Siap Untuk Peran Masa Depan Dengan Pengembangan' NAMA_KUADRAN, 'V' KODE_KUADRAN 
				, 5 ORDER_KUADRAN
				UNION ALL
				SELECT 31 ID_KUADRAN, 'Pertimbangkan (Mutasi)' NAMA_KUADRAN, 'VI' KODE_KUADRAN 
				, 6 ORDER_KUADRAN
				UNION ALL
				SELECT 23 ID_KUADRAN, 'Siap Untuk Peran Masa Depan Dengan Pengembangan' NAMA_KUADRAN, 'VII' KODE_KUADRAN 
				, 7 ORDER_KUADRAN
				UNION ALL
				SELECT 32 ID_KUADRAN, 'Siap Untuk Peran Masa Depan Dengan Pengembangan' NAMA_KUADRAN, 'VIII' KODE_KUADRAN 
				, 8 ORDER_KUADRAN
				UNION ALL
				SELECT 33 ID_KUADRAN, 'Siap Untuk Peran Di Masa Depan' NAMA_KUADRAN, 'IX' KODE_KUADRAN 
				, 9 ORDER_KUADRAN
			) A
		) BK ON BK.ORDER_KUADRAN = RSP.ORDER_KUADRAN
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectspiderpotensikompetensi($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		A.ATRIBUT_ID, A.ASPEK_ID, A.NAMA, B.NILAI, B.PENILAIAN_ID
		, B1.NILAI_STANDAR, B2.JADWAL_TES_ID
		FROM atribut A
		LEFT JOIN penilaian_detil B ON A.ATRIBUT_ID = B.ATRIBUT_ID
		LEFT JOIN penilaian B2 ON B.PENILAIAN_ID = B2.PENILAIAN_ID
		INNER JOIN formula_atribut B1 ON B1.FORMULA_ATRIBUT_ID = B.FORMULA_ATRIBUT_ID
		INNER JOIN level_atribut LA ON LA.LEVEL_ID = B1.LEVEL_ID
		WHERE A.ATRIBUT_ID_PARENT NOT IN ('0')
		AND B.PENILAIAN_ID IS NOT NULL
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectpenilaianrekomendasi($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.PENILAIAN_REKOMENDASI_ID ASC")
	{
		$str = "
		SELECT A.*
		FROM penilaian_rekomendasi A
		WHERE 1=1
		";

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectbyidAdmin($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		select * from user_app
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
} 
?>