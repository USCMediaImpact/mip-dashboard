@inject('formatter', 'App\Helpers\FormatterHelper')
@extends('layouts.main')

@section('content')
	@if ($have_data)
		<form id="form_data_quality" method="GET">
		<?php echo csrf_field(); ?>
		<div class="row expanded">
			<div class="column small-9">
				<h4 class="title">Weekly Story Performance</h4>
				<h6 class="sub-title">with Data from Oracle Eloqua</h6>
			</div>
			<div class="column small-3 align-self-bottom">
				@include('widgets.daterange', ['min_date' => $min_date, 'max_date' => $max_date])
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
							<div class="button-group tiny">
								<button class="button btnSwitcher disabled" mode="percent">Percent</button>
								<button class="button btnSwitcher " mode="count">Count</button>
								<span>&nbsp;</span>
								<button class="button small">Download</button>
							</div>
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
							<div class="button-group tiny">
								<button class="button btnSwitcher disabled" mode="percent">Percent</button>
                                <button class="button btnSwitcher " mode="count">Count</button>
								<span>&nbsp;</span>
								<button class="button small">Download</button>
							</div>
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
							<button class="button">Download</button>
						</div>
					</div>
					<div class="table-scroll">
						<table id="dataStoriesUserInteractions" class="report tiny hover expanded">
				            <thead>
				                <tr>
				                	<th>Article Title</th>
				                	<th>Total Page Views</th>
				                    <th>Comments</th>
				                    <th>Email Shares</th>
				                    <th>Tweets</th>
				                    <th>FB Shares</th>
				                    <th>Total Shares</th>
				                    <th>Share Rate</th>
				                    <th>Related Content Clicks</th>
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
	    </form>
    @else
		<div class="small-12 column">
	        <div>Data Users Coming soon.</div>
	    </div>
	@endif
@endsection



