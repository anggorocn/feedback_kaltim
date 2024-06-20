<? 
/* *******************************************************************************************************
MODUL NAME 			: E LEARNING
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel KontakPegawai.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');
  
  class Api extends Entity{ 

		var $query;
    /**
    * Class constructor.
    **/
    function Api()
		{
      $this->Entity(); 
    }

    function selectByParamsApi($paramsArray=array(),$limit=-1,$from=-1, $statement='', $statementdetil='', $sOrder="", $formulaid="")
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
			, CASE WHEN COALESCE(X.NILAI,0) > MAX_DATA_X THEN MAX_DATA_Y ELSE COALESCE(X.NILAI,0) END NILAI_X
			, CASE WHEN COALESCE(Y.NILAI,0) > MAX_DATA_Y THEN MAX_DATA_Y ELSE COALESCE(Y.NILAI,0) END NILAI_Y
			, A1.KODE PANGKAT_KODE, A1.NAMA PANGKAT_NAMA, A2.NAMA SATKER_NAMA
			, CASE WHEN A.LAST_ESELON_ID = 99 THEN A4.NAMA ELSE A3.NAMA END ESELON_NAMA, RJ.RUMPUN_NAMA
			, A.*
			FROM simpeg.pegawai A
			LEFT JOIN simpeg.pangkat A1 ON A1.PANGKAT_ID = A.LAST_PANGKAT_ID
			LEFT JOIN simpeg.satker A2 ON A.SATKER_ID = A2.SATKER_ID
			LEFT JOIN simpeg.eselon A3 ON A3.ESELON_ID = A.LAST_ESELON_ID
			LEFT JOIN simpeg.tipe_pegawai A4 ON A4.TIPE_PEGAWAI_ID = A.TIPE_PEGAWAI_ID
			LEFT JOIN
			(
				SELECT A.JABATAN_ID, A.RUMPUN_ID, B.NAMA RUMPUN_NAMA
				FROM simpeg.jabatan A
				INNER JOIN simpeg.rumpun_jabatan B ON A.RUMPUN_ID = B.RUMPUN_ID
			) RJ ON A.LAST_JABATAN_ID = RJ.JABATAN_ID
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
		// echo $str; exit;
				
		return $this->selectLimit($str,$limit,$from); 
	}


  } 
?>