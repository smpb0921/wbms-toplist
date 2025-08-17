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


	$customqry =   "select personnel.id,
						   personnel.first_name, 
						   personnel.last_name, 
						   personnel.position,
						   personnel.contact_number,
						   personnel.type,
						   personnel.created_date,
						   concat(cuser.first_name,' ',cuser.last_name) as created_by,
						   personnel.updated_date,
						   concat(uuser.first_name,' ',uuser.last_name) as updated_by,
						   personnel.active_flag,
						   case 
						   		when personnel.active_flag='1' then 'ACTIVE'
						   		else 'INACTIVE'
						   end as activeflag
					from personnel
					left join user as cuser on cuser.id=personnel.created_by
					left join user as uuser on uuser.id=personnel.updated_by";

	// Setup sort and search SQL using posted data
	$sortSql = "order by $sortname $sortorder";
	$searchSql = ($qtype != '' && $query != '') ? "where $qtype like '%$query%'" : '';


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

		

			
	//print_r($sql);
	$results = mysql_query($sql);
	$line = 1;
	$type = 'Courier';
	while ($obj = mysql_fetch_object($results)) {
		$firstname = utfEncode($obj->first_name);
		$lastname = utfEncode($obj->last_name);
		$type = utfEncode($obj->type);
		$contact = utfEncode($obj->contact_number);
		$position = utfEncode($obj->position);
		$createdby = utfEncode($obj->created_by);
		$createddate = $obj->created_date;
		$updatedby = utfEncode($obj->updated_by);
		$updateddate = $obj->updated_date;
		$flag = $obj->active_flag;
		$flagdesc = $obj->activeflag;
		$id = $obj->id;
		$editbtn = (userAccess(USERID,'.editpersonnelbtn')==false)?"<img src='../resources/flexigrid/images/edit.png' rowid='$id' firstname='$firstname' lastname='$lastname' type='$type' position='$position' contact='$contact' title='Edit Personnel' activeflag='$flag' class='editpersonnelbtn pointer' data-toggle='modal' href='#editpersonnelmodal' height='20px'>":'';

		

		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													 $editbtn,
													 $id, 
													 $flagdesc,
													 $firstname,
													 $lastname,
													 $contact,
													 $position,
													 $type,
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