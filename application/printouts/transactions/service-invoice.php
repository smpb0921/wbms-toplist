<?php

include("../../../config/connection.php");
include("../../../config/checklogin.php");
include("../../../config/functions.php");
require('../../../resources/htmltopdf/fpdf.php');
include("../../../classes/company-information.class.php");

$txnnumber = isset($_GET['txnnumber']) ? escapeString($_GET['txnnumber']) : '';

// Validate transaction number
if(empty($txnnumber)){
    die("Error: No transaction number provided");
}

class PDF extends FPDF
{	
    function Footer()
    {
        $this->SetY(-8);
        $this->SetFont('Arial','',8);
        $this->Cell(0, 3, 'PAGE '.$this->PageNo().' of {nb}', 0, 0, 'R');
    }
}

// Get company information - leave blank, only show labels

// Query the txn_billing table with the billing_number
$rs = query("SELECT * FROM txn_billing WHERE billing_number = '$txnnumber'");

// Check if transaction exists
if(getNumRows($rs) != 1){
    die("Error: Billing number '$txnnumber' not found in database");
}

$data = fetch($rs);

// Initialize PDF
$pdf = new PDF('P', 'mm', 'LETTER');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 15);

// Set margins
$leftMargin = 15;
$rightMargin = 15;
$pageWidth = 216; // Letter size width in mm
$contentWidth = $pageWidth - $leftMargin - $rightMargin;

// Helper variables
$currentY = 15;

// ==================== HEADER SECTION ====================
$pdf->SetY($currentY);

// Company Logo/Name (if you have a logo)
$imagelogo = "../../../resources/printout-logo.png";
if(file_exists($imagelogo)){
    $pdf->Image($imagelogo, $leftMargin, $currentY, 30);
    $currentY += 10;
}

$pdf->SetY($currentY);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell($contentWidth, 6, '', 0, 1, 'C');  // Blank company name


$currentY = $pdf->GetY() + 3;

// SERVICE INVOICE Header
$pdf->SetY($currentY);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell($contentWidth, 6, 'SERVICE INVOICE', 0, 1, 'C');

$currentY = $pdf->GetY() + 2;

// ==================== INVOICE NUMBER AND DATE ====================

// ==================== CUSTOMER INFORMATION BOX ====================
$pdf->SetY($currentY);
$boxStartY = $currentY;

// Draw border for customer info
// $pdf->Rect($leftMargin, $currentY, $contentWidth, 25);

//date
$pdf->SetX($leftMargin + 170);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(40, 5, date('n/j/Y', strtotime($data->document_date)), 0, 1);

//customer name
$pdf->SetX($leftMargin + 10);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell($contentWidth - 110, 5, strtoupper($data->bill_to_company_name), 0, 0);
$pdf->SetFont('Arial', '', 8);

//due date
$pdf->SetX($leftMargin + 10);
$dueDate = !empty($data->payment_due_date) ? date('n/j/Y', strtotime($data->payment_due_date)) : '';
$pdf->Cell(40, 5, $dueDate, 0, 1);


//account number
$pdf->SetFont('Arial', '', 8);
$pdf->SetX($leftMargin + 10);
$pdf->Cell($contentWidth - 110, 5, $data->bill_to_account_number, 0, 0);


//Terms
$pdf->SetX($leftMargin + 170);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(40, 5, 'COD', 0, 1);


//address
$pdf->SetX($leftMargin + 10);
$pdf->SetFont('Arial', '', 8);
$billingAddress = trim(implode(', ', array_filter([
    $data->billing_street_address,
    $data->billing_district,
    $data->billing_city,
    $data->billing_state_province,
    $data->billing_country
])));
$pdf->MultiCell($contentWidth - 35, 5, $billingAddress, 0, 'L');

$currentY = $boxStartY + 27;

// ==================== TABLE HEADER ====================
$pdf->SetY($currentY);
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(240, 240, 240);


