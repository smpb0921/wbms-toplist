var contentUSERGROUP = '#usergroup-menutabpane';


$(document).off('click','.editusergroupbtn').on('click','.editusergroupbtn',function(){
	var modal = "#editusergroupmodal";
	$(modal+' .code').val($(this).attr('code'));
	$(modal+' .description').val($(this).attr('desc'));
	$(modal+' .usergroupmodalid').val($(this).attr('rowid'));
});


$(document).off('click',contentUSERGROUP+' .editusergrouprightsbtn').on('click',contentUSERGROUP+' .editusergrouprightsbtn',function(){
	var id = $(this).attr('rowid');
	var modal = "#editusergrouppermissionsmodal";

	$(modal+' #usergrouppermissionid').val(id);

	$(modal+' .usergroupaccesssection').jstree(true).select_all();
	$(modal+' .usergroupaccesssection').jstree().open_all();
	$.post(server+'user-group.php',{DLnslrii3hl34p03ddnfo4i:'sdfei3opod30napri',id:id},function(data){
		
			var fetchdata = data.trim().split(','),
				count = fetchdata.length;
			for (var i = 0;i<count; i++) {

				var nodeID = $(modal+" li[value='"+fetchdata[i]+"']").attr('id');
				$(modal+' .usergroupaccesssection').jstree('deselect_node', nodeID);

			};

	});
});


$(document).off('click',contentUSERGROUP+' #usergrouppermission-savebtn:not(".disabled")').on('click',contentUSERGROUP+' #usergrouppermission-savebtn:not(".disabled")',function(){
	var modal = '#'+$(this).closest('.modal').attr('id');
	var usergroupid = $(modal+' #usergrouppermissionid').val();
	var usergroupaccess = [];
	var button = $(this);
	button.addClass('disabled');
	$('#loading-img').removeClass('hidden');

	$(modal+' .usergroupaccesssection').jstree().open_all();
	$(modal+" .usergroupaccesssection").find(".jstree-checkbox").each(function(){			
		if(!$(this).closest('.jstree-anchor').hasClass("jstree-clicked")&&!$(this).hasClass('jstree-undetermined')){
			usergroupaccess.push($(this).closest('li').attr('value'));
		}
	});
	

	$.post(server+'user-group.php',{SaveUserGroupPermissions:'fjh$O2sFlfp$3oiml!@spoa',id:usergroupid,usergroupaccess:usergroupaccess},function(data){

		if(data.trim()=='success'){
				$(modal).modal('hide');
				$(modal).off('hidden.bs.modal').on('hidden.bs.modal',function(){
					$(modal).off('hidden.bs.modal');
					button.removeClass('disabled');
					$('#loading-img').addClass('hidden');
					
				});
		}
		else{
			alert(data);
			button.removeClass('disabled');
			$('#loading-img').addClass('hidden');
		}

	})

});




/***************************** SAVE | EDIT **************************************************/
$(document).off('click','.usergroupmodal-savebtn:not(".disabled")').on('click','.usergroupmodal-savebtn:not(".disabled")',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		code = $(modal+' .code').val(),
		desc = $(modal+' .description').val(),
		id='',
		newsort = 'created_date',
		source='add',
		button=$(this);
		button.addClass('disabled').attr('disabled','disabled');

	if(modal=='#editusergroupmodal'){
		id = $(modal+' .usergroupmodalid').val();
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
		$.post(server+'user-group.php',{usergroupSaveEdit:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',source:source,id:id,code:code,desc:desc},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						
						$('#usergrouptable').flexOptions({
											url:'loadables/ajax/system.user-group.php',
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
