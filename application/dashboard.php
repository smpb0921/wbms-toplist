<?php
    include('../config/connection.php');
    include("../config/checkurlaccess.php");
    include('../config/dashboard-functions.php');

?>
<div class='header-page' >
	<div class='header-page-inner'>
		Dashboard
	</div>
</div>
<div class="container-fluid">
    <div class="pageContent no-border">

    	<div class='col-md-9'>
    		<div class='row'>


    			<div class='tabpane-white margin-top-20 margin-bottom-10'>
    				
    				<ul class="nav nav-tabs">
    					<li role="presentation" class="active" data-pane='#dashboard-shipperperformancepane' id='dashboard-shipperperformancetab'><a href="#">Shipper Performance</a></li>
    				</ul>
    				<div class='tab-panes'>
    					<div class='pane active' id='dashboard-shipperperformancepane'>
    							<div class='form-horizontal'>
			    					<div class="form-group">
			    						<label class='control-label col-md-1'>Date From</label>
			    						<div class="col-md-2">
			    							<input type='text' class='form-control datepicker dashboard-shipperdatefrom' >
			    						</div>

			    						<label class='control-label col-md-1'>Date To</label>
			    						<div class="col-md-2">
			    							<input type='text' class='form-control datepicker dashboard-shipperdateto' >
			    						</div>
			    					</div>
			    				</div>
			    				<br>
			    				<div id='dash-shipperperformancepanel'>
							    	<table id='dash-shipperperformancetbl'>
										<tbody></tbody>

									</table>
								</div>
    					</div>
    				</div>
    			</div>

    			<br>

    			<div class='tabpane-white margin-top-20 margin-bottom-10'>
    				<ul class="nav nav-tabs">
    					<li role="presentation" class="active" data-pane='#dashboard-bookingpane' id='dashboard-bookingtab'><a href="#">Booking</a></li>
    					<li role="presentation" data-pane='#dashboard-bookingapprovalpane' id='dashboard-bookingapprovaltab'><a href="#">Booking Approval</a></li>
    					<li role="presentation" data-pane='#dashboard-waybillpane' id='dashboard-waybilltab'><a href="#">Waybill</a></li>
    					<li role="presentation" data-pane='#dashboard-loadplanpane' id='dashboard-loadplantab'><a href="#">Load Plan</a></li>
    					<li role="presentation" data-pane='#dashboard-manifestpane' id='dashboard-manifesttab'><a href="#">Manifest</a></li>
    					<li role="presentation" data-pane='#dashboard-ar-taggingpane' id='dashboard-ar-taggingtab'><a href="#">A/R Tagging</a></li>
    				</ul>
    				<div class='tab-panes'>
    					<div class='pane active' id='dashboard-bookingpane'>
    						<div id='dash-bookingpanel'>
						    	<table id='dash-bookingpaneltbl'>
									<tbody></tbody>

								</table>
							</div>
    					</div>
    					<div class='pane' id='dashboard-bookingapprovalpane'>
    						<div id='dash-bookingapprovalpanel'>
						    	<table id='dash-bookingapprovalpaneltbl'>
									<tbody></tbody>

								</table>
							</div>
    					</div>
    					<div class='pane' id='dashboard-waybillpane'>
    						<div id='dash-waybillpanel'>
						    	<table id='dash-waybillpaneltbl'>
									<tbody></tbody>

								</table>
							</div>
    					</div>
    					<div class='pane' id='dashboard-loadplanpane'>
    						<div id='dash-loadplanpanel'>
						    	<table id='dash-loadplanpaneltbl'>
									<tbody></tbody> 
								</table>
							</div>
    					</div>
    					<div class='pane' id='dashboard-manifestpane'>
    						<div id='dash-manifestpanel'>
						    	<table id='dash-manifestpaneltbl'>
									<tbody></tbody>

								</table>
							</div>
    					</div>
						
    					<div class='pane' id='dashboard-ar-taggingpane'>
    						<div id='dash-ar-tagging-panel'>
						    	<table id='dash-ar-tagging-tbl'>
									<tbody></tbody>

								</table>
							</div>
    					</div>
    				</div>
    			</div>

		    	
		    	
		    	
				
			

			</div>
		</div>

		<div class='col-md-3'>
			<div class='row'>
				<div class='wells'>
					<br><br><br>
				</div>
			</div>
		</div>
    

	</div>  
