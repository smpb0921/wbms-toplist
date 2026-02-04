<?php

include("../../../config/connection.php");
include("../../../config/checklogin.php");
include("../../../config/functions.php");
require('../../../resources/htmltopdf/fpdf.php');

$awb_number = isset($_GET['awb']) ? escapeString($_GET['awb']) : '';

class PDF extends FPDF
{	
    function Footer()
    {
        $this->SetY(-8);
        $this->SetFont('Arial','',7);
        $this->Cell(0, 3, 'SHIPPER\'S COPY', 0, 0, 'C');
        $this->Cell(0, 3, 'PAGE '.$this->PageNo(), 0, 0, 'R');
    }
}

// Query the shipper table
$rs = query("SELECT * FROM shipper");

$data = fetch($rs);

// Initialize PDF - Landscape orientation for the form
$pdf = new PDF('L', 'mm', 'LETTER');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);

// Set margins
$leftMargin = 10;
$topMargin = 10;
$pageWidth = 279; // Letter landscape width in mm
$pageHeight = 216; // Letter landscape height in mm
$contentWidth = $pageWidth - ($leftMargin * 2);

// ==================== HEADER SECTION ====================
$currentY = $topMargin;

// Toplis Logo (top right)
$toplislogo = "../../../resources/toplislogo.png";
if(file_exists($toplislogo)){
    $pdf->Image($toplislogo, $pageWidth - 50, $currentY - 3, 40);
}

// AWB Number (top center)
$pdf->SetXY($pageWidth/2 - 30, $currentY);
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 8, 'AWB No.', 0, 1, 'C');

$pdf->SetXY($pageWidth/2 - 30, $currentY + 8);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(0, 4, 'www.logicorerex.com', 0, 1, 'C');

// AWB Number value
$pdf->SetXY($pageWidth/2 - 30, $currentY + 12);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(200, 0, 0);
$pdf->Cell(0, 8, $awb_number, 0, 1, 'C');
$pdf->SetTextColor(0, 0, 0);

$currentY = $topMargin + 25;

// ==================== LEFT SECTION WITH BORDERS ====================
$leftSectionWidth = 165;

// Top box: Courier's Name and Date of Pick-up
$pdf->Rect($leftMargin, $currentY, $leftSectionWidth, 14);

$pdf->SetXY($leftMargin, $currentY);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(85, 4, "Courier's Name:", 0, 0);
$pdf->Cell(80, 4, 'Date of Pick-up:', 0, 1);

$pdf->SetXY($leftMargin, $pdf->GetY());
$pdf->SetFont('Arial', '', 8);
$courier = !empty($data->courier) ? $data->courier : '';
$pickupDate = !empty($data->pickup_date) ? date('m/d/Y', strtotime($data->pickup_date)) : '';
$pdf->Cell(85, 9, strtoupper($courier), 0, 0);
$pdf->Cell(80, 9, $pickupDate, 0, 1);

// Origin, Destn, Pieces, Actual Weight box
$currentY = $pdf->GetY() + 1;
$pdf->Rect($leftMargin, $currentY, $leftSectionWidth, 14);

$pdf->SetXY($leftMargin, $currentY);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(25, 4, 'Origin:', 0, 0);
$pdf->Cell(25, 4, 'Destn:', 0, 0);
$pdf->Cell(25, 4, 'Pieces:', 0, 0);
$pdf->Cell(90, 4, 'Actual Weight:', 0, 1);

$pdf->SetXY($leftMargin, $pdf->GetY());
$pdf->SetFont('Arial', '', 8);
$origin = !empty($data->origin) ? $data->origin : '';
$destination = !empty($data->destination) ? $data->destination : '';
$pcs = !empty($data->pcs) ? $data->pcs : '';
$weight = !empty($data->weight_fee) ? $data->weight_fee : '';
$pdf->Cell(25, 9, strtoupper($origin), 0, 0);
$pdf->Cell(25, 9, strtoupper($destination), 0, 0);
$pdf->Cell(25, 9, $pcs, 0, 0);
$pdf->Cell(90, 9, $weight . ' kg', 0, 1);

