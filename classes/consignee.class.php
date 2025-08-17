<?php
require_once('table.class.php');
class consignee extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $account_number;
	var $account_name;
	var $company_name;
	var $company_street_address;
	var $company_district;
	var $company_city;
	var $company_state_province;
	var $company_zip_code;
	var $company_country;
	var $created_by;
	var $created_date;
	var $updated_by;
	var $updated_date;
	var $inactive_flag;
	var $id_number;

}

?>