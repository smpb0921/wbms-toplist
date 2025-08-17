<?php   
include_once('conn.php');  
if (($file = fopen("../import/files_for_upload/ava.csv", "r")) !== FALSE) { 
    $conn->query("set sql_safe_updates=0;");
    while (($data = fgetcsv($file, 10000000, ",")) !== FALSE) {   
            try{
                $itemcode = trim($data[2]);
                $item = $conn->query("select id from item where itm_reference='$itemcode'")->fetch_object()->id;
                $caseSize = $conn->query("select plu_qty from item_plu where item_id=$item and plu_uom=1")->fetch_object()->plu_qty;
                $qty = 10*$caseSize;
                $physId = $conn->query("select ifnull(id,0) id from physical_inventory where item_id=$item and quantity>=$qty order by quantity desc limit 1")->fetch_object()->id; 
                $conn->query("update physical_inventory set quantity=quantity-$qty where id=$physId;");
                echo "$data[2] $data[3] CaseSize is $caseSize<br/>";	 
            }
            catch(Exception $ex){ 
                echo $conn->error_get_last[0];
            }
    }
    $conn->query("set sql_safe_updates=1;");  
    fclose($file); 
	$conn->close();
}

