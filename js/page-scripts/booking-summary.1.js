var bookingreportpane = "#bookingreport-menutabpane";

$(document).off('click',bookingreportpane+' #bookingsummary-trans-downloadbtn:not(".disabled")').on('click',bookingreportpane+' #bookingsummary-trans-downloadbtn:not(".disabled")',function(){

	var status = $(bookingreportpane+' .bookingsummary-status').val(),
	    origin = $(bookingreportpane+' .bookingsummary-origin').val(),
	    destination = $(bookingreportpane+' .bookingsummary-destination').val(),
	    shipper = $(bookingreportpane+' .bookingsummary-shipper').val(),
	    //consignee = $(bookingreportpane+' .bookingsummary-consignee').val(),
	    city = $(bookingreportpane+' .bookingsummary-city').val(),
	    region = $(bookingreportpane+' .bookingsummary-region').val(),
	    service = $(bookingreportpane+' .bookingsummary-service').val(),
	    modeoftransport = $(bookingreportpane+' .bookingsummary-modeoftransport').val(),
	    handlinginstruction = $(bookingreportpane+' .bookingsummary-handlinginstruction').val(),
	    paymode = $(bookingreportpane+' .bookingsummary-paymode').val(),
	    pickupdatefrom = $(bookingreportpane+' .bookingsummary-pickupdatefrom').val(),
	    pickupdateto = $(bookingreportpane+' .bookingsummary-pickupdateto').val(),
	    format = $(bookingreportpane+' .bookingsummary-format').val(),
		//itemname = $(bookingreportpane+' .issuancereport-itemname').val().replace(/\s/g,'%20'),
		button = $(this);

		button.addClass('disabled').removeClass('active');


		var w = window.open("printouts/excel/reports.booking-summary.php?status="+status+"&origin="+origin+"&destination="+destination+"&shipper="+shipper+"&city="+city+"&region="+region+"&service="+service+"&modeoftransport="+modeoftransport+"&handlinginstruction="+handlinginstruction+"&paymode="+paymode+"&pickupdatefrom="+pickupdatefrom+"&pickupdateto="+pickupdateto+"&format="+format);
							

		w.onbeforeunload = function(){
								button.removeClass('disabled').addClass('active');
						   }				
		

		
		

});

