<?php

      

        include("../../../config/connection.php");
        include("../../../config/checklogin.php");
        include("../../../config/functions.php");

        require_once '../../../resources/spout/vendor/box/spout/src/Spout/Autoloader/autoload.php';
        use Box\Spout\Writer\WriterFactory;

        use Box\Spout\Common\Type;

        //use Box\Spout\Writer\WriterInterface;
        use Box\Spout\Writer\Style\Border;
        use Box\Spout\Writer\Style\BorderPart;
        use Box\Spout\Writer\Style\BorderBuilder;
        use Box\Spout\Writer\Style\Color;
        use Box\Spout\Writer\Style\Style;
        use Box\Spout\Writer\Style\StyleBuilder;
        


        $writer = WriterFactory::create(Type::XLSX);
        //$writer->openToFile("waybill-tracking-history-.xlsx");
        $writer->openToBrowser("courier-daily-delivery-transmittal-".date('YmdHis').".xlsx");

        // HEADER
        $titlestyle = (new StyleBuilder())
           ->setFontBold()
           ->setFontSize(20)
           ->setBackgroundColor(Color::WHITE)
           ->build();

        $boldsm = (new StyleBuilder())
           ->setFontBold()
           ->setFontSize(12)
           ->setBackgroundColor(Color::WHITE)
           ->build();

        $boldmd = (new StyleBuilder())
           ->setFontBold()
           ->setFontSize(14)
           ->setBackgroundColor(Color::WHITE)
           ->build();

        $border = (new BorderBuilder())
                  ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                  ->setBorderLeft(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                  ->setBorderRight(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                  ->setBorderTop(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                  ->build();


        $headerstyle = (new StyleBuilder())
           ->setFontBold()
           ->setFontSize(12)
           ->setBorder($border)
           ->setBackgroundColor(Color::WHITE)
           ->build();


        $rowstyle = (new StyleBuilder())
           ->setFontSize(12)
           ->setBorder($border)
           ->setBackgroundColor(Color::WHITE)
           ->build();



         $title = array(
                                'Courier Daily Delivery Transmittal'

                            );

        $writer->addRowWithStyle($title,$titlestyle);



        


        $writer->addRow(array(''));
        $writer->addRow(array(''));


         $writer->addRowWithStyle(
                            array(
                                    'Courier:',
                                  ),
                            $boldsm
                        );

         $writer->addRowWithStyle(
                            array(
                                    'Destination:',
                                  ),
                            $boldsm
                        );

         $writer->addRowWithStyle(
                            array(
                                    'Transmittal Date:',
                                  ),
                            $boldsm
                        );


        $writer->addRow(array(''));
        $writer->addRow(array(''));



        $writer->addRowWithStyle(
                            array(
                                    'Type of Deliveries:',
                                    '',
                                    '',
                                    'NEW & PENDING DELIVERIES'
                                  ),
                             $boldmd
                        );


        $headercell = array(
                                'NO',
                                'PICK UP DATE',
                                'CBL BOL',
                                'AUTO DR & SALES INVOICE NUMBERS',
                                'DESTINATION',
                                'SHIPPER\'S NAME',
                                'CONTENT',
                                'CONSIGNEE COMPANY / CONSIGNEE NAME',
                                'STATUS',
                                'REMARKS'

                            );

        $writer->addRowWithStyle($headercell,$headerstyle);

        


        
          /////////////////// FILTER VARS ////////////////////////////////
            $having = array();
            $filter = array();
            $courier = isset($_GET['courier'])?escapeString(strtoupper($_GET['courier'])):'';
            $destination = isset($_GET['destination'])?escapeString(strtoupper($_GET['destination'])):'';

            $datefrom = isset($_GET['datefrom'])?escapeString(strtoupper(trim($_GET['datefrom']))):'';
            $dateto = isset($_GET['dateto'])?escapeString(strtoupper(trim($_GET['dateto']))):'';

            if($datefrom!=''){
                $datefrom = date('Y-m-d', strtotime($datefrom));
            }
            if($dateto!=''){
                $dateto = date('Y-m-d', strtotime($dateto));
            }

          
         
          /////////////////// FILTER VARS - END ////////////////////////////////


            if($courier!=''&&$courier!='NULL'){
                //array_push($filter, "purchase_order_header.supplier_id in ($supplier)");
            }

            if($destination!=''&&$destination!='NULL'){
                array_push($filter, "txn_waybill.destination_id in ($destination)");
            }

          



            if($datefrom!=''&&$dateto!=''){
                array_push($filter,"date(txn_waybill.created_date)>='$datefrom' and date(txn_waybill.created_date)<='$dateto'");
            }
            else if($datefrom==''&&$dateto!=''){
                array_push($filter,"date(txn_waybill.created_date) <= '$dateto'");
            }
            else if($datefrom!=''&&$dateto==''){
                 array_push($filter,"date(txn_waybill.created_date) >= '$datefrom'");
            }


         





            $condition = '';
            if(count($filter)>0){
              $condition = ' where '.implode(" and ", $filter);
            }

            $groupbycondition = "";

            $customqry = " 
                            select txn_waybill.id,
                                   txn_waybill.waybill_number,
                                   date_format(txn_waybill.pickup_date,'%m/%d/%Y') as pickupdate,
                                   txn_waybill.invoice_number,
                                   txn_waybill.destination_id,
                                   origin_destination_port.description as destination,
                                   txn_waybill.shipper_company_name,
                                   txn_waybill.remarks,
                                   txn_waybill.consignee_company_name,
                                   txn_waybill.status
                            from txn_waybill
                            left join origin_destination_port on origin_destination_port.id=txn_waybill.destination_id




            ";

            

            // Setup sort and search SQL using posted data
            $sortSql = "order by txn_waybill.waybill_number asc";




            $condition2 = '';
            if(count($having)>0){
              $condition2 = ' having '.implode(" and ", $having);
            }


            $customqry = $customqry.$condition.$groupbycondition.$condition2;
        
      
         //echo $customqry;

         $rs = mysql_query($customqry);

         
         
         //DETAILS
         $row = 3;
         $line = 1;
         $currenttxnnumber = '';
         $rowcolor = 'ffffff';
         $totalcaseqty = 0;
         $totalbaseqty = 0;
         $totalunitcost = 0;
         $totalexcise = 0;
         $totalvat = 0;
         $totalvatdi = 0;
         $totalbrokerage = 0;
         $totalfreight = 0;
         $totalinsurance = 0;
         $totalrcvcost = 0;

        if(mysql_num_rows($rs)>0){
             while($obj=mysql_fetch_object($rs)){

                   

                    $detailcell = array(
                                                $line,
                                                utf8_encode($obj->pickupdate),
                                                utf8_encode($obj->waybill_number),
                                                '',
                                                utf8_encode($obj->destination),
                                                utf8_encode($obj->shipper_company_name),
                                                utf8_encode($obj->remarks),
                                                utf8_encode($obj->consignee_company_name),
                                                utf8_encode($obj->status),
                                                ''

                                            );
                    

                    

                    $writer->addRowWithStyle($detailcell,$rowstyle);

                    
                   
                    
                    $line++;
                    $row++;
             }
           
        }
         

        $writer->close();





	


?>