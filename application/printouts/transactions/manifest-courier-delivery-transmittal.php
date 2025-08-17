<?php

include("../../../config/connection.php");
include("../../../config/checklogin.php");
include("../../../config/functions.php");
require('../../../resources/htmltopdf/pdf-table.php');
include("../../../classes/company-information.class.php");

$txnnumber = isset($_GET['txnnumber'])?escapeString($_GET['txnnumber']):'';

function pcsuffix($x){
	if($x>1){
		return "pcs";
	}
	else if($x>0){
		return "pc";
	}
	else{
		return "";
	}
}



class PDF extends PDF_MC_Table
{	
	function Footer()
	{
		$this->SetFont('Arial','',7);
		$this->SetY(-8);
		$this->Cell(135,3,'Date Effective: February 12, 2018',0,0,'L');
		$this->Cell(135,3,'QF-OPR-07 Issue #2 rev.00',0,0,'R');

	    $this->SetY(-8);
	    $this->SetFont('Arial','B',7);
	    $this->Cell(270,3,'Page '.$this->PageNo().' of {nb}',0,0,'C');
	    //PAGE '.$this->PageNo().' of {nb}
	    
	}
}

$pdf = new PDF('L','mm','LETTER');
$pdf->AliasNbPages();
$pdf->AddPage();
$now = date('m/d/Y h:i:s A');

$fillcolor = getColor('primary_color');//array(214, 69, 65);
$rowitem = getColor('secondary_color');
$headerfont = 8;
$detailfont = 7;
$hlineheight = 5;


