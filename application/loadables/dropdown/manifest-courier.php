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
		$query = query("select distinct driver_name
		            from txn_manifest 
		            where driver_name like '%".$search."%' 
		            order by driver_name asc
		            limit 40");
	}
	else{
		$query = query("select distinct driver_name
		            from txn_manifest 
		            order by driver_name asc
		            limit 50");
	}	
	
	
	$rscount = getNumRows($query);
	if($rscount>0){
		$data[] = array('id' => 'NULL', 'text' => '-');
		while($obj=fetch($query)){
			$id = $obj->driver_name;
			$desc = utfEncode($obj->driver_name);
			$data[] = array('id' => $id, 'text' => $desc);		
		}
	}
	else{
		$data[] = array('id' => '', 'text' => 'No Results Found');
	}
	

	// return the result in json
	echo json_encode($data);

?>