// Shipper's Name box
$currentY = $pdf->GetY() + 1;
$pdf->Rect($leftMargin, $currentY, $leftSectionWidth, 14);

$pdf->SetXY($leftMargin, $currentY);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell($leftSectionWidth, 4, "Shipper's Name:", 0, 1);

$pdf->SetXY($leftMargin, $pdf->GetY());
$pdf->SetFont('Arial', '', 8);
$shipper = !empty($data->shipper) ? $data->shipper : '';
$pdf->Cell($leftSectionWidth, 9, strtoupper($shipper), 0, 1);

// Company Name box
$currentY = $pdf->GetY() + 1;
$pdf->Rect($leftMargin, $currentY, $leftSectionWidth, 14);

$pdf->SetXY($leftMargin, $currentY);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell($leftSectionWidth, 4, 'Company Name:', 0, 1);

$pdf->SetXY($leftMargin, $pdf->GetY());
$pdf->SetFont('Arial', '', 8);
$companyName = !empty($data->company_name) ? $data->company_name : '';
$pdf->Cell($leftSectionWidth, 9, strtoupper($companyName), 0, 1);

// Pick-up Address box
$currentY = $pdf->GetY() + 1;
$pickupBoxHeight = 24;
$pdf->Rect($leftMargin, $currentY, $leftSectionWidth, $pickupBoxHeight);

$pdf->SetXY($leftMargin, $currentY);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell($leftSectionWidth, 4, 'Pick-up Address:', 0, 1);

$pdf->SetXY($leftMargin, $pdf->GetY());
$pdf->SetFont('Arial', '', 8);
$pickupStreet = !empty($data->pickup_street_address) ? $data->pickup_street_address : '';
$pickupCity = !empty($data->pickup_city) ? $data->pickup_city : '';
$pickupProvince = !empty($data->pickup_state_province) ? $data->pickup_state_province : '';
$pickupAddr = trim(implode(', ', array_filter(array($pickupStreet, $pickupCity, $pickupProvince))));
$pdf->MultiCell($leftSectionWidth, 5, strtoupper($pickupAddr), 0);

// Consignee's Name box
$currentY = $pdf->GetY() + 1;
$pdf->Rect($leftMargin, $currentY, $leftSectionWidth, 14);

$pdf->SetXY($leftMargin, $currentY);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell($leftSectionWidth, 4, "Consignee's Name:", 0, 1);

$pdf->SetXY($leftMargin, $pdf->GetY());
$pdf->SetFont('Arial', '', 8);
$consignee = !empty($data->consignee) ? $data->consignee : '';
$pdf->Cell($leftSectionWidth, 9, strtoupper($consignee), 0, 1);

// Company Name box
$currentY = $pdf->GetY() + 1;
$pdf->Rect($leftMargin, $currentY, $leftSectionWidth, 14);

$pdf->SetXY($leftMargin, $currentY);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell($leftSectionWidth, 4, 'Company Name:', 0, 1);

$pdf->SetXY($leftMargin, $pdf->GetY());
$pdf->SetFont('Arial', '', 8);
$consigneeCompany = !empty($data->consignee_company) ? $data->consignee_company : '';
$pdf->Cell($leftSectionWidth, 9, strtoupper($consigneeCompany), 0, 1);

// Consignees Contact Number box
$currentY = $pdf->GetY() + 1;
$pdf->Rect($leftMargin, $currentY, $leftSectionWidth, 14);

$pdf->SetXY($leftMargin, $currentY);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell($leftSectionWidth, 4, 'Consignees Contact Number:', 0, 1);

$pdf->SetXY($leftMargin, $pdf->GetY());
$pdf->SetFont('Arial', '', 8);
$consigneeContact = !empty($data->collection_contact_person) ? $data->collection_contact_person : '';
$pdf->Cell($leftSectionWidth, 9, $consigneeContact, 0, 1);

