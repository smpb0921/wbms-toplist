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
	   
	}
}

$pdf = new PDF('P','mm','LETTER');
//$pdf->AliasNbPages();
$pdf->AddPage();
$now = date('m/d/Y h:i:s A');

$rs = query("SELECT txn_billing.id,
				                txn_billing.billing_number,
				                txn_billing.bill_to_account_number,
				                txn_billing.bill_to_account_name,
				                txn_billing.bill_to_company_name,
				                txn_billing.billing_contact_person,
				                shipper.billing_street_address,
				                shipper.billing_district,
				                shipper.billing_city,
				                shipper.billing_state_province,
				                shipper.billing_zip_code,
				                shipper.billing_country,
				                txn_billing.total_amount,
				                txn_billing.subtotal,
				                txn_billing.vat,
				                txn_billing.total_vatable_charges,
				                txn_billing.total_non_vatable_charges,
				                txn_billing.document_date,
				                txn_billing.payment_due_date,
				                txn_billing.remarks,
								shipper.tin,
								txn_billing.attention,
								txn_billing.vat_flag
								
				         from txn_billing
				         left join shipper on shipper.id=txn_billing.shipper_id
				         where txn_billing.billing_number = '$txnnumber'");

if(getNumRows($rs)==1){
	while($obj = fetch($rs)){
		$maxlen = 55;
		$shipperaddr = concatAddress(
			array(ucwords(strtolower($obj->billing_street_address),", "),
				ucwords(strtolower($obj->billing_district),", "),
				ucwords(strtolower($obj->billing_city),", "),
				ucwords(strtolower($obj->billing_state_province),", "))
			);
		$shipperaddr = ucwords(strtolower($shipperaddr));
		$shipperaddr1 = lineBreak($shipperaddr, $maxlen);
		$shipperaddrrem1 = trim(str_replace($shipperaddr1, '', $shipperaddr));
		$shipperaddr2 = lineBreak($shipperaddrrem1, $maxlen);
		$shipperaddrrem2 = trim(str_replace($shipperaddr2, '', $shipperaddrrem1));
		$shipperaddr3 = lineBreak($shipperaddrrem2, $maxlen);

		$pdf->SetTextColor(0, 0, 0);

		$pdf->SetFont('Arial','',12);
		$pdf->SetY(43.5);
		$pdf->cell(28,5,'',0,0);
		$pdf->cell(135,5,strtoupper($obj->bill_to_account_name),0,0);
		$pdf->cell(30,5,dateFormat($obj->document_date,'F d, Y'),0,0,'R');

		$pdf->SetFont('Arial','',9);
		$pdf->SetY(49.5);
		$pdf->cell(28,5,'',0,0);
		$pdf->cell(135,5,$shipperaddr1,0,0);
		$pdf->SetFont('Arial','',12);
		$pdf->cell(30,5,strtoupper($obj->tin),0,0,'R');

		$pdf->SetFont('Arial','',9);
		$pdf->SetY(54);
		$pdf->cell(28,5,'',0,0);
		$pdf->cell(30,5,$shipperaddr2,0,1);
		$pdf->cell(28,5,'',0,0);
		$pdf->cell(30,5,$obj->attention,0,1);


		$rs1  = query("SELECT txn_billing_waybill.id,
							txn_billing_waybill.billing_number,
							ifnull(txn_waybill.mawbl_bl,txn_waybill.waybill_number) waybill_number,
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
							trim(replace(lower(
								if(txn_waybill.consignee_state_province = 'METRO MANILA', if(length(txn_waybill.consignee_city)>0,txn_waybill.consignee_city,if(length(txn_waybill.consignee_state_province)>0,txn_waybill.consignee_state_province,destinationtbl.description)),
									destinationtbl.description )),'city','')) as destination,
							mode_of_transport.description as modeoftransport,
							case 
									when txn_billing_waybill.flag=0 then 'REVISED'
									when txn_billing_waybill.flag=1 then 'ACTIVE'
									else 'N/A' 
							end as 'detailstatus',
							txn_waybill.document_date, 
							txn_waybill.pickup_date, 
							if(waybill_type='DOCUMENT',if(express_transaction_type='DOCUMENT','dox','wpx'),'wpx') wyb_type,
							ifnull(
								if(mode_of_transport.id in (select id from mode_of_transport where description like '%SEA%'),
									concat(format(ceil(package_cbm),1),'cbm'),
									replace(
										concat(
											format(
												if(txn_waybill.package_chargeable_weight<=.5,
												package_chargeable_weight*1000,
													round(package_chargeable_weight,1)),if(package_chargeable_weight<=.5,0,1)),
											if(package_chargeable_weight<=.5,
												'gms',
												if(package_chargeable_weight = 1,
													'kg','kgs'))),'.00','.0')),'0') weight_display
							
							
					from txn_billing_waybill
					left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
					left join txn_billing on txn_billing.billing_number=txn_billing_waybill.billing_number
					left join origin_destination_port as origintbl on origintbl.id=txn_billing_waybill.origin_id
					left join origin_destination_port as destinationtbl on destinationtbl.id=txn_billing_waybill.destination_id
					left join mode_of_transport on mode_of_transport.id=txn_waybill.package_mode_of_transport
								where txn_billing_waybill.billing_number='$txnnumber'
						order by txn_waybill.pickup_date,txn_waybill.mawbl_bl,txn_waybill.waybill_number
						");

		$y = 82;
		$grandtotal = 0;
		$pdf->SetMargins(9,0);
		if(getNumRows($rs1)<=20) {
			while($obj1=fetch($rs1)){
				$pdf->SetFont('Arial','',9);
				$pdf->SetY($y);
				$pdf->cell(14,5,dateFormat($obj1->pickup_date,'m-d'),0,0,'C');
				$pdf->cell(18,5,$obj1->waybill_number,0,0);
				$pdf->cell(40,5,ucwords(strtolower($obj1->destination)),0,0);

				if(strpos(strtolower($obj1->modeoftransport),"land") !== false){
  
					$pdf->cell(23,5,"Trucking",0,0); 

				}
				else {
 
					$pdf->cell(10,5,$obj1->wyb_type,0,0);
					$pdf->cell(13,5,$obj1->weight_display,0,0,'C');

				}
  
				$pdf->cell(20,5,convertWithDecimal($obj1->vatable_charges,2),0,0,'R');
				$pdf->cell(40,5,convertWithDecimal($obj1->vatable_charges,2),0,0,'R');
				$pdf->cell(40,5,convertWithDecimal($obj1->vatable_charges,2),0,1,'R');
				$grandtotal += $obj1->vatable_charges;
				$y = $y+4;
			}
  
			$vat = ($obj->vat_flag ? convertWithDecimal($grandtotal * 0.12,2) : '');
			$gross = convertWithDecimal($grandtotal,2);
			$net = convertWithDecimal($grandtotal + ($obj->vat_flag ? $grandtotal * .12 : 0),2);

			$pdf->SetY(-82);
			$pdf->SetFont('Arial','',12);
			$pdf->cell(165,5,'',0,0);
			$pdf->cell(30,5,$gross,0,1,'R');
			$pdf->cell(165,5,'',0,0);
			$pdf->cell(30,5,$vat,0,1,'R');
			$pdf->cell(165,5,'',0,0);
			$pdf->cell(165,5,'',0,1);
			$pdf->cell(165,5,'',0,1);
			$pdf->cell(165,5,'',0,0);
			$pdf->cell(30,5,$net,0,1,'R');
			
		}
		else {

			
			while($obj1=fetch($rs1)) {
				$grandtotal += $obj1->vatable_charges; 
			}
			
			$vat = ($obj->vat_flag ? convertWithDecimal($grandtotal * 0.12,2) : '');
			$gross = convertWithDecimal($grandtotal,2);
			$net = convertWithDecimal($grandtotal + ($obj->vat_flag ? $grandtotal * .12 : 0),2);


			$pdf->SetFont('Arial','',10);
			$pdf->SetY(165);
			$pdf->cell(10,5,'',0,0);
			$pdf->cell(19,5,'SEE ATTACHED',0,0);

			$pdf->SetY(170);
			$pdf->cell(10,5,'',0,0);
			$pdf->cell(19,5,'Transaction Summary & '.getNumRows($rs1)." Piece(s) Bill of Lading",0,0);

			$pdf->SetY(180);
			$pdf->cell(10,5,'',0,0);
			$pdf->cell(10,5,"Next 30 Days Upon Receipt of Billing Statement",0,0);
			$pdf->cell(19,5,'','',0);

			$pdf->SetY(-82);
			$pdf->SetFont('Arial','',12);
			$pdf->cell(165,5,'',0,0);
			$pdf->cell(30,5,$gross,0,1,'R');
			$pdf->cell(165,5,'',0,0);
			$pdf->cell(30,5,$vat,0,1,'R');
			$pdf->cell(165,5,'',0,0);
			$pdf->cell(165,5,'',0,1);
			$pdf->cell(165,5,'',0,1);
			$pdf->cell(165,5,'',0,0);
			$pdf->cell(30,5,$net,0,1,'R');
		}



	}
}







$pdf->Output();
	



?>