$currentY = $pdf->GetY();

// ==================== TABLE CONTENT ====================
$pdf->SetFont('Arial', '', 8);

// Main description box
$descriptionStartY = $currentY;
$descriptionHeight = 80;

// // Quantity cell
// // $pdf->Rect($leftMargin, $currentY, 25, $descriptionHeight);

// // Description cell
// // $pdf->Rect($leftMargin + 25, $currentY, 115, $descriptionHeight);

// // Unit Cost cell
// // $pdf->Rect($leftMargin + 140, $currentY, 25, $descriptionHeight);

// // Amount cell
// // $pdf->Rect($leftMargin + 165, $currentY, 25, $descriptionHeight);

// Fill in the description
$pdf->SetXY($leftMargin + 27, $currentY + 2);
$pdf->SetFont('Arial', 'B', 9);

// Get service description from remarks or build it
$serviceDescription = !empty($data->remarks) ? $data->remarks : 'BL No. Ocean Freight';

$pdf->MultiCell(111, 4, $serviceDescription, 0, 'L');

$pdf->SetFont('Arial', '', 8);
$pdf->SetX($leftMargin + 27);
$pdf->Ln(5);

// Bank details - labels only, no values
$pdf->SetX($leftMargin + 27);
$pdf->Cell(111, 4, 'Due to: ', 0, 1, 'L');
$pdf->SetX($leftMargin + 27);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(111, 4, 'Bank: ', 0, 1, 'L');
$pdf->SetX($leftMargin + 27);
$pdf->Cell(111, 4, 'Account Name: ', 0, 1, 'L');
$pdf->SetX($leftMargin + 27);
$pdf->Cell(111, 4, 'Account #: ', 0, 1, 'L');
$pdf->SetX($leftMargin + 27);
$pdf->Cell(111, 4, 'Swift Code: ', 0, 1, 'L');

$pdf->Ln(3);
$pdf->SetX($leftMargin + 27);
$pdf->SetFont('Arial', '', 7);
$pdf->MultiCell(111, 3, 'Please review our statement and notify us for any discrepancy within 7 days from receipt hereof, otherwise, our statement shall be presumed correct and valid. Failure to pay within agreed credit term will be subject to a monthly interest of 1%.', 0, 'L');

// Amount on the right - using total_amount field
$amount = !empty($data->total_amount) ? floatval($data->total_amount) : 0.00;
$pdf->SetXY($leftMargin + 165, $currentY + 2);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(25, 6, 'USD ' . number_format($amount, 2), 0, 1, 'C');

$currentY = $descriptionStartY + $descriptionHeight;

// ==================== TAX BREAKDOWN SECTION ====================
$pdf->SetY($currentY);
$pdf->SetFont('Arial', '', 8);

// Calculate tax amounts
$vat = !empty($data->vat) ? floatval($data->vat) : 0.00;
$subtotal = !empty($data->subtotal) ? floatval($data->subtotal) : 0.00;
$gross = !empty($data->gross) ? floatval($data->gross) : 0.00;
$net = !empty($data->net) ? floatval($data->net) : 0.00;

// Left column
$taxBreakdownHeight = 35;
// $pdf->Rect($leftMargin, $currentY, 60, $taxBreakdownHeight);

$pdf->SetXY($leftMargin + 2, $currentY + 2);
$pdf->Cell(58, 4, 'SC/PWD/NAAC/MOV/', 0, 1, 'L');
$pdf->SetX($leftMargin + 2);
$pdf->Cell(58, 4, 'Solo Parent ID No.', 0, 1, 'L');
$pdf->SetX($leftMargin + 2);
$pdf->Cell(58, 4, 'Zero Rated Sales', 0, 1, 'L');
$pdf->SetX($leftMargin + 2);
$pdf->Cell(58, 4, 'VAT Amount', 0, 1, 'L');
$pdf->SetX($leftMargin + 2);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(58, 4, 'Total Sales', 0, 1, 'L');

