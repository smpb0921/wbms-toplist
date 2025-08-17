<?php
     include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
    $refBK = isset($_GET['reference'])?escapeString($_GET['reference']):'';
?>
<div class='header-page' >
	<div class='header-page-inner'>
		Booking 
	</div>
</div>
<div class="container-fluid">
    <div class="pageContent">
        <div class="panel panel-primary mypanel">
            <div class="panel-body">
                <!-- CONTENT -->
                <div class='transaction-wrapper booking-content'>

                        <div class='col-md-12 no-padding margin-bottom-sm topbuttonsdiv'>
                            <div class='padded-with-border-engraved topbuttonswrapper'>
                                <div class='button-group'>
                                    <div class='button-group-btn active' title='New' id='booking-trans-newbtn'>
                                        <img src="../resources/img/add.png">
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class='booking-inputfields'>
                                				<input type='hidden' id='pgtxnbooking-id'>
                                				<div class='col-lg-2'>
                                                    <div class="form-horizontal">
                                                        <div class="form-group">
                                                        	<div class='col-md-12'>
                                                                <label class='control-label'>Booking No.</label>
                                                                <input type='text' class='form-input form-control transactionnumber'>
                                                            </div>
                                                                
                                                        </div>
                                                    </div>
                                                    <div class="firstprevnextlastbtn">
                                                        <div class="btn-group btn-group-justified btn-group-sm margin-bottom-xs stock-item-refbuttons" role="group" aria-label="...">
                                                            <div class="btn-group" role="group">
                                                                <button type="button" class="btn btn-default stock-item-firstbtn" data-info='first'>
                                                                    <span class="glyphicon glyphicon-fast-backward" aria-hidden="true"></span>
                                                                </button>
                                                            </div>
                                                            <div class="btn-group" role="group">
                                                                <button type="button" class="btn btn-default" data-info='second'>
                                                                    <span class="glyphicon glyphicon-backward" aria-hidden="true"></span>
                                                                </button>
                                                            </div>
                                                            <div class="btn-group" role="group">
                                                                <button type="button" class="btn btn-default" data-info='third'>
                                                                    <span class="glyphicon glyphicon-forward" aria-hidden="true"></span>
                                                                </button>
                                                            </div>
                                                            <div class="btn-group" role="group">
                                                                <button type="button" class="btn btn-default" data-info='fourth'>
                                                                    <span class="glyphicon glyphicon-fast-forward" aria-hidden="true"></span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class='transaction-status-div margin-bottom-xs statusdiv'><br></div>
                                                    <div class='button-group-btn fluid searchbtn active' data-toggle='modal' href='#booking-searchmodallookup'>
                                                        <!--<i class='fa fa-search fa-lg fa-space'></i>-->
                                                        <img src="../resources/img/search.png">&nbsp;&nbsp;Search
                                                    </div>
                                                    <br>
                                                </div>
                                                <div class="col-lg-10">

                                                	<div class='col-md-12 no-padding margin-bottom-xs margin-top-sm'>
							                            <div class="panel-group classictheme margin-bottom-xs" id="booking-panelheader-wrapper" role="tablist" aria-multiselectable="true">

							                                    <div class="panel panel-default">
							                                        <div class="panel-heading" role="tab" data-toggle="collapse" href="#booking-panelheader">
							                                                Header
							                                        </div>
							                                        <div id="booking-panelheader" class="panel-collapse collapse in" role="tabpanel">
							                                            <div class="panel-body">
							                                            		<div class='row'>
								                                                	<div class="col-md-12">
									                                                    <div class="header-errordiv"></div>
									                                                </div>
									                                            </div>
							                                                    <div class="form-horizontal">
							                                                        <div class="col-md-6">
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Origin</label>
							                                                                <div class="col-lg-9">
							                                                                    <select class='form-control form-input booking-origin origindestinationdropdownselect' style='width:100%'></select>
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Destination</label>
							                                                                <div class="col-lg-9">
							                                                                    <select class='form-control form-input booking-destination origindestinationdropdownselect' style='width:100%'></select>
							                                                                </div>
							                                                            </div>
							                                                            
							                                                            
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Pickup Date</label>
							                                                                <div class="col-lg-9">
							                                                                	<div class="input-group">
							                                                            			
							                                                                    	<input type='text' class='form-control booking-pickupdate datepicker'>
							                                                                    	<span class="input-group-addon inputgroupbtn" style='line-height: 0px'>
							                                                            				&nbsp;&nbsp;<input type='checkbox' class='booking-samedaypickupflag'>
							                                                            			</span>
							                                                            			<span class="input-group-addon inputgroupcheckboxlabel" style='line-height: 0px'>Same Day Pick-up
							                                                            			</span>
							                                                                    </div>
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Actual Pickup Date</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control booking-actualpickupdate alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Picked-up by</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control booking-pickupby alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Remarks</label>
							                                                                <div class="col-lg-9">
							                                                                    <textarea class='form-control booking-remarks' rows='7'></textarea>
							                                                                </div>
							                                                            </div>


							                                                           

							                                                            
							                                                        </div>
							                                                        <div class='col-md-6'>
							                                                            
							                                                        	<div class="form-group">
							                                                                <label class='control-label col-lg-3'>Created Date</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control booking-createddate alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Created by</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control booking-createdby alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Posted Date</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control booking-posteddate alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Posted by</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control booking-postedby alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                        	<div class="form-group">
							                                                                <label class='control-label col-lg-3'>Approved Date</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control booking-approveddate alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Approved by</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control booking-approvedby alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>

							                                                            

							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Rejected Date</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control booking-rejecteddate alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Rejected by</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control booking-rejectedby alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Reason</label>
							                                                                <div class="col-lg-9">
							                                                                    <textarea class='form-control booking-reason alwaysdisabled' rows='3' disabled="true"></textarea>
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Supervisor Notified</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control booking-supervisornotified alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Driver Notified</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control booking-drivernotified alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                        </div>
							                                                        
							                                                    </div>
							                                            </div>
							                                        </div>
							                                    </div>
							                            </div>

							                            <div class="panel-group classictheme margin-bottom-xs" id="booking-paneldetails-wrapper" role="tablist" aria-multiselectable="true">

							                                    <div class="panel panel-default">
							                                        <div class="panel-heading" role="tab" data-toggle="collapse" href="#booking-paneldetails">
							                                                Details
							                                        </div>
							                                        <div id="booking-paneldetails" class="panel-collapse collapse in" role="tabpanel">
							                                            <div class="panel-body">
							                                            	<div class='row'>
								                                                	<div class="col-md-12">
									                                                    <div class="detail-errordiv"></div>
									                                                </div>
									                                        </div>
							                                            	<div class='row'>
							                                            		<div class='col-lg-6'>

							                                                        <fieldset>
							                                                        	<legend>Shipper Information</legend>
							                                                        	<div class='form-horizontal'>
							                                                        		<div class="form-group">
																								<label class='control-label col-md-3'>Account No.</label>
																								<div class='col-md-5'>
																									<div class="input-group">
																										<input type='hidden' class='booking-shipper-systemid'>
								    			                                                        <input type='text' class='form-input form-control booking-shipper-accountnumber alwaysdisabled' disabled="true">

								                                                                        <span class="input-group-addon inputgroupbtn">
								                                                                        		<img src="../resources/img/info.png" style='height: 24px; cursor: pointer;' id='booking-shipperinfobtn' title="Shipper Information">
								                                                                                <i class="fa fa-search inputgroupicon inputgroupbtnicon hidden" title='Search for Shipper' id='booking-shipperlookupbtn' data-modal='#booking-shipperlookup'></i>
								                                                                        		
								                                                                        </span>
								                                                                    </div>
																									
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Account Name</label>
																								<div class='col-md-9'>
																									<input type='text' class='form-input form-control booking-shipper-accountname alwaysdisabled' disabled="true">
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Contact</label>
																								<div class='col-md-9'>
																									<input type='text' class='form-input form-control booking-shipper-contact'>
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Phone</label>
																								<div class='col-md-9'>
																									<input type='text' class='form-input form-control booking-shipper-telephone'>
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Mobile</label>
																								<div class='col-md-9'>
																									<input type='text' class='form-input form-control booking-shipper-mobile'>
																								</div>
																							</div>
																							
																							<div class="form-group">
																								<label class='control-label col-md-3'>Company Name</label>
																								<div class='col-md-9'>
																									<input type='text' class='form-input form-control booking-shipper-companyname alwaysdisabled' disabled="true">
																								</div>
																							</div>
																							<br>
																							
																							
																							<div class="form-group">
																								<label class='control-label col-md-3'>Region/Province</label>
																								<div class='col-md-9'>
																									<select class='form-control inputslctfld booking-shipper-province addrdropregion addressregiondropdownselect alwaysdisabled'  disabled="true"></select>
																									
																								</div>
																							</div>
																							
																							<div class="form-group">
																								<label class='control-label col-md-3'>City</label>
																								<div class='col-md-9'>
																									<select class='form-control inputslctfld booking-shipper-city addrdropcity addresscitydropdownselect alwaysdisabled'  disabled="true"></select>
																									
																								</div>
																							</div>
																							
																							
																							<div class="form-group">
																								<label class='control-label col-md-3'>District/Barangay</label>
																								<div class='col-md-9'>
																									<select class='form-control inputslctfld booking-shipper-district addrdropdistrict addressdistrictdropdownselect alwaysdisabled'  disabled="true"></select>
																									
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Zip Code</label>
																								<div class='col-md-9'>
																									<select class='form-control inputslctfld booking-shipper-zipcode addrdropzip addresszipcodedropdownselect alwaysdisabled'  disabled="true"></select>
																									
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Street</label>
																								<div class='col-md-9'>
																								    <textarea class='form-input form-control booking-shipper-street alwaysdisabled' disabled rows='3'></textarea>
																									<!--<input type='text' class='form-input form-control booking-shipper-street alwaysdisabled' disabled="true">-->
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Country</label>
																								<div class='col-md-9'>
																									<select class='form-control countriesdropdownselect booking-shipper-country alwaysdisabled'  disabled="true"></select>
																								</div>
																							</div>
																							<br>

																							<fieldset>
									                                                        	<legend>Package</legend>
									                                                        	<div class='form-horizontal'>
									                                                        		<div class='col-md-5'>
									                                                        			
											                                                        		<div class="form-group">
											                                                        			<div class='col-md-12'>
																													<label class='control-label'>No. of Packages (Estimated)</label>
																													<input type='number' class='form-input form-control booking-numberofpackages text-right'>
																												</div>
																											</div>
																											<div class="form-group">
																												<div class='col-md-12'>
																													<label class='control-label'>Declared Value (Estimated)</label>
																													<input type='number' class='form-input form-control booking-declaredvalue text-right'>
																												</div>
																											</div>
																											<div class="form-group">
																												<div class='col-md-12'>
																													<label class='control-label'>Actual Weight(kg) (Estimated)</label>
																													<input type='number' class='form-input form-control booking-actualweight text-right'>
																												</div>
																											</div>
																											<div class="form-group">
																												<div class='col-md-12'>
																													<label class='control-label'>CBM (Estimated)</label>
																													<input type='number' class='form-input form-control booking-vwcbm text-right'>
																												</div>
																											</div>
																											<div class="form-group hidden">
																												<div class='col-md-12'>
																													<label class='control-label'>Amount</label>
																													<input type='number' class='form-input form-control booking-amount text-right'>
																												</div>
																											</div>
																											<div class="form-group">
											                                                        			<div class='col-md-12'>
																													<label class='control-label'>Unit of Measure</label>
																													<select class='form-control booking-uom uomdropdownselect'></select>
																												</div>
																											</div>
																											
																									</div>
																									<div class='col-md-7'>
																											
											                                                        		<div class="form-group">
											                                                        			<div class='col-md-12'>
																													<label class='control-label'>Services</label>
																													<select class='form-control booking-services servicesdropdownselect'></select>
																												</div>
																											</div>
																											<div class="form-group">
																												<div class='col-md-12'>
																													<label class='control-label'>Mode of Transport</label>
																													<select class='form-control booking-modeoftransport modeoftransportdropdownselect'></select>
																												</div>
																											</div>
																											<div class="form-group">
																												<div class='col-md-12'>
																													<label class='control-label'>Documents</label>
																													<select class='form-control booking-document documentdropdownselect' multiple></select>
																												</div>
																											</div>
																											<div class="form-group">
																												<div class='col-md-12'>
																													<label class='control-label'>Handling Instruction</label>
																													<select class='form-control booking-handlinginstruction handlinginstructiondropdownselect' multiple></select>
																												</div>
																											</div>
																											<div class="form-group">	
																												<div class='col-md-12'>
																													<label class='control-label'>Pay Mode</label>
																													<select class='form-control booking-paymode paymodedropdownselect'></select>
																												</div>
																											</div>
																											
																										
																									</div>



																								</div>

									                                                        </fieldset>

																							
																						</div>

							                                                        </fieldset>

							                                                    </div>
							                                                    <div class='col-lg-6'>
							                                                    	<fieldset class='hidden'>
							                                                        	<legend>Consignee Information</legend>
							                                                        	<div class='form-horizontal'>
							                                                        		<div class="form-group">
																								<label class='control-label col-md-3'>Account No.</label>
																								<div class='col-md-5'>
																									<div class="input-group">
								    			                                                        <input type='hidden' class='booking-consignee-systemid'>
																										<input type='text' class='form-input form-control booking-consignee-accountnumber alwaysdisabled' disabled="true">
																										<span class="input-group-addon inputgroupbtn">
								                                                                                <i class="fa fa-search inputgroupicon hidden inputgroupbtnicon" title='Search for Consignee' id='booking-consigneelookupbtn' data-modal='#booking-consigneelookup'></i>
								                                                                        </span>
								                                                                    </div>
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Account Name</label>
																								<div class='col-md-9'>
																									<input type='text' class='form-input form-control booking-consignee-accountname alwaysdisabled' disabled="true">
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Phone</label>
																								<div class='col-md-5'>
																									<input type='text' class='form-input form-control booking-consignee-telephone'>
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Company Name</label>
																								<div class='col-md-9'>
																									<input type='text' class='form-input form-control booking-consignee-companyname alwaysdisabled' disabled="true">
																								</div>
																							</div>
																							<br>

																							
																							<div class="form-group">
																								<label class='control-label col-md-3'>Region/Province</label>
																								<div class='col-md-9'>
																									<select class='form-control inputslctfld booking-consignee-province addrdropregion addressregiondropdownselect alwaysdisabled' disabled></select>
																									
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>City</label>
																								<div class='col-md-9'>
																									<select class='form-control inputslctfld booking-consignee-city addrdropcity addresscitydropdownselect alwaysdisabled' disabled></select>
																									
																								</div>
																							</div>
																							
																							<div class="form-group">
																								<label class='control-label col-md-3'>District/Barangay</label>
																								<div class='col-md-9'>
																									<select class='form-control inputslctfld booking-consignee-district addrdropdistrict addressdistrictdropdownselect alwaysdisabled' disabled></select>
																									
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Zip Code</label>
																								<div class='col-md-9'>
																									<select class='form-control inputslctfld booking-consignee-zipcode addrdropzip addresszipcodedropdownselect alwaysdisabled' disabled></select>
																									
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Street</label>
																								<div class='col-md-9'>
																								    <textarea class='form-input form-control booking-consignee-street alwaysdisabled' disabled rows='3'></textarea>
																									<!--<input type='text' class='form-input form-control booking-consignee-street alwaysdisabled' disabled>-->
																								</div>
																							</div>
																							
																							<div class="form-group">
																								<label class='control-label col-md-3'>Country</label>
																								<div class='col-md-9'>
																									<select class='form-control countriesdropdownselect booking-consignee-country alwaysdisabled' disabled></select>
																								</div>
																							</div>
																						</div>

							                                                        </fieldset>

							                                                        <fieldset>
																								<legend>Pickup Address</legend>
																								<div class='form-horizontal addressgroupwrapper'>
																									<div class="form-group">
																										<label class='control-label col-md-3'>Region/Province</label>
																										<div class='col-md-9'>
																											<div class="input-group">
										    			                                                       	<select class='form-control inputslctfld booking-shipper-pickupprovince addressregiondropdownselect'></select>

										                                                                        <span class="input-group-addon inputgroupbtn">
										                                                                                <i class="fa fa-search hidden inputgroupbtnicon" id='booking-pickupaddresslookupbtn' title='Search for Pickup Address' data-modal='#booking-shipperpickupaddresslookup'></i>
										                                                                        </span>
										                                                                    </div>
																											
																											
																										</div>
																									</div>
																									
																									<div class="form-group">
																										<label class='control-label col-md-3'>City</label>
																										<div class='col-md-9'>
																											<select class='form-control inputslctfld booking-shipper-pickupcity addresscitydropdownselect'></select>
																											
																										</div>
																									</div>
																									
																									<div class="form-group">
																										<label class='control-label col-md-3'>District/Barangay</label>
																										<div class='col-md-9'>
																											<select class='form-control inputslctfld booking-shipper-pickupdistrict addressdistrictdropdownselect'></select>
																											
																										</div>
																									</div>
																									<div class="form-group">
																										<label class='control-label col-md-3'>Zip Code</label>
																										<div class='col-md-9'>
																											<select class='form-control inputslctfld booking-shipper-pickupzipcode addresszipcodedropdownselect'></select>
																											
																										</div>
																									</div>
																									<div class="form-group">
																										<label class='control-label col-md-3'>Street</label>
																										<div class='col-md-9'>
																										    <textarea class='form-input form-control booking-shipper-pickupstreet' rows='3'></textarea>
										    			                                                    <!--<input type='text' class='form-input form-control booking-shipper-pickupstreet'>-->																											
																										</div>
																									</div>
																									<div class="form-group">
																										<label class='control-label col-md-3'>Country</label>
																										<div class='col-md-9'>
																											<select class='form-control countriesdropdownselect booking-shipper-pickupcountry'></select>
																										</div>
																									</div>
																								</div>
																					</fieldset>
							                                                        

							                                                        <fieldset>
							                                                        	<legend>Vehicle Information</legend>
							                                                        	<div class='form-horizontal'>
							                                                        		<div class="form-group">
							                                                        				<label class='control-label col-lg-3'>Vehicle Type</label>
							                                                        				<div class="col-lg-9">
							                                                        					<select class='form-control booking-vehicletype vehicletypedropdownselect'></select>
							                                                        				</div>
							                                                        		</div>
							                                                        		<div class="form-group">
							                                                        				<label class='control-label col-lg-3'></label>
							                                                        				<div class="col-lg-9">
							                                                        					<input type='text' class='form-input form-control booking-vehicletypetype alwaysdisabled' disabled>
							                                                        				</div>
							                                                        		</div>
							                                                        		<div class="form-group">
							                                                        			<label class='control-label col-lg-3'>Time Ready</label>
							                                                        			<div class="col-lg-9">
							                                                        				<input type='text' class='form-control booking-timeready datetimepicker'>
							                                                        			</div>
							                                                        		</div>
							                                                        		<div class='vehicleinformationsection'>
								                                                        		<div class="form-group">
								                                                        				<label class='control-label col-lg-3'>Plate Number</label>
								                                                        				<div class="col-lg-9">
								                                                        					<select class='form-input form-control booking-platenumber vehicledropdownselect'>
								                                                        					</select>
								                                                        				</div>
								                                                        		</div>
							                                                        		
								                                                        		<div class="form-group">
									                                                                <label class='control-label col-lg-3'>Driver</label>
									                                                                <div class="col-lg-9">
									                                                                    <select class='form-control booking-driver driverdropdownselect'></select>
									                                                                </div>
									                                                            </div>
									                                                            <div class="form-group">
									                                                                <label class='control-label col-lg-3'>Helper</label>
									                                                                <div class="col-lg-9">
									                                                                    <select class='form-control booking-helper helperdropdownselect'></select>
									                                                                </div>
									                                                            </div>
								                                                        	    <div class="form-group">
									                                                                <label class='control-label col-lg-3'>Driver Contact No.</label>
									                                                                <div class="col-lg-9">
									                                                                    <input type='text' class='form-control booking-drivercontactnumber'>
									                                                                </div>
									                                                            </div>
									                                                            
									                                                            
							                                                        		</div>
							                                                        		<div class="form-group">
							                                                        			<label class='control-label col-lg-3'>Bill To</label>
							                                                        			<div class="col-lg-9">
							                                                        				<input type='text' class='form-control booking-billto'>
							                                                        			</div>
							                                                        		</div>
																						</div>

							                                                        </fieldset>

							                                                        <div class='col-md-12'>
							                                                        					<div class="form-group">
																											<label class='control-label'>Shipment Description</label>
																											<textarea class='form-input form-control booking-shipmentdescription' rows='5'></textarea>
																										</div>
																									</div>
							                                                    </div>
							                                                </div>

							                                               
							                                                        
							                                                        
							                                            </div>
							                                        </div>
							                                    </div>


							                            </div>                  
							                        </div>

							                        <div class='col-md-12 no-padding margin-top-xs savecancelbuttonwrapper'>
							                            
							                        </div>

                                                </div>
                              
                        </div>


                        

                        

                    
                </div>
                <!-- CONTENT-END -->
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="booking-searchmodallookup">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Search for Booking
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
	            	<div class="form-horizontal">
	            		<div class='col-md-6'>
	            			<div class="form-group">
			            			<label class='control-label col-md-3'>Status</label>
			            			<div class="col-md-9">
			            				 <select class='form-control form-input bookingsearch-status select2' style='width:100%'>
			            				 		<option value=''></option>
			            				 		<option value='LOGGED'>LOGGED</option>
			            				 		<option value='POSTED'>POSTED</option>
			            				 		<option value='APPROVED'>APPROVED</option>
			            				 		<option value='WAITING FOR RESPONSE'>WAITING FOR RESPONSE</option>
			            				 		<option value='ACKNOWLEDGED'>ACKNOWLEDGED</option>
			            				 		<option value='PICKED UP'>PICKED UP</option>
			            				 		<option value='VOID'>VOID</option>
			            				 </select>
			            			</div>
		            		</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-3'>Origin</label>
			            			<div class="col-md-9">
			            				 <select class='form-control form-input bookingsearch-origin origindestinationdropdownselect' style='width:100%'></select>
			            			</div>
		            		</div>	
		            		<div class="form-group">
			            			<label class='control-label col-md-3'>Destination</label>
			            			<div class="col-md-9">
			            				<select class='form-control form-input bookingsearch-destination origindestinationdropdownselect' style='width:100%'></select>
			            			</div>
		            		</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-3'>Shipper</label>
			            			<div class="col-md-9">
			            				<select class='form-control form-input bookingsearch-shipper shipperdropdownselect' style='width:100%'></select>
			            			</div>
		            		</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-3'>Consignee</label>
			            			<div class="col-md-9">
			            				<select class='form-control form-input bookingsearch-consignee consigneedropdownselect' style='width:100%'></select>
			            			</div>
		            		</div>
		            		
			            </div>
		            	<div class='col-md-6'>
		            		<div class="form-group">
			            			<label class='control-label col-md-4'>Pickup Date From</label>
			            			<div class="col-md-8">
			            				<input type='text' class='form-control bookingsearch-pickupdatefrom datepicker'>
			            			</div>
			            	</div>
			            	<div class="form-group">
			            			<label class='control-label col-md-4'>Pickup Date To</label>
			            			<div class="col-md-8">
			            				<input type='text' class='form-control bookingsearch-pickupdateto datepicker'>
			            			</div>
			            	</div>
			            	<div class="form-group">
	                            <label class='control-label col-lg-4'>Pickup City</label>
	                            <div class="col-lg-8">
	                                 <select class='form-control form-input bookingsearch-city bookingcitydropdownselect'></select>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class='control-label col-lg-4'>Pickup Region</label>
	                            <div class="col-lg-8">
	                                 <select class='form-control form-input bookingsearch-region bookingregiondropdownselect'></select>
	                            </div>
	                        </div>
			            	<div class="form-group">
			            			<div class="col-md-offset-4 col-md-8">
			            				<div class='button-group-btn fluid active' id='bookingsearch-searchbtn'>
	                                        <img src="../resources/img/search.png">&nbsp;&nbsp;Search
	                                    </div>
			            			</div>
			            	</div>
		            	</div>			
	            	</div>
            		
            		<div class='col-md-12'>
            			<br>
		            	<table id='bookingsearch-table'>
							<tbody></tbody>
						</table>
						<br>
					</div>
           	</div>
        </div>
    </div>
