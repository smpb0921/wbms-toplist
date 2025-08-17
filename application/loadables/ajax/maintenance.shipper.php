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
				    select shipper.id,
				    	   shipper.account_number,
				    	   shipper.account_name,
				           shipper.company_name,
				           shipper.company_street_address,
				           shipper.company_district,
				           shipper.company_city,
				           shipper.company_state_province,
				           shipper.company_zip_code,
				           shipper.company_country,
				           shipper.billing_street_address,
				           shipper.billing_district,
				           shipper.billing_city,
				           shipper.billing_state_province,
				           shipper.billing_zip_code,
				           shipper.billing_country,
				           shipper.pickup_street_address,
				           shipper.pickup_district,
				           shipper.pickup_city,
				           shipper.pickup_state_province,
				           shipper.pickup_zip_code,
				           shipper.pickup_country,
				           case
							   when shipper.inactive_flag=1 then 'YES'
							   else 'NO'
						   end as inactive_flag,
						   case
							   when shipper.non_pod_flag=1 then 'YES'
							   else 'NO'
						   end as non_pod_flag,
						   case
							   when shipper.vat_flag=1 then 'YES'
							   else 'NO'
						   end as vat_flag,
						   case
							   when shipper.on_hold_flag=1 then 'YES'
							   else 'NO'
						   end as on_hold_flag,
				           shipper.created_date,
						   concat(cuser.first_name,' ',cuser.last_name) as created_by,
						   shipper.updated_date,
						   concat(uuser.first_name,' ',uuser.last_name) as updated_by,
						   concat(billinguser.first_name,' ',billinguser.last_name) as billing_in_charge,
						   account_executive.name as account_executive
				    from shipper
					left join user as cuser on cuser.id=shipper.created_by
					left join user as uuser on uuser.id=shipper.updated_by
					left join user as billinguser on billinguser.id=shipper.billing_in_charge
					left join account_executive on account_executive.id=shipper.account_executive
					order by shipper.account_name asc
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
		$accountnumber = $obj->account_number;
		$accountname = $obj->account_name;
		$companyname = $obj->company_name;
		$billingincharge = $obj->billing_in_charge;
		$accountexecutive = $obj->account_executive;
		$nonpodflag = $obj->non_pod_flag;
		$vatflag = $obj->vat_flag;
		$onholdflag = $obj->on_hold_flag;
		$companystreet = $obj->company_street_address;
		$companydistrict = $obj->company_district;
		$companycity = $obj->company_city;
		$companyregion = $obj->company_state_province;
		$companyzip = $obj->company_zip_code;
		$companycountry = $obj->company_country;
		$billingstreet = $obj->billing_street_address;
		$billingdistrict = $obj->billing_district;
		$billingcity = $obj->billing_city;
		$billingregion = $obj->billing_state_province;
		$billingzip = $obj->billing_zip_code;
		$billingcountry = $obj->billing_country;
		$pickupstreet = $obj->pickup_street_address;
		$pickupdistrict = $obj->pickup_district;
		$pickupcity = $obj->pickup_city;
		$pickupregion = $obj->pickup_state_province;
		$pickupzip = $obj->pickup_zip_code;
		$pickupcountry = $obj->pickup_country;
		$createdby = $obj->created_by;
		$createddate = $obj->created_date;
		$updatedby = $obj->updated_by;
		$updateddate = $obj->updated_date;
		$inactiveflag = $obj->inactive_flag;


		$editbtn = (userAccess(USERID,'.editshipperbtn')==false)?"<img src='../resources/flexigrid/images/edit.png' rowid='$id' title='Edit Shipper' class='editshipperbtn pointer' data-toggle='modal' href='#editshippermodal' height='20px'>":'';

		$attachmentbtn = (userAccess(USERID,'.viewshipperattachmentbtn')==false)?"&nbsp;<img src='../resources/flexigrid/images/attachment.png' rowid='$id' title='View Attachments' class='viewshipperattachmentbtn pointer' data-toggle='modal' href='#viewshipperattachmentmodal' height='20px'>":'';

		$shipperratebtn = '';//(userAccess(USERID,'.viewshipperratebtn')==false)?"&nbsp;<img src='../resources/img/rate.png' rowid='$id' title='View Shipper Rate' class='viewshipperratebtn pointer' data-toggle='modal' href='#viewshipperratemodal' accountname='$accountname' height='20px'>":'';

		$invoicebtn = (userAccess(USERID,'.viewshipperinvoicebtn')==false)?"&nbsp;<img src='../resources/img/invoice.png' title='View Shipper Invoices' class='viewshipperinvoicebtn pointer' shipperid='$id' accountname='$accountname' height='20px'>":'';

		$emailbtn = (userAccess(USERID,'.viewshippercsemailbtn')==false)?"&nbsp;<img src='../resources/img/email.png' title='View Shipper CS Email' class='viewshippercsemailbtn pointer' shipperid='$id' accountname='$accountname' height='20px'>":'';


		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													 $editbtn.$attachmentbtn.$shipperratebtn.$invoicebtn.$emailbtn,
													 $id, 
													 utfEncode($accountnumber),
													 utfEncode($accountname),
													 utfEncode($companyname),
													 $inactiveflag,
													 utfEncode($billingincharge),
													 utfEncode($accountexecutive),
													 $nonpodflag,
													 $vatflag,
													 utfEncode($createdby),
													 $createddate,
													 utfEncode($updatedby),
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