<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Supplier Rate
	</div>

</div>
<div class="container-fluid">
    <div class="pageContent noborder">

    	<table id='supplierratetable'>
			<tbody></tbody>

		</table>


	</div>
</div>


<div class="modal fade" id="addsupplierratemodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					New
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<div class="col-md-12">
						<form class="form-horizontal">
							<div class="form-group">
								<div class="errordiv"></div>
							</div>

							<div class='col-md-7'>
								<div class='col-md-12'>
									<div class="form-group">
										<label class='control-label'>Type*</label>
										<select class='form-input form-control type noresetfld select2' style='width:100%'>
											<option value='PARCEL'>PARCEL</option>
											<option value='DOCUMENT'>DOCUMENT</option>
										</select>
									</div>

									
									<div class="form-group">
										<label class='control-label'>Shipment Type*</label>
										<select class='form-input form-control shipmenttype shipmenttypedropdownselect noresetfld' style='width:100%'>
										</select>
									</div>

									<div class="form-group">
										<label class='control-label'>3PL*</label>
										<select class='form-input form-control 3pl 3pldropdownselect noresetfld' style='width:100%'>
										</select>
									</div>
									
									<div class="form-group">
										<label class='control-label'>Origin*</label>
										<select class='form-input form-control origin origindestinationdropdownselect noresetfld' style='width:100%'></select>
									</div>	
									<div class="form-group">
											<label class='control-label'>Zone Destination*</label>
											<select class='form-input form-control zone zonedropdownselect' style='width:100%'></select>
									</div>
									<div class="form-group hidden">
											<label class='control-label'>Destination</label>
											<select class='form-input form-control destination origindestinationdropdownselect noresetfld' style='width:100%'></select>
									</div>	
									<div class="form-group modeoftransportwrapper hidden">
											<label class='control-label'>Mode of Transport</label>	
											<select class='form-input form-control modeoftransport modeoftransportdropdownselect noresetfld' style='width:100%'></select>
									</div>
									<div class="form-group serviceswrapper hidden">
											<label class='control-label'>Services</label>	
											<select class='form-input form-control services servicesdropdownselect noresetfld' style='width:100%'></select>
									</div>
									<div class="form-group freightcomputationwrapper hidden">
											<label class='control-label'>Freight Computation</label>	
											<select class='form-input form-control freightcomputation freightcomputationdropdownselect noresetfld' style='width:100%'></select>
									</div>
									<div class="form-group pouchsizewrapper">
											<label class='control-label'>Pouch Size*</label>	
											<select class='form-input form-control pouchsize pouchsizedropdownselect noresetfld' style='width:100%'></select>
									</div>
									<div class="form-group hidden">
									    <br>
										<label class="control-label col-md-3">Rush Flag &nbsp;&nbsp;</label>
										<div class='col-md-9'>
											<input type="checkbox" class='togglebutton rushflag activeflag'>
										</div>
									</div>
									<div class="form-group pulloutflagwrapper hidden">
										<label class="control-label col-md-3">Pull Out Flag &nbsp;&nbsp;</label>
										<div class='col-md-9'>
											<input type="checkbox" class='togglebutton pulloutflag activeflag'>
										</div>
									</div>
									<div class="form-group fixedrateflagwrapper">
										<br>
										<label class="control-label col-md-3">Fixed Rate &nbsp;&nbsp;</label>
										<div class='col-md-9'>
											<input type="checkbox" class='togglebutton fixedrateflag'>
										</div>
									</div>


								</div>
							</div>

							<div class='col-md-5'>
								<div class='col-md-12'>
									<!--<div class="form-group odaratewrapper normalratewrapper">
										<label class='control-label'>ODA Rate</label>
										<input type='number' class='form-input form-control odarate text-right' placeholder="%">
									</div>-->
									<div class="form-group freightratewrapper normalratewrapper hidden">
										<label class='control-label'>Freight Rate*</label>
										<input type='number' class='form-input form-control freightrate text-right'>
									</div>
									<div class="form-group valuationwrapper normalratewrapper">
										<label class='control-label'>Valuation</label>
										<input type='number' class='form-input form-control valuation text-right' placeholder="%">
									</div>
									<div class="form-group insuranceratewrapper normalratewrapper">
										<label class='control-label'>Insurance Rate</label>
										<input type='number' class='form-input form-control insurancerate text-right'>
									</div>
									<div class="form-group fuelratewrapper normalratewrapper">
										<label class='control-label'>Fuel Rate</label>
										<input type='number' class='form-input form-control fuelrate text-right'>
									</div>
									<div class="form-group bunkerratewrapper normalratewrapper">
										<label class='control-label'>Bunker Rate</label>
										<input type='number' class='form-input form-control bunkerrate text-right'>
									</div>
									<div class="form-group minimumratewrapper normalratewrapper">
										<label class='control-label'>Minimum Rate</label>
										<input type='number' class='form-input form-control minimumrate text-right'>
									</div>
									<div class="form-group fixedrateamountwrapper hidden">
										<label class='control-label'>Fixed Rate Amount</label>
										<input type='number' class='form-input form-control fixedrateamount text-right'>
									</div>
									<div class="form-group pulloutfeewrapper hidden">
										<label class='control-label'>Pull Out Fee</label>
										<input type='number' class='form-input form-control pulloutfee text-right'>
									</div>

								</div>
							</div>
							
							
						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn supplierratemodal-savebtn' id='addsupplierratemodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editsupplierratemodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Edit
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<div class="col-md-12">
						<form class="form-horizontal">

							<div class="form-group">
								<div class="errordiv"></div>
							</div>
							<input type='hidden' class='supplierratemodalid'>
							<div class='col-md-7'>
								<div class='col-md-12'>
									<div class="form-group">
										<label class='control-label'>Type*</label>
										<select class='form-input form-control type noresetfld select2' style='width:100%'>
											<option value='PARCEL'>PARCEL</option>
											<option value='DOCUMENT'>DOCUMENT</option>
										</select>
									</div>

									<div class="form-group">
										<label class='control-label'>Shipment Type*</label>
										<select class='form-input form-control shipmenttype shipmenttypedropdownselect noresetfld' style='width:100%'>
										</select>
									</div>

									<div class="form-group">
										<label class='control-label'>3PL*</label>
										<select class='form-input form-control 3pl 3pldropdownselect noresetfld' style='width:100%'>
										</select>
									</div>
									
									<div class="form-group">
										<label class='control-label'>Origin*</label>
										<select class='form-input form-control origin origindestinationdropdownselect noresetfld' style='width:100%'></select>
									</div>	
									<div class="form-group">
											<label class='control-label'>Zone Destination*</label>
											<select class='form-input form-control zone zonedropdownselect' style='width:100%'></select>
									</div>
									<div class="form-group hidden">
											<label class='control-label'>Destination*</label>
											<select class='form-input form-control destination origindestinationdropdownselect' style='width:100%'></select>
									</div>	
									<div class="form-group modeoftransportwrapper hidden">
											<label class='control-label'>Mode of Transport*</label>	
											<select class='form-input form-control modeoftransport modeoftransportdropdownselect' style='width:100%'></select>
									</div>
									<div class="form-group serviceswrapper hidden">
											<label class='control-label'>Services*</label>	
											<select class='form-input form-control services servicesdropdownselect' style='width:100%'></select>
									</div>
									<div class="form-group freightcomputationwrapper hidden">
											<label class='control-label'>Freight Computation*</label>	
											<select class='form-input form-control freightcomputation freightcomputationdropdownselect' style='width:100%'></select>
									</div>
									<div class="form-group pouchsizewrapper">
											<label class='control-label'>Pouch Size*</label>	
											<select class='form-input form-control pouchsize pouchsizedropdownselect noresetfld' style='width:100%'></select>
									</div>
									<div class="form-group hidden">
										<br>
										<label class="control-label col-md-3">Rush Flag &nbsp;&nbsp;</label>
										<div class='col-md-9'>
											<input type="checkbox" class='togglebutton rushflag activeflag'>
										</div>
									</div>
									<div class="form-group pulloutflagwrapper hidden">
										<label class="control-label col-md-3">Pull Out Flag &nbsp;&nbsp;</label>
										<div class='col-md-9'>
											<input type="checkbox" class='togglebutton pulloutflag activeflag'>
										</div>
									</div>
									<div class="form-group fixedrateflagwrapper">
										<br>
										<label class="control-label col-md-3">Fixed Rate &nbsp;&nbsp;</label>
										<div class='col-md-9'>
											<input type="checkbox" class='togglebutton fixedrateflag'>
										</div>
									</div>


								</div>
							</div>

							<div class='col-md-5'>
								<div class='col-md-12'>
									<!--<div class="form-group odaratewrapper normalratewrapper">
										<label class='control-label'>ODA Rate</label>
										<input type='number' class='form-input form-control odarate text-right' placeholder="%">
									</div>-->
									<div class="form-group freightratewrapper normalratewrapper hidden">
										<label class='control-label'>Freight Rate*</label>
										<input type='number' class='form-input form-control freightrate text-right'>
									</div>
									<div class="form-group valuationwrapper normalratewrapper">
										<label class='control-label'>Valuation*</label>
										<input type='number' class='form-input form-control valuation text-right' placeholder="%">
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
									<div class="form-group fixedrateamountwrapper hidden">
										<label class='control-label'>Fixed Rate Amount*</label>
										<input type='number' class='form-input form-control fixedrateamount text-right'>
									</div>
									<div class="form-group pulloutfeewrapper hidden">
										<label class='control-label'>Pull Out Fee*</label>
										<input type='number' class='form-input form-control pulloutfee text-right'>
									</div>

								</div>
							</div>	

						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn supplierratemodal-savebtn' id='editsupplierratemodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>