</div>

<div class="modal fade" id="booking-shipperlookup">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Shipper Lookup
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='col-md-2'>
            		Double click row to select a shipper.
            	</div>
            	<div class='col-md-10'>
	            	<table id='booking-shipperlookuptbl'>
						<tbody></tbody>

					</table>
				</div>
           	</div>

        </div>
    </div>
</div>


<div class="modal fade" id="booking-shipperpickupaddresslookup">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Pickup Address Lookup
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='col-md-2'>
            		Double click row to select an address.<br> To save a new address, please fill out the following information below then click save.
            	</div>
            	<div class='col-md-10'>
            		
	            	<table id='booking-shipperpickupaddresslookuptbl'>
						<tbody></tbody>
					</table>
					<br>
					<div class='form-horizontal booking-addpickupaddresswrapper addressgroupwrapper hidden'>
            			<input type='hidden' class='booking-shipperpickupaddresslookup-shipperid'>
            			<div class='col-md-12'>
            				<div class='modal-errordiv'></div>
            			</div>
            			<div class='col-md-6'>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Account No.</label>
	            				<div class='col-md-9'>
	            					<input type='text' class='form-input form-control booking-shipperpickupaddresslookup-accountnumber' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Account Name</label>
	            				<div class='col-md-9'>
	            					<input type='text' class='form-input form-control booking-shipperpickupaddresslookup-accountname' disabled="true">
	            				</div>
	            			</div>
	            			
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Region/Province</label>
	            				<div class='col-md-9'>
	            					<select class='form-control inputslctfld booking-shipperpickupaddresslookup-province addrdropregion addressregiondropdownselect'></select>
	            					
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>City</label>
	            				<div class='col-md-9'>
	            					<select class='form-control inputslctfld booking-shipperpickupaddresslookup-city addrdropcity addresscitydropdownselect'></select>
	            					
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>District/Barangay</label>
	            				<div class='col-md-9'>
	            					<select class='form-control inputslctfld booking-shipperpickupaddresslookup-district addrdropdistrict addressdistrictdropdownselect'></select>
	            					
	            				</div>
	            			</div>

	            		</div>
	            		<div class='col-md-6'>
	            			
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Zip Code</label>
	            				<div class='col-md-9'>
	            					<select class='form-control inputslctfld booking-shipperpickupaddresslookup-zipcode addrdropzip addresszipcodedropdownselect'></select>
	            					
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Street</label>
	            				<div class='col-md-9'>
	            					<input type='text' class='form-input form-control booking-shipperpickupaddresslookup-street inputtxtfld'>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Country</label>
	            				<div class='col-md-9'>
	            					<select class='form-control countriesdropdownselect booking-shipperpickupaddresslookup-country inputslctfld'></select>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<div class='col-md-9 col-md-offset-3'>
	            					<div class='button-group-btn fluid active' id='booking-shipperpickupaddress-savebtn'>
                                        <img src="../resources/img/save.png">&nbsp;&nbsp;Save New Address
                                    </div>
	            				</div>
	            			</div>
	            		</div>
            		</div>

				</div>
				<hr>
           	</div>
        </div>
    </div>
