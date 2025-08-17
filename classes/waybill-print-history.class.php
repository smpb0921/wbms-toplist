<?php
require_once('table.class.php');
class txn_waybill_print_history extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $waybill_number;
	var $print_counter;
	var $printed_by;
	var $printed_date;

}

?>