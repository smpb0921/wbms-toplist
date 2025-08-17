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

	$customqry1 = "  select count(id) as rowcount
					 from system_log";

	$customqry = "  select system_log.id,
						   system_log.module, 
						   system_log.description,
						   system_log.query,
						   system_log.date_source as created_date,
						   concat(first_name,' ',last_name) as created_by
					from system_log
					left join user on user.id=system_log.user_source";

	// Setup sort and search SQL using posted data
	$sortSql = "order by $sortname $sortorder";
	if($qtype != ''&&strtolower($qtype)=='module'&&$query!=''){
		$searchSql = "where $qtype='$query'";
	}
	else{
		$searchSql = ($qtype != '' && $query != '') ? "where $qtype like '%$query%'" : '';
	}


	// Get total count of records
	$sql = "$customqry1 $searchSql";
	$result = mysql_query($sql);
	$total = 0;
	while($obj=fetch($result)){
		$total = $obj->rowcount;
	}
	//$total = mysql_num_rows($result);

	
	// Setup paging SQL
	$pageStart = ($page-1)*$rp;
	$limitSql = "limit $pageStart, $rp";

	$sortSql = "order by $sortname $sortorder";
	// Return JSON data
	$data = array();
	$data['page'] = $page;
	$data['total'] = $total;
	$data['rows'] = array();
	$sql = "$customqry
			$searchSql
			$sortSql
			$limitSql";

	$results = mysql_query($sql);
	$line = 1;
	//$hidebtn = (userAccess(USERID,'.edittranstypebtn')==false)?'':'hidden';
	while ($obj = mysql_fetch_object($results)) {
		$id = utfEncode($obj->id);
		$module = utfEncode($obj->module);
		$desc = utfEncode($obj->description);
		$qry = utfEncode($obj->query);
		$createdby = utfEncode($obj->created_by);
		$createddate = utfEncode($obj->created_date);

		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													 $id, 
													 $module,
													 $desc,
													 $qry,
													 $createdby,
													 $createddate
													),
									'rowAttr'=>array(
													   'rowid'=>$id
													)
								);
		$line++;
	}
	echo json_encode($data);
?>