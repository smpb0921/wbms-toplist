<?php
	include('../../../config/connection.php');
	include('../../../config/functions.php');


	$wbmnumber = isset($_GET['wbmnumber'])?strtoupper($_GET['wbmnumber']):'';

	

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
				    select txn_waybill_movement_waybill.id,
				           txn_waybill_movement_waybill.waybill_movement_number,
				           txn_waybill_movement_waybill.waybill_number,
				           txn_waybill_movement_waybill.created_date,
				           txn_waybill_movement_waybill.created_by,
				           txn_waybill_movement_waybill.remarks,
				           origintbl.description as origin,
				           destinationtbl.description as destination,
				           destinationroutetbl.description as destinationroute,
				           numofpckgtbl.numofpackage,
				           totalpckgtbl.totalpackage,
				           concat(numofpckgtbl.numofpackage,'/',totalpckgtbl.totalpackage) as pckgs
				    from txn_waybill_movement_waybill
				    left join txn_waybill on txn_waybill.waybill_number=txn_waybill_movement_waybill.waybill_number
					left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id
				    left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id
				    left join destination_route as destinationroutetbl on destinationroutetbl.id=txn_waybill.destination_route_id
				    left join (		
				    			 select txn_waybill_movement_package_code.waybill_movement_number,
				    			 		txn_waybill_movement_package_code.waybill_number,
				    			 		count(package_code) as numofpackage
				    			 from txn_waybill_movement_package_code
				    			 where txn_waybill_movement_package_code.waybill_movement_number='$wbmnumber'
				    			 group by txn_waybill_movement_package_code.waybill_number

				    		   ) as numofpckgtbl
				    on numofpckgtbl.waybill_movement_number=txn_waybill_movement_waybill.waybill_movement_number and
				       numofpckgtbl.waybill_number=txn_waybill_movement_waybill.waybill_number
				    left join (
				    			 select waybill_number,
				    			        count(code) as totalpackage 
				    			 from txn_waybill_package_code 
				    			 group by waybill_number

				    		   ) as totalpckgtbl
				    on totalpckgtbl.waybill_number=txn_waybill.waybill_number
				    where txn_waybill_movement_waybill.waybill_movement_number='$wbmnumber'
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
	if(!$results){
		echo $sql;
	}
	$line = 1;

	$wbmstatus = '';
	$getwbmstatusrs = query("select * from txn_waybill_movement where waybill_movement_number='$wbmnumber'");
	while($objtemp=fetch($getwbmstatusrs)){
		$wbmstatus = $objtemp->status;
	}
	

	while ($obj = mysql_fetch_object($results)) {
		$id = $obj->id;
		$wbmnumber = $obj->waybill_movement_number;
		$wbnumber = $obj->waybill_number;
		$origin = utfEncode($obj->origin);
		$destination = utfEncode($obj->destination);
		$destinationroute = utfEncode($obj->destinationroute);
		$pckgs = $obj->pckgs;
		$remarks = utfEncode($obj->remarks);



		$rowcheckbox = '';
		$roweditremarksbtn = '';
		if($wbmstatus!='POSTED'){
			$rowcheckbox = "<input type='checkbox' class='wbmwaybillrowcheckbox valignmiddle' rowid='$id'>";
			$roweditremarksbtn = "<img src='../resources/flexigrid/images/edit.png' title='Edit Remarks' class='wbmwaybillremarkseditbtn pointer' data-toggle='modal' href='#wbmwaybillremarksmodal' rowid='$id' remarks='$remarks' height='20px'> ";
		}
		//<img src='../resources/flexigrid/images/edit.png' title='Edit Remarks' class='wbmwaybillremarkseditbtn pointer' data-toggle='modal' href='#wbmwaybillremarksmodal' height='20px'>




		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													"$rowcheckbox",
													 $wbnumber, 
													 $pckgs,
													 $origin,
													 $destination,
													 $destinationroute,
													 "$roweditremarksbtn$remarks"
													 
													),
									'rowAttr'=>array(
													   'rowid'=>$id,
													   'rowWaybill'=>$wbnumber
													)
								);
		$line++;
	}
	echo json_encode($data);
?>