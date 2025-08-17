<?php
require_once('table.class.php');
class txn_waybill_status_history extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $waybill_number;
	var $status_code;
	var $status_description;
	var $remarks;
	var $created_date;
	var $created_by;
	var $location_id;
	var $source;
	var $source_type;
	var $received_by;
	var $received_date;
	var $personnel_id;

}

?>