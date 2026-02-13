<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Shipper
	</div>

</div>
<div class="container-fluid">
    <div class="pageContent noborder">

    	<table id='shippertable'>
			<tbody></tbody>

		</table>


	</div>
</div>


<div class="modal fade" id="addshippermodal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					New
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<div class="col-md-12">
						<div class="form-horizontal">

							<div class="form-group">
								<div class="errordiv"></div>
							</div>

										

							<div class='row'>
								<div class='tabpane-white margin-top-20 margin-bottom-10 tabpanesection'>
									<ul class="nav nav-tabs">
										<li role="presentation" class="active" data-pane='#shipperaddgeninfo-pane' id='shipperaddgeninfo-tab'><a href="#">General Information</a></li>
										<li role="presentation" data-pane='#shipperaddaddress-pane' id='shipperaddaddress-tab'><a href="#">Address</a></li>
										<li role="presentation" data-pane='#shipperaddcontact-pane' id='shipperaddcontact-tab'><a href="#">Contact</a></li>
										<!--<li role="presentation" data-pane='#shipperaddrate-pane' id='shipperaddrate-tab'><a href="#">Rate</a></li>-->
									</ul>
									<div class='tab-panes'>

										<div class='pane active' id='shipperaddgeninfo-pane'>
											<fieldset>
												<legend>Shipper Information</legend>
												<div class='col-md-8'>
													<div class="form-group hidden">
														<label class='control-label col-md-3'>Account No.*</label>
														<div class='col-md-9'>
															<input type='text' class='form-input form-control inputtxtfld accountnumber'>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-3'>Account Name*</label>
														<div class='col-md-9'>
															<input type='text' class='form-input form-control inputtxtfld accountname'>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-3'>Company Name*</label>
														<div class='col-md-9'>
															<input type='text' class='form-input form-control inputtxtfld companyname'>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-3'>TIN</label>
														<div class='col-md-9'>
															<input type='text' class='form-input form-control inputtxtfld tin'>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-3'>Billing in Charge</label>
														<div class='col-md-9'>
															<select class='form-input form-control inputslctfld billingincharge userdropdownselect'></select>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-3'>Account Exective</label>
														<div class='col-md-9'>
															<select class='form-input form-control inputslctfld accountexecutivedropdownselect accountexecutive'></select>
														</div>
													</div>
													
													<div class="form-group">
														<label class='control-label col-md-3'>Business Type</label>
														<div class='col-md-9'>
															<input type='text' class='form-input form-control inputtxtfld businessstyle'>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-3'>Line of Business</label>
														<div class='col-md-9'>
															<input type='text' class='form-input form-control inputtxtfld lineofbusiness'>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-3'>POD Instruction</label>
														<div class='col-md-9'>
															<textarea class='form-control inputtxtfld podinstruction' rows='3'></textarea>
														</div>
													</div>
												</div>
												<div class='col-md-4'>
													<div class="form-group">
														<label class='control-label col-md-5'>Non-POD Flag</label>
														<div class='col-md-7'>
															<select class='form-input form-control select2 inputslctfld nonpodflag'>
																<option value='0'>No</option>
																<option value='1'>Yes</option>
																
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-5'>VAT Flag</label>
														<div class='col-md-7'>
															<select class='form-input form-control select2 inputslctfld vatflag'>
																<option value='0'>No</option>
																<option value='1'>Yes</option>
																
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-5'>Status</label>
														<div class='col-md-7'>
															<select class='form-input form-control select2 inputslctfld shipperstatus'>
																<option value='ACTIVE'>Active</option>
																<option value='SUSPENDED'>Suspended</option>
																<option value='DORMANT'>Dormant</option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-5'>Pay Mode*</label>
														<div class='col-md-7'>
															<select class='form-input form-control inputslctfld paymode paymodedropdownselect'>
																
															</select>
														</div>
													</div>
													
													
												</div>
												
											</fieldset>

											<fieldset>
												<legend>Collection Information</legend>
												<div class='col-md-5'>
													<div class="form-group">
														<label class='control-label col-md-4'>Collection Term</label>
														<div class='col-md-8'>
															<input type='text' class='form-input form-control inputtxtfld creditterm'>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-4'>Credit Limit</label>
														<div class='col-md-8'>
															<input type='text' class='form-input form-control inputtxtfld creditlimit'>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-4'>Billing Cut-off</label>
														<div class='col-md-8'>
															<input type='text' class='form-input form-control inputtxtfld billingcutoff'>
														</div>
													</div>
													
												</div>
												<div class='col-md-7'>
													<div class="form-group">
														<label class='control-label col-md-4'>Collection Contact Person</label>
														<div class='col-md-8'>
															<input type='text' class='form-input form-control inputtxtfld collectioncontactperson'>
														</div>
													</div>
													
													<div class="form-group">
														<label class='control-label col-md-4'>Collection day</label>
														<div class='col-md-8'>
															<input type='text' class='form-input form-control inputtxtfld collectionday'>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-4'>Collection location</label>
														<div class='col-md-8'>
															<input type='text' class='form-input form-control inputtxtfld collectionlocation'>
														</div>
													</div>
												</div>
											</fieldset>

											
										</div>
										<div class='pane' id='shipperaddaddress-pane'>	

											<fieldset>
												<legend>Company Address*</legend>
												
												<div class='addressgroupwrapper'>
													<div class="form-group">
														<label class='control-label col-md-2'>Region/Province*</label>
														<div class='col-md-8'>
															<select class='form-control inputslctfld companyprovince addrdropregion addressregiondropdownselect'></select>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-2'>City*</label>
														<div class='col-md-7'>
															<select class='form-control inputslctfld companycity addrdropcity addresscitydropdownselect'></select>
														</div>
													</div>
													
													<div class="form-group">
														<label class='control-label col-md-2'>District/Barangay*</label>
														<div class='col-md-6'>
															<select class='form-control inputslctfld companydistrict addrdropdistrict addressdistrictdropdownselect'></select>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-2'>Zip Code*</label>
														<div class='col-md-3'>
															<select class='form-control inputslctfld companyzipcode addrdropzip addresszipcodedropdownselect'></select>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-2'>Street*</label>
														<div class='col-md-8'>
															<input type='text' class='form-input form-control inputtxtfld companystreet'>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-2'>Country*</label>
														<div class='col-md-8'>
															<select class='form-control companycountry inputslctfld countriesdropdownselect addrdropcountry'></select>
														</div>
													</div>
												</div>
											</fieldset>

											<fieldset>
												<legend>Billing Address*</legend>
												
												<div class='addressgroupwrapper'>
													<div class="form-group">
														<label class='control-label col-md-2'>Region/Province*</label>
														<div class='col-md-8'>
															<select class='form-control inputslctfld billingprovince addrdropregion addressregiondropdownselect'></select>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-2'>City*</label>
														<div class='col-md-7'>
															<select class='form-control inputslctfld billingcity addrdropcity addresscitydropdownselect'></select>
														</div>
													</div>
													
													<div class="form-group">
														<label class='control-label col-md-2'>District/Barangay*</label>
														<div class='col-md-6'>
															<select class='form-control inputslctfld billingdistrict addrdropdistrict addressdistrictdropdownselect'></select>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-2'>Zip Code*</label>
														<div class='col-md-3'>
															<select class='form-control inputslctfld billingzipcode addrdropzip addresszipcodedropdownselect'></select>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-2'>Street*</label>
														<div class='col-md-8'>
															<input type='text' class='form-input form-control inputtxtfld billingstreet'>
														</div>
													</div>
													
													<div class="form-group">
														<label class='control-label col-md-2'>Country*</label>
														<div class='col-md-8'>
															<select class='form-control billingcountry inputslctfld countriesdropdownselect'></select>
														</div>
													</div>
												</div>
											</fieldset>
											<fieldset>
												<legend>Pickup Address*</legend>
												<fieldset>
													<div class='addressgroupwrapper'>
														<div class='col-md-6'>
															
															
															<div class="form-group">
																<label class='control-label col-md-3'>Region/Province*</label>
																<div class='col-md-9'>
																	<select class='form-control inputslctfld pickupprovincefld addrdropregion addressregiondropdownselect'></select>
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-3'>City*</label>
																<div class='col-md-9'>
																	<select class='form-control inputslctfld pickupcityfld addrdropcity addresscitydropdownselect'></select>
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-3'>District/Barangay*</label>
																<div class='col-md-9'>
																	<select class='form-control inputslctfld pickupdistrictfld addrdropdistrict addressdistrictdropdownselect'></select>
																</div>
															</div>
															


														</div>
														<div class='col-md-6'>
															
															
															
															<div class="form-group">
																<label class='control-label col-md-3'>Zip Code*</label>
																<div class='col-md-9'>
																	<select class='form-control inputslctfld pickupzipcodefld addrdropzip addresszipcodedropdownselect'></select>
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-3'>Street*</label>
																<div class='col-md-9'>
																	<input type='text' class='form-input form-control inputtxtfld pickupstreetfld'>
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-3'>Country*</label>
																<div class='col-md-9'>
																	<select class='form-control pickupcountryfld inputslctfld countriesdropdownselect'></select>
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-3'></label>
																<div class='col-md-9'>
																	<div class='smallbuttons-wrapper'>
																		<button class='btn mybtn shipper-insertpickupdaddressbtn datatablebtn'>
																			<i class='fa fa-xs fa-plus'></i>Add
																		</button>
																		<button class='btn mybtn shipper-removepickupdaddressbtn datatablebtn'>
																		    <i class='fa fa-xs fa-trash'></i>Remove
																		</button>
																		<button class='btn mybtn shipper-clearpickupdaddressbtn datatablebtn'>
																		    <i class='fa fa-xs fa-refresh'></i>Clear
																		</button>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</fieldset>
												<div class='table-sm'>
													<table class='table table-condensed table-hover pointer table-striped table-bordered text-nowrap mytable gray-template table-font-sm no-side-borders shipperpickupaddressdetailstbl' id='shipper-addpickupaddressdetailstbl' style='width:100%'>
									    				<thead>
									    					<tr>
									    						<th class='column-nosort column-checkbox text-center'></th>
									    						<th class='column-nosort column-checkbox'>DEFAULT</th>
									    						<th>STREET</th>
									    						<th>DISTRICT</th>
															    <th>CITY</th>
									    						<th>REGION/PROVINCE</th>
									    						<th>ZIP CODE</th>
									    						<th>COUNTRY</th>
									    					</tr>
									    				</thead>
									    				<tbody>
									    					
									    				</tbody>
									    			</table>
									    		</div>
								    		</fieldset>
										</div>

										<div class='pane' id='shipperaddcontact-pane'>
											<fieldset>
												<legend>Contact Information*</legend>
												<fieldset>
													<div class='col-md-6'>
														<div class="form-group">
															<label class='control-label col-md-3'>Contact Person*</label>
															<div class='col-md-9'>
																<input type='text' class='form-input form-control inputtxtfld contactfld'>
															</div>
														</div>
														<div class="form-group">
															<label class='control-label col-md-3'>Phone Number**</label>
															<div class='col-md-9'>
																<input type='text' class='form-input form-control inputtxtfld phonenumberfld'>
															</div>
														</div>
													</div>
													<div class='col-md-6'>
														<div class="form-group">
															<label class='control-label col-md-3'>Email**</label>
															<div class='col-md-9'>
																<input type='text' class='form-input form-control inputtxtfld emailfld'>
															</div>
														</div>
														<div class="form-group">
															<label class='control-label col-md-3'>Mobile Number**</label>
															<div class='col-md-9'>
																<input type='text' class='form-input form-control inputtxtfld mobilenumberfld'>
															</div>
														</div>
														<div class="form-group">
															<label class='control-label col-md-3'></label>
															<div class='col-md-9'>
																<div class='smallbuttons-wrapper'>
																	<button class='btn mybtn shipper-insertcontactbtn datatablebtn'>
																		<i class='fa fa-xs fa-plus'></i>Add
																	</button>
																	<button class='btn mybtn shipper-removecontactbtn datatablebtn'>
																	    <i class='fa fa-xs fa-trash'></i>Remove
																	</button>
																	<button class='btn mybtn shipper-clearcontactfieldsbtn datatablebtn'>
																	    <i class='fa fa-xs fa-refresh'></i>Clear
																	</button>
																</div>
															</div>
														</div>
													</div>
												</fieldset>
												<div class='table-sm'>
													<table class='table table-condensed table-hover pointer table-striped table-bordered text-nowrap mytable gray-template table-font-sm no-side-borders shippercontactdetailstbl' id='shipper-addcontactdetailtbl' style='width:100%'>
									    				<thead>
									    					<tr>
									    						<th class='column-nosort column-checkbox text-center'></th>
									    						<th class='column-nosort column-checkbox'>DEFAULT</th>
									    						<th class='column-nosort column-checkbox'>SEND SMS</th>
									    						<th class='column-nosort column-checkbox'>SEND EMAIL</th>
															    <th>CONTACT</th>
									    						<th>PHONE NUMBER</th>
									    						<th>EMAIL</th>
									    						<th>MOBILE</th>
									    					</tr>
									    				</thead>
									    				<tbody>
									    					
									    				</tbody>
									    			</table>
									    		</div>
								    		</fieldset>
										</div>
										<!--<div class='pane' id='shipperaddrate-pane'>

											<fieldset>
												<legend>Custom Rates</legend>
												<fieldset>
													<div class='col-md-7'>
														<div class="form-group">
															<label class='control-label col-md-3'>Origin</label>
															<div class='col-md-9'>
																<select class='form-input form-control originfld origindestinationdropdownselect inputslctfld noresetfld' style='width:100%'></select>
															</div>
														</div>	
														<div class="form-group">
																<label class='control-label col-md-3'>Destination</label>
																<div class='col-md-9'>
																	<select class='form-input form-control destinationfld origindestinationdropdownselect inputslctfld noresetfld' style='width:100%'></select>
																</div>
														</div>	
														<div class="form-group">
																<label class='control-label col-md-3'>Mode of Transport</label>	
																<div class='col-md-9'>
																	<select class='form-input form-control modeoftransportfld modeoftransportdropdownselect inputslctfld noresetfld' style='width:100%'></select>
																</div>
														</div>
														<div class="form-group">
																<label class='control-label col-md-3'>Freight Computation</label>
																<div class='col-md-9'>	
																	<select class='form-input form-control freightcomputationfld freightcomputationdropdownselect inputslctfld' style='width:100%'></select>
																</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-3">Rush Flag &nbsp;&nbsp;</label>
															<div class='col-md-9'>
																<input type="checkbox" class='togglebutton rushflagfld activeflag'>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-3">Pull Out Flag &nbsp;&nbsp;</label>
															<div class='col-md-9'>
																<input type="checkbox" class='togglebutton pulloutflagfld activeflag'>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-3">Fixed Rate &nbsp;&nbsp;</label>
															<div class='col-md-9'>
																<input type="checkbox" class='togglebutton fixedrateflagfld'>
															</div>
														</div>
													</div>
													<div class='col-md-5'>
														<div class="form-group">
															<label class='control-label col-md-4'>Valuation</label>
															<div class='col-md-8'>
																<input type='number' class='form-input form-control valuationfld inputtxtfld text-right' placeholder="%">
															</div>
														</div>
														<div class="form-group">
															<label class='control-label col-md-4'>Freight Rate</label>
															<div class='col-md-8'>
																<input type='number' class='form-input form-control freightratefld inputtxtfld text-right'>
															</div>
														</div>
														<div class="form-group">
															<label class='control-label col-md-4'>Insurance Rate</label>
															<div class='col-md-8'>
																<input type='number' class='form-input form-control insuranceratefld inputtxtfld text-right'>
															</div>
														</div>
														<div class="form-group">
															<label class='control-label col-md-4'>Fuel Rate</label>
															<div class='col-md-8'>
																<input type='number' class='form-input form-control fuelratefld inputtxtfld text-right'>
															</div>
														</div>
														<div class="form-group">
															<label class='control-label col-md-4'>Bunker Rate</label>
															<div class='col-md-8'>
																<input type='number' class='form-input form-control bunkerratefld inputtxtfld text-right'>
															</div>
														</div>
														<div class="form-group">
															<label class='control-label col-md-4'>Minimum Rate</label>
															<div class='col-md-8'>
																<input type='number' class='form-input form-control minimumratefld inputtxtfld text-right'>
															</div>
														</div>
														<div class="form-group">
															<label class='control-label col-md-4'></label>
															<div class='col-md-8'>
																<div class='smallbuttons-wrapper'>
																	<button class='btn mybtn shipper-insertratebtn datatablebtn'>
																		<i class='fa fa-xs fa-plus'></i>Add
																	</button>
																	<button class='btn mybtn shipper-removeratebtn datatablebtn'>
																	    <i class='fa fa-xs fa-trash'></i>Remove
																	</button>
																	<button class='btn mybtn shipper-clearratefieldsbtn datatablebtn'>
																	    <i class='fa fa-xs fa-refresh'></i>Clear
																	</button>
																</div>
															</div>
														</div>
													</div>
												</fieldset>
												<div class='table-sm'>
													<table class='table table-condensed table-hover pointer table-striped table-bordered text-nowrap mytable gray-template table-font-sm no-side-borders shipperratedetailstbl' id='shipper-addratedetailtbl' style='width:100%'>
									    				<thead>
									    					<tr>
									    						<th class='column-nosort column-checkbox text-center'></th>
									    						<th class='column-nosort column-checkbox text-center'>FIXED RATE</th>
									    						<th class='column-nosort column-checkbox text-center'>RUSH</th>
									    						<th class='column-nosort column-checkbox text-center'>PULL OUT</th>
															    <th>ORIGIN</th>
									    						<th>DESTINATION</th>
									    						<th>MODE OF TRANSPORT</th>
									    						<th>FREIGHT COMPUTATION</th>
									    						<th>VALUATION</th>
									    						<th>FREIGHT RATE</th>
									    						<th>INSURANCE RATE</th>
									    						<th>FUEL RATE</th>
									    						<th>BUNKER RATE</th>
									    						<th>MINIMUM RATE</th>
									    					</tr>
									    				</thead>
									    				<tbody>
									    					
									    				</tbody>
									    			</table>
									    		</div>
								    		</fieldset>

										</div>-->

									</div>


								</div>
							</div>


						</div>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn shippermodal-savebtn' id='addshippermodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editshippermodal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Edit
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<div class="col-md-12">
						<div class="form-horizontal">
							<div class="form-group">
								<div class="errordiv"></div>
							</div>
							<input type='hidden' class='shippermodalid mdlIDfld'>

							<div class='row'>
								<div class='tabpane-white margin-top-20 margin-bottom-10 tabpanesection'>
									<ul class="nav nav-tabs">
										<li role="presentation" class="active" data-pane='#shippereditgeninfo-pane' id='shippereditgeninfo-tab'><a href="#">General Information</a></li>
										<li role="presentation" data-pane='#shippereditaddress-pane' id='shippereditaddress-tab'><a href="#">Address</a></li>
										<li role="presentation" data-pane='#shippereditcontact-pane' id='shippereditcontact-tab'><a href="#">Contact</a></li>
										<!--<li role="presentation" data-pane='#shippereditrate-pane' id='shippereditrate-tab'><a href="#">Rate</a></li>-->
									</ul>
									<div class='tab-panes'>

										<div class='pane active' id='shippereditgeninfo-pane'>
											<fieldset>
												<legend>Shipper Information</legend>
												<div class='col-md-8'>	
													
													<div class="form-group">
														<label class='control-label col-md-3'>Account No.*</label>
														<div class='col-md-9'>
															<input type='text' class='form-input form-control inputtxtfld accountnumber'>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-3'>Account Name*</label>
														<div class='col-md-9'>
															<input type='text' class='form-input form-control inputtxtfld accountname'>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-3'>Company Name*</label>
														<div class='col-md-9'>
															<input type='text' class='form-input form-control inputtxtfld companyname'>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-3'>TIN</label>
														<div class='col-md-9'>
															<input type='text' class='form-input form-control inputtxtfld tin'>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-3'>Billing in Charge</label>
														<div class='col-md-9'>
															<select class='form-input form-control inputslctfld billingincharge userdropdownselect'></select>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-3'>Account Exective</label>
														<div class='col-md-9'>
															<select class='form-input form-control inputslctfld accountexecutivedropdownselect accountexecutive'></select>
														</div>
													</div>

													<div class="form-group">
														<label class='control-label col-md-3'>Business Type</label>
														<div class='col-md-9'>
															<input type='text' class='form-input form-control inputtxtfld businessstyle'>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-3'>Line of Business</label>
														<div class='col-md-9'>
															<input type='text' class='form-input form-control inputtxtfld lineofbusiness'>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-3'>POD Instruction</label>
														<div class='col-md-9'>
															<textarea class='form-control inputtxtfld podinstruction' rows='3'></textarea>
														</div>
													</div>
												</div>
												<div class='col-md-4'>
													<div class="form-group">
														<label class='control-label col-md-5'>Inactive</label>
														<div class='col-md-7'>
															<select class='form-control inputslctfld inactiveflag select2'>
																<option value='1'>Yes</option>
																<option value='0'>No</option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-5'>Non-POD Flag</label>
														<div class='col-md-7'>
															<select class='form-input form-control select2 inputslctfld nonpodflag'>
																<option value='0'>No</option>
																<option value='1'>Yes</option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-5'>VAT Flag</label>
														<div class='col-md-7'>
															<select class='form-input form-control select2 inputslctfld vatflag'>
																<option value='0'>No</option>
																<option value='1'>Yes</option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-5'>Status</label>
														<div class='col-md-7'>
															<select class='form-input form-control select2 inputslctfld shipperstatus'>
																<option value='ACTIVE'>Active</option>
																<option value='SUSPENDED'>Suspended</option>
																<option value='DORMANT'>Dormant</option>
															</select>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-5'>Pay Mode*</label>
														<div class='col-md-7'>
															<select class='form-input form-control inputslctfld paymode paymodedropdownselect'>
																
															</select>
														</div>
													</div>
												</div>
												
											</fieldset>

											<fieldset>
												<legend>Collection Information</legend>
												<div class='col-md-5'>
													<div class="form-group">
														<label class='control-label col-md-4'>Collection Term</label>
														<div class='col-md-8'>
															<input type='text' class='form-input form-control inputtxtfld creditterm'>
														</div>
													</div>
													<div class="form-group creditlimitwrapper hidden">
														<label class='control-label col-md-4'>Credit Limit</label>
														<div class='col-md-8'>
															<div class="input-group">
																<input type='text' class='form-input form-control inputtxtfld creditlimit'>
																<span class="input-group-addon inputgroupbtn">
																	<img src="../resources/img/info.png" style='height: 24px; cursor: pointer;' id='shipper-creditinfolookupbtn' title="Credit Info">
																</span>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-4'>Billing Cut-off</label>
														<div class='col-md-8'>
															<input type='text' class='form-input form-control inputtxtfld billingcutoff'>
														</div>
													</div>
													
												</div>
												<div class='col-md-7'>
													<div class="form-group">
														<label class='control-label col-md-4'>Collection Contact Person</label>
														<div class='col-md-8'>
															<input type='text' class='form-input form-control inputtxtfld collectioncontactperson'>
														</div>
													</div>
													
													<div class="form-group">
														<label class='control-label col-md-4'>Collection day</label>
														<div class='col-md-8'>
															<input type='text' class='form-input form-control inputtxtfld collectionday'>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-4'>Collection location</label>
														<div class='col-md-8'>
															<input type='text' class='form-input form-control inputtxtfld collectionlocation'>
														</div>
													</div>
												</div>
											</fieldset>

											
										</div>
										<div class='pane' id='shippereditaddress-pane'>	

											<fieldset>
												<legend>Company Address*</legend>
												<div class='addressgroupwrapper'>
													<div class="form-group">
														<label class='control-label col-md-2'>Region/Province*</label>
														<div class='col-md-8'>
															<select class='form-control inputslctfld companyprovince editdropregion addressregiondropdownselect'></select>
														</div>
													</div>
													
													<div class="form-group">
														<label class='control-label col-md-2'>City*</label>
														<div class='col-md-7'>
															<select class='form-control inputslctfld companycity editdropcity addresscitydropdownselect'></select>
														</div>
													</div>
													
													<div class="form-group">
														<label class='control-label col-md-2'>District/Barangay*</label>
														<div class='col-md-6'>
															<select class='form-control inputslctfld companydistrict editdropdistrict addressdistrictdropdownselect'></select>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-2'>Zip Code*</label>
														<div class='col-md-3'>
															<select class='form-control inputslctfld companyzipcode editdropzip addresszipcodedropdownselect'></select>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-2'>Street*</label>
														<div class='col-md-8'>
															<input type='text' class='form-input form-control inputtxtfld companystreet'>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-2'>Country*</label>
														<div class='col-md-8'>
															<select class='form-control companycountry inputslctfld countriesdropdownselect'></select>
														</div>
													</div>
												</div>
											</fieldset>

											<fieldset>
												<legend>Billing Address*</legend>
												<div class='addressgroupwrapper'>
													<div class="form-group">
														<label class='control-label col-md-2'>Region/Province*</label>
														<div class='col-md-8'>
															<select class='form-control inputslctfld billingprovince editdropregion addressregiondropdownselect'></select>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-2'>City*</label>
														<div class='col-md-7'>
															<select class='form-control inputslctfld billingcity editdropcity addresscitydropdownselect'></select>
														</div>
													</div>
													
													<div class="form-group">
														<label class='control-label col-md-2'>District/Barangay*</label>
														<div class='col-md-6'>
															<select class='form-control inputslctfld billingdistrict editdropdistrict addressdistrictdropdownselect'></select>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-2'>Zip Code*</label>
														<div class='col-md-3'>
															<select class='form-control inputslctfld billingzipcode editdropzip addresszipcodedropdownselect'></select>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-2'>Street*</label>
														<div class='col-md-8'>
															<input type='text' class='form-input form-control inputtxtfld billingstreet'>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-2'>Country*</label>
														<div class='col-md-8'>
															<select class='form-control billingcountry inputslctfld countriesdropdownselect'></select>
														</div>
													</div>
												</div>
											</fieldset>

											<fieldset>
												<legend>Pickup Address*</legend>
												<fieldset>
													<div class='addressgroupwrapper'>
														<div class='col-md-6'>
															
															<div class="form-group">
																<label class='control-label col-md-3'>Region/Province*</label>
																<div class='col-md-9'>
																	<select class='form-control inputslctfld pickupprovincefld addrdropregion addressregiondropdownselect'></select>
																</div>
															</div>
															
															<div class="form-group">
																<label class='control-label col-md-3'>City*</label>
																<div class='col-md-9'>
																	<select class='form-control inputslctfld pickupcityfld addrdropcity addresscitydropdownselect'></select>
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-3'>District/Barangay*</label>
																<div class='col-md-9'>
																	<select class='form-control inputslctfld pickupdistrictfld addrdropdistrict addressdistrictdropdownselect'></select>
																</div>
															</div>
															
														</div>
														<div class='col-md-6'>
															
															<div class="form-group">
																<label class='control-label col-md-3'>Zip Code*</label>
																<div class='col-md-9'>
																	<select class='form-control inputslctfld pickupzipcodefld addrdropzip addresszipcodedropdownselect'></select>
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-3'>Street*</label>
																<div class='col-md-9'>
																	<input type='text' class='form-input form-control inputtxtfld pickupstreetfld'>
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-3'>Country*</label>
																<div class='col-md-9'>
																	<select class='form-control pickupcountryfld countriesdropdownselect'></select>
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-3'></label>
																<div class='col-md-9'>
																	<div class='smallbuttons-wrapper'>
																		<button class='btn mybtn shipper-insertpickupdaddressbtn datatablebtn'>
																			<i class='fa fa-xs fa-plus'></i>Add
																		</button>
																		<button class='btn mybtn shipper-removepickupdaddressbtn datatablebtn'>
																		    <i class='fa fa-xs fa-trash'></i>Remove
																		</button>
																		<button class='btn mybtn shipper-clearpickupdaddressbtn datatablebtn'>
																		    <i class='fa fa-xs fa-refresh'></i>Clear
																		</button>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</fieldset>
												<div class='table-sm'>
													<table class='table table-condensed table-hover pointer table-striped table-bordered text-nowrap mytable gray-template table-font-sm no-side-borders shipperpickupaddressdetailstbl' id='shipper-editpickupaddressdetailstbl' style='width:100%'>
									    				<thead>
									    					<tr>
									    						<th class='column-nosort column-checkbox text-center'></th>
									    						<th class='column-nosort column-checkbox'>DEFAULT</th>
									    						<th>STREET</th>
									    						<th>DISTRICT/BARANGAY</th>
															    <th>CITY</th>
									    						<th>REGION/PROVINCE</th>
									    						<th>ZIP CODE</th>
									    						<th>COUNTRY</th>
									    					</tr>
									    				</thead>
									    				<tbody>
									    					
									    				</tbody>
									    			</table>
									    		</div>
								    		</fieldset>
										</div>
										<div class='pane' id='shippereditcontact-pane'>	
											<fieldset>
												<legend>Contact Information*</legend>
												<fieldset>
													<div class='col-md-6'>
														<div class="form-group">
															<label class='control-label col-md-3'>Contact Person*</label>
															<div class='col-md-9'>
																<input type='text' class='form-input form-control inputtxtfld contactfld'>
															</div>
														</div>
														<div class="form-group">
															<label class='control-label col-md-3'>Phone Number**</label>
															<div class='col-md-9'>
																<input type='text' class='form-input form-control inputtxtfld phonenumberfld'>
															</div>
														</div>
													</div>
													<div class='col-md-6'>
														<div class="form-group">
															<label class='control-label col-md-3'>Email**</label>
															<div class='col-md-9'>
																<input type='text' class='form-input form-control inputtxtfld emailfld'>
															</div>
														</div>
														<div class="form-group">
															<label class='control-label col-md-3'>Mobile Number**</label>
															<div class='col-md-9'>
																<input type='text' class='form-input form-control inputtxtfld mobilenumberfld'>
															</div>
														</div>
														<div class="form-group">
															<label class='control-label col-md-3'></label>
															<div class='col-md-9'>
																<div class='smallbuttons-wrapper'>
																	<button class='btn mybtn shipper-insertcontactbtn datatablebtn'>
																		<i class='fa fa-xs fa-plus'></i>Add
																	</button>
																	<button class='btn mybtn shipper-removecontactbtn datatablebtn'>
																	    <i class='fa fa-xs fa-trash'></i>Remove
																	</button>
																	<button class='btn mybtn shipper-clearcontactfieldsbtn datatablebtn'>
																	    <i class='fa fa-xs fa-refresh'></i>Clear
																	</button>
																</div>
															</div>
														</div>
													</div>
												</fieldset>
												<div class='table-sm'>
													<table class='table table-condensed table-hover pointer table-striped table-bordered text-nowrap mytable gray-template table-font-sm no-side-borders shippercontactdetailstbl' id='shipper-editcontactdetailtbl' style='width:100%'>
									    				<thead>
									    					<tr>
									    						<th class='column-nosort column-checkbox text-center'></th>
									    						<th class='column-nosort column-checkbox'>DEFAULT</th>
									    						<th class='column-nosort column-checkbox'>SEND SMS</th>
									    						<th class='column-nosort column-checkbox'>SEND EMAIL</th>
															    <th>CONTACT</th>
									    						<th>PHONE NUMBER</th>
									    						<th>EMAIL</th>
									    						<th>MOBILE</th>
									    					</tr>
									    				</thead>
									    				<tbody>
									    					
									    				</tbody>
									    			</table>
									    		</div>
								    		</fieldset>
										</div>
										<!--<div class='pane' id='shippereditrate-pane'>

											<fieldset>
												<legend>Custom Rates</legend>
												<fieldset>
													<div class='col-md-7'>
														<div class="form-group">
															<label class='control-label col-md-3'>Origin</label>
															<div class='col-md-9'>
																<select class='form-input form-control originfld origindestinationdropdownselect inputslctfld noresetfld' style='width:100%'></select>
															</div>
														</div>	
														<div class="form-group">
																<label class='control-label col-md-3'>Destination</label>
																<div class='col-md-9'>
																	<select class='form-input form-control destinationfld origindestinationdropdownselect inputslctfld noresetfld' style='width:100%'></select>
																</div>
														</div>	
														<div class="form-group">
																<label class='control-label col-md-3'>Mode of Transport</label>	
																<div class='col-md-9'>
																	<select class='form-input form-control modeoftransportfld modeoftransportdropdownselect inputslctfld noresetfld' style='width:100%'></select>
																</div>
														</div>
														<div class="form-group">
																<label class='control-label col-md-3'>Freight Computation</label>
																<div class='col-md-9'>	
																	<select class='form-input form-control freightcomputationfld freightcomputationdropdownselect inputslctfld' style='width:100%'></select>
																</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-3">Rush Flag &nbsp;&nbsp;</label>
															<div class='col-md-9'>
																<input type="checkbox" class='togglebutton rushflagfld activeflag'>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-3">Pull Out Flag &nbsp;&nbsp;</label>
															<div class='col-md-9'>
																<input type="checkbox" class='togglebutton pulloutflagfld activeflag'>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label col-md-3">Fixed Rate &nbsp;&nbsp;</label>
															<div class='col-md-9'>
																<input type="checkbox" class='togglebutton fixedrateflagfld'>
															</div>
														</div>
													</div>
													<div class='col-md-5'>
														<div class="form-group">
															<label class='control-label col-md-4'>Valuation</label>
															<div class='col-md-8'>
																<input type='number' class='form-input form-control valuationfld inputtxtfld text-right' placeholder="%">
															</div>
														</div>
														<div class="form-group">
															<label class='control-label col-md-4'>Freight Rate</label>
															<div class='col-md-8'>
																<input type='number' class='form-input form-control freightratefld inputtxtfld text-right'>
															</div>
														</div>
														<div class="form-group">
															<label class='control-label col-md-4'>Insurance Rate</label>
															<div class='col-md-8'>
																<input type='number' class='form-input form-control insuranceratefld inputtxtfld text-right'>
															</div>
														</div>
														<div class="form-group">
															<label class='control-label col-md-4'>Fuel Rate</label>
															<div class='col-md-8'>
																<input type='number' class='form-input form-control fuelratefld inputtxtfld text-right'>
															</div>
														</div>
														<div class="form-group">
															<label class='control-label col-md-4'>Bunker Rate</label>
															<div class='col-md-8'>
																<input type='number' class='form-input form-control bunkerratefld inputtxtfld text-right'>
															</div>
														</div>
														<div class="form-group">
															<label class='control-label col-md-4'>Minimum Rate</label>
															<div class='col-md-8'>
																<input type='number' class='form-input form-control minimumratefld inputtxtfld text-right'>
															</div>
														</div>
														<div class="form-group">
															<label class='control-label col-md-4'></label>
															<div class='col-md-8'>
																<div class='smallbuttons-wrapper'>
																	<button class='btn mybtn shipper-insertratebtn datatablebtn'>
																		<i class='fa fa-xs fa-plus'></i>Add
																	</button>
																	<button class='btn mybtn shipper-removeratebtn datatablebtn'>
																	    <i class='fa fa-xs fa-trash'></i>Remove
																	</button>
																	<button class='btn mybtn shipper-clearratefieldsbtn datatablebtn'>
																	    <i class='fa fa-xs fa-refresh'></i>Clear
																	</button>
																</div>
															</div>
														</div>
													</div>
												</fieldset>
												<div class='table-sm'>
													<table class='table table-condensed table-hover pointer table-striped table-bordered text-nowrap mytable gray-template table-font-sm no-side-borders shipperratedetailstbl' id='shipper-editratedetailtbl' style='width:100%'>
									    				<thead>
									    					<tr>
									    						<th class='column-nosort column-checkbox text-center'></th>
									    						<th class='column-nosort column-checkbox text-center'>FIXED RATE</th>
									    						<th class='column-nosort column-checkbox text-center'>RUSH</th>
									    						<th class='column-nosort column-checkbox text-center'>PULL OUT</th>
															    <th>ORIGIN</th>
									    						<th>DESTINATION</th>
									    						<th>MODE OF TRANSPORT</th>
									    						<th>FREIGHT COMPUTATION</th>
									    						<th>VALUATION</th>
									    						<th>FREIGHT RATE</th>
									    						<th>INSURANCE RATE</th>
									    						<th>FUEL RATE</th>
									    						<th>BUNKER RATE</th>
									    						<th>MINIMUM RATE</th>
									    					</tr>
									    				</thead>
									    				<tbody>
									    					
									    				</tbody>
									    			</table>
									    		</div>
								    		</fieldset>

										</div>-->

									</div>


								</div>
							</div>

							
							
							
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn shippermodal-savebtn' id='editshippermodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="viewshipperratemodal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Shipper Rate
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<input type='hidden' class='noresetfld' id='viewshipperratemodal-shipperid'>
					<div><span style='font-weight: bold' id='viewshipperratemodal-accountname'></span></div>
					<fieldset>
						<div class='form-horizontal'>
						    <div class='col-md-12'>
						    	<div class="form-group">
									<div class="errordiv"></div>
								</div>
						    </div>
							<div class='col-md-7'>
								<div class="form-group">
									<label class='control-label col-md-3'>Type*</label>
									<div class='col-md-9'>
										<select class='form-input form-control viewshipperratemodal-type inputslctfld noresetfld select2 shipperrate-waybilltype' style='width:100%'>
											<option value='PARCEL'>PARCEL</option>
											<option value='DOCUMENT'>EXPRESS</option>
										</select>
									</div>
								</div>
								<!--<div class="form-group parceltypewrapper">
									<label class='control-label col-md-3'>Parcel Type*</label>
									<div class='col-md-9'>
										<select class='form-input form-control viewshipperratemodal-parceltype parceltypedropdownselect inputslctfld noresetfld' style='width:100%'></select>
									</div>
								</div>-->	
								<div class="form-group">
									<label class='control-label col-md-3'>Origin*</label>
									<div class='col-md-9'>
										<select class='form-input form-control viewshipperratemodal-origin origindestinationdropdownselect inputslctfld noresetfld' style='width:100%'></select>
									</div>
								</div>	
								<div class="form-group">
									<label class='control-label col-md-3'>Destination*</label>
									<div class='col-md-9'>
										<select class='form-input form-control viewshipperratemodal-destination origindestinationdropdownselect inputslctfld noresetfld' style='width:100%'></select>
									</div>
								</div>	
								<div class="form-group modeoftransportwrapper">
									<label class='control-label col-md-3'>Mode of Transport*</label>	
									<div class='col-md-9'>
										<select class='form-input form-control viewshipperratemodal-modeoftransport modeoftransportdropdownselect inputslctfld noresetfld' style='width:100%'></select>
									</div>
								</div>
								<div class="form-group serviceswrapper">
									<label class='control-label col-md-3'>Services*</label>	
									<div class='col-md-9'>
										<select class='form-input form-control viewshipperratemodal-services servicesdropdownselect inputslctfld noresetfld' style='width:100%'></select>
									</div>
								</div>
								<div class="form-group freightcomputationwrapper">
									<label class='control-label col-md-3'>Freight Computation*</label>
									<div class='col-md-9'>	
										<select class='form-input form-control viewshipperratemodal-freightcomputation shipperrate-freightcomputation freightcomputationdropdownselect inputslctfld' style='width:100%'></select>
									</div>
								</div>
								<div class="form-group cbmcomputationwrapper hidden">
									<label class='control-label col-md-3'>CBM Computation*</label>
									<div class='col-md-9'>
										<select class='form-control viewshipperratemodal-cbmcomputation inputslctfld shipperrate-cbmcomputation cbmcomputationdropdownselect'>
												<!--<option value=''>-</option>
												<option value='1' title='Chargeable weight is multiplied'>Computation 1</option>
												<option value='2' title='Freight Charge is fixed. Depends on weight range'>Computation 2</option>-->
										</select>
									</div>
							    </div>
								<div class="form-group freightchargecomputationwrapper normalratewrapper">
									<label class='control-label col-md-3'>Freight Charge Computation*</label>
									<div class='col-md-9'>
										<select class='form-control viewshipperratemodal-freightchargecomputation inputslctfld shipperrate-freightchargecomputation freightchargecomputationdropdownselect'>
												<!--<option value=''>-</option>
												<option value='1' title='Chargeable weight is multiplied'>Computation 1</option>
												<option value='2' title='Freight Charge is fixed. Depends on weight range'>Computation 2</option>-->
										</select>
									</div>
							    </div>
							    
							    <div class="form-group collectionpercentagewrapper hidden">
									<label class='control-label col-md-3'>Collection Percentage*</label>
									<div class='col-md-9'>
										<input type='number' class='form-input form-control viewshipperratemodal-collectionpercentage inputtxtfld text-right' placeholder="%">
									</div>
								</div>
								<div class="form-group insuranceratecomputationwrapper normalratewrapper">
										<label class='control-label col-md-3'>Insurance Rate Computation*</label>
										<div class='col-md-9'>
											<select class='form-control viewshipperratemodal-insuranceratecomputation inputslctfld shipperrate-insuranceratecomputation insuranceratecomputationdropdownselect'>
											</select>
										</div>
								</div>
								 <div class="form-group excessamountwrapper hidden">
										<label class='control-label col-md-3'>Excess Amount of*</label>
										<div class='col-md-9'>
											<input type='number' class='form-input form-control viewshipperratemodal-excessamount text-right'>
										</div>
								</div>
								<div class="form-group pouchsizewrapper hidden">
									<label class='control-label col-md-3'>Pouch Size*</label>	
									<div class='col-md-9'>	
										<select class='form-input form-control viewshipperratemodal-pouchsize pouchsizedropdownselect inputslctfld noresetfld' style='width:100%'></select>
									</div>
								</div>
								<div class="form-group expresstransactiontypewrapper hidden">
									<label class='control-label col-md-3'>Express Transaction Type*</label>	
									<div class='col-md-9'>
										<select class='form-input form-control viewshipperratemodal-expresstransactiontype expresstransactiontypedropdownselect inputslctfld noresetfld' style='width:100%'>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">Rush Flag &nbsp;&nbsp;</label>
									<div class='col-md-9'>
										<input type="checkbox" class='togglebutton viewshipperratemodal-rushflag activeflag'>
									</div>
								</div>
								<div class="form-group pulloutflagwrapper">
									<label class="control-label col-md-3">Pull Out Flag &nbsp;&nbsp;</label>
									<div class='col-md-9'>
										<input type="checkbox" class='togglebutton viewshipperratemodal-pulloutflag activeflag shipperrate-pulloutflag'>
									</div>
								</div>
								<div class="form-group fixedrateflagwrapper">
									<label class="control-label col-md-3">Fixed Rate &nbsp;&nbsp;</label>
									<div class='col-md-9'>
										<input type="checkbox" class='togglebutton viewshipperratemodal-fixedrateflag shipperrate-fixedrateflag'>
									</div>
								</div>
								<div class="form-group advaloremflagwrapper hidden">
									<label class="control-label col-md-3">Ad Valorem &nbsp;&nbsp;</label>
									<div class='col-md-9'>
										<input type="checkbox" class='togglebutton viewshipperratemodal-advaloremflag shipperrate-advaloremflag'>
									</div>
								</div>
							</div>
							<div class='col-md-5'>
							    <div class="form-group odaratewrapper normalratewrapper">
									<label class='control-label col-md-4'>ODA Rate</label>
									<div class='col-md-8'>
										<input type='number' class='form-input form-control viewshipperratemodal-odarate inputtxtfld text-right shipperrate-odarate'>
									</div>
							    </div>
								<div class="form-group fixedrateamountwrapper hidden">
									<label class='control-label col-md-4'>Fixed Rate Amount*</label>
									<div class='col-md-8'>
										<input type='number' class='form-input form-control viewshipperratemodal-fixedrateamount inputtxtfld text-right shipperrate-fixedrateamount'>
									</div>
								</div>
								<div class="form-group pulloutfeewrapper hidden">
									<label class='control-label col-md-4'>Pull Out Fee*</label>
									<div class='col-md-8'>
										<input type='number' class='form-input form-control viewshipperratemodal-pulloutfee inputtxtfld text-right shipperrate-pulloutfee'>
									</div>
								</div>
								<div class="form-group valuationwrapper normalratewrapper">
									<label class='control-label col-md-4'>Valuation</label>
									<div class='col-md-8'>
										<input type='number' class='form-input form-control viewshipperratemodal-valuation inputtxtfld text-right' placeholder="%">
									</div>
								</div>
								<div class="form-group freightratewrapper normalratewrapper">
									<label class='control-label col-md-4'>Freight Rate</label>
									<div class='col-md-8'>
										<input type='number' class='form-input form-control viewshipperratemodal-freightrate inputtxtfld text-right'>
									</div>
								</div>
								<div class="form-group insuranceratewrapper normalratewrapper">
									<label class='control-label col-md-4'>Insurance Rate</label>
									<div class='col-md-8'>
										<input type='number' class='form-input form-control viewshipperratemodal-insurancerate inputtxtfld text-right'>
									</div>
								</div>
								<div class="form-group fuelratewrapper normalratewrapper">
									<label class='control-label col-md-4'>Fuel Rate</label>
									<div class='col-md-8'>
										<input type='number' class='form-input form-control viewshipperratemodal-fuelrate inputtxtfld text-right'>
									</div>
								</div>
								<div class="form-group bunkerratewrapper normalratewrapper">
									<label class='control-label col-md-4'>Bunker Rate</label>
									<div class='col-md-8'>
										<input type='number' class='form-input form-control viewshipperratemodal-bunkerrate inputtxtfld text-right'>
									</div>
								</div>
								<div class="form-group minimumratewrapper normalratewrapper">
									<label class='control-label col-md-4'>Minimum Rate</label>
									<div class='col-md-8'>
										<input type='number' class='form-input form-control viewshipperratemodal-minimumrate inputtxtfld text-right'>
									</div>
								</div>
								
								<div class="form-group returndocumentfeewrapper normalratewrapper">
									<label class='control-label col-md-4'>Return Doc Fee</label>
									<div class='col-md-8'>
										<input type='number' class='form-input form-control viewshipperratemodal-returndocumentfee inputtxtfld text-right shipperrate-returndocumentfee'>
									</div>
							    </div>

							    <div class="form-group waybillfeewrapper normalratewrapper">
									<label class='control-label col-md-4'>Waybill Fee</label>
									<div class='col-md-8'>
										<input type='number' class='form-input form-control viewshipperratemodal-waybillfee inputtxtfld text-right shipperrate-waybillfee'>
									</div>
							    </div>
							    <div class="form-group securityfeewrapper normalratewrapper">
									<label class='control-label col-md-4'>Security Fee</label>
									<div class='col-md-8'>
										<input type='number' class='form-input form-control viewshipperratemodal-securityfee inputtxtfld text-right shipperrate-securityfee'>
									</div>
							    </div>
							    <div class="form-group docstampfeewrapper normalratewrapper">
									<label class='control-label col-md-4'>Doc Stamp Fee</label>
									<div class='col-md-8'>
										<input type='number' class='form-input form-control viewshipperratemodal-docstampfee inputtxtfld text-right shipperrate-docstampfee'>
									</div>
							    </div>
								<div class="form-group">
									<label class='control-label col-md-4'></label>
									<div class='col-md-8'>
										<div class='smallbuttons-wrapper'>
											<button class='btn mybtn viewshipperratemodal-insertratebtn datatablebtn'>
												<i class='fa fa-xs fa-plus'></i>Add
											</button>
											<button class='btn mybtn viewshipperratemodal-clearratefieldsbtn datatablebtn'>
												<i class='fa fa-xs fa-refresh'></i>Clear
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
					<table id='viewshipperratemodal-tbl'>
						<tbody></tbody>
					</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="editshipperratemodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Edit Rate
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<div class="col-md-12">
						<form class="form-horizontal">

							<div class="form-group">
								<div class="errordiv"></div>
							</div>
							<input type='hidden' class='editshipperratemodal-shipperrateid'>
							<input type='hidden' class='editshipperratemodal-shipperid'>
							<div class='col-md-7'>
								<div class='col-md-12'>
									<div class="form-group">
										<label class='control-label'>Type*</label>
										<select class='form-input form-control type inputslctfld select2 shipperrate-waybilltype noresetfld' style='width:100%'>
											<option value='PARCEL'>PARCEL</option>
											<option value='DOCUMENT'>EXPRESS</option>
										</select>
									</div>
									<!--<div class="form-group parceltypewrapper">
										<label class='control-label'>Parcel Type*</label>
										<select class='form-input form-control parceltype parceltypedropdownselect' style='width:100%'></select>
									</div>-->	
									<div class="form-group">
										<label class='control-label'>Origin*</label>
										<select class='form-input form-control origin origindestinationdropdownselect' style='width:100%'></select>
									</div>	
									<div class="form-group">
											<label class='control-label'>Destination*</label>
											<select class='form-input form-control destination origindestinationdropdownselect' style='width:100%'></select>
									</div>	
									<div class="form-group modeoftransportwrapper">
											<label class='control-label'>Mode of Transport*</label>	
											<select class='form-input form-control modeoftransport modeoftransportdropdownselect' style='width:100%'></select>
									</div>
									<div class="form-group serviceswrapper">
											<label class='control-label'>Services*</label>	
											<select class='form-input form-control services servicesdropdownselect' style='width:100%'></select>
									</div>
									<div class="form-group freightcomputationwrapper">
											<label class='control-label'>Freight Computation*</label>	
											<select class='form-input form-control freightcomputation freightcomputationdropdownselect shipperrate-freightcomputation' style='width:100%'></select>
									</div>

									<div class="form-group cbmcomputationwrapper hidden">
										<label class='control-label'>CBM Computation*</label>
										<select class='form-control cbmcomputation inputslctfld shipperrate-cbmcomputation cbmcomputationdropdownselect'>
										</select>
								    </div>

									<div class="form-group freightchargecomputationwrapper normalratewrapper">
										<label class='control-label'>Freight Charge Computation*</label>
										<select class='form-control freightchargecomputation inputslctfld shipperrate-freightchargecomputation freightchargecomputationdropdownselect'>
										</select>
								    </div>
								    
									<div class="form-group collectionpercentagewrapper hidden">
										<label class='control-label'>Collection Percentage</label>
										<input type='number' class='form-input form-control collectionpercentage text-right' placeholder="%">
									</div>
									<div class="form-group insuranceratecomputationwrapper normalratewrapper">
										<label class='control-label'>Insurance Rate Computation*</label>
										<select class='form-control insuranceratecomputation inputslctfld shipperrate-insuranceratecomputation insuranceratecomputationdropdownselect'>
										</select>
								    </div>
								    <div class="form-group excessamountwrapper hidden">
										<label class='control-label'>Excess Amount of*</label>
										<input type='number' class='form-input form-control excessamount text-right'>
									</div>
									<div class="form-group pouchsizewrapper hidden">
											<label class='control-label'>Pouch Size*</label>	
											<select class='form-input form-control pouchsize pouchsizedropdownselect' style='width:100%'></select>
									</div>
									<div class="form-group expresstransactiontypewrapper hidden">
										<label class='control-label'>Express Transaction Type*</label>
										<select class='form-input form-control expresstransactiontype expresstransactiontypedropdownselect inputslctfld noresetfld' style='width:100%'>
										</select>
									</div>
									<div class="form-group">
										<br>
										<label class="control-label col-md-3">Rush Flag &nbsp;&nbsp;</label>
										<div class='col-md-9'>
											<input type="checkbox" class='togglebutton rushflag activeflag'>
										</div>
									</div>
									<div class="form-group pulloutflagwrapper">
										<label class="control-label col-md-3">Pull Out Flag &nbsp;&nbsp;</label>
										<div class='col-md-9'>
											<input type="checkbox" class='togglebutton pulloutflag activeflag shipperrate-pulloutflag'>
										</div>
									</div>
									<div class="form-group fixedrateflagwrapper">
										
										<label class="control-label col-md-3">Fixed Rate &nbsp;&nbsp;</label>
										<div class='col-md-9'>
											<input type="checkbox" class='togglebutton fixedrateflag shipperrate-fixedrateflag'>
										</div>
									</div>

									<div class="form-group advaloremflagwrapper hidden">
										<label class="control-label col-md-3">Ad Valorem &nbsp;&nbsp;</label>
										<div class='col-md-9'>
											<input type="checkbox" class='togglebutton advaloremflag shipperrate-advaloremflag'>
										</div>
									</div>
									
									
									


								</div>
							</div>

							<div class='col-md-5'>
								<div class='col-md-12'>
									<div class="form-group odaratewrapper normalratewrapper">
										<label class='control-label'>ODA Rate</label>
										<input type='number' class='form-input form-control odarate text-right shipperrate-odarate' placeholder="%">
								    </div>
								    <div class="form-group fixedrateamountwrapper hidden">
										<label class='control-label'>Fixed Rate Amount*</label>
										<input type='number' class='form-input form-control fixedrateamount text-right shipperrate-fixedrateamount'>
								    </div>
								    <div class="form-group pulloutfeewrapper hidden">
										<label class='control-label'>Pull Out Fee*</label>
										<input type='number' class='form-input form-control pulloutfee text-right shipperrate-pulloutfee'>
								    </div>
									<div class="form-group valuationwrapper normalratewrapper">
										<label class='control-label'>Valuation*</label>
										<input type='number' class='form-input form-control valuation text-right' placeholder="%">
									</div>
									<div class="form-group freightratewrapper normalratewrapper">
										<label class='control-label'>Freight Rate*</label>
										<input type='number' class='form-input form-control freightrate text-right'>
									</div>
									<div class="form-group insuranceratewrapper normalratewrapper">
										<label class='control-label'>Insurance Rate*</label>
										<input type='number' class='form-input form-control insurancerate text-right'>
									</div>
									<div class="form-group fuelratewrapper normalratewrapper">
										<label class='control-label'>Fuel Rate*</label>
										<input type='number' class='form-input form-control fuelrate text-right'>
									</div>
									<div class="form-group bunkerratewrapper normalratewrapper">
										<label class='control-label'>Bunker Rate*</label>
										<input type='number' class='form-input form-control bunkerrate text-right'>
									</div>
									<div class="form-group minimumratewrapper normalratewrapper">
										<label class='control-label'>Minimum Rate*</label>
										<input type='number' class='form-input form-control minimumrate text-right'>
									</div>
									<div class="form-group returndocumentfeewrapper normalratewrapper">
										<label class='control-label'>Return Doc Fee</label>
										<input type='number' class='form-input form-control returndocumentfee text-right'>
								    </div>

								    <div class="form-group waybillfeewrapper normalratewrapper">
										<label class='control-label'>Waybill Fee</label>
										<input type='number' class='form-input form-control waybillfee text-right'>
								    </div>

								    <div class="form-group securityfeewrapper normalratewrapper">
										<label class='control-label'>Security Fee</label>
										<input type='number' class='form-input form-control securityfee text-right'>
								    </div>
								    <div class="form-group docstampfeewrapper normalratewrapper">
										<label class='control-label'>Doc Stamp Fee</label>
										<input type='number' class='form-input form-control docstampfee text-right'>
								    </div>

								</div>
							</div>	

						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn editshipperratemodal-savebtn' id='editpublishedratemodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>


