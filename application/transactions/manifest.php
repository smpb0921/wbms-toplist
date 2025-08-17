<?php
     include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
    $refMFT = isset($_GET['reference'])?escapeString($_GET['reference']):'';
?>
<div class='header-page'>
	<div class='header-page-inner'>
		Manifest
	</div>
</div>
<div class="container-fluid">
	<div class="pageContent">
		<div class="panel panel-primary mypanel">
			<div class="panel-body">
				<!-- CONTENT -->
				<div class='transaction-wrapper manifest-content'>

					<div class='col-md-12 no-padding margin-bottom-sm topbuttonsdiv'>
						<div class='padded-with-border-engraved topbuttonswrapper'>
							<div class='button-group'>
								<div class='button-group-btn active' title='New' id='manifest-trans-newbtn'
									data-toggle='modal' href='#newmanifestmodal'>
									<img src="../resources/img/add.png">
								</div>

							</div>
						</div>
					</div>

					<div class='manifest-inputfields'>
						<input type='hidden' id='pgtxnmanifest-id'>
						<div class='col-lg-2'>
							<div class="form-horizontal">
								<div class="form-group">
									<div class='col-md-12'>
										<label class='control-label'>Manifest No.</label>
										<input type='text' class='form-input form-control transactionnumber'>
									</div>

								</div>
							</div>
							<div class="firstprevnextlastbtn">
								<div class="btn-group btn-group-justified btn-group-sm margin-bottom-xs stock-item-refbuttons"
									role="group" aria-label="...">
									<div class="btn-group" role="group">
										<button type="button" class="btn btn-default stock-item-firstbtn"
											data-info='first'>
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
							<div class='button-group-btn fluid searchbtn active' data-toggle='modal'
								href='#manifest-searchmodallookup'>
								<!--<i class='fa fa-search fa-lg fa-space'></i>-->
								<img src="../resources/img/search.png">&nbsp;&nbsp;Search
							</div>
							<br>
						</div>
						<div class="col-lg-10">

							<div class='col-md-12 no-padding margin-bottom-xs margin-top-sm'>
								<div class="panel-group classictheme margin-bottom-xs" id="manifest-panelheader-wrapper"
									role="tablist" aria-multiselectable="true">

									<div class="panel panel-default">
										<div class="panel-heading" role="tab" data-toggle="collapse"
											href="#manifest-panelheader">
											Header
										</div>
										<div id="manifest-panelheader" class="panel-collapse collapse in"
											role="tabpanel">
											<div class="panel-body">
												<div class='row'>
													<div class="col-md-12">
														<div class="header-errordiv"></div>
													</div>
												</div>
												<div class='tabpane-white margin-top-20 margin-bottom-10'>
													<ul class="nav nav-tabs">
														<li role="presentation" class="active"
															data-pane='#manifest-geninfopane' id='manifest-geninfotab'>
															<a href="#">General Information</a>
														</li>
														<li role="presentation" data-pane='#manifest-truckinginfopane'
															id='manifest-truckinginfotab'><a href="#">Trucking
																Information</a></li>
													</ul>
													<div class='tab-panes'>
														<div class='pane active' id='manifest-geninfopane'>
															<div class="form-horizontal">
																<div class="col-md-6">
																	<div class="form-group">
																		<label class='control-label col-lg-3'>Load Plan
																			No.</label>
																		<div class="col-lg-9">
																			<input type='text'
																				class='form-control manifest-loadplannumber'>
																		</div>
																	</div>
																	<div class="form-group">
																		<label
																			class='control-label col-lg-3'>Location</label>
																		<div class="col-lg-9">
																			<input type='text'
																				class='form-control manifest-location'>
																		</div>
																	</div>
																	<!--<div class="form-group">
										                                                            	<label class='control-label col-lg-3'>Carrier</label>
										                                                            	<div class="col-lg-9">
										                                                            		<input type='text' class='form-control manifest-carrier'>
										                                                            	</div>
										                                                            </div>-->
																	<div class="form-group">
																		<label
																			class='control-label col-lg-3'>Origin</label>
																		<div class="col-lg-9">
																			<input type='text'
																				class='form-control manifest-origin'>
																		</div>
																	</div>
																	<div class="form-group">
																		<label
																			class='control-label col-lg-3'>Destination</label>
																		<div class="col-lg-9">
																			<textarea
																				class='form-control manifest-destination'
																				rows='4'>
										                                                                    </textarea>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class='control-label col-lg-3'>Mode of
																			Transport</label>
																		<div class="col-lg-9">
																			<input type='text'
																				class='form-control manifest-modeoftransport'>
																		</div>
																	</div>
																	<div class="form-group">
																		<label
																			class='control-label col-lg-3'>Agent</label>
																		<div class="col-lg-9">
																			<input type='text'
																				class='form-control manifest-agent'>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class='control-label col-lg-3'>Agent
																			Address</label>
																		<div class="col-lg-9">
																			<textarea
																				class='form-control manifest-agentaddress'
																				rows='3'></textarea>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class='control-label col-lg-3'>Agent
																			Contact</label>
																		<div class="col-lg-9">
																			<input type='text'
																				class='form-control manifest-agentcontact'>
																		</div>
																	</div>
																	<div class="form-group">
																		<label
																			class='control-label col-lg-3'>Remarks</label>
																		<div class="col-lg-9">
																			<textarea
																				class='form-control manifest-remarks'
																				rows='3'></textarea>
																		</div>
																	</div>




																</div>
																<div class='col-md-6'>
																	<div class="form-group">
																		<label class='control-label col-lg-3'>Document
																			Date</label>
																		<div class="col-lg-9">
																			<input type='text'
																				class='form-control manifest-documentdate'>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class='control-label col-lg-3'>MAWB
																			No./BL No.</label>
																		<div class="col-lg-9">
																			<input type='text'
																				class='form-control manifest-mawbbl'>
																		</div>
																	</div>

																	<div class="form-group">
																		<label
																			class='control-label col-lg-3'>ETD</label>
																		<div class="col-lg-9">
																			<input type='text'
																				class='form-control manifest-etd'>
																		</div>
																	</div>
																	<div class="form-group">
																		<label
																			class='control-label col-lg-3'>ETA</label>
																		<div class="col-lg-9">
																			<input type='text'
																				class='form-control manifest-eta'>
																		</div>
																	</div>

																	<div class="form-group">
																		<label class='control-label col-lg-3'>Created
																			Date</label>
																		<div class="col-lg-9">
																			<input type='text'
																				class='form-control manifest-createddate alwaysdisabled'
																				disabled="true">
																		</div>
																	</div>
																	<div class="form-group">
																		<label class='control-label col-lg-3'>Created
																			by</label>
																		<div class="col-lg-9">
																			<input type='text'
																				class='form-control manifest-createdby alwaysdisabled'
																				disabled="true">
																		</div>
																	</div>
																	<div class="form-group">
																		<label class='control-label col-lg-3'>Updated
																			Date</label>
																		<div class="col-lg-9">
																			<input type='text'
																				class='form-control manifest-updateddate alwaysdisabled'
																				disabled="true">
																		</div>
																	</div>
																	<div class="form-group">
																		<label class='control-label col-lg-3'>Updated
																			by</label>
																		<div class="col-lg-9">
																			<input type='text'
																				class='form-control manifest-updatedby alwaysdisabled'
																				disabled="true">
																		</div>
																	</div>
																	<div class="form-group">
																		<label class='control-label col-lg-3'>Status
																			Update Remarks</label>
																		<div class="col-lg-9">
																			<input type='text'
																				class='form-control manifest-statusupdateremarks'>
																		</div>
																	</div>





																</div>

															</div>
														</div>
														<div class='pane' id='manifest-truckinginfopane'>
															<div class="form-horizontal">
																<div class="col-md-6">
																	<div class="form-group">
																		<label class='control-label col-lg-3'>Trucker
																			Name</label>
																		<div class="col-lg-9">
																			<input type='text'
																				class='form-control manifest-truckername'>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class='control-label col-lg-3'>Vehicle
																			Type</label>
																		<div class="col-lg-9">
																			<input type='text'
																				class='form-control manifest-trucktype'>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class='control-label col-lg-3'>Shipping
																			Line/Plate Number</label>
																		<div class="col-lg-9">
																			<input type='text'
																				class='form-control manifest-platenumber'>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class='control-label col-lg-3'>Driver
																			Name</label>
																		<div class="col-lg-9">
																			<input type='text'
																				class='form-control manifest-drivername'>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class='control-label col-lg-3'>Contact
																			Number</label>
																		<div class="col-lg-9">
																			<input type='text'
																				class='form-control manifest-contactnumber'>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="panel-group classictheme margin-bottom-xs"
									id="manifest-waybillpaneldetails-wrapper" role="tablist"
									aria-multiselectable="true">

									<div class="panel panel-default">
										<div class="panel-heading" role="tab" data-toggle="collapse"
											href="#manifest-waybillpaneldetails">
											BOL Details
										</div>
										<div id="manifest-waybillpaneldetails" class="panel-collapse collapse in"
											role="tabpanel">
											<div class="panel-body">
												<div class='row'>

													<div class="col-md-12">
														<div class="waybilldetail-errordiv"></div>
													</div>
													<div class='col-md-12'>
														<div class='form-horizontal'>
															<div class="form-group">
																<label
																	class='control-label col-lg-offset-8 col-lg-2'>BOL
																	Count</label>
																<div class="col-lg-2">
																	<input type='text'
																		class='form-control manifest-waybillcountinloadplan'>
																</div>
															</div>
														</div>
														<div class='table-sm'>
															<table id='manifest-waybilltbl'>
																<tbody></tbody>
															</table>
														</div>

													</div>
												</div>





											</div>
										</div>
									</div>




								</div>
								<div class="panel-group classictheme margin-bottom-xs"
									id="manifest-packagepaneldetails-wrapper" role="tablist"
									aria-multiselectable="true">

									<div class="panel panel-default">
										<div class="panel-heading" role="tab" data-toggle="collapse"
											href="#manifest-packagepaneldetails">
											Package Details
										</div>
										<div id="manifest-packagepaneldetails" class="panel-collapse collapse in"
											role="tabpanel">
											<div class="panel-body">
												<div class='row'>
													<div class="col-md-12">
														<div class="mftpackagedetail-errordiv"></div>
													</div>

													<div class='col-md-12'>

														<div class='table-sm'>
															<table id='manifest-packagecodetbl'>
																<tbody></tbody>

															</table>
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


