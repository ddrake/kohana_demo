// Remove sortUp and sortDn classes from all sort-head <th>'s
function removeSortClasses()
{
	$('#sort-heads').find('th').removeClass('sort-asc sort-desc');
}
// reset values of all filter controls
function clearFilters()
{
	$('#filter-heads').find('input').val('');
	$('#filter-heads').find('select').val('');
}

// Build a parameter string from the filter controls
function getParameterString()
{
	var filterParams = new Array
	$('#filter-heads').find('input').each(function() {
		if ($(this).val().length > 0) {
			var filt = new Object();
			filt.name = $(this).attr('id');
			filt.value = 'like|' + $(this).val();
			filterParams.push(filt)
		}			
	});
	$('#filter-heads').find('select').each(function() {
		if ($(this).val().length > 0) {
			var filt = new Object();
			filt.name = $(this).attr('id');
			filt.value = 'eq|' + $(this).val();
			filterParams.push(filt)
		}			
	});
	$('#sort-heads').find('th.sort-asc').each(function() {
			var filt = new Object();
			filt.name = $(this).attr('id');
			filt.value = 'asc';
			filterParams.push(filt)
	});
	$('#sort-heads').find('th.sort-desc').each(function() {
			var filt = new Object();
			filt.name = $(this).attr('id');
			filt.value = 'desc';
			filterParams.push(filt)
	});
	return $.param(filterParams);
}

// Add the parameter string to the pagination links
function appendParameterStringToPagination()
{
	var filterParams = getParameterString();
	$('#pagination').find('a').each(function() {
		$(this).attr('href') += getParameterString();
	});
}

// onload jquery to call functions to build parameter string and add it to pagination links.
$(function(){
	//add onclick handler to add or toggle the sort class of a sort column head, 
	//and then performing the request to re-load the page with the current filter specs
	$('#sort-heads').find('th').click(function() {
		var isSortAsc = $(this).hasClass('sort-asc');
		removeSortClasses();
		$(this).addClass((isSortAsc ? 'sort-desc' : 'sort-asc'))
		window.location = $.url().attr('path') + '?' + getParameterString();
	});
	
	//add onclick handler to the 'refresh' button to get the parameter string and re-load the page
	$('#refresh').click(function(event) {
		event.preventDefault();
		window.location = $.url().attr('path') + '?' + getParameterString();
	})
	//add onclick handler to the 'clear filters' button to clear the filters, get the parameter string and re-load the page
	$('#clear-filters').click(function(event) {
		event.preventDefault();
		clearFilters();
		window.location = $.url().attr('path') + '?' + getParameterString();
	})
}); 

