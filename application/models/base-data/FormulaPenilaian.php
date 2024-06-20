<? 
include_once(APPPATH.'/models/Entity.php');

class FormulaPenilaian extends Entity{ 

	var $query;

	function FormulaPenilaian()
	{
		$this->Entity(); 
	}

	function insertformulapenilaian()
	{
		$this->setField("FORMULA_PENILAIAN_ID", $this->getNextId("FORMULA_PENILAIAN_ID","simpeg.formula_penilaian")); 

		$str = "
		INSERT INTO simpeg.formula_penilaian
		(
			FORMULA_PENILAIAN_ID, PERMEN_INDIKATOR_ID, NAMA
		) 
		VALUES 
		(
			".$this->getField("FORMULA_PENILAIAN_ID")."
			, (SELECT PERMEN_INDIKATOR_ID FROM simpeg.permen_indikator WHERE STATUS = '1')
			, '".$this->getField("NAMA")."'
		)
		";

		$this->id = $this->getField("FORMULA_PENILAIAN_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updateformulapenilaian()
	{
		$str = "		
		UPDATE simpeg.formula_penilaian
		SET    
		 	NAMA= '".$this->getField("NAMA")."'
		WHERE FORMULA_PENILAIAN_ID = ".$this->getField("FORMULA_PENILAIAN_ID")."
		"; 
		$this->query = $str;
		// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function insertnineboxstandart()
	{
		$str = "
		INSERT INTO simpeg.formula_tp
		(
			FORMULA_PENILAIAN_ID, SKP_X0, GM_Y0, SKP_X1, GM_Y1, SKP_X2, GM_Y2
			, SKP_Y0, GM_X0, SKP_Y1, GM_X1, SKP_Y2, GM_X2
		)
		VALUES 
		(
			".$this->getField("FORMULA_PENILAIAN_ID")."
			, ".$this->getField("SKP_X0")."
			, ".$this->getField("GM_Y0")."
			, ".$this->getField("SKP_X1")."
			, ".$this->getField("GM_Y1")."
			, ".$this->getField("SKP_X2")."
			, ".$this->getField("GM_Y2")."
			, 0, 0, 0, 0, 0, 0
		)"; 
				
		$this->query = $str;
		// echo $str;exit();
		// $this->id = $this->getField("TAHUN");
		return $this->execQuery($str);
    }
	
    function updatenineboxstandart()
	{
		$str = "
		UPDATE simpeg.formula_tp
		SET
			SKP_X0= ".$this->getField("SKP_X0")."
			, GM_Y0= ".$this->getField("GM_Y0")."
			, SKP_X1= ".$this->getField("SKP_X1")."
			, GM_Y1= ".$this->getField("GM_Y1")."
			, SKP_X2= ".$this->getField("SKP_X2")."
			, GM_Y2= ".$this->getField("GM_Y2")."
		WHERE FORMULA_PENILAIAN_ID= '".$this->getField("FORMULA_PENILAIAN_ID")."'
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function deleteformulapenilaianbobot()
	{
		$str = "		
		DELETE FROM simpeg.formula_penilaian_bobot
		WHERE FORMULA_PENILAIAN_ID = ".$this->getField("FORMULA_PENILAIAN_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function insertformulapenilaianbobot()
	{
		$str = "
		INSERT INTO simpeg.formula_penilaian_bobot
		(
			FORMULA_PENILAIAN_ID, PERMEN_INDIKATOR_ID, INDIKATOR_PENILAIAN_ID, SUB_INDIKATOR_ID, NILAI,JENIS_SUBINDIKATOR
		) 
		VALUES 
		(
			".$this->getField("FORMULA_PENILAIAN_ID")."
			, (SELECT PERMEN_INDIKATOR_ID FROM simpeg.formula_penilaian WHERE FORMULA_PENILAIAN_ID = ".$this->getField("FORMULA_PENILAIAN_ID").")
			, ".$this->getField("INDIKATOR_PENILAIAN_ID")."
			, ".$this->getField("SUB_INDIKATOR_ID")."
			, ".$this->getField("NILAI")."
			, '".$this->getField("JENIS_SUBINDIKATOR")."'
		)
		";

		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function deleteformulapenilaianvalue()
	{
		$str = "		
		DELETE FROM simpeg.formula_penilaian_value
		WHERE FORMULA_PENILAIAN_ID = ".$this->getField("FORMULA_PENILAIAN_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function insertformulapenilaianvalue()
	{
		$str = "
		INSERT INTO simpeg.formula_penilaian_value
		(
			FORMULA_PENILAIAN_ID, PERMEN_INDIKATOR_ID, INDIKATOR_PENILAIAN_ID, SUB_INDIKATOR_ID
			, SUB_INDIKATOR_DETIL_ID, NILAI,JENIS_SUBINDIKATOR
		) 
		VALUES 
		(
			".$this->getField("FORMULA_PENILAIAN_ID")."
			, (SELECT PERMEN_INDIKATOR_ID FROM simpeg.formula_penilaian WHERE FORMULA_PENILAIAN_ID = ".$this->getField("FORMULA_PENILAIAN_ID").")
			, ".$this->getField("INDIKATOR_PENILAIAN_ID")."
			, ".$this->getField("SUB_INDIKATOR_ID")."
			, ".$this->getField("SUB_INDIKATOR_DETIL_ID")."
			, ".$this->getField("NILAI")."
			, '".$this->getField("JENIS_SUBINDIKATOR")."'
		)
		";

		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

	function selectbyparamsformulapenilaian($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
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

	function selectbyparamsformulapenilaiannineboxstandart($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		A.*
		FROM simpeg.formula_tp A
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

	function selectbyparamsformulapenilaianbobot($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		A.*
		FROM simpeg.formula_penilaian_bobot A
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

	function selectbyparamsformulapenilaianvalue($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		A.*
		FROM simpeg.formula_penilaian_value A
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

	function selectbyindikatorpenilaian($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.INDIKATOR_PENILAIAN_ID")
	{
		$str = "
		SELECT
		A.*
		FROM simpeg.indikator_penilaian A
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

	function selectbyindikatorpenilaiansub($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.INDIKATOR_PENILAIAN_ID, A.SUB_INDIKATOR_ID")
	{
		$str = "
		SELECT
		A.*
		FROM simpeg.indikator_penilaian_sub A
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

	function selectbyindikatorpenilaianvalue($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.INDIKATOR_PENILAIAN_ID, A.SUB_INDIKATOR_ID, A.SUB_INDIKATOR_DETIL_ID")
	{
		$str = "
		SELECT
		A.*
		FROM simpeg.indikator_penilaian_value A
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

	function ppetapegawai()
    {
        $str = "
        SELECT simpeg.ppetapegawai(".$this->getField("ID").")
        "; 
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

    function ppetapegawaiCat()
    {
        $str = "
        SELECT simpeg.passakhir(".$this->getField("ID").")
        "; 
        $this->query = $str;
        // echo $str;exit();
        return $this->execQuery($str);
    }

	function selectbyparamspegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		A1.KODE PANGKAT_KODE, A1.NAMA PANGKAT_NAMA, A2.NAMA SATKER_NAMA
		, VPOTENSI, VKOMPETENSI, VPENDIDIKAN_FORMAL, vkinerja, vpenghargaan, vriwayat_hukdis
		, JUMLAH_POTENSI
		, VSKP, JUMLAH_KINERJA
		, A.*
		FROM simpeg.pegawai A
		LEFT JOIN simpeg.pangkat A1 ON A1.PANGKAT_ID = A.LAST_PANGKAT_ID
		LEFT JOIN simpeg.satker A2 ON A.SATKER_ID = A2.SATKER_ID
		INNER JOIN
		(
			select
			a.*
			from
			(
				select 
				a.pegawai_id, a.formula_penilaian_id
				, simpeg.pembulatan(a.potensi) vpotensi
				, simpeg.pembulatan(a.kompetensi) vkompetensi
				, simpeg.pembulatan(a.pendidikan_formal) vpendidikan_formal
				, simpeg.pembulatan(a.jumlah_potensi) jumlah_potensi
				, simpeg.pembulatan(a.kinerja) vkinerja
				, simpeg.pembulatan(a.penghargaan) vpenghargaan
				, a.riwayat_hukdis vriwayat_hukdis
				, simpeg.pembulatan(a.jumlah_kompetensi) JUMLAH_KINERJA
				, simpeg.pembulatan(a.skp) vskp
				from
				(
					select
					a.pegawai_id, a.formula_penilaian_id
					, sum(case when jenis_subindikator = 'potensi' then a.nilai else 0 end) potensi
					, sum(case when jenis_subindikator = 'kompetensi' then a.nilai else 0 end) kompetensi
					, sum(case when jenis_subindikator = 'pendidikan_formal' then a.nilai else 0 end) pendidikan_formal
					, sum(case when jenis_subindikator = 'jumlah_potensi' then a.nilai else 0 end) jumlah_potensi
					, sum(case when jenis_subindikator = 'kinerja' then a.nilai else 0 end) kinerja
					, sum(case when jenis_subindikator = 'penghargaan' then a.nilai else 0 end) penghargaan
					, sum(case when jenis_subindikator = 'riwayat_hukdis'  then a.nilai else 0 end) riwayat_hukdis
					, sum(case when jenis_subindikator = 'jumlah_kompetensi' then a.nilai else 0 end) jumlah_kompetensi
					, sum(case when jenis_subindikator = 'skp' then a.nilai else 0 end) skp
					from simpeg.pegawai_formula_penilaian a
					group by a.pegawai_id, a.formula_penilaian_id
				) a
			) a
			where 1=1
		) FP ON A.PEGAWAI_ID = FP.PEGAWAI_ID
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

	function selectpotensikompetensigrafik($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="", $formulaid="")
	{
		if(empty($formulaid))
			$formulaid= "1";

		$str = "
		SELECT
			A.PEGAWAI_ID, A.NAMA
			, CASE WHEN COALESCE(X.NILAI,0) > MAX_DATA_X THEN MAX_DATA_Y ELSE COALESCE(X.NILAI,0) END NILAI_X
			, CASE WHEN COALESCE(Y.NILAI,0) > MAX_DATA_Y THEN MAX_DATA_Y ELSE COALESCE(Y.NILAI,0) END NILAI_Y
		FROM simpeg.pegawai A
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
			SELECT FORMULA_PENILAIAN_ID, COALESCE(GM_Y2,0) MAX_DATA_Y, COALESCE(SKP_X2,0) MAX_DATA_X
			FROM simpeg.formula_tp
		) FTP ON FTP.FORMULA_PENILAIAN_ID = X.FORMULA_PENILAIAN_ID
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

	function selectpotensikompetensitable($paramsArray=array(),$limit=-1,$from=-1, $statement='', $statementdetil= "", $sOrder="", $formulaid="")
	{
		if(empty($formulaid))
			$formulaid= "1";

		$str= "
		SELECT
		A.*, COALESCE(B.JUMLAH_PEGAWAI,0) JUMLAH
		FROM
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
		) A
		LEFT JOIN
		(
			SELECT
				COUNT(1) JUMLAH_PEGAWAI, A.KUADRAN_PEGAWAI KUADRAN_PEGAWAI_ID
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
				) KUADRAN_PEGAWAI
				, A.PEGAWAI_ID, A.NAMA
				, CASE WHEN COALESCE(X.NILAI,0) > MAX_DATA_X THEN MAX_DATA_Y ELSE COALESCE(X.NILAI,0) END NILAI_X
				, CASE WHEN COALESCE(Y.NILAI,0) > MAX_DATA_Y THEN MAX_DATA_Y ELSE COALESCE(Y.NILAI,0) END NILAI_Y
				FROM simpeg.pegawai A
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
				WHERE 1=1
				".$statement."
			) A
			GROUP BY A.KUADRAN_PEGAWAI
		) B ON B.KUADRAN_PEGAWAI_ID = A.ID_KUADRAN 
		WHERE 1=1 ";

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statementdetil." ".$sOrder;
		$this->query = $str;
		// echo $str;exit;
				
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectbyparamskuadranpegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $statementdetil='', $sOrder="", $formulaid="")
	{
		if(empty($formulaid))
			$formulaid= "1";

		$str= "
		SELECT
			*
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
			) KUADRAN_PEGAWAI_ID
			, CASE WHEN simpeg.pembulatan(COALESCE(X.NILAI,0)) > MAX_DATA_X THEN MAX_DATA_Y ELSE simpeg.pembulatan(COALESCE(X.NILAI,0)) END NILAI_X
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

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectnilairangkuman($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		select
		a.*
		from
		(
			select 
			a.pegawai_id, a.formula_penilaian_id
			, simpeg.pembulatan(a.potensi) vpotensi
			, simpeg.pembulatan(a.kompetensi) vkompetensi
			, simpeg.pembulatan(a.pendidikan_formal) vpendidikan_formal
			, simpeg.pembulatan(a.kinerja) vpelatihan
			, simpeg.pembulatan(a.penghargaan) vjabatan
			, simpeg.pembulatan(a.riwayat_hukdis) vkomitmenorganisasi
			from
			(
				select
				a.pegawai_id, a.formula_penilaian_id
				, sum(case when jenis_subindikator = 'potensi' then a.nilai else 0 end) potensi
				, sum(case when jenis_subindikator = 'kompetensi' then a.nilai else 0 end) kompetensi
				, sum(case when jenis_subindikator = 'pendidikan_formal' then a.nilai else 0 end) pendidikan_formal
				, sum(case when jenis_subindikator = 'kinerja' then a.nilai else 0 end) kinerja
				, sum(case when jenis_subindikator = 'penghargaan' then a.nilai else 0 end) penghargaan
				, sum(case when jenis_subindikator = 'riwayat_hukdis' then a.nilai else 0 end) riwayat_hukdis
				from simpeg.pegawai_formula_penilaian a
				group by a.pegawai_id, a.formula_penilaian_id
			) a
		) a
		where 1=1
		--and a.pegawai_id = 5908
		--and a.formula_penilaian_id = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
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
		LEFT JOIN formula_atribut B1 ON B1.FORMULA_ATRIBUT_ID = B.FORMULA_ATRIBUT_ID
		LEFT JOIN level_atribut LA ON LA.LEVEL_ID = B1.LEVEL_ID
		WHERE A.ATRIBUT_ID_PARENT NOT IN ('0')
		AND B.PENILAIAN_ID IS NOT NULL
		--AND B.PEGAWAI_ID = 5908
		AND B.PENILAIAN_ID IN
		(
			SELECT PENILAIAN_ID
			FROM penilaian A
			WHERE 1=1
			AND EXISTS
			(
				SELECT 1
				FROM
				(
				SELECT PEGAWAI_ID, ASPEK_ID, MAX(TANGGAL_TES) TANGGAL_TES FROM penilaian WHERE ASPEK_ID = '1' GROUP BY PEGAWAI_ID, ASPEK_ID
				UNION
				SELECT PEGAWAI_ID, ASPEK_ID, MAX(TANGGAL_TES) TANGGAL_TES FROM penilaian WHERE ASPEK_ID = '2' GROUP BY PEGAWAI_ID, ASPEK_ID
				) X
				WHERE A.PEGAWAI_ID = X.PEGAWAI_ID AND A.TANGGAL_TES = X.TANGGAL_TES AND A.ASPEK_ID = X.ASPEK_ID
			)
		)
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectstr($str='')
	{
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
	}

	function getformulapenilaianpendidikan($formulapenilaianid, $pegawaiid="")
	{
		$str = "
		select simpeg.getformulapenilaianpendidikan('".$formulapenilaianid."'::character varying, '".$pegawaiid."'::character varying) INFODATA
		";

		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow())
			return $this->getField("INFODATA");
		else 
			return "";
    }

    function getformulapenilaianjabatan($formulapenilaianid, $pegawaiid="")
	{
		$str = "
		select simpeg.getformulapenilaianjabatan('".$formulapenilaianid."'::character varying, '".$pegawaiid."'::character varying) INFODATA
		";

		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow())
			return $this->getField("INFODATA");
		else 
			return "";
    }

    function getformulapendidikanpelatihan($formulapenilaianid, $pegawaiid="")
	{
		$str = "
		select simpeg.getformulapendidikanpelatihan('".$formulapenilaianid."'::character varying, '".$pegawaiid."'::character varying) INFODATA
		";

		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow())
			return $this->getField("INFODATA");
		else 
			return "";
    }

    function getformulaketerlibatantim($formulapenilaianid, $pegawaiid="")
	{
		$str = "
		select simpeg.getformulaketerlibatantim('".$formulapenilaianid."'::character varying, '".$pegawaiid."'::character varying) INFODATA
		";

		$this->query = $str;
		// echo $str;exit;
		$this->select($str); 
		if($this->firstRow())
			return $this->getField("INFODATA");
		else 
			return "";
    }

} 
?>