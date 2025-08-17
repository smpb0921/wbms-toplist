<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/agent.class.php");
    include("../classes/agent-contact.class.php");
    include("../classes/system-log.class.php");////////

	if(isset($_POST['AgentSaveEdit'])){
		if($_POST['AgentSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = escapeString($_POST['source']);
			$code = escapeString($_POST['code']);
			$companyname = escapeString($_POST['companyname']);
			$area = escapeString($_POST['area']);
			$street = escapeString($_POST['street']);
			$district = escapeString($_POST['district']);
			$city = escapeString($_POST['city']);
			$province = escapeString($_POST['province']);
			$zipcode = escapeString($_POST['zipcode']);
			$country = escapeString($_POST['country']);
			$remarks = escapeString($_POST['remarks']);



			@$contact = $_POST['contact'];
			@$email = $_POST['email'];
			@$phonenumber= $_POST['phonenumber'];
			@$mobilenumber = $_POST['mobilenumber'];
			@$defaultflag = $_POST['defaultflag'];
			$iterate = count($contact);

			

			$qry = "select * from agent where upper(code)='$code'";
			if($source=='edit'){
				$rowid = escapeString($_POST['id']);
				$qry = "select * from agent where upper(code)='$code' and id!='$rowid'";

				} 
				
			$rs = query($qry);
			if(getNumRows($rs)==0){
				$userid = USERID;
				$now = date('Y-m-d H:i:s');
				$agentclass = new agent();
				$agentcontactclass = new agent_contact();
				$systemlog = new system_log();

				if($source=='add'){
					$agentclass->insert(array('',$code,$companyname,$street,$district,$city,$province,$zipcode,$country,$area,$remarks,$userid,$now,'NULL','NULL'));
					$id = $agentclass->getInsertId();
					$systemlog->logAddedInfo($agentclass,array($id,$code,$companyname,$street,$district,$city,$province,$zipcode,$country,$area,$remarks,$userid,$now,'NULL','NULL'),'AGENT','New Agent Added',$userid,$now);
				}
				else if($source=='edit'){
					$id = escapeString($_POST['id']);
					$systemlog->logEditedInfo($agentclass,$id,array($id,$code,$companyname,$street,$district,$city,$province,$zipcode,$country,$area,$remarks,'','',$userid,$now),'AGENT','Edited Agent Info',$userid,$now);/// log should be before update is made
					$agentclass->update($id,array($code,$companyname,$street,$district,$city,$province,$zipcode,$country,$area,$remarks,'NOCHANGE','NOCHANGE',$userid,$now));
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
								escapeString($defaultflag[$i])
							    );
					array_push($contactdata, $temp);
				}

				$agentcontactclass->deleteWhere("where agent_id=".$id);
				if($iterate>0){
					$agentcontactclass->insertMultiple($contactdata);
				}
				echo "success";
			}
			else{
				echo "codeexist";
			}

		}
	}

	if(isset($_POST['AgentGetInfo'])){
		if($_POST['AgentGetInfo']=='kjoI$H2oiaah3h0$09jDppo92po@k@'){
			$id = escapeString($_POST['id']);

			$rs = query("       select agent.id,
							    	   agent.code,
							           agent.company_name,
							           agent.company_street_address,
							           agent.company_district,
							           agent.company_city,
							           agent.company_state_province,
							           agent.company_zip_code,
							           agent.company_country,
							           agent.area,
							           agent.remarks
							    from agent
								where agent.id='$id'
				 	    ");

			if(getNumRows($rs)==1){

				while($obj=fetch($rs)){
					$dataarray = array(
										   "id"=>utfEncode($obj->id),
										   "code"=>utfEncode($obj->code),
										   "companyname"=>utfEncode($obj->company_name),
										   "area"=>utfEncode($obj->area),
										   "remarks"=>utfEncode($obj->remarks),
										   "street"=>utfEncode($obj->company_street_address),
										   "district"=>utfEncode($obj->company_district),
										   "city"=>utfEncode($obj->company_city),
										   "province"=>utfEncode($obj->company_state_province),
										   "zipcode"=>utfEncode($obj->company_zip_code),
										   "country"=>utfEncode($obj->company_country),
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


	if(isset($_POST['AgentContactGetInfo'])){
		if($_POST['AgentContactGetInfo']=='kjoI$H2oiaah3h0$09jDppo92po@k@'){
				$id = escapeString($_POST['id']);
				$rs = query("select * from agent_contact where agent_id='$id'");
				$tmp = array();
				while($obj=fetch($rs)){
					$tmpinner = array(
										   "contact"=>utfEncode($obj->contact_name),
										   "phone"=>utfEncode($obj->phone_number),
										   "email"=>utfEncode($obj->email_address),
										   "mobile"=>utfEncode($obj->mobile_number),
										   "defaultflag"=>utfEncode($obj->default_flag),
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

	        	$rs = query("delete from agent where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }




?>
