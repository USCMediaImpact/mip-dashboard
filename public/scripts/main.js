$(function(){
	$(document).foundation();
	$(window).on('resize', function(){
		var documentHeight = $(document).height(),
			windowHeight = $(window).height(),
			headerHeight = $('.top-bar').height(),
			footerHeight = $('.footer').height(),
			height = Math.max(documentHeight, windowHeight);
		$('.off-canvas').height(height);
		$('.main-content').css('min-height', height - headerHeight - footerHeight);
	});
	$(window).trigger('resize');
});