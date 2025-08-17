var contentUSER = '#user-menutabpane';

$(document).off('click',contentUSER+' .user-modal-savebtn:not(".disabled")').on('click',contentUSER+' .user-modal-savebtn:not(".disabled")',function(){
	var button = $(this);
	button.addClass('disabled');

	var modal = '#'+$(this).closest('.modal').attr('id');
	var action = (modal=='#addusermodal')?'add':'edit';
	var fname=$(modal+' .user-firstname').val();
	var mname=$(modal+' .user-middlename').val();
	var lname=$(modal+' .user-lastname').val();
	var username=$(modal+' .user-username').val();
	var email=$(modal+' .user-email').val();
	var mobile=$(modal+' .user-mobile').val();
	var couriersupervisor=$(modal+' .user-couriersupervisorflag').val();
	var freightsupervisor=$(modal+' .user-freightsupervisorflag').val();
	var loc=$(modal+' .user-location').val();
	var usergroup=$(modal+' .user-usergroup').val();
	var otherloc = $(modal+' .user-otherlocation').val();

	var id = '';
	var activeflag = 1;

	var useraccess = [];

	if(modal=='#editusermodal'){
		activeflag = $(modal+' .user-activeflag').val();
		id = $(modal+' #user-userid').val();
	}


	/*var v =$(modal+' .useraccesssection').jstree(true).get_json('#', {'flat': true});
	for (i = 0; i < v.length && i < 10; i++) {
	    var z = v[i];
	    alert("z[id] = " + z["id"]);
	}*/
	$(modal+' .useraccesssection').jstree().open_all();
	$(modal+" .useraccesssection").find(".jstree-checkbox").each(function(){			
		if(!$(this).closest('.jstree-anchor').hasClass("jstree-clicked")&&!$(this).hasClass('jstree-undetermined')){
			useraccess.push($(this).closest('li').attr('value'));
			//alert($(this).closest('li').attr('value'));
		}
	});




	//alert(useraccess);

	///////////////////
	if(fname==''){
		$(modal+' .user-firstname').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide <b>first name</b></div></div>");
		button.removeClass('disabled');
	}
	else if(lname==''){
		$(modal+' .user-lastname').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide <b>last name</b></div></div>");
		button.removeClass('disabled');
	}
	else if(username==''){
		$(modal+' .user-username').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a <b>username</b></div></div>");
		button.removeClass('disabled');
	}
	else if(checkUsername(username)==false){
		$(modal+' .user-username').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span><b>Invalid Username</b> <br>It should only contain alphabetical and numerical characters, and underscores.<br>It should be atleast 5 characters long.</div></div>");
		button.removeClass('disabled');
	}
	else if((couriersupervisor==1||freightsupervisor==1)&&mobile.trim()==''){
		$(modal+' .user-mobile').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a mobile number.</div></div>");
		button.removeClass('disabled');
	}
	else if(loc==''||loc=='NULL'||loc==null||loc=='null'){
		$(modal+' .user-location').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a location.</div></div>");
		button.removeClass('disabled');
	}
	else if(usergroup==''||usergroup=='NULL'||usergroup==null||usergroup=='null'){
		$(modal+' .user-usergroup').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select user group.</div></div>");
		button.removeClass('disabled');
	}
	else{

		$('#loading-img').removeClass('hidden');
		$.post(server+'user.php',{userSaveEditTransaction:'sdfsdFJso#Nj@Ho1iss4z4$Oi#l',action:action,mobile:mobile,couriersupervisor:couriersupervisor,freightsupervisor:freightsupervisor,id:id,fname:fname,mname:mname,lname:lname,loc:loc,otherloc:otherloc,email:email,username:username,activeflag:activeflag,usergroup:usergroup,useraccess:useraccess},function(data){

			if(data.trim()=='success'){
				$(modal).modal('hide');
				$(modal).off('hidden.bs.modal').on('hidden.bs.modal',function(){
					$(modal).off('hidden.bs.modal');
					$('#loading-img').addClass('hidden');
					button.removeClass('disabled');

					$(contentUSER+' #usertable').flexOptions({
											url:'loadables/ajax/system.user.php',
											sortname: 'created_date',
											sortorder: "desc"
					}).flexReload(); 
					

					$(modal+' .useraccesssection').jstree(true).deselect_all();
					$(modal+' .user-firstname').val('');
					$(modal+' .user-middlename').val('');
					$(modal+' .user-lastname').val('');
					$(modal+' .user-username').val('');
					$(modal+' .user-email').val('');
					$(modal+' .user-location').empty().trigger('change');
					$(modal+' .user-otherlocation').empty().trigger('change');
					$(modal+' .user-usergroup').empty().trigger('change');
				});
				
			}
			else if(data.trim()=='usernameexist'){
				$(modal+' .user-username').focus();
				$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span><b>Username</b> already exists.</div></div>");
				$('#loading-img').addClass('hidden');
				button.removeClass('disabled');
			}
			else{
				$('#loading-img').addClass('hidden');
				button.removeClass('disabled');
				alert(data);
			}
		});
	}
});

