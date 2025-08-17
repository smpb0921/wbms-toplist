<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Users
	</div>

</div>
<div class="container-fluid">
    <div class="pageContent">

    	<table id='usertable'>
			<tbody></tbody>

		</table>

	</div>
</div>


<div class="modal fade" id="addusermodal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					New User
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-xs-12">
				
									<div class='tabpane-white margin-top-20 margin-bottom-10'>
										<ul class="nav nav-tabs">
										  <li role="presentation" class="active" data-pane='#userinfo'><a href="#">User Information</a></li>
										  <li role="presentation" data-pane='#useraccess'><a href="#">User Permission</a></li>
										</ul>
										<div class='tab-panes'>
											<div class='pane active' id='userinfo'>
												<div class="pane-content" style='height: 400px'>

													<div class="col-md-4">
														<div class='modal-body-info'>
																	<span class='title'>User</span><br>
																	To add user, please provide the following information.
														</div>
													</div>

													<div class="col-md-8">
														<form class="form-horizontal">
															<div class="form-group">
																<div class="errordiv"></div>
															</div>
															
															<div class="form-group">
																<label class='control-label col-md-4'>First Name*</label>
																<div class="col-md-8">
																	<input type='text' class='form-input form-control requiredinput capitalize user-firstname' >
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-4'>Middle Name</label>
																<div class="col-md-8">
																	<input type='text' class='form-input form-control capitalize user-middlename' >
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-4'>Last Name*</label>
																<div class="col-md-8">
																	<input type='text' class='form-input form-control requiredinput capitalize user-lastname' >
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-4'>Username*</label>
																<div class="col-md-8">
																	<input type='hidden' id='checkifusername'>
																	<input type='text' name='status' class='form-input form-control requiredinput lowercase user-username'>
																</div>
															</div>
															
															<div class="form-group">
																<label class='control-label col-md-4'>Email Address*</label>
																<div class="col-md-8">
																	<input type='email' name='sec-dept-div' class='form-input form-control lowercase user-email'>
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-4'>Mobile</label>
																<div class="col-md-8">
																	<input type='email' name='sec-dept-div' class='form-input form-control lowercase user-mobile'>
																</div>
															</div>
															
															<div class="form-group">
																<label class='control-label col-md-4'>Default Location*</label>
																<div class="col-md-8">
																	<select class='form-control user-location locationdropdownselect'>
																			
																	</select>
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-4'>Other Location(s)</label>
																<div class="col-md-8">
																	<select  class='form-input locationdropdownselect form-control user-otherlocation' multiple style='width:100%'>
																			
																			
																	</select>
																</div>
															</div>

															<div class="form-group">
																<label class='control-label col-md-4'>User Group*</label>
																<div class="col-md-8">
																	<select class='form-control user-usergroup usergroupdropdownselect'>
																			
																	</select>
																</div>
															</div>

															<div class="form-group">
																<label class='control-label col-md-4'>Courier Supervisor Flag*</label>
																<div class="col-md-8">
																	<select class='form-control user-couriersupervisorflag select2'>
																			<option value='0'>NO</option>
																			<option value='1'>YES</option>
																	</select>
																</div>
															</div>

															<div class="form-group">
																<label class='control-label col-md-4'>Freight Supervisor Flag*</label>
																<div class="col-md-8">
																	<select class='form-control user-freightsupervisorflag select2'>
																			<option value='0'>NO</option>
																			<option value='1'>YES</option>
																	</select>
																</div>
															</div>

														</form>

													</div>

													
												</div>
											</div>
											<div class='pane' id='useraccess'>
												<div class="pane-content" style='height: 400px;  overflow: auto; background-color:#fff'>
												

													<div class="useraccesssection" style='padding:10px'>
														<?php include('../loadables/ajax/list-of-permissions.php'); ?>
													</div>
													
      
												</div>
											</div>
										</div>
									</div>
							
				</div>

			</div>
			<div class="modal-footer">
								<div class="text-center">
									<button class='btn btn-blue2 mybtn user-modal-savebtn' id='usercreate-savebtn'>Save</button>
									<button class='btn btn-blue2 mybtn modal-cancelbtn' id='usercreate-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editusermodal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Edit User
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="col-xs-12">
				
									<div class='tabpane-white margin-top-20 margin-bottom-10'>
										<ul class="nav nav-tabs">
										  <li role="presentation" class="active" data-pane='#userinfo-edit'><a href="#">User Information</a></li>
										  <li role="presentation" data-pane='#useraccess-edit'><a href="#">User Permission</a></li>
										</ul>
										<div class='tab-panes'>
											<div class='pane active' id='userinfo-edit'>
												<div class="pane-content" style='height: 400px'>
													<div class="col-md-4">
														<div class='modal-body-info'>
																	<span class='title'>User</span><br>
																	To add user, please provide the following information.
														</div>
													</div>

													<div class="col-md-8">
														<form class="form-horizontal">
															<div class="form-group">
																<div class="errordiv"></div>
															</div>
															<input type='hidden' id='user-userid'>
															<div class="form-group">
																<label class='control-label col-md-4'>Active Flag</label>
																<div class="col-md-8">
																	<select class='form-control user-activeflag select2'>
																		<option value='1'>Active</option>
																		<option value='0'>Inactive</option>
																	</select>
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-4'>First Name*</label>
																<div class="col-md-8">
																	<input type='text' class='form-input form-control requiredinput capitalize user-firstname' >
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-4'>Middle Name</label>
																<div class="col-md-8">
																	<input type='text' class='form-input form-control capitalize user-middlename' >
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-4'>Last Name*</label>
																<div class="col-md-8">
																	<input type='text' class='form-input form-control requiredinput capitalize user-lastname' >
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-4'>Username*</label>
																<div class="col-md-8">
																	<input type='hidden' id='checkifusername'>
																	<input type='text' name='status' class='form-input form-control requiredinput lowercase user-username'>
																</div>
															</div>
															
															<div class="form-group">
																<label class='control-label col-md-4'>Email Address*</label>
																<div class="col-md-8">
																	<input type='email' name='sec-dept-div' class='form-input form-control lowercase user-email'>
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-4'>Mobile</label>
																<div class="col-md-8">
																	<input type='email' name='sec-dept-div' class='form-input form-control lowercase user-mobile'>
																</div>
															</div>
															
															<div class="form-group">
																<label class='control-label col-md-4'>Default Location*</label>
																<div class="col-md-8">
																	<select class='form-control user-location locationdropdownselect'>
																			
																	</select>
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-4'>Other Location(s)</label>
																<div class="col-md-8">
																	<select  class='form-input locationdropdownselect form-control user-otherlocation' multiple style='width:100%'>
																			
																			
																	</select>
																</div>
															</div>
															<div class="form-group">
																<label class='control-label col-md-4'>User Group*</label>
																<div class="col-md-8">
																	<select class='form-control user-usergroup usergroupdropdownselect'>
																			
																	</select>
																</div>
															</div>

															<div class="form-group">
																<label class='control-label col-md-4'>Courier Supervisor Flag*</label>
																<div class="col-md-8">
																	<select class='form-control user-couriersupervisorflag select2'>
																			<option value='0'>NO</option>
																			<option value='1'>YES</option>
																	</select>
																</div>
															</div>

															<div class="form-group">
																<label class='control-label col-md-4'>Freight Supervisor Flag*</label>
																<div class="col-md-8">
																	<select class='form-control user-freightsupervisorflag select2'>
																			<option value='0'>NO</option>
																			<option value='1'>YES</option>
																	</select>
																</div>
															</div>
														</form>
														<br>
													</div>

													
												</div>
											</div>
											<div class='pane' id='useraccess-edit'>
												<div class="pane-content" style='height: 400px;  overflow: auto; background-color:#fff'>
											

													<div class="useraccesssection" style='padding:10px'>
														<?php include('../loadables/ajax/list-of-permissions.php'); ?>
													</div>
													
      
												</div>
											</div>
										</div>
									</div>
							
				</div>

			</div>
			<div class="modal-footer">
								<div class="text-center">
									<button class='btn btn-blue2 mybtn user-modal-savebtn' id='useredit-savebtn'>Save</button>
									<button class='btn btn-blue2 mybtn modal-cancelbtn' id='useredit-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>


