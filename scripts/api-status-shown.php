<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/api-status-shown.class.php");
    include("../classes/system-log.class.php");////////

	if(isset($_POST['apistatusshownSaveEdit'])){
		if($_POST['apistatusshownSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = escapeString($_POST['source']);
			$desc = escapeString(strtoupper($_POST['desc']));
			$userid = USERID;
			$now = date('Y-m-d H:i:s');
			$apistatusshownclass = new webapi_status_shown();
			$systemlog = new system_log();


			if($source=='edit'){
				$id = escapeString($_POST['id']);
				$query = "select * from webapi_status_shown where upper(status)='$desc' and id!='$id'";
			}
			else{
				$query = "select * from webapi_status_shown where upper(status)='$desc'";
			}
			$rs = query($query);

			if(getNumRows($rs)==0){
			
				if($source=='add'){
					$apistatusshownclass->insert(array('',$desc));
					$id = $apistatusshownclass->getInsertId();
					$systemlog->logAddedInfo($apistatusshownclass,array($id,$desc),'API STATUS SHOWN','New API Status Shown Added',$userid,$now);
					echo "success";
				}
				else if($source=='edit'){
						$id = escapeString($_POST['id']);
					
						$systemlog->logEditedInfo($apistatusshownclass,$id,array('',$desc),'API STATUS SHOWN','Edited API Status Shown',$userid,$now);/// log should be before update is made
						$apistatusshownclass->update($id,array($desc));
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
	        $systemlog = new system_log();
	        $userid = USERID;
	        $now = date('Y-m-d H:i:s');

	        for($i=0;$i<$itemsiterate;$i++){
	        	$id = mysql_real_escape_string($data[$i]);
	        	$statusdesc = getInfo("webapi_status_shown","status","where id='$id'");


	        	$rs = query("delete from webapi_status_shown where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}
	        	else{
	        		$systemlog->logInfo('API STATUS SHOWN','Deleted API Status Shown',"System ID: ".$id."; Status: $statusdesc",$userid,$now);
	        	}

	        }

	        echo "success";
       	}
    }



?>

