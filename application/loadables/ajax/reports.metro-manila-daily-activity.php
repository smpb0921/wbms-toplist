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
	$sortSql = "order by txn_manifest.driver_name asc, $sortname $sortorder";
	

	//Search Condition
	$filter = array();
	$docdate = '';
    if($docdate!=''){
        $docdate = date('Y-m-d', strtotime($docdate));
    }

	
	if($qtype != '' && $query != ''){
		 array_push($filter,"$qtype like '%$query%'");
	}
	//if($bookingnumber!=''){
    	array_push($filter, "date(txn_manifest.document_date) = '".$docdate."')");
    //}
	//$searchSql = '';
    //if(count($filter)>0){
    	$searchSql = ' where '.implode(" and ", $filter);
    //}

    $groupby = " group by txn_manifest.driver_name, txn_waybill.consignee_city ";

	$customqry = "
                   
                    select  txn_manifest_waybill.manifest_number,
                            group_concat(distinct txn_manifest_waybill.waybill_number) as waybill_number,
                            txn_manifest_waybill.pouch_size_id,
                            txn_manifest.manifest_number,
                            txn_manifest.driver_name,
                            txn_waybill.consignee_city as bolcity,
                            count(distinct txn_manifest_waybill.waybill_number) as totalscheduledcount,
                            group_concat(distinct withattempt.status_description) as pendingwithattemptcount,
                            group_concat(distinct withoutattempt.status_description) as pendingwithoutattemptcount,
                            '' as remarks
                        from txn_manifest_waybill
                        left join txn_manifest on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number
                        left join txn_waybill on txn_waybill.waybill_number=txn_manifest_waybill.waybill_number
                        left join txn_waybill_status_history as withattempt on withattempt.waybill_number=txn_manifest_waybill.waybill_number and withattempt.status_description='PENDING WITH ATTEMPT'
                        left join txn_waybill_status_history as withoutattempt on withoutattempt.waybill_number=txn_manifest_waybill.waybill_number  and withoutattempt.status_description='PENDING WITHOUT ATTEMPT' 


                        
                 

				";


	// Get total count of records
	$total = 0;
	$sql = "$customqry $searchSql $groupby";
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
	$sql = "
			$customqry
			$searchSql
            $groupby
			$sortSql
			$limitSql";

			//echo $sql;

		

			
	$results = mysql_query($sql);
	if(!$results){
		echo $sql;
	}
	$line = 1;
	while ($obj = mysql_fetch_object($results)) {
	
		$data['rows'][] = array(
									'id' => $obj->id,
									'cell' => array( 
													 utfEncode($obj->id),
													 utfEncode($obj->booking_number),
													 utfEncode($obj->waybill_number),
													 utfEncode($obj->mawbl_bl),
													 utfEncode($obj->shipper),
													 utfEncode($obj->status),
													 utfEncode($obj->pickupdate),
													 utfEncode($obj->created_date),
													 utfEncode($obj->createdby)
													),
									'rowAttr'=>array(
													   'rowid'=>$obj->id,
													   "class"=>'bookingwaybillrow pointer'
													)
								);
		$line++;
	}
	echo json_encode($data);
?>