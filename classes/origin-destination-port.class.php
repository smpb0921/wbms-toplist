<?php
require_once('table.class.php');
class origin_destination_port extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $code;
	var $description;
	var $created_by;
	var $created_date;
	var $updated_by;
	var $updated_date;
	var $country_id;
	var $zone_id;
	var $lead_time;

}

?>