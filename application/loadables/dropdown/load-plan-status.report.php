<?php 
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

	// strip tags may not be the best method for your project to apply extra layer of security but fits needs for this tutorial 
	$search = isset($_GET['q'])?strip_tags(trim($_GET['q'])):''; 
	$type = isset($_GET['type'])?strtoupper($_GET['type']):'';
	$search = escapeString($search);

	// Do Prepared Query 
	if($search!=''){
		$query = query("select distinct status
		            from txn_load_plan
		            where status like '%".$search."%'
		            order by status asc
		            limit 40");
	}
	else{
		$query = query("select distinct status
		            from txn_load_plan
		            order by status asc
		            limit 40");
	}	
	
	
	$rscount = getNumRows($query);
	if($rscount>0){
		$data[] = array('id' => 'NULL', 'text' => '-');
		while($obj=fetch($query)){
			$desc = $obj->status;
			$data[] = array('id' => $desc, 'text' => $desc);		
		}
	}
	else{
		$data[] = array('id' => '', 'text' => 'No Results Found');
	}
	

	// return the result in json
	echo json_encode($data);

?>
