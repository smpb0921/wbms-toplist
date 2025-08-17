<?php
    include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");

    $whse = isset($_GET['whse'])?trim($_GET['whse']," "):'1';
    $loc = isset($_GET['location'])?trim($_GET['location']," "):'';
    $lot = isset($_GET['lot'])?strtoupper(trim($_GET['lot']," ")):'';
    $unit = isset($_GET['unit'])?trim($_GET['unit']," "):'';
    $sku = isset($_GET['sku'])?trim($_GET['sku']," "):'';
    $expiry = isset($_GET['expiry'])?trim($_GET['expiry']," "):'';
    $name = isset($_GET['name'])?trim($_GET['name']," "):'';
    $grouping =isset($_GET['groupby'])?trim($_GET['groupby']," "):'';
    $wherearr = array();
    $grouparr = array();

    $where = "where warehouse_id='$whse' and inv.stockqty>0 ";

    $groupby = "group by warehouse_id,item_id,uom_id";
    if(strtoupper($grouping)!='NULL'&&$grouping!=''){
        $groupby = $groupby.",".$grouping;
    }

    $query = "select itm_name,
                     itm_reference,
                     item_id,
                     uom_code,
                     uom_id,
                     sum(stockqty) as totalqty,
                     lot_number,
                     expiry_date,
                     location_id,
                     loc_code,
                     loc_description,
                     warehouse_name,
                     sum(totalreservedqty) as totalreservedquantity,
                     sum(totalpickedqty) as totalpickedquantity,
                     on_hold,
                     classification
              from                  
                    (select a.warehouse_id, 
                             warehouse_name, 
                             a.location_id, 
                             loc_code, 
                             loc_description,
                             a.lot_number, 
                             a.expiry_date, 
                             itm_name, 
                             itm_reference,
                             a.item_id, 
                             a.uom_id,
                             uom_code,
                             stockqty, 
                             
                             reserved.totalreservedqty,
                             reserved.totalpickedqty,
                             a.on_hold,
                             classification

                    FROM
                            (select warehouse_id, 
                                    location_id, 
                                    lot_number, 
                                    item_id, 
                                    uom_id,
                                    expiry_date, 
                                    sum(quantity) as stockqty,
                                    on_hold,
                                    classification
                              FROM `physical_inventory`
                              group by warehouse_id, location_id, lot_number, expiry_date, item_id, uom_id) as a
                   left join (select picklist_reservation.status,
                                picklist_reservation.item_id,
                                picklist_reservation.location_id,
                                picklist_reservation.lot_number,
                                picklist_reservation.expiry_date,
                                picklist_reservation.uom_id,
                                sum(picklist_reservation.reserved_quantity) as totalreservedqty,
                                sum(picklist_reservation.picked_quantity) as totalpickedqty
                          from picklist_reservation
                          group by picklist_reservation.item_id, 
                                    picklist_reservation.uom_id, 
                                    picklist_reservation.location_id, 
                                    picklist_reservation.lot_number,
                                    picklist_reservation.expiry_date) as reserved
                  on a.item_id=reserved.item_id and
                     a.uom_id=reserved.uom_id and
                     a.location_id=reserved.location_id and
                     a.lot_number=reserved.lot_number and
                     (
                       a.expiry_date=reserved.expiry_date or 
                      (a.expiry_date='0000-00-00' and reserved.expiry_date='0000-00-00')
                     )
                   
                    inner join warehouse on warehouse.id=a.warehouse_id
                    inner join item on item.id=a.item_id
                    inner JOIN location on location.id=a.location_id
                    inner join unit_of_measure on unit_of_measure.id=a.uom_id) as inv ";
 
    if($loc!=''&&strtoupper($loc)!='NULL'){
        array_push($wherearr, "location_id=".$loc);
    }
    if($lot!=''&&$lot!='NULL'){
        array_push($wherearr, "lot_number='".$lot."'");
    }
    if(trim($sku)!=''){ 
        array_push($wherearr, "itm_reference='".$sku."'");
    }
    if($name!=''){
        array_push($wherearr, "itm_name regexp '".$name."'");
    }
    if($unit!=''&&$unit!='NULL'){
        array_push($wherearr, "uom_id = '".$unit."'");
    }

 
    $condition = implode(" and ", $wherearr);
    if(trim($condition)!=''){
        $where = $where."and ".$condition." ";
    }
    
    $query = $query.$where.$groupby." order by itm_name, uom_code, loc_code, lot_number asc";
    
    $rs = query($query); 
    $arrx = array();
    $line = 1;
    while($obj = fetch($rs)){
        $itemcode = $obj->itm_reference;
        $itemid=$obj->item_id;
        $name = utf8_encode($obj->itm_name);
        $uomid = $obj->uom_id;
        $uom = $obj->uom_code;
        $qty = convertWithDecimal($obj->totalqty,5);
        $whse = $obj->warehouse_name;
        $loc = $obj->loc_code;
        $locdesc = $obj->loc_description;
        $disposition = $obj->classification;
        $lot = $obj->lot_number;
        $expirydate = ($obj->expiry_date==NULL||$obj->expiry_date=='0000-00-00')?'N/A':dateFormat($obj->expiry_date,'m/d/Y');

        $reserved = $obj->totalreservedquantity-$obj->totalpickedquantity;
        $available = $obj->totalqty-$reserved;
 
        $availablecaseqty = getCaseLoose($itemid,$uomid,$uom,$available);
        
        $reserved = convertWithDecimal($reserved,5);
        $available = convertWithDecimal($available,5);

        $onhold = $obj->on_hold==1?'YES':'NO';
        array_push($arrx,array(
                        "ItemCode"=>$itemcode,
                        "ItemDescription"=>$name,
                        "Qty"=>$availablecaseqty  
        )); 
    }
echo json_encode($arrx);