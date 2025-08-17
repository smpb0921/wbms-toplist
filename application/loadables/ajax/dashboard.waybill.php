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

	$useronlycondition = "where txn_waybill.status='LOGGED'";
	$checkuserviewconrs = mysql_query("select * from user_rights where user_id='".USERID."' and menu_id='dashuseronly'");
	if(mysql_num_rows($checkuserviewconrs)>0){
		$useronlycondition = "where txn_waybill.status='LOGGED' and 
		                            txn_waybill.created_by=".USERID;
	}


	$customqry =   "select  txn_waybill.id,
							txn_waybill.waybill_number,
							txn_waybill.status,
							txn_waybill.delivery_date,
							origintbl.description as origin,
							destinationtbl.description as destination,
							concat(cuser.first_name,' ',cuser.last_name) as created_by,
							txn_waybill.created_date,
							txn_waybill.updated_date
					from txn_waybill
					left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id
				    left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id
					left join user as cuser on cuser.id=txn_waybill.created_by
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
													 $obj->waybill_number, 
													 $obj->status,
													 dateFormat($obj->delivery_date,'m/d/Y'),
													 utfEncode($obj->origin),
													 utfEncode($obj->destination),
													 utfEncode($obj->created_by),
													 dateFormat($obj->created_date,'m/d/Y h:i:s A'),
													 dateFormat($obj->updated_date,'m/d/Y h:i:s A')

													),
									'rowAttr'=>array(
													   'waybillnumber'=>$obj->waybill_number,
													   'class'=>'dash-waybillpendingrow pointer'
													)
								);
		$line++;
	}
	echo json_encode($data);
?>