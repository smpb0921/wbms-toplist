<?php

      

        include("../config/connection.php");
        include("../config/functions.php");
        

        require_once '../resources/spout/vendor/box/spout/src/Spout/Autoloader/autoload.php';
        use Box\Spout\Reader\ReaderFactory;
        use Box\Spout\Common\Type;

        $reader = ReaderFactory::create(Type::XLSX);

        $reader->open('excel/consignee-idnumber.xlsx');


        $headerColumns = array(
                                    'ACCOUNT NUMBER', //A -> 0
                                    'ACCOUNT NAME',
                                    'ID NUMBER'
                         

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
                    $accountnumber = trim($row[0]);
                    $accountname = trim($row[1]);
                    $idnumber = trim($row[2]);

                    $rs = query("
                                select consignee.account_number,
                                       consignee.account_name,
                                       consignee.id_number
                               from consignee
                               where consignee.account_number='$accountnumber' 

                            ");
                    if(getNumRows($rs)>0){
                        if($idnumber!=''){

                            /*$checkifexistrs = query("
                                                        select consignee.account_number,
                                                               consignee.account_name,
                                                               consignee.id_number
                                                       from consignee
                                                       where consignee.account_number!='$accountnumber' and 
                                                             id_number='$idnumber'



                                                    ");
                            if(getNumRows($checkifexistrs)>0){
                                echo "ID Number already used: Account_number->$accountnumber | Account_name->$accountname | ID_number->$idnumber <br>";
                            }
                            else{*/
                                $checkifrowexistrs = query("select * from consignee
                                       where consignee.account_number='$accountnumber'
                                       ");
                                if(getNumRows($checkifrowexistrs)>0){
                                    query("update consignee  
                                       set consignee.id_number='$idnumber'
                                       where consignee.account_number='$accountnumber'
                                       ");
                                    echo "Updated: Account_number->$accountnumber | Account_name->$accountname | ID_number->$idnumber <br>";
                                }
                            //}
                        }
                        else{
                            echo "ID Number not provided: Account_number->$accountnumber | Account_name->$accountname | ID_number->$idnumber <br>";
                        }
                    }
                    else{
                        
                        echo "Not Found: Account_number->$accountnumber | Account_name->$accountname | ID_number->$idnumber <br>";

                    }
                }

                $line++;

            }
        }

        

        $reader->close();












?>