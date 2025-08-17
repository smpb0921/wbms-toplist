$(document).off('click','.editfinalstatusbtn').on('click','.editfinalstatusbtn',function(){
	var modal = "#editfinalstatusmodal";
	$(modal+' .status').val($(this).attr('status'));
	$(modal+' .finalstatusmodalid').val($(this).attr('rowid'));
});

/***************************** SAVE | EDIT **************************************************/
$(document).off('click','.finalstatusmodal-savebtn:not(".disabled")').on('click','.finalstatusmodal-savebtn:not(".disabled")',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		status = $(modal+' .status').val(),
		id='',
		newsort = 'created_date',
		source='add',
		button=$(this);
		button.addClass('disabled').attr('disabled','disabled');

	if(modal=='#editfinalstatusmodal'){
		id = $(modal+' .finalstatusmodalid').val();
		source = 'edit';
		newsort = 'updated_date';
	}

	if(status==''){
		$(modal+' .status').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide status description.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		$.post(server+'final-status.php',{finalstatusSaveEdit:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',source:source,id:id,status:status},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						
						$('#finalstatustable').flexOptions({
											url:'loadables/ajax/system.final-status.php',
											sortname: newsort,
											sortorder: "desc"
						}).flexReload(); 
						$('#loading-img').addClass('hidden');
						$(modal).off('hidden.bs.modal');
						button.removeAttr('disabled').removeClass('disabled');
						$(modal+' .status').val('');


					});
				}
				else if(data.trim()=='statusexists'){
					$('#loading-img').addClass('hidden');
					$(modal+' .status').focus();
					$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Status exists. Please provide another status description.</div></div>");
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
