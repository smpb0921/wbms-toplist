<?php
     include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
    $refWB = isset($_GET['reference'])?escapeString($_GET['reference']):'';
?>
<div class='header-page' >
	<div class='header-page-inner'>
		Bill of Lading 
	</div>
</div>
<div class="container-fluid">
    <div class="pageContent">
        <div class="panel panel-primary mypanel">
            <div class="panel-body">
                <!-- CONTENT -->
                <div class='transaction-wrapper waybill-content'>

                        <div class='col-md-12 no-padding margin-bottom-sm topbuttonsdiv'>
                            <div class='padded-with-border-engraved topbuttonswrapper'>
                                <div class='button-group'>
                                    <div class='button-group-btn active' title='New' id='waybill-trans-newbtn'>
                                        <img src="../resources/img/add.png">
                                    </div>
                                    <div class='button-group-btn active' title='Upload' id='waybill-trans-uploadwaybillstatusupdatebtn'>
                                    	<img src='../resources/img/upload.png'>
                                    </div>
                                    <!--<div class='button-group-btn active' title='Upload' id='waybill-trans-uploadbtn'>
                                        <img src="../resources/img/upload.png">
                                    </div>-->

                                </div>
                            </div>
                        </div>

                        <div class='waybill-inputfields'>
                                				<input type='hidden' id='pgtxnwaybill-id'>
                                				<div class='col-lg-2'>
                                                    <div class="form-horizontal">
                                                        <div class="form-group">
                                                        	<div class='col-md-12'>
                                                                <label class='control-label'>BOL/Tracking No.</label>
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
                                                    <div class='transaction-status-div2 margin-bottom-xs hidden billingflagdiv'><br></div>
                                                    <div class='transaction-status-div3 margin-bottom-xs hidden odzflagdiv'><br></div>
													<div class='button-group-btn fluid searchbtn active' data-toggle='modal' href='#waybill-searchmodallookup'>
                                                        <!--<i class='fa fa-search fa-lg fa-space'></i>-->
                                                        <img src="../resources/img/search.png">&nbsp;&nbsp;Search
                                                    </div>
                                                    <br>
                                                </div>
                                                <div class="col-lg-10">

                                                	<div class='col-md-12 no-padding margin-bottom-xs margin-top-sm'>
							                            <div class="panel-group classictheme margin-bottom-xs" id="waybill-panelheader-wrapper" role="tablist" aria-multiselectable="true">

							                                    <div class="panel panel-default">
							                                        <div class="panel-heading" role="tab" data-toggle="collapse" href="#waybill-panelheader">
							                                                Header
							                                        </div>
							                                        <div id="waybill-panelheader" class="panel-collapse collapse in" role="tabpanel">
							                                            <div class="panel-body">
							                                            		<div class='row'>
								                                                	<div class="col-md-12">
									                                                    <div class="header-errordiv"></div>
									                                                </div>
									                                            </div>
							                                                    <div class="form-horizontal">
							                                                    	<div class='col-md-12'>
							                                                    		<fieldset>
								                                                        	<legend>General Information</legend>
								                                                        	<div class='form-horizontal'>
								                                                        	   	<div class='col-lg-6'>

																									<div class="form-group">
																										<label class='control-label col-md-3'>Shipment Type</label>
																										<div class='col-md-9'>
																											<select class='form-control form-input waybill-shipmenttype addshipmenttypedropdownselect'  style='width:100%'></select>
																												
																										</div>
																									</div>

										                                                        	<div class="form-group">
										                                                                <label class='control-label col-lg-3'>Booking No.</label>
										                                                                <div class="col-lg-9">
										                                                                    <select class='form-control form-input waybill-bookingnumber wbbookingnumberdropdownselect' style='width:100%'></select>
										                                                                </div>
										                                                            </div>
										                                                           
										                                                            
										                                                            <!--<div class="form-group parceltypewrapper hidden">
										                                                                <label class='control-label col-lg-3'>Parcel Type</label>
										                                                                <div class="col-lg-9">
										                                                                    <select class='form-control form-input waybill-parceltype parceltypedropdownselect' style='width:100%'></select>
										                                                                </div>
										                                                            </div>-->
										                                                            <div class="form-group">
										                                                                <label class='control-label col-lg-3'>Origin</label>
										                                                                <div class="col-lg-9">
										                                                                    <select class='form-control form-input waybill-origin origindestinationdropdownselect' style='width:100%'></select>
										                                                                </div>
										                                                            </div>
										                                                            <div class="form-group">
										                                                                <label class='control-label col-lg-3'>Destination</label>
										                                                                <div class="col-lg-9">
										                                                                    <select class='form-control form-input waybill-destination origindestinationdropdownselect' style='width:100%'></select>
										                                                                </div>
										                                                            </div>
										                                                            <div class="form-group">
										                                                                <label class='control-label col-lg-3'>Destination Route</label>
										                                                                <div class="col-lg-9">
										                                                                    <select class='form-control form-input waybill-destinationroute destinationroutedropdownselect' style='width:100%'></select>
										                                                                </div>
										                                                            </div>
										                                                            
										                                                            <div class="form-group">
										                                                                <label class='control-label col-lg-3'>Delivery Date</label>
										                                                                <div class="col-lg-9">
										                                                                    <input type='text' class='form-control waybill-deliverydate datepicker'>
										                                                                </div>
										                                                            </div>
										                                                            <div class="form-group">
										                                                            <label class='control-label col-md-3'>On Hold</label>
										                                                            	<div class='col-md-9'>
										                                                            		<div class="input-group">
										                                                            			<span class="input-group-addon inputgroupbtn" style='line-height: 0px'>
										                                                            				<input type='checkbox' class='waybill-onholdcheckbox'>
										                                                            			</span>
										                                                            			<input type='text' class='form-input form-control waybill-onholdremarks'>

										                                                            			
										                                                            		</div>
										                                                            	</div>
										                                                            </div>
										                                                            <div class="form-group">
										                                                                <label class='control-label col-lg-3'>Remarks</label>
										                                                                <div class="col-lg-9">
										                                                                    <textarea class='form-control waybill-remarks' rows='5'></textarea>
										                                                                </div>
										                                                            </div>
										                                                        </div>
										                                                        <div class='col-lg-6'>
										                                                        	<div class="form-group hidden">
										                                                                <label class='control-label col-lg-3'>Document Date</label>
										                                                                <div class="col-lg-9">
										                                                                    <input type='text' class='form-control waybill-documentdate datepicker'>
										                                                                </div>
										                                                            </div>
										                                                        	<div class="form-group">
										                                                                <label class='control-label col-lg-3'>Created Date</label>
										                                                                <div class="col-lg-9">
										                                                                    <input type='text' class='form-control waybill-createddate alwaysdisabled' disabled="true">
										                                                                </div>
										                                                            </div>
										                                                            <div class="form-group">
										                                                                <label class='control-label col-lg-3'>Created by</label>
										                                                                <div class="col-lg-9">
										                                                                    <input type='text' class='form-control waybill-createdby alwaysdisabled' disabled="true">
										                                                                </div>
										                                                            </div>
										                                                            <div class="form-group">
										                                                                <label class='control-label col-lg-3'>Updated Date</label>
										                                                                <div class="col-lg-9">
										                                                                    <input type='text' class='form-control waybill-updateddate alwaysdisabled' disabled="true">
										                                                                </div>
										                                                            </div>
										                                                            <div class="form-group">
										                                                                <label class='control-label col-lg-3'>Updated by</label>
										                                                                <div class="col-lg-9">
										                                                                    <input type='text' class='form-control waybill-updatedby alwaysdisabled' disabled="true">
										                                                                </div>
										                                                            </div>
										                                                            <div class="form-group">
										                                                                <label class='control-label col-lg-3'>Printed</label>
										                                                                <div class="col-lg-9">
										                                                                    <input type='text' class='form-control waybill-printedflag alwaysdisabled' disabled="true">
										                                                                </div>
										                                                            </div>
										                                                            <div class="form-group">
										                                                                <label class='control-label col-lg-3'>Printed by</label>
										                                                                <div class="col-lg-9">
										                                                                    <input type='text' class='form-control waybill-printedby alwaysdisabled' disabled="true">
										                                                                </div>
										                                                            </div>
										                                                            <div class="form-group">
										                                                                <label class='control-label col-lg-3'>Printed Date</label>
										                                                                <div class="col-lg-9">
										                                                                    <input type='text' class='form-control waybill-printeddate alwaysdisabled' disabled="true">
										                                                                </div>
										                                                            </div>
										                                                            <div class="form-group">
										                                                                <label class='control-label col-lg-3'>Number of Reprint</label>
										                                                                <div class="col-lg-9">
										                                                                    <input type='text' class='form-control waybill-numberofreprint alwaysdisabled' disabled="true">
										                                                                </div>
										                                                            </div>
										                                                            <div class="form-group">
										                                                                <label class='control-label col-lg-3'>Last Status Update Remarks</label>
										                                                                <div class="col-lg-9">
										                                                                    <textarea class='form-control waybill-laststatupdateremarks alwaysdisabled' disabled rows='3'></textarea>
										                                                                </div>
										                                                            </div>
										                                                        	<div class="form-group">
										                                                                <label class='control-label col-lg-3'>Manifest No.</label>
										                                                                <div class="col-lg-9">
										                                                                    <input type='text' class='form-control waybill-manifestnumber alwaysdisabled' disabled="true">
										                                                                </div>
										                                                            </div>
										                                                            <div class="form-group">
										                                                                <label class='control-label col-lg-3'>Invoice No.</label>
										                                                                <div class="col-lg-9">
										                                                                    <input type='text' class='form-control waybill-invoicenumber alwaysdisabled' disabled="true">
										                                                                </div>
										                                                            </div>
										                                                            <div class="form-group">
										                                                                <label class='control-label col-lg-3'>Payment Status</label>
										                                                                <div class="col-lg-9">
										                                                                    <input type='text' class='form-control waybill-paidflag alwaysdisabled' disabled="true">
										                                                                </div>
										                                                            </div>
										                                                            <div class="form-group">
										                                                                <label class='control-label col-lg-3'>Payment Reference</label>
										                                                                <div class="col-lg-9">
										                                                                    <input type='text' class='form-control waybill-paymentreference alwaysdisabled' disabled="true">
										                                                                </div>
										                                                            </div>
										                                                            
										                                                            <div class="form-group">
										                                                                <label class='control-label col-lg-3'>Billing Reference</label>
										                                                                <div class="col-lg-9">
										                                                                    <input type='text' class='form-control waybill-billingreference alwaysdisabled' disabled="true">
										                                                                </div>
										                                                            </div>
																									<div class="form-group">
										                                                                <label class='control-label col-lg-3'>Billing Statement #</label>
										                                                                <div class="col-lg-9">
										                                                                    <input type='text' class='form-control waybill-billingstatement alwaysdisabled' disabled="true">
										                                                                </div>
										                                                            </div>
										                                                        </div>
										                                                    </div>
									                                             		</fieldset>
							                                                    	</div>
							                                                    	
							                                                    	<div class="col-md-12">
							                                                    		<fieldset>
							                                                    			<div class='row'>

									                                                    		<div class='col-md-6'>
										                                                    			<div class="form-group">
											                                                                <label class='control-label col-lg-3'>Pickup Date</label>
											                                                                <div class="col-lg-9">
											                                                                    <input type='text' class='form-control waybill-pickupdate datepicker'>
											                                                                </div>
											                                                            </div>
										                                                    			<div class="form-group">
											                                                        		<label class='control-label col-md-3'>BOL Type</label>
											                                                        		<div class="col-md-9">
											                                                        			<select class='form-control form-input waybill-waybilltype notnullfld select2'>
											                                                        				<option value='PARCEL'>PARCEL</option>
											                                                        				<option value='DOCUMENT'>DOCUMENTS</option>

											                                                        			</select>
											                                                        		</div>
											                                                        	</div>
										                                                        		<div class="form-group 3plwrapper">
											                                                                <label class='control-label col-md-3'>3PL</label>
											                                                                <div class="col-md-9">
											                                                                    <select class='form-control form-input waybill-3pl 3pldropdownselect' style='width:100%'></select>
											                                                                </div>
											                                                            </div>
																										<div class="form-group">
										                                                        			<label class='control-label col-md-3'>MAWBL</label>
										                                                        			<div class="col-md-9">
										                                                        				<input type='text' class='form-control waybill-mawbl' disabled="true">
										                                                        			</div>
										                                                        		</div>

										                                                        		<div class="form-group modeoftransportwrapper">
																											
																												<label class='control-label col-md-3'>Mode of Transport</label>
																												<div class='col-md-9'>
																													<select class='form-control waybill-modeoftransport modeoftransportdropdownselect'></select>
																												</div>
																										</div>
																										<div class="form-group packagedimensionswrapper">
																												<label class='control-label col-md-3'>Package Dimensions</label>
																												<div class='col-md-9'>
											                                                                        <span class="input-group-addon inputgroupbtn pull-left">
											                                                                                <i class="fa fa-search inputgroupicon inputgroupbtnicon" alwaysshow title='View Package Dimensions' id='waybill-packagedimensionsbtn'></i>
											                                                                        </span>
											                                                                    </div>
																										</div>
										                                                        		
																										
																										
									                                                        	</div>
									                                                        	<div class='col-md-6'>
									                                                        			
									                                                        			<div class="form-group numberofpackageswrapper">
																												<label class='control-label col-md-3'>No. of Packages</label>
																												<div class='col-md-9'>
																													<div class="input-group">
																														<input type='number' class='form-input form-control waybill-numberofpackages text-right alwaysdisabled' disabled>
																														<span class="input-group-addon inputgroupbtn wbaddpackagecodebtnwrapper">
																														</span>
																													</div>
																												</div>
																										</div>
																										<div class="form-group">
																											
																												<label class='control-label col-md-3'>Declared Value</label>
																												<div class='col-md-9'>
																													<input type='number' class='form-input form-control waybill-declaredvalue text-right'>
																												</div>
																										</div>
																										<div class="form-group actualweightwrapper">
																												<label class='control-label col-md-3'>Actual Weight(kg)</label>
																												<div class='col-md-9'>
																													<input type='number' class='form-input form-control waybill-actualweight text-right' disabled>
																												</div>
																										</div>

																										<div class="form-group">
																											<label class='control-label col-md-3'>Package/Shipment Description</label>
																											<div class='col-md-9'>
																												<textarea class='form-input form-control waybill-shipmentdescription' rows='4'></textarea>
																											</div>
																										</div>
									                                                        	</div>

									                                                        </div>
							                                                    		</fieldset>
							                                                    	</div>
							                                                        <div class="col-md-6">
							                                                        	
							                                                        	<fieldset>
								                                                        	<legend>Shipper Information</legend>
								                                                        	<div class='form-horizontal'>
																								<div class="form-group">
																									<label class='control-label col-md-3'>Shipment Mode</label>
																									<div class='col-md-9'>
																										<select class='form-control form-input waybill-shipmentmode addshipmentmodedropdownselect'  style='width:100%'></select>
																										
																									</div>
																								</div>
								                                                        		<div class="form-group">
																									<label class='control-label col-md-3'>Account No.</label>
																									<div class='col-md-5'>
																										<div class="input-group">
																											<input type='hidden' class='waybill-shipper-systemid'>
									    			                                                        <input type='text' class='form-input form-control waybill-shipper-accountnumber alwaysdisabled' disabled="true">

									                                                                        <span class="input-group-addon inputgroupbtn">
									                                                                                <i class="fa fa-search inputgroupicon inputgroupbtnicon hidden" title='Search for Shipper' id='waybill-shipperlookupbtn' data-modal='#waybill-shipperlookup'></i>
									                                                                        </span>
									                                                                    </div>
																										
																									</div>
																								</div>
																								<div class="form-group">
																									<label class='control-label col-md-3'>Account Name</label>
																									<div class='col-md-9'>
																										<input type='text' class='form-input form-control waybill-shipper-accountname alwaysdisabled' disabled="true">
																									</div>
																								</div>
																								
																								<div class="form-group">
																									<label class='control-label col-md-3'>Company Name</label>
																									<div class='col-md-9'>
																										<input type='text' class='form-input form-control waybill-shipper-companyname alwaysdisabled' disabled="true">
																									</div>
																								</div>
																								<div class="form-group">
																									<label class='control-label col-md-3'>Contact Person</label>
																									<div class='col-md-9'>
																										<input type='text' class='form-input form-control waybill-shipper-contactperson'>
																									</div>
																								</div>
																								<div class="form-group">
																									<label class='control-label col-md-3'>Contact Number</label>
																									<div class='col-md-9'>
																										<input type='text' class='form-input form-control waybill-shipper-telephone'>
																									</div>
																								</div>
																								<div class="form-group">
																									<label class='control-label col-md-3'>POD Instruction</label>
																									<div class='col-md-9'>
																										<textarea class='form-input form-control waybill-shipper-podinstruction alwaysdisabled' disabled="true" rows='3'>
																										</textarea>
																									</div>
																								</div>
																								<br>
																								
																								
																								<div class="form-group">
																									<label class='control-label col-md-3'>Region/Province</label>
																									<div class='col-md-9'>
																										<select class='form-control inputslctfld waybill-shipper-province addrdropregion addressregiondropdownselect alwaysdisabled' disabled></select>
																										
																									</div>
																								</div>
																								
																								<div class="form-group">
																									<label class='control-label col-md-3'>City</label>
																									<div class='col-md-9'>
																										<select class='form-control inputslctfld waybill-shipper-city addrdropcity addresscitydropdownselect alwaysdisabled' disabled></select>
																										
																									</div>
																								</div>
																								
																								
																								<div class="form-group">
																									<label class='control-label col-md-3'>District/Barangay</label>
																									<div class='col-md-9'>
																										<select class='form-control inputslctfld waybill-shipper-district addrdropdistrict addressdistrictdropdownselect alwaysdisabled' disabled></select>
																										
																									</div>
																								</div>
																								<div class="form-group">
																									<label class='control-label col-md-3'>Zip Code</label>
																									<div class='col-md-9'>
																										<select class='form-control inputslctfld waybill-shipper-zipcode addrdropzip addresszipcodedropdownselect alwaysdisabled' disabled></select>
																										
																									</div>
																								</div>
																								<div class="form-group">
																									<label class='control-label col-md-3'>Street</label>
																									<div class='col-md-9'>
																										<textarea class='form-input form-control waybill-shipper-street alwaysdisabled' disabled rows='3'></textarea>
																										<!--<input type='text' class='form-input form-control waybill-shipper-street alwaysdisabled' disabled>-->
																									</div>
																								</div>
																								<div class="form-group">
																									<label class='control-label col-md-3'>Country</label>
																									<div class='col-md-9'>
																										<select class='form-control countriesdropdownselect waybill-shipper-country alwaysdisabled' disabled></select>
																									</div>
																								</div>
																								<br>

																									

																								<fieldset class='hidden'>
																									<legend>Pickup Address</legend>
																									
																									<div class="form-group">
																										<label class='control-label col-md-3'>Region/Province</label>
																										<div class='col-md-9'>
																											<select class='form-control inputslctfld waybill-shipper-pickupprovince addrdropregion addressregiondropdownselect alwaysdisabled' disabled></select>
																											
																										</div>
																									</div>
																									
																									<div class="form-group">
																										<label class='control-label col-md-3'>City</label>
																										<div class='col-md-9'>
																											<select class='form-control inputslctfld waybill-shipper-pickupcity addrdropcity addresscitydropdownselect alwaysdisabled' disabled></select>
																											
																										</div>
																									</div>
																									
																									
																									<div class="form-group">
																										<label class='control-label col-md-3'>District/Barangay</label>
																										<div class='col-md-9'>
																											<select class='form-control inputslctfld waybill-shipper-pickupdistrict addrdropdistrict addressdistrictdropdownselect alwaysdisabled' disabled></select>
																											
																										</div>
																									</div>
																									<div class="form-group">
																										<label class='control-label col-md-3'>Zip Code</label>
																										<div class='col-md-9'>
																											<select class='form-control inputslctfld waybill-shipper-pickupzipcode addrdropzip addresszipcodedropdownselect alwaysdisabled' disabled></select>
																											
																										</div>
																									</div>
																									<div class="form-group">
																										<label class='control-label col-md-3'>Street</label>
																										<div class='col-md-9'>
																											<div class="input-group">
																											    <textarea class='form-input form-control waybill-shipper-pickupstreet alwaysdisabled' disabled rows='3'></textarea>
										    			                                                       	<!--<input type='text' class='form-input form-control waybill-shipper-pickupstreet alwaysdisabled' disabled>-->

										                                                                        <span class="input-group-addon inputgroupbtn">
										                                                                                <i class="fa fa-search inputgroupbtnicon hidden" id='waybill-pickupaddresslookupbtn' title='Search for Pickup Address' data-modal='#waybill-shipperpickupaddresslookup'></i>
										                                                                        </span>
										                                                                    </div>
																											
																										</div>
																									</div>
																									<div class="form-group">
																										<label class='control-label col-md-3'>Country</label>
																										<div class='col-md-9'>
																											<select class='form-control countriesdropdownselect waybill-shipper-pickupcountry alwaysdisabled' disabled></select>
																										</div>
																									</div>
																								</fieldset>
																							</div>

								                                                        </fieldset>

								                                                        
							                                                            
							                                                            <!--<div class="form-group">
							                                                                <label class='control-label col-lg-3'>Actual Pickup Date</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control waybill-actualpickupdate alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Picked-up by</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control waybill-pickupby alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>-->


							                                                           

							                                                            
							                                                        </div>
							                                                        <div class='col-md-6'>

							                                                        	<div class='form-horizontal'>
									                                                        	<fieldset>
										                                                        	<legend>Consignee Information</legend>
										                                                        	
										                                                        		<div class="form-group">
																											<label class='control-label col-md-3'>Reference</label>
																											<div class='col-md-9'>
																												<input type='text' class='form-input form-control waybill-reference '>
																											</div>
																										</div>
																										<div class="form-group">
																											<label class='control-label col-md-3'>ID No.</label>
																											<div class='col-md-5'>
																												<div class="input-group">
																													<input type='text' class='form-input form-control waybill-consignee-idnumber alwaysdisabled' disabled="true">
											                                                                    </div>
																											</div>
																										</div>
										                                                        		<div class="form-group">
										                                                        			<input type='hidden' class='waybill-consignee-systemid'>
																											<label class='control-label col-md-3'>Account No.</label>
																											<div class='col-md-5'>
																												<div class="input-group">
											    			                                                        
																													<input type='text' class='form-input form-control waybill-consignee-accountnumber alwaysdisabled' disabled="true">
																													<span class="input-group-addon inputgroupbtn">
											                                                                                <i class="fa fa-search inputgroupicon inputgroupbtnicon hidden" title='Search for Consignee' id='waybill-consigneelookupbtn' data-modal='#waybill-consigneelookup'></i>
											                                                                        </span>
											                                                                    </div>
																											</div>
																										</div>
																										<div class="form-group">
																											<label class='control-label col-md-3'>Account Name</label>
																											<div class='col-md-9'>
																												<input type='text' class='form-input form-control waybill-consignee-accountname alwaysdisabled' disabled="true">
																											</div>
																										</div>
																										<div class="form-group">
																											<label class='control-label col-md-3'>Company Name</label>
																											<div class='col-md-9'>
																												<input type='text' class='form-input form-control waybill-consignee-companyname alwaysdisabled' disabled="true">
																											</div>
																										</div>
																										<div class="form-group">
																											<label class='control-label col-md-3'>Contact Number</label>
																											<div class='col-md-5'>
																												<input type='text' class='form-input form-control waybill-consignee-telephone'>
																											</div>
																										</div>
																										<div class="form-group">
																											<label class='control-label col-md-3'>Secondary Recipient</label>
																											<div class='col-md-9'>
																												<input type='text' class='form-input form-control waybill-consignee-secondary alwaysdisabled' disabled="true">
																											</div>
																										</div>	
																										<div class="form-group">
																											<label class='control-label col-md-3'>Region/Province</label>
																											<div class='col-md-9'>
																												<select class='form-control inputslctfld waybill-consignee-province addrdropregion addressregiondropdownselect alwaysdisabled' disabled></select>
																												
																											</div>
																										</div>
																										
																										<div class="form-group">
																											<label class='control-label col-md-3'>City</label>
																											<div class='col-md-9'>
																												<select class='form-control inputslctfld waybill-consignee-city addrdropcity addresscitydropdownselect alwaysdisabled' disabled></select>
																												
																											</div>
																										</div>
																										
																										<div class="form-group">
																											<label class='control-label col-md-3'>District/Barangay</label>
																											<div class='col-md-9'>
																												<select class='form-control inputslctfld waybill-consignee-district addrdropdistrict addressdistrictdropdownselect alwaysdisabled' disabled></select>
																												
																											</div>
																										</div>
																										<div class="form-group">
																											<label class='control-label col-md-3'>Zip Code</label>
																											<div class='col-md-9'>
																												<select class='form-control inputslctfld waybill-consignee-zipcode addrdropzip addresszipcodedropdownselect alwaysdisabled' disabled></select>
																												
																											</div>
																										</div>
																										<div class="form-group">
																											<label class='control-label col-md-3'>Street</label>
																											<div class='col-md-9'>
																												<textarea class='form-input form-control waybill-consignee-street alwaysdisabled' disabled rows='3'></textarea>
																												<!--<input type='text' class='form-input form-control waybill-consignee-street alwaysdisabled' disabled>-->
																											</div>
																										</div>
																										<div class="form-group">
																											<label class='control-label col-md-3'>Country</label>
																											<div class='col-md-9'>
																												<select class='form-control countriesdropdownselect waybill-consignee-country alwaysdisabled' disabled></select>
																											</div>
																										</div>
																										<div class="form-group odaflagwrapper">
																												<label class='control-label col-md-3'>ODZ Flag</label>
																												<div class='col-md-9'>
																													<select class='form-control waybill-odaflag select2 notnullfld'>
																														<option value='0'>No</option>
																														<option value='1'>Yes</option>
																													</select>
																												</div>
																										</div>

																										<br>
																										

																									

										                                                        </fieldset>

								                                                       			<!--<div class="form-group">
									                                                        		<div class='col-md-12'>
									                                                        			<label class='control-label'>Package/Shipment Description</label>
									                                                        			<textarea class='form-input form-control waybill-shipmentdescription' rows='5'></textarea>
									                                                        		</div>
									                                                        	</div>-->

									                                                    </div>

							                                                            

							                                                            
							                                                        </div>
							                                                        
							                                                    </div>
							                                            </div>
							                                        </div>
							                                    </div>
							                            </div>

							                            <div class="panel-group classictheme margin-bottom-xs" id="waybill-paneldetails-wrapper" role="tablist" aria-multiselectable="true">

							                                    <div class="panel panel-default">
							                                        <div class="panel-heading" role="tab" data-toggle="collapse" href="#waybill-paneldetails">
							                                                Details
							                                        </div>
							                                        <div id="waybill-paneldetails" class="panel-collapse collapse in" role="tabpanel">
							                                            <div class="panel-body">
							                                            	<div class='row'>
								                                                	<div class="col-md-12">
									                                                    <div class="detail-errordiv"></div>
									                                                </div>
									                                        </div>

									                                        
							                                            		 
							                                            	
							                                            	<div class='row'>
							                                            		<div class='col-lg-6'>

							                                                       

							                                                        <fieldset>
							                                                        	<legend>Package</legend>
							                                                        	<div class='form-horizontal'>
							                                                        		<div class='col-md-5'>
							                                                        				
									                                                        		
																									
																									<div class="form-group cbmwrapper">
																										<div class='col-md-12'>
																												<label class='control-label'>CBM</label>
										    			                                                        <input type='number' class='form-input form-control  waybill-vwcbm text-right'>
										                                                                        
																								
											
																										</div>
																									</div>
																									<div class="form-group volweightwrapper">
																										<div class='col-md-12'>
																											<label class='control-label'>Vol. Weight</label>
										    			                                                    <input type='number' class='form-input form-control waybill-vw text-right' disabled>

																										</div>
																									</div>
																									
																									<div class="form-group pouchsizewrapper">
																										<div class='col-md-12'>
																											<label class='control-label'>Pouch Size</label>	
																											<select class='form-input form-control waybill-pouchsize pouchsizedropdownselect' style='width:100%'></select>
																								    	</div>
																								    </div>
																									<div class="form-group">
									                                                        			<div class='col-md-12'>
																											<label class='control-label'>Rush Flag</label>
																											<select class='form-control waybill-rushflag select2 notnullfld'>
																												<option value='0'>No</option>
																												<option value='1'>Yes</option>
																											</select>
																										</div>
																									</div>
																									<div class="form-group pulloutflagwrapper">
									                                                        			<div class='col-md-12'>
																											<label class='control-label'>Pull Out Flag</label>
																											<select class='form-control waybill-pulloutflag select2 notnullfld'>
																												<option value='0'>No</option>
																												<option value='1'>Yes</option>
																											</select>
																										</div>
																									</div>
																									<div class="form-group serviceswrapper">
									                                                        			<div class='col-md-12'>
																											<label class='control-label'>Services</label>
																											<select class='form-control waybill-services servicesdropdownselect'></select>
																										</div>
																									</div>


																									
																							</div>
																							<div class='col-md-7'>
																																																		
									                                                        		<div class="form-group expresstransactiontypewrapper hidden">
																										<div class='col-md-12'>
																											<label class='control-label'>Express Transaction Type</label>
																											<select class='form-control waybill-expresstransactiontype expresstransactiontypedropdownselect'></select>
																										</div>
																									</div>
																									
																									<div class="form-group documentswrapper">
																										<div class='col-md-12'>
																											<label class='control-label'>Documents</label>
																											<select class='form-control waybill-document documentdropdownselect' multiple></select>
																										</div>
																									</div>
																									<div class="form-group deliveryinstructionwrapper">
																										<div class='col-md-12'>
																											<label class='control-label'>Delivery Instruction</label>
																											<select class='form-control waybill-deliveryinstruction deliveryinstructiondropdownselect'></select>
																										</div>
																									</div>
																									<div class="form-group hidden">
																										<div class='col-md-12'>
																											<label class='control-label'>Transport Charges</label>
																											<select class='form-control waybill-transportcharges transportchargesdropdownselect'></select>
																										</div>
																									</div>
																									<div class="form-group handlinginstructionwrapper">
																										<div class='col-md-12'>
																											<label class='control-label'>Handling Instruction</label>
																											<select class='form-control waybill-handlinginstruction handlinginstructiondropdownselect' multiple></select>
																										</div>
																									</div>
																									<div class="form-group paymodewrapper">	
																										<div class='col-md-12'>
																											<label class='control-label'>Pay Mode</label>
																											<select class='form-control waybill-paymode paymodedropdownselect'></select>
																										</div>
																									</div>

																									
																									<div class="form-group amountforcollectionwrapper">
																										<div class="col-md-12">
										                                                                	<label class='control-label'>Amount for Collection</label>
										                                                                    <input type='number' class='form-control waybill-amountforcollection text-right'>
										                                                                </div>
									                                                                </div>
																									
																								
																							</div>
																						</div>

							                                                        </fieldset>

							                                                        
							                                                    </div>
							                                                    <div class='col-lg-6'>
							                                                    	

							                                                        <fieldset>
							                                                        	<legend>Other Information</legend>
							                                                        	<div class='form-horizontal'>
							                                                        		<div class="form-group">
							                                                        			<label class='control-label col-md-3'>Agent</label>
							                                                        			<div class="col-md-9">
							                                                        				<select class='form-control form-input waybill-agent agentdropdownselect' style='width:100%'></select>
							                                                        			</div>
							                                                        		</div>
							                                                        		<div class="form-group">
							                                                        			<label class='control-label col-md-3'>Carrier</label>
							                                                        			<div class="col-md-9">
							                                                        				<select class='form-control form-input waybill-carrier carrierdropdownselect' style='width:100%'></select>
							                                                        			</div>
							                                                        		</div>
							                                                        		<div class="form-group">
							                                                        			<label class='control-label col-md-3'>Shipper Rep Name</label>
							                                                        			<div class="col-md-9">
							                                                        				<input type='text' class='form-control waybill-shipperrepname'>
							                                                        			</div>
							                                                        		</div>
							                                                        		<div class="form-group">
																								<label class='control-label col-md-3'>Brand</label>
																								<div class='col-md-9'>
																									<input type='text' class='form-input form-control waybill-brand'>
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Cost Center Code</label>
																								<div class='col-md-9'>
																									<input type='text' class='form-input form-control waybill-costcentercode'>
																								</div>
																							</div>
																							<div class="form-group costcenterwrapper">
																								<label class='control-label col-md-3'>Cost Center</label>
																								<div class='col-md-9'>
																									<input type='text' class='form-input form-control waybill-costcenter'>
																								</div>
																								
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Buyer's Code</label>
																								<div class='col-md-9'>
																									<input type='text' class='form-input form-control waybill-buyercode'>
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Contract No.</label>
																								<div class='col-md-9'>
																									<input type='text' class='form-input form-control waybill-contractnumber'>
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Customer No.</label>
																								<div class='col-md-9'>
																									<input type='text' class='form-input form-control waybill-customernumber'>
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Project</label>
																								<div class='col-md-9'>
																									<input type='text' class='form-input form-control waybill-project'>
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Phase/Parking Slot</label>
																								<div class='col-md-9'>
																									<input type='text' class='form-input form-control waybill-parkingslot'>
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Block/Unit/District</label>
																								<div class='col-md-9'>
																									<input type='text' class='form-input form-control waybill-blockunitdistrict'>
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Lot/Floor</label>
																								<div class='col-md-9'>
																									<input type='text' class='form-input form-control waybill-lotfloor'>
																								</div>
																							</div>
							                                                        		
																						</div>


							                                                        </fieldset>

							                                                        
							                                                       

							                                                        


							                                                        
							                                                    </div>
							                                                </div>

							                                               
							                                                        
							                                                        
							                                            </div>
							                                        </div>
							                                    </div>


							                            </div>   

							                            <div class="panel-group classictheme ratesandotherchargesfldwrapper hidden margin-bottom-xs" id="waybill-panelcharges-wrapper" role="tablist" aria-multiselectable="true">

							                                    <div class="panel panel-default">
							                                        <div class="panel-heading" role="tab" data-toggle="collapse" href="#waybill-panelcharges">
							                                                Charges
							                                        </div>
							                                        <div id="waybill-panelcharges" class="panel-collapse collapse in" role="tabpanel">
							                                            <div class="panel-body">
							                                            	<div class='row'>
							                                            		<div class='col-lg-6'>	
							                                            			<fieldset>
							                                                        	<legend>Regular Charges</legend>
							                                                        	<div class='form-horizontal'>
							                                                        		<div class='col-md-6'>
							                                                        				<div class="form-group">
																										<div class='col-md-12'>
																											<label class='control-label'>Freight Computation</label>
																											<input type='text' class='form-input form-control waybill-freightcomputation text-right alwaysdisabled' disabled>
																										</div>
																									</div>
									                                                        		
																									<div class="form-group">
																										<div class='col-md-12'>
																											<label class='control-label'>Chargeable Weight/Amount</label>
																											<input type='text' class='form-input form-control waybill-chargeableweight text-right alwaysdisabled' disabled>
																										</div>
																									</div>
							                                                        		        <div class="form-group">
																										<div class='col-md-12'>
																											<label class='control-label'>Return Document Fee</label>
																											<input type='text' class='form-input form-control waybill-returndocumentfee text-right alwaysdisabled' disabled>
																										</div>
																									</div>
																									<div class="form-group">
																										<div class='col-md-12'>
																											<label class='control-label'>Waybill Fee</label>
																											<input type='text' class='form-input form-control waybill-waybillfee text-right alwaysdisabled' disabled>
																										</div>
																									</div>
																									<div class="form-group">
																										<div class='col-md-12'>
																											<label class='control-label'>Security Fee</label>
																											<input type='text' class='form-input form-control waybill-securityfee text-right alwaysdisabled' disabled>
																										</div>
																									</div>
																									<div class="form-group">
																										<div class='col-md-12'>
																											<label class='control-label'>Doc Stamp Fee</label>
																											<input type='text' class='form-input form-control waybill-docstampfee text-right alwaysdisabled' disabled>
																										</div>
																									</div>
							                                                        				
																									<div class="form-group hidden">
																										<div class='col-md-12'>
																											<label class='control-label'>Base ODA Charges</label>
																											<input type='text' class='form-input form-control waybill-baseoda text-right alwaysdisabled' disabled>
																										</div>
																									</div>
																									<div class="form-group hidden">
																										<div class='col-md-12'>
																											<label class='control-label'>Shipper ODA Rate (%)</label>
																											<input type='text' class='form-input form-control waybill-shipperoda text-right alwaysdisabled' disabled>
																										</div>
																									</div>
																									<div class="form-group">
																										<div class='col-md-12'>
																											<label class='control-label'>ODA Charges</label>
																											<input type='text' class='form-input form-control waybill-oda text-right alwaysdisabled' disabled>
																										</div>
																									</div>
																									
																									
																									
																							</div>
																							<div class='col-md-6'>
																									<div class="form-group">
																										<div class='col-md-12'>
																											<label class='control-label'>Valuation</label>
																											<input type='text' class='form-input form-control waybill-valuation text-right alwaysdisabled' disabled>
																										</div>
																									</div>
																									<div class="form-group">
																										<div class='col-md-12'>
																											<label class='control-label'>Freight Charges</label>
																											<input type='text' class='form-input form-control waybill-freight text-right alwaysdisabled' disabled>
																										</div>
																									</div>
																									<div class="form-group">
																										<div class='col-md-12'>
																											<label class='control-label'>Insurance Charges</label>
																											<input type='text' class='form-input form-control waybill-insurancerate text-right alwaysdisabled' disabled>
																										</div>
																									</div>
									                                                        		<div class="form-group">
																										<div class='col-md-12'>
																											<label class='control-label'>Fuel Charges</label>
																											<input type= 'text' class='form-input form-control waybill-fuelrate text-right alwaysdisabled' disabled>
																										</div>
																									</div>
																									<div class="form-group">
																										<div class='col-md-12'>
																											<label class='control-label'>Bunker Charges</label>
																											<input type='text' class='form-input form-control waybill-bunkerrate text-right alwaysdisabled' disabled>
																										</div>
																									</div>
																									
																									<div class="form-group">
																										<div class='col-md-12'>
																											<label class='control-label'>Minimum Charges</label>
																											<input type='text' class='form-input form-control waybill-minimumrate text-right alwaysdisabled' disabled>
																										</div>
																									</div>

																									<div class="form-group hidden">
																										<div class='col-md-12'>
																											<label class='control-label'>Pull Out Fee</label>
																											<input type='text' class='form-input form-control waybill-pulloutfee text-right alwaysdisabled' disabled>
																										</div>
																									</div>

																									<div class="form-group">
																										<div class='col-md-12'>
																											<label class='control-label'>Fixed Rate Amount Fee</label>
																											<input type='text' class='form-input form-control waybill-fixedrateamount text-right alwaysdisabled' disabled>
																										</div>
																									</div>
																									<div class="form-group">
																										<div class='col-md-12'>
																											<label class='control-label'>Total Handling Charges</label>
																											<input type='text' class='form-input form-control waybill-totalhandlingcharges text-right alwaysdisabled' disabled>
																										</div>
																									</div>
																									
																								
																							</div>
																						</div>

							                                                        </fieldset>
							                                            		</div>
							                                            		<div class='col-lg-6'>
							                                                        	<fieldset>
							                                                        		<legend>Other Charges</legend>
																							<div class='form-horizontal wbotherchargessectionflds'>
																								<div class="form-group">
																									<label class='control-label col-md-3'>Description</label>
																									<div class='col-md-9'>
																										<select class='form-control inputslctfld othercharges-descriptionfld otherchargesdropdownselect'></select>
																										<!--<input type='text' class='form-input form-control inputtxtfld othercharges-descriptionfld'>-->
																									</div>
																								</div>
																								<div class="form-group">
																									<label class='control-label col-md-3'>Amount</label>
																									<div class='col-md-9'>
																										<input type='number' class='form-input form-control inputtxtfld text-right othercharges-amountfld'>
																									</div>
																								</div>
																								<div class="form-group">
																									<label class='control-label col-md-3'>Vatable</label>
																									<div class='col-md-9'>
																										<select class='form-input form-control inputslctfld othercharges-vatablefld select2'>
																											<option value='YES'>Yes</option>
																											<option value="NO">No</option>
																										</select>
																									</div>
																								</div>
																								<div class="form-group">
																									<label class='control-label col-md-3'></label>
																									<div class='col-md-9 text-right'>
																										<div class='smallbuttons-wrapper'>
																											<button class='btn mybtn othercharges-insertbtn datatablebtn'>
																												<i class='fa fa-xs fa-plus'></i>Add
																											</button>
																											<button class='btn mybtn othercharges-removebtn datatablebtn'>
																											    <i class='fa fa-xs fa-trash'></i>Remove
																											</button>
																											<button class='btn mybtn othercharges-clearbtn datatablebtn'>
																											    <i class='fa fa-xs fa-refresh'></i>Clear
																											</button>
																										</div>
																									</div>
																								</div>
																								<br>
																							</div>
																							
																							<div class='table-xs'>
																								<table class='table table-condensed table-hover pointer table-striped table-bordered text-nowrap mytable gray-template table-font-sm no-side-borders dsfsfsd' id='waybill-otherchargestbl' style='width:100%'>
																				    				<thead>
																				    					<tr>
																				    						<th class='column-nosort column-checkbox text-center'></th>
																				    						<th>DESCRIPTION</th>
																				    						<th>AMOUNT</th>
																				    						<th>VATABLE</th>
																				    					</tr>
																				    				</thead>
																				    				<tbody>
																				    					
																				    				</tbody>
																				    			</table>
																				    		</div>
																						</fieldset>
																						
																			    		<br>
																			    		<div class='form-horizontal'>
																			    				<div class="form-group">
									                                                                <label class='control-label col-lg-3'>Regular Charges</label>
									                                                                <div class="col-lg-5">
									                                                                    <input type='text' class='form-control waybill-regularcharges text-right alwaysdisabled' disabled>
									                                                                </div>
								                                                                </div>
								                                                                <div class="form-group">
									                                                                <label class='control-label col-lg-3'>Other Charges - Vatable</label>
									                                                                <div class="col-lg-5">
									                                                                    <input type='text' class='form-control waybill-otherchargesvatable text-right alwaysdisabled' disabled>
									                                                                </div>
								                                                                </div>

																			    				<div class="form-group">
									                                                                <label class='control-label col-lg-3'>Total Vatable Charges</label>
									                                                                <div class="col-lg-5">
									                                                                    <input type='text' class='form-control waybill-subtotal text-right alwaysdisabled' disabled>
									                                                                </div>
								                                                                </div>

								                                                                <div class="form-group">
									                                                                <label class='control-label col-lg-3'>VAT</label>
									                                                                <div class="col-lg-5">
									                                                                    <input type='text' class='form-control waybill-vat text-right alwaysdisabled' disabled>
									                                                                </div>
								                                                                </div>

								                                                                <div class="form-group">
									                                                                <label class='control-label col-lg-3'>Other Charges - Non Vatable</label>
									                                                                <div class="col-lg-5">
									                                                                    <input type='text' class='form-control waybill-otherchargesnonvatable text-right alwaysdisabled' disabled>
									                                                                </div>
								                                                                </div>
								                                                                
																				    			

								                                                                <div class="form-group">
									                                                                <label class='control-label col-lg-3'>Total Amount</label>
									                                                                <div class="col-lg-7">
										                                                                <div class='input-group'>
										                                                                    <input type='text' class='form-control waybill-totalamount text-right alwaysdisabled' disabled="">
										                                                                    <span class="input-group-addon inputgroupbtn" style='line-height: 0px'>
										                                                                    	&nbsp;&nbsp;
									                                                            				<input type='checkbox' class='waybill-zeroratedcheckbox alwaysdisabled' disabled> <span class='input-group-addon-label' style='font-size: 12px; font-weight: 600'>&nbsp;Zero Rated</span>
									                                                            			</span>
									                                                            		</div>
									                                                                </div>
									                                                            </div>

									                                                            <br>
									                                                            
									                                                            
								                                                            
																			    		</div>
							                                            		</div>
							                                            	</div>
							                                           	</div>
							                                        </div>
							                                    </div>
							                            </div>  

							                            <div class="panel-group classictheme costingfldwrapper hidden margin-bottom-xs" id="waybill-panelcosting-wrapper" role="tablist" aria-multiselectable="true">

							                                    <div class="panel panel-default">
							                                        <div class="panel-heading" role="tab" data-toggle="collapse" href="#waybill-panelcosting">
							                                                Costing
							                                        </div>
							                                        <div id="waybill-panelcosting" class="panel-collapse collapse in" role="tabpanel">
							                                            <div class="panel-body">
							                                            	<div class='row'>
							                                            		<div class='form-horizontal'>
							                                            			<div class='col-lg-6'>	
							                                            				
							                                                        				<div class="form-group">
																										
																											<label class='control-label col-md-3'>Bill Reference</label>
																											<div class='col-md-9'>
																												<input type='text' class='form-input form-control waybill-billreference alwaysdisabled' disabled>
																											</div>
																									</div>
																									<div class="form-group">
																										
																											<label class='control-label col-md-3'>Bill Item No.</label>
																											<div class='col-md-9'>
																												<input type='text' class='form-input form-control waybill-billitemnumber alwaysdisabled' disabled>
																											</div>
																									</div>
							                                                        				<div class="form-group">
																										
																											<label class='control-label col-md-3'>Freight Cost</label>
																											<div class='col-md-9'>
																												<input type='text' class='form-input form-control waybill-freightcost text-right alwaysdisabled' disabled>
																											</div>
																									</div>
									                                                        		
																									

																									<div class="form-group">
																										
																											<label class='control-label col-md-3'>Agent Cost</label>
																											<div class='col-md-9'>
																												<input type='text' class='form-input form-control waybill-agentcost text-right alwaysdisabled' disabled>
																											</div>
																									</div>

																									

																									

								                                            		</div>
								                                            		<div class='col-lg-6'>
								                                            						<div class="form-group">
																										
																											<label class='control-label col-md-3'>Insurance Ref No.</label>
																											<div class='col-md-9'>
																												<input type='text' class='form-input form-control waybill-insurancereference alwaysdisabled' disabled>
																											</div>
																									</div>
								                                                        			<div class="form-group">
																										
																											<label class='control-label col-md-3'>Insurance Amount</label>
																											<div class='col-md-9'>
																												<input type='text' class='form-input form-control waybill-insuranceamount text-right alwaysdisabled' disabled>
																											</div>
																									</div>

																									<div class="form-group">
																										
																											<label class='control-label col-md-3'>Total Other Expenses</label>
																											<div class='col-md-9'>
																												<input type='text' class='form-input form-control waybill-totalcost text-right alwaysdisabled' disabled>
																											</div>
																									</div>

																									
																									<div class="form-group">
																										
																											<label class='control-label col-md-3'>Gross Income</label>
																											<div class='col-md-9'>
																												<input type='text' class='form-input form-control waybill-grossincome text-right alwaysdisabled' disabled>
																											</div>
																									</div>
								                                            		</div>
								                                            		<div class='col-lg-12'>
																            			<br>
																		            	<table id='waybillcostingdetails-table'>
																							<tbody></tbody>
																						</table>
																						<br>
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


