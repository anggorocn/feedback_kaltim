<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

function checkwarna($value, $id, $arrdata="", $arrdetil="")
{
	$str = $value;
	$obj = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $str), true );
	// print_r($obj);exit;
	if($obj[strtoupper($id)][1] == strtoupper($id))
	{
		if(!empty($arrdata))
		{
			$infodata= $obj[$id][0];
			
			if($arrdata == "date")
			{
				$infodata= dateToPageCheck($infodata);
				$infowarna= "bg-danger text-white";
			}
			else
			{
				$infodetil= in_array_column($infodata, $arrdetil[0], $arrdata);
				$infodata= $arrdata[$infodetil[0]][$arrdetil[1]];
				$infowarna= "wrap-ds-danger";
			}
		}
		else
		{
			$infodata= $obj[$id][0];
			if(empty($infodata))
			{
				$infodata= "Data kosong";
			}
			$infowarna= "bg-danger text-white";
		}
	}
	else
	{
		$infodata= $infowarna= "";
	}

	return array("data"=>$infodata, "warna"=>$infowarna);
}

function cutitahunan($id)
{
	$arrdata = array(
		array(
			"id" => "1",
			"text" => "Cuti Tahunan",
		),
		array(
			"id" => "2",
			"text" => "Cuti Besar",
		),
		array(
			"id" => "3",
			"text" => "Cuti Bersalin",
		),
		array(
			"id" => "4",
			"text" => "Cuti Sakit",
		),
		array(
			"id" => "5",
			"text" => "CLTN",
		),
		array(
			"id" => "6",
			"text" => "Perpanjangan CLTN",
		),
		array(
			"id" => "7",
			"text" => "Cuti Menikah",
		),
		array(
			"id" => "8",
			"text" => "Cuti karena alasan penting",
		)
	);
	return $arrdata;
}

function statusanak($id)
{
	$arrdata = array(
		array(
			"id" => "1",
			"text" => "Kandung",
		),
		array(
			"id" => "2",
			"text" => "Tiri",
		),
		array(
			"id" => "3",
			"text" => "Angkat",
		)
	);
	return $arrdata;
}

function jeniskelamin($id)
{
	$arrdata = array(
		array(
			"id" => "L",
			"text" => "L",
		),
		array(
			"id" => "P",
			"text" => "P",
		)
	);
	return $arrdata;
}
function jenisbahasa($id)
{
	$arrdata = array(
		array(
			"id" => "1",
			"text" => "Asing",
		),
		array(
			"id" => "2",
			"text" => "Daerah",
		)
	);
	return $arrdata;
}
function kemampuanbicara($id)
{
	$arrdata = array(
		array(
			"id" => "1",
			"text" => "Aktif",
		),
		array(
			"id" => "2",
			"text" => "Pasif",
		)
	);
	return $arrdata;
}

?>