<div class="modal fade" id="viewsupplierratefreightcharge">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					supplier Rate - Freight Charge
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<input type='hidden' class='noresetfld' id='viewsupplierratefreightcharge-supplierrateid'>

					<div class='form-horizontal'>
						<div class="form-group">
							<div class='col-md-12'>
								<div class="errordiv"></div>
							</div>
						</div>
						<div class="form-group">
							<div class='col-md-8'>
								<div class="form-group">
									<label class='control-label col-md-3'>From (KG)</label>
									<div class='col-md-9'>
										<input type='number' class='form-input form-control viewsupplierratefreightcharge-fromkg text-right'>
									</div>
								</div>
								<div class="form-group">
									<label class='control-label col-md-3'>To (KG)</label>
									<div class='col-md-9'>
										<input type='number' class='form-input form-control viewsupplierratefreightcharge-tokg text-right'>
									</div>
								</div>
								<div class="form-group">
									<label class='control-label col-md-3'>Freight Charge</label>
									<div class='col-md-9'>
										<input type='number' class='form-input form-control viewsupplierratefreightcharge-freightcharge text-right'>
									</div>
								</div>
								<div class="form-group">
									<label class='control-label col-md-3'>Excess Weight Charge</label>
									<div class='col-md-9'>
										<input type='number' class='form-input form-control viewsupplierratefreightcharge-excessweightcharge text-right'>
									</div>
								</div>
								<div class="form-group">
									<label class='control-label col-md-3'></label>
									<div class='col-md-9'>
										<div class='smallbuttons-wrapper'>
											<button class='btn mybtn viewsupplierratefreightcharge-insertratebtn datatablebtn'>
												<i class='fa fa-xs fa-plus'></i>Add
											</button>
											<button class='btn mybtn viewsupplierratefreightcharge-clearratefieldsbtn datatablebtn'>
												<i class='fa fa-xs fa-refresh'></i>Clear
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<table id='viewsupplierratefreightcharge-tbl'>
						<tbody></tbody>
					</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="editsupplierratefreightchargemodal">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">

			<div class="modal-header">
				<div class='page-title'>
					Edit Freight Charge
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<div class='form-horizontal'>
							<input type='hidden' id='supplierratefreightchargeID'>
							<div class='col-md-12'>
								<div class="form-group">
									<div class="errordiv"></div>
								</div>
							
								<div class="form-group">
									<label class='control-label'>From (KG)</label>
									<input type='number' class='form-input form-control editsupplierratefreightchargemodal-fromkg text-right'>
								</div>
								<div class="form-group">
									<label class='control-label'>To (KG)</label>
									<input type='number' class='form-input form-control editsupplierratefreightchargemodal-tokg text-right'>
								</div>
								<div class="form-group">
									<label class='control-label'>Freight Charge</label>
									<input type='number' class='form-input form-control editsupplierratefreightchargemodal-freightcharge text-right'>
								</div>
								<div class="form-group">
									<label class='control-label'>Excess Weight Charge</label>
									<input type='number' class='form-input form-control editsupplierratefreightchargemodal-excessweightcharge text-right'>
									
								</div>
							</div>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn editsupplierratefreightchargemodal-savebtn' id='editsupplierratemodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>

		</div>
	</div>
