<?php

include("../../../config/connection.php");
include("../../../config/checklogin.php");
include("../../../config/functions.php");
require('../../../resources/htmltopdf/fpdf.php');

$txnnumber = isset($_GET['txnnumber']) ? escapeString($_GET['txnnumber']) : ''; 

$pdf = new FPDF('P', 'mm', 'LETTER');
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10);

// Header - Company Name
$pdf->SetFont("Arial", "B", 14);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(195, 6, "TOPLIS LOGISTICS, INC.", 0, 1, "C");

$pdf->SetFont("Arial", "", 10);
$pdf->Cell(195, 5, "COMBINED TRANSPORT BILL OF LADING", 0, 1, "C");

// Add logo space
// $pdf->Cell(30, 5, $pdf->Image("../../../resources/printout-logo.png", 10, 10, 25, 12), 0, 0);
$pdf->SetFont("Arial", "B", 11);
$pdf->Cell(175, 5, "ORIGINAL", 0, 1, "R");

$pdf->Ln(3);

// Fetch waybill data
$bl_number = $txnnumber;
$user = USERID;

$waybill = fetch(query(
        "SELECT wyb.*, 
            u.*, 
            carrier.description as carrier_name, 
            carrier.code as carrier_code
        FROM txn_waybill wyb
        LEFT JOIN user u ON u.id = {$user}
        LEFT JOIN carrier ON carrier.id = wyb.carrier_id
        WHERE wyb.waybill_number = '{$bl_number}' OR wyb.reference = '{$bl_number}'"
));

$waybill_dims = query("SELECT * FROM txn_waybill_package_dimension WHERE waybill_number = '{$bl_number}'");
$dims = [];
while ($dims_rs = fetch($waybill_dims)) { 
    if ($dims_rs->length != null) {
        array_push($dims, [
            "Length" => $dims_rs->length,
            "Width" => $dims_rs->width,
            "Height" => $dims_rs->height,
            "Quantity" => $dims_rs->quantity,
            "VW" => $dims_rs->volumetric_weight,
            "CBM" => $dims_rs->cbm,
            "Weight" => $dims_rs->actual_weight,
        ]);
    }
}

// Shipper Section
$pdf->SetFillColor(240, 240, 240);
$pdf->SetFont("Arial", "B", 8);
$pdf->Cell(95, 5, "Shipper", "T", 0, "L", true);
$pdf->Cell(25, 5, "", "TL", 0, "L", true);
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(75, 5, '', "T", 1);

// Store starting position for shipper block
$shipperStartY = $pdf->GetY();
$shipperStartX = $pdf->GetX();

// Left side - Shipper info (no internal borders)
$pdf->SetFont("Arial", "", 8);
$shipperLines = [
    strtoupper($waybill->shipper_account_name),
    $waybill->shipper_street_address,
    strtoupper($waybill->shipper_city . ", " . $waybill->shipper_state_province),
    strtoupper($waybill->shipper_country)
];

foreach ($shipperLines as $line) {
    $pdf->Cell(95, 4, $line, 0, 1);
}
$shipperEndY = $pdf->GetY();

// Right side cells (no internal borders)
$pdf->SetXY($shipperStartX + 95, $shipperStartY);
$pdf->SetFont("Arial", "", 7);
$pdf->Cell(25, 4, "B/L No:", 0, 0);
$pdf->Cell(75, 4, $waybill->mawbl_bl, 0, 0);
$pdf->SetXY($shipperStartX + 95, $shipperStartY + 4);
$pdf->Cell(25, 4, "Ref No:", 0, 0);
$pdf->Cell(75, 4, $waybill->reference, 0, 0);
$pdf->SetXY($shipperStartX + 95, $shipperStartY + 8);
$pdf->Cell(25, 4, "Page", 0, 0);
$pdf->Cell(75, 4, "", 0, 0);
$pdf->SetXY($shipperStartX + 95, $shipperStartY + 12);
$pdf->Cell(25, 4, "Print SRL", 0, 0);
$pdf->Cell(75, 4, "", 0, 0);