<div class="modal fade" id="waybill-searchmodallookup">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Search for Waybill
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
	            	<div class="form-horizontal">
	            		<div class='col-md-6'>
	            			<div class="form-group">
			            			<label class='control-label col-md-3'>Status</label>
			            			<div class="col-md-9">
			            				 <select class='form-control form-input waybillsearch-status waybillstatusdropdownselect' style='width:100%'>
			            				 		
			            				 </select>
			            			</div>
		            		</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-3'>Booking Number</label>
			            			<div class="col-md-9">
			            				<input type='text' class='form-control waybillsearch-bookingnumber'>
			            			</div>
			            	</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-3'>Origin</label>
			            			<div class="col-md-9">
			            				 <select class='form-control form-input waybillsearch-origin origindestinationdropdownselect' style='width:100%'></select>
			            			</div>
		            		</div>	
		            		<div class="form-group">
			            			<label class='control-label col-md-3'>Destination</label>
			            			<div class="col-md-9">
			            				<select class='form-control form-input waybillsearch-destination origindestinationdropdownselect' style='width:100%'></select>
			            			</div>
		            		</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-3'>Destination Route</label>
			            			<div class="col-md-9">
			            				<select class='form-control form-input waybillsearch-destinationroute destinationroutedropdownselect' style='width:100%'></select>
			            			</div>
		            		</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-3'>Shipper</label>
			            			<div class="col-md-9">
			            				<select class='form-control form-input waybillsearch-shipper shipperdropdownselect' style='width:100%'></select>
			            			</div>
		            		</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-3'>Consignee</label>
			            			<div class="col-md-9">
			            				<select class='form-control form-input waybillsearch-consignee consigneedropdownselect' style='width:100%'></select>
			            			</div>
		            		</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-3'>BOL/Tracking No.</label>
			            			<div class="col-md-9">
			            				<input type='text' class='form-control waybillsearch-trackingnumber'>
			            			</div>
			            	</div>
			            	<div class="form-group">
			            			<label class='control-label col-md-3'>Reference</label>
			            			<div class="col-md-9">
			            				<input type='text' class='form-control waybillsearch-reference'>
			            			</div>
			            	</div>
			            	<div class="form-group">
			            			<label class='control-label col-md-3'>MAWBL</label>
			            			<div class="col-md-9">
			            				<input type='text' class='form-control waybillsearch-mawbl'>
			            			</div>
			            	</div>
		            		
			            </div>
		            	<div class='col-md-6'>
		            		<div class="form-group">
			            			<label class='control-label col-md-4'>Manifest Number</label>
			            			<div class="col-md-8">
			            				<input type='text' class='form-control waybillsearch-manifestnumber'>
			            			</div>
			            	</div>
			            	<div class="form-group">
			            			<label class='control-label col-md-4'>Invoice Number</label>
			            			<div class="col-md-8">
			            				<input type='text' class='form-control waybillsearch-invoicenumber'>
			            			</div>
			            	</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-4'>Pickup Date From</label>
			            			<div class="col-md-8">
			            				<input type='text' class='form-control waybillsearch-pickupdatefrom datepicker'>
			            			</div>
			            	</div>
			            	<div class="form-group">
			            			<label class='control-label col-md-4'>Pickup Date To</label>
			            			<div class="col-md-8">
			            				<input type='text' class='form-control waybillsearch-pickupdateto datepicker'>
			            			</div>
			            	</div>
			            	<div class="form-group">
	                            <label class='control-label col-lg-4'>Pickup City</label>
	                            <div class="col-lg-8">
	                                 <select class='form-control form-input waybillsearch-city bookingcitydropdownselect'></select>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class='control-label col-lg-4'>Pickup Region</label>
	                            <div class="col-lg-8">
	                                 <select class='form-control form-input waybillsearch-region bookingregiondropdownselect'></select>
	                            </div>
	                        </div>
	                        <div class="form-group">
			            			<label class='control-label col-md-4'>Billing Reference</label>
			            			<div class="col-md-8">
			            				<input type='text' class='form-control waybillsearch-billingnumber'>
			            			</div>
			            	</div>
			            	
			            	<div class="form-group">
			            			<div class="col-md-offset-4 col-md-8">
			            				<div class='button-group-btn fluid active' id='waybillsearch-searchbtn'>
	                                        <img src="../resources/img/search.png">&nbsp;&nbsp;Search
	                                    </div>
			            			</div>
			            	</div>
		            	</div>			
	            	</div>
            		
            		<div class='col-md-12'>
            			<br>
		            	<table id='waybillsearch-table'>
							<tbody></tbody>
						</table>
						<br>
					</div>
           	</div>
        </div>
    </div>
