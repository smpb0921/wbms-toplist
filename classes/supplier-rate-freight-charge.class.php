<?php
require_once('table.class.php');
class supplier_rate_freight_charge extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $supplier_rate_id;
	var $from_kg;
	var $to_kg;
	var $freight_charge;
	var $excess_weight_charge;
	var $created_date;
	var $created_by;
	var $updated_date;
	var $updated_by;


}

?>