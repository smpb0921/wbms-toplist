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
    $writer->openToBrowser("mmda-report-".date('Ymd-hisA').".xlsx");

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
    

    $filter = array();
    $docdate = isset($_GET['docdate'])&&trim($_GET['docdate'])!=''?escapeString($_GET['docdate']):'';
   
    array_push($filter, "txn_manifest.status!='LOGGED' and txn_manifest.status!='VOID' and txn_manifest.status!='CANCELLED'");

    if($docdate!=''){
        $docdate = date('Y-m-d', strtotime($docdate));
        array_push($filter, "date(txn_manifest.document_date)='$docdate'");
    }

	$searchSql = '';
    if(count($filter)>0){
    	$searchSql = ' where '.implode(" and ", $filter);
    }

    $sql = "
                        select tbl.manifest_number,
                               tbl.waybill_number,
                               tbl.pouch_size_id,
                               tbl.manifest_number,
                               tbl.driver_name,
                               tbl.bolcity,
                               count(distinct tbl.waybill_number) as totalscheduledcount,
                               sum(tbl.withattemptcount) as withattemptcount,
                               sum(tbl.withoutattemptcount) as withoutattemptcount,
                               sum(tbl.newdelcount) as newdelcount,
                               tbl.remarks
                        from (
                                select  group_concat(distinct txn_manifest_waybill.waybill_number) as waybill_number,
                                        txn_manifest_waybill.pouch_size_id,
                                        txn_manifest.manifest_number,
                                        txn_manifest.driver_name,
                                        txn_waybill.consignee_city as bolcity,
                                        '' as remarks,
                                        if(withattempt.status_description is null and withoutattempt.status_description is null,1,0) as newdelcount,
                                        if(withattempt.status_description is not null,1,0) as withattemptcount,
                                        if(withoutattempt.status_description is not null,1,0) as withoutattemptcount
                                from txn_manifest_waybill
                                left join txn_manifest on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number
                                left join txn_waybill on txn_waybill.waybill_number=txn_manifest_waybill.waybill_number
                                left join txn_waybill_status_history as withattempt on withattempt.waybill_number=txn_manifest_waybill.waybill_number and withattempt.status_description='PENDING WITH ATTEMPT'
                                left join txn_waybill_status_history as withoutattempt on withoutattempt.waybill_number=txn_manifest_waybill.waybill_number and withoutattempt.status_description='PENDING WITHOUT ATTEMPT'
                                $searchSql
                                group by txn_manifest_waybill.waybill_number
                        ) as tbl 
                        group by tbl.driver_name, tbl.bolcity
                        order by tbl.driver_name, tbl.bolcity asc
                ";

    
    $rs = mysql_query($sql);

    

    ///////////////////////// HEADER ///////////////////////////////////
    $writer->addRowWithStyle(array(
        'Run Date & Time: '.date("M d, Y | h:i:s A")
    ),$rowxsstyle);


    $currentSheet = $writer->getCurrentSheet();
    $mergeRanges = ['A2:G2']; // or ['A1:A4','A1:E1']
    $currentSheet->setMergeRanges($mergeRanges);
    $writer->addRowWithStyle(
                                array('METRO MANILA DAILY ACTIVITY'),
                                $reporttitlestyle
                            );

    $header = array(
                        "Name",
                        "Area",
                        "New Deliveries",
                        "Pending With Attempt",
                        "Pending W/O Attempt",
                        "Total Scheduled Deliveries",
                        "Remarks"
    );


    $writer->addRowWithStyle($header,$columnheaderstyle);



    ////////////////////// DETAILS ////////////////////////////

    
    while($obj=mysql_fetch_object($rs)){
        $rowdata = array(
                      
                        utfEncode($obj->driver_name),
                        utfEncode($obj->bolcity),
                        utfEncode($obj->newdelcount),
                        utfEncode($obj->withattemptcount),
                        utfEncode($obj->withoutattemptcount),
                        utfEncode($obj->totalscheduledcount),
                        utfEncode($obj->remarks)
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
                                        'Received by:'
                                ),
                                $rowheaderstyle
    );
    $writer->addRow(array(''));
    $writer->addRow(array(''));
    $writer->addRowWithStyle(
        array(
                'Courier Dispatching Head:',
                '',
                '',
                '',
                '',
                'Courier Supervisor:'
        ),
        $rowheaderstyle
    );
    $writer->addRow(array(''));
    $writer->addRowWithStyle(
        array(
                'Date Effective: Februaty 12, 2018',
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