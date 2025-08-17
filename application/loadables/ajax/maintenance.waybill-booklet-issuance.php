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
	$sql = "select * from waybill_booklet_issuance $searchSql";
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
			        select waybill_booklet_issuance.id,
						   waybill_booklet_issuance.issuance_date, 
						   waybill_booklet_issuance.validity_date,
						   waybill_booklet_issuance.issued_to,
						   waybill_booklet_issuance.location_id,
						   location.description as location,
						   location.code as location_code,
						   waybill_booklet_issuance.booklet_start_series,
						   waybill_booklet_issuance.booklet_end_series,
						   waybill_booklet_issuance.remarks,
						   waybill_booklet_issuance.created_date,
						   concat(cuser.first_name,' ',cuser.last_name) as created_by,
						   waybill_booklet_issuance.updated_date,
						   concat(uuser.first_name,' ',uuser.last_name) as updated_by,
						   waybill_booklet_issuance.courier_flag,
						   waybill_booklet_issuance.shipper_id,
						   ifnull(shipper.account_name,'N/A') as shipper,
						   ifnull(waybill_booklet_issuance.courier,'N/A') as courier,
						   case 
						   		when waybill_booklet_issuance.courier_flag=1 then 'YES'
						   		when waybill_booklet_issuance.courier_flag=0 then 'NO'
						   		else 'UNDEFINED'
						   end as courierflag
					from waybill_booklet_issuance
					left join location on location.id=waybill_booklet_issuance.location_id
					left join user as cuser on cuser.id=waybill_booklet_issuance.created_by
					left join user as uuser on uuser.id=waybill_booklet_issuance.updated_by
					left join shipper on shipper.id=waybill_booklet_issuance.shipper_id
				  ) as tbl
			$searchSql
			$sortSql
			$limitSql";

		

			
	$results = mysql_query($sql);
	$line = 1;
	while ($obj = mysql_fetch_object($results)) {
		$issuancedate = $obj->issuance_date;
		$validitydate = $obj->validity_date;
		$issuedto = $obj->issued_to;
		$location = $obj->location;
		$startseries = $obj->booklet_start_series;
		$endseries = $obj->booklet_end_series;
		$createdby = $obj->created_by;
		$createddate = $obj->created_date;
		$updatedby = $obj->updated_by;
		$updateddate = $obj->updated_date;
		$remarks = $obj->remarks;
		$id = $obj->id;

		$editbtn = (userAccess(USERID,'.editwaybillbookletissuancebtn')==false)?"<img src='../resources/flexigrid/images/edit.png' rowid='$id' title='Edit Waybill Booklet' class='editwaybillbookletissuancebtn pointer' data-toggle='modal' href='#editwaybillbookletissuancemodal' height='20px'>":'';
		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													 $editbtn,
													 $id, 
													 $issuancedate,
													 $validitydate,
													 $issuedto,
													 utfEncode($location),
													 utfEncode($obj->courierflag),
													 utfEncode($obj->shipper),
													 utfEncode($obj->courier),
													 $startseries,
													 $endseries,
													 utfEncode($remarks)

													),
									'rowAttr'=>array(
													   'rowid'=>$id
													)
								);
		$line++;
	}
	echo json_encode($data);
?>