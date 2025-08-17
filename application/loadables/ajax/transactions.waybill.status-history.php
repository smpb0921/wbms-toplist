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
	$searchSql = ($qtype != '' && $query != '') ? " and $qtype like '%$query%'" : '';

	$waybillnumber = isset($_GET['waybillnumber'])?escapeString(strtoupper(trim($_GET['waybillnumber']," "))):'';

	$customqry = "
				    select txn_waybill_status_history.id,
				           txn_waybill_status_history.waybill_number,
				           txn_waybill_status_history.status_description,
				           txn_waybill_status_history.remarks,
				           txn_waybill_status_history.created_date,
				           txn_waybill_status_history.created_by,
				           concat(user.first_name,' ',user.last_name) as createdby,
				           txn_waybill_status_history.received_by,
				           txn_waybill_status_history.received_date,
				           txn_waybill_status_history.personnel_id,
				           concat(personnel.first_name,' ',personnel.last_name) as courier
				    from txn_waybill_status_history
				    left join user on user.id=txn_waybill_status_history.created_by
				    left join personnel on personnel.id=txn_waybill_status_history.personnel_id
				    where waybill_number='$waybillnumber'
				";

//,' (',personnel.position,')'

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

	$deletebtnflag = userAccess(USERID,'.deletewaybillstatushistorybtn');
	
	while ($obj = mysql_fetch_object($results)) {
		$id = $obj->id;
		$createddate = dateFormat($obj->created_date,'m/d/Y h:i:s A');
		$receiveddate = dateFormat($obj->received_date,'m/d/Y h:i:s A');

		$deletebtn = $deletebtnflag==false?"<img src='../resources/img/trash.png' class='pointer deletewaybillstatushistorybtn' status='".utfEncode($obj->status_description)."' title='Delete Status History' rowid='$id' height='16px'>":'';

		$data['rows'][] = array(
									'id' => $obj->id,
									'cell' => array(
										             $deletebtn,
													 utfEncode($obj->status_description),
													 $createddate,
													 utfEncode($obj->createdby), 
													 utfEncode($obj->remarks),
													 utfEncode($obj->received_by),
													 $receiveddate,
													 utfEncode($obj->courier)
													 

													)

								);
		$line++;
	}
	echo json_encode($data);
?>