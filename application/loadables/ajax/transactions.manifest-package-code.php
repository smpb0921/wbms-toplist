<?php
	include('../../../config/connection.php');
	include('../../../config/functions.php');

	$txnnumber = isset($_GET['txnnumber'])?strtoupper($_GET['txnnumber']):'';


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
				    select txn_manifest_waybill_package_code.id,
				           txn_manifest_waybill_package_code.manifest_number,
				           txn_manifest_waybill_package_code.waybill_number,
				           txn_manifest_waybill_package_code.package_code,
				           txn_manifest_waybill_package_code.created_date,
				           txn_manifest_waybill_package_code.created_by
				    from txn_manifest_waybill_package_code
				    where txn_manifest_waybill_package_code.manifest_number='$txnnumber'
				    order by txn_manifest_waybill_package_code.package_code asc
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
	$getmftstatusrs = query("select * from txn_manifest where manifest_number='$txnnumber'");
	while($objtemp=fetch($getmftstatusrs)){
		$mftstatus = $objtemp->status;
	}
		
	while ($obj = mysql_fetch_object($results)) {
		$id = $obj->id;
		$mftnumber = $obj->manifest_number;
		$wbnumber = $obj->waybill_number;
		$packagecode = $obj->package_code;

		$rowcheckbox = '';
		if($mftstatus!='POSTED'){
			$rowcheckbox = "<input type='checkbox' class='mftpackagecoderowcheckbox valignmiddle' rowid='$id'>";
		}

		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													"$rowcheckbox",
													 $packagecode, 
													 $wbnumber
													),
									'rowAttr'=>array(
													   'rowid'=>$id
													)
								);
		$line++;
	}
	echo json_encode($data);
?>