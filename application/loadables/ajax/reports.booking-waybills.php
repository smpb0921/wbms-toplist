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
	$sortSql = "order by txn_waybill.booking_number asc, $sortname $sortorder";
	

	//Search Condition
	$filter = array();
	$bookingnumber = '';
	//$bookingnumber = isset($_GET['bookingnumber'])?escapeString(strtoupper(trim($_GET['bookingnumber']))):'';
	//$bookingnumber = trim($_GET['bookingnumber'])!=''?explode($bookingnumber,','):'';
	//$bookingnumber = $bookingnumber!=''?implode($bookingnumber,"','"):'';
	//echo $_GET['bookingnumber'];
	if(isset($_GET['bookingnumber'])&&$_GET['bookingnumber']!=''){
		$bookingnumber = explode(',',$_GET['bookingnumber']);
		$bookingnumber = implode("','",$bookingnumber);
	}
	
	if($qtype != '' && $query != ''){
		 array_push($filter,"$qtype like '%$query%'");
	}
	//if($bookingnumber!=''){
    	array_push($filter, "upper(txn_waybill.booking_number) in ('".$bookingnumber."')");
    //}
	//$searchSql = '';
    //if(count($filter)>0){
    	$searchSql = ' where '.implode(" and ", $filter);
    //}

	$customqry1 = "
				    select count(*) as rowcount
				    from txn_waybill
				    left join shipper on shipper.id=txn_waybill.shipper_id
				    left join consignee on consignee.id=txn_waybill.consignee_id
				    left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id
				    left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id
				    left join destination_route as destinationroutetbl on destinationroutetbl.id=txn_waybill.destination_route_id
				    left join user on user.id=txn_waybill.created_by
				";

	$customqry = "
				 select txn_waybill.id,
						txn_waybill.waybill_number,
						txn_waybill.booking_number,
						txn_waybill.pickup_date,
						txn_waybill.status,
						txn_waybill.mawbl_bl,
						txn_waybill.pickup_city,
						txn_waybill.pickup_state_province,
						txn_waybill.manifest_number,
						txn_waybill.invoice_number,
						txn_waybill.package_chargeable_weight,
						txn_waybill.package_actual_weight,
						txn_waybill.reference,
						case 
							when txn_waybill.billed_flag=1 then 'BILLED'
							else 'UNBILLED'
						end as billingstatus,
						shipper.account_name as shipper,
						consignee.account_name as consignee,
						origintbl.description as origin,
						destinationtbl.description as destination,
						destinationroutetbl.description as destinationroute,
						concat(first_name,' ',last_name) as createdby,
						date_format(txn_waybill.created_date,'%m/%d/%Y %h:%i:%s %p') as created_date,
						date_format(txn_waybill.pickup_date,'%m/%d/%Y') as pickupdate
					from txn_waybill
					left join shipper on shipper.id=txn_waybill.shipper_id
					left join consignee on consignee.id=txn_waybill.consignee_id
					left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id
					left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id
					left join destination_route as destinationroutetbl on destinationroutetbl.id=txn_waybill.destination_route_id
					left join user on user.id=txn_waybill.created_by
				";


	// Get total count of records
	$total = 0;
	$sql = "$customqry1 $searchSql";
	$result = mysql_query($sql);
	while($obj=fetch($result)){
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
	$sql = "
			$customqry
			$searchSql
			$sortSql
			$limitSql";

			//echo $sql;

		

			
	$results = mysql_query($sql);
	if(!$results){
		echo $sql;
	}
	$line = 1;
	while ($obj = mysql_fetch_object($results)) {
	
		$data['rows'][] = array(
									'id' => $obj->id,
									'cell' => array( 
													 utfEncode($obj->id),
													 utfEncode($obj->booking_number),
													 utfEncode($obj->waybill_number),
													 utfEncode($obj->mawbl_bl),
													 utfEncode($obj->billingstatus),
													 utfEncode($obj->shipper),
													 utfEncode($obj->status),
													 utfEncode($obj->pickupdate),
													 utfEncode($obj->created_date),
													 utfEncode($obj->createdby)
													),
									'rowAttr'=>array(
													   'rowid'=>$obj->id,
													   "class"=>'bookingwaybillrow pointer'
													)
								);
		$line++;
	}
	echo json_encode($data);
?>