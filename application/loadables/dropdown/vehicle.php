<?php 
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

	// strip tags may not be the best method for your project to apply extra layer of security but fits needs for this tutorial 
	$search = isset($_GET['q'])?strip_tags(trim($_GET['q'])):''; 
	$search = escapeString($search);

	$type = isset($_GET['type'])?escapeString(strtoupper($_GET['type'])):'';
	$flag = isset($_GET['flag'])?" and vehicle.active_flag='".escapeString(strtoupper($_GET['flag']))."' ":'';

	// Do Prepared Query 
	if($search!=''){
		$query = query("select id,
		                       plate_number,
		                       model,
		                       year
		            from vehicle 
		            where (plate_number like '%".$search."%' or model like '%".$search."%') and vehicle_type_id='$type' $flag
		            order by plate_number asc
		            limit 40");
	}
	else{
		$query = query("select id,
		                       plate_number,
		                       model,
		                       year
		            from vehicle 
		            where vehicle_type_id='$type' $flag
		            order by plate_number asc
		            limit 50");
	}	
	
	
	$rscount = getNumRows($query);
	if($rscount>0){
		$data[] = array('id' => 'NULL', 'text' => '-');
		while($obj=fetch($query)){
			$id = $obj->id;
			$platenumber = utfEncode($obj->plate_number);
			$model = utfEncode($obj->model);
			$year = utfEncode($obj->year);
			$data[] = array('id' => $platenumber, 'text' => $platenumber);		
		}
	}
	else{
		$data[] = array('id' => '', 'text' => 'No Results Found');
	}
	

	// return the result in json
	echo json_encode($data);

?>