@section('script')
<script>
	DefaultDateRangePickerOptions = {
		presetRanges: [],
		datepickerOptions: {
			numberOfMonths: 1,
			showOtherMonths: true,
  			selectOtherMonths: true,
			onSelect: function(date, el){
				var min_date = moment(date, 'MM/DD/YYYY').day(0),
					max_date = moment(date, 'MM/DD/YYYY').day(6);					
				$('#dateRange').daterangepicker('setRange', {
					start: min_date.toDate(),
					end: max_date.toDate()
				});
				$('#dateRange').daterangepicker('close');
				$('input[name="min_date"]').val(min_date.format('YYYY-MM-DD'));
				$('input[name="max_date"]').val(min_date.format('YYYY-MM-DD'));
				
			}
		}
	};

	$(function(){
		var dataTable = {};
		dataTable['dataStoriesScrollDepth'] = $('#dataStoriesScrollDepth').DataTable({
            'processing': true,
            'serverSide': true,
            'searching': false,
            'ajax': {
	            'url': '/data/stories/scroll_depth/',
                'beforeSend': function(xhr, settings){
                    settings.url += $('#dataStoriesScrollDepth').attr('mode');
                },
	            'type': 'POST',
	            'data': function(data){
	            	console.log(data);
	            	return $.extend({
	            		'min_date': $('[name="min_date"]').val(),
						'max_date': $('[name="max_date"]').val(),
	            	}, data);
	            }
	        },
            'dom': 'Bfrtip',
            'columns': [{
                'data': 'Article'
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
                'render': function(data, type, row){
                	var url = '{{$client['website']}}' + row.Page_Path;
                	return '<a href="' + url + '" title="' + url + '" target="_blank;" data-tooltip aria-haspopup="true" class="has-tip top">' + row.Article + '</a>';
                }
            }, {
                'targets': 1,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data);
                }
            }, {
                'targets': 2,
                'render': function (data, type, row) {
                    if($('#dataStoriesScrollDepth').attr('mode') == 'count'){
                        return new Intl.NumberFormat().format(data);
                    }
                    return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 3,
                'render': function (data, type, row) {
                    if($('#dataStoriesScrollDepth').attr('mode') == 'count'){
                        return new Intl.NumberFormat().format(data);
                    }
                    return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 4,
                'render': function (data, type, row) {
                    if($('#dataStoriesScrollDepth').attr('mode') == 'count'){
                        return new Intl.NumberFormat().format(data);
                    }
                    return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 5,
                'render': function (data, type, row) {
                    if($('#dataStoriesScrollDepth').attr('mode') == 'count'){
                        return new Intl.NumberFormat().format(data);
                    }
                	return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 6,
                'render': function (data, type, row) {
                    if($('#dataStoriesScrollDepth').attr('mode') == 'count'){
                        return new Intl.NumberFormat().format(data);
                    }
                    return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 7,
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
        dataTable['dataStoriesTimeOnArticle'] = $('#dataStoriesTimeOnArticle').DataTable({
            'processing': true,
            'serverSide': true,
            'searching': false,
            'ajax': {
	            'url': '/data/stories/time_on_article/',
                'beforeSend': function(xhr, settings){
                    settings.url += $('#dataStoriesTimeOnArticle').attr('mode');
                },
	            'type': 'POST',
	            'data': function(data){
	            	return $.extend({
	            		'min_date': $('[name="min_date"]').val(),
						'max_date': $('[name="max_date"]').val(),
	            	}, data);
	            }
	        },
            'dom': 'Bfrtip',
            'columns': [{
                'data': 'Article'
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
                'render': function(data, type, row){
                	var url = '{{$client['website']}}' + row.Page_Path;
                	return '<a href="' + url + '" title="' + url + '" target="_blank;" data-tooltip aria-haspopup="true" class="has-tip top">' + row.Article + '</a>';
                }
            }, {
                'targets': 1,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 2,
                'render': function (data, type, row) {
                    if($('#dataStoriesTimeOnArticle').attr('mode') == 'count'){
                        return new Intl.NumberFormat().format(data);
                    }
                	return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 3,
                'render': function (data, type, row) {
                    if($('#dataStoriesTimeOnArticle').attr('mode') == 'count'){
                        return new Intl.NumberFormat().format(data);
                    }
                	return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 4,
                'render': function (data, type, row) {
                    if($('#dataStoriesTimeOnArticle').attr('mode') == 'count'){
                        return new Intl.NumberFormat().format(data);
                    }
                	return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 5,
                'render': function (data, type, row) {
                    if($('#dataStoriesTimeOnArticle').attr('mode') == 'count'){
                        return new Intl.NumberFormat().format(data);
                    }
                	return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 6,
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
        dataTable['dataStoriesUserInteractions'] = $('#dataStoriesUserInteractions').DataTable({
            'processing': true,
            'serverSide': true,
            'searching': false,
            'ajax': {
	            'url': '/data/stories/user_interactions',
	            'type': 'POST',
	            'data': function(data){
	            	return $.extend({
	            		'min_date': $('[name="min_date"]').val(),
						'max_date': $('[name="max_date"]').val(),
	            	}, data);
	            }
	        },
            'dom': 'Bfrtip',
            'columns': [{
                'data': 'Article'
            }, {
                'data': 'Pageviews'
            }, {
                'data': 'Comments'
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
                'data': 'Related_Clicks'
            }, {
                'data': 'ClickThroughRate'
            }],
            'columnDefs': [{
                'targets': 0,
                'render': function(data, type, row){
                	var url = '{{$client['website']}}' + row.Page_Path;
                	return '<a href="' + url + '" title="' + url + '" target="_blank;" data-tooltip aria-haspopup="true" class="has-tip top">' + row.Article + '</a>';
                }
            }, {
                'targets': 1,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 2,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 3,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 4,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 5,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 6,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 7,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }, {
                'targets': 8,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat().format(data)
                }
            }, {
                'targets': 9,
                'render': function (data, type, row) {
                    return new Intl.NumberFormat('en-US', {style: 'percent', minimumFractionDigits: 0}).format(data);
                }
            }]
        });

        $(document).on('change.daterange', function(){
			$.each(dataTable, function(k, v){
				v.ajax.reload();
			});
        });

        $(document).on('click', '.btnDownload', function(){
            var action = $(this).attr('action'),
                downloadForm = $('form', {action: action, method: 'POST', target: '_self'});
            downloadForm.append($('[name="min_date"]').clone());
            downloadForm.append($('[name="max_date"]').clone());
            downloadForm[0].submit();
        });

        $('#dataStoriesScrollDepth, #dataStoriesTimeOnArticle, #dataStoriesUserInteractions').on('draw.dt', function(){
        	// Foundation.reInit('tooltip');
        	$(document).foundation();
        });

        $(document).on('click', '.btnSwitcher', function(){
            //$(this).parents('div').find('.btnSwitcher')
            if($(this).attr('checked')){
                return false;
            }
            var panel = $(this).parents('.panel');
            panel.find('.btnSwitcher').attr('checked', false).toggleClass('disabled');
            $(this).attr('checked', true);
            var table = panel.find('table');
            table.attr('mode', $(this).attr('mode'));
            dataTable[table.attr('id')].ajax.reload();
            return false;
        });
	});
</script>
@endsection