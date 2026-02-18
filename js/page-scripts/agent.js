var tabagent = '#agent-menutabpane ';
function agentContactInsertRow(modal,table){

	var contact = $(tabagent+modal+' .contactfld').val(),
	    phone = $(tabagent+modal+' .phonenumberfld').val(),
	    email = $(tabagent+modal+' .emailfld').val(),
	    mobile = $(tabagent+modal+' .mobilenumberfld').val(),
	    defaultflag = "<select class='form-control defaultcontactflag'><option value='1' selected>Yes</option><option value='0'>No</option></select>";

	    if(parseInt(table.find("option[value='1']:selected").length)>0){
	    	defaultflag = "<select class='form-control defaultcontactflag'><option value='1'>Yes</option><option value='0' selected>No</option></select>";
	    }
	    if(contact.trim()!=''&&(phone.trim()!=''||email.trim()!=''||mobile.trim()!='')){
			table.DataTable().row.add([
						"<input type='checkbox' class='rowcheckbox'>",
						defaultflag,
						"<span class='agentcontact-contact'><i class='hidden'>"+contact+"</i><input type='text' class='form-control datatableinputfld' value='"+contact+"' style='min-width: 150px'></span>",
						"<span class='agentcontact-phone'><i class='hidden'>"+phone+"</i><input type='text' class='form-control datatableinputfld' value='"+phone+"' style='min-width: 150px'></span>",
						"<span class='agentcontact-email'><i class='hidden'>"+email+"</i><input type='text' class='form-control datatableinputfld' value='"+email+"' style='min-width: 150px'></span>",
						"<span class='agentcontact-mobile'><i class='hidden'>"+mobile+"</i><input type='text' class='form-control datatableinputfld' value='"+mobile+"' style='min-width: 150px'></span>"
			]).draw();

	    	agentContactClearContactfields(modal);
		}
}

function agentContactRemoveRow(modal,table){
	$(tabagent+modal+' '+table+' tbody tr .rowcheckbox:checked').each(function(){
		var tr = $(this).closest('tr');
		$(tabagent+modal+' '+table).DataTable().row(tr).remove().draw();
	});
}

function agentContactClearContactfields(modal){
		$(tabagent+modal+' .contactfld').val('');
	    $(tabagent+modal+' .phonenumberfld').val('');
	    $(tabagent+modal+' .emailfld').val('');
	    $(tabagent+modal+' .mobilenumberfld').val('');
	    $(tabagent+modal+' .contactfld').focus();
}


function agentClearInputFields(modal,type){
	$(modal+' .inputtxtfld').val(''),
	$(modal+' .inputslctfld').val('').trigger('change');

	if(type=='edit'){
		$(modal+' .mdlIDfld').val('');
	}

}




$(document).off('click',tabagent+' .agent-insertcontactbtn').on('click',tabagent+' .agent-insertcontactbtn',function(){
		var modal = '#'+$(this).closest('.modal').attr('id');
	    agentContactInsertRow(modal,$(tabagent+modal+' .agentcontactdetailstbl'));
});

$(document).off('click',tabagent+' .agent-removecontactbtn').on('click',tabagent+' .agent-removecontactbtn',function(){

		var tbl = '.agentcontactdetailstbl';
		var modal = '#'+$(this).closest('.modal').attr('id');
		agentContactRemoveRow(modal,tbl);
			

});


$(document).off('click',tabagent+' .agent-clearcontactfieldsbtn').on('click',tabagent+' .agent-clearcontactfieldsbtn',function(){


		var modal = '#'+$(this).closest('.modal').attr('id');
		agentContactClearContactfields(modal);
});


$(document).off('change',tabagent+' .defaultcontactflag').on('change',tabagent+' .defaultcontactflag',function(){
	$(this).closest('tr').siblings().find('select').val(0);
});





