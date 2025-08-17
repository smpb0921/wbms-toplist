<?php
require_once('table.class.php');
class payee extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $payee_name;
	var $address;
    var $tin;
	var $created_by;
	var $created_date;
	var $updated_by;
	var $updated_date;

}

?>