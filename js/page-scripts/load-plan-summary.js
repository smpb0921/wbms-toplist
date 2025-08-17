var loadplanreportpane = "#loadplanreport-menutabpane";

$(document).off('click',loadplanreportpane+' #loadplansummary-trans-downloadbtn:not(".disabled")').on('click',loadplanreportpane+' #loadplansummary-trans-downloadbtn:not(".disabled")',function(){

	var status = $(loadplanreportpane+' .loadplansummary-status').val(),
	    origin = $(loadplanreportpane+' .loadplansummary-origin').val(),
	    destination = $(loadplanreportpane+' .loadplansummary-destination').val(),
	    modeoftransport = $(loadplanreportpane+' .loadplansummary-modeoftransport').val(),
	    vehicletype = $(loadplanreportpane+' .loadplansummary-vehicletype').val(),
	    agent = $(loadplanreportpane+' .loadplansummary-agent').val(),
	    manifestnumber = $(loadplanreportpane+' .loadplansummary-manifestnumber').val(),
	    mawbblnumber = $(loadplanreportpane+' .loadplansummary-mawbblnumber').val(),
	    location = $(loadplanreportpane+' .loadplansummary-location').val(),
	    carrier = $(loadplanreportpane+' .loadplansummary-carrier').val(),
	    docdatefrom = $(loadplanreportpane+' .loadplansummary-docdatefrom').val(),
	    docdateto = $(loadplanreportpane+' .loadplansummary-docdateto').val(),
	    etdfrom = $(loadplanreportpane+' .loadplansummary-etdfrom').val(),
	    etdto = $(loadplanreportpane+' .loadplansummary-etdto').val(),
	    etafrom = $(loadplanreportpane+' .loadplansummary-etafrom').val(),
	    etato = $(loadplanreportpane+' .loadplansummary-etato').val(),
	    waybill = $(loadplanreportpane+' .loadplansummary-waybill').val(),
		button = $(this);

		button.addClass('disabled').removeClass('active');


		var w = window.open("printouts/excel/reports.load-plan-summary.php?status="+status+"&origin="+origin+"&destination="+"&modeoftransport="+modeoftransport+"&vehicletype="+vehicletype+"&agent="+agent+"&manifestnumber="+manifestnumber+"&mawbblnumber="+mawbblnumber+"&docdatefrom="+docdatefrom+"&docdateto="+docdateto+"&etdfrom="+etdfrom+"&etdto="+etdto+"&etafrom="+etafrom+"&etato="+etato+"&location="+location+"&carrier="+carrier+"&waybill="+waybill);
							

		w.onbeforeunload = function(){
								button.removeClass('disabled').addClass('active');
						   }				
		

		
		

});

