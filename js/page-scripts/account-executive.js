var tabAccountExecutive = '#accountexecutive-menutabpane';

$(document).off('click','.editaccountexecutivebtn').on('click','.editaccountexecutivebtn',function(){
	var modal = "#editaccountexecutivemodal";
	$(modal+' .code').val($(this).attr('code'));
	$(modal+' .name').val($(this).attr('name'));
	$(modal+' .email').val($(this).attr('email'));
	$(modal+' .mobile').val($(this).attr('mobile'));
	$(modal+' .username').val($(this).attr('username'));
	$(modal+' .password').val($(this).attr('password'));
	$(modal+' .accountexecutivemodalid').val($(this).attr('rowid'));
});

/***************************** SAVE | EDIT **************************************************/
$(document).off('click','.accountexecutivemodal-savebtn:not(".disabled")').on('click','.accountexecutivemodal-savebtn:not(".disabled")',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		code = $(modal+' .code').val(),
		name = $(modal+' .name').val(),
		email = $(modal+' .email').val(),
		mobile = $(modal+' .mobile').val(),
		username = $(modal+' .username').val(),
		password = $(modal+' .password').val(),
		id='',
		newsort = 'created_date',
		source='add',
		button=$(this);
		button.addClass('disabled').attr('disabled','disabled');

	if(modal=='#editaccountexecutivemodal'){
		id = $(modal+' .accountexecutivemodalid').val();
		source = 'edit';
		newsort = 'updated_date';
	}

	if(code==''){
		$(modal+' .code').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a code.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(name==''){
		$(modal+' .name').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a name.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(email==''){
		$(modal+' .email').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide an email address.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(mobile==''){
		$(modal+' .mobile').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a mobile number.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(username==''){
		$(modal+' .username').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a username.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(password==''){
		$(modal+' .password').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a password.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		$.post(server+'account-executive.php',{AccountExecutiveSaveEdit:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',source:source,id:id,code:code,name:name,email:email,mobile:mobile,username:username,password:password},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						
						$('#accountexecutivetable').flexOptions({
											url:'loadables/ajax/maintenance.account-executive.php',
											sortname: newsort,
											sortorder: "desc"
						}).flexReload(); 
						$('#loading-img').addClass('hidden');
						$(modal).off('hidden.bs.modal');
						button.removeAttr('disabled').removeClass('disabled');
						$(modal+' input').val('');

					});
				}
				else if(data.trim()=='codeexists'){
					$('#loading-img').addClass('hidden');
					$(modal+' .code').focus();
					$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Code exists. Please provide another code.</div></div>");
					button.removeAttr('disabled').removeClass('disabled');
				}
				else if(data.trim()=='usernameexists'){
					$('#loading-img').addClass('hidden');
					$(modal+' .username').focus();
					$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Username exists. Please provide another username.</div></div>");
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

/************************************* UPLOAD *************************************************/

$(document).off('click',tabAccountExecutive+' #uploadaccountexecutivemodal-uploadbtn:not(".disabled")').on('click',tabAccountExecutive+' #uploadaccountexecutivemodal-uploadbtn:not(".disabled")',function(){

	var modal = '#uploadaccountexecutivemodal';
	var modal2 = '#accountexecutive-uploadtransactionlogmodal';
	var form = '#uploadaccountexecutivemodal-form';
	var logframe = '#accountexecutiveuploadtransactionlogframe';
	var button = $(this);
	button.addClass('disabled');

	if($(tabAccountExecutive+' '+modal+' .uploadaccountexecutivemodal-file').val().trim()==''){
		say('Please select a file to upload');
		button.removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		
		$(modal).modal('hide');
		$(document).off('hidden.bs.modal',tabAccountExecutive+' '+modal).on('hidden.bs.modal',tabAccountExecutive+' '+modal,function(){
			
			$(document).off('hidden.bs.modal',tabAccountExecutive+' '+modal);
			$(tabAccountExecutive+' '+modal2).modal('show');
			$(form).submit();

			$(logframe).load(function () {

				button.removeClass('disabled');

				$('#loading-img').addClass('hidden');

				$('#accountexecutivetable').flexOptions({
						url:'loadables/ajax/maintenance.account-executive.php',
						sortname: "created_date",
						sortorder: "desc"
				}).flexReload();

			});

		});
	
	}

});
/************************************* UPLOAD END *************************************************/
