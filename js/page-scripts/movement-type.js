$(document).off('click','.editmovementtypebtn').on('click','.editmovementtypebtn',function(){
	var modal = "#editmovementtypemodal";
	var rowid = $(this).attr('rowid');
	$(modal+' .movementtypemodalid').val(rowid);

	$.post(server+'movement-type.php',{MovementTypeGetInfo:'kjoI$H2oiaah3h0$09jDppo92po@k@',id:rowid},function(data){
		//alert(data);
		rsp = $.parseJSON(data);
		if(rsp['response']=='success'){
			$(modal+' .code').val(rsp['code']);
			$(modal+' .description').val(rsp['description']);

			$(modal+' .sourcemovement').tagsinput('removeAll');
			var mtsval = rsp['sourcemovement'].split(',');
			for(var i = 0; i<mtsval.length; i++){
				$(modal+' .sourcemovement').tagsinput('add',mtsval[i]);
			}
			
		}
		else{
			
			$(modal).modal('hide');
			$(modal).on('hidden.bs.modal',function(){
				$(modal).off('hidden.bs.modal');
				say('Unable to load data. Invalid ID.');
			});
		}
		
	});
});

/***************************** SAVE | EDIT **************************************************/
$(document).off('click','.movementtypemodal-savebtn:not(".disabled")').on('click','.movementtypemodal-savebtn:not(".disabled")',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		code = $(modal+' .code').val(),
		desc = $(modal+' .description').val(),
		sourcemovement = $(modal+' .sourcemovement').val(),
		shipmenttype = $(modal+' .shipmenttype').val(),
		id='',
		newsort = 'created_date',
		source='add',
		button=$(this);
		button.addClass('disabled').attr('disabled','disabled');

	if(modal=='#editmovementtypemodal'){
		id = $(modal+' .movementtypemodalid').val();
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
	else if(shipmenttype==''){
		$(modal+' .shipmenttype').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a shipment type.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		$.post(server+'movement-type.php',{MovementTypeSaveEdit:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',source:source,id:id,code:code,desc:desc,sourcemovement:sourcemovement,shipmenttype:shipmenttype},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						
						$('#movementtypetable').flexOptions({
											url:'loadables/ajax/maintenance.movement-type.php',
											sortname: newsort,
											sortorder: "desc"
						}).flexReload(); 
						$('#loading-img').addClass('hidden');
						$(modal).off('hidden.bs.modal');
						button.removeAttr('disabled').removeClass('disabled');
						$(modal+' .code').val('');
						$(modal+' .description').val('');
						$(modal+' .sourcemovement').tagsinput('removeAll');
						$(modal+' .shipmenttype').val('');


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
