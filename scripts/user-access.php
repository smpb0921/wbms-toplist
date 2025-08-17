<?php
	include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include('../config/functions.php');

    if(isset($_POST['DkkIroh3Lro93hdlk'])){
    	if($_POST['DkkIroh3Lro93hdlk']=='sdfei3opod30napri'){
            $userid = $_SESSION['RPBLCuserID'];
    		$access = array();
    		$query = "select * from user_rights where user_id='$userid'";
    		$rs = query($query);
    		while($obj=fetch($rs)){
    			array_push($access, $obj->menu_id);
    		}
    		echo implode(',', $access);
    	}
    }


