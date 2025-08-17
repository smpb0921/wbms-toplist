<?php

include("../../../config/connection.php");
include("../../../config/checklogin.php");
include("../../../config/functions.php");
require('../../../resources/htmltopdf/pdf-table.php');
include("../../../classes/company-information.class.php");

$costingid = isset($_GET['txnnumber'])?escapeString($_GET['txnnumber']):'';

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
		$this->Cell(210,3,'',0,0,'L');

	    $this->SetY(-8);
	    $this->SetFont('Arial','B',7);
	    $this->Cell(210,3,'Page '.$this->PageNo().' of {nb}',0,0,'C');
	    //PAGE '.$this->PageNo().' of {nb}
	    
	}
}

$pdf = new PDF('P','mm','LETTER');
$pdf->AliasNbPages();
$pdf->AddPage();
$now = date('m/d/Y h:i:s A');

$fillcolor = getColor('primary_color');//array(214, 69, 65);
$rowitem = getColor('secondary_color');
$headerfont = 8;
$detailfont = 7;
$hlineheight = 5;


$rs = query("
                              select costing.id,
                                     costing.chart_of_accounts_id, 
                                     costing.amount,
                                     costing.reference,
                                     costing.prf_number,
                                     costing.payee_id,
                                     payee.payee_name,
                                     payee.tin,
                                     costing.payee_address,
                                     costing.is_vatable,
                                     case 
                                          when costing.is_vatable=1 then 'YES'
                                          else 'NO'
                                     end as vatflag,
                                     costing.vatable_amount,
                                     costing.vat_amount,
                                     date_format(costing.date,'%m/%d/%Y') as date,
                                     date_format(costing.created_date,'%m/%d/%Y') as created_date,
                                     date_format(costing.updated_date,'%m/%d/%Y') as updated_date,
                                     concat(cuser.first_name,' ',cuser.last_name) as createdby,
                                     concat(uuser.first_name,' ',uuser.last_name) as updatedby,
                                     chart_of_accounts.description as chartofaccounts,
                                     ifnull(count(distinct costing_waybill.waybill_number),0) as waybillcount,
                                     expense_type.description as typeofaccount,
                                     chart_of_accounts.expense_type_id,
                                     chart_of_accounts.type as producttype,
                                     ifnull(sum(txn_waybill.package_actual_weight),0) as totalactualweight,
                                     ifnull(waybillpackages.volweight,0) as totalvolweight,
                                     case 
                                            when ifnull(sum(txn_waybill.package_actual_weight),0)>ifnull(waybillpackages.volweight,0) then ifnull(sum(txn_waybill.package_actual_weight),0)
                                            else ifnull(waybillpackages.volweight,0)
                                     end as actualvolweight,
                                     case 
                                            when ifnull(sum(txn_waybill.package_actual_weight),0)>ifnull(waybillpackages.volweight,0) then 'ACTUAL'
                                            else 'VOLUMETRIC'
                                     end as targetweight
                              from costing 
                              left join chart_of_accounts on chart_of_accounts.id=costing.chart_of_accounts_id
                              left join expense_type on expense_type.id=chart_of_accounts.expense_type_id
                              left join user as cuser on cuser.id=costing.created_by
                              left join user as uuser on uuser.id=costing.updated_by
                              left join costing_waybill on costing_waybill.costing_id=costing.id
                              left join txn_waybill on txn_waybill.waybill_number=costing_waybill.waybill_number
                              left join (
                                            select waybill_number,
                                                   ifnull(sum(volumetric_weight),0) as volweight,
                                                   ifnull(sum(actual_weight),0) as actualweight
                                            from txn_waybill_package_dimension
                                            where waybill_number in (select waybill_number from costing_waybill where costing_id='$costingid')
                                            group by txn_waybill_package_dimension.waybill_number
                                        ) as waybillpackages on waybillpackages.waybill_number=costing_waybill.waybill_number
                              left join payee on payee.id=costing.payee_id
                              where costing.id='$costingid'
                              group by costing.id


	         ");

           

 


$rowcount = getNumRows($rs);


if($rowcount==1){

			while($obj=fetch($rs)){
				$typeofaccount = utfEncode($obj->typeofaccount);
                $account = utfEncode($obj->chartofaccounts);
                $grossamount = utfEncode($obj->amount);
                $vatableamount = utfEncode($obj->vatable_amount);
                $vat = utfEncode($obj->vat_amount);
                $docdate = utfEncode($obj->date);
                $reference = utfEncode($obj->reference);
                $actualvolweight = utfEncode($obj->actualvolweight);
                $txnnumber = utfEncode($obj->prf_number);
                $payee = utfEncode($obj->payee_name);
                $address = utfEncode($obj->payee_address);
                $targetweight = $obj->targetweight;
			}





            
			$imagelogo = "../../../resources/printout-logo.png";
			$pdf->Image($imagelogo, 5, 5, 30);

			$pdf->SetAutoPageBreak(false);

			$pdf->SetY(10);
			$pdf->SetFont('Arial','B',14);
			$pdf->cell(210,5,'COSTING TRANSACTION',0,1,'C');


			$pdf->SetY(5);
			$pdf->SetFont('Arial','B',10);
			$pdf->SetTextColor(99, 158, 188);
			$pdf->cell(200,5,'# '.$txnnumber,0,1,'R');

		    $pdf->SetMargins(4,0);
            $pdf->SetTextColor(0, 0, 0);
			
            $fontsize = 8;
            $font = 'Arial';
            $labelcol = 30;
            $datacol = 75;
            $lineheight = 6;

            $pdf->SetY(25);
			$pdf->SetFont($font,'B',$fontsize);
			$pdf->cell($labelcol,$lineheight,'Type of Account: ',0,0,'R');
			$pdf->SetFont($font,'',$fontsize);
			$pdf->cell($datacol,$lineheight,$typeofaccount,'TBLR',1,'L');

			$pdf->SetFont($font,'B',$fontsize);
			$pdf->cell($labelcol,$lineheight,'Account: ',0,0,'R');
			$pdf->SetFont($font,'',$fontsize);
			$pdf->cell($datacol,$lineheight,$account,'TBLR',1,'L');

            $pdf->SetFont($font,'B',$fontsize);
			$pdf->cell($labelcol,$lineheight,'Payee: ',0,0,'R');
			$pdf->SetFont($font,'',$fontsize);
			$pdf->cell($datacol,$lineheight,$payee,'TBLR',1,'L');

            //$pdf->SetFont('Arial','B',9);
			//$pdf->cell($labelcol,$lineheight,'Address: ',0,0,'R');
			//$pdf->SetFont('Arial','',0);
			//$pdf->cell($datacol,$lineheight,$address,'TBLR',1,'L');

            $datacol = 40;
            $indent = 140;

            $pdf->SetY(25);
            $pdf->SetX($indent);

            $pdf->SetFont($font,'B',$fontsize);
			$pdf->cell($labelcol,$lineheight,'Date: ',0,0,'R');
			$pdf->SetFont($font,'',$fontsize);
			$pdf->cell($datacol,$lineheight,$docdate,'TBLR',1,'R');

            $pdf->SetX($indent);

            $pdf->SetFont($font,'B',$fontsize);
			$pdf->cell($labelcol,$lineheight,'Reference: ',0,0,'R');
			$pdf->SetFont($font,'',$fontsize);
			$pdf->cell($datacol,$lineheight,$reference,'TBLR',1,'R');

            $pdf->SetX($indent);

            $pdf->SetFont($font,'B',$fontsize);
			$pdf->cell($labelcol,$lineheight,'Amount: ',0,0,'R');
			$pdf->SetFont($font,'',$fontsize);
			$pdf->cell($datacol,$lineheight,convertWithDecimal($grossamount,2),'TBLR',1,'R');

            $pdf->SetX($indent);

            $pdf->SetFont($font,'B',$fontsize);
			$pdf->cell($labelcol,$lineheight,'Actual Weight/Vol Weight: ',0,0,'R');
			$pdf->SetFont($font,'',$fontsize);
			$pdf->cell($datacol,$lineheight,convertWithDecimal($actualvolweight,2),'TBLR',1,'R');
			

			$pdf->ln();
			

			$pdf->SetFont('Arial','B',7);
			$headerlinespacing = 5;

			$columnwidths=array(10,30,20,20,25,35,25,40);
			function printHeader($pdf,$headerlinespacing,$columnwidths){
				$pdf->SetFont('Arial','B',7);
			

				$pdf->Cell($columnwidths[0],$headerlinespacing,'LINE','TBLR',0,'C');
                $pdf->Cell($columnwidths[1],$headerlinespacing,'BOL NUMBER','TBLR',0,'C');
				$pdf->Cell($columnwidths[2],$headerlinespacing,'ACTUAL WGT','TBLR',0,'C');
				$pdf->Cell($columnwidths[3],$headerlinespacing,'VOL. WGT','TBLR',0,'C');
				$pdf->Cell($columnwidths[4],$headerlinespacing,'BOL AMOUNT','TBLR',0,'C');
				$pdf->Cell($columnwidths[5],$headerlinespacing,'DISTRIBUTED AMOUNT','TBLR',0,'C');
				$pdf->Cell($columnwidths[6],$headerlinespacing,'CREATED DATE','TBLR',0,'C');
				$pdf->Cell($columnwidths[7],$headerlinespacing,'CREATED BY','TBLR',1,'C');
			}

			printHeader($pdf,$headerlinespacing,$columnwidths);
			

			


			$rs = query("
								select 
										date_format(costing_waybill.created_date,'%m/%d/%Y') as created_date,
										concat(cuser.first_name,' ',cuser.last_name) as createdby,
										ifnull(txn_waybill.package_actual_weight,0) as actualweight,
										ifnull(waybillpackages.volweight,0) as volweight,
										costing_waybill.waybill_number,
										ifnull(txn_waybill.freight_cost,0) as freight_cost,
										ifnull(txn_waybill.agent_cost,0) as agent_cost,
										ifnull(txn_waybill.insurance_amount,0) as insurance_amount,
										ifnull(txn_waybill.total_amount,0) as total_amount,
										case 
												when '$targetweight'='ACTUAL' then round((($grossamount/$actualvolweight)*ifnull(txn_waybill.package_actual_weight,0)),2)
												else round((($grossamount/$actualvolweight)*ifnull(waybillpackages.volweight,0)),2)
										end as distributedamount
								from costing_waybill 
								left join txn_waybill on txn_waybill.waybill_number=costing_waybill.waybill_number
								left join (
											select waybill_number,
													ifnull(sum(volumetric_weight),0) as volweight,
													ifnull(sum(actual_weight),0) as actualweight
											from txn_waybill_package_dimension
											where waybill_number in (select waybill_number from costing_waybill where costing_id='$costingid')
											group by txn_waybill_package_dimension.waybill_number
										) as waybillpackages on waybillpackages.waybill_number=costing_waybill.waybill_number
								left join user as cuser on cuser.id=costing_waybill.created_by
								where costing_waybill.costing_id='$costingid'
								group by costing_waybill.waybill_number
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

					$createddate = utf8_encode($obj->created_date);
                    $createdby = utf8_encode($obj->createdby);
                    $bolnumber = utf8_encode($obj->waybill_number);
					$actualweight = utf8_encode($obj->actualweight);
                    $volweight = utf8_encode($obj->volweight);
                    $distributedamount = utf8_encode($obj->distributedamount);

					$totalamount = utf8_encode($obj->total_amount);
					$freightcost = utf8_encode($obj->freight_cost);
					$agentcost = utf8_encode($obj->agent_cost);
					$insuranceamount = utf8_encode($obj->insurance_amount);

					$totalexpenses = $freightcost+$agentcost+$insuranceamount=$distributedamount;
					$grossamount = $totalamount-$totalexpenses;

                    if($pageline>$totalrowsperpage||$rowcount>$maxlinecreated){
                    	$pageline = 1;
						$page++;	
						$pdf->AddPage();
						$pdf->AliasNbPages();
						$pdf->SetMargins(4,0);

						
                    }


                    $pdf->SetFont('Arial','',7);
                    if($pageline<=$totalrowsperpage&&$rowcount<=$maxlinecreated){

                    	$rowcreated =   $pdf->Row(
				                    				array(
				                    						$line,
				                    						$bolnumber,
															$actualweight,
				                    						$volweight,
															$totalamount,
				                    						$distributedamount,
				                    						$createddate,
				                    						$createdby
				                    					 )
				                    	);

                    	$rowcount = $rowcreated;

                    	
					}
					

					
					



					$line++;
					$pageline++;
					$linerow++;

					if($pageline>$totalrowsperpage||$rowcount>$maxlinecreated||$line>$rscount){
						




						
					}

					

				}

			

				




				


			}
			else{
				$pdf->Cell(210,5,'No results found','BLR',0,'C');
			}


			


	

}
else{
	$pdf->Cell(210,5,'Invalid Costing',0,1,'C');
}




$pdf->Output();
	



?>