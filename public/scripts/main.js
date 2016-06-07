$(function () {
	$(document).foundation();
	$.noty.defaults = {
		layout: 'topRight',
		theme: 'relax', // or 'relax'
		type: 'alert',
		text: '', // can be html or string
		dismissQueue: false, // If you want to use queue feature set this true
		template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
		animation: {
			open: 'animated fadeIn', // or Animate.css class names like: 'animated bounceInLeft'
			close: 'animated flipOutX', // or Animate.css class names like: 'animated bounceOutLeft'
			easing: 'swing',
			speed: 500 // opening & closing animation speed
		},
		timeout: 10 * 1000, // delay for closing event. Set false for sticky notifications
		force: false, // adds notification to the beginning of queue when set to true
		modal: false,
		maxVisible: 5, // you can set max visible notification for dismissQueue true option,
		killer: false, // for close all notifications before show
		closeWith: ['click'], // ['click', 'button', 'hover', 'backdrop'] // backdrop click will close all notifications
		callback: {
		},
		buttons: false // an array of buttons
	};
	$(document).on('change', '.clientSelector', function(){
		Cookies.set('client-id', $(this).val());
		window.location.reload(true);
	});
	$(window).on('resize', function () {
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