// Delivery Address box
$currentY = $pdf->GetY() + 1;
$deliveryBoxHeight = 24;
$pdf->Rect($leftMargin, $currentY, $leftSectionWidth, $deliveryBoxHeight);

$pdf->SetXY($leftMargin, $currentY);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell($leftSectionWidth, 4, 'Delivery Address:', 0, 1);

$pdf->SetXY($leftMargin, $pdf->GetY());
$pdf->SetFont('Arial', '', 8);
$billingStreet = !empty($data->billing_street_address) ? $data->billing_street_address : '';
$billingCity = !empty($data->billing_city) ? $data->billing_city : '';
$billingProvince = !empty($data->billing_state_province) ? $data->billing_state_province : '';
$billingCountry = !empty($data->billing_country) ? $data->billing_country : '';
$deliveryAddr = trim(implode(', ', array_filter(array($billingStreet, $billingCity, $billingProvince, $billingCountry))));
$pdf->MultiCell($leftSectionWidth, 5, strtoupper($deliveryAddr), 0);

// ==================== DIMENSIONS TABLE (RIGHT SIDE) ====================
$dimensionsX = $leftMargin + 170;
$dimensionsY = $topMargin + 25;
$dimensionsWidth = 90;

// Draw border for entire dimensions section
$pdf->Rect($dimensionsX, $dimensionsY, $dimensionsWidth, 90);

// Header
$pdf->SetXY($dimensionsX, $dimensionsY);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell($dimensionsWidth, 5, 'Dimension (cm)', 1, 1, 'C');

// Column headers
$pdf->SetXY($dimensionsX, $pdf->GetY());
$pdf->SetFont('Arial', '', 7);
$colWidth = $dimensionsWidth / 4;
$pdf->Cell($colWidth, 5, 'length', 1, 0, 'C');
$pdf->Cell($colWidth, 5, 'width', 1, 0, 'C');
$pdf->Cell($colWidth, 5, 'height', 1, 0, 'C');
$pdf->Cell($colWidth, 5, 'Qty', 1, 1, 'C');

// Data rows (5 rows)
for($i = 0; $i < 5; $i++){
    $pdf->SetXY($dimensionsX, $pdf->GetY());
    $pdf->Cell($colWidth, 6, '', 1, 0, 'C');
    $pdf->Cell($colWidth, 6, '', 1, 0, 'C');
    $pdf->Cell($colWidth, 6, '', 1, 0, 'C');
    $pdf->Cell($colWidth, 6, '', 1, 1, 'C');
}

// Volume and Declared Value section
$pdf->SetXY($dimensionsX, $pdf->GetY());
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell($dimensionsWidth / 2, 5, 'Volume', 1, 0, 'C');
$pdf->Cell($dimensionsWidth / 2, 5, 'Declared Value:', 1, 1, 'C');

$pdf->SetXY($dimensionsX, $pdf->GetY());
$pdf->Cell($dimensionsWidth / 2, 6, '', 1, 0, 'C');
$pdf->Cell($dimensionsWidth / 2, 6, '', 1, 1, 'C');

// Charges section header
$pdf->SetXY($dimensionsX, $pdf->GetY());
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell($dimensionsWidth, 5, 'CHARGES', 1, 1, 'C');

// Charge items
$charges = array(
    'Chargeable Weight',
    'Total CBM',
    'Freight',
    'Valuation',
    'ViewBill Fee',
    'Fuel/Bunker Surcharge',
    'Insurance Surcharge',
    'GRA Fee',
    'Crating/Packing Charge',
    'Management Fee'
);

foreach($charges as $charge){
    $pdf->SetXY($dimensionsX, $pdf->GetY());
    $pdf->SetFont('Arial', '', 6);
    $pdf->Cell($dimensionsWidth, 4, $charge, 1, 1, 'L');
}

// ==================== RIGHT COLUMN - Packaging & Commodity ====================
$rightColX = $dimensionsX;
$rightColY = $topMargin + 117;

