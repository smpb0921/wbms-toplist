<?php
require_once('table.class.php');
class txn_booking_status_history extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $booking_number;
	var $status_description;
	var $date;
	var $contact;
	var $remarks;
	var $supervisor;
	var $supervisor_mobile_number;
	var $driver;
	var $driver_mobile_number;
	var $assigned_by;
	var $time_ready;
	var $created_date;
	var $created_by;
	var $driver_flag;

}

?>