</div>

<div class="modal fade" id="waybill-shipperlookup">
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
	            	<table id='waybill-shipperlookuptbl'>
						<tbody></tbody>

					</table>
				</div>
           	</div>

        </div>
    </div>
</div>


<div class="modal fade" id="waybill-shipperpickupaddresslookup">
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
            		
	            	<table id='waybill-shipperpickupaddresslookuptbl'>
						<tbody></tbody>
					</table>
					<br>
					<div class='form-horizontal'>
            			<input type='hidden' class='waybill-shipperpickupaddresslookup-shipperid'>
            			<div class='col-md-12'>
            				<div class='modal-errordiv'></div>
            			</div>
            			<div class='col-md-6'>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Account No.</label>
	            				<div class='col-md-9'>
	            					<input type='text' class='form-input form-control waybill-shipperpickupaddresslookup-accountnumber' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Account Name</label>
	            				<div class='col-md-9'>
	            					<input type='text' class='form-input form-control waybill-shipperpickupaddresslookup-accountname' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Street</label>
	            				<div class='col-md-9'>

	            					<input type='text' class='form-input form-control waybill-shipperpickupaddresslookup-street inputtxtfld'>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>District/Barangay</label>
	            				<div class='col-md-9'>
	            					<select class='form-control inputslctfld waybill-shipperpickupaddresslookup-district addrdropdistrict addressdistrictdropdownselect'></select>
	            					
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>City</label>
	            				<div class='col-md-9'>
	            					<select class='form-control inputslctfld waybill-shipperpickupaddresslookup-city addrdropcity addresscitydropdownselect'></select>
	            					
	            				</div>
	            			</div>

	            		</div>
	            		<div class='col-md-6'>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Zip Code</label>
	            				<div class='col-md-9'>
	            					<select class='form-control inputslctfld waybill-shipperpickupaddresslookup-zipcode addrdropzip addresszipcodedropdownselect'></select>
	            					
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Region/Province</label>
	            				<div class='col-md-9'>
	            					<select class='form-control inputslctfld waybill-shipperpickupaddresslookup-province addrdropregion addressregiondropdownselect'></select>
	            					
	            				</div>
	            			</div>
	            			
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Country</label>
	            				<div class='col-md-9'>
	            					<select class='form-control countriesdropdownselect waybill-shipperpickupaddresslookup-country inputslctfld'></select>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<div class='col-md-9 col-md-offset-3'>
	            					<div class='button-group-btn fluid active' id='waybill-shipperpickupaddress-savebtn'>
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

