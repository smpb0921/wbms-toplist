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
				    select txn_load_plan_waybill.id,
				           txn_load_plan_waybill.load_plan_number,
				           txn_load_plan_waybill.waybill_number,
				           txn_load_plan_waybill.created_date,
				           txn_load_plan_waybill.created_by,
				           txn_waybill.document_date,
				           txn_waybill.delivery_date,
				           txn_waybill.shipper_account_name,
				           txn_waybill.consignee_account_name,
				           txn_waybill.package_number_of_packages,
				           txn_waybill.package_actual_weight,
				           txn_waybill.package_declared_value,
				           txn_waybill.package_cbm,
				           txn_waybill.package_vw,
				           txn_waybill.total_amount,
				           origintbl.description as origin,
				           destinationtbl.description as destination,
				           mode_of_transport.description as modeoftransport,
				           txn_load_plan.manifest_number,
				           ifnull(txn_manifest_waybill.waybill_number,'NOTINMANIFEST') as manifestremarks
				    from txn_load_plan_waybill
				    left join txn_load_plan on txn_load_plan.load_plan_number=txn_load_plan_waybill.load_plan_number
				    left join txn_waybill on txn_waybill.waybill_number=txn_load_plan_waybill.waybill_number
					left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id
				    left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id
				    left join mode_of_transport on mode_of_transport.id=txn_waybill.package_mode_of_transport
				    left join txn_manifest_waybill on txn_manifest_waybill.manifest_number=txn_load_plan.manifest_number and
				                                      txn_manifest_waybill.waybill_number=txn_load_plan_waybill.waybill_number
				    where txn_load_plan_waybill.load_plan_number='$reference'
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

	$ldpstatus = '';
	$getldpstatusrs = query("select * from txn_load_plan where load_plan_number='$reference'");
	while($objtemp=fetch($getldpstatusrs)){
		$ldpstatus = $objtemp->status;
	}
	
	
	while ($obj = mysql_fetch_object($results)) {
		$id = $obj->id;
		$ldpnumber = $obj->load_plan_number;
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
		$declaredvalue=$obj->package_declared_value;
		$chargeamount = $obj->total_amount;

		$ldpmanifest = $obj->manifest_number;
		$manifestremarks = utfEncode($obj->manifestremarks);

		$rowwarning = '';
		$warningtitle = '';
		if(trim($ldpmanifest)!=''&&$manifestremarks=='NOTINMANIFEST'){
			$rowwarning = 'rowwarning';
			$warningtitle = "Waybill [$wbnumber] not in corresponding manifest transaction";
		}



		$rowcheckbox = '';
		if($ldpstatus=='LOGGED'){
			$rowcheckbox = "<input type='checkbox' class='ldpwaybillcheckbox valignmiddle' rowid='$id'>";
		}


		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													"$rowcheckbox",
													 $docdate,
													 $deldate,
													 $wbnumber,
													 $destination,
													 $pckgs, 
													 $actualweight,
													 convertWithDecimal($declaredvalue,4),
													 $cbm,
													 $vw,
													 $shipper,
													 $consignee,
													 $modeoftransport
													 
													),
									'rowAttr'=>array(
													   'rowid'=>$id,
													   'rowWaybill'=>$wbnumber,
													   'class'=>$rowwarning,
													   'title'=>$warningtitle
													)
								);
		$line++;
	}
	echo json_encode($data);
?>