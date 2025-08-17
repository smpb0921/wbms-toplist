<?php
require_once('table.class.php');
class agent_contact extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $agent_id;
	var $contact_name;
	var $phone_number;
	var $email_address;
	var $mobile_number;
	var $created_date;
	var $default_flag;

}

?>