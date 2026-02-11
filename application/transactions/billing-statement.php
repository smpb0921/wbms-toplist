<?php
     include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
    $refBLS = isset($_GET['reference'])?escapeString($_GET['reference']):'';
?>
<div class='header-page' >
	<div class='header-page-inner'>
		Billing Satement
	</div>
</div>
<div class="container-fluid">
    <div class="pageContent">
        <div class="panel panel-primary mypanel">
            <div class="panel-body">
                <!-- CONTENT -->
                <div class='transaction-wrapper billingstatement-content'>

                        <div class='col-md-12 no-padding margin-bottom-sm topbuttonsdiv'>
                            <div class='padded-with-border-engraved topbuttonswrapper'>
                                <div class='button-group'>
                                    <div class='button-group-btn active' title='New' id='billingstatement-trans-newbtn' href='#newbillingstatementmodal' data-toggle='modal'>
                                        <img src="../resources/img/add.png">
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class='billingstatement-inputfields'>
                                				<input type='hidden' id='pgtxnbillingstatement-id'>
                                				<div class='col-lg-2'>
                                                    <div class="form-horizontal">
                                                        <div class="form-group">
                                                        	<div class='col-md-12'>
                                                                <label class='control-label'>Statement No.</label>
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
                                                    <div class='transaction-status-div2 margin-bottom-xs paidflagdiv'><br></div>
                                                    <div class='button-group-btn fluid searchbtn active' data-toggle='modal' href='#billingstatement-searchmodallookup'>
                                                        <!--<i class='fa fa-search fa-lg fa-space'></i>-->
                                                        <img src="../resources/img/search.png">&nbsp;&nbsp;Search
                                                    </div>
                                                    <br>
                                                </div>
                                                <div class="col-lg-10">

                                                	<div class='col-md-12 no-padding margin-bottom-xs margin-top-sm'>
							                            <div class="panel-group classictheme margin-bottom-xs" id="billingstatement-panelheader-wrapper" role="tablist" aria-multiselectable="true">

							                                    <div class="panel panel-default">
							                                        <div class="panel-heading" role="tab" data-toggle="collapse" href="#billingstatement-panelheader">
							                                                Header
							                                        </div>
							                                        <div id="billingstatement-panelheader" class="panel-collapse collapse in" role="tabpanel">
							                                            <div class="panel-body">
							                                            		<div class='row'>
								                                                	<div class="col-md-12">
									                                                    <div class="header-errordiv"></div>
									                                                </div>
									                                            </div>
							                                                    <div class="form-horizontal">
							                                                        <div class="col-md-7">
							                                                        	

							                                                        	<!--<fieldset>
							                                                        		<legend>Shipper Information</legend>-->
																							<div class="form-group">
							                                                        			<label class='control-label col-md-3'>Shipment Type</label>
							                                                        			<div class='col-md-9'>
							                                                        				<input type='text' class='form-input form-control billingstatement-shipmenttype alwaysdisabled' disabled="true">
							                                                        			</div>
							                                                        		</div>
							                                                        		<div class="form-group">
							                                                        			<label class='control-label col-md-3'>Billing Type</label>
							                                                        			<div class='col-md-9'>
							                                                        				<input type='text' class='form-input form-control billingstatement-billingtype alwaysdisabled' disabled="true">
							                                                        			</div>
							                                                        		</div>
							                                                        		<div class="form-group">
							                                                        			<label class='control-label col-md-3'>Account No.</label>
							                                                        			<div class='col-md-5'>
							                                                        				<!--<div class="input-group">-->
							                                                        					<input type='hidden' class='billingstatement-shipperid'>
							                                                        					<input type='text' class='form-input form-control billingstatement-accountnumber alwaysdisabled' disabled="true">

							                                                        					<!--<span class="input-group-addon inputgroupbtn">
							                                                        						<i class="fa fa-search inputgroupicon inputgroupbtnicon hidden" title='Search for Shipper' id='billingstatement-shipperlookupbtn' data-modal='#billingstatement-shipperlookup'></i>
							                                                        					</span>
							                                                        				</div>-->

							                                                        			</div>
							                                                        		</div>
							                                                        		<div class="form-group">
							                                                        			<label class='control-label col-md-3'>Account Name</label>
							                                                        			<div class='col-md-9'>
							                                                        				<input type='text' class='form-input form-control billingstatement-accountname alwaysdisabled' disabled="true">
							                                                        			</div>
							                                                        		</div>
							                                                        		<div class="form-group">
							                                                        			<label class='control-label col-md-3'>Company Name</label>
							                                                        			<div class='col-md-9'>
							                                                        				<input type='text' class='form-input form-control billingstatement-companyname alwaysdisabled' disabled="true">
							                                                        			</div>
							                                                        		</div>
							                                                        		<div class="form-group">
							                                                        			<label class='control-label col-md-3'>Account Executive</label>
							                                                        			<div class='col-md-9'>
							                                                        				<input type='text' class='form-input form-control billingstatement-accountexecutive alwaysdisabled' disabled="true">
							                                                        			</div>
							                                                        		</div>
							                                                        		<!--<br>
							                                                        		<div class="form-group">
																								<label class='control-label col-md-3'>Region/Province</label>
																								<div class='col-md-9'>
																									<select class='form-control inputslctfld billingstatement-province addrdropregion addressregiondropdownselect alwaysdisabled' disabled></select>
																									
																								</div>
																							</div>
																							
																							<div class="form-group">
																								<label class='control-label col-md-3'>City</label>
																								<div class='col-md-9'>
																									<select class='form-control inputslctfld billingstatement-city addrdropcity addresscitydropdownselect alwaysdisabled' disabled></select>
																									
																								</div>
																							</div>
																							
																							
																							<div class="form-group">
																								<label class='control-label col-md-3'>District/Barangay</label>
																								<div class='col-md-9'>
																									<select class='form-control inputslctfld billingstatement-district addrdropdistrict addressdistrictdropdownselect alwaysdisabled' disabled></select>
																									
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Zip Code</label>
																								<div class='col-md-9'>
																									<select class='form-control inputslctfld billingstatement-zipcode addrdropzip addresszipcodedropdownselect alwaysdisabled' disabled></select>
																									
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Street</label>
																								<div class='col-md-9'>
																									<textarea class='form-input form-control billingstatement-street alwaysdisabled' disabled rows='3'></textarea>
																								
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Country</label>
																								<div class='col-md-9'>
																									<select class='form-control countriesdropdownselect billingstatement-country alwaysdisabled' disabled></select>
																								</div>
																							</div>-->
							                                                        	<!--</fieldset>

							                                                        	<fieldset>
							                                                        		<legend>Billing Information</legend>-->
							                                                        		<br>
							                                                        		<div class="form-group">
								                                                                <label class='control-label col-lg-3'>Contact Name</label>
								                                                                <div class="col-lg-9">
								                                                                    <input type='text' class='form-control billingstatement-contact'>
								                                                                </div>
								                                                            </div>
								                                                            <div class="form-group">
								                                                                <label class='control-label col-lg-3'>Phone</label>
								                                                                <div class="col-lg-4">
								                                                                    <input type='text' class='form-control billingstatement-phone'>
								                                                                </div>

								                                                                <label class='control-label col-lg-1'>Mobile</label>
								                                                                <div class="col-lg-4">
								                                                                    <input type='text' class='form-control billingstatement-mobile'>
								                                                                </div>
								                                                            </div>
								                                                            <!--<div class="form-group">
								                                                                <label class='control-label col-lg-3'>Fax</label>
								                                                                <div class="col-lg-9">
								                                                                    <input type='text' class='form-control billingstatement-fax'>
								                                                                </div>
								                                                            </div>-->
								                                                            <div class="form-group">
								                                                                <label class='control-label col-lg-3'>Email</label>
								                                                                <div class="col-lg-9">
								                                                                    <input type='text' class='form-control billingstatement-email'>
								                                                                </div>
								                                                            </div>
								                                                            <br>
							                                                        		<div class="form-group">
																								<label class='control-label col-md-3'>Region/Province</label>
																								<div class='col-md-9'>
																									<input type='text' class='form-control billingstatement-billingprovince'>
																									
																								</div>
																							</div>
																							
																							<div class="form-group">
																								<label class='control-label col-md-3'>City</label>
																								<div class='col-md-9'>
																									<input type='text' class='form-control billingstatement-billingcity'>
																									
																								</div>
																							</div>
																							
																							
																							<div class="form-group">
																								<label class='control-label col-md-3'>District/Barangay</label>
																								<div class='col-md-9'>
																									<input type='text' class='form-control billingstatement-billingdistrict'>
																									
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Zip Code</label>
																								<div class='col-md-9'>
																									<input type='text' class='form-control billingstatement-billingzipcode'>
																									
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Street</label>
																								<div class='col-md-9'>
																									<textarea class='form-input form-control billingstatement-billingstreet' rows='3'></textarea>
																									<!--<input type='text' class='form-input form-control billingstatement-street alwaysdisabled' disabled>-->
																								</div>
																							</div>
																							<div class="form-group">
																								<label class='control-label col-md-3'>Country</label>
																								<div class='col-md-9'>
																									<input type='text' class='form-control billingstatement-billingcountry'>
																								</div>
																							</div>
																							<div class="form-group">
								                                                                <label class='control-label col-md-3'>Remarks</label>
								                                                                <div class="col-md-9">
								                                                                    <textarea class='form-control billingstatement-remarks' rows='4'></textarea>
								                                                                </div>
								                                                            </div>
							                                                        	<!--</fieldset>-->
							                                                        	
							                                                         

							                                                            
							                                                        </div>
							                                                        <div class='col-md-5'>
							                                                        	<div class="form-group">
							                                                                <label class='control-label col-md-3'>Statement Date</label>
							                                                                <div class="col-md-9">
							                                                                    <input type='text' class='form-control billingstatement-documentdate'>
							                                                                </div>
							                                                            </div>
																						
							                                                            <div class="form-group">
							                                                                <label class='control-label col-md-3'>Attention</label>
							                                                                <div class="col-md-9">
							                                                                    <input type='text' class='form-control billingstatement-attention'>
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-md-3'>BS#</label>
							                                                                <div class="col-md-9">
							                                                                    <input type='text' class='form-control billingstatement-invoice'>
							                                                                </div>
							                                                            </div>
																						<div class="form-group">
																							<label class="control-label col-md-3">VAT Flag</label>
																							<div class="col-md-9">
																								<select class="form-control billingstatement-vatflag">
																									<option value="1">Yes</option>
																									<option value="0">No</option>
																								</select>
																							</div>
																						</div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-md-3'>Payment Due Date</label>
							                                                                <div class="col-md-9">
							                                                                    <input type='text' class='form-control billingstatement-paymentduedate'>
							                                                                </div>
							                                                            </div>
							                                                            
							                                                          
							                                                            
							                                                        	<div class="form-group">
							                                                                <label class='control-label col-md-3'>Created Date</label>
							                                                                <div class="col-md-9">
							                                                                    <input type='text' class='form-control billingstatement-createddate alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-md-3'>Created by</label>
							                                                                <div class="col-md-9">
							                                                                    <input type='text' class='form-control billingstatement-createdby alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-md-3'>Updated Date</label>
							                                                                <div class="col-md-9">
							                                                                    <input type='text' class='form-control billingstatement-updateddate alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-md-3'>Updated by</label>
							                                                                <div class="col-md-9">
							                                                                    <input type='text' class='form-control billingstatement-updatedby alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-md-3'>Posted Date</label>
							                                                                <div class="col-md-9">
							                                                                    <input type='text' class='form-control billingstatement-posteddate alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-md-3'>Posted by</label>
							                                                                <div class="col-md-9">
							                                                                    <input type='text' class='form-control billingstatement-postedby alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-md-3'>Received Date</label>
							                                                                <div class="col-md-9">
							                                                                    <input type='text' class='form-control billingstatement-receiveddate alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-md-3'>Received by</label>
							                                                                <div class="col-md-9">
							                                                                    <input type='text' class='form-control billingstatement-receivedby alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>

							                                                            <div class="form-group">
							                                                                <label class='control-label col-md-3'>Reason</label>
							                                                                <div class="col-md-9">
							                                                                    <textarea class='form-control billingstatement-reason' rows='4'></textarea>
							                                                                </div>
							                                                            </div>

							                                                            <!--<div class="form-group">
							                                                                <label class='control-label col-lg-3'>Paid</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control billingstatement-paidflag alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>-->


							                                                            

							                                                            
							                                                        </div>
							                                                        
							                                                    </div>
							                                            </div>
							                                        </div>
							                                    </div>
							                            </div>

							                            <div class="panel-group classictheme margin-bottom-xs" id="billingstatement-paneldetails-wrapper" role="tablist" aria-multiselectable="true">

							                                    <div class="panel panel-default">
							                                        <div class="panel-heading" role="tab" data-toggle="collapse" href="#billingstatement-paneldetails">
							                                                Details
							                                        </div>
							                                        <div id="billingstatement-paneldetails" class="panel-collapse collapse in" role="tabpanel">
							                                            <div class="panel-body">
							                                            	<div class='row'>
								                                                	<div class="col-md-12">
									                                                    <div class="waybilldetail-errordiv"></div>
									                                                </div>
									                                                <div class='col-md-12'>

											                                        	<div class='table-sm'>
											                                        		<table id='billingstatement-detailstbl'>
																								<tbody></tbody>
																							</table>
																		            	</div>

											                                        </div>
									                                        </div>
									                                        
									                                     
							                                               	<br>
							                                                <div class='form-horizontal'>
							                                                		<div class='col-md-offset-9 col-md-3'>
							                                                			<div class="form-group">
							                                                                <label class='control-label'>Total Vatable Charges</label>
							                                                                <input type='text' class='text-right form-control billingstatement-totalvatablecharges' style='height: 30px; font-size: 20px'>
							                                                                
							                                                            </div>
							                                                        </div>
							                                                        <div class='col-md-offset-9 col-md-3'>
							                                                			<div class="form-group">
							                                                                <label class='control-label'>Total Non Vatable Charges</label>
							                                                                <input type='text' class='text-right form-control billingstatement-totalnonvatablecharges' style='height: 30px; font-size: 20px'>
							                                                                
							                                                            </div>
							                                                        </div>
							                                                        <div class='col-md-offset-9 col-md-3'>
							                                                			<div class="form-group">
							                                                                <label class='control-label'>VAT</label>
							                                                                <input type='text' class='text-right form-control billingstatement-vat' style='height: 30px; font-size: 20px'>
							                                                                
							                                                            </div>
							                                                        </div>
							                                                        <div class='col-md-offset-9 col-md-3'>
							                                                			<div class="form-group">
							                                                                <label class='control-label'>Total Amount</label>
							                                                                <input type='text' class='text-right form-control billingstatement-totalbillingamount' style='height: 30px; font-size: 20px'>
							                                                                
							                                                            </div>
							                                                        </div>
							                                                        <div class='col-md-offset-9 col-md-3'>
							                                                			<div class="form-group">
							                                                                <label class='control-label'>Cancelled Amount</label>
							                                                                <input type='text' class='text-right form-control billingstatement-cancelledamount' style='height: 30px; font-size: 20px'>
							                                                                
							                                                            </div>
							                                                        </div>
							                                                        <div class='col-md-offset-9 col-md-3'>
							                                                			<div class="form-group">
							                                                                <label class='control-label'>Revised Amount</label>
							                                                                <input type='text' class='text-right form-control billingstatement-revisedamount' style='height: 30px; font-size: 20px'>
							                                                                
							                                                            </div>
							                                                        </div>
							                                                </div>   
							                                                <br>
							                                                        
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