</div>

<div class="modal fade" id="booking-consigneelookup">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Consignee Lookup
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='col-md-2'>
            		Double click row to select a consignee.
            	</div>
            	<div class='col-md-10'>
	            	<table id='booking-consigneelookuptbl'>
						<tbody></tbody>
					</table>
				</div>
           	</div>
        </div>
    </div>
</div>

<div class="modal fade" id="booking-updatestattopickedmodal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Update Status 
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='col-md-4'>
            		<br>
            		To update booking status, please provide the following information.
            	</div>
            	<div class='col-md-8'>
            		<div class='form-horizontal'>
            				<input type='hidden' id='booking-updatestattopickedmodal-bookingid'>
            				<div class='modal-errordiv'></div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Booking No.</label>
	            				<div class='col-md-8'>
	            					<input type='text' class='form-input form-control booking-updatestattopickedmodal-bookingnumber' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Status</label>
	            				<div class='col-md-8'>
	            					<select class='form-control booking-updatestattopickedmodal-status bookingstatusdropdownselect'>
	            					</select>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Date</label>
	            				<div class='col-md-8'>
	            					<input type='text' class='form-control booking-updatestattopickedmodal-actualpickupdate datetimepicker'>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Contact Person</label>
	            				<div class='col-md-8'>
	            					<input type='text' class='form-control booking-updatestattopickedmodal-pickedupby'>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Remarks</label>
	            				<div class='col-md-8'>
	            					<textarea class='form-control booking-updatestattopickedmodal-remarks' rows='5'></textarea>
	            				</div>
	            			</div>
	            	</div>
            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='booking-updatestattopickedmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>

