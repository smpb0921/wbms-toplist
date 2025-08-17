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
		$query = query("select id,
		                       code,
		                       description
		            from status 
		            where code like '%".$search."%' or description like '%".$search."%' and type='$type'
		            order by description asc
		            limit 40");
	}
	else{
		$query = query("select id,
		                   code,
		                   description
		            from status
		            where type='$type'
		            order by description asc
		            limit 50");
	}	
	
	
	$rscount = getNumRows($query);
	if($rscount>0){
		$data[] = array('id' => 'NULL', 'text' => '-');
		while($obj=fetch($query)){
			$id = $obj->id;
			$code = utfEncode($obj->code);
			$desc = utfEncode($obj->description);
			$data[] = array('id' => $code, 'text' => '['.$code.'] '.$desc);		
		}
	}
	else{
		$data[] = array('id' => '', 'text' => 'No Results Found');
	}
	

	// return the result in json
	echo json_encode($data);

?>