<div class="modal fade" id="newbillingstatementmodal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    New Billing
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='col-md-12'>
            		<div class='form-horizontal'>
            				<div class='modal-errordiv'></div>
            				<div class="form-group">
            					<label class='control-label col-lg-2'>Statement Date*</label>
            					<div class="col-lg-4">
            						<input type='text' class='form-control newbillingstatementmodal-documentdate datepicker'>
            					</div>

            					<label class='control-label col-lg-2'>Payment Due Date*</label>
            					<div class="col-lg-4">
            						<input type='text' class='form-control newbillingstatementmodal-paymentduedate datepicker'>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-2'>Attention*</label>
            					<div class="col-lg-4">
            						<input type='text' class='form-control newbillingstatementmodal-attention blsattention'>
            					</div>
            					<label class='control-label col-lg-2'>VAT Flag</label>
            					<div class="col-lg-4">
									<select class="form-control newbillingstatementmodal-vatflag blsvatflag">
										<option value="1">Yes</option>
										<option value="0">No</option>
									</select>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-2'>Billing Statement No.*</label>
            					<div class="col-lg-10">
									<input type='text' class='form-control newbillingstatementmodal-invoice blsinvoice'>
            					</div>
            				</div>

							<div class="form-group">
            					<label class='control-label col-lg-2'>Shipment Type*</label>
            					<div class="col-lg-10">
            						<select class='form-control form-input newbillingstatementmodal-shipmenttype shipmenttypedropdownselect' style='width:100%'></select>
            					</div>
            				</div>

            				<div class="form-group">
            					<label class='control-label col-lg-2'>Billing Type*</label>
            					<div class="col-lg-10">
            						<select class='form-control form-input newbillingstatementmodal-billingtype billingtypedropdownselect' style='width:100%'></select>
            					</div>
            				</div>
            				
            				<div class="form-group">
            					<label class='control-label col-lg-2'>Shipper*</label>
            					<div class="col-lg-10">
            						<select class='form-control form-input newbillingstatementmodal-shipper blsshipperselection shipperdropdownselect' style='width:100%'></select>
            					</div>
            				</div>

            				<div class="form-group">
            					<label class='control-label col-lg-2'>Account Executive*</label>
            					<div class="col-lg-10">
            						<select class='form-control form-input newbillingstatementmodal-accountexecutive accountexecutivedropdownselect' style='width:100%'></select>
            					</div>
            				</div>
            				
            				<div class="form-group">
            					<label class='control-label col-lg-2'>Remarks</label>
            					<div class="col-lg-10">
            						<textarea class='form-control newbillingstatementmodal-remarks' rows='4'></textarea>
            					</div>
            				</div>
            				<fieldset>
            					<legend>Contact Information</legend>
            					<div class="form-group">
            						<label class='control-label col-lg-2'>Contact Name</label>
            						<div class="col-lg-10">
            							<input type='text' class='form-control blscontact'>
            						</div>
            					</div>
            					<div class="form-group">
            						<label class='control-label col-lg-2'>Phone</label>
            						<div class="col-lg-10">
            							<input type='text' class='form-control blsphone'>
            						</div>
            					</div>
            					<div class="form-group">
            						<label class='control-label col-lg-2'>Mobile</label>
            						<div class="col-lg-10">
            							<input type='text' class='form-control blsmobile'>
            						</div>
            					</div>
            					<!--<div class="form-group">
            						<label class='control-label col-lg-2'>Fax</label>
            						<div class="col-lg-10">
            							<input type='text' class='form-control blsfax'>
            						</div>
            					</div>-->
            					<div class="form-group">
            						<label class='control-label col-lg-2'>Email</label>
            						<div class="col-lg-10">
            							<input type='text' class='form-control blsemail'>
            						</div>
            					</div>
            				</fieldset>
            				<!--<fieldset>
            					<legend>Billing Information</legend>
            					<div class="form-group">
            						<label class='control-label col-lg-2'>Region/Province</label>
            						<div class='col-lg-10'>
            							<input type='text' class='form-control blsprovince alwaysdisabled' disabled="">

            						</div>
            					</div>
            					<div class="form-group">
            						<label class='control-label col-lg-2'>City</label>
            						<div class='col-lg-10'>
            							<input type='text' class='form-control blscity alwaysdisabled' disabled="">

            						</div>
            					</div>
            					<div class="form-group">
            						<label class='control-label col-lg-2'>District/Barangay</label>
            						<div class='col-lg-10'>
            							<input type='text' class='form-control blsdistrict alwaysdisabled' disabled="">

            						</div>
            					</div>
            					<div class="form-group">
            						<label class='control-label col-lg-2'>Zip Code</label>
            						<div class='col-lg-10'>
            							<input type='text' class='form-control blszipcode alwaysdisabled' disabled="">

            						</div>
            					</div>
            					<div class="form-group">
            						<label class='control-label col-lg-2'>Street</label>
            						<div class='col-lg-10'>
            							<input type='text' class='form-control blsstreet alwaysdisabled' disabled="">

            						</div>
            					</div>
            					<div class="form-group">
            						<label class='control-label col-lg-2'>Country</label>
            						<div class='col-lg-10'>
            							<input type='text' class='form-control blscountry alwaysdisabled' disabled="">

            						</div>
            					</div>
            				</fieldset>-->
            				
	            	</div>
            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='newbillingstatementmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>

