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


	$customqry2 = "
				    select count(distinct txn_manifest.manifest_number) as rowcount
				    from txn_manifest
					left join txn_load_plan on txn_load_plan.load_plan_number=txn_manifest.load_plan_number
				    left join txn_load_plan_destination on txn_load_plan_destination.load_plan_number=txn_load_plan.load_plan_number
				    left join origin_destination_port as origintbl on origintbl.id=txn_load_plan.origin_id
				    left join origin_destination_port as originmft on originmft.id=txn_manifest.origin_id
				    left join origin_destination_port as destinationtbl on destinationtbl.id=txn_load_plan_destination.origin_destination_port_id
				    left join mode_of_transport on mode_of_transport.id=txn_load_plan.mode_of_transport_id
				    left join mode_of_transport as modeoftransmft on modeoftransmft.id=txn_manifest.mode_of_transport_id
				    left join agent on agent.id=txn_load_plan.agent_id
				    left join agent as agentmft on agentmft.id=txn_manifest.agent_id
				    left join carrier on carrier.id=txn_manifest.trucker_name
				    left join vehicle_type on vehicle_type.id=txn_manifest.truck_type
				    left join txn_manifest_waybill on txn_manifest_waybill.manifest_number=txn_manifest.manifest_number
				    left join txn_waybill on txn_waybill.waybill_number=txn_manifest_waybill.waybill_number
				    left join origin_destination_port as destinationmft on destinationmft.id=txn_waybill.destination_id
				    left join txn_manifest_waybill_package_code on txn_manifest_waybill_package_code.manifest_number=txn_manifest.manifest_number and txn_manifest_waybill_package_code.waybill_number=txn_manifest_waybill.waybill_number
				    left join user on user.id=txn_manifest.created_by
				    
				";


	$customqry = "
				    select txn_manifest.id,
				           txn_manifest.manifest_number,
				           txn_manifest.status,
				           txn_manifest.document_date,
				           ifnull(txn_manifest.load_plan_number,'N/A') as load_plan_number,
				           txn_manifest.created_date,
				           concat(first_name,' ',last_name) as created_by,
				           case 
				                when txn_manifest.load_plan_number is null then originmft.description
				                else origintbl.description
				           end as origin,
				           case 
				                when txn_manifest.load_plan_number is null then modeoftransmft.description
				                else mode_of_transport.description
				           end as mode_of_transport,
				           case 
				                when txn_manifest.load_plan_number is null then agentmft.company_name
				                else agent.company_name
				           end as agent,
				           carrier.description as carrier,
				           vehicle_type.description as vehicle_type,
				           txn_manifest_waybill.waybill_number,
				           txn_manifest_waybill_package_code.package_code,
				           case 
				                when txn_manifest.load_plan_number is null then txn_manifest.mawbl
				                else txn_load_plan.mawbl_bl
				           end as mawbl_bl,
				           case 
				                when txn_manifest.load_plan_number is null then group_concat(distinct destinationmft.description separator ', ')
				                else group_concat(distinct destinationtbl.description separator ', ')
				           end as destinationfiltered,
				           concat(user.first_name,' ',user.last_name) as createdby,
				           count(distinct destinationtbl.description) as destinationcount
				    from txn_manifest
				    left join txn_load_plan on txn_load_plan.load_plan_number=txn_manifest.load_plan_number
				    left join txn_load_plan_destination on txn_load_plan_destination.load_plan_number=txn_load_plan.load_plan_number
				    left join origin_destination_port as origintbl on origintbl.id=txn_load_plan.origin_id
				    left join origin_destination_port as originmft on originmft.id=txn_manifest.origin_id
				    left join origin_destination_port as destinationtbl on destinationtbl.id=txn_load_plan_destination.origin_destination_port_id
				    left join mode_of_transport on mode_of_transport.id=txn_load_plan.mode_of_transport_id
				    left join mode_of_transport as modeoftransmft on modeoftransmft.id=txn_manifest.mode_of_transport_id
				    left join agent on agent.id=txn_load_plan.agent_id
				    left join agent as agentmft on agentmft.id=txn_manifest.agent_id
				    left join carrier on carrier.id=txn_manifest.trucker_name
				    left join vehicle_type on vehicle_type.id=txn_manifest.truck_type
				    left join txn_manifest_waybill on txn_manifest_waybill.manifest_number=txn_manifest.manifest_number
				    left join txn_waybill on txn_waybill.waybill_number=txn_manifest_waybill.waybill_number
				    left join origin_destination_port as destinationmft on destinationmft.id=txn_waybill.destination_id
				    left join txn_manifest_waybill_package_code on txn_manifest_waybill_package_code.manifest_number=txn_manifest.manifest_number and txn_manifest_waybill_package_code.waybill_number=txn_manifest_waybill.waybill_number
				    left join user on user.id=txn_manifest.created_by
				    
				";

				/* temptbl.destination,

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
	$loadplannumber = isset($_GET['loadplannumber'])?escapeString(strtoupper(trim($_GET['loadplannumber']," "))):'';
	$origin = isset($_GET['origin'])?escapeString(strtoupper(trim($_GET['origin']," "))):'';
	$destination = isset($_GET['destination'])?escapeString(strtoupper(trim($_GET['destination']," "))):'';
	$mode = isset($_GET['mode'])?escapeString(strtoupper(trim($_GET['mode']," "))):'';
	$agent = isset($_GET['agent'])?escapeString(strtoupper(trim($_GET['agent']," "))):'';
	$carrier = isset($_GET['carrier'])?escapeString(strtoupper(trim($_GET['carrier']," "))):'';
	$vehicletype = isset($_GET['vehicletype'])?escapeString(strtoupper(trim($_GET['vehicletype']," "))):'';
	$waybillnumber = isset($_GET['waybillnumber'])?escapeString(strtoupper(trim($_GET['waybillnumber']," "))):'';
	$mawbl = isset($_GET['mawbl'])?escapeString(strtoupper(trim($_GET['mawbl']))):'';
	$packagecode = isset($_GET['packagecode'])?escapeString(strtoupper(trim($_GET['packagecode']," "))):'';
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
	if($status!=''&&$status!='NULL'){
      array_push($filter,"txn_manifest.status='$status'");
    }
    if($loadplannumber!=''&&$loadplannumber!='NULL'){
    	array_push($filter, "upper(txn_manifest.load_plan_number) regexp '$loadplannumber'");
    }
    if($waybillnumber!=''&&$waybillnumber!='NULL'){
    	array_push($filter, "upper(txn_manifest_waybill.waybill_number) regexp '$waybillnumber'");
    }
    if($mawbl!=''&&$mawbl!='NULL'){
    	array_push($filter, "upper(txn_load_plan.mawbl_bl) regexp '$mawbl'");
    }
    if($origin!=''&&$origin!='NULL'){
    	array_push($filter, "txn_load_plan.origin_id='$origin'");
    }
    if($destination!=''&&$destination!='NULL'){
    	array_push($filter, "txn_load_plan_destination.origin_destination_port_id in ($destination)");
    	$destarray = explode(',', $destination);
    	$destlen = count($destarray);
    	array_push($having, "count(distinct destinationtbl.description)>=$destlen");
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
    	array_push($filter, "txn_manifest.truck_type='$vehicletype'");
    }
    if($carrier!=''&&$carrier!='NULL'){
    	array_push($filter, "txn_manifest.trucker_name='$carrier'");
    }
    if($packagecode!=''&&$packagecode!='NULL'){
    	array_push($filter, "upper(txn_manifest_waybill_package_code.package_code) regexp '$packagecode'");
    }

    if($docdatefrom!=''&&$docdateto!=''){
        array_push($filter,"date(txn_manifest.document_date) between '$docdatefrom' and '$docdateto'");
    }
    else if($docdatefrom==''&&$docdateto!=''){
        array_push($filter,"date(txn_manifest.document_date) <= '$docdateto'");
    }
    else if($docdatefrom!=''&&$docdateto==''){
         array_push($filter,"date(txn_manifest.document_date) >= '$docdatefrom'");
    }

    $condition = '';
    if(count($filter)>0){
    	$condition = ' where '.implode(" and ", $filter);
    }

    $condition2 = '';
    if(count($having)>0){
    	$condition2 = ' having '.implode(" and ", $having);
    }
    $customqry = $customqry.$condition." group by txn_manifest.manifest_number asc".$condition2;

	$customqry2 = $customqry2.$condition;

    //echo $customqry2;

	// Get total count of records
	$sql = "$customqry2";
	$result = mysql_query($sql);
	$total = 0;
	while($obj=mysql_fetch_object($result)){
		$total = $obj->rowcount;
	}

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
													 utfEncode($obj->manifest_number),
													 utfEncode($obj->status),
													 utfEncode($obj->load_plan_number),
													 utfEncode($obj->origin),
													 utfEncode($obj->destinationfiltered),
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
													   'manifestnumber'=>$obj->manifest_number,
													   "class"=>'manifestsearchrow pointer'
													)
								);
		$line++;
	}
	echo json_encode($data);
?>