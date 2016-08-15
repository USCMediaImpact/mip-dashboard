@inject('formatter', 'App\Helpers\FormatterHelper')
@extends('layouts.main')

@section('content')
	@if ($have_data)
		<div class="row expanded">
			<div class="column small-9">
				<h4 class="title">Weekly Story Performance</h4>
			</div>
		</div>
		<div class="row expanded">
			<div class="column small-12">
				<div class="panel">
					<div class="top-bar">
						<div class="top-bar-left">
							Scroll Depth
						</div>
						<div class="top-bar-right">
                                <input id="dateRangeScrollDepth" name="date_range" />
                                <input type="hidden" name="min_date" value="{{ date('Y-m-d', $min_date) }}" />
                                <input type="hidden" name="max_date" value="{{ date('Y-m-d', $max_date) }}" />
                                
								 <div class="switcher-group">
                                    <button class="button btnSwitcher small switcher on" mode="percent">Percent</button>
                                    <button class="button btnSwitcher small switcher" mode="count">Count</button>
                                </div>
								
								<button class="button small btnDownload" action="/data/stories/scroll_depth/{mode}/csv/all">Download Full Report</button>
						</div>
					</div>
					<div class="table-scroll">
						<table id="dataStoriesScrollDepth" mode="percent" class="report tiny hover expanded">
				            <thead>
				                <tr>
				                	<th>Article Title</th>
				                    <th>Total Page Views</th>
				                    <th>Started Scrolling</th>
				                    <th>25%<br />Scroll</th>
				                    <th>50%<br />Scroll</th>
				                    <th>75%<br />Scroll</th>
				                    <th>100%<br />Scroll</th>
				   					<th>Related Content</th>
				   					<th>End of Page</th>
				                </tr>
				            </thead>
				            <tbody>
				            </tbody>
				        </table>
					</div>
		        </div>
		        <div class="panel">
					<div class="top-bar">
						<div class="top-bar-left">
							Time on Article
						</div>
						<div class="top-bar-right">
                                <input id="dateRangeTimeOnArticle" name="date_range" />
                                <input type="hidden" name="min_date" value="{{ date('Y-m-d', $min_date) }}" />
                                <input type="hidden" name="max_date" value="{{ date('Y-m-d', $max_date) }}" />
                                <div class="switcher-group">
    								<button class="button btnSwitcher small switcher on" mode="percent">Percent</button>
                                    <button class="button btnSwitcher small switcher" mode="count">Count</button>
                                </div>
								<button class="button small btnDownload" action="/data/stories/time_on_article/{mode}/csv/all">Download Full Report</button>
						</div>
					</div>
					<div class="table-scroll">
						<table id="dataStoriesTimeOnArticle" mode="percent" class="report tiny hover expanded">
				            <thead>
				                <tr>
				                	<th>Article Title</th>
				                    <th>Total Page Views</th>
				                    <th>15 <br />Seconds</th>
				                    <th>30 <br />Seconds</th>
				                    <th>45 <br />Seconds</th>
				                    <th>60 <br />Seconds</th>
				                    <th>75 <br />Seconds</th>
				                    <th>90 <br />Seconds</th>
				                </tr>
				            </thead>
				            <tbody>
				            </tbody>
				        </table>
					</div>
		        </div>
		        <div class="panel">
					<div class="top-bar">
						<div class="top-bar-left">
							User Interactions
						</div>
						<div class="top-bar-right">
                            <input id="dateRangeUserInteractions" name="date_range" />
                            <input type="hidden" name="min_date" value="{{ date('Y-m-d', $min_date) }}" />
                            <input type="hidden" name="max_date" value="{{ date('Y-m-d', $max_date) }}" />
							<button class="button btnDownload small" action="/data/stories/user_interactions/csv/all">Download Full Report</button>
						</div>
					</div>
					<div class="table-scroll">
						<table id="dataStoriesUserInteractions" class="report tiny hover expanded">
				            <thead>
				                <tr>
				                	<th>Article Title</th>
				                	<th>Total Page Views</th>
				                    <th>Comments</th>
                                    <th>Republish</th>
				                    <th>Email Shares</th>
				                    <th>Tweets</th>
				                    <th>FB Shares</th>
				                    <th>Total Shares</th>
				                    <th>Share Rate</th>
                                    <th>Tribpedia Clicks</th>
				                    <th>Related Content Clicks</th>
                                    <th>Total Related Clicks</th>
				                    <th>Click Through Rate</th>
				                </tr>
				            </thead>
				            <tbody>
				            </tbody>
				        </table>
			        </div>
		        </div>
			</div>
		</div>
    @else
		<div class="small-12 column">
	        <div>Data Users Coming soon.</div>
	    </div>
	@endif
