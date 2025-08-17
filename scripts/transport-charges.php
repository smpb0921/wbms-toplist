<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/transport-charges.class.php");
    include("../classes/system-log.class.php");////////

	if(isset($_POST['TransportChargesSaveEdit'])){
		if($_POST['TransportChargesSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = escapeString($_POST['source']);
			$code = escapeString(strtoupper(trim($_POST['code'])));
			$desc = escapeString($_POST['desc']);
			$userid = USERID;
			$now = date('Y-m-d H:i:s');
			$tcclass = new transport_charges();
			$systemlog = new system_log();


			if($source=='edit'){
				$id = escapeString($_POST['id']);
				$query = "select * from transport_charges where code='$code' and id!='$id'";
			}
			else{
				$query = "select * from transport_charges where code='$code'";
			}
			$rs = query($query);

			if(getNumRows($rs)==0){
			
				if($source=='add'){
					$tcclass->insert(array('',$code,$desc,$userid,$now,'NULL','NULL'));
					$id = $tcclass->getInsertId();
					$systemlog->logAddedInfo($tcclass,array($id,$code,$desc,$userid,$now,'NULL','NULL'),'TRANSPORT CHARGES','New Transport Charges Added',$userid,$now);
					echo "success";
				}
				else if($source=='edit'){
						$id = escapeString($_POST['id']);
					
						$systemlog->logEditedInfo($tcclass,$id,array('',$code,$desc,'NOCHANGE','NOCHANGE',$userid,$now),'TRANSPORT CHARGES','Edited Transport Charges Info',$userid,$now);/// log should be before update is made
						$tcclass->update($id,array($code,$desc,'NOCHANGE','NOCHANGE',$userid,$now));
						echo "success";

				}
			}
			else{
				echo "codeexists";
			}
		}
			
	}


	if(isset($_POST['deleteSelectedRows'])){
        if($_POST['deleteSelectedRows']=='skj$oihdtpoa$I#@4noi4AIFNlskoRboIh4!j3sio$*yhs'){
        	@$data = $_POST['data'];
	        $itemsiterate = count($data);

	        for($i=0;$i<$itemsiterate;$i++){
	        	$id = mysql_real_escape_string($data[$i]);

	        	$rs = query("delete from transport_charges where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }



?>

