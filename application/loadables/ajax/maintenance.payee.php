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
	$sql = "select * from payee $searchSql";
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
	$sql = "        select payee.id,
						   payee.payee_name, 
						   payee.address,
                           payee.tin,
						   payee.created_date,
						   concat(cuser.first_name,' ',cuser.last_name) as createdby,
						   payee.updated_date,
						   concat(uuser.first_name,' ',uuser.last_name) as updatedby
					from payee
					left join user as cuser on cuser.id=payee.created_by
					left join user as uuser on uuser.id=payee.updated_by
			        $searchSql
			        $sortSql
			        $limitSql";

		

			
	$results = mysql_query($sql);
	$line = 1;
	while ($obj = mysql_fetch_object($results)) {
		$name = utfEncode($obj->payee_name);
		$address = utfEncode($obj->address);
        $tin = utfEncode($obj->tin);
		$createdby = utfEncode($obj->createdby);
		$createddate = $obj->created_date;
		$updatedby = utfEncode($obj->updatedby);
		$updateddate = $obj->updated_date;
		$id = $obj->id;


		$editbtn = (userAccess(USERID,'.editpayeebtn')==false)?"<img src='../resources/flexigrid/images/edit.png' rowid='$id' title='Edit Payee' class='editpayeebtn pointer' data-toggle='modal' href='#editpayeemodal' height='20px'>":'';

		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													$editbtn,
													 $id, 
													 $name,
                                                     $address,
													 $tin,
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