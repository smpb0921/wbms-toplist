<?php
	include('../../../config/connection.php');
	include('../../../config/functions.php');




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

	$useronlycondition = "where txn_booking.status='POSTED'";
	$checkuserviewconrs = mysql_query("select * from user_rights where user_id='".USERID."' and menu_id='dashuseronly'");
	if(mysql_num_rows($checkuserviewconrs)>0){
		$useronlycondition = "where txn_booking.status='POSTED' and 
		                            txn_booking.created_by=".USERID;
	}


	$customqry =   "select  txn_booking.id,
							txn_booking.booking_number,
							txn_booking.status,
							txn_booking.pickup_date,
							origintbl.description as origin,
							destinationtbl.description as destination,
							concat(cuser.first_name,' ',cuser.last_name) as posted_by,
							txn_booking.posted_date
					from txn_booking
					left join origin_destination_port as origintbl on origintbl.id=txn_booking.origin_id
				    left join origin_destination_port as destinationtbl on destinationtbl.id=txn_booking.destination_id
					left join user as cuser on cuser.id=txn_booking.posted_by
					$useronlycondition";

	// Setup sort and search SQL using posted data
	$sortSql = "order by $sortname $sortorder";
	$searchSql = ($qtype != '' && $query != '') ? "where $qtype like '%$query%'" : '';


	// Get total count of records
	$sql = "select * from ( $customqry ) as tbl $searchSql";
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
	$sql = "select *
			from (
			        $customqry
				  ) as tbl
			$searchSql
			$sortSql
			$limitSql";

		

			
	$results = mysql_query($sql);
	$line = 1;
	while ($obj = mysql_fetch_object($results)) {
		
		$data['rows'][] = array(
									'id' => $obj->id,
									'cell' => array(
													 $obj->booking_number, 
													 $obj->status,
													 dateFormat($obj->pickup_date,'m/d/Y'),
													 utfEncode($obj->origin),
													 utfEncode($obj->destination),
													 utfEncode($obj->posted_by),
													 dateFormat($obj->posted_date,'m/d/Y h:i:s A')

													),
									'rowAttr'=>array(
													   'bookingnumber'=>$obj->booking_number,
													   'class'=>'dash-bookingapprovalrow pointer'
													)
								);
		$line++;
	}
	echo json_encode($data);
?>