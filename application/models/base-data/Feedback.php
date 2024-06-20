<? 
include_once(APPPATH.'/models/Entity.php');

class Feedback extends Entity{ 

	var $query;

	function Feedback()
	{
		$this->Entity(); 
	}

	function selectByParamsJumlahAsesorPegawai($statement='', $asesorId="", $sOrder="")
	{
		$str = "
		SELECT A.TANGGAL_TES, COALESCE(JUMLAH,0) JUMLAH
		FROM
		(
			SELECT A.TANGGAL_TES, COUNT(1) JUMLAH
			FROM
			(
				SELECT
				JT.TANGGAL_TES, B.PEGAWAI_ID
				FROM jadwal_asesor A
				INNER JOIN jadwal_pegawai B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
				INNER JOIN jadwal_tes JT ON A.JADWAL_TES_ID = JT.JADWAL_TES_ID
				INNER JOIN formula_eselon FE ON FE.FORMULA_ESELON_ID = JT.FORMULA_ESELON_ID
				INNER JOIN formula_assesment FA ON FA.FORMULA_ID = FE.FORMULA_ID
				WHERE  A.ASESOR_ID = ".$asesorId."
				GROUP BY JT.TANGGAL_TES, B.PEGAWAI_ID
			) A
			GROUP BY A.TANGGAL_TES
		) A
		WHERE 1=1
		"; 
		
		$str .= $sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str, -1, -1); 
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY ASESOR_ID ASC")
	{
		$str = "SELECT ASESOR_ID, NAMA, ALAMAT, EMAIL, TELEPON, NO_SK, TIPE, CASE TIPE WHEN '1' THEN 'Internal' WHEN '2' THEN 'Eksternal' ELSE '' END TIPE_NAMA, STATUS_AKTIF, CASE WHEN STATUS_AKTIF = '1' THEN 'Ya' ELSE 'Tidak' END STATUS_KET
				FROM asesor A WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsJadwalTes($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY JADWAL_TES_ID ASC")
	{
		$str = "SELECT JADWAL_TES_ID, TANGGAL_TES, BATCH, ACARA, TEMPAT, ALAMAT, KETERANGAN, STATUS_PENILAIAN
				, STATUS_VALID, TTD_ASESOR, TTD_PIMPINAN, NIP_ASESOR, NIP_PIMPINAN, TTD_TANGGAL
				FROM jadwal_tes WHERE JADWAL_TES_ID IS NOT NULL"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsDataAsesorPegawaiSuper($statement='', $asesorId="", $sOrder=" ORDER BY JA.NOMOR_URUT")
	{
		$str = "
		SELECT
		a.last_eselon_id,A.PEGAWAI_ID, A.NAMA NAMA_PEGAWAI, A.NIP_BARU, JA.JADWAL_TES_ID, JA.asesor_id
		, JA.NOMOR_URUT NOMOR_URUT_GENERATE
		FROM simpeg.pegawai A
		INNER JOIN
		(
			SELECT A.*,x.TANGGAL_TES,x.asesor_id
			FROM
			(
				SELECT
				A.JADWAL_TES_ID, JT.TANGGAL_TES, B.PEGAWAI_ID,a.asesor_id
				FROM jadwal_asesor A
				INNER JOIN jadwal_pegawai B ON A.JADWAL_ASESOR_ID = B.JADWAL_ASESOR_ID
				INNER JOIN jadwal_tes JT ON A.JADWAL_TES_ID = JT.JADWAL_TES_ID
				INNER JOIN formula_eselon FE ON FE.FORMULA_ESELON_ID = JT.FORMULA_ESELON_ID
				INNER JOIN formula_assesment FA ON FA.FORMULA_ID = FE.FORMULA_ID
				GROUP BY A.JADWAL_TES_ID, JT.TANGGAL_TES, B.PEGAWAI_ID, a.asesor_id
			) X
			INNER JOIN
			(
				SELECT a.no_urut NOMOR_URUT, A.PEGAWAI_ID, A.LAST_UPDATE_DATE
				, JADWAL_TES_ID
				FROM jadwal_awal_tes_simulasi_pegawai A
				INNER JOIN jadwal_tes B ON JADWAL_AWAL_TES_SIMULASI_ID = JADWAL_TES_ID
			) A ON A.JADWAL_TES_ID = X.JADWAL_TES_ID AND A.PEGAWAI_ID = X.PEGAWAI_ID
			WHERE 1=1
		) JA ON JA.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1
		".$statement; 
		
		$str .= $sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str, -1, -1); 
    }

    function selectByParamsIdentitasAsesor($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT
		* from user_app
		WHERE 1=1
		".$statement; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str, -1, -1); 
    }

    function selectByParamsPenilaianAsesor($statement='', $sOrder="ORDER BY ur.urut, A.ASPEK_ID , A.ATRIBUT_ID asc")
	{
		$str = "
		SELECT
			A.PENILAIAN_ID, A.PENILAIAN_DETIL_ID, A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT, A.NAMA, A.ATRIBUT_GROUP
			, A.NILAI_STANDAR, A.NILAI, A.GAP
			, CASE WHEN A.PROSENTASE > 100 THEN 100 ELSE A.PROSENTASE END PROSENTASE
			, STRIP_TAGS(A.BUKTI) BUKTI, STRIP_TAGS(A.CATATAN) CATATAN
			, A.LEVEL, A.LEVEL_KETERANGAN, A.JADWAL_TES_ID, A.PEGAWAI_ID, A.ASPEK_ID, A.CATATAN_STRENGTH,A.CATATAN_WEAKNES,A.KESIMPULAN,A.SARAN_PENGEMBANGAN,A.SARAN_PENEMPATAN,A.PROFIL_KEPRIBADIAN,A.KESESUAIAN_RUMPUN,A.RINGKASAN_PROFIL_KOMPETENSI,UR.URUT
			,a.FORMULA_ESELON_ID
		FROM 
		(
			SELECT A.PENILAIAN_ID, B.PENILAIAN_DETIL_ID, C.ATRIBUT_ID, C.ATRIBUT_ID_PARENT, C.NAMA, C.ATRIBUT_ID_PARENT ATRIBUT_GROUP
			, B1.NILAI_STANDAR
			, LA.LEVEL, LA.KETERANGAN LEVEL_KETERANGAN
			, CASE WHEN B.NILAI IS NULL THEN 3 ELSE B.NILAI END NILAI, COALESCE(B.GAP,0) GAP, B.BUKTI, B.CATATAN
			, ROUND((B.NILAI / B1.NILAI_STANDAR) * 100,2) PROSENTASE
			, B.PERMEN_ID, A.JADWAL_TES_ID, A.PEGAWAI_ID, A.ASPEK_ID,A.CATATAN_STRENGTH,A.CATATAN_WEAKNES,A.KESIMPULAN,A.SARAN_PENGEMBANGAN,A.SARAN_PENEMPATAN,A.PROFIL_KEPRIBADIAN,A.KESESUAIAN_RUMPUN,A.RINGKASAN_PROFIL_KOMPETENSI
			,b1.FORMULA_ESELON_ID
			FROM penilaian A
			LEFT JOIN penilaian_detil B ON A.PENILAIAN_ID = B.PENILAIAN_ID
			LEFT JOIN formula_atribut B1 ON B1.FORMULA_ATRIBUT_ID = B.FORMULA_ATRIBUT_ID
			LEFT JOIN atribut C ON B.ATRIBUT_ID = C.ATRIBUT_ID AND B.PERMEN_ID = C.PERMEN_ID
			LEFT JOIN level_atribut LA ON LA.LEVEL_ID = B1.LEVEL_ID
			WHERE 1=1
			UNION ALL
			SELECT B.PENILAIAN_ID, NULL PENILAIAN_DETIL_ID, A.ATRIBUT_ID, A.ATRIBUT_ID_PARENT, A.NAMA, A.ATRIBUT_ID ATRIBUT_GROUP
			, NULL NILAI_STANDAR
			, NULL AS LEVEL, '' LEVEL_KETERANGAN
			, NULL NILAI, NULL GAP, '' BUKTI, '' CATATAN
			, B.PROSENTASE, A.PERMEN_ID, B.JADWAL_TES_ID, B.PEGAWAI_ID, B.ASPEK_ID,B.CATATAN_STRENGTH,B.CATATAN_WEAKNES,B.KESIMPULAN,B.SARAN_PENGEMBANGAN,B.SARAN_PENEMPATAN,B.PROFIL_KEPRIBADIAN,B.KESESUAIAN_RUMPUN,B.RINGKASAN_PROFIL_KOMPETENSI
			,b.FORMULA_ESELON_ID
			FROM atribut A
			LEFT JOIN
			(
				SELECT B.PENILAIAN_ID, SUBSTR(B.ATRIBUT_ID, 1, 2) ATRIBUT_ID, COUNT(1) JUMLAH_PENILAIAN_DETIL
				, ROUND((SUM(B.NILAI) / SUM(B1.NILAI_STANDAR)) * 100,2) PROSENTASE, PERMEN_ID, B2.JADWAL_TES_ID, B2.PEGAWAI_ID, B2.ASPEK_ID,B2.CATATAN_STRENGTH,B2.CATATAN_WEAKNES,B2.KESIMPULAN,B2.SARAN_PENGEMBANGAN,B2.SARAN_PENEMPATAN,B2.PROFIL_KEPRIBADIAN,B2.KESESUAIAN_RUMPUN,B2.RINGKASAN_PROFIL_KOMPETENSI,b1.FORMULA_ESELON_ID
				FROM penilaian_detil B
				LEFT JOIN formula_atribut B1 ON B1.FORMULA_ATRIBUT_ID = B.FORMULA_ATRIBUT_ID
				LEFT JOIN penilaian B2 ON B.PENILAIAN_ID = B2.PENILAIAN_ID
				WHERE 1=1
				GROUP BY B.PENILAIAN_ID, SUBSTR(B.ATRIBUT_ID, 1, 2), PERMEN_ID, B2.JADWAL_TES_ID, B2.PEGAWAI_ID, B2.ASPEK_ID,B2.CATATAN_STRENGTH,B2.CATATAN_WEAKNES,B2.KESIMPULAN,B2.SARAN_PENGEMBANGAN,B2.SARAN_PENEMPATAN,B2.PROFIL_KEPRIBADIAN,B2.KESESUAIAN_RUMPUN,B2.RINGKASAN_PROFIL_KOMPETENSI,b1.FORMULA_ESELON_ID
			) B ON A.ATRIBUT_ID = B.ATRIBUT_ID AND A.PERMEN_ID = B.PERMEN_ID
			WHERE 1=1
		) A
		LEFT JOIN 
		( 
			SELECT * FROM formula_assesment_atribut_urutan
		) UR ON a.ATRIBUT_ID = UR.ATRIBUT_ID AND UR.PERMEN_ID = a.PERMEN_ID and  UR.FORMULA_ESELON_ID = a.FORMULA_ESELON_ID
		WHERE 1=1
		".$statement;
		// AND A.ASESOR_ID = 25 AND A.JADWAL_TES_ID = 25
		
		$str .= " ".$sOrder;
		$this->query = $str;
		// echo $str;exit();
				
		return $this->selectLimit($str, -1, -1); 
    }

    function selectByParamsCatatan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY A.PENILAIAN_REKOMENDASI_ID ASC")
	{
		$str = "
		SELECT A.PENILAIAN_REKOMENDASI_ID, A.PEGAWAI_ID, A.JADWAL_TES_ID, A.KETERANGAN,A.TIPE,A.NO_URUT
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

    function insert()
    {
      $this->setField("feedback_id", $this->getNextId("feedback_id","feedback")); 

      $str = "INSERT INTO feedback (
             feedback_id, asesor_id, pegawai_id, jadwal_tes_id, keterangan, harapan, saran_pengembangan, quotes, harapan_instansi)
          VALUES (
            ".$this->getField("feedback_id").",
            ".$this->getField("asesor_id").",
            ".$this->getField("pegawai_id").",
            ".$this->getField("jadwal_tes_id").",
            '".$this->getField("keterangan")."',
            '".$this->getField("harapan")."',
            '".$this->getField("saran_pengembangan")."',
            '".$this->getField("quotes")."',
            '".$this->getField("harapan_instansi")."'
          )"; 
          
		$this->id = $this->getField("feedback_id");
      	$this->query = $str;
      	// echo $str;exit;
      	return $this->execQuery($str);
    }

    function update()
	{
		$str = "		
		UPDATE feedback
		SET    
		 	keterangan= '".$this->getField("keterangan")."',
		 	harapan= '".$this->getField("harapan")."',
		 	saran_pengembangan= '".$this->getField("saran_pengembangan")."',
		 	quotes= '".$this->getField("quotes")."',
		 	harapan_instansi= '".$this->getField("harapan_instansi")."'
		WHERE feedback_id = ".$this->getField("feedback_id")."
		"; 
		$this->query = $str;
		// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function selectByParamsFeedback($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="")
	{
		$str = "
		SELECT * FROM feedback A
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

     function delete()
	{
		$str = "		
		DELETE FROM feedback_pengembangan_diri
		WHERE feedback_id = ".$this->getField("feedback_id")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function insertDetil()
    {
      $this->setField("feedback_pengembangan_diri_id", $this->getNextId("feedback_pengembangan_diri_id","feedback_pengembangan_diri")); 

      $str = "INSERT INTO feedback_pengembangan_diri (
             feedback_pengembangan_diri_id, feedback_id, keterangan, Urut)
          VALUES (
            ".$this->getField("feedback_pengembangan_diri_id").",
            ".$this->getField("feedback_id").",
            '".$this->getField("keterangan")."',
            '".$this->getField("Urut")."'
          )"; 
          
      $this->query = $str;
      // echo $str;exit;
      return $this->execQuery($str);
    }

  function selectByParamsDetilFeedback($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY urut ASC")
	{
		$str = "
		SELECT *
		FROM feedback_pengembangan_diri A
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

  function selectByParamsAsesor($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY ASESOR_ID ASC")
	{
		$str = "SELECT ASESOR_ID, a.NAMA, a.ALAMAT, a.EMAIL, a.TELEPON, NO_SK, TIPE, CASE TIPE WHEN '1' THEN 'Internal' WHEN '2' THEN 'Eksternal' ELSE '' END TIPE_NAMA, STATUS_AKTIF, CASE WHEN STATUS_AKTIF = '1' THEN 'Ya' ELSE 'Tidak' END STATUS_KET
				FROM asesor A 
				left join user_app b on a. asesor_id = b.pegawai_id 
				WHERE 1=1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$sOrder;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
    function selectByParamsJadwal($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder="ORDER BY JADWAL_TES_ID ASC")
	{
		$str = "
		SELECT A.JADWAL_TES_ID, A.TANGGAL_TES, A.BATCH, A.ACARA, A.TEMPAT, A.ALAMAT, A.KETERANGAN, A.STATUS_PENILAIAN
		, A.FORMULA_ESELON_ID, D.FORMULA || ' untuk (' || COALESCE((C.NOTE || ' ' || C.NAMA), C.NAMA) || ')' NAMA_FORMULA_ESELON
		, COALESCE(JUMLAH_ASESOR,0) JUMLAH_ASESOR, COALESCE(JUMLAH_PEGAWAI,0) JUMLAH_PEGAWAI
		, A.JUMLAH_RUANGAN, A.STATUS_VALID, A.TTD_ASESOR, A.TTD_PIMPINAN, A.NIP_ASESOR, A.NIP_PIMPINAN
		, A.TTD_TANGGAL,A.LINK_SOAL
		FROM jadwal_tes A
		INNER JOIN formula_eselon B ON A.FORMULA_ESELON_ID = B.FORMULA_ESELON_ID
		INNER JOIN eselon C ON C.ESELON_ID = B.ESELON_ID
		INNER JOIN formula_assesment D ON D.FORMULA_ID = B.FORMULA_ID
		LEFT JOIN
		(
			SELECT A.JADWAL_TES_ID, COUNT(A.ASESOR_ID) JUMLAH_ASESOR
			FROM
			(
			SELECT A.JADWAL_TES_ID, A.ASESOR_ID
			FROM jadwal_tes_simulasi_asesor A
			GROUP BY A.JADWAL_TES_ID, A.ASESOR_ID
			) A
			GROUP BY A.JADWAL_TES_ID
		) JML_ASESOR ON JML_ASESOR.JADWAL_TES_ID = A.JADWAL_TES_ID
		LEFT JOIN
		(
		SELECT A.JADWAL_TES_ID, COUNT(A.PEGAWAI_ID) JUMLAH_PEGAWAI
		FROM jadwal_tes_simulasi_pegawai A
		GROUP BY A.JADWAL_TES_ID
		) JML_PEGAWAI ON JML_PEGAWAI.JADWAL_TES_ID = A.JADWAL_TES_ID
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