<?php 
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

	// strip tags may not be the best method for your project to apply extra layer of security but fits needs for this tutorial 
	$search = isset($_GET['q'])?strip_tags(trim($_GET['q'])):''; 
	$city = isset($_GET['city'])?strip_tags(trim($_GET['city'])):'';

	$search = escapeString($search);
	$city = escapeString($city);

	if($city==''){
		$citystr = '';
	}
	else{

		$citystr = " and city='$city'";
	}


	// Do Prepared Query 
	if($search!=''){
		$query = query("select distinct district_barangay,
		                       city,
		                       zip_code,
		                       origin_destination_port.description as region,
		                       country_name
		            from district_city_zipcode
		            left join origin_destination_port on origin_destination_port.id=district_city_zipcode.origin_destination_port_id
		            left join countries on countries.id=origin_destination_port.country_id 
		            where district_barangay like '%".$search."%' and district_barangay!='' $citystr
		            group by district_barangay
		            order by district_barangay asc
		            limit 50");

		
	}
	else{
		$query = query("select distinct district_barangay,
		                       city,
		                       zip_code,
		                       origin_destination_port.description as region,
		                       country_name
		            from district_city_zipcode
		            left join origin_destination_port on origin_destination_port.id=district_city_zipcode.origin_destination_port_id
		            left join countries on countries.id=origin_destination_port.country_id
		            where district_barangay!='' $citystr
		            group by district_barangay
		            order by district_barangay asc
		            limit 50");
	}	
	
	
	$rscount = getNumRows($query);
	if($rscount>0){
		$data[] = array('id' => '', 'text' => '-');
		while($obj=fetch($query)){
			$desc = utfEncode($obj->district_barangay);
			$data[] = array('id' => $desc, 'text' => $desc);		
		}
	}
	else{
		$data[] = array('id' => '', 'text' => 'No Results Found');
	}
	

	// return the result in json
	echo json_encode($data);

?>
