<?php
session_start();
include("config/db-function.php");
if(isset($_SESSION['jhsgfhjsdgfhjnseuiyiu+-kjerowjn.oruewilsdkfjnojwoieriojsld'])&&isset($_SESSION['zfie@#MKLDFi934OPedieo'])){
		header("location:application/main.php");
}

//unset($_SESSION['username']);

$errormsg = '';

if(isset($_GET['signout'])&&isset($_GET['JEmnsdke0Iejdn6Dowdhueoksd'])){
	if($_GET['signout']=='1'&&$_GET['JEmnsdke0Iejdn6Dowdhueoksd']=='Oekfndfoeih'){
		$errormsg = "<div class='index-errorbox'>You have just signed out</div>";
	}
}
else if(isset($_GET['0jksleo03lDnlei'])&&$_GET['0jksleo03lDnlei']=='1'&&isset($_GET['sessionExpired'])&&$_GET['sessionExpired']=='true'){
	$errormsg = "<div class='index-errorbox'>Session has expired</div>";
}


$salt ='jhHKHUOIEOU898978d7uoojoIJknLowiue!897&uhshnkwrweOIUYUT!';



if(isset($_POST['submitbtn'])){	
	$token = $_POST['sometext'];

	if($token=="jhHKHUOIEOU898978d7uoojoIJknLowiue!897&uhshnkwrweOIUYUT!"){
		$uname = strtolower(htmlentities($_POST['username'],ENT_QUOTES,'UTF-8',false));
		$pword = md5(htmlentities($_POST['password'],ENT_QUOTES,'UTF-8',false));

		$_SESSION['username'] = $uname;

		$query = "select * from user where username='$uname' and password='$pword'";
		$resultset = query($query);
		$num = getNumRows($resultset);

		$query = "select * from user where username='$uname' and password='$pword' and active_flag='1'";
		$resultset = query($query);
		$num2 = getNumRows($resultset);


		while($obj = fetch($resultset)){
			$fname = $obj->first_name;
			$userid = $obj->id;
		}

		if($num===1&&$num2===1){
			unset($_SESSION['username']);
			$_SESSION['jhsgfhjsdgfhjnseuiyiu+-kjerowjn.oruewilsdkfjnojwoieriojsld'] = $uname;
			$_SESSION['zfie@#MKLDFi934OPedieo'] = $pword;
			$_SESSION['RPBLCuserID']=$userid;
			$_SESSION['fnameWMS']=$fname;
			header("location:application/main.php");
		}
		else if ($uname===""||$pword==="") {
			$errormsg = "<div class='index-errorbox'>Please Enter Username and Password</div>";
		}
		else if($num!=1){
			$errormsg =  "<div class='index-errorbox'>Username and Password does not match</div>";
		}
		else if($num2!=1){
			$errormsg = "<div class='index-errorbox'>Your account had been deactivated. <br>Please contact system administrator.</div>";
		}
		

	}
	else{
		$errormsg = "<div class='index-errorbox'>Sorry, but you cannot login from another domain</div>";
	}
}


?>