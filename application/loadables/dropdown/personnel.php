<?php 
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

	// strip tags may not be the best method for your project to apply extra layer of security but fits needs for this tutorial 
	$search = isset($_GET['q'])?strip_tags(trim($_GET['q'])):''; 
	$search = escapeString($search);


	$hastype = isset($_GET['hastype'])?escapeString(strtoupper($_GET['hastype'])):'0';
	$type = isset($_GET['type'])?escapeString(strtoupper($_GET['type'])):'';
	$position = isset($_GET['position'])?escapeString(strtoupper($_GET['position'])):'';
	$flag = isset($_GET['flag'])?escapeString(strtoupper($_GET['flag'])):'';

	$filter = array();
	$positioncondition = '';
	if($position!=''){
		array_push($filter,"position='$position'");
	}


	$typecondition='';
	$typecondition1='';
	if($hastype==0){
		array_push($filter,"type='$type'");
	}

	if($flag!=''){
		array_push($filter,"active_flag='$flag'");
	}

	$wherecondition = '';
	$wherecondition2 = '';
	if(count($filter)>0){
		$wherecondition = " where ".implode(' and ',$filter);
		$wherecondition2 = " and ".implode(' and ',$filter);
	}
	


	// Do Prepared Query 
	if($search!=''){
		$qry =     "select id,
		                       first_name,
		                       last_name,
		                       contact_number
		            from personnel 
		            where (first_name like '%".$search."%' or last_name like '%".$search."%')  $wherecondition2
		            order by first_name asc
		            limit 40";
	}
	else{
		$qry =  "select id,
		                       first_name,
		                       last_name,
		                       contact_number
		            from personnel 
		            $wherecondition
		            order by first_name asc
		            limit 50";
	}	
	
	//echo $qry;
	$query = query($qry);
	$rscount = getNumRows($query);
	if($rscount>0){
		$data[] = array('id' => 'NULL', 'text' => '-');
		while($obj=fetch($query)){
			$id = $obj->id;
			$firstname = utfEncode($obj->first_name);
			$lastname = utfEncode($obj->last_name);
			$contact = utfEncode($obj->contact_number);
			$data[] = array('id' => $firstname.' '.$lastname, 'text' => $firstname.' '.$lastname, 'contactnumber'=>$contact);		
		}
	}
	else{
		$data[] = array('id' => '', 'text' => 'No Results Found');
	}
	

	// return the result in json
	echo json_encode($data);

?>
