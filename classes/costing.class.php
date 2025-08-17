<?php
require_once('table.class.php');
class costing extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $chart_of_accounts_id;
	var $amount;
	var $reference;
	var $prf_number;
	var $date;
	var $created_date;
	var $created_by;
	var $updated_date;
	var $updated_by;
	var $payee_id;
	var $payee_address;
	var $vatable_amount;
	var $is_vatable;
	var $vat_amount;
	

}

?>