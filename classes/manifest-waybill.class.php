<?php
require_once('table.class.php');
class txn_manifest_waybill extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $manifest_number;
	var $waybill_number;
	var $created_date;
	var $created_by;
	var $pouch_size_id;

}

?>