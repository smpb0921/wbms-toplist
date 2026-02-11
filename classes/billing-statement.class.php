<?php
require_once('table.class.php');
class txn_billing extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $status;
	var $billing_number;
	var $document_date;
	var $payment_due_date;
	var $remarks;
	var $shipper_id;
	var $bill_to_account_number;
	var $bill_to_account_name;
	var $bill_to_company_name;
	var $company_street_address;
	var $company_district;
	var $company_city;
	var $company_state_province;
	var $company_zip_code;
	var $company_country;
	var $billing_street_address;
	var $billing_district;
	var $billing_city;
	var $billing_state_province;
	var $billing_zip_code;
	var $billing_country;
	var $billing_contact_person;
	var $phone;
	var $mobile;
	var $email;
	var $created_by;
	var $created_date;
	var $updated_by;
	var $updated_date;
	var $attention;
	var $invoice;
	var $vat_flag;
	var $billing_type_id;
	var $account_executive_id;
	var $shipment_type_id;

}

?>