$(document).off('click','.editpersonnelbtn').on('click','.editpersonnelbtn',function(){
	var modal = "#editpersonnelmodal";
	$(modal+' .firstname').val($(this).attr('firstname'));
	$(modal+' .lastname').val($(this).attr('lastname'));
	$(modal+' .contactnumber').val($(this).attr('contact'));
	$(modal+' .personnelmodalid').val($(this).attr('rowid'));
	$(modal+' .status').val($(this).attr('activeflag')).trigger('change');

	var type = $(this).attr('type');
	var position = $(this).attr('position');
	

	if(type!='null'&&type!='NULL'&&type!=null&&type!=undefined&&type!=''){
		$(modal+" .type").empty().append('<option selected value="'+type+'">'+type+'</option>').trigger('change');
	}
	else{
		$(modal+" .type").empty().trigger('change');
	}

	if(position!='null'&&position!='NULL'&&position!=null&&position!=undefined&&position!=''){
		$(modal+" .position").empty().append('<option selected value="'+position+'">'+position+'</option>').trigger('change');
	}
	else{
		$(modal+" .position").empty().trigger('change');
	}
});

/***************************** SAVE | EDIT **************************************************/
$(document).off('click','.personnelmodal-savebtn:not(".disabled")').on('click','.personnelmodal-savebtn:not(".disabled")',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		firstname = $(modal+' .firstname').val(),
		lastname = $(modal+' .lastname').val(),
		position = $(modal+' .position').val(),
		contact = $(modal+' .contactnumber').val(),
		type = $(modal+' .type').val(),
		id='',
		status = 1,
		newsort = 'created_date',
		source='add',
		button=$(this);
		button.addClass('disabled').attr('disabled','disabled');

	if(modal=='#editpersonnelmodal'){
		id = $(modal+' .personnelmodalid').val();
		status = $(modal+' .status').val();
		source = 'edit';
		newsort = 'updated_date';
	}

	if(status!=1&&status!=0){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select status.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(firstname==''){
		$(modal+' .firstname').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide first name.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(lastname==''){
		$(modal+' .lastname').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide last name.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(contact==''){
		$(modal+' .contactnumber').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide contact number.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(position==''||position=='null'||position=='NULL'||position==null||position==undefined){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select position.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type==''||type=='null'||type=='NULL'||type==null||type==undefined){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select driver for.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		$.post(server+'personnel.php',{personnelSaveEdit:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',status:status,source:source,id:id,firstname:firstname,lastname:lastname,contact:contact,position:position,type:type},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						
						$('#personneltable').flexOptions({
											url:'loadables/ajax/maintenance.personnel.php',
											sortname: newsort,
											sortorder: "desc"
						}).flexReload(); 
						$('#loading-img').addClass('hidden');
						$(modal).off('hidden.bs.modal');
						button.removeAttr('disabled').removeClass('disabled');
						$(modal+' .firstname').val('');
						$(modal+' .lastname').val('');
						$(modal+' .contactnumber').val('');
						$(modal+' .position').empty().trigger('change');
						$(modal+' .type').empty().trigger('change');


					});
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
