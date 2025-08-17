var contentLDP = "#loadplan-menutabpane";
var inputfieldsLDP = ".loadplan-inputfields";
var processLDP = '';
var currentloadplanTxn = '';

function getWaybillTotalWeightLDP(txnnumber){
	$(contentLDP+' .loadplan-totalactualweight').val(0);
	$(contentLDP+' .loadplan-totalvolweight').val(0);
	$(contentLDP+' .loadplan-totalcbm').val(0);
	$.post(server+'load-plan.php',{getTotalActualWeight:'oiu2OI9kldp39u2o0lfknzzzo92po@k@',txnnumber:txnnumber},function(data){
		
		data = $.parseJSON(data);
		if(data['response']=='success'){
			$(contentLDP+' .loadplan-totalactualweight').val(data['totalweight']);
			$(contentLDP+' .loadplan-totalvolweight').val(data['totalvolweight']);
			$(contentLDP+' .loadplan-totalcbm').val(data['totalcbm']);
			$(contentLDP+' .loadplan-totalnumofpackage').val(data['totalnumofpackage']);
			$(contentLDP+' .loadplan-totalnumofwaybill').val(data['totalnumofwaybill']);
		}


	});

}

function clearLDPNewFields(){
	$(contentLDP+' .newloadplanmodal-documentdate').val('');
	$(contentLDP+' .newloadplanmodal-etd').val('');
	$(contentLDP+' .newloadplanmodal-eta').val('');
	$(contentLDP+' .newloadplanmodal-manifestnumber').val('');
	$(contentLDP+' .newloadplanmodal-mawbbl').val('');
	//$(contentLDP+' .newloadplanmodal-location').empty().trigger('change');
	$(contentLDP+' .newloadplanmodal-origin').empty().trigger('change');
	$(contentLDP+' .newloadplanmodal-destination').empty().trigger('change');
	$(contentLDP+' .newloadplanmodal-modeoftransport').empty().trigger('change');
	$(contentLDP+' .newloadplanmodal-agent').empty().trigger('change');
	$(contentLDP+' .newloadplanmodal-carrier').empty().trigger('change');
	$(contentLDP+' .newloadplanmodal-remarks').val('');
}

$(document).off('click',contentLDP+' #newloadplanmodal-savebtn:not(".disabled")').on('click',contentLDP+' #newloadplanmodal-savebtn:not(".disabled")',function(){

	var location = $(contentLDP+' .newloadplanmodal-location').val();
	var carrier = $(contentLDP+' .newloadplanmodal-carrier').val();
	var origin = $(contentLDP+' .newloadplanmodal-origin').val();
	var destination = $(contentLDP+' .newloadplanmodal-destination').val();
	var modeoftransport = $(contentLDP+' .newloadplanmodal-modeoftransport').val();
	var vehicletype = $(contentLDP+' .newloadplanmodal-vehicletype').val();
	var agent = $(contentLDP+' .newloadplanmodal-agent').val();
	var remarks = $(contentLDP+' .newloadplanmodal-remarks').val();
	var manifestnumber = $(contentLDP+' .newloadplanmodal-manifestnumber').val();
	var documentdate = $(contentLDP+' .newloadplanmodal-documentdate').val();
	var etd = $(contentLDP+' .newloadplanmodal-etd').val();
	var eta = $(contentLDP+' .newloadplanmodal-eta').val();
	var mawbbl = $(contentLDP+' .newloadplanmodal-mawbbl').val();
	var modal = '#'+$(this).closest('.modal').attr('id');
	var button = $(this);
	button.addClass('disabled');

	$(modal+' .modal-errordiv').empty();


	if(location==''||location=='NULL'||location=='null'||location==null){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select location.</div></div>");
		button.removeClass('disabled');
	}
	else if(carrier==''||carrier=='NULL'||carrier=='null'||carrier==null){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select carrier.</div></div>");
		button.removeClass('disabled');
	}
	else if(origin==''||origin=='NULL'||origin=='null'||origin==null){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select origin.</div></div>");
		button.removeClass('disabled');
	}
	else if(destination==''||destination=='NULL'||destination=='null'||destination==null){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select destination.</div></div>");
		button.removeClass('disabled');
	}
	else if(modeoftransport==''||modeoftransport=='NULL'||modeoftransport=='null'||modeoftransport==null){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select mode of transport.</div></div>");
		button.removeClass('disabled');
	}
	else if(vehicletype==''||vehicletype=='NULL'||vehicletype=='null'||vehicletype==null){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select vehicle type.</div></div>");
		button.removeClass('disabled');
	}
	else if(agent==''||agent=='NULL'||agent=='null'||agent==null){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select an agent.</div></div>");
		button.removeClass('disabled');
	}
	else if(documentdate.trim()==''){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide document date.</div></div>");
		button.removeClass('disabled');
	}
	/*else if(etd.trim()==''){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide estimated time of departure.</div></div>");
		button.removeClass('disabled');
	}
	else if(eta.trim()==''){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide estimated time of arrival.</div></div>");
		button.removeClass('disabled');
	}*/
	else if(mawbbl.trim()==''){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide mother BOL number.</div></div>");
		button.removeClass('disabled');
	}
	else{	
		$('#loading-img').removeClass('hidden');
		$.post(server+'load-plan.php',{saveNewLoadPlanTransaction:'oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@',location:location,carrier:carrier,origin:origin,destination:destination,modeoftransport:modeoftransport,agent:agent,remarks:remarks,manifestnumber:manifestnumber,documentdate:documentdate,etd:etd,eta:eta,mawbbl:mawbbl,vehicletype:vehicletype},function(data){
			
			/*alert(data);
			$('#loading-img').addClass('hidden');
				button.removeClass('disabled');*/
			
			data = $.parseJSON(data);
			if(data['response']=='success'){
				$('#loading-img').addClass('hidden');
				button.removeClass('disabled');
				$(modal).modal('hide');
				clearLDPNewFields();
				$(document).off('hidden.bs.modal',modal).on('hidden.bs.modal',modal,function(){
					getLoadPlanInformation(data['txnnumber']);
					$(document).off('hidden.bs.modal',modal);
				});
				


			}
			else if(data['response']=='invaliddocdate'){
				say("Invalid document date");
				button.removeClass('disabled');
				$('#loading-img').addClass('hidden');
			}
			else if(data['response']=='invalidetd'){
				say("Invalid estimated time of departure");
				button.removeClass('disabled');
				$('#loading-img').addClass('hidden');
			}
			else if(data['response']=='invalideta'){
				say("Invalid estimated time of arrival");
				button.removeClass('disabled');
				$('#loading-img').addClass('hidden');
			}
			else{
				alert(data);
				$('#loading-img').addClass('hidden');
				button.removeClass('disabled');
			}

		});

	}


});


