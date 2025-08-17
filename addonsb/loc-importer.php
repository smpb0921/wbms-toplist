
<?php 
$srv = $_SERVER['HTTP_HOST'];
if(!($srv=="localhost" || $srv=="127.0.0.1")){
   header("Location: http://$srv");
} 
include_once('conn.php');
$row = 0;
$qry = "insert into location (loc_code,loc_description,loc_pallet_slot,loc_pallet_cs,loc_capacity,warehouse_id,for_all_items,weight_capacity,cbm_capacity,zone_id) VALUES";
if (($file = fopen("../import/files_for_upload/UPLLOC.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($file, 100000, ",")) !== FALSE) {  	 
		if($row>0){ 
			$qry.="('$data[0]','$data[1]',100000000,100000000,100000000,1,1,100000000,1.8,(select id from zone where zone_code='N/A' limit 1)),";
			echo "$data[1] has been added!<br/>";
		}
        $row++;
    }
	fclose($file); 
	try{
		$qry = substr($qry,0,-1)." on duplicate key update loc_description = values(loc_description);"; 
		$conn->query($qry);  
		unlink($file);
	}
	catch(Exception $ex){
		echo $ex->getMessage();
	}
	finally{
		$conn->close(); 
	}
	
}





?>