<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Agent
	</div>

</div>
<div class="container-fluid">
    <div class="pageContent noborder">

    	<table id='agenttable'>
			<tbody></tbody>

		</table>


	</div>
</div>


<div class="modal fade" id="addagentmodal">
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

							<!--<div class='tabpane-white margin-top-20 margin-bottom-10'>
								<ul class="nav nav-tabs">
									<li role="presentation" class="active" data-pane='#agentaddgeneralinfo-pane'><a href="#">General Info</a></li>
									<li role="presentation" data-pane='#agentaddaddress-pane'><a href="#">Address</a></li>
									<li role="presentation" data-pane='#agentaddcontact-pane'><a href="#">Contacts</a></li>
								</ul>
								<div class='tab-panes'>
									<div class='pane active' id='agentaddgeneralinfo-pane'>									
											 	<div class="form-group">
													<label class='control-label col-md-2'>Code</label>
													<div class='col-md-4'>
														<input type='text' class='form-input form-control inputtxtfld code'>
													</div>
												</div>
												<div class="form-group">
													<label class='control-label col-md-2'>Company Name</label>
													<div class='col-md-8'>
														<input type='text' class='form-input form-control inputtxtfld companyname'>
													</div>
												</div>
												<div class="form-group">
													<label class='control-label col-md-2'>Area</label>
													<div class='col-md-5'>
														<input type='text' class='form-input form-control inputtxtfld area'>
													</div>
												</div>
												<div class="form-group">
													<label class='control-label col-md-2'>Remarks</label>
													<div class='col-md-8'>
														<textarea class='form-control inputtxtfld remarks' rows='5'></textarea>
													</div>
												</div>
									</div>

									<div class='pane' id='agentaddaddress-pane'>
												<div class="form-group">
													<label class='control-label col-md-2'>Street</label>
													<div class='col-md-8'>
														<input type='text' class='form-input form-control inputtxtfld street'>
													</div>
												</div>
												<div class="form-group">
													<label class='control-label col-md-2'>District/Barangay</label>
													<div class='col-md-6'>
														<input type='text' class='form-input form-control inputtxtfld district'>
													</div>
												</div>
												<div class="form-group">
													<label class='control-label col-md-2'>City</label>
													<div class='col-md-7'>
														<input type='text' class='form-input form-control inputtxtfld city'>
													</div>
												</div>
												<div class="form-group">
													<label class='control-label col-md-2'>Region/Province</label>
													<div class='col-md-8'>
														<input type='text' class='form-input form-control inputtxtfld province'>
													</div>
												</div>
												<div class="form-group">
													<label class='control-label col-md-2'>Zip Code</label>
													<div class='col-md-3'>
														<input type='text' class='form-input form-control inputtxtfld zipcode'>
													</div>
												</div>
												<div class="form-group">
													<label class='control-label col-md-2'>Country</label>
													<div class='col-md-8'>
														<select class='form-control country inputslctfld countriesdropdownselect'></select>
													</div>
												</div>
									</div>

									<div class='pane' id='agentaddcontact-pane'>
												<div class='col-md-6'>
													<div class="form-group">
														<label class='control-label col-md-3'>Contact</label>
														<div class='col-md-9'>
															<input type='text' class='form-input form-control inputtxtfld contactfld'>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-3'>Phone Number</label>
														<div class='col-md-9'>
															<input type='text' class='form-input form-control inputtxtfld phonenumberfld'>
														</div>
													</div>
												</div>
												<div class='col-md-6'>
													<div class="form-group">
														<label class='control-label col-md-3'>Email</label>
														<div class='col-md-9'>
															<input type='text' class='form-input form-control inputtxtfld emailfld'>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-3'>Mobile Number</label>
														<div class='col-md-9'>
															<input type='text' class='form-input form-control inputtxtfld mobilenumberfld'>
														</div>
													</div>
													<div class="form-group">
														<label class='control-label col-md-3'></label>
														<div class='col-md-9'>
															<div class='smallbuttons-wrapper'>
																<button class='btn mybtn agent-insertcontactbtn datatablebtn'>
																	<i class='fa fa-xs fa-plus'></i>Add
																</button>
																<button class='btn mybtn agent-removecontactbtn datatablebtn'>
																    <i class='fa fa-xs fa-trash'></i>Remove
																</button>
																<button class='btn mybtn agent-clearcontactfieldsbtn datatablebtn'>
																    <i class='fa fa-xs fa-refresh'></i>Clear
																</button>
															</div>
														</div>
													</div>
												</div>
											
												<div class='table-sm'>
													<table class='table table-condensed table-hover pointer table-striped table-bordered text-nowrap mytable gray-template table-font-sm no-side-borders agentcontactdetailstbl' id='agent-addcontactdetailtbl' style='width:100%'>
									    				<thead>
									    					<tr>
									    						<th class='column-nosort column-checkbox text-center'></th>
									    						<th class='column-nosort column-checkbox'>DEFAULT</th>
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
									</div>

								</div>
							</div>-->

							<div class='row'>
								<fieldset>
									<legend>General Information</legend>
									<div class="form-group">
										<label class='control-label col-md-2'>Code*</label>
										<div class='col-md-4'>
											<input type='text' class='form-input form-control inputtxtfld code'>
										</div>
									</div>
									<div class="form-group">
										<label class='control-label col-md-2'>Company Name*</label>
										<div class='col-md-8'>
											<input type='text' class='form-input form-control inputtxtfld companyname'>
										</div>
									</div>
									<div class="form-group">
										<label class='control-label col-md-2'>Area*</label>
										<div class='col-md-5'>
											<input type='text' class='form-input form-control inputtxtfld area'>
										</div>
									</div>
									<div class="form-group">
										<label class='control-label col-md-2'>Remarks</label>
										<div class='col-md-8'>
											<textarea class='form-control inputtxtfld remarks' rows='5'></textarea>
										</div>
									</div>
								</fieldset>
							</div>

							
							

							<div class='row'>
								<fieldset>
									<legend>Address Information</legend>
									<div class='addressgroupwrapper'>
										<div class="form-group">
											<label class='control-label col-md-2'>Region/Province*</label>
											<div class='col-md-8'>
												<select class='form-control inputslctfld province addrdropregion addressregiondropdownselect'></select>
											</div>
										</div>
										
										<div class="form-group">
											<label class='control-label col-md-2'>City*</label>
											<div class='col-md-7'>
												<select class='form-control inputslctfld city addrdropcity addresscitydropdownselect'></select>
											</div>
										</div>
										
										<div class="form-group">
											<label class='control-label col-md-2'>District/Barangay*</label>
											<div class='col-md-6'>
											<select class='form-control inputslctfld district addrdropdistrict addressdistrictdropdownselect'></select>
											</div>
										</div>
										<div class="form-group">
											<label class='control-label col-md-2'>Zip Code*</label>
											<div class='col-md-3'>
												<select class='form-control inputslctfld zipcode addrdropzip addresszipcodedropdownselect'></select>
											</div>
										</div>
										<div class="form-group">
											<label class='control-label col-md-2'>Street*</label>
											<div class='col-md-8'>
												<input type='text' class='form-input form-control inputtxtfld street'>
											</div>
										</div>
										<div class="form-group">
											<label class='control-label col-md-2'>Country*</label>
											<div class='col-md-8'>
												<select class='form-control country inputslctfld countriesdropdownselect'></select>
											</div>
										</div>
									</div>
								</fieldset>
							</div>

							
							<div class='row'>
								<fieldset>

										<legend>Contact Information *</legend>
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
															<button class='btn mybtn agent-insertcontactbtn datatablebtn'>
																<i class='fa fa-xs fa-plus'></i>Add
															</button>
															<button class='btn mybtn agent-removecontactbtn datatablebtn'>
															    <i class='fa fa-xs fa-trash'></i>Remove
															</button>
															<button class='btn mybtn agent-clearcontactfieldsbtn datatablebtn'>
															    <i class='fa fa-xs fa-refresh'></i>Clear
															</button>
														</div>
													</div>
												</div>
											</div>
										</fieldset>
										<div class='table-xs'>
											<table class='table table-condensed table-hover pointer table-striped table-bordered text-nowrap mytable gray-template table-font-sm no-side-borders agentcontactdetailstbl' id='agent-addcontactdetailtbl' style='width:100%'>
							    				<thead>
							    					<tr>
							    						<th class='column-nosort column-checkbox text-center'></th>
							    						<th class='column-nosort column-checkbox'>DEFAULT</th>
													    <th>CONTACT PERSON</th>
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


							
							
							
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn agentmodal-savebtn' id='addagentmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editagentmodal">
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
							<input type='hidden' class='agentmodalid mdlIDfld'>

							<div class='row'>
								<fieldset>
									<legend>General Information</legend>
									<div class="form-group">
										<label class='control-label col-md-2'>Code*</label>
										<div class='col-md-4'>
											<input type='text' class='form-input form-control code'>
										</div>
									</div>
									<div class="form-group">
										<label class='control-label col-md-2'>Company Name*</label>
										<div class='col-md-8'>
											<input type='text' class='form-input form-control companyname'>
										</div>
									</div>
									<div class="form-group">
										<label class='control-label col-md-2'>Area*</label>
										<div class='col-md-5'>
											<input type='text' class='form-input form-control area'>
										</div>
									</div>
									<div class="form-group">
										<label class='control-label col-md-2'>Remarks</label>
										<div class='col-md-8'>
											<textarea class='form-control remarks' rows='5'></textarea>
										</div>
									</div>
								</fieldset>
							</div>

							
							

							<div class='row'>
								<fieldset>
									<legend>Address Information</legend>
									<div class='addressgroupwrapper'>
										<div class="form-group">
											<label class='control-label col-md-2'>Region/Province*</label>
											<div class='col-md-8'>
												<select class='form-control inputslctfld province editdropregion addressregiondropdownselect'></select>
											</div>
										</div>
										
										
										<div class="form-group">
											<label class='control-label col-md-2'>City*</label>
											<div class='col-md-7'>
												<select class='form-control inputslctfld city editdropcity addresscitydropdownselect'></select>
											</div>
										</div>
										
										<div class="form-group">
											<label class='control-label col-md-2'>District/Barangay*</label>
											<div class='col-md-6'>
											<select class='form-control inputslctfld district editdropdistrict addressdistrictdropdownselect'></select>
											</div>
										</div>
										<div class="form-group">
											<label class='control-label col-md-2'>Zip Code*</label>
											<div class='col-md-3'>
												<select class='form-control inputslctfld zipcode editdropzip addresszipcodedropdownselect'></select>
											</div>
										</div>
										<div class="form-group">
											<label class='control-label col-md-2'>Street*</label>
											<div class='col-md-8'>
												<input type='text' class='form-input form-control street'>
											</div>
										</div>
										<div class="form-group">
											<label class='control-label col-md-2'>Country*</label>
											<div class='col-md-8'>
												<select class='form-control country countriesdropdownselect'></select>
											</div>
										</div>
									</div>
								</fieldset>
							</div>

							<!--<div class='row'>
								<fieldset>
									<legend>Contact Information</legend>
									<div class="form-group">
										<label class='control-label col-md-2'>Phone Numbers</label>
										<div class='col-md-8'>
											<input type='text' class='form-input form-control phone'>
										</div>
									</div>
									<div class="form-group">
										<label class='control-label col-md-2'>Email Addresses</label>
										<div class='col-md-8'>
											<input type='text' class='form-input form-control email'>
										</div>
									</div>
									<div class="form-group">
										<label class='control-label col-md-2'>Mobile Numbers</label>
										<div class='col-md-8'>
											<input type='text' class='form-input form-control mobile'>
										</div>
									</div>
								</fieldset>
							</div>-->
							<div class='row'>
								<fieldset>

										<legend>Contact Information*</legend>
										<fieldset>
											<div class='col-md-6'>
												<div class="form-group">
													<label class='control-label col-md-3'>Contact Person*</label>
													<div class='col-md-9'>
														<input type='text' class='form-input form-control contactfld'>
													</div>
												</div>
												<div class="form-group">
													<label class='control-label col-md-3'>Phone Number**</label>
													<div class='col-md-9'>
														<input type='text' class='form-input form-control phonenumberfld'>
													</div>
												</div>
											</div>
											<div class='col-md-6'>
												<div class="form-group">
													<label class='control-label col-md-3'>Email**</label>
													<div class='col-md-9'>
														<input type='text' class='form-input form-control emailfld'>
													</div>
												</div>
												<div class="form-group">
													<label class='control-label col-md-3'>Mobile Number**</label>
													<div class='col-md-9'>
														<input type='text' class='form-input form-control mobilenumberfld'>
													</div>
												</div>
												<div class="form-group">
													<label class='control-label col-md-3'></label>
													<div class='col-md-9'>
														<div class='smallbuttons-wrapper'>
															<button class='btn mybtn agent-insertcontactbtn datatablebtn'>
																<i class='fa fa-xs fa-plus'></i>Add
															</button>
															<button class='btn mybtn agent-removecontactbtn datatablebtn'>
															    <i class='fa fa-xs fa-trash'></i>Remove
															</button>
															<button class='btn mybtn agent-clearcontactfieldsbtn datatablebtn'>
															    <i class='fa fa-xs fa-refresh'></i>Clear
															</button>
														</div>
													</div>
												</div>
											</div>
										</fieldset>
										<div class='table-xs'>
											<table class='table table-condensed table-hover pointer table-striped table-bordered text-nowrap mytable gray-template table-font-sm no-side-borders agentcontactdetailstbl' id='agent-editcontactdetailtbl' style='width:100%'>
							    				<thead>
							    					<tr>
							    						<th class='column-nosort column-checkbox text-center'></th>
							    						<th class='column-nosort column-checkbox'>DEFAULT</th>
													    <th>CONTACT PERSON</th>
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


							
							
							
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn agentmodal-savebtn' id='editagentmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>


