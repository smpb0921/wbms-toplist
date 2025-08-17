<?php
	include('../../../config/connection.php');
	include('../../../config/functions.php');

	$myapprovalcon = '';
	$viewallhistory = hasAccess(USERID,'viewallapprovalhistory');
	if($viewallhistory!=1){
		$myapprovalcon = " where txn_booking_approval_rejection_history.created_by='".USERID."'";
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

	// Setup sort and search SQL using posted data
	$sortSql = "order by $sortname $sortorder";
	$searchSql = ($qtype != '' && $query != '') ? "where $qtype like '%$query%'" : '';

	$customqry = "
				    select txn_booking_approval_rejection_history.id,
				           txn_booking_approval_rejection_history.action_taken,
				           txn_booking_approval_rejection_history.booking_id,
				           txn_booking_approval_rejection_history.remarks,
				           txn_booking_approval_rejection_history.created_date,
				           txn_booking.booking_number,
				           concat(first_name,' ',last_name) as created_by
				    from txn_booking_approval_rejection_history
				    left join txn_booking on txn_booking.id=txn_booking_approval_rejection_history.booking_id
				    left join user on user.id=txn_booking_approval_rejection_history.created_by
				    $myapprovalcon
				";


	// Get total count of records
	$sql = "select * from ( $customqry ) as tbl $searchSql";
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
		$action = $obj->action_taken;
		$bookingnumber = $obj->booking_number;
		$remarks = utfEncode($obj->remarks);
		$createddate = $obj->created_date;
		$createdby = utfEncode($obj->created_by);

		$actionimg = '';
		if($action=='APPROVE'){
			$actionimg = "<img src='../resources/img/checkmark.png' height='11px'>";
		}
		else if($action=='REJECT'){
			$actionimg = "<img src='../resources/img/cancel1.png' height='11px'>";
		}

		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													"<img src='../resources/img/clipboard2.png' rowid='$id' title='View Booking History' class='viewbookinghistorybtn pointer' height='20px' bookingnumber='$bookingnumber'>",
													 $id, 
													 $bookingnumber,
													 $actionimg,
													 $remarks,
													 $createdby,
													 $createddate

													),
									'rowAttr'=>array(
													   'rowid'=>$id
													)
								);
		$line++;
	}
	echo json_encode($data);
?>