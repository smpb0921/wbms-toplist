<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Driver/Helper
	</div>

</div>
<div class="container-fluid">
    <div class="pageContent noborder">

    	<table id='personneltable'>
			<tbody></tbody>

		</table>


	</div>
</div>


<div class="modal fade" id="addpersonnelmodal">
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
								<label class='control-label'>First Name*</label>
								<input type='text' class='form-input form-control firstname'>
								
							</div>	
							<div class="form-group">
								<label class='control-label'>Last Name*</label>
								<input type='text' class='form-input form-control lastname'>
								
							</div>
							<div class="form-group">
								<label class='control-label'>Contact Number*</label>
								<input type='text' class='form-input form-control contactnumber'>
								
							</div>
							<div class="form-group">
								<label class='control-label'>Position*</label>
								<select class='form-control position personnelpositiondropdownselect'>
								</select>
								
							</div>		
							<div class="form-group">
								<label class='control-label'>Driver For*</label>
								<select class='form-control type personneltypedropdownselect'>
								</select>
								
							</div>	
						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn personnelmodal-savebtn' id='addpersonnelmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editpersonnelmodal">
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
							<input type='hidden' class='personnelmodalid'>
							<div class="form-group">
								<label class='control-label'>Status*</label>
								<select class='form-input form-control status select2'>
									<option value='1'>Active</option>
									<option value='0'>Inactive</option>
								</select>
								
							</div>	
							<div class="form-group">
								<label class='control-label'>First Name*</label>
								<input type='text' class='form-input form-control firstname'>
								
							</div>	
							<div class="form-group">
								<label class='control-label'>Last Name*</label>
								<input type='text' class='form-input form-control lastname'>
								
							</div>
							<div class="form-group">
								<label class='control-label'>Contact Number*</label>
								<input type='text' class='form-input form-control contactnumber'>
								
							</div>
							<div class="form-group">
								<label class='control-label'>Position*</label>
								<select class='form-control position personnelpositiondropdownselect'>
								</select>
								
							</div>		
							<div class="form-group">
								<label class='control-label'>Driver For*</label>
								<select class='form-control type personneltypedropdownselect'>
								</select>
								
							</div>						
						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn personnelmodal-savebtn' id='editpersonnelmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>


<script type="text/javascript">
	$(document).ready(function(){
		var tabpersonnel = '#personnel-menutabpane';
		$('.modal-dialog').draggable();

		$(tabpersonnel+' .select2').select2({
			width:'100%'
		});

		$(tabpersonnel+" .personneltypedropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/vehicle-type-tagging.php",
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

	    $(tabpersonnel+" .personnelpositiondropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/personnel-position.php",
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
		

		$("#personneltable").flexigrid({
				url: 'loadables/ajax/maintenance.personnel.php',
				dataType: 'json',
				colModel : [
						{display: 'Actions', name : 'action', width : 100, sortable : false, align: 'center'},
						{display: 'System ID', name : 'id', width : 80, sortable : true, align: 'left'},
						{display: 'Status', name : 'activeflag', width : 80, sortable : true, align: 'left'},
						{display: 'First Name', name : 'first_name', width : 200, sortable : true, align: 'left'},
						{display: 'Last Name', name : 'last_name', width : 200, sortable : true, align: 'left'},
						{display: 'Contact', name : 'contact_number', width : 250, sortable : true, align: 'left'},
						{display: 'Position', name : 'position', width : 100, sortable : true, align: 'left'},
						{display: 'Driver For', name : 'type', width : 100, sortable : true, align: 'left'},
						{display: 'Created by', name : 'created_by', width : 150, sortable : true, align: 'left'},
						{display: 'Created Date', name : 'created_date', width : 150, sortable : true, align: 'left'},
						{display: 'Updated by', name : 'updated_by', width : 150, sortable : true, align: 'left'},
						{display: 'Updated Date', name : 'updated_date', width : 150, sortable : true, align: 'left'}
				],
				buttons : [
						{name: 'Add', bclass: 'add addpersonnelbtn', onpress : addpersonnel},
						{separator: true},
						{name: 'Delete', bclass: 'delete deletepersonnelbtn', onpress : deletepersonnel}
				],
				searchitems : [
						{display: 'First Name', name : 'personnel.first_name', isdefault: true},
						{display: 'Last Name', name : 'personnel.last_name'},
						{display: 'Position', name : 'position'},
						{display: 'Contact No.', name : 'contact_number'},
						{display: 'Driver For', name : 'type'}
				],
				sortname: "first_name",
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

		function addpersonnel(){
				$('#addpersonnelmodal').modal('show');
		}

		function deletepersonnel(){

		
			if(parseInt($('#personneltable .trSelected').length)>0){
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
							$('#personneltable .trSelected').each(function(){
								data.push($(this).attr('rowid'));
							});
							$.post('../scripts/personnel.php',{deleteSelectedRows:'skj$oihdtpoa$I#@4noi4AIFNlskoRboIh4!j3sio$*yhs',data:data},function(response){

								if(response.trim()=='success'){
									$('#personneltable').flexOptions({
											url:'loadables/ajax/maintenance.personnel.php',
											sortname: "first_name",
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