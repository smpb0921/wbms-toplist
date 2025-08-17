<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/chart-of-accounts.class.php");
    include("../classes/system-log.class.php");////////

	if(isset($_POST['chartofaccountsSaveEdit'])){
		if($_POST['chartofaccountsSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = escapeString($_POST['source']);
			$code = escapeString(strtoupper(trim($_POST['code'])));
			$desc = escapeString($_POST['desc']);
			$type = isset($_POST['type'])?escapeString($_POST['type']):'NULL';
			$producttype = isset($_POST['producttype'])?escapeString($_POST['producttype']):'NULL';
			$userid = USERID;
			$now = date('Y-m-d H:i:s');
			$chartofaccountsclass = new chart_of_accounts();
			$systemlog = new system_log();


			if($source=='edit'){
				$id = escapeString($_POST['id']);
				$query = "select * from chart_of_accounts where code='$code' and id!='$id'";
			}
			else{
				$query = "select * from chart_of_accounts where code='$code'";
			}
			$rs = query($query);

			if(getNumRows($rs)==0){
			
				if($source=='add'){
					$chartofaccountsclass->insert(array('',$code,$desc,$type,$producttype,$userid,$now,'NULL','NULL'));
					$id = $chartofaccountsclass->getInsertId();
					$systemlog->logAddedInfo($chartofaccountsclass,array($id,$code,$desc,$type,$producttype,$userid,$now,'NULL','NULL'),'Chart of Accounts','New Chart of Accounts Added',$userid,$now);
					echo "success";
				}
				else if($source=='edit'){
						$id = escapeString($_POST['id']);
					
						$systemlog->logEditedInfo($chartofaccountsclass,$id,array('',$code,$desc,$type,$producttype,'NOCHANGE','NOCHANGE',$userid,$now),'Chart of Accounts','Edited Chart of Accounts Info',$userid,$now);/// log should be before update is made
						$chartofaccountsclass->update($id,array($code,$desc,$type,$producttype,'NOCHANGE','NOCHANGE',$userid,$now));
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

	        	$rs = query("delete from chart_of_accounts where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }



?>

