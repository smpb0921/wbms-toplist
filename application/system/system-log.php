<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
	<div class='header-page-inner'>
		System Log
	</div>
</div>
<div class="container-fluid">
    <div class="pageContent noborder">
	    			<table id='systemlogtable'>
	    				
	    				<tbody>
	    					
	    				</tbody>
	    			</table>
	    			
    			

	</div>
</div>



<script type="text/javascript">
	$(document).ready(function(){
			$('.modal-dialog').draggable();
			userAccess();
			////////////////// DATATABLES //////////////////////////////
			$("#systemlogtable").flexigrid({
				url: 'loadables/ajax/system.system-log.php',
				dataType: 'json',
				colModel : [
						{display: 'System ID', name : 'system_log.id', width : 100, sortable : true, align: 'left'},
						{display: 'Module', name : 'system_log.module', width : 250, sortable : true, align: 'left'},
						{display: 'Description', name : 'system_log.description', width : 250, sortable : true, align: 'left'},
						{display: 'Query', name : 'system_log.query', width : 450, sortable : false, align: 'left'},
						{display: 'Created by', name : 'createdby', width : 250, sortable : true, align: 'left'},
						{display: 'Created Date', name : 'system_log.date_source', width : 250, sortable : true, align: 'left'}

				],
				buttons : [
						{name: 'Download', bclass: 'download', onpress : downloadSystemLog},
						{separator: true}
						/*{name: 'Delete', bclass: 'delete', onpress : deleteUserGroup}*/
				],
				searchitems : [
						{display: 'Module', name : 'system_log.module', isdefault: true},
						{display: 'Description', name : 'system_log.description'},
						{display: 'Query', name : 'system_log.query'}
				],
				sortname: "system_log.id",
				sortorder: "desc",
				usepager: true,
				title: "",
				useRp: true,
				rp: 15, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 500,
				singleSelect: false,
				disableSelect: true
		});

		function downloadSystemLog(){
			var downloadlog = window.open("Printouts/excel/download-system-log.php");
		}
			
	});	
		
</script>