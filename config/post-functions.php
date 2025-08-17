<?php
	require_once("connection.php");
	require_once("functions.php");

	if(isset($_POST['getLoggedUserAndNextRef'])&&$_POST['getLoggedUserAndNextRef']=='Fns!oi3ah434ad#2l211#$*3%'){
        $user_id = USERID;
        $transactionid = escapeString($_POST['transactionid']);
        $name = utfEncode(getNameOfUser($user_id));

        $rs = query("select * from user where user.id='".USERID."'");
        $datetoday = date('m/d/Y');
       
        echo $name."@#$%".getNextTransactionNumber($transactionid)."@#$%".$user_id."@#$%".$datetoday;

    }


    if(isset($_POST['getDefaultUserLocation'])&&$_POST['getDefaultUserLocation']=='Fns!oi3ah434ad#2l211#$*3%'){

        $data = array();
        $rs = query("select user.location_id,
                            location.code as loccode,
                            location.description as locdesc
                    from user 
                    left join location on location.id=user.location_id
                    where user.id='".USERID."'");

        while($obj=fetch($rs)){
            $data = array(  
                                "loccode"=>utfEncode($obj->loccode),
                                "locdesc"=>utfEncode($obj->locdesc),
                                "locid"=>$obj->location_id
                );
        }

        echo json_encode($data);


    }

    if(isset($_POST['generaterandompassword'])&&$_POST['generaterandompassword']=='Fns!oi3ah434ad#2l211#$*3%'){
        $pword = randomPassword();
        $flag = true;
        $x = 0;
        while($flag&&$x<50){
            $rs = query("select * from account_executive where password='$pword'");
            $rscount = getNumRows($rs);
            if($rscount==0){
                $flag = false;
            }
            else{
                $pword = randomPassword();
            }
            $x++;
        }
        echo $pword;
    }

    if(isset($_POST['getZipCode'])&&$_POST['getZipCode']=='Fns!oi3ah434ad#2l211#$*3%'){

        $district = isset($_POST['district'])?escapeString($_POST['district']):'';
        $city = isset($_POST['city'])?escapeString($_POST['city']):'';
        $zip = '';

        $rs = query("select * from district_city_zipcode where city='$city' and district_barangay='$district'");
        if(getNumRows($rs)==1){
            while($obj=fetch($rs)){
                $zip = utfEncode($obj->zip_code);
            }
        }

        
        echo $zip;


    }


    if(isset($_POST['CheckIfDeletionAllowed'])&&$_POST['CheckIfDeletionAllowed']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){

        $table = escapeString($_POST['table']);
        $column = escapeString($_POST['column']);
        $val = escapeString($_POST['val']);

        $flag = usedAsForeignKey(DBNAME,$table,$column,$val);

        if($flag==1){
            echo "false";
        }
        else if($flag==0){
            echo "true";
        }

    }

    if(isset($_POST['defaulDoortoDoor'])){
        if($_POST['defaulDoortoDoor']=='oiskus49Fnla3#Oih4noiI$IO@Y#*h@o3sk'){

            $serviceid = '';
            $service = '';
            $defaultserviceid = getInfo("company_information","default_service","where id=1");
            if($defaultserviceid>0){
                $serviceid = $defaultserviceid;

                $rs = query("select * from services where id='$defaultserviceid'");
                while($obj=fetch($rs)){
                    $service = $obj->description;
                }
            }

            $dataarray = array(
                                       "response"=>'success',
                                       "serviceid"=>$serviceid,
                                       "service"=>utfEncode($service)
                               );
            print_r(json_encode($dataarray));




        }
    }


    

	
?>