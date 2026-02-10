
var tabshipperRATE = '#shipperrate-menutabpane';




$(document).off('change',tabshipperRATE+' .fixedrateflag').on('change',tabshipperRATE+' .fixedrateflag',function(){
		var modal = '#'+$(this).closest('.modal').attr('id');
		var type = $(modal+' .type').val();
		var fixedrateflag = $(this).prop('checked');
		showHideshipperRateFields(modal,type,fixedrateflag);

});

/*$(document).off('change',tabshipperRATE+' .pulloutflag').on('change',tabshipperRATE+' .pulloutflag',function(){
		var modal = '#'+$(this).closest('.modal').attr('id');
		var flag = $(this).prop('checked');
		var fixedrateflag = $(modal+' .fixedrateflag').prop('checked');
		//alert(flag);


		if(flag==true&&fixedrateflag==false){
				$(modal+' .pulloutfeewrapper').removeClass('hidden');
				$(modal+' .fixedrateamountwrapper').addClass('hidden');
				$(modal+' .normalratewrapper').addClass('hidden');
		}	
		else if(flag==true&&fixedrateflag==true){	
			    $(modal+' .fixedrateamountwrapper').removeClass('hidden');
			    $(modal+' .pulloutfeewrapper').addClass('hidden');
			    $(modal+' .normalratewrapper').addClass('hidden');
		}	
		else if(fixedrateflag==true){	
			    $(modal+' .fixedrateamountwrapper').removeClass('hidden');
			    $(modal+' .pulloutfeewrapper').addClass('hidden');
			    $(modal+' .normalratewrapper').addClass('hidden');
		}	
		else{
				$(modal+' .pulloutfeewrapper').addClass('hidden');
				$(modal+' .fixedrateamountwrapper').addClass('hidden');
				$(modal+' .normalratewrapper').removeClass('hidden');
		}


});*/


