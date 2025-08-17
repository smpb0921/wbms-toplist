<?php
	include('../../../config/connection.php');
	include('../../../config/functions.php');




	$page = 1;	// The current page
	$sortname = '';	// Sort column
	$sortorder = '';	// Sort order
	$qtype = '';	// Search column
	$query = '';	// Search string

	$txnID = isset($_GET['txnid'])?escapeString($_GET['txnid']):'';

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

	$customqry = "  select txn_waybill.id,
	                       txn_waybill.waybill_number,
						   txn_waybill.mawbl_bl 
					from txn_waybill
					where txn_waybill.waybill_number not in (
																select waybill_number
																from costing_waybill
																where costing_id='$txnID'
														  ) and
							   txn_waybill.status!='VOID' and
							   txn_waybill.status!='LOGGED' 
				 ";



	// Setup sort and search SQL using posted data
	$sortSql = '';//"order by $sortname $sortorder";
	$searchSql = ($qtype != '' && $query != '') ? " and $qtype like '%$query%'" : '';
	


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
	//$hidebtn = (userAccess(USERID,'.edittranstypebtn')==false)?'':'hidden';

	$line = $line+(($page-1)*$rp);

	while ($obj = mysql_fetch_object($results)) {
		

		
		$waybillid = utfEncode($obj->id);
		$waybillnumber = utfEncode($obj->waybill_number);
		$mawbl = utfEncode($obj->mawbl_bl);

	



		$addbtn = "<img class='pointer costing-addrcvbtn' src='../resources/flexigrid/images/add.png' rowid='$waybillid' txnnumber='$waybillnumber' height='20px'>";

		

		$data['rows'][] = array(
									'id' => $waybillid,
									'cell' => array(
													 $addbtn,
													 $waybillid,
													 $waybillnumber,
													 $mawbl
													 
													 
													),
									'rowAttr'=>array(
													   'rowid'=>$waybillid
													)
								);
		$line++;

	}
	echo json_encode($data);
?>