<div class="modal fade" id="booking-updatebookingstatusmodal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Update Status 
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='col-md-4'>
            		To update booking status, please provide the following information.
            	</div>
            	<div class='col-md-8'>
            		<div class='form-horizontal'>
            				<input type='hidden' id='booking-updatebookingstatusmodal-bookingid'>
            				<div class='modal-errordiv'></div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Booking No.</label>
	            				<div class='col-md-8'>
	            					<input type='text' class='form-input form-control booking-updatebookingstatusmodal-bookingnumber' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Status*</label>
	            				<div class='col-md-8'>
	            					<select class='form-control booking-updatebookingstatusmodal-status bookingstatusdropdownselect'>
	            					</select>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Date*</label>
	            				<div class='col-md-8'>
	            					<input type='text' class='form-control booking-updatebookingstatusmodal-actualpickupdate datetimepicker'>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Contact Person*</label>
	            				<div class='col-md-8'>
	            					<input type='text' class='form-control booking-updatebookingstatusmodal-pickedupby'>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Plate Number*</label>
	            				<div class='col-md-8'>
	            					<select class='form-control booking-updatebookingstatusmodal-platenumber vehicledropdownselect'></select>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Driver*</label>
	            				<div class='col-md-8'>
	            					<select class='form-control booking-updatebookingstatusmodal-driver driverdropdownselect'></select>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Helper</label>
	            				<div class='col-md-8'>
	            					<select class='form-control booking-updatebookingstatusmodal-helper helperdropdownselect'></select>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Remarks*</label>
	            				<div class='col-md-8'>
	            					<textarea class='form-control booking-updatebookingstatusmodal-remarks' rows='5'></textarea>
	            				</div>
	            			</div>
	            	</div>
            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='booking-updatebookingstatusmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>


