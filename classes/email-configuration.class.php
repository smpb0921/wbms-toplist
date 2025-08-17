<?php
require_once('table.class.php');
class email_configuration extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $host;
	var $username;
	var $password;
	var $port;
	var $email_sent_from;
	var $email_sender;
	var $encryption;
	var $active_flag;

}

?>