<div class="modal fade" id="manifestprintingmodal">
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
						<input type='hidden' id='manifestprintingmodal-manifestid'>
						<div class='modal-errordiv'></div>
						<div class="form-group">
							<label class='control-label'>Form Type</label>
							<select class='form-control manifestprintingmodal-formtype select2'>
								<!--<option value='MFTTRANSMITTAL'>Dispatch Transmittal</option>-->
								<option value='MFTTRANSMITTAL2'>Dispatch Transmittal</option>
								<option value='MFTCOURIERDELTRANS'>Courier Delivery Transmittal</option>
								<option value='MFTSYSTEMGENERATED'>Freight Manifest Transmittal</option>
								<option value='AIRCARGOMANIFEST'>Air Cargo Manifest</option>
								<option value='SEALANDCARGOMANIFEST'>Sea/Land Cargo Manifest</option>
								<option value='RTS'>RTS Transmittal</option>
							</select>

						</div>


					</div>
				</div>
			</div>
			<div class='modal-footer'>
				<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='manifestprintingmodal-printbtn'>Print</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="newmanifestmodal">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					New Manifest
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class='col-md-3'>
					Please provide the following information.
				</div>
				<div class='col-md-9'>
					<div class='form-horizontal'>
						<div class='modal-errordiv'></div>
						<div class="form-group">
							<label class='control-label col-lg-3'>With Load Plan</label>
							<div class="col-lg-9">
								<select class='form-control form-input newmanifestmodal-loadplanflag select2'
									style='width:100%'>
									<option value='0'>No</option>
									<option value='1'>Yes</option>
								</select>
							</div>
						</div>
						<div class="form-group mftloadplanfieldwrapper hidden">
							<label class='control-label col-lg-3'>Load Plan No.</label>
							<div class="col-lg-9">
								<select
									class='form-control form-input newmanifestmodal-loadplannumber mftloadplannumberdropdownselect'
									style='width:100%'></select>
							</div>
						</div>
						<div class='mftnoloadplanfieldwrapper'>
							<div class="form-group">
								<label class='control-label col-md-3'>Location</label>
								<div class="col-md-9">
									<select
										class='form-control form-input newmanifestmodal-location locationdropdownselect'>

									</select>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-md-3'>Origin</label>
								<div class="col-md-9">
									<select
										class='form-control form-input newmanifestmodal-origin origindestinationdropdownselect'>

									</select>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>Mode of Transport</label>
								<div class="col-lg-9">
									<select
										class='form-control newmanifestmodal-modeoftransport modeoftransportdropdownselect'></select>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>Agent</label>
								<div class="col-lg-9">
									<select class='form-control newmanifestmodal-agent agentdropdownselect'></select>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>ETD</label>
								<div class="col-lg-9">
									<input type='text' class='form-control newmanifestmodal-etd datetimepicker'>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>ETA</label>
								<div class="col-lg-9">
									<input type='text' class='form-control newmanifestmodal-eta datetimepicker'>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>MAWB No./BL No.</label>
								<div class="col-lg-9">
									<input type='text' class='form-control newmanifestmodal-mawbbl'>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-lg-3'>Document Date</label>
							<div class="col-lg-9">
								<input type='text' class='form-control newmanifestmodal-documentdate datepicker'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-lg-3'>Carrier</label>
							<div class="col-lg-9">
								<select
									class='form-control newmanifestmodal-truckername carrierdropdownselect'></select>

							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-lg-3'>Vehicle Type</label>
							<div class="col-lg-9">
								<select
									class='form-control newmanifestmodal-trucktype vehicletypedropdownselect'></select>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-lg-3'>Shipping Line/Plate Number</label>
							<div class="col-lg-9">
								<input type='text' class='form-control newmanifestmodal-platenumber'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-lg-3'>Driver</label>
							<div class="col-lg-9">
								<select
									class='form-input form-control newmanifestmodal-driver driverdropdownselect'></select>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-lg-3'>Contact Number</label>
							<div class="col-lg-9">
								<input type='text' class='form-control newmanifestmodal-contactnumber'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-lg-3'>Remarks</label>
							<div class="col-lg-9">
								<textarea class='form-control newmanifestmodal-remarks' rows='4'></textarea>
							</div>
						</div>


					</div>
				</div>
			</div>
			<div class='modal-footer'>
				<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='newmanifestmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="editmanifestmodal">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Edit Manifest Header
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class='col-md-3'>
					Please provide the following information. Waybill(s) not under selected load plan number will be
					deleted.
				</div>
				<div class='col-md-9'>
					<div class='form-horizontal'>
						<div class='modal-errordiv'></div>
						<div class="form-group">
							<label class='control-label col-lg-3'>With Load Plan</label>
							<div class="col-lg-9">
								<select class='form-control form-input editmanifestmodal-loadplanflag select2'
									style='width:100%'>
									<option value='0'>No</option>
									<option value='1'>Yes</option>
								</select>
							</div>
						</div>
						<div class="form-group mftloadplanfieldwrapper">
							<label class='control-label col-lg-3'>Load Plan No.</label>
							<div class="col-lg-9">
								<select
									class='form-control form-input editmanifestmodal-loadplannumber mftloadplannumberdropdownselect'
									style='width:100%'></select>
							</div>
						</div>
						<div class='mftnoloadplanfieldwrapper'>
							<div class="form-group">
								<label class='control-label col-md-3'>Location</label>
								<div class="col-md-9">
									<select
										class='form-control form-input editmanifestmodal-location locationdropdownselect'>

									</select>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-md-3'>Origin</label>
								<div class="col-md-9">
									<select
										class='form-control form-input editmanifestmodal-origin origindestinationdropdownselect'>

									</select>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>Mode of Transport</label>
								<div class="col-lg-9">
									<select
										class='form-control editmanifestmodal-modeoftransport modeoftransportdropdownselect'></select>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>Agent</label>
								<div class="col-lg-9">
									<select class='form-control editmanifestmodal-agent agentdropdownselect'></select>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>ETD</label>
								<div class="col-lg-9">
									<input type='text' class='form-control editmanifestmodal-etd datetimepicker'>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>ETA</label>
								<div class="col-lg-9">
									<input type='text' class='form-control editmanifestmodal-eta datetimepicker'>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>MAWB No./BL No.</label>
								<div class="col-lg-9">
									<input type='text' class='form-control editmanifestmodal-mawbbl'>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class='control-label col-lg-3'>Document Date</label>
							<div class="col-lg-9">
								<input type='text' class='form-control editmanifestmodal-documentdate datepicker'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-lg-3'>Carrier</label>
							<div class="col-lg-9">
								<select
									class='form-control editmanifestmodal-truckername carrierdropdownselect'></select>

							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-lg-3'>Vehicle Type</label>
							<div class="col-lg-9">
								<select
									class='form-control editmanifestmodal-trucktype vehicletypedropdownselect'></select>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-lg-3'>Shipping Line/Plate Number</label>
							<div class="col-lg-9">
								<input type='text' class='form-control editmanifestmodal-platenumber'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-lg-3'>Driver</label>
							<div class="col-lg-9">
								<select
									class='form-input form-control editmanifestmodal-driver driverdropdownselect'></select>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-lg-3'>Contact Number</label>
							<div class="col-lg-9">
								<input type='text' class='form-control editmanifestmodal-contactnumber'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-lg-3'>Remarks</label>
							<div class="col-lg-9">
								<textarea class='form-control editmanifestmodal-remarks' rows='4'></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class='modal-footer'>
				<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='editmanifestmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="mftaddwaybillnumbermodal">
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
						<div class="form-group hidden">
							<label class='control-label'>Scan Type</label>
							<select class='form-control mftaddwaybillnumbermodal-scantype select2'>
								<option value='WB'>BOL</option>
								<option value='MAWB'>Mother BOL</option>
							</select>
						</div>
						<div class="form-group">
							<label class='control-label'>Pouch Size</label>
							<select class='form-control mftaddwaybillnumbermodal-pouchsize pouchsizedropdownselect'>
							</select>
						</div>
						<div class="form-group">
							<label class='control-label'>BOL No.</label>
							<input type='text' class='form-control mftaddwaybillnumbermodal-waybillnumber'>
						</div>
						<div class="form-group">
							<div class='button-group-btn fluid active' id='mftaddwaybillnumbermodal-addbtn'>
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

