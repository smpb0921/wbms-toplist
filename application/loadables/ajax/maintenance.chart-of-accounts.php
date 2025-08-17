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


	$qry  = "select chart_of_accounts.id,
						   chart_of_accounts.code, 
						   chart_of_accounts.description,
						   chart_of_accounts.created_date,
						   chart_of_accounts.expense_type_id,
						   chart_of_accounts.type as producttype,
						   concat(cuser.first_name,' ',cuser.last_name) as createdby,
						   chart_of_accounts.updated_date,
						   concat(uuser.first_name,' ',uuser.last_name) as updatedby,
						   expense_type.description as expensetype
					from chart_of_accounts
					left join expense_type on expense_type.id=chart_of_accounts.expense_type_id
					left join user as cuser on cuser.id=chart_of_accounts.created_by
					left join user as uuser on uuser.id=chart_of_accounts.updated_by";


	// Get total count of records
	$sql = "$qry $searchSql";
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
	$sql = "
			$qry       	 
			$searchSql
			$sortSql
			$limitSql";

		

			
	$results = mysql_query($sql);
	$line = 1;
	while ($obj = mysql_fetch_object($results)) {
		$code = utfEncode($obj->code);
		$desc = utfEncode($obj->description);
		$producttype = utfEncode($obj->producttype);
		$createdby = utfEncode($obj->createdby);
		$createddate = $obj->created_date;
		$updatedby = utfEncode($obj->updatedby);
		$updateddate = $obj->updated_date;
		$id = $obj->id;

		$expensetypeid = utfEncode($obj->expense_type_id);
		$expensetype = utfEncode($obj->expensetype);


		$editbtn = (userAccess(USERID,'.editchartofaccountsbtn')==false)?"<img src='../resources/flexigrid/images/edit.png' rowid='$id' code='$code' desc='$desc' expensetypeid='$expensetypeid' expensetype='$expensetype' title='Edit Chart of Accounts' class='editchartofaccountsbtn pointer' data-toggle='modal' href='#editchartofaccountsmodal' height='20px'>":'';

		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													 $editbtn,
													 $id, 
													 $code,
													 $desc,
													 $expensetype,
													 $producttype,
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