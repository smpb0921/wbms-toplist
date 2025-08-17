<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/user-group.class.php");
    include("../classes/system-log.class.php");////////

	if(isset($_POST['usergroupSaveEdit'])){
		if($_POST['usergroupSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = escapeString($_POST['source']);
			$code = escapeString(strtoupper(trim($_POST['code'])));
			$desc = escapeString($_POST['desc']);
			$userid = USERID;
			$now = date('Y-m-d H:i:s');
			$usergroupclass = new user_group();
			$systemlog = new system_log();


			if($source=='edit'){
				$id = escapeString($_POST['id']);
				$query = "select * from user_group where code='$code' and id!='$id'";
			}
			else{
				$query = "select * from user_group where code='$code'";
			}
			$rs = query($query);

			if(getNumRows($rs)==0){
			
				if($source=='add'){
					$usergroupclass->insert(array('',$code,$desc,$userid,$now,'NULL','NULL'));
					$id = $usergroupclass->getInsertId();
					$systemlog->logAddedInfo($usergroupclass,array($id,$code,$desc,$userid,$now,'NULL','NULL'),'User Group','New User Group Added',$userid,$now);
					echo "success";
				}
				else if($source=='edit'){
						$id = escapeString($_POST['id']);
					
						$systemlog->logEditedInfo($usergroupclass,$id,array('',$code,$desc,'NOCHANGE','NOCHANGE',$userid,$now),'User Group','Edited User Group Info',$userid,$now);/// log should be before update is made
						$usergroupclass->update($id,array($code,$desc,'NOCHANGE','NOCHANGE',$userid,$now));
						echo "success";

				}
			}
			else{
				echo "codeexists";
			}
		}
			
	}

	/*if(isset($_POST['deleteselected'])){
		$idarray = $_POST['id'];
		$tmp = array();
		foreach ($idarray as $id) {
			$delete = escapeString($id);
			array_push($tmp, $delete);
		}
		$deleteids = "(".implode(',', $tmp).")";
		$ugclass = new user_group();
		$ugclass->deleteMultiple($deleteids);
		echo "success";

	}*/
	if(isset($_POST['deleteSelectedRows'])){
        if($_POST['deleteSelectedRows']=='skj$oihdtpoa$I#@4noi4AIFNlskoRboIh4!j3sio$*yhs'){
        	@$data = $_POST['data'];
	        $itemsiterate = count($data);

	        for($i=0;$i<$itemsiterate;$i++){
	        	$id = mysql_real_escape_string($data[$i]);

	        	$rs = query("delete from user_group where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }


    if(isset($_POST['DLnslrii3hl34p03ddnfo4i'])){//edit user permission - checks exisiting user access
    	if($_POST['DLnslrii3hl34p03ddnfo4i']=='sdfei3opod30napri'){
    		$access = array();
    		$id = escapeString($_POST['id']);
    		$query = "select * from user_group_rights where user_group_id='$id'";
    		$rs = query($query);
    		while($obj=fetch($rs)){
    			array_push($access, $obj->menu_id);
    		}
    		echo implode(',', $access);
    	}
    }


    if(isset($_POST['SaveUserGroupPermissions'])){//edit user permission - checks exisiting user access
    	if($_POST['SaveUserGroupPermissions']=='fjh$O2sFlfp$3oiml!@spoa'){
    		$id = escapeString($_POST['id']);


    							////////// USER RIGHTS ///////////////////
								query("delete from user_group_rights where user_group_id='$id'");
								@$useraccess = $_POST['usergroupaccess'];
								$accesscount = count($useraccess);
								//print_r($useraccess);
								for($i=0;$i<$accesscount;$i++){
									$access = escapeString($useraccess[$i]);
									$query = "insert into user_group_rights(user_group_id, menu_id) values('$id','$access')";
									$rs = query($query);
								}
								/////////////////////////////////////

								echo 'success';

    	}
    }


?>

