var server = '../scripts/';
var pagescripts = '../js/page-scripts/';
var loadables = 'Loadables/';
var configfldr = '../config/';

function isJSON(str) {
	try {
		JSON.parse(str);
	} catch (e) {
		return false;
	}
	return true;
}
function setActiveTab(tabid) {
	var section = $(tabid).closest('.tabpanesection');
	$(tabid).addClass('active').siblings().removeClass('active');
	var pane = $(tabid).data('pane');
	$(pane).addClass('active').siblings().removeClass('active');
}

function loadpage(loadto, tobeloaded) {
	$(loadto).load(tobeloaded, function () {
		setTimeout(function () {
			$('#loading-img').addClass('hidden');
		}, 300);
	});
}

function getDate() {
	var date = new Date(),
		m = date.getMonth() + 1,
		d = date.getDate(),
		y = date.getFullYear(),
		datestr = m + '/' + d + '/' + y;
	return datestr;
}

function getDatetime() {
	var date = new Date(),
		m = date.getMonth() + 1,
		d = date.getDate(),
		y = date.getFullYear(),
		H = date.getHours(),
		i = date.getMinutes(),
		s = date.getSeconds(),
		datestr = m + '/' + d + '/' + y + ' ' + H + ':' + i + ':' + s;
	return datestr;
}

function say(message) {
	$.alert({
		title: 'System Message',
		content: message,
		confirmButton: 'Close',
		animation: 'bottom',
		closeAnimation: 'top',
		confirmButtonClass: 'btn-royalblue',
		columnClass: 'col-md-offset-3 col-md-6',
		animationSpeed: 1000,
	});
}

function notify(msg, type, icon) {
	$.alert({
		title: 'System Message',
		content: msg,
		confirmButton: 'Close',
		animation: 'bottom',
		closeAnimation: 'top',
		confirmButtonClass: 'btn-royalblue',
		columnClass: 'col-md-offset-3 col-md-6',
		animationSpeed: 1000,
	});
	/*$.notify({
				// options
				icon: icon,

				message: msg
			},{
				// settings
				type: type,
				offset: 20,
				spacing: 10,
				z_index: 2031,
				delay: 5000,
				placement: {
					from: "bottom",
					align: "center"
				},
				allow_dismiss:true,
				timer: 1000,
				url_target: '_blank',
				mouse_over: null,
				animate: {
					enter: 'animated fadeInDown',
					exit: 'animated fadeOutUp'
				},
			});*/
}

function convertFloat(input) {
	var number = parseFloat(Math.round(input * 100000) / 100000).toFixed(5),
		temp,
		start; //decimal round off to 5 decimal places
	number = Number(number).toLocaleString(undefined, { minimumFractionDigits: 5 }); //5 decimal places
	if (number.indexOf('.') < 0) {
		number = number + '.00';
	} else {
		start = number.indexOf('.');
		temp = number.substring(start);
		if (temp.length < 3) {
			number = number + '0';
		}
	}
	number = number.replace(/0{0,3}$/, '');
	return number;
}

function checkEmail(input) {
	var result = /^([a-zA-Z0-9\.\_]{2,}([\@]{1}[a-zA-Z0-9]{2,})([\.]{1}[a-zA-Z0-9]{2,})([\.]{1}[a-zA-Z]{2,}){0,})$/.test(input);
	return result;
}

function checkName(input) {
	var result = /^([a-zA-Z]{2,})([\s][a-zA-Z]{0,}){0,}$/.test(input);
	return result;
}

function checkDeptName(input) {
	var result = /^([a-zA-Z0-9\/\(\)\-]{2,})([\s][a-zA-Z0-9\/\(\)\-]{0,}){0,}$/.test(input);
	return result;
}

function checkAlphanumeric(input) {
	var result = /^([a-zA-Z0-9]{3,})([\s][a-zA-Z0-9]{0,}){0,}$/.test(input);
	return result;
}

