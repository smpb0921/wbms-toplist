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

	$waybill = isset($_GET['waybill'])?escapeString($_GET['waybill']):'';


	$customqry = "
				    select costing.id,
                           costing.chart_of_accounts_id, 
                           ifnull(costing_waybill.amount,0) as amount,
                           costing.reference,
                           costing.prf_number,
                           date_format(costing.date,'%m/%d/%Y') as date,
                           date_format(costing.created_date,'%m/%d/%Y %h:%i%:%s %p') as created_date,
                           date_format(costing.updated_date,'%m/%d/%Y %h:%i%:%s %p') as updated_date,
                           concat(cuser.first_name,' ',cuser.last_name) as createdby,
                           concat(uuser.first_name,' ',uuser.last_name) as updatedby,
                           chart_of_accounts.description as chartofaccounts,
                           expense_type.description as typeofaccount
                    from costing_waybill 
                    left join costing on costing_waybill.costing_id=costing.id
                    left join chart_of_accounts on chart_of_accounts.id=costing.chart_of_accounts_id
                    left join expense_type on expense_type.id=chart_of_accounts.expense_type_id
                    left join user as cuser on cuser.id=costing.created_by
                    left join user as uuser on uuser.id=costing.updated_by
                    left join txn_waybill on txn_waybill.waybill_number=costing_waybill.waybill_number
                    where costing_waybill.waybill_number='$waybill'
				    
				";



	

    $sortSql = "order by $sortname $sortorder";
    $searchSql = ($qtype != '' && $query != '') ? " and $qtype like '%$query%'" : '';

  
    

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
		$id = utfEncode($obj->id);
        $type = utfEncode($obj->typeofaccount);
        $chartofaccounts = utfEncode($obj->chartofaccounts);
        $reference = utfEncode($obj->reference);
        $prfnumber = utfEncode($obj->prf_number);
        $amount = utfEncode($obj->amount);
        $date = utfEncode($obj->date);
        $createdby = utfEncode($obj->createdby);
        $createddate = utfEncode($obj->created_date);
        $updatedby = utfEncode($obj->updatedby);
        $updateddate = utfEncode($obj->updated_date);

        $amount = convertWithDecimal($amount,4);

       
        $data['rows'][] = array(
                                    'id' => $id,
                                    'cell' => array(
                                                     $date,
                                                     $type,
                                                     $chartofaccounts,
                                                     $reference,
                                                     $prfnumber,
                                                     $amount
                                                     
                                                     
                                                    ),
                                    'rowAttr'=>array(
                                                       'rowid'=>$id
                                                    )
                                );
        $line++;
	}
	echo json_encode($data);
?>