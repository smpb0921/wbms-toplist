<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/personnel.class.php");
    include("../classes/system-log.class.php");////////

	if(isset($_POST['personnelSaveEdit'])){
		if($_POST['personnelSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
				$status = escapeString($_POST['status']);
				$source = escapeString($_POST['source']);
				$firstname = escapeString(trim($_POST['firstname']));
				$lastname = escapeString(trim($_POST['lastname']));
				$contact = escapeString(trim($_POST['contact']));
				$position = escapeString(strtoupper(trim($_POST['position'])));
				$type = escapeString(strtoupper(trim($_POST['type'])));
				$userid = USERID;
				$now = date('Y-m-d H:i:s');
				$personnelclass = new personnel();
				$systemlog = new system_log();


			
				if($source=='add'){
					$personnelclass->insert(array('',$firstname,$lastname,$position,$type,$contact,$userid,$now,'NULL','NULL',$status));
					$id = $personnelclass->getInsertId();
					$systemlog->logAddedInfo($personnelclass,array($id,$firstname,$lastname,$position,$type,$contact,$userid,$now,'NULL','NULL',$status),'PERSONNEL','New Personnel Added',$userid,$now);
					echo "success";
				}
				else if($source=='edit'){
						$id = escapeString($_POST['id']);
					
						$systemlog->logEditedInfo($personnelclass,$id,array($id,$firstname,$lastname,$position,$type,$contact,'NOCHANGE','NOCHANGE',$userid,$now,$status),'PERSONNEL','Edited Personnel Info',$userid,$now);/// log should be before update is made
						$personnelclass->update($id,array($firstname,$lastname,$position,$type,$contact,'NOCHANGE','NOCHANGE',$userid,$now,$status));
						echo "success";

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

	        	$rs = query("delete from personnel where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }



?>