/***************************** SAVE | EDIT **************************************************/
$(document).off('click','.agentmodal-savebtn:not(".disabled")').on('click','.agentmodal-savebtn:not(".disabled")',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		code = $(modal+' .code').val(),
		shipmenttype = $(modal+' .shipmenttype').val(),
		companyname = $(modal+' .companyname').val(),
		area = $(modal+' .area').val(),
		remarks = $(modal+' .remarks').val(),
		street = $(modal+' .street').val(),
		district = $(modal+' .district').val(),
		city = $(modal+' .city').val(),
		province = $(modal+' .province').val(),
		zipcode = $(modal+' .zipcode').val(),
		country = $(modal+' .country').val(),
		contactcount = $(modal+' .agentcontactdetailstbl tbody tr.mydatatablerow').length,
		contact = [],
		email = [],
		phonenumber = [],
		mobilenumber = [],
		defaultflag = [],
		id='',
		newsort = 'created_date',
		source='add',
		button=$(this);
		button.addClass('disabled').attr('disabled','disabled');

	if(modal=='#editagentmodal'){
		id = $(modal+' .agentmodalid').val();
		source = 'edit';
		newsort = 'updated_date';
	}

	if(code==''){
		$(modal+' .code').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide code.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(shipmenttype==''||shipmenttype==null||shipmenttype=='NULL'||shipmenttype=='null'){
		$(modal+' .shipmenttype').select2('open');
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select shipment type.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	} 
	else if(companyname==''){
		$(modal+' .companyname').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide company name.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(area==''){
		$(modal+' .area').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide an area.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	
	/*else if(province==''||province==null||province=='NULL'||province=='null'){
		$(modal+' .province').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select region/province.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(city==''||city==null||city=='NULL'||city=='null'){
		$(modal+' .city').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select city.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(district==''||district==null||district=='NULL'||district=='null'){
		$(modal+' .district').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select district.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(zipcode==''||zipcode==null||zipcode=='NULL'||zipcode=='null'){
		$(modal+' .zipcode').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select zip code.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(street==''){
		$(modal+' .street').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide street address.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(country==''||country==null||country=='NULL'||country=='null'){
		$(modal+' .country').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select country.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}*/
	else if(parseInt(contactcount)==0){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide atleast 1 contact information</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else{
		$(modal+' .errordiv').empty();
		$('#loading-img').removeClass('hidden');
		$(tabagent+modal+' .agentcontactdetailstbl tbody tr.mydatatablerow').each(function(){
				contact.push($(this).find('.agentcontact-contact input').val());
				email.push($(this).find('.agentcontact-email input').val());
				phonenumber.push($(this).find('.agentcontact-phone input').val());
				mobilenumber.push($(this).find('.agentcontact-mobile input').val());
				defaultflag.push($(this).find('.defaultcontactflag').val());
		});

		
		
		$.post(server+'agent.php',{AgentSaveEdit:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',source:source,id:id,shipmenttypeee:shipmenttype,code:code,companyname:companyname,area:area,remarks:remarks,street:street,district:district,city:city,province:province,zipcode:zipcode,country:country,contact:contact,email:email,phonenumber:phonenumber,mobilenumber:mobilenumber,defaultflag:defaultflag},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						
						$('#agenttable').flexOptions({
											url:'loadables/ajax/maintenance.agent.php',
											sortname: newsort,
											sortorder: "desc"
						}).flexReload(); 
						$('#loading-img').addClass('hidden');
						$(modal).off('hidden.bs.modal');
						button.removeAttr('disabled').removeClass('disabled');


						agentClearInputFields(modal,source);
						$(tabagent+modal+' .agentcontactdetailstbl').DataTable().clear().draw();
						$(modal+' .errordiv').empty();

					});
				}
				else if(data.trim()=='codeexist'){
					$('#loading-img').addClass('hidden');
					$(modal+' .code').focus();
					$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Code exists. Please provide another code.</div></div>");
					button.removeAttr('disabled').removeClass('disabled');
				}
				else{
					$('#loading-img').addClass('hidden');
					$(modal+' .errordiv').empty();
					alert(data);
					button.removeAttr('disabled').removeClass('disabled');
				}
		});	
	}
});

/************************************************************************************/



