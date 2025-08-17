<?php

include("../../../config/connection.php");
include("../../../config/checklogin.php");
include("../../../config/functions.php");
require('../../../resources/htmltopdf/fpdf.php');
include("../../../classes/company-information.class.php");

$txnnumber = isset($_GET['txnnumber'])?escapeString($_GET['txnnumber']):''; 
$formtype = isset($_GET['formtype'])?escapeString($_GET['formtype']):''; 
 

$pdf = new FPDF('P','mm','LETTER');

$pdf->AddPage();
$now = date('m/d/Y h:i:s A');

$pdf->SetFont("Arial","B",12);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(195,3,"CBL Freight Forwarder and Courier Express Int'l Inc.",0,1,"C");
$pdf->Cell(30,5,$pdf->Image("../../../resources/printout-logo.png",5,5,30,15),0,1);


$pdf->SetFont("Arial","B",9);
$pdf->Cell(195,3,"International and Domestic Services",0,1,"C");
$pdf->Cell(195,3,utf8_decode("Marquez St, Edison Ave, Parañaque, 1709 Metro Manila"),0,1,"C");
$pdf->Cell(195,3,"Tel:(632) 772-900 Fax:(632) 772-9016",0,1,"C");
// $pdf->Ln(); 
 
$bl_number = $txnnumber;

$user = USERID;

