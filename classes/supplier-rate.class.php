<?php
require_once('table.class.php');
class supplier_rate extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $origin_id;
	var $destination_id;
	var $mode_of_transport_id;
	var $freight_computation;
	var $fixed_rate_flag;
	var $valuation;
	var $freight_rate;
	var $insurance_rate;
	var $fuel_rate;
	var $bunker_rate;
	var $minimum_rate;
	var $created_by;
	var $created_date;
	var $updated_by;
	var $updated_date;
	var $rush_flag;
	var $pull_out_flag;
	var $waybill_type;
	var $pouch_size_id;
	var $fixed_rate_amount;
	var $pull_out_fee;
	var $oda_rate;
	var $services_id;
	var $zone_id;
	var $third_party_logistic_id;
	var $shipment_type_id;

}

?>