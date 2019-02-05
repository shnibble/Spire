// process and retrieve GET variables
function getQueryParams(qs) {
	qs = qs.split("+").join(" ");
	let params = {},
		tokens,
		re = /[?&]?([^=]+)=([^&]*)/g;

	while (tokens = re.exec(qs)) {
		params[decodeURIComponent(tokens[1])]
			= decodeURIComponent(tokens[2]);
	}

	return params;
}
let $_GET = getQueryParams(document.location.search);

// load filters
let validFilters = [];
function loadAjaxTableFilters(filters) {
	let filter = filters.split(" ");
	for (i = 0; i < filter.length; i++) {
		validFilters.push(filter[i]);
	}
}

function processAjaxTableFilters(table) {
	var validFilterFound = false;
	$.each($_GET, function(index, element){
		if (!validFilterFound && jQuery.inArray(index, validFilters) !== -1) {
			table.data('filtertype', index);
			table.data('filtervalue', element);
			table.siblings('.ajax-table-filter').children('select[data-filtertype="' + index + '"]').val(element);
			
			validFilterFound = true;
			//console.log("Valid filter found: " + index + " = " + element);
		}
	});
}

// initialize ajax tables
$('.ajax-table').each(function(){
	let table = $(this);
	let pager = $(this).siblings('.ajax-table-pager');
	let results = $(this).siblings('.ajax-table-results').children().children('.results-count');
	let src = table.data('src');
	let limit = table.data('limit');
	let page = table.data('page');
	let validFilters = table.data('validfilters');
	
	// process GET filters
	if (validFilters) {
		loadAjaxTableFilters(validFilters);
		processAjaxTableFilters(table);
	}
	
	let filterType = table.data('filtertype');		
	let filterValue = table.data('filtervalue');
	let sort = table.data('sort');
	let order = table.data('order');		
	let offset = 0;
	let id = table.data('id');
	let tableData = {};
	if (id) {
		tableData = { "limit": limit, "offset": offset, "sort": sort, "order": order, "filtertype": filterType, "filtervalue": filterValue, "id": id };
		
	} else {
		tableData = { "limit": limit, "offset": offset, "sort": sort, "order": order, "filtertype": filterType, "filtervalue": filterValue };
	}
	// console.log(tableData);
	
	$.ajax({
		url: src,
		type: "POST",
		data: tableData,
		success: function(d) {
			if (d == "error") {;
				alert("An error occurred. Table not loaded.")
			} else {
				let count = d.match(/\{(.*)\}/);
				results.text(count[1]);
				let pages = Math.ceil(count[1] / limit);
				table.data('pages', pages);
				pager.children('span').children('.ajax-table-pager-pages').text(pages);
				if (pages > 1) {
					pager.children('.ajax-table-btn.page-forward').prop('disabled', false);
					pager.children('.ajax-table-btn.page-end').prop('disabled', false);
				} else {
					pager.remove();
				}
				table.append(d.replace("{" + count[1] + "}", ""));
			}
		}
	});
});

// load ajax table
function loadAjaxTable(table){
	let pager = table.siblings('.ajax-table-pager');
	let results = table.siblings('.ajax-table-results').children().children('.results-count');
	let src = table.data('src');
	let limit = table.data('limit');
	let pages = table.data('pages');
	let page = table.data('page');
	let filterType = table.data('filtertype');		
	let filterValue = table.data('filtervalue');
	let sort = table.data('sort');
	let order = table.data('order');
	let offset = (page - 1) * limit;
	let id = table.data('id');
	let tableData = {};
	if (id) {
		tableData = { "limit": limit, "offset": offset, "sort": sort, "order": order, "filtertype": filterType, "filtervalue": filterValue, "id": id };
		
	} else {
		tableData = { "limit": limit, "offset": offset, "sort": sort, "order": order, "filtertype": filterType, "filtervalue": filterValue };
	}
	// console.log(tableData);
	
	$.ajax({
		url: src,
		type: "POST",
		data: tableData,
		success: function(d) {
			if (d == "error") {
				alert("An error occurred. Table not loaded.");
			} else {
				let count = d.match(/\{(.*)\}/);
				results.text(count[1]);
				let pages = Math.ceil(count[1] / limit);
				table.data('pages', pages);
				pager.children('span').children('.ajax-table-pager-pages').text(pages);
				table.children('tbody').children().remove();
				table.append(d.replace("{" + count[1] + "}", ""));
				
				if (page < pages) {
					pager.children('.ajax-table-btn.page-forward').prop('disabled', false);
					pager.children('.ajax-table-btn.page-end').prop('disabled', false);
				} else {
					pager.children('.ajax-table-btn.page-forward').prop('disabled', true);
					pager.children('.ajax-table-btn.page-end').prop('disabled', true);
				}
				
				if (page > 1) {
					pager.children('.ajax-table-btn.page-back').prop('disabled', false);
					pager.children('.ajax-table-btn.page-beginning').prop('disabled', false);
				} else {
					pager.children('.ajax-table-btn.page-back').prop('disabled', true);
					pager.children('.ajax-table-btn.page-beginning').prop('disabled', true);
				}
			}
		}
	});
}

// ajax table buttons
$('.ajax-table-btn.page-beginning').click(function(){
	pageAjaxTable($(this).parent().siblings('.ajax-table'), -1);
});
$('.ajax-table-btn.page-back').click(function(){
	pageAjaxTable($(this).parent().siblings('.ajax-table'), 0);
});
$('.ajax-table-btn.page-forward').click(function(){
	pageAjaxTable($(this).parent().siblings('.ajax-table'), 1);
});
$('.ajax-table-btn.page-end').click(function(){
	pageAjaxTable($(this).parent().siblings('.ajax-table'), 2);
});
$('.ajax-table-filter-select').on('change', function(){
	
	$('.ajax-table-filter-select').not($(this)).val(0);
	
	let table = $(this).parent().siblings('.ajax-table');
	let filterType = $(this).data('filtertype');
	let filterValue = $(this).val();
	
	table.data('filtertype', filterType);
	table.data('filtervalue', filterValue);
	
	//console.log("type: " + filterType + ", value: " + filterValue);
	
	loadAjaxTable(table);
});

function pageAjaxTable(table, direction) {
	let pages = table.data('pages');
	let page = table.data('page');
	let pager = table.siblings('.ajax-table-pager');
	
	if (direction == 1 && page < pages) {
		page += 1;
	} else if (direction == 0 && page > 1) {
		page -= 1;
	} else if (direction == -1) {
		page = 1;
	} else if (direction == 2) {
		page = pages;
	}
	
	table.data('page', page);
	pager.children('span').children('.ajax-table-pager-page').text(page);
	loadAjaxTable(table);
}


// sort ajax table
$('.ajax-table-header').click(function(){
	let table = $(this).parent().parent().parent('.ajax-table');
	let sort = $(this).data('sort');
	let oldSort = table.data('sort');
	let oldOrder = table.data('order');
	let order = "";
	
	if ( sort == oldSort) {
		if (oldOrder == "ASC") {
			order = "DESC";
		} else {
			order = "ASC";
		}
	} else {
		order = oldOrder;
	}
	
	$(this).siblings().children('span').text("");
	$(this).parent('tr').siblings('tr').children('th').children('span').text("");
	if (order == "ASC") {
		$(this).children('span').html("&circ;");
	} else {
		$(this).children('span').html("&caron;");
	}
	
	table.data('sort', sort);
	table.data('order', order);
	loadAjaxTable(table);
});