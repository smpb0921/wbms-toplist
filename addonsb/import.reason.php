<?php 
$srv = $_SERVER['HTTP_HOST']; 
include_once('conn.php'); 
$row =0;
if (($file = fopen("../import/files_for_upload/uplreason_reason.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($file, 100000, ",")) !== FALSE) { 
		if($row>0){  
			$dataz=mysql_escape_string($data[1]);
			$conn->query("insert into reason(code,description,type) values('$dataz','$dataz',UPPER('$data[0]'));");
			echo "$data[1] has been added!<br/>";	
		}
        $row++; 
    }
    fclose($file);
	unlink("../import/files_for_upload/uplreason_reason.csv");
	$conn->close();
}

