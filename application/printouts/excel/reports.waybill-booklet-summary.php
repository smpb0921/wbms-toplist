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

     $sheet->setTitle('Waybill Booklet Summary');

     $issuedto = isset($_GET['issuedto'])?escapeString(strtoupper(trim($_GET['issuedto']))):'';
     $datefrom = isset($_GET['datefrom'])?escapeString(trim($_GET['datefrom'])):'';
     $dateto = isset($_GET['dateto'])?escapeString(trim($_GET['dateto'])):'';

     if($datefrom!=''){
        $datefrom = date('Y-m-d', strtotime($datefrom));
     }
     if($dateto!=''){
        $dateto = date('Y-m-d', strtotime($dateto));
     }


      $arr = array();
      if(trim($issuedto)!=''){
            array_push($arr, "txn_waybill_issuance.issued_to='%".$issuedto."%'");
      }

      if($dateto!=''){
            array_push($arr,"date(txn_waybill_issuance.validity_date)<='$dateto'");
      }
      if($datefrom!=''){
            array_push($arr,"date(txn_waybill_issuance.issuance_date)>='$datefrom'");
      }

        
      $condition = implode(" and ", $arr);
      if(count($arr)>0){
            $condition = " where ".$condition; 
      }

     $qry =       " select waybill_booklet_issuance.id,
                           waybill_booklet_issuance.issuance_date,
                           waybill_booklet_issuance.validity_date,
                           waybill_booklet_issuance.issued_to,
                           waybill_booklet_issuance.location_id,
                           location.description as loc_description,
                           waybill_booklet_issuance.booklet_start_series,
                           waybill_booklet_issuance.booklet_end_series,
                           waybill_booklet_issuance.remarks,
                           concat(cuser.first_name,' ',cuser.last_name) as createdby,
                           waybill_booklet_issuance.created_date,
                           concat(uuser.first_name,' ',uuser.last_name) as updatedby,
                           waybill_booklet_issuance.updated_date
                    from waybill_booklet_issuance
                    left join user as cuser on cuser.id=waybill_booklet_issuance.created_by
                    left join user as uuser on uuser.id=waybill_booklet_issuance.updated_by
                    left join location on location.id=waybill_booklet_issuance.location_id
                    $condition
                    order by issuance_date, validity_date asc

                   ";


         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'ISSUANCE DATE',
                                'VALIDITY DATE',
                                'ISSUED TO',
                                'LOCATION',
                                'START SERIES',
                                'END SERIES',
                                'REMARKS',
                                'CREATED BY',
                                'CREATED DATE',
                                'UPDATED BY',
                                'UPDATED DATE'
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
                $detailcell = array(
                                        $line,
                                        dateFormat($obj->issuance_date, 'm/d/Y'),
                                        dateFormat($obj->validity_date, 'm/d/Y'),
                                        utfEncode($obj->issued_to),
                                        utfEncode($obj->loc_description),
                                        $obj->booklet_start_series,
                                        $obj->booklet_end_series,
                                        utfEncode($obj->remarks),
                                        utfEncode($obj->createdby),
                                        dateFormat($obj->created_date, "m/d/Y h:i:s A"),
                                        utfEncode($obj->updatedby),
                                        dateFormat($obj->updated_date, "m/d/Y h:i:s A")
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
          header('Content-Disposition: attachment; filename="waybill-booklet-issuance-'.date('Ymdhis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');

?>