$rs = query("select carrier.description as carrier,
	                txn_manifest.document_date,
	                txn_manifest.remarks,
	                txn_manifest.driver_name,
	                group_concat(distinct txn_waybill.consignee_city separator ', ') as companycity,
	                group_concat(distinct destinationtbl.description separator ', ') as destination
	         from txn_manifest 
	         left join carrier on carrier.id=txn_manifest.trucker_name
	         left join txn_manifest_waybill on txn_manifest_waybill.manifest_number=txn_manifest.manifest_number
	         left join txn_waybill on txn_waybill.waybill_number=txn_manifest_waybill.waybill_number
	         left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id
	         where txn_manifest.manifest_number='$txnnumber'
	         group by txn_manifest.manifest_number


	         ");


$rowcount = getNumRows($rs);


if($rowcount==1){

			while($obj=fetch($rs)){
				$carrier = $obj->carrier;
				$docdate = dateFormat($obj->document_date,'m/d/Y');
				$txnremarks = $obj->remarks;
				$driver = utfEncode($obj->driver_name);
				$headerdestination = utfEncode($obj->companycity);
				$headerdestination = ucwords(strtolower($headerdestination));

				$maxlen = 120;
				$headerdestination1 = lineBreak($headerdestination, $maxlen);
				$headerdestinationrem1 = trim(str_replace($headerdestination1, '', $headerdestination));
				$headerdestination2 = lineBreak($headerdestinationrem1, $maxlen);
				$headerdestinationrem2 = trim(str_replace($headerdestination2, '', $headerdestinationrem1));
				$headerdestination3 = lineBreak($headerdestinationrem2, $maxlen);
				$headerdestinationrem3 = trim(str_replace($headerdestination2, '', $headerdestinationrem1));
				$headerdestination4 = lineBreak($headerdestinationrem2, $maxlen);
			}






			$imagelogo = "../../../resources/printout-logo.png";
			$pdf->Image($imagelogo, 5, 5, 30);

			$pdf->SetAutoPageBreak(false);

			$pdf->SetY(10);
			$pdf->SetFont('Arial','B',14);
			$pdf->cell(270,5,'COURIER DAILY DELIVERY TRANSMITTAL',0,1,'C');


			$pdf->SetY(5);
			$pdf->SetFont('Arial','B',10);
			$pdf->SetTextColor(99, 158, 188);
			$pdf->cell(260,5,'# '.$txnnumber,0,1,'R');

			$pdf->ln();
		    $pdf->SetMargins(4,0);

			$pdf->SetTextColor(0, 0, 0);
			

			$pdf->cell(10,10,'',0,1);

			$pdf->SetFont('Arial','B',9);
			$pdf->cell(35,5,'Name of Courier: ',0,0);
			$pdf->SetFont('Arial','',0);
			$pdf->cell(150,5,$driver,'B',0,'L');

			$pdf->cell(20,5,'',0,0);
			$pdf->SetFont('Arial','B',9);
			$pdf->cell(35,5,'Transmittal Date: ',0,0);
			$pdf->SetFont('Arial','',9);
			$pdf->cell(30,5,$docdate,'B',1,'L');

			$pdf->SetFont('Arial','B',9);
			$pdf->cell(35,5,'Area: ',0,0);
			$pdf->SetFont('Arial','',9);
			$pdf->cell(150,5,$headerdestination1,'B',1,'L');

			$pdf->SetFont('Arial','B',9);
			$pdf->cell(35,5,'',0,0);
			$pdf->SetFont('Arial','',9);
			$pdf->cell(150,5,$headerdestination2,'B',1,'L');

			$pdf->SetFont('Arial','B',9);
			$pdf->cell(35,5,'',0,0);
			$pdf->SetFont('Arial','',9);
			$pdf->cell(150,5,$headerdestination3,'B',1,'L');

			$pdf->SetFont('Arial','B',9);
			$pdf->cell(35,5,'',0,0);
			$pdf->SetFont('Arial','',9);
			$pdf->cell(150,5,$headerdestination4,'B',1,'L');

			

			$pdf->ln();
			
			$pdf->SetFont('Arial','B',12);
			$pdf->cell(95,5,'Type of Deliveries:',0,0);
			$pdf->cell(35,5,'N E W  &  P E N D I N G  D E L I V E R I E S',0,1);

			$pdf->SetFont('Arial','B',7);
			$headerlinespacing = 5;

			$columnwidths = array(8,17,20,20,25,25,50,25,35,30,18);
			function printHeader($pdf,$headerlinespacing,$columnwidths){
				$pdf->SetFont('Arial','B',7);
				$pdf->Cell($columnwidths[0],$headerlinespacing,'','TLR',0,'L');
				$pdf->Cell($columnwidths[1],$headerlinespacing,'','TLR',0,'L');
				$pdf->Cell($columnwidths[2],$headerlinespacing,'','TLR',0,'L');
				$pdf->Cell($columnwidths[3],$headerlinespacing,'','TLR',0,'L');
				$pdf->Cell($columnwidths[4],$headerlinespacing,'AUTO DR & SALES','TLR',0,'C');
				$pdf->Cell($columnwidths[5],$headerlinespacing,'','TLR',0,'L');
				$pdf->Cell($columnwidths[6],$headerlinespacing,'','TLR',0,'L');
				$pdf->Cell($columnwidths[7],$headerlinespacing,'','TLR',0,'L');
				$pdf->Cell($columnwidths[8],$headerlinespacing,'CONSIGNEE COMPANY / ','TLR',0,'C');
				$pdf->Cell($columnwidths[9],$headerlinespacing,'','TLR',0,'C');
				$pdf->Cell($columnwidths[10],$headerlinespacing,'','TLR',1,'L');

				$pdf->Cell($columnwidths[0],$headerlinespacing,'NO.','BLR',0,'C');
				$pdf->Cell($columnwidths[1],$headerlinespacing,'PICKUP DATE','BLR',0,'C');
				$pdf->Cell($columnwidths[2],$headerlinespacing,'BRF#','BLR',0,'C');
				$pdf->Cell($columnwidths[3],$headerlinespacing,'CBL BOL','BLR',0,'C');
				$pdf->Cell($columnwidths[4],$headerlinespacing,'INVOICE NUMBERS','BLR',0,'C');
				$pdf->Cell($columnwidths[5],$headerlinespacing,'DESTINATION','BLR',0,'C');
				$pdf->Cell($columnwidths[6],$headerlinespacing,'SHIPPER\'S NANE','BLR',0,'C');
				$pdf->Cell($columnwidths[7],$headerlinespacing,'CONTENT','BLR',0,'C');
				$pdf->Cell($columnwidths[8],$headerlinespacing,'CONSIGNEE\'S NAME','BLR',0,'C');
				$pdf->Cell($columnwidths[9],$headerlinespacing,'STATUS','BLR',0,'C');
				$pdf->Cell($columnwidths[10],$headerlinespacing,'REMARKS','BLR',1,'C');
			}

			printHeader($pdf,$headerlinespacing,$columnwidths);
			

			


			$rs = query("
						    select txn_manifest_waybill.id,
						           txn_manifest_waybill.manifest_number,
						           txn_manifest_waybill.waybill_number,
						           txn_manifest_waybill.created_date,
						           txn_manifest_waybill.created_by,
						           txn_waybill.pickup_date,
						           txn_waybill.document_date,
						           txn_waybill.shipper_account_name,
						           txn_waybill.consignee_account_name,
						           txn_waybill.package_number_of_packages,
						           txn_waybill.package_actual_weight,
						           txn_waybill.package_cbm,
						           txn_waybill.total_amount,
						           txn_waybill.amount_for_collection,
						           txn_waybill.reference,
								   txn_waybill.booking_number,
								   txn_waybill.status,
						           origintbl.description as origin,
						           destinationtbl.description as destination,
						           mode_of_transport.description as modeoftransport,
						           pouch_size.code as pouchsize,
                                   date_format(txn_waybill.pickup_date,'%m/%d/%Y') as pickupdate,
                                   txn_waybill.invoice_number,
                                   txn_waybill.destination_id,
                                   txn_waybill.shipper_company_name,
                                   txn_waybill.shipment_description,
                                   txn_waybill.consignee_company_name,
                                   txn_waybill.status,
                                   txn_waybill.consignee_city as consigneecity
						    from txn_manifest_waybill
						    left join txn_waybill on txn_waybill.waybill_number=txn_manifest_waybill.waybill_number
						    left join pouch_size on txn_waybill.pouch_size_id=pouch_size.id
							left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id
						    left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id
						    left join mode_of_transport on mode_of_transport.id=txn_waybill.package_mode_of_transport
						    where txn_manifest_waybill.manifest_number='$txnnumber'
						    order by txn_waybill.shipper_account_name, txn_waybill.waybill_number asc
						");

			$rscount = getNumRows($rs);

			if($rscount>0){

				$line = 1;
				$linerow = 1;
				$page = 1;
				$pageline = 1;
				$totalrowsperpage = 15;
				$even = 0;

				$rowcount = 0;
				$maxlinecreated = 23;
				
				

				$pdf->SetWidths($columnwidths);
				srand(microtime()*1000000);

				while ($obj = mysql_fetch_object($rs)) {

					$pickupdate = utfEncode($obj->pickupdate);
                    $waybill = utf8_encode($obj->waybill_number);
                    $invoice = utf8_encode($obj->invoice_number);
					$bookingnumber = utf8_encode($obj->booking_number);
                    $destination = utf8_encode($obj->destination);
                    $shipper = utf8_encode($obj->shipper_company_name);
                    $content = utf8_encode($obj->shipment_description);
                    $consignee = utf8_encode($obj->consignee_company_name);
                    $status = utf8_encode($obj->status);
                    $remarks = '';

                    if($pageline>$totalrowsperpage||$rowcount>$maxlinecreated){
                    	$pageline = 1;
						$page++;	
						$pdf->AddPage();
						$pdf->AliasNbPages();
						$pdf->SetMargins(4,0);

						$imagelogo = "../../../resources/printout-logo.png";
						$pdf->Image($imagelogo, 5, 5, 30);

						$pdf->SetAutoPageBreak(false);

						$pdf->SetY(10);
						$pdf->SetFont('Arial','B',14);
						$pdf->cell(270,5,'COURIER DAILY DELIVERY TRANSMITTAL',0,1,'C');


						$pdf->SetY(5);
						$pdf->SetFont('Arial','B',10);
						$pdf->SetTextColor(99, 158, 188);
						$pdf->cell(260,5,'# '.$txnnumber,0,1,'R');

						$pdf->ln();
					    $pdf->SetMargins(4,0);

						$pdf->SetTextColor(0, 0, 0);
						

						$pdf->cell(10,10,'',0,1);

						$pdf->SetFont('Arial','B',9);
						$pdf->cell(35,5,'Name of Courier: ',0,0);
						$pdf->SetFont('Arial','',0);
						$pdf->cell(150,5,$driver,'B',0,'L');

						$pdf->cell(20,5,'',0,0);
						$pdf->SetFont('Arial','B',9);
						$pdf->cell(35,5,'Transmittal Date: ',0,0);
						$pdf->SetFont('Arial','',9);
						$pdf->cell(30,5,$docdate,'B',1,'L');

						$pdf->SetFont('Arial','B',9);
						$pdf->cell(35,5,'Area: ',0,0);
						$pdf->SetFont('Arial','',9);
						$pdf->cell(150,5,$headerdestination1,'B',1,'L');

						$pdf->SetFont('Arial','B',9);
						$pdf->cell(35,5,'',0,0);
						$pdf->SetFont('Arial','',9);
						$pdf->cell(150,5,$headerdestination2,'B',1,'L');

						$pdf->SetFont('Arial','B',9);
						$pdf->cell(35,5,'',0,0);
						$pdf->SetFont('Arial','',9);
						$pdf->cell(150,5,$headerdestination3,'B',1,'L');

						$pdf->SetFont('Arial','B',9);
						$pdf->cell(35,5,'',0,0);
						$pdf->SetFont('Arial','',9);
						$pdf->cell(150,5,$headerdestination4,'B',1,'L');

						$pdf->ln();
						
						$pdf->SetFont('Arial','B',12);
						$pdf->cell(95,5,'Type of Deliveries:',0,0);
						$pdf->cell(35,5,'N E W  &  P E N D I N G  D E L I V E R I E S',0,1);

						$pdf->SetFont('Arial','B',7);
						$headerlinespacing = 5;
						printHeader($pdf,$headerlinespacing,$columnwidths);

						$rowcount = 0;
						$pdf->setRowCreated(0);
                    }


                    $pdf->SetFont('Arial','',7);
                    if($pageline<=$totalrowsperpage&&$rowcount<=$maxlinecreated){

                    	$rowcreated =   $pdf->Row(
				                    				array(
				                    						$line,
				                    						$pickupdate,
															$bookingnumber,
				                    						$waybill,
				                    						$invoice,
				                    						$destination,
				                    						$shipper,
				                    						$content,
				                    						$consignee,
				                    						$status,
				                    						$remarks
				                    					 )
				                    	);

                    	$rowcount = $rowcreated;

                    	//$pdf->Cell(8,4,$rowcount,'L',1,'C',$even);

						/*$pdf->Cell(8,5,$line,'L',0,'C',$even);
						$pdf->Cell(17,5,$pickupdate,'L',0,'C',$even);
						$pdf->Cell(20,5,$waybill,'L',0,'C',$even);
						$pdf->Cell(25,5,$invoice,'L',0,'L',$even);
						$pdf->Cell(35,5,$destination,'L',0,'L',$even);
						$pdf->Cell(60,5,$shipper,'L',0,'L',$even);
						$pdf->Cell(25,5,$content,'L',0,'L');
						$pdf->Cell(45,5,$consignee,'L',0,'L');
						$pdf->Cell(20,5,$status,'L',0,'L',$even);
						$pdf->Cell(18,5,$remarks,'LR',1,'L',$even);*/
					}
					

					
					



					$line++;
					$pageline++;
					$linerow++;

					if($pageline>$totalrowsperpage||$rowcount>$maxlinecreated||$line>$rscount){
						




						$pdf->SetY(-40);
						$pdf->ln();
						$tmpfp = 1;
						$linehgt = 5;
						$spaceinbtwn= 90;

						$pdf->SetFont('Arial','',8);


						$pdf->Cell(10,$linehgt,'',0,0,'L');
						$pdf->Cell(35,$linehgt,'Prepared by: ',0,0,'L');
						$pdf->Cell(50,$linehgt,'','B',0,'L');


						$pdf->Cell($spaceinbtwn,$linehgt,'',0,0,'L');

						$pdf->Cell(30,$linehgt,'Received By: ',0,0,'L');
						$pdf->Cell(50,$linehgt,'','B',1,'L');



						$pdf->Cell(10,$linehgt,'',0,0,'L');
						$pdf->Cell(35,$linehgt,'',0,0,'L');
						$pdf->Cell(50,$linehgt,'Operation Clerk',0,0,'C');

						$pdf->Cell($spaceinbtwn,$linehgt,'',0,0,'L');

						$pdf->Cell(30,$linehgt,'',0,0,'L');
						$pdf->Cell(50,$linehgt,'Name of Courier /Signature',0,1,'C');




						$pdf->Cell(10,$linehgt,'',0,0,'L');
						$pdf->Cell(35,$linehgt,'Checked by: ',0,0,'L');
						$pdf->Cell(50,$linehgt,'','B',0,'L');


						$pdf->Cell($spaceinbtwn,$linehgt,'',0,0,'L');

						$pdf->Cell(30,$linehgt,' ',0,0,'L');
						$pdf->Cell(50,$linehgt,'','B',1,'L');



						$pdf->Cell(10,$linehgt,'',0,0,'L');
						$pdf->Cell(35,$linehgt,'',0,0,'L');
						$pdf->Cell(50,$linehgt,'Operation Clerk',0,0,'C');


						$pdf->Cell($spaceinbtwn,$linehgt,'',0,0,'L');

						$pdf->Cell(30,$linehgt,'',0,0,'L');
						$pdf->Cell(50,$linehgt,'Date Received',0,1,'C');
					}

					

				}

				//print_r($daysprccount);

				




				


			}
			else{
				$pdf->Cell(273,5,'No results found','BLR',0,'C');
			}


			


	

}
else{
	$pdf->Cell(273,5,'Invalid Manifest Number',0,1,'C');
}




$pdf->Output();
	



?>