<!--<div class="modal fade" id="viewunbilledwaybillforrecomputation">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Recompute Unbilled Waybill(s)
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<input type='hidden' class='noresetfld' id='viewunbilledwaybillforrecomputation-shipperrate'>
					
					<table id='viewunbilledwaybillforrecomputation-tbl'>
						<tbody></tbody>
					</table>
			</div>
		</div>
	</div>
</div>-->


<div class="modal fade" id="viewshipperratehandlinginstruction">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Shipper Rate - Handling Instruction
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<input type='hidden' class='noresetfld' id='viewshipperratehandlinginstruction-shipperrateid'>

					<div class='form-horizontal'>
						<div class="form-group">
								<div class="errordiv"></div>
						</div>
						<div class="form-group">
							<div class='col-md-8'>
							    <div class="form-group">
									<label class='control-label col-md-3'>Handling Instruction</label>
									<div class='col-md-9'>	
										<select class='form-input form-control inputslctfld viewshipperratehandlinginstruction-handlinginstruction handlinginstructiondropdownselect' style='width:100%'></select>
									</div>
								</div>
								<div class="form-group">
									<label class='control-label col-md-3'>Type</label>
									<div class='col-md-9'>	
										<select class='form-input form-control select2 viewshipperratehandlinginstruction-type inputslctfld noresetfld' style='width:100%'>
											<option value='1'>Percentage</option>
											<option value='0'>Fixed Charge</option>
										</select>
									</div>
								</div>
								<div class="form-group percentagewrapper">
									<label class='control-label col-md-3'>Percentage</label>
									<div class='col-md-9'>
										<input type='number' class='form-input form-control viewshipperratehandlinginstruction-percentage text-right'>
									</div>
								</div>
								<div class="form-group fixedchargewrapper hidden">
									<label class='control-label col-md-3'>Fixed Charge</label>
									<div class='col-md-9'>
										<input type='number' class='form-input form-control viewshipperratehandlinginstruction-fixedcharge text-right'>
									</div>
								</div>
								<div class="form-group">
									<label class='control-label col-md-3'></label>
									<div class='col-md-9'>
										<div class='smallbuttons-wrapper'>
											<button class='btn mybtn viewshipperratehandlinginstruction-insertratebtn datatablebtn'>
												<i class='fa fa-xs fa-plus'></i>Add
											</button>
											<button class='btn mybtn viewshipperratehandlinginstruction-clearratefieldsbtn datatablebtn'>
												<i class='fa fa-xs fa-refresh'></i>Clear
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
						<!--<div class='form-horizontal'>
						    <div class='col-md-12'>
						    	<div class="form-group">
									<div class="errordiv"></div>
								</div>
						    </div>
							<div class='col-md-7'>
								<div class="form-group">
									<label class='control-label col-md-3'>Type</label>
									<div class='col-md-9'>
										<input type="text" class='form-control viewshipperratehandlinginstruction-type'>
									</div>
								</div>
								<div class="form-group">
									<label class='control-label col-md-3'>Origin</label>
									<div class='col-md-9'>
										<input type="text" class='form-control viewshipperratehandlinginstruction-origin'>
									</div>
								</div>
								<div class="form-group">
									<label class='control-label col-md-3'>Destination</label>
									<div class='col-md-9'>
										<input type="text" class='form-control viewshipperratehandlinginstruction-destination'>
									</div>
								</div>
								<div class="form-group">
									<label class='control-label col-md-3'>Mode of Transport</label>
									<div class='col-md-9'>
										<input type="text" class='form-control viewshipperratehandlinginstruction-modeoftransport'>
									</div>
								</div>
								
							</div>
							<div class='col-md-5'>
							    <div class="form-group pouchsizewrapper hidden">
									<label class='control-label col-md-3'>Pouch Size</label>
									<div class='col-md-9'>
										<input type="text" class='form-control viewshipperratehandlinginstruction-pouchsize'>
									</div>
								</div>
								<div class="form-group">
									<label class='control-label col-md-3'>Rush Flag</label>
									<div class='col-md-9'>
										<input type="text" class='form-control viewshipperratehandlinginstruction-rushflag'>
									</div>
								</div>
								<div class="form-group">
									<label class='control-label col-md-3'>Pull Out Flag</label>
									<div class='col-md-9'>
										<input type="text" class='form-control viewshipperratehandlinginstruction-pulloutflag'>
									</div>
								</div>

							</div>
						</div>-->
					
					<table id='viewshipperratehandlinginstruction-tbl'>
						<tbody></tbody>
					</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="viewshipperratefreightcharge">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Shipper Rate - Freight Charge
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<input type='hidden' class='noresetfld' id='viewshipperratefreightcharge-shipperrateid'>

					<div class='form-horizontal'>
						<div class="form-group">
								<div class="errordiv"></div>
						</div>
						<div class="form-group">
							<div class='col-md-8'>
								<div class="form-group">
									<label class='control-label col-md-3'>From (KG)</label>
									<div class='col-md-9'>
										<input type='number' class='form-input form-control viewshipperratefreightcharge-fromkg text-right'>
									</div>
								</div>
								<div class="form-group">
									<label class='control-label col-md-3'>To (KG)</label>
									<div class='col-md-9'>
										<input type='number' class='form-input form-control viewshipperratefreightcharge-tokg text-right'>
									</div>
								</div>
								<div class="form-group">
									<label class='control-label col-md-3'>Freight Charge</label>
									<div class='col-md-9'>
										<input type='number' class='form-input form-control viewshipperratefreightcharge-freightcharge text-right'>
									</div>
								</div>
								<div class="form-group">
									<label class='control-label col-md-3'>Excess Weight Charge</label>
									<div class='col-md-9'>
										<input type='number' class='form-input form-control viewshipperratefreightcharge-excessweightcharge text-right'>
									</div>
								</div>
								<div class="form-group">
									<label class='control-label col-md-3'></label>
									<div class='col-md-9'>
										<div class='smallbuttons-wrapper'>
											<button class='btn mybtn viewshipperratefreightcharge-insertratebtn datatablebtn'>
												<i class='fa fa-xs fa-plus'></i>Add
											</button>
											<button class='btn mybtn viewshipperratefreightcharge-clearratefieldsbtn datatablebtn'>
												<i class='fa fa-xs fa-refresh'></i>Clear
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<table id='viewshipperratefreightcharge-tbl'>
						<tbody></tbody>
					</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="editshipperratefreightchargemodal">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">

			<div class="modal-header">
				<div class='page-title'>
					Edit Shipper Rate - Freight Charge
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<div class='form-horizontal'>
							<input type='hidden' id='shipperratefreightchargeID'>
							<div class='col-md-12'>
								<div class="form-group">
									<div class="errordiv"></div>
								</div>
							
								<div class="form-group">
									<label class='control-label'>From (KG)</label>
									<input type='number' class='form-input form-control editshipperratefreightchargemodal-fromkg text-right'>
								</div>
								<div class="form-group">
									<label class='control-label'>To (KG)</label>
									<input type='number' class='form-input form-control editshipperratefreightchargemodal-tokg text-right'>
								</div>
								<div class="form-group">
									<label class='control-label'>Freight Charge</label>
									<input type='number' class='form-input form-control editshipperratefreightchargemodal-freightcharge text-right'>
								</div>
								<div class="form-group">
									<label class='control-label'>Excess Weight Charge</label>
									<input type='number' class='form-input form-control editshipperratefreightchargemodal-excessweightcharge text-right'>
									
								</div>
							</div>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn editshipperratefreightchargemodal-savebtn' id='editpublishedratemodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>

		</div>
	</div>