/**************************** VIEWING ******************************/
function getLoadPlanInformation(txnnumber){
	processLDP = '';
	//$(contentLDP+' .topbuttonswrapper .button-group').addClass('hidden');
	
	$.post(server+'load-plan.php',{getLoadPlanData:'F#@!3R3ksk#Op1NEi34smo1sonk&$',txnnumber:txnnumber},function(response){
		//alert(response);
		if(response.trim()=='INVALID'){
			//clearAllWB();
			$(contentLDP+' .statusdiv').html('<br>');
			$(contentLDP+" #pgtxnloadplan-id").val('').removeAttr('pgtxnloadplan-number','');
			$(contentLDP+' .topbuttonsdiv').html("<div class='padded-with-border-engraved topbuttonswrapper'><div class='button-group'><div class='button-group-btn active' title='New' id='loadplan-trans-newbtn' data-toggle='modal' href='#newloadplanmodal'><img src='../resources/img/add.png'></div></div></div>");
			currentloadplanTxn='';

			
			$(contentLDP+' #loadplan-waybilltbl').flexOptions({
											url:'loadables/ajax/transactions.load-plan-waybill.php?reference=',
											sortname: 'waybill_number',
											sortorder: "asc"
			}).flexReload();

			userAccess();
			
		}	
		else{
			currentloadplanTxn = txnnumber;
			data = $.parseJSON(response);	


			
			$(contentLDP+" .transactionnumber").val(txnnumber);
			$(contentLDP+" #pgtxnloadplan-id").val(data["id"]).attr('pgtxnloadplan-number',txnnumber);
			$(contentLDP+" .statusdiv").text(data["status"]);
			
			$(contentLDP+" .loadplan-location").val(data["location"]);
			$(contentLDP+" .loadplan-carrier").val(data["carrier"]);
			$(contentLDP+" .loadplan-origin").val(data["origin"]).attr('originid',data["originid"]);
			$(contentLDP+" .loadplan-destination").val(data["destination"]).attr('destinationid',data["destinationid"]);
			$(contentLDP+" .loadplan-modeoftransport").val(data["modeoftransport"]).attr('modeoftransportid',data["modeoftransportid"]);

			$(contentLDP+" .loadplan-vehicletype").val(data["vehicletype"]).attr('vehicletypeid',data["vehicletypeid"]);
			$(contentLDP+" .loadplan-agent").val(data["agent"]);
			$(contentLDP+" .loadplan-remarks").val(data["remarks"]);

			$(contentLDP+" .loadplan-manifestnumber").val(data["manifestnumber"]);
			$(contentLDP+" .loadplan-mawbbl").val(data["mawbbl"]);


			$(contentLDP+" .loadplan-documentdate").val(data["documentdate"]);
			$(contentLDP+" .loadplan-etd").val(data["etd"]);
			$(contentLDP+" .loadplan-eta").val(data["eta"]);

		
			$(contentLDP+" .loadplan-createddate").val(data["createddate"]);
			$(contentLDP+" .loadplan-createdby").val(data["createdby"]);
			$(contentLDP+" .loadplan-updateddate").val(data["updateddate"]);
			$(contentLDP+" .loadplan-updatedby").val(data["updatedby"]);

			$(contentLDP+" .loadplan-statusupdateremarks").val(data["statusupdateremarks"]);

			

			var allowothertrans = '';
			if(data["status"]=="LOGGED"){
				if(data["hasaccess"]=='true'){


					allowothertrans = "<div class='button-group-btn active' title='Edit' id='loadplan-trans-editbtn'><img src='../resources/img/edit.png'></div><div class='button-group-btn active' title='Void' id='loadplan-trans-voidbtn'><img src='../resources/img/block.png'></div><div class='button-group-btn active' title='Post' id='loadplan-trans-postbtn'><img src='../resources/img/post.png'></div>";
				}
				$(contentLDP+" .topbuttonswrapper .button-group").html("<div class='button-group-btn active' title='New' id='loadplan-trans-newbtn' data-toggle='modal' href='#newloadplanmodal'><img src='../resources/img/add.png'></div>"+allowothertrans+"<div class='button-group-btn active' title='Print' id='loadplan-trans-printbtn'><img src='../resources/img/print.png'></div>");
				userAccess();

				$(contentLDP+' .ldpaddwaybillbtn').removeClass('hidden');
				$(contentLDP+' .ldpdeletewaybillbtn').removeClass('hidden');
				$(contentLDP+' .ldpwaybilllookupbtn').removeClass('hidden');

			}
			else if(data["status"]=="POSTED"){
				if(data["hasmanifest"]==0){
					if(data["hasaccess"]=='true'){
						allowothertrans = "<div class='button-group-btn active' title='Unpost' id='loadplan-trans-unpostbtn'><img src='../resources/img/edit.png'></div>";
					
						$(contentLDP+' .ldpaddwaybillbtn').addClass('hidden');
						$(contentLDP+' .ldpdeletewaybillbtn').addClass('hidden');
						$(contentLDP+' .ldpwaybilllookupbtn').addClass('hidden');
					}
					else{
						$(contentLDP+' .ldpaddwaybillbtn').addClass('hidden');
						$(contentLDP+' .ldpdeletewaybillbtn').addClass('hidden');
						$(contentLDP+' .ldpwaybilllookupbtn').addClass('hidden');
					}
					
				}
				else{

					$(contentLDP+' .ldpaddwaybillbtn').addClass('hidden');
					$(contentLDP+' .ldpdeletewaybillbtn').addClass('hidden');
					$(contentLDP+' .ldpwaybilllookupbtn').addClass('hidden');
				}

				$(contentLDP+" .topbuttonswrapper .button-group").html("<div class='button-group-btn active' title='New' id='loadplan-trans-newbtn' data-toggle='modal' href='#newloadplanmodal'><img src='../resources/img/add.png'></div>"+allowothertrans+"<div class='button-group-btn active' title='Print' id='loadplan-trans-printbtn'><img src='../resources/img/print.png'></div>");
				userAccess();

				
			}
			else{

				$(contentLDP+" .topbuttonswrapper .button-group").html("<div class='button-group-btn active' title='New' id='loadplan-trans-newbtn' data-toggle='modal' href='#newloadplanmodal'><img src='../resources/img/add.png'></div>"+allowothertrans+"<div class='button-group-btn active' title='Print' id='loadplan-trans-printbtn'><img src='../resources/img/print.png'></div>");
				userAccess();

				$(contentLDP+' .ldpaddwaybillbtn').addClass('hidden');
				$(contentLDP+' .ldpdeletewaybillbtn').addClass('hidden');
				$(contentLDP+' .ldpwaybilllookupbtn').addClass('hidden');

			}


			$(contentLDP+' #loadplan-waybilltbl').flexOptions({
											url:'loadables/ajax/transactions.load-plan-waybill.php?reference='+txnnumber,
											sortname: 'waybill_number',
											sortorder: "asc"
			}).flexReload();


			getWaybillTotalWeightLDP(txnnumber);

			


		}
		$('#loading-img').addClass('hidden');
		
	});
}

