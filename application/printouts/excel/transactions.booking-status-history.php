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

     $sheet->setTitle('Booking Status History');

     $bookingnumber = isset($_GET['bookingnumber'])?escapeString(strtoupper(trim($_GET['bookingnumber']," "))):'';
     $sheet->setCellValue('A1','Booking No.:');
     $sheet->setCellValue('B1',$bookingnumber);

     $qry =       " select txn_booking_status_history.id,
                           txn_booking_status_history.booking_number,
                           txn_booking_status_history.status_description,
                           txn_booking_status_history.remarks,
                           txn_booking_status_history.created_date,
                           txn_booking_status_history.created_by,
                           concat(first_name,' ',last_name) as createdby,
                           txn_booking_status_history.contact,
                           txn_booking_status_history.date
                    from txn_booking_status_history
                    left join user on user.id=txn_booking_status_history.created_by
                    where booking_number='$bookingnumber'
                    order by txn_booking_status_history.created_date desc

                   ";


        $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'BOOKING NUMBER',
                                'STATUS',
                                'CONTACT',
                                'DATE',
                                'REMARKS',
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
                $date = dateFormat($obj->date, "m/d/Y h:i:s A");
                $timestamp = dateFormat($obj->created_date, "m/d/Y h:i:s A");
                $detailcell = array(
                                        $line,
                                        utfEncode($obj->booking_number),
                                        utfEncode($obj->status_description),
                                        utfEncode($obj->contact),
                                        utfEncode($date),
                                        utfEncode($obj->remarks),
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
          header('Content-Disposition: attachment; filename="'.$bookingnumber.'-booking-status-history-'.date('Ymdhis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');

?>