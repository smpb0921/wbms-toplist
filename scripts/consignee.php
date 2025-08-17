<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/consignee.class.php");
    include("../classes/consignee-contact.class.php");
    include("../classes/system-log.class.php");////////

	if(isset($_POST['consigneeSaveEdit'])){
		if($_POST['consigneeSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = escapeString($_POST['source']);
			$accountnumber = escapeString(strtoupper($_POST['accountnumber']));
			$accountname = escapeString(ucwords(strtolower($_POST['accountname'])));
			$companyname = escapeString(ucwords(strtolower($_POST['companyname'])));
			$idnumber = trim($_POST['idnumber'])==''?'NULL':escapeString($_POST['idnumber']);
			$street = trim($_POST['street'])==''?'NULL':escapeString($_POST['street']);
			$district = trim($_POST['district'])==''?'NULL':escapeString($_POST['district']);
			$city = trim($_POST['city'])==''?'NULL':escapeString($_POST['city']);
			$province = trim($_POST['province'])==''?'NULL':escapeString($_POST['province']);
			$zipcode = trim($_POST['zipcode'])==''?'NULL':escapeString($_POST['zipcode']);
			$country = trim($_POST['country'])==''?'NULL':escapeString($_POST['country']);



			@$contact = $_POST['contact'];
			@$email = $_POST['email'];
			@$phonenumber= $_POST['phonenumber'];
			@$mobilenumber = $_POST['mobilenumber'];
			@$defaultflag = $_POST['defaultflag'];
			@$sendsmsflag = $_POST['sendsmsflag'];
			@$sendemailflag = $_POST['sendemailflag'];
			$iterate = count($contact);

			

			$qry = "select * from consignee where upper(account_number)='$accountnumber'";
			if($source=='edit'){
				$rowid = escapeString($_POST['id']);
				$qry = "select * from consignee where upper(account_number)='$accountnumber' and id!='$rowid'";

			} 
				
			$rs = query($qry);
			if(getNumRows($rs)==0){

				$qry2 = "select * from consignee where upper(id_number)='$idnumber'";
				if($source=='edit'){
					$rowid = escapeString($_POST['id']);
					$qry2 = "select * from consignee where upper(id_number)='$idnumber' and id!='$rowid'";

				} 
					
				$checkidnumberrs = query($qry2);
				if(getNumRows($checkidnumberrs)==0){

							$userid = USERID;
							$now = date('Y-m-d H:i:s');
							$consigneeclass = new consignee();
							$consigneecontactclass = new consignee_contact();
							$systemlog = new system_log();

							if($source=='add'){
								$accountnumber = getTransactionNumber(7);
								$consigneeclass->insert(array('',$accountnumber,$accountname,$companyname,$street,$district,$city,$province,$zipcode,$country,$userid,$now,'NULL','NULL',0,$idnumber));
								$id = $consigneeclass->getInsertId();
								$systemlog->logAddedInfo($consigneeclass,array($id,$accountnumber,$accountname,$companyname,$street,$district,$city,$province,$zipcode,$country,$userid,$now,'NULL','NULL',0,$idnumber),'CONSIGNEE','New Consignee Added',$userid,$now);
							}
							else if($source=='edit'){
								$id = escapeString($_POST['id']);
								$inactiveflag = escapeString($_POST['inactiveflag']);
								$systemlog->logEditedInfo($consigneeclass,$id,array($id,$accountnumber,$accountname,$companyname,$street,$district,$city,$province,$zipcode,$country,'','',$userid,$now,$inactiveflag,$idnumber),'CONSIGNEE','Edited Consignee Info',$userid,$now);/// log should be before update is made
								$consigneeclass->update($id,array($accountnumber,$accountname,$companyname,$street,$district,$city,$province,$zipcode,$country,'NOCHANGE','NOCHANGE',$userid,$now,$inactiveflag,$idnumber));
							}

							$contactdata = array();
							for($i=0;$i<$iterate;$i++){
								$temp = array();
								array_push($temp,
											NULL,
										    $id,//agentID
										    escapeString($contact[$i]),
											escapeString($phonenumber[$i]),
											escapeString($email[$i]),
											escapeString($mobilenumber[$i]),
											$now,
											escapeString($defaultflag[$i]),
											escapeString($sendsmsflag[$i]),
											escapeString($sendemailflag[$i])
										    );
								array_push($contactdata, $temp);
							}

							$consigneecontactclass->deleteWhere("where consignee_id=".$id);
							if($iterate>0){
								$consigneecontactclass->insertMultiple($contactdata);
							}
							echo "success";
				}
				else{
					echo "idnumberexists";
				}
			}
			else{
				echo "codeexist";
			}

		}
	}

	if(isset($_POST['ConsigneeGetInfo'])){
		if($_POST['ConsigneeGetInfo']=='kjoI$H2oiaah3h0$09jDppo92po@k@'){
			$id = escapeString($_POST['id']);

			$rs = query("       select consignee.id,
							    	   consignee.account_number,
							    	   consignee.account_name,
							           consignee.company_name,
							           consignee.company_street_address,
							           consignee.company_district,
							           consignee.company_city,
							           consignee.company_state_province,
							           consignee.company_zip_code,
							           consignee.company_country,
							           consignee.id_number,
							           consignee.inactive_flag
							    from consignee
								where consignee.id='$id'
				 	    ");

			if(getNumRows($rs)==1){

				
				

				while($obj=fetch($rs)){


					$odaflag = 'UNDEFINED';
					$barangay = strtoupper(trim($obj->company_district));

					if(trim($obj->company_district)!=''){
						$rs1 = query("select GROUP_CONCAT(distinct oda_flag) as odaflag, 
							                 district_barangay 
							          FROM `district_city_zipcode` 
							          where upper(district_barangay)='$barangay'
							          group by district_barangay");

						while($obj1=fetch($rs1)){
							if($obj1->odaflag==1||$obj1->odaflag==0){
								$odaflag = $obj1->odaflag;
							}
							else{
								$odaflag = 'UNDEFINED';
							}
						}
					}	

					$provinceid = '';
					$province = strtoupper(trim($obj->company_state_province));

					if(trim($obj->company_state_province)!=''){
						$rs2 = query("select id
								      FROM `origin_destination_port` 
								      where upper(description)='$province'");
						while($obj2=fetch($rs2)){
							$provinceid = $obj2->id;
						}
					}



					$dataarray = array(
										   "id"=>$obj->id,
										   "accountnumber"=>utfEncode($obj->account_number),
										   "accountname"=>utfEncode($obj->account_name),
										   "companyname"=>utfEncode($obj->company_name),
										   "idnumber"=>utfEncode($obj->id_number),
										   "street"=>utfEncode($obj->company_street_address),
										   "district"=>utfEncode($obj->company_district),
										   "city"=>utfEncode($obj->company_city),
										   "province"=>utfEncode($obj->company_state_province),
										   "provinceid"=>utfEncode($provinceid),
										   "zipcode"=>utfEncode($obj->company_zip_code),
										   "country"=>utfEncode($obj->company_country),
										   "inactiveflag"=>$obj->inactive_flag,
										   "odaflag"=>$odaflag,
										   "response"=>'success'

										  
										   );
				}
				print_r(json_encode($dataarray));
				
			}
			else{
				$dataarray = array(
										  
										   "response"=>'invalidID'

										  
								  );
				
				print_r(json_encode($dataarray));
			}




		


		}
	}


	if(isset($_POST['ConsigneeContactGetInfo'])){
		if($_POST['ConsigneeContactGetInfo']=='kjoI$H2oiaah3h0$09jDppo92po@k@'){
				$id = escapeString($_POST['id']);
				$rs = query("select * from consignee_contact where consignee_id='$id'");
				$tmp = array();
				while($obj=fetch($rs)){
					$tmpinner = array(
										   "contact"=>utfEncode($obj->contact_name),
										   "phone"=>utfEncode($obj->phone_number),
										   "email"=>utfEncode($obj->email_address),
										   "mobile"=>utfEncode($obj->mobile_number),
										   "defaultflag"=>$obj->default_flag,
										   "sendsmsflag"=>$obj->send_sms_flag,
										   "sendemailflag"=>$obj->send_email_flag,
										   "response"=>'success'

										  
										   );
					array_push($tmp, $tmpinner);
				}
				echo json_encode($tmp);
		}
	}

	if(isset($_POST['deleteSelectedRows'])){
        if($_POST['deleteSelectedRows']=='skj$oihdtpoa$I#@4noi4AIFNlskoRboIh4!j3sio$*yhs'){
        	@$data = $_POST['data'];
	        $itemsiterate = count($data);

	        for($i=0;$i<$itemsiterate;$i++){
	        	$id = mysql_real_escape_string($data[$i]);

	        	$rs = query("delete from consignee where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }




?>
