<? 
/* *******************************************************************************************************
MODUL NAME 			: IMASYS
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel website.agama.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class Menu extends Entity{ 

  	var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function Menu()
	{
      $this->Entity(); 
    }
	
	function selectByParamsMenu($group_id, $akses_id, $table_prefix, $statement = "", $order="ORDER BY URUT, A.MENU_ID ASC")
	{
		$str = "
		SELECT A.MENU_ID, A.MENU_PARENT_ID, NAMA, LINK_FILE, LINK_DETIL_FILE, AKSES, ICON
		, COALESCE(MN.JUMLAH,0) JUMLAH_CHILD
		,
		(SELECT COUNT(".$table_prefix."_ID) FROM ".$table_prefix."_MENU X WHERE SUBSTR(X.MENU_ID, 1, 2) = A.MENU_ID AND ".$table_prefix."_ID = ".$akses_id.") JUMLAH_MENU,
		(SELECT COUNT(".$table_prefix."_ID) FROM ".$table_prefix."_MENU X WHERE SUBSTR(X.MENU_ID, 1, 2) = A.MENU_ID AND ".$table_prefix."_ID = ".$akses_id." AND AKSES = 'D') JUMLAH_DISABLE
		FROM MENU  A
		LEFT JOIN ".$table_prefix."_MENU B ON A.MENU_ID = B.MENU_ID AND ".$table_prefix."_ID = ".$akses_id."
		LEFT JOIN
		(
			SELECT CASE WHEN COUNT(1) > 0 THEN 1 ELSE 0 END JUMLAH, MENU_PARENT_ID PARENT_ID FROM MENU A GROUP BY MENU_PARENT_ID ORDER BY MENU_PARENT_ID
		) MN ON A.MENU_ID = MN.PARENT_ID
		WHERE MENU_GROUP_ID = ".$group_id."
	    "; 
		
		$str .= $statement."  ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,-1,-1); 
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $akses_adm_intranet_id="", $table="", $order="ORDER BY A.MENU_ID ASC")
	{
		//, (SELECT CASE WHEN COUNT(1) > 0 THEN 1 ELSE 0 END FROM MENU X WHERE 1=1 AND CASE WHEN LENGTH(A.MENU_ID) = 6 THEN SUBSTR(A.MENU_ID, 1, 6) WHEN LENGTH(A.MENU_ID) = 4 THEN SUBSTR(A.MENU_ID, 1, 4) ELSE SUBSTR(A.MENU_ID, 1, 2) END = X.MENU_PARENT_ID) JUMLAH_CHILD
		$str = "
                SELECT 
				A.MENU_ID, MENU_PARENT_ID, MENU_GROUP_ID, A.NAMA MENU, KETERANGAN, LINK_FILE, COALESCE(B.AKSES, 'A') AKSES, C.NAMA 
				, COALESCE(MN.JUMLAH,0) JUMLAH_CHILD, LENGTH(A.MENU_ID) PANJANG_MENU
				FROM MENU A 
                LEFT JOIN ".$table."_MENU B ON A.MENU_ID = B.MENU_ID AND B.".$table."_ID = '".$akses_adm_intranet_id."' 
                LEFT JOIN ".$table." C ON C.".$table."_ID = '".$akses_adm_intranet_id."'
				LEFT JOIN
				(
					SELECT CASE WHEN COUNT(1) > 0 THEN 1 ELSE 0 END JUMLAH, MENU_PARENT_ID PARENT_ID
					FROM MENU A
					GROUP BY MENU_PARENT_ID
					ORDER BY MENU_PARENT_ID
				) MN ON A.MENU_ID = MN.PARENT_ID
                WHERE A.MENU_ID IS NOT NULL 
				"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= $statement." ".$order;
		
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
  } 
?>