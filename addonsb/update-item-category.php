<?php
include_once('conn.php');



$row =0;
if (($file = fopen("../import/files_for_upload/updatedivision.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($file, 100000, ",")) !== FALSE) { 
		if($row>0){
			$dataz=mysql_escape_string($data[1]);
			$conn->query("update item set itm_division='$data[9]' where itm_reference='$data[1]';");
			echo "$data[1] has been added!<br/>";	
		}
        $row++; 
    }
    fclose($file);
	unlink("../import/files_for_upload/uplreason_reason.csv");
	$conn->close();
}

