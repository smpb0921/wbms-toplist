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

	$useronlycondition = "where txn_manifest.status='LOGGED'";
	$checkuserviewconrs = mysql_query("select * from user_rights where user_id='".USERID."' and menu_id='dashuseronly'");
	if(mysql_num_rows($checkuserviewconrs)>0){
		$useronlycondition = "where txn_manifest.status='LOGGED' and
		                            txn_manifest.created_by=".USERID;
	}


	$customqry =   "select  txn_manifest.id,
							txn_manifest.manifest_number,
							txn_manifest.load_plan_number,
							txn_manifest.status,
							location.description as location,
							origintbl.description as origin,
							destinationtbl.description as destination,
							mode_of_transport.description as mode_of_transport,
							concat(cuser.first_name,' ',cuser.last_name) as created_by,
							txn_manifest.created_date,
							txn_manifest.updated_date,
							group_concat(waybill_number) as waybills
					from txn_manifest
					left join txn_load_plan on txn_load_plan.load_plan_number=txn_manifest.load_plan_number
					left join origin_destination_port as origintbl on origintbl.id=txn_load_plan.origin_id
				    left join origin_destination_port as destinationtbl on destinationtbl.id=txn_load_plan.destination_id
				    left join location on location.id=txn_load_plan.location_id
				    left join mode_of_transport on mode_of_transport.id=txn_load_plan.mode_of_transport_id
					left join user as cuser on cuser.id=txn_load_plan.created_by
					left join txn_manifest_waybill on txn_manifest_waybill.manifest_number=txn_manifest.manifest_number
					$useronlycondition
					group by txn_manifest.manifest_number";

	

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
													 $obj->manifest_number, 
													 $obj->status,
													 $obj->load_plan_number, 
													 utfEncode($obj->location),
													 utfEncode($obj->origin),
													 utfEncode($obj->destination),
													 utfEncode($obj->mode_of_transport),
													 $obj->waybills,
													 utfEncode($obj->created_by),
													 dateFormat($obj->created_date,'m/d/Y h:i:s A'),
													 dateFormat($obj->updated_date,'m/d/Y h:i:s A')

													),
									'rowAttr'=>array(
													   'manifestnumber'=>$obj->manifest_number,
													   'class'=>'dash-manifestpendingrow pointer'
													)
								);
		$line++;
	}
	echo json_encode($data);
?>