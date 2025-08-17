<?php

include("../../../config/connection.php");
include("../../../config/checklogin.php");
include("../../../config/functions.php");
require('../../../resources/htmltopdf/fpdf.php');
include("../../../classes/company-information.class.php");

$formtype = isset($_GET['formtype'])?escapeString($_GET['formtype']):'';
$txnnumber = isset($_GET['txnnumber'])?escapeString($_GET['txnnumber']):'';
$bolnumber = isset($_GET['bolnumber'])?escapeString($_GET['bolnumber']):'';
$printcounter = getInfo("txn_waybill","print_counter","where waybill_number='$txnnumber'");
$remarks = isset($_GET['remarks'])?escapeString(trim($_GET['remarks'])):'';

class PDF extends FPDF
{	
	function Footer()
	{	
		$user = '';
		$rs = query("select concat(first_name,' ',last_name) as user from user where id='".USERID."'");
		if(getNumRows($rs)>0){
			while($obj=fetch($rs)){
				$user = strtoupper(trim($obj->user));
			}
			
		}

	    $this->SetY(-8);
	    $this->SetFont('Arial','',7);
	    $this->SetTextColor(145, 145, 145);
	    $this->Cell(20,3,date('m/d/Y h:i:s A').' - '.$user,0,0,'L');
	    $this->SetTextColor(0, 0, 0);
	    $this->SetFont('Arial','B',8);
	    $this->Cell(180,3,'PAGE '.$this->PageNo().' of {nb}',0,0,'R');

	    
	}
}

$pdf = new PDF('P','mm','LETTER');
$pdf->AliasNbPages();
$pdf->AddPage();
$now = date('m/d/Y h:i:s A');

//$company = new company_information();
//$company->select();
//$cmp = $company->loadObjectlist();

