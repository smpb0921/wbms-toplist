<?php
require_once('table.class.php');
class txn_waybill_billing_history extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $billing_flag;
	var $waybill_number;
	var $reference;
	var $billing_number;
	var $remarks;
	var $created_date;
	var $created_by;

}

?>