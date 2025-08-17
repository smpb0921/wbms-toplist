<?php


	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/billing-statement-waybill.class.php");
    include("../classes/system-log.class.php");////////
    include("../resources/PHPExcel-1.8/Classes/PHPExcel.php");
    //include("../resources/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");

    if(isset($_FILES['blsuploadwaybillfile'])){

       
        $billingnumber = isset($_POST['blswaybilluploadbillingnumber'])?escapeString($_POST['blswaybilluploadbillingnumber']):'';
    	$file = $_FILES['blsuploadwaybillfile'];
    	$tmp = $file['tmp_name'];
    	$filename = $file['name'];
        //$ftype = $file['type'];
    	$ftype = strrchr($filename, '.');

        $vatpercent = getInfo("company_information","value_added_tax_percentage","where id=1");
        if($vatpercent>0){
        }
        else{
            $vatpercent = 0;
        }

    	

    	if($ftype=='.xls'||$ftype=='.csv'||$ftype=='.xlsx'){

    			@$excelReader = PHPExcel_IOFactory::createReaderForFile($tmp);
    			@$excelObj = $excelReader->load($tmp);



                $sheetcount = $excelObj->getSheetCount();
                $sheets = $excelObj->getSheetNames();
                for($i=0;$i<count($sheets);$i++){
                    $sheets[$i] = strtoupper(trim($sheets[$i]));
                }

                $checkrequiredsheets = true;
                /*if(!in_array('INFO', $sheets)){
                    $checkrequiredsheets = false;
                }
                }*/


                
                    $checkheaderformat = true;
                    $checkheaderremarks = array();

                    /************  CHECK HEADER ***********************/
                 
                    $worksheet = $excelObj->getActiveSheet();
                    $headerColumns = array(
                                    'BOL NUMBER'
                                );

                    $checkHeaderInfo = 'TRUE';
                    $col = "A";
                    for($i=0;$i<count($headerColumns);$i++){
                        if(strtoupper(trim($worksheet->getCell($col.'1')->getValue()))!=strtoupper(trim($headerColumns[$i]))){
                            $checkHeaderInfo = 'FALSE';
                        }
                        $col++;
                    }

                    if($checkHeaderInfo=='FALSE'){
                        $checkheaderformat = false;
                        array_push($checkheaderremarks, 'Invalid header. Please download upload template.');
                    }
                  
                  
                    
                    


                    /*************** CHECK HEADER FOR EACH SHEET - END ****************/



                    if($checkheaderformat==true){

                            

                            $checkbillingrs = query("select * from txn_billing where billing_number='$billingnumber' and status='LOGGED'");

                            if(getNumRows($checkbillingrs)==1){ 
                                    $vatflag = 0;
                                    while($obj=fetch($checkbillingrs)){
                                        $shipperid = $obj->shipper_id;
                                        $vatflag = $obj->vat_flag;
                                    }



                                    //***** INFO SHEET ******/

                        			$worksheet = $excelObj->getActiveSheet();
                        			$lastRow = $worksheet->getHighestRow();
                        			$lastCol = $worksheet->getHighestColumn();

                    		      
                                    $datacheck = true;


                                    /** ROW CHECK **/
                                    $txnwithrowerror = array();
                                    $txnwithouterror = array();
                                    for($i=2;$i<=$lastRow;$i++){
                                            
                                            $bolnumber = strtoupper($worksheet->getCell('A'.$i)->getValue());


                                            

                                            $checkwaybillrs = query("
                                                                        select txn_waybill.id,
                                                                               txn_waybill.waybill_number,
                                                                               txn_waybill.waybill_type,
                                                                               txn_waybill.status,
                                                                               txn_waybill.mawbl_bl,
                                                                               date_format(txn_waybill.document_date,'%m/%d/%Y') as document_date,
                                                                               date_format(txn_waybill.delivery_date,'%m/%d/%Y') as delivery_date,
                                                                               txn_waybill.consignee_account_name,
                                                                               txn_waybill.total_amount,
                                                                               origintbl.description as origin,
                                                                               destinationtbl.description as destination,
                                                                               mode_of_transport.description as modeoftransport
                                                                        from txn_waybill
                                                                        left join shipper on shipper.id=txn_waybill.shipper_id
                                                                        left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id
                                                                        left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id
                                                                        left join mode_of_transport on mode_of_transport.id=txn_waybill.package_mode_of_transport
                                                                        where  txn_waybill.shipper_id='$shipperid'  and
                                                                               (txn_waybill.status='DELIVERED' or shipper.non_pod_flag=1) and 
                                                                               txn_waybill.status!='VOID' and
                                                                               txn_waybill.status!='LOGGED' and
                                                                               txn_waybill.billed_flag=0 and 
                                                                               txn_waybill.waybill_number='$bolnumber' and
                                                                                txn_waybill.waybill_number not in (
                                                                                                                    select txn_billing_waybill.waybill_number
                                                                                                                    from txn_billing_waybill
                                                                                                                    left join txn_billing on txn_billing.billing_number=txn_billing_waybill.billing_number
                                                                                                                    where txn_billing.status!='VOID' and
                                                                                                                          txn_billing_waybill.flag=1 and 
                                                                                                                          txn_billing.shipper_id='$shipperid'
                                                                                                                )


                                                                    ");
                                            
                                            if(getNumRows($checkwaybillrs)==1){

                                            }
                                            else if(trim($bolnumber)!=''){

                                                $errormessage = '';
                                                $checkbolifvalid = query("select txn_waybill.status,
                                                                                 txn_waybill.billed_flag,
                                                                                 shipper.non_pod_flag 
                                                                          from txn_waybill  
                                                                          left join shipper on shipper.id=txn_waybill.shipper_id
                                                                          where txn_waybill.waybill_number='$bolnumber'");

                                                if(getNumRows($checkbolifvalid)==1){
                                                    while($obj=fetch($checkbolifvalid)){
                                                        $bolstatus = $obj->status;
                                                        $bolbilledflag = $obj->billed_flag;
                                                        $bolshippernonpodflag = $obj->non_pod_flag;
                                                    }


                                                    $checkbolifvalidshipper = query("select * from txn_waybill where waybill_number='$bolnumber' and shipper_id='$shipperid'");
                                                    if(getNumRows($checkbolifvalidshipper)!=1){
                                                        $errormessage = $errormessage."BOL shipper is different from Billing Transaction Shipper;<br> ";
                                                    }

                                                    $checkbolstatus = query("select * from txn_waybill where waybill_number='$bolnumber' and status!='VOID' and status!='LOGGED'");
                                                    if(getNumRows($checkbolstatus)!=1){
                                                        $errormessage = $errormessage."BOL status should not be equal to 'LOGGED' & 'VOID': status=$bolstatus;<br> ";
                                                    }

                                                    if($bolbilledflag!=0){
                                                        $errormessage = $errormessage."BOL is already tagged as billed;<br> ";
                                                    }

                                                    if($bolstatus!='DELIVERED'&&$bolshippernonpodflag!=1){
                                                        $errormessage = $errormessage."BOL status must be equal to 'DELIVERED' or corresponding shipper's non pod flag must be equal to 1;<br> ";
                                                    }


                                                    $checkifhasbilling = query("select txn_billing_waybill.waybill_number,
                                                                                       txn_billing.billing_number
                                                                                from txn_billing_waybill
                                                                                left join txn_billing on txn_billing.billing_number=txn_billing_waybill.billing_number
                                                                                where txn_billing.status!='VOID' and
                                                                                      txn_billing_waybill.flag=1 and 
                                                                                      txn_billing.shipper_id='$shipperid' and 
                                                                                      txn_billing_waybill.waybill_number='$bolnumber'
                                                                               ");
                                                    if(getNumRows($checkifhasbilling)>0){
                                                        while($obj=fetch($checkifhasbilling)){
                                                            $bolbilling = $obj->billing_number;
                                                        }
                                                        $errormessage = $errormessage."BOL has corresponding billing transaction: $bolbilling;<br> ";
                                                    }

                                                }
                                                else{
                                                    $errormessage = "BOL Number not found; ";
                                                }
                                                array_push($txnwithrowerror, array($bolnumber, $errormessage));
                                                $datacheck = false;
                                            }


                                           
                                    }
                                    /** ROW CHECK - END **/

                                    if($datacheck==true){
                                        $blswclass = new txn_billing_waybill();
                                        $systemlog = new system_log();
                                        $userid = USERID;
                                        $now = date('Y-m-d H:i:s');

                                        for($i=2;$i<=$lastRow;$i++){

                                            $wbamount = 0;
                                            $wb = strtoupper($worksheet->getCell('A'.$i)->getValue());
                                            $wb = trim($wb);
                                            
                                            if(trim($wb)!=''){
                                            
                                                

                                               
                                                $checkifaddedrs = query("select * 
                                                                         from txn_billing_waybill 
                                                                         where billing_number='$billingnumber' and 
                                                                               waybill_number='$wb'");

                                                if(getNumRows($checkifaddedrs)==0){
                                                        $getwbinfors = query("select * from txn_waybill where waybill_number='$wb' limit 1");
                                                        while($wbinfoobj=fetch($getwbinfors)){
                                                            $wbinfo_totalwaybillamount = trim($wbinfoobj->total_amount)==''?0:$wbinfoobj->total_amount;
                                                            $wbinfo_origin =  trim($wbinfoobj->origin_id)==''?'NULL':$wbinfoobj->origin_id;
                                                            $wbinfo_destination = trim($wbinfoobj->destination_id)==''?'NULL':$wbinfoobj->destination_id;
                                                            $wbinfo_modeoftransport = trim($wbinfoobj->package_mode_of_transport)==''?'NULL':$wbinfoobj->package_mode_of_transport;
                                                            $wbinfo_chargeableweight = trim($wbinfoobj->package_chargeable_weight)==''?'NULL':$wbinfoobj->package_chargeable_weight;
                                                            $wbinfo_regularcharges = trim($wbinfoobj->total_regular_charges)==''?0:$wbinfoobj->total_regular_charges;
                                                            $wbinfo_otherchargesvatable = trim($wbinfoobj->total_other_charges_vatable)==''?0:$wbinfoobj->total_other_charges_vatable;
                                                            $wbinfo_otherchargesnonvatable = trim($wbinfoobj->total_other_charges_non_vatable)==''?0:$wbinfoobj->total_other_charges_non_vatable;
                                                            $wbinfo_vat = trim($wbinfoobj->vat)==''?0:$wbinfoobj->vat;

                                                            $blswclass->insert(
                                                                                array(
                                                                                          '',
                                                                                          $billingnumber,
                                                                                          $wb,
                                                                                          $now,
                                                                                          $userid,
                                                                                          $wbinfo_totalwaybillamount,
                                                                                          $wbinfo_origin,
                                                                                          $wbinfo_destination,
                                                                                          $wbinfo_modeoftransport,
                                                                                          $wbinfo_chargeableweight,
                                                                                          $wbinfo_regularcharges,
                                                                                          $wbinfo_otherchargesvatable,
                                                                                          $wbinfo_otherchargesnonvatable,
                                                                                          $wbinfo_vat
                                                                                      )
                                                                                );
                                                        }

                                                        $systemlog->logInfo('BILLING STATEMENT',"Uploaded Waybill","Billing No.: $billingnumber | Waybill No.: $wb ",$userid,$now);


                                                        array_push($txnwithouterror, $wb);
                                                }

                                            }
                                           
                                            
                                        }

                                            
                                        query("update txn_billing 
                                                   set total_amount= 
                                                                     (
                                                                         select round(sum(txn_waybill.total_amount),2) as total
                                                                        from txn_billing_waybill
                                                                        left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
                                                                        where txn_billing_waybill.billing_number='$billingnumber'
                                                                        group by txn_billing_waybill.billing_number
                                                                      ),
                                                        vat=          
                                                                    ( 
                                                                        select round((sum(txn_waybill.total_regular_charges+txn_waybill.total_other_charges_vatable+txn_waybill.total_other_charges_non_vatable)*$vatpercent*$vatflag),2) as total
                                                                        from txn_billing_waybill
                                                                        left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
                                                                        where txn_billing_waybill.billing_number='$billingnumber'
                                                                        group by txn_billing_waybill.billing_number
                                                                     ),
                                                        total_vatable_charges= 
                                                                     (
                                                                        select (round(sum(txn_waybill.total_regular_charges),2)+round(sum(txn_waybill.total_other_charges_vatable),2)) as total
                                                                        from txn_billing_waybill
                                                                        left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
                                                                        where txn_billing_waybill.billing_number='$billingnumber'
                                                                        group by txn_billing_waybill.billing_number
                                                                      ),
                                                        total_non_vatable_charges= 
                                                                     (
                                                                        select round(sum(txn_waybill.total_other_charges_non_vatable),2) as total
                                                                        from txn_billing_waybill
                                                                        left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
                                                                        where txn_billing_waybill.billing_number='$billingnumber'
                                                                        group by txn_billing_waybill.billing_number
                                                                      )
                                                   where billing_number='$billingnumber'");

                                        


                                        echo "<table border='1px' cellspacing=0>
                                                                            <tr><td></td><td><b>UPLOADED BOL</b></td></tr>";
                                                                            $line = 1;
                                                                            for($o=0;$o<count($txnwithouterror);$o++){
                                                                                echo "<tr><td>$line</td><td>".$txnwithouterror[$o]."</td></tr>";
                                                                                $line++;
                                                                            }
                                        echo "</table><br><br>";

                                        

                                    }
                                    else{
                                        echo "Unable to upload file.<br><br>";

                                                            echo "<table border='1px' cellspacing=0>
                                                                            <tr><td><b>BOL NUMBER</b></td><td><b>REASON</b></td></tr>";
                                                                            for($o=0;$o<count($txnwithrowerror);$o++){
                                                                                echo "<tr><td>".$txnwithrowerror[$o][0]."</td><td>".$txnwithrowerror[$o][1]."</td></tr>";
                                                                            }
                                                            echo "</table><br><br>";
                                                            
                                    }

                                        			





                                                  
                            }
                            else{
                                echo "Unable to upload file. Invalid Billing No.: ".$billingnumber;
                            }




                    }
                    else{

                            echo "Unable to upload file.<br><br>";

                            echo "<table border='1px' cellspacing=0>
                                            <tr><td><b>REASON</b></td></tr>";
                                            for($o=0;$o<count($checkheaderremarks);$o++){
                                                echo "<tr><td>".$checkheaderremarks[$o]."</td></tr>";
                                            }
                            echo "</table><br><br>";

                            echo "Click <a class='pointer downloadblswaybillfiletemplate' href='../file-templates/billing-transaction-waybill-upload-template.xlsx'>here</a> to download file template<br>";
                    }


               

         



    			

    	}
    	else{
    		echo "Invalid File Type: $ftype<br><br>
                  <b>Valid File Types</b>: .xlsx, .xls, .csv";
    	}


				
				

		
	}


?>