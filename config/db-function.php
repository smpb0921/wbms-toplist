<?php
function fetch($result){
	$row = mysql_fetch_object($result);
	return $row;
}

function fetchArray($result){
	$row = mysql_fetch_assoc($result);
	return $row;
}

function error(){
	return mysql_error();
}
function errornum(){
	return mysql_errno();
}

function query($query){
	$result = mysql_query($query);
	if(!$result){
		echo error();
		echo $query."\n";
	}
	else{
		return $result;
	}
}



function getTotalRows($tablename){
        $query = "select * from $tablename";
        $rs = mysql_query($query);
        $count = mysql_num_rows($rs);
        return $count;
}

function getNumRows($result){
        $count = mysql_num_rows($result);
        return $count;
}

function escapeString($str){
	$str = utf8_decode($str);
	$str = mysql_real_escape_string($str);
	$str = stripcslashes($str);
	$str = str_replace("'", "\'", $str);
	

	//$str = htmlentities($str,ENT_QUOTES,'UTF-8');
	return $str;
}

function dateString($str){
	$str = date('Y-m-d', strtotime($str));
	return $str;
}

function datetimeString($str){
	$str = date('Y-m-d H:i:s', strtotime($str));
	return $str;
}


@define('ROOT', __DIR__); // for PHP >= 5.3
//@define('ROOT', realpath(dirname(__FILE__)));  // for PHP < 5.3


@session_start();
$userid = (isset($_SESSION['RPBLCuserID']))?$_SESSION['RPBLCuserID']:'';

@define('USERID', $userid);




?>