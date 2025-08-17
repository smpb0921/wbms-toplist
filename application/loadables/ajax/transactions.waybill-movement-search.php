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
	


	$customqry = "
				    select txn_waybill_movement.id,
				    	   txn_waybill_movement.waybill_movement_number,
				           txn_waybill_movement.status,
				           txn_waybill_movement.location_id,
				           txn_waybill_movement.movement_type_id,
				           txn_waybill_movement.document_date,
				           txn_waybill_movement.created_date,
				           txn_waybill_movement.created_by,
				           concat(user.first_name,' ',user.last_name) as createdby,
				           group_concat(txn_waybill_movement_waybill.waybill_number) as waybills,
				           location.description as location,
				           movement_type.description as movementtype
				    from txn_waybill_movement
				    left join location on location.id=txn_waybill_movement.location_id
				    left join movement_type on movement_type.id=txn_waybill_movement.movement_type_id
				    left join txn_waybill_movement_waybill on txn_waybill_movement_waybill.waybill_movement_number=txn_waybill_movement.waybill_movement_number
				    left join user on user.id=txn_waybill_movement.created_by
				";

	/****** FILTER VARS ****/
	$filter = array();
	$status = isset($_GET['status'])?escapeString(strtoupper(trim($_GET['status']," "))):'';
	$movementtype = isset($_GET['movementtype'])?escapeString(strtoupper(trim($_GET['movementtype']," "))):'';
	$location = isset($_GET['location'])?escapeString(strtoupper(trim($_GET['location']," "))):'';
	$waybill = isset($_GET['waybill'])?escapeString(strtoupper(trim($_GET['waybill']," "))):'';
	$docdatefrom = isset($_GET['docdatefrom'])?escapeString(strtoupper(trim($_GET['docdatefrom']," "))):'';
	$docdateto = isset($_GET['docdateto'])?escapeString(strtoupper(trim($_GET['docdateto']," "))):'';
	if($docdatefrom!=''){
        $docdatefrom = date('Y-m-d', strtotime($docdatefrom));
    }
    if($docdateto!=''){
        $docdateto = date('Y-m-d', strtotime($docdateto));
    }
	/** FILTER VARS - END **/

	if($qtype != '' && $query != ''){
	  array_push($filter, "$qtype like '%$query%'");
	}
	if($status!=''){
      array_push($filter,"txn_waybill_movement.status='$status'");
    }
    if($movementtype!=''&&$movementtype!='NULL'){
    	array_push($filter, "txn_waybill_movement.movement_type_id='$movementtype'");
    }
    if($location!=''&&$location!='NULL'){
    	array_push($filter, "txn_waybill_movement.location_id='$location'");
    }
    if($waybill!=''){
    	array_push($filter, "txn_waybill_movement.waybill_movement_number in (select waybill_movement_number from txn_waybill_movement_waybill where waybill_number='$waybill')");
    }

    if($docdatefrom!=''&&$docdateto!=''){
        array_push($filter,"date(txn_waybill_movement.document_date) between '$docdatefrom' and '$docdateto'");
    }
    else if($docdatefrom==''&&$docdateto!=''){
        array_push($filter,"date(txn_waybill_movement.document_date) <= '$docdateto'");
    }
    else if($docdatefrom!=''&&$docdateto==''){
         array_push($filter,"date(txn_waybill_movement.document_date) >= '$docdatefrom'");
    }

    $condition = '';
    if(count($filter)>0){
    	$condition = ' where '.implode(" and ", $filter);
    }
    $customqry = $customqry.$condition." group by txn_waybill_movement.waybill_movement_number";
    //echo $customqry;

	// Get total count of records
	$sql = "$customqry";
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
			$sortSql
			$limitSql";

			//echo $sql;

		

			
	$results = mysql_query($sql);
	if(!$results){
		echo $sql;
	}
	$line = 1;
	while ($obj = mysql_fetch_object($results)) {
		$createddate = dateFormat($obj->created_date,'m/d/Y h:i:s A');
		$docdate = dateFormat($obj->document_date,'m/d/Y');
		$data['rows'][] = array(
									'id' => $obj->id,
									'cell' => array( 
													 $obj->waybill_movement_number,
													 $obj->status,
													 $obj->movementtype,
													 utfEncode($obj->location),
													 $obj->waybills,
													 $docdate,
													 utfEncode($obj->createdby),
													 $createddate,
													 $obj->id

													),
									'rowAttr'=>array(
													   'rowid'=>$obj->id,
													   'waybillmovementnumber'=>$obj->waybill_movement_number,
													   "class"=>'waybillmovementsearchrow pointer'
													)
								);
		$line++;
	}
	echo json_encode($data);
?>