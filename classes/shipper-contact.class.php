<?php
require_once('table.class.php');
class shipper_contact extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $shipper_id;
	var $contact_name;
	var $phone_number;
	var $email_address;
	var $mobile_number;
	var $created_date;
	var $default_flag;
	var $send_sms_flag;
	var $send_email_flag;

}

?>