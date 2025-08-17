<?php 
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

	// strip tags may not be the best method for your project to apply extra layer of security but fits needs for this tutorial 
	$search = isset($_GET['q'])?strip_tags(trim($_GET['q'])):''; 
	$search = escapeString($search);

	$c1 = ' and user.id!=1';
	$c2 = 'where user.id!=1';

	if(USERID==1){
		$c1 = '';
		$c2 = '';
	}


	// Do Prepared Query 
	if($search!=''){
		$query = query("select id,
		                       first_name,
		                       last_name
		            from user 
		            where first_name like '%".$search."%' or last_name like '%".$search."%' $c1
		            order by last_name asc
		            limit 40");
	}
	else{
		$query = query("select id,
		                   first_name,
		                   last_name
		            from user 
		            $c2
		            order by last_name asc
		            limit 50");
	}	
	
	
	$rscount = getNumRows($query);
	if($rscount>0){
		$data[] = array('id' => 'NULL', 'text' => '-');
		while($obj=fetch($query)){
			$id = $obj->id;
			$firstname = utfEncode($obj->first_name);
			$lastname = utfEncode($obj->last_name);
			$data[] = array('id' => $id, 'text' => $firstname.' '.$lastname);		
		}
	}
	else{
		$data[] = array('id' => '', 'text' => 'No Results Found');
	}
	

	// return the result in json
	echo json_encode($data);

?>
