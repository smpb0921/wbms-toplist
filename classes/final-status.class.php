<?php
require_once('table.class.php');
class no_update_status extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $status;
	var $created_by;
	var $created_date;
	var $updated_by;
	var $updated_date;

}

?>