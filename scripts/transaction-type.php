<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/db-function.php");
    include("../classes/transaction-type.class.php");
    include("../classes/system-log.class.php");

if(isset($_POST['DmalePNElaporjd'])){
	$id = escapeString($_POST['id']);
	$code=escapeString($_POST['code']);
	$desc=escapeString($_POST['desc']);
	$prefix=escapeString($_POST['prefix']);
	$numseries=escapeString($_POST['numseries']);
	
	$loginuser =$userid;
	$date = date('Y-m-d H:i:s');
	$systemlog = new System_log();

	$query = "select * from transaction_type where code='$code' and id!='$id'";
	$rs = query($query);

	if(getNumRows($rs)==0){
		$transactiontype = new Transaction_type();
		$systemlog->logEditedInfo($transactiontype,$id,array('',$code,$desc,$prefix,$numseries,$loginuser,$date),'TRANSACTION TYPE','Edited Transaction Type Info',$loginuser,$date);/// log should be before update is made
		$transactiontype->update($id,array($code,$desc,$prefix,$numseries,$loginuser,$date));
		echo "success";
	}
	else{
		echo "codeexist";
	}
}



?>