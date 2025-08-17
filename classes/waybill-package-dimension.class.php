<?php
require_once('table.class.php');
class txn_waybill_package_dimension extends table{
	var $id;
	var $waybill_number;
	var $length;
	var $width;
	var $height;
	var $quantity;
	var $volumetric_weight;
	var $cbm;
	var $uom;
	var $actual_weight;

}
?>