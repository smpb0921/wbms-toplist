var contentWB = '#waybill-menutabpane';
var inputfieldsWB = '.waybill-inputfields';
var processWB = '';
var currentWaybillTxn = '';
var currentdivisor = 0;

function defaultExpressValue(type) {
	/*if(processWB=='add'||processWB=='edit'){

		if(type=='DOCUMENT'){
			$(contentWB+' .waybill-services, '+contentWB+' .waybill-modeoftransport').attr('disabled','disabled').empty().trigger('change');

			$.post(server+'waybill.php',{getExpressValforModeAndServices:'sdf#io2s9$dlIP$psLn!#oid($)soep$8%syo7'},function(data){

				data = $.parseJSON(data);

				if(data["motexpress"]!=null&&data["motexpressid"]>0){
					$(inputfieldsWB+" .waybill-modeoftransport").empty().append('<option selected value="'+data["motexpressid"]+'">'+data["motexpress"]+'</option>').trigger('change');
				}
				else{
					$(inputfieldsWB+" .waybill-modeoftransport").empty().trigger('change');
				}

				if(data["srvexpress"]!=null&&data["srvexpressid"]>0){
					$(inputfieldsWB+" .waybill-services").empty().append('<option selected value="'+data["srvexpressid"]+'">'+data["srvexpress"]+'</option>').trigger('change');
				}
				else{
					$(inputfieldsWB+" .waybill-services").empty().trigger('change');
				}

			});
		}
		else{
			$(contentWB+' .waybill-services, '+contentWB+' .waybill-modeoftransport').removeAttr('disabled');
		}

	}*/
}

function showFieldsBasedOnWaybillType(type) {
	if (type == 'PARCEL') {
		$(contentWB + ' .parceltypewrapper').removeClass('hidden');
		$(contentWB + ' .cbmwrapper').removeClass('hidden');
		$(contentWB + ' .volweightwrapper').removeClass('hidden');
		//$(contentWB+' .actualweightwrapper').removeClass('hidden');
		//$(contentWB+' .pouchsizewrapper').addClass('hidden');
		$(contentWB + ' .expresstransactiontypewrapper').addClass('hidden');
		$(contentWB + ' .numberofpackageswrapper').removeClass('hidden');
		$(contentWB + ' .pulloutflagwrapper').removeClass('hidden');
		//$(contentWB+' .waybill-numberofpackages').addClass('alwaysdisabled').attr('disabled','disabled');

		//$(contentWB+' .serviceswrapper').removeClass('hidden');
		$(contentWB + ' .documentswrapper').removeClass('hidden');
		$(contentWB + ' .deliveryinstructionwrapper').removeClass('hidden');
		$(contentWB + ' .handlinginstructionwrapper').removeClass('hidden');
		$(contentWB + ' .paymodewrapper').removeClass('hidden');
		$(contentWB + ' .amountforcollectionwrapper').removeClass('hidden');
		$(contentWB + ' .packagedimensionswrapper').removeClass('hidden');

		/*if($(contentWB+' .waybill-paymode').val()=='SERVICE CARGO'||$(contentWB+' .waybill-paymode').val()=='Service Cargo'){
					$(contentWB+' .costcenterwrapper').removeClass('hidden');
				}
				else{
					$(contentWB+' .costcenterwrapper').addClass('hidden');
				}*/
	} else {
		$(contentWB + ' .packagedimensionswrapper').addClass('hidden');
		$(contentWB + ' .parceltypewrapper').addClass('hidden');
		//$(contentWB+' .pouchsizewrapper').removeClass('hidden');
		$(contentWB + ' .expresstransactiontypewrapper').removeClass('hidden');

		$(contentWB + ' .cbmwrapper').addClass('hidden');
		$(contentWB + ' .volweightwrapper').addClass('hidden');
		//$(contentWB+' .actualweightwrapper').addClass('hidden');
		$(contentWB + ' .numberofpackageswrapper').addClass('hidden');
		$(contentWB + ' .pulloutflagwrapper').addClass('hidden');
		//$(contentWB+' .serviceswrapper').addClass('hidden');
		$(contentWB + ' .documentswrapper').addClass('hidden');
		//$(contentWB+' .deliveryinstructionwrapper').addClass('hidden');
		$(contentWB + ' .handlinginstructionwrapper').addClass('hidden');
		$(contentWB + ' .paymodewrapper').addClass('hidden');
		//$(contentWB+' .costcenterwrapper').addClass('hidden');
		$(contentWB + ' .amountforcollectionwrapper').addClass('hidden');

		//$(contentWB+' .waybill-numberofpackages').removeClass('alwaysdisabled').removeAttr('disabled');
	}
}

function enableFieldsWB() {
	$(contentWB + ' .errordiv').empty();
	$(inputfieldsWB + ' input:not(".alwaysdisabled"),' + inputfieldsWB + ' textarea:not(".alwaysdisabled"),' + inputfieldsWB + ' select:not(".alwaysdisabled")').removeAttr('disabled');
	$(contentWB + ' .firstprevnextlastbtn').addClass('hidden');
	//$(contentWB+' .transactionnumber').attr('disabled',true);
	$(contentWB + ' .searchbtn')
		.addClass('disabled')
		.removeClass('active')
		.addClass('hidden');

	$(contentWB + ' .billingflagdiv').addClass('hidden');

	if ($(contentWB + ' .waybill-waybilltype').val() == 'PARCEL') {
		$(contentWB + ' .waybill-numberofpackages')
			.addClass('alwaysdisabled')
			.attr('disabled', 'disabled');
	} else {
		$(contentWB + ' .waybill-numberofpackages')
			.removeClass('alwaysdisabled')
			.removeAttr('disabled');
	}

	$(contentWB + ' .waybill-consignee-secondary')
		.removeClass('alwaysdisabled')
		.removeAttr('disabled');

	$(contentWB + ' .controllabeliconedit').remove();

	$(contentWB + ' .topbuttonsdiv')
		.empty()
		.html(
			"<div class='padded-with-border-engraved topbuttonswrapper'><div class='button-group'><div class='button-group-btn active' title='Save' id='waybill-trans-savebtn'><img src='../resources/img/save.png'></div><div class='button-group-btn active' title='Cancel' id='waybill-trans-cancelbtn'><img src='../resources/img/cancel.png'></div></div></div>"
		);
	$(contentWB + ' .savecancelbuttonwrapper').html(
		"<div class='padded-with-border-engraved button-bottom'><div class='button-group'><div class='button-group-btn active' id='waybill-trans-savebtn'><img src='../resources/img/save.png'>&nbsp;&nbsp;Save</div><div class='button-group-btn active' id='waybill-trans-cancelbtn'><img src='../resources/img/cancel.png'>&nbsp;&nbsp;Cancel</div></div></div>"
	);
	$(contentWB + ' .wbotherchargessectionflds').removeClass('hidden');
	$(contentWB + ' .packagedimensionsmodalflds').removeClass('hidden');
	$(contentWB + ' .rowcheckbox').removeClass('hidden');
	$(contentWB + ' .inputgroupbtnicon').removeClass('hidden');

	$(contentWB + ' .wbaddpackagecodebtnwrapper').empty();

	defaultExpressValue($(contentWB + ' .waybill-waybilltype').val());
}

function disableFieldsWB() {
	$(contentWB + ' .errordiv').empty();
	$(inputfieldsWB + ' input:not(".alwaysdisabled"),' + inputfieldsWB + ' textarea:not(".alwaysdisabled"),' + inputfieldsWB + ' select:not([aria-controls="waybill-otherchargestbl"])').attr(
		'disabled',
		true
	);
	$(contentWB + ' .savecancelbuttonwrapper').empty();
	$(contentWB + ' .firstprevnextlastbtn').removeClass('hidden');
	$(contentWB + ' .transactionnumber').removeAttr('disabled');
	$(contentWB + ' .searchbtn')
		.removeClass('disabled')
		.addClass('active')
		.removeClass('hidden');
	$(contentWB + ' .wbotherchargessectionflds').addClass('hidden');
	$(contentWB + ' .packagedimensionsmodalflds').addClass('hidden');
	$(contentWB + ' .rowcheckbox').addClass('hidden');
	$(inputfieldsWB + ' input[aria-controls="waybill-otherchargestbl"]').removeAttr('disabled');
	$(contentWB + ' .inputgroupbtnicon:not([alwaysshow])').addClass('hidden');

	$(contentWB + ' .waybill-consignee-secondary')
		.addClass('alwaysdisabled')
		.attr('disabled', 'disabled');

	$(contentWB + ' .waybill-numberofpackages')
		.addClass('alwaysdisabled')
		.attr('disabled', 'disabled');
}

function clearAllWB() {
	$(inputfieldsWB + ' input.form-control:not(".transactionnumber")').val('');
	$(inputfieldsWB + ' textarea.form-control')
		.text('')
		.val('');
	$(inputfieldsWB + ' select:not([aria-controls="waybill-otherchargestbl"])')
		.val('')
		.trigger('change');
	$(inputfieldsWB + ' .header-errordiv').empty();
	$(inputfieldsWB + ' .detail-errordiv').empty();
	$(contentWB + ' .statusdiv').html('<br>');
	$(contentWB + ' #waybill-otherchargestbl')
		.DataTable()
		.clear()
		.search('')
		.draw();
	$(contentWB + ' #waybill-packagedimensionsmodaltbl')
		.DataTable()
		.clear()
		.search('')
		.draw();

	$(contentWB + ' .waybill-consignee-secondary').val('');

	$(contentWB + ' .waybill-rushflag, ' + contentWB + ' .waybill-pulloutflag, ' + contentWB + ' .waybill-odaflag')
		.val('0')
		.trigger('change');
	$(contentWB + ' .waybill-waybilltype')
		.val('PARCEL')
		.trigger('change');
}

function clearNewPickupAddressFieldsWB() {
	var modal = contentWB + ' #waybill-shipperpickupaddresslookup';
	$(modal + ' .inputtxtfld').val('');
	$(modal + ' .inputslctfld')
		.empty()
		.trigger('change');
}

function computeRatesWBEditCharges() {}

function computeTotalsEWC() {
	var decimalplaces = 2;
	var totalotherchargesvatable = getTotalOtherChargesVatable($(contentWB + ' #editwaybillchargesmodal-otherchargestbl').DataTable());
	var totalotherchargesnonvatable = getTotalOtherChargesNonVatable($(contentWB + ' #editwaybillchargesmodal-otherchargestbl').DataTable());
	var totalothercharges = getTotalOtherCharges($(contentWB + ' #editwaybillchargesmodal-otherchargestbl').DataTable());

	totalotherchargesvatable = parseFloat(totalotherchargesvatable);
	totalotherchargesnonvatable = parseFloat(totalotherchargesnonvatable);
	totalothercharges = parseFloat(totalothercharges);

	var totalamount = 0;
	var totalratecharges = 0;
	var vat = 0;

	var modal = '#editwaybillchargesmodal';

	var fixedrateamount = parseFloat($(modal + ' .editwaybillchargesmodal-fixedrateamount').val());
	var fixedrateamount = fixedrateamount >= 0 ? fixedrateamount : 0;

	if (fixedrateamount > 0) {
		var returndocumentfee = 0;
		var waybillfee = 0;
		var securityfee = 0;
		var docstampfee = 0;
		var oda = 0;
		var valuation = 0;
		var freight = 0;
		var insurancerate = 0;
		var fuelrate = 0;
		var bunkerrate = 0;
		var totalhandlingcharges = 0;
	} else {
		var returndocumentfee = parseFloat($(modal + ' .editwaybillchargesmodal-returndocumentfee').val());
		var returndocumentfee = returndocumentfee >= 0 ? returndocumentfee : 0;
		var waybillfee = parseFloat($(modal + ' .editwaybillchargesmodal-waybillfee').val());
		var waybillfee = waybillfee >= 0 ? waybillfee : 0;
		var securityfee = parseFloat($(modal + ' .editwaybillchargesmodal-securityfee').val());
		var securityfee = securityfee >= 0 ? securityfee : 0;
		var docstampfee = parseFloat($(modal + ' .editwaybillchargesmodal-docstampfee').val());
		var docstampfee = docstampfee >= 0 ? docstampfee : 0;
		var oda = parseFloat($(modal + ' .editwaybillchargesmodal-oda').val());
		var oda = oda >= 0 ? oda : 0;
		var valuation = parseFloat($(modal + ' .editwaybillchargesmodal-valuation').val());
		var valuation = valuation >= 0 ? valuation : 0;
		var freight = parseFloat($(modal + ' .editwaybillchargesmodal-freight').val());
		var freight = freight >= 0 ? freight : 0;
		var insurancerate = parseFloat($(modal + ' .editwaybillchargesmodal-insurancerate').val());
		var insurancerate = insurancerate >= 0 ? insurancerate : 0;
		var fuelrate = parseFloat($(modal + ' .editwaybillchargesmodal-fuelrate').val());
		var fuelrate = fuelrate >= 0 ? fuelrate : 0;
		var bunkerrate = parseFloat($(modal + ' .editwaybillchargesmodal-bunkerrate').val());
		var bunkerrate = bunkerrate >= 0 ? bunkerrate : 0;
		var totalhandlingcharges = parseFloat($(modal + ' .editwaybillchargesmodal-totalhandlingcharges').val());
		var totalhandlingcharges = totalhandlingcharges >= 0 ? totalhandlingcharges : 0;
	}

	var totalregularcharges = returndocumentfee + waybillfee + securityfee + docstampfee + oda + valuation + freight + insurancerate + fuelrate + bunkerrate + totalhandlingcharges + fixedrateamount;

	var totalvatableamount = totalregularcharges + totalotherchargesvatable;
	totalvatableamount = parseFloat(totalvatableamount);

	var vatvalue = totalvatableamount * 0.12;
	vatvalue = Math.round(vatvalue * 100) / 100;

	totalamountnovat = parseFloat(totalvatableamount) + parseFloat(totalotherchargesnonvatable);

	totalamountplusvat = parseFloat(totalvatableamount) + parseFloat(vatvalue) + parseFloat(totalotherchargesnonvatable);

	$(contentWB + ' .editwaybillchargesmodal-regularcharges')
		.val(totalregularcharges)
		.number(true, decimalplaces);
	$(contentWB + ' .editwaybillchargesmodal-otherchargesvatable')
		.val(totalotherchargesvatable)
		.number(true, decimalplaces);
	$(contentWB + ' .editwaybillchargesmodal-otherchargesnonvatable')
		.val(totalotherchargesnonvatable)
		.number(true, decimalplaces);
	$(contentWB + ' .editwaybillchargesmodal-subtotal')
		.val(totalvatableamount)
		.number(true, decimalplaces);

	var zeroratedflag = $(contentWB + ' .editwaybillchargesmodal-zeroratedcheckbox').prop('checked');
	if (zeroratedflag == true) {
		$(contentWB + ' .editwaybillchargesmodal-totalamount')
			.val(totalamountnovat)
			.number(true, decimalplaces);
		$(contentWB + ' .editwaybillchargesmodal-vat')
			.val(0)
			.number(true, decimalplaces);
	} else if (zeroratedflag == false) {
		$(contentWB + ' .editwaybillchargesmodal-totalamount')
			.val(totalamountplusvat)
			.number(true, decimalplaces);
		$(contentWB + ' .editwaybillchargesmodal-vat')
			.val(vatvalue)
			.number(true, decimalplaces);
	}

	/*$.post(server+'waybill.php',{waybillComputation:'sdf#io2s9$dlIP$psLn!#oid($)soep$8%syo7',origin:origin,destination:destination,modeoftransport:modeoftransport,services:services,cbm:vwcbm,actualweight:actualweight,declaredvalue:declaredvalue,numberofpackage:numberofpackage,vw:vw,waybilltype:waybilltype,rushflag:rushflag,pulloutflag:pulloutflag,shipperid:shipperid,pouchsize:pouchsize,handlinginstruction:handlinginstruction,consigneeid:consigneeid,amountforcollection:amountforcollection,odaflag:odaflag,expresstransactiontype:expresstransactiontype},function(data){
			//alert(data);
			data = $.parseJSON(data);
			var decimalplaces = data["decimalplaces"];


			if(data['response']=='success'){

				$(contentWB+' .waybill-returndocumentfee').val(data['returndocumentfee']).number(true,decimalplaces);
				$(contentWB+' .waybill-waybillfee').val(data['waybillfee']).number(true,decimalplaces);
				$(contentWB+' .waybill-securityfee').val(data['securityfee']).number(true,decimalplaces);
				$(contentWB+' .waybill-docstampfee').val(data['docstampfee']).number(true,decimalplaces);

				$(contentWB+' .waybill-freightcomputation').val(data['freightcomputation']);
				$(contentWB+' .waybill-chargeableweight').val(data['chargeableweight']).number(true,4);
				$(contentWB+' .waybill-valuation').val(data['valuation']).number( true, decimalplaces );
				$(contentWB+' .waybill-freight').val(data['freightrate']).number( true, decimalplaces );
				$(contentWB+' .waybill-insurancerate').val(data['insurancerate']).number( true, decimalplaces );
				$(contentWB+' .waybill-fuelrate').val(data['fuelrate']).number( true, decimalplaces );
				$(contentWB+' .waybill-bunkerrate').val(data['bunkerrate']).number( true, decimalplaces );
				$(contentWB+' .waybill-minimumrate').val(data['minimumrate']).number( true, decimalplaces );
				$(contentWB+' .waybill-pulloutfee').val(data['pulloutfee']).number( true, decimalplaces );
				$(contentWB+' .waybill-fixedrateamount').val(data['fixedrateamount']).number( true, decimalplaces );

				$(contentWB+' .waybill-baseoda').val(data['baseoda']).number( true, decimalplaces );
				$(contentWB+' .waybill-shipperoda').val(data['shipperoda']).number( true, decimalplaces);
				$(contentWB+' .waybill-oda').val(data['oda']).number( true, decimalplaces );
				$(contentWB+' .waybill-totalhandlingcharges').val(data['totalhandlingcharges']).number( true, decimalplaces );

				totalratecharges = parseFloat(data['totalrate']);
				vat =  parseFloat(data['vat']);

				if(parseFloat(data['minimumrate'])>parseFloat(data['totalrate'])){
					totalratecharges = parseFloat(data['minimumrate']);
				}

				totalvatableamount = totalratecharges+totalotherchargesvatable;
				totalvatableamount = parseFloat(totalvatableamount);

				
				vatvalue = parseFloat(vat*totalvatableamount);

				totalamountnovat = parseFloat(totalvatableamount)+parseFloat(totalotherchargesnonvatable);
				
				totalamountplusvat = parseFloat(totalvatableamount)+parseFloat(vatvalue)+parseFloat(totalotherchargesnonvatable);

				var zeroratedflag = $(contentWB+' .waybill-zeroratedcheckbox').prop('checked');

				//vatvalue = vatvalue.toFixed(2);
				//totalamount = totalamount.toFixed(2);
				//totalamountplusvat = totalamountplusvat.toFixed(2);

				$(contentWB+' .waybill-regularcharges').val(totalratecharges).number( true, decimalplaces );
				$(contentWB+' .waybill-otherchargesvatable').val(totalotherchargesvatable).number( true, decimalplaces );
				$(contentWB+' .waybill-otherchargesnonvatable').val(totalotherchargesnonvatable).number( true, decimalplaces );
				$(contentWB+' .waybill-subtotal').val(totalvatableamount).number( true, decimalplaces );
				if(zeroratedflag==true){
					$(contentWB+' .waybill-totalamount').val(totalamountnovat).number( true, decimalplaces );
					$(contentWB+' .waybill-vat').val(0).number( true, decimalplaces );
				}
				else if(zeroratedflag==false){
					$(contentWB+' .waybill-totalamount').val(totalamountplusvat).number( true, decimalplaces );
					$(contentWB+' .waybill-vat').val(vatvalue).number( true, decimalplaces );
				}
				



			}




		});*/
}

function computeRatesWB() {
	if (processWB == 'add' || processWB == 'edit') {
		var totalotherchargesvatable = getTotalOtherChargesVatable($(contentWB + ' #waybill-otherchargestbl').DataTable());
		var totalotherchargesnonvatable = getTotalOtherChargesNonVatable($(contentWB + ' #waybill-otherchargestbl').DataTable());
		var totalothercharges = getTotalOtherCharges($(contentWB + ' #waybill-otherchargestbl').DataTable());

		totalotherchargesvatable = parseFloat(totalotherchargesvatable);
		totalotherchargesnonvatable = parseFloat(totalotherchargesnonvatable);
		totalothercharges = parseFloat(totalothercharges);

		var totalamount = 0;
		var totalratecharges = 0;
		var vat = 0;
		var origin = $(contentWB + ' .waybill-origin').val();
		var destination = $(contentWB + ' .waybill-destination').val();
		var modeoftransport = $(contentWB + ' .waybill-modeoftransport').val();
		var services = $(contentWB + ' .waybill-services').val();
		var vwcbm = $(contentWB + ' .waybill-vwcbm').val();
		var vw = $(contentWB + ' .waybill-vw').val();
		var actualweight = $(contentWB + ' .waybill-actualweight').val();
		var declaredvalue = $(contentWB + ' .waybill-declaredvalue').val();
		var amountforcollection = $(contentWB + ' .waybill-amountforcollection').val();
		var numberofpackage = $(contentWB + ' .waybill-numberofpackages').val();
		var waybilltype = $(contentWB + ' .waybill-waybilltype').val();
		var parceltype = 'NULL'; //$(contentWB+' .waybill-parceltype').val();
		var odaflag = $(contentWB + ' .waybill-odaflag').val();
		var pouchsize = $(contentWB + ' .waybill-pouchsize').val();
		var expresstransactiontype = $(contentWB + ' .waybill-expresstransactiontype').val();
		var rushflag = $(contentWB + ' .waybill-rushflag').val();
		var pulloutflag = $(contentWB + ' .waybill-pulloutflag').val();
		var shipperid = $(contentWB + ' .waybill-shipper-systemid').val();
		var consigneeid = $(contentWB + ' .waybill-consignee-systemid').val();
		var handlinginstruction = $(contentWB + ' .waybill-handlinginstruction').val();
		var tpl = $(contentWB + ' .waybill-3pl').val();

		$.post(
			server + 'waybill.php',
			{
				waybillComputation: 'sdf#io2s9$dlIP$psLn!#oid($)soep$8%syo7',
				tpl: tpl,
				origin: origin,
				destination: destination,
				modeoftransport: modeoftransport,
				services: services,
				cbm: vwcbm,
				actualweight: actualweight,
				declaredvalue: declaredvalue,
				numberofpackage: numberofpackage,
				vw: vw,
				waybilltype: waybilltype,
				rushflag: rushflag,
				pulloutflag: pulloutflag,
				shipperid: shipperid,
				pouchsize: pouchsize,
				handlinginstruction: handlinginstruction,
				consigneeid: consigneeid,
				amountforcollection: amountforcollection,
				odaflag: odaflag,
				expresstransactiontype: expresstransactiontype,
				parceltype: parceltype
			},
			function (data) {
				//alert(data);
				data = $.parseJSON(data);
				var decimalplaces = data['decimalplaces'];

				if (data['response'] == 'success') {
					$(contentWB + ' .waybill-returndocumentfee')
						.val(data['returndocumentfee'])
						.number(true, decimalplaces);
					$(contentWB + ' .waybill-waybillfee')
						.val(data['waybillfee'])
						.number(true, decimalplaces);
					$(contentWB + ' .waybill-securityfee')
						.val(data['securityfee'])
						.number(true, decimalplaces);
					$(contentWB + ' .waybill-docstampfee')
						.val(data['docstampfee'])
						.number(true, decimalplaces);

					$(contentWB + ' .waybill-freightcomputation').val(data['freightcomputation']);

					$(contentWB + ' .waybill-freightcost').val(data['supplierfreightrate']);

					$(contentWB + ' .waybill-chargeableweight').val(data['chargeableweight']);
					if (parseFloat($(contentWB + ' .waybill-chargeableweight').val())) {
						$(contentWB + ' .waybill-chargeableweight').number(true, 4);
					}
					$(contentWB + ' .waybill-valuation')
						.val(data['valuation'])
						.number(true, decimalplaces);
					$(contentWB + ' .waybill-freight')
						.val(data['freightrate'])
						.number(true, decimalplaces);
					$(contentWB + ' .waybill-insurancerate')
						.val(data['insurancerate'])
						.number(true, decimalplaces);
					$(contentWB + ' .waybill-fuelrate')
						.val(data['fuelrate'])
						.number(true, decimalplaces);
					$(contentWB + ' .waybill-bunkerrate')
						.val(data['bunkerrate'])
						.number(true, decimalplaces);
					$(contentWB + ' .waybill-minimumrate')
						.val(data['minimumrate'])
						.number(true, decimalplaces);
					$(contentWB + ' .waybill-pulloutfee')
						.val(data['pulloutfee'])
						.number(true, decimalplaces);
					$(contentWB + ' .waybill-fixedrateamount')
						.val(data['fixedrateamount'])
						.number(true, decimalplaces);

					$(contentWB + ' .waybill-baseoda')
						.val(data['baseoda'])
						.number(true, decimalplaces);
					$(contentWB + ' .waybill-shipperoda')
						.val(data['shipperoda'])
						.number(true, decimalplaces);
					$(contentWB + ' .waybill-oda')
						.val(data['oda'])
						.number(true, decimalplaces);
					$(contentWB + ' .waybill-totalhandlingcharges')
						.val(data['totalhandlingcharges'])
						.number(true, decimalplaces);

					totalratecharges = parseFloat(data['totalrate']);
					vat = parseFloat(data['vat']);

					if (parseFloat(data['minimumrate']) > parseFloat(data['totalrate'])) {
						totalratecharges = parseFloat(data['minimumrate']);
					}

					totalvatableamount = totalratecharges + totalotherchargesvatable;
					totalvatableamount = parseFloat(totalvatableamount);

					vatvalue = parseFloat(vat * totalvatableamount);

					totalamountnovat = parseFloat(totalvatableamount) + parseFloat(totalotherchargesnonvatable);

					totalamountplusvat = parseFloat(totalvatableamount) + parseFloat(vatvalue) + parseFloat(totalotherchargesnonvatable);

					var zeroratedflag = $(contentWB + ' .waybill-zeroratedcheckbox').prop('checked');

					//vatvalue = vatvalue.toFixed(2);
					//totalamount = totalamount.toFixed(2);
					//totalamountplusvat = totalamountplusvat.toFixed(2);

					$(contentWB + ' .waybill-regularcharges')
						.val(totalratecharges)
						.number(true, decimalplaces);
					$(contentWB + ' .waybill-otherchargesvatable')
						.val(totalotherchargesvatable)
						.number(true, decimalplaces);
					$(contentWB + ' .waybill-otherchargesnonvatable')
						.val(totalotherchargesnonvatable)
						.number(true, decimalplaces);
					$(contentWB + ' .waybill-subtotal')
						.val(totalvatableamount)
						.number(true, decimalplaces);
					if (zeroratedflag == true) {
						$(contentWB + ' .waybill-totalamount')
							.val(totalamountnovat)
							.number(true, decimalplaces);
						$(contentWB + ' .waybill-vat')
							.val(0)
							.number(true, decimalplaces);
					} else if (zeroratedflag == false) {
						$(contentWB + ' .waybill-totalamount')
							.val(totalamountplusvat)
							.number(true, decimalplaces);
						$(contentWB + ' .waybill-vat')
							.val(vatvalue)
							.number(true, decimalplaces);
					}
				}
			}
		);
	}
}

/************* NEW BTN *********************/
$(document)
	.off('click', contentWB + ' #waybill-trans-newbtn:not(".disabled")')
	.on('click', contentWB + ' #waybill-trans-newbtn:not(".disabled")', function () {
		processWB = 'add';
		enableFieldsWB();
		clearAllWB();

		var date = getDate();
		$('#loading-img').removeClass('hidden');
		$(contentWB + ' #pgtxnwaybill-id')
			.val('')
			.removeAttr('pwynumber');
		$(contentWB + ' .statusdiv').html('RECEIVED AT CBL HUB');

		$(contentWB + ' .waybill-onholdcheckbox').prop('checked', false);
		$(contentWB + ' .waybill-shipper-systemid, ' + contentWB + ' .waybill-consignee-systemid').val('');

		$(contentWB + ' .waybill-zeroratedcheckbox').prop('checked', false);

		$.post('../config/post-functions.php', { getLoggedUserAndNextRef: 'Fns!oi3ah434ad#2l211#$*3%', transactionid: '2' }, function (data) {
			data = data.split('@#$%');
			$(contentWB + ' .waybill-createdby').val(data[0]);

			$(contentWB + ' .waybill-createddate').val(data[3]);
			$(contentWB + ' .waybill-documentdate').val(data[3]);

			//$(contentWB+' .transactionnumber').val(data[1]);
			$(contentWB + ' .transactionnumber').val('');
			$('#loading-img').addClass('hidden');

			$.post(server + 'waybill.php', { checkifviewchargesallowed: 'KFHoEO#0HELKN#Opsy#lka$P#HlNlk!I#H$' }, function (response) {
				data1 = $.parseJSON(response);
				//alert(data1["viewwaybillchargesflag"]);
				if (data1['viewwaybillchargesflag'] == 'true') {
					$(contentWB + ' .ratesandotherchargesfldwrapper').removeClass('hidden');
				} else {
					$(contentWB + ' .ratesandotherchargesfldwrapper').addClass('hidden');
				}

				if (data1['viewwaybillcostingflag'] == 'true') {
					$(contentWB + ' .costingfldwrapper').removeClass('hidden');
				} else {
					$(contentWB + ' .costingfldwrapper').addClass('hidden');
				}

				$.post('../config/post-functions.php', { defaulDoortoDoor: 'oiskus49Fnla3#Oih4noiI$IO@Y#*h@o3sk' }, function (data) {
					data = $.parseJSON(data);

					if (data['response'] == 'success') {
						if (data['service'].trim() != null) {
							$(contentWB + ' .waybill-services')
								.empty()
								.append('<option selected value="' + data['serviceid'] + '">' + data['service'] + '</option>')
								.trigger('change');
						} else {
							$(contentWB + ' .waybill-services')
								.empty()
								.trigger('change');
						}
					}
					$(contentWB + ' .waybill-origin')
						.empty()
						.append(`<option value='106'>METRO MANILA</option>`)
						.val(106)
						.trigger('change');
					$(contentWB + ' .waybill-destinationroute')
						.empty()
						.append(`<option value='9'>NA</option>`)
						.val(9)
						.trigger('change');
					$(contentWB + ' .waybill-deliveryinstruction')
						.empty()
						.append(`<option value='3'>RUSH!! PLEASE RETURN POD</option>`)
						.val(3)
						.trigger('change');
					$(contentWB + ' .waybill-modeoftransport')
						.empty()
						.append(`<option value='1'>Airfreight Domestic</option>`)
						.val(1)
						.trigger('change');

					$(contentWB + ' .waybill-3pl')
						.empty()
						.append('<option selected value=1>CBL</option>')
						.trigger('change');
					$(contentWB + ' .transactionnumber')
						.focus()
						.select();
				});
			});
		});
	});
/************* NEW BTN - END *****************/

$(document).on('change', contentWB + ' .waybill-documentdate', function () {
	$(contentWB + ' .waybill-pickupdate').val($(contentWB + ' .waybill-documentdate').val());
});

/************* EDIT BTN *********************/
$(document)
	.off('click', contentWB + ' #waybill-trans-editbtn:not(".disabled")')
	.on('click', contentWB + ' #waybill-trans-editbtn:not(".disabled")', function () {
		var button = $(this);
		button.addClass('disabled');

		var wbnumber = $(contentWB + ' #pgtxnwaybill-id').attr('pgtxnwaybill-number');

		$.post(server + 'waybill.php', { checkWaybillEnableEdit: 'F#@!3R3ksk#Op1NEi34smo1sonk&$', wbnumber: wbnumber }, function (data) {
			rp = $.parseJSON(data);
			if (rp['response'] == 'success') {
				processWB = 'edit';
				enableFieldsWB();
				button.removeClass('disabled');
			} else if (rp['response'] == 'inactivemanifesttransaction') {
				say('Unable to edit transaction. Waybill is in active manifest transaction. [' + rp['manifest'] + ']');
				button.removeClass('disabled');
			} else if (rp['response'] == 'inactivebillingtransaction') {
				say('Unable to edit transaction. Waybill is in active billing transaction. [' + rp['billing'] + ']');
				button.removeClass('disabled');
			} else {
				alert(data);
				button.removeClass('disabled');
			}
		});
	});
/************* EDIT BTN - END *****************/

/************** CANCEL BTN *******************/
$(document)
	.off('click', contentWB + ' #waybill-trans-cancelbtn:not(".disabled")')
	.on('click', contentWB + ' #waybill-trans-cancelbtn:not(".disabled")', function () {
		processWB = '';

		$('#loading-img').removeClass('hidden');
		clearAllWB();
		disableFieldsWB();

		if (currentWaybillTxn != '') {
			getWaybillInformation(currentWaybillTxn);
		} else {
			$(contentWB + ' .transactionnumber').val('');
			$(contentWB + ' .topbuttonsdiv').html(
				"<div class='padded-with-border-engraved topbuttonswrapper'><div class='button-group'><div class='button-group-btn active' title='New' id='waybill-trans-newbtn'><img src='../resources/img/add.png'></div></div></div>"
			); //<div class='button-group-btn active' title='Upload' id='waybill-trans-uploadbtn'><img src='../resources/img/upload.png'></div>
		}

		setTimeout(function () {
			$('#loading-img').addClass('hidden');
		}, 500);
		$('.content').animate({ scrollTop: 0 }, 300);
	});
/************ CANCEL BTN - END *****************/