<div class="modal fade" id="voidmanifesttransactionmodal">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Void Manifest Transaction
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class='col-md-3'>
					To void manifest transaction, please provide a reason.
				</div>
				<div class='col-md-9'>
					<div class='form-horizontal'>
						<input type='hidden' id='voidmanifesttransactionmodal-id'>
						<div class='modal-errordiv'></div>
						<div class="form-group">
							<label class='control-label col-md-3'>BOL No.</label>
							<div class='col-md-9'>
								<input type='text'
									class='form-input form-control voidmanifesttransactionmodal-txnnumber'
									disabled="true">
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-3'>Remarks</label>
							<div class='col-md-9'>
								<textarea class='form-control voidmanifesttransactionmodal-remarks' rows='6'></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class='modal-footer'>
				<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='voidmanifesttransactionmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>




<div class="modal fade" id="mftaddpackagecodemodal">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Add Package Code
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class='modal-errordiv'></div>
				<div class='col-md-12'>
					<div class='form-horizontal'>

						<div class="form-group">
							<label class='control-label'>Package Code</label>
							<input type='text' class='form-control mftaddpackagecodemodal-code'>
						</div>
						<div class="form-group">
							<div class='button-group-btn fluid active' id='mftaddpackagecodemodal-addbtn'>
								<img src="../resources/img/add.png">&nbsp;&nbsp;Add Package
							</div>
						</div>
					</div>
					<br>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="updatemanifeststatusmodal">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Update Status
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class='col-md-12'>
					<div class='form-horizontal'>
						<input type='hidden' id='updatemanifeststatusmodal-manifestid'>
						<div class='modal-errordiv'></div>
						<div class="form-group">
							<label class='control-label col-md-4'>Manifest No.</label>
							<div class='col-md-8'>
								<input type='text'
									class='form-input form-control updatemanifeststatusmodal-manifestnumber'
									disabled="true">
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Status</label>
							<div class='col-md-8'>
								<select class='form-control select2 updatemanifeststatusmodal-status'>
									<option value=''>-</option>
									<option value='TRANSFERRED'>Transferred</option>
									<option value='LOADED'>Loaded</option>
									<option value='RETRIEVED'>Retrieved</option>
									<option value='OUT FOR DELIVERY'>Out for Delivery</option>
								</select>
							</div>
						</div>
						<div class="form-group mftmawblwrapper hidden">
							<label class='control-label col-md-4'>MAWB No./BL No.</label>
							<div class="col-md-8">
								<input type='text' class='form-control updatemanifeststatusmodal-mawbbl'>
							</div>
						</div>
						<div class="form-group mftetdwrapper hidden">
							<label class='control-label col-md-4'>ETD</label>
							<div class="col-md-8">
								<input type='text' class='form-control updatemanifeststatusmodal-etd datetimepicker'>
							</div>
						</div>
						<div class="form-group mftetawrapper hidden">
							<label class='control-label col-md-4'>ETA</label>
							<div class="col-md-8">
								<input type='text' class='form-control updatemanifeststatusmodal-eta datetimepicker'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Remarks</label>
							<div class="col-md-8">
								<textarea class='form-control updatemanifeststatusmodal-remarks' rows='3'></textarea>
							</div>
						</div>


					</div>
				</div>
			</div>
			<div class='modal-footer'>
				<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='updatemanifeststatusmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="manifest-searchmodallookup">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Search for Manifest
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="form-horizontal">
					<div class='col-md-6'>
						<div class="form-group">
							<label class='control-label col-md-3'>Status</label>
							<div class="col-md-9">
								<select
									class='form-control form-input manifestsearch-status manifeststatusdropdownselect'>

								</select>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-3'>Load Plan Number</label>
							<div class="col-md-9">
								<select
									class='form-control form-input manifestsearch-loadplan manifestloadplandropdownselect'>

								</select>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-3'>Origin</label>
							<div class="col-md-9">
								<select
									class='form-control form-input manifestsearch-origin origindestinationdropdownselect'>

								</select>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-3'>Destination</label>
							<div class="col-md-9">
								<select
									class='form-control form-input manifestsearch-destination origindestinationdropdownselect'
									multiple>

								</select>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-3'>Mode</label>
							<div class="col-md-9">
								<select
									class='form-control form-input manifestsearch-mode modeoftransportdropdownselect'>

								</select>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-3'>Agent</label>
							<div class="col-md-9">
								<select class='form-control form-input manifestsearch-agent agentdropdownselect'>

								</select>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-3'>Carrier</label>
							<div class="col-md-9">
								<select class='form-control form-input manifestsearch-carrier carrierdropdownselect'>

								</select>
							</div>
						</div>


					</div>
					<div class='col-md-6'>
						<div class="form-group">
							<label class='control-label col-md-4'>Vehicle Type</label>
							<div class="col-md-8">
								<select
									class='form-control form-input manifestsearch-vehicletype vehicletypedropdownselect'>

								</select>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>MAWBL/BL</label>
							<div class="col-md-8">
								<input type='text' class='form-control manifestsearch-mawbl'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>BOL Number</label>
							<div class="col-md-8">
								<input type='text' class='form-control manifestsearch-waybillnumber'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Package Code</label>
							<div class="col-md-8">
								<input type='text' class='form-control manifestsearch-packagecode'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Document Date From</label>
							<div class="col-md-8">
								<input type='text' class='form-control manifestsearch-docdatefrom datepicker'>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-md-4'>Dcoument Date To</label>
							<div class="col-md-8">
								<input type='text' class='form-control manifestsearch-docdateto datepicker'>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-4 col-md-8">
								<div class='button-group-btn fluid active' id='manifestsearch-searchbtn'>
									<img src="../resources/img/search.png">&nbsp;&nbsp;Search
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class='col-md-12'>
					<br>
					<table id='manifestsearch-table'>
						<tbody></tbody>
					</table>
					<br>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
