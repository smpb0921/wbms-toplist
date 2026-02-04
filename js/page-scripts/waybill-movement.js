var contentWBM = "#waybillmovement-menutabpane";
var inputfieldsWBM = ".waybillmovement-inputfields";
var processWBM = '';
var currentWaybillMovementTxn = '';

function clearWBMNewFields(){
	$(contentWBM+' .newwaybillmovementmodal-documentdate').val('');
	$(contentWBM+' .newwaybillmovementmodal-location').empty().trigger('change');
	$(contentWBM+' .newwaybillmovementmodal-remarks').val('');
}

function clearAllWBM(){
	$(inputfieldsWBM+' input.form-control:not(".transactionnumber")').val('');
	$(inputfieldsWBM+' textarea.form-control').text('').val('');
	$(inputfieldsWBM+' .header-errordiv').empty();
	$(inputfieldsWBM+' .detail-errordiv').empty();
	$(contentWBM+' .statusdiv').html('<br>');

	$(contentWBM+' .wbmaddwaybillbtn').addClass('hidden');
	$(contentWBM+' .wbmdeletewaybillbtn').addClass('hidden');
	$(contentWBM+' .wbmwaybilllookupbtn').addClass('hidden');
	$(contentWBM+' .wbmaddpackagebtn').addClass('hidden');
	$(contentWBM+' .wbmdeletepackagebtn').addClass('hidden');

}


