<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Origin and Destination Port
	</div>

</div>
<div class="container-fluid">
    <div class="pageContent noborder">

    	<table id='origindestinationporttable'>
			<tbody></tbody>

		</table>


	</div>
</div>


<div class="modal fade" id="addorigindestinationportmodal">
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
								<label class='control-label'>Zone*</label>
								<select class='form-control zone zonedropdownselect'></select>
							</div>
							<div class="form-group">
								<label class='control-label'>Country*</label>
								<select class='form-control country countriesdropdownselect'></select>
							</div>
							<div class="form-group">
								<label class='control-label'>Lead Time (In Days)*</label>
								<input type='text' class='form-input form-control leadtime'>
								
							</div>	
						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn editorigindestinationportmodal-savebtn' id='addorigindestinationportmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editorigindestinationportmodal">
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
							<input type='hidden' class='editorigindestinationportid'>
							<div class="form-group">
								<label class='control-label'>Code*</label>
								<input type='text' class='form-input form-control code'>
								
							</div>	
							<div class="form-group">
								<label class='control-label'>Description*</label>
								<input type='text' class='form-input form-control description'>
								
							</div>	
							<div class="form-group">
								<label class='control-label'>Zone*</label>
								<select class='form-control zone zonedropdownselect'></select>
							</div>
							<div class="form-group">
								<label class='control-label'>Country*</label>
								<select class='form-control country countriesdropdownselect'></select>
							</div>		
							<div class="form-group">
								<label class='control-label'>Lead Time (In Days)*</label>
								<input type='text' class='form-input form-control leadtime'>
								
							</div>	
						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn editorigindestinationportmodal-savebtn' id='editorigindestinationportmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>


<script type="text/javascript">
	$(document).ready(function(){

		$('.modal-dialog').draggable();
		tabORGDSTPORT = '#origindestinationport-menutabpane';

		$(tabORGDSTPORT+" .countriesdropdownselect").select2({
	            ajax: {
	                    url: "Loadables/dropdown/country-id.php",
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

	    $(tabORGDSTPORT+" .zonedropdownselect").select2({
	            ajax: {
	                    url: "Loadables/dropdown/zone.php",
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


		

		$("#origindestinationporttable").flexigrid({
				url: 'loadables/ajax/maintenance.origin-destination-port.php',
				dataType: 'json',
				colModel : [
						{display: 'Actions', name : 'action', width : 100, sortable : false, align: 'center'},
						{display: 'System ID', name : 'id', width : 80, sortable : true, align: 'left'},
						{display: 'Code', name : 'code', width : 150, sortable : true, align: 'left'},
						{display: 'Description', name : 'description', width : 200, sortable : true, align: 'left'},
						{display: 'Zone', name : 'zone', width : 150, sortable : true, align: 'left'},
						{display: 'Country', name : 'country_name', width : 200, sortable : true, align: 'left'},
						{display: 'Lead Time', name : 'lead_time', width : 100, sortable : true, align: 'left'},
						{display: 'Created by', name : 'created_by', width : 200, sortable : true, align: 'left'},
						{display: 'Created Date', name : 'created_date', width : 200, sortable : true, align: 'left'},
						{display: 'Updated by', name : 'updated_by', width : 200, sortable : true, align: 'left'},
						{display: 'Updated Date', name : 'updated_date', width : 200, sortable : true, align: 'left'}
				],
				buttons : [
						{name: 'Add', bclass: 'add addorigindestinationportbtn', onpress : addOriginDestinationPort},
						{separator: true},
						{name: 'Delete', bclass: 'delete deleteorigindestinationportbtn', onpress : deleteOriginDestinationPort},
						{separator: true},
						{name: 'Download', bclass: 'download downloadorigindestinationportbtn', onpress : downloadOriginDestinationPort}
				],
				searchitems : [
						{display: 'Description', name : 'description', isdefault: true},
						{display: 'Code', name : 'code'}
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

		function addOriginDestinationPort(){
				$('#addorigindestinationportmodal').modal('show');
		}

		function deleteOriginDestinationPort(){

		
			if(parseInt($('#origindestinationporttable .trSelected').length)>0){
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
							$('#origindestinationporttable .trSelected').each(function(){
								data.push($(this).attr('rowid'));
							});
							$.post('../scripts/origin-destination-port.php',{deleteSelectedRows:'skj$oihdtpoa$I#@4noi4AIFNlskoRboIh4!j3sio$*yhs',data:data},function(response){

								if(response.trim()=='success'){
									$('#origindestinationporttable').flexOptions({
											url:'loadables/ajax/maintenance.origin-destination-port.php',
											sortname: "description",
											sortorder: "asc"
									}).flexReload(); 
								}
								else{
									say("Unable to delete port. It is already used as a foreign key.");
									//alert(response);
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

		function downloadOriginDestinationPort(){
	        window.open("Printouts/excel/maintenance.origin-destination-port.php");
		}
		
		userAccess();
			

		
	});
	
</script>