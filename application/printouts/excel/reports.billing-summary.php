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
    $writer->openToBrowser("billing-statement-summary-".date('Ymd-hisA').".xlsx");

    $border1 = (new BorderBuilder())
                  ->setBorderBottom(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
                  ->setBorderTop(Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)
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

    $columnheaderstyle = (new StyleBuilder())
           ->setFontBold()
           ->setFontSize(9)
           ->setBorder($border1)
           ->setBackgroundColor(Color::WHITE)
           ->build();

    $rowstyle = (new StyleBuilder())
           ->setFontSize(9)
          // ->setBorder($border1)
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

    

    $filter = array();
	$format = isset($_GET['format'])?escapeString(strtoupper($_GET['format'])):'';
    $status = isset($_GET['status'])?escapeString(strtoupper($_GET['status'])):'';
    $paidflag = isset($_GET['paidflag'])?escapeString(strtoupper($_GET['paidflag'])):'';
    $shipper = isset($_GET['shipper'])?escapeString(strtoupper($_GET['shipper'])):'';
    $waybill = isset($_GET['waybill'])?escapeString($_GET['waybill']):'';
    $accountexecutive = isset($_GET['accountexecutive'])?escapeString($_GET['accountexecutive']):'';
    $docdatefrom = isset($_GET['docdatefrom'])?escapeString($_GET['docdatefrom']):'';
    $docdateto = isset($_GET['docdateto'])?escapeString($_GET['docdateto']):'';
    $duedatefrom = isset($_GET['duedatefrom'])?escapeString($_GET['duedatefrom']):'';
    $duedateto = isset($_GET['duedateto'])?escapeString($_GET['duedateto']):'';
    $createddatefrom = isset($_GET['createddatefrom'])?escapeString($_GET['createddatefrom']):'';
    $createddateto = isset($_GET['createddateto'])?escapeString($_GET['createddateto']):'';
	$bsstartseries = isset($_GET['bsstartseries'])?escapeString(strtoupper($_GET['bsstartseries'])):'';
    $bsendseries = isset($_GET['bsendseries'])?escapeString(strtoupper($_GET['bsendseries'])):'';
	$bsstartseries = is_numeric($bsstartseries)==true?$bsstartseries:'';
    $bsendseries = is_numeric($bsendseries)==true?$bsendseries:'';

    if($docdatefrom!=''){
        $docdatefrom = date('Y-m-d', strtotime($docdatefrom));
    }
    if($docdateto!=''){
        $docdateto = date('Y-m-d', strtotime($docdateto));
    }

    if($duedatefrom!=''){
        $duedatefrom = date('Y-m-d', strtotime($duedatefrom));
    }
    if($duedateto!=''){
        $duedateto = date('Y-m-d', strtotime($duedateto));
    }

    if($createddatefrom!=''){
        $createddatefrom = date('Y-m-d', strtotime($createddatefrom));
    }
    if($createddateto!=''){
        $createddateto = date('Y-m-d', strtotime($createddateto));
    }

    //QUERY 
    if($format==1){
        $customqry = "
                                select  txn_billing.id,
                                        txn_billing.billing_number,
                                        txn_billing.document_date,
                                        txn_billing.shipper_id,
                                        txn_billing.bill_to_account_number,
                                        txn_billing.bill_to_account_name,
                                        txn_billing.bill_to_company_name,
                                        txn_billing.email,
                                        txn_billing.fax,
                                        txn_billing.received_date,
                                        txn_billing.received_by,
                                        txn_billing.posted_date,
                                        txn_billing.phone,
                                        txn_billing.mobile,
                                        txn_billing.company_street_address,
                                        txn_billing.company_district,
                                        txn_billing.company_city,
                                        txn_billing.company_state_province,
                                        txn_billing.company_zip_code,
                                        txn_billing.company_country,
                                        txn_billing.billing_contact_person,
                                        txn_billing.billing_street_address,
                                        txn_billing.billing_district,
                                        txn_billing.billing_city,
                                        txn_billing.billing_state_province,
                                        txn_billing.billing_zip_code,
                                        txn_billing.billing_country,
                                        txn_billing.total_amount,
                                        txn_billing.payment_due_date,
                                        txn_billing.remarks,
                                        txn_billing.created_date,
                                        txn_billing.status,
                                        txn_billing.attention,
                                        txn_billing.invoice,
                                        txn_billing.reason,
                                        txn_billing.subtotal,
                                        txn_billing.vat,
                                        case 
                                            when txn_billing.paid_flag=1 then 'YES'
                                            when txn_billing.paid_flag=0 then 'NO'
                                            else ''
                                        end as paidflag,
                                        txn_billing.total_vatable_charges,
                                        txn_billing.total_non_vatable_charges,
                                        concat(cuser.first_name,' ',cuser.last_name) as createdby,
                                        count(txn_billing_waybill.waybill_number) as numberofbols,
                                        billing_type.description as billingtype,
                                        account_executive.name as accountexecutive
                                from txn_billing
                                left join billing_type on billing_type.id=txn_billing.billing_type_id 
                                left join account_executive on account_executive.id=txn_billing.account_executive_id
                                left join txn_billing_waybill on txn_billing_waybill.billing_number=txn_billing.billing_number and flag=1
                                left join user as cuser on cuser.id=txn_billing.created_by";
    }
    else if($format==2){


        $customqry =    "select txn_billing.id,
                                txn_billing.billing_number,
                                txn_billing.document_date,
                                txn_billing.shipper_id,
                                txn_billing.bill_to_account_number,
                                txn_billing.bill_to_account_name,
                                txn_billing.bill_to_company_name,
                                txn_billing.email,
                                txn_billing.fax,
                                txn_billing.phone,
                                txn_billing.mobile,
                                txn_billing.company_street_address,
                                txn_billing.company_district,
                                txn_billing.company_city,
                                txn_billing.company_state_province,
                                txn_billing.company_zip_code,
                                txn_billing.company_country,
                                txn_billing.billing_contact_person,
                                txn_billing.billing_street_address,
                                txn_billing.billing_district,
                                txn_billing.billing_city,
                                txn_billing.billing_state_province,
                                txn_billing.billing_zip_code,
                                txn_billing.billing_country,
                                txn_billing.total_amount,
                                txn_billing.payment_due_date,
                                txn_billing.remarks,
                                txn_billing.created_date,
                                txn_billing.posted_date,
                                txn_billing.status,
                                txn_billing.attention,
                                txn_billing.invoice,
                                txn_billing.reason,
                                txn_billing.subtotal,
                                txn_billing.vat,
                                case 
                                    when txn_billing.paid_flag=1 then 'YES'
                                    when txn_billing.paid_flag=0 then 'NO'
                                    else ''
                                end as paidflag,
                                txn_billing.total_vatable_charges,
                                txn_billing.total_non_vatable_charges,
                                concat(cuser.first_name,' ',cuser.last_name) as createdby,
                                txn_billing_waybill.flag,
                                txn_billing_waybill.regular_charges as bwbregularcharges,
                                txn_billing_waybill.other_charges_vatable as bwbotherchargesvatable,
                                txn_billing_waybill.other_charges_non_vatable as bwbotherchargesnonvatable,
                                txn_billing_waybill.vat as bwbvat,
                                txn_billing_waybill.amount as bwbtotalamount,
                                (txn_billing_waybill.regular_charges+txn_billing_waybill.other_charges_vatable) as bwbtotalvatablecharges,
                                (txn_billing_waybill.other_charges_non_vatable+txn_billing_waybill.other_charges_vatable) as bwbtotalothercharges,
                                txn_billing_waybill.waybill_number,
                                origin_destination_port.description as wbdestination,
                                services.description as wbservices,
                                txn_waybill.document_date as wbdate,
                                txn_waybill.shipment_description as wbdescription,
                                txn_waybill.package_declared_value as wbdeclaredvalue,
                                txn_waybill.package_number_of_packages as wbnumofpackages,
                                txn_waybill.package_actual_weight as wbactualweight,
                                txn_waybill.package_cbm as wbcbm,
                                txn_waybill.package_chargeable_weight as wbchargeableweight,
                                txn_waybill.package_freight as wbfreightcharges,
                                txn_waybill.package_valuation as wbvaluation,
                                txn_waybill.vat as wbvat,
                                txn_waybill.total_amount as wbtotalamount,
                                txn_waybill.package_freight_computation as wbfreightcomputation,
                                txn_waybill.package_insurance_rate as wbinsurancecharges,
                                txn_waybill.package_fuel_rate as wbfuelcharges,
                                txn_waybill.package_bunker_rate as wbbunkercharges,
                                txn_waybill.package_minimum_rate as wbminimumcharges,
                                txn_waybill.subtotal as wbsubtotal,
                                txn_waybill.package_vw as wbvw,
                                txn_waybill.fixed_rate_amount as wbfixedcharges,
                                txn_waybill.pull_out_fee as wbpulloutfee,
                                txn_waybill.oda_charges as wbodacharges,
                                txn_waybill.total_handling_charges as wbhandlingcharges,
                                txn_waybill.return_document_fee as wbreturndocfee,
                                txn_waybill.waybill_fee as wbwaybillfee,
                                txn_waybill.security_fee as wbsecurityfee,
                                txn_waybill.doc_stamp_fee as wbdocstampfee,
                                txn_waybill.total_regular_charges as wbtotalregularcharges,
                                txn_waybill.total_other_charges_vatable as wbtotalotherchargesvatable,
                                txn_waybill.total_other_charges_non_vatable as wbtotalotherchargesnonvatable,
                                (txn_waybill.total_other_charges_non_vatable+txn_waybill.total_other_charges_vatable) as wbtotalothercharges,
                                group_concat(handling_instruction.description separator ', ') as wbhandlinginstruction,
                                group_concat(
                                            concat(
                                                        '(',
                                                        txn_waybill_package_dimension.length,
                                                        ' x ',
                                                        txn_waybill_package_dimension.width,
                                                        ' x ',
                                                        txn_waybill_package_dimension.height,
                                                        ')'
                                                    )
                                            separator ', '
                                            ) as wbpackagedimensions,
                                txn_billing.billing_type_id,
                                billing_type.description as billingtype,
                                account_executive.name as accountexecutive
                         from txn_billing
                         left join billing_type on billing_type.id=txn_billing.billing_type_id 
                         left join account_executive on account_executive.id=txn_billing.account_executive_id
                         left join txn_billing_waybill on txn_billing_waybill.billing_number=txn_billing.billing_number
                         left join txn_waybill on txn_waybill.waybill_number=txn_billing_waybill.waybill_number
                         left join origin_destination_port on origin_destination_port.id=txn_waybill.destination_id
                         left join services on services.id=txn_waybill.package_service
                         left join txn_waybill_package_dimension on txn_waybill_package_dimension.waybill_number=txn_waybill.waybill_number
                         left join txn_waybill_handling_instruction on txn_waybill_handling_instruction.waybill_number=txn_waybill.waybill_number
                         left join handling_instruction on handling_instruction.id=txn_waybill_handling_instruction.handling_instruction_id
                         left join user as cuser on cuser.id=txn_billing.created_by

                   ";
    }

    
    
    if($status!=''&&$status!='NULL'){
        array_push($filter, "txn_billing.status='".$status."'");
    }
    if(trim($shipper)!=''&&strtoupper($shipper)!='NULL'){
      array_push($filter, "txn_billing.shipper_id='".$shipper."'");
    }

    if(trim($accountexecutive)!=''&&strtoupper($accountexecutive)!='NULL'){
      array_push($filter, "txn_billing.account_executive_id='".$accountexecutive."'");
    }

    if(trim($waybill)!=''&&$format!=1){
      array_push($filter, "txn_billing_waybill.waybill_number='".$waybill."'");
    }

    if(trim($paidflag)!=''&&strtoupper($paidflag)!='NULL'){
        array_push($filter, "txn_billing.paid_flag='$paidflag'");
    }

    if($docdatefrom!=''&&$docdateto!=''){
        array_push($filter,"date(txn_billing.document_date) >= '$docdatefrom' and date(txn_billing.document_date) <= '$docdateto'");
    }
    else if($docdatefrom==''&&$docdateto!=''){
        array_push($arr,"date(txn_billing.document_date) <= '$docdateto'");
    }
    else if($docdatefrom!=''&&$docdateto==''){
         array_push($filter,"date(txn_billing.document_date) >= '$docdatefrom'");
    }

    if($duedatefrom!=''&&$duedateto!=''){
        array_push($filter,"date(txn_billing.payment_due_date) >= '$duedatefrom' and date(txn_billing.payment_due_date) <= '$duedateto'");
    }
    else if($duedatefrom==''&&$duedateto!=''){
        array_push($filter,"date(txn_billing.payment_due_date) <= '$duedateto'");
    }
    else if($duedatefrom!=''&&$duedateto==''){
         array_push($filter,"date(txn_billing.payment_due_date) >= '$duedatefrom'");
    }

    if($createddatefrom!=''&&$createddateto!=''){
        array_push($filter,"date(txn_billing.created_date) >= '$createddatefrom' and date(txn_billing.created_date) <= '$createddateto'");
    }
    else if($createddatefrom==''&&$createddateto!=''){
        array_push($filter,"date(txn_billing.created_date) <= '$createddateto'");
    }
    else if($createddatefrom!=''&&$createddateto==''){
         array_push($filter,"date(txn_billing.created_date) >= '$createddatefrom'");
    }

	if($bsstartseries!=''&&$bsendseries!=''){
		array_push($filter,"trim(LEADING '0' from txn_billing.invoice) >= $bsstartseries and trim(LEADING '0' from txn_billing.invoice) <= $bsendseries");
	}
	else if($bsstartseries==''&&$bsendseries!=''){
		array_push($filter,"trim(LEADING '0' from txn_billing.invoice) <= $bsendseries");
	}
	else if($bsstartseries!=''&&$bsendseries==''){
		 array_push($filter,"trim(LEADING '0' from txn_billing.invoice) >= $bsstartseries");
	}


    $searchSql = '';
    if(count($filter)>0){
    	$searchSql = ' where '.implode(" and ", $filter);
    }

    if($format==1){
       $sql = $customqry.$searchSql.' group by txn_billing.billing_number order by txn_billing.billing_number asc';

       $header = array(
                            'BILLING REFERENCE NUMBER',
                            'BS#',
                            'STATUS',
                            'DOCUMENT DATE',
                            'RECEIVED DATE',
                            'RECEIVED BY',
                            'PAID',
                            'ACCOUNT NAME',
                            'COMPANY NAME',
                            'ACCOUNT EXECUTIVE',
                            'NO. OF BOLS',
                            'COURIER',
                            'FREIGHT',
                            'INTERNATIONAL',
                            'WAREHOUSING',
                            'TRUCKING',
                            'SUBTOTAL',
                            'VAT',
                            'TOTAL AMOUNT',
                            'CREATED DATE',
                            'CREATED BY'
                            
                        );
    }
    else if($format==2){
       $sql = $customqry.$searchSql.' group by txn_billing.billing_number, txn_billing_waybill.waybill_number order by txn_billing.billing_number asc';

       $header = array(
                            'LINE',
                            'SYSTEM ID',
                            'BILLING NUMBER',
                            'BS #',
                            'BILLING TYPE',
                            'STATUS',
                            'ACCOUNT NUMBER',
                            'ACCOUNT NAME',
                            'COMPANY NAME',
                            'ACCOUNT EXECUTIVE',
                            'DOCUMENT DATE',
                            'CREATED DATE',
                            'DUE DATE',
                            'REMARKS',
                            'REASON',
                            'EMAIL',
                            'FAX',
                            'PHONE',
                            'MOBILE',
                            'STREET',
                            'DISTRICT',
                            'CITY',
                            'REGION',
                            'ZIP CODE',
                            'COUNTRY',
                            'BILLING CONTACT PERSON',
                            'BILLING STREET',
                            'BILLING DISTRICT',
                            'BILLING CITY',
                            'BILLING REGION',
                            'BILLING ZIP CODE',
                            'BILLING COUNTRY',
                            'BILLING - TOTAL VATABLE CHARGES',
                            'BILLING - TOTAL NON VATABLE CHARGES',
                            'BILLING - VAT',
                            'BILLING- TOTAL AMOUNT',
                            'WAYBILL NUMBER',
                            'DATE',
                            'DESTINATION',
                            'SERVICES',
                            'PACKAGE DIMENSION',
                            'HANDLING INSTRUCTION',
                            'SHIPMENT DESCRIPTION',
                            'DECALRED VALUE',
                            'NO. OF PACKAGES',
                            'VOL. WEIGHT',
                            'CBM',
                            'ACTUAL WEIGHT',
                            'FREIGHT COMPUTATION',
                            'CHARGEABLE WEIGHT',
                            'RETURN DOCUMENT FEE',
                            'WAYBILL FEE',
                            'SECURITY FEE',
                            'DOC STAMP FEE',
                            'ODA CHARGES',
                            'VALUATION',
                            'FREIGHT CHARGES',
                            'INSURANCE CHARGES',
                            'FUEL CHARGES',
                            'BUNKER CHARGES',
                            'MINIMUM CHARGES',
                            'FIXED RATE FEE',
                            'TOTAL HANDLING CHARGES',
                            'TOTAL REGULAR CHARGES',
                            'TOTAL OTHER CHARGES - NON VATABLE',
                            'TOTAL OTHER CHARGES - VATABLE',
                            'TOTAL OTHER CHARGES',
                            'TOTAL VATABLE CHARGES',
                            'TOTAL NON VATABLE CHARGES',
                            'VAT',
                            'TOTAL AMOUNT'
                            
                        );
    }
    $rs = mysql_query($sql);

    

    ///////////////////////// HEADER ///////////////////////////////////
    $writer->addRowWithStyle(array(
        'Run Date & Time: '.date("M d, Y | h:i:s A")
    ),$rowxsstyle);

    $temp = 'A';
    for($i=0;$i<count($header);$i++){
        $temp++;
    }


    $currentSheet = $writer->getCurrentSheet();
    $mergeRanges = ['A2:'.$temp.'2']; // or ['A1:A4','A1:E1']
    $currentSheet->setMergeRanges($mergeRanges);
    $writer->addRowWithStyle(
                                array('BILLING SUMMARY'),
                                $reporttitlestyle
                            );

    $writer->addRowWithStyle($header,$columnheaderstyle);



    ////////////////////// DETAILS ////////////////////////////

    if($format==1){
        while($obj=mysql_fetch_object($rs)){
            $subtotal = $obj->total_vatable_charges+$obj->total_non_vatable_charges;
            $vat = $obj->vat;
            $totalamount = $subtotal+$vat;
            $billtypecourier = '';
            $billtypefreight = '';
            $billtypeinternational = '';
            $billtypewarehousing = '';
            $billtypetrucking = '';


            if(trim(strtoupper($obj->billingtype))=='COURIER'){
                $billtypecourier = $subtotal;
            }
            else if(trim(strtoupper($obj->billingtype))=='FREIGHT'){
                $billtypefreight = $subtotal;
            }
            else if(trim(strtoupper($obj->billingtype))=='INTERNATIONAL'){
                $billtypeinternational = $subtotal;
            }
            else if(trim(strtoupper($obj->billingtype))=='WAREHOUSING'){
                $billtypewarehousing = $subtotal;
            }
            else if(trim(strtoupper($obj->billingtype))=='TRUCKING'){
                $billtypetrucking = $subtotal;
            }

            $rowdata = array(
                        
                                        utfEncode($obj->billing_number),
                                        utfEncode($obj->invoice),
                                        utfEncode($obj->status),
                                        dateFormat($obj->document_date,'m/d/Y'),
                                        dateFormat($obj->received_date,'m/d/Y'),
                                        utfEncode($obj->received_by),
                                        utfEncode($obj->paidflag),
                                        utfEncode($obj->bill_to_account_name),
                                        utfEncode($obj->bill_to_company_name),
                                        utfEncode($obj->accountexecutive),
                                        utfEncode($obj->numberofbols),
                                        $billtypecourier,
                                        $billtypefreight,
                                        $billtypeinternational,
                                        $billtypewarehousing,
                                        $billtypetrucking,
                                        $subtotal,
                                        $vat,
                                        $totalamount,
                                        dateFormat($obj->created_date,'m/d/Y'),
                                        utfEncode($obj->createdby)
            );
            $writer->addRowWithStyle($rowdata,$rowstyle);
        }
    }
    else if($format==2){
        while($obj=mysql_fetch_object($rs)){
            $rowdata = array(
                        
                            utfEncode($obj->manifest_number),
                            utfEncode($obj->pickupdate),
                            utfEncode($obj->booking_number),
                            utfEncode($obj->waybill_number),
                            utfEncode($obj->billingstatementnumber),
                            utfEncode($obj->shipper_account_name),
                            utfEncode($obj->consignee_account_name),
                            utfEncode($obj->destination),
                            utfEncode($obj->shipment_description),
                            '',
                            utfEncode($obj->manifestcreatedby)
                    );
            $writer->addRowWithStyle($rowdata,$rowstyle);
        }
    }




    $writer->close();
?>