<div class="modal fade" id="booking-resetvehicleinformationmodal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Reset Driver/Helper Details 
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='col-md-12'>
            		<div class='form-horizontal'>
            				<input type='hidden' id='booking-resetvehicleinformationmodal-bookingid'>
            				<div class='modal-errordiv'></div>
	            			<div class="form-group">
	            				<label class='control-label col-md-2'>Booking No.</label>
	            				<div class='col-md-10'>
	            					<input type='text' class='form-input form-control booking-resetvehicleinformationmodal-bookingnumber' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-2'>Reason</label>
	            				<div class='col-md-10'>
	            					<textarea class='form-control booking-resetvehicleinformationmodal-remarks' rows='5'></textarea>
	            				</div>
	            			</div>
	            	</div>
            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='booking-resetvehicleinformationmodal-savebtn'>Confirm</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>

<div class="modal fade" id="voidbookingtransactionmodal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Void Booking
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='col-md-4'>
            		<br>
            		To void booking transaction, please provide a reason.
            	</div>
            	<div class='col-md-8'>
            		<div class='form-horizontal'>
            				<input type='hidden' id='voidbookingtransactionmodal-bookingid'>
            				<div class='modal-errordiv'></div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Booking No.</label>
	            				<div class='col-md-8'>
	            					<input type='text' class='form-input form-control voidbookingtransactionmodal-bookingnumber' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Remarks</label>
	            				<div class='col-md-8'>
	            					<textarea class='form-control voidbookingtransactionmodal-remarks' rows='6'></textarea>
	            				</div>
	            			</div>
	            	</div>
            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='voidbookingtransactionmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>



