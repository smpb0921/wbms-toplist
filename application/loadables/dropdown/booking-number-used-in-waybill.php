<?php 
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

	// strip tags may not be the best method for your project to apply extra layer of security but fits needs for this tutorial 
	$search = isset($_GET['q'])?strip_tags(trim($_GET['q'])):''; 
	$search = escapeString($search);

	// Do Prepared Query 
	if($search!=''){
		$query = query("select txn_waybill.booking_number
		            from txn_waybill 
		            where txn_waybill.booking_number like '%".$search."%' 
		            
		            group by txn_waybill.booking_number
		            order by txn_waybill.booking_number desc
		            limit 40");
	}
	else{
		$query = query("select txn_waybill.booking_number
		            from txn_waybill 
		            group by txn_waybill.booking_number
		            order by txn_waybill.booking_number desc
		            limit 50");
	}	
	
	
	$rscount = getNumRows($query);
	if($rscount>0){
		$data[] = array('id' => 'NULL', 'text' => '-');
		while($obj=fetch($query)){
			$id = $obj->booking_number;
			$desc = $obj->booking_number;
			$data[] = array('id' => $desc, 'text' => $desc);		
		}
	}
	else{
		$data[] = array('id' => '', 'text' => 'No Results Found');
	}
	

	// return the result in json
	echo json_encode($data);

?>