/************* POST BTN *********************/
$(document).off('click',contentWBM+' #waybillmovement-trans-postbtn:not(".disabled")').on('click',contentWBM+' #waybillmovement-trans-postbtn:not(".disabled")',function(){
	
	var id = $(contentWBM+" #pgtxnwaybillmovement-id").val(),
		txnnumber = $(contentWBM+" #pgtxnwaybillmovement-id").attr("pgtxnwaybillmovement-number"),
		button = $(this);
		button.addClass('disabled');

		$.confirm({
				animation: 'bottom', 
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce:1,
				title: 'Post Transaction',
				content: 'Posting Waybill Transaction['+txnnumber+']. Do you want to continue?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',	
				confirmButtonClass: 'btn-oceanblue', 
				cancelButtonClass: 'btn-royalblue', 
				theme: 'white', 

				confirm: function(){
						$('#loading-img').removeClass('hidden');
						

						$.post(server+'waybill-movement.php',{postWaybillMovementTransaction:'oiskus49Fnla3#Oih4noiI$IO@Y#*h@o3sk',id:id,txnnumber:txnnumber},function(data){

								rp = $.parseJSON(data);
								if(rp['response']=='success'){
									$('#loading-img').addClass('hidden');
									loadpage(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='waybillmovement-menutabpane']",'transactions/waybill-movement.php?reference='+txnnumber);
									button.removeClass('disabled');
								}
								else if(rp['response']=='noaccess'){
									say("No permission to post waybill transaction ["+$txnnumber+"].");
									$('#loading-img').addClass('hidden');
									button.removeClass('disabled');

								}
								else if(rp['response']=='nowaybilladded'){
									say("Please add waybill transaction(s) to be updated.");
									$('#loading-img').addClass('hidden');
									button.removeClass('disabled');

								}
								else if(rp['response']=='alreadyposted'){
									say("Waybill transaction ["+txnnumber+"] is already posted.");
									getWaybillMovementInformation(txnnumber);
									$('#loading-img').addClass('hidden');
									button.removeClass('disabled');

								}
								else if(rp['response']=='invalidtransaction'){
									say("Unable to post transaction. Invalid waybill movement number ["+$txnnumber+"].");
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


$(document).off('click',contentWBM+' #newwaybillmovementmodal-savebtn:not(".disabled")').on('click',contentWBM+' #newwaybillmovementmodal-savebtn:not(".disabled")',function(){

	var documentdate = $(contentWBM+' .newwaybillmovementmodal-documentdate').val();
	var location = $(contentWBM+' .newwaybillmovementmodal-location').val();
	var movementtype = $(contentWBM+' .newwaybillmovementmodal-movementtype').val();
	var shipmenttype = $(contentWBM+' .newwaybillmovementmodal-shipmenttype').val();
	var remarks = $(contentWBM+' .newwaybillmovementmodal-remarks').val();
	var modal = '#'+$(this).closest('.modal').attr('id');
	var button = $(this);
	button.addClass('disabled');

	$(modal+' .modal-errordiv').empty();
	if(movementtype==''||movementtype=='NULL'||movementtype=='null'||movementtype==null){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select movement type.</div></div>");
		button.removeClass('disabled');
	}
	else if(shipmenttype==''||shipmenttype=='NULL'||shipmenttype=='null'||shipmenttype==null){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a shipment type.</div></div>");
		button.removeClass('disabled');
	}
	else if(location==''||location=='NULL'||location=='null'||location==null){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a location.</div></div>");
		button.removeClass('disabled');
	}
	else{	
		$('#loading-img').removeClass('hidden');
		$.post(server+'waybill-movement.php',{saveNewWaybillMovementTransaction:'oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@',documentdate:documentdate,location:location,remarks:remarks,movementtype:movementtype,shipmenttype:shipmenttype},function(data){
		
			data = $.parseJSON(data);
			if(data['response']=='success'){
				$('#loading-img').addClass('hidden');
				button.removeClass('disabled');
				$(modal).modal('hide');
				clearWBMNewFields();
				$(document).off('hidden.bs.modal',modal).on('hidden.bs.modal',modal,function(){
					getWaybillMovementInformation(data['txnnumber']);
					$(document).off('hidden.bs.modal',modal);
				});
				


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
function getWaybillMovementInformation(txnnumber){
	processWBM = '';
	
	$.post(server+'waybill-movement.php',{getWaybillMovementData:'F#@!3R3ksk#Op1NEi34smo1sonk&$',txnnumber:txnnumber},function(response){
		//alert(response);
		if(response.trim()=='INVALID'){
			clearAllWBM();
			$(contentWBM+' .statusdiv').html('<br>');
			$(contentWBM+" #pgtxnwaybillmovement-id").val('').removeAttr('pgtxnwaybillmovement-number','');
			$(contentWBM+' .topbuttonsdiv').html("<div class='padded-with-border-engraved topbuttonswrapper'><div class='button-group'><div class='button-group-btn active' title='New' id='waybillmovement-trans-newbtn' data-toggle='modal' href='#newwaybillmovementmodal'><img src='../resources/img/add.png'></div></div></div>");
			currentWaybillMovementTxn='';

			
			

			clearAllWBM();
			$(contentWBM+' #waybillmovement-waybilltbl').flexOptions({
											url:'loadables/ajax/transactions.waybill-movement-waybill.php?wbmnumber=',
											sortname: 'waybill_number',
											sortorder: "asc"
			}).flexReload();

			$(contentWBM+' #waybillmovement-packagecodetbl').flexOptions({
											url:'loadables/ajax/transactions.waybill-movement-package-code.php?wbmnumber=',
											sortname: 'waybill_number asc, package_code',
											sortorder: "asc"
			}).flexReload();


			userAccess();

		}
		else{
			currentWaybillMovementTxn = txnnumber;
			data = $.parseJSON(response);	


			$(contentWBM+' .wbmaddwaybillbtn').removeClass('hidden');
			$(contentWBM+' .wbmdeletewaybillbtn').removeClass('hidden');
			$(contentWBM+' .wbmwaybilllookupbtn').removeClass('hidden');
			$(contentWBM+' .wbmaddpackagebtn').removeClass('hidden');
			$(contentWBM+' .wbmdeletepackagebtn').removeClass('hidden');

			$(contentWBM+" .transactionnumber").val(txnnumber);
			$(contentWBM+" #pgtxnwaybillmovement-id").val(data["id"]).attr('pgtxnwaybillmovement-number',txnnumber);
			$(contentWBM+" .statusdiv").text(data["status"]);
			
			$(contentWBM+" .waybillmovement-movementtype").val(data["movementtype"]);
			$(contentWBM+" .waybillmovement-shipmenttype").val(data["shipmenttype"]);
			$(contentWBM+" .waybillmovement-location").val(data["location"]);
			$(contentWBM+" .waybillmovement-remarks").val(data["remarks"]);
			$(contentWBM+" .waybillmovement-documentdate").val(data["documentdate"]);

		
			$(contentWBM+" .waybillmovement-createddate").val(data["createddate"]);
			$(contentWBM+" .waybillmovement-createdby").val(data["createdby"]);
			$(contentWBM+" .waybillmovement-updateddate").val(data["updateddate"]);
			$(contentWBM+" .waybillmovement-updatedby").val(data["updatedby"]);

		

			var allowothertrans = '';
			if(data["status"]=="LOGGED"){
				if(data["hasaccess"]=='true'){

					//<div class='button-group-btn active' title='Edit' id='waybillmovement-trans-editbtn'><img src='../resources/img/edit.png'></div>
					allowothertrans = "<div class='button-group-btn active' title='Void' id='waybillmovement-trans-voidbtn'><img src='../resources/img/block.png'></div><div class='button-group-btn active' title='Post' id='waybillmovement-trans-postbtn'><img src='../resources/img/post.png'></div>";
				}
				$(contentWBM+" .topbuttonswrapper .button-group").html("<div class='button-group-btn active' title='New' id='waybillmovement-trans-newbtn' data-toggle='modal' href='#newwaybillmovementmodal'><img src='../resources/img/add.png'></div>"+allowothertrans+"<div class='button-group-btn active' title='Print' id='waybillmovement-trans-printbtn'><img src='../resources/img/print.png'></div>");
				

				$(contentWBM+' .wbmaddwaybillbtn').removeClass('hidden');
				$(contentWBM+' .wbmdeletewaybillbtn').removeClass('hidden');
				$(contentWBM+' .wbmwaybilllookupbtn').removeClass('hidden');
				$(contentWBM+' .wbmaddpackagebtn').removeClass('hidden');
				$(contentWBM+' .wbmdeletepackagebtn').removeClass('hidden');
				userAccess();
			}
			else{

				$(contentWBM+" .topbuttonswrapper .button-group").html("<div class='button-group-btn active' title='New' id='waybillmovement-trans-newbtn' data-toggle='modal' href='#newwaybillmovementmodal'><img src='../resources/img/add.png'></div>"+allowothertrans+"<div class='button-group-btn active' title='Print' id='waybillmovement-trans-printbtn'><img src='../resources/img/print.png'></div>");
				

				$(contentWBM+' .wbmaddwaybillbtn').addClass('hidden');
				$(contentWBM+' .wbmdeletewaybillbtn').addClass('hidden');
				$(contentWBM+' .wbmwaybilllookupbtn').addClass('hidden');
				$(contentWBM+' .wbmaddpackagebtn').addClass('hidden');
				$(contentWBM+' .wbmdeletepackagebtn').addClass('hidden');
				userAccess();
			}


			$(contentWBM+' #waybillmovement-waybilltbl').flexOptions({
											url:'loadables/ajax/transactions.waybill-movement-waybill.php?wbmnumber='+txnnumber,
											sortname: 'waybill_number',
											sortorder: "asc"
			}).flexReload();

			$(contentWBM+' #waybillmovement-packagecodetbl').flexOptions({
											url:'loadables/ajax/transactions.waybill-movement-package-code.php?wbmnumber='+txnnumber,
											sortname: 'waybill_number asc, package_code',
											sortorder: "asc"
			}).flexReload();



		}
		$('#loading-img').addClass('hidden');
		
	});
}

$(document).off('click',contentWBM+" .firstprevnextlastbtn button:not('.disabled')").on('click',contentWBM+" .firstprevnextlastbtn button:not('.disabled')",function(){
	$('#loading-img').removeClass('hidden');
	var source = $(this).data('info'),
		id = $('#pgtxnwaybillmovement-id').val(),
		button = $(this);
		button.addClass('disabled');

	$.post(server+'waybill-movement.php',{getReference:'FOio5ja3op2a2lK@3#4hh$93s',source:source,id:id},function(data){
		if(data.trim()=='N/A'){
			$('#loading-img').addClass('hidden');
			getWaybillMovementInformation('');
		}
		else{
			getWaybillMovementInformation(data.trim());
		}
		setTimeout(function(){button.removeClass('disabled');},200);
		
	});
});

$(document).off('keyup',contentWBM+' .transactionnumber').on('keyup',contentWBM+' .transactionnumber',function(e){
	e.preventDefault();
	$('#loading-img').removeClass('hidden');
	var key = e.which||e.keycode,
	    wbmnumber = $(this).val();
	if(key=='13'){
		getWaybillMovementInformation(wbmnumber);
	}
	else{
		$('#loading-img').addClass('hidden');
	}
});

/**************************** VIEWING ******************************/





/*************************** WAYBILL ******************************/
$(document).off('shown.bs.modal',contentWBM+' #wbmaddwaybillnumbermodal').on('shown.bs.modal',contentWBM+' #wbmaddwaybillnumbermodal',function(){
	$(this).find(' .wbmaddwaybillnumbermodal-waybillnumber').val('').focus();
	$(this).find(' .modal-errordiv').empty();

});


function wbminsertnewwaybill(modal){
	var wbmnumber = $(contentWBM+' #pgtxnwaybillmovement-id').attr('pgtxnwaybillmovement-number');
	var wbnumber = $(contentWBM+' #wbmaddwaybillnumbermodal .wbmaddwaybillnumbermodal-waybillnumber').val();
	$(modal+' .modal-errordiv').empty();

	if(wbnumber.trim()==''){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please enter waybill number.</div></div>");
		 $(contentWBM+' #wbmaddwaybillnumbermodal .wbmaddwaybillnumbermodal-waybillnumber').focus();
	}
	else{
		$.post(server+'waybill-movement.php',{insertNewWaybillNumber:'dskljouioU#ouh$3ksk#Op1NEi34smo1sonk&$',wbmnumber:wbmnumber,wbnumber:wbnumber},function(data){

			if(data.trim()=='success'){


				$(contentWBM+' #waybillmovement-waybilltbl').flexOptions({
											url:'loadables/ajax/transactions.waybill-movement-waybill.php?wbmnumber='+wbmnumber,
											sortname: 'created_date',
											sortorder: "desc"
				}).flexReload();

				$(contentWBM+' #waybillmovement-packagecodetbl').flexOptions({
												url:'loadables/ajax/transactions.waybill-movement-package-code.php?wbmnumber='+wbmnumber,
												sortname: 'created_date desc, package_code',
												sortorder: "asc"
				}).flexReload();


				$(contentWBM+' #wbmaddwaybillnumbermodal .wbmaddwaybillnumbermodal-waybillnumber').val('').focus();
			}
			else if(data.trim()=='invalidwaybillmovement'){

			}
			else if(data.trim()=='invalidwaybill'){
				$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid waybill number.</div></div>");
		 		$(contentWBM+' #wbmaddwaybillnumbermodal .wbmaddwaybillnumbermodal-waybillnumber').val('').focus();
			}
			else if(data.trim()=='txnalreadyposted'){
				$(modal).modal('hide');
				$(modal).off('hidden.bs.modal').on('hidden.bs.modal',function(){
					$(modal).off('hidden.bs.modal');
					$(contentWBM+' #wbmaddwaybillnumbermodal .wbmaddwaybillnumbermodal-waybillnumber').val('').focus();
					$(modal+' .modal-errordiv').empty();
					getWaybillMovementInformation(wbmnumber);
					say("Unable to insert waybill. Waybill Movement transaction is already posted.");
				});
				
		 		
			}
			else if(data.trim()=='alreadyadded'){
				$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Waybill number already added.</div></div>");
		 		$(contentWBM+' #wbmaddwaybillnumbermodal .wbmaddwaybillnumbermodal-waybillnumber').val('').focus();
			}
			else{
				alert(data);
			}

			


		});
	}

}

$(document).off('click',contentWBM+' #wbmaddwaybillnumbermodal-addbtn').on('click',contentWBM+' #wbmaddwaybillnumbermodal-addbtn',function(){
	var modal = '#'+$(this).closest('.modal').attr('id');
	wbminsertnewwaybill(modal);

});


$(document).off('keyup',contentWBM+' #wbmaddwaybillnumbermodal .wbmaddwaybillnumbermodal-waybillnumber').on('keyup',contentWBM+' #wbmaddwaybillnumbermodal .wbmaddwaybillnumbermodal-waybillnumber',function(e){
	var key = e.keycode||e.which;
	if(key==13){
		var modal = '#'+$(this).closest('.modal').attr('id');
		wbminsertnewwaybill(modal);
	}

});
/************************* WAYBILL - END ****************************/



/********************* PACKAGE CODE ********************************/
$(document).off('shown.bs.modal',contentWBM+' #wbmaddpackagecodemodal').on('shown.bs.modal',contentWBM+' #wbmaddpackagecodemodal',function(){
	$(this).find(' .wbmaddpackagecodemodal-code').val('').focus();
	$(this).find(' .modal-errordiv').empty();

});


$(document).off('click',contentWBM+' #wbmaddpackagecodemodal-addbtn').on('click',contentWBM+' #wbmaddpackagecodemodal-addbtn',function(){
	var modal = '#'+$(this).closest('.modal').attr('id');
	wbminsertnewpackagecode(modal);

});


$(document).off('keyup',contentWBM+' #wbmaddpackagecodemodal .wbmaddpackagecodemodal-code').on('keyup',contentWBM+' #wbmaddpackagecodemodal .wbmaddpackagecodemodal-code',function(e){
	var key = e.keycode||e.which;
	if(key==13){
		var modal = '#'+$(this).closest('.modal').attr('id');
		wbminsertnewpackagecode(modal);
	}

});

function wbminsertnewpackagecode(modal){
	var wbmnumber = $(contentWBM+' #pgtxnwaybillmovement-id').attr('pgtxnwaybillmovement-number');
	var packagecode = $(contentWBM+' #wbmaddpackagecodemodal .wbmaddpackagecodemodal-code').val();
	$(modal+' .modal-errordiv').empty();

	if(packagecode.trim()==''){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please enter package code.</div></div>");
		 $(contentWBM+' #wbmaddpackagecodemodal .wbmaddpackagecodemodal-code').focus();
	}
	else{
		$.post(server+'waybill-movement.php',{insertNewPackageCode:'ojoi#johlp#ouh$3ksk#Op1NEi34smo1sonk&$',wbmnumber:wbmnumber,packagecode:packagecode},function(data){

			if(data.trim()=='success'){


				$(contentWBM+' #waybillmovement-waybilltbl').flexOptions({
											url:'loadables/ajax/transactions.waybill-movement-waybill.php?wbmnumber='+wbmnumber,
											sortname: 'created_date',
											sortorder: "desc"
				}).flexReload();

				$(contentWBM+' #waybillmovement-packagecodetbl').flexOptions({
												url:'loadables/ajax/transactions.waybill-movement-package-code.php?wbmnumber='+wbmnumber,
												sortname: 'created_date desc, package_code',
												sortorder: "asc"
				}).flexReload();


				$(contentWBM+' #wbmaddpackagecodemodal .wbmaddpackagecodemodal-code').val('').focus();
			}
			else if(data.trim()=='invalidwaybillmovement'){

			}
			else if(data.trim()=='invalidpackagecode'){
				$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid package code.</div></div>");
		 		$(contentWBM+' #wbmaddpackagecodemodal .wbmaddpackagecodemodal-code').val('').focus();
			}
			else if(data.trim()=='invalidwaybill'){
				$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Source waybill is invalid</div></div>");
		 		$(contentWBM+' #wbmaddpackagecodemodal .wbmaddpackagecodemodal-code').val('').focus();
			}
			else if(data.trim()=='alreadyadded'){
				$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Package code already added.</div></div>");
		 		$(contentWBM+' #wbmaddpackagecodemodal .wbmaddpackagecodemodal-code').val('').focus();
			}
			else{
				alert(data);
			}

			


		});
	}

}






/****************** PACKAGE CODE - END ******************************/




$(document).off('click',contentWBM+' #waybillmovement-waybilltbl tr').on('click',contentWBM+' #waybillmovement-waybilltbl tr',function(){

				var wbnumber = $(contentWBM+' #waybillmovement-waybilltbl tr.trSelected').attr('rowwaybill');
				var wbmnumber = $(contentWBM+' #pgtxnwaybillmovement-id').attr('pgtxnwaybillmovement-number');
			
				if(wbnumber!=''&&wbnumber!=undefined){
					$(contentWBM+' #waybillmovement-packagecodetbl').flexOptions({
													qtype:'waybill_number',
													query:wbnumber
					}).flexReload();
				}
				else{
					$(contentWBM+' #waybillmovement-packagecodetbl').flexOptions({
												url:'loadables/ajax/transactions.waybill-movement-package-code.php?wbmnumber='+wbmnumber,
												sortname: 'waybill_number, package_code',
												sortorder: "asc",
												query:''
					}).flexReload();
				}

});









/************************* PRINTING *****************************************/
$(document).off('click',contentWBM+' #waybillmovement-trans-printbtn').on('click',contentWBM+' #waybillmovement-trans-printbtn',function(){
	var title = 'Print Preview ['+ $('#pgtxnwaybillmovement-id').attr('pgtxnwaybillmovement-number')+']';
	var tabid = $('#pgtxnwaybillmovement-id').attr('pgtxnwaybillmovement-number');

	if($(".content>.content-tab-pane .content-tabs").find("li[data-pane='#"+tabid+"tabpane']").length>=1){
		$(".content>.content-tab-pane .content-tabs>li[data-pane='#"+tabid+"tabpane']").remove();
		$(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='"+tabid+"tabpane']").remove();
		$('#loading-img').removeClass('hidden');
		$('.content').animate({scrollTop:0},300);

		$('.content>.content-tab-pane .content-tabs>li').removeClass('active');
		$('.content>.content-tab-pane .content-pane-wrapper>.content-pane').removeClass('active');
		$('.content>.content-tab-pane .content-tabs').append("<li data-pane='#"+tabid+"tabpane' class='active'>"+title+"<i class='fa fa-remove'></i></li>");
		$('.content>.content-tab-pane .content-pane-wrapper').append("<div class='content-pane active' id='"+tabid+"tabpane'></div>");
		$('.content>.content-tab-pane .content-pane-wrapper>.content-pane:last-child').load("Printouts/print-preview.php?source=printouts/transactions/waybill-movement.php?txnnumber="+tabid+'&reference='+tabid);
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
		$('.content>.content-tab-pane .content-pane-wrapper>.content-pane:last-child').load("Printouts/print-preview.php?source=printouts/transactions/waybill-movement.php?txnnumber="+tabid+'&reference='+tabid);
		setTimeout(function(){
			$('#loading-img').addClass('hidden');
		},400);
	}
});
/************************* PRINTING - END *****************************************/


$(document).off('show.bs.modal',contentWBM+' #newwaybillmovementmodal').on('show.bs.modal',contentWBM+' #newwaybillmovementmodal',function(){

	var modal = '#newwaybillmovementmodal';

	$.post("../config/post-functions.php",{getDefaultUserLocation:'Fns!oi3ah434ad#2l211#$*3%'},function(data){
		
			data = $.parseJSON(data);
			if(data["locdesc"]!=null){
		        $(modal+" .newwaybillmovementmodal-location").empty().append('<option selected value="'+data["locid"]+'">'+data["loccode"]+' - '+data["locdesc"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .newwaybillmovementmodal-location").empty().trigger('change');
		    }
		
	});


});


/************************* SEARCHING ***********************************/


$(document).off('dblclick',contentWBM+' .waybillmovementsearchrow').on('dblclick',contentWBM+' .waybillmovementsearchrow',function(){
	var modal = '#'+$(this).closest('.modal').attr('id');
	var waybillmovementnumber = $(this).attr('waybillmovementnumber');
	$(modal).modal('hide');
	$(document).off('hidden.bs.modal',modal).on('hidden.bs.modal',modal,function(){
		$(document).off('hidden.bs.modal',modal);
		getWaybillMovementInformation(waybillmovementnumber);
	});

});


function searchWaybillMovementLookup(modal){
	var status = $(modal+' .waybillmovementsearch-status').val().replace(/\s/g,'%20'),
		waybillnumber = $(modal+' .waybillmovementsearch-waybillnumber').val().replace(/\s/g,'%20'),
		movementtype = $(modal+' .waybillmovementsearch-movementtype').val(),
		location = $(modal+' .waybillmovementsearch-location').val(),
	    docdatefrom = $(modal+' .waybillmovementsearch-docdatefrom').val(),
	    docdateto = $(modal+' .waybillmovementsearch-docdateto').val();

	   $(contentWBM+' #waybillmovementsearch-table').flexOptions({
			url:'loadables/ajax/transactions.waybill-movement-search.php?status='+status+'&movementtype='+movementtype+'&location='+location+'&waybill='+waybillnumber+'&docdatefrom='+docdatefrom+'&docdateto='+docdateto,
			sortname: 'waybill_movement_number',
			sortorder: "asc"
		}).flexReload(); 

}

$(document).on('keyup',contentWBM+" #waybillmovement-searchmodallookup .waybillmovementsearch-docdatefrom,"+contentWBM+" #waybillmovement-searchmodallookup .waybillmovementsearch-docdateto, "+contentWBM+" #waybillmovement-searchmodallookup .waybillmovementsearch-waybillnumber",function(e){
	var key = e.which||e.keycode;
	if(key=='13'){
		var modal = "#"+$(this).closest('.modal').attr('id');
		searchWaybillMovementLookup(modal);
	}
});



$(document).off('click',contentWBM+' #waybillmovementsearch-searchbtn:not(".disabled")').on('click',contentWBM+' #waybillmovementsearch-searchbtn:not(".disabled")',function(){


	var modal = "#"+$(this).closest('.modal').attr('id');
	searchWaybillMovementLookup(modal);

});


/************************** SEARCHING - END ********************************/



$(document).off('click',contentWBM+' .wbmwaybilllookupbtn').on('click',contentWBM+' .wbmwaybilllookupbtn',function(){
	var modal = '#waybillmovement-waybilllookupmodal';
	var wbmnumber = $(contentWBM+' #pgtxnwaybillmovement-id').attr('pgtxnwaybillmovement-number');

	$(modal).modal('show');

	$(contentWBM+' #waybillmovement-waybilllookupmodal-tbl').flexOptions({
			url:'loadables/ajax/transactions.waybill-movement-waybill-lookup.php?wbmnumber='+wbmnumber,
			sortname: 'waybill_number',
			sortorder: "asc"
	}).flexReload();


});


$(document).off('click',contentWBM+' .wbmwaybilllookup-addbtn').on('click',contentWBM+' .wbmwaybilllookup-addbtn',function(){
	var button = $(this);
	    button.addClass('disabled');

	
	var txnid = $(contentWBM+' #pgtxnwaybillmovement-id').val();
	var txnnumber = $(contentWBM+' #pgtxnwaybillmovement-id').attr('pgtxnwaybillmovement-number');

	var wbnumbers = [];
	var modal = '#'+$(this).closest('.modal').attr('id');
	var selectedwaybills = $(modal+' .wbmwaybilllookup-checkbox:checked').length;

	if(parseInt(selectedwaybills)>0){

		$(modal+' .wbmwaybilllookup-checkbox:checked').each(function(){
			wbnumbers.push($(this).attr('waybillnumber'));
		});

		$.post(server+'waybill-movement.php',{insertMultipleWaybillNumber:'oihh#p@0fldpe3ksk#Op1NEi34smo1sonk&$',wbmnumber:txnnumber,wbnumber:wbnumbers},function(data){
			//alert(data);
			data = $.parseJSON(data);
			if(data['response']=='success'){
				$(modal).modal('hide');
				$(modal).off('hidden.bs.modal').on('hidden.bs.modal',function(){
					$(modal).off('hidden.bs.modal');
					button.removeClass('disabled');
					$(contentWBM+' #waybillmovement-waybilltbl').flexOptions({
											url:'loadables/ajax/transactions.waybill-movement-waybill.php?wbmnumber='+txnnumber,
											sortname: 'waybill_number',
											sortorder: "asc"
					}).flexReload();

					$(contentWBM+' #waybillmovement-packagecodetbl').flexOptions({
													url:'loadables/ajax/transactions.waybill-movement-package-code.php?wbmnumber='+txnnumber,
													sortname: 'waybill_number asc, package_code',
													sortorder: "asc"
					}).flexReload();

					if(data['failedmsg']!=''){
						say(data['failedmsg']);
					}
				});

			}
			else if(data['response']=='invalidwaybillmovement'){

				say("Invalid waybill movement transaction, please refresh page.<br>Waybill Movement No.: "+txnnumber);
				button.removeClass('disabled');
			}
			else if(data['response']=='txnalreadyposted'){
				$(modal).modal('hide');
				$(modal).off('hidden.bs.modal').on('hidden.bs.modal',function(){
					$(modal).off('hidden.bs.modal');
					getWaybillMovementInformation(txnnumber);
					say("Unable to insert waybill. Waybill Movement transaction is already posted.");
					button.removeClass('disabled');
				});
			}
			else{
				button.removeClass('disabled');
			}



		});
	}
	else{
		say("Please select waybills to be added.");
		button.removeClass('disabled');
	}

});


$(document).off('click',contentWBM+' #waybillmovement-trans-voidbtn:not(".disabled")').on('click',contentWBM+' #waybillmovement-trans-voidbtn:not(".disabled")',function(){

	//var modal = '#'+$(this).closest('.modal').attr('id');
	var txnid = $(contentWBM+' #pgtxnwaybillmovement-id').val();
	var txnnumber = $(contentWBM+' #pgtxnwaybillmovement-id').attr('pgtxnwaybillmovement-number');
	var button = $(this);
	button.addClass('disabled');

	//$(modal+' .modal-errordiv').empty();


	$.confirm({
				animation: 'bottom', 
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce:1,
				title: 'Void BOL Movement ['+txnnumber+']',
				content: 'Do you want to continue?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',	
				confirmButtonClass: 'btn-oceanblue', 
				cancelButtonClass: 'btn-royalblue', 
				theme: 'white', 

				confirm: function(){
					$('#loading-img').removeClass('hidden');
					$.post(server+'waybill-movement.php',{voidWaybillMovementTransaction:'dROi$nsFpo94dnels$4sRoi809srbmouS@1!',txnid:txnid,txnnumber:txnnumber},function(data){


						if(data.trim()=='success'){
							getWaybillMovementInformation(txnnumber);
							button.removeClass('disabled');
							$('#loading-img').addClass('hidden');
							/*$(modal).modal('hide');
							$(document).off('hidden.bs.modal',modal).on('hidden.bs.modal',modal,function(){
								$(document).off('hidden.bs.modal',modal);
								
				

								getWaybillInformation(waybillnumber);
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							});*/
						}
						else if(data.trim()=='invalidwaybillmovement'){
							say("Unable to void BOL movement transaction. Invalid ID/No. ID: "+txnid+" - Transaction No.: "+txnnumber);
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
	
});




