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


$rs = query("select txn_booking.booking_number
	         from txn_booking
	         where txn_booking.booking_number='$txnnumber'");




$imgpath = '../../../barcode/';
$imagelogo = "../../../resources/logicorelogo.png";



$pdf->SetTextColor(0, 0, 0);

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

	$headerfont = 9;
	$detailfont = 8;
	$labelfont = 8;
	$hlineheight = 5;

	$maxlen = 60;
	$maxlen1 = 80;
	$maxlen2 = 110;

	while($obj = fetch($rs)){

		$pickupaddr = concatAddress(
									array($obj->shipper_pickup_street_address,
										  $obj->shipper_pickup_district,
										  $obj->shipper_pickup_city,
										  $obj->shipper_pickup_state_province,
										  $obj->shipper_pickup_zip_code,
										  $obj->shipper_pickup_country)
							 );

		$shipperaddr = concatAddress(
									array($obj->shipper_street_address,
										  $obj->shipper_district,
										  $obj->shipper_city,
										  $obj->shipper_state_province,
										  $obj->shipper_zip_code,
										  $obj->shipper_country)
							 );

		// Add logo to the left side - before any other content
		if(file_exists($imagelogo)){
			// Image(file, x, y, width, height)
			// x=10 (left margin), y=8 (top margin), width=65mm (bigger logo)
			$pdf->Image($imagelogo, 25, 5, 75);
		}

		$leftLabel = 32;
		$leftValue = 55;
		$rightX    = 135;   
		$rightLabel = 38;
		
		$pdf->setX($rightX);
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell($rightLabel,$hlineheight,'Run Date:',0,0);
		$pdf->cell(70,$hlineheight,dateFormat($obj->pickup_date,'m/d/Y'),0,1);

		$pdf->setX($rightX);
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell($rightLabel,$hlineheight,'Contact:',0,1);

		$pdf->setX($rightX);
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell($rightLabel,$hlineheight,'Phone:',0,1);

		$pdf->setX($rightX);
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell($rightLabel,$hlineheight,'Fax:',0,1);

		$pdf->setX($rightX);
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell($rightLabel,$hlineheight,'Email:',0,1);
		$pdf->ln(50);
		


		// Header Section - No borders
	
		$pdf->SetY(40);
		$pdf->setfont('helvetica','B',12);
		$pdf->cell(180,8,'BOOKING CONFIRMATION',0,0,'C');
		$pdf->setfont('helvetica','',10);
		$pdf->SetTextColor(99, 158, 188);
		$pdf->ln(10);


		$leftLabel = 32;
		$leftValue = 55;
		$rightX    = 115;   
		$rightLabel = 38;
		$pdf->SetTextColor(0, 0, 0);

		// Booking Info Line
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(35,$hlineheight,'Booking No. :',0,0);
        
		$pdf->setfont('helvetica','',$detailfont);
		$pdf->cell(60,$hlineheight,$txnnumber,0,0);
		
		$pdf->setX($rightX);
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell($rightLabel,$hlineheight,'Date:',0,0);
		$pdf->cell(70,$hlineheight,dateFormat($obj->pickup_date,'m/d/Y'),0,1);

		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(35,$hlineheight,'Shipper :',0,0);
		$pdf->setfont('helvetica','',$detailfont);
		$pdf->cell(160,$hlineheight,strtoupper($obj->shipper_name),0,1);

		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(35,$hlineheight,'Commodity:',0,1);
		$pdf->setfont('helvetica','',$detailfont);

        $pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(35,$hlineheight,'Trucker:',0,1);
		$pdf->setfont('helvetica','',$detailfont);

		$pdf->ln(5);
		// Origin and Destination
		// $pdf->setfont('helvetica','B',$labelfont);
		// $pdf->cell(30,$hlineheight,'Pickup Date',0,0);
		// $pdf->setfont('helvetica','',$detailfont);
		// $pdf->cell(70,$hlineheight,dateFormat($obj->pickup_date,'m/d/Y'),0,1);
	


        
		$pdf->ln(5);
			// ===== ROW 1 =====
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell($leftLabel,$hlineheight,'Feeder Vessel:',0,0);
		$pdf->setfont('helvetica','',$detailfont);
		$pdf->cell($leftValue,$hlineheight,strtoupper($obj->shipper_name),0,0);

		$pdf->setX($rightX);
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell($rightLabel,$hlineheight,'Cargo Cut off:',0,1);

		// ===== ROW 2 =====
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(35,$hlineheight,'Origin :',0,0);
		$pdf->setfont('helvetica','',$detailfont);
		$pdf->cell(60,$hlineheight,strtoupper($obj->destination),0,0);

		// right side
		$pdf->setX($rightX);
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(45,$hlineheight,'Final SI Deadline :',0,1);


		// ===== ROW 3 =====
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(35,$hlineheight,'Sailing (ETD) :',0,1);
		$pdf->setfont('helvetica','',$detailfont);


		// ===== ROW 4 =====
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(35,$hlineheight,'T/S Port :',0,0);
		$pdf->setfont('helvetica','',$detailfont);

		// right side
		$pdf->setX($rightX);
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(45,$hlineheight,'ETA T/S Port :',0,1);
		$pdf->setfont('helvetica','',$detailfont);


		// $pdf->setfont('helvetica','B',$labelfont);
		// $pdf->cell(25,$hlineheight,'Address',0,0);
		// $pdf->setfont('helvetica','',$detailfont);
		// $pdf->cell(105,$hlineheight,substr($shipperaddr,0,60),0,0);

		// $pdf->cell(25,$hlineheight,'',0,0);
		// $pdf->cell(105,$hlineheight,substr($shipperaddr,60,60),0,0);
		// $pdf->cell(30,$hlineheight,'',0,0);
		// $pdf->cell(35,$hlineheight,convertWithDecimal($obj->package_actual_weight,2),0,1);

		// $pdf->setfont('helvetica','B',$labelfont);
		// $pdf->cell(25,$hlineheight,'Tel',0,0);
		// $pdf->setfont('helvetica','',$detailfont);
		// $pdf->cell(105,$hlineheight,trim($obj->shipper_tel_number),0,0);

		// $pdf->setfont('helvetica','B',$labelfont);
		// $pdf->cell(30,$hlineheight,'CBM',0,0);
		// $pdf->setfont('helvetica','',$detailfont);
		// $pdf->cell(35,$hlineheight,convertWithDecimal($obj->package_cbm,2),0,1);

		$pdf->ln(10);

		// PICKUP INFORMATION Header

		// Pickup Details
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(25,$hlineheight,'Mainliner',0,0);
		$pdf->setfont('helvetica','',$detailfont);
		$pdf->cell(105,$hlineheight,strtoupper($obj->shipper_pickup_state_province),0,0);

        $pdf->setX($rightX);
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(25,$hlineheight,'Flag/Lloyds Code',0,1);
		$pdf->setfont('helvetica','',$detailfont);


		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(30,$hlineheight,'ETA T/S Port',0,0);
		$pdf->setfont('helvetica','',$detailfont);
		$pdf->cell(35,$hlineheight,convertWithDecimal($obj->package_declared_value,0),0,0);

        $pdf->setX($rightX);
        $pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(25,$hlineheight,'ETD T/S Port',0,1);
		$pdf->setfont('helvetica','',$detailfont);


		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(25,$hlineheight,'Dischargin Port',0,0);
		$pdf->setfont('helvetica','',$detailfont);
		

        $pdf->setX($rightX);
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(30,$hlineheight,'ETA Discharging Port',0,1);
		$pdf->setfont('helvetica','',$detailfont);
	

		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(30,$hlineheight,'Destination',0,0);
		$pdf->setfont('helvetica','',$detailfont);
		$pdf->cell(35,$hlineheight,'',0,0);

        $pdf->setX($rightX);
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(30,$hlineheight,'ETA Destination',0,0);
		$pdf->setfont('helvetica','',$detailfont);

		$pdf->ln(15);

		// REMARKS Header

        $pdf->setfont('helvetica','B',$labelfont, 12);
		$pdf->cell(30,$hlineheight,'Booking Remarks:',0,0);
		$pdf->setfont('helvetica','',$detailfont);


		$pdf->setfont('helvetica','',$detailfont);
		$pdf->MultiCell(130,$hlineheight,$obj->remarks,0,'L');

		$pdf->ln(2);

		// Additional Info
        $pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(25,$hlineheight,'Imco:',0,0);
		$pdf->setfont('helvetica','',$detailfont);

        $pdf->setX($rightX);
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(25,$hlineheight,'Reefer:',0,1);
		$pdf->setfont('helvetica','',$detailfont);


		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(30,$hlineheight,'Out of Gauge:',0,0);
		$pdf->setfont('helvetica','',$detailfont);

        $pdf->setX($rightX);
        $pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(25,$hlineheight,'Heavy Lifting:',0,1);
		$pdf->setfont('helvetica','',$detailfont);


		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(25,$hlineheight,'SVC-NBR:',0,0);
		$pdf->setfont('helvetica','',$detailfont);
		

        $pdf->setX($rightX);
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(30,$hlineheight,'Filling Ref:',0,1);
		$pdf->setfont('helvetica','',$detailfont);


		$pdf->ln(3);
        $y = $pdf->GetY();
        $pdf->Line(10, $y, 205, $y); 
        $pdf->ln(1);


		$startY = $pdf->GetY();

		// LEFT SECTION - Pickup Depot
		$pdf->SetY($startY);
		$pdf->SetX(10);
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(0,$hlineheight,'Pickup Depot:',0,1,'L');

		$pdf->SetX(10);
		$pdf->setfont('helvetica','B',10); 
		$pdf->cell(60,$hlineheight + 3,strtoupper($obj->origin),0,0,'L'); 


		// MIDDLE SECTION - Qty
		$pdf->SetY($startY);
		$pdf->SetX(75);
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(0,$hlineheight,'Qty:',0,1,'L');

		$pdf->SetX(75);
		$pdf->setfont('helvetica','B',10);
		$pdf->cell(45,$hlineheight + 3,$obj->package_number_of_packages,0,0,'L');  

		// RIGHT SECTION - Delivery Terminal
		$pdf->SetY($startY);
		$pdf->SetX(125);
		$pdf->setfont('helvetica','B',$labelfont);
		$pdf->cell(0,$hlineheight,'Delivery Terminal:',0,1,'L');

		$pdf->SetX(125);
		$pdf->setfont('helvetica','B',10); 
		$pdf->cell(0,$hlineheight + 3,strtoupper($obj->destination),0,1,'L'); 


		


        $pdf->ln(15);
        $y = $pdf->GetY();
        $pdf->Line(10, $y, 205, $y);
        $pdf->ln(2);

		// Bottom Section
        $pdf->setfont('helvetica','B',$labelfont);
        $pdf->cell(0,$hlineheight,'REMINDER:',0,1);

        $pdf->setfont('helvetica','',$detailfont);

        $reminderText =
        "- Please instruct your Trucker to conduct empty container inspection based on your requirement upon pull out. For any reason of return empty container,\n".
        "- we will be collecting CANCEL BOOKING / RETURNED EMPTY CONTAINER FEE, automatically charged to shipper.\n".
        "- FINAL SI must be sent on or before given deadline to avoid LATE SI FEE amounting to USD 20 per BL.\n".
        "- Any misdescription or misdeclaration by customers will be charged of Cargo Misdeclaration Fee, please login our web site\n".
        "  (https://www.yangming.com)\n".
        "  by your ID/password and refer to our customer advisory \"Charge of Cargo Misdeclaration Fee\" under [News] / [Customer Advisory].";

        $pdf->MultiCell(0,4,$reminderText,0,'L');

        $pdf->ln(4);
        $pdf->setfont('helvetica','B',$labelfont);
        $pdf->cell(0,$hlineheight,'ORIGINAL COPY',0,1,'R');

        
        
        
        

	}
    

}
else{
	echo "Invalid Booking Transaction";
}

$pdf->Output();

?>