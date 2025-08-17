<?php

    include("../../../config/connection.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");
    require '../../../resources/spout/vendor/box/spout/src/Spout/Autoloader/autoload.php';
    use Box\Spout\Writer\WriterFactory;
    use Box\Spout\Common\Type;

    $writer = WriterFactory::create(Type::XLSX);
    $writer->openToFile("waybills-in-booking-".date('Ymd-hisA').".xlsx");
    $writer->openToBrowser("waybills-in-booking-".date('Ymd-hisA').".xlsx");
    $header = array(
                        "Booking Number",
                        "BOL/Tracking No.",
                        "MAWBL",
						'Billing Status',
                        "Shipper",
                        "Status",
                        "Pickup Date",
                        "Created Date",
                        "Created By"
    );
    $writer->addRow($header);



    //QUERY 
    $customqry = "
                    select txn_waybill.id,
                            txn_waybill.waybill_number,
                            txn_waybill.booking_number,
                            txn_waybill.pickup_date,
                            txn_waybill.status,
                            txn_waybill.mawbl_bl,
                            txn_waybill.pickup_city,
                            txn_waybill.pickup_state_province,
                            txn_waybill.manifest_number,
                            txn_waybill.invoice_number,
                            txn_waybill.package_chargeable_weight,
                            txn_waybill.package_actual_weight,
                            txn_waybill.reference,
							case 
								when txn_waybill.billed_flag=1 then 'BILLED'
								else 'UNBILLED'
							end as billingflag,
                            shipper.account_name as shipper,
                            consignee.account_name as consignee,
                            origintbl.description as origin,
                            destinationtbl.description as destination,
                            destinationroutetbl.description as destinationroute,
                            concat(first_name,' ',last_name) as createdby,
                            date_format(txn_waybill.created_date,'%m/%d/%Y %h:%i:%s %p') as created_date,
                            date_format(txn_waybill.pickup_date,'%m/%d/%Y') as pickupdate
                        from txn_waybill
                        left join shipper on shipper.id=txn_waybill.shipper_id
                        left join consignee on consignee.id=txn_waybill.consignee_id
                        left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id
                        left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id
                        left join destination_route as destinationroutetbl on destinationroutetbl.id=txn_waybill.destination_route_id
                        left join user on user.id=txn_waybill.created_by";

    $filter = array();
	$bookingnumber = '';
	if(isset($_GET['bookingnumber'])&&$_GET['bookingnumber']!=''){
		$bookingnumber = explode(',',$_GET['bookingnumber']);
		$bookingnumber = implode("','",$bookingnumber);
	}
    array_push($filter, "upper(txn_waybill.booking_number) in ('".$bookingnumber."')");
    if(count($filter)>0){
    	$searchSql = ' where '.implode(" and ", $filter);
    }

    $sql = $customqry.$searchSql." order by txn_waybill.booking_number, txn_waybill.waybill_number asc";

    $rs = mysql_query($sql);
    while($obj=mysql_fetch_object($rs)){
        $rowdata = array(
                      
                        utfEncode($obj->booking_number),
                        utfEncode($obj->waybill_number),
                        utfEncode($obj->mawbl_bl),
						utfEncode($obj->billingflag),
                        utfEncode($obj->shipper),
                        utfEncode($obj->status),
                        utfEncode($obj->pickupdate),
                        utfEncode($obj->created_date),
                        utfEncode($obj->createdby)
                   );
        $writer->addRow($rowdata);
    }

    $writer->close();
?>