<?php

require '../../resources/PHPMailer/PHPMailerAutoload.php';  
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;



//WAYBILL HISTORY

$app->post('/api/waybill',function(Request $request, Response $response, array $input){

	//$wbnumber = escapeString($request->getAttribute('wbnumber')); //or escapeString($input['wbnumber']);


    	if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
			$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
			$_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP)) {
        	$ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
        	$ip = $forward;
        }
        else {
        	$ip = $remote;
        }

	try {

		$db = new db();
		$db = $db->connect();
		$mainarray = array();
		$data = array();
		$flag = 1;
		$userid = 0;


 
		$wbnumber = escapeString($request->getParam('tracking_number'));
		$user = escapeString($request->getParam('user'));
		$password = escapeString($request->getParam('password'));
		$password = md5($password);



		$checkusersql = "SELECT * from webapi_user where user=:user and password=:password and ip_address=:ip_add";
		$stmt = $db->prepare($checkusersql);
		$stmt->execute(
							array(
									':user'=>$user,
									':password'=>$password,
									':ip_add'=>$ip
								  )
			           );
		$rowcount = $stmt->rowCount();

		if($rowcount==1){
				while($obj=$stmt->fetch(PDO::FETCH_OBJ)){	
					$userid = $obj->id;
				}

				// OR (txn_waybill.mawbl_bl = :waybill and txn_waybill.mawbl_bl is not null)
				$sql = "SELECT 	txn_waybill_status_history.status_code,
								txn_waybill_status_history.status_description,
				                txn_waybill_status_history.remarks,
				                txn_waybill_status_history.created_date,
				                txn_waybill_status_history.created_by,
				                concat(first_name,' ',last_name) as createdby,
				                origintbl.description as origin,
				                destinationtbl.description as destination,
				                txn_waybill.consignee_account_name,
				                txn_waybill.document_date
				        from txn_waybill_status_history
				        left join txn_waybill on txn_waybill.waybill_number=txn_waybill_status_history.waybill_number
				        left join user on user.id=txn_waybill_status_history.created_by
				        left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id 
					    left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id
				        where (txn_waybill_status_history.waybill_number=:waybill ) and 
				              txn_waybill_status_history.status_description in (select status from webapi_status_shown)
				        order by txn_waybill_status_history.created_date asc";

				

				//$stmt = $db->query($sql);	
				// or 
				//$hst = $stmt->fetchAll(PDO::FETCH_OBJ);


				$stmt = $db->prepare($sql);
				$stmt->execute(
									array(':waybill'=>$wbnumber)
					           );
				
				$rowcount = $stmt->rowCount();
				$destination = '';
				$origin = '';
				$transactiondate = '';
				$consigneename = '';
				if($rowcount>0){
					while($obj=$stmt->fetch(PDO::FETCH_OBJ)){

						if($flag==1){
								
								$destination = utf8_encode($obj->destination);
								$origin = utf8_encode($obj->origin);
								$transactiondate = utf8_encode(dateFormat($obj->document_date,'m/d/Y'));
								$consigneename = utf8_encode($obj->consignee_account_name);
									 	
						}
						$temp = array(
										"date"=>utf8_encode(dateFormat($obj->created_date,'m/d/Y h:i:s A')),
										"movement"=>utf8_encode($obj->status_description),
										"remarks"=>utf8_encode($obj->remarks)
									 ); 
						array_push($data, $temp);
						$flag++;
					}
				
					$mainarray = array(
										"Success" => true,
										"destination"=>$destination,
										"origin"=>$origin,
										"transaction_date"=>$transactiondate,
										"consignee_name"=>$consigneename,
										"details"=>$data
								 ); 
					
					insertApiLog($db,$userid);

					$db = null;
					return $response->withStatus(201)->withHeader('Content-Type', 'application/json')->write(json_encode($mainarray));
				}
				else{


					$sql = "select  origintbl.description as origin,
					                destinationtbl.description as destination,
					                txn_waybill.consignee_account_name,
					                txn_waybill.document_date 
					        from txn_waybill
					        left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id 
					        left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id 
					        where waybill_number=:waybill";
					$stmt = $db->prepare($sql);
					$stmt->execute(
										array(':waybill'=>$wbnumber)
						           );
					$rowcount = $stmt->rowCount();

					if($rowcount==1){
						while($obj=$stmt->fetch(PDO::FETCH_OBJ)){

							$destination = utf8_encode($obj->destination);
							$origin = utf8_encode($obj->origin);
							$transactiondate = utf8_encode(dateFormat($obj->document_date,'m/d/Y'));
							$consigneename = utf8_encode($obj->consignee_account_name);
						}
						$mainarray = array(
											"Success" => true,
											"destination"=>$destination,
											"origin"=>$origin,
											"transaction_date"=>$transactiondate,
											"consignee_name"=>$consigneename,
											"details"=>$data
								          ); 

						insertApiLog($db,$userid);

						$db = null;
						return $response->withStatus(201)->withHeader('Content-Type', 'application/json')->write(json_encode($mainarray));
					}
					else{
						insertApiLog($db,$userid);
						return $response->withStatus(400)->withHeader('Content-Type', 'application/json')->write(json_encode([
						    "Success" => false,
						    "Message" => 'Invalid Waybill Number : '.$wbnumber
						    ]));
					}
				}

		}
		else {

			insertApiLog($db,$userid);

			return $response->withStatus(400)->write(json_encode([
			    "Success" => false,
			    "Message" => 'Invalid IP : '.$ip
			    ]));
			    
		}

	}catch(PDOException $e){
		$err = array(
						"error"=>$e->getMessage()
					);

		insertApiLog($db,$userid);
		return $response->withStatus(500)->write(json_encode($err));
	}

});