<div class="modal fade" id="editbillingstatementmodal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Edit Billing Statement
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='col-md-12'>
            		<div class='form-horizontal'>
            				<div class='modal-errordiv'></div>
            				<div class="form-group">
            					<label class='control-label col-lg-2'>Statement Date*</label>
            					<div class="col-lg-4">
            						<input type='text' class='form-control editbillingstatementmodal-documentdate datepicker'>
            					</div>

            					<label class='control-label col-lg-2'>Payment Due Date*</label>
            					<div class="col-lg-4">
            						<input type='text' class='form-control editbillingstatementmodal-paymentduedate datepicker'>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-2'>Attention*</label>
            					<div class="col-lg-4">
            						<input type='text' class='form-control editbillingstatementmodal-attention'>
            					</div>

            					<label class='control-label col-lg-2'>Billing Statement No.*</label>
            					<div class="col-lg-4">
            						<input type='text' class='form-control editbillingstatementmodal-invoice'>
            					</div>
            				</div>
							<div class="form-group">
            					<label class='control-label col-lg-2'>VAT Flag</label>
            					<div class="col-lg-4">
									<select class="form-control editbillingstatementmodal-vatflag">
										<option value="1">Yes</option>
										<option value="0">No</option>
									</select>
            					</div>
							</div>

							<div class="form-group">
            					<label class='control-label col-lg-2'>Shipment Type*</label>
            					<div class="col-lg-10">
            						<select class='form-control form-input editbillingstatementmodal-shipmenttype shipmenttypedropdownselect' style='width:100%'></select>
            					</div>
            				</div>

							<div class="form-group">
            					<label class='control-label col-lg-2'>Billing Type*</label>
            					<div class="col-lg-10">
            						<select class='form-control form-input editbillingstatementmodal-billingtype billingtypedropdownselect' style='width:100%'></select>
            					</div>
            				</div>
            				
            				<div class="form-group">
            					<label class='control-label col-lg-2'>Shipper*</label>
            					<div class="col-lg-10">
            						<select class='form-control form-input editbillingstatementmodal-shipper blsshipperselection shipperdropdownselect' style='width:100%'></select>
            					</div>
            				</div>

            				<div class="form-group">
            					<label class='control-label col-lg-2'>Account Executive*</label>
            					<div class="col-lg-10">
            						<select class='form-control form-input editbillingstatementmodal-accountexecutive accountexecutivedropdownselect' style='width:100%'></select>
            					</div>
            				</div>
            				<div class="form-group hidden">
            						<label class='control-label col-lg-2'>Shipper</label>
            						<div class="col-lg-10">
            							<input type='text' class='form-control editbillingstatementmodal-shipperstr alwaysdisabled' disabled>
            						</div>
            				</div>
            				<!--<div class="form-group">
            					<label class='control-label col-lg-2'>Shipper*</label>
            					<div class="col-lg-10">
            						<select class='form-control form-input editbillingstatementmodal-shipper blsshipperselection shipperdropdownselect' style='width:100%'></select>
            					</div>
            				</div>-->
            				
            				<div class="form-group">
            					<label class='control-label col-lg-2'>Remarks</label>
            					<div class="col-lg-10">
            						<textarea class='form-control editbillingstatementmodal-remarks' rows='4'></textarea>
            					</div>
            				</div>
            				<fieldset>
            					<legend>Contact Information</legend>
            					<div class="form-group">
            						<label class='control-label col-lg-2'>Contact Name</label>
            						<div class="col-lg-10">
            							<input type='text' class='form-control editbillingstatementmodal-contact'>
            						</div>
            					</div>
            					<div class="form-group">
            						<label class='control-label col-lg-2'>Phone</label>
            						<div class="col-lg-10">
            							<input type='text' class='form-control editbillingstatementmodal-phone'>
            						</div>
            					</div>
            					<div class="form-group">
            						<label class='control-label col-lg-2'>Mobile</label>
            						<div class="col-lg-10">
            							<input type='text' class='form-control editbillingstatementmodal-mobile'>
            						</div>
            					</div>
            					<div class="form-group">
            						<label class='control-label col-lg-2'>Email</label>
            						<div class="col-lg-10">
            							<input type='text' class='form-control editbillingstatementmodal-email'>
            						</div>
            					</div>
            				</fieldset>

            				<fieldset>
            					<legend>Billing Address</legend>
            					<div class='addressgroupwrapper'>
	            					<div class="form-group">
	            						<label class='control-label col-md-2'>Region/Province</label>
	            						<div class='col-md-10'>
	            						<select class='form-control editbillingstatementmodal-province addrdropregion addressregiondropdownselect'></select>

	            						</div>
	            					</div>

	            					<div class="form-group">
	            						<label class='control-label col-md-2'>City</label>
	            						<div class='col-md-10'>
	            							<select class='form-control editbillingstatementmodal-city addrdropcity addresscitydropdownselect'></select>

	            						</div>
	            					</div>
	            					<div class="form-group">
	            						<label class='control-label col-md-2'>District/Barangay</label>
	            						<div class='col-md-10'>
	            						<select class='form-control editbillingstatementmodal-district addrdropdistrict addressdistrictdropdownselect'></select>

	            						</div>
	            					</div>
	            					<div class="form-group">
	            						<label class='control-label col-md-2'>Zip Code</label>
	            						<div class='col-md-10'>
	            							<select class='form-control editbillingstatementmodal-zipcode addrdropzip addresszipcodedropdownselect'></select>

	            						</div>
	            					</div>
	            					<div class="form-group">
	            						<label class='control-label col-md-2'>Street</label>
	            						<div class='col-md-10'>
	            							<textarea class='form-input form-control editbillingstatementmodal-street' rows='3'></textarea>
	            						</div>
	            					</div>
	            					<div class="form-group">
	            						<label class='control-label col-md-2'>Country</label>
	            						<div class='col-md-10'>
	            							<select class='form-control countriesdropdownselect editbillingstatementmodal-country'></select>
	            						</div>
	            					</div>
	            				</div>
            				</fieldset>
            			
            				
	            	</div>
            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='editbillingstatementmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>


