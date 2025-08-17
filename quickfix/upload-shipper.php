<?php
	ini_set('max_execution_time', 30000);
	include("../config/connection.php");
	include("../config/functions.php");



				$tmpfile = "excel/shipper-1.csv";
				$handle = fopen($tmpfile, 'r');
				$numberofrows = count(file($tmpfile));

				$strvalues =array();
				$rowdata = fgetcsv($handle);
				$now = date('Y-m-d H:i:s');

					for($i=1;$i<$numberofrows;$i++){

						
						$rowdata = fgetcsv($handle);

						
						$accountnumber = getTransactionNumber(6);
						$accountname = trim($rowdata[1])==''?'NULL':escapeString(strtoupper($rowdata[1]));
						$companyname = trim($rowdata[2])==''?'NULL':escapeString(strtoupper($rowdata[2]));
						$address = trim($rowdata[3])==''?'NULL':escapeString(strtoupper($rowdata[3]));
						$contactperson = trim($rowdata[4])==''?'NULL':escapeString(strtoupper($rowdata[4]));
						$contactnumber = trim($rowdata[5])==''?'NULL':escapeString(strtoupper($rowdata[5]));



						if($accountnumber!='NULL'&&$accountname!='NULL'&&$companyname!='NULL'){

							$checkifexistrs = mysql_query("select * from shipper where account_name='$accountname'");

							if(mysql_num_rows($checkifexistrs)==1){

								while($obj=fetch($checkifexistrs)){
									$shipperid = $obj->id;
								}

								mysql_query("delete from shipper_contact where shipper_id='$shipperid'");
								$rs = mysql_query("insert into shipper_contact(
																			shipper_id,
																			contact_name,
																			phone_number,
																			default_flag
																		)
																	values(
																				$shipperid,
																				'$contactperson',
																				'$contactnumber',
																				1
																		  )

																	");

								$checkpickupaddr = query("select * from shipper_pickup_address where shipper_id='$shipperid'");
								if(getNumRows($checkpickupaddr)>0){

								}
								else{

									$checkifexistaddr = query("select * from shipper_pickup_address where pickup_street_address='$address' and shipper_id='$shipperid'");
									if(getNumRows($checkifexistaddr)==0){
										query("insert into shipper_pickup_address(shipper_id,default_flag,pickup_street_address) values('$shipperid',1,'$address')");
									}
									
								}
								echo mysql_error()."<br>";

							}
							if(mysql_num_rows($checkifexistrs)==0){
								mysql_query("insert into shipper(	
																	account_number,
																	account_name,
																	company_name,
																	company_street_address,
																	created_by
																)   
														  values(
														  			'$accountnumber',
														  			'$accountname',
														  			'$companyname',
														  			'$address',
														  			1
														  		)");
								$shipperid = mysql_insert_id();

								$rs = mysql_query("insert into shipper_contact(
																			shipper_id,
																			contact_name,
																			phone_number,
																			default_flag
																		)
																	values(
																				$shipperid,
																				'$contactperson',
																				'$contactnumber',
																				1

																			)
																	");
								echo mysql_error()."<br>";

								echo "Inserted: $accountnumber - $accountname <br>";
							}


						}
					}

					query("update shipper set non_pod_flag=0, vat_flag=1");
?>