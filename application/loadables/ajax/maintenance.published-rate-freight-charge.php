<?php
	include('../../../config/connection.php');
	include('../../../config/functions.php');

	$publishedrateid = isset($_GET['publishedrateid'])?trim($_GET['publishedrateid']," "):'';


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
				    select published_rate_freight_charge.id,
                           published_rate_freight_charge.published_rate_id,
                           published_rate_freight_charge.from_kg,
                           published_rate_freight_charge.to_kg,
                           published_rate_freight_charge.freight_charge,
                           published_rate_freight_charge.excess_weight_charge,
                           published_rate_freight_charge.created_date,
						   concat(cuser.first_name,' ',cuser.last_name) as created_by,
						   published_rate_freight_charge.updated_date,
						   concat(uuser.first_name,' ',uuser.last_name) as updated_by
				    from published_rate_freight_charge
				    left join user as cuser on cuser.id=published_rate_freight_charge.created_by
					left join user as uuser on uuser.id=published_rate_freight_charge.updated_by
				    where published_rate_id='$publishedrateid'
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
		$fromkg = utfEncode($obj->from_kg);
		$tokg = utfEncode($obj->to_kg);
		$freightcharge = utfEncode($obj->freight_charge);
		$excessweightcharge = utfEncode($obj->excess_weight_charge);

		$checkbox = "<input type='checkbox' class='viewpublishedratefreightcharge-checkbox' rowid='$id'>";

		$editbtn = (userAccess(USERID,'.editpublishedratebtn')==false)?"<img src='../resources/flexigrid/images/edit.png' rowid='$id' fromkg='$fromkg' tokg='$tokg' freightcharge='$freightcharge' excessweightcharge='$excessweightcharge' title='Edit Published Rate - Freight Charge' class='editpublishedratefreightchargebtn pointer' height='20px'>":'';
		

		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													 $checkbox,
													 $editbtn,
													 $fromkg,
													 $tokg,
													 $freightcharge,
													 $excessweightcharge
													 

													),
									'rowAttr'=>array(
													   'rowid'=>$id
													)
								);
		$line++;
	}
	echo json_encode($data);
?>