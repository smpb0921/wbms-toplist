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

	$useronlycondition = "where tb.status='POSTED'";
	$checkuserviewconrs = mysql_query("select * from user_rights where user_id='".USERID."' and menu_id='dashuseronly'");
	if(mysql_num_rows($checkuserviewconrs)>0) {
		$useronlycondition = "where tb.status='POSTED' and 
        tb.created_by=".USERID;
	}


    $customqry =   "SELECT
							tb.id,
							tb.remarks,
							tb.invoice,
							tb.status,
							tb.vat_flag,
							format(tb.total_vatable_charges,2) as total_vatable_charges,
							format(tb.total_non_vatable_charges,2) as total_non_vatable_charges,
							billing_number,
							bill_to_account_name,
							date_format(document_date,'%m/%d/%y') doc_date,
							format(gross,2) gross,
							format(vat,2) vat,
							format((total_vatable_charges+total_non_vatable_charges+vat),2) total_amount,
							format(net,2) net,
							ifnull(date_format(received_date,'%m/%d/%y'),'Double Click Row to Receive') received_date,
							ifnull(received_by,'') received_by,
							ifnull(date_format(payment_due_date,'%m/%d/%y'),'N/A') due_date,
							if(now()>payment_due_date,1,0) Due,
							ifnull(if(length(s.credit_term)>0,s.credit_term,30),30) term
					from txn_billing tb 
					left join user as cuser on cuser.id=tb.posted_by
					left join shipper s on s.id = tb.shipper_id
					";

	// Setup sort and search SQL using posted data
	$sortSql = "order by $sortname  $sortorder ";
	$searchSql = ($qtype != '' && $query != '') ? "where $qtype like '%$query%' " : '';


	// Get total count of records
	$sql = "select * from ( {$customqry} ) as tbl $searchSql";
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
	$sql = "
			select tbl.id,
			       tbl.remarks,
				   tbl.invoice,
				   tbl.status,
				   tbl.total_vatable_charges,
				   tbl.total_non_vatable_charges,
				   tbl.billing_number,
				   tbl.bill_to_account_name,
				   tbl.doc_date,
				   tbl.vat,
				   tbl.total_amount,
				   tbl.received_date,
				   tbl.received_by,
				   tbl.due_date,
				   tbl.Due,
				   tbl.term,
				   format(sum(txn_billing_waybill.regular_charges+txn_billing_waybill.other_charges_vatable+txn_billing_waybill.other_charges_non_vatable+IF(tbl.vat_flag=1,txn_billing_waybill.vat,0)),2) as cancelledamount
			from (
				$customqry
				$searchSql
				$sortSql
	            $limitSql
	        ) as tbl
	        left join txn_billing_waybill on txn_billing_waybill.billing_number=tbl.billing_number and txn_billing_waybill.flag=0
	        group by tbl.billing_number
	        $sortSql
	        ";
            

    $results = mysql_query($sql);
    
    //echo $sql;
    // echo mysql_error();
	$line = 1;
	while ($obj = mysql_fetch_object($results)) {
			$cancelledamount =  $obj->status=='VOID'?$obj->total_amount:$obj->cancelledamount;
			$revisedamount = $obj->status=='VOID'?0:(str_replace(',', '', $obj->total_amount)-str_replace(',', '', $obj->cancelledamount));
			$revisedamount = convertWithDecimal($revisedamount,2);
		
		$data['rows'][] = array(
									'id' => $obj->id,
									'cell' =>  array(
                                                     $line,
													 $obj->billing_number,
													 utfEncode($obj->invoice), 

													 $obj->bill_to_account_name,
													 utfEncode($obj->status),
													 utfEncode($obj->doc_date),
													 utfEncode($obj->total_vatable_charges),
													 utfEncode($obj->total_non_vatable_charges),
													 utfEncode($obj->vat),
													 utfEncode($obj->total_amount),
													 utfEncode($cancelledamount),
													 utfEncode($revisedamount),
													 (strlen(utfEncode($obj->received_date)) <= 0 ? "N/A" : $obj->received_date),
													 trim(implode("<br>",explode(" ",utfEncode($obj->received_by)))),
                                                     utfEncode($obj->due_date),
                                                     utfEncode($obj->remarks)
													),
									'rowAttr'=>array(
													   'billing_number'=>$obj->billing_number,
													   'dayterm'=>$obj->term,
                                                       'class'=>"dash-billing-row pointer	 ".($obj->Due ? "bg-danger" : "")
													)
								);
		$line++;
	}
    print_r(json_encode($data));
    