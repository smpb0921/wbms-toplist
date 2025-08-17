


var couriertransmittalpane = "#courierdeliverytransmittal-menutabpane";

$(document).off('click',couriertransmittalpane+' #couriertransmittal-trans-downloadbtn:not(".disabled")').on('click',couriertransmittalpane+' #couriertransmittal-trans-downloadbtn:not(".disabled")',function(){

	var courier = $(couriertransmittalpane+' .couriertransmittal-courier').val();
	var destination = $(couriertransmittalpane+' .couriertransmittal-destination').val();
	var	datefrom = $(couriertransmittalpane+' .couriertransmittal-datefrom').val();
	var	dateto = $(couriertransmittalpane+' .couriertransmittal-dateto').val();
	var	button = $(this);

		button.addClass('disabled');


	/*if(courier==''||courier==null||courier=='NULL'||courier=='null'){
		button.removeClass('disabled');
		say("Please select courier");
	}*/
	/*else if(destination==''||destination==null||destination=='NULL'||destination=='null'){
		button.removeClass('disabled');
		say("Please select destination");
	}*/
	//else{
		var w = window.open("printouts/excel/reports.courier-destination-transmittal.php?courier="+courier+"&destination="+destination+"&datefrom="+datefrom+"&dateto="+dateto);
						
		w.onbeforeunload = function(){
				button.removeClass('disabled');
		}	
	//}
		

			
		

		
		

});

