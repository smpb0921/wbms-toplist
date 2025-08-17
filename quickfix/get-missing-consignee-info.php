<?php
	ini_set('max_execution_time', 30000);
	include("../config/connection.php");
	include("../config/functions.php");



	$rs = query("select * from txn_waybill where consignee_account_number='' or consignee_account_number is null");

	while($obj=fetch($rs)){
		$rowid = $obj->id;
		$waybillnumber = $obj->waybill_number;
		$consigneeid = $obj->consignee_id;


		$getconsigneeinfo = query("select * from consignee where id='$consigneeid'");

		while($conobj=fetch($getconsigneeinfo)){
			$accountnumber = $conobj->account_number;
			$accountname = $conobj->account_name;
			$companyname = $conobj->company_name;
			$street = $conobj->company_street_address;
			$district = $conobj->company_district;
			$city = $conobj->company_city;
			$region = $conobj->company_state_province;
			$zip = $conobj->company_zip_code;
			$country = $conobj->company_country;

			query("update txn_waybill
				   set consignee_account_number='$accountnumber',
				       consignee_account_name='$accountname',
				       consignee_tel_number='-',
				       consignee_company_name='$companyname',
				       consignee_street_address='$street',
				       consignee_district='$district',
				       consignee_city='$city',
				       consignee_state_province='$region',
				       consignee_zip_code='$zip',
				       consignee_country='$country'
				   where id='$rowid'");

			echo "Update Consignee Info: $waybillnumber($rowid) <br>";

		}


	}
?>