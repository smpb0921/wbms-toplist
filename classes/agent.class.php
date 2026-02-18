<?php
require_once('table.class.php');
class agent extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $code;
	var $company_name;
	var $company_street_address;
	var $company_district;
	var $company_city;
	var $company_state_province;
	var $company_zip_code;
	var $company_country;
	var $area;
	var $remarks;
	var $created_by;
	var $created_date;
	var $updated_by;
	var $updated_date;
	var $shipment_type_id;

}

?>