<div class="modal fade" id="bookingshipperinfomodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Shipper Credit Info
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='modal-errordiv'></div>
            	<div class='col-md-12'>
            		<div class='form-horizontal'>
            				<div class="form-group">
	            				<label class='control-label col-md-4'>Account No.</label>
	            				<div class='col-md-7'>
	            					<input type='text' class='form-input form-control bookingshipperinfomodal-accountnumber' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Account Name</label>
	            				<div class='col-md-7'>
	            					<input type='text' class='form-input form-control bookingshipperinfomodal-accountname' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Status</label>
	            				<div class='col-md-7'>
	            					<input type='text' class='form-input form-control bookingshipperinfomodal-status' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Credit Limit</label>
	            				<div class='col-md-4'>
	            					<input type='text' class='form-input form-control bookingshipperinfomodal-creditlimit text-right' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Billed Balance</label>
	            				<div class='col-md-4'> 
	            					<input type='text' class='form-input form-control bookingshipperinfomodal-billedamount text-right' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Unbilled Balance</label>
	            				<div class='col-md-4'> 
	            					<input type='text' class='form-input form-control bookingshipperinfomodal-unbilledamount text-right' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Total Balance</label>
	            				<div class='col-md-4'>
	            					<input type='text' class='form-input form-control bookingshipperinfomodal-balance text-right' disabled="true">
	            				</div>
	            			</div>
	            			
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Credit Balance</label>
	            				<div class='col-md-4'>
	            					<input type='text' class='form-input form-control bookingshipperinfomodal-creditbalance text-right' disabled="true">
	            				</div>
	            			</div>
	            			
	            	</div>
            	</div>
           	</div>
           	<div class='modal-footer'>
           		<br>
           	</div>
        </div>
    </div>
</div>


<div class="modal fade" id="booking-assigndriverdetailsmodal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Assign Driver/Helper Details 
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='modal-errordiv'></div>
            	<div class='col-md-12'>
            		<div class='form-horizontal'>
            				<input type='hidden' id='booking-assigndriverdetailsmodal-bookingid'>
            				
	            			<div class="form-group">
	            				<label class='control-label'>Booking No.</label>
	            				<input type='text' class='form-input form-control booking-assigndriverdetailsmodal-bookingnumber' disabled="true">
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label'>Vehicle Type</label>
	            				<input type='text' class='form-input form-control booking-assigndriverdetailsmodal-vehicletype' disabled="true">
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label'>Driver For</label>
	            				<input type='text' class='form-input form-control booking-assigndriverdetailsmodal-driverfor' disabled="true">
	            				
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label'>Plate Number*</label>
	            				<select class='form-control booking-assigndriverdetailsmodal-platenumber vehicledropdownselect'></select>
	            				
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label'>Driver*</label>
	            				<select class='form-control booking-assigndriverdetailsmodal-driver driverdropdownselect'></select>
	            				
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label'>Helper*</label>
	            				<select class='form-control booking-assigndriverdetailsmodal-helper helperdropdownselect'></select>
	            				
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label'>Driver Contact No.*</label>
	            				<input type='text' class='form-control booking-assigndriverdetailsmodal-drivercontactnumber'>
	            				
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label'>Time Ready*</label>
	            				<input type='text' class='form-control booking-assigndriverdetailsmodal-timeready datetimepicker'>
	            				
	            			</div>
	            	</div>
            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='booking-assigndriverdetailsmodal-savebtn'>Confirm</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>


<div class="modal fade" id="booking-statushistorymodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Status History
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='tabpane-white margin-top-20 margin-bottom-10'>
            		<ul class="nav nav-tabs">
            			<li role="presentation" class="active" data-pane='#booking-statushistorypane' id='booking-statushistorytab'><a href="#">Status</a></li>
            		</ul>
            		<div class='tab-panes'>
            			<div class='pane active' id='booking-statushistorypane'>
            					<table id='booking-statushistorytbl'>
									<tbody></tbody>
								</table>
            			</div>
            		</div>
            	</div>
	            	
           	</div>
        </div>
    </div>
</div>



