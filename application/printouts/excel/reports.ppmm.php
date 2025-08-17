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
    $writer->openToBrowser("ppmm-report-".date('Ymd-hisA').".xlsx");

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
    $driver = isset($_GET['driver'])&&trim($_GET['driver'])!=''?escapeString($_GET['driver']):'';

   
   
    $deliveryfilter = array();
    array_push($deliveryfilter, "txn_waybill.status='DELIVERED'");

    $pickupfilter = array();
    array_push($pickupfilter, "txn_booking.status!='VOID' and txn_booking.status!='LOGGED'");


    if($datefrom!=''&&$dateto==''){
        $datefrom = date('Y-m-d', strtotime($datefrom));
        array_push($deliveryfilter, "date(txn_waybill.received_date)>='$datefrom'");
        array_push($pickupfilter, "date(txn_booking.actual_pickup_date)>='$datefrom'");
    }
    else if($datefrom==''&&$dateto!=''){
        $dateto = date('Y-m-d', strtotime($dateto));
        array_push($deliveryfilter, "date(txn_waybill.received_date)<='$dateto'");
        array_push($pickupfilter, "date(txn_booking.actual_pickup_date)<='$dateto'");
    }
    else if($datefrom!=''&&$dateto!=''){
        $datefrom = date('Y-m-d', strtotime($datefrom));
        $dateto = date('Y-m-d', strtotime($dateto));
        array_push($deliveryfilter,"date(txn_waybill.received_date) >= '$datefrom' and date(txn_waybill.received_date) <= '$dateto'");
        array_push($pickupfilter,"date(txn_booking.actual_pickup_date) >= '$datefrom' and date(txn_booking.actual_pickup_date) <= '$dateto'");
    }

    if($driver!=''&&strtoupper($driver)!='NULL'){
        array_push($filter, "trim(tbl.driver_name) like '%$driver%'");
    }

	$searchDeliverySql = '';
    if(count($deliveryfilter)>0){
    	$searchDeliverySql = ' where '.implode(" and ", $deliveryfilter);
    }

    $searchPickupSql = '';
    if(count($pickupfilter)>0){
    	$searchPickupSql = ' where '.implode(" and ", $pickupfilter);
    }

    $searchSql = '';
    if(count($filter)>0){
    	$searchSql = ' where '.implode(" and ", $filter);
    }

    

    $sql = "
                select tbl.driver_name,
                       tbl.city,
                       ifnull(deltbl.parcelcount,0) as parcelcount,
                       ifnull(deltbl.documentcount,0) as documentcount,
                       ifnull(pickuptbl.mmcount,0) as mmcount,
                       ifnull(pickuptbl.domesticcount,0) as domesticcount,
                       ifnull(pickuptbl.ipcount,0) as ipcount
                from (
                        select * 
                        from (
                                select  concat(personnel.first_name,' ',personnel.last_name) as driver_name,
                                        txn_waybill.consignee_city as city
                                from txn_waybill 
                                inner join txn_waybill_status_history on txn_waybill_status_history.waybill_number=txn_waybill.waybill_number and 
								                                        txn_waybill_status_history.status_description='DELIVERED'
								inner join personnel on personnel.id=txn_waybill_status_history.personnel_id

                                union 

                                select  driver as driver_name,
                                        shipper_pickup_city as city
                                from txn_booking  
                        ) as innerbtl 
                        group by driver_name, city
                        order by driver_name, city asc
                ) as tbl
                left join (
                                select concat(personnel.first_name,' ',personnel.last_name) as driver_name,
                                    txn_waybill.consignee_city,
                                    SUM(CASE WHEN txn_waybill.waybill_type='PARCEL' THEN 1 ELSE 0 END) as parcelcount,
                                    SUM(CASE WHEN txn_waybill.waybill_type='DOCUMENT' THEN 1 ELSE 0 END) as documentcount
                                from txn_waybill 
                                inner join txn_waybill_status_history on txn_waybill_status_history.waybill_number=txn_waybill.waybill_number and 
								                                        txn_waybill_status_history.status_description='DELIVERED'
								inner join personnel on personnel.id=txn_waybill_status_history.personnel_id
                                $searchDeliverySql
                                group by concat(personnel.first_name,' ',personnel.last_name), txn_waybill.consignee_city
                          ) as deltbl on deltbl.driver_name=tbl.driver_name and 
                                         deltbl.consignee_city=tbl.city
                left join (
                                select  driver as driver_name,
                                        shipper_pickup_city,
                                        SUM(CASE WHEN txn_booking.shipper_pickup_state_province='METRO MANILA' THEN 1 ELSE 0 END) as mmcount,
                                        SUM(CASE WHEN txn_booking.shipper_pickup_state_province!='METRO MANILA' and txn_booking.shipper_pickup_country='Philippines' THEN 1 ELSE 0 END) as domesticcount,
                                        SUM(CASE WHEN txn_booking.shipper_pickup_state_province!='METRO MANILA' and txn_booking.shipper_pickup_country!='Philippines' THEN 1 ELSE 0 END) as ipcount
                                from txn_booking
                                $searchPickupSql
                                group by txn_booking.driver, txn_booking.shipper_pickup_city  
                                
                          ) as pickuptbl on pickuptbl.driver_name=tbl.driver_name and 
                                            pickuptbl.shipper_pickup_city=tbl.city
                $searchSql
				having parcelcount>0 or documentcount>0 or mmcount>0 or domesticcount>0 or ipcount>0



            
                        

    ";

    //echo $sql;

  
    $rs = mysql_query($sql);

    

    ///////////////////////// HEADER ///////////////////////////////////
    $writer->addRowWithStyle(array(
        'Run Date & Time: '.date("M d, Y | h:i:s A")
    ),$rowxsstyle);


    $currentSheet = $writer->getCurrentSheet();
    $mergeRanges = ['A1:G1','A2:G2','C4:D4','E4:G4','A4:A5','B4:B5']; // or ['A1:A4','A1:E1']
    $currentSheet->setMergeRanges($mergeRanges);
    $writer->addRowWithStyle(
                                array('Process Performance Monitoring and Measurement'),
                                $reporttitlestyle
                            );


    $writer->addRow(array(''));

    $header = array(
                        "NAME",
                        "AREA",
                        "NO. OF DELIVERED ITEMS",
                        "",
                        "TOTAL PICKED ITEMS",
                        "",
                        ""
    );


    $writer->addRowWithStyle($header,$columnheaderstyle);

    $header = array(
                        "",
                        "",
                        "DOCUMENT",
                        "PARCEL",
                        "MM",
                        "DOMESTIC",
                        "IP"
    );

    $writer->addRowWithStyle($header,$columnheaderstyle);



    ////////////////////// DETAILS ////////////////////////////

    
    while($obj=mysql_fetch_object($rs)){
        $rowdata = array(
                      
                        utfEncode($obj->driver_name),
                        utfEncode($obj->city),
                        utfEncode($obj->parcelcount),
                        utfEncode($obj->documentcount),
                        utfEncode($obj->mmcount),
                        utfEncode($obj->domesticcount),
                        utfEncode($obj->ipcount)
                   );
        $writer->addRowWithStyle($rowdata,$rowdatastyle);
    }
    $writer->addRow(array(''));
    

    $writer->close();
?>