$app->post('/api/send-quote-request',function(Request $request, Response $response, array $input){

	

	if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
		$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		$_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
	}
	$client  = @$_SERVER['HTTP_CLIENT_IP'];
	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$remote  = $_SERVER['REMOTE_ADDR'];

	if(filter_var($client, FILTER_VALIDATE_IP)) {
		$ip = $client;
	}
	elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
		$ip = $forward;
	}
	else {
		$ip = $remote;
	}

	try {

		$db = new db();
		$db = $db->connect();
		$mainarray = array();
		$data = array();
		$flag = 1;
		$userid = 0;
 
		$user = $request->getParam('user');
		$password = $request->getParam('password');
		$password = md5($password);

		$quote_for = $request->getParam("quote_for");
		$company_name = $request->getParam("company_name");
		$company_address = $request->getParam("company_address");
		$contact_person_first_name = ucwords(strtolower($request->getParam("contact_person_first_name")));
		$contact_person_last_name = ucwords(strtolower($request->getParam("contact_person_last_name")));
		$contact_mobile_number = $request->getParam("contact_mobile_number");
		$contact_phone_number = $request->getParam("contact_phone_number");
		$contact_email_address = $request->getParam("contact_email_address");
		$shipment_pickup_address = $request->getParam("shipment_pickup_address");
		$shipment_delivery_address = $request->getParam("shipment_delivery_address");
		$shipment_commodity = $request->getParam("shipment_commodity");
		$shipment_weight = $request->getParam("shipment_weight");
		$shipment_length = $request->getParam("shipment_length");
		$shipment_width = $request->getParam("shipment_width");
		$shipment_height = $request->getParam("shipment_height");
		$shipment_declared_value = $request->getParam("shipment_declared_value");
		$shipment_declared_value = number_format((strlen($shipment_declared_value)>0 ? $shipment_declared_value : 0),2);

		$checkusersql = "SELECT * from webapi_user where user=:user and password=:password and ip_address=:ip_add";
		$stmt = $db->prepare($checkusersql);
		$stmt->execute(
							array(
									':user'=>$user,
									':password'=>$password,
									':ip_add'=>$ip
								)
					);
		$rowcount = $stmt->rowCount();

		if($rowcount > 0){
			while($obj=$stmt->fetch(PDO::FETCH_OBJ)){
				$userid = $obj->id;
			}  
			
			$mail = new PHPMailer();
			try {  
				$mail->isSMTP();  
				$mail->Host = "sg2plcpnl0114.prod.sin2.secureserver.net";
				$mail->Port = 587;
				$mail->SMTPSecure = "tls";
				$mail->SMTPAuth = true; 
				$mail->Username = "no-reply@tpincorporated.com"; 
				$mail->Password = "#reply.Dont-4wms"; 
			
				$mail->setFrom($contact_email_address, trim("{$contact_person_first_name} {$contact_person_last_name}")); 
				//sales_inquiry@cbl-freight.com
				$mail->addAddress("sales_inquiry@cbl-freight.com", "CBL Account Executive");

				$mail->isHTML(true);
				$mail->Subject = "TESTING EMAIL : {$quote_for}"; 
				$mail->Body = "
					<div style='font-family: \"Trebuchet MS\", Arial, Helvetica, sans-serif; text-align: center; margin: 0 auto; width: 100%;'>
						
						<table style='display: block; width: 80%; border: 1px solid black; text-align: center;'>
							<tr style='border-bottom: 2px solid black;'>
								<th><h1>Company Information</h1></th>
							</tr>
							<tr>
								<td>Name</td>
							</tr>
							<tr>
								<td><strong>$company_name</strong></td>
							</tr>
							<tr>
								<td>Address</td>
							</tr>
							<tr>
								<td><strong>$company_address</strong></td>
							</tr>
						</table>

						<br/>
						<br/>

						<table style='display: block; width: 80%; border: 1px solid black; text-align: center;'>
							<tr style='border-bottom: 2px solid black;'>
								<th colspan='3'><h1>Contact Information</h1></th>
							</tr>
							<tr>
								<td colspan='3'>Contact Person</td>
							</tr>
							<tr>
								<td>First Name</td>
								<td>&nbsp;</td>
								<td>Last Name</td>
							</tr>
							<tr>
								<td><strong>$contact_person_first_name</strong></td>
								<td>&nbsp;</td>
								<td><strong>$contact_person_last_name</strong></td>
							</tr>
							<tr>
								<td colspan='3'>Contact Number</td>
							</tr>
							<tr>
								<td>Mobile</td>
								<td>&nbsp;</td>
								<td>Telephone</td>
							</tr>
							<tr> 
								<td><strong>$contact_mobile_number</strong></td>
								<td>&nbsp;</td>
								<td><strong>$contact_phone_number</strong></td>
							</tr>
							<tr>
								<td colspan='3'>Email Address</td>
							</tr>
							<tr>
								<td colspan='3'><strong>$contact_email_address</strong></td> 
							</tr>
						</table>
						
						<br/>
						<br/>

						<table style='display: block; width: 80%; border: 1px solid black; text-align: center;'>
							<tr style='border-bottom: 2px solid black;'>
								<th colspan='3'><h1>Shipment/Package Information</h1></th>
							</tr>
							<tr>
								<td colspan='3'>Pick up Address</td>
							</tr>
							<tr>
								<td colspan='3'><strong>$shipment_pickup_address</strong></td> 
							</tr>
							<tr>
								<td colspan='3'>Delivery Address</td>
							</tr>
							<tr>
								<td colspan='3'><strong>$shipment_delivery_address</strong></td> 
							</tr>
							<tr>
								<td colspan='3'>Commodity</td>
							</tr>
							<tr>
								<td colspan='3'><strong>$shipment_commodity</strong></td> 
							</tr>
							<tr>
								<td colspan='3'>Weight</td>
							</tr>
							<tr>
								<td colspan='3'><strong>$shipment_weight</strong></td> 
							</tr>
							<tr>
								<td colspan='3'>Dimension</td>
							</tr>
							<tr>
								<td>Length</td> 
								<td>Width</td> 
								<td>Height</td> 
							</tr>
							<tr>
								<td><strong>$shipment_length</strong></td> 
								<td><strong>$shipment_width</strong></td> 
								<td><strong>$shipment_height</strong></td> 
							</tr>
							<tr>
								<td colspan='3'>Declared value</td>
							</tr>
							<tr>
								<td colspan='3'><strong>$shipment_declared_value</strong></td> 
							</tr>
						</table>
					
					</div>
				";

				if($mail->send()){
				    return $response->withStatus(201)->withHeader('Content-Type', 'application/json')->write(json_encode([
						"Success" => true,
						"Message" => "An email has been sent for the quotation requested."
					]));
					 
				}
				$db = null;

			} 
			catch (Exception $e) {
			    return $response->withStatus(201)->withHeader('Content-Type', 'application/json')->write(json_encode([
					"Success" => false,
					"Message" => $mail->ErrorInfo
				]));
			}
			
			$db = null;
			$mail = null;


		}
		else {

			insertApiLog($db,$userid);
			return $response->withStatus(400)->write(json_encode([
				"Success" => false,
				"Message" => "Invalid IP: ".$ip
			]));
		}

	}
	catch(PDOException $e){
		$err = array(
						"error"=>$e->getMessage()
					);

		insertApiLog($db,$userid);
		return $response->withStatus(500)->write($err);
	}

});

 
//WAYBILL STATUS UPDATE
/*$app->post('/api/wbupdate',function(Request $request, Response $response){
    $waybill = escapeString($request->getParam('waybill_number'));
	$statuscode = escapeString($request->getParam('status_code'));
	$timestamp = escapeString($request->getParam('timestamp'));
	$remarks = escapeString($request->getParam('remarks'));
	
	$data = array();
	$notice = '';
	$message = '';

	try{

		$db = new db();
	    $db = $db->connect();


	    //check if valid waybill
		$stmt = $db->query("select * from txn_waybill where waybill_number='$waybill' and status!='VOID' and status!='LOGGED' and status!='POSTED'");
		if($stmt->rowCount()==1){
			//check if valid status code
			$stmt1 = $db->query("select * from status where code='$statuscode'");
			if($stmt1->rowCount()==1){

				$notice = 'SUCCESS';
			    $message = '';

			    //insert code here.....

			}
			else{
				$notice = 'FAILED';
			    $message = 'Invalid status code';
			}
		}
		else if($stmt->rowCount()>1){
			$notice = 'FAILED';
			$message = 'Multiple waybill transaction';
		}
		else{
			$notice = 'FAILED';
			if($waybill==''){
				$message = 'No waybill provided';
			}
			else{
				$message = 'Invalid waybill number';
			}
			
			
		}

		
		$data =	       array(
			       			"notice"=>$notice,
			       			"message"=>$message
			             );

		$response->withStatus(200)->withHeader('Content-Type', 'application/json')->write(json_encode($data));

	}catch (PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	}
	
});



//ADD AGENT
$app->post('/api/agent/add',function(Request $request, Response $response){
    $code = $request->getParam('code');
	$description = $request->getParam('description');

	$sql = "insert into location(code,description) values(:code,:description)";
	
	
	try{

		$db = new db();
	    $db = $db->connect();

	    $stmt = $db->prepare($sql);
	    $stmt->bindParam(':code', $code);
	    $stmt->bindParam(':description', $description);
	    $stmt->execute();

	    echo '{"notice": {"text": "Location Added"}';
	}catch (PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	}
	//$response->withStatus(200)->withHeader('Content-Type', 'application/json')->write(json_encode($data));
});

//UPDATE LOCATION
$app->post('/api/location/update/{id}',function(Request $request, Response $response){
	$locID = escapeString($request->getAttribute('id'));

    $code = $request->getParam('code');
	$description = $request->getParam('description');


	$sql = "update location
	        set code = :code,
	            description = :description
	        where id = :locid";
	
	
	try{

		$db = new db();
	    $db = $db->connect();

	    $stmt = $db->prepare($sql);
	    $stmt->bindParam(':code', $code);
	    $stmt->bindParam(':description', $description);
	    $stmt->bindParam(':locid', $locID);
	    $stmt->execute();

	    echo '{"notice": {"text": "Location Updated"}';
	}catch (PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	}
	//$response->withStatus(200)->withHeader('Content-Type', 'application/json')->write(json_encode($data));
});
*/



?>