<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
	<div class='header-page-inner'>
		District/City/Zip Code
	</div>

</div>
<div class="container-fluid">
    <div class="pageContent noborder">

    	<table id='districtcityziptable'>
			<tbody></tbody>

		</table>


	</div>
</div>


<div class="modal fade" id="adddistrictcityzipmodal">
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
								<label class='control-label'>District/Barangay*</label>
								<input type='text' class='form-input form-control district'>
								
							</div>	
							<div class="form-group">
								<label class='control-label'>City*</label>
								<input type='text' class='form-input form-control city'>
								
							</div>
							<div class="form-group">
								<label class='control-label'>Zip Code*</label>
								<input type='text' class='form-input form-control zip'>
								
							</div>	
							<div class="form-group">
								<label class='control-label'>Region/Province*</label>
								<select class='form-control form-input region origindestinationdropdownselect'></select>
							</div>
							<div class="form-group hidden">
								<label class='control-label'>Lead Time*</label>
								<input type='number' class='form-input form-control cityleadtime'>
								
							</div>
							<div class="form-group">
								<label class='control-label'>ODA Flag*</label>
								<select class='form-control odaflag select2'>
									<option value='0'>No</option>
									<option value='1'>Yes</option>
								</select>
								
							</div>
							<div class="form-group odaratewrapper hidden">
								<label class='control-label'>ODA Rate*</label>
								<input type='number' class='form-input form-control odarate'>
							</div>	


						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn districtcityzipmodal-savebtn' id='adddistrictcityzipmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editdistrictcityzipmodal">
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
							<input type='hidden' class='districtcityzipmodalid'>
							<div class="form-group">
								<label class='control-label'>District/Barangay*</label>
								<input type='text' class='form-input form-control district'>
								
							</div>	
							<div class="form-group">
								<label class='control-label'>City*</label>
								<input type='text' class='form-input form-control city'>
								
							</div>
							<div class="form-group">
								<label class='control-label'>Zip Code*</label>
								<input type='text' class='form-input form-control zip'>
								
							</div>	
							<div class="form-group">
								<label class='control-label'>Region/Province*</label>
								<select class='form-control form-input region origindestinationdropdownselect'></select>
							</div>	
							<div class="form-group hidden">
								<label class='control-label'>Lead Time*</label>
								<input type='number' class='form-input form-control cityleadtime'>
								
							</div>
							<div class="form-group">
								<label class='control-label'>ODA Flag*</label>
								<select class='form-control odaflag select2'>
									<option value='0'>No</option>
									<option value='1'>Yes</option>
								</select>
								
							</div>
							<div class="form-group odaratewrapper hidden">
								<label class='control-label'>ODA Rate*</label>
								<input type='number' class='form-input form-control odarate'>
							</div>			
						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn districtcityzipmodal-savebtn' id='editdistrictcityzipmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="uploaddistrictcityzipmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Upload File
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action='../scripts/districtcityzip-transaction-upload.php' method='post' id='uploaddistrictcityzipmodal-form'  enctype='multipart/form-data' target='districtcityzipuploadtransactionlogframe'>
                    <div class='col-md-4'>
                        Please make sure to follow the right format.
                        Click <a class='pointer' id='districtcityzip-downloadtransactionfiletemplatebtn' href='../file-templates/districtcityzip-transaction-template.xlsx'>here</a> to download file template.
                    </div>
                    <div class='col-md-offset-1 col-md-6'>
                        <div class="form-group">
                            <label class='control-label'>Select an Excel File</label>
                            <input type='file' class='form-control uploaddistrictcityzipmodal-file' name='uploaddistrictcityzipmodal-file'>
                        </div>
                    </div>
                </form>
                <br>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button class='btn btn-blue2 mybtn' id='uploaddistrictcityzipmodal-uploadbtn'>Upload</button>
                    <button class='btn btn-blue2 mybtn modal-cancelbtn' >Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="districtcityzip-uploadtransactionlogmodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Uploading File...
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <iframe id="districtcityzipuploadtransactionlogframe" name="districtcityzipuploadtransactionlogframe" height="600" width="100%" frameborder="0" scrolling="yes" style='background: #fff'></iframe>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
	$(document).ready(function(){
		var tabDCZR = '#districtcityzip-menutabpane';

		$('.modal-dialog').draggable();
		$(tabDCZR+" .select2").select2({
			width:'100%'
		});

		$(tabDCZR+" .origindestinationdropdownselect").select2({
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
	                minimumInputLength: 0,
	                width: '100%'
	    });

		

		$(tabDCZR+" #districtcityziptable").flexigrid({
				url: 'loadables/ajax/maintenance.district-city-zip.php',
				dataType: 'json',
				colModel : [
						{display: 'Actions', name : 'action', width : 100, sortable : false, align: 'center'},
						{display: 'System ID', name : 'id', width : 100, sortable : true, align: 'left'},
						{display: 'District/Barangay', name : 'district_barangay', width : 250, sortable : true, align: 'left'},
						{display: 'City', name : 'city', width : 200, sortable : true, align: 'left'},
						{display: 'Zip Code', name : 'zip_code', width : 100, sortable : true, align: 'left'},
						{display: 'Region/Province', name : 'region_province', width : 200, sortable : true, align: 'left'},
						{display: 'Lead Time (In Days)', name : 'lead_time', width : 100, sortable : true, align: 'left', hide:true},
						{display: 'ODA Flag', name : 'oda_flag', width : 80, sortable : true, align: 'left'},
						{display: 'ODA Rate', name : 'oda_rate', width : 80, sortable : true, align: 'left'},

						{display: 'Created by', name : 'created_by', width : 200, sortable : true, align: 'left'},
						{display: 'Created Date', name : 'created_date', width : 135, sortable : true, align: 'left'},
						{display: 'Updated by', name : 'updated_by', width : 200, sortable : true, align: 'left'},
						{display: 'Updated Date', name : 'updated_date', width : 135, sortable : true, align: 'left'}
				],
				buttons : [
						{name: 'Add', bclass: 'add', onpress : adddistrictcityzip},
						{separator: true},
						{name: 'Delete', bclass: 'delete', onpress : deletedistrictcityzip},
						{separator: true},
						{name: 'Upload', bclass: 'upload', onpress : uploaddistrictcityzip},
						{separator: true},
						{name: 'Download', bclass: 'download downloaddistrictcityzip', onpress : ''}
				],
				searchitems : [
						{display: 'District/Barangay', name : 'district_barangay', isdefault: true},
						{display: 'City', name : 'city'},
						{display: 'Zip Code', name : 'zip_code'},
						{display: 'Region/Province', name : 'region_province'}
				],
				sortname: "region_province",
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

		function adddistrictcityzip(){
				$('#adddistrictcityzipmodal').modal('show');
		}

		function uploaddistrictcityzip(){
				$('#uploaddistrictcityzipmodal').modal('show');
		}

		function deletedistrictcityzip(){

		
			if(parseInt($('#districtcityziptable .trSelected').length)>0){
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
							$('#districtcityziptable .trSelected').each(function(){
								data.push($(this).attr('rowid'));
							});
							$.post('../scripts/district-city-zip.php',{deleteSelectedRows:'skj$oihdtpoa$I#@4noi4AIFNlskoRboIh4!j3sio$*yhs',data:data},function(response){

								if(response.trim()=='success'){
									$('#districtcityziptable').flexOptions({
											url:'loadables/ajax/maintenance.district-city-zip.php',
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