$rs = query("select txn_waybill.waybill_number,
					txn_waybill.reference
	         from txn_waybill
	         where txn_waybill.waybill_number='$txnnumber'");

$trackingnumber = '';
while($obj=fetch($rs)){
	$trackingnumber = $obj->reference;
}




$imgpath = '../../../barcode/';
$imagelogo = "../../../resources/printout-logo.png";
//$pdf->Cell(75, 3, '',0,0);
//$pdf->Image($imagelogo, $pdf->GetX(), $pdf->GetY(), 55)


//$imagelogo = "../../../resources/barcode-generator/generate-barcode.php?text=kaye";



			$pdf->SetFont('Arial','B',18);
			$pdf->cell(115,8,'BILL OF LADING',0,0);
			$pdf->SetFont('Arial','B',14);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->cell(80,8,'DR No.: '.$trackingnumber,0,1,'R');

			$copystr = 'ORIGINAL COPY';
			if($printcounter>1){
				$copystr = 'DUPLICATE COPY';
			}

			
			$pdf->SetFont('Arial','B',10);
			$pdf->SetTextColor(145, 145, 145);
			$pdf->cell(150,5,$copystr,0);
			$pdf->SetTextColor(0, 0, 0);

			$maindir = str_replace(' ','%20',str_replace($_SERVER['DOCUMENT_ROOT'].'/', '', $_SERVER['SCRIPT_FILENAME']));
		 	$maindir = substr($maindir, 0,strpos($maindir, '/')); 
		 	$maindir = strtoupper($maindir)=='APPLICATION'?'':$maindir;
		 	$urlbarcodegen = 'http://'.$_SERVER['HTTP_HOST'].'/wbms/resources/barcode-generator/generate-barcode.php?text='.$txnnumber;
		 	file_put_contents($imgpath.$txnnumber.".png", file_get_contents($urlbarcodegen));
			//file_put_contents($imgpath.$txnnumber.".png", file_get_contents("http://localhost:8017/wbms/v1/resources/barcode-generator/generate-barcode.php?text=".$txnnumber));
			//echo $imgpath.$txnnumber.".png";
			$pdf->Cell(50, 5, $pdf->image($imgpath.$txnnumber.".png", $pdf->GetX(), $pdf->GetY(), 45),0,1);
			$pdf->cell(50,8,'BOL No.: '.$bolnumber,0,0);
			$pdf->cell(145,8,'',0,1,'R');

			$pdf->SetTextColor(0, 0, 0);



			/*$pdf->SetFont('Arial','B',11);
			$pdf->cell(30,12,'BOL Number: ','B',0);
			$pdf->SetFont('Arial','',11);
			$pdf->cell(165,12,$bolnumber,'B',1);*/

			$pdf->cell(195,1,'','B',1);


			//$pdf->cell(195,5,'','B',1);
			

			
		

			$headerfont = 8;
			$detailfont = 7;
			$hlineheight = 5;
			$pdf->SetFont('Arial','',$detailfont);

			$fillcolor = getColor('primary_color');//array(214, 69, 65);
			$rowitem = getColor('secondary_color');

			$othercharges = 0;

			$rs1 = query("select sum(amount) as othercharges from txn_waybill_other_charges where waybill_number='$txnnumber'");
			if(getNumRows($rs1)>0){
				while($obj1=fetch($rs1)){
					$othercharges = $obj1->othercharges;
				}
			}

			if(!$othercharges>=0){
				$othercharges = 0;
			}

	        $rs = query("select txn_waybill.id, 
								txn_waybill.waybill_number,
								txn_waybill.status,
				                txn_waybill.booking_number,
				                txn_waybill.origin_id,
				                txn_waybill.destination_id,
				                txn_waybill.destination_route_id,
				                txn_waybill.pickup_date,
				                txn_waybill.on_hold_flag,
				                txn_waybill.on_hold_remarks,
				                txn_waybill.remarks,
				                txn_waybill.created_date,
				                txn_waybill.created_by,
				                txn_waybill.updated_date,
				                txn_waybill.updated_by,
				                txn_waybill.document_date,
				                txn_waybill.delivery_date,
				                txn_waybill.manifest_number,
				                txn_waybill.invoice_number,
				                txn_waybill.shipper_id,
				                txn_waybill.shipper_account_number,
				                txn_waybill.shipper_account_name,
				                txn_waybill.shipper_tel_number,
				                txn_waybill.shipper_company_name,
				                txn_waybill.shipper_street_address,
				                txn_waybill.shipper_district,
				                txn_waybill.shipper_city,
				                txn_waybill.shipper_state_province,
				                txn_waybill.shipper_zip_code,
				                txn_waybill.shipper_country,
				                txn_waybill.consignee_id,
				                txn_waybill.amount_for_collection,
				                txn_waybill.consignee_account_number,
				                txn_waybill.consignee_account_name,
				                txn_waybill.consignee_tel_number,
				                txn_waybill.consignee_company_name,
				                txn_waybill.consignee_street_address,
				                txn_waybill.consignee_district,
				                txn_waybill.consignee_city,
				                txn_waybill.consignee_state_province,
				                txn_waybill.consignee_zip_code,
				                txn_waybill.consignee_country,
				                txn_waybill.pickup_street_address,
				                txn_waybill.pickup_district,
				                txn_waybill.pickup_city,
				                txn_waybill.pickup_state_province,
				                txn_waybill.pickup_zip_code,
				                txn_waybill.pickup_country,
				                txn_waybill.shipment_description,
				                txn_waybill.package_number_of_packages,
				                txn_waybill.package_declared_value,
				                txn_waybill.package_actual_weight,
				                txn_waybill.package_cbm,
				                txn_waybill.package_vw,
				                txn_waybill.package_chargeable_weight,
				                txn_waybill.package_freight,
				                txn_waybill.package_valuation,
				                txn_waybill.package_service,
				                txn_waybill.package_mode_of_transport,
				                txn_waybill.package_document,
				                txn_waybill.package_delivery_instruction,
				                txn_waybill.package_transport_charges,
				                txn_waybill.package_pay_mode,
				                txn_waybill.vat,
				                txn_waybill.zero_rated_flag,
				                txn_waybill.total_amount,
				                txn_waybill.carrier_id,
				                txn_waybill.shipper_rep_name,
				                txn_waybill.package_freight_computation,
						   		txn_waybill.package_insurance_rate,
						    	txn_waybill.package_fuel_rate,
						    	txn_waybill.package_bunker_rate,
						    	txn_waybill.package_minimum_rate,
						    	txn_waybill.subtotal,
						    	txn_waybill.print_counter,
						    	txn_waybill.printed_date,
						    	txn_waybill.printed_by,
						    	txn_waybill.third_party_logistic_id,
						    	txn_waybill.last_status_update_remarks,
						    	txn_waybill.secondary_recipient,
						    	concat(printeduser.first_name,' ',printeduser.last_name) as printedby,
						    	third_party_logistic.description as thirdpartylogistic,
				                origintbl.description as origin,
				                destinationtbl.description as destination,
				                services.description as servicedesc,
				                mode_of_transport.description as modeoftransport,
				                delivery_instruction.description as deliveryinstruction,
				                destination_route.description as destinationroute,
				                group_concat(distinct accompanying_documents.description separator ', ') as document,
				                transport_charges.description as transportcharges,
				                carrier.description as carrier,
				                group_concat(distinct handling_instruction.description separator ', ') as handlinginstruction
				         from txn_waybill
				         left join txn_waybill_document on txn_waybill_document.waybill_number=txn_waybill.waybill_number
				         left join txn_waybill_handling_instruction on txn_waybill_handling_instruction.waybill_number=txn_waybill.waybill_number
				         left join handling_instruction on handling_instruction.id=txn_waybill_handling_instruction.handling_instruction_id
				         left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id 
				         left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id 
				         left join services on services.id=txn_waybill.package_service
				         left join mode_of_transport on mode_of_transport.id=txn_waybill.package_mode_of_transport
				         left join delivery_instruction on delivery_instruction.id=txn_waybill.package_delivery_instruction
				         left join destination_route on destination_route.id=txn_waybill.destination_route_id
				         left join accompanying_documents on accompanying_documents.id=txn_waybill_document.accompanying_document_id
				         left join transport_charges on transport_charges.id=txn_waybill.package_transport_charges
				         left join carrier on carrier.id=txn_waybill.carrier_id
				         left join third_party_logistic on third_party_logistic.id=txn_waybill.third_party_logistic_id
				         left join user as printeduser on printeduser.id=txn_waybill.printed_by
				         where txn_waybill.waybill_number = '$txnnumber' 
				         group by txn_waybill.waybill_number");
			$maxlen = 60;
			$maxlen1 = 90;
			$maxlen2 = 110;
			if(getNumRows($rs)==1){
				while($obj = fetch($rs)){
					$zeroratedflag = $obj->zero_rated_flag==1?'YES':'NO';
					$documentdate = dateFormat($obj->document_date, "m/d/Y");
					$deliverydate = dateFormat($obj->delivery_date, "m/d/Y");

					if($formtype=='INTERNAL-ALT'){
						$shipperaddr = concatAddress(
															array($obj->consignee_street_address,
																  $obj->consignee_district,
																  $obj->consignee_city,
																  $obj->consignee_state_province,
																  $obj->consignee_zip_code,
																  $obj->consignee_country)
													 );
						$shipperaddr1 = lineBreak($shipperaddr, $maxlen);
						$shipperaddrrem1 = trim(str_replace($shipperaddr1, '', $shipperaddr));
						$shipperaddr2 = lineBreak($shipperaddrrem1, $maxlen);
						$shipperaddrrem2 = trim(str_replace($shipperaddr2, '', $shipperaddrrem1));
					    $shipperaddr3 = lineBreak($shipperaddrrem2, $maxlen);

						$consigneeaddr = concatAddress(
															array($obj->shipper_street_address,
																  $obj->shipper_district,
																  $obj->shipper_city,
																  $obj->shipper_state_province,
																  $obj->shipper_zip_code,
																  $obj->shipper_country)
													 );

						
					    $consigneeaddr1 = lineBreak($consigneeaddr, $maxlen);
						$consigneeaddrrem1 = trim(str_replace($consigneeaddr1, '', $consigneeaddr));
						$consigneeaddr2 = lineBreak($consigneeaddrrem1, $maxlen);
						$consigneeaddrrem2 = trim(str_replace($consigneeaddr2, '', $consigneeaddrrem1));
					    $consigneeaddr3 = lineBreak($consigneeaddrrem2, $maxlen);
					}
					else{


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

						$consigneeaddr = concatAddress(
															array($obj->consignee_street_address,
																  $obj->consignee_district,
																  $obj->consignee_city,
																  $obj->consignee_state_province,
																  $obj->consignee_zip_code,
																  $obj->consignee_country)
													 );

						
					    $consigneeaddr1 = lineBreak($consigneeaddr, $maxlen);
						$consigneeaddrrem1 = trim(str_replace($consigneeaddr1, '', $consigneeaddr));
						$consigneeaddr2 = lineBreak($consigneeaddrrem1, $maxlen);
						$consigneeaddrrem2 = trim(str_replace($consigneeaddr2, '', $consigneeaddrrem1));
					    $consigneeaddr3 = lineBreak($consigneeaddrrem2, $maxlen);
					}


				    $handlinginstructionstr1 = lineBreak($obj->handlinginstruction, 35);
					$handlinginstructionstrrem1 = trim(str_replace($handlinginstructionstr1, '', $obj->handlinginstruction));
					$handlinginstructionstr2 = lineBreak($handlinginstructionstrrem1, 35);
					$handlinginstructionstrrem2 = trim(str_replace($handlinginstructionstr2, '', $handlinginstructionstrrem1));
				    $handlinginstructionstr3 = lineBreak($handlinginstructionstrrem2,35);
				    $handlinginstructionstrrem3 = trim(str_replace($handlinginstructionstr3, '', $handlinginstructionstrrem2));
				    $handlinginstructionstr4 = lineBreak($handlinginstructionstrrem3,35);
				    $handlinginstructionstrrem4 = trim(str_replace($handlinginstructionstr4, '', $handlinginstructionstrrem3));
				    $handlinginstructionstr5 = lineBreak($handlinginstructionstrrem4,35);





				    $shipmentdesc1 = lineBreak($obj->shipment_description, $maxlen1);
					$shipmentdescrem1 = trim(str_replace($shipmentdesc1, '', $obj->shipment_description));
					$shipmentdesc2 = lineBreak($shipmentdescrem1, $maxlen1);
					$shipmentdescrem2 = trim(str_replace($shipmentdesc2, '', $shipmentdescrem1));
				    $shipmentdesc3 = lineBreak($shipmentdescrem2, $maxlen1);
				    $shipmentdescrem3 = trim(str_replace($shipmentdesc3, '', $shipmentdescrem2));
				    $shipmentdesc4 = lineBreak($shipmentdescrem3, $maxlen1);
				    $shipmentdescrem4 = trim(str_replace($shipmentdesc4, '', $shipmentdescrem3));
				    $shipmentdesc5 = lineBreak($shipmentdescrem4, $maxlen1);


				    $wbremarks1 = lineBreak($obj->remarks, $maxlen2);
					$wbremarksrem1 = trim(str_replace($wbremarks1, '', $obj->remarks));
					$wbremarks2 = lineBreak($wbremarksrem1, $maxlen2);
					$wbremarksrem2 = trim(str_replace($wbremarks2, '', $wbremarksrem1));
				    $wbremarks3 = lineBreak($wbremarksrem2, $maxlen2);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'3PL ','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(100,$hlineheight,strtoupper($obj->thirdpartylogistic),0,0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'Document Date ',0,0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(35,$hlineheight,$documentdate,'R',1);


					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'Origin ','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(100,$hlineheight,strtoupper($obj->origin),0,0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'Delivery Date ',0,0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(35,$hlineheight,$deliverydate,'R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'Destination ','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(100,$hlineheight,strtoupper($obj->destination),0,0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'Manifest No. ',0,0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(35,$hlineheight,strtoupper($obj->manifest_number),'R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'Route ','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(100,$hlineheight,strtoupper($obj->destinationroute),0,0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'Invoice No. ',0,0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(35,$hlineheight,strtoupper($obj->invoice_number),'R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'Mode ','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(100,$hlineheight,strtoupper($obj->modeoftransport),0,0);

					if($obj->print_counter>1){
						$pdf->SetFont('Arial','B',$headerfont);
						$pdf->cell(30,$hlineheight,'Reprint No.',0,0);
						$pdf->SetFont('Arial','',$headerfont);
						$pdf->cell(35,$hlineheight,($obj->print_counter-1),'R',1);
					}
					else{
						$pdf->SetFont('Arial','B',$headerfont);
					 	$pdf->cell(30,$hlineheight,'Printed by',0,0);
						$pdf->SetFont('Arial','',$headerfont);
						$pdf->cell(35,$hlineheight,$obj->printedby,'R',1);


						$pdf->SetFont('Arial','B',$headerfont);
						$pdf->cell(30,$hlineheight,'','L',0);
						$pdf->SetFont('Arial','',$headerfont);
						$pdf->cell(100,$hlineheight,'',0,0);

						$pdf->SetFont('Arial','B',$headerfont);
						$pdf->cell(30,$hlineheight,'Printed Date ',0,0);
						$pdf->SetFont('Arial','',$headerfont);
						$pdf->cell(35,$hlineheight,dateFormat($obj->printed_date,'m/d/Y'),'R',1);
					}

					


					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'Remarks ','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(165,$hlineheight,$wbremarks1,'R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(165,$hlineheight,$wbremarks2,'R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(165,$hlineheight,$wbremarks3,'R',1);

				
					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(140,$hlineheight,'SHIPPER INFORMATION','LTR',0);

					$pdf->cell(55,$hlineheight,'PACKAGE','TBR',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(25,$hlineheight,'No. ','L',0);
					$pdf->SetFont('Arial','',$headerfont);

					if($formtype=='INTERNAL-ALT'){
						$pdf->cell(115,$hlineheight,strtoupper($obj->consignee_account_number),'R',0);
					}
					else{
						$pdf->cell(115,$hlineheight,strtoupper($obj->shipper_account_number),'R',0);
					}

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(55,$hlineheight,'No. of Packages ','R',1,'L');

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(25,$hlineheight,'Name ','L',0);
					$pdf->SetFont('Arial','',$headerfont);

					if($formtype=='INTERNAL-ALT'){
						$pdf->cell(115,$hlineheight,strtoupper($obj->consignee_account_name),'R',0);
					}
					else{
						$pdf->cell(115,$hlineheight,strtoupper($obj->shipper_account_name),'R',0);
					}

					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(55,$hlineheight,convertWithDecimal($obj->package_number_of_packages,0),'BR',1,'L');

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(25,$hlineheight,'Address ','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(115,$hlineheight,$shipperaddr1,'R',0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(55,$hlineheight,'Actual Weight','R',1,'L');

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(25,$hlineheight,'','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(115,$hlineheight,$shipperaddr2,'R',0);

					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(55,$hlineheight,convertWithDecimal($obj->package_actual_weight,5),'BR',1,'L');

					
					$pdf->cell(25,$hlineheight,'','L',0);
					$pdf->cell(115,$hlineheight,$shipperaddr3,'R',0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(55,$hlineheight,'VW / CBM','R',1,'L');

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(25,$hlineheight,'Tel','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					if($formtype=='INTERNAL-ALT'){
						$pdf->cell(115,$hlineheight,trim($obj->consignee_tel_number,' | '),'R',0);
					}
					else{
						$pdf->cell(115,$hlineheight,trim($obj->shipper_tel_number,' | '),'R',0);
					}


					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(55,$hlineheight,convertWithDecimal($obj->package_vw,5).' / '.convertWithDecimal($obj->package_cbm,5),'BR',1,'L');


					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(25,$hlineheight,'','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(115,$hlineheight,'','R',0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(55,$hlineheight,'Declared Value','R',1,'L');

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(25,$hlineheight,'','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(115,$hlineheight,'','R',0);

					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(55,$hlineheight,$obj->package_declared_value,'BR',1,'L');

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(40,$hlineheight,'','L',0);
					$pdf->cell(55,$hlineheight,'Shipper Name / Signature',0,0);
					$pdf->cell(15,$hlineheight,'',0,0);
					$pdf->cell(30,$hlineheight,'Date/Time','R',0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(55,$hlineheight,'Service','R',1,'L');

					

					$pdf->SetFont('Arial','B',$headerfont);


					$pdf->cell(140,$hlineheight,'CONSIGNEE INFORMATION','LTR',0);

					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(55,$hlineheight,$obj->servicedesc,'BR',1,'L');
					


					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(25,$hlineheight,'No. ','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					if($formtype=='INTERNAL-ALT'){
						$pdf->cell(115,$hlineheight,strtoupper($obj->shipper_account_number),'R',0);
					}
					else{
						$pdf->cell(115,$hlineheight,strtoupper($obj->consignee_account_number),'R',0);
					}

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(55,$hlineheight,'Document','R',1,'L');

					

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(25,$hlineheight,'Name ','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					if($formtype=='INTERNAL-ALT'){
						$pdf->cell(115,$hlineheight,strtoupper($obj->shipper_account_name),'R',0);
					}
					else{
						$pdf->cell(115,$hlineheight,strtoupper($obj->consignee_account_name),'R',0);
					}


					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(55,$hlineheight,$obj->document,'BR',1,'L');

					

					

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(25,$hlineheight,'Address ','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(115,$hlineheight,$consigneeaddr1,'R',0);


					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(55,$hlineheight,'Handling Instruction','R',1,'L');

					

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(25,$hlineheight,'','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(115,$hlineheight,$consigneeaddr2,'R',0);

					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(55,$hlineheight,$handlinginstructionstr1,'R',1,'L');


					$pdf->cell(25,$hlineheight,'','L',0);
					$pdf->cell(115,$hlineheight,$consigneeaddr3,'R',0);

					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(55,$hlineheight,$handlinginstructionstr2,'R',1,'L');

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(35,$hlineheight,'Secondary Recipient ','L',0);
					$pdf->SetFont('Arial','',$headerfont);

					if($formtype=='INTERNAL-ALT'){
						$pdf->cell(105,$hlineheight,'','R',0);
					}
					else{
						$pdf->cell(105,$hlineheight,strtoupper($obj->secondary_recipient),'R',0);
					}
					

					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(55,$hlineheight,$handlinginstructionstr2,'R',1,'L');

					

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(35,$hlineheight,'Tel','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					if($formtype=='INTERNAL-ALT'){
						$pdf->cell(105,$hlineheight,trim($obj->shipper_tel_number,' | '),'R',0);
					}
					else{
						$pdf->cell(105,$hlineheight,trim($obj->consignee_tel_number,' | '),'R',0);
					}

					


					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(55,$hlineheight,$handlinginstructionstr3,'R',1,'L');

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(25,$hlineheight,'','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(115,$hlineheight,'','R',0);


					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(55,$hlineheight,$handlinginstructionstr4,'R',1,'L');

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(25,$hlineheight,'','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(115,$hlineheight,'','R',0);

					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(55,$hlineheight,$handlinginstructionstr5,'BR',1,'L');

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(65,$hlineheight,'Received By / Signature','L',0);
					$pdf->cell(40,$hlineheight,'Relationship / ID No.',0,0);
					$pdf->cell(15,$hlineheight,'',0,0);
					$pdf->cell(20,$hlineheight,'Date/Time','R',0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(55,$hlineheight,'Delivery Instruction','R',1,'L');
					

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(140,$hlineheight,'SHIPMENT DESCRIPTION','LTR',0);

					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(55,$hlineheight,$obj->deliveryinstruction,'BR',1,'L');

					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(140,$hlineheight,$shipmentdesc1,'LR',0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(55,$hlineheight,'Amount for Collection','R',1,'L');

					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(140,$hlineheight,$shipmentdesc2,'LR',0);

					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(55,$hlineheight,$obj->amount_for_collection,'BR',1,'L');

					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(140,$hlineheight,$shipmentdesc3,'LR',0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(55,$hlineheight,'Pay Mode','R',1,'L');

					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(140,$hlineheight,$shipmentdesc4,'LR',0);

					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(55,$hlineheight,$obj->package_pay_mode,'R',1,'L');

					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(140,$hlineheight,strtoupper("NOTE: ".$remarks),'LBR',0);


					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(55,$hlineheight,'','BR',1,'L');

					/*$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(195,$hlineheight,'CHARGES','LR',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(35,$hlineheight,'Freight Computation ','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(65,$hlineheight,strtoupper($obj->package_freight_computation),0,0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(35,$hlineheight,'Fuel Charges ',0,0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(60,$hlineheight,$obj->package_fuel_rate,'R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(35,$hlineheight,'Chargeable Weight','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(65,$hlineheight,$obj->package_chargeable_weight,0,0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(35,$hlineheight,'Bunker Charges ',0,0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(60,$hlineheight,$obj->package_bunker_rate,'R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(35,$hlineheight,'Valuation Charges ','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(65,$hlineheight,strtoupper($obj->package_valuation),0,0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(35,$hlineheight,'Insurance Charges ',0,0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(60,$hlineheight,$obj->package_insurance_rate,'R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(35,$hlineheight,'Freight Charges ','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(65,$hlineheight,$obj->package_freight,0,0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(35,$hlineheight,'Minimum Charges ',0,0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(60,$hlineheight,$obj->package_minimum_rate,'R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(35,$hlineheight,'Other Charges ','BL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(65,$hlineheight,$othercharges,'B',0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(35,$hlineheight,' ','B',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(60,$hlineheight,'','BR',1);*/

				
					$pdf->cell(195,2,'','LR',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(40,$hlineheight,'Carrier','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(155,$hlineheight,$obj->carrier,'R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(40,$hlineheight,'Shipper Rep Name','LB',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(155,$hlineheight,$obj->shipper_rep_name,'RB',1);
					$pdf->ln();

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(55,$hlineheight,'Moved Out (Consignee/Company)',0,0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(30,$hlineheight,'','B',0);
					$pdf->cell(110,$hlineheight,'',0,1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(55,$hlineheight,'Unknown Consignee',0,0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(30,$hlineheight,'','B',0);
					$pdf->cell(110,$hlineheight,'',0,1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(55,$hlineheight,'No one to receive',0,0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(30,$hlineheight,'','B',0);
					$pdf->cell(110,$hlineheight,'',0,1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(55,$hlineheight,'Refused to accept by:',0,0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(30,$hlineheight,'','B',0);
					$pdf->cell(110,$hlineheight,'',0,1);


					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(55,$hlineheight,'Incomplete/Unalocated Address',0,0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(30,$hlineheight,'','B',0);
					$pdf->cell(110,$hlineheight,'',0,1);

					
					$pdf->cell(20,$hlineheight,'',0,0);
					$pdf->cell(110,$hlineheight,'(Consignee Out / House Closed / No authorized Representative / No ID presented upon delivery)',0,1);




					/*$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(120,$hlineheight,'','L',0);
					

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'SUBTOTAL','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(45,$hlineheight,convertWithDecimal($obj->subtotal,2),'R',1,'R');

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(40,$hlineheight,'Carrier','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(80,$hlineheight,$obj->carrier,0,0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'VAT','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(45,$hlineheight,convertWithDecimal($obj->vat,2),'R',1,'R');

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(40,$hlineheight,'Shipper Rep Name','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(80,$hlineheight,$obj->shipper_rep_name,0,0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'*** Zero Rated ***','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(45,$hlineheight,$zeroratedflag,'R',1,'L');

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(120,$hlineheight,'','BL',0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'TOTAL AMOUNT','LB',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(45,$hlineheight,convertWithDecimal($obj->total_amount,2),'BR',1,'R');*/

				





				}
				
			}
			else{
				echo "INVALID";
			}



$pdf->Output();
	



?>