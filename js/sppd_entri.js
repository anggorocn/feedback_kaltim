function setKotaTujuan(tempId)
{
	var url = 'sppd/kota_json/kota_combo_json?reqId='+tempId;
	$('#reqKotaTujuan').combobox('reload', url);
}

function setKotaTujuan2(tempId)
{
	var url = 'sppd/kota_json/kota_combo_json?reqId='+tempId;
	$('#reqKotaTujuan2').combobox('reload', url);
}

function setKotaBerangkat(tempId)
{
	var url = 'sppd/kota_json/kota_combo_json?reqId='+tempId;
	$('#reqKotaBerangkat').combobox('reload', url);
}

function diff(start, end) {
	start = start.split("-");
	end = end.split("-");
	var date1 = new Date(start[2]+'/'+start[1]+'/'+start[0]);
	var date2 = new Date(end[2]+'/'+end[1]+'/'+end[0]);
	var diffDays = parseInt((date2 - date1) / (1000 * 60 * 60 * 24)); 
	$('#reqLamaPerjalanan').val(diffDays + 1);
	/* CEK VALID TANGGAL BERANGKAT TANGGAL KEMBALI */
	cekTanggalBerangkat();
	
}

function cekTanggalBerangkat()
{
	tempPegawaiId=$("#reqPegawaiId").val();
	tempTanggalBerangkat= $('#reqTanggalBerangkat').datebox('getValue');
	tempTanggalKembali= $('#reqTanggalKembali').datebox('getValue');
	
	$.getJSON('../json-sppd/check_tanggal_berangkat_json.php?reqId=' + $("#reqId").val() + '&reqPegawaiId=' + tempPegawaiId + '&reqTanggalBerangkat=' + tempTanggalBerangkat + '&reqTanggalKembali=' + tempTanggalKembali,
	  function(data){
		tempSppdId=data.tempSppdId;
		tempJenis=data.tempJenis;
		tempKota=data.tempKota;
		if(tempSppdId == "")
		{
			$("#reqValidasi").val("1");
			$("#reqValidasiInfo").val("");
		}
		else
		{
			$("#reqValidasi").val("0");
			$("#reqValidasiInfo").val("Pada tanggal terpilih pegawai sedang melaksanakan "+tempJenis+' di '+tempKota);
		}		
	});
	
}

function openPencarianKaryawan()
{
	parent.OpenDHTML('app/loadUrl/sppd/pegawai_perjalanan_dinas_pencarian.php', 'Pencarian Pegawai', 900, 600)
}

function openPencarianInstansi(index)
{
	parent.OpenDHTML('app/loadUrl/sppd/instansi_pencarian.php?reqRowId='+index, 'Pencarian Instansi', 700, 400)
}

var tempRowId= tempInstansiId= tempInstansi= tempKota= tempKategoriWilayahId= tempKategoriWilayah= tempJarakKm= "";
var tempId= tempJabatan= tempDepartemen= tempNama= tempKelas= "";
function OptionSet(id)
{
	tempId=id;
	$.getJSON('sppd/pegawai_json/pegawai_get_info?reqId=' + id,
	  function(data){
		tempDepartemen=data.tempDepartemen;
		tempJabatan=data.tempJabatan;
		tempKelas=data.tempKelas;
		tempNama=data.tempNama;
	  });
	  
	  setTimeout(setValueJson, 1000);
}

function setValueJson()
{
	$('#reqNamaKaryawan').val(tempNama);
	$('#reqPegawaiId').val(tempId);
	$('#reqGrade').val(tempKelas);
	$('#ccJabatan').val(tempJabatan);
	$('#reqDepartemen').val(tempDepartemen);
}

function addRowMaksud(tableID) {
	var table = document.getElementById(tableID);

	var rowCount = table.rows.length;
	//var id_row = rowCount-1;
	var id_row = rowCount;
	var row = table.insertRow(rowCount);
	
	var column4= row.insertCell(0);
	var element4 = document.createElement("textarea");
	element4.type = "text";
	element4.name = "reqMaksud[]";
	element4.id = "reqMaksud"+id_row;
	element4.style.width = '558px';
	element4.style.height = '50px';
	column4.appendChild(element4);
	
	var add_label = document.createElement('label');
	column4.appendChild(add_label);
	
	add_label.innerHTML = '&nbsp;</a>&nbsp;<a style="cursor:pointer" onclick="deleteRowMaksud(\'dataTableMaksud\', this)"><img src="images/delete-icon.png" width="15" height="15" border="0" /></a>';
}

function deleteRowMaksud(tableID, id) {
	if(confirm('Apakah anda ingin menghapus data terpilih?') == false)
		return "";
			
	try {
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;
	var id=id.parentNode.parentNode.parentNode.rowIndex;
	
	for(var i=0; i<=rowCount; i++) {
		if(id == i) {
			table.deleteRow(i);
		}
	}
	}catch(e) {
		alert(e);
	}
}

function deleteRowMaksudPhp(tableID, id) {
	if(confirm('Apakah anda ingin menghapus data terpilih?') == false)
		return "";
		
			
	try {
	var table = document.getElementById(tableID);
	var rowCount = table.rows.length;
	var id=id.parentNode.parentNode.rowIndex;
	
	for(var i=0; i<=rowCount; i++) {
		if(id == i) {
			table.deleteRow(i);
		}
	}
	}catch(e) {
		alert(e);
	}
}