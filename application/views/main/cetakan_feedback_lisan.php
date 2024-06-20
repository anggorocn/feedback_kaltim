<base href="<?=base_url()?>">

<?
$this->load->model("base-data/InfoData");
$this->load->model("base-data/Feedback");

$reqPegawaiHard= $this->input->get("reqPegawaiHard");
$reqJadwalTesId= $this->input->get("jadwaltesid");

//==============================================================
$html= file_get_contents(base_url()."app/loadUrl/main/cetakan_feedback_lisan_isi?formulaid=1&reqPegawaiHard=".$reqPegawaiHard."&jadwaltesid=".$reqJadwalTesId."&adminuserid=".$this->adminuserid);	
//==============================================================
//==============================================================
//==============================================================
include("lib/MPDF60/mpdf.php");

//$mpdf=new mPDF('c','A4'); 
$mpdf = new mPDF('',    // mode - default ''
 '',    // format - A4, for example, default ''
 0,     // font size - default 0
 '',    // default font family
 15,    // margin_left
 15,    // margin right
 16,     // margin top
 16,    // margin bottom
 9,     // margin header
 9,     // margin footer
 'L');  // L - landscape, P - portrait

$mpdf->SetDisplayMode('fullpage');

$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

// LOAD a stylesheet
// $stylesheet = file_get_contents('../WEB/css/cetak_assesment.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->AddPage();
$mpdf->WriteHTML($html,2);

$mpdf->Output('cetak_assesment.pdf','I');
exit;
//==============================================================
//==============================================================
//==============================================================
?>