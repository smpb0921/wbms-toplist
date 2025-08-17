<?php
	include('../../../config/connection.php');
	include('../../../config/functions.php');

	$shipperid = isset($_GET['shipper'])?trim($_GET['shipper']," "):'';


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
				    select shipper_rate.id,
				           shipper_rate.shipper_id,
						   shipper_rate.origin_id,
						   origintbl.description as origin, 
						   shipper_rate.destination_id,
						   destinationtbl.description as destination,
						   shipper_rate.mode_of_transport_id,
						   mode_of_transport.description as mode_of_transport,
						   shipper_rate.freight_computation,
						   case
							   when shipper_rate.fixed_rate_flag=1 then 'YES'
							   else 'NO'
						   end as fixed_rate_flag,
						   case
							   when shipper_rate.rush_flag=1 then 'YES'
							   else 'NO'
						   end as rush_flag,
						   case
							   when shipper_rate.pull_out_flag=1 then 'YES'
							   else 'NO'
						   end as pull_out_flag,
						   case
							   when shipper_rate.ad_valorem_flag=1 then 'YES'
							   else 'NO'
						   end as ad_valorem_flag,
						   shipper_rate.valuation,
						   shipper_rate.freight_rate,
						   shipper_rate.insurance_rate,
						   shipper_rate.fuel_rate,
						   shipper_rate.bunker_rate,
						   shipper_rate.minimum_rate,
						   shipper_rate.oda_rate,
						   shipper_rate.waybill_type,
						   shipper_rate.pouch_size_id,
						   pouch_size.description as pouchsize,
						   shipper_rate.fixed_rate_amount,
						   shipper_rate.pull_out_fee,
						   shipper_rate.created_date,
						   shipper_rate.services_id,
						   shipper_rate.return_document_fee,
						   shipper_rate.waybill_fee,
						   shipper_rate.security_fee,
						   shipper_rate.doc_stamp_fee,
						   shipper_rate.collection_fee_percentage,
						   shipper_rate.express_transaction_type,
						   freight_charge_computation.description as freightchargecomputation,
						   shipper_rate.insurance_rate_computation,
						   shipper_rate.excess_amount,
						   insurance_rate_computation.description as insuranceratecomputation,
						   services.description as services,
						   concat(cuser.first_name,' ',cuser.last_name) as created_by,
						   shipper_rate.updated_date,
						   concat(uuser.first_name,' ',uuser.last_name) as updated_by,
						   parcel_type.description as parceltype,
						   shipper_rate.cbm_computation,
						   cbm_computation.description as cbmcomputation
					from shipper_rate
					left join origin_destination_port as origintbl on origintbl.id=shipper_rate.origin_id
					left join origin_destination_port as destinationtbl on destinationtbl.id=shipper_rate.destination_id
					left join mode_of_transport on mode_of_transport.id=shipper_rate.mode_of_transport_id
					left join user as cuser on cuser.id=shipper_rate.created_by
					left join user as uuser on uuser.id=shipper_rate.updated_by
					left join pouch_size on pouch_size.id=shipper_rate.pouch_size_id
					left join freight_charge_computation on freight_charge_computation.id=shipper_rate.freight_charge_computation
					left join insurance_rate_computation on insurance_rate_computation.id=shipper_rate.insurance_rate_computation
					left join services on services.id=shipper_rate.services_id
					left join parcel_type on parcel_type.id=shipper_rate.parcel_type_id
					left join cbm_computation on cbm_computation.id=shipper_rate.cbm_computation
					where shipper_rate.shipper_id='$shipperid'
					order by origintbl.description, destinationtbl.description asc
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
	$line = 1;
	while ($obj = mysql_fetch_object($results)) {
		$originid = $obj->origin_id;
		$origin = utfEncode($obj->origin);
		$destinationid = $obj->destination_id;
		$destination = utfEncode($obj->destination);
		$modeoftransportid = $obj->mode_of_transport_id;
		$modeoftransport = utfEncode($obj->mode_of_transport);
		$servicesid = $obj->services_id;
		$services = utfEncode($obj->services);
		$freightcomp = $obj->freight_computation;
		$fixedrateflag = $obj->fixed_rate_flag;
		$rushflag = $obj->rush_flag;
		$pulloutflag = $obj->pull_out_flag;
		$advaloremflag = $obj->ad_valorem_flag;
		$valuation = convertWithDecimal($obj->valuation,5);
		$freightrate = convertWithDecimal($obj->freight_rate,5);
		$insurancerate = convertWithDecimal($obj->insurance_rate,5);
		$fuelrate = convertWithDecimal($obj->fuel_rate,5);
		$bunkerrate = convertWithDecimal($obj->bunker_rate,5);
		$minimumrate = convertWithDecimal($obj->minimum_rate,5);
		$returndocumentfee = convertWithDecimal($obj->return_document_fee,5);
		$waybillfee = convertWithDecimal($obj->waybill_fee,5);
		$securityfee = convertWithDecimal($obj->security_fee,5);
		$docstampfee = convertWithDecimal($obj->doc_stamp_fee,5);
		$collectionpercentage = convertWithDecimal($obj->collection_fee_percentage,5);
		$freightchargecomputation = utfEncode($obj->freightchargecomputation);
		$insuranceratecomputation = utfEncode($obj->insuranceratecomputation);
		$cbmcomputation = utfEncode($obj->cbmcomputation);
		$excessamount = convertWithDecimal($obj->excess_amount,5);
		$createdby = utfEncode($obj->created_by);
		$createddate = dateFormat($obj->created_date,'m/d/Y h:i:s A');
		$updatedby = utfEncode($obj->updated_by);
		$updateddate = dateFormat($obj->updated_date,'m/d/Y h:i:s A');

		$parceltype = utfEncode($obj->parceltype);

		$waybilltype = utfEncode($obj->waybill_type);
		$pouchsizeid = utfEncode($obj->pouch_size_id);
		$pouchsize = utfEncode($obj->pouchsize);
		$expresstransactiontype = utfEncode($obj->express_transaction_type);


		$fixedrateamount = convertWithDecimal($obj->fixed_rate_amount,5);
		$pulloutfee = convertWithDecimal($obj->pull_out_fee,5);
		$odarate = convertWithDecimal($obj->oda_rate,5);
		$id = $obj->id;

		$checkbox = "<input type='checkbox' class='viewshipperratemodal-checkbox' rowid='$id'>";

		if(strtoupper($freightcomp)=='CBM'||strtoupper($freightcomp)=='ACTUAL WEIGHT'||strtoupper($freightcomp)=='VOLUMETRIC'||strtoupper($freightcomp)=='DEFAULT'){
			$freightchargebtn = "&nbsp;&nbsp;<img src='../resources/img/weight_edit.png' title='View Freight Charges' shipperid='$shipperid' shipperrateid='$id' class='viewshipperfreightchargebtn pointer' height='20px'>";
		}
		else{
			$freightchargebtn = '';
		}
		if(strtoupper($waybilltype)=='PARCEL'){
			$handlinginstructionbtn = "&nbsp;&nbsp;<img src='../resources/img/handling.png' title='View Handling Instruction' shipperid='$shipperid' shipperrateid='$id' class='viewshipperhandlinginstructionbtn pointer' height='20px'>";
		}
		else{
			$handlinginstructionbtn = '';
		}
		

		$editbtn = (userAccess(USERID,'.editshipperratebtn')==false)?"<img src='../resources/flexigrid/images/edit.png' rowid='$id' title='Edit Shipper Rate' shipperid='$shipperid' class='editshipperratebtn pointer' height='20px'>&nbsp;&nbsp;<img src='../resources/img/calculator.png' rowid='$id' title='Re-compute Unbilled Waybills' shipperrateID='$id' class='recomputewaybillbtn hidden pointer' height='20px'>$handlinginstructionbtn $freightchargebtn":'';

		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													 $checkbox,
													 $editbtn,
													 $waybilltype,
													 //$parceltype,
													 $origin,
													 $destination,
													 $modeoftransport,
													 $services,
													 $rushflag,
													 $pulloutflag,
													 $freightcomp,
													 $freightchargecomputation,
													 $cbmcomputation,
													 $pouchsize,
													 $expresstransactiontype,
													 $advaloremflag,
													 $fixedrateflag,
													 $fixedrateamount,
													 $collectionpercentage,
													 $valuation,
													 $freightrate,
													 $insuranceratecomputation,
													 $excessamount,
													 $insurancerate,
													 $fuelrate,
													 $bunkerrate,
													 $minimumrate,
													 $odarate,
													 $returndocumentfee,
													 $waybillfee,
													 $securityfee,
													 $docstampfee,
													 $createddate,
													 $createdby,
													 $updateddate,
													 $updatedby
													 

													),
									'rowAttr'=>array(
													   'rowid'=>$id
													)
								);
		$line++;
	}
	echo json_encode($data);
?>