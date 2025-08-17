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
				    select agent.id,
				    	   agent.code,
				           agent.company_name,
				           agent.company_street_address,
				           agent.company_district,
				           agent.company_city,
				           agent.company_state_province,
				           agent.company_zip_code,
				           agent.company_country,
				           agent.area,
				           agent.remarks,
				           agent.created_date,
						   concat(cuser.first_name,' ',cuser.last_name) as created_by,
						   agent.updated_date,
						   concat(uuser.first_name,' ',uuser.last_name) as updated_by
				    from agent
					left join user as cuser on cuser.id=agent.created_by
					left join user as uuser on uuser.id=agent.updated_by
					order by agent.company_name asc
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
		$code = $obj->code;
		$companyname = utfEncode($obj->company_name);
		$street = utfEncode($obj->company_street_address);
		$district = utfEncode($obj->company_district);
		$city = utfEncode($obj->company_city);
		$region = utfEncode($obj->company_state_province);
		$zip = utfEncode($obj->company_zip_code);
		$country = utfEncode($obj->company_country);
		$area = utfEncode($obj->area);
		$remarks = utfEncode($obj->remarks);
		$createdby = utfEncode($obj->created_by);
		$createddate = $obj->created_date;
		$updatedby = utfEncode($obj->updated_by);
		$updateddate = $obj->updated_date;

		$editbtn = (userAccess(USERID,'.editagentbtn')==false)?"<img src='../resources/flexigrid/images/edit.png' rowid='$id' title='Edit Agent' class='editagentbtn pointer' data-toggle='modal' href='#editagentmodal' height='20px'>":'';

		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													$editbtn,
													 $id, 
													 $code,
													 $companyname,
													 $area,
													 $remarks,
													 $street,
													 $district,
													 $city,
													 $region,
													 $zip,
													 $country,
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