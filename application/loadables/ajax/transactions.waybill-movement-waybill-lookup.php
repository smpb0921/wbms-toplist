<?php
	include('../../../config/connection.php');
	include('../../../config/functions.php');


	//$origin = isset($_GET['origin'])?strtoupper($_GET['origin']):'';
	//$destination = isset($_GET['destination'])?strtoupper($_GET['destination']):'';
	//$mode = isset($_GET['mode'])?strtoupper($_GET['mode']):'';
	//$validwaybillstatus = getInfo("company_information","load_plan_waybill_status","where id=1");
	$wbmnumber = isset($_GET['wbmnumber'])?strtoupper($_GET['wbmnumber']):'';
	$wbmmovementtypeid = '';


	$rs = query("select * from txn_waybill_movement where waybill_movement_number='$wbmnumber'");
	while($obj=fetch($rs)){
		$wbmmovementtypeid = $obj->movement_type_id;
	}


	

	$page = 1;	// The current page
	$sortname = '';	// Sort column
	$sortorder = '';	// Sort order
	$qtype = '';	// Search column
	$query = '';	// Search string
	$rp = 10;

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
	
	if($qtype=='document_date'){
		$searchSql = ($qtype != '' && $query != '') ? " and $qtype='".dateString($query)."'" : '';
	}
	else{
		$searchSql = ($qtype != '' && $query != '') ? " and $qtype like '%$query%'" : '';
	}
	

	$customqry = "
					select txn_waybill.id,
					       txn_waybill.status,
					       txn_waybill.waybill_number,
						   txn_waybill.waybill_type,
						   txn_waybill.status,
				           txn_waybill.document_date,
				           txn_waybill.delivery_date,
				           txn_waybill.consignee_account_name,
				           txn_waybill.shipper_account_name,
				           txn_waybill.total_amount,
				           origintbl.description as origin,
				           destinationtbl.description as destination,
				           mode_of_transport.description as modeoftransport
					from txn_waybill
					left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id
				    left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id
				    left join mode_of_transport on mode_of_transport.id=txn_waybill.package_mode_of_transport
					where txn_waybill.status in (select source_movement 
					                             from movement_type_source 
					                             where movement_type_id='$wbmmovementtypeid') and
					      txn_waybill.waybill_number not in (select waybill_number 
					                                         from txn_waybill_movement_waybill
					                                         where waybill_movement_number='$wbmnumber')

				 ";

    
	// Get total count of records
	$sql = "$customqry $searchSql";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$total = mysql_num_rows($result);

	//echo 'asdsadasd '.$sql;

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
	if(!$results){
		echo $sql;
	}
	$line = 1;


	

	while ($obj = mysql_fetch_object($results)) {
		$id = $obj->id;
		$status = utfEncode($obj->status);
		$waybilltype = utfEncode($obj->waybill_type);
		$wbnumber = utfEncode($obj->waybill_number);
		$docdate = dateFormat($obj->document_date,'m/d/Y');
		$deldate = dateFormat($obj->delivery_date,'m/d/Y');
		$consignee = utfEncode($obj->consignee_account_name);
		$shipper = utfEncode($obj->shipper_account_name);
		$origin = utfEncode($obj->origin);
		$destination = utfEncode($obj->destination);
		$modeoftransport = utfEncode($obj->modeoftransport);
		$chargeamount = $obj->total_amount;



		
		$rowcheckbox = "<input type='checkbox' class='wbmwaybilllookup-checkbox valignmiddle' waybillnumber='$wbnumber'>";
		


		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													"$rowcheckbox",
													 $wbnumber, 
													 $docdate,
													 $origin,
													 $destination,
													 $shipper,
													 $consignee
													 
													 
													),
									'rowAttr'=>array(
													   'rowid'=>$id,
													   'rowwaybill'=>$wbnumber
													)
								);
		$line++;
	}
	echo json_encode($data);
?>