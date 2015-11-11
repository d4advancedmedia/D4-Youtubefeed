jQuery(document).ready(function($) {


	$('#youtube-thumbs a').click(function(e) {
		e.preventDefault();
		var videoid = $(this).data('vidsrc');
		$("#youtube-frame iframe").remove();
		$('<iframe width="100%" height="100%" frameborder="0" allowfullscreen></iframe>')
		    .attr("src", "http://www.youtube.com/embed/" + videoid + '?rel=0&autoplay=1')
		    .appendTo("#youtube-frame");
		$('.youtube-active').removeClass('youtube-active');
	    $(this).addClass('youtube-active');
	}); //*/

});