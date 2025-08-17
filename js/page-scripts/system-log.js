$(document).off('click','#downloadsystemlogbtn:not(".disabled")').on('click','#downloadsystemlogbtn:not(".disabled")',function(){
	var button = $(this);
	
	var downloadlog = window.open("Printouts/excel/download-system-log.php");

	
});