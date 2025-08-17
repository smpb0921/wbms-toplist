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

	$tempcon = 'where user.id!=1';
	if(USERID==1){
		$tempcon = '';
	}

	$customqry = "
				   select user.id,
				          user.first_name,
				          user.last_name,
				          user.middle_name,
				          user.username,
				          user.created_date,
				          user.updated_date,
				          user.location_id,
				          user.email_address,
				          user.active_flag,
				          user.user_group_id,
				          concat(cuser.first_name,' ',cuser.last_name) as created_by,
						  concat(uuser.first_name,' ',uuser.last_name) as updated_by,
						  location.description as location,
						  user_group.description as usergroup,
						  user.mobile,
						  user.courier_supervisor_flag,
						  user.freight_supervisor_flag,
						  case 
						  		when user.courier_supervisor_flag=1 then 'YES'
						  		else 'NO'
						  end as couriersupervisor,
						  case 
						  		when user.freight_supervisor_flag=1 then 'YES'
						  		else 'NO'
						  end as freightsupervisor 
				   from user
				   left join user as cuser on cuser.id=user.created_by
				   left join user as uuser on uuser.id=user.updated_by
				   left join user_group on user_group.id=user.user_group_id
				   left join location on location.id=user.location_id
				   $tempcon
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
	while ($obj = mysql_fetch_object($results)) {
		$id = $obj->id;
		$editbtn = (userAccess(USERID,'.edituserbtn')==false||USERID==1)?"<img src='../resources/flexigrid/images/edit.png' rowid='$id' title='Edit User' class='edituserbtn pointer' data-toggle='modal' href='#editusermodal' mobile='".utfEncode($obj->mobile)."' couriersupervisor='".utfEncode($obj->courier_supervisor_flag)."' freightsupervisor='".utfEncode($obj->freight_supervisor_flag)."' fname='".utfEncode($obj->first_name)."' mname='".utfEncode($obj->middle_name)."' lname='".utfEncode($obj->last_name)."' username='".utfEncode($obj->username)."' locid='".utfEncode($obj->location_id)."' locname='".utfEncode($obj->location)."' email='".utfEncode($obj->email_address)."' activeflag='$obj->active_flag' usergroupid='$obj->user_group_id' usergroup='".utfEncode($obj->usergroup)."' height='20px'>":'';

		$resetpasswordbtn = (userAccess(USERID,'.resetpasswordbtn')==false||USERID==1)?"<img src='../resources/img/reset-password.png' rowid='$id' title='Reset User Password' class='resetpasswordbtn pointer' fname='".utfEncode($obj->first_name)."' mname='".utfEncode($obj->middle_name)."' lname='".utfEncode($obj->last_name)."' username='".utfEncode($obj->username)."' height='20px'>":'';

		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													 $editbtn.' '.$resetpasswordbtn,
													 $id, 
													 utfEncode($obj->first_name),
													 utfEncode($obj->middle_name),
													 utfEncode($obj->last_name),
													 utfEncode($obj->username),
													 utfEncode($obj->location),
													 utfEncode($obj->usergroup),
													 utfEncode($obj->couriersupervisor),
													 utfEncode($obj->freightsupervisor),
													 utfEncode($obj->mobile),
													 utfEncode($obj->created_by),
													 $obj->created_date,
													 utfEncode($obj->updated_by),
													 $obj->updated_date,

													),
									'rowAttr'=>array(
													   'rowid'=>$id

													)
								);
		$line++;
	}
	echo json_encode($data);
?>