<?php

include("../../../config/connection.php");
include("../../../config/checklogin.php");
include("../../../config/functions.php");
require('../../../resources/htmltopdf/fpdf.php');
include("../../../classes/company-information.class.php");

$txnnumber = isset($_GET['txnnumber'])?escapeString($_GET['txnnumber']):'';

class PDF extends FPDF
{	
	function Footer()
	{
	    $this->SetY(-8);
	    $this->setfont('helvetica','',8);
	    $this->Cell(200,3,'PAGE '.$this->PageNo().' of {nb}',0,0,'R');
	}
}

$pdf = new PDF('P','mm','LETTER');
$pdf->AliasNbPages();
$pdf->AddPage();
$now = date('m/d/Y h:i:s A');

$imgpath = '../../../barcode/';
$imagelogo = "../../../resources/logicorelogo.png"; // Should be ONE logo

$pdf->SetTextColor(0, 0, 0);

// Fetch booking data
$rs = query("SELECT txn_booking.id,
                     txn_booking.booking_number,
                     origintbl.description as origin,
                     destinationtbl.description as destination,
                     txn_booking.pickup_date,
                     txn_booking.remarks,
                     txn_booking.shipper_account_number,
                     txn_booking.shipper_name,
                     txn_booking.shipper_tel_number,
                     txn_booking.shipper_company_name,
                     txn_booking.shipper_street_address,
                     txn_booking.shipper_district,
                     txn_booking.shipper_city,
                     txn_booking.shipper_state_province,
                     txn_booking.shipper_zip_code,
                     txn_booking.shipper_country,
                     txn_booking.package_number_of_packages,
                     txn_booking.package_actual_weight,
                     txn_booking.package_cbm,
                     txn_booking.package_declared_value,
                     txn_booking.unit_of_measure,
                     txn_booking.shipper_pickup_state_province,
                     txn_booking.shipper_pickup_street_address,
                     txn_booking.shipper_pickup_district,
                     txn_booking.shipper_pickup_city,
                     txn_booking.shipper_pickup_zip_code,
                     txn_booking.shipper_pickup_country,
                     services.description as services,
                     group_concat(distinct accompanying_documents.description separator ', ') as documents,
                     group_concat(distinct handling_instruction.description separator ', ') as handlinginstruction,
                     mode_of_transport.description as modeoftransport,
                     txn_booking.package_pay_mode,
                     txn_booking.shipment_description,
					 txn_booking.time_ready,
					 vt.description vehicle_type_desc,
					 concat(u.first_name,' ',u.last_name) created_by
					 
 	          from txn_booking 
 	          left join origin_destination_port as origintbl on origintbl.id=txn_booking.origin_id
 	          left join origin_destination_port as destinationtbl on destinationtbl.id=txn_booking.destination_id
 	          left join services on services.id=txn_booking.package_service
 	          left join mode_of_transport on mode_of_transport.id=txn_booking.package_mode_of_transport
 	          left join txn_booking_document on txn_booking_document.booking_number=txn_booking.booking_number
 	          left join txn_booking_handling_instruction on txn_booking_handling_instruction.booking_number=txn_booking.booking_number
 	          left join accompanying_documents on accompanying_documents.id=txn_booking_document.accompanying_document_id
 	          left join handling_instruction on handling_instruction.id=txn_booking_handling_instruction.handling_instruction_id
			  left join vehicle_type vt on vt.id = txn_booking.vehicle_type_id
			  left join user u on u.id = txn_booking.created_by
 	          where txn_booking.booking_number='$txnnumber'
 	          group by txn_booking.booking_number
 	        ");

if(getNumRows($rs)==1){

	$obj = fetch($rs);
	
	// Constants for layout
	$lineHeight = 4.5;
	$labelFont = 8;
	$valueFont = 8;
	$headerFont = 10;
	$titleFont = 14;
	
	// ==================== HEADER SECTION ====================
	
	// Logo (if available)
	if(file_exists($imagelogo)){
		$pdf->Image($imagelogo, 15, 6, 35);
	}
	
	// Title
	$pdf->SetY(10);
	$pdf->SetX(60);
	$pdf->SetFont('helvetica','B',$titleFont);
	$pdf->Cell(90, 8, 'Booking Receipt Notice', 0, 0, 'C');
	
	// Date and Page info (top right)
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->SetXY(160, 10);
	$pdf->Cell(40, 4, date('d M y H:i:s'), 0, 1, 'R');
	$pdf->SetX(160);
	$pdf->Cell(40, 4, 'Page : 1/2', 0, 0, 'R');
	
	// Line separator
	$pdf->SetY(22);
	$pdf->Line(10, 22, 205, 22);
	
	// ==================== TO/FROM SECTION ====================
	
	$pdf->SetY(25);
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(12, $lineHeight, 'To', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(5, $lineHeight, ':', 0, 0);
	$pdf->MultiCell(0, $lineHeight, $obj->shipper_name . ' / ' . $obj->shipper_company_name . ' (Tel:' . $obj->shipper_tel_number . ') / Fax:', 0, 'L');
	
	$pdf->SetX(10);
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(12, $lineHeight, 'From', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(5, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, 'Ocean Network Express Philippines, Inc. - Manila / Fatmah Bangoola (TEL: )', 0, 1);
	
	$pdf->SetX(27);
	$pdf->SetFont('helvetica','I', $labelFont - 1);
	$pdf->Cell(0, $lineHeight, '"We received a booking order listed as follows. Please review following items and advise us of any discrepancy"', 0, 1);
	
	// Line separator
	$y = $pdf->GetY();
	$pdf->Line(10, $y, 205, $y);
	$pdf->Ln(1);
	
	// ==================== BOOKING INFORMATION SECTION ====================
	
	// Row 1: Booking No, Booking Ref No, Booking Date
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'Booking No', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, $obj->booking_number, 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'Booking Ref. No.', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, isset($obj->booking_ref_no) ? $obj->booking_ref_no : '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'Booking Date', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, dateFormat($obj->pickup_date,'dM/y'), 0, 1);
	
	// Row 2: Booking Staff, Export Ref.NO
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'Booking Staff', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, isset($obj->booking_staff) ? $obj->booking_staff : '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'Export Ref.NO', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, '', 0, 1);
	
	// Row 3: Sales Rep, B/L No.
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'Sales Rep', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, isset($obj->sales_rep) ? $obj->sales_rep : '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'B/L No.', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, isset($obj->bl_number) ? $obj->bl_number : '', 0, 1);
	
	// Row 4: Shipper
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'Shipper', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, strtoupper($obj->shipper_name), 0, 1);
	
	// Row 5: Forwarder, Rate Agreement No.
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'Forwarder', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'Rate Agreement No.', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, isset($obj->rate_agreement_no) ? $obj->rate_agreement_no : '', 0, 1);
	
	// Line separator
	$y = $pdf->GetY();
	$pdf->Line(10, $y, 205, $y);
	$pdf->Ln(1);
	
	// ==================== VESSEL INFORMATION ====================
	
	// Row 6: Pre Carrier, Latest ETA/ETD
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'Pre Carrier', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, isset($obj->pre_carrier) ? $obj->pre_carrier : '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'Latest ETA/ETD', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, isset($obj->latest_eta_etd) ? $obj->latest_eta_etd : '', 0, 1);
	
	// Row 7: IMO/Flag/Call Sign (Pre), NRT
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'IMO/Flag/Call Sign', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, isset($obj->pre_carrier_call_sign) ? $obj->pre_carrier_call_sign : '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'NRT', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, isset($obj->nrt) ? $obj->nrt : '', 0, 1);
	
	// Row 8: Trunk Vessel, Latest ETA/ETD
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'Trunk Vessel', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, isset($obj->trunk_vessel) ? $obj->trunk_vessel : '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'Latest ETA/ETD', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, '', 0, 1);
	
	// Row 9: MRN(Korea only), CCN
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'MRN(Korea only)', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, isset($obj->mrn_korea_only) ? $obj->mrn_korea_only : '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'CCN', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, '', 0, 1);
	
	// Row 10: IMO/Flag/Call Sign (Trunk), NRT
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'IMO/Flag/Call Sign', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, isset($obj->trunk_vessel_call_sign) ? $obj->trunk_vessel_call_sign : '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'NRT', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, '', 0, 1);
	
	// Row 11: Post Carrier, ETA/ETD
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'Post Carrier', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, isset($obj->post_carrier) ? $obj->post_carrier : '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'ETA/ETD', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, '', 0, 1);
	
	// Row 12: IMO/Flag/Call Sign (Post), NRT
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'IMO/Flag/Call Sign', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, isset($obj->post_carrier_call_sign) ? $obj->post_carrier_call_sign : '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'NRT', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, '', 0, 1);
	
	// Line separator
	$y = $pdf->GetY();
	$pdf->Line(10, $y, 205, $y);
	$pdf->Ln(1);
	
	// ==================== PORT INFORMATION ====================
	
	// Row 13: Place of Receipt, Proforma 1st vessel ETD
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'Place of Receipt', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, isset($obj->origin) ? $obj->origin : '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'Proforma 1st vessel ETD', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, isset($obj->proforma_vessel_etd) ? $obj->proforma_vessel_etd : '', 0, 1);
	
	// Row 14: Port of Loading, Terminal
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'Port of Loading', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, isset($obj->port_of_loading) ? $obj->port_of_loading : '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'Terminal', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, isset($obj->terminal_pol) ? $obj->terminal_pol : '', 0, 1);
	
	// Row 15: Port of Discharging, Terminal
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'Port of Discharging', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, isset($obj->port_of_discharging) ? $obj->port_of_discharging : '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'Terminal', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, isset($obj->terminal_pod) ? $obj->terminal_pod : '', 0, 1);
	
	// Row 16: Place of Delivery, Terminal
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'Place of Delivery', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, isset($obj->place_of_delivery) ? $obj->place_of_delivery : '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'Terminal', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, isset($obj->terminal_delivery) ? $obj->terminal_delivery : '', 0, 1);
	
	// Row 17: T/S Port, POD / DEL ETA
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'T/S Port', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, isset($obj->ts_port) ? $obj->ts_port : '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'POD / DEL ETA', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, isset($obj->pod_del_eta) ? $obj->pod_del_eta : '', 0, 1);
	
	// Row 18: Ocean Route Type, Rcv/Del Term
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'Ocean Route Type', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, isset($obj->ocean_route_type) ? $obj->ocean_route_type : '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'Rcv/Del Term', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, isset($obj->rcv_del_term) ? $obj->rcv_del_term : '', 0, 1);
	
	// Line separator
	$y = $pdf->GetY();
	$pdf->Line(10, $y, 205, $y);
	$pdf->Ln(1);
	
	// ==================== CARGO INFORMATION ====================
	
	// Row 19: Equipment Type/Q'ty, Estimated Weight
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'Equipment Type/Q\'ty', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, isset($obj->equipment_type_qty) ? $obj->equipment_type_qty : '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'Estimated Weight', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, (isset($obj->estimated_weight) && $obj->estimated_weight) ? number_format($obj->estimated_weight, 3) . ' KGS' : '', 0, 1);
	
	// Row 20: Commodity
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'Commodity', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->MultiCell(0, $lineHeight, isset($obj->commodity) ? $obj->commodity : '', 0, 'L');
	$y = $pdf->GetY();
	$pdf->Line(10, $y, 205, $y);
	$pdf->Ln(1);	
	// Row 21: Empty Pick UP CY, Empty Pick Up Date
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'Empty Pick UP CY', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, isset($obj->empty_pick_up_cy) ? $obj->empty_pick_up_cy : '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'Empty Pick Up Date', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, isset($obj->empty_pick_up_date) ? $obj->empty_pick_up_date : '', 0, 1);
	
	// Row 22: Address, Yard PIC
	$pickupaddr = concatAddress(
		array($obj->shipper_pickup_street_address,
			  $obj->shipper_pickup_district,
			  $obj->shipper_pickup_city,
			  $obj->shipper_pickup_state_province,
			  $obj->shipper_pickup_zip_code,
			  $obj->shipper_pickup_country)
	);
	
    $pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'Address', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, substr($pickupaddr, 0, 80), 0, 1);
	
	// Row 26: TEL, Yard PIC
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'TEL', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, $obj->shipper_tel_number ?: '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'Yard PIC', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, '', 0, 1);

	$y = $pdf->GetY();
	$pdf->Line(10, $y, 205, $y);
	$pdf->Ln(1);

	// Row 24: Full Return CY, Full Return Date
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'Full Return CY', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, isset($obj->full_return_cy) ? $obj->full_return_cy : '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'Full Return Date', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, isset($obj->full_return_date) ? $obj->full_return_date : '', 0, 1);
	
	// Row 25: Address
	$shipperaddr = concatAddress(
		array($obj->shipper_street_address,
			  $obj->shipper_district,
			  $obj->shipper_city,
			  $obj->shipper_state_province,
			  $obj->shipper_zip_code,
			  $obj->shipper_country)
	);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'Address', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, substr($shipperaddr, 0, 80), 0, 1);
	
	// Row 26: TEL, Yard PIC
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'TEL', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, $obj->shipper_tel_number ?: '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'Yard PIC', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, '', 0, 1);
	
	$y = $pdf->GetY();
	$pdf->Line(10, $y, 205, $y);
	$pdf->Ln(1);
	
	// ==================== CUT-OFF DATES ====================
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'Doc Cut-off', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'Customs Cut-off', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, '', 0, 1);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'VGM Cut-off', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, isset($obj->vgm_cut_off) ? $obj->vgm_cut_off : '', 0, 1);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(25, $lineHeight, 'Port Cargo Cut-off', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(40, $lineHeight, isset($obj->port_cargo_cut_off) ? $obj->port_cargo_cut_off : '', 0, 0);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(30, $lineHeight, 'Rail Receiving Date', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, '~', 0, 1);
	
	$pdf->Ln(1);
	
	
	$y = $pdf->GetY();
	$pdf->Line(10, $y, 205, $y);
	$pdf->Ln(1);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(0, $lineHeight, 'Special Cargo Information (Please see attached, if exists)', 0, 0);
	
	// Checkboxes row 1
	$pdf->SetXY(135, $pdf->GetY());
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(3, $lineHeight, '', 1, 0, 'C'); // Checkbox
	$pdf->Cell(35, $lineHeight, ' Shipper\'s own Container', 0, 0);
	$pdf->Cell(5, $lineHeight, '', 0, 0);
	$pdf->Cell(3, $lineHeight, '', 1, 0, 'C'); // Checkbox
	$pdf->Cell(15, $lineHeight, ' RAD', 0, 1);
	
    	$pdf->Ln(3);

	// Checkboxes row 2
	$pdf->Cell(3, $lineHeight, '', 1, 0, 'C'); // Checkbox
	$pdf->Cell(25, $lineHeight, ' Dangerous', 0, 0);
	$pdf->Cell(10, $lineHeight, '', 0, 0);
	$pdf->Cell(3, $lineHeight, '', 1, 0, 'C'); // Checkbox
	$pdf->Cell(25, $lineHeight, ' Reefer (Temp. Set', 0, 0);
	$pdf->Cell(15, $lineHeight, ')', 0, 0);
	$pdf->Cell(3, $lineHeight, '', 1, 0, 'C'); // Checkbox
	$pdf->Cell(15, $lineHeight, ' Awkward', 0, 0);
	$pdf->Cell(10, $lineHeight, '', 0, 0);
	$pdf->Cell(3, $lineHeight, '', 1, 0, 'C'); // Checkbox
	$pdf->Cell(20, $lineHeight, ' Break Bulk', 0, 1);
	$pdf->Ln(3);

	// Line separator
	$y = $pdf->GetY();
	$pdf->Line(10, $y, 205, $y);
	$pdf->Ln(1);
	
	// ==================== REMARKS ====================
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(20, $lineHeight, 'Remarks 1', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, '', 0, 1);
	
	$pdf->Ln(9);
	
	$pdf->SetFont('helvetica','B',$labelFont);
	$pdf->Cell(20, $lineHeight, 'Remarks 2', 0, 0);
	$pdf->SetFont('helvetica','',$labelFont);
	$pdf->Cell(2, $lineHeight, ':', 0, 0);
	$pdf->Cell(0, $lineHeight, isset($obj->remarks_2) ? $obj->remarks_2 : 'See attached rider.', 0, 1);
	
	$pdf->Ln(2);
	
	// ==================== FOOTER DISCLAIMER ====================
	
	$y = $pdf->GetY();
	$pdf->Line(10, $y, 205, $y);
	$pdf->Ln(1);
	
	$pdf->SetFont('helvetica','',9);
	$pdf->MultiCell(0, $lineHeight, 
		'THE ABOVE BOOKING IS SUBJECT TO CHANGE FOR DELIVERY DATE/TIME AS WELL AS TO VESSEL SPACE.' . "\n" .
		'VESSEL SCHEDULE MAY BE CHANGED WITHOUT NOTICE. ANY DATE/TIME ABOVE IS FOR MERCHANT\'S' . "\n" .
		'REFERENCE ONLY AND WITHOUT ANY GUARANTEE.', 0, 'L');
	
	$pdf->Ln(2);
	

}
else{
	echo "Invalid Booking Transaction";
}

$pdf->Output();

?>