$(document).off('click',contentLDP+" .firstprevnextlastbtn button:not('.disabled')").on('click',contentLDP+" .firstprevnextlastbtn button:not('.disabled')",function(){
	$('#loading-img').removeClass('hidden');
	var source = $(this).data('info'),
		id = $('#pgtxnloadplan-id').val(),
		button = $(this);
		button.addClass('disabled');

	$.post(server+'load-plan.php',{getReference:'FOio5ja3op2a2lK@3#4hh$93s',source:source,id:id},function(data){
		if(data.trim()=='N/A'){
			$('#loading-img').addClass('hidden');
			getLoadPlanInformation('');
		}
		else{
			getLoadPlanInformation(data.trim());
		}
		setTimeout(function(){button.removeClass('disabled');},200);
		
	});
});

$(document).off('keyup',contentLDP+' .transactionnumber').on('keyup',contentLDP+' .transactionnumber',function(e){
	e.preventDefault();
	$('#loading-img').removeClass('hidden');
	var key = e.which||e.keycode,
	    wbmnumber = $(this).val();
	if(key=='13'){
		getLoadPlanInformation(wbmnumber);
	}
	else{
		$('#loading-img').addClass('hidden');
	}
});

/**************************** VIEWING ******************************/




/*************************** WAYBILL ******************************/
$(document).off('shown.bs.modal',contentLDP+' #ldpaddwaybillnumbermodal').on('shown.bs.modal',contentLDP+' #ldpaddwaybillnumbermodal',function(){
	$(this).find(' .ldpaddwaybillnumbermodal-waybillnumber').val('').focus();
	$(this).find(' .modal-errordiv').empty();

});


function ldpinsertnewwaybill(modal){
	var ldpnumber = $(contentLDP+' #pgtxnloadplan-id').attr('pgtxnloadplan-number');
	var wbnumber = $(contentLDP+' #ldpaddwaybillnumbermodal .ldpaddwaybillnumbermodal-waybillnumber').val();
	$(modal+' .modal-errordiv').empty();

	if(wbnumber.trim()==''){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please enter waybill number.</div></div>");
		 $(contentLDP+' #ldpaddwaybillnumbermodal .ldpaddwaybillnumbermodal-waybillnumber').focus();
	}
	else{
		$.post(server+'load-plan.php',{insertNewWaybillNumber:'dskljouioU#ouh$3ksk#Op1NEi34smo1sonk&$',ldpnumber:ldpnumber,wbnumber:wbnumber},function(data){

			if(data.trim()=='success'){


				$(contentLDP+' #loadplan-waybilltbl').flexOptions({
											url:'loadables/ajax/transactions.load-plan-waybill.php?reference='+ldpnumber,
											sortname: 'created_date',
											sortorder: "desc"
				}).flexReload();

				getWaybillTotalWeightLDP(ldpnumber);


				$(contentLDP+' #ldpaddwaybillnumbermodal .ldpaddwaybillnumbermodal-waybillnumber').val('').focus();
			}
			else if(data.trim()=='invalidloadplannumber'){
				$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid load plan number.</div></div>");
			}
			else if(data.trim()=='invalidwaybill'){
				$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid waybill number.</div></div>");
		 		$(contentLDP+' #ldpaddwaybillnumbermodal .ldpaddwaybillnumbermodal-waybillnumber').val('').focus();
			}
			else if(data.trim()=='inanotherloadplan'){
				$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Waybill number already in another load plan transaction.</div></div>");
		 		$(contentLDP+' #ldpaddwaybillnumbermodal .ldpaddwaybillnumbermodal-waybillnumber').val('').focus();
			}
			else if(data.trim()=='alreadyadded'){
				$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Waybill number already added.</div></div>");
		 		$(contentLDP+' #ldpaddwaybillnumbermodal .ldpaddwaybillnumbermodal-waybillnumber').val('').focus();
			}
			else{
				alert(data);
			}

			


		});
	}

}

