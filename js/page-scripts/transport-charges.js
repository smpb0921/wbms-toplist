$(document).off('click','.edittransportchargesbtn').on('click','.edittransportchargesbtn',function(){
	var modal = "#edittransportchargesmodal";
	$(modal+' .code').val($(this).attr('code'));
	$(modal+' .description').val($(this).attr('desc'));
	$(modal+' .transportchargesmodalid').val($(this).attr('rowid'));
});

/***************************** SAVE | EDIT **************************************************/
$(document).off('click','.transportchargesmodal-savebtn:not(".disabled")').on('click','.transportchargesmodal-savebtn:not(".disabled")',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		code = $(modal+' .code').val(),
		desc = $(modal+' .description').val(),
		id='',
		newsort = 'created_date',
		source='add',
		button=$(this);
		button.addClass('disabled').attr('disabled','disabled');

	if(modal=='#edittransportchargesmodal'){
		id = $(modal+' .transportchargesmodalid').val();
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
	else{
		$('#loading-img').removeClass('hidden');
		$.post(server+'transport-charges.php',{TransportChargesSaveEdit:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',source:source,id:id,code:code,desc:desc},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						
						$('#transportchargestable').flexOptions({
											url:'loadables/ajax/maintenance.transport-charges.php',
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