// Packaging Description
$pdf->Rect($rightColX, $rightColY, $dimensionsWidth, 45);

$pdf->SetXY($rightColX, $rightColY);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell($dimensionsWidth, 4, 'Packaging Description', 1, 1, 'L');

$pdf->SetXY($rightColX, $pdf->GetY());
$pdf->SetFont('Arial', '', 8);
$pdf->Cell($dimensionsWidth, 6, '', 1, 1, 'L');

// Commodity
$pdf->SetXY($rightColX, $pdf->GetY());
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell($dimensionsWidth, 4, 'Commodity', 1, 1, 'L');

$pdf->SetXY($rightColX, $pdf->GetY());
$pdf->SetFont('Arial', '', 7);
$description = !empty($data->description) ? $data->description : '';
$pdf->MultiCell($dimensionsWidth, 4, strtoupper($description), 1);

// SHIPPER'S OWN PACKED/ITEMS NOT VERIFIED
$yPos = $rightColY + 35;
$pdf->SetXY($rightColX, $yPos);
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell($dimensionsWidth, 4, "SHIPPER'S OWN PACKED/ITEMS NOT VERIFIED", 1, 1, 'C');

$pdf->SetXY($rightColX, $pdf->GetY());
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(15, 5, '', 1, 0, 'C');
$pdf->Cell(20, 5, 'YES', 0, 0, 'C');
$pdf->Cell(15, 5, '', 1, 0, 'C');
$pdf->Cell(40, 5, 'NO', 0, 1, 'C');

// ==================== BOTTOM LEFT SECTIONS ====================
$bottomY = $pdf->GetY() + 3;

// Shipping Mode
$pdf->SetXY($leftMargin, $bottomY);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(60, 4, 'Shipping Mode:', 0, 1);

$pdf->SetXY($leftMargin, $pdf->GetY());
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(5, 4, '', 1, 0);
$pdf->Cell(30, 4, ' Door to Door', 0, 0);
$pdf->Cell(5, 4, '', 1, 0);
$pdf->Cell(25, 4, ' Port to Port', 0, 1);

$pdf->SetXY($leftMargin, $pdf->GetY() + 1);
$pdf->Cell(5, 4, '', 1, 0);
$pdf->Cell(30, 4, ' Port to Door', 0, 0);
$pdf->Cell(5, 4, '', 1, 0);
$pdf->Cell(25, 4, ' Door to Port', 0, 1);

// Mode of Shipment
$pdf->SetXY($leftMargin, $pdf->GetY() + 2);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(60, 4, 'Mode of Shipment', 0, 1);

$pdf->SetXY($leftMargin, $pdf->GetY());
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(5, 4, '', 1, 0);
$pdf->Cell(30, 4, ' By Land', 0, 0);
$pdf->Cell(5, 4, '', 1, 0);
$pdf->Cell(25, 4, ' Airfreight', 0, 1);

$pdf->SetXY($leftMargin, $pdf->GetY() + 1);
$pdf->Cell(40, 4, '', 0, 0);
$pdf->Cell(5, 4, '', 1, 0);
$pdf->Cell(25, 4, ' Seafreight', 0, 1);

// Pay Mode
$pdf->SetXY($leftMargin, $pdf->GetY() + 2);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(60, 4, 'Pay Mode', 0, 1);

$pdf->SetXY($leftMargin, $pdf->GetY());
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(5, 4, '', 1, 0);
$pdf->Cell(30, 4, ' Cash', 0, 0);
$pdf->Cell(5, 4, '', 1, 0);
$pdf->Cell(25, 4, ' Freight Collect', 0, 1);

$pdf->SetXY($leftMargin, $pdf->GetY() + 1);
$pdf->Cell(5, 4, '', 1, 0);
$pdf->Cell(30, 4, ' Online Payment', 0, 0);
$pdf->Cell(5, 4, '', 1, 0);
$pdf->Cell(25, 4, ' Charge/Prepaid', 0, 1);

// ==================== SHIPPER'S SIGNATURE (CENTER) ====================
$signX = $leftMargin + 75;
$signY = $bottomY;

