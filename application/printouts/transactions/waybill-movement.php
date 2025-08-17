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


//$imagelogo = "../../../resources/barcode-generator/generate-barcode.php?text=kaye";



			$pdf->SetFont('Arial','B',14);
			$pdf->cell(50,8,'WAYBILL MOVEMENT',0,0);
			$pdf->SetFont('Arial','B',14);
			$pdf->SetTextColor(99, 158, 188);
			$pdf->cell(70,8,'# '.$txnnumber,0,1,'R');


			$pdf->cell(150,5,'',0);

			$pdf->SetTextColor(0, 0, 0);


			$pdf->cell(195,5,'',0,1);
			$pdf->cell(200,5,'','B',1);

			$fillcolor = getColor('primary_color');//array(214, 69, 65);
			$rowitem = getColor('secondary_color');


			
		

			$headerfont = 8;
			$detailfont = 7;
			$hlineheight = 5;
			$pdf->SetFont('Arial','',$detailfont);

			$fillcolor = getColor('primary_color');//array(214, 69, 65);
			$rowitem = getColor('secondary_color');


	       $rs = query("select txn_waybill_movement.id,
				                txn_waybill_movement.waybill_movement_number,
				                txn_waybill_movement.status,
				                txn_waybill_movement.location_id,
				                txn_waybill_movement.remarks,
				                txn_waybill_movement.document_date,
				                txn_waybill_movement.created_date,
				                txn_waybill_movement.updated_date,
				                txn_waybill_movement.created_by,
				                txn_waybill_movement.updated_by,
				                concat(cuser.first_name,' ',cuser.last_name) as createdby,
				                concat(uuser.first_name,' ',uuser.last_name) as updatedby,
				                location.code as loccode,
				                location.description as locdesc,
				                movement_type.description as movementtypedesc,
				                movement_type.code as movementtypecode
				         from txn_waybill_movement
				         left join user as cuser on cuser.id=txn_waybill_movement.created_by
				         left join user as uuser on uuser.id=txn_waybill_movement.updated_by
				         left join location on location.id=txn_waybill_movement.location_id
				         left join movement_type on movement_type.id=txn_waybill_movement.movement_type_id
				         where txn_waybill_movement.waybill_movement_number = '$txnnumber'");

			if(getNumRows($rs)==1){
				while($obj = fetch($rs)){

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(20,$hlineheight,'LOCATION','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(80,$hlineheight,$obj->loccode,0,0);

					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(30,$hlineheight,'MOVEMENT TYPE','L',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(70,$hlineheight,$obj->movementtypedesc,'R',1);


					$pdf->SetFont('Arial','B',$headerfont);
					$pdf->cell(20,$hlineheight,'','BL',0);
					$pdf->SetFont('Arial','',$headerfont);
					$pdf->cell(80,$hlineheight,$obj->locdesc,'B',0);

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
				    select txn_waybill_movement_waybill.id,
				           txn_waybill_movement_waybill.waybill_movement_number,
				           txn_waybill_movement_waybill.waybill_number,
				           txn_waybill_movement_waybill.created_date,
				           txn_waybill_movement_waybill.created_by,
				           txn_waybill_movement_waybill.remarks,
				           origintbl.description as origin,
				           destinationtbl.description as destination,
				           destinationroutetbl.description as destinationroute,
				           numofpckgtbl.numofpackage,
				           totalpckgtbl.totalpackage,
				           concat(numofpckgtbl.numofpackage,'/',totalpckgtbl.totalpackage) as pckgs
				    from txn_waybill_movement_waybill
				    left join txn_waybill on txn_waybill.waybill_number=txn_waybill_movement_waybill.waybill_number
					left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id
				    left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id
				    left join destination_route as destinationroutetbl on destinationroutetbl.id=txn_waybill.destination_route_id
				    left join (		
				    			 select txn_waybill_movement_package_code.waybill_movement_number,
				    			 		txn_waybill_movement_package_code.waybill_number,
				    			 		count(package_code) as numofpackage
				    			 from txn_waybill_movement_package_code
				    			 where txn_waybill_movement_package_code.waybill_movement_number='$txnnumber'
				    			 group by txn_waybill_movement_package_code.waybill_number

				    		   ) as numofpckgtbl
				    on numofpckgtbl.waybill_movement_number=txn_waybill_movement_waybill.waybill_movement_number and
				       numofpckgtbl.waybill_number=txn_waybill_movement_waybill.waybill_number
				    left join (
				    			 select waybill_number,
				    			        count(code) as totalpackage 
				    			 from txn_waybill_package_code 
				    			 group by waybill_number

				    		   ) as totalpckgtbl
				    on totalpckgtbl.waybill_number=txn_waybill.waybill_number
				    where txn_waybill_movement_waybill.waybill_movement_number='$txnnumber'
				");

			$rscount = getNumRows($rs);
			$totalrowsperpage = 35;
			$page = 1;
			$line = 1;	

	    $pdf->SetFillColor($fillcolor[0],$fillcolor[1],$fillcolor[2]);
	    $pdf->SetFont('Arial','B',$detailfont);

	    $pdf->ln();
	    $pdf->Cell(200,5,'WAYBILL TRANSACTIONS','LTR',1,'C',true);
		$pdf->Cell(45,5,'WAYBILL NO.','LBT',0,'L',true);
		$pdf->Cell(30,5,'PACKAGE COUNT','LBT',0,'L',true);
		$pdf->Cell(40,5,'ORIGIN','LBT',0,'L',true);
		$pdf->Cell(40,5,'DESTINATION','LBT',0,'L',true);
		$pdf->Cell(45,5,'ROUTE','LBTR',1,'L',true);
		$pdf->SetFont('Arial','',$detailfont);
		while ($obj = mysql_fetch_object($rs)) {
				$id = $obj->id;
				$wbnumber = $obj->waybill_number;
				$origin = $obj->origin;
				$destination = $obj->destination;
				$destinationroute = $obj->destinationroute;
				$pckgs = $obj->pckgs;

				if($line>($page*$totalrowsperpage)){

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
					$pdf->Cell(45,5,'WAYBILL NO.','LBT',0,'L',true);
					$pdf->Cell(30,5,'PACKAGE COUNT','LBT',0,'L',true);
					$pdf->Cell(40,5,'ORIGIN','LBT',0,'L',true);
					$pdf->Cell(40,5,'DESTINATION','LBT',0,'L',true);
					$pdf->Cell(45,5,'ROUTE','LBTR',1,'L',true);
					$pdf->SetFillColor($rowitem[0],$rowitem[1],$rowitem[2]);
					$pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial','',$detailfont);

				}	

				$even = 0;

				if((($totalrowsperpage*$page)!=$line)&&$line!=$rscount){
					$pdf->Cell(45,5,$wbnumber,'L',0,'L',$even);
					$pdf->Cell(30,5,$pckgs,'L',0,'C',$even);
					$pdf->Cell(40,5,$origin,'L',0,'L',$even);
					$pdf->Cell(40,5,$destination,'L',0,'L',$even);
					$pdf->Cell(45,5,$destinationroute,'LR',1,'L',$even);
				}
				else{
					$pdf->Cell(45,5,$wbnumber,'BL',0,'L',$even);
					$pdf->Cell(30,5,$pckgs,'BL',0,'C',$even);
					$pdf->Cell(40,5,$origin,'BL',0,'L',$even);
					$pdf->Cell(40,5,$destination,'BL',0,'L',$even);
					$pdf->Cell(45,5,$destinationroute,'BLR',1,'L',$even);
				}

				$line++;
		}


		$rs = query("
				     select txn_waybill_movement_package_code.id,
				           txn_waybill_movement_package_code.waybill_movement_number,
				           txn_waybill_movement_package_code.waybill_number,
				           txn_waybill_movement_package_code.package_code,
				           txn_waybill_movement_package_code.created_date,
				           txn_waybill_movement_package_code.created_by
				    from txn_waybill_movement_package_code
				    where txn_waybill_movement_package_code.waybill_movement_number='$txnnumber'
				    order by waybill_number, txn_waybill_movement_package_code.package_code asc
				");

			$rscount = getNumRows($rs);
			$totalrowsperpage = 20;
			$page = 1;
			$line = 1;	

	    $pdf->SetFillColor($fillcolor[0],$fillcolor[1],$fillcolor[2]);
	    $pdf->SetFont('Arial','B',$detailfont);

	    $pdf->ln();
	    $pdf->Cell(200,5,'WAYBILL PACKAGES','LTR',1,'C',true);
		$pdf->Cell(45,5,'WAYBILL NO.','LBT',0,'L',true);
		$pdf->Cell(155,5,'PACKAGE CODE','LBTR',1,'L',true);
		$pdf->SetFont('Arial','',$detailfont);
		if(getNumRows($rs)>0){
			while ($obj = mysql_fetch_object($rs)) {
				$id = $obj->id;
				$wbnumber = $obj->waybill_number;
				$code = $obj->package_code;

				if($line>($page*$totalrowsperpage)){

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
					$pdf->Cell(45,5,'WAYBILL NO.','LBT',0,'L',true);
					$pdf->Cell(155,5,'PACKAGE CODE','LBTR',1,'L',true);
					$pdf->SetFillColor($rowitem[0],$rowitem[1],$rowitem[2]);
					$pdf->SetTextColor(0, 0, 0);
					$pdf->SetFont('Arial','',$detailfont);

				}	

				$even = 0;

				if((($totalrowsperpage*$page)!=$line)&&$line!=$rscount){
					$pdf->Cell(45,5,$wbnumber,'L',0,'L',$even);
					$pdf->Cell(155,5,$code,'LR',1,'L',$even);
				}
				else{
					$pdf->Cell(45,5,$wbnumber,'BL',0,'L',$even);
					$pdf->Cell(155,5,$code,'BLR',1,'L',$even);
				}

				$line++;
			}
		}
		else{
			 $pdf->Cell(200,5,'No package codes','LBTR',1,'C',true);
		}




$pdf->Output();
	



?>