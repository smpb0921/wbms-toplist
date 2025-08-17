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
    $writer->openToBrowser("rts-report-".date('Ymd-hisA').".xlsx");

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
    $datefrom = isset($_GET['datefrom'])&&trim($_GET['datefrom'])!=''?escapeString($_GET['datefrom']):'';
    $dateto = isset($_GET['dateto'])&&trim($_GET['dateto'])!=''?escapeString($_GET['dateto']):'';
    $shipper = isset($_GET['shipper'])&&trim($_GET['shipper'])!=''?escapeString($_GET['shipper']):'';
   
    array_push($filter, "txn_waybill.status='RETURN TO SHIPPER'");
    array_push($filter, "txn_waybill.shipper_id='$shipper'");

    $datefrom = $datefrom!=''?date('Y-m-d', strtotime($datefrom)):$datefrom;
    $dateto = $dateto!=''?date('Y-m-d', strtotime($dateto)):$dateto;

    if($datefrom!=''&&$dateto!=''){
        array_push($filter,"date(txn_waybill_status_history.created_date) >= '$datefrom' and date(txn_waybill_status_history.created_date) <= '$dateto'");
    }
    else if($datefrom==''&&$dateto!=''){
        array_push($filter,"date(txn_waybill_status_history.created_date) <= '$dateto'");
    }
    else if($datefrom!=''&&$dateto==''){
         array_push($filter,"date(txn_waybill_status_history.created_date) >= '$datefrom'");
    }
    

	$searchSql = '';
    if(count($filter)>0){
    	$searchSql = ' where '.implode(" and ", $filter);
    }

    $shippername = '';
    $shipperaddress = '';
    $headersql = mysql_query("select * from shipper where id='$shipper'");
    while($obj=mysql_fetch_object($headersql)){
        $shippername = $obj->company_name;
        $shipperaddress = concatAddress(
            array($obj->company_street_address,
                  $obj->company_district,
                  $obj->company_city,
                  $obj->company_state_province,
                  $obj->company_zip_code,
                  $obj->company_country)
        );
    }

    $sql = "
                        select  txn_waybill.id,
                                txn_waybill.waybill_number,
                                date_format(txn_waybill.pickup_date,'%m/%d/%Y') as pickupdate,
                                txn_waybill.invoice_number,
                                txn_waybill.destination_id,
                                origin_destination_port.description as destination,
                                txn_waybill.shipper_company_name,
                                txn_waybill.remarks,
                                txn_waybill.consignee_company_name,
                                txn_waybill.status,
                                txn_waybill.shipment_description,
                                txn_waybill.mawbl_bl,
                                txn_waybill.last_status_update_remarks as rtsremarks,
                                txn_waybill_status_history.created_date as datestatusupdated
                        from txn_waybill
                        left join origin_destination_port on origin_destination_port.id=txn_waybill.destination_id
                        left join txn_waybill_status_history on txn_waybill_status_history.waybill_number=txn_waybill.waybill_number and 
                                                                txn_waybill_status_history.status_code=txn_waybill.status
                        $searchSql
                ";

  
    $rs = mysql_query($sql);

    

    ///////////////////////// HEADER ///////////////////////////////////
    $writer->addRowWithStyle(array(
        'Run Date & Time: '.date("M d, Y | h:i:s A")
    ),$rowxsstyle);


    $currentSheet = $writer->getCurrentSheet();
    $mergeRanges = ['A1:J1','A2:J2','B4:F4','B5:F5','B6:F6','A11:I11','A12:A13','B12:B13','C12:C13','D12:D13','E12:E13','F12:F13','G12:G13','H12:I12','J12:J13']; // or ['A1:A4','A1:E1']
    $currentSheet->setMergeRanges($mergeRanges);
    $writer->addRowWithStyle(
                                array('RTS Shipment Transmittal - OUTBOUND'),
                                $reporttitlestyle
                            );
    $writer->addRow(array(''));

    $writer->addRowWithStyle(array('Date',date('F d, Y')),$headerinfostyle);
    $writer->addRowWithStyle(array('Company Name',$shippername),$headerinfostyle);
    $writer->addRowWithStyle(array('Address',$shipperaddress),$headerinfostyle);

    $writer->addRow(array(''));
    $writer->addRowWithStyle(array('Attention:',''),$headerinfostyle);
    $writer->addRowWithStyle(array('Department:',''),$headerinfostyle);

    $writer->addRow(array(''));
    $writer->addRowWithStyle(array('We are submitting the return (RTS ) of your  shipments, attached the Original proof with corresponding reasons.'),$rowstyle);

    $header = array(
                        "Date Pick Up",
                        "Customer",
                        "BOL",
                        "Reference Number",
                        "Destination",
                        "Consignee Company/Name",
                        "Description",
                        "Status",
                        "",
                        "Remarks"
    );


    $writer->addRowWithStyle($header,$columnheaderstyle);

    $header = array(
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "",
                        "1st Attempt",
                        "2nd Attempt",
                        ''
    );

    $writer->addRowWithStyle($header,$columnheaderstyle);



    ////////////////////// DETAILS ////////////////////////////

    
    while($obj=mysql_fetch_object($rs)){
        $rowdata = array(
                      
                        utfEncode($obj->pickupdate),
                        utfEncode($obj->shipper_company_name),
                        utfEncode($obj->mawbl_bl),
                        utfEncode($obj->waybill_number),
                        utfEncode($obj->destination),
                        utfEncode($obj->consignee_company_name),
                        utfEncode($obj->shipment_description),
                        utfEncode($obj->rtsremarks),
                        '',
                        utfEncode('RTS')
                   );
        $writer->addRowWithStyle($rowdata,$rowdatastyle);
    }
    $writer->addRow(array(''));
    $writer->addRowWithStyle(array('Please call us anytime at 772-9000 or 844-7583/84'),$rowstyle);

    $writer->addRow(array(''));
    $writer->addRowWithStyle(
                                array(
                                        'Prepared by:',
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
                'Date Effective: June 8, 2020',
                '',
                '',
                '',
                '',
                'QF-SAM-16 Issue #2 rev.01'
        ),
        $rowstyle
    );


    $writer->close();
?>