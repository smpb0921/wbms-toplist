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


    $type = isset($_GET['type'])?escapeString($_GET['type']):'';


    

    if($type=='PORT'){


    

        $objExcel = new PHPExcel();
        $sheet = $objExcel->getActiveSheet();

        $sheet->setTitle('Origin and Destination Port');


        $qry =       "    select    origin_destination_port.id, 
                                    origin_destination_port.code,
                                    origin_destination_port.description,
                                    origin_destination_port.created_by,
                                    origin_destination_port.created_date,
                                    origin_destination_port.updated_by,
                                    origin_destination_port.updated_date,
                                    origin_destination_port.country_id,
                                    countries.country_name,
                                    concat(c.first_name,' ',c.last_name) as createdby,
                                    concat(u.first_name,' ',u.last_name) as updatedby
                             from origin_destination_port
                             left join countries on countries.id=origin_destination_port.country_id  
                             left join user as c on c.id=origin_destination_port.created_by
                             left join user as u on u.id=origin_destination_port.updated_by                           
                       ";
        
      
         //echo $qry;

         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'CODE',
                                'DESCRIPTION',
                                'COUNTRY',
                                'CREATED BY',
                                'CREATED DATE',
                                'UPDATED BY',
                                'UPDATED DATE'


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
                                        utfEncode($obj->code),
                                        utfEncode($obj->description),
                                        utfEncode($obj->country_name),
                                        utfEncode($obj->createdby),
                                        dateFormat($obj->created_date,'m/d/Y'),
                                        utfEncode($obj->updatedby),
                                        dateFormat($obj->updated_date,'m/d/Y')
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
          header('Content-Disposition: attachment; filename="origin-destination-port-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');

    }
    else if($type=='ROUTE'){

        
         $objExcel = new PHPExcel();
         $sheet = $objExcel->getActiveSheet();

         $sheet->setTitle('Destination Route');


         $qry =      "    select    destination_route.id, 
                                    destination_route.code,
                                    destination_route.description,
                                    destination_route.created_by,
                                    destination_route.created_date,
                                    destination_route.updated_by,
                                    destination_route.updated_date,
                                    concat(c.first_name,' ',c.last_name) as createdby,
                                    concat(u.first_name,' ',u.last_name) as updatedby
                             from destination_route                           
                             left join user as c on c.id=destination_route.created_by
                             left join user as u on u.id=destination_route.updated_by                                
                       ";
        
      
         //echo $qry;

         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'CODE',
                                'DESCRIPTION',
                                'CREATED BY',
                                'CREATED DATE',
                                'UPDATED BY',
                                'UPDATED DATE'


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
                                        utfEncode($obj->code),
                                        utfEncode($obj->description),
                                        utfEncode($obj->createdby),
                                        dateFormat($obj->created_date,'m/d/Y'),
                                        utfEncode($obj->updatedby),
                                        dateFormat($obj->updated_date,'m/d/Y')
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
          header('Content-Disposition: attachment; filename="destination-route-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');


    }
    else if($type=='SERVICES'){

        
         $objExcel = new PHPExcel();
         $sheet = $objExcel->getActiveSheet();

         $sheet->setTitle('Services');


         $qry =      "    select    services.id, 
                                    services.code,
                                    services.description,
                                    services.created_by,
                                    services.created_date,
                                    services.updated_by,
                                    services.updated_date,
                                    concat(c.first_name,' ',c.last_name) as createdby,
                                    concat(u.first_name,' ',u.last_name) as updatedby
                             from services                           
                             left join user as c on c.id=services.created_by
                             left join user as u on u.id=services.updated_by                                
                       ";
        
      
         //echo $qry;

         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'CODE',
                                'DESCRIPTION',
                                'CREATED BY',
                                'CREATED DATE',
                                'UPDATED BY',
                                'UPDATED DATE'


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
                                        utfEncode($obj->code),
                                        utfEncode($obj->description),
                                        utfEncode($obj->createdby),
                                        dateFormat($obj->created_date,'m/d/Y'),
                                        utfEncode($obj->updatedby),
                                        dateFormat($obj->updated_date,'m/d/Y')
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
          header('Content-Disposition: attachment; filename="services-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');


    }
    else if($type=='MODE'){

        
         $objExcel = new PHPExcel();
         $sheet = $objExcel->getActiveSheet();

         $sheet->setTitle('Mode of Transport');


         $qry =      "    select    mode_of_transport.id, 
                                    mode_of_transport.code,
                                    mode_of_transport.description,
                                    mode_of_transport.created_by,
                                    mode_of_transport.created_date,
                                    mode_of_transport.updated_by,
                                    mode_of_transport.updated_date,
                                    concat(c.first_name,' ',c.last_name) as createdby,
                                    concat(u.first_name,' ',u.last_name) as updatedby
                             from mode_of_transport                           
                             left join user as c on c.id=mode_of_transport.created_by
                             left join user as u on u.id=mode_of_transport.updated_by                                
                       ";
        
      
         //echo $qry;

         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'CODE',
                                'DESCRIPTION',
                                'CREATED BY',
                                'CREATED DATE',
                                'UPDATED BY',
                                'UPDATED DATE'


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
                                        utfEncode($obj->code),
                                        utfEncode($obj->description),
                                        utfEncode($obj->createdby),
                                        dateFormat($obj->created_date,'m/d/Y'),
                                        utfEncode($obj->updatedby),
                                        dateFormat($obj->updated_date,'m/d/Y')
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
          header('Content-Disposition: attachment; filename="mode-of-transport-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');


    }
    else if($type=='HANDLING'){

        
         $objExcel = new PHPExcel();
         $sheet = $objExcel->getActiveSheet();

         $sheet->setTitle('Handling Instruction');


         $qry =      "    select    handling_instruction.id, 
                                    handling_instruction.code,
                                    handling_instruction.description,
                                    handling_instruction.created_by,
                                    handling_instruction.created_date,
                                    handling_instruction.updated_by,
                                    handling_instruction.updated_date,
                                    concat(c.first_name,' ',c.last_name) as createdby,
                                    concat(u.first_name,' ',u.last_name) as updatedby
                             from handling_instruction                           
                             left join user as c on c.id=handling_instruction.created_by
                             left join user as u on u.id=handling_instruction.updated_by                                
                       ";
        
      
         //echo $qry;

         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'CODE',
                                'DESCRIPTION',
                                'CREATED BY',
                                'CREATED DATE',
                                'UPDATED BY',
                                'UPDATED DATE'


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
                                        utfEncode($obj->code),
                                        utfEncode($obj->description),
                                        utfEncode($obj->createdby),
                                        dateFormat($obj->created_date,'m/d/Y'),
                                        utfEncode($obj->updatedby),
                                        dateFormat($obj->updated_date,'m/d/Y')
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
          header('Content-Disposition: attachment; filename="handling-instruction-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');


    }
    else if($type=='DOCUMENT'){

        
         $objExcel = new PHPExcel();
         $sheet = $objExcel->getActiveSheet();

         $sheet->setTitle('Accompanying Document');


         $qry =      "    select    accompanying_documents.id, 
                                    accompanying_documents.code,
                                    accompanying_documents.description,
                                    accompanying_documents.created_by,
                                    accompanying_documents.created_date,
                                    accompanying_documents.updated_by,
                                    accompanying_documents.updated_date,
                                    concat(c.first_name,' ',c.last_name) as createdby,
                                    concat(u.first_name,' ',u.last_name) as updatedby
                             from accompanying_documents                           
                             left join user as c on c.id=accompanying_documents.created_by
                             left join user as u on u.id=accompanying_documents.updated_by                                
                       ";
        
      
         //echo $qry;

         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'CODE',
                                'DESCRIPTION',
                                'CREATED BY',
                                'CREATED DATE',
                                'UPDATED BY',
                                'UPDATED DATE'


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
                                        utfEncode($obj->code),
                                        utfEncode($obj->description),
                                        utfEncode($obj->createdby),
                                        dateFormat($obj->created_date,'m/d/Y'),
                                        utfEncode($obj->updatedby),
                                        dateFormat($obj->updated_date,'m/d/Y')
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
          header('Content-Disposition: attachment; filename="accompanying-document-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');


    }
    else if($type=='DELIVERY'){

        
         $objExcel = new PHPExcel();
         $sheet = $objExcel->getActiveSheet();

         $sheet->setTitle('Delivery Instruction');


         $qry =      "    select    delivery_instruction.id, 
                                    delivery_instruction.code,
                                    delivery_instruction.description,
                                    delivery_instruction.created_by,
                                    delivery_instruction.created_date,
                                    delivery_instruction.updated_by,
                                    delivery_instruction.updated_date,
                                    concat(c.first_name,' ',c.last_name) as createdby,
                                    concat(u.first_name,' ',u.last_name) as updatedby
                             from delivery_instruction                           
                             left join user as c on c.id=delivery_instruction.created_by
                             left join user as u on u.id=delivery_instruction.updated_by                                
                       ";
        
      
         //echo $qry;

         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'CODE',
                                'DESCRIPTION',
                                'CREATED BY',
                                'CREATED DATE',
                                'UPDATED BY',
                                'UPDATED DATE'


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
                                        utfEncode($obj->code),
                                        utfEncode($obj->description),
                                        utfEncode($obj->createdby),
                                        dateFormat($obj->created_date,'m/d/Y'),
                                        utfEncode($obj->updatedby),
                                        dateFormat($obj->updated_date,'m/d/Y')
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
          header('Content-Disposition: attachment; filename="delivery-instruction-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');


    }
    else if($type=='CARRIER'){

        
         $objExcel = new PHPExcel();
         $sheet = $objExcel->getActiveSheet();

         $sheet->setTitle('Carrier');


         $qry =      "    select    carrier.id, 
                                    carrier.code,
                                    carrier.description,
                                    carrier.created_by,
                                    carrier.created_date,
                                    carrier.updated_by,
                                    carrier.updated_date,
                                    concat(c.first_name,' ',c.last_name) as createdby,
                                    concat(u.first_name,' ',u.last_name) as updatedby
                             from carrier                           
                             left join user as c on c.id=carrier.created_by
                             left join user as u on u.id=carrier.updated_by                                
                       ";
        
      
         //echo $qry;

         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'CODE',
                                'DESCRIPTION',
                                'CREATED BY',
                                'CREATED DATE',
                                'UPDATED BY',
                                'UPDATED DATE'


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
                                        utfEncode($obj->code),
                                        utfEncode($obj->description),
                                        utfEncode($obj->createdby),
                                        dateFormat($obj->created_date,'m/d/Y'),
                                        utfEncode($obj->updatedby),
                                        dateFormat($obj->updated_date,'m/d/Y')
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
          header('Content-Disposition: attachment; filename="carrier-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');


    }
    else if($type=='LOCATION'){

        
         $objExcel = new PHPExcel();
         $sheet = $objExcel->getActiveSheet();

         $sheet->setTitle('Location');


         $qry =      "    select    location.id, 
                                    location.code,
                                    location.description,
                                    location.created_by,
                                    location.created_date,
                                    location.updated_by,
                                    location.updated_date,
                                    concat(c.first_name,' ',c.last_name) as createdby,
                                    concat(u.first_name,' ',u.last_name) as updatedby
                             from location                           
                             left join user as c on c.id=location.created_by
                             left join user as u on u.id=location.updated_by                                
                       ";
        
      
         //echo $qry;

         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'CODE',
                                'DESCRIPTION',
                                'CREATED BY',
                                'CREATED DATE',
                                'UPDATED BY',
                                'UPDATED DATE'


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
                                        utfEncode($obj->code),
                                        utfEncode($obj->description),
                                        utfEncode($obj->createdby),
                                        dateFormat($obj->created_date,'m/d/Y'),
                                        utfEncode($obj->updatedby),
                                        dateFormat($obj->updated_date,'m/d/Y')
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
          header('Content-Disposition: attachment; filename="location-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');


    }
    else if($type=='MOVEMENT'){

        
         $objExcel = new PHPExcel();
         $sheet = $objExcel->getActiveSheet();

         $sheet->setTitle('Movement Type');


         $qry =      "    select    movement_type.id, 
                                    movement_type.code,
                                    movement_type.description,
                                    movement_type.created_by,
                                    movement_type.created_date,
                                    movement_type.updated_by,
                                    movement_type.updated_date,
                                    concat(c.first_name,' ',c.last_name) as createdby,
                                    concat(u.first_name,' ',u.last_name) as updatedby
                             from movement_type                           
                             left join user as c on c.id=movement_type.created_by
                             left join user as u on u.id=movement_type.updated_by                                
                       ";
        
      
         //echo $qry;

         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'CODE',
                                'DESCRIPTION',
                                'CREATED BY',
                                'CREATED DATE',
                                'UPDATED BY',
                                'UPDATED DATE'


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
                                        utfEncode($obj->code),
                                        utfEncode($obj->description),
                                        utfEncode($obj->createdby),
                                        dateFormat($obj->created_date,'m/d/Y'),
                                        utfEncode($obj->updatedby),
                                        dateFormat($obj->updated_date,'m/d/Y')
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
          header('Content-Disposition: attachment; filename="movement-type-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');


    }
    else if($type=='VEHICLE'){

        
         $objExcel = new PHPExcel();
         $sheet = $objExcel->getActiveSheet();

         $sheet->setTitle('Vehicle Type');


         $qry =      "    select    vehicle_type.id, 
                                    vehicle_type.code,
                                    vehicle_type.description,
                                    vehicle_type.created_by,
                                    vehicle_type.created_date,
                                    vehicle_type.updated_by,
                                    vehicle_type.updated_date,
                                    concat(c.first_name,' ',c.last_name) as createdby,
                                    concat(u.first_name,' ',u.last_name) as updatedby
                             from vehicle_type                           
                             left join user as c on c.id=vehicle_type.created_by
                             left join user as u on u.id=vehicle_type.updated_by                                
                       ";
        
      
         //echo $qry;

         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'CODE',
                                'DESCRIPTION',
                                'CREATED BY',
                                'CREATED DATE',
                                'UPDATED BY',
                                'UPDATED DATE'


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
                                        utfEncode($obj->code),
                                        utfEncode($obj->description),
                                        utfEncode($obj->createdby),
                                        dateFormat($obj->created_date,'m/d/Y'),
                                        utfEncode($obj->updatedby),
                                        dateFormat($obj->updated_date,'m/d/Y')
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
          header('Content-Disposition: attachment; filename="vehicle-type-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');


    }
    else if($type=='UOM'){

        
         $objExcel = new PHPExcel();
         $sheet = $objExcel->getActiveSheet();

         $sheet->setTitle('Unit of Measure');


         $qry =      "    select    unit_of_measure.id, 
                                    unit_of_measure.code,
                                    unit_of_measure.description,
                                    unit_of_measure.created_by,
                                    unit_of_measure.created_date,
                                    unit_of_measure.updated_by,
                                    unit_of_measure.updated_date,
                                    concat(c.first_name,' ',c.last_name) as createdby,
                                    concat(u.first_name,' ',u.last_name) as updatedby
                             from unit_of_measure                           
                             left join user as c on c.id=unit_of_measure.created_by
                             left join user as u on u.id=unit_of_measure.updated_by                                
                       ";
        
      
         //echo $qry;

         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'CODE',
                                'DESCRIPTION',
                                'CREATED BY',
                                'CREATED DATE',
                                'UPDATED BY',
                                'UPDATED DATE'


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
                                        utfEncode($obj->code),
                                        utfEncode($obj->description),
                                        utfEncode($obj->createdby),
                                        dateFormat($obj->created_date,'m/d/Y'),
                                        utfEncode($obj->updatedby),
                                        dateFormat($obj->updated_date,'m/d/Y')
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
          header('Content-Disposition: attachment; filename="unit-of-measure-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');


    }
    else if($type=='PAYMODE'){

        
         $objExcel = new PHPExcel();
         $sheet = $objExcel->getActiveSheet();

         $sheet->setTitle('Pay Mode');


         $qry =      "    select    pay_mode.id, 
                                    pay_mode.code,
                                    pay_mode.description,
                                    pay_mode.created_by,
                                    pay_mode.created_date,
                                    pay_mode.updated_by,
                                    pay_mode.updated_date,
                                    concat(c.first_name,' ',c.last_name) as createdby,
                                    concat(u.first_name,' ',u.last_name) as updatedby
                             from pay_mode                           
                             left join user as c on c.id=pay_mode.created_by
                             left join user as u on u.id=pay_mode.updated_by                                
                       ";
        
      
         //echo $qry;

         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'CODE',
                                'DESCRIPTION',
                                'CREATED BY',
                                'CREATED DATE',
                                'UPDATED BY',
                                'UPDATED DATE'


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
                                        utfEncode($obj->code),
                                        utfEncode($obj->description),
                                        utfEncode($obj->createdby),
                                        dateFormat($obj->created_date,'m/d/Y'),
                                        utfEncode($obj->updatedby),
                                        dateFormat($obj->updated_date,'m/d/Y')
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
          header('Content-Disposition: attachment; filename="pay-mode-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');


    }
    else if($type=='ADDRESS'){

        
         $objExcel = new PHPExcel();
         $sheet = $objExcel->getActiveSheet();

         $sheet->setTitle('District City Zip');


         $qry =      "    select    district_city_zipcode.id, 
                                    district_city_zipcode.district_barangay,
                                    district_city_zipcode.city,
                                    district_city_zipcode.zip_code,
                                    origin_destination_port.description as origin_destination_port,
                                    district_city_zipcode.created_by,
                                    district_city_zipcode.created_date,
                                    district_city_zipcode.updated_by,
                                    district_city_zipcode.updated_date,
                                    concat(c.first_name,' ',c.last_name) as createdby,
                                    concat(u.first_name,' ',u.last_name) as updatedby
                             from district_city_zipcode  
                             left join origin_destination_port on origin_destination_port.id=district_city_zipcode.origin_destination_port_id                         
                             left join user as c on c.id=district_city_zipcode.created_by
                             left join user as u on u.id=district_city_zipcode.updated_by                                
                       ";
        
      
         //echo $qry;

         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'DISTRICT/BARANGAY',
                                'CITY',
                                'ZIP CODE',
                                'ORIGIN/DESTINATION PORT',
                                'CREATED BY',
                                'CREATED DATE',
                                'UPDATED BY',
                                'UPDATED DATE'


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
                                        utfEncode($obj->district_barangay),
                                        utfEncode($obj->city),
                                        utfEncode($obj->zip_code),
                                        utfEncode($obj->origin_destination_port),
                                        utfEncode($obj->createdby),
                                        dateFormat($obj->created_date,'m/d/Y'),
                                        utfEncode($obj->updatedby),
                                        dateFormat($obj->updated_date,'m/d/Y')
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
          header('Content-Disposition: attachment; filename="district-city-zip-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');


    }
    else if($type=='ACCOUNT'){

        
         $objExcel = new PHPExcel();
         $sheet = $objExcel->getActiveSheet();

         $sheet->setTitle('Account Executive');


         $qry =      "    select    account_executive.id, 
                                    account_executive.code,
                                    account_executive.name,
                                    account_executive.email_address,
                                    account_executive.mobile_number,
                                    account_executive.username,
                                    account_executive.created_by,
                                    account_executive.created_date,
                                    account_executive.updated_by,
                                    account_executive.updated_date,
                                    concat(c.first_name,' ',c.last_name) as createdby,
                                    concat(u.first_name,' ',u.last_name) as updatedby
                             from account_executive                           
                             left join user as c on c.id=account_executive.created_by
                             left join user as u on u.id=account_executive.updated_by                                
                       ";
        
      
         //echo $qry;

         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'CODE',
                                'NAME',
                                'EMAIL',
                                'MOBILE',
                                'USERNAME',
                                'CREATED BY',
                                'CREATED DATE',
                                'UPDATED BY',
                                'UPDATED DATE'


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
                                        utfEncode($obj->code),
                                        utfEncode($obj->name),
                                        utfEncode($obj->email_address),
                                        utfEncode($obj->mobile_number),
                                        utfEncode($obj->username),
                                        utfEncode($obj->createdby),
                                        dateFormat($obj->created_date,'m/d/Y'),
                                        utfEncode($obj->updatedby),
                                        dateFormat($obj->updated_date,'m/d/Y')
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
          header('Content-Disposition: attachment; filename="account-executive-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');


    }
    else if($type=='AGENT'){

        
         $objExcel = new PHPExcel();
         $sheet = $objExcel->getActiveSheet();

         $sheet->setTitle('Agent');


         $qry =      "    select    agent.id, 
                                    agent.code,
                                    agent.company_name,
                                    agent.company_street_address,
                                    agent.company_district,
                                    agent.company_city,
                                    agent.company_state_province,
                                    agent.company_zip_code,
                                    agent.company_country,
                                    agent.area,
                                    agent.remarks,
                                    agent.created_by,
                                    agent.created_date,
                                    agent.updated_by,
                                    agent.updated_date,
                                    concat(c.first_name,' ',c.last_name) as createdby,
                                    concat(u.first_name,' ',u.last_name) as updatedby
                             from agent                           
                             left join user as c on c.id=agent.created_by
                             left join user as u on u.id=agent.updated_by  
                             order by company_name asc                              
                       ";
        
      
         //echo $qry;

         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'CODE',
                                'COMPANY NAME',
                                'STREET',
                                'DISTRICT/BARANGAY',
                                'CITY',
                                'ZIP CODE',
                                'REGION',
                                'COUNTRY',
                                'AREA',
                                'REMARKS',
                                'CREATED BY',
                                'CREATED DATE',
                                'UPDATED BY',
                                'UPDATED DATE'


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
                                        utfEncode($obj->code),
                                        utfEncode($obj->company_name),
                                        utfEncode($obj->company_street_address),
                                        utfEncode($obj->company_district),
                                        utfEncode($obj->company_city),
                                        utfEncode($obj->company_zip_code),
                                        utfEncode($obj->company_state_province),
                                        utfEncode($obj->company_country),
                                        utfEncode($obj->area),
                                        utfEncode($obj->remarks),
                                        utfEncode($obj->createdby),
                                        dateFormat($obj->created_date,'m/d/Y'),
                                        utfEncode($obj->updatedby),
                                        dateFormat($obj->updated_date,'m/d/Y')
                                    );

                $col = 'A';
                for($i=0;$i<count($detailcell);$i++){
                    $sheet->setCellValue($col.$row,$detailcell[$i]);
                    $col++;
                }
               
                
                $line++;
                $row++;
         }



        //END OF FIRST SHEET

        $sheet = $objExcel->createSheet();

        $sheet->setTitle('Agent Contact');


        $qry =      "     select    agent_contact.id, 
                                    agent.company_name,
                                    agent_contact.contact_name,
                                    agent_contact.phone_number,
                                    agent_contact.email_address,
                                    agent_contact.mobile_number,
                                    agent_contact.created_date,
                                    CASE 
                                    WHEN agent_contact.default_flag=1 then 'YES'
                                    WHEN agent_contact.default_flag=0 then 'NO'
                                    ELSE null
                                    END as 'default_flag'
                             from agent_contact                           
                             left join agent on agent.id=agent_contact.agent_id
                             order by company_name asc, agent_contact.default_flag desc                     
                       ";
        
      
         //echo $qry;

         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'AGENT',
                                'CONTACT',
                                'PHONE',
                                'EMAIL',
                                'MOBILE',
                                'DEFAULT FLAG',
                                'CREATED DATE'

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
                                        utfEncode($obj->company_name),
                                        utfEncode($obj->contact_name),
                                        utfEncode($obj->phone_number),
                                        utfEncode($obj->email_address),
                                        utfEncode($obj->mobile_number),
                                        utfEncode($obj->default_flag),
                                        dateFormat($obj->created_date,'m/d/Y')
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
          header('Content-Disposition: attachment; filename="agent-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');


    }
    else if($type=='CONSIGNEE'){

        
        $objExcel = new PHPExcel();
        $sheet = $objExcel->getActiveSheet();

        $sheet->setTitle('Consignee');

        $qry =      "    select     consignee.id, 
                                    consignee.account_number,
                                    consignee.account_name,
                                    consignee.company_name,
                                    consignee.company_street_address,
                                    consignee.company_district,
                                    consignee.company_city,
                                    consignee.company_state_province,
                                    consignee.company_zip_code,
                                    consignee.company_country,
                                    CASE 
                                    WHEN consignee.inactive_flag=1 then 'INACTIVE'
                                    WHEN consignee.inactive_flag=0 then 'ACTIVE'
                                    ELSE null
                                    END as active_flag,
                                    consignee.created_by,
                                    consignee.created_date,
                                    consignee.updated_by,
                                    consignee.updated_date,
                                    concat(c.first_name,' ',c.last_name) as createdby,
                                    concat(u.first_name,' ',u.last_name) as updatedby
                             from consignee                           
                             left join user as c on c.id=consignee.created_by
                             left join user as u on u.id=consignee.updated_by  
                             order by account_name asc                              
                       ";
        
      
         //echo $qry;

         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'ACCOUNT NUMBER',
                                'ACCOUNT NAME',
                                'COMPANY NAME',
                                'STREET',
                                'DISTRICT/BARANGAY',
                                'CITY',
                                'ZIP CODE',
                                'REGION',
                                'COUNTRY',
                                'FLAG',
                                'CREATED BY',
                                'CREATED DATE',
                                'UPDATED BY',
                                'UPDATED DATE'


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
                                        utfEncode($obj->account_number),
                                        utfEncode($obj->account_name),
                                        utfEncode($obj->company_name),
                                        utfEncode($obj->company_street_address),
                                        utfEncode($obj->company_district),
                                        utfEncode($obj->company_city),
                                        utfEncode($obj->company_zip_code),
                                        utfEncode($obj->company_state_province),
                                        utfEncode($obj->company_country),
                                        utfEncode($obj->active_flag),
                                        utfEncode($obj->createdby),
                                        dateFormat($obj->created_date,'m/d/Y'),
                                        utfEncode($obj->updatedby),
                                        dateFormat($obj->updated_date,'m/d/Y')
                                    );

                $col = 'A';
                for($i=0;$i<count($detailcell);$i++){
                    $sheet->setCellValue($col.$row,$detailcell[$i]);
                    $col++;
                }
               
                
                $line++;
                $row++;
         }



        //END OF FIRST SHEET

        
        $sheet = $objExcel->createSheet();

        $sheet->setTitle('Consignee Contact');


        $qry =      "     select    consignee_contact.id, 
                                    consignee.account_name,
                                    consignee_contact.contact_name,
                                    consignee_contact.phone_number,
                                    consignee_contact.email_address,
                                    consignee_contact.mobile_number,
                                    consignee_contact.created_date,
                                    CASE 
                                    WHEN consignee_contact.default_flag=1 then 'YES'
                                    WHEN consignee_contact.default_flag=0 then 'NO'
                                    ELSE null
                                    END as 'default_flag',
                                    CASE 
                                    WHEN consignee_contact.send_sms_flag=1 then 'YES'
                                    WHEN consignee_contact.send_sms_flag=0 then 'NO'
                                    ELSE null
                                    END as 'sms_flag',
                                    CASE 
                                    WHEN consignee_contact.send_email_flag=1 then 'YES'
                                    WHEN consignee_contact.send_email_flag=0 then 'NO'
                                    ELSE null
                                    END as 'email_flag'
                             from consignee_contact                           
                             left join consignee on consignee.id=consignee_contact.consignee_id
                             order by account_name asc, consignee_contact.default_flag desc                     
                       ";
        
      
         //echo $qry;

         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'CONSIGNEE',
                                'CONTACT',
                                'PHONE',
                                'EMAIL',
                                'MOBILE',
                                'DEFAULT FLAG',
                                'SMS FLAG',
                                'EMAIL FLAG',
                                'CREATED DATE'

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
                                        utfEncode($obj->account_name),
                                        utfEncode($obj->contact_name),
                                        utfEncode($obj->phone_number),
                                        utfEncode($obj->email_address),
                                        utfEncode($obj->mobile_number),
                                        utfEncode($obj->default_flag),
                                        utfEncode($obj->sms_flag),
                                        utfEncode($obj->email_flag),
                                        dateFormat($obj->created_date,'m/d/Y')
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
          header('Content-Disposition: attachment; filename="consignee-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');


    }
    else if($type=='SHIPPER'){

        
        $objExcel = new PHPExcel();
        $sheet = $objExcel->getActiveSheet();

        $sheet->setTitle('Shipper');

        $qry =      "    select     shipper.id, 
                                    shipper.account_number,
                                    shipper.account_name,
                                    shipper.company_name,
                                    shipper.company_street_address,
                                    shipper.company_district,
                                    shipper.company_city,
                                    shipper.company_state_province,
                                    shipper.company_zip_code,
                                    shipper.company_country,
                                    shipper.billing_street_address,
                                    shipper.billing_district,
                                    shipper.billing_city,
                                    shipper.billing_state_province,
                                    shipper.billing_zip_code,
                                    shipper.billing_country,
                                    shipper.billing_in_charge,
                                    shipper.account_executive,
                                    shipper.non_pod_flag,
                                    shipper.vat_flag,
                                    shipper.on_hold_flag,
                                    shipper.pay_mode_id,
                                    shipper.business_style,
                                    shipper.credit_limit,
                                    shipper.credit_term,
                                    shipper.tin,
                                    shipper.line_of_business,
                                    shipper.collection_contact_person,
                                    shipper.billing_cut_off,
                                    shipper.collection_day,
                                    shipper.collection_location,
                                    shipper.pod_instruction,
                                    shipper.status,

                                    CASE 
                                    WHEN shipper.non_pod_flag=1 then 'YES'
                                    WHEN shipper.non_pod_flag=0 then 'NO'
                                    ELSE null
                                    END as nonpodflag,

                                    CASE 
                                    WHEN shipper.vat_flag=1 then 'YES'
                                    WHEN shipper.vat_flag=0 then 'NO'
                                    ELSE null
                                    END as vatflag,

                                    CASE 
                                    WHEN shipper.on_hold_flag=1 then 'YES'
                                    WHEN shipper.on_hold_flag=0 then 'NO'
                                    ELSE null
                                    END as onholdflag,

                                    CASE 
                                    WHEN shipper.inactive_flag=1 then 'INACTIVE'
                                    WHEN shipper.inactive_flag=0 then 'ACTIVE'
                                    ELSE null
                                    END as flag,

                                    shipper.created_by,
                                    shipper.created_date,
                                    shipper.updated_by,
                                    shipper.updated_date,
                                    concat(c.first_name,' ',c.last_name) as createdby,
                                    concat(u.first_name,' ',u.last_name) as updatedby,
                                    concat(incharge.first_name,' ',incharge.last_name) as billingincharge,
                                    account_executive.name as accountexecutive,
                                    pay_mode.description as paymode
                             from shipper                           
                             left join user as c on c.id=shipper.created_by
                             left join user as u on u.id=shipper.updated_by  
                             left join user as incharge on incharge.id=shipper.billing_in_charge
                             left join account_executive on account_executive.id=shipper.account_executive
                             left join pay_mode on pay_mode.id=shipper.pay_mode_id
                             order by account_name asc                              
                       ";
        
      
         //echo $qry;

         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'ACCOUNT NUMBER',
                                'ACCOUNT NAME',
                                'COMPANY NAME',
                                'FLAG',
                                'STATUS',
                                'STREET',
                                'DISTRICT/BARANGAY',
                                'CITY',
                                'ZIP CODE',
                                'REGION',
                                'COUNTRY',
                                'BILLING STREET',
                                'BILLING DISTRICT/BARANGAY',
                                'BILLING CITY',
                                'BILLING ZIP CODE',
                                'BILLING REGION',
                                'BILLING COUNTRY',
                                'BILLING IN CHARGE',
                                'ACCOUNT EXECUTIVE',
                                'NON-POD FLAG',
                                'VAT FLAG',
                                'ON HOLD FLAG',
                                'PAY MODE',
                                'TIN',
                                'BUSINESS STYLE',
                                'LINE OF BUSINESS',
                                'CREDIT LIMIT',
                                'CREDIT TERM',
                                'BILLING CUT OFF',
                                'COLLECTION CONTACT PERSON',
                                'COLLECTION DAY',
                                'COLLECTION LOCATION',
                                'POD INSTRUCTION',
                                'CREATED BY',
                                'CREATED DATE',
                                'UPDATED BY',
                                'UPDATED DATE'


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
                                        utfEncode($obj->account_number),
                                        utfEncode($obj->account_name),
                                        utfEncode($obj->company_name),
                                        utfEncode($obj->flag),
                                        utfEncode($obj->status),
                                        utfEncode($obj->company_street_address),
                                        utfEncode($obj->company_district),
                                        utfEncode($obj->company_city),
                                        utfEncode($obj->company_zip_code),
                                        utfEncode($obj->company_state_province),
                                        utfEncode($obj->company_country),
                                        utfEncode($obj->billing_street_address),
                                        utfEncode($obj->billing_district),
                                        utfEncode($obj->billing_city),
                                        utfEncode($obj->billing_zip_code),
                                        utfEncode($obj->billing_state_province),
                                        utfEncode($obj->billing_country),
                                        utfEncode($obj->billingincharge),
                                        utfEncode($obj->accountexecutive),
                                        utfEncode($obj->nonpodflag),
                                        utfEncode($obj->vatflag),
                                        utfEncode($obj->onholdflag),
                                        utfEncode($obj->paymode),
                                        utfEncode($obj->tin),
                                        utfEncode($obj->business_style),
                                        utfEncode($obj->line_of_business),
                                        utfEncode($obj->credit_limit),
                                        utfEncode($obj->credit_term),
                                        utfEncode($obj->billing_cut_off),
                                        utfEncode($obj->collection_contact_person),
                                        utfEncode($obj->collection_day),
                                        utfEncode($obj->collection_location),
                                        utfEncode($obj->pod_instruction),
                                        utfEncode($obj->createdby),
                                        dateFormat($obj->created_date,'m/d/Y'),
                                        utfEncode($obj->updatedby),
                                        dateFormat($obj->updated_date,'m/d/Y')
                                    );

                $col = 'A';
                for($i=0;$i<count($detailcell);$i++){
                    $sheet->setCellValue($col.$row,$detailcell[$i]);
                    $col++;
                }
               
                
                $line++;
                $row++;
         }



        //END OF FIRST SHEET

        
        $sheet = $objExcel->createSheet();

        $sheet->setTitle('Shipper Contact');


        $qry =      "     select    shipper_contact.id, 
                                    shipper.account_number,
                                    shipper.account_name,
                                    shipper_contact.contact_name,
                                    shipper_contact.phone_number,
                                    shipper_contact.email_address,
                                    shipper_contact.mobile_number,
                                    shipper_contact.created_date,
                                    CASE 
                                    WHEN shipper_contact.default_flag=1 then 'YES'
                                    WHEN shipper_contact.default_flag=0 then 'NO'
                                    ELSE null
                                    END as 'default_flag',
                                    CASE 
                                    WHEN shipper_contact.send_sms_flag=1 then 'YES'
                                    WHEN shipper_contact.send_sms_flag=0 then 'NO'
                                    ELSE null
                                    END as 'sms_flag',
                                    CASE 
                                    WHEN shipper_contact.send_email_flag=1 then 'YES'
                                    WHEN shipper_contact.send_email_flag=0 then 'NO'
                                    ELSE null
                                    END as 'email_flag'
                             from shipper_contact                           
                             left join shipper on shipper.id=shipper_contact.shipper_id
                             order by shipper.account_name asc, shipper_contact.default_flag desc                     
                       ";
        
      
         //echo $qry;

         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'SHIPPER ACCOUNT NO.',
                                'SHIPPER NAME',
                                'CONTACT',
                                'PHONE',
                                'EMAIL',
                                'MOBILE',
                                'DEFAULT FLAG',
                                'SMS FLAG',
                                'EMAIL FLAG',
                                'CREATED DATE'

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
                                        utfEncode($obj->account_number),
                                        utfEncode($obj->account_name),
                                        utfEncode($obj->contact_name),
                                        utfEncode($obj->phone_number),
                                        utfEncode($obj->email_address),
                                        utfEncode($obj->mobile_number),
                                        utfEncode($obj->default_flag),
                                        utfEncode($obj->sms_flag),
                                        utfEncode($obj->email_flag),
                                        dateFormat($obj->created_date,'m/d/Y')
                                    );

                $col = 'A';
                for($i=0;$i<count($detailcell);$i++){
                    $sheet->setCellValue($col.$row,$detailcell[$i]);
                    $col++;
                }
               
                
                $line++;
                $row++;
         }

         //END OF SECOND SHEET

        $sheet = $objExcel->createSheet();

        $sheet->setTitle('Shipper Pickup Address');


        $qry =      "     select    shipper_pickup_address.id, 
                                    shipper.account_number,
                                    shipper.account_name,
                                    shipper_pickup_address.pickup_street_address,
                                    shipper_pickup_address.pickup_district,
                                    shipper_pickup_address.pickup_city,
                                    shipper_pickup_address.pickup_zip_code,
                                    shipper_pickup_address.pickup_state_province,
                                    shipper_pickup_address.pickup_country,
                                    shipper_pickup_address.created_date,
                                    CASE 
                                    WHEN shipper_pickup_address.default_flag=1 then 'YES'
                                    WHEN shipper_pickup_address.default_flag=0 then 'NO'
                                    ELSE null
                                    END as 'default_flag'
                                   
                             from shipper_pickup_address                           
                             left join shipper on shipper.id=shipper_pickup_address.shipper_id
                             order by shipper.account_name asc, shipper_pickup_address.default_flag desc                     
                       ";
        
      
         //echo $qry;

         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'LINE',
                                'SYSTEM ID',
                                'SHIPPER ACCOUNT NO.',
                                'SHIPPER NAME',
                                'STREET',
                                'DISTRICT/BARANGAY',
                                'CITY',
                                'ZIP CODE',
                                'COUNTRY',
                                'DEFAULT FLAG',
                                'CREATED DATE'

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
                                        utfEncode($obj->account_number),
                                        utfEncode($obj->account_name),
                                        utfEncode($obj->pickup_street_address),
                                        utfEncode($obj->pickup_district),
                                        utfEncode($obj->pickup_city),
                                        utfEncode($obj->pickup_zip_code),
                                        utfEncode($obj->pickup_country),
                                        utfEncode($obj->default_flag),
                                        dateFormat($obj->created_date,'m/d/Y')
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
          header('Content-Disposition: attachment; filename="shipper-'.date('YmdHis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');


    }


      

?>