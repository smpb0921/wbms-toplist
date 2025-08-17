<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/other-charges.class.php");
    include("../classes/system-log.class.php");////////

	if(isset($_POST['otherchargesSaveEdit'])){
		if($_POST['otherchargesSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = escapeString($_POST['source']);
			$code = escapeString(strtoupper(trim($_POST['code'])));
			$desc = escapeString($_POST['desc']);
			$defaultvalue = escapeString($_POST['defaultvalue']);
			$userid = USERID;
			$now = date('Y-m-d H:i:s');
			$otherchargesclass = new other_charges();
			$systemlog = new system_log();


			if($source=='edit'){
				$id = escapeString($_POST['id']);
				$query = "select * from other_charges where code='$code' and id!='$id'";
			}
			else{
				$query = "select * from other_charges where code='$code'";
			}
			$rs = query($query);

			if(getNumRows($rs)==0){
			
				if($source=='add'){
					$otherchargesclass->insert(array('',$code,$desc,$defaultvalue,$userid,$now,'NULL','NULL'));
					$id = $otherchargesclass->getInsertId();
					$systemlog->logAddedInfo($otherchargesclass,array($id,$code,$desc,$defaultvalue,$userid,$now,'NULL','NULL'),'Other Charges','New Other Charges Added',$userid,$now);
					echo "success";
				}
				else if($source=='edit'){
						$id = escapeString($_POST['id']);
					
						$systemlog->logEditedInfo($otherchargesclass,$id,array('',$code,$desc,$defaultvalue,'NOCHANGE','NOCHANGE',$userid,$now),'Other Charges','Edited Other Charges Info',$userid,$now);/// log should be before update is made
						$otherchargesclass->update($id,array($code,$desc,$defaultvalue,'NOCHANGE','NOCHANGE',$userid,$now));
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

	        	$rs = query("delete from other_charges where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }



?>

