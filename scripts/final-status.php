<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/final-status.class.php");
    include("../classes/system-log.class.php");////////

	if(isset($_POST['finalstatusSaveEdit'])){
		if($_POST['finalstatusSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = escapeString($_POST['source']);
			$status = escapeString(strtoupper(trim($_POST['status'])));
			$userid = USERID;
			$now = date('Y-m-d H:i:s');
			$finalstatusclass = new no_update_status();
			$systemlog = new system_log();


			if($source=='edit'){
				$id = escapeString($_POST['id']);
				$query = "select * from no_update_status where status='$status' and id!='$id'";
			}
			else{
				$query = "select * from no_update_status where status='$status'";
			}
			$rs = query($query);

			if(getNumRows($rs)==0){
			
				if($source=='add'){
					$finalstatusclass->insert(array('',$status,$userid,$now,'NULL','NULL'));
					$id = $finalstatusclass->getInsertId();
					$systemlog->logAddedInfo($finalstatusclass,array($id,$status,$userid,$now,'NULL','NULL'),'Final Waybill Status','New Final Status Added',$userid,$now);
					echo "success";
				}
				else if($source=='edit'){
						$id = escapeString($_POST['id']);
					
						$systemlog->logEditedInfo($finalstatusclass,$id,array('',$status,'NOCHANGE','NOCHANGE',$userid,$now),'Final Waybill Status','Edited Final Status',$userid,$now);/// log should be before update is made
						$finalstatusclass->update($id,array($status,'NOCHANGE','NOCHANGE',$userid,$now));
						echo "success";

				}
			}
			else{
				echo "statusexists";
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

	        	$rs = query("delete from no_update_status where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }



?>