@endsection



@section('script')
<script>
    ReportDataTable = {};
	$(function(){
        $('#dateRangeScrollDepth').daterangepicker({
            dateFormat: 'M d, yy',
            presetRanges: [],
            datepickerOptions: {
                minDate: moment('{{$date_range_min}}').toDate(),
                maxDate: moment('{{$date_range_max}}').toDate(),
                numberOfMonths: 1,
                showOtherMonths: true,
                selectOtherMonths: true,
                onSelect: function(date, el){
                    var panel = $('#dateRangeScrollDepth').parents('.panel');
                        min_date = moment(date, 'MM/DD/YYYY').day(0),
                        max_date = moment(date, 'MM/DD/YYYY').day(6);
                    $('#dateRangeScrollDepth').daterangepicker('setRange', {
                        start: min_date.toDate(),
                        end: max_date.toDate()
                    });
                    $('#dateRangeScrollDepth').daterangepicker('close');
                    $('input[name="min_date"]', panel).val(min_date.format('YYYY-MM-DD'));
                    $('input[name="max_date"]', panel).val(min_date.format('YYYY-MM-DD'));
                    panel.trigger('change.daterange');
                }
            }
        });
        $('#dateRangeTimeOnArticle').daterangepicker({
            dateFormat: 'M d, yy',
            presetRanges: [],
            datepickerOptions: {
                minDate: moment('{{$date_range_min}}').toDate(),
                maxDate: moment('{{$date_range_max}}').toDate(),
                numberOfMonths: 1,
                showOtherMonths: true,
                selectOtherMonths: true,
                onSelect: function(date, el){
                    var panel = $('#dateRangeTimeOnArticle').parents('.panel');
                        min_date = moment(date, 'MM/DD/YYYY').day(0),
                        max_date = moment(date, 'MM/DD/YYYY').day(6);
                    $('#dateRangeTimeOnArticle').daterangepicker('setRange', {
                        start: min_date.toDate(),
                        end: max_date.toDate()
                    });
                    $('#dateRangeTimeOnArticle').daterangepicker('close');
                    $('input[name="min_date"]', panel).val(min_date.format('YYYY-MM-DD'));
                    $('input[name="max_date"]', panel).val(min_date.format('YYYY-MM-DD'));
                    panel.trigger('change.daterange');
                }
            }
        });
        $('#dateRangeUserInteractions').daterangepicker({
            dateFormat: 'M d, yy',
            presetRanges: [],
            datepickerOptions: {
                minDate: moment('{{$date_range_min}}').toDate(),
                maxDate: moment('{{$date_range_max}}').toDate(),
                numberOfMonths: 1,
                showOtherMonths: true,
                selectOtherMonths: true,
                onSelect: function(date, el){
                    var panel = $('#dateRangeUserInteractions').parents('.panel');
                        min_date = moment(date, 'MM/DD/YYYY').day(0),
                        max_date = moment(date, 'MM/DD/YYYY').day(6);
                    $('#dateRangeUserInteractions').daterangepicker('setRange', {
                        start: min_date.toDate(),
                        end: max_date.toDate()
                    });
                    $('#dateRangeUserInteractions').daterangepicker('close');
                    $('input[name="min_date"]', panel).val(min_date.format('YYYY-MM-DD'));
                    $('input[name="max_date"]', panel).val(min_date.format('YYYY-MM-DD'));
                    panel.trigger('change.daterange');
                }
            }
        });
        /**
         * set default range
         */
        if(!$('#dateRangeScrollDepth').daterangepicker('getRange')){
            $('#dateRangeScrollDepth').daterangepicker('setRange', {
                start: moment('{{date('Y-m-d', $min_date)}}').toDate(),
                end: moment('{{date('Y-m-d', $max_date)}}').toDate()
            });
        }
        if(!$('#dateRangeTimeOnArticle').daterangepicker('getRange')){
            $('#dateRangeTimeOnArticle').daterangepicker('setRange', {
                start: moment('{{date('Y-m-d', $min_date)}}').toDate(),
                end: moment('{{date('Y-m-d', $max_date)}}').toDate()
            });
        }
        if(!$('#dateRangeUserInteractions').daterangepicker('getRange')){
            $('#dateRangeUserInteractions').daterangepicker('setRange', {
                start: moment('{{date('Y-m-d', $min_date)}}').toDate(),
                end: moment('{{date('Y-m-d', $max_date)}}').toDate()
            });
        }
		ReportDataTable['dataStoriesScrollDepth'] = $('#dataStoriesScrollDepth').DataTable({
            'processing': true,
            'serverSide': true,
            'searching': false,
            "scrollX": true,
            "order": [[ 1, "desc" ]],
            'ajax': {
	            'url': '/data/stories/scroll_depth/',
                'beforeSend': function(xhr, settings){
                    settings.url += $('#dataStoriesScrollDepth').attr('mode');
                },
	            'type': 'POST',
	            'data': function(data){
	            	var panel = $('#dataStoriesScrollDepth').parents('.panel');
	            	return $.extend({
	            		'min_date': $('[name="min_date"]', panel).val(),
						'max_date': $('[name="max_date"]', panel).val(),
	            	}, data);
	            }
	        },
            'dom': 'Bfrtip',
            'columns': [{
                
            }, {
                'data': 'Pageviews'
            }, {
                'data': 'StartedScrolling'
            }, {
                'data': 'Scroll25'
            }, {
                'data': 'Scroll50'
            }, {
                'data': 'Scroll75'
            }, {
                'data': 'Scroll100'
            }, {
                'data': 'RelatedContent'
            }, {
                'data': 'EndOfPage'
            }],
            'columnDefs': [{
                'targets': 0,
                'width': 320,
                'render': function(data, type, row){
                	var url = '{{$client['website']}}' + row.Combo_URL;
                    var displayText = row.Article ? row.Article : url;
                	return '<a href="' + url + '" title="' + url + '" target="_blank;" data-tooltip aria-haspopup="true" class="has-tip top">' + displayText + '</a>';
                }
            }, {
                'targets': 1,
                'width': 60,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data);
                }
            }, {
                'targets': 2,
                'width': 60,
                'render': function (data, type, row) {
                    if($('#dataStoriesScrollDepth').attr('mode') == 'count'){
                        return new Intl.NumberFormat().format(data);
                    }
                    return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 3,
                'width': 60,
                'render': function (data, type, row) {
                    if($('#dataStoriesScrollDepth').attr('mode') == 'count'){
                        return new Intl.NumberFormat().format(data);
                    }
                    return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 4,
                'width': 60,
                'render': function (data, type, row) {
                    if($('#dataStoriesScrollDepth').attr('mode') == 'count'){
                        return new Intl.NumberFormat().format(data);
                    }
                    return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 5,
                'width': 60,
                'render': function (data, type, row) {
                    if($('#dataStoriesScrollDepth').attr('mode') == 'count'){
                        return new Intl.NumberFormat().format(data);
                    }
                	return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 6,
                'width': 60,
                'render': function (data, type, row) {
                    if($('#dataStoriesScrollDepth').attr('mode') == 'count'){
                        return new Intl.NumberFormat().format(data);
                    }
                    return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 7,
                'width': 60,
                'render': function (data, type, row) {
                    if($('#dataStoriesScrollDepth').attr('mode') == 'count'){
                        return new Intl.NumberFormat().format(data);
                    }
                    return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 8,
                
                'render': function (data, type, row) {
                    if($('#dataStoriesScrollDepth').attr('mode') == 'count'){
                        return new Intl.NumberFormat().format(data);
                    }
                    return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            } ]
        });
        ReportDataTable['dataStoriesTimeOnArticle'] = $('#dataStoriesTimeOnArticle').DataTable({
            'processing': true,
            'serverSide': true,
            'searching': false,
            "scrollX": true,
            "order": [[ 1, "desc" ]],
            'ajax': {
	            'url': '/data/stories/time_on_article/',
                'beforeSend': function(xhr, settings){
                    settings.url += $('#dataStoriesTimeOnArticle').attr('mode');
                },
	            'type': 'POST',
	            'data': function(data){
                    var panel = $('#dataStoriesTimeOnArticle').parents('.panel');
	            	return $.extend({
	            		'min_date': $('[name="min_date"]', panel).val(),
						'max_date': $('[name="max_date"]', panel).val(),
	            	}, data);
	            }
	        },
            'dom': 'Bfrtip',
            'columns': [{
                
            }, {
                'data': 'Pageviews'
            }, {
                'data': 'Time15'
            }, {
                'data': 'Time30'
            }, {
                'data': 'Time45'
            }, {
                'data': 'Time60'
            }, {
                'data': 'Time75'
            }, {
                'data': 'Time90'
            }],
            'columnDefs': [{
                'targets': 0,
                'width': 320,
                'render': function(data, type, row){
                    var url = '{{$client['website']}}' + row.Combo_URL;
                    var displayText = row.Article ? row.Article : url;
                    return '<a href="' + url + '" title="' + url + '" target="_blank;" data-tooltip aria-haspopup="true" class="has-tip top">' + displayText + '</a>';
                }
            }, {
                'targets': 1,
                'width': 60,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 2,
                'width': 60,
                'render': function (data, type, row) {
                    if($('#dataStoriesTimeOnArticle').attr('mode') == 'count'){
                        return new Intl.NumberFormat().format(data);
                    }
                	return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 3,
                'width': 60,
                'render': function (data, type, row) {
                    if($('#dataStoriesTimeOnArticle').attr('mode') == 'count'){
                        return new Intl.NumberFormat().format(data);
                    }
                	return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 4,
                'width': 60,
                'render': function (data, type, row) {
                    if($('#dataStoriesTimeOnArticle').attr('mode') == 'count'){
                        return new Intl.NumberFormat().format(data);
                    }
                	return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 5,
                'width': 60,
                'render': function (data, type, row) {
                    if($('#dataStoriesTimeOnArticle').attr('mode') == 'count'){
                        return new Intl.NumberFormat().format(data);
                    }
                	return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 6,
                'width': 60,
                'render': function (data, type, row) {
                    if($('#dataStoriesTimeOnArticle').attr('mode') == 'count'){
                        return new Intl.NumberFormat().format(data);
                    }
                	return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 7,
                
                'render': function (data, type, row) {
                    if($('#dataStoriesTimeOnArticle').attr('mode') == 'count'){
                        return new Intl.NumberFormat().format(data);
                    }
                	return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }]
        });
        ReportDataTable['dataStoriesUserInteractions'] = $('#dataStoriesUserInteractions').DataTable({
            'processing': true,
            'serverSide': true,
            'searching': false,
            "scrollX": true,
            "order": [[ 1, "desc" ]],
            'ajax': {
	            'url': '/data/stories/user_interactions',
	            'type': 'POST',
	            'data': function(data){
                    var panel = $('#dataStoriesUserInteractions').parents('.panel');
	            	return $.extend({
	            		'min_date': $('[name="min_date"]', panel).val(),
						'max_date': $('[name="max_date"]', panel).val(),
	            	}, data);
	            }
	        },
            'dom': 'Bfrtip',
            'columns': [{
                
            }, {
                'data': 'Pageviews'
            }, {
                'data': 'Comments'
            }, {
                'data': 'Republish'
            }, {
                'data': 'Emails'
            }, {
                'data': 'Tweets'
            }, {
                'data': 'Facebook_Recommendations'
            }, {
                'data': 'TotalShares'
            }, {
                'data': 'SahreRate'
            }, {
                'data': 'Tribpedia_Related_Clicks'
            }, {
                'data': 'Related_Clicks'
            }, {
                'data': 'Total_Related_Clicks'
            }, {
                'data': 'ClickThroughRate'
            }],
            'columnDefs': [{
                'targets': 0,
                'width': 320,
                'render': function(data, type, row){
                    var url = '{{$client['website']}}' + row.Combo_URL;
                    var displayText = row.Article ? row.Article : url;
                    return '<a href="' + url + '" title="' + url + '" target="_blank;" data-tooltip aria-haspopup="true" class="has-tip top">' + displayText + '</a>';
                }
            }, {
                'targets': 1,
                'width': 60,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 2,
                'width': 60,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 3,
                'width': 60,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 4,
                'width': 60,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 5,
                'width': 60,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 6,
                'width': 60,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 7,
                'width': 60,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 8,
                'width': 60,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 9,
                'width': 60,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 10,
                'width': 60,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 11,
                
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 12,
                
                'render': function (data, type, row) {
                    return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }]
        });

        $('#dataStoriesScrollDepth, #dataStoriesTimeOnArticle, #dataStoriesUserInteractions').on('draw.dt', function(){
        	$(document).foundation();
        });

        $(document).on('click', '.btnSwitcher', function(){
            if($(this).attr('checked')){
                return false;
            }
            var panel = $(this).parents('.panel');
            panel.find('.btnSwitcher').attr('checked', false).toggleClass('on');
            $(this).attr('checked', true);
            var table = panel.find('table[id]');
            table.attr('mode', $(this).attr('mode'));
            ReportDataTable[table.attr('id')].ajax.reload();
            return false;
        });
	});
</script>
@endsection