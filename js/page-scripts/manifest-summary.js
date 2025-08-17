var manifestreportpane = "#manifestreport-menutabpane";

$(document).off('click',manifestreportpane+' #manifestsummary-trans-downloadbtn:not(".disabled")').on('click',manifestreportpane+' #manifestsummary-trans-downloadbtn:not(".disabled")',function(){

	var mftstatus = $(manifestreportpane+' .manifestsummary-mftstatus').val(),
	    mftmodeoftransport = $(manifestreportpane+' .manifestsummary-mftmodeoftransport').val(),
	    mftagent = $(manifestreportpane+' .manifestsummary-mftagent').val(),
	    mftcarrier = $(manifestreportpane+' .manifestsummary-mftcarrier').val(),
	    mftvehicletype = $(manifestreportpane+' .manifestsummary-mftvehicletype').val(),
	    mftdocdatefrom = $(manifestreportpane+' .manifestsummary-mftdocdatefrom').val(),
	    mftdocdateto = $(manifestreportpane+' .manifestsummary-mftdocdateto').val(),
	    mftcreatedfrom = $(manifestreportpane+' .manifestsummary-mftcreatedfrom').val(),
	    mftcreatedto = $(manifestreportpane+' .manifestsummary-mftcreatedto').val(),
	    /*wbstatus = $(manifestreportpane+' .manifestsummary-wbstatus').val(),
	    wbmawbl = $(manifestreportpane+' .manifestsummary-wbmawbl').val(),
	    wborigin = $(manifestreportpane+' .manifestsummary-wborigin').val(),
	    wbdestination = $(manifestreportpane+' .manifestsummary-wbdestination').val(),
	    wbshipper = $(manifestreportpane+' .manifestsummary-wbshipper').val(),
	    wbconsignee = $(manifestreportpane+' .manifestsummary-wbconsignee').val(),
	    wbdocdatefrom = $(manifestreportpane+' .manifestsummary-wbdocdatefrom').val(),
	    wbdocdateto = $(manifestreportpane+' .manifestsummary-wbdocdateto').val(),
	    wbpudatefrom = $(manifestreportpane+' .manifestsummary-wbpudatefrom').val(),
	    wbpudateto = $(manifestreportpane+' .manifestsummary-wbpudateto').val(),
	    wbcreateddatefrom = $(manifestreportpane+' .manifestsummary-wbcreateddatefrom').val(),
	    wbcreateddateto = $(manifestreportpane+' .manifestsummary-wbcreateddateto').val(),*/
		button = $(this);

		button.addClass('disabled').removeClass('active');


		var w = window.open("printouts/excel/reports.manifest-summary.php?mftstatus="+mftstatus+"&mftmodeoftransport="+mftmodeoftransport+"&mftagent="+mftagent+"&mftcarrier="+mftcarrier+"&mftvehicletype="+mftvehicletype+"&mftdocdatefrom="+mftdocdatefrom+"&mftdocdateto="+mftdocdateto+"&mftcreatedfrom="+mftcreatedfrom+"&mftcreatedto="+mftcreatedto);

		//"printouts/excel/reports.manifest-summary.php?mftstatus="+mftstatus+"&mftmodeoftransport="+mftmodeoftransport+"&mftagent="+mftagent+"&mftcarrier="+mftcarrier+"&mftvehicletype="+mftvehicletype+"&mftdocdatefrom="+mftdocdatefrom+"&mftdocdateto="+mftdocdateto+"&mftcreatedfrom="+mftcreatedfrom+"&mftcreatedto="+mftcreatedto+"&wbstatus="+wbstatus+"&wbmawbl="+wbmawbl+"&wborigin="+wborigin+"&wbdestination="+wbdestination+"&wbshipper="+wbshipper+"&wbconsignee="+wbconsignee+"&wbdocdatefrom="+wbdocdatefrom+"&wbdocdateto="+wbdocdateto+"&wbpudatefrom="+wbpudatefrom+"&wbpudateto="+wbpudateto+"&wbcreateddatefrom="+wbcreateddatefrom+"&wbcreateddateto="+wbcreateddateto
							

		w.onbeforeunload = function(){
								button.removeClass('disabled').addClass('active');
						   }				
		

		
		

});