$(document).off('click',contentUSER+' .edituserbtn').on('click',contentUSER+' .edituserbtn',function(){
		var id, stat, firstname, middlename, lastname, department, email, username, usergroup, warehouse, warehousename;
		id = $(this).attr('rowid');
		stat = $(this).attr('activeflag');
		firstname = $(this).attr('fname');
		middlename = $(this).attr('mname');
		lastname = $(this).attr('lname');
		locid = $(this).attr('locid');
		locname = $(this).attr('locname');
		email = $(this).attr('email');
		username = $(this).attr('username');
		usergroupid = $(this).attr('usergroupid');
		usergroup = $(this).attr('usergroup');
		mobile = $(this).attr('mobile');
		couriersupervisor = $(this).attr('couriersupervisor');
		freightsupervisor = $(this).attr('freightsupervisor');
		$('#editusermodal #user-userid').val(id);
		$('#editusermodal .user-firstname').val(firstname);
		$('#editusermodal .user-middlename').val(middlename);
		$('#editusermodal .user-lastname').val(lastname);
		$('#editusermodal .user-email').val(email);
		$('#editusermodal .user-username').val(username);
		$('#editusermodal .user-activeflag').val(stat);
		$('#editusermodal .user-mobile').val(mobile);
		$('#editusermodal .user-couriersupervisorflag').val(couriersupervisor).trigger('change');
		$('#editusermodal .user-freightsupervisorflag').val(freightsupervisor).trigger('change');


		$('#editusermodal .useraccesssection').jstree(true).select_all();


		if(locname!=null){
				$('#editusermodal .user-location').empty().append('<option selected value="'+locid+'">'+locname+'</option>').trigger('change');
		}
		else{
				$('#editusermodal .user-location').empty().trigger('change');
		}

		if(usergroup!=null){
				$('#editusermodal .user-usergroup').addClass('disabled').empty().append('<option selected value="'+usergroupid+'">'+usergroup+'</option>').trigger('change').removeClass('disabled');
		}
		else{
				$('#editusermodal .user-usergroup').addClass('disabled').empty().trigger('change').removeClass('disabled');
		}


		$.post(server+'user.php',{otherLocations:'sdfed#n2L1hfi$n#opi3opod30napri',id:id},function(data){
			data = $.parseJSON(data);
			var otherwhse = data["otherwhse"].split('#@$');


			$('#editusermodal .user-otherlocation').empty();
			for (var i = 0; i < otherwhse.length; i++) {
				var otherwhse1 = otherwhse[i];
				    otherwhse1 = otherwhse1.split('%$&');
				if(otherwhse1[1]!=null){
					$('#editusermodal .user-otherlocation').append('<option selected value="'+otherwhse1[0]+'">'+otherwhse1[1]+'</option>').trigger('change');
				}
				else{
					$('#editusermodal .user-otherlocation').empty().trigger('change');
				}

			};
		});


		$('#editusermodal .useraccesssection').jstree().open_all();
		$.post(server+'user.php',{DLnslrii3hl34p03ddnfo4i:'sdfei3opod30napri',id:id},function(data){
			var fetchdata = data.trim().split(','),
				count = fetchdata.length;
			for (var i = 0;i<count; i++) {

				var nodeID = $("#editusermodal li[value='"+fetchdata[i]+"']").attr('id');
				$('#editusermodal .useraccesssection').jstree('deselect_node', nodeID);


				//$('#editusermodal .useraccesssection').jstree(true).deselect_node($("#editusermodal .treeitem[value='"+fetchdata[i]+"']"));
				
			};

		});
		
});

