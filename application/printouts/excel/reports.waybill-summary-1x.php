<?php



    include("../../../config/connection.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");
    include("../../../resources/PHPExcel-1.8/classes/PHPExcel.php");


    $status = isset($_GET['status'])?escapeString(strtoupper($_GET['status'])):'';
    $origin = isset($_GET['origin'])?escapeString(strtoupper($_GET['origin'])):'';
    $destination = isset($_GET['destination'])?escapeString($_GET['destination']):'';
    $destinationroute = isset($_GET['destinationroute'])?escapeString(strtoupper($_GET['destinationroute'])):'';
    $shipper = isset($_GET['shipper'])?escapeString(strtoupper($_GET['shipper'])):'';
    $consignee = isset($_GET['consignee'])?escapeString(strtoupper($_GET['consignee'])):'';
    $service = isset($_GET['service'])?escapeString(strtoupper($_GET['service'])):'';
    $modeoftransport = isset($_GET['modeoftransport'])?escapeString(strtoupper($_GET['modeoftransport'])):'';
    $paymode = isset($_GET['paymode'])?escapeString($_GET['paymode']):'';
    $docdatefrom = isset($_GET['docdatefrom'])?escapeString($_GET['docdatefrom']):'';
    $docdateto = isset($_GET['docdateto'])?escapeString($_GET['docdateto']):'';
    $deldatefrom = isset($_GET['deldatefrom'])?escapeString($_GET['deldatefrom']):'';
    $deldateto = isset($_GET['deldateto'])?escapeString($_GET['deldateto']):'';
    $pudatefrom = isset($_GET['pudatefrom'])?escapeString($_GET['pudatefrom']):'';
    $pudateto = isset($_GET['pudateto'])?escapeString($_GET['pudateto']):'';
    $format = isset($_GET['format'])?escapeString(strtoupper($_GET['format'])):'';

    if($docdatefrom!=''){
        $docdatefrom = date('Y-m-d', strtotime($docdatefrom));
    }
    if($docdateto!=''){
        $docdateto = date('Y-m-d', strtotime($docdateto));
    }

    if($deldatefrom!=''){
        $deldatefrom = date('Y-m-d', strtotime($deldatefrom));
    }
    if($deldateto!=''){
        $deldateto = date('Y-m-d', strtotime($deldateto));
    }

    if($pudateto!=''){
        $pudateto = date('Y-m-d', strtotime($pudateto));
    }
    if($pudatefrom!=''){
        $pudatefrom = date('Y-m-d', strtotime($pudatefrom));
    }



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

     $sheet->setTitle('Waybill Summary');

     $qry =       "      select txn_waybill.id, 
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
                                txn_waybill.manifest_number,
                                txn_waybill.invoice_number,
                                txn_waybill.shipper_id,
                                txn_waybill.shipper_account_number,
                                txn_waybill.shipper_account_name,
                                txn_waybill.shipper_tel_number,
                                txn_waybill.shipper_company_name,
                                txn_waybill.shipper_street_address,
                                txn_waybill.shipper_district,
                                txn_waybill.shipper_city,
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
                                group_concat(distinct accompanying_documents.description separator ', ') as document,
                                group_concat(distinct handling_instruction.description separator ', ') as handlinginstruction,
                                transport_charges.description as transportcharges,
                                carrier.description as carrier,
                                sum(txn_waybill_other_charges.amount) as othercharges,
                                group_concat(concat(packdim.quantity,' - ',packdim.length,'x',packdim.width,'x',packdim.height) separator ', ') as packdimensions,
                                txn_waybill.received_date,
                                txn_waybill.received_by,
                                txn_waybill.bill_reference,
                                shipper_contact.send_sms_flag as shippersmsflag,
                                shipper_contact.phone_number as shippertel,
                                shipper_contact.mobile_number as shippermobile,
                                shipper_contact.email_address as shipperemail,
                                consignee_contact.send_sms_flag as consigneesmsflag,
                                consignee_contact.phone_number as consigneetel,
                                consignee_contact.mobile_number as consigneemobile,
                                consignee_contact.email_address as consigneeemail

                         from txn_waybill
                         left join shipper_contact on shipper_contact.shipper_id=txn_waybill.shipper_id and
                                                      shipper_contact.default_flag=1
                         left join consignee_contact on consignee_contact.consignee_id=txn_waybill.consignee_id and
                                                      consignee_contact.default_flag=1
                         left join txn_waybill_package_dimension as packdim on packdim.waybill_number=txn_waybill.waybill_number
                         left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id 
                         left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id 
                         left join services on services.id=txn_waybill.package_service
                         left join mode_of_transport on mode_of_transport.id=txn_waybill.package_mode_of_transport
                         left join delivery_instruction on delivery_instruction.id=txn_waybill.package_delivery_instruction
                         left join destination_route on destination_route.id=txn_waybill.destination_route_id
                         left join transport_charges on transport_charges.id=txn_waybill.package_transport_charges
                         left join carrier on carrier.id=txn_waybill.carrier_id
                         left join txn_waybill_other_charges on txn_waybill_other_charges.waybill_number=txn_waybill.waybill_number 
                         left join txn_waybill_handling_instruction on txn_waybill_handling_instruction.waybill_number=txn_waybill.waybill_number
                         left join txn_waybill_document on txn_waybill_document.waybill_number=txn_waybill.waybill_number
                         left join accompanying_documents on accompanying_documents.id=txn_waybill_document.accompanying_document_id
                         left join handling_instruction on handling_instruction.id=txn_waybill_handling_instruction.handling_instruction_id



                   ";

        $arr = array();
        if($status!=''&&$status!='NULL'&&$status!='UNDEFINED'){
            array_push($arr, "txn_waybill.status='".$status."'");
        }
        if(trim($origin)!=''&&strtoupper($origin)!='NULL'){
          array_push($arr, "txn_waybill.origin_id='".$origin."'");
        }
        if(trim($destination)!=''&&strtoupper($destination)!='NULL'){
          array_push($arr, "txn_waybill.destination_id='".$destination."'");
        }
        if(trim($destinationroute)!=''&&strtoupper($destinationroute)!='NULL'){
          array_push($arr, "txn_waybill.destination_route_id='".$destinationroute."'");
        }
        if(trim($shipper)!=''&&strtoupper($shipper)!='NULL'){
          array_push($arr, "txn_waybill.shipper_id='".$shipper."'");
        }
        if(trim($consignee)!=''&&strtoupper($consignee)!='NULL'){
          array_push($arr, "txn_waybill.consignee_id='".$consignee."'");
        }
        if($service!=''&&strtoupper($service)!='NULL'){
            array_push($arr, "txn_waybill.package_service='".$service."'");
        }
        if($modeoftransport!=''&&strtoupper($modeoftransport)!='NULL'){
            array_push($arr, "txn_waybill.package_mode_of_transport='".$modeoftransport."'");
        }
        if($paymode!=''&&strtoupper($paymode)!='NULL'){
            array_push($arr, "txn_waybill.package_pay_mode='".$paymode."'");
        }


        if($docdatefrom!=''&&$docdateto!=''){
            array_push($arr,"date(txn_waybill.document_date) >= '$docdatefrom' and date(txn_waybill.document_date) <= '$docdateto'");
        }
        else if($docdatefrom==''&&$docdateto!=''){
            array_push($arr,"date(txn_waybill.document_date) <= '$docdateto'");
        }
        else if($docdatefrom!=''&&$docdateto==''){
             array_push($arr,"date(txn_waybill.document_date) >= '$docdatefrom'");
        }

        if($pudateto!=''&&$pudatefrom!=''){
            array_push($arr,"date(txn_waybill.pickup_date) >= '$pudatefrom' and date(txn_waybill.pickup_date) <= '$pudateto'");
        }
        else if($pudatefrom==''&&$pudateto!=''){
            array_push($arr,"date(txn_waybill.pickup_date) <= '$pudateto'");
        }
        else if($pudatefrom!=''&&$pudateto==''){
             array_push($arr,"date(txn_waybill.pickup_date) >= '$pudatefrom'");
        }
 
        if($deldatefrom!=''&&$deldateto!=''){
            array_push($arr,"date(txn_waybill.created_date) >= '$deldatefrom' and date(txn_waybill.created_date) <= '$deldateto'");
        }
        else if($deldatefrom==''&&$deldateto!=''){
            array_push($arr,"date(txn_waybill.created_date) <= '$deldateto'");
        }
        else if($deldatefrom!=''&&$deldateto==''){
             array_push($arr,"date(txn_waybill.created_date) >= '$deldatefrom'");
        }


        $condition = implode(" and ", $arr);
        if(count($arr)>0){
            $condition = " where ".$condition; 
        }
        $qry = $qry.$condition." group by txn_waybill.waybill_number";
        
        //echo $qry;
        $rs = mysql_query($qry);

        if($format==1){

             // HEADER
             $headercell = array(
                                    'DOCUMENT DATE',
                                    'MAWB/BOL#',
                                    'DESTINATION',
                                    'CONSIGNEE ACCOUNT NAME',
                                    'CONSIGNEE COMPANY NAME',
                                    'CONSIGNEE TEL',
                                    'SHIPMENT DESCRIPTION',
                                    'WAYBILL NUMBER',
                                    'RECEIVED DATE',
                                    'RECEIVED BY',
                                    'LAST STATUS UPDATE REMARKS',
                                    'REMARKS'

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
             while($obj=mysql_fetch_object($rs)){
                    $zerorated = $obj->zero_rated_flag==1?'YES':'NO';
                    $detailcell = array(
                                           

                                           
                                            dateFormat($obj->document_date,'m/d/Y'),
                                            utfEncode($obj->waybill_number),
                                            utfEncode($obj->destination),
                                            utfEncode($obj->consignee_account_name),
                                            utfEncode($obj->consignee_company_name),
                                            utfEncode($obj->consignee_tel_number),
                                            utfEncode($obj->shipment_description),
                                            utfEncode($obj->waybill_number),
                                            dateFormat($obj->received_date,'m/d/Y'),
                                            utfEncode($obj->received_by),
                                            utfEncode($obj->last_status_update_remarks),
                                            utfEncode($obj->remarks)

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
        else if($format==2){

             // HEADER
             $headercell = array(
                                    'LINE',
                                    'SYSTEM ID',
                                    'WAYBILL NUMBER',
                                    'STATUS',
                                    'RECEIVED BY',
                                    'RECEIVED DATE',
                                    'LAST STATUS UPDATE REMARKS',
                                    'DOCUMENT DATE',
                                    'CREATED DATE',
                                    'DELIVERY DATE',
                                    'MANIFEST NO.',
                                    'INVOICE NO.',
                                    'REMARKS',
                                    'BOOKING NUMBER',
                                    'ORIGIN',
                                    'DESTINATION',
                                    'DESTINATION ROUTE',
                                    'SHIPPER ACCOUNT NUMBER',
                                    'SHIPPER ACCOUNT NAME',
                                    'SHIPPER TEL',
                                    'SHIPPER COMPANY NAME',
                                    'SHIPPER STREET ADDRESS',
                                    'SHIPPER DISTRICT',
                                    'SHIPPER CITY',
                                    'SHIPPER REGION/PROVINCE',
                                    'SHIPPER ZIPCODE',
                                    'SHIPPER COUNTRY',
                                    'CONSIGNEE ACCOUNT NUMBER',
                                    'CONSIGNEE ACCOUNT NAME',
                                    'CONSIGNEE TEL',
                                    'CONSIGNEE COMPANY NAME',
                                    'CONSIGNEE STREET ADDRESS',
                                    'CONSIGNEE DISTRICT',
                                    'CONSIGNEE CITY',
                                    'CONSIGNEE REGION/PROVINCE',
                                    'CONSIGNEE ZIPCODE',
                                    'CONSIGNEE COUNTRY',
                                    'NUMBER OF PACKAGE',
                                    'DECLARED VALUE',
                                    'ACTUAL WEIGHT',
                                    'CBM',
                                    'VW',
                                    'SERVICE',
                                    'MODE OF TRANSPORT',
                                    'DOCUMENT',
                                    'HANDLING INSTRUCTION',
                                    'DELIVERY INSTRUCTION',
                                    'TRANSPORT CHARGES',
                                    'PAY MODE',
                                    'SHIPMENT DESCRIPTION',
                                    'FREIGHT COMPUTATION',
                                    'CHARGEABLE WEIGHT',
                                    'VALUATION',
                                    'FREIGHT CHARGES',
                                    'INSURANCE CHARGES',
                                    'FUEL CHARGES',
                                    'BUNKER CHARGES',
                                    'MINIMUM CHARGES',
                                    'OTHER CHARGES',
                                    'SUBTOTAL',
                                    'ZERO RATED FLAG',
                                    'VAT',
                                    'TOTAL AMOUNT',
                                    'AMOUNT FOR COLLECTION',
                                    'PACKAGE DIMENSIONS'

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
             while($obj=mysql_fetch_object($rs)){
                    $zerorated = $obj->zero_rated_flag==1?'YES':'NO';
                    $detailcell = array(
                                            /*$line,
                                            $obj->id,
                                            $obj->waybill_number,
                                            $obj->status,
                                            dateFormat($obj->document_date,'m/d/Y'),
                                            $obj->manifest_number,
                                            $obj->invoice_number,
                                            $obj->remarks,
                                            $obj->booking_number,
                                            $obj->origin,
                                            $obj->destination,
                                            $obj->destinationroute,
                                            'SHIPPER ACCOUNT NUMBER',
                                            'SHIPPER ACCOUNT NAME',
                                            'SHIPPER TEL',
                                            'SHIPPER COMPANY NAME',
                                            'SHIPPER STREET ADDRESS',
                                            'SHIPPER DISTRICT',
                                            'SHIPPER CITY',
                                            'SHIPPER REGION/PROVINCE',
                                            'SHIPPER ZIPCODE',
                                            'SHIPPER COUNTRY',
                                            'CONSIGNEE ACCOUNT NUMBER',
                                            'CONSIGNEE ACCOUNT NAME',
                                            'CONSIGNEE TEL',
                                            'CONSIGNEE COMPANY NAME',
                                            'CONSIGNEE STREET ADDRESS',
                                            'CONSIGNEE DISTRICT',
                                            'CONSIGNEE CITY',
                                            'CONSIGNEE REGION/PROVINCE',
                                            'CONSIGNEE ZIPCODE',
                                            'CONSIGNEE COUNTRY',
                                            'NUMBER OF PACKAGE',
                                            'DECLARED VALUE',
                                            'ACTUAL WEIGHT',
                                            'VW/CBM',
                                            'SERVICE',
                                            'MODE OF TRANSPORT',
                                            'DOCUMENT',
                                            'DELIVERY INSTRUCTION',
                                            'TRANSPORT CHARGES',
                                            'PAY MODE',
                                            'SHIPMENT DESCRIPTION',
                                            'FREIGHT COMPUTATION',
                                            'CHARGEABLE WEIGHT',
                                            'VALUATION',
                                            'FREIGHT CHARGES',
                                            'INSURANCE CHARGES',
                                            'FUEL CHARGES',
                                            'BUNKER CHARGES',
                                            'MINIMUM CHARGES'*/

                                            utfEncode($line),
                                            utfEncode($obj->id),
                                            utfEncode($obj->waybill_number),
                                            utfEncode($obj->status),
                                            utfEncode($obj->received_by),
                                            dateFormat($obj->received_date,'m/d/Y h:i:s A'),
                                            utfEncode($obj->last_status_update_remarks),
                                            dateFormat($obj->document_date,'m/d/Y'),
                                            dateFormat($obj->created_date,'m/d/Y'),
                                            dateFormat($obj->delivery_date,'m/d/Y'),
                                            utfEncode($obj->manifest_number),
                                            utfEncode($obj->invoice_number),
                                            utfEncode($obj->remarks),
                                            utfEncode($obj->booking_number),
                                            utfEncode($obj->origin),
                                            utfEncode($obj->destination),
                                            utfEncode($obj->destinationroute),
                                            utfEncode($obj->shipper_account_number),
                                            utfEncode($obj->shipper_account_name),
                                            utfEncode($obj->shipper_tel_number),
                                            utfEncode($obj->shipper_company_name),
                                            utfEncode($obj->shipper_street_address),
                                            utfEncode($obj->shipper_district),
                                            utfEncode($obj->shipper_city),
                                            utfEncode($obj->shipper_state_province),
                                            utfEncode($obj->shipper_zip_code),
                                            utfEncode($obj->shipper_country),
                                            utfEncode($obj->consignee_account_number),
                                            utfEncode($obj->consignee_account_name),
                                            utfEncode($obj->consignee_tel_number),
                                            utfEncode($obj->consignee_company_name),
                                            utfEncode($obj->consignee_street_address),
                                            utfEncode($obj->consignee_district),
                                            utfEncode($obj->consignee_city),
                                            utfEncode($obj->consignee_state_province),
                                            utfEncode($obj->consignee_zip_code),
                                            utfEncode($obj->consignee_country),
                                            utfEncode($obj->package_number_of_packages),
                                            utfEncode($obj->package_declared_value),
                                            utfEncode($obj->package_actual_weight),
                                            utfEncode($obj->package_cbm),
                                            utfEncode($obj->package_vw),
                                            utfEncode($obj->servicedesc),
                                            utfEncode($obj->modeoftransport),
                                            utfEncode($obj->document),
                                            utfEncode($obj->handlinginstruction),
                                            utfEncode($obj->deliveryinstruction),
                                            utfEncode($obj->transportcharges),
                                            utfEncode($obj->package_pay_mode),
                                            utfEncode($obj->shipment_description),
                                            utfEncode($obj->package_freight_computation),
                                            utfEncode($obj->package_chargeable_weight),
                                            utfEncode($obj->package_valuation),
                                            utfEncode($obj->package_freight),
                                            utfEncode($obj->package_insurance_rate),
                                            utfEncode($obj->package_fuel_rate),
                                            utfEncode($obj->package_bunker_rate),
                                            utfEncode($obj->package_minimum_rate),
                                            utfEncode($obj->othercharges),
                                            utfEncode($obj->subtotal),
                                            utfEncode($zerorated),
                                            utfEncode($obj->vat),
                                            utfEncode($obj->total_amount),
                                            utfEncode($obj->amount_for_collection),
                                            $obj->packdimensions

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
        else if($format==3){

             // HEADER
             $headercell = array(
                                    'Item ID',
                                    'Shipment Mode',
                                    'Tracking Number',
                                    'PUR',
                                    'Origin',
                                    'Destination',
                                    'Transaction Date',
                                    'ODZ',
                                    'Shipper Account No.',
                                    'Shipper',
                                    'Street/Bldg. (Shipper)',
                                    'Barangay (Shipper)',
                                    'City/Municipality (Shipper)',
                                    'Province (Shipper)',
                                    'Contact Number (Shipper)',
                                    'Send SMS (Shipper)',
                                    'Mobile Number (Shipper)',
                                    'Pay Mode',
                                    'Product Line',
                                    'Service Mode',
                                    'COD Amount',
                                    'Consignee Account No.',
                                    'Consignee',
                                    'Street/Bldg. (Shipper)',
                                    'Barangay (Shipper)',
                                    'City/Municipality (Shipper)',
                                    'Province (Shipper)',
                                    'Contact Number (Shipper)',
                                    'Send SMS (Shipper)',
                                    'Mobile Number (Shipper)',
                                    'Special Instructions',
                                    'Quantity',
                                    'PKG',
                                    'Actual Weight',
                                    'Dimensions',
                                    'Length (cm)',
                                    'Width (cm)',
                                    'Height (cm)',
                                    'Vol WT',
                                    'CBM',
                                    'Chargeable WT',
                                    'Declared Value',
                                    'Description',
                                    'Commodity',
                                    'For Crating',
                                    'Attachment Name 1',
                                    'Reference No. 1',
                                    'Attachment Name 2',
                                    'Reference No. 2',
                                    'Attachment Name 3',
                                    'Reference No. 3',
                                    'Attachment Name 4',
                                    'Reference No. 4',
                                    'OR Number',
                                    'OR Amount',
                                    'Delivery Requirements',
                                    'Consignee Email Address',
                                    'Billing Reference'

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
             while($obj=mysql_fetch_object($rs)){
                    $zerorated = $obj->zero_rated_flag==1?'YES':'NO';
                    $detailcell = array(
                                            

                                            utfEncode($line),
                                            utfEncode($obj->modeoftransport),
                                            utfEncode($obj->waybill_number),
                                            utfEncode(''),
                                            utfEncode($obj->origin),
                                            utfEncode($obj->destination),
                                            dateFormat($obj->document,'m/d/Y h:i:s A'),
                                            utfEncode(''),
                                            utfEncode($obj->shipper_account_number),
                                            utfEncode($obj->shipper_account_name),
                                            utfEncode($obj->shipper_street_address),
                                            utfEncode($obj->shipper_district),
                                            utfEncode($obj->shipper_city),
                                            utfEncode($obj->shipper_state_province),
                                            utfEncode($obj->shippertel),
                                            utfEncode($obj->shippersmsflag),
                                            utfEncode($obj->shippermobile),
                                            utfEncode($obj->package_pay_mode),
                                            utfEncode(''),
                                            utfEncode(''),
                                            utfEncode(''),
                                            utfEncode($obj->consignee_account_number),
                                            utfEncode($obj->consignee_account_name),
                                            utfEncode($obj->consignee_street_address),
                                            utfEncode($obj->consignee_district),
                                            utfEncode($obj->consignee_city),
                                            utfEncode($obj->consignee_state_province),
                                            utfEncode($obj->consigneetel),
                                            utfEncode($obj->consigneesmsflag),
                                            utfEncode($obj->consigneemobile),
                                            utfEncode($obj->deliveryinstruction),
                                            utfEncode($obj->package_number_of_packages),
                                            utfEncode(''),
                                            utfEncode($obj->package_actual_weight),
                                            $obj->packdimensions,
                                            utfEncode(''),
                                            utfEncode(''),
                                            utfEncode(''),
                                            utfEncode($obj->package_vw),
                                            utfEncode($obj->package_cbm),
                                            utfEncode($obj->package_chargeable_weight),
                                            utfEncode($obj->package_declared_value),
                                            utfEncode($obj->shipment_description),
                                            utfEncode($obj->handlinginstruction),
                                            utfEncode(''),
                                            utfEncode(''),
                                            utfEncode(''),
                                            utfEncode(''),
                                            utfEncode(''),
                                            utfEncode(''),
                                            utfEncode(''),
                                            utfEncode(''),
                                            utfEncode(''),
                                            utfEncode(''),
                                            utfEncode(''),
                                            utfEncode(''),
                                            utfEncode(''),
                                            utfEncode($obj->bill_reference)

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

          header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          header('Content-Disposition: attachment; filename="waybill-summary-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');

?>