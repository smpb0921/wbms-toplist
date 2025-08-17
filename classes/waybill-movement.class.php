<?php
require_once('table.class.php');
class txn_waybill_movement extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $waybill_movement_number;
	var $status;
	var $location_id;
	var $remarks;
	var $document_date;
	var $created_date;
	var $created_by;
	var $updated_date;
	var $updated_by;
	var $movement_type_id;

}

?>