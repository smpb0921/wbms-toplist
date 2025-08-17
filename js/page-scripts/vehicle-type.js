$(document).off('click','.editvehicletypebtn').on('click','.editvehicletypebtn',function(){
	var modal = "#editvehicletypemodal";
	$(modal+' .code').val($(this).attr('code'));
	$(modal+' .description').val($(this).attr('desc'));
	$(modal+' .vehicletypemodalid').val($(this).attr('rowid'));

	var type = $(this).attr('type');
	

	if(type!='null'&&type!='NULL'&&type!=null&&type!=undefined&&type!=''){
		$(modal+" .type").empty().append('<option selected value="'+type+'">'+type+'</option>').trigger('change');
	}
	else{
		$(modal+" .type").empty().trigger('change');
	}
});

/***************************** SAVE | EDIT **************************************************/
$(document).off('click','.vehicletypemodal-savebtn:not(".disabled")').on('click','.vehicletypemodal-savebtn:not(".disabled")',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		code = $(modal+' .code').val(),
		desc = $(modal+' .description').val(),
		type = $(modal+' .type').val(),
		id='',
		newsort = 'created_date',
		source='add',
		button=$(this);
		button.addClass('disabled').attr('disabled','disabled');

	if(modal=='#editvehicletypemodal'){
		id = $(modal+' .vehicletypemodalid').val();
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
	else if(type==''||type=='null'||type=='NULL'||type==null||type==undefined){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select type.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		$.post(server+'vehicle-type.php',{vehicletypeSaveEdit:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',source:source,id:id,code:code,desc:desc,type:type},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						
						$('#vehicletypetable').flexOptions({
											url:'loadables/ajax/maintenance.vehicle-type.php',
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
