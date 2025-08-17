$(document).off('click','.editzonebtn').on('click','.editzonebtn',function(){
	var modal = "#editzonemodal";
	$(modal+' .code').val($(this).attr('code'));
	$(modal+' .description').val($(this).attr('desc'));
	$(modal+' .type').val($(this).attr('type')).trigger('change');
	$(modal+' .zonemodalid').val($(this).attr('rowid'));
});

/***************************** SAVE | EDIT **************************************************/
$(document).off('click','.zonemodal-savebtn:not(".disabled")').on('click','.zonemodal-savebtn:not(".disabled")',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		code = $(modal+' .code').val(),
		desc = $(modal+' .description').val(),
		type = $(modal+' .type').val(),
		id='',
		newsort = 'created_date',
		source='add',
		button=$(this);
		button.addClass('disabled').attr('disabled','disabled');

	if(modal=='#editzonemodal'){
		id = $(modal+' .zonemodalid').val();
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
	else if(type!='DOMESTIC'&&type!='INTERNATIONAL'){
		$(modal+' .type').select2('open');
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select type.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		$.post(server+'zone.php',{zoneSaveEdit:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',source:source,id:id,type:type,code:code,desc:desc},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						
						$('#zonetable').flexOptions({
											url:'loadables/ajax/maintenance.zone.php',
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
				else if(data.trim()=='invalidtype'){
					$('#loading-img').addClass('hidden');
					$(modal+' .type').select2('open');
					$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid Type. Please re-select.</div></div>");
					button.removeAttr('disabled').removeClass('disabled');
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