<div class="modal fade" id="blsaddwaybillnumbermodal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Add BOL
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
           		<div class='modal-errordiv'></div>
            	<div class='col-md-12'>
            		<div class='form-horizontal'>
            				
            				<div class="form-group">
            					<label class='control-label'>BOL No.</label>
            					<input type='text' class='form-control blsaddwaybillnumbermodal-waybillnumber'>
            				</div>
            				<div class="form-group">
	            					<div class='button-group-btn fluid active' id='blsaddwaybillnumbermodal-addbtn'>
                                        <img src="../resources/img/add.png">&nbsp;&nbsp;Add Waybill
                                    </div>
                            </div>
            		</div>
            		<br>
            	</div>
           	</div>
        </div>
    </div>
</div>

<div class="modal fade" id="blssearchwaybilltransactionmodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    BOL Lookup
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<table id='blssearchwaybilltransactiontbl'>
            		<tbody></tbody> 
            	</table>
            	
           	</div>
        </div>
    </div>
</div>


<div class="modal fade" id="voidbillingstatementtransactionmodal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Void Billing Transaction
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='col-md-3'>
            		To void, please provide a reason.
            	</div>
            	<div class='col-md-9'>
            		<div class='form-horizontal'>
            				<input type='hidden' id='voidbillingstatementtransactionmodal-id'>
            				<div class='modal-errordiv'></div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Billing No.</label>
	            				<div class='col-md-9'>
	            					<input type='text' class='form-input form-control voidbillingstatementtransactionmodal-txnnumber' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Remarks</label>
	            				<div class='col-md-9'>
	            					<textarea class='form-control voidbillingstatementtransactionmodal-remarks' rows='6'></textarea>
	            				</div>
	            			</div>
	            	</div>
            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='voidbillingstatementtransactionmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>