$pdf->SetXY($leftMargin + 2, $currentY + 22);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(58, 4, 'SC/PWD/NAAC/MOV/', 0, 1, 'L');
$pdf->SetX($leftMargin + 2);
$pdf->Cell(58, 4, 'Signature', 0, 1, 'L');

// Middle column
// $pdf->Rect($leftMargin + 60, $currentY, 55, $taxBreakdownHeight);

$pdf->SetXY($leftMargin + 62, $currentY + 2);
$pdf->Cell(51, 4, 'Vatable Sales', 0, 1, 'L');
$pdf->SetX($leftMargin + 62);
$pdf->Cell(51, 4, 'VAT-Exempt Sales', 0, 1, 'L');
$pdf->SetX($leftMargin + 62);
$pdf->Cell(51, 4, '', 0, 1, 'L');
$pdf->SetX($leftMargin + 62);
$pdf->Cell(51, 4, '', 0, 1, 'L');
$pdf->SetX($leftMargin + 62);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(30, 4, '', 0, 0, 'L');
$pdf->Cell(21, 4, 'USD ' . number_format($amount, 2), 0, 1, 'R');

// Right column - Totals
// $pdf->Rect($leftMargin + 115, $currentY, 75, $taxBreakdownHeight);

$pdf->SetXY($leftMargin + 117, $currentY + 2);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(50, 4, 'Total Sales (VAT Incl.)', 0, 0, 'L');
$pdf->Cell(21, 4, '', 0, 1, 'R');
$pdf->SetX($leftMargin + 117);
$pdf->Cell(50, 4, 'Less: SC/PWD Discount', 0, 0, 'L');
$pdf->Cell(21, 4, '', 0, 1, 'R');
$pdf->SetX($leftMargin + 117);
$pdf->Cell(50, 4, 'Amount Net of VAT', 0, 0, 'L');
$pdf->Cell(21, 4, '', 0, 1, 'R');
$pdf->SetX($leftMargin + 117);
$pdf->Cell(50, 4, 'Less: Discount (SC/PWD/NAAC/MOV/SP)', 0, 0, 'L');
$pdf->Cell(21, 4, '', 0, 1, 'R');
$pdf->SetX($leftMargin + 117);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(50, 4, 'Total', 0, 0, 'L');
$pdf->Cell(21, 4, '', 0, 1, 'R');
$pdf->SetX($leftMargin + 117);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(50, 4, 'Less: Withholding Tax', 0, 0, 'L');
$pdf->Cell(21, 4, '', 0, 1, 'R');
$pdf->SetX($leftMargin + 117);
$pdf->Cell(50, 4, 'Less: EWT', 0, 0, 'L');
$pdf->Cell(21, 4, '', 0, 1, 'R');
$pdf->SetX($leftMargin + 117);
$pdf->Cell(50, 4, 'Add: VAT', 0, 0, 'L');
$pdf->Cell(21, 4, number_format($vat, 2), 0, 1, 'R');

$currentY += $taxBreakdownHeight;

// ==================== TOTAL AMOUNT DUE ====================
$pdf->SetY($currentY);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell($contentWidth - 65, 7, '', 0, 0);
$pdf->Cell(60, 7, 'TOTAL AMOUNT DUE', 0, 0, 'L'); // Changed 1 to 0
$pdf->Cell(13, 7, 'USD ' . number_format($amount, 2), 0, 1, 'R'); // Changed 1 to 0

$currentY = $pdf->GetY() + 3;

// ==================== PAYMENT INSTRUCTIONS ====================
$pdf->SetY($currentY);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell($contentWidth, 5, '', 0, 1, 'C');  // Blank company name



$currentY = $pdf->GetY() + 2;

