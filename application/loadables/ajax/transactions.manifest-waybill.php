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
	$searchSql = ($qtype != '' && $query != '') ? "where $qtype like '%$query%'" : '';

	$customqry = "
				    select txn_manifest_waybill.id,
				           txn_manifest_waybill.manifest_number,
				           txn_manifest_waybill.waybill_number,
				           txn_manifest_waybill.created_date,
				           txn_manifest_waybill.created_by,
				           txn_manifest_waybill.remarks,
				           txn_waybill.document_date,
				           txn_waybill.shipper_account_name,
				           txn_waybill.consignee_account_name,
				           txn_waybill.package_number_of_packages,
				           txn_waybill.package_actual_weight,
				           txn_waybill.package_cbm,
				           txn_waybill.package_vw,
				           txn_waybill.delivery_date,
				           txn_waybill.total_amount,
				           pouch_size.description as pouchsize,
				           txn_waybill.amount_for_collection,
				           origintbl.description as origin,
				           destinationtbl.description as destination,
				           mode_of_transport.description as modeoftransport,
				           ifnull(numofpckgtbl.numofpackage,0) as numofpackage,
				           ifnull(totalpckgtbl.totalpackage,0) as totalpackage
				    from txn_manifest_waybill
				    left join txn_waybill on txn_waybill.waybill_number=txn_manifest_waybill.waybill_number
				    left join pouch_size on pouch_size.id=txn_manifest_waybill.pouch_size_id
					left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id
				    left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id
				    left join mode_of_transport on mode_of_transport.id=txn_waybill.package_mode_of_transport

				    left join (		
				    			 select txn_manifest_waybill_package_code.manifest_number,
				    			 		txn_manifest_waybill_package_code.waybill_number,
				    			 		count(package_code) as numofpackage
				    			 from txn_manifest_waybill_package_code
				    			 where txn_manifest_waybill_package_code.manifest_number='$reference'
				    			 group by txn_manifest_waybill_package_code.waybill_number

				    		   ) as numofpckgtbl
				    on numofpckgtbl.manifest_number=txn_manifest_waybill.manifest_number and
				       numofpckgtbl.waybill_number=txn_manifest_waybill.waybill_number
				    left join (
				    			 select waybill_number,
				    			        count(code) as totalpackage 
				    			 from txn_waybill_package_code 
				    			 group by waybill_number

				    		   ) as totalpckgtbl
				    on totalpckgtbl.waybill_number=txn_manifest_waybill.waybill_number
				    where txn_manifest_waybill.manifest_number='$reference'
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

	$mftstatus = '';
	$getmftstatusrs = query("select * from txn_manifest where manifest_number='$reference'");
	while($objtemp=fetch($getmftstatusrs)){
		$mftstatus = $objtemp->status;
	}
	

	while ($obj = mysql_fetch_object($results)) {
		$id = $obj->id;
		$mftnumber = $obj->manifest_number;
		$pouchsize = $obj->pouchsize;
		$wbnumber = $obj->waybill_number;
		$docdate = dateFormat($obj->document_date,'m/d/Y');
		$deldate = dateFormat($obj->delivery_date,'m/d/Y');
		$origin = utfEncode($obj->origin);
		$destination = utfEncode($obj->destination);
		$modeoftransport = utfEncode($obj->modeoftransport);
		$shipper = utfEncode($obj->shipper_account_name);
		$consignee = utfEncode($obj->consignee_account_name);
		$pckgs = $obj->package_number_of_packages;
		$actualweight = $obj->package_actual_weight;
		$cbm = $obj->package_cbm;
		$vw = $obj->package_vw;
		$amountforcollection = $obj->amount_for_collection;
		$chargeamount = $obj->total_amount;
		$packagecount = $obj->numofpackage."/".$obj->totalpackage;
		$remarks = utfEncode($obj->remarks);

		if($obj->totalpackage==0){
			$pckgs = $obj->package_number_of_packages.'/'.$obj->package_number_of_packages;
		}
		else{
			$pckgs = $packagecount;
		}





		$rowcheckbox = '';
		if($mftstatus=='LOGGED'){
			$rowcheckbox = "<input type='checkbox' class='mftwaybillcheckbox valignmiddle' rowid='$id'>";
			$remarks = "<input type='text' class='mftwaybillremarks' rowid='$id' value='$remarks' style='min-width:100%'>";
		}


		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													"$rowcheckbox",
													 $wbnumber,
													 $pouchsize,
													 $docdate,
													 $deldate,
													 $destination,
													 $pckgs, 
													 $actualweight,
													 $cbm,
													 $vw,
													 $shipper,
													 $consignee,
													 $modeoftransport,
													 $amountforcollection,
													 $remarks
													 
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