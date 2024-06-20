<?
$this->load->library("crfs_protect"); $csrf = new crfs_protect('_crfs_login');
// $username= "196109141986031013";
$username= "";

$sessinfopesan= $this->sessinfopesan;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?=base_url()?>">
        <meta charset="utf-8" />
        <title>Login</title>
        <meta name="description" content="Login page example" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link rel="canonical" href="https://keenthemes.com/metronic" />
        <link href="assets/css/pages/login/classic/login-4.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/themes/layout/header/base/light.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/themes/layout/header/menu/light.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/themes/layout/brand/dark.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/themes/layout/aside/dark.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="assets/media/logos/favicon.png" />
        <link href="assets/css/new-style.css" rel="stylesheet" type="text/css" />
        
        <style>
            .area-kiri-login {
                height: 100vh; 
                background-image: url('assets/media/bg/bg-login2.png'); 
                background-size: auto 100%; 
                background-position: right bottom;
                display: flex; justify-content: center; align-items: center; 
            }
            .nama-aplikasi {
/*                background: #FFFFFF;*/
                color:  #fff;
                padding: 20px;
                text-align: center;
/*                border: 1px solid red;*/
            }
            .nama-aplikasi h4 {
                font-size: 16px !important;
                text-transform: uppercase;
                color:  #333333;
            }
            .area-kanan-login {
                height: 100vh; 
                display: flex; justify-content: center; align-items: center; 
                background: #FFFFFF;
            }
            @media screen and (max-width:767px) {
                .d-flex.flex-center.flex-row-fluid.bgi-size-cover.bgi-position-top.bgi-no-repeat {
/*                    display: inherit !important;*/
                }
                .area-kiri-login {
                    position: absolute;
                    top: 0;
                    height: 30vh;
                    width: 100%;
                    display: inline-block;
/*                    border: 1px solid red;*/
                    background-size: 100% 100%; 
                }
                .area-kanan-login {
                    position: absolute;
                    bottom: 0;
                    height: 70vh;
                    width: 100%;
                    display: inline-block;
/*                    border: 1px solid cyan;*/
                }
            }
        </style>
    </head>

    <body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
        <div class="d-flex flex-column flex-root">
            <div class="login login-4 login-signin-on d-flex flex-row-fluid" id="kt_login">
<!--                <div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat" style="background-image: url('assets/media/bg/bg-3.jpg');">-->
                <div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat">
                    <div class="col-md-7 area-kiri-login">
                        
                    </div>
                    <div class="col-md-5 area-kanan-login">
                        <div class="login-form text-center p-7 position-relative overflow-hidden">
                            
                            <div class="d-flex flex-center mb-5">
                                <a href="#">
                                    <img src="assets/media/logos/logo-aplikasi.png" class="max-h-75px" alt="" />
                                </a>
                            </div>
                            <div class="nama-aplikasi">
                                <h4><strong>Login Aplikasi SiMEGILAN</strong></h4>
                            </div>
                            <div class="login-signin">
<!--
                                <div class="mb-10">
                                    <h3>Lamongan</h3>
                                    <div class="text-muted font-weight-bold">Aplikasi Personal:</div>
                                </div>
-->
                                <form class="form" id="kt_login_signin_form" action="login/action" method="post">
                                    <div class="form-group mb-5">
                                        <input class="form-control h-auto form-control-solid py-4 px-8" type="text" placeholder="NIP Baru" name="reqUser" autocomplete="off" value="<?=$username?>" />
                                    </div>
                                    <div class="form-group mb-5">
                                        <input class="form-control h-auto form-control-solid py-4 px-8" type="password" placeholder="Password" name="reqPasswd" value="<?=$username?>" />
                                    </div>

                                    <?
                                    if(!empty($sessinfopesan))
                                    {
                                    ?>
                                    <div class="form-group mb-5">
                                        <label class="text-danger"><?=$sessinfopesan?></label>
                                    </div>
                                    <?
                                    }
                                    ?>
                                    <div class="form-group mb-5">
                                        <?=$csrf->echoInputField();?>
                                    </div>
                                    <button id="kt_login_signin_submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4">Login</button>

                                    <input type="hidden" name="reqMode" value="submitLogin"/>
                                    <span><?=$pesan?></span>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
		<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/60d15a0265b7290ac6372f37/1f8osf6gm';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
    </body>
</html>