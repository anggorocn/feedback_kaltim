<?
include_once("functions/personal.func.php");
$this->load->model("base-validasi/PegawaiJabatan");
$this->load->model("base-validasi/HapusData");
$this->load->model("base-personal/JenisJabatan");
$this->load->model("base-validasi/Eselon");
$this->load->model("base-personal/KategoriJabatan");
$this->load->model("base-personal/JabatanFungsional");
$this->load->model("base-personal/JabatanPelaksana");
$this->load->model("base-personal/JabatanStruktural");


$reqPegawaiId= $this->input->get('reqPegawaiId');
$reqRowId= $this->input->get('reqRowId');
$reqDetilId= $this->input->get('reqDetilId');
$reqMode= $this->input->get('reqMode');
$reqValidasiHapusId= $this->input->get('reqValidasiHapusId');
$adminuserid= $this->adminuserid;


$statement="";
$sOrder=" ORDER BY JENIS_JABATAN_ID ASC";
$set= new JenisJabatan();
$arrjenisjabatan= [];
$set->selectByParams(array(), -1,-1,$statement,$sOrder);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	$arrdata["id"]= $set->getField("JENIS_JABATAN_ID");
	$arrdata["text"]= $set->getField("NAMA");
	array_push($arrjenisjabatan, $arrdata);
}
unset($set);

$statement="";
$sOrder=" ORDER BY NAMA ASC";
$set= new Eselon();
$arreselon= [];
$set->selectByParams(array(), -1,-1,$statement,$sOrder);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	$arrdata["id"]= $set->getField("ESELON_ID");
	$arrdata["text"]= $set->getField("NAMA");
	array_push($arreselon, $arrdata);
}
unset($set);

$statement="";
$sOrder=" ORDER BY TIPE_PEGAWAI_NEW_ID ASC";
$set= new KategoriJabatan();
$arrkategorijabatan= [];
$set->selectByParams(array(), -1,-1,$statement,$sOrder);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	$arrdata["id"]= $set->getField("TIPE_PEGAWAI_NEW_ID");
	$arrdata["text"]= $set->getField("NAMA");
	array_push($arrkategorijabatan, $arrdata);
}
unset($set);

$statement="";
$sOrder=" ORDER BY JABATAN_FUNGSIONAL_NEW_ID ASC";
$set= new JabatanFungsional();
$arrjabatanfungsional= [];
$set->selectByParams(array(), -1,-1,$statement,$sOrder);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	$arrdata["id"]= $set->getField("JABATAN_FUNGSIONAL_NEW_ID");
	$arrdata["text"]= $set->getField("NAMA");
	array_push($arrjabatanfungsional, $arrdata);
}
unset($set);

$statement="";
$sOrder=" ORDER BY JABATAN_PELAKSANA_NEW_ID ASC";
$set= new JabatanPelaksana();
$arrjabatanPelaksana= [];
$set->selectByParams(array(), -1,-1,$statement,$sOrder);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	$arrdata["id"]= $set->getField("JABATAN_PELAKSANA_NEW_ID");
	$arrdata["text"]= $set->getField("NAMA");
	array_push($arrjabatanPelaksana, $arrdata);
}
unset($set);

$statement="";
$sOrder=" ORDER BY JABATAN_STRUKTURAL_NEW_ID ASC";
$set= new JabatanStruktural();
$arrjabatanStruktural= [];
$set->selectByParams(array(), -1,-1,$statement,$sOrder);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	$arrdata["id"]= $set->getField("JABATAN_STRUKTURAL_NEW_ID");
	$arrdata["text"]= $set->getField("NAMA");
	array_push($arrjabatanStruktural, $arrdata);
}
unset($set);

// print_r($arrjabatanStruktural);exit;

