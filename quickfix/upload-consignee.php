<?php
	ini_set('max_execution_time', 30000);
	include("../config/connection.php");
	include("../config/functions.php");



				$tmpfile = "excel/consignee.csv";
				$handle = fopen($tmpfile, 'r');
				$numberofrows = count(file($tmpfile));

				$strvalues =array();
				$rowdata = fgetcsv($handle);
				$now = date('Y-m-d H:i:s');

					for($i=1;$i<$numberofrows;$i++){

						
						$rowdata = fgetcsv($handle);

						
						
						$accountname = trim($rowdata[1])==''?'NULL':escapeString(strtoupper($rowdata[1]));
						$companyname = trim($rowdata[2])==''?'NULL':escapeString(strtoupper($rowdata[2]));
						$address = trim($rowdata[3])==''?'NULL':escapeString(strtoupper($rowdata[3]));

						$contactperson = trim($rowdata[4])==''?'NULL':escapeString(strtoupper($rowdata[4]));
						$contactnumber = trim($rowdata[5])==''?'NULL':escapeString(strtoupper($rowdata[5]));


						$accountname = strtoupper(trim($accountname))=='NONE'?$contactperson:$accountname;
						$companyname = strtoupper(trim($companyname))=='NONE'?$contactperson:$companyname;
						



						if($accountname!='NULL'&&$companyname!='NULL'){
							$accountnumber = getTransactionNumber(7);

							$checkifexistrs = mysql_query("select * from consignee where account_name='$accountname'");

							if(mysql_num_rows($checkifexistrs)==1){

								while($obj=fetch($checkifexistrs)){
									$consigneeid = $obj->id;
								}

								$checkcontact = query("select * from consignee_contact where consignee_id='$consigneeid'");
								if(getNumRows($checkcontact)>0){
									$flag = 0;
								}
								else{	
									$flag = 1;
								}
								$checkifcontactexist = query("select * from consignee_contact where consignee_id='$consigneeid' and phone_number='$contactnumber'");

								if(getNumRows($checkifcontactexist)==0){
									$rs = mysql_query("insert into consignee_contact(
																				shipper_id,
																				contact_name,
																				phone_number,
																				default_flag
																			)
																		values(
																					$consigneeid,
																					'$contactperson',
																					'$contactnumber',
																					$flag
																			  )

																		");
								}

								
								echo mysql_error()."<br>";

							}
							if(mysql_num_rows($checkifexistrs)==0){
								mysql_query("insert into consignee(	
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
								$consigneeid = mysql_insert_id();

								if($contactnumber!='NONE'&&$contactnumber!='NULL'){

									$rs = mysql_query("insert into consignee_contact(
																				consignee_id,
																				contact_name,
																				phone_number,
																				default_flag
																			)
																		values(
																					$consigneeid,
																					'$contactperson',
																					'$contactnumber',
																					1

																				)
																		");
								}
								echo mysql_error()."<br>";

								echo "Inserted: $accountnumber - $accountname <br>";
							}


						}
					}

					//query("update shipper set non_pod_flag=0, vat_flag=1");
?>