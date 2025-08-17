<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
    <div class='header-page-inner'>
        Booking BOLs Summary
    </div>
</div>
<div class="container-fluid">
    <div class="pageContent">
        <div class="panel panel-primary mypanel">
            <div class="panel-body">
                <br>
                <!-- CONTENT -->
                <div class="form-horizontal">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Booking Number</label>
                            <div class="col-lg-8">
                                <select class='form-control bookingbols-bookingnumber bookingnumberdropdownselect' multiple>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class='col-md-12'>
                        <br>
                    </div>
                    
                </div>
                <div class='row'>
                    <div class='col-md-12'>
                        <div class='table-sm'>
                        	<table id='bookingbols-table'>
                        		<tbody></tbody>
                        	</table>
                        </div>
                    </div>
                </div>
                
                <!-- CONTENT-END -->
                
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){

        var tabBKBOL = "#bookingbols-menutabpane";
        $(tabBKBOL+" .select2").select2();
        var datetoday = new Date();
        $(tabBKBOL+' .datepicker').datepicker();


        $(tabBKBOL+" .bookingnumberdropdownselect").select2({
            ajax: {
                url: "loadables/dropdown/booking-number-used-in-waybill.php",
                dataType: 'json',
                delay: 100,
                data: function (params) {
                    return {
                                q: params.term // search term
                            };
                },
                processResults: function (data) {
                    return {
                                results: data
                            };
                },
                cache: true
            },
            minimumInputLength: 0,
            width: '100%'
        });

        $(tabBKBOL+' #bookingbols-table').flexigrid({
				url: 'loadables/ajax/reports.booking-waybills.php',
				dataType: 'json',
				colModel : [
						{display: 'SystemID', name : 'txn_waybill.id', width : 80, hide:true, sortable : true, align: 'left'},
                        {display: 'Booking No.', name : 'txn_booking.booking_number', width : 150, sortable : true, align: 'left'},
                    
						{display: 'BOL / Tracking No.', name : 'txn_waybill.waybill_number', width : 150, sortable : true, align: 'left'},
                        {display: 'MAWBL', name : 'txn_waybill.mawbl_bl', width : 150, sortable : true, align: 'left'},
						{display: 'Billing Status', name : 'billingstatus', width : 150, sortable : true, align: 'left'},
                        {display: 'Shipper', name : 'shipper.account_name', width : 250, sortable : true, align: 'left'},
                        {display: 'Status', name : 'txn_waybill.status', width : 150, sortable : true, align: 'left'},
                        {display: 'Pickup Date', name : 'pickupdate', width : 150, sortable : true, align: 'left'},
						{display: 'Created Date', name : 'costing.created_date', width : 145, hide:false, sortable : true, align: 'left'},
						{display: 'Created By', name : 'createdby', width : 150, hide:false, sortable : true, align: 'left'}
				],
				buttons : [

						{separator: true},
						{name: 'Download', bclass: 'download downloadbookingbolreport', onpress : ''},

						
				],
				searchitems : [
						{display: 'BOL / Tracking No.', name : 'txn_waybill.waybill_number', isdefault: true},
						{display: 'MAWBL', name : 'txn_waybill.mawbl_bl'},
						{display: 'Shipper', name : 'shipper.account_name'},
						{display: 'Billing Status', name : 'billingstatus'}
				],
				sortname: "txn_waybill.waybill_number",
				sortorder: "asc",
				usepager: true,
				title: "",
				useRp: true,
				rp: 10, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 350,
				singleSelect: false,
				disableSelect: true
		});

       

     });
</script>