/**** INITIALIZED *******/
$(document).ready(function() {
	var tabMFT = '#manifest-menutabpane';
	var inputfieldsMFT = ".manifest-inputfields";


	//$(tabMFT+' .modal-dialog').draggable();
	$(inputfieldsMFT + ' input,' + inputfieldsMFT + ' textarea,' + inputfieldsMFT + ' select').attr('disabled',
		'disabled');
	$(inputfieldsMFT + ' .transactionnumber').removeAttr('disabled').focus();
	$(tabMFT + " .select2").select2({
		width: '100%'
	});
	var datetoday = new Date();
	$(tabMFT + ' .datepicker').datepicker();
	$(tabMFT + ' .datetimepicker').datetimepicker();


	var refMFT = <?php echo json_encode($refMFT); ?>;
	if (refMFT != '') {
		getManifestInformation(refMFT);
		currentmanifestTxn = refMFT;
	}

	$(tabMFT + " .locationdropdownselect").select2({
		ajax: {
			url: "loadables/dropdown/user-assigned-locations.php",
			dataType: 'json',
			delay: 100,
			data: function(params) {
				return {
					q: params.term // search term
				};
			},
			processResults: function(data) {
				return {
					results: data
				};
			},
			cache: true
		},
		minimumInputLength: 0,
		width: '100%'
	});

	$(tabMFT + " .origindestinationdropdownselect").select2({
		ajax: {
			url: "loadables/dropdown/origin-destination-port.php",
			dataType: 'json',
			delay: 100,
			data: function(params) {
				return {
					q: params.term // search term
				};
			},
			processResults: function(data) {
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

	$(tabMFT + " .modeoftransportdropdownselect").select2({
		ajax: {
			url: "loadables/dropdown/mode-of-transport.php",
			dataType: 'json',
			delay: 100,
			data: function(params) {
				return {
					q: params.term // search term
				};
			},
			processResults: function(data) {
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

	$(tabMFT + " .agentdropdownselect").select2({
		ajax: {
			url: "loadables/dropdown/agent.php",
			dataType: 'json',
			delay: 100,
			data: function(params) {
				return {
					q: params.term // search term
				};
			},
			processResults: function(data) {
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

	$(tabMFT + " .manifeststatusdropdownselect").select2({
		ajax: {
			url: "loadables/dropdown/manifest-status.report.php",
			dataType: 'json',
			delay: 100,
			data: function(params) {
				return {
					q: params.term // search term
				};
			},
			processResults: function(data) {
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

	$(tabMFT + " .pouchsizedropdownselect").select2({
		ajax: {
			url: "loadables/dropdown/pouch-size.php",
			dataType: 'json',
			delay: 100,
			data: function(params) {
				return {
					q: params.term // search term
				};
			},
			processResults: function(data) {
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


	$(tabMFT + " .manifestloadplandropdownselect").select2({
		ajax: {
			url: "loadables/dropdown/manifest-load-plan-number.report.php",
			dataType: 'json',
			delay: 100,
			data: function(params) {
				return {
					q: params.term // search term
				};
			},
			processResults: function(data) {
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


	$(tabMFT + " .carrierdropdownselect").select2({
		ajax: {
			url: "loadables/dropdown/carrier.php",
			dataType: 'json',
			delay: 100,
			data: function(params) {
				return {
					q: params.term // search term
				};
			},
			processResults: function(data) {
				return {
					results: data
				};
			},
			cache: true
		},
		minimumInputLength: 0,
		width: '100%'
	});

	$(tabMFT + " .vehicletypedropdownselect").select2({
		ajax: {
			url: "loadables/dropdown/vehicle-type.php",
			dataType: 'json',
			delay: 100,
			data: function(params) {
				return {
					q: params.term // search term
				};
			},
			processResults: function(data) {
				return {
					results: data
				};
			},
			cache: true
		},
		minimumInputLength: 0,
		width: '100%'
	});

	$(tabMFT + " .driverdropdownselect").select2({
		ajax: {
			url: "loadables/dropdown/personnel.php?position=DRIVER&hastype=1",
			dataType: 'json',
			delay: 100,
			data: function(params) {
				return {
					q: params.term // search term
				};
			},
			processResults: function(data) {
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

	$(tabMFT + " .mftloadplannumberdropdownselect").select2({
		ajax: {
			url: "loadables/dropdown/manifest-load-plan-numbers.php",
			dataType: 'json',
			delay: 100,
			data: function(params) {
				return {
					q: params.term // search term
				};
			},
			processResults: function(data) {
				return {
					results: data
				};
			},
			cache: true
		},
		minimumInputLength: 0,
		width: '100%'
	});


	function mftAddWaybillNumber() {
		$(tabMFT + ' #mftaddwaybillnumbermodal').modal('show');
	}

	function getWaybillCountInLDP(manifest) {

		$.post(server + 'manifest.php', {
			getWaybillCount: 'oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@',
			manifest: manifest
		}, function(data) {
			//alert(data);
			data = $.parseJSON(data);
			$(contentMFT + ' .manifest-waybillcountinloadplan').val(data['waybillcount']);
		});
	}

	function mftDeleteWaybillNumber() {
		wbnumbersid = [];
		mftnumber = $(tabMFT + ' #pgtxnmanifest-id').attr('pgtxnmanifest-number');

		$(tabMFT + ' .mftwaybillcheckbox:checked').each(function() {
			wbnumbersid.push($(this).attr('rowid'));
		});
		if (wbnumbersid.length > 0) {
			$.post(server + 'manifest.php', {
				deleteWaybillNumber: 'dskljouioU#ouh$3ksk#Op1NEi34smo1sonk&$',
				wbnumbersid: wbnumbersid
			}, function(data) {

				if (data.trim() == 'success') {
					getWaybillCountInLDP(mftnumber);
					$(tabMFT + ' #manifest-waybilltbl').flexOptions({
						url: 'loadables/ajax/transactions.manifest-waybill.php?reference=' +
							mftnumber,
						sortname: 'waybill_number',
						sortorder: "asc"
					}).flexReload();

					$(contentMFT + ' #manifest-packagecodetbl').flexOptions({
						url: 'loadables/ajax/transactions.manifest-package-code.php?txnnumber=' +
							mftnumber,
						sortname: 'waybill_number, package_code',
						sortorder: "asc"
					}).flexReload();


				} else {
					alert(data);
				}



			});
		}
	}



	$(tabMFT + ' #manifest-waybilltbl').flexigrid({
		url: 'loadables/ajax/transactions.manifest-waybill.php?reference=' + refMFT,
		dataType: 'json',
		colModel: [{
				display: '',
				name: 'action',
				width: 40,
				sortable: false,
				align: 'center'
			},
			{
				display: 'BOL No.',
				name: 'waybill_number',
				width: 130,
				sortable: true,
				align: 'left'
			},
			{
				display: 'Pouch Size',
				name: 'pouchsize',
				width: 130,
				sortable: true,
				align: 'left'
			},
			{
				display: 'Date',
				name: 'document_date',
				width: 150,
				sortable: true,
				align: 'left'
			},
			{
				display: 'Requested Delivery',
				name: 'delivery_date',
				width: 130,
				sortable: true,
				align: 'left'
			},
			{
				display: 'Destination',
				name: 'destination',
				width: 200,
				sortable: true,
				align: 'left'
			},
			{
				display: 'No. of Package',
				name: 'package_number_of_packages',
				width: 150,
				sortable: true,
				align: 'left'
			},
			{
				display: 'Actual Weight',
				name: 'package_actual_weight',
				width: 180,
				sortable: true,
				align: 'left'
			},
			{
				display: 'CBM',
				name: 'package_cbm',
				width: 180,
				sortable: true,
				align: 'left'
			},
			{
				display: 'VMW',
				name: 'package_vw',
				width: 180,
				sortable: true,
				align: 'left'
			},
			{
				display: 'Shipper',
				name: 'shipper_account_name',
				width: 180,
				sortable: true,
				align: 'left'
			},
			{
				display: 'Consignee',
				name: 'consignee_account_name',
				width: 180,
				sortable: true,
				align: 'left'
			},
			{
				display: 'Mode of Transport',
				name: 'modeoftransport',
				width: 180,
				sortable: true,
				align: 'left'
			},
			{
				display: 'Amount for Collection',
				name: 'amount_for_collection',
				width: 250,
				sortable: true,
				align: 'left'
			},
			{
				display: 'Remarks',
				name: 'remarks',
				width: 250,
				sortable: true,
				align: 'left'
			}


		],
		buttons: [{
				name: 'Add',
				bclass: 'add mftaddwaybillbtn hidden',
				onpress: mftAddWaybillNumber
			},
			{
				separator: true
			},
			{
				name: 'Delete',
				bclass: 'delete mftdeletewaybillbtn hidden',
				onpress: mftDeleteWaybillNumber
			}
		],
		searchitems: [{
				display: 'BOL No.',
				name: 'waybill_number',
				isdefault: true
			},
			{
				display: 'Destination',
				name: 'destination'
			}
		],
		sortname: "waybill_number",
		sortorder: "asc",
		usepager: true,
		title: "",
		useRp: true,
		rp: 10, //rows per page
		showTableToggleBtn: false,
		resizable: false,
		//width: 800,
		height: 250,
		singleSelect: false
	});




	function mftAddPackageCode() {
		$(tabMFT + ' #mftaddpackagecodemodal').modal('show');
	}

	function mftDeletePackageCode() {

		packagecodeids = [];
		mftnumber = $(tabMFT + ' #pgtxnmanifest-id').attr('pgtxnmanifest-number');

		$(tabMFT + ' .mftpackagecoderowcheckbox:checked').each(function() {
			packagecodeids.push($(this).attr('rowid'));
		});

		$.post(server + 'manifest.php', {
			deletePackageCodes: 'dskljouioU#ouh$3ksk#Op1NEi34smo1sonk&$',
			packagecodeids: packagecodeids,
			mftnumber: mftnumber
		}, function(data) {

			if (data.trim() == 'success') {
				$(tabMFT + ' #manifest-waybilltbl').flexOptions({
					url: 'loadables/ajax/transactions.manifest-waybill.php?reference=' +
						mftnumber,
					sortname: 'waybill_number',
					sortorder: "asc"
				}).flexReload();

				$(contentMFT + ' #manifest-packagecodetbl').flexOptions({
					url: 'loadables/ajax/transactions.manifest-package-code.php?txnnumber=' +
						mftnumber,
					sortname: 'waybill_number, package_code',
					sortorder: "asc"
				}).flexReload();

			} else {
				alert(data);
			}

		});
	}

	$(tabMFT + ' #manifest-packagecodetbl').flexigrid({
		url: 'loadables/ajax/transactions.manifest-package-code.php?txnnumber=' + refMFT,
		dataType: 'json',
		colModel: [{
				display: '',
				name: 'action',
				width: 40,
				sortable: false,
				align: 'center'
			},
			{
				display: 'Package Code',
				name: 'package_code',
				width: 280,
				sortable: true,
				align: 'left'
			},
			{
				display: 'BOL No.',
				name: 'waybill_number',
				width: 200,
				sortable: true,
				align: 'left'
			}
		],
		buttons: [{
				name: 'Add',
				bclass: 'add mftaddpackagebtn hidden',
				onpress: mftAddPackageCode
			},
			{
				separator: true
			},
			{
				name: 'Delete',
				bclass: 'delete mftdeletepackagebtn hidden',
				onpress: mftDeletePackageCode
			}
		],
		searchitems: [{
				display: 'Code',
				name: 'package_code',
				isdefault: true
			},
			{
				display: 'BOL No.',
				name: 'waybill_number'
			}
		],
		sortname: "waybill_number, package_code",
		sortorder: "asc",
		usepager: true,
		title: "",
		useRp: true,
		rp: 10, //rows per page
		showTableToggleBtn: false,
		resizable: false,
		//width: 800,
		height: 250,
		singleSelect: false
	});





	$(tabMFT + " #manifestsearch-table").flexigrid({
		url: 'loadables/ajax/transactions.manifest-search.php',
		dataType: 'json',
		colModel: [{
				display: 'Manifest No.',
				name: 'txn_manifest.manifest_number',
				width: 120,
				sortable: true,
				align: 'left'
			},
			{
				display: 'Status',
				name: 'txn_manifest.status',
				width: 100,
				sortable: true,
				align: 'left'
			},
			{
				display: 'Load Plan No.',
				name: 'load_plan_number',
				width: 100,
				sortable: true,
				align: 'left'
			},
			{
				display: 'Origin',
				name: 'origin',
				width: 200,
				sortable: true,
				align: 'left'
			},
			{
				display: 'Destination',
				name: 'destinationfiltered',
				width: 200,
				sortable: true,
				align: 'left'
			},
			{
				display: 'Mode',
				name: 'mode_of_transport',
				width: 120,
				sortable: true,
				align: 'left'
			},
			{
				display: 'Agent',
				name: 'agent',
				width: 200,
				sortable: true,
				align: 'left'
			},
			{
				display: 'Vehicle Type',
				name: 'vehicle_type.description',
				width: 200,
				sortable: true,
				align: 'left'
			},
			{
				display: 'Document Date',
				name: 'txn_manifest.document_date',
				width: 100,
				sortable: true,
				align: 'left'
			},
			{
				display: 'MAWBL No./BL No.',
				name: 'mawbl_bl',
				width: 130,
				sortable: true,
				align: 'left'
			},
			{
				display: 'Created by',
				name: 'createdby',
				width: 150,
				sortable: true,
				align: 'left'
			},
			{
				display: 'Created Date',
				name: 'txn_manifest.created_date',
				width: 150,
				sortable: true,
				align: 'left'
			},
			{
				display: 'System ID',
				name: 'txn_manifest.id',
				width: 80,
				sortable: true,
				align: 'left',
				hide: true
			}
		],

		searchitems: [{
				display: 'Manifest No.',
				name: 'txn_manifest.manifest_number',
				isdefault: true
			},
			{
				display: 'Status',
				name: 'txn_manifest.status'
			},
			{
				display: 'Load Plan No.',
				name: 'load_plan_number'
			},
			{
				display: 'Origin',
				name: 'origin'
			},
			{
				display: 'Destination',
				name: 'destinationfiltered'
			},
			{
				display: 'Mode',
				name: 'mode_of_transport'
			},
			{
				display: 'Agent',
				name: 'agent'
			}
		],
		sortname: "txn_manifest.manifest_number",
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