$(document).off('click',contentLDP+' #ldpaddwaybillnumbermodal-addbtn').on('click',contentLDP+' #ldpaddwaybillnumbermodal-addbtn',function(){
	var modal = '#'+$(this).closest('.modal').attr('id');
	ldpinsertnewwaybill(modal);

});


$(document).off('keyup',contentLDP+' #ldpaddwaybillnumbermodal .ldpaddwaybillnumbermodal-waybillnumber').on('keyup',contentLDP+' #ldpaddwaybillnumbermodal .ldpaddwaybillnumbermodal-waybillnumber',function(e){
	var key = e.keycode||e.which;
	if(key==13){
		var modal = '#'+$(this).closest('.modal').attr('id');
		ldpinsertnewwaybill(modal);
	}

});
/************************* WAYBILL - END ****************************/


/************* POST BTN *********************/
$(document).off('click',contentLDP+' #loadplan-trans-postbtn:not(".disabled")').on('click',contentLDP+' #loadplan-trans-postbtn:not(".disabled")',function(){
	
	var id = $(contentLDP+" #pgtxnloadplan-id").val(),
		txnnumber = $(contentLDP+" #pgtxnloadplan-id").attr("pgtxnloadplan-number"),
		button = $(this);
		button.addClass('disabled');

		$.confirm({
				animation: 'bottom', 
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce:1,
				title: 'Post Transaction',
				content: 'Posting Load Plan Transaction['+txnnumber+']. Do you want to continue?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',	
				confirmButtonClass: 'btn-oceanblue', 
				cancelButtonClass: 'btn-royalblue', 
				theme: 'white', 

				confirm: function(){
						$('#loading-img').removeClass('hidden');
						

						$.post(server+'load-plan.php',{postTransaction:'oiskus49Fnla3#Oih4noiI$IO@Y#*h@o3sk',id:id,txnnumber:txnnumber},function(data){

								rp = $.parseJSON(data);
								if(rp['response']=='success'){
									$('#loading-img').addClass('hidden');
									loadpage(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='loadplan-menutabpane']",'transactions/load-plan.php?reference='+txnnumber);
									button.removeClass('disabled');
								}
								else if(rp['response']=='noaccess'){
									say("No permission to post load plan transaction ["+$txnnumber+"].");
									$('#loading-img').addClass('hidden');
									button.removeClass('disabled');

								}
								else if(rp['response']=='nowaybilladded'){
									say("Please add waybill transaction(s) to update.");
									$('#loading-img').addClass('hidden');
									button.removeClass('disabled');

								}
								else if(rp['response']=='alreadyposted'){
									say("Waybill transaction ["+txnnumber+"] is already posted.");
									getWaybillInformation(txnnumber);
									$('#loading-img').addClass('hidden');
									button.removeClass('disabled');

								}
								else if(rp['response']=='invalidtransaction'){
									say("Unable to post transaction. Invalid load plan number ["+$txnnumber+"].");
									$('#loading-img').addClass('hidden');
									button.removeClass('disabled');

								}
								else{
									alert(data);
									$('#loading-img').addClass('hidden');
									button.removeClass('disabled');
								}
							
						});
				},
				cancel:function(){ 
					button.removeClass('disabled').addClass('active');
				}

		});

});
/************* POST BTN - END *****************/






