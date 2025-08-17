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
$pdf->Cell(75, 3, $pdf->Image($imagelogo, $pdf->GetX(), $pdf->GetY(), 35),0,0);
$rowdeduct = 0;


//$imagelogo = "../../../resources/barcode-generator/generate-barcode.php?text=kaye";



			$pdf->SetFont('Arial','B',14);
			$pdf->cell(50,5,'LOAD PLAN',0,0);
			$pdf->SetFont('Arial','B',14);
			$pdf->SetTextColor(99, 158, 188);
			$pdf->cell(70,5,'# '.$txnnumber,0,1,'R');


		

			$pdf->SetTextColor(0, 0, 0);


			$pdf->cell(200,5,'','B',1);

			$fillcolor = getColor('primary_color');//array(214, 69, 65);
			$rowitem = getColor('secondary_color');


			
		

			$headerfont = 8;
			$detailfont = 7;
			$hlineheight = 5;
			$pdf->SetFont('Arial','',$detailfont);

			$fillcolor = getColor('primary_color');//array(214, 69, 65);
			$rowitem = getColor('secondary_color');


	      	$rs = query("select txn_load_plan.id,
				                txn_load_plan.load_plan_number,
				                txn_load_plan.status,
				                txn_load_plan.manifest_number,
				                txn_load_plan.location_id,
				                txn_load_plan.carrier_id,
				                txn_load_plan.origin_id,
				                txn_load_plan.destination_id,
				                txn_load_plan.mode_of_transport_id,
				                txn_load_plan.agent_id,
				                txn_load_plan.mawbl_bl,
				                txn_load_plan.remarks,
				                txn_load_plan.document_date,
				                txn_load_plan.eta,
				                txn_load_plan.etd,
				                txn_load_plan.created_date,
				                txn_load_plan.updated_date,
				                txn_load_plan.created_by,
				                txn_load_plan.updated_by,
				                txn_load_plan.last_status_update_remarks,
				                concat(cuser.first_name,' ',cuser.last_name) as createdby,
				                concat(uuser.first_name,' ',uuser.last_name) as updatedby,
				                location.code as loccode,
				                location.description as locdesc,
				                carrier.description as carrierdesc,
				                carrier.code as carriercode,
				                origintbl.description as origin,
				                group_concat(destinationtbl.description separator ', ') as destination,
				                mode_of_transport.description as modeoftransport,
				                agent.company_name as agent
				         from txn_load_plan
				         left join txn_load_plan_destination on txn_load_plan_destination.load_plan_number=txn_load_plan.load_plan_number
				         left join user as cuser on cuser.id=txn_load_plan.created_by
				         left join user as uuser on uuser.id=txn_load_plan.updated_by
				         left join location on location.id=txn_load_plan.location_id
				         left join carrier on carrier.id=txn_load_plan.carrier_id
 						 left join origin_destination_port as origintbl on origintbl.id=txn_load_plan.origin_id 
				         left join origin_destination_port as destinationtbl on destinationtbl.id=txn_load_plan_destination.origin_destination_port_id 
				         left join mode_of_transport on mode_of_transport.id=txn_load_plan.mode_of_transport_id
				         left join agent on agent.id=txn_load_plan.agent_id
				         where txn_load_plan.load_plan_number = '$txnnumber'
				         group by txn_load_plan.load_plan_number");

			if(getNumRows($rs)==1){
				while($obj = fetch($rs)){

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'LOCATION','BL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(170,$hlineheight,'['.$obj->loccode.'] '.$obj->locdesc,'BR',1);

					


					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'ORIGIN','BL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,$obj->origin,'B',0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'MAWB NO./BL NO.','BL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,$obj->mawbl_bl,'BR',1);


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
					$pdf->cell(30,$hlineheight,'MODE','BL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,$obj->modeoftransport,'B',0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'ETD','BL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,dateFormat($obj->etd,'m/d/Y h:i:s A'),'BR',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'CARRIER','BL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,$obj->carrierdesc,'B',0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'ETA','BL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,dateFormat($obj->eta,'m/d/Y h:i:s A'),'BR',1);


					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'AGENT','LB',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,$obj->agent,'BR',0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'DOCUMENT DATE','BL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,dateFormat($obj->document_date,'m/d/Y'),'BR',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'REMARKS','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(170,$hlineheight,$obj->remarks,'R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(170,$hlineheight,'','R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'','BL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(170,$hlineheight,'','BR',1);





				}
				
			}
			else{
				echo "INVALID";
			}


			$rs = query("
				    select txn_load_plan_waybill.id,
				           txn_load_plan_waybill.load_plan_number,
				           txn_load_plan_waybill.waybill_number,
				           txn_load_plan_waybill.created_date,
				           txn_load_plan_waybill.created_by,
				           txn_waybill.document_date,
				           txn_waybill.shipper_account_name,
				           txn_waybill.consignee_account_name,
				           txn_waybill.package_number_of_packages,
				           txn_waybill.package_actual_weight,
				           txn_waybill.package_cbm,
				           txn_waybill.total_amount,
				           origintbl.description as origin,
				           destinationtbl.description as destination,
				           mode_of_transport.description as modeoftransport,
				           txn_load_plan.manifest_number,
				           ifnull(txn_manifest_waybill.waybill_number,'NOTINMANIFEST') as manifestremarks,
				           dimensiontbl.cbm as totalcbm,
				           dimensiontbl.volweight as totalvolweight
				    from txn_load_plan_waybill
				    left join txn_load_plan on txn_load_plan.load_plan_number=txn_load_plan_waybill.load_plan_number
				    left join txn_waybill on txn_waybill.waybill_number=txn_load_plan_waybill.waybill_number
					left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id
				    left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id
				    left join mode_of_transport on mode_of_transport.id=txn_waybill.package_mode_of_transport
				    left join txn_manifest_waybill on txn_manifest_waybill.manifest_number=txn_load_plan.manifest_number and
				                                      txn_manifest_waybill.waybill_number=txn_load_plan_waybill.waybill_number
				    left join (
							    select sum(txn_waybill_package_dimension.volumetric_weight) as volweight,
						               sum(txn_waybill_package_dimension.cbm) as cbm,
						               txn_load_plan_waybill.waybill_number
							    from txn_load_plan_waybill
							    left join txn_waybill_package_dimension 
							    on txn_waybill_package_dimension.waybill_number=txn_load_plan_waybill.waybill_number 
							    where txn_load_plan_waybill.load_plan_number='$txnnumber'
							    group by txn_load_plan_waybill.waybill_number
							  ) as dimensiontbl
			        on dimensiontbl.waybill_number=txn_load_plan_waybill.waybill_number
				    where txn_load_plan_waybill.load_plan_number='$txnnumber'
				");

			$rscount = getNumRows($rs);
			
			$page = 1;
			$line = 1;	
			$ttlcbm = 0;
			$ttlvw = 0;
			$ttlactualwght = 0;
			$ttlpckgs = 0;

			$totalrowsperpage = 35;
			$maxlinesperpage = 59;
			$consumedlines = 0;
			$consumedlines = $consumedlines+20+$rowdeduct;

		    $pdf->SetFillColor($fillcolor[0],$fillcolor[1],$fillcolor[2]);
		    $pdf->SetFont('Arial','B',$detailfont);

		    $pdf->ln();
		    $pdf->Cell(200,5,'WAYBILL TRANSACTIONS','LTR',1,'C',true);
			$pdf->Cell(20,5,'WAYBILL NO.','LBT',0,'L',true);
			$pdf->Cell(15,5,'DOC DATE','LBT',0,'L',true);
			$pdf->Cell(45,5,'SHIPPER','LBT',0,'L',true);
			$pdf->Cell(45,5,'CONSIGNEE','LBT',0,'L',true);
			$pdf->Cell(20,5,'NO. OF PKGS','LBT',0,'L',true);
			$pdf->Cell(20,5,'CBM','LBT',0,'L',true);
			$pdf->Cell(15,5,'VOL. WGT','LBT',0,'L',true);
			$pdf->Cell(20,5,'ACTUAL WGT','LBTR',1,'L',true);
			$pdf->SetFont('Arial','',$detailfont);

			if(getNumRows($rs)>0){
				while ($obj = mysql_fetch_object($rs)) {
						$ldpnumber = $obj->load_plan_number;
						$wbnumber = $obj->waybill_number;
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

						$ttlcbm = $ttlcbm + $obj->totalcbm;
						$ttlvw = $ttlvw + $obj->totalvolweight;
						$ttlactualwght = $ttlactualwght+$obj->package_actual_weight;

						$ldpmanifest = $obj->manifest_number;
						$manifestremarks = $obj->manifestremarks;
						$ttlpckgs = $ttlpckgs + $pckgs;

						if($consumedlines>$maxlinesperpage){

							
							$consumedlines = 0;

							$page++;
							$pdf->AddPage();
							$pdf->AliasNbPages();
							$pdf->ln();
							//line item header
							/*$pdf->SetFillColor($fillcolor[0],$fillcolor[1],$fillcolor[2]);
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetFont('Arial','B',$detailfont);
							$pdf->Cell(200,5,'WAYBILL TRANSACTIONS','LTR',1,'C',true);
							$pdf->Cell(20,5,'WAYBILL NO.','LBT',0,'L',true);
							$pdf->Cell(15,5,'DOC DATE','LBT',0,'L',true);
							$pdf->Cell(45,5,'SHIPPER','LBT',0,'L',true);
							$pdf->Cell(45,5,'CONSIGNEE','LBT',0,'L',true);
							$pdf->Cell(20,5,'NO. OF PKGS','LBT',0,'L',true);
							$pdf->Cell(20,5,'CBM','LBT',0,'L',true);
							$pdf->Cell(15,5,'VOL. WGT','LBT',0,'L',true);
							$pdf->Cell(20,5,'ACTUAL WGT','LBTR',1,'L',true);
							$pdf->SetFillColor($rowitem[0],$rowitem[1],$rowitem[2]);
							$pdf->SetTextColor(0, 0, 0);
							$pdf->SetFont('Arial','',$detailfont);*/

						}	

						$even = 0;

						/*if((($totalrowsperpage*$page)!=$line)&&$line!=$rscount){
							$pdf->Cell(25,4,$wbnumber,'L',0,'L',$even);
							$pdf->Cell(20,4,$docdate,'L',0,'C',$even);
							$pdf->Cell(45,4,$shipper,'L',0,'L',$even);
							$pdf->Cell(50,4,$consignee,'L',0,'L',$even);
							$pdf->Cell(20,4,$totalcbm,'L',0,'R',$even);
							$pdf->Cell(20,4,$totalvw,'L',0,'R',$even);
							$pdf->Cell(20,4,$actualweight,'LR',1,'R',$even);
						}
						else{
							$pdf->Cell(25,4,$wbnumber,'BL',0,'L',$even);
							$pdf->Cell(20,4,$docdate,'BL',0,'C',$even);
							$pdf->Cell(45,4,$shipper,'BL',0,'L',$even);
							$pdf->Cell(50,4,$consignee,'BL',0,'L',$even);
							$pdf->Cell(20,4,$totalcbm,'BL',0,'R',$even);
							$pdf->Cell(20,4,$totalvw,'BL',0,'R',$even);
							$pdf->Cell(20,4,$actualweight,'BLR',1,'R',$even);


							
						}

						$line++;*/

						$lineheight = 4;

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
								$pdf->Cell(20,$lineheight,$totalcbm,$cellborder,0,'R',$even);
								$pdf->Cell(15,$lineheight,$totalvw,$cellborder,0,'R',$even);
								$pdf->Cell(20,$lineheight,$actualweight,$cellborder.'R',1,'R',$even);

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
								$pdf->Cell(20,$lineheight,$totalcbm,$cellborder,0,'R',$even);
								$pdf->Cell(15,$lineheight,$totalvw,$cellborder,0,'R',$even);
								$pdf->Cell(20,$lineheight,$actualweight,$cellborder.'R',1,'R',$even);

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
										$pdf->Cell(20,$lineheight,'','LB',0,'R',$even);
										$pdf->Cell(15,$lineheight,'','LB',0,'R',$even);
										$pdf->Cell(20,$lineheight,'','LBR',1,'R',$even);

										
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
										$pdf->Cell(20,$lineheight,'','L',0,'R',$even);
										$pdf->Cell(15,$lineheight,'','L',0,'R',$even);
										$pdf->Cell(20,$lineheight,'','LR',1,'R',$even);
										$consumedlines++;
										
									}
									$n++;
									$totalrowsperpage--;
									
									
								}
							}

						}

						$line++;
				}

				$ttlcbm = convertWithDecimal($ttlcbm,5);
			    $ttlvw = convertWithDecimal($ttlvw,5);
			    $ttlactualwght = convertWithDecimal($ttlactualwght,5);

			    if($consumedlines>$maxlinesperpage){


			    	$consumedlines = 0;
			    	$page++;
			    	$pdf->AddPage();
			    	$pdf->AliasNbPages();
			    	$pdf->ln();

			    }


			    $pdf->SetFont('Arial','',$detailfont);
			    $pdf->Cell(20,5,($line-1),'BL',0,'C',0);
			    $pdf->SetFont('Arial','B',$detailfont);
				$pdf->Cell(105,5,'TOTAL','BL',0,'C',0);
				$pdf->SetFont('Arial','',$detailfont);
				$pdf->Cell(20,5,$ttlpckgs,'BL',0,'R',0);
				$pdf->Cell(20,5,$ttlcbm,'BL',0,'R',0);
				$pdf->Cell(15,5,$ttlvw,'BL',0,'R',0);
				$pdf->Cell(20,5,$ttlactualwght,'BLR',1,'R',0);

				$consumedlines++;
			}
			else{
				$pdf->Cell(200,5,'No results found','BLR',0,'C',1);
			}
	

	




$pdf->Output();
	



?>