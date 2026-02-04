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

	$customqry = "
				    select movement_type.id,
						   movement_type.code, 
						   movement_type.description,
						   movement_type.created_date,
						   concat(cuser.first_name,' ',cuser.last_name) as created_by,
						   movement_type.updated_date,
						   concat(uuser.first_name,' ',uuser.last_name) as updated_by,
						   group_concat(source_movement) as source_movement,
						   movement_type.shipment_type_id,
						   shipment_type.code as shipmenttype
					from movement_type
					left join movement_type_source on movement_type_source.movement_type_id=movement_type.id
					left join user as cuser on cuser.id=movement_type.created_by
					left join user as uuser on uuser.id=movement_type.updated_by
					left join shipment_type on shipment_type.id=movement_type.shipment_type_id
					group by movement_type.id
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

		

			
	$results = mysql_query($sql);
	$line = 1;
	while ($obj = mysql_fetch_object($results)) {
		$code = utfEncode($obj->code);
		$desc = utfEncode($obj->description);
		$sourcemovement = utfEncode($obj->source_movement);
		$shipmenttype = utfEncode($obj->shipmenttype);
		$createdby = utfEncode($obj->created_by);
		$createddate = $obj->created_date;
		$updatedby = utfEncode($obj->updated_by);
		$updateddate = $obj->updated_date;
		$id = $obj->id;

		$editbtn = (userAccess(USERID,'.editmovementtypebtn')==false)?"<img src='../resources/flexigrid/images/edit.png' rowid='$id' title='Edit Service' class='editmovementtypebtn pointer' data-toggle='modal' href='#editmovementtypemodal' height='20px'>":'';
		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													 $editbtn,
													 $id, 
													 $code,
													 $desc,
													 $sourcemovement,
													 $shipmenttype,
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