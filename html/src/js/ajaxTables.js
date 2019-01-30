// initialize ajax tables
$('.ajax-table').each(function(){
	let table = $(this);
	let pager = $(this).siblings('.ajax-table-pager');
	let src = table.data('src');
	let limit = table.data('limit');
	let page = table.data('page');	
	let sort = table.data('sort');
	let order = table.data('order');			
	let offset = 0;
	let tableData = { "limit": limit, "offset": offset, "sort": sort, "order": order };
	
	$.ajax({
		url: src,
		type: "POST",
		data: tableData,
		success: function(d) {
			if (d == "error") {
				alert("An error occurred. Table not loaded.");
			} else {
				let count = d.match(/\{(.*)\}/);
				let pages = Math.ceil(count[1] / limit);
				table.data('pages', pages);
				pager.children('span').children('.ajax-table-pager-pages').text(pages);
				if (pages > 1) {
					pager.children('.ajax-table-btn.page-forward').prop('disabled', false);
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
	let src = table.data('src');
	let limit = table.data('limit');
	let pages = table.data('pages');
	let page = table.data('page');
	let sort = table.data('sort');
	let order = table.data('order');
	let offset = (page - 1) * limit;
	let tableData = { "limit": limit, "offset": offset, "sort": sort, "order": order };
	
	$.ajax({
		url: src,
		type: "POST",
		data: tableData,
		success: function(d) {
			if (d == "error") {
				alert("An error occurred. Table not loaded.");				
			} else {
				let count = d.match(/\{(.*)\}/);
				let pages = Math.ceil(count[1] / limit);
				table.data('pages', pages);
				pager.children('span').children('.ajax-table-pager-pages').text(pages);
				table.children('tbody').children().remove();
				table.append(d.replace("{" + count[1] + "}", ""));
			}
		}
	});
}

// page ajax table
$('.ajax-table-btn.page-forward').click(function(){
	pageAjaxTable($(this).parent().siblings('.ajax-table'), 1);
});
$('.ajax-table-btn.page-back').click(function(){
	pageAjaxTable($(this).parent().siblings('.ajax-table'), 0);
});

function pageAjaxTable(table, direction) {
	let pages = table.data('pages');
	let page = table.data('page');
	let pager = table.siblings('.ajax-table-pager');
	
	if (direction == 1 && page < pages) {
		page += 1;
	} else if (direction == 0 && page > 1) {
		page -= 1;
	}
	
	table.data('page', page);
	pager.children('span').children('.ajax-table-pager-page').text(page);
	
	if (page < pages) {
		pager.children('.ajax-table-btn.page-forward').prop('disabled', false);
	} else {
		pager.children('.ajax-table-btn.page-forward').prop('disabled', true);
	}
	
	if (page > 1) {
		pager.children('.ajax-table-btn.page-back').prop('disabled', false);
	} else {
		pager.children('.ajax-table-btn.page-back').prop('disabled', true);
	}
	
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
	
	table.data('sort', sort);
	table.data('order', order);
	loadAjaxTable(table);
});