<div class="modal fade" id="unpostbillingstatementtransactionmodal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Unpost Billing Statement Transaction
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='col-md-3'>
            		To unpost transaction, please provide a reason.
            	</div>
            	<div class='col-md-9'>
            		<div class='form-horizontal'>
            				<input type='hidden' id='unpostbillingstatementtransactionmodal-id'>
            				<div class='modal-errordiv'></div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Billing Statement No.</label>
	            				<div class='col-md-9'>
	            					<input type='text' class='form-input form-control unpostbillingstatementtransactionmodal-txnnumber' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Remarks</label>
	            				<div class='col-md-9'>
	            					<textarea class='form-control unpostbillingstatementtransactionmodal-remarks' rows='6'></textarea>
	            				</div>
	            			</div>
	            	</div>
            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='unpostbillingstatementtransactionmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>




<div class="modal fade" id="billingstatement-searchmodallookup">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Search for Billing Statement
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
	            	<div class="form-horizontal">
	            		<div class='col-md-6'>
	            			<div class="form-group">
			            			<label class='control-label col-md-3'>Status</label>
			            			<div class="col-md-9">
			            				 <select class='form-control form-input billingstatementsearch-status billingstatementstatusdropdownselect'>
			            				 	
			            				 </select>
			            			</div>
		            		</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-3'>Shipper</label>
			            			<div class="col-md-9">
			            				 <select class='form-control form-input billingstatementsearch-shipper shipperdropdownselect'>
			            				 	
			            				 </select>
			            			</div>
		            		</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-3'>BOL Number</label>
			            			<div class="col-md-9">
			            				<input type='text' class='form-control billingstatementsearch-waybillnumber'>
			            			</div>
			            	</div>
							<div class="form-group">
			            			<label class='control-label col-md-3'>BS #</label>
			            			<div class="col-md-9">
			            				<input type='text' class='form-control billingstatementsearch-bsnumber'>
			            			</div>
			            	</div>
		            		
		            		
			            </div>
		            	<div class='col-md-6'>
		            		<div class="form-group">
			            			<label class='control-label col-md-4'>Paid</label>
			            			<div class="col-md-8">
			            				 <select class='form-control form-input billingstatementsearch-paidflag select2' style='width:100%'>
			            				 		<option value=''></option>
			            				 		<option value='0'>NO</option>
			            				 		<option value='1'>YES</option>
			            				 </select>
			            			</div>
		            		</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-4'>Document Date From</label>
			            			<div class="col-md-8">
			            				<input type='text' class='form-control billingstatementsearch-docdatefrom datepicker'>
			            			</div>
			            	</div>
			            	<div class="form-group">
			            			<label class='control-label col-md-4'>Dcoument Date To</label>
			            			<div class="col-md-8">
			            				<input type='text' class='form-control billingstatementsearch-docdateto datepicker'>
			            			</div>
			            	</div>
			            	<div class="form-group">
			            			<div class="col-md-offset-4 col-md-8">
			            				<div class='button-group-btn fluid active' id='billingstatementsearch-searchbtn'>
	                                        <img src="../resources/img/search.png">&nbsp;&nbsp;Search
	                                    </div>
			            			</div>
			            	</div>
		            	</div>			
	            	</div>
            		
            		<div class='col-md-12'>
            			<br>
		            	<table id='billingstatementsearch-table'>
							<tbody></tbody>
						</table>
						<br>
					</div>
           	</div>
        </div>
    </div>
