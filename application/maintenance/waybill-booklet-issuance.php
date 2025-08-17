<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Waybill Booklet Issuance
	</div>

</div>
<div class="container-fluid">
    <div class="pageContent noborder">

    	<table id='waybillbookletissuancetable'>
			<tbody></tbody>

		</table>


	</div>
</div>


<div class="modal fade" id="addwaybillbookletissuancemodal">
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
											<label class='control-label'>Issuance Date*</label>
											<input type='text' class='form-input form-control issuancedate datepicker'>
										</div>	
										<div class="form-group">
											<label class='control-label'>Validity Date*</label>
											<input type='text' class='form-input form-control validitydate datepicker'>
										</div>
										<div class="form-group">
											<label class='control-label'>Issued to*</label>
											<input type='text' class='form-input form-control issuedto'>
										</div>	
										<div class="form-group">
											<label class='control-label'>Location</label>
											<select class='form-input form-control location locationdropdownselect' style='width:100%'></select>
										</div>
										<div class="form-group">
											<label class='control-label'>Courier Flag*</label>
											<select class='form-input form-control courierflag select2' style='width:100%'>
												<option value='1'>Yes</option>
												<option value='0'>No</option>
											</select>
										</div>
										<div class="form-group couriergroupwrapper">
											<label class='control-label'>Courier*</label>
											<select class='form-input form-control courier courierdropdownselect' style='width:100%'></select>
										</div>
										<div class="form-group shippergroupwrapper hidden">
											<label class='control-label'>Shipper*</label>
											<select class='form-input form-control shipper shipperdropdownselect' style='width:100%'></select>
										</div>	

								</div>
							</div>


							<div class='col-md-5'>
								<div class='col-md-12'>

										<div class="form-group">
											<label class='control-label'>Booklet Start Series*</label>
											<input type='number' class='form-input form-control startseries'>
										</div>
										<div class="form-group">
											<label class='control-label'>Booklet End Series*</label>
											<input type='number' class='form-input form-control endseries'>
										</div>
										<div class="form-group">
											<label class='control-label'>Remarks</label>
											<textarea class='form-control remarks' rows='6'></textarea>
										</div>
								</div>
							</div>

							
							
						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn waybillbookletissuancemodal-savebtn' id='addwaybillbookletissuancemodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editwaybillbookletissuancemodal">
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
							<input type='hidden' class='waybillbookletissuancemodalid'>
							<div class='col-md-7'>
								<div class='col-md-12'>

										<div class="form-group">
											<label class='control-label'>Issuance Date*</label>
											<input type='text' class='form-input form-control issuancedate datepicker'>
										</div>	
										<div class="form-group">
											<label class='control-label'>Validity Date*</label>
											<input type='text' class='form-input form-control validitydate datepicker'>
										</div>
										<div class="form-group">
											<label class='control-label'>Issued to*</label>
											<input type='text' class='form-input form-control issuedto'>
										</div>	
										<div class="form-group">
											<label class='control-label'>Location</label>
											<select class='form-input form-control location locationdropdownselect' style='width:100%'></select>
										</div>	
										<div class="form-group">
											<label class='control-label'>Courier Flag*</label>
											<select class='form-input form-control courierflag select2' style='width:100%'>
												<option value='1'>Yes</option>
												<option value='0'>No</option>
											</select>
										</div>
										<div class="form-group couriergroupwrapper">
											<label class='control-label'>Courier*</label>
											<select class='form-input form-control courier courierdropdownselect' style='width:100%'></select>
										</div>
										<div class="form-group shippergroupwrapper hidden">
											<label class='control-label'>Shipper*</label>
											<select class='form-input form-control shipper shipperdropdownselect' style='width:100%'></select>
										</div>

								</div>
							</div>


							<div class='col-md-5'>
								<div class='col-md-12'>

										<div class="form-group">
											<label class='control-label'>Booklet Start Series*</label>
											<input type='number' class='form-input form-control startseries'>
										</div>
										<div class="form-group">
											<label class='control-label'>Booklet End Series*</label>
											<input type='number' class='form-input form-control endseries'>
										</div>
										<div class="form-group">
											<label class='control-label'>Remarks</label>
											<textarea class='form-control remarks' rows='6'></textarea>
										</div>
								</div>
							</div>				
						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn waybillbookletissuancemodal-savebtn' id='editwaybillbookletissuancemodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>


