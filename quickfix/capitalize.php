<?php
    include('../config/connection.php');

    /*$arr = ['shipper','consignee'];

    for($i=0;$i<count($arr);$i++){
        $table = $arr[$i];
        $rs = mysql_query("select * from $table");
        while($obj=mysql_fetch_object($rs)){
            $accountname = ucwords(strtolower($obj->account_name));
            $companyname = ucwords(strtolower($obj->company_name));

            mysql_query("update $table set account_name='$accountname', company_name='$companyname' where id='$obj->id'");
            
        }
    }

    $arr1 = ['origin_destination_port'];

    for($i=0;$i<count($arr1);$i++){
        $table = $arr1[$i];
        $rs = mysql_query("select * from $table");
        while($obj=mysql_fetch_object($rs)){
            $description = ucwords(strtolower($obj->description));

            mysql_query("update $table set description='$description' where id='$obj->id'");
            
        }
    }


    $arr2 = ['txn_waybill'];
    for($i=0;$i<count($arr2);$i++){
        $table = $arr2[$i];
        $rs = mysql_query("select * from $table");
        while($obj=mysql_fetch_object($rs)){
            $shipperaccountname = ucwords(strtolower($obj->shipper_account_name));
            $shippercompanyname = ucwords(strtolower($obj->shipper_company_name));
            $consigneeaccountname = ucwords(strtolower($obj->consignee_account_name));
            $consigneecompanyname = ucwords(strtolower($obj->consignee_company_name));
            mysql_query("update $table 
                         set shipper_account_name='$shipperaccountname', 
                             shipper_company_name='$shippercompanyname',
                             consignee_account_name='$consigneeaccountname', 
                             consignee_company_name='$consigneecompanyname'  
                         where id='$obj->id'");
        }
    }*/

    $arr2 = ['personnel'];
    for($i=0;$i<count($arr2);$i++){
        $table = $arr2[$i];
        $rs = mysql_query("select * from $table");
        while($obj=mysql_fetch_object($rs)){
            $firstname = ucwords(strtolower($obj->first_name));
            $lastname = ucwords(strtolower($obj->last_name));
            mysql_query("update $table 
                         set first_name='$firstname', 
                         last_name='$lastname'  
                         where id='$obj->id'");
        }
    }


    
?>