$set = new PegawaiJabatan();
if($reqMode == 'edit' || $reqMode == 'cancel' || $reqMode == 'view')
{
	if($reqDetilId == "")
	{
		$set->selectByParams(array('A.PEGAWAI_JABATAN_ID'=>$reqRowId));
	}
	else
	{
		$set->selectByParams(array('A.TEMP_VALIDASI_ID'=>$reqDetilId));
	}
	// echo $set->query;exit;
	$set->firstRow();
	$reqRowId= $set->getField('PEGAWAI_JABATAN_ID');
	$reqPerubahanData= $set->getField('PERUBAHAN_DATA');
	$reqTempValidasiId=$set->getField('TEMP_VALIDASI_ID');
	$reqNomor= $set->getField('NOMOR');$valNomor= checkwarna($reqPerubahanData, 'NOMOR');
	$reqTmtJabatan= dateToPageCheck($set->getField('TMT_JABATAN'));$valTmtJabatan= checkwarna($reqPerubahanData, 'TMT_JABATAN', "date");
	$reqTahun= $set->getField('TAHUN');$valTahun= checkwarna($reqPerubahanData, 'TAHUN');
	$reqJenisJabatan=$set->getField('JENIS_JABATAN_ID');$valJenisJabatanId= checkwarna($reqPerubahanData, 'JENIS_JABATAN_ID',$arrjenisjabatan, array("id","text"));
	$reqKelJab=$set->getField('KELAS_JABATAN');
	$reqNamaJabatan="";
	$reqJabatanId="";
	$valNamaJabatan="";
	if($reqJenisJabatan == 1)
	{
		$reqNamaJabatan=$set->getField('NAMA_STRUKTURAL');
		$reqJabatanId=$set->getField('JABATAN_STRUKTURAL_NEW_ID');$valNamaJabatan= checkwarna($reqPerubahanData, 'JABATAN_STRUKTURAL_NEW_ID',$arrjabatanStruktural, array("id","text"));
	}
	else if($reqJenisJabatan == 2 || $reqJenisJabatan == 4)
	{
		$reqNamaJabatan=$set->getField('NAMA_FUNGSIONAL');
		$reqJabatanId=$set->getField('JABATAN_FUNGSIONAL_NEW_ID');$valNamaJabatan= checkwarna($reqPerubahanData, 'JABATAN_FUNGSIONAL_NEW_ID',$arrjabatanfungsional, array("id","text"));
	}
	else if ($reqJenisJabatan == 3)
	{
		$reqNamaJabatan=$set->getField('NAMA_PELAKSANA');
		$reqJabatanId=$set->getField('JABATAN_PELAKSANA_NEW_ID');$valNamaJabatan= checkwarna($reqPerubahanData, 'JABATAN_PELAKSANA_NEW_ID',$arrjabatanPelaksana, array("id","text"));
	}
	// print_r ($valNamaJabatan);exit;

	$reqKategoriJabatanId=$set->getField('TIPE_PEGAWAI_NEW_ID');$valKategoriJabatanId= checkwarna($reqPerubahanData, 'TIPE_PEGAWAI_NEW_ID',$arrkategorijabatan, array("id","text"));
	$reqBup=$set->getField('BUP');$valBup= checkwarna($reqPerubahanData, 'BUP');
	$reqKategoriJabatan=$set->getField('KATEGORI_JABATAN');
	$reqTanggalSuratKeputusan=dateToPageCheck($set->getField('TANGGAL_SK'));$valTanggalSuratKeputusan= checkwarna($reqPerubahanData, 'TANGGAL_SK', "date");
	$reqNoSk=$set->getField('NO_SK');$valNoSk= checkwarna($reqPerubahanData, 'NO_SK');
	$reqTugasTambahan=$set->getField('TUGAS_TAMBAHAN_NAMA');$valTugasTambahan= checkwarna($reqPerubahanData, 'TUGAS_TAMBAHAN_NAMA');
	$reqTugasTambahanId=$set->getField('TUGAS_TAMBAHAN_ID');
	$reqValidasi= $set->getField('VALIDASI');
	$reqUnorId= $set->getField('UNOR_ID');
	$reqLinkFile= $set->getField('LINK_FILE');
	$reqPegawaiJabatanFileId= $set->getField('PEGAWAI_JABATAN_FILE_ID');
	$reqLinkFileServer= $set->getField('LINK_SERVER');
	
	// echo $reqPegawaiJabatanFileId;exit;

	$reqEselon=$set->getField('ESELON_NAMA');
	$reqEselonId=$set->getField('ESELON_ID');$valEselonId= checkwarna($reqPerubahanData, 'ESELON_ID',$arreselon, array("id","text"));	
	unset($set);
}
if($reqDetilId == "")
{
	$reqMode = 'insert';
}
else
{
	$reqMode = 'update';
}