<script type="text/javascript">
	$(document).ready(function(){

			var tabUSER = "#user-menutabpane";
			$('.modal-dialog').draggable();
			userAccess();

			$(tabUSER+' .select2').select2();

			$(tabUSER+' .useraccesssection').jstree({
				"checkbox" : {
			      //"keep_selected_style" : false,
			      //"two_state": true,
			      "cascade":'up'
			    },
				"plugins" : [ "checkbox" ]
			});


	 		$(tabUSER+" .locationdropdownselect").select2({
		            ajax: {
		                    url: "loadables/dropdown/location.php",
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

		    $(tabUSER+" .usergroupdropdownselect").select2({
		            ajax: {
		                    url: "loadables/dropdown/user-group.php",
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

			
			

			

	        $(tabUSER+" #usertable").flexigrid({
				url: 'loadables/ajax/system.user.php',
				dataType: 'json',
				colModel : [
						{display: 'Actions', name : 'action', width : 100, sortable : false, align: 'center'},
						{display: 'System ID', name : 'id', width : 80, sortable : true, align: 'left'},
						{display: 'First Name', name : 'first_name', width : 200, sortable : true, align: 'left'},
						{display: 'Middle Name', name : 'middle_name', width : 200, sortable : true, hide:true,align: 'left'},
						{display: 'Last Name', name : 'last_name', width : 200, sortable : true, align: 'left'},
						{display: 'Username', name : 'username', width : 200, sortable : true, align: 'left'},
						{display: 'Location', name : 'location', width : 200, sortable : true, align: 'left'},
						{display: 'User Group', name : 'usergroup', width : 200, sortable : true, align: 'left'},
						{display: 'Courier Supervisor ', name : 'couriersupervisor', width : 150, sortable : true, align: 'left'},
						{display: 'Freight Supervisor', name : 'freightsupervisor', width : 150, sortable : true, align: 'left'},
						{display: 'Mobile', name : 'mobile', width : 150, sortable : true, align: 'left'},
						{display: 'Created by', name : 'created_by', width : 140, sortable : true, align: 'left'},
						{display: 'Created Date', name : 'created_date', width : 140, sortable : true, align: 'left'},
						{display: 'Updated by', name : 'updated_by', width : 140, sortable : true, align: 'left'},
						{display: 'Updated Date', name : 'updated_date', width : 140, sortable : true, align: 'left'}
				],
				buttons : [
						{name: 'Add', bclass: 'add adduserbtn', onpress : addUser},
						{separator: true},
						{name: 'Delete', bclass: 'delete deleteuserbtn', onpress : deleteUser}
				],
				searchitems : [
						{display: 'First Name', name : 'first_name', isdefault: true},
						{display: 'Last Name', name : 'last_name'},
						{display: 'Location', name : 'location'}
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

			function addUser(){
				$(tabUSER+' #addusermodal').modal('show');
			}

			function deleteUser(){

			
				/*if(parseInt($('#servicestable .trSelected').length)>0){
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
								$('#servicestable .trSelected').each(function(){
									data.push($(this).attr('rowid'));
								});
								$.post('../scripts/services.php',{deleteSelectedRows:'skj$oihdtpoa$I#@4noi4AIFNlskoRboIh4!j3sio$*yhs',data:data},function(response){

									if(response.trim()=='success'){
										$('#servicestable').flexOptions({
												url:'loadables/ajax/maintenance.services.php',
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
				}*/

					//$("#mytable").flexAddData(eval(array));
					//$('#mytable').flexOptions({url:'staff.php?name=user%200'}).flexReload(); 
					
			}
		
		
	});
	
</script>