$(document).off('click','.editshipviabtn').on('click','.editshipviabtn',function(){
	var modal = "#editshipviamodal";
	$(modal+' .shipvia').val($(this).attr('shipvia'));
	$(modal+' .shipviaid').val($(this).attr('id'));
});

/***************************** SAVE | EDIT **************************************************/
$(document).off('click','.shipvia-modal-savebtn').on('click','.shipvia-modal-savebtn',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		shipvia = $(modal+' .shipvia').val(),
		id='',
		source='add',
		button=$(this);

	if(modal=='#editshipviamodal'){
		id = $(modal+' .shipviaid').val();
		source = 'edit';
	}

	if(shipvia==''){
		$(modal+' .shipvia').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide ship via description.</div></div>");
		button.removeAttr('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		$.post(server+'ship-via.php',{ShipViaSaveEdit:'SJK!ks@hd83b%2J2Hwo2i&0',source:source,id:id,shipvia:shipvia},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						loadpage(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='shipvia-menutabpane']",'Sales/ship-via.php');
					});
				}
				else{
					$('#loading-img').addClass('hidden');
					alert(data);
					button.removeAttr('disabled');
				}
		});	
	}
});

/******************************* DELETE *****************************************************/

$(document).off('click','#deleteshipviabtn').on('click','#deleteshipviabtn',function(){
		var button = $(this),
			tablename = '#shipviatable';
		button.attr('disabled',true);
		var id, response;


		$.confirm({
					animation: 'bottom', 
					closeAnimation: 'top',
					animationSpeed: 1000,
					animationBounce:1,
					title: 'Confirmation',
					content: 'Are you sure you want to continue?',
					confirmButton: 'Confirm',
					cancelButton: 'Cancel',	
					confirmButtonClass: 'btn-yellow', 
					cancelButtonClass: 'btn-yellow', 
					theme: 'white', 

					confirm: function(){
						id = [];
						$(tablename+' .itemcheckbox:checked').each(function(){
							id.push($(this).attr('rowid'));
						});
						if(id.length>0){
							$('#loading-img').removeClass('hidden');
							$.post(server+'ship-via.php',{deleteselected:'',id:id},function(data){
								if(data.trim()!='success'){
									$('#loading-img').addClass('hidden');
									alert(data);
									button.removeAttr('disabled');
								}
								else{
									loadpage(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='shipvia-menutabpane']",'Sales/ship-via.php');
									
								}
							});
						}
						else{
							button.removeAttr('disabled');
						}
					},
					cancel:function(){ 
						button.removeAttr('disabled');
					}

		});

});