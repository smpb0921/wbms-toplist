<?php



    include("../../../config/connection.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");
    include("../../../resources/PHPExcel-1.8/classes/PHPExcel.php");


    $status = isset($_GET['status'])?escapeString(strtoupper($_GET['status'])):'';
    $origin = isset($_GET['origin'])?escapeString(strtoupper($_GET['origin'])):'';
    $destination = isset($_GET['destination'])?escapeString($_GET['destination']):'';
    $modeoftransport = isset($_GET['modeoftransport'])?escapeString(strtoupper($_GET['modeoftransport'])):'';
    $vehicletype = isset($_GET['vehicletype'])?escapeString(strtoupper($_GET['vehicletype'])):'';
    $agent = isset($_GET['agent'])?escapeString(strtoupper($_GET['agent'])):'';
    $location = isset($_GET['location'])?escapeString(strtoupper($_GET['location'])):'';
    $carrier = isset($_GET['carrier'])?escapeString(strtoupper($_GET['carrier'])):'';
    $manifestnumber = isset($_GET['manifestnumber'])?escapeString(strtoupper($_GET['manifestnumber'])):'';
    $mawbblnumber = isset($_GET['mawbblnumber'])?escapeString(strtoupper($_GET['mawbblnumber'])):'';
    $docdatefrom = isset($_GET['docdatefrom'])?escapeString($_GET['docdatefrom']):'';
    $docdateto = isset($_GET['docdateto'])?escapeString($_GET['docdateto']):'';
    $etdfrom = isset($_GET['etdfrom'])?escapeString($_GET['etdfrom']):'';
    $etdto = isset($_GET['etdto'])?escapeString($_GET['etdto']):'';
    $etafrom = isset($_GET['etafrom'])?escapeString($_GET['etafrom']):'';
    $etato = isset($_GET['etato'])?escapeString($_GET['etato']):'';
    $waybill = isset($_GET['waybill'])?escapeString(trim(strtoupper($_GET['waybill']))):'';

    if($docdatefrom!=''){
        $docdatefrom = date('Y-m-d', strtotime($docdatefrom));
    }
    if($docdateto!=''){
        $docdateto = date('Y-m-d', strtotime($docdateto));
    }

    if($etdfrom!=''){
        $etdfrom = date('Y-m-d', strtotime($etdfrom));
    }
    if($etdto!=''){
        $etdto = date('Y-m-d', strtotime($etdto));
    }

    if($etafrom!=''){
        $etafrom = date('Y-m-d', strtotime($etafrom));
    }
    if($etato!=''){
        $etato = date('Y-m-d', strtotime($etato));
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

     $sheet->setTitle('Load Plan Summary');

     $qry =       "      select txn_load_plan.id,
                                txn_load_plan.load_plan_number,
                                txn_load_plan.status,
                                txn_load_plan.manifest_number,
                                txn_load_plan.location_id,
                                txn_load_plan.carrier_id,
                                txn_load_plan.origin_id,
                                txn_load_plan.destination_id,
                                txn_load_plan.mode_of_transport_id,
                                txn_load_plan.agent_id,
                                txn_load_plan.mawbl_bl,
                                txn_load_plan.document_date,
                                txn_load_plan.eta,
                                txn_load_plan.etd,
                                txn_load_plan.remarks,
                                txn_load_plan.vehicle_type_id,
                                location.description as locdesc,
                                carrier.description as carrierdesc,
                                origintbl.description as origindesc,
                                destinationtbl.description as destinationdesc,
                                mode_of_transport.description as modeoftransportdesc,
                                agent.company_name as agentdesc,
                                vehicle_type.description as vehicletypedesc,
                                group_concat(txn_load_plan_waybill.waybill_number) as waybills
                        from txn_load_plan
                        left join location on location.id=txn_load_plan.location_id
                        left join carrier on carrier.id=txn_load_plan.carrier_id
                        left join origin_destination_port as origintbl on origintbl.id=txn_load_plan.origin_id 
                        left join origin_destination_port as destinationtbl on destinationtbl.id=txn_load_plan.destination_id
                        left join mode_of_transport on mode_of_transport.id=txn_load_plan.mode_of_transport_id
                        left join agent on agent.id=txn_load_plan.agent_id
                        left join vehicle_type on vehicle_type.id=txn_load_plan.vehicle_type_id
                        left join txn_load_plan_waybill on txn_load_plan_waybill.load_plan_number=txn_load_plan.load_plan_number

                   ";

        $arr = array();
        if($status!=''&&$status!='NULL'&&$status!='UNDEFINED'){
            array_push($arr, "txn_load_plan.status='".$status."'");
        }
        if(trim($origin)!=''&&strtoupper($origin)!='NULL'){
          array_push($arr, "txn_load_plan.origin_id='".$origin."'");
        }
        if(trim($destination)!=''&&strtoupper($destination)!='NULL'){
          array_push($arr, "txn_load_plan.destination_id='".$destination."'");
        }
        if($modeoftransport!=''&&strtoupper($modeoftransport)!='NULL'){
            array_push($arr, "txn_load_plan.mode_of_transport_id='".$modeoftransport."'");
        }
        if($vehicletype!=''&&strtoupper($vehicletype)!='NULL'){
            array_push($arr, "txn_load_plan.vehicle_type_id='".$vehicletype."'");
        }
        if($location!=''&&strtoupper($location)!='NULL'){
            array_push($arr, "txn_load_plan.location_id='".$location."'");
        }
        if($carrier!=''&&strtoupper($carrier)!='NULL'){
            array_push($arr, "txn_load_plan.carrier_id='".$carrier."'");
        }
        if(trim($manifestnumber)!=''){
            array_push($arr, "txn_load_plan.manifest_number regexp '".$manifestnumber."'");
        }
        if(trim($mawbblnumber)!=''){
            array_push($arr, "txn_load_plan.mawbl_bl regexp '".$mawbblnumber."'");
        }

        if(trim($waybill)!=''){
            array_push($arr, "txn_load_plan.load_plan_number in (select load_plan_number from txn_load_plan_waybill where waybill_number='$waybill')");
        }


        if($docdatefrom!=''&&$docdateto!=''){
            array_push($arr,"date(txn_load_plan.document_date) between '$docdatefrom' and '$docdateto'");
        }
        else if($docdatefrom==''&&$docdateto!=''){
            array_push($arr,"date(txn_load_plan.document_date) <= '$docdateto'");
        }
        else if($docdatefrom!=''&&$docdateto==''){
             array_push($arr,"date(txn_load_plan.document_date) >= '$docdatefrom'");
        }

        if($etdfrom!=''&&$etdto!=''){
            array_push($arr,"date(txn_load_plan.etd) between '$etdfrom' and '$etdto'");
        }
        else if($etdfrom==''&&$etdto!=''){
            array_push($arr,"date(txn_load_plan.etd) <= '$etdto'");
        }
        else if($etdfrom!=''&&$etdto==''){
             array_push($arr,"date(txn_load_plan.etd) >= '$etdfrom'");
        }


        if($etafrom!=''&&$etato!=''){
            array_push($arr,"date(txn_load_plan.eta) between '$etafrom' and '$etato'");
        }
        else if($etafrom==''&&$etato!=''){
            array_push($arr,"date(txn_load_plan.eta) <= '$etato'");
        }
        else if($etafrom!=''&&$etato==''){
             array_push($arr,"date(txn_load_plan.eta) >= '$etafrom'");
        }


        $condition = implode(" and ", $arr);
        if(count($arr)>0){
            $condition = " where ".$condition; 
        }
        $qry = $qry.$condition." group by txn_load_plan.load_plan_number";

        //echo $qry;
        $rs = mysql_query($qry);



         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'LOAD PLAN NUMBER',
                                'STATUS',
                                'DOCUMENT DATE',
                                'MANIFEST NO.',
                                'MAWBL/BL NO.',
                                'LOCATION',
                                'CARRIER',
                                'ORIGIN',
                                'DESTINATION',
                                'MODE OF TRANSPORT',
                                'VEHICLE TYPE',
                                'AGENT',
                                'ETD',
                                'ETA',
                                'REMARKS',
                                'WAYBILL(S)'

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
                                        $obj->load_plan_number,
                                        $obj->status,
                                        dateFormat($obj->document_date,'m/d/Y'),
                                        $obj->manifest_number,
                                        $obj->mawbl_bl,
                                        utfEncode($obj->locdesc),
                                        utfEncode($obj->carrierdesc),
                                        utfEncode($obj->origindesc),
                                        utfEncode($obj->destinationdesc),
                                        utfEncode($obj->modeoftransportdesc),
                                        utfEncode($obj->vehicletypedesc),
                                        utfEncode($obj->agentdesc),
                                        dateFormat($obj->etd,'m/d/Y h:i:s A'),
                                         dateFormat($obj->eta,'m/d/Y h:i:s A'),
                                        utfEncode($obj->remarks),
                                        $obj->waybills

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
          header('Content-Disposition: attachment; filename="load-plan-summary-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');

?>