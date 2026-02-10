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
	$searchSql = ($qtype != '' && $query != '') ? "where $qtype like '%$query%'" : '';

	$customqry = "
				    select supplier_rate.id,
						   supplier_rate.origin_id,
						   origintbl.description as origin, 
						   supplier_rate.destination_id,
						   destinationtbl.description as destination,
						   supplier_rate.mode_of_transport_id,
						   mode_of_transport.description as mode_of_transport,
						   supplier_rate.freight_computation,
						   supplier_rate.waybill_type,
						   supplier_rate.pouch_size_id,
						   pouch_size.description as pouchsize,
						   supplier_rate.services_id,
						   services.description as services,
						   zone.description as zone,
						   third_party_logistic.description as thirdpartylogistic,
						   case
							   when supplier_rate.fixed_rate_flag=1 then 'YES'
							   else 'NO'
						   end as fixed_rate_flag,
						   case
							   when supplier_rate.rush_flag=1 then 'YES'
							   else 'NO'
						   end as rush_flag,
						   case
							   when supplier_rate.pull_out_flag=1 then 'YES'
							   else 'NO'
						   end as pull_out_flag,
						   supplier_rate.valuation,
						   supplier_rate.freight_rate,
						   supplier_rate.insurance_rate,
						   supplier_rate.fuel_rate,
						   supplier_rate.bunker_rate,
						   supplier_rate.minimum_rate,
						   supplier_rate.fixed_rate_amount,
						   supplier_rate.pull_out_fee,
						   supplier_rate.oda_rate,
						   supplier_rate.created_date,
						   concat(cuser.first_name,' ',cuser.last_name) as created_by,
						   supplier_rate.updated_date,
						   concat(uuser.first_name,' ',uuser.last_name) as updated_by,
						   supplier_rate.shipment_type_id,
						   shipment_type.code as shipmenttype,
						   supplier_rate.shipment_mode_id,
						   shipment_mode.code as shipmentmode
					from supplier_rate
					left join origin_destination_port as origintbl on origintbl.id=supplier_rate.origin_id
					left join origin_destination_port as destinationtbl on destinationtbl.id=supplier_rate.destination_id
					left join mode_of_transport on mode_of_transport.id=supplier_rate.mode_of_transport_id
					left join pouch_size on pouch_size.id=supplier_rate.pouch_size_id
					left join zone on zone.id=supplier_rate.zone_id
					left join third_party_logistic on third_party_logistic.id=supplier_rate.third_party_logistic_id
					left join services on services.id=supplier_rate.services_id
					left join user as cuser on cuser.id=supplier_rate.created_by
					left join user as uuser on uuser.id=supplier_rate.updated_by
					left join shipment_type on shipment_type.id=supplier_rate.shipment_type_id
					left join shipment_mode on shipment_mode.id=supplier_rate.shipment_mode_id
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
		$tpl = utfEncode($obj->thirdpartylogistic);
		$type = utfEncode($obj->waybill_type);
		$pouchsize = utfEncode($obj->pouchsize);
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
		$valuation = convertWithDecimal($obj->valuation,5);
		$freightrate = convertWithDecimal($obj->freight_rate,5);
		$insurancerate = convertWithDecimal($obj->insurance_rate,5);
		$fuelrate = convertWithDecimal($obj->fuel_rate,5);
		$bunkerrate = convertWithDecimal($obj->bunker_rate,5);
		$minimumrate = convertWithDecimal($obj->minimum_rate,5);
		$createdby = utfEncode($obj->created_by);
		$createddate = $obj->created_date;
		$updatedby = utfEncode($obj->updated_by);
		$updateddate = $obj->updated_date;
		$id = $obj->id;
		$fixedrateamount = convertWithDecimal($obj->fixed_rate_amount,5);
		$pulloutfee = convertWithDecimal($obj->pull_out_fee,5);
		$odarate = convertWithDecimal($obj->oda_rate,5);
		$zone = utfEncode($obj->zone);
		$shipmenttype = utfEncode($obj->shipmenttype);
		$shipmentmode = utfEncode($obj->shipmentmode);

		$editbtn = (userAccess(USERID,'.editsupplierratebtn')==false)?"<img src='../resources/flexigrid/images/edit.png' rowid='$id' title='Edit supplier Rate' class='editsupplierratebtn pointer' data-toggle='modal' href='#editsupplierratemodal' height='20px'>":'';

		$viewsupplierratefreightchargebtn = (userAccess(USERID,'.editsupplierratebtn')==false&&$fixedrateflag=='NO')?"<img src='../resources/img/weight_add.png' rowid='$id' title='Freight Rate based on Weight Range' class='viewsupplierratefreightchargebtn pointer' height='20px'>":'';

		


		$arr = array();
		if(trim($editbtn)!=''){
			array_push($arr, $editbtn);
		}
		if(trim($viewsupplierratefreightchargebtn)!=''){
			array_push($arr, $viewsupplierratefreightchargebtn);
		}

		$btn = implode(" ", $arr);

		$freightrateshown = $fixedrateflag=='YES'?$freightrate:"<a class='viewpblfreightratebtn pointer' rowid='$id'>View</a>";
		

		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													 $btn,
													 $id, 
													 $shipmenttype,
													 $shipmentmode,
													 $modeoftransport,
													 $tpl,
													 $type,
													 $pouchsize,
													 $origin,
													 $zone,
													 /*$destination,
													 $services,
													 $rushflag,
													 $pulloutflag,
													 $freightcomp,
													 $pouchsize,
													 $fixedrateflag,
													 $fixedrateamount,
													 $pulloutfee,*/
													 $freightrateshown,
													 $valuation,
													 $insurancerate,
													 $fuelrate,
													 $bunkerrate,
													 $minimumrate,
													 $createdby,
													 $createddate,
													 $updatedby,
													 $updateddate

													),
									'rowAttr'=>array(
													   'rowid'=>$id
													)
								);
		$line++;
	}
	echo json_encode($data);
?>