</div>


<div class="modal fade" id="blsscanwaybilltransactionmodal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Scan BOL
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
           		<div class='modal-errordiv'></div>
            	<div class='col-md-12'>
            		<div class='form-horizontal'>
            				<div class="form-group">
            					<label class='control-label'>BOL No.</label>
            					<input type='text' class='form-control blsscanwaybilltransactionmodal-waybillnumber'>
            				</div>
            				<div class="form-group">
	            					<div class='button-group-btn fluid active' id='blsscanwaybilltransactionmodal-addbtn'>
                                        <img src="../resources/img/add.png">&nbsp;&nbsp;Add BOL
                                    </div>
                            </div>
            		</div>
            		<br>
            	</div>
           	</div>
        </div>
    </div>
</div>


<div class="modal fade" id="togglepaidflagmodal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Payment Tagging
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='modal-errordiv'></div>
            	<div class='col-md-12'>
            		<div class='form-horizontal'>
            				<input type='hidden' id='togglepaidflagmodal-id'>
            				
	            			<div class="form-group">
	            				<label class='control-label'>Billing No.</label>
	            				<input type='text' class='form-input form-control togglepaidflagmodal-txnnumber' disabled="true">
	            				
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label'>Paid Flag</label>
	            					<select class='form-control togglepaidflagmodal-paidflag select2' style='width:100%'>
	            						<option value='0'>No</option>
	            						<option value='1'>Yes</option>
	            					</select>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label '>Remarks</label>
	            				<textarea class='form-control togglepaidflagmodal-remarks' rows='6'></textarea>
	            			</div>
	            	</div>
            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='togglepaidflagmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>


