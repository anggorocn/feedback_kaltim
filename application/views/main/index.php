<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model("base-data/InfoData");
$this->load->library('globalmenu');

$userpegawaimode= $this->userpegawaimode;
$adminuserid= $this->adminuserid;

// if(!empty($userpegawaimode))
//     $reqPegawaiId= $this->userpegawaimode;
// else
//     $reqPegawaiId= $this->pegawaiId;

$reqPegawaiId= $this->userpegawaimode;
// echo  $this->input->get("reqPegawaiHard");exit;

$formulaid= $this->input->get("formulaid");
$reqPegawaiHard= $this->input->get("reqPegawaiHard");
$rencanasuksesiid= $this->input->get("rencanasuksesiid");
$set= new Feedback();
$set->selectByParamsIdentitasAsesor(array("USER_APP_ID"=>$adminuserid),-1,-1);
$set->firstRow();
$reqNama= $set->getField('NAMA');
$reqSatker= $set->getField('NMSATKER');
$reqEmail= $set->getField('EMAIL');
$reqLogo= substr($reqNama, 0, 1);
$reqPegawaiAdminId= $set->getField('PEGAWAI_ID');

// untuk kondisi file
$vfpeg= new globalmenu();

$index_set=0;
$arrMenu= [];
$arrparam= ["mode"=>"personal", "formulaid"=>$formulaid, "rencanasuksesiid"=>$rencanasuksesiid];
// $arrMenu= harcodemenu($userstatuspegId);
$arrMenu= $vfpeg->harcodemenu($arrparam);
// print_r($arrMenu);exit;

$arrparam= ["pg"=>$pg, "arrMenu"=>$arrMenu];
$arrcarimenuparent= $vfpeg->cariparentmenu($arrparam);
// echo $arrcarimenuparent;exit;


$statement= " AND TO_CHAR(TANGGAL_TES, 'DD-MM-YYYY') = '07-02-2022'";
$setJadwal= new Feedback();
$setJadwal->selectByParamsJadwalTes(array(), -1,-1, $statement);
 // echo $setJadwal->query;exit;
$index_loop= 0;
while($setJadwal->nextRow())
{
  $reqJadwalTesId= $setJadwal->getField("JADWAL_TES_ID");
  
  $statement= " AND JA.JADWAL_TES_ID = ".$reqJadwalTesId." and ja.asesor_id= ".$reqPegawaiAdminId;
  $set= new Feedback();
  
  $set->selectByParamsDataAsesorPegawaiSuper($statement, $reqPegawaiAdminId);
 // echo $set->query;exit;
  
  while($set->nextRow())
  {

    $arrAsesor[$index_loop]["JADWAL_TES_ID"]= $set->getField("JADWAL_TES_ID");
    $arrAsesor[$index_loop]["PEGAWAI_ID"]= $set->getField("PEGAWAI_ID");
    $arrAsesor[$index_loop]["ACARA"]= $setJadwal->getField("ACARA");
    $arrAsesor[$index_loop]["NAMA_PEGAWAI"]= $set->getField("NAMA_PEGAWAI");
    $arrAsesor[$index_loop]["NIP_BARU"]= $set->getField("NIP_BARU");
    $arrAsesor[$index_loop]["NOMOR_URUT_GENERATE"]= $set->getField("NOMOR_URUT_GENERATE");
    $arrAsesor[$index_loop]["ESELON"]= $set->getField("last_eselon_id");
    $arrAsesor[$index_loop]["ASESOR_ID"]= $set->getField("asesor_id");

    
    $index_loop++;
    $jumlah_asesor= $index_loop;
  }
  
}

$set= new Feedback();
$set->selectByParamsAsesor(array(), -1,-1, " AND b.USER_APP_ID = ".$this->adminuserid);
// echo $set->query;exit;
$set->firstRow();
$tempAsesorNoSk= $set->getField("NO_SK");
$tempAsesorTipeNama= $set->getField("TIPE_NAMA");
// echo $tempAsesorNoSk;exit;

