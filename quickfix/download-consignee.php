<?php

      

        include("../config/connection.php");
        include("../config/functions.php");

        require_once '../resources/spout/vendor/box/spout/src/Spout/Autoloader/autoload.php';
        use Box\Spout\Writer\WriterFactory;
        use Box\Spout\Common\Type;

        use Box\Spout\Writer\Style\Border;
        use Box\Spout\Writer\Style\BorderPart;
        use Box\Spout\Writer\Style\BorderBuilder;
        use Box\Spout\Writer\Style\Color;
        use Box\Spout\Writer\Style\CellAlignment;
        use Box\Spout\Writer\Style\Style;
        use Box\Spout\Writer\Style\StyleBuilder;


       

        $writer = WriterFactory::create(Type::XLSX);
        //$writer->openToFile("waybill-tracking-history-.xlsx");
        $writer->openToBrowser("consignee-".date('Ymd-hisA').".xlsx");

        $whse = '';
        $location = '';
        $item = '';
        $status = '';



        $customqry = "select account_name,
                             account_number,
                             id_number
                      from consignee
                      order by account_name asc

                            


                        
        ";



            

            // Setup sort and search SQL using posted data



        /*$border1 = (new BorderBuilder())
                  ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                  ->setBorderTop(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                  ->build();


        $columnheaderstyle = (new StyleBuilder())
           ->setFontBold()
           ->setFontSize(9)
           ->setBorder($border1)
           ->setBackgroundColor(Color::WHITE)
           ->build();

        $rowstyle = (new StyleBuilder())
           ->setFontSize(9)
          // ->setBorder($border1)
           ->setBackgroundColor(Color::WHITE)
           ->build();

        $rowxsstyle = (new StyleBuilder())
           ->setFontSize(8)
           ->setFontBold()
          // ->setBorder($border1)
           ->setBackgroundColor(Color::WHITE)
           ->build();


       

        $writer->addRowWithStyle(array(
                                    'Date & Time: '.date("M d, Y | h:i:s A")
                        ),$rowxsstyle);*/





     




        

        // HEADER
        $headercell = array(
                                'ACCOUNT NUMBER',
                                'ACCOUNT NAME',
                                'ID NUMBER'

                                

                            );


        //$writer->addRowWithStyle($headercell,$columnheaderstyle);
        $writer->addRow($headercell);
        
      
        

         $rs = mysql_query($customqry);


         //echo $customqry;
         
         
         //DETAILS
         $row = 3;
         $line = 1;
         
         $rowcolor = 'ffffff';
         if(getNumRows($rs)>0){
             while($obj=mysql_fetch_object($rs)){
                    
               


                    $detailcell = array(
                                            utf8_encode($obj->account_number),
                                            utf8_encode($obj->account_name),
                                            utf8_encode($obj->id_number)
                                        );

                    //$writer->addRowWithStyle($detailcell,$rowstyle);
                    $writer->addRow($detailcell);


                   

                    
                   
                    
                    $line++;
                    $row++;
             }
         

                 


                  

        }
        else{

        }

         $writer->close();





  


?>