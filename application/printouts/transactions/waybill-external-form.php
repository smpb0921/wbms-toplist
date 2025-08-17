<?php

include("../../../config/connection.php");
include("../../../config/checklogin.php");
include("../../../config/functions.php");
require('../../../resources/htmltopdf/fpdf.php');
include("../../../classes/company-information.class.php");

$formtype = isset($_GET['formtype'])?escapeString($_GET['formtype']):'';
$txnnumber = isset($_GET['txnnumber'])?escapeString($_GET['txnnumber']):'';
$bolnumber = isset($_GET['bolnumber'])?escapeString($_GET['bolnumber']):'';
$remarks = isset($_GET['remarks'])?escapeString(trim($_GET['remarks'])):'';
function pushArray($array,$data){
	if(trim($data)!=''){
		array_push($array, $data);
	}
	return $array;
}



class PDF extends FPDF
{	
	function Footer()
	{	
		/*$user = '';
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
	    $this->Cell(180,3,'PAGE '.$this->PageNo().' of {nb}',0,0,'R');*/

	    
	}
}

$pdf = new PDF('P','mm','LETTER');
//$pdf->AliasNbPages();
$now = date('m/d/Y h:i:s A');



$maxlen = 60;
$maxlen1 = 55;
$maxlen2 = 110;
		

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
				                txn_waybill.secondary_recipient,
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
								txn_waybill.waybill_type,
								txn_waybill.express_transaction_type,
								txn_waybill.project,
								txn_waybill.contract_number,
								txn_waybill.buyer_code,
								txn_waybill.cost_center,
								txn_waybill.cost_center_code,
								txn_waybill.customer_number,
								txn_waybill.phase_parking_slot,
						    	concat(printeduser.first_name,' ',printeduser.last_name) as printedby,
						    	third_party_logistic.description as thirdpartylogistic,
				                origintbl.description as origin,
				                destinationtbl.description as destination,
				                services.description as servicedesc,
				                mode_of_transport.description as modeoftransport,
				                delivery_instruction.description as deliveryinstruction,
				                destination_route.description as destinationroute,
				                accompanying_documents.description as document,
				                transport_charges.description as transportcharges,
				                carrier.description as carrier,
				                group_concat(handling_instruction.description separator ', ') as handlinginstruction
				         from txn_waybill
				         left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id 
				         left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id 
				         left join services on services.id=txn_waybill.package_service
				         left join mode_of_transport on mode_of_transport.id=txn_waybill.package_mode_of_transport
				         left join delivery_instruction on delivery_instruction.id=txn_waybill.package_delivery_instruction
				         left join destination_route on destination_route.id=txn_waybill.destination_route_id
				         left join accompanying_documents on accompanying_documents.id=txn_waybill.package_document
				         left join transport_charges on transport_charges.id=txn_waybill.package_transport_charges
				         left join carrier on carrier.id=txn_waybill.carrier_id
				         left join third_party_logistic on third_party_logistic.id=txn_waybill.third_party_logistic_id
				         left join user as printeduser on printeduser.id=txn_waybill.printed_by
				         left join txn_waybill_handling_instruction on txn_waybill_handling_instruction.waybill_number=txn_waybill.waybill_number
				         left join handling_instruction on handling_instruction.id=txn_waybill_handling_instruction.handling_instruction_id
				         where txn_waybill.waybill_number = '$txnnumber' 
				         group by txn_waybill.waybill_number");

			if(getNumRows($rs)==1){
				while($obj = fetch($rs)){
					$zeroratedflag = $obj->zero_rated_flag==1?'YES':'NO';
					$documentdate = dateFormat($obj->document_date, "m/d/Y");
					$deliverydate = dateFormat($obj->delivery_date, "m/d/Y");

					if($formtype=='EXTERNAL-ALT'){
						$shipperacctnumber = $obj->consignee_account_number;
						$shipperacctname = $obj->consignee_account_name;
						$shippercompanyname = $obj->consignee_company_name;
						$shippertelnumber = $obj->consignee_tel_number;

						$consigneeacctnumber = $obj->shipper_account_number;
						$consigneeacctname = $obj->shipper_account_name;
						$consigneecompanyname = $obj->shipper_company_name;
						$consigneetelnumber = $obj->shipper_tel_number;



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



					    $constreetdistrictarray = array();
					    if(trim($obj->shipper_street_address)!=''){
					    	array_push($constreetdistrictarray,$obj->shipper_street_address);
					    }
					    if(trim($obj->shipper_district)!=''){
					    	array_push($constreetdistrictarray,$obj->shipper_district);
					    }
					    $constreetdistrict = implode(', ', $constreetdistrictarray);

					    $maxlen1 = 50;
					    $constreetdistrictstr1 = lineBreak($constreetdistrict, $maxlen1);
					    $constreetdistrictstrrem1 = trim(str_replace($constreetdistrictstr1, '', $constreetdistrict));
						$constreetdistrictstr2= lineBreak($constreetdistrictstrrem1, $maxlen1);


					    $shipstreetdistrictarray = array();
					    if(trim($obj->consignee_street_address)!=''){
					    	array_push($shipstreetdistrictarray,$obj->consignee_street_address);
					    }
					    if(trim($obj->consignee_district)!=''){
					    	array_push($shipstreetdistrictarray,$obj->consignee_district);
					    }
					    $shipstreetdistrict = implode(', ', $shipstreetdistrictarray);

					    $maxlen1 = 50;
					    $shipstreetdistrictstr1 = lineBreak($shipstreetdistrict, $maxlen1);
					    $shipstreetdistrictstrrem1 = trim(str_replace($shipstreetdistrictstr1, '', $shipstreetdistrict));
						$shipstreetdistrictstr2= lineBreak($shipstreetdistrictstrrem1, $maxlen1);
					}
					else{

						$shipperacctnumber = $obj->shipper_account_number;
						$shipperacctname = $obj->shipper_account_name;
						$shippercompanyname = $obj->shipper_company_name;
						$shippertelnumber = $obj->shipper_tel_number;

						$consigneeacctnumber = $obj->consignee_account_number;
						$consigneeacctname = $obj->consignee_account_name;
						$consigneecompanyname = $obj->consignee_company_name;
						$consigneetelnumber = $obj->consignee_tel_number;


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




					    $constreetdistrictarray = array();
					    if(trim($obj->consignee_street_address)!=''){
					    	array_push($constreetdistrictarray,$obj->consignee_street_address);
					    }
					    if(trim($obj->consignee_district)!=''){
					    	array_push($constreetdistrictarray,$obj->consignee_district);
					    }
					    $constreetdistrict = implode(', ', $constreetdistrictarray);

					    $maxlen1 = 50;
					    $constreetdistrictstr1 = lineBreak($constreetdistrict, $maxlen1);
					    $constreetdistrictstrrem1 = trim(str_replace($constreetdistrictstr1, '', $constreetdistrict));
						$constreetdistrictstr2= lineBreak($constreetdistrictstrrem1, $maxlen1);


					    $shipstreetdistrictarray = array();
					    if(trim($obj->shipper_street_address)!=''){
					    	array_push($shipstreetdistrictarray,$obj->shipper_street_address);
					    }
					    if(trim($obj->shipper_district)!=''){
					    	array_push($shipstreetdistrictarray,$obj->shipper_district);
					    }
					    $shipstreetdistrict = implode(', ', $shipstreetdistrictarray);

					    $maxlen1 = 50;
					    $shipstreetdistrictstr1 = lineBreak($shipstreetdistrict, $maxlen1);
					    $shipstreetdistrictstrrem1 = trim(str_replace($shipstreetdistrictstr1, '', $shipstreetdistrict));
						$shipstreetdistrictstr2= lineBreak($shipstreetdistrictstrrem1, $maxlen1);

					}



				    



				    


				    $wbremarks1 = lineBreak($obj->remarks, $maxlen2);
					$wbremarksrem1 = trim(str_replace($wbremarks1, '', $obj->remarks));
					$wbremarks2 = lineBreak($wbremarksrem1, $maxlen2);
					$wbremarksrem2 = trim(str_replace($wbremarks2, '', $wbremarksrem1));
				    $wbremarks3 = lineBreak($wbremarksrem2, $maxlen2);

				    $handlinginstructionstr1 = lineBreak($obj->handlinginstruction, 35);
					$handlinginstructionstrrem1 = trim(str_replace($handlinginstructionstr1, '', $obj->handlinginstruction));
					$handlinginstructionstr2 = lineBreak($handlinginstructionstrrem1, 35);
					$handlinginstructionstrrem2 = trim(str_replace($handlinginstructionstr2, '', $handlinginstructionstrrem1));
				    $handlinginstructionstr3 = lineBreak($handlinginstructionstrrem2,35);
				    $handlinginstructionstrrem3 = trim(str_replace($handlinginstructionstr3, '', $handlinginstructionstrrem2));
				    $handlinginstructionstr4 = lineBreak($handlinginstructionstrrem3,35);
				    $handlinginstructionstrrem4 = trim(str_replace($handlinginstructionstr4, '', $handlinginstructionstrrem3));
				    $handlinginstructionstr5 = lineBreak($handlinginstructionstrrem4,35);


				    if($obj->third_party_logistic_id==1||$obj->thirdpartylogistic=='FEDEX'){
				    	$pdf->AddPage();

				    	$contentsfield = concatData(
														array($obj->shipment_description,
															  $obj->project,
															  $obj->contract_number,
															  $obj->buyer_code,
															  $obj->cost_center,
															  $obj->cost_center_code,
															  $obj->customer_number,
															  $obj->phase_parking_slot),
														'|'
												 );
				    	

				    	$maxlen1 = 35;
				    	$shipmentdesc1 = lineBreak($contentsfield, $maxlen1);
						$shipmentdescrem1 = trim(str_replace($shipmentdesc1, '', $contentsfield));
						$shipmentdesc2 = lineBreak($shipmentdescrem1, $maxlen1);
						$shipmentdescrem2 = trim(str_replace($shipmentdesc2, '', $shipmentdescrem1));
					    $shipmentdesc3 = lineBreak($shipmentdescrem2, $maxlen1);
					    $shipmentdescrem3 = trim(str_replace($shipmentdesc3, '', $shipmentdescrem2));
					    $shipmentdesc4 = lineBreak($shipmentdescrem3, $maxlen1);
					    $shipmentdescrem4 = trim(str_replace($shipmentdesc4, '', $shipmentdescrem3));
					    $shipmentdesc5 = lineBreak($shipmentdescrem4, $maxlen1);

						 $marginleft = 1;
					     $pdf->SetTextColor(0, 0, 0);

					     $pdf->SetFont('Arial','',14);
					     $pdf->SetY(29);
	 					 $pdf->cell(237,5,'',0,0);
	 					 $pdf->cell(30,5,strtoupper($bolnumber),0,0);

					     $pdf->SetFont('Arial','',8);

					     $pdf->SetY(35);
	 					 $pdf->cell($marginleft+5,5,'',0,0);
	 					 $pdf->cell(60,5,dateFormat($obj->pickup_date,'M d, Y'),0,0);

	 					 $pdf->SetY(38);
	 					 $pdf->cell($marginleft,5,'',0,0);
	 					 $pdf->cell(60,5,'',0,0);
	 					 $pdf->cell(30,5,$shippertelnumber,0,0);

						 $pdf->SetY(43);
	 					 $pdf->cell($marginleft,5,'',0,0);
	 					 $pdf->cell(60,5,$shipperacctname,0,0);
	 					 $pdf->cell(30,5,'',0,0);

	 					 $pdf->SetY(54);
	 					 $pdf->cell($marginleft,5,'',0,0);
	 					 $pdf->cell(60,5,$shippercompanyname,0,0);
	 					 $pdf->cell(30,5,'',0,0);


	 					 $pdf->SetY(65);
	 					 $pdf->cell($marginleft+3,5,'',0,0);
	 					 $pdf->cell(60,5,$shipstreetdistrictstr1,0,0);
	 					 $pdf->cell(30,5,'',0,0);
	 					 $pdf->SetY(67.5);
	 					 $pdf->cell($marginleft+3,5,'',0,0);
	 					 $pdf->cell(60,5,$shipstreetdistrictstr2,0,0);
	 					 $pdf->cell(30,5,'',0,0);

	 					 if($formtype=='EXTERNAL-ALT'){
	 					 	 $pdf->SetY(72);
		 					 $pdf->cell($marginleft,5,'',0,0);
		 					 $pdf->cell(60,5,strtoupper($obj->consignee_city).' / '.strtoupper($obj->consignee_state_province),0,0);
		 					 $pdf->cell(30,5,strtoupper($obj->consignee_zip_code),0,0);
	 					 }
	 					 else{
		 					 $pdf->SetY(72);
		 					 $pdf->cell($marginleft,5,'',0,0);
		 					 $pdf->cell(60,5,strtoupper($obj->shipper_city).' / '.strtoupper($obj->shipper_state_province),0,0);
		 					 $pdf->cell(30,5,strtoupper($obj->shipper_zip_code),0,0);
		 				 }

	 					 $pdf->SetY(77);
	 					 $pdf->cell($marginleft+5,5,'',0,0);
	 					 $pdf->cell(60,5,$obj->destination,0,0);

	 					 $pdf->SetY(82);
	 					 $pdf->cell($marginleft+5,5,'',0,0);
	 					 $pdf->cell(60,5,'',0,0);
	 					 $pdf->cell(30,5,$consigneetelnumber,0,0);

	 					 $pdf->SetY(90);
	 					 $pdf->cell($marginleft,5,'',0,0);
	 					 $pdf->cell(60,5,$consigneeacctname,0,0);
	 					 $pdf->cell(30,5,'',0,0);

	 					 $pdf->SetY(100);
	 					 $pdf->cell($marginleft,5,'',0,0);
	 					 $pdf->cell(60,5,$consigneecompanyname,0,0);
	 					 $pdf->cell(30,5,'',0,0);


	 					 $pdf->SetY(107);
	 					 $pdf->cell($marginleft+3,5,'',0,0);
	 					 $pdf->cell(60,5,$constreetdistrictstr1,0,0);
	 					 $pdf->cell(30,5,'',0,0);
	 					 $pdf->SetY(109.5);
	 					 $pdf->cell($marginleft+3,5,'',0,0);
	 					 $pdf->cell(60,5,$constreetdistrictstr2,0,0);
	 					 $pdf->cell(30,5,'',0,0);

	 					 if($formtype=='EXTERNAL-ALT'){
	 					 	 $pdf->SetY(115);
		 					 $pdf->cell($marginleft,5,'',0,0);
		 					 $pdf->cell(60,5,strtoupper($obj->shipper_city).' / '.strtoupper($obj->shipper_state_province),0,0);
		 					 $pdf->cell(30,5,strtoupper($obj->shipper_zip_code),0,0);
	 					 }
	 					 else{
		 					 $pdf->SetY(115);
		 					 $pdf->cell($marginleft,5,'',0,0);
		 					 $pdf->cell(60,5,strtoupper($obj->consignee_city).' / '.strtoupper($obj->consignee_state_province),0,0);
		 					 $pdf->cell(30,5,strtoupper($obj->consignee_zip_code),0,0);
		 				 }

	 					 $pdf->SetY(126);
	 					 $pdf->cell($marginleft,2,'',0,0);
	 					 $pdf->cell(75,3,strtoupper($shipmentdesc1),0,0);
	 					 $pdf->cell(30,3,strtoupper($obj->package_declared_value),0,1);
	 					 $pdf->cell($marginleft,2,'',0,0);
	 					 $pdf->cell(75,3,strtoupper($shipmentdesc2),0,1);
	 					 $pdf->cell($marginleft,2,'',0,0);
	 					 $pdf->cell(75,3,strtoupper($shipmentdesc3),0,1);
	 					 $pdf->cell($marginleft,2,'',0,0);
	 					 $pdf->cell(75,3,strtoupper($shipmentdesc4),0,1);
	 					 $pdf->cell($marginleft,2,'',0,0);
	 					 $pdf->cell(75,3,strtoupper("NOTE: ".$remarks),0,1);

	 					 
	 					 $pdf->SetFont('Arial','',7);
			 			 $pdf->SetY(145);
			 			 $pdf->cell($marginleft,5,'',0,0);
			 			 $pdf->cell(30,5,$obj->package_number_of_packages,0,0);

	 					$dimarray = array();
	 					$getdimensions = query("select length, 
	 						                           width, 
	 						                           height, 
	 						                           quantity, 
	 						                           volumetric_weight, 
	 						                           cbm, 
	 						                           uom, 
	 						                           actual_weight 
	 						                    from txn_waybill_package_dimension
	 						                    where waybill_number='$txnnumber'");
	 					$y = 145;
	 					while($objdim = fetch($getdimensions)){
	 							
	 						
	 							
	 						
	 							array_push($dimarray, array(
	 														"length"=>$objdim->length,
	 														"width"=>$objdim->width,
	 														"height"=>$objdim->height,
	 														"volumetric_weight"=>$objdim->volumetric_weight,
	 														"cbm"=>$objdim->cbm,
	 														"uom"=>$objdim->uom,
	 														"actualweight"=>$objdim->actual_weight,
	 														"quantity"=>$objdim->quantity
	 							));	

	 							 $pdf->SetFont('Arial','',7);
			 					 $pdf->SetY($y);
			 					 $pdf->cell($marginleft,5,'',0,0);
			 					 $pdf->cell(30,5,'',0,0);
			 					 $pdf->cell(30,5,$objdim->length.' x '.$objdim->width.' x '.$objdim->height,0,0);
			 					 $pdf->cell(30,5,($objdim->actual_weight*$objdim->quantity),0,0);

			 					 $y = $y+3;


	 						
	 					}

	 					


	 				}
	 				else if($obj->thirdpartylogistic=='AIR21'){
	 					$pdf->AddPage();
	 					$maxlen1 = 25;
				    	$shipmentdesc1 = lineBreak($obj->shipment_description, $maxlen1);
						$shipmentdescrem1 = trim(str_replace($shipmentdesc1, '', $obj->shipment_description));
						$shipmentdesc2 = lineBreak($shipmentdescrem1, $maxlen1);
						$shipmentdescrem2 = trim(str_replace($shipmentdesc2, '', $shipmentdescrem1));
					    $shipmentdesc3 = lineBreak($shipmentdescrem2, $maxlen1);
					    $shipmentdescrem3 = trim(str_replace($shipmentdesc3, '', $shipmentdescrem2));
					    $shipmentdesc4 = lineBreak($shipmentdescrem3, $maxlen1);
					    $shipmentdescrem4 = trim(str_replace($shipmentdesc4, '', $shipmentdescrem3));
					    $shipmentdesc5 = lineBreak($shipmentdescrem4, $maxlen1);

					    $maxlen = 45;

					    if($formtype=='EXTERNAL-ALT'){
						    $shipperaddr = concatAddress(
															array($obj->consignee_street_address,
																  $obj->consignee_district,
																  $obj->consignee_city,
																  $obj->consignee_state_province,
																  $obj->consignee_zip_code,
																  $obj->consignee_country)
													 );
							
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
						}

						$shipperaddr1 = lineBreak($shipperaddr, $maxlen);
						$shipperaddrrem1 = trim(str_replace($shipperaddr1, '', $shipperaddr));
						$shipperaddr2 = lineBreak($shipperaddrrem1, $maxlen);
						$shipperaddrrem2 = trim(str_replace($shipperaddr2, '', $shipperaddrrem1));
						$shipperaddr3 = lineBreak($shipperaddrrem2, $maxlen);

	 					 $marginleft = 1;
	 					 $pdf->SetTextColor(0, 0, 0);
	 					 $pdf->SetFont('Arial','',6);

	 					 $pdf->SetY(25);
	 					 $pdf->cell($marginleft+20,5,'',0,0);
	 					 $pdf->cell(35,5,dateFormat($obj->pickup_date,'M d, Y'),0,0);

					     $pdf->SetFont('Arial','',10);

					     $pdf->SetY(32);
	 					 $pdf->cell($marginleft,5,'',0,0);
	 					 $pdf->cell(32,5,'',0,0);
	 					 $pdf->cell(30,5,'1000003418',0,0);

	 					  $pdf->SetFont('Arial','',8);


						 $pdf->SetY(42);
	 					 $pdf->cell($marginleft,5,'',0,0);
	 					 $pdf->cell(60,5,$shipperacctname,0,0);

	 					 $pdf->SetY(49);
	 					 $pdf->cell($marginleft,5,'',0,0);
	 					 $pdf->cell(12,5,'',0,0);
	 					 $pdf->cell(60,5,$shippercompanyname,0,0);
	 					 $pdf->cell(30,5,'',0,0);

	 					 $pdf->SetY(54);
	 					 $pdf->cell($marginleft,5,'',0,0);
	 					 $pdf->cell(37,5,$shippertelnumber,0,0);
	 					 $pdf->cell(30,5,'',0,0);


	 					 $pdf->SetY(65);
	 					 $pdf->cell($marginleft,5,'',0,0);
	 					 $pdf->cell(35,3,$shipperaddr1,0,1);
	 					 $pdf->cell(35,3,$shipperaddr2,0,1);
	 					 $pdf->cell(35,3,$shipperaddr3,0,0);


	 					 if($formtype=='EXTERNAL-ALT'){
	 					 	 $pdf->SetY(76);
		 					 $pdf->cell($marginleft,5,'',0,0);
		 					 $pdf->cell(20,5,'',0,0);
		 					 $pdf->cell(43,5,strtoupper($obj->consignee_city),0,0);
		 					 $pdf->cell(30,5,strtoupper($obj->consignee_zip_code),0,0);

		 					 $pdf->SetY(82);
		 					 $pdf->cell($marginleft,5,'',0,0);
		 					 $pdf->cell(10,5,'',0,0);
		 					 $pdf->cell(38,5,strtoupper($obj->consignee_state_province),0,0);
		 					 $pdf->cell(30,5,'',0,0);
	 					 }
	 					 else{
		 					 $pdf->SetY(76);
		 					 $pdf->cell($marginleft,5,'',0,0);
		 					 $pdf->cell(20,5,'',0,0);
		 					 $pdf->cell(43,5,strtoupper($obj->shipper_city),0,0);
		 					 $pdf->cell(30,5,strtoupper($obj->shipper_zip_code),0,0);

		 					 $pdf->SetY(82);
		 					 $pdf->cell($marginleft,5,'',0,0);
		 					 $pdf->cell(10,5,'',0,0);
		 					 $pdf->cell(38,5,strtoupper($obj->shipper_state_province),0,0);
		 					 $pdf->cell(30,5,'',0,0);
		 				}

	 					 $pdf->SetFont('Arial','',10);

					     $pdf->SetY(89);
	 					 $pdf->cell($marginleft,5,'',0,0);
	 					 $pdf->cell(32,5,'',0,0);
	 					 $pdf->cell(30,5,'',0,0);

	 					  $pdf->SetFont('Arial','',8);


						 $pdf->SetY(100);
	 					 $pdf->cell($marginleft,5,'',0,0);
	 					 $pdf->cell(60,5,$consigneeacctname,0,0);

	 					 $pdf->SetY(108);
	 					 $pdf->cell($marginleft,5,'',0,0);
	 					 $pdf->cell(12,5,'',0,0);
	 					 $pdf->cell(60,5,$consigneecompanyname,0,0);
	 					 $pdf->cell(30,5,'',0,0);

	 					 $pdf->SetY(114);
	 					 $pdf->cell($marginleft,5,'',0,0);
	 					 $pdf->cell(8,5,'',0,0);
	 					 $pdf->cell(37,5,$consigneetelnumber,0,0);
	 					 //$pdf->cell(30,5,'<MOBILE HERE>',0,0);

	 					 if($formtype=='EXTERNAL-ALT'){
	 					 	 $pdf->SetY(127);
		 					 $pdf->cell($marginleft,5,'',0,0);
		 					 $pdf->cell(35,5,strtoupper($obj->shipper_street_address).', '.strtoupper($obj->shipper_district),0,0);

		 					 $pdf->SetY(134);
		 					 $pdf->cell($marginleft,5,'',0,0);
		 					 $pdf->cell(13,5,'',0,0);
		 					 $pdf->cell(43,5,strtoupper($obj->shipper_city),0,0);
		 					 $pdf->cell(30,5,strtoupper($obj->shipper_zip_code),0,0);

		 					 $pdf->SetY(143);
		 					 $pdf->cell($marginleft,5,'',0,0);
		 					 $pdf->cell(10,5,'',0,0);
		 					 $pdf->cell(38,5,strtoupper($obj->shipper_state_province),0,0);
		 					 $pdf->cell(30,5,'',0,0);
	 					 }
	 					 else{
		 					 $pdf->SetY(127);
		 					 $pdf->cell($marginleft,5,'',0,0);
		 					 $pdf->cell(35,5,strtoupper($obj->consignee_street_address).', '.strtoupper($obj->consignee_district),0,0);

		 					 $pdf->SetY(134);
		 					 $pdf->cell($marginleft,5,'',0,0);
		 					 $pdf->cell(13,5,'',0,0);
		 					 $pdf->cell(43,5,strtoupper($obj->consignee_city),0,0);
		 					 $pdf->cell(30,5,strtoupper($obj->consignee_zip_code),0,0);

		 					 $pdf->SetY(143);
		 					 $pdf->cell($marginleft,5,'',0,0);
		 					 $pdf->cell(10,5,'',0,0);
		 					 $pdf->cell(38,5,strtoupper($obj->consignee_state_province),0,0);
		 					 $pdf->cell(30,5,'',0,0);
		 				 }

	 					 $pdf->SetFont('Arial','',7);
	 					 $pdf->SetY(162);
	 					 $pdf->cell($marginleft,2,'',0,0);
	 					 $pdf->cell(62,2,strtoupper($shipmentdesc1),0,0);
	 					 $pdf->SetFont('Arial','',7);
	 					 $pdf->cell(62,2,convertWithDecimal($obj->package_declared_value,5),0,1);
	 					 $pdf->SetFont('Arial','',7);
	 					 $pdf->cell($marginleft,2,'',0,0);
	 					 $pdf->cell(62,2,strtoupper($shipmentdesc2),0,1);
	 					 $pdf->cell($marginleft,2,'',0,0);
	 					 $pdf->cell(62,2,strtoupper($shipmentdesc3),0,1);
	 					 $pdf->cell($marginleft,2,'',0,0);
	 					 $pdf->cell(62,2,strtoupper($shipmentdesc4),0,1);
	 					 $pdf->cell($marginleft,2,'',0,0);
	 					 $pdf->cell(62,2,strtoupper("NOTE: ".$remarks),0,1);


	 					

	 				}
	 				else if($obj->thirdpartylogistic=='LBC'){
	 					$maxlen2 = 30;
	 					$wbremarks1 = lineBreak($obj->remarks, $maxlen2);
						$wbremarksrem1 = trim(str_replace($wbremarks1, '', $obj->remarks));
						$wbremarks2 = lineBreak($wbremarksrem1, $maxlen2);
						$wbremarksrem2 = trim(str_replace($wbremarks2, '', $wbremarksrem1));
					    $wbremarks3 = lineBreak($wbremarksrem2, $maxlen2);

					    $maxlen = 50;
					    $consigneeaddr1 = lineBreak($consigneeaddr, $maxlen);
						$consigneeaddrrem1 = trim(str_replace($consigneeaddr1, '', $consigneeaddr));
						$consigneeaddr2 = lineBreak($consigneeaddrrem1, $maxlen);
						$consigneeaddrrem2 = trim(str_replace($consigneeaddr2, '', $consigneeaddrrem1));
				    	$consigneeaddr3 = lineBreak($consigneeaddrrem2, $maxlen);

	 					$numofpackages = $obj->package_number_of_packages>0?$obj->package_number_of_packages:1;
	 					$dimarray = array();
	 					$getdimensions = query("select length, 
	 						                           width, 
	 						                           height, 
	 						                           quantity, 
	 						                           volumetric_weight, 
	 						                           cbm, 
	 						                           uom, 
	 						                           actual_weight 
	 						                    from txn_waybill_package_dimension
	 						                    where waybill_number='$txnnumber'");
	 					while($objdim = fetch($getdimensions)){
	 						for ($i=0; $i <$objdim->quantity ; $i++) { 
	 							
	 						
	 							array_push($dimarray, array(
	 														"length"=>$objdim->length,
	 														"width"=>$objdim->width,
	 														"height"=>$objdim->height,
	 														"volumetric_weight"=>$objdim->volumetric_weight,
	 														"cbm"=>$objdim->cbm,
	 														"uom"=>$objdim->uom,
	 														"actualweight"=>$objdim->actual_weight
	 							));	
	 						}
	 					}
	 					for ($i=0; $i < $obj->package_number_of_packages; $i++) { 
	 							$pdf->AddPage();
	 					
			 					$documentdate = dateFormat($obj->document_date,'M d, Y');
			 					$maxlen1 = 30;
						    	$shipmentdesc1 = lineBreak($obj->shipment_description, $maxlen1);
								$shipmentdescrem1 = trim(str_replace($shipmentdesc1, '', $obj->shipment_description));
								$shipmentdesc2 = lineBreak($shipmentdescrem1, $maxlen1);
								$shipmentdescrem2 = trim(str_replace($shipmentdesc2, '', $shipmentdescrem1));
							    $shipmentdesc3 = lineBreak($shipmentdescrem2, $maxlen1);
							    $shipmentdescrem3 = trim(str_replace($shipmentdesc3, '', $shipmentdescrem2));
							    $shipmentdesc4 = lineBreak($shipmentdescrem3, $maxlen1);
							    $shipmentdescrem4 = trim(str_replace($shipmentdesc4, '', $shipmentdescrem3));
							    $shipmentdesc5 = lineBreak($shipmentdescrem4, $maxlen1);

			 					 $marginleft = 102;
			 					 $pdf->SetTextColor(0, 0, 0);
			 					 $pdf->SetFont('Arial','',8);

			 					 $pdf->SetY(16);
			 					 $pdf->cell(10,5,'',0,0);
			 					 $pdf->cell(35,5,strtoupper($bolnumber),0,0);
			 					 $pdf->cell(75,5,'',0,0);
			 					 $pdf->cell(30,5,$documentdate,0,0);



			 					 $pdf->SetY(21);
			 					 $pdf->cell(10,5,'',0,0);
			 					 $pdf->cell(35,5,$obj->origin,0,0);
			 					 $pdf->cell(75,5,'',0,0);
			 					 $pdf->cell(30,5,$obj->destination,0,0);

							    

							     $pdf->SetY(25);
			 					 $pdf->cell(10,5,'',0,0);
			 					 $pdf->cell(35,5,strtoupper($shipperacctname),0,0);


			 					 $careof = '';
			 					 if(trim($obj->secondary_recipient)!=''&&$formtype!='EXTERNAL-ALT'){
			 					 	$careof = " c/o ".$obj->secondary_recipient;
			 					 }

			 					 $pdf->SetY(27);
			 					 $pdf->cell(45,5,'',0,0);
			 					 $pdf->cell(75,5,'',0,0);
			 					 $pdf->cell(30,5,strtoupper($consigneeacctname).$careof,0,0);

			 					 $pdf->SetY(30);
			 					 $pdf->cell(45,5,'',0,0);
			 					 $pdf->cell(75,5,'',0,0);
			 					 $pdf->cell(30,5,strtoupper($consigneecompanyname),0,0);

			 					

			 					 $pdf->SetFont('Arial','',8);
			 					 $pdf->SetY(37);
			 					 $pdf->cell(10,5,'',0,0);
			 					 //$pdf->cell(35,5,strtoupper($obj->shipper_street_address),0,0);
			 					 $pdf->cell(35,5,'CBL COURIER EXPRESS INTL. INC',0,0);

			 					 $pdf->SetY(41);
			 					 $pdf->cell(10,5,'',0,0);
			 					 $pdf->cell(35,5,strtoupper('#104 L. Marquez Edison Ave. Brgy. Merville Paranaque'),0,0);

			 					 $pdf->SetY(67);
			 					 $pdf->cell(25,5,'',0,0);
			 					 $pdf->cell(35,5,convertWithDecimal($obj->package_declared_value,5),0,0);

			 					 $pdf->SetY(37);
			 					 $pdf->cell(10,5,'',0,0);
			 					 $pdf->cell(35,5,'',0,0);
			 					 $pdf->cell(64,5,'',0,0);
			 					 $pdf->cell(55,5,strtoupper($consigneeaddr1),0,0);
			 					

		 					     $pdf->SetY(40);
		                         $pdf->cell(10,5,'',0,0);
			 					 $pdf->cell(99,5,'',0,0);
			 					 $pdf->cell(55,5,strtoupper($consigneeaddr2),0,0);

			 					 $pdf->SetY(43);
		                         $pdf->cell(10,5,'',0,0);
			 					 $pdf->cell(99,5,'',0,0);
			 					 $pdf->cell(55,5,strtoupper($consigneeaddr3),0,0);

			 					 $pdf->SetY(53);
		                         $pdf->cell(10,5,'',0,0);
			 					 $pdf->cell(110,5,'',0,0);
			 					 $pdf->cell(55,5,$consigneetelnumber,0,0);

			 					 $pdf->SetY(100);
		                         $pdf->cell(60,5,'',0,0);
			 					 $pdf->cell(100,5,'',0,0);
			 					 $pdf->cell(55,5,$obj->deliveryinstruction,0,0);

			 					 if(count($dimarray)>0){
				 					 $pdf->SetY(100);
				 					 $pdf->cell(45,5,1,0,0);
				 					 $pdf->cell(40,5,'',0,0);
				 					 $pdf->cell(55,5,$dimarray[$i]['length'].' x '.$dimarray[$i]['width'].' x '.$dimarray[$i]['height'],0,0);

				 					 $pdf->SetY(100);
				 					 $pdf->cell(35,5,1,0,0);
				 					 $pdf->cell(25,5,convertWithDecimal($dimarray[$i]['actualweight'],5),0,0);

				 					 $pdf->SetFont('Arial','',6);
				 					 $pdf->SetY(94);
				 					 $pdf->cell(120,5,'',0,0);
				 					 $pdf->cell(25,5,strtoupper($shipmentdesc1),0,0);

				 					 $pdf->SetY(98);
				 					 $pdf->cell(120,5,'',0,0);
				 					 $pdf->cell(25,5,strtoupper($shipmentdesc2),0,0);

				 					 $pdf->SetY(102);
				 					 $pdf->cell(120,5,'',0,0);
				 					 $pdf->cell(25,5,strtoupper($shipmentdesc3),0,0);

				 					 $pdf->SetFont('Arial','',7);
				 					 $pdf->SetY(106);
				 					 $pdf->cell(120,5,'',0,0);
				 					 $pdf->cell(25,5,strtoupper("NOTE: ".$remarks),0,0);
								 }
								 else {
									 if($obj->waybill_type == "DOCUMENT"){

										$pdf->SetY(100);
										$pdf->cell(45,5,1,0,0);
										$pdf->cell(40,5,'',0,0);
										$pdf->cell(55,5,'',0,0);
	
										$pdf->SetY(100);
										$pdf->cell(35,5,1,0,0);
										$pdf->cell(25,5,convertWithDecimal($obj->package_actual_weight,5),0,0);
	
										$pdf->SetFont('Arial','',6);
										$pdf->SetY(94);
										$pdf->cell(120,5,'',0,0);
										$pdf->cell(25,5,strtoupper($shipmentdesc1),0,0);
  
										$pdf->SetY(98);
										$pdf->cell(120,5,'',0,0);
										$pdf->cell(25,5,strtoupper($shipmentdesc2),0,0);
  
										$pdf->SetY(102);
										$pdf->cell(120,5,'',0,0);
										$pdf->cell(25,5,strtoupper($shipmentdesc3),0,0);
  										

  										$pdf->SetFont('Arial','',7);
										$pdf->SetY(106);
										$pdf->cell(120,5,'',0,0);
										$pdf->cell(25,5,strtoupper("NOTE: ".$remarks),0,0);
									 }
								 }

			 			}

	 					
	 					 

	 				}






				}
				
			}
			else{
				
			}



$pdf->Output();
	



?>