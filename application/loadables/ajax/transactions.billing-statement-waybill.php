<?php
	include('../../../config/connection.php');
	include('../../../config/functions.php');


	$reference = isset($_GET['reference'])?strtoupper($_GET['reference']):'';

	

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
	$searchSql = ($qtype != '' && $query != '') ? "and $qtype like '%$query%'" : '';

	$customqry = "
				    select txn_billing_waybill.id,
				           txn_billing_waybill.billing_number,
				           txn_billing_waybill.waybill_number,
				           txn_billing_waybill.created_date,
				           txn_billing_waybill.created_by,
				           txn_billing_waybill.amount,
				           txn_billing_waybill.chargeable_weight,
				           txn_billing_waybill.regular_charges,
				           txn_billing_waybill.other_charges_vatable,
				           txn_billing_waybill.other_charges_non_vatable,
				           txn_billing_waybill.vat,
				           (txn_billing_waybill.regular_charges+txn_billing_waybill.other_charges_vatable) as vatable_charges,
				           origintbl.description as origin,
				           destinationtbl.description as destination,
				           mode_of_transport.description as modeoftransport,
				           case 
				           		when txn_billing_waybill.flag=0 then 'REVISED'
				           		when txn_billing_waybill.flag=1 then 'ACTIVE'
				           		else 'N/A'
				           end as 'detailstatus',
				           txn_waybill.document_date,
				           txn_waybill.mawbl_bl
				    from txn_billing_waybill
				    left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
				    left join txn_billing on txn_billing.billing_number=txn_billing_waybill.billing_number
					left join origin_destination_port as origintbl on origintbl.id=txn_billing_waybill.origin_id
				    left join origin_destination_port as destinationtbl on destinationtbl.id=txn_billing_waybill.destination_id
				    left join mode_of_transport on mode_of_transport.id=txn_billing_waybill.mode_of_transport_id
				    where txn_billing_waybill.billing_number='$reference'
				";


	// Get total count of records
	$sql = "$customqry $searchSql";
	$result = mysql_query($sql);
	//$row = mysql_fetch_array($result);
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
			$searchSql
			$sortSql
			$limitSql";

			//echo $sql;

		

			
	$results = mysql_query($sql);
	if(!$results){
		echo $sql;
	}
	$line = 1;

	$blsstatus = '';
	$getblsstatusrs = query("select * from txn_billing where billing_number='$reference'");
	while($objtemp=fetch($getblsstatusrs)){
		$blsstatus = $objtemp->status;
	}
	
	
	while ($obj = mysql_fetch_object($results)) {
		$id = $obj->id;
		$billingnumber = utfEncode($obj->billing_number);
		$wbnumber = utfEncode($obj->waybill_number);
		$mawbl = utfEncode($obj->mawbl_bl);
		$docdate = dateFormat($obj->document_date,'m/d/Y');
		$origin = utfEncode($obj->origin);
		$destination = utfEncode($obj->destination);
		$modeoftransport = utfEncode($obj->modeoftransport);
		$vatablecharges = convertWithDecimal($obj->vatable_charges,2);
		$otherchargesnonvatable = convertWithDecimal($obj->other_charges_non_vatable,2);
		$vat = convertWithDecimal($obj->vat,2);
		$chargeamount = convertWithDecimal($obj->amount,2);
		$detailstatus = utfEncode($obj->detailstatus);
		$chargeableweight = trim($obj->chargeable_weight)==''?'N/A':convertWithDecimal($obj->chargeable_weight,4);

		
		$rowcheckbox = '';
		if($blsstatus=='LOGGED'){
			$rowcheckbox = "<input type='checkbox' class='blswaybillcheckbox valignmiddle' rowid='$id'>";
		}

		$rowwarning = '';
		if(strtoupper($detailstatus)!='ACTIVE'){
			$rowwarning = 'rowwarning';
		}


		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													"$rowcheckbox",
													 $detailstatus,
													 $wbnumber,
													 $mawbl,
													 $docdate,
													 $origin,
													 $destination, 
													 $modeoftransport,
													 $chargeableweight,
													 $vatablecharges,
													 $otherchargesnonvatable,
													 $vat,
													 $chargeamount
													 
													),
									'rowAttr'=>array(
													   'rowid'=>$id,
													   'rowwaybill'=>$wbnumber,
													   'class'=>$rowwarning
													   //'title'=>$warningtitle
													)
								);
		$line++;
	}
	echo json_encode($data);
?>