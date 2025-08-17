<?php
$srv = $_SERVER['HTTP_HOST'];
if(!($srv=="localhost" || $srv=="127.0.0.1")){
   header('Location: http://wms.jrrdist.com');
} 
include_once('conn.php');	
$row = 0;
if (($file = fopen("UPLUSER.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($file, 100000, ",")) !== FALSE) { 
		if($row>0){
			
			$name = explode(",",mysql_escape_string(str_replace("\"","",$data[0])));	  
			$conn->query("insert into user(first_name,last_name,username,password,active_flag,warehouse_id)VALUES('$name[1]','$name[0]','".strtolower(str_replace(" ","_",trim($name[1])).trim($name[0][0]))."','827ccb0eea8a706c4c34a16891f84e7b',1,1);");
			echo "$name[0] has been added!<br/>";
		}	
        $row++; 
    }
    fclose($file);
	unlink($file);
	$conn->close();
} 