$rightEndY = $pdf->GetY() + 4;

// Draw middle vertical line only
$shipperHeight = max($shipperEndY, $rightEndY) - $shipperStartY;
$pdf->Line($shipperStartX + 95, $shipperStartY, $shipperStartX + 95, $shipperStartY + $shipperHeight);

// Move to next section
$pdf->SetXY($shipperStartX, max($shipperEndY, $rightEndY));

// Consignee Section
$consigneeHeaderY = $pdf->GetY();
$consigneeStartX = $pdf->GetX();

// Consignee header
$pdf->SetFillColor(240, 240, 240);
$pdf->SetFont("Arial", "B", 8);
$pdf->Cell(95, 5, "Consignee", "T", 0, "L", true);

// Right side - Long text block
$pdf->SetFont("Arial", "", 6);
$consigneeText = "RECEIVED by the Carrier as specified below in apparent good order and condition unless otherwise stated, the goods shall be transported to such place as agreed, authorized or permitted herein and subject to all the forms and conditions whether written, type, stamped, printed or Incorporated on the front and reverse side hereof which the Merchant agrees to be bound by accepting this Bill of Lading any local privilleges and custom withstanding. The particulars given below as stated by the shipper. the weight measure quantity condition contentes and value of goods are unknown to the carrier. In WITNESS whereof one (1) original Bill of Lading has been signed if not otherwise stated below, the time being accomplished the other(s). If any to be void, it required by the carrier one (1) original Bill of Lading must be surrounded duly endorsed in condition for the across or delivery order.";
$pdf->MultiCell(100, 3, $consigneeText, "TL", "J");

$consigneeStartY = $pdf->GetY();

// Move back to left side for consignee info
$pdf->SetXY($consigneeStartX, $consigneeHeaderY + 5);

// Left side - Consignee info (no internal borders)
$pdf->SetFont("Arial", "", 8);
$consigneeLines = [
    strtoupper($waybill->consignee_account_name),
    strtoupper($waybill->consignee_company_name),
    $waybill->consignee_street_address,
    strtoupper($waybill->consignee_city)
];

foreach ($consigneeLines as $line) {
    $pdf->Cell(95, 4, $line, 0, 1);
    $pdf->SetX($consigneeStartX);
}

$pdf->SetFont("Arial", "", 7);
$pdf->Cell(95, 4, "T: " . strtoupper($waybill->consignee_tel_number), 0, 1);
$pdf->SetX($consigneeStartX);
$pdf->Cell(95, 4, "E: " . strtoupper(''), 0, 1);

$consigneeEndY = $pdf->GetY();

// Draw middle vertical line for consignee section
$consigneeHeight = $consigneeEndY - ($consigneeHeaderY + 5);
$pdf->Line($consigneeStartX + 95, $consigneeHeaderY + 5, $consigneeStartX + 95, $consigneeEndY);

// Move to next section - Notify Party
$pdf->SetXY($consigneeStartX, $consigneeEndY);

// Notify Party Section
$pdf->SetFillColor(240, 240, 240);
$pdf->SetFont("Arial", "B", 8);
$pdf->Cell(95, 5, "Notify Party", "T", 0, "L", true);

// Domestic Routing Section - ALIGNED with Notify Party
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(100, 5, "Domestic Routing Instruction/Agent at Port of Discharge", "TL", 1, "L", true);

$notifyStartY = $pdf->GetY();
$notifyStartX = $consigneeStartX; // DEFINE notifyStartX here

// Notify Party info (no internal borders)
$pdf->SetX($consigneeStartX);
$pdf->SetFont("Arial", "", 8);
$notifyLines = [
    strtoupper($waybill->consignee_account_name),
    $waybill->consignee_street_address,
    strtoupper($waybill->consignee_city)
];

foreach ($notifyLines as $line) {
    $pdf->Cell(95, 4, $line, 0, 1);
    $pdf->SetX($consigneeStartX);
}

