<?php
require_once('table.class.php');
class vehicle extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $vehicle_type_id;
	var $plate_number;
	var $model;
	var $year;
	var $created_by;
	var $created_date;
	var $updated_by;
	var $updated_date;
	var $active_flag;


}

?>