<?php
	include('../../../config/connection.php');
	include('../../../config/functions.php');

	$datefrom = isset($_GET['datefrom'])?escapeString($_GET['datefrom']):'';
	$dateto = isset($_GET['dateto'])?escapeString($_GET['dateto']):'';


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

	// Setup paging SQL
	$pageStart = ($page-1)*$rp;
	$limitSql = "limit $pageStart, $rp";



	if(trim($datefrom)==''&&trim($dateto)==''){
		$customqry =   "select shipper.id,
	                       company_name as shippername,
	                       SUM(IF(txn_waybill.status != 'VOID', 1,0)) as totaltransactions,
	                       SUM(IF(txn_waybill.status = 'DELIVERED', 1,0)) as totaldelivered,
	                       (SUM(IF(txn_waybill.status = 'DELIVERED', 1,0))/ SUM(IF(txn_waybill.status != 'VOID', 1,0)))*100 as deliveredpercentage,
	                       SUM(IF(txn_waybill.status != 'DELIVERED' and txn_waybill.status != 'VOID' and waybill_number in (select waybill_number from txn_manifest_waybill left join txn_manifest on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number where status!='VOID' and status!='LOGGED'), 1,0)) as totalundelivered,
	                       ( SUM(IF(txn_waybill.status != 'DELIVERED' and txn_waybill.status != 'VOID' and waybill_number in (select waybill_number from txn_manifest_waybill left join txn_manifest on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number where status!='VOID' and status!='LOGGED'), 1,0))/ SUM(IF(txn_waybill.status != 'VOID', 1,0)))*100 as undeliveredpercentage,
	                        SUM(IF(txn_waybill.status != 'DELIVERED' and txn_waybill.status != 'VOID' and waybill_number not in (select waybill_number from txn_manifest_waybill left join txn_manifest on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number where status!='VOID' and status!='LOGGED'), 1,0)) as totalonprocess,
	                        ( SUM(IF(txn_waybill.status != 'DELIVERED' and txn_waybill.status != 'VOID' and waybill_number not in (select waybill_number from txn_manifest_waybill left join txn_manifest on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number where status!='VOID' and status!='LOGGED'), 1,0))/ SUM(IF(txn_waybill.status != 'VOID', 1,0)))*100 as onprocesspercentage
	                from (select id, company_name from shipper $searchSql order by account_name $limitSql) as shipper
	                left join txn_waybill on txn_waybill.shipper_id=shipper.id
	               
	                ";
	}
	else{


	
	

		$customqry =   "select shipper.id,
		                       company_name as shippername,
		                       SUM(IF(txn_waybill.status != 'VOID', 1,0)) as totaltransactions,
		                       SUM(IF(txn_waybill.status = 'DELIVERED', 1,0)) as totaldelivered,
		                       (SUM(IF(txn_waybill.status = 'DELIVERED', 1,0))/ SUM(IF(txn_waybill.status != 'VOID', 1,0)))*100 as deliveredpercentage,
		                       SUM(IF(txn_waybill.status != 'DELIVERED' and txn_waybill.status != 'VOID' and waybill_number in (select waybill_number from txn_manifest_waybill left join txn_manifest on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number where status!='VOID' and status!='LOGGED'), 1,0)) as totalundelivered,
		                       ( SUM(IF(txn_waybill.status != 'DELIVERED' and txn_waybill.status != 'VOID' and waybill_number in (select waybill_number from txn_manifest_waybill left join txn_manifest on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number where status!='VOID' and status!='LOGGED'), 1,0))/ SUM(IF(txn_waybill.status != 'VOID', 1,0)))*100 as undeliveredpercentage,
		                        SUM(IF(txn_waybill.status != 'DELIVERED' and txn_waybill.status != 'VOID' and waybill_number not in (select waybill_number from txn_manifest_waybill left join txn_manifest on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number where status!='VOID' and status!='LOGGED'), 1,0)) as totalonprocess,
		                        ( SUM(IF(txn_waybill.status != 'DELIVERED' and txn_waybill.status != 'VOID' and waybill_number not in (select waybill_number from txn_manifest_waybill left join txn_manifest on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number where status!='VOID' and status!='LOGGED'), 1,0))/ SUM(IF(txn_waybill.status != 'VOID', 1,0)))*100 as onprocesspercentage
		                from (select id, company_name from shipper $searchSql) as shipper
		                left join txn_waybill on txn_waybill.shipper_id=shipper.id
		               
		                ";
	}
	

	if($datefrom!=''){
        $datefrom = date('Y-m-d', strtotime($datefrom));
    }
    if($dateto!=''){
        $dateto = date('Y-m-d', strtotime($dateto));
    }
	$filter = array();

	if($datefrom!=''&&$dateto!=''){
        array_push($filter,"date(txn_waybill.document_date) <= '$dateto' and date(txn_waybill.document_date) >= '$datefrom'");
    }
    else if($datefrom==''&&$dateto!=''){
        array_push($filter,"date(txn_waybill.document_date) <= '$dateto'");
    }
    else if($datefrom!=''&&$dateto==''){
         array_push($filter,"date(txn_waybill.document_date) >= '$datefrom'");
    }

    $condition = '';
    if(count($filter)>0){
    	$condition = ' where '.implode(" and ", $filter);
    }
    $customqry = $customqry.$condition.'  group by shipper.id';
	

	


	// Get total count of records
	$sql = "select id from shipper $searchSql";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$total = mysql_num_rows($result);

	

	// Return JSON data
	$data = array();
	$data['page'] = $page;
	$data['total'] = $total;
	$data['rows'] = array();

	if(trim($datefrom)==''&&trim($dateto)==''){
		$sql = "$customqry";
	}
	else{
		$sql = "select *
		        from ($customqry) as tbl
				$sortSql
				$limitSql";
	}
		

	//echo $sql;
	$results = mysql_query($sql);
	$line = 1;
	while ($obj = mysql_fetch_object($results)) {
		
		$data['rows'][] = array(
									'id' => $obj->id,

									'cell' => array(
													 $line+(($page-1)*$rp),
													 utfEncode($obj->shippername), 
													 convertWithDecimal($obj->totaltransactions,0),
													 convertWithDecimal($obj->totaldelivered,0),
													 convertWithDecimal($obj->deliveredpercentage,2),
													 convertWithDecimal($obj->totalundelivered,0),
													 convertWithDecimal($obj->undeliveredpercentage,2),
													 convertWithDecimal($obj->totalonprocess,0),
													 convertWithDecimal($obj->onprocesspercentage,2)
													 

													),
									'rowAttr'=>array(
													   'shipperid'=>$obj->id,
													   'class'=>'dash-shipperperformancerow pointer'
													)
								);
		$line++;
	}
	echo json_encode($data);
?>