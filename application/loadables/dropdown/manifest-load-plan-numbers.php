<?php 
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

	// strip tags may not be the best method for your project to apply extra layer of security but fits needs for this tutorial 
	$search = isset($_GET['q'])?strip_tags(trim($_GET['q'])):''; 

	$loadplanmultiplemanifest = getInfo("company_information","load_plan_multiple_manifest","where id=1");
	if($loadplanmultiplemanifest==1){
		//$ldpstat = "and (status = 'POSTED' or status='DISPATCHED')";
		$ldpstat = "and status='POSTED'";
	}
	else{
		$ldpstat = "and status='POSTED'";
	}

	$search = escapeString($search);
	// Do Prepared Query 
	if($search!=''){
		$qry = "select id,
		                       load_plan_number
		            from txn_load_plan 
		            where load_plan_number like '%".$search."%' $ldpstat and load_plan_number not in (select load_plan_number from txn_manifest where status!='VOID' and load_plan_number is not null)
		            order by load_plan_number asc
		            limit 40";
	}
	else{
		$qry = "select id,
		                       load_plan_number
		            from txn_load_plan 
		            where load_plan_number not in (select load_plan_number from txn_manifest where status!='VOID' and load_plan_number is not null) $ldpstat
		            order by load_plan_number asc
		            limit 50";
	}

	//echo $qry;	
	$query = query($qry);
	
	$rscount = getNumRows($query);
	if($rscount>0){
		$data[] = array('id' => 'NULL', 'text' => '-');
		while($obj=fetch($query)){
			$id = $obj->id;
			$desc = $obj->load_plan_number;
			$data[] = array('id' => $desc, 'text' => $desc);		
		}
	}
	else{
		$data[] = array('id' => '', 'text' => 'No Results Found');
	}
	

	// return the result in json
	echo json_encode($data);

?>
