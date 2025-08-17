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
				           shipper.account_name as shipper,
				           consignee.account_name as consignee,
				           origintbl.description as origin,
				           destinationtbl.description as destination,
				           destinationroutetbl.description as destinationroute,
				           concat(first_name,' ',last_name) as created_by,
				           txn_waybill.created_date
				    from txn_waybill
				    left join shipper on shipper.id=txn_waybill.shipper_id
				    left join consignee on consignee.id=txn_waybill.consignee_id
				    left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id
				    left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id
				    left join destination_route as destinationroutetbl on destinationroutetbl.id=txn_waybill.destination_route_id
				    left join user on user.id=txn_waybill.created_by
				";


	/****** FILTER VARS ****/
	$filter = array();
	$status = isset($_GET['status'])?escapeString(strtoupper($_GET['status'])):'';
	$mawbl = isset($_GET['mawbl'])?escapeString(strtoupper(trim($_GET['mawbl']," "))):'';
	$billingnumber = isset($_GET['billingnumber'])?escapeString(strtoupper(trim($_GET['billingnumber']," "))):'';
	$bookingnumber = isset($_GET['bookingnumber'])?escapeString(strtoupper(trim($_GET['bookingnumber']," "))):'';
	$manifestnumber = isset($_GET['manifestnumber'])?escapeString(strtoupper(trim($_GET['manifestnumber']," "))):'';
	$invoicenumber = isset($_GET['invoicenumber'])?escapeString(strtoupper(trim($_GET['invoicenumber']," "))):'';
	$origin = isset($_GET['origin'])?escapeString(strtoupper(trim($_GET['origin']," "))):'';
	$destination = isset($_GET['destination'])?escapeString(strtoupper(trim($_GET['destination']," "))):'';
	$destinationroute = isset($_GET['destinationroute'])?escapeString(strtoupper(trim($_GET['destinationroute']," "))):'';
	$shipper = isset($_GET['shipper'])?escapeString(strtoupper(trim($_GET['shipper']," "))):'';
	$consignee = isset($_GET['consignee'])?escapeString(strtoupper(trim($_GET['consignee']," "))):'';
	$pickupcity = isset($_GET['pickupcity'])?escapeString(strtoupper(trim($_GET['pickupcity']," "))):'';
	$pickupregion = isset($_GET['pickupregion'])?escapeString(strtoupper(trim($_GET['pickupregion']," "))):'';
	$pickupdatefrom = isset($_GET['pickupdatefrom'])?escapeString(strtoupper(trim($_GET['pickupdatefrom']," "))):'';
	$pickupdateto = isset($_GET['pickupdateto'])?escapeString(strtoupper(trim($_GET['pickupdateto']," "))):'';
	$trackingnumber = isset($_GET['trackingnumber'])?escapeString(strtoupper(trim($_GET['trackingnumber']," "))):'';
	$reference = isset($_GET['reference'])?escapeString(strtoupper(trim($_GET['reference']," "))):'';
	if($pickupdatefrom!=''){
        $pickupdatefrom = date('Y-m-d', strtotime($pickupdatefrom));
    }
    if($pickupdateto!=''){
        $pickupdateto = date('Y-m-d', strtotime($pickupdateto));
    }
	/** FILTER VARS - END **/

	if($qtype != '' && $query != ''){
	   array_push($filter,"$qtype like '%$query%'");
	}
	if($status!=''&&$status!='NULL'){
      array_push($filter,"txn_waybill.status='$status'");
	}
	if($billingnumber!=''){
		array_push($filter, "upper(txn_waybill.billing_reference)='$billingnumber'");
	}
	if($mawbl!=''){
		array_push($filter, "upper(txn_waybill.mawbl_bl)='$mawbl'");
	}
	if($trackingnumber!=''){
		array_push($filter, "upper(txn_waybill.waybill_number)='$trackingnumber'");
	}
	if($reference!=''){
		array_push($filter, "upper(txn_waybill.reference)='$reference'");
	}
    if($bookingnumber!=''){
    	array_push($filter, "upper(txn_waybill.booking_number) regexp '$bookingnumber'");
    }
    if($manifestnumber!=''){
    	array_push($filter, "upper(txn_waybill.manifest_number) regexp '$manifestnumber'");
    }
    if($invoicenumber!=''){
    	array_push($filter, "upper(txn_waybill.invoice_number) regexp '$invoicenumber'");
    }
    if($origin!=''&&$origin!='NULL'){
    	array_push($filter, "txn_waybill.origin_id='$origin'");
    }
    if($destination!=''&&$destination!='NULL'){
    	array_push($filter, "txn_waybill.destination_id='$destination'");
    }
    if($destinationroute!=''&&$destinationroute!='NULL'){
    	array_push($filter, "txn_waybill.destination_route_id='$destinationroute'");
    }
    if($shipper!=''&&$shipper!='NULL'){
    	array_push($filter, "txn_waybill.shipper_id='$shipper'");
    }
    if($consignee!=''&&$consignee!='NULL'){
    	array_push($filter, "txn_waybill.consignee_id='$consignee'");
    }
    if($pickupcity!=''&&$pickupcity!='NULL'){
    	array_push($filter, "upper(txn_waybill.pickup_city) regexp '$pickupcity'");
    }
    if($pickupregion!=''&&$pickupregion!='NULL'){
    	array_push($filter, "upper(txn_waybill.pickup_state_province) regexp '$pickupregion'");
    }

    if($pickupdatefrom!=''&&$pickupdateto!=''){
        array_push($filter,"date(txn_waybill.pickup_date) between '$pickupdatefrom' and '$pickupdateto'");
    }
    else if($pickupdatefrom==''&&$pickupdateto!=''){
        array_push($filter,"date(txn_waybill.pickup_date) <= '$pickupdateto'");
    }
    else if($pickupdatefrom!=''&&$pickupdateto==''){
         array_push($filter,"date(txn_waybill.pickup_date) >= '$pickupdatefrom'");
    }

    $condition = '';
    if(count($filter)>0){
    	$condition = ' where '.implode(" and ", $filter);
    }
	$customqry = $customqry.$condition;
	$customqry1 = $customqry1.$condition;


	// Get total count of records
	$total = 0;
	$sql = "$customqry1";
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
		$pickupdate = dateFormat($obj->pickup_date,'m/d/Y');
		$data['rows'][] = array(
									'id' => $obj->id,
									'cell' => array( 
													 $obj->waybill_number,
													 utfEncode($obj->mawbl_bl),
													 utfEncode($obj->reference),
													 $obj->status,
													 $obj->booking_number,
													 utfEncode($obj->origin),
													 utfEncode($obj->destination),
													 utfEncode($obj->destinationroute),
													 utfEncode($obj->shipper),
													 utfEncode($obj->consignee),
													 $pickupdate,
													 utfEncode($obj->pickup_city),
													 utfEncode($obj->pickup_state_province),
													 utfEncode($obj->package_chargeable_weight),
													 utfEncode($obj->package_actual_weight),
													 $obj->manifest_number,
													 $obj->invoice_number,
													 utfEncode($obj->created_by),
													 $createddate,
													 $obj->id

													),
									'rowAttr'=>array(
													   'rowid'=>$obj->id,
													   'waybillnumber'=>$obj->waybill_number,
													   "class"=>'waybillsearchrow pointer'
													)
								);
		$line++;
	}
	echo json_encode($data);
?>