<div class="modal fade" id="waybill-statushistorymodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Status/Billing History
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='tabpane-white margin-top-20 margin-bottom-10'>
            		<ul class="nav nav-tabs">
            			<li role="presentation" class="active" data-pane='#waybill-statushistorypane' id='waybill-statushistorytab'><a href="#">Status</a></li>
            			<li role="presentation" data-pane='#waybill-billinghistorypane' id='waybill-billinghistorytab'><a href="#">Billing</a></li>
            		</ul>
            		<div class='tab-panes'>
            			<div class='pane active' id='waybill-statushistorypane'>
            					<table id='waybill-statushistorytbl'>
									<tbody></tbody>
								</table>
            			</div>
            			<div class='pane' id='waybill-billinghistorypane'>
            					<table id='waybill-billinghistorytbl'>
									<tbody></tbody>
								</table>
            			</div>
            		</div>
            	</div>
	            	
           	</div>
        </div>
    </div>
</div>

<div class="modal fade" id="editwaybillchargesmodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Edit BOL Charges - <span class='editwaybillchargesmodal-targetwaybill'></span>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<!--<div class='col-md-12'>
            		<button class='btn btn-blue2 mybtn pull-right' id='editwaybillchargesmodal-recomputebtn'>Recompute</button>
            	</div>-->
            	<input type='hidden' id='editwaybillchargesmodal-waybillnumber'>
            	<input type='hidden' class="editwaybillchargesmodal-freightcomputation">
            	<input type='hidden' class="editwaybillchargesmodal-minimumrate">
            	<div class='col-md-12'>
            		<div class="pull-right">
						<button class='btn btn-blue2 mybtn' id='editwaybillchargesmodal-savebtn'>Save</button>
						<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
					</div>
            	</div>
            	<fieldset>
            		<legend>Charges</legend>
            		<div class='form-horizontal'>	
            			<div class='col-md-12'>
            				<div class='modal-errordiv'></div>
            			</div>
            			<div class='col-md-6'>
          					<div class="form-group">
            					<div class='col-md-12'>
            						<label class='control-label'>Chargeable Weight/Amount</label>
            						<input type='number' class='form-input form-control editwaybillchargesmodal-chargeableweight ratefld text-right'>
            					</div>
            				</div>
            				<div class="form-group">
            					<div class='col-md-12'>
            						<label class='control-label'>Return Document Fee</label>
            						<input type='number' class='form-input form-control editwaybillchargesmodal-returndocumentfee ratefld text-right'>
            					</div>
            				</div>
            				<div class="form-group">
            					<div class='col-md-12'>
            						<label class='control-label'>Waybill Fee</label>
            						<input type='number' class='form-input form-control editwaybillchargesmodal-waybillfee ratefld text-right'>
            					</div>
            				</div>
            				<div class="form-group">
            					<div class='col-md-12'>
            						<label class='control-label'>Security Fee</label>
            						<input type='number' class='form-input form-control editwaybillchargesmodal-securityfee ratefld text-right'>
            					</div>
            				</div>
            				<div class="form-group">
            					<div class='col-md-12'>
            						<label class='control-label'>Doc Stamp Fee</label>
            						<input type='number' class='form-input form-control editwaybillchargesmodal-docstampfee ratefld text-right'>
            					</div>
            				</div>
            				<div class="form-group">
            					<div class='col-md-12'>
            						<label class='control-label'>ODA Charges</label>
            						<input type='number' class='form-input form-control editwaybillchargesmodal-oda ratefld text-right'>
            					</div>
            				</div>
            				<div class="form-group">
            					<div class='col-md-12'>
            						<label class='control-label'>Valuation</label>
            						<input type='number' class='form-input form-control editwaybillchargesmodal-valuation ratefld text-right'>
            					</div>
            				</div>



            			</div>
            			<div class='col-md-6'>
            				
            				<div class="form-group">
            					<div class='col-md-12'>
            						<label class='control-label'>Freight Charges</label>
            						<input type='number' class='form-input form-control editwaybillchargesmodal-freight ratefld text-right'>
            					</div>
            				</div>
            				<div class="form-group">
            					<div class='col-md-12'>
            						<label class='control-label'>Insurance Charges</label>
            						<input type='number' class='form-input form-control editwaybillchargesmodal-insurancerate ratefld text-right'>
            					</div>
            				</div>
            				<div class="form-group">
            					<div class='col-md-12'>
            						<label class='control-label'>Fuel Charges</label>
            						<input type= 'number' class='form-input form-control editwaybillchargesmodal-fuelrate ratefld text-right'>
            					</div>
            				</div>
            				<div class="form-group">
            					<div class='col-md-12'>
            						<label class='control-label'>Bunker Charges</label>
            						<input type='number' class='form-input form-control editwaybillchargesmodal-bunkerrate ratefld text-right'>
            					</div>
            				</div>

            				<div class="form-group editwaybillchargesmodal-fixedrateamountwrapper">
            					<div class='col-md-12'>
            						<label class='control-label'>Fixed Rate Amount Fee</label>
            						<input type='number' class='form-input form-control editwaybillchargesmodal-fixedrateamount text-right'>
            					</div>
            				</div>
            				<div class="form-group editwaybillchargesmodal-handlinginstructionwrapper">
            					<div class='col-md-12'>
            						<label class='control-label'>Handling Instruction</label>
            						<select class='form-control editwaybillchargesmodal-handlinginstruction handlinginstructiondropdownselect' multiple></select>
            					</div>
            				</div>
            				<div class="form-group editwaybillchargesmodal-totalhandlingchargeswrapper">
            					<div class='col-md-12'>
            						<label class='control-label'>Total Handling Charges</label>
            						<input type='number' class='form-input form-control editwaybillchargesmodal-totalhandlingcharges text-right ratefld'>
            					</div>
            				</div>

            				


            			</div>
            		</div>

            	</fieldset>

            	<fieldset>
            		<legend>Other Charges</legend>
            		<div class='form-horizontal'>
            			<div class='col-md-7'>
            			<div class="form-group">
            				<label class='control-label col-md-3'>Description</label>
            				<div class='col-md-9'>
            					<select class='form-control inputslctfld editwaybillchargesmodal-otherchargesdesc otherchargesdropdownselect'></select>
            				</div>
            			</div>
            			<div class="form-group">
            				<label class='control-label col-md-3'>Amount</label>
            				<div class='col-md-9'>
            					<input type='number' class='form-input form-control inputtxtfld text-right editwaybillchargesmodal-otherchargesamount'>
            				</div>
            			</div>
            			<div class="form-group">
            				<label class='control-label col-md-3'>Vatable</label>
            				<div class='col-md-9'>
            					<select class='form-input form-control inputslctfld editwaybillchargesmodal-otherchargesvatflag select2 alwaysdisabled'>
									<option value='YES'>Yes</option>
            						<option value="NO">No</option>
            					</select>
            				</div>
            			</div>
            			<div class="form-group">
            				<label class='control-label col-md-3'></label>
            				<div class='col-md-9 text-right'>
            					<div class='smallbuttons-wrapper'>
            						<button class='btn mybtn editwaybillchargesmodal-otherchargesinsertbtn datatablebtn'>
            							<i class='fa fa-xs fa-plus'></i>Add
            						</button>
            						<button class='btn mybtn editwaybillchargesmodal-otherchargesremovebtn datatablebtn'>
            							<i class='fa fa-xs fa-trash'></i>Remove
            						</button>
            						<button class='btn mybtn editwaybillchargesmodal-otherchargesclearbtn datatablebtn'>
            							<i class='fa fa-xs fa-refresh'></i>Clear
            						</button>
            					</div>
            				</div>
            			</div>
            			<br>
            			</div>
            		</div>

            		<div class='table-md'>
            			<table class='table table-condensed table-hover pointer table-striped table-bordered text-nowrap mytable gray-template table-font-sm no-side-borders' id='editwaybillchargesmodal-otherchargestbl' style='width:100%'>
            				<thead>
            					<tr>
            						<th class='column-nosort column-checkbox text-center'></th>
            						<th>DESCRIPTION</th>
            						<th>AMOUNT</th>
            						<th>VATABLE</th>
            					</tr>
            				</thead>
            				<tbody>

            				</tbody>
            			</table>
            		</div>
            	</fieldset>

            	<div class='form-horizontal'>
            		<div class="form-group">
            			<label class='control-label col-lg-3'>Regular Charges</label>
            			<div class="col-lg-5">
            				<input type='text' class='form-control editwaybillchargesmodal-regularcharges text-right alwaysdisabled' disabled>
            			</div>
            		</div>
            		<div class="form-group">
            			<label class='control-label col-lg-3'>Other Charges - Vatable</label>
            			<div class="col-lg-5">
            				<input type='text' class='form-control editwaybillchargesmodal-otherchargesvatable text-right alwaysdisabled' disabled>
            			</div>
            		</div>

            		<div class="form-group">
            			<label class='control-label col-lg-3'>Total Vatable Charges</label>
            			<div class="col-lg-5">
            				<input type='text' class='form-control editwaybillchargesmodal-subtotal text-right alwaysdisabled' disabled>
            			</div>
            		</div>

            		<div class="form-group">
            			<label class='control-label col-lg-3'>VAT</label>
            			<div class="col-lg-5">
            				<input type='text' class='form-control editwaybillchargesmodal-vat text-right alwaysdisabled' disabled>
            			</div>
            		</div>

            		<div class="form-group">
            			<label class='control-label col-lg-3'>Other Charges - Non Vatable</label>
            			<div class="col-lg-5">
            				<input type='text' class='form-control editwaybillchargesmodal-otherchargesnonvatable text-right alwaysdisabled' disabled>
            			</div>
            		</div>



            		<div class="form-group">
            			<label class='control-label col-lg-3'>Total Amount</label>
            			<div class="col-lg-5">
            				<div class='input-group'>
            					<span class="input-group-addon inputgroupbtn" style='line-height: 0px'>
            						<input type='checkbox' class='editwaybillchargesmodal-zeroratedcheckbox' > <span class='input-group-addon-label' style='font-size: 12px; font-weight: 600'>&nbsp;Zero Rated</span>
            						&nbsp;&nbsp;
            					</span>
            					<input type='text' class='form-control editwaybillchargesmodal-totalamount text-right alwaysdisabled' disabled="">
            					
            				</div>
            			</div>
            		</div>



            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='editwaybillchargesmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>

