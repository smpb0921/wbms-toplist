var contentORDESPORT = '#origindestinationport-menutabpane';

$(document).off('show.bs.modal','#addorigindestinationportmodal').on('show.bs.modal','#addorigindestinationportmodal',function(){
	$(contentORDESPORT+" #addorigindestinationportmodal .country").empty().append('<option selected value="174">Philippines</option>').trigger('change');
});


$(document).off('click','.editorigindestinationportbtn').on('click','.editorigindestinationportbtn',function(){
	var modal = "#editorigindestinationportmodal";
	$(modal+' .code').val($(this).attr('code'));
	$(modal+' .description').val($(this).attr('desc'));
	$(modal+' .leadtime').val($(this).attr('leadtime'));
	$(modal+' .editorigindestinationportid').val($(this).attr('rowid'));

	if($(this).attr('countryname')!=null){
		$(modal+" .country").empty().append('<option selected value="'+$(this).attr('countryid')+'">'+$(this).attr('countryname')+'</option>').trigger('change');
	}
	else{
		$(modal+" .country").empty().trigger('change');
	}

	if($(this).attr('zone')!=null){
		$(modal+" .zone").empty().append('<option selected value="'+$(this).attr('zoneid')+'">'+$(this).attr('zone')+'</option>').trigger('change');
	}
	else{
		$(modal+" .zone").empty().trigger('change');
	}
});

/***************************** SAVE | EDIT **************************************************/
$(document).off('click','.editorigindestinationportmodal-savebtn:not(".disabled")').on('click','.editorigindestinationportmodal-savebtn:not(".disabled")',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		code = $(modal+' .code').val(),
		desc = $(modal+' .description').val(),
		country = $(modal+' .country').val(),
		zone = $(modal+' .zone').val(),
		leadtime = $(modal+' .leadtime').val(),
		id='',
		newsort = 'created_date',
		source='add',
		button=$(this);
		button.addClass('disabled').attr('disabled','disabled');

	if(modal=='#editorigindestinationportmodal'){
		id = $(modal+' .editorigindestinationportid').val();
		source = 'edit';
		newsort = 'updated_date';
	}

	if(code==''){
		$(modal+' .code').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a code.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(desc==''){
		$(modal+' .description').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a description.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(zone==''||zone=='NULL'||zone=='null'||zone==null){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a zone.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(country==''||country=='NULL'||country=='null'||country==null){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a country.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(parseFloat(leadtime)<1||leadtime.trim()==''){
		$(modal+' .leadtime').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide Lead Time (In Days).</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		$.post(server+'origin-destination-port.php',{OriginDestinationPortSaveEdit:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',source:source,id:id,code:code,desc:desc,country:country,zone:zone,leadtime:leadtime},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						
						$('#origindestinationporttable').flexOptions({
											url:'loadables/ajax/maintenance.origin-destination-port.php',
											sortname: newsort,
											sortorder: "desc"
						}).flexReload(); 
						$('#loading-img').addClass('hidden');
						$(modal).off('hidden.bs.modal');
						button.removeAttr('disabled').removeClass('disabled');
						$(modal+' .code').val('');
						$(modal+' .description').val('');


					});
				}
				else if(data.trim()=='codeexists'){
					$('#loading-img').addClass('hidden');
					$(modal+' .code').focus();
					$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Code exists. Please provide another code.</div></div>");
					button.removeAttr('disabled').removeClass('disabled');
				}
				else{
					$('#loading-img').addClass('hidden');
					alert(data);
					button.removeAttr('disabled').removeClass('disabled');
				}
		});	
	}
});

/******************************* DELETE *****************************************************/



/*$(document).off('click','#origindestinationporttable tr').on('click','#origindestinationporttable tr',function(e){
	e.preventDefault();
	var rowid = $(this).attr('rowid');
	var table = 'origin_destination_port';
	var column = 'id';
	var row = $(this);



	$.post(configfldr+'post-functions.php',{CheckIfDeletionAllowed:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',table:table,column:column,val:rowid},function(data){

			if(data.trim()=='true'){

			}
			else if(data.trim()=='false'){
				row.removeClass('trSelected');
			}
			else{
				alert(data);
			}

	});
});*/