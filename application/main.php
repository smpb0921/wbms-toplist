<?php
	include("../config/connection.php");
	include("../config/checklogin.php");
	if(isset($_SESSION['MdjKeoIdhk'])){
		$viewtobeloaded = $_SESSION['MdjKeoIdhk'];
	}else{
		$viewtobeloaded = "";
	}

	

?>

<!doctype html>
<html>
	<head>

		<title>Waybill Management System</title>
		<meta name="viewport" content="width=device-width, initial-scale=.7">
		<link rel="stylesheet" href="../css/bootstrap.css">

		

		<link rel="stylesheet" href="../css/main.css">
		<!--<link href='https://fonts.googleapis.com/css?family=Pacifico|Open+Sans|Lato:400,700,300,900|Carter+One' rel='stylesheet' type='text/css'>-->
		<!--<link rel="shortcut icon" href="../resources/icon.ico">-->

		<!--<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>-->

    	<!--<link rel="stylesheet" href="../resources/tags/dist/bootstrap-tagsinput.css">-->

		<link href="../css/fontAwesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link href="../css/animate.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src='../js/functions.js'></script>

		<!-- FOR DROPDOWN -->
		<link href="../resources/select/dist/css/select2.css" rel="stylesheet" />


		<!-- DIALOG, CONFIRM, ALERTS-->
		<link rel="stylesheet" type="text/css" href="../resources/dialog/jquery-confirm.css" />

		<!--DATE PICKER -->
		<link rel="stylesheet" href="../resources/datepicker/jquery-ui.css">
		<link rel="stylesheet" href="../resources/datepicker/jquery-ui.theme.css">
		<link rel="stylesheet" href="../resources/datepicker/jquery-ui.structure.css">
		<!--<link rel="stylesheet" href="../resources/jquery-interactions/jquery-ui2.css">-->

		<!-- DATATABLES -->
		<link rel="stylesheet" href="../resources/datatables/media/css/dataTables.bootstrap.css">

		<!-- COLOR PICKER -->
		<link href="../resources/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css" rel="stylesheet">

		<!-- FLEXIGRID -->
		<link rel="stylesheet" href="../resources/flexigrid/css/flexigrid.css">
		<link rel="stylesheet" href="../resources/flexigrid/css/kaye_custom.css">
		<link rel="stylesheet" href="../resources/flexigrid/custom.css">

		<link rel="stylesheet" href="../resources/timepicker-addon/src/jquery-ui-timepicker-addon.css">


		<link rel="stylesheet" href="../css/bootstrap-toggle.css">

		<!-- TAGS -->
		<link rel="stylesheet" href="../resources/tags/bootstrap-tagsinput.css">
		<link rel="stylesheet" href="../resources/tags/custom.css">

		<!-- BOOTSTRAP TABLE -->
		<link rel="stylesheet" href="../resources/bootstrap-table/dist/bootstrap-table.css">

		<!-- TREE -->
		<link rel="stylesheet" href="../resources/jstree/dist/themes/default/style.css">


		<!-- UPLOADER -->
		<link rel="stylesheet" href="../resources/uploader-master/dist/css/jquery.dm-uploader.min.css">



		







		<!-- GRAPHS -->

		<!--<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<link rel="stylesheet" href="../resources/chartist/chartist.css">-->






	</head>
	<body style='background-color: #EAEFF2;' tabindex='1' class='gray-theme'>
		<div id='loading-img' style='width:100%; height:100%; position:absolute; z-index:1000000000; background-color:rgba(255, 255, 255, 0);'> 
				<img src="../resources/img/loading.gif" height='180px'  style='position:fixed; z-index:10000; top:35%; left:43%;'>
		</div>		
			    <!-- ********************************* MOBILE NAV *********************************************************************-->
    			<div class="visible-xs visible-sm">
        			<div class="navbar navbar-inverse navbar-fixed-top">
           				<div class="container">

                			<div class="navbar-header">
			                    <div class="navbar-brand">WMS</div>
			                    <button class="navbar-toggle" data-toggle="collapse" data-target=".collapseThisNav">
			                        <span class="icon-bar"></span>
			                        <span class="icon-bar"></span>
			                        <span class="icon-bar"></span>
			                    </button>
			                </div>

			                <div class="collapse navbar-collapse collapseThisNav">
			                    <ul class="nav navbar-nav">
			                    	<li class='nosubmenu' data-file='dashboard.php'><a>Dashboard</a></li>
			                        
			                        <li class="dropdown hidden" data-id='system-section'>
			                            <a class="dropdown-toggle" data-toggle="dropdown">System &nbsp;<i class='fa fa-caret-down'></i></a>
			                                <ul class="dropdown-menu">
			                                	 <li class='nosubmenu hidden' data-file='system/configuration.php' data-id='configuration' data-js='configuration.js'>
				                                	<a>Configuration</a>
				                                </li>
				                                <li class='nosubmenu hidden' data-file='system/user.php' data-id='user' data-js='user.js'>
				                                	<a>User</a>
				                                </li>
				                                <li class='nosubmenu hidden' data-file='system/user-group.php' data-js='user-group.js' data-id='usergroup'>
				                                	<a>User Group</a>
				                                </li>
				                                <li class='nosubmenu hidden' data-file='system/approver.php' data-id='approver' data-js='approver.js'>
				                                	<a>Approver</a>
				                                </li>
				                                <li class='nosubmenu hidden' data-file='system/transaction-type.php' data-js='transaction-type.js' data-id='transaction'>
				                                	<a>Transaction Type</a>
				                                </li>
				                                <li class='nosubmenu hidden' data-file='system/system-log.php' data-js='system-log.js' data-id='transaction'>
				                                	<a>System Log</a>
				                                </li>
				                                <li class='nosubmenu hidden' data-file='system/department.php' data-js='department.js' data-id='department-menu'>
				                                	<a>Department</a>
				                                </li>
				                                <li class='nosubmenu hidden' data-file='inventory/stock-receipt.php' data-js='stock-receipt.js' data-id='stockreceipt-menu'>
				                                	<a>Stock Receipt</a>
				                                </li>

			                                </ul>   
			                        </li>
			                         <li class="dropdown hidden" data-id='maintenance-section'>
          								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Maintenance&nbsp; <span class="caret"></span></a>
							          	<ul class="dropdown-menu">
								            <li class='nosubmenu hidden' data-file='maintenance/items.php' data-js='item.js' data-id='items'>
			                                	<a>Item</a>
			                                </li>
			                                <li class='nosubmenu hidden' data-file='maintenance/supplier.php' data-js='supplier.js' data-id='supplier'>
			                                	<a>Supplier</a>
			                         		 </li>
			                                <li class='nosubmenu hidden' data-file='maintenance/terms-of-payment.php' data-js='terms-of-payment.js' data-id='terms'>
			                                	<a>Term of Payment</a>
			                                </li>
			                                <li class='nosubmenu hidden' data-file='maintenance/section-department.php'  data-js='section-department.js' data-id='department'>
			                                	<a>Section/Department</a>
			                                </li>

         								 </ul>
       								</li>
       								<li class="dropdown hidden" data-id='transaction-section'>
          								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Transaction&nbsp;<span class="caret"></span></a>
							          	<ul class="dropdown-menu">
										    <li class='nosubmenu hidden' data-file='transaction/purchase-requisition.php' data-js='purchase-requisition.js' data-id='pr'>
			                                	<a>Purchase Requisition</a>
			                                </li>
			                                <li class='nosubmenu hidden' data-file='transaction/purchase-order.php' data-js='purchase-order.js' data-id='po'>
			                                	<a>Purchase Order</a>
			                                </li>
			                                <li class='nosubmenu hidden' data-file='transaction/receiving.php' data-js='receiving.js' data-id='receiving'>
			                                	<a>Receiving</a>
			                                </li>

         								 </ul>
       								</li>
       								<li class="dropdown hidden" data-id='reports-section'>
          								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reports&nbsp; <span class="caret"></span></a>
							          	<ul class="dropdown-menu">
							          		<li class='nosubmenu hidden' data-file='reports/pr-summary.php' data-js='pr-summary.js' data-id='prsumm'>
			                                	<a>Purchase Request Summary</a>
			                                </li>
			                                <li class='nosubmenu hidden' data-file='reports/po-summary.php' data-js='po-summary.js' data-id='posumm'>
			                                	<a>Purchase Order Summary</a>
			                                </li>
			                                <li class='nosubmenu hidden' data-file='reports/rcv-summary.php' data-js='rcv-summary.js' data-id='rcvsumm'>
			                                	<a>Receiving Summary</a>
			                                </li>
							          	</ul>
							         </li>
							         <li><a href='logout.php'>Logout</a></li>
			                       




			                    </ul>
			                </div>

			            </div>
			        </div>
			    </div>
			    <!-- ********************************* END MOBILE NAV ******************************************************************-->





			   <!-- ************************************ SIDEBAR NAV ****************************************************************** -->
			   <div id="sidebar">
			   		<div id='sidebar-panel'>
				   		<div id="sidebar-nav">
				   			<div class="sidebarlogo">
				   				<a href=''><img class='img img-responsive' src="../resources/logicorelogo.png"></a>
				   			</div>
				   			<div class='nosubmenu active' data-title='Dashboard' data-file='dashboard.php' data-js='dashboard.js' id='dash'>
				   				<img class='img' src="../resources/icons/main-home.png"><span>Dashboard</span>
				   			</div>
				   			<div class='withsubmenu' id='system-section'>
				   				<img class='img' src="../resources/icons/main-system.png">System
				   			</div>
				   			<div class="submenu">
				   				<div data-title='Configuration' data-file='system/configuration.php' id='configuration-menu' data-js='configuration.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Configuration
				   				</div>
				   				<div data-title='User' data-file='system/user.php' id='user-menu' data-js='user.js'>
				   					<img class='img' src="../resources/img/folder_64.png">User
				   				</div>
				   				<div data-title='User Group' data-file='system/user-group.php' id='usergroup-menu' data-js='user-group.js'>
				   					<img class='img' src="../resources/img/folder_64.png">User Group
				   				</div>
				   				<div data-title='API Status Shown' data-file='system/api-status-shown.php' id='webapistatusshown-menu' data-js='api-status-shown.js'>
				   					<img class='img' src="../resources/img/folder_64.png">API Status Shown
				   				</div>
				   				<div data-title='Transaction Type' data-file='system/transaction-type.php' data-js='transaction-type.js' id='transactiontype-menu'>
				   					<img class='img' src="../resources/img/folder_64.png">Transaction Type
				   				</div>
				   				<div data-title='System Log' data-file='system/system-log.php' data-js='system-log.js' id='systemlog-menu'>
				   					<img class='img' src="../resources/img/folder_64.png">System Log
				   				</div>
				   				<div data-title='Final Waybill Status' data-file='system/final-status.php' data-js='final-status.js' id='finalstatus-menu'>
				   					<img class='img' src="../resources/img/folder_64.png">Final Waybill Status
				   				</div>
				   				<div data-title='Booking Status in BOL' data-file='system/allowed-booking-status-in-waybill.php' data-js='allowed-booking-status-in-waybill.js' id='bookingstatusinwaybill-menu'>
				   					<img class='img' src="../resources/img/folder_64.png">Booking Status in BOL
				   				</div>
				   			</div>
		                    <div class='withsubmenu' id='maintenance-section'><img class='img' src="../resources/icons/main-sales.png">Maintenance</div>
				   			<div class="submenu">
				   				<div  data-title='Zone' data-file='maintenance/zone.php' id='zone-menu' data-js='zone.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Zone
				   				</div>
				   				<div  data-title='Origin/Destination Port' data-file='maintenance/origin-destination-port.php' id='origindestinationport-menu' data-js='origin-destination-port.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Origin/Destination Port
				   				</div>
				   				<div  data-title='Destination Route' data-file='maintenance/destination-route.php' id='destinationroute-menu' data-js='destination-route.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Destination Route
				   				</div>
				   				<div  data-title='Shipper' data-file='maintenance/shipper.php' id='shipper-menu' data-js='shipper.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Shipper
				   				</div>
				   				<div  data-title='Account Executive' data-file='maintenance/account-executive.php' id='accountexecutive-menu' data-js='account-executive.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Account Executive
				   				</div>
				   				<div  data-title='Consignee' data-file='maintenance/consignee.php' id='consignee-menu' data-js='consignee.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Consignee
				   				</div>
				   				<div  data-title='Agent' data-file='maintenance/agent.php' id='agent-menu' data-js='agent.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Agent
				   				</div>
				   				<div  data-title='Driver/Helper' data-file='maintenance/personnel.php' id='personnel-menu' data-js='personnel.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Driver/Helper
				   				</div>
				   				<div  data-title='Services' data-file='maintenance/services.php' id='services-menu' data-js='services.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Services
				   				</div>
				   				<div  data-title='Mode of Transport' data-file='maintenance/mode-of-transport.php' id='modeoftransport-menu' data-js='mode-of-transport.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Mode of Transport
				   				</div>
				   				<div  data-title='Handling Instruction' data-file='maintenance/handling-instruction.php' id='handlinginstruction-menu' data-js='handling-instruction.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Handling Instruction
				   				</div>
				   				<div  data-title='Accompanying Documents' data-file='maintenance/accompanying-documents.php' id='accompanyingdocuments-menu' data-js='accompanying-documents.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Accompanying Documents
				   				</div>
				   				<div  data-title='Delivery Instruction' data-file='maintenance/delivery-instruction.php' id='deliveryinstruction-menu' data-js='delivery-instruction.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Delivery Instruction
				   				</div>
				   				<div  data-title='Transport Charges' data-file='maintenance/transport-charges.php' id='transportcharges-menu' data-js='transport-charges.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Transport Charges
				   				</div>
				   				<div  data-title='Carrier' data-file='maintenance/carrier.php' id='carrier-menu' data-js='carrier.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Carrier
				   				</div>
				   				<div  data-title='Location' data-file='maintenance/location.php' id='location-menu' data-js='location.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Location
				   				</div>
				   				<div  data-title='Movement Type' data-file='maintenance/movement-type.php' id='movementtype-menu' data-js='movement-type.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Movement Type
				   				</div>
				   				<div  data-title='Published Rate' data-file='maintenance/published-rate.php' id='publishedrate-menu' data-js='published-rate.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Published Rate
				   				</div>
				   				<div  data-title='Shipper Rate' data-file='maintenance/shipper-rate2.php' id='shipperrate-menu' data-js='shipper-rate2.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Shipper Rate
				   				</div>
				   				<div  data-title='Supplier Rate' data-file='maintenance/supplier-rate.php' id='supplierrate-menu' data-js='supplier-rate.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Supplier Rate
				   				</div>
									<div  data-title='Shipment Type' data-file='maintenance/shipment-type.php' id='shipmenttype-menu' data-js='shipment-type.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Shipment Type
								</div>
									<div  data-title='Shipment Mode' data-file='maintenance/shipment-mode.php' id='shipmentmode-menu' data-js='shipment-mode.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Shipment Mode
				   				</div>
				   				<div  data-title='Waybill Booklet Issuance' data-file='maintenance/waybill-booklet-issuance.php' id='waybillbookletissuance-menu' data-js='waybill-booklet-issuance.js'>
				   					<img class='img' src="../resources/img/folder_64.png">BOL Booklet Issuance
				   				</div>
				   				<div  data-title='Vehicle Type' data-file='maintenance/vehicle-type.php' id='vehicletype-menu' data-js='vehicle-type.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Vehicle Type
				   				</div>
				   				<div  data-title='Vehicle' data-file='maintenance/vehicle.php' id='vehicle-menu' data-js='vehicle.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Vehicle
				   				</div>
				   				<div  data-title='Unit of Measure' data-file='maintenance/unit-of-measure.php' id='unitofmeasure-menu' data-js='unit-of-measure.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Unit of Measure
				   				</div>
				   				<div  data-title='Pay Mode' data-file='maintenance/pay-mode.php' id='paymode-menu' data-js='pay-mode.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Pay Mode
				   				</div>
				   				<div  data-title='Other Charges' data-file='maintenance/other-charges.php' id='othercharges-menu' data-js='other-charges.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Other Charges
				   				</div>
				   				<div  data-title='District/City/Zip Code' data-file='maintenance/district-city-zip.php' id='districtcityzip-menu' data-js='district-city-zip.js'>
				   					<img class='img' src="../resources/img/folder_64.png">District/City/Zip Code
				   				</div>
				   				<div  data-title='Pouch Size' data-file='maintenance/pouch-size.php' id='pouchsize-menu' data-js='pouch-size.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Pouch Size
				   				</div>
				   				<div  data-title='Type of Account' data-file='maintenance/expense-type.php' id='expensetype-menu' data-js='expense-type.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Type of Account
				   				</div>
				   				<div  data-title='Chart of Accounts' data-file='maintenance/chart-of-accounts.php' id='chartofaccounts-menu' data-js='chart-of-accounts.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Chart of Accounts
				   				</div>
								<div  data-title='Payee' data-file='maintenance/payee.php' id='payee-menu' data-js='payee.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Payee
				   				</div>
				   				
		                    </div>
		                    <div class='withsubmenu' id='transactions-section'><img class='img' src="../resources/icons/main-purchases.png">Transactions</div>
				   			<div class="submenu">
				   				
				   				<div  data-title='Booking' data-file='transactions/booking.php' id='booking-menu' data-js='booking.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Booking
				   				</div>
				   				<div  data-title='Booking Approval' data-file='transactions/booking-approval.php' id='bookingapproval-menu' data-js='booking-approval.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Booking Approval
				   				</div>
				   				<!--<div  data-title='Booking' data-file='transactions/booking.php' id='booking-menu' data-js='booking.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Import/Export
				   				</div>-->
				   				<div  data-title='Bill of Lading' data-file='transactions/waybill.php' id='waybill-menu' data-js='waybill.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Bill of Lading
				   				</div>
				   				<div  data-title='Costing' data-file='transactions/costing.php' id='costing-menu' data-js='costing.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Costing
				   				</div>
				   				<div  data-title='BOL Movement' data-file='transactions/waybill-movement.php' id='waybillmovement-menu' data-js='waybill-movement.js'>
				   					<img class='img' src="../resources/img/folder_64.png">BOL Movement
				   				</div>
				   				<div  data-title='Load Plan' data-file='transactions/load-plan.php' id='loadplan-menu' data-js='load-plan.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Load Plan
				   				</div>
				   				<div  data-title='Dispatch/Manifest' data-file='transactions/manifest.php' id='manifest-menu' data-js='manifest.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Dispatch/Manifest
				   				</div>
				   				<div  data-title='Billing Statement' data-file='transactions/billing-statement.php' id='billingstatement-menu' data-js='billing-statement.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Billing Statement
				   				</div>

				   				
				   				
				   				
		                    </div>
		                    <div class='withsubmenu' id='reports-section'><img class='img' src="../resources/icons/main-report.png">Reports
		                    	


		                    </div>
				   			<div class="submenu">
				   				
				   				<div  data-title='Booking Summary' data-file='reports/booking-summary.php' id='bookingreport-menu' data-js='booking-summary.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Booking Summary
				   				</div>
				   				<div  data-title='Waybill Summary' data-file='reports/waybill-summary.php' id='waybillreport-menu' data-js='waybill-summary.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Bill of Lading
				   				</div>
				   				<div  data-title='Load Plan Summary' data-file='reports/load-plan-summary.php' id='loadplanreport-menu' data-js='load-plan-summary.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Load Plan Summary
				   				</div>
				   				<div  data-title='Dispatch/Manifest Summary' data-file='reports/manifest-summary.php' id='manifestreport-menu' data-js='manifest-summary.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Dispatch/Manifest
				   				</div>
				   				<div  data-title='Masterlist' data-file='reports/masterlist-summary.php' id='masterlistreport-menu' data-js='masterlist-summary.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Masterlist
				   				</div>
				   				<!--<div  data-title='Waybill Tracking Summary' data-file='reports/waybill-tracking-summary.php' id='waybilltrackingreport-menu' data-js='waybill-tracking-summary.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Waybill Tracking
				   				</div>
				   				<div  data-title='Receipt Summary' data-file='reports/receipt-summary.php' id='waybillbatchprintreport-menu' data-js='receipt-summary.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Waybill Batch Print
				   				</div>-->
				   				<div  data-title='Waybill Booklet Summary' data-file='reports/waybill-booklet-summary.php' id='waybillbookletreport-menu' data-js='waybill-booklet-summary.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Waybill Booklet Summary
				   				</div>

				   				<div  data-title='Billing Summary' data-file='reports/billing-summary.php' id='billingreport-menu' data-js='billing-summary.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Billing Summary
				   				</div>
				   				<div  data-title='Transmittal' data-file='reports/transmittal-summary.php' id='transmittalreport-menu' data-js='transmittal-summary.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Transmittal Summary
				   				</div>
								<div  data-title='Costing' data-file='reports/costing-summary.php' id='costingreport-menu' data-js='costing-summary.js'>
				   					<img class='img' src="../resources/img/folder_64.png">BOL Costing Summary
				   				</div>
								<div  data-title='Booking BOLs' data-file='reports/booking-bols.php' id='bookingbols-menu' data-js='booking-bols.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Booking BOLs Summary
				   				</div>
								<!--<div  data-title='Courier Daily Delivery Transmittal' data-file='reports/courier-daily-delivery-transmittal.php' id='cddtreport-menu' data-js='courier-daily-delivery-transmittal.js'>
				   					<img class='img' src="../resources/img/folder_64.png">CDDT Report
				   				</div>-->
								<div  data-title='Metro Manila Daily Acitvity' data-file='reports/metro-manila-daily-activity.php' id='mmdareport-menu' data-js='metro-manila-daily-activity.js'>
				   					<img class='img' src="../resources/img/folder_64.png">Metro Manila Daily Activity
				   				</div>
								<div  data-title='RTS Report' data-file='reports/rts-report.php' id='rtsreport-menu' data-js='rts-report.js'>
				   					<img class='img' src="../resources/img/folder_64.png">RTS Report
				   				</div>
								<div  data-title='PPMM Report' data-file='reports/ppmm-report.php' id='ppmmreport-menu' data-js='ppmm-report.js'>
				   					<img class='img' src="../resources/img/folder_64.png">PPMM Report
				   				</div>
				   				
				   				
				   				
		                    </div>
				   		</div>
				   		<div class='text-center' id='poweredby'>Designed and Developed by <a href="">Turningpoint Inc.</a></div>
				   	</div>
			   </div>

			   


			   <!-- ************************************END SIDEBAR NAV ****************************************************************** -->





			   <!-- ************************************************ HEADER ******************************************** -->
			   <div class="topheader">
			   			<div class='user-header hidden-xs hidden-sm' >
			   				<div class="user-header-inner" >
				   				<i class="fa fa-user fa-2x" style='padding-right:5px; font-size:1.3em'></i>
				   				<span>
				   				Welcome, 
				   				<span class='account-settings-link pointer' data-title='User Settings' data-js='user-settings.js' data-file='user-settings.php' id='usersettings' title='User Settings'><?php echo ucwords(strtolower(@$_SESSION['fnameWMS'])); ?>
				   				</span>!&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<?php echo date('l, F d, Y') ?>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
				   				<span class='pointer kainos-wms-about-link' data-toggle='modal' href='#systemaboutmodal'>About</span>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
				   				<a class='signout' href="logout.php">
				   					<i class="fa fa-sign-out fa-2x" style='padding-right:5px; font-size:1.2em'></i>Logout
				   				</a>
				   				</span>
				   			</div>	   				
			   			</div>
			   </div>

			   

			   <!-- ************************************************ HEADER ******************************************** -->





			   <!-- ******************************************* CONTENT ****************************************************************-->

				<div class='content' id='content' style='overflow:auto;'>
					<div class="content-tab-pane">
						<div class="content-tabs-wrapper">
							<ul class='content-tabs'>

							</ul>
						</div>
						<div class='content-pane-wrapper'>
						</div>
					</div>

				</div>

			   <!-- ************************************ END OF CONTENT ********************************************************************-->

			   	<div class="modal fade" id="systemaboutmodal">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<div class='page-title'>
									About
									<button class="close" data-dismiss="modal">&times;</button>
								</div>
							</div>
							<div class="modal-body">
									<div class="col-sm-4">
										<img src="../resources/logo.png" class='img-responsive'><br>
									</div>
									<div class="col-sm-8">
										<span style='font-size:18px'>AIRWAY BILL MANAGEMENT SYSTEM</span><br>
										Version 1.1.0 (For Windows) <br>
										Developed by <a href="http://tpincorporated.com">Turningpoint Incorporated</a>.<br>
										Copyright &copy; <?php echo date('Y'); ?>. All Rights Reserved.


										<br></br>
										This software is protected by copyright law. Unauthorized reproduction or
										distribution of this application, or any portion of it, may result in severe
										civil and criminal penalties, and will be prosecuted to the maximum extent possible
										under law.

									</div>
							</div>
							<div class="modal-footer">
								<br>
							</div>
						</div>
					</div>  
				</div>





				<div class="modal fade" id="fieldeditmodal">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<div class='page-title'>
									<span class='fieldeditmodal-title'></span>
									<button class="close" data-dismiss="modal">&times;</button>
								</div>
							</div>
							<div class="modal-body">
								<div class='form-horizontal'>
										<input type='hidden' class='fieldeditmodal-table'>
										<input type='hidden' class='fieldeditmodal-column'>
										<input type='hidden' class='fieldeditmodal-txncolumn'>
										<input type='hidden' class='fieldeditmodal-txnnumber'>
										<input type='hidden' class='fieldeditmodal-type'>
										<input type='hidden' class='fieldeditmodal-id'>
										<input type='hidden' class='fieldeditmodal-code'>
									
										<div class='modal-errordiv'></div>
										<div class="form-group">
											<div class='col-md-12'>
										    	<label class='control-label'>Old Value</label>
										        <input type='text' class='form-input form-control fieldeditmodal-oldvalue' disabled="">
										    </div>
										</div>
										<div class="form-group">
											<div class='col-md-12'>
										    	<label class='control-label'>New Value</label>
										        <input type='text' class='form-input form-control fieldeditmodal-newvalue'>
										    </div>
										        
										</div>
								</div>
							</div>
							<div class="modal-footer">
								<div class="text-center">
									<button class='btn btn-blue2 mybtn' id='fieldeditmodal-savebtn'>Save</button>
									<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
								</div>
							</div>
						</div>
					</div>  
				</div>

		





		<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->

		<script src="../js/jquery-1.11.3.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script src="../js/jsfunctions.js"></script>

		<script src="../resources/BootstrapNotification/bootstrap-notify.min.js"></script>

		<script type="text/javascript" src="../resources/dialog/jquery-confirm.js"></script> 	

		<script type="text/javascript" src="../resources/datepicker/jquery-ui.js"></script>
		<script type="text/javascript" src="../resources/jquery-interactions/jquery-ui2.js"></script>

		<!--<script src="../resources/tags/dist/bootstrap-tagsinput.min.js"></script>
		<script src="../resources/tags/examples/assets/app.js"></script>-->

		<!-- DATATABLES -->
		<script type="text/javascript" src="../resources/datatables/media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" src="../resources/datatables/media/js/dataTables.bootstrap.js"></script>

		<!-- MY CUSTOM SCRIPTS -->
		<script type="text/javascript" src='../js/custom.js'></script>	
		<script type="text/javascript" src='../js/main.js'></script>

		<!-- FOR DROPDOWN -->
		<script src="../resources/select/dist/js/select2.js"></script>
		
		<!-- PRICE FIELD FORMAT -->
		<script type="text/javascript" src="../resources/priceformat2/jquery.number.js"></script>


		<!-- FLEXIGRID -->
		<script type="text/javascript" src="../resources/flexigrid/js/flexigrid.js"></script>

		<!-- COLOR PICKER -->
		<script type="text/javascript" src="../resources/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js"></script>


		<script type="text/javascript" src="../resources/resizecolumn/dist/jquery.resizableColumns.js"></script>

		<script type="text/javascript" src="../resources/timepicker-addon/src/jquery-ui-timepicker-addon.js"></script>


		<!-- TOGGLE -->
		<script src="../js/bootstrap-toggle.js"></script>

		<!-- TAGS -->
		<script type="text/javascript" src="../resources/tags/bootstrap-tagsinput.js"></script>

		<!-- BOOTSTRAP TABLE -->
		<script src="../resources/bootstrap-table/dist/bootstrap-table.js"></script>

		<!-- TREE -->
		<script src="../resources/jstree/dist/jstree.js"></script>

		
		<script src="../resources/uploader-master/dist/js/jquery.dm-uploader.min.js"></script>

		<!-- GRAPHS -->
		<!--<script type="text/javascript" src="../resources/chartist/chartist.js"></script>
		<script src="https://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/modules/exporting.js"></script>-->
		





		<script type="text/javascript">
			$(document).ready(function(){
				setTimeout(function(){
					$('#loading-img').addClass('hidden');
				},500);
				
				
				var windowHeight = $(window).height()-51;
				$('.content').css("height",windowHeight);
				$('.sidebar-panel').css("height",$(window).height()-20);
				$(window).resize(function(){
					$('.content').css({height:$(window).height()-51});
					$('.sidebar-panel').css({height:$(window).height()-20});

				});

				userAccess();
				userAccessMobile();

				var viewtobeloaded  = <?php echo json_encode($viewtobeloaded) ?>;
				viewtobeloaded = viewtobeloaded.trim();


				if(viewtobeloaded==''){
					$.post("../scripts/main.php",{checkDashboardAccess:'sd$oihBO$h#OiB@s09#j!@IO#09aujj$Oi03n'},function(data){

						if(data.trim()=='true'){
							$('.content>.content-tab-pane .content-tabs').append("<li data-pane='#dashtabpane' class='active'>Dashboard<i class='fa fa-remove'></i></li>");
							$('.content>.content-tab-pane .content-pane-wrapper').append("<div class='content-pane active' id='dashtabpane'></div>");
							$('.content>.content-tab-pane .content-pane-wrapper>.content-pane:last-child').load('dashboard.php');
							$.getScript(pagescripts+'dashboard.js');
						}
						else if(data.trim()!=''){
							alert(data);
						}
					});
				}



				/** BUTTON CLICK SOUND ***/
				var obj = document.createElement("audio"); 
				obj.src="../resources/click.mp3";
				obj.volume=1;
				obj.autoPlay=false;
				obj.preLoad=true;
				$(document).on("click",".soundclick, button:not('.disabled'), .button-group-btn:not('.disabled'),.nav-tabs>li,#sidebar-nav>.nosubmenu,#sidebar-nav>.withsubmenu,#sidebar-nav>.submenu>div, table.dataTable>thead>tr>th:not('.column-nosort'), #usersettings",function(){
			        obj.play();
				});
				/***************************/

					$(".withsubmenu").click(function(){
						var ht = .46*$(window).height();
						$(this).next('.submenu').css({maxHeight:ht});
					});





					/*(function() {
					    var beforePrint = function() {
					        console.log('Functionality to run before printing.');
					    };
					    var afterPrint = function() {
					        console.log('Functionality to run after printing');
					    };

					    if (window.matchMedia) {
					        var mediaQueryList = window.matchMedia('print');
					        mediaQueryList.addListener(function(mql) {
					            if (mql.matches) {
					                beforePrint();
					            } else {
					                afterPrint();
					            }
					        });
					    }

					    window.onbeforeprint = beforePrint;
					    window.onafterprint = afterPrint;
					}());*/


					var beforePrint = function() {
        console.log('Functionality to run before printing.');
    };

    var afterPrint = function() {
        console.log('Functionality to run after printing');
    };

    if (window.matchMedia) {
        var mediaQueryList = window.matchMedia('print');
        mediaQueryList.addListener(function(mql) {
            if (mql.matches) {
                beforePrint();
            } else {
                afterPrint();
            }
        });
    }

    window.onbeforeprint = beforePrint;
    window.onafterprint = afterPrint;

			});

		</script>






		


	</body>
</html>