$(document).off('click',contentLDP+' #loadplan-trans-editbtn').on('click',contentLDP+' #loadplan-trans-editbtn',function(){
	var modal = "#editloadplanmodal";
	var rowid = $(contentLDP+" #pgtxnloadplan-id").val();
	var ldpnumber = $(contentLDP+" #pgtxnloadplan-id").attr('pgtxnloadplan-number');

	$.post(server+'load-plan.php',{LoadPlanGetInfo:'kjoI$H2oiaah3h0$09jDppo92po@k@',id:rowid},function(data){

		rsp = $.parseJSON(data);
		if(rsp['response']=='success'){
			$(modal+' .editloadplanmodal-manifestnumber').val(rsp['manifestnumber']);
			$(modal+' .editloadplanmodal-mawbbl').val(rsp['mawbbl']);
			$(modal+' .editloadplanmodal-documentdate').val(rsp['documentdate']);
			$(modal+' .editloadplanmodal-etd').val(rsp['etd']);
			$(modal+' .editloadplanmodal-eta').val(rsp['eta']);
			$(modal+' .editloadplanmodal-remarks').val(rsp['remarks']);

		    if(rsp["location"]!=null){
		        $(modal+" .editloadplanmodal-location").empty().append('<option selected value="'+rsp["locationid"]+'">'+rsp["location"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .editloadplanmodal-location").empty().trigger('change');
		    }

		    if(rsp["carrier"]!=null){
		        $(modal+" .editloadplanmodal-carrier").empty().append('<option selected value="'+rsp["carrierid"]+'">'+rsp["carrier"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .editloadplanmodal-carrier").empty().trigger('change');
		    }

		    if(rsp["origin"]!=null){
		        $(modal+" .editloadplanmodal-origin").empty().append('<option selected value="'+rsp["originid"]+'">'+rsp["origin"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .editloadplanmodal-origin").empty().trigger('change');
		    }

		    if(rsp["destination"]!=null){
		        $(modal+" .editloadplanmodal-destination").empty().append('<option selected value="'+rsp["destinationid"]+'">'+rsp["destination"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .editloadplanmodal-destination").empty().trigger('change');
		    }

		    if(rsp["modeoftransport"]!=null){
		        $(modal+" .editloadplanmodal-modeoftransport").empty().append('<option selected value="'+rsp["modeoftransportid"]+'">'+rsp["modeoftransport"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .editloadplanmodal-modeoftransport").empty().trigger('change');
		    }

		    if(rsp["vehicletype"]!=null){
		        $(modal+" .editloadplanmodal-vehicletype").empty().append('<option selected value="'+rsp["vehicletypeid"]+'">'+rsp["vehicletype"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .editloadplanmodal-vehicletype").empty().trigger('change');
		    }

		    if(rsp["agent"]!=null){
		        $(modal+" .editloadplanmodal-agent").empty().append('<option selected value="'+rsp["agentid"]+'">'+rsp["agent"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .editloadplanmodal-agent").empty().trigger('change');
		    }

		    $.post(server+'load-plan.php',{getDestinations:'sdfed#n2L1hfi$n#opi3opod30napri',id:ldpnumber},function(data){
				data = $.parseJSON(data);
				var ldpdestination = data["ldpdestination"].split('#@$');


				$('#editloadplanmodal .editloadplanmodal-destination').empty();
				for (var i = 0; i < ldpdestination.length; i++) {
					var ldpdestination1 = ldpdestination[i];
					    ldpdestination1 = ldpdestination1.split('%$&');
					if(ldpdestination1[1]!=null){
						$('#editloadplanmodal .editloadplanmodal-destination').append('<option selected value="'+ldpdestination1[0]+'">'+ldpdestination1[1]+'</option>').trigger('change');
					}
					else{
						$('#editloadplanmodal .editloadplanmodal-destination').empty().trigger('change');
					}

				};
			});


		    $(modal).modal('show');

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



$(document).off('click',contentLDP+' #editloadplanmodal-savebtn:not(".disabled")').on('click',contentLDP+' #editloadplanmodal-savebtn:not(".disabled")',function(){
	var id = $(contentLDP+" #pgtxnloadplan-id").val();
	var loadplannumber = $(contentLDP+" #pgtxnloadplan-id").attr('pgtxnloadplan-number');
	var location = $(contentLDP+' .editloadplanmodal-location').val();
	var carrier = $(contentLDP+' .editloadplanmodal-carrier').val();
	var origin = $(contentLDP+' .editloadplanmodal-origin').val();
	var destination = $(contentLDP+' .editloadplanmodal-destination').val();
	var modeoftransport = $(contentLDP+' .editloadplanmodal-modeoftransport').val();
	var vehicletype = $(contentLDP+' .editloadplanmodal-vehicletype').val();
	var agent = $(contentLDP+' .editloadplanmodal-agent').val();
	var remarks = $(contentLDP+' .editloadplanmodal-remarks').val();
	var manifestnumber = $(contentLDP+' .editloadplanmodal-manifestnumber').val();
	var documentdate = $(contentLDP+' .editloadplanmodal-documentdate').val();
	var etd = $(contentLDP+' .editloadplanmodal-etd').val();
	var eta = $(contentLDP+' .editloadplanmodal-eta').val();
	var mawbbl = $(contentLDP+' .editloadplanmodal-mawbbl').val();
	var modal = '#'+$(this).closest('.modal').attr('id');
	var button = $(this);
	button.addClass('disabled');

	$(modal+' .modal-errordiv').empty();


	if(location==''||location=='NULL'||location=='null'||location==null){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select location.</div></div>");
		button.removeClass('disabled');
	}
	else if(carrier==''||carrier=='NULL'||carrier=='null'||carrier==null){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select carrier.</div></div>");
		button.removeClass('disabled');
	}
	else if(origin==''||origin=='NULL'||origin=='null'||origin==null){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select origin.</div></div>");
		button.removeClass('disabled');
	}
	else if(destination==''||destination=='NULL'||destination=='null'||destination==null){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select destination.</div></div>");
		button.removeClass('disabled');
	}
	else if(modeoftransport==''||modeoftransport=='NULL'||modeoftransport=='null'||modeoftransport==null){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select mode of transport.</div></div>");
		button.removeClass('disabled');
	}
	else if(vehicletype==''||vehicletype=='NULL'||vehicletype=='null'||vehicletype==null){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select vehicle type.</div></div>");
		button.removeClass('disabled');
	}
	else if(agent==''||agent=='NULL'||agent=='null'||agent==null){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select an agent.</div></div>");
		button.removeClass('disabled');
	}
	else if(documentdate.trim()==''){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide document date.</div></div>");
		button.removeClass('disabled');
	}
	/*else if(etd.trim()==''){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide estimated time of departure.</div></div>");
		button.removeClass('disabled');
	}
	else if(eta.trim()==''){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide estimated time of arrival.</div></div>");
		button.removeClass('disabled');
	}*/
	else if(mawbbl.trim()==''){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide master waybill/bill of lading number.</div></div>");
		button.removeClass('disabled');
	}
	else{	
		$('#loading-img').removeClass('hidden');
		$.post(server+'load-plan.php',{saveEditLoadPlanTransaction:'oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@',id:id,location:location,carrier:carrier,origin:origin,destination:destination,modeoftransport:modeoftransport,agent:agent,remarks:remarks,manifestnumber:manifestnumber,documentdate:documentdate,etd:etd,eta:eta,mawbbl:mawbbl,vehicletype:vehicletype,loadplannumber:loadplannumber},function(data){
			
			/*alert(data);
			$('#loading-img').addClass('hidden');
				button.removeClass('disabled');*/
			
			data = $.parseJSON(data);
			if(data['response']=='success'){
				$('#loading-img').addClass('hidden');
				button.removeClass('disabled');
				$(modal).modal('hide');
				clearLDPNewFields();
				$(document).off('hidden.bs.modal',modal).on('hidden.bs.modal',modal,function(){
					getLoadPlanInformation(data['txnnumber']);
					$(document).off('hidden.bs.modal',modal);
				});
				


			}
			else if(data['response']=='invaliddocdate'){
				say("Invalid document date");
				button.removeClass('disabled');
				$('#loading-img').addClass('hidden');
			}
			else if(data['response']=='invalidetd'){
				say("Invalid estimated time of departure");
				button.removeClass('disabled');
				$('#loading-img').addClass('hidden');
			}
			else if(data['response']=='invalideta'){
				say("Invalid estimated time of arrival");
				button.removeClass('disabled');
				$('#loading-img').addClass('hidden');
			}
			else{
				alert(data);
				$('#loading-img').addClass('hidden');
				button.removeClass('disabled');
			}

		});

	}


});


/************************* VOID BTN ***********************/
$(document).off('click',contentLDP+' #loadplan-trans-voidbtn:not(".disabled")').on('click',contentLDP+' #loadplan-trans-voidbtn:not(".disabled")',function(){

	var modal = '#voidloadplantransactionmodal';
	var txnid = $(contentLDP+' #pgtxnloadplan-id').val();
	var txnnumber = $(contentLDP+' #pgtxnloadplan-id').attr('pgtxnloadplan-number');

	$(contentLDP+' #voidloadplantransactionmodal-id').val(txnid);
	$(contentLDP+' .voidloadplantransactionmodal-txnnumber').val(txnnumber);
	
	$(modal).modal('show');
	$(document).off('shown.bs.modal',modal).on('shown.bs.modal',modal,function(){
		$(document).off('shown.bs.modal',modal);
		$(contentLDP+' .voidloadplantransactionmodal-remarks').focus();
	});

});

$(document).off('click',contentLDP+' #voidloadplantransactionmodal-savebtn:not(".disabled")').on('click',contentLDP+' #voidloadplantransactionmodal-savebtn:not(".disabled")',function(){

	var modal = '#'+$(this).closest('.modal').attr('id');
	var txnid = $(contentLDP+' #pgtxnloadplan-id').val();
	var txnnumber = $(contentLDP+' #pgtxnloadplan-id').attr('pgtxnloadplan-number');
	var remarks = $(modal+' .voidloadplantransactionmodal-remarks').val();
	var button = $(this);
	button.addClass('disabled');

	$(modal+' .modal-errordiv').empty();

	if(remarks.trim()==''){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide reason for cancellation.</div></div>");
		$(modal+' .voidloadplantransactionmodal-remarks').focus();
		button.removeClass('disabled');
	}
	else{
		$.confirm({
				animation: 'bottom', 
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce:1,
				title: 'Void Load Plan ['+txnnumber+']',
				content: 'Do you want to continue?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',	
				confirmButtonClass: 'btn-oceanblue', 
				cancelButtonClass: 'btn-royalblue', 
				theme: 'white', 

				confirm: function(){
					$('#loading-img').removeClass('hidden');
					$.post(server+'load-plan.php',{voidTransaction:'dROi$nsFpo94dnels$4sRoi809srbmouS@1!',txnid:txnid,txnnumber:txnnumber,remarks:remarks},function(data){


						if(data.trim()=='success'){
							$(modal).modal('hide');
							$(document).off('hidden.bs.modal',modal).on('hidden.bs.modal',modal,function(){
								$(document).off('hidden.bs.modal',modal);
								
								$(modal+' #voidloadplantransactionmodal-id').val('');
								$(modal+' .voidloadplantransactionmodal-txnnumber').val('');
								$(modal+' .voidloadplantransactionmodal-remarks').val('');

								getLoadPlanInformation(txnnumber);
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							});
						}
						else if(data.trim()=='invalidtransaction'){
							say("Unable to void Load Plan transaction. Invalid Load Plan ID/No. ID: "+txnid+" - Load Plan No.: "+txnnumber);
							button.removeClass('disabled');
							$('#loading-img').addClass('hidden');
						}
						else{
							alert(data);
							button.removeClass('disabled');
							$('#loading-img').addClass('hidden');
						}
					});
				},
				cancel: function(){
					button.removeClass('disabled');
				}
		});
	}

});

/********************** VOID BTN - END ********************/



$(document).off('show.bs.modal',contentLDP+' #ldpsearchwaybilltransactionmodal').on('show.bs.modal',contentLDP+' #ldpsearchwaybilltransactionmodal',function(){
	var origin = $(contentLDP+' .loadplan-origin').attr('originid');
	var destination = $(contentLDP+' .loadplan-destination').attr('destinationid');
	var mode = $(contentLDP+' .loadplan-modeoftransport').attr('modeoftransportid');
	var ldpnunmber = $(contentLDP+' #pgtxnloadplan-id').attr('pgtxnloadplan-number');
	//alert('sdfsdf');
	$(contentLDP+' #ldpsearchwaybilltransactiontbl').flexOptions({
		url:'loadables/ajax/transactions.load-plan-waybill-lookup.php?origin='+origin+'&destination='+destination+'&mode='+mode+'&loadplannumber='+ldpnunmber,
		sortname: 'waybill_number',
		sortorder: "desc",
		rp: 10,
	    useRp: true
	}).flexReload();

});


$(document).off('click',contentLDP+' .ldpwaybilllookup-addbtn:not(".disabled")').on('click',contentLDP+' .ldpwaybilllookup-addbtn:not(".disabled")',function(){

	var modal = '#'+$(this).closest('.modal').attr('id');
	var txnid = $(contentLDP+' #pgtxnloadplan-id').val();
	var txnnumber = $(contentLDP+' #pgtxnloadplan-id').attr('pgtxnloadplan-number');
	var waybillcount = $(modal+' .ldpwaybilllookup-checkbox:checked').length;
	var waybills = [];
	var button = $(this);
	button.addClass('disabled');

	if(waybillcount>0){

		$(modal+' .ldpwaybilllookup-checkbox:checked').each(function(){
			waybills.push($(this).attr('waybillnumber'));
		});

		$.post(server+'load-plan.php',{insertMultipleWaybillNumber:'oihh#p@0fldpe3ksk#Op1NEi34smo1sonk&$',ldpnumber:txnnumber,wbnumber:waybills},function(data){

			data = $.parseJSON(data);
			if(data['response']=='success'){
				$(modal).modal('hide');
				$(modal).off('hidden.bs.modal').on('hidden.bs.modal',function(){
					$(modal).off('hidden.bs.modal');
					button.removeClass('disabled');
					$(contentLDP+' #loadplan-waybilltbl').flexOptions({
											url:'loadables/ajax/transactions.load-plan-waybill.php?reference='+txnnumber,
											sortname: 'created_date',
											sortorder: "desc"
					}).flexReload();
					getWaybillTotalWeightLDP(txnnumber);

				});

			}
			else if(data['response']=='invalidloadplannumber'){
				say("Invalid load plan number. <br> Load Plan: "+txnnumber);
				button.removeClass('disabled');
			}
			else if(data['response']=='unabletoallselectedwaybills'){
				say("Invalid Waybill(s): "+data['invaliddetails']+"<br><br>In Another Load Plan: "+data['inanotherldpdetails']);
				button.removeClass('disabled');
			}
			else{
				button.removeClass('disabled');
			}



		});


	}
	else{
		say("Please select waybill number(s) to be added");
		button.removeClass('disabled');
	}




});




/************************* PRINTING *****************************************/
$(document).off('click',contentLDP+' #loadplan-trans-printbtn').on('click',contentLDP+' #loadplan-trans-printbtn',function(){
	var title = 'Print Preview ['+ $('#pgtxnloadplan-id').attr('pgtxnloadplan-number')+']';
	var tabid = $('#pgtxnloadplan-id').attr('pgtxnloadplan-number');

	if($(".content>.content-tab-pane .content-tabs").find("li[data-pane='#"+tabid+"tabpane']").length>=1){
		$(".content>.content-tab-pane .content-tabs>li[data-pane='#"+tabid+"tabpane']").remove();
		$(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='"+tabid+"tabpane']").remove();
		$('#loading-img').removeClass('hidden');
		$('.content').animate({scrollTop:0},300);

		$('.content>.content-tab-pane .content-tabs>li').removeClass('active');
		$('.content>.content-tab-pane .content-pane-wrapper>.content-pane').removeClass('active');
		$('.content>.content-tab-pane .content-tabs').append("<li data-pane='#"+tabid+"tabpane' class='active'>"+title+"<i class='fa fa-remove'></i></li>");
		$('.content>.content-tab-pane .content-pane-wrapper').append("<div class='content-pane active' id='"+tabid+"tabpane'></div>");
		$('.content>.content-tab-pane .content-pane-wrapper>.content-pane:last-child').load("Printouts/print-preview.php?source=printouts/transactions/load-plan.php?txnnumber="+tabid+'&reference='+tabid);
		setTimeout(function(){
			$('#loading-img').addClass('hidden');
		},400);
	}
	else{
		$('#loading-img').removeClass('hidden');
		$('.content').animate({scrollTop:0},300);

		$('.content>.content-tab-pane .content-tabs>li').removeClass('active');
		$('.content>.content-tab-pane .content-pane-wrapper>.content-pane').removeClass('active');
		$('.content>.content-tab-pane .content-tabs').append("<li data-pane='#"+tabid+"tabpane' class='active'>"+title+"<i class='fa fa-remove'></i></li>");
		$('.content>.content-tab-pane .content-pane-wrapper').append("<div class='content-pane active' id='"+tabid+"tabpane'></div>");
		$('.content>.content-tab-pane .content-pane-wrapper>.content-pane:last-child').load("Printouts/print-preview.php?source=printouts/transactions/load-plan.php?txnnumber="+tabid+'&reference='+tabid);
		setTimeout(function(){
			$('#loading-img').addClass('hidden');
		},400);
	}
});
/************************* PRINTING - END *****************************************/



$(document).off('show.bs.modal',contentLDP+' #newloadplanmodal').on('show.bs.modal',contentLDP+' #newloadplanmodal',function(){

	var modal = '#newloadplanmodal';

	$.post("../config/post-functions.php",{getDefaultUserLocation:'Fns!oi3ah434ad#2l211#$*3%'},function(data){
		
			data = $.parseJSON(data);
			if(data["locdesc"]!=null){
		        $(modal+" .newloadplanmodal-location").empty().append('<option selected value="'+data["locid"]+'">'+data["loccode"]+' - '+data["locdesc"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .newloadplanmodal-location").empty().trigger('change');
		    }
		
	});


});






$(document).off('click',contentLDP+' #loadplan-trans-unpostbtn:not(".disabled")').on('click',contentLDP+' #loadplan-trans-unpostbtn:not(".disabled")',function(){

	var modal = '#unpostloadplantransactionmodal';
	var txnid = $(contentLDP+' #pgtxnloadplan-id').val();
	var txnnumber = $(contentLDP+' #pgtxnloadplan-id').attr('pgtxnloadplan-number');

	$(contentLDP+' #unpostloadplantransactionmodal-id').val(txnid);
	$(contentLDP+' .unpostloadplantransactionmodal-txnnumber').val(txnnumber);
	
	$(modal).modal('show');
	$(document).off('shown.bs.modal',modal).on('shown.bs.modal',modal,function(){
		$(document).off('shown.bs.modal',modal);
		$(contentLDP+' .unpostloadplantransactionmodal-remarks').focus();
	});

});

$(document).off('click',contentLDP+' #unpostloadplantransactionmodal-savebtn:not(".disabled")').on('click',contentLDP+' #unpostloadplantransactionmodal-savebtn:not(".disabled")',function(){

	var modal = '#'+$(this).closest('.modal').attr('id');
	var txnid = $(contentLDP+' #pgtxnloadplan-id').val();
	var txnnumber = $(contentLDP+' #pgtxnloadplan-id').attr('pgtxnloadplan-number');
	var remarks = $(modal+' .unpostloadplantransactionmodal-remarks').val();
	var button = $(this);
	button.addClass('disabled');

	$(modal+' .modal-errordiv').empty();

	if(remarks.trim()==''){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide reason for unposting.</div></div>");
		$(modal+' .unpostloadplantransactionmodal-remarks').focus();
		button.removeClass('disabled');
	}
	else{
		$.confirm({
				animation: 'bottom', 
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce:1,
				title: 'Unpost Load Plan ['+txnnumber+']',
				content: 'Do you want to continue?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',	
				confirmButtonClass: 'btn-oceanblue', 
				cancelButtonClass: 'btn-royalblue', 
				theme: 'white', 

				confirm: function(){
					$('#loading-img').removeClass('hidden');
					$.post(server+'load-plan.php',{unpostTransaction:'dROi$nsFpo94dnels$4sRoi809srbmouS@1!',txnid:txnid,txnnumber:txnnumber,remarks:remarks},function(data){


						if(data.trim()=='success'){
							$(modal).modal('hide');
							$(document).off('hidden.bs.modal',modal).on('hidden.bs.modal',modal,function(){
								$(document).off('hidden.bs.modal',modal);
								
								$(modal+' #unpostloadplantransactionmodal-id').val('');
								$(modal+' .unpostloadplantransactionmodal-txnnumber').val('');
								$(modal+' .unpostloadplantransactionmodal-remarks').val('');

								getLoadPlanInformation(txnnumber);
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							});
						}
						else if(data.trim()=='invalidtransaction'){
							say("Unable to unpost load plan transaction. Invalid Load Plan ID/No. ID: "+txnid+" - Load Plan No.: "+txnnumber);
							button.removeClass('disabled');
							$('#loading-img').addClass('hidden');
						}
						else if(data.trim()=='hasmanifest'){
							$(modal).modal('hide');
							$(document).off('hidden.bs.modal',modal).on('hidden.bs.modal',modal,function(){
								$(document).off('hidden.bs.modal',modal);
								
								$(modal+' #unpostloadplantransactionmodal-id').val('');
								$(modal+' .unpostloadplantransactionmodal-txnnumber').val('');
								$(modal+' .unpostloadplantransactionmodal-remarks').val('');

								say("Unable to unpost load plan. Load Plan No. ["+txnnumber+"] has corresponding manifest transaction.");
								$(contentLDP+' #loadplan-trans-unpostbtn').remove();
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							});
							
						}
						else{
							alert(data);
							button.removeClass('disabled');
							$('#loading-img').addClass('hidden');
						}
					});
				},
				cancel: function(){
					button.removeClass('disabled');
				}
		});
	}

});


/************************* SEARCHING ***********************************/


$(document).off('dblclick',contentLDP+' .loadplansearchrow').on('dblclick',contentLDP+' .loadplansearchrow',function(){
	var modal = '#'+$(this).closest('.modal').attr('id');
	var loadplannumber = $(this).attr('loadplannumber');
	$(modal).modal('hide');
	$(document).off('hidden.bs.modal',modal).on('hidden.bs.modal',modal,function(){
		$(document).off('hidden.bs.modal',modal);
		getLoadPlanInformation(loadplannumber);
	});

});


function searchLoadPlanLookup(modal){
	var status = $(modal+' .loadplansearch-status').val(),
		manifestnumber = $(modal+' .loadplansearch-manifestnumber').val(),
		waybillnumber = $(modal+' .loadplansearch-waybillnumber').val().replace(/\s/g,'%20'),
		mawbl = $(modal+' .loadplansearch-mawbl').val().replace(/\s/g,'%20'),
		location = $(modal+' .loadplansearch-location').val(),
		origin = $(modal+' .loadplansearch-origin').val(),
		destination = $(modal+' .loadplansearch-destination').val(),
		agent = $(modal+' .loadplansearch-agent').val(),
	    mode = $(modal+' .loadplansearch-mode').val(),
	    carrier = $(modal+' .loadplansearch-carrier').val(),
	    vehicletype = $(modal+' .loadplansearch-vehicletype').val(),
	    docdatefrom = $(modal+' .loadplansearch-docdatefrom').val(),
	    docdateto = $(modal+' .loadplansearch-docdateto').val();
	    

	   $(contentLDP+' #loadplansearch-table').flexOptions({
											url:'loadables/ajax/transactions.load-plan-search.php?status='+status+'&manifestnumber='+manifestnumber+'&location='+location+'&destination='+destination+'&origin='+origin+'&mode='+mode+'&agent='+agent+'&carrier='+carrier+'&vehicletype='+vehicletype+'&waybillnumber='+waybillnumber+'&docdatefrom='+docdatefrom+'&docdateto='+docdateto+"&mawbl="+mawbl,
											sortname: 'load_plan_number',
											sortorder: "asc"
		}).flexReload(); 

	

}

$(document).on('keyup',contentLDP+" #loadplan-searchmodallookup .loadplansearch-docdatefrom,"+contentLDP+" #loadplan-searchmodallookup .loadplansearch-docdateto, "+contentLDP+" #loadplan-searchmodallookup .loadplansearch-waybillnumber, "+contentLDP+" #loadplan-searchmodallookup .loadplansearch-mawbl, "+contentLDP+" #loadplan-searchmodallookup .loadplansearch-manifestnumber",function(e){
	var key = e.which||e.keycode;
	if(key=='13'){
		var modal = "#"+$(this).closest('.modal').attr('id');
		searchLoadPlanLookup(modal);
	}
});



$(document).off('click',contentLDP+' #loadplansearch-searchbtn:not(".disabled")').on('click',contentLDP+' #loadplansearch-searchbtn:not(".disabled")',function(){


	var modal = "#"+$(this).closest('.modal').attr('id');
	searchLoadPlanLookup(modal);


});







/************************** SEARCHING - END ********************************/