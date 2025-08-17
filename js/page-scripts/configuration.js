$(document).off('click','#emailnotification-savebtn').on('click','#emailnotification-savebtn', function(){
	$(this).attr('disabled',true);
	var modal = '#'+$(this).closest('.modal').attr('id');
	var host, encryption, port, username, password, sender, emailfrom, ok=true, flag;
	host = $(modal+' .host').val();
	encryption = $(modal+' .encryption').val();
	port = $(modal+' .tcpport').val();
	username = $(modal+' .smtpusername').val();
	password = $(modal+' .smtppassword').val();
	sender = $(modal+' .emailsender').val();
	emailfrom = $(modal+' .emailsentfrom').val();
	emailfrom = $(modal+' .emailsentfrom').val();
	flag = $(modal+' .activeflag').prop('checked');
	if(flag==true){
		flag = 1;
	}
	else{
		flag = 0;
	}

	$('#email-notification-settings .requiredinputfield').each(function(){
		if($(this).val()==''){
			ok=false;
		}
	});

	if(ok==false){
		$('#email-notification-settings .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide all<b> required information</b>.</div></div>");
		$('.content').animate({scrollTop:0},200);
		$(this).removeAttr('disabled');
	}
	else{

		$.post(server+'configuration.php',{emailConfiguration:'Bdm2Donoi#20slOPD90&$rpodk49d94po3',flag:flag,host:host,encryption:encryption,port:port,username:username,password:password,sender:sender,emailfrom:emailfrom},function(data){
			if(data.trim()=='success'){

				
				$('#email-notification-settings').modal('hide');
				$('#email-notification-settings').on('hidden.bs.modal',function(){
					loadpage(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='configuration-menutabpane']",'System/configuration.php');
					$.getScript(pagescripts+'configuration.js');
					$('.content').animate({scrollTop:0},200);
					notify("Email Settings has been <b>updated</b>", "success","fa fa-check-circle");
				});
				
			}
			else{
				alert(data);
			}
		});
	}
});


$(document).off('click','#companyinformation-savebtn:not(".disabled")').on('click','#companyinformation-savebtn:not(".disabled")', function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		name = $(modal+' .companyname').val(),
		tin = $(modal+' .companytin').val(),
		addr = $(modal+' .companyaddress').val(),
		city = $(modal+' .city').val(),
		state = $(modal+' .stateprovince').val(),
		postal = $(modal+' .postalcode').val(),
		country = $(modal+' .country').val(),
		primary = $(modal+' .primarycolor').val(),
		secondary = $(modal+' .secondarycolor').val(),
		button = $(this);

	button.addClass('disabled');

	if(name.trim()==''){
		say('Please provide company name');
		button.removeClass('disabled');
	}
	else if(addr.trim()==''){
		say('Please provide company address');
		button.removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		$.post(server+'configuration.php',{companyInformation:'Bdm#r@1podk49d94po3sfr$@1qdsdf',tin:tin,name:name,addr:addr,city:city,state:state,postal:postal,country:country,primary:primary,secondary:secondary},function(data){
			if(data.trim()=='success'){
				$(modal).modal('hide');
				$(modal).on('hidden.bs.modal',function(){
					
					loadpage(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='configuration-menutabpane']",'System/configuration.php');
					//$.getScript(pagescripts+'configuration.js');
					//$('.content').animate({scrollTop:0},200);
					notify("Company Information has been <b>updated</b>", "success","fa fa-check-circle");
					$('#loading-img').addClass('hidden');
					$(modal).off('hidden.bs.modal');
				});
			}
			else{
				alert(data);
				button.removeClass('disabled');
				$('#loading-img').addClass('hidden');
			}
		});
	}


});

