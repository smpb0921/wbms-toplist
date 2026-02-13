var tabcarrier = '#carrier-menutabpane';

$(document).off('click','.editcarrierbtn').on('click','.editcarrierbtn',function(){
	var modal = "#editcarriermodal";
	$(modal+' .code').val($(this).attr('code'));
	$(modal+' .description').val($(this).attr('desc'));
	$(modal+' .carriermodalid').val($(this).attr('rowid'));
});

/***************************** SAVE | EDIT **************************************************/
$(document).off('click','.carriermodal-savebtn:not(".disabled")').on('click','.carriermodal-savebtn:not(".disabled")',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		code = $(modal+' .code').val(),
		desc = $(modal+' .description').val(),
		id='',
		newsort = 'created_date',
		source='add',
		button=$(this);
		button.addClass('disabled').attr('disabled','disabled');

	if(modal=='#editcarriermodal'){
		id = $(modal+' .carriermodalid').val();
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
		$.post(server+'carrier.php',{CarrierSaveEdit:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',source:source,id:id,code:code,desc:desc},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						
						$('#carriertable').flexOptions({
											url:'loadables/ajax/maintenance.carrier.php',
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

$(document).off('click',tabcarrier+' #uploadcarriermodal-uploadbtn:not(".disabled")').on('click',tabcarrier+' #uploadcarriermodal-uploadbtn:not(".disabled")',function(){

	var modal = '#uploadcarriermodal';
	var modal2 = '#carrier-uploadtransactionlogmodal';
	var form = '#uploadcarriermodal-form';
	var logframe = '#carrieruploadtransactionlogframe';
	var button = $(this);
	button.addClass('disabled');

	if($(tabcarrier+' '+modal+' .uploadcarriermodal-file').val().trim()==''){
		say('Please select a file to upload');
		button.removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		
		$(modal).modal('hide');
		$(document).off('hidden.bs.modal',tabcarrier+' '+modal).on('hidden.bs.modal',tabcarrier+' '+modal,function(){
			
			$(document).off('hidden.bs.modal',tabcarrier+' '+modal);
			$(tabcarrier+' '+modal2).modal('show');
			$(form).submit();

			$(logframe).load(function () {

				button.removeClass('disabled');

				$('#loading-img').addClass('hidden');

				$('#carriertable').flexOptions({
						url:'loadables/ajax/maintenance.carrier.php',
						sortname: "created_date",
						sortorder: "desc"
				}).flexReload();

			});

		});
	
	}

});
/************************************* UPLOAD END *************************************************/
