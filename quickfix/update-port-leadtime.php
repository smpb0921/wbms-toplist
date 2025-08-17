<?php

      

        include("../config/connection.php");
        include("../config/functions.php");
        

        require_once '../resources/spout/vendor/box/spout/src/Spout/Autoloader/autoload.php';
        use Box\Spout\Reader\ReaderFactory;
        use Box\Spout\Common\Type;

        $reader = ReaderFactory::create(Type::XLSX);

        $reader->open('excel/city-leadtime.xlsx');


        $headerColumns = array(
                                    'REGION', //A -> 0
                                    'CITY',
                                    'LEAD TIME'
                         

                                );

        $line = 1;
        $headerformatflag = 1;
        //CHECK EXCEL DATA
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                
                
                if($line==1){//check header format
                    //$cells = $row->getCells();
                    echo "Checking Header format....<br>";

                    for ($i = 0; $i<count($headerColumns); $i++) {
                        echo strtoupper(trim($headerColumns[$i]))."---->".strtoupper(trim($row[$i]))."<br>"; 

                        if(strtoupper(trim($row[$i]))!=strtoupper(trim($headerColumns[$i]))){
                                $headerformatflag = 0;
                                echo "Invalid Header Format: ".strtoupper(trim($row[$i]))." ----> ".strtoupper(trim($headerColumns[$i]))."<br>";
                        }
                    }
                       
                }
                

                if($headerformatflag!=1){
                    echo "Invalid format";
                    break;  
                }
                else if($line>1){
                    $region = trim($row[0]);
                    $city = trim($row[1]);
                    $leadtime = trim($row[2]);

                    $rs = query("
                                select origin_destination_port.description as region,
                                       origin_destination_port.lead_time
                               from origin_destination_port
                               where origin_destination_port.description='$region'

                            ");
                    if(getNumRows($rs)>0){
                        query("update origin_destination_port 
                               set origin_destination_port.lead_time='$leadtime'
                               where origin_destination_port.description='$region' 
                               ");

                        echo "Updated: Region->$region | Lead_time->$leadtime <br>";
                    }
                    else{
                        
                        echo "Not Found: Region->$region | Lead_time->$leadtime <br>";

                    }
                }

                $line++;

            }
        }

        

        $reader->close();












?>