/************** SAVE BTN *******************/
$(document)
	.off('click', contentWB + ' #waybill-trans-savebtn:not(".disabled")')
	.on('click', contentWB + ' #waybill-trans-savebtn:not(".disabled")', function () {
		var button = $(this),
			waybillnumber = $(contentWB + ' .transactionnumber').val(),
			bookingnumber = $(contentWB + ' .waybill-bookingnumber').val(),
			waybilltype = $(contentWB + ' .waybill-waybilltype').val(),
			reference = $(contentWB + ' .waybill-reference').val(),
			parceltype = 'NULL'; //$(contentWB+' .waybill-parceltype').val(),
		(pouchsize = ''),
			(expresstransactiontype = ''),
			(rushflag = $(contentWB + ' .waybill-rushflag').val()),
			(tpl = $(contentWB + ' .waybill-3pl').val()),
			(pulloutflag = $(contentWB + ' .waybill-pulloutflag').val()),
			(origin = $(contentWB + ' .waybill-origin').val()),
			(destination = $(contentWB + ' .waybill-destination').val()),
			(destinationroute = $(contentWB + ' .waybill-destinationroute').val()),
			(pickupdate = $(contentWB + ' .waybill-pickupdate').val()),
			(onholdflag = $(contentWB + ' .waybill-onholdcheckbox').prop('checked')),
			(onholdremarks = $(contentWB + ' .waybill-onholdremarks').val()),
			(remarks = $(contentWB + ' .waybill-remarks').val()),
			(mawbl = $(contentWB + ' .waybill-mawbl').val()),
			(documentdate = $(contentWB + ' .waybill-documentdate').val()),
			(deliverydate = $(contentWB + ' .waybill-deliverydate').val()),
			(waybillstatus = $(contentWB + ' .statusdiv').text()),
			//manifestnumber = $(contentWB+' .waybill-manifestnumber').val(),
			//invoicenumber = $(contentWB+' .waybill-invoicenumber').val(),
			(shipperid = $(contentWB + ' .waybill-shipper-systemid').val()),
			(shipperaccountnumber = $(contentWB + ' .waybill-shipper-accountnumber').val()),
			(shipperaccountname = $(contentWB + ' .waybill-shipper-accountname').val()),
			(shippertel = $(contentWB + ' .waybill-shipper-telephone').val()),
			(shippercontact = $(contentWB + ' .waybill-shipper-contactperson').val()),
			(shippercompanyname = $(contentWB + ' .waybill-shipper-companyname').val()),
			(shipperpodinstruction = $(contentWB + ' .waybill-shipper-podinstruction').val()),
			(shipperstreet = $(contentWB + ' .waybill-shipper-street').val()),
			(shipperdistrict = $(contentWB + ' .waybill-shipper-district').val()),
			(shippercity = $(contentWB + ' .waybill-shipper-city').val()),
			(shipperprovince = $(contentWB + ' .waybill-shipper-province').val()),
			(shipperzipcode = $(contentWB + ' .waybill-shipper-zipcode').val()),
			(shippercountry = $(contentWB + ' .waybill-shipper-country').val()),
			(pickupstreet = $(contentWB + ' .waybill-shipper-pickupstreet').val()),
			(pickupdistrict = $(contentWB + ' .waybill-shipper-pickupdistrict').val()),
			(pickupcity = $(contentWB + ' .waybill-shipper-pickupcity').val()),
			(pickupprovince = $(contentWB + ' .waybill-shipper-pickupprovince').val()),
			(pickupzipcode = $(contentWB + ' .waybill-shipper-pickupzipcode').val()),
			(pickupcountry = $(contentWB + ' .waybill-shipper-pickupcountry').val()),
			(consigneeid = $(contentWB + ' .waybill-consignee-systemid').val()),
			(consigneeaccountnumber = $(contentWB + ' .waybill-consignee-accountnumber').val()),
			(consigneeaccountname = $(contentWB + ' .waybill-consignee-accountname').val()),
			(consigneetel = $(contentWB + ' .waybill-consignee-telephone').val()),
			(consigneecompanyname = $(contentWB + ' .waybill-consignee-companyname').val()),
			(secondaryrecipient = $(contentWB + ' .waybill-consignee-secondary').val()),
			(consigneestreet = $(contentWB + ' .waybill-consignee-street').val()),
			(consigneedistrict = $(contentWB + ' .waybill-consignee-district').val()),
			(consigneecity = $(contentWB + ' .waybill-consignee-city').val()),
			(consigneeprovince = $(contentWB + ' .waybill-consignee-province').val()),
			(consigneezipcode = $(contentWB + ' .waybill-consignee-zipcode').val()),
			(consigneecountry = $(contentWB + ' .waybill-consignee-country').val()),
			(numberofpackage = parseInt($(contentWB + ' .waybill-numberofpackages').val())),
			(declaredvalue = $(contentWB + ' .waybill-declaredvalue').val()),
			(actualweight = $(contentWB + ' .waybill-actualweight').val()),
			(freightcomputation = $(contentWB + ' .waybill-freightcomputation').val()),
			(chargeableweight = $(contentWB + ' .waybill-chargeableweight').val()),
			(freight = $(contentWB + ' .waybill-freight').val()),
			(valuation = $(contentWB + ' .waybill-valuation').val()),
			(insurancerate = $(contentWB + ' .waybill-insurancerate').val()),
			(fuelrate = $(contentWB + ' .waybill-fuelrate').val()),
			(bunkerrate = $(contentWB + ' .waybill-bunkerrate').val()),
			(minimumrate = $(contentWB + ' .waybill-minimumrate').val()),
			(vwcbm = $(contentWB + ' .waybill-vwcbm').val()),
			(vw = $(contentWB + ' .waybill-vw').val()),
			(vat = $(contentWB + ' .waybill-vat').val()),
			(totalamount = $(contentWB + ' .waybill-totalamount').val()),
			(subtotal = $(contentWB + ' .waybill-subtotal').val()),
			(totalregularcharges = $(contentWB + ' .waybill-regularcharges').val()),
			(totalotherchargesvatable = $(contentWB + ' .waybill-otherchargesvatable').val()),
			(totalotherchargesnonvatable = $(contentWB + ' .waybill-otherchargesnonvatable').val()),
			(zeroratedflag = $(contentWB + ' .waybill-zeroratedcheckbox').prop('checked')),
			(services = $(contentWB + ' .waybill-services').val()),
			(modeoftransport = $(contentWB + ' .waybill-modeoftransport').val()),
			(handlinginstruction = $(contentWB + ' .waybill-handlinginstruction').val()),
			(deliveryinstruction = $(contentWB + ' .waybill-deliveryinstruction').val()),
			(accompanyingdocument = $(contentWB + ' .waybill-document').val()),
			(transportcharges = $(contentWB + ' .waybill-transportcharges').val()),
			(paymode = $(contentWB + ' .waybill-paymode').val()),
			(shipmentdescription = $(contentWB + ' .waybill-shipmentdescription').val()),
			(carrier = $(contentWB + ' .waybill-carrier').val()),
			(shipperrepname = $(contentWB + ' .waybill-shipperrepname').val()),
			(costcenter = $(contentWB + ' .waybill-costcenter').val()),
			(amountforcollection = $(contentWB + ' .waybill-amountforcollection').val()),
			(returndocumentfee = $(contentWB + ' .waybill-returndocumentfee').val()),
			(waybillfee = $(contentWB + ' .waybill-waybillfee').val()),
			(securityfee = $(contentWB + ' .waybill-securityfee').val()),
			(docstampfee = $(contentWB + ' .waybill-docstampfee').val()),
			(baseoda = $(contentWB + ' .waybill-baseoda').val()),
			(shipperoda = $(contentWB + ' .waybill-shipperoda').val()),
			(oda = $(contentWB + ' .waybill-oda').val()),
			(freightcost = $(contentWB + ' .waybill-freightcost').val()),
			(pulloutfee = 0), //$(contentWB+" .waybill-pulloutfee").val(),
			(fixedrateamount = $(contentWB + ' .waybill-fixedrateamount').val()),
			(totalhandlingcharges = $(contentWB + ' .waybill-totalhandlingcharges').val()),
			(odaflag = $(contentWB + ' .waybill-odaflag').val()),
			(brand = $(contentWB + ' .waybill-brand').val()),
			(costcentercode = $(contentWB + ' .waybill-costcentercode').val()),
			(costcenter = $(contentWB + ' .waybill-costcenter').val()),
			(buyercode = $(contentWB + ' .waybill-buyercode').val()),
			(contractnumber = $(contentWB + ' .waybill-contractnumber').val()),
			(customernumber = $(contentWB + ' .waybill-customernumber').val()),
			(project = $(contentWB + ' .waybill-project').val()),
			(parkingslot = $(contentWB + ' .waybill-parkingslot').val()),
			(blockunitdistrict = $(contentWB + ' .waybill-blockunitdistrict').val()),
			(lotfloor = $(contentWB + ' .waybill-lotfloor').val()),
			(agent = $(contentWB + ' .waybill-agent').val()),
			(shipmenttype = $(contentWB + ' .waybill-shipmenttype').val()),
			(shipmentmode = $(contentWB + ' .waybill-shipmentmode').val()),
			(source = processWB),
			(linedesc = []),
			(lineamount = []),
			(linevatflag = []),
			(linepackagedimlength = []),
			(linepackagedimwidth = []),
			(linepackagedimheight = []),
			(linepackagedimqty = []),
			(linepackagedimvw = []),
			(linepackagedimcbm = []),
			(linepackagedimuom = []),
			(linepackagedimactualweight = []),
			(id = '');

		button.addClass('disabled');
		$(contentWB + ' .header-errordiv, ' + contentWB + ' .detail-errordiv').empty();

		if (processWB == 'edit') {
			id = $(contentWB + ' #pgtxnwaybill-id').val();
		}

		/*if(paymode=='SERVICE CARGO'){
		costcenter = $(contentWB+' .waybill-costcenter').val();
	}*/

		if (waybilltype == 'PARCEL') {
			pouchsize = $(contentWB + ' .waybill-pouchsize').val();
		} else {
			parceltype = 'NULL';
			vwcbm = '';
			vw = '';
			//actualweight = '';
			numberofpackage = 1;

			pouchsize = $(contentWB + ' .waybill-pouchsize').val();
			expresstransactiontype = $(contentWB + ' .waybill-expresstransactiontype').val();
		}

		if (bookingnumber == null || bookingnumber == '' || bookingnumber == 'null' || bookingnumber == 'NULL') {
			$(contentWB + ' .header-errordiv').html(
				"<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select corresponding booking number.</div></div>"
			);
			$('.content').animate({ scrollTop: 0 }, 500);
			$(contentWB + ' .waybill-bookingnumber').select2('open');
			button.removeClass('disabled');
		} else if (shipmenttype == '' || shipmenttype == null || shipmenttype == 'null' || shipmenttype == 'NULL') {
			$(contentWB + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide shipment type</div></div>");
			$('.content').animate({ scrollTop: 0 }, 500);
			button.removeClass('disabled');
		} else if (shipmentmode == '' || shipmentmode == null || shipmentmode == 'null' || shipmentmode == 'NULL') {
			$(contentWB + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide shipment mode</div></div>");
			$('.content').animate({ scrollTop: 0 }, 500);
			button.removeClass('disabled');
		} else if (tpl == '' || tpl == null || tpl == 'null' || tpl == 'NULL' || tpl == undefined) {
			$(contentWB + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select third party logistic (3PL)</div></div>");
			$('.content').animate({ scrollTop: 0 }, 500);
			button.removeClass('disabled');
		} else if (mawbl == '' || mawbl == null || mawbl == 'null' || mawbl == 'NULL') {
			$(contentWB + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide mawbl</div></div>");
			$('.content').animate({ scrollTop: 0 }, 500);
			$(contentWB + ' .waybill-mawbl').focus();
			button.removeClass('disabled');
		} else if (modeoftransport == '' || modeoftransport == null || modeoftransport == 'null' || modeoftransport == 'NULL') {
			$(contentWB + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select mode of transport</div></div>");
			$('.content').animate({ scrollTop: 0 }, 500);
			$(contentWB + ' .waybill-modeoftransport').focus();
			button.removeClass('disabled');
		} else if (numberofpackage == '' || !numberofpackage > 0) {
			$(contentWB + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide number of packages</div></div>");
			$('.content').animate({ scrollTop: 0 }, 500);
			$(contentWB + ' .waybill-numberofpackages').focus();
			button.removeClass('disabled');
		} else if (declaredvalue == '' || declaredvalue < 0) {
			$(contentWB + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide declared value</div></div>");
			$('.content').animate({ scrollTop: 0 }, 500);
			$(contentWB + ' .waybill-declaredvalue').focus();
			button.removeClass('disabled');
		} else if (waybilltype == 'PARCEL' && (actualweight == '' || !actualweight > 0)) {
			$(contentWB + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide actual weight</div></div>");
			$('.content').animate({ scrollTop: 0 }, 500);
			$(contentWB + ' .waybill-actualweight').focus();
			button.removeClass('disabled');
		} else if (shipmentdescription.trim() == '') {
			$(contentWB + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide shipment description</div></div>");
			$('.content').animate({ scrollTop: 0 }, 500);
			$(contentWB + ' .waybill-shipmentdescription').focus();
			button.removeClass('disabled');
		} else if (shipperid == '') {
			/*else if(waybilltype=='PARCEL'&&(parceltype==''||parceltype==null||parceltype=='null'||parceltype=='NULL'||parceltype==undefined)){
		$(contentWB+' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select parcel type.</div></div>");
		$('.content').animate({scrollTop:0},500);
		button.removeClass('disabled');
	}*/
			/*else if(origin==''||origin==null||origin=='null'||origin=='NULL'){
		$(contentWB+' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select an origin</div></div>");
		$('.content').animate({scrollTop:0},500);
		//$(modal+' .waybill-shipperpickupaddresslookup-province').focus();
		button.removeClass('disabled');
	}
	else if(destination==''||destination==null||destination=='null'||destination=='NULL'){
		$(contentWB+' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a destination</div></div>");
		$('.content').animate({scrollTop:0},500);
		button.removeClass('disabled');
	}
	else if(destinationroute==''||destinationroute==null||destinationroute=='null'||destinationroute=='NULL'){
		$(contentWB+' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select destination route.</div></div>");
		$('.content').animate({scrollTop:0},500);
		button.removeClass('disabled');
	}
	else if(pickupdate==''||checkDateFormat(pickupdate)!=1){
		$(contentWB+' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide valid pickup date</div></div>");
		$('.content').animate({scrollTop:0},500);
		$(contentWB+' .waybill-pickupdate').focus();
		button.removeClass('disabled');
	}*/
			/*else if(deliverydate==''||checkDateFormat(deliverydate)!=1){
		$(contentWB+' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide valid delivery date</div></div>");
		$('.content').animate({scrollTop:0},500);
		$(contentWB+' .waybill-deliverydate').focus();
		button.removeClass('disabled');
	}*/
			/*else if(documentdate==''||checkDateFormat(documentdate)!=1){
		$(contentWB+' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide valid document date</div></div>");
		$('.content').animate({scrollTop:0},500);
		$(contentWB+' .waybill-documentdate').focus();
		button.removeClass('disabled');
	}
	else if(onholdflag==true&&onholdremarks.trim()==''){
		$(contentWB+' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide remarks for holding waybill transaction</div></div>");
		$('.content').animate({scrollTop:0},500);
		$(contentWB+' .waybill-onholdremarks').focus();
		button.removeClass('disabled');
	}*/
			$(contentWB + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a shipper</div></div>");
			$('.content').animate({ scrollTop: 200 }, 500);
			button.removeClass('disabled');
		} else if (shippercontact == '') {
			$(contentWB + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide shipper contact person.</div></div>");
			$('.content').animate({ scrollTop: 200 }, 500);
			$(contentWB + ' .waybill-shipper-contactperson').focus();
			button.removeClass('disabled');
		} else if (consigneeid == '') {
			/*else if(shippertel==''){
		$(contentWB+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide shipper contact information.</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(contentWB+' .waybill-shipper-telephone').focus();
		button.removeClass('disabled');
	}
	else if(shipperstreet==''){
		$(contentWB+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide shipper street address.</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(contentWB+' .waybill-shipper-street').focus();
		button.removeClass('disabled');
	}
	else if(shippercity==''){
		$(contentWB+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide shipper city.</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(contentWB+' .waybill-shipper-city').focus();
		button.removeClass('disabled');
	}
	else if(shipperprovince==''){
		$(contentWB+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide shipper province.</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(contentWB+' .waybill-shipper-province').focus();
		button.removeClass('disabled');
	}
	else if(reference==''){
		$(contentWB+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a tracking number</div></div>");
		$('.content').animate({scrollTop:500},500);
		button.removeClass('disabled');
	}*/
			$(contentWB + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a consignee</div></div>");
			$('.content').animate({ scrollTop: 200 }, 500);
			button.removeClass('disabled');
		} else if (consigneeaccountname == '' || consigneeaccountname == null) {
			$(contentWB + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select consignee</div></div>");
			$('.content').animate({ scrollTop: 200 }, 500);
			button.removeClass('disabled');
		} else if (pickupdate.trim() == '') {
			/*else if(consigneecity==''){
		$(contentWB+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide consignee city.</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(contentWB+' .waybill-consignee-city').focus();
		button.removeClass('disabled');
	}
	else if(consigneeprovince==''){
		$(contentWB+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide consignee province.</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(contentWB+' .waybill-consignee-province').focus();
		button.removeClass('disabled');
	}*/

			/*else if(waybilltype=='PARCEL'&&(vwcbm==''||!vwcbm>0)){
		$(contentWB+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide cbm</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(contentWB+' .waybill-vwcbm').focus();
		button.removeClass('disabled');
	}
	else if(waybilltype=='PARCEL'&&(vw==''||!vw>0)){
		$(contentWB+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide volumetric weight</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(contentWB+' .waybill-vw').focus();
		button.removeClass('disabled');
	}
	else if(waybilltype=='DOCUMENT'&&(pouchsize==''||pouchsize==null||pouchsize=='null'||pouchsize=='NULL'||pouchsize==undefined)){
		$(contentWB+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select pouch size</div></div>");
		$('.content').animate({scrollTop:500},500);
		button.removeClass('disabled');
	}
	else if(expresstransactiontype=='DOCUMENT'&&(expresstransactiontype==''||expresstransactiontype==null||expresstransactiontype=='null'||expresstransactiontype=='NULL'||expresstransactiontype==undefined)){
		$(contentWB+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select express transaction type</div></div>");
		$('.content').animate({scrollTop:500},500);
		button.removeClass('disabled');
	}*/
			/*else if(chargeableweight==''||!chargeableweight>0){
		$(contentWB+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide chargeable weight.</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(contentWB+' .waybill-chargeableweight').focus();
		button.removeClass('disabled');
	}
	else if(freight==''||!freight>0){
		$(contentWB+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide freight.</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(contentWB+' .waybill-freight').focus();
		button.removeClass('disabled');
	}
	else if(valuation==''||!valuation>0){
		$(contentWB+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide valuation.</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(contentWB+' .waybill-valuation').focus();
		button.removeClass('disabled');
	}
	else if(chargeableweight==''||!chargeableweight>0){
		$(contentWB+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide chargeable weight.</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(contentWB+' .waybill-chargeableweight').focus();
		button.removeClass('disabled');
	}*/
			/*else if(waybilltype=='PARCEL'&&(services==''||services==null||services=='null'||services=='NULL')){
		$(contentWB+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select what type of service</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(contentWB+' .waybill-services').focus();
		button.removeClass('disabled');
	}
	
	else if(waybilltype=='PARCEL'&&(accompanyingdocument==''||accompanyingdocument==null||accompanyingdocument=='null'||accompanyingdocument=='NULL')){
		$(contentWB+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select accompanying document</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(contentWB+' .waybill-document').focus();
		button.removeClass('disabled');
	}
	else if(waybilltype=='PARCEL'&&(deliveryinstruction==''||deliveryinstruction==null||deliveryinstruction=='null'||deliveryinstruction=='NULL')){
		$(contentWB+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select delivery instruction</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(contentWB+' .waybill-deliveryinstruction').focus();
		button.removeClass('disabled');
	}
	else if(waybilltype=='PARCEL'&&(paymode==''||paymode==null||paymode=='null'||paymode=='NULL')){
		$(contentWB+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select pay mode</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(contentWB+' .waybill-paymode').focus();
		button.removeClass('disabled');
	}*/
			/*else if(waybilltype=='PARCEL'&&(costcenter.trim()==''&&paymode.toUpperCase()=='SERVICE CARGO')){
		$(contentWB+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide cost center</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(contentWB+' .waybill-costcenter').focus();
		button.removeClass('disabled');
	}*/
			$(contentWB + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide pickup date</div></div>");
			//$('.content').animate({scrollTop:0},500);
			$(contentWB + ' .waybill-pickupdate').focus();
			button.removeClass('disabled');
		} else if (pouchsize == '' || pouchsize == null || pouchsize == 'null' || pouchsize == 'NULL' || pouchsize == undefined) {
			$(contentWB + ' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select pouch size</div></div>");
			$('.content').animate({ scrollTop: 1000 }, 500);
			button.removeClass('disabled');
			//$(contentWB+' .waybill-pouchsize').select2('open');
		} else {
			$(contentWB + ' #waybill-otherchargestbl tbody tr.wbotherchargesrow').each(function () {
				linedesc.push($(this).find('.waybill-othercharges-description').text());
				lineamount.push($(this).find('.waybill-othercharges-amount').text());
				linevatflag.push($(this).find('.waybill-othercharges-vatable').text());
			});
			if (waybilltype == 'PARCEL') {
				$(contentWB + ' #waybill-packagedimensionsmodaltbl tbody tr.wbpackagedimensions').each(function () {
					linepackagedimlength.push($(this).find('.waybill-packagedimensiontbl-length').text());
					linepackagedimwidth.push($(this).find('.waybill-packagedimensiontbl-width').text());
					linepackagedimheight.push($(this).find('.waybill-packagedimensiontbl-height').text());
					linepackagedimqty.push($(this).find('.waybill-packagedimensiontbl-quantity').text());
					linepackagedimvw.push($(this).find('.waybill-packagedimensiontbl-volweight').text());
					linepackagedimcbm.push($(this).find('.waybill-packagedimensiontbl-cbm').text());
					linepackagedimuom.push($(this).find('.waybill-packagedimensiontbl-uom').text());
					linepackagedimactualweight.push($(this).find('.waybill-packagedimensiontbl-actualweight').text());
				});
			}

			$('#loading-img').removeClass('hidden');
			//button.removeClass('disabled');
			$(contentWB + ' .header-errordiv, ' + contentWB + ' .detail-errordiv').empty();
			$.post(
				server + 'waybill.php',
				{
					SaveWaybillTransaction: 'oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@',
					agent: agent,
					id: id,
					mawbl: mawbl,
					reference: reference,
					waybillnumber: waybillnumber,
					bookingnumber: bookingnumber,
					origin: origin,
					destination: destination,
					destinationroute: destinationroute,
					onholdflag: onholdflag,
					onholdremarks: onholdremarks,
					pickupdate: pickupdate,
					remarks: remarks,
					documentdate: documentdate,
					shipperid: shipperid,
					shipperaccountnumber: shipperaccountnumber,
					shipperaccountname: shipperaccountname,
					shippertel: shippertel,
					shippercompanyname: shippercompanyname,
					shipperstreet: shipperstreet,
					shipperdistrict: shipperdistrict,
					shippercity: shippercity,
					shipperprovince: shipperprovince,
					shipperzipcode: shipperzipcode,
					shippercountry: shippercountry,
					pickupstreet: pickupstreet,
					pickupdistrict: pickupdistrict,
					pickupcity: pickupcity,
					pickupprovince: pickupprovince,
					pickupzipcode: pickupzipcode,
					pickupcountry: pickupcountry,
					consigneeid: consigneeid,
					consigneeaccountnumber: consigneeaccountnumber,
					consigneeaccountname: consigneeaccountname,
					consigneetel: consigneetel,
					consigneecompanyname: consigneecompanyname,
					consigneestreet: consigneestreet,
					consigneedistrict: consigneedistrict,
					consigneecity: consigneecity,
					consigneeprovince: consigneeprovince,
					consigneezipcode: consigneezipcode,
					consigneecountry: consigneecountry,
					numberofpackage: numberofpackage,
					declaredvalue: declaredvalue,
					actualweight: actualweight,
					vwcbm: vwcbm,
					chargeableweight: chargeableweight,
					freight: freight,
					valuation: valuation,
					totalamount: totalamount,
					vat: vat,
					zeroratedflag: zeroratedflag,
					services: services,
					modeoftransport: modeoftransport,
					deliveryinstruction: deliveryinstruction,
					accompanyingdocument: accompanyingdocument,
					transportcharges: transportcharges,
					paymode: paymode,
					carrier: carrier,
					shipperrepname: shipperrepname,
					shipmentdescription: shipmentdescription,
					source: source,
					lineamount: lineamount,
					linedesc: linedesc,
					freightcomputation: freightcomputation,
					insurancerate: insurancerate,
					fuelrate: fuelrate,
					bunkerrate: bunkerrate,
					minimumrate: minimumrate,
					subtotal: subtotal,
					linepackagedimlength: linepackagedimlength,
					linepackagedimwidth: linepackagedimwidth,
					linepackagedimheight: linepackagedimheight,
					linepackagedimqty: linepackagedimqty,
					linepackagedimvw: linepackagedimvw,
					linepackagedimcbm: linepackagedimcbm,
					deliverydate: deliverydate,
					handlinginstruction: handlinginstruction,
					linepackagedimuom: linepackagedimuom,
					linepackagedimactualweight: linepackagedimactualweight,
					vw: vw,
					costcenter: costcenter,
					shipperpodinstruction: shipperpodinstruction,
					amountforcollection: amountforcollection,
					waybillstatus: waybillstatus,
					waybilltype: waybilltype,
					rushflag: rushflag,
					pulloutflag: pulloutflag,
					pouchsize: pouchsize,
					baseoda: baseoda,
					shipperoda: shipperoda,
					oda: oda,
					pulloutfee: pulloutfee,
					fixedrateamount: fixedrateamount,
					totalhandlingcharges: totalhandlingcharges,
					returndocumentfee: returndocumentfee,
					waybillfee: waybillfee,
					securityfee: securityfee,
					docstampfee: docstampfee,
					totalregularcharges: totalregularcharges,
					totalotherchargesvatable: totalotherchargesvatable,
					totalotherchargesnonvatable: totalotherchargesnonvatable,
					linevatflag: linevatflag,
					expresstransactiontype: expresstransactiontype,
					odaflag: odaflag,
					parceltype: parceltype,
					tpl: tpl,
					secondaryrecipient: secondaryrecipient,
					shippercontact: shippercontact,
					brand: brand,
					costcentercode: costcentercode,
					costcenter: costcenter,
					buyercode: buyercode,
					contractnumber: contractnumber,
					customernumber: customernumber,
					project: project,
					parkingslot: parkingslot,
					blockunitdistrict: blockunitdistrict,
					lotfloor: lotfloor,
					freightcost: freightcost,
					shipmenttype: shipmenttype,
					shipmentmode: shipmentmode
				},
				function (data) {
					//alert(data);
					data = $.parseJSON(data);

					if (data['response'] == 'success') {
						$('#loading-img').addClass('hidden');

						loadpage(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='waybill-menutabpane']", 'transactions/waybill.php?reference=' + data['txnnumber']);
						button.removeClass('disabled');
					} else if (data['response'] == 'waybillexists') {
						$(contentWB + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Waybill number exists.</div></div>");
						$('.content').animate({ scrollTop: 0 }, 500);
						$(contentWB + ' .transactionnumber').focus();
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					} else if (data['response'] == 'invalidpickupdate') {
						$(contentWB + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid pickup date.</div></div>");
						$('.content').animate({ scrollTop: 0 }, 500);
						$(contentWB + ' .waybill-pickupdate').focus();
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					} else if (data['response'] == 'invaliddeliverydate') {
						$(contentWB + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid delivery date.</div></div>");
						$('.content').animate({ scrollTop: 0 }, 500);
						$(contentWB + ' .waybill-deliverydate').focus();
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					} else if (data['response'] == 'invaliddocdate') {
						$(contentWB + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid document date.</div></div>");
						$('.content').animate({ scrollTop: 0 }, 500);
						$(contentWB + ' .waybill-documentdate').focus();
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					} else if (data['response'] == 'exceedsmaxweight') {
						say(
							'Unable to save changes. Actual Weight exceeds max weight allowed for selected pouch size. <br>Max Weight: ' +
								data['pouchsizemaxweight'] +
								'<br>Actual Weight: ' +
								actualweight
						);
						$(contentWB + ' .waybill-actualweight').focus();
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					} else if (data['response'] == 'exceedscreditlimit') {
						say('Unable to save changes. Total amount will exceed shipper credit available.<br>Credit Available: ' + data['creditlimitavailable']);
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					} else if (data['response'] == 'invalidconsigneeid') {
						$(contentWB + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid consignee.</div></div>");
						$('.content').animate({ scrollTop: 0 }, 500);
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					} else if (data['response'] == 'invalidshipperid') {
						$(contentWB + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid shipper.</div></div>");
						$('.content').animate({ scrollTop: 0 }, 500);
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					} else if (data['response'] == 'invalidwaybill') {
						$(contentWB + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid waybill number.</div></div>");
						$('.content').animate({ scrollTop: 0 }, 500);
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					} else if (data['response'] == 'noexpressidformot') {
						say('Mode of Transport - Express not in maintenance. Please contact system administrator.');
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					} else if (data['response'] == 'noexpressidforsrvc') {
						say('Services - Express not in maintenance. Please contact system administrator.');
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					} else if (data['response'] == 'noexpressidformotsrvc') {
						say('Services/Mode of Transport - Express not in maintenance. Please contact system administrator.');
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					} else {
						alert(data);
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					}
				}
			);
		}
	});
/************ SAVE BTN - END *****************/

/************************* VOID BTN ***********************/
$(document)
	.off('click', contentWB + ' #waybill-trans-voidbtn:not(".disabled")')
	.on('click', contentWB + ' #waybill-trans-voidbtn:not(".disabled")', function () {
		var modal = '#voidwaybilltransactionmodal';
		var waybillid = $(contentWB + ' #pgtxnwaybill-id').val();
		var waybillnumber = $(contentWB + ' #pgtxnwaybill-id').attr('pgtxnwaybill-number');

		$(contentWB + ' #voidwaybilltransactionmodal-waybillid').val(waybillid);
		$(contentWB + ' .voidwaybilltransactionmodal-waybillnumber').val(waybillnumber);

		$(modal).modal('show');
		$(document)
			.off('shown.bs.modal', modal)
			.on('shown.bs.modal', modal, function () {
				$(document).off('shown.bs.modal', modal);
				$(contentWB + ' .voidwaybilltransactionmodal-remarks').focus();
			});
	});
/********************** VOID BTN - END ********************/

/************* POST BTN *********************/
$(document)
	.off('click', contentWB + ' #waybill-trans-postbtn:not(".disabled")')
	.on('click', contentWB + ' #waybill-trans-postbtn:not(".disabled")', function () {
		var id = $(contentWB + ' #pgtxnwaybill-id').val(),
			txnnumber = $(contentWB + ' #pgtxnwaybill-id').attr('pgtxnwaybill-number'),
			button = $(this);
		button.addClass('disabled');

		var consigneeaccount = $(contentWB + ' .waybill-consignee-accountname').val();
		var consigneecompany = $(contentWB + ' .waybill-consignee-companyname').val();

		if (consigneecompany == '' || consigneeaccount == '') {
			say('Please select a consignee');
			button.removeClass('disabled');
		} else {
			$.confirm({
				animation: 'bottom',
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce: 1,
				title: 'Post Transaction',
				content: 'Posting Waybill Transaction[' + txnnumber + ']. Do you want to continue?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',
				confirmButtonClass: 'btn-oceanblue',
				cancelButtonClass: 'btn-royalblue',
				theme: 'white',

				confirm: function () {
					$('#loading-img').removeClass('hidden');

					$.post(server + 'waybill.php', { postWaybillTransaction: 'oiskus49Fnla3#Oih4noiI$IO@Y#*h@o3sk', id: id, txnnumber: txnnumber }, function (data) {
						rp = $.parseJSON(data);
						if (rp['response'] == 'success') {
							$('#loading-img').addClass('hidden');
							loadpage(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='waybill-menutabpane']", 'transactions/waybill.php?reference=' + txnnumber);
							button.removeClass('disabled');
						} else if (rp['response'] == 'noaccess') {
							say('No permission to post waybill transaction [' + $txnnumber + '].');
							$('#loading-img').addClass('hidden');
							button.removeClass('disabled');
						} else if (rp['response'] == 'alreadyposted') {
							say('Waybill transaction [' + txnnumber + '] is already posted.');
							getWaybillInformation(txnnumber);
							$('#loading-img').addClass('hidden');
							button.removeClass('disabled');
						} else if (rp['response'] == 'exceedscreditlimit') {
							say('Unable to post transaction. Will exceed shipper credit limit.<br>Credit Available: ' + rp['creditlimitavailable']);
							$('#loading-img').addClass('hidden');
							button.removeClass('disabled');
						} else if (rp['response'] == 'invalidtransaction') {
							say('Unable to post transaction. Invalid waybill number [' + $txnnumber + '].');
							$('#loading-img').addClass('hidden');
							button.removeClass('disabled');
						} else {
							alert(data);
							$('#loading-img').addClass('hidden');
							button.removeClass('disabled');
						}
					});
				},
				cancel: function () {
					button.removeClass('disabled').addClass('active');
				}
			});
		}
	});
/************* POST BTN - END *****************/

/****************** OTHER CHARGES TABLE *******************/
function getTotalOtherChargesNonVatable(table) {
	var totalcharges = 0;
	table
		.column(2)
		.nodes()
		.to$()
		.each(function () {
			if ($(this).closest('tr').find('.tblotherchargesvatflag').text() == 'NO') {
				totalcharges = totalcharges + parseFloat($(this).text().replace(',', ''));
			}
		});
	return totalcharges;
}
function getTotalOtherChargesVatable(table) {
	var totalcharges = 0;
	table
		.column(2)
		.nodes()
		.to$()
		.each(function () {
			if ($(this).closest('tr').find('.tblotherchargesvatflag').text() == 'YES') {
				totalcharges = totalcharges + parseFloat($(this).text().replace(',', ''));
			}
		});
	return totalcharges;
}

function getTotalOtherCharges(table) {
	var totalcharges = 0;
	table
		.column(2)
		.nodes()
		.to$()
		.each(function () {
			totalcharges = totalcharges + parseFloat($(this).text().replace(',', ''));
		});

	return totalcharges;
}

////// INSERT RATE ROW
function waybillOtherChargesInsertRow(table) {
	var description = $(contentWB + ' .othercharges-descriptionfld').val(),
		amount = $(contentWB + ' .othercharges-amountfld').val(),
		vatable = $(contentWB + ' .othercharges-vatablefld').val();

	if (description.trim() != '' && parseFloat(amount) > 0 && (vatable == 'NO' || vatable == 'YES')) {
		amount = parseFloat(amount);
		amount = amount.toLocaleString();

		table.row
			.add([
				"<input type='checkbox' class='rowcheckbox'>",
				"<span class='waybill-othercharges-description'>" + description + '</span>',
				"<span class='waybill-othercharges-amount pull-right'>" + amount + '</span>',
				"<span class='waybill-othercharges-vatable tblotherchargesvatflag'>" + vatable + '</span>'
			])
			.search('')
			.draw();
		waybillOtherChargesClearFields();
	} else if (description.trim() == '') {
		$(contentWB + ' .othercharges-descriptionfld').focus();
	} else if (amount.trim() == '') {
		$(contentWB + ' .othercharges-amountfld').focus();
	}

	computeRatesWB();
}

$(document)
	.off('click', contentWB + ' .othercharges-insertbtn')
	.on('click', contentWB + ' .othercharges-insertbtn', function () {
		waybillOtherChargesInsertRow($(contentWB + ' #waybill-otherchargestbl').DataTable());
	});
////// INSERT RATE ROW END

////// REMOVE RATE ROW
function waybillOtherChargesRemoveRow(table) {
	$(table + ' tbody tr .rowcheckbox:checked').each(function () {
		var tr = $(this).closest('tr');
		$(table).DataTable().row(tr).remove().draw();
	});

	computeRatesWB();
}

$(document)
	.off('click', contentWB + ' .othercharges-removebtn')
	.on('click', contentWB + ' .othercharges-removebtn', function () {
		var tbl = '#waybill-otherchargestbl';
		waybillOtherChargesRemoveRow(tbl);
	});
/////// REMOVE RATE ROW END

////// CLEAR RATE INPUT FIELDS
function waybillOtherChargesClearFields() {
	$(contentWB + ' .othercharges-amountfld')
		.val('')
		.focus();
	$(contentWB + ' .othercharges-descriptionfld')
		.empty()
		.trigger('change');
}

$(document)
	.off('click', contentWB + ' .othercharges-clearbtn ')
	.on('click', contentWB + ' .othercharges-clearbtn', function () {
		waybillOtherChargesClearFields();
	});
////// CLEAR RATE INPUT FIELDS END

/****************** OTHER CHARGES TABLE - END *******************/
$(document)
	.off('change', contentWB + ' .waybill-handlinginstruction')
	.on('change', contentWB + ' .waybill-handlinginstruction', function () {
		//computeRatesWB();
	});
$(document)
	.off('change', contentWB + ' .waybill-rushflag')
	.on('change', contentWB + ' .waybill-rushflag', function () {
		//computeRatesWB();
	});
$(document)
	.off('change', contentWB + ' .waybill-pulloutflag')
	.on('change', contentWB + ' .waybill-pulloutflag', function () {
		//computeRatesWB();
	});
$(document)
	.off('change', contentWB + ' .waybill-odaflag')
	.on('change', contentWB + ' .waybill-odaflag', function () {
		//computeRatesWB();
	});
$(document)
	.off('keyup', contentWB + ' .waybill-numberofpackages')
	.on('keyup', contentWB + ' .waybill-numberofpackages', function () {
		//computeRatesWB();
	});
$(document)
	.off('change', contentWB + ' .waybill-waybilltype')
	.on('change', contentWB + ' .waybill-waybilltype', function () {
		var type = $(this).val();

		if (processWB == 'add' || processWB == 'edit') {
			showFieldsBasedOnWaybillType(type);
		}
		computeRatesWB();

		defaultExpressValue(type);
	});
$(document)
	.off('change', contentWB + ' .waybill-parceltype')
	.on('change', contentWB + ' .waybill-parceltype', function () {
		computeRatesWB();
	});
$(document)
	.off('change', contentWB + ' .waybill-pouchsize')
	.on('change', contentWB + ' .waybill-pouchsize', function () {
		computeRatesWB();
	});
$(document)
	.off('change', contentWB + ' .waybill-origin')
	.on('change', contentWB + ' .waybill-origin', function () {
		computeRatesWB();
	});
$(document)
	.off('change', contentWB + ' .waybill-destination')
	.on('change', contentWB + ' .waybill-destination', function () {
		computeRatesWB();
	});
$(document)
	.off('change', contentWB + ' .waybill-expresstransactiontype')
	.on('change', contentWB + ' .waybill-expresstransactiontype', function () {
		//computeRatesWB();
	});
$(document)
	.off('change', contentWB + ' .waybill-modeoftransport')
	.on('change', contentWB + ' .waybill-modeoftransport', function () {
		//computeRatesWB();
	});
$(document)
	.off('change', contentWB + ' .waybill-services')
	.on('change', contentWB + ' .waybill-services', function () {
		//computeRatesWB();
	});
$(document)
	.off('change', contentWB + ' .waybill-zeroratedcheckbox')
	.on('change', contentWB + ' .waybill-zeroratedcheckbox', function () {
		computeRatesWB();
	});
$(document)
	.off('focusout', contentWB + ' .waybill-amountforcollection')
	.on('focusout', contentWB + ' .waybill-amountforcollection', function () {
		//computeRatesWB();
	});
$(document)
	.off('focusout', contentWB + ' .waybill-declaredvalue')
	.on('focusout', contentWB + ' .waybill-declaredvalue', function () {
		computeRatesWB();
	});
$(document)
	.off('focusout', contentWB + ' .waybill-actualweight')
	.on('focusout', contentWB + ' .waybill-actualweight', function () {
		computeRatesWB();
	});
$(document)
	.off('focusout', contentWB + ' .waybill-vw')
	.on('focusout', contentWB + ' .waybill-vw', function () {
		computeRatesWB();
	});
$(document)
	.off('focusout', contentWB + ' .waybill-vwcbm')
	.on('focusout', contentWB + ' .waybill-vwcbm', function () {
		computeRatesWB();
	});

$(document)
	.off('change', contentWB + ' .waybill-3pl')
	.on('change', contentWB + ' .waybill-3pl', function () {
		computeRatesWB();
	});

$(document)
	.off('change', contentWB + ' .editwaybillchargesmodal-zeroratedcheckbox')
	.on('change', contentWB + ' .editwaybillchargesmodal-zeroratedcheckbox', function () {
		computeTotalsEWC();
	});

$(document)
	.off('change', contentWB + ' .waybill-bookingnumber')
	.on('change', contentWB + ' .waybill-bookingnumber', function () {
		var bookingnumber = $(this).find('option:selected').text();
		if ((processWB == 'add' || processWB == 'edit') && bookingnumber != '' && bookingnumber != null) {
			$.post(server + 'booking.php', { getBookingData: 'F#@!3R3ksk#Op1NEi34smo1sonk&$', txnnumber: bookingnumber }, function (response) {
				if (response.trim() != 'INVALID') {
					currentWaybillTxn = bookingnumber;
					data = $.parseJSON(response);

					$(contentWB + ' .waybill-shipperpickupaddresslookup-shipperid').val(data['shipperid']);
					$(contentWB + ' .waybill-shipperpickupaddresslookup-accountnumber').val(data['shipperaccountnumber']);
					$(contentWB + ' .waybill-shipperpickupaddresslookup-accountname').val(data['shipperaccountname']);

					$(contentWB + ' .waybill-remarks').val(data['remarks']);
					$(contentWB + ' .waybill-pickupdate').val(data['pickupdate']);

					$(contentWB + ' .waybill-shipper-systemid').val(data['shipperid']);
					$(contentWB + ' .waybill-shipper-accountnumber').val(data['shipperaccountnumber']);
					$(contentWB + ' .waybill-shipper-accountname').val(data['shipperaccountname']);
					$(contentWB + ' .waybill-shipper-telephone').val(data['shippertel']);
					$(contentWB + ' .waybill-shipper-companyname').val(data['shippercompanyname']);
					$(contentWB + ' .waybill-shipper-street').val(data['shipperstreet']);
					$(contentWB + ' .waybill-shipper-district').val(data['shipperdistrict']);
					$(contentWB + ' .waybill-shipper-city').val(data['shippercity']);
					$(contentWB + ' .waybill-shipper-province').val(data['shipperprovince']);
					$(contentWB + ' .waybill-shipper-zipcode').val(data['shipperzipcode']);

					$(contentWB + ' .waybill-shipper-pickupstreet').val(data['pickupstreet']);
					$(contentWB + ' .waybill-shipper-pickupdistrict').val(data['pickupdistrict']);
					$(contentWB + ' .waybill-shipper-pickupcity').val(data['pickupcity']);
					$(contentWB + ' .waybill-shipper-pickupprovince').val(data['pickupprovince']);
					$(contentWB + ' .waybill-shipper-pickupzipcode').val(data['pickupzipcode']);

					$(contentWB + ' .waybill-consignee-systemid').val(data['consigneeid']);
					$(contentWB + ' .waybill-consignee-accountnumber').val(data['consigneeaccountnumber']);
					$(contentWB + ' .waybill-consignee-accountname').val(data['consigneeaccountname']);
					$(contentWB + ' .waybill-consignee-telephone').val(data['consigneetel']);
					$(contentWB + ' .waybill-consignee-companyname').val(data['consigneecompanyname']);
					$(contentWB + ' .waybill-consignee-street').val(data['consigneestreet']);
					$(contentWB + ' .waybill-consignee-district').val(data['consigneedistrict']);
					$(contentWB + ' .waybill-consignee-secondary').val(data['secondary_recipient']);
					$(contentWB + ' .waybill-consignee-city').val(data['consigneecity']);
					$(contentWB + ' .waybill-consignee-province').val(data['consigneeprovince']);
					$(contentWB + ' .waybill-consignee-zipcode').val(data['consigneezipcode']);

					//$(contentWB+" .waybill-numberofpackages").val(data["numberofpackage"]);
					$(contentWB + ' .waybill-declaredvalue').val(data['declaredvalue']);
					//$(contentWB+" .waybill-actualweight").val(data["actualweight"]);
					//$(contentWB+" .waybill-vwcbm").val(data["vwcbm"]);
					$(contentWB + ' .waybill-amount').val(data['amount']);
					$(contentWB + ' .waybill-paymode').val(data['paymode']);
					$(contentWB + ' .waybill-shipmentdescription').val(data['shipmentdescription']);

					getWBShipperPODInstruction();

					if (data['origin'] != null) {
						$(inputfieldsWB + ' .waybill-origin')
							.empty()
							.append('<option selected value="' + data['originid'] + '">' + data['origin'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-origin')
							.empty()
							.trigger('change');
					}

					if (data['destination'] != null) {
						$(inputfieldsWB + ' .waybill-destination')
							.empty()
							.append('<option selected value="' + data['destinationid'] + '">' + data['destination'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-destination')
							.empty()
							.trigger('change');
					}

					if (data['shipperdistrict'] != null) {
						$(inputfieldsWB + ' .waybill-shipper-district')
							.empty()
							.append('<option selected value="' + data['shipperdistrict'] + '">' + data['shipperdistrict'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-shipper-district')
							.empty()
							.trigger('change');
					}
					if (data['shippercity'] != null) {
						$(inputfieldsWB + ' .waybill-shipper-city')
							.empty()
							.append('<option selected value="' + data['shippercity'] + '">' + data['shippercity'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-shipper-city')
							.empty()
							.trigger('change');
					}
					if (data['shipperprovince'] != null) {
						$(inputfieldsWB + ' .waybill-shipper-province')
							.empty()
							.append('<option selected value="' + data['shipperprovince'] + '">' + data['shipperprovince'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-shipper-province')
							.empty()
							.trigger('change');
					}
					if (data['shipperzipcode'] != null) {
						$(inputfieldsWB + ' .waybill-shipper-zipcode')
							.empty()
							.append('<option selected value="' + data['shipperzipcode'] + '">' + data['shipperzipcode'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-shipper-zipcode')
							.empty()
							.trigger('change');
					}

					if (data['shippercountry'] != null) {
						$(inputfieldsWB + ' .waybill-shipper-country')
							.empty()
							.append('<option selected value="' + data['shippercountry'] + '">' + data['shippercountry'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-shipper-country')
							.empty()
							.trigger('change');
					}

					if (data['pickupdistrict'] != null) {
						$(inputfieldsWB + ' .waybill-shipper-pickupdistrict')
							.empty()
							.append('<option selected value="' + data['pickupdistrict'] + '">' + data['pickupdistrict'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-shipper-pickupdistrict')
							.empty()
							.trigger('change');
					}
					if (data['pickupcity'] != null) {
						$(inputfieldsWB + ' .waybill-shipper-pickupcity')
							.empty()
							.append('<option selected value="' + data['pickupcity'] + '">' + data['pickupcity'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-shipper-pickupcity')
							.empty()
							.trigger('change');
					}
					if (data['pickupprovince'] != null) {
						$(inputfieldsWB + ' .waybill-shipper-pickupprovince')
							.empty()
							.append('<option selected value="' + data['pickupprovince'] + '">' + data['pickupprovince'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-shipper-pickupprovince')
							.empty()
							.trigger('change');
					}
					if (data['pickupzipcode'] != null) {
						$(inputfieldsWB + ' .waybill-shipper-pickupzipcode')
							.empty()
							.append('<option selected value="' + data['pickupzipcode'] + '">' + data['pickupzipcode'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-shipper-pickupzipcode')
							.empty()
							.trigger('change');
					}

					if (data['pickupcountry'] != null) {
						$(inputfieldsWB + ' .waybill-shipper-pickupcountry')
							.empty()
							.append('<option selected value="' + data['pickupcountry'] + '">' + data['pickupcountry'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-shipper-pickupcountry')
							.empty()
							.trigger('change');
					}

					if (data['consigneedistrict'] != null) {
						$(inputfieldsWB + ' .waybill-consignee-district')
							.empty()
							.append('<option selected value="' + data['consigneedistrict'] + '">' + data['consigneedistrict'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-consignee-district')
							.empty()
							.trigger('change');
					}
					if (data['consigneecity'] != null) {
						$(inputfieldsWB + ' .waybill-consignee-city')
							.empty()
							.append('<option selected value="' + data['consigneecity'] + '">' + data['consigneecity'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-consignee-city')
							.empty()
							.trigger('change');
					}
					if (data['consigneeprovince'] != null) {
						$(inputfieldsWB + ' .waybill-consignee-province')
							.empty()
							.append('<option selected value="' + data['consigneeprovince'] + '">' + data['consigneeprovince'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-consignee-province')
							.empty()
							.trigger('change');
					}
					if (data['consigneezipcode'] != null) {
						$(inputfieldsWB + ' .waybill-consignee-zipcode')
							.empty()
							.append('<option selected value="' + data['consigneezipcode'] + '">' + data['consigneezipcode'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-consignee-zipcode')
							.empty()
							.trigger('change');
					}

					if (data['consigneecountry'] != null) {
						$(inputfieldsWB + ' .waybill-consignee-country')
							.empty()
							.append('<option selected value="' + data['consigneecountry'] + '">' + data['consigneecountry'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-consignee-country')
							.empty()
							.trigger('change');
					}

					if (data['service'] != null) {
						$(inputfieldsWB + ' .waybill-services')
							.empty()
							.append('<option selected value="' + data['serviceid'] + '">' + data['service'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-services')
							.empty()
							.trigger('change');
					}
					if (data['modeoftransport'] != null) {
						$(inputfieldsWB + ' .waybill-modeoftransport')
							.empty()
							.append('<option selected value="' + data['modeoftransportid'] + '">' + data['modeoftransport'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-modeoftransport')
							.empty()
							.trigger('change');
					}
					if (data['handlinginstruction'] != null) {
						$(inputfieldsWB + ' .waybill-handlinginstruction')
							.empty()
							.append('<option selected value="' + data['handlinginstructionid'] + '">' + data['handlinginstruction'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-handlinginstruction')
							.empty()
							.trigger('change');
					}

					if (data['paymode'] != null) {
						$(inputfieldsWB + ' .waybill-paymode')
							.empty()
							.append('<option selected value="' + data['paymode'] + '">' + data['paymode'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-paymode')
							.empty()
							.trigger('change');
					}

					setTimeout(function () {
						computeRatesWB();
					}, 1000);
				}
			});
		}
	});

/*************** INPUT GROUP BUTTONS ****************************/
$(document)
	.off('click', contentWB + ' .inputgroupicon:not(".disabled")')
	.on('click', contentWB + ' .inputgroupicon:not(".disabled")', function () {
		if (processWB == 'add' || processWB == 'edit') {
			var modal = $(this).data('modal');
			$(modal).modal('show');
		}
	});

$(document)
	.off('click', contentWB + ' .inputgroupicon[alwaysshow]')
	.on('click', contentWB + ' .inputgroupicon[alwaysshow]', function () {
		if (processWB == '') {
			var modal = $(this).data('modal');
			$(modal).modal('show');
		}
	});

$(document)
	.off('click', contentWB + ' #waybill-pickupaddresslookupbtn:not(".disabled")')
	.on('click', contentWB + ' #waybill-pickupaddresslookupbtn:not(".disabled")', function () {
		var modal = $(this).data('modal');
		var selectedshipperID = $(modal + ' .waybill-shipperpickupaddresslookup-shipperid').val();
		var button = $(this);
		button.addClass('disabled');

		if (selectedshipperID.trim() != '' && selectedshipperID != null && selectedshipperID != undefined) {
			$(modal).modal('show');
			button.removeClass('disabled');
		} else {
			say('Please select a shipper');
			button.removeClass('disabled');
		}
	});

/********************* TABLEROW EVENTS ********************************/
$(document)
	.off('dblclick', contentWB + ' .shipperlookuprow:not(".disabled")')
	.on('dblclick', contentWB + ' .shipperlookuprow:not(".disabled")', function () {
		var id = $(this).attr('rowid');
		var modal = '#' + $(this).closest('.modal').attr('id');
		var row = $(this);
		row.addClass('disabled');

		$(modal).modal('hide');
		$(document).on('hidden.bs.modal', contentWB + ' ' + modal, function () {
			$(document).off('hidden.bs.modal', contentWB + ' ' + modal);

			$.post(server + 'shipper.php', { ShipperGetInfo: 'kjoI$H2oiaah3h0$09jDppo92po@k@', id: id }, function (data) {
				rsp = $.parseJSON(data);
				if (rsp['response'] == 'success') {
					$(inputfieldsWB + ' .waybill-shipper-systemid').val(id);
					$(inputfieldsWB + ' .waybill-shipper-accountnumber').val(rsp['accountnumber']);
					$(inputfieldsWB + ' .waybill-shipper-accountname').val(rsp['accountname']);
					$(inputfieldsWB + ' .waybill-shipper-companyname').val(rsp['companyname']);
					$(inputfieldsWB + ' .waybill-shipper-podinstruction').val(rsp['podinstruction']);
					$(inputfieldsWB + ' .waybill-shipper-street').val(rsp['companystreet']);
					$(inputfieldsWB + ' .waybill-shipper-district').val(rsp['companydistrict']);
					$(inputfieldsWB + ' .waybill-shipper-city').val(rsp['companycity']);
					$(inputfieldsWB + ' .waybill-shipper-province').val(rsp['companyprovince']);
					$(inputfieldsWB + ' .waybill-shipper-zipcode').val(rsp['companyzipcode']);

					if (rsp['vatflag'].trim() == 1) {
						$(inputfieldsWB + ' .waybill-zeroratedcheckbox').prop('checked', false);
					} else {
						$(inputfieldsWB + ' .waybill-zeroratedcheckbox').prop('checked', true);
					}

					if (rsp['companydistrict'] != null) {
						$(inputfieldsWB + ' .waybill-shipper-district')
							.empty()
							.append('<option selected value="' + rsp['companydistrict'] + '">' + rsp['companydistrict'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-shipper-district')
							.empty()
							.trigger('change');
					}
					if (rsp['companycity'] != null) {
						$(inputfieldsWB + ' .waybill-shipper-city')
							.empty()
							.append('<option selected value="' + rsp['companycity'] + '">' + rsp['companycity'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-shipper-city')
							.empty()
							.trigger('change');
					}
					if (rsp['companyzipcode'] != null) {
						$(inputfieldsWB + ' .waybill-shipper-zipcode')
							.empty()
							.append('<option selected value="' + rsp['companyzipcode'] + '">' + rsp['companyzipcode'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-shipper-zipcode')
							.empty()
							.trigger('change');
					}
					if (rsp['companyprovince'] != null) {
						$(inputfieldsWB + ' .waybill-shipper-province')
							.empty()
							.append('<option selected value="' + rsp['companyprovince'] + '">' + rsp['companyprovince'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-shipper-province')
							.empty()
							.trigger('change');
					}
					if (rsp['companycountry'] != null) {
						$(inputfieldsWB + ' .waybill-shipper-country')
							.empty()
							.append('<option selected value="' + rsp['companycountry'] + '">' + rsp['companycountry'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-shipper-country')
							.empty()
							.trigger('change');
					}

					if (rsp['paymode'] != null) {
						$(inputfieldsWB + ' .waybill-paymode')
							.empty()
							.append('<option selected value="' + rsp['paymode'] + '">' + rsp['paymode'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-paymode')
							.empty()
							.trigger('change');
					}

					$(contentWB + ' .waybill-shipperpickupaddresslookup-shipperid').val(id);
					$(contentWB + ' .waybill-shipperpickupaddresslookup-accountnumber').val(rsp['accountnumber']);
					$(contentWB + ' .waybill-shipperpickupaddresslookup-accountname').val(rsp['accountname']);

					$.post(server + 'booking.php', { ShipperDefaultContactGetInfo: 'ojoiAndElspriaoi#@po92po@k@', id: id }, function (data1) {
						rsp1 = $.parseJSON(data1);
						if (rsp1['response'] == 'success') {
							$(inputfieldsWB + ' .waybill-shipper-telephone').val(rsp1['phone']);
							row.removeClass('disabled');
						} else if (rsp1['response'] == 'nocontactinfo') {
							row.removeClass('disabled');
						} else {
							alert(data1);
							say('Unable to retrive default contact information of selected shipper.');
							row.removeClass('disabled');
						}

						$.post(server + 'booking.php', { ShipperDefaultPickupAddressGetInfo: 'ooi3h$9apsojespriaoi#@po92po@k@', id: id }, function (data2) {
							rsp2 = $.parseJSON(data2);
							if (rsp2['response'] == 'success') {
								$(inputfieldsWB + ' .waybill-shipper-pickupstreet').val(rsp2['street']);
								$(inputfieldsWB + ' .waybill-shipper-pickupdistrict').val(rsp2['district']);
								$(inputfieldsWB + ' .waybill-shipper-pickupcity').val(rsp2['city']);
								$(inputfieldsWB + ' .waybill-shipper-pickupprovince').val(rsp2['province']);
								$(inputfieldsWB + ' .waybill-shipper-pickupzipcode').val(rsp2['zipcode']);

								if (rsp2['district'] != null) {
									$(inputfieldsWB + ' .waybill-shipper-pickupdistrict')
										.empty()
										.append('<option selected value="' + rsp2['district'] + '">' + rsp2['district'] + '</option>')
										.trigger('change');
								} else {
									$(inputfieldsWB + ' .waybill-shipper-pickupdistrict')
										.empty()
										.trigger('change');
								}
								if (rsp2['city'] != null) {
									$(inputfieldsWB + ' .waybill-shipper-pickupcity')
										.empty()
										.append('<option selected value="' + rsp2['city'] + '">' + rsp2['city'] + '</option>')
										.trigger('change');
								} else {
									$(inputfieldsWB + ' .waybill-shipper-pickupcity')
										.empty()
										.trigger('change');
								}
								if (rsp2['province'] != null) {
									$(inputfieldsWB + ' .waybill-shipper-pickupprovince')
										.empty()
										.append('<option selected value="' + rsp2['province'] + '">' + rsp2['province'] + '</option>')
										.trigger('change');
								} else {
									$(inputfieldsWB + ' .waybill-shipper-pickupprovince')
										.empty()
										.trigger('change');
								}
								if (rsp2['zipcode'] != null) {
									$(inputfieldsWB + ' .waybill-shipper-pickupzipcode')
										.empty()
										.append('<option selected value="' + rsp2['zipcode'] + '">' + rsp2['zipcode'] + '</option>')
										.trigger('change');
								} else {
									$(inputfieldsWB + ' .waybill-shipper-pickupzipcode')
										.empty()
										.trigger('change');
								}
								if (rsp2['country'] != null) {
									$(inputfieldsWB + ' .waybill-shipper-pickupcountry')
										.empty()
										.append('<option selected value="' + rsp2['country'] + '">' + rsp2['country'] + '</option>')
										.trigger('change');
								} else {
									$(inputfieldsWB + ' .waybill-shipper-pickupcountry')
										.empty()
										.trigger('change');
								}
								row.removeClass('disabled');

								$(contentWB + ' .waybill-shipper-contactperson')
									.focus()
									.select();
							} else if (rsp2['response'] == 'nopickupaddressinfo') {
								row.removeClass('disabled');
							} else {
								alert(data2);
								say('Unable to retrive default pickup address information of selected shipper.');
								row.removeClass('disabled');
							}
							computeRatesWB();
						});
					});
				} else {
					alert(data);
					say('Selected shipper has invalid system ID. Please select another.');
					row.removeClass('disabled');
				}
			});
		});
	});

$(document)
	.off('dblclick', contentWB + ' .shipperpickupaddresslookuprow:not(".disabled")')
	.on('dblclick', contentWB + ' .shipperpickupaddresslookuprow:not(".disabled")', function () {
		var row = $(this);
		var modal = '#' + $(this).closest('.modal').attr('id');

		$(modal).modal('hide');
		$(document).on('hidden.bs.modal', contentWB + ' ' + modal, function () {
			$(inputfieldsWB + ' .waybill-shipper-pickupstreet').val(row.find("td[abbr='pickup_street_address'] div").text());
			$(inputfieldsWB + ' .waybill-shipper-pickupdistrict').val(row.find("td[abbr='pickup_district'] div").text());
			$(inputfieldsWB + ' .waybill-shipper-pickupcity').val(row.find("td[abbr='pickup_city'] div").text());
			$(inputfieldsWB + ' .waybill-shipper-pickupprovince').val(row.find("td[abbr='pickup_state_province'] div").text());
			$(inputfieldsWB + ' .waybill-shipper-pickupzipcode').val(row.find("td[abbr='pickup_zip_code'] div").text());

			if (row.find("td[abbr='pickup_district'] div").text().trim() != null && row.find("td[abbr='pickup_district'] div").text().trim() != '') {
				$(inputfieldsWB + ' .waybill-shipper-pickupdistrict')
					.empty()
					.append('<option selected value="' + row.find("td[abbr='pickup_district'] div").text() + '">' + row.find("td[abbr='pickup_district'] div").text() + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-shipper-pickupdistrict')
					.empty()
					.trigger('change');
			}
			if (row.find("td[abbr='pickup_city'] div").text().trim() != null && row.find("td[abbr='pickup_city'] div").text().trim() != '') {
				$(inputfieldsWB + ' .waybill-shipper-pickupcity')
					.empty()
					.append('<option selected value="' + row.find("td[abbr='pickup_city'] div").text() + '">' + row.find("td[abbr='pickup_city'] div").text() + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-shipper-pickupcity')
					.empty()
					.trigger('change');
			}
			if (row.find("td[abbr='pickup_zip_code'] div").text().trim() != null && row.find("td[abbr='pickup_zip_code'] div").text().trim() != '') {
				$(inputfieldsWB + ' .waybill-shipper-pickupcountry')
					.empty()
					.append('<option selected value="' + row.find("td[abbr='pickup_zip_code'] div").text() + '">' + row.find("td[abbr='pickup_zip_code'] div").text() + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-shipper-pickupcountry')
					.empty()
					.trigger('change');
			}
			if (row.find("td[abbr='pickup_state_province'] div").text().trim() != null && row.find("td[abbr='pickup_state_province'] div").text().trim() != '') {
				$(inputfieldsWB + ' .waybill-shipper-pickupprovince')
					.empty()
					.append('<option selected value="' + row.find("td[abbr='pickup_state_province'] div").text() + '">' + row.find("td[abbr='pickup_state_province'] div").text() + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-shipper-pickupprovince')
					.empty()
					.trigger('change');
			}
			if (row.find("td[abbr='pickup_country'] div").text().trim() != null && row.find("td[abbr='pickup_country'] div").text().trim() != '') {
				$(inputfieldsWB + ' .waybill-shipper-pickupcountry')
					.empty()
					.append('<option selected value="' + row.find("td[abbr='pickup_country'] div").text() + '">' + row.find("td[abbr='pickup_country'] div").text() + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-shipper-pickupcountry')
					.empty()
					.trigger('change');
			}
			$(document).off('hidden.bs.modal', contentWB + ' ' + modal);
		});
	});

function consigneeLookupRowSelected(id, modal) {
	$(modal).modal('hide');
	$(document).on('hidden.bs.modal', contentWB + ' ' + modal, function () {
		$(document).off('hidden.bs.modal', contentWB + ' ' + modal);

		$.post(server + 'consignee.php', { ConsigneeGetInfo: 'kjoI$H2oiaah3h0$09jDppo92po@k@', id: id }, function (data) {
			rsp = $.parseJSON(data);
			if (rsp['response'] == 'success') {
				$(inputfieldsWB + ' .waybill-consignee-systemid').val(id);
				$(inputfieldsWB + ' .waybill-consignee-idnumber').val(rsp['idnumber']);
				$(inputfieldsWB + ' .waybill-consignee-accountnumber').val(rsp['accountnumber']);
				$(inputfieldsWB + ' .waybill-consignee-accountname').val(rsp['accountname']);
				$(inputfieldsWB + ' .waybill-consignee-companyname').val(rsp['companyname']);
				$(inputfieldsWB + ' .waybill-consignee-street').val(rsp['street']);
				$(inputfieldsWB + ' .waybill-consignee-district').val(rsp['district']);
				$(inputfieldsWB + ' .waybill-consignee-city').val(rsp['city']);
				$(inputfieldsWB + ' .waybill-consignee-province').val(rsp['province']);
				$(inputfieldsWB + ' .waybill-consignee-zipcode').val(rsp['zipcode']);

				if (rsp['district'] != null) {
					$(inputfieldsWB + ' .waybill-consignee-district')
						.empty()
						.append('<option selected value="' + rsp['district'] + '">' + rsp['district'] + '</option>')
						.trigger('change');
				} else {
					$(inputfieldsWB + ' .waybill-consignee-district')
						.empty()
						.trigger('change');
				}
				if (rsp['city'] != null) {
					$(inputfieldsWB + ' .waybill-consignee-city')
						.empty()
						.append('<option selected value="' + rsp['city'] + '">' + rsp['city'] + '</option>')
						.trigger('change');
				} else {
					$(inputfieldsWB + ' .waybill-consignee-city')
						.empty()
						.trigger('change');
				}
				if (rsp['zipcode'] != null) {
					$(inputfieldsWB + ' .waybill-consignee-zipcode')
						.empty()
						.append('<option selected value="' + rsp['zipcode'] + '">' + rsp['zipcode'] + '</option>')
						.trigger('change');
				} else {
					$(inputfieldsWB + ' .waybill-consignee-zipcode')
						.empty()
						.trigger('change');
				}

				if (rsp['province'] != null) {
					$(inputfieldsWB + ' .waybill-consignee-province')
						.empty()
						.append('<option selected value="' + rsp['province'] + '">' + rsp['province'] + '</option>')
						.trigger('change');
				} else {
					$(inputfieldsWB + ' .waybill-consignee-province')
						.empty()
						.trigger('change');
				}

				if (rsp['provinceid'] != '' && rsp['provinceid'] != null) {
					$(inputfieldsWB + ' .waybill-destination')
						.empty()
						.append('<option selected value="' + rsp['provinceid'] + '">' + rsp['province'] + '</option>')
						.trigger('change');
				} else {
					$(inputfieldsWB + ' .waybill-destination')
						.empty()
						.trigger('change');
				}

				if (rsp['country'] != null) {
					$(inputfieldsWB + ' .waybill-consignee-country')
						.empty()
						.append('<option selected value="' + rsp['country'] + '">' + rsp['country'] + '</option>')
						.trigger('change');
				} else {
					$(inputfieldsWB + ' .waybill-consignee-country')
						.empty()
						.trigger('change');
				}

				$.post(server + 'booking.php', { ConsigneeDefaultContactGetInfo: 'oj94oifof#o@odlspriaoi#@po92po@k@', id: id }, function (data1) {
					rsp1 = $.parseJSON(data1);
					if (rsp1['response'] == 'success') {
						$(inputfieldsWB + ' .waybill-consignee-telephone').val(rsp1['phone']);
						//$(inputfieldsWB+' .waybill-numberofpackages').focus();
						//row.removeClass('disabled');
					} else if (rsp1['response'] == 'nocontactinfo') {
						//row.removeClass('disabled');
					} else {
						alert(data1);
						say('Unable to retrive default contact information of selected consignee.');
						//row.removeClass('disabled');
					}

					$(contentWB + ' .waybill-consignee-telephone')
						.focus()
						.select();
				});
			} else {
				alert(data);
				say('Selected consignee has invalid system ID. Please select another.');
				row.removeClass('disabled');
			}
		});
	});
}

$(document)
	.off('dblclick', contentWB + ' .consigneelookuprow:not(".disabled")')
	.on('dblclick', contentWB + ' .consigneelookuprow:not(".disabled")', function () {
		var id = $(this).attr('rowid');
		var modal = '#' + $(this).closest('.modal').attr('id');
		var row = $(this);
		row.addClass('disabled');

		$(modal).modal('hide');
		$(document).on('hidden.bs.modal', contentWB + ' ' + modal, function () {
			$(document).off('hidden.bs.modal', contentWB + ' ' + modal);

			$.post(server + 'consignee.php', { ConsigneeGetInfo: 'kjoI$H2oiaah3h0$09jDppo92po@k@', id: id }, function (data) {
				rsp = $.parseJSON(data);
				if (rsp['response'] == 'success') {
					$(inputfieldsWB + ' .waybill-consignee-systemid').val(id);
					$(inputfieldsWB + ' .waybill-consignee-idnumber').val(rsp['idnumber']);
					$(inputfieldsWB + ' .waybill-consignee-accountnumber').val(rsp['accountnumber']);
					$(inputfieldsWB + ' .waybill-consignee-accountname').val(rsp['accountname']);
					$(inputfieldsWB + ' .waybill-consignee-companyname').val(rsp['companyname']);
					$(inputfieldsWB + ' .waybill-consignee-street').val(rsp['street']);
					$(inputfieldsWB + ' .waybill-consignee-district').val(rsp['district']);
					$(inputfieldsWB + ' .waybill-consignee-city').val(rsp['city']);
					$(inputfieldsWB + ' .waybill-consignee-province').val(rsp['province']);
					$(inputfieldsWB + ' .waybill-consignee-zipcode').val(rsp['zipcode']);

					if (rsp['odaflag'] == 1 || rsp['odaflag'] == 0) {
						$(inputfieldsWB + ' .waybill-odaflag')
							.attr('disabled', '')
							.val(rsp['odaflag'])
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-odaflag')
							.removeAttr('disabled')
							.val(0)
							.trigger('change');
					}

					if (rsp['district'] != null) {
						$(inputfieldsWB + ' .waybill-consignee-district')
							.empty()
							.append('<option selected value="' + rsp['district'] + '">' + rsp['district'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-consignee-district')
							.empty()
							.trigger('change');
					}
					if (rsp['city'] != null) {
						$(inputfieldsWB + ' .waybill-consignee-city')
							.empty()
							.append('<option selected value="' + rsp['city'] + '">' + rsp['city'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-consignee-city')
							.empty()
							.trigger('change');
					}
					if (rsp['zipcode'] != null) {
						$(inputfieldsWB + ' .waybill-consignee-zipcode')
							.empty()
							.append('<option selected value="' + rsp['zipcode'] + '">' + rsp['zipcode'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-consignee-zipcode')
							.empty()
							.trigger('change');
					}
					if (rsp['province'] != null) {
						$(inputfieldsWB + ' .waybill-consignee-province')
							.empty()
							.append('<option selected value="' + rsp['province'] + '">' + rsp['province'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-consignee-province')
							.empty()
							.trigger('change');
					}
					if (rsp['provinceid'] != '' && rsp['provinceid'] != null) {
						$(inputfieldsWB + ' .waybill-destination')
							.empty()
							.append('<option selected value="' + rsp['provinceid'] + '">' + rsp['province'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-destination')
							.empty()
							.trigger('change');
					}
					if (rsp['country'] != null) {
						$(inputfieldsWB + ' .waybill-consignee-country')
							.empty()
							.append('<option selected value="' + rsp['country'] + '">' + rsp['country'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsWB + ' .waybill-consignee-country')
							.empty()
							.trigger('change');
					}

					$.post(server + 'booking.php', { ConsigneeDefaultContactGetInfo: 'oj94oifof#o@odlspriaoi#@po92po@k@', id: id }, function (data1) {
						rsp1 = $.parseJSON(data1);
						if (rsp1['response'] == 'success') {
							$(inputfieldsWB + ' .waybill-consignee-telephone').val(rsp1['phone']);
							//$(inputfieldsWB+' .waybill-numberofpackages').focus();
							row.removeClass('disabled');
						} else if (rsp1['response'] == 'nocontactinfo') {
							row.removeClass('disabled');
						} else {
							alert(data1);
							say('Unable to retrive default contact information of selected consignee.');
							row.removeClass('disabled');
						}

						$(contentWB + ' .waybill-consignee-telephone')
							.focus()
							.select();
					});
				} else {
					alert(data);
					say('Selected consignee has invalid system ID. Please select another.');
					row.removeClass('disabled');
				}
			});
		});
	});

/*$(document).off('change',contentWB+' .waybill-paymode').on('change',contentWB+' .waybill-paymode',function(){
	if($(this).val()=='SERVICE CARGO'||$(this).val()=='Service Cargo'){
		$(contentWB+' .costcenterwrapper').removeClass('hidden');
	}
	else{
		$(contentWB+' .costcenterwrapper').addClass('hidden');
	}
});*/

/********************* TABLEROW EVENTS - END ********************************/

/************************* MODAL EVENTS ***************************************/

$(document)
	.off('show.bs.modal', contentWB + ' #waybill-shipperpickupaddresslookup')
	.on('show.bs.modal', contentWB + ' #waybill-shipperpickupaddresslookup', function () {
		var modal = '#' + $(this).attr('id');
		var selectedshipperID = $(modal + ' .waybill-shipperpickupaddresslookup-shipperid').val();
		$(contentWB + ' #waybill-shipperpickupaddresslookuptbl')
			.flexOptions({
				url: 'loadables/ajax/transactions.booking.shipper-pickup-address-lookup.php?id=' + selectedshipperID,
				sortname: 'pickup_street_address',
				sortorder: 'asc'
			})
			.flexReload();
	});

$(document)
	.off('click', contentWB + ' #voidwaybilltransactionmodal-savebtn:not(".disabled")')
	.on('click', contentWB + ' #voidwaybilltransactionmodal-savebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var waybillid = $(modal + ' #voidwaybilltransactionmodal-waybillid').val();
		var waybillnumber = $(modal + ' .voidwaybilltransactionmodal-waybillnumber').val();
		var remarks = $(modal + ' .voidwaybilltransactionmodal-remarks').val();
		var button = $(this);
		button.addClass('disabled');

		$(modal + ' .modal-errordiv').empty();

		if (remarks.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide reason for cancellation.</div></div>");
			$(modal + ' .voidwaybilltransactionmodal-remarks').focus();
			button.removeClass('disabled');
		} else {
			$.confirm({
				animation: 'bottom',
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce: 1,
				title: 'Void Waybill [' + waybillnumber + ']',
				content: 'Do you want to continue?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',
				confirmButtonClass: 'btn-oceanblue',
				cancelButtonClass: 'btn-royalblue',
				theme: 'white',

				confirm: function () {
					$('#loading-img').removeClass('hidden');
					$.post(
						server + 'waybill.php',
						{ voidWaybillTransaction: 'dROi$nsFpo94dnels$4sRoi809srbmouS@1!', waybillid: waybillid, waybillnumber: waybillnumber, remarks: remarks },
						function (data) {
							if (data.trim() == 'success') {
								$(modal).modal('hide');
								$(document)
									.off('hidden.bs.modal', modal)
									.on('hidden.bs.modal', modal, function () {
										$(document).off('hidden.bs.modal', modal);

										$(modal + ' #voidwaybilltransactionmodal-waybillid').val('');
										$(modal + ' .voidwaybilltransactionmodal-waybillnumber').val('');
										$(modal + ' .voidwaybilltransactionmodal-remarks').val('');

										getWaybillInformation(waybillnumber);
										button.removeClass('disabled');
										$('#loading-img').addClass('hidden');
									});
							} else if (data.trim() == 'invalidwaybill') {
								say('Unable to void waybill transaction. Invalid Waybill ID/No. ID: ' + waybillid + ' - Waybill No.: ' + waybillnumber);
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							} else {
								alert(data);
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							}
						}
					);
				},
				cancel: function () {
					button.removeClass('disabled');
				}
			});
		}
	});

/************************* MODAL EVENTS - END ************************************/

/*********************** MODAL BUTTONS *************************************/
$(document)
	.off('click', contentWB + ' #waybill-shipperpickupaddress-savebtn:not(".disabled")')
	.on('click', contentWB + ' #waybill-shipperpickupaddress-savebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id'),
			shipperid = $(modal + ' .waybill-shipperpickupaddresslookup-shipperid').val(),
			street = $(modal + ' .waybill-shipperpickupaddresslookup-street').val(),
			district = $(modal + ' .waybill-shipperpickupaddresslookup-district').val(),
			city = $(modal + ' .waybill-shipperpickupaddresslookup-city').val(),
			province = $(modal + ' .waybill-shipperpickupaddresslookup-province').val(),
			zipcode = $(modal + ' .waybill-shipperpickupaddresslookup-zipcode').val(),
			country = $(modal + ' .waybill-shipperpickupaddresslookup-country').val(),
			button = $(this);
		button.addClass('disabled');

		if (street == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide street address.</div></div>");
			$(modal + ' .waybill-shipperpickupaddresslookup-street').focus();
			button.removeClass('disabled');
		} else if (city == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a city.</div></div>");
			$(modal + ' .waybill-shipperpickupaddresslookup-city').focus();
			button.removeClass('disabled');
		} else if (province == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a region/province.</div></div>");
			$(modal + ' .waybill-shipperpickupaddresslookup-province').focus();
			button.removeClass('disabled');
		} else {
			$('#loading-img').removeClass('hidden');

			$.post(
				server + 'booking.php',
				{
					AddNewPickupAddress: 'kkjO#@siaah3h0$09odfj$owenxezpo92po@k@',
					shipperid: shipperid,
					street: street,
					district: district,
					city: city,
					province: province,
					zipcode: zipcode,
					country: country
				},
				function (data) {
					if (data.trim() == 'success') {
						$(contentWB + ' #waybill-shipperpickupaddresslookuptbl')
							.flexOptions({
								url: 'loadables/ajax/transactions.booking.shipper-pickup-address-lookup.php?id=' + shipperid,
								sortname: 'created_date',
								sortorder: 'desc'
							})
							.flexReload();
						clearNewPickupAddressFieldsWB();
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					} else if (data.trim() == 'invalidshipperid') {
						say('Invalid Shipper ID. Please re-select a shipper.');
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					} else {
						alert(data);
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					}
				}
			);
		}
	});

/********************* VIEWING *********************************/
$(document)
	.off('click', contentWB + " .firstprevnextlastbtn button:not('.disabled')")
	.on('click', contentWB + " .firstprevnextlastbtn button:not('.disabled')", function () {
		$('#loading-img').removeClass('hidden');
		var source = $(this).data('info'),
			id = $('#pgtxnwaybill-id').val(),
			button = $(this);
		button.addClass('disabled');

		$.post(server + 'waybill.php', { getReference: 'FOio5ja3op2a2lK@3#4hh$93s', source: source, id: id }, function (data) {
			if (data.trim() == 'N/A') {
				$('#loading-img').addClass('hidden');
				getWaybillInformation('');
			} else {
				getWaybillInformation(data.trim());
			}
			setTimeout(function () {
				button.removeClass('disabled');
			}, 200);
		});
	});

$(document)
	.off('keyup', contentWB + ' .transactionnumber')
	.on('keyup', contentWB + ' .transactionnumber', function (e) {
		e.preventDefault();
		$('#loading-img').removeClass('hidden');
		var key = e.which || e.keycode,
			waybillnumber = $(this).val();
		if (key == '13') {
			getWaybillInformation(waybillnumber);
		} else {
			$('#loading-img').addClass('hidden');
		}
	});

function getWaybillInformation(txnnumber) {
	processWB = '';
	$.post(server + 'waybill.php', { getWaybillData: 'F#@!3R3ksk#Op1NEi34smo1sonk&$', txnnumber: txnnumber }, function (response) {
		//alert(response);
		if (response.trim() == 'INVALID') {
			clearAllWB();
			$(contentWB + ' .statusdiv').html('<br>');
			$(contentWB + ' .billingflagdiv')
				.html('<br>')
				.removeClass('billed')
				.removeClass('notbilled')
				.removeAttr('billingflag');
			$(contentWB + ' #pgtxnwaybill-id')
				.val('')
				.removeAttr('pgtxnwaybill-number', '');
			$(contentWB + ' .topbuttonsdiv').html(
				"<div class='padded-with-border-engraved topbuttonswrapper'><div class='button-group'><div class='button-group-btn active' title='New' id='waybill-trans-newbtn'><img src='../resources/img/add.png'></div></div></div>"
			);
			//<div class='button-group-btn active' title='Upload' id='waybill-trans-uploadbtn'><img src='../resources/img/upload.png'></div>
			currentWaybillTxn = '';
			userAccess();
		} else {
			currentWaybillTxn = txnnumber;
			data = $.parseJSON(response);
			var decimalplaces = 2;

			currentdivisor = data['divisor'];

			$(contentWB + ' .waybill-shipperpickupaddresslookup-shipperid').val(data['shipperid']);
			$(contentWB + ' .waybill-shipperpickupaddresslookup-accountnumber').val(data['shipperaccountnumber']);
			$(contentWB + ' .waybill-shipperpickupaddresslookup-accountname').val(data['shipperaccountname']);

			$(contentWB + ' .transactionnumber').val(txnnumber);
			$(contentWB + ' #pgtxnwaybill-id')
				.val(data['id'])
				.attr('pgtxnwaybill-number', txnnumber);
			$(contentWB + ' .statusdiv').text(data['status']);

			$(contentWB + ' .waybill-remarks').val(data['remarks']);
			if (data['onholdflag'] == 'true') {
				$(contentWB + ' .waybill-onholdcheckbox').prop('checked', true);
			} else {
				$(contentWB + ' .waybill-onholdcheckbox').prop('checked', false);
			}

			if (data['zeroratedflag'] == 'true') {
				$(contentWB + ' .waybill-zeroratedcheckbox').prop('checked', true);
			} else {
				$(contentWB + ' .waybill-zeroratedcheckbox').prop('checked', false);
			}

			if (data['billedflag'] == '1') {
				$(contentWB + ' .billingflagdiv')
					.removeClass('notbilled')
					.addClass('billed')
					.html('BILLED')
					.attr('billingflag', data['billedflag']);
			} else {
				$(contentWB + ' .billingflagdiv')
					.removeClass('billed')
					.addClass('notbilled')
					.html('UNBILLED')
					.attr('billingflag', data['billedflag']);
			}

			if (data['paidflag'] == '1') {
				//$(contentWB+' .paidflagdiv').html('PAID').attr('paidflag',data["paidflag"]).removeClass('hidden');
				$(contentWB + ' .waybill-paidflag')
					.val('Paid')
					.attr('paidflag', data['paidflag'])
					.removeClass('hidden');
			} else {
				//$(contentWB+' .paidflagdiv').html('UNPAID').attr('paidflag',data["paidflag"]).removeClass('hidden');
				$(contentWB + ' .waybill-paidflag')
					.val('Unpaid')
					.attr('paidflag', data['paidflag'])
					.removeClass('hidden');
			}

			if (data['viewbillingaccess'] == '1') {
				$(contentWB + ' .billingflagdiv').removeClass('hidden');
			} else {
				$(contentWB + ' .billingflagdiv').addClass('hidden');
			}

			$(contentWB + ' .waybill-onholdremarks').val(data['onholdremarks']);
			$(contentWB + ' .waybill-pickupdate').val(data['pickupdate']);
			$(contentWB + ' .waybill-documentdate').val(data['documentdate']);
			$(contentWB + ' .waybill-deliverydate').val(data['deliverydate']);

			$(contentWB + ' .waybill-shipper-systemid').val(data['shipperid']);
			$(contentWB + ' .waybill-shipper-accountnumber').val(data['shipperaccountnumber']);
			$(contentWB + ' .waybill-shipper-accountname').val(data['shipperaccountname']);
			$(contentWB + ' .waybill-shipper-telephone').val(data['shippertel']);
			$(contentWB + ' .waybill-shipper-companyname').val(data['shippercompanyname']);
			$(contentWB + ' .waybill-shipper-podinstruction').val(data['shipperpodinstruction']);
			$(contentWB + ' .waybill-shipper-street').val(data['shipperstreet']);
			$(contentWB + ' .waybill-shipper-district').val(data['shipperdistrict']);
			$(contentWB + ' .waybill-shipper-city').val(data['shippercity']);
			$(contentWB + ' .waybill-shipper-province').val(data['shipperprovince']);
			$(contentWB + ' .waybill-shipper-zipcode').val(data['shipperzipcode']);

			$(contentWB + ' .waybill-shipper-pickupstreet').val(data['pickupstreet']);
			$(contentWB + ' .waybill-shipper-pickupdistrict').val(data['pickupdistrict']);
			$(contentWB + ' .waybill-shipper-pickupcity').val(data['pickupcity']);
			$(contentWB + ' .waybill-shipper-pickupprovince').val(data['pickupprovince']);
			$(contentWB + ' .waybill-shipper-pickupzipcode').val(data['pickupzipcode']);

			$(contentWB + ' .waybill-consignee-systemid').val(data['consigneeid']);
			$(contentWB + ' .waybill-consignee-idnumber').val(data['consigneeidnumber']);
			$(contentWB + ' .waybill-consignee-accountnumber').val(data['consigneeaccountnumber']);
			$(contentWB + ' .waybill-consignee-accountname').val(data['consigneeaccountname']);
			$(contentWB + ' .waybill-consignee-telephone').val(data['consigneetel']);
			$(contentWB + ' .waybill-consignee-companyname').val(data['consigneecompanyname']);
			$(contentWB + ' .waybill-consignee-secondary').val(data['secondary_recipient']);
			$(contentWB + ' .waybill-consignee-street').val(data['consigneestreet']);
			$(contentWB + ' .waybill-consignee-district').val(data['consigneedistrict']);
			$(contentWB + ' .waybill-consignee-city').val(data['consigneecity']);
			$(contentWB + ' .waybill-consignee-province').val(data['consigneeprovince']);
			$(contentWB + ' .waybill-consignee-zipcode').val(data['consigneezipcode']);

			$(contentWB + ' .waybill-numberofpackages').val(data['numberofpackage']);
			$(contentWB + ' .waybill-declaredvalue').val(data['declaredvalue']);
			$(contentWB + ' .waybill-actualweight').val(data['actualweight']);
			$(contentWB + ' .waybill-vwcbm').val(data['vwcbm']);
			$(contentWB + ' .waybill-vw').val(data['vw']);
			$(contentWB + ' .waybill-freightcomputation').val(data['freightcomputation']);
			$(contentWB + ' .waybill-chargeableweight')
				.val(data['chargeableweight'])
				.number(true, 4);
			$(contentWB + ' .waybill-freight')
				.val(data['freight'])
				.number(true, decimalplaces);
			$(contentWB + ' .waybill-valuation')
				.val(data['valuation'])
				.number(true, decimalplaces);
			$(contentWB + ' .waybill-insurancerate')
				.val(data['insurancerate'])
				.number(true, decimalplaces);
			$(contentWB + ' .waybill-fuelrate')
				.val(data['fuelrate'])
				.number(true, decimalplaces);
			$(contentWB + ' .waybill-bunkerrate')
				.val(data['bunkerrate'])
				.number(true, decimalplaces);
			$(contentWB + ' .waybill-minimumrate')
				.val(data['minimumrate'])
				.number(true, decimalplaces);
			$(contentWB + ' .waybill-regularcharges')
				.val(data['totalregularcharges'])
				.number(true, decimalplaces);
			$(contentWB + ' .waybill-otherchargesvatable')
				.val(data['totalotherchargesvatable'])
				.number(true, decimalplaces);
			$(contentWB + ' .waybill-otherchargesnonvatable')
				.val(data['totalotherchargesnonvatable'])
				.number(true, decimalplaces);
			$(contentWB + ' .waybill-subtotal')
				.val(data['subtotal'])
				.number(true, decimalplaces);
			$(contentWB + ' .waybill-vat')
				.val(data['vat'])
				.number(true, decimalplaces);
			$(contentWB + ' .waybill-totalamount')
				.val(data['totalamount'])
				.number(true, decimalplaces);
			$(contentWB + ' .waybill-shipperrepname').val(data['shipperrepname']);
			$(contentWB + ' .waybill-shipmentdescription').val(data['shipmentdescription']);
			$(contentWB + ' .waybill-costcenter').val(data['costcenter']);

			$(contentWB + ' .waybill-amountforcollection').val(data['amountforcollection']);

			$(contentWB + ' .waybill-billingreference').val(data['billingreference']);
			$(contentWB + ' .waybill-billingstatement').val(data['billingstatement']);
			$(contentWB + ' .waybill-shipper-contactperson').val(data['shippercontact']);

			$(contentWB + ' .waybill-returndocumentfee')
				.val(data['returndocumentfee'])
				.number(true, decimalplaces);
			$(contentWB + ' .waybill-waybillfee')
				.val(data['waybillfee'])
				.number(true, decimalplaces);
			$(contentWB + ' .waybill-securityfee')
				.val(data['securityfee'])
				.number(true, decimalplaces);
			$(contentWB + ' .waybill-docstampfee')
				.val(data['docstampfee'])
				.number(true, decimalplaces);

			$(contentWB + ' .waybill-baseoda')
				.val(data['baseoda'])
				.number(true, decimalplaces);
			$(contentWB + ' .waybill-shipperoda')
				.val(data['shipperoda'])
				.number(true, decimalplaces);
			$(contentWB + ' .waybill-oda')
				.val(data['oda'])
				.number(true, decimalplaces);
			$(contentWB + ' .waybill-totalhandlingcharges')
				.val(data['totalhandlingcharges'])
				.number(true, decimalplaces);
			$(contentWB + ' .waybill-fixedrateamount')
				.val(data['fixedrateamount'])
				.number(true, decimalplaces);
			$(contentWB + ' .waybill-pulloutfee')
				.val(data['pulloutfee'])
				.number(true, decimalplaces);

			$(contentWB + ' .waybill-reference').val(data['reference']);
			$(contentWB + ' .waybill-mawbl').val(data['mawbl']);

			$(contentWB + ' .waybill-createddate').val(data['createddate']);
			$(contentWB + ' .waybill-createdby').val(data['createdby']);
			$(contentWB + ' .waybill-updateddate').val(data['updateddate']);
			$(contentWB + ' .waybill-updatedby').val(data['updatedby']);
			$(contentWB + ' .waybill-laststatupdateremarks').val(data['laststatupdateremarks']);
			$(contentWB + ' .waybill-manifestnumber').val(data['manifestnumber']);
			$(contentWB + ' .waybill-invoicenumber').val(data['invoicenumber']);

			$(contentWB + ' .waybill-printedflag').val(data['printedflag']);
			$(contentWB + ' .waybill-printedby').val(data['printedby']);
			$(contentWB + ' .waybill-printeddate').val(data['printeddate']);
			$(contentWB + ' .waybill-numberofreprint').val(data['reprintcount']);

			$(inputfieldsWB + ' .waybill-waybilltype')
				.val(data['waybilltype'])
				.trigger('change');
			$(inputfieldsWB + ' .waybill-rushflag')
				.val(data['rushflag'])
				.trigger('change');
			$(inputfieldsWB + ' .waybill-pulloutflag')
				.val(data['pulloutflag'])
				.trigger('change');
			$(inputfieldsWB + ' .waybill-odaflag')
				.val(data['odaflag'])
				.trigger('change');

			if (data['odaflag'] == 1) {
				$(contentWB + ' .odzflagdiv')
					.removeClass('hidden')
					.html('ODZ');
			} else {
				$(contentWB + ' .odzflagdiv')
					.addClass('hidden')
					.html('<br>');
			}

			$(contentWB + ' .waybill-paymentreference').val(data['paymentreference']);

			$(contentWB + ' .waybill-brand').val(data['brand']);
			$(contentWB + ' .waybill-costcentercode').val(data['costcentercode']);
			$(contentWB + ' .waybill-buyercode').val(data['buyercode']);
			$(contentWB + ' .waybill-contractnumber').val(data['contractnumber']);
			$(contentWB + ' .waybill-customernumber').val(data['customernumber']);
			$(contentWB + ' .waybill-project').val(data['project']);
			$(contentWB + ' .waybill-parkingslot').val(data['parkingslot']);
			$(contentWB + ' .waybill-blockunitdistrict').val(data['blockunitdistrict']);
			$(contentWB + ' .waybill-lotfloor').val(data['lotfloor']);

			$(contentWB + ' .waybill-freightcost').val(data['freightcost']);
			$(contentWB + ' .waybill-billreference').val(data['billreference']);
			$(contentWB + ' .waybill-agentcost').val(data['agentcost']);
			$(contentWB + ' .waybill-billitemnumber').val(data['billitemnumber']);
			$(contentWB + ' .waybill-insuranceamount').val(data['insuranceamount']);
			$(contentWB + ' .waybill-insurancereference').val(data['insurancereference']);
			$(contentWB + ' .waybill-grossincome').val(data['grossincome']);
			$(contentWB + ' .waybill-totalcost').val(data['totalcost']);
			//$(inputfieldsWB+" .waybill-billedflag").val(data["billedflag"]).trigger('change');

			/*if(data["waybilltype"]=='PARCEL'){
				$(contentWB+' .cbmwrapper').removeClass('hidden');
				$(contentWB+' .volweightwrapper').removeClass('hidden');
				$(contentWB+' .actualweightwrapper').removeClass('hidden');
				$(contentWB+' .pouchsizewrapper').addClass('hidden');
				
			}
			else{
					$(contentWB+' .cbmwrapper').addClass('hidden');
					$(contentWB+' .volweightwrapper').addClass('hidden');
					$(contentWB+' .actualweightwrapper').addClass('hidden');
					$(contentWB+' .pouchsizewrapper').removeClass('hidden');


			}*/

			$(contentWB + ' #waybillcostingdetails-table')
				.flexOptions({
					url: 'loadables/ajax/transactions.waybill-costing-details.php?waybill=' + txnnumber,
					sortname: 'chart_of_accounts.description',
					sortorder: 'asc'
				})
				.flexReload();

			showFieldsBasedOnWaybillType(data['waybilltype']);

			if (data['bookingnumber'] != null) {
				$(inputfieldsWB + ' .waybill-bookingnumber')
					.empty()
					.append('<option selected value="' + data['bookingnumber'] + '">' + data['bookingnumber'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-bookingnumber')
					.empty()
					.trigger('change');
			}

			if (data['thirdpartylogistic'] != null) {
				$(inputfieldsWB + ' .waybill-3pl')
					.empty()
					.append('<option selected value="' + data['thirdpartylogisticid'] + '">' + data['thirdpartylogistic'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-3pl')
					.empty()
					.trigger('change');
			}
			if (data['agent'] != null) {
				$(inputfieldsWB + ' .waybill-agent')
					.empty()
					.append('<option selected value="' + data['agentid'] + '">' + data['agent'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-agent')
					.empty()
					.trigger('change');
			}

			if (data['shipmenttype'] != null) {
				$(inputfieldsWB + ' .waybill-shipmenttype')
					.empty()
					.append('<option selected value="' + data['shipmenttypeid'] + '">' + data['shipmenttype'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-shipmenttype')
					.empty()
					.trigger('change');
			}

			if (data['shipmentmode'] != null) {
				$(inputfieldsWB + ' .waybill-shipmentmode')
					.empty()
					.append('<option selected value="' + data['shipmentmodeid'] + '">' + data['shipmentmode'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-shipmentmode')
					.empty()
					.trigger('change');
			}

			/*if(data["parceltype"]!=null){
				$(inputfieldsWB+" .waybill-parceltype").empty().append('<option selected value="'+data["parceltypeid"]+'">'+data["parceltype"]+'</option>').trigger('change');
			}
			else{
				$(inputfieldsWB+" .waybill-parceltype").empty().trigger('change');
			}*/

			if (data['origin'] != null) {
				$(inputfieldsWB + ' .waybill-origin')
					.empty()
					.append('<option selected value="' + data['originid'] + '">' + data['origin'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-origin')
					.empty()
					.trigger('change');
			}

			if (data['destination'] != null) {
				$(inputfieldsWB + ' .waybill-destination')
					.empty()
					.append('<option selected value="' + data['destinationid'] + '">' + data['destination'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-destination')
					.empty()
					.trigger('change');
			}

			if (data['destinationroute'] != null) {
				$(inputfieldsWB + ' .waybill-destinationroute')
					.empty()
					.append('<option selected value="' + data['destinationrouteid'] + '">' + data['destinationroute'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-destinationroute')
					.empty()
					.trigger('change');
			}

			if (data['shipperdistrict'] != null) {
				$(inputfieldsWB + ' .waybill-shipper-district')
					.empty()
					.append('<option selected value="' + data['shipperdistrict'] + '">' + data['shipperdistrict'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-shipper-district')
					.empty()
					.trigger('change');
			}
			if (data['shippercity'] != null) {
				$(inputfieldsWB + ' .waybill-shipper-city')
					.empty()
					.append('<option selected value="' + data['shippercity'] + '">' + data['shippercity'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-shipper-city')
					.empty()
					.trigger('change');
			}
			if (data['shipperprovince'] != null) {
				$(inputfieldsWB + ' .waybill-shipper-province')
					.empty()
					.append('<option selected value="' + data['shipperprovince'] + '">' + data['shipperprovince'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-shipper-province')
					.empty()
					.trigger('change');
			}
			if (data['shipperzipcode'] != null) {
				$(inputfieldsWB + ' .waybill-shipper-zipcode')
					.empty()
					.append('<option selected value="' + data['shipperzipcode'] + '">' + data['shipperzipcode'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-shipper-zipcode')
					.empty()
					.trigger('change');
			}

			if (data['shippercountry'] != null) {
				$(inputfieldsWB + ' .waybill-shipper-country')
					.empty()
					.append('<option selected value="' + data['shippercountry'] + '">' + data['shippercountry'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-shipper-country')
					.empty()
					.trigger('change');
			}

			if (data['pickupdistrict'] != null) {
				$(inputfieldsWB + ' .waybill-shipper-pickupdistrict')
					.empty()
					.append('<option selected value="' + data['pickupdistrict'] + '">' + data['pickupdistrict'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-shipper-pickupdistrict')
					.empty()
					.trigger('change');
			}
			if (data['pickupcity'] != null) {
				$(inputfieldsWB + ' .waybill-shipper-pickupcity')
					.empty()
					.append('<option selected value="' + data['pickupcity'] + '">' + data['pickupcity'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-shipper-pickupcity')
					.empty()
					.trigger('change');
			}
			if (data['pickupprovince'] != null) {
				$(inputfieldsWB + ' .waybill-shipper-pickupprovince')
					.empty()
					.append('<option selected value="' + data['pickupprovince'] + '">' + data['pickupprovince'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-shipper-pickupprovince')
					.empty()
					.trigger('change');
			}
			if (data['pickupzipcode'] != null) {
				$(inputfieldsWB + ' .waybill-shipper-pickupzipcode')
					.empty()
					.append('<option selected value="' + data['pickupzipcode'] + '">' + data['pickupzipcode'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-shipper-pickupzipcode')
					.empty()
					.trigger('change');
			}

			if (data['pickupcountry'] != null) {
				$(inputfieldsWB + ' .waybill-shipper-pickupcountry')
					.empty()
					.append('<option selected value="' + data['pickupcountry'] + '">' + data['pickupcountry'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-shipper-pickupcountry')
					.empty()
					.trigger('change');
			}

			if (data['consigneedistrict'] != null) {
				$(inputfieldsWB + ' .waybill-consignee-district')
					.empty()
					.append('<option selected value="' + data['consigneedistrict'] + '">' + data['consigneedistrict'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-consignee-district')
					.empty()
					.trigger('change');
			}
			if (data['consigneecity'] != null) {
				$(inputfieldsWB + ' .waybill-consignee-city')
					.empty()
					.append('<option selected value="' + data['consigneecity'] + '">' + data['consigneecity'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-consignee-city')
					.empty()
					.trigger('change');
			}
			if (data['consigneeprovince'] != null) {
				$(inputfieldsWB + ' .waybill-consignee-province')
					.empty()
					.append('<option selected value="' + data['consigneeprovince'] + '">' + data['consigneeprovince'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-consignee-province')
					.empty()
					.trigger('change');
			}
			if (data['consigneezipcode'] != null) {
				$(inputfieldsWB + ' .waybill-consignee-zipcode')
					.empty()
					.append('<option selected value="' + data['consigneezipcode'] + '">' + data['consigneezipcode'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-consignee-zipcode')
					.empty()
					.trigger('change');
			}

			if (data['consigneecountry'] != null) {
				$(inputfieldsWB + ' .waybill-consignee-country')
					.empty()
					.append('<option selected value="' + data['consigneecountry'] + '">' + data['consigneecountry'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-consignee-country')
					.empty()
					.trigger('change');
			}

			if (data['service'] != null) {
				$(inputfieldsWB + ' .waybill-services')
					.empty()
					.append('<option selected value="' + data['serviceid'] + '">' + data['service'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-services')
					.empty()
					.trigger('change');
			}
			if (data['modeoftransport'] != null) {
				$(inputfieldsWB + ' .waybill-modeoftransport')
					.empty()
					.append('<option selected value="' + data['modeoftransportid'] + '">' + data['modeoftransport'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-modeoftransport')
					.empty()
					.trigger('change');
			}
			if (data['deliveryinstruction'] != null) {
				$(inputfieldsWB + ' .waybill-deliveryinstruction')
					.empty()
					.append('<option selected value="' + data['deliveryinstructionid'] + '">' + data['deliveryinstruction'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-deliveryinstruction')
					.empty()
					.trigger('change');
			}

			/*if(data["document"]!=null){
				$(inputfieldsWB+" .waybill-document").empty().append('<option selected value="'+data["documentid"]+'">'+data["document"]+'</option>').trigger('change');
			}
			else{
				$(inputfieldsWB+" .waybill-document").empty().trigger('change');
			}*/

			if (data['transportcharges'] != null) {
				$(inputfieldsWB + ' .waybill-transportcharges')
					.empty()
					.append('<option selected value="' + data['transportchargesid'] + '">' + data['transportcharges'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-transportcharges')
					.empty()
					.trigger('change');
			}

			if (data['carrier'] != null) {
				$(inputfieldsWB + ' .waybill-carrier')
					.empty()
					.append('<option selected value="' + data['carrierid'] + '">' + data['carrier'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-carrier')
					.empty()
					.trigger('change');
			}

			if (data['pouchsize'] != null) {
				$(inputfieldsWB + ' .waybill-pouchsize')
					.empty()
					.append('<option selected value="' + data['pouchsizeid'] + '">' + data['pouchsize'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-pouchsize')
					.empty()
					.trigger('change');
			}

			if (data['paymode'] != null) {
				$(inputfieldsWB + ' .waybill-paymode')
					.empty()
					.append('<option selected value="' + data['paymode'] + '">' + data['paymode'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-paymode')
					.empty()
					.trigger('change');
			}

			if (data['viewbillingaccess'] == '1') {
				var editbillingbtn = "<div class='button-group-btn active' title='Billed Flagging' id='waybill-trans-billingtogglebtn'><img src='../resources/img/billing.png'></div>";
			} else {
				var editbillingbtn = '';
			}

			if (data['expresstransactiontype'] != null) {
				$(inputfieldsWB + ' .waybill-expresstransactiontype')
					.empty()
					.append('<option selected value="' + data['expresstransactiontype'] + '">' + data['expresstransactiontype'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsWB + ' .waybill-expresstransactiontype')
					.empty()
					.trigger('change');
			}

			//alert(data["viewwaybillchargesflag"]);
			if (data['viewwaybillchargesflag'] == 'true') {
				$(contentWB + ' .ratesandotherchargesfldwrapper').removeClass('hidden');
			} else {
				$(contentWB + ' .ratesandotherchargesfldwrapper').addClass('hidden');
			}

			if (data['viewwaybillcostingflag'] == 'true') {
				$(contentWB + ' .costingfldwrapper').removeClass('hidden');
			} else {
				$(contentWB + ' .costingfldwrapper').addClass('hidden');
			}

			var allowothertrans = '';
			var editchargesbtn = '';
			var editcostingbtn = '';
			var uploadwaybillstatusupdatebtn = '';
			var updatepaidflagbtn = '';

			if (data['editchargesenabled'] == 'true') {
				editchargesbtn = "<div class='button-group-btn active' title='Edit Waybill Charges' id='waybill-trans-editwaybillchargesbtn'><img src='../resources/img/cost.png'></div>";
			}

			if (data['editwaybillcostingflag'] == 'true') {
				editcostingbtn = "<div class='button-group-btn active' title='Edit Costing' id='waybill-trans-editwaybillcostingbtn'><img src='../resources/img/costing2.png'></div>";
			}

			if (data['updatewaybillstatusflag'] == 'true') {
				uploadwaybillstatusupdatebtn = "<div class='button-group-btn active' title='Upload' id='waybill-trans-uploadwaybillstatusupdatebtn'><img src='../resources/img/upload.png'></div>";
			}

			if (data['updatepaidflag'] == 'true') {
				updatepaidflagbtn = "<div class='button-group-btn active' title='Update Payment Status' id='waybill-trans-updatepaidflagbtn' ><img src='../resources/img/payment.png'></div>";
			}

			if (data['status'] == 'LOGGED') {
				if (data['hasaccess'] == 'true') {
					allowothertrans =
						"<div class='button-group-btn active' title='Edit' id='waybill-trans-editbtn'><img src='../resources/img/edit.png'></div><div class='button-group-btn active' title='Void' id='waybill-trans-voidbtn'><img src='../resources/img/block.png'></div><div class='button-group-btn active' title='Post' id='waybill-trans-postbtn'><img src='../resources/img/post.png'></div><div class='button-group-btn active' title='Print' id='waybill-trans-printbtn'><img src='../resources/img/print.png'></div>";
				}

				$(contentWB + ' .topbuttonswrapper .button-group').html(
					"<div class='button-group-btn active' title='New' id='waybill-trans-newbtn'><img src='../resources/img/add.png'></div>" +
						allowothertrans +
						"<div class='button-group-btn active' title='View Status History' id='waybill-trans-viewstatushistorybtn'><img src='../resources/img/history.png'></div>" +
						editcostingbtn +
						uploadwaybillstatusupdatebtn
				);
				//<div class='button-group-btn active' title='Upload' id='waybill-trans-uploadbtn'><img src='../resources/img/upload.png'></div>
				userAccess();
			} else if (data['status'] == 'POSTED') {
				if (data['usercanupdatestatus'] == 'true' && data['finalstatusflag'] == 'false') {
					allowothertrans += "<div class='button-group-btn active' title='Update Status' id='waybill-trans-updatestatusbtn'><img src='../resources/img/update-status.png'></div>";
				}

				if (data['hasadminrights'] == 'true' && data['waybillinloadplan'] == 'false' && data['billedflag'] != 1 && data['finalstatusflag'] == 'false') {
					allowothertrans += "<div class='button-group-btn active' title='Edit' id='waybill-trans-editbtn'><img src='../resources/img/edit.png'></div>";
				}

				if (data['editwaybilldimensionsflag'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Edit Dimensions & Other Info' id='waybill-trans-editdimensionsbtn'><img src='../resources/img/edit2.png'></div>";
				}

				if (data['resetprintacess'] == 1 && data['printcounter'] > 0) {
					allowothertrans += "<div class='button-group-btn active' title='Reset Print Counter' id='waybill-trans-resetprintcounterbtn'><img src='../resources/img/reset-print.png'></div>";
				}

				$(contentWB + ' .topbuttonswrapper .button-group').html(
					"<div class='button-group-btn active' title='New' id='waybill-trans-newbtn'><img src='../resources/img/add.png'></div>" +
						allowothertrans +
						editbillingbtn +
						"<div class='button-group-btn active' title='Print' id='waybill-trans-printbtn'><img src='../resources/img/print.png'></div><div class='button-group-btn active' title='View Status History' id='waybill-trans-viewstatushistorybtn'><img src='../resources/img/history.png'></div>" +
						editchargesbtn +
						editcostingbtn +
						uploadwaybillstatusupdatebtn
				);
				//<div class='button-group-btn active' title='Upload' id='waybill-trans-uploadbtn'><img src='../resources/img/upload.png'></div>
				userAccess();
			} else if (data['status'] == 'VOID') {
				if (data['hasaccess'] == 'true') {
					allowothertrans = '';
				}
				$(contentWB + ' .topbuttonswrapper .button-group').html(
					"<div class='button-group-btn active' title='New' id='waybill-trans-newbtn'><img src='../resources/img/add.png'></div>" +
						allowothertrans +
						"<div class='button-group-btn active' title='Print' id='waybill-trans-printbtn'><img src='../resources/img/print.png'></div><div class='button-group-btn active' title='View Status History' id='waybill-trans-viewstatushistorybtn'><img src='../resources/img/history.png'></div>" +
						uploadwaybillstatusupdatebtn
				);
				//<div class='button-group-btn active' title='Upload' id='waybill-trans-uploadbtn'><img src='../resources/img/upload.png'></div>
				userAccess();
			} else {
				if (data['usercanupdatestatus'] == 'true' && data['finalstatusflag'] == 'false') {
					allowothertrans += "<div class='button-group-btn active' title='Update Status' id='waybill-trans-updatestatusbtn'><img src='../resources/img/update-status.png'></div>";
				}

				if (data['hasadminrights'] == 'true' && data['waybillinloadplan'] == 'false' && data['billedflag'] != 1) {
					//&&data["finalstatusflag"]=='false'
					allowothertrans += "<div class='button-group-btn active' title='Edit' id='waybill-trans-editbtn'><img src='../resources/img/edit.png'></div>";
				}

				if (data['editwaybilldimensionsflag'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Edit Dimensions & Other Info' id='waybill-trans-editdimensionsbtn'><img src='../resources/img/edit2.png'></div>";
				}

				if (data['resetprintacess'] == 1 && data['printcounter'] > 0) {
					allowothertrans += "<div class='button-group-btn active' title='Reset Print Counter' id='waybill-trans-resetprintcounterbtn'><img src='../resources/img/reset-print.png'></div>";
				}

				$(contentWB + ' .topbuttonswrapper .button-group').html(
					"<div class='button-group-btn active' title='New' id='waybill-trans-newbtn'><img src='../resources/img/add.png'></div>" +
						allowothertrans +
						editbillingbtn +
						"<div class='button-group-btn active' title='Print' id='waybill-trans-printbtn'><img src='../resources/img/print.png'></div><div class='button-group-btn active' title='View Status History' id='waybill-trans-viewstatushistorybtn'><img src='../resources/img/history.png'></div>" +
						editchargesbtn +
						editcostingbtn +
						updatepaidflagbtn +
						uploadwaybillstatusupdatebtn
				);
				//<div class='button-group-btn active' title='Upload' id='waybill-trans-uploadbtn'><img src='../resources/img/upload.png'></div>
				userAccess();
			}

			$(contentWB + ' #waybill-packagecodemodaltbl')
				.DataTable()
				.ajax.url('loadables/ajax/transactions.waybill-package-codes.php?waybill=' + txnnumber)
				.search('')
				.load();
			if (data['hasaccess'] == 'true') {
				$(contentWB + ' .wbaddpackagecodebtnwrapper').html(
					"<i class='fa fa-search inputgroupicon inputgroupbtnicon' alwaysshow title='View Package Code(s)' id='waybill-addpackagecodebtn' data-modal='#waybill-addpackagecodemodal'></i>"
				);
			} else {
				$(contentWB + ' .wbaddpackagecodebtnwrapper').empty();
			}
			/*else if(data["status"]=="CLOSED"){
				$(contentPRO+" .topbuttonswrapper .button-group").html("<div class='button-group-btn active' title='New' id='purchase-return-trans-newbtn'><i class='fa fa-file-o fa-lg fa-space'></i></div><div class='button-group-btn active' title='Print' id='purchase-return-trans-printbtn'><i class='fa fa-print fa-lg fa-space'></i></div><div class='button-group-btn active' title='Unclose' id='purchase-return-trans-unclosebtn'><i class='fa fa-unlock fa-lg fa-space'></i></div>");
				userAccess();
			}*/

			$.post(server + 'waybill.php', { WaybillOtherChargesGetInfo: 'kjoI$H2oiaah3h0$09jDppo92po@k@', txnnumber: txnnumber }, function (dt) {
				//alert(dt);
				rsp1 = $.parseJSON(dt);
				$(contentWB + ' #waybill-otherchargestbl')
					.DataTable()
					.search('')
					.clear()
					.draw();
				for (var i = 0; i < rsp1.length; i++) {
					$(contentWB + ' #waybill-otherchargestbl')
						.DataTable()
						.row.add([
							"<input type='checkbox' class='rowcheckbox hidden'>",
							"<span class='waybill-othercharges-description'>" + rsp1[i]['description'] + '</span>',
							"<span class='waybill-othercharges-amount pull-right'>" + rsp1[i]['amount'] + '</span>',
							"<span class='waybill-othercharges-vatable tblotherchargesvatflag'>" + rsp1[i]['vatflag'] + '</span>'
						])
						.draw();
				}
			});

			$.post(server + 'waybill.php', { WaybillPackageDimensionsGetInfo: 'kjoI$H2oiaah3h0$09jDppo92po@k@', txnnumber: txnnumber }, function (dt2) {
				//alert(dt);
				rsp2 = $.parseJSON(dt2);
				$(contentWB + ' #waybill-packagedimensionsmodaltbl')
					.DataTable()
					.search('')
					.clear()
					.draw();
				for (var i = 0; i < rsp2.length; i++) {
					$(contentWB + ' #waybill-packagedimensionsmodaltbl')
						.DataTable()
						.row.add([
							"<input type='checkbox' class='rowcheckbox hidden'>",
							"<span class='waybill-packagedimensiontbl-quantity'>" + rsp2[i]['qty'] + '</span>',
							"<span class='waybill-packagedimensiontbl-uom'>" + rsp2[i]['uom'] + '</span>',
							"<span class='waybill-packagedimensiontbl-actualweight'>" + rsp2[i]['actualweight'] + '</span>',
							"<span class='waybill-packagedimensiontbl-length'>" + rsp2[i]['length'] + '</span>',
							"<span class='waybill-packagedimensiontbl-width'>" + rsp2[i]['width'] + '</span>',
							"<span class='waybill-packagedimensiontbl-height'>" + rsp2[i]['height'] + '</span>',
							"<span class='waybill-packagedimensiontbl-volweight'>" + rsp2[i]['vw'] + '</span>',
							"<span class='waybill-packagedimensiontbl-cbm'>" + rsp2[i]['cbm'] + '</span>'
						])
						.draw();
				}
			});

			$.post(server + 'waybill.php', { getHandlingInstructions: 'sdfed#n2L1hfi$n#opi3opod30napri', txnnumber: txnnumber }, function (data) {
				data = $.parseJSON(data);
				var instructions = data['instructions'].split('#@$');

				$(contentWB + ' .waybill-handlinginstruction').empty();
				for (var i = 0; i < instructions.length; i++) {
					var otherwhse1 = instructions[i];
					otherwhse1 = otherwhse1.split('%$&');
					if (otherwhse1[1] != null) {
						$(contentWB + ' .waybill-handlinginstruction')
							.append('<option selected value="' + otherwhse1[0] + '">' + otherwhse1[1] + '</option>')
							.trigger('change');
					} else {
						$(contentWB + ' .waybill-handlinginstruction')
							.empty()
							.trigger('change');
					}
				}
			});

			$.post(server + 'waybill.php', { getAccompanyingDocuments: 'sdfed#n2L1hfi$n#opi3opod30napri', txnnumber: txnnumber }, function (data) {
				//alert(data);
				data = $.parseJSON(data);
				var descriptions = data['descriptions'].split('#@$');

				$(contentWB + ' .waybill-document').empty();
				for (var i = 0; i < descriptions.length; i++) {
					var strdesc = descriptions[i];
					strdesc = strdesc.split('%$&');
					if (strdesc[1] != null) {
						$(contentWB + ' .waybill-document')
							.append('<option selected value="' + strdesc[0] + '">' + strdesc[1] + '</option>')
							.trigger('change');
					} else {
						$(contentWB + ' .waybill-document').trigger('change');
					}
				}
			});

			/****** FIELD EDIT ****************/
			if (data['fieldeditwbcbm'] == 'true') {
				$(contentWB + ' .cbmwrapper')
					.find('.control-label')
					.html(
						'CBM <img class="controllabeliconedit" id="fieldedit-wbcbm" data-label="CBM" data-code="WBCBM" data-table="txn_waybill" data-column="package_cbm" data-txncolumn="waybill_number" data-txnnumber="' +
							txnnumber +
							'" data-type="numeric" src="../resources/img/editinputicon.png">'
					);
			} else {
				$(contentWB + ' .cbmwrapper')
					.find('.control-label')
					.html('CBM');
			}
			/****** FIELD EDIT ****************/

			//$('.content').animate({scrollTop:0},1000);
		}
		$('#loading-img').addClass('hidden');
	});
}
/********************* VIEWING - END *******************************/

/************************* SEARCHING ***********************************/

$(document)
	.off('dblclick', contentWB + ' .waybillsearchrow')
	.on('dblclick', contentWB + ' .waybillsearchrow', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var waybillnumber = $(this).attr('waybillnumber');
		$(modal).modal('hide');
		$(document)
			.off('hidden.bs.modal', modal)
			.on('hidden.bs.modal', modal, function () {
				$(document).off('hidden.bs.modal', modal);
				getWaybillInformation(waybillnumber);
			});
	});

function searchWaybillLookup(modal) {
	var status = $(modal + ' .waybillsearch-status').val(), //.replace(/\s/g,'%20'),
		bookingnumber = $(modal + ' .waybillsearch-bookingnumber').val(), //.replace(/\s/g,'%20'),
		manifestnumber = $(modal + ' .waybillsearch-manifestnumber').val(), //.replace(/\s/g,'%20'),
		invoicenumber = $(modal + ' .waybillsearch-invoicenumber').val(), //.replace(/\s/g,'%20'),
		origin = $(modal + ' .waybillsearch-origin').val(),
		destination = $(modal + ' .waybillsearch-destination').val(),
		destinationroute = $(modal + ' .waybillsearch-destinationroute').val(),
		shipper = $(modal + ' .waybillsearch-shipper').val(),
		consignee = $(modal + ' .waybillsearch-consignee').val(),
		pickupdatefrom = $(modal + ' .waybillsearch-pickupdatefrom').val(),
		pickupdateto = $(modal + ' .waybillsearch-pickupdateto').val(),
		pickupcity = $(modal + ' .waybillsearch-city').val(),
		pickupregion = $(modal + ' .waybillsearch-region').val(),
		trackingnumber = $(modal + ' .waybillsearch-trackingnumber').val(),
		reference = $(modal + ' .waybillsearch-reference').val(),
		billingnumber = $(modal + ' .waybillsearch-billingnumber').val(),
		mawbl = $(modal + ' .waybillsearch-mawbl').val();

	$(contentWB + ' #waybillsearch-table')
		.flexOptions({
			url: encodeURI(
				`loadables/ajax/transactions.waybill-search.php?status=${status}&origin=${origin}&destination=${destination}&shipper=${shipper}&consignee=${consignee}&pickupdatefrom=${pickupdatefrom}&pickupdateto=${pickupdateto}&pickupcity=${pickupcity}&pickupregion=${pickupregion}&bookingnumber=${bookingnumber}&manifestnumber=${manifestnumber}&invoicenumber=${invoicenumber}&destinationroute=${destinationroute}&trackingnumber=${trackingnumber}&mawbl=${mawbl}&billingnumber=${billingnumber}&reference=${reference}`
			),
			sortname: 'txn_waybill.waybill_number',
			sortorder: 'asc'
		})
		.flexReload();
}

$(document).on(
	'keyup',
	contentWB +
		' #waybill-searchmodallookup .waybillsearch-pickupdatefrom,' +
		contentWB +
		' #waybill-searchmodallookup .waybillsearch-pickupdateto, ' +
		contentWB +
		' #waybill-searchmodallookup .waybillsearch-bookingnumber, ' +
		contentWB +
		' #waybill-searchmodallookup .waybillsearch-manifestnumber, ' +
		contentWB +
		' #waybill-searchmodallookup .waybillsearch-invoicenumber, #waybill-searchmodallookup .waybillsearch-trackingnumber, #waybill-searchmodallookup .waybillsearch-mawbl',
	function (e) {
		var key = e.which || e.keycode;
		if (key == '13') {
			var modal = '#' + $(this).closest('.modal').attr('id');
			searchWaybillLookup(modal);
		}
	}
);

$(document)
	.off('click', contentWB + ' #waybillsearch-searchbtn:not(".disabled")')
	.on('click', contentWB + ' #waybillsearch-searchbtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		searchWaybillLookup(modal);
	});

/************************** SEARCHING - END ********************************/

/************************* PRINTING *****************************************/

/*$(document).off('change',contentWB+' .waybillprintingmodal-formtype').on('change',contentWB+' .waybillprintingmodal-formtype',function(){
	var modal = '#'+$(this).closest('.modal').attr('id');
	var value = $(this).val();
	if(value=='EXTERNAL'){
		$(modal+' .waybillprintingmodal3plwrapper').removeClass('hidden');
	}
	else{
		$(modal+' .waybillprintingmodal3plwrapper').addClass('hidden');
	}

});*/
$(document)
	.off('click', contentWB + ' #waybill-trans-printbtn:not(".disabled")')
	.on('click', contentWB + ' #waybill-trans-printbtn:not(".disabled")', function () {
		var modal = '#waybillprintingmodal';
		var waybillid = $(contentWB + ' #pgtxnwaybill-id').val();
		var waybillnumber = $(contentWB + ' #pgtxnwaybill-id').attr('pgtxnwaybill-number');
		var btn = $(this);
		btn.addClass('disabled');

		$(modal + ' .waybillprintingmodal-waybillnumber').val(waybillnumber);
		$(modal).modal('show');
		$(document)
			.off('shown.bs.modal', modal)
			.on('shown.bs.modal', modal, function () {
				$(document).off('shown.bs.modal', modal);
				$(modal + ' .waybillprintingmodal-bolnumber')
					.focus()
					.select();
				btn.removeClass('disabled');
			});
	});

$(document)
	.off('click', contentWB + ' #waybillprintingmodal-savebtn:not(".disabled")')
	.on('click', contentWB + ' #waybillprintingmodal-savebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var bolnumber = $(modal + ' .waybillprintingmodal-bolnumber').val();
		var formtype = $(modal + ' .waybillprintingmodal-formtype').val();
		var remarks = $(modal + ' .waybillprintingmodal-remarks')
			.val()
			.replace(/\s/g, '%20');

		var title = 'Print Preview [' + $('#pgtxnwaybill-id').attr('pgtxnwaybill-number') + ']';
		var tabid = formtype + $('#pgtxnwaybill-id').attr('pgtxnwaybill-number');
		var waybillnumber = $('#pgtxnwaybill-id').attr('pgtxnwaybill-number');
		var waybillid = $(contentWB + ' #pgtxnwaybill-id').val();

		var btn = $(this);
		btn.addClass('disabled');

		/*if(bolnumber.trim()==''){
		btn.removeClass('disabled');
		say("Please provide BOL Number");
	}
	else */ if (formtype != 'EXTERNAL' && formtype != 'INTERNAL' && formtype != 'DR' && formtype != 'DR-ALT' && formtype != 'INTERNAL-ALT' && formtype != 'EXTERNAL-ALT' && formtype != 'TRANS-BOL-ORIG') {
			btn.removeClass('disabled');
			say('Please select form type');
		} else {
			if (formtype == 'INTERNAL' || formtype == 'INTERNAL-ALT') {
				$.confirm({
					animation: 'bottom',
					closeAnimation: 'top',
					animationSpeed: 1000,
					animationBounce: 1,
					title: 'Confirmation',
					content: 'Do you want to continue?',
					confirmButton: 'Confirm',
					cancelButton: 'Cancel',
					confirmButtonClass: 'btn-oceanblue',
					cancelButtonClass: 'btn-royalblue',
					theme: 'white',

					confirm: function () {
						$.post(server + 'waybill.php', { increaseprintcounter: 'KFHoEO#0HELKN#Opsy#lka$P#HlNlk!I#H$', waybillid: waybillid }, function (data) {
							if (data.trim() == 'success') {
								$(modal).modal('hide');
								$(document)
									.off('hidden.bs.modal', modal)
									.on('hidden.bs.modal', modal, function () {
										$(document).off('hidden.bs.modal', modal);

										getWaybillInformation(waybillnumber);
										if ($('.content>.content-tab-pane .content-tabs').find("li[data-pane='#" + tabid + "tabpane']").length >= 1) {
											$(".content>.content-tab-pane .content-tabs>li[data-pane='#" + tabid + "tabpane']").remove();
											$(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='" + tabid + "tabpane']").remove();
											$('#loading-img').removeClass('hidden');
											$('.content').animate({ scrollTop: 0 }, 300);

											$('.content>.content-tab-pane .content-tabs>li').removeClass('active');
											$('.content>.content-tab-pane .content-pane-wrapper>.content-pane').removeClass('active');
											$('.content>.content-tab-pane .content-tabs').append("<li data-pane='#" + tabid + "tabpane' class='active'>" + title + "<i class='fa fa-remove'></i></li>");
											$('.content>.content-tab-pane .content-pane-wrapper').append("<div class='content-pane active' id='" + tabid + "tabpane'></div>");
											$('.content>.content-tab-pane .content-pane-wrapper>.content-pane:last-child').load(
												'Printouts/print-preview.php?txnnumber=' +
													waybillnumber +
													'&source=printouts/transactions/waybill.php?txnnumber=' +
													waybillnumber +
													'tpicorpaabbccreference=' +
													waybillnumber +
													'tpicorpaabbccbolnumber=' +
													bolnumber +
													'tpicorpaabbccformtype=' +
													formtype +
													'tpicorpaabbccremarks=' +
													remarks
											);
											setTimeout(function () {
												$('#loading-img').addClass('hidden');
											}, 400);
										} else {
											$('#loading-img').removeClass('hidden');
											$('.content').animate({ scrollTop: 0 }, 300);

											$('.content>.content-tab-pane .content-tabs>li').removeClass('active');
											$('.content>.content-tab-pane .content-pane-wrapper>.content-pane').removeClass('active');
											$('.content>.content-tab-pane .content-tabs').append("<li data-pane='#" + tabid + "tabpane' class='active'>" + title + "<i class='fa fa-remove'></i></li>");
											$('.content>.content-tab-pane .content-pane-wrapper').append("<div class='content-pane active' id='" + tabid + "tabpane'></div>");
											$('.content>.content-tab-pane .content-pane-wrapper>.content-pane:last-child').load(
												'Printouts/print-preview.php?txnnumber=' +
													waybillnumber +
													'&source=printouts/transactions/waybill.php?txnnumber=' +
													waybillnumber +
													'tpicorpaabbccreference=' +
													waybillnumber +
													'tpicorpaabbccbolnumber=' +
													bolnumber +
													'tpicorpaabbccformtype=' +
													formtype +
													'tpicorpaabbccremarks=' +
													remarks
											);
											setTimeout(function () {
												$('#loading-img').addClass('hidden');
											}, 400);
										}
										btn.removeClass('disabled');
									});
							} else if (data.trim() == 'invalidwaybill') {
								say('Unable to print waybill. Invalid waybill number, please refresh page.');
								btn.removeClass('disabled');
							} else if (data.trim() == 'loggedstatus') {
								getWaybillInformation(waybillnumber);
								say('Unable to print waybill. Transaction not yet posted.');
								btn.removeClass('disabled');
							} else {
								say(data);
								btn.removeClass('disabled');
							}
						});
					},
					cancel: function () {
						btn.removeClass('disabled');
					}
				});
			} else if (formtype == 'EXTERNAL' || formtype == 'EXTERNAL-ALT') {
				$(modal).modal('hide');
				$(document)
					.off('hidden.bs.modal', modal)
					.on('hidden.bs.modal', modal, function () {
						$(document).off('hidden.bs.modal', modal);
						if ($('.content>.content-tab-pane .content-tabs').find("li[data-pane='#" + tabid + "tabpane']").length >= 1) {
							$(".content>.content-tab-pane .content-tabs>li[data-pane='#" + tabid + "tabpane']").remove();
							$(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='" + tabid + "tabpane']").remove();
							$('#loading-img').removeClass('hidden');
							$('.content').animate({ scrollTop: 0 }, 300);

							$('.content>.content-tab-pane .content-tabs>li').removeClass('active');
							$('.content>.content-tab-pane .content-pane-wrapper>.content-pane').removeClass('active');
							$('.content>.content-tab-pane .content-tabs').append("<li data-pane='#" + tabid + "tabpane' class='active'>" + title + "<i class='fa fa-remove'></i></li>");
							$('.content>.content-tab-pane .content-pane-wrapper').append("<div class='content-pane active' id='" + tabid + "tabpane'></div>");
							$('.content>.content-tab-pane .content-pane-wrapper>.content-pane:last-child').load(
								'Printouts/print-preview.php?txnnumber=' +
									waybillnumber +
									'&source=printouts/transactions/waybill-external-form.php?txnnumber=' +
									waybillnumber +
									'tpicorpaabbccreference=' +
									waybillnumber +
									'tpicorpaabbccbolnumber=' +
									bolnumber +
									'tpicorpaabbccformtype=' +
									formtype +
									'tpicorpaabbccremarks=' +
									remarks
							);
							setTimeout(function () {
								$('#loading-img').addClass('hidden');
							}, 400);
						} else {
							$('#loading-img').removeClass('hidden');
							$('.content').animate({ scrollTop: 0 }, 300);

							$('.content>.content-tab-pane .content-tabs>li').removeClass('active');
							$('.content>.content-tab-pane .content-pane-wrapper>.content-pane').removeClass('active');
							$('.content>.content-tab-pane .content-tabs').append("<li data-pane='#" + tabid + "tabpane' class='active'>" + title + "<i class='fa fa-remove'></i></li>");
							$('.content>.content-tab-pane .content-pane-wrapper').append("<div class='content-pane active' id='" + tabid + "tabpane'></div>");
							$('.content>.content-tab-pane .content-pane-wrapper>.content-pane:last-child').load(
								'Printouts/print-preview.php?txnnumber=' +
									waybillnumber +
									'&source=printouts/transactions/waybill-external-form.php?txnnumber=' +
									waybillnumber +
									'tpicorpaabbccreference=' +
									waybillnumber +
									'tpicorpaabbccbolnumber=' +
									bolnumber +
									'tpicorpaabbccformtype=' +
									formtype +
									'tpicorpaabbccremarks=' +
									remarks
							);
							setTimeout(function () {
								$('#loading-img').addClass('hidden');
							}, 400);
						}
						btn.removeClass('disabled');
					});
			} else if (formtype == 'DR' || formtype == 'DR-ALT') {
				$(modal).modal('hide');
				$(document)
					.off('hidden.bs.modal', modal)
					.on('hidden.bs.modal', modal, function () {
						$(document).off('hidden.bs.modal', modal);
						if ($('.content>.content-tab-pane .content-tabs').find("li[data-pane='#" + tabid + "tabpane']").length >= 1) {
							$(".content>.content-tab-pane .content-tabs>li[data-pane='#" + tabid + "tabpane']").remove();
							$(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='" + tabid + "tabpane']").remove();
							$('#loading-img').removeClass('hidden');
							$('.content').animate({ scrollTop: 0 }, 300);

							$('.content>.content-tab-pane .content-tabs>li').removeClass('active');
							$('.content>.content-tab-pane .content-pane-wrapper>.content-pane').removeClass('active');
							$('.content>.content-tab-pane .content-tabs').append("<li data-pane='#" + tabid + "tabpane' class='active'>" + title + "<i class='fa fa-remove'></i></li>");
							$('.content>.content-tab-pane .content-pane-wrapper').append("<div class='content-pane active' id='" + tabid + "tabpane'></div>");
							$('.content>.content-tab-pane .content-pane-wrapper>.content-pane:last-child').load(
								'Printouts/print-preview.php?txnnumber=' +
									waybillnumber +
									'&source=printouts/transactions/waybill-dr-form.php?txnnumber=' +
									waybillnumber +
									'tpicorpaabbccreference=' +
									waybillnumber +
									'tpicorpaabbccbolnumber=' +
									bolnumber +
									'tpicorpaabbccformtype=' +
									formtype
							);
							setTimeout(function () {
								$('#loading-img').addClass('hidden');
							}, 400);
						} else {
							$('#loading-img').removeClass('hidden');
							$('.content').animate({ scrollTop: 0 }, 300);

							$('.content>.content-tab-pane .content-tabs>li').removeClass('active');
							$('.content>.content-tab-pane .content-pane-wrapper>.content-pane').removeClass('active');
							$('.content>.content-tab-pane .content-tabs').append("<li data-pane='#" + tabid + "tabpane' class='active'>" + title + "<i class='fa fa-remove'></i></li>");
							$('.content>.content-tab-pane .content-pane-wrapper').append("<div class='content-pane active' id='" + tabid + "tabpane'></div>");
							$('.content>.content-tab-pane .content-pane-wrapper>.content-pane:last-child').load(
								'Printouts/print-preview.php?txnnumber=' +
									waybillnumber +
									'&source=printouts/transactions/waybill-dr-form.php?txnnumber=' +
									waybillnumber +
									'tpicorpaabbccreference=' +
									waybillnumber +
									'tpicorpaabbccbolnumber=' +
									bolnumber +
									'tpicorpaabbccformtype=' +
									formtype
							);
							setTimeout(function () {
								$('#loading-img').addClass('hidden');
							}, 400);
						}
						btn.removeClass('disabled');
					});
			} else if (formtype == 'TRANS-BOL-ORIG') {
				$(modal).modal('hide');
				$(document)
					.off('hidden.bs.modal', modal)
					.on('hidden.bs.modal', modal, function () {
						$(document).off('hidden.bs.modal', modal);
						if ($('.content>.content-tab-pane .content-tabs').find("li[data-pane='#" + tabid + "tabpane']").length >= 1) {
							$(".content>.content-tab-pane .content-tabs>li[data-pane='#" + tabid + "tabpane']").remove();
							$(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='" + tabid + "tabpane']").remove();
							$('#loading-img').removeClass('hidden');
							$('.content').animate({ scrollTop: 0 }, 300);

							$('.content>.content-tab-pane .content-tabs>li').removeClass('active');
							$('.content>.content-tab-pane .content-pane-wrapper>.content-pane').removeClass('active');
							$('.content>.content-tab-pane .content-tabs').append("<li data-pane='#" + tabid + "tabpane' class='active'>" + title + "<i class='fa fa-remove'></i></li>");
							$('.content>.content-tab-pane .content-pane-wrapper').append("<div class='content-pane active' id='" + tabid + "tabpane'></div>");
							$('.content>.content-tab-pane .content-pane-wrapper>.content-pane:last-child').load(
								'Printouts/print-preview.php?txnnumber=' +
									waybillnumber +
									'&source=printouts/transactions/bol-original.php?txnnumber=' +
									waybillnumber +
									'tpicorpaabbccreference=' +
									waybillnumber +
									'tpicorpaabbccbolnumber=' +
									bolnumber +
									'tpicorpaabbccformtype=' +
									formtype
							);
							setTimeout(function () {
								$('#loading-img').addClass('hidden');
							}, 400);
						} 
						btn.removeClass('disabled');
					});
			}
		}
	});

/*$(document).off('click',contentWB+' #waybill-trans-printbtn:not(".disabled")').on('click',contentWB+' #waybill-trans-printbtn:not(".disabled")',function(){
	var title = 'Print Preview ['+ $('#pgtxnwaybill-id').attr('pgtxnwaybill-number')+']';
	var tabid = $('#pgtxnwaybill-id').attr('pgtxnwaybill-number');
	var waybillid = $(contentWB+' #pgtxnwaybill-id').val();
	var btn = $(this);
	btn.addClass('disabled');

	$.confirm({
				animation: 'bottom', 
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce:1,
				title: 'Print BOL',
				content: 'Do you want to continue?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',	
				confirmButtonClass: 'btn-oceanblue', 
				cancelButtonClass: 'btn-royalblue', 
				theme: 'white', 

				confirm: function(){
					$.post(server+'waybill.php',{increaseprintcounter:'KFHoEO#0HELKN#Opsy#lka$P#HlNlk!I#H$',waybillid:waybillid},function(data){

						if(data.trim()=='success'){
							getWaybillInformation(tabid);
							if($(".content>.content-tab-pane .content-tabs").find("li[data-pane='#"+tabid+"tabpane']").length>=1){
								$(".content>.content-tab-pane .content-tabs>li[data-pane='#"+tabid+"tabpane']").remove();
								$(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='"+tabid+"tabpane']").remove();
								$('#loading-img').removeClass('hidden');
								$('.content').animate({scrollTop:0},300);

								$('.content>.content-tab-pane .content-tabs>li').removeClass('active');
								$('.content>.content-tab-pane .content-pane-wrapper>.content-pane').removeClass('active');
								$('.content>.content-tab-pane .content-tabs').append("<li data-pane='#"+tabid+"tabpane' class='active'>"+title+"<i class='fa fa-remove'></i></li>");
								$('.content>.content-tab-pane .content-pane-wrapper').append("<div class='content-pane active' id='"+tabid+"tabpane'></div>");
								$('.content>.content-tab-pane .content-pane-wrapper>.content-pane:last-child').load("Printouts/print-preview.php?source=printouts/transactions/waybill.php?txnnumber="+tabid+'&reference='+tabid);
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
								$('.content>.content-tab-pane .content-pane-wrapper>.content-pane:last-child').load("Printouts/print-preview.php?source=printouts/transactions/waybill.php?txnnumber="+tabid+'&reference='+tabid);
								setTimeout(function(){
									$('#loading-img').addClass('hidden');
								},400);
							}
						}
						else if(data.trim()=='invalidwaybill'){
							say("Unable to print waybill. Invalid waybill number, please refresh page.");
							btn.removeClass('disabled');
						}
						else if(data.trim()=='loggedstatus'){
							getWaybillInformation(tabid);
							say("Unable to print waybill. Transaction not yet posted.");
							btn.removeClass('disabled');
						}
						else{
							say(data);
							btn.removeClass('disabled');
						}
					});
				},
				cancel: function(){
					btn.removeClass('disabled');
				}
	});

	
});*/

/************************* PRINTING - END *****************************************/

/************** UPDATE STATUS BTN *******************/

$(document)
	.off('click', contentWB + ' #waybill-trans-updatestatusbtn:not(".disabled")')
	.on('click', contentWB + ' #waybill-trans-updatestatusbtn:not(".disabled")', function () {
		var waybillid = $(contentWB + ' #pgtxnwaybill-id').val();
		var waybillnumber = $(contentWB + ' #pgtxnwaybill-id').attr('pgtxnwaybill-number');

		$(contentWB + ' #waybill-updatestatusmodal').modal('show');

		$(contentWB + ' #waybill-updatestatusmodal-waybillid').val(waybillid);
		$(contentWB + ' .waybill-updatestatusmodal-waybillnumber').val(waybillnumber);
	});

$(document)
	.off('click', contentWB + ' #waybill-updatestatusmodal-savebtn:not(".disabled")')
	.on('click', contentWB + ' #waybill-updatestatusmodal-savebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var waybillid = $(modal + ' #waybill-updatestatusmodal-waybillid').val();
		var waybillnumber = $(modal + ' .waybill-updatestatusmodal-waybillnumber').val();
		var status = $(modal + ' .waybill-updatestatusmodal-status').val();
		var remarks = $(modal + ' .waybill-updatestatusmodal-remarks').val();
		var tpl = $(contentWB + ' .waybill-3pl').val();

		var receiveddate = '';
		var receivedby = '';
		var personnel = '';

		if (status == 'DEL' || status == 'DELIVERED' || status == 'RTS' || status == 'RETURN TO SHIPPER') {
			receivedby = $(modal + ' .waybill-updatestatusmodal-receivedby').val();
			receiveddate = $(modal + ' .waybill-updatestatusmodal-receiveddate').val();
			personnel = $(modal + ' .waybill-updatestatusmodal-personnel').val();
		}

		var button = $(this);
		button.addClass('disabled');
		$(modal + ' .modal-errordiv').empty();

		if (status == '' || status == null || status == 'null' || status == 'NULL' || status == undefined) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select status.</div></div>");
			button.removeClass('disabled');
		} else if ((status.toUpperCase() == 'DEL' || status.toUpperCase() == 'DELIVERED' || status.toUpperCase() == 'RTS' || status.toUpperCase() == 'RETURN TO SHIPPER') && receivedby.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide received by.</div></div>");
			$(modal + ' .waybill-updatestatusmodal-receivedby').focus();
			button.removeClass('disabled');
		} else if (
			(status.toUpperCase() == 'DEL' || status.toUpperCase() == 'DELIVERED' || status.toUpperCase() == 'RTS' || status.toUpperCase() == 'RETURN TO SHIPPER') &&
			receiveddate.trim() == ''
		) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide received date/time.</div></div>");
			$(modal + ' .waybill-updatestatusmodal-receiveddate').focus();
			button.removeClass('disabled');
		} else if (
			(status.toUpperCase() == 'DEL' || status.toUpperCase() == 'DELIVERED' || status.toUpperCase() == 'RTS' || status.toUpperCase() == 'RETURN TO SHIPPER') &&
			(personnel == null || personnel == 'null' || personnel == 'NULL' || personnel == undefined) &&
			tpl == 1
		) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select courier.</div></div>");
			$(modal + ' .waybill-updatestatusmodal-personnel').select2('open');
			button.removeClass('disabled');
		} else if (remarks.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide remarks.</div></div>");
			$(modal + ' .waybill-updatestatusmodal-remarks').focus();
			button.removeClass('disabled');
		} else {
			$.confirm({
				animation: 'bottom',
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce: 1,
				title: 'Update Status',
				content: 'Do you want to continue?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',
				confirmButtonClass: 'btn-oceanblue',
				cancelButtonClass: 'btn-royalblue',
				theme: 'white',

				confirm: function () {
					$('#loading-img').removeClass('hidden');
					$.post(
						server + 'waybill.php',
						{
							updateStatusWaybill: 'kkjO#@siaah3h0$09odfj$owenxezpo92po@k@',
							waybillid: waybillid,
							status: status,
							remarks: remarks,
							receivedby: receivedby,
							receiveddate: receiveddate,
							personnel: personnel
						},
						function (data) {
							//alert(data);
							if (data.trim() == 'success') {
								$(modal).modal('hide');
								$(document)
									.off('hidden.bs.modal', modal)
									.on('hidden.bs.modal', modal, function () {
										$(document).off('hidden.bs.modal', modal);

										getWaybillInformation(waybillnumber);
										$(modal + ' #waybill-updatestatusmodal-waybillid').val('');
										$(modal + ' .waybill-updatestatusmodal-waybillnumber').val('');
										$(modal + ' .waybill-updatestatusmodal-status')
											.empty()
											.trigger('change');
										$(modal + ' .waybill-updatestatusmodal-personnel')
											.empty()
											.trigger('change');
										$(modal + ' .waybill-updatestatusmodal-remarks').val('');
										$(modal + ' .waybill-updatestatusmodal-receivedby').val('');
										$(modal + ' .waybill-updatestatusmodal-receiveddate').val('');

										button.removeClass('disabled');
										$('#loading-img').addClass('hidden');
									});
							} else if (data.trim() == 'invalidreceiveddate') {
								$(modal + ' .modal-errordiv').html(
									"<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide received date/time.</div></div>"
								);
								$(modal + ' .waybill-updatestatusmodal-receiveddate').focus();
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							} else if (data.trim() == 'invalidwaybill') {
								say('Unable to update waybill status. ID: ' + waybillid + ' - Waybill No.: ' + waybillnumber);
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							} else if (data.trim() == 'invalidstatus') {
								say('Unable to update waybill status. Status invalid.');
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							} else if (data.trim() == 'samestatus') {
								getWaybillInformation(waybillnumber);
								say('Unable to update waybill status. Selected status is the same as current waybill status.');
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							} else if (data.trim() == 'nostatusupdate') {
								$(modal).modal('hide');
								$(document)
									.off('hidden.bs.modal', modal)
									.on('hidden.bs.modal', modal, function () {
										$(document).off('hidden.bs.modal', modal);

										getWaybillInformation(waybillnumber);
										$(modal + ' #waybill-updatestatusmodal-waybillid').val('');
										$(modal + ' .waybill-updatestatusmodal-waybillnumber').val('');
										$(modal + ' .waybill-updatestatusmodal-status')
											.empty()
											.trigger('change');
										$(modal + ' .waybill-updatestatusmodal-remarks').val('');
										$(modal + ' .waybill-updatestatusmodal-receivedby').val('');
										$(modal + ' .waybill-updatestatusmodal-receiveddate').val('');

										say('Unable to update status. Current Status considered as final.');
										button.removeClass('disabled');
										$('#loading-img').addClass('hidden');
									});
							} else if (data.trim() == 'usernoaccess') {
								$(modal).modal('hide');
								$(document)
									.off('hidden.bs.modal', modal)
									.on('hidden.bs.modal', modal, function () {
										$(document).off('hidden.bs.modal', modal);

										getWaybillInformation(waybillnumber);
										$(modal + ' #waybill-updatestatusmodal-waybillid').val('');
										$(modal + ' .waybill-updatestatusmodal-waybillnumber').val('');
										$(modal + ' .waybill-updatestatusmodal-status')
											.empty()
											.trigger('change');
										$(modal + ' .waybill-updatestatusmodal-remarks').val('');
										$(modal + ' .waybill-updatestatusmodal-receivedby').val('');
										$(modal + ' .waybill-updatestatusmodal-receiveddate').val('');

										say('Unable to update status. No user permission.');
										button.removeClass('disabled');
										$('#loading-img').addClass('hidden');
									});
							} else {
								var rsp = data.trim();

								rsp = rsp.split('@');
								var reftxn = rsp[1];

								if (rsp[0] == 'inpendingmanifesttxn' && rsp[3] == 'inpendingmanifesttxn') {
									say('Unable to update waybill status to ' + rsp[2] + '. Corresponding manifest transaction must be posted. [' + reftxn + ']');
									button.removeClass('disabled');
									$('#loading-img').addClass('hidden');
								} else if (rsp[0] == 'inpendingloadplantxn' && rsp[3] == 'inpendingloadplantxn') {
									say('Unable to update waybill status to ' + rsp[2] + '. Waybill is included in an active load plan transaction. [' + reftxn + ']');
									button.removeClass('disabled');
									$('#loading-img').addClass('hidden');
								} else {
									alert(data);
									button.removeClass('disabled');
									$('#loading-img').addClass('hidden');
								}
							}
						}
					);
				},
				cancel: function () {
					button.removeClass('disabled');
				}
			});
		}
	});

$(document)
	.off('click', contentWB + ' #waybill-trans-viewstatushistorybtn:not(".disabled")')
	.on('click', contentWB + ' #waybill-trans-viewstatushistorybtn:not(".disabled")', function () {
		$(contentWB + ' #waybill-statushistorymodal').modal('show');

		var waybillnumber = $(contentWB + ' #pgtxnwaybill-id').attr('pgtxnwaybill-number');

		$(document)
			.off('shown.bs.modal', contentWB + ' #waybill-statushistorymodal')
			.on('shown.bs.modal', contentWB + ' #waybill-statushistorymodal', function () {
				$(document).off('shown.bs.modal', contentWB + ' #waybill-statushistorymodal');
				$(contentWB + ' #waybill-statushistorytbl')
					.flexOptions({
						url: 'loadables/ajax/transactions.waybill.status-history.php?waybillnumber=' + waybillnumber,
						sortname: 'created_date',
						sortorder: 'desc'
					})
					.flexReload();

				$(contentWB + ' #waybill-billinghistorytbl')
					.flexOptions({
						url: 'loadables/ajax/transactions.waybill.billing-history.php?waybillnumber=' + waybillnumber,
						sortname: 'created_date',
						sortorder: 'desc'
					})
					.flexReload();
			});
	});

/************ UPDATE STATUS BTN - END *****************/

function computeWBPDWolumetricWeight() {
	var length = $(contentWB + ' .packagedimensionsmodalflds-length').val();
	var width = $(contentWB + ' .packagedimensionsmodalflds-width').val();
	var height = $(contentWB + ' .packagedimensionsmodalflds-height').val();
	var qty = $(contentWB + ' .packagedimensionsmodalflds-quantity').val();

	if (length == '') {
		length = 0;
	}
	if (width == '') {
		width = 0;
	}
	if (height == '') {
		height = 0;
	}
	if (qty == '') {
		qty = 0;
	}
	length = parseFloat(length);
	width = parseFloat(width);
	height = parseFloat(height);

	var x = length * width * height;
	var divisor = currentdivisor;

	if (parseFloat(divisor) > 0) {
		var vw = x / divisor;
	} else {
		var vw = 0;
	}

	//vw = vw*qty;
	vw = vw.toFixed(4);

	$(contentWB + ' .packagedimensionsmodalflds-volweight').val(vw);
}

function computeWBPDcbm() {
	var length = $(contentWB + ' .packagedimensionsmodalflds-length').val();
	var width = $(contentWB + ' .packagedimensionsmodalflds-width').val();
	var height = $(contentWB + ' .packagedimensionsmodalflds-height').val();
	var qty = $(contentWB + ' .packagedimensionsmodalflds-quantity').val();

	if (length == '') {
		length = 0;
	}
	if (width == '') {
		width = 0;
	}
	if (height == '') {
		height = 0;
	}
	if (qty == '') {
		qty = 0;
	}
	length = parseFloat(length);
	width = parseFloat(width);
	height = parseFloat(height);

	var cbm = (length / 100) * (width / 100) * (height / 100);

	var cbm = cbm * qty;

	cbm = cbm.toFixed(4);
	$(contentWB + ' .packagedimensionsmodalflds-cbm').val(cbm);
}

$(document)
	.off('keyup', contentWB + ' .packagedimensionsmodalflds-length')
	.on('keyup', contentWB + ' .packagedimensionsmodalflds-length', function () {
		computeWBPDWolumetricWeight();
		computeWBPDcbm();
	});
$(document)
	.off('keyup', contentWB + ' .packagedimensionsmodalflds-width')
	.on('keyup', contentWB + ' .packagedimensionsmodalflds-width', function () {
		computeWBPDWolumetricWeight();
		computeWBPDcbm();
	});
$(document)
	.off('keyup', contentWB + ' .packagedimensionsmodalflds-height')
	.on('keyup', contentWB + ' .packagedimensionsmodalflds-height', function () {
		computeWBPDWolumetricWeight();
		computeWBPDcbm();
	});
$(document)
	.off('keyup', contentWB + ' .packagedimensionsmodalflds-quantity')
	.on('keyup', contentWB + ' .packagedimensionsmodalflds-quantity', function () {
		computeWBPDWolumetricWeight();
		computeWBPDcbm();
	});

/****************** PACKAGE DIMENSIONS TABLE *******************/
function computeWBtotalvw(table) {
	var totalvw = 0;
	table
		.column(7)
		.nodes()
		.to$()
		.each(function () {
			qty = parseFloat($(this).closest('tr').find('.waybill-packagedimensiontbl-quantity').text());
			totalvw = totalvw + parseFloat($(this).text()) * qty;
		});

	totalvw = Math.ceil(totalvw); //(totalvw).toFixed(4);
	return totalvw;
}

function computeWBtotalcbm(table) {
	var totalcbm = 0;
	table
		.column(8)
		.nodes()
		.to$()
		.each(function () {
			qty = parseFloat($(this).closest('tr').find('.waybill-packagedimensiontbl-quantity').text());
			totalcbm = totalcbm + parseFloat($(this).text()) * qty;
		});

	totalcbm = totalcbm.toFixed(4);
	return totalcbm;
}

function computeWBtotalactualweight(table) {
	var actualweight = 0;
	var qty = 0;
	table
		.column(3)
		.nodes()
		.to$()
		.each(function () {
			qty = parseFloat($(this).closest('tr').find('.waybill-packagedimensiontbl-quantity').text());
			actualweight = actualweight + parseFloat($(this).text()) * qty;
		});

	actualweight = actualweight.toFixed(4);
	return actualweight;
}

function computeWBtotalnumberofpackages(table) {
	var packagecount = 0;
	table
		.column(1)
		.nodes()
		.to$()
		.each(function () {
			packagecount = packagecount + parseInt($(this).text());
		});
	packagecount = parseInt(packagecount);
	return packagecount;
}

function computeVWCBMVal(table) {
	var tmp = '';
	var vw = computeWBtotalvw(table);
	var cbm = computeWBtotalcbm(table);
	var actualweight = computeWBtotalactualweight(table);
	var numberofpackages = computeWBtotalnumberofpackages(table);

	if (vw > cbm) {
		tmp = vw;
	} else {
		tmp = cbm;
	}

	$(contentWB + ' .waybill-vwcbm').val(cbm);
	$(contentWB + ' .waybill-vw').val(vw);
	$(contentWB + ' .waybill-actualweight').val(actualweight);
	$(contentWB + ' .waybill-numberofpackages').val(numberofpackages);

	computeRatesWB();
}

////// INSERT RATE ROW
/*function waybillPackageDimensionInsertRow(table){
	var length = $(contentWB+' .packagedimensionsmodalflds-length').val(),
		width = $(contentWB+' .packagedimensionsmodalflds-width').val(),
		height = $(contentWB+' .packagedimensionsmodalflds-height').val(),
		qty = parseInt($(contentWB+' .packagedimensionsmodalflds-quantity').val()),
		uom = $(contentWB+' .packagedimensionsmodalflds-uom').val(),
		actualweight = $(contentWB+' .packagedimensionsmodalflds-actualweight').val(),
		volweight = $(contentWB+' .packagedimensionsmodalflds-volweight').val(),
		cbm = $(contentWB+' .packagedimensionsmodalflds-cbm').val();


	    if(parseFloat(length)>0&&parseFloat(width)>0&&parseFloat(height)>0&&parseInt(qty)>0&&parseFloat(cbm)>0&&parseFloat(volweight)>0&&parseFloat(actualweight)>0&&uom!=null&&uom!=''&&uom!='null'&&uom!='NULL'
	      )
	    {
			table.row.add([
						"<input type='checkbox' class='rowcheckbox'>",
						"<span class='waybill-packagedimensiontbl-quantity'>"+qty+"</span>",
						"<span class='waybill-packagedimensiontbl-uom'>"+uom+"</span>",
						"<span class='waybill-packagedimensiontbl-actualweight'>"+actualweight+"</span>",
						"<span class='waybill-packagedimensiontbl-length'>"+length+"</span>",
						"<span class='waybill-packagedimensiontbl-width'>"+width+"</span>",
						"<span class='waybill-packagedimensiontbl-height'>"+height+"</span>",
						"<span class='waybill-packagedimensiontbl-volweight'>"+volweight+"</span>",
						"<span class='waybill-packagedimensiontbl-cbm'>"+cbm+"</span>"
				

			]).search('').draw();
	    	waybillPackageDimensionClearFields();

	    	computeVWCBMVal(table);
	    	computeRatesWB();
		}
		else if(!length>0){
			$(contentWB+' .packagedimensionsmodalflds-length').focus();
		}
		else if(!width>0){
			$(contentWB+' .packagedimensionsmodalflds-width').focus();
		}
		else if(!height>0){
			$(contentWB+' .packagedimensionsmodalflds-height').focus();
		}
		else if(!qty>0){
			$(contentWB+' .packagedimensionsmodalflds-quantity').focus();
		}
		else if(!actualweight>0){
			$(contentWB+' .packagedimensionsmodalflds-actualweight').focus();
		}
		else if(uom==null||uom==''||uom==undefined||uom=='NULL'||uom=='null'){
			$(contentWB+' .packagedimensionsmodalflds-uom').focus();
		}

}*/
function waybillPackageDimensionInsertRow(table) {
	var length = $(contentWB + ' .packagedimensionsmodalflds-length').val(),
		width = $(contentWB + ' .packagedimensionsmodalflds-width').val(),
		height = $(contentWB + ' .packagedimensionsmodalflds-height').val(),
		qty = parseInt($(contentWB + ' .packagedimensionsmodalflds-quantity').val()),
		uom = $(contentWB + ' .packagedimensionsmodalflds-uom').val(),
		actualweight = $(contentWB + ' .packagedimensionsmodalflds-actualweight').val(),
		volweight = $(contentWB + ' .packagedimensionsmodalflds-volweight').val(),
		cbm = $(contentWB + ' .packagedimensionsmodalflds-cbm').val();

	//parseFloat(length)>0&&parseFloat(width)>0&&parseFloat(height)>0&&parseFloat(volweight)>0&&
	if (parseFloat(volweight) >= 0 && parseInt(qty) > 0 && parseFloat(cbm) > 0 && parseFloat(actualweight) >= 0 && uom != null && uom != '' && uom != 'null' && uom != 'NULL') {
		if (length == '') {
			length = 0;
		}
		if (width == '') {
			width = 0;
		}
		if (height == '') {
			height = 0;
		}
		if (volweight == '') {
			volweight = 0;
		}

		table.row
			.add([
				"<input type='checkbox' class='rowcheckbox'>",
				"<span class='waybill-packagedimensiontbl-quantity'>" + qty + '</span>',
				"<span class='waybill-packagedimensiontbl-uom'>" + uom + '</span>',
				"<span class='waybill-packagedimensiontbl-actualweight'>" + actualweight + '</span>',
				"<span class='waybill-packagedimensiontbl-length'>" + length + '</span>',
				"<span class='waybill-packagedimensiontbl-width'>" + width + '</span>',
				"<span class='waybill-packagedimensiontbl-height'>" + height + '</span>',
				"<span class='waybill-packagedimensiontbl-volweight'>" + volweight + '</span>',
				"<span class='waybill-packagedimensiontbl-cbm'>" + cbm + '</span>'
			])
			.search('')
			.draw();
		waybillPackageDimensionClearFields();

		computeVWCBMVal(table);
		computeRatesWB();
	} else if (!qty > 0) {
		/*else if(!length>0){
			$(contentWB+' .packagedimensionsmodalflds-length').focus();
		}
		else if(!width>0){
			$(contentWB+' .packagedimensionsmodalflds-width').focus();
		}
		else if(!height>0){
			$(contentWB+' .packagedimensionsmodalflds-height').focus();
		}*/
		$(contentWB + ' .packagedimensionsmodalflds-quantity')
			.focus()
			.select();
	} else if (uom == null || uom == '' || uom == undefined || uom == 'NULL' || uom == 'null') {
		$(contentWB + ' .packagedimensionsmodalflds-uom').select2('open');
	} else if (!(parseFloat(actualweight) > 0)) {
		$(contentWB + ' .packagedimensionsmodalflds-actualweight')
			.focus()
			.select();
	} else if (!(parseFloat(cbm) > 0)) {
		$(contentWB + ' .packagedimensionsmodalflds-cbm')
			.focus()
			.select();
	} else if (!(parseFloat(volweight) > 0)) {
		$(contentWB + ' .packagedimensionsmodalflds-volweight')
			.focus()
			.select();
	}
}

$(document)
	.off('click', contentWB + ' .packagedimensionsmodalflds-insertbtn')
	.on('click', contentWB + ' .packagedimensionsmodalflds-insertbtn', function () {
		waybillPackageDimensionInsertRow($(contentWB + ' #waybill-packagedimensionsmodaltbl').DataTable());
	});
////// INSERT RATE ROW END

////// REMOVE RATE ROW
function waybillPackageDimensionRemoveRow(table) {
	$(table + ' tbody tr .rowcheckbox:checked').each(function () {
		var tr = $(this).closest('tr');
		$(table).DataTable().row(tr).remove().draw();
	});

	computeRatesWB();
}

$(document)
	.off('click', contentWB + ' .packagedimensionsmodalflds-removebtn')
	.on('click', contentWB + ' .packagedimensionsmodalflds-removebtn', function () {
		var tbl = '#waybill-packagedimensionsmodaltbl';
		waybillPackageDimensionRemoveRow(tbl);
		computeVWCBMVal($(contentWB + ' #waybill-packagedimensionsmodaltbl').DataTable());
	});
/////// REMOVE RATE ROW END

////// CLEAR RATE INPUT FIELDS
function waybillPackageDimensionClearFields() {
	$(contentWB + ' .packagedimensionsmodalflds-length').val('');
	$(contentWB + ' .packagedimensionsmodalflds-width').val('');
	$(contentWB + ' .packagedimensionsmodalflds-height').val('');
	$(contentWB + ' .packagedimensionsmodalflds-quantity')
		.val('')
		.focus()
		.select();
	$(contentWB + ' .packagedimensionsmodalflds-volweight').val('');
	$(contentWB + ' .packagedimensionsmodalflds-cbm').val('');
	$(contentWB + ' .packagedimensionsmodalflds-uom')
		.empty()
		.trigger('change');
	$(contentWB + ' .packagedimensionsmodalflds-actualweight').val('');
}

$(document)
	.off('click', contentWB + ' .packagedimensionsmodalflds-clearbtn ')
	.on('click', contentWB + ' .packagedimensionsmodalflds-clearbtn', function () {
		waybillPackageDimensionClearFields();
	});
////// CLEAR RATE INPUT FIELDS END

/****************** PACKAGE DIMENSIONS TABLE - END *******************/

/****************** PACKAGE CODE **************************/
function wbaddpackagecode(modal) {
	var code = $(modal + ' .addpackagecodemodal-code').val();
	var waybillnumber = $(contentWB + ' #pgtxnwaybill-id').attr('pgtxnwaybill-number');
	var button = $(this);
	button.addClass('disabled');

	if (code.trim() == '') {
		button.removeClass('disabled');
		$(modal + ' .addpackagecodemodal-code')
			.val('')
			.focus();
	} else {
		$.post(server + 'waybill.php', { AddWaybillPackageCode: '#@1F4fdgrw$ghjt3K@3#4hh$9v7&3s', waybillnumber: waybillnumber, code: code }, function (data) {
			if (data.trim() == 'success') {
				button.removeClass('disabled');
				$(contentWB + ' #waybill-packagecodemodaltbl')
					.DataTable()
					.ajax.url('loadables/ajax/transactions.waybill-package-codes.php?waybill=' + waybillnumber)
					.search('')
					.load();
				$(modal + ' .addpackagecodemodal-code')
					.val('')
					.focus();
			} else if (data.trim() == 'codeexist') {
				button.removeClass('disabled');
				say('Package code already exists');
			} else {
				alert(data);
				button.removeClass('disabled');
			}
		});
	}
}
$(document)
	.off('click', contentWB + ' .addpackagecodemodal-insertbtn:not(".disabled")')
	.on('click', contentWB + ' .addpackagecodemodal-insertbtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		wbaddpackagecode(modal);
	});

$(document)
	.off('keyup', contentWB + ' .addpackagecodemodal-code')
	.on('keyup', contentWB + ' .addpackagecodemodal-code', function (e) {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var key = e.keycode || e.which;
		if (key == 13) {
			wbaddpackagecode(modal);
		}
	});

$(document)
	.off('click', contentWB + ' .addpackagecodemodal-removebtn:not(".disabled")')
	.on('click', contentWB + ' .addpackagecodemodal-removebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var rowid = [];
		var waybillnumber = $(contentWB + ' #pgtxnwaybill-id').attr('pgtxnwaybill-number');
		var button = $(this);
		button.addClass('disabled');

		$(modal + ' .wbpackagecodecheckbox:checked').each(function () {
			rowid.push($(this).attr('rowid'));
		});

		$.post(server + 'waybill.php', { deletePackageCodes: '#@1F4fdgrw$ghjt3K@3#4hh$9v7&3s', rowid: rowid, waybillnumber: waybillnumber }, function (data) {
			if (data.trim() == 'success') {
				button.removeClass('disabled');
				$(contentWB + ' #waybill-packagecodemodaltbl')
					.DataTable()
					.ajax.url('loadables/ajax/transactions.waybill-package-codes.php?waybill=' + waybillnumber)
					.search('')
					.load();
				$(modal + ' .addpackagecodemodal-code')
					.val('')
					.focus();
			} else {
				alert(data);
				button.removeClass('disabled');
			}
		});
	});

/*************** PACKAGE CODE - END ***********************/

function getWBShipperPODInstruction() {
	var shipperid = $(contentWB + ' .waybill-shipper-systemid').val();
	$.post(server + 'waybill.php', { getShipperPODInstruction: '#@1F4fdgrw$ghjt3K@3#4hh$9v7&3s', shipperid: shipperid }, function (data) {
		data = $.parseJSON(data);
		$(contentWB + ' .waybill-shipper-podinstruction').val(data['instruction']);
	});
}

/**************** UPLOADING WAYBILL TRANSACTION **********************/
$(document)
	.off('click', contentWB + ' #waybill-trans-uploadbtn')
	.on('click', contentWB + ' #waybill-trans-uploadbtn', function () {
		var modal = '#waybill-uploadtransactionfilemodal';
		$(modal).modal('show');
	});

/*$(document).off('click',contentWB+' #waybill-downloadtransactionfiletemplatebtn').on('click',contentpane+' #waybill-downloadtransactionfiletemplatebtn',function(){
	            window.open("printouts/excel/download-joborder-upload-template.php");
});*/

$(document)
	.off('click', contentWB + ' #waybill-uploadtransactionfilemodal-uploadbtn:not(".disabled")')
	.on('click', contentWB + ' #waybill-uploadtransactionfilemodal-uploadbtn:not(".disabled")', function () {
		var modal = '#waybill-uploadtransactionfilemodal';
		var modal2 = '#waybill-uploadtransactionlogmodal';
		var form = '#waybill-uploadtransactionfileform';
		var button = $(this);
		button.addClass('disabled');

		if (
			$(contentWB + ' ' + modal + ' .waybilluploadtransactionfile')
				.val()
				.trim() == ''
		) {
			say('Please select a file to upload');
			button.removeClass('disabled');
		} else {
			$('#loading-img').removeClass('hidden');

			$(modal).modal('hide');
			$(document)
				.off('hidden.bs.modal', contentWB + ' ' + modal)
				.on('hidden.bs.modal', contentWB + ' ' + modal, function () {
					$(document).off('hidden.bs.modal', contentWB + ' ' + modal);
					$(contentWB + ' ' + modal2).modal('show');
					$(form).submit();

					$('#waybilluploadtransactionlogframe').load(function () {
						button.removeClass('disabled');

						$('#loading-img').addClass('hidden');
						getWaybillInformation(currentWaybillTxn);

						$(this)
							.contents()
							.find('#touploadsuccessbtn')
							.off('click')
							.on('click', function () {
								$(this).contents().find('#touploadsuccessbtn').off('click');

								/*var to = $(this).attr('tonumber');
					$('#transfer-order-touploadlog-modal').modal('hide');
					$(document).off('hidden.bs.modal','#transfer-order-touploadlog-modal').on('hidden.bs.modal','#transfer-order-touploadlog-modal',function(){
						$(document).off('hidden.bs.modal','#transfer-order-touploadlog-modal');
						getToDetails(to);
					});*/
							});
					});
				});
		}
	});

/*$(this).contents().find('.downloadwaybillfiletemplate').off('click').on('click',function(){


});*/

/***************** UPLOADING - END **********************************/

/************************* TOGGLE BILLING FLAG BTN ***********************/
$(document)
	.off('change', contentWB + ' .togglebillingflagmodal-billingflag')
	.on('change', contentWB + ' .togglebillingflagmodal-billingflag', function () {
		var billed = $(this).val();
		var modal = '#' + $(this).closest('.modal').attr('id');

		if (billed == 1) {
			$(modal + ' .billingreferencewrapper').removeClass('hidden');
		} else {
			$(modal + ' .billingreferencewrapper').addClass('hidden');
		}
	});
$(document)
	.off('click', contentWB + ' #waybill-trans-billingtogglebtn:not(".disabled")')
	.on('click', contentWB + ' #waybill-trans-billingtogglebtn:not(".disabled")', function () {
		var modal = '#togglebillingflagmodal';
		var billingflag = $(contentWB + ' .billingflagdiv').attr('billingflag');
		var billingreference = $(contentWB + ' .waybill-billingreference').val();
		var txnid = $(contentWB + ' #pgtxnwaybill-id').val();
		var txnnumber = $(contentWB + ' #pgtxnwaybill-id').attr('pgtxnwaybill-number');

		$(contentWB + ' .togglebillingflagmodal-billingreference').val(billingreference);
		$(contentWB + ' #togglebillingflagmodal-id').val(txnid);
		$(contentWB + ' .togglebillingflagmodal-txnnumber').val(txnnumber);
		$(contentWB + ' .togglebillingflagmodal-billingflag')
			.val(billingflag)
			.trigger('change');

		$(modal).modal('show');
		$(document)
			.off('shown.bs.modal', modal)
			.on('shown.bs.modal', modal, function () {
				$(document).off('shown.bs.modal', modal);
				$(contentWB + ' .togglebillingflagmodal-billingreference').focus();
			});
	});

$(document)
	.off('click', contentWB + ' #togglebillingflagmodal-savebtn:not(".disabled")')
	.on('click', contentWB + ' #togglebillingflagmodal-savebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var txnid = $(contentWB + ' #pgtxnwaybill-id').val();
		var txnnumber = $(contentWB + ' #pgtxnwaybill-id').attr('pgtxnwaybill-number');
		var remarks = $(modal + ' .togglebillingflagmodal-remarks').val();
		var billingflag = $(modal + ' .togglebillingflagmodal-billingflag').val();
		var billingreference = '';

		var button = $(this);
		button.addClass('disabled');

		if (billingflag == 1) {
			billingreference = $(modal + ' .togglebillingflagmodal-billingreference').val();
		}

		$(modal + ' .modal-errordiv').empty();

		if (billingflag == 1 && billingreference.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide billing reference.</div></div>");
			$(modal + ' .togglebillingflagmodal-billingreference').focus();
			button.removeClass('disabled');
		} else if (remarks.trim() == '') {
			$(modal + ' .modal-errordiv').html(
				"<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide reason for changing billing flag.</div></div>"
			);
			$(modal + ' .togglebillingflagmodal-remarks').focus();
			button.removeClass('disabled');
		} else {
			$.confirm({
				animation: 'bottom',
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce: 1,
				title: 'Change Billing Flag for [' + txnnumber + ']',
				content: 'Do you want to continue?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',
				confirmButtonClass: 'btn-oceanblue',
				cancelButtonClass: 'btn-royalblue',
				theme: 'white',

				confirm: function () {
					$('#loading-img').removeClass('hidden');
					$.post(
						server + 'waybill.php',
						{
							changeBillingFlagging: 'dROi$nsFpo94dnels$4sRoi809srbmouS@1!',
							txnid: txnid,
							txnnumber: txnnumber,
							remarks: remarks,
							billingflag: billingflag,
							billingreference: billingreference
						},
						function (data) {
							if (data.trim() == 'success') {
								$(modal).modal('hide');
								$(document)
									.off('hidden.bs.modal', modal)
									.on('hidden.bs.modal', modal, function () {
										$(document).off('hidden.bs.modal', modal);

										$(modal + ' #togglebillingflagmodal-id').val('');
										$(modal + ' .togglebillingflagmodal-txnnumber').val('');
										$(modal + ' .togglebillingflagmodal-billingflag')
											.val(0)
											.trigger('change');
										$(modal + ' .togglebillingflagmodal-billingreference').val('');
										$(modal + ' .togglebillingflagmodal-remarks').val('');

										getWaybillInformation(txnnumber);
										button.removeClass('disabled');
										$('#loading-img').addClass('hidden');
									});
							} else if (data.trim() == 'invalidtransaction') {
								say('Unable to change Billing Flag. Invalid Waybill ID/No. ID: ' + txnid + ' - Waybill No.: ' + txnnumber);
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							} else if (data.trim() == 'noreference') {
								say('Unable to change Billing Flag. Please provide billing reference.');
								$(modal + ' .togglebillingflagmodal-billingreference').focus();
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							} else {
								alert(data);
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							}
						}
					);
				},
				cancel: function () {
					button.removeClass('disabled');
				}
			});
		}
	});

/********************** TOGGLE BILLING FLAG BTN - END ********************/

$(document)
	.off('change', contentWB + ' .waybill-updatestatusmodal-status')
	.on('change', contentWB + ' .waybill-updatestatusmodal-status', function () {
		var status = $(this).val();
		var modal = '#' + $(this).closest('.modal').attr('id');

		if (
			status == 'DEL' ||
			status == 'DELIVERED' ||
			status == 'del' ||
			status == 'delivered' ||
			status == 'RTS' ||
			status == 'RETURN TO SHIPPER' ||
			status == 'rts' ||
			status == 'return to shipper'
		) {
			$(modal + ' .receivedbywrapper, ' + modal + ' .receiveddatewrapper, ' + modal + ' .personnelwrapper').removeClass('hidden');
		} else {
			$(modal + ' .receivedbywrapper, ' + modal + ' .receiveddatewrapper, ' + modal + ' .personnelwrapper').addClass('hidden');
		}
	});

$(document)
	.off('click', contentWB + ' .deletewaybillstatushistorybtn:not(".disabled")')
	.on('click', contentWB + ' .deletewaybillstatushistorybtn:not(".disabled")', function () {
		var rowid = $(this).attr('rowid');
		var status = $(this).attr('status');
		var button = $(this);
		button.addClass('disabled');

		var modal = '#deletewaybillstatushistorymodal';

		$(modal + ' .deletewaybillstatushistorymodal-status').val(status);
		$(modal + ' #szoiFROkslsfrohvrxpojdmslrp').val(rowid);

		$(modal).modal('show');

		$(document)
			.off('shown.bs.modal', modal)
			.on('shown.bs.modal', modal, function () {
				$(document).off('shown.bs.modal', modal);
				$(modal + ' .deletewaybillstatushistorymodal-remarks').focus();
				button.removeClass('disabled');
			});
	});

$(document)
	.off('click', contentWB + ' #deletewaybillstatushistorymodal-deletebtn:not(".disabled")')
	.on('click', contentWB + ' #deletewaybillstatushistorymodal-deletebtn:not(".disabled")', function () {
		var button = $(this);
		button.addClass('disabled');

		var modal = '#' + $(this).closest('.modal').attr('id');
		var rowid = $(modal + ' #szoiFROkslsfrohvrxpojdmslrp').val();
		var remarks = $(modal + ' .deletewaybillstatushistorymodal-remarks').val();
		var waybillnumber = $(contentWB + ' #pgtxnwaybill-id').attr('pgtxnwaybill-number');

		if (remarks.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a reason.</div></div>");
			$(modal + ' .deletewaybillstatushistorymodal-remarks').focus();
			button.removeClass('disabled');
		} else {
			$.confirm({
				animation: 'bottom',
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce: 1,
				title: 'Confirmation',
				content: 'Delete waybill status history. Do you want to continue?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',
				confirmButtonClass: 'btn-oceanblue',
				cancelButtonClass: 'btn-royalblue',
				theme: 'white',

				confirm: function () {
					$('#loading-img').removeClass('hidden');
					$.post(server + 'waybill.php', { deleteWaybillStatusHistory: 'dROi$nsFpo94dnels$4sRoi809srbmouS@1!', rowid: rowid, remarks: remarks }, function (data) {
						if (data.trim() == 'success') {
							$(modal).modal('hide');
							$(document)
								.off('hidden.bs.modal', modal)
								.on('hidden.bs.modal', modal, function () {
									$(document).off('hidden.bs.modal', modal);

									$(modal + ' #szoiFROkslsfrohvrxpojdmslrp').val('');
									$(modal + ' .deletewaybillstatushistorymodal-remarks').val('');
									$(modal + ' .deletewaybillstatushistorymodal-status').val('');

									$(contentWB + ' #waybill-statushistorytbl')
										.flexOptions({
											url: 'loadables/ajax/transactions.waybill.status-history.php?waybillnumber=' + waybillnumber,
											sortname: 'created_date',
											sortorder: 'desc'
										})
										.flexReload();

									button.removeClass('disabled');
									$('#loading-img').addClass('hidden');
								});
						} else if (data.trim() == 'noreasonprovided') {
							$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a reason.</div></div>");
							$(modal + ' .deletewaybillstatushistorymodal-remarks').focus();
							button.removeClass('disabled');
							$('#loading-img').addClass('hidden');
						} else if (data.trim() == 'nopermission') {
							$(modal).modal('hide');
							$(document)
								.off('hidden.bs.modal', modal)
								.on('hidden.bs.modal', modal, function () {
									$(document).off('hidden.bs.modal', modal);

									$(modal + ' #szoiFROkslsfrohvrxpojdmslrp').val('');
									$(modal + ' .deletewaybillstatushistorymodal-remarks').val('');
									$(modal + ' .deletewaybillstatushistorymodal-status').val('');

									$(contentWB + ' #waybill-statushistorytbl')
										.flexOptions({
											url: 'loadables/ajax/transactions.waybill.status-history.php?waybillnumber=' + waybillnumber,
											sortname: 'created_date',
											sortorder: 'desc'
										})
										.flexReload();

									button.removeClass('disabled');
									$('#loading-img').addClass('hidden');

									say('Unable to delete status history. No permission granted.');
								});
						} else if (data.trim() == 'invalidrowid') {
							$(modal).modal('hide');
							$(document)
								.off('hidden.bs.modal', modal)
								.on('hidden.bs.modal', modal, function () {
									$(document).off('hidden.bs.modal', modal);

									$(modal + ' #szoiFROkslsfrohvrxpojdmslrp').val('');
									$(modal + ' .deletewaybillstatushistorymodal-remarks').val('');
									$(modal + ' .deletewaybillstatushistorymodal-status').val('');

									$(contentWB + ' #waybill-statushistorytbl')
										.flexOptions({
											url: 'loadables/ajax/transactions.waybill.status-history.php?waybillnumber=' + waybillnumber,
											sortname: 'created_date',
											sortorder: 'desc'
										})
										.flexReload();

									button.removeClass('disabled');
									$('#loading-img').addClass('hidden');

									say('Invalid system row ID [' + rowid + ']. Please try again.');
								});
						} else if (data.trim() == 'lateststatus') {
							$(modal).modal('hide');
							$(document)
								.off('hidden.bs.modal', modal)
								.on('hidden.bs.modal', modal, function () {
									$(document).off('hidden.bs.modal', modal);

									$(modal + ' #szoiFROkslsfrohvrxpojdmslrp').val('');
									$(modal + ' .deletewaybillstatushistorymodal-remarks').val('');
									$(modal + ' .deletewaybillstatushistorymodal-status').val('');

									$(contentWB + ' #waybill-statushistorytbl')
										.flexOptions({
											url: 'loadables/ajax/transactions.waybill.status-history.php?waybillnumber=' + waybillnumber,
											sortname: 'created_date',
											sortorder: 'desc'
										})
										.flexReload();

									button.removeClass('disabled');
									$('#loading-img').addClass('hidden');

									say('Unable to delete history of the current waybill status.');
								});
						} else {
							alert(data);
							button.removeClass('disabled');
							$('#loading-img').addClass('hidden');
						}
					});
				},
				cancel: function () {
					button.removeClass('disabled');
				}
			});
		}
	});

$(document)
	.off('click', contentWB + ' #waybill-trans-editwaybillchargesbtn:not(".disabled")')
	.on('click', contentWB + ' #waybill-trans-editwaybillchargesbtn:not(".disabled")', function () {
		var modal = '#editwaybillchargesmodal';
		var waybillnumber = $(contentWB + ' #pgtxnwaybill-id').attr('pgtxnwaybill-number');
		var decimalplaces = 2;

		$(modal + ' .editwaybillchargesmodal-targetwaybill').html(waybillnumber);

		$.post(server + 'waybill.php', { getWaybillData: 'F#@!3R3ksk#Op1NEi34smo1sonk&$', txnnumber: waybillnumber }, function (response) {
			if (response.trim() == 'INVALID') {
				say('Unable to edit waybill charges. Invalid Waybill: ' + waybillnumber);
			} else {
				$(modal).modal('show');

				data = $.parseJSON(response);

				$(contentWB + ' .editwaybillchargesmodal-freightcomputation').val(data['freightcomputation']);
				$(contentWB + ' .editwaybillchargesmodal-chargeableweight').val(data['chargeableweight']);
				$(contentWB + ' .editwaybillchargesmodal-freight').val(data['freight']);
				$(contentWB + ' .editwaybillchargesmodal-valuation').val(data['valuation']);
				$(contentWB + ' .editwaybillchargesmodal-insurancerate').val(data['insurancerate']);
				$(contentWB + ' .editwaybillchargesmodal-fuelrate').val(data['fuelrate']);
				$(contentWB + ' .editwaybillchargesmodal-bunkerrate').val(data['bunkerrate']);
				$(contentWB + ' .editwaybillchargesmodal-minimumrate').val(data['minimumrate']);
				$(contentWB + ' .editwaybillchargesmodal-regularcharges').val(data['totalregularcharges']);
				$(contentWB + ' .editwaybillchargesmodal-otherchargesvatable').val(data['totalotherchargesvatable']);
				$(contentWB + ' .editwaybillchargesmodal-otherchargesnonvatable').val(data['totalotherchargesnonvatable']);
				$(contentWB + ' .editwaybillchargesmodal-subtotal').val(data['subtotal']);
				$(contentWB + ' .editwaybillchargesmodal-vat').val(data['vat']);
				$(contentWB + ' .editwaybillchargesmodal-totalamount').val(data['totalamount']);
				$(contentWB + ' .editwaybillchargesmodal-returndocumentfee').val(data['returndocumentfee']);
				$(contentWB + ' .editwaybillchargesmodal-waybillfee').val(data['waybillfee']);
				$(contentWB + ' .editwaybillchargesmodal-securityfee').val(data['securityfee']);
				$(contentWB + ' .editwaybillchargesmodal-docstampfee').val(data['docstampfee']);
				$(contentWB + ' .editwaybillchargesmodal-oda').val(data['oda']);
				$(contentWB + ' .editwaybillchargesmodal-totalhandlingcharges').val(data['totalhandlingcharges']);
				$(contentWB + ' .editwaybillchargesmodal-fixedrateamount').val(data['fixedrateamount']);

				if (data['waybilltype'] == 'PARCEL') {
					$(contentWB + ' .editwaybillchargesmodal-fixedrateamount')
						.closest('.form-group')
						.removeClass('hidden');
					$(contentWB + ' .editwaybillchargesmodal-totalhandlingcharges')
						.closest('.form-group')
						.removeClass('hidden');
					$(contentWB + ' .editwaybillchargesmodal-insurancerate')
						.closest('.form-group')
						.removeClass('hidden');
				} else {
					$(contentWB + ' .editwaybillchargesmodal-fixedrateamount')
						.val(0)
						.closest('.form-group')
						.addClass('hidden');
					$(contentWB + ' .editwaybillchargesmodal-totalhandlingcharges')
						.val(0)
						.closest('.form-group')
						.addClass('hidden');
					$(contentWB + ' .editwaybillchargesmodal-insurancerate')
						.val(0)
						.closest('.form-group')
						.addClass('hidden');
				}

				if (data['zeroratedflag'] == 'true') {
					$(contentWB + ' .editwaybillchargesmodal-zeroratedcheckbox').prop('checked', true);
				} else {
					$(contentWB + ' .editwaybillchargesmodal-zeroratedcheckbox').prop('checked', false);
				}

				$.post(server + 'waybill.php', { WaybillOtherChargesGetInfo: 'kjoI$H2oiaah3h0$09jDppo92po@k@', txnnumber: waybillnumber }, function (dt) {
					rsp1 = $.parseJSON(dt);
					$(contentWB + ' #editwaybillchargesmodal-otherchargestbl')
						.DataTable()
						.search('')
						.clear()
						.draw();
					for (var i = 0; i < rsp1.length; i++) {
						$(contentWB + ' #editwaybillchargesmodal-otherchargestbl')
							.DataTable()
							.row.add([
								"<input type='checkbox' class='rowcheckbox'>",
								"<span class='editwaybillchargesmodal-othercharges-description'>" + rsp1[i]['description'] + '</span>',
								"<span class='editwaybillchargesmodal-othercharges-amount pull-right'>" + rsp1[i]['amount'] + '</span>',
								"<span class='editwaybillchargesmodal-othercharges-vatable tblotherchargesvatflag'>" + rsp1[i]['vatflag'] + '</span>'
							])
							.draw();
					}
				});

				$.post(server + 'waybill.php', { getHandlingInstructions: 'sdfed#n2L1hfi$n#opi3opod30napri', txnnumber: waybillnumber }, function (data) {
					data = $.parseJSON(data);
					var instructions = data['instructions'].split('#@$');

					$(contentWB + ' .editwaybillchargesmodal-handlinginstruction').empty();
					for (var i = 0; i < instructions.length; i++) {
						var otherwhse1 = instructions[i];
						otherwhse1 = otherwhse1.split('%$&');
						if (otherwhse1[1] != null) {
							$(contentWB + ' .editwaybillchargesmodal-handlinginstruction')
								.append('<option selected value="' + otherwhse1[0] + '">' + otherwhse1[1] + '</option>')
								.trigger('change');
						} else {
							$(contentWB + ' .editwaybillchargesmodal-handlinginstruction')
								.empty()
								.trigger('change');
						}
					}
				});
			}
		});
	});

////// INSERT RATE ROW - EDIT WAYBILL CHARGES
function editWaybillChargesOtherChargesInsertRow(table) {
	var description = $(contentWB + ' .editwaybillchargesmodal-otherchargesdesc').val(),
		amount = $(contentWB + ' .editwaybillchargesmodal-otherchargesamount').val(),
		vatable = $(contentWB + ' .editwaybillchargesmodal-otherchargesvatflag').val();

	if (description.trim() != '' && parseFloat(amount) > 0 && (vatable == 'NO' || vatable == 'YES')) {
		amount = parseFloat(amount);
		amount = amount.toLocaleString();

		table.row
			.add([
				"<input type='checkbox' class='rowcheckbox'>",
				"<span class='editwaybillchargesmodal-othercharges-description'>" + description + '</span>',
				"<span class='editwaybillchargesmodal-othercharges-amount pull-right'>" + amount + '</span>',
				"<span class='editwaybillchargesmodal-othercharges-vatable tblotherchargesvatflag'>" + vatable + '</span>'
			])
			.search('')
			.draw();
		editWaybillChargesOtherChargesClearFields();
	} else if (description.trim() == '') {
		$(contentWB + ' .editwaybillchargesmodal-otherchargesdesc').focus();
	} else if (amount.trim() == '') {
		$(contentWB + ' .oeditwaybillchargesmodal-otherchargesamount').focus();
	}

	computeTotalsEWC();
}

$(document)
	.off('click', contentWB + ' .editwaybillchargesmodal-otherchargesinsertbtn')
	.on('click', contentWB + ' .editwaybillchargesmodal-otherchargesinsertbtn', function () {
		editWaybillChargesOtherChargesInsertRow($(contentWB + ' #editwaybillchargesmodal-otherchargestbl').DataTable());
	});
////// INSERT RATE ROW END - EDIT WAYBILL CHARGES

////// REMOVE RATE ROW - EDIT WAYBILL CHARGES
function editWaybillChargesOtherChargesRemoveRow(table) {
	$(table + ' tbody tr .rowcheckbox:checked').each(function () {
		var tr = $(this).closest('tr');
		$(table).DataTable().row(tr).remove().draw();
	});

	computeTotalsEWC();
}

$(document)
	.off('click', contentWB + ' .editwaybillchargesmodal-otherchargesremovebtn')
	.on('click', contentWB + ' .editwaybillchargesmodal-otherchargesremovebtn', function () {
		var tbl = '#editwaybillchargesmodal-otherchargestbl';
		editWaybillChargesOtherChargesRemoveRow(tbl);
	});
/////// REMOVE RATE ROW END - EDIT WAYBILL CHARGES

////// CLEAR RATE INPUT FIELDS - EDIT WAYBILL CHARGES
function editWaybillChargesOtherChargesClearFields() {
	$(contentWB + ' .editwaybillchargesmodal-otherchargesamount')
		.val('')
		.focus();
	$(contentWB + ' .editwaybillchargesmodal-otherchargesdesc')
		.empty()
		.trigger('change');
}

$(document)
	.off('click', contentWB + ' .editwaybillchargesmodal-otherchargesclearbtn')
	.on('click', contentWB + ' .editwaybillchargesmodal-otherchargesclearbtn', function () {
		editWaybillChargesOtherChargesClearFields();
	});
////// CLEAR RATE INPUT FIELDS END - EDIT WAYBILL CHARGES

$(document)
	.off('keyup', '#editwaybillchargesmodal .ratefld')
	.on('keyup', '#editwaybillchargesmodal .ratefld', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');

		var val = parseFloat($(this).val());
		if (val > 0) {
			$(modal + ' .editwaybillchargesmodal-fixedrateamount').val(0);
		}

		computeTotalsEWC();
	});

$(document)
	.off('keyup', '#editwaybillchargesmodal .editwaybillchargesmodal-fixedrateamount')
	.on('keyup', '#editwaybillchargesmodal .editwaybillchargesmodal-fixedrateamount', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var fixedrateamount = parseFloat($(this).val());

		if (fixedrateamount > 0) {
			$(modal + ' .editwaybillchargesmodal-returndocumentfee').val(0);
			$(modal + ' .editwaybillchargesmodal-waybillfee').val(0);
			$(modal + ' .editwaybillchargesmodal-securityfee').val(0);
			$(modal + ' .editwaybillchargesmodal-docstampfee').val(0);
			$(modal + ' .editwaybillchargesmodal-oda').val(0);
			$(modal + ' .editwaybillchargesmodal-valuation').val(0);
			$(modal + ' .editwaybillchargesmodal-freight').val(0);
			$(modal + ' .editwaybillchargesmodal-insurancerate').val(0);
			$(modal + ' .editwaybillchargesmodal-fuelrate').val(0);
			$(modal + ' .editwaybillchargesmodal-bunkerrate').val(0);
			$(modal + ' .editwaybillchargesmodal-totalhandlingcharges').val(0);
		}

		computeTotalsEWC();
	});

$(document)
	.off('click', '#editwaybillchargesmodal #editwaybillchargesmodal-savebtn:not(".disabled")')
	.on('click', '#editwaybillchargesmodal #editwaybillchargesmodal-savebtn:not(".disabled")', function () {
		computeTotalsEWC();
		var waybillnumber = $(contentWB + ' #pgtxnwaybill-id').attr('pgtxnwaybill-number');
		var modal = '#' + $(this).closest('.modal').attr('id');
		var button = $(this);
		button.addClass('disabled');

		var chargeableweight = parseFloat($(modal + ' .editwaybillchargesmodal-chargeableweight').val());
		var fixedrateamount = parseFloat($(modal + ' .editwaybillchargesmodal-fixedrateamount').val());
		var fixedrateamount = fixedrateamount >= 0 ? fixedrateamount : 0;
		var returndocumentfee = parseFloat($(modal + ' .editwaybillchargesmodal-returndocumentfee').val());
		var waybillfee = parseFloat($(modal + ' .editwaybillchargesmodal-waybillfee').val());
		var securityfee = parseFloat($(modal + ' .editwaybillchargesmodal-securityfee').val());
		var docstampfee = parseFloat($(modal + ' .editwaybillchargesmodal-docstampfee').val());
		var oda = parseFloat($(modal + ' .editwaybillchargesmodal-oda').val());
		var valuation = parseFloat($(modal + ' .editwaybillchargesmodal-valuation').val());
		var freight = parseFloat($(modal + ' .editwaybillchargesmodal-freight').val());
		var insurancerate = parseFloat($(modal + ' .editwaybillchargesmodal-insurancerate').val());
		var fuelrate = parseFloat($(modal + ' .editwaybillchargesmodal-fuelrate').val());
		var bunkerrate = parseFloat($(modal + ' .editwaybillchargesmodal-bunkerrate').val());
		var totalhandlingcharges = parseFloat($(modal + ' .editwaybillchargesmodal-totalhandlingcharges').val());
		var zeroratedflag = $(contentWB + ' .editwaybillchargesmodal-zeroratedcheckbox').prop('checked');
		var handlinginstruction = $(contentWB + ' .editwaybillchargesmodal-handlinginstruction').val();

		var linedesc = [];
		var lineamount = [];
		var linevatflag = [];

		var vat = $(contentWB + ' .editwaybillchargesmodal-vat').val();
		var totalamount = $(contentWB + ' .editwaybillchargesmodal-totalamount').val();
		var subtotal = $(contentWB + ' .editwaybillchargesmodal-subtotal').val();
		var totalregularcharges = $(contentWB + ' .editwaybillchargesmodal-regularcharges').val();
		var totalotherchargesvatable = $(contentWB + ' .editwaybillchargesmodal-otherchargesvatable').val();
		var totalotherchargesnonvatable = $(contentWB + ' .editwaybillchargesmodal-otherchargesnonvatable').val();

		if (returndocumentfee < 0 || returndocumentfee == null || returndocumentfee == undefined || isNaN(returndocumentfee) == 1) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide return document fee.</div></div>");
			$(contentWB + ' .editwaybillchargesmodal-returndocumentfee').focus();
			button.removeClass('disabled');
		} else if (waybillfee < 0 || waybillfee == null || waybillfee == undefined || isNaN(waybillfee) == 1) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide waybill fee.</div></div>");
			$(contentWB + ' .editwaybillchargesmodal-waybillfee').focus();
			button.removeClass('disabled');
		} else if (securityfee < 0 || securityfee == null || securityfee == undefined || isNaN(securityfee) == 1) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide waybill fee.</div></div>");
			$(contentWB + ' .editwaybillchargesmodal-securityfee').focus();
			button.removeClass('disabled');
		} else if (docstampfee < 0 || docstampfee == null || docstampfee == undefined || isNaN(docstampfee) == 1) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide doc stamp fee.</div></div>");
			$(contentWB + ' .editwaybillchargesmodal-docstampfee').focus();
			//$('.content').animate({scrollTop:0},500);
			button.removeClass('disabled');
		} else if (oda < 0 || oda == null || oda == undefined || isNaN(oda) == 1) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide oda charges.</div></div>");
			$(contentWB + ' .editwaybillchargesmodal-oda').focus();
			button.removeClass('disabled');
		} else if (valuation < 0 || valuation == null || valuation == undefined || isNaN(valuation) == 1) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide valuation.</div></div>");
			$(contentWB + ' .editwaybillchargesmodal-valuation').focus();
			button.removeClass('disabled');
		} else if (freight < 0 || freight == null || freight == undefined || isNaN(freight) == 1) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide freight.</div></div>");
			$(contentWB + ' .editwaybillchargesmodal-freight').focus();
			button.removeClass('disabled');
		} else if (insurancerate < 0 || insurancerate == null || insurancerate == undefined || isNaN(insurancerate) == 1) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide insurance charges.</div></div>");
			$(contentWB + ' .editwaybillchargesmodal-insurancerate').focus();
			button.removeClass('disabled');
		} else if (fuelrate < 0 || fuelrate == null || fuelrate == undefined || isNaN(fuelrate) == 1) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide fuel charges.</div></div>");
			$(contentWB + ' .editwaybillchargesmodal-fuelrate').focus();
			button.removeClass('disabled');
		} else if (bunkerrate < 0 || bunkerrate == null || bunkerrate == undefined || isNaN(bunkerrate) == 1) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide bunker charges.</div></div>");
			$(contentWB + ' .editwaybillchargesmodal-bunkerrate').focus();
			button.removeClass('disabled');
		} else if (totalhandlingcharges < 0 || totalhandlingcharges == null || totalhandlingcharges == undefined || isNaN(totalhandlingcharges) == 1) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide total handling charges.</div></div>");
			$(contentWB + ' .editwaybillchargesmodal-totalhandlingcharges').focus();
			button.removeClass('disabled');
		} else {
			$(contentWB + ' #editwaybillchargesmodal-otherchargestbl tbody tr.editwaybillchargesmodal-otherchargesrow').each(function () {
				linedesc.push($(this).find('.editwaybillchargesmodal-othercharges-description').text());
				lineamount.push($(this).find('.editwaybillchargesmodal-othercharges-amount').text());
				linevatflag.push($(this).find('.editwaybillchargesmodal-othercharges-vatable').text());
			});

			$.post(
				server + 'waybill.php',
				{
					ConfirmEditedCharges: 'D#@ihQnsRPFG%$po92po@k@',
					waybillnumber: waybillnumber,
					returndocumentfee: returndocumentfee,
					waybillfee: waybillfee,
					securityfee: securityfee,
					docstampfee: docstampfee,
					oda: oda,
					valuation: valuation,
					freight: freight,
					insurancerate: insurancerate,
					fuelrate: fuelrate,
					bunkerrate: bunkerrate,
					totalhandlingcharges: totalhandlingcharges,
					zeroratedflag: zeroratedflag,
					handlinginstruction: handlinginstruction,
					linedesc: linedesc,
					lineamount: lineamount,
					linevatflag: linevatflag,
					totalregularcharges: totalregularcharges,
					totalotherchargesvatable: totalotherchargesvatable,
					subtotal: subtotal,
					vat: vat,
					totalotherchargesnonvatable: totalotherchargesnonvatable,
					totalamount: totalamount,
					fixedrateamount: fixedrateamount,
					chargeableweight: chargeableweight
				},
				function (data) {
					rsp = $.parseJSON(data);

					if (rsp['response'] == 'success') {
						$(modal).modal('hide');
						$(modal).on('hidden.bs.modal', function () {
							$(modal + ' .modal-errordiv').empty();
							getWaybillInformation(waybillnumber);

							button.removeClass('disabled');
							$(modal).off('hidden.bs.modal');
						});
					} else if (rsp['response'] == 'exceedscreditlimit') {
						say('Unable to save waybill charges. Will exceed credit limit.<br>Credit Available: ' + rsp['creditlimitavailable']);
						button.removeClass('disabled');
						$(modal + ' .modal-errordiv').empty();
					} else if (rsp['response'] == 'nopermission') {
						$(modal).modal('hide');
						$(modal).on('hidden.bs.modal', function () {
							$(modal + ' .modal-errordiv').empty();
							getWaybillInformation(waybillnumber);

							say('Unable to edit waybill charges. No user permission.');

							button.removeClass('disabled');
							$(modal).off('hidden.bs.modal');
						});
					} else if (rsp['response'] == 'inactivebilling') {
						$(modal).modal('hide');
						$(modal).on('hidden.bs.modal', function () {
							$(modal + ' .modal-errordiv').empty();
							getWaybillInformation(waybillnumber);

							say('Unable to edit waybill charges. Waybill is in active billing transaction [' + rsp['billingnumber'] + ']');

							button.removeClass('disabled');
							$(modal).off('hidden.bs.modal');
						});
					} else {
						alert(data);
						button.removeClass('disabled');
						$(modal + ' .modal-errordiv').empty();
					}
				}
			);
		}
	});

$(document)
	.off('click', contentWB + ' #waybill-trans-resetprintcounterbtn:not(".disabled")')
	.on('click', contentWB + ' #waybill-trans-resetprintcounterbtn:not(".disabled")', function () {
		var modal = '#resetprintcountermodal';
		var waybillnumber = $(contentWB + ' #pgtxnwaybill-id').attr('pgtxnwaybill-number');
		var btn = $(this);
		btn.addClass('disabled');

		$(modal + ' .resetprintcountermodal-txnnumber').val(waybillnumber);

		$(modal).modal('show');
		$(document)
			.off('shown.bs.modal', modal)
			.on('shown.bs.modal', modal, function () {
				$(document).off('shown.bs.modal', modal);
				btn.removeClass('disabled');
				$(modal + ' .resetprintcountermodal-remarks').focus();
			});
	});

$(document)
	.off('click', contentWB + ' #resetprintcountermodal-savebtn:not(".disabled")')
	.on('click', contentWB + ' #resetprintcountermodal-savebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var btn = $(this);
		btn.addClass('disabled');
		var remarks = $(modal + ' .resetprintcountermodal-remarks')
			.val()
			.trim();
		var waybillnumber = $(modal + ' .resetprintcountermodal-txnnumber').val();

		if (remarks == '') {
			say('Please provide a reason for print counter reset');
			btn.removeClass('disabled');
		} else {
			$.post(server + 'waybill.php', { resetPrintCounter: 'sdfed#n2L1hfi$n#opi3opod30napri', waybillnumber: waybillnumber, remarks: remarks }, function (data) {
				if (data.trim() == 'success') {
					$(modal).modal('hide');
					$(document)
						.off('hidden.bs.modal', modal)
						.on('hidden.bs.modal', modal, function () {
							$(document).off('hidden.bs.modal', modal);
							btn.removeClass('disabled');
							getWaybillInformation(waybillnumber);
							$(modal + ' .resetprintcountermodal-remarks').val('');
						});
				} else if (data.trim() == 'invalidwaybill') {
					$(modal).modal('hide');
					$(document)
						.off('hidden.bs.modal', modal)
						.on('hidden.bs.modal', modal, function () {
							$(document).off('hidden.bs.modal', modal);
							btn.removeClass('disabled');
							getWaybillInformation(waybillnumber);
							say('Unable to reset counter. Invalid Waybill Number: ' + waybillnumber);
						});
				} else if (data.trim() == 'noaccess') {
					$(modal).modal('hide');
					$(document)
						.off('hidden.bs.modal', modal)
						.on('hidden.bs.modal', modal, function () {
							$(document).off('hidden.bs.modal', modal);
							btn.removeClass('disabled');
							getWaybillInformation(waybillnumber);
							say('Unable to reset counter. No user permission.');
						});
				} else {
					alert(data);
					btn.removeClass('disabled');
				}
			});
		}
	});

$(document)
	.off('click', contentWB + ' #waybill-packagedimensionsbtn:not(".disabled")')
	.on('click', contentWB + ' #waybill-packagedimensionsbtn:not(".disabled")', function () {
		var mode = $(contentWB + ' .waybill-modeoftransport').val();

		if (mode == '' || mode == null || mode == 'NULL' || mode == 'null' || mode == undefined) {
			say('No mode of transport selected');
		} else {
			var modal = '#waybill-packagedimensionsmodal';
			$(modal).modal('show');
		}
	});

$(document)
	.off('select2:select', contentWB + ' .waybill-modeoftransport')
	.on('select2:select', contentWB + ' .waybill-modeoftransport', function (e) {
		if (processWB == 'add' || processWB == 'edit') {
			currentdivisor = e.params.data['data-divisor'];
		}
	});

$(document)
	.off('click', contentWB + ' #waybill-trans-editwaybillcostingbtn:not(".disabled")')
	.on('click', contentWB + ' #waybill-trans-editwaybillcostingbtn:not(".disabled")', function () {
		var modal = '#editwaybillcostingmodal';
		var btn = $(this);
		var txnid = $(contentWB + ' #pgtxnwaybill-id').val();
		var txnnumber = $(contentWB + ' #pgtxnwaybill-id').attr('pgtxnwaybill-number');
		$(modal + ' .modal-errordiv').empty();
		$(modal).modal('show');

		$.post(server + 'waybill.php', { getWaybillData: 'F#@!3R3ksk#Op1NEi34smo1sonk&$', txnnumber: txnnumber }, function (response) {
			//alert(response);

			if (response.trim() == 'INVALID') {
				getWaybillInformation(txnnumber);
				say('Unable to load details. Invalid Waybill Number');
				btn.removeClass('disabled');
			} else {
				$(modal).modal('show');
				$(document)
					.off('shown.bs.modal', modal)
					.on('shown.bs.modal', modal, function () {
						$(document).off('shown.bs.modal', modal);
						btn.removeClass('disabled');

						data = $.parseJSON(response);
						$(contentWB + ' .editwaybillcostingmodal-freightcost').val(data['freightcost']);
						$(contentWB + ' .editwaybillcostingmodal-agentcost').val(data['agentcost']);
						$(contentWB + ' .editwaybillcostingmodal-billitemnumber').val(data['billitemnumber']);
						$(contentWB + ' .editwaybillcostingmodal-insuranceamount').val(data['insuranceamount']);
						$(contentWB + ' .editwaybillcostingmodal-insurancereference').val(data['insurancereference']);
						$(contentWB + ' .editwaybillcostingmodal-grossincome').val(data['grossincome']);
						$(contentWB + ' .editwaybillcostingmodal-totalcost').val(data['totalcost']);

						$(contentWB + ' .editwaybillcostingmodal-billreference')
							.val(data['billreference'])
							.focus()
							.select();
					});
			}
		});
	});

$(document)
	.off('click', contentWB + ' #editwaybillcostingmodal-savebtn:not(".disabled")')
	.on('click', contentWB + ' #editwaybillcostingmodal-savebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var btn = $(this);
		var txnid = $(contentWB + ' #pgtxnwaybill-id').val();
		var txnnumber = $(contentWB + ' #pgtxnwaybill-id').attr('pgtxnwaybill-number');
		$(modal + ' .modal-errordiv').empty();

		btn.addClass('disabled');

		var billreference = $(modal + ' .editwaybillcostingmodal-billreference').val(),
			freightcost = $(modal + ' .editwaybillcostingmodal-freightcost').val(),
			agentcost = $(modal + ' .editwaybillcostingmodal-agentcost').val(),
			billitemnumber = $(modal + ' .editwaybillcostingmodal-billitemnumber').val(),
			insuranceamount = $(modal + ' .editwaybillcostingmodal-insuranceamount').val(),
			insurancereference = $(modal + ' .editwaybillcostingmodal-insurancereference').val();

		if (freightcost != '' && freightcost < 0) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid Freight Cost</div></div>");
			$(contentWB + ' .editwaybillcostingmodal-freightcost')
				.focus()
				.select();
			btn.removeClass('disabled');
		} else if (agentcost != '' && agentcost < 0) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid Freight Cost</div></div>");
			$(contentWB + ' .editwaybillcostingmodal-agentcost')
				.focus()
				.select();
			btn.removeClass('disabled');
		} else if (insuranceamount != '' && insuranceamount < 0) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid Freight Cost</div></div>");
			$(contentWB + ' .editwaybillcostingmodal-insuranceamount')
				.focus()
				.select();
			btn.removeClass('disabled');
		} else {
			$.post(
				server + 'waybill.php',
				{
					saveWaybillCostingDetails: 'F#@!3R3ksk#Op1NEi34smo1sonk&$',
					txnnumber: txnnumber,
					txnid: txnid,
					billreference: billreference,
					freightcost: freightcost,
					agentcost: agentcost,
					billitemnumber: billitemnumber,
					insuranceamount: insuranceamount,
					insurancereference: insurancereference
				},
				function (response) {
					rsp = $.parseJSON(response);

					if (rsp['response'] == 'success') {
						$(modal).modal('hide');
						$(modal).on('hidden.bs.modal', function () {
							$(modal + ' .modal-errordiv').empty();
							getWaybillInformation(txnnumber);
							btn.removeClass('disabled');
							$(modal).off('hidden.bs.modal');
						});
					} else if (rsp['response'] == 'nopermission') {
						$(modal).modal('hide');
						$(modal).on('hidden.bs.modal', function () {
							$(modal + ' .modal-errordiv').empty();
							getWaybillInformation(txnnumber);

							say('Unable to edit costing. No user permission.');

							btn.removeClass('disabled');
							$(modal).off('hidden.bs.modal');
						});
					} else if (rsp['response'] == 'invalidtransaction') {
						$(modal).modal('hide');
						$(modal).on('hidden.bs.modal', function () {
							$(modal + ' .modal-errordiv').empty();
							getWaybillInformation(txnnumber);

							say('Unable to edit costing. Invalid Transaction, ID: ' + txnid + ' No.: ' + txnnumber);

							btn.removeClass('disabled');
							$(modal).off('hidden.bs.modal');
						});
					} else {
						alert(data);
						btn.removeClass('disabled');
						$(modal + ' .modal-errordiv').empty();
					}
				}
			);
		}
	});

/************* EDIT DIMENSIONS BTN *********************/
$(document)
	.off('click', contentWB + ' #waybill-trans-editdimensionsbtn:not(".disabled")')
	.on('click', contentWB + ' #waybill-trans-editdimensionsbtn:not(".disabled")', function () {
		var button = $(this);
		button.addClass('disabled');

		var wbnumber = $(contentWB + ' #pgtxnwaybill-id').attr('pgtxnwaybill-number');

		$.post(server + 'waybill.php', { checkWaybillEnableEdit: 'F#@!3R3ksk#Op1NEi34smo1sonk&$', wbnumber: wbnumber }, function (data) {
			rp = $.parseJSON(data);
			if (rp['response'] == 'success') {
				/******* *********************/
				processWB = 'edit';
				$(contentWB + ' .errordiv').empty();

				$(contentWB + ' .firstprevnextlastbtn').addClass('hidden');
				$(contentWB + ' .searchbtn')
					.addClass('disabled')
					.removeClass('active')
					.addClass('hidden');
				$(contentWB + ' .billingflagdiv').addClass('hidden');
				$(contentWB + ' .controllabeliconedit').remove();

				if ($(contentWB + ' .waybill-waybilltype').val() == 'PARCEL') {
					$(contentWB + ' .waybill-numberofpackages')
						.addClass('alwaysdisabled')
						.attr('disabled', 'disabled');
				} else {
					$(contentWB + ' .waybill-numberofpackages')
						.removeClass('alwaysdisabled')
						.removeAttr('disabled');
				}

				$(contentWB + ' .packagedimensionsmodalflds').removeClass('hidden');
				$(contentWB + ' .wbaddpackagecodebtnwrapper').empty();
				$(
					contentWB +
						' .waybill-vwcbm, ' +
						contentWB +
						' .waybill-vw, ' +
						contentWB +
						' .waybill-mawbl, ' +
						contentWB +
						' .waybill-actualweight, ' +
						contentWB +
						' .waybill-pickupdate, ' +
						contentWB +
						' .waybill-remarks'
				).removeAttr('disabled');

				$(contentWB + ' .topbuttonsdiv')
					.empty()
					.html(
						"<div class='padded-with-border-engraved topbuttonswrapper'><div class='button-group'><div class='button-group-btn active' title='Save' id='waybill-trans-savebtn'><img src='../resources/img/save.png'></div><div class='button-group-btn active' title='Cancel' id='waybill-trans-cancelbtn'><img src='../resources/img/cancel.png'></div></div></div>"
					);
				$(contentWB + ' .savecancelbuttonwrapper').html(
					"<div class='padded-with-border-engraved button-bottom'><div class='button-group'><div class='button-group-btn active' id='waybill-trans-savebtn'><img src='../resources/img/save.png'>&nbsp;&nbsp;Save</div><div class='button-group-btn active' id='waybill-trans-cancelbtn'><img src='../resources/img/cancel.png'>&nbsp;&nbsp;Cancel</div></div></div>"
				);
				$(contentWB + ' .inputgroupbtnicon').removeClass('hidden');

				/******* *********************/

				button.removeClass('disabled');
			} else if (rp['response'] == 'inactivemanifesttransaction') {
				say('Unable to edit transaction. Waybill is in active manifest transaction. [' + rp['manifest'] + ']');
				button.removeClass('disabled');
			} else if (rp['response'] == 'inactivebillingtransaction') {
				say('Unable to edit transaction. Waybill is in active billing transaction. [' + rp['billing'] + ']');
				button.removeClass('disabled');
			} else {
				alert(data);
				button.removeClass('disabled');
			}
		});

		/*$(inputfieldsWB+' input:not(".alwaysdisabled"),'+inputfieldsWB+' textarea:not(".alwaysdisabled"),'+inputfieldsWB+' select:not(".alwaysdisabled")').removeAttr('disabled');
	$(contentWB+' .firstprevnextlastbtn').addClass('hidden');
	$(contentWB+' .searchbtn').addClass('disabled').removeClass('active').addClass('hidden');

	$(contentWB+' .billingflagdiv').addClass('hidden');

	if($(contentWB+' .waybill-waybilltype').val()=='PARCEL'){
		$(contentWB+' .waybill-numberofpackages').addClass('alwaysdisabled').attr('disabled','disabled');
	}
	else{
		$(contentWB+' .waybill-numberofpackages').removeClass('alwaysdisabled').removeAttr('disabled');
	}

	$(contentWB+' .waybill-consignee-secondary').removeClass('alwaysdisabled').removeAttr('disabled');

	$(contentWB+' .controllabeliconedit').remove(); 

	$(contentWB+' .topbuttonsdiv').empty().html("<div class='padded-with-border-engraved topbuttonswrapper'><div class='button-group'><div class='button-group-btn active' title='Save' id='waybill-trans-savebtn'><img src='../resources/img/save.png'></div><div class='button-group-btn active' title='Cancel' id='waybill-trans-cancelbtn'><img src='../resources/img/cancel.png'></div></div></div>");
	$(contentWB+' .savecancelbuttonwrapper').html("<div class='padded-with-border-engraved button-bottom'><div class='button-group'><div class='button-group-btn active' id='waybill-trans-savebtn'><img src='../resources/img/save.png'>&nbsp;&nbsp;Save</div><div class='button-group-btn active' id='waybill-trans-cancelbtn'><img src='../resources/img/cancel.png'>&nbsp;&nbsp;Cancel</div></div></div>");
	$(contentWB+' .wbotherchargessectionflds').removeClass('hidden');
	$(contentWB+' .packagedimensionsmodalflds').removeClass('hidden');
	$(contentWB+' .rowcheckbox').removeClass('hidden');
	$(contentWB+' .inputgroupbtnicon').removeClass('hidden');

	$(contentWB+' .wbaddpackagecodebtnwrapper').empty();

	defaultExpressValue($(contentWB+' .waybill-waybilltype').val());*/
	});

/***************************** UPLOAD WAYBILL STATUS UPDATE ********************************************************/
$(document)
	.off('click', contentWB + ' #waybill-trans-uploadwaybillstatusupdatebtn')
	.on('click', contentWB + ' #waybill-trans-uploadwaybillstatusupdatebtn', function () {
		var modal = '#uploadwaybillstatusupdatemodal';
		$(modal).modal('show');
	});

$(document)
	.off('click', contentWB + ' #uploadwaybillstatusupdatemodal-uploadbtn:not(".disabled")')
	.on('click', contentWB + ' #uploadwaybillstatusupdatemodal-uploadbtn:not(".disabled")', function () {
		var modal = '#uploadwaybillstatusupdatemodal';
		var modal2 = '#uploadwaybillstatusupdatelogmodal';
		var form = '#uploadwaybillstatusupdatemodal-form';
		var logframe = '#uploadwaybillstatusupdatelogframe';
		var button = $(this);
		button.addClass('disabled');

		if (
			$(contentWB + ' ' + modal + ' .uploadwaybillstatusupdatemodal-file')
				.val()
				.trim() == ''
		) {
			say('Please select a file to upload');
			button.removeClass('disabled');
		} else {
			$('#loading-img').removeClass('hidden');

			$(modal).modal('hide');
			$(document)
				.off('hidden.bs.modal', contentWB + ' ' + modal)
				.on('hidden.bs.modal', contentWB + ' ' + modal, function () {
					$(document).off('hidden.bs.modal', contentWB + ' ' + modal);
					$(contentWB + ' ' + modal2).modal('show');
					$(form).submit();

					$(logframe).load(function () {
						if (currentWaybillTxn != '') {
							getWaybillInformation(currentWaybillTxn);
						}
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');

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

$(document)
	.off('click', contentWB + ' #waybill-consigneelookup-savebtn:not(".disabled")')
	.on('click', contentWB + ' #waybill-consigneelookup-savebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id'),
			accountname = $(modal + ' .waybill-consigneelookup-accountname').val(),
			companyname = $(modal + ' .waybill-consigneelookup-companyname').val(),
			contact = $(modal + ' .waybill-consigneelookup-contact').val(),
			tel = $(modal + ' .waybill-consigneelookup-telnumber').val(),
			mobile = $(modal + ' .waybill-consigneelookup-mobile').val(),
			region = $(modal + ' .waybill-consigneelookup-region').val(),
			city = $(modal + ' .waybill-consigneelookup-city').val(),
			district = $(modal + ' .waybill-consigneelookup-district').val(),
			zipcode = $(modal + ' .waybill-consigneelookup-zipcode').val(),
			street = $(modal + ' .waybill-consigneelookup-street').val(),
			country = $(modal + ' .waybill-consigneelookup-country').val(),
			idnumber = $(modal + ' .waybill-consigneelookup-idnumber').val(),
			button = $(this);
		button.addClass('disabled');

		if (accountname.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide an account name.</div></div>");
			$(modal + ' .waybill-consigneelookup-accountname').focus();
			button.removeClass('disabled');
		} else if (companyname.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a company name.</div></div>");
			$(modal + ' .waybill-consigneelookup-companyname').focus();
			button.removeClass('disabled');
		} else if (street.trim() == '' || street == null || street == 'null' || street == 'NULL' || street == undefined) {
			/*else if(idnumber.trim()==''){
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide ID number.</div></div>");
		$(modal+' .waybill-consigneelookup-idnumber').focus();
		button.removeClass('disabled');
	}*/
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide street address.</div></div>");
			$(modal + ' .waybill-consigneelookup-street').focus();
			button.removeClass('disabled');
		} else {
			$('#loading-img').removeClass('hidden');

			$.post(
				server + 'waybill.php',
				{
					AddNewConsignee: 'kkjO#@siaah3h0$09odfj$owenxezpo92po@k@',
					idnumber: idnumber,
					accountname: accountname,
					companyname: companyname,
					contact: contact,
					tel: tel,
					mobile: mobile,
					street: street,
					district: district,
					city: city,
					region: region,
					zipcode: zipcode,
					country: country
				},
				function (data) {
					var rsp = data.split('@#$%&');

					if (rsp[0].trim() == 'success') {
						$(contentWB + ' #waybill-consigneelookuptbl')
							.flexOptions({
								url: 'loadables/ajax/transactions.booking.consignee-lookup.php',
								sortname: 'created_date',
								sortorder: 'desc'
							})
							.flexReload();
						clearNewConsigneeWB();
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');

						consigneeLookupRowSelected(rsp[1].trim(), '#waybill-consigneelookup');
					} else if (rsp[0].trim() == 'consigneeexists') {
						say('Consignee with the same account name already exists.');
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					} else if (rsp[0].trim() == 'idnumberexists') {
						say('Consignee with the same ID Number already exists.');
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					} else if (rsp[0].trim() == 'noaccess') {
						say('Unable to add consignee. No user permission.');
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					} else {
						alert(data);
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					}
				}
			);
		}
	});

$(document)
	.off('show.bs.modal', contentWB + ' #waybill-consigneelookup')
	.on('show.bs.modal', contentWB + ' #waybill-consigneelookup', function () {
		var modal = '#' + $(this).attr('id');
		$(contentWB + ' #waybill-consigneelookuptbl')
			.flexOptions({
				url: 'loadables/ajax/transactions.booking.consignee-lookup.php',
				sortname: 'account_name',
				sortorder: 'asc'
			})
			.flexReload();

		$.post(server + 'waybill.php', { checkIfUserCanAddNewConsignee: 'dROi$nsFpo94dnels$4sRoi809srbmouS@1!' }, function (data) {
			if (data.trim() == '1') {
				$(contentWB + ' .waybill-consigneelookup-country')
					.empty()
					.append('<option selected value="Philippines">Philippines</option>')
					.trigger('change');
				$(contentWB + ' .waybill-addnewconsigneewrapper').removeClass('hidden');
			} else if (data.trim() == '0') {
				$(contentWB + ' .waybill-addnewconsigneewrapper').addClass('hidden');
			} else {
				alert(data);
				$(contentWB + ' .waybill-addnewconsigneewrapper').addClass('hidden');
			}
		});
	});

function clearNewConsigneeWB() {
	var modal = contentWB + ' #waybill-consigneelookup';
	$(modal + ' .inputtxtfld').val('');
	$(modal + ' .inputslctfld')
		.empty()
		.trigger('change');
}

$(document)
	.off('focus', contentWB + ' .select2')
	.on('focus', contentWB + ' .select2', function (e) {
		if (e.originalEvent) {
			$(this).siblings('select').select2('open');
		}
	});

$(document)
	.off('keydown', contentWB + ' .waybill-shipmentdescription')
	.on('keydown', contentWB + ' .waybill-shipmentdescription', function (e) {
		var key = e.keycode || e.which;
		if (key == '9') {
			$(contentWB + ' #waybill-shipperlookup').modal('show');
		}
	});

$(document)
	.off('keydown', contentWB + ' .waybill-shipper-telephone')
	.on('keydown', contentWB + ' .waybill-shipper-telephone', function (e) {
		var key = e.keycode || e.which;
		if (key == '9') {
			$(contentWB + ' #waybill-consigneelookup').modal('show');
		}
	});

$(document)
	.off('shown.bs.modal', contentWB + ' .modal')
	.on('shown.bs.modal', contentWB + ' .modal', function () {
		$(this).find('.qsbox').focus().select();
	});

$(document)
	.off('hidden.bs.modal', contentWB + ' #waybill-packagedimensionsmodal')
	.on('hidden.bs.modal', contentWB + ' #waybill-packagedimensionsmodal', function () {
		$(inputfieldsWB + ' .waybill-declaredvalue')
			.focus()
			.select();
	});

$(document)
	.off('shown.bs.modal', contentWB + ' #waybill-packagedimensionsmodal')
	.on('shown.bs.modal', contentWB + ' #waybill-packagedimensionsmodal', function () {
		$(this).find('.packagedimensionsmodalflds-quantity').focus().select();
	});

/*$(document).off('keydown',contentWB+' .packagedimensionsmodalflds-actualweight').on('keydown',contentWB+' .packagedimensionsmodalflds-actualweight',function(e){
	
	var key = e.keycode||e.which;
	if(key=='9'){
		$(contentWB+' .packagedimensionsmodalflds-length').focus().select();
	}
});*/

$(document)
	.off('select2:close', contentWB + ' .waybill-modeoftransport')
	.on('select2:close', contentWB + ' .waybill-modeoftransport', function (e) {
		if ($(contentWB + ' .waybill-waybilltype').val() == 'PARCEL') {
			$(contentWB + ' #waybill-packagedimensionsmodal').modal('show');
		}
		//$(contentWB+' #waybill-packagedimensionsmodal').modal('show');
	});
/*$(document).off('keyup',contentWB+' .packagedimensionsmodalflds input[type="text"]').on('keyup',contentWB+' .packagedimensionsmodalflds input[type="text"]',function(e){

	if
});*/

$(document)
	.off('click', contentWB + ' #waybillprintingmodal-printbtn')
	.on('click', contentWB + ' #waybillprintingmodal-printbtn', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var bolnumber = $(modal + ' .waybillprintingmodal-bolnumber').val();
		var formtype = $(modal + ' .waybillprintingmodal-formtype').val();
		var remarks = $(modal + ' .waybillprintingmodal-remarks')
			.val()
			.replace(/\s/g, '%20');

		var title = 'Print Preview [' + $('#pgtxnwaybill-id').attr('pgtxnwaybill-number') + ']';
		var tabid = formtype + $('#pgtxnwaybill-id').attr('pgtxnwaybill-number');
		var waybillnumber = $('#pgtxnwaybill-id').attr('pgtxnwaybill-number');
		var waybillid = $(contentWB + ' #pgtxnwaybill-id').val();

		var btn = $(this);
		btn.addClass('disabled');

		/*if(bolnumber.trim()==''){
		btn.removeClass('disabled');
		say("Please provide BOL Number");
	}
	else */ if (formtype != 'EXTERNAL' && formtype != 'INTERNAL' && formtype != 'DR' && formtype != 'DR-ALT' && formtype != 'INTERNAL-ALT' && formtype != 'EXTERNAL-ALT' && formtype != 'TRANS-BOL-ORIG') {
			btn.removeClass('disabled');
			say('Please select form type');
		} else {
			if (formtype == 'INTERNAL' || formtype == 'INTERNAL-ALT') {
				$.confirm({
					animation: 'bottom',
					closeAnimation: 'top',
					animationSpeed: 1000,
					animationBounce: 1,
					title: 'Confirmation',
					content: 'Do you want to continue?',
					confirmButton: 'Confirm',
					cancelButton: 'Cancel',
					confirmButtonClass: 'btn-oceanblue',
					cancelButtonClass: 'btn-royalblue',
					theme: 'white',

					confirm: function () {
						$.post(server + 'waybill.php', { increaseprintcounter: 'KFHoEO#0HELKN#Opsy#lka$P#HlNlk!I#H$', waybillid: waybillid }, function (data) {
							if (data.trim() == 'success') {
								$(modal).modal('hide');
								$(document)
									.off('hidden.bs.modal', modal)
									.on('hidden.bs.modal', modal, function () {
										$(document).off('hidden.bs.modal', modal);

										getWaybillInformation(waybillnumber);

										$(contentWB + ' #WBprintpdfiframe').attr(
											'src',
											'printouts/transactions/waybill.php?txnnumber=' + waybillnumber + '&bolnumber=' + bolnumber + '&formtype=' + formtype + '&remarks=' + remarks
										);

										$(contentWB + ' #WBprintpdfiframe').load(function () {
											PDFDirectPrint('WBprintpdfiframe');
											btn.removeClass('disabled');
										});
										/*printJS(maindir+"application/printouts/transactions/waybill.php?txnnumber="+waybillnumber+"&bolnumber="+bolnumber);*/
									});
							} else if (data.trim() == 'invalidwaybill') {
								say('Unable to print waybill. Invalid waybill number, please refresh page.');
								btn.removeClass('disabled');
							} else if (data.trim() == 'loggedstatus') {
								getWaybillInformation(waybillnumber);
								say('Unable to print waybill. Transaction not yet posted.');
								btn.removeClass('disabled');
							} else {
								say(data);
								btn.removeClass('disabled');
							}
						});
					},
					cancel: function () {
						btn.removeClass('disabled');
					}
				});
			} else if (formtype == 'EXTERNAL' || formtype == 'EXTERNAL-ALT') {
				$(modal).modal('hide');
				$(document)
					.off('hidden.bs.modal', modal)
					.on('hidden.bs.modal', modal, function () {
						$(document).off('hidden.bs.modal', modal);
						/*
						printJS(maindir+"application/printouts/transactions/waybill-external-form.php?txnnumber="+waybillnumber+"&bolnumber="+bolnumber);*/
						$(contentWB + ' #WBprintpdfiframe').attr(
							'src',
							'printouts/transactions/waybill-external-form.php?txnnumber=' + waybillnumber + '&bolnumber=' + bolnumber + '&formtype=' + formtype + '&remarks=' + remarks
						);

						$(contentWB + ' #WBprintpdfiframe').load(function () {
							PDFDirectPrint('WBprintpdfiframe');
							btn.removeClass('disabled');
						});
					});
			} else if (formtype == 'DR' || formtype == 'DR-ALT') {
				$(modal).modal('hide');
				$(document)
					.off('hidden.bs.modal', modal)
					.on('hidden.bs.modal', modal, function () {
						$(document).off('hidden.bs.modal', modal);
						/*printJS(maindir+"application/printouts/transactions/waybill-dr-form.php?txnnumber="+waybillnumber+"&bolnumber="+bolnumber);*/
						$(contentWB + ' #WBprintpdfiframe').attr('src', 'printouts/transactions/waybill-dr-form.php?txnnumber=' + waybillnumber + '&bolnumber=' + bolnumber + '&formtype=' + formtype);

						$(contentWB + ' #WBprintpdfiframe').load(function () {
							PDFDirectPrint('WBprintpdfiframe');
							btn.removeClass('disabled');
						});
					});
			} else if (formtype == 'TRANS-BOL-ORIG') {
				$(modal).modal('hide');
				$(document)
					.off('hidden.bs.modal', modal)
					.on('hidden.bs.modal', modal, function () {
						$(document).off('hidden.bs.modal', modal);
						$(contentWB + ' #WBprintpdfiframe').attr('src', 'printouts/transactions/bol-original.php?txnnumber=' + waybillnumber + '&bolnumber=' + bolnumber + '&formtype=' + formtype);

						$(contentWB + ' #WBprintpdfiframe').load(function () {
							PDFDirectPrint('WBprintpdfiframe');
							btn.removeClass('disabled');
						});
					});
			}
		}
	});

function PDFDirectPrint(elementId) {
	var getMyFrame = document.getElementById(elementId);
	getMyFrame.focus();
	getMyFrame.contentWindow.print();
}

$(document)
	.off('change', contentWB + ' .waybill-pickupdate')
	.on('change', contentWB + ' .waybill-pickupdate', function () {
		$(contentWB + ' .waybill-waybilltype').select2('open');
	});

$(document)
	.off('change', contentWB + ' .uploadwaybillstatusupdatemodal-type')
	.on('change', contentWB + ' .uploadwaybillstatusupdatemodal-type', function () {
		var type = $(this).val();
		var modal = '#' + $(this).closest('.modal').attr('id');

		if (type == 'STATUSUPDATE') {
			$(modal + ' #uploadwaybillstatusupdatemodal-downloadtemplatebtn').attr('href', '../file-templates/waybill-update-status-template.xlsx');
		} else if (type == 'COSTINGUPDATE') {
			$(modal + ' #uploadwaybillstatusupdatemodal-downloadtemplatebtn').attr('href', '../file-templates/waybill-update-costing-template.xlsx');
		} else if (type == 'WAYBILLUPLOAD') {
			$(modal + ' #uploadwaybillstatusupdatemodal-downloadtemplatebtn').attr('href', '../file-templates/waybill-upload-template.xlsx');
		} else if (type == 'CHARGESUPLOAD') {
			$(modal + ' #uploadwaybillstatusupdatemodal-downloadtemplatebtn').attr('href', '../file-templates/waybill-charges-upload-template.xlsx');
		} else if (type == 'PACKAGEDIMENSIONUPLOAD') {
			$(modal + ' #uploadwaybillstatusupdatemodal-downloadtemplatebtn').attr('href', '../file-templates/waybill-package-dimensions-upload-template.xlsx');
		}
	});

$(document)
	.off('click', contentWB + ' #waybill-trans-updatepaidflagbtn:not(".disabled")')
	.on('click', contentWB + ' #waybill-trans-updatepaidflagbtn:not(".disabled")', function () {
		listentoboltype = false;
		var modal = '#togglewbpaidflagmodal';
		var paidflag = $(contentWB + ' .waybill-paidflag').attr('paidflag');
		var paymentreference = $(contentWB + ' .waybill-paymentreference').val();
		var txnid = $(contentWB + ' #pgtxnwaybill-id').val();
		var txnnumber = $(contentWB + ' #pgtxnwaybill-id').attr('pgtxnwaybill-number');

		$(contentWB + ' .togglewbpaidflagmodal-paymentreference').val(paymentreference);
		//$(contentWB+' #togglewbpaidflagmodal-id').val(txnid);
		$(contentWB + ' .togglewbpaidflagmodal-txnnumber')
			.val(txnnumber)
			.closest('.form-group')
			.removeClass('hidden');
		$(contentWB + ' .togglewbpaidflagmodal-paidflag')
			.val(paidflag)
			.trigger('change');

		$(contentWB + ' .togglewbpaidflagmodal-type')
			.val('NON-VARIOUS')
			.trigger('change');

		$(modal).modal('show');
		$(document)
			.off('shown.bs.modal', modal)
			.on('shown.bs.modal', modal, function () {
				$(document).off('shown.bs.modal', modal);
				$(contentWB + ' .togglewbpaidflagmodal-txnnumber')
					.focus()
					.select();
				listentoboltype = true;
			});
	});

$(document)
	.off('click', contentWB + ' #togglewbpaidflagmodal-savebtn:not(".disabled")')
	.on('click', contentWB + ' #togglewbpaidflagmodal-savebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');

		var remarks = $(modal + ' .togglewbpaidflagmodal-remarks').val();
		var paidflag = $(modal + ' .togglewbpaidflagmodal-paidflag').val();
		var txnnumber = $(modal + ' .togglewbpaidflagmodal-txnnumber').val();
		var mawbl = $(modal + ' .togglewbpaidflagmodal-mawbl').val();
		var type = $(modal + ' .togglewbpaidflagmodal-type').val();
		var paymentreference = '';
		var currenttxnnumber = $(contentWB + ' #pgtxnwaybill-id').attr('pgtxnwaybill-number');

		var button = $(this);
		button.addClass('disabled');

		if (paidflag == 1) {
			paymentreference = $(modal + ' .togglewbpaidflagmodal-paymentreference').val();
		}

		$(modal + ' .modal-errordiv').empty();

		if (type == 'VARIOUS' && mawbl.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide valid mawbl number.</div></div>");
			$(modal + ' .togglewbpaidflagmodal-mawbl').focus();
			button.removeClass('disabled');
		} else if (type == 'NON-VARIOUS' && txnnumber.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide valid BOL number.</div></div>");
			$(modal + ' .togglewbpaidflagmodal-txnnumber').focus();
			button.removeClass('disabled');
		} else if (paidflag == 1 && paymentreference.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide payment reference.</div></div>");
			$(modal + ' .togglewbpaidflagmodal-paymentreference').focus();
			button.removeClass('disabled');
		} else if (type != 'NON-VARIOUS' && type != 'VARIOUS') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid Type, please refresh page.</div></div>");
			button.removeClass('disabled');
		} else {
			$.confirm({
				animation: 'bottom',
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce: 1,
				title: 'Change Payment Status',
				content: 'Do you want to continue?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',
				confirmButtonClass: 'btn-oceanblue',
				cancelButtonClass: 'btn-royalblue',
				theme: 'white',

				confirm: function () {
					$('#loading-img').removeClass('hidden');
					$.post(
						server + 'waybill.php',
						{
							changePaymentFlagging: 'dROi$nsFpo94dnels$4sRoi809srbmouS@1!',
							type: type,
							txnnumber: txnnumber,
							mawbl: mawbl,
							remarks: remarks,
							paidflag: paidflag,
							paymentreference: paymentreference
						},
						function (data) {
							if (data.trim() == 'success') {
								$(modal).modal('hide');
								$(document)
									.off('hidden.bs.modal', modal)
									.on('hidden.bs.modal', modal, function () {
										$(document).off('hidden.bs.modal', modal);

										$(modal + ' #togglewbpaidflagmodal-id').val('');
										$(modal + ' .togglewbpaidflagmodal-txnnumber').val('');
										$(modal + ' .togglewbpaidflagmodal-paidflag')
											.val(0)
											.trigger('change');
										$(modal + ' .togglewbpaidflagmodal-paymentreference').val('');
										$(modal + ' .togglewbpaidflagmodal-remarks').val('');

										//if(type=='NON-VARIOUS'){
										getWaybillInformation(currenttxnnumber);
										//}
										button.removeClass('disabled');
										$('#loading-img').addClass('hidden');

										say('Payment status updated for ' + txnnumber + mawbl);
									});
							} else if (data.trim() == 'invalidtransaction') {
								say('Unable to change Payment Status Flag. Invalid BOL Number: ' + txnnumber);
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							} else if (data.trim() == 'invalidmawbl') {
								say('Unable to change Payment Status Flag. Invalid MAWBL: ' + mawbl);
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							} else if (data.trim() == 'noreference') {
								say('Unable to change Payment Status Flag. Please provide payment reference.');
								$(modal + ' .togglewbpaidflagmodal-paymentreference').focus();
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							} else {
								alert(data);
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							}
						}
					);
				},
				cancel: function () {
					button.removeClass('disabled');
				}
			});
		}
	});

$(document)
	.off('change', contentWB + ' .togglewbpaidflagmodal-paidflag:not(".disabled")')
	.on('change', contentWB + ' .togglewbpaidflagmodal-paidflag:not(".disabled")', function () {
		var flag = $(this).val();
		var modal = $(this).closest('.modal');
		if (flag == 1) {
			modal.find('.togglewbpaidflagmodal-paymentreference').closest('.form-group').removeClass('hidden');
		} else {
			modal.find('.togglewbpaidflagmodal-paymentreference').val('').closest('.form-group').addClass('hidden');
		}
	});

$(document)
	.off('change', contentWB + ' .togglewbpaidflagmodal-type:not(".disabled")')
	.on('change', contentWB + ' .togglewbpaidflagmodal-type:not(".disabled")', function () {
		if (listentoboltype == true) {
			var flag = $(this).val();
			var modal = $(this).closest('.modal');
			if (flag == 'VARIOUS') {
				modal.find('.togglewbpaidflagmodal-mawbl').val('').closest('.form-group').removeClass('hidden');
				modal.find('.togglewbpaidflagmodal-txnnumber').val('').closest('.form-group').addClass('hidden');
				modal.find('.togglewbpaidflagmodal-mawbl').focus();
			} else {
				modal.find('.togglewbpaidflagmodal-mawbl').val('').closest('.form-group').addClass('hidden');
				modal.find('.togglewbpaidflagmodal-txnnumber').val('').closest('.form-group').removeClass('hidden');
				modal.find('.togglewbpaidflagmodal-txnnumber').focus();
			}
		}
	});
