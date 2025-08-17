<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Vehicle
	</div>

</div>
<div class="container-fluid">
    <div class="pageContent noborder">

    	<table id='vehicletable'>
			<tbody></tbody>

		</table>


	</div>
</div>


<div class="modal fade" id="addvehiclemodal">
	<div class="modal-dialog modal-sm">
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
							<div class="form-group">
								<label class='control-label'>Vehicle Type*</label>
								<select class='form-control vehicletype vehicletypedropdownselect'>
								</select>
								
							</div>	
							<div class="form-group">
								<label class='control-label'>Plate Number*</label>
								<input type='text' class='form-input form-control platenumber'>
								
							</div>	
							<div class="form-group">
								<label class='control-label'>Model*</label>
								<input type='text' class='form-input form-control model'>
								
							</div>
							<div class="form-group">
								<label class='control-label'>Year</label>
								<input type='text' class='form-input form-control year'>
								
							</div>	
							
						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn vehiclemodal-savebtn' id='addvehiclemodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editvehiclemodal">
	<div class="modal-dialog modal-sm">
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
							<input type='hidden' class='vehiclemodalid'>
							<div class="form-group">
								<label class='control-label'>Status*</label>
								<select class='form-input form-control status select2'>
									<option value='1'>Active</option>
									<option value='0'>Inactive</option>
								</select>
								
							</div>	
							<div class="form-group">
								<label class='control-label'>Vehicle Type*</label>
								<select class='form-control vehicletype vehicletypedropdownselect'>
								</select>
								
							</div>	
							<div class="form-group">
								<label class='control-label'>Plate Number*</label>
								<input type='text' class='form-input form-control platenumber'>
								
							</div>	
							<div class="form-group">
								<label class='control-label'>Model*</label>
								<input type='text' class='form-input form-control model'>
								
							</div>
							<div class="form-group">
								<label class='control-label'>Year</label>
								<input type='text' class='form-input form-control year'>
								
							</div>							
						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn vehiclemodal-savebtn' id='editvehiclemodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>


<script type="text/javascript">
	$(document).ready(function(){
		var tabvehicle = '#vehicle-menutabpane';
		$('.modal-dialog').draggable();

		$(tabvehicle+' .select2').select2({
			width:'100%'
		});

		$(tabvehicle+" .vehicletypedropdownselect").select2({
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

	    

		$("#vehicletable").flexigrid({
				url: 'loadables/ajax/maintenance.vehicle.php',
				dataType: 'json',
				colModel : [
						{display: 'Actions', name : 'action', width : 100, sortable : false, align: 'center'},
						{display: 'System ID', name : 'id', width : 80, sortable : true, align: 'left'},
						{display: 'Status', name : 'activeflag', width : 80, sortable : true, align: 'left'},
						{display: 'Plate Number', name : 'plate_number', width : 150, sortable : true, align: 'left'},
						{display: 'Model', name : 'model', width : 200, sortable : true, align: 'left'},
						{display: 'Year', name : 'year', width : 80, sortable : true, align: 'left'},
						{display: 'Vehicle Type', name : 'vehicletype', width : 250, sortable : true, align: 'left'},
						{display: 'Created by', name : 'created_by', width : 150, sortable : true, align: 'left'},
						{display: 'Created Date', name : 'created_date', width : 150, sortable : true, align: 'left'},
						{display: 'Updated by', name : 'updated_by', width : 150, sortable : true, align: 'left'},
						{display: 'Updated Date', name : 'updated_date', width : 150, sortable : true, align: 'left'}
				],
				buttons : [
						{name: 'Add', bclass: 'add addvehiclebtn', onpress : addvehicle},
						{separator: true},
						{name: 'Delete', bclass: 'delete deletevehiclebtn', onpress : deletevehicle}
				],
				searchitems : [
						{display: 'Plate Number', name : 'plate_number', isdefault: true},
						{display: 'Model', name : 'model'},
						{display: 'Year', name : 'year'},
						{display: 'Vehicle Type', name : 'vehicletype'}
				],
				sortname: "plate_number",
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

		function addvehicle(){
				$('#addvehiclemodal').modal('show');
		}

		function deletevehicle(){

		
			if(parseInt($('#vehicletable .trSelected').length)>0){
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
							$('#vehicletable .trSelected').each(function(){
								data.push($(this).attr('rowid'));
							});
							$.post('../scripts/vehicle.php',{deleteSelectedRows:'skj$oihdtpoa$I#@4noi4AIFNlskoRboIh4!j3sio$*yhs',data:data},function(response){

								if(response.trim()=='success'){
									$('#vehicletable').flexOptions({
											url:'loadables/ajax/maintenance.vehicle.php',
											sortname: "plate_number",
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