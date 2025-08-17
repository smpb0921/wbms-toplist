<?php
	include('../../../config/connection.php');
	include('../../../config/functions.php');

	$shipperid = isset($_GET['shipper'])?trim($_GET['shipper']," "):'';


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
				    select shipper_attachments.id,
				           shipper_attachments.filename,
				           shipper_attachments.description,
				           shipper_attachments.created_date,
				           shipper_attachments.created_by,
				           shipper_attachments.system_filename,
				           concat(first_name,' ',last_name) as createdby
				    from shipper_attachments
					left join user as cuser on cuser.id=shipper_attachments.created_by
					where shipper_id='$shipperid'
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
			

			/*$editbtn = (userAccess(USERID,'.editshipperbtn')==false)?"<img src='../resources/flexigrid/images/edit.png' rowid='$id' title='Edit Shipper' class='editshipperbtn pointer' data-toggle='modal' href='#editshippermodal' height='20px'>":'';

			*/


			$data['rows'][] = array(
										'id' => $obj->id,
										'cell' => array(
														 "<input type='checkbox' class='viewshipperattachmentmodal-checkbox' filename='$obj->system_filename' rowid='$obj->id'>",
														 "<form action='../attachments/download.php' method='post'>
																	<input type='hidden' name='file' value='$obj->system_filename'>
																	<input class='btn btn-xs mybtn' type='submit' value='Download' name='downloadbtn' style='background-color:#FFE5A0'>
														</form>", 
														 utfEncode($obj->filename),
														 utfEncode($obj->description),
														 dateFormat($obj->created_date,'m/d/Y h:i:s A'),
														 utfEncode($obj->createdby)

														),
										'rowAttr'=>array(
														   'rowid'=>$obj->id
														)
									);
			$line++;
		}
	
	echo json_encode($data);
?>