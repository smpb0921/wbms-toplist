<?php
require_once('table.class.php');
class txn_billing_waybill extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $billing_number;
	var $waybill_number;
	var $created_date;
	var $created_by;
	var $amount;
	var $origin_id;
	var $destination_id;
	var $mode_of_transport_id;
	var $chargeable_weight;
	var $regular_charges;
	var $other_charges_vatable;
	var $other_charges_non_vatable;
	var $vat;

}

?>