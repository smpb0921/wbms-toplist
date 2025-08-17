<?php
require_once('table.class.php');
class txn_booking extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $booking_number;
	var $status;
	var $origin_id;
	var $destination_id;
	var $pickup_date;
	var $remarks;
	var $created_date;
	var $created_by;
	var $updated_date;
	var $updated_by;
	var $shipper_id;
	var $shipper_account_number;
	var $shipper_name;
	var $shipper_tel_number;
	var $shipper_company_name;
	var $shipper_street_address;
	var $shipper_district;
	var $shipper_city;
	var $shipper_state_province;
	var $shipper_zip_code;
	var $shipper_country;
	var $shipper_pickup_street_address;
	var $shipper_pickup_district;
	var $shipper_pickup_city;
	var $shipper_pickup_state_province;
	var $shipper_pickup_zip_code;
	var $shipper_pickup_country;
	var $consignee_id;
	var $consignee_account_number;
	var $consignee_name;
	var $consignee_tel_number;
	var $consignee_company_name;
	var $consignee_street_address;
	var $consignee_district;
	var $consignee_city;
	var $consignee_state_province;
	var $consignee_zip_code;
	var $consignee_country;
	var $package_number_of_packages;
	var $package_declared_value;
	var $package_actual_weight;
	var $package_cbm;
	var $package_service;
	var $package_mode_of_transport;
	var $package_handling_instruction;
	var $package_pay_mode;
	var $package_amount;
	var $shipment_description;
	var $package_vw;
	var $unit_of_measure;
	var $shipper_mobile;
	var $shipper_contact;
	var $package_document;
	var $location_id;
	var $same_day_pickup_flag;
	var $driver_contact_number;
	var $bill_to;
	var $driver;
	var $helper;
	var $vehicle_type_id;
	var $plate_number;
	var $time_ready;
	

}

?>