<div class="modal fade" id="waybill-consigneelookup">
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
	            	<div class='col-md-10 margin-bottom-sm'>
		            	<table id='waybill-consigneelookuptbl'>
							<tbody></tbody>
						</table>
					</div>
				

					<div class='form-horizontal waybill-addnewconsigneewrapper hidden addressgroupwrapper margin-top-sm'>
						
            			<div class='col-md-12'>
            				<div class='modal-errordiv'></div>
            			</div>
            			<div class='col-md-6'>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Account Name</label>
	            				<div class='col-md-9'>
	            					<input type='text' class='form-input form-control waybill-consigneelookup-accountname inputtxtfld'>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Company Name</label>
	            				<div class='col-md-9'>
	            					<input type='text' class='form-input form-control waybill-consigneelookup-companyname inputtxtfld'>
	            				</div>
	            			</div>
							<div class="form-group">
	            				<label class='control-label col-md-3'>ID Number</label>
	            				<div class='col-md-9'>
	            					<input type='text' class='form-input form-control waybill-consigneelookup-idnumber inputtxtfld'>
	            				</div>
	            			</div>

	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Default Contact</label>
	            				<div class='col-md-9'>
	            					<input type='text' class='form-input form-control waybill-consigneelookup-contact inputtxtfld'>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Default Tel</label>
	            				<div class='col-md-9'>
	            					<input type='text' class='form-input form-control waybill-consigneelookup-telnumber inputtxtfld'>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Default Mobile</label>
	            				<div class='col-md-9'>
	            					<input type='text' class='form-input form-control waybill-consigneelookup-mobile inputtxtfld'>
	            				</div>
	            			</div>
	            			
	            			

	            		</div>
	            		<div class='col-md-6'>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Region/Province</label>
	            				<div class='col-md-9'>
	            					<select class='form-control inputslctfld waybill-consigneelookup-region addressregiondropdownselect addrdropregion'></select>
	            					
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>City</label>
	            				<div class='col-md-9'> 
	            					<select class='form-control inputslctfld waybill-consigneelookup-city addrdropcity addresscitydropdownselect'></select>
	            					<!--<input type='text' class='form-input form-control waybill-consigneelookup-city inputtxtfld'>-->
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>District/Barangay</label>
	            				<div class='col-md-9'>
	            					<select class='form-control inputslctfld waybill-consigneelookup-district addrdropdistrict addressdistrictdropdownselect'></select>
	            					<!--<input type='text' class='form-input form-control waybill-consigneelookup-district inputtxtfld'>-->
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Zip Code</label>
	            				<div class='col-md-9'>
	            					<select class='form-control inputslctfld waybill-consigneelookup-zipcode addrdropzip addresszipcodedropdownselect'></select>
	            					<!--<input type='text' class='form-input form-control waybill-consigneelookup-zipcode inputtxtfld'>-->
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Street</label>
	            				<div class='col-md-9'>
	            					<input type='text' class='form-input form-control waybill-consigneelookup-street inputtxtfld'>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Country</label>
	            				<div class='col-md-9'>
	            					<select class='form-control countriesdropdownselect waybill-consigneelookup-country inputslctfld'>
	            						<option value='Philippines' selected>Philippines</option>
	            					</select>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<div class='col-md-9 col-md-offset-3'>
	            					<div class='button-group-btn fluid active' id='waybill-consigneelookup-savebtn'>
                                        <img src="../resources/img/save.png">&nbsp;&nbsp;Save New Consignee
                                    </div>
	            				</div>
	            			</div>
	            		</div>
            		</div>
           	</div>
        </div>
    </div>
