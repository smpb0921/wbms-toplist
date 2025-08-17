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

     $sheet->setTitle('Origin Destination Port');


     $qry =       " select origin_destination_port.id,
                           origin_destination_port.code, 
                           origin_destination_port.description,
                           origin_destination_port.created_date,
                           concat(cuser.first_name,' ',cuser.last_name) as created_by,
                           origin_destination_port.updated_date,
                           concat(uuser.first_name,' ',uuser.last_name) as updated_by,
                           origin_destination_port.country_id,
                           origin_destination_port.zone_id,
                           zone.code as zone,
                           country_name
                    from origin_destination_port
                    left join user as cuser on cuser.id=origin_destination_port.created_by
                    left join user as uuser on uuser.id=origin_destination_port.updated_by
                    left join countries on countries.id=origin_destination_port.country_id
                    left join zone on zone.id=origin_destination_port.zone_id

                   ";


        $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'PORT CODE',
                                'DESCRIPTION',
                                'ZONE CODE',
                                'COUNTRY'
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
                                        utfEncode($obj->code),
                                        utfEncode($obj->description),
                                        utfEncode($obj->zone),
                                        utfEncode($obj->country_name)
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
          header('Content-Disposition: attachment; filename="origin-destination-port-as-of-'.date('Ymdhis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');

?>