$pdf->SetFont("Arial", "", 7);
$pdf->Cell(95, 4, "T: " . strtoupper($waybill->consignee_tel_number), 0, 1);
$pdf->SetX($consigneeStartX);
$pdf->Cell(95, 4, "E: " . strtoupper(''), 0, 1);

// Vessel/Port Information (left side under Notify Party)
// Row 1: Precarriage by / Place of Receipt
$pdf->SetX($notifyStartX);
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(45, 4, "Precarriage by", "T", 0, "L", true);
$pdf->Cell(50, 4, "Place of Receipt", "T", 0, "L", true);
$pdf->Cell(100, 4, "", 0, 1);

$pdf->SetX($notifyStartX);
$pdf->SetFont("Arial", "", 7);
$pdf->Cell(45, 4, strtoupper($waybill->carrier_name), 0, 0, "L", true);
// $pdf->Cell(-45, 4, strtoupper($waybill->carrier_id), 0, 0);
$pdf->Cell(100, 4, strtoupper($waybill->shipper_city . ", " . $waybill->shipper_country), 0, 1);

// Row 2: Vessel/Voy. No. / Port of Loading
$pdf->SetX($notifyStartX);
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(45, 4, "Vessel/ Voy. No.", "T", 0, "L", true);
$pdf->Cell(50, 4, "Port of Loading", "T", 0, "L", true);
$pdf->Cell(100, 4, "", 0, 1);

$pdf->SetX($notifyStartX);
$pdf->SetFont("Arial", "", 7);
$pdf->Cell(45, 4, "", 0, 0);
$pdf->Cell(100, 4, strtoupper($waybill->shipper_city . ", " . $waybill->shipper_country), 0, 1);

// Row 3: Port of Discharge / Place of Delivery
$pdf->SetX($notifyStartX);
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(45, 4, "Port of Discharge.", "T", 0, "L", true);
$pdf->Cell(50, 4, "Place of Delivery", "T", 0, "L", true);
$pdf->Cell(100, 4, "", 0, 1);

$pdf->SetX($notifyStartX);
$pdf->SetFont("Arial", "", 7);
$pdf->Cell(45, 4, "", 0, 0);
$pdf->Cell(-45, 4, strtoupper($waybill->consignee_city . ", " . $waybill->consignee_country), 0, 0);
$pdf->Cell(100, 4, strtoupper($waybill->consignee_city . ", " . $waybill->consignee_country), 0, 1);

$notifyEndY = $pdf->GetY();

// Draw middle vertical line for notify section
$notifyHeight = $notifyEndY - $notifyStartY;
$pdf->Line($consigneeStartX + 95, $notifyStartY, $consigneeStartX + 95, $notifyEndY);



$pdf->Ln(2);

// Cargo Details Header
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(195, 4, "PARTICULARS FURNISHED BY THE MERCHANT", "T", 1, "C", true);
$pdf->Cell(195, 3, "Description of Packages and Goods As Stated By Shipper: Shipper's Load  Stow Count and Seal, Said to Contain", 0, 1, "C");

$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(30, 4, "Marks/Numbers", "T", 0, "L");
$pdf->Cell(30, 4, "No. of Packages", "T", 0, "L");
$pdf->Cell(80, 4, "Description of Goods", "T", 0, "L");
$pdf->Cell(30, 4, "Gross Weight/Kgs", "T", 0, "L");
$pdf->Cell(25, 4, "Measurement/CBM", "T", 1, "L");

// Cargo Details
$total_packages = number_format($waybill->package_number_of_packages);
$total_weight = number_format($waybill->package_actual_weight, 2);
$description = wordwrap(strtoupper($waybill->shipment_description));
$total_cbm = 0;

foreach ($dims as $dim) {
    $total_cbm += $dim['CBM'];
}

// First Row - Container and Package info in left columns, weight and CBM in right columns
$pdf->SetFont("Arial", "", 7);

$pdf->Cell(30, 5, "1 X 40'HQ CNTR", 0, 0);
$pdf->Cell(30, 5, $total_packages . " CASES", 0, 0);
$pdf->Cell(80, 5, $description, 0, 0);
$pdf->Cell(30, 5, $total_weight . "KGS", 0, 0);
$pdf->Cell(25, 5, number_format($total_cbm, 2) . "CBM", 0, 1);

