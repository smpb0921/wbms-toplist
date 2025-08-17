<?php 
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

	// strip tags may not be the best method for your project to apply extra layer of security but fits needs for this tutorial 
	$search = isset($_GET['q'])?strip_tags(trim($_GET['q'])):''; 
	$search = escapeString($search);

	$typecondition1 = isset($_GET['type'])?" and expense_type_id='".escapeString($_GET['type'])."'":'';
	$typecondition2 = isset($_GET['type'])?" where expense_type_id='".escapeString($_GET['type'])."'":'';
	

	// Do Prepared Query 
	if($search!=''){
		$query = query("select id,
		                       code,
		                       description, 
							   type
		            from chart_of_accounts
		            where code like '%".$search."%' or description like '%".$search."%'
		            $typecondition1
		            order by description asc
		            limit 40");
	}
	else{
		$query = query("select id,
		                   code,
		                   description, 
						   type
		            from chart_of_accounts 
		            $typecondition2
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
			$type = utfEncode($obj->type);
			$data[] = array('id' => $id, 'text' => $desc, 'data-type'=>$type);		
		}
	}
	else{
		$data[] = array('id' => '', 'text' => 'No Results Found');
	}
	

	// return the result in json
	echo json_encode($data);

?>
