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

	


	$customqry = "
				    select costing.id,
                           costing.chart_of_accounts_id, 
                           costing.amount,
                           costing.reference,
                           costing.prf_number,
                           costing.payee_id,
                           costing.vatable_amount,
                           costing.vat_amount,
                           costing.is_vatable,
                           costing.payee_address,
                           payee.payee_name,
                           payee.tin,
                           case when costing.is_vatable=1 then 'YES'
                           else 'NO' end as vatflag,
                           date_format(costing.date,'%m/%d/%Y') as date,
                           date_format(costing.created_date,'%m/%d/%Y %h:%i%:%s %p') as created_date,
                           date_format(costing.updated_date,'%m/%d/%Y %h:%i%:%s %p') as updated_date,
                           concat(cuser.first_name,' ',cuser.last_name) as createdby,
                           concat(uuser.first_name,' ',uuser.last_name) as updatedby,
                           chart_of_accounts.description as chartofaccounts,
                           ifnull(count(distinct costing_waybill.waybill_number),0) as waybillcount,
                           expense_type.description as typeofaccount
                    from costing 
                    left join payee on payee.id=costing.payee_id
                    left join chart_of_accounts on chart_of_accounts.id=costing.chart_of_accounts_id
                    left join expense_type on expense_type.id=chart_of_accounts.expense_type_id
                    left join user as cuser on cuser.id=costing.created_by
                    left join user as uuser on uuser.id=costing.updated_by
                    left join costing_waybill on costing_waybill.costing_id=costing.id
                    left join txn_waybill on txn_waybill.waybill_number=costing_waybill.waybill_number
				    
				";



	/****** FILTER VARS ****/
	$having = array();
	$filter = array();
	$typeofaccount = isset($_GET['typeofaccount'])?escapeString(strtoupper($_GET['typeofaccount'])):'';
	$reference = isset($_GET['reference'])?escapeString(strtoupper($_GET['reference'])):'';
    $prfnumber = isset($_GET['prfnumber'])?escapeString(strtoupper($_GET['prfnumber'])):'';
    $chartofaccounts = isset($_GET['chartofaccounts'])?escapeString(strtoupper($_GET['chartofaccounts'])):'';
    $waybillnumber = isset($_GET['waybillnumber'])?escapeString(strtoupper($_GET['waybillnumber'])):'';
    $payee = isset($_GET['payee'])?escapeString(strtoupper($_GET['payee'])):'';
   
    $rcvcondition = '';
    $rcv = explode(",", $waybillnumber);

    if(count($rcv)>0){
        for ($i=0; $i <count($rcv); $i++) { 
            $rcv[$i] = trim($rcv[$i]);
        }

        $rcvcondition = "'".implode("','", $rcv)."'";
    }
    

    $costingdatefrom = isset($_GET['costingdatefrom'])?escapeString(strtoupper(trim($_GET['costingdatefrom']," "))):'';
	$costingdateto = isset($_GET['costingdateto'])?escapeString(strtoupper(trim($_GET['costingdateto']," "))):'';
	if($costingdatefrom!=''){
        $costingdatefrom = date('Y-m-d', strtotime($costingdatefrom));
    }
    if($costingdateto!=''){
        $costingdateto = date('Y-m-d', strtotime($costingdateto));
    }

    $createddatefrom = isset($_GET['createddatefrom'])?escapeString(strtoupper(trim($_GET['createddatefrom']," "))):'';
    $createddateto = isset($_GET['createddateto'])?escapeString(strtoupper(trim($_GET['createddateto']," "))):'';
    if($createddatefrom!=''){
        $createddatefrom = date('Y-m-d', strtotime($createddatefrom));
    }
    if($createddateto!=''){
        $createddateto = date('Y-m-d', strtotime($createddateto));
    }
	/** FILTER VARS - END **/




    if($reference!=''&&$reference!='NULL'){
        array_push($filter, "costing.reference like '%$reference%'");
    }
    if($prfnumber!=''&&$prfnumber!='NULL'){
        array_push($filter, "costing.prf_number like '%$prfnumber%'");
    }
    if($typeofaccount!=''&&$typeofaccount!='NULL'){
        array_push($filter, "chart_of_accounts.expense_type_id='$typeofaccount'");
    }
    if($chartofaccounts!=''&&$chartofaccounts!='NULL'){
        array_push($filter, "costing.chart_of_accounts_id='$chartofaccounts'");
    }
    if($payee!=''&&$payee!='NULL'){
        array_push($filter, "costing.payee_id='$payee'");
    }
    if($waybillnumber!=''&&$waybillnumber!='NULL'){
        array_push($filter, "costing.id in (    select costing_id
                                                from costing_waybill
                                                where costing_waybill.waybill_number in ($rcvcondition)

                                            )");
    }


    if($costingdatefrom!=''&&$costingdateto!=''){
        array_push($filter,"date(costing.date)>='$costingdatefrom' and date(costing.date)<='$costingdateto'");
    }
    else if($costingdatefrom==''&&$costingdateto!=''){
        array_push($filter,"date(costing.date) <= '$costingdateto'");
    }
    else if($costingdatefrom!=''&&$costingdateto==''){
         array_push($filter,"date(costing.date) >= '$costingdatefrom'");
    }
    

    if($createddatefrom!=''&&$createddateto!=''){
        array_push($filter,"date(costing.created_date)>='$createddatefrom' and date(costing.created_date)<='$createddateto'");
    }
    else if($createddatefrom==''&&$createddateto!=''){
        array_push($filter,"date(costing.created_date) <= '$createddateto'");
    }
    else if($createddatefrom!=''&&$createddateto==''){
         array_push($filter,"date(costing.created_date) >= '$createddatefrom'");
    }


    // MY MODIFICATION for $searchSql
    if($qtype!=''&&$query!=''){
    	if($qtype=='createdby'){
    		array_push($having,"$qtype like '%$query%'");
    	}
    	else{
    		array_push($filter,"$qtype like '%$query%'");
    	}
    }
    //////////////


    $condition = '';
    if(count($filter)>0){
    	$condition = ' where '.implode(" and ", $filter);
    }

    // Setup sort and search SQL using posted data
	$sortSql = " order by $sortname $sortorder ";




    $condition2 = '';
    if(count($having)>0){
    	$condition2 = ' having '.implode(" and ", $having);
    }


    $customqry = $customqry.$condition.$condition2." group by costing.id";

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
		$id = utfEncode($obj->id);
        $type = utfEncode($obj->typeofaccount);
        $chartofaccounts = utfEncode($obj->chartofaccounts);
        $reference = utfEncode($obj->reference);
        $prfnumber = utfEncode($obj->prf_number);
        $payeename = utfEncode($obj->payee_name);
        $payeeaddress = utfEncode($obj->payee_address);
        $payeetin = utfEncode($obj->tin);
        $vatableamount = utfEncode($obj->vatable_amount);
        $vatamount = utfEncode($obj->vat_amount);
        $vatflag = utfEncode($obj->vatflag);
        $amount = utfEncode($obj->amount);
        $date = utfEncode($obj->date);
        $createdby = utfEncode($obj->createdby);
        $createddate = utfEncode($obj->created_date);
        $updatedby = utfEncode($obj->updatedby);
        $updateddate = utfEncode($obj->updated_date);
        $waybillcount = utfEncode($obj->waybillcount);

        $amount = convertWithDecimal($amount,4);

        $rowcheckbox = "<input type='checkbox' class='txncosting-checkbox valignmiddle' rowid='$obj->id'>";

        $editbtn = (userAccess(USERID,'.editcostingbtn')==false)?"<img src='../resources/flexigrid/images/edit.png' rowid='$id' title='Edit Costing' class='editcostingbtn pointer' height='20px'> ":'';
        $printbtn = "<img src='../resources/img/print.png' rowid='$id' prfnumber='$prfnumber' title='Print' class='printcostingbtn pointer' height='20px'>";



        /*$addbtn = (userAccess(USERID,'.addcostingrcvbtn')==false)?"<img src='../resources/flexigrid/images/add.png' rowid='$id' title='Add Stock Receipt' class='addcostingrcvbtn pointer' height='20px'>":'';*/

        $viewrcvbtn = (userAccess(USERID,'.viewcostingrcvbtn')==false)?"<img src='../resources/flexigrid/images/search.png' rowid='$id' title='View' class='viewcostingrcvbtn pointer' height='20px'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;":'';

        $data['rows'][] = array(
                                    'id' => $id,
                                    'cell' => array(
                                                     $rowcheckbox,
                                                     $printbtn." ".$editbtn,
                                                     $id,
                                                     $date,
                                                     $type,
                                                     $chartofaccounts,
                                                     $payeename,
                                                     $payeeaddress,
                                                     $payeetin,
                                                     $vatflag,
                                                     $amount,
                                                     $vatableamount,
                                                     $vatamount,
                                                     $reference,
                                                     $prfnumber,
                                                     $viewrcvbtn.$waybillcount,
                                                     $createddate,
                                                     $createdby,
                                                     $updateddate,
                                                     $updatedby
                                                     
                                                     
                                                    ),
                                    'rowAttr'=>array(
                                                       'rowid'=>$id
                                                    )
                                );
        $line++;
	}
	echo json_encode($data);
?>