<?php
	include('../../../config/connection.php');
	include('../../../config/functions.php');




	$page = 1;	// The current page
	$sortname = '';	// Sort column
	$sortorder = '';	// Sort order
	$qtype = '';	// Search column
	$query = '';	// Search string

    $bookingnumber = isset($_GET['bookingnumber'])?escapeString($_GET['bookingnumber']):'';
   

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

    $filter = array();
    array_push($filter,"txn_booking_attachments.booking_number='$bookingnumber'");
	if($qtype != '' && $query != ''){
        array_push($filter,"$qtype like '%$query%'");
    }
    $searchSql = '';
    if(count($filter)>0){
        $searchSql = " where ".implode(" and ",$filter);
    }

	$customqry = "
				    select txn_booking_attachments.id,
				    	   txn_booking_attachments.file_name,
                           txn_booking_attachments.booking_number,
                           date_format(txn_booking_attachments.created_date,'%m/%d/%Y %h:%i:%s %p') as created_date,
						   concat(cuser.first_name,' ',cuser.last_name) as createdby
				    from txn_booking_attachments
					left join user as cuser on cuser.id=txn_booking_attachments.created_by
                    
				";


	// Get total count of records
	$sql = "$customqry $searchSql";
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
	$sql = "$customqry
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
		

		$downloadbtn = (hasAccess(USERID,'#booking-trans-editbtn')==1)?"<a href='../application/attachments/".$obj->file_name."' download ><img src='../resources/flexigrid/images/download.png' rowid='".$obj->id."' title='Download' class='bookingattachmentsdownloadbtn pointer' height='16px'></a>":'';

        $removebtn = (hasAccess(USERID,'#booking-trans-editbtn')==1)?"<img src='../resources/flexigrid/images/trash.png' rowid='".$obj->id."' title='Remove Attachment' class='bookingattachmentsremovebtn pointer' attachmentid='".$obj->id."' filename='".$obj->file_name."' height='16px'>":'';

		$data['rows'][] = array(
									'id' => $obj->id,
									'cell' => array(
													 $downloadbtn.' '.$removebtn,
													 utfEncode($obj->file_name), 
													 utfEncode($obj->created_date),
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