<div class="modal fade" id="uploadagentmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Upload File
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action='../scripts/agent-upload.php' method='post' id='uploadagentmodal-form'  enctype='multipart/form-data' target='agentuploadtransactionlogframe'>
                    <div class='col-md-4'>
                        Please make sure to follow the right format.
                        Click <a class='pointer' id='agent-downloadtransactionfiletemplatebtn' href='../file-templates/agent-template.xlsx'>here</a> to download file template.
                    </div>
                    <div class='col-md-offset-1 col-md-6'>
                        <div class="form-group">
                            <label class='control-label'>Select an Excel File</label>
                            <input type='file' class='form-control uploadagentmodal-file' name='uploadagentmodal-file'>
                        </div>
                    </div>
                </form>
                <br>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button class='btn btn-blue2 mybtn' id='uploadagentmodal-uploadbtn'>Upload</button>
                    <button class='btn btn-blue2 mybtn modal-cancelbtn' >Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="agent-uploadtransactionlogmodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Uploading File...
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <iframe id="agentuploadtransactionlogframe" name="agentuploadtransactionlogframe" height="600" width="100%" frameborder="0" scrolling="yes" style='background: #fff'></iframe>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
	$(document).ready(function(){

		var tabagent = '#agent-menutabpane';
		$(tabagent+' .tagsinput').tagsinput();

		$('.modal-dialog').draggable();

		$(tabagent+" .addressdistrictdropdownselect").select2({
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

	    $(tabagent+" .addresscitydropdownselect").select2({
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

	    $(tabagent+" .addresszipcodedropdownselect").select2({
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

	    $(tabagent+" .addressregiondropdownselect").select2({
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

		$(tabagent+" .countriesdropdownselect").select2({
	            ajax: {
	                    url: "Loadables/dropdown/country.php",
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




		var dt = $(tabagent+' .agentcontactdetailstbl').DataTable({
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

		
		

		$("#agenttable").flexigrid({
				url: 'loadables/ajax/maintenance.agent.php',
				dataType: 'json',
				colModel : [
						{display: 'Actions', name : 'action', width : 70, sortable : false, align: 'center'},
						{display: 'System ID', name : 'id', width : 70, sortable : true, align: 'left'},
						{display: 'Code', name : 'code', width : 100, sortable : true, align: 'left'},
						{display: 'Company Name', name : 'company_name', width : 250, sortable : true, align: 'left'},
						{display: 'Area', name : 'area', width : 130, sortable : true, align: 'left'},
						{display: 'Remarks', name : 'remarks', width : 130, sortable : true, align: 'left'},			
						{display: 'Street', name : 'company_street_address', width : 150, sortable : true, align: 'left', hide: true},
						{display: 'District', name : 'company_district', width : 150, sortable : true, align: 'left', hide: true},
						{display: 'City', name : 'company_city', width : 150, sortable : true, align: 'left', hide: true},
						{display: 'Region', name : 'company_state_province', width : 150, sortable : true, align: 'left', hide: true},
						{display: 'Zip Code', name : 'company_zip_code', width : 150, sortable : true, align: 'left', hide: true},
						{display: 'Country', name : 'company_country', width : 150, sortable : true, align: 'left', hide: true},
						{display: 'Created by', name : 'created_by', width : 150, sortable : true, align: 'left'},
						{display: 'Created Date', name : 'created_date', width : 125, sortable : true, align: 'left'},
						{display: 'Updated by', name : 'updated_by', width : 150, sortable : true, align: 'left'},
						{display: 'Updated Date', name : 'updated_date', width : 125, sortable : true, align: 'left'}
				],
				buttons : [
						{name: 'Add', bclass: 'add addagentbtn', onpress : addAgent},
						{separator: true},
						{name: 'Delete', bclass: 'delete deleteagentbtn', onpress : deleteAgent},
						{separator: true},
						{name: 'Upload', bclass: 'upload uploadagentbtn', onpress : uploadAgent}
				],
				searchitems : [
						{display: 'Code', name : 'code', isdefault: true},
						{display: 'Company Name', name : 'company_name'},
						{display: 'Area', name : 'area'},
						{display: 'Street', name : 'company_street_address'},
						{display: 'District', name : 'company_district'},
						{display: 'City', name : 'company_city'},
						{display: 'Region', name : 'company_state_province'},
						{display: 'Country', name : 'company_country'}
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

		function uploadAgent(){
			$('#uploadagentmodal').modal('show');
		
		}

		function addAgent(){
				$('#addagentmodal').modal('show');
		}

		function deleteAgent(){

		
			if(parseInt($('#agenttable .trSelected').length)>0){
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
							$('#agenttable .trSelected').each(function(){
								data.push($(this).attr('rowid'));
							});
							$.post('../scripts/agent.php',{deleteSelectedRows:'skj$oihdtpoa$I#@4noi4AIFNlskoRboIh4!j3sio$*yhs',data:data},function(response){

								if(response.trim()=='success'){
									$('#agenttable').flexOptions({
											url:'loadables/ajax/maintenance.agent.php',
											sortname: "company_name",
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
		
		userAccess();
			

		
	});
	
</script>