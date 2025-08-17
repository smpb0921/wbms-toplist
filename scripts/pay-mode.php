<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/pay-mode.class.php");
    include("../classes/system-log.class.php");////////

	if(isset($_POST['paymodeSaveEdit'])){
		if($_POST['paymodeSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = escapeString($_POST['source']);
			$code = escapeString(strtoupper(trim($_POST['code'])));
			$desc = escapeString($_POST['desc']);
			$userid = USERID;
			$now = date('Y-m-d H:i:s');
			$paymodeclass = new pay_mode();
			$systemlog = new system_log();


			if($source=='edit'){
				$id = escapeString($_POST['id']);
				$query = "select * from pay_mode where code='$code' and id!='$id'";
			}
			else{
				$query = "select * from pay_mode where code='$code'";
			}
			$rs = query($query);

			if(getNumRows($rs)==0){
			
				if($source=='add'){
					$paymodeclass->insert(array('',$code,$desc,$userid,$now,'NULL','NULL'));
					$id = $paymodeclass->getInsertId();
					$systemlog->logAddedInfo($paymodeclass,array($id,$code,$desc,$userid,$now,'NULL','NULL'),'Pay Mode','New Pay Mode Added',$userid,$now);
					echo "success";
				}
				else if($source=='edit'){
						$id = escapeString($_POST['id']);
					
						$systemlog->logEditedInfo($paymodeclass,$id,array('',$code,$desc,'NOCHANGE','NOCHANGE',$userid,$now),'Pay Mode','Edited Pay Mode Info',$userid,$now);/// log should be before update is made
						$paymodeclass->update($id,array($code,$desc,'NOCHANGE','NOCHANGE',$userid,$now));
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

	        	$rs = query("delete from pay_mode where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }



?>

