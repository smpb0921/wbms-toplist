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
$imagelogo = "../../../resources/printout-logo.png";
$pdf->Cell(85, 3, '',0,0);

//$pdf->Image($imagelogo, $pdf->GetX(), $pdf->GetY(), 55)

			$pdf->setfont('helvetica','',18);
			$pdf->cell(40,8,'BOOKING',0,0);
			$pdf->setfont('helvetica','',14);
			$pdf->SetTextColor(99, 158, 188);
			$pdf->cell(70,8,'# '.$txnnumber,0,1,'R');
			$pdf->ln();

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

				$headerfont = 8;
				$detailfont = 7;
				$hlineheight = 6;

				$maxlen = 60;
				$maxlen1 = 80;
			 	$maxlen2 = 110;

				while($obj = fetch($rs)){

					$shipmentdesc1 = lineBreak($obj->remarks, $maxlen1);
					$shipmentdescrem1 = trim(str_replace($shipmentdesc1, '', $obj->remarks));
					$shipmentdesc2 = lineBreak($shipmentdescrem1, $maxlen1);
					$shipmentdescrem2 = trim(str_replace($shipmentdesc2, '', $shipmentdescrem1));
				    $shipmentdesc3 = lineBreak($shipmentdescrem2, $maxlen1);
				    $shipmentdescrem3 = trim(str_replace($shipmentdesc3, '', $shipmentdescrem2));
				    $shipmentdesc4 = lineBreak($shipmentdescrem3, $maxlen1);
				    $shipmentdescrem4 = trim(str_replace($shipmentdesc4, '', $shipmentdescrem3));
				    $shipmentdesc5 = lineBreak($shipmentdescrem4, $maxlen1);
					$shipmentdescrem5 = trim(str_replace($shipmentdesc5, '', $shipmentdescrem4));
				    $shipmentdesc6 = lineBreak($shipmentdescrem5, $maxlen1);
					

					$pickupaddr = concatAddress(
														array($obj->shipper_pickup_street_address,
															  $obj->shipper_pickup_district,
															  $obj->shipper_pickup_city,
															  $obj->shipper_pickup_state_province,
															  $obj->shipper_pickup_zip_code,
															  $obj->shipper_pickup_country)
												 );

					
				    $pickupaddr1 = lineBreak($pickupaddr, $maxlen);
					$pickupaddrrem1 = trim(str_replace($pickupaddr1, '', $pickupaddr));
					$pickupaddr2 = lineBreak($pickupaddrrem1, $maxlen);
					$pickupaddrrem2 = trim(str_replace($pickupaddr2, '', $pickupaddrrem1));
				    $pickupaddr3 = lineBreak($pickupaddrrem2, $maxlen);

					$shipperaddr = concatAddress(
														array($obj->shipper_street_address,
															  $obj->shipper_district,
															  $obj->shipper_city,
															  $obj->shipper_state_province,
															  $obj->shipper_zip_code,
															  $obj->shipper_country)
												 );
					$shipperaddr1 = lineBreak($shipperaddr, $maxlen);
					$shipperaddrrem1 = trim(str_replace($shipperaddr1, '', $shipperaddr));
					$shipperaddr2 = lineBreak($shipperaddrrem1, $maxlen);
					$shipperaddrrem2 = trim(str_replace($shipperaddr2, '', $shipperaddrrem1));
				    $shipperaddr3 = lineBreak($shipperaddrrem2, $maxlen);

					$wbremarks1 = lineBreak($obj->remarks, $maxlen1);
					$wbremarksrem1 = trim(str_replace($wbremarks1, '', $obj->remarks));
					$wbremarks2 = lineBreak($wbremarksrem1, $maxlen1);
					$wbremarksrem2 = trim(str_replace($wbremarks2, '', $wbremarksrem1));
				    $wbremarks3 = lineBreak($wbremarksrem2, $maxlen1);
				    $wbremarksrem3 = trim(str_replace($wbremarks3, '', $wbremarksrem2));
				    $wbremarks4 = lineBreak($wbremarksrem3, $maxlen1);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(30,$hlineheight,'Origin ','LT',0);
					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(100,$hlineheight,strtoupper($obj->origin),'T',0);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(30,$hlineheight,'Pickup Date ','T',0);
					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(35,$hlineheight,dateFormat($obj->pickup_date,'m/d/Y'),'RT',1);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(30,$hlineheight,'Destination ','L',0);
					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(100,$hlineheight,strtoupper($obj->destination),0,0);

					$pdf->cell(65,$hlineheight,'','R',1);





					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(140,$hlineheight,'SHIPPER INFORMATION','LTR',0);

					$pdf->cell(55,$hlineheight,'PACKAGE','TBR',1);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(25,$hlineheight,'No. ','L',0);
					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(115,$hlineheight,strtoupper($obj->shipper_account_number),'R',0);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(55,$hlineheight,'No. of Packages ','R',1,'L');

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(25,$hlineheight,'Name ','L',0);
					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(115,$hlineheight,strtoupper($obj->shipper_name),'R',0);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(55,$hlineheight,convertWithDecimal($obj->package_number_of_packages,0).' '.$obj->unit_of_measure,'BR',1,'L');

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(25,$hlineheight,'Address ','L',0);
					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(115,$hlineheight,$shipperaddr1,'R',0);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(55,$hlineheight,'Actual Weight','R',1,'L');

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(25,$hlineheight,'','L',0);
					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(115,$hlineheight,$shipperaddr2,'R',0);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(55,$hlineheight,convertWithDecimal($obj->package_actual_weight,5),'BR',1,'L');

					
					$pdf->cell(25,$hlineheight,'','L',0);
					$pdf->cell(115,$hlineheight,$shipperaddr3,'R',0);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(55,$hlineheight,'CBM','R',1,'L');

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(25,$hlineheight,'Tel','LB',0);
					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(115,$hlineheight,trim($obj->shipper_tel_number,' | '),'BR',0);


					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(55,$hlineheight,convertWithDecimal($obj->package_cbm,5),'BR',1,'L');

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(140,$hlineheight,'PICKUP INFORMATION','LTR',0);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(55,$hlineheight,'Declared Value ','R',1,'L');


					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(25,$hlineheight,'Region ','L',0);
					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(115,$hlineheight,strtoupper($obj->shipper_pickup_state_province),'R',0);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(55,$hlineheight,convertWithDecimal($obj->package_declared_value,0),'BR',1,'L');
					
					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(25,$hlineheight,'City ','L',0);
					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(115,$hlineheight,strtoupper($obj->shipper_pickup_city),'R',0);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(55,$hlineheight,'Services','R',1,'L');

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(25,$hlineheight,'District ','L',0);
					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(115,$hlineheight,strtoupper($obj->shipper_pickup_district),'R',0);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(55,$hlineheight,strtoupper($obj->services),'BR',1,'L');

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(25,$hlineheight,'Street ','L',0);
					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(115,$hlineheight,strtoupper($pickupaddr1),'R',0);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(55,$hlineheight,'Mode of Transport','R',1,'L');

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(25,$hlineheight,'','L',0);
					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(115,$hlineheight,strtoupper($pickupaddr2),'R',0);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(55,$hlineheight,strtoupper($obj->modeoftransport),'BR',1,'L');

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(25,$hlineheight,'','L',0);
					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(115,$hlineheight,strtoupper($pickupaddr3),'R',0);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(55,$hlineheight,'Documents','R',1,'L');

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(25,$hlineheight,'Country ','L',0);
					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(115,$hlineheight,strtoupper($obj->shipper_pickup_country),'R',0);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(55,$hlineheight,strtoupper($obj->documents),'BR',1,'L');

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(140,$hlineheight,'REMARKS','LTR',0);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(55,$hlineheight,'Handling Instruction ','R',1,'L');

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(140,$hlineheight,$shipmentdesc1,'LR',0);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(55,$hlineheight,strtoupper($obj->handlinginstruction),'BR',1,'L');

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(140,$hlineheight,$shipmentdesc2,'LR',0);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(55,$hlineheight,'Pay Mode ','R',1,'L');

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(140,$hlineheight,$shipmentdesc3,'LR',0);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(55,$hlineheight,strtoupper($obj->package_pay_mode),'R',1,'L');


					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(140,$hlineheight,$shipmentdesc4,'LR',0);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(55,$hlineheight,'','R',1,'L');

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(140,$hlineheight,$shipmentdesc5,'LR',0);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(55,$hlineheight,'','R',1,'L');

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(140,$hlineheight,$shipmentdesc6,'BLR',0);

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(55,$hlineheight,'','RB',1,'L');

					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(65,5,"Vehicle Type",'LR',0,'L');
					$pdf->cell(65,5,"Time Ready",'LR',0,'L');
					$pdf->cell(65,5,"Created By",'R',1,'L');
					$pdf->setfont('helvetica','',$headerfont);
					$pdf->cell(65,5,$obj->vehicle_type_desc,'LRB',0,'L');
					$pdf->cell(65,5,date('M d, Y g:i A',strtotime($obj->time_ready)),'LRB',0,'L');
					$pdf->cell(65,5,$obj->created_by,'RB',1,'L');


 
				}

			}
			else{
				echo "Invalid Booking Transaction";
			}

 


$pdf->Output();
	



?>