</div>

<div class="modal fade" id="waybill-updatestatusmodal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Update Status 
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='col-md-3'>
            		To update waybill, please select status and provide corresponding remarks.
            	</div>
            	<div class='col-md-9'>
            		<div class='form-horizontal'>
            				<input type='hidden' id='waybill-updatestatusmodal-waybillid'>
            				<div class='modal-errordiv'></div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>BOL/Tracking No.</label>
	            				<div class='col-md-9'>
	            					<input type='text' class='form-input form-control waybill-updatestatusmodal-waybillnumber' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Status</label>
	            				<div class='col-md-9'>
	            					<select class='form-control waybill-updatestatusmodal-status statusdropdownselect'></select>
	            				</div>
	            			</div>
	            			<div class="form-group hidden receivedbywrapper">
	            				<label class='control-label col-md-3'>Received By</label>
	            				<div class='col-md-9'>
	            					<input type='text' class='form-input form-control waybill-updatestatusmodal-receivedby'>
	            				</div>
	            			</div>
	            			<div class="form-group hidden receiveddatewrapper">
	            				<label class='control-label col-md-3'>Received Date/Time</label>
	            				<div class='col-md-9'>
	            					<input type='text' class='form-input form-control waybill-updatestatusmodal-receiveddate datetimepicker'>
	            				</div>
	            			</div>
	            			<div class="form-group hidden personnelwrapper">
	            				<label class='control-label col-md-3'>Courier</label>
	            				<div class='col-md-9'>
	            					<select class='form-input form-control waybill-updatestatusmodal-personnel personneldropdownselect'></select>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Remarks</label>
	            				<div class='col-md-9'>
	            					<textarea class='form-control waybill-updatestatusmodal-remarks' rows='8'></textarea>
	            				</div>
	            			</div>
	            	</div>
            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='waybill-updatestatusmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>

<div class="modal fade" id="editwaybillcostingmodal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Edit Costing
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	
            		<div class='form-horizontal'>
            			<div class='col-md-12'>
        					<div class='modal-errordiv'></div>
        				</div>
            			<div class='col-md-6'>
            				<div class="form-group">
	            				<label class='control-label col-md-4'>Bill Reference</label>
	            				<div class='col-md-8'>
	            					<input type='text' class='form-input form-control editwaybillcostingmodal-billreference'>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Freight Cost</label>
	            				<div class='col-md-8'>
	            					<input type='number' class='form-input form-control editwaybillcostingmodal-freightcost text-right'>
	            				</div>
	            			</div>
	            			
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Agent Cost</label>
	            				<div class='col-md-8'>
	            					<input type='number' class='form-input form-control editwaybillcostingmodal-agentcost text-right'>
	            				</div>
	            			</div>
		            	</div>
		            	<div class='col-md-6'>	
		            		<div class="form-group">
	            				<label class='control-label col-md-4'>Bill Item No.</label>
	            				<div class='col-md-8'>
	            					<input type='text' class='form-input form-control editwaybillcostingmodal-billitemnumber'>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Insurance Amount</label>
	            				<div class='col-md-8'>
	            					<input type='number' class='form-input form-control editwaybillcostingmodal-insuranceamount text-right'>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Insurance Ref No.</label>
	            				<div class='col-md-8'>
	            					<input type='text' class='form-input form-control editwaybillcostingmodal-insurancereference'>
	            				</div>
	            			</div>
		            	</div>
	            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='editwaybillcostingmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>


<div class="modal fade" id="voidwaybilltransactionmodal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Void BOL Transaction
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='col-md-3'>
            		<br>
            		To void BOL transaction, please provide a reason.
            	</div>
            	<div class='col-md-9'>
            		<div class='form-horizontal'>
            				<input type='hidden' id='voidwaybilltransactionmodal-waybillid'>
            				<div class='modal-errordiv'></div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>BOL No.</label>
	            				<div class='col-md-9'>
	            					<input type='text' class='form-input form-control voidwaybilltransactionmodal-waybillnumber' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Remarks</label>
	            				<div class='col-md-9'>
	            					<textarea class='form-control voidwaybilltransactionmodal-remarks' rows='6'></textarea>
	            				</div>
	            			</div>
	            	</div>
            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='voidwaybilltransactionmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>

<div class="modal fade" id="waybillprintingmodal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Print
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='col-md-12'>
            		<div class='form-horizontal'>
            				<input type='hidden' id='waybillprintingmodal-waybillid'>
            				<div class='modal-errordiv'></div>
	            			<div class="form-group">
	            				<label class='control-label'>BOL No. (System Generated)</label>
	            				<input type='text' class='form-input form-control waybillprintingmodal-waybillnumber' disabled="true">
	            				
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label'>BOL No.</label>
	            				<input type='text' class='form-input form-control waybillprintingmodal-bolnumber'>
	            				
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label'>Form Type</label>
	            				<select class='form-control waybillprintingmodal-formtype select2'>
	            					<option value='INTERNAL'>Internal</option>
	            					<option value='INTERNAL-ALT'>Internal - RTS</option>
	            					<option value='EXTERNAL'>External</option>
	            					<option value='EXTERNAL-ALT'>External - RTS</option>
	            					<option value='DR'>Delivery Receipt</option>
	            					<option value='DR-ALT'>Delivery Receipt - RTS</option>
									<option value='TRANS-BOL-ORIG'>BOL Original</option>
	            				</select>
	            				
	            			</div>

	            			<div class="form-group waybillprintingmodal3plwrapper hidden">
	            				<label class='control-label'>3PL Form</label>
	            				<select class='form-control waybillprintingmodal-form 3pldropdownselect'>
	            				</select>
	            			</div>

	            			<div class="form-group waybillprintingmodalprintoutremarkswrapper">
	            				<label class='control-label'>Printout Remarks</label>
	            				<textarea class='form-control waybillprintingmodal-remarks' rows='6'>
	            				</textarea>
	            			</div>

	            			
	            	</div>
            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
           			<button class='btn btn-blue2 mybtn' id='waybillprintingmodal-printbtn'>Print</button>
					<button class='btn btn-blue2 mybtn' id='waybillprintingmodal-savebtn'>Preview</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>
<iframe style="dispaly:none;" id='WBprintpdfiframe' class="hidden" width="0px" height="0px"></iframe>

