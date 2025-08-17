<?php



    include("../../../config/connection.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");
    include("../../../resources/PHPExcel-1.8/classes/PHPExcel.php");




    function cellColor($objPHPExcel,$cells,$color){
        $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                 'rgb' => $color
            )
        ));
    }

     $objExcel = new PHPExcel();
     $sheet = $objExcel->getActiveSheet();

     $sheet->setTitle('Costing Summary');

     $shipper = isset($_GET['shipper'])?escapeString(strtoupper(trim($_GET['shipper']))):'';
     $trackingnumber = isset($_GET['trackingnumber'])?escapeString(trim($_GET['trackingnumber'])):'';
     $bolnumber = isset($_GET['bolnumber'])?escapeString(trim($_GET['bolnumber'])):'';
     $billreference = isset($_GET['billreference'])?escapeString(trim($_GET['billreference'])):'';
     $insurancereference = isset($_GET['insurancereference'])?escapeString(trim($_GET['insurancereference'])):'';
     $datefrom = isset($_GET['datefrom'])?escapeString(trim($_GET['datefrom'])):'';
     $dateto = isset($_GET['dateto'])?escapeString(trim($_GET['dateto'])):'';

     if($datefrom!=''){
        $datefrom = date('Y-m-d', strtotime($datefrom));
     }
     if($dateto!=''){
        $dateto = date('Y-m-d', strtotime($dateto));
     }



      $arr = array();
      if(trim($shipper)!=''&&$shipper!='NULL'){
            array_push($arr, "txn_waybill.shipper_id='".$shipper."'");
      }

      if($trackingnumber!=''){
            array_push($arr, "txn_waybill.waybill_number='".$trackingnumber."'");
      }

      if($bolnumber!=''){
            array_push($arr, "txn_waybill.mawbl_bl='".$bolnumber."'");
      }

      if($billreference!=''){
            array_push($arr, "txn_waybill.bill_reference='".$billreference."'");
      }
      if($insurancereference!=''){
            array_push($arr, "txn_waybill.insurance_reference='".$insurancereference."'");
      }

      if($datefrom!=''&&$dateto!=''){
            array_push($arr,"date(txn_waybill.pickup_date) >= '$datefrom' and date(txn_waybill.pickup_date) <= '$dateto'");
      }
      else if($datefrom==''&&$dateto!=''){
            array_push($arr,"date(txn_waybill.pickup_date) <= '$dateto'");
      }
      else if($datefrom!=''&&$dateto==''){
             array_push($arr,"date(txn_waybill.pickup_date) >= '$datefrom'");
      }

      

        
      $condition = '';
      if(count($arr)>0){
            $condition = " where ".implode(" and ", $arr);
      }

     $qry =       " select txn_waybill.id,
                           txn_waybill.waybill_number,
                           txn_waybill.mawbl_bl,
                           txn_waybill.shipper_id,
                           txn_waybill.bill_reference,
                           txn_waybill.bill_item_number,
                           txn_waybill.insurance_reference,
                           txn_waybill.agent_cost,
                           txn_waybill.freight_cost,
                           txn_waybill.insurance_amount,
                           txn_waybill.package_freight,
                           txn_waybill.pickup_date,
                           txn_waybill.shipper_account_name,
                           txn_waybill.total_amount
                    from txn_waybill
                    $condition
                    order by txn_waybill.bill_item_number asc


                   ";


         $rs = mysql_query($qry);

         // HEADER
         $headercell = array(
                                'ITEM NO',
                                'TRACKING NO',
                                'PICKUP DATE',
                                'BILLING REFERENCE',
                                'INSURANCE REFERENCE',
                                'FREIGHT COST',
                                'INSURANCE AMOUNT',
                                'AGENT COST',
                                'SELLING PRICE',
                                'NET SALES',
                                'BL NO',
                                'CUSTOMER'
                            );
         $col = 'A';
         for($i=0;$i<count($headercell);$i++){
            $sheet->setCellValue($col.'1',$headercell[$i]);
                    cellColor($objExcel,$col.'1','bbdff1');
            $col++;
         }
         
         //DETAILS
         $row = 2;
         $line = 1;
         while($obj=mysql_fetch_object($rs)){
                $netsales = $obj->total_amount-($obj->freight_cost+$obj->agent_cost+$obj->insurance_amount);
                $detailcell = array(
                                        utfEncode($obj->bill_item_number),
                                        utfEncode($obj->waybill_number),
                                        dateFormat($obj->pickup_date, 'm/d/Y'),
                                        utfEncode($obj->bill_reference),
                                        utfEncode($obj->insurance_reference),
                                        utfEncode($obj->freight_cost),
                                        utfEncode($obj->insurance_amount),
                                        utfEncode($obj->agent_cost),
                                        utfEncode($obj->total_amount),
                                        utfEncode($netsales),
                                        utfEncode($obj->mawbl_bl),
                                        utfEncode($obj->shipper_account_name)
                                    );

                $col = 'A';
                for($i=0;$i<count($detailcell);$i++){
                    $sheet->setCellValue($col.$row,$detailcell[$i]);
                    $col++;
                }
               
                
                $line++;
                $row++;
         }

          header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          header('Content-Disposition: attachment; filename="waybill-costing-summary-'.date('Ymdhis').'.xlsx"');
          header('Cache-Control: max-age=0');

          $objWriter = PHPExcel_IOFactory::createWriter($objExcel,'Excel2007');
          $objWriter->save('php://output');

?>