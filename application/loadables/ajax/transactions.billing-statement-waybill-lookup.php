<?php
	include('../../../config/connection.php');
	include('../../../config/functions.php');


	//$origin = isset($_GET['origin'])?strtoupper($_GET['origin']):'';
	//$destination = isset($_GET['destination'])?strtoupper($_GET['destination']):'';
	//$mode = isset($_GET['mode'])?strtoupper($_GET['mode']):'';
	//$validwaybillstatus = getInfo("company_information","load_plan_waybill_status","where id=1");
	$shipperid = isset($_GET['shipperid'])?strtoupper($_GET['shipperid']):'';
	$agentid = isset($_GET['agentid'])?strtoupper($_GET['agentid']):'';
	$consigneeid = isset($_GET['consigneeid'])?strtoupper($_GET['consigneeid']):'';
	$shipmenttypeid = isset($_GET['shipmenttypeid'])?strtoupper($_GET['shipmenttypeid']):'';
	$billedto = isset($_GET['billedto'])?strtoupper($_GET['billedto']):'';

	$accountstr = '';
	if($billedto=='SHIPPER'){
		$accountstr =  "and txn_waybill.shipper_id='$shipperid'";
	}
	else if($billedto=='AGENT'){
		$accountstr =  "and txn_waybill.agent_id='$agentid'";
	} else if($billedto=='CONSIGNEE'){
		$accountstr =  "and txn_waybill.consignee_id='$consigneeid'";
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
	$sortSql = '';//"order by $sortname $sortorder";
	$searchSql = ($qtype != '' && $query != '') ? " and $qtype like '%$query%'" : '';

	$customqry = "	 select txn_waybill.id,
							txn_waybill.waybill_number,
							txn_waybill.waybill_type,
							txn_waybill.status,
							txn_waybill.mawbl_bl,
							date_format(txn_waybill.document_date,'%m/%d/%Y') as document_date,
							date_format(txn_waybill.delivery_date,'%m/%d/%Y') as delivery_date,
							shipper.account_name as shipper,
							txn_waybill.consignee_account_name,
							txn_waybill.total_amount,
							origintbl.description as origin,
							destinationtbl.description as destination,
							mode_of_transport.description as modeoftransport,
							agent.company_name as agent
						from txn_waybill
						left join shipper on shipper.id=txn_waybill.shipper_id
						left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id
						left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id
						left join mode_of_transport on mode_of_transport.id=txn_waybill.package_mode_of_transport
						left join txn_billing_waybill on txn_billing_waybill.waybill_number=txn_waybill.waybill_number 
						left join txn_billing on txn_billing.billing_number=txn_billing_waybill.billing_number
						left join agent on agent.id=txn_waybill.agent_id
						where  (txn_waybill.status='DELIVERED' or shipper.non_pod_flag=1) and 
								txn_waybill.status!='VOID' and
								txn_waybill.status!='LOGGED' and
								txn_waybill.billed_flag=0 and 
								txn_waybill.shipment_type_id='$shipmenttypeid' and
								(txn_billing.status='VOID' or
								txn_billing_waybill.flag is null or txn_billing_waybill.flag=0)
								$accountstr
				 ";


	// Get total count of records
	$sql = "$customqry $searchSql";
	$result = mysql_query($sql);
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
		$docdate = $obj->document_date;
		$deldate = $obj->delivery_date;
		$consignee = utfEncode($obj->consignee_account_name);
		$origin = utfEncode($obj->origin);
		$destination = utfEncode($obj->destination);
		$modeoftransport = utfEncode($obj->modeoftransport);
		$mawbl = utfEncode($obj->mawbl_bl);
		$chargeamount = $obj->total_amount;

		$shipper = utfEncode($obj->shipper);
		$agent = utfEncode($obj->agent);



		
		$rowcheckbox = "<input type='checkbox' class='blswaybilllookup-checkbox valignmiddle' waybillnumber='$wbnumber'>";
		


		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													"$rowcheckbox",
													 $wbnumber, 
													 $mawbl,
													 $docdate,
													 $deldate,
													 $status,
													 $waybilltype,
													 $shipper,
													 $consignee,
													 $agent,
													 $origin,
													 $destination,
													 $modeoftransport
													 
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