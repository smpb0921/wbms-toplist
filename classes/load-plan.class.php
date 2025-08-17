<?php
require_once('table.class.php');
class txn_load_plan extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $load_plan_number;
	var $status;
	var $manifest_number;
	var $location_id;
	var $carrier_id;
	var $origin_id;
	var $destination_id;
	var $mode_of_transport_id;
	var $agent_id;
	var $mawbl_bl;
	var $document_date;
	var $eta;
	var $etd;
	var $remarks;
	var $created_date;
	var $created_by;
	var $updated_date;
	var $updated_by;
	var $last_status_update_remarks;
	var $vehicle_type_id;

}

?>