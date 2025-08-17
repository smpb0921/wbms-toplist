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
				                txn_billing.remarks,
				                shipper.tin
				         from txn_billing
				         left join shipper on shipper.id=txn_billing.shipper_id
				         where txn_billing.billing_number = '$txnnumber'");

if(getNumRows($rs)==1){
	while($obj = fetch($rs)){
		$maxlen = 55;
		$shipperaddr = concatAddress(
			array($obj->billing_street_address,
				$obj->billing_district,
				$obj->billing_city,
				$obj->billing_state_province)
			);
		$shipperaddr1 = lineBreak($shipperaddr, $maxlen);
		$shipperaddrrem1 = trim(str_replace($shipperaddr1, '', $shipperaddr));
		$shipperaddr2 = lineBreak($shipperaddrrem1, $maxlen);
		$shipperaddrrem2 = trim(str_replace($shipperaddr2, '', $shipperaddrrem1));
		$shipperaddr3 = lineBreak($shipperaddrrem2, $maxlen);

		$pdf->SetTextColor(0, 0, 0);

		$pdf->SetFont('Arial','',12);
		$pdf->SetY(46);
		$pdf->cell(28,5,'',0,0);
		$pdf->cell(115,5,strtoupper($obj->bill_to_account_name),0,0);
		$pdf->cell(30,5,date('F d, Y'),0,0);

		$pdf->SetFont('Arial','',9);
		$pdf->SetY(51);
		$pdf->cell(28,5,'',0,0);
		$pdf->cell(115,5,$shipperaddr1,0,0);
		$pdf->cell(30,5,strtoupper($obj->tin),0,0);

		$pdf->SetY(55);
		$pdf->cell(28,5,'',0,0);
		$pdf->cell(30,5,$shipperaddr2,0,0);


		$rs1  = query("SELECT txn_billing_waybill.id,
								txn_billing_waybill.billing_number,
								txn_waybill.mawbl_bl waybill_number,
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
								txn_waybill.document_date,
								ifnull(txn_waybill.package_actual_weight,'0.00') package_actual_weight,
								if(waybill_type='DOCUMENT','dox','wpx') wyb_type,
								ifnull(if(mode_of_transport.id in (select id from mode_of_transport where description like '%SEA%'),concat(format(sum(package_cbm),1),'cbm'),concat(format(sum(package_actual_weight),2),'kg')),'0.00') weight_display
								
								
						from txn_billing_waybill
						left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
						left join txn_billing on txn_billing.billing_number=txn_billing_waybill.billing_number
						left join origin_destination_port as origintbl on origintbl.id=txn_billing_waybill.origin_id
						left join origin_destination_port as destinationtbl on destinationtbl.id=txn_billing_waybill.destination_id
						left join mode_of_transport on mode_of_transport.id=txn_billing_waybill.mode_of_transport_id
							    where txn_billing_waybill.billing_number='$txnnumber'
						group by txn_waybill.mawbl_bl order by txn_waybill.mawbl_bl,txn_waybill.waybill_number
						");

		$y = 82;
		$pdf->SetMargins(9,0);
		if(getNumRows($rs1)<=24){
			while($obj1=fetch($rs1)){
				$pdf->SetFont('Arial','',9);
				$pdf->SetY($y);
				$pdf->cell(15,5,dateFormat($obj1->document_date,'m-d'),0,0);
				$pdf->cell(19,5,$obj1->waybill_number,0,0);
				$pdf->cell(30,5,$obj1->destination,0,0);
				$pdf->cell(10,5,$obj1->wyb_type,0,0);
				$pdf->cell(10,5,$obj1->weight_display,0,0);
				$pdf->cell(20,5,convertWithDecimal($obj1->amount,2),0,0,'R');
				$pdf->cell(54,5,convertWithDecimal($obj1->amount,2),0,0,'R');
				$pdf->cell(35,5,convertWithDecimal($obj1->amount,2),0,0,'R');

				$y = $y+4;
			}
		}
		else {
			$pdf->SetFont('Arial','',10);
			$pdf->SetY(105);
			$pdf->cell(10,5,'',0,0);
			$pdf->cell(19,5,'SEE ATTACHED',0,0);

			$pdf->SetY(115);
			$pdf->cell(10,5,'',0,0);
			$pdf->cell(19,5,'Transaction Summary & '.getNumRows($rs1)." Piece(s) Bill of Lading with DR",0,0);

			$pdf->SetY(120);
			$pdf->cell(10,5,'',0,0);
			$pdf->cell(10,5,"For ",0,0);
			$pdf->cell(19,5,'','B',0);

			$pdf->SetY(125);
			$pdf->cell(10,5,'',0,0);
			$pdf->cell(10,5,"Note: ",0,0);
			$pdf->cell(19,5,$obj->remarks,0,0);
		}



	}
}







$pdf->Output();
	



?>