$waybill = fetch(query(
                "SELECT *,ifnull(ps.description,ps.code) pouch_size_desc from txn_waybill wyb
                left join user u on u.id = {$user}
                left join pouch_size ps on ps.id = wyb.pouch_size_id
                where waybill_number = '{$bl_number}' or reference = '{$bl_number}' "));
$waybill_dims = query("SELECT * from txn_waybill_package_dimension where waybill_number ='{$bl_number}' ");
$dims = [];
for ($i=1; $dims_rs = fetch($waybill_dims); $i++) { 
    
    if($dims_rs->length != null){

        array_push($dims, [
            "Length" => $dims_rs->length,
            "Width" => $dims_rs->width,
            "Height" => $dims_rs->height,
            "Quantity" => $dims_rs->quantity,
            "VW" => $dims_rs->volumetric_weight,
            "CBM" => $dims_rs->cbm,
            "UOM" => $dims_rs->uom,
            "Weight" => $dims_rs->actual_weight,
        ]);
    }

}  

$pdf->SetFont("Arial","",9);
$pdf->Cell(165,4,"D.R. No. ",0,0,"R");
$pdf->Cell(30,4,$waybill->waybill_number,0,1,"L");
$pdf->Cell(165,4,"B.L. No. ",0,0,"R");
$pdf->Cell(30,4,$waybill->mawbl_bl,0,1,"L"); 

$pdf->SetFont("Arial","B",12);
$pdf->Cell(195,5,"DELIVERY RECEIPT",0,1,"C");
$pdf->Ln(); 

 
$shipping_date = 'Y-m-d';

$dim = count($dims) == 1 ?  $dims[0]["Length"]."*".$dims[0]["Width"]."*".$dims[0]["Height"] :  "";
$weight = number_format($waybill->package_actual_weight,1)."kgs";



$pdf->SetFont("Arial","",9);
$pdf->Cell(30,4,"Shipping Date");
$pdf->SetFont("Arial","B",9); 
$pdf->Cell(35,4,date("M d, Y",strtotime(date($waybill->document_date))));

$pdf->SetFont("Arial","",9);
$pdf->Cell(15,4,"Dimension",0,0,"R");
$pdf->SetFont("Arial","B",9);
$pdf->Cell(30,4,$dim);


$pdf->SetFont("Arial","",9);
$pdf->Cell(15,4,"Wt",0,0,"R");
$pdf->SetFont("Arial","B",9);
$pdf->Cell(30,4,$weight,0,0);

$pdf->SetFont("Arial","",9);
$pdf->Cell(15,4,"Pouch Size",0,0,"R");
$pdf->SetFont("Arial","B",9);
$pdf->Cell(30,4,(strlen($waybill->pouch_size_desc) > 0 ? $waybill->pouch_size_desc : "N/A"),0,1);

if($formtype=='DR-ALT'){
    $shipperaccountname = $waybill->consignee_account_name;
    $consigneeaccountname = $waybill->shipper_account_name;
    $consigneetelnumber = $waybill->shipper_tel_number;
    $consigneecompanyname = $waybill->shipper_company_name;
    $consigneestreet = $waybill->shipper_street_address;
    $consigneedistrict = $waybill->shipper_district;
    $consigneecity = $waybill->shipper_city;
    $consigneeregion = $waybill->shipper_state_province;
}
else{
    $shipperaccountname = $waybill->shipper_account_name;
    $consigneeaccountname = $waybill->consignee_account_name;
    $consigneetelnumber = $waybill->consignee_tel_number;
    $consigneecompanyname = $waybill->consignee_company_name;
    $consigneestreet = $waybill->consignee_street_address;
    $consigneedistrict = $waybill->consignee_district;
    $consigneecity = $waybill->consignee_city;
    $consigneeregion = $waybill->consignee_state_province;
}

$pdf->SetFont("Arial","",9);
$pdf->Cell(30,4,"Shipper's Name");
$pdf->SetFont("Arial","B",9);
$pdf->Cell(165,4,ucwords(strtolower($shipperaccountname)," ,./"),0,1);

$pdf->SetFont("Arial","",9);
$pdf->Cell(30,4,"Consignee's Name");
$pdf->SetFont("Arial","B",9);
$pdf->Cell(67.5,4,ucwords(strtolower($consigneeaccountname)," ,./"),0,0);
$pdf->SetFont("Arial","",9);
$pdf->Cell(30,4,"Contact Number");
$pdf->SetFont("Arial","B",9);
$pdf->Cell(67.5,4,strtoupper($consigneetelnumber),0,1);

$pdf->SetFont("Arial","",9);
$pdf->Cell(30,4,"Company Name");
$pdf->SetFont("Arial","B",9);
$pdf->Cell(165,4,ucwords(strtolower($consigneecompanyname)," ,./"),0,1);

$addr = ucwords(strtolower($consigneestreet.((strlen($consigneedistrict) > 0 ? " " : "").$consigneedistrict.(strlen($consigneecity) > 0 ? " " : "").$consigneecity.(strlen($consigneeregion) > 0 ? " " : "").$consigneeregion))," ,./");

$pdf->SetFont("Arial","",9);
$pdf->Cell(30,4,"Address");
$pdf->SetFont("Arial","B",9);
$pdf->MultiCell(165,4,$addr,0,1);
 
$pdf->SetFont("Arial","",9);
$pdf->Cell(30,4,"Item Description");
$pdf->SetFont("Arial","B",9);
$pdf->Cell(67.5,4,$waybill->shipment_description,0,0);

$pdf->SetFont("Arial","",9);
$pdf->Cell(30,4,"Declared Value");
$pdf->SetFont("Arial","B",9);
$pdf->Cell(67.5,4,number_format($waybill->package_declared_value,2),0,1);

$pdf->Ln();


$pdf->SetFont("Arial","",9);
$pdf->Cell(30,4,"Received By",0,0,"R");
$pdf->SetFont("Arial","B",9); 
$pdf->Cell(55,4,"","B");

$pdf->SetFont("Arial","",9);
$pdf->Cell(30,4,"Date Received",0,0,"R");
$pdf->SetFont("Arial","B",9);
$pdf->Cell(25,4,"","B");


$pdf->SetFont("Arial","",9);
$pdf->Cell(30,4,"Time",0,0,"R");
$pdf->SetFont("Arial","B",9);
$pdf->Cell(25,4,"","B",1);  


$pdf->SetFont("Arial","",9);
$pdf->Cell(30,4,"",0,0,"R");
$pdf->SetFont("Arial","",9);
$pdf->Cell(55,4,"Signature Over Printed Name",0,1,"C");



$pdf->Ln();

$pdf->SetFont("Arial","",9);
$pdf->Cell(40,4,"Relationship to Consignee",0,0,"R");
$pdf->SetFont("Arial","B",9); 
$pdf->Cell(45,4,"","B");

$pdf->SetFont("Arial","",9);
$pdf->Cell(30,4,"Type of ID",0,0,"R");
$pdf->SetFont("Arial","B",9);
$pdf->Cell(25,4,"","B");


$pdf->SetFont("Arial","",9);
$pdf->Cell(30,4,"ID No.",0,0,"R");
$pdf->SetFont("Arial","B",9);
$pdf->Cell(25,4,"","B",1);  


$pdf->Ln(); 


$pdf->SetFont("Arial","",12);
$pdf->Cell(25,4,"Remarks:",0,1);  

$pdf->Ln();    

$pdf->SetFont("Arial","",9);
$pdf->Cell(4,4,"","RLTB",0);
$pdf->Cell(56,4,"Moved Out (Consignee Company)",0,0);
$pdf->Cell(21,4,"","B",1);
 
$pdf->Cell(4,4,"","",0);
$pdf->Cell(56,4,"Unknown Consignee",0,0);
$pdf->Cell(21,4,"","B",1);

$pdf->Cell(4,4,"","",0);
$pdf->Cell(56,4,"No one to receive",0,0);
$pdf->Cell(21,4,"","B",1);

$pdf->Cell(4,4,"","",0);
$pdf->Cell(56,4,"Refused to accept by",0,0);
$pdf->Cell(21,4,"","B",1);

$pdf->Cell(10,4,"","",0);
$pdf->Cell(55,4,"(Consignee Out/House Closed/No authorized Representative/ No ID presented upon deliver)",0,1);

$pdf->Cell(4,4,"","",0);
$pdf->Cell(56,4,"Incomplete/Unlocated Address",0,0);
$pdf->Cell(21,4,"","B",1);
 
$pdf->Ln(); 

$pdf->Cell(4,4,"","",0); 
$pdf->SetFont("Arial","I",8);
$pdf->Cell(15,4,"Printed by:",0,0);
$pdf->Cell(170,4,ucwords(strtolower($waybill->first_name." ".$waybill->last_name)),0,0);

$pdf->Output();
	