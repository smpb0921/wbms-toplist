<?php
require_once('table.class.php');
class company_information extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $company_name;
	var $company_address;
	var $city;
	var $state_province;
	var $country;
	var $postal_code;
	var $primary_color;
	var $secondary_color;
	var $tax_identification_number;

}

?>