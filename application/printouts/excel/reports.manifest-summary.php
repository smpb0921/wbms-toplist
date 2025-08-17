<?php



    include("../../../config/connection.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");
    include("../../../resources/PHPExcel-1.8/classes/PHPExcel.php");


    $mftstatus = isset($_GET['mftstatus'])?escapeString(strtoupper($_GET['mftstatus'])):'';
    $mftmodeoftransport = isset($_GET['mftmodeoftransport'])?escapeString(strtoupper($_GET['mftmodeoftransport'])):'';
    $mftagent = isset($_GET['mftagent'])?escapeString(strtoupper($_GET['mftagent'])):'';
    $mftcarrier = isset($_GET['mftcarrier'])?escapeString(strtoupper($_GET['mftcarrier'])):'';
    $mftvehicletype = isset($_GET['mftvehicletype'])?escapeString(strtoupper($_GET['mftvehicletype'])):'';
    $mftdocdatefrom = isset($_GET['mftdocdatefrom'])?escapeString($_GET['mftdocdatefrom']):'';
    $mftdocdateto = isset($_GET['mftdocdateto'])?escapeString($_GET['mftdocdateto']):'';
    $mftcreatedfrom = isset($_GET['mftcreatedfrom'])?escapeString($_GET['mftcreatedfrom']):'';
    $mftcreatedto = isset($_GET['mftcreatedto'])?escapeString($_GET['mftcreatedto']):'';

    /*$wbstatus = isset($_GET['wbstatus'])?escapeString(strtoupper($_GET['wbstatus'])):'';
    $wbmawbl = isset($_GET['wbmawbl'])?escapeString(strtoupper($_GET['wbmawbl'])):'';
    $wborigin = isset($_GET['wborigin'])?escapeString(strtoupper($_GET['wborigin'])):'';
    $wbdestination = isset($_GET['wbdestination'])?escapeString(strtoupper($_GET['wbdestination'])):'';
    $wbshipper = isset($_GET['wbshipper'])?escapeString(strtoupper($_GET['wbshipper'])):'';
    $wbconsignee = isset($_GET['wbconsignee'])?escapeString(strtoupper($_GET['wbconsignee'])):'';
    $wbdocdatefrom = isset($_GET['wbdocdatefrom'])?escapeString($_GET['wbdocdatefrom']):'';
    $wbdocdateto = isset($_GET['wbdocdateto'])?escapeString($_GET['wbdocdateto']):'';
    $wbpudatefrom = isset($_GET['wbpudatefrom'])?escapeString($_GET['wbpudatefrom']):'';
    $wbpudateto = isset($_GET['wbpudateto'])?escapeString($_GET['wbpudateto']):'';
    $wbcreateddatefrom = isset($_GET['wbcreateddatefrom'])?escapeString($_GET['wbcreateddatefrom']):'';
    $wbcreateddateto = isset($_GET['wbcreateddateto'])?escapeString($_GET['wbcreateddateto']):'';*/

   

    if($mftdocdatefrom!=''){
        $mftdocdatefrom = date('Y-m-d', strtotime($mftdocdatefrom));
    }
    if($mftdocdateto!=''){
        $mftdocdateto = date('Y-m-d', strtotime($mftdocdateto));
    }

    if($mftcreatedfrom!=''){
        $mftcreatedfrom = date('Y-m-d', strtotime($mftcreatedfrom));
    }
    if($mftcreatedto!=''){
        $mftcreatedto = date('Y-m-d', strtotime($mftcreatedto));
    }

    


    /*if($wbdocdatefrom!=''){
        $wbdocdatefrom = date('Y-m-d', strtotime($wbdocdatefrom));
    }
    if($wbdocdateto!=''){
        $wbdocdateto = date('Y-m-d', strtotime($wbdocdateto));
    }

    if($wbpudatefrom!=''){
        $wbpudatefrom = date('Y-m-d', strtotime($wbpudatefrom));
    }
    if($wbpudateto!=''){
        $wbpudateto = date('Y-m-d', strtotime($wbpudateto));
    }

    if($wbcreateddatefrom!=''){
        $wbcreateddatefrom = date('Y-m-d', strtotime($wbcreateddatefrom));
    }
    if($wbcreateddateto!=''){
        $wbcreateddateto = date('Y-m-d', strtotime($wbcreateddateto));
    }*/



    function cellColor($objPHPExcel,$cells,$color){
        $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                 'rgb' => $color
            )
        ));
    }

     $objExcel = new PHPExcel();
     $sheet = $objExcel->getActiveSheet();

     $sheet->setTitle('Manifest Summary');

     $qry =       "     select  txn_manifest.manifest_number,
                                txn_manifest.created_date as manifest_created_date,
                                txn_manifest.created_by as manifest_created_by,
                                txn_manifest.document_date as manifest_document_date,
                                concat(mftuser.first_name,' ',mftuser.last_name) as manifestcreatedby,
                                txn_waybill.id, 
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
                                txn_waybill.invoice_number,
                                txn_waybill.shipper_id,
                                txn_waybill.shipper_account_number,
                                txn_waybill.shipper_account_name,
                                txn_waybill.shipper_tel_number,
                                txn_waybill.shipper_company_name,
                                txn_waybill.shipper_street_address,
                                txn_waybill.shipper_district,
                                txn_waybill.shipper_city,
                                txn_waybill.shipper_contact_person,
                                txn_waybill.shipper_state_province,
                                txn_waybill.shipper_zip_code,
                                txn_waybill.shipper_country,
                                txn_waybill.consignee_id,
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
                                txn_waybill.mawbl_bl,
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
                                txn_waybill.amount_for_collection,
                                txn_waybill.last_status_update_remarks,
                                origintbl.description as origin,
                                destinationtbl.description as destination,
                                services.description as servicedesc,
                                mode_of_transport.description as modeoftransport,
                                delivery_instruction.description as deliveryinstruction,
                                destination_route.description as destinationroute,
                                accompanying_documents.description as document,
                                transport_charges.description as transportcharges,
                                carrier.description as carrier,
                                sum(txn_waybill_other_charges.amount) as othercharges,
                                group_concat(distinct handling_instruction.description separator ', ') as handlinginstruction,
                                group_concat(concat(packdim.quantity,' - ',packdim.length,'x',packdim.width,'x',packdim.height) separator ', ') as packdimensions,
                                group_concat(concat('(',packdim.quantity,')',' - ',packdim.length) separator ', ') as packdimensionslen,
                                group_concat(packdim.width separator ', ') as packdimensionswid,
                                group_concat(packdim.height separator ', ') as packdimensionshgt,
                                txn_waybill.received_date,
                                txn_waybill.received_by,
                                txn_waybill.reference,
                                case 
                                        when txn_waybill.oda_flag=1 then 'YES'
                                        else 'NO'
                                end as odaflag,
                                concat(user.first_name,' ',user.last_name) as bolcreatedby,
                                location.description as location,
                                user_group.description as usergroup
                         from txn_manifest_waybill
                         left join txn_manifest on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number
                         left join txn_waybill on txn_waybill.waybill_number=txn_manifest_waybill.waybill_number
                         left join txn_waybill_package_dimension as packdim on packdim.waybill_number=txn_waybill.waybill_number
                         left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id 
                         left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id 
                         left join services on services.id=txn_waybill.package_service
                         left join mode_of_transport on mode_of_transport.id=txn_waybill.package_mode_of_transport
                         left join delivery_instruction on delivery_instruction.id=txn_waybill.package_delivery_instruction
                         left join destination_route on destination_route.id=txn_waybill.destination_route_id
                         left join accompanying_documents on accompanying_documents.id=txn_waybill.package_document
                         left join transport_charges on transport_charges.id=txn_waybill.package_transport_charges
                         left join carrier on carrier.id=txn_waybill.carrier_id
                         left join txn_waybill_handling_instruction on txn_waybill_handling_instruction.waybill_number=txn_waybill.waybill_number
                         left join handling_instruction on handling_instruction.id=txn_waybill_handling_instruction.handling_instruction_id
                         left join txn_waybill_other_charges on txn_waybill_other_charges.waybill_number=txn_waybill.waybill_number 
                         left  join user on user.id=txn_waybill.created_by
                         left join user as mftuser on mftuser.id=txn_manifest.created_by
                         left join location on location.id=user.location_id
                         left join user_group on user_group.id=user.user_group_id
                        

                   ";

                   /*
                                group_concat(txn_manifest_waybill.waybill_number) as waybills,
                                sum(txn_waybill.package_actual_weight) as totalactualweight,
                                sum(txn_waybill.package_cbm) as totalcbm,
                                sum(txn_waybill.package_number_of_packages) as totalnumberofpackages



                        left join txn_manifest_waybill on txn_manifest_waybill.manifest_number=txn_manifest.manifest_number
                        left join txn_waybill on txn_waybill.waybill_number=txn_manifest_waybill.waybill_number
                   */

        $arr = array();
        if($mftstatus!=''&&$mftstatus!='NULL'&&$mftstatus!='UNDEFINED'){
            array_push($arr, "txn_manifest.status='".$status."'");
        }
        if($mftmodeoftransport!=''&&$mftmodeoftransport!='NULL'&&$mftmodeoftransport!='UNDEFINED'){
            array_push($arr, "txn_manifest.load_plan_number='".$loadplan."'");
        }
        if($mftagent!=''&&$mftagent!='NULL'&&$mftagent!='UNDEFINED'){
            array_push($arr, "txn_manifest.load_plan_number='".$loadplan."'");
        }
        if($mftcarrier!=''&&$mftcarrier!='NULL'&&$mftcarrier!='UNDEFINED'){
            array_push($arr, "txn_manifest.load_plan_number='".$loadplan."'");
        }
        if($mftvehicletype!=''&&$mftvehicletype!='NULL'&&$mftvehicletype!='UNDEFINED'){
            array_push($arr, "txn_manifest.load_plan_number='".$loadplan."'");
        }
       

        if($mftdocdatefrom!=''&&$mftdocdateto!=''){
            array_push($arr,"date(txn_manifest.document_date) >= '$mftdocdatefrom' and date(txn_manifest.document_date) <= '$mftdocdateto'");
        }
        else if($mftdocdatefrom==''&&$mftdocdateto!=''){
            array_push($arr,"date(txn_manifest.document_date) <= '$mftdocdateto'");
        }
        else if($mftdocdatefrom!=''&&$mftdocdateto==''){
             array_push($arr,"date(txn_manifest.document_date) >= '$mftdocdatefrom'");
        }

        if($mftcreatedfrom!=''&&$mftcreatedto!=''){
            array_push($arr,"date(txn_manifest.created_date) >= '$mftcreatedfrom' and date(txn_manifest.created_date) <= '$mftcreatedto'");
        }
        else if($mftcreatedfrom==''&&$mftcreatedto!=''){
            array_push($arr,"date(txn_manifest.created_date) <= '$mftcreatedto'");
        }
        else if($mftcreatedfrom!=''&&$mftcreatedto==''){
             array_push($arr,"date(txn_manifest.created_date) >= '$mftcreatedfrom'");
        }


        $condition = implode(" and ", $arr);
        if(count($arr)>0){
            $condition = " where ".$condition; 
        }
        $qry = $qry.$condition." group by txn_manifest_waybill.manifest_number, txn_manifest_waybill.waybill_number";

       //echo $qry;
        $rs = mysql_query($qry);



         // HEADER
         $headercell = array(
                                    'MANIFEST NUMBER',
                                    'MFT DOCUMENT DATE',
                                    'MFT CREATED DATE',
                                    'MFT CREATED BY',
                                    'ITEM ID',
                                    'SHIPMENT MODE',
                                    'TRACKING NUMBER',
                                    'PUR',
                                    'ORIGIN',
                                    'DESTINATION',
                                    'TRANSACTION DATE',
                                    'ODZ',
                                    'SHIPPER ACCOUNT NO.',
                                    'SHIPPER',
                                    'STREET/BLDG (SHIPPER)',
                                    'BARANGAY (SHIPPER)',
                                    'CITY/MUNICIPALITY (SHIPPER)',
                                    'CONTACT NUMBER (SHIPPER)',
                                    'SEND SMS (SHIPPER)',
                                    'MOBILE NUMBER (SHIPPER)',
                                    'PAY MODE',
                                    'PRODUCT LINE',
                                    'SERVICE MODE',
                                    'COD AMOUNT',
                                    'CONSIGNEE ACCOUNT NO.',
                                    'CONSIGNEE',
                                    'STREET/BLDG (CONSIGNEE)',
                                    'BARANGAY (CONSIGNEE)',
                                    'CITY/MUNICIPALITY (CONSIGNEE)',
                                    'CONTACT NUMBER (CONSIGNEE)',
                                    'SEND SMS (CONSIGNEE)',
                                    'MOBILE NUMBER (CONSIGNEE)',
                                    'SPECIAL INSTRUCTIONS',
                                    'QUANTITY',
                                    'PKG',
                                    'ACT WT (KGS)',
                                    'LENGTH (CM)',
                                    'WIDTH (CM)',
                                    'HEIGHT (CM)',
                                    'VOL WT',
                                    'CBM',
                                    'CHARGEABLE WT',
                                    'DECLARED VALUE',
                                    'DESCRIPTION',
                                    'COMMODITY',
                                    'FOR CRATING',
                                    'ATTACHMENT NAME 1',
                                    'REFERENCE NO. 1',
                                    'ATTACHMENT NAME 2',
                                    'REFERENCE NO. 2',
                                    'ATTACHMENT NAME 3',
                                    'REFERENCE NO. 3',
                                    'ATTACHMENT NAME 4',
                                    'REFERENCE NO. 4',
                                    'OR NUMBER',
                                    'OR AMOUNT',
                                    'DELIVERY REQUIREMENTS',
                                    'CONSIGNEE EMAIL ADD',
                                    'BILLING REFERENCE',
                                    'DISPATCHER NAME',
                                    'LOCATION',
                                    'USER GROUP',
                                    'TIME CREATED',
                                    'TIME INTERVAL'

                            );
         $col = 'A';
         for($i=0;$i<count($headercell);$i++){
            $sheet->setCellValue($col.'2',$headercell[$i]);
                    cellColor($objExcel,$col.'2','bbdff1');
            $col++;
         }
         
         //DETAILS
         $row = 3;
         $line = 1;
         $rowuser = '';
         $prevtime = '';
         $timediff = '';
         while($obj=mysql_fetch_object($rs)){

                    
                    if($prevtime==''||$rowuser!=$obj->created_by){
                        $timediff = '';
                        $prevtime = $obj->created_date;
                        $rowuser = $obj->created_by;
                    }
                    else{
                        
                        $then = new DateTime($prevtime);
                        $nexttime = new DateTime($obj->created_date);
                        $sinceThen = $then->diff($nexttime);

                        $prevtime = $obj->created_date;
                        $rowuser = $obj->created_by;

                        $timediff = $sinceThen->d.":".$sinceThen->h.":".$sinceThen->i.":".$sinceThen->s;


                    }

                    $zerorated = $obj->zero_rated_flag==1?'YES':'NO';

                  
                    

                    $detailcell = array(
                                            utfEncode($obj->manifest_number),
                                            dateFormat($obj->manifest_document_date,'m/d/Y'),
                                            dateFormat($obj->manifest_created_date,'m/d/Y'),
                                            utfEncode($obj->manifestcreatedby),
                                            '',//ITEM ID
                                            utfEncode($obj->modeoftransport),//SHIPMENT MODE
                                            utfEncode($obj->waybill_number),//TRACKING NUMBER
                                            '',//PUR
                                            utfEncode($obj->origin),//ORIGIN
                                            utfEncode($obj->destination),//DESTINATION
                                            dateFormat($obj->document_date,'m/d/Y'),//TRANSACTION DATE
                                            utfEncode($obj->odaflag),
                                            utfEncode($obj->shipper_account_number),//SHIPPER ACCOUNT NO.
                                            utfEncode($obj->shipper_account_name),//SHIPPER
                                            utfEncode($obj->shipper_street_address),//STREET/BLDG (SHIPPER)
                                            utfEncode($obj->shipper_district),//BARANGAY (SHIPPER)
                                            utfEncode($obj->shipper_city),//CITY/MUNICIPALITY (SHIPPER)
                                            utfEncode($obj->shipper_tel_number),//CONTACT NUMBER (SHIPPER)
                                            '',//SEND SMS (SHIPPER)
                                            utfEncode($obj->shipper_tel_number),//MOBILE NUMBER (SHIPPER)
                                            utfEncode($obj->package_pay_mode),//PAY MODE
                                            '',//PRODUCT LINE
                                            utfEncode($obj->servicedesc),//SERVICE MODE
                                            '',//COD AMOUNT
                                            utfEncode($obj->consignee_account_number),//consignee ACCOUNT NO.
                                            utfEncode($obj->consignee_account_name),//consignee
                                            utfEncode($obj->consignee_street_address),//STREET/BLDG (consignee)
                                            utfEncode($obj->consignee_district),//BARANGAY (consignee)
                                            utfEncode($obj->consignee_city),//CITY/MUNICIPALITY (consignee)
                                            utfEncode($obj->consignee_tel_number),//CONTACT NUMBER (consignee)
                                            '',//SEND SMS (consignee)
                                            utfEncode($obj->consignee_tel_number),//MOBILE NUMBER (consignee)
                                            '',//SPECIAL INSTRUCTIONS
                                            utfEncode($obj->package_number_of_packages),//QUANTITY
                                            '',//PKG
                                            utfEncode($obj->package_actual_weight),//ACT WT (KGS)
                                            utfEncode($obj->packdimensionslen),//LENGTH (CM)
                                            utfEncode($obj->packdimensionswid),//WIDTH (CM)
                                            utfEncode($obj->packdimensionshgt),//HEIGHT (CM)
                                            utfEncode($obj->package_vw),//VOL WT
                                            utfEncode($obj->package_cbm),//CBM
                                            utfEncode($obj->package_chargeable_weight),//CHARGEABLE WT
                                            utfEncode($obj->package_declared_value),//DECLARED VALUE
                                            utfEncode($obj->shipment_description),//DESCRIPTION
                                            utfEncode($obj->handlinginstruction),//COMMODITY
                                            '',//FOR CRATING
                                            '',//ATTACHMENT NAME 1
                                            '',//REFERENCE NO. 1
                                            '',//ATTACHMENT NAME 2
                                            '',//REFERENCE NO. 2
                                            '',//ATTACHMENT NAME 3
                                            '',//REFERENCE NO. 3
                                            '',//ATTACHMENT NAME 4
                                            '',//REFERENCE NO. 4
                                            '',//OR NUMBER
                                            '',//OR AMOUNT
                                            '',//DELIVERY REQUIREMENTS
                                            '',//CONSIGNEE EMAIL ADD
                                            '',//BILLING REFERENCE
                                            utfEncode($obj->bolcreatedby),//DISPATCHER NAME
                                            utfEncode($obj->location),//LOCATION
                                            utfEncode($obj->usergroup),//USER GROUP
                                            dateFormat($obj->created_date,'m/d/Y H:i:s'),
                                            $timediff
                                           
                                      

                                        );

                    $col = 'A';
                    for($i=0;$i<count($detailcell);$i++){
                        $sheet->setCellValue($col.$row,$detailcell[$i]);
                        $col++;
                    }
                    
                    
                    $line++;
                    $row++;
             }

          header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          header('Content-Disposition: attachment; filename="manifest-summary-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');

?>