<script type="text/javascript">
	$(document).ready(function(){

		var tabWYBKLTISS = '#waybillbookletissuance-menutabpane';
		$('.modal-dialog').draggable();
		$(tabWYBKLTISS+' .datepicker').datepicker();

		$(tabWYBKLTISS+' .select2').select2();

		$(tabWYBKLTISS+" .locationdropdownselect").select2({
	            ajax: {
	                    url: "Loadables/dropdown/location.php",
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

	    $(tabWYBKLTISS+" .shipperdropdownselect").select2({
	            ajax: {
	                    url: "Loadables/dropdown/shipper.php",
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

	    $(tabWYBKLTISS+" .courierdropdownselect").select2({
	            ajax: {
	                    url: "Loadables/dropdown/personnel.php?type=COURIER",
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

		

		$("#waybillbookletissuancetable").flexigrid({
				url: 'loadables/ajax/maintenance.waybill-booklet-issuance.php',
				dataType: 'json',
				colModel : [
						{display: 'Actions', name : 'action', width : 80, sortable : false, align: 'center'},
						{display: 'System ID', name : 'id', width : 80, sortable : true, align: 'left'},
						{display: 'Issuance Date', name : 'issuance_date', width : 125, sortable : true, align: 'left'},
						{display: 'Validity Date', name : 'validity_date', width : 125, sortable : true, align: 'left'},
						{display: 'Issued To', name : 'issued_to', width : 200, sortable : true, align: 'left'},
						{display: 'Location', name : 'location_id', width : 200, sortable : true, align: 'left'},
						{display: 'Courier Flag', name : 'location_id', width : 100, sortable : false, align: 'left'},
						{display: 'Shipper', name : 'shipper.account_name', width : 180, sortable : true, align: 'left'},
						{display: 'Courier', name : 'carrier.description', width : 180, sortable : true, align: 'left'},
						{display: 'Booklet Start Series', name : 'booklet_start_series', width : 150, sortable : true, align: 'left'},
						{display: 'Booklet End Series', name : 'booklet_end_series', width : 150, sortable : true, align: 'left'},
						{display: 'Remarks', name : 'remarks', width : 350, sortable : true, align: 'left'}
				],
				buttons : [
						{name: 'Add', bclass: 'add addwaybillbookletissuancebtn', onpress : addWaybillBookletIssuance},
						{separator: true},
						{name: 'Delete', bclass: 'delete deletewaybillbookletissuancebtn', onpress : deleteWaybillBookletIssuance}
				],
				searchitems : [
						{display: 'Issuance Date', name : 'issuance_date', isdefault: true},
						{display: 'Validity', name : 'validity_date'},
						{display: 'Issued To', name : 'issued_to'},
						{display: 'Location', name : 'location'},
						{display: 'Remarks', name : 'remarks'},
						{display: 'Start Series', name : 'booklet_start_series'},
						{display: 'End Series', name : 'booklet_end_series'}
				],
				sortname: "issued_to",
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

		function addWaybillBookletIssuance(){
				$('#addwaybillbookletissuancemodal').modal('show');
		}

		function deleteWaybillBookletIssuance(){

		
			if(parseInt($('#waybillbookletissuancetable .trSelected').length)>0){
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
							$('#waybillbookletissuancetable .trSelected').each(function(){
								data.push($(this).attr('rowid'));
							});
							$.post('../scripts/waybill-booklet-issuance.php',{deleteSelectedRows:'skj$oihdtpoa$I#@4noi4AIFNlskoRboIh4!j3sio$*yhs',data:data},function(response){

								if(response.trim()=='success'){
									$('#waybillbookletissuancetable').flexOptions({
											url:'loadables/ajax/maintenance.waybill-booklet-issuance.php',
											sortname: "created_date",
											sortorder: "desc"
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