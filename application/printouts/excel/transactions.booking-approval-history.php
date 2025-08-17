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

     $sheet->setTitle('Booking Approval History');

     $qry =       "   select hst.id,
                             hst.action_taken,
                             hst.booking_id,
                             hst.remarks,
                             hst.created_date,
                             hst.created_by,
                             txn_booking.booking_number,
                             concat(first_name,' ',last_name) as createdby
                      from txn_booking_approval_rejection_history as hst
                      left join txn_booking on txn_booking.id=hst.booking_id
                      left join user on user.id=hst.created_by
                      order by txn_booking.booking_number, hst.created_date asc

                   ";


        $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'TIMESTAMP',
                                'APPROVER',
                                'BOOKING NUMBER',
                                'ACTION',
                                'REMARKS',
                                'SYSTEM ROW ID'
                            );
         $col = 'A';
         for($i=0;$i<count($headercell);$i++){
            $sheet->setCellValue($col.'1',$headercell[$i]);
                    cellColor($objExcel,$col.'1','bbdff1');
            $col++;
         }
         
         //DETAILS
         $row = 2;
         $line = 1;
         while($obj=mysql_fetch_object($rs)){
                $timestamp = dateFormat($obj->created_date, "m/d/Y h:i:s A");
                $detailcell = array(
                                        utfEncode($line),
                                        utfEncode($timestamp),
                                        utfEncode($obj->createdby),
                                        utfEncode($obj->booking_number),
                                        utfEncode($obj->action_taken),
                                        utfEncode($obj->remarks),
                                        utfEncode($obj->id)
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
          header('Content-Disposition: attachment; filename="booking-approval-history-'.date('Ymdhis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');

?>