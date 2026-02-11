<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/billing-statement.class.php");
    include("../classes/billing-statement-waybill.class.php");
    include("../classes/system-log.class.php");////////
    include("../classes/waybill-billing-history.class.php");////////


    if(isset($_POST['getSelectedShipperInfoBLS'])){
        if($_POST['getSelectedShipperInfoBLS']=='oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@'){
            $shipperid = escapeString($_POST['shipperid']);

            
            $rs = query("SELECT shipper.id,
                                shipper.pod_instruction,
                                shipper.billing_street_address,
                                shipper.billing_district,
                                shipper.billing_city,
                                shipper.billing_state_province,
                                shipper.billing_zip_code,
                                shipper.billing_country,
                                shipper.collection_contact_person,
                                shippercontacttbl.contact_name,
                                shippercontacttbl.phone_number,
                                shippercontacttbl.email_address,
                                shippercontacttbl.mobile_number
                         from shipper 
                         left join (
                                        select shipper_id,
                                               contact_name,
                                               phone_number,
                                               email_address,
                                               mobile_number
                                        from shipper_contact
                                        where shipper_id='$shipperid' and 
                                              default_flag=1
                                        limit 1
                                    ) as shippercontacttbl
                         on shippercontacttbl.shipper_id=shipper.id
                         where shipper.id='$shipperid'");

            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){

                    $dataarray = array(
                                     "response"=>'success',
                                     "podinstruction"=>utfEncode($obj->pod_instruction),
                                     "billingstreet"=>utfEncode($obj->billing_street_address),
                                     "billingdistrict"=>utfEncode($obj->billing_district),
                                     "billingcity"=>utfEncode($obj->billing_city),
                                     "billingprovince"=>utfEncode($obj->billing_state_province),
                                     "billingzipcode"=>utfEncode($obj->billing_zip_code),
                                     "billingcountry"=>utfEncode($obj->billing_country),
                                     "collectioncontact"=>utfEncode($obj->collection_contact_person),
                                     "contact"=>utfEncode($obj->contact_name),
                                     "phone"=>utfEncode($obj->phone_number),
                                     "email"=>utfEncode($obj->email_address),
                                     "mobile"=>utfEncode($obj->mobile_number)

                                  );

                }
            }
            else{
                $dataarray = array(
                                     "response"=>'invalidshipperid'
                                  );
            }
            print_r(json_encode($dataarray));

        }
    }


    if(isset($_POST['NewBillingStatement'])){
        if($_POST['NewBillingStatement']=='f#oifpNLEpR#nsRP9$nzpo92po@k@'){

            
            $shipperid = escapeString($_POST['shipperid']);
            $docdate = dateString($_POST['docdate']);
            $paymentduedate = dateString($_POST['paymentduedate']);
            $paymentduedate = trim($paymentduedate)=='1970-01-01'||trim($paymentduedate)==''?'NULL':$paymentduedate;
            $remarks = escapeString($_POST['remarks'])==''?'NULL':escapeString($_POST['remarks']);
            $contact = escapeString($_POST['contact'])==''?'NULL':escapeString($_POST['contact']);
            $phone = escapeString($_POST['phone'])==''?'NULL':escapeString($_POST['phone']);
            $mobile = escapeString($_POST['mobile'])==''?'NULL':escapeString($_POST['mobile']);
            $email = escapeString($_POST['email'])==''?'NULL':escapeString($_POST['email']);
            $attention  = escapeString($_POST['attention'])==''?'NULL':escapeString($_POST['attention']);
            $invoice = escapeString($_POST['invoice'])==''?'NULL':escapeString($_POST['invoice']);
            $vatflag = escapeString($_POST['vatflag'])==''?'NULL':escapeString($_POST['vatflag']);
            $shipmenttype  = escapeString($_POST['shipmenttype'])==''?'NULL':escapeString($_POST['shipmenttype']);
            $billingtype  = escapeString($_POST['billingtype'])==''?'NULL':escapeString($_POST['billingtype']);
            $accountexecutive  = escapeString($_POST['accountexecutive'])==''?'NULL':escapeString($_POST['accountexecutive']);

            $billprovince = 'NULL';
            $billcity = 'NULL';
            $billdistrict = 'NULL';
            $billzipcode = 'NULL';
            $billstreet = 'NULL';
            $billcountry = 'NULL';

            $compprovince = 'NULL';
            $compcity = 'NULL';
            $compdistrict = 'NULL';
            $compzipcode = 'NULL';
            $compstreet = 'NULL';
            $compcountry = 'NULL';

            $accountnumber = '';
            $accountname = '';
            $companyname = '';

            if($docdate==''||$docdate=='1970-01-01'){
                $response = array(
                                       "response"=>'invaliddocdate'
                             );
            }
            else if(($paymentduedate==''||$paymentduedate=='1970-01-01')&&$paymentduedate!='NULL'){
                $response = array(
                                       "response"=>'invalidpaymentduedate'
                             );
            }
            else{
                $rs = query("select * from shipper where shipper.id='$shipperid'");
                if(getNumRows($rs)==1){

                    $blsclass = new txn_billing();
                    $systemlog = new system_log();
                    $billingnumber = getTransactionNumber(9);


                    while($obj=fetch($rs)){
                        $billprovince = trim($obj->billing_state_province)==''?'NULL':$obj->billing_state_province;
                        $billcity = trim($obj->billing_city)==''?'NULL':$obj->billing_city;
                        $billdistrict = trim($obj->billing_district)==''?'NULL':$obj->billing_district;
                        $billzipcode = trim($obj->billing_zip_code)==''?'NULL':$obj->billing_zip_code;
                        $billstreet = trim($obj->billing_street_address)==''?'NULL':$obj->billing_street_address;
                        $billcountry = trim($obj->billing_country)==''?'NULL':$obj->billing_country;

                        $compprovince = trim($obj->company_state_province)==''?'NULL':$obj->company_state_province;
                        $compcity = trim($obj->company_city)==''?'NULL':$obj->company_city;
                        $compdistrict = trim($obj->company_district)==''?'NULL':$obj->company_district;
                        $compzipcode = trim($obj->company_zip_code)==''?'NULL':$obj->company_zip_code;
                        $compstreet = trim($obj->company_street_address)==''?'NULL':$obj->company_street_address;
                        $compcountry = trim($obj->company_country)==''?'NULL':$obj->company_country;

                        $accountnumber = $obj->account_number;
                        $accountname = $obj->account_name;
                        $companyname = $obj->company_name;
                    }

                    $now = date("Y-m-d H:i:s");
                    $userid = USERID;

                    $blsclass->insert(array('','LOGGED',$billingnumber,$docdate,$paymentduedate,$remarks,$shipperid,$accountnumber,$accountname,$companyname,$compstreet,$compdistrict,$compcity,$compprovince,$compzipcode,$compcountry,$billstreet,$billdistrict,$billcity,$billprovince,$billzipcode,$billcountry,$contact,$phone,$mobile,$email,$userid,$now,'NULL','NULL',$attention,$invoice,$vatflag,$billingtype,$accountexecutive,$shipmenttype));
                    $txnid = $blsclass->getInsertId();
					// $systemlog->logInfo('BILLING STATEMENT',"New Billing Statement","Statement Number: $billingnumber | Shipper ID: $shipperid | Account Number: $accountnumber | Account Name: $accountname | Company Name: $companyname | Company Street Address: $compstreet | Company District: $compdistrict | Company City: $compcity | Company Region: $compprovince | Company Zip: $compzipcode | Company Country: $compcountry | Billing Street Address: $billstreet | Billing District: $billdistrict | Billing City: $billcity | Billing Region: $billprovince | Billing Zip: $billzipcode | Billing Country: $billcountry | Contact: $contact | Phone: $phone | Mobile: $mobile | Email: $email | Attention: $attention | Invoice: $invoice | Vat Flag: $vatflag | Billing Type: $billingtype | Account Executive: $accountexecutive",$userid,$now,$txnid);

                    $response = array(
                                       "response"=>'success',
                                       "txnnumber"=>$billingnumber
                                      );

                }
                else{
                    $response = array(
                                       "response"=>'invalidshipper'
                                     );
                }
            }
            print_r(json_encode($response));

        }
    }
 
    if(isset($_POST['updateBillingStatement'])){
        if($_POST['updateBillingStatement']=='kjoI$H2oiaah3h0$09jDppo92po@k@'){

            $billingid = escapeString($_POST['id']);
            $billingnumber = escapeString($_POST['billingnumber']);
            $docdate = dateString($_POST['docdate']);
            $paymentduedate = dateString($_POST['paymentduedate']);
            $paymentduedate =trim($paymentduedate)=='1970-01-01'||trim($paymentduedate)==''?'NULL':$paymentduedate;
            $shipperid = escapeString($_POST['shipperid'])==''?'NULL':escapeString($_POST['shipperid']);
            $remarks = escapeString($_POST['remarks'])==''?'NULL':escapeString($_POST['remarks']);
            $contact = escapeString($_POST['contact'])==''?'NULL':escapeString($_POST['contact']);
            $phone = escapeString($_POST['phone'])==''?'NULL':escapeString($_POST['phone']);
            $mobile = escapeString($_POST['mobile'])==''?'NULL':escapeString($_POST['mobile']);
            $email = escapeString($_POST['email'])==''?'NULL':escapeString($_POST['email']); 
            $attention  = escapeString($_POST['attention'])==''?'NULL':escapeString($_POST['attention']);
            $invoice = escapeString($_POST['invoice'])==''?'NULL':escapeString($_POST['invoice']);
            $vatflag = escapeString($_POST['vatflag'])==''?'NULL':escapeString($_POST['vatflag']);

            $billingtype  = escapeString($_POST['billingtype'])==''?'NULL':escapeString($_POST['billingtype']);
            $accountexecutive  = escapeString($_POST['accountexecutive'])==''?'NULL':escapeString($_POST['accountexecutive']);
            $shipmenttype  = escapeString($_POST['shipmenttype'])==''?'NULL':escapeString($_POST['shipmenttype']);




            $province = escapeString($_POST['province'])==''?'NULL':escapeString($_POST['province']);
            $city = escapeString($_POST['city'])==''?'NULL':escapeString($_POST['city']);
            $district = escapeString($_POST['district'])==''?'NULL':escapeString($_POST['district']);
            $zipcode = escapeString($_POST['zipcode'])==''?'NULL':escapeString($_POST['zipcode']);
            $street = escapeString($_POST['street'])==''?'NULL':escapeString($_POST['street']);
            $country = escapeString($_POST['country'])==''?'NULL':escapeString($_POST['country']);

            $checkifvalidbillingrs = query("select * from txn_billing where id='$billingid' and billing_number='$billingnumber'");

                if(getNumRows($checkifvalidbillingrs)!=1){
                    $response = array(
                                           "response"=>'invalidbilling'
                                 );
                }
                else if(strtoupper($province)=='NULL'&&1==2){
                    $response = array(
                                           "response"=>'noprovinceprovided'
                                 );
                }
                else if(strtoupper($city)=='NULL'&&1==2){
                    $response = array(
                                           "response"=>'nocityprovided'
                                 );
                }
                else if(strtoupper($district)=='NULL'&&1==2){
                    $response = array(
                                           "response"=>'nodistrictprovided'
                                 );
                }
                else if(strtoupper($zipcode)=='NULL'&&1==2){
                    $response = array(
                                           "response"=>'nozipcodeprovided'
                                 );
                }
                else if(strtoupper($street)=='NULL'){
                    $response = array(
                                           "response"=>'nostreetprovided'
                                 );
                }
                else if(strtoupper($country)=='NULL'&&1==2){
                    $response = array(
                                           "response"=>'nocountryprovided'
                                 );
                }
                else if($docdate==''||$docdate=='1970-01-01'){
                    $response = array(
                                           "response"=>'invaliddocdate'
                                 );
                }
                else if(($paymentduedate==''||$paymentduedate=='1970-01-01')&&$paymentduedate!='NULL'){
                    $response = array(
                                           "response"=>'invalidpaymentduedate'
                                 );
                }
                else{
                    $blsclass = new txn_billing();
                    $systemlog = new system_log();
                    $now = date('Y-m-d H:i:s');
                    $userid = USERID;


                    $billtoaccountnumber = 'NULL';
                    $billtoaccountname = 'NULL';
                    $billtocompanyname = 'NULL';
                    $shipperrs = query("select * from shipper where id='$shipperid'");
                    while($objshipper = fetch($shipperrs)){
                        $billtoaccountnumber = $objshipper->account_number;
                        $billtoaccountname = $objshipper->account_name;
                        $billtocompanyname = $objshipper->company_name;
                    }

                    $systemlog->logEditedInfo($blsclass,$billingid,array($billingid,'LOGGED',$billingnumber,$docdate,$paymentduedate,$remarks,$shipperid,$billtoaccountnumber,$billtoaccountname,$billtocompanyname,'NOCHANGE','NOCHANGE','NOCHANGE','NOCHANGE','NOCHANGE','NOCHANGE',$street,$district,$city,$province,$zipcode,$country,$contact,$phone,$mobile,$email,'NOCHANGE','NOCHANGE',$userid,$now,$attention,$invoice,$vatflag,$billingtype,$accountexecutive,$shipmenttype),'BILLING STATEMENT','Edited Billing Statement Info',$userid,$now);/// log should be before update is made
                    $blsclass->update($billingid,array('NOCHANGE',$billingnumber,$docdate,$paymentduedate,$remarks,$shipperid,$billtoaccountnumber,$billtoaccountname,$billtocompanyname,'NOCHANGE','NOCHANGE','NOCHANGE','NOCHANGE','NOCHANGE','NOCHANGE',$street,$district,$city,$province,$zipcode,$country,$contact,$phone,$mobile,$email,'NOCHANGE','NOCHANGE',$userid,$now,$attention,$invoice,$vatflag,$billingtype,$accountexecutive,$shipmenttype));

                    query("delete txn_billing_waybill 
                           from txn_billing_waybill 
                           left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number 
                           where txn_billing_waybill.billing_number='$billingnumber' and 
                                 txn_waybill.shipper_id!='$shipperid'");

                    if($vatflag==0){
                        query("update txn_billing set vat=0 where billing_number='$billingnumber'");
                    }
                    else if($vatflag==1){
                        $vatpercent = getInfo("company_information","value_added_tax_percentage","where id=1");
                        if($vatpercent>0){
                        }
                        else{
                            $vatpercent = 0;
                        }
                        query("update txn_billing set vat=round((total_vatable_charges*$vatpercent),2) where billing_number='$billingnumber'");
                    }

                    $response = array(
                                           "response"=>'success',
                                           "txnnumber"=>$billingnumber
                                 );
                
                }
                print_r(json_encode($response));



        }
    }


    if(isset($_POST['editBillingGetInfo'])){
        if($_POST['editBillingGetInfo']=='kjoI$H2oiaah3h0$09jDppo92po@k@'){
           $id = escapeString($_POST['id']);

           $query = "select txn_billing.id,
                            txn_billing.billing_number,
                            txn_billing.document_date,
                            txn_billing.shipper_id,
                            txn_billing.bill_to_account_number,
                            txn_billing.bill_to_account_name,
                            txn_billing.bill_to_company_name,
                            txn_billing.email,
                            txn_billing.fax,
                            txn_billing.phone,
                            txn_billing.mobile,
                            txn_billing.shipper_id,
                            txn_billing.billing_street_address,
                            txn_billing.billing_district,
                            txn_billing.billing_city,
                            txn_billing.billing_state_province,
                            txn_billing.billing_zip_code,
                            txn_billing.billing_country,
                            txn_billing.billing_contact_person,
                            txn_billing.remarks,
                            txn_billing.payment_due_date,
                            shipper.account_name,
                            shipper.account_number,
                            txn_billing.attention,
                            txn_billing.invoice,
                            txn_billing.vat_flag,
                            txn_billing.billing_type_id,
                            billing_type.description as billingtype,
                            txn_billing.shipment_type_id,
                            shipment_type.description as shipmenttype,
                            txn_billing.account_executive_id,
                            account_executive.name as accountexecutive
                     from txn_billing
                     left join billing_type on billing_type.id=txn_billing.billing_type_id 
                     left join account_executive on account_executive.id=txn_billing.account_executive_id
                     left join shipper on shipper.id=txn_billing.shipper_id
                     left join shipment_type on shipment_type.id=txn_billing.shipment_type_id
                     where txn_billing.id='$id'";

          $rs = query($query);

          if(getNumRows($rs)==1){
            while($obj=fetch($rs)){

                  $documentdate = dateFormat($obj->document_date, "m/d/Y");
                  $paymentduedate = dateFormat($obj->payment_due_date, "m/d/Y");

                  

                  $dataarray = array(
                                       "id"=>utfEncode($obj->id),
                                       "documentdate"=>utfEncode($documentdate),
                                       "paymentduedate"=>utfEncode($paymentduedate),
                                       "remarks"=>utfEncode($obj->remarks),
                                       "contact"=>utfEncode($obj->billing_contact_person),
                                       "phone"=>utfEncode($obj->phone),
                                       "mobile"=>utfEncode($obj->mobile),
                                       "email"=>utfEncode($obj->email),
                                       "street"=>utfEncode($obj->billing_street_address),
                                       "district"=>utfEncode($obj->billing_district),
                                       "city"=>utfEncode($obj->billing_city),
                                       "province"=>utfEncode($obj->billing_state_province),
                                       "zipcode"=>utfEncode($obj->billing_zip_code),
                                       "country"=>utfEncode($obj->billing_country),
                                       "shipperid"=>utfEncode($obj->shipper_id),
                                       "invoice"=>utfEncode($obj->invoice),
                                       "attention"=>utfEncode($obj->attention),
                                       "shipper"=>utfEncode($obj->account_number).' - '.utfEncode($obj->account_name),
                                       "billingtypeid"=>utfEncode($obj->billing_type_id),
                                       "billingtype"=>utfEncode($obj->billingtype),
                                       "shipmenttypeid"=>utfEncode($obj->shipment_type_id),
                                       "shipmenttype"=>utfEncode($obj->shipmenttype),
                                       "accountexecutiveid"=>utfEncode($obj->account_executive_id),
                                       "accountexecutive"=>utfEncode($obj->accountexecutive),
                                       "response"=>"success",
                                       "vatflag"=>utfEncode($obj->vat_flag)
                                    );

            }
          }
          else{
              $dataarray = array(
                                       "response"=>"invalidbilling"
                                );

          }

           print_r(json_encode($dataarray));




        }
    }

    if(isset($_POST['getBillingStatementData'])){
        if($_POST['getBillingStatementData']=='foiRFN#@!pspR#1NEi34smo1sonk&$'){

            $txnnumber = escapeString($_POST['txnnumber']);
            $rs = query("
                            select txn_billing.id,
                                   txn_billing.billing_number,
                                   txn_billing.total_amount,
                                   txn_billing.document_date,
                                   txn_billing.shipper_id,
                                   txn_billing.bill_to_account_number,
                                   txn_billing.bill_to_account_name,
                                   txn_billing.bill_to_company_name,
                                   txn_billing.company_street_address,
                                   txn_billing.company_district,
                                   txn_billing.company_city,
                                   txn_billing.company_state_province,
                                   txn_billing.company_zip_code,
                                   txn_billing.company_country,
                                   txn_billing.billing_street_address,
                                   txn_billing.billing_district,
                                   txn_billing.billing_city,
                                   txn_billing.billing_state_province,
                                   txn_billing.billing_zip_code,
                                   txn_billing.billing_country,
                                   txn_billing.billing_contact_person,
                                   txn_billing.phone,
                                   txn_billing.mobile,
                                   txn_billing.email,
                                   txn_billing.remarks,
                                   txn_billing.payment_due_date,
                                   txn_billing.created_date,
                                   txn_billing.updated_date,
                                   txn_billing.posted_date,
                                   txn_billing.status,
                                   txn_billing.created_by,
                                   txn_billing.reason,
                                   txn_billing.received_date,
                                   txn_billing.received_by,    
                                   txn_billing.paid_flag,
                                   case
                                        when txn_billing.paid_flag=0 then 'NO'
                                        when txn_billing.paid_flag=1 then 'YES'
                                   end as paidflag,
                                   concat(cuser.first_name,' ',cuser.last_name) as createdby,
                                   concat(uuser.first_name,' ',uuser.last_name) as updatedby,
                                   concat(puser.first_name,' ',puser.last_name) as postedby,
                                   txn_billing.attention,
                                   txn_billing.invoice,
                                   txn_billing.vat_flag,
                                   txn_billing.billing_type_id,
                                   billing_type.description as billingtype,
                                   txn_billing.shipment_type_id,
                                   shipment_type.description as shipmenttype,
                                   txn_billing.account_executive_id,
                                   account_executive.name as accountexecutive
                            from txn_billing
                            left join billing_type on billing_type.id=txn_billing.billing_type_id 
                            left join shipment_type on shipment_type.id=txn_billing.shipment_type_id
                            left join account_executive on account_executive.id=txn_billing.account_executive_id
                            left join user as cuser on cuser.id=txn_billing.created_by
                            left join user as uuser on uuser.id=txn_billing.updated_by
                            left join user as puser on puser.id=txn_billing.posted_by
                            where txn_billing.billing_number='$txnnumber'


                        ");
            if(getNumRows($rs)==1){
                while($obj = fetch($rs)){
                    $receiveddate = dateFormat($obj->received_date, "m/d/Y");
                    $createddate = dateFormat($obj->created_date, "m/d/Y h:i:s A");
                    $updateddate = dateFormat($obj->updated_date, "m/d/Y h:i:s A");
                    $posteddate = dateFormat($obj->posted_date, "m/d/Y h:i:s A");
                    $documentdate = dateFormat($obj->document_date, "m/d/Y");
                    $paymentduedate = dateFormat($obj->payment_due_date, "m/d/Y");

                    $userhasaccess = hasAccess(USERID,'#useraccessmanagebillingstatement');
                    if(USERID==$obj->created_by||USERID==1||$userhasaccess==1){
                        $loggedequalcreated='true';
                    }
                    else{
                        $loggedequalcreated='false';
                    }

                    $checkpaidflagbtnaccess = hasAccess(USERID,'#billingstatement-trans-paymenttaggingbtn');
                    if($checkpaidflagbtnaccess==1){
                        $paidflagbtn='true';
                    }
                    else{
                        $paidflagbtn='false';
                    }

                    $voidtxnaccess = hasAccess(USERID,'#billingstatement-trans-voidbtn');
                    if($voidtxnaccess==1){
                        $voidtxnflag='true';
                    }
                    else{
                        $voidtxnflag='false';
                    }

                    $unpostaccess = hasAccess(USERID,'#billingstatement-trans-unpostbtn');
                    if($unpostaccess==1){
                        $unposttxnflag='true';
                    }
                    else{
                        $unposttxnflag='false';
                    }

                  


                    $dataarray = array(

                                       "id"=>utfEncode($obj->id),
                                       "status"=>utfEncode($obj->status),
                                       "totalamount"=>utfEncode($obj->total_amount),
                                       "billingnumber"=>utfEncode($obj->billing_number),
                                       "shipperid"=>utfEncode($obj->shipper_id),
                                       "accountnumber"=>utfEncode($obj->bill_to_account_number),
                                       "accountname"=>utfEncode($obj->bill_to_account_name),
                                       "companyname"=>utfEncode($obj->bill_to_company_name),
                                       "contact"=>utfEncode($obj->billing_contact_person),
                                       "phone"=>utfEncode($obj->phone),
                                       "mobile"=>utfEncode($obj->mobile),
                                       "email"=>utfEncode($obj->email),
                                       "billstreet"=>utfEncode($obj->billing_street_address),
                                       "billdistrict"=>utfEncode($obj->billing_district),
                                       "billcity"=>utfEncode($obj->billing_city),
                                       "billprovince"=>utfEncode($obj->billing_state_province),
                                       "billzipcode"=>utfEncode($obj->billing_zip_code),
                                       "billcountry"=>utfEncode($obj->billing_country),
                                       "documentdate"=>$documentdate,
                                       "paymentduedate"=>$paymentduedate,
                                       "remarks"=>utfEncode($obj->remarks),
                                       "createddate"=>$createddate,
                                       "updateddate"=>$updateddate,
                                       "posteddate"=>$posteddate,
                                       "createdby"=>utfEncode($obj->createdby),
                                       "updatedby"=>utfEncode($obj->updatedby),
                                       "postedby"=>utfEncode($obj->postedby),
                                       "reason"=>utfEncode($obj->reason),
                                       "billingtypeid"=>utfEncode($obj->billing_type_id),
                                       "billingtype"=>utfEncode($obj->billingtype),
                                       "shipmenttypeid"=>utfEncode($obj->shipment_type_id),
                                       "shipmenttype"=>utfEncode($obj->shipmenttype),
                                       "accountexecutiveid"=>utfEncode($obj->account_executive_id),
                                       "accountexecutive"=>utfEncode($obj->accountexecutive),
                                       "hasaccess"=>$loggedequalcreated,
                                       "paidflagbtn"=>$paidflagbtn,
                                       "paidflag"=>$obj->paid_flag,
                                       "paidflagstr"=>$obj->paidflag,
                                       "attention"=>$obj->attention,
                                       "voidtxnflag"=>$voidtxnflag,
                                       "invoice"=>$obj->invoice,
                                       "vatflag"=>$obj->vat_flag,
                                       "receiveddate"=>$receiveddate,
                                       "receivedby"=>$obj->received_by,
                                       "unpostaccess"=>$unposttxnflag

                                       );
                }
                print_r(json_encode($dataarray));
            }
            else{
                echo "INVALID";
            }
        }
    }

    if(isset($_POST['getReference'])){
        if($_POST['getReference']=='FOio5ja3op2a2lK@3#4hh$93s'){
            $source = escapeString($_POST['source']);
            $id = escapeString($_POST['id']);
            

            $query = '';



            if($source=='first'){
                $query = "select * from txn_billing order by id asc limit 1";
            }
            else if($source=='second' && $id!=''){
                $query = "select * from txn_billing where id < $id order by id desc limit 1";
            }
            else if($source=='third' && $id!=''){
                $query = "select * from txn_billing where id > $id order by id asc limit 1";
            }
            else if($source=='fourth'){
                $query = "select * from txn_billing order by id desc limit 1";
            }
            else if($id==''){
                $query = "select * from txn_billing order by id asc limit 1";
            }
            if($query!=''){
                $rs = query($query);
                if(getNumRows($rs)>0){
                        $obj = fetch($rs);
                        echo $obj->billing_number;
                }
                else{
                    $rs = query("select * from txn_billing where id='$id'");
                    if(getNumRows($rs)>0){
                        $obj = fetch($rs);
                        echo $obj->billing_number;
                    }
                    else{
                        echo "N/A";
                    }
                }
            }
            else{
                echo "N/A";
            }
        }
    }


    if(isset($_POST['insertMultipleWaybillNumber'])){
        if($_POST['insertMultipleWaybillNumber']=='oihh#p@0fldpe3ksk#Op1NEi34smo1sonk&$'){

            $txnnumber = escapeString(strtoupper($_POST['txnnumber']));
            $wbnumber = $_POST['wbnumber'];
            $waybillcount = count($wbnumber);
            $now = date('Y-m-d H:i:s');
            $userid = USERID;

            $shipperid = '';

            $vatpercent = getInfo("company_information","value_added_tax_percentage","where id=1");
            if($vatpercent>0){
            }
            else{
                $vatpercent = 0;
            }

        

            $invalidwaybills = array();
            $checkwaybills = true;

            $inanotherbls = array();
            $noldps = true;
            $vatflag = 0;

            $addedwaybills = array();

            $blswclass = new txn_billing_waybill();
            $systemlog = new system_log();

            $validwaybillstatus = getInfo("company_information","load_plan_waybill_status","where id=1");
            $checkifvalidtxnrs = query("select * from txn_billing where billing_number='$txnnumber'");

            if(getNumRows($checkifvalidtxnrs)==1){
                while($obj=fetch($checkifvalidtxnrs)){
                    $shipperid = $obj->shipper_id;
                    $vatflag = $obj->vat_flag;
                }

               






                
                for($i=0;$i<$waybillcount;$i++){
                    $checkifvalidwbrs = query(" select  *
                                                from txn_waybill
                                                left join shipper on shipper.id=txn_waybill.shipper_id
                                                where (txn_waybill.status='DELIVERED' or shipper.non_pod_flag=1) and 
                                                      txn_waybill.status!='VOID' and
                                                      txn_waybill.status!='LOGGED' and
                                                      txn_waybill.billed_flag=0 and
                                                      txn_waybill.shipper_id='$shipperid' and
                                                      txn_waybill.waybill_number not in (
                                                                                            select txn_billing_waybill.waybill_number
                                                                                            from txn_billing_waybill
                                                                                            left join txn_billing on txn_billing.billing_number=txn_billing_waybill.billing_number
                                                                                            where txn_billing.status!='VOID' and
                                                                                                  txn_billing_waybill.flag=1
                                                                                         ) and
                                                      txn_waybill.waybill_number='".escapeString(strtoupper(trim($wbnumber[$i])))."'");

                    if(getNumRows($checkifvalidwbrs)!=1){
                        array_push($invalidwaybills, escapeString(strtoupper($wbnumber[$i])));
                        $checkwaybills = false;
                    }
                    else{
                         array_push($addedwaybills, escapeString(strtoupper($wbnumber[$i])));
                    }

                    /*$checkifinanotherbillingrs = query("select * 
                                                                from txn_load_plan_waybill 
                                                                left join txn_load_plan on txn_load_plan.load_plan_number=txn_load_plan_waybill.load_plan_number
                                                                where txn_load_plan_waybill.waybill_number='".escapeString(strtoupper($wbnumber[$i]))."' and 
                                                                      txn_load_plan.status!='VOID' and 
                                                                      (txn_load_plan.load_plan_number not in (select load_plan_number from txn_manifest where status!='VOID') or 
                                                                       txn_load_plan.load_plan_number in (select load_plan_number from txn_manifest where status!='POSTED') or 
                                                                       txn_load_plan_waybill.waybill_number in (select waybill_number from txn_manifest_waybill left join txn_manifest on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number where txn_manifest.status!='VOID')
                                                                      )");
                    if(getNumRows($checkifinanotherbillingrs)!=0){
                        array_push($inanotherbls, escapeString(strtoupper($wbnumber[$i])));
                        $noldps = false;
                    }


                    $checkifaddedrs = query("select * 
                                             from txn_load_plan_waybill 
                                             where load_plan_number='$ldpnumber' and waybill_number='".escapeString(strtoupper($wbnumber[$i]))."'");
                    if(getNumRows($checkifaddedrs)==0){
                        array_push($addedwaybills, escapeString(strtoupper($wbnumber[$i])));
                    }*/




                }

                $invalidwaybillstr = implode(', ', $invalidwaybills);
                //$inanotherblsstr = implode(', ', $inanotherbls);
                $addedwaybillstr = implode(', ', $addedwaybills);



                if($checkwaybills==true&&count($addedwaybills)>0){

                        for($i=0;$i<$waybillcount;$i++){
                                $wbamount = 0;
                                $wb = escapeString(strtoupper(trim($wbnumber[$i])));
                                $checkifaddedrs = query("select * 
                                                         from txn_billing_waybill 
                                                         where billing_number='$txnnumber' and 
                                                               waybill_number='$wb'");
                               // $gettotalwaybillamount = getInfo("txn_waybill","total_amount","where waybill_number='$wb'");
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
                                                                      $txnnumber,
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
                                }
                        }
                        $systemlog->logInfo('BILLING STATEMENT',"Added Waybill(s) - Multiple","Billing No.: $txnnumber | Waybill No.: $addedwaybillstr ",$userid,$now);

                        /*
                            TOTAL AMOUNT ---> select round(sum(txn_waybill.total_amount),2) as total
                                                    from txn_billing_waybill
                                                    left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
                                                    where txn_billing_waybill.billing_number='$txnnumber'
                                                    group by txn_billing_waybill.billing_number

                        VAT --> select (round(sum(txn_waybill.vat),2))*$vatflag as vat
                                                    from txn_billing_waybill
                                                    left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
                                                    where txn_billing_waybill.billing_number='$txnnumber'
                                                    group by txn_billing_waybill.billing_number*/

                                                    /* select (round(sum(txn_waybill.total_regular_charges),2)+round(sum(txn_waybill.total_other_charges_vatable),2))+round(sum(txn_waybill.total_other_charges_non_vatable),2)+(((round(sum(txn_waybill.total_regular_charges),2)+round(sum(  txn_waybill.total_other_charges_vatable),2))*$vatpercent)*$vatflag) as total
                                                    from txn_billing_waybill
                                                    left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
                                                    where txn_billing_waybill.billing_number='$txnnumber'
                                                    group by txn_billing_waybill.billing_number*/
                        
                        query("update txn_billing 
                               set total_amount= 
                                                 (
                                                     select round(sum(txn_waybill.total_amount),2) as total
                                                    from txn_billing_waybill
                                                    left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
                                                    where txn_billing_waybill.billing_number='$txnnumber'
                                                    group by txn_billing_waybill.billing_number
                                                  ),
                                    vat=          
                                                ( 
                                                    select round((sum(txn_waybill.total_regular_charges+txn_waybill.total_other_charges_vatable+txn_waybill.total_other_charges_non_vatable)*$vatpercent*$vatflag),2) as total
                                                    from txn_billing_waybill
                                                    left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
                                                    where txn_billing_waybill.billing_number='$txnnumber'
                                                    group by txn_billing_waybill.billing_number
                                                 ),
                                    total_vatable_charges= 
                                                 (
                                                    select (round(sum(txn_waybill.total_regular_charges),2)+round(sum(txn_waybill.total_other_charges_vatable),2)) as total
                                                    from txn_billing_waybill
                                                    left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
                                                    where txn_billing_waybill.billing_number='$txnnumber'
                                                    group by txn_billing_waybill.billing_number
                                                  ),
                                    total_non_vatable_charges= 
                                                 (
                                                    select round(sum(txn_waybill.total_other_charges_non_vatable),2) as total
                                                    from txn_billing_waybill
                                                    left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
                                                    where txn_billing_waybill.billing_number='$txnnumber'
                                                    group by txn_billing_waybill.billing_number
                                                  )
                               where billing_number='$txnnumber'");

                        $billingtotal = 0;
                        $gettotalbillingamountrs = query("select total_amount from txn_billing where billing_number='$txnnumber'");
                        while($obj1=fetch($gettotalbillingamountrs)){
                            $billingtotal = $obj1->total_amount;
                        }


                        $response = array(
                                            "response"=>'success',
                                            "billingtotal"=>$billingtotal
                                        );
                }
                else if($checkwaybills==false){
                    $response = array(
                                        "response"=>'invalidwaybill',
                                        "details"=>$invalidwaybillstr
                                     );
                }
                /*else if($noldps==false){
                    $response = array(
                                        "response"=>'inanotherloadplan',
                                        "details"=>$inanotherblsstr
                                     );

                }*/
                else if(count($addedwaybills)<=0){
                    $response = array(
                                            "response"=>'success'
                                      );
                }
                



            }
            else{
                $response = array(
                                    "response"=>'invalidtxnnumber'
                                 );
            }

            print_r(json_encode($response));



        }
    }

    if(isset($_POST['deleteWaybillNumber'])){
        if($_POST['deleteWaybillNumber']=='dskljouioU#ouh$3ksk#Op1NEi34smo1sonk&$'){

            $vatpercent = getInfo("company_information","value_added_tax_percentage","where id=1");
            if($vatpercent>0){
            }
            else{
                $vatpercent = 0;
            }
            

            $wbnumbersid = $_POST['wbnumbersid'];
            $wbnumbersid = implode("','", $wbnumbersid);
            $txnnumber = '';
            $deletedwaybills = '';
            $now = date('Y-m-d H:i:s');
            $userid = USERID;

            $waybills = array();
            $getcorrespondingwbs = query("select * from txn_billing_waybill where id in ('$wbnumbersid')");
            while($obj=fetch($getcorrespondingwbs)){
                array_push($waybills, $obj->waybill_number);
            }
            $waybills = implode("','", $waybills);


            
            $rs = query("select group_concat(waybill_number) as deletedwaybills, billing_number from txn_billing_waybill where id in ('$wbnumbersid')");
            while($obj=fetch($rs)){
                $deletedwaybills = $obj->deletedwaybills;
                $txnnumber = $obj->billing_number;
            }

            $vatflag = 0;
            $checkifvalidtxnrs = query("select vat_flag from txn_billing where billing_number='$txnnumber'");
            while($obj=fetch($checkifvalidtxnrs)){
                $vatflag = $obj->vat_flag;
            }


            $rs = query("delete from txn_billing_waybill where id in ('$wbnumbersid')");
            if($rs){    
                query("update txn_billing 
                               set total_amount= 
                                                 (
                                                     select round(sum(txn_waybill.total_amount),2) as total
                                                    from txn_billing_waybill
                                                    left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
                                                    where txn_billing_waybill.billing_number='$txnnumber'
                                                    group by txn_billing_waybill.billing_number
                                                  ),
                                    vat=          
                                                ( 
                                                    select round((sum(txn_waybill.total_regular_charges+txn_waybill.total_other_charges_vatable+txn_waybill.total_other_charges_non_vatable)*$vatpercent*$vatflag),2) as total
                                                    from txn_billing_waybill
                                                    left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
                                                    where txn_billing_waybill.billing_number='$txnnumber'
                                                    group by txn_billing_waybill.billing_number
                                                 ),
                                    total_vatable_charges= 
                                                 (
                                                    select (round(sum(txn_waybill.total_regular_charges),2)+round(sum(txn_waybill.total_other_charges_vatable),2)) as total
                                                    from txn_billing_waybill
                                                    left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
                                                    where txn_billing_waybill.billing_number='$txnnumber'
                                                    group by txn_billing_waybill.billing_number
                                                  ),
                                    total_non_vatable_charges= 
                                                 (
                                                    select round(sum(txn_waybill.total_other_charges_non_vatable),2) as total
                                                    from txn_billing_waybill
                                                    left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
                                                    where txn_billing_waybill.billing_number='$txnnumber'
                                                    group by txn_billing_waybill.billing_number
                                                  )
                               where billing_number='$txnnumber'");
                /*query("update txn_billing 
                               set total_amount= 
                                                 (
                                                    select round(sum(txn_waybill.total_amount),4) as total
                                                    from txn_billing_waybill
                                                    left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
                                                    where txn_billing_waybill.billing_number='$txnnumber'
                                                    group by txn_billing_waybill.billing_number
                                                  ), 
                                   vat= 
                                                 (
                                                    select round(sum(txn_waybill.vat),4) as vat
                                                    from txn_billing_waybill
                                                    left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
                                                    where txn_billing_waybill.billing_number='$txnnumber'
                                                    group by txn_billing_waybill.billing_number
                                                  ),
                                   subtotal= 
                                                 (
                                                    select round(sum(txn_waybill.subtotal),4) as subtotal
                                                    from txn_billing_waybill
                                                    left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
                                                    where txn_billing_waybill.billing_number='$txnnumber'
                                                    group by txn_billing_waybill.billing_number
                                                  ) 
                               where billing_number='$txnnumber'");
                */
                $systemlog = new system_log();
                $systemlog->logInfo('BILLING STATEMENT',"Deleted Waybill(s)","Billing No.: $txnnumber | Deleted Waybill(s): $deletedwaybills",$userid,$now);
                echo "success";

            }
            else{
                echo mysql_error();
            }

            

        }
    }




    if(isset($_POST['getBillingComputation'])){
        if($_POST['getBillingComputation']=='oiu2OI9kldp39u2o0lfknzzzo92po@k@'){
            $txnnumber = escapeString($_POST['txnnumber']);
            $totalamount = 0;
            $vat = 0;
            
            $vatablecharges = 0;
            $nonvatablecharges = 0;

            $cancelledamount = 0;
            $revisedamount = 0;
            $cnnonvatablecharges = 0;
            $cnvatablecharges = 0;
            $cnvatflag = 0;
            $cnvat = 0;

            $rs = query("select sum(txn_billing_waybill.regular_charges+txn_billing_waybill.other_charges_vatable) as total_vatable_charges,
                                sum(txn_billing_waybill.other_charges_non_vatable) as total_non_vatable_charges,
                                txn_billing.vat_flag
                         from txn_billing_waybill
                         left join txn_billing on txn_billing.billing_number=txn_billing_waybill.billing_number
                         where txn_billing_waybill.billing_number='$txnnumber'");
            /*$rs = query("select txn_billing.total_amount, 
                                txn_billing.vat,
                                txn_billing.total_vatable_charges,
                                txn_billing.total_non_vatable_charges,
                                shipper.vat_flag
                         from txn_billing 
                         left join shipper on shipper.id=txn_billing.shipper_id
                         where billing_number='$txnnumber'");*/
            while($obj=fetch($rs)){
                    //$totalamount = $obj->total_amount;
                    //$vat = $obj->vat;
                    $nonvatablecharges = $obj->total_non_vatable_charges;
                    $vatablecharges = $obj->total_vatable_charges;
                    $vatflag = $obj->vat_flag;

            }


            if($vatflag==1){
                $vat = $vatablecharges*.12;
                $vat = round($vat,2);
            }
            else{
                $vat = 0;
            }
            $totalamount = $nonvatablecharges+$vatablecharges+$vat;

            $status = '';
            $checkblsstatusrs = query("select status from txn_billing where billing_number='$txnnumber'");
            while($obj=fetch($checkblsstatusrs)){
                $status = $obj->status;
            }

            if($status=='VOID'){
                $cancelledamount = $totalamount;
            }
            else{
                $rs = query("select sum(txn_billing_waybill.regular_charges+txn_billing_waybill.other_charges_vatable) as total_vatable_charges,
                                    sum(txn_billing_waybill.other_charges_non_vatable) as total_non_vatable_charges,
                                    txn_billing.vat_flag
                             from txn_billing_waybill
                             left join txn_billing on txn_billing.billing_number=txn_billing_waybill.billing_number
                             where txn_billing_waybill.billing_number='$txnnumber' and txn_billing_waybill.flag=0");
                while($obj=fetch($rs)){
                        $cnnonvatablecharges = $obj->total_non_vatable_charges;
                        $cnvatablecharges = $obj->total_vatable_charges;
                        $cnvatflag = $obj->vat_flag;
                }
                if($cnvatflag==1){
                    $cnvat = $cnvatablecharges*.12;
                    $cnvat = round($cnvat,2);
                }
                else{
                    $cnvat = 0;
                }
                $cancelledamount = $cnnonvatablecharges+$cnvatablecharges+$cnvat;
            }
            $revisedamount = $totalamount-$cancelledamount; 


            $response = array(
                                           "response"=>'success',
                                           "totalamount"=>$totalamount,
                                           "vat"=>$vat,
                                           "totalvatablecharges"=>$vatablecharges,
                                           "totalnonvatablecharges"=>$nonvatablecharges,
                                           "cancelledamount"=>$cancelledamount,
                                           "revisedamount"=>$revisedamount
                                 );
            print_r(json_encode($response));




        }
    }

    if(isset($_POST['voidTransaction'])){
        if($_POST['voidTransaction']=='dROi$nsFpo94dnels$4sRoi809srbmouS@1!'){
                $id = escapeString($_POST['txnid']);
                $txnnumber = escapeString($_POST['txnnumber']);
                $remarks = escapeString($_POST['remarks']);

                $now = date('Y-m-d H:i:s');
                $userid = USERID;
                $systemlog = new system_log();
                $billingstatus = '';
            

                $checktxnrs = query("select * from txn_billing where id='$id' and billing_number='$txnnumber'");


                if(getNumRows($checktxnrs)==1){

                        while($obj=fetch($checktxnrs)){
                            $billingstatus = $obj->status;
                        }

                        if(strtoupper(trim($billingstatus))=='POSTED'){
                                $waybillbillhistory = new txn_waybill_billing_history();

                               

                       
                                $getbillingwbrs = query("select txn_billing_waybill.waybill_number 
                                                         from txn_billing_waybill 
                                                         left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
                                                         where txn_billing_waybill.billing_number='$txnnumber' and
                                                               txn_waybill.billed_flag=1");

                                while($obj1=fetch($getbillingwbrs)){
                                    $waybillnumber = $obj1->waybill_number;
                                    $waybillbillhistory->insert(array('',0,$waybillnumber,$txnnumber,$txnnumber,"SYSTEM GENERATED - VOID BILLING REFERENCE <br>Reason: ".$remarks,$now,$userid));
                                    $systemlog->logInfo('BILLING STATEMENT','Changed Billing Flag',"Waybill No.: ".$waybillnumber." | Flag: 0 | Reference:  | Remarks: $remarks",$userid,$now);
                                }

                                 $updateblsrs = query("update txn_waybill 
                                                       set billed_flag=0, 
                                                           billing_reference=NULL 
                                                       where waybill_number in (select waybill_number 
                                                                                from txn_billing_waybill
                                                                                where billing_number='$txnnumber')");
                            


                           
                        }

                        $rs = query("update txn_billing set status='VOID', updated_date='$now', updated_by='$userid', reason='$remarks' where id='$id'");
                        if($rs){
                            $systemlog->logInfo('BILLING STATEMENT','Cancelled Billing Transaction',"Billing No.: ".$txnnumber." | Remarks: $remarks",$userid,$now);
                            echo "success";
                        }
                    
                }
                else{
                    echo "invalidtransaction";
                }

                
                
        }
    }

    if(isset($_POST['postTransaction'])){
        if($_POST['postTransaction']=='dROi$nsFpo94dnels$4sRoi809srbmouS@1!'){
                $id = escapeString($_POST['txnid']);
                $txnnumber = escapeString($_POST['txnnumber']);

                $now = date('Y-m-d H:i:s');
                $userid = USERID;
                $systemlog = new system_log();
            

                $checktxnrs = query("select * from txn_billing where id='$id' and billing_number='$txnnumber'");


                if(getNumRows($checktxnrs)==1){

                    $checkwaybillcount = query("select * from txn_billing_waybill where billing_number='$txnnumber'");
                    if(getNumRows($checkwaybillcount)>0){

                        $rs = query("update txn_billing 
                                    set status='POSTED',
                                        posted_date='$now', 
                                        posted_by='$userid' 
                                    where id='$id'");
                        if($rs){

                            $rs1 = query("update txn_waybill 
                                   set billed_flag=1, 
                                       billing_reference='$txnnumber' 
                                   where waybill_number in (
                                                                select waybill_number 
                                                                from txn_billing_waybill 
                                                                where billing_number='$txnnumber'
                                                            )");

                            if($rs1){
                                $waybillbillhistory = new txn_waybill_billing_history();
                                $rs2 = query("select waybill_number from txn_billing_waybill where billing_number='$txnnumber'");
                                while($obj2=fetch($rs2)){
                                    $waybillnum = $obj2->waybill_number;
                                    $waybillbillhistory->insert(array('',1,$waybillnum,$txnnumber,$txnnumber,'SYSTEM GENERATED',$now,$userid));
                                }

                                $systemlog->logInfo('BILLING STATEMENT','Posted Billing Transaction',"Billing No.: ".$txnnumber,$userid,$now);
                                echo "success";

                            }
                        }
                    }
                    else{
                        echo "nowaybillsadded";
                    }
                    
                }
                else{
                    echo "invalidtransaction";
                }

                
                
        }
    }

    if(isset($_POST['unpostTransaction'])){
        if($_POST['unpostTransaction']=='dferDi$nsFpo94dnels$4sRoi809srbmouS@1!'){
                $id = escapeString($_POST['txnid']);
                $txnnumber = escapeString($_POST['txnnumber']);
                $reason = escapeString($_POST['reason']);

                $now = date('Y-m-d H:i:s');
                $userid = USERID;
                $systemlog = new system_log();
                $status = '';

                $unpostaccess = hasAccess(USERID,'#billingstatement-trans-unpostbtn');

                if($unpostaccess==1){
            

                    $checktxnrs = query("select * from txn_billing where id='$id' and billing_number='$txnnumber'");


                    if(getNumRows($checktxnrs)==1){
                        while($obj=fetch($checktxnrs)){
                            $status=$obj->status;
                        }
                        
                        if($status=='POSTED'){

                            $rs = query("update txn_billing 
                                        set status='LOGGED',
                                            paid_flag=0,
                                            posted_date=null, 
                                            posted_by=null,
                                            received_date=null,
                                            received_by=null,
                                            updated_date='$now',
                                            updated_by='$userid' 
                                        where id='$id'");
                            if($rs){

                                $rs1 = query("update txn_waybill 
                                       set billed_flag=0, 
                                           billing_reference=null 
                                       where waybill_number in (
                                                                    select waybill_number 
                                                                    from txn_billing_waybill 
                                                                    where billing_number='$txnnumber'
                                                                )");

                                if($rs1){
                                    $waybillbillhistory = new txn_waybill_billing_history();
                                    $rs2 = query("select waybill_number from txn_billing_waybill where billing_number='$txnnumber'");
                                    while($obj2=fetch($rs2)){
                                        $waybillnum = $obj2->waybill_number;
                                        $waybillbillhistory->insert(array('',0,$waybillnum,$txnnumber,$txnnumber,'SYSTEM GENERATED - UNPOSTED BILLING REFERENCE <br>Reason: '.$reason,$now,$userid));
                                    }

                                    $systemlog->logInfo('BILLING STATEMENT','Unposted Billing Transaction',"Billing No.: ".$txnnumber." | Reason: ".$reason,$userid,$now);
                                    echo "success";

                                }
                            }
                        }
                        else{
                            echo "notposted";
                        }
                        
                    }
                    else{
                        echo "invalidtransaction";
                    }
                }
                else{
                     echo "nopermission";
                }

                
                
        }
    }



    if(isset($_POST['changePaymentFlagging'])){
        if($_POST['changePaymentFlagging']=='dROi$nsFpo94dnels$4sRoi809srbmouS@1!'){
                $id = escapeString($_POST['txnid']);
                $txnnumber = escapeString($_POST['txnnumber']);
                $flag = escapeString($_POST['paidflag']);
                $remarks = escapeString($_POST['remarks']);

                $now = date('Y-m-d H:i:s');
                $userid = USERID;
                $systemlog = new system_log();
            

                $checktxnrs = query("select * from txn_billing where id='$id' and billing_number='$txnnumber'");


                if(getNumRows($checktxnrs)==1){           

                        $rs = query("update txn_billing 
                                     set paid_flag='$flag'
                                     where id='$id'");
                        if($rs){
                            $systemlog->logInfo('BILLING STATEMENT','Paid Flag',"Billing No.: ".$txnnumber." | Flag: $flag | Remarks: $remarks",$userid,$now);
                            echo "success";
                        }
                  
                    
                }
                else{
                    echo "invalidtransaction";
                }

                
                
        }
    }

?>