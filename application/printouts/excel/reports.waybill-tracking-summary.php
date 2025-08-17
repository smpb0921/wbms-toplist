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

     $waybillnumber = isset($_GET['waybillnumber'])?escapeString(strtoupper(trim($_GET['waybillnumber']))):'';
     $shipper = isset($_GET['shipper'])?escapeString(strtoupper(trim($_GET['shipper']))):'';
     $datefrom = isset($_GET['datefrom'])?escapeString(trim($_GET['datefrom'])):'';
     $dateto = isset($_GET['dateto'])?escapeString(trim($_GET['dateto'])):'';

     if($datefrom!=''){
        $datefrom = date('Y-m-d', strtotime($datefrom));
     }
     if($dateto!=''){
        $dateto = date('Y-m-d', strtotime($dateto));
     }


      $arr = array();
      if($shipper!=''&&$shipper!='NULL'&&$shipper!='UNDEFINED'){
            array_push($arr, "txn_waybill.shipper_id='".$shipper."'");
      }
      if(trim($waybillnumber)!=''){
            array_push($arr, "txn_waybill_status_history.waybill_number='".$waybillnumber."'");
      }

      if($datefrom!=''&&$dateto!=''){
            array_push($arr,"date(txn_waybill.document_date)>='$datefrom' and date(txn_waybill.document_date)<='$dateto'");
      }
      else if($datefrom==''&&$dateto!=''){
            array_push($arr,"date(txn_waybill.document_date) <= '$dateto'");
      }
      else if($datefrom!=''&&$dateto==''){
            array_push($arr,"date(txn_waybill.document_date)>='$datefrom'");
      }

        
        $condition = implode(" and ", $arr);
        if(count($arr)>0){
            $condition = " where ".$condition; 
        }




    

       

         // HEADER

         $headerrs = query(
                            "select max(statuscount) as maxcount
                              from (
                                    select txn_waybill_status_history.id,
                                           txn_waybill_status_history.waybill_number,
                                           count(*) as statuscount
                                    from txn_waybill_status_history
                                    left join txn_waybill on txn_waybill.waybill_number=txn_waybill_status_history.waybill_number
                                    left join shipper on shipper.id=txn_waybill.shipper_id
                                    left join user on user.id=txn_waybill_status_history.created_by
                                    $condition
                                    group by txn_waybill.waybill_number
                                    order by txn_waybill_status_history.waybill_number, txn_waybill_status_history.created_date asc
                            ) as tbl"
                          );

         $headercell = array(
                                'LINE',
                                'DOCUMENT DATE',
                                'SHIPPER ACCOUNT NAME',
                                'WAYBILL NUMBER'
                            );
         $maxcount = 0;
         while($objtemp = mysql_fetch_object($headerrs)){
                $maxcount = $objtemp->maxcount;
         }
         for($i=0;$i<$maxcount;$i++){
                array_push($headercell, 'STATUS','TIMESTAMP');
         }


         $col = 'A';
         for($i=0;$i<count($headercell);$i++){
            $sheet->setCellValue($col.'1',$headercell[$i]);
                    cellColor($objExcel,$col.'1','bbdff1');
            $col++;
         }




         
         //DETAILS
         $row = 2;
         $line = 1;
         $qry =   " select txn_waybill_status_history.id,
                           txn_waybill_status_history.waybill_number,
                           txn_waybill_status_history.status_description,
                           txn_waybill_status_history.remarks,
                           txn_waybill_status_history.created_date,
                           txn_waybill_status_history.created_by,
                           concat(first_name,' ',last_name) as createdby,
                           shipper.account_name,
                           shipper.company_name,
                           txn_waybill.document_date
                    from txn_waybill_status_history
                    left join txn_waybill on txn_waybill.waybill_number=txn_waybill_status_history.waybill_number
                    left join shipper on shipper.id=txn_waybill.shipper_id
                    left join user on user.id=txn_waybill_status_history.created_by
                    $condition
                    order by txn_waybill_status_history.waybill_number, txn_waybill_status_history.created_date asc

                   ";

         $rs = mysql_query($qry);
         $currentwaybill = '';
         while($obj=mysql_fetch_object($rs)){
                $status = $obj->status_description;
                $timestamp = dateFormat($obj->created_date, "m/d/Y h:i:s A");
                $detailcell = array(
                                        $line,
                                        dateFormat($obj->document_date, "m/d/Y"),
                                        $obj->account_name,
                                        $obj->waybill_number,
                                        $obj->status_description,
                                        $timestamp
                                    );
                $detailappendcell = array(
                                                $status,
                                                $timestamp

                                          );

                if($currentwaybill!=$obj->waybill_number){
                    $row++;
                    $line++;
                    $currentwaybill = $obj->waybill_number;

                    $col = 'A';
                    for($i=0;$i<count($detailcell);$i++){
                        $sheet->setCellValue($col.$row,$detailcell[$i]);
                        $col++;
                    }

                }
                else{
                    for($i=0;$i<count($detailappendcell);$i++){
                        $sheet->setCellValue($col.$row,$detailappendcell[$i]);
                        $col++;
                    }
                }


               
         }

          header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          header('Content-Disposition: attachment; filename="waybill-tracking-history-'.date('Ymdhis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');

?>