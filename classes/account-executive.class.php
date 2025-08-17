<?php
require_once('table.class.php');
class account_executive extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $code;
	var $name;
	var $email_address;
	var $mobile_number;
	var $username;
	var $password;
	var $created_by;
	var $created_date;
	var $updated_by;
	var $updated_date;

}

?>