// Second Row - Package count and "SEAL SAID TO CONTAIN"
$pdf->Cell(30, 5, "", 0, 0);
$pdf->Cell(30, 5, "", 0, 0);
$pdf->Cell(80, 5, $description, 0, 0);
$pdf->Cell(30, 5, "", 0, 0);
$pdf->Cell(25, 5, "", 0, 1);

// Third Row - Empty line before description
$pdf->Cell(30, 5, "", 0, 0);
$pdf->Cell(30, 5, "", 0, 0);
$pdf->Cell(80, 5, "", 0, 0);
$pdf->Cell(30, 5, "", 0, 0);
$pdf->Cell(25, 5, "", 0, 1);

// Item description - indent under "Description of Goods" column
$description_lines = explode("\n", wordwrap(strtoupper($waybill->shipment_description), 80));
foreach ($description_lines as $line) {
    $pdf->Cell(30, 4, "", 0, 0);
    $pdf->Cell(30, 4, "", 0, 0);
    $pdf->Cell(80, 4, "", 0, 0);
    $pdf->Cell(30, 4, "", 0, 0);
    $pdf->Cell(25, 4, "", 0, 1);
}

// Add spacing
for ($i = 0; $i < 3; $i++) {
    $pdf->Cell(30, 4, "", 0, 0);
    $pdf->Cell(30, 4, "", 0, 0);
    $pdf->Cell(80, 4, "", 0, 0);
    $pdf->Cell(30, 4, "", 0, 0);
    $pdf->Cell(25, 4, "", 0, 1);
}

// // Draw outer border for the entire cargo details section
// $endY = $pdf->GetY();
// $pdf->Rect($pdf->GetX(), $startY, 195, $endY - $startY);


$pdf->Ln(60);

// Footer Section
$pdf->SetFont("Arial", "B", 7);
$pdf->Cell(100, 5, "TOTAL NUMBER OF CONTAINERS OR PACKAGES (IN WORD)", "T", 0, "L", true);
$pdf->Cell(2, 5, "TOTAL:", "T", 0, "C", true);
$pdf->Cell(93, 5, "", "T", 1);

$pdf->SetFont("Arial", "", 7);
$pdf->Cell(100, 4, "Number of Original Bills of Lading", "TR", 0);
$pdf->Cell(50, 4, "", "T", 0);
$pdf->Cell(5, 4, "TOPLIS LOGISTICS, INC.", "T", 0, "C");
$pdf->Cell(40, 5, "", "T", 1);

$pdf->Cell(100, 4, "Place and Date of Issue", "T", 0);
$pdf->Cell(50, 4, "", "L", 0);
$pdf->Cell(45, 4, "", 0, 1);

$pdf->Cell(100, 4, "SHIPPED ON BOARD", "T", 0);
$pdf->Cell(50, 4, "", "L", 0);
$pdf->SetFont("Arial", "B", 6);
$pdf->Cell(5, 9, "AUTHORIZED SIGNATURE(S)", 0, 0, "C");
$pdf->Cell(45, 5, "", 0, 1);

$pdf->SetFont("Arial", "", 7);
$pdf->Cell(50, 4, "ISSUED BY", "TB", 0);
$pdf->Cell(50, 4, "Type of Movement", "TRB", 0);
$pdf->Cell(50, 4, "", 0, 0);
$pdf->SetFont("Arial", "", 6);
$pdf->Cell(5, 4, "SIGNED AS AGENT FOR CARRIER", 0, 0, "C");
$pdf->Cell(40, 5, "", 0, 1);

$pdf->Ln(3);

// Printed by
// $pdf->SetFont("Arial", "I", 7);
// $pdf->Cell(195, 4, "Printed by: " . ucwords(strtolower($waybill->first_name . " " . $waybill->last_name)) . " - " . date('m/d/Y h:i:s A'), 0, 1);

$pdf->Output();

?>