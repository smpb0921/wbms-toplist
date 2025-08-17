<?php
require_once('table.class.php');
class shipper_rate_handling_instruction extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $shipper_rate_id;
	var $handling_instruction_id;
	var $percentage_flag;
	var $percentage;
	var $fixed_charge;
	var $created_date;
	var $created_by;
	var $updated_date;
	var $updated_by;


}

?>