<div class="modal fade" id="waybill-packagedimensionsmodal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Input Package Dimensions
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='form-horizontal packagedimensionsmodalflds hidden'>
            		<div class='row'>
            		<div class='col-md-6'>
            			<div class="form-group">
	            			<label class='control-label col-md-3'>Quantity</label>
	            			<div class='col-md-9'>
	            				<input type='number' class='form-input form-control inputtxtfld text-right packagedimensionsmodalflds-quantity'>
	            			</div>
	            		</div>
	            		<div class="form-group">
	            			<label class='control-label col-md-3'>Uom</label>
	            			<div class='col-md-9'>
	            				<select class='form-input form-control inputslctfld text-right packagedimensionsmodalflds-uom uomdropdownselect'>
	            				</select>
	            			</div>
	            		</div>
	            		<div class="form-group">
	            			<label class='control-label col-md-3'>Actual Weight</label>
	            			<div class='col-md-9'>
	            				<input type='text' class='form-input form-control inputtxtfld text-right packagedimensionsmodalflds-actualweight'>
	            			</div>
	            		</div>
	            		<div class="form-group">
	            			<label class='control-label col-md-3'>Length(cm)</label>
	            			<div class='col-md-9'>
	            				<input type='text' class='form-input form-control inputtxtfld text-right packagedimensionsmodalflds-length'>
	            			</div>
	            		</div>
            			
	            		
            		</div>
            		<div class='col-md-6'>
            			
	            		
	            		<div class="form-group">
	            			<label class='control-label col-md-3'>Width(cm)</label>
	            			<div class='col-md-9'>
	            				<input type='text' class='form-input form-control inputtxtfld text-right packagedimensionsmodalflds-width'>
	            			</div>
	            		</div>
	            		<div class="form-group">
	            			<label class='control-label col-md-3'>Height(cm)</label>
	            			<div class='col-md-9'>
	            				<input type='text' class='form-input form-control inputtxtfld text-right packagedimensionsmodalflds-height'>
	            			</div>
	            		</div>
	            		<div class="form-group">
	            			<label class='control-label col-md-3'>CBM</label>
	            			<div class='col-md-9'>
	            				<input type='text' class='form-input form-control inputtxtfld text-right packagedimensionsmodalflds-cbm'>
	            			</div>
	            		</div>
	            		<div class="form-group">
	            			<label class='control-label col-md-3'>Vol. Weight </label>
	            			<div class='col-md-9'>
	            				<input type='text' class='form-input form-control inputtxtfld text-right packagedimensionsmodalflds-volweight'>
	            			</div>
	            		</div>

	            		<div class="form-group">
	            			<label class='control-label col-md-3'></label>
	            			<div class='col-md-9 text-right'>
	            				<div class='smallbuttons-wrapper'>
	            					<button class='btn mybtn packagedimensionsmodalflds-insertbtn datatablebtn'>
	            						<i class='fa fa-xs fa-plus'></i>Add
	            					</button>
	            					<button class='btn mybtn packagedimensionsmodalflds-removebtn datatablebtn'>
	            						<i class='fa fa-xs fa-trash'></i>Remove
	            					</button>
	            					<button class='btn mybtn packagedimensionsmodalflds-clearbtn datatablebtn'>
	            						<i class='fa fa-xs fa-refresh'></i>Clear
	            					</button>
	            				</div>
	            				<br>
	            			</div>
	            		</div>
	            		
            		</div>
            		</div>
            		
            	</div>

            	<div class='table-sm'>

            		<table class='table table-condensed table-hover pointer table-striped table-bordered text-nowrap mytable gray-template table-font-sm no-side-borders' id='waybill-packagedimensionsmodaltbl' style='width:100%'>
            			<thead>
            				<tr>
            					<th class='column-nosort column-checkbox text-center'></th>
            					<th>QUANTITY</th>
            					<th>UOM</th>
            					<th>ACTUAL WEIGHT</th>
            					<th>LENGTH</th>
            					<th>WIDTH</th>
            					<th>HEIGHT</th>
            					<th>VOL. WEIGHT</th>
            					<th>CBM</th>
            				</tr>
            			</thead>
            			<tbody>

            			</tbody>
            		</table>
            	</div>
           	</div>
           
        </div>
    </div>
</div>


<div class="modal fade" id="waybill-addpackagecodemodal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Add Package Code
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='form-horizontal addpackagecodemodalflds'>
	            	<div class='row'>
	            		<div class='col-md-6'>
	            			<div class="form-group">
		            			<label class='control-label col-md-3'>Code</label>
		            			<div class='col-md-9'>
		            				<input type='text' class='form-input form-control inputtxtfld addpackagecodemodal-code'>
		            			</div>
		            		</div>
		            		<div class="form-group">
		            			<label class='control-label col-md-3'></label>
		            			<div class='col-md-9 text-right'>
		            				<div class='smallbuttons-wrapper'>
		            					<button class='btn mybtn addpackagecodemodal-insertbtn datatablebtn'>
		            						<i class='fa fa-xs fa-plus'></i>Add
		            					</button>
		            					<button class='btn mybtn addpackagecodemodal-removebtn datatablebtn'>
		            						<i class='fa fa-xs fa-trash'></i>Remove
		            					</button>
		            				</div>
		            				<br>
		            			</div>
		            		</div>
	            		</div>
	            	</div>
            		
            	</div>

            	<div class='table-sm'>

            		<table class='table table-condensed table-hover pointer table-striped table-bordered text-nowrap mytable gray-template table-font-sm no-side-borders' id='waybill-packagecodemodaltbl' style='width:100%'>
            			<thead>
            				<tr>
            					<th class='column-nosort column-checkbox text-center'></th>
            					<th>CODE</th>
            					<th class='column-nosort'>DATE ADDED</th>
            					<th class='column-nosort'>ADDED BY</th>
            				</tr>
            			</thead>
            			<tbody>

            			</tbody>
            		</table>
            	</div>
           	</div>
        </div>
    </div>
</div>

<div class="modal fade" id="togglebillingflagmodal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Change Billing Flag
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='modal-errordiv'></div>
            	<div class='col-md-12'>
            		<div class='form-horizontal'>
            				<input type='hidden' id='togglebillingflagmodal-id'>
            				
	            			<div class="form-group">
	            				<label class='control-label'>BOL No.</label>
	            				<input type='text' class='form-input form-control togglebillingflagmodal-txnnumber' disabled="true">
	            				
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label'>Billed Flag</label>
	            					<select class='form-control togglebillingflagmodal-billingflag select2' style='width:100%'>
	            						<option value='0'>No</option>
	            						<option value='1'>Yes</option>
	            					</select>
	            			</div>
	            			<div class="form-group billingreferencewrapper hidden">
	            				<label class='control-label'>Reference</label>
	            				<input type='text' class='form-input form-control togglebillingflagmodal-billingreference'>
	            				
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label '>Remarks</label>
	            					<textarea class='form-control togglebillingflagmodal-remarks' rows='6'></textarea>
	            				
	            			</div>
	            	</div>
            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='togglebillingflagmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>

<div class="modal fade" id="togglewbpaidflagmodal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Update Payment Status
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='modal-errordiv'></div>
            	<div class='col-md-12'>
            		<div class='form-horizontal'>
            				
            				<div class="form-group">
	            				<label class='control-label'>Type</label>
	            					<select class='form-control togglewbpaidflagmodal-type select2' style='width:100%'>
	            						<option value='NON-VARIOUS'>Selected BOL</option>
	            						<option value='VARIOUS'>Various</option>
	            						
	            					</select>
	            			</div>
	            			<div class="form-group hidden">
	            				<label class='control-label'>BOL No.</label>
	            				<input type='text' class='form-input form-control togglewbpaidflagmodal-txnnumber'>
	            				
	            			</div>
	            			<div class="form-group hidden">
	            				<label class='control-label'>MAWBL</label>
	            				<input type='text' class='form-input form-control togglewbpaidflagmodal-mawbl'>
	            				
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label'>Payment Status</label>
	            					<select class='form-control togglewbpaidflagmodal-paidflag select2' style='width:100%'>
	            						<option value='0'>Unpaid</option>
	            						<option value='1'>Paid</option>
	            					</select>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label'>Reference</label>
	            				<input type='text' class='form-input form-control togglewbpaidflagmodal-paymentreference'>
	            				
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label '>Remarks</label>
	            					<textarea class='form-control togglewbpaidflagmodal-remarks' rows='6'></textarea>
	            			</div>
	            	</div>
            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='togglewbpaidflagmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>


<div class="modal fade" id="resetprintcountermodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Reset Print Counter
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            		<div class='modal-errordiv'></div>
            	
            		<div class='form-horizontal'>
            				<input type='hidden' id='resetprintcountermodal-waybillid'>
            				
	            			<div class="form-group">
	            				<label class='control-label col-md-1'>Waybill</label>
	            				<div class='col-md-4'>
	            					<input type='text' class='form-input form-control resetprintcountermodal-txnnumber' disabled="true">
	            				</div>
	            				
	            			</div>
	            			<div class="form-group">
		            			<div class='col-md-12'>
		            				<label class='control-label '>Remarks</label>
		            				<textarea class='form-control resetprintcountermodal-remarks' rows='6'></textarea>
	            				</div>
	            			</div>
	            	</div>
            	
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='resetprintcountermodal-savebtn'>Confirm</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>



<div class="modal fade" id="waybill-uploadtransactionfilemodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Upload Waybill
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action='../scripts/waybill-transaction-upload.php' method='post' id='waybill-uploadtransactionfileform'  enctype='multipart/form-data' target='waybilluploadtransactionlogframe'>
                    <div class='col-md-4'>
                        Please make sure to follow the right format. Waybill Transactions with missing/incorrect details will not be uploaded in the system.
                        Click <a class='pointer' id='waybill-downloadtransactionfiletemplatebtn' href='../file-templates/waybill-transaction-template.xlsx'>here</a> to download file template.
                    </div>
                    <div class='col-md-offset-1 col-md-6'>
                        <div class="form-group">
                            <label class='control-label'>Select an Excel File</label>
                            <input type='file' class='form-control waybilluploadtransactionfile' name='waybilluploadtransactionfile'>
                        </div>
                    </div>
                </form>
                <br>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button class='btn btn-blue2 mybtn' id='waybill-uploadtransactionfilemodal-uploadbtn'>Upload</button>
                    <button class='btn btn-blue2 mybtn modal-cancelbtn' >Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="waybill-uploadtransactionlogmodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Uploading Waybill...
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <iframe id="waybilluploadtransactionlogframe" name="waybilluploadtransactionlogframe" height="600" width="100%" frameborder="0" scrolling="yes" style='background: #fff'></iframe>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="deletewaybillstatushistorymodal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Delete 
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='col-md-12'>
            		<div class='form-horizontal'>
            				<input type='hidden' id='szoiFROkslsfrohvrxpojdmslrp'>
            				<div class='modal-errordiv'></div>
	            			<div class="form-group">
	            				<label class='control-label'>Status</label>
	            				<input type='text' class='form-input form-control deletewaybillstatushistorymodal-status' disabled="true">
	            				
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label'>Remarks</label>
	            				<textarea class='form-control deletewaybillstatushistorymodal-remarks' rows='6'></textarea>
	            				
	            			</div>
	            	</div>
            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='deletewaybillstatushistorymodal-deletebtn'>Delete</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>


