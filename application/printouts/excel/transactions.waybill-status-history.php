<?php



    include("../../../config/connection.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");
    include("../../../resources/PHPExcel-1.8/classes/PHPExcel.php");


   
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

     $sheet->setTitle('Waybill Status History');

     $waybillnumber = isset($_GET['waybillnumber'])?escapeString(strtoupper(trim($_GET['waybillnumber']," "))):'';
     $sheet->setCellValue('A1','Waybill No.:');
     $sheet->setCellValue('B1',$waybillnumber);

     $qry =       " select txn_waybill_status_history.id,
                           txn_waybill_status_history.waybill_number,
                           txn_waybill_status_history.status_description,
                           txn_waybill_status_history.remarks,
                           txn_waybill_status_history.received_date,
                           txn_waybill_status_history.received_by,
                           txn_waybill_status_history.created_date,
                           txn_waybill_status_history.created_by,
                           concat(user.first_name,' ',user.last_name) as createdby
                    from txn_waybill_status_history
                    left join user on user.id=txn_waybill_status_history.created_by
                    where waybill_number='$waybillnumber'
                    order by txn_waybill_status_history.created_date desc

                   ";


        $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'WAYBILL NUMBER',
                                'STATUS',
                                'REMARKS',
                                'DATE',
                                'RECEIVED BY',
                                'TIMESTAMP',
                                'USER',
                                'SYSTEM ROW ID'
                            );
         $col = 'A';
         for($i=0;$i<count($headercell);$i++){
            $sheet->setCellValue($col.'3',$headercell[$i]);
                    cellColor($objExcel,$col.'3','bbdff1');
            $col++;
         }
         
         //DETAILS
         $row = 4;
         $line = 1;
         while($obj=mysql_fetch_object($rs)){
                $receiveddate = dateFormat($obj->received_date, "m/d/Y h:i:s A");
                $timestamp = dateFormat($obj->created_date, "m/d/Y h:i:s A");
                $detailcell = array(
                                        $line,
                                        utfEncode($obj->waybill_number),
                                        utfEncode($obj->status_description),
                                        utfEncode($obj->remarks),
                                        utfEncode($receiveddate),
                                        utfEncode($obj->received_by),
                                        $timestamp,
                                        utfEncode($obj->createdby),
                                        $obj->id
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
          header('Content-Disposition: attachment; filename="'.$waybillnumber.'-waybillstatus-history-'.date('Ymdhis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');

?>