$set = new PegawaiJabatan();
$statement=" AND A.PEGAWAI_ID ='".$reqPegawaiId."'";
if ($reqMode == 'update' || !empty($reqRowId ))
{
	$statement=" AND A.SATKER_ID ='".$reqUnorId."'";
}


$set->selectByParamsUnitKerja(array(),-1,-1,$statement);
// echo $set->query;exit;
$set->firstRow();
$reqUnor= $set->getField('SATKER_INDUK_NAMA');
$reqUnorId= $set->getField('SATKER_ID');
$reqSubUnit= $set->getField('SATKER');
// echo $reqSubUnit;exit;

?>
<script type="text/javascript" src="functions/globalfunction.js"></script>

<div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
	<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
		<div class="d-flex align-items-center flex-wrap mr-1">
			<div class="d-flex align-items-baseline flex-wrap mr-5">
				<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">Data Riwayat</a>
					</li>
					<li class="breadcrumb-item text-muted">
						<a class="text-muted">Jabatan</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
        	<div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="flaticon2-notepad text-primary"></i>
                    </span>
                    <h3 class="card-label">Jabatan</h3>
                </div>
            </div>
       
            <div class="modal fade" id="jabatanmodal" tabindex="-1" role="dialog" aria-labelledby="jabatanmodal" aria-hidden="true">
            	<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            		<div class="modal-content">
            			<div class="modal-header">
            				<h5 class="modal-title" id="exampleModalLabel">Tugas Tambahan</h5>
            				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
            					<i aria-hidden="true" class="ki ki-close"></i>
            				</button>
            			</div>
            			<div class="modal-body" id="popup">
            				<script type="text/JavaScript">
            					$("#popup").load("app/loadUrl/main/tugas_tambahan_cari.php");
            				</script>
            			</div>
            		</div>
            	</div>
            </div>

            <form class="form" id="ktloginform" method="POST" enctype="multipart/form-data">
	        	<div class="card-body">
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Jenis Jabatan
	        			<?
	        			if(!empty($valJenisJabatanId['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valJenisJabatanId['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<select class="form-control select2 <?=$valJenisJabatanId['warna']?>" id="ktjenisjabatan" name="reqJenisJabatan" >
	        					<option value=""></option>
	        					<?
	        					foreach($arrjenisjabatan as $item) 
	        					{
	        						$selectvalid= $item["id"];
	        						$selectvaltext= $item["text"];
	        					?>
	        					<option value="<?=$selectvalid?>" <? if($reqJenisJabatan == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
	        					<?
	        					}
	        					?>
	        				</select>
	        			</div>  				
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12"> Kategori Jabatan
	        			<?
	        			if(!empty($valKategoriJabatanId['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valKategoriJabatanId['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<select class="form-control select2 <?=$valKategoriJabatanId['warna']?>" <?=$disabled?>  id="ktkatjabatan" name="reqKategoriJabatan">
	        					<option value="<?=$reqKategoriJabatanId?>"><?=$reqKategoriJabatan?></option>
	        				</select>
	        				<span class="form-text text-muted"><label id="ktjabatandetil"></label></span>
	        				<input type="hidden" name="reqKategoriJabatanId" id="reqKategoriJabatanId" value="<?=$reqKategoriJabatanId?>" >
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Eselon
	        			<?
	        			if(!empty($valEselon['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valEselon['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control " <?=$disabled?> readonly style="background-color: #F3F6F9;"  placeholder=""name="reqEselon" id="reqEselon" value="<?=$reqEselon?>" />
	        				<input type="hidden" name="reqEselonId" id="reqEselonId" value="<?=$reqEselonId?>" >
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Nama Jabatan
	        			<?
	        			if(!empty($valNamaJabatan['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNamaJabatan['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-10 col-sm-12">
	        				<select class="form-control select2 <?=$valNamaJabatan['warna']?>" <?=$disabled?> id="ktnamajabatan" name="reqNamaJabatan">
	        					<option value="<?=$reqJabatanId?>"><?=$reqNamaJabatan?></option>
	        				</select>
	        				<span class="form-text text-muted"><label id="jabatandetil"></label></span>
	        				<input type="hidden" name="reqJabatanId" id="reqJabatanId" value="<?=$reqJabatanId?>" >
	        			</div>
	        		</div>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Bup</label>
	        			<div class="col-lg-2 col-sm-12">
	        				<input type="text" class="form-control"  <?=$disabled?>  readonly style="background-color: #F3F6F9;" name="reqBup" id="reqBup" value="<?=$reqBup?>" />
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Kelas Jab</label>
	        			<div class="col-lg-2 col-sm-12">
	        				<input type="text" class="form-control" <?=$disabled?> readonly style="background-color: #F3F6F9;"  placeholder=""name="reqKelJab" id="reqKelJab" value="<?=$reqKelJab?>" />
	        			</div>
	        		</div>
	        		<?
	        		if ($reqMode == 'update' || !empty($reqRowId ))
	        		{
	        		?>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Unit Organisasi</label>
	        			<div class="col-lg-10 col-sm-12">
	        				<select class="form-control select2 <?=$valNamaJabatan['warna']?>" <?=$disabled?> id="ktunor" name="reqUnor">
	        					<option value="<?=$reqUnorId?>"><?=$reqUnor?></option>
	        				</select>
	        				<span class="form-text text-muted"><label id="unordetil"></label></span>
	        				<input type="hidden" name="reqUnorId" id="reqUnorId" value="<?=$reqUnorId?>" >
	        			</div>
	        		</div>
	        		<?
	        		}
	        		else
	        		{
	        		?>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Unit Organisasi</label>
	        			<div class="col-lg-10 col-sm-12">
	        				<input type="text" class="form-control"  <?=$disabled?>  readonly style="background-color: #F3F6F9;" name="reqUnor" id="reqUnor" value="<?=$reqUnor?>" />
	        				<input type="hidden" name="reqUnorId" id="reqUnorId" value="<?=$reqUnorId?>" >
	        			</div>
	        		</div>
	        		<?
	        		}
	        		?>
	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12"> Sub Unit Organisasi</label>
	        			<div class="col-lg-10 col-sm-12">
	        				<input type="text" class="form-control"  <?=$disabled?>  readonly style="background-color: #F3F6F9;" name="reqSubUnit" id="reqSubUnit" value="<?=$reqSubUnit?>" />
	        			</div>
	        		</div>

	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">TMT Jabatan
	        			<?
	        			if(!empty($valTmtJabatan['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTmtJabatan['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-2 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control <?=$valTmtJabatan['warna']?>" id="kttmtjabatan" name="reqTmtJabatan" readonly="readonly" value="<?=$reqTmtJabatan?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tgl SK
	        			<?
	        			if(!empty($valTanggalSuratKeputusan['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTanggalSuratKeputusan['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-2 col-sm-12">
	        				<div class="input-group date">
		        				<input type="text" class="form-control <?=$valTanggalSuratKeputusan['warna']?>" id="kttmtjabatan" name="reqTanggalSuratKeputusan" readonly="readonly" value="<?=$reqTanggalSuratKeputusan?>" />
		        				<div class="input-group-append">
		        					<span class="input-group-text">
		        						<i class="la la-calendar"></i>
		        					</span>
		        				</div>
		        			</div>
	        			</div>
	        		</div>

	        		<div class="form-group row">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">No. SK
	        			<?
	        			if(!empty($valNoSk['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valNoSk['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-4 col-sm-12">
	        				<input type="text" class="form-control <?=$valNoSk['warna']?>" name="reqNoSk" id="reqNoSk" value="<?=$reqNoSk?>" />
	        			</div>
	        		</div>

	        		<div class="form-group row" id ="tugas_tambahan">
	        			<label class="col-form-label text-right col-lg-2 col-sm-12">Tugas Tambahan
	        			<?
	        			if(!empty($valTugasTambahan['data']))
	        			{
	        			?>
	        			<i class="fa fa-question-circle" rel="tooltip" data-html="true" title="<?=$valTugasTambahan['data']?>"></i>
	        			<?
	        			}
	        			?>
	        			</label>
	        			<div class="col-lg-5 col-sm-12">
	        				<input type="text" readonly style="background-color: #F3F6F9;"  class="form-control <?=$valTugasTambahan['warna']?>" name="reqTugasTambahan" id="reqTugasTambahan" value="<?=$reqTugasTambahan?>" />
	        				<input type="hidden" name="reqTugasTambahanId" id="reqTugasTambahanId" value="<?=$reqTugasTambahanId?>" />
	        			</div>
	        			<i style="float: left;width: 5%;padding: 10px;background: #2196F3;color: white;font-size: 17px;border: 1px solid grey;border-left: none;cursor: pointer;" class="fa fa-search" data-toggle="modal" data-target="#jabatanmodal"></i>
	        		</div>

	        	</div>
	        	<div class="card-footer">
	        		<div class="row">
	        			<div class="col-lg-9">
	        				<input type="hidden" name="reqMode" value="<?=$reqMode?>">
	        				<input type="hidden" name="reqRowId" value="<?=$reqRowId?>">
	        				<input type="hidden" name="reqTempValidasiId" value="<?=$reqTempValidasiId?>">
	        				<input type="hidden" name="reqPegawaiId" value="<?=$reqPegawaiId?>">
	        				<input type="hidden" name="reqTipePegawaiId" value="<?=$reqTipePegawaiParentId?>">
	        				<?
	        				if(!empty($adminuserid) && empty($reqValidasi) && !empty($reqTempValidasiId))
	        				{
	        				?>
	        					<button type="submit" id="ktloginformsubmitbutton"  name="reqStatusValidasi"  class="btn btn-primary font-weight-bold mr-2" value="1">Validasi</button>
	        					<button type="submit" id="ktloginformsubmitbutton" name="reqStatusValidasi"  class="btn btn-danger font-weight-bold mr-2" value="0">Tolak</button>
	        				<?
	        				}
	        				else
	        				{
	        				?>
		        				<button type="submit" id="ktloginformsubmitbutton"  class="btn btn-primary font-weight-bold mr-2">Simpan</button>
		        				<button onclick="kembali()" type="button" class="btn btn-warning font-weight-bold mr-2">Kembali</button>
	        				<?
	        				}
	        				?>

	        				<?if(empty($reqLinkFileServer))
	        				{
	        				?>
		        				<input type="button" id="upload" class="btn btn-success font-weight-bold mr-2" value="Upload" onclick="document.getElementById('reqLinkFile').click();" />
		        				<input type="file" style="display:none;" id="reqLinkFile" name="reqLinkFile"  accept=".pdf"/>
	        				<?
	        				}
	        				else
	        				{
	        				?>
	        					<a class="btn btn-success font-weight-bold mr-2" href="app/loadUrl/main/viewer?reqForm=jabatan&reqPegawaiJabatanFileId=<?=$reqPegawaiJabatanFileId?>" target="_blank">Download</a>
	        					<a class="btn btn-danger font-weight-bold mr-2" onclick="btndeletefile(<?=$reqPegawaiJabatanFileId?>,<?=$reqPegawaiId?>,<?=$reqDetilId?>,<?=$reqRowId?>)">Hapus File</a>
	        				<?
	        				}
	        				?>
	        		
	        				
	        			</div>
	        		</div>
	        	</div>
	        </form>
        </div>
    </div>
</div>



<script type="text/javascript">
	$(document).on("input", "#reqTh,#reqBl,#reqGajiPokok", function() {
		this.value = this.value.replace(/\D/g,'');
	});
	function kembali() {
		window.location.href='app/index/pegawai_jabatan_tipe'
	}

    function tampilKatJabatan(val) {
        if (val.loading) return val.text;
        var markup = "<div class='select2-result-repository clearfix'>" +
	            "<div class='select2-result-repository__meta'>" +
	            	"<div class='select2-result-repository__title'>" + val.description + "</div>" +
	            "</div>" +
            "</div>";
        return markup;
    }
    function pilihKatJabatan(val) {
    	$("#ktjabatandetil").text(val.description);
    	$("#reqKategoriJabatan").val(val.text);	
        // console.log(val);
        return val.text;
    }

	$('#ktjenisjabatan').select2({
		placeholder: "Pilih Jabatan"
	});

	$('#kteselon').select2({
		placeholder: "Pilih Eselon"
	});


    arrows= {leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'};
	$('#ktttglsk,#kttmtjabatan').datepicker({
		todayHighlight: true
		, autoclose: true
		, orientation: "bottom left"
		, clearBtn: true
		, format: 'dd-mm-yyyy'
		, templates: arrows
	}).on("changeDate", function (e) {
		var date = $(this).datepicker('getDate'),
		day  = date.getDate(),  
		month = date.getMonth() + 1,              
		year =  date.getFullYear();
		setTahun(year);
	});

	var reqJenisJabatan = '<?=$reqJenisJabatan?>';
	var reqKategoriJabatanId = '<?=$reqKategoriJabatanId?>';

	// if (reqJenisJabatan == 1 || reqJenisJabatan == 4)
	// console.log(reqJenisJabatan);
	if (reqJenisJabatan == 4)
	{
		$('#tugas_tambahan').show();
	}
	else
	{
		$('#tugas_tambahan').hide();
	}

	if(reqKategoriJabatanId == 16)
	{
		$('#tugas_tambahan').hide();
	}
	else
	{
		//$('#tugas_tambahan').show();	
	}

	var jenisjabatanid = katjabatanid= url ="";
	$("#ktjenisjabatan").change(function (event) {
		var jenisjabatanid = $('#ktjenisjabatan').val();
		$('#ktkatjabatan').val('');
		$('#ktjabatandetil').html('');
		$('#ktnamajabatan').val('');
		$('#jabatandetil').html('');
		$('#reqBup').val('');
		$('#reqKelJab').val('');
		$('#ktnamajabatan').prop('disabled', true);
		$('#ktkatjabatan').prop('disabled', true);
		// if(jenisjabatanid == 1 || jenisjabatanid == 4 )
		if(jenisjabatanid == 4)
		{
			$('#tugas_tambahan').show();
		}
		else
		{
			$('#tugas_tambahan').hide();	
		}
		// console.log(jenisjabatanid);
		katjabatan(jenisjabatanid);		
	});

	$("#ktkatjabatan").change(function (event) {
		var jenisjabatanid = $('#ktjenisjabatan').val();
		var katjabatanid = $('#ktkatjabatan').val();
		$('#ktnamajabatan').val('');
		$('#jabatandetil').html('');
		// console.log(jenisjabatanid);
		if(katjabatanid == 16 || jenisjabatanid == 1)
		{
			$('#tugas_tambahan').hide();
		}
		else
		{
			$('#tugas_tambahan').show();	
		}
		// console.log(katjabatanid);

		namajabatan(katjabatanid);		
	});


	// console.log(reqJenisJabatan);

	if(jenisjabatanid =="")
	{
		$('#ktkatjabatan').prop('disabled', true);
		$('#ktnamajabatan').prop('disabled', true);
		$('#kteselon').prop('disabled', true);
	}

	if (reqJenisJabatan)
	{
		$('#ktkatjabatan').prop('disabled', false);
		$('#ktnamajabatan').prop('disabled', false);
		katjabatan(reqJenisJabatan);
	}
	if (reqKategoriJabatanId) 
	{
		// console.log(reqKategoriJabatanId);
		namajabatan(reqKategoriJabatanId);	
	}

	function tampilNamaJabatan(val) {
		if (val.loading) return val.text;
		var markup = "<div class='select2-result-repository clearfix'>" +
		"<div class='select2-result-repository__meta'>" +
		"<div class='select2-result-repository__title'>" + val.description + "</div>" +
		"</div>" +
		"</div>";
		return markup;
	}
	function pilihNamaJabatan(val) {
		// console.log(val);
		$("#jabatandetil").text(val.description);
		$("#reqJabatanId").val(val.id);
		$("#reqNamaJabatan").val(val.text);
		if (val.eselon_id) 
		{
			$("#reqEselonId").val(val.eselon_id);
			$("#reqEselon").val(val.eselon_nama);	
		}
		// console.log(katjabatanid);
		if (val.bup) 
		{
			$("#reqBup").val(val.bup);	
		}
		if (val.kelas_jabatan) 
		{
			$("#reqKelJab").val(val.kelas_jabatan);	
		}
        // console.log(val);
        return val.text;
    }

    function tampilUnor(val) {
		if (val.loading) return val.text;
		var markup = "<div class='select2-result-repository clearfix'>" +
		"<div class='select2-result-repository__meta'>" +
		"<div class='select2-result-repository__title'>" + val.description + "</div>" +
		"</div>" +
		"</div>";
		return markup;
	}

    function pilihUnor(val) {
		// console.log(val);
		$("#unordetil").text(val.description);
		$("#reqUnorId").val(val.id);
		$("#reqUnor").val(val.text);
		if (val.sub_unor) 
		{
			$("#reqSubUnit").val(val.sub_unor);	
		}
		
        return val.text;
    }

	function katjabatan(jenisjabatanid) {
		if (jenisjabatanid)
		{
			var url = "json-validasi/combo_json/autocompleteKategoriJabatan?reqJenisJabatanId="+jenisjabatanid;
			$('#ktkatjabatan').prop('disabled', false);
			if (jenisjabatanid == 1)
			{
				$('#kteselon').prop('disabled', false);
			}
			else 
			{
				$('#kteselon').prop('disabled', true);
			}
			$('#kttmtjabatan').prop('disabled', false);
			$('#ktttglsk').prop('disabled', false);
			$('#reqNoSk').prop('disabled', false);
			
		}
		else
		{
			var url = "json-validasi/combo_json/autocompleteKategoriJabatan";
		}
		
		$("#ktkatjabatan").select2({
			placeholder: "Pilih Kategori Jabatan",
			allowClear: true,
			ajax: {
				url: url,
				dataType: 'json',
				delay: 250,
				data: function(params) {
					return {
						q: params.term
						, page: params.page
					};
				},
				processResults: function(data, params) {
					params.page = params.page || 1;
					return {
						results: data.items
						, pagination: {
							more: (params.page * 30) < data.total_count && data.items != ""
						}
					};
				},
				cache: true
			},
			escapeMarkup: function(markup) {
				return markup;
		        }, // let our custom formatter work
		        minimumInputLength: 1,
		        templateResult: tampilKatJabatan, // omitted for brevity, see the source of this page
		        templateSelection: pilihKatJabatan // omitted for brevity, see the source of this page
		    });
	}

	function namajabatan(katjabatanid) {

		if (katjabatanid)
		{
			var url = "json-validasi/combo_json/autocompletejabatannew?reqKategoriJabatanId="+katjabatanid;
			$('#ktnamajabatan').prop('disabled', false);
		}
		else
		{
			var url = "";
		}
		// console.log(katjabatanid);
	
		$("#ktnamajabatan").select2({
			placeholder: "Pilih Nama Jabatan",
			allowClear: true,
			ajax: {
				url: url,
				dataType: 'json',
				delay: 250,
				data: function(params) {
					return {
						q: params.term
						, page: params.page
					};
				},
				processResults: function(data, params) {
					params.page = params.page || 1;
					return {
						results: data.items
						, pagination: {
							more: (params.page * 30) < data.total_count && data.items != ""
						}
					};
				},
				cache: true
			},
			escapeMarkup: function(markup) {
				return markup;
		        }, // let our custom formatter work
		        minimumInputLength: 1,
		        templateResult: tampilNamaJabatan, // omitted for brevity, see the source of this page
		        templateSelection: pilihNamaJabatan // omitted for brevity, see the source of this page
		    });

	}

	$("#ktunor").select2({
		placeholder: "Pilih Unor",
		allowClear: true,
		ajax: {
			url: "json-validasi/combo_json/autocompleteunor",
			dataType: 'json',
			delay: 250,
			data: function(params) {
				return {
					q: params.term
					, page: params.page
				};
			},
			processResults: function(data, params) {
				params.page = params.page || 1;
				return {
					results: data.items
					, pagination: {
						more: (params.page * 30) < data.total_count && data.items != ""
					}
				};
			},
			cache: true
		},
		escapeMarkup: function(markup) {
			return markup;
		}, 
		minimumInputLength: 1,
		templateResult: tampilUnor,
		templateSelection: pilihUnor 
	});


	function setTahun(val) {
    	$("#reqTahun").val(val);
    }

	var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';
	jQuery(document).ready(function() {

		var form = KTUtil.getById('ktloginform');
		var formSubmitUrl = "json-validasi/personal_json/jsonpegawaijabatanadd";
		var formSubmitButton = KTUtil.getById('ktloginformsubmitbutton');
		if (!form) {
			return;
		}
		FormValidation
		.formValidation(
			form,
			{
				fields: {
					reqInstansi: {
						validators: {
							notEmpty: {
								message: 'Pejabat Penetap harus diisi'
							}
						}
					},
					reqNoSK: {
						validators: {
							notEmpty: {
								message: 'No SK harus diisi'
							}
						}
					},
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					submitButton: new FormValidation.plugins.SubmitButton(),
					bootstrap: new FormValidation.plugins.Bootstrap()
				}
			}
			)
		.on('core.form.valid', function() {
				// Show loading state on button
				KTUtil.btnWait(formSubmitButton, _buttonSpinnerClasses, "Please wait");
				var formData = new FormData(document.querySelector('form'));
				$.ajax({
					url: formSubmitUrl,
					data: formData,
					processData: false,
					contentType: false,
					type: 'POST',
					dataType: 'json',
					success: function (response) {
			        	// console.log(response); return false;
			        	Swal.fire({
			        		text: response.message,
			        		icon: "success",
			        		buttonsStyling: false,
			        		confirmButtonText: "Ok",
			        		customClass: {
			        			confirmButton: "btn font-weight-bold btn-light-primary"
			        		}
			        	}).then(function() {
			        		document.location.href = "app/index/pegawai_jabatan_tipe";
			        	});
			        },
			        error: function(xhr, status, error) {
			        	var err = JSON.parse(xhr.responseText);
			        	Swal.fire("Error", err.message, "error");
			        },
			        complete: function () {
			        	KTUtil.btnRelease(formSubmitButton);
			        }
			    });
			})
		.on('core.form.invalid', function() {
			Swal.fire({
				text: "Sorry, looks like there are some errors detected, please try again.",
				icon: "error",
				buttonsStyling: false,
				confirmButtonText: "Ok, got it!",
				customClass: {
					confirmButton: "btn font-weight-bold btn-light-primary"
				}
			}).then(function() {
				KTUtil.scrollTop();
			});
		});
	});

	function addJabatan(id,nama) {
		$("#reqTugasTambahanId").val(id);
		$("#reqTugasTambahan").val(nama);
	}

	function closePopup()
	{
		$('#jabatanmodal').modal('toggle');
	}

	function btndeletefile(fileid,reqPegawaiId,reqDetilId,reqRowId) {
		if(fileid !== "")
        {
            urlAjax= "json-validasi/personal_json/jsonpegawaijabatantipedeletefile?reqFileId="+fileid+"&reqPegawaiId="+reqPegawaiId+"&reqDetilId="+reqDetilId+"&reqRowId="+reqRowId;
            swal.fire({
                title: 'Apakah anda yakin untuk hapus file?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes'
            }).then(function(result) { 
                if (result.value) {
                    $.ajax({
                        url : urlAjax,
                        type : 'DELETE',
                        dataType:'json',
                        beforeSend: function() {
                            swal.fire({
                                title: 'Please Wait..!',
                                text: 'Is working..',
                                onOpen: function() {
                                    swal.showLoading()
                                }
                            })
                        },
                        success : function(data) { 
                            swal.fire({
                                position: 'center',
                                icon: "success",
                                type: 'success',
                                title: data.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(function() {
                                location.reload();
                            });
                        },
                        complete: function() {
                            swal.hideLoading();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            swal.hideLoading();
                            var err = JSON.parse(jqXHR.responseText);
                            Swal.fire("Error", err.message, "error");
                        }
                    });
                }
            });
        }
	}



</script>

