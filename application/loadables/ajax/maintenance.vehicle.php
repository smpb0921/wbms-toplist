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


	$customqry =   "select vehicle.id,
						   vehicle.plate_number, 
						   vehicle.model, 
						   vehicle.year,
						   vehicle.vehicle_type_id,
						   vehicle.created_date,
						   concat(cuser.first_name,' ',cuser.last_name) as created_by,
						   vehicle.updated_date,
						   concat(uuser.first_name,' ',uuser.last_name) as updated_by,
						   vehicle.active_flag,
						   case 
						   		when vehicle.active_flag='1' then 'ACTIVE'
						   		else 'INACTIVE'
						   end as activeflag,
						   vehicle_type.description as vehicletype
					from vehicle
					left join user as cuser on cuser.id=vehicle.created_by
					left join user as uuser on uuser.id=vehicle.updated_by
					left join vehicle_type on vehicle_type.id=vehicle.vehicle_type_id";

	// Setup sort and search SQL using posted data
	$sortSql = "order by $sortname $sortorder";
	$searchSql = ($qtype != '' && $query != '') ? "where $qtype like '%$query%'" : '';


	// Get total count of records
	$sql = "select * from ($customqry) as tbl $searchSql";
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
	$sql = "select * from ($customqry) as tbl
			$searchSql
			$sortSql
			$limitSql";

		

			
	$results = mysql_query($sql);
	$line = 1;
	$type = 'Courier';
	while ($obj = mysql_fetch_object($results)) {
		$platenumber = utfEncode($obj->plate_number);
		$model = utfEncode($obj->model);
		$year = utfEncode($obj->year);
		$vehicletypeid = utfEncode($obj->vehicle_type_id);
		$vehicletype = utfEncode($obj->vehicletype);
		$createdby = utfEncode($obj->created_by);
		$createddate = $obj->created_date;
		$updatedby = utfEncode($obj->updated_by);
		$updateddate = $obj->updated_date;
		$flag = $obj->active_flag;
		$flagdesc = $obj->activeflag;
		$id = $obj->id;
		$editbtn = (userAccess(USERID,'.editvehiclebtn')==false)?"<img src='../resources/flexigrid/images/edit.png' rowid='$id' platenumber='$platenumber' model='$model' vehicletype='$vehicletype' vehicletypeid='$vehicletypeid' year='$year' title='Edit Vehicle' activeflag='$flag' class='editvehiclebtn pointer' data-toggle='modal' href='#editvehiclemodal' height='20px'>":'';

		

		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													 $editbtn,
													 $id, 
													 $flagdesc,
													 $platenumber,
													 $model,
													 $year,
													 $vehicletype,
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