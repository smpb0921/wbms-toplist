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
		                       payee_name,
		                       address,
                               tin
		            from payee
		            where payee_name like '%".$search."%' or tin like '%".$search."%'
		            order by company_name asc
		            limit 40");
	}
	else{
		$query = query("select  id,
                                payee_name,
                                address,
                                tin
		            from payee 
		            order by payee_name asc
		            limit 50");
	}	
	
	
	$rscount = getNumRows($query);
	if($rscount>0){
		$data[] = array('id' => 'NULL', 'text' => '-');
		while($obj=fetch($query)){
			$id = $obj->id;
			$payeename = utfEncode($obj->payee_name);
			$tin = utfEncode($obj->tin);
			$data[] = array('id' => $id, 'text' => '['.$tin.'] '.$payeename);		
		}
	}
	else{
		$data[] = array('id' => '', 'text' => 'No Results Found');
	}
	

	// return the result in json
	echo json_encode($data);

?>
