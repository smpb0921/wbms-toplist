<?php
require_once('table.class.php');
class txn_waybill_handling_instruction extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $waybill_number;
	var $handling_instruction_id;
}

?>