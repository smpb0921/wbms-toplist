<?php
	ini_set('max_execution_time', 30000);
	include("../config/connection.php");
	include("../config/functions.php");



				$tmpfile = "excel/port-codes2.csv";
				$handle = fopen($tmpfile, 'r');
				$numberofrows = count(file($tmpfile));

				$strvalues =array();
				$rowdata = fgetcsv($handle);
				$now = date('Y-m-d H:i:s');

					for($i=1;$i<$numberofrows;$i++){

						
						$rowdata = fgetcsv($handle);

						
						$code = trim($rowdata[0])==''?'NULL':trim(strtoupper($rowdata[0]));
						$desc = trim($rowdata[1])==''?'NULL':trim(strtoupper($rowdata[1]));



						if($code!='NULL'&&$desc!='NULL'){

							$checkifexistrs = mysql_query("select * from origin_destination_port where code='$code'");

							if(mysql_num_rows($checkifexistrs)>0){
							}
							else{
								mysql_query("insert into origin_destination_port(code,description,created_by,created_date,country_id) values('$code','$desc',1,'$now',174)");
								echo "Inserted: $code - $desc <br>";
							}


						}
					}
?>