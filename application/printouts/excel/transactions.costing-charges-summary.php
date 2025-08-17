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
    $writer->openToBrowser("costing-summary-".date('Ymd-hisA').".xlsx");

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
                    select costing.id,
                        costing.chart_of_accounts_id,
                        chart_of_accounts.description as chartofaccounts,
                        expense_type.description as expensetype,
                        costing.amount,
                        costing.reference,
                        costing.payee_id,
                        costing.payee_address,
                        payee.tin,
                        payee.payee_name,
                        costing.vatable_amount,
                        costing.is_vatable,
                        costing.vat_amount,
                        costing.date,
                        costing.prf_number,
                        date_format(costing.created_date,'%m/%d/%Y') as created_date,
                        costing.created_by,
                        date_format(costing.updated_date,'%m/%d/%Y') as updated_date,
                        costing.updated_by,
                        concat(cuser.first_name,' ',cuser.last_name) as createdby,
                        concat(uuser.first_name,' ',uuser.last_name) as updatedby,
                        group_concat(costing_waybill.waybill_number separator ', ') as waybills,
                        count(distinct costing_waybill.waybill_number) as waybillcount,
                        chart_of_accounts.type as producttype,
                        case when chart_of_accounts.type='GOODS' then costing.vat_amount
                        else '' end as vatgoods,
                        case when chart_of_accounts.type='SERVICES' then costing.vat_amount
                        else '' end as vatservices
                    from costing
                    left join payee on payee.id=costing.payee_id
                    left join user as cuser on cuser.id=costing.created_by
                    left join user as uuser on uuser.id=costing.updated_by
                    left join chart_of_accounts on chart_of_accounts.id=costing.chart_of_accounts_id
                    left join expense_type on expense_type.id=chart_of_accounts.expense_type_id
                    left join costing_waybill on costing_waybill.costing_id=costing.id
    ";

    $filter = array();
	$prfnumber = isset($_GET['prfnumber'])?escapeString(strtoupper($_GET['prfnumber'])):'';
    $expensetype = isset($_GET['expensetype'])?escapeString(strtoupper($_GET['expensetype'])):'';
    $chartofaccounts = isset($_GET['chartofaccounts'])?escapeString(strtoupper($_GET['chartofaccounts'])):'';
    $payee = isset($_GET['payee'])?escapeString(strtoupper($_GET['payee'])):'';
    $reference = isset($_GET['reference'])?escapeString($_GET['reference']):'';
    $bolnumber = isset($_GET['bolnumber'])?escapeString($_GET['bolnumber']):'';

    $docdatefrom = isset($_GET['costingdatefrom'])?escapeString($_GET['costingdatefrom']):'';
    $docdateto = isset($_GET['costingdateto'])?escapeString($_GET['costingdateto']):'';

    $createddatefrom = isset($_GET['createddatefrom'])?escapeString($_GET['createddatefrom']):'';
    $createddateto = isset($_GET['createddateto'])?escapeString($_GET['createddateto']):'';

    if($docdatefrom!=''){
        $docdatefrom = date('Y-m-d', strtotime($docdatefrom));
    }
    if($docdateto!=''){
        $docdateto = date('Y-m-d', strtotime($docdateto));
    }


    if($createddatefrom!=''){
        $createddatefrom = date('Y-m-d', strtotime($createddatefrom));
    }
    if($createddateto!=''){
        $createddateto = date('Y-m-d', strtotime($createddateto));
    }

    
      if(trim($expensetype)!=''&&strtoupper($expensetype)!='NULL'){
        array_push($filter, "chart_of_accounts.expense_type_id='".$expensetype."'");
      }

      if(trim($chartofaccounts)!=''&&strtoupper($chartofaccounts)!='NULL'){
        array_push($filter, "costing.chart_of_accounts_id='".$chartofaccounts."'");
      }

      if(trim($payee)!=''&&strtoupper($payee)!='NULL'){
        array_push($filter, "costing.payee_id='".$payee."'");
      }

      
      if(trim($bolnumber)!=''){
        array_push($filter, " costing.costing_waybill.waybill_number='".$bolnumber."'");
      }

      if(trim($prfnumber)!=''){
        array_push($filter, " costing.prf_number='".$prfnumber."'");
      }

      if(trim($reference)!=''){
        array_push($filter, " costing.reference='".$reference."'");
      }


      if($docdatefrom!=''&&$docdateto!=''){
          array_push($filter,"date(costing.date) >= '$docdatefrom' and date(costing.date) <= '$docdateto'");
      }
      else if($docdatefrom==''&&$docdateto!=''){
          array_push($filter,"date(costing.date) <= '$docdateto'");
      }
      else if($docdatefrom!=''&&$docdateto==''){
           array_push($filter,"date(costing.date) >= '$docdatefrom'");
      }

      
      if($createddatefrom!=''&&$createddateto!=''){
          array_push($filter,"date(costing.created_date) >= '$createddatefrom' and date(costing.created_date) <= '$createddateto'");
      }
      else if($createddatefrom==''&&$createddateto!=''){
          array_push($filter,"date(costing.created_date) <= '$createddateto'");
      }
      else if($createddatefrom!=''&&$createddateto==''){
           array_push($filter,"date(costing.created_date) >= '$createddatefrom'");
      }
      
    $searchSql = '';
    if(count($filter)>0){
    	$searchSql = ' where '.implode(" and ", $filter);
    }
    
    $sql = $customqry.$searchSql." group by costing.id order by costing.date, costing.prf_number asc";
    $rs = mysql_query($sql);

    //echo $sql;

    ///////////////////////// HEADER ///////////////////////////////////


    

    $header = array(
                        "DATE",
                        "EXPENSE TYPE",
                        "PAYEE/NAME",
                        "CHART OF ACCOUNTS",
                        "ADDRESS",
                        "TIN",
                        "GROSS AMOUNT",
                        "VATABLE AMOUNT",
                        "INPUT VAT: GOODS",
                        "INPUT VAT: SERVICES",
                        "REFERENCE",
                        "PRF NUMBER",
                        "NO. OF TRANSACTIONS",
                        "CREATED DATE",
                        "CREATED BY",
                        "UPDATED DATE",
                        "UPDATED BY"
    );

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
                                array('COSTING SUMMARY'),
                                $reporttitlestyle
                            );

    $writer->addRowWithStyle($header,$columnheaderstyle);



    ////////////////////// DETAILS ////////////////////////////

    
    while($obj=mysql_fetch_object($rs)){
        $rowdata = array(
                                utfEncode($obj->date),
                                utfEncode($obj->expensetype),
                                utfEncode($obj->payee_name),
                                utfEncode($obj->chartofaccounts),
                                utfEncode($obj->payee_address),
                                utfEncode($obj->tin),
                                utfEncode($obj->amount),
                                utfEncode($obj->vatable_amount),
                                utfEncode($obj->vatgoods),
                                utfEncode($obj->vatservices),
                                utfEncode($obj->reference),
                                utfEncode($obj->prf_number),
                                utfEncode($obj->waybillcount),
                                utfEncode($obj->created_date),
                                utfEncode($obj->createdby),
                                utfEncode($obj->updated_date),
                                utfEncode($obj->updatedby)
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