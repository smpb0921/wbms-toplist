<?php
	include('../../../config/connection.php');
	include('../../../config/functions.php');

	$shipperrateid = isset($_GET['shipperrateid'])?trim($_GET['shipperrateid']," "):'';


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
				    select shipper_rate_handling_instruction.id,
                           shipper_rate_handling_instruction.shipper_rate_id,
                           shipper_rate_handling_instruction.handling_instruction_id,
                           shipper_rate_handling_instruction.percentage_flag,
                           shipper_rate_handling_instruction.percentage,
                           shipper_rate_handling_instruction.fixed_charge,
                           handling_instruction.description as handlinginstruction,
                           case 
                           		when percentage_flag=1 then 'Percentage'
                           		when percentage_flag=0 then 'Fixed Charge'
                           		else 'N/A'
                           end as type,
                           shipper_rate_handling_instruction.created_date,
						   concat(cuser.first_name,' ',cuser.last_name) as created_by,
						   shipper_rate_handling_instruction.updated_date,
						   concat(uuser.first_name,' ',uuser.last_name) as updated_by
				    from shipper_rate_handling_instruction
				    left join handling_instruction on handling_instruction.id=shipper_rate_handling_instruction.handling_instruction_id
				    left join user as cuser on cuser.id=shipper_rate_handling_instruction.created_by
					left join user as uuser on uuser.id=shipper_rate_handling_instruction.updated_by
				    where shipper_rate_id='$shipperrateid'
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
	$line = 1;
	while ($obj = mysql_fetch_object($results)) {

		$id = $obj->id;
		$handlinginstruction = utfEncode($obj->handlinginstruction);
		$type = utfEncode($obj->type);
		$percentage = utfEncode($obj->percentage);
		$fixedcharge = utfEncode($obj->fixed_charge);

		$checkbox = "<input type='checkbox' class='viewshipperratehandlinginstruction-checkbox' rowid='$id'>";

		//$editbtn = (userAccess(USERID,'.editshipperratebtn')==false)?"<img src='../resources/flexigrid/images/edit.png' rowid='$id' title='Edit Shipper Rate' shipperid='$shipperid' class='editshipperratebtn pointer' height='20px'>":'';
		$editbtn = '';

		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													 $checkbox,
													 //$editbtn,
													 $handlinginstruction,
													 $type,
													 $percentage,
													 $fixedcharge
													 

													),
									'rowAttr'=>array(
													   'rowid'=>$id
													)
								);
		$line++;
	}
	echo json_encode($data);
?>