function checkRemarks(input) {
	var result = /^([a-zA-Z0-9]{3,})([\s][a-zA-Z0-9\.\-\%\$\<\>\?\!\&\,\;\:\'\"]{0,}([\.]{0,1})){0,}$/.test(input);
	return result;
}

function checkClassfn(input) {
	var result = /^([a-zA-Z0-9]{3,})$/.test(input);
	return result;
}

function checkUsername(input) {
	var result = /^([a-zA-Z0-9]{1,})([a-zA-Z0-9\_\.]{4,})([\_][a-zA-Z0-9]{0,}){0,}$/.test(input);
	return result;
}
function checkDateFormat(input) {
	var result =
		/^(((((0[13578]|1[02])([/])((0[1-9])|([12][0-9]{1})|(3[01])))|((0[4,6,9]|11)([/])((0[1-9])|([1-2]{1}[0-9]{1})|30))|((02)([/])((0[1-9])|([1][0-9]{1})|([2][0-8]{1}))))([/])(19|20)([0-9]{2}))|((02[/]29[/])(19|20)(00|04|08|12|16|20|24|28|32|36|40|44|48|52|56|60|64|68|72|76|80|84|88|92|96)))$/.test(
			input
		);
	return result;
}

function checkNumber(input) {
	var result = /^([\+]{0,}([0-9]{2,})([\-\s][0-9]{3,}){0,})([\,][\s]{0,}[\+]{0,}([0-9]{2,})([\-\s][0-9]{3,}){0,}){0,}$/.test(input);
	return result;
}

function checkPrice(input) {
	var result = /^([0-9]{0,})([\.][0-9]{1,}){0,1}$/.test(input);
	return result;
}

function checkPassword(input) {
	var result = /^([a-zA-Z0-9\!\?]{5,})$/.test(input);
	return result;
}

function checkInt(input) {
	var result = /^([1-9]{1})([0-9]{0,})$/.test(input);
	return result;
}

function checkCode(input) {
	var result = /^([a-zA-Z0-9]{2,})$/.test(input);
	return result;
}

function computeGrandTotal(tableidrow, column, storeto) {
	var total = 0;
	$(tableidrow).each(function () {
		total = parseFloat($(this).find(column).text().replace(/,/g, '')) + parseFloat(total);
	});
	total = convertFloat(total);
	$(storeto).val(total);
}

// TABLES AND PAGINATION
var rowperpage = 10;
function sliceTable(rowperpage, tablename, paginationId) {
	if ($(tablename + ' .tablerow').length < 1) {
		$(tablename + ' .noresult').removeClass('hidden');
	}
	$(tablename + ' .tablerow').addClass('hidden');
	$(tablename + ' .tablerow')
		.slice(0, rowperpage)
		.removeClass('hidden');

	$(paginationId + ' li:first-child').addClass('active');
	$(paginationId + ' li').addClass('hidden');
	$(paginationId + ' li')
		.slice(0, 5)
		.removeClass('hidden');
}

function userAccess() {
	$.post(server + 'user-access.php', { DkkIroh3Lro93hdlk: 'sdfei3opod30napri' }, function (data) {
		var fetchdata = data.split(','),
			count = fetchdata.length;
		for (var i = 0; i < count; i++) {
			//$(fetchdata[i]).removeClass('hidden').removeAttr('disabled');
			$(fetchdata[i]).remove();
		}
	});
}

function userAccessMobile() {
	$.post(server + 'user-access.php', { DkkIroh3Lro93hdlk: 'sdfei3opod30napri' }, function (data) {
		var fetchdata = data.split(','),
			count = fetchdata.length;
		for (var i = 0; i < count; i++) {
			$("li[data-id='" + fetchdata[i].replace(/[\.\#]/g, '') + "']")
				.removeClass('hidden')
				.removeAttr('disabled');
		}
	});
}

function reinitializeDataTable(tableID, sortColumnInitial) {
	setTimeout(function () {
		$(tableID).DataTable({
			aaSorting: [[sortColumnInitial, 'asc']], //initially, table is sorted by second column desc
			columnDefs: [
				{
					targets: 'column-nosort', //class of columns you dont want to be sortable
					orderable: false,
				},
			],
			pagingType: 'full',
			bDestroy: true,
		});
	}, 100);
}

function _fnVisbleColumns(oSettings) {
	return $(_pluck(oSettings.aoColumns, 'nTh')).filter(':visible').length;
	var vis = 0;

	// No reduce in IE8, use a loop for now
	$.each(oSettings.aoColumns, function (i, col) {
		if (col.bVisible && $(col.nTh).css('display') !== 'none') {
			vis++;
		}
	});

	return vis;
}
