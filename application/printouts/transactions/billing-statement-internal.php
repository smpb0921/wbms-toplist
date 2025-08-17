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
	    $this->Cell(260,3,'PAGE '.$this->PageNo().' of {nb}',0,0,'R');
	    
	}
}

$pdf = new PDF('L','mm','LETTER');
$pdf->AliasNbPages();
$pdf->AddPage();
$now = date('m/d/Y h:i:s A');

//$company = new company_information();
//$company->select();
//$cmp = $company->loadObjectlist();





$imgpath = '../../../barcode/';
$imagelogo = "../../../resources/printout-logo.png";
$pdf->Cell(110, 3, $pdf->Image($imagelogo, $pdf->GetX(), $pdf->GetY(), 35),0,0);


//$imagelogo = "../../../resources/barcode-generator/generate-barcode.php?text=kaye";



			$pdf->SetFont('Arial','B',14);
			$pdf->cell(80,8,'BATCH HEADER',0,0);
			$pdf->SetFont('Arial','B',14);
			$pdf->SetTextColor(34, 63, 142);
			$pdf->cell(70,8,'# '.$txnnumber,0,1,'R');


			$pdf->cell(150,2,'',0);

			$pdf->SetTextColor(0, 0, 0);


			$pdf->cell(195,2,'',0,1);
			$pdf->cell(260,2,'','B',1);

			$fillcolor = getColor('primary_color');//array(214, 69, 65);
			$rowitem = getColor('secondary_color');


			
		

			$headerfont = 8;
			$detailfont = 7;
			$hlineheight = 5;
			$pdf->SetFont('Arial','',$detailfont);

			$fillcolor = getColor('primary_color');//array(214, 69, 65);
			$rowitem = getColor('secondary_color');


	       $rs = query("select txn_billing.id,
				                txn_billing.billing_number,
				                txn_billing.bill_to_account_number,
				                txn_billing.bill_to_account_name,
				                txn_billing.bill_to_company_name,
				                txn_billing.billing_contact_person,
				                txn_billing.billing_street_address,
				                txn_billing.billing_district,
				                txn_billing.billing_city,
				                txn_billing.billing_state_province,
				                txn_billing.billing_zip_code,
				                txn_billing.billing_country,
				                txn_billing.total_amount,
				                txn_billing.subtotal,
				                txn_billing.vat,
				                txn_billing.total_vatable_charges,
				                txn_billing.total_non_vatable_charges,
				                txn_billing.document_date,
				                txn_billing.payment_due_date,
				                txn_billing.remarks
				         from txn_billing
				         where txn_billing.billing_number = '$txnnumber'");
	        $maxlen = 80;
	        $bltotal = 0;
	        $blsubtotal = 0;
	        $blvat = 0;
			if(getNumRows($rs)==1){
				while($obj = fetch($rs)){

					$billingaddr = concatAddress(
														array($obj->billing_street_address,
															  $obj->billing_district,
															  $obj->billing_city,
															  $obj->billing_state_province,
															  $obj->billing_zip_code,
															  $obj->billing_country)
												 );
 					$billingaddr1 = lineBreak($billingaddr, $maxlen);
					$billingaddrrem1 = trim(str_replace($billingaddr1, '', $billingaddr));
					$billingaddr2 = lineBreak($billingaddrrem1, $maxlen);
					$billingaddrrem2 = trim(str_replace($billingaddr2, '', $billingaddrrem1));
				    $billingaddr3 = lineBreak($billingaddrrem2, $maxlen);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'Account No.','BL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(165,$hlineheight,$obj->bill_to_account_number,'BR',0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'Document Date','BL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(35,$hlineheight,dateFormat($obj->document_date,'m/d/Y'),'BR',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'Account Name','BL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(165,$hlineheight,$obj->bill_to_account_name,'BR',0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'Payment Due Date','BL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(35,$hlineheight,dateFormat($obj->payment_due_date,'m/d/Y'),'BR',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'Company Name','BL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(230,$hlineheight,$obj->bill_to_company_name,'BR',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'Remarks','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(230,$hlineheight,$obj->remarks,'R',1);


					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(230,$hlineheight,'','R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'','BL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(230,$hlineheight,'','BR',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'BILLING INFORMATION','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(230,$hlineheight,'','R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'Contact Person','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(230,$hlineheight,strtoupper($obj->billing_contact_person),'R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'Address ','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(230,$hlineheight,$billingaddr1,'R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(230,$hlineheight,$billingaddr2,'R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(230,$hlineheight,$billingaddr3,'R',1);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'','BL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(230,$hlineheight,'','BR',1);



					

					$bltotal = convertWithDecimal($obj->total_amount,2);
	       			$bltotalvatable = convertWithDecimal($obj->total_vatable_charges,2);
	       			$bltotalnonvatable = convertWithDecimal($obj->total_non_vatable_charges,2);
	        		$blvat = convertWithDecimal($obj->vat,2);




				}
				
			}
			else{
				echo "INVALID";
			}


			/*$rs = query("

					select txn_billing_waybill.id,
				           txn_billing_waybill.billing_number,
				           txn_billing_waybill.waybill_number,
				           txn_billing_waybill.created_date,
				           txn_billing_waybill.created_by,
				           txn_billing_waybill.amount,
				           txn_waybill.waybill_type,
				           txn_waybill.document_date,
				           txn_waybill.delivery_date,
				           txn_waybill.shipper_account_name,
				           txn_waybill.consignee_account_name,
				           txn_waybill.package_number_of_packages,
				           txn_waybill.package_actual_weight,
				           txn_waybill.package_declared_value,
				           txn_waybill.package_cbm,
				           txn_waybill.package_vw,
				           txn_waybill.total_amount,
				           txn_waybill.subtotal,
				           txn_waybill.vat,
				           txn_billing.total_amount as billingtotal,
				           origintbl.description as origin,
				           destinationtbl.description as destination,
				           mode_of_transport.description as modeoftransport
				    from txn_billing_waybill
				    left join txn_billing on txn_billing.billing_number=txn_billing_waybill.billing_number
				    left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
					left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id
				    left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id
				    left join mode_of_transport on mode_of_transport.id=txn_waybill.package_mode_of_transport
				    where txn_billing_waybill.billing_number='$txnnumber'
				    
			");*/
			$rs  = query("
								select txn_billing_waybill.id,
							           txn_billing_waybill.billing_number,
							           txn_billing_waybill.waybill_number,
							           txn_billing_waybill.created_date,
							           txn_billing_waybill.created_by,
							           txn_billing_waybill.amount,
							           txn_billing_waybill.chargeable_weight,
							           txn_billing_waybill.regular_charges,
							           txn_billing_waybill.other_charges_vatable,
							           txn_billing_waybill.other_charges_non_vatable,
							           txn_billing_waybill.vat,
							           (txn_billing_waybill.regular_charges+txn_billing_waybill.other_charges_vatable) as vatable_charges,
							           origintbl.description as origin,
							           destinationtbl.description as destination,
							           mode_of_transport.description as modeoftransport,
							           case 
							           		when txn_billing_waybill.flag=0 then 'REVISED'
							           		when txn_billing_waybill.flag=1 then 'ACTIVE'
							           		else 'N/A'
							           end as 'detailstatus',
							           txn_waybill.document_date
							    from txn_billing_waybill
							    left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
							    left join txn_billing on txn_billing.billing_number=txn_billing_waybill.billing_number
								left join origin_destination_port as origintbl on origintbl.id=txn_billing_waybill.origin_id
							    left join origin_destination_port as destinationtbl on destinationtbl.id=txn_billing_waybill.destination_id
							    left join mode_of_transport on mode_of_transport.id=txn_billing_waybill.mode_of_transport_id
							    where txn_billing_waybill.billing_number='$txnnumber'

						");

			$rscount = getNumRows($rs);
			$totalrowsperpage = 12;
			$consumedlines = 0;
			$page = 1;
			$line = 1;	

		if($rscount<=12){
			$totalrowsperpage = 12;
		}
		else{
			$totalrowsperpage = 20;
		}

	    $pdf->SetFillColor($fillcolor[0],$fillcolor[1],$fillcolor[2]);
	    $pdf->SetFont('Arial','B',$detailfont);

	    $pdf->Cell(260,5,'WAYBILL TRANSACTIONS','LTR',1,'C',true);
		$pdf->Cell(20,5,'WAYBILL NO.','LBT',0,'L',true);
		$pdf->Cell(20,5,'DATE','LBT',0,'L',true);
		$pdf->Cell(30,5,'ORIGIN','LBT',0,'L',true);
		$pdf->Cell(30,5,'DESTINATION','LBT',0,'L',true);
		$pdf->Cell(40,5,'MODE','LBT',0,'L',true);
		$pdf->Cell(20,5,'CHARGE WGT','LBT',0,'L',true);
		$pdf->Cell(25,5,'VATABLE','LBT',0,'L',true);
		$pdf->Cell(25,5,'NON VATABLE','LBT',0,'L',true);
		$pdf->Cell(25,5,'VAT','LBT',0,'L',true);
		$pdf->Cell(25,5,'AMOUNT','LBTR',1,'L',true);
		$pdf->SetFont('Arial','',$detailfont);

		$billingtotal = 0;
		$even = 0;

		while ($obj = mysql_fetch_object($rs)) {
				$id = $obj->id;
				$billingnumber = utfEncode($obj->billing_number);
				$wbnumber = utfEncode($obj->waybill_number);
				$date = dateFormat($obj->document_date,'m/d/Y');
				$origin = utfEncode($obj->origin);
				$destination = utfEncode($obj->destination);
				$modeoftransport = utfEncode($obj->modeoftransport);
				$vatablecharges = convertWithDecimal($obj->vatable_charges,2);
				$otherchargesnonvatable = convertWithDecimal($obj->other_charges_non_vatable,2);
				$vat = convertWithDecimal($obj->vat,2);
				$chargeamount = convertWithDecimal($obj->amount,2);
				$detailstatus = utfEncode($obj->detailstatus);
				$chargeableweight = trim($obj->chargeable_weight)==''?'N/A':convertWithDecimal($obj->amount,4);



				//if($line>($page*$totalrowsperpage)){
				if($consumedlines>=($totalrowsperpage)){
					$consumedlines = 0;

					if($page>1){
						$pdf->Cell(260,5,'','T',1);

						$rem = $rscount - $line;
						if($rem>25){
							$totalrowsperpage = 30;
						}
						else{
							$totalrowsperpage = 25;
						}
					}



					$page++;
					$pdf->AddPage();
					$pdf->AliasNbPages();
					$pdf->ln();
					$pdf->ln();
					//line item header
					$pdf->SetFillColor($fillcolor[0],$fillcolor[1],$fillcolor[2]);
					$pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial','B',$detailfont);
					$pdf->Cell(260,5,'WAYBILL TRANSACTIONS','LTR',1,'C',true);
					$pdf->Cell(20,5,'WAYBILL NO.','LBT',0,'L',true);
					$pdf->Cell(20,5,'DATE','LBT',0,'L',true);
					$pdf->Cell(30,5,'ORIGIN','LBT',0,'L',true);
					$pdf->Cell(30,5,'DESTINATION','LBT',0,'L',true);
					$pdf->Cell(40,5,'MODE','LBT',0,'L',true);
					$pdf->Cell(20,5,'CHARGE WGT','LBT',0,'L',true);
					$pdf->Cell(25,5,'VATABLE','LBT',0,'L',true);
					$pdf->Cell(25,5,'NON VATABLE','LBT',0,'L',true);
					$pdf->Cell(25,5,'VAT','LBT',0,'L',true);
					$pdf->Cell(25,5,'AMOUNT','LBTR',1,'L',true);
					$pdf->SetFillColor($rowitem[0],$rowitem[1],$rowitem[2]);
					$pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial','',$detailfont);

				}	

				$even = 0;

				if((($totalrowsperpage*$page)!=$line)&&$line!=$rscount){
					$pdf->Cell(20,5,$wbnumber,'L',0,'L',$even);
					$pdf->Cell(20,5,$date,'L',0,'C',$even);
					$pdf->Cell(30,5,$origin,'L',0,'L',$even);
					$pdf->Cell(30,5,$destination,'L',0,'L',$even);
					$pdf->Cell(40,5,$modeoftransport,'L',0,'L',$even);
					$pdf->Cell(20,5,$chargeableweight,'L',0,'R',$even);
					$pdf->Cell(25,5,$vatablecharges,'L',0,'R',$even);
					$pdf->Cell(25,5,$otherchargesnonvatable,'L',0,'R',$even);
					$pdf->Cell(25,5,$vat,'L',0,'R',$even);
					$pdf->Cell(25,5,$chargeamount,'LR',1,'R',$even);
				}
				else{
					$pdf->Cell(20,5,$wbnumber,'BL',0,'L',$even);
					$pdf->Cell(20,5,$date,'BL',0,'C',$even);
					$pdf->Cell(30,5,$origin,'BL',0,'L',$even);
					$pdf->Cell(30,5,$destination,'BL',0,'L',$even);
					$pdf->Cell(40,5,$modeoftransport,'BL',0,'L',$even);
					$pdf->Cell(20,5,$chargeableweight,'BL',0,'R',$even);
					$pdf->Cell(25,5,$vatablecharges,'BL',0,'R',$even);
					$pdf->Cell(25,5,$otherchargesnonvatable,'BL',0,'R',$even);
					$pdf->Cell(25,5,$vat,'BL',0,'R',$even);
					$pdf->Cell(25,5,$chargeamount,'BLR',1,'R',$even);
				}

				$line++;

				$consumedlines++;


				if($page>1){
					$totalrowsperpage = 30;
				}


		}

		$pdf->Cell(260,6,'',0,1,'L',$even);

		$pdf->Cell(180,6,'',0,0,'L',$even);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(45,6,'TOTAL VATABLE CHARGES','TBL',0,'L',$even);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(35,6,$bltotalvatable,'TBLR',1,'R',$even);

		$pdf->Cell(180,6,'',0,0,'L',$even);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(45,6,'NON VATABLE CHARGES','TBL',0,'L',$even);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(35,6,$bltotalnonvatable,'TBLR',1,'R',$even);

		$pdf->Cell(180,6,'',0,0,'L',$even);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(45,6,'VAT','TBL',0,'L',$even);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(35,6,$blvat,'TBLR',1,'R',$even);

		$pdf->Cell(180,6,'',0,0,'L',$even);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(45,6,'TOTAL AMOUNT','TBL',0,'L',$even);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(35,6,$bltotal,'TBLR',1,'R',$even);


		



$pdf->Output();
	



?>