</div>


<div class="modal fade" id="uploadsupplierratemodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Upload File
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action='../scripts/supplier-rate-upload.php' method='post' id='uploadsupplierratemodal-form'  enctype='multipart/form-data' target='supplierrateuploadtransactionlogframe'>
                    <div class='col-md-4'>
                        Please make sure to follow the right format.
                        Click <a class='pointer' id='supplierrate-downloadtransactionfiletemplatebtn' href='../file-templates/supplier-rate-template.xlsx'>here</a> to download file template.
                    </div>
                    <div class='col-md-offset-1 col-md-6'>
                        <div class="form-group">
                            <label class='control-label'>Select an Excel File</label>
                            <input type='file' class='form-control uploadsupplierratemodal-file' name='uploadsupplierratemodal-file'>
                        </div>
                    </div>
                </form>
                <br>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button class='btn btn-blue2 mybtn' id='uploadsupplierratemodal-uploadbtn'>Upload</button>
                    <button class='btn btn-blue2 mybtn modal-cancelbtn' >Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="supplierrate-uploadtransactionlogmodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Uploading File...
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <iframe id="supplierrateuploadtransactionlogframe" name="supplierrateuploadtransactionlogframe" height="600" width="100%" frameborder="0" scrolling="yes" style='background: #fff'></iframe>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
	$(document).ready(function(){

		var tabsupplierRATE = '#supplierrate-menutabpane';
		$(tabsupplierRATE+' .tagsinput').tagsinput();
		$(tabsupplierRATE+' .select2').select2();


		$(tabsupplierRATE+' .modal-dialog').draggable();
		$(tabsupplierRATE+' .togglebutton').bootstrapToggle({
		      on: 'Yes',
		      off: 'No',
		      size: 'mini',
		      width: '100px'
		});

		$(tabsupplierRATE+" .shipmenttypedropdownselect").select2({
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
	                minimumInputLength: 0
	    });

		$(tabsupplierRATE+" .3pldropdownselect").select2({
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

		$(tabsupplierRATE+" .origindestinationdropdownselect").select2({
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

	    $(tabsupplierRATE+" .modeoftransportdropdownselect").select2({
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

	    $(tabsupplierRATE+" .servicesdropdownselect").select2({
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

	    $(tabsupplierRATE+" .freightcomputationdropdownselect").select2({
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

	    $(tabsupplierRATE+" .pouchsizedropdownselect").select2({
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

	   	$(tabsupplierRATE+" .zonedropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/zone.php",
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


		

		$(tabsupplierRATE+" #supplierratetable").flexigrid({
				url: 'loadables/ajax/maintenance.supplier-rate.php',
				dataType: 'json',
				colModel : [
						{display: 'Actions', name : 'action', width : 70, sortable : false, align: 'center'},
						{display: 'System ID', name : 'id', width : 70, sortable : true, align: 'left'},
						{display: 'Shipment Type', name : 'shipmenttype', width : 130, sortable : true, align: 'left'},
						{display: '3PL', name : 'thirdpartylogistic', width : 100, sortable : true, align: 'left'},
						{display: 'Type', name : 'waybill_type', width : 100, sortable : true, align: 'left'},
						{display: 'Pouch Size', name : 'pouchsize', width : 130, sortable : true, align: 'left'},
						{display: 'Origin', name : 'origin', width : 200, sortable : true, align: 'left'},
						{display: 'Zone', name : 'zone', width : 200, sortable : true, align: 'left'},
						/*{display: 'Destination', name : 'destination', width : 200, sortable : true, align: 'left'},
						{display: 'Mode of Transport', name : 'mode_of_transport', width : 130, sortable : true, align: 'left'},
						{display: 'Services', name : 'services', width : 100, sortable : true, align: 'left'},
						{display: 'Rush Flag', name : 'rush_flag', width : 80, sortable : true, align: 'center'},
						{display: 'Pull Out Flag', name : 'pull_out_flag', width : 80, sortable : true, align: 'center'},
						{display: 'Freight Computation', name : 'freight_computation', width : 130, sortable : true, align: 'left'},
						
						{display: 'Fixed Rate Flag', name : 'fixed_rate_flag', width : 80, sortable : true, align: 'center'},
						{display: 'Fixed Rate Amount', name : 'fixed_rate_amount', width : 100, sortable : true, align: 'right'},*/
						//{display: 'Pull Out Fee', name : 'pull_out_fee', width : 100, sortable : true, align: 'right'},
						{display: 'Freight Rate', name : 'freight_rate', width : 100, sortable : true, align: 'right'},
						{display: 'Valuation (%)', name : 'valuation', width : 100, sortable : true, align: 'right'},
						{display: 'Insurance Rate', name : 'insurance_rate', width : 100, sortable : true, align: 'right'},
						{display: 'Fuel Rate', name : 'fuel_rate', width : 100, sortable : true, align: 'right'},
						{display: 'Bunker Rate', name : 'bunker_rate', width : 100, sortable : true, align: 'right'},
						{display: 'Minimum Rate', name : 'minimum_rate', width : 100, sortable : true, align: 'right'},
						{display: 'Created by', name : 'created_by', width : 150, sortable : true, align: 'left', hide: true},
						{display: 'Created Date', name : 'created_date', width : 125, sortable : true, align: 'left', hide: true},
						{display: 'Updated by', name : 'updated_by', width : 150, sortable : true, align: 'left', hide: true},
						{display: 'Updated Date', name : 'updated_date', width : 125, sortable : true, align: 'left', hide: true}
				],
				buttons : [
						{name: 'Add', bclass: 'add addsupplierratebtn', onpress : addsupplierRate},
						{separator: true},
						{name: 'Delete', bclass: 'delete deletesupplierratebtn', onpress : deletesupplierRate},
						{separator: true},
						{name: 'Upload', bclass: 'upload uploadsupplierratebtn', onpress : uploadsupplierRate}
				],
				searchitems : [
						{display: 'Type', name : 'waybill_type', isdefault: true},
						{display: 'Origin', name : 'origin'},
						{display: 'Destination', name : 'destination'},
						{display: 'Mode of Transport', name : 'mode_of_transport'},
						{display: 'Freight Computation', name : 'freight_computation'},
						{display: 'Pouch Size', name : 'pouchsize'}
				],
				sortname: "waybill_type, mode_of_transport, origin, destination",
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

		function uploadsupplierRate(){
			$('#uploadsupplierratemodal').modal('show');
		
		}

		function addsupplierRate(){
				$('#addsupplierratemodal').modal('show');
		}

		function deletesupplierRate(){

		
			if(parseInt($('#supplierratetable .trSelected').length)>0){
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
							$('#supplierratetable .trSelected').each(function(){
								data.push($(this).attr('rowid'));
							});
							$.post('../scripts/supplier-rate.php',{deleteSelectedRows:'skj$oihdtpoa$I#@4noi4AIFNlskoRboIh4!j3sio$*yhs',data:data},function(response){

								if(response.trim()=='success'){
									$('#supplierratetable').flexOptions({
											url:'loadables/ajax/maintenance.supplier-rate.php',
											sortname: "mode_of_transport",
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


		$(tabsupplierRATE+" #viewsupplierratefreightcharge-tbl").flexigrid({
				url: 'loadables/ajax/maintenance.supplier-rate-freight-charge.php',
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
						
						{name: 'Delete', bclass: 'delete deletesupplierratefreightchargebtn', onpress : deletesupplierRateFreightCharge}
				],
				/*searchitems : [
						{display: 'Handling Instruction', name : 'handlinginstruction', isdefault: true}
				],*/
				sortname: "from_kg",
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

		function deletesupplierRateFreightCharge(){
			var supplierrateid = $(tabsupplierRATE+' #viewsupplierratefreightcharge-supplierrateid').val();
			if(parseInt($('#viewsupplierratefreightcharge-tbl .viewsupplierratefreightcharge-checkbox:checked').length)>0){
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
							$('#viewsupplierratefreightcharge-tbl .viewsupplierratefreightcharge-checkbox:checked').each(function(){
								data.push($(this).attr('rowid'));
							});
							$.post('../scripts/supplier-rate.php',{deletesupplierRateFreightCharge:'$jhfoFIsmdlPE#9s3#7skoRboIh4!j3sio$*yhs',data:data},function(response){

								if(response.trim()=='success'){
									$('#viewsupplierratefreightcharge-tbl').flexOptions({
											url:'loadables/ajax/maintenance.supplier-rate-freight-charge.php?supplierrateid='+supplierrateid,
											sortname: "from_kg",
											sortorder: "asc"
									}).flexReload(); 
								}
								else if(response.trim()=='noaccess'){
									say("Unable to delete selected row(s). No user permission.");
									$('#viewsupplierratefreightcharge-tbl').flexOptions({
											url:'loadables/ajax/maintenance.supplier-rate-freight-charge.php?supplierrateid='+supplierrateid,
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