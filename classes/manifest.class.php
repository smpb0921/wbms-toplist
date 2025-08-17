<?php
require_once('table.class.php');
class txn_manifest extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $manifest_number;
	var $status;
	var $document_date;
	var $load_plan_number;
	var $remarks;
	var $created_date;
	var $created_by;
	var $updated_date;
	var $updated_by;
	var $last_status_update_remarks;
	var $trucker_name;
	var $truck_type;
	var $plate_number;
	var $driver_name;
	var $contact_number;
	var $load_plan_flag;
	var $location_id;
	var $origin_id;
	var $mode_of_transport_id;
	var $agent_id;
	var $mawbl;
	var $eta;
	var $etd;

}

?>