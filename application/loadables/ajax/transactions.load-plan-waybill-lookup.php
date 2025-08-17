<?php
	include('../../../config/connection.php');
	include('../../../config/functions.php');


	$origin = isset($_GET['origin'])?strtoupper($_GET['origin']):'';
	//$destination = isset($_GET['destination'])?strtoupper($_GET['destination']):'';
	$mode = isset($_GET['mode'])?strtoupper($_GET['mode']):'';
	$validwaybillstatus = getInfo("company_information","load_plan_waybill_status","where id=1");
	$loadplannumber = isset($_GET['loadplannumber'])?strtoupper($_GET['loadplannumber']):'';


	

	$page = 1;	// The current page
	$sortname = 'waybill_number';	// Sort column
	$sortorder = 'asc';	// Sort order
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
	$searchSql = ($qtype != '' && $query != '') ? "where $qtype like '%$query%'" : '';

	$customqry = "
				    select txn_waybill.id,
				           txn_waybill.waybill_number,
				           txn_waybill.status,
				           txn_waybill.document_date,
				           txn_waybill.shipper_account_name,
						   txn_waybill.consignee_account_name,
		                   txn_waybill.package_number_of_packages,
		                   txn_waybill.package_actual_weight,
		                   txn_waybill.package_cbm,
		                   txn_waybill.total_amount,
		                   dimensiontbl.volumetric_weight,
		                   dimensiontbl.cbm,
		                   origin_destination_port.description as destination
				    from txn_waybill
				    left join (select waybill_number,
				                      sum(volumetric_weight) as volumetric_weight,
				                      sum(cbm) as cbm 
				               from txn_waybill_package_dimension
				               group by waybill_number
				               ) as dimensiontbl
				    on txn_waybill.waybill_number=dimensiontbl.waybill_number
				    left join origin_destination_port on origin_destination_port.id=txn_waybill.destination_id
				    where txn_waybill.origin_id='$origin' and
				          txn_waybill.status='$validwaybillstatus' and
				          txn_waybill.destination_id in 
				                   (
				                   		select origin_destination_port_id 
				                        from txn_load_plan_destination 
				                        where load_plan_number='$loadplannumber'
				                    ) and 
				          txn_waybill.package_mode_of_transport='$mode' and
				         
				          txn_waybill.waybill_number not in (
				          										select txn_load_plan_waybill.waybill_number 
				          	                                    from txn_load_plan_waybill 
				          	                                    left join txn_load_plan 
				          	                                    on txn_load_plan.load_plan_number=txn_load_plan_waybill.load_plan_number
				          	                                    where txn_load_plan.status!='VOID' and 
				          	                                          txn_load_plan.status!='DISPATCHED'
				          	                                   ) or
				          (txn_waybill.waybill_number in (
				          										select txn_load_plan_waybill.waybill_number 
				          	                                    from txn_load_plan_waybill 
				          	                                    left join txn_load_plan 
				          	                                    on txn_load_plan.load_plan_number=txn_load_plan_waybill.load_plan_number
				          	                                    where txn_load_plan.status!='VOID' and  
				          	                                          txn_load_plan.status!='DISPATCHED'
				          	                              ) and
				          
				          txn_waybill.waybill_number not in (
				          										select txn_load_plan_waybill.waybill_number 
				          	                                    from txn_load_plan_waybill 
				          	                                    left join txn_load_plan 
				          	                                    on txn_load_plan.load_plan_number=txn_load_plan_waybill.load_plan_number
				          	                                    where txn_load_plan.status!='VOID' and   
				          	                                          txn_load_plan.status!='DISPATCHED' and 
				          	                                          (
				          	                                          	(
				          	                                          		txn_load_plan.load_plan_number not in (select load_plan_number 
				          	                                          	                                       from txn_manifest 
				          	                                          	                                       where txn_manifest.status!='VOID'
				          	                                          	                                       ) 
				          	                                          	) or
				          	                                          	(
				          	                                          		txn_load_plan.load_plan_number in ( select load_plan_number 
					          	                                          	                                    from txn_manifest 
					          	                                          	                                    where txn_manifest.status='LOGGED'
					          	                                          	                                   )
				          	                                            ) or
				          	                                            (
				          	                                            	
				          	                                                txn_load_plan_waybill.waybill_number in (select waybill_number 
				          	                                                                                         from txn_manifest_waybill
				          	                                                                                         left join txn_manifest 
				          	                                                                                         on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number
				          	                                                                                         where txn_manifest.status='POSTED'
				          	                                            									        )
				          	                                            )


				          	                                          )
				          	                                   ) 

				           )

				";

    
	// Get total count of records
	$sql = "select * from ( $customqry ) as tbl $searchSql";
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


	

	while ($obj = mysql_fetch_object($results)) {
		$id = $obj->id;
		$wbnumber = $obj->waybill_number;
		$docdate = dateFormat($obj->document_date,'m/d/Y');
		$shipper = utfEncode($obj->shipper_account_name);
		$consignee = utfEncode($obj->consignee_account_name);
		$pckgs = $obj->package_number_of_packages;
		$actualweight = $obj->package_actual_weight;
		$vwcbm = $obj->package_cbm;
		$vw = convertWithDecimal($obj->volumetric_weight,5);
		$cbm = convertWithDecimal($obj->cbm,5);
		$chargeamount = $obj->total_amount;
		$destination = utfEncode($obj->destination);



		
		$rowcheckbox = "<input type='checkbox' class='ldpwaybilllookup-checkbox valignmiddle' waybillnumber='$wbnumber'>";
		


		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													"$rowcheckbox",
													 $wbnumber, 
													 $destination,
													 $docdate,
													 $shipper,
													 $consignee,
													 $pckgs,
													 $actualweight,
													 $cbm,
													 $vw
													 
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