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
		$query = query("select id,
							   account_number,
			                   account_name
		            from shipper 
		            where account_number like '%".$search."%' or account_name like '%".$search."%'
		            order by account_name asc
		            limit 40");
	}
	else{
		$query = query("select id,
							   account_number,
			                   account_name
		            from shipper 
		            order by account_name asc
		            limit 50");
	}	
	
	
	$rscount = getNumRows($query);
	if($rscount>0){
		$data[] = array('id' => 'NULL', 'text' => '-');
		while($obj=fetch($query)){
			$id = $obj->id;
			$num = utfEncode($obj->account_number);
			$name = utfEncode($obj->account_name);
			$data[] = array('id' => $id, 'text' => "$num - $name");		
		}
	}
	else{
		$data[] = array('id' => '', 'text' => 'No Results Found');
	}
	

	// return the result in json
	echo json_encode($data);

?>
