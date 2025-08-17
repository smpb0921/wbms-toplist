<?php
require_once('table.class.php');
class shipper_rate extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $shipper_id;
	var $shipper_rate_code;
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
	var $created_date;
	var $created_by;
	var $updated_date;
	var $updated_by;
	var $rush_flag;
	var $pull_out_flag;
	var $waybill_type;
	var $pouch_size_id;
	var $fixed_rate_amount;
	var $pull_out_fee;
	var $oda_rate;
	var $services_id;
	var $return_document_fee;
	var $waybill_fee;
	var $security_fee;
	var $doc_stamp_fee;
	var $freight_charge_computation;
	var $collection_fee_percentage;
	var $express_transaction_type;
	var $ad_valorem_flag;
	var $insurance_rate_computation;
	var $excess_amount;
	var $parcel_type_id;
	var $cbm_computation;


}

?>