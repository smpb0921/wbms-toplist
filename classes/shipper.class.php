<?php
require_once('table.class.php');
class shipper extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $inactive_flag;
	var $account_number;
	var $account_name;
	var $company_name;
	var $company_street_address;
	var $company_district;
	var $company_city;
	var $company_state_province;
	var $company_zip_code;
	var $company_country;
	var $billing_street_address;
	var $billing_district;
	var $billing_city;
	var $billing_state_province;
	var $billing_zip_code;
	var $billing_country;
	var $billing_in_charge;
	var $account_executive;
	var $non_pod_flag;
	var $vat_flag;
	var $status;
	var $created_by;
	var $created_date;
	var $updated_by;
	var $updated_date;
	var $pay_mode_id;
	var $business_style;
	var $credit_limit;
	var $credit_term;
	var $tin;
	var $line_of_business;
	var $collection_contact_person;
	var $billing_cut_off;
	var $collection_day;
	var $collection_location;
	var $pod_instruction;
	var $return_document_fee;
	var $waybill_fee;
	var $security_fee;
	var $doc_stamp_fee;
	
}

?>