$('#editusermodal').on('hidden.bs.modal',function(){
	$("#editusermodal input[type='checkbox']").prop('checked',false);
});



$(document).off('click',contentUSER+' .deleteuserbtn').on('click',contentUSER+' .deleteuserbtn',function(){
		var button = $(this);
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
					confirmButtonClass: 'btn-oceanblue btn-primary', 
					cancelButtonClass: 'btn-royalblue', 
					theme: 'white', 

					confirm: function(){
						id = [];
						$(contentUSER+' #usertable tr.trSelected').each(function(){
							id.push($(this).attr('rowid'));
						});
						if(id.length>0){
							$('#loading-img').removeClass('hidden');
							$.post(server+'user.php',{deleteselected:'',id:id},function(data){
								if(data.trim()!='success'){
									$('#loading-img').addClass('hidden');
									alert(data);
									button.removeAttr('disabled');
								}
								else{
									loadpage(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='user-menutabpane']",'System/user.php');
									
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

$(document).off('click',contentUSER+' .resetpasswordbtn').on('click',contentUSER+' .resetpasswordbtn',function(){
	var button = $(this);
	button.attr('disabled',true);



	$.confirm({
					animation: 'bottom', 
					closeAnimation: 'top',
					animationSpeed: 1000,
					animationBounce:1,
					title: 'Reset User Password',
					content: 'Are you sure you want to continue?',
					confirmButton: 'Confirm',
					cancelButton: 'Cancel',	
					confirmButtonClass: 'btn-yellow', 
					cancelButtonClass: 'btn-yellow', 
					theme: 'white', 

					confirm: function(){
						var id = button.attr('rowid');
						$('#loading-img').removeClass('hidden');
						$.post(server+'user.php',{ki45lKJn3idlky:'3kh$klkddj4%l',id:id},function(data){
								if(data.trim()=='success'){
								
									
										setTimeout(function(){
											$('#loading-img').addClass('hidden');
											say("Password Reset Successful");
											$(contentUSER+' #usertable').flexOptions({
												url:'loadables/ajax/system.user.php',
												sortname: 'updated_date',
												sortorder: "desc"
											}).flexReload(); 
										},300);
									
								}
								else{
									$('#loading-img').addClass('hidden');
									alert(data);
									button.removeAttr('disabled');
								}
						});
					},
					cancel:function(){ 
						button.removeAttr('disabled');
					}

		});


});

$(document).off('change',contentUSER+' .section').on('change',contentUSER+' .section',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		sectionname = $(this).data('section');

	if($(this).prop('checked')==true){
		$(modal+' .'+sectionname+' .useraccess').prop('checked',true);
	}
	else{
		$(modal+' .'+sectionname+' .useraccess').prop('checked',false);
	}
	
});

$(document).off('change',contentUSER+' .view').on('change',contentUSER+' .view',function(){
	if($(this).prop('checked')==true){
		$(this).closest('td').siblings('td:not([rowspan])').children('input').prop('checked',true);
	}
	else{
		$(this).closest('td').siblings('td:not([rowspan])').children('input').prop('checked',false);
	}
	
});


$(document).off('change',contentUSER+' .user-usergroup:not(".disabled")').on('change',contentUSER+' .user-usergroup:not(".disabled")',function(){

	var id = $(this).val();
	var modal = '#'+$(this).closest(".modal").attr('id');

	$(modal+' .useraccesssection').jstree(true).select_all();
	$(modal+' .useraccesssection').jstree().open_all();
	$.post(server+'user-group.php',{DLnslrii3hl34p03ddnfo4i:'sdfei3opod30napri',id:id},function(data){
		
			var fetchdata = data.trim().split(','),
				count = fetchdata.length;
			for (var i = 0;i<count; i++) {

				var nodeID = $(modal+" li[value='"+fetchdata[i]+"']").attr('id');
				$(modal+' .useraccesssection').jstree('deselect_node', nodeID);

			};

	});


});