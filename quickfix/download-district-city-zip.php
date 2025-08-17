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
        $writer->openToBrowser("district-city-zip-".date('Ymd-hisA').".xlsx");

        $whse = '';
        $location = '';
        $item = '';
        $status = '';



        $customqry = "select district_city_zipcode.city,
                             origin_destination_port.description as region,
                             district_city_zipcode.lead_time
                      from district_city_zipcode 
                      left join origin_destination_port on origin_destination_port.id=district_city_zipcode.origin_destination_port_id
                      group by district_city_zipcode.city, 
                               origin_destination_port_id
                      order by origin_destination_port.description, district_city_zipcode.city asc

                            


                        
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
                                'REGION',
                                'CITY',
                                'LEAD TIME'

                                

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
                                            utf8_encode($obj->region),
                                            utf8_encode($obj->city),
                                            utf8_encode($obj->lead_time)
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