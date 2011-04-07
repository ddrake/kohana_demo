$.ajaxSetup ({
  cache: false,
  dataType: "html"
});
var ajax_load = "<img src='img/load.gif' alt='loading...' />";

$('#album_list tr').live('click', function(e) {
  this.blur();
  $("#details").html(ajax_load).load('album/details/' + $(this).attr('id'));
});
$(function() {
	$("tr").hover(function() {
		$(this).addClass("highlight");
			}, function() {
		$(this).removeClass("highlight");
	});
});
