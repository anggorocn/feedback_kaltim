<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'kloader.php';
include_once("libraries/nusoap-0.9.5/lib/nusoap.php");
include_once("functions/date.func.php");

class kauth {
    
    function __construct() {
        $CI =& get_instance();
        $CI->load->driver('session');
    }

    public function cekuseradminlogin($username,$credential) {
        $CI =& get_instance();
        $configdata= $CI->config;
        $configvlxsessfolder= $configdata->config["vlxsessfolder"];

        $CI->load->model("base-data/Users");
        $users = new Users();
        $users->selectbyloginadmin($username, md5($credential));
        //echo $users->query;exit;
        
        if($users->firstRow())
        { 
            $CI->session->set_userdata("adminuserid".$configvlxsessfolder, $users->getField("USER_LOGIN_ID"));
            $CI->session->set_userdata("adminuserloginnama".$configvlxsessfolder, $users->getField("LOGIN_USER"));
            $CI->session->set_userdata("adminusernama".$configvlxsessfolder, $users->getField("LOGIN_USER_NAMA"));
			$CI->session->set_userdata("userlevel".$configvlxsessfolder, $users->getField("USER_GROUP_ID"));
            $CI->session->set_userdata("userSatkerId".$configvlxsessfolder, $users->getField("SATKER_ID"));

            if($users->getField("USER_LOGIN_ID") == "")
                return "Username dan password salah.";
            else
                return "1";
        }
        else
            return "Username dan password salah.";
    }

    public function unsetcekuseradminlogin() {
        $CI =& get_instance();
        $configdata= $CI->config;
        $configvlxsessfolder= $configdata->config["vlxsessfolder"];

        $CI->session->unset_userdata("adminuserid".$configvlxsessfolder);
        $CI->session->unset_userdata("adminuserloginnama".$configvlxsessfolder);
        $CI->session->unset_userdata("adminusernama".$configvlxsessfolder);
        $CI->session->unset_userdata("adminuseraksesappmenu".$configvlxsessfolder);
        $CI->session->unset_userdata("userpegawaimode".$configvlxsessfolder);
		$CI->session->unset_userdata("userlevel".$configvlxsessfolder);
        $CI->session->unset_userdata("userSatkerId".$configvlxsessfolder);
    }

    // public function cekuserloginpersonal($username,$credential) {

    //     $CI =& get_instance();
    //     $configdata= $CI->config;
    //     $configvlxsessfolder= $configdata->config["vlxsessfolder"];

    //     $CI->load->model("base-data/Users");
    //     $users = new Users();
    //     $users->selectbyloginpegawai($username, md5($credential));
    //     // echo $users->query; exit;
        
    //     if($users->firstRow())
    //     { 
    //          $CI->session->set_userdata("userpegawaiId".$configvlxsessfolder, $users->getField("PEGAWAI_ID"));
    //          $CI->session->set_userdata("userpegawaiNama".$configvlxsessfolder, str_replace("'", "", $users->getField("NAMA")));
			
	// 		if($users->getField("PEGAWAI_ID") == "")
    //         {
    //              return "0";
    //         }
    //         else 
    //         {
    //             return "1";  
    //         }
    //     }
    //     else
    //         return "Username atau password salah.";
    // }

    public function cekuserloginpersonal($loginadminid) {

        $CI =& get_instance();
        $configdata= $CI->config;
        $configvlxsessfolder= $configdata->config["vlxsessfolder"];

        $CI->load->model("base-data/Users");
        $users = new Users();
        $users->selectbyloginadminnew($loginadminid);
        // echo $users->query; exit;
        
        if($users->firstRow())
        { 
            $CI->session->set_userdata("adminuserid".$configvlxsessfolder, $users->getField("user_app_id"));
            $CI->session->set_userdata("adminuserloginnama".$configvlxsessfolder, $users->getField("NAMA"));
            $CI->session->set_userdata("adminusernama".$configvlxsessfolder, $users->getField("NAMA"));
            // $CI->session->set_userdata("userlevel".$configvlxsessfolder, $users->getField("USER_GROUP_ID"));
            // $CI->session->set_userdata("userSatkerId".$configvlxsessfolder, $users->getField("SATKER_ID"));

            return "1";  
        }
        else
            return "Username atau password salah.";
    }

    public function unsetcekuserloginpersonal() {
        $CI =& get_instance();
        $configdata= $CI->config;
        $configvlxsessfolder= $configdata->config["vlxsessfolder"];

        $CI->session->unset_userdata("userpegawaiId".$configvlxsessfolder);
        $CI->session->unset_userdata("userpegawaiNama".$configvlxsessfolder);
    }

    public function setadminpegawai($id) {
        $CI =& get_instance();
        $configdata= $CI->config;
        $configvlxsessfolder= $configdata->config["vlxsessfolder"];

        // $CI->session->set_userdata("userpegawaiId".$configvlxsessfolder, $id);
        $CI->session->set_userdata("userpegawaimode".$configvlxsessfolder, $id);

    }

    public function unsetadminpegawai() {
        $CI =& get_instance();
        $configdata= $CI->config;
        $configvlxsessfolder= $configdata->config["vlxsessfolder"];

        // $CI->session->unset_userdata("userpegawaiId".$configvlxsessfolder);
        $CI->session->unset_userdata("userpegawaimode".$configvlxsessfolder);
    }

}
?>