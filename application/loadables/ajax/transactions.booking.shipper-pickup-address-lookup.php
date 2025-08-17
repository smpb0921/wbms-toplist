<?php
	include('../../../config/connection.php');
	include('../../../config/functions.php');

	$refID = isset($_GET['id'])?trim($_GET['id']," "):'13';


	$page = 1;	// The current page
	$sortname = '';	// Sort column
	$sortorder = '';	// Sort order
	$qtype = '';	// Search column
	$query = '';	// Search string

	// Get posted data
	if (isset($_POST['page'])) {
		$page = mysql_real_escape_string($_POST['page']);
	}
	if (isset($_POST['sortname'])) {
		$sortname = mysql_real_escape_string($_POST['sortname']);
	}
	if (isset($_POST['sortorder'])) {
		$sortorder = mysql_real_escape_string($_POST['sortorder']);
	}
	if (isset($_POST['qtype'])) {
		$qtype = mysql_real_escape_string($_POST['qtype']);
	}
	if (isset($_POST['query'])) {
		$query = mysql_real_escape_string($_POST['query']);
	}
	if (isset($_POST['rp'])) {
		$rp = mysql_real_escape_string($_POST['rp']);
	}

	// Setup sort and search SQL using posted data
	$sortSql = "order by $sortname $sortorder";
	$searchSql = ($qtype != '' && $query != '') ? " and $qtype like '%$query%'" : '';

	$customqry = "
				    select id,
				           shipper_id,
				           pickup_street_address,
				           pickup_district,
				           pickup_city,
				           pickup_state_province,
				           pickup_zip_code,
				           pickup_country,
				           created_date
				    from shipper_pickup_address
				    where shipper_id='$refID'
				";


	// Get total count of records
	$sql = "$customqry $searchSql";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$total = mysql_num_rows($result);

	// Setup paging SQL
	$pageStart = ($page-1)*$rp;
	$limitSql = "limit $pageStart, $rp";

	// Return JSON data
	$data = array();
	$data['page'] = $page;
	$data['total'] = $total;
	$data['rows'] = array();
	$sql = "$customqry 
			$searchSql
			$sortSql
			$limitSql";

			//echo $sql;

		

			
	$results = mysql_query($sql);
	$line = 1;
	while ($obj = mysql_fetch_object($results)) {
		
		$id = $obj->id;
		$street = utfEncode($obj->pickup_street_address);
		$district = utfEncode($obj->pickup_district);
		$city = utfEncode($obj->pickup_city);
		$region = utfEncode($obj->pickup_state_province);
		$zip = utfEncode($obj->pickup_zip_code);
		$country = utfEncode($obj->pickup_country);
		$createddate = $obj->created_date;


		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													 $street, 
													 $district,
													 $city,
													 $region,
													 $zip,
													 $country,
													 $createddate

													),
									'rowAttr'=>array(
													   'rowid'=>$id,
													   "class"=>'shipperpickupaddresslookuprow pointer'
													)

								);
		$line++;
	}
	echo json_encode($data);
?>