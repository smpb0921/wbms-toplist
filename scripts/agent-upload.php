<?php


	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/system-log.class.php");////////
    include("../classes/agent.class.php");
    include("../resources/PHPExcel-1.8/Classes/PHPExcel.php");

    if(isset($_FILES['uploadagentmodal-file'])){

        function convertToText($str){
            $str = trim($str);
            $str = escapeString($str);
            //$str = trim($str,'0');
            return $str;
        }

    	$file = $_FILES['uploadagentmodal-file'];
    	$tmp = $file['tmp_name'];
    	$filename = $file['name'];
        //$ftype = $file['type'];
    	$ftype = strrchr($filename, '.');

    	

    	if($ftype=='.xls'||$ftype=='.csv'||$ftype=='.xlsx'){

    			@$excelReader = PHPExcel_IOFactory::createReaderForFile($tmp);
    			@$excelObj = $excelReader->load($tmp);



                $worksheet = $excelObj->getActiveSheet();
                $lastRow = $worksheet->getHighestRow();
                $lastCol = $worksheet->getHighestColumn();

                $headerColumns = array(
                                        'CODE', //A
                                        'COMPANY NAME', //B
                                        'COMPANY STREET ADDRESS', //C
                                        'COMPANY DISTRICT', //D
                                        'COMPANY CITY', //E
                                        'COMPANY STATE PROVINCE', //F
                                        'COMPANY ZIP CODE', //G
                                        'COMPANY COUNTRY', //H
                                        'AREA', //I
                                        'REMARKS' //J
                                       );
                $checkHeaderInfo = true;
                $col = "A";
                for($i=0;$i<count($headerColumns);$i++){
                        //echo strtoupper(trim($worksheet->getCell($col.'1')->getValue()))."   ==   ".strtoupper(trim($headerColumns[$i]))."<br>";
                        if(strtoupper(trim($worksheet->getCell($col.'1')->getValue()))!=strtoupper(trim($headerColumns[$i]))){

                            $checkHeaderInfo = false;
                        }
                        $col++;
                }

                if($checkHeaderInfo==true){
                    $txnwithrowerror = array();
                    $txnwithouterror = array();
                    $txnrowexist = array();
                    $rowerror = array();
                    $transactedIDs = array();

                    $prclass = new agent();
                    $systemlog = new system_log();

                    for($i=2;$i<=$lastRow;$i++){
                        $code = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                        $companyname = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                        $companystreetaddress = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                        $companydistrict = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));
                        $companycity = convertToText(strtoupper($worksheet->getCell('E'.$i)->getValue()));
                        $companystateprovince = convertToText(strtoupper($worksheet->getCell('F'.$i)->getValue()));
                        $companyzipcode = convertToText(strtoupper($worksheet->getCell('G'.$i)->getValue()));
                        $companycountry = convertToText(strtoupper($worksheet->getCell('H'.$i)->getValue()));
                        $area = convertToText(strtoupper($worksheet->getCell('I'.$i)->getValue()));
                        $remarks = convertToText(strtoupper($worksheet->getCell('J'.$i)->getValue()));

                        // Fixed SQL query - added closing quote after $remarks
                        $checkifexistrs = query("select * from agent 
                                                where code='$code' and
                                                      company_name='$companyname' and
                                                      company_street_address='$companystreetaddress' and
                                                      company_district='$companydistrict' and 
                                                      company_city='$companycity' and
                                                      company_state_province='$companystateprovince' and
                                                      company_zip_code='$companyzipcode' and 
                                                      company_country='$companycountry' and
                                                      area='$area'");

                        if(getNumRows($checkifexistrs)>0){//EXISTING RATE - UPDATE
                            $userid = USERID;
                            $now = date('Y-m-d H:i:s');

                            while($obj=fetch($checkifexistrs)){
                                $systemID = $obj->id;
                            }

                            array_push($txnrowexist, "<b>Details</b>: Line $i - SystemID=$systemID, Code=$code, CompanyName=$companyname, CompanyStreetAddress=$companystreetaddress, CompanyDistrict=$companydistrict, CompanyCity=$companycity, CompanyStateProvince=$companystateprovince, CompanyZipCode=$companyzipcode, CompanyCountry=$companycountry, Area=$area, Remarks=$remarks");

                            $systemlog->logEditedInfo($prclass,$systemID,array($code,$companyname,$companystreetaddress,$companydistrict,$companycity,$companystateprovince,$companyzipcode,$companycountry,$area,$remarks,$userid,$now,'NULL','NULL'),'AGENT','Edited Agent Info (UPLOAD)',$userid,$now);
                            $prclass->update($systemID,array($code,$companyname,$companystreetaddress,$companydistrict,$companycity,$companystateprovince,$companyzipcode,$companycountry,$area,$remarks,$userid,$now,'NULL','NULL'));
                        }
                        // Changed validation - removed $remarks!='' requirement
                        else if($code!=''&&$companyname!=''&&$companystreetaddress!=''&&$companydistrict!=''&&$companycity!=''&&$companystateprovince!=''&&$companyzipcode!=''&&$companycountry!=''&&$area!=''){//NEW RECORD - INSERT
                            $userid = USERID;
                            $now = date('Y-m-d H:i:s');
                            
                            $prclass->insert(array('',$code,$companyname,$companystreetaddress,$companydistrict,$companycity,$companystateprovince,$companyzipcode,$companycountry,$area,$remarks,$userid,$now,'NULL','NULL'));
                            $systemID = $prclass->getInsertId();

                            $systemlog->logAddedInfo($prclass,array($systemID,$code,$companyname,$companystreetaddress,$companydistrict,$companycity,$companystateprovince,$companyzipcode,$companycountry,$area,$remarks,$userid,$now,'NULL','NULL'),'AGENT','New Agent Added (UPLOAD)',$userid,$now);
                            
                            array_push($txnwithouterror, "<b>Details</b>: Line $i - SystemID=$systemID, Code=$code, CompanyName=$companyname, CompanyStreetAddress=$companystreetaddress, CompanyDistrict=$companydistrict, CompanyCity=$companycity, CompanyStateProvince=$companystateprovince, CompanyZipCode=$companyzipcode, CompanyCountry=$companycountry, Area=$area, Remarks=$remarks");
                        }
                        else if($code!=''||$companyname!=''||$companystreetaddress!=''||$companydistrict!=''||$companycity!=''||$companystateprovince!=''||$companyzipcode!=''||$companycountry!=''||$area!=''){//INCOMPLETE DATA - ERROR
                            if($code==''){
                                array_push($rowerror, "Code is required");
                            }
                            if($companyname==''){
                                array_push($rowerror, "Company Name is required");
                            }
                            if($companystreetaddress==''){
                                array_push($rowerror, "Company Street Address is required");
                            }
                            if($companydistrict==''){
                                array_push($rowerror, "Company District is required");
                            }
                            if($companycity==''){
                                array_push($rowerror, "Company City is required");
                            }
                            if($companystateprovince==''){
                                array_push($rowerror, "Company State Province is required");
                            }
                            if($companyzipcode==''){
                                array_push($rowerror, "Company Zipcode is required");
                            }
                            if($companycountry==''){
                                array_push($rowerror, "Company Country is required");
                            }
                            if($area==''){
                                array_push($rowerror, "Area is required");
                            }
                            // Note: Remarks is NOT required, so no validation check added
                            
                            $rowtexterror = implode(", ", $rowerror);
                            array_push($txnwithrowerror, "<b>Line</b>: $i<br><b>Error</b>: $rowtexterror <br><b>Details</b>: Code=$code, CompanyName=$companyname, CompanyStreetAddress=$companystreetaddress, CompanyDistrict=$companydistrict, CompanyCity=$companycity, CompanyStateProvince=$companystateprovince, CompanyZipCode=$companyzipcode, CompanyCountry=$companycountry, Area=$area, Remarks=$remarks");
                            $rowerror = array();
                        }
                    }



                    echo "<table border='1px' cellspacing='0px' style='background-color:#ffdede'>
                            <thead >
                                <tr>    
                                    <th colspan='2'>WITH ERROR (NOT UPLOADED)</th>
                                </tr>
                                <tr>
                                    <th>Line</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>";
                                $line = 1;
                                for($x=0;$x<count($txnwithrowerror);$x++){
                                    echo "<tr>
                                               <td>$line</td>
                                               <td>".utf8_encode($txnwithrowerror[$x])."</td>
                                          </tr>";
                                    $line++;
                                }
                    echo   "</tbody></table><br><br>";

                    echo "<table border='1px' cellspacing='0px'>
                            <thead>
                                <tr>    
                                    <th colspan='2'>ADDED</th>
                                </tr>
                                <tr>
                                    <th>Line</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>";
                                $line = 1;
                                for($x=0;$x<count($txnwithouterror);$x++){
                                    echo "<tr>
                                               <td>$line</td>
                                               <td>".utf8_encode($txnwithouterror[$x])."</td>
                                          </tr>";
                                    $line++;
                                }
                     echo   "</tbody></table><br>";

                     echo "<table border='1px' cellspacing='0px'>
                            <thead>
                                <tr>    
                                    <th colspan='2'>UPDATED</th>
                                </tr>
                                <tr>
                                    <th>Line</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>";
                                $line = 1;
                                for($x=0;$x<count($txnrowexist);$x++){
                                    echo "<tr>
                                               <td>$line</td>
                                               <td>".utf8_encode($txnrowexist[$x])."</td>
                                          </tr>";
                                    $line++;
                                }
                     echo   "</tbody></table><br>";

                     


                                    
                }
                else{
                    echo "Unable to upload file. <b>Invalid Header Format</b>.<br><br>";
                    echo "Click <a class='pointer' href='../file-templates/agent-template.xlsx'>here</a> to download file template<br>";
                        
                }


    	}
    	else{
    		echo "Invalid File Type: $ftype<br><br>
                  <b>Valid File Types</b>: .xlsx, .xls, .csv";
    	}


				
				

		
	}


?>