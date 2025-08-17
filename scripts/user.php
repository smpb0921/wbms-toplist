<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/user.class.php");
    include("../classes/user-location.class.php");
    include("../classes/system-log.class.php");
    //include('email-class.php');

	if(isset($_POST['userSaveEditTransaction'])){
		if($_POST['userSaveEditTransaction']=='sdfsdFJso#Nj@Ho1iss4z4$Oi#l'){

			
			$fname = flcapital(escapeString($_POST['fname']));
			$mname = flcapital(escapeString($_POST['mname']));
			$lname = flcapital(escapeString($_POST['lname']));
			$activeflag = escapeString($_POST['activeflag']);
			$mobile = escapeString($_POST['mobile']);
			$loc = escapeString($_POST['loc']);
			$email = escapeString($_POST['email']);
			$username = escapeString(strtolower($_POST['username']));
			$usergroup = escapeString($_POST['usergroup']);
			@$otherlocarray = $_POST['otherloc'];

			$couriersupervisor = escapeString($_POST['couriersupervisor']);
			$freightsupervisor = escapeString($_POST['freightsupervisor']);


		

			 //randomPassword();
			$now = date('Y-m-d H:i:s');

			$source = escapeString($_POST['action']);

			$userclass = new user();
			$systemlog = new system_log();

			
						/*if($couriersupervisor==1){
							query("update user set courier_supervisor_flag=0");
						}
						if($freightsupervisor==1){
							query("update user set freight_supervisor_flag=0");
						}*/
		
						$checking = false;
						if($source=='add'){
							$pwd = md5('12345');
							if(checkIfExist("user","where username='".$username."'")==false){	
								$rs = $userclass->insert(array('',$fname,$mname,$lname,$username,$pwd,$email,$activeflag,$loc,$now,USERID,'NULL','NULL',0,0,$usergroup,$mobile,$couriersupervisor,$freightsupervisor));
								$id = $userclass->getInsertId();
								$systemlog->logAddedInfo($userclass,array($id,$fname,$mname,$lname,$username,$pwd,$email,$activeflag,$loc,$now,USERID,'NULL','NULL',0,0,$usergroup,$mobile,$couriersupervisor,$freightsupervisor),'USER','New User Added',USERID,$now);
								$checking = true;
							}
							else{
								echo "usernameexist";
							}

						}
						else if($source=='edit'){
							$id = escapeString($_POST['id']);
							if(checkIfExist("user","where username='".$username."' and id!='".$id."'")==false){
								$systemlog->logEditedInfo($userclass,$id,array($id,$fname,$mname,$lname,$username,'NOCHANGE',$email,$activeflag,$loc,$now,USERID,'NULL','NULL',0,0,$usergroup,$mobile,$couriersupervisor,$freightsupervisor),'USER','Edited User Info',USERID,$now);/// log should be before update is made
								$rs = $userclass->update($id,array($fname,$mname,$lname,$username,'NOCHANGE',$email,$activeflag,$loc,$now,USERID,'NULL','NULL',0,0,$usergroup,$mobile,$couriersupervisor,$freightsupervisor));
								$checking = true;
							}
							else{
								echo "usernameexist";
							}
						}

						if($checking==true){
								////////// USER RIGHTS ///////////////////
								query("delete from user_rights where user_id='$id'");
								@$useraccess = $_POST['useraccess'];
								$accesscount = count($useraccess);
								for($i=0;$i<$accesscount;$i++){
									$access = escapeString($useraccess[$i]);
									$query = "insert into user_rights(user_id, menu_id) values('$id','$access')";
									$rs = query($query);
								}
								/////////////////////////////////////



								/**** OTHER WAREHOUSE ***/
								$userlocclass = new user_location();
								$userlocclass->deleteWhere("where user_id=".$id);
								$locdata = array();
								
								if($_POST['otherloc']!=null){
									for($i=0;$i<count($otherlocarray);$i++){
										$otherloc = array();
										array_push($otherloc, $id, $otherlocarray[$i]);
										array_push($locdata, $otherloc);
									}
									if(count($otherlocarray)>0){
										$userlocclass->insertMultiple($locdata);
									}
								}
								/**** OTHER WAREHOUSE - END ***/


								

								echo "success";



						}

						
				

		
					
		
		}				
	}

	if(isset($_POST['deleteselected'])){
		$idarray = $_POST['id'];
		$tmp = array();
		foreach ($idarray as $id) {
			$delete = escapeString($id);
			array_push($tmp, $delete);
		}
		$deleteids = "(".implode(',', $tmp).")";
		$userclass = new User();
		$userclass->deleteMultiple($deleteids);
		echo "success";

	}

	if(isset($_POST['otherLocations'])){
		if($_POST['otherLocations']=='sdfed#n2L1hfi$n#opi3opod30napri'){
			$userid = escapeString($_POST['id']);
			$otherwhse = getUserOtherLocations($userid);

			$dataarray = array(
								 "otherwhse"=>$otherwhse
							  );
			print_r(json_encode($dataarray));

		}
	}

	if(isset($_POST['getusergroups'])){
		if($_POST['getusergroups']=='koI$BO#psoa5ni$n#opi3opod90jlknp'){
			$userid = escapeString($_POST['id']);
			$userug = getUserUsergoups($userid);

			$dataarray = array(
								 "usergroups"=>$userug
							  );
			print_r(json_encode($dataarray));

		}
	}


	if(isset($_POST['ki45lKJn3idlky'])){//reset user password
		if($_POST['ki45lKJn3idlky']=='3kh$klkddj4%l'){
			$id = escapeString($_POST['id']);
			$systemlog = new system_log();
			$pwd = '12345';
			$resetpword=md5($pwd);
			$now = date('Y-m-d H:i:s');
			$userid = USERID;
			$query = "update user set password='$resetpword', updated_date='$now', updated_by='$userid' where id='$id'";
			$rs=query($query);
			if($rs){
				if($userid==$id){
					$_SESSION['zfie@#MKLDFi934OPedieo'] = $resetpword;
				}
				$systemlog->logInfo('USER',"Reset Password","User ID: $id",$userid,$now);
				echo "success";
			}
		}
	}

	if(isset($_POST['KsornKdjeiu00doo93'])){//check if username exist
		if($_POST['KsornKdjeiu00doo93']=='KDlojs47kklqib'){
			$input = escapeString($_POST['input']);
			$query = "select * from user where username='$input'";
			$rs=query($query);
			$count = getNumRows($rs);
			if($count==1){
				echo 'exist';
			}
			else{
				echo "not exist";
			}
		}
	}

	if(isset($_POST['DLnslrii3hl34p03ddnfo4i'])){//edit user permission - checks exisiting user access
    	if($_POST['DLnslrii3hl34p03ddnfo4i']=='sdfei3opod30napri'){
    		$access = array();
    		$id = escapeString($_POST['id']);
    		$query = "select * from user_rights where user_id='$id'";
    		$rs = query($query);
    		while($obj=fetch($rs)){
    			array_push($access, $obj->menu_id);
    		}
    		echo implode(',', $access);
    	}
    }

	


?>