</div>

<div class="modal fade" id="viewshipperinvoicemodal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					View Invoices for <span id='viewshipperinvoicemodal-shippername'></span>
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<table id='viewshipperinvoicemodal-tbl'>
						<tbody></tbody>
					</table>
			</div>
		</div>
	</div>
</div>



<div class="modal fade" id="viewshipperattachmentmodal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					View Attachments
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<input type='hidden' name='shipperid' id='viewshipperattachmentmodal-shipperid'>
					<iframe id="uploadtgt" class='hidden' name="uploadtgt" height="0" width="0" frameborder="0" scrolling="yes"></iframe>
					<table id='viewshipperattachmentmodal-tbl'>
						<tbody></tbody>
					</table>
					<div id='viewshipperattachmentbuttonwrapper'></div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="viewshippercsemailaddressesmodal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					View CS Email Addresses
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<div class='modal-errordiv'></div>
					<input type='hidden' id='viewshippercsemailaddressesmodal-shipperid'>
					<div class="form-horizontal">
						<div class="form-group">
							<label class='control-label col-md-2'>Email Address</label>
							<div class='col-md-5'>
								<input type='text' class='form-input form-control inputtxtfld viewshippercsemailaddressesmodal-email'>
							</div>
							<div class='col-md-1'>
								<div class='smallbuttons-wrapper text-right'>
									<button class='btn mybtn viewshippercsemailaddressesmodal-insertratebtn datatablebtn'>
										<i class='fa fa-xs fa-plus'></i>Add
									</button>
								</div>
							</div>
						</div>
					</div>
					<table id='viewshippercsemailaddressesmodal-tbl'>
						<tbody></tbody>
					</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="addshipperattachmentmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Add Attachments
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <form class='form-horizontal' action='../scripts/shipper.php' method='post' id='addshipperattachmentmodal-form'  enctype='multipart/form-data' target='uploadtgt'>
                            <input type='hidden' name='shipperid' id='addshipperattachmentmodal-shipperid'>
                            <table class='table table-condensed table-hover pointer table-striped table-bordered text-nowrap mytable gray-template table-font-sm no-side-borders ' id='addshipperattachmentmodal-tbl'>
                                <thead>
                                    <tr>
                                        <th class='column-nosort column-checkbox'></th>
                                        <th class='column-nosort'>FILE</th>
                                        <th class='column-nosort'>DESCRIPTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type='checkbox' class='itemcheckbox'></td>
                                        <td>
                                            <input type='file' class='fileattachment' name='file[]'>
                                        </td>
                                        <td>
                                            <input type='text' class='form-control fileattachmentdescription' name='filedescription[]' style='width:100%'>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type='checkbox' class='itemcheckbox'></td>
                                        <td>
                                            <input type='file' class='fileattachment' name='file[]'>
                                        </td>
                                        <td>
                                            <input type='text' class='form-control fileattachmentdescription' name='filedescription[]' style='width:100%'>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                    </form>

                </div>
            </div>
            <div class="modal-footer">
                <div class='padded-with-border-engraved button-bottom'>
                        <div class='button-group'>
                            <div class='button-group-btn addshipperattachmentmodal-moreattachmentsbtn active' >
                                <i class='fa fa-plus fa-lg fa-space'></i>Add
                            </div>
                            <div class='button-group-btn addshipperattachmentmodal-removeattachmentbtn active' >
                                <i class='fa fa-times fa-lg fa-space'></i>Remove
                            </div>
                            <div class='button-group-btn addshipperattachmentmodal-savebtn active' closemodal='false'>
                                <i class='fa fa-save fa-lg fa-space'></i>Save
                            </div>
                            <div class='button-group-btn modal-cancelbtn active'>
                                <i class='fa fa-ban fa-lg fa-space'></i>Cancel
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="uploadshipperratesmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Upload Shipper Rate
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action='../scripts/shipper-rates-upload.php' method='post' id='uploadshipperratesmodal-form'  enctype='multipart/form-data' target='shipperratesuploadtransactionlogframe'>
                    <div class='col-md-4'>
                        Please make sure to follow the right format.
                        For Express Type, click <a class='pointer' id='uploadshipperratesmodal-downloadshipperrateexpresstemplatebtn' href='../file-templates/shipper-rate-express-template.xlsx'>here</a>. For Parcel Type, click <a class='pointer' id='uploadshipperratesmodal-downloadshipperrateparceltemplatebtn' href='../file-templates/shipper-rate-parcel-template.xlsx'>here</a>.
                    </div>
                    <div class='col-md-offset-1 col-md-6'>
                    	<div class="form-group">
                            <label class='control-label'>Select Type</label>
                            <select class='form-control uploadshipperratesmodal-type select2' name='uploadshipperratesmodal-type'>
                            	<option value='PARCEL'>PARCEL</option>
                            	<option value='DOCUMENT'>EXPRESS</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class='control-label'>Select an Excel File</label>
                            <input type='file' class='form-control uploadshipperratesmodal-file' name='uploadshipperratesmodal-file'>
                        </div>
                    </div>
                </form>
                <br>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button class='btn btn-blue2 mybtn' id='uploadshipperratesmodal-uploadbtn'>Upload</button>
                    <button class='btn btn-blue2 mybtn modal-cancelbtn' >Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadshipperrateslogmodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Uploading File...
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <iframe id="shipperratesuploadtransactionlogframe" name="shipperratesuploadtransactionlogframe" height="600" width="100%" frameborder="0" scrolling="yes" style='background: #fff'></iframe>
            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="shipper-creditinfomodal">
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
	            					<input type='text' class='form-input form-control shipper-creditinfomodal-accountnumber' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Account Name</label>
	            				<div class='col-md-7'>
	            					<input type='text' class='form-input form-control shipper-creditinfomodal-accountname' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Status</label>
	            				<div class='col-md-7'>
	            					<input type='text' class='form-input form-control shipper-creditinfomodal-status' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Credit Limit</label>
	            				<div class='col-md-4'>
	            					<input type='text' class='form-input form-control shipper-creditinfomodal-creditlimit text-right' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Billed Balance</label>
	            				<div class='col-md-4'> 
	            					<input type='text' class='form-input form-control shipper-creditinfomodal-billedamount text-right' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Unbilled Balance</label>
	            				<div class='col-md-4'> 
	            					<input type='text' class='form-input form-control shipper-creditinfomodal-unbilledamount text-right' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Total Balance</label>
	            				<div class='col-md-4'>
	            					<input type='text' class='form-input form-control shipper-creditinfomodal-balance text-right' disabled="true">
	            				</div>
	            			</div>
	            			
	            			<div class="form-group">
	            				<label class='control-label col-md-4'>Credit Balance</label>
	            				<div class='col-md-4'>
	            					<input type='text' class='form-input form-control shipper-creditinfomodal-creditbalance text-right' disabled="true">
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

