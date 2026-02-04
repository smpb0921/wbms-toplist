<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Movement Type
	</div>

</div>
<div class="container-fluid">
    <div class="pageContent noborder">

    	<table id='movementtypetable'>
			<tbody></tbody>

		</table>


	</div>
</div>


<div class="modal fade" id="addmovementtypemodal">
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
									<label class='control-label'>Code*</label>
									<input type='text' class='form-input form-control code'>
								
							</div>	
							<div class="form-group">
									<label class='control-label'>Description*</label>
									<input type='text' class='form-input form-control description'>
								
							</div>	
							<div class="form-group">
									<label class='control-label'>Shipment Type*</label>
									<select class='form-input form-control shipmenttype shipmenttypedropdownselect noresetfld' style='width:100%'></select>
							</div>
							<div class="form-group">
									<label class='control-label'>Source Movement</label>	
									<input type='text' class='form-input form-control sourcemovement tagsinput' data-role='tagsinput'>
								
							</div>
							
						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn movementtypemodal-savebtn' id='addmovementtypemodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editmovementtypemodal">
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
							<input type='hidden' class='movementtypemodalid'>
							<div class="form-group">
									<label class='control-label'>Code*</label>
									<input type='text' class='form-input form-control code'>
								
							</div>	
							<div class="form-group">
									<label class='control-label'>Description*</label>
									<input type='text' class='form-input form-control description'>
								
							</div>	
							<div class="form-group">
									<label class='control-label'>Shipment Type*</label>
									<select class='form-input form-control shipmenttype shipmenttypedropdownselect noresetfld' style='width:100%'></select>
							</div>
							<div class="form-group">
									<label class='control-label'>Source Movement</label>	
									<input type='text' class='form-input form-control sourcemovement tagsinput' data-role='tagsinput'>
								
							</div>			
						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn movementtypemodal-savebtn' id='editmovementtypemodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>


<script type="text/javascript">
	$(document).ready(function(){

		var tabMOVEMENTTYPE = '#movementtype-menutabpane';
		$(tabMOVEMENTTYPE+' .tagsinput').tagsinput();

		$('.modal-dialog').draggable();

		$(tabMOVEMENTTYPE+" .shipmenttypedropdownselect").select2({
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



		

		$("#movementtypetable").flexigrid({
				url: 'loadables/ajax/maintenance.movement-type.php',
				dataType: 'json',
				colModel : [
						{display: 'Actions', name : 'action', width : 100, sortable : false, align: 'center'},
						{display: 'System ID', name : 'id', width : 100, sortable : true, align: 'left'},
						{display: 'Code', name : 'code', width : 120, sortable : true, align: 'left'},
						{display: 'Description', name : 'description', width : 250, sortable : true, align: 'left'},
						{display: 'Source Movement', name : 'source_movement', width : 350, sortable : true, align: 'left'},
						{display: 'Shipment Type', name : 'shipmenttype', width : 130, sortable : true, align: 'left'},
						{display: 'Created by', name : 'created_by', width : 200, sortable : true, align: 'left'},
						{display: 'Created Date', name : 'created_date', width : 130, sortable : true, align: 'left'},
						{display: 'Updated by', name : 'updated_by', width : 200, sortable : true, align: 'left'},
						{display: 'Updated Date', name : 'updated_date', width : 130, sortable : true, align: 'left'}
				],
				buttons : [
						{name: 'Add', bclass: 'add addmovementtypebtn', onpress : addMovementType},
						{separator: true},
						{name: 'Delete', bclass: 'delete deletemovementtypebtn', onpress : deleteMovementType}
				],
				searchitems : [
						{display: 'Code', name : 'code', isdefault: true},
						{display: 'Description', name : 'description'},
						{display: 'Source Movement', name : 'source_movement'}
				],
				sortname: "description",
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

		function addMovementType(){
				$('#addmovementtypemodal').modal('show');
		}

		function deleteMovementType(){

		
			if(parseInt($('#movementtypetable .trSelected').length)>0){
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
							$('#movementtypetable .trSelected').each(function(){
								data.push($(this).attr('rowid'));
							});
							$.post('../scripts/movement-type.php',{deleteSelectedRows:'skj$oihdtpoa$I#@4noi4AIFNlskoRboIh4!j3sio$*yhs',data:data},function(response){

								if(response.trim()=='success'){
									$('#movementtypetable').flexOptions({
											url:'loadables/ajax/maintenance.movement-type.php',
											sortname: "description",
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