<script type="text/javascript">
	/**** INITIALIZED *******/
	$(document).ready(function(){
			var tabBK = '#booking-menutabpane';
			var inputfieldsBK = ".booking-inputfields";
			

			//$(tabBK+' .modal-dialog').draggable();
			$(inputfieldsBK+' input,'+inputfieldsBK+' textarea,'+inputfieldsBK+' select').attr('disabled','disabled');
        	$(inputfieldsBK+' .transactionnumber').removeAttr('disabled').focus();
        	$(tabBK+" .select2").select2();
        	var datetoday = new Date();
        	//$(tabBK+' .datepicker').datepicker();
        	$(tabBK+' .datetimepicker').datetimepicker();

        	var dateToday = new Date(); 
			$(tabBK+' .datepicker').datepicker({
			        //minDate: dateToday
			});

       
       		var refBK = <?php echo json_encode($refBK); ?>;
	        if(refBK!=''){
	            getBookingInformation(refBK);
	        }

	        $(tabBK+" .bookingstatusdropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/booking-status.php",
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

	        $(tabBK+" .vehicletypedropdownselect").select2({
		            ajax: {
		                    url: "loadables/dropdown/vehicle-type.php",
		                    dataType: 'json',
		                    delay: 100,
		                    data: function (params) {
		                        return {
		                            q: params.term // search term
		                        };
		                    },
		                    processResults: function (data) {
		                        // parse the results into the format expected by Select2.
		                        // since we are using custom formatting functions we do not need to
		                        // alter the remote JSON data
		                        return {
		                            results: data
		                        };
		                    },
		                    cache: true
		                },
		                minimumInputLength: 0,
		                width:'100%'
		    });

		    $(tabBK+" .vehicledropdownselect").select2({
		            ajax: {
		                    url: "loadables/dropdown/vehicle.php?flag=1&type=",
		                    dataType: 'json',
		                    delay: 100,
		                    data: function (params) {
		                        return {
		                            q: params.term // search term
		                        };
		                    },
		                    processResults: function (data) {
		                        // parse the results into the format expected by Select2.
		                        // since we are using custom formatting functions we do not need to
		                        // alter the remote JSON data
		                        return {
		                            results: data
		                        };
		                    },
		                    cache: true
		                },
		                minimumInputLength: 0,
		                width:'100%'
		    });

	        $(tabBK+" .driverdropdownselect").select2({
		            ajax: {
		                    url: "loadables/dropdown/personnel.php?position=DRIVER",
		                    dataType: 'json',
		                    delay: 100,
		                    data: function (params) {
		                        return {
		                            q: params.term // search term
		                        };
		                    },
		                    processResults: function (data) {
		                        // parse the results into the format expected by Select2.
		                        // since we are using custom formatting functions we do not need to
		                        // alter the remote JSON data
		                        return {
		                            results: data
		                        };
		                    },
		                    cache: true
		                },
		                minimumInputLength: 0,
		                width:'100%'
		    });

		    $(tabBK+" .helperdropdownselect").select2({
		            ajax: {
		                    url: "loadables/dropdown/personnel.php?position=HELPER",
		                    dataType: 'json',
		                    delay: 100,
		                    data: function (params) {
		                        return {
		                            q: params.term // search term
		                        };
		                    },
		                    processResults: function (data) {
		                        // parse the results into the format expected by Select2.
		                        // since we are using custom formatting functions we do not need to
		                        // alter the remote JSON data
		                        return {
		                            results: data
		                        };
		                    },
		                    cache: true
		                },
		                minimumInputLength: 0,
		                width:'100%'
		    });

	        $(tabBK+" .documentdropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/accompanying-documents.php",
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

		    $(tabBK+" .addressdistrictdropdownselect").select2({
		            ajax: {
		                    url: "loadables/dropdown/address-district.php",
		                    dataType: 'json',
		                    delay: 100,
		                    data: function (params) {
		                        return {
		                            q: params.term // search term
		                        };
		                    },
		                    processResults: function (data) {
		                        // parse the results into the format expected by Select2.
		                        // since we are using custom formatting functions we do not need to
		                        // alter the remote JSON data
		                        return {
		                            results: data
		                        };
		                    },
		                    cache: true
		                },
		                minimumInputLength: 0,
		                width:'100%'
		    });

		    $(tabBK+" .addresscitydropdownselect").select2({
		            ajax: {
		                    url: "loadables/dropdown/address-city.php",
		                    dataType: 'json',
		                    delay: 100,
		                    data: function (params) {
		                        return {
		                            q: params.term // search term
		                        };
		                    },
		                    processResults: function (data) {
		                        // parse the results into the format expected by Select2.
		                        // since we are using custom formatting functions we do not need to
		                        // alter the remote JSON data
		                        return {
		                            results: data
		                        };
		                    },
		                    cache: true
		                },
		                minimumInputLength: 0,
		                width:'100%'
		    });

		    $(tabBK+" .addresszipcodedropdownselect").select2({
		            ajax: {
		                    url: "loadables/dropdown/address-zip.php",
		                    dataType: 'json',
		                    delay: 100,
		                    data: function (params) {
		                        return {
		                            q: params.term // search term
		                        };
		                    },
		                    processResults: function (data) {
		                        // parse the results into the format expected by Select2.
		                        // since we are using custom formatting functions we do not need to
		                        // alter the remote JSON data
		                        return {
		                            results: data
		                        };
		                    },
		                    cache: true
		                },
		                minimumInputLength: 0,
		                width:'100%'
		    });

		    $(tabBK+" .addressregiondropdownselect").select2({
		            ajax: {
		                    url: "loadables/dropdown/address-region.php",
		                    dataType: 'json',
		                    delay: 100,
		                    data: function (params) {
		                        return {
		                            q: params.term // search term
		                        };
		                    },
		                    processResults: function (data) {
		                        // parse the results into the format expected by Select2.
		                        // since we are using custom formatting functions we do not need to
		                        // alter the remote JSON data
		                        return {
		                            results: data
		                        };
		                    },
		                    cache: true
		                },
		                minimumInputLength: 0,
		                width:'100%'
		    });


	        $(tabBK+" .origindestinationdropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/origin-destination-port.php",
	                    dataType: 'json',
	                    delay: 100,
	                    data: function (params) {
	                        return {
	                            q: params.term // search term
	                        };
	                    },
	                    processResults: function (data) {
	                        // parse the results into the format expected by Select2.
	                        // since we are using custom formatting functions we do not need to
	                        // alter the remote JSON data
	                        return {
	                            results: data
	                        };
	                    },
	                    cache: true
	                },
	                minimumInputLength: 0,
	                width: '100%'
	    	});

	    	$(tabBK+" .countriesdropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/country.php",
	                    dataType: 'json',
	                    delay: 100,
	                    data: function (params) {
	                        return {
	                            q: params.term // search term
	                        };
	                    },
	                    processResults: function (data) {
	                        // parse the results into the format expected by Select2.
	                        // since we are using custom formatting functions we do not need to
	                        // alter the remote JSON data
	                        return {
	                            results: data
	                        };
	                    },
	                    cache: true
	                },
	                minimumInputLength: 0,
	                width:'100%',
	                width: '100%'
	    	});

	    	$(tabBK+" .modeoftransportdropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/mode-of-transport.php",
	                    dataType: 'json',
	                    delay: 100,
	                    data: function (params) {
	                        return {
	                            q: params.term // search term
	                        };
	                    },
	                    processResults: function (data) {
	                        // parse the results into the format expected by Select2.
	                        // since we are using custom formatting functions we do not need to
	                        // alter the remote JSON data
	                        return {
	                            results: data
	                        };
	                    },
	                    cache: true
	                },
	                minimumInputLength: 0,
	                width: '100%'
	    	});

	    	$(tabBK+" .uomdropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/unit-of-measure.php",
	                    dataType: 'json',
	                    delay: 100,
	                    data: function (params) {
	                        return {
	                            q: params.term // search term
	                        };
	                    },
	                    processResults: function (data) {
	                        // parse the results into the format expected by Select2.
	                        // since we are using custom formatting functions we do not need to
	                        // alter the remote JSON data
	                        return {
	                            results: data
	                        };
	                    },
	                    cache: true
	                },
	                minimumInputLength: 0,
	                width: '100%'
	    	});

	    	$(tabBK+" .servicesdropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/services.php",
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

	    	$(tabBK+" .handlinginstructiondropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/handling-instruction.php",
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

	    	$(tabBK+" .paymodedropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/pay-mode.php",
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

	    	$(tabBK+" .shipperdropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/shipper.php",
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

	    	$(tabBK+" .consigneedropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/consignee.php",
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

	    	$(tabBK+" .bookingcitydropdownselect").select2({
	            ajax: {
	                url: "loadables/dropdown/booking.city.php",
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

	        $(tabBK+" .bookingregiondropdownselect").select2({
	            ajax: {
	                url: "loadables/dropdown/booking.region.php",
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



	    	$(tabBK+" #bookingsearch-table").flexigrid({
				url: 'loadables/ajax/transactions.booking-search.php',
				dataType: 'json',
				colModel : [
						
						{display: 'Booking No.', name : 'booking_number', width : 100, sortable : true, align: 'left'},
						{display: 'Status', name : 'status', width : 100, sortable : true, align: 'left'},
						{display: 'Origin', name : 'origin', width : 200, sortable : true, align: 'left'},
						{display: 'Destination', name : 'destination', width : 200, sortable : true, align: 'left'},
						{display: 'Shipper', name : 'shipper', width : 200, sortable : true, align: 'left'},
						{display: 'Driver', name : 'driver', width : 200, sortable : true, align: 'left'},
						{display: 'Pickup Date', name : 'pickup_date', width : 100, sortable : true, align: 'left'},
						{display: 'Pickup City', name : 'shipper_pickup_city', width : 130, sortable : true, align: 'left'},
						{display: 'Pickup Region', name : 'shipper_pickup_state_province', width : 130, sortable : true, align: 'left'},
						{display: 'Created by', name : 'created_by', width : 150, sortable : true, align: 'left'},
						{display: 'Created Date', name : 'created_date', width : 150, sortable : true, align: 'left'},
						{display: 'System ID', name : 'id', width : 80, sortable : true, align: 'left'}
				],
				
				searchitems : [
						{display: 'Booking Number', name : 'booking_number', isdefault: true},
						{display: 'Origin', name : 'origin'},
						{display: 'Destination', name : 'destination'},
						{display: 'Shipper', name : 'shipper'},
						{display: 'Driver', name : 'driver'}
				],
				sortname: "booking_number",
				sortorder: "desc",
				usepager: true,
				title: "Search Results", 
				useRp: true,
				rp: 15, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 400,
				singleSelect: false,
				disableSelect: true
			});


			$(tabBK+" #booking-shipperlookuptbl").flexigrid({
				url: 'loadables/ajax/transactions.booking.shipper-lookup.php',
				dataType: 'json',
				colModel : [
						{display: 'Account Number', name : 'account_number', width : 150, sortable : true, align: 'left'},
						{display: 'Account Name', name : 'account_name', width : 350, sortable : true, align: 'left'},
						{display: 'Company Name', name : 'company_name', width : 350, sortable : true, align: 'left'}
				],
				
				searchitems : [
						{display: 'Account Name', name : 'account_name', isdefault: true},
						{display: 'Account Number', name : 'account_number'},
						{display: 'Company Name', name : 'company_name'}
				],
				sortname: "account_name",
				sortorder: "asc",
				usepager: true,
				title: "SHIPPER LIST",
				useRp: true,
				rp: 15, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				disableSelect: true,
				//width: 800,
				height: 400,
				singleSelect: false
			});

			$(tabBK+" #booking-shipperpickupaddresslookuptbl").flexigrid({
				url: 'loadables/ajax/transactions.booking.shipper-pickup-address-lookup.php',
				dataType: 'json',
				colModel : [
						{display: 'Street', name : 'pickup_street_address', width : 250, sortable : true, align: 'left'},
						{display: 'District', name : 'pickup_district', width : 120, sortable : true, align: 'left'},
						{display: 'City', name : 'pickup_city', width : 120, sortable : true, align: 'left'},
						{display: 'Region/Province', name : 'pickup_state_province', width : 120, sortable : true, align: 'left'},
						{display: 'Zip Code', name : 'pickup_zip_code', width : 120, sortable : true, align: 'left'},
						{display: 'Country', name : 'pickup_country', width : 120, sortable : true, align: 'left'},
						{display: 'Created_date', name : 'created_date', width : 150, sortable : true, align: 'left', hide: true}
				],
				
				searchitems : [
						{display: 'Street', name : 'pickup_street_address', isdefault: true},
						{display: 'District', name : 'pickup_district'},
						{display: 'City', name : 'pickup_city'},
						{display: 'Region/Province', name : 'pickup_state_province'},
						{display: 'Zip Code', name : 'pickup_zip_code'},
						{display: 'Country', name : 'pickup_country'}
				],
				sortname: "pickup_street_address",
				sortorder: "asc",
				usepager: true,
				title: "PICKUP ADDRESSES",
				useRp: true,
				rp: 10, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				disableSelect: true,
				//width: 800,
				height: 300,
				singleSelect: false
			});

			$(tabBK+" #booking-consigneelookuptbl").flexigrid({
				url: 'loadables/ajax/transactions.booking.consignee-lookup.php',
				dataType: 'json',
				colModel : [
						{display: 'Account Number', name : 'account_number', width : 150, sortable : true, align: 'left'},
						{display: 'Account Name', name : 'account_name', width : 350, sortable : true, align: 'left'},
						{display: 'Company Name', name : 'company_name', width : 350, sortable : true, align: 'left'}
				],
				
				searchitems : [
						{display: 'Account Name', name : 'account_name', isdefault: true},
						{display: 'Account Number', name : 'account_number'},
						{display: 'Company Name', name : 'company_name'}
				],
				sortname: "account_name",
				sortorder: "asc",
				usepager: true,
				title: "CONSIGNEE LIST",
				useRp: true,
				rp: 15, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				disableSelect: true,
				//width: 800,
				height: 400,
				singleSelect: false
			});


			function downloadBookingStatusHistory(){
				var bookingnumber = $(tabBK+' #pgtxnbooking-id').attr('pgtxnbooking-number');
	            window.open("Printouts/excel/transactions.booking-status-history.php?bookingnumber="+bookingnumber);
			}

			$(tabBK+" #booking-statushistorytbl").flexigrid({
				url: 'loadables/ajax/transactions.booking.status-history.php?bookingnumber',
				dataType: 'json',
				colModel : [
				        {display: '', name : 'action', width : 20, sortable : false, align: 'center'},
						{display: 'Status', name : 'status_description', width : 120, sortable : true, align: 'left'},
						{display: 'User', name : 'createdby', width : 200, sortable : true, align: 'left'},
						{display: 'Timestamp', name : 'created_date', width : 150, sortable : true, align: 'left'},
						{display: 'Supervisor', name : 'supervisor', width : 200, sortable : true, align: 'left'},
						{display: 'Supervisor Mobile No.', name : 'supervisor_mobile_number', width : 200, sortable : true, align: 'left'},
						{display: 'Driver', name : 'driver', width : 200, sortable : true, align: 'left'},
						{display: 'Driver Mobile No.', name : 'driver_mobile_number', width : 200, sortable : true, align: 'left'},
						{display: 'Time Ready', name : 'time_ready', width : 200, sortable : true, align: 'left'},
						{display: 'Assigned By', name : 'assignedby', width : 200, sortable : true, align: 'left'},
						{display: 'Contact', name : 'contact', width : 150, sortable : true, align: 'left'},
						{display: 'Date', name : 'date', width : 150, sortable : true, align: 'left'},
						{display: 'Remarks', name : 'remarks', width : 300, sortable : true, align: 'left'}
						
						
				],
				
				searchitems : [
						{display: 'Status', name : 'status_description', isdefault: true},
						{display: 'Driver', name : 'driver'},
						{display: 'Remarks', name : 'remarks'},
						{display: 'User', name : 'createdby'},
						{display: 'Assigne by', name : 'assignedby'}
				],
				sortname: "created_date",
				sortorder: "desc",
				usepager: true,
				buttons : [
                        {name: 'Download', bclass: 'download', onpress : downloadBookingStatusHistory},
                        {separator: true}
                ],
				//title: "LOG",
				useRp: true,
				rp: 20, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				disableSelect: true,
				//width: 800,
				disableSelect: true,
				height: 500,
				singleSelect: false
			});



       

        


        	userAccess();
			
    


			

	});
	



</script>