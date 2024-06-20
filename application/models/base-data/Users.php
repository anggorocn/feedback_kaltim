<? 
/* *******************************************************************************************************
MODUL NAME      : E LEARNING
FILE NAME       : 
AUTHOR        : 
VERSION       : 1.0
MODIFICATION DOC  :
DESCRIPTION     : 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel KontakPegawai.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class Users extends Entity{ 

    var $query;
    function Users()
    {
      $this->Entity(); 
    }

    function selectbyloginadmin($username,$passwd)
    {
      $statement= " AND A.LOGIN_USER = ".$this->db->escape($username)." AND A.LOGIN_PASS = ".$this->db->escape($passwd);

      $str = "
      SELECT
        A.*
      FROM simpeg.user_login A
      WHERE 1 = 1
      ".$statement;

      $this->query = $str;
      // echo $str;exit;
      return $this->select($str);
    }

    function selectbyloginpegawai($username,$passwd)
    {
      // $statement= " 1 = 2";
      // if(md5($id_usr) == $passwd)
      //   $statement= " AND A.NIP_BARU = '".$id_usr."'";

      // $statement= " AND A.NIP_BARU = ".$this->db->escape($username)." AND A.LOGIN_PASS = ".$this->db->escape($passwd);
      $statement= " AND B.user_login = ".$this->db->escape($username)." AND B.user_pass = ".$this->db->escape($passwd);

      $str = "
      SELECT
        A.PEGAWAI_ID, A.NIP_BARU, A.NAMA
      FROM simpeg.pegawai A
      LEFT JOIN CAT.USER_APP B ON A.PEGAWAI_ID=B.PEGAWAI_ID
      WHERE 1 = 1
      ".$statement;

      $this->query = $str;
      // echo $str;exit;
      return $this->select($str);
    }

    function selectbyloginadminnew($loginadminid)
    {
      // $statement= " 1 = 2";
      // if(md5($id_usr) == $passwd)
      //   $statement= " AND A.NIP_BARU = '".$id_usr."'";
      $statement= " AND user_app_id = ".$this->db->escape($loginadminid);

      $str = "
      SELECT
      *
      FROM user_app 
      WHERE 1 = 1
      ".$statement;

      $this->query = $str;
      // echo $str;exit;
      return $this->select($str);
    }

  } 
?>