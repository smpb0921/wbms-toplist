<?php
	include('../../../config/connection.php');
	include('../../../config/functions.php');

	$shipperid = isset($_GET['shipperid'])?trim($_GET['shipperid']):'';


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
					select txn_billing.id,
					       txn_billing.billing_number,
					       txn_billing.status,
					       txn_billing.document_date,
					       txn_billing.total_amount,
					       case
					       		when txn_billing.paid_flag=1 then 'PAID'
					       		else 'UNPAID'
					       end as paymentstatus,
					       txn_billing.created_date,
						   concat(cuser.first_name,' ',cuser.last_name) as created_by,
						   txn_billing.updated_date,
						   concat(uuser.first_name,' ',uuser.last_name) as updated_by
					from  txn_billing
					left join user as cuser on cuser.id=txn_billing.created_by
					left join user as uuser on uuser.id=txn_billing.updated_by
					where txn_billing.shipper_id='$shipperid'
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

	$data['page'] = 1;
	$data['total'] = $total;
	$data['rows'] = array();



	

		

	$results = mysql_query($sql);
	$line = 1;
	while ($obj = mysql_fetch_object($results)) {
		
		$billingnumber = utfEncode($obj->billing_number);
		$date = dateFormat($obj->document_date,'m/d/Y');
		$status = utfEncode($obj->status);
		$paymentstatus = utfEncode($obj->paymentstatus);
		$totalamount = convertWithDecimal($obj->total_amount,5);
		$createdby = utfEncode($obj->created_by);
		$createddate = $obj->created_date;
		$updatedby = utfEncode($obj->updated_by);
		$updateddate = $obj->updated_date;
		$id = $obj->id;

		

		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													 $billingnumber,
													 $date,
													 $status,
													 $paymentstatus,
													 $totalamount,
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