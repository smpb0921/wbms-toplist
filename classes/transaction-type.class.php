<?php
require('table.class.php');
class Transaction_type extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $code;
	var $description;
	var $prefix;
	var $next_number_series;
	var $updated_by;
	var $updated_date;




}

?>