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
				    select txn_booking.id,
				           txn_booking.booking_number,
				           txn_booking.pickup_date,
				           txn_booking.status,
				           txn_booking.shipper_pickup_city,
				           txn_booking.shipper_pickup_state_province,
				           txn_booking.driver,
				           shipper.account_name as shipper,
				           consignee.account_name as consignee,
				           origintbl.description as origin,
				           destinationtbl.description as destination,
				           concat(first_name,' ',last_name) as created_by,
				           txn_booking.created_date,
				           booking_type.description as bookingtype
				    from txn_booking
				    left join booking_type on booking_type.id=txn_booking.booking_type_id
				    left join shipper on shipper.id=txn_booking.shipper_id
				    left join consignee on consignee.id=txn_booking.consignee_id
				    left join origin_destination_port as origintbl on origintbl.id=txn_booking.origin_id
				    left join origin_destination_port as destinationtbl on destinationtbl.id=txn_booking.destination_id
				    left join user on user.id=txn_booking.created_by
				";

	/****** FILTER VARS ****/
	$filter = array();
	if($qtype != '' && $query != ''){
		array_push($filter,"$qtype like '%$query%'");
    } 
	$bookingtype = isset($_GET['bookingtype'])?escapeString(strtoupper(trim($_GET['bookingtype']))):'';
	$status = isset($_GET['status'])?escapeString(strtoupper(trim($_GET['status']," "))):'';
	$origin = isset($_GET['origin'])?escapeString(strtoupper(trim($_GET['origin']," "))):'';
	$destination = isset($_GET['destination'])?escapeString(strtoupper(trim($_GET['destination']," "))):'';
	$shipper = isset($_GET['shipper'])?escapeString(strtoupper(trim($_GET['shipper']," "))):'';
	$consignee = isset($_GET['consignee'])?escapeString(strtoupper(trim($_GET['consignee']," "))):'';
	$pickupcity = isset($_GET['pickupcity'])?escapeString(strtoupper(trim($_GET['pickupcity']," "))):'';
	$pickupregion = isset($_GET['pickupregion'])?escapeString(strtoupper(trim($_GET['pickupregion']," "))):'';
	$pickupdatefrom = isset($_GET['pickupdatefrom'])?escapeString(strtoupper(trim($_GET['pickupdatefrom']," "))):'';
	$pickupdateto = isset($_GET['pickupdateto'])?escapeString(strtoupper(trim($_GET['pickupdateto']," "))):'';
	if($pickupdatefrom!=''){
        $pickupdatefrom = date('Y-m-d', strtotime($pickupdatefrom));
    }
    if($pickupdateto!=''){
        $pickupdateto = date('Y-m-d', strtotime($pickupdateto));
    }
	/** FILTER VARS - END **/


	if($status!=''){
      array_push($filter,"txn_booking.status='$status'");
    }
    if($bookingtype!=''&&$bookingtype!='NULL'){
    	array_push($filter, "txn_booking.booking_type_id='$bookingtype'");
    }
    if($origin!=''&&$origin!='NULL'){
    	array_push($filter, "txn_booking.origin_id='$origin'");
    }
    if($destination!=''&&$destination!='NULL'){
    	array_push($filter, "txn_booking.destination_id='$destination'");
    }
    if($shipper!=''&&$shipper!='NULL'){
    	array_push($filter, "txn_booking.shipper_id='$shipper'");
    }
    if($consignee!=''&&$consignee!='NULL'){
    	array_push($filter, "txn_booking.consignee_id='$consignee'");
    }
    if($pickupcity!=''&&$pickupcity!='NULL'){
    	array_push($filter, "upper(txn_booking.shipper_pickup_city) regexp '$pickupcity'");
    }
    if($pickupregion!=''&&$pickupregion!='NULL'){
    	array_push($filter, "upper(txn_booking.shipper_pickup_state_province) regexp '$pickupregion'");
    }

    if($pickupdatefrom!=''&&$pickupdateto!=''){
        array_push($filter,"date(txn_booking.pickup_date) between '$pickupdatefrom' and '$pickupdateto'");
    }
    else if($pickupdatefrom==''&&$pickupdateto!=''){
        array_push($filter,"date(txn_booking.pickup_date) <= '$pickupdateto'");
    }
    else if($pickupdatefrom!=''&&$pickupdateto==''){
         array_push($filter,"date(txn_booking.pickup_date) >= '$pickupdatefrom'");
    }

    $condition = '';
    if(count($filter)>0){
    	$condition = ' where '.implode(" and ", $filter);
    }
    $customqry = $customqry.$condition;


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
		$pickupdate = dateFormat($obj->pickup_date,'m/d/Y');
		$data['rows'][] = array(
									'id' => $obj->id,
									'cell' => array( 
													 $obj->booking_number,
													 utfEncode($obj->bookingtype),
													 $obj->status,
													 utfEncode($obj->origin),
													 utfEncode($obj->destination),
													 utfEncode($obj->shipper),
													 utfEncode($obj->driver),
													 $pickupdate,
													 utfEncode($obj->shipper_pickup_city),
													 utfEncode($obj->shipper_pickup_state_province),
													 utfEncode($obj->created_by),
													 $createddate,
													 $obj->id

													),
									'rowAttr'=>array(
													   'rowid'=>$obj->id,
													   'bookingnumber'=>$obj->booking_number,
													   "class"=>'bookingsearchrow pointer'
													)
								);
		$line++;
	}
	echo json_encode($data);
?>