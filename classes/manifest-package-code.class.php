<?php
require_once('table.class.php');
class txn_manifest_waybill_package_code extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $manifest_number;
	var $waybill_number;
	var $package_code;
	var $created_date;
	var $created_by;

}

?>