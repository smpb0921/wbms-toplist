<?php  
include("../config/db-function.php"); 
include("../config/connection.php"); 
$salt ='jhHKHUOIEOU898978d7uoojoIJknLowiue!897&uhshnkwrweOIUYUT!'; 
if(isset($_POST['submitbtn'])){	
	$token = $_POST['sometext']; 
	if($token=="jhHKHUOIEOU898978d7uoojoIJknLowiue!897&uhshnkwrweOIUYUT!"){
		$uname = strtolower(htmlentities($_POST['username'],ENT_QUOTES,'UTF-8',false));
		$pword = md5(htmlentities($_POST['password'],ENT_QUOTES,'UTF-8',false)); 
		$_SESSION['username'] = $uname; 
		$query = "select * from user where username='$uname' and password='$pword'";
		$resultset = mysql_query($query);
		$num = mysql_num_rows($resultset); 
		$query = "select * from user where username='$uname' and password='$pword' and active_flag='1'";
		$resultset = mysql_query($query);
		$num2 = mysql_num_rows($resultset); 
		while($obj = mysql_fetch_object($resultset)){
			$fname = $obj->first_name;
			$userid = $obj->id;
		}
		if($num===1&&$num2===1){
			echo $userid;
		} 
		else if($num!=1){
			echo -1;
		}
		else if($num2!=1){
			echo -2;
		} 
	} 
} 