<div class="modal fade" id="uploadwaybillstatusupdatemodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Upload
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action='../scripts/waybill-status-update-upload.php' method='post' id='uploadwaybillstatusupdatemodal-form'  enctype='multipart/form-data' target='uploadwaybillstatusupdatelogframe'>
                    <div class='col-md-4'>
                        Please make sure to follow the right format.
                        Click <a class='pointer' id='uploadwaybillstatusupdatemodal-downloadtemplatebtn' href='../file-templates/waybill-update-status-template.xlsx'>here</a> to download file template.
                    </div>
                    <div class='col-md-offset-1 col-md-6'>
                    	<div class="form-group">
                            <label class='control-label'>Upload Type</label>
                            <select class='form-control uploadwaybillstatusupdatemodal-type select2' name='uploadtype'>
                            	<option value='STATUSUPDATE'>Status Update</option>
                            	<option value='COSTINGUPDATE'>Costing Update</option>
								<option value='WAYBILLUPLOAD'>BOL Upload</option>
								<option value='CHARGESUPLOAD'>Charges Upload</option>
								<option value='PACKAGEDIMENSIONUPLOAD'>Package Dimensions Upload</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class='control-label'>Select an Excel File</label>
                            <input type='file' class='form-control uploadwaybillstatusupdatemodal-file' name='uploadwaybillstatusupdatemodal-file'>
                        </div>
                    </div>
                </form>
                <br>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button class='btn btn-blue2 mybtn' id='uploadwaybillstatusupdatemodal-uploadbtn'>Upload</button>
                    <button class='btn btn-blue2 mybtn modal-cancelbtn' >Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadwaybillstatusupdatelogmodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Uploading File...
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <iframe id="uploadwaybillstatusupdatelogframe" name="uploadwaybillstatusupdatelogframe" height="600" width="100%" frameborder="0" scrolling="yes" style='background: #fff'></iframe>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	/**** INITIALIZED *******/
	$(document).ready(function(){
			var tabWB = '#waybill-menutabpane';
			var inputfieldsWB = ".waybill-inputfields";

			$(tabWB+' .packagedimensionsmodalflds-actualweight').number(true,4);
			$(tabWB+' .packagedimensionsmodalflds-volweight').number(true,4);
			$(tabWB+' .packagedimensionsmodalflds-cbm').number(true,4);
			$(tabWB+' .packagedimensionsmodalflds-length').number(true,4);
			$(tabWB+' .packagedimensionsmodalflds-width').number(true,4);
			$(tabWB+' .packagedimensionsmodalflds-height').number(true,4);
			

			//$(tabWB+' .modal-dialog').draggable();
			$(inputfieldsWB+' input,'+inputfieldsWB+' textarea,'+inputfieldsWB+' select').attr('disabled','disabled');
        	$(inputfieldsWB+' .transactionnumber').removeAttr('disabled').focus();
        	$(tabWB+" .select2").select2({
        		width:'100%'
        	});
        	var datetoday = new Date();
        	$(tabWB+' .datepicker').datepicker();
        	$(tabWB+' .datetimepicker').datetimepicker();

       
       		var refWB = <?php echo json_encode($refWB); ?>;
	        if(refWB!=''){
	            getWaybillInformation(refWB);
	            currentWaybillTxn = refWB;
	        }


	        $(tabWB+" .addressdistrictdropdownselect").select2({
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

		    $(tabWB+" .addresscitydropdownselect").select2({
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

		    $(tabWB+" .addresszipcodedropdownselect").select2({
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

		    $(tabWB+" .addressregiondropdownselect").select2({
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

			$(tabWB+" .addshipmenttypedropdownselect").select2({
		            ajax: {
		                    url: "loadables/dropdown/shipment-type.php",
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

			$(tabWB+" .addshipmentmodedropdownselect").select2({
		            ajax: {
		                    url: "loadables/dropdown/shipment-mode.php",
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

			$(tabWB+" .addshipmodedropdownselect").select2({
		            ajax: {
		                    url: "loadables/dropdown/shipment-mode.php",
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


	        $(tabWB+" .wbbookingnumberdropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/waybill-booking-numbers.php",
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

	    	$(tabWB+" .waybillstatusdropdownselect").select2({
                ajax: {
                        url: "loadables/dropdown/waybill-status.report.php",
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

	        $(tabWB+" .origindestinationdropdownselect").select2({
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

	    	$(tabWB+" .destinationroutedropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/destination-route.php",
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

	    	$(tabWB+" .countriesdropdownselect").select2({
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

	    	$(tabWB+" .personneldropdownselect").select2({
		            ajax: {
		                    url: "loadables/dropdown/personnel-1.php?hastype=1",
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

	    	$(tabWB+" .expresstransactiontypedropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/express-transaction-type.php",
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

	    	$(tabWB+" .modeoftransportdropdownselect").select2({
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

	    	$(tabWB+" .servicesdropdownselect").select2({
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

	    	$(tabWB+" .documentdropdownselect").select2({
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

	    	$(tabWB+" .deliveryinstructiondropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/delivery-instruction.php",
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

	    	$(tabWB+" .transportchargesdropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/transport-charges.php",
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

	    	$(tabWB+" .3pldropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/3pl.php",
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
	                minimumInputLength: 0
	    	});

	    	$(tabWB+" .parceltypedropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/parcel-type.php",
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
	                minimumInputLength: 0
	    	});

	    	$(tabWB+" .pouchsizedropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/pouch-size.php",
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
	                minimumInputLength: 0
	    	});

	    	$(tabWB+" .handlinginstructiondropdownselect").select2({
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

	    	$(tabWB+" .agentdropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/agent.php",
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

	    	$(tabWB+" .paymodedropdownselect").select2({
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
	    	$(tabWB+" .otherchargesdropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/other-charges.php",
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

	    	$(tabWB+" .carrierdropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/carrier.php",
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

	    	$(tabWB+" .shipperdropdownselect").select2({
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

	    	$(tabWB+" .consigneedropdownselect").select2({
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

	    	$(tabWB+" .bookingcitydropdownselect").select2({
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

	        $(tabWB+" .uomdropdownselect").select2({
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
	                    return {
	                                results: data
	                            };
	                },
	                cache: true
	            },
	            minimumInputLength: 0,
	            width: '100%'
	        });

	        $(tabWB+" .bookingregiondropdownselect").select2({
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

	        $(tabWB+" .statusdropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/status.php?type=WAYBILL",
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



	    	$(tabWB+" #waybillsearch-table").flexigrid({
				url: 'loadables/ajax/transactions.waybill-search.php',
				dataType: 'json',
				colModel : [
						{display: 'BOL/Tracking No.', name : 'txn_waybill.waybill_number', width : 150, sortable : true, align: 'left'},
						{display: 'MAWBL', name : 'txn_waybill.mawbl_bl', width : 150, sortable : true, align: 'left'},
						{display: 'Reference', name : 'txn_waybill.reference', width : 180, sortable : true, align: 'left'},
						{display: 'Status', name : 'txn_waybill.status', width : 100, sortable : true, align: 'left'},
						{display: 'Booking No.', name : 'txn_waybill.booking_number', width : 100, sortable : true, align: 'left'},
						{display: 'Origin', name : 'origintbl.description', width : 200, sortable : true, align: 'left'},
						{display: 'Destination', name : 'destinationtbl.description', width : 200, sortable : true, align: 'left'},
						{display: 'Destination Route', name : 'destinationroutetbl.description', width : 200, sortable : true, align: 'left', hide:true},
						{display: 'Shipper', name : 'shipper.account_name', width : 200, sortable : true, align: 'left'},
						{display: 'Consignee', name : 'consignee.account_name', width : 200, sortable : true, align: 'left'},
						{display: 'Pickup Date', name : 'txn_waybill.pickup_date', width : 100, sortable : true, align: 'left'},
						{display: 'Pickup City', name : 'txn_waybill.pickup_city', width : 130, sortable : true, align: 'left'},
						{display: 'Pickup Region', name : 'txn_waybill.pickup_state_province', width : 130, sortable : true, align: 'left'},
						{display: 'Chargeable Weight', name : 'txn_waybill.package_chargeable_weight', width : 100, sortable : true, align: 'right'},
						{display: 'Actual Weight', name : 'txn_waybill.package_actual_weight', width : 100, sortable : true, align: 'right'},
						{display: 'Manifest No.', name : 'txn_waybill.manifest_number', width : 100, sortable : true, align: 'left'},
						{display: 'Invoice No.', name : 'txn_waybill.invoice_number', width : 100, sortable : true, align: 'left'},
						{display: 'Created by', name : 'user.first_name', width : 150, sortable : true, align: 'left'},
						{display: 'Created Date', name : 'txn_waybill.created_date', width : 150, sortable : true, align: 'left'},
						{display: 'System ID', name : 'txn_waybill.id', width : 80, sortable : true, align: 'left'}
				],
				
				searchitems : [

						{display: 'BOL No.', name : 'txn_waybill.waybill_number', isdefault: true},
						{display: 'Reference', name : 'txn_waybill.reference'},
						{display: 'Booking No.', name : 'txn_waybill.booking_number'},
						{display: 'Origin', name : 'origintbl.description'},
						{display: 'Destination', name : 'destinationtbl.description'},
						{display: 'Shipper', name : 'shipper.account_name'},
						{display: 'Consignee', name : 'consignee.account_name'}
				],
				sortname: "txn_waybill.waybill_number",
				sortorder: "asc",
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


			$(tabWB+" #waybillcostingdetails-table").flexigrid({
				url: 'loadables/ajax/transactions.waybill-costing-details.php',
				dataType: 'json',
				colModel : [
						{display: 'Date', name : 'costing.date', width : 100, sortable : true, align: 'left'},
						{display: 'Type of Account', name : 'expense_type.description', width : 300, sortable : true, align: 'left'},
						{display: 'Account', name : 'chart_of_accounts.description', width : 150, sortable : true, align: 'left'},
						{display: 'Reference', name : 'costing.reference', width : 150, sortable : true, align: 'left'},
						{display: 'PRF Number', name : 'costing.prf_number', width : 150, sortable : true, align: 'left'},
						{display: 'Amount', name : 'costing.amount', width : 150, sortable : true, align: 'right'}
				],
				
				searchitems : [

						{display: 'Account', name : 'chart_of_accounts.description', isdefault: true},
						{display: 'Type of Account', name : 'expense_type.description'},
						{display: 'Reference', name : 'costing.reference'},
						{display: 'PRF Number', name : 'costing.prf_number'}
				],
				sortname: "chart_of_accounts.description",
				sortorder: "asc",
				usepager: true,
				title: "", 
				useRp: true,
				rp: 15, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 250,
				singleSelect: false,
				disableSelect: true
			});


			$(tabWB+" #waybill-shipperlookuptbl").flexigrid({
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

			$(tabWB+" #waybill-shipperpickupaddresslookuptbl").flexigrid({
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

			$(tabWB+" #waybill-consigneelookuptbl").flexigrid({
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

			$(tabWB+" #waybill-statushistorytbl").flexigrid({
				url: 'loadables/ajax/transactions.waybill.status-history.php?waybillnumber',
				dataType: 'json',
				colModel : [
				        {display: '', name : 'action', width : 50, sortable : false, align: 'center'},
						{display: 'Status', name : 'status_description', width : 150, sortable : true, align: 'left'},
						{display: 'Timestamp', name : 'created_date', width : 150, sortable : true, align: 'left'},
						{display: 'User', name : 'createdby', width : 200, sortable : true, align: 'left'},
						{display: 'Remarks', name : 'remarks', width : 300, sortable : true, align: 'left'},
						{display: 'Received By', name : 'received_by', width : 150, sortable : true, align: 'left'},
						{display: 'Date', name : 'received_date', width : 150, sortable : true, align: 'left'},
						{display: 'Courier', name : 'courier', width : 150, sortable : true, align: 'left'}
						
				],
				
				searchitems : [
						{display: 'Status', name : 'status_description', isdefault: true},
						{display: 'Remarks', name : 'remarks'},
						{display: 'User', name : 'createdby'}
				],
				sortname: "created_date",
				sortorder: "desc",
				usepager: true,
				buttons : [
                        {name: 'Download', bclass: 'download', onpress : downloadWaybillStatusHistory},
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


			$(tabWB+" #waybill-billinghistorytbl").flexigrid({
				url: 'loadables/ajax/transactions.waybill.billing-history.php?waybillnumber',
				dataType: 'json',
				colModel : [
						{display: 'Flag', name : 'billing_flag', width : 150, sortable : true, align: 'left'},
						{display: 'Reference', name : 'reference', width : 150, sortable : true, align: 'left'},
						{display: 'Remarks', name : 'remarks', width : 350, sortable : true, align: 'left'},
						{display: 'Timestamp', name : 'created_date', width : 150, sortable : true, align: 'left'},
						{display: 'User', name : 'createdby', width : 150, sortable : true, align: 'left'}
				],
				
				searchitems : [
						{display: 'Flag', name : 'billing_flag', isdefault: true},
						{display: 'Reference', name : 'reference'},
						{display: 'Remarks', name : 'remarks'},
						{display: 'User', name : 'createdby'}
				],
				sortname: "created_date",
				sortorder: "desc",
				usepager: true,
				buttons : [
                        {name: 'Download', bclass: 'download', onpress : downloadWaybillBillingHistory},
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

			function downloadWaybillStatusHistory(){
				var waybillnumber = $(tabWB+' #pgtxnwaybill-id').attr('pgtxnwaybill-number');
	            window.open("Printouts/excel/transactions.waybill-status-history.php?waybillnumber="+waybillnumber);
	        }

	        function downloadWaybillBillingHistory(){
				var waybillnumber = $(tabWB+' #pgtxnwaybill-id').attr('pgtxnwaybill-number');
	            window.open("Printouts/excel/transactions.waybill-billing-history.php?waybillnumber="+waybillnumber);
	        }



			$(tabWB+' #waybill-otherchargestbl').DataTable({
					aaSorting: [[ 1, "asc" ]], //initially, table is sorted by second column desc
                    columnDefs: [
                                     {
                                        targets: "column-nosort", //class of columns you dont want to be sortable
                                        orderable: false,
                                        //visible: false,
                                        //searchable: true
                                     }
                                 ],
                    pagingType: "full",
	                "createdRow": function( row, data, dataIndex ) {
					    $(row).addClass('wbotherchargesrow');
				  	}
			});

			$(tabWB+' #editwaybillchargesmodal-otherchargestbl').DataTable({
					aaSorting: [[ 1, "asc" ]], //initially, table is sorted by second column desc
                    columnDefs: [
                                     {
                                        targets: "column-nosort", //class of columns you dont want to be sortable
                                        orderable: false,
                                        //visible: false,
                                        //searchable: true
                                     }
                                 ],
                    pagingType: "full",
	                "createdRow": function( row, data, dataIndex ) {
					    $(row).addClass('editwaybillchargesmodal-otherchargesrow');
				  	}
			});

			$(tabWB+' #waybill-packagedimensionsmodaltbl').DataTable({
					aaSorting: [[ 1, "asc" ]], //initially, table is sorted by second column desc
                    columnDefs: [
                                     {
                                        targets: "column-nosort", //class of columns you dont want to be sortable
                                        orderable: false,
                                        //visible: false,
                                        //searchable: true
                                     }
                                 ],
                    pagingType: "full",
	                "createdRow": function( row, data, dataIndex ) {
					    $(row).addClass('wbpackagedimensions');
				  	}
			});

			$(tabWB+' #waybill-packagecodemodaltbl').DataTable({
						pagingType:"full",
						aaSorting: [[ 1, "asc" ]],
			        	columnDefs: [
						      				 {
										        targets: "column-nosort", 
										        orderable: false,
										     }
									 ],

			            "columns": [
				            			
								        { data: 'checkbox',
								          "className": 'text-center'} ,
								        { data: 'code' },
								        { data: 'created_date' },
								        { data: 'createdby' }
				                   ],
						"processing": true,
						"serverSide": true,
						"ajax": {
						                url: "loadables/ajax/transactions.waybill-package-codes.php?waybill",
						                type: 'POST'
						        }
			});

			
			



       

        


        
				userAccess();
    


			

	});
	



</script>