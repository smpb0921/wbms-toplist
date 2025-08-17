<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Final Waybill Status
	</div>

</div>
<div class="container-fluid">
    <div class="pageContent noborder">

    	<table id='finalstatustable'>
			<tbody></tbody>

		</table>


	</div>
</div>


<div class="modal fade" id="addfinalstatusmodal">
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
								<label class='control-label'>Status*</label>
								<input type='text' class='form-input form-control status'>
								
							</div>		
						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn finalstatusmodal-savebtn' id='addfinalstatusmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editfinalstatusmodal">
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
							<input type='hidden' class='finalstatusmodalid'>
							<div class="form-group">
								<label class='control-label'>Status*</label>
								<input type='text' class='form-input form-control status'>
								
							</div>				
						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn finalstatusmodal-savebtn' id='editfinalstatusmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>



<script type="text/javascript">
	$(document).ready(function(){

		var tabfinalstatus = '#finalstatus-menutabpane';
		$(tabfinalstatus+' .modal-dialog').draggable();

		

		

		$(tabfinalstatus+" #finalstatustable").flexigrid({
				url: 'loadables/ajax/system.final-status.php',
				dataType: 'json',
				colModel : [
						{display: 'Actions', name : 'action', width : 100, sortable : false, align: 'center'},
						{display: 'System ID', name : 'id', width : 100, sortable : true, align: 'left'},
						{display: 'Status', name : 'status', width : 250, sortable : true, align: 'left'},
						{display: 'Created by', name : 'created_by', width : 200, sortable : true, align: 'left'},
						{display: 'Created Date', name : 'created_date', width : 200, sortable : true, align: 'left'},
						{display: 'Updated by', name : 'updated_by', width : 200, sortable : true, align: 'left'},
						{display: 'Updated Date', name : 'updated_date', width : 200, sortable : true, align: 'left'}
				],
				buttons : [
						{name: 'Add', bclass: 'add addfinalstatusbtn', onpress : addfinalstatus},
						{separator: true},
						{name: 'Delete', bclass: 'delete deletefinalstatusbtn', onpress : deletefinalstatus}
				],
				searchitems : [
						{display: 'Status', name : 'status', isdefault: true}
				],
				sortname: "status",
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

		function addfinalstatus(){
				$('#addfinalstatusmodal').modal('show');
		}

		function deletefinalstatus(){

		
			if(parseInt($('#finalstatustable .trSelected').length)>0){
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
							$('#finalstatustable .trSelected').each(function(){
								data.push($(this).attr('rowid'));
							});
							$.post('../scripts/final-status.php',{deleteSelectedRows:'skj$oihdtpoa$I#@4noi4AIFNlskoRboIh4!j3sio$*yhs',data:data},function(response){

								if(response.trim()=='success'){
									$('#finalstatustable').flexOptions({
											url:'loadables/ajax/system.final-status.php',
											sortname: "status",
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