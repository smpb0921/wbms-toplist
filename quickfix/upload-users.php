<?php
	ini_set('max_execution_time', 30000);
	include("../config/connection.php");
	include("../config/functions.php");



				$tmpfile = "excel/users.csv";
				$handle = fopen($tmpfile, 'r');
				$numberofrows = count(file($tmpfile));

				$strvalues =array();
				$rowdata = fgetcsv($handle);
				$now = date('Y-m-d H:i:s');

					for($i=1;$i<$numberofrows;$i++){

						
						$rowdata = fgetcsv($handle);

						
						$firstname = trim($rowdata[0])==''?'':trim(strtoupper($rowdata[0]));
						$lastname = trim($rowdata[2])==''?'':trim(strtoupper($rowdata[2]));
						$username = trim($rowdata[3])==''?'':trim(strtoupper($rowdata[3]));
						$email = trim($rowdata[4])==''||trim($rowdata[4])=='Waiting for I.T approval'?'':trim(strtoupper($rowdata[4]));
						$location = trim($rowdata[5])==''?'':trim(strtoupper($rowdata[5]));
						$usergroup = trim($rowdata[7])==''?'':trim(strtoupper($rowdata[7]));


						if($firstname!=''&&$lastname!=''&&$username!=''&&$location!=''&&$usergroup!=''){

							$checkifexistrs = mysql_query("select * from user where username='$username'");

							if(mysql_num_rows($checkifexistrs)>0){
							}
							else{
								$rs = mysql_query("insert into user(first_name,last_name,username,email_address,location_id,user_group_id,created_by,created_date) values('$firstname','$lastname','$username','$email',$location,$usergroup,1,'$now')");
								if($rs){
									echo "Inserted: $username - $firstname $lastname <br>";
								}
								else{
									echo mysql_error()."<br>";
									echo "insert into user(first_name,last_name,username,email_address,location_id,user_group_id,created_by,created_date) values('$firstname','$lastname','$username','$email',$location,$usergroup,1,'$now') <br>";
								}
							}


						}
					}
?>