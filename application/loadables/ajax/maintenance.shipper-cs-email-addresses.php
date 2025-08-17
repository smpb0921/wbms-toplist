<?php
	include('../../../config/connection.php');
	include('../../../config/functions.php');

	$shipperid = isset($_GET['shipperid'])?trim($_GET['shipperid']):'';


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
	$searchSql = ($qtype != '' && $query != '') ? "where shipper_cs_email_addresses.shipper_id='$shipperid' and $qtype like '%$query%'" : "where shipper_cs_email_addresses.shipper_id='$shipperid'";

	$customqry = "		
					select shipper_cs_email_addresses.id,
					       shipper_cs_email_addresses.email,
					       shipper_cs_email_addresses.created_date,
						   concat(cuser.first_name,' ',cuser.last_name) as created_by
					from  shipper_cs_email_addresses
					left join user as cuser on cuser.id=shipper_cs_email_addresses.created_by
					
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

	$data['page'] = 1;
	$data['total'] = $total;
	$data['rows'] = array();



	

		

	$results = mysql_query($sql);
	$line = 1;
	while ($obj = mysql_fetch_object($results)) {
		
		$email = utfEncode($obj->email);
		$createddate = dateFormat($obj->created_date,'m/d/Y');
		$createdby = utfEncode($obj->created_by);
		$id = $obj->id;

		

		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													"<input type='checkbox' class='viewshippercsemailaddressesmodal-checkbox' rowid='$id'>",
													 $email,
													 $createddate,
													 $createdby

													),
									'rowAttr'=>array(
													   'rowid'=>$id
													)
								);
		$line++;
	}
	echo json_encode($data);
?>