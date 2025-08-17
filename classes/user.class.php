<?php
require_once('table.class.php');
class user extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $first_name;
	var $middle_name;
	var $last_name;
	var $username;
	var $password;
	var $email_address;
	var $active_flag;
	var $location_id;
	var $created_date;
	var $created_by;
	var $updated_date;
	var $updated_by;
	var $admin_flag;
	var $booking_approver_flag;
	var $user_group_id;
	var $mobile;
	var $courier_supervisor_flag;
	var $freight_supervisor_flag;





}

?>