// ==================== TERMS & CONDITIONS ====================
// $pdf->SetY($currentY);
// $pdf->SetFont('Arial', 'B', 8);
// $pdf->Cell($contentWidth, 4, 'TERMS & CONDITIONS:', 0, 1, 'L');
// $pdf->SetFont('Arial', '', 7);
// $termsText = 'Unless otherwise herein specified this bill becomes due and payable within agreed credit term from date or receipts. Interest of 1% per annum will be charged on all overdue accounts, plus attorney\'s fees equal to 25% of the entire collection that may arise out of judicial execution of this.';
// $pdf->MultiCell($contentWidth, 3, $termsText, 0, 'L');

$currentY = $pdf->GetY() + 3;

// ==================== SIGNATURE SECTION ====================
$pdf->SetY($currentY);

// Left signature box
// $pdf->Rect($leftMargin, $currentY, 65, 25);
$pdf->SetXY($leftMargin + 2, $currentY + 2);
$pdf->SetFont('Arial', '', 8);
// $pdf->Cell(61, 4, 'NOTED:', 0, 1, 'L');
$pdf->Ln(8);
$pdf->SetX($leftMargin + 2);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(61, 4, 'Lucy Candillo', 0, 1, 'C');
$pdf->SetX($leftMargin + 2);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(61, 4, 'AUTHORIZED REPRESENTATIVE', 0, 1, 'C');


// Middle signature box
// $pdf->Rect($leftMargin + 65, $currentY, 65, 25);
$pdf->SetXY($leftMargin + 67, $currentY + 10);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(61, 4, 'Arnel Crizaldo', 0, 1, 'C');
$pdf->SetX($leftMargin + 67);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(61, 4, 'Signature Over Printed Name', 0, 1, 'C');


// Right signature box
// $pdf->Rect($leftMargin + 130, $currentY, 60, 25);
$pdf->SetXY($leftMargin + 132, $currentY + 2);
$pdf->SetFont('Arial', '', 8);
// $pdf->Cell(56, 4, 'RECEIVED BY:', 0, 1, 'L');
$pdf->Ln(8);
$pdf->SetX($leftMargin + 132);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(56, 4, 'AUTHORIZED REPRESENTATIVE', 0, 1, 'C');
$pdf->SetX($leftMargin + 132);
$pdf->Cell(56, 4, 'Signature Over Printed Name', 0, 1, 'C');

$currentY = $pdf->GetY() + 2;

// ==================== FOOTER NOTES ====================
// $pdf->SetY($currentY);
// $pdf->SetFont('Arial', '', 6);
// $pdf->Cell($contentWidth, 3, 'Printed:' . date('n/j/y g:i') . ', 10th Floor 1021-1023, BDT Plaza Bldg. 6535- 39 Main Ave. Cor. E. Rodriguez Jr. Ave., Bagumbayan, Quezon City. Date of ATP : 20/07/2020', 0, 1, 'L');
// $pdf->Cell($contentWidth, 3, 'Printed By: ' . (isset($_SESSION['user_name']) ? strtoupper($_SESSION['user_name']) : 'SYSTEM') . ' Valid Until: 20/07/2024 Authority No. 067-062103527-000047 Reg. PT 066221200500001 Acc. System: Accounting System Series Range No: From 1S To 200000 S', 0, 1, 'L');
// $pdf->Cell($contentWidth, 3, 'ZCAGVENG-L-1/26-003-26022-2020 Date of Issue: 1/20/2020', 0, 1, 'L');

// $currentY = $pdf->GetY() + 2;
// $pdf->SetY($currentY);
// $pdf->SetFont('Arial', '', 7);
// $pdf->Cell($contentWidth / 2, 3, 'Printer\'s Accreditation #: 1431MP23/040800001', 0, 0, 'L');
// $pdf->Cell($contentWidth / 2, 3, '', 0, 1, 'R');
// $pdf->Cell($contentWidth / 2, 3, 'Date Issued: 01/08/2024 Expiration Date: 12/31/2026', 0, 0, 'L');

$pdf->Output();

?>