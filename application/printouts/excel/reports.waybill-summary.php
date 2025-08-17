<?php

    include("../../../config/connection.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

    require_once '../../../resources/spout/vendor/box/spout/src/Spout/Autoloader/autoload.php';
    use Box\Spout\Writer\WriterFactory;
    use Box\Spout\Common\Type;

    use Box\Spout\Writer\Style\Border;
    use Box\Spout\Writer\Style\BorderPart;
    use Box\Spout\Writer\Style\BorderBuilder;
    use Box\Spout\Writer\Style\Color;
    use Box\Spout\Writer\Style\CellAlignment;
    use Box\Spout\Writer\Style\Style;
    use Box\Spout\Writer\Style\StyleBuilder;

    $writer = WriterFactory::create(Type::XLSX);
    //$writer->openToFile("waybill-tracking-history-.xlsx");
    $writer->openToBrowser("bill-of-lading-summary-".date('Ymd-hisA').".xlsx");

    $border1 = (new BorderBuilder())
                  ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                  ->setBorderTop(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                  ->build();

    $borderfull = (new BorderBuilder())
                  ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                  ->setBorderTop(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                  ->setBorderLeft(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                  ->setBorderRight(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                  ->build();


    $reporttitlestyle = (new StyleBuilder())
           ->setFontBold()
           ->setFontSize(14)
           ->setCellAlignment(CellAlignment::CENTER)
          // ->setBorder($border1)
           ->setBackgroundColor(Color::WHITE)
           ->build();

    $rowheaderstyle = (new StyleBuilder())
           ->setFontBold()
           ->setFontSize(9)
          // ->setBorder($border1)
           ->setBackgroundColor(Color::WHITE)
           ->build();

    $headerinfostyle = (new StyleBuilder())
           ->setFontBold()
           ->setFontSize(9)
           ->setBackgroundColor(Color::WHITE)
           ->build();

    $columnheaderstyle = (new StyleBuilder())
           ->setFontBold()
           ->setFontSize(9)
           ->setBorder($borderfull)
           ->setCellAlignment(CellAlignment::CENTER)
           ->setBackgroundColor(Color::WHITE)
           ->build();

    $rowstyle = (new StyleBuilder())
           ->setFontSize(9)
          // ->setBorder($border1)
           ->setBackgroundColor(Color::WHITE)
           ->build();
    
    $rowdatastyle = (new StyleBuilder())
           ->setFontSize(9)
           ->setBorder($borderfull)
           ->setBackgroundColor(Color::WHITE)
           ->build();

    $rowxsstyle = (new StyleBuilder())
           ->setFontSize(8)
           ->setFontBold()
          // ->setBorder($border1)
           ->setBackgroundColor(Color::WHITE)
           ->build();


    $rowtotalstyle = (new StyleBuilder())
           ->setFontBold()
           ->setFontSize(9)
          // ->setBorder($border1)
           ->setBackgroundColor(Color::LIGHT_GRAY)
           ->build();

    $rowgrandtotalstyle = (new StyleBuilder())
           ->setFontBold()
           ->setFontSize(9)
          // ->setBorder($border1)
           ->setBackgroundColor(Color::GRAY)
           ->build();

    



    //QUERY 
    

    $filter = array();
    $status = isset($_GET['status'])?escapeString(strtoupper($_GET['status'])):'';
    $billingstatus = isset($_GET['billingstatus'])?escapeString(strtoupper($_GET['billingstatus'])):'';
    $paymentstatus = isset($_GET['paymentstatus'])?escapeString(strtoupper($_GET['paymentstatus'])):'';
    $mawbl = isset($_GET['mawbl'])?escapeString(strtoupper($_GET['mawbl'])):'';
    $origin = isset($_GET['origin'])?escapeString(strtoupper($_GET['origin'])):'';
    $destination = isset($_GET['destination'])?escapeString($_GET['destination']):'';
    $destinationroute = isset($_GET['destinationroute'])?escapeString(strtoupper($_GET['destinationroute'])):'';
    $shipper = isset($_GET['shipper'])?escapeString(strtoupper($_GET['shipper'])):'';
    $consignee = isset($_GET['consignee'])?escapeString(strtoupper($_GET['consignee'])):'';
    $service = isset($_GET['service'])?escapeString(strtoupper($_GET['service'])):'';
    $modeoftransport = isset($_GET['modeoftransport'])?escapeString(strtoupper($_GET['modeoftransport'])):'';
    $paymode = isset($_GET['paymode'])?escapeString($_GET['paymode']):'';
    $docdatefrom = isset($_GET['docdatefrom'])?escapeString($_GET['docdatefrom']):'';
    $docdateto = isset($_GET['docdateto'])?escapeString($_GET['docdateto']):'';
    $deldatefrom = isset($_GET['deldatefrom'])?escapeString($_GET['deldatefrom']):'';
    $deldateto = isset($_GET['deldateto'])?escapeString($_GET['deldateto']):'';
    $pudatefrom = isset($_GET['pudatefrom'])?escapeString($_GET['pudatefrom']):'';
    $pudateto = isset($_GET['pudateto'])?escapeString($_GET['pudateto']):'';
    $format = isset($_GET['format'])?escapeString(strtoupper($_GET['format'])):'';
    $manifestflag = isset($_GET['manifestflag'])?escapeString(strtoupper($_GET['manifestflag'])):'';
    $bolstartseries = isset($_GET['bolstartseries'])?escapeString(strtoupper($_GET['bolstartseries'])):'';
    $bolendseries = isset($_GET['bolendseries'])?escapeString(strtoupper($_GET['bolendseries'])):'';
	$bsstartseries = isset($_GET['bsstartseries'])?escapeString(strtoupper($_GET['bsstartseries'])):'';
    $bsendseries = isset($_GET['bsendseries'])?escapeString(strtoupper($_GET['bsendseries'])):'';
    $bookingnumber = isset($_GET['bookingnumber'])?escapeString(strtoupper($_GET['bookingnumber'])):'';
    $bolstartseries = is_numeric($bolstartseries)==true?$bolstartseries:'';
    $bolendseries = is_numeric($bolendseries)==true?$bolendseries:'';
	$bsstartseries = is_numeric($bsstartseries)==true?$bsstartseries:'';
    $bsendseries = is_numeric($bsendseries)==true?$bsendseries:'';


    if($docdatefrom!=''){
        $docdatefrom = date('Y-m-d', strtotime($docdatefrom));
    }
    if($docdateto!=''){
        $docdateto = date('Y-m-d', strtotime($docdateto));
    }

    if($deldatefrom!=''){
        $deldatefrom = date('Y-m-d', strtotime($deldatefrom));
    }
    if($deldateto!=''){
        $deldateto = date('Y-m-d', strtotime($deldateto));
    }

    if($pudateto!=''){
        $pudateto = date('Y-m-d', strtotime($pudateto));
    }
    if($pudatefrom!=''){
        $pudatefrom = date('Y-m-d', strtotime($pudatefrom));
    }

   
   


	$arr = array();
	if($status!=''&&$status!='NULL'&&$status!='UNDEFINED'){
		array_push($arr, "txn_waybill.status='".$status."'");
	}
	if(trim($billingstatus)!=''&&strtoupper($billingstatus)!='NULL'){
	  array_push($arr, "txn_waybill.billed_flag='".$billingstatus."'");
	}
	if(trim($paymentstatus)!=''&&strtoupper($paymentstatus)!='NULL'){
	  array_push($arr, "txn_waybill.paid_flag='".$paymentstatus."'");
	}
	if(trim($origin)!=''&&strtoupper($origin)!='NULL'){
	  array_push($arr, "txn_waybill.origin_id='".$origin."'");
	}
	if(trim($destination)!=''&&strtoupper($destination)!='NULL'){
	  array_push($arr, "txn_waybill.destination_id='".$destination."'");
	}
	if(trim($destinationroute)!=''&&strtoupper($destinationroute)!='NULL'){
	  array_push($arr, "txn_waybill.destination_route_id='".$destinationroute."'");
	}
	if(trim($shipper)!=''&&strtoupper($shipper)!='NULL'){
	  array_push($arr, "txn_waybill.shipper_id='".$shipper."'");
	}
	if(trim($consignee)!=''&&strtoupper($consignee)!='NULL'){
	  array_push($arr, "txn_waybill.consignee_id='".$consignee."'");
	}
	if($service!=''&&strtoupper($service)!='NULL'){
		array_push($arr, "txn_waybill.package_service='".$service."'");
	}
	if($modeoftransport!=''&&strtoupper($modeoftransport)!='NULL'){
		array_push($arr, "txn_waybill.package_mode_of_transport='".$modeoftransport."'");
	}
	if($paymode!=''&&strtoupper($paymode)!='NULL'){
		array_push($arr, "txn_waybill.package_pay_mode='".$paymode."'");
	}
	if(trim($mawbl)!=''){
		 array_push($arr, "txn_waybill.mawbl_bl='".$mawbl."'");
	}
	if(trim($bookingnumber)!=''&&strtoupper($bookingnumber)!='NULL'){
		 array_push($arr, "txn_waybill.booking_number='".$bookingnumber."'");
	}

   


	if($docdatefrom!=''&&$docdateto!=''){
		array_push($arr,"date(txn_waybill.document_date) >= '$docdatefrom' and date(txn_waybill.document_date) <= '$docdateto'");
	}
	else if($docdatefrom==''&&$docdateto!=''){
		array_push($arr,"date(txn_waybill.document_date) <= '$docdateto'");
	}
	else if($docdatefrom!=''&&$docdateto==''){
		 array_push($arr,"date(txn_waybill.document_date) >= '$docdatefrom'");
	}

	if($pudateto!=''&&$pudatefrom!=''){
		array_push($arr,"date(txn_waybill.pickup_date) >= '$pudatefrom' and date(txn_waybill.pickup_date) <= '$pudateto'");
	}
	else if($pudatefrom==''&&$pudateto!=''){
		array_push($arr,"date(txn_waybill.pickup_date) <= '$pudateto'");
	}
	else if($pudatefrom!=''&&$pudateto==''){
		 array_push($arr,"date(txn_waybill.pickup_date) >= '$pudatefrom'");
	}

	if($deldatefrom!=''&&$deldateto!=''){
		array_push($arr,"date(txn_waybill.created_date) >= '$deldatefrom' and date(txn_waybill.created_date) <= '$deldateto'");
	}
	else if($deldatefrom==''&&$deldateto!=''){
		array_push($arr,"date(txn_waybill.created_date) <= '$deldateto'");
	}
	else if($deldatefrom!=''&&$deldateto==''){
		 array_push($arr,"date(txn_waybill.created_date) >= '$deldatefrom'");
	}

	if($bolstartseries!=''&&$bolendseries!=''){
		array_push($arr,"trim(LEADING 'L' from txn_waybill.mawbl_bl) >= $bolstartseries and trim(LEADING 'L' from txn_waybill.mawbl_bl) <= $bolendseries");
	}
	else if($bolstartseries==''&&$bolendseries!=''){
		array_push($arr,"trim(LEADING 'L' from txn_waybill.mawbl_bl) <= $bolendseries");
	}
	else if($bolstartseries!=''&&$bolendseries==''){
		 array_push($arr,"trim(LEADING 'L' from txn_waybill.mawbl_bl) >= $bolstartseries");
	}


	if($bsstartseries!=''&&$bsendseries!=''){
		array_push($arr,"trim(LEADING '0' from txn_billing.invoice) >= $bsstartseries and trim(LEADING '0' from txn_billing.invoice) <= $bsendseries");
	}
	else if($bsstartseries==''&&$bsendseries!=''){
		array_push($arr,"trim(LEADING '0' from txn_billing.invoice) <= $bsendseries");
	}
	else if($bsstartseries!=''&&$bsendseries==''){
		 array_push($arr,"trim(LEADING '0' from txn_billing.invoice) >= $bsstartseries");
	}

    $searchSql = '';
    if(count($arr)>0){
    	$searchSql = ' where '.implode(" and ", $arr);
    }

    

    $sql = "
					select txn_waybill.id, 
							txn_waybill.waybill_number,
							txn_waybill.status,
							txn_waybill.booking_number,
							txn_waybill.origin_id,
							txn_waybill.destination_id,
							txn_waybill.destination_route_id,
							txn_waybill.pickup_date,
							txn_waybill.on_hold_flag,
							txn_waybill.on_hold_remarks,
							txn_waybill.remarks,
							txn_waybill.created_date,
							date(txn_waybill.pickup_date) as bolcreateddate,
							txn_waybill.created_by,
							txn_waybill.updated_date,
							txn_waybill.updated_by,
							txn_waybill.document_date,
							txn_waybill.delivery_date,
							txn_waybill.manifest_number,
							txn_waybill.invoice_number,
							txn_waybill.shipper_id,
							txn_waybill.paid_flag,
							case 
								when txn_waybill.paid_flag=1 then 'PAID'
								else 'UNPAID'
							end as paidflag,
							txn_waybill.payment_reference,
							txn_waybill.shipper_account_number,
							txn_waybill.shipper_account_name,
							txn_waybill.shipper_tel_number,
							txn_waybill.shipper_company_name,
							txn_waybill.shipper_street_address,
							txn_waybill.shipper_district,
							txn_waybill.shipper_city,
							txn_waybill.shipper_contact_person,
							txn_waybill.shipper_state_province,
							txn_waybill.shipper_zip_code,
							txn_waybill.shipper_country,
							txn_waybill.consignee_id,
							txn_waybill.consignee_account_number,
							txn_waybill.consignee_account_name,
							txn_waybill.consignee_tel_number,
							txn_waybill.consignee_company_name,
							txn_waybill.consignee_street_address,
							txn_waybill.consignee_district,
							txn_waybill.consignee_city,
							txn_waybill.consignee_state_province,
							txn_waybill.consignee_zip_code,
							txn_waybill.consignee_country,
							txn_waybill.pickup_street_address,
							txn_waybill.pickup_district,
							txn_waybill.pickup_city,
							txn_waybill.pickup_state_province,
							txn_waybill.pickup_zip_code,
							txn_waybill.pickup_country,
							txn_waybill.shipment_description,
							txn_waybill.package_number_of_packages,
							txn_waybill.package_declared_value,
							txn_waybill.package_actual_weight,
							txn_waybill.package_cbm,
							txn_waybill.package_vw,
							txn_waybill.package_chargeable_weight,
							txn_waybill.package_freight,
							txn_waybill.package_valuation,
							txn_waybill.package_service,
							txn_waybill.package_mode_of_transport,
							txn_waybill.package_document,
							txn_waybill.package_delivery_instruction,
							txn_waybill.package_transport_charges,
							txn_waybill.package_pay_mode,
							txn_waybill.vat,
							txn_waybill.mawbl_bl,
							txn_waybill.zero_rated_flag,
							txn_waybill.total_amount,
							txn_waybill.carrier_id,
							txn_waybill.shipper_rep_name,
							txn_waybill.package_freight_computation,
							txn_waybill.package_insurance_rate,
							txn_waybill.package_fuel_rate,
							txn_waybill.package_bunker_rate,
							txn_waybill.package_minimum_rate,
							txn_waybill.subtotal,
							txn_waybill.amount_for_collection,
							txn_waybill.last_status_update_remarks,
							origintbl.description as origin,
							destinationtbl.description as destination,
							services.description as servicedesc,
							mode_of_transport.description as modeoftransport,
							delivery_instruction.description as deliveryinstruction,
							destination_route.description as destinationroute,
							accompanying_documents.description as document,
							transport_charges.description as transportcharges,
							carrier.description as carrier,
							sum(txn_waybill_other_charges.amount) as othercharges,
							group_concat(distinct handling_instruction.description separator ', ') as handlinginstruction,
							group_concat(concat(packdim.quantity,' - ',packdim.length,'x',packdim.width,'x',packdim.height) separator ', ') as packdimensions,
							group_concat(concat('(',packdim.quantity,')',' - ',packdim.length) separator ', ') as packdimensionslen,
							group_concat(packdim.width separator ', ') as packdimensionswid,
							group_concat(packdim.height separator ', ') as packdimensionshgt,
							txn_waybill.received_date,
							date(txn_waybill.received_date) as boldelivereddate,
							txn_waybill.received_by,
							txn_waybill.reference,
							case 
									when txn_waybill.oda_flag=1 then 'YES'
									else 'NO'
							end as odaflag,
							concat(user.first_name,' ',user.last_name) as bolcreatedby,
							location.description as location,
							user_group.description as usergroup,
							case 
									when txn_waybill.billed_flag=1 then 'BILLED'
									else 'NOT BILLED'
							end as billingflag,
							txn_waybill.billing_reference,
							destinationtbl.lead_time,
							consignee.id_number,
							zone.description as destinationzone,
							txn_billing.invoice
					from txn_waybill
					left join txn_billing on txn_billing.billing_number=txn_waybill.billing_reference
					left join consignee on consignee.id=txn_waybill.consignee_id
					left join txn_waybill_package_dimension as packdim on packdim.waybill_number=txn_waybill.waybill_number
					left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id 
					left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id 
					left join zone on zone.id=destinationtbl.zone_id
					left join services on services.id=txn_waybill.package_service
					left join mode_of_transport on mode_of_transport.id=txn_waybill.package_mode_of_transport
					left join delivery_instruction on delivery_instruction.id=txn_waybill.package_delivery_instruction
					left join destination_route on destination_route.id=txn_waybill.destination_route_id
					left join accompanying_documents on accompanying_documents.id=txn_waybill.package_document
					left join transport_charges on transport_charges.id=txn_waybill.package_transport_charges
					left join carrier on carrier.id=txn_waybill.carrier_id
					left join txn_waybill_handling_instruction on txn_waybill_handling_instruction.waybill_number=txn_waybill.waybill_number
					left join handling_instruction on handling_instruction.id=txn_waybill_handling_instruction.handling_instruction_id
					left join txn_waybill_other_charges on txn_waybill_other_charges.waybill_number=txn_waybill.waybill_number 
					left  join user on user.id=txn_waybill.created_by
					left join location on location.id=user.location_id
					left join user_group on user_group.id=user.user_group_id
                	$searchSql
					group by txn_waybill.waybill_number
    ";



  
    $rs = mysql_query($sql);

    

    ///////////////////////// HEADER ///////////////////////////////////
    $writer->addRowWithStyle(array(
        'Run Date & Time: '.date("M d, Y | h:i:s A")
    ),$rowxsstyle);

	$writer->addRowWithStyle(
		array('BILL OF LADING SUMMARY'),
		$reporttitlestyle
	);

	$hlength = 1;
	if($format==1){
		$headercell = array(
							'PICKUP DATE',
							'BOL#/TRACKING NO.',
							'MAWBL',
							'BS #',
							'REFERENCE NUMBER',
							'COMPLETE ADDRESS',
							'REGION',
							'DESTINATION',
							'CITY',
							'CONSIGNEE ACCOUNT NAME',
							'CONSIGNEE COMPANY NAME',
							'CONSIGNEE TEL',
							'SHIPMENT DESCRIPTION',
							'ID NUMBER',
							//'CREATED DATE',
							'RECEIVED DATE',
							'DELIVERY LEAD TIME',
							'ACTUAL RESULT (DAYS)',
							'RECEIVED BY',
							'HIT/MISSED',
							'STATUS',
							'REMARKS',
							'SHIPPER NAME'

		);
		$hlength = count($headercell);
		$writer->addRowWithStyle($headercell,$columnheaderstyle);
		//DETAILS
		$row = 3;
		$line = 1;
		while($obj=mysql_fetch_object($rs)){
			   $zerorated = $obj->zero_rated_flag==1?'YES':'NO';
			   $consigneeaddr = concatAddress(
												   array($obj->consignee_street_address,
														 $obj->consignee_district,
														 $obj->consignee_city,
														 $obj->consignee_state_province,
														 $obj->consignee_zip_code,
														 $obj->consignee_country)
											);

			   $actualdays = 'N/A';
			   $hitmiss = 'N/A';


			   $bolcreateddate = new DateTime($obj->bolcreateddate);
			   $boldelivereddate = $obj->boldelivereddate;

			   $bolcdate = strtotime($obj->bolcreateddate);
			   $bolddate = strtotime($obj->boldelivereddate);

			   if(trim($boldelivereddate)!=''&&trim($boldelivereddate)!=null&&$bolddate>=$bolcdate){
				   $boldelivereddate = new DateTime($boldelivereddate);
				   $actualdays = $boldelivereddate->diff($bolcreateddate);
				   $actualdays = $actualdays->format("%a");
			   }


			   if($actualdays!='N/A'&&$obj->lead_time>0&&$actualdays<=$obj->lead_time){
				   $hitmiss = "HIT";
			   }
			   else if($actualdays!='N/A'&&$obj->lead_time>0&&$actualdays>$obj->lead_time){
				   $hitmiss = "MISSED";
			   }

			   


				$rowdata = array(
                      
										dateFormat($obj->pickup_date,'m/d/Y'),
										utfEncode($obj->waybill_number),
										utfEncode($obj->mawbl_bl),
										utfEncode($obj->invoice),
										utfEncode($obj->reference),
										utfEncode($consigneeaddr),
										utfEncode($obj->destinationzone),
										utfEncode($obj->destination),
										utfEncode($obj->consignee_city),
										utfEncode($obj->consignee_account_name),
										utfEncode($obj->consignee_company_name),
										utfEncode($obj->consignee_tel_number),
										utfEncode($obj->shipment_description),
										utfEncode($obj->id_number),
										//dateFormat($obj->created_date,'m/d/Y'),
										dateFormat($obj->received_date,'m/d/Y'),
										utfEncode($obj->lead_time),
										$actualdays,
										utfEncode($obj->received_by),
										$hitmiss,
										utfEncode($obj->status),
										utfEncode(strlen($obj->last_status_update_remarks)>0 ? $obj->last_status_update_remarks : $obj->remarks),
										utfEncode($obj->shipper_contact_person)
							   );
				$writer->addRowWithStyle($rowdata,$rowdatastyle);
			  
			   
			   $line++;
			   $row++;
		}

	}
	else if($format==2){
					$headercell = array(
								'LINE',
								'SYSTEM ID',
								'WAYBILL NUMBER',
								'MAWBL',
								'BS #',
								'STATUS',
								'BILLING STATUS',
								'BILLING REFERENCE',
								'PAYMENT STATUS',
								'PAYMENT REFERENCE',
								'RECEIVED BY',
								'RECEIVED DATE',
								'LAST STATUS UPDATE REMARKS',
								'DOCUMENT DATE',
								'CREATED DATE',
								'DELIVERY DATE',
								'MANIFEST NO.',
								'INVOICE NO.',
								'REMARKS',
								'BOOKING NUMBER',
								'ORIGIN',
								'DESTINATION',
								'DESTINATION ROUTE',
								'SHIPPER ACCOUNT NUMBER',
								'SHIPPER ACCOUNT NAME',
								'SHIPPER TEL',
								'SHIPPER COMPANY NAME',
								'SHIPPER STREET ADDRESS',
								'SHIPPER DISTRICT',
								'SHIPPER CITY',
								'SHIPPER REGION/PROVINCE',
								'SHIPPER ZIPCODE',
								'SHIPPER COUNTRY', 
								'CONSIGNEE ACCOUNT NUMBER',
								'CONSIGNEE ACCOUNT NAME',
								'CONSIGNEE TEL',
								'CONSIGNEE COMPANY NAME',
								'CONSIGNEE STREET ADDRESS',
								'CONSIGNEE DISTRICT',
								'CONSIGNEE CITY',
								'CONSIGNEE REGION/PROVINCE',
								'CONSIGNEE ZIPCODE',
								'CONSIGNEE COUNTRY',
								'NUMBER OF PACKAGE',
								'DECLARED VALUE',
								'ACTUAL WEIGHT',
								'CBM',
								'VW',
								'SERVICE',
								'MODE OF TRANSPORT',
								'DOCUMENT',
								'DELIVERY INSTRUCTION',
								'TRANSPORT CHARGES',
								'PAY MODE',
								'SHIPMENT DESCRIPTION',
								'FREIGHT COMPUTATION',
								'CHARGEABLE WEIGHT',
								'VALUATION',
								'FREIGHT CHARGES',
								'INSURANCE CHARGES',
								'FUEL CHARGES',
								'BUNKER CHARGES',
								'MINIMUM CHARGES',
								'OTHER CHARGES',
								'SUBTOTAL',
								'ZERO RATED FLAG',
								'VAT',
								'TOTAL AMOUNT',
								'AMOUNT FOR COLLECTION',
								'PACKAGE DIMENSIONS'

					);
					$hlength = count($headercell);
					$writer->addRowWithStyle($headercell,$columnheaderstyle);

					//DETAILS
					$row = 3;
					$line = 1;
					while($obj=mysql_fetch_object($rs)){
						   $zerorated = $obj->zero_rated_flag==1?'YES':'NO';
						   $rowdata = array(
												   utfEncode($line),
												   utfEncode($obj->id),
												   utfEncode($obj->waybill_number),
												   utfEncode($obj->mawbl_bl),
												   utfEncode($obj->invoice),
												   utfEncode($obj->status),
												   utfEncode($obj->billingflag),
												   utfEncode($obj->billing_reference),
												   utfEncode($obj->paidflag),
												   utfEncode($obj->payment_reference),
												   utfEncode($obj->received_by),
												   dateFormat($obj->received_date,'m/d/Y h:i:s A'),
												   utfEncode($obj->last_status_update_remarks),
												   dateFormat($obj->document_date,'m/d/Y'),
												   dateFormat($obj->created_date,'m/d/Y'),
												   dateFormat($obj->delivery_date,'m/d/Y'),
												   utfEncode($obj->manifest_number),
												   utfEncode($obj->invoice_number),
												   utfEncode($obj->remarks),
												   utfEncode($obj->booking_number),
												   utfEncode($obj->origin),
												   utfEncode($obj->destination),
												   utfEncode($obj->destinationroute), 
												   utfEncode($obj->shipper_account_number),
												   utfEncode($obj->shipper_account_name),
												   utfEncode($obj->shipper_tel_number),
												   utfEncode($obj->shipper_company_name),
												   utfEncode($obj->shipper_street_address),
												   utfEncode($obj->shipper_district),
												   utfEncode($obj->shipper_city),
												   utfEncode($obj->shipper_state_province),
												   utfEncode($obj->shipper_zip_code),
												   utfEncode($obj->shipper_country),
												   utfEncode($obj->consignee_account_number),
												   utfEncode($obj->consignee_account_name),
												   utfEncode($obj->consignee_tel_number),
												   utfEncode($obj->consignee_company_name),
												   utfEncode($obj->consignee_street_address),
												   utfEncode($obj->consignee_district),
												   utfEncode($obj->consignee_city),
												   utfEncode($obj->consignee_state_province),
												   utfEncode($obj->consignee_zip_code),
												   utfEncode($obj->consignee_country),
												   utfEncode($obj->package_number_of_packages),
												   utfEncode($obj->package_declared_value),
												   utfEncode($obj->package_actual_weight),
												   utfEncode($obj->package_cbm),
												   utfEncode($obj->package_vw),
												   utfEncode($obj->servicedesc),
												   utfEncode($obj->modeoftransport),
												   utfEncode($obj->document),
												   utfEncode($obj->deliveryinstruction),
												   utfEncode($obj->transportcharges),
												   utfEncode($obj->package_pay_mode),
												   utfEncode($obj->shipment_description),
												   utfEncode($obj->package_freight_computation),
												   utfEncode($obj->package_chargeable_weight),
												   utfEncode($obj->package_valuation),
												   utfEncode($obj->package_freight),
												   utfEncode($obj->package_insurance_rate),
												   utfEncode($obj->package_fuel_rate),
												   utfEncode($obj->package_bunker_rate),
												   utfEncode($obj->package_minimum_rate),
												   utfEncode($obj->othercharges),
												   utfEncode($obj->subtotal),
												   utfEncode($zerorated),
												   utfEncode($obj->vat),
												   utfEncode($obj->total_amount),
												   utfEncode($obj->amount_for_collection),
												   $obj->packdimensions
	   
											   );
	   
						   $writer->addRowWithStyle($rowdata,$rowdatastyle);
						  
						   
						   $line++;
						   $row++;
					}

	}
	else if($format==3){
					$headercell = array(
										'ITEM ID',
										'SHIPMENT MODE',
										'TRACKING NUMBER',
										'MAWBL',
										'BS #',
										'PUR',
										'ORIGIN',
										'DESTINATION',
										'TRANSACTION DATE',
										'ODZ',
										'SHIPPER ACCOUNT NO.',
										'SHIPPER',
										'STREET/BLDG (SHIPPER)',
										'BARANGAY (SHIPPER)',
										'CITY/MUNICIPALITY (SHIPPER)',
										'CONTACT NUMBER (SHIPPER)',
										'SEND SMS (SHIPPER)',
										'MOBILE NUMBER (SHIPPER)',
										'PAY MODE',
										'PRODUCT LINE',
										'SERVICE MODE',
										'COD AMOUNT',
										'CONSIGNEE ACCOUNT NO.',
										'CONSIGNEE',
										'STREET/BLDG (CONSIGNEE)',
										'BARANGAY (CONSIGNEE)',
										'CITY/MUNICIPALITY (CONSIGNEE)',
										'CONTACT NUMBER (CONSIGNEE)',
										'SEND SMS (CONSIGNEE)',
										'MOBILE NUMBER (CONSIGNEE)',
										'SPECIAL INSTRUCTIONS',
										'QUANTITY',
										'PKG',
										'ACT WT (KGS)',
										'LENGTH (CM)',
										'WIDTH (CM)',
										'HEIGHT (CM)',
										'VOL WT',
										'CBM',
										'CHARGEABLE WT',
										'DECLARED VALUE',
										'DESCRIPTION',
										'COMMODITY',
										'FOR CRATING',
										'ATTACHMENT NAME 1',
										'REFERENCE NO. 1',
										'ATTACHMENT NAME 2',
										'REFERENCE NO. 2',
										'ATTACHMENT NAME 3',
										'REFERENCE NO. 3',
										'ATTACHMENT NAME 4',
										'REFERENCE NO. 4',
										'OR NUMBER',
										'OR AMOUNT',
										'DELIVERY REQUIREMENTS',
										'CONSIGNEE EMAIL ADD',
										'BILLING REFERENCE',
										'DISPATCHER NAME',
										'LOCATION',
										'USER GROUP',
										'TIME CREATED',
										'TIME INTERVAL',
										'BILLING STATUS',
										'BILLING REFERENCE',
										'PAYMENT STATUS',
										'PAYMENT REFERENCE'

					);
					$hlength = count($headercell);
					$writer->addRowWithStyle($headercell,$columnheaderstyle);

					//DETAILS
					$row = 2;
					$line = 1;
					$rowuser = '';
					$prevtime = '';
					$timediff = '';
					while($obj=mysql_fetch_object($rs)){
	   
						   if($prevtime==''||$rowuser!=$obj->created_by){
							   $timediff = '';
							   $prevtime = $obj->created_date;
							   $rowuser = $obj->created_by;
						   }
						   else{
							   
							   $then = new DateTime($prevtime);
							   $nexttime = new DateTime($obj->created_date);
							   $sinceThen = $then->diff($nexttime);
							   $prevtime = $obj->created_date;
							   $rowuser = $obj->created_by;
							   $timediff = $sinceThen->d.":".$sinceThen->h.":".$sinceThen->i.":".$sinceThen->s;
						   }
	   
						   $zerorated = $obj->zero_rated_flag==1?'YES':'NO';
	   
						   $rowdata = array(
												  
												   '',//ITEM ID
												   utfEncode($obj->modeoftransport),//SHIPMENT MODE
												   utfEncode($obj->waybill_number),//TRACKING NUMBER
												   utfEncode($obj->mawbl_bl),
												   utfEncode($obj->invoice),
												   '',//PUR
												   utfEncode($obj->origin),//ORIGIN
												   utfEncode($obj->destination),//DESTINATION
												   dateFormat($obj->document_date,'m/d/Y'),//TRANSACTION DATE
												   utfEncode($obj->odaflag),
												   utfEncode($obj->shipper_account_number),//SHIPPER ACCOUNT NO.
												   utfEncode($obj->shipper_account_name),//SHIPPER
												   utfEncode($obj->shipper_street_address),//STREET/BLDG (SHIPPER)
												   utfEncode($obj->shipper_district),//BARANGAY (SHIPPER)
												   utfEncode($obj->shipper_city),//CITY/MUNICIPALITY (SHIPPER)
												   utfEncode($obj->shipper_tel_number),//CONTACT NUMBER (SHIPPER)
												   '',//SEND SMS (SHIPPER)
												   utfEncode($obj->shipper_tel_number),//MOBILE NUMBER (SHIPPER)
												   utfEncode($obj->package_pay_mode),//PAY MODE
												   '',//PRODUCT LINE
												   utfEncode($obj->servicedesc),//SERVICE MODE
												   '',//COD AMOUNT
												   utfEncode($obj->consignee_account_number),//consignee ACCOUNT NO.
												   utfEncode($obj->consignee_account_name),//consignee
												   utfEncode($obj->consignee_street_address),//STREET/BLDG (consignee)
												   utfEncode($obj->consignee_district),//BARANGAY (consignee)
												   utfEncode($obj->consignee_city),//CITY/MUNICIPALITY (consignee)
												   utfEncode($obj->consignee_tel_number),//CONTACT NUMBER (consignee)
												   '',//SEND SMS (consignee)
												   utfEncode($obj->consignee_tel_number),//MOBILE NUMBER (consignee)
												   '',//SPECIAL INSTRUCTIONS
												   utfEncode($obj->package_number_of_packages),//QUANTITY
												   '',//PKG
												   utfEncode($obj->package_actual_weight),//ACT WT (KGS)
												   utfEncode($obj->packdimensionslen),//LENGTH (CM)
												   utfEncode($obj->packdimensionswid),//WIDTH (CM)
												   utfEncode($obj->packdimensionshgt),//HEIGHT (CM)
												   utfEncode($obj->package_vw),//VOL WT
												   utfEncode($obj->package_cbm),//CBM
												   utfEncode($obj->package_chargeable_weight),//CHARGEABLE WT
												   utfEncode($obj->package_declared_value),//DECLARED VALUE
												   utfEncode($obj->shipment_description),//DESCRIPTION
												   utfEncode($obj->handlinginstruction),//COMMODITY
												   '',//FOR CRATING
												   '',//ATTACHMENT NAME 1
												   '',//REFERENCE NO. 1
												   '',//ATTACHMENT NAME 2
												   '',//REFERENCE NO. 2
												   '',//ATTACHMENT NAME 3
												   '',//REFERENCE NO. 3
												   '',//ATTACHMENT NAME 4
												   '',//REFERENCE NO. 4
												   '',//OR NUMBER
												   '',//OR AMOUNT
												   '',//DELIVERY REQUIREMENTS
												   '',//CONSIGNEE EMAIL ADD
												   '',//BILLING REFERENCE
												   utfEncode($obj->bolcreatedby),//DISPATCHER NAME
												   utfEncode($obj->location),//LOCATION
												   utfEncode($obj->usergroup),//USER GROUP
												   dateFormat($obj->created_date,'m/d/Y H:i:s'),
												   $timediff,
												   utfEncode($obj->billingflag),
												   utfEncode($obj->billing_reference),
												   utfEncode($obj->paidflag),
												   utfEncode($obj->payment_reference)
												  
											 
	   
											   );
	   
						   
						   
						   $writer->addRowWithStyle($rowdata,$rowdatastyle);
						   $line++;
						   $row++;
					}
	}
	else  if($format==4){

					// HEADER
					$headercell = array(
											'BOL#/TRACKING NO.',
											'MAWBL',
											'STATUS',
											'BILLING STATUS',
											'BILLING REFERENCE',
											'BS #',
											'SHIPPER ACCOUNT NAME',
											'SHIPPER COMPANY NAME',
											'CONSIGNEE ACCOUNT NAME',
											'CONSIGNEE COMPANY NAME',
											'PAYMENT STATUS'

					);
					$hlength = count($headercell);
					$writer->addRowWithStyle($headercell,$columnheaderstyle);


					//DETAILS
					$row = 3;
					$line = 1;
					while($obj=mysql_fetch_object($rs)){
						  
	   
						   $rowdata = array(
												   utfEncode($obj->waybill_number),
												   utfEncode($obj->mawbl_bl),
												   utfEncode($obj->status),
												   utfEncode($obj->billingflag),
												   utfEncode($obj->billing_reference),
												   utfEncode($obj->invoice),
												   utfEncode($obj->shipper_account_name),
												   utfEncode($obj->shipper_company_name),
												   utfEncode($obj->consignee_account_name),
												   utfEncode($obj->consignee_company_name),
												   utfEncode($obj->paidflag)
	   
							);
	   
						   $writer->addRowWithStyle($rowdata,$rowdatastyle);
						   
						   $line++;
						   $row++;
					}
	}

	$lastcolumn = 'A';
	for($i=1;$i<$hlength;$i++){
		$lastcolumn++;
	}
	$currentSheet = $writer->getCurrentSheet();
    $mergeRanges = ["A1:".$lastcolumn."1","A2:".$lastcolumn."2"]; // or ['A1:A4','A1:E1']
    $currentSheet->setMergeRanges($mergeRanges);

    $writer->close();
?>