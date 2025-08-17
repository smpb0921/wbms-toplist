<?php

include("../../../config/connection.php");
include("../../../config/checklogin.php");
include("../../../config/functions.php");
require('../../../resources/htmltopdf/fpdf.php');
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



class PDF extends FPDF
{	
	function Footer()
	{
		$this->SetFont('Arial','',10);
		$this->SetY(-12);
		$this->Cell(100,3,'Date Effective: July 23, 2019',0,0,'L');
		$this->Cell(105,3,'QF-OPR-09 Issue #2 rev.01',0,0,'R');

	    $this->SetY(-8);
	    $this->SetFont('Arial','B',6);
	    $this->Cell(210,3,'',0,0,'C');
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
$headerlinespacing = 5;

$rs = query("select carrier.description as carrier,
	                txn_manifest.document_date,
	                txn_manifest.remarks
	         from txn_manifest 
	         left join carrier on carrier.id=txn_manifest.trucker_name
	         where txn_manifest.manifest_number='$txnnumber'");


$rowcount = getNumRows($rs);


if($rowcount==1){

			while($obj=fetch($rs)){
				$carrier = $obj->carrier;
				$docdate = dateFormat($obj->document_date,'m/d/Y');
				$txnremarks = $obj->remarks;
			}





			$imgpath = '../../../barcode/';
			$imagelogo = "../../../resources/printout-logo.png";
			$pdf->Cell(70, 3, $pdf->Image($imagelogo, $pdf->GetX(), $pdf->GetY(), 20),0,0);

			$pdf->SetFont('Arial','B',14);
			$pdf->cell(60,5,'DISPATCH TRANSMITTAL',0,0);
			$pdf->SetFont('Arial','B',14);
			$pdf->SetTextColor(99, 158, 188);
			$pdf->cell(70,5,'# '.$txnnumber,0,1,'R');


		    $pdf->SetMargins(4,0);

			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFont('Arial','B',10);

			$pdf->cell(10,10,'',0,1);

			$pdf->cell(35,5,'SUPPLIER NAME: ',0,0);
			$pdf->cell(110,5,strtoupper($carrier),'B',0,'C');

			$pdf->cell(10,5,'',0,0);

			$pdf->cell(15,5,'DATE: ',0,0);
			$pdf->cell(30,5,$docdate,'B',1,'C');

			


			$pdf->SetFont('Arial','B',6);
			

			$pdf->Cell(10,$headerlinespacing,'',0,1);

			$pdf->Cell(10,$headerlinespacing,'','TLR',0,'L');
			$pdf->Cell(15,$headerlinespacing,'DATE','TLR',0,'C');
			$pdf->Cell(15,$headerlinespacing,'DATE','TLR',0,'C');
			$pdf->Cell(70,$headerlinespacing,'','TLR',0,'L');
			$pdf->Cell(25,$headerlinespacing,'','TLR',0,'L');
			$pdf->Cell(30,$headerlinespacing,'STATUS','BTLR',0,'C');
			$pdf->Cell(18,$headerlinespacing,'DAYS','TLR',0,'C');
			$pdf->Cell(25,$headerlinespacing,'','TLR',1,'L');

			$pdf->Cell(10,$headerlinespacing,'NO.','BLR',0,'C');
			$pdf->Cell(15,$headerlinespacing,'PICKUP','BLR',0,'C');
			$pdf->Cell(15,$headerlinespacing,'DISPATCHED','BLR',0,'C');
			$pdf->Cell(70,$headerlinespacing,'COMPANY NAME','BLR',0,'C');
			$pdf->Cell(25,$headerlinespacing,'TRACKING NOS.','BLR',0,'C');
			$pdf->Cell(15,$headerlinespacing,'ACCURATE','BLR',0,'C');
			$pdf->Cell(15,$headerlinespacing,'INACCURATE','BLR',0,'C');
			$pdf->Cell(18,$headerlinespacing,'PROCESSED','BLR',0,'C');
			$pdf->Cell(25,$headerlinespacing,'REMARKS','BLR',1,'C');

			

			


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
						           origintbl.description as origin,
						           destinationtbl.description as destination,
						           mode_of_transport.description as modeoftransport,
						           pouch_size.code as pouchsize
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
				$totalrowsperpage = 35;
				$remrows = 35;
				$morethanadaycounter = 0;

				$daysprocessedarray = array();
				$daysprocessedcountarray = array();

				$daysprccount = array(0,0,0,0);

				$pouchsizearray = array();
				$pouchsizecountarray = array();

				while ($obj = mysql_fetch_object($rs)) {

					$pickupdate = dateFormat($obj->pickup_date,'m/d/Y');
					$dispatcheddate = $docdate;
					$waybillnumber = $obj->waybill_number;
					$shipper = $obj->shipper_account_name;
					$pouchsize = $obj->pouchsize;

					$ddate = strtotime($dispatcheddate);
					$pdate = strtotime($obj->pickup_date);
					$datediff = $ddate - $pdate;

					$daysprocessed = round($datediff / (60 * 60 * 24));

					if($daysprocessed>1){
						$morethanadaycounter++;
					}



					if($daysprocessed>3){
						$currentval = $daysprccount[3];
						$newval = $currentval+1;
						$daysprccount[3] = $newval;
					}
					else{
						$index = $daysprocessed-1;

						$index = $index<0?0:$index;

						$currentval = $daysprccount[$index];
						$newval = $currentval+1;
						$daysprccount[$index] = $newval;
					}


					if(in_array("$pouchsize", $pouchsizearray)){
						$index = array_search("$pouchsize", $pouchsizearray);
						$currentval = $pouchsizecountarray[$index];
						$newval = $currentval+1;
						$pouchsizecountarray[$index] = $newval;
					}
					else{
						array_push($pouchsizearray, "$pouchsize");
						array_push($pouchsizecountarray, 1);
					}

					

					/*if(in_array("$daysprocessed", $daysprocessedarray)){

						//echo "exists $daysprocessed <br>";

						$index = array_search("$daysprocessed", $daysprocessedarray);
						$currentval = $daysprocessedcountarray[$index];
						$newval = $currentval+1;
						$daysprocessedcountarray[$index] = $newval;

						//echo $daysprocessedcountarray[$index]."<br>";

					}
					else{
						//echo "not exists $daysprocessed <br>";
						array_push($daysprocessedarray, "$daysprocessed");
						array_push($daysprocessedcountarray, 1);
					}*/


					if($line>($page*$totalrowsperpage)){



						$linerow = 1;

						//$pdf->Cell(208,3,'','T',0,'L');

						$page++;
						$pdf->AddPage();
						$pdf->AliasNbPages();
						$pdf->ln();
						$pdf->ln();
						$pdf->ln();
						$pdf->Cell(70, 3, $pdf->Image($imagelogo, $pdf->GetX(), $pdf->GetY(), 20),0,0);

						$pdf->SetFont('Arial','B',14);
						$pdf->cell(60,5,'DISPATCH TRANSMITTAL',0,0);
						$pdf->SetFont('Arial','B',14);
						$pdf->SetTextColor(99, 158, 188);
						$pdf->cell(70,5,'# '.$txnnumber,0,1,'R');


					    $pdf->SetMargins(4,0);

						$pdf->SetTextColor(0, 0, 0);
						$pdf->SetFont('Arial','B',10);

						$pdf->cell(10,10,'',0,1);

						$pdf->cell(35,5,'SUPPLIER NAME: ',0,0);
						$pdf->cell(110,5,strtoupper($carrier),'B',0,'C');

						$pdf->cell(10,5,'',0,0);

						$pdf->cell(15,5,'DATE: ',0,0);
						$pdf->cell(30,5,$docdate,'B',1,'C');

						$pdf->ln();

						$pdf->SetFont('Arial','B',6);


						

						$pdf->Cell(10,$headerlinespacing,'','TLR',0,'L');
						$pdf->Cell(15,$headerlinespacing,'DATE','TLR',0,'C');
						$pdf->Cell(15,$headerlinespacing,'DATE','TLR',0,'C');
						$pdf->Cell(70,$headerlinespacing,'','TLR',0,'L');
						$pdf->Cell(25,$headerlinespacing,'','TLR',0,'L');
						$pdf->Cell(30,$headerlinespacing,'STATUS','BTLR',0,'C');
						$pdf->Cell(18,$headerlinespacing,'DAYS','TLR',0,'C');
						$pdf->Cell(25,$headerlinespacing,'','TLR',1,'L');

						$pdf->Cell(10,$headerlinespacing,'NO.','BLR',0,'C');
						$pdf->Cell(15,$headerlinespacing,'PICKUP','BLR',0,'C');
						$pdf->Cell(15,$headerlinespacing,'DISPATCHED','BLR',0,'C');
						$pdf->Cell(70,$headerlinespacing,'COMPANY NAME','BLR',0,'C');
						$pdf->Cell(25,$headerlinespacing,'TRACKING NOS.','BLR',0,'C');
						$pdf->Cell(15,$headerlinespacing,'ACCURATE','BLR',0,'C');
						$pdf->Cell(15,$headerlinespacing,'INACCURATE','BLR',0,'C');
						$pdf->Cell(18,$headerlinespacing,'PROCESSED','BLR',0,'C');
						$pdf->Cell(25,$headerlinespacing,'REMARKS','BLR',1,'C');

						$remrows = 35;
						

					}	

					$even = 0;
					$pdf->SetFont('Arial','',7);

					//if((($totalrowsperpage*$page)!=$line)&&$line!=$rscount){
						
						$pdf->Cell(10,$headerlinespacing,$linerow,'LR',0,'C');
						$pdf->Cell(15,$headerlinespacing,$pickupdate,'LR',0,'C');
						$pdf->Cell(15,$headerlinespacing,$dispatcheddate,'LR',0,'C');
						$pdf->Cell(70,$headerlinespacing,strtoupper($shipper),'LR',0,'L');
						$pdf->Cell(25,$headerlinespacing,$waybillnumber,'LR',0,'C');
						$pdf->Cell(15,$headerlinespacing,'','LR',0,'C');
						$pdf->Cell(15,$headerlinespacing,'','LR',0,'C');
						$pdf->Cell(18,$headerlinespacing,$daysprocessed,'LR',0,'C');
						$pdf->Cell(25,$headerlinespacing,'','LR',1,'C');

						$remrows = $remrows-1;
					//}
					

					if($remrows>0&&$line==$rscount){
						
						while($remrows>0){
								$line++;
								$linerow++;
								if($remrows==1){
									$pdf->Cell(10,$headerlinespacing,$linerow,'BLR',0,'C');
									$pdf->Cell(15,$headerlinespacing,'','BLR',0,'C');
									$pdf->Cell(15,$headerlinespacing,'','BLR',0,'C');
									$pdf->Cell(70,$headerlinespacing,'','BLR',0,'C');
									$pdf->Cell(25,$headerlinespacing,'','BLR',0,'C');
									$pdf->Cell(15,$headerlinespacing,'','BLR',0,'C');
									$pdf->Cell(15,$headerlinespacing,'','BLR',0,'C');
									$pdf->Cell(18,$headerlinespacing,'','BLR',0,'C');
									$pdf->Cell(25,$headerlinespacing,'','BLR',1,'C');
								}
								else{
									$pdf->Cell(10,$headerlinespacing,$linerow,'LR',0,'C');
									$pdf->Cell(15,$headerlinespacing,'','LR',0,'C');
									$pdf->Cell(15,$headerlinespacing,'','LR',0,'C');
									$pdf->Cell(70,$headerlinespacing,'','LR',0,'C');
									$pdf->Cell(25,$headerlinespacing,'','LR',0,'C');
									$pdf->Cell(15,$headerlinespacing,'','LR',0,'C');
									$pdf->Cell(15,$headerlinespacing,'','LR',0,'C');
									$pdf->Cell(18,$headerlinespacing,'','LR',0,'C');
									$pdf->Cell(25,$headerlinespacing,'','LR',1,'C');
								}
								$remrows = $remrows-1;



						}
					}
					else{

					}

					if($linerow==35){
						/*$totalpouchsizecount = 0;
						$rs1 = query("select group_concat(distinct pouch_size.description) as pouchsize,
											count(txn_waybill.pouch_size_id) as totalpscount
							         from txn_manifest_waybill
							         left join txn_waybill on txn_waybill.waybill_number=txn_manifest_waybill.waybill_number
							         left join pouch_size on txn_waybill.pouch_size_id=pouch_size.id
							         where txn_manifest_waybill.manifest_number='$txnnumber'
							         group by txn_manifest_waybill.manifest_number, txn_waybill.pouch_size_id");

						$pouchsizearr = array();
						while($obj1=fetch($rs1)){
							if($obj1->totalpscount>1){
								$suffix = 'pcs';
							}
							else{
								$suffix = 'pc';
							}
							$pouchsizedesc = $obj1->pouchsize.' - '.$obj1->totalpscount." $suffix";

							array_push($pouchsizearr, $pouchsizedesc);
						}

						$pouchsizedesc = implode(', ', $pouchsizearr);*/

						$pouchsizedesc = '';
						for($i=0;$i<count($pouchsizearray);$i++){
							$pouchsizedesc .= $pouchsizearray[$i].' - '.$pouchsizecountarray[$i].', ';
						}


						$pdf->SetFont('Arial','B',8);
						$pdf->Cell(15,8,'TOTAL','TBLR',0,'C');
						$pdf->Cell(150,8,trim($pouchsizedesc,', '),'TBLR',0,'C');
						$pdf->Cell(18,8,$morethanadaycounter,'TBLR',0,'C');
						$pdf->Cell(25,8,'More than 1 day','TBLR',1,'C');

						
						$pdf->Cell(52,8,"1 day - ".$daysprccount[0]." ".pcsuffix($daysprccount[0]),'TBLR',0,'C');
						$pdf->Cell(52,8,"2 days - ".$daysprccount[1]." ".pcsuffix($daysprccount[1]),'TBLR',0,'C');
						$pdf->Cell(52,8,"3 days - ".$daysprccount[2]." ".pcsuffix($daysprccount[2]),'TBLR',0,'C');
						$pdf->Cell(52,8,"4 days and above - ".$daysprccount[3]." ".pcsuffix($daysprccount[3]),'TBLR',1,'C');

						$pdf->Cell(25,8,'',0,1,'C');

						
						$pdf->Cell(23,8,'Prepared by: ',0,0,'L');
						$pdf->Cell(40,8,'','B',0,'C');
						$pdf->Cell(8,5,'',0,0,'C');
						$pdf->Cell(23,8,'Reviewed by: ',0,0,'L');
						$pdf->Cell(40,8,'','B',0,'C');
						$pdf->Cell(8,5,'',0,0,'C');
						$pdf->Cell(23,8,'Approved by: ',0,0,'L');
						$pdf->Cell(40,8,'','B',1,'C');

						

						
						$pdf->Cell(23,5,'',0,0,'L');
						$pdf->Cell(40,5,'Operations Clerk',0,0,'C');
						$pdf->Cell(8,5,'',0,0,'C');
						$pdf->Cell(23,5,'',0,0,'L');
						$pdf->Cell(40,5,'Dispatching Supervisor',0,0,'C');
						$pdf->Cell(8,5,'',0,0,'C');
						$pdf->Cell(23,5,'',0,0,'L');
						$pdf->Cell(40,5,'Operations Manager',0,1,'C');


						$morethanadaycounter = 0;
						$daysprccount = array(0,0,0,0);
						$pouchsizearray = array();
						$pouchsizecountarray = array();
					}
					





					$line++;
					$linerow++;

				}

				//print_r($daysprccount);

				



			}
			else{
				$pdf->Cell(208,5,'No results found','BLR',0,'C');
			}


			


	

}
else{
	$pdf->Cell(200,5,'Invalid Manifest Number',0,1,'C');
}




$pdf->Output();
	



?>