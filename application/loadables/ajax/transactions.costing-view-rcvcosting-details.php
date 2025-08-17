<?php
	include('../../../config/connection.php');
	include('../../../config/functions.php');




	$page = 1;	// The current page
	$sortname = '';	// Sort column
	$sortorder = '';	// Sort order
	$qtype = '';	// Search column
	$query = '';	// Search string

	$txnID = isset($_GET['txnid'])?escapeString($_GET['txnid']):'';

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

	$customqry = "  select costing_waybill.id,
	                       costing_waybill.waybill_number,
	                       costing_waybill.amount,
	                       costing_waybill.created_date,
	                       costing_waybill.created_by,
	                       txn_waybill.waybill_number,
	                       concat(cuser.first_name,' ',cuser.last_name) as createdby,
	                       costing.amount as costingamount,
                           ifnull(txn_waybill.package_actual_weight,0) as actualweight,
                           ifnull(waybillpackages.volweight,0) as volweight
					from costing_waybill
					left join costing on costing.id=costing_waybill.costing_id
					left join txn_waybill on txn_waybill.waybill_number=costing_waybill.waybill_number
					left join (
						select waybill_number,
								ifnull(sum(volumetric_weight),0) as volweight,
								ifnull(sum(actual_weight),0) as actualweight
						from txn_waybill_package_dimension
						where waybill_number in (select waybill_number from costing_waybill where costing_id='$txnID')
						group by txn_waybill_package_dimension.waybill_number
					) as waybillpackages on waybillpackages.waybill_number=costing_waybill.waybill_number
					left join user as cuser on cuser.id=costing_waybill.created_by
					where costing_waybill.costing_id='$txnID'";

				
/* case 
                                        when '$targetweight'='ACTUAL' then round((($grossamount/$actualvolweight)*ifnull(txn_waybill.package_actual_weight,0)),2)
                                        else round((($grossamount/$actualvolweight)*ifnull(waybillpackages.volweight,0)),2)
                                end as distributedamount*/


	// Setup sort and search SQL using posted data
	$sortSql = "order by $sortname $sortorder";
	$searchSql = ($qtype != '' && $query != '') ? "and $qtype like '%$query%'" : '';
	


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
	$line = 1;
	//$hidebtn = (userAccess(USERID,'.edittranstypebtn')==false)?'':'hidden';

	$line = $line+(($page-1)*$rp);

	while ($obj = mysql_fetch_object($results)) {
		

		$id = utfEncode($obj->id);
		$waybillnumber = utfEncode($obj->waybill_number);
		$amount = utfEncode($obj->amount);
		$actualweight = utfEncode($obj->actualweight);
		$volweight = utfEncode($obj->volweight);
		$createdby = utfEncode($obj->createdby);
		$createddate = dateFormat($obj->created_date,'m/d/Y h:i:s A');

		$amount = convertWithDecimal($amount,4);



		/*$editbtn = (hasAccess(USERID,ACCESSEDITRCV)==1&&(USERID==$obj->txncreator||hasAccess(USERID,ACCESSOTHERSRCV)==1)&&strtoupper($obj->status)=='LOGGED')?"<img class='pointer txnstockreceiptdetail-editbtn' src='../resources/flexigrid/images/edit.png' rowid='$id' height='20px'>":'';*/

		$rowcheckbox = "<input type='checkbox' class='viewrcvcosting-checkbox valignmiddle' rowid='$obj->id'>";

		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													 $rowcheckbox,
													 $id,
													 $waybillnumber,
													 $actualweight,
													 $volweight,
													 $amount,
													 $createddate,
													 $createdby
													 
													 
													),
									'rowAttr'=>array(
													   'rowid'=>$id
													)
								);
		$line++;

	}
	echo json_encode($data);
?>