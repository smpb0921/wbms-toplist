<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/payee.class.php");
    include("../classes/system-log.class.php");////////

	if(isset($_POST['payeeSaveEdit'])){
		if($_POST['payeeSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = escapeString($_POST['source']);
			$name = isset($_POST['payeename'])?escapeString(trim(ucwords(strtolower($_POST['payeename'])))):'NULL';
			$address = isset($_POST['payeeaddress'])?escapeString(trim($_POST['payeeaddress'])):'NULL';
            $tin = isset($_POST['payeetin'])?escapeString(trim($_POST['payeetin'])):'NULL';
			$userid = USERID;
			$now = date('Y-m-d H:i:s');
			$payeeclass = new payee();
			$systemlog = new system_log();


			if($source=='edit'){
				$id = escapeString($_POST['id']);
				$query = "select * from payee where tin='$tin' and id!='$id'";
			}
			else{
				$query = "select * from payee where tin='$tin'";
			}
			$rs = query($query);

			if(getNumRows($rs)==0){
			
				if($source=='add'){
					$payeeclass->insert(array('',$name,$address,$tin,$userid,$now,'NULL','NULL'));
					$id = $payeeclass->getInsertId();
					$systemlog->logAddedInfo($payeeclass,array($id,$name,$address,$tin,$userid,$now,'NULL','NULL'),'PAYEE','New Payee Added',$userid,$now);
					echo "success";
				}
				else if($source=='edit'){
						$id = escapeString($_POST['id']);
					
						$systemlog->logEditedInfo($payeeclass,$id,array('',$name,$address,$tin,'NOCHANGE','NOCHANGE',$userid,$now),'PAYEE','Edited Payee Info',$userid,$now);/// log should be before update is made
						$payeeclass->update($id,array($name,$address,$tin,'NOCHANGE','NOCHANGE',$userid,$now));
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

	        	$rs = query("delete from payee where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }

    if(isset($_POST['payeeGetInfo'])){
		if($_POST['payeeGetInfo']=='kjoI$H2oiaah3h0$09jDppo92po@k@'){
			$id = escapeString($_POST['id']);

			$rs = query("       select payee.id,
                                       payee.payee_name,
							           payee.address,
							           payee.tin
							    from payee
								where payee.id='$id'
				 	    ");

			if(getNumRows($rs)==1){

				while($obj=fetch($rs)){
					$dataarray = array(
										   "id"=>utfEncode($obj->id),
										   "payeename"=>utfEncode($obj->payee_name),
										   "payeeaddress"=>utfEncode($obj->address),
										   "payeetin"=>utfEncode($obj->tin),
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




?>

