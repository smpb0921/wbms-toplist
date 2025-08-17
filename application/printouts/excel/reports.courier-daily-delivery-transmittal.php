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
    $writer->openToBrowser("cddt-report-".date('Ymd-hisA').".xlsx");

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

    



    //QUERY 
    $customqry = "
                    select  txn_manifest_waybill.manifest_number,
                            txn_manifest_waybill.waybill_number,
                            date_format(txn_manifest.document_date,'%m/%d/%Y') as documentdate,
                            date_format(txn_manifest.created_date,'%m/%d/%Y') as createddate,
                            txn_waybill.booking_number,
                            date_format(txn_waybill.pickup_date,'%m/%d/%Y') as pickupdate,
                            txn_waybill.mawbl_bl,
                            txn_waybill.shipper_account_name,
                            txn_waybill.shipper_company_name,
                            txn_waybill.consignee_account_name,
                            txn_waybill.consignee_company_name,
                            origin_destination_port.description as destination,
                            txn_billing.invoice as billingstatementnumber,
                            txn_waybill.shipment_description,
                            concat(user.first_name,' ',user.last_name) as manifestcreatedby
                    from txn_manifest_waybill 
                    left join txn_manifest on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number
                    left join txn_waybill on txn_waybill.waybill_number=txn_manifest_waybill.waybill_number 
                    left join origin_destination_port on origin_destination_port.id=txn_waybill.destination_id 
                    left join txn_billing on txn_billing.billing_number=txn_waybill.billing_reference
                    left join user on user.id=txn_manifest.created_by";

    $filter = array();
	$driver = isset($_GET['driver'])&&trim($_GET['driver'])!=''?escapeString(strtoupper($_GET['driver'])):'';
    $docdate = isset($_GET['docdate'])&&trim($_GET['docdate'])!=''?escapeString($_GET['docdate']):'';

    
    if($docdate!=''){
        $docdate = date('Y-m-d', strtotime($docdate));
        array_push($filter, "date(txn_manifest.document_date)='$docdate'");
    }

	if($driver!=''&&$driver!='NULL'){
        array_push($filter, "upper(txn_manifest.driver_name)='$driver'");
	}
   
    if(count($filter)>0){
    	$searchSql = ' where '.implode(" and ", $filter);
    }

    $sql = $customqry.$searchSql." order by txn_manifest.manifest_number, txn_waybill.waybill_number asc";
    $rs = mysql_query($sql);

    

    ///////////////////////// HEADER ///////////////////////////////////
    $writer->addRowWithStyle(array(
        'Run Date & Time: '.date("M d, Y | h:i:s A")
    ),$rowxsstyle);


    $currentSheet = $writer->getCurrentSheet();
    $mergeRanges = ['A2:K2', 'A3:E3','A4:E4']; // or ['A1:A4','A1:E1']
    $currentSheet->setMergeRanges($mergeRanges);
    $writer->addRowWithStyle(array('COURIER DAILY DELIVERY TRANSMITTAL'
    ),$reporttitlestyle);

    $header = array(
                        "No.",
                        "Pickup Date",
                        "BRF #",
                        "BOL #",
                        "AUTO DR #",
                        "Shipper's Name/Client's Name",
                        "Consignee Name",
                        "Destination",
                        "Content/Description",
                        "Status",
                        "Updated By"
    );

    $writer->addRowWithStyle(
                                array(
                                        'Assigned Courier: '.$driver
                                    ),
                                $rowheaderstyle
    );
    $writer->addRowWithStyle(
                                array(
                                        'Transmittal Date: '.$docdate
                                ),
                                $rowheaderstyle
    );
    $writer->addRowWithStyle($header,$columnheaderstyle);



    ////////////////////// DETAILS ////////////////////////////

    
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

    $writer->addRow(array(''));
    $writer->addRowWithStyle(
                                array(
                                        'Prepared by:',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        'Received by:'
                                ),
                                $rowheaderstyle
    );
    $writer->addRow(array(''));
    $writer->addRow(array(''));
    $writer->addRowWithStyle(
        array(
                'Checked by:',
                '',
                '',
                '',
                '',
                '',
                '',
                'Date Received:'
        ),
        $rowheaderstyle
    );
    $writer->addRow(array(''));
    $writer->addRowWithStyle(
        array(
                'Date Effective:',
                'Februaty 12, 2018',
                '',
                '',
                '',
                '',
                '',
                'QF- QPR-07 Issue 07-Issue #2 rev. 00'
        ),
        $rowstyle
    );


    $writer->close();
?>