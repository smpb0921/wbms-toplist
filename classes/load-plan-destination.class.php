<?php
require_once('table.class.php');
class txn_load_plan_destination extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $load_plan_number;
	var $origin_destination_port_id;
	var $created_by;
}

?>