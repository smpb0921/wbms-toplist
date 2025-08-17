<?php
    require_once "conn - Copy.php"; 
    $r = $conn->query("select order_number'PONumber' from purchase_order_header where order_status='RELEASED';");
    $a = array(); 
    if($r->num_rows<=0){
       echo "No result";
       exit;
    }  
    foreach($r as $row){  
       array_push($a,$row);
    }
    echo json_encode($a);