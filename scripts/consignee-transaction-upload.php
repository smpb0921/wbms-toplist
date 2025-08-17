<?php


	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/system-log.class.php");////////
    include("../classes/consignee.class.php");
    include("../classes/consignee-contact.class.php");
    include("../resources/PHPExcel-1.8/Classes/PHPExcel.php");
    //include("../resources/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");

    if(isset($_FILES['uploadconsigneemodal-file'])){

        function getPortID($code){
            $id = '';
            $rs = query("select * from origin_destination_port where upper(code)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $id=$obj->id;
                }
            }
            return $id;
        }

        function convertToText($str){
            $str = escapeString($str);
            //$str = trim($str,'0');
            return $str;
        }

    	$file = $_FILES['uploadconsigneemodal-file'];
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
                                        'ACCOUNT NAME', //A
                                        'COMPANY NAME', //B
                                        'ACTIVE FLAG', //C
                                        'STREET',//D
                                        'DISTRICT/BARANGAY', //E
                                        'CITY', //F
                                        'REGION',//G
                                        'ZIP CODE',//H
                                        'COUNTRY',//I
                                        'CONTACT PERSON',//J
                                        'PHONE',//K
                                        'MOBILE',//L
                                        'EMAIL',//M
                                        'ID NUMBER'//M
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

                    $consigneeclass = new consignee();
                    $consigneecontactclass = new consignee_contact();
                    $systemlog = new system_log();


                    for($i=2;$i<=$lastRow;$i++){

                        $accountname = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                        $companyname = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                        $accountname = trim($accountname)==''?$companyname:$accountname;
                        $companyname = trim($companyname)==''?$accountname:$companyname;
                        $activeflag = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                        $activeflagbool = trim($activeflag)=='YES'?0:1;
                        $street = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));
                        $district = convertToText(strtoupper($worksheet->getCell('E'.$i)->getValue()));
                        $city = convertToText(strtoupper($worksheet->getCell('F'.$i)->getValue()));
                        $region = convertToText(strtoupper($worksheet->getCell('G'.$i)->getValue()));
                        $zipcode = convertToText(strtoupper($worksheet->getCell('H'.$i)->getValue()));
                        $country = convertToText(strtoupper($worksheet->getCell('I'.$i)->getValue()));

                        $contactperson = convertToText(strtoupper($worksheet->getCell('J'.$i)->getValue()));
                        $phone = convertToText(strtoupper($worksheet->getCell('K'.$i)->getValue()));
                        $mobile = convertToText(strtoupper($worksheet->getCell('L'.$i)->getValue()));
                        $email = convertToText(strtoupper($worksheet->getCell('M'.$i)->getValue()));
                        $idnumber = convertToText(strtoupper($worksheet->getCell('N'.$i)->getValue()));
                        $idnumber = trim($idnumber)==''||trim(strtoupper($idnumber))=='N/A'||trim(strtoupper($idnumber))=='NA'?'NULL':trim(strtoupper($idnumber));

                        

                        

                        if($accountname!=''){

                           

                            $checkifexistrs = query("select * from consignee where account_name='$accountname'");

                            if(getNumRows($checkifexistrs)==1){
                                while($obj=fetch($checkifexistrs)){
                                    $consigneeid = $obj->id;
                                }
                                $checkcontact = query("select * from consignee_contact where consignee_id='$consigneeid' and default_flag=1");

                                if(getNumRows($checkcontact)>0){
                                    $flag = 0;
                                }
                                else{   
                                    $flag = 1;
                                }

                                $checkifcontactexist = query("select * 
                                                              from consignee_contact 
                                                              where consignee_id='$consigneeid' and 
                                                                    contact_name='$contactperson'");

                                if(getNumRows($checkifcontactexist)==0&&
                                   trim($contactperson)!=''&&
                                   (trim($phone)!=''||trim($mobile)!=''||trim($email)!='')
                                   ){


                                    $rs = query("insert into consignee_contact(
                                                                                consignee_id,
                                                                                contact_name,
                                                                                phone_number,
                                                                                mobile_number,
                                                                                email_address,
                                                                                default_flag
                                                                            )
                                                                        values(
                                                                                    $consigneeid,
                                                                                    '$contactperson',
                                                                                    '$phone',
                                                                                    '$mobile',
                                                                                    '$email',
                                                                                    $flag
                                                                              )

                                                                        ");
                                }

                                array_push($txnrowexist, "Row Updated | Details: Account Name(Required)=$accountname, Company Name=$companyname, Active Flag=$activeflag, Street=$street, District/Barangay=$district, City=$city, Region=$region, Zip Code=$zipcode, Country=$country, Contact Person=$contactperson, Phone=$phone, Mobile=$mobile, Email=$email, ID Number=$idnumber");
                                
                               

                            }
                            
                            if(getNumRows($checkifexistrs)==0){
                                $accountname = trim($accountname)==''?'NULL':$accountname;
                                $companyname = trim($companyname)==''?'NULL':$companyname;
                                $street = trim($street)==''?'NULL':$street;
                                $district = trim($district)==''?'NULL':$district;
                                $city = trim($city)==''?'NULL':$city;
                                $region = trim($region)==''?'NULL':$region;
                                $zipcode = trim($zipcode)==''?'NULL':$zipcode;
                                $country = trim($country)==''?'NULL':$country;


                                $userid = USERID;
                                $now = date('Y-m-d H:i:s');
                                $accountnumber = getTransactionNumber(7);
                                $consigneeclass->insert(array('',$accountnumber,$accountname,$companyname,$street,$district,$city,$region,$zipcode,$country,$userid,$now,'NULL','NULL',$activeflagbool,$idnumber));
                                $consigneeid = $consigneeclass->getInsertId();
                                
                                /*query("insert into consignee( 
                                                                    account_number,
                                                                    account_name,
                                                                    inactive_flag,
                                                                    company_name,
                                                                    company_street_address,
                                                                    company_district,
                                                                    company_city,
                                                                    company_state_province,
                                                                    company_zip_code,
                                                                    company_country
                                                                )   
                                                          values(
                                                                    '$accountnumber',
                                                                    '$accountname',
                                                                    '$activeflagbool',
                                                                    '$companyname',
                                                                    '$street',
                                                                    '$district',
                                                                    '$city',
                                                                    '$region',
                                                                    '$zipcode',
                                                                    '$country'
                                                                )");
                                $consigneeid = mysql_insert_id();*/
                                
                                if(strtoupper($contactperson)!='NONE'&&
                                   $contactperson!='NULL'&&
                                   trim($contactperson)!=''&&
                                   (trim($phone)!=''||trim($mobile)!=''||trim($email)!='')
                                   ){

                                    $contactperson = trim($contactperson)==''?'NULL':$contactperson;
                                    $phone = trim($phone)==''?'NULL':$phone;
                                    $mobile = trim($mobile)==''?'NULL':$mobile;
                                    $email = trim($email)==''?'NULL':$email;
                                   
                                    $consigneecontactclass->insert(array('',$consigneeid,$contactperson,$phone,$email,$mobile,$now,1,1,1));

                                    
                                    
                                        
                                    
                                }
                                
                                array_push($txnwithouterror, "Row Inserted | Details: Account Name(Required)=$accountname, Company Name=$companyname, Active Flag=$activeflag, Street=$street, District/Barangay=$district, City=$city, Region=$region, Zip Code=$zipcode, Country=$country, Contact Person=$contactperson, Phone=$phone, Mobile=$mobile, Email=$email, ID Number=$idnumber");
                                
                            }
                        }
                        else{
                            array_push($txnwithrowerror, "Error: Incomplete Details | Details: Line $i - Account Name(Required)=$accountname, Company Name=$companyname, Active Flag=$activeflag, Street=$street, District/Barangay=$district, City=$city, Region=$region, Zip Code=$zipcode, Country=$country, Contact Person=$contactperson, Phone=$phone, Mobile=$mobile, Email=$email, ID Number=$idnumber");
                        }


                    }

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

                     echo "<table border='1px' cellspacing='0px'>
                            <thead>
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
                     echo   "</tbody></table><br>";


                                    
                }
                else{
                    echo "Unable to upload file. <b>Invalid Header Format</b>.<br><br>";
                    echo "Click <a class='pointer downloadwaybillfiletemplate' href='../file-templates/consignee-transaction-template.xlsx'>here</a> to download file template<br>";
                        
                }


    	}
    	else{
    		echo "Invalid File Type: $ftype<br><br>
                  <b>Valid File Types</b>: .xlsx, .xls, .csv";
    	}


				
				

		
	}


?>