<?php



    include("../../../config/connection.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");
    include("../../../resources/PHPExcel-1.8/classes/PHPExcel.php");


    $status = isset($_GET['status'])?escapeString($_GET['status']):'';
    $bookingtype = isset($_GET['bookingtype'])?escapeString($_GET['bookingtype']):'';
    $origin = isset($_GET['origin'])?escapeString($_GET['origin']):'';
    $destination = isset($_GET['destination'])?escapeString($_GET['destination']):'';
    $shipper = isset($_GET['shipper'])?escapeString($_GET['shipper']):'';
    //$consignee = isset($_GET['consignee'])?escapeString($_GET['consignee']):'';
    $city = isset($_GET['city'])?escapeString($_GET['city']):'';
    $region = isset($_GET['region'])?escapeString($_GET['region']):'';
    $service = isset($_GET['service'])?escapeString($_GET['service']):'';
    $modeoftransport = isset($_GET['modeoftransport'])?escapeString($_GET['modeoftransport']):'';
    $handlinginstruction = isset($_GET['handlinginstruction'])?escapeString($_GET['handlinginstruction']):'';
    $paymode = isset($_GET['paymode'])?escapeString($_GET['paymode']):'';
    $pickupdatefrom = isset($_GET['pickupdatefrom'])?escapeString($_GET['pickupdatefrom']):'';
    $pickupdateto = isset($_GET['pickupdateto'])?escapeString($_GET['pickupdateto']):'';
    $createddatefrom = isset($_GET['createddatefrom'])?escapeString($_GET['createddatefrom']):'';
    $createddateto = isset($_GET['createddateto'])?escapeString($_GET['createddateto']):'';
    $format = isset($_GET['format'])?escapeString($_GET['format']):'';

    if($pickupdatefrom!=''){
        $pickupdatefrom = date('Y-m-d', strtotime($pickupdatefrom));
    }
    if($pickupdateto!=''){
        $pickupdateto = date('Y-m-d', strtotime($pickupdateto));
    }

    if($createddatefrom!=''){
        $createddatefrom = date('Y-m-d', strtotime($createddatefrom));
    }
    if($createddateto!=''){
        $createddateto = date('Y-m-d', strtotime($createddateto));
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

     $sheet->setTitle('Booking Summary');

     $qry =       "SELECT txn_booking.id, 
                                txn_booking.booking_number,
                                txn_booking.status,
                                txn_booking.origin_id,
                                txn_booking.destination_id,
                                txn_booking.approved_by,
                                txn_booking.approved_date,
                                txn_booking.rejected_by,
                                txn_booking.rejected_date,
                                txn_booking.rejected_reason,
                                txn_booking.pickup_date,
                                txn_booking.actual_pickup_date,
                                txn_booking.pickup_by,
                                txn_booking.remarks,
                                txn_booking.created_date,
                                txn_booking.created_by,
                                txn_booking.updated_date,
                                txn_booking.updated_by,
                                txn_booking.shipper_id,
                                txn_booking.shipper_account_number,
                                txn_booking.shipper_name,
                                txn_booking.shipper_tel_number,
                                txn_booking.shipper_company_name,
                                txn_booking.shipper_street_address,
                                txn_booking.shipper_district,
                                txn_booking.shipper_city,
                                txn_booking.shipper_state_province,
                                txn_booking.shipper_zip_code,
                                txn_booking.shipper_country,
                                txn_booking.shipper_pickup_street_address,
                                txn_booking.shipper_pickup_district,
                                txn_booking.shipper_pickup_city,
                                txn_booking.shipper_pickup_state_province,
                                txn_booking.shipper_pickup_zip_code,
                                txn_booking.shipper_pickup_country,
                                txn_booking.consignee_id,
                                txn_booking.consignee_account_number,
                                txn_booking.consignee_name,
                                txn_booking.consignee_tel_number,
                                txn_booking.consignee_company_name,
                                txn_booking.consignee_street_address,
                                txn_booking.consignee_district,
                                txn_booking.consignee_city,
                                txn_booking.consignee_state_province,
                                txn_booking.consignee_zip_code,
                                txn_booking.consignee_country,
                                txn_booking.package_number_of_packages,
                                txn_booking.package_declared_value,
                                txn_booking.package_actual_weight,
                                txn_booking.package_cbm,
                                txn_booking.package_service,
                                txn_booking.package_mode_of_transport,
                                txn_booking.package_handling_instruction,
                                txn_booking.package_pay_mode,
                                txn_booking.package_amount,
                                txn_booking.shipment_description,
                                txn_booking.posted_by,
                                txn_booking.posted_date,
                                txn_booking.unit_of_measure,
                                txn_booking.package_document,
                                group_concat(distinct accompanying_documents.description separator ', ') as documents,
                                group_concat(distinct handling_instruction.description separator ', ') as handlinginstruction,
                                origintbl.description as origin,
                                destinationtbl.description as destination,
                                services.description as servicedesc,
                                mode_of_transport.description as modeoftransport,
                                concat(cuser.first_name,' ',cuser.last_name) as createdby,
                                concat(puser.first_name,' ',puser.last_name) as postedby,
                                concat(ruser.first_name,' ',ruser.last_name) as rejectedby,
                                concat(auser.first_name,' ',auser.last_name) as approvedby,
                                vt.type vehicle_type_type,
                                vt.description as vehicletype,
                                txn_booking.driver,
                                txn_booking.helper,
                                booking_type.description as bookingtype

                         from txn_booking
                         left join txn_booking_document on txn_booking_document.booking_number=txn_booking.booking_number
                         left join txn_booking_handling_instruction on txn_booking_handling_instruction.booking_number=txn_booking.booking_number
                         left join origin_destination_port as origintbl on origintbl.id=txn_booking.origin_id 
                         left join origin_destination_port as destinationtbl on destinationtbl.id=txn_booking.destination_id 
                         left join services on services.id=txn_booking.package_service
                         left join accompanying_documents on accompanying_documents.id=txn_booking_document.accompanying_document_id
                         left join handling_instruction on handling_instruction.id=txn_booking_handling_instruction.handling_instruction_id
                         left join mode_of_transport on mode_of_transport.id=txn_booking.package_mode_of_transport
                         left join user as cuser on cuser.id=txn_booking.created_by
                         left join user as puser on puser.id=txn_booking.posted_by
                         left join user as ruser on ruser.id=txn_booking.rejected_by
                         left join user as auser on auser.id=txn_booking.approved_by
                         left join vehicle_type vt on vt.id = txn_booking.vehicle_type_id
                         left join booking_type on booking_type.id=txn_booking.booking_type_id
                   ";

        $arr = array();
        if($status!=''&&$status!='NULL'){
            array_push($arr, "txn_booking.status='".$status."'");
        }
        if(trim($bookingtype)!=''&&strtoupper($bookingtype)!='NULL'){
          array_push($arr, "txn_booking.booking_type_id='".$bookingtype."'");
        }
        if(trim($origin)!=''&&strtoupper($origin)!='NULL'){
          array_push($arr, "txn_booking.origin_id='".$origin."'");
        }
        if(trim($destination)!=''&&strtoupper($destination)!='NULL'){
          array_push($arr, "txn_booking.destination_id='".$destination."'");
        }
        if(trim($shipper)!=''&&strtoupper($shipper)!='NULL'){
          array_push($arr, "txn_booking.shipper_id='".$shipper."'");
        }
        /*if(trim($consignee)!=''&&strtoupper($consignee)!='NULL'){
          array_push($arr, "txn_booking.consignee_id='".$consignee."'");
        }*/
        if(trim($city)!=''&&strtoupper($city)!='NULL'){
          array_push($arr, "txn_booking.shipper_pickup_city regexp '".$city."'");
        }
        if(trim($region)!=''&&strtoupper($region)!='NULL'){
          array_push($arr, "txn_booking.shipper_pickup_state_province regexp '".$region."'");
        }
        if($service!=''&&strtoupper($service)!='NULL'){
            array_push($arr, "txn_booking.package_service='".$service."'");
        }
        if($handlinginstruction!=''&&strtoupper($handlinginstruction)!='NULL'){
            array_push($arr, "txn_booking.package_handling_instruction='".$handlinginstruction."'");
        }
        if($modeoftransport!=''&&strtoupper($modeoftransport)!='NULL'){
            array_push($arr, "txn_booking.package_mode_of_transport='".$modeoftransport."'");
        }
        if($paymode!=''&&strtoupper($paymode)!='NULL'){
            array_push($arr, "txn_booking.package_pay_mode='".$paymode."'");
        }


        if($pickupdatefrom!=''&&$pickupdateto!=''){
            array_push($arr,"date(txn_booking.pickup_date)>='$pickupdatefrom' and date(txn_booking.pickup_date)<='$pickupdateto'");
        }
        else if($pickupdatefrom==''&&$pickupdateto!=''){
            array_push($arr,"date(txn_booking.pickup_date) <= '$pickupdateto'");
        }
        else if($pickupdatefrom!=''&&$pickupdateto==''){
             array_push($arr,"date(txn_booking.pickup_date) >= '$pickupdatefrom'");
        }

        if($createddatefrom!=''&&$createddateto!=''){
            array_push($arr,"date(txn_booking.created_date)>='$createddatefrom' and date(txn_booking.created_date)<='$createddateto'");
        }
        else if($createddatefrom==''&&$createddateto!=''){
            array_push($arr,"date(txn_booking.created_date) <= '$createddateto'");
        }
        else if($createddatefrom!=''&&$createddateto==''){
             array_push($arr,"date(txn_booking.created_date) >= '$createddatefrom'");
        }


        $condition = implode(" and ", $arr);
        if(count($arr)>0){
            $condition = " where ".$condition; 
        }
        $qry = $qry.$condition." group by txn_booking.booking_number";

        //echo $qry;

        $rs = mysql_query($qry);

        if($format==1){
             // HEADER
             $headercell = array(
                                    'BOOKING TYPE',
                                    'BOOKING NUMBER',
                                    'SHIPPER ACCOUNT NAME',
                                    'PICKUP CITY',
                                    'VEHICLE TYPE',
                                    'DRIVER',
                                    'CREATED BY',
                                    'REMARKS',
                                    'STATUS'
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

                    $detailcell = array(
                                            utfEncode($obj->bookingtype),
                                            $obj->booking_number,
                                            utfEncode($obj->shipper_name),
                                            utfEncode($obj->shipper_pickup_city),
                                            utfEncode($obj->vehicle_type_type),
                                            utfEncode($obj->driver),
                                            //dateFormat($obj->created_date,'m/d/Y h:i:s A'),
                                            utfEncode($obj->createdby),
                                            utfEncode($obj->remarks),
                                            $obj->status

                                        );

                    $col = 'A';
                    for($i=0;$i<count($detailcell);$i++){
                        $sheet->setCellValue($col.$row,$detailcell[$i]);
                        $sheet->getColumnDimension($col)->setAutoSize(true);
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
                                    'BOOKING NUMBER',
                                    'STATUS',
                                    'BOOKING TYPE',
                                    'ORIGIN',
                                    'DESTINATION',
                                    'PICKUP DATE',
                                    'PICKUP STREET ADDRESS',
                                    'PICKUP DISTRICT',
                                    'PICKUP CITY',
                                    'PICKUP REGION/PROVINCE',
                                    'PICKUP ZIPCODE',
                                    'PICKUP COUNTRY',
                                    'REMARKS',
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
                                    'UOM',
                                    'NUMBER OF PACKAGE',
                                    'DECLARED VALUE',
                                    'ACTUAL WEIGHT',
                                    'CBM',
                                    'SERVICE',
                                    'HANDLING INSTRUCTION',
                                    'MODE OF TRANSPORT',
                                    'PAY MODE',
                                    'DOCUMENTS',
                                    'SHIPMENT DESCRIPTION',
                                    'ACTUAL PICKUP DATE',
                                    'PICKED-UP BY',
                                    'CREATED DATE',
                                    'CREATED BY',
                                    'POSTED DATE',
                                    'POSTED BY',
                                    'APPROVED DATE',
                                    'APPROVED BY',
                                    'REJECTED DATE',
                                    'REJECTED BY',
                                    'REASON',
                                    'VEHICLE TYPE',
                                    'DRIVER',
                                    'HELPER'
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

                    $detailcell = array(
                                            $line,
                                            $obj->id,
                                            $obj->booking_number,
                                            $obj->status,
                                            utfEncode($obj->bookingtype),
                                            utfEncode($obj->origin),
                                            utfEncode($obj->destination),
                                            dateFormat($obj->pickup_date,'m/d/Y'),
                                            utfEncode($obj->shipper_pickup_street_address),
                                            utfEncode($obj->shipper_pickup_district),
                                            utfEncode($obj->shipper_pickup_city),
                                            utfEncode($obj->shipper_pickup_state_province),
                                            utfEncode($obj->shipper_pickup_zip_code),
                                            utfEncode($obj->shipper_pickup_country),
                                            utfEncode($obj->remarks),
                                            utfEncode($obj->shipper_account_number),
                                            utfEncode($obj->shipper_name),
                                            utfEncode($obj->shipper_tel_number),
                                            utfEncode($obj->shipper_company_name),
                                            utfEncode($obj->shipper_street_address),
                                            utfEncode($obj->shipper_district),
                                            utfEncode($obj->shipper_city),
                                            utfEncode($obj->shipper_state_province),
                                            utfEncode($obj->shipper_zip_code),
                                            utfEncode($obj->shipper_country),
                                            utfEncode($obj->unit_of_measure),
                                            utfEncode($obj->package_number_of_packages),
                                            utfEncode($obj->package_declared_value),
                                            utfEncode($obj->package_actual_weight),
                                            utfEncode($obj->package_cbm),
                                            utfEncode($obj->servicedesc),
                                            utfEncode($obj->handlinginstruction),
                                            utfEncode($obj->modeoftransport),
                                            utfEncode($obj->package_pay_mode),
                                            utfEncode($obj->documents),
                                            utfEncode($obj->shipment_description),
                                            dateFormat($obj->actual_pickup_date,'m/d/Y'),
                                            utfEncode($obj->pickup_by),
                                            dateFormat($obj->created_date,'m/d/Y'),
                                            utfEncode($obj->createdby),
                                            dateFormat($obj->posted_date,'m/d/Y'),
                                            utfEncode($obj->postedby),
                                            dateFormat($obj->approved_date,'m/d/Y'),
                                            utfEncode($obj->approvedby),
                                            dateFormat($obj->rejected_date,'m/d/Y'),
                                            utfEncode($obj->rejectedby),
                                            utfEncode($obj->rejected_reason), 
                                            utfEncode($obj->vehicletype), 
                                            utfEncode($obj->driver), 
                                            utfEncode($obj->helper)
                                            

                                        );

                    $col = 'A';
                    for($i=0;$i<count($detailcell);$i++){
                        $sheet->setCellValue($col.$row,$detailcell[$i]);
                        $sheet->getColumnDimension($col)->setAutoSize(true);
                        $col++;
                    }
                   
                    
                    $line++;
                    $row++;
             }
        }

          header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          header('Content-Disposition: attachment; filename="booking-summary-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');

?>