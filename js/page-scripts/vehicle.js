$(document).off('click','.editvehiclebtn').on('click','.editvehiclebtn',function(){
	var modal = "#editvehiclemodal";
	$(modal+' .platenumber').val($(this).attr('platenumber'));
	$(modal+' .model').val($(this).attr('model'));
	$(modal+' .year').val($(this).attr('year'));
	$(modal+' .vehiclemodalid').val($(this).attr('rowid'));
	$(modal+' .status').val($(this).attr('activeflag')).trigger('change');

	var vehicletype = $(this).attr('vehicletype');
	var vehicletypeid = $(this).attr('vehicletypeid');
	

	if(vehicletype!='null'&&vehicletype!='NULL'&&vehicletype!=null&&vehicletype!=undefined&&vehicletype!=''){
		$(modal+" .vehicletype").empty().append('<option selected value="'+vehicletypeid+'">'+vehicletype+'</option>').trigger('change');
	}
	else{
		$(modal+" .vehicletype").empty().trigger('change');
	}

	
});

/***************************** SAVE | EDIT **************************************************/
$(document).off('click','.vehiclemodal-savebtn:not(".disabled")').on('click','.vehiclemodal-savebtn:not(".disabled")',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		platenumber = $(modal+' .platenumber').val(),
		model = $(modal+' .model').val(),
		year = $(modal+' .year').val(),
		vehicletype = $(modal+' .vehicletype').val(),
		id='',
		status = 1,
		newsort = 'created_date',
		source='add',
		button=$(this);
		button.addClass('disabled').attr('disabled','disabled');

	if(modal=='#editvehiclemodal'){
		id = $(modal+' .vehiclemodalid').val();
		status = $(modal+' .status').val();
		source = 'edit';
		newsort = 'updated_date';
	}

	if(status!=1&&status!=0){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select status.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(vehicletype==''||vehicletype=='null'||vehicletype=='NULL'||vehicletype==null||vehicletype==undefined){
		$(modal+' .vehicletype').select2('open');
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select vehicle type.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(platenumber==''){
		$(modal+' .platenumber').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide plate number.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(model==''){
		$(modal+' .model').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide vehicle model.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		$.post(server+'vehicle.php',{vehicleSaveEdit:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',status:status,source:source,id:id,platenumber:platenumber,model:model,year:year,vehicletype:vehicletype},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						
						$('#vehicletable').flexOptions({
											url:'loadables/ajax/maintenance.vehicle.php',
											sortname: newsort,
											sortorder: "desc"
						}).flexReload(); 
						$('#loading-img').addClass('hidden');
						$(modal).off('hidden.bs.modal');
						button.removeAttr('disabled').removeClass('disabled');
						$(modal+' .platenumber').val('');
						$(modal+' .model').val('');
						$(modal+' .year').val('');
						$(modal+' .vehicletype').empty().trigger('change');


					});
				}
				else if(data.trim()=='codeexists'){
					$('#loading-img').addClass('hidden');
					$(modal+' .platenumber').focus();
					$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Plate Number exists. Please provide another plate number.</div></div>");
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
