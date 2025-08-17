<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/account-executive.class.php");
    include("../classes/system-log.class.php");////////

	if(isset($_POST['AccountExecutiveSaveEdit'])){
		if($_POST['AccountExecutiveSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = escapeString($_POST['source']);
			$code = escapeString(strtoupper(trim($_POST['code'])));
			$name = escapeString(flcapital($_POST['name']));
			$email = escapeString($_POST['email']);
			$mobile = escapeString($_POST['mobile']);
			$username = escapeString($_POST['username']);
			$password = escapeString($_POST['password']);
			$userid = USERID;
			$now = date('Y-m-d H:i:s');
			$aeclass = new account_executive();
			$systemlog = new system_log();


			if($source=='edit'){
				$id = escapeString($_POST['id']);
				$query = "select * from account_executive where code='$code' and id!='$id'";
			}
			else{
				$query = "select * from account_executive where code='$code'";
			}
			$rs = query($query);

			if(getNumRows($rs)==0){

				if($source=='edit'){
					$id = escapeString($_POST['id']);
					$query = "select * from account_executive where username='$username' and id!='$id'";
				}
				else{
					$query = "select * from account_executive where username='$username'";
				}
				$rs = query($query);

				if(getNumRows($rs)==0){
			
						if($source=='add'){
							$aeclass->insert(array('',$code,$name,$email,$mobile,$username,$password,$userid,$now,'NULL','NULL'));
							$id = $aeclass->getInsertId();
							$systemlog->logAddedInfo($aeclass,array($id,$code,$name,$email,$mobile,$username,$password,$userid,$now,'NULL','NULL'),'ACCOUNT EXECUTIVE','New Account Executive Added',$userid,$now);
							echo "success";
						}
						else if($source=='edit'){
								$id = escapeString($_POST['id']);
							
								$systemlog->logEditedInfo($aeclass,$id,array('',$code,$name,$email,$mobile,$username,$password,'NOCHANGE','NOCHANGE',$userid,$now),'ACCOUNT EXECUTIVE','Edited Account Executive Info',$userid,$now);/// log should be before update is made
								$aeclass->update($id,array($code,$name,$email,$mobile,$username,$password,'NOCHANGE','NOCHANGE',$userid,$now));
								echo "success";

						}
				}
				else{
					echo "usernameexists";
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

	        	$rs = query("delete from account_executive where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }



?>

