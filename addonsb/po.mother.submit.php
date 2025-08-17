<?php
    require_once "conn - Copy.php"; 
    if(isset($_POST["PO"])){  
        $pos = explode("|",substr($_POST["PO"],0,-1));
		$id = $_POST["ID"];
        $txn = $conn->query("select concat(code,lpad(next_number_series,8,0)) as txn from transaction_type where id=5;")->fetch_object()->txn;
        $do = $conn->query("(select date_ordered from purchase_order_header where order_number='$pos[0]')")->fetch_object()->date_ordered; 
        $dd = $conn->query("(select delivery_date from purchase_order_header where order_number='$pos[0]')")->fetch_object()->delivery_date;
        $ref = implode(",",$pos); 
        echo $txn;
        $conn->query("insert into purchase_order_header(order_number,order_status,supplier_id,date_ordered,delivery_date,ship_to_warehouse,created_date,created_by,remarks)".
        "values('$txn','RELEASED',1,'$do','$dd',1,now(),$id,'PO Consolidated: $ref')") or die($conn->error." Failed at Inserting Header");
        $condition = array();
        foreach($pos as $po){
            array_push($condition," order_number='$po' ");
        }
        $condition = implode("or",$condition);
        $conn->query("update purchase_order_header set order_status='CLOSED',remarks=if(length(remarks)>0,concat(remarks,'\r\n','Consolidated Into $txn'),'Consolidated Into $txn') where $condition ") or die($conn->error." Failed at Updating Consolidated PO");
        $toinsert = array(); 
        $rs = $conn->query("select order_number,item_id,ifnull(item_cost,0) as cost,sum(item_qty) as qty,ifnull(item_discount,0) as discount,ifnull(uom_id,1) as uom,line_number,sum(item_amount) as total from purchase_order_details where $condition group by item_id") or die($conn->error." Failed at Updating Details");
        while( $row = $rs->fetch_object()){ 
            array_push($toinsert,"('$txn',$row->item_id,$row->cost,$row->qty,$row->discount,$row->uom,null,$row->total)");
        }
        $conn->query("insert into purchase_order_details(order_number,item_id,item_cost,item_qty,item_discount,uom_id,line_number,item_amount) values ".implode(",",$toinsert)) or die($conn->error." Failed In Inserting Consolidated PO Details");
        $conn->query("update transaction_type set next_number_series=next_number_series+1 where id=5") or die($conn->error." Failed");
    }