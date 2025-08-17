<?php 
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

	// strip tags may not be the best method for your project to apply extra layer of security but fits needs for this tutorial 
	$search = isset($_GET['q'])?strip_tags(trim($_GET['q'])):''; 

	$district = isset($_GET['district'])?strip_tags(trim($_GET['district'])):'';
	$city = isset($_GET['city'])?strip_tags(trim($_GET['city'])):'';

	$search = escapeString($search);
	$district = escapeString($district);
	$city = escapeString($city);

	if($city==''){
		$citystr = '';
	}
	else{
		$citystr = " and city='$city'";
	}

	if($district==''){
		$districtstr = '';
	}
	else{
		$districtstr = " and district_barangay='$district'";
	}

	// Do Prepared Query 
	if($search!=''){
		$query = query("select distinct zip_code,
		                       origin_destination_port.description as region,
		                       country_name
		            from district_city_zipcode
		            left join origin_destination_port on origin_destination_port.id=district_city_zipcode.origin_destination_port_id
		            left join countries on countries.id=origin_destination_port.country_id 
		            where zip_code like '%".$search."%' and zip_code!='' $districtstr $citystr
		            group by zip_code
		            order by zip_code asc
		            limit 150");
	}
	else{
		$query = query("select distinct zip_code,
		                       origin_destination_port.description as region,
		                       country_name
		            from district_city_zipcode
		            left join origin_destination_port on origin_destination_port.id=district_city_zipcode.origin_destination_port_id
		            left join countries on countries.id=origin_destination_port.country_id
		            where zip_code!='' $districtstr $citystr
		            group by zip_code
		            order by zip_code asc
		            limit 150");
	}	
	
	
	$rscount = getNumRows($query);
	if($rscount>0){
		$data[] = array('id' => '', 'text' => '-');
		while($obj=fetch($query)){
			$desc = utfEncode($obj->zip_code);
			$data[] = array('id' => $desc, 'text' => $desc, 'selected'=>'selected');		
		}
	}
	else{
		$data[] = array('id' => '', 'text' => 'No Results Found');
	}
	

	// return the result in json
	echo json_encode($data);

?>
