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


	// Get total count of records
	$sql = "select * from account_executive $searchSql";
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
			from (
			        select account_executive.id,
						   account_executive.code, 
						   account_executive.name,
						   account_executive.email_address,
						   account_executive.mobile_number,
						   account_executive.username,
						   account_executive.password,
						   account_executive.created_date,
						   concat(cuser.first_name,' ',cuser.last_name) as created_by,
						   account_executive.updated_date,
						   concat(uuser.first_name,' ',uuser.last_name) as updated_by
					from account_executive
					left join user as cuser on cuser.id=account_executive.created_by
					left join user as uuser on uuser.id=account_executive.updated_by
				  ) as tbl
			$searchSql
			$sortSql
			$limitSql";


			
	$results = mysql_query($sql);
	$line = 1;
	while ($obj = mysql_fetch_object($results)) {
		$code = utfEncode($obj->code);
		$name = utfEncode($obj->name);
		$email = utfEncode($obj->email_address);
		$mobile = utfEncode($obj->mobile_number);
		$username = utfEncode($obj->username);
		$password = $obj->password;
		$createdby = utfEncode($obj->created_by);
		$createddate = $obj->created_date;
		$updatedby = utfEncode($obj->updated_by);
		$updateddate = $obj->updated_date;
		$id = $obj->id;

		$editbtn = (userAccess(USERID,'.editaccountexecutivebtn')==false)?"<img src='../resources/flexigrid/images/edit.png' rowid='$id' code='$code' name='$name' email='$email' mobile='$mobile' username='$username' password='$password' title='Edit Account Executive' class='editaccountexecutivebtn pointer' data-toggle='modal' href='#editaccountexecutivemodal' height='20px'>":'';
		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													$editbtn,
													 $id, 
													 $code,
													 $name,
													 $email,
													 $username,
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