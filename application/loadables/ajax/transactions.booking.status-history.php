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

	// Setup sort and search SQL using posted data
	$sortSql = "order by $sortname $sortorder";
	$searchSql = ($qtype != '' && $query != '') ? "where $qtype like '%$query%'" : '';

	$bookingnumber = isset($_GET['bookingnumber'])?escapeString(strtoupper(trim($_GET['bookingnumber']," "))):'';

	$customqry = "
				    select txn_booking_status_history.id,
				           txn_booking_status_history.booking_number,
				           txn_booking_status_history.status_description,
				           txn_booking_status_history.remarks,
				           txn_booking_status_history.created_date,
				           txn_booking_status_history.created_by,
				           concat(user.first_name,' ',user.last_name) as createdby,
				           txn_booking_status_history.contact,
				           txn_booking_status_history.date,
				           txn_booking_status_history.supervisor,
				           txn_booking_status_history.supervisor_mobile_number,
				           txn_booking_status_history.driver,
				           txn_booking_status_history.driver_mobile_number,
				           concat(auser.first_name,' ',auser.last_name) as assignedby,
				           txn_booking_status_history.time_ready,
				           txn_booking_status_history.driver_flag
				    from txn_booking_status_history
				    left join user on user.id=txn_booking_status_history.created_by
				    left join user as auser on auser.id=txn_booking_status_history.assigned_by
				    where booking_number='$bookingnumber'
				";


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
			from ( $customqry ) as tbl
			$searchSql
			$sortSql
			$limitSql";

			//echo $sql;

		

			
	$results = mysql_query($sql);
	$line = 1;

	$deletebtnflag = true;
	
	while ($obj = mysql_fetch_object($results)) {
		$supervisor = str_replace(';',"<br>", $obj->supervisor);
		$supervisormobile = str_replace(';',"<br>", $obj->supervisor_mobile_number);
		$drivermobile = str_replace(';',"<br>", $obj->driver_mobile_number);
		$id = $obj->id;
		$createddate = dateFormat($obj->created_date,'m/d/Y h:i:s A');
		$timeready = dateFormat($obj->time_ready,'m/d/Y h:i:s A');
		$date = dateFormat($obj->date,'m/d/Y h:i:s A');

		$deletebtn = $deletebtnflag==false?"<img src='../resources/img/trash.png' class='pointer deletebookingstatushistorybtn' status='".utfEncode($obj->status_description)."' title='Delete Status History' rowid='$id' height='16px'>":'';

		$data['rows'][] = array(
									'id' => $obj->id,
									'cell' => array(
										             $deletebtn,
													 utfEncode($obj->status_description), 
													 utfEncode($obj->createdby),
													 $createddate,
													 $supervisor,
													 $supervisormobile,
													 utfEncode($obj->driver),
													 $drivermobile,
													 $timeready,
													 utfEncode($obj->assignedby),
													 utfEncode($obj->contact),
													 $date,
													 utfEncode($obj->remarks)
													 
													 

													)

								);
		$line++;
	}
	echo json_encode($data);
?>