$url = 'https://api-simpeg.kaltimbkd.info/pns/semua-data-utama/'.$tempAsesorNoSk.'/?api_token=f5a46b71f13fe1fd00f8747806f3b8fa';
$data = json_decode(file_get_contents($url), true);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?=base_url()?>">
        <meta charset="utf-8" />
        <title>Aplikasi Manajemen Talenta</title>
        <meta name="description" content="User profile block example" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/custom/jstree/jstree.bundle.css" rel="stylesheet" type="text/css" />

        <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />

        <link href="assets/css/themes/layout/header/base/light.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/themes/layout/header/menu/light.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/themes/layout/brand/light.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/themes/layout/aside/light.css" rel="stylesheet" type="text/css" />

        <link rel="shortcut icon" href="assets/media/logos/favicon.png" />
        <link href="assets/css/new-style.css" rel="stylesheet" type="text/css" />

        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>

        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <script src="assets/js/valsix-serverside.js"></script>
        <script src="assets/plugins/custom/jstree/jstree.bundle.js"></script>

        <script src="assets/emodal/eModal.min.js"></script>
        <script>
            function openAdd(pageUrl) {
                eModal.iframe(pageUrl, 'Aplikasi')
            }
        </script>

        <!-- <script script type="text/javascript" src="js/highcharts.js"></script> -->
        <!-- <script src="lib/highcharts/jquery-3.1.1.min.js"></script> -->
        <script src="lib/highcharts/highcharts-spider.js"></script>
        <script src="lib/highcharts/highcharts-more.js"></script>
        <script src="lib/highcharts/exporting-spider.js"></script>
        <script src="lib/highcharts/export-data.js"></script>
        <script src="lib/highcharts/accessibility.js"></script>

        <style type="text/css">
            .brand {
                padding-left: 0px;
            }
            .card.card-custom {
              margin-top: 0%;
            }
            .aside-fixed .wrapper {
                padding-left: 0px;
            }
        </style>
        
    </head>

    <body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">

        <div id="kt_header_mobile" class="header-mobile align-items-center header-mobile-fixed">

            <a href="app">
                <!-- <img alt="Logo" src="assets/media/logos/logo-aplikasi.png" /> -->
            </a>

            <div class="d-flex align-items-center">
                <button class="btn p-0 burger-icon burger-icon-left" id="kt_aside_mobile_toggle">
                    <span></span>
                </button>

                <button class="btn btn-hover-text-primary p-0 ml-2" id="kt_header_mobile_topbar_toggle">
                    <span class="svg-icon svg-icon-xl">

                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24" />
                                <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                            </g>
                        </svg>

                    </span>
                </button>
            </div>

        </div>

        <div class="d-flex flex-column flex-root">
            <!--begin::Wrapper-->
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper" style="padding-top: 75px;">
                <div id="kt_header" class="header header-fixed">
                    <div class="container-fluid d-flex align-items-stretch justify-content-between">
                        <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
                            <div class="header-menu header-menu-mobile header-menu-layout-default"></div>
                        </div>
                        <div class="topbar">
                            <div class="topbar-item">
                                <div class="xxxtes btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
                                    <span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1"></span>
                                    <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3"><?=$reqNama?></span>
                                    <span class="symbol symbol-lg-35 symbol-25 symbol-light-success">
                                        <span class="symbol-label font-size-h5 font-weight-bold"><?=$reqLogo?></span>
                                        <span class="calendar-notif" style="background-color:red;width: 30px;height: 30px;margin-top: -45px;"><?=$jumlah_asesor?></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content d-flex flex-column flex-column-fluid" id="kt_content" style="background: url(images/bg-login.jpg); background-size: 100% 100%; padding: 0px;">
                    <?=$content?>
                </div>
                <!--end::Content-->
                <!--begin::Footer-->
                <div class="footer bg-white py-4 d-flex flex-lg-column" id="kt_footer">
                    <!--begin::Container-->
                    <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
                        <!--begin::Copyright-->
                        <div class="text-dark order-2 order-md-1">
                            <span class="text-muted font-weight-bold mr-2">© 2023</span>
                            <a class="text-dark-75 text-hover-primary">Pemerintah Provinsi Kalimantan Timur</a>
                        </div>
                        <!--end::Copyright-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Main-->

        <div id="kt_quick_user" class="offcanvas offcanvas-right p-10">
            <!--begin::Header-->
            <div class="offcanvas-header d-flex align-items-center justify-content-between pb-5">
                <h3 class="font-weight-bold m-0">User Profile
                <small class="text-muted font-size-sm ml-2"></small></h3>
                <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_user_close">
                    <i class="ki ki-close icon-xs text-muted"></i>
                </a>
            </div>
            <!--end::Header-->
            <!--begin::Content-->
            <div class="d-flex align-items-center mt-5" style="margin-bottom: 2rem !important;">
                <div class="symbol symbol-100 mr-5">
                    <!-- <div class="symbol-label" style="background-image:url('assets/media/users/blank.png')"></div> -->
                        <img id="reqImagePesertaIndex" alt="image" />

                    <!-- <i class="symbol-badge bg-success"></i> -->
                    <span class="calendar-notif" style="background-color:red;width: 30px;height: 30px;margin-top: -45px;margin-left: 60px;margin-top: -115px;text-align: center;"><?=$jumlah_asesor?></span>
                </div>
                <div class="d-flex flex-column">
                    <a href="#" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary"><? if($data['nama']==''){?>
                        <?=$tempAsesorNama?>
                        <? } 
                        else{ ?>
                        <? if($data['glr_depan']=='-'){ } else{ echo $data['glr_depan']; }?> <?=$data['nama']?> <? if($data['glr_belakang']=='-'){ } else{ echo $data['glr_belakang']; }?>
                        <?}?>
                    </a>
                    <div class="text-muted mt-1"><?=$tempAsesorNoSk?></div>
                    <div class="text-muted mt-1"><?=$tempAsesorTipeNama?></div>
                </div>
            </div>
            <div class="separator separator-dashed mt-8 mb-5" style="margin-top: 0rem !important;"></div>
            <div class="offcanvas-content pr-5 mr-n5">
                <div class="navi navi-spacer-x-0 p-0" style="overflow: scroll;height: 70%;">
                    <!--begin::Item-->
                    <?
                    for($checkbox_index=0;$checkbox_index<$jumlah_asesor;$checkbox_index++){
                        ?>
                            <a onclick="openpopup(<?=$arrAsesor[$checkbox_index]["PEGAWAI_ID"]?>,<?=$arrAsesor[$checkbox_index]["JADWAL_TES_ID"]?>)" style="cursor:pointer;">
                                <div class="d-flex align-items-center bg-light-success rounded p-5 gutter-b" style="margin-bottom: 5px;padding: 0.5rem !important;">
                                    <div class="d-flex flex-column flex-grow-1 mr-2">
                                        <span ><?=$arrAsesor[$checkbox_index]["NAMA_PEGAWAI"]?></span>
                                        <span style="font-size:10px"><?=$arrAsesor[$checkbox_index]["NIP_BARU"]?></span>
                                        <span class="text-muted font-size-sm" style="font-size:10px">Due in 2 Days</span>
                                    </div>
                                </div>
                            </a>
                        <?
                    }
                    ?>
                    <!--end:Item-->
                </div>
                <!--end::Nav-->
            </div>
            <!--end::Content-->
        </div>
        <!-- end::User Panel-->

        <?
        if(!empty($adminuserid))
        {
        ?> 
        <script type="text/javascript">
            function setkembali()
            {
                $.ajax({
                    url: "admin/unsetpegawai",
                    processData: false,
                    contentType: false,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        // console.log(response); return false;
                        document.location.href = "app/index";
                    },
                    error: function(xhr, status, error) {
                        // var err = JSON.parse(xhr.responseText);
                        // Swal.fire("Error", err.message, "error");
                    },
                    complete: function () {
                        // KTUtil.btnRelease(formSubmitButton);
                        document.location.href = "app/index";
                    }
                });
            }
        </script>
        <?
        }
        ?>

    </body>
</html>

<script type="text/javascript">
    $(function() {
           <?
           if($data['foto_original'] == "")
           {
            ?>
            // $("#reqImagePeserta").attr("src", "../WEB/images/no-picture.jpg");
            <?
        }
        else
        {
            ?>
            $("#reqImagePesertaIndex").attr("src", "<?=$data['foto_original']?>");
            <?
        }
        ?>
    });
    function openpopup(val,reqJadwalTesId ) {
        pageUrl= "popup/index/pegawai_formula_data?formulaid=1&reqPegawaiHard="+val+"&jadwaltesid="+reqJadwalTesId;
        window.open(pageUrl, '_blank').focus();
        // openAdd(pageUrl);
    }
</script>