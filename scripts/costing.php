<?php
	  include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/costing.class.php");
    //include("../classes/costing-supplier.class.php");
    //include("../classes/putaway-details.class.php");
    include("../classes/system-log.class.php");////////

    
    if(isset($_POST['getPayeeInfo'])){
      if($_POST['getPayeeInfo']=='BCDjlkns2k!DEUgDLQIWN4mCLAhdOIEZ#'){
              $payee = isset($_POST['payee'])?escapeString($_POST['payee']):'NULL';

              $address = '';
              $tin  = '';
              $rs = query("select * from payee where id='$payee'");
              while($obj=fetch($rs)){
                $address = utf8_encode($obj->address);
                $tin = utf8_encode($obj->tin);
              }

              print_r(
                      json_encode(
                                    array(
                                            "address"=>$address,
                                            "tin"=>$tin,
                                            "response"=>"success"
                                          )
                                  )
              );

      }
    }

    

    if(isset($_POST['costingSaveEdit'])){
      if($_POST['costingSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){

        //$editaccess = hasAccess(USERID,ACCESSEDITCOSTNG);
        //$addaccess = hasAccess(USERID,ACCESSADDCOSTNG);

        $source = escapeString($_POST['source']);

        //if(($editaccess==1&&$source=='edit')||($addaccess==1&&$source=='add')){
        if(1==1){

                $type = isset($_POST['type'])?$_POST['type']:'NULL';
                $particulars = isset($_POST['particulars'])&&trim($_POST['particulars'])!=''?escapeString(strtoupper(trim($_POST['particulars']))):'NULL';
                $reference = isset($_POST['reference'])&&trim($_POST['reference'])!=''?escapeString(strtoupper(trim($_POST['reference']))):'NULL';
                $prfnumber = isset($_POST['prfnumber'])&&trim($_POST['prfnumber'])!=''?escapeString(strtoupper(trim($_POST['prfnumber']))):'NULL';
                $payee = isset($_POST['payee'])&&trim($_POST['payee'])!=''?escapeString(trim($_POST['payee'])):'NULL';
                $payeeaddress = isset($_POST['payee'])&&trim($_POST['payee'])!=''?escapeString(trim($_POST['payeeaddress'])):'NULL';
                $vatflag = isset($_POST['vatflag'])&&trim($_POST['vatflag'])!=''?escapeString(trim($_POST['vatflag'])):'NULL';
                $vatableamount = isset($_POST['vatableamount'])&&trim($_POST['vatableamount'])!=''?escapeString(trim($_POST['vatableamount'])):0;
                $vat = isset($_POST['vat'])&&trim($_POST['vat'])!=''?escapeString(trim($_POST['vat'])):0;
                $prfnumber = isset($_POST['prfnumber'])&&trim($_POST['prfnumber'])!=''?escapeString(strtoupper(trim($_POST['prfnumber']))):'NULL';
                $amount = isset($_POST['amount'])&&trim($_POST['amount'])!=''&&trim($_POST['amount'])>=0?escapeString(trim($_POST['amount'])):0;
                $date = trim($_POST['date'])==''?'NULL':dateString($_POST['date']);
                $userid = USERID;
                $now = date('Y-m-d H:i:s');
                $costingclass = new costing();
                $systemlog = new system_log();

                //$rs = query("select id from supplier where supplier.id='$supplier'");
                //if(getNumRows($rs)==1){

                  $rs = query("select id from chart_of_accounts where chart_of_accounts.id='$particulars'");
                  if(getNumRows($rs)==1){

                    if($date=='1970-01-01'){
                        echo "invalidcostingdate";
                    }
                    else{

                          $rs = query("select * from payee where id='$payee'");
                          if(getNumRows($rs)==1){

                            /*if($source=='edit'){
                              $id = escapeString($_POST['id']);
                              $query = "select * from costing where code='$code' and id!='$id'";
                            }
                            else{
                              $query = "select * from costing where code='$code'";
                            }
                            $rs = query($query);

                            if(getNumRows($rs)==0){*/

                                  if($source=='add'){
                                    $costingclass->insert(array('',$particulars,$amount,$reference,$prfnumber,$date,$now,$userid,'NULL','NULL',$payee,$payeeaddress,$vatableamount,$vatflag,$vat));
                                    $id = $costingclass->getInsertId();
                                    $systemlog->logAddedInfo($costingclass,array($id,$particulars,$amount,$reference,$prfnumber,$date,$now,$userid,'NULL','NULL',$payee,$payeeaddress,$vatableamount,$vatflag,$vat),'COSTING','New Costing Added',$userid,$now,$id);
                                    echo "success";
                                  }
                                  else if($source=='edit'){
                                    $id = escapeString($_POST['id']);
                                    
                                      $systemlog->logEditedInfo($costingclass,$id,array($id,$particulars,$amount,$reference,$prfnumber,$date,'NOCHANGE','NOCHANGE',$now,$userid,$payee,$payeeaddress,$vatableamount,$vatflag,$vat),'COSTING','Edited Costing Info',$userid,$now,$id);/// log should be before update is made
                                      $costingclass->update($id,array($particulars,$amount,$reference,$prfnumber,$date,'NOCHANGE','NOCHANGE',$now,$userid,$payee,$payeeaddress,$vatableamount,$vatflag,$vat));
                                      echo "success";

                                  }

                                  
                                    //INSERT MULTIPLE SUPPLIER
                                    /*$costingsupplierclass = new costing_supplier();
                                    $costingsupplierclass->deleteWhere("where costing_id=".$id);
                                    $supplierdata = array();
                                    
                                    if($_POST['supplier']!=null){
                                        for($i=0;$i<count($supplier);$i++){
                                            $temp = array();
                                            array_push($temp,$id,$supplier[$i]);
                                            array_push($supplierdata, $temp);
                                        }
                                        if(count($supplier)>0){
                                            $costingsupplierclass->insertMultiple($supplierdata);
                                        }
                                    }*/
                                    
                                  
                              /*}
                              else{
                                echo "codeexists";
                              }*/

                          }
                          else{
                            echo "invalidpayee";
                          }

                            

                    }

                  }
                  else{
                      echo "invalidparticulars";
                  }
                //}
                //else{
                //  echo "invalidsupplier";
                //}


        }
        else if($source=='edit'){
          echo "noeditpermission";
        }
        else if($source=='add'){
          echo "noaddpermission";
        }
        else{
          echo "sourceundefined";
        }



      }

    }

    if(isset($_POST['getData'])){
        if($_POST['getData']=='kFNEPslkd$HmndlUpdklSpR#1NEi34smo1sonk&$'){

            $id = escapeString($_POST['id']);

            //$addrcvaccess = hasAccess(USERID,ACCESSADDRCVCOSTNG);
            

            

            






            $rs = query("
                              select costing.id,
                                     costing.chart_of_accounts_id, 
                                     costing.amount,
                                     costing.reference,
                                     costing.prf_number,
                                     costing.payee_id,
                                     payee.payee_name,
                                     payee.tin,
                                     costing.payee_address,
                                     costing.is_vatable,
                                     case 
                                          when costing.is_vatable=1 then 'YES'
                                          else 'NO'
                                     end as vatflag,
                                     costing.vatable_amount,
                                     costing.vat_amount,
                                     date_format(costing.date,'%m/%d/%Y') as date,
                                     date_format(costing.created_date,'%m/%d/%Y') as created_date,
                                     date_format(costing.updated_date,'%m/%d/%Y') as updated_date,
                                     concat(cuser.first_name,' ',cuser.last_name) as createdby,
                                     concat(uuser.first_name,' ',uuser.last_name) as updatedby,
                                     chart_of_accounts.description as chartofaccounts,
                                     ifnull(count(distinct costing_waybill.waybill_number),0) as waybillcount,
                                     expense_type.description as typeofaccount,
                                     chart_of_accounts.expense_type_id,
                                     chart_of_accounts.type as producttype
                              from costing 
                              left join chart_of_accounts on chart_of_accounts.id=costing.chart_of_accounts_id
                              left join expense_type on expense_type.id=chart_of_accounts.expense_type_id
                              left join user as cuser on cuser.id=costing.created_by
                              left join user as uuser on uuser.id=costing.updated_by
                              left join costing_waybill on costing_waybill.costing_id=costing.id
                              left join txn_waybill on txn_waybill.waybill_number=costing_waybill.waybill_number
                              left join payee on payee.id=costing.payee_id
                              where costing.id='$id'
                              group by costing.id
                            


                        ");

            if(getNumRows($rs)==1){
                while($obj = fetch($rs)){

                  
               

                   
                    $dataarray = array(
                                       "id"=>utfEncode($obj->id),
                                       "typeofaccountid"=>utfEncode($obj->expense_type_id),
                                       "typeofaccount"=>utfEncode($obj->typeofaccount),
                                       "accountid"=>utfEncode($obj->chart_of_accounts_id),
                                       "account"=>utfEncode($obj->chartofaccounts),
                                       "producttype"=>utfEncode($obj->producttype),
                                       "payeeid"=>utfEncode($obj->payee_id),
                                       "payeename"=>utfEncode($obj->payee_name),
                                       "payeeaddress"=>utfEncode($obj->payee_address),
                                       "payeetin"=>utfEncode($obj->tin),
                                       "amount"=>utfEncode($obj->amount),
                                       "vatableamount"=>utfEncode($obj->vatable_amount),
                                       "vat"=>utfEncode($obj->vat_amount),
                                       "vatflag"=>utfEncode($obj->vatflag),
                                       "isvatable"=>utfEncode($obj->is_vatable),
                                       "amountdesc"=>utfEncode(convertWithDecimal($obj->amount,4)),
                                       "reference"=>utfEncode($obj->reference),
                                       "prfnumber"=>utfEncode($obj->prf_number),
                                       "date"=>utfEncode($obj->date),
                                       "createddate"=>utfEncode($obj->created_date),
                                       "createdby"=>utfEncode($obj->createdby),
                                       "updateddate"=>utfEncode($obj->updated_date),
                                       "updatedby"=>utfEncode($obj->updatedby),
                                       "response"=>'success'
                                       );
                

                }
            }              
            else{
                $dataarray = array(
                                        "response"=>'INVALID',
                                        "addrcvaccess"=>$addrcvaccess
                                   );
            }

            print_r(json_encode($dataarray));

        }
    }

    if(isset($_POST['addStockReceipt'])){
        if($_POST['addStockReceipt']=='FOskfOIheNLPFI#nlio5ja3op2a2lK@3#4hh$93s'){

          //$addrcvaccess = hasAccess(USERID,ACCESSADDRCVCOSTNG);
          //if($addrcvaccess==1){

                  $txnnumber = escapeString($_POST['txnnumber'])==''?'NULL':escapeString(strtoupper($_POST['txnnumber']));
                  $rowid = escapeString($_POST['rowid'])==''?'NULL':escapeString($_POST['rowid']);
                  $costingid = escapeString($_POST['costingid'])==''?'NULL':escapeString($_POST['costingid']);

                  $rs = query("select id, amount
                               from costing where id='$costingid'");
                  if(getNumRows($rs)==1){
                    while($obj=fetch($rs)){

                        $amount = $obj->amount;
                    }
                    

                    $rs = query("select id 
                                 from txn_waybill 
                                 where waybill_number='$txnnumber' and
                                       id='$rowid'
                                ");

                    if(getNumRows($rs)==1){

                      $rs = query("select id 
                                   from costing_waybill
                                   where costing_id='$costingid' and
                                         waybill_number='$txnnumber'
                                ");

                      if(getNumRows($rs)>0){
                        $response = array(
                                            "response"=>'ALREADYADDED'
                                          );
                      }
                      else{

                          
                            $userid = USERID;
                            $now = date('Y-m-d H:i:s');
                            $systemlog = new system_log();


                            $rowcount = 0;
                            $getcountrs = query("select ifnull(count(*),0) as rowcount from costing_waybill where costing_id='$costingid' group by costing_id");
                            while($obj=fetch($getcountrs)){
                              $rowcount = $obj->rowcount;
                            }


                            $rowcount = $rowcount +1;
                            $dividedamount = $amount/$rowcount;

                            query("insert into 
                                   costing_waybill(
                                                          costing_id,
                                                          waybill_number,
                                                          amount,
                                                          created_by,
                                                          created_date
                                                         ) 
                                           values(
                                              '$costingid',
                                              '$txnnumber',
                                              0,
                                              '$userid',
                                              '$now'
                                                 )");


                            ////////////////////////////// UPDATE AMOUNT //////////////////////////////////////////////
                            $rs = query("select    
                                                    
                                                    costing.amount,
                                                    ifnull(sum(txn_waybill.package_actual_weight),0) as totalactualweight,
                                                    ifnull(waybillpackages.volweight,0) as totalvolweight,
                                                    case 
                                                          when ifnull(sum(txn_waybill.package_actual_weight),0)>ifnull(waybillpackages.volweight,0) then ifnull(sum(txn_waybill.package_actual_weight),0)
                                                          else ifnull(waybillpackages.volweight,0)
                                                    end as actualvolweight,
                                                    case 
                                                          when ifnull(sum(txn_waybill.package_actual_weight),0)>ifnull(waybillpackages.volweight,0) then 'ACTUAL'
                                                          else 'VOLUMETRIC'
                                                    end as targetweight
                                            from costing 
                                            left join costing_waybill on costing_waybill.costing_id=costing.id
                                            left join txn_waybill on txn_waybill.waybill_number=costing_waybill.waybill_number
                                            left join (
                                                          select waybill_number,
                                                                  ifnull(sum(volumetric_weight),0) as volweight,
                                                                  ifnull(sum(actual_weight),0) as actualweight
                                                          from txn_waybill_package_dimension
                                                          where waybill_number in (select waybill_number from costing_waybill where costing_id='$costingid')
                                                          group by txn_waybill_package_dimension.waybill_number
                                                      ) as waybillpackages on waybillpackages.waybill_number=costing_waybill.waybill_number
                                            where costing.id='$costingid'
                                            group by costing.id");
                          $actualvolweight = 0;
                          $grossamount = 0;
                          $targetweight = '';
                          while($obj=fetch($rs)){
                                $grossamount = utfEncode($obj->amount);
                                $actualvolweight = utfEncode($obj->actualvolweight);
                                $targetweight = $obj->targetweight;
                          }
                                      
                            $updatercostingwaybillrs = query("
                                                              select 
                                                                      date_format(costing_waybill.created_date,'%m/%d/%Y') as created_date,
                                                                      concat(cuser.first_name,' ',cuser.last_name) as createdby,
                                                                      ifnull(txn_waybill.package_actual_weight,0) as actualweight,
                                                                      ifnull(waybillpackages.volweight,0) as volweight,
                                                                      costing_waybill.waybill_number,
                                                                      case 
                                                                              when '$targetweight'='ACTUAL' then round((($grossamount/$actualvolweight)*ifnull(txn_waybill.package_actual_weight,0)),2)
                                                                              else round((($grossamount/$actualvolweight)*ifnull(waybillpackages.volweight,0)),2)
                                                                      end as distributedamount
                                                              from costing_waybill 
                                                              left join txn_waybill on txn_waybill.waybill_number=costing_waybill.waybill_number
                                                              left join (
                                                                          select waybill_number,
                                                                                  ifnull(sum(volumetric_weight),0) as volweight,
                                                                                  ifnull(sum(actual_weight),0) as actualweight
                                                                          from txn_waybill_package_dimension
                                                                          where waybill_number in (select waybill_number from costing_waybill where costing_id='$costingid')
                                                                          group by txn_waybill_package_dimension.waybill_number
                                                                      ) as waybillpackages on waybillpackages.waybill_number=costing_waybill.waybill_number
                                                              left join user as cuser on cuser.id=costing_waybill.created_by
                                                              where costing_waybill.costing_id='$costingid'
                                                              group by costing_waybill.waybill_number
                            ");
                            while($obj=fetch($updatercostingwaybillrs)){
                              $distributedamount = $obj->distributedamount;
                              query("update costing_waybill 
                                     set amount='$distributedamount' 
                                     where costing_id='$costingid' and 
                                           waybill_number='$obj->waybill_number'");
                            }       
                            ////////////////////////////// UPDATE AMOUNT //////////////////////////////////////////////
                            





                            
                            


                            $systemlog->logInfo('COSTING',"Added Waybill","costing_id=$costingid | waybill_number=$txnnumber | waybill_id=$rowid",$userid,$now,$costingid);

                            $response = array(
                                                    "response"=>'ADDED'
                                                  );

                          
                        

                      }



                    }
                    else{
                      $response = array(
                                               "response"=>'INVALIDTXN'
                                       );
                    }



                  }
                  else{
                    $response = array(
                                               "response"=>'INVALIDCOSTING'
                                       );
                  }


         /* }
          else{
             $response = array(
                                               "response"=>'NOPERMISSION'
                                       );

          }*/
          print_r(json_encode($response));

        }
    }



    if(isset($_POST['deleteSelectedRcvCostingDetails'])){
        if($_POST['deleteSelectedRcvCostingDetails']=='skj$oihdtpoa$I#@4noi4AIFlskoRboIh4!Uboi@bp9Rbzhs'){

            //$deleteaccess = hasAccess(USERID,ACCESSDELETERCVCOSTNG);


          

                    //if($deleteaccess==1){

                       

                                $rows = isset($_POST['rows'])&&$_POST['rows']!=''?$_POST['rows']:'';
                                $txnid = isset($_POST['txnid'])?escapeString($_POST['txnid']):'';
                                $rowdeletioncount = count($rows);

                                $userid = USERID;
                                $now = date('Y-m-d H:i:s');
                                $systemlog = new system_log();
                                $costingid = '';

                           

                                

                                    //$condition = "where costing_stock_receipt.costing_id='$txnid'";
                                    if($rowdeletioncount>0){
                                        $condition = " where costing_waybill.id in ('".implode("','", $rows)."')";
                                        $costingid = '';

                                        $rs = query("select costing_waybill.id,
                                                            costing_waybill.costing_id,
                                                            costing_waybill.waybill_number,
                                                            costing_waybill.amount
                                                     from costing_waybill
                                                     $condition");
                                        while($obj=fetch($rs)){
                                            $id = $obj->id;
                                            $costingid = $obj->costing_id;
                                            $waybillnumber = $obj->waybill_number;

                                            query("delete from costing_waybill 
                                                   where costing_waybill.waybill_number='$waybillnumber' and 
                                                         costing_id='$costingid'");
                                            $deleters = query("delete from costing_waybill where id='$id'");
                                            if(mysql_affected_rows()==1){
                                                $systemlog->logInfo('COSTING',"Deleted Waybill Number","row_id=$id | costing_id=$costingid | waybill_number=$waybillnumber",$userid,$now,$costingid);
                                            }
                                            
                                        }

                                        //recomputeRCVCostingPerLineItem($costingid);

                                        if($costingid!=''){
                                            $rs = query("select id, amount
                                                         from costing 
                                                         where id='$costingid'");
                                            while($obj=fetch($rs)){
                                                $amount = $obj->amount;
                                            }
                                            $rowcount = 0;
                                            $getcountrs = query("select ifnull(count(*),0) as rowcount from costing_waybill where costing_id='$costingid' group by costing_id");
                                            while($obj=fetch($getcountrs)){
                                              $rowcount = $obj->rowcount;
                                            }

                                            $rowcount = $rowcount +1;
                                            $dividedamount = $amount/$rowcount;
                                            query("update costing_waybill set amount='$dividedamount' where costing_id='$costingid'");
                                        }


                            



                                    }


                                    ////////////////////////////// UPDATE AMOUNT //////////////////////////////////////////////
                                    $rs = query("select    
                                                            
                                                              costing.amount,
                                                              ifnull(sum(txn_waybill.package_actual_weight),0) as totalactualweight,
                                                              ifnull(waybillpackages.volweight,0) as totalvolweight,
                                                              case 
                                                                    when ifnull(sum(txn_waybill.package_actual_weight),0)>ifnull(waybillpackages.volweight,0) then ifnull(sum(txn_waybill.package_actual_weight),0)
                                                                    else ifnull(waybillpackages.volweight,0)
                                                              end as actualvolweight,
                                                              case 
                                                                    when ifnull(sum(txn_waybill.package_actual_weight),0)>ifnull(waybillpackages.volweight,0) then 'ACTUAL'
                                                                    else 'VOLUMETRIC'
                                                              end as targetweight
                                                      from costing 
                                                      left join costing_waybill on costing_waybill.costing_id=costing.id
                                                      left join txn_waybill on txn_waybill.waybill_number=costing_waybill.waybill_number
                                                      left join (
                                                                    select waybill_number,
                                                                            ifnull(sum(volumetric_weight),0) as volweight,
                                                                            ifnull(sum(actual_weight),0) as actualweight
                                                                    from txn_waybill_package_dimension
                                                                    where waybill_number in (select waybill_number from costing_waybill where costing_id='$txnid')
                                                                    group by txn_waybill_package_dimension.waybill_number
                                                                ) as waybillpackages on waybillpackages.waybill_number=costing_waybill.waybill_number
                                                      where costing.id='$txnid'
                                                      group by costing.id");
                                    $actualvolweight = 0;
                                    $grossamount = 0;
                                    $targetweight = '';
                                    while($obj=fetch($rs)){
                                          $grossamount = utfEncode($obj->amount);
                                          $actualvolweight = utfEncode($obj->actualvolweight);
                                          $targetweight = $obj->targetweight;
                                    }
                                                
                                      $updatercostingwaybillrs = query("
                                                                        select 
                                                                                date_format(costing_waybill.created_date,'%m/%d/%Y') as created_date,
                                                                                concat(cuser.first_name,' ',cuser.last_name) as createdby,
                                                                                ifnull(txn_waybill.package_actual_weight,0) as actualweight,
                                                                                ifnull(waybillpackages.volweight,0) as volweight,
                                                                                costing_waybill.waybill_number,
                                                                                case 
                                                                                        when '$targetweight'='ACTUAL' then round((($grossamount/$actualvolweight)*ifnull(txn_waybill.package_actual_weight,0)),2)
                                                                                        else round((($grossamount/$actualvolweight)*ifnull(waybillpackages.volweight,0)),2)
                                                                                end as distributedamount
                                                                        from costing_waybill 
                                                                        left join txn_waybill on txn_waybill.waybill_number=costing_waybill.waybill_number
                                                                        left join (
                                                                                    select waybill_number,
                                                                                            ifnull(sum(volumetric_weight),0) as volweight,
                                                                                            ifnull(sum(actual_weight),0) as actualweight
                                                                                    from txn_waybill_package_dimension
                                                                                    where waybill_number in (select waybill_number from costing_waybill where costing_id='$txnid')
                                                                                    group by txn_waybill_package_dimension.waybill_number
                                                                                ) as waybillpackages on waybillpackages.waybill_number=costing_waybill.waybill_number
                                                                        left join user as cuser on cuser.id=costing_waybill.created_by
                                                                        where costing_waybill.costing_id='$txnid'
                                                                        group by costing_waybill.waybill_number
                                      ");
                                      while($obj=fetch($updatercostingwaybillrs)){
                                        $distributedamount = $obj->distributedamount;
                                        query("update costing_waybill 
                                              set amount='$distributedamount' 
                                              where costing_id='$txnid' and 
                                                    waybill_number='$obj->waybill_number'");
                                      }       
                                      ////////////////////////////// UPDATE AMOUNT //////////////////////////////////////////////

                                    $response = array(
                                                       "response"=>'success'
                                                     );

                                    

                           


                        

                    /*}
                    else{
                            $response = array(
                                                    "response"=>'nouserpermission'
                                             );
                    }*/
                   
              
            print_r(json_encode($response));

        }
    }

    if(isset($_POST['deleteSelectedCostingDetails'])){
        if($_POST['deleteSelectedCostingDetails']=='skj$oihdtpoa$I#JFHOFDnO#2hDOihENnlDUnKEUbn'){

            /*$deleteaccess = hasAccess(USERID,ACCESSDELETECOSTNG);


          

                    if($deleteaccess==1){*/

                       

                                $rows = isset($_POST['rows'])&&$_POST['rows']!=''?$_POST['rows']:'';
                                $rowdeletioncount = count($rows);

                                $userid = USERID;
                                $now = date('Y-m-d H:i:s');
                                $systemlog = new system_log();

                           

                                

                                    //$condition = "where costing_stock_receipt.costing_id='$txnid'";
                                    if($rowdeletioncount>0){
                                        $condition = " where costing.id in ('".implode("','", $rows)."')";
                                    

                                        $rs = query("  select costing.id,
                                                              costing.chart_of_accounts_id, 
                                                              costing.amount,
                                                              costing.reference,
                                                              costing.prf_number,
                                                              costing.payee_id,
                                                              payee.payee_name,
                                                              payee.tin,
                                                              costing.payee_address,
                                                              costing.is_vatable,
                                                              case 
                                                                  when costing.is_vatable=1 then 'YES'
                                                                  else 'NO'
                                                              end as vatflag,
                                                              costing.vatable_amount,
                                                              costing.vat_amount,
                                                              date_format(costing.date,'%m/%d/%Y') as date,
                                                              date_format(costing.created_date,'%m/%d/%Y') as created_date,
                                                              date_format(costing.updated_date,'%m/%d/%Y') as updated_date,
                                                              concat(cuser.first_name,' ',cuser.last_name) as createdby,
                                                              concat(uuser.first_name,' ',uuser.last_name) as updatedby,
                                                              chart_of_accounts.description as chartofaccounts,
                                                              ifnull(count(distinct costing_waybill.waybill_number),0) as waybillcount,
                                                              expense_type.description as typeofaccount,
                                                              chart_of_accounts.expense_type_id
                                                      from costing 
                                                      left join chart_of_accounts on chart_of_accounts.id=costing.chart_of_accounts_id
                                                      left join expense_type on expense_type.id=chart_of_accounts.expense_type_id
                                                      left join user as cuser on cuser.id=costing.created_by
                                                      left join user as uuser on uuser.id=costing.updated_by
                                                      left join costing_waybill on costing_waybill.costing_id=costing.id
                                                      left join txn_waybill on txn_waybill.waybill_number=costing_waybill.waybill_number
                                                      left join payee on payee.id=costing.payee_id
                                                      $condition
                                                      group by costing.id");
                                        while($obj=fetch($rs)){
                                            $id = $obj->id;
                                            $account = $obj->chartofaccounts;
                                            $accountid = $obj->chart_of_accounts_id;
                                            $amount = $obj->amount;
                                            $vatableamount = $obj->vatable_amount;
                                            $vatflag = $obj->vatflag;
                                            $vat = $obj->vat_amount;
                                            $payeeid = $obj->payee_id;
                                            $payeename = $obj->payee_name;
                                            $payeeaddress = $obj->payee_address;
                                            $payeetin = $obj->tin;
                                            $amount = $obj->amount;
                                            $reference = $obj->reference;
                                            $prfnumber = $obj->prf_number;
                                            $date = $obj->date;

                                            $deleters = query("delete from costing where id='$id'");
                                            if(mysql_affected_rows()==1){
                                                $systemlog->logInfo('COSTING',"Deleted Costing","row_id=$id | chart_of_accounts_id=$accountid | chart_of_accounts=$account | payee=$payeename | payee_address=$payeeaddress | payee_tin=$payeetin | amount=$amount | vatable_amount=$vatableamount | vat_flag=$vatflag | vat=$vat | reference=$reference | prf_number=$prfnumber | date=$date",$userid,$now,$id);
                                            }
                                            
                                        }
                                    }

                                    $response = array(
                                                       "response"=>'success'
                                                     );

                                    

                           


                        

                   /* }
                    else{
                            $response = array(
                                                    "response"=>'nouserpermission'
                                             );
                    }*/
                   
              
            print_r(json_encode($response));

        }
    }

    
?>