$(document).off('click','.editagentbtn').on('click','.editagentbtn',function(){
	var modal = "#editagentmodal";
	var rowid = $(this).attr('rowid');

	agentClearInputFields(modal,'edit');
	$(tabagent+modal+' .agentcontactdetailstbl').DataTable().search('').clear().draw();

	$(modal+' .agentmodalid').val(rowid);

	$.post(server+'agent.php',{AgentGetInfo:'kjoI$H2oiaah3h0$09jDppo92po@k@',id:rowid},function(data){

		rsp = $.parseJSON(data);
		if(rsp['response']=='success'){
			$(modal+' .code').val(rsp['code']);
			$(modal+' .companyname').val(rsp['companyname']);
			$(modal+' .area').val(rsp['area']);
			$(modal+' .remarks').val(rsp['remarks']);
			$(modal+' .street').val(rsp['street']);
			$(modal+' .district').val(rsp['district']);
			$(modal+' .city').val(rsp['city']);
			$(modal+' .province').val(rsp['province']);
			$(modal+' .zipcode').val(rsp['zipcode']);

			if(rsp["district"]!=null){
		        $(modal+" .district").empty().append('<option selected value="'+rsp["district"]+'">'+rsp["district"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .district").empty().trigger('change');
		    }
			if(rsp["shipmenttype"]!=null){
		        $(modal+" .shipmenttype").empty().append('<option selected value="'+rsp["shipmenttypeid"]+'">'+rsp["shipmenttype"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .shipmenttype").empty().trigger('change');
		    }
		    if(rsp["city"]!=null){
		        $(modal+" .city").empty().append('<option selected value="'+rsp["city"]+'">'+rsp["city"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .city").empty().trigger('change');
		    }
		    if(rsp["province"]!=null){
		        $(modal+" .province").empty().append('<option selected value="'+rsp["province"]+'">'+rsp["province"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .province").empty().trigger('change');
		    }
		    if(rsp["zipcode"]!=null){
		        $(modal+" .zipcode").empty().append('<option selected value="'+rsp["zipcode"]+'">'+rsp["zipcode"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .zipcode").empty().trigger('change');
		    }

		    if(rsp["country"]!=null){
		        $(modal+" .country").empty().append('<option selected value="'+rsp["country"]+'">'+rsp["country"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .country").empty().trigger('change');
		    }



		    $.post(server+'agent.php',{AgentContactGetInfo:'kjoI$H2oiaah3h0$09jDppo92po@k@',id:rowid},function(data2){
		    	rsp1 = $.parseJSON(data2);
		    	for(var i=0;i<rsp1.length;i++){
				    if(rsp1[i]['defaultflag']==1){
		    			defaultflag = "<select class='form-control defaultcontactflag'><option value='1' selected>Yes</option><option value='0'>No</option></select>";	
				    }
				    else{
				    	defaultflag = "<select class='form-control defaultcontactflag'><option value='1'>Yes</option><option value='0' selected>No</option></select>";
				    }

		    		$(tabagent+modal+' .agentcontactdetailstbl').DataTable().row.add([
						"<input type='checkbox' class='rowcheckbox'>",
						defaultflag,
						"<span class='agentcontact-contact'><i class='hidden'>"+rsp1[i]['contact']+"</i><input type='text' class='form-control datatableinputfld' value='"+rsp1[i]['contact']+"' style='min-width: 150px'></span>",
						"<span class='agentcontact-phone'><i class='hidden'>"+rsp1[i]['phone']+"</i><input type='text' class='form-control datatableinputfld' value='"+rsp1[i]['phone']+"' style='min-width: 150px'></span>",
						"<span class='agentcontact-email'><i class='hidden'>"+rsp1[i]['email']+"</i><input type='text' class='form-control datatableinputfld' value='"+rsp1[i]['email']+"' style='min-width: 150px'></span>",
						"<span class='agentcontact-mobile'><i class='hidden'>"+rsp1[i]['mobile']+"</i><input type='text' class='form-control datatableinputfld' value='"+rsp1[i]['mobile']+"' style='min-width: 150px'></span>"
					]).draw();
		    	}
		    });
		    

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


$(document).off('show.bs.modal',tabagent+' #addagentmodal').on('show.bs.modal',tabagent+' #addagentmodal',function(){
    $("#addagentmodal .countriesdropdownselect").empty().append('<option selected value="Philippines">Philippines</option>').trigger('change');
    
});



/************************************* UPLOAD *************************************************/

$(document).off('click',tabagent+' #uploadagentmodal-uploadbtn:not(".disabled")').on('click',tabagent+' #uploadagentmodal-uploadbtn:not(".disabled")',function(){

	var modal = '#uploadagentmodal';
	var modal2 = '#agent-uploadtransactionlogmodal';
	var form = '#uploadagentmodal-form';
	var logframe = '#agentuploadtransactionlogframe';
	var button = $(this);
	button.addClass('disabled');

	if($(tabagent+' '+modal+' .uploadagentmodal-file').val().trim()==''){
		say('Please select a file to upload');
		button.removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		
		$(modal).modal('hide');
		$(document).off('hidden.bs.modal',tabagent+' '+modal).on('hidden.bs.modal',tabagent+' '+modal,function(){
			
			$(document).off('hidden.bs.modal',tabagent+' '+modal);
			$(tabagent+' '+modal2).modal('show');
			$(form).submit();

			$(logframe).load(function () {

				button.removeClass('disabled');

				$('#loading-img').addClass('hidden');

				$('#agenttable').flexOptions({
						url:'loadables/ajax/maintenance.agent.php',
						sortname: "created_date",
						sortorder: "desc"
				}).flexReload();

			});

		});
	
	}

});
/************************************* UPLOAD END *************************************************/
