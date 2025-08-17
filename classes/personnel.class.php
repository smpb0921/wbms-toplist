<?php
require_once('table.class.php');
class personnel extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $first_name;
	var $last_name;
	var $position;
	var $type;
	var $contact_number;
	var $created_by;
	var $created_date;
	var $updated_by;
	var $updated_date;
	var $active_flag;


}

?>