</div>
 
<div class="modal" id="modal-receive-billing" tabindex="-1" role="dialog" aria-labelledby="modal-receive-billing-title" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="modal-receive-billing-title">Receive Billing</h4>
			</div>
 			<div class="modal-body">
			 	<div class="modal-errordiv"> 
				</div>
				<input type="text" placeholder="Received Date" class="form-control datepicker billing-received-date">
				<input type="text" placeholder="Received By" class="form-control " id="billing-received-by"/>
			</div>
			<div class="modal-footer"> 
				<button type="button" class="btn btn-blue2 mybtn">Receive</button>
			</div>
		</div>
	</div>
</div>
  
<script type="text/javascript">
	/**** INITIALIZED *******/
	$(document).ready(function(){
			var dashpane = '#dashtabpane';
			$(dashpane+' .datepicker').datepicker();

			$(document).off('change',dashpane+' .dashboard-shipperdatefrom').on('change',dashpane+' .dashboard-shipperdatefrom',function(){
				var datefrom = $(this).val();
				var dateto = $(dashpane+' .dashboard-shipperdateto').val();

				$(dashpane+' #dash-shipperperformancetbl').flexOptions({
											url:'loadables/ajax/dashboard.shipper-performance.php?datefrom='+datefrom+'&dateto='+dateto,
											sortname: 'totaltransactions',
											sortorder: "desc"
				}).flexReload();
			});

			$(document).off('change',dashpane+' .dashboard-shipperdateto').on('change',dashpane+' .dashboard-shipperdateto',function(){
				var dateto = $(this).val();
				var datefrom = $(dashpane+' .dashboard-shipperdatefrom').val();

				$(dashpane+' #dash-shipperperformancetbl').flexOptions({
											url:'loadables/ajax/dashboard.shipper-performance.php?datefrom='+datefrom+'&dateto='+dateto,
											sortname: 'totaltransactions',
											sortorder: "desc"
				}).flexReload();
			});
				

			$(dashpane+" #dash-bookingpaneltbl").flexigrid({
				url: 'loadables/ajax/dashboard.booking.php',
				dataType: 'json',
				colModel : [
						{display: 'Booking No.', name : 'booking_number', width : 150, sortable : true, align: 'left'},
						{display: 'Status', name : 'status', width : 100, sortable : true, align: 'left'},
						{display: 'Pickup Date', name : 'pickup_date', width : 100, sortable : true, align: 'left'},
						{display: 'Origin', name : 'origin', width : 200, sortable : true, align: 'left'},
						{display: 'Destination', name : 'destination', width : 200, sortable : true, align: 'left'},
						{display: 'Created by', name : 'created_by', width : 150, sortable : true, align: 'left'},
						{display: 'Created date', name : 'created_date', width : 150, sortable : true, align: 'left'},
						{display: 'Updated date', name : 'updated_date', width : 150, sortable : true, align: 'left', hide:true}
				],
				searchitems : [
						{display: 'Booking No.', name : 'booking_number', isdefault: true},
						{display: 'Origin', name : 'origin'},
						{display: 'Destination', name : 'destination'},
						{display: 'Created by', name : 'created_by'}
				],
				sortname: "updated_date",
				sortorder: "desc",
				usepager: true,
				title: "Bookings",
				useRp: true,
				rp: 10, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 200,
				disableSelect: true,
				singleSelect: false
			});


			$(dashpane+" #dash-bookingapprovalpanel").flexigrid({
				url: 'loadables/ajax/dashboard.booking-approval.php',
				dataType: 'json',
				colModel : [
						{display: 'Booking No.', name : 'booking_number', width : 150, sortable : true, align: 'left'},
						{display: 'Status', name : 'status', width : 100, sortable : true, align: 'left'},
						{display: 'Pickup Date', name : 'pickup_date', width : 100, sortable : true, align: 'left'},
						{display: 'Origin', name : 'origin', width : 200, sortable : true, align: 'left'},
						{display: 'Destination', name : 'destination', width : 200, sortable : true, align: 'left'},
						{display: 'Posted by', name : 'posted_by', width : 150, sortable : true, align: 'left'},
						{display: 'Posted date', name : 'posted_date', width : 150, sortable : true, align: 'left'}
				],
				searchitems : [
						{display: 'Booking No.', name : 'booking_number', isdefault: true},
						{display: 'Origin', name : 'origin'},
						{display: 'Destination', name : 'destination'},
						{display: 'Posted by', name : 'posted_by'}
				],
				sortname: "posted_date",
				sortorder: "desc",
				usepager: true,
				title: "Booking Approvals",
				useRp: true,
				rp: 10, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 200,
				singleSelect: false,
				disableSelect: true
			});

			$(dashpane+" #dash-waybillpaneltbl").flexigrid({
				url: 'loadables/ajax/dashboard.waybill.php',
				dataType: 'json',
				colModel : [
						{display: 'Waybill No.', name : 'waybill_number', width : 150, sortable : true, align: 'left'},
						{display: 'Status', name : 'status', width : 100, sortable : true, align: 'left'},
						{display: 'Delivery Date', name : 'delivery_date', width : 100, sortable : true, align: 'left'},
						{display: 'Origin', name : 'origin', width : 200, sortable : true, align: 'left'},
						{display: 'Destination', name : 'destination', width : 200, sortable : true, align: 'left'},
						{display: 'Created by', name : 'created_by', width : 150, sortable : true, align: 'left'},
						{display: 'Created date', name : 'created_date', width : 150, sortable : true, align: 'left'},
						{display: 'Updated date', name : 'updated_date', width : 150, sortable : true, align: 'left', hide:true}
				],
				searchitems : [
						{display: 'Waybill No.', name : 'waybill_number', isdefault: true},
						{display: 'Origin', name : 'origin'},
						{display: 'Destination', name : 'destination'},
						{display: 'Created by', name : 'created_by'}
				],
				sortname: "updated_date",
				sortorder: "desc",
				usepager: true,
				title: "Waybills",
				useRp: true,
				rp: 10, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 200,
				disableSelect: true,
				singleSelect: false
			});


			$(dashpane+" #dash-loadplanpaneltbl").flexigrid({
				url: 'loadables/ajax/dashboard.load-plan.php',
				dataType: 'json',
				colModel : [
						{display: 'Load Plan No.', name : 'load_plan_number', width : 150, sortable : true, align: 'left'},
						{display: 'Status', name : 'status', width : 100, sortable : true, align: 'left'},
						{display: 'Location', name : 'location', width : 200, sortable : true, align: 'left'},
						{display: 'Origin', name : 'origin', width : 200, sortable : true, align: 'left'},
						{display: 'Destination', name : 'destination', width : 200, sortable : true, align: 'left'},
						{display: 'Mode', name : 'mode_of_transport', width : 80, sortable : true, align: 'left'},
						{display: 'Waybills', name : 'waybills', width : 200, sortable : true, align: 'left'},
						{display: 'Created by', name : 'created_by', width : 150, sortable : true, align: 'left'},
						{display: 'Created date', name : 'created_date', width : 150, sortable : true, align: 'left'},
						{display: 'Updated date', name : 'updated_date', width : 150, sortable : true, align: 'left', hide:true}
				],
				searchitems : [
						{display: 'Load Plan No.', name : 'load_plan_number', isdefault: true},
						{display: 'Waybills', name : 'waybills'},
						{display: 'Location', name : 'location'},
						{display: 'Origin', name : 'origin'},
						{display: 'Destination', name : 'destination'},
						{display: 'Created by', name : 'created_by'}
				],
				sortname: "updated_date",
				sortorder: "desc",
				usepager: true,
				title: "Load Plan",
				useRp: true,
				rp: 10, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 200,
				disableSelect: true,
				singleSelect: false
			});


			$(dashpane+" #dash-manifestpaneltbl").flexigrid({
				url: 'loadables/ajax/dashboard.manifest.php',
				dataType: 'json',
				colModel : [
						{display: 'Manifest No.', name : 'manifest_number', width : 150, sortable : true, align: 'left'},
						{display: 'Status', name : 'status', width : 100, sortable : true, align: 'left'},
						{display: 'Load Plan No.', name : 'load_plan_number', width : 150, sortable : true, align: 'left'},
						{display: 'Location', name : 'location', width : 200, sortable : true, align: 'left'},
						{display: 'Origin', name : 'origin', width : 200, sortable : true, align: 'left'},
						{display: 'Destination', name : 'destination', width : 200, sortable : true, align: 'left'},
						{display: 'Mode', name : 'mode_of_transport', width : 80, sortable : true, align: 'left'},
						{display: 'Waybills', name : 'waybills', width : 200, sortable : true, align: 'left'},
						{display: 'Created by', name : 'created_by', width : 150, sortable : true, align: 'left'},
						{display: 'Created date', name : 'created_date', width : 150, sortable : true, align: 'left'},
						{display: 'Updated date', name : 'updated_date', width : 150, sortable : true, align: 'left', hide:true}
				],
				searchitems : [
						{display: 'Manifest No.', name : 'manifest_number', isdefault: true},
						{display: 'Load Plan No.', name : 'load_plan_number'},
						{display: 'Waybills', name : 'waybills'},
						{display: 'Location', name : 'location'},
						{display: 'Origin', name : 'origin'},
						{display: 'Destination', name : 'destination'},
						{display: 'Created by', name : 'created_by'}
				],
				sortname: "updated_date",
				sortorder: "desc",
				usepager: true,
				title: "Dispatch/Manifest",
				useRp: true,
				rp: 10, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 200,
				disableSelect: true,
				singleSelect: false
			});


			$(dashpane+" #dash-shipperperformancetbl").flexigrid({
				url: 'loadables/ajax/dashboard.shipper-performance.php',
				dataType: 'json',
				colModel : [
						{display: 'line', name : 'line', width : 30, sortable : false, align: 'left'},
						{display: 'Shipper', name : 'shipper.account_name', width : 350, sortable : true, align: 'left'},
						{display: 'Total Transactions', name : 'totaltransactions', width : 120, sortable : true, align: 'right'},
						{display: 'Total Delivered', name : 'totaldelivered', width : 120, sortable : true, align: 'right'},
						{display: 'Delivered %', name : 'deliveredpercentage', width : 90, sortable : true, align: 'right'},
						{display: 'Total Undelivered', name : 'totalundelivered', width : 120, sortable : true, align: 'right'},
						{display: 'Undelivered %', name : 'undeliveredpercentage', width : 90, sortable : true, align: 'right'},
						{display: 'On-Process', name : 'totalonprocess', width : 120, sortable : true, align: 'right'},
						{display: 'On-Process %', name : 'onprocesspercentage', width : 90, sortable : true, align: 'right'}
				],
				searchitems : [
						{display: 'Shipper', name : 'shipper.account_name', isdefault: true}
				],
				sortname: "totaltransactions",
				sortorder: "desc",
				usepager: true,
				title: "",
				useRp: true,
				rp: 15, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 400,
				disableSelect: true,
				singleSelect: true
			});

			
			$(dashpane+" #dash-ar-tagging-tbl").flexigrid({
				url: 'loadables/ajax/dashboard.ar-tagging.php',
				dataType: 'json',
				colModel : [
						{display: 'Line', name : 'line', width : 30, sortable : false, align: 'center'},
						{display: 'Billing<br>Statement<br>Number', name : 'billing_number', width : 65, sortable : true, align: 'center'},
						{display: 'Invoice', name : 'invoice', width : 65, sortable : true, align: 'center'},
						{display: 'Account', name : 'bill_to_account_name', width : 200, sortable : true, align: 'center'},
						{display: 'Status', name : 'status', width : 65, sortable : true, align: 'center'},
						{display: 'Document<br>Date', name : 'document_date', width : 70, sortable : true, align: 'center'},
						{display: 'Vatable<br>Charges', name : 'total_vatable_charges', width : 90, sortable : true, align: 'center'},
						{display: 'Non Vatable<br>Charges', name : 'total_non_vatable_charges', width : 90, sortable : true, align: 'center'},
						{display: 'Vat', name : 'vat', width : 60, sortable : true, align: 'center'},
						{display: 'Total Amount', name : 'total_amount', width : 90, sortable : true, align: 'center'},
						{display: 'Cancelled<br>Amount', name : '', width : 90, sortable : false, align: 'center'},
						{display: 'Revised<br>Amount', name : '', width : 90, sortable : false, align: 'center'},
						{display: 'Received<br>Date', name : 'received_date', width : 90, sortable : true, align: 'center'},
						{display: 'Received<br>By', name : 'received_by', width : 80, sortable : true, align: 'center'},
						{display: 'Due Date', name : 'Due', width : 70, sortable : true, align: 'center'},
						{display: 'Remarks', name : 'remarks', width : 200, sortable : true, align: 'center'}
				],	
				searchitems : [
					    {display: 'Invoice', name : 'invoice', isdefault: true},
						{display: 'Shipper', name : 'bill_to_account_name'}
						
				],
				sortname: "billing_number",
				sortorder: "desc",
				usepager: true,
				title: "",
				useRp: true,
				rp: 15, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 400,
				disableSelect: true,
				singleSelect: true
			});


			function dash_viewdetails(title,tabid,file,jsfile){
					if($(".content>.content-tab-pane .content-tabs").find("li[data-pane='#"+tabid+"tabpane']").length>=1){
						$('#loading-img').removeClass('hidden');
						$(".content>.content-tab-pane .content-tabs>li[data-pane='#"+tabid+"tabpane']").addClass('active').siblings().removeClass('active');
						$(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='"+tabid+"tabpane']").addClass('active').siblings().removeClass('active');
						$(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='"+tabid+"tabpane']").load(file,function(){
							$('#loading-img').addClass('hidden');
						});
						
					}
					else{
						$('#loading-img').removeClass('hidden');
						var js = pagescripts+jsfile;
						$.getScript(js);
						$('.content').animate({scrollTop:0},300);

						$('.content>.content-tab-pane .content-tabs>li').removeClass('active');
						$('.content>.content-tab-pane .content-pane-wrapper>.content-pane').removeClass('active');
						$('.content>.content-tab-pane .content-tabs').append("<li data-pane='#"+tabid+"tabpane' class='active'>"+title+"<i class='fa fa-remove'></i></li>");
						$('.content>.content-tab-pane .content-pane-wrapper').append("<div class='content-pane active' id='"+tabid+"tabpane'></div>");
						$('.content>.content-tab-pane .content-pane-wrapper>.content-pane:last-child').load(file,function(){
								$('#loading-img').addClass('hidden');
						});
						
					}
			}

			$(document).off('dblclick',dashpane+' .dash-bookingpendingrow').on('dblclick',dashpane+' .dash-bookingpendingrow',function(){
				var sourcedoc = $(this).attr('bookingnumber'),
				        title = 'Booking',
					    tabid = 'booking-menu',
					     file = 'transactions/booking.php?reference='+sourcedoc,
					    jsfile = 'booking.js';

				dash_viewdetails(title,tabid,file,jsfile);

					
			});

			$(document).off('dblclick',dashpane+' .dash-waybillpendingrow').on('dblclick',dashpane+' .dash-waybillpendingrow',function(){
				var sourcedoc = $(this).attr('waybillnumber'),
				        title = 'Waybill',
					    tabid = 'waybill-menu',
					     file = 'transactions/waybill.php?reference='+sourcedoc,
					    jsfile = 'waybill.js';

				dash_viewdetails(title,tabid,file,jsfile);

					
			});

			$(document).off('dblclick',dashpane+' .dash-loadplanpendingrow').on('dblclick',dashpane+' .dash-loadplanpendingrow',function(){
				var sourcedoc = $(this).attr('loadplannumber'),
				        title = 'Load Plan',
					    tabid = 'loadplan-menu',
					     file = 'transactions/load-plan.php?reference='+sourcedoc,
					    jsfile = 'load-plan.js';

				dash_viewdetails(title,tabid,file,jsfile);

					
			});


			$(document).off('dblclick',dashpane+' .dash-manifestpendingrow').on('dblclick',dashpane+' .dash-manifestpendingrow',function(){
				
				var sourcedoc = $(this).attr('manifestnumber'),
				        title = 'Dispatch/Manifest',
					    tabid = 'manifest-menu',
					     file = 'transactions/manifest.php?reference='+sourcedoc,
					    jsfile = 'manifest.js';

				dash_viewdetails(title,tabid,file,jsfile);
				
			});


			$(document).off('dblclick',dashpane+' .dash-bookingapprovalrow').on('dblclick',dashpane+' .dash-bookingapprovalrow',function(){
				var sourcedoc = $(this).attr('bookingnumber'),
				        title = 'Booking Approval',
					    tabid = 'bookingapproval-menu',
					     file = 'transactions/booking-approval.php?reference='+sourcedoc,
					    jsfile = 'booking-approval.js'; 
				dash_viewdetails(title,tabid,file,jsfile);
			});



			$(document).off('dblclick',dashpane+' .dash-billing-row').on('dblclick',dashpane+' .dash-billing-row',function(){
				var billno = $(this).attr('billing_number');
				var dayterm = $(this).attr('dayterm');
				$("#modal-receive-billing .btn").attr("billno",billno);
				$("#modal-receive-billing .btn").attr("dayterm",dayterm);
				$("#modal-receive-billing").modal('show');

			});

			
			$("#modal-receive-billing").on("shown.bs.modal", e => {
 
				$.ajax({
					type: "POST",
					url: "../addonsb/ar-tagging.php",
					data: { datenow : 1 }, 
					success: function (response) {
						$(".billing-received-date").val(response);
						$("#billing-received-by").select();
						$("#modal-receive-billing-title").html(`Receive Billing: ${$("#modal-receive-billing .btn").attr("billno")}`);
					}
				}); 
				
			});

			
			$("#modal-receive-billing .btn").click( e => {
				var billno = $("#modal-receive-billing .btn").attr("billno");
				var dayterm = $("#modal-receive-billing .btn").attr("dayterm");
				console.log(`Billing Num: ${billno}`);
				
				if($(".billing-received-date").val().length<=0){

									$("#modal-receive-billing .modal-errordiv").html(
										`
										<div class='message'> 
											<div class='message-content'>
												<span class='closemessage'>&times;</span>Please enter receive date.
											</div>
										</div>
										`
									);

				}
				else if($("#billing-received-by").val().length<=0){

									$("#modal-receive-billing .modal-errordiv").html(
										`
										<div class='message'> 
											<div class='message-content'>
												<span class='closemessage'>&times;</span>Please Enter Received By Person
											</div>
										</div>
										`
									);

				}
				else {

					$.ajax({
						type: "POST",
						url: "../addonsb/ar-tagging.php",
						data: { receive : 1, receivedDate : $(".billing-received-date").val(), receivedBy : $("#billing-received-by").val(), billing_num : billno, dayterm : dayterm },
						success: function (response) {
							try {
								response = $.parseJSON(response);
								if(response.Success){
									$(".billing-received-date").val('');
									$("#billing-received-by").val('');
									$("#modal-receive-billing").modal('hide');	
									$("#dash-ar-tagging-tbl").flexReload();
								}
								else {
									$("#modal-receive-billing .modal-errordiv").html(
										`
										<div class='message'> 
											<div class='message-content'>
												<span class='closemessage'>&times;</span>${response.Message}
											</div>
										</div>
										`
									);
								}
							} catch (error) {
								console.log(error.toString());
							}
						}
					});	

				}


			}); 

			userAccess();
			
    


			

	});
	



</script>