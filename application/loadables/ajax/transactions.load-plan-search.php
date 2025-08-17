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
	//$searchSql = ($qtype != '' && $query != '') ? "where $qtype like '%$query%'" : '';


	$customqry = "
				    select txn_load_plan.id,
				           txn_load_plan.load_plan_number,
				           txn_load_plan.status,
				           txn_load_plan.manifest_number,
				           txn_load_plan.document_date,
				           txn_load_plan.created_date,
				           concat(first_name,' ',last_name) as createdby,
				           origintbl.description as origin,
				           mode_of_transport.description as mode_of_transport,
				           agent.company_name as agent,
				           carrier.description as carrier,
				           vehicle_type.description as vehicle_type,
				           location.code as loccode,
				           location.description as location,
				           txn_load_plan_waybill.waybill_number,
				           txn_load_plan.mawbl_bl,
				           group_concat(distinct destinationtbl.description order by destinationtbl.description asc separator ', ') as destinationfiltered,
				           count(distinct destinationtbl.description) as destinationcount
				    from txn_load_plan 
				    left join txn_load_plan_destination on txn_load_plan_destination.load_plan_number=txn_load_plan.load_plan_number
				    left join origin_destination_port as origintbl on origintbl.id=txn_load_plan.origin_id
				    left join origin_destination_port as destinationtbl on destinationtbl.id=txn_load_plan_destination.origin_destination_port_id
				    left join mode_of_transport on mode_of_transport.id=txn_load_plan.mode_of_transport_id
				    left join agent on agent.id=txn_load_plan.agent_id
				    left join location on location.id=txn_load_plan.location_id
				    left join carrier on carrier.id=txn_load_plan.carrier_id
				    left join vehicle_type on vehicle_type.id=txn_load_plan.vehicle_type_id
				    left join txn_load_plan_waybill on txn_load_plan_waybill.load_plan_number=txn_load_plan.load_plan_number
				    left join user on user.id=txn_load_plan.created_by
				    
				";

				/* temptbl.destination

					left join (select load_plan_number, 
				                      group_concat(distinct origin_destination_port.description order by origin_destination_port.description asc separator ', ') as destination
				               from txn_load_plan_destination 
				               left join origin_destination_port on origin_destination_port.id=origin_destination_port_id
				               group by load_plan_number
				               ) as temptbl 
				    on temptbl.load_plan_number=txn_load_plan.load_plan_number*/

	/****** FILTER VARS ****/
	$having = array();
	$filter = array();
	$status = isset($_GET['status'])?escapeString(strtoupper($_GET['status'])):'';
	$location = isset($_GET['location'])?escapeString(strtoupper($_GET['location'])):'';
	$manifestnumber = isset($_GET['manifestnumber'])?escapeString(strtoupper(trim($_GET['manifestnumber']," "))):'';
	$origin = isset($_GET['origin'])?escapeString(strtoupper(trim($_GET['origin']," "))):'';
	$destination = isset($_GET['destination'])?escapeString(strtoupper(trim($_GET['destination']," "))):'';
	$mode = isset($_GET['mode'])?escapeString(strtoupper(trim($_GET['mode']," "))):'';
	$agent = isset($_GET['agent'])?escapeString(strtoupper(trim($_GET['agent']," "))):'';
	$carrier = isset($_GET['carrier'])?escapeString(strtoupper(trim($_GET['carrier']," "))):'';
	$vehicletype = isset($_GET['vehicletype'])?escapeString(strtoupper(trim($_GET['vehicletype']," "))):'';
	$waybillnumber = isset($_GET['waybillnumber'])?escapeString(strtoupper(trim($_GET['waybillnumber']," "))):'';
	$mawbl = isset($_GET['mawbl'])?escapeString(strtoupper(trim($_GET['mawbl']))):'';
	$docdatefrom = isset($_GET['docdatefrom'])?escapeString(strtoupper(trim($_GET['docdatefrom']," "))):'';
	$docdateto = isset($_GET['docdateto'])?escapeString(strtoupper(trim($_GET['docdateto']," "))):'';
	if($docdatefrom!=''){
        $docdatefrom = date('Y-m-d', strtotime($docdatefrom));
    }
    if($docdateto!=''){
        $docdateto = date('Y-m-d', strtotime($docdateto));
    }
    
    $userdefaultloc = getInfo("user","location_id","where id='".USERID."'");
    $userotherloc = getUserAssignedLocations(USERID);
	/** FILTER VARS - END **/

	array_push($filter, "(location.id='$userdefaultloc' or location.id in $userotherloc or ".USERID."=1)");

	if($qtype != '' && $query != ''){
	  array_push($filter, "$qtype like '%$query%'");
	}
	if($status!=''&&$status!='NULL'){
      array_push($filter,"txn_load_plan.status='$status'");
    }
    if($manifestnumber!=''&&$manifestnumber!='NULL'){
    	array_push($filter, "upper(txn_load_plan.load_plan_number) regexp '$manifestnumber'");
    }
    if($waybillnumber!=''&&$waybillnumber!='NULL'){
    	array_push($filter, "upper(txn_load_plan_waybill.waybill_number) regexp '$waybillnumber'");
    }
    if($mawbl!=''&&$mawbl!='NULL'){
    	array_push($filter, "upper(txn_load_plan.mawbl_bl) regexp '$mawbl'");
    }
    if($location!=''&&$location!='NULL'){
    	array_push($filter, "txn_load_plan.location_id='$location'");
    }
    if($origin!=''&&$origin!='NULL'){
    	array_push($filter, "txn_load_plan.origin_id='$origin'");
    }
    if($destination!=''&&$destination!='NULL'){
    	/*array_push($filter, "txn_load_plan_destination.origin_destination_port_id in ($destination)");
    	$destarray = explode(',', $destination);
    	$destlen = count($destarray);
    	array_push($having, "count(distinct destinationtbl.description)>=$destlen");*/

    	array_push($filter, "txn_load_plan.load_plan_number in (select distinct load_plan_number 
    		                                                    from  txn_load_plan_destination
    		                                                    where  origin_destination_port_id in 
    		                                                           ($destination)
    		                                                    )
    		       ");
    	/*$destarray = explode(',', $destination);
    	$destlen = count($destarray);
    	for($i=0;$i<$destlen;$i++){
    		array_push($filter, "in('".$destarray[$i]."',temptbl.destinationidstr)>0");
    	}*/



    	//array_push($filter, "txn_load_plan_destination.origin_destination_port_id in ($destination)");
    }
    if($mode!=''&&$mode!='NULL'){
    	array_push($filter, "txn_load_plan.mode_of_transport_id='$mode'");
    }
    if($agent!=''&&$agent!='NULL'){
    	array_push($filter, "txn_load_plan.agent_id='$agent'");
    }
    if($vehicletype!=''&&$vehicletype!='NULL'){
    	array_push($filter, "txn_load_plan.vehicle_type_id='$vehicletype'");
    }
    if($carrier!=''&&$carrier!='NULL'){
    	array_push($filter, "txn_load_plan.carrier_id='$carrier'");
    }

    if($docdatefrom!=''&&$docdateto!=''){
        array_push($filter,"date(txn_load_plan.document_date) between '$docdatefrom' and '$docdateto'");
    }
    else if($docdatefrom==''&&$docdateto!=''){
        array_push($filter,"date(txn_load_plan.document_date) <= '$docdateto'");
    }
    else if($docdatefrom!=''&&$docdateto==''){
         array_push($filter,"date(txn_load_plan.document_date) >= '$docdatefrom'");

    }

    $condition = '';
    if(count($filter)>0){
    	$condition = ' where '.implode(" and ", $filter);
    }

    $condition2 = '';
    if(count($having)>0){
    	$condition2 = ' having '.implode(" and ", $having);
    }
    $customqry = $customqry.$condition." group by txn_load_plan.load_plan_number asc".$condition2;
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

		
 //echo $sql;
			
	$results = mysql_query($sql);
	if(!$results){
		echo $sql;
	}
	$line = 1;
	while ($obj = mysql_fetch_object($results)) {
		$createddate = dateFormat($obj->created_date,'m/d/Y h:i:s A');
		$docdate = dateFormat($obj->document_date,'m/d/Y');
		//$destination = getLoadPlanDestination($obj->load_plan_number);
		$data['rows'][] = array(
									'id' => $obj->id,
									'cell' => array( 
													 utfEncode($obj->load_plan_number),
													 utfEncode($obj->status),
													 utfEncode($obj->manifest_number),
													 '['.utfEncode($obj->loccode).'] '.utfEncode($obj->location),
													 utfEncode($obj->origin),
													 utfEncode($obj->destinationfiltered),
													 utfEncode($obj->carrier),
													 utfEncode($obj->mode_of_transport),
													 utfEncode($obj->agent),
													 utfEncode($obj->vehicle_type),
													 $docdate,
													 utfEncode($obj->mawbl_bl),
													 utfEncode($obj->createdby),
													 $createddate,
													 $obj->id

													),
									'rowAttr'=>array(
													   'rowid'=>$obj->id,
													   'loadplannumber'=>$obj->load_plan_number,
													   "class"=>'loadplansearchrow pointer'
													)
								);
		$line++;
	}
	echo json_encode($data);
?>