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
	


	$customqry = "
				   select txn_billing.id,
				          txn_billing.billing_number,
				          txn_billing.document_date,
				          txn_billing.shipper_id,
				          txn_billing.status,
						  txn_billing.paid_flag,
						  txn_billing.invoice,
				          case 
				          	 when txn_billing.paid_flag='1' then 'YES'
				          	 when txn_billing.paid_flag='0' then 'NO'
				          	 else ''
				          end as paidflag,
				          txn_billing.created_date,
				          concat(user.first_name,' ',user.last_name) as created_by,
				          shipper.account_name,
				          group_concat(txn_billing_waybill.waybill_number separator ', ') as waybill,
				          count(distinct waybill_number) as waybillcount
				   from txn_billing
				   left join user on user.id=txn_billing.created_by
				   left join shipper on shipper.id=txn_billing.shipper_id
				   left join txn_billing_waybill on txn_billing_waybill.billing_number=txn_billing.billing_number
				    
				";

		

	/****** FILTER VARS ****/
	
	$filter = array();
	$paidflag = isset($_GET['paidflag'])?escapeString(strtoupper($_GET['paidflag'])):'';
	$status = isset($_GET['status'])?escapeString(strtoupper($_GET['status'])):'';
	$shipper = isset($_GET['shipper'])?escapeString(strtoupper(trim($_GET['shipper']))):'';
	$waybillnumber = isset($_GET['waybillnumber'])?escapeString(strtoupper(trim($_GET['waybillnumber']))):'';
	$bsnumber = isset($_GET['bsnumber'])?escapeString(strtoupper(trim($_GET['bsnumber']))):'';
	$docdatefrom = isset($_GET['docdatefrom'])?escapeString(strtoupper(trim($_GET['docdatefrom']," "))):'';
	$docdateto = isset($_GET['docdateto'])?escapeString(strtoupper(trim($_GET['docdateto']," "))):'';
	if($docdatefrom!=''){
        $docdatefrom = date('Y-m-d', strtotime($docdatefrom));
    }
    if($docdateto!=''){
        $docdateto = date('Y-m-d', strtotime($docdateto));
    }
	/** FILTER VARS - END **/

	if($qtype != '' && $query != ''){
		array_push($filter,"$qtype like '%$query%'");
	} 

	if($paidflag!=''&&$paidflag!='NULL'){
      array_push($filter,"txn_billing.paid_flag='$paidflag'");
    }
	if($status!=''&&$status!='NULL'){
      array_push($filter,"txn_billing.status='$status'");
    }
    if($waybillnumber!=''&&$waybillnumber!='NULL'){
    	array_push($filter, "upper(txn_billing_waybill.waybill_number)='$waybillnumber'");
    }
	if($bsnumber!=''&&$bsnumber!='NULL'){
    	array_push($filter, "upper(txn_billing.invoice) like '%$bsnumber%'");
    }
    if($shipper!=''&&$shipper!='NULL'){
    	array_push($filter, "txn_billing.shipper_id='$shipper'");
    }
    if($docdatefrom!=''&&$docdateto!=''){
        array_push($filter,"date(txn_billing.document_date) <= '$docdateto' and date(txn_billing.document_date) >= '$docdatefrom'");
    }
    else if($docdatefrom==''&&$docdateto!=''){
        array_push($filter,"date(txn_billing.document_date) <= '$docdateto'");
    }
    else if($docdatefrom!=''&&$docdateto==''){
         array_push($filter,"date(txn_billing.document_date) >= '$docdatefrom'");
    }

    $condition = '';
    if(count($filter)>0){
    	$condition = ' where '.implode(" and ", $filter);
    }

  
    $customqry = $customqry.$condition." group by txn_billing.billing_number asc";

    //echo $customqry;

	// Get total count of records
	$sql = "$customqry";
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
			$sortSql
			$limitSql";

			//echo $sql;

		

			
	$results = mysql_query($sql);
	if(!$results){
		echo $sql;
	}
	$line = 1;
	while ($obj = mysql_fetch_object($results)) {
		$createddate = dateFormat($obj->created_date,'m/d/Y h:i:s A');
		$docdate = dateFormat($obj->document_date,'m/d/Y');
		$data['rows'][] = array(
									'id' => $obj->id,
									'cell' => array( 
													 utfEncode($obj->billing_number),
													 utfEncode($obj->invoice),
													 utfEncode($obj->status),
													 utfEncode($obj->paidflag),
													 utfEncode($obj->account_name),
													 $docdate,
													 convertWithDecimal($obj->waybillcount,0),
													 utfEncode($obj->created_by),
													 $createddate,
													 $obj->id

													),
									'rowAttr'=>array(
													   'rowid'=>$obj->id,
													   'billingnumber'=>$obj->billing_number,
													   "class"=>'billingstatementsearchrow pointer'
													)
								);
		$line++;
	}

	echo json_encode($data);
?>