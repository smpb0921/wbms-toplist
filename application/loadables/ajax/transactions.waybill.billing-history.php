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
	$searchSql = ($qtype != '' && $query != '') ? " and $qtype like '%$query%'" : '';

	$waybillnumber = isset($_GET['waybillnumber'])?escapeString(strtoupper(trim($_GET['waybillnumber']," "))):'';

	$customqry = "
				    select txn_waybill_billing_history.id,
				           txn_waybill_billing_history.waybill_number,
				           txn_waybill_billing_history.reference,
				           txn_waybill_billing_history.billing_number,
				           txn_waybill_billing_history.remarks,
				           txn_waybill_billing_history.created_date,
				           txn_waybill_billing_history.created_by,
				           concat(first_name,' ',last_name) as createdby,
				           case 
				           		when billing_flag=0 then 'UNBILLED'
				           		when billing_flag=1 then 'BILLED'
				           		else 'N/A'
				           end as billing_flag
				    from txn_waybill_billing_history
				    left join user on user.id=txn_waybill_billing_history.created_by
				    where waybill_number='$waybillnumber'
				";


	// Get total count of records
	$sql = "$customqry $searchSql";
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
	$sql = "$customqry
			$searchSql
			$sortSql
			$limitSql";

			//echo $sql;

		

			
	$results = mysql_query($sql);
	$line = 1;

	
	while ($obj = mysql_fetch_object($results)) {
		$id = $obj->id;
		$createddate = dateFormat($obj->created_date,'m/d/Y h:i:s A');

		$data['rows'][] = array(
									'id' => $obj->id,
									'cell' => array(
													 utfEncode($obj->billing_flag), 
													 utfEncode($obj->reference),
													 utfEncode($obj->remarks),
													 $createddate,
													 utfEncode($obj->createdby)

													)

								);
		$line++;
	}
	echo json_encode($data);
?>