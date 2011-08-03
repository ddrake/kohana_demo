$.ajaxSetup ({
  cache: false,
  dataType: 'html'
});
var ajax_load = "<img src='assets/images/load.gif' alt='loading...' />";

$('#album_list').find('tr.data').live('click', function(e) {
  this.blur();
  $('#details').html(ajax_load).load('album/details/' + $(this).attr('id'));
});
$(function() {
	$('tr.data').hover(function() {
		$(this).addClass('highlight');
			}, function() {
		$(this).removeClass('highlight');
	});
});
