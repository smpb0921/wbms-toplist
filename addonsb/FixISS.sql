set sql_safe_updates=0,foreign_key_checks=0;
update sales_order_details set served_qty=0 where order_number = (select so_number from picklist_sales_order where picklist_number='PKL00001907');
update sales_order_header set order_status='RELEASED' where  order_number = (select so_number from picklist_sales_order where picklist_number='PKL00001907');
update picklist_header set status='LOGGED' WHERE picklist_number='PKL00001907';
delete from issuance_staging where picklist_number='PKL00001907';
delete from issuance_summary_details where issuance_number = (select issuance_number from issuance_header where picklist_so_number='PKL00001907');
delete from issuance_header where picklist_so_number='PKL00001907';
delete from issuance_staging_details where picklist_number='PKL00001907';
set sql_safe_updates=1,foreign_key_checks=1;