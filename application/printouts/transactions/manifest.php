<?php

include("../../../config/connection.php");
include("../../../config/checklogin.php");
include("../../../config/functions.php");
require('../../../resources/htmltopdf/pdf-table.php');
include("../../../classes/company-information.class.php");

$txnnumber = isset($_GET['txnnumber'])?escapeString($_GET['txnnumber']):'';



class PDF extends PDF_MC_Table
{	
	function Footer()
	{
	    $this->SetY(-8);
	    $this->SetFont('Arial','B',8);
	    $this->Cell(200,3,'PAGE '.$this->PageNo().' of {nb}',0,0,'R');
	    
	}
}

$pdf = new PDF('P','mm','LETTER');
$pdf->AliasNbPages();
$pdf->AddPage();
$now = date('m/d/Y h:i:s A');

//$company = new company_information();
//$company->select();
//$cmp = $company->loadObjectlist();





$imgpath = '../../../barcode/';
$imagelogo = "../../../resources/printout-logo.png";

//$pdf->Image($imagelogo, $pdf->GetX(), $pdf->GetY(), 35)

//$imagelogo = "../../../resources/barcode-generator/generate-barcode.php?text=kaye";





		

		

			

			$fillcolor = getColor('primary_color');//array(214, 69, 65);
			$rowitem = getColor('secondary_color');


			
		

			$headerfont = 8;
			$detailfont = 7;
			$hlineheight = 5;
			$pdf->SetFont('Arial','',$detailfont);

			$fillcolor = getColor('primary_color');//array(214, 69, 65);
			$rowitem = getColor('secondary_color');

			$loadplanflag = getInfo("txn_manifest","load_plan_flag","where manifest_number='$txnnumber'");

			if($loadplanflag==1){

		      	$rs = query("select txn_manifest.id,
					                txn_manifest.load_plan_number,
					                txn_manifest.status,
					                txn_manifest.manifest_number,
					                txn_manifest.document_date,
					                txn_manifest.created_date,
					                txn_manifest.updated_date,
					                txn_manifest.created_by,
					                txn_manifest.updated_by,
					                txn_manifest.remarks,
					                txn_manifest.last_status_update_remarks,
					                txn_manifest.trucker_name,
					                txn_manifest.truck_type,
					                txn_manifest.plate_number,
					                txn_manifest.driver_name,
					                txn_manifest.contact_number,
					                txn_load_plan.location_id,
					                txn_load_plan.carrier_id,
					                txn_load_plan.origin_id,
					                txn_load_plan.destination_id,
					                txn_load_plan.agent_id,
					                txn_load_plan.mawbl_bl,
					                txn_load_plan.eta,
					                txn_load_plan.etd,
					                concat(cuser.first_name,' ',cuser.last_name) as createdby,
					                concat(uuser.first_name,' ',uuser.last_name) as updatedby,
					                location.code as loccode,
					                location.description as locdesc,
					                carrier.description as carrierdesc,
					                carrier.code as carriercode,
					                vehicle_type.description as vehicletype,
					                origintbl.description as origin,
					                mode_of_transport.description as modeoftransport,
					                group_concat(destinationtbl.description separator ', ') as destination,
					                agent.company_name as agent,
									agent_contact.mobile_number as agentcontact,
									agent.company_street_address,
									agent.company_district,
									agent.company_city,
									agent.company_state_province,
									agent.company_zip_code,
									agent.company_country
					         from txn_manifest
							 left join agent_contact on agent_contact.agent_id=txn_manifest.agent_id and default_flag=1
					         left join txn_load_plan on txn_load_plan.load_plan_number=txn_manifest.load_plan_number
					         left join user as cuser on cuser.id=txn_manifest.created_by
					         left join user as uuser on uuser.id=txn_manifest.updated_by
					         left join location on location.id=txn_load_plan.location_id
					         left join carrier on carrier.id=txn_manifest.trucker_name
					         left join vehicle_type on vehicle_type.id=txn_manifest.truck_type
	 						 left join origin_destination_port as origintbl on origintbl.id=txn_load_plan.origin_id 
	 						 left join txn_load_plan_destination on txn_load_plan_destination.load_plan_number=txn_load_plan.load_plan_number
					         left join origin_destination_port as destinationtbl on destinationtbl.id=txn_load_plan_destination.origin_destination_port_id 
					         left join mode_of_transport on mode_of_transport.id=txn_load_plan.mode_of_transport_id
					         left join agent on agent.id=txn_load_plan.agent_id
					         where txn_manifest.manifest_number = '$txnnumber'
					         group by txn_manifest.manifest_number");

		    }
		    else{
		    	$rs = query("select txn_manifest.id,
					                ifnull(txn_manifest.load_plan_number,'N/A') as load_plan_number,
					                txn_manifest.status,
					                txn_manifest.manifest_number,
					                txn_manifest.document_date,
					                txn_manifest.created_date,
					                txn_manifest.updated_date,
					                txn_manifest.created_by,
					                txn_manifest.updated_by,
					                txn_manifest.remarks,
					                txn_manifest.last_status_update_remarks,
					                txn_manifest.trucker_name,
					                txn_manifest.truck_type,
					                txn_manifest.plate_number,
					                txn_manifest.driver_name,
					                txn_manifest.contact_number,
					                txn_manifest.mawbl as mawbl_bl,
					                txn_manifest.eta,
					                txn_manifest.etd,
					                concat(cuser.first_name,' ',cuser.last_name) as createdby,
					                concat(uuser.first_name,' ',uuser.last_name) as updatedby,
					                location.code as loccode,
					                location.description as locdesc,
					                carrier.description as carrierdesc,
					                vehicle_type.description as vehicletype,
					                carrier.code as carriercode,
					                origintbl.description as origin,
					                group_concat(distinct destinationtbl.description separator ', ') as destination,
					                mode_of_transport.description as modeoftransport,
					                agent.company_name as agent,
									agent_contact.mobile_number as agentcontact,
									agent.company_street_address,
									agent.company_district,
									agent.company_city,
									agent.company_state_province,
									agent.company_zip_code,
									agent.company_country
					         from txn_manifest
							 left join agent_contact on agent_contact.agent_id=txn_manifest.agent_id and default_flag=1
					         left join user as cuser on cuser.id=txn_manifest.created_by
					         left join user as uuser on uuser.id=txn_manifest.updated_by
					         left join location on location.id=txn_manifest.location_id
					         left join carrier on carrier.id=trucker_name
					         left join vehicle_type on vehicle_type.id=truck_type
	 						 left join origin_destination_port as origintbl on origintbl.id=txn_manifest.origin_id
					         left join mode_of_transport on mode_of_transport.id=txn_manifest.mode_of_transport_id
					         left join agent on agent.id=txn_manifest.agent_id
					         left join txn_manifest_waybill on txn_manifest_waybill.manifest_number=txn_manifest.manifest_number
					         left join txn_waybill on txn_waybill.waybill_number=txn_manifest_waybill.waybill_number
					         left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id
					         where txn_manifest.manifest_number = '$txnnumber'
					         group by txn_manifest.manifest_number");
		    }
	      	$rowdeduct = 0;

			if(getNumRows($rs)==1){
				while($obj = fetch($rs)){
					$mode = strtolower(trim($obj->modeoftransport));
					$titleprefix = strpos($mode,'air')!== false?'AIRFREIGHT ':(strpos($mode,'sea')!== false?'SEAFREIGHT ':'FREIGHT ');


					$pdf->SetFont('Arial','B',14);
					$pdf->cell(125,5,$titleprefix.'MANIFEST TRANSMITTAL',0,0,'L');
					$pdf->SetFont('Arial','B',14);
					$pdf->SetTextColor(99, 158, 188);
					$pdf->cell(70,5,'# '.$txnnumber,0,1,'R');

					$pdf->SetTextColor(0, 0, 0);

					


					$pdf->cell(200,5,'','B',1);
					
					

					$agentaddressarr = [];
					array_push($agentaddressarr,
													$obj->company_street_address, 
													$obj->company_district, 
													$obj->company_city,
													$obj->company_state_province
													//$obj->company_country.' '.$obj->company_zip_code
					);

					$maxlen1 = 40;
					$agentaddress = concatData($agentaddressarr,', ');
					
					$agentaddress1 = lineBreak($agentaddress, $maxlen1);
					$agentaddressrem1 = trim(str_replace($agentaddress1, '', $agentaddress));
					$agentaddress2 = lineBreak($agentaddressrem1, $maxlen1);
					$agentaddressrem2 = trim(str_replace($agentaddress2, '', $agentaddressrem1));
				    $agentaddress3 = lineBreak($agentaddressrem2, $maxlen1);
				    $agentaddressrem3 = trim(str_replace($agentaddress3, '', $agentaddressrem2));
				    $agentaddress4 = lineBreak($agentaddressrem3, $maxlen1);
					

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'LOCATION','BL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(170,$hlineheight,'['.$obj->loccode.'] '.$obj->locdesc,'BR',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'LOAD PLAN NO.','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,$obj->load_plan_number,0,0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'DOCUMENT DATE','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,dateFormat($obj->document_date,'m/d/Y'),'R',1);


					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'ORIGIN','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,$obj->origin,0,0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'MAWB NO./BL NO.','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,$obj->mawbl_bl,'R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'DESTINATION','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,lineBreak($obj->destination,40),0,0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'MANIFEST NO.','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,$obj->manifest_number,'R',1);

					$substr = lineBreak($obj->destination,40);
					$checkremstr = trim(str_replace($substr, '', $obj->destination));


					while(trim($checkremstr)!=''){
						$substr = lineBreak($checkremstr,40);
						$checkremstr = trim(str_replace($substr, '', $checkremstr));

						
						$pdf->SetFont('Arial','B',$headerfont);
						$pdf->cell(30,$hlineheight,'','L',0);
						$pdf->SetFont('Arial','',$headerfont);
						$pdf->cell(70,$hlineheight,$substr,0,0);
						$pdf->SetFont('Arial','B',$headerfont);
						$pdf->cell(30,$hlineheight,'','L',0);
						$pdf->SetFont('Arial','',$headerfont);
						$pdf->cell(70,$hlineheight,'','R',1);
						$rowdeduct++;
						
					}

					

					


					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'MODE','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,$obj->modeoftransport,0,0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'ETD','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,dateFormat($obj->etd,'m/d/Y h:i:s A'),'R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'AGENT','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,$obj->agent,0,0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'ETA','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,dateFormat($obj->eta,'m/d/Y h:i:s A'),'R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'AGENT CONTACT','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,$obj->agentcontact,0,0);
					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,'','R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'AGENT ADDRESS','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,$agentaddress1,0,0);
					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,'','R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,$agentaddress2,0,0);
					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,'','R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,$agentaddress3,0,0);
					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,'','R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,$agentaddress4,0,0);
					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,'','R',1);
					

					

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'REMARKS','TL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(170,$hlineheight,$obj->remarks,'TR',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(170,$hlineheight,'','R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'','BL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(170,$hlineheight,'','BR',1);


					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'CARRIER','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,$obj->carrierdesc,0,0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'DRIVER NAME','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,$obj->driver_name,'R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'VEHICLE TYPE','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,$obj->vehicletype,0,0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'CONTACT NUMBER','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,$obj->contact_number,'R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'PLATE NUMBER','BL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,$obj->plate_number,'B',0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'','BL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,'','BR',1);





				}
				
			}
			else{
				echo "INVALID";
			}


			$rs = query("
				   select txn_manifest_waybill.id,
				           txn_manifest_waybill.manifest_number,
				           txn_manifest_waybill.waybill_number,
				           txn_manifest_waybill.created_date,
				           txn_manifest_waybill.created_by,
				           txn_waybill.document_date,
				           txn_waybill.shipper_account_name,
				           txn_waybill.consignee_account_name,
				           txn_waybill.package_number_of_packages,
				           txn_waybill.package_actual_weight,
				           txn_waybill.package_cbm,
				           txn_waybill.total_amount,
						   date_format(txn_waybill.pickup_date,'%m/%d/%Y') as pickupdate,
                           txn_waybill.invoice_number,
                           txn_waybill.shipment_description,
						   txn_waybill.booking_number,
                           txn_waybill.mawbl_bl,
				           txn_waybill.amount_for_collection,
				           txn_waybill.reference,
				           origintbl.description as origin,
				           destinationtbl.description as destination,
				           mode_of_transport.description as modeoftransport,
				           ifnull(numofpckgtbl.numofpackage,0) as numofpackage,
				           ifnull(totalpckgtbl.totalpackage,0) as totalpackage,
				           dimensiontbl.cbm as totalcbm,
				           dimensiontbl.volweight as totalvolweight
				    from txn_manifest_waybill
				    left join txn_waybill on txn_waybill.waybill_number=txn_manifest_waybill.waybill_number
					left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id
				    left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id
				    left join mode_of_transport on mode_of_transport.id=txn_waybill.package_mode_of_transport

				    left join (		
				    			 select txn_manifest_waybill_package_code.manifest_number,
				    			 		txn_manifest_waybill_package_code.waybill_number,
				    			 		count(package_code) as numofpackage
				    			 from txn_manifest_waybill_package_code
				    			 where txn_manifest_waybill_package_code.manifest_number='$txnnumber'
				    			 group by txn_manifest_waybill_package_code.waybill_number

				    		   ) as numofpckgtbl
				    on numofpckgtbl.manifest_number=txn_manifest_waybill.manifest_number and
				       numofpckgtbl.waybill_number=txn_manifest_waybill.waybill_number
				    left join (
				    			 select waybill_number,
				    			        count(code) as totalpackage 
				    			 from txn_waybill_package_code 
				    			 group by waybill_number

				    		   ) as totalpckgtbl
				    on totalpckgtbl.waybill_number=txn_manifest_waybill.waybill_number
				    left join (
							    select sum(txn_waybill_package_dimension.volumetric_weight) as volweight,
						               sum(txn_waybill_package_dimension.cbm) as cbm,
						               txn_manifest_waybill.waybill_number
							    from txn_manifest_waybill
							    left join txn_waybill_package_dimension 
							    on txn_waybill_package_dimension.waybill_number=txn_manifest_waybill.waybill_number 
							    where txn_manifest_waybill.manifest_number='$txnnumber'
							    group by txn_manifest_waybill.waybill_number
							  ) as dimensiontbl
			        on dimensiontbl.waybill_number=txn_manifest_waybill.waybill_number
				    where txn_manifest_waybill.manifest_number='$txnnumber'
				");

			$rscount = getNumRows($rs);
			$totalrowsperpage = 35;

			//$totalrowsperpage = $totalrowsperpage-$rowdeduct;
			$page = 1;
			$line = 1;	
			$ttlcbm = 0;
			$ttlvw = 0;
			$ttlactualwght = 0;
			$ttlpckgs = 0;
			$maxlinesperpage = 52;
			$consumedlines = 0;
			$consumedlines = $consumedlines+24+$rowdeduct;

			$ttlamountforcollection = 0;

			$headerlinespacing = 3;

			$x = 1;
			$n = 1;

		    $pdf->SetFillColor($fillcolor[0],$fillcolor[1],$fillcolor[2]);
		    $pdf->SetFont('Arial','B',$detailfont);

		    $pdf->ln();
		    $pdf->Cell(201,5,'WAYBILL TRANSACTIONS','LTBR',1,'L',true);

		    /*$pdf->Cell(20,1,'','LTR',0,'L',true);
			$pdf->Cell(15,1,'','LTR',0,'L',true);
			$pdf->Cell(45,1,'','LTR',0,'L',true);
			$pdf->Cell(45,1,'','LTR',0,'L',true);
			$pdf->Cell(20,1,'','LTR',0,'L',true);
			$pdf->Cell(15,1,'','LTR',0,'L',true);
			$pdf->Cell(15,1,'','LTR',0,'L',true);
			$pdf->Cell(25,1,'','LTR',1,'L',true);

			$pdf->Cell(15,$headerlinespacing,'NO.','LR',0,'L',true);
			$pdf->Cell(15,$headerlinespacing,'PICKUP DATE','LR',0,'L',true);
			$pdf->Cell(20,$headerlinespacing,'BRF#','LR',0,'L',true);
			$pdf->Cell(20,$headerlinespacing,'BOL#','LR',0,'L',true);
			$pdf->Cell(30,$headerlinespacing,'SHIPPER','LR',0,'L',true);
			$pdf->Cell(30,$headerlinespacing,'CONSIGNEE','LR',0,'L',true);
			$pdf->Cell(20,$headerlinespacing,'DESCRIPTION','LR',0,'L',true);
			$pdf->Cell(20,$headerlinespacing,'NO. OF PKGS','LR',0,'L',true);
			$pdf->Cell(15,$headerlinespacing,'CBM','LR',0,'L',true);
			//$pdf->Cell(15,$headerlinespacing,'VOL. WGT','LBT',0,'L',true);
			$pdf->Cell(15,$headerlinespacing,'ACTUAL','LR',0,'L',true);
			$pdf->Cell(20,$headerlinespacing,'TRACKING','LR',1,'L',true);

			$pdf->Cell(20,$headerlinespacing,'','LR',0,'L',true);
			$pdf->Cell(15,$headerlinespacing,'','LR',0,'L',true);
			$pdf->Cell(45,$headerlinespacing,'','LR',0,'L',true);
			$pdf->Cell(45,$headerlinespacing,'','LR',0,'L',true);
			$pdf->Cell(20,$headerlinespacing,'','LR',0,'L',true);
			$pdf->Cell(15,$headerlinespacing,'','LR',0,'L',true);
			//$pdf->Cell(15,$headerlinespacing,'VOL. WGT','LBT',0,'L',true);
			$pdf->Cell(15,$headerlinespacing,'WEIGHT','LR',0,'L',true);
			$pdf->Cell(25,$headerlinespacing,'NUMBER','LR',1,'L',true);

			$pdf->Cell(20,1,'','LBR',0,'L',true);
			$pdf->Cell(15,1,'','LBR',0,'L',true);
			$pdf->Cell(45,1,'','LBR',0,'L',true);
			$pdf->Cell(45,1,'','LBR',0,'L',true);
			$pdf->Cell(20,1,'','LBR',0,'L',true);
			$pdf->Cell(15,1,'','LBR',0,'L',true);
			$pdf->Cell(15,1,'','LBR',0,'L',true);
			$pdf->Cell(25,1,'','LBR',1,'L',true);*/

			$detailwidths = array(8,15,20,20,29,29,29,9,12,12,18);

			function printWaybillHeader($pdf,$widths){
				$pdf->SetFont('Arial','B',6);
				$pdf->SetWidths($widths);
				$pdf->Row(
				            array(
				            			'#',
				                    	'PICKUP DATE',
				                        'BRF#',
				                    	'BOL#',
				                    	'SHIPPER',
				                    	'CONSIGNEE',
				                    	'DESCRIPTION',
				                    	'NO. OF PKGS',
				                    	'CBM',
				                    	'ACTUAL WGT',
				                    	'TRACKING NO.'
				            )
				);

			}

			printWaybillHeader($pdf,$detailwidths);

			$pdf->SetFont('Arial','',$detailfont);
			$line = 1;
			while ($obj = mysql_fetch_object($rs)) {
					$wbnumber = $obj->waybill_number;
					$brfnumber = $obj->booking_number;
					$pickupdate = $obj->pickupdate;
					$description = utf8_encode($obj->shipment_description);
					$docdate = dateFormat($obj->document_date,'m/d/Y');
					$origin = $obj->origin;
					$destination = $obj->destination;
					$modeoftransport = $obj->modeoftransport;
					$shipper = $obj->shipper_account_name;
					$consignee = $obj->consignee_account_name;
					$pckgs = $obj->package_number_of_packages;
					$actualweight = $obj->package_actual_weight;
					$vwcbm = $obj->package_cbm;
					$chargeamount = $obj->total_amount;
					$actualweight = convertWithDecimal($obj->package_actual_weight,5);
					$totalcbm = convertWithDecimal($obj->totalcbm,5);
					$totalvw = convertWithDecimal($obj->totalvolweight,5);

					



					$amountforcollection = $obj->amount_for_collection;
					$amountforcollection = $obj->reference;
					$ttlamountforcollection = '';//$ttlamountforcollection + $amountforcollection;

					$ttlcbm = $ttlcbm + $obj->totalcbm;
					$ttlvw = $ttlvw + $obj->totalvolweight;
					$ttlactualwght = $ttlactualwght+$obj->package_actual_weight;
					$ttlpckgs = $ttlpckgs + $pckgs;

					$ldpmanifest = $obj->manifest_number;

					//if($line>($page*$totalrowsperpage)||($page>1&&$consumedlines>$maxlinesperpage)){
					if($consumedlines>$maxlinesperpage){

						//$totalrowsperpage = 50;
						$consumedlines = 0;
						$page++;
						$pdf->AddPage();
						$pdf->AliasNbPages();
						$pdf->ln();
						

					}	

					$even = 0;


					$pdf->Row(
						array(
									$line,
									$pickupdate,
									$brfnumber,
									$wbnumber,
									$shipper,
									$consignee,
									$description,
									$pckgs,
									$totalcbm,
									$actualweight,
									$amountforcollection
						)
					);
					$line++;

					/*$lineheight = 4;

					$flag = 1;
					$flag1 = 1;
					$flag2 = 1;
					while($flag==1){
						$substr = lineBreak($shipper,25);
						$checkremstr = trim(str_replace($substr, '', $shipper));

						$substr1 = lineBreak($consignee,25);
						$checkremstr1 = trim(str_replace($substr1, '', $consignee));

						

						if($checkremstr==''&&$checkremstr1==''){
							if($consumedlines==0){
								$cellborder = 'LBT';
							}
							else{
								$cellborder = 'LB';
							}
							$pdf->Cell(20,$lineheight,$wbnumber,$cellborder,0,'L',$even);
							$pdf->Cell(15,$lineheight,$docdate,$cellborder,0,'C',$even);
							$pdf->Cell(45,$lineheight,$shipper,$cellborder,0,'L',$even);
							$pdf->Cell(45,$lineheight,$consignee,$cellborder,0,'L',$even);
							$pdf->Cell(20,$lineheight,$pckgs,$cellborder,0,'R',$even);
							$pdf->Cell(15,$lineheight,$totalcbm,$cellborder,0,'R',$even);
							//$pdf->Cell(15,$lineheight,$totalvw,$cellborder,0,'R',$even);
							$pdf->Cell(15,$lineheight,$actualweight,$cellborder,0,'R',$even);
							$pdf->Cell(25,$lineheight,$amountforcollection,$cellborder.'R',1,'R',$even);

							$flag = 0;
							$n++;
							$consumedlines++;
						}
						else{

							if($consumedlines==0){
								$cellborder = 'LT';
							}
							else{
								$cellborder = 'L';
							}
							$pdf->Cell(20,$lineheight,$wbnumber,$cellborder,0,'L',$even);
							$pdf->Cell(15,$lineheight,$docdate,$cellborder,0,'C',$even);
							$pdf->Cell(45,$lineheight,$substr,$cellborder,0,'L',$even);
							$pdf->Cell(45,$lineheight,$substr1,$cellborder,0,'L',$even);
							$pdf->Cell(20,$lineheight,$pckgs,$cellborder,0,'R',$even);
							$pdf->Cell(15,$lineheight,$totalcbm,$cellborder,0,'R',$even);
							//$pdf->Cell(15,$lineheight,$totalvw,$cellborder,0,'R',$even);
							$pdf->Cell(15,$lineheight,$actualweight,$cellborder,0,'R',$even);
							$pdf->Cell(25,$lineheight,$amountforcollection,$cellborder.'R',1,'R',$even);

							$n++;
							$consumedlines++;

							while($flag1==1||$flag2==1){
								$substr = lineBreak($checkremstr, 25);
								$checkremstr = trim(str_replace($substr, '', $checkremstr));

								$substr1 = lineBreak($checkremstr1, 25);
								$checkremstr1 = trim(str_replace($substr1, '', $checkremstr1));

								if($consumedlines>$maxlinesperpage){

									
									$consumedlines = 0;
									$page++;
									$pdf->AddPage();
									$pdf->AliasNbPages();
									$pdf->ln();

								}	

								if($checkremstr==''&&$checkremstr1==''){

									$pdf->Cell(20,$lineheight,'','LB',0,'L',$even);
									$pdf->Cell(15,$lineheight,'','LB',0,'C',$even);
									$pdf->Cell(45,$lineheight,$substr,'LB',0,'L',$even);
									$pdf->Cell(45,$lineheight,$substr1,'LB',0,'L',$even);
									$pdf->Cell(20,$lineheight,'','LB',0,'R',$even);
									$pdf->Cell(15,$lineheight,'','LB',0,'R',$even);
									$pdf->Cell(15,$lineheight,'','LB',0,'R',$even);
									$pdf->Cell(25,$lineheight,'','LBR',1,'R',$even);

									
									$flag1=0;
									$flag=0;
									$flag2=0;
									$consumedlines++;

								}
								else{
									$pdf->Cell(20,$lineheight,'','L',0,'L',$even);
									$pdf->Cell(15,$lineheight,'','L',0,'C',$even);
									$pdf->Cell(45,$lineheight,$substr,'L',0,'L',$even);
									$pdf->Cell(45,$lineheight,$substr1,'L',0,'L',$even);
									$pdf->Cell(20,$lineheight,'','L',0,'R',$even);
									$pdf->Cell(15,$lineheight,'','L',0,'R',$even);
									$pdf->Cell(15,$lineheight,'','L',0,'R',$even);
									$pdf->Cell(25,$lineheight,'','LR',1,'R',$even);
									$consumedlines++;
									
								}
								$n++;
								$totalrowsperpage--;
								
								
							}
						}

					}

					$line++;*/
			}

			$ttlpckgs = convertWithDecimal($ttlpckgs,5);
			$ttlcbm = convertWithDecimal($ttlcbm,5);
		    $ttlvw = convertWithDecimal($ttlvw,5);
		    $ttlactualwght = convertWithDecimal($ttlactualwght,5);
		    //$ttlamountforcollection = convertWithDecimal($ttlamountforcollection,5);

		    if($consumedlines>$maxlinesperpage){


		    	$consumedlines = 0;
		    	$page++;
		    	$pdf->AddPage();
		    	$pdf->AliasNbPages();
		    	$pdf->ln();

		    }
			$detailwidths = array(8,15,20,20,29,29,29,9,12,12,18);

		    $pdf->SetFont('Arial','',$detailfont);
		    $pdf->Cell(8,5,($line-1),'BL',0,'C',0);
		    $pdf->SetFont('Arial','B',$detailfont);
			$pdf->Cell(142,5,'TOTAL','BL',0,'C',0);
			$pdf->SetFont('Arial','',$detailfont);
			$pdf->Cell(9,5,$ttlpckgs,'BL',0,'R',0);
			$pdf->Cell(12,5,$ttlcbm,'BL',0,'R',0);
			//$pdf->Cell(15,5,$ttlvw,'BL',0,'R',0);
			$pdf->Cell(12,5,$ttlactualwght,'BL',0,'R',0);
			$pdf->Cell(18,5,$ttlamountforcollection,'BLR',1,'R',0);

			$consumedlines++;

			


			$rs = query("
				     select txn_manifest_waybill_package_code.id,
				           txn_manifest_waybill_package_code.manifest_number,
				           txn_manifest_waybill_package_code.waybill_number,
				           txn_manifest_waybill_package_code.package_code,
				           txn_manifest_waybill_package_code.created_date,
				           txn_manifest_waybill_package_code.created_by
				    from txn_manifest_waybill_package_code
				    where txn_manifest_waybill_package_code.manifest_number='$txnnumber'
				    order by waybill_number, txn_manifest_waybill_package_code.package_code asc
				");

			$rscount = getNumRows($rs);
			$totalrowsperpage = 20;
			$page = 1;
			$line = 1;	

	    $pdf->SetFillColor($fillcolor[0],$fillcolor[1],$fillcolor[2]);
	    $pdf->SetFont('Arial','B',$detailfont);

	    if(($maxlinesperpage-$consumedlines)>=3){
					$pdf->ln();
				    $pdf->Cell(200,5,'WAYBILL PACKAGES','LTR',1,'L',true);
				    $pdf->Cell(20,5,'LINE','LBT',0,'L',true);
					$pdf->Cell(45,5,'WAYBILL NO.','LBT',0,'L',true);
					$pdf->Cell(135,5,'PACKAGE CODE','LBTR',1,'L',true);
					$pdf->SetFont('Arial','',$detailfont);

					$consumedlines = $consumedlines+3;
		}

		$currentpkgscode = '';
		if(getNumRows($rs)>0){
			while ($obj = mysql_fetch_object($rs)) {
				$id = $obj->id;
				$wbnumber = strtoupper($obj->waybill_number);
				$code = $obj->package_code;

				if($consumedlines>$maxlinesperpage){
					$consumedlines = 0;
					$page++;
					$pdf->AddPage();
					$pdf->AliasNbPages();
					$pdf->ln();
					$pdf->ln();
					$pdf->ln();
					$pdf->ln();
					//line item header
					$pdf->SetFillColor($fillcolor[0],$fillcolor[1],$fillcolor[2]);
					$pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial','B',$detailfont);
					$pdf->Cell(20,5,'LINE','LBT',0,'L',true);
					$pdf->Cell(45,5,'WAYBILL NO.','LBT',0,'L',true);
					$pdf->Cell(135,5,'PACKAGE CODE','LBTR',1,'L',true);
					$pdf->SetFillColor($rowitem[0],$rowitem[1],$rowitem[2]);
					$pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial','',$detailfont);

				}
				$even = 0;

				if($currentpkgscode!=$wbnumber){
					$currentpkgscode = $wbnumber;
					$cellborder = 'LT';
				}
				else{
					$wbnumber = '';
					$cellborder = 'L';
				}

				if(getNumRows($rs)==$line){
					$cellborder.='B';
				}
				

				$pdf->Cell(20,5,$line,$cellborder,0,'L',$even);
				$pdf->Cell(45,5,$wbnumber,$cellborder,0,'L',$even);
				$pdf->Cell(135,5,$code,$cellborder.'R',1,'L',$even);
				

				$line++;
			}


		}
		else{
			 $pdf->Cell(200,5,'No package codes','LBTR',1,'C',true);
		}
	

	




$pdf->Output();
	



?>