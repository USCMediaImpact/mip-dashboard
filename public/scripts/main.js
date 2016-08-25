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
		callback: {},
		buttons: false // an array of buttons
	};

	$(document).on('change', '.clientSelector', function () {
		Cookies.set('client-id', $(this).val());
		window.location.reload(true);
	});

	var dateRangeDisplayText = $('[name="defaultDateRange"]').val(),
		max_date = $('[name="max_date"]').val(),
		min_date = $('[name="min_date"]').val();

	if (max_date && min_date) {
		max_date = moment(max_date, 'YYYY-MM-DD');
		min_date = moment(min_date, 'YYYY-MM-DD');
		if (max_date.isValid() && min_date.isValid()) {
			dateRangeDisplayText = $.datepicker.formatDate('M d, yy', min_date.toDate()) + ' - ' + $.datepicker.formatDate('M d, yy', max_date.toDate());
		}
	}

	DefaultDateRangePickerOptions = typeof DefaultDateRangePickerOptions === 'undefined' ? {} : DefaultDateRangePickerOptions;
	$('.dateRange').daterangepicker({
		initialText: dateRangeDisplayText,
		dateFormat: 'M d, yy',
		presetRanges: [],
		change: function (event, el) {
			var range = el.instance.getRange(),
				min_date = moment(range.start),
				max_date = moment(range.end),
				panel = $(this).parents('.panel');
			$('input[name="min_date"]', panel).val(min_date.format('YYYY-MM-DD'));
			$('input[name="max_date"]', panel).val(max_date.format('YYYY-MM-DD'));
			$(panel).trigger('change.daterange');
		},
	}, DefaultDateRangePickerOptions);
	/**
	 * set default range
	 */
	if (!$('.dateRange').daterangepicker('getRange') && max_date && min_date) {
		$('.dateRange').daterangepicker('setRange', {
			start: moment(min_date).toDate(),
			end: moment(max_date).toDate()
		});
	}
	$('select').select2({
		minimumResultsForSearch: Infinity,
		change: function (data) {}
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

	var dateRangeChangeTimeout = null;
	$(document).on('change.daterange', '.panel', function () {
		window.clearTimeout(dateRangeChangeTimeout);
		var tableId = $(this).find('table').attr('id');
		dateRangeChangeTimeout = window.setTimeout(function () {
			typeof ReportDataTable !== 'undefined' && ReportDataTable[tableId] && ReportDataTable[tableId].ajax.reload();
		}, 500);
	});

	$(document).on('click', '.btnDownload', function () {
		var panel = $(this).parents('.panel'),
			mode = panel.find('table[id]').attr('mode'),
			action = mode ? $(this).attr('action').replace('{mode}', mode) : $(this).attr('action'),
			downloadForm = $('<form />', {
				action: action,
				method: 'POST',
				target: '_blank',
			});
		downloadForm.append($('[name="min_date"]', panel).clone());
		downloadForm.append($('[name="max_date"]', panel).clone());
		downloadForm.appendTo('body');
		downloadForm.submit();
		downloadForm.remove();
	});

	$('body').on('click', 'button.disabled, .button.disabled', function (evt) {
		evt.stopPropagation();
		evt.preventDefault();
	})
});