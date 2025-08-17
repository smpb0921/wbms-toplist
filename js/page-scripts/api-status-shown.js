$(document).off('click','.editwebapistatusshownbtn').on('click','.editwebapistatusshownbtn',function(){
	var modal = "#editapistatusshownmodal";
	$(modal+' .description').val($(this).attr('desc'));
	$(modal+' .apistatusshownmodalid').val($(this).attr('rowid'));
});

$(document).off('shown.bs.modal','#addapistatusshownmodal').on('shown.bs.modal','#addapistatusshownmodal',function(){
	$(this).find(' .description').focus();
});

$(document).off('shown.bs.modal','#editapistatusshownmodal').on('shown.bs.modal','#editapistatusshownmodal',function(){
	$(this).find(' .description').focus();
});

/***************************** SAVE | EDIT **************************************************/
$(document).off('click','.apistatusshownmodal-savebtn:not(".disabled")').on('click','.apistatusshownmodal-savebtn:not(".disabled")',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		desc = $(modal+' .description').val(),
		id='',
		newsort = 'status',
		source='add',
		button=$(this);
		button.addClass('disabled').attr('disabled','disabled');

	if(modal=='#editapistatusshownmodal'){
		id = $(modal+' .apistatusshownmodalid').val();
		source = 'edit';
		newsort = 'status';
	}

	if(desc==''){
		$(modal+' .description').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide status name.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		$.post(server+'api-status-shown.php',{apistatusshownSaveEdit:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',source:source,id:id,desc:desc},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						
						$('#apistatusshowntable').flexOptions({
											url:'loadables/ajax/system.api-status-shown.php',
											sortname: newsort,
											sortorder: "asc"
						}).flexReload(); 
						$('#loading-img').addClass('hidden');
						$(modal).off('hidden.bs.modal');
						button.removeAttr('disabled').removeClass('disabled');
						$(modal+' .description').val('');


					});
				}
				else if(data.trim()=='codeexists'){
					$('#loading-img').addClass('hidden');
					$(modal+' .description').focus();
					$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Status exists. Please provide another status.</div></div>");
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
