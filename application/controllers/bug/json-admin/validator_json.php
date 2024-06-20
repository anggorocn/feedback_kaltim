<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/class-list-util.php");
include_once("functions/class-list-util-serverside.php");

class validator_json extends CI_Controller {

	function __construct() {
		parent::__construct();
		//kauth
		if($this->session->userdata("adminuserid") == "")
		{
			redirect('adminlogin');
		}
		
		$this->adminuserid= $this->session->userdata("adminuserid");
		$this->adminusernama= $this->session->userdata("adminusernama");
		$this->adminuseraksesappmenu= $this->session->userdata("adminuseraksesappmenu");
	}

	function json()
	{
		$this->load->model("base-validasi/Validasi");

		$set= new Validasi();

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = "true";
			}
		}
		// print_r($columnsDefault);exit;
		$reqId= $this->input->get('reqId');
		$reqValidasi= $this->input->get('reqValidasi');
		$reqSatuanKerja= $this->input->get('reqSatuanKerja');
		$reqBulan= $this->input->get('reqBulan');
		$reqTahun= $this->input->get('reqTahun');

		$displaystart= -1;
		$displaylength= -1;

		$arrinfodata= [];

		$statement= "";
		if($userLogin->userSatkerId == "")//kondisi login sebagai admin
		{
			if($reqSatuanKerja == "")
			{
				//$statement .= " AND A.SATKER_ID = '-1'";
			}
			elseif($reqSatuanKerja == "-1")
			{
				$statement .= "";
			}
			else
				$statement .= " AND A.SATKER_ID LIKE '".$reqSatuanKerja."%' ";
		}
		else // kondisi login sebagai SKPD
		{
			if($reqSatuanKerja == "")
			{
				$statement .= " AND A.SATKER_ID LIKE '".$userLogin->userSatkerId."%' ";
			}
			elseif($reqSatuanKerja == "-1")
			{
				$statement .= " AND A.SATKER_ID LIKE '".$userLogin->userSatkerId."%' ";
			}
			else
			{
				$statement .= " AND A.SATKER_ID LIKE '".$reqSatuanKerja."%' ";
			}
		}

		if($reqValidasi == "0" || $reqValidasi == "1")
		{
			$statement .= " AND A.VALIDASI = '".$reqValidasi."'";
		}
		else
		{
			$statement .= " AND A.VALIDASI IS NULL";
		}


		if($reqBulan == "0")
		{
			$statement .= " AND TO_CHAR(A.TANGGAL_PROSES, 'YYYY') = '".date('Y')."'";	
		}
		else
		{
			if(!empty($reqBulan))
			{
				$statement .= " AND TO_CHAR(A.TANGGAL_PROSES, 'MM') = '".$reqBulan."'";	
			}

			if(!empty($reqTahun))
			{
				$statement .= " AND TO_CHAR(A.TANGGAL_PROSES, 'YYYY') = '".$reqTahun."'";	
			}

		}

		

		$sOrder = " ORDER BY A.GROUP_NAMA ASC";
		// $sOrder = "";
		$set->selectByParamsMonitoring(array(), $displaylength, $displaystart, $statement, $sOrder);
		// echo $set->query;exit;
		while ($set->nextRow()) 
		{
			$infocheckid= $set->getField("TEMP_VALIDASI_ID");
			$infoform= str_replace('app/index/', '',$set->getField("LINK_FORM"));
			$row= [];
			foreach($columnsDefault as $valkey => $valitem) 
			{
				if ($valkey == "SORDERDEFAULT")
					$row[$valkey]= "1";
				else if ($valkey == "TANGGAL")
				{
					$row[$valkey]= dateToPageCheck($set->getField($valkey));
				}
				else if ($valkey == "CHECK")
				{
					if ($reqValidasi=="")
					{
						$row[$valkey]= "<input type='checkbox' $checked onclick='setKlikCheck()' class='editor-active' id='reqPilihCheck".$infocheckid.'-'.$infoform."' ".$checked." value='".$infocheckid."'>";
					}
					else if ($reqValidasi=="0")
					{
						$row[$valkey]= "Data di tolak";
					}
					else if ($reqValidasi=="1")
					{
						$row[$valkey]= "Data sudah di validasi";
					}
					
				}
				else
					$row[$valkey]= $set->getField($valkey);
			}
			array_push($arrinfodata, $row);
		}

		// get all raw data
		$alldata = $arrinfodata;
		// print_r($alldata);exit;

		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			// $data[] = filterArray( $d, $columnsDefault );
			$data[] = $d;
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {
			$data         = filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					$data         = filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			if(count($columnsDefault) - 2 == $column){}
			else
			{
				$dir    = $_REQUEST['order'][0]['dir'];
				usort( $data, function ( $a, $b ) use ( $column, $dir ) {
					$a = array_slice( $a, $column, 1 );
					$b = array_slice( $b, $column, 1 );
					$a = array_pop( $a );
					$b = array_pop( $b );

					if ( $dir === 'asc' ) {
						return $a > $b ? true : false;
					}

					return $a < $b ? true : false;
				} );
			}
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$result = [
		    'recordsTotal'    => $totalRecords,
		    'recordsFiltered' => $totalDisplay,
		    'data'            => $data,
		];

		header('Content-Type: application/json');
		echo json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);	
	}

	function add()
	{

		$this->load->model("base-validasi/Validasi");

		$set= new Validasi();

		$arrvalidasi = array();
		$reqValidasiId= $this->input->get('reqValidasiId');
		$reqValidasiForm= $this->input->get('reqValidasiForm');
		$reqValidasiStatus= $this->input->get('reqValidasiStatus');
		// print_r($reqValidasiStatus);exit;

		if (!empty($reqValidasiId) && !empty($reqValidasiForm)  )
		{
			$arrid = explode(',', $reqValidasiId);
			$arrform = explode(',', $reqValidasiForm);
			$arrvalidasi = array_combine($arrid, $arrform);

			// print_r($arrvalidasi);exit;

			$reqForm ="";
			
			foreach ($arrvalidasi as $key => $value ) 
			{

				if ($value == "pegawai_jabatan_tipe_add")
				{
					$set->selectByParamsJabatanTipe(array('TEMP_VALIDASI_ID' => $key), -1, -1);
					// echo $set->query;exit;
					$set->firstRow();
					$reqTempValidasiId = $set->getField("TEMP_VALIDASI_ID");
					$reqJenisJabatan = $set->getField("JENIS_JABATAN_ID");
					$reqJabatanStrukturalId = $set->getField("JABATAN_STRUKTURAL_NEW_ID");
					$reqJabatanFungsionalId = $set->getField("JABATAN_FUNGSIONAL_NEW_ID");
					$reqJabatanPelaksanaId = $set->getField("JABATAN_PELAKSANA_NEW_ID");
					$reqKategoriJabatanId = $set->getField("TIPE_PEGAWAI_NEW_ID");
					$reqTugasTambahanId = $set->getField("TUGAS_TAMBAHAN_ID");
					$reqTugasTambahan = $set->getField("TUGAS_TAMBAHAN_NAMA");
					$reqEselonId = $set->getField("ESELON_ID");
					$reqBup = $set->getField("BUP");
					$reqKelJab = $set->getField("KELAS_JABATAN");
					$reqTmtJabatan = dateToPageCheck($set->getField("TMT_JABATAN"));
					$reqTanggalSuratKeputusan = dateToPageCheck($set->getField("TANGGAL_SK"));
					$reqNoSk = $set->getField("NO_SK");
					$reqPegawaiId = $set->getField("PEGAWAI_ID");


					$set->setField("LAST_CREATE_USER", "");
					$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set->setField("LAST_CREATE_SATKER", "");
					$set->setField('PEGAWAI_ID', $reqPegawaiId);
					$set->setField('JENIS_JABATAN_ID', ValToNullDB($reqJenisJabatan));

					if ($reqJenisJabatan == 1) {
						$set->setField('JABATAN_STRUKTURAL_NEW_ID', $reqJabatanStrukturalId);
					}
					elseif($reqJenisJabatan == 2 || $reqJenisJabatan == 4)
					{
						$set->setField('JABATAN_FUNGSIONAL_NEW_ID', $reqJabatanFungsionalId);

					}
					elseif ($reqJenisJabatan == 3) {
						$set->setField('JABATAN_PELAKSANA_NEW_ID', $reqJabatanPelaksanaId);
					}

					if($reqKategoriJabatanId == 16)
					{
						$set->setField('TUGAS_TAMBAHAN_ID', $reqTugasTambahanId);
						$set->setField('TUGAS_TAMBAHAN_NAMA', $reqTugasTambahan);
					}

					$set->setField('ESELON_ID', ValToNullDB($reqEselonId));
					$set->setField('TIPE_PEGAWAI_NEW_ID', $reqKategoriJabatanId);
					$set->setField('BUP', ValToNullDB($reqBup));
					$set->setField('KELAS_JABATAN', ValToNullDB($reqKelJab));
					$set->setField('TMT_JABATAN', dateToDBCheck($reqTmtJabatan));
					$set->setField('TANGGAL_SK', dateToDBCheck($reqTanggalSuratKeputusan));
					$set->setField('NO_SK', $reqNoSk);
					$set->setField('USER_APP_ID', ValToNullDB($userLogin->UID));

					$reqSimpan= "";

					$reqStatusValidasi = $reqValidasiStatus;
					if(is_numeric($reqTempValidasiId))
					{
						$set->setField('VALIDASI', $reqStatusValidasi);
						$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

						$reqForm= "Jabatan";
						if($set->updateJabatanTipe())
						{
							$reqSimpan= "1";
						}
						else
						{
							$reqForm= "Anak";
						}

					}
				}
				elseif ($value == "pegawai_anak_add") 
				{
					$set->selectByParamsAnak(array('TEMP_VALIDASI_ID' => $key), -1, -1);
					// echo $set->query;exit;
					$set->firstRow();
					$reqTempValidasiId = $set->getField("TEMP_VALIDASI_ID");
					$reqNama = $set->getField("NAMA");
					$reqTmpLahir = $set->getField("TEMPAT_LAHIR");
					$reqTglLahir = dateToPageCheck($set->getField("TANGGAL_LAHIR"));
					$reqLP = $set->getField("JENIS_KELAMIN");
					$reqStatus = $set->getField("STATUS_KELUARGA");
					$reqDapatTunjangan = $set->getField("STATUS_TUNJANGAN");
					$reqPendidikan = $set->getField("PENDIDIKAN_ID");
					$reqPegawaiId = $set->getField("PEGAWAI_ID");
					$reqPekerjaan = $set->getField("PEKERJAAN");
					$reqMulaiDibayar = dateToPageCheck($set->getField("AWAL_BAYAR"));
					$reqAkhirDibayar = dateToPageCheck($set->getField("AKHIR_BAYAR"));
					
					$set->setField('NAMA', setQuote($reqNama,1));
					$set->setField('TEMPAT_LAHIR', $reqTmpLahir);
					$set->setField('TANGGAL_LAHIR', dateToDBCheck($reqTglLahir));
					$set->setField('JENIS_KELAMIN', $reqLP);
					$set->setField('STATUS_KELUARGA', ValToNullDB($reqStatus));
					$set->setField('STATUS_TUNJANGAN', ValToNullDB($reqDapatTunjangan));
					$set->setField('PENDIDIKAN_ID', ValToNullDB($reqPendidikan));
					$set->setField('PEGAWAI_ID', $reqPegawaiId);
					$set->setField('PEKERJAAN', $reqPekerjaan);
					$set->setField('AWAL_BAYAR', dateToDBCheck($reqMulaiDibayar));
					$set->setField('AKHIR_BAYAR', dateToDBCheck($reqAkhirDibayar));
					$set->setField('USER_APP_ID', ValToNullDB($userLogin->UID));

					$reqSimpan= "";
					$reqStatusValidasi = $reqValidasiStatus;
					if(is_numeric($reqTempValidasiId))
					{
						$set->setField('VALIDASI', $reqStatusValidasi);
						$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

						if($set->updatevalidasiAnak())
						{
							$reqSimpan= "1";
							
						}
						else
						{
							$reqForm= "Anak";
						}
					}
				}
				elseif ($value == "pegawai_bahasa_add") 
				{
					$set->selectByParamsBahasa(array('TEMP_VALIDASI_ID' => $key), -1, -1);
					// echo $set->query;exit;
					$set->firstRow();
					$reqTempValidasiId = $set->getField("TEMP_VALIDASI_ID");
					$reqNamaBahasa = $set->getField("NAMA");
					$reqJenisBahasa = $set->getField("JENIS");
					$reqKemampuanBicara = $set->getField("KEMAMPUAN");
					$reqPegawaiId = $set->getField("PEGAWAI_ID");
					
					$set->setField('NAMA', $reqNamaBahasa);
					$set->setField('JENIS', $reqJenisBahasa);
					$set->setField('KEMAMPUAN', $reqKemampuanBicara);
					$set->setField('PEGAWAI_ID', $reqPegawaiId);

					$reqSimpan= "";
					$reqStatusValidasi = $reqValidasiStatus;
					if(is_numeric($reqTempValidasiId))
					{
						$set->setField('VALIDASI', $reqStatusValidasi);
						$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

						if($set->updatebahasa())
						{
							$reqSimpan= "1";
							
						}
						else
						{
							$reqForm= "Anak";
						}
					}
				}
				elseif ($value == "pegawai_cuti_add") 
				{
					$set->selectByParamsCuti(array('TEMP_VALIDASI_ID' => $key), -1, -1);
					// echo $set->query;exit;
					$set->firstRow();
					$reqTempValidasiId = $set->getField("TEMP_VALIDASI_ID");
					$reqPegawaiId = $set->getField("PEGAWAI_ID");
					$reqNoSurat = $set->getField("NO_SURAT");
					$reqJenisCuti = $set->getField("JENIS_CUTI");
					$reqTanggalPermohonan = dateToPageCheck($set->getField("TANGGAL_PERMOHONAN"));
					$reqTanggalSurat = dateToPageCheck($set->getField("TANGGAL_SURAT"));
					$reqTanggalMulai = dateToPageCheck($set->getField("TANGGAL_MULAI"));
					$reqTanggalPermohonan = dateToPageCheck($set->getField("TANGGAL_PERMOHONAN"));
					$reqLama = $set->getField("LAMA");
					$reqCutiKeterangan = $set->getField("CUTI_KETERANGAN");
					$reqKeterangan = $set->getField("KETERANGAN");
					
					$set->setField('NO_SURAT', $reqNoSurat);
					$set->setField('JENIS_CUTI', ValToNullDB($reqJenisCuti));
					$set->setField('TANGGAL_PERMOHONAN', dateToDBCheck($reqTanggalPermohonan));
					$set->setField('TANGGAL_SURAT', dateToDBCheck($reqTanggalSurat));
					$set->setField('TANGGAL_MULAI', dateToDBCheck($reqTanggalMulai));
					$set->setField('TANGGAL_SELESAI', dateToDBCheck($reqTanggalSelesai));	
					$set->setField('LAMA', ValToNullDB($reqLama));
					$set->setField("CUTI_KETERANGAN", $reqCutiKeterangan);
					$set->setField('KETERANGAN', $reqKeterangan);
					$set->setField('PEGAWAI_ID', $reqPegawaiId);

					$reqSimpan= "";
					$reqStatusValidasi = $reqValidasiStatus;
					if(is_numeric($reqTempValidasiId))
					{
						$set->setField('VALIDASI', $reqStatusValidasi);
						$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

						if($set->updatecuti())
						{
							$reqSimpan= "1";
							
						}
						else
						{
							$reqForm= "Cuti";
						}
					}
				}

				elseif ($value == "pegawai_hukuman_add") 
				{
					$set->selectByParamsHukuman(array('TEMP_VALIDASI_ID' => $key), -1, -1);
					// echo $set->query;exit;
					$set->firstRow();
					$reqTempValidasiId = $set->getField("TEMP_VALIDASI_ID");
					$reqPegawaiId = $set->getField("PEGAWAI_ID");
					$reqPejabatPenetapId = $set->getField("PEJABAT_PENETAP_ID");
					$reqTanggalMulai = dateToPageCheck($set->getField("TANGGAL_MULAI"));
					$reqTanggalAkhir = dateToPageCheck($set->getField("TANGGAL_AKHIR"));
					$reqNoSK = $set->getField("NO_SK");
					$reqTanggalSK = dateToPageCheck($set->getField("TANGGAL_SK"));
					$reqTMTSK = dateToPageCheck($set->getField("TMT_SK"));
					$reqJenisHukuman = $set->getField("JENIS_HUKUMAN_ID");
					$reqTingkatHukuman = $set->getField("TINGKAT_HUKUMAN_ID");
					$reqPeraturan = $set->getField("PERATURAN_ID");
					$reqPermasalahan = $set->getField("KETERANGAN");
					$reqMasihBerlaku = $set->getField("BERLAKU");

					
					$set->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId));	
					$set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
					$set->setField('TANGGAL_MULAI', dateToDBCheck($reqTanggalMulai));
					$set->setField('TANGGAL_AKHIR', dateToDBCheck($reqTanggalAkhir));
					$set->setField('NO_SK', $reqNoSK);
					$set->setField('TANGGAL_SK', dateToDBCheck($reqTanggalSK));
					$set->setField('TMT_SK', dateToDBCheck($reqTMTSK));
					$set->setField('JENIS_HUKUMAN_ID', ValToNullDB($reqJenisHukuman));
					$set->setField('TINGKAT_HUKUMAN_ID', ValToNullDB($reqTingkatHukuman));
					$set->setField('PERATURAN_ID', ValToNullDB($reqPeraturan));
					$set->setField('KETERANGAN', $reqPermasalahan);
					$set->setField('PEGAWAI_ID',$reqPegawaiId);
					$set->setField('BERLAKU',ValToNullDB((int)$reqMasihBerlaku));

					$reqSimpan= "";
					$reqStatusValidasi = $reqValidasiStatus;
					if(is_numeric($reqTempValidasiId))
					{
						$set->setField('VALIDASI', $reqStatusValidasi);
						$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

						if($set->updatehukuman())
						{
							$reqSimpan= "1";
							
						}
						else
						{
							$reqForm= "Hukuman";
						}
					}
				}
				elseif ($value == "pegawai_kursus_add") 
				{
					$set->selectByParamsKursus(array('TEMP_VALIDASI_ID' => $key), -1, -1);
					// echo $set->query;exit;
					$set->firstRow();
					$reqTempValidasiId = $set->getField("TEMP_VALIDASI_ID");
					$reqPegawaiId = $set->getField("PEGAWAI_ID");

					$reqNamaKursus = $set->getField("NAMA");
					$reqLamaKursus = $set->getField("LAMA");
					$reqTanggalKursus = dateToPageCheck($set->getField("TANGGAL_MULAI"));
					$reqInstansiId = $set->getField("INSTANSI_ID");
					$reqTipeKursus = $set->getField("TIPE");
					$reqNoSertifikat = $set->getField("NO_PIAGAM");
					$reqInstitusi = $set->getField("PENYELENGGARA");
					$reqTahunKursus = $set->getField("TAHUN");

					
					$set->setField('NAMA', $reqNamaKursus);
					$set->setField('LAMA', ValToNullDB($reqLamaKursus));
					$set->setField('TANGGAL_MULAI', dateToDBCheck($reqTanggalKursus));
					$set->setField('INSTANSI_ID', ValToNullDB($reqInstansiId));
					$set->setField('TIPE',ValToNullDB($reqTipeKursus));
					$set->setField('NO_PIAGAM', $reqNoSertifikat);
					$set->setField('PENYELENGGARA', $reqInstitusi);
					$set->setField('TAHUN', ValToNullDB($reqTahunKursus));
					$set->setField('PEGAWAI_ID', $reqPegawaiId);

					$reqSimpan= "";
					$reqStatusValidasi = $reqValidasiStatus;
					if(is_numeric($reqTempValidasiId))
					{
						$set->setField('VALIDASI', $reqStatusValidasi);
						$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

						if($set->updatekursus())
						{
							$reqSimpan= "1";
							
						}
						else
						{
							$reqForm= "Kursus";
						}
					}
				}
				elseif ($value == "pegawai_organisasi_add") 
				{
					$set->selectByParamsOrganisasi(array('TEMP_VALIDASI_ID' => $key), -1, -1);
					// echo $set->query;exit;
					$set->firstRow();
					$reqTempValidasiId = $set->getField("TEMP_VALIDASI_ID");
					$reqPegawaiId = $set->getField("PEGAWAI_ID");

					$reqJabatan = $set->getField("JABATAN");
					$reqNamaOrganisasi = $set->getField("NAMA");
					$reqAwal = dateToPageCheck($set->getField("TANGGAL_AWAL"));
					$reqAkhir = dateToPageCheck($set->getField("TANGGAL_AKHIR"));
					$reqPimpinan = $set->getField("PIMPINAN");
					$reqTempat = $set->getField("TEMPAT");

					$set->setField('JABATAN', $reqJabatan);
					$set->setField('NAMA', $reqNamaOrganisasi);
					$set->setField('TANGGAL_AWAL', dateToDBCheck($reqAwal));
					$set->setField('TANGGAL_AKHIR', dateToDBCheck($reqAkhir));
					$set->setField('PIMPINAN', $reqPimpinan);
					$set->setField('TEMPAT', $reqTempat);
					$set->setField('PEGAWAI_ID', $reqPegawaiId);

					$reqSimpan= "";
					$reqStatusValidasi = $reqValidasiStatus;
					if(is_numeric($reqTempValidasiId))
					{
						$set->setField('VALIDASI', $reqStatusValidasi);
						$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

						if($set->updateorganisasi())
						{
							$reqSimpan= "1";
							
						}
						else
						{
							$reqForm= "Organisasi";
						}
					}
				}
				elseif ($value == "pegawai_penghargaan_add") 
				{
					$set->selectByParamsPenghargaan(array('TEMP_VALIDASI_ID' => $key), -1, -1);
					// echo $set->query;exit;
					$set->firstRow();
					$reqTempValidasiId = $set->getField("TEMP_VALIDASI_ID");
					$reqPegawaiId = $set->getField("PEGAWAI_ID");

					$reqPejabatPenetapId = $set->getField("PEJABAT_PENETAP_ID");
					$reqPejabatPenetap = $set->getField("PEJABAT_PENETAP");
					$reqNamaPenghargaan = $set->getField("NAMA");
					$reqTahun = $set->getField("TAHUN");
					$reqTglSK = dateToPageCheck($set->getField("TANGGAL_SK"));
					$reqNoSK = $set->getField("NO_SK");

					$set->setField('PEJABAT_PENETAP_`ID', ValToNullDB($reqPejabatPenetapId));	
					$set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
					$set->setField('NAMA', $reqNamaPenghargaan);
					$set->setField('TAHUN', ValToNullDB($reqTahun));
					$set->setField('TANGGAL_SK', dateToDBCheck($reqTglSK));
					$set->setField('NO_SK', $reqNoSK);
					$set->setField('PEGAWAI_ID', $reqPegawaiId);

					$reqSimpan= "";
					$reqStatusValidasi = $reqValidasiStatus;
					if(is_numeric($reqTempValidasiId))
					{
						$set->setField('VALIDASI', $reqStatusValidasi);
						$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

						if($set->updatepenghargaan())
						{
							$reqSimpan= "1";
							
						}
						else
						{
							$reqForm= "Penghargaan";
						}
					}
				}
				elseif ($value == "pegawai_diklat_add") 
				{
					$set->selectByParamsDiklat(array('TEMP_VALIDASI_ID' => $key), -1, -1);
					// echo $set->query;exit;
					$set->firstRow();
					$reqTempValidasiId = $set->getField("TEMP_VALIDASI_ID");
					$reqPegawaiId = $set->getField("PEGAWAI_ID");

					$reqNomor = $set->getField("NOMOR");
					$reqTanggal = dateToPageCheck($set->getField("TANGGAL"));
					$reqTahun = $set->getField("TAHUN");
					$reqDiklat = $set->getField("DIKLAT_ID");

					$set->setField('NOMOR', $reqNomor);
					$set->setField('TANGGAL', dateToDBCheck($reqTanggal));
					$set->setField('TAHUN', ValToNullDB($reqTahun));
					$set->setField("DIKLAT_ID", $reqDiklat);
					$set->setField("PEGAWAI_ID", $reqPegawaiId);

					$reqSimpan= "";
					$reqStatusValidasi = $reqValidasiStatus;
					if(is_numeric($reqTempValidasiId))
					{
						$set->setField('VALIDASI', $reqStatusValidasi);
						$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

						if($set->updatediklat())
						{
							$reqSimpan= "1";
							
						}
						else
						{
							$reqForm= "Diklat";
						}
					}
				}

				elseif ($value == "pegawai_penilaian_potensi_add") 
				{
					$set->selectByParamsPotensi(array('TEMP_VALIDASI_ID' => $key), -1, -1);
					// echo $set->query;exit;
					$set->firstRow();
					$reqTempValidasiId = $set->getField("TEMP_VALIDASI_ID");
					$reqPegawaiId = $set->getField("PEGAWAI_ID");

					$reqTahun = $set->getField("TAHUN");
					$reqTanggungJawab = $set->getField("TANGGUNG_JAWAB");
					$reqMotivasi = $set->getField("MOTIVASI");
					$reqMinat = $set->getField("MINAT");

					$set->setField('TAHUN', $reqTahun);
					$set->setField('TANGGUNG_JAWAB', $reqTanggungJawab);
					$set->setField('MOTIVASI', $reqMotivasi);
					$set->setField('MINAT', $reqMinat);
					$set->setField('PEGAWAI_ID', $reqPegawaiId);
		

					$reqSimpan= "";
					$reqStatusValidasi = $reqValidasiStatus;
					if(is_numeric($reqTempValidasiId))
					{
						$set->setField('VALIDASI', $reqStatusValidasi);
						$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

						if($set->updatepotensi())
						{
							$reqSimpan= "1";
							
						}
						else
						{
							$reqForm= "Potensi";
						}
					}
				}
				elseif ($value == "tambahan_masa_kerja") 
				{
					$set->selectByParamsMasaKerja(array('TEMP_VALIDASI_ID' => $key), -1, -1);
					// echo $set->query;exit;
					$set->firstRow();
					$reqTempValidasiId = $set->getField("TEMP_VALIDASI_ID");
					$reqPegawaiId = $set->getField("PEGAWAI_ID");

					$reqBlMK = $set->getField("BULAN_BARU");
					$reqBlTMK = $set->getField("BULAN_TAMBAHAN");
					$reqNoSK = $set->getField("NO_SK");
					$reqThMK = $set->getField("TAHUN_BARU");
					$reqThTMK = $set->getField("TAHUN_TAMBAHAN");
					$reqTglSK = dateToPageCheck($set->getField("TANGGAL_SK"));
					$reqTMTSK = dateToPageCheck($set->getField("TMT_SK"));
					$reqGolRuang = $set->getField("PANGKAT_ID");
					$reqPejabatPenetapId = $set->getField("PEJABAT_PENETAP_ID");
					$reqPejabatPenetap = $set->getField("PEJABAT_PENETAP");
					$reqGajiPokok = $set->getField("GAJI_POKOK");
					

					$set->setField('BULAN_BARU', $reqBlMK);
					$set->setField('BULAN_TAMBAHAN', $reqBlTMK);
					$set->setField('NO_SK', $reqNoSK);
					$set->setField('TAHUN_BARU', $reqThMK);
					$set->setField('PEGAWAI_ID', $reqPegawaiId);
					$set->setField('TAHUN_TAMBAHAN', $reqThTMK);
					$set->setField('TANGGAL_SK', dateToDBCheck($reqTglSK));
					$set->setField('TMT_SK', dateToDBCheck($reqTMTSK));
					$set->setField('USER_APP_ID', $userLogin->UID);
					$set->setField('PANGKAT_ID', ValToNullDB($reqGolRuang));
					$set->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId));	
					$set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
					$set->setField('GAJI_POKOK', ValToNullDB(dotToNo($reqGajiPokok)));

					$reqSimpan= "";
					$reqStatusValidasi = $reqValidasiStatus;
					if(is_numeric($reqTempValidasiId))
					{
						$set->setField('VALIDASI', $reqStatusValidasi);
						$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

						if($set->updatemasakerja())
						{
							$reqSimpan= "1";
							
						}
						else
						{
							$reqForm= "Masa Kerja";
						}
					}
				}
				elseif ($value == "pegawai_suami_istri_add") 
				{
					$set->selectByParamsSuamiIstri(array('TEMP_VALIDASI_ID' => $key), -1, -1);
					// echo $set->query;exit;
					$set->firstRow();
					$reqTempValidasiId = $set->getField("TEMP_VALIDASI_ID");
					$reqPegawaiId = $set->getField("PEGAWAI_ID");

					$reqPendidikan = $set->getField("PENDIDIKAN_ID");
					$reqNamaSuamiIstri = $set->getField("NAMA");
					$reqTempatLahir = $set->getField("TEMPAT_LAHIR");
					$reqTglSK = dateToPageCheck($set->getField("TANGGAL_LAHIR"));
					$reqTMTSK = dateToPageCheck($set->getField("TANGGAL_KAWIN"));
					$reqNoAktaNikah = $set->getField("SK_CERAI");
					$reqStatus = $set->getField("STATUS");
					$reqNoHp = $set->getField("BUKU_NIKAH");
					$reqKartu = $set->getField("KARTU");
					$reqPns = $set->getField("STATUS_PNS");
					$reqNIP = $set->getField("NIP_PNS");
					$reqPekerjaan = $set->getField("PEKERJAAN");
					$reqTunjangan = $set->getField("STATUS_TUNJANGAN");
					$reqSudahDibayar = $set->getField("STATUS_BAYAR");
					$reqBulanDibayar = $set->getField("BULAN_BAYAR");
					

					$set->setField("PEGAWAI_ID", $reqPegawaiId);
					$set->setField("PENDIDIKAN_ID", ValToNullDB($reqPendidikan));
					$set->setField("NAMA", $reqNamaSuamiIstri);
					$set->setField("TEMPAT_LAHIR", $reqTempatLahir);
					$set->setField("TANGGAL_LAHIR", dateToDBCheck($reqTglSK));
					$set->setField("TANGGAL_KAWIN", dateToDBCheck($reqTMTSK));
					$set->setField('SK_CERAI', $reqNoAktaNikah);
					$set->setField('STATUS', ValToNullDB($reqStatus));
					$set->setField('BUKU_NIKAH', $reqNoHp);
					$set->setField("KARTU", $reqKartu);
					$set->setField("STATUS_PNS", ValToNullDB($reqPns));
					$set->setField("NIP_PNS", $reqNIP);
					$set->setField("PEKERJAAN", $reqPekerjaan);
					$set->setField("STATUS_TUNJANGAN", ValToNullDB($reqTunjangan));
					$set->setField("STATUS_BAYAR", ValToNullDB($reqSudahDibayar));
					$set->setField("BULAN_BAYAR", dateToDBCheck($reqBulanDibayar));

					$reqSimpan= "";
					$reqStatusValidasi = $reqValidasiStatus;
					if(is_numeric($reqTempValidasiId))
					{
						$set->setField('VALIDASI', $reqStatusValidasi);
						$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

						if($set->updatesuamiistri())
						{
							$reqSimpan= "1";
							
						}
						else
						{
							$reqForm= "Suami Istri";
						}
					}
				}

				elseif ($value == "pegawai_orang_tua") 
				{
	
					$statement.= " AND TEMP_VALIDASI_ID =".$key;
					$set->selectByParamsAyah(array("JENIS_KELAMIN" => "L"),-1,-1, $statement);
					// echo $set->query;exit;
					$set->firstRow();
					$reqPerubahanDataAyah= $set->getField('PERUBAHAN_DATA');

					$reqRowIdAyah= $set->getField('ORANG_TUA_ID');
					$reqDataIdAyah= $set->getField('TEMP_VALIDASI_ID');
					$reqNamaAyah= $set->getField('NAMA');
					$reqIdAyah= $set->getField("ORANG_TUA_ID");
					$reqTempatLahirAyah= $set->getField('TEMPAT_LAHIR');
					$reqTglLahirAyah= dateToPageCheck($set->getField('TANGGAL_LAHIR'));
					$reqUsiaAyah= $set->getField('USIA');
					$reqPekerjaanAyah = $set->getField('PEKERJAAN');
					$reqAlamatAyah= $set->getField('ALAMAT');
					$reqPropinsiAyahId= $set->getField('PROPINSI_ID');
					$reqKabupatenAyahId= $set->getField('KABUPATEN_ID');
					$reqKecamatanAyahId= $set->getField('KECAMATAN_ID');
					$reqDesaAyahId= $set->getField('KELURAHAN_ID');
					$reqPropinsiAyah= $set->getField('PROPINSI_NAMA');
					$reqKabupatenAyah= $set->getField('KABUPATEN_NAMA');
					$reqKecamatanAyah= $set->getField('KECAMATAN_NAMA');
					$reqKodePosAyah= $set->getField('KODEPOS');
					$reqDesaAyah= $set->getField('KELURAHAN_NAMA');
					$reqTeleponAyah= $set->getField('TELEPON');
					$reqValidasiAyah= $set->getField('VALIDASI');
					$reqPegawaiIdAyah = $set->getField("PEGAWAI_ID");

					unset($set);	

					$set= new Validasi();
					$set->selectByParamsIbu(array("JENIS_KELAMIN" => "P"),-1,-1, $statement);
					// echo $set->query;exit;
					$set->firstRow();
					$reqRowIdIbu= $set->getField('ORANG_TUA_ID');
					$reqDataIdIbu= $set->getField('TEMP_VALIDASI_ID');
					$reqPerubahanDataIbu= $set->getField('PERUBAHAN_DATA');	
					$reqIdIbu= $set->getField("ORANG_TUA_ID");
					$reqNamaIbu= $set->getField('NAMA');
					$reqTempatLahirIbu= $set->getField('TEMPAT_LAHIR');
					$reqTglLahirIbu= dateToPageCheck($set->getField('TANGGAL_LAHIR'));
					$reqUsiaIbu= $set->getField('USIA');
					$reqPekerjaanIbu= $set->getField('PEKERJAAN');
					$reqAlamatIbu= $set->getField('ALAMAT');
					$reqPropinsiIbuId= $set->getField('PROPINSI_ID');
					$reqKabupatenIbuId= $set->getField('KABUPATEN_ID');
					$reqKecamatanIbuId= $set->getField('KECAMATAN_ID');
					$reqDesaIbuId= $set->getField('KELURAHAN_ID');
					$reqPropinsiIbu= $set->getField('PROPINSI_NAMA');
					$reqKabupatenIbu= $set->getField('KABUPATEN_NAMA');
					$reqKecamatanIbu= $set->getField('KECAMATAN_NAMA');
					$reqDesaIbu= $set->getField('KELURAHAN_NAMA');
					$reqKodePosIbu= $set->getField('KODEPOS');
					$reqTeleponIbu= $set->getField('TELEPON');
					$reqValidasiIbu= $set->getField('VALIDASI');
					$reqPegawaiIdIbu = $set->getField("PEGAWAI_ID");
					unset($set);

					$set= new Validasi();	
					
					$set->setField("PEGAWAI_ID", $reqPegawaiIdAyah);
					$set->setField("JENIS_KELAMIN", "L");
					$set->setField("NAMA", $reqNamaAyah);
					$set->setField("TEMPAT_LAHIR", $reqTempatLahirAyah);
					$set->setField("TANGGAL_LAHIR", dateToDBCheck($reqTglLahirAyah));
					$set->setField("PEKERJAAN", $reqPekerjaanAyah);
					$set->setField("ALAMAT", $reqAlamatAyah);
					$set->setField("KODEPOS", $reqKodePosAyah);
					$set->setField("TELEPON", $reqTeleponAyah);
					$set->setField("PROPINSI_ID", ValToNullDB($reqPropinsiAyahId));
					$set->setField("KABUPATEN_ID", ValToNullDB($reqKabupatenAyahId));
					$set->setField("KECAMATAN_ID", ValToNullDB($reqKecamatanAyahId));
					$set->setField("KELURAHAN_ID", ValToNullDB($reqDesaAyahId));
					$set->setField("LAST_CREATE_USER", $userLogin->idUser);
					$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
					$set->setField('ORANG_TUA_ID',ValToNullDB($reqRowIdAyah));


					$reqSimpan= "";
					$reqStatusValidasi = $reqValidasiStatus;
					if(is_numeric($reqDataIdAyah))
					{
						$set->setField('VALIDASI', $reqStatusValidasi);
						$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set->setField('TEMP_VALIDASI_ID', $reqDataIdAyah);

						if($set->updateorangtua())
						{
							$reqSimpan= "1";
						}
						else
						{
							$reqForm= "Orang Tua Ayah";
						}
						
					}

					if(is_numeric($reqDataIdIbu))
					{
						$set->setField("PEGAWAI_ID", $reqPegawaiIdIbu);
						$set->setField("JENIS_KELAMIN", "P");
						$set->setField("NAMA", $reqNamaIbu);
						$set->setField("TEMPAT_LAHIR", $reqTempatLahirIbu);
						$set->setField("TANGGAL_LAHIR", dateToDBCheck($reqTglLahirIbu));
						$set->setField("PEKERJAAN", $reqPekerjaanIbu);
						$set->setField("ALAMAT", $reqAlamatIbu);
						$set->setField("KODEPOS", $reqKodePosIbu);
						$set->setField("TELEPON", $reqTeleponIbu);
						$set->setField("PROPINSI_ID", ValToNullDB($reqPropinsiIbuId));
						$set->setField("KABUPATEN_ID", ValToNullDB($reqKabupatenIbuId));
						$set->setField("KECAMATAN_ID", ValToNullDB($reqKecamatanIbuId));
						$set->setField("KELURAHAN_ID", ValToNullDB($reqDesaIbuId));
						$set->setField("LAST_CREATE_USER", $userLogin->idUser);
						$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
						$set->setField("LAST_CREATE_SATKER", $userLogin->userSatkerId);
						$set->setField('ORANG_TUA_ID',ValToNullDB($reqRowIdIbu));

						$set->setField('VALIDASI', $reqStatusValidasi);
						$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set->setField('TEMP_VALIDASI_ID', $reqDataIdIbu);

						if($set->updateorangtua())
						{
							$reqSimpan= "1";
						}
						else
						{
							$reqForm= "Orang Tua Ibu";
						}
						
					}
				}
				elseif ($value == "pegawai_penilaian_prestasi_add") 
				{
					$set->selectByParamsPenilaiankerja(array('TEMP_VALIDASI_ID' => $key), -1, -1);
					// echo $set->query;exit;
					$set->firstRow();
					$reqTempValidasiId = $set->getField("TEMP_VALIDASI_ID");
					$reqPegawaiId = $set->getField("PEGAWAI_ID");

					$reqPejabatPenetapId = $set->getField("PEJABAT_PENETAP_ID");
					$reqPejabatPenetap = $set->getField("PEJABAT_PENETAP");
					$reqTahun = $set->getField("TAHUN");
					$reqNilai1 = $set->getField("NILAI1");
					$reqNilai2 = $set->getField("NILAI2");
					$reqNilai3 = $set->getField("NILAI3");
					$reqNilai4 = $set->getField("NILAI4");
					$reqNilai5 = $set->getField("NILAI5");
					$reqNilai6 = $set->getField("NILAI6");
					$reqJumlah = $set->getField("JUMLAH");
					$reqRataRata = $set->getField("RATA_RATA");

					$reqTanggalAwal = dateToPageCheck($set->getField("TANGGAL_AWAL"));
					$reqTanggalAkhir = dateToPageCheck($set->getField("TANGGAL_AKHIR"));
					$reqSasaranKerja = $set->getField("SASARAN_KERJA");
					$reqSasaranKerjaHasil = $set->getField("SASARAN_KERJA_HASIL");
					$reqPerilakuHasil = $set->getField("PERILAKU_HASIL");
					$reqNilaiHasil = $set->getField("NILAI_HASIL");
					$reqRekomendasi = $set->getField("REKOMENDASI");
					

					$set->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId));	
					$set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));

					$set->setField('TAHUN', $reqTahun);
					$set->setField('NILAI1', ValToNullDB(commaToDot($reqNilai1)));
					$set->setField('NILAI2', ValToNullDB(commaToDot($reqNilai2)));
					$set->setField('NILAI3', ValToNullDB(commaToDot($reqNilai3)));
					$set->setField('NILAI4', ValToNullDB(commaToDot($reqNilai4)));
					$set->setField('NILAI5', ValToNullDB(commaToDot($reqNilai5)));
					$set->setField('NILAI6', ValToNullDB(commaToDot($reqNilai6)));
					$set->setField('JUMLAH', ValToNullDB(commaToDot($reqJumlah)));
					$set->setField('RATA_RATA', ValToNullDB(commaToDot($reqRataRata)));

					$set->setField('TANGGAL_AWAL', dateToDBCheck($reqTanggalAwal));
					$set->setField('TANGGAL_AKHIR', dateToDBCheck($reqTanggalAkhir));

					$set->setField('SASARAN_KERJA', ValToNullDB(commaToDot($reqSasaranKerja)));
					$set->setField('SASARAN_KERJA_HASIL', ValToNullDB(commaToDot($reqSasaranKerjaHasil)));
					$set->setField('PERILAKU_HASIL', ValToNullDB(commaToDot($reqPerilakuHasil)));
					$set->setField('NILAI_HASIL', ValToNullDB(commaToDot($reqNilaiHasil)));
					$set->setField('REKOMENDASI', $reqRekomendasi);
					$set->setField('PEGAWAI_ID', $reqPegawaiId);
					$set->setField('USER_APP_ID', $userLogin->UID);

					$reqSimpan= "";
					$reqStatusValidasi = $reqValidasiStatus;
					if(is_numeric($reqTempValidasiId))
					{
						$set->setField('VALIDASI', $reqStatusValidasi);
						$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

						if($set->updatepenilaiankerja())
						{
							$reqSimpan= "1";
							
						}
						else
						{
							$reqForm= "Penilaian Kerja";
						}
					}
				}
				elseif ($value == "pegawai_data") 
				{
					$set->selectByParamsPegawai(array('TEMP_VALIDASI_ID' => $key), -1, -1);
					// echo $set->query;exit;
					$set->firstRow();
					$reqTempValidasiId = $set->getField("TEMP_VALIDASI_ID");
					$reqPegawaiId = $set->getField("PEGAWAI_ID");

					$reqNipLama= $set->getField('NIP_LAMA');
					$reqNipBaru= $set->getField('NIP_BARU');
					$reqNama= $set->getField('NAMA');
					$reqTipePegawai= $set->getField('TIPE_PEGAWAI_ID');
					$reqGelarDepan= $set->getField('GELAR_DEPAN');
					$reqGelarBelakang= $set->getField('GELAR_BELAKANG');
					$reqStatusPegawai= $set->getField('STATUS_PEGAWAI');
					$reqTempatLahir= $set->getField('TEMPAT_LAHIR');
					$reqTanggalLahir= dateToPageCheck($set->getField('TANGGAL_LAHIR'));
					$reqTglPensiun= dateToPageCheck($set->getField('TANGGAL_PENSIUN'));
					$reqJenisKelamin= $set->getField('JENIS_KELAMIN');
					$reqJenisPegawai= $set->getField('JENIS_PEGAWAI_ID');
					$reqStatusPernikahan= $set->getField('STATUS_KAWIN');
					$reqKartuPegawai= $set->getField('KARTU_PEGAWAI');
					$reqSukuBangsa= $set->getField('SUKU_BANGSA');
					$reqGolDarah= $set->getField('GOLONGAN_DARAH');
					$reqAkses= $set->getField('ASKES');
					$reqTaspen= $set->getField('TASPEN');
					$reqAlamat= $set->getField('ALAMAT');
					$reqNPWP= $set->getField('NPWP');
					$reqNIK= $set->getField('NIK');
					$reqRT= $set->getField('RT');
					$reqRW= $set->getField('RW');
					$reqEmail= $set->getField('EMAIL');
					$reqPropinsiId= $set->getField('PROPINSI_ID');
					$reqPropinsi= $set->getField('PROPINSI_NAMA');
					$reqKabupatenId= $set->getField('KABUPATEN_ID');
					$reqKabupaten= $set->getField('KABUPATEN_NAMA');
					$reqKecamatanId= $set->getField('KECAMATAN_ID');
					$reqKecamatan= $set->getField('KECAMATAN_NAMA');
					$reqDesaId= $set->getField('KELURAHAN_ID');
					$reqDesa= $set->getField('KELURAHAN_NAMA');
					$reqBank= $set->getField('BANK_ID');
					$reqNoRekening= $set->getField('NO_REKENING');
					$reqPangkatTerkahir= $set->getField('GOL_RUANG');
					$reqTMTPangkat= $set->getField('TMT_PANGKAT');
					$reqJabatanTerkahir= $set->getField('JABATAN');
					$reqTMTJabatan= $set->getField('TMT_JABATAN');
					$reqPendidikanTerkahir= $set->getField('PENDIDIKAN');
					$reqJurusanTerkahir= $set->getField('JURUSAN');
					$reqTahunLulus= $set->getField('TAHUN');
					$reqGambar= $set->getField('FOTO_BLOB');
					$reqAgamaId= $set->getField('AGAMA_ID');
					$reqTelepon= $set->getField('TELEPON');
					$reqKodePos= $set->getField('KODEPOS');
					$reqKedudukanId= $set->getField('KEDUDUKAN_ID');
					$reqKonversiNIP= $set->getField('SK_KONVERSI_NIP');
					$reqSatuanKerjaNama= $set->getField('NMSATKER');
					$reqSatuanKerja= $set->getField('SATKER_ID');
					$reqFotoBlob= $set->getField('FOTO_BLOB');
					$reqFotoBlobOther=$set->getField('FOTO_BLOB_OTHER');
					

					$set->setField("LAST_CREATE_USER", "");
					$set->setField("LAST_CREATE_DATE", "CURRENT_DATE");	
					$set->setField("LAST_CREATE_SATKER", "");
					$set->setField('PEGAWAI_ID', $reqPegawaiId);
					$set->setField('NIP_LAMA', $reqNipLama);
					$set->setField('NIP_BARU', $reqNipBaru);
					$set->setField('SATKER_ID', $reqSatuanKerja);
					$reqNama= str_replace("\'", "''", $reqNama);	
					$set->setField('NAMA', $reqNama);
					$set->setField('TIPE_PEGAWAI_ID', $reqTipePegawai);
					$set->setField('GELAR_DEPAN', $reqGelarDepan);
					$set->setField('GELAR_BELAKANG', $reqGelarBelakang);
					$set->setField('STATUS_PEGAWAI', ValToNullDB($reqStatusPegawai));
					$set->setField('TEMPAT_LAHIR', $reqTempatLahir);
					$set->setField('TANGGAL_LAHIR', dateToDBCheck($reqTanggalLahir));
					$set->setField('TANGGAL_PENSIUN', dateToDBCheck($reqTanggalPensiun));
					$set->setField('TANGGAL_PINDAH', dateToDBCheck($reqTanggalPindah));
					$set->setField('JENIS_KELAMIN', $reqJenisKelamin);
					$set->setField('JENIS_PEGAWAI_ID', ValToNullDB($reqJenisPegawai));
					$set->setField('STATUS_KAWIN', ValToNullDB($reqStatusPernikahan));
					$set->setField('KARTU_PEGAWAI', $reqKartuPegawai);
					$set->setField('SUKU_BANGSA', $reqSukuBangsa);
					$reqGolDarah = str_replace(" ", "", $reqGolDarah);	
					$set->setField('GOLONGAN_DARAH', $reqGolDarah);
					$set->setField('ASKES', $reqAkses);
					$set->setField('TASPEN', $reqTaspen);
					$set->setField('ALAMAT', setQuote($reqAlamat,1));
					$set->setField('NPWP', $reqNPWP);
					$set->setField('NIK', $reqNIK);
					$set->setField('RT', $reqRT);
					$set->setField('RW', $reqRW);
					$set->setField('EMAIL', $reqEmail);
					$set->setField('SK_KONVERSI_NIP', $reqKonversiNIP);
					$set->setField('TANGGAL_MATI', dateToDBCheck(''));
					$set->setField('TANGGAL_TERUSAN', dateToDBCheck(''));
					$set->setField('TANGGAL_UPDATE',"CURRENT_DATE");
					$set->setField('PROPINSI_ID', ValToNullDB($reqPropinsiId));
					$set->setField('KABUPATEN_ID', ValToNullDB($reqKabupatenId));
					$set->setField('KECAMATAN_ID', ValToNullDB($reqKecamatanId));
					$set->setField('KELURAHAN_ID', ValToNullDB($reqDesaId));
					$set->setField('BANK_ID', ValToNullDB($reqBank));
					$set->setField('NO_REKENING', $reqNoRekening);
					$set->setField('AGAMA_ID', ValToNullDB($reqAgamaId));
					$set->setField('TELEPON', $reqTelepon);
					$set->setField('KODEPOS', $reqKodePos);
					$set->setField('KEDUDUKAN_ID', ValToNullDB($reqKedudukanId));

					$reqSimpan= "";
					$reqStatusValidasi = $reqValidasiStatus;
					if(is_numeric($reqTempValidasiId))
					{
						$set->setField('VALIDASI', $reqStatusValidasi);
						$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

						if($set->updatepegawai())
						{
							$reqSimpan= "1";
							
						}
						else
						{
							$reqForm= "Penilaian Kerja";
						}
					}
				}

				elseif ($value == "pegawai_riwayat_kontrak_add") 
				{
					$set->selectByParamsKontrak(array('TEMP_VALIDASI_ID' => $key), -1, -1);
					// echo $set->query;exit;
					$set->firstRow();
					$reqTempValidasiId = $set->getField("TEMP_VALIDASI_ID");
					$reqPegawaiId = $set->getField("PEGAWAI_ID");

					$reqMasaKerjaBulan = $set->getField("MASA_KERJA_BULAN");
					$reqMasaKerjaTahun = $set->getField("MASA_KERJA_TAHUN");
					$reqNoSK = $set->getField("NO_SK");
					$reqMasaBerlaku = $set->getField("MASA_BERLAKU");
					$reqTglSK = $set->getField("TANGGAL_SK");
					$reqSelesai = dateToPageCheck($set->getField("SELESAI_KONTRAK"));
					$reqTMTSK = dateToPageCheck($set->getField("TMT_SK"));
					$reqGolonganPppkId = $set->getField("GOLONGAN_PPPK_ID");
					$reqPejabatPenetapId = $set->getField("PEJABAT_PENETAP_ID");
					$reqPejabatPenetap = $set->getField("PEJABAT_PENETAP");
					$reqGajiPokok = $set->getField("GAJI_POKOK");
					

					$set->setField('MASA_KERJA_BULAN', $reqMasaKerjaBulan);
					$set->setField('MASA_KERJA_TAHUN', $reqMasaKerjaTahun);
					$set->setField('NO_SK', $reqNoSK);
					$set->setField('MASA_BERLAKU', ValToNullDB($reqMasaBerlaku));
					$set->setField('PEGAWAI_ID', $reqPegawaiId);
					$set->setField('TANGGAL_SK', dateToDBCheck($reqTglSK));
					$set->setField('SELESAI_KONTRAK', dateToDBCheck($reqSelesai));
					$set->setField('TMT_SK', dateToDBCheck($reqTMTSK));
					$set->setField('USER_APP_ID', $userLogin->UID);
					$set->setField('GOLONGAN_PPPK_ID', ValToNullDB($reqGolonganPppkId));
					$set->setField('PEJABAT_PENETAP_ID', ValToNullDB($reqPejabatPenetapId));	
					$set->setField('PEJABAT_PENETAP', strtoupper($reqPejabatPenetap));
					$set->setField('GAJI_POKOK', ValToNullDB(dotToNo($reqGajiPokok)));

					$reqSimpan= "";
					$reqStatusValidasi = $reqValidasiStatus;
					if(is_numeric($reqTempValidasiId))
					{
						$set->setField('VALIDASI', $reqStatusValidasi);
						$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

						if($set->updatekontrak())
						{
							$reqSimpan= "1";
							
						}
						else
						{
							$reqForm= "Masa Kerja";
						}
					}
				}

				elseif ($value == "riwayat_pendidikan_add") 
				{
					$set->selectByParamsPendidikanRiwayat(array('TEMP_VALIDASI_ID' => $key), -1, -1);
					// echo $set->query;exit;
					$set->firstRow();
					$reqTempValidasiId = $set->getField("TEMP_VALIDASI_ID");
					$reqPegawaiId = $set->getField("PEGAWAI_ID");

					$reqPendidikanId = $set->getField("PEGAWAI_PENDIDIKAN_ID");
					$reqPendidikan = $set->getField("PEGAWAI_PENDIDIKAN");
					$reqJurusanId = $set->getField("TINGKAT_PENDIDIKAN_ID");
					$reqJurusan = $set->getField("TINGKAT_PENDIDIKAN");
					$reqTglSTTB = dateToPageCheck($set->getField("TANGGAL_LULUS"));
					$reqTahun = $set->getField("TAHUN_LULUS");
					$reqNomorIjazah = $set->getField("NOMOR_IJAZAH");
					$reqNamaSekolah = $set->getField("NAMA_SEKOLAH");
					$reqGelarDepan = $set->getField("GELAR_DEPAN");
					$reqGelarBelakang = $set->getField("GELAR_BELAKANG");
					$reqPendidikanCpns = $set->getField("PENDIDIKAN_CPNS");
					

					$set->setField('PEGAWAI_PENDIDIKAN_ID', ValToNullDB($reqPendidikanId));
					$set->setField('TINGKAT_PENDIDIKAN_ID', ValToNullDB($reqJurusanId));
					$set->setField('TANGGAL_LULUS', dateToDBCheck($reqTglSTTB));
					$set->setField('TAHUN_LULUS', $reqTahun);
					$set->setField('NOMOR_IJAZAH', $reqNomorIjazah);
					$set->setField('NAMA_SEKOLAH', $reqNamaSekolah);
					$set->setField('GELAR_DEPAN', $reqGelarDepan);
					$set->setField('GELAR_BELAKANG', $reqGelarBelakang);
					$set->setField('PENDIDIKAN_CPNS', ValToNullDB($reqPendidikanCpns));
					$set->setField('PEGAWAI_ID', $reqPegawaiId);
					$set->setField('USER_APP_ID', $userLogin->UID);

					$reqSimpan= "";
					$reqStatusValidasi = $reqValidasiStatus;
					if(is_numeric($reqTempValidasiId))
					{
						$set->setField('VALIDASI', $reqStatusValidasi);
						$set->setField("LAST_UPDATE_USER", $userLogin->idUser);
						$set->setField("LAST_UPDATE_DATE", "CURRENT_DATE");	
						$set->setField("LAST_UPDATE_SATKER", $userLogin->userSatkerId);
						$set->setField('TEMP_VALIDASI_ID', $reqTempValidasiId);

						if($set->updateriwayatpendidikan())
						{
							$reqSimpan= "1";
							
						}
						else
						{
							$reqForm= "Masa Kerja";
						}
					}
				}

			}
		

			if($reqSimpan == 1 )
			{

				if ($reqStatusValidasi == 1)
				{
					echo json_response(200, 'Data berhasil divalidasi');
				}
				else
				{
					echo json_response(200, 'Data berhasil ditolak');
				}
				
			}
			else
			{
				if ($reqForm=="")
				{
					echo json_response(400, 'Data gagal divalidasi');
				}
				else
				{
					echo json_response(400, 'Data '.$reqForm.' gagal divalidasi');
				}
				
			}
		}



		
	}

}
?>