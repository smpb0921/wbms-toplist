<?php
require_once('table.class.php');
class waybill_booklet_issuance extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $issuance_date;
	var $validity_date;
	var $issued_to;
	var $location_id;
	var $booklet_start_series;
	var $booklet_end_series;
	var $remarks;
	var $created_by;
	var $created_date;
	var $updated_by;
	var $updated_date;
	var $courier_flag;
	var $shipper_id;
	var $courier;

}

?>