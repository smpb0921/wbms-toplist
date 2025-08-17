<?php
	ini_set('max_execution_time', 30000);
	include("../config/connection.php");
	include("../config/functions.php");



				$tmpfile = "excel/district-city-zip-09252018.csv";
				$handle = fopen($tmpfile, 'r');
				$numberofrows = count(file($tmpfile));

				$strvalues =array();
				$rowdata = fgetcsv($handle);
				$now = date('Y-m-d H:i:s');

				echo "uploading... <br> Total Number of Rows: $numberofrows <br>";

					for($i=0;$i<$numberofrows;$i++){

						
						$rowdata = fgetcsv($handle);

						
						$district = trim($rowdata[0])==''?'':trim(utf8_decode($rowdata[0]));
						$district = str_replace("'", "\'", $district);
						$city = trim($rowdata[1])==''?'':trim($rowdata[1]);
                        $zip = trim($rowdata[2])==''?'':trim($rowdata[2]);
                        $port = trim($rowdata[3])==''?'':trim(strtoupper($rowdata[3]));
                        $odzflag = trim(strtoupper($rowdata[4]))=='ODZ'?1:0;

                        $portID = '';
                        $portrs = query("select * from origin_destination_port where upper(code)='$port'");
                        while($obj=fetch($portrs)){
                        	$portID = $obj->id;
                        }

						if($portID!=''){



							$checkifexistrs = mysql_query("select * from district_city_zipcode
							                               where district_barangay='$district' and 
							                                     city='$city' and
							                                     zip_code='$zip' and
							                                     origin_destination_port_id='$portID'");

							if(mysql_num_rows($checkifexistrs)>0){
								while($obj=fetch($checkifexistrs)){
									$rowid = $obj->id;

									query("update district_city_zipcode set oda_flag='$odzflag' where id='$rowid'");
									echo "Updated: $district - $city - $zip - $port=$portID - $odzflag<br>";
								}
							}
							else{
								mysql_query("insert into district_city_zipcode(
									                                              district_barangay,
									                                              city,
									                                              zip_code,
									                                              origin_destination_port_id,
									                                              created_by,
									                                              created_date,
									                                              oda_flag
									                                              ) 
									                                       values('$district','$city','$zip','$portID',1,'$now','$odzflag')");
								echo "Inserted: $district - $city - $zip - $port=$portID <br>";
							}


						}
						else{
							echo "skipped $port <br>";
						}
					}
?>