<?php
	include('../../../config/connection.php');
	include('../../../config/functions.php');

	$location = 'NULL';
	$getuserlocationrs = query("select * from user where id='$userid'");
	$forc = array();
	while($obj=fetch($getuserlocationrs)){
		$location = $obj->location_id>0?$obj->location_id:'NULL';
		if($obj->courier_supervisor_flag){
			array_push($forc," vehicle_type.type = 'COURIER'");
		}
		if($obj->freight_supervisor_flag){
			array_push($forc," vehicle_type.type = 'FREIGHT'");
		}
	}

	$loccon = '';
	$alllocationflag = hasAccess(USERID,'alllocationbookingapproval');
	if($alllocationflag!=1){
		$loccon = " and txn_booking.location_id='$location'";
	}

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

	
	if(count($forc)>0){
		$forc = "(".implode(" or ",$forc).")";
	}
	else{
		// $forc = USERID."=1";
		$forc = "1=1";
	}

	// Setup sort and search SQL using posted data
	$sortSql = "order by $sortname $sortorder";
	$searchSql = ($qtype != '' && $query != '') ? "where $qtype like '%$query%'" : '';

	$customqry = "
				    select txn_booking.id,
				           txn_booking.booking_number,
				           origintbl.description as origin,
				           destinationtbl.description as destination,
				           txn_booking.pickup_date,
				           txn_booking.created_date,
				           txn_booking.posted_date,
				           concat(puser.first_name,' ',puser.last_name) as posted_by,
				           concat(cuser.first_name,' ',cuser.last_name) as created_by,
				           location.description as location
				    from txn_booking
				    left join origin_destination_port as origintbl on origintbl.id=txn_booking.origin_id
					left join origin_destination_port as destinationtbl on destinationtbl.id=txn_booking.destination_id
					left join user as puser on puser.id=txn_booking.posted_by
					left join user as cuser on cuser.id=txn_booking.created_by
					left join location on location.id=txn_booking.location_id
					left join vehicle_type on vehicle_type.id = txn_booking.vehicle_type_id
					where ({$forc}) and txn_booking.status='POSTED' $loccon
				";


	// Get total count of records
	$sql = "select * from ( $customqry ) as tbl $searchSql";
	//echo $sql;
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	///$total = $row[0];
	$total = mysql_num_rows($result);



	// Setup paging SQL
	$pageStart = ($page-1)*$rp;
	$limitSql = "limit $pageStart, $rp";

	// Return JSON data
	$data = array();
	$data['page'] = $page;
	$data['total'] = $total;
	$data['rows'] = array();
	$sql = "select *
			from ( $customqry ) as tbl
			$searchSql
			$sortSql
			$limitSql";

			

		

			
	$results = mysql_query($sql);

	$line = 1;
	while ($obj = mysql_fetch_object($results)) {
		$id = $obj->id;
		$bookingnumber = $obj->booking_number;
		$origin = utfEncode($obj->origin);
		$destination = utfEncode($obj->destination);
		$location = utfEncode($obj->location);
		$pickupdate = $obj->pickup_date;
		$createdby = utfEncode($obj->created_by);
		$createddate = $obj->created_date;
		$postedby = utfEncode($obj->posted_by);
		$posteddate = $obj->posted_date;
		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													"<img src='../resources/img/clipboard2.png' rowid='$id' title='Review Booking' class='reviewbookingbtn pointer' data-toggle='modal' href='#reviewbookingmodal' height='20px' bookingnumber='$bookingnumber'>",
													 $id, 
													 $bookingnumber,
													 $origin,
													 $destination,
													 $pickupdate,
													 $location,
													 $createdby,
													 $createddate,
													 $postedby,
													 $posteddate

													),
									'rowAttr'=>array(
													   'rowid'=>$id
													)
								);
		$line++;
	}
	echo json_encode($data);
?>