<div class="modal fade" id="blsuploadwaybillmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Upload Waybill
                    <button class="close" data-dismiss="modal">&times;</button>

                </div>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action='../scripts/billing-statement-waybill-upload.php' method='post' id='blsuploadwaybillmodal-form'  enctype='multipart/form-data' target='billingstatementuploadtransactionlogframe'>
                	<input type='hidden' class='blswaybilluploadbillingnumber' name='blswaybilluploadbillingnumber'>
                    <div class='col-md-4'>
                        Please make sure to follow the right format. Waybill Transactions with missing/incorrect details will not be uploaded in the system.
                        Click <a class='pointer' id='billing-statement-downloadtransactionfiletemplatebtn' href='../file-templates/billing-transaction-waybill-upload-template.xlsx'>here</a> to download file template.
                    </div>
                    <div class='col-md-offset-1 col-md-6'>
                    	<div class="form-group">
                            <label class='control-label'>Billing Number</label>
                            <input type='text' class='form-control blsuploadwaybillmodal-billingnumber' name='blsuploadwaybillmodal-billingnumber' disabled>
                        </div>
                        <div class="form-group">
                            <label class='control-label'>Select an Excel File</label>
                            <input type='file' class='form-control blsuploadwaybillmodal-file' name='blsuploadwaybillfile'>
                        </div>
                    </div>
                </form>
                <br>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button class='btn btn-blue2 mybtn' id='blsuploadwaybillmodal-uploadbtn'>Upload</button>
                    <button class='btn btn-blue2 mybtn modal-cancelbtn' >Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="blsuploadwaybilllogmodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Uploading Waybill...
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <iframe id="billingstatementuploadtransactionlogframe" name="billingstatementuploadtransactionlogframe" height="600" width="100%" frameborder="0" scrolling="yes" style='background: #fff'></iframe>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="billingstatementprintingmodal">
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
						<div class='modal-errordiv'></div>
						<div class="form-group">
							<label class='control-label col-sm-4'>Form Type</label>
							<div class='col-sm-8'>
								<select class='form-control billingstatementprintingmodal-formtype select2'>
									<option value="SERVICEINVOICE">Service Invoice</option>
									<option value="AWB">AWB</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class='modal-footer'>
				<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='billingstatementprintingmodal-printbtn'>Print</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	/**** INITIALIZED *******/
	$(document).ready(function(){
			var tabBLS = '#billingstatement-menutabpane';
			var inputfieldsBLS = ".billingstatement-inputfields";
			

			//$(tabBLS+' .modal-dialog').draggable();
			$(inputfieldsBLS+' input,'+inputfieldsBLS+' textarea,'+inputfieldsBLS+' select').attr('disabled','disabled');
        	$(inputfieldsBLS+' .transactionnumber').removeAttr('disabled').focus();
        	$(tabBLS+" .select2").select2();
        	var datetoday = new Date();
        	$(tabBLS+' .datepicker').datepicker();
        	$(tabBLS+' .datetimepicker').datetimepicker();

       
       		var refBLS = <?php echo json_encode($refBLS); ?>;
	        if(refBLS!=''){
	            getBillingStatementInformation(refBLS);
	            currentbillingstatementTxn = refBLS;
	        }

	        $(tabBLS+" .shipperdropdownselect").select2({
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

			$(tabBLS+" .shipmenttypedropdownselect").select2({
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
	                        return {
	                            results: data
	                        };
	                    },
	                    cache: true
	                },
	                minimumInputLength: 0,
	                width: '100%'
	    	});

	    	$(tabBLS+" .billingtypedropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/billing-type.php",
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

	    	$(tabBLS+" .accountexecutivedropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/account-executive.php",
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

	    	$(tabBLS+" .addressdistrictdropdownselect").select2({
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

		    $(tabBLS+" .addresscitydropdownselect").select2({
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

		    $(tabBLS+" .addresszipcodedropdownselect").select2({
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

		    $(tabBLS+" .addressregiondropdownselect").select2({
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

		    $(tabBLS+" .countriesdropdownselect").select2({
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
		                width:'100%'
		    });

		    $(tabBLS+" .billingstatementstatusdropdownselect").select2({
                ajax: {
                        url: "loadables/dropdown/billing-statement-status.report.php",
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


	        function blsAddWaybillNumber(){
	    		$(tabBLS+' #blsaddwaybillnumbermodal').modal('show');
	    	}

	    	function blsDeleteWaybillNumber(){
	    		wbnumbersid = [];
	    		txnnumber = $(tabBLS+' #pgtxnbillingstatement-id').attr('pgtxnbillingstatement-number');

	    		$(tabBLS+' .blswaybillcheckbox:checked').each(function(){
	    			wbnumbersid.push($(this).attr('rowid'));
	    		});

	    		if(wbnumbersid.length>0){
		    		$.post(server+'billing-statement.php',{deleteWaybillNumber:'dskljouioU#ouh$3ksk#Op1NEi34smo1sonk&$',wbnumbersid:wbnumbersid},function(data){

		    			if(data.trim()=='success'){
		    				$(tabBLS+' #billingstatement-detailstbl').flexOptions({
												url:'loadables/ajax/transactions.billing-statement-waybill.php?reference='+txnnumber,
												sortname: 'waybill_number',
												sortorder: "asc"
							}).flexReload();

							getBillingComputationBLS(txnnumber);

						
		    			}
		    			else{
		    				alert(data);
		    			}



		    		});
		    	}
	    	}

	    	function blsSelectAllWaybillNumber(){
	    		$(tabBLS+' .blswaybilllookup-checkbox').prop('checked',true);
	    	}

	    	function blsDeselectAllWaybillNumber(){
	    		$(tabBLS+' .blswaybilllookup-checkbox').prop('checked',false);
	    	}


	    	function blsSearchForWaybill(){
	    		$(tabBLS+' #blssearchwaybilltransactionmodal').modal('show');
	    	}

	    	function blsScanWaybillNumber(){
	    		$(tabBLS+' #blsscanwaybilltransactionmodal').modal('show');
	    	}



			$(tabBLS+' #billingstatement-detailstbl').flexigrid({
				url: 'loadables/ajax/transactions.billing-statement-waybill.php?reference='+refBLS,
				dataType: 'json',
				colModel : [
						{display: '', name : 'action', width : 40, sortable : false, align: 'center'},
						{display: 'status', name : 'detailstatus', width : 150, sortable : true, align: 'left'},
						{display: 'BOL No.', name : 'txn_billing_waybill.waybill_number', width : 130, sortable : true, align: 'left'},
						{display: 'MAWB', name : 'txn_waybill.mawbl_bl', width : 130, sortable : true, align: 'left'},
						{display: 'Document Date', name : 'txn_waybill.document_date', width : 150, sortable : true, align: 'left'},
						{display: 'Origin', name : 'origin', width : 200, sortable : true, align: 'left'},
						{display: 'Destination', name : 'destination', width : 200, sortable : true, align: 'left'},
						{display: 'Mode of Transport', name : 'modeoftransport', width : 180, sortable : true, align: 'left'},
						{display: 'Chargeable Weight', name : 'txn_billing_waybill.chargeable_weight', width : 180, sortable : true, align: 'right'},
						{display: 'Total Vatable Charges', name : 'txn_billing_waybill.vatable_charges', width : 180, sortable : true, align: 'right'},
						{display: 'Non-Vatable Charges', name : 'txn_billing_waybill.other_charges_non_vatable', width : 180, sortable : true, align: 'right'},
						{display: 'VAT', name : 'txn_billing_waybill.vat', width : 180, sortable : true, align: 'right'},
						{display: 'Amount', name : 'txn_billing_waybill.amount', width : 180, sortable : true, align: 'right'}
						

				],
				buttons : [
						{separator: true},
						{name: 'Scan', bclass: 'qrcode blsscanwaybillbtn hidden', onpress : blsScanWaybillNumber},
						
						{name: 'Add', bclass: 'add blswaybilllookupbtn hidden', onpress : blsSearchForWaybill},
						{name: 'Delete', bclass: 'delete blsdeletewaybillbtn hidden', onpress : blsDeleteWaybillNumber},
						{name: 'Upload', bclass: 'upload blsuploadwaybillbtn hidden', onpress : ''}

						
				],
				searchitems : [
						{display: 'BOL No.', name : 'txn_billing_waybill.waybill_number', isdefault: true},
						{display: 'Origin', name : 'origin'},
						{display: 'Destination', name : 'destination'},
						{display: 'Mode', name : 'modeoftransport'}
				],
				sortname: "txn_billing_waybill.waybill_number",
				sortorder: "asc",
				usepager: true,
				title: "",
				useRp: true,
				rp: 10, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 250,
				singleSelect: false,
				disableSelect: true
			});

			$(tabBLS+' #blssearchwaybilltransactiontbl').flexigrid({
				url: 'loadables/ajax/transactions.billing-statement-waybill-lookup.php',
				dataType: 'json',
				colModel : [
						{display: '', name : 'action', width : 40, sortable : false, align: 'center'},
						{display: 'BOL No.', name : 'txn_waybill.waybill_number', width : 150, sortable : true, align: 'left'},
						{display: 'MAWBL', name : 'txn_waybill.mawbl_bl', width : 150, sortable : true, align: 'left'},
						{display: 'Date', name : 'txn_waybill.document_date', width : 130, sortable : true, align: 'left'},
						{display: 'Requested Delivery', name : 'txn_waybill.delivery_date', width : 130, sortable : true, align: 'left'},
						{display: 'Status', name : 'txn_waybill.status', width : 130, sortable : true, align: 'left'},
						{display: 'Type', name : 'txn_waybill.waybill_type', width : 100, sortable : true, align: 'left'},
						{display: 'Consignee', name : 'txn_waybill.consignee_account_name', width : 200, sortable : true, align: 'left'},
						{display: 'Origin', name : 'origin', width : 180, sortable : true, align: 'left'},
						{display: 'Destination', name : 'destination', width : 180, sortable : true, align: 'left'},
						{display: 'Mode of Transport', name : 'modeoftransport', width : 200, sortable : true, align: 'left'}
						

				],
				buttons : [
						{name: 'Add', bclass: 'add blswaybilllookup-addbtn'},
						{separator: true},
						{name: 'Select All', bclass: 'select1 blswaybilllookup-selectallbtn',onpress : blsSelectAllWaybillNumber},
						{separator: true},
						{name: 'Deselect All', bclass: 'deselect1 blswaybilllookup-deselectallbtn',onpress : blsDeselectAllWaybillNumber}
				],
				searchitems : [
						
						{display: 'Bol No.', name : 'txn_waybill.waybill_number', isdefault: true},
						{display: 'Mawbl', name : 'txn_waybill.mawbl_bl'},
						{display: 'Status', name : 'txn_waybill.status'},
						{display: 'Origin', name : 'origin'},
						{display: 'Destination', name : 'destination'},
						{display: 'Mode of Transport', name : 'modeoftransport'},
						{display: 'Consignee', name : 'txn_waybill.consignee_account_name'}

				],
				sortname: "txn_waybill.waybill_number",
				sortorder: "asc",
				usepager: true,
				title: "",
				useRp: true,
				rp: 50, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 550,
				singleSelect: false,
				disableSelect: true
			});



			$(tabBLS+" #billingstatementsearch-table").flexigrid({
				url: 'loadables/ajax/transactions.billing-statement-search.php',
				dataType: 'json',
				colModel : [
						{display: 'Billing No.', name : 'txn_billing.billing_number', width : 120, sortable : true, align: 'left'},
						{display: 'Invoice', name : 'txn_billing.invoice', width : 100, sortable : true, align: 'left'},
						{display: 'Status', name : 'txn_billing.status', width : 100, sortable : true, align: 'left'},
						{display: 'Paid', name : 'paidflag', width : 100, sortable : true, align: 'left'},
						{display: 'Shipper', name : 'shipper.account_name', width : 400, sortable : true, align: 'left'},
						{display: 'Document Date', name : 'txn_billing.document_date', width : 100, sortable : true, align: 'left'},
						{display: 'No. of BOLs', name : 'waybillcount', width : 120, sortable : true, align: 'right'},
						{display: 'Created by', name : 'txn_billing.created_by', width : 150, sortable : true, align: 'left'},
						{display: 'Created Date', name : 'txn_billing.created_date', width : 150, sortable : true, align: 'left'},
						{display: 'System ID', name : 'txn_billing.id', width : 80, sortable : true, align: 'left', hide:true}
				],
				
				searchitems : [
						{display: 'Invoice', name : 'txn_billing.invoice', isdefault: true},
						{display: 'Billing No.', name : 'txn_billing.billing_number'},
						{display: 'Status', name : 'txn_billing.status'},
						{display: 'Shipper', name : 'shipper.account_name'}
				],
				sortname: "txn_billing.billing_number",
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







        
			
    		userAccess();


			

	});
	



</script>	