<?php
require_once('table.class.php');
class shipper_pickup_address extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $shipper_id;
	var $default_flag;
	var $pickup_street_address;
	var $pickup_district;
	var $pickup_city;
	var $pickup_state_province;
	var $pickup_zip_code;
	var $pickup_country;

}

?>