$pdf->Rect($signX, $signY, 85, 28);
$pdf->SetXY($signX, $signY);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(85, 4, "Shipper's Signature", 0, 1, 'L');

$pdf->SetXY($signX, $signY + 4);
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell(85, 3, 'All above information are verified and correct', 0, 'L');

$pdf->SetXY($signX, $signY + 18);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(42.5, 4, 'DATE:', 0, 0, 'L');
$pdf->Cell(42.5, 4, 'TIME:', 0, 1, 'L');

$pdf->SetXY($signX, $signY + 22);
$pdf->Cell(85, 4, 'Attachment:', 0, 1, 'L');

// ==================== GRAND TOTAL (RIGHT) ====================
$totalX = $dimensionsX;
$totalY = $bottomY;

$pdf->Rect($totalX, $totalY, $dimensionsWidth, 10);
$pdf->SetXY($totalX, $totalY);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell($dimensionsWidth, 10, 'GRAND TOTAL', 0, 1, 'C');

// Consignee's Signature
$pdf->SetXY($totalX, $totalY + 11);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell($dimensionsWidth, 4, "Consignee's Signature", 0, 1, 'L');

$pdf->SetXY($totalX, $pdf->GetY());
$pdf->SetFont('Arial', '', 6);
$pdf->MultiCell($dimensionsWidth, 3, 'Received in good order and condition', 0, 'L');

$pdf->SetXY($totalX, $totalY + 22);
$pdf->Cell($dimensionsWidth, 6, '', 'B', 1);

// ==================== BOTTOM DISCLAIMERS ====================
$disclaimerY = $bottomY + 30;

// 12% VAT and other text
$pdf->SetXY($leftMargin, $disclaimerY);
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(20, 3, '12% VAT', 0, 1);

$pdf->SetXY($leftMargin, $pdf->GetY());
$pdf->Cell(40, 3, 'Subtotal', 0, 0);
$pdf->Cell(50, 3, 'Other Reimbursable Charges', 0, 1);

$pdf->SetXY($leftMargin, $pdf->GetY() + 1);
$pdf->SetFont('Arial', '', 5);
$disclaimerText = 'Upon signing this waybill, you hereby allow LogiCore to collect your personal data as may be necessary for your transaction in accordance with Data Privacy Act.';
$pdf->MultiCell($contentWidth - 95, 2.5, $disclaimerText, 0, 'L');

// Final signature boxes at the bottom right
$finalSignY = $disclaimerY;

// Signature over printed name boxes
$pdf->SetXY($totalX, $finalSignY);
$pdf->SetFont('Arial', '', 5);
$pdf->Cell($dimensionsWidth, 3, 'Signature over printed name', 0, 1, 'C');

// DATE/TIME boxes
$pdf->SetXY($totalX, $pdf->GetY());
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell($dimensionsWidth/2, 4, 'DATE:', 1, 0, 'L');
$pdf->Cell($dimensionsWidth/2, 4, 'TIME:', 1, 1, 'L');

$pdf->SetXY($totalX, $pdf->GetY());
$pdf->Cell($dimensionsWidth/2, 5, '', 1, 0);
$pdf->Cell($dimensionsWidth/2, 5, '', 1, 1);

$pdf->SetXY($totalX, $pdf->GetY() + 2);
$pdf->SetFont('Arial', '', 5);
$pdf->Cell($dimensionsWidth, 3, 'Signature over printed name', 0, 1, 'C');

$pdf->SetXY($totalX, $pdf->GetY());
$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell($dimensionsWidth/2, 4, 'DATE:', 1, 0, 'L');
$pdf->Cell($dimensionsWidth/2, 4, 'TIME:', 1, 1, 'L');

$pdf->SetXY($totalX, $pdf->GetY());
$pdf->Cell($dimensionsWidth/2, 5, '', 1, 0);
$pdf->Cell($dimensionsWidth/2, 5, '', 1, 1);

$pdf->Output();

?>