$(document).off('click','.editshipperratebtn').on('click','.editshipperratebtn',function(){
	var modal = "#editshipperratemodal";
	var rowid = $(this).attr('rowid')
	$(modal+' .shipperratemodalid').val(rowid);

	$.post(server+'shipper-rate2.php',{shipperRateGetInfo:'kjoI$H2oiaah3h0$09jDppo92po@k@',id:rowid},function(data){
		
		rsp = $.parseJSON(data);
		if(rsp['response']=='success'){
			$(modal+' .valuation').val(rsp['valuation']);
			$(modal+' .freightrate').val(rsp['freightrate']);
			$(modal+' .insurancerate').val(rsp['insurancerate']);
			$(modal+' .fuelrate').val(rsp['fuelrate']);
			$(modal+' .bunkerrate').val(rsp['bunkerrate']);
			$(modal+' .minimumrate').val(rsp['minimumrate']);
			$(modal+' .fixedrateamount').val(rsp['fixedrateamount']);
			$(modal+' .pulloutfee').val(rsp['pulloutfee']);
			//$(modal+' .odarate').val(rsp['odarate']);
			$(modal+' .type').val(rsp['waybilltype']).trigger('change');

			

			if(rsp['fixedrateflag']==1){

				$(modal+' .fixedrateflag').bootstrapToggle('on')
			}
			else{
				$(modal+' .fixedrateflag').bootstrapToggle('off');
			}

			if(rsp['rushflag']==1){

				$(modal+' .rushflag').bootstrapToggle('on')
			}
			else{
				$(modal+' .rushflag').bootstrapToggle('off');
			}

			if(rsp['pulloutflag']==1){

				$(modal+' .pulloutflag').bootstrapToggle('on')
			}
			else{
				$(modal+' .pulloutflag').bootstrapToggle('off');
			}

			if(rsp["shipper"]!=null){
		        $(modal+" .shipper").empty().append('<option selected value="'+rsp["shipperid"]+'">'+rsp["shipper"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .shipper").empty().trigger('change');
		    }
			
			if(rsp["origin"]!=null){
		        $(modal+" .origin").empty().append('<option selected value="'+rsp["originid"]+'">'+rsp["origin"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .origin").empty().trigger('change');
		    }

		    if(rsp["destination"]!=null){
		        $(modal+" .destination").empty().append('<option selected value="'+rsp["destinationid"]+'">'+rsp["destination"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .destination").empty().trigger('change');
		    }

		    if(rsp["modeoftransport"]!=null){
		        $(modal+" .modeoftransport").empty().append('<option selected value="'+rsp["modeoftransportid"]+'">'+rsp["modeoftransport"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .modeoftransport").empty().trigger('change');
		    }


		    if(rsp["services"]!=null){
		        $(modal+" .services").empty().append('<option selected value="'+rsp["servicesid"]+'">'+rsp["services"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .services").empty().trigger('change');
		    }

		    if(rsp["freightcomputation"]!=null){
		        $(modal+" .freightcomputation").empty().append('<option selected value="'+rsp["freightcomputation"]+'">'+rsp["freightcomputation"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .freightcomputation").empty().trigger('change');
		    }

		    if(rsp["pouchsize"]!=null){
		        $(modal+" .pouchsize").empty().append('<option selected value="'+rsp["pouchsizeid"]+'">'+rsp["pouchsize"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .pouchsize").empty().trigger('change');
		    }

		    if(rsp["zone"]!=null){
		        $(modal+" .zone").empty().append('<option selected value="'+rsp["zoneid"]+'">'+rsp["zone"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .zone").empty().trigger('change');
		    }

			if(rsp["shipmenttype"]!=null){
		        $(modal+" .shipmenttype").empty().append('<option selected value="'+rsp["shipmenttypeid"]+'">'+rsp["shipmenttype"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .shipmenttype").empty().trigger('change');
		    }

			if(rsp["shipmentmode"]!=null){
		        $(modal+" .shipmentmode").empty().append('<option selected value="'+rsp["shipmentmodeid"]+'">'+rsp["shipmentmode"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .shipmentmode").empty().trigger('change');
		    }

		    if(rsp["tpl"]!=null){
		        $(modal+" .3pl").empty().append('<option selected value="'+rsp["tplid"]+'">'+rsp["tpl"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .3pl").empty().trigger('change');
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
$(document).off('click','.shipperratemodal-savebtn:not(".disabled")').on('click','.shipperratemodal-savebtn:not(".disabled")',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		shipper = $(modal+' .shipper').val(),
		tpl = $(modal+' .3pl').val(),
		origin = $(modal+' .origin').val(),
		zone = $(modal+' .zone').val(),
		shipmenttype = $(modal+' .shipmenttype').val(),
		shipmentmode = $(modal+' .shipmentmode').val(),
		destination = $(modal+' .destination').val(),
		modeoftransport = $(modal+' .modeoftransport').val(),
		services = $(modal+' .services').val(),
		freightcomputation = '',
		fixedrateflag = $(modal+' .fixedrateflag').prop('checked'),
		rushflag = $(modal+' .rushflag').prop('checked'),
		pulloutflag = $(modal+' .pulloutflag').prop('checked'),
		valuation = $(modal+' .valuation').val(),
		freightrate = $(modal+' .freightrate').val(),
		insurancerate = $(modal+' .insurancerate').val(),
		fuelrate = $(modal+' .fuelrate').val(),
		bunkerrate = $(modal+' .bunkerrate').val(),
		minimumrate = $(modal+' .minimumrate').val(),
		pulloutfee = 0;//$(modal+' .pulloutfee').val(),
		fixedrateamount = $(modal+' .fixedrateamount').val(),
		//odarate = $(modal+' .odarate').val(),
		wbtype = $(modal+' .type').val(),
		pouchsize = $(modal+' .pouchsize').val(),
		id='',
		newsort = 'created_date',
		source='add',
		button=$(this);
		button.addClass('disabled').attr('disabled','disabled');

	if(fixedrateflag==false){
		freightrate = 0;
		//odarate = 0;
	}
	/*else if(pulloutflag==true){
		pulloutfee = $(modal+' .pulloutfee').val();
		valuation = 0;
		freightrate = 0;
		insurancerate = 0;
		fuelrate = 0;
		bunkerrate = 0;
		minimumrate = 0;
		fixedrateamount = 0;
		//odarate = 0;
	}*/

	/*if(wbtype=='PARCEL'){
		//freightcomputation = $(modal+' .freightcomputation').val();
	}
	else if(wbtype=='DOCUMENT'){
		pouchsize = $(modal+' .pouchsize').val();
	}*/

	if(modal=='#editshipperratemodal'){
		id = $(modal+' .shipperratemodalid').val();
		source = 'edit';
		newsort = 'updated_date';
	}

	if(shipper==''||shipper==null||shipper=='NULL'||shipper=='null'||shipper==undefined){
		$(modal+' .shipper').select2('open');
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select shipper</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(tpl==''||tpl==null||tpl=='NULL'||tpl=='null'){
		$(modal+' .3pl').select2('open');
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select 3PL</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(wbtype!='DOCUMENT'&&wbtype!='PARCEL'){
		$(modal+' .type').select2('open');
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid Waybill Type</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(origin==''||origin==null||origin=='NULL'||origin=='null'){
		$(modal+' .origin').select2('open');
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select an origin.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(shipmenttype==''||shipmenttype==null||shipmenttype=='NULL'||shipmenttype=='null'){
		$(modal+' .shipmenttype').select2('open');
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select shipment type.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	} 
	else if(shipmentmode==''||shipmentmode==null||shipmentmode=='NULL'||shipmentmode=='null'){
		$(modal+' .shipmentmode').select2('open');
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select shipment mode.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	} 
	else if(modeoftransport==''||modeoftransport==null||modeoftransport=='NULL'||modeoftransport=='null'){
		$(modal+' .modeoftransport').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select mode of transport.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(zone==''||zone==null||zone=='NULL'||zone=='null'){
		$(modal+' .zone').select2('open');
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select zone destination.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(pouchsize==''||pouchsize==null||pouchsize=='NULL'||pouchsize=='null'){
		$(modal+' .pouchsize').select2('open');
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select pouch size.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(fixedrateflag==true&&(freightrate==''||parseFloat(freightrate)<0||freightrate==null||freightrate==undefined)){
		$(modal+' .freightrate').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a valid freight rate.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	/*else if(destination==''||destination==null||destination=='NULL'||destination=='null'){
		$(modal+' .destination').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select destination.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(wbtype=='PARCEL'&&(services==''||services==null||services=='NULL'||services=='null')){
		$(modal+' .services').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select services.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(wbtype=='PARCEL'&&(freightcomputation==''||freightcomputation==null||freightcomputation=='NULL'||freightcomputation=='null')){
		$(modal+' .freightcomputation').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select freight computation.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}*/
	/*else if(fixedrateflag==false&&(valuation==''||valuation<0)){
		$(modal+' .valuation').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide valuation.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(fixedrateflag==false&&(freightrate==''||freightrate<=0)){
		$(modal+' .freightrate').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide freight rate.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(fixedrateflag==false&&(insurancerate==''||insurancerate<0)){
		$(modal+' .insurancerate').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide insurance rate.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(wbtype=='PARCEL'&&(fixedrateflag==false&&(fuelrate==''||fuelrate<0))){
		$(modal+' .fuelrate').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide fuel rate.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(wbtype=='PARCEL'&&(fixedrateflag==false&&(bunkerrate==''||bunkerrate<0))){
		$(modal+' .bunkerrate').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide bunker rate.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(wbtype=='PARCEL'&&(fixedrateflag==false&&(minimumrate==''||minimumrate<0))){
		$(modal+' .minimumrate').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide minimum rate.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(wbtype=='PARCEL'&&(fixedrateflag==true&&(fixedrateamount==''||fixedrateamount<=0))){
		$(modal+' .fixedrateamount').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide fixed rate amount.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(fixedrateflag==false&&pulloutflag==true&&(pulloutfee==''||pulloutfee<=0)){
		$(modal+' .pulloutfee').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide pull out fee.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}*/
	else{
		$('#loading-img').removeClass('hidden');
		$.post(server+'shipper-rate2.php',{shipperRateSaveEdit:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',shipper:shipper,source:source,id:id,tpl:tpl,zone:zone,shipmenttype:shipmenttype,shipmentmode:shipmentmode,origin:origin,destination:destination,modeoftransport:modeoftransport,freightcomputation:freightcomputation,fixedrateflag:fixedrateflag,valuation:valuation,freightrate:freightrate,insurancerate:insurancerate,fuelrate:fuelrate,bunkerrate:bunkerrate,minimumrate:minimumrate,rushflag:rushflag,pulloutflag:pulloutflag,wbtype:wbtype,pouchsize:pouchsize,fixedrateamount:fixedrateamount,pulloutfee:pulloutfee,services:services},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						
						$(tabshipperRATE+' #shipperratetable').flexOptions({
											url:'loadables/ajax/maintenance.shipper-rate2.php',
											sortname: newsort,
											sortorder: "desc"
						}).flexReload(); 
						$('#loading-img').addClass('hidden');
						$(modal).off('hidden.bs.modal');
						button.removeAttr('disabled').removeClass('disabled');
						$(modal+' input:not(".noresetfld"), '+modal+' textarea').val('');
						$(modal+' .errordiv').empty();

					});
				}
				else if(data.trim()=='invalidshipper'){
					$('#loading-img').addClass('hidden');
					$(modal+' .shipper').select2('open');
					$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid Shipper</div></div>");
					button.removeAttr('disabled').removeClass('disabled');
					
				}
				else if(data.trim()=='rateexists'){
					$('#loading-img').addClass('hidden');
					$(modal+' .destination').focus();
					$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Rate exists. Please select another 3pl, type, origin, zone, shipment type, pouch size.</div></div>");
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

/******************************* DELETE *****************************************************/

function showHideshipperRateFields(modal,type,fixedrateflag){
	if(type=='PARCEL'){
			//$(modal+' .pouchsizewrapper').addClass('hidden');
			

			/*$(modal+' .freightcomputationwrapper').removeClass('hidden');

			$(modal+' .modeoftransportwrapper').removeClass('hidden');
			$(modal+' .serviceswrapper').removeClass('hidden');
			$(modal+' .pulloutflagwrapper').removeClass('hidden');
			$(modal+' .fixedrateflagwrapper').removeClass('hidden');

			$(modal+' .fuelratewrapper').removeClass('hidden');
			$(modal+' .bunkerratewrapper').removeClass('hidden');
			$(modal+' .minimumratewrapper').removeClass('hidden');


			if(fixedrateflag==true){
				$(modal+' .fixedrateamountwrapper').removeClass('hidden');
				$(modal+' .normalratewrapper').addClass('hidden');
				$(modal+' .pulloutfeewrapper').addClass('hidden');
			}	
			else{
				$(modal+' .pulloutfeewrapper').addClass('hidden');
				$(modal+' .fixedrateamountwrapper').addClass('hidden');
				$(modal+' .normalratewrapper').removeClass('hidden');	
			}*/
			
	}
	else{
			//$(modal+' .pouchsizewrapper').removeClass('hidden');
			/*$(modal+' .valuationwrapper').removeClass('hidden');
			$(modal+' .freightratewrapper').removeClass('hidden');
			$(modal+' .insuranceratewrapper').removeClass('hidden');
			

			$(modal+' .freightcomputationwrapper').addClass('hidden');
			$(modal+' .modeoftransportwrapper').addClass('hidden');
			$(modal+' .serviceswrapper').addClass('hidden');
			$(modal+' .pulloutflagwrapper').addClass('hidden');
			$(modal+' .fixedrateflagwrapper').addClass('hidden');

			$(modal+' .fuelratewrapper').addClass('hidden');
			$(modal+' .bunkerratewrapper').addClass('hidden');
			$(modal+' .minimumratewrapper').addClass('hidden');
			$(modal+' .fixedrateamountwrapper').addClass('hidden');
			$(modal+' .pulloutfeewrapper').addClass('hidden');*/
	}

	if(fixedrateflag==true){
		$(modal+' .freightratewrapper').removeClass('hidden');
	}	
	else{
		$(modal+' .freightratewrapper').addClass('hidden');
	}
}


$(document).off('change',tabshipperRATE+' .type').on('change',tabshipperRATE+' .type',function(){

	var type = $(this).val();
	var modal = '#'+$(this).closest('.modal').attr('id');
	var fixedrateflag = $(modal+' .fixedrateflag').prop('checked');

	showHideshipperRateFields(modal,type,fixedrateflag);
	

	/*if(type=='PARCEL'){
			$(modal+' .pouchsizewrapper').addClass('hidden');

			$(modal+' .freightcomputationwrapper').removeClass('hidden');

			$(modal+' .modeoftransportwrapper').removeClass('hidden');
			$(modal+' .serviceswrapper').removeClass('hidden');
			$(modal+' .pulloutflagwrapper').removeClass('hidden');
			$(modal+' .fixedrateflagwrapper').removeClass('hidden');

			$(modal+' .fuelratewrapper').removeClass('hidden');
			$(modal+' .bunkerratewrapper').removeClass('hidden');
			$(modal+' .minimumratewrapper').removeClass('hidden');


			if(fixedrateflag==true){
				$(modal+' .fixedrateamountwrapper').removeClass('hidden');
				$(modal+' .normalratewrapper').addClass('hidden');
				$(modal+' .pulloutfeewrapper').addClass('hidden');
			}	
			else{
				$(modal+' .pulloutfeewrapper').addClass('hidden');
				$(modal+' .fixedrateamountwrapper').addClass('hidden');
				$(modal+' .normalratewrapper').removeClass('hidden');	
			}
			
	}
	else{
			$(modal+' .pouchsizewrapper').removeClass('hidden');
			$(modal+' .valuationwrapper').removeClass('hidden');
			$(modal+' .freightratewrapper').removeClass('hidden');
			$(modal+' .insuranceratewrapper').removeClass('hidden');
			

			$(modal+' .freightcomputationwrapper').addClass('hidden');
			$(modal+' .modeoftransportwrapper').addClass('hidden');
			$(modal+' .serviceswrapper').addClass('hidden');
			$(modal+' .pulloutflagwrapper').addClass('hidden');
			$(modal+' .fixedrateflagwrapper').addClass('hidden');

			$(modal+' .fuelratewrapper').addClass('hidden');
			$(modal+' .bunkerratewrapper').addClass('hidden');
			$(modal+' .minimumratewrapper').addClass('hidden');
			$(modal+' .fixedrateamountwrapper').addClass('hidden');
			//$(modal+' .pulloutfeewrapper').addClass('hidden');
	}*/



	

});

$(document).off('click',tabshipperRATE+' .viewshipperratefreightchargebtn, '+tabshipperRATE+' .viewpblfreightratebtn').on('click',tabshipperRATE+' .viewshipperratefreightchargebtn, '+tabshipperRATE+' .viewpblfreightratebtn',function(){
	var modal = '#viewshipperratefreightcharge';
	var shipperrateid = $(this).attr('rowid');
	$(modal+' #viewshipperratefreightcharge-shipperrateid').val(shipperrateid);

	$(tabshipperRATE+" #viewshipperratefreightcharge-tbl").flexOptions({
		url:'loadables/ajax/maintenance.shipper-rate-freight-charge2.php?shipperrateid='+shipperrateid,
		sortname: "from_kg",
		sortorder: "asc",
		newp: 1
	}).flexReload();

	$(modal).modal('show');

});



$(document).off('click',tabshipperRATE+' .viewshipperratefreightcharge-insertratebtn:not(".disabled")').on('click',tabshipperRATE+' .viewshipperratefreightcharge-insertratebtn:not(".disabled")',function(){


		var button = $(this);
		    button.addClass('disabled');

		var modal = '#'+$(this).closest('.modal').attr('id');

		var fromkg = $(modal+' .viewshipperratefreightcharge-fromkg').val();
		var tokg = $(modal+' .viewshipperratefreightcharge-tokg').val();
		var freightcharge = $(modal+' .viewshipperratefreightcharge-freightcharge').val();
		var excessweightcharge = $(modal+' .viewshipperratefreightcharge-excessweightcharge').val();

		var shipperrateid = $(modal+' #viewshipperratefreightcharge-shipperrateid').val();




		if(fromkg==undefined||fromkg==null||fromkg==''||fromkg<0){
			$(modal+' .viewshipperratefreightcharge-fromkg').focus();
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide from (kg).</div></div>");
			button.removeClass('disabled');
		}
		else if(tokg==undefined||tokg==null||tokg==''||tokg<=0){
			$(modal+' .viewshipperratefreightcharge-tokg').focus();
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide to (kg).</div></div>");
			button.removeClass('disabled');
		}
		else if(freightcharge==undefined||freightcharge==null||freightcharge==''||freightcharge<=0){
			$(modal+' .viewshipperratefreightcharge-freightcharge').focus();
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide freight charge.</div></div>");
			button.removeClass('disabled');
		}
		else if(excessweightcharge==undefined||excessweightcharge==null||excessweightcharge==''||excessweightcharge<0){
			$(modal+' .viewshipperratefreightcharge-excessweightcharge').focus();
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide excess weight charge.</div></div>");
			button.removeClass('disabled');
		}
		else{
			$('#loading-img').removeClass('hidden');
			$.post(server+'shipper-rate2.php',{AddEditshipperRateFreightCharge:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',shipperrateid:shipperrateid,fromkg:fromkg,tokg:tokg,freightcharge:freightcharge,excessweightcharge:excessweightcharge,source:'add'},function(data){
					if(data.trim()=='success'){
						//$(modal).modal('hide');
						//$(modal).on('hidden.bs.modal',function(){
							
							$(tabshipperRATE+" #viewshipperratefreightcharge-tbl").flexOptions({
								url:'loadables/ajax/maintenance.shipper-rate-freight-charge2.php?shipperrateid='+shipperrateid,
								sortname: "created_date",
								sortorder: "desc"
							}).flexReload(); 
							$('#loading-img').addClass('hidden');
							
							button.removeAttr('disabled').removeClass('disabled');
							$(modal+' input:not(".noresetfld"), '+modal+' textarea').val('');
							$(modal+' .inputslctfld:not(".noresetfld")').empty().trigger('change');
							$(modal+' .errordiv').empty();
							//$(modal).off('hidden.bs.modal');

						//});
					}
					else if(data.trim()=='noaccess'){
						$('#loading-img').addClass('hidden');
						$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Unable to add/edit shipper rate freight charge. No user permission.</div></div>");
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

$(document).off('click',tabshipperRATE+' .editshipperratefreightchargebtn').on('click',tabshipperRATE+' .editshipperratefreightchargebtn',function(){

	var modal = '#editshipperratefreightchargemodal';
	var freightchargeID = $(this).attr('rowid');
	var fromkg = $(this).attr('fromkg');
	var tokg = $(this).attr('tokg');
	var freightcharge = $(this).attr('freightcharge');
	var excessweightcharge = $(this).attr('excessweightcharge');

	$(modal+' #shipperratefreightchargeID').val(freightchargeID);
	$(modal+' .editshipperratefreightchargemodal-fromkg').val(fromkg);
	$(modal+' .editshipperratefreightchargemodal-tokg').val(tokg);
	$(modal+' .editshipperratefreightchargemodal-freightcharge').val(freightcharge);
	$(modal+' .editshipperratefreightchargemodal-excessweightcharge').val(excessweightcharge);
	$(tabshipperRATE+' '+modal).modal('show');


});

$(document).off('click',tabshipperRATE+' .editshipperratefreightchargemodal-savebtn:not("disabled")').on('click',tabshipperRATE+' .editshipperratefreightchargemodal-savebtn:not("disabled")',function(){
	var button = $(this);
		button.addClass("disabled");

	var shipperrateid = $('#viewshipperratefreightcharge #viewshipperratefreightcharge-shipperrateid').val();
	
	var modal = '#'+$(this).closest(".modal").attr('id');
	var freightchargeID = $(modal+' #shipperratefreightchargeID').val();
	var fromkg = $(modal+' .editshipperratefreightchargemodal-fromkg').val();
	var tokg = $(modal+' .editshipperratefreightchargemodal-tokg').val();
	var freightcharge = $(modal+' .editshipperratefreightchargemodal-freightcharge').val();
	var excessweightcharge = $(modal+' .editshipperratefreightchargemodal-excessweightcharge').val();


	if(fromkg==undefined||fromkg==null||fromkg==''||fromkg<0){
		$(modal+' .editshipperratefreightchargemodal-fromkg').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide from (kg).</div></div>");
		button.removeClass('disabled');
	}
	else if(tokg==undefined||tokg==null||tokg==''||tokg<=0){
		$(modal+' .editshipperratefreightchargemodal-tokg').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide to (kg).</div></div>");
		button.removeClass('disabled');
	}
	else if(freightcharge==undefined||freightcharge==null||freightcharge==''||freightcharge<=0){
		$(modal+' .editshipperratefreightchargemodal-freightcharge').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide freight charge.</div></div>");
		button.removeClass('disabled');
	}
	else if(excessweightcharge==undefined||excessweightcharge==null||excessweightcharge==''||excessweightcharge<0){
		$(modal+' .editshipperratefreightchargemodal-excessweightcharge').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide excess weight charge.</div></div>");
		button.removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		$.post(server+'shipper-rate2.php',{AddEditshipperRateFreightCharge:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',shipperrateid:shipperrateid,freightchargeID:freightchargeID,fromkg:fromkg,tokg:tokg,freightcharge:freightcharge,excessweightcharge:excessweightcharge,source:'edit'},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						
						$(tabshipperRATE+" #viewshipperratefreightcharge-tbl").flexOptions({
							url:'loadables/ajax/maintenance.shipper-rate-freight-charge2.php?shipperrateid='+shipperrateid,
							sortname: "updated_date",
							sortorder: "desc"
						}).flexReload(); 
						$('#loading-img').addClass('hidden');
						
						button.removeClass('disabled');
						$(modal+' .errordiv').empty();
						$(modal).off('hidden.bs.modal');

					});
				}
				else if(data.trim()=='noaccess'){

					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						
						$(tabshipperRATE+" #viewshipperratefreightcharge-tbl").flexOptions({
							url:'loadables/ajax/maintenance.shipper-rate-freight-charge2.php?shipperrateid='+shipperrateid,
							sortname: "updated_date",
							sortorder: "desc"
						}).flexReload(); 
						$('#loading-img').addClass('hidden');
						
						button.removeClass('disabled');
						$(modal+' .errordiv').empty();
						$(modal).off('hidden.bs.modal');
						say("Unable to edit freight charges. No user permission.");

					});

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


$(document).off('click',tabshipperRATE+' .viewshipperratefreightcharge-clearratefieldsbtn').on('click',tabshipperRATE+' .viewshipperratefreightcharge-clearratefieldsbtn',function(){
		var modal = '#'+$(this).closest('.modal').attr('id');
		$(modal+' .viewshipperratefreightcharge-fromkg').val('');
		$(modal+' .viewshipperratefreightcharge-tokg').val('');
		$(modal+' .viewshipperratefreightcharge-freightcharge').val('');
		$(modal+' .viewshipperratefreightcharge-excessweightcharge').val('');
		$(modal+' .viewshipperratefreightcharge-fromkg').focus();
});



/***************************** UPLOAD ********************************************************/


$(document).off('click',tabshipperRATE+' #uploadshipperratemodal-uploadbtn:not(".disabled")').on('click',tabshipperRATE+' #uploadshipperratemodal-uploadbtn:not(".disabled")',function(){

	var modal = '#uploadshipperratemodal';
	var modal2 = '#shipperrate-uploadtransactionlogmodal';
	var form = '#uploadshipperratemodal-form';
	var logframe = '#shipperrateuploadtransactionlogframe';
	var button = $(this);
	var shipper = $(modal+' .uploadshipperratemodal-shipper').val();
	button.addClass('disabled');

	if(shipper==''||shipper=='null'||shipper=='NULL'||shipper==null||shipper==undefined){
		say('Please select shipper');
		button.removeClass('disabled');
	}
	else if($(tabshipperRATE+' '+modal+' .uploadshipperratemodal-file').val().trim()==''){
		say('Please select a file to upload');
		button.removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		
		$(modal).modal('hide');
		$(document).off('hidden.bs.modal',tabshipperRATE+' '+modal).on('hidden.bs.modal',tabshipperRATE+' '+modal,function(){
			
			$(document).off('hidden.bs.modal',tabshipperRATE+' '+modal);
			$(tabshipperRATE+' '+modal2).modal('show');
			$(form).submit();

			$(logframe).load(function () {

				button.removeClass('disabled');

				$('#loading-img').addClass('hidden');

				$(tabshipperRATE+' #shipperratetable').flexOptions({
						url:'loadables/ajax/maintenance.shipper-rate2.php',
						sortname: "created_date",
						sortorder: "desc"
				}).flexReload();

				/*$(this).contents().find('#touploadsuccessbtn').off('click').on('click',function(){
					$(this).contents().find('#touploadsuccessbtn').off('click');

					*var to = $(this).attr('tonumber');
					$('#transfer-order-touploadlog-modal').modal('hide');
					$(document).off('hidden.bs.modal','#transfer-order-touploadlog-modal').on('hidden.bs.modal','#transfer-order-touploadlog-modal',function(){
						$(document).off('hidden.bs.modal','#transfer-order-touploadlog-modal');
						getToDetails(to);
					});

				});*/

			});

		});
	
	}

});
/************************************* UPLOAD END *************************************************/