<script type="text/javascript">
	$(document).ready(function(){

		var tabshipper = '#shipper-menutabpane';
		$(tabshipper+' .select2').select2({
			width:'100%'
		});
		//$(tabshipper+' .tagsinput').tagsinput();

		//$('.modal-dialog').draggable();

		$(tabshipper+' #addshipperattachmentmodal-tbl').DataTable({
					aaSorting: [[ 1, "asc" ]], //initially, table is sorted by second column desc
                    columnDefs: [
                                     {
                                        targets: "column-nosort", //class of columns you dont want to be sortable
                                        orderable: false
                                     }
                                 ],
                    pagingType: "full",
	                "createdRow": function( row, data, dataIndex ) {
					    $(row).addClass('addshipperattachmentmodal-row');
				  	},
				  	"bFilter": false
		});

		$(tabshipper+" .insuranceratecomputationdropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/insurance-rate-computation.php",
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
	                width:'100%',
	                minimumInputLength: 0
	    });

		$(tabshipper+" .servicesdropdownselect").select2({
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

		 $(tabshipper+" .pouchsizedropdownselect").select2({
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

		$(tabshipper+" .userdropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/user.php",
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
	    $(tabshipper+" .accountexecutivedropdownselect").select2({
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
	    $(tabshipper+" .expresstransactiontypedropdownselect").select2({
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
	                width:'100%'
	    });
	    $(tabshipper+" .paymodedropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/pay-mode-id.php",
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
		$(tabshipper+" .countriesdropdownselect").select2({
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

	    $(tabshipper+" .addressdistrictdropdownselect").select2({
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

	    $(tabshipper+" .addresscitydropdownselect").select2({
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

	    $(tabshipper+" .addresszipcodedropdownselect").select2({
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

	    $(tabshipper+" .addressregiondropdownselect").select2({
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

	    $(tabshipper+' .togglebutton').bootstrapToggle({
		      on: 'Yes',
		      off: 'No',
		      size: 'mini',
		      width: '100px'
		});

		$(tabshipper+" .parceltypedropdownselect").select2({
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

		$(tabshipper+" .origindestinationdropdownselect").select2({
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
	                minimumInputLength: 0
	    });

	    $(tabshipper+" .modeoftransportdropdownselect").select2({
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
	                minimumInputLength: 0
	    });

	     $(tabshipper+" .cbmcomputationdropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/cbm-computation.php",
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

	    $(tabshipper+" .freightchargecomputationdropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/freight-charge-computation.php",
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

	    $(tabshipper+" .freightcomputationdropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/freight-computation.php",
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

	    $(tabshipper+" .handlinginstructiondropdownselect").select2({
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




		$(tabshipper+' .shippercontactdetailstbl').DataTable({
					aaSorting: [[ 4, "asc" ]], //initially, table is sorted by second column desc
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
					    $(row).addClass('mydatatablerow');
				  	}
		});

		$(tabshipper+' .shipperpickupaddressdetailstbl').DataTable({
					aaSorting: [[ 2, "asc" ]], //initially, table is sorted by second column desc
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
					    $(row).addClass('mydatatablerow');
				  	}
		});

		$(tabshipper+' .shipperratedetailstbl').DataTable({
					aaSorting: [[ 4, "asc" ]], //initially, table is sorted by second column desc
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
					    $(row).addClass('mydatatablerow');
				  	}
		});

		
		

		$("#shippertable").flexigrid({
				url: 'loadables/ajax/maintenance.shipper.php',
				dataType: 'json',
				colModel : [
						{display: 'Actions', name : 'action', width : 140, sortable : false, align: 'center'},
						{display: 'System ID', name : 'id', width : 70, sortable : true, align: 'left'},
						{display: 'Account No.', name : 'account_number', width : 100, sortable : true, align: 'left'},
						{display: 'Account Name', name : 'account_name', width : 250, sortable : true, align: 'left'},
						{display: 'Company Name', name : 'company_name', width : 250, sortable : true, align: 'left'},
						{display: 'Inactive', name : 'inactive_flag', width : 80, sortable : true, align: 'text-center'},
						{display: 'Billing in Charge', name : 'billing_in_charge', width : 200, sortable : true, align: 'left'},
						{display: 'Account Exective', name : 'account_executive', width : 200, sortable : true, align: 'left'},
						{display: 'Non-POD Flag', name : 'non_pod_flag', width : 100, sortable : true, align: 'text-center'},
						{display: 'VAT Flag', name : 'vat_flag', width : 100, sortable : true, align: 'text-center'},
						{display: 'Created by', name : 'created_by', width : 150, sortable : true, align: 'left', hide:true},
						{display: 'Created Date', name : 'created_date', width : 125, sortable : true, align: 'left', hide:true},
						{display: 'Updated by', name : 'updated_by', width : 150, sortable : true, align: 'left', hide:true},
						{display: 'Updated Date', name : 'updated_date', width : 125, sortable : true, align: 'left', hide:true}
				],
				buttons : [
						{name: 'Add', bclass: 'add addshipperbtn', onpress : addShipper},
						{separator: true},
						{name: 'Delete', bclass: 'delete deleteshipperbtn', onpress : deleteShipper},
						{separator: true},
						{name: 'Upload', bclass: 'upload uploadshipperratebtn', onpress : uploadShipperRates}
				],
				searchitems : [
						{display: 'Account Number', name : 'account_number'},
						{display: 'Account Name', name : 'account_name', isdefault: true},
						{display: 'Company Name', name : 'company_name'},
						{display: 'Inactive Flag', name : 'inactive_flag'}
				],
				sortname: "company_name",
				sortorder: "asc",
				usepager: true,
				title: "",
				useRp: true,
				rp: 15, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 500,
				singleSelect: false
		});

		$("#viewshipperinvoicemodal-tbl").flexigrid({
				url: 'loadables/ajax/maintenance.shipper-invoice.php',
				dataType: 'json',
				colModel : [

						{display: 'Billing No.', name : 'billing_number', width : 100, sortable : true, align: 'left'},
						{display: 'Date', name : 'document_date', width : 250, sortable : true, align: 'left'},
						{display: 'Status', name : 'status', width : 250, sortable : true, align: 'left'},
						{display: 'Payment Status', name : 'paymentstatus', width : 250, sortable : true, align: 'left'},
						{display: 'Amount', name : 'total_amount', width : 80, sortable : true, align: 'right'},
						{display: 'Created by', name : 'created_by', width : 150, sortable : true, align: 'left', hide:true},
						{display: 'Created Date', name : 'created_date', width : 125, sortable : true, align: 'left', hide:true},
						{display: 'Updated by', name : 'updated_by', width : 150, sortable : true, align: 'left', hide:true},
						{display: 'Updated Date', name : 'updated_date', width : 125, sortable : true, align: 'left', hide:true}
				],
				searchitems : [
						{display: 'Billing Number', name : 'billing_number', isdefault: true},
						{display: 'Payment Status', name : 'paymentstatus'},
						{display: 'Status', name : 'status'}
				],
				sortname: "billing_number",
				sortorder: "asc",
				usepager: true,
				title: "",
				useRp: true,
				rp: 15, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 500,
				singleSelect: false
		});


		$("#viewshipperattachmentmodal-tbl").flexigrid({
				url: 'loadables/ajax/maintenance.shipper-attachments.php',
				dataType: 'json',
				colModel : [
						{display: '', name : 'checkbox', width : 50, sortable : false, align: 'center'},
						{display: 'Actions', name : 'download', width : 100, sortable : false, align: 'center'},
						{display: 'Filename', name : 'filename', width : 250, sortable : true, align: 'left'},
						{display: 'Description', name : 'description', width : 250, sortable : true, align: 'left'},
						{display: 'Uploaded Date', name : 'created_date', width : 180, sortable : true, align: 'left'},
						{display: 'Uploaded By', name : 'createdby', width : 180, sortable : true, align: 'left'}
				],
				buttons : [
						{name: 'Add', bclass: 'add addshipperattachmentbtn', onpress : addShipperAttachment},
						{name: 'Delete', bclass: 'delete deleteshipperattachmentbtn', onpress : deleteShipperAttachment}
				],
				searchitems : [
						{display: 'Filename', name : 'filename', isdefault: true},
						{display: 'Description', name : 'description'}
				],
				sortname: "filename",
				sortorder: "asc",
				hideOnSubmit: false,
				usepager: true,
				title: "",
				useRp: true,
				rp: 15, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 500,
				singleSelect: false
		});

		$("#viewshippercsemailaddressesmodal-tbl").flexigrid({
				url: 'loadables/ajax/maintenance.shipper-cs-email-addresses.php',
				dataType: 'json',
				colModel : [
						{display: '', name : 'checkbox', width : 50, sortable : false, align: 'center'},
						{display: 'Email', name : 'email', width : 350, sortable : true, align: 'left'},
						{display: 'Created Date', name : 'created_date', width : 150, sortable : true, align: 'left',hide:true},
						{display: 'Created By', name : 'created_by', width : 200, sortable : true, align: 'left',hide:true}
				],
				buttons : [
						{name: 'Delete', bclass: 'delete deleteshippercsemailaddressesbtn', onpress : ''}
				],
				searchitems : [
						{display: 'Email', name : 'email', isdefault: true}
				],
				sortname: "email",
				sortorder: "asc",
				hideOnSubmit: false,
				usepager: true,
				title: "",
				useRp: true,
				rp: 15, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 500,
				singleSelect: false
		});

		$("#viewshipperratemodal-tbl").flexigrid({
				url: 'loadables/ajax/maintenance.shipper-rates.php',
				dataType: 'json',
				colModel : [
						{display: '', name : 'checkbox', width : 50, sortable : false, align: 'center'},
						{display: 'Actions', name : 'download', width : 120, sortable : false, align: 'center'},
						{display: 'Type', name : 'waybill_type', width : 110, sortable : true, align: 'left'},
						//{display: 'Parcel Type', name : 'parceltypye', width : 110, sortable : true, align: 'left'},
						{display: 'Origin', name : 'origin', width : 180, sortable : true, align: 'left'},
						{display: 'Destination', name : 'destination', width : 180, sortable : true, align: 'left'},
						{display: 'Mode of Transport', name : 'mode_of_transport', width : 120, sortable : true, align: 'left'},
						{display: 'Services', name : 'services', width : 100, sortable : true, align: 'left'},
						{display: 'Rush', name : 'rush_flag', width : 80, sortable : true, align: 'left'},
						{display: 'Pull Out', name : 'pull_out_flag', width : 80, sortable : true, align: 'left'},
						{display: 'Freight Computation', name : 'freight_computation', width : 120, sortable : true, align: 'left'},
						{display: 'Freight Charge Computation', name : 'freightchargecomputation', width : 120, sortable : true, align: 'left'},
						{display: 'CBM Computation', name : 'cbmcomputation', width : 120, sortable : true, align: 'left'},
						{display: 'Pouch Size', name : 'pouchsize', width : 180, sortable : true, align: 'left'},
						{display: 'Express Transaction Type', name : 'express_transaction_type', width : 180, sortable : true, align: 'left'},
						{display: 'Ad Valorem Flag', name : 'ad_valorem_flag', width : 80, sortable : true, align: 'left'},
						{display: 'Fixed Rate', name : 'fixed_rate_flag', width : 80, sortable : true, align: 'left'},
						{display: 'Fixed Rate Amount', name : 'fixed_rate_amount', width : 100, sortable : true, align: 'right'},	
						{display: 'Collection (%)', name : 'collection_fee_percentage', width : 100, sortable : true, align: 'right'},
						{display: 'Valuation (%)', name : 'valuation', width : 100, sortable : true, align: 'right'},
						{display: 'Freight Rate', name : 'freight_rate', width : 100, sortable : true, align: 'right'},
						{display: 'Insurance Rate Computation', name : 'insuranceratecomputation', width : 120, sortable : true, align: 'left'},
						{display: 'Excess Amount', name : 'excess_amount', width : 100, sortable : true, align: 'right'},
						{display: 'Insurance Rate', name : 'insurance_rate', width : 100, sortable : true, align: 'right'},
						{display: 'Fuel Rate', name : 'fuel_rate', width : 100, sortable : true, align: 'right'},
						{display: 'Bunker Rate', name : 'bunker_rate', width : 100, sortable : true, align: 'right'},
						{display: 'Minimum Rate', name : 'minimum_rate', width : 100, sortable : true, align: 'right'},
						{display: 'ODA Rate', name : 'oda_rate', width : 100, sortable : true, align: 'right'},
						{display: 'Return Document Fee', name : 'return_document_fee', width : 100, sortable : true, align: 'right'},
						{display: 'Waybill Fee', name : 'waybill_fee', width : 100, sortable : true, align: 'right'},
						{display: 'Security Fee', name : 'security_fee', width : 100, sortable : true, align: 'right'},
						{display: 'Doc Stamp Fee', name : 'doc_stamp_fee', width : 100, sortable : true, align: 'right'},
						{display: 'Created Date', name : 'created_date', width : 150, sortable : true, align: 'left'},
						{display: 'Created By', name : 'created_by', width : 120, sortable : true, align: 'left'},
						{display: 'Updated Date', name : 'updated_date', width : 150, sortable : true, align: 'left'},
						{display: 'Updated By', name : 'updated_by', width : 120, sortable : true, align: 'left'}
				],
				buttons : [
						/*{name: 'Add', bclass: 'add addshipperattachmentbtn', onpress : addShipperAttachment},*/
						{name: 'Delete', bclass: 'delete deleteshipperratebtn', onpress : deleteShipperRate}
				],
				searchitems : [
						{display: 'type', name : 'waybill_type', isdefault: true},
						{display: 'Origin', name : 'origin'},
						{display: 'Destination', name : 'destination'},
						{display: 'Mode of Transport', name : 'mode_of_transport'},
						{display: 'Pouch Size', name : 'pouchsize'},
						{display: 'Express Transaction Type', name : 'express_transaction_type'}
				],
				sortname: "origin",
				sortorder: "asc",
				hideOnSubmit: false,
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

		$("#viewshipperratehandlinginstruction-tbl").flexigrid({
				url: 'loadables/ajax/maintenance.shipper-rate-handling-instruction.php',
				dataType: 'json',
				colModel : [
						{display: '', name : 'checkbox', width : 50, sortable : false, align: 'center'},
						//{display: 'Actions', name : 'download', width : 100, sortable : false, align: 'center'},
						{display: 'Handling Instruction', name : 'handlinginstruction', width : 300, sortable : true, align: 'left'},
						{display: 'Type', name : 'type', width : 100, sortable : true, align: 'left'},
						{display: 'Percentage', name : 'percentage', width : 150, sortable : true, align: 'right'},
						{display: 'Fixed Charge', name : 'fixed_charge', width : 150, sortable : true, align: 'right'}
				],
				buttons : [
						
						{name: 'Delete', bclass: 'delete deleteshipperratehandlinginstructionbtn', onpress : deleteShipperRateHandlingInstruction}
				],
				searchitems : [
						{display: 'Handling Instruction', name : 'handlinginstruction', isdefault: true}
				],
				sortname: "handlinginstruction",
				sortorder: "asc",
				hideOnSubmit: false,
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

		$("#viewshipperratefreightcharge-tbl").flexigrid({
				url: 'loadables/ajax/maintenance.shipper-rate-freight-charge.php',
				dataType: 'json',
				colModel : [
						{display: '', name : 'checkbox', width : 50, sortable : false, align: 'center'},
						{display: 'Actions', name : '', width : 100, sortable : false, align: 'center'},
						{display: 'From (KG)', name : 'from_kg', width : 150, sortable : true, align: 'right'},
						{display: 'To (KG)', name : 'to_kg', width : 150, sortable : true, align: 'right'},
						{display: 'Freight Charge', name : 'freight_charge', width : 150, sortable : true, align: 'right'},
						{display: 'Excess Weight Charge', name : 'excess_weight_charge', width : 150, sortable : true, align: 'right'}
				],
				buttons : [
						
						{name: 'Delete', bclass: 'delete deleteshipperratefreightchargebtn', onpress : deleteShipperRateFreightCharge}
				],
				/*searchitems : [
						{display: 'Handling Instruction', name : 'handlinginstruction', isdefault: true}
				],*/
				sortname: "created_date",
				sortorder: "asc",
				hideOnSubmit: false,
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

		/*$("#viewunbilledwaybillforrecomputation-tbl").flexigrid({
				url: 'loadables/ajax/maintenance.shipper-rate-unbilled-waybill.php',
				dataType: 'json',
				colModel : [
						{display: '', name : 'checkbox', width : 50, sortable : false, align: 'center'},
						{display: 'Actions', name : '', width : 100, sortable : false, align: 'center'},
						{display: 'Waybill Number', name : 'waybill_number', width : 150, sortable : true, align: 'left'}
				],
				buttons : [

				],
				sortname: "waybill_number",
				sortorder: "asc",
				hideOnSubmit: false,
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
		});*/

		function uploadShipperRates(){
				$('#uploadshipperratesmodal').modal('show');
		}

		function addShipperAttachment(){
				$('#addshipperattachmentmodal').modal('show');
		}

		function deleteShipperAttachment(){
			var shipperid = $(tabshipper+' #viewshipperattachmentmodal-shipperid').val();
			var button = $(this);
			button.addClass('disabled').removeClass('active');

			var filestobedeleted = [];
			$(tabshipper+' #viewshipperattachmentmodal-tbl .viewshipperattachmentmodal-checkbox:checked').each(function(){
				var prevattachedfile = $(this).attr('filename');
				filestobedeleted.push(prevattachedfile);
			});

			if(filestobedeleted.length>0){

				$.confirm({
							animation: 'bottom', 
							closeAnimation: 'top',
							animationSpeed: 1000,
							animationBounce:1,
							title: 'Delete Attachment(s)',
							content: 'Are you sure you want to continue?',
							confirmButton: 'Delete',
							cancelButton: 'Cancel',	
							confirmButtonClass: 'btn-oceanblue', 
							cancelButtonClass: 'btn-royalblue', 
							theme: 'white', 

							confirm: function(){
								$('#loading-img').removeClass('hidden');
								$.post(server+'shipper.php',{deleteFileAttachments:'f$bpom@soalns3o#2$I!Hk3so3!njsk',shipperid:shipperid,deletefiles:filestobedeleted},function(data){
									$('#loading-img').addClass('hidden');
									//var data = data.trim().split("$@!$%@");
									if(data.trim()=='notlogged'){
										say("Unable to delete attachment(s). Transaction already submitted for Approval.");
										$('#loading-img').addClass('hidden');
										button.removeClass('disabled').addClass('active');
										
									}
									else if(data.trim()=='success'){
										$('#loading-img').addClass('hidden');
										button.removeClass('disabled').addClass('active');
										$(tabshipper+" #viewshipperattachmentmodal-tbl").flexOptions({
											url:'loadables/ajax/maintenance.shipper-attachments.php?shipper='+shipperid,
											sortname: "filename",
											sortorder: "asc"
										}).flexReload(); 
									}
									else if(data.trim()=='noaccess'){
										say("No permission to delete attachment(s)");
										$(tabshipper+" #viewshipperattachmentmodal-tbl").flexOptions({
											url:'loadables/ajax/maintenance.shipper-attachments.php?shipper='+shipperid,
											sortname: "filename",
											sortorder: "asc"
										}).flexReload();
										$('#loading-img').addClass('hidden');
										button.removeClass('disabled').addClass('active');
									}
									else{
										$('#loading-img').addClass('hidden');
										button.removeClass('disabled').addClass('active');
										alert(data);
									}



								});
							},
							cancel:function(){
								button.removeClass('disabled').addClass('active');
							}
				});

			}
			else{
				say("There are no file attachments to be deleted");
				button.removeClass('disabled').addClass('active');
			}
	

		}

		function deleteShipperRate(){
			var shipperid = $(tabshipper+' #viewshipperratemodal-shipperid').val();
			if(parseInt($('#viewshipperratemodal-tbl .viewshipperratemodal-checkbox:checked').length)>0){
				$.confirm({
					animation: 'bottom', 
					closeAnimation: 'top',
					animationSpeed: 1000,
					animationBounce:1,
					title: 'Delete Shipper Rate',
					content: 'Delete selected row(s)?',
					confirmButton: 'Delete',
					cancelButton: 'Cancel',	
					confirmButtonClass: 'btn-maroon', 
					cancelButtonClass: 'btn-maroon', 
					theme: 'white', 

					confirm: function(){
							var data = [];
							$('#viewshipperratemodal-tbl .viewshipperratemodal-checkbox:checked').each(function(){
								data.push($(this).attr('rowid'));
							});
							$.post('../scripts/shipper.php',{deleteShipperRates:'$jhfoFIsmdlPE#9s3#7skoRboIh4!j3sio$*yhs',data:data},function(response){

								if(response.trim()=='success'){
									$('#viewshipperratemodal-tbl').flexOptions({
											url:'loadables/ajax/maintenance.shipper-rates.php?shipper='+shipperid,
											sortname: "origin",
											sortorder: "asc"
									}).flexReload(); 
								}
								else{
									alert(response);
								}

							});
					},
					cancel:function(){

					}
				});
			}
			else{
				say("Please select row(s) to delete");
			}
	

		}

		function addShipper(){
				$('#addshippermodal').modal('show');
		}

		function deleteShipper(){

		
			if(parseInt($('#shippertable .trSelected').length)>0){
				$.confirm({
					animation: 'bottom', 
					closeAnimation: 'top',
					animationSpeed: 1000,
					animationBounce:1,
					title: 'Confirmation',
					content: 'Delete selected row(s)?',
					confirmButton: 'Delete',
					cancelButton: 'Cancel',	
					confirmButtonClass: 'btn-maroon', 
					cancelButtonClass: 'btn-maroon', 
					theme: 'white', 

					confirm: function(){
							var data = [];
							$('#shippertable .trSelected').each(function(){
								data.push($(this).attr('rowid'));
							});
							$.post('../scripts/shipper.php',{deleteSelectedRows:'skj$oihdtpoa$I#@4noi4AIFNlskoRboIh4!j3sio$*yhs',data:data},function(response){

								if(response.trim()=='success'){
									$('#shippertable').flexOptions({
											url:'loadables/ajax/maintenance.shipper.php',
											sortname: "account_name",
											sortorder: "asc"
									}).flexReload(); 
								}
								else{
									alert(response);
								}

							});
					},
					cancel:function(){

					}
				});
			}
			else{
				say("Please select row(s) to delete");
			}

				//$("#mytable").flexAddData(eval(array));
				//$('#mytable').flexOptions({url:'staff.php?name=user%200'}).flexReload(); 
				
		}
		

		function deleteShipperRateHandlingInstruction(){
			var shipperrateid = $(tabshipper+' #viewshipperratehandlinginstruction-shipperrateid').val();
			if(parseInt($('#viewshipperratehandlinginstruction-tbl .viewshipperratehandlinginstruction-checkbox:checked').length)>0){
				$.confirm({
					animation: 'bottom', 
					closeAnimation: 'top',
					animationSpeed: 1000,
					animationBounce:1,
					title: 'Delete Handling Instruction',
					content: 'Delete selected row(s)?',
					confirmButton: 'Delete',
					cancelButton: 'Cancel',	
					confirmButtonClass: 'btn-maroon', 
					cancelButtonClass: 'btn-maroon', 
					theme: 'white', 

					confirm: function(){
							var data = [];
							$('#viewshipperratehandlinginstruction-tbl .viewshipperratehandlinginstruction-checkbox:checked').each(function(){
								data.push($(this).attr('rowid'));
							});
							$.post('../scripts/shipper.php',{deleteShipperRateHandlingInstruction:'$jhfoFIsmdlPE#9s3#7skoRboIh4!j3sio$*yhs',data:data},function(response){
								
								if(response.trim()=='success'){
									$('#viewshipperratehandlinginstruction-tbl').flexOptions({
											url:'loadables/ajax/maintenance.shipper-rate-handling-instruction.php?shipperrateid='+shipperrateid,
											sortname: "handlinginstruction",
											sortorder: "asc"
									}).flexReload(); 
								}
								else{
									alert(response);
								}

							});
					},
					cancel:function(){

					}
				});
			}
			else{
				say("Please select row(s) to delete");
			}
		}

		function deleteShipperRateFreightCharge(){
			var shipperrateid = $(tabshipper+' #viewshipperratefreightcharge-shipperrateid').val();
			if(parseInt($('#viewshipperratefreightcharge-tbl .viewshipperratefreightcharge-checkbox:checked').length)>0){
				$.confirm({
					animation: 'bottom', 
					closeAnimation: 'top',
					animationSpeed: 1000,
					animationBounce:1,
					title: 'Delete Freight Charge',
					content: 'Delete selected row(s)?',
					confirmButton: 'Delete',
					cancelButton: 'Cancel',	
					confirmButtonClass: 'btn-maroon', 
					cancelButtonClass: 'btn-maroon', 
					theme: 'white', 

					confirm: function(){
							var data = [];
							$('#viewshipperratefreightcharge-tbl .viewshipperratefreightcharge-checkbox:checked').each(function(){
								data.push($(this).attr('rowid'));
							});
							$.post('../scripts/shipper.php',{deleteShipperRateFreightCharge:'$jhfoFIsmdlPE#9s3#7skoRboIh4!j3sio$*yhs',data:data},function(response){

								if(response.trim()=='success'){
									$('#viewshipperratefreightcharge-tbl').flexOptions({
											url:'loadables/ajax/maintenance.shipper-rate-freight-charge.php?shipperrateid='+shipperrateid,
											sortname: "from_kg",
											sortorder: "asc"
									}).flexReload(); 
								}
								else{
									alert(response);
								}

							});
					},
					cancel:function(){

					}
				});
			}
			else{
				say("Please select row(s) to delete");
			}
		}
		userAccess();
			

		
	});
	
</script>