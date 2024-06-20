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
$reqJadwalTesId= $this->input->get("jadwaltesid");
$set= new InfoData();
$set->selectbyparamspegawai(array("A.PEGAWAI_ID"=>$reqPegawaiId),-1,-1);
$set->firstRow();
// echo $set->query; exit;
$reqNama= $set->getField('NAMA');
$reqSatker= $set->getField('NMSATKER');
$reqEmail= $set->getField('EMAIL');
$reqLogo= substr($reqNama, 0, 1);

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

            <div class="d-flex flex-row flex-column-fluid page">

                <div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">

                    <div class="brand flex-column-auto" id="kt_brand">

                        <a href="app" class="brand-logo">
                            <!-- <img alt="Logo" src="assets/media/logos/logo-aplikasi.png" /> -->
                        </a>

                        <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
                            <span class="svg-icon svg-icon svg-icon-xl">

                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)" />
                                        <path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)" />
                                    </g>
                                </svg>

                            </span>
                        </button>
                    </div>


                    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
                        <!--begin::Menu Container-->
                        <div id="kt_aside_menu" class="aside-menu my-4" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500" style="border: 0px solid red; margin-top: 0px !important; margin-bottom: 0px !important;">
                            <!--begin::Menu Nav-->
                            <ul class="menu-nav">
                                <li class="menu-item menu-item-submenu menu-item-here" aria-haspopup="true" data-menu-toggle="hover">
                                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                                        <span class="menu-text">Feedback Tertulis</span>
                                        <i class="menu-arrow"></i>
                                    </a>
                                    <div class="menu-submenu">
                                        <i class="menu-arrow"></i>
                                        <ul class="menu-subnav">
                                            <li class="menu-item menu-item-parent" aria-haspopup="true">
                                                <span class="menu-link">
                                                    <span class="menu-text">Feedback Tertulis</span>
                                                </span>
                                                <li class="menu-item <?=$menuopen?>" aria-haspopup="true">
                                                    <a href="popup/index/pegawai_formula_data?reqPegawaiHard=<?=$reqPegawaiHard?>&jadwaltesid=<?=$reqJadwalTesId?>" class="menu-link">
                                                        <i class="menu-bullet menu-bullet-line">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Feedback Tertulis</span>
                                                    </a>
                                                </li>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="menu-item menu-item-submenu menu-item-here" aria-haspopup="true" data-menu-toggle="hover">
                                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                                        <span class="menu-text">Feedback Lisan</span>
                                        <i class="menu-arrow"></i>
                                    </a>
                                    <div class="menu-submenu">
                                        <i class="menu-arrow"></i>
                                        <ul class="menu-subnav">
                                            <li class="menu-item menu-item-parent" aria-haspopup="true">
                                                <span class="menu-link">
                                                    <span class="menu-text">Rencana Pengembangan diri</span>
                                                </span>
                                                <li class="menu-item <?=$menuopen?>" aria-haspopup="true">
                                                    <a href="popup/index/rencana_pengembangan_diri?reqPegawaiHard=<?=$reqPegawaiHard?>&jadwaltesid=<?=$reqJadwalTesId?>" class="menu-link">
                                                        <i class="menu-bullet menu-bullet-line">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Rencana Pengembangan diri</span>
                                                    </a>
                                                </li>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                            <!--end::Menu Nav-->
                        </div>
                        <!--end::Menu Container-->
                    </div>
                    <!--end::Aside Menu-->
                </div>
                <!--end::Aside-->
                <!--begin::Wrapper-->
                <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
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
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="content d-flex flex-column flex-column-fluid" id="kt_content" style="background: url(images/bg-login.jpg); background-size: 100% 100%">
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
            <!--end::Page-->
        </div>
        <!--end::Main-->


        <!-- end::User Panel-->

        <?
        if(!empty($reqPegawaiId))
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
                        document.location.href = "admin/index";
                    },
                    error: function(xhr, status, error) {
                        // var err = JSON.parse(xhr.responseText);
                        // Swal.fire("Error", err.message, "error");
                    },
                    complete: function () {
                        KTUtil.btnRelease(formSubmitButton);
                    }
                });
            }
        </script>
        <?
        }
        ?>

    </body>
</html>