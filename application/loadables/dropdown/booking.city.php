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
		$query = query("select distinct shipper_pickup_city
		            from txn_booking 
		            where shipper_pickup_city like '%".$search."%'
		            order by shipper_pickup_city asc
		            limit 40");
	}
	else{
		$query = query("select distinct shipper_pickup_city
		            from txn_booking 
		            order by shipper_pickup_city asc
		            limit 50");
	}	
	
	
	$rscount = getNumRows($query);
	if($rscount>0){
		$data[] = array('id' => 'NULL', 'text' => '-');
		while($obj=fetch($query)){
			$city = utfEncode($obj->shipper_pickup_city);
			$data[] = array('id' => $city, 'text' => $city);		
		}
	}
	else{
		$data[] = array('id' => '', 'text' => 'No Results Found');
	}
	

	// return the result in json
	echo json_encode($data);

?>
