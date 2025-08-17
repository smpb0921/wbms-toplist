<?php



    include("../../../config/connection.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");
    include("../../../resources/PHPExcel-1.8/classes/PHPExcel.php");


    $form = isset($_GET['format'])?escapeString(strtoupper($_GET['format'])):'';
    $billingnumber = isset($_GET['billingnumber'])?escapeString(strtoupper($_GET['billingnumber'])):'';
  

    function cellColor($objPHPExcel,$cells,$color){
        $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                 'rgb' => $color
            )
        ));
    }

    $blshipper = '';
    $blattn = '';
    $bladdr1 = '';
    $bladdr2 = '';
    $bldate = '';
    $bltin = '';
    $bldatereceived = '';
    $bltimereceived = '';
    $blreceivedby = '';
    $blsubtotal = '';
    $blvat = '';
    $bltotal = '';

    $getbillininfors = query("select * from txn_billing where billing_number='$billingnumber'");
    while($obj=fetch($getbillininfors)){
        $blshipper = strtoupper($obj->bill_to_account_name);
        $blattn = '';
        $bladdr1 = $obj->billing_street_address;
        $bladdr2 = ucwords(strtolower($obj->billing_city)).', '.ucwords(strtolower($obj->billing_state_province));
        $bldate = dateFormat($obj->document_date,'F d, Y');
        $blsubtotal = $obj->total_vatable_charges+$obj->total_non_vatable_charges;
        $blsubtotal = convertWithDecimal($blsubtotal,5);
        $blvat = convertWithDecimal($obj->vat,5);
        $bltotal = convertWithDecimal($obj->total_amount,5);
    }

     $objExcel = new PHPExcel();
     $sheet = $objExcel->getActiveSheet();

     $sheet->setTitle('Billing Summary');

     $qry =       "      select txn_billing.id,
                                txn_billing.billing_number,
                                txn_billing.document_date,
                                txn_billing.shipper_id,
                                txn_billing.bill_to_account_number,
                                txn_billing.bill_to_account_name,
                                txn_billing.bill_to_company_name,
                                txn_billing.email,
                                txn_billing.fax,
                                txn_billing.phone,
                                txn_billing.mobile,
                                txn_billing.company_street_address,
                                txn_billing.company_district,
                                txn_billing.company_city,
                                txn_billing.company_state_province,
                                txn_billing.company_zip_code,
                                txn_billing.company_country,
                                txn_billing.billing_contact_person,
                                txn_billing.billing_street_address,
                                txn_billing.billing_district,
                                txn_billing.billing_city,
                                txn_billing.billing_state_province,
                                txn_billing.billing_zip_code,
                                txn_billing.billing_country,
                                txn_billing.total_amount,
                                txn_billing.payment_due_date,
                                txn_billing.remarks,
                                txn_billing.created_date,
                                txn_billing.status,
                                txn_billing.reason,
                                txn_billing.subtotal,
                                txn_billing.vat,
                                txn_billing.total_vatable_charges,
                                txn_billing.total_non_vatable_charges,
                                concat(cuser.first_name,' ',cuser.last_name) as createdby,
                                txn_billing_waybill.flag,
                                txn_billing_waybill.regular_charges as bwbregularcharges,
                                txn_billing_waybill.other_charges_vatable as bwbotherchargesvatable,
                                txn_billing_waybill.other_charges_non_vatable as bwbotherchargesnonvatable,
                                txn_billing_waybill.vat as bwbvat,
                                txn_billing_waybill.amount as bwbtotalamount,
                                (txn_billing_waybill.regular_charges+txn_billing_waybill.other_charges_vatable) as bwbtotalvatablecharges,
                                (txn_billing_waybill.other_charges_non_vatable+txn_billing_waybill.other_charges_vatable) as bwbtotalothercharges,
                                txn_billing_waybill.waybill_number,
                                origin_destination_port.description as wbdestination,
                                services.description as wbservices,
                                txn_waybill.status as wbstatus,
                                txn_waybill.document_date as wbdate,
                                txn_waybill.shipment_description as wbdescription,
                                txn_waybill.package_declared_value as wbdeclaredvalue,
                                txn_waybill.package_number_of_packages as wbnumofpackages,
                                txn_waybill.package_actual_weight as wbactualweight,
                                txn_waybill.package_cbm as wbcbm,
                                txn_waybill.package_chargeable_weight as wbchargeableweight,
                                txn_waybill.package_freight as wbfreightcharges,
                                txn_waybill.package_valuation as wbvaluation,
                                txn_waybill.vat as wbvat,
                                txn_waybill.total_amount as wbtotalamount,
                                txn_waybill.package_freight_computation as wbfreightcomputation,
                                txn_waybill.package_insurance_rate as wbinsurancecharges,
                                txn_waybill.package_fuel_rate as wbfuelcharges,
                                txn_waybill.package_bunker_rate as wbbunkercharges,
                                txn_waybill.package_minimum_rate as wbminimumcharges,
                                txn_waybill.subtotal as wbsubtotal,
                                txn_waybill.package_vw as wbvw,
                                txn_waybill.fixed_rate_amount as wbfixedcharges,
                                txn_waybill.pull_out_fee as wbpulloutfee,
                                txn_waybill.oda_charges as wbodacharges,
                                txn_waybill.total_handling_charges as wbhandlingcharges,
                                txn_waybill.return_document_fee as wbreturndocfee,
                                txn_waybill.waybill_fee as wbwaybillfee,
                                txn_waybill.security_fee as wbsecurityfee,
                                txn_waybill.doc_stamp_fee as wbdocstampfee,
                                txn_waybill.consignee_account_name,
                                txn_waybill.waybill_type,
                                txn_waybill.project,
                                txn_waybill.mawbl_bl,
                                txn_waybill.lot_floor,
                                txn_waybill.block_unit_district,
                                txn_waybill.phase_parking_slot,
                                txn_waybill.customer_number,
                                txn_waybill.contract_number,
                                txn_waybill.buyer_code,
                                txn_waybill.cost_center_code,
                                txn_waybill.reference,
                                txn_waybill.brand,
                                txn_waybill.express_transaction_type,
                                txn_waybill.cost_center,
                                txn_waybill.consignee_street_address,
                                txn_waybill.shipper_account_name,
                                pouch_size.description as wbpouchsize,
                                mode_of_transport.description as modeoftransport,
                                txn_waybill.total_regular_charges as wbtotalregularcharges,
                                txn_waybill.total_other_charges_vatable as wbtotalotherchargesvatable,
                                txn_waybill.total_other_charges_non_vatable as wbtotalotherchargesnonvatable,
                                (txn_waybill.total_other_charges_non_vatable+txn_waybill.total_other_charges_vatable) as wbtotalothercharges
                               
                         from txn_billing
                         left join txn_billing_waybill on txn_billing_waybill.billing_number=txn_billing.billing_number
                         left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
                         left join origin_destination_port on origin_destination_port.id=txn_waybill.destination_id
                         left join services on services.id=txn_waybill.package_service
                         left join mode_of_transport on mode_of_transport.id=txn_waybill.package_mode_of_transport
                         left join pouch_size on pouch_size.id=txn_waybill.pouch_size_id
                         left join user as cuser on cuser.id=txn_billing.created_by

                   ";

      

        $arr = array();
        if(trim($billingnumber)!=''){
            array_push($arr, "txn_billing.billing_number='".$billingnumber."'");
        }
       
        $condition = implode(" and ", $arr);
        if(count($arr)>0){
            $condition = " where ".$condition; 
        }
        $qry = $qry.$condition.' group by txn_billing.billing_number, txn_billing_waybill.waybill_number order by txn_waybill.waybill_number asc ';

        //echo $qry;

       // echo $form;

        $rs = mysql_query($qry);
        
        if($form==''||$form=='DEFAULT'){
                    $sheet->setCellValue('A1',$blshipper);
                    $sheet->setCellValue('H1',$bldate);
                    $objExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold( true );
                    $objExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold( true );
                    $sheet->setCellValue('A2',$bladdr1);
                    $sheet->setCellValue('A3',$bladdr2);
                    $sheet->setCellValue('A5','Attention: '.$blattn);


                     // HEADER
                     $headercell = array(
                                            'Date',
                                            'BL No.',
                                            'Destination',
                                            'Desc',
                                            'Weight',
                                            'Rate/Currency',
                                            "Consignee's Name",
                                            'Billing Statement No.'
                                            
                                        );
                     $col = 'A';
                     for($i=0;$i<count($headercell);$i++){
                        $sheet->setCellValue($col.'6',$headercell[$i]);
                                cellColor($objExcel,$col.'6','bbdff1');
                        $objExcel->getActiveSheet()->getStyle($col.'6')->getFont()->setBold( true );
                        $col++;
                     }
                     
                     //DETAILS
                     $row = 7;
                     $line = 1;
                     while($obj=mysql_fetch_object($rs)){
                            $bol = trim($obj->mawbl_bl)==''?$obj->waybill_number:trim(strtoupper($obj->mawbl_bl));

                            $mot = str_replace(' ','', $obj->modeoftransport);
                            $wgt = $obj->wbactualweight;
                            $seaflag = 0;
                            $seaflagcheck = strpos(strtoupper($mot), 'SEAFREIGHT');
                            if($seaflagcheck>=0&&trim($seaflagcheck)!=''){
                                $wgt = $obj->wbchargeableweight;
                                $seaflag = 1;
                            }   
                            $waybilltype = strtoupper($obj->waybill_type)=='PARCEL'?'wpx':(strtoupper($obj->express_transaction_type)=='NON-DOCUMENT'?'wpx':'dox');
                            $weightuom = $seaflag==1?'cbm':(strtoupper($obj->waybill_type)=='PARCEL'?'kgs':'gms');

                            if(trim($wgt)==''){
                                $weightuom = '';
                            }

                            $detailcell = array(
                                                     dateFormat($obj->wbdate,'m/d'),
                                                     utfEncode($bol),
                                                     utfEncode($obj->wbdestination),
                                                     utfEncode($waybilltype),
                                                     utfEncode($wgt.' '.$weightuom),
                                                     utfEncode(($obj->wbtotalamount-$obj->wbvat)),
                                                     utfEncode($obj->consignee_account_name),
                                                     utfEncode($billingnumber)




                                                );

                            $col = 'A';
                            for($i=0;$i<count($detailcell);$i++){
                                $sheet->setCellValue($col.$row,$detailcell[$i]);    

                                $col++;
                            }
                           
                            
                            $line++;
                            $row++;

                     }
        }
        else if($form=='HERBALIFE'){
                    $sheet->setCellValue('A1','HERBALIFE INTERNATIONAL PHILIPPINES, INC.');
                    $sheet->setCellValue('H1',$bldate);
                    $objExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold( true );
                    $objExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold( true );
                    $sheet->setCellValue('A2','26th Floor The Trade and Financial Tower,');
                    $sheet->setCellValue('H2','004-448-565-000');
                    $sheet->setCellValue('A3','32nd Street Corner 7th Avenue BGC, Taguig City, 1634');
                    $sheet->setCellValue('A5','Attention: '.$blattn);


                     // HEADER
                     $headercell = array(
                                            'Date',
                                            'BL No.',
                                            'Destination',
                                            'Desc',
                                            'Weight',
                                            'Rate/Currency',
                                            "Consignee's Name",
                                            'Billing Statement No.',
                                            "Remarks"
                                            
                                        );
                     $col = 'A';
                     for($i=0;$i<count($headercell);$i++){
                        $sheet->setCellValue($col.'6',$headercell[$i]);
                                cellColor($objExcel,$col.'6','bbdff1');
                        $objExcel->getActiveSheet()->getStyle($col.'6')->getFont()->setBold( true );
                        $col++;
                     }
                     
                     //DETAILS
                     $row = 7;
                     $line = 1;
                     while($obj=mysql_fetch_object($rs)){
                            $bol = trim($obj->mawbl_bl)==''?$obj->waybill_number:trim(strtoupper($obj->mawbl_bl));

                            $mot = str_replace(' ','', $obj->modeoftransport);
                            $wgt = $obj->wbactualweight;
                            $seaflag = 0;
                            $seaflagcheck = strpos(strtoupper($mot), 'SEAFREIGHT');
                            if($seaflagcheck>=0&&trim($seaflagcheck)!=''){
                                $wgt = $obj->wbchargeableweight;
                                $seaflag = 1;
                            }
                            $waybilltype = strtoupper($obj->waybill_type)=='PARCEL'?'wpx':(strtoupper($obj->express_transaction_type)=='NON-DOCUMENT'?'wpx':'dox');
                            $weightuom = $seaflag==1?'cbm':(strtoupper($obj->waybill_type)=='PARCEL'?'kgs':'gms');

                            if(trim($wgt)==''){
                                $weightuom = '';
                            }

                            $detailcell = array(
                                                     dateFormat($obj->wbdate,'m/d'),
                                                     utfEncode($bol),
                                                     utfEncode($obj->wbdestination),
                                                     utfEncode($waybilltype),
                                                     utfEncode($wgt.' '.$weightuom),
                                                     utfEncode(($obj->wbtotalamount-$obj->wbvat)),
                                                     utfEncode($obj->consignee_account_name),
                                                     utfEncode($billingnumber),
                                                     utfEncode($obj->modeoftransport)




                                                );

                            $col = 'A';
                            for($i=0;$i<count($detailcell);$i++){
                                $sheet->setCellValue($col.$row,$detailcell[$i]);    

                                $col++;
                            }
                           
                            
                            $line++;
                            $row++;

                     }
        }
        else if($form=='ROBINSONS'){
                    $sheet->setCellValue('A1','ROBINSONS LAND CORPORATION');
                    $sheet->setCellValue('H1',$bldate);
                    $objExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold( true );
                    $objExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold( true );
                    $sheet->setCellValue('A2','Residential Building Division Lower Level East Lane');
                    $sheet->setCellValue('H2','000-361-376-000');
                    $sheet->setCellValue('A3','Robinsons Galleria Mall Ortigas Avenue Cor. EDSA');
                    $sheet->setCellValue('A5','Attention: '.$blattn);


                     // HEADER
                     $headercell = array(
                                            'Date',
                                            'BOL#',
                                            'Destination',
                                            'Desc',
                                            'Weight',
                                            'Rate/Currency',
                                            "Shipper Name",
                                            "Contents",
                                            "Project Code",
                                            "Lot Unit",
                                            "Consignee Name",
                                            "Date Received",
                                            "Time Received",
                                            "Received By",
                                            'Billing Statement No.',
                                            "Remarks"
                                            
                                        );
                     $col = 'A';
                     for($i=0;$i<count($headercell);$i++){
                        $sheet->setCellValue($col.'6',$headercell[$i]);
                                cellColor($objExcel,$col.'6','bbdff1');
                        $objExcel->getActiveSheet()->getStyle($col.'6')->getFont()->setBold( true );
                        $col++;
                     }
                     
                     //DETAILS
                     $row = 7;
                     $line = 1;
                     while($obj=mysql_fetch_object($rs)){
                            $bol = trim($obj->mawbl_bl)==''?$obj->waybill_number:trim(strtoupper($obj->mawbl_bl));

                            $mot = str_replace(' ','', $obj->modeoftransport);
                            $wgt = $obj->wbactualweight;
                            $seaflag = 0;
                            $seaflagcheck = strpos(strtoupper($mot), 'SEAFREIGHT');
                            if($seaflagcheck>=0&&trim($seaflagcheck)!=''){
                                $wgt = $obj->wbchargeableweight;
                                $seaflag = 1;
                            }
                            $waybilltype = strtoupper($obj->waybill_type)=='PARCEL'?'wpx':(strtoupper($obj->express_transaction_type)=='NON-DOCUMENT'?'wpx':'dox');
                            $weightuom = $seaflag==1?'cbm':(strtoupper($obj->waybill_type)=='PARCEL'?'kgs':'gms');

                            if(trim($wgt)==''){
                                $weightuom = '';
                            }

                            $detailcell = array(
                                                     dateFormat($obj->wbdate,'m/d'),
                                                     utfEncode($bol),
                                                     utfEncode($obj->wbdestination),
                                                     utfEncode($waybilltype),
                                                     utfEncode($wgt.' '.$weightuom),
                                                     utfEncode(($obj->wbtotalamount-$obj->wbvat)),
                                                     utfEncode($obj->shipper_account_name),
                                                     utfEncode($obj->wbdescription),
                                                     utfEncode($obj->project),
                                                     utfEncode($obj->lot_floor),
                                                     utfEncode($obj->consignee_account_name),
                                                     utfEncode($bldatereceived),
                                                     utfEncode($bltimereceived),
                                                     utfEncode($blreceivedby),
                                                     utfEncode($billingnumber),
                                                     utfEncode($obj->wbstatus)




                                                );

                            $col = 'A';
                            for($i=0;$i<count($detailcell);$i++){
                                $sheet->setCellValue($col.$row,$detailcell[$i]);    

                                $col++;
                            }
                           
                            
                            $line++;
                            $row++;

                     }
        }
        else if($form=='XYNGULAR'){
                    $sheet->setCellValue('A1','XYNGULAR');
                    $sheet->setCellValue('H1',$bldate);
                    $objExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold( true );
                    $objExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold( true );
                    $sheet->setCellValue('A2','G/F Seddco Bldg. Rada St. Legaspi Village,');
                    $sheet->setCellValue('A3','Makati City');
                    $sheet->setCellValue('A5','Attention: '.$blattn);


                     // HEADER
                     $headercell = array(
                                            'Date',
                                            'BL No.',
                                            'Destination',
                                            'Desc',
                                            'Weight',
                                            'Rate/Currency',
                                            "Consignee's Name",
                                            'Date Received',
                                            'Received By',
                                            'Billing Statement No.'
                                            
                                        );
                     $col = 'A';
                     for($i=0;$i<count($headercell);$i++){
                        $sheet->setCellValue($col.'6',$headercell[$i]);
                                cellColor($objExcel,$col.'6','bbdff1');
                        $objExcel->getActiveSheet()->getStyle($col.'6')->getFont()->setBold( true );
                        $col++;
                     }
                     
                     //DETAILS
                     $row = 7;
                     $line = 1;
                     while($obj=mysql_fetch_object($rs)){
                            $bol = trim($obj->mawbl_bl)==''?$obj->waybill_number:trim(strtoupper($obj->mawbl_bl));

                            $mot = str_replace(' ','', $obj->modeoftransport);
                            $wgt = $obj->wbactualweight;
                            $seaflag = 0;
                            $seaflagcheck = strpos(strtoupper($mot), 'SEAFREIGHT');
                            if($seaflagcheck>=0&&trim($seaflagcheck)!=''){
                                $wgt = $obj->wbchargeableweight;
                                $seaflag = 1;
                            }
                            $waybilltype = strtoupper($obj->waybill_type)=='PARCEL'?'wpx':(strtoupper($obj->express_transaction_type)=='NON-DOCUMENT'?'wpx':'dox');
                            $weightuom = $seaflag==1?'cbm':(strtoupper($obj->waybill_type)=='PARCEL'?'kgs':'gms');

                            $detailcell = array(
                                                     dateFormat($obj->wbdate,'m/d'),
                                                     utfEncode($bol),
                                                     utfEncode($obj->wbdestination),
                                                     utfEncode($waybilltype),
                                                     utfEncode($wgt.' '.$weightuom),
                                                     utfEncode(($obj->wbtotalamount-$obj->wbvat)),
                                                     utfEncode($obj->consignee_account_name),
                                                     utfEncode($bldatereceived),
                                                     utfEncode($blreceivedby),
                                                     utfEncode($billingnumber)




                                                );

                            $col = 'A';
                            for($i=0;$i<count($detailcell);$i++){
                                $sheet->setCellValue($col.$row,$detailcell[$i]);    

                                $col++;
                            }
                           
                            
                            $line++;
                            $row++;

                     }
                      $sheet->setCellValue('A'.$row,'TOTAL AMOUNT');
                      $objExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold( true );
                      $sheet->setCellValue('B'.$row,$blsubtotal);

                      $row = $row+1;
                      $sheet->setCellValue('A'.$row,'PLUS 12% VAT');
                      $objExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold( true );
                      $sheet->setCellValue('B'.$row,$blvat);

                      $row = $row+1;
                      $sheet->setCellValue('A'.$row,'TOTAL AMOUNT DUE');
                      $objExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold( true );
                      $sheet->setCellValue('B'.$row,$bltotal);

        }
        else if($form=='ROCKWELL'){
                    $sheet->setCellValue('A1','ROCKWELL PRIMARIES DEVELOPMENT CORPORATION');
                    $sheet->setCellValue('H1',$bldate);
                    $objExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold( true );
                    $objExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold( true );
                    $sheet->setCellValue('A2','8 Rockwell Hidalgo Driver Rockwel Center Makati');
                    $sheet->setCellValue('A3','');
                    $sheet->setCellValue('A5','Attention: '.$blattn);


                     // HEADER
                     $headercell = array(
                                            'Date',
                                            'BL No.',
                                            'Destination',
                                            'Desc',
                                            'Weight',
                                            'Rate/Currency',
                                            "Consignee Company Name",
                                            "Consignee First Name",
                                            "Tracking No.",
                                            "Date Received",
                                            "Received By",
                                            'Billing Statement No.'
                                            
                                        );
                     $col = 'A';
                     for($i=0;$i<count($headercell);$i++){
                        $sheet->setCellValue($col.'6',$headercell[$i]);
                                cellColor($objExcel,$col.'6','bbdff1');
                        $objExcel->getActiveSheet()->getStyle($col.'6')->getFont()->setBold( true );
                        $col++;
                     }
                     
                     //DETAILS
                     $row = 7;
                     $line = 1;
                     while($obj=mysql_fetch_object($rs)){
                            $bol = trim($obj->mawbl_bl)==''?$obj->waybill_number:trim(strtoupper($obj->mawbl_bl));

                            $mot = str_replace(' ','', $obj->modeoftransport);
                            $wgt = $obj->wbactualweight;
                            $seaflag = 0;
                            $seaflagcheck = strpos(strtoupper($mot), 'SEAFREIGHT');
                            if($seaflagcheck>=0&&trim($seaflagcheck)!=''){
                                $wgt = $obj->wbchargeableweight;
                                $seaflag = 1;
                            }
                            $waybilltype = strtoupper($obj->waybill_type)=='PARCEL'?'wpx':(strtoupper($obj->express_transaction_type)=='NON-DOCUMENT'?'wpx':'dox');
                            $weightuom = $seaflag==1?'cbm':(strtoupper($obj->waybill_type)=='PARCEL'?'kgs':'gms');

                            if(trim($wgt)==''){
                                $weightuom = '';
                            }

                            $detailcell = array(
                                                     dateFormat($obj->wbdate,'m/d'),
                                                     utfEncode($bol),
                                                     utfEncode($obj->wbdestination),
                                                     utfEncode($waybilltype),
                                                     utfEncode($wgt.' '.$weightuom),
                                                     utfEncode(($obj->wbtotalamount-$obj->wbvat)),
                                                     utfEncode($obj->consignee_company_name),
                                                     utfEncode($obj->consignee_account_name),
                                                     utfEncode($obj->reference),
                                                     utfEncode($bldatereceived),
                                                     utfEncode($blreceivedby),
                                                     utfEncode($billingnumber)




                                                );

                            $col = 'A';
                            for($i=0;$i<count($detailcell);$i++){
                                $sheet->setCellValue($col.$row,$detailcell[$i]);    

                                $col++;
                            }
                           
                            
                            $line++;
                            $row++;

                     }
                      $sheet->setCellValue('A'.$row,'TOTAL AMOUNT');
                      $objExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold( true );
                      $sheet->setCellValue('B'.$row,$blsubtotal);

                      $row = $row+1;
                      $sheet->setCellValue('A'.$row,'PLUS 12% VAT');
                      $objExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold( true );
                      $sheet->setCellValue('B'.$row,$blvat);

                      $row = $row+1;
                      $sheet->setCellValue('A'.$row,'TOTAL AMOUNT DUE');
                      $objExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold( true );
                      $sheet->setCellValue('B'.$row,$bltotal);

        }
        else if($form=='AMAIA'){
                    $sheet->setCellValue('A1','AMAIA LAND CORP');
                    $sheet->setCellValue('H1',$bldate);
                    $objExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold( true );
                    $objExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold( true );
                    $sheet->setCellValue('A2','2nd Floor Ayala Life FGU Bldg.');
                    $sheet->setCellValue('A3','Alabang Zapote Road, Alabang Muntinlupa');
                    $sheet->setCellValue('A5','Attention: '.$blattn);


                     // HEADER
                     $headercell = array(
                                            'Date',
                                            'Brand',
                                            'Cost Center',
                                            'Cost Center Code',
                                            "Buyer's Code",
                                            'Contract No.',
                                            'Customer No.',
                                            "Buyer's Name Consignee",
                                            "Projects",
                                            "Specific Destination",
                                            "Destination",
                                            "Document Type",
                                            "Shipper's Name",
                                            'BL No.',
                                            'Currency Rate',
                                            'Weight',
                                            'Received By',
                                            'Date Received',
                                            'Billing Statement No.',
                                            'Remarks'
                                            
                                        );
                     $col = 'A';
                     for($i=0;$i<count($headercell);$i++){
                        $sheet->setCellValue($col.'6',$headercell[$i]);
                                cellColor($objExcel,$col.'6','bbdff1');
                        $objExcel->getActiveSheet()->getStyle($col.'6')->getFont()->setBold( true );
                        $col++;
                     }
                     
                     //DETAILS
                     $row = 7;
                     $line = 1;
                     while($obj=mysql_fetch_object($rs)){
                            $bol = trim($obj->mawbl_bl)==''?$obj->waybill_number:trim(strtoupper($obj->mawbl_bl));

                            $mot = str_replace(' ','', $obj->modeoftransport);
                            $wgt = $obj->wbactualweight;
                            $seaflag = 0;
                            $seaflagcheck = strpos(strtoupper($mot), 'SEAFREIGHT');
                            if($seaflagcheck>=0&&trim($seaflagcheck)!=''){
                                $wgt = $obj->wbchargeableweight;
                                $seaflag = 1;
                            }
                           
                            $weightuom = $seaflag==1?'cbm':(strtoupper($obj->waybill_type)=='PARCEL'?'kgs':'gms');

                            if(trim($wgt)==''){
                                $weightuom = '';
                            }

                            $detailcell = array(
                                                     dateFormat($obj->wbdate,'Y-m-d'),
                                                     utfEncode($obj->brand),
                                                     utfEncode($obj->cost_center),
                                                     utfEncode($obj->cost_center_code),
                                                     utfEncode($obj->buyer_code),
                                                     utfEncode($obj->contract_number),
                                                     utfEncode($obj->customer_number),
                                                     utfEncode($obj->consignee_account_name),
                                                     utfEncode($obj->project),
                                                     utfEncode($obj->consignee_street_address),
                                                     utfEncode($obj->wbdestination),
                                                     utfEncode($obj->wbdescription),
                                                     utfEncode($obj->shipper_account_name),
                                                     utfEncode($bol),
                                                     utfEncode(($obj->wbtotalamount-$obj->wbvat)),
                                                     utfEncode($wgt.' '.$weightuom),
                                                     utfEncode($blreceivedby),
                                                     utfEncode($bldatereceived),
                                                     utfEncode($billingnumber),
                                                     utfEncode($obj->wbstatus)




                                                );

                            $col = 'A';
                            for($i=0;$i<count($detailcell);$i++){
                                $sheet->setCellValue($col.$row,$detailcell[$i]);    

                                $col++;
                            }
                           
                            
                            $line++;
                            $row++;

                     }
                      /*$sheet->setCellValue('A'.$row,'TOTAL AMOUNT');
                      $objExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold( true );
                      $sheet->setCellValue('B'.$row,$blsubtotal);

                      $row = $row+1;
                      $sheet->setCellValue('A'.$row,'PLUS 12% VAT');
                      $objExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold( true );
                      $sheet->setCellValue('B'.$row,$blvat);

                      $row = $row+1;
                      $sheet->setCellValue('A'.$row,'TOTAL AMOUNT DUE');
                      $objExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold( true );
                      $sheet->setCellValue('B'.$row,$bltotal);*/

        }
        else if($form=='PBSP'){
                    $sheet->setCellValue('A1','PHILIPPINE BUSINESS FOR SOCIAL PROGRESS');
                    $sheet->setCellValue('H1',$bldate);
                    $objExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold( true );
                    $objExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold( true );
                    $sheet->setCellValue('A2','2/F PSDC Bldg., Magallanes');
                    $sheet->setCellValue('A3','cor Real Street, Intramuros, Manila');
                    $sheet->setCellValue('A5','Attention: '.$blattn);


                     // HEADER
                     $headercell = array(
                                            'Date',
                                            'AWB#',
                                            'Destination',
                                            'Desc',
                                            "Weight",
                                            'QTY of Box/Pouch',
                                            'Rate',
                                            "Consignee Company Name",
                                            "Tracking No.",
                                            "Date Received",
                                            "Time Received",
                                            "Received By",
                                            'Billing Statement No.'
                                            
                                        );
                     $col = 'A';
                     for($i=0;$i<count($headercell);$i++){
                        $sheet->setCellValue($col.'6',$headercell[$i]);
                                cellColor($objExcel,$col.'6','bbdff1');
                        $objExcel->getActiveSheet()->getStyle($col.'6')->getFont()->setBold( true );
                        $col++;
                     }
                     
                     //DETAILS
                     $row = 7;
                     $line = 1;
                     while($obj=mysql_fetch_object($rs)){
                            $bol = trim($obj->mawbl_bl)==''?$obj->waybill_number:trim(strtoupper($obj->mawbl_bl));

                            $mot = str_replace(' ','', $obj->modeoftransport);
                            $wgt = $obj->wbactualweight;
                            $seaflag = 0;
                            $seaflagcheck = strpos(strtoupper($mot), 'SEAFREIGHT');
                            if($seaflagcheck>=0&&trim($seaflagcheck)!=''){
                                $wgt = $obj->wbchargeableweight;
                                $seaflag = 1;
                            }
                            $waybilltype = strtoupper($obj->waybill_type)=='PARCEL'?'wpx':(strtoupper($obj->express_transaction_type)=='NON-DOCUMENT'?'wpx':'dox');
                            $weightuom = $seaflag==1?'cbm':(strtoupper($obj->waybill_type)=='PARCEL'?'kgs':'gms');

                            if(trim($wgt)==''){
                                $weightuom = '';
                            }

                            $detailcell = array(
                                                     dateFormat($obj->wbdate,'Y-m-d'),
                                                     utfEncode($bol),
                                                     utfEncode($obj->wbdestination),
                                                     utfEncode($waybilltype),
                                                     utfEncode($wgt.' ',$weightuom),
                                                     utfEncode($obj->wbpouchsize),
                                                     utfEncode(($obj->wbtotalamount-$obj->wbvat)),
                                                     utfEncode($obj->consignee_account_name),
                                                     utfEncode($obj->reference),
                                                     utfEncode($bldatereceived),
                                                     utfEncode($bltimereceived),
                                                     utfEncode($blreceivedby),
                                                     utfEncode($billingnumber)



                                                );

                            $col = 'A';
                            for($i=0;$i<count($detailcell);$i++){
                                $sheet->setCellValue($col.$row,$detailcell[$i]);    

                                $col++;
                            }
                           
                            
                            $line++;
                            $row++;

                     }
                      $sheet->setCellValue('A'.$row,'TOTAL AMOUNT DUE');
                      $objExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold( true );
                      $sheet->setCellValue('B'.$row,$bltotal);

                      /*$row = $row+1;
                      $sheet->setCellValue('A'.$row,'PLUS 12% VAT');
                      $objExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold( true );
                      $sheet->setCellValue('B'.$row,$blvat);

                      $row = $row+1;
                      $sheet->setCellValue('A'.$row,'TOTAL AMOUNT DUE');
                      $objExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold( true );
                      $sheet->setCellValue('B'.$row,$bltotal);*/

        }
        else if($form=='BROTHER'){
                    $sheet->setCellValue('A1','BROTHER INTERNATIONAL PHILS. CORPORATION');
                    $sheet->setCellValue('H1',$bldate);
                    $objExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold( true );
                    $objExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold( true );
                    $sheet->setCellValue('A2','6/F Marajo Bldg. 312-26th West cor');
                    $sheet->setCellValue('A3','4th Ave., Global City Taguig');
                    $sheet->setCellValue('A5','Attention: '.$blattn);


                     // HEADER
                     $headercell = array(
                                            'Date',
                                            'BL No.',
                                            'Destination',
                                            "Destination's Category",
                                            "Desc",
                                            'Qty of Box/Pouch',
                                            "Weight",
                                            "Contents",
                                            "Consignee's Name",
                                            "Received By",
                                            "Date Rcvd",
                                            "Billing Statement No.",
                                            'Rate',
                                            "DV 1%",
                                            "Total",
                                            "Vat",
                                            "Net Amount"
                                            
                                        );
                     $col = 'A';
                     for($i=0;$i<count($headercell);$i++){
                        $sheet->setCellValue($col.'6',$headercell[$i]);
                                cellColor($objExcel,$col.'6','bbdff1');
                        $objExcel->getActiveSheet()->getStyle($col.'6')->getFont()->setBold( true );
                        $col++;
                     }
                     
                     //DETAILS
                     $row = 7;
                     $line = 1;
                     while($obj=mysql_fetch_object($rs)){
                            $bol = trim($obj->mawbl_bl)==''?$obj->waybill_number:trim(strtoupper($obj->mawbl_bl));

                            $mot = str_replace(' ','', $obj->modeoftransport);
                            $wgt = $obj->wbactualweight;
                            $seaflag = 0;
                            $seaflagcheck = strpos(strtoupper($mot), 'SEAFREIGHT');
                            if($seaflagcheck>=0&&trim($seaflagcheck)!=''){
                                $wgt = $obj->wbchargeableweight;
                                $seaflag = 1;
                            }
                            
                            $weightuom = $seaflag==1?'cbm':(strtoupper($obj->waybill_type)=='PARCEL'?'kgs':'gms');

                            if(trim($wgt)==''){
                                $weightuom = '';
                            }
                            $waybilltype = strtoupper($obj->waybill_type)=='PARCEL'?'wpx':(strtoupper($obj->express_transaction_type)=='NON-DOCUMENT'?'wpx':'dox');
                            $qtyuom = strtoupper($obj->waybill_type)=='PARCEL'?'bx':'pch';


                            $detailcell = array(
                                                     dateFormat($obj->wbdate,'m-d'),
                                                     utfEncode($bol),
                                                     utfEncode($obj->wbdestination),
                                                     utfEncode(''),
                                                     utfEncode($waybilltype),
                                                     utfEncode($obj->wbnumofpackages.' '.$qtyuom),
                                                     utfEncode($wgt.' '.$weightuom),
                                                     utfEncode($obj->wbdescription),
                                                     utfEncode($obj->consignee_account_name),
                                                     utfEncode($blreceivedby),
                                                     utfEncode($bldatereceived),
                                                     utfEncode($billingnumber),
                                                     utfEncode((($obj->wbtotalregularcharges+$obj->wbtotalotherchargesvatable+$obj->wbtotalotherchargesnonvatable)-$obj->wbdeclaredvalue)),
                                                     utfEncode($obj->wbdeclaredvalue),
                                                     utfEncode($obj->wbtotalregularcharges+$obj->wbtotalotherchargesvatable+$obj->wbtotalotherchargesnonvatable),
                                                     utfEncode($obj->wbvat),
                                                     utfEncode(($obj->wbtotalamount-$obj->wbvat))




                                                );

                            $col = 'A';
                            for($i=0;$i<count($detailcell);$i++){
                                $sheet->setCellValue($col.$row,$detailcell[$i]);    

                                $col++;
                            }
                           
                            
                            $line++;
                            $row++;

                     }
                      /*$sheet->setCellValue('A'.$row,'TOTAL AMOUNT');
                      $objExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold( true );
                      $sheet->setCellValue('B'.$row,$blsubtotal);

                      $row = $row+1;
                      $sheet->setCellValue('A'.$row,'PLUS 12% VAT');
                      $objExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold( true );
                      $sheet->setCellValue('B'.$row,$blvat);

                      $row = $row+1;
                      $sheet->setCellValue('A'.$row,'TOTAL AMOUNT DUE');
                      $objExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold( true );
                      $sheet->setCellValue('B'.$row,$bltotal);*/

        }
        else if($form=='EST'){
                    $sheet->setCellValue('A1','EARTH & STYLE CORPORATION');
                    $sheet->setCellValue('H1',$bldate);
                    $objExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold( true );
                    $objExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold( true );
                    $sheet->setCellValue('A2','39th Floor Joy Nostalg Center, #17 ADB Ave.');
                    $sheet->setCellValue('H2','206-114-531-000');
                    $sheet->setCellValue('A3','Ortigas Center San Antonio, Pasig City');
                    $sheet->setCellValue('A5','Attention: '.$blattn);


                     // HEADER
                     $headercell = array(
                                            'Date',
                                            'BOL #',
                                            'Destination',
                                            'Desc',
                                            'Weight',
                                            'Total Amount',
                                            "Shipper's Name",
                                            "Content",
                                            "Dept.",
                                            "Consignee Name",
                                            "Date Received",
                                            "Time Received",
                                            "Received By",
                                            'Billing Statement No.'
                                            
                                        );
                     $col = 'A';
                     for($i=0;$i<count($headercell);$i++){
                        $sheet->setCellValue($col.'6',$headercell[$i]);
                                cellColor($objExcel,$col.'6','bbdff1');
                        $objExcel->getActiveSheet()->getStyle($col.'6')->getFont()->setBold( true );
                        $col++;
                     }
                     
                     //DETAILS
                     $row = 7;
                     $line = 1;
                     while($obj=mysql_fetch_object($rs)){
                            $bol = trim($obj->mawbl_bl)==''?$obj->waybill_number:trim(strtoupper($obj->mawbl_bl));

                            $mot = str_replace(' ','', $obj->modeoftransport);
                            $wgt = $obj->wbactualweight;
                            $seaflag = 0;
                            $seaflagcheck = strpos(strtoupper($mot), 'SEAFREIGHT');
                            if($seaflagcheck>=0&&trim($seaflagcheck)!=''){
                                $wgt = $obj->wbchargeableweight;
                                $seaflag = 1;
                            }
                            $waybilltype = strtoupper($obj->waybill_type)=='PARCEL'?'wpx':(strtoupper($obj->express_transaction_type)=='NON-DOCUMENT'?'wpx':'dox');
                            $weightuom = $seaflag==1?'cbm':(strtoupper($obj->waybill_type)=='PARCEL'?'kgs':'gms');

                            if(trim($wgt)==''){
                                $weightuom = '';
                            }

                            $detailcell = array(
                                                     dateFormat($obj->wbdate,'m/d'),
                                                     utfEncode($bol),
                                                     utfEncode($obj->wbdestination),
                                                     utfEncode($waybilltype),
                                                     utfEncode($wgt.' '.$weightuom),
                                                     utfEncode(($obj->wbtotalamount-$obj->wbvat)),
                                                     utfEncode($obj->shipper_account_name),
                                                     utfEncode($obj->wbdescription),
                                                     utfEncode($blattn),
                                                     utfEncode($obj->consignee_account_name),
                                                     utfEncode($bldatereceived),
                                                     utfEncode($bltimereceived),
                                                     utfEncode($blreceivedby),
                                                     utfEncode($billingnumber)




                                                );

                            $col = 'A';
                            for($i=0;$i<count($detailcell);$i++){
                                $sheet->setCellValue($col.$row,$detailcell[$i]);    

                                $col++;
                            }
                           
                            
                            $line++;
                            $row++;

                     }

                     $sheet->setCellValue('A'.$row,'TOTAL AMOUNT');
                      $objExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold( true );
                      $sheet->setCellValue('B'.$row,$blsubtotal);

                      $row = $row+1;
                      $sheet->setCellValue('A'.$row,'PLUS 12% VAT');
                      $objExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold( true );
                      $sheet->setCellValue('B'.$row,$blvat);

                      $row = $row+1;
                      $sheet->setCellValue('A'.$row,'TOTAL AMOUNT DUE');
                      $objExcel->getActiveSheet()->getStyle('A'.$row)->getFont()->setBold( true );
                      $sheet->setCellValue('B'.$row,$bltotal);
        }

         

          header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          header('Content-Disposition: attachment; filename="transmittal-summary-'.$billingnumber.'-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');

?>