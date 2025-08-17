<?php
require_once('table.class.php');
class district_city_zipcode extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $district_barangay;
	var $city;
	var $zip_code;
	var $origin_destination_port_id;
	var $created_by;
	var $created_date;
	var $updated_by;
	var $updated_date;
	var $oda_flag;
	var $oda_rate;
	var $lead_time;

}

?>