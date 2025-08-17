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
	$sql = "select * from transaction_type $searchSql";
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
					select transaction_type.id,
						   transaction_type.code, 
						   transaction_type.description,
						   transaction_type.prefix,
						   transaction_type.next_number_series,
						   concat(first_name,' ',last_name) as updated_by,
						   transaction_type.updated_date
					from transaction_type
					left join user on user.id=transaction_type.updated_by
				 ) as tbl
			$searchSql
			$sortSql
			$limitSql";

	$results = mysql_query($sql);
	$line = 1;
	$hidebtn = (userAccess(USERID,'.edittranstypebtn')==false)?'':'hidden';
	while ($obj = mysql_fetch_object($results)) {
		$id = $obj->id;
		$code = utfEncode($obj->code);
		$desc = utfEncode($obj->description);
		$prefix = $obj->prefix;
		$num = $obj->next_number_series;
		$updatedby = utfEncode($obj->updated_by);
		$updateddate = $obj->updated_date;

		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													"<img src='../resources/flexigrid/images/edit.png' rowid='$id' code='$code' desc='$desc' prefix='$prefix' numseries='$num' title='Edit Transaction Type' class='edittranstypebtn pointer $hidebtn' data-toggle='modal' href='#edittransactiontypemodal' height='20px'>",
													 $id, 
													 $code,
													 $desc,
													 $prefix,
													 $num,
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