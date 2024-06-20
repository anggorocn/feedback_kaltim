<?php
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$index_set=0;
$arrMenu= [];
$arrMenu= harcodemenu();
// $set = new Menu();
// $set->selectByParamsMenu(2, $this->AKSES_APP_ABSENSI_ID, "AKSES_APP_ABSENSI", " AND A.STATUS IS NULL", "ORDER BY A.URUT, A.MENU_ID");
// // echo $set->query;exit;
// while($set->nextRow())
// {
//     $arrMenu[$index_set]["MENU_ID"]= $set->getField("MENU_ID");
//     $arrMenu[$index_set]["MENU_PARENT_ID"]= $set->getField("MENU_PARENT_ID");
//     $arrMenu[$index_set]["NAMA"]= $set->getField("NAMA");
//     $arrMenu[$index_set]["LINK_FILE"]= $set->getField("LINK_FILE");
//     $arrMenu[$index_set]["AKSES"]= $set->getField("AKSES");
//     $arrMenu[$index_set]["JUMLAH_CHILD"]= $set->getField("JUMLAH_CHILD");
//     $arrMenu[$index_set]["JUMLAH_MENU"]= $set->getField("JUMLAH_MENU");
//     $arrMenu[$index_set]["JUMLAH_DISABLE"]= $set->getField("JUMLAH_DISABLE");
//     $index_set++;
// }
// $tempMenu= $index_set;
// unset($set);
// print_r($arrMenu);exit;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?=base_url()?>">
        <meta charset="utf-8" />
        <title>Profile 3 | Keenthemes</title>
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

        <link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
        <link href="assets/css/new-style.css" rel="stylesheet" type="text/css" />

        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>

        <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <script src="assets/js/valsix-serverside.js"></script>
        <script src="assets/plugins/custom/jstree/jstree.bundle.js"></script>
        <!-- <script src="assets/js/pages/crud/datatables/data-sources/ajax-server-side.js"></script> -->
    </head>

    <body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">

        <div id="kt_header_mobile" class="header-mobile align-items-center header-mobile-fixed">

            <a href="app">
                <img alt="Logo" src="assets/media/logos/logo-aplikasi.png" />
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
                            <img alt="Logo" src="assets/media/logos/logo-aplikasi.png" />
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
                        <div id="kt_aside_menu" class="aside-menu my-4" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500">
                            <!--begin::Menu Nav-->
                            <ul class="menu-nav">

                                <?
                                function getMenuByParent($id_induk, $arrMenu, $tempParentNama)
                                {
                                    $arrayKey= [];
                                    $arrayKey= in_array_column($id_induk, "MENU_PARENT_ID", $arrMenu);
                                    if(!empty($arrayKey))
                                    {
                                        for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
                                        {
                                            $index_row= $arrayKey[$index_detil];
                                            $tempMenuId= $arrMenu[$index_row]["MENU_ID"];
                                            $arrMenu[$index_row]["MENU_PARENT_ID"];
                                            $tempNama= $arrMenu[$index_row]["NAMA"];
                                            $tempLinkFile= $arrMenu[$index_row]["LINK_FILE"];
                                            $tempAkses= $arrMenu[$index_row]["AKSES"];
                                            $tempJumlahChild= $arrMenu[$index_row]["JUMLAH_CHILD"];
                                            $tempJumlahMenu= $arrMenu[$index_row]["JUMLAH_MENU"];
                                            $tempJumlahDisable= $arrMenu[$index_row]["JUMLAH_DISABLE"];
                                            $tempInfoNama= $tempParentNama.";".$tempNama;
                                ?>
                                <li class="menu-item" aria-haspopup="true">
                                    <a href="app/index/<?=$tempLinkFile?>" class="menu-link">
                                        <i class="menu-bullet menu-bullet-line">
                                            <span></span>
                                        </i>
                                        <span class="menu-text"><?=$tempNama?></span>
                                    </a>
                                </li>
                                <?
                                        }
                                    }
                                }
                                ?>

                                <?
                                $arrayKey= [];
                                $arrayKey= in_array_column("0", "MENU_PARENT_ID", $arrMenu);
                                // print_r($arrayKey);exit;
                                if(!empty($arrayKey))
                                {
                                    for($index_detil=0; $index_detil < count($arrayKey); $index_detil++)
                                    {
                                        $index_row= $arrayKey[$index_detil];
                                        $tempMenuId= $arrMenu[$index_row]["MENU_ID"];
                                        $arrMenu[$index_row]["MENU_PARENT_ID"];
                                        $tempNama= $arrMenu[$index_row]["NAMA"];
                                        $tempLinkFile= $arrMenu[$index_row]["LINK_FILE"];
                                        $tempAkses= $arrMenu[$index_row]["AKSES"];
                                        $tempJumlahChild= $arrMenu[$index_row]["JUMLAH_CHILD"];
                                        $tempJumlahMenu= $arrMenu[$index_row]["JUMLAH_MENU"];
                                        $tempJumlahDisable= $arrMenu[$index_row]["JUMLAH_DISABLE"];
                                ?>
                                <li class="menu-item menu-item-submenu menu-item-open menu-item-here" aria-haspopup="true" data-menu-toggle="hover">
                                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                                        <span class="menu-text"><?=$tempNama?></span>
                                        <i class="menu-arrow"></i>
                                    </a>
                                    <div class="menu-submenu">
                                        <i class="menu-arrow"></i>
                                        <ul class="menu-subnav">
                                            <li class="menu-item menu-item-parent" aria-haspopup="true">
                                                <span class="menu-link">
                                                    <span class="menu-text"><?=$tempNama?></span>
                                                </span>
                                            </li>

                                            <?
                                            if($tempJumlahChild > 0)
                                            {
                                                getMenuByParent($tempMenuId, $arrMenu, $tempNama);
                                            ?>
                                            <?
                                            }
                                            ?>

                                        </ul>
                                    </div>
                                </li>
                                <?
                                    }
                                }
                                ?>

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
                                        <span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Hi,</span>
                                        <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3">Sean</span>
                                        <span class="symbol symbol-lg-35 symbol-25 symbol-light-success">
                                            <span class="symbol-label font-size-h5 font-weight-bold">S</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                        <?=$content?>
                    </div>
                    <!--end::Content-->
                    <!--begin::Footer-->
                    <div class="footer bg-white py-4 d-flex flex-lg-column" id="kt_footer">
                        <!--begin::Container-->
                        <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
                            <!--begin::Copyright-->
                            <div class="text-dark order-2 order-md-1">
                                <span class="text-muted font-weight-bold mr-2">2021©</span>
                                <a class="text-dark-75 text-hover-primary">Valsix</a>
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
            <div class="offcanvas-content pr-5 mr-n5">
                <!--begin::Header-->
                <div class="d-flex align-items-center mt-5">
                    <div class="symbol symbol-100 mr-5">
                        <div class="symbol-label" style="background-image:url('assets/media/users/blank.png')"></div>
                        <i class="symbol-badge bg-success"></i>
                    </div>
                    <div class="d-flex flex-column">
                        <a href="#" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">James Jones</a>
                        <div class="text-muted mt-1">Application Developer</div>
                        <div class="navi mt-2">
                            <a href="#" class="navi-item">
                                <span class="navi-link p-0 pb-2">
                                    <span class="navi-icon mr-1">
                                        <span class="svg-icon svg-icon-lg svg-icon-primary">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Mail-notification.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <path d="M21,12.0829584 C20.6747915,12.0283988 20.3407122,12 20,12 C16.6862915,12 14,14.6862915 14,18 C14,18.3407122 14.0283988,18.6747915 14.0829584,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,12.0829584 Z M18.1444251,7.83964668 L12,11.1481833 L5.85557487,7.83964668 C5.4908718,7.6432681 5.03602525,7.77972206 4.83964668,8.14442513 C4.6432681,8.5091282 4.77972206,8.96397475 5.14442513,9.16035332 L11.6444251,12.6603533 C11.8664074,12.7798822 12.1335926,12.7798822 12.3555749,12.6603533 L18.8555749,9.16035332 C19.2202779,8.96397475 19.3567319,8.5091282 19.1603533,8.14442513 C18.9639747,7.77972206 18.5091282,7.6432681 18.1444251,7.83964668 Z" fill="#000000" />
                                                    <circle fill="#000000" opacity="0.3" cx="19.5" cy="17.5" r="2.5" />
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </span>
                                    <span class="navi-text text-muted text-hover-primary">jm@softplus.com</span>
                                </span>
                            </a>
                            <a href="app/logout" class="btn btn-sm btn-light-primary font-weight-bolder py-2 px-5">Sign Out</a>
                        </div>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Separator-->
                <div class="separator separator-dashed mt-8 mb-5"></div>
                <!--end::Separator-->
                <!--begin::Nav-->
                <div class="navi navi-spacer-x-0 p-0">
                    <!--begin::Item-->
                    <a class="navi-item">
                        <div class="navi-link">
                            <div class="navi-text">
                                <div class="font-weight-bold">My Profile</div>
                                <div class="text-muted">Account settings and more
                            </div>
                        </div>
                    </a>
                    <!--end:Item-->
                </div>
                <!--end::Nav-->